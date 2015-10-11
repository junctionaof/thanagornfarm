<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="form-body">
	<div class="col-md-12">
		<div class="input-group">
			<?php echo Html::textInput('search-gallery', '', array('class'=>'form-control', 'placeholder'=>'คีย์เวิร์ด:'));?>
			<span class="input-group-btn">
					<a class="btn blue searchGallery" id="searchGallery" >ค้นหา</a>
			</span>
		</div>
	</div>
</div>
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-advance">
		<thead>
			<tr>
				<th>รหัส</th>
				<th>ชื่อเรื่อง</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody id="tbodyGallery">
			<tr style="display:none" data-object="content" data-id="{id}">
				<td>{id}</td>
				<td>{title}</td>
				<td>
					<a href="<?php //echo $this->controller->createUrl('gallery/edit', array('id' => '')) ?>{url}" class="btn default btn-xs <?php //echo Yii::app()->params['uiPortletHighlight']?>">
						<i class="fa fa-edit"></i> เลือก
					</a>
				</td>
			</tr>
<?php
if($items):
	foreach ($items as $item ) :
	?>
			<tr class="galleryRow" data-id="<?php echo $item->id ?>" data-object="content" data-type="gallery">
				<td><?php echo $item->id ?></td>
				<td><?php echo $item->title ?></td>
				<td>
					<a href="<?php //echo $this->controller->createUrl('gallery/edit', array('id' => $item->id)) ?>" class="btn default btn-xs <?php //echo Yii::app()->params['uiPortletHighlight']?>"><i class="fa fa-edit"></i> เลือก</a>
				</td>
			</tr>
<?php
	endforeach;
	endif;
?>	
		</tbody>
	</table>
</div>