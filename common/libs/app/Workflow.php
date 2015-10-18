<?php
namespace app;

class Workflow {
	const STATUS_REJECTED = -1;
	const STATUS_EDITING = 2;
	const STATUS_EDIT_FINISH = 4;
	const STATUS_PUBLISHED = 10;
	

	
	const STATUS_LIVEREPORT = 1;
	const STATUS_NO_LIVEREPORT = 0;
	
	const LOGIN = 1;
	const LOGOUT = 2;	
	
	public static $arrThStatusTpbs = array(
			self::STATUS_EDITING=>'กำลังแก้ไข',
			self::STATUS_EDIT_FINISH => 'แก้ไขเสร็จสิ้น(รอเปิด)',
			self::STATUS_PUBLISHED=>'แสดงผล',
			self::STATUS_REJECTED => 'ลบ',
	);
	
	public static $arrStatusTpbs = array(
			self::STATUS_EDITING=>'Draft',
			self::STATUS_EDIT_FINISH => 'Approved',
			self::STATUS_PUBLISHED=>'Published',
			self::STATUS_REJECTED => 'Delete',
	);

	public static $arrStatusIcon = array(
			self::STATUS_REJECTED => 'disable_icon.png',
			self::STATUS_EDITING=>'abc.png',
			self::STATUS_EDIT_FINISH => 'abc.png',
			self::STATUS_PUBLISHED=>'enable_icon.png',
	);

	public static $arrStatusLiveReport = array(
			self::STATUS_LIVEREPORT => 'แสดงผล',
			self::STATUS_NO_LIVEREPORT=>'ไม่แสดง',
	);
	
	public static $arrStatusTpbsIcon = array(
			self::STATUS_EDITING=> array('icon'=>'fa-pencil-square-o', 'css'=> 'draft'),
			self::STATUS_EDIT_FINISH => array('icon'=>'fa-clock-o', 'css'=> 'approved'),
			self::STATUS_PUBLISHED=> array('icon'=>'fa-check', 'css'=> 'published'),
			self::STATUS_REJECTED => array('icon'=>'fa-lock', 'css'=> 'delete'),
	);
	
	public static $arrStatusUser = array(
			self::LOGIN => 'ลงชื่อเข้าใช้งาน',
			self::LOGOUT => 'ออกจากระบบ',
	);
	
}
