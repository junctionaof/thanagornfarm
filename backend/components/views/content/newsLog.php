<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;

use app\DateUtil;
use app\Workflow;
?>

<div class="row">
	<div class="col-md-12">
		<div class="portlet ">
			<div class="portlet-body form">
				<div class="form-body">
		<div class="caption">
			<i class="fa fa-reorder"></i>โปรไฟล์ข่าว
		</div>
					<form class="form-horizontal" role="form">
						<h4 class="form-section">
							<i class="fa fa-user-md"></i> ข้อมูลทั่วไป
						</h4>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><strong>รหัสข่าว:</strong></label>
									<div class="col-md-9">
										<p class="form-control-static"><?php echo Html::encode($Content->id);?></p>
									</div>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><strong>หัวข้อข่าว:</strong></label>
									<div class="col-md-9">
										<p class="form-control-static"><?php echo Html::encode($Content->title);?></p>
									</div>
								</div>
							</div>
							<!--/span-->
						</div>

						<!--/row-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><strong>สถานะ:</strong></label>
									<div class="col-md-9">
									<?php 
										$statusStr = isset(Workflow::$arrStatusTpbs[$Content->status])?Workflow::$arrStatusTpbs[$Content->status]:'-';
									?>
										<p class="form-control-static"><?php echo Html::encode($statusStr);?></p>
									</div>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-6">
								<div class="form-group">
									
								</div>
							</div>
							<!--/span-->
						</div>
						<!--/row-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><strong>บันทึกโดย:</strong></label>
									<div class="col-md-9">
									<?php 
										$CreateBy = $Content->getCreateBy()->one();
										$LastUpdateBy = $Content->getLastUpdateBy()->one();
										
										$strCreateBy = NULL;
										$strLastUpdateBy = NULL;
											if(!empty($CreateBy))
												$strCreateBy = $CreateBy->firstName . ' - '. $CreateBy->lastName;
											
											if(!empty($LastUpdateBy))
												$strLastUpdateBy = $LastUpdateBy->firstName . ' - '. $LastUpdateBy->lastName;
									?>
										<p class="form-control-static"><?php echo Html::encode($strCreateBy);?></p>
									</div>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><strong>วัน/เวลาที่บันทึก:</strong></label>
									<div class="col-md-9">
										<p class="form-control-static"><?php echo Html::encode(DateUtil::th_date(DateUtil::LDT_FMT_TH,strtotime($Content->createTime)));?></p>
									</div>
								</div>
							</div>
							<!--/span-->
						</div>
						<!--/row-->
						<!--/row-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><strong>แก้ไขโดย:</strong></label>
									<div class="col-md-9">
										<p class="form-control-static"><?php echo Html::encode($strLastUpdateBy);?></p>
									</div>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3"><strong>วันที่/เวลาแก้ไขล่าสุด:</strong></label>
									<div class="col-md-9">
										<p class="form-control-static"><?php echo Html::encode(DateUtil::th_date(DateUtil::LDT_FMT_TH,strtotime($Content->lastUpdateTime)));?></p>
									</div>
								</div>
							</div>
							<!--/span-->
						</div>
						<!--/row-->
					</form>
					
					<!-- BEGIN AuditLog -->
					<div class="row">
					<div class="form-body">
						<div class="col-md-12">
							<h4><i class="fa fa-info"></i> ข้อมูลการเข้าใช้</h4>
							<div class="table-scrollable" >
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>	
											<th class="text-center"><strong>#</strong></th>		
											<th width="30%"><strong>ชื่อผู้ใช้</strong></th>
											<th><strong>ประเภทผู้ใช้</strong></th>
											<th><strong>วันเวลาที่เข้าใช้</strong></th>
											<th><strong>ลักษณะการเข้าใช้งาน</strong></th>
										</tr>
									</thead>
									<tbody>
									<?php 
									$number = 1;
									if($arrLog):
										foreach ($arrLog as $fields):
											$ts = DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($fields->ts));
									?>
										<tr>
											<td class="text-center"><?php echo $number;?></td>
											<td><?php echo $arrUser[$fields->userId]?></td>
											<td>-</td>
											<td><?php echo $ts?></td>
											<td><?php echo Workflow::$arrStatusTpbs[$fields->status]?></td>
										</tr>
									<?php 
										$number++;
										endforeach;
									endif;
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					</div>
					<!-- END AuditLog -->


				</div>
			</div>
		</div>
	</div>



</div>
