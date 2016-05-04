<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

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


/** test
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'about' ,'contact'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    
    public function init() {
    	parent::init();
    	
    }
    
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
    			$arrPorn = [];
    	       	$objTypelist = Typelist::find()->all();
    				foreach ($objTypelist as $dataTypelist){
    						//$arrTypelist[] = 
    					    $objPond = Pond::find()->andwhere("status = 1")->andwhere(['type'=>$dataTypelist->id])->all();
    						foreach ($objPond as $dataPond){
    							$arrPorn[] = ['typeId'=>$dataTypelist->id,'typelist'=>$dataTypelist,'title'=>$dataPond->title,'larvae'=>$dataPond->larvae,];
    						}
    					}
    					
    					
    					
    	$this->layout = 'layoutstyle';
        return $this->render('index',[
                'arrPorn' => $arrPorn,
            ]);
    }
    
    public function actionAbout()
    {
    	$this->layout = 'layoutstyle';
    	return $this->render('about');
    }
    
    public function actionContact()
    {
    	$this->layout = 'layoutstyle';
    	return $this->render('contact');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
        	$this->layout = 'login';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
