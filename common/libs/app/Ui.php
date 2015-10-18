<?php
namespace app;

class Ui {
	public static function setMessage($msg, $level = 'info', $title = null) {
		\Yii::$app->session->setFlash('message.content', $msg);
		\Yii::$app->session->setFlash('message.level', $level);
	}
}