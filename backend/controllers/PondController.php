<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers;
use yii\helpers\Url;

use common\models\pond;
use common\models\Typelist;
use common\models\pondRef;
use common\models\Media;
use common\models\weight;
use common\models\Oxygen;
use common\models\ph;
use common\models\alkalinity;
use common\models\temp;
use common\models\watertemp;

use app\Workflow;
use app\JsonPackage;
use common\models\Feed;
use yii\helpers\ArrayHelper;
use app\Ui;
use app\Entity;
use app\DateUtil;
use common\models\User;
use common\models\Food;
use common\models\Checkyo;
use common\models\pondPublish;
use app\TpbsLog;
use common\models\Document;
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
    
    
    
    
    // Start Food
    public function actionFood() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = food::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->foodDelete();
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

    					$objPond = Pond::find()->orderBy(['id'=>SORT_ASC])->all();
    					foreach ($objPond as $dataPond){
    						$objTypelist = Typelist::find()->where(['id'=>$dataPond->type])->all();
    						foreach ($objTypelist as $obj){
    						$arrPond[$dataPond->id] = $obj->name.' '.$dataPond->title;
    						}
    					
    					}
    				}
    				
    				
    					
    echo $this->render('food', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'pagination' => $pagination,
    		'arrUser' =>$arrUser,
    		'q'=>$q,
    ]);
    }
    
    public function actionEditfood()
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
    		$model = new food();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    	
    	$foodTime = $request->get('foodTime', $request->post('foodTime', null));
    	$foodTimeIn = date('Y-m-d H:i:s', strtotime($foodTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->foodNo  = $request->get('foodNo', $request->post('foodNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->foodNum  = $request->get('foodNum', $request->post('foodNum', null));
    		$model->foodTime  = $foodTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('food');
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
    
    	$query = Pond::find()->orderBy(['id'=>SORT_ASC]);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editfood', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    	]);
    }
    // End of Foot
    
    
    
    
    //    หน้า การวิเคราะห์ความเป็นด่าง หรือ อัลคาลินิตี้ (Alkalinity) 
    //
    public function actionAlkalinity() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Alkalinity::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->alkalinityDelete();
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

    					$objPond = Pond::find()->orderBy(['id'=>SORT_ASC])->all();
    					foreach ($objPond as $dataPond){
    						$objTypelist = Typelist::find()->where(['id'=>$dataPond->type])->all();
    						foreach ($objTypelist as $obj){
    						$arrPond[$dataPond->id] = $obj->name.' '.$dataPond->title;
    						}
    					
    					}
    				}
    				
    				
    					
    echo $this->render('alkalinity', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'pagination' => $pagination,
    		'arrUser' =>$arrUser,
    		'q'=>$q,
    ]);
    }
    
    public function actionEditalkalinity()
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
    		$model = new food();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    	
    	$foodTime = $request->get('foodTime', $request->post('foodTime', null));
    	$foodTimeIn = date('Y-m-d H:i:s', strtotime($foodTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->foodNo  = $request->get('foodNo', $request->post('foodNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->foodNum  = $request->get('foodNum', $request->post('foodNum', null));
    		$model->foodTime  = $foodTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('food');
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
    
    	$query = Pond::find()->orderBy(['id'=>SORT_ASC]);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editfood', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    	]);
    }
    // End of Alkalinity
    
    
    // Start Checkyo 
    public function actionCheckyo() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Checkyo::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->checkyoDelete();
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

    					$objPond = Pond::find()->orderBy(['id'=>SORT_ASC])->all();
    					foreach ($objPond as $dataPond){
    						$objTypelist = Typelist::find()->where(['id'=>$dataPond->type])->all();
    						foreach ($objTypelist as $obj){
    						$arrPond[$dataPond->id] = $obj->name.' '.$dataPond->title;
    						}
    					
    					}
    				}
    				
    				
    					
		    echo $this->render('checkyo', [
		    		'lst' => $list,
		    		'arrPond' => $arrPond,
		    		'pagination' => $pagination,
		    		'arrUser' =>$arrUser,
		    		'q'=>$q,
		    ]);
    }
    
    
    public function actionEditcheckyo()
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
    		$model = new Checkyo();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    	
    	$checkyoTime = $request->get('checkyoTime', $request->post('checkyoTime', null));
    	$checkyoTimeIn = date('Y-m-d H:i:s', strtotime($checkyoTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->checkyoNo  = $request->get('checkyoNo', $request->post('checkyoNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->checkyoNum  = $request->get('checkyoNum', $request->post('checkyoNum', null));
    		$model->checkyoTime  = $checkyoTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));
    		$model->yo01  = $request->get('yo01', $request->post('yo01', null));
    		$model->yo02  = $request->get('yo02', $request->post('yo02', null));
    		$model->yo03  = $request->get('yo03', $request->post('yo03', null));
    		$model->yo04  = $request->get('yo04', $request->post('yo04', null));
    		
    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('checkyo');
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
    
    	$query = Pond::find()->orderBy(['id'=>SORT_ASC]);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editcheckyo', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    	]);
    }
    // End of Checkyo
    
    
  

    // Start editoxygen
    public function actionOxygen() {	
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Oxygen::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->oxygenDelete();
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

    					$objPond = Pond::find()->orderBy(['id'=>SORT_ASC])->all();
    					foreach ($objPond as $dataPond){
    						$objTypelist = Typelist::find()->where(['id'=>$dataPond->type])->all();
    						foreach ($objTypelist as $obj){
    						$arrPond[$dataPond->id] = $obj->name.' '.$dataPond->title;
    						}
    					
    					}
    				}
    				
    				
    					
    echo $this->render('oxygen', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'pagination' => $pagination,
    		'arrUser' =>$arrUser,
    		'q'=>$q,
    ]);}
    
    public function actionEditoxygen()
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
    		$model = new Oxygen();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    	
    	$oxygenTime = $request->get('oxygenTime', $request->post('oxygenTime', null));
    	$oxygenTimeIn = date('Y-m-d H:i:s', strtotime($oxygenTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->oxygenNo  = $request->get('oxygenNo', $request->post('oxygenNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->oxygenNum  = $request->get('oxygenNum', $request->post('oxygenNum', null));
    		$model->oxygenTime  = $oxygenTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('oxygen');
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
    
    	$query = Pond::find()->orderBy(['id'=>SORT_ASC]);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editoxygen', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    	]);
    }
    // End of editoxygen
    
    

    // Start PH
    public function actionPh() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Ph::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->phDelete();
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

    					$objPond = Pond::find()->orderBy(['id'=>SORT_ASC])->all();
    					foreach ($objPond as $dataPond){
    						$objTypelist = Typelist::find()->where(['id'=>$dataPond->type])->all();
    						foreach ($objTypelist as $obj){
    						$arrPond[$dataPond->id] = $obj->name.' '.$dataPond->title;
    						}
    					
    					}
    				}
    				
    				
    					
    echo $this->render('ph', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'pagination' => $pagination,
    		'arrUser' =>$arrUser,
    		'q'=>$q,
    ]);
    }
    
    public function actionEditph()
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
    		$model = new Ph();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    	
    	$phTime = $request->get('phTime', $request->post('phTime', null));
    	$phTimeIn = date('Y-m-d H:i:s', strtotime($phTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->phNo  = $request->get('phNo', $request->post('phNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->phNum  = $request->get('phNum', $request->post('phNum', null));
    		$model->phTime  = $phTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('ph');
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
    
    	$query = Pond::find()->orderBy(['id'=>SORT_ASC]);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editph', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    	]);
    }
    // End of PH
    
    
    // Start Temp
    public function actionTemp() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Temp::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->tempDelete();
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

    					$objPond = Pond::find()->orderBy(['id'=>SORT_ASC])->all();
    					foreach ($objPond as $dataPond){
    						$objTypelist = Typelist::find()->where(['id'=>$dataPond->type])->all();
    						foreach ($objTypelist as $obj){
    						$arrPond[$dataPond->id] = $obj->name.' '.$dataPond->title;
    						}
    					
    					}
    				}
    				
    				
    					
    echo $this->render('temp', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'pagination' => $pagination,
    		'arrUser' =>$arrUser,
    		'q'=>$q,
    ]);
    }
    
    public function actionEdittemp()
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
    		$model = new temp();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    	
    	$tempTime = $request->get('tempTime', $request->post('tempTime', null));
    	$tempTimeIn = date('Y-m-d H:i:s', strtotime($tempTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->tempNo  = $request->get('tempNo', $request->post('tempNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->tempNum  = $request->get('tempNum', $request->post('tempNum', null));
    		$model->tempTime  = $tempTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('temp');
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
    
    	$query = Pond::find()->orderBy(['id'=>SORT_ASC]);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('edittemp', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    	]);
    }
    // End of Temp
    
    
    

    // Start watertemp
    public function actionWatertemp() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = watertemp::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->watertempDelete();
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

    					$objPond = Pond::find()->orderBy(['id'=>SORT_ASC])->all();
    					foreach ($objPond as $dataPond){
    						$objTypelist = Typelist::find()->where(['id'=>$dataPond->type])->all();
    						foreach ($objTypelist as $obj){
    						$arrPond[$dataPond->id] = $obj->name.' '.$dataPond->title;
    						}
    					
    					}
    				}
    				
    				
    					
    echo $this->render('watertemp', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'pagination' => $pagination,
    		'arrUser' =>$arrUser,
    		'q'=>$q,
    ]);
    }
    
    public function actionEditwatertemp()
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
    		$model = new watertemp();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    	
    	$watertempTime = $request->get('watertempTime', $request->post('watertempTime', null));
    	$watertempTimeIn = date('Y-m-d H:i:s', strtotime($watertempTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->watertempNo  = $request->get('foodNo', $request->post('foodNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->foodNum  = $request->get('foodNum', $request->post('foodNum', null));
    		$model->foodTime  = $foodTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('food');
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
    
    	$query = Pond::find()->orderBy(['id'=>SORT_ASC]);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editfood', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    	]);
    }
    // End of watertemp
    
    
    // Start Weight
    public function actionWeight() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = weight::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->weightDelete();
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

    					$objPond = Pond::find()->orderBy(['id'=>SORT_ASC])->all();
    					foreach ($objPond as $dataPond){
    						$objTypelist = Typelist::find()->where(['id'=>$dataPond->type])->all();
    						foreach ($objTypelist as $obj){
    						$arrPond[$dataPond->id] = $obj->name.' '.$dataPond->title;
    						}
    					
    					}
    				}
    				
    				
    					
    echo $this->render('weight', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'pagination' => $pagination,
    		'arrUser' =>$arrUser,
    		'q'=>$q,
    ]);
    }
    
    public function actionEditweight(){
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = Typelist::find();
    	
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    	}else{
    		$model = new weight();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    	
    	$weightTime = $request->get('weightTime', $request->post('weightTime', null));
    	$weightTimeIn = date('Y-m-d H:i:s', strtotime($weightTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->weightNo  = $request->get('weightNo', $request->post('weightNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->weightNum  = $request->get('weightNum', $request->post('weightNum', null));
    		$model->weightTime  = $weightTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('weight');
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
    
    	$query = Pond::find()->orderBy(['id'=>SORT_ASC]);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editweight', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    	]);
    }
    // End of Weight
    
    
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
    			$this->pondDelete();
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
    	$type = $request->get('type', $request->post('type', null));	
    	$description= $request->get('description', $request->post('description', null));
    	$larvae = $request->get('larvae', $request->post('larvae', null));
    	$larvaeType= $request->get('larvaeType', $request->post('larvaeType', null));     		
    	$larvaePrice = $request->get('larvaePrice', $request->post('larvaePrice', null));
    	$larvaeCompany = $request->get('larvaeCompany', $request->post('larvaeCompany', null));
    	$releaseTime = $request->get('releaseTime', $request->post('releaseTime', null));
    	$title = $request->get('title', $request->post('title', null));
    		
    	if(empty($pond)){
    		$pond = new pond();
    		$pond->createBy = $identity->id;
    		$pond->createTime = date('Y-m-d H:i:s', $currentTs);
    	} 
    	
    	if ($request->isPost) {
    				
    		$publishTs = $currentTs;
    		$pond->title = $title;
    		$pond->type = $type;
    		$pond->pond = $description;
    		$pond->lastUpdateTime = date(DateUtil::SQL_DT_FMT, $currentTs);
    		$pond->lastUpdateBy = $identity->id;
    		$pond->larvae = $larvae;
    		$pond->larvaeType = $larvaeType;
    		$pond->larvaePrice = $larvaePrice;
    		$pond->larvaeCompany = $larvaeCompany;
    		$pond->releaseTime = $releaseTime;
    		
    		if($pond->save()) {
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('list');
    		}else{
    			Ui::setMessage(json_encode($pond->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    	}
    	
		$query = Typelist::find();
		$query->orderBy(['id'=>SORT_ASC]);
		$objTypelist = $query->all();
		$arrTypelist = [];
		foreach ($objTypelist as $dataTypelist){
			$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
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
    
   
    
 
    
    public function actionGetpond(){
    	if($_REQUEST['id']){
	    	$model = pond::findOne(['id'=> $_REQUEST['id']]);
	    	\Yii::$app->response->format = 'json';
	    	echo json_encode(['id'=>$model->id,'title'=>$model->title]);
    	}
    }
    
    public function actionSearchpond(){
    	$request =Yii::$app->request;
    	$q = $request->get('q');
    	
    	if (!empty($q)){
    			
    		$query = pond::find();
    		$query->andWhere('status=:status', [':status'=> Workflow::STATUS_PUBLISHED]);
    		if(is_numeric($q))
    			$query->andWhere(['LIKE', 'id', $q]);
    		else
    			$query->andWhere(['LIKE', 'title', $q]);
    			
    		$query->limit(30);
    		$lst = $query->all();
    			
    	}else{
			$query = pond::find();
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
    
    private function foodDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    		
    	if (is_array($arrIds) && !empty($arrIds)) {

    			foreach ($arrIds as $lst){
    				$queryUser = Food::find();
    				$Food = $queryUser->where(['id' => $lst])->one()->delete();
    				$deleted = $deleted + 1;
    			}

    		if ($deleted > 0) {
    			Ui::setMessage("ลบข้อมูลจำนวน  $deleted รายการ" + "บันทึกข้อมูลสำเร็จ");
    			
    		}
    		else {
    			Ui::setMessage('ไม่มีข้อมูลถูกลบ');
    		}
    	}
    }
    
    private function weightDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Weight::find();
    				$Food = $queryUser->where(['id' => $lst])->one()->delete();
    				$deleted = $deleted + 1;
    			}
    
    			if ($deleted > 0) {
    				Ui::setMessage("ลบข้อมูลจำนวน  $deleted รายการ" + "บันทึกข้อมูลสำเร็จ");
    				 
    			}
    			else {
    				Ui::setMessage('ไม่มีข้อมูลถูกลบ');
    			}
    		}
    }
    
    private function pondDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryPond= Pond::find();
    				$Food = $queryPond->where(['id' => $lst])->one()->delete();
    				$deleted = $deleted + 1;
    			}
    
    			if ($deleted > 0) {
    				Ui::setMessage("ลบข้อมูลจำนวน  $deleted รายการ" + "บันทึกข้อมูลสำเร็จ");
    				 
    			}
    			else {
    				Ui::setMessage('ไม่มีข้อมูลถูกลบ');
    			}
    		}
    }
    

    private function checkyoDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = checkyo::find();
    				$checkyo = $queryUser->where(['id' => $lst])->one()->delete();
    				$deleted = $deleted + 1;
    			}
    
    			if ($deleted > 0) {
    				Ui::setMessage("ลบข้อมูลจำนวน  $deleted รายการ" + "บันทึกข้อมูลสำเร็จ");
    				 
    			}
    			else {
    				Ui::setMessage('ไม่มีข้อมูลถูกลบ');
    			}
    		}
    }
    
    public function actionFinpond(){
    	$items = ['message'=> 'success'];
    	//$id = \Yii::$app->request->post('id');
    	$id = \Yii::$app->request->get('id');
    	
    	$pond = pond::findOne(['id'=> $id]);
    	$Typelist = Typelist::findOne(['id'=>$pond->type]);

    	$date1 = strtotime($pond->releaseTime);
    	$date2 = time();
    	$subTime = $date2 - $date1;
    	$y = ($subTime/(60*60*24*365));
    	$d = ($subTime/(60*60*24))%365;
    	$h = ($subTime/(60*60))%24;
    	$m = ($subTime/60)%60;
    	$age = 'อายุ  '.$d.' วัน '.$h.' ชั่วโมง '.$m.' นาที ';

    	$items['pond'] = $Typelist->name.' '.$pond->title;
    	$items['age'] = $age;

    	header('Content-Type: application/json');
    	//var_dump($items); exit();
    	echo json_encode($items);
    }
    
    public function init() {
        parent::init();
        $this->layout = 'layoutstyle';
    }

}
