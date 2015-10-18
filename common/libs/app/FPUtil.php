<?php
namespace app;

use yii\helpers\Url;
use common\models\Feed;
class FPUtil {
	
	public static function ConvertStringToInt($data = array(), $fieldsName = array()){
		foreach ($data as $index => $fields){
			if($fieldsName){
				foreach ($fieldsName as $key => $title){
					$data[$index][$title] = (int)$fields[$title];
				}
			}else{
				$data[$index] = (int)$fields;
			}
		}
		
		return $data;
	}
	
	public static function getDontCopyHtml(){

$js = <<<EOT
		<script  type="text/javascript">
			var omitformtags=["input", "textarea", "select"];
			//var message="You may not right mouse click this page.";
			
			/* Disable Right Click */
			if (navigator.appName == 'Microsoft Internet Explorer'){
				function NOclickIE(e) {
					if (event.button == 2 || event.button == 3) {
						alert(message);
						return false;
					}
						return true;
				}
					document.onmousedown=NOclickIE;
					document.onmouseup=NOclickIE;
					window.onmousedown=NOclickIE;
					window.onmouseup=NOclickIE;
			}
			else {
				function NOclickNN(e){
					if (document.layers||document.getElementById&&!document.all){
						if (e.which==2||e.which==3){
							//alert(message);
							return false;
						}
					}
				}
				if (document.layers){
					document.captureEvents(Event.MOUSEDOWN);
					document.onmousedown=NOclickNN; 
				}
				document.oncontextmenu=new Function("return false")
			}
			
			/* ห้ามคลุมดำ */
			function disableselect(e){
					for (i = 0; i  < omitformtags.length; i++)
					if (omitformtags[i]==(e.target.tagName.toLowerCase()))
					return;
					return false
					}
			
					function reEnable(){
						return true;
					}
			
					function noSelect(){
						if (typeof document.onselectstart!="undefined"){
							document.onselectstart=new Function ("return false")
								if (document.getElementsByTagName){
			
								tags=document.getElementsByTagName('*')
									for (j = 0; j < tags.length; j++){
									for (i = 0; i < omitformtags.length; i++)
										if (tags[j].tagName.toLowerCase()==omitformtags[i]){
												tags[j].onselectstart=function(){
												document.onselectstart=new Function ('return true')
											}
			
											tags[j].onmouseup=function(){
												document.onselectstart=new Function ('return false')
											}
										}
									}
								}
						}
						else{
						document.onmousedown=disableselect
			
						document.onmouseup=reEnable
						}
					}
			
					window.onload=noSelect;
			
			</script>
EOT;

return $js;

	}
	
