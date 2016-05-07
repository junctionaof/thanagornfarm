<?php 
use common\models\Payment;
use common\models\UseProgramItem;
use common\models\NewspaymentItem;
use common\models\SubType;
use common\models\Province;

use yii\helpers\Url;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\web\View;
use app\DateUtil;
use app\Ui;
use yii\rest\IndexAction;
use backend\controllers\PaymentController;
$baseUrl = \Yii::getAlias('@web');


$user = \Yii::$app->user;
$session = \Yii::$app->session;
$st = "";
$Date = date('Y-m-d', time());
$allcolum[] = '';
$allamount[] = '';
$amountItem[] ='';
$totalamount[] = 0;
$totalamountItem[] = 0;
$totalcolumprint = '0';
$totalamountprint = '0';
$day = '';
/* $stday =  DateUtil::th_date('j F Y',strtotime($Date));
$stmount =  DateUtil::th_date('F Y',strtotime($dayto));
$cureday = DateUtil::th_date('j F Y',strtotime($Date));
$allTotal = $amountTotal + $salaryTotal ; */
$str = <<<EOT
	$('.doPrint').on('click',function(){
		window.print();
	});
UIGeneral.init();

EOT;
$this->registerJs($str, View::POS_LOAD, 'form-js');
$this->registerCssFile("$baseUrl/assets/plugins/jquery.pulsate.min.js");

//var_dump($data); exit();

$css=<<<EOT
td{
valign:middle;
}
.text-font{
	font-size: 12px;
}
.right {
text-align: right;
float:right;
display: inline-block;
}
.left {
text-align: left;
float:left;
display: inline-block;
}
.border{
	border:1px #999 solid ;
	border-bottom :2px #555 solid ;
	border-top :0px;
	
}

.marginhHeigth {
margin-top: 100px;
}
	   @media print {
	   
	   	  	  @page { 
				 font-size:12px ;
			  		margin:.5cm .5cm 1cm 1cm;
			  }
			  th{
			  	line-height: 70% ;
			  	font-size:12px ;
			  }
			  
			 .smail{
			   font-size:12px ;
			   font-weight: normal;
			 }
			  tr{
			  	font-size:12px ;
		         height: 24px;
			  }
			  td{
			  	line-height: 90% ;
			  	font-size:12px ;
			  }
			  .page-scope{
			  	page-break-after: always;
			  }  
			  .footer{
			  	display:none;
			  }
			   #for-print{
			  	display:true;
			  }
			  #for-screen{
			  	display:none;
			  } 
			#fortable{
				font-size:12px ;
			}
			  .border{
					border:1px #999 solid ;
					border-bottom :2px #555 solid ;
					border-top :0px;
					
		
					}
					
			}
			@media screen{
			  #for-print{
			  	display:none;
			  }
			  #for-screen{
			  	display:true;
			  }
			}

EOT;
$this->registerCss($css);

?>

<!---------------  for screen  -->

<div id="for-screen" style="margin-top:20px;" >
<a class="btn default doPrint right"><i class="fa fa-print" style=" font-size: 18px; color: #FF3F3F;"></i></a>
<a class="btn default left" href="<?php  echo $baseUrl?>/pond/list"><i class="fa fa-arrow-circle-o-left" style=" font-size: 18px; color: #FF3F3F;"></i></a>
<div id="pulsate-regular" align="center" style=" padding-top: 30px;">
<div class="col-md-12 text-center"><h3>ข้อมูลพื้นฐานการเลี้ยง</h3></div>
   <h5><br><br>ธนากรฟาร์ม บ่อที่........... ขนาด.........ไร่ รุ่นที่........จำนวนกุ้งที่ปล่อย.................ตัว วันที่ปล่อย............. </h5> 
  <h5  class="centor"> ชนิดลูกกุ้ง............ ราคาตัวละ.......สตางค์   ฟาร์ม/บริษัทที่รับลูกกุ้ง..............................   </h5>
  <br>
 
</div>

 <h5 class="left"> <?php //  if (!empty(\Yii::$app->params['region'][$key])) { echo \Yii::$app->params['region'][$key]; }?> </h5> 
<table border="1" cellpadding="5" width ="100%" id="fortable" >
	<thead>
		<tr style="font-weight:bold;color:#000; text-align: center;" >
			<th align="center" rowspan="3"  width="2%">วันที่</th>
			<th align="center" rowspan="3" width="5%">อายุ(วัน)</th>
			<th align="center" colspan="7"  width="20%">การให้อาหาร</th>
			<th align="center" colspan="6"  width="20%">การเช็คยอ</th>
			<th align="center" rowspan="3"  width="5%">นํ้าหนักเฉลี่ย/กรัม</th>
			<th colspan="10" rowspan="2" align="center" width="20%"><div class ="smail">คุณภาพนํ้า</div></th>
			<th rowspan="3" align="center" width="10%"><div class ="smail">บันทึกอื่นๆ</div></th>
		</tr>
		<tr style="font-weight:bold;color:#000;" >
			
			<th rowspan="2"  align="center" width="5%">เบอร์อาหาร</th>
			<th colspan="6" align="center" width="20%">อาหาร/มื้อที่</th>
			<th colspan="6" align="center" width="20%">อาหาร/มื้อที่</th>
			
		
			
		</tr>
		
			<tr style="font-weight:bold;color:#000;" >
			
			
			<th align="center" width="2%">1</th>
			<th align="center" width="2%">2</th>
			<th align="center" width="2%">3</th>
			<th align="center" width="2%">4</th>
			<th align="center" width="2%">5</th>
			<th align="center" width="2%">6</th>
			
			<th align="center" width="2%">1</th>
			<th align="center" width="2%">2</th>
			<th align="center" width="2%">3</th>
			<th align="center" width="2%">4</th>
			<th align="center" width="2%">5</th>
			<th align="center" width="2%">6</th>
			
			
			
			<th align="center" width="2%">เช้า</th>
			<th align="center" width="2%">บ่าย</th>
			<th align="center" width="2%">ออกซิเจน</th>
			<th align="center" width="2%">อัลคาไลน์</th>
			<th align="center" width="2%">อุณหภูมินํ้า</th>
			<th align="center" width="2%">อุณหภูมิ</th>
			<th align="center" width="2%">แอมโมเนีย</th>
			<th align="center" width="2%">ไนเตรรท์</th>
			<th align="center" width="2%">เปลี่ยนนํ้า</th>
			<th align="center" width="2%">ความเค็ม</th>

		</tr>
		
	</thead>
<tbody>
 <?php $data = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];  foreach ($data as $lst): ?>
 <?php 
 // $allamount = [];
 // $allamountItem = [];
 
 //var_dump($lst); exit();
 ?>
		<tr>
			<td> <?php// echo $i; ?> . </td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			<td> <?php// echo $i; ?> .</td>
			
		</tr>
		<?php  
/* 		$arrtotal[] = $lst['charge'];
		$arrsum[] = sprintf("%01.2f", $lst['amountPayee'])+$lst['salary'] ;
		$i++;
		 endif;
		$allsum = array_sum($arrsum);
		$alltotal = array_sum($arrtotal);
		$fullSum = $allsum - $alltotal  ; */
		endforeach;
		?>
	</tbody>
	</table>

	<br><br><br><br>
								
								 
								
								<br><br><br><br><br>
	<br /> <br />
	</div>
	<!---------------  for print -->
	<div id="for-print">
	
	</div>