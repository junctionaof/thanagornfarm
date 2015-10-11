<?php
namespace backend\components;

use yii\base\Widget;

class Portlet extends Widget {
	protected $portletId;
	public $title;
	public $themeClass = ' box grey';
	public $iconClass = 'edit';
	public $actions = array();
	public $attrs = array();
	
	protected $arrActionMap = array(
		'edit' => array('pencil', 'แก้ไข'),
		'save' => array('save', 'บันทึก'),
	);
	
	public function init()
	{
		parent::init();
		ob_start();
		$this->portletId = \Yii::$app->params['portlets'] + 1;
		\Yii::$app->params['portlets'] = $this->portletId;
	}
	
	public function run() {
		$content = ob_get_clean();
		echo $this->render('portlet', array(
			'content' => $content,
			'attrs'=>$this->attrs,
			'themeClass'=>$this->themeClass,
			'actions'=>$this->actions,
			'iconClass'=>$this->iconClass,
			'title'=>$this->title,
			'id'=>$this->id,
			'arrActionMap'=>$this->arrActionMap
		));
	}
}