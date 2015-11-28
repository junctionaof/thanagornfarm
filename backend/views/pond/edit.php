<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;

use common\models\Content;
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


$contentDate = "";
$contentTime = "";
/* $ts = DateUtil::ParseSQLDate($Content->publishTime);
if ($ts) {
	$contentDate = date('Y-m-d', $ts);
	$contentTime = date('H:i', $ts);
	if (substr($contentTime, -3) == ':00')
		$contentTime = substr($contentTime, 0, -3);
}


$ts = DateUtil::ParseSQLDate($Content->publishTime);
if ($ts) {
	$contentDate = date('Y-m-d', $ts);
	$contentTime = date('H:i', $ts);
	if (substr($contentTime, -3) == ':00')
		$contentTime = substr($contentTime, 0, -3);
}

// expire Time
$expireDate = null;
$expireTime = null;
$ts = DateUtil::ParseSQLDate($Content->expireTime);
if ($ts) {
	$expireDate = date('Y-m-d', $ts);
	$expireTime = date('H:i', $ts);
	
} */

?>

<?php 
$identity = \Yii::$app->user->getIdentity();
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;
$this->registerJsFile($baseUrl  . '/assets/scripts/relatedContent.js', ['position' => \yii\web\View::POS_END]);

?>
<div class="col-md-12">
    <div id="content-main" class="portlet box blue" data-entity="<?php // echo Entity::TYPE_CONTENT?>">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>ระบบบันทึกข้อมูลการเลี้ยงกุ้ง
            </div>
            <div class="actions">
               <a href="<?php echo Url::toRoute('content/list') ?>" class="btn red" > <i class="fa fa-toggle-left"></i> กลับสู่หน้า ข้อมูลทั้งหมด </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="tabbable tabbable-tabdrop">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab11">
				<?php $form = ActiveForm::begin ();?>
				<?php echo Html::hiddenInput('id', $Content->id, array( 'id' => 'id', 'class' => 'form-control select2')) ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="portlet ">
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="form-group">
                                                	<label class="control-label">กรอกชื่อรุ่น</label>
                                                    <?php echo Html::activeInput('text', $Content, 'title', ['id'=>'title','class' => 'form-control','require', 'maxlength'=>140,'placeholder' => 'กรอกชื่อรุ่น..','title' => 'ชื่อรุ่น ..']);?>
                                                    <span class="help-block pull-right" id="title-available-char"></span>
                                                    <span class="help-block">
                                                        Maxlength is 140 chars. </span>
                                                </div>
                                                <div class="form-group">
													<label class="control-label">เลือกบ่อ</label>
													<?= Html::dropDownList('type', ' ', $arrTypelist , ['id'=>'type','class' => 'form-control input-medium'])?>	
												</div>
                                                <div class="form-group">
                                                    <label class="control-label">คำอธิบาย ข้อมูลอื่นๆ ของรุ่นนี้</label>
                                                    <?php echo Html::activeTextarea($Content, 'content', ['id'=>'content_textarea','class' => 'form-control', 'rows'=>'20', 'style'=>'font-size:14px;'])?>
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
														<a class="btn <?php // echo \Yii::$app->params['uiPortletHighlight'] ?>" href="http://news.tpbs.ndev.pw/content/preview?id=<?php // echo "{$Content->id}&key=$previewKey" ?>" target="_blank"> 
															<i class="fa fa-search"></i> Preview
														</a>

													</div>
												</div>
                                            
                                        </div>
                                        <div class="portlet-body form">

                                            <div class="form-body">
                                                <h3 class="form-section">ข้อมูล ลูกกุ้งที่ปล่อยลงในบ่อ</h3>
                                                <div class="form-group">
                                                    <label class="control-label">ชนิดของลูกกุ้ง</label>
                                                   <?php // echo Html::activeDropDownList($Content, 'type', Content::$arrTypeTpbs, ['class'=>'form-control select2me', 'data-placeholder'=>'Select...'])?>
                                                </div>
                                                <div class="form-group">
                                                	<label class="control-label">ราคาของลูกกุ้ง</label>
                                                    <?php // echo Html::dropDownList('categoryId', $Content->categoryId, [0=>'เลือกหมวดหมู่'] + CategoryTree::getAllRootNode(), ['id'=> 'categoryId', 'class'=> 'form-control select2me'])?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">บริษัท / ฟาร์ม ที่รับมา</label>
                                                   <?php // echo Html::activeDropDownList($Content, 'tpbsId', [0=> 'เลือกหัวข้อ'] + $arrFeed, ['class'=>'form-control select2me', 'data-placeholder'=>'Select...'])?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-body">
                                                <h3 class="form-section">วันที่ ปล่อยลงบ่อ</h3>
                                                <div class="form-group">
													<label class="control-label">Credit :</label>
													<?php // echo Html::activeTextInput($Content,'credit',array('class'=>'form-control medium'))?>
												</div>
                                                <div class="form-group">
                                                    <label class="control-label">สถานะ</label>
                                            <?php // if(!$canPublishNews && ($Content->status == Workflow::STATUS_PUBLISHED)):?>
                                            		<p><?php // echo Workflow::$arrStatusTpbs[$Content->status]?></p>
                                            <?php // else :?>
                                            	<?php // echo Html::activeDropDownList($Content, 'status', $arrStatus, ['class'=>'form-control select2me', 'data-placeholder'=>'Select...'])?>
                                            <?php // endif;?>
                                                </div>
                                                <div class="form-group">
	                                                <p class="news-calendar">
														<i class="fa fa-calendar"></i> กำหนดจับกุ้ง
													</p>
													<label class="control-label">วันที่</label>
													<?php echo Html::textInput('content_date', $contentDate, array('class'=>'form-control form-control-inline  date-picker'))?>
                                                	<label class="control-label">เวลา</label>
                                                	<?php echo Html::textInput('content_time', $contentTime, array('class'=>'form-control timepicker-24'))?>
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
                                                    <a href="<?php echo Url::toRoute('content/list') ?>" class="btn" >ยกเลิก </a>
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

<script>

$(document).ready(function() {       
    $('#maxlength_140').maxlength({
        limitReachedClass: "label label-danger",
        threshold: 50
    });
    
    $('#maxlength_255').maxlength({
        limitReachedClass: "label label-danger",
        threshold: 55
    });

    
    if($('input[name=expire_date]').val() == ""){
    	$('input[name=expire_time]').val("")
    }else{
        $('input[name=expire_time]').addClass("timepicker-24");
    }

    var length = $("#title").val().length;
	var availableChar = 140-length;
	$("#title-available-char").html(availableChar);
    
});

$('input[name=expire_date]').on("change", function(){
	if($('input[name=expire_date]').val() != ""){
		$('input[name=expire_time]').timepicker({
            autoclose: true,
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        });
	}
});

$("#title").on("change keyup paste", function(){
    var length = $("#title").val().length;
	var availableChar = 140-length;
	$("#title-available-char").html(availableChar);
});

</script>