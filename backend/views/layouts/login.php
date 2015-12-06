<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\bootstrap\Nav;
	use yii\bootstrap\NavBar;
	use yii\widgets\Breadcrumbs;
	use yii\helpers\BaseUrl;
	
	use backend\assets\LoginAsset;
	
	$baseUrl = \Yii::getAlias('@web');
	LoginAsset::register($this);

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.0.2
Version: 1.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title><?php echo Html::encode($this->title); ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="MobileOptimized" content="320">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->          
<?php $this->head(); ?>
<?php 
$str = <<<EOT
	
/* fix vertical when not overflow
call fullscreenFix() if .fullscreen content changes */
function fullscreenFix(){
    var h = $('body').height();
    // set .fullscreen height
    $(".content-b").each(function(i){
        if($(this).innerHeight() <= h){
            $(this).closest(".fullscreen").addClass("not-overflow");
        }
    });
}
$(window).resize(fullscreenFix);
fullscreenFix();

/* resize background images */
function backgroundResize(){
    var windowH = $(window).height();
    $(".background").each(function(i){
        var path = $(this);
        // variables
        var contW = path.width();
        var contH = path.height();
        var imgW = path.attr("data-img-width");
        var imgH = path.attr("data-img-height");
        var ratio = imgW / imgH;
        // overflowing difference
        var diff = parseFloat(path.attr("data-diff"));
        diff = diff ? diff : 0;
        // remaining height to have fullscreen image only on parallax
        var remainingH = 0;
        if(path.hasClass("parallax")){
            var maxH = contH > windowH ? contH : windowH;
            remainingH = windowH - contH;
        }
        // set img values depending on cont
        imgH = contH + remainingH + diff;
        imgW = imgH * ratio;
        // fix when too large
        if(contW > imgW){
            imgW = contW;
            imgH = imgW / ratio;
        }
        //
        path.data("resized-imgW", imgW);
        path.data("resized-imgH", imgH);
        path.css("background-size", imgW + "px " + imgH + "px");
    });
}
$(window).resize(backgroundResize);
$(window).focus(backgroundResize);
backgroundResize(); 
			
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
  
	//QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features
   
EOT;

$this->registerJs($str, \yii\web\View::POS_READY, 'start-js');

$str = <<<EOT
/* background setup */
.background {
    background-repeat:no-repeat;
    /* custom background-position */
    background-position:50% 50%;
    /* ie8- graceful degradation */
    background-position:50% 50%\9 !important;
}

/* fullscreen setup */
html, body {
    /* give this to all tags from html to .fullscreen */
    height:100%;
}
.fullscreen,
.content-a {
    width:100%;
    min-height:100%;
	position: absolute;
}
.not-fullscreen,
.not-fullscreen .content-a,
.fullscreen.not-overflow,
.fullscreen.not-overflow .content-a {
    height:100%;
    overflow:hidden;
}
EOT;
$this->registerCss($str, [], 'css'); ?>
</head>	
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">

<?php $this->beginBody() ?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">

	<!-- BEGIN LOGO -->
	<div class="logo">
		<!-- <img src="<?php echo $baseUrl?>assets/global/img/tpbs-org-logo.png" alt="" />  -->
		<h1><span class="label label-info">THANAGORN</span><span class="label label-warning">FARM</span> </h1>
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<?= $content ?>
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		COPYRIGHT &copy;2015  THANAGORN FARM
	</div>
	<!-- END JAVASCRIPTS -->

<?php $this->endBody() ?>
</div>
</body>
</html>
<?php $this->endPage() ?>