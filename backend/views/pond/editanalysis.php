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
use common\models\analysis;
use common\models\Typelist;

$baseUrl = \Yii::getAlias('@web');
$cancelUrl = Url::toRoute('faq/list');
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

if($model->analysisTime == ''){
	$correntDate =  date( "Y-m-d H:i:s",strtotime("now"));
}else {
	$correntDate = $model->analysisTime;
}

$contentDate = "";
$contentTime = "";

$str = <<<EOT

$(document).ready(function() {
	$('#pondId').on('change', function() {
  		// Do someting
		var id = $(this).val();
		var jqxhr = $.get( "finpond", {id:id}, function() {
		  		console.log('success loading');
			}).done(function(data) {
			var json = $.parseJSON(data);
			$('#pond').val(json.pond);
		    $('#age').val(json.age);
		 	$('#larvae').val(json.larvae);
			$('#larvaePrice').val(json.larvaePrice);
			$('#foods').val(json.foods);
			$('#typesize').val(json.typesize);
		
		 	}).fail(function() {
		    	console.log('error loading');
		  	});
	});
		
		$("#checkSurvivalRate").click(function()
			{
				  	$('input[name=survivalRate]').val(calculateSurvivalRate());
			});	
		
		$("#checkQuantity").click(function()
			{
				  	$('input[name=quantity]').val(calculateQuantity());
			});
		
		$("#checkFcr").click(function()
			{
				  	$('input[name=fcr]').val(calculateFcr());
			});	
		
		$("#checkReceipts").click(function()
			{
				  	$('input[name=receipts]').val(calculateReceipts());
			});	
		
		$("#checkCostShrimp").click(function()
			{
				  	$('input[name=costShrimp]').val(calculateCostShrimp());
			});	
		
		$("#checkProfits").click(function()
			{
				  	$('input[name=profits]').val(calculateProfits());
			});	
		
		
		$("#checkYields").click(function()
			{
				  	$('input[name=yields]').val(calculateYields());
			});	
		
 		function calculateSurvivalRate() 
		 {
			
				var larvae = $('input[name=larvae]').val();
				var size = $('input[name=size]').val();
				var results =$('input[name=results]').val();
				var total = (((results * 1000)/size)/larvae)*100
				return total;
		
		}	
		
		function calculateQuantity() 
		 {
				var foods;
				foods = $('input[name=foods]').val();
				return foods;
		
		}	
		
		function calculateFcr() 
		 {
				var results;
				var quantity;

				quantity = $('input[name=quantity]').val();
				results =$('input[name=results]').val();
				total = quantity/results;
				return total ;
		
		}	
		
		function calculateReceipts() 
		 {
				var results;
				var price;
				var total;
		
				price = $('input[name=price]').val();
				results =$('input[name=results]').val();
				total = price*results;
				return total ;
		
		}	
		
		function calculateCostShrimp() 
		 {
				var larvaePrice;
				var larvae;
				var total
		
				larvae = $('input[name=larvae]').val();
				larvaePrice = $('input[name=larvaePrice]').val();
				
				total = (larvae * larvaePrice)/100;
		
				return total;
		
		}	
		
		 function calculateProfits() 
		 {
				var receipts = $('input[name=receipts]').val();
				var costShrimp = $('input[name=costShrimp]').val();
				var costFood =$('input[name=costFood]').val();
				var costWage =$('input[name=costWage]').val();
				var costEnergy =$('input[name=costEnergy]').val();
				var costOther =$('input[name=costOther]').val();
				var totala =  Number(costShrimp) + Number(costFood) + Number(costWage) + Number(costEnergy) + Number(costOther);
				var total = receipts - totala ;
				return total;
		
		}	
		
		function calculateYields() 
		 {
				var profits = $('input[name=profits]').val();
				var typesize = $('input[name=typesize]').val();
				total = profits/typesize;
				return total;
		
		}	
		
		

});


		
		

		


EOT;
$this->registerJs($str);
$this->registerCssFile($baseUrl  . '/assets/global/plugins/select2/css/select2.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/select2/css/select2-bootstrap.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css',['position' => \yii\web\View::POS_HEAD]) ;

$this->registerCssFile($baseUrl  . '/assets/global/css/components-md.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/css/plugins-md.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/select2/css/select2.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/select2/css/select2-bootstrap.min.css',['position' => \yii\web\View::POS_HEAD]) ;

