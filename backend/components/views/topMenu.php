<?php
use yii\helpers\Url;
use common\models\User;
use yii\helpers\Html;
$identity = \Yii::$app->user->getIdentity();
$baseUrl = \Yii::getAlias('@web');
if(isset($identity)){
	$name = $identity->firstName.' '.$identity->lastName ;
	$id = $identity->id;
}else {
	$name = "ผู้เยี่ยมชม";
	$id = "0";
}
$user = \Yii::$app->user;
?>
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="header-inner">
			<!-- BEGIN LOGO -->  
			<a class="navbar-brand" href="<?php echo $baseUrl?>/" style="padding-top: 5px; padding-bottom:0px;">
			 <img src="<?php echo $baseUrl?>/assets/img" alt="logo" class="img-responsive" style="height:45px;" />
			</a>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER --> 
			<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<img src="<?php echo $baseUrl ?>/assets/img/menu-toggler.png" alt="" />
			</a> 
			<!-- END RESPONSIVE MENU TOGGLER -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<ul class="nav navbar-nav pull-right" style=" margin: 0px;">
				<!-- BEGIN NOTIFICATION DROPDOWN -->
				<li class="dropdown user">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
			          <img alt="" src="<?php echo $baseUrl ?>/assets/img/avatar1_small.jpg"/> 
			          <span class="username"> <?php if($name){ echo $name; }?> </span>
			          <i class="fa fa-angle-down"></i>
			          </a>
					<ul class="dropdown-menu">
						 <li><a href="<?= Url::toRoute(['/user/edit'])?>?id=<?=$id?>"><i class="fa fa-user"></i> ข้อมูลส่วนตัว</a></li>
						<li class="divider"></li>
						<li><a href="http://tpbs.ndev.pw/" target="_blank" id="trigger_fullscreen"><i class="fa fa-home"></i> Visit site</a></li>
						<li><a href="<?= Url::to(['site/logout'])?>" data-method="post"><i class="fa fa-key"></i> Log Out</a></li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
			</ul>
			<!-- END TOP NAVIGATION MENU -->
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
