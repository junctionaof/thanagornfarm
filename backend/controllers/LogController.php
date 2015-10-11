<?php
namespace backend\controllers;
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\PageCache;

use yii\data\ActiveDataProvider;

use app\Ui;
use common\models\User;
use common\models\Auth;
use common\models\UserLogSystem;
use app\Workflow;
use app\Entity;
use common\models\LogSystem;
use common\models\Content;
use common;
use app\components\ExcelGrid;
use yii\base\Widget;
use yii\base\Object;

class LogController extends BaseController{
	
	public function init() {
		parent::init();
		$this->layout = 'layoutstyle';
	}
	

	
	public function actionIndex()
	{
		echo $this->render('index');
	}

	
	public function actionAccesslog()
	{
		$request = \Yii::$app->request;
		
		if (\Yii::$app->request->isPost) {
			/* if ($_REQUEST['op'] == "delete") {
				$arrId = $_REQUEST['selectUser'];
				foreach ($arrId as $lst){
					$queryUser = User::find();
					$User = $queryUser->where(['id' => $lst])->one()->delete();
				}
				echo $this->redirect(Url::toRoute('log/accesslog'));
			}
	
			if ($_REQUEST['op'] == "search") {
				$searchText = $_REQUEST['searchText'];
				$query->andWhere(['LIKE' ,'firstName','%'.$searchText.'%', false]);
			} */
			
			$op = $request->post('op', '');
			if (empty($op))
				$op = $request->get('op', '');
		}
		
		$query = UserLogSystem::find();
	
		$datefrom = $request->post('datefrom', null);
		if (empty($datefrom))
			$datefrom = $request->get('datefrom', null);
		
		$dateto = $request->post('dateto', null);
		if (empty($dateto))
			$dateto = $request->get('dateto', null);
		
		if(!empty($datefrom) && !empty($dateto)){
			$query->andWhere(['between','ts', $datefrom, $dateto]);
		}
		
		$userId = $request->post('userId', '');
		if (empty($userId))
			$userId = $request->get('userId', '');
		
		if(!empty($userId)){
			$query->andWhere('userId=:userId', [':userId'=> $userId]);
		}
	
		$arrSearchParams = ['datefrom'=>$datefrom,
				'dateto'=>$dateto,
				'userId'=>$userId,
		];
		
		
		$pagination = new Pagination([
				'defaultPageSize' => 20,
				'totalCount' => $query->count(),
		]);
	
		$pagination->params = ['page'=> $pagination->page, 'userId'=> $userId, 'datefrom'=> $datefrom, 'dateto'=> $dateto];
		$model = $query
		->orderBy('ts DESC')
		->offset($pagination->offset)
		->limit($pagination->limit)
		->all();
		
		$arrUser = [];
		$query = User::find()->andWhere(['status'=> User::STATUS_ACTIVE])->all();
		foreach ($query as $Object){
			$arrUser[$Object->id] = $Object->firstName .' - '. $Object->lastName;
		}
		
		$arrUserUse = [];
		$arrUserId = [];
		if($model){
			foreach ($model as $Object){
				$arrUserId[$Object->userId] = $Object->userId;
			}
				
			if($arrUserId){
				$query = User::find();
				$query->andWhere(['in', 'id', $arrUserId]);
				$lst = $query->all();
				foreach ($lst as $Object){
					$arrUserUse[$Object->id] = $Object->firstName .' - '. $Object->lastName;
				}
			}
		}
	
		echo $this->render('accesslog',[
								'model' => $model ,
								'pagination'=>$pagination,
								'arrUser' => $arrUser,
								'arrUserUse' => $arrUserUse,
								'arrSearchParams'=>$arrSearchParams,
				
						]);
	}

	
	public function actionContentlog()
	{
		$request = \Yii::$app->request;
		
		if (\Yii::$app->request->isPost) {
			/* if ($_REQUEST['op'] == "delete") {
				$arrId = $_REQUEST['selectUser'];
				foreach ($arrId as $lst){
					$queryUser = User::find();
					$User = $queryUser->where(['id' => $lst])->one()->delete();
				}
				echo $this->redirect(Url::toRoute('log/contentlog'));
			} 
			if ($_REQUEST['op'] == "search") {
				$searchText = $_REQUEST['searchText'];
				$query->andWhere(['LIKE' ,'firstName','%'.$searchText.'%', false]);
			}
			*/
	
			$op = $request->post('op', '');
			if (empty($op))
				$op = $request->get('op', '');

		}
		
		$arrUser = [];
		$query = User::find()->andWhere(['status'=> User::STATUS_ACTIVE])->all();
		foreach ($query as $Object){
			$arrUser[$Object->id] = $Object->firstName .' - '. $Object->lastName;
		}
		
		$query = LogSystem::find();
		$query->andWhere(['entityType'=> Entity::TYPE_CONTENT]);
		
		
		$datefrom = $request->post('datefrom', null);
		if (empty($datefrom))
			$datefrom = $request->get('datefrom', null);
		
		$dateto = $request->post('dateto', null);
		if (empty($dateto))
			$dateto = $request->get('dateto', null);
		
		if(!empty($datefrom) && !empty($dateto)){
			$query->andWhere(['between','ts', $datefrom, $dateto]);
		}
		
		$status = $request->post('status', '');
		if (empty($status))
			$status = $request->get('status', '');
		
		if(!empty($status)){
			$query->andWhere('status=:status', [':status'=> $status]);
		}
		
		$userId = $request->post('userId', '');
		if (empty($userId))
			$userId = $request->get('userId', '');
		
		if(!empty($userId)){
			$query->andWhere('userId=:userId', [':userId'=> $userId]);
		}
		
		$arrSearchParams = ['datefrom'=>$datefrom,
							'dateto'=>$dateto,
							'status'=>$status,
							'userId'=>$userId,
						];
		
		$pagination = new Pagination([
				'defaultPageSize' => 20,
				'totalCount' => $query->count(),
		]);
		$pagination->params = ['page'=> $pagination->page, 'status'=>$status, 'userId'=> $userId, 'datefrom'=> $datefrom, 'dateto'=> $dateto];
	
	
		$model = $query->orderBy('ts DESC')->offset($pagination->offset)->limit($pagination->limit)->all();
		
		$arrUserUse = [];
		$arrContent = [];
		$arrUserId = [];
		$arrContentId = [];
		if($model){
			foreach ($model as $Object){
				$arrUserId[$Object->userId] = $Object->userId;
				$arrContentId[$Object->refId] = $Object->refId;
			}
			
			if($arrUserId){
				$query = User::find();
				$query->andWhere(['in', 'id', $arrUserId]);
				$lst = $query->all();
				foreach ($lst as $Object){
					$arrUserUse[$Object->id] = $Object->firstName .' - '. $Object->lastName;
				}
			}
			
			if($arrContentId){
				$query = Content::find();
				$query->andWhere(['in', 'id', $arrContentId]);
				$lst = $query->all();
				foreach ($lst as $Object){
					$arrContent[$Object->id] = $Object;
				}
			}
			
		}

		echo $this->render('contentlog',[
								'model' => $model , 
								'arrContent' => $arrContent,
								'arrUser' => $arrUser,
								'arrUserUse' => $arrUserUse,
								'arrSearchParams'=>$arrSearchParams,
								'pagination'=>$pagination
		]);
	}
	

