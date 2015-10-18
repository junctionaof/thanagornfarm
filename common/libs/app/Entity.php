<?php
namespace app;

use common\models\Gallery;
use common\models\Feed;
use common\models\FeedContent;
use common\models\WeatherForecast;
use common\models\Quote;
use common\models\Widget;
use common\models\Content;
use common\models\Document;
use common\models\InfoGraphic;

class Entity {
	const EXPORT_STRING = 1;
	const EXPORT_ARRAY = 2;
	
	const TYPE_TOPIC = 1;
	const TYPE_AGENDA = 2;
	const TYPE_TASK = 3;
	const TYPE_RESOURCE = 4;
	const TYPE_CONTENT = 5;
	const TYPE_PROGRAM = 6;
	const TYPE_SCRIPT = 7;
	const TYPE_PERSON = 8;
	const TYPE_GALLERY = 9;
	const TYPE_CALENDAR = 10;
	const TYPE_NOVEL = 11;
	const TYPE_ACTIVITY = 12;
	const TYPE_BUNNY = 13;
	const TYPE_VIDEO = 14;
	const TYPE_LOTTERY = 15;
	const TYPE_MEDIA_COLLECTION = 16;

	const TYPE_LIVEREPORT = 18;
	const TYPE_NEWSPAPER = 19;
	const TYPE_VIDEO_PLAYLIST = 20;
	const TYPE_CARTOON = 21;
	const TYPE_MEDIA = 22;
	const TYPE_FEEDCONTENT = 23;
	const TYPE_FAQ = 24;
	const TYPE_INFOGRAPHIC = 25;
	const TYPE_FEED = 26;
	const TYPE_WATCHTOPIC = 27;
	const TYPE_EMBED = 28;
	
	const TYPE_WEATHER = 29;
	
	const TYPE_SPORT_PLAYER = 31;
	const TYPE_SPORT_TEAM = 32; 	
	const TYPE_QUOTE = 33;
	const TYPE_WIDGET = 34;
	const TYPE_TV_HIGHLIGHT =51;
	const TYPE_TV_ANCHOR = 52;
	const TYPE_TV_PROGRAM = 53;
	const TYPE_TV_SCHEDULE = 54;
	
	const TYPE_DOCUMENT = 200;

	const TYPE_BLOG = 60;
	const TYPE_POST = 80;
	const TYPE_USER = 99;
	
	public static $arrTitle = array(
			self::TYPE_ACTIVITY => 'กิจกรรม',
			self::TYPE_AGENDA => 'หมายข่าว',
			self::TYPE_BLOG => 'ผู้ประกาศข่าว',
			self::TYPE_BUNNY => 'บันนี่',
			self::TYPE_CALENDAR => 'ปฏิทิน',
			self::TYPE_CARTOON => 'การ์ตูน',
			self::TYPE_CONTENT => 'เนื้อหา',
			self::TYPE_DOCUMENT => 'เอกสาร',
			self::TYPE_FAQ => 'FAQ',			
			self::TYPE_FEEDCONTENT => 'FeedContent',
			self::TYPE_FEED =>'Feed',
			self::TYPE_GALLERY => 'อัลบั้มภาพ',
			self::TYPE_INFOGRAPHIC => 'Infographic',
			self::TYPE_LIVEREPORT => 'Live Report',
			self::TYPE_LOTTERY => 'ลอตเตอรี่',
			self::TYPE_MEDIA_COLLECTION => 'Collection',
			self::TYPE_NOVEL => 'นิยาย',
			self::TYPE_PERSON => 'บุคคล',
			self::TYPE_PROGRAM => 'โปรแกรมโทรทัศน์',
			self::TYPE_RESOURCE => 'ทรัพยากร',
			self::TYPE_SCRIPT => 'สคริปต์โทรทัศน์',
			self::TYPE_SPORT_PLAYER => 'นักฟุตบอล',
			self::TYPE_SPORT_TEAM => 'ทีมฟุตบอล',
			self::TYPE_QUOTE => 'คำคม',
			self::TYPE_WIDGET => 'ข้อมูลกำหนดเอง',
			self::TYPE_TASK => 'งาน',
			self::TYPE_TOPIC => 'ประเด็น',
			self::TYPE_TV_HIGHLIGHT=> 'ไฮไลต์โทรทัศน์',
			self::TYPE_TV_ANCHOR => 'ผู้ประกาศ',
			self::TYPE_TV_PROGRAM =>'รายการโทรทัศน์',
			self::TYPE_TV_SCHEDULE => 'ผังออกอากาศ',
			self::TYPE_VIDEO => 'วิดีโอ',
			self::TYPE_VIDEO_PLAYLIST => 'Playlist',	
			self::TYPE_WATCHTOPIC =>'watchTopic',
			self::TYPE_WEATHER => 'พยากรณ์อากาศ',
			self::TYPE_USER => 'user',
	);

