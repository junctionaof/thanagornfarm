<?php 
use yii\helpers\Url;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\web\View;
use common\models\User;
use yii\rbac\Rule;
use backend\controllers\UserController;

$baseUrl = \Yii::getAlias('@web');

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
$str = <<<EOT
$.ajaxSetup({
        data: {
        	$csrfParam: '$csrfToken'
		}
});			
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
		
$('.do').click(function() {
	postAction($(this).attr('data-action'));
});	


	
EOT;
$this->registerJs($str);
$this->registerCssFile("$baseUrl/assets/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css");
$this->registerCssFile("$baseUrl/assets/plugins/bootstrap-editable/inputs-ext/address/address.css");

$css = <<<EOT

.alert-warning{
background-color:#EEEEEE;
border-color:#DDDDDD;
color: #666666;
}
.paddSearch{
	padding-right:20px;
	float:left;
}
.reslut{
float:left;
}
.pagination{
float:left !important;
}
.dropdown-menu{
right: 0 !important;
left: auto !important;
}

.text-num-position{text-align: right;}
.event-panel{margin-right: 2%;}
.edit-inline{display: none;}
EOT;
$this->registerCss($css);
?>

<?php ActiveForm::begin(['id' => 'dataTable-form'])?>
<div class="portlet box grey">
	<div class="portlet-title">
		<div class="caption"><i class="fa fa-reorder"></i>รายชื่อพนักงานทั้งหมด</div>
		<div class="actions">
			<a href="javascript:;" class="btn search" data-toggle="collapse" data-target="#datatable-toolbar"><i class="fa fa-search"></i> ค้นหาพนักงาน</a> 
			
			<a href="<?= Url::toRoute(['add'])?>"
						class="btn add"><i class="fa fa-pencil"></i> เพิ่มข้อมูลพนักงาน </a>
			<a href="<?= Url::toRoute(['list'])?>" class="btn listall"><i class="fa fa-filter"> ดูข้อมูลทั้งหมด</i></a>
			<div class="btn-group">
				<a class="btn action" href="javascript:;" data-toggle="dropdown"> 
					<i class="fa fa-cogs"></i> รายการที่เลือก
					<i class="fa fa-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li><a href="javascript:;" class="do" data-action="delete"><i class="fa fa-trash-o"></i>ลบข้อมูล</a></li>

				</ul>
			</div>
		</div>
	</div>
	<div class="portlet-body">
				<div id="datatable-toolbar" class="table-toolbar collapse">
			<div class="alert alert-warning">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-right">
						<div class="paddSearch">
							<input name="searchText" type="text" class="form-control input-medium" placeholder="กรุณากรอกคำที่คุณต้องการค้นหา...">
						</div>
						<label class="paddSearch" style="padding-top:5px;"> : </label>
						<div class="paddSearch">
							<input type="hidden" name="searchUser" value="search">
							<a class="do btn btn search" data-action="search"><i class="fa fa-search"></i> ค้นหา</a>
						</div>	
						</div>		
					</div>
				</div>
			</div>			
		</div>	
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover text-center">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">ชื่อที่ใช้เข้าสู่ระบบ</th>
						<th class="text-center">ชื่อพนักงาน</th>
						<th class="text-center">นามสกุลพนักงาน</th>
						<th class="text-center">ตำแหน่ง</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($model as $lst):?>
					<tr data-id="<?=$lst['username'];?>" >
						<td><input name="selectUser[]" type="checkbox" value="<?php echo $lst['id']?>"></td>
						<td><a href="<?= Url::toRoute(['edit'])?>?id=<?=$lst['id'] ?>"><?php echo $lst['username']?></a></td>
						<td><?php echo $lst['firstName']?></td>
						<td><?php echo $lst['lastName']?></td>
						<td><?php echo User::$arrPosition[$lst['position']];?></td>						
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="pull-right">
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="op" id="op" value="">
<?php ActiveForm::end();
$this->registerJsFile($baseUrl.'/assets/plugins/jquery.mockjax.js');
$this->registerJsFile($baseUrl.'/global/plugins/uniform/jquery.uniform.min.js');
$this->registerJsFile($baseUrl.'/assets/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.min.js');
$this->registerJsFile($baseUrl.'/assets/plugins/bootstrap-editable/inputs-ext/address/address.js');
$this->registerJsFile($baseUrl.'/assets/plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js');
//$this->registerJsFile($baseUrl.'/assets/scripts/form-editable.js');
?>
