<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;

use common\models\pond;
use common\models\Typelist;
use common\models\pondRef;
use common\models\Media;
use app\CategoryTree;
use app\Workflow;
use app\JsonPackage;
use common\models\Feed;
use yii\helpers\ArrayHelper;
use app\Ui;
use app\Entity;
use app\DateUtil;
use common\models\User;
use common\models\pondPublish;
use app\TpbsLog;
use common\models\Document;
use app\TrEnc;
use app\CmsTextUtil;
use common\models\OtherCategory;
use yii\base\Object;
use backend\components\UiMessage;

/**
 * Test controller
 */
class PondController extends BaseController {

    public function actionIndex() {
        echo $this->render('index');
    }
    public function actionTypelist() {
    	
    		$currentTs =time();
    		$request = Yii::$app->request;
    		$identity = \Yii::$app->user->getIdentity();
    	
    		$searchCategory = $request->post('type', $request->get('type', ''));
    		$searchStatus = $request->post('status', $request->get('status', ''));
    		$q = trim($request->post('q', $request->get('q', '')));
    	
    		$query = Typelist::find();
    		$query->orderBy(['id'=>SORT_ASC]);
    	
    		if ($searchCategory)
    			$query->andWhere('type = :type',[':type' => $searchCategory]);
    			
    	
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    			
    		if ($q)
    			$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    	
    	
    		//actions
    		switch ($request->post('op')){
    			case 'publish':
    				var_dump($model);exit;
    				$model->status = Workflow::STATUS_PUBLISHED;
    				$model->save();
    				break;
    			case 'unpublish':
    				$model->status = Workflow::STATUS_REJECTED;
    				$model->save();
    				break;
    			case 'delete':
    				$this->doDelete();
    				break;
    		}
    	
    		//paging
    		$pagination = new Pagination([
    				'defaultPageSize' => \Yii::$app->params['ui']['defaultPageSize'],
    				'totalCount' => $query->count(),
    		]);
    		$pagination->params = ['status'=>$searchStatus,
    				'categoryId'=>$searchCategory,
    				'q'=>$q,
    				'page'=>$pagination->page,
    		];
    		$query->offset($pagination->offset);
    		$query->limit($pagination->limit);
    	
    		$list = $query->all();
    	
    		//get users
    		$arrId = [];
    		$arrUser = [];
    		if (!empty($list)){
    			foreach ($list as $obj){
    				$arrId[] = $obj->createBy;
    			}
    			$modelsUser = User::find()->where(['id'=>$arrId])->all();
    			if(!empty($modelsUser)){
    				foreach ($modelsUser as $obj){
    					$arrUser[$obj->id] = $obj->firstName.' '.$obj->lastName;
    				}
    			}
    		}
    			
    		echo $this->render('typelist', [
    				'lst' => $list,
    				'pagination' => $pagination,
    				'arrUser' =>$arrUser,
    				'q'=>$q,
    		]);
    }
    
