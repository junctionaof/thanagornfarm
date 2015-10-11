<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* $cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$js = <<<EOT

EOT;
$cs->registerScript('youtubeChoose', $js); */
?>
<script type="text/javascript">
$( "body" ).delegate( ".saveEmbed", "click", function( event ) {	
	var id = $('body').find('#content-main').attr('data-id');
	var title = $('#titleEmbed').val();
	var content = tinyMCE.activeEditor.getContent();
	var status = $('#Embed_status').val();
	$.get(App.baseUri + 'content/saveEmbed', {
					id: id,
					title: title,
					content: content,
					status: status
	}).done(function(data){
		toastr.options.timeOut = 1500;
		toastr[data.alert](data.response);
	});	
});
</script>
<style>
.iframeYoutube{
padding: 15px;
background-color: #f3f3f3;
margin-top:15px;
}
#embed-content_ifr{
height:400px !important;
}
.embedSource{
padding: 15px 0 15px 0;
}
.saving{
left: 50%;
bottom: auto;
right: auto;
padding: 0;
width: 600px;
margin-left: -250px;
background-color: transparent !important;
border: 0px !important;
border: 0px !important;
border-radius: 6px;
-webkit-box-shadow: 0 0px 0px rgba(0, 0, 0, 0.5) !important;
box-shadow: 0 0px 0px rgba(0, 0, 0, 0.5) !important;
background-clip: padding-box;
</style>
<div class="row">
	<div class="col-md-12">
		<?php echo Html::textInput('title', '', array('id' => 'titleEmbed','class'=>'form-control', 'placeholder'=>'ชื่อไตเติ้ลของEMBED'));?>
		<div class="embedSource">
		<textarea rows="20"  class="form-control tinymce-enabled" id="embed-content" name="embed"><?php //echo $items->content?></textarea>
		</div>
		<div class="row">
			<div class="col-md-12">
				<label class="control-label pull-left" style="padding-right:15px;">การแสดงผล:</label>
				<div class="pull-left" style="padding-right:10px;">
					<?php //echo CHtml::activeDropdownList($items,'status',Workflow::$arrStatus, array('class'=>'form-control form-control-inline input-medium', 'data-placeholder'=>'Select...')); ?>
				</div>
				<div class="pull-left">
					<a href="javascript:;" class="btn btn green saveEmbed">บันทึก</a>
					<a href="javascript:;" class="btn btn blue hilightEmbed">ใช้เป็นจั่ว</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade saving" id="basic" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body text-center">
					<div class="pull-right">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					</div>
					<img src="<?php //echo $baseUrl?>/assets/img/input-spinner.gif"> <span style="font-size:16px;font-weight:bold;">กรุณารอสักครู่ ระบบกำลังประมวลผล...</span>
				</div>
			</div>
		</div>
</div>