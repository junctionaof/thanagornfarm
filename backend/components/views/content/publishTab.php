<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\PublishBL;
use app\CategoryTree;
use app\JsonPackage;

$identity = \Yii::$app->user->getIdentity();
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;
$this->registerJsFile($baseUrl.'/global/scripts/tinymce_init.js');
$str = <<<EOT
$(document).delegate('#add-category','click',function(){
	var thisObj = $($('.categoryClone')[0]).clone(true).removeClass('categoryClone').appendTo('#items-category');
	thisObj.find('select option:first').attr("selected", "selected");
	thisObj.attr('data-rank',$('.categoryClone').length+1);
});

$(document).delegate('.delete-category','click',function(){
	if($('#items-category [data-rank]').length == 1)
		return false;
	else
		$(this).parent('div').parent('div').parent('div').remove();
});
		
$(document).delegate('.clear-category','click',function(){
	$(this.parentNode).find('select option:first').attr("selected", "selected");
});

$(document).delegate('#save-category','click',function(){
	var items = [];
	var i = 0;
	$.each($('.main-category').find('select'), function( index, value ) {
	 	var item = $($('.main-category').find('select')[i]).val();
		items.push(item);
		i++;
	});
	$.post("$baseUrl/content/savecategory", {
		items: items,_csrf:$('meta[name="csrf-token"]').attr("content"),
		contentid:$(this).data('contentid')}, 
	function(result){
        	toastr.options.timeOut = 1500;
			toastr['success']('บันทึกข้อมูลเรียบร้อย');
  	});
	
});

$(document).delegate('#save-twitter','click',function(){
	var msg = $("#contentpublish-tweetcontent").val();
		
	$.post("$baseUrl/content/savecontentpublish", {tweetContent: msg, contentId:$(this).data('contentid')}, function(response){
        	if(typeof response == "string")
				var response = $.parseJSON(response);
			
			toastr.options.timeOut = 1500;
			toastr['success'](response.message);
  	});
});

$("#contentpublish-tweetcontent").on("change keyup paste", function(){
    var length = $("#contentpublish-tweetcontent").val().length;
	var availableChar = 140-length;
	$("#available-char").html(availableChar);
});

EOT;
$this->registerJs($str);

