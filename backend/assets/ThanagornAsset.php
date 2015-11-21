<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ThanagornAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'BEGIN GLOBAL MANDATORY STYLES/',
        'assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'assets/global/plugins/uniform/css/uniform.default.css',
        'assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
    	// END GLOBAL MANDATORY STYLES 
    	
    	// BEGIN PAGE LEVEL PLUGINS
    	'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
    	'assets/global/plugins/morris/morris.css',
    	'assets/global/plugins/fullcalendar/fullcalendar.min.css',
    	'assets/global/plugins/jqvmap/jqvmap/jqvmap.css',
    	// END PAGE LEVEL PLUGINS
    	
    	//BEGIN THEME GLOBAL STYLES	
    	'assets/global/css/components.min.css',
    	'assets/global/css/plugins.min.css',
    	//END THEME GLOBAL STYLES	
    		
    	//BEGIN THEME LAYOUT STYLES
    	'assets/layouts/layout4/css/layout.min.css',
    	'assets/layouts/layout4/css/themes/light.min.css',
    	'assets/layouts/layout4/css/custom.min.css',
    	//END THEME LAYOUT STYLES

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
    	'assets/global/plugins/moment.min.js',
    	'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
    	'assets/global/plugins/morris/morris.min.js',
    	'assets/global/plugins/morris/raphael-min.js',
    	'assets/global/plugins/counterup/jquery.waypoints.min.js',
    	'assets/global/plugins/counterup/jquery.counterup.min.js',
    	'assets/global/plugins/amcharts/amcharts/amcharts.js',
    	'assets/global/plugins/amcharts/amcharts/serial.js',
    	'assets/global/plugins/amcharts/amcharts/pie.js',
    	'assets/global/plugins/amcharts/amcharts/radar.js',
    	'assets/global/plugins/amcharts/amcharts/themes/light.js',
    	'assets/global/plugins/amcharts/amcharts/themes/patterns.js',
    	'assets/global/plugins/amcharts/amcharts/themes/chalk.js',
    	'assets/global/plugins/amcharts/ammap/ammap.js',
    	'assets/global/plugins/amcharts/ammap/maps/js/worldLow.js',
    	'assets/global/plugins/amcharts/amstockcharts/amstock.js',
    	'assets/global/plugins/fullcalendar/fullcalendar.min.js',
    	'assets/global/plugins/flot/jquery.flot.min.js',
    	'assets/global/plugins/flot/jquery.flot.resize.min.js',
    	'assets/global/plugins/flot/jquery.flot.categories.min.js',
    	'assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js',
    	'assets/global/plugins/jquery.sparkline.min.js',
    	'assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js',
    	'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js',
    	'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js',
    	'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js',
    	'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js',
    	'assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js',
    	'assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js',
    	//END PAGE LEVEL PLUGINS
    	
    	//BEGIN THEME GLOBAL SCRIPTS
    	'assets/global/scripts/app.min.js',
    	//END THEME GLOBAL SCRIPTS
    	
    	//BEGIN PAGE LEVEL SCRIPTS
    	'assets/pages/scripts/dashboard.min.js',
    	//END PAGE LEVEL SCRIPTS 
    	
    	// BEGIN THEME LAYOUT SCRIPTS
    	'assets/layouts/layout4/scripts/layout.min.js',
    	'assets/layouts/layout4/scripts/demo.min.js',
    	'assets/layouts/global/scripts/quick-sidebar.min.js',
    	//END THEME LAYOUT SCRIPTS
    	
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}