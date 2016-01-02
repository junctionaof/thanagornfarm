<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;
use common\models\Pond;
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
    <div class="portlet box blue">
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
                                 	<a class="btn  add" href="<?= Url::toRoute(['pond/edit'])?>" title="เพิ่ม">
                                        <i class="icon-plus"> เพิ่มรุ่นในบ่อเลี้ยง</i>
                                    </a>
                                    <a class="btn  listall" href="<?= Url::toRoute(['pond/typelist'])?>" title="เพิ่ม">
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
                    		 วันที่ปล่อยกุ้ง
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
                        <th>
                                                                          สร้างเมื่อ
                        </th>
                         <th>
                                                                         ผู้สร้าง
                        </th>

                    </tr>
                </thead>
                <tbody>
<?php 
	if($lst):
		foreach ($lst as $Pond):
?>                
                    <tr class="odd">
                        <td>
                        	<?php
                        		$disable = [];
                        	 	if(!$canPublishNews && $Pond->status == Workflow::STATUS_PUBLISHED){
                        	 		$disable["disabled"] = "disabled";
                        	 	}?>
                        	
                            <?php echo Html::checkbox('idCheck[]', false, ['value'=> $Pond->id, 'class'=> 'checkboxes'] + $disable)?>
                        </td>
                        <td>
                            <?php echo $Pond->type;?>
                        </td>
                        <td>
                            <a href="<?= Url::toRoute(['pond/edit'])?>?id=<?php echo $Pond->id; ?>"><?php echo $Pond->title;?></a>
                        </td>
                        <td>
                        <?php echo DateUtil::th_date(DateUtil::SDT_FMT_TH, strtotime($Pond->releaseTime));?>
                            
                        </td>
                        <td class="center">
                            <?php echo $Pond->larvae;?>
                        </td>
                        <td>
                        <?php echo isset($Pond->larvaeType)?Pond::$larvaeType[$Pond->larvaeType]:'-';?>
                        </td>
                        <td class="center">
							<?php echo $Pond->larvaeCompany;?>
                        </td>
                        <td class="center">
                           	<?php echo DateUtil::th_date(DateUtil::SDT_FMT_TH, strtotime($Pond->lastUpdateTime));?>
                        </td>
                        <td class="center">
                            <?php echo  isset($arrUser[$Pond->lastUpdateBy]) ? $arrUser[$Pond->lastUpdateBy] : 'anonymous'; ?>
                        </td>
                        <td class="center">
                           	<?php echo DateUtil::th_date(DateUtil::SDT_FMT_TH, strtotime($Pond->createTime));?>
                        </td>
                         <td class="center">
                            <?php echo  isset($arrUser[$Pond->createBy]) ?$arrUser[$Pond->createBy] : 'anonymous'; ?>
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

<?= Html::hiddenInput('op','',['id'=>'op']);?>
<?php ActiveForm::end() ?>
<script>
$(document).ready(function() {       
    $( "#filter-bnt" ).live( "click", function() {
        $('#filter-search').toggle(500);
    });
});
</script>