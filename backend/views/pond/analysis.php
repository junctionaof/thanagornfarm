<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;
use common\models\analysis;
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
                <i class="fa fa-table"></i>รายการบันทึกวิเคราะห์ผลการเลี้ยง 
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
                                 	<a class="btn add" href="<?= Url::toRoute(['pond/editanalysis'])?>" title="เพิ่ม">
                                        <i class="icon-plus"> บันทึกวิเคราะห์ผลการเลี้ยง </i>
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
		                        <th>บ่อ-รุ่นที่ </th>
		                        <th>อายุลูกกุ้ง</th>
		                        <th>วันที่จับกุ้ง</th>
		                        <th>รายรับ</th>
		                        <th>กำไรขั้นต้น</th>
		                        <th>ผลผลิตต่อไร่</th>
		                        <th>ผู้บันทึก </th>	
                    	</tr>
                </thead>
                <tbody>
					<?php 
					if($lst):
						foreach ($lst as $Content):
					?>                
				          <tr class="odd">
				                 <td> <?php echo Html::checkbox('idCheck[]', false, ['value'=> $Content->id, 'class'=> 'checkboxes'])?></td>
				                 <td><a href="<?= Url::toRoute(['pond/editanalysis'])?>?id=<?=$Content->id?>"><?php echo isset($arrPond[$Content->pondId])?$arrPond[$Content->pondId]:'ไม่ได้เลือกบ่อ รุ่น ';?></a></td>
				                 <td> <?php echo $Content->age;?>  </td>
				                 <td class="text-left"><?php echo DateUtil::th_date(DateUtil::LDT_FMT_TH, strtotime($Content->pickDate));?></td>
				                 <td><?php echo $Content->receipts; ?> </td>
				                 <td><?php echo $Content->profits; ?>   </td>
				                 <td> <?php echo $Content->yields; ?>  </td>
				                 <td class="center"><?php echo  isset($arrUser[$Content->lastUpdateBy]) ? $arrUser[$Content->lastUpdateBy] : 'anonymous'; ?>  </td>
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