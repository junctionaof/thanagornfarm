<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers;
use yii\helpers\Url;

use common\models\Pond;
use common\models\Typelist;
use common\models\PondRef;
use common\models\Media;
use common\models\Weight;
use common\models\Oxygen;
use common\models\Ph;
use common\models\Alkalinity;
use common\models\Temp;
use common\models\Watertemp;
use common\models\Ammonia;
use common\models\Nitrite;
use common\models\Salinity;
use common\models\Waterchange;
use common\models\Other;
use common\models\analysis;

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
    
    public function actionShrimp() {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$params = \Yii::$app->params;
    	$id = $request->get('id', $request->post('id', null));
    	
    	$query = Typelist::find();
    	if ($id){ 
    		$query->where("id=".$id);
    		$model = $query->one();
    	}
    	
    	$pond = Pond::find()->andwhere('type='.$id)->andwhere('status=1')->one();

    	
    	// นั้าหนักเฉลี่ยกุ้ง 
    	$objweightavg = Weight::find()->andwhere('pondId='.$pond->id)->one(); 
    	$weightavg = isset($objweightavg->weightNum)?$objweightavg->weightNum : 0 ;
    	
    	
    	
    	//คาดการผลผลิตระหว่างการเลี้ยง
    	$weight = isset($params['eatperweight'][$weightavg])?$params['eatperweight'][$weightavg] : 0 ;
    	 // เอานํ้าหนักมาเทียบกับตาราง จะได้ค่า อัตราการกินอหาร
    	$now = date("Y-m-d");  // วันปัจจุบัน
    	$command = Yii::$app->db->createCommand("SELECT sum(foodNum) FROM  food Where foodTime Like  '%$now%' and pondId = ".$pond->id); 
    	$sumfood = $command->queryScalar(); // ผลรวม นํ้าหนักอาหาร ต่อวันปัจจุบัน
    	$outputOnprocess = $weight != 0 ? (100/$weight)* $sumfood : 0 ;  // ผลผลิตระหว่างการเลี้ยง  
		
    	
    	// % อัตราการรอดตาย
    	$larvae = $pond->larvae;//จํานวนลูกกุ้งที่ปล่อย
 		$survive = $weightavg != 0 ? (($outputOnprocess * 1000)/$weightavg) / $larvae * 100 : 0 ;
    	
 		// หารุ่น
 		$queryPond = Pond::find();
 		$queryPond->where("type=".$id);
 		$queryPond->orderBy(['id'=>SORT_ASC]);
 		$modelPond = $queryPond->all();
 		$arrPond = [];
 		$arrfood = [];
 		$arrPondid = [];
 		foreach($modelPond AS $objPond){
 			$arrPond[] = $objPond;
 			$arrPondid[] = $objPond->id;
    		}
    		
    	// ข้อมูลการให้อาหาร
    	$queryfood = food::find();
    	$queryfood->andWhere(['in', 'pondId', $arrPondid]);
    	$queryfood->orderBy(['id'=>SORT_ASC]);
    	$countQueryfood = clone $queryfood;
    	$countfood = $countQueryfood->count();
    	$pagesfood = new Pagination([
    			'defaultPageSize' => 8,
    			'totalCount' => $countfood,
    	]);
    	$queryfood->offset($pagesfood->offset)->limit($pagesfood->limit);
    	$food = $queryfood->all();
    	
    	// ข้อมูลการเช็คยอ
    	$queryCheckyo = Checkyo::find();
    	$queryCheckyo->andWhere(['in', 'pondId', $arrPondid]);
    	$queryCheckyo->orderBy(['id'=>SORT_ASC]);
    	
    	$countQueryCheckyo = clone $queryCheckyo;
    	$countCheckyo = $countQueryCheckyo->count();
    	$pagesCheckyo = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countCheckyo,
    	]);
    	$queryCheckyo->offset($pagesCheckyo->offset)->limit($pagesCheckyo->limit);
    	$checkyo = $queryCheckyo->all();
    	
    	// ข้อมูลการวัดนํ้าหนักเฉลี่ย
    	$queryWeight = Weight::find();
    	$queryWeight->andWhere(['in', 'pondId', $arrPondid]);
    	$queryWeight->orderBy(['id'=>SORT_ASC]);
    	$countQueryWeight = clone $queryWeight;
    	$countWeight = $countQueryWeight->count();
    	$pagesWeight = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countWeight,
    	]);
    	$queryWeight->offset($pagesWeight->offset)->limit($pagesWeight->limit);
    	$weight = $queryWeight->all();
    	
    	
    	// ข้อมูลออกซิเจนละลายนํ้า
    	$queryOxygen = Oxygen::find();
    	$queryOxygen->andWhere(['in', 'pondId', $arrPondid]);
    	$queryOxygen->orderBy(['id'=>SORT_ASC]);
    	$countQueryOxygen = clone $queryOxygen;
    	$countOxygen = $countQueryOxygen->count();
    	$pagesOxygen = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countOxygen,
    	]);
    	$queryOxygen->offset($pagesOxygen->offset)->limit($pagesOxygen->limit);
    	$oxygen = $queryOxygen->all();
    	
    	
    	// ข้อมูล ค่้า PH
    	$queryPh = Ph::find();
    	$queryPh->andWhere(['in', 'pondId', $arrPondid]);
    	$queryPh->orderBy(['id'=>SORT_ASC]);
    	$countQueryPh = clone $queryPh;
    	$countPh = $countQueryPh->count();
    	$pagesPh = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countPh,
    	]);
    	$queryPh->offset($pagesPh->offset)->limit($pagesPh->limit);
    	$ph = $queryPh->all();
    	
    	
    	// ข้อมูลค่า ค่าอัลคาไลน์นิติ
    	$queryAlkalinity = Alkalinity::find();
    	$queryAlkalinity->andWhere(['in', 'pondId', $arrPondid]);
    	$queryAlkalinity->orderBy(['id'=>SORT_ASC]);
    	$countQueryAlkalinity = clone $queryAlkalinity;
    	$countAlkalinity = $countQueryAlkalinity->count();
    	$pagesAlkalinity = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countAlkalinity,
    	]);
    	$queryAlkalinity->offset($pagesAlkalinity->offset)->limit($pagesAlkalinity->limit);
    	$alkalinity = $queryAlkalinity->all();
    	
    	// ข้อมูล ค่าอุณหภูมิของน้ำ
    	$queryWatertemp = Watertemp::find();
    	$queryWatertemp->andWhere(['in', 'pondId', $arrPondid]);
    	$queryWatertemp->orderBy(['id'=>SORT_ASC]);
    	$countQueryWatertemp = clone $queryWatertemp;
    	$countWatertemp = $countQueryWatertemp->count();
    	$pagesWatertemp = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countWatertemp,
    	]);
    	$queryWatertemp->offset($pagesWatertemp->offset)->limit($pagesWatertemp->limit);
    	$watertemp = $queryWatertemp->all();
    	
    	// ข้อมูล ค่าอุณหภูมิแวดล้อม
    	$queryTemp = Temp::find();
    	$queryTemp->andWhere(['in', 'pondId', $arrPondid]);
    	$queryTemp->orderBy(['id'=>SORT_ASC]);
    	$countQueryTemp = clone $queryTemp;
    	$countTemp = $countQueryTemp->count();
    	$pagesTemp = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countTemp,
    	]);
    	$queryTemp->offset($pagesTemp->offset)->limit($pagesTemp->limit);
    	$temp = $queryTemp->all();
    	
    	// ข้อมูล ค่าแอมโมเนีย
    	$queryAmmonia = Ammonia::find();
    	$queryAmmonia->andWhere(['in', 'pondId', $arrPondid]);
    	$queryAmmonia->orderBy(['id'=>SORT_ASC]);
    	$countQueryAmmonia = clone $queryAmmonia;
    	$countAmmonia = $countQueryAmmonia->count();
    	$pagesAmmonia = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countQueryAmmonia,
    	]);
    	//$queryAmmonia->offset($pagesAmmonia->offset)->limit($pagesAmmonia->limit);
    	$ammonia = $queryAmmonia->all();
    	
    	// ข้อมูล ค่าไนไตรท์
    	$queryNitrite = Nitrite::find();
    	$queryNitrite->andWhere(['in', 'pondId', $arrPondid]);
    	$queryNitrite->orderBy(['id'=>SORT_ASC]);
    	$countQueryNitrite = clone $queryNitrite;
    	$countNitrite = $countQueryNitrite->count();
    	$pagesNitrite = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countNitrite,
    	]);
    	$queryNitrite->offset($pagesNitrite->offset)->limit($pagesNitrite->limit);
    	$nitrite = $queryNitrite->all();
    	
    	// ข้อมูล ค่าความเค็มของนํ้า
    	$querySalinity = Salinity::find();
    	$querySalinity->andWhere(['in', 'pondId', $arrPondid]);
    	$querySalinity->orderBy(['id'=>SORT_ASC]);
    	$countQuerySalinity = clone $querySalinity;
    	$countSalinity = $countQuerySalinity->count();
    	$pagesSalinity = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countSalinity,
    	]);
    	$querySalinity->offset($pagesSalinity->offset)->limit($pagesSalinity->limit);
    	$salinity = $querySalinity->all();
    	
    	// ข้อมูล การเปลี่ยนถ่ายนํ้า
    	$queryWaterchange = Waterchange::find();
    	$queryWaterchange->andWhere(['in', 'pondId', $arrPondid]);
    	$queryWaterchange->orderBy(['id'=>SORT_ASC]);
    	$countQueryWaterchange = clone $queryWaterchange;
    	$countWaterchange = $countQueryWaterchange->count();
    	$pagesWaterchange = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countWaterchange,
    	]);
    	$queryWaterchange->offset($pagesWaterchange->offset)->limit($pagesWaterchange->limit);
    	$waterchange = $queryWaterchange->all();
    	
    	//ข้อมูล อื่นๆ
    	$queryOther = Other::find();
    	$queryOther->andWhere(['in', 'pondId', $arrPondid]);
    	$queryOther->orderBy(['id'=>SORT_ASC]);
    	$countQueryOther = clone $queryOther;
    	$countOther = $countQueryOther->count();
    	$pagesOther = new Pagination([
    			'defaultPageSize' => 20,
    			'totalCount' => $countOther,
    	]);
    	$queryOther->offset($pagesOther->offset)->limit($pagesOther->limit);
    	$other = $queryOther->all();
    	
    	
    	echo $this->render('shrimp', [
    			'alkalinity' => $alkalinity,
    			'watertemp' => $watertemp,
    			'temp' => $temp,
    			'ammonia' => $ammonia,
    			'nitrite' => $nitrite,
    			'salinity' => $salinity,
    			'waterchange' => $waterchange,
    			'other' => $other,
    			'food' => $food,
    			'checkyo' => $checkyo,
    			'weight' => $weight,
    			'oxygen' => $oxygen,
    			'ph' => $ph,
    			'arrPond'=> $arrPond,
    			'model' => $model,
    			'pond' => $pond,
    			'outputOnprocess' => $outputOnprocess,
    			'survive' => $survive,
    			'weightavg' => $weightavg,
    			'countOther' => $countOther,
    			'pagesOther' => $pagesOther,
    			'countWaterchange' => $countWaterchange,
    			'pagesWaterchange' => $pagesWaterchange,
    			'countSalinity' => $countSalinity,
    			'pagesSalinity' => $pagesSalinity,
    			'countNitrite' => $countNitrite,
    			'pagesNitrite' => $pagesNitrite,
    			'countAmmonia' => $countAmmonia,
    			'pagesAmmonia' => $pagesAmmonia,
    			'countTemp' => $countTemp,
    			'pagesTemp' => $pagesTemp,
    			'countWatertemp' => $countWatertemp,
    			'pagesWatertemp' => $pagesWatertemp,
    			'pagesfood' => $pagesfood,
    			'countfood' => $countfood,
    			'pagesCheckyo' => $pagesCheckyo,
    			'countCheckyo' => $countCheckyo,
    			'pagesWeight' => $pagesWeight,
    			'countWeight' => $countWeight,
    			'pagesOxygen' => $pagesOxygen,
    			'countOxygen' => $countOxygen,
    			'pagesPh' => $pagesPh,
    			'countPh' => $countPh,
    			'pagesAlkalinity' => $pagesAlkalinity,
    			'countAlkalinity' => $countAlkalinity,	
    	]);
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
    		
    		$arrAllUser = [];
    		$modelsAllUser = User::find()->all();
    		if(!empty($modelsAllUser)){
    			foreach ($modelsAllUser as $obj){
    				$arrAllUser[$obj->id] = $obj->firstName.' '.$obj->lastName;
    			}
    		}
    			
    		echo $this->render('typelist', [
    				'lst' => $list,
    				'pagination' => $pagination,
    				'arrUser' =>$arrUser,
    				'arrAllUser' => $arrAllUser,
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
    	if ($id){ $query->where("id=".$id);$model = $query->one();
   
    	}else{
    		$model = new Typelist();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    	}
    
    	if($request->isPost){

    		$user = $request->get('user', $request->post('user', null));
    		$model->name = $_POST['Typelist']['name'];
    		$model->size =$_POST['Typelist']['size'];
    		$model->keeper = serialize($user);
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);

    		if ($model->save()) {
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
    	
    	$arrUser = [];
    		$modelsUser = User::find()->all();
    		if(!empty($modelsUser)){
    			foreach ($modelsUser as $obj){
    				$arrUser[$obj->id] = $obj->firstName.' '.$obj->lastName;
    			}
    		}
    		
    	echo $this->render('edittype', [
    			'model' => $model,
    			'arrUser' =>$arrUser,
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
    	
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['typep'])){
    			$typep = $_REQUEST['typep'];
    			if($typep != 0){
    				$queryf = pond::find();
    				$queryf->andWhere(['type'=> $typep]);
    				$pondf = $queryf->all();
    				foreach ($pondf as $obj){
    					$arrId[] = $obj->id;
    				}
    				 
    				if($arrId){
    					$query->andWhere(['pondId'=> $arrId]);
    				}
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    
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
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    				
    					
    echo $this->render('food', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'pagination' => $pagination,
    		'arrUser' =>$arrUser,
    		'arrTypelist' => $arrTypelist,
    		'q'=>$q,
    ]);
    }
    
    public function actionEditfood()
    {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = Food::find();
    	
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new food();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
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
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('food');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}

    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	//var_dump($query->all()); exit();
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editfood', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
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
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
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
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    				
    					
    echo $this->render('alkalinity', [
    		'lst' => $list,
    		'arrTypelist'=>$arrTypelist,
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
    	$query = Alkalinity::find();
    	
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Alkalinity();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
    	}
    	
    	$alkalinityTime = $request->get('alkalinityTime', $request->post('alkalinityTime', null));
    	$alkalinityTimeIn = date('Y-m-d H:i:s', strtotime($alkalinityTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->alkalinityNo  = $request->get('alkalinityNo', $request->post('alkalinityNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->alkalinityNum  = $request->get('alkalinityNum', $request->post('alkalinityNum', null));
    		$model->alkalinityTime  = $alkalinityTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('alkalinity');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    		
    
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editalkalinity', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of alkalinity
    
    
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
    
    	
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
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
    				
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
		    echo $this->render('checkyo', [
		    		'lst' => $list,
		    		'arrTypelist'=>$arrTypelist,
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
    	$query = Checkyo::find();
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Checkyo();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
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
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);
    		
    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('checkyo');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    		
    
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    
    	echo $this->render('editcheckyo', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of Checkyo
    
    // Start Analysis
    public function actionAnalysis() { 
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = analysis::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	 
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	 
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->analysisDelete();
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
    
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
    				echo $this->render('analysis', [
    						'lst' => $list,
    						'arrTypelist'=>$arrTypelist,
    						'arrPond' => $arrPond,
    						'pagination' => $pagination,
    						'arrUser' =>$arrUser,
    						'q'=>$q,
    				]);
    }
    
    
    public function actionEditanalysis()
    {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = analysis::find();
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new analysis();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
    	}
    	 
    	$analysisTime = $request->get('analysisTime', $request->post('analysisTime', null));
    	$analysisTimeIn = date('Y-m-d H:i:s', strtotime($analysisTime));
    	 
    	if($request->isPost){
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->pickDate  = $request->get('pickDate', $request->post('pickDate', null));
    		$model->results  = $request->get('results', $request->post('results', null));
    		$model->size  = $request->get('size', $request->post('size', null));
    		$model->survivalRate  = $request->get('survivalRate', $request->post('survivalRate', null));
    		$model->quantity  = $request->get('quantity', $request->post('quantity', null));
    		$model->fcr  = $request->get('fcr', $request->post('fcr', null));
    		$model->receipts  = $request->get('receipts', $request->post('receipts', null));
    		$model->costShrimp  = $request->get('costShrimp', $request->post('costShrimp', null));
    		$model->costWage = $request->get('costWage', $request->post('costWage', null));
    		$model->costEnergy = $request->get('costEnergy', $request->post('costEnergy', null));
    		$model->costOther  = $request->get('costOther', $request->post('costOther', null));
    		$model->profits  = $request->get('profits', $request->post('profits', null));
    		$model->yields  = $request->get('yields', $request->post('yields', null));
    		$model->suggestion  = $request->get('suggestion', $request->post('suggestion', null));
    		$model->price = $request->get('price', $request->post('price', null));
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);
    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('analysis');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    
    
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    
    
    	echo $this->render('editanalysis', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of analysis
    
    
  

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

    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
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
    				
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
    echo $this->render('oxygen', [
    		'lst' => $list,
    		'arrTypelist'=> $arrTypelist,
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
    	$query = Oxygen::find();
    	
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Oxygen();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
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
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}

    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('oxygen');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	$queryAll = Pond::find();
    	$arrAllTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrAllTypelist += \yii\helpers\ArrayHelper::map($queryAll->all(), 'id' ,'title','type');
    	
    	
    
    	echo $this->render('editoxygen', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'arrAllTypelist' => $arrAllTypelist,
    			'status' => $status,
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
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
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
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    				
    					
    echo $this->render('ph', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'$arrTypelist'=>$arrTypelist,
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
    	$query = Ph::find();
    	
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Ph();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
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
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('ph');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');

    	echo $this->render('editph', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
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
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
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
    				
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
    echo $this->render('temp', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'arrTypelist'=>$arrTypelist,
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
    	$query = Temp::find();
    	
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new temp();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
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
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('temp');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('edittemp', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of Temp
    
    
    

    // Start Ammonia
    public function actionAmmonia() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    	$arrPond = [];
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Ammonia::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->ammoniaDelete();
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
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    
    					
    				echo $this->render('ammonia', [
    						'lst' => $list,
    						'arrPond' => $arrPond,
    						'arrTypelist'=>$arrTypelist,
    						'arrTypelist'=>$arrTypelist,
    						'pagination' => $pagination,
    						'arrUser' =>$arrUser,
    						'q'=>$q,
    				]);
    }
    
    public function actionEditammonia()
    {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = Ammonia::find();
    	 
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Ammonia();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
    	}
    	 
    	$ammoniaTime = $request->get('ammoniaTime', $request->post('ammoniaTime', null));
    	$ammoniaTimeIn = date('Y-m-d H:i:s', strtotime($ammoniaTime));
    	 
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->ammoniaNo  = $request->get('ammoniaNo', $request->post('ammoniaNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->ammoniaNum  = $request->get('ammoniaNum', $request->post('ammoniaNum', null));
    		$model->ammoniaTime  = $ammoniaTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);
    
    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('ammonia');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    
    	 
    
    	echo $this->render('editammonia', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of ammonia
    
    
    // Start Nitrite
    public function actionNitrite() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    	$arrPond = [];
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Nitrite::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->nitriteDelete();
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
    
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
    				echo $this->render('nitrite', [
    						'lst' => $list,
    						'arrPond' => $arrPond,
    						'arrTypelist'=>$arrTypelist,
    						'pagination' => $pagination,
    						'arrUser' =>$arrUser,
    						'q'=>$q,
    				]);
    }
    
    public function actionEditnitrite()
    {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = Nitrite::find();
    
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Nitrite();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
    	}
    
    	$nitriteTime = $request->get('nitriteTime', $request->post('nitriteTime', null));
    	$nitriteTimeIn = date('Y-m-d H:i:s', strtotime($nitriteTime));
    
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->nitriteNo  = $request->get('nitriteNo', $request->post('nitriteNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->nitriteNum  = $request->get('nitriteNum', $request->post('nitriteNum', null));
    		$model->nitriteTime  = $nitriteTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);
    
    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('nitrite');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    	
    
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');

    	echo $this->render('editnitrite', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of nitrite
    
    // Start other
    public function actionOther() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    	$arrPond = [];
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Other::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->otherDelete();
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
    
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
    				echo $this->render('other', [
    						'lst' => $list,
    						'arrPond' => $arrPond,
    						'arrTypelist'=>$arrTypelist,
    						'pagination' => $pagination,
    						'arrUser' =>$arrUser,
    						'q'=>$q,
    				]);
    }
    
    public function actionEditother()
    {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = Other::find();
    
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Other();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
    	}
    
    	$otherTime = $request->get('otherTime', $request->post('otherTime', null));
    	$otherTimeIn = date('Y-m-d H:i:s', strtotime($otherTime));
    
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->otherNo  = $request->get('otherNo', $request->post('otherNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->otherNum  = $request->get('otherNum', $request->post('otherNum', null));
    		$model->otherTime  = $otherTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);
    
    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('other');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    
    
    
    	echo $this->render('editother', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of other
    
    
    
    // Start Salinity
    public function actionSalinity() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    	$arrPond = [];
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Salinity::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->salinityDelete();
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
    
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    
    					
    				echo $this->render('salinity', [
    						'lst' => $list,
    						'arrTypelist'=>$arrTypelist,
    						'arrPond' => $arrPond,
    						'pagination' => $pagination,
    						'arrUser' =>$arrUser,
    						'q'=>$q,
    				]);
    }
    
    public function actionEditsalinity()
    {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = Salinity::find();
    
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Salinity();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
    	}
    
    	$salinityTime = $request->get('salinityTime', $request->post('salinityTime', null));
    	$salinityTimeIn = date('Y-m-d H:i:s', strtotime($salinityTime));
    
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->salinityNo  = $request->get('salinityNo', $request->post('salinityNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->salinityNum  = $request->get('salinityNum', $request->post('salinityNum', null));
    		$model->salinityTime  = $salinityTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);
    
    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if (!$model->hasErrors()) {
    			$model->save();
    			return $this->redirect('salinity');
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
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    
    
    
    	echo $this->render('editsalinity', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of salinity
    
    

    // Start Waterchange
    public function actionWaterchange() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    	$arrPond = [];
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Waterchange::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
    	if ($searchCategory)
    		$query->andWhere('type = :type',[':type' => $searchCategory]);
    		 
    		 
    		if ($searchStatus)
    			$query->andWhere('status = :status',[':status' => $searchStatus]);
    
    			if ($q)
    				$query->andWhere(['LIKE' ,'name','%'.$q.'%', false]);
    					
    					
    				//actions
    				switch ($request->post('op')){
    					case 'delete':
    						$this->waterchangeDelete();
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
    
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
    				echo $this->render('waterchange', [
    						'lst' => $list,
    						'arrPond' => $arrPond,
    						'arrTypelist'=>$arrTypelist,
    						'pagination' => $pagination,
    						'arrUser' =>$arrUser,
    						'q'=>$q,
    				]);
    }
    
    public function actionEditwaterchange()
    {
    	$currentTs = time();
    	$identity = \Yii::$app->user->getIdentity();
    	$request = \Yii::$app->request;
    	$id = $request->get('id', $request->post('id', null));
    	$query = Waterchange::find();
    	$arrPond =[];
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new waterchange();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
    	}
    
    	$waterchangeTime = $request->get('waterchangeTime', $request->post('waterchangeTime', null));
    	$waterchangeTimeIn = date('Y-m-d H:i:s', strtotime($waterchangeTime));
    
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->waterchangeNo  = $request->get('waterchangeNo', $request->post('waterchangeNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->waterchangeNum  = $request->get('waterchangeNum', $request->post('waterchangeNum', null));
    		$model->waterchangeTime  = $waterchangeTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);
    
    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('waterchange');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    
    
    
    	echo $this->render('editwaterchange', [
    			'arrPond' => $arrPond,
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
    	]);
    }
    // End of waterchange
    
    
    // Start watertemp
    public function actionWatertemp() {
    	$currentTs = time();
    	$request = Yii::$app->request;
    	$identity = \Yii::$app->user->getIdentity();
    
    	$searchCategory = $request->post('type', $request->get('type', ''));
    	$searchStatus = $request->post('status', $request->get('status', ''));
    	$q = trim($request->post('q', $request->get('q', '')));
    
    	$query = Watertemp::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
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
    				
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
    echo $this->render('watertemp', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'arrTypelist'=>$arrTypelist,
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
    	$query = Watertemp::find();
    	
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new watertemp();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
    	}
    	
    	$watertempTime = $request->get('watertempTime', $request->post('watertempTime', null));
    	$watertempTimeIn = date('Y-m-d H:i:s', strtotime($watertempTime));
    	
    	if($request->isPost){
    		$model->name  = $request->get('name', $request->post('name', null));
    		$model->pondId  = $request->get('pondId', $request->post('pondId', null));
    		$model->watertempNo  = $request->get('watertempNo', $request->post('watertempNo', null));
    		$model->age  = $request->get('age', $request->post('age', null));
    		$model->watertempNum  = $request->get('watertempNum', $request->post('watertempNum', null));
    		$model->watertempTime  = $watertempTimeIn;
    		$model->numberOf  = $request->get('numberOf', $request->post('numberOf', null));
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('watertemp');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}

    
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editwatertemp', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
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
    
    	$query = Weight::find();
    	$query->orderBy(['id'=>SORT_ASC]);
    
    	$op = $request->post('op', $request->get('opss', ''));
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    		 
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}
    	}
    	
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
    				
    				$query = Typelist::find();
    				$query->orderBy(['id'=>SORT_ASC]);
    				$objTypelist = $query->all();
    				$arrTypelist = [];
    				foreach ($objTypelist as $dataTypelist){
    					$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
    				}
    					
    echo $this->render('weight', [
    		'lst' => $list,
    		'arrPond' => $arrPond,
    		'arrTypelist'=> $arrTypelist,
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
    	$query = Weight::find();
    	
    	if ($id){
    		$query->where("id=".$id);
    		$model = $query->one();
    		$status = "แก้ใข";
    	}else{
    		$model = new Weight();
    		$model->createTime = date('Y-m-d H:i:s', $currentTs);
    		$model->createBy = $identity->id;
    		$status = "บันทึก";
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
    		
    		$model->lastUpdateBy = $identity->id;
    		$model->lastUpdateTime = date('Y-m-d H:i:s', $currentTs);

    		if (trim($model->pondId) == ''){
    			$model->addError('pondId', 'ไม่ได้เลือก รุ่น และบ่อ');
    		}
    
    		if($model->save()) {
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด
    			Ui::setMessage('บันทึกข้อมูลสำเร็จ');
    			return $this->redirect('weight');
    		}else{
    			Ui::setMessage(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE), 'warning');
    		}
    		
    	}
    
    	$query = Pond::find()->where("status = 1")->orderBy(['type'=>SORT_ASC])->groupBy(['type']);
    	$arrTypelist = [0=>'กรุณาเลือกบ่อ  และรุ่นที่ต้องการ'];
    	$arrTypelist += \yii\helpers\ArrayHelper::map($query->all(), 'id' ,'title','type');
    	 
    	
    
    	echo $this->render('editweight', [
    			'model' => $model,
    			'arrTypelist'=> $arrTypelist,
    			'status' => $status,
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
    	
    	if ($op == "search") {
    		if (!empty($_REQUEST['type'])){
    			$type = $_REQUEST['type'];
    			if($type != 0){
    				$query->andWhere('type=:type',[':type'=> $type]);
    			}
    		}
    	
    		if (!empty($_REQUEST['q'])){
    			$item = $_REQUEST['q'];
    			$query->andWhere(['LIKE' ,'title','%'.$item.'%', false]);
    		}	
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
		
		$query = Typelist::find();
		$query->orderBy(['id'=>SORT_ASC]);
		$objTypelist = $query->all();
		$arrTypelist = [];
		foreach ($objTypelist as $dataTypelist){
			$arrTypelist[$dataTypelist->id] = $dataTypelist->name;
		}
		//var_dump($arrTypelist); exit();
		
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
        				'arrTypelist'=>$arrTypelist,
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
    		
    		$pond->status = 1; 
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
    			//เซ็ตให้ status รุ่นเก่า เป็น 0 = ไม่ active  ให้หมด 
    			\Yii::$app->db->createCommand("UPDATE pond SET status = 0 WHERE type = $type AND id != $pond->id ")->execute();
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
    
    private function oxygenDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Oxygen::find();
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
    
    private function phDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Ph::find();
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
    
    private function alkalinityDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Alkalinity::find();
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
    
    private function watertempDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Watertemp::find();
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
    
    private function tempDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Temp::find();
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
    
    private function ammoniaDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Ammonia::find();
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
    
    private function nitriteDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Nitrite::find();
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
    
    private function salinityDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Salinity::find();
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
    
    private function waterchangeDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Waterchange::find();
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
    
    private function otherDelete() {
    	$identity = \Yii::$app->user->getIdentity();
    	$currentTs =time();
    	$deleted = 0;
    	$arrIds = \Yii::$app->request->post('idCheck', NULL);
    	if(empty($arrIds))
    		$arrIds = \Yii::$app->request->get('idCheck', NULL);
    
    		if (is_array($arrIds) && !empty($arrIds)) {
    
    			foreach ($arrIds as $lst){
    				$queryUser = Other::find();
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
    	$larvae = $pond->larvae;
    	$items['pond'] = $Typelist->name.' '.$pond->title;
    	$items['age'] = $age;
    	$items['larvae'] = $larvae;

    	header('Content-Type: application/json');
    	//var_dump($items); exit();
    	echo json_encode($items);
    }
    
    public function init() {
        parent::init();
        $this->layout = 'layoutstyle';
    }

}
