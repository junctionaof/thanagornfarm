<?php use app\SectionConfig;?>
<?php $arrConfig = $sectionConfig;?>
<div class=" portlet-tabs">
	<ul class="nav nav-tabs" id="tablist">
		<!-- <li class="active"><a href="#portlet_tab1" data-toggle="tab">ข่าว</a></li>
		<li><a href="#portlet_tab2" data-toggle="tab">แกลอรี่</a></li>
		<li><a href="#portlet_tab3" data-toggle="tab">วีดีโอ</a></li> -->
		
		<?php
		if(!empty($arrConfig[$_REQUEST['section']])):
		foreach ($arrConfig[$_REQUEST['section']] as $index=>$data):?>
			<?php 
			if(!empty($data[3])):
				foreach ($data[3] as $keyindex=> $key):?>
					<li class="<?php echo $keyindex==0?'active':'';?>" data-tabtype="<?php echo $data[1];?>"><a href="#portlet_tab_<?php echo $key?>" data-tabtype="<?php echo $key?>"  data-toggle="tab" style="display:none;"><?php echo SectionConfig::$arrNameCategory[$key]?></a></li>
				<?php endforeach;
			endif;?>
	<?php endforeach;
		endif;
	?>
	</ul>
	<div class="tab-content" id="itemlist">
	<?php foreach (SectionConfig::$arrCategory as $index => $data):?>
		<div class="tab-pane <?php echo $index==0?'active':''?>" id="portlet_tab_<?php echo $data?>">
			
			<div class="form-group">
				<label for="exampleInputEmail1"></label>
				<input type="email" class="form-control" data-searchtype="<?php echo $data?>" name="q" placeholder="ค้นหา..">
			</div>
			
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>รหัส</th>
							<th>เรื่อง</th>

						</tr>
					</thead>
					<tbody class="sortable2" id="items-<?php echo $data?>">
						<tr class="liClone" style="display: none;" data-type="<?php echo $data?>">
							<td class="showid"></td>
							<td class="">
								<p class="iconRemove" style="display: none;"><a href="javascript:;" id="iconRemove"><i  class="fa fa-minus-square"></i></a></p>
								<p class="showname" >{ ยังไม่มีข้อมูล }</p>
							</td>
						</tr>					
					</tbody>
				</table>
			</div>				
				
		</div>
	<?php endforeach;?>
	</div>
</div>