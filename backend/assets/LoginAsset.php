<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //'global/',
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/bootstrap/css/bootstrap.min.css',
        'global/plugins/uniform/css/uniform.default.css',        
        'global/plugins/clockface/css/clockface.css',
        
        'global/css/components-md.css',
    	'assets/css/style-metronic.css',
    	'assets/css/style.css',
    	'assets/css/style-responsive.css',
    	'global/css/plugins.css',
        'global/css/plugins-md.css',
        'admin/layout/css/layout.css',
        'admin/layout/css/themes/darkblue.css',
        'admin/layout/css/custom.css',
    	'assets/css/pages/login-soft.css',
    		
        'global/plugins/bootstrap-select/bootstrap-select.min.css',
        'assets/plugins/select2/select2_metro.css',
        'global/plugins/jquery-ui/jquery-ui.min.css',
    ];
    public $js = [
    	'assets/plugins/jquery-1.10.2.min.js',
    	'assets/plugins/jquery-migrate-1.2.1.min.js',
        'global/plugins/jquery-ui/jquery-ui.min.js',
        'global/plugins/bootstrap/js/bootstrap.min.js',
        'global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/jquery.cokie.min.js',
        'global/plugins/uniform/jquery.uniform.min.js',
    	
        // component
        'assets/plugins/jquery-validation/dist/jquery.validate.min.js',
    	'global/plugins/backstretch/jquery.backstretch.min.js',
        'global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
        'global/plugins/bootstrap-touchspin/bootstrap.touchspin.js',
    	'global/plugins/select2/select2.min.js',
        
        'admin/pages/scripts/components-dropdowns.js',
        'admin/pages/scripts/components-jqueryui-sliders.js',
        
        //page component script
        'global/scripts/metronic.js',
        'admin/layout/scripts/layout.js',
        'admin/layout/scripts/quick-sidebar.js',
        'admin/layout/scripts/demo.js',
        'admin/pages/scripts/table-managed.js',  
        'admin/pages/scripts/components-pickers.js',
        
        /* Last js my create */
        //'global/scripts/form-components.js',
        'global/tpbs-components-form-tools.js',
        'global/tpbs-js.js',
        
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}