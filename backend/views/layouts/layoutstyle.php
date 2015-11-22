<?php 
    use backend\assets\ThanagornAsset;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Breadcrumbs;
    use yii\helpers\BaseUrl;
    

    use backend\components\TopMenu;
    use backend\components\SideBar;
    use backend\components\Message;
    
    $baseUrl = \Yii::getAlias('@web');
    ThanagornAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->	
	<head>
	 	<title>Thanagorn Farm</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		<?php $this->head(); ?>
		<link rel="shortcut icon" href="favicon.ico"/>
	</head>
<!-- END HEAD -->

<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">
<?php $this->beginBody() ?>
<!-- BEGIN HEADER -->
<?= TopMenu::widget() ?>
<!-- END HEADER -->
 <div class="clearfix"> </div>
 
<!-- BEGIN CONTAINER -->
 <div class="page-container">
    <!-- BEGIN SIDEBAR -->
	<?= SideBar::widget() ?>
 	<!-- END SIDEBAR -->
	
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<!-- BEGIN CONTENT BODY -->
		<div class="page-content">
			<!-- BEGIN PAGE HEAD-->
			<div class="page-head">
                 <!-- BEGIN PAGE TITLE -->
                 <div class="page-title">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h1>
							SHRIMP <strong> RECORD </strong> SYSTEM 
					</h1>
                  </div>
            </div>
            <!-- END PAGE HEAD-->
			<?php echo Message::widget() ?>
            <?php echo $content ?>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	
	<!-- END CONTENT -->
	<!-- BEGIN QUICK SIDEBAR -->
	<a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
        <!-- รายละเอียดในปุ่ม logout ลบออกไปแล้ว -->
	<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->


<!-- END JAVASCRIPTS -->
    <?php $this->endBody() ?>

</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>
