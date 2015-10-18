<?php
class TRHtml {
	/**
	 * HTML element ที่เหมาะสมเพื่อสร้าง textarea ตาม model และ field ที่ระบุ
	 * เรียบเรียง input field ใหม่
	 */
	private static $fullColumn = 13;
	
	/**
	 * HTML element ที่เหมาะสมเพื่อสร้าง date-picker input ตาม model และ field ที่ระบุ
	 * @param CActiveRecord $model
	 * @param string $fieldName
	 * @param array $options
	 * @return string
	 */	
	public static function dateInput(CActiveRecord $model, $fieldName, $options = array()) {
		if (!isset($options['data-date-format']))
			$options['data-date-format'] ='yyyy-mm-dd';
		if ($options['inputClass']) {
			$options['inputClass'] .= " date-picker";
		}
		else 
			$options['inputClass'] = 'date-picker';

		if ($model->$fieldName)
			$fieldValue = date(DateUtil::SD_FMT_FORM, $model->$fieldName);
		else
			$fieldValue = null;

		return self::textInput($model, $fieldName, $options + array('fieldColumn' => 2, 'fieldValue' => $fieldValue));		
	}
	
	public static function dateInputFull(CActiveRecord $model, $fieldName, $options = array()) {
		$fieldColumn = 6;
	
		if (!isset($options['data-date-format']))
			$options['data-date-format'] ='dd/mm/yyyy';
		if ($options['inputClass']) {
			$options['inputClass'] .= " date-picker";
		}
		else
			$options['inputClass'] = 'date-picker';
	
		if ($model->$fieldName)
			$fieldValue = date(DateUtil::SD_FMT_TH, strtotime($model->$fieldName));
		else
			$fieldValue = null;
	
	
		return self::textInputFull($model, $fieldName, $options + array('fieldColumn' => $fieldColumn, 'fieldValue' => $fieldValue));
	}
	
	/**
	 * HTML element ที่เหมาะสมเพื่อสร้าง switch input ตาม model และ field ที่ระบุ
	 * @param CActiveRecord $model
	 * @param string $fieldName
	 * @param array $options
	 * @return string
	 */
	public static function switchInput(CActiveRecord $model, $fieldName, $options = array()) {
		$containerCss = '';
		$options['class'] = 'toggle';
		if ($options['inputClass']) {
			$options['class'] .= " {$options['inputClass']}";
			unset($options['inputClass']);
		}
		if ($model->hasErrors($fieldName)) {
			$containerCss .= ' has-error';			
			$errorHelp = '<span class="help-block">' . $model->getError($fieldName) . '</span>';
		}
		$str = "<div class=\"form-group$containerCss\">" . CHtml::activeLabelEx($model, $fieldName, array('class' => 'control-label col-md-3')) .
		'<div class="col-md-2"><div class="make-switch">' . CHtml::activeCheckBox($model, $fieldName, $options) . "</div>$errorHelp</div></div>";
		
		return $str;		
	}
	
	public static function textAreaFull(CActiveRecord $model, $fieldName, $options = array()) {
		$containerCss = '';
		$options['class'] = 'form-control';
	
		if (array_key_exists('inputClass',$options)) {
			$options['class'] .= " {$options['inputClass']}";
				
		}
	
		if (array_key_exists('fieldColumn',$options)) {
			$columns = $options['fieldColumn'];
			unset($options['fieldColumn']);
		}
		else
			$columns = self::$fullColumn;
	
		$errorHelp = '';
		if ($model->hasErrors($fieldName)) {
			$containerCss .= ' has-error';
			$errorHelp = '<span class="help-block">' . $model->getError($fieldName) . '</span>';
		}
	
		$str = "<div class=\"form-group$containerCss\">";
	
		if (array_key_exists('hasLabel',$options)) {
			$str .= '<div class="input-caption col-md-'.$columns.'">' . CHtml::activeLabelEx($model, $fieldName, array('class' => 'control-label')) . '</div>';
			if(stripos($options['inputClass'], 'ckeditor') ){
				$str .= '<br/><br/>';
			}
			//$str .= CHtml::activeLabelEx($model, $fieldName, array('class' => 'control-label'));
			unset($options['hasLabel']);
		}
		unset($options['inputClass']);
		$str .= "<div class=\"col-md-$columns\">" . CHtml::activeTextArea($model, $fieldName, $options);
	
		$str .= "$errorHelp";
	
		if(array_key_exists('footer-area',$options)) {
			$str .= '<span class="footer-block">'. $options['footer-area'] .'</span>';
			unset($options['footer-area']);
		}
	
		$str .= "</div>";
	
		return $str."</div>";
	}
	
