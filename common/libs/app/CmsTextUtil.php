<?php
namespace app;

use common\models\Media;
use common\models\Document;
use yii\helpers\Url;
class CmsTextUtil {

	public static function decode($content) {
		$search = '/<(entity|media|youtube)([^>]*)>(.*)<\/\\1>/msU';
		$content = preg_replace_callback($search, array(__CLASS__, '_decoder'), $content);
		
		return $content;
	}

	/**
	 * Normalize html text:
	 *  - ปรับการบันทึกค่า entity ต่างๆ
	 * @param string $content
	 */
	public static function normalize($content) {
		$search = '/<(media|entity|youtube)(.*)>(.*)<\/\\1>/msU';
		$content = preg_replace($search, '<$1$2></$1>', $content);

		return preg_replace($search, '<$1$2></$1>', $content);
	}
	
	private static function _entityDecoder($attributes) {
	
		/* check social facebook instagram twitter  */
			$itemNo = NULL;
			switch($attributes['type']) {
				case 'document':
						$itemNo = $attributes['itemno'];
						$entityType = Entity::mapType($attributes['type']);
						$entity = Document::findOne(['type'=> 5, 'refId'=>$attributes['id'], 'itemNo'=> $itemNo,]);
					break;
				default:
						$entityType = Entity::mapType($attributes['type']);
						$entity = Entity::getInstance($entityType, $attributes['id']);
					break;
			}
			
			
			if (empty($entity)) return;
	
			
			switch($attributes['type']) {
				case 'document':
					$arr = explode(".", $entity->srcPath);
					$extension = $arr[count($arr) - 1];
					$thumbnail = $extension.".png";
					$thumbnailUrl = Url::toRoute(['/global/img/'.$thumbnail]);
						$str = "<entity type=\"{$attributes['type']}\" data-itemno=\"{$attributes['itemno']}\" data-id=\"{$attributes['id']}\">" ;
						$str.='<img src="'.$thumbnailUrl.'" alt="" width="30" height="30">';
					break;
				default:
						$str = "<entity type=\"{$attributes['type']}\" data-id=\"{$attributes['id']}\">" .
						$entity->getPreview(array(
								Media::ENCODE_WIDTH => 100,
						));
					break;
			}
			
			switch($entityType) {
				case Entity::TYPE_PERSON:
					$caption = $entity->getDisplayName();
					break;
				case Entity::TYPE_DOCUMENT:
					$caption = $entity->caption;
					break;
				default:
					$caption = $entity->title;
			}
			$str .= "<p class=\"caption\">" . Entity::$arrTitle[$entityType] . ": $caption</p></entity>";

		return $str;
	}

	private static function _mediaDecoder($attributes){
		$baseUrl = \Yii::$app->homeUrl;
		$id = $attributes['id'];
		$type = Entity::mapType($attributes['object']);
		$itemNo = $attributes['itemno'];

		$items = array();
		$output ='';

		/* $criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('refId'=> $id, 'type'=> $type, 'itemNo'=>$itemNo)); */
		$query = Media::find();
		$query->andWhere(['refId'=> $id, 'type'=> $type, 'itemNo'=>$itemNo]);
		$options = array(Media::ENCODE_WIDTH => 100);
		$lst = Media::getItems($query, $options);
		$items = array_shift($lst);

		$output .= "<media id=\"{$items['refId']}\" object=\"{$attributes['object']}\" itemno=\"{$items['itemNo']}\">";
		$output .= '<img src="'.$baseUrl.'media/'.$items['fullPath'].'">';
		$output .= '<p class="caption">'.$items['caption'].'</p></media>';

		return $output;
	}

	private static function _decoder($matches) {
		preg_match_all('/(\w+)=(\'|")(\w+)\\2/',$matches[2], $matches2, PREG_SET_ORDER);
		$attributes = array();
		foreach($matches2 as $attrInfo) {
			$attributes[$attrInfo[1]] = $attrInfo[3];
		}
		switch ($matches[1]){
			case 'entity':
				return self::_entityDecoder($attributes);
				break;
			case 'media':
				return self::_mediaDecoder($attributes);
				break;
		}
	}
	
	private static function _youtubeDecoder($attributes) {
		$str = "<youtube key=\"{$attributes['key']}\">
			<iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/{$attributes['key']}\" frameborder=\"0\"></iframe>
		</youtube>";
		
		return $str;
	}
}