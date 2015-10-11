<?php

use yii\helpers\Html;
use app\Entity;
use app\TagsAlgo;
use app\JsonPackage;
use backend\components\TagCloud;
use backend\components\UiForm;
use backend\components\FileUpload;
use common\models\Media;

$appBaseUrl = \Yii::getAlias('@web');

switch (get_class($entity)) {
    case 'CartoonChapter':
        $refId = $entity->cartoonId . '-' . $entity->chapter;
        break;
    case 'FeedContent':
        $refId = (float) $entity->ts;
        break;
    default:
        $refId = $entity->id;
}

$props = JsonPackage::unpackProps($entity);
$panoItemNo = -1;
if (isset($props['highlights']) && isset($props['highlights'][Media::HIGHLIGHT_PANORAMA])) {
    $panoItemNo = $props['highlights'][Media::HIGHLIGHT_PANORAMA];
}
unset($props['highlights']['1']);
echo UiForm::widget(['features' => [UiForm::FEATURE_TAGS]]);
?>

<?php echo Html::csrfMetaTags();?>

<link href="<?php echo $appBaseUrl ?>/global/css/toastr.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $appBaseUrl ?>/global/scripts/toastr.js"></script>
<style>
    .displayBlock{
        display:block !important;
    }
    #crop-modal{ 
        top: 10% !important;
        left: 40% !important;
    }
</style>
<div class="tab-pane" id="media-tab" data-entity="<?php echo Entity::mapType($entity) ?>" data-id="<?php echo $refId ?>">
    <?php
    if ($useForm) :
        ?>
        <form id="fileupload" action="<?php echo $appBaseUrl . '/media/upload'; ?>" method="POST" enctype="multipart/form-data" class="fileupload-processing">
            <?php
        endif;
        ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                if (empty($this->arrMedia)) :
                    ?>
                    <label>ตั้งภาพจั่วอัตโนมัติ</label>
                    <?php
                    echo Html::checkbox('autoHighlight', 1, array('checked' => 'checked'));
                endif;
                ?>
                <?php
                echo FileUpload::widget(['useForm' => false,
                    'params' => ['entity' => Entity::mapType($entity), 'id' => $refId] + $fileUploadParams
                ]);
                ?>
            </div>
        </div>
        <?php
        if ($useForm)
            echo '</form>';
        ?>

        <div class="row">
            <div id="media-list" class="col-md-6">
                <div class="row" style="padding-left:15px">
                    <?php
                    echo Html::a(Html::img('') . '<p class="margin-top-10"><span class="width-height pull-right"></span>' .
                            '<span class="orderNo" style="padding-left:10px"></span>' .
                            'No.<input class="orderNo form-control" style="width:50px; padding-left:10px">' .
                            '</p>'.
                    		'<div class="btn-group btn-group-xs btn-group-solid" style="position: absolute;top:0;width:40px;padding-top:5px;">' .
                    		'<button type="button" id ="highlightButton" class="btn green selectHighlight circleHighlight" style="display:none;" ><i class="fa fa-camera"></i></button>' .
                    		'<button type="button" id ="panoramaButton" class="btn red selectPanorama circleHighlight" style="display:none;"><i class="fa fa-picture-o"></i></button>' .
                    		'</div>'
                            , 'javascript:;', array(
                        'class' => 'col-md-4 col-sm-6 thumbnail',
                        'data-itemno' => 0,
                        'style' => 'display: none',
                    ));
                    if (is_array($arrMedia)) {
                        $highlightDisplay = "";
                        $panoDisplay = "";
                        $tagDisplay = "";
                        //var_dump($arrMedia); exit;
                        foreach ($arrMedia as $mediaFields) {
                            $highlightDisplay = "";
                            $panoDisplay = "";
                            $tagDisplay = "";
                            $class = 'col-md-4 col-sm-6 thumbnail';

                            if ($mediaFields['itemNo'] == $panoItemNo)
                                $panoDisplay .= ' displayBlock';

                            if ($mediaFields['itemNo'] == $entity->previewRefId)
                                $highlightDisplay .= ' displayBlock';


                            if (isset($props['highlights'])) {
                                if (in_array($mediaFields['itemNo'], $props['highlights'], TRUE))
                                    $tagDisplay .= ' displayBlock';
                            }
                            echo Html::a(Html::img($appBaseUrl . '/media/' . $mediaFields['fullPath']) .
                                    '<p class="margin-top-10"><span class="width-height pull-right">' . Html::encode($mediaFields['width']) . " x " . Html::encode($mediaFields['height']) . '</span>' .
                                    'No.<input class="orderNo form-control" style="width:50px; padding-left:10px" value="' . $mediaFields['orderNo'] . '">' .
                                    '</p>' .
                                    '<div class="btn-group btn-group-xs btn-group-solid" style="position: absolute;top:0;width:40px;padding-top:5px;">' .
                                    '<button type="button" id ="highlightButton" class="btn green selectHighlight circleHighlight' . $highlightDisplay . '" style="display:none;" ><i class="fa fa-camera"></i></button>' .
                                    '<button type="button" id ="panoramaButton" class="btn red selectPanorama circleHighlight' . $panoDisplay . '" style="display:none;"><i class="fa fa-picture-o"></i></button>' .
                                    '</div>'
                                    , 'javascript:;', array(
                                'class' => $class,
                                'data-itemno' => $mediaFields['itemNo']
                            ));


                            unset($highlightDisplay);
                            unset($panoDisplay);
                            unset($tagDisplay);
                        }
                    }
                    ?>
                </div>
                <div class="row form-actions nobg fluid" style="padding-left:15px;">
                	<?php $hidden = count($arrMedia)>=2?"":"hidden"?>
                    <button type="button" id="bt-order-save" class="<?php echo $hidden ?> btn btn-primary">บันทึกการจัดลำดับ</button>
                </div>
            </div>

            <div class="col-md-6 form">
                <form action="javaScript:;" class="horizontal-form">
                    <div class="form-body">
                        <h3 class="form-section">แก้ไขข้อมูลภาพ</h3>

                        <div class="form-group">

                            <label class="control-label">เลือกลายน้ำ</label>
                            <?php echo Html::dropDownList('waterMarkType', '', Media::$arrWaterMarkType, array('class' => 'form-control')) ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">คำบรรยายภาพ (Alt)</label>
                            <textarea class="form-control" rows="5" name="media-caption"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Tags</label>
                            <div id="tagGeControl">
                                <input type="text" name="media-tags" id="tag_pic" class="form-control" >
                            </div>
                       		 <span class="help-block">Tags - คำสำคัญของภาพ เพื่อช่วยเหลือในการค้นหาได้ดียิ่งขึ้น**ให้คั่นแต่ละคำโดยการกดปุ่ม Enter</span>
	                    </div>
                    </div>
                    <div class="form-actions nobg fluid">

                        <button type="button" id="bt-detail-save" class="btn btn-primary" disabled="disabled">บันทึก</button>
                        <a href="" id="bt-img-delete" class="btn " disabled="disabled" data-toggle="modal">ลบภาพ</a>

						<button id="media-button-highlight" disabled="disabled" class="btn green">ตั้งภาพจั่ว</button>
                        <!-- <div class="btn-group dropup ">
                            <button class="btn green dropdown-toggle " type="button" data-toggle="dropdown" disabled="disabled">
                                Highlight <i class="fa fa-angle-up"></i>
                            </button>
                            
                            <ul class="dropdown-menu pull-right hold-on-click" role="menu" style="left: 0%;">
                                <li><a id="media-button-highlight" href="#"><i class="fa fa-camera"></i> Thumbnail</a></li>
                                <li><a id="media-button-highlight-pano" href="#"><i class="fa fa-picture-o"></i> Panorama</a></li> 
                            </ul>
                        </div>-->
                    </div>
                </form>
            </div>
        </div>
        <div id="media-modal" class="modal fade" tabindex="-1" >
            <div class="modal-dialog" style="width: auto; margin:0;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"></button>
                        <h4 class="modal-title">ยืนยันการลบภาพ</h4>
                    </div>
                    <div class="modal-body">
                        <p>คุณต้องการลบภาพที่เลือก?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn default" data-dismiss="modal">ยกเลิก</button>
                        <button id="media-modal-confirm" data-dismiss="modal" class="btn blue">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>

