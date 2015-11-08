<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ComponentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //'global/',
        //'global/plugins/font-awesome/css/font-awesome.min.css',
    ];
    public $js = [
        /* Component script */
    	'global/plugins/bootstrap/js/bootstrap.min.js',
        'global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        'global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
        'global/plugins/clockface/js/clockface.js',
        'global/plugins/bootstrap-daterangepicker/moment.min.js',
        'global/plugins/bootstrap-daterangepicker/daterangepicker.js',
        'global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js',
        'global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        'global/plugins/select2/select2.min.js',
        'global/plugins/datatables/media/js/jquery.dataTables.min.js',
        'global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js',
        
        // component
        'global/plugins/fuelux/js/spinner.min.js',
        'global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',
        'global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
        'global/plugins/jquery.input-ip-address-control-1.0.min.js',
        'global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js',
        'global/plugins/jquery-tags-input/jquery.tagsinput.min.js',
        'global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
        'global/plugins/bootstrap-touchspin/bootstrap.touchspin.js',
        'global/plugins/typeahead/handlebars.min.js',
        'global/plugins/typeahead/typeahead.bundle.min.js',
        'global/plugins/ckeditor/ckeditor.js',
        
        'global/scripts/form-components.js',
        'global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'global/plugins/bootstrap-fileupload/bootstrap-fileupload.js',
        'global/plugins/bootstrap-modal/js/bootstrap-modal.js',
        'global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js',
        'global/plugins/bootstrap/js/bootstrap2-typeahead.js',
        
        //page component script
        'global/scripts/metronic.js',
        'admin/layout/scripts/layout.js',
        'admin/layout/scripts/quick-sidebar.js',
        'admin/layout/scripts/demo.js',
        'admin/pages/scripts/table-managed.js',  
        'admin/pages/scripts/components-pickers.js',
        
        
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
