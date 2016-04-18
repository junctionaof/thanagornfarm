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
use common\models\Food;
use common\models\Typelist;

$baseUrl = \Yii::getAlias('@web');
$cancelUrl = Url::toRoute('faq/list');
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

if(!isset($model->foodTime)){
	$correntDate =  date( "Y-m-d H:i:s",strtotime("now"));
}else {
	$correntDate = $model->foodTime;
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
		 	}).fail(function() {
		    	console.log('error loading');
		  	});
	});

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
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/ckeditor/ckeditor.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-markdown/lib/markdown.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile($baseUrl  . '/assets/pages/scripts/form-validation.min.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile($baseUrl  . '/assets/pages/scripts/components-date-time-pickers.min.js', ['position' => \yii\web\View::POS_END]);
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
                                        <i class="fa fa-gift"></i><?php echo $status; ?>การให้อาหารกุ้ง </div>
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
                                                        <?php echo Html::dropDownList('pondId', $model->pondId, $arrTypelist , ['id'=>'pondId','class' => 'select2 pull-right form-control'])?>	
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                            	<label class="control-label col-md-3">ข้อมูลบ่อ และรุ่น </label>
                                                      <div class="input-group input-large " >
                                                      <?= Html::input('text','pond', $model->name,['id'=>'pond','class' => 'form-control', 'disabled' => 'true']);?>
                                                  	</div>
                                            </div>
                                            
                                            <div class="form-group">
                                           		<label class="control-label col-md-3">อายุลูกกุ้ง</label>
                                                      <div class="input-group input-large " >
                                                       <?= Html::input('text', 'age', $model->age,['id'=>'age','class' => 'form-control','disabled'=>'true']);?>
                                                  	</div>
                                            </div>
                                            
                                             <div class="form-group">
                                                <label class="control-label col-md-3">วันที่ให้อาหาร</label>
                                                      <div class="input-group input-large">
                                                       <?= Html::input('text', 'foodTime' ,$correntDate ,['id'=>'foodTime','class' => 'form-control form-control-inline input-medium date-picker']);?>
                                                  	</div>
                                            </div>
                                               <div class="form-group">
                                                <label class="control-label col-md-3">มื้อที่</label>
                                                      <div class="input-group input-large ">
                                                        <?php echo Html::dropDownList('foodNo',$model->foodNo, Food::$arrMeal , ['id'=>'foodNo','class' => 'select2 form-control'])?>	
                                                  	</div>
                                            </div>
                                            

                                            
                                             <div class="form-group">
                                                <label class="control-label col-md-3">เบอร์อาหาร</label>
                                                      <div class="input-group input-large " >
                                                      <?php echo Html::dropDownList('numberOf',$model->numberOf, Food::$arrNumberOf , ['id'=>'numberOf','class' => 'select2 form-control'])?>	

                                                  	</div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="control-label col-md-3">ปริมาณอาหารที่ให้</label>
                                                      <div class="input-group input-large" >
                                                       <?= Html::input('text', 'foodNum', $model->foodNum,['id'=>'foodNum','class' => 'form-control']);?>
                                                  	   <span class="error" style="color: Red; display: none">*เฉพาะตัวเลขเท่านั้น</span>
													<script type="text/javascript">
													        var specialKeys = new Array();
													        specialKeys.push(8);   //Backspace
													        $(function () {
													            $('input[name="foodNum"]').bind("keypress", function (e) {
													                var keyCode = e.which ? e.which : e.keyCode
													                var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
													                $(".error").css("display", ret ? "none" : "inline");
													                return ret;
													            });
													            $(".numeric").bind("paste", function (e) {
													                return false;
													            });
													            $(".numeric").bind("drop", function (e) {
													                return false;
													            });
													        });
													    </script>
                                                  	<span class="input-group-addon">
                                                        <i class="fa"> กิโลกรัม</i>
                                                    </span>
                                                  	</div>
                                                  	
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn red">
                                                        <i class="fa fa-check"></i> Submit</button>
                                                    <a href="<?php echo Url::toRoute('pond/food') ?>" class="btn default" >ยกเลิก </a>
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