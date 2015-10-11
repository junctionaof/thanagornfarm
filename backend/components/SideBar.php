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
						'name'  => 'เนื้อหา/ข่าว content',
						'url'   => $baseUrl.'/content/list',
						'rule'  => 'tpbs.content',
						'icon'  => 'fa-pencil-square'
				),
				array(
						'name'  => 'แกลอรี่ (gallery)',
						'url'   => $baseUrl.'/gallery/list',
						'rule'  => 'tpbs.gallery',
						'icon'  => 'fa-camera'
				),
				
				array(
						'name'  => 'วีดีโอ (video)',
						'icon'  => 'fa-youtube-play',
						'rule'  => 'tpbs.gallery',
						'url'   => $baseUrl.'/video/',
				),
				array(
						'name'  => 'เสียง (audio)',
						'icon'  => 'fa-volume-up',
						'rule'  => 'tpbs.gallery',
						'url'   => $baseUrl.'/audio/',
				),
				array(
						'name'  => 'การจัดการเวบ (web)',
						'icon'  => 'fa-list-alt',
						'rule'  => 'tpbs.web',
						'sub'   => array(
		
								array(
										'name'  => ' หน้าแรก',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_HOME.'',
										'icon'  => 'fa-home',
										'rule'  => 'tpbs.web.home'
								),
								array(
										'name'  => ' ข่าวหน้าแรก',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_HOME_NEWS.'',
										'icon'  => 'fa-edit',
										'rule'  => 'tpbs.web.homenews'
								),
								array(
										'name'  => ' การเมือง',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_POL.'',
										'icon'  => 'fa-group',
										'rule'  => 'tpbs.web.pol'
								),
								array(
										'name'  => ' เศรษฐกิจ',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_ECO.'',
										'icon'  => 'fa-money',
										'rule'  => 'tpbs.web.eco'
								),
								array(
										'name'  => ' ต่างประเทศ',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_FOREIGN.'',
										'icon'  => 'fa-globe',
										'rule'  => 'tpbs.web.oversea'
								),
								array(
										'name'  => ' สังคม',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_SOCIAL.'',
										'icon'  => 'fa-home',
										'rule'  => 'tpbs.web.social'
								),
								array(
										'name'  => ' กีฬา',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_SPORT.'',
										'icon'  => 'fa-trophy',
										'rule'  => 'tpbs.web.sport'
								),
								array(
										'name'  => ' ศิลปบันเทิง',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_ENT.'',
										'icon'  => 'fa-comments',
										'rule'  => 'tpbs.web.ent'
								),
								array(
										'name'  => ' ภูมิภาค',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_PROVINCIAL.'',
										'icon'  => 'fa-picture-o',
										'rule'  => 'tpbs.web.provincial'
								),
								array(
										'name'  => ' อาชญากรรม',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_CRIME.'',
										'icon'  => 'fa-gavel',
										'rule'  => 'tpbs.web.crime'
								),
								array(
										'name'  => ' ภัยพิบัติ',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_DISASTER.'',
										'icon'  => 'fa-cloud',
										'rule'  => 'tpbs.web.crime'
								),
								array(
										'name'  => ' ไลฟ์สไตล์',
										'url'   => $baseUrl.'/web/page?section='.SectionConfig::SECTION_NAME_LIFE.'',
										'icon'  => 'fa-gamepad',
										'rule'  => 'tpbs.web.crime'
								),
						)
				),
				array(
						'name'  => 'พยากรณ์อากาศ (weather)',
						'icon'  => 'fa-umbrella',
						'rule'  => 'tpbs.weather',
						'url'   => $baseUrl.'/weather/list',
				),
				
				array(
						'name'  => 'อินโฟกราฟิก (infographic)',
						'icon'  => 'fa-picture-o',
						'rule'  => 'tpbs.weather',
						'url'   => $baseUrl.'/infographic/list',
				),
				
				array(
						'name'  => 'หัวข้อไทยพีบีเอสโฟกัส ',
						'icon'  => 'fa-star',
						'rule'  => 'tpbs.feed',
						'url'   => $baseUrl.'/feed/list',
				),
				array(
						'name'  => 'เนื้อหาไทยพีบีเอสโฟกัส',
						'icon'  => 'fa-star-o',
						'rule'  => 'tpbs.feedcontent',
						'url'   => $baseUrl.'/feedcontent/list',
				),
				array(
						'name'  => 'คำในข่าว(quote)',
						'icon'  => 'fa-comments-o',
						'rule'  => 'tpbs.quote',
						'url'   => $baseUrl.'/quote/list',
				),
				array(
						'name'  => 'ทันข่าวเด่น',
						'icon'  => 'fa-clock-o',
						'rule'  => 'tpbs.quote',
						'url'   => $baseUrl.'/feedcontent/tunkaowdenlist',
				),
				array(
						'name'  => 'ข้อมูลกำหนดเอง (widget)',
						'icon'  => 'fa-wrench',
						'rule'  => 'tpbs.widget',
						'url'   => $baseUrl.'/widget/list',
				),
				array(
						'name'  => 'ไมโครไซต์ (microsite)',
						'icon'  => 'fa-qrcode',
						'rule'  => 'tpbs.microsite',
						'url'   => $baseUrl.'/microsite/page',
				),
				
				array(
						'name'  => 'แท็กยอดฮิต (แท็กข่าว)',
						'icon'  => 'fa-tags',
						'rule'  => 'tpbs.tag',
						'url'   => $baseUrl.'/tag/listcloud',
				),
				array(
						'name'  => 'ราคานํ้ามัน',
						'icon'  => 'fa-truck',
						'rule'  => 'tpbs.oil',
						'url'   => $baseUrl.'/web/oil',
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
		
								array(
										'name'  => 'log การใช้งานข่าว',
										'url'   => $baseUrl.'/log/contentlog',
										'icon'  => 'fa-user',
										'rule'  => 'tpbs.admin.contentlog'
								),
		
								array(
										'name'  => 'log การเข้าสู่ระบบ',
										'url'   => $baseUrl.'/log/accesslog',
										'icon'  => 'fa-user',
										'rule'  => 'tpbs.admin.accesslog'
								),
						)
				),
		);
		echo $this->render('sideBar',[
				'primary_nav' => $primary_nav,
		]);
		
	}	
}