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
		$baseUrl = \Yii::getAlias('@web');
		//เมณู และ Sub menu
		$primary_nav = array(
				array(
						'name'  => 'หน้าหลัก',
						'url'   => $baseUrl.'/',
						'rule'  => 'tpbs.home',
						'icon'  => 'fa-home'
				),
				
				array(
						'name'  => 'บันทึกการเลี้ยงกุ้ง',
						'url'   => $baseUrl.'/content/list',
						'icon'  => 'fa-list-alt',
						'rule'  => 'tpbs.admin',

				),
				
				array(
						'name'  => 'พยากรณ์อากาศ ',
						'icon'  => 'fa-umbrella',
						'rule'  => 'tpbs.oil',
						'url'   => $baseUrl.'/weather/list',
				),
				
				array(
						'name'  => 'ข้อมูลฟาร์ม',
						'icon'  => 'fa-truck',
						'rule'  => 'tpbs.oil',
						'url'   => $baseUrl.'/web/2',
				),
				
				array(
		
						'name'  => 'จัดการระบบ',
						'icon'  => 'fa-list-alt',
						'rule'  => 'tpbs.admin',
						'sub'   => array(
		
								array(
										'name'  => 'จัดการผู้ใช้งานระบบ',
										'url'   => $baseUrl.'/user/list',
										'icon'  => 'fa-user',
										'rule'  => 'tpbs.admin.user'
								),
						)
				),
		);
		echo $this->render('sideBar',[
				'primary_nav' => $primary_nav,
		]);
		
	}	
}