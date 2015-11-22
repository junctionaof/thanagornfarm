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
    	'assets/global/css/pages/login-soft.css',
        'assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'assets/global/plugins/uniform/css/uniform.default.css',        
        'assets/global/plugins/clockface/css/clockface.css',
        
        'assets/global/css/components-md.css',
    	'assets/global/css/style-metronic.css',
    	'assets/global/css/style.css',
    	'assets/global/css/style-responsive.css',
    	'assets/global/css/plugins.css',
        'assets/global/css/plugins-md.css',
        'assets/admin/layout/css/layout.css',
        'assets/layouts/layout/css/themes/darkblue.css',
        'assets/layouts/layout/css/custom.css',

    		
        'assets/global/plugins/bootstrap-select/bootstrap-select.min.css',
        'assets/global/plugins/select2/select2_metro.css',
        'assets/global/plugins/jquery-ui/jquery-ui.min.css',
    ];
    public $js = [
    	'assets/global/plugins/jquery.min.js',
    	'assets/global/plugins/jquery-migrate.min.js',
        'assets/global/plugins/jquery-ui/jquery-ui.min.js',
        'assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'assets/global/plugins/jquery.blockui.min.js',
        'assets/global/plugins/jquery.cokie.min.js',
        'assets/global/plugins/uniform/jquery.uniform.min.js',
    	
        // component
        
    	'assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
    	'assets/global/plugins/backstretch/jquery.backstretch.min.js',
        'assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
        'assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js',
    	'assets/global/plugins/select2/select2.min.js',
        
        'assets/pages/scripts/components-dropdowns.js',
        'assets/pages/scripts/components-jqueryui-sliders.js',
        
        //page component script
        'assets/global/scripts/metronic.js',
        'assets/layouts/layout/scripts/layout.js',
        'assets/layouts/layout/scripts/quick-sidebar.js',
        'assets/layouts/layout/scripts/demo.js',
        'assets/pages/scripts/table-managed.js',  
        'assets/pages/scripts/components-pickers.js',
        
        /* Last js my create */
        //'global/scripts/form-components.js',
        'assets/global/tpbs-components-form-tools.js',
        'assets/global/tpbs-js.js',
        
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}