$this->registerJsFile($baseUrl  . '/assets/global/plugins/select2/js/select2.full.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/jquery-validation/js/jquery.validate.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/jquery-validation/js/additional-methods.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/ckeditor/ckeditor.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-markdown/lib/markdown.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile($baseUrl  . '/assets/pages/scripts/form-validation.min.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile($baseUrl  . '/assets/global/plugins/select2/js/select2.full.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/pages/scripts/components-select2.min.js', ['position' => \yii\web\View::POS_END]);

?>
<?php echo UiMessage::widget(); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i><?php echo $status; ?>วิเคราะห์ผลการเลี้ยง </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse"> </a>
                                        <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                        <a href="javascript:;" class="reload"> </a>
                                        <a href="javascript:;" class="remove"> </a>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <!-- BEGIN FORM-->
                                    <?php echo Html::beginForm('', 'post', array('class' => 'form-horizontal form-bordered')) ?>
                                        <div class="form-body">
                                        	
                                            <div class="form-group">
                                                <label class="control-label col-md-3" for="pondId">เลือกบ่อและรุ่น</label>
                                                <div class="col-md-4">
                                                    <div class="input-group input-large" id="defaultrange">
                                                        <?php echo Html::dropDownList('pondId',  $model->pondId, $arrTypelist , ['id'=>'pondId','class' => 'select2 form-control'])?>	
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                            	<label class="control-label col-md-3">ข้อมูลบ่อ และรุ่น </label>
                                                      <div class="input-group input-large " >
                                                      <?= Html::input('text','name', $model->name,['id'=>'pond','class' => 'form-control']);?>
                                                  	</div>
                                            </div>
                                            
                                            <div class="form-group">
                                           		<label class="control-label col-md-3">อายุลูกกุ้ง</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'age', $model->age,['id'=>'age','class' => 'form-control']);?>
                                                       <?= Html::input('hidden', 'larvae','',['id'=>'larvae','class' => 'form-control' ,'value' => '6000']);?>
                                                  	</div>
                                            </div>
                                                                                        
                                             <div class="form-group">
                                                <label class="control-label col-md-3">วันที่จับกุ้ง</label>
                                                      <div class="input-group input-large" data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
                                                       <?= Html::input('text', 'pickDate' ,$model->pickDate ,['id'=>'analysisTime','class' => 'form-control']);?>
                                                  	</div>
                                            </div>
                                                                                        
                                        
                                            <div class="form-group">
                                                <label class="control-label col-md-3">ผลผลิตที่ได้</label>
                                                      <div class="input-group input-large ">
                                                        <?= Html::input('text', 'results', $model->results,['id'=>'results','class' => 'form-control']);?>
                                                  	<span class="input-group-addon">
                                                        <i class="fa">กิโลกรัม</i>
                                                    </span>
                                                  </div>	
                                            </div>
                                              <div class="form-group">
                                                <label class="control-label col-md-3">ขนาดกุ้งที่จับ</label>
                                                      <div class="input-group input-large ">
                                                        <?= Html::input('text', 'size', $model->size,['id'=>'size','class' => 'form-control']);?>
                                                  	<span class="input-group-addon">
                                                        <i class="fa">ตัว / กิโลกรัม</i>
                                                    </span>
                                                  </div>	
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">ราคาที่ขาย</label>
                                                      <div class="input-group input-large ">
                                                        <?= Html::input('text', 'price', $model->price,['id'=>'price','class' => 'form-control']);?>
                                                  	<span class="input-group-addon">
                                                        <i class="fa">บาท / กิโลกรัม</i>
                                                    </span>
                                                  </div>	
                                            </div>
                                              <div class="form-group">
                                                <label class="control-label col-md-3">ความหนาแน่นตัว/ตร.ม.</label>
                                                      <div class="input-group input-large ">
                                                        <?= Html::input('text', 'density', $model->density,['id'=>'density','class' => 'form-control']);?>
                                                  	<span class="input-group-addon">
                                                        <i class="fa">ตัว / ตารางเมตร</i>
                                                    </span>
                                                  </div>	
                                            </div>
                                              <div class="form-group">
                                                <label class="control-label col-md-3">อัตรารอด</label>
                                                      <div class="input-group input-large ">
                                                        <?= Html::input('text', 'survivalRate', $model->survivalRate,['id'=>'survivalRate','class' => 'form-control','readonly'=>'readonly']);?>
                                                  	<span class="input-group-addon">
                                                        <i class="fa"> % </i>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <button id="checkSurvivalRate" class="btn btn-success" type="button">
                                                        <i class="fa fa-arrow-left fa-fw"></i> คำนวณ</button>
                                                    </span>
                                                  </div>
                                                    </div>
                                              <div class="form-group">
                                                <label class="control-label col-md-3">ปริมาณอาหารที่ใช้รวม</label>
                                                      <div class="input-group input-large ">
                                                        <?= Html::input('text', 'quantity', $model->quantity,['id'=>'quantity','class' => 'form-control','readonly'=>'readonly']);?>
                                                  		<?= Html::input('hidden', 'foods','',['id'=>'foods','class' => 'form-control' ,'value' => '0']);?>
                                                  		<?= Html::input('hidden', 'typesize','',['id'=>'typesize','class' => 'form-control' ,'value' => '0']);?>
                                                  	<span class="input-group-addon">
                                                        <i class="fa">กิโลกรัม</i>
                                                    </span>
                                                     <span class="input-group-btn">
                                                        <button id="checkQuantity" class="btn btn-success" type="button">
                                                        <i class="fa fa-arrow-left fa-fw"></i> คำนวณ</button>
                                                    </span>
                                                  </div>	
                                            </div>
                                            
                                          <div class="form-group">
                                           		<label class="control-label col-md-3">อัตราแลกเนื้อ (FCR)</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'fcr', $model->fcr,['id'=>'fcr','class' => 'form-control','readonly'=>'readonly']);?>
                                                  	  <span class="input-group-btn">
                                                        <button id="checkFcr" class="btn btn-success" type="button">
                                                        <i class="fa fa-arrow-left fa-fw"></i> คำนวณ</button>
                                                    </span>
                                                  	</div>
                                            </div>
                                            
                                            <div class="form-group">
                                           		<label class="control-label col-md-3">รายรับ</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'receipts', $model->receipts,['id'=>'receipts','class' => 'form-control','readonly'=>'readonly']);?>
                                                  	<span class="input-group-addon">
                                                        <i class="fa">บาท</i>
                                                    </span>
                                                  	 <span class="input-group-btn">
                                                        <button id="checkReceipts" class="btn btn-success" type="button">
                                                        <i class="fa fa-arrow-left fa-fw"></i> คำนวณ</button>
                                                    </span>
                                                  	</div>
                                                  	
                                            </div>
                                    <div class="note note-info">
                                        <strong>วิเคราะห์ต้นทุนการผลิต </strong>
                                    </div>
                                      <div class="form-group">
                                           		<label class="control-label col-md-3">ต้นทุนลูกกุ้ง</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'costShrimp', $model->costShrimp,['id'=>'costShrimp','class' => 'form-control','readonly'=>'readonly']);?>
                                                  	<?= Html::input('hidden', 'larvaePrice','',['id'=>'larvaePrice','class' => 'form-control' ,'value' => '0']);?>
                                                  	<span class="input-group-btn">
                                                        <button id="checkCostShrimp" class="btn btn-success" type="button">
                                                        <i class="fa fa-arrow-left fa-fw"></i> คำนวณ</button>
                                                    </span>
                                                  	</div>
                                            </div>
                                              <div class="form-group">
                                           		<label class="control-label col-md-3">ต้นทุนอาหาร</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'costFood', $model->costFood,['id'=>'costFood','class' => 'form-control']);?>
                                                  	</div>
                                            </div>
                                              <div class="form-group">
                                           		<label class="control-label col-md-3">ต้นทุนค่าจ้างพนักงาน</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'costWage', $model->costWage,['id'=>'costWage','class' => 'form-control']);?>
                                                  	</div>
                                            </div>
                                              <div class="form-group">
                                           		<label class="control-label col-md-3">ต้นทุนค่าพลังงาน</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'costEnergy', $model->costEnergy,['id'=>'costEnergy','class' => 'form-control']);?>
                                                  	</div>
                                            </div>
                                            <div class="form-group">
                                           		<label class="control-label col-md-3">ต้นทุนอื่นๆ</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'costOther', $model->costOther,['id'=>'costOther','class' => 'form-control']);?>
                                                  	</div>
                                            </div>
                                            <div class="form-group">
                                           		<label class="control-label col-md-3">กำไรขั้นต้น</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'profits', $model->profits,['id'=>'profits','class' => 'form-control','readonly'=>'readonly']);?>
                                                  	<span class="input-group-btn">
                                                        <button id="checkProfits" class="btn btn-success" type="button">
                                                        <i class="fa fa-arrow-left fa-fw"></i> คำนวณ</button>
                                                    </span>
                                                  	</div>
                                            </div>
                                            <div class="form-group">
                                           		<label class="control-label col-md-3">ผลผลิตต่อไร่</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'yields', $model->yields,['id'=>'yields','class' => 'form-control','readonly'=>'readonly']);?>
                                                  	<span class="input-group-btn">
                                                        <button id="checkYields" class="btn btn-success" type="button">
                                                        <i class="fa fa-arrow-left fa-fw"></i> คำนวณ</button>
                                                    </span>
                                                  	</div>
                                            </div>
                                            <div class="form-group">
                                           		<label class="control-label col-md-3">ข้อเสนอแนะ</label>
                                                      <div class="input-group input-large " >
                                                        <textarea name = "suggestion" id="suggestion" class="form-control" rows="3" placeholder=""></textarea>
                                                  	</div>
                                            </div>
                                          
                                            
                                            
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn red">
                                                        <i class="fa fa-check"></i> Submit</button>
                                                    <a href="<?php echo Url::toRoute('pond/analysis') ?>" class="btn default" >ยกเลิก </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php if ($model->id) {echo Html::hiddenInput('id', $model->id);}?>
                                    <?php echo Html::endForm() ?>  
                                    <!-- END FORM-->
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>