<?php
namespace app;

Class PublishBL{
	
	public static $arrChannel = array(self::CH_WAP=>"WAP", self::CH_RSS=> "RSS", self::CH_TWITTER=>'Twitter', self::CH_FACEBOOK=>'Facebook');
	
	public static $leftColumn = array(self::CH_WAP=>"WAP", self::CH_RSS=> "RSS");
	
	//public static $arrReviewNewsSection = array("TOPNEWS THAILAND", "สบาย สบาย ปีขาล", "FACE2010 หน้าใหม่ใสปิ๊ง", "TOPNEWS AROUND THE WORLD", "ดิจิตอล 2010", "กีฬาเบรกแตก");
	public static $arrChannelSub = array(
									
									self::CH_TRWEB_HOMEPAGE=>array(self::BREAKINGNEWS=>"เบรกกิ้งนิวส์",
																self::POL=>"การเมือง", 
																self::ENT=>"บันเทิง", 
																self::SPORT=>"กีฬา", 
																self::LIFE=>"ไลฟ์สไตล์", 
																self::ECO=>"เศรษฐกิจ", 
																self::EDU=>"การศึกษา", 
																self::OVERSEA=>"ต่างประเทศ", 
																self::TECH=>"วิทยาการ" ),
																							
									self::CH_RSS=>array(self::BREAKINGNEWS=>"เบรกกิ้งนิวส์",
														 self::POL=>"การเมือง", 
														 self::ENT=>"บันเทิง", 
														 self::SPORT=>"กีฬา", 
														 self::LIFE=>"ไลฟ์สไตล์", 
														 self::ECO=>"เศรษฐกิจ", 
														 self::EDU=>"การศึกษา", 
														 self::OVERSEA=>"ต่างประเทศ", 
														 self::TECH=>"วิทยาการ", 
														 self::REGION=>"ข่าวทั่วไทย", 
														 self::MISC=>"อื่น ๆ" ),
														 
									self::CH_TWITTER=>array(
											self::BREAKINGNEWS=>"เบรกกิ้งนิวส์",
											self::POL=>"การเมือง", 
											self::ENT=>"บันเทิง", 
											self::SPORT=>"กีฬา", 
									),
	
								   );
								   
		   
								   
	//public static $arrHomePageSubcate = array(, , , , , , );
	
						   
	// publishing channel
	const CH_TRWEB_HOMEPAGE= "trweb";
	const CH_SPECIAL_EVENT = "speacialevent";
	const CH_RSS = "rss";
	const CH_TWITTER = "twitter";
	const CH_FACEBOOK = "facebook";	
	const CH_WAP = "wap";
	
	
	//subcategory
	const BREAKINGNEWS = "Breaking";
	const POL = "Pol";
	const ENT = "Ent";
	const SPORT = "Sport";
	const LIFE = "Life";
	const ECO = "Eco";
	const EDU = "Edu";
	const OVERSEA = "Oversea";
	const TECH = "Tech";
	const REGION = "Region";
	const MISC = "Misc";
}
?>