	/**
	 * หาความสูงของรูป
	 * @srcSizeWidth ความกว้างของรูปจริง
	 * @srcSizeHeight ความสูงของรูปจริง
	 * $destSizeWidth ความกว้างที่ต้องการ resize
	 * @return 
	 */
	public static function getImagesHeight($srcSizeWidth, $srcSizeHeight, $destSizeWidth){
		if($destSizeWidth && $srcSizeWidth){
			$retVal = array(
					'width' => $destSizeWidth,
					'height' => intval(($destSizeWidth*$srcSizeHeight)/$srcSizeWidth),
				);
		}else{
			$retVal = array();
		}
		return $retVal;
	}
	
	
	/**
	 * สร้าง link ไปยังภาพ ตามขนาดที่ระบุ
	 * ต่างจาก getMediaLink ตรงที่ content ในฟังก์ชันนี้เป็นรายการของ content ที่มีได้หลายๆ ภาพ โดยมีภาพที่เลือกเป็นภาพหลัก 1 ภาพ 
	 * @param string $contentType ประเภทของ content [person, album, collection]
	 * @param array $fields record fields ที่ต้องการสร้าง link
	 * @param int $imgWidth ความกว้างของภาพที่ต้องการ
	 * @param int $imgHeight ความสูงของภาพที่ต้องการ
	 * @param int $imgNo ภาพที่ต้องการแสดงผล หากไม่ระบุ หรือระบุเป็น 0 จะใช้ภาพหลักมาแสดง
	 * @return string
	 */
	public static function getImageLink($contentType, $data, $imgWidth, $imgHeight = 0, $imgNo = 0) {
		
		/* if (!is_numeric($data['id'])) {
			$id = substr($data['id'], strrpos($data['id'], '.') + 1);
		}
		else 
			$id = $data['id'];

		$baseUrl = self::getMediaBaseUrl();
		$contentDate = date('Y/m/d', strtotime($data['createTime']));
		$imageType = ($_REQUEST['type'])? $_REQUEST['type'] : 'jpg';
		if (is_array($data['params']))
			$params = '?' . http_build_query($data['params']);
		
		switch($contentType) {
			case 'person':
				if (!$imgNo) $imgNo = 1;
				if ($imgHeight)
					return "$baseUrl/media/person/$contentDate/p$imgNo/$imgWidth/$imgHeight/{$id}.{$imageType}";
				else
					return "$baseUrl/media/person/$contentDate/p$imgNo/$imgWidth/{$id}.{$imageType}";
				break;
			case 'album':
				if ($imgHeight)
					return "$baseUrl/media/gallery/$contentDate/p$imgNo/$imgWidth/$imgHeight/{$id}.{$imageType}{$params}";
				else
					return "$baseUrl/media/gallery/$contentDate/p$imgNo/$imgWidth/{$id}.{$imageType}{$params}";				
				break;
			case 'collection':
				if ($imgHeight)
					return "$baseUrl/media/collection/$contentDate/p$imgNo/$imgWidth/$imgHeight/{$id}.{$imageType}";
				else
					return "$baseUrl/media/collection/$contentDate/p$imgNo/$imgWidth/{$id}.{$imageType}";
				break;
		} */
	}
	
	
	/**
	 * ส่งค่า Header Cache-Control ต่างๆ  ตาม cache lifetime ที่ระบุ
	 * @param int $cacheTime ระบุเวลาเป็นจำนวนวินาทีที่ cache valid หรือ 0 = ไม่ให้มี cache
	 * @return void
	 */
	public static function setCacheHeaders($cacheTime = 0, $dataDate = 0) {
		if ($cacheTime > 0) {
			header("Expires: " . gmdate("D, d M Y H:i:s", time() + $cacheTime) . " GMT");
			header("Cache-Control: max-age=$cacheTime, must-revalidate");
		}
		else {
			if($dataDate){
					header("Expires: " . $dataDate->format('D, Y-m-d H:i:s') . " GMT");
					header("Cache-Control: max-age=$cacheTime, must-revalidate");	
			}else{
			    header('Pragma: public');
			    header("Expires: Sun, 19 Apr 2009 07:00:00 GMT");                  // Date in the past   
			    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
			    header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
			    header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
			}    
		}
	}
	
