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
                        <li class="nav-item start active open">
                            <a href="<?php echo Url::toRoute('/') ?>" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">หน้าหลัก</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        <li class="heading">
                            <h3 class="uppercase">ข้อมูลบ่อเลี้ยงกุ้ง</h3>
                        </li>
                        <li class="nav-item  ">
                            <a href="<?php echo Url::toRoute('content/typelist') ?>" class="nav-link nav-toggle">
                                <i class="icon-diamond"></i>
                                <span class="title">จัดการบ่อ</span>
         
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="<?php echo Url::toRoute('content/list') ?>" class="nav-link nav-toggle">
                                <i class="icon-puzzle"></i>
                                <span class="title">จัดการรุ่นในบ่อเลี้ยง</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-bulb"></i>
                                <span class="title">บันทึกค่าต่างๆ</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="elements_steps.html" class="nav-link ">
                                        <span class="title">บันทึกการให้อาหาร</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="elements_lists.html" class="nav-link ">
                                        <span class="title">บันทึกหารเช็คยอ</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="elements_ribbons.html" class="nav-link ">
                                        <span class="title">บันทึกนํ้าหนักเฉลี่ย</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="portlet_boxed.html" class="nav-link ">
                                        <span class="title">บันทึกต่าออกซิเจนละลายนํ้า</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="portlet_light.html" class="nav-link ">
                                        <span class="title">บันทึกค่่า PH </span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="portlet_solid.html" class="nav-link ">
                                        <span class="title">บันทุกค่าอัลคาไลน์นิติ</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="portlet_ajax.html" class="nav-link ">
                                        <span class="title">บันทึกค่าอุณหฤูมมิของนํ้า</span>
                                    </a>
                                </li>
                                <li class="nav-item  ">
                                    <a href="portlet_draggable.html" class="nav-link ">
                                        <span class="title">บันทึกค่าอุณหฤูมมิของอากาศ</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="heading">
                            <h3 class="uppercase">จัดการระบบ</h3>
                        </li>
                        <li class="nav-item  ">
                            <a href="<?php echo Url::toRoute('user/list') ?>" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">จัดการผู้ใช้งานระบบ</span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-social-dribbble"></i>
                                <span class="title">Log การใช้งาน</span>
                            </a>
                            
                        </li>
                       
                       
                    </ul>
                    <!-- END SIDEBAR MENU -->
         </div>           
   </div>