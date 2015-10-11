<?php
namespace backend\components;

use yii\base\Widget;

class PortletRight extends Widget {
	public $sectionConfig;
	public function run() {
		echo $this->render('portletRight',['sectionConfig'=> $this->sectionConfig]);
	}	
}