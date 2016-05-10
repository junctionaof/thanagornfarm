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
use app\Ui;
use common\models\User;
use common\models\Auth;
use common\models\AuthAssignment;
use common\models\AuthItem;

class UserController extends BaseController{
	
	public function init() {
		parent::init();
		$this->layout = 'layoutstyle';
	}
	

	public function actionAdd()
	{
		$model = new User();

		if (\Yii::$app->request->isPost)
		{
			$user = \Yii::$app->request->post('User');
			$model->attributes = \Yii::$app->request->post('User');
			$model->position = \Yii::$app->request->post('type');
			$password = \Yii::$app->request->post('password');
			$model->status = 10 ;
			if( ($password) ){
				$model->setPassword($password);
				$model->generateAuthKey();
			}
			if($model->save()){
				if (\Yii::$app->request->post('type')){
					switch (\Yii::$app->request->post('type')){
							case 1:
								$auth = 'thanagorn.role.admin';
								break;
							case 2:
								$auth = 'thanagorn.role.staff';
								break;
						}
					$modelauth = new AuthAssignment();
					$modelauth->itemname  = $auth ;
					$modelauth->userid =  $user['username'];
					$modelauth->data = 'N;';
					if($modelauth->save()){
						//AuthController::reassign();
					}else {
						Ui::setMessage('ไม่สามารถ กำหนนดสิทธิ์ ได้','warning');
					}
				}
				Ui::setMessage('บันทึกข้อมูลสำเร็จ');
				return $this->redirect(Url::toRoute('user/list'));
			}
			else {
				Ui::setMessage('การบันทึกข้อมูลผิดพลาด','warning');
			}
		}
			
		echo $this->render('add',['model' => $model]);
	}

	public function actionEdit()
	{
		
	 $model = new User();

	 $id = \Yii::$app->request->get('id');
		if($id){
			$query = User::find();
			$query->andWhere(['id' => $id]);
			$model = $query->one();
			if (\Yii::$app->request->isPost){
				
				$user = \Yii::$app->request->post('User');
				$model->attributes = \Yii::$app->request->post('User');
				$model->position = \Yii::$app->request->post('type');
				$model->type = \Yii::$app->request->post('type');
				$model->status = 10 ;
				
				$password = \Yii::$app->request->post('password');
				$confirmPassword= \Yii::$app->request->post('confirmPassword');
				
				if( ($password) && ($password == $confirmPassword) ){
					$model->setPassword($password);
					$model->generateAuthKey();
				}
				if($model->save()){		
/* 					if (\Yii::$app->request->post('type')){
						switch (\Yii::$app->request->post('type')){
							case 1:
								$auth = 'tpbs.role.admin';
								break;
							case 2:
								$auth = 'tpbs.role.editor';
								break;
							case 3:
								$auth = 'tpbs.role.journalist';
								break;
						}
						
						$query = AuthAssignment::find();
						$query->andWhere(['userid' => $user]);
						$modelauth = $query->one();
						$modelauth->itemname  = $auth ;
						$modelauth->userid =  $user['username'];
						$modelauth->data = 'N;';
						if($modelauth->save()){
							//AuthController::reassign();
						}else {
							Ui::setMessage('ไม่สามารถ กำหนนดสิทธิ์ ได้','warning');
						}
					} */
					Ui::setMessage("บันทึกข้อมูลเสร็จเรียบร้อย", 'success');
					return $this->redirect(Url::toRoute('user/list'));
				}else{
					Ui::setMessage('การบันทึกข้อมูลผิดพลาด','warning');
				}
			}
			echo $this->render('edit',['model' => $model]);
		}
	}

	public function actionIndex()
	{
		echo $this->render('index');
	}

