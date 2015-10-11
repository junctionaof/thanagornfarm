<?php
$baseUrl = \Yii::getAlias ( '@web' );
use yii\helpers\Html;
/* $this->registerJsFile ( $baseUrl . '/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js', [
		'position' => \yii\web\View::POS_END
		] );
$this->registerJsFile ( $baseUrl . '/assets/plugins/bootstrap/js/bootstrap2-typeahead.js', [
		'position' => \yii\web\View::POS_END
		] );
$this->registerCssFile ( $baseUrl . '/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css' );
$this->registerCssFile ( $baseUrl . '/assets/plugins/jquery-typeahead/css/jquery.typeahead.css' ); */


//$cs->registerCssFile($appBaseUrl . '/assets/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css');

$this->registerJsFile($baseUrl.'/global/tinymce-2/js/tinymce/tinymce.min.js');
$this->registerJsFile($baseUrl.'/global/scripts/tinymce_init.js');
$this->registerJsFile('http://console.qoder.tpbs.ndev.pw/sdk/qoder.js');
/* $cs->registerScriptFile($appBaseUrl . '/assets/scripts/tinymce_init.js', CClientScript::POS_END);
$cs->registerScriptFile($appBaseUrl . '/assets/scripts/popup-twitter.js', CClientScript::POS_END);
$cs->registerScriptFile($appBaseUrl  . '/assets/plugins/bootstrap-modal/js/bootstrap-modal.js', CClientScript::POS_END);
$cs->registerScriptFile($appBaseUrl  . '/assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js', CClientScript::POS_END); */

?>
<?php if(false):?>
<script type="text/javascript" src="http://console.qoder.tr1.tpbs.inox.co.th/sdk/qoder.js"></script>
<script type="text/javascript" src="http://console.qoder.tr1.tpbs.inox.co.th/sdk/tinymce-qoder.js"></script>
<script>
        qoder.config.url = 'http://console.qoder.tr1.tpbs.inox.co.th';
</script>
<?php endif;?>
<style>
    .conten-pos-mo{
        top: 10% !important;
        left: 40% !important;
    }
</style>

<div id="embeded-media-items" class="modal conten-pos-mo" tabindex="-1">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>แทรกภาพประกอบ</h3>
	</div>
	<div class="modal-body">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>รหัส</th>
					<th>รูป</th>
					<th>Caption</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr style="display:none" data-object="{objectType}" data-id="{refId}">
					<td>{id}</td>
					<td>
						<a href="javascript:;" class="pull-left"> <img alt="" src="<?php echo $baseUrl?>/media/{thumbnail}" class="media-object"> </a>
					</td>
					<td>{title}</td>
					<td><a href="javascript:;" data-refId="{id}" data-itemNo="{itemNo}" data-caption="{caption}" data-source="{fullPath}" data-object="{objectType}" class="media-select btn default btn-xs">
						<i class="fa fa-edit"></i> เลือก</a></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn">ปิด</button>
	</div>
</div>


<div id="embeded-doc-items" class="modal conten-pos-mo" tabindex="-1">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>แทรกภาพประกอบ</h3>
	</div>
	<div class="modal-body">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>รหัส</th>
					<th>ประเภท</th>
					<th>ชื่อ</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr style="display:none" data-object="{objectType}" data-id="{refId}">
					<td>{id}</td>
					<td>
					
						<a href="javascript:;" class="pull-left"> <img alt="" src="<?php echo $baseUrl?>/global/img/{thumbnail}" class="media-object" width="30" height="30"> </a>
					</td>
					<td>{title}</td>
					<td><a href="javascript:;" data-refId="{id}" data-itemNo="{itemNo}" data-caption="{caption}" data-source="{fullPath}" data-object="{objectType}" class="media-select btn default btn-xs">
						<i class="fa fa-edit"></i> เลือก</a></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn">ปิด</button>
	</div>
</div>

