<?php
namespace backend\components;

use yii\base\Widget;

class RelatedList extends Widget {
	public $items;
	public $view = 'list';
	public function run() {
		echo $this->render('relatedList/' . $this->view, array(
			'items' => $this->items,
		));
	}	
}