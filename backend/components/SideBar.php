<?php
namespace backend\components;
use yii\base\Widget;
use yii\helpers\Url;
use common\models\User;
use yii\helpers\Html;
use app\SectionConfig;
$identity = \Yii::$app->user->getIdentity();
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;

class SideBar extends Widget {
	public function run() {
		echo $this->render('sideBar');
		
	}	
}