	public function actionExportcontentlog() {
		
		$request = \Yii::$app->request;
		$arrUser = [];
		$filename = "";
		$query = User::find()->andWhere(['status'=> User::STATUS_ACTIVE])->all();
		foreach ($query as $Object){
			$arrUser[$Object->id] = $Object->firstName .' - '. $Object->lastName;
		}
		
		$logQuery = LogSystem::find();
		$logQuery->andWhere(['entityType'=> Entity::TYPE_CONTENT]);
		
		
		$datefrom = $request->post('datefrom', null);
		if (empty($datefrom))
			$datefrom = $request->get('datefrom', null);
		
		$dateto = $request->post('dateto', null);
		if (empty($dateto))
			$dateto = $request->get('dateto', null);
		
		if(!empty($datefrom) && !empty($dateto)){
			$logQuery->andWhere(['between','ts', $datefrom, $dateto]);
			$filename .= $datefrom."_".$dateto;
		}
		
		$status = $request->post('status', '');
		if (empty($status))
			$status = $request->get('status', '');
		
		if(!empty($status)){
			$logQuery->andWhere('status=:status', [':status'=> $status]);
			$filename .= "_".Workflow::$arrStatusTpbs[$status];
		}
		
		$userId = $request->post('userId', '');
		if (empty($userId))
			$userId = $request->get('userId', '');
		
		if(!empty($userId)){
			$logQuery->andWhere('userId=:userId', [':userId'=> $userId]);
			$filename .= "_user_".$userId;
		}
		
		$logQuery->orderBy('ts DESC');
		
		$logLst = $logQuery->all();
		
		$arrUserName = [];
		$arrContent = [];
		$arrUserId = [];
		$arrContentId = [];
		
		if($logLst){
			foreach ($logLst as $Object){
				$arrUserId[$Object->userId] = $Object->userId;
				$arrContentId[$Object->refId] = $Object->refId;
			}
				
			if(!empty($arrUserId)){
				$query = User::find();
				$query->andWhere(['in', 'id', $arrUserId]);
				$lst = $query->all();
				foreach ($lst as $Object){
					$arrUserName[$Object->id] = $Object->firstName .' - '. $Object->lastName;
				}
			}
				
			if(!empty($arrContentId)){
				$query = Content::find();
				$query->andWhere(['in', 'id', $arrContentId]);
				$lst = $query->all();
				foreach ($lst as $Object){
					$arrContent[$Object->id] = $Object->title;
				}
			}
				
		}
		
		
		$dataProvider = new ActiveDataProvider([
				'query' => $logQuery,
		]);
		
		$filename = trim($filename, "_");
		$filename = $filename?$filename:"all_content_log";
		$extension = 'xls';
		
		$response = \Yii::$app->response;
		
		$response->headers->set("Cache-Control", "no-cache");
		$response->headers->set("Expires", "0");
		$response->headers->set("Pragma", "no-cache");
		$response->headers->set("Content-Type", "application/{$extension}");
		$response->headers->set("Content-Disposition", "attachment; filename={$filename}.{$extension}");
		
        return $this->renderPartial('excel', [
            'dataProvider' => $dataProvider,
        	'arrUserName'=>$arrUserName,
        	'arrContent'=>$arrContent,
        ]);
	}
	
