<?php 
use yii\helpers\Url;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\Button;
use yii\bootstrap\ActiveForm;
use common\models\User;
$baseUrl = \Yii::getAlias('@web');
$backUrl = Url::toRoute('list');

$str = <<<EOT
$('#timepicker').datepicker({
		format: 'mm-yyyy',
		viewMode: "months",
    	minViewMode: "months"
		
		
});
		
$( document ).ready(function() {	
$('input[name="password"],input[name="confirmPassword"]').keyup(function(){
	var text = 'Password ถูกต้อง';
	if( $('input[name="password"]').val() !== $('input[name="confirmPassword"]').val() ){
		text = 'กรุณากรอกPasswordให้ตรงกัน';
	}
	$('.ect').text(text);
	})
});
		
$('#btnCancel').click(function() {
  window.location.assign('$baseUrl."/user/list"');
	 window.location.href = 
});	

EOT;
$this->registerJs($str);

?>
<style>
.row{
    margin-top: 25px;
  	margin-bottom: 10px;
  	
}

.rowbb {
  background-color: #F5FBF3;
    margin: 5px;
    padding: 15px;
}
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

<?php $form = ActiveForm::begin([
				'layout' => 'horizontal',
						]);
				?>

	<div class="portlet box grey">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>แก้ใขข้อมูลผู้ใช้งาน</div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
								<a href="#portlet-config" data-toggle="modal" class="config"></a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="tabbable-custom ">
								<ul class="nav nav-tabs ">
									<li class="active"><a href="#info" data-toggle="tab">ข้อมูลผู้ใช้งาน</a></li>
								</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="info">
								<h4 style="padding-left:20px;">ประวัติผู้ใช้งาน</h4>
									<div class="row">
														<div class="col-md-5 col-md-offset-1">
															<div class="form-group">
																<label class="control-label col-md-3"><span class="required"> * </span>ชื่อผู้ใช้งาน : </label>
																<div class="col-md-9">
																	<?= Html::activeInput('text', $model, 'firstName', ['id'=>'firstName','class' => 'form-control', 'required'=>'required' , 'placeholder' => 'กรอกชื่อผู้ใช้งาน..'])?>
																</div>
			 												</div>
															<div class="form-group">
																<label class="control-label col-md-3"><span class="required"> * </span>นามสกุล : </label>
																<div class="col-md-9">
																	<?= Html::activeInput('text', $model, 'lastName', ['id'=>'lastName','class' => 'form-control' , 'required'=>'required', 'placeholder' => 'กรอกนามสกุลผู้ใช้งาน..'])?>
																</div>
															</div>
															
															<div class="form-group">
																<label class="control-label col-md-3">โทรศัพท์ : </label>
																<div class="col-md-9">
																	<?= Html::activeInput('text', $model, 'phone', ['id'=>'phone','class' => 'form-control', 'placeholder' => 'กรอกเบอร์โทรศัพท์ผู้ใช้งาน..'])?>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">อีเมล์ : </label>
																<div class="col-md-9">
																	<?= Html::activeInput('text', $model, 'email', ['id'=>'email','class' => 'form-control',  'placeholder' => 'กรอกอีเมลผู้ใช้งาน..'])?>
																</div>
															</div>
															
															
								
														</div>
														<div class="col-md-5">
			
															<div class="form-group">
																<label class="control-label col-md-3">สิทธิ์ : </label>
																<div class="col-md-9">
																	<?= HTML::dropDownList('type',$model['type'], [''=> '---- เลือกตำแหน่งผู้ใช้ ----'] + User::$arrPosition, ['id'=>'type','class' => 'form-control input-large','disable'=>!\Yii::$app->user->can('billing.role.admin')])?>	
																</div>
															</div>
																										
															<div class="form-group">
																<label class="control-label col-md-3"><span class="required"> * </span>UserName:</label>
																<div class="col-md-9">
																	<?= Html::activeInput('text', $model, 'username', ['id'=>'username','class' => 'form-control', 'placeholder' => 'Username...','readonly'=>true])?>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Password:</label>
																<div class="col-md-9">
																	<?= Html::passwordInput('password', '', ['id'=>'password','class'=>'form-control', 'placeholder'=>'กรุณากรอกรหัสผ่านใหม่'])?>
																	</div>
															</div>
															
															<div class="form-group">
																<label class="control-label col-md-3">Verify Password:</label>
																<div class="col-md-9">
																	<?= Html::passwordInput('confirmPassword', '', ['id'=>'confirmPassword','class'=>'form-control', 'placeholder'=>'กรุณากรอกรหัสผ่านใหม่อีกครั้ง'])?> 
																	</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																<p class="ect"></p>
																	</div>
															</div>
															
														</div>
								</div>
			
							</div>
								
							<div class="row">
								<div class="col-md-12">
									<div class="form-actions fluid">
										<div class="text-center">
											  <?= Button::widget([
															    'label' => 'บันทึก',
															    'options' => [
															    'class' => 'btn green',
															    'type' => 'submit'
																],
															]);?>
												<?= Button::widget([
																'id' => 'btnCancel',
															    'label' => 'ยกเลิก',
																'options' => [
																'type' => 'button',
																],
												]);?>	
										</div>
									</div>
								</div>
							</div>
						</div>
		</div>
	</div>
</div>
<?php ActiveForm::end() ?>