</div>

<div id="crop-modal" class="modal container fade" tabindex="-1" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"></button>
        <h4 class="modal-title">จัดการรูป</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="button_jcrop col-md-7">
                <span id="jcrop-choose" class="requiresjcrop">
                    <button id="jcrop_create-portlet" class="btn btn-block red">บันทึก</button>			
                </span>
            </div>
            <div class="col-md-5">
                <div class="pull-right">
                    <a href="javascript:;" id="ratio-16" class="btn default green-stripe" data-ratio="16"> 16:9</a>
                    <a href="javascript:;" id="ratio-16" class="btn default green-stripe" data-ratio="4">4:3</a>
                    <a href="javascript:;" id="ratio-0" class="btn default purple-stripe" data-ratio="1">1:1</a>
                    <a href="javascript:;" id="ratio-0" class="btn default purple-stripe" data-ratio="0">ฟรีไซส์</a>
                </div>
            </div>
        </div>	
        <div class="row">
            <div style="padding-left:15px; padding-top:15px;">
                <img alt="Jcrop Example" src="" class="img-responsive" id="picture-1" >
            </div>
            <div class="jc_coords">
                <form onsubmit="return false;">
                    <label>X1 <input type="text" size="4" id="x" name="x"></label> <label>Y1
                        <input type="text" size="4" id="y" name="y">
                    </label> <label>X2 <input type="text" size="4" id="x2" name="x2"></label>
                    <label>Y2 <input type="text" size="4" id="y2" name="y2"></label>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
    </div>
</div>


<?php
echo TagCloud::widget([
    'idName' => 'tag_pic',
]);
?>

<script>

    function recalltag() {
        <?php
        $tagObj = new TagsAlgo();
        $targetArray = $tagObj->getTagResult();
        $targetArray = json_encode($targetArray);
        ?>
        $("#tag_pic").select2({
            tags: <?php echo $targetArray ?>,
        });
    }

    $(document).ready(function ($) {
        CropImage.init();
        $(document).delegate(".rangAp", "change", function () {
            debugger;
        });

        $('#trigger').popover({
            html: true,
            content: function () {
                return $('#popover-content');
            }
        });

        $('#link').click(function () {
            alert('beep');
        });
    });


</script>