	public function actionList()
	{
		$query = User::find();
		if (\Yii::$app->request->isPost) {
			if ($_REQUEST['op'] == "delete") {
				$arrId = $_REQUEST['selectUser'];
				foreach ($arrId as $lst){
					$queryUser = User::find();
					$User = $queryUser->where(['id' => $lst])->one()->delete();
				}
				echo $this->redirect(Url::toRoute('user/list'));
			}
				
			if ($_REQUEST['op'] == "search") {
				$searchText = $_REQUEST['searchText'];
						$query->andWhere(['LIKE' ,'firstName','%'.$searchText.'%', false]);
		}
		}


		$pagination = new Pagination([
				'defaultPageSize' => 20,
				'totalCount' => $query->count(),
		]);
		
		
		$queryauth = AuthItem::find();
		$queryauth->where(['type' => '2' ]);
		$modelauth = $queryauth->all();
		$arrauth = '';
		foreach ($modelauth as $lst){
			$arrauth[0] = 'เลือกสิทธิ์ ให้กับ user' ;
			$arrauth[] = $lst->name;
		}
		
		

		$model = $query
		->orderBy('createTime DESC')
		->offset($pagination->offset)
		->limit($pagination->limit)
		->all();

		echo $this->render('list',['model' => $model , 'arrauth' => $arrauth, 'pagination'=>$pagination]);
	}
	
	
	public function actionAccesslog()
	{
		$query = User::find();
	
	
		if (\Yii::$app->request->isPost) {
			if ($_REQUEST['op'] == "delete") {
				$arrId = $_REQUEST['selectUser'];
				foreach ($arrId as $lst){
					$queryUser = User::find();
					$User = $queryUser->where(['id' => $lst])->one()->delete();
				}
				echo $this->redirect(Url::toRoute('user/list'));
			}
	
			if ($_REQUEST['op'] == "search") {
				$searchText = $_REQUEST['searchText'];
				$query->andWhere(['LIKE' ,'firstName','%'.$searchText.'%', false]);
			}
		}
	
	
		$pagination = new Pagination([
				'defaultPageSize' => 20,
				'totalCount' => $query->count(),
		]);
	
	
		$model = $query
		->orderBy('createTime DESC')
		->offset($pagination->offset)
		->limit($pagination->limit)
		->all();
	
		echo $this->render('accesslog',['model' => $model , 'pagination'=>$pagination]);
	}

	
	public function actionContentlog()
	{
		$query = User::find();
	
	
		if (\Yii::$app->request->isPost) {
			if ($_REQUEST['op'] == "delete") {
				$arrId = $_REQUEST['selectUser'];
				foreach ($arrId as $lst){
					$queryUser = User::find();
					$User = $queryUser->where(['id' => $lst])->one()->delete();
				}
				echo $this->redirect(Url::toRoute('user/list'));
			}
	
			if ($_REQUEST['op'] == "search") {
				$searchText = $_REQUEST['searchText'];
				$query->andWhere(['LIKE' ,'firstName','%'.$searchText.'%', false]);
			}
		}
	
	
		$pagination = new Pagination([
				'defaultPageSize' => 20,
				'totalCount' => $query->count(),
		]);
	
	
		$model = $query
		->orderBy('createTime DESC')
		->offset($pagination->offset)
		->limit($pagination->limit)
		->all();
	
		echo $this->render('contentlog',['model' => $model , 'pagination'=>$pagination]);
	}
	
	
	public function actionProfile(){
		$identity = \Yii::$app->user->getIdentity();

		$profile = User::findOne(['id'=> $identity->id]);
		if(empty($profile)) $profile = new User();

		if (\Yii::$app->request->isPost) {
			$profile->attributes = $_POST['User'];
			if( ($_POST['password']) && ($_POST['password'] == $_POST['confirmPassword']) ){
				$profile->setPassword($_POST['password']);
				$profile->generateAuthKey();
			}

			if($profile->save()){
				Ui::setMessage("บันทึกข้อมูลเสร็จเรียบร้อย", 'success');
			}else{
				Ui::setMessage('การบันทึกข้อมูลผิดพลาด','warning');
			}
		}


		echo $this->render('profile', ['profile'=> $profile]);
	}


}