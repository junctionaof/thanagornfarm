<?php
/* @var $this yii\web\View */
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;
$session = \Yii::$app->session;
$this->title = 'about thanagornfarm ';
$this->registerCssFile($baseUrl  . '/assets/pages/css/contact.min.css') ;

$this->registerJsFile('http://maps.google.com/maps/api/js?sensor=false', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl. '/assets/global/plugins/gmaps/gmaps.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile($baseUrl. '/assets/pages/scripts/contact.js', ['position' => \yii\web\View::POS_END]);
?>

 <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span class="active">Contact</span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="c-content-contact-1 c-opt-1">
                        <div class="row" data-auto-height=".c-height">
                            <div class="col-lg-8 col-md-6 c-desktop"></div>
                            <div class="col-lg-4 col-md-6">
                                <div class="c-body">
                                    <div class="c-section">
                                        <h3>ธนกรฟาร์ม</h3>
                                    </div>
                                    <div class="c-section">
                                        <div class="c-content-label uppercase bg-blue">Address</div>
                                        <p>25, Lorem Lis Street,
                                            <br/>Orange C, California,
                                            <br/>United States of America</p>
                                    </div>
                                    <div class="c-section">
                                        <div class="c-content-label uppercase bg-blue">Contacts</div>
                                        <p>
                                            <strong>T</strong> 800 123 0000
                                            <br/>
                                            <strong>F</strong> 800 123 8888</p>
                                    </div>
                                    <div class="c-section">
                                        <div class="c-content-label uppercase bg-blue">Social</div>
                                        <br/>
                                        <ul class="c-content-iconlist-1 ">
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-twitter"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-facebook"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-youtube-play"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-linkedin"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="gmapbg" class="c-content-contact-1-gmap" style="height: 615px;"></div>
                    </div>

            <!-- END QUICK SIDEBAR -->