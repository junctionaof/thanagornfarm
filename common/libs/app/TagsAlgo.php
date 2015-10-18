<?php

namespace app;

use common\models\Content;

class TagsAlgo {

    public $arTagResult;
    public $arTagCount; 

    public function __construct($turnBack = 10 , $condition = array()) {
        $publicDay = date('Y-m-d');
        //$publicDay = '2012-10-20';
        $txt = '';
        $turnBackDay = date("Y-m-d", strtotime("-" . $turnBack . " day", strtotime($publicDay)));

        $rsCon = Content::find()->where('date(publishTime) >= :turnBackDay and date(publishTime) <= :publicDay', [
                    ':publicDay' => $publicDay,
                    ':turnBackDay' => $turnBackDay,
                ])->all();
        foreach ($rsCon as $item) {
            $txt = $txt . $item->tags;
        }
        $txt = '##@' . $txt . '##@';
        $txt = str_replace(array(', ,', ',,'), ',', $txt);
        $txt = str_replace(array('##@,', ',##@'), '', $txt);
        //$txt = 'พี่ปลาอายุ40,พี่ปลาอายุ40,test2,test3 ,test4,พี่ปลาอายุ50,พี่ปลาอายุ50,test2,พี่ปลาอายุ40,พี่ปลาอายุ40,test5,test3,พี่ปลาอายุ50,พี่ปลาอายุ50,พี่ปลาอายุ50';
        $arTag = explode(',', $txt, -1);
        foreach ($arTag as $key => $val) {
            $arTag[$key] = trim($val);
        }
        $countValAr = array_count_values($arTag);
        arsort($countValAr);
        $targetArray = array();
        $icount = 0;
        foreach ($countValAr as $key => $value) {
            $targetArray[] = $key;
            if ($icount >= 9)
                break;
            $icount = $icount + 1;
        }
        $this->arTagCount = $countValAr;
        $this->arTagResult = $targetArray;
    }

    public function getTagResult() {
        return $this->arTagResult;
    }

    /**
     * คือค่า array สิบอันดับแรกโดย [tagname] => จำนวนการใช้งาน
     * @return array
     */
    public function getTagTenRepeat() {
        $countValAr = $this->arTagCount;
        $icount = 0;
        $targetArray = array();
        foreach ($countValAr as $key => $value) {
            $targetArray[$key] = $value;
            if ($icount >= 9)
                break;
            $icount = $icount + 1;
        }
        return $targetArray;
    }

    public static function getTagItem($tag){
    	$tags = trim($tag, ",");
    	if($tags){
    		$arrTag = explode(',', $tags);
    		$arrayTag = [];
    		$num = 1;
    		foreach ($arrTag as $tag){
    			if ($num <= 4){
    				$arrayTag[] = $tag;
    			}
    			$num++;
    		}
    		
    		return $arrayTag;
    	}else{
    		return NULL;
    	}
    }
}
