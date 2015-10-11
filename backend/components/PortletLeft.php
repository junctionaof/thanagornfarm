<?php
namespace backend\components;

use yii\base\Widget;

class PortletLeft extends Widget {
	public function run() {
		echo $this->render('portletLeft');
	}	
}