?>
<?php echo Html::csrfMetaTags();?>
<div class="row">
	<div class="col-md-12">
		<h3 class="form-section">
			<i class="fa fa-anchor"></i> การตีพิมพ์
		</h3>
		
		<div class="col-md-6">
			<div class="portlet box grey">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-reorder"></i>Twitter Publish</div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
					<div class="actions">
						<div class="btn-group">
							<a id="save-twitter" class="btn btn-sm green" href="javascript:;" data-toggle="dropdown" style="margin-right: 10px;" data-contentid="<?=$Content->id?>"> <i class="fa fa-save"></i> บันทึก</a>
						</div>
					</div>
				</div>
				<div class="portlet-body form">
				<div class="row">
					<div class="col-md-12 margin-top-20 margin-bottom-20">
						<div class="control-label col-md-2">
							<label for="tweetContent col-md-2">
								ข้อความ
							</label>
						</div>
						<div class="col-md-10">
							<?php echo Html::activeTextInput($contentPublish, 'tweetContent', array(
									'class'=>'form-control', 
									'placeholder'=>'',
									'size' => 60,
									'maxlength' => 140,
									)); 
							?>
							<span id="available-char" class="help-block pull-right"><?php echo (140 - strlen(utf8_decode($contentPublish->tweetContent)))?></span>
							<div><span class="help-block">Maxlength is 140 chars. </span></div>
						</div>
						
					</div>
				</div>
				
				<!-- <div class="row">
					<ul class="pager">
						<li>
							<a href="#" id="twitter-modal" data-toggle="modal"><i class="fa fa-cog"></i> แก้ไขข้อความ</a> <label > <i class="fa fa-warning"></i> (ปล่อยว่าง = ข้อความอัตโนมัติ)</label>
						</li>
					</ul>	
					<div id="twitter-modal-popup" class="modal fade" tabindex="-1" data-width="840">
						<div class="portlet box grey">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-reorder"></i>การตีพิมพ์</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body form">
								<div class="row">
									<div class="col-md-6 col-md-offset-4">
										<div class="form-group">
											
										</div>
										<div class="form-group">
											<div class="row">
												ข้อมูลการตีพิมพ์
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>-->
			</div>
		</div>
			
	</div>
		
		<div class="col-md-6">
			<div class="portlet box grey">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>หมวดเพิ่มเติม
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
					<div class="actions">
						<div class="btn-group">
							<a id="save-category" class="btn btn-sm green" href="javascript:;" data-toggle="dropdown" style="margin-right: 10px;" data-contentid="<?=$Content->id?>"> <i class="fa fa-save"></i> บันทึก</a>
							<a id="add-category" class="btn btn-sm green" href="javascript:;" data-toggle="dropdown"> <i class="fa fa-plus"></i> เพิ่มหมวด</a>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="other-category">
					<div class="row">
						<div class="col-md-12 margin-top-20">
							<div class="hide-form-category" style="display: none;">
								<div class="row">
									<div class="col-md-12 portlet-box-content">
										<div class="col-md-4">
											<label class="control-label pull-right">หมวดหมู่</label>
										</div>
										<div class="col-md-8">
											<div class="main-category">
												<a class="btn btn-xs green category-popup" id="ajax-demo" data-id="{id}" data-cat-id="cat_id_{id}" data-cat-title="cat_title_{id}" data-toggle="modal">เลือก..
													<i class="fa fa-link"></i>
												</a>
											</div>
										</div>
										<div id="ajax-modal" class="modal fade" tabindex="-1"></div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 portlet-box-content">
										<div class="col-md-4"></div>
										<div class="col-md-8">
											<input type="hidden" value="" name="cat_id_{id}" id="cat_id_{id}">
											<label id="cat_title_{id}">{category-title}</label>
										</div>
									</div>
								</div>
							</div>
				
							<!-- category item 1 -->
							<div class="show-category" id="other-category-1">
								<div class="row" id="items-category">
								<?php if($arrOtherCategory):?>
								<?php foreach ($arrOtherCategory as $index=> $data):?>
									<div class="col-md-12 portlet-box-content categoryClone" data-rank="<?php echo $index+1?>">
										<p></p>
										<div class="col-md-4">
											<label class="control-label pull-right" id="add-category">หมวดหมู่</label>
										</div>
										<div class="col-md-8">
											<div class="main-category">
												<?php echo Html::dropDownList('categoryId', $data->categoryId, [0=>'เลือกหมวดหมู่'] + CategoryTree::getAllRootNode(), ['id'=> '', 'class'=> 'form-control'])?>
												<a class="btn btn-sm yellow clear-category" data-id="1" data-cat-id="cat_id_1" data-cat-title="cat_title_1" data-action="clear" ><i class="fa fa-ban"></i> ล้างข้อมูล</a>
												<a class="btn btn-sm red delete-category" data-id="1" data-cat-id="cat_id_1" data-cat-title="cat_title_1" data-action="delete" ><i class="fa fa-minus"></i> ลบ</a>
											</div>	
										</div>
										<div id="ajax-modal" class="modal fade" tabindex="-1"></div>
									</div>
								<?php endforeach;?>
								<?php else:?>
									<div class="col-md-12 portlet-box-content categoryClone" data-rank="1">
										<p></p>
										<div class="col-md-4">
											<label class="control-label pull-right" id="add-category">หมวดหมู่</label>
										</div>
										<div class="col-md-8">
											<div class="main-category">
												<?php echo Html::dropDownList('categoryId', '', [0=>'เลือกหมวดหมู่'] + CategoryTree::getAllRootNode(), ['id'=> '', 'class'=> 'form-control'])?>
												<a class="btn btn-sm yellow clear-category" data-id="1" data-cat-id="cat_id_1" data-cat-title="cat_title_1" data-action="clear" ><i class="fa fa-ban"></i> ล้างข้อมูล</a>
												<a class="btn btn-sm red delete-category" data-id="1" data-cat-id="cat_id_1" data-cat-title="cat_title_1" data-action="delete" ><i class="fa fa-minus"></i> ลบ</a>
											</div>	
										</div>
										<div id="ajax-modal" class="modal fade" tabindex="-1"></div>
									</div>
								<?php endif;?>
								</div>
								<div class="row">
									<div class="col-md-12 portlet-box-content">
										<div class="col-md-4"></div>
										<div class="col-md-8">
							<?php 
								/* $categoryField = 0;
								$CategoryNode = null;
								if($arrObjectCategoryId){
									$categoryField = array_shift($arrObjectCategoryId);
									$CategoryNode = CategoryTree::getNode($categoryField);
								} */
								
							?>
							<?php //echo CHtml::hiddenField('cat_id_1', $categoryField)?>
											<label id="cat_title_1"><?php //if ($CategoryNode) echo $CategoryNode->getFullPath('TH')?></label>
										</div>
									</div>
								</div>
							</div>
				
				
				
				
									
						</div>
					</div>
				</div>
			</div>
			<!-- end Category -->
		
			
			
		</div>
		
				
	</div>
</div>


<?php //echo CHtml::endForm();?>

<?php 
/* $cs = Yii::app()->clientScript;
$js = <<<EOT
$('input:checkbox[id=emotion]').click(function() {
	//console.log($(this).val());
	if( $(this).is(':checked') ){
		$('#emotion-id-'+$(this).val()).removeClass('select-emotion');
	}else{
		$('#emotion-id-'+$(this).val()).addClass('select-emotion');		
	}
	
});
EOT;
$cs->registerScript('emotion-js', $js); */
?>