<div class="row">
	<div id="social-media" class="modal conten-pos-mo hide fade" tabindex="-1" data-width="760">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3>แทรก Social Feed</h3>
	  </div>
	  <div class="modal-body">
	    <div class="row">
		    <div id="table-image-listdata">
				<table class="table table-striped table-bordered table-advance">
					<thead>
						<tr>
							<th>รหัส</th>
							<th>รูป</th>
							<th>ชื่อรูป</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr style="display:none" data-object="{objectType}" data-id="{refId}">
							<td>{id}</td>
							<td>
								<a href="javascript:;" class="pull-left"> <img alt="" src="<?php echo $baseUrl?>/media/{thumbnail}" class="media-object"> </a>
							</td>
							<td>{title}</td>
							<td><a href="javascript:;" data-refId="{id}" data-itemNo="{itemNo}"  class="media-select btn default btn-xs">
								<i class="fa fa-edit"></i> เลือก</a></td>
						</tr>
					</tbody>
				</table>
			</div>
	    </div>
	  </div>
	  <div class="modal-footer">
	    <button type="button" data-dismiss="modal" class="btn">Close</button>
	  </div>
	</div>
</div>

<div class="modal conten-pos-mo" id="embed-popup-items">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3>แทรกเนื้อหา</h3>
	  </div>
	  <div class="modal-body">
	  	<div class="row">
			<div class="col-md-5 col-md-offset-7">
				<div class="input-group">
					<?php //echo CHtml::textField('search-key', '', array('class'=>'form-control', 'placeholder'=>'คำค้น...'));?>
						<input type="text" name="q" id="search-key" class="form-control" placeholder="คำค้น..">
					<span class="input-group-btn">
						<?php //echo CHtml::button('ค้นหา', array('class'=>'btn blue', 'name'=>'btn-search', 'id'=>'btn-search'))?>
						<button type="button" id="btn-search" class="btn blue">ค้นหา..</button>
					</span>
				</div>
					<!-- /input-group -->
			</div>
		</div>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>รหัส</th>
					<th>ภาพ</th>
					<th>หัวเรื่อง</th>
					<th>ตัวเลือก</th>
				</tr>
			</thead>
			<tbody>
				<tr style="display:none" data-object="{objectType}" data-id="{id}">
					<td>{id}</td>
					<td>
						<a href="javascript:;" class="pull-left"> {thumbnail} </a>
					</td>
					<td>{title}</td>
					<td><a href="javascript:;" class="btn default btn-xs">
						<i class="fa fa-edit"></i> เลือก</a></td>
				</tr>
			</tbody>
		</table>
		</div>
		<div class="modal-footer">
	    <button type="button" data-dismiss="modal" class="btn">Close</button>
	  </div>
</div>


<div class="modal conten-pos-mo" id="embed-video-external">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3>แทรกวีดีโอ</h3>
	  </div>
	  <div class="modal-body">
	  	<div class="row">
			<div class="col-md-5 col-md-offset-7">
				<div class="input-group">
					<?php //echo CHtml::textField('search-key', '', array('class'=>'form-control', 'placeholder'=>'คำค้น...'));?>
						<input type="text" name="q" id="searchvideo-key" class="form-control" placeholder="คำค้น..">
					<span class="input-group-btn">
						<?php //echo CHtml::button('ค้นหา', array('class'=>'btn blue', 'name'=>'btn-search', 'id'=>'btn-search'))?>
						<button type="button" id="btn-search" class="btn blue">ค้นหา..</button>
					</span>
				</div>
					<!-- /input-group -->
			</div>
		</div>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>รหัส</th>
					<th>ภาพ</th>
					<th>หัวเรื่อง</th>
					<th>ตัวเลือก</th>
				</tr>
			</thead>
			<tbody>
				<tr style="display:none" data-object="{objectType}" data-id="{id}" data-embed"{embed}">
					<td>{id}</td>
					<td>
						<a href="javascript:;" class="pull-left"> {thumbnail} </a>
					</td>
					<td>{title}</td>
					<td><a href="javascript:;" class="btn default btn-xs">
						<i class="fa fa-edit"></i> เลือก</a></td>
				</tr>
			</tbody>
		</table>
		</div>
		<div class="modal-footer">
	    <button type="button" data-dismiss="modal" class="btn">Close</button>
	  </div>
</div>