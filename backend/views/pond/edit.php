<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;

use common\models\pond;
use common\models\ObjectCategory;

use app\DateUtil;
use app\Workflow;
use app\CategoryTree;
use backend\components\TinyMCE;
use backend\components\TagCloud;
use backend\components\PublishTab;
use backend\components\NewsLog;
use backend\components\Portlet;
use backend\components\RelatedList;
use backend\components\OtherHighlight;
use backend\components\MediaListTab;
use app\Entity;
use backend\components\DocumentListTab;
use common\models\Typelist;


$pondDate = "";
$pondTime = "";
?>

<?php 
$identity = \Yii::$app->user->getIdentity();
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;

if($pond->releaseTime == ''){
	$correntDate =  date( "Y-m-d H:i:s",strtotime("now"));
}else {
	$correntDate = $pond->releaseTime;
}

//register Css
$this->registerCssFile($baseUrl  . '/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',['position' => \yii\web\View::POS_HEAD]) ;
$this->registerCssFile($baseUrl  . '/assets/global/plugins/clockface/css/clockface.css',['position' => \yii\web\View::POS_HEAD]) ;

//Register Js
$this->registerJsFile($baseUrl  . '/assets/global/plugins/ckeditor/ckeditor.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/moment.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/global/plugins/clockface/js/clockface.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl  . '/assets/pages/scripts/components-date-time-pickers.min.js', ['position' => \yii\web\View::POS_END]);

?>
<div class="col-md-12">
    <div id="content-main" class="portlet box blue" data-entity="<?php // echo Entity::TYPE_CONTENT?>">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>ระบบบันทึกข้อมูลการเลี้ยงกุ้ง
            </div>
            <div class="actions">
               <a href="<?php echo Url::toRoute('pond/list') ?>" class="btn red" > <i class="fa fa-toggle-left"></i> กลับสู่หน้า ข้อมูลทั้งหมด </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="tabbable tabbable-tabdrop">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab11">
				<?php $form = ActiveForm::begin ();?>
				<?php echo Html::hiddenInput('id', $pond->id, array( 'id' => 'id', 'class' => 'form-control select2')) ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="portlet ">
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="form-group">
                                                	<label class="control-label">กรอกชื่อรุ่น</label>
                                                	<?php echo Html::textInput('title', $pond->title, array('class'=>'form-control','title'=>'ชื่อรุ่น ..','placeholder'=>'กรอกชื่อรุ่น..'))?>
                                                </div>
                                                <div class="form-group">
													<label class="control-label">เลือกบ่อ</label>
													<?php echo Html::dropDownList('type', ' ', $arrTypelist , ['id'=>'type','class' => 'form-control input-medium'])?>	
												</div>
                                                <div class="form-group">
                                                    <label class="control-label">คำอธิบาย ข้อมูลอื่นๆ ของรุ่นนี้</label>
                                                    <?php echo Html::textarea('description', $pond->pond, array('class'=>'ckeditor form-control','rows'=>'20','placeholder'=>'กรอกชื่อรุ่น..'))?>
                                                </div>                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="portlet box blue-hoki">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-gift"></i>ข้อมูลลูกกุ้ง
                                            </div>
                                            
                                            <div class="actions">					
													<div class="btn-group pull-right">
														<a class="btn <?php // echo \Yii::$app->params['uiPortletHighlight'] ?>" href="http://news.tpbs.ndev.pw/pond/preview?id=<?php // echo "{$pond->id}&key=$previewKey" ?>" target="_blank"> 
															<i class="fa fa-search"></i> Preview
														</a>

													</div>
												</div>
                                            
                                        </div>
                                        <div class="portlet-body form">

                                            <div class="form-body">
                                                <h3 class="form-section">ลูกกุ้งที่ปล่อยลงในบ่อ</h3>
                                                <div class="form-group">
                                                    <label class="control-label">จำนวนลูกกุ้ง</label>
                                                   <?php echo Html::textInput('larvae', $pond->larvae,  array('id'=>'larvae','class' => 'form-control','placeholder'=>'ระบุจำนวน..'))?>	
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">ชนิดของลูกกุ้ง</label>
                                                   <?php echo Html::dropDownList('larvaeType', $pond->larvaeType, pond::$larvaeType , ['id'=>'larvaeType','class' => 'form-control input-smail'])?>	
                                                </div>
                                                <div class="form-group">
                                                	<label class="control-label">ราคาของลูกกุ้ง</label>
                                                    <?php echo Html::textInput('larvaePrice', $pond->larvaePrice, array('class'=>'form-control','title'=>'ราคา ..','placeholder'=>'กรอกราคา..'))?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">บริษัท / ฟาร์ม ที่รับมา</label>
                                                      <?php echo Html::textInput('larvaeCompany', $pond->larvaeCompany, array('class'=>'form-control','title'=>'ราคา ..','placeholder'=>'กรอกชื่อบริษัท..'))?>
                                                </div>
                                            </div>
                                            <div class="form-body">
                                                <h3 class="form-section">วันที่ ปล่อยลงบ่อ</h3>
                                                <div class="form-group">
                                               		<p class="news-calendar">
														<i class="fa fa-calendar"></i> วันที่ และเวลาปล่อยลูกกุ้ง
													</p>
												<div class="input-group date form_meridian_datetime input-large" data-date="">
                                                  <?php echo Html::textInput('releaseTime', $correntDate, array('class'=>'form-control','title'=>'วันที่ปล่อย ..','placeholder'=>'ระบุวันที่..'))?>
                                                      <span class="input-group-btn">
                                                        <button class="btn default date-reset" type="button">
                                                           <i class="fa fa-times"></i>
                                                           </button>
                                                           <button class="btn default date-set" type="button">
                                                           <i class="fa fa-calendar"></i>
                                                        </button>
                                                      </span>
                                                  </div>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="portlet box yellow">
                                        <div class="portlet-body form">
                                            <div class="form-actions top">
                                                <div class="btn-set pull-right">
                                                    <button type="submit" class="btn green">บันทึก</button>
                                                    <a href="<?php echo Url::toRoute('pond/list') ?>" class="btn" >ยกเลิก </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end ข้อมูลข่าว -->
					<?php ActiveForm::end() ?>
                    </div>
                    <div class="tab-pane" id="tab12">
                    002
                    </div>
                    <div class="tab-pane" id="tab13">
					003
                    </div>
                    
                    <div class="tab-pane" id="tab14">
					004
                    </div>
                    <div class="tab-pane" id="tab15">

                    </div>
                    <div class="tab-pane" id="tab16">
                    
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
