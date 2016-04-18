<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;
use common\models\Content;
use common\models\Typelist;
use common\models\Waterchange;
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
                <i class="fa fa-table"></i>รายการ บันทึกการเปลี่ยนถ่ายนํ้า
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
                                 	<a class="btn add" href="<?= Url::toRoute(['pond/editwaterchange'])?>" title="เพิ่ม">
                                        <i class="icon-plus"> บันทึกการเปลี่ยนถ่ายนํ้า</i>
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
                        <div id="datatable-toolbar" class="table-toolbar" style="padding: 20px;background: #E9ECF3;">
			<div class="row" ">
				<div class="col-md-12">
					<div class="btn-group pull-right">
						<button class="btn blue" id="searchBtn">ค้นหา</button>
					</div>
					<div class="col-md-4 pull-right">
					
					<div class="input-group" id="type">
					<?php echo Html::dropDownList('type', '0', ['0'=>"เลือกบ่อที่ต้องการ"]+$arrTypelist , ['id'=>'type','class' => 'form-control pull-right input-medium'])?>	
					 <span class="input-group-addon"><i class="glyphicon glyphicon-list-alt "></i></span>
					</div>
					</div>
					<div class="col-md-7 pull-right" >
						 <div class="input-group " >
						 	<input type="text" name="q" id="q" class="form-control form-control-inline"  placeholder="ค้นหาตามชื่อรุ่น">           				
							<span class="input-group-addon"><i class="glyphicon glyphicon-list-alt "></i></span>
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
                    		 วัน/เดือน/ปี ที่บันทึก 
                        </th>
                        <th>
                          	 ค่าที่วัดได้ 
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
                        <td><a href="<?= Url::toRoute(['pond/editwaterchange'])?>?id=<?php echo $Content->id; ?>"><?php echo $Content->name;?></td>
                        <td class="text-left"><?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($Content->waterchangeTime));?></td>
                        <td><?php echo $Content->waterchangeNum;?> </td>
                        <td> <?php echo $Content->age;?>  </td>
                        <td class="center"><?php echo  isset($arrUser[$Content->createBy]) ? $arrUser[$Content->createBy] : 'anonymous'; ?>  </td>
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