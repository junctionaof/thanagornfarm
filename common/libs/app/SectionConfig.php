<?php
namespace app;
class SectionConfig {

	const SECTION_NAME_HOME = 'home';
	const SECTION_NAME_HOME_NEWS = 'homeNews';
	const SECTION_NAME_POL = 'politics';
	const SECTION_NAME_ECO = 'economy';
	const SECTION_NAME_FOREIGN = 'foreign';
	const SECTION_NAME_SOCIAL = 'social';
	const SECTION_NAME_SPORT = 'sport';
	const SECTION_NAME_ENT = 'entertainment';
	const SECTION_NAME_PROVINCIAL = 'provincial';
	const SECTION_NAME_CRIME = 'crime';
	const SECTION_NAME_DISASTER = 'disaster';
	const SECTION_NAME_LIFE = 'lifestyle';

	// กำหนดชื่อแต่ละ section
	public static $sectionName = [
				self::SECTION_NAME_HOME => 'หน้าแรก',
				self::SECTION_NAME_HOME_NEWS => 'ข่าวหน้าแรก',
				self::SECTION_NAME_POL => 'การเมือง',
				self::SECTION_NAME_ECO => 'เศรษฐกิจ',
				self::SECTION_NAME_FOREIGN => 'ต่างประเทศ',
				self::SECTION_NAME_SOCIAL => 'สังคม',
				self::SECTION_NAME_SPORT => 'กีฬา',
				self::SECTION_NAME_ENT => 'ศิลปบันเทิง',
				self::SECTION_NAME_PROVINCIAL => 'ภูมิภาค',
				self::SECTION_NAME_CRIME => 'อาชญากรรม',
				self::SECTION_NAME_DISASTER => 'ภัยพิบัติ',
				self::SECTION_NAME_LIFE => 'ไลฟ์สไตล์',
	];
	
	// กำหนดหมวดหมู่ข้อมูลที่จะทำการ Query
	const TYPE_CONTENT = 'content';
	const TYPE_GALLERY = 'gallery';
	const TYPE_VIDEO = 'video';
	const TYPE_FEED = 'feed';
	const TYPE_WEATHER = 'weather';
	const TYPE_QUOTE = 'quote';
	const TYPE_WIDGET = 'widget';
	const TYPE_FEED_CONTENT = 'feedcontent';
	
	public static $arrCategory = [
			self::TYPE_CONTENT,
			self::TYPE_GALLERY,
			self::TYPE_VIDEO,
			self::TYPE_FEED,
			self::TYPE_WEATHER,
			self::TYPE_QUOTE,
			self::TYPE_WIDGET,
			self::TYPE_FEED_CONTENT,
	];
	
	public static $arrNameCategory = [
			self::TYPE_CONTENT => 'คอนเทนต์',
			self::TYPE_GALLERY => 'แกลลอรี่',
			self::TYPE_VIDEO => 'วีดีโอ',
			self::TYPE_FEED => 'ไทยพีบีเอสโฟกัส',
			self::TYPE_WEATHER => 'พยากรณ์อากาศ',
			self::TYPE_QUOTE => 'คำในข่าว',
			self::TYPE_WIDGET => 'ข้อมูลกำหนดเอง (Widget)',
			self::TYPE_FEED_CONTENT => 'ฟีด คอนเทนต์',
	];
	
	// map class media
	public static function mapMediaClass($obj){
		if($obj){
			$classId = Entity::mapType($obj);
			switch($classId) {
				case Entity::TYPE_GALLERY:
					$type = 'media-icon media-icon--image';
					break;
				case Entity::TYPE_VIDEO:
					$type = 'media-icon';
					break;
				default:
					$type = strtolower($className);
					break;
			}
			return $type;
		}
	}
	
	// map icons media
	public static function mapMediaIcons($obj){
		if($obj){
			$classId = Entity::mapType($obj);
			switch($classId) {
				case Entity::TYPE_GALLERY:
					$icon = 'fa fa-image';
					break;
				case Entity::TYPE_VIDEO:
					$icon = 'fa fa-play';
					break;
				default:
					$icon = strtolower($className);
					break;
			}
			return $icon;
		}
	}
	
	const STATUS_CLOSE = 0;
	const STATUS_OPEN = 1;
	const STATUS_PROGRAM_SCHADUL = 2;
	
	//
	public static $arrBreakingNews = [
			''=>'-- เลือกสถานะการแสดงผล --',
			self::STATUS_CLOSE=>'ปิด',
			self::STATUS_OPEN=>'เปิด',
			self::STATUS_PROGRAM_SCHADUL=>'ขึ้นอยู่กับผังรายการ'
	];
}
