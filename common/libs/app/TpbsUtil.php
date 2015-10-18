<?php
namespace app;

use common\models\Media;
use yii\helpers\Url;
use common\models\FeedContent;
use common\models\Document;
use common\models\Quote;

class TpbsUtil{
	private $_tags = array();
	public $_index = 0;

	private static function _quoteReplace($entity, $attributes = array()){
		$baseUrl = \Yii::$app->params['newsSocialUrl'];
		$title = $entity->title;
		$titleEncode = urlencode($title);
		$image = $entity->getPreviewUrl();
		$urlPath = $baseUrl.FPUtil::getLink($entity);
		$url = urlencode($urlPath);
		$baseUrl = \Yii::getAlias('@web');

		$str = <<<EOT
		<!-- QUOTE BLOCK -->
    <section class="quote-block">
        <h2 class="hide">$title</h2>
        <img src="$image" class="img-responsive quote-block__image" />
        <div class="quote-block__social">
            <a href="https://www.facebook.com/sharer/sharer.php?app_id=299133566841790&sdk=joey&u=$url&display=popup&ref=plugin&src=share_button" class="quote-block__social__item" target="_blank">
                <i class="fa fa-facebook"></i>
            </a>
            <a href="https://plus.google.com/share?url=$url" class="quote-block__social__item" target="_blank">
                <i class="fa fa-google-plus"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?hashtags=ThaiPBSNews&original_referer=$url&ref_src=twsrc%5Etfw&text=$titleEncode&tw_p=tweetbutton&url=$url&via=ThaiPBSNews" class="quote-block__social__item" target="_blank">
                <i class="fa fa-twitter"></i>
            </a>
            <a href="http://line.me/R/msg/text/?$url" class="quote-block__social__item" target="_blank">
                <img src="$baseUrl/images/linebutton_bl.png" width="20" height="20" class="normal" alt="LINE it!" />
                <img src="$baseUrl/images/linebutton_wh.png" width="20" height="20" class="hover" alt="LINE it!" />
            </a>

        </div>
    </section>
    <!--/ QUOTE BLOCK -->
EOT;
		return $str;
	}
	
	private static function _documentReplace($entity, $attributes = array()) {
		$arr = explode(".", $entity->srcPath);
		$extension = $arr[count($arr) - 1];
		$thumbnail = $extension.".png";
		$thumbnailUrl = Url::toRoute(['/images/'.$thumbnail]);
		$param = '1='.$entity->type.'&2='.$entity->refId.'&3='.$entity->itemNo;
		$docUri = Url::toRoute(['/document/download?'.$param]);
		$caption = $entity->caption;
		
		$str = <<<EOT
			<!-- ATTACHMENT -->
			<section class="attachment">
			    <h2 class="hide">ดาวโหลดไฟล์</h2>
			    <a class="attachment__item" href="$docUri">
			        <span class="attachment__item__icon">
			            <img src="$thumbnailUrl" class="img-responsive" alt="ดาวโหลดไฟล์" />
			        </span>
			        <h2 class="attachment__item__title">ดาวโหลดไฟล์</h2>
			        <h3 class="attachment__item__file-info">
			            <i class="fa fa-download"></i>
			        	$caption
			            <!-- <span class="type">PDF - 1.8 MB</span> -->
			        </h3>
			    </a>
			</section>
			<!--/ ATTACHMENT -->
EOT;
	
		return $str;
	}
	
