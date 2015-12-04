<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;
use common\models\Content;
use common\models\Typelist;
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
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-table"></i>จัดการบ่อเลี้ยงกุ้ง
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
                                 	<a class="btn add" href="<?= Url::toRoute(['pond/editalkalinity'])?>" title="เพิ่ม">
                                        <i class="icon-plus"> เพิ่มบ่อเลี้ยงกุ้ง</i>
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
                 		             ขนาดบ่อ
                        </th>
                        <th>
                    		 วัน/เดือน/ปี ที่สร้าง
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
?>                
                    <tr class="odd">
                         <td>                  	
                            <?php echo Html::checkbox('idCheck[]', false, ['value'=> $Content->id, 'class'=> 'checkboxes'])?>
                        </td>
                        <td><?php echo $Content->name;?> </td>
                        <td><?php echo $Content->size;?></td>
                        <td class="text-center"><?php echo DateUtil::th_date(DateUtil::SDT_FMT_TH, strtotime($Content->createTime));?></td>
                        <td></td>
                        <td class="center"></td>
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