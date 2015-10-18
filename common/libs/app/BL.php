<?php
namespace app;

Class BL{
	
	public static $arrMainCategory = [
					'politics'=> 'การเมือง', 'economy'=>'เศรษฐกิจ', 'foreign'=> 'ต่างประเทศ',
					'crime'=> 'อาชญากรรม', 'disaster'=> 'ภัยพิบัติ', 'sport'=> 'กีฬา',
					'entertainment'=> 'ศิลปบันเทิง', 'lifestyle'=> 'ไลฟ์สไตล์'
	];
	
	public static $arrOtherCategory = [
			0 => ['asean'=> 'อาเซียน', 'frontier'=> 'จัดหวัดชายแดนภาคใต้', 'environment'=> 'สิ่งแวดล้อม', 'social'=> 'สังคม',],
			1 => ['health'=> 'สาธารณสุข', 'education'=> 'การศึกษา', 'woman'=> 'สตรีและเยาวชน', 'farmland'=> 'เกษตร',],
			2 => ['tech'=> 'วิทยาศาสตร์เทคโนโลยี', 'articles'=> 'บทความ-บทวิเคราะห์']
	];
	
	public static $arrEnCategory = [
			'asean'=> 'อาเซียน', 'frontier'=> 'จัดหวัดชายแดนภาคใต้', 'environment'=> 'สิ่งแวดล้อม', 'social'=> 'สังคม',
			'health'=> 'สาธารณสุข', 'education'=> 'การศึกษา', 'woman'=> 'สตรีและเยาวชน', 'farmland'=> 'เกษตร',
			'tech'=> 'วิทยาศาสตร์เทคโนโลยี', 'articles'=> 'บทความ-บทวิเคราะห์'
	];
}
?>