	private static function _galleryReplace($entity, $attributes = array()) {
		$entityId = $entity->id;
		
		$query = Media::find();
		$query->andWhere(['type' => Entity::TYPE_GALLERY, 'refId' => $entity->id]);
		$query->orderBy(['orderNo'=> SORT_ASC]);
		$total = $query->count();
		$lst = $query->all();

		$url = Url::toRoute(['/gallery/'.$entity->id]);
		$html = '';
		$moreHtml = '';
		if($lst){
			$moreImage = (count($lst) > 4)?true:false;
			
			foreach ($lst as $index => $media){
				if($moreImage && $index > 2){
					if($index == 3){
						$encodedThumbnail = $media->getPublishUri(array(
								Media::ENCODE_WIDTH => 240,
								Media::ENCODE_HEIGHT => 159,
						));
						$thumbnailUrl = Url::toRoute(['/media/'.$encodedThumbnail]);
						
						$moreHtml='<div class="img-item view-more">
	                             <a href="'.$url.'" class="view-more-link">
	                                     <div class="view-more-overlay">
	                                            <span>'.$total.'<br/>
	                                            Photos<br/>
	                                            More</span>
	                                     </div>
	                         	      <img src="'.$thumbnailUrl.'" itemprop="thumbnail" alt="Image description" />
	                              </a>
	                         </div>';
						continue;
					}else{
						continue;
					}
				}
				
				$encoded = $media->getPublishUri(array(
						Media::ENCODE_WIDTH => $media->width,
						Media::ENCODE_HEIGHT => $media->height,
				));
				$encodedThumbnail = $media->getPublishUri(array(
						Media::ENCODE_WIDTH => 240,
						Media::ENCODE_HEIGHT => 159,
				));
				$mediaUrl = Url::toRoute(['/media/'.$encoded]);
				$thumbnailUrl = Url::toRoute(['/media/'.$encodedThumbnail]);
				
				$html.='<figure class="img-item" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" data-index="0">
                                                <a href="'.$mediaUrl.'" itemprop="contentUrl" data-size="'.$media->width.'x'.$media->height.'">
                                                    <img src="'.$thumbnailUrl.'" itemprop="thumbnail" alt="'.$media->caption.'" />
                                                </a>
                                                <meta itemprop="width" content="'.$media->width.'">
                                                <meta itemprop="height" content="'.$media->height.'">
                                                <figcaption class="caption" itemprop="caption description">'.$media->caption.'</figcaption>
                                            </figure>';
			}

		}
		
		//$entityUrl = $this->url->get('gallery/' . $entity->id);
		$str = <<<EOT
			<div class="gallery-list-block gallery-widget">
                                <div class="gallery-list-bg">
                                </div>
                                <div class="gallery-list-content">
                                    <header>
                                        <h3>$entity->title</h3>
                                    </header>
                                    <div class="gallery-list-holder">
                                        <div class="gallery-list-item" itemscope itemtype="http://schema.org/ImageGallery">
                                            $html
                                            $moreHtml
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
EOT;
	
		return $str;
	}
	
	private static function _feedReplace($entity, $attributes = array()) {
		$entityId = $entity->id;
		$date = DateUtil::th_date(DateUtil::SD_FMT_TH, time());
		
		$query = FeedContent::find();
		$query->andWhere(['feedId'=> $entityId, 'status'=> Workflow::STATUS_PUBLISHED]);
		$query->limit = 3;
		$query->orderBy(['publishTime'=> SORT_DESC]);
		$lst = $query->all();
		
		$html = '';
		if($lst){
			foreach ($lst as $object){
				$time = date(DateUtil::ST_FMT_TWOPOSITION_FORM, $object->ts);
				$html.='<li class="first">
                     <div class="time">'.$time.'</div>
                     <div class="desc">'.$object->title.'</div>
                 </li>';
			}
		}
		
		$str = <<<EOT
			<div class="live-block-stream">
                                <header>
                                    <h1>LIVE</h1>
                                </header>
                                <section>
                                    <h2><i></i> $entity->title <span class="date">$date</span></h2>
                                    <ul class="list">
                                        $html
                                    </ul>
                                    <a href="javascript:;" class="view-all">ดูทั้งหมด <i class="fa fa-chevron-circle-right"></i></a>
                                </section>
				</div>
EOT;
		return $str;
	}
	