	public static $arrType = array(
			self::TYPE_ACTIVITY => 'activity',
			self::TYPE_AGENDA => 'agenda',
			self::TYPE_BLOG => 'blog',
			self::TYPE_BUNNY => 'bunny',
			self::TYPE_CALENDAR => 'calendar',
			self::TYPE_CARTOON => 'cartoon',
			self::TYPE_CONTENT => 'content',
			self::TYPE_DOCUMENT => 'document',
			self::TYPE_FAQ => 'faq',
			self::TYPE_FEEDCONTENT => 'feedContent',
			self::TYPE_FEED => 'feed',
			self::TYPE_GALLERY => 'gallery',
			self::TYPE_INFOGRAPHIC => 'infographic',
			self::TYPE_LIVEREPORT => 'livereport',
			self::TYPE_LOTTERY => 'lottery',
			self::TYPE_MEDIA_COLLECTION => 'mediaCollection',
			self::TYPE_NEWSPAPER => 'newspaper',
			self::TYPE_NOVEL => 'novel',
			self::TYPE_PERSON => 'person',
			self::TYPE_PROGRAM => 'broadcastprogram',
			self::TYPE_RESOURCE => 'resource',
			self::TYPE_SCRIPT => 'broadcastscript',
			self::TYPE_SPORT_PLAYER => 'sportPlayer',
			self::TYPE_SPORT_TEAM => 'sportTeam',
			self::TYPE_QUOTE => 'quote',
			self::TYPE_WIDGET => 'widget',
			self::TYPE_TASK => 'task',
			self::TYPE_TOPIC => 'topic',
			self::TYPE_TV_ANCHOR => 'tvAnchor',
			self::TYPE_TV_HIGHLIGHT=> 'tvHighlight',
			self::TYPE_TV_PROGRAM =>'tvProgram',
			self::TYPE_TV_SCHEDULE => 'tvSchedule',
			self::TYPE_VIDEO => 'video',
			self::TYPE_VIDEO_PLAYLIST => 'videoPlaylist',
			self::TYPE_WATCHTOPIC =>'watchTopic',
			self::TYPE_WEATHER=>'weather',
			self::TYPE_USER => 'user',
	);

