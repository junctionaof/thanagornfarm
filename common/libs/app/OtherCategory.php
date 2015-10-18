<?php
class OtherCategory{
	/**
	 * ดึงข้อมูลหมวดๆ ที่ content นั้นๆสามารถอยู่ได้
	 * @param int $objectId รหัสข่าว
	 * @param string $type หมวดของเนื้อหา
	 */
	public static function getItems($objectId, $type = 'news') {
		/*category mongo*/
		$cursor = Yii::app()->mongo->category->find(array(
				'objectId' => $objectId,
				'type' => $type,
		))->sort(array('ts' => -1))->limit(1);
		
		$doc = $cursor->getNext();
		if (!$doc) return array();

		return $doc['categories'];
	}
}