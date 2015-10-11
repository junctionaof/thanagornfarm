<?php
namespace backend\controllers;

use yii\filters\AccessControl;

class BaseController extends \yii\web\Controller {
	public function behaviors() {
		return [
				'access' => [
					'class' => AccessControl::className(),
					'rules' => [ 
							[ 
								'allow' => true,
								'roles' => ['@'] 
							] 
					] 
			],
		];
	}
}