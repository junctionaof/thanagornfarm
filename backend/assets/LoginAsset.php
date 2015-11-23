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
        // BEGIN GLOBAL MANDATORY STYLES 
    	'assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'assets/global/plugins/uniform/css/uniform.default.css',        
    	//END GLOBAL MANDATORY STYLES 
    	
    	//BEGIN PAGE LEVEL PLUGINS
        'assets/global/plugins/select2/css/select2.min.css',
    	'assets/global/plugins/select2/css/select2-bootstrap.min.css',
    	//END PAGE LEVEL PLUGINS	
    		
    	//BEGIN THEME GLOBAL STYLES	
    	'assets/global/css/components.min.css',
    	'assets/global/css/plugins.min.css',
    	//END THEME GLOBAL STYLES
    	
    	//BEGIN PAGE LEVEL STYLES
    	'assets/pages/css/login-4.min.css',
    	//END PAGE LEVEL STYLES
    ];
    public $js = [
    	//BEGIN CORE PLUGINS
    	'assets/global/plugins/jquery.min.js',
    	'assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'assets/global/plugins/js.cookie.min.js',
        'assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'assets/global/plugins/jquery.blockui.min.js',
        'assets/global/plugins/uniform/jquery.uniform.min.js',
        'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
    	//END CORE PLUGINS 
        
        //BEGIN PAGE LEVEL PLUGINS
        
    	'assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
    	'assets/global/plugins/jquery-validation/js/additional-methods.min.js',
        'assets/global/plugins/select2/js/select2.full.min.js',
        'assets/global/plugins/backstretch/jquery.backstretch.min.js',
    	//END PAGE LEVEL PLUGINS 
        
    	// BEGIN THEME GLOBAL SCRIPTS
        'assets/global/scripts/app.min.js',
		//END THEME GLOBAL SCRIPTS
        
        //BEGIN PAGE LEVEL SCRIPTS
        'assets/pages/scripts/login-4.min.js',
        // END PAGE LEVEL SCRIPTS
        
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}