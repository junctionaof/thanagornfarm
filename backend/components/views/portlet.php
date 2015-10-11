<?php $actionInfo =[];?>
<div id="<?php echo $id ?>" class="portlet<?php echo $themeClass ?>"<?php

foreach($attrs as $key=>$val) {
	echo " $key=\"$val\"";
} 
?>>
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-<?php echo $iconClass?>"></i> <?php echo $title ?></div>
<?php 
if (!empty($actions)) :
?>
		<div class="actions">
<?php
foreach ($actions as $key=>$value) :
	if (is_array($value)) :
		// dropdown menu
?>
	<div class="btn-group">
		<a class="btn <?php echo \Yii::$app->params['uiPortletHighlight']?>" href="javascript:;" data-toggle="dropdown"> <?php echo $key ?> <i class="fa fa-angle-down"></i>
		</a>
		<ul class="dropdown-menu pull-right">	
<?php
		foreach($value as $key2 => $value2) :
		if (is_numeric($key2)) {
			$key2 = $value2;
			$actionInfo = $arrActionMap[$key2];
		}
?>
				<li><a href="javascript:;"  data-action="<?php echo $key2?>"><i class="fa fa-<?php echo $actionInfo[0]?>"></i> <?php echo $actionInfo[1]?></a></li>
<?php 		
		endforeach;
?>
			</ul>
		</div>	
<?php		
	else :
	// menu button
	if (is_numeric($key)) {
		$key = $value;
		$actionInfo = $arrActionMap[$key];
	}
?>
			<a href="javascript:;" class="btn <?php echo \Yii::$app->params['uiPortletHighlight']?>" data-action="<?php echo $key?>"><i class="fa fa-<?php echo $actionInfo[0]?>"></i> <?php echo $actionInfo[1]?></a>
<?php
	endif;
endforeach;
?>
		</div>
<?php 
endif;
?>
	</div>
	<div class="portlet-body">

<?php echo $content?>

<?php  if (!empty($actionInfo)) :
		if($actionInfo[0] == "save"):?>
<div id="showrow" class="text-center margin-bottom-10">
		
</div>
<?php endif; 
endif;?>

	</div>
</div>