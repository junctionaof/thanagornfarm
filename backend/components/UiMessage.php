<?php
namespace backend\components;

use yii\base\Widget;
use yii\web\Session;

class UiMessage extends Widget{
	
	/**
	 * สร้าง notify message ผ่าน CWebUser->setFlash()
	 * @param string $message
	 * @param string $level
	 */
	public static function setMessage($message, $level = 'info') {
		$session = \Yii::$app->session;
		$session->setFlash('message', $message);
		$session->setFlash('messageLevel', $level);
	}
	
	public function run() {
		$message = \Yii::$app->session->getFlash('message');
		$messageLevel = \Yii::$app->session->getFlash('messageLevel', 'success');
		$str = '';
		if($message){
			$str = <<<EOT
<div class="note note-$messageLevel">
	<p>$message</p>
</div>
EOT;
			$js = <<<EOT
			$( document ).ready(function( $ ) {
	 var noteDiv = $("div.note");
	 if(noteDiv.closest("html").length > 0){
		 setInterval(function () {
			 noteDiv.remove();        
	     },10000);
	 } });
EOT;
			
			$this->view->registerJs($js, \yii\web\View::POS_READY, 'note.remove');
		}

			echo $str;
	}
	
	
}
