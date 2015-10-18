<?php
namespace app;

use yii\base\Object;
use common\models\LogSystem;
use common\models\UserLogSystem;

class TpbsLog
{
    /**
     * Stores log messages to Log System Table.
     */
    public static function info($message = NULL, $categories = NULL)
    {
    	$currentTs = time();
    	$ts = date(DateUtil::SQL_DT_FMT, $currentTs);
    	$arrItems = json_decode($message, true);
    	$arrItems['categories'] = $categories;

    	if(!isset($arrItems['ts']))
    		$arrItems['ts'] = $ts;
    	
    	$Log = new LogSystem();
    	$Log->attributes = $arrItems;
    	$Log->message = $message;
    	$Log->save();
    }
    
    public static function userInfo($message = NULL)
    {
    	$currentTs = time();
    	$ts = date(DateUtil::SQL_DT_FMT, $currentTs);
    	$arrItems = json_decode($message, true);
    	
    	if(!isset($arrItems['ts']))
    		$arrItems['ts'] = $ts;
    	 
    	$Log = new UserLogSystem();
    	$Log->attributes = $arrItems;
    	$Log->message = $message;
    	$Log->save();
    }
}