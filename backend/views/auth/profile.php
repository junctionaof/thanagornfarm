<?php 
use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
//use yii\bootstrap\Button;
use yii\helpers\Url;

//$cs = Yii::app ()->clientScript;
$cancelUrl = Url::toRoute(['site/index']);
$baseUrl = \Yii::getAlias('@web');
$this->registerJsFile($baseUrl  . '/assets/scripts/form-components.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js', ['position' => \yii\web\View::POS_END]);

$str = <<<EOT
FormComponents.init();
$("#tab-data div.col-md-4").sortable({
	connectWith: '#tab-data div.col-md-4',
	items : ".portlet",
	opacity : 0.5,
	placeholder : 'sortable-box-placeholder round-all',
	forcePlaceholderSize : true,
	tolerance : "pointer"
});
 
$('#btnCancel').click(function() {
		location.assign('$cancelUrl');
});
$(document).ready(function(){
	var x =	$('#name').val();
	if(x==""){
		$("#show").hide();				
	}
	
	$('#search-categoryId').addClass('form-control input-medium');
});
		
$( document ).ready(function() {	
	$('input[name="password"],input[name="confirmPassword"]').keyup(function(){
		var text = 'Password ถูกต้อง';
		if( $('input[name="password"]').val() !== $('input[name="confirmPassword"]').val() ){
			text = 'กรุณากรอกPasswordให้ตรงกัน';
		}
		$('.ect').text(text);
	});
});

EOT;
$this->registerJs($str, View::POS_LOAD, 'form-js');
?>

<style>
	.paddingtop {
	padding: 20px 0 0 0;
	}
	
	.padding-top {
	padding: 2px 0 0 0;
	}
	
	.paddingrow {
	padding: 20px 20px 20px 20px;
	}

</style>

<div class="row">
	<div class="col-md-12"><?php //$this->beginwidget('UserMessage')?><?php //$this->endWidget('UserMessage'); ?></div>
	<div class="col-md-12">
		 <?php
		 /* $previewOptions = array(Media::ENCODE_WIDTH => 250); 
		if ($dateText == "") {
			$dateText = date ( "Y-m-d" );
		} */
		?>
			<div class="portlet box grey tabbable form">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-edit"></i> Update Profile
					</div>
				</div>
				
				<div class="portlet-body form">
				<div class=" portlet-tabs">
					<div class="tabbable portlet-tabs">
							<ul class="nav nav-tabs">
								<!-- <li><a href="#media-tab" data-toggle="tab">รูปภาพ</a></li> -->
								<li class="active"><a href="#profile-tab" data-toggle="tab">ข้อมูลส่วนบุคคล</a></li>
							</ul>
				<div class="tab-content">
				<div class="tab-pane active row" id="profile-tab">
				<div class="paddingtop">
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-12">
								<!-- Begin Left Menu -->
								<div class="col-md-9">
									<div class="portlet">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-cog"></i>แก้ไขข้อมูลส่วนบุคคล
											</div>
										</div>
										<div class="portlet-body">
											<div class="row">
												<div class="col-md-4">
										
													 <div class="fileupload fileupload-new" data-provides="fileupload">
														<div class="fileupload-new thumbnail" > 
															<img alt="ยังไม่มีรูปภาพ"  width="250px" src="<?php echo $baseUrl ?>/assets/img/no-image.jpg">
														</div>
													</div>

												</div>
												<?php $form = ActiveForm::begin([
																	'layout' => 'horizontal',
																]);
												?>
												<?php //echo CHtml::form('', 'post', array('class' => 'form-horizontal', 'id'=>'profile-form'))?>
												<div class="col-md-4">
													<div class="margin-top-20">
														<label class="control-label">Firstname<span class="required"> * </span></label>
														<div>
															<?= Html::activeInput('text', $profile, 'firstName', ['id'=>'firstName','class' => 'form-control', 'placeholder' => 'กรุณากรอกชื่อ'])?>
														</div>
													</div>
													<div class="margin-top-20">
														<label class="control-label">Lastname</label>
														<div>
															<?= Html::activeInput('text', $profile, 'lastName', ['id'=>'lastName','class' => 'form-control', 'placeholder' => 'กรุณากรอกนามสกุล'])?>															
														</div>
													</div>
													<div class="margin-top-20">
														<label class="control-label">Nickname</label>
														<div>
															<?= Html::activeInput('text', $profile, 'nickName', ['id'=>'nickName','class' => 'form-control', 'placeholder' => 'กรุณากรอกชื่อเล่น'])?>
														</div>
													</div>
													<div class="margin-top-20">
														<label class="control-label">Bank Account</label>
														<div>
															<?= Html::activeInput('text', $profile, 'bankAccount', ['id'=>'bankAccount','class' => 'form-control', 'placeholder' => 'กรุณากรอกเลขที่บัญชีธนาคาร'])?>
														</div>
													</div>
												</div>		
												<div class="col-md-4">
													<div class="margin-top-20">
														<label class="control-label">Telephone</label>
														<div class="input-group">
															<?= Html::activeInput('text', $profile, 'phone', ['id'=>'phone','class' => 'form-control', 'placeholder' => 'กรุณากรอกเบอร์ติดต่อ'])?>
															<span class="input-group-addon"><i class="fa fa-phone"></i></span>
														</div>
													</div>
													<div class="margin-top-20">
														<label class="control-label">Email Address</label>
														<div class="input-group">
															<?= Html::activeInput('text', $profile, 'email', ['id'=>'email','class' => 'form-control', 'placeholder' => 'กรุณากรอกอีเมล์'])?>
															<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
														</div>
													</div>
													<div class="margin-top-20">
														<label class="control-label">Address</label>
														<div class="input-icon">
															<i class="fa fa-angle-right"></i>
															<?= Html::activeInput('text', $profile, 'address', ['id'=>'address','class' => 'form-control', 'placeholder' => 'กรุณากรอกที่อยู่'])?>
															<span class="help-block"></span>
														</div>
													</div>
												</div>									
											</div>				
										</div>
									</div>
								</div>
								<!-- End Left Menu -->
								<!-- Begin Right Menu -->
								<div class="col-md-3">
									<div class="portlet solid grey">
										<div class="portlet-title">
											<div class="caption">
												<span class="margin-top-20"><i class="fa fa-lock"></i> แก้ไขรหัสผ่านสำหรับเข้าสู่ระบบ</span>
											</div>
										</div>
										<div class="portlet-body">
											<div class="paddingrow">
												<label class="control-label">Enter new password</label>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-key"></i></span>
													<?= Html::passwordInput('password', '', ['class'=>'form-control', 'placeholder'=>'กรุณากรอกรหัสผ่านใหม่'])?>
												</div>
											</div>
											<div class="paddingrow">
												<label class="control-label">Re-enter new password</label>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-key"></i></span>
													<?= Html::passwordInput('confirmPassword', '', ['class'=>'form-control', 'placeholder'=>'กรุณากรอกรหัสผ่านใหม่อีกครั้ง'])?>
												</div>
											</div>
											<div>
												<div class="input-icon">
													<p class="ect"></p>
												</div>
											</div>
											<div class="form-actions nobg fluid">
												<div align="center">
													<button type="submit" name="submit" class="btn green">ตกลง</button>
													<button type="button" class="btn red" id="btnCancel">ยกเลิก</button>
												</div>
											</div>
										</div>
										
									</div>
								</div>
								<!-- End Right Menu -->
							<?php ActiveForm::end() ?>
							</div>
						</div>
					</div>
				</div>
				
				
			</div>
				
			
	</div></div></div></div>
	<!--  -->
	</div>

</div>
</div>
