<?php 
use yii\helpers\Url;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\Button;
use yii\bootstrap\ActiveForm;


use common\models\User;
use common\models\AuthItem;

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

EOT;
$this->registerJs($str);
?>
<style>

</style>

<?php $form = ActiveForm::begin([
				'layout' => 'horizontal',
						]);
				?>

<div class="portlet box grey tabbable">
	<div class="portlet-title">
		<div class="caption"><i class="fa fa-reorder"></i>เพิ่มข้อมูลผู้ใช้งาน</div>
	</div>
	<div class="portlet-body">
		<div class="tabbable portlet-tabs">
			<ul class="nav nav-tabs">
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
													<label class="control-label col-md-3"><span class="required"> * </span>อีเมล์ : </label>
													<div class="col-md-9">
														<?= Html::activeInput('text', $model, 'email', ['id'=>'email','class' => 'form-control', 'required'=>'required', 'placeholder' => 'กรอกอีเมลผู้ใช้งาน..'])?>
													</div>
												</div>
												
												
					
											</div>
											<div class="col-md-5">

												<div class="form-group">
													<label class="control-label col-md-3"><span class="required"> * </span>ตำแหน่ง : </label>
													<div class="col-md-9">
														<?= HTML::dropDownList('position',$model['position'], [''=> '---- เลือกตำแหน่งผู้ใช้ ----'] + User::$arrPosition, ['id'=>'position','class' => 'form-control input-large','required'=>'required','disable'=>!\Yii::$app->user->can('billing.role.admin')])?>	
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-md-3"><span class="required"> * </span>สังกัด : </label>
													<div class="col-md-9">
														<?= HTML::dropDownList('type',$model['type'], [''=> '---- เลือกสังกัด ----'] + User::$arrType, ['id'=>'type','class' => 'form-control input-large', 'required'=>'required'])?>	
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
								<button type="submit" class="btn green">ตกลง</button> 
								<button type="button" class="btn" id="btnCancel">ยกเลิก</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end() ?>