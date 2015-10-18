<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;

use app\CategoryTree;
use app\Workflow;

use backend\components\UserMessage;

use common\models\Media;
use common\models\Gallery;

$baseUrl = \Yii::getAlias('@web');

$this->registerCssFile("$baseUrl/global/plugins/jquery-nestable/jquery.nestable.css");
?>
	
	
	<!-- BEGIN PAGE HEADER-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
							SHRIMP <strong> RECORD </strong> SYSTEM 
					</h3>
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="<?=$baseUrl?>/index.php">หน้าหลัก</a> 
														<i class="fa fa-angle-right"></i>
						</li>
						<li>
						<i class="fa fa-file-text"></i>
							<a href="<?=$baseUrl?>/paymentitem/list">พยากรณ์อากาศ </a>
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
<!-- END PAGE HEADER-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box red">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>Weather</div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
								<a href="#portlet-config" data-toggle="modal" class="config"></a>
								<a href="javascript:;" class="reload"></a>
								<a href="javascript:;" class="remove"></a>
							</div>
						</div>
						<div class="portlet-body">
							<div id="chart_2" class="chart"></div>
						</div>
					</div>
				</div>
			</div>
	
	
	
<?php
$this->registerJsFile($baseUrl.'/global/plugins/flot/jquery.flot.js');
$this->registerJsFile($baseUrl.'/global/plugins/flot/jquery.flot.resize.js');
$this->registerJsFile($baseUrl.'/global/plugins/flot/jquery.flot.pie.js');
$this->registerJsFile($baseUrl.'/global/plugins/flot/jquery.flot.stack.js');
$this->registerJsFile($baseUrl.'/global/plugins/jquery.cokie.min.js');
$this->registerJsFile($baseUrl.'/global/plugins/flot/jquery.flot.crosshair.js');

$this->registerJsFile($baseUrl.'/global/scripts/charts.js');
$this->registerJsFile($baseUrl.'/global/scripts/app.js');


$str = <<<EOT
		   App.init();
		   Charts.init();
		   Charts.initCharts();
		   Charts.initPieCharts();
		   Charts.initBarCharts();
EOT;
$this->registerJs($str);
?>





