<?php
namespace app;

use Yii;
/**
 * SQL condition generator
 * @author Sirichai Meemutha (jjoi@gayji.net)
 *
 */
class QueryBuilder {
	public static $arrOpMapping = array(
		'lt' => 'น้อยกว่า',
		'le' => 'น้อยกว่าหรือเท่ากับ',
		'eq' => 'เท่ากับ',
		'gt' => 'มากกว่า',
		'ge'  => 'มากกว่าหรือเท่ากับ',
		'ne' => 'ไม่เท่ากับ',
		'like' => 'คล้ายกับ',
		'nlike' => 'ไม่คล้ายกับ',
		'in' => 'อยู่ในรายการ',
		'nin' => 'ไม่อยู่ในรายการ'
	);
	private static $_opMapping = array(
		'lt' => '<',
		'le' => '<=',
		'eq' => '=',
		'gt' => '>',
		'ge'  => '>=',
		'ne' => '<>',
		'like' => 'like',
		'nlike' => 'not like',
		'in' => 'in',
		'nin' => 'not in'
	);

	public static function GetSQLCondition($queryParams) {
		$sqlCondition = '';
		
		$tablePrefix = isset($queryParams[0])?($queryParams[0] . '.'):'';
		
		foreach ($queryParams as $paramKey => $paramValue) {
			if ($paramKey === 0) continue;

			// find field definition
			if (substr($paramKey, 0, 7) == 'custom.') {
				$sqlCondition .= " and ($paramValue)";
			}
			else {
				list($fieldName, $conditionOp) = preg_split('/\,/', $paramKey);
				if ($conditionOp != '') {
	
					if ($tablePrefix != '' && strpos($fieldName, '.') === false)
						$fieldName = $tablePrefix . $fieldName;
						
					switch($conditionOp) {
						case 'in':
						case 'nin':
							if (is_array($paramValue)) {
								if (count($paramValue) == 0)
									$paramValue = NULL;
								else
									$paramValue = join(',', $paramValue);
							}
							else
								$paramValue = NULL;
							if (empty($paramValue))
								$paramValue = NULL;
								
//							if (!is_null($paramValue) && $fieldType == DBAbstract::TYPE_TEXT)
//								$paramValue = preg_replace('/\,/', "','", $paramValue);			
							break;
						case 'like':
						case 'nlike':
							$paramValue = $paramValue;
							break;
					}
					
					if (is_null($paramValue)) {
						if ($conditionOp == 'eq' || $conditionOp == 'in')
							$sqlCondition .= " and {$fieldName} is NULL";
						elseif ($conditionOp == 'ne' || $conditionOp == 'nin')
							$sqlCondition .= " and {$fieldName} is not NULL";
						else
							$sqlCondition .= " and 1=2";				
					}
					else {
					
						if ($conditionOp == 'in' || $conditionOp == 'nin')
							$paramValue = "($paramValue)";
						$sqlCondition .= " and {$fieldName} " . self::$_opMapping[$conditionOp] . " $paramValue";
					}
				}
			} // else not custom param
		} // foreach
		
		if ($sqlCondition != '')
			$sqlCondition = substr($sqlCondition, 5);
			
		return $sqlCondition;
	}
	
	public static function IfNull($value, $nullReplacement = 'NULL') {
		if (is_null($value))
			return $nullReplacement;
		else
			return strval($value);
	}
	
	// const
	const SQL_DT_FMT = "'Y-m-d H:i:s'";
	const SQL_D_FMT = "'Y-m-d'";	
}

?>