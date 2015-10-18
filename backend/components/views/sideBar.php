<?php
use yii\helpers\Url;
use common\models\User;
use yii\helpers\Html;
use app\SectionConfig;
$identity = \Yii::$app->user->getIdentity();
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;
$myUrl = $_SERVER['REQUEST_URI'];
$template = array(
		'active_page'   => $myUrl
);
?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
	<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
	<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
	<div class="page-sidebar navbar-collapse collapse">
		<!-- BEGIN SIDEBAR MENU -->
		<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
		<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
		<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
		<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<?php if ($primary_nav) { ?>
		<ul class="page-sidebar-menu page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
			<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
			<li class="divider" style="margin-top: 20px;"></li>
			<li class="sidebar-toggler-wrapper">
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<div class="sidebar-toggler">
				</div>
				<!-- END SIDEBAR TOGGLER BUTTON -->
			</li>
			<li class="divider" style="margin-top: 10px;"></li>
			 <?php foreach ($primary_nav as $key => $link) {
	                                $link_class = '';
	                                // Get link's vital info
	                                $url = (isset($link['url']) && $link['url']) ? $link['url'] : '#';
	                                $active = (isset($link['url']) && ($template['active_page'] == $link['url'])) ? 'active' : '';
	                                $icon = (isset($link['icon']) && $link['icon']) ? '<i class="fa ' . $link['icon'] . '"></i>' : '';
	                                // Check if we need add the class active to the li element (only if a sublink is active)
	                                $li_active = '';
	                                $menu_link = '';
	                                
	                                if (isset($link['sub']) && $link['sub']) {
	                                    foreach ($link['sub'] as $sub_link) {
	                                        if (in_array($template['active_page'], $sub_link)) {
	                                            $li_active = ' class="active"';
	                                            break;
	                                        }
	                                        // Check and sublinks for active class if they exist
	                                        if (isset($sub_link['sub']) && $sub_link['sub']) {
	                                            foreach ($sub_link['sub'] as $sub2_link) {
	                                                if (in_array($template['active_page'], $sub2_link)) {
	                                                    $li_active = ' class="active"';
	                                                    break;
	                                                }
	                                            }
	                                        }
	                                    }
	
	                                    $menu_link = 'menu-link';
	                                }
	
	                                if ($menu_link || $active)
	                                    $link_class = ' class="'. $menu_link . $active .'"';
	                                $li_active_m = "";
	                                
	                                if ($active) 
	                                	$li_active_m = ' class="'. $active .'"'; 	                              
	                            ?>
	                            <?php   if (isset($link['rule'])){  //เช็คค่าที่ดึงมาจาก  array = echo $sub_link['rule']; ?>
	                            <li<?php if ($active){echo $li_active_m;}else {echo $li_active;}?>>
	                                <a href="<?php echo $url; ?>"<?php echo $link_class; ?>>
	                                <?php echo $icon; ?>
	                                 <?php if (isset($link['sub'])) { ?>
	                                     	<span class="arrow"></span>
	                                     	 <span class="selected"></span>
	                                 <?php } ?>  	
	                                <span class="title"><?php echo $link['name']; ?></span></a>
	                                <?php if (isset($link['sub']) && $link['sub']) { ?>
	                                    <ul class="sub-menu">
	                                        <?php foreach ($link['sub'] as $sub_link) {
	                                            $link_class = '';
	                                            // Get sublink's vital info
	                                            $url = (isset($sub_link['url']) && $sub_link['url']) ? $sub_link['url'] : '#';
	                                            $active = (isset($sub_link['url']) && ($template['active_page'] == $sub_link['url'])) ? ' active' : '';
	                                            $subicon = (isset($sub_link['icon']) && $sub_link['icon']) ? '<i class="fa ' . $sub_link['icon'] . '"></i>' : '';
	                                            // เพิ่ม คลาส Active เฉพาะ   sub_link 
	                                            $li2_active = '';
	                                            $submenu_link = '';
	                                            if (isset($sub_link['sub']) && $sub_link['sub']) {
	                                                foreach ($sub_link['sub'] as $sub2_link) {
	                                                    if (in_array($template['active_page'], $sub2_link)) {
	                                                        $li2_active = ' class="active"';
	                                                        break;
	                                                    }
	                                                }
	                                                $submenu_link = 'submenu-link';
	                                            }
	                                            if ($submenu_link || $active)
	                                                $link_class = ' class="'. $submenu_link . $active .'"';?>
	                                        <li<?php echo $link_class; ?>>
	                                            <a href="<?php echo $url; ?>"<?php echo $link_class; ?>><?php echo $subicon; ?><?php echo $sub_link['name']; ?></a>
	                                            <?php if (isset($sub_link['sub']) && $sub_link['sub']) { ?>
	                                                <ul>
	                                                    <?php foreach ($sub_link['sub'] as $sub2_link) {
	                                                        // Get vital info of sublinks
	                                                        $url = (isset($sub2_link['url']) && $sub2_link['url']) ? $sub2_link['url'] : '#';
	                                                        $active = (isset($sub2_link['url']) && ($template['active_page'] == $sub2_link['url'])) ? ' class="active"' : '';
	                                                    ?>
	                                                    <li>
	                                                        <a href="<?php echo $url; ?>"<?php echo $active ?>><?php echo $sub2_link['name']; ?></a>
	                                                    </li>
	                                                    <?php } ?>
	                                                </ul>
	                                            <?php } ?>
	                                        </li>
	                                       
	                                        <?php } ?>
	                                    </ul>
	                                <?php } ?>
	                            </li>
	                          <?php } ?>
	                        <?php } ?>
			</ul>
		 <?php } ?>
		<!-- END SIDEBAR MENU -->
	</div>
</div>
<!-- END SIDEBAR -->
