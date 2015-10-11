<?php
namespace backend\controllers;

use yii\base\Controller;
use yii\web\HttpException;

use app\Entity;
use app\JsonPackage;
use app\TrEnc;

use common\models\Document;

use yii;
use backend\components\UiMessage;
use yii\base\Object;

class DocumentController extends BaseController {
	
	public function actionIndex() {
		$request = \Yii::$app->request;
		$result = "";
		switch($request->method) {
			case 'DELETE':
				$result = $this->doDelete();
				break;
			case 'GET':
				$result = $this->doQuery();
				break;
			case 'POST':
				$result = $this->saveData();
				break;
		}
	
		echo $result;
	}
	
	public function actionDownload(){
		
		$request = \Yii::$app->request;
		$params = [];
		$params[1] = $request->get('1');
		$params[2] = $request->get('2');
		$params[3] = (int)$request->get('3');
		
		$model = New Document();
		
		$document = $model->findByParams($params);
		
		
		// check for view permission
		if($document == null)
			throw new HttpException(404);
		
		$entity = Entity::getInstance($document->type, $document->refId);
		
		if ($entity == null)
			throw new HttpException(404);
		
		$filePath = \Yii::$app->params['document.basePath'] . Entity::$arrType[$document->type] . '/' . $document->srcPath;
		
		if (!is_readable($filePath)) {
			$filePath = \Yii::$app->params['document.basePath2'] . Entity::$arrType[$document->type] . '/' . $document->srcPath;
		}
		
		if (!file_exists($filePath)) {
			throw new HttpException(404);
		}
		
		$response = \Yii::$app->getResponse();
		$response->format =  \yii\web\Response::FORMAT_RAW;
		
		return \Yii::$app->response->sendFile($filePath);
		
	}
	
	
	
	public function actionGetitems(){
		$request = \Yii::$app->request;
		
		$refId = $request->post('id', 0);
		if (empty($refId))
			$refId = $request->get('id', 0);
		
		$entityType = $request->post('entity', 0);
		if (empty($entityType))
			$entityType = $request->get('entity', 0);
		
		$query = Document::find();
		$query->andWhere(['type'=> $entityType, 'refId' => $refId]);
		$lst = $query->all();
		
		$result = [];
		if($lst){
			foreach ($lst as $object){
				$arr = explode(".", $object->srcPath);
				$extension = $arr[count($arr) - 1];
				$thumbnail = $extension.".png";
				$result[] = $object->attributes + ['thumbnail'=> $thumbnail];
			}
		}
		
		\Yii::$app->response->format = 'json';
    	echo json_encode($result);
	}
	
