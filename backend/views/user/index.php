<?php 
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$str = <<<EOT
$('#timepicker').datepicker({
		format: 'mm-yyyy',
		viewMode: "months",
    	minViewMode: "months"
});

EOT;
$this->registerJs($str);
?>
<style>
.boldText{
font-weight: bold !important;
font-size: 20px !important;
}
.table-condensed{
width:100%;
height: 300px;
}
.datepicker-inline {
width: 300px;
}
.datepicker-days{
border:1px solid #DDDDDD;
padding:5px;
}
.previewLabel{
width:300px;
height:200px;
border: 1px solid #999999;
padding:10px;
}
.previewText1{
	position: absolute;
	top: 120px;
}
</style>
						<div class="tab-pane " id="tab_3">
								<div class="portlet box grey">
									<div class="portlet-title">
										<div class="caption"><i class="fa fa-reorder"></i>ข้อมูลผู้ใช้งานระบบ</div>
										<div class="tools">
											<a href="javascript:;" class="collapse"></a>
											<a href="#portlet-config" data-toggle="modal" class="config"></a>
											<a href="javascript:;" class="reload"></a>
											<a href="javascript:;" class="remove"></a>
										</div>
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form class="form-horizontal" role="form">
											<div class="form-body">
												<h2 class="margin-bottom-20"> ชื่อ นามสกุล </h2>
												<h3 class="form-section">Person Info</h3>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">First Name:</label>
															<div class="col-md-9">
																<p class="form-control-static">ชื่อ</p>
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">Last Name:</label>
															<div class="col-md-9">
																<p class="form-control-static">นามสกุุล</p>
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">Gender:</label>
															<div class="col-md-9">
																<p class="form-control-static">ชาย</p>
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">Date of Birth:</label>
															<div class="col-md-9">
																<p class="form-control-static">24 ตุลาคม 2555</p>
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->        
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">ตำแหน่ง:</label>
															<div class="col-md-9">
																<p class="form-control-static">User</p>
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">User Name:</label>
															<div class="col-md-9">
																<p class="form-control-static">Name</p>
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												   
											
											</div>
											<div class="row">
											<div class="col-md-12">
												<div class="form-actions fluid">
													<div class="text-center">
														<button type="submit" class="btn green">ตกลง</button> 
														<button type="button" class="btn" id="btnCancel">ยกเลิก</button>
													</div>
												</div>
											</div>
											</div>
											</form>
										<!-- END FORM-->  
									</div>
								
								</div>
							</div>