	public static function getLink($what, $params = array()) {
		$retVal = '';
		if (is_object($what)) {

			switch(get_class($what)) {
				case 'common\models\Content':
					if(isset($params['configUrl']) && $params['configUrl'])
						$retVal = \Yii::$app->params['newsSocialUrl'].'/content/'.$what->id;
					else
						$retVal = Url::toRoute('content/'.$what->id);
					
					break;
				case 'common\models\Gallery':
					if(isset($params['configUrl']) && $params['configUrl'])
						$retVal = \Yii::$app->params['newsSocialUrl'].'/gallery/'.$what->id;
					else
						$retVal = Url::toRoute('gallery/'.$what->id);
					
					break;
				case 'common\models\Feed':
					$url = NULL;
					$titleEn = $what->titleEn;
					
					if(!empty($titleEn)){
						if($what->theme == Feed::THEME_FOCUS){
							if(isset($params['configUrl']) && $params['configUrl'])
								$retVal = \Yii::$app->params['newsSocialUrl'].'/hotissues/focus/'.$titleEn;
							else
								$retVal = Url::toRoute(['hotissues/focus/'.$titleEn]);
							
						}else if($what->theme == Feed::THEME_FOCUSLIVE){
							if(isset($params['configUrl']) && $params['configUrl'])
								$retVal = \Yii::$app->params['newsSocialUrl'].'/hotissues/focuslive/'.$titleEn;
							else
								$retVal = Url::toRoute(['hotissues/focuslive/'.$titleEn]);
							
						}else{
							if(isset($params['configUrl']) && $params['configUrl'])
								$retVal = \Yii::$app->params['newsSocialUrl'].'/hotissues/'.$what->id;
							else
								$retVal = Url::toRoute('hotissues/'.$what->id);
							
						}
					}else{
						if(isset($params['configUrl']) && $params['configUrl'])
							$retVal = \Yii::$app->params['newsSocialUrl'].'/hotissues/'.$what->id;
						else
							$retVal = Url::toRoute('hotissues/'.$what->id);
					}
					
					
					
					break;
				case 'common\models\InfoGraphic':
					if(isset($params['configUrl']) && $params['configUrl'])
						$retVal = \Yii::$app->params['newsSocialUrl'].'/infographic/'.$what->id;
					else
						$retVal = Url::toRoute('infographic/'.$what->id);
					
					break;
				case 'common\models\Quote':
					if(isset($params['configUrl']) && $params['configUrl'])
						$retVal = \Yii::$app->params['newsSocialUrl'].'/quote/'.$what->id;
					else
						$retVal = Url::toRoute('quote/'.$what->id);
					
					break;
		}
	}else{
		//search?q=test
		switch($what) {
			case 'search':
				$q = isset($params['q'])?$params['q']:'';
				$retVal = Url::toRoute('/search?q='.$q);
				break;
			case 'newsClip':
				$id = isset($params['id'])?$params['id']:0;
				$retVal = Url::toRoute('/clip/'.$id);
				break;
		}
	}
	
	return $retVal;
	}
	
	/**
	 * 
	 * คืนค่า timestamp ตามเวลาที่เหมาะสม ตาม $_REQUEST['d']
	 */
	public static function timeMachine() {
		$ts = time();
		/* if (Yii::app()->params['timeMachine']) {			
			if (!empty($_REQUEST['d'])) {
				$ts = strtotime("{$_REQUEST['d']} " . date('H:i:s'));
			}
		} */
		
		return $ts;
	}
		
	/**
	 * ตัด string ตามความยาวที่กำหนด
	 * นำมาจาก smarty_modifier_truncate (Thank you very much)
	 * @param string $string ข้อความที่ต้องการตัด
	 * @param int $length ความยาวที่ต้องการ
	 * @param string $etc ตัวย่อ (เช่น มีต่อ...)
	 * @param bool $break_words ตัดกลางคำหรือไม่
	 * @param bool $middle ตัดหัว-ท้ายหรือไม่
	 * @return string
	 */
	public static function truncate($string, $length, $etc = '..',
	$break_words = true, $middle = false, $startString = 0)
	{
		if ($length == 0)
		return '';
		
		return ThaiText::truncate($string, $length); 

		if (mb_strlen($string) > $length) {
			$length -= min($length, mb_strlen($etc, 'UTF-8'));
			if (!$break_words && !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/', '', mb_strcut($string, 0, $length+1));
			}
			
			
			if( $break_words && $middle){

				return mb_strcut($string, $startString, -1, 'UTF-8') . $etc;
				
			}
			
			if(!$middle) {
				return mb_strcut($string, 0, $length, 'UTF-8') . $etc;
			} else {
				return mb_strcut($string, 0, $length/2, 'UTF-8') . $etc . mb_strcut($string, -$length/2, 'UTF-8');
			}
		} else {
			if( $break_words && $middle){
	
					return mb_strcut($string, $startString, -1, 'UTF-8') . $etc;
					
				}
			return $string;
		}
	}


}
?>