<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TpbsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //'global/',
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        'global/plugins/bootstrap/css/bootstrap.min.css',
        'global/plugins/uniform/css/uniform.default.css',
        'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css',
        'global/plugins/bootstrap-modal/css/bootstrap-modal.css',
        
        'global/plugins/clockface/css/clockface.css',
        'global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
        'global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
        'global/plugins/bootstrap-colorpicker/css/colorpicker.css',
        'global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css',
        'global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
        'global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
        
        'css/global/components-md.css',
        'css/global/plugins-md.css',
    	'css/global/tpbs-color.css',
        //'admin/layout/css/layout.css',
        'css/admin/layout/layout.css',
        //'admin/layout/css/themes/tpbs-darkblue.css',
        'css/admin/layout/themes/tpbs-themes.css',
        'admin/layout/css/custom.css',
        'global/plugins/jquery-tags-input/jquery.tagsinput.css',
        
        'global/plugins/bootstrap-select/bootstrap-select.min.css',
        'global/plugins/select2/select2.css',
        'global/plugins/jquery-multi-select/css/multi-select.css',
        'global/plugins/jquery-ui/jquery-ui.min.css',
    ];
    public $js = [
        'global/plugins/jquery.min.js',
        'global/plugins/jquery-migrate.min.js',
        'global/plugins/jquery-ui/jquery-ui.min.js',
        'global/plugins/bootstrap/js/bootstrap.min.js',
        'global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/jquery.cokie.min.js',
        'global/plugins/uniform/jquery.uniform.min.js',
        'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        /* Component script */
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
        
        
        'global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'global/plugins/bootstrap-fileupload/bootstrap-fileupload.js',
        'global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js',
        'global/plugins/bootstrap/js/bootstrap2-typeahead.js',
        
        'global/plugins/bootstrap-select/bootstrap-select.min.js',
        'global/plugins/jquery-multi-select/js/jquery.multi-select.js',
        'admin/pages/scripts/components-dropdowns.js',
       
        'admin/pages/scripts/components-jqueryui-sliders.js',
        
        //page component script
        'global/scripts/metronic-tpbs.js',
        'admin/layout/scripts/layout.js',
        'admin/layout/scripts/quick-sidebar.js',
        'admin/layout/scripts/demo.js',
        'admin/pages/scripts/table-managed.js',  
        'admin/pages/scripts/components-pickers.js',
        
        /* Last js my create */
        'global/scripts/form-components.js',
        'global/tpbs-components-form-tools.js',
        'global/tpbs-js.js',
        
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}