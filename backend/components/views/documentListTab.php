<?php

use yii\helpers\Html;
use app\TagsAlgo;
use app\Entity;
use backend\components\TagCloud;
use backend\components\DocumentFileUpload;
use common\models\Document;


$baseUrl = \Yii::getAlias('@web');
$refId = $entity->id;


$str = <<<EOT

$("#bt-download-file").click(function(){
	var url = "$baseUrl/document/download";
	document.location = url+"?1=" + $('#document-tab').attr('data-entity') + "&2=" + $('#document-tab').attr('data-id') + "&3=" + $(this).attr('item-no');

});

EOT;
$this->registerJs($str);

?>
<link href="<?php echo $baseUrl?>/global/css/toastr.css" rel="stylesheet" type="text/css" />

<script src="<?php echo $baseUrl?>/global/scripts/toastr.js"></script>
<style>
.displayBlock{
display:block !important;
}
</style>
<div class="tab-pane" id="document-tab" data-entity="<?php echo Entity::mapType($entity) ?>" data-id="<?php echo $refId ?>">
<?php
if ($useForm) :
?>
    <form id="documentfileupload" action="" method="POST" enctype="multipart/form-data" class="fileupload-processing">
<?php
    endif;
?>
        <div class="row">
            <div class="col-md-12">
            
                <?php
                echo DocumentFileUpload::widget(['useForm' => false,
                    'params' => ['entity' => Entity::mapType($entity), 'id' => $refId]
                ]);
                ?>
            </div>
        </div>
<?php
if ($useForm)
    echo '</form>';
?>
            
        <div class="row">
            <div id="document-list" class="col-md-6">
            	<?php
                echo Html::a('<div class="col-md-4">'.Html::img('', ['width'=>'50', "height"=>'50']).'</div>' . 
                		'<div class="col-md-6">'.
                			'<p class="text-center margin-top-10"></p>'.
                		'</div>', 
                		'javascript:;', array(
                    'class' => 'col-md-9 col-sm-6 thumbnail',
                    'data-itemno' => 0,
                    'style' => 'display: none',
                ));
                if (is_array($arrDocument) && !empty($arrDocument)) :
                	
                    foreach ($arrDocument as $documentFields) :
                   		
                    	$arr = explode(".", $documentFields['srcPath']);
                		$extension = $arr[count($arr) - 1];
                		$class = 'col-md-9 col-sm-6 thumbnail';
                			
			           	echo Html::a('<div class="col-md-4">'.Html::img($baseUrl .'/global/img/'.$extension.".png", ['width'=>'50', "height"=>'50']) .'</div>'.
			                		'<div class="col-md-6"><p class="text-center margin-top-10">' . Html::encode($documentFields['fileName']) . "</p></div>"
			                		,'javascript:;', array(
			                				'class' => $class,
			                				'data-itemno' => $documentFields['itemNo']
			                		)); 
                    endforeach;
                endif;
                ?>
			 </div>

            <div class="col-md-6 form">
                <form action="javaScript:;" class="horizontal-form">
                    <div class="form-body">
                        <div class="form-group">
                            <h3 class="form-section">แก้ไขข้อมูลประกอบไฟล์</h3>
                        </div>
                        <div class="form-group">
                            <label class="control-label">คำอธิบายไฟล์</label>
                            <textarea class="form-control" rows="5" name="document-caption"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label">แท็ก</label>
                            <div id="tagGeControl-doc">
                            	<input type="text" name="document-tags" id="tag_doc" class="form-control">
                            </div>
                        </div>
                        <input class="hidden" name="document-uri">
                    </div>
                    <div class="form-actions nobg fluid">
                        <button type="button" id="bt-detail-save" class="btn btn-primary" disabled="disabled">บันทึก</button>
                        <a href="#document-modal" class="btn" disabled="disabled" data-toggle="modal">ลบไฟล์</a>
                        <button type="button" id="bt-download-file" class="btn " disabled="disabled">ดาวน์โหลด</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="document-modal" class="modal fade" tabindex="-1" style="width:650px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"></button>
                        <h4 class="modal-title">ยืนยันการลบไฟล์</h4>
                    </div>
                    <div class="modal-body">
                        <p>คุณต้องการลบไฟล์ที่เลือก?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn default" data-dismiss="modal">ยกเลิก</button>
                        <button id="document-modal-confirm" data-dismiss="modal" class="btn blue">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>
        
</div>

<script>

function recalltagdoc(){
    <?php
        $tagObj = new TagsAlgo();
        $targetArray = $tagObj->getTagResult();
        $targetArray = json_encode($targetArray);
    ?>
    $("#tag_doc").select2({
        tags: <?php echo $targetArray ?>,
    });         
}  

jQuery(document).ready(function() { 
	
    // initiate layout and plugins
    <?php
        echo TagCloud::widget([
            'idName'=>'tag_doc',
        ]);
    ?>

    
});



</script>