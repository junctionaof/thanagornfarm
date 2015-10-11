<?php

namespace backend\components;

use yii\base\Widget;

class UiForm extends Widget {
	public $features = [];
	
	const FEATURE_TAGS = 1;
	
	public function run() {
		//$cs = Yii::app()->clientScript;
		$baseUri = \Yii::getAlias('@web');
		
		if (in_array(self::FEATURE_TAGS, $this->features)) {
			$this->view->registerJsFile($baseUri  . '/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js', ['position'=>\yii\web\View::POS_END], 'tagsinput');
			$this->view->registerCssFile($baseUri . '/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css');
			//$cs->registerScriptFile($baseUri  . '/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js', CClientScript::POS_END);
			//$cs->registerCssFile($baseUri . '/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css');
			
			$str = <<<EOT
	$('.tags-enabled').tagsinput({
		itemText: function(item) {
			if (typeof item == 'string')
				return item;
			else
				return item['title'];
		},
		itemValue: function(item) {
			if (typeof item == 'string')
				return item;
			else
				return item['type'] + ':' + item['id'];
		},
		tagClass: function(item) {
			cssClass = 'label';
			if (typeof item == 'string')
				cssClass += ' label-info';
			else
				cssClass += ' label-object label-' + item.type;
			
			return cssClass;
		},
		typeahead: {
			source: function(query) {
				return $.getJSON('$baseUri/tags', {
						q: query
				});
			}
		}
	});
			
	$('.bootstrap-tagsinput').on('focus', 'input', function() {
			var tags = $(this).closest('.bootstrap-tagsinput');
			if (!tags.hasClass('ui-sortable'))
				tags.sortable({
					start: function (event, ui) {
			            $(ui.item).data("startindex", ui.item.index());
			        },
					stop: function(event, ui) {
						var tagsinput = $(this).prev();						
						var arr = tagsinput.data('tagsinput').itemsArray;
						var startindex = ui.item.data('startindex');
						var endindex = ui.item.index();
						if (endindex > arr.length - 1) endindex = arr.length - 1;

						var tmp = arr[startindex];
						
						// start < end
						for (index = startindex; index < endindex; index++) {
							arr[index] = arr[index+1];
						}
						// start > end
						for (index = startindex; index > endindex; index--) {
							arr[index] = arr[index - 1];
						}
						arr[endindex] = tmp;

						tagsinput.data('tagsinput').pushVal();
					}
				});
	});
EOT;
			$this->view->registerJs($str, \yii\web\View::POS_HEAD,'UiForm');
			//$cs->registerScript('', $str);
		}
	}
}