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

                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN PORTLET-->
                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i>บันทึกการให้อาหารกุ้ง </div>
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
                                                <label class="control-label col-md-3" for="pondId">เลือกบ่อ</label>
                                                <div class="col-md-4">
                                                    <div class="input-group input-large" id="defaultrange">
                                                        <?php // echo Html::dropDownList('pondId', ' ', $arrTypelist , ['id'=>'pondId','class' => 'select2 form-control']) ?>	
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label class="control-label col-md-3">วันที่</label>
                                                      <div class="input-group input-large date date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
                                                        <input type="text" class="form-control" readonly>
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                  	</div>
                                            </div>
                                               <div class="form-group">
                                                <label class="control-label col-md-3">มื้อที่</label>
                                                      <div class="input-group input-large ">
                                                        <input type="text" class="form-control" /> 
                                                  	</div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="control-label col-md-3">อายุลูกกุ้ง</label>
                                                      <div class="input-group input-large " >
                                                        <input type="text" class="form-control" /> 
                                                  	</div>
                                            </div>
                                            
                                             <div class="form-group">
                                                <label class="control-label col-md-3">เบอร์อาหาร</label>
                                                      <div class="input-group input-large " >
                                                       <input type="text" class="form-control" /> 
                                                  	</div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="control-label col-md-3">จำนวนที่ใช้</label>
                                                      <div class="input-group input-large" >
                                                        <input type="text" class="form-control" /> 
                                                  	</div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn red">
                                                        <i class="fa fa-check"></i> Submit</button>
                                                    <a href="<?php echo Url::toRoute('content/typelist') ?>" class="btn default" >ยกเลิก </a>
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
 