	/**
	 * ให้ค่า instance ของ model ที่ระบุด้วย entity type และ $refId
	 * @param int $type
	 * @param int $refId
	 * @return ActiveRecord
	 */
	public static function getInstance($type, $refId, $orderNo = NULL) {
		$instance = null;
		switch($type) {
			case self::TYPE_ACTIVITY :
				$instance = Activity::findOne($refId);
				break;
			case self::TYPE_BLOG:
				$instance = Blogs::findOne($refId);
				break;
			case self::TYPE_CARTOON:
				$arr = preg_split('/-/', $refId);
				$instance = CartoonChapter::findOne(array('cartoonId' => $arr[0], 'chapter' => $arr[1]));
				break;
			case self::TYPE_CONTENT:
				$instance = Content::findOne($refId);
				break;
			case self::TYPE_DOCUMENT:
				$instance = Document::findOne(array('type'=> $type, 'refId'=>$refId, 'itemNo'=> $orderNo));
				break;
			case self::TYPE_FAQ:
				$instance = Faq::findOne($refId);
				break;
			case self::TYPE_FEEDCONTENT:
				$instance = FeedContent::findOne($refId);
				break;
			case self::TYPE_FEED:
				$instance = Feed::findOne($refId);
				break;
			case self::TYPE_GALLERY:
				$instance = Gallery::findOne($refId);
				break;
			case self::TYPE_INFOGRAPHIC:
				$instance = InfoGraphic::findOne($refId);
				break;
			case self::TYPE_LIVEREPORT:
				$instance = LiveReport::findOne($refId);
				break;				
			case self::TYPE_LOTTERY :
					$instance = Lottery::findOne($refId);
					break;
			case self::TYPE_BUNNY:
			case self::TYPE_MEDIA_COLLECTION:
				$instance = MediaCollection::findOne($refId);
				break;
			case self::TYPE_NEWSPAPER:
				// temporary class for media upload
				$instance = new stdClass();
				$instance->createTime = date('Y-m-d H:i:s');
				break;
			case self::TYPE_NOVEL:
				$instance = Novel::findOne($refId);
				break;
			case self::TYPE_PERSON:
				$instance = Person::findOne($refId);
				break;
			case self::TYPE_SPORT_PLAYER:
				$instance = Player::findOne($refId);
				break;
			case self::TYPE_SPORT_TEAM:
				$instance = Team::findOne($refId);
				break;
			case self::TYPE_QUOTE:
				$instance = Quote::findOne($refId);
				break;
			case self::TYPE_WIDGET:
				$instance = Widget::findOne($refId);
				break;
			case self::TYPE_TV_ANCHOR:
				$instance = TvAnchor::findOne($refId);
				break;
			case self::TYPE_TV_PROGRAM:
				$instance = TvProgram::findOne($refId);
				break;
			case self::TYPE_TV_HIGHLIGHT:
				$instance = TvHighlight::findOne($refId);
				break;
			case self::TYPE_TV_SCHEDULE:
				$instance = TvSchedule::findOne($refId);
				break;
			case self::TYPE_USER:
				$instance = User::findOne($refId);
				break;
			case self::TYPE_VIDEO:
				$instance = Video::findOne($refId);
				break;
			case self::TYPE_VIDEO_PLAYLIST:
				$instance = VideoPlaylist::findOne($refId);
				break;
			case self::TYPE_WATCHTOPIC:
				$instance = WatchTopic::findOne($refId);
				break;
			case self::TYPE_WEATHER:
				$instance = WeatherForecast::findOne($refId);
				break;
		}
		return $instance;
	}

	/**
	 * เปลี่ยนค่า type string ให้เป็น constant
	 * @param obj $obj
	 * @param bool $asString = false map ให้เป็น string หรือไม่
	 * @return mixed
	 */
	public static function mapType($obj, $asString = false) {
		
		if (is_object($obj)) { 
			$className = \yii\helpers\StringHelper::basename(get_class($obj));
			switch($className) {
				case 'CartoonChapter':
					$type = 'cartoon';
					break;
				case 'Document':
					$type = 'document';
					break;
				case 'FeedContent':
					$type = 'feedContent';
					break;
				case 'Feed':
					$type = 'feed';
					break;
				case 'MediaCollection':
					$type = 'mediaCollection';
					break;
				case 'Player':
					$type = 'sportPlayer';
					break;
				case 'Team':
					$type = 'sportTeam';
					break;
				case 'Quote':
					$type = 'quote';
					break;
				case 'Widget':
					$type = 'widget';
					break;
				case 'TvProgram':
					$type = 'tvProgram';
					break;
				case 'TvHighlight' :
					$type = 'tvHighlight';
					break;
				case 'TvAnchor':
					$type = 'tvAnchor';
					break;
				case 'TvSchedule':
					$type = 'tvSchedule';
					break;
				case 'VideoPlaylist':
					$type = 'videoPlaylist';
					break;
				case 'WatchTopic':
					$type = 'watchTopic';
					break;
				case 'WeatherForecast':
					$type = 'weather';
					break;
				case 'Blogs':
					$type = 'blogs';
					break;
				case 'Gallery':
					$type = 'gallery';
					break;
				default:
					$type = strtolower($className);
					break;
			}
		}
		else
			$type = $obj;

		if ($asString)
			return $type;
		return array_search($type, self::$arrType);
	}
	
	/**
	 * เปลี่ยน entity model ให้เป็น Tag representation
	 * @param CModel $obj
	 * @return mixed
	 */
	public static function toTag($obj, $mode = self::EXPORT_STRING) {
		switch(get_class($obj)) {
			case 'Person':
				$refId = $obj->id;
				$title = $obj->getDisplayName();
				break;
			default:
				$refId = $obj->id;
				$title = $obj->title;
		}
		
		if ($mode == self::EXPORT_STRING)
			return $title;
		else {
			return [
					'type' => self::mapType($obj, true),
					'id' => $refId,
					'title' => $title,
			];
		}
	}
}