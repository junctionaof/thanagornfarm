<?php
namespace common\models\Collection;

use yii\mongodb\ActiveRecord;

class Log extends ActiveRecord {
	/**
	 * @return array list of attribute names.
	 */
	public function attributes()
	{
		return ['_id', 'level', 'category', 'log_time', 'prefix', 'message'];
	}
	
}