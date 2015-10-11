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
<script src="<?php echo $baseUrl ?>/global/scripts/app.js"></script>
	<script>
		jQuery(document).ready(function() {
		   App.init();
		   Login.init();
		   App.baseUri = '<?php echo $baseUrl?>/';

		   jQuery.ajaxSetup({
			   cache: false,
		   });

		});
	</script>
<?php 
$str = <<<EOT
	
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
  
	//QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features
   
   
    //TableManaged.init();
    //FormComponents.init();
    //ComponentsDropdowns.init();
    //ComponentsFormTools.init();
    //ComponentsPickers.init(); 
        
EOT;

$this->registerJs($str, \yii\web\View::POS_READY, 'start-js');
?>
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
		<img src="<?php echo $baseUrl?>/assets/img/cms-logo.png" alt="" /> 
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<?= $content ?>
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		COPYRIGHT &copy;2015  TPBS
	</div>
	<!-- END JAVASCRIPTS -->
</body>
<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>