	/**
	 * HTML element ที่เหมาะสมเพื่อสร้าง textfield input ตาม model และ field ที่ระบุ
	 * @param CActiveRecord $model instance ของ model
	 * @param string $fieldName ชื่อฟิลด์
	 * @return string
	 */
	public static function textInput(CActiveRecord $model, $fieldName, $options = array()) {
		$containerCss = '';
		$options['class'] = 'form-control';
		
		if ($options['inputClass']) {
			$options['class'] .= " {$options['inputClass']}";
			unset($options['inputClass']);
		}
		if ($options['fieldColumn']) {
			$columns = $options['fieldColumn'];
			unset($options['fieldColumn']);
		}
		else
			$columns = 4;
		
		$errorHelp = '';
		if ($model->hasErrors($fieldName)) {
			$containerCss .= ' has-error';
			$errorHelp = '<span class="help-block">' . $model->getError($fieldName) . '</span>';
		}
		$str = "<div class=\"form-group$containerCss\">";
		$str .= CHtml::activeLabelEx($model, $fieldName, array('class' => 'control-label col-md-3'));
		$str .= "<div class=\"col-md-$columns\">" . CHtml::activeTextField($model, $fieldName, $options) . "$errorHelp</div></div>";
	
		return $str;
	}
	
	public static function textInputFull(CActiveRecord $model, $fieldName, $options = array()) {
		$containerCss = '';
		$options['class'] = 'form-control';
	
		if (array_key_exists('inputClass',$options)) {
			$options['class'] .= " {$options['inputClass']}";
			unset($options['inputClass']);
		}
	
		if (array_key_exists('fieldColumn',$options)) {
			$columns = $options['fieldColumn'];
			unset($options['fieldColumn']);
		}
		else
			$columns = self::$fullColumn;
	
		$errorHelp = '';
		if ($model->hasErrors($fieldName)) {
			$containerCss .= ' has-error';
			$errorHelp = '<span class="help-block">' . $model->getError($fieldName) . '</span>';
		}
		$str = "<div class=\"form-group$containerCss\">";
		if(array_key_exists('hasLabel',$options)){
			$str .= '<div class="input-caption col-md-'. ($columns).'">' . CHtml::activeLabelEx($model, $fieldName, array('class' => 'control-label')) . '</div>';
			unset($options['hasLabel']);
		}
		$str .= "<div class=\"col-md-$columns\">" . CHtml::activeTextField($model, $fieldName, $options) . "$errorHelp</div></div>";
	
		return $str;
	}
	
	public static function timeInputFull(CActiveRecord $model, $fieldName, $options = array()) {
		$fieldColumn = 6;
	
		if (!isset($options['data-date-format']))
			$options['data-date-format'] ='H:i';
		if ($options['inputClass']) {
			$options['inputClass'] .= " timepicker-default";
		}
		else
			$options['inputClass'] = 'timepicker-default';
	
		if ($model->$fieldName)
			$fieldValue = date('H:i:s', strtotime($model->$fieldName));
		else
			$fieldValue = null;
	
		return self::textInputFull($model, $fieldName, $options + array('fieldColumn' => $fieldColumn, 'fieldValue' => $fieldValue));
	}
}
