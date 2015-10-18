<?php
namespace app;

class JsonPackage{
	/**
	 * @param array $arr ข้อมูลต้นทาง
	 * @return string
	 */
	public static function packProps($arr) {
		$props = array();
		if($arr){
			foreach($arr as $columnName => $value) {
				$props[$columnName] = $value;
			}
		}

		if (empty($props))
			return null;

		return json_encode($props);
	}

	/**
	 * ให้ค่า array ของ model ที่ได้จาก attributes + props
	 * @param ActiveRecord $model
	 * @return array
	 */
	public static function unpackProps($model) {
		if ($model->hasAttribute('props')) {
			$props = json_decode($model->props, true);
			if (!is_array($props))
				$props = array();
		}
		else
			$props = array();

		return $props;
	}
}