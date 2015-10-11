<?php

namespace backend\components;

use yii\base\Widget;

class MediaListTab extends Widget {

    public $entity;
    public $arrMedia;
    public $useForm = true;
    public $fileUploadParams = array();

    public function run() {
    	$this->view->registerJsFile(\Yii::getAlias('@web'). '/global/scripts/media-list.js',['position'=>\yii\web\View::POS_END]);
    	$this->view->registerJsFile(\Yii::getAlias('@web'). '/global/plugins/jquery.mockjax.js',['position'=>\yii\web\View::POS_END]);
    	//$this->view->registerJsFile(\Yii::getAlias('@web'). '/admin/pages/scripts/form-editable.js',['position'=>\yii\web\View::POS_END]);
    	$this->view->registerJsFile(\Yii::getAlias('@web'). '/global/plugins/jcrop/js/jquery.Jcrop.min.js',['position'=>\yii\web\View::POS_END]);
    	$this->view->registerJsFile(\Yii::getAlias('@web'). '/global/scripts/crop-image-tpbs.js',['position'=>\yii\web\View::POS_END]);
    	
    	$this->view->registerCssFile(\Yii::getAlias('@web').'/admin/pages/css/image-crop.css');
        $this->view->registerCssFile(\Yii::getAlias('@web'). '/global/plugins/jcrop/css/jquery.Jcrop.min.css');
        echo $this->render('mediaListTab', ['entity' => $this->entity,
            'fileUploadParams' => $this->fileUploadParams,
            'arrMedia' => $this->arrMedia,
            'useForm' => $this->useForm
        ]);
    }

}