    public function actionEdittype()
    {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = Typelist::find();
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    
    
    	}else{
    		$model = new Typelist();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    
    	if($request->isPost){
    		$model->name = $_POST['Typelist']['name'];
    		$model->size =$_POST['Typelist']['size'];
    
    		if (trim($model->name) == ''){
    			$model->addError('name', 'ไม่ได้กรอกชื่อบ่อ');
    		}

    		if (trim($model->size) == ''){
    			$model->addError('size', 'ไม่ได้กรอกขนาดบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			//UiMessage::setMessage('บันทึกข้อมูลเรียบร้อยแล้ว');
    			return $this->redirect('typelist');
    		}
    		else {
    			$modelError = '';
    			$errors = $model->getErrors(null);
    			if (is_array($errors)) {
    				foreach($errors as $field => $fieldError) {
    					$modelError .= "\n$field: " . join(', ', $fieldError);
    				}
    			}
    			UiMessage::setMessage('การบันทึกข้อมูลผิดพลาด:' . $modelError, 'warning');
    		}
    
    	}
    
    	echo $this->render('edittype', [
    			'model' => $model,
    	]);
    }
    
    private function doDeleteType() {
    
    	$currentTs =time();
    	$identity = \Yii::$app->user->getIdentity();
    
    	$arrIds = \Yii::$app->request->post('ids');
    	if (is_array($arrIds) && !empty($arrIds)) {
    		$query = Phonebook::find();
    		$query->where(["id"=> $arrIds]);
    		$lst = $query->all();
    
    		$deleted = 0;
    
    		if($lst){
    			foreach ($lst as $Object){
    				$Object->status = Workflow::STATUS_REJECTED;
    				if($Object->save()){
    					Yii::info(json_encode(array(
    							'id'=>$Object->id,
    							'userId'=>$identity->id,
    							'status'=>$Object->status,
    							'ts' => $currentTs,
    					)), 'audit.faq.update.'.$Object->id);
    					$deleted = $deleted + 1;
    				}
    			}
    		}
    		if ($deleted > 0) {
    			UiMessage::setMessage("ลบข้อมูลจำนวน $deleted รายการ", 'success');
    		}
    		else {
    			UiMessage::setMessage('ไม่มีข้อมูลถูกลบ');
    		}
    	}
    }
    
    public function actionList() {
    	
    	$canPublishNews = Yii::$app->user->can('tpbs.pond.approve');
    	$request = Yii::$app->request;
    	
    	$op = $request->post('op', '');
    	if (empty($op))
    		$op = $request->get('op', '');
    	switch($op) {
    		case 'delete':
    			$this->doDelete();
    			break;
    	}
    	
    	
    	$status = $request->post('status', '');
    	if (empty($status))
    		$status = $request->get('status', '');
    	
    	$pondType = $request->post('pondType', 0);
    	if (empty($pondType))
    		$pondType = $request->get('pondType', 0);
    	
    	$categoryId = $request->post('categoryId', 0);
    	if (empty($categoryId))
    		$categoryId = $request->get('categoryId', 0);
    	
    	$dateStart = $request->post('dateStart', 0);
    	if (empty($dateStart))
    		$dateStart = $request->get('dateStart', 0);
    	
    	$dateEnd = $request->post('dateEnd', 0);
    	if (empty($dateEnd))
    		$dateEnd = $request->get('dateEnd', 0);
    	
    	$order = $request->post('order', 0);
    	if (empty($order))
    		$order = $request->get('order', 0);
    	
    	$q = $request->post('q', '');
    	if (empty($q))
    		$q = $request->get('q', '');
    	
    	
    	$query = pond::find();
    	
    	if(!empty($status)){
    		$query->andWhere('status=:status', [':status'=> $status]);
    	}

    	if(!empty($pondType)){
    		$query->andWhere('type=:type', [':type'=> $pondType]);
    	}
    	
    	if(!empty($datetStart) && empty($datetEnd)){
    		$query->andWhere(['LIKE', 'createTime', $datetStart]);
    	}
    	
    	if(!empty($dateStart) && !empty($dateEnd)){
    		$query->andWhere(['between','createTime', $dateStart, $dateEnd]);
    	}
    	
    	if(!empty($categoryId) && $categoryId != 0){
    			switch ($pondType) {
    				case pond::TYPE_ONLINE_NEWS:
    					$type = 'news';
    					break;
    				case pond::TYPE_ARTICLE:
    					$type = 'column';
    					break;
    				default:
    					$type = 'news';
    					break;
    			}
    			$node = CategoryTree::getNode($categoryId);
    			$query->andWhere(['in', 'categoryId', $node->getChildren($type,true)]);
    	}
    	
    	$query->orderBy('lastUpdateTime DESC');
    	if(!empty($order) && $order != -1){
    		$query->orderBy("viewCount $order");
    	}
    	
    	if(!empty($q)){
    		$query->andWhere(['LIKE', 'title', $q]);
    	}
    	
    	$pagination = new Pagination([
    			'defaultPageSize' => Yii::$app->params['ui']['defaultPageSize'],
    			'totalCount' => $query->count(),
    			]);

    	$query->offset($pagination->offset);
    	$query->limit($pagination->limit);
    	$lst = $query->all();
   	
    	$pagination->params = ['page'=> $pagination->page, 'status'=>$status, 'pondType'=> $pondType, 'categoryId'=> $categoryId, 'order'=> $order, 'q'=>$q];
    	
		$arrUserId = [];
		$arrUser = [];
		if($lst){
			foreach ($lst as $fields){
				if($fields->lastUpdateBy)
					array_push($arrUserId, $fields->lastUpdateBy);
				else
					array_push($arrUserId, $fields->createBy);
			}
		}
		
		$query = User::find();
		
		if(!empty($arrUserId))
			$query->andWhere(['in', 'id', $arrUserId]);

		$user = $query->all();
		if($user){
			foreach ($user as $object){
				$arrUser[$object->id] = $object->firstName . ' - ' . $object->lastName;
			}
		}
		
		
		
        echo $this->render(
        			'list', 
        			[
        				'lst' => $lst,
        				'arrUser' => $arrUser,
        				'status'=>$status,
        				'pondType'=> $pondType,
        				'categoryId'=> $categoryId,
        				'order'=> $order,
        				'q'=>$q,
        				'pagination'=>$pagination,
        				'canPublishNews'=>$canPublishNews,
					]
				);
    }
    public function actionEdit() {
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	
    	$id = $request->post('id', 0);
    	if (empty($id))
    		$id = $request->get('id', 0);
    	
    	$pond = pond::findOne(['id'=> $id]);
    	
    	if(empty($pond)){
    		$pond = new pond();
    		$pond->createBy = $identity->id;
    		$pond->createTime = date('Y-m-d H:i:s', $currentTs);
    	} 
    	//$pond
    	if ($request->isPost) {
    		$categoryId = $request->post('categoryId', NULL);
    		if (empty($categoryId))
    			$categoryId = $request->get('categoryId', NULL);
    		
    		$hasVideo = $request->post('hasVideo', 0);
			
    		
    		$publishTs = $currentTs;
    		$pond->publishTime = $publishTs?date(DateUtil::SQL_DT_FMT, $publishTs):null;
			
			$pond->attributes = $request->post('pond');
    		$pond->categoryId = $categoryId;
    		$pond->lastUpdateTime = date(DateUtil::SQL_DT_FMT, $currentTs);
    		$pond->lastUpdateBy = $identity->id;
    		
    		$pond->pond = CmsTextUtil::normalize($pond->pond);
    		
    			
    		if($pond->save()) {
    			/* Yii::info(json_encode(array(
					'id'=>$pond->id,
					'userId'=>$identity->id,
					'status'=>$pond->status,
    				'ts' => $currentTs,	
				)), 'audit.pond.update.'.$pond->id); */
    			
    			TpbsLog::info(json_encode(array(
					'entityType'=>Entity::TYPE_pond,
					'refId'=>$pond->id,
					'userId'=>$identity->id,
					'status'=>$pond->status,
    				'ts' => date(DateUtil::SQL_DT_FMT, $currentTs),
				)), 'audit.pond.update');
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    		}else{
    			Ui::setMessage(json_encode($pond->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    	}
    	
		$query = Typelist::find();
		$query->orderBy(['id'=>SORT_ASC]);
		$objTypelist = $query->all();
		$arrTypelist = [];
		foreach ($objTypelist as $dataTypelist){
			$arrTypelist[] = $dataTypelist->name;
		}
		
		
        echo $this->render('edit', [
        								'pond'=> $pond,
        								'arrTypelist'=>$arrTypelist,
       						]);
    }
    
    public function actionSavecategory(){
    	$id = $_REQUEST['pondid'];
    	if(empty($id))
    		return false;
  
    	if(!empty($_REQUEST['items'])){
    		$del = OtherCategory::deleteAll(['refId'=> $id]);
    		foreach ($_REQUEST['items'] as $index=>$data){
    			$lst = new OtherCategory();
    			$lst->refId = $id;
    			$lst->categoryId = $data;
    			$lst->save();
    		}
    	}
    }
    
    public function actionInstagramapi(){
    	$json = file_get_ponds('http://api.instagram.com/oembed?url='.$_REQUEST['objectName']);
    
    	if($json){
    		\Yii::$app->response->format = 'json';
    		echo $json;
    	}else{
    		return false;
    	}
    }
    
    public function actionSaverelated() {
    	$success = array();
    	if(!empty($_REQUEST['ids'])) {
    		foreach ($_REQUEST['ids'] as $index => $data) {
    			switch($data[0]) {
    				case 'article':
    				case 'news':
    				case 'pond':
    					$relType = Entity::TYPE_pond;
    					break;
    				case 'person':
    					$relType = Entity::TYPE_PERSON;
    					break;
    			}
    
    			if(!$index) {
    				$ret = pondRef::deleteAll('pondId=:pondId AND refType=:refType', [':pondId'=> $_REQUEST['pondId'],':refType'=> $relType]);
    			}
    			
    			$query = pondRef::find();
    			$query->andWhere('pondId=:pondId AND refType=:refType AND refId=:refId', [':pondId'=>pondEST['pondId'],':refType'=> $relType,':refId'=> $data[1]]);
    			$rc = $query->all();
    			
    			if($rc == null) {
    				$rc = new pondRef();
    				$rc->pondId = $_REQUEST['pondId'];
    				$rc->refType = $relType;
    				$rc->refId = $data[1];
    				$rc->relationType = pond::RELATIONTYPE_GENERAL;
    				if($rc->save())
    					$success['success'][] = $rc->refId;
    				else
    					$success['fail'][] = $rc->refId;
    
    			}
    		}
    	}else{
    		pondRef::deleteAll('pondId=:pondId', [':pondId'=> $_REQUEST['pondId']]);
    	}
    
    }
    
    public function actionGetpond(){
    	if($_REQUEST['id']){
	    	$model = pond::findOne(['id'=> $_REQUEST['id']]);
	    	\Yii::$app->response->format = 'json';
	    	echo json_encode(['id'=>$model->id,'title'=>$model->title,'viewCount'=>$model->viewCount]);
    	}
    }
    
    public function actionSearchpond(){
    	$request =Yii::$app->request;
    	$q = $request->get('q');
    	
    	if (!empty($q)){
    			
    		$query = pond::find();
    		$query->orderBy('publishTime DESC');
    		$query->andWhere('status=:status', [':status'=> Workflow::STATUS_PUBLISHED]);
    		if(is_numeric($q))
    			$query->andWhere(['LIKE', 'id', $q]);
    		else
    			$query->andWhere(['LIKE', 'title', $q]);
    			
    		$query->limit(30);
    		$lst = $query->all();
    			
    	}else{
			$query = pond::find();
			$query->orderBy('publishTime DESC');
			$query->andWhere('status=:status', [':status'=> Workflow::STATUS_PUBLISHED]);
			$query->limit(30);
			$lst = $query->all();
		}
    	
    	$arrLst = [];
    	foreach ($lst as $data){
    		$arrLst[] = ['id'=>$data['id'],'title'=>$data['title']];
    	}
    	\Yii::$app->response->format = 'json';
    	echo json_encode($arrLst,JSON_UNESCAPED_UNICODE);
    }
    
    public function actionGetitemsimg(){
    
    	$request = \Yii::$app->request;
    	$id = $request->post('id');
    	if(empty($id))
    		$id = $request->get('id');
    	 
    	//เพิ่มการแสดงรูปสำหรับ type อื่น เช่น กิจกรรม
    	//$type = Entity::TYPE_pond;
    	$type = $request->get('entity');
    	 
    	$items = array();
    	$query = Media::find();
    	if(empty($id))
    		$query->andWhere('type=:type', [':type'=>$type]);
    	else
    		$query->andWhere('type=:type AND refId=:refId', [':type'=>$type,':refId'=>$id]);
    	 
    	$options = [Media::ENCODE_WIDTH => 100];
    	$items = Media::getItems($query, $options);
    	 
    	\Yii::$app->response->format = 'json';
    	echo json_encode($items);
    	 
    }
    
    public function  actionSavepondpublish(){
    	$request = \Yii::$app->request;
    	$tweetpond = $request->post('tweetpond', '');
    	$pondId = $request->post('pondId', '');
    
    	$model = pondPublish::find()->where('pondId=:pondId', [':pondId'=>$pondId])->one();
    	if (empty($model)){
    		$model = new pondPublish();
    		$model->pondId = $pondId;
    		$model->twitterBreaking = 1;
    	}
    
    	$model->tweetpond = $tweetpond;
    	$text = "";
    	$result = [];
    	$success = $model->save();
    
    	if ($success){
    		$result['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
    		$result['success'] = true;
    	}else{
    		$modelError = '';
    		$errors = $model->getErrors(null);
    		if (is_array($errors)) {
    			foreach($errors as $field => $fieldError) {
    				$modelError .= "\n$field: " . join(', ', $fieldError);
    			}
    		}
    		$result['message'] = 'การบันทึกข้อมูลผิดพลาด:' . $modelError;
    		$result['success'] = false;
    	}
    
    
    	header('pond-Type: application/json');
    
    	echo json_encode($result);
    }
    
    private function doDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    		
    	if (is_array($arrIds) && !empty($arrIds)) {
    		$query = pond::find();
    		$query->where(["id"=> $arrIds]);
    		$lst = $query->all();
    		//var_dump($lst);exit;
    		if($lst){
    			foreach ($lst as $Object){
    				$Object->status = Workflow::STATUS_REJECTED;
    				if($Object->save()){
    					Yii::info(json_encode(array(
    							'id'=>$Object->id,
    							'userId'=>$identity->id,
    							'status'=>$Object->status,
    							'ts' => $currentTs,
    					)), 'audit.pond.update.'.$Object->id);
    					$deleted = $deleted + 1;
    				}
    			}
    		}
    		if ($deleted > 0) {
    			Ui::setMessage("ลบข้อมูลจำนวน $deleted รายการ" + "บันทึกข้อมูลสำเร็จ");
    		}
    		else {
    			Ui::setMessage('ไม่มีข้อมูลถูกลบ');
    		}
    	}
    }
    
    public function init() {
        parent::init();
        $this->layout = 'layoutstyle';
    }

}
