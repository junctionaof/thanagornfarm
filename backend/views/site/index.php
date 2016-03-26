<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\web\View;
use common\models\Pond;
use app\DateUtil;
use app\Workflow;
use common\models\ObjectCategory;
use app\CategoryTree;
use common\models\Media;
use app\Entity;
/* @var $this yii\web\View */
$baseUrl = \Yii::getAlias('@web');
$user = \Yii::$app->user;
$session = \Yii::$app->session;
$this->title = 'home thanagornfarm ';
$identity = \Yii::$app->user->getIdentity();
?>

 					<!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span class="active">รายการบ่อ และรุ่นทั้งหมด</span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->

					<div class="row">
					
					<?php foreach ($arrPorn as $value) { ?>
					
                          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat blue">
                                <div class="visual">
                                    <i class="fa fa-comments"></i>
                                </div>
                                <div class="details">
                                    <div class="number">
                                        <span data-counter="counterup" data-value="<?php echo $value["larvae"]; ?>">0</span> ตัว</div>
                                    <div class="desc"><?php echo $value["typelist"]->name; ?>   </div>
                                </div>
                                <a class="more" href="<?= Url::toRoute(['pond/shrimp'])?>?id=<?=$value["typeId"]?>"> View more
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </a>
                            </div>
                        </div>
					

					<?php }?>

                    </div>
                   