	public function actionInfo(){
		$request = \Yii::$app->request;
		$type = $request->get('1', '');
		$refId = $request->get('2','');
		$itemNo = (int)$request->get('3',0);
		
		$document = Document::findOne(['type'=>$type, 'refId'=>$refId, 'itemNo'=>$itemNo]);
		
		if ($document == null){
			throw new HttpException(404);
		}
		
		$result = $document->getInfo($request->bodyParams);
		if (!empty($result)){
			foreach ($result as $key=>$value){
				if ($value == null){
					$result[$key] = "";
				}
			}
		}
		
		\Yii::$app->response->format = 'json';
		echo json_encode($result);
	}
	
	
	public function actionUpload() {
		
		\Yii::$app->response->format = 'json';
		$request = \Yii::$app->request;
		$fileInfo = $_FILES['files'];
		$extension = '';
		$document = null;
	
		if (!empty($fileInfo['tmp_name'][0]) && empty($fileInfo['error'][0])) {
			$arrName = explode(".", $fileInfo['name'][0]);
			$index = count($arrName) - 1;
			$extension = $arrName[$index];
			$document = new Document();
			$document = $document->newItem($request->post('entity'), $request->post('id'), $_FILES['files']['tmp_name'][0], $fileInfo['name'][0]);
			$document->save();
		}
		
		if ($document != null) {
					
			echo json_encode(array(
					'itemNo' => $document->itemNo,
					'fileName'=> $document->fileName,
					'iconSrc'=>\Yii::getAlias('@web')."/global/img/".$extension.".png",
					
						
			));
		}
		else {
			header('500 Internal Server Error');
			echo '"Cannot upload file"';
		}
	}
	
	
	public function actionView() {
		$request = \Yii::$app->request;
		
		$enc = new TrEnc(\Yii::$app->params['crypto'][0][0], \Yii::$app->params['crypto'][0][1]);
		$encrypted = substr($request->get('key'), 0, strrpos($request->get('key'), '.'));
		
		$params = $enc->decode($encrypted);
		
		$flag = $request->get('falg', '');
		if ($flag) {
			switch ($flag) {
				case '@':
					$this->displayParams($params);
					break;
			}
			return;
		}
		$model = New Document();
		
		$document = $model->findByParams($params);
		
		// check for view permission
		if($document == null)
			throw new HttpException(404);
		
		$entity = Entity::getInstance($document->type, $document->refId);
		
		if ($entity == null)
			throw new HttpException(404);
		
		$filePath = \Yii::$app->params['document.basePath'] . Entity::$arrType[$document->type] . '/' . $document->srcPath;
		
		if (!is_readable($filePath)) {
			$filePath = \Yii::$app->params['document.basePath2'] . Entity::$arrType[$document->type] . '/' . $document->srcPath;
		}
		
		if (!file_exists($filePath)) {
			throw new HttpException(404);
		}
		
		$response = \Yii::$app->getResponse();
		$response->headers->set('Content-Type', $document->mime);
		$response->format =  \yii\web\Response::FORMAT_RAW;
		
		
		echo file_get_contents($filePath);
		
		
	}
	
	private function displayParams($params) {
		$arrParamMap = array(
				1 => 'ENCODE_ENTITY',
				'ENCODE_ID',
				'ENCODE_ITEMNO',
		);
		$arr = array();
		foreach($params as $code => $value) {
			$arr[$arrParamMap[$code]] = $value;
		}
		\Yii::$app->response->format = 'json';
		echo json_encode($arr);
	}
	
	private function doDelete() {
		$request = \Yii::$app->request;
		$model = new Document();
		$document = $model->findByParams($request->bodyParams);
		if ($document == null)
			throw new HttpException(404);
	
		$document->delete();
		return 'ลบเอกสารเรียบร้อยแล้ว';
	}
	
	private function doQuery() {
		$request = \Yii::$app->request;
		$id = $request->post('id',$request->get('id',''));
	
		//เพิ่มการแสดงรูปสำหรับ type อื่น เช่น กิจกรรม
		$type = Entity::TYPE_CONTENT;
		if($request->post('entity')) {
			$request->post('entity');
		}
	
		$items = array();
	
		$query = Document::find();
		$query->andWhere('refId=:id AND type=:type', [':id'=> $id, ':type'=>$type]);
		$items = Document::getItems($query, $options);
	
		\Yii::$app->response->format = 'json';
		echo json_encode($items);
	}
	
	private function saveData() {
		$request = \Yii::$app->request;
		$arrParams = $request->bodyParams;
		$arrPimaryKey = [];
	
		//type
		if(!empty($arrParams[1])){
			$arrPimaryKey['type'] = $arrParams[1];
		}
	
		//refId
		if(!empty($arrParams[2])){
			$arrPimaryKey['refId'] = $arrParams[2];
		}
	
		//itemNo
		if(!empty($arrParams[3])){
			$arrPimaryKey['itemNo'] = $arrParams[3];
		}
	
		$document = Document::findOne($arrPimaryKey);
	
		if ($document == null)
			throw new HttpException(404);
	
		$document->caption = $request->post('caption');
		$document->tags = $request->post('tags');
	
		if ($document->save())
			return 'บันทึกข้อมูลเอกสารแล้ว';
		else {
			throw new HttpException(500, join("\n", $document->getErrors()));
		}
	}
	
	
}