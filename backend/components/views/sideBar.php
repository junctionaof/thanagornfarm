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
<div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
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
                     <ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        <li class="nav-item start <?php echo $myUrl == Url::toRoute('/')?'active open':'';?>">
                            <a href="<?php echo Url::toRoute('/') ?>" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">หน้าหลัก</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="heading">
                            <h3 class="uppercase">ข้อมูลบ่อเลี้ยงกุ้ง</h3>
                        </li>
                        <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/typelist') || $myUrl == Url::toRoute('pond/edittypelist')?'active':'';?> ">
                            <a href="<?php echo Url::toRoute('pond/typelist') ?>" class="nav-link nav-toggle">
                                <i class="icon-diamond"></i>
                                <span class="title">จัดการบ่อ</span>
         
                            </a>
                        </li>
                        <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/list') || $myUrl == Url::toRoute('pond/edit')?'active':'';?> ">
                            <a href="<?php echo Url::toRoute('pond/list') ?>" class="nav-link nav-toggle">
                                <i class="icon-puzzle"></i>
                                <span class="title">จัดการรุ่นในบ่อเลี้ยง</span>
                            </a>
                        </li>
                        <li class="heading">
                            <h3 class="uppercase">บันทึกค่าต่างๆ</h3>
                        </li>
                                <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/food') || $myUrl == Url::toRoute('pond/editfood')?'active':'';?>  ">
                                    <a href="<?php echo Url::toRoute('pond/food') ?>" class="nav-link ">
                                    	<i class="fa fa-angle-right"></i>
                                        <span class="title"> บันทึกการให้อาหาร</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/checkyo') || $myUrl == Url::toRoute('pond/editcheckyo')?'active':'';?>  ">
                                    <a href="<?php echo Url::toRoute('pond/checkyo') ?>" class="nav-link ">
                                    <i class="fa fa-angle-right"></i>
                                        <span class="title"> บันทึกการเช็คยอ</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/weight') || $myUrl == Url::toRoute('pond/editweight')?'active':'';?>  ">
                                    <a href="<?php echo Url::toRoute('pond/weight') ?>" class="nav-link ">
                                    <i class="fa fa-angle-right"></i>
                                        <span class="title"> บันทึกนํ้าหนักเฉลี่ย</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/oxygen') || $myUrl == Url::toRoute('pond/editoxygen')?'active':'';?>  ">
                                    <a href="<?php echo Url::toRoute('pond/oxygen') ?>" class="nav-link ">
                                    <i class="fa fa-angle-right"></i>
                                        <span class="title"> บันทึกต่าออกซิเจนละลายนํ้า</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/ph') || $myUrl == Url::toRoute('pond/editph')?'active':'';?>  ">
                                    <a href="<?php echo Url::toRoute('pond/ph') ?>" class="nav-link ">
                                    <i class="fa fa-angle-right"></i>
                                        <span class="title"> บันทึกค่่า PH </span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/alkalinity') || $myUrl == Url::toRoute('pond/editalkalinity')?'active':'';?>  ">
                                    <a href="<?php echo Url::toRoute('pond/alkalinity') ?>" class="nav-link ">
                                    <i class="fa fa-angle-right"></i>
                                        <span class="title"> บันทึกค่าอัลคาไลน์นิติ</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/watertemp') || $myUrl == Url::toRoute('pond/editwatertemp') ?'active':'';?>  ">
                                    <a href="<?php echo Url::toRoute('pond/watertemp') ?>" class="nav-link ">
                                    <i class="fa fa-angle-right"></i>
                                        <span class="title"> บันทึกค่าอุณหฤูมมิของนํ้า</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php echo $myUrl == Url::toRoute('pond/temp') || $myUrl == Url::toRoute('pond/edittemp') ?'active':'';?>  ">
                                    <a href="<?php echo Url::toRoute('pond/temp') ?>" class="nav-link ">
                                    <i class="fa fa-angle-right"></i>
                                        <span class="title">บันทึกค่าอุณหฤูมมิ</span>
                                    </a>
                                </li>
                        <li class="heading">
                            <h3 class="uppercase"> ข้อมูลฟาร์ม</h3>
                        </li>        
                         <li class="nav-item  <?php echo $myUrl == Url::toRoute('site/about')?'active':'';?> ">
                            <a href="<?php echo Url::toRoute('site/about') ?>" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title"> เกี่ยวกับเรา</span>
                            </a>
                        </li>
                         <li class="nav-item  <?php echo $myUrl == Url::toRoute('site/contact')?'active':'';?> ">
                            <a href="<?php echo Url::toRoute('site/contact') ?>" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title"> ติดต่อเรา</span>
                            </a>
                        </li>
                        <li class="heading">
                            <h3 class="uppercase"> จัดการระบบ</h3>
                        </li>
                        <li class="nav-item  <?php echo $myUrl == Url::toRoute('user/list')?'active':'';?> ">
                            <a href="<?php echo Url::toRoute('user/list') ?>" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title"> จัดการผู้ใช้งานระบบ</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-social-dribbble"></i>
                                <span class="title"> Log การใช้งาน</span>
                            </a>
                            
                        </li>
                       
                       
                    </ul>
                    <!-- END SIDEBAR MENU -->
         </div>           
   </div>