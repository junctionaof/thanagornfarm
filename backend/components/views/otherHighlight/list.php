<div class="table" id="otherHighlight">
	<table class="table table-striped table-bordered">
		<tbody>
			<tr style="display:none" data-object="content" data-id="{id}">
				<td><a href="javascript:;" class="pull-left"> {thumbnail}
				</a></td>
				<td>
					<p> {id}. {title}</p>
					<p>ดู: {view} <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
				</td>
			</tr>
<?php
if($items):
	$previewOptions = array(
			Media::ENCODE_WIDTH => 100,
	);
?>
		<?php if ($type == Entity::TYPE_EMBED) {?>
			<tr class="trRow" data-object="content" data-id="<?php echo $items['refId']?>">
				<td>
					<a href="javascript:;" class="pull-left">
					<img src='http://img.youtube.com/vi/%3Cinsert-youtube-video-id-here%3E/sddefault.jpg'>
					</a>
				</td>
				<td>
					<p><?php echo $items['refId'] ?>. <?php echo $items['title'] ?></p>
					<p>ดู:  <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
				</td>
			</tr>
		<?php }else{?>
			<tr class="trRow" data-object="content" data-id="<?php echo $items['id']?>">
				<td>
					<a href="javascript:;" class="pull-left">
					<?php echo $items->getPreview($previewOptions);?>
					</a>
				</td>
				<td>
					<p><?php echo $items['id'] ?>. <?php echo $items['title'] ?></p>
					<p>ดู: <?php echo $items['viewCount'] ?> <a href="javascript:;"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
				</td>
			</tr>
		<?php }?>
<?php
else:
?>
			<tr data-object="content" data-id="0">
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