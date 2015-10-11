<?php 
use backend\assets\FileuploadAsset;
use yii\helpers\Html;

FileuploadAsset::register($this);
?>

<?php if($useForm): ?>
<form id="fileupload" action="" method="POST" enctype="multipart/form-data" class="fileupload-processing">
<?php endif;?>
<?php
if (is_array($params)) {
	foreach($params as $key => $val) {
		echo Html::hiddenInput($key, $val);
	}
} 

?>

<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

	<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
	<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn green fileinput-button">
                    <i class="fa fa-plus"></i>
                    <span>เลือกไฟล์... </span>
                    <input type="file" name="files[]" multiple="">
                </span>
                <button type="submit" class="btn blue start">
                    <i class="fa fa-upload"></i>
                    <span>อัพโหลดไฟล์</span>
                </button>
                <!--
                <button type="reset" class="btn warning cancel">
                    <i class="fa fa-ban-circle"></i>
                    <span>
                        Cancel upload </span>
                </button>
                -->
                <button type="button" class="btn red delete">
                    <i class="fa fa-trash"></i>
                    <span>ยกเลิก</span>
                </button>
                <!-- <input type="checkbox" class="toggle"> -->
                <!-- The global file processing state -->
                <!-- <span class="fileupload-process"></span> -->
            </div>
            <!-- The global progress information -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;">
                    </div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">
                    &nbsp;
                </div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped clearfix">
            <tbody class="files">
            </tbody>
        </table>
<?php if($useForm):?>
</form>
<?php endif;?>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger label label-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button  class="btn btn-primary start hidden" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn red cancel">
                    <i class="fa fa-ban"></i>
                    <span>ลบ</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script id="template-download" type="text/x-tmpl">

</script>