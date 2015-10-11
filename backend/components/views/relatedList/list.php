<div class="table">
	<table class="table table-striped table-bordered">
		<tbody id="sortable1">
			<tr style="display:none" data-object="content" data-id="{id}">
				<td><a href="javascript:;" class="pull-left"> {thumbnail}
				</a></td>
				<td>
					<p> {id}. {title}</p>
					<p>ดู: {view} <a href="javascript:;" class="itemdRemove"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
				</td>
			</tr>
			<tr style="display:none" data-object="content" data-id="0" class="trClone">
				<td><a href="#" class="pull-left"> <img src="http://placehold.it/100x60" class="media-object">
				</a></td>
				<td>
					<p>0. [ยังไม่มีรายการ]</p>
					<p>ดู: 0</p>
				</td>
			</tr>
<?php
if($items):
	$previewOptions = array(
			//Media::ENCODE_WIDTH => 100,
	);

foreach ( $items as $index => $obj ) :
	$item = $obj->attributes;
?>
			<tr data-object="content" data-id="<?php echo $item['id']?>">
				<td>
					<a href="javascript:;" class="pull-left">
						<img src="http://placehold.it/100x60" class="media-object">
					<?php 
						//echo $obj->getPreview($previewOptions)?$obj->getPreview($previewOptions):'<img src="http://placehold.it/100x60" class="media-object">';
					?>
					</a>
				</td>
				<td>
					<p><?php echo $item['id'] ?>. <?php echo $item['title'] ?></p>
					<p>ดู: <?php echo $item['viewCount'] ?> <a href="javascript:;" class="itemdRemove"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
				</td>
			</tr>
<?php
endforeach;
else:
?>
			<tr data-object="content" data-id="0" class="trClone">
				<td><a href="#" class="pull-left"> <img src="http://placehold.it/100x60" class="media-object">
				</a></td>
				<td>
					<p>0. [ยังไม่มีรายการ]</p>
					<p>ดู: 0</p>
				</td>
			</tr>
<?php 
endif;
?>	
		</tbody>
	</table>
</div>