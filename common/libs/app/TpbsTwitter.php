<?php
namespace app;
use Yii;
use app\TwitterOAuth; 

class TpbsTwitter {
	
	/**
	 * ส่งข้อความเพื่อทำการ tweet ด้วย account และ content ที่ระบุ
	 * @param string $account key ของ account ที่ำทำการสมัครไว้แล้ว
	 * @param string $content เนื้อหาที่ต้องการ tweet
	 * @return string
	 */
	public static function tweet($account, $str) {
		$accountInfo = self::$arrAccount[$account];
		if (count($accountInfo) != 4) return;
		
		$connection = new TwitterOAuth($accountInfo[0], $accountInfo[1], $accountInfo[2], $accountInfo[3]);	
		
		$parameters = array('status' => $str);
		$result = $connection->post("statuses/update", $parameters);
		return $result;
	}
	
	private static $arrAccount = array(
		'twitterBreaking'=>array('nsqOUpbRjoArfaLT1HQkCKu4u','esBfmyiYmNOXmJV8e0Rl6ytIljqerlUICjMrlKnYNvB7SfDnnP', '12754182-RlmOipTNlhvRQjBI7uj6SNov9JbNI6Rm0e6TwPk7a', '5MYNa4OAEGFqYthvwnrkEK32ULexhgPrXijP9mmxjOlgA'),
			
	);
	
	//const BITLY_SHORTEN_URL = 'http://api.bit.ly/shorten?';	
}
?>