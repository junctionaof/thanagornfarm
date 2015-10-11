<?php
namespace backend\components;

use yii\base\Widget;

class DocumentFileUpload extends Widget {
	public $params;
	public $useForm = true;
	
	public function run() {
		
		$this->view->registerJsFile(\Yii::getAlias('@web'). '/global/scripts/document-fileupload.js',['position'=>\yii\web\View::POS_BEGIN]);
		$js = <<<EOT
                        DocumentFileUpload.init();
EOT;
		$this->view->registerJs($js, \yii\web\View::POS_READY, 'documentFileUpload.init');
		

		echo $this->render('documentFileUpload',['useForm'=>$this->useForm, 'params'=>$this->params]);
	}
}