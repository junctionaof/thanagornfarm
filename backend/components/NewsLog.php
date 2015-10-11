<?php
namespace backend\components;

use yii\base\Widget;
use yii\helpers\Html;

use yii\mongodb;
use app\Entity;
use common\models\Collection\Log;
use common\models\User;
use common\models\LogSystem;

class NewsLog extends Widget {
	public $_Content = NULL;
	public $objectId;
	public $entity;
	
	public function run() {
		$attributes = array();
		$arrUserId = array();
		$arrUser = array();
		$arrParams = array();
		
		switch ($this->entity){
			case Entity::TYPE_CONTENT:
				$arrParams = array(
					'entityType' => Entity::TYPE_CONTENT,
					'categories' => 'audit.content.update',
				);
				break;
		}
		
		$arrLog = [];
		if(!empty($this->_Content->id)){
			$arrParams['refId'] = $this->_Content->id;
			
			$query = LogSystem::find();
			$query->andWhere($arrParams);
			$query->orderBy(['ts'=> SORT_DESC]);
			$arrLog = $query->all();
			
			if($arrLog){
				foreach ($arrLog as $object){
					$arrUserId[$object->userId]=$object->userId;
				}
			}
		}
		
		if($arrUserId){
			$query = User::find();
			$query->andWhere(['in', 'id', $arrUserId]);
			$user = $query->all();
			if($user){
				foreach ($user as $object){
					$arrUser[$object->id] = $object->firstName . ' - ' . $object->lastName;
				}
			}
		}

		echo $this->render('content/newsLog', [
			'Content' => $this->_Content,
			'arrLog'=>$arrLog, 
			'arrUser'=>$arrUser
		]);
	}
} 
?>