<?php
namespace backend\components;

use yii\base\Widget;

class TopMenu extends Widget {
	public function run() {
		echo $this->render('topMenu');
	}	
}