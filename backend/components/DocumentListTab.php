<?php

namespace backend\components;

use yii\base\Widget;

class DocumentListTab extends Widget {

    public $entity;
    public $arrDocument;
    public $useForm = true;
    public $fileUploadParams = array();

    public function run() {
    	$this->view->registerJsFile(\Yii::getAlias('@web'). '/global/scripts/document-list.js',['position'=>\yii\web\View::POS_END]);
    	$this->view->registerJsFile(\Yii::getAlias('@web'). '/global/plugins/jquery.mockjax.js',['position'=>\yii\web\View::POS_END]);
    	
    	echo $this->render('documentListTab', ['entity' => $this->entity,
            'fileUploadParams' => $this->fileUploadParams,
            'arrDocument' => $this->arrDocument,
            'useForm' => $this->useForm
        ]);
    }

}
