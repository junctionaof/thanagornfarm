<?php

use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use app\CategoryTree;
use app\DateUtil;
use app\Workflow;
use backend\components\UserMessage;
use backend\components\UiMessage;
use backend\components\Portlet;
use common\models\FAQ;
use backend\components\TagCloud;
use common\models\Typelist;

$baseUrl = \Yii::getAlias('@web');
$cancelUrl = Url::toRoute('faq/list');

$contentDate = "";
$contentTime = "";


?>
<?php echo UiMessage::widget(); ?>
<div class="col-md-12">
	<?php
	Portlet::begin(['title' => 'ข้อมูล บ่อเลี้ยงกุ้ง',
	    'themeClass' => ' box grey']);
	?>

		
		<div class="tab-content">

		<?php echo Html::beginForm('', 'post', array('class' => 'form-horizontal')) ?>
		<div class="row margin-top-20">
		
			<div class="col-md-7 col-md-offset-1">
			
				<div class="form-group">
					<label class="control-label col-md-3">ชื่อบ่อ<span class="required">*</span></label>
					<div class="col-md-9">
						<?= Html::activeInput('text', $model, 'name', ['class' => 'form-control', 'placeholder' => 'กรุณาระบุ ชื่อบ่อ'])?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-md-3">ขนาดบ่อ<span class="required">*</span></label>
					<div class="col-md-9">
						<?= Html::activeInput('text', $model, 'size', ['class' => 'form-control', 'placeholder' => 'กรุณาระบุ  ขนาดของบ่อ'])?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-md-3"></label>
					<div class="col-md-9">
						<?php 
							if ($model->id) {
								echo Html::hiddenInput('id', $model->id);
							}
						?>
						
						<button type="submit" class="btn btn-primary">บันทึก</button>
						<a href="<?php echo Url::toRoute('content/typelist') ?>" class="btn" >ยกเลิก </a>
					</div>
				</div>
				
			</div>
		
			
			
		</div>
		<?php echo Html::endForm() ?>


		
		</div>

	<?php Portlet::end(); ?>
</div>
 