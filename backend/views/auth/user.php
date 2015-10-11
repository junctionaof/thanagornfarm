<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;
use common\models\User;
use app\DateUtil;
use app\Workflow;
use common\models\ObjectCategory;
use app\CategoryTree;
?>
<div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="row" id="filter-search" style="display: none;"> 
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-haze">
                        <i class="icon-settings font-green-haze"></i>
                        <span class="caption-subject bold uppercase"> ค้นหา</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <form class="form-inline margin-bottom-40" role="form">
<?php if(false):?>                    
                        <div class="md-checkbox md-checkbox-inline has-success">
                            <input type="checkbox" id="checkbox113" class="md-check">
                            <label for="checkbox113">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span>
                                ค้นข่าวเก่า </label>
                        </div>
<?php endif;?>                        
                        <div class="form-group form-md-line-input has-success">
                        	<?php // echo Html::dropDownList('status', $status, [0=>'สถานะ'] + Workflow::$arrStatus, ['id'=> 'form_control_1', 'class'=> 'form-control'])?>
                            <div class="form-control-focus">
                            </div>
                        </div>
						<div class="form-group form-md-line-input has-success">
                        <?php // echo Html::dropDownList('contentType', $contentType, [0=>'ชนิดข่าว'] + Content::$arrType, ['id'=> 'contentType', 'class'=> 'form-control'])?>
                            <div class="form-control-focus">
                            </div>
                        </div>
                        <div class="form-group form-md-line-input has-success">
                        <?php // echo Html::dropDownList('categoryId', $categoryId, [0=>'หมวด'] + CategoryTree::getAllRootNode(), ['id'=> 'categoryId', 'class'=> 'form-control'])?>
                            <div class="form-control-focus">
                            </div>
                        </div>
                        <div class="form-group form-md-line-input has-success">
                        <?php // echo Html::dropDownList('order', $order, [-1=>'เรียง'] + [0=>'---ทั้งหมด---', 'asc'=>'น้อยไปมาก', 'desc'=>'มากไปน้อย'], ['id'=> 'order', 'class'=> 'form-control'])?>
                            <div class="form-control-focus">
                            </div>
                        </div>
                        <div class="form-group form-md-line-input has-success">
                       		<?php // echo Html::textInput('q', $q, ['id'=> 'q', 'class'=> 'form-control', 'placeholder'=> 'ค้นหา']);?>
                            <div class="form-control-focus">
                            </div>
                        </div>
                        <?php // echo Html::submitButton('ค้นหา', ['class'=>'btn btn-success'])?>
                    </form>
                </div>
            </div>                      
        </div>
    </div>
    <div class="portlet box grey-cascade">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-globe"></i>จัดการผู้ใช้งานระบบ
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
                                    <a class="btn btn-circle btn-icon-only blue" id="filter-bnt" href="javascript:;" title="ค้นหาขั้นสูง">
                                        <i class="icon-magnifier"></i>
                                    </a>
                                    <a class="btn btn-circle btn-icon-only green" href="<?= Url::toRoute(['auth/edit'])?>" title="เพิ่ม">
                                        <i class="icon-plus"></i>
                                    </a>                                   
                                </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="btn-group pull-right">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">รายการ <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;">
                                        active </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Not active</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:;">
                                        ลบข้อมูล </a>
                                </li>
                            </ul>
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
                            User NAME
                        </th>
                        <th>
                        		  ชื่อ - นามสกุล
                        </th>
                        <th>
                       			    เบอร์โทรศัพท์
                        </th>
                        <th>
                        		   ตำแหน่ง
                        </th>
                        <th>
                        		    สิทธิ์
                        </th>
                        <th>
                       		    แผนก 
                        </th>
                        <th>
                    		       เข้าสู่ระบบล่าสุด
                        </th>
                        <th class="center" width="65px" >
                       		     สถาณะ
                        </th>
                    </tr>
                </thead>
                <tbody>
					<?php 
						if($user):
							foreach ($user as $Content):
					?>                
					                    <tr class="odd">
					                        <td>
					                            <?php echo Html::checkbox('idCheck', false, ['value'=> "0", 'class'=> 'checkboxes'])?>
					                        </td>
					                        <td>
					                            <?php echo $Content->username;?>
					                        </td>
					                        <td>
					                        <?php echo $Content->firstName." ".$Content->lastName ;?>
					                        </td>
					                        <td>
					                         <?php echo $Content->phone;?>
					                        </td>
					                        <td class="center">
					                         <?php echo $Content->position;?>
					                        </td>
					                        <td>
					                            <a href="javascript:;" title="ผู้ดูแลระบบ" class="btn btn-icon-only green">
					                                <i class="fa fa-edit"></i>
					                            </a>
					                            <a href="javascript:;" title="บรรณาธิการ" class="btn btn-icon-only gray ">
					                                <i class="fa fa-check"></i>
					                            </a>
					                            <a href="javascript:;" title="นักข่าว" class="btn btn-icon-only green">
					                                <i class="fa fa-eye"></i>
					                            </a>
					                        </td>
					                        <td class="center">
					                            <?php echo $Content->address; ?>
					                        </td>
					                        <td class="center">
					                           	<?php echo $Content->lastUpdateTime; ?>
					                        </td>
					                        <td class="center">
					                            <?php if ($Content->status == 1){ ?>
					                             <a href="javascript:;" title="บรรณาธิการ" class="btn btn-icon-only center blue ">
					                                <i class="fa  fa-unlock"></i>
					                            </a>
					                            <?php } elseif($Content->status == 0){ ?>
					                             <a href="javascript:;" title="บรรณาธิการ" class="btn btn-icon-only center red ">
					                                <i class="fa  fa-lock"></i>
					                            </a>
					                            <?php } ?>
					                            
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
</div>
<script>
$(document).ready(function() {       
    $( "#filter-bnt" ).live( "click", function() {
        $('#filter-search').toggle(500);
    });
});
</script>