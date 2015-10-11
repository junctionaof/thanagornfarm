
<div class="table-scrollable">
	<table class="table table-striped table-bordered table-advance">
		<thead>
			<tr>
				<th>รหัส</th>
				<th>ชื่อเรื่อง</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody id="sortable2">
			<tr style="display:none" data-object="content" data-id="{id}">
				<td class="id">{id}</td>
				<td class="title">{title}</td>
				<td><a href="<?php //echo $this->controller->createUrl('edit', array('id' => '')) ?>{url}" class="btn default btn-xs <?php //echo Yii::app()->params['uiPortletHighlight']?>">
					<i class="fa fa-edit"></i> เลือก</a></td>
			</tr>
<?php
if($items):
	foreach ($items as $item ) :
	?>
			<tr data-id="<?php echo $item->id ?>" data-object="content">
				<td><?php echo $item->id ?></td>
				<td><?php echo $item->title ?></td>
				<td><a
					href="<?php //echo $this->controller->createUrl('edit', array('id' => $item->id)) ?>"
					class="btn default btn-xs <?php //echo Yii::app()->params['uiPortletHighlight']?>"><i
						class="fa fa-edit"></i> เลือก</a></td>
			</tr>
<?php
	endforeach;
endif;
?>	
						</tbody>
	</table>
</div>