	public function actionExportaccesslog() {
	
		$request = \Yii::$app->request;
		$logQuery = UserLogSystem::find();
		$filename = "";
		
		$datefrom = $request->post('datefrom', null);
		if (empty($datefrom))
			$datefrom = $request->get('datefrom', null);
		
		$dateto = $request->post('dateto', null);
		if (empty($dateto))
			$dateto = $request->get('dateto', null);
		
		if(!empty($datefrom) && !empty($dateto)){
			$logQuery->andWhere(['between','ts', $datefrom, $dateto]);
			$filename .= $datefrom."_".$dateto;
		}
		
		$userId = $request->post('userId', '');
		if (empty($userId))
			$userId = $request->get('userId', '');
		
		if(!empty($userId)){
			$logQuery->andWhere('userId=:userId', [':userId'=> $userId]);
			
			$user = User::find()->where("id=$userId")->one();
			$filename .= "_".$user->firstName."_".$user->lastName;
		}
		
		$models = $logQuery->orderBy('ts DESC')->all();
		
		
		//find all users' full name
		$arrUserFullName = [];
		$arrUserId = [];
		if($models){
			foreach ($models as $Object){
				$arrUserId[$Object->userId] = $Object->userId;
			}
		
			if($arrUserId){
				$query = User::find();
				$query->andWhere(['in', 'id', $arrUserId]);
				$lst = $query->all();
				foreach ($lst as $Object){
					$arrUserFullName[$Object->id] = $Object->firstName .' - '. $Object->lastName;
				}
			}
		}
		
		
		$dataProvider = new ActiveDataProvider([
				'query' => $logQuery,
				'pagination' => [
						'pageSize' => 5000,
				],
		]);
	
		$filename = trim($filename, "_");
		$filename = $filename?$filename:"all_access_log";
		$extension = 'xls';
	
		$response = \Yii::$app->response;
	
		$response->headers->set("Cache-Control", "no-cache");
		$response->headers->set("Expires", "0");
		$response->headers->set("Pragma", "no-cache");
		$response->headers->set("Content-Type", "application/{$extension}");
		$response->headers->set("Content-Disposition", "attachment; filename={$filename}.{$extension}");
	
		return $this->renderPartial('accessexcel', [
				'dataProvider' => $dataProvider,
				'arrUserFullName'=>$arrUserFullName,
		]);
	}

}