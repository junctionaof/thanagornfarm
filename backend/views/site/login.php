<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
?>

<h3 class="form-title">กรอกข้อมูลผู้ใช้ เพื่อเข้าสู่ระบบ</h3>
<div class="alert alert-danger display-hide">
	<button class="close" data-close="alert"></button>
	<span>Enter any username and password.</span>
</div>
<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
<div class="form-group">
	<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
	<label class="control-label visible-ie8 visible-ie9">Username</label>
	<div class="input-icon">
		<i class="fa fa-user"></i> <input
			class="form-control placeholder-no-fix" type="text"
			autocomplete="off" placeholder="Username" name="LoginForm[username]" />
	</div>
</div>
<div class="form-group">
	<label class="control-label visible-ie8 visible-ie9">Password</label>
	<div class="input-icon">
		<i class="fa fa-lock"></i> <input
			class="form-control placeholder-no-fix" type="password"
			autocomplete="off" placeholder="รหัสผ่าน" name="LoginForm[password]" />
	</div>
</div>
<div class="form-actions">
	<label class="checkbox"> <input type="checkbox" name="remember"
		value="1" /> จดจำฉัน
	</label>
	<button type="submit" class="btn green pull-right">
		เข้าสู่ระบบ <i class="m-icon-swapright m-icon-white"></i>
	</button>
</div>
<?php ActiveForm::end(); ?>