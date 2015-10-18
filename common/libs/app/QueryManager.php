<?php
namespace app;

use Yii;
use yii\web\Controller;
use common\models\Content;
use app\Workflow;
use yii\mongodb;
use common\models\Gallery;
use yii\base\Object;
use common\models\Feed;
use common\models\WeatherForecast;
use common\models\Quote;
use common\models\Widget;
use common\models\Oil;
use common\models\Media;
use common\models\FeedContent;

class QueryManager{
	public static function QueryMgr($condition = array()){
		$collection = Yii::$app->mongodb->getCollection('lists');
		$mongObj = $collection->find($condition);
		
		if(!empty($mongObj)){
			$arrIdContent = [];
			foreach ($mongObj as $index=>$data){
				if(!empty($data['items'])){
					foreach ($data['items'] as $key){
						$arrIdContent[] = self::getData($key[0], $key[1]);
					}
				}	
			}
			if(empty($arrIdContent))
				return false;
			else
				return $arrIdContent;
		}
	}
	
	public static function getData($type,$id){
		if(!empty($type) && !empty($id)){
			switch ($type){
				case SectionConfig::TYPE_CONTENT: $model = Content::findOne(['id'=> $id]); break;
				case SectionConfig::TYPE_GALLERY: $model = Gallery::findOne(['id'=> $id]); break;
				case SectionConfig::TYPE_FEED   : $model = Feed::findOne(['id'=> $id]); break;
				case SectionConfig::TYPE_FEED_CONTENT   : $model = FeedContent::getFeedItems($id,3);    break;
				case SectionConfig::TYPE_WEATHER: $model = WeatherForecast::findOne(['id'=> $id]); break;
				case SectionConfig::TYPE_QUOTE  : $model = Quote::findOne(['id'=> $id]); break;
				case SectionConfig::TYPE_WIDGET : $model = Widget::findOne(['id'=> $id]); break;
			}
			if(empty($model))
				return array();
			else
				return $model;
		}
	}
}