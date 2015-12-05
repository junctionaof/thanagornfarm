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
                <i class="fa fa-table"></i>รายการ บันทึกค่าอุณหฤูมมิของนํ้า
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
                                 	<a class="btn add" href="<?= Url::toRoute(['pond/editwatertemp'])?>" title="เพิ่ม">
                                        <i class="icon-plus">  บันทึกค่าอุณหฤูมมิของนํ้า</i>
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
                 		            บ่อ - รุ่นที่
                        </th>
                        <th>
                    		 วัน/เดือน/ปี ที่ให้อาหาร
                        </th>
                        <th>
                          	  ปริมานที่ให้
                        </th>
                        <th>
                          	  เบอร์อาหาร
                        </th>
                        <th>
                          	 มื้อที่
                        </th>
                        <th>
                          	  อายุลูกกุ้ง
                        </th>
                        <th>
                                                                         ผู้บันทึก
                        </th>
                    </tr>
                </thead>
                <tbody>
<?php 
	if($lst):
		foreach ($lst as $Content):
?>                
                    <tr class="odd">
                        <td> <?php echo Html::checkbox('idCheck[]', false, ['value'=> $Content->id, 'class'=> 'checkboxes'])?></td>
                        <td><?php echo $arrPond[$Content->pondId];?></td>
                        <td class="text-left"><?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($Content->watertempTime));?></td>
                        <td><?php echo $Content->watertempNum;?> </td>
                        <td><?php echo $Content->numberOf;?>   </td>
                        <td> <?php echo $Content->watertempNo;?>  </td>
                        <td> <?php echo $Content->age;?>  </td>
                        <td class="center"><?php echo $arrUser[$Content->createBy];?> </td>
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