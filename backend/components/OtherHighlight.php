<?php
namespace backend\components;

use yii\base\Widget;

class OtherHighlight extends Widget {
	public $items;
	public $type;
	public $view = 'list';
	public function run() {
		echo $this->render('otherHighlight/' . $this->view, array(
			'items' => $this->items,
			'type' => $this->type
		));
	}	
}