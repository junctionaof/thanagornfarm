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

$contentDate = "";
$contentTime = "";

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
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile($baseUrl  . '/assets/pages/scripts/form-validation.min.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile($baseUrl  . '/assets/global/plugins/select2/js/select2.full.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/pages/scripts/components-select2.min.js', ['position' => \yii\web\View::POS_END]);

?>
<?php echo UiMessage::widget(); ?>
 
			   <div class="portlet light portlet-fit portlet-form bordered" id="form_wizard_1">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class=" icon-layers font-green"></i>
                                        <span class="caption-subject font-green sbold uppercase">บันทึกการให้อาหารกุ้ง </span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <!-- BEGIN FORM-->
                                    <?php echo Html::beginForm('', 'post', array('class' => 'form-horizontal')) ?>
                                        <div class="form-body">
                                            <h4 class="form-section" style=" margin-top: 0px;">ข้อมูลบ่อ และรุ่น</h4>
                                            <div class="form-group has-warning">
                                                <label class="control-label col-md-3" for="inputWarning">เลือกบ่อ</label>
                                                <div class="col-md-4">
                                                	<?php echo Html::dropDownList('type', ' ', $arrTypelist , ['id'=>'type','class' => 'select2 form-control'])?>	
                                                    <span class="help-block"> ระบบจะโชว บ่อ เป็นตัวเลข และรุ่นทั้งหมด ในบ่อ  </span>
                                                </div>
                                            </div>
                                            <h4 class="form-section">รายละเอียดการให้อาหาร</h4>
                                            <div class="row">
                                            <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">วันที่</label>
                                                <div class="col-md-7">
                                                    <div class="date-picker" data-date-format="mm/dd/yyyy"> </div>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="col-md-7">
                                            <div class="form-group has-warning">
                                                <label class="control-label col-md-3">มื้อที่</label>
                                                <div class="col-md-7">
                                                    <div class="input-icon right">
                                                        <i class="fa fa-exclamation tooltips" data-original-title="please write a valid email"></i>
                                                        <input type="text" class="form-control" /> </div>
                                                </div>
                                            </div>
                                            <div class="form-group has-error">
                                                <label class="control-label col-md-3">อายุลูกกุ้ง</label>
                                                <div class="col-md-7">
                                                    <div class="input-icon right">
                                                        <i class="fa fa-warning tooltips" data-original-title="please write a valid email"></i>
                                                        <?php // = Html::activeInput('text', $model, 'name', ['class' => 'form-control','id'=>'inputWarning','placeholder' => 'กรุณาระบุ ชื่อบ่อ'])?>
                                                        <input type="text" class="form-control" /> </div>
                                                </div>
                                            </div>
                                            <div class="form-group has-error">
                                                <label class="control-label col-md-3">เบอร์อาหาร</label>
                                                <div class="col-md-7">
                                                    <div class="input-icon right">
                                                        <i class="fa fa-warning tooltips" data-original-title="please write a valid email"></i>
                                                        <input type="text" class="form-control" /> </div>
                                                </div>
                                            </div>
                                            <div class="form-group has-error">
                                                <label class="control-label col-md-3">จำนวนที่ใช้</label>
                                                <div class="col-md-7">
                                                    <div class="input-icon right">
                                                        <i class="fa fa-warning tooltips" data-original-title="please write a valid email"></i>
                                                        <input type="text" class="form-control" /> </div>
                                                </div>
                                            </div>
                                            </div>
  											</div>

                                        </div>
                                        <?php if ($model->id) {echo Html::hiddenInput('id', $model->id);}?>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn green">Submit</button>
                                                    <a href="<?php echo Url::toRoute('content/typelist') ?>" class="btn default" >ยกเลิก </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo Html::endForm() ?>
                                    <!-- END FORM-->
                                </div>
                            </div>
                            <!-- END VALIDATION STATES-->

 