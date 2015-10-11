<?php
namespace backend\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii\base\Object;

use common\models\ContentPublish;

class PublishTab extends Widget {
	public $_Content = NULL;
	public $arrOtherCategory;
	public function run() {
		if(!empty($this->_Content->id)){
			$query = ContentPublish::find();
			$query->andWhere(['contentId' => $this->_Content->id]);
			$contentPublish = $query->one();
			if(empty($contentPublish)){
				$contentPublish = new ContentPublish();
				$contentPublish->tweetContent = $this->_Content->title;
			}else{
				$contentPublish->tweetContent = ($contentPublish->tweetContent)?$contentPublish->tweetContent:$this->_Content->title;
			}
		}else{
			$contentPublish = new ContentPublish();
			$contentPublish->tweetContent = $this->_Content->title;
		}
		
		echo $this->render('content/publishTab', [
			'Content' => $this->_Content,
			'contentPublish'=> $contentPublish,
			'arrOtherCategory' => $this->arrOtherCategory,
		]);
	}
} 
?>