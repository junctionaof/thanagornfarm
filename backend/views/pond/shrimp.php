<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;
use common\models\Pond;
use app\DateUtil;
use app\Workflow;
use common\models\ObjectCategory;
use app\CategoryTree;
use common\models\Media;
use app\Entity;
/* @var $this yii\web\View */
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;
$session = \Yii::$app->session;
$this->title = 'home thanagornfarm ';

$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js', ['position' => \yii\web\View::POS_END]);

$title = $pond->title ? $pond->title : 'ไม่ระบุ';

?>

 					<!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span class="active">Shrimp Pond</span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->

				    <!-- BEGIN PAGE BASE CONTENT -->
                    
                    <div class="portlet light form-fit bordered col-md-12">
                                <div class="portlet-title">
                                <div class="caption">
                                        <i class="icon-social-dribbble font-green  " style="font-size: 25px;"></i>
                                        <span class="caption-subject font-green bold uppercase" style="font-size: 25px;"> <?php echo $model->name ?>  </span>
                                        <span class="caption-helper" style="font-size: 25px;"> <?php echo $title ?></span>
                                    </div>
                                    <div class="actions">
                                        <a class="btn btn-circle btn-icon-only btn-default" href="<?= Url::toRoute(['pond/edit'])?>?id=<?php echo $pond->id; ?>">
                                            <i class="icon-wrench"></i>
                                        </a>
                                    </div>
                                </div>
                    	</div>
                    	<div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-green-sharp">
                                            <span data-counter="counterup" data-value="<?php echo $outputOnprocess?>">0</span>
                                            <small class="font-green-sharp">ก.ก.</small>
                                        </h3>
                                        <small>คาดการณ์ <br />ผลผลิตระหว่างเลี้ยง</small>
                                    </div>

                                </div>
                                <div class="progress-info">
                                    <div class="progress">
                                        <span style="width: 76%;" class="progress-bar progress-bar-success red-soft">
                                            <span class="sr-only">76% progress</span>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-title"> progress </div>
                                        <div class="status-number"> 76% </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-green-haze">
                                            <span data-counter="counterup" data-value="<?php echo number_format($survive);?>">0</span> %
                                        </h3>
                                        <small>% อัตราการรอดตาย <br /> ขณะทำการเลี้ยง</small>
                                    </div>
                                </div>
                                <div class="progress-info">
                                    <div class="progress">
                                        <span style="width: 85%;" class="progress-bar progress-bar-success red-soft">
                                            <span class="sr-only">85% change</span>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-title"> change </div>
                                        <div class="status-number"> 85% </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-green-sharp">
                                            <span data-counter="counterup" data-value="<?php echo $weightavg;?>"></span> 
                                            <small class="font-green-soft">กรัม/ตัว</small>
                                        </h3>
                                        
                                        <small>คํานวณ <br />นํ้าหนักเฉลี่ย ของกุ้ง</small>
                                    </div>
                                    
                                </div>
                                <div class="progress-info">
                                    <div class="progress">
                                        <span style="width: 45%;" class="progress-bar progress-bar-success red-soft">
                                            <span class="sr-only">45% grow</span>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-title"> grow </div>
                                        <div class="status-number"> 45% </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <div class="display">
                                    <div class="number">
                                        <h3 class="font-green-soft">
                                            <span data-counter="counterup" data-value="0.22"></span>
                                            <small class="font-green-soft">กรัม/วัน</small>
                                        </h3>
                                        <small>อัตราการเจริญเติบโต <br />ต่อวัน (ADG) </small>
                                    </div>
                                </div>
                                <div class="progress-info">
                                    <div class="progress">
                                        <span style="width: 57%;" class="progress-bar progress-bar-success red-soft">
                                            <span class="sr-only">56% change</span>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-title"> change </div>
                                        <div class="status-number"> 57% </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption caption-md">
                                        <i class="icon-bar-chart font-red"></i>
                                        <span class="caption-subject font-red bold uppercase">ข้อมูลรุ่น ต่างๆ ในบ่อ ที่ 1</span>
                                      
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row number-stats margin-bottom-30">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="stat-left">
                                                <div class="stat-chart">
                                                    <!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
                                                    <div id="sparkline_bar"></div>
                                                </div>
                                                <div class="stat-number">
                                                    <div class="title"> จำนวนลูกกุ้งปัจจุบัณ </div>
                                                    <div class="number"> 2460 </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="stat-right">
                                                <div class="stat-chart">
                                                    <!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
                                                    <div id="sparkline_bar2"></div>
                                                </div>
                                                <div class="stat-number">
                                                    <div class="title"> จำนวนลูกกุ้ง รุ่นที่แล้ว </div>
                                                    <div class="number"> 3000 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-scrollable table-scrollable-borderless">
                                        <table class="table table-hover table-light">
                                            <thead>
                                                <tr class="uppercase">
                                                    <th >รุ่น </th>
                                                    <th> วันที่ปล่อยกุ้ง </th>
                                                    <th> จำนวนลูกกุ้ง	 </th>
                                                    <th> บริษัท </th>
                                                </tr>
                                            </thead>
                                            <?php foreach($arrPond as $lsd):?>
                                             <tr>
                                                <td>
                                                    <a href="javascript:;" class="primary-link"><?=$lsd->title?></a>
                                                </td>
                                                <td> <?=$lsd->releaseTime?> </td>
                                                <td> <?=$lsd->larvae?> </td>
                                                <td> <?=$lsd->larvaeCompany?> </td>
                                            </tr>
                                            <?php endforeach;?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                         <div class="col-md-6 col-sm-6">
                            <!-- BEGIN PORTLET-->
                         <div class="portlet light bordered">
                              <div class="portlet-title tabbable-line">
                                    <div class="caption">
                                        <i class="icon-globe font-green-sharp"></i>
                                        <span class="caption-subject font-green-sharp bold uppercase">กราฟอัตราการเจริญเติบโต ลูกกุ้ง</span>
                                    </div>
                         	</div>
                         <div class="portlet-body">
                          <div class="clearfix">                             
                                        <div class="portlet-body">
                                            <div id="chart_3" class="chart" style="height: 240px;"> </div>
                                        </div>
                          </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
                         <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-globe font-green-sharp"></i>
                                        <span class="caption-subject font-green-sharp bold uppercase">รายละเอียดที่บันทึก</span>
                                    </div>
                                 </div>
                                     <div class="tabbable tabbable-tabdrop">
                                     <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tab_1_1" data-toggle="tab">ให้อาหาร </a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_2" data-toggle="tab">เช็คยอ</a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_3" data-toggle="tab"> นํ้าหนักเฉลี่ย </a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_4" data-toggle="tab">  ออกซิเจนละลายนํ้า</a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_5" data-toggle="tab">ค่า ph </a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_6" data-toggle="tab"> ค่าอัลคาไลน์นิติ</a>
                                            </li>
                                            <li>
                                                <a href="#tab_1_7" data-toggle="tab"> ค่าอุณหภูมิขิงนํ้า</a>
                                            </li>
                                            </li>
                                            <li>
                                           		<a href="#tab_1_8" data-toggle="tab"> อุณหภูมิแวดล้อม </a>
	                                        </li>
	                                        <li>
	                                            <a href="#tab_1_9" data-toggle="tab">  ค่าแอมโมเนีย </a>
	                                        </li> 
	                                        <li>
	                                            <a href="#tab_2_1" data-toggle="tab">  ค่าไนไตรท์ </a>
	                                        </li>
	                                           <li>
	                                            <a href="#tab_2_2" data-toggle="tab">  ค่าความเค็มของนํ้า </a>
	                                        </li>
	                                        <li>
	                                            <a href="#tab_2_3"  data-toggle="tab"> การเปลี่ยนถ่ายนํ้า</a>
	                                        </li> 
	                                       	<li>
	                                            <a href="#tab_2_4" data-toggle="tab">  อื่นๆ</a>
	                                        </li>
                                           
                                        </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1_1">
                                            <div class="scroller" style="height: 339px;" data-always-visible="1" data-rail-visible="0">
                                                <ul class="feeds">
                                                    <?php foreach ($food As $lstfood): ?>
                                                    <li>
                                                        <div class="col1">
                                                            <div class="cont">
                                                                <div class="cont-col1">
                                                                    <div class="label label-sm label-success">
                                                                        <i class="fa fa-bell-o"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="cont-col2">
                                                                    <div class="desc"><?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstfood->foodTime));?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col2">
                                                            <div class="date">มื้อที่ <?php echo $lstfood->foodNo ?> </div>
                                                        </div>
                                                    </li>
                                                    <?php endforeach;?>
                                                </ul>
                                                <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesfood->offset+1?> - <?= $pagesfood->offset+30?> จากทั้งหมด <?= $countfood?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesfood, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												</div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab_1_2">
			                                <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา เช็ค </th>
			                                                    <th> ยอที่ 1 วัดได้ </th>
			                                                    <th> ยอที่ 2 วัดได้  </th>
			                                                    <th> ยอที่ 3 วัดได้  </th>
			                                                    <th>ยอที่ 4 วัดได้  </th>
			                                                    <th>มื้อที่ </th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($checkyo As $lstcheckyo): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstcheckyo->checkyoTime));?> </td>
			                                                    <td> <?php echo $lstcheckyo->yo01 ?>/4 </td>
			                                                    <td> <?php echo $lstcheckyo->yo02 ?>/4 </td>                           
			                                                    <td> <?php echo $lstcheckyo->yo03 ?>/4 </td>
			                                                    <td> <?php echo $lstcheckyo->yo04 ?>/4 </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"> มื้อที่ <?php echo $lstcheckyo->checkyoNo ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesCheckyo->offset+1?> - <?= $pagesCheckyo->offset+30?> จากทั้งหมด <?= $countCheckyo?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesCheckyo, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                    </div>
			                                </div>
			                            </div>
                                       <div class="tab-pane" id="tab_1_3">
 											<div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($weight As $lstweight): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstweight->weightTime));?> </td>
			                                                    <td> <?php echo $lstweight->name ?> </td>
			                                                    <td> <?php echo $lstweight->weightNo ?></td>                           
			                                                    <td> <?php echo $lstweight->weightNum ?> </td>
			                                                    <td> <?php echo $lstweight->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstweight->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesWeight->offset+1?> - <?= $pagesWeight->offset+30?> จากทั้งหมด <?= $countWeight?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesWeight, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                        <div class="tab-pane" id="tab_1_4">
                                       <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริออกซิเจนละลานรํ้า  </th>
			                                                    <th> เบอร์</th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($oxygen As $lstoxygen): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstoxygen->oxygenTime));?> </td>
			                                                    <td> <?php echo $lstoxygen->name ?> </td>
			                                                    <td> <?php echo $lstoxygen->oxygenNo ?></td>                           
			                                                    <td> <?php echo $lstoxygen->oxygenNum ?> </td>
			                                                    <td> <?php echo $lstoxygen->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstoxygen->numberOf ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesOxygen->offset+1?> - <?= $pagesOxygen->offset+30?> จากทั้งหมด <?= $countOxygen?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesOxygen, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                        <div class="tab-pane" id="tab_1_5">
                                       <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($ph As $lstph): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstph->phTime));?> </td>
			                                                    <td> <?php echo $lstph->age ?> </td>
			                                                    <td> <?php echo $lstph->phNo ?></td>                           
			                                                    <td> <?php echo $lstph->phNum ?> </td>
			                                                    <td> <?php echo $lstph->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstph->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesPh->offset+1?> - <?= $pagesPh->offset+30?> จากทั้งหมด <?= $countPh?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesPh, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                         <div class="tab-pane" id="tab_1_6">
											<div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($alkalinity As $lstalkalinity): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstalkalinity->weightTime));?> </td>
			                                                    <td> <?php echo $lstalkalinity->name ?> </td>
			                                                    <td> <?php echo $lstalkalinity->weightNo ?></td>                           
			                                                    <td> <?php echo $lstalkalinity->weightNum ?> </td>
			                                                    <td> <?php echo $lstalkalinity->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstalkalinity->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesAlkalinity->offset+1?> - <?= $pagesAlkalinity->offset+30?> จากทั้งหมด <?= $countAlkalinity?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesAlkalinity, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                        <div class="tab-pane" id="tab_1_7">
                                        <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($weight As $lstweight): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstweight->weightTime));?> </td>
			                                                    <td> <?php echo $lstweight->name ?> </td>
			                                                    <td> <?php echo $lstweight->weightNo ?></td>                           
			                                                    <td> <?php echo $lstweight->weightNum ?> </td>
			                                                    <td> <?php echo $lstweight->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstweight->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesWeight->offset+1?> - <?= $pagesWeight->offset+30?> จากทั้งหมด <?= $countWeight?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesWeight, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                        <div class="tab-pane" id="tab_1_8">
                                        <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($weight As $lstweight): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstweight->weightTime));?> </td>
			                                                    <td> <?php echo $lstweight->name ?> </td>
			                                                    <td> <?php echo $lstweight->weightNo ?></td>                           
			                                                    <td> <?php echo $lstweight->weightNum ?> </td>
			                                                    <td> <?php echo $lstweight->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstweight->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesWeight->offset+1?> - <?= $pagesWeight->offset+30?> จากทั้งหมด <?= $countWeight?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesWeight, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                        <div class="tab-pane" id="tab_1_9">
                                        <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($weight As $lstweight): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstweight->weightTime));?> </td>
			                                                    <td> <?php echo $lstweight->name ?> </td>
			                                                    <td> <?php echo $lstweight->weightNo ?></td>                           
			                                                    <td> <?php echo $lstweight->weightNum ?> </td>
			                                                    <td> <?php echo $lstweight->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstweight->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesWeight->offset+1?> - <?= $pagesWeight->offset+30?> จากทั้งหมด <?= $countWeight?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesWeight, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                        <div class="tab-pane" id="tab_2_1">
                                       <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($weight As $lstweight): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstweight->weightTime));?> </td>
			                                                    <td> <?php echo $lstweight->name ?> </td>
			                                                    <td> <?php echo $lstweight->weightNo ?></td>                           
			                                                    <td> <?php echo $lstweight->weightNum ?> </td>
			                                                    <td> <?php echo $lstweight->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstweight->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesWeight->offset+1?> - <?= $pagesWeight->offset+30?> จากทั้งหมด <?= $countWeight?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesWeight, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                        <div class="tab-pane" id="tab_2_2">
                                       <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($weight As $lstweight): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstweight->weightTime));?> </td>
			                                                    <td> <?php echo $lstweight->name ?> </td>
			                                                    <td> <?php echo $lstweight->weightNo ?></td>                           
			                                                    <td> <?php echo $lstweight->weightNum ?> </td>
			                                                    <td> <?php echo $lstweight->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstweight->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesWeight->offset+1?> - <?= $pagesWeight->offset+30?> จากทั้งหมด <?= $countWeight?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesWeight, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>
                                     <div class="tab-pane" id="tab_2_3">
                                     <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($weight As $lstweight): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstweight->weightTime));?> </td>
			                                                    <td> <?php echo $lstweight->name ?> </td>
			                                                    <td> <?php echo $lstweight->weightNo ?></td>                           
			                                                    <td> <?php echo $lstweight->weightNum ?> </td>
			                                                    <td> <?php echo $lstweight->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstweight->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesWeight->offset+1?> - <?= $pagesWeight->offset+30?> จากทั้งหมด <?= $countWeight?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesWeight, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div>                                       
                                        
                                    <div class="tab-pane" id="tab_2_4">
                                     <div class="portlet light ">
			                                <div class="portlet-body">
			                                    <div class="">
			                                        <table class="table table-hover table-light">
			                                            <thead>
			                                                <tr>
			                                                    <th> วัน เวลา วัด </th>
			                                                    <th> บ่อ</th>
			                                                    <th> มื้อที่  </th>
			                                                    <th> ปริมาณอาหาร  </th>
			                                                    <th> เบอร์อาหาร </th>
			                                                    <th> อายุลูกกุ้ง</th>
			                                                </tr>
			                                            </thead>
			                                            <tbody>
			                                             <?php foreach ($weight As $lstweight): ?>
			                                                <tr>
			                                                    <td> <?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($lstweight->weightTime));?> </td>
			                                                    <td> <?php echo $lstweight->name ?> </td>
			                                                    <td> <?php echo $lstweight->weightNo ?></td>                           
			                                                    <td> <?php echo $lstweight->weightNum ?> </td>
			                                                    <td> <?php echo $lstweight->numberOf ?> </td>
			                                                    <td>
			                                                        <span class="label label-sm label-success"><?php echo $lstweight->age ?> </span>
			                                                    </td>
			                                                </tr>
			                                             <?php endforeach;?>
			                                            </tbody>
			                                        </table>
			                                      <div class="row">
														<div class="btn-group col-md-5 col-sm-12">
															<div class="dataTables_info" id="sample_1_info">รายการที่ <?= $pagesWeight->offset+1?> - <?= $pagesWeight->offset+30?> จากทั้งหมด <?= $countWeight?></div>
														</div>
														<div class="btn-group col-md-7 col-sm-12">
															<?php echo LinkPager::widget(['pagination' => $pagesWeight, 'options' => ['class'=> 'pagination pull-right']])?>		
														</div>
												 </div>
			                                    </div>
			                                 </div>
			                                </div>
                                        </div> 
                                        
                                    </div>
                                    <!--END TABS CONTENT-->
                                </div>
                                  <!--END TABS TABLE -->
                            </div>
                            <!-- END PORTLET-->
                        </div>
                        </div>
                      
                    </div>
                    <!-- END PAGE BASE CONTENT -->
                   