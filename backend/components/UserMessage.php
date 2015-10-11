<?php
namespace backend\components;

use yii\base\Widget;
use yii\helpers\Html;

class UserMessage extends Widget{
	public $message;
	
	public function init(){
		parent::init();
		if($this->message===null){
			$this->message= '';
		}else{
			$this->message= ''.$this->message;
		}
	}
	
	public function run(){
		return Html::encode($this->message);
	}
}
?>