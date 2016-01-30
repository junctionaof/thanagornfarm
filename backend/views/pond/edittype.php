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
                            <div class="portlet box blue">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i>บันทึกบ่อเลี้ยงกุ้ง </div>
                                </div>
                                <div class="portlet-body form">
                                    <!-- BEGIN FORM-->
                                   <?php echo Html::beginForm('', 'post', array('class' => 'form-horizontal form-bordered')) ?>
										<div class="form-body">
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
													<label class="control-label col-md-3">ผู้ดูแลบ่อ</label>
													<div class="col-md-9">
													<?php echo Html::dropDownList('user[]',unserialize($model->keeper),$arrUser , ['id'=>'type','class' => 'form-control select2-multiple','multiple'=>''])?>	
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
													<a href="<?php echo Url::toRoute('pond/typelist') ?>" class="btn" >ยกเลิก </a>
												</div>
										</div>	
									</div>
									<?php echo Html::endForm() ?>
                                    <!-- END FORM-->
                                </div>
                            </div>
                            <!-- END PORTLET-->
                        </div>
                    </div>
 