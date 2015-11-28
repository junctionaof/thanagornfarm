<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;
use common\models\Content;
use app\DateUtil;
use app\Workflow;
use common\models\ObjectCategory;
use app\CategoryTree;
use common\models\Media;
use app\Entity;
$identity = \Yii::$app->user->getIdentity();
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;
$str = <<<EOT
function postAction(action) {

		if(action == 'delete'){
			if(! confirm("คุณแน่ใจว่าต้องการจะลบรายการที่เลือกไว้ ?")){
				$('div.checker span').removeClass('checked');
				return false;
			}
		}

		$('#op').val(action);
		$('#dataTable-form').submit();
}

$('#searchBtn').click(function() {
		postAction('search');
});

$('.actions .dropdown-menu a[data-action]').click(function() {
		 if ($('[name^=idCheck]:checked').length > 0)
			postAction($(this).attr('data-action'));
});
EOT;

$css = <<<EOT
	table tbody tr td a.btn:hover {
	 cursor:default;
	}
	table tbody tr td a.cursor-pointer:hover {
	 cursor:pointer;
	}
EOT;

$this->registerCss($css);
$this->registerJs($str, View::POS_LOAD, 'form-js');

ActiveForm::begin(['id' => 'dataTable-form']);
?>
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-table"></i>รายการข้อมูลบ่อเลี้ยงกุ้ง
            </div>
            <div class="tools">
                <a href="javascript:;" class="collapse">
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="portlet-title">
                                <div class="actions">
                                 	<a class="btn  add" href="<?= Url::toRoute(['content/edit'])?>" title="เพิ่ม">
                                        <i class="icon-plus"> เพิ่มรุ่นในบ่อเลี้ยง</i>
                                    </a>
                                    <a class="btn  listall" href="<?= Url::toRoute(['content/typelist'])?>" title="เพิ่ม">
                                        <i class="icon-plus"> จัดการบ่อเลี้ยงกุ้ง</i>
                                    </a>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                        <div class="actions">					
							<div class="btn-group pull-right">
								<a class="btn  action" href="#"
									data-toggle="dropdown"> <i class="fa fa-cogs"></i> รายการที่เลือก
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right">
									<!-- <li><a href="javascript:;" data-action="publish"><i class="fa fa-check"></i> แสดงผล</a></li>
									<li><a href="javascript:;" data-action="unpublish"><i class="fa fa-ban"></i> ไม่แสดงผล</a></li> -->
									<li class="divider"></li>
									<li><a href="javascript:void(0);" data-action="delete"><i class="fa fa-trash-o"></i> ลบข้อมูล</a></li>
								</ul>
							</div>
						</div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-list" id="">
                <thead>
                    <tr>
                        <th class="table-checkbox">
                            <input type="checkbox" class="group-checkable" data-set=".table-list .checkboxes"/>
                        </th>
                        <th>
                     		   บ่อที่ 
                        </th>
                        <th>
                 		             รุ่นที่
                        </th>
                        <th>
                    		 วัน/เดือน/ปี
                        </th>
                        <th>
                          	  จำนวนลูกกุ้ง
                        </th>
                        <th>
                          	ชนิดลูกกุ้ง
                        </th>
                        <th>
                           	บริษัทฟาร์มลูกถ้ง
                        </th>
                        <th>
                                                                          แก้ไขล่าสุด
                        </th>
                        <th>
                                                                         ผู้แก้ไข
                        </th>
                    </tr>
                </thead>
                <tbody>
<?php 
	if($lst):
		foreach ($lst as $Content):
		$viewCount = $Content->viewCount?number_format($Content->viewCount):0;
		$node = isset($Content->categoryId)?CategoryTree::getNode($Content->categoryId):'-';
		
		$arrStatus = isset(Workflow::$arrStatusTpbsIcon[$Content->status])?Workflow::$arrStatusTpbsIcon[$Content->status]:NULL;

		$query = Media::find();
		$query->andWhere(['type'=> Entity::TYPE_CONTENT, 'refId'=> $Content->id]);
		$mediaCount = $query->count();
		
		$hasPreview = (bool)$Content->previewRefId;
?>                
                    <tr class="odd">
                        <td>
                        	<?php
                        		$disable = [];
                        	 	if(!$canPublishNews && $Content->status == Workflow::STATUS_PUBLISHED){
                        	 		$disable["disabled"] = "disabled";
                        	 	}?>
                        	
                            <?php echo Html::checkbox('idCheck[]', false, ['value'=> $Content->id, 'class'=> 'checkboxes'] + $disable)?>
                        </td>
                        <td>
                            <?php echo $Content->id;?>
                        </td>
                        <td>
                            <a href="<?= Url::toRoute(['content/edit','id'=>$Content->id])?>"><?php echo $Content->title;?></a>
                        </td>
                        <td>
                            <?php echo isset(Content::$arrType[$Content->type])?Content::$arrType[$Content->type]:'-';?>
                        </td>
                        <td class="center">
                            <?php echo $node->title;?>
                        </td>
                        <td>
                        <?php 
                        	if($arrStatus):
                        ?>
                        	<a href="javascript:;" title="<?php echo Workflow::$arrThStatusTpbs[$Content->status]?>" class="btn btn-icon-only <?php echo $arrStatus['css']?>">
                                <i class="fa <?php echo $arrStatus['icon']?>"></i>
                            </a>
                        <?php 
                        	endif;
                        ?>
						<?php if(false): //$hasPreview?>
							<a href="javascript:;" title="มีรูปจั่ว" class="btn btn-icon-only green">
                                <i class="fa fa-star-o"></i>
                            </a>
						<?php endif;?>
						<?php if($mediaCount > 0):?>		
                            <a href="javascript:;" title="มีรูปแล้ว" class="btn btn-icon-only hasmedia">
                                <i class="fa fa-picture-o"></i>
                            </a>
                        <?php endif;?>
                        <?php if($Content->hasVideo):?>
                            <a href="javascript:;" title="มีวีดีโอ" class="btn btn-icon-only hasvideo">
                                <i class="fa fa-video-camera"></i>
                            </a>
                        <?php endif;?>
                        <?php if($Content->hasComment):?>
                            <a href="javascript:;" title="มี comment" class="btn btn-icon-only hascomment">
                                <i class="fa fa-comments"></i>
                            </a>
                        <?php endif;?>
                        </td>
                        <td class="center">
                            <?php echo $viewCount;?> ครั้ง
                        </td>
                        <td class="center">
                           	<?php echo DateUtil::th_date(DateUtil::SDT_FMT_TH, strtotime($Content->lastUpdateTime));?>
                        </td>
                        <td class="center">
                            <?php echo $arrUser[$Content->lastUpdateBy]?>
                        </td>
                    </tr>
<?php 
		endforeach;
	endif;
?>                    
                </tbody>
            </table>
            
            <?= LinkPager::widget(['pagination' => $pagination,]);?>
        </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->


<?php ActiveForm::end() ?>
<script>
$(document).ready(function() {       
    $( "#filter-bnt" ).live( "click", function() {
        $('#filter-search').toggle(500);
    });
});
</script>