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

class WeatherController extends BaseController{
	
	public function init() {
		parent::init();
		$this->layout = 'layoutstyle';
	}
	

public function actionList()
{
	$this->layout = 'layoutstyle';
	return $this->render('list');
}

}