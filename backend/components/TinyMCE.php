<?php
namespace backend\components;

use yii\base\Widget;
use yii\helpers\Html;

class TinyMCE extends Widget {
	public function run() {
		echo $this->render('tinyMCE');
	}
} 
?>