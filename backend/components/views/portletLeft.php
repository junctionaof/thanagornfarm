<div class="table-responsive">
	<table class="table table-bordered table-hover">
		<tbody id="sortable1">	

			<tr style="display:none" data-object="content" data-content-id="{id}">
				<td><a href="javascript:;" class="pull-left"> {thumbnail}
				</a></td>
				<td>
					<p id="id-title"> {id}. {title}</p>
					<p id="viewCount">ดู: {view} <a href="javascript:;" class="itemdRemove" id="iconRemove"> <i class="fa fa-trash-o" title="ลบ"></i> </a></p>
				</td>
			</tr>
			<tr id="NotFoundData" style="display:none" data-object="content" data-content-id="0" class="liClone">
				<td><a href="#" class="pull-left"> <img src="http://placehold.it/100x60" class="media-object">
				</a></td>
				<td>
					<p>0. [ยังไม่มีรายการ]</p>
					<p>ดู: 0</p>
				</td>
			</tr>
		</tbody>
	</table>
	<p id="numberItems" style="text-align: center;"></p>
	<input type="hidden" id="numberItemsConfig">
</div>