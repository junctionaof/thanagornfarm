<?php
namespace backend\components;

use yii\base\Widget;

class FileUpload extends Widget {
	public $params;
	public $useForm = true;
	
	public function run() {
		
		$this->view->registerJsFile(\Yii::getAlias('@web'). '/admin/pages/scripts/form-fileupload.js',['position'=>\yii\web\View::POS_END]);
		
		$js = <<<EOT
                        FormFileUpload.init();
EOT;
		$this->view->registerJs($js, \yii\web\View::POS_READY, 'fileUpload.init');
		

		echo $this->render('fileUpload',['useForm'=>$this->useForm, 'params'=>$this->params]);
	}
}