	private static function _entityDecoder($attributes, $matches) {
		if(isset($attributes['type'])){
			switch ($attributes['type']){
				case 'document':
						$entityType = Entity::mapType($attributes['type']);
						$itemNo = $attributes['data-itemno'];
						$entityType = Entity::mapType($attributes['type']);
						$entity = Document::findOne(['type'=> 5, 'refId'=>$attributes['data-id'], 'itemNo'=> $itemNo,]);
					break;
				default:
					$entityType = Entity::mapType($attributes['type']);
					$entity = Entity::getInstance($entityType, $attributes['data-id']);
					break;
			}
		}
	
		if (empty($entity)) return;
	
		switch($entityType) {
			case Entity::TYPE_DOCUMENT:
				$result = self::_documentReplace($entity, $attributes);
			break;
			case Entity::TYPE_GALLERY:
				$result = self::_galleryReplace($entity, $attributes);
			break;
			case Entity::TYPE_FEED:
				$result = self::_feedReplace($entity, $attributes);
				break;
			case Entity::TYPE_QUOTE:
				$result = self::_quoteReplace($entity, $attributes);
				break;
			default:
				$result = $entity->getPreview(array(
				Media::ENCODE_WIDTH => 532,
				));
			break;
		}
	
		return $result;
	}
	
	
	private static function _mediaDecoder($attributes, $content) {
		if (isset($attributes['itemno']))
			$itemNo = (int)$attributes['itemno'];
		else
			$itemNo = null;
		$entityType = isset($attributes['object'])?Entity::mapType($attributes['object']):null;
		$refId = isset($attributes['id'])?$attributes['id']:null;
	
		if (empty($itemNo) || empty($entityType) || empty($itemNo) )
			return null;
	
		$params = array(
				Media::ENCODE_ENTITY => $entityType,
				Media::ENCODE_ID => $refId,
				Media::ENCODE_ITEMNO => $itemNo,
		);
		if (isset($attributes['width']))
			$params[Media::ENCODE_WIDTH] = $attributes['width'];
		else
			$params[Media::ENCODE_WIDTH] = 770;
		if (isset($attributes['height']))
			$params[Media::ENCODE_HEIGHT] = $attributes['height'];
		
		
		$query = Media::find();
		$query->andWhere(['type'=> $entityType, 'refId'=> $refId, 'itemNo'=> $itemNo]);
		$media = $query->one();
		
		if (empty($media)) return;
		$encoded = $media->getPublishUri($params);
		
		$mediaUrl = Url::toRoute(['/media/'.$encoded]);
		$imgTag = "<img src=\"$mediaUrl\" class=\"img-responsive\">";
		/* if (!empty($media->caption))
			$captionTag = "<figcaption>{$media->caption}</figcaption>";
		else
			$captionTag = ''; */

		$str = <<<EOT
$imgTag
EOT;
		return $str;
	}
	
	private static function _decoder($matches) {
		preg_match_all('/([-\w]+)=(\'|")([^\\2]+)\\2/U',$matches[2], $matches2, PREG_SET_ORDER);
		$attributes = array();
		foreach($matches2 as $attrInfo) {
			$attributes[$attrInfo[1]] = $attrInfo[3];
		}
		switch($matches[1]) {
			case 'entity':
				return self::_entityDecoder($attributes, $matches[3]);
				break;
			case 'media':
				return self::_mediaDecoder($attributes, $matches[3]);
				break;
		}
	}
	
	public static function add($html, $key = 'default') {
		if (!isset($this->_tags[$key])) {
			$this->_tags[$key] = array();
		}
	
		array_push($this->_tags[$key], $html);
	}
	
	public static function decode($content, $options = array()) {
		$search = '/<(entity|media)([^>]*)>(.*)<\/\\1>/msU';
	
		//if (!isset($options['followLink']))
			/* $content = $this->noFollowLink($content);
	
		$content = $this->blankTargetLink($content); */
	
		return preg_replace_callback($search, array(__CLASS__, '_decoder'), $content);
	}
	
	public static function get($key = 'default') {
		$result = '';
		if (isset($this->_tags[$key])) {
			foreach($this->_tags[$key] as $html)
				$result .= $html;
		}
	
		return $result;
	}
	
	public static function set($html, $key = 'default', $subKey = 0) {
		if (!isset($this->_tags[$key])) {
			$this->_tags[$key] = array();
		}
	
		$this->_tags[$key][$subKey] = $html;
	}
}