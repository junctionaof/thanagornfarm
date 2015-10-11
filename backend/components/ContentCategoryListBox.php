<?php
namespace backend\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\CategoryTree;

class ContentCategoryListbox extends Widget {
	public $id;
	public $name;
	public $selected;
	public $htmlOptions;
	public $contentType;
	public $baseId;
	
	public function init()
	{
		//parent::init();
		
	}
	
	public function run()
	{	
		$optionString = "";
		if (is_array($this->htmlOptions)) {
			foreach ($this->htmlOptions as $key=>$value) {
				$optionString .= " $key=\"$value\"";
			}
		} 
		//var_dump(CategoryTree::getHtmlListOption($this->baseId, $this->selected, $this->contentType));exit;
		$str = "<select class='form-control' name=\"{$this->name}\" id=\"{$this->id}\"$optionString>"
		.'<option value="">--หมวดเนื้อหา--</option>'
		.CategoryTree::getHtmlListOption($this->baseId, $this->selected, $this->contentType)
		."</select>";
		
		echo $str;
		
	}


}
?>