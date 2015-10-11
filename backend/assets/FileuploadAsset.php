<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FileuploadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //'global/',
        'global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css',
        'global/plugins/jquery-file-upload/css/jquery.fileupload.css',
        'global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css',
    ];
    /*public $js = [
    		'testJs/vendor/jquery.ui.widget.js',
    		'global/plugins/jquery-file-upload/js/vendor/tmpl.min.js',
    		'global/plugins/jquery-file-upload/js/vendor/load-image.min.js',
    		'global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js',
    		//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'
    		//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js'
    		'testJs/jquery.iframe-transport.js',
    		'testJs/jquery.fileupload.js',
    		'testJs/jquery.fileupload-process.js',
    		'testJs/jquery.fileupload-image.js',
    		'testJs/jquery.fileupload-audio.js',
    		'testJs/jquery.fileupload-video.js',
    		'testJs/jquery.fileupload-validate.js',
    		'testJs/jquery.fileupload-ui.js',
    		'admin/pages/scripts/form-fileupload.js',
    		//'testJs/main.js',
    ];*/
    
    public $js = [
       
    	'global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js',
    	'global/plugins/jquery-file-upload/js/vendor/tmpl.min.js',
    	'global/plugins/jquery-file-upload/js/vendor/load-image.min.js',
    	'global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js',
    	'global/plugins/bootstrap-fileupload/bootstrap-fileupload.js',
    	'global/plugins/jquery-file-upload/js/jquery.iframe-transport.js',
    	'global/plugins/jquery-file-upload/js/jquery.fileupload.js',
    	'global/plugins/jquery-file-upload/js/jquery.fileupload-process.js',
    	'global/plugins/jquery-file-upload/js/jquery.fileupload-image.js',
    	'global/plugins/jquery-file-upload/js/jquery.fileupload-audio.js',
    	'global/plugins/jquery-file-upload/js/jquery.fileupload-video.js',
    	'global/plugins/jquery-file-upload/js/jquery.fileupload-validate.js',
    	'global/plugins/jquery-file-upload/js/jquery.fileupload-ui.js',
    	//'admin/pages/scripts/form-fileupload.js',
    	//'global/scripts/document-fileupload.js',
        //'global/scripts/media-list.js',
       // 'global/plugins/jquery.mockjax.js',
              
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];
}
