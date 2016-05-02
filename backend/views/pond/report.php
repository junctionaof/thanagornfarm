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
<a class="btn default left" href="<?php // echo $baseUrl?>/payment/newspaperlist"><i class="fa fa-arrow-circle-o-left" style=" font-size: 18px; color: #FF3F3F;"></i></a>
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
			<th align="center" width="5%">นํ้าหนักเฉลี่ย</th>
			<th align="center" width="20%"><div class ="smail">คุณภาพนํ้า</div></th>
			<th align="center" width="10%"><div class ="smail">บันทึกอื่นๆ</div></th>
		</tr>
		<tr style="font-weight:bold;color:#000;" >
			
			<th rowspan="2"  align="center" width="5%">เบอร์อาหาร</th>
			<th colspan="6" align="center" width="20%">อาหาร/มื้อที่</th>
			<th colspan="6" align="center" width="20%">อาหาร/มื้อที่</th>
			<th align="center" width="5%">นํ้าหนักเฉลี่ย</th>
			<th align="center" width="20%"><div class ="smail">คุณภาพนํ้า</div></th>
			<th align="center" width="10%"><div class ="smail">บันทึกอื่นๆ</div></th>
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
			
			<th align="center" width="2%">การเช็คยอ</th>
			<th align="center" width="2%">นํ้าหนักเฉลี่ย</th>
			<th align="center" width="20%"><div class ="smail">คุณภาพนํ้า</div></th>
			<th align="center" width="10%"><div class ="smail">บันทึกอื่นๆ</div></th>
		</tr>
		
	</thead>
<tbody>
 <?php // $total= 0 ; $sum = 0; $i =1; if (!empty($data)): foreach ($data as $lst): ?>
 <?php 
 // $allamount = [];
 // $allamountItem = [];
 
 //var_dump($lst); exit();
 ?>
		<tr>
			<td align="center"> <?php// echo $i; ?> </td>
			<td align="left"><a href=""><?php // echo $arrPayee[$lst["payment"]["payTo"]]["name"] ?></a></td>
			<td align="left"><?php // echo  $lst['bankAccount'] ; ?></td>
			<td align="right"><?php // $amounts = sprintf("%01.2f",$lst['amountPayee'])+$lst['salary'];
			//echo  number_format($amounts,2, '.', ','); ?></td>
			<td align="right"><?php // echo $lst['charge'];?></td>
			<td align="right"><?php // echo number_format($lst['amountPayee']+$lst['salary'] - $lst['charge'],2) ?></td>
		</tr>
		<?php  
/* 		$arrtotal[] = $lst['charge'];
		$arrsum[] = sprintf("%01.2f", $lst['amountPayee'])+$lst['salary'] ;
		$i++;
		endforeach; endif;
		$allsum = array_sum($arrsum);
		$alltotal = array_sum($arrtotal);
		$fullSum = $allsum - $alltotal  ; */
		?>
	</tbody>
	</table>
	<table border="0" cellpadding="3" width ="100%">
					<tr>
						<td colspan="3" class="text-right">รวม </td>
						<td width="21.6%"  align="right" class="text-right border"> <?php // echo number_format($allsum,2)?></td>
						<td width="10.8%"  align="right" class="text-right border"> <?php // echo number_format($alltotal,2)?></td>
						<td width="19.5%" class="text-right border"><?php // echo number_format($fullSum,2)?> </td>
					</tr>

	</table>
	<br><br><br><br>
								
								 <table border="0" width="100%">
								 <tr>
								 <td width="25%" align = "center"> 
								  <h5 class = "text-center">
								 ---------------------------------------- <br />
											
											   ผู้ส่ง <br />
								  </h5></td>
								 <td width="45%">   </td>
								 <td width="30%"  align = "center" ">   
								 <h5 class = "text-center"> 
								----------------------------------------- <br>
								
								ผู้รับ<br /> </h5>
								 </td>
								 </tr> 
								 </table>
								
								<br><br><br><br><br>
	<br /> <br />
	</div>
	<!---------------  for print -->
	<div id="for-print">
	<!--  ใบปะหน้าของ รายงานค่าใช้จ่ายอื่นๆๆ -->

<?php 
		/* $pageSet = 1;
		$page = 1;	
		$max = 34; //จำนวน row สูงสุดใน 1 หน้าที่จะปริ้น
		$start = 0;
		$no = 1;
		$i = 1;
		$total = 0;
		$maxPage = ceil(count($data)/$max);
		$mpage = $maxPage;
		//$st = DateUtil::th_date('F Y', strtotime($itemDatefrom));
		while($arrTmp = array_slice($data, $start, $max)){	 */
	?>
<div class="<? //= ($page!=$maxPage)?'page-scope':''?> pulsate-regular" id="pulsate-regular" align="center" style="page-break-after: always;"  >
<div align="center">
<div class="col-md-12 text-center"><h3>รายงานส่งเงินธนาคาร</h3></div>
    <h5><br>ธนาคารกรุงเทพจำกัด สาขาบางซื่อ วันที่โอน   <?//=date('d/m/Y', strtotime($tranferday))?></h5> 
  <h5  class="centor"> พิมพ์รายงาน ณ. วันที่   <?//=$cureday?> </h5>
  <br>
</div>
<table border="1" cellpadding="5" width ="95%" id="fortable" align="center" >
	<thead>
		<tr style="font-weight:bold;color:#FFFFFF;" bgcolor="#666666">
			<th class="smail" align="center" width="8%">ลำดับที่</th>
			<th class="smail" align="center" width="25%">ชื่อ - นามสกุล ขทร.</th>
			<th class="smail" align="center" width="15%">เลขที่บัญชี</th>
			<th class="smail" align="right" class="right" width="15%">  จำนวนเงิน</th>
			<th class="smail" align="right" class="right" width="15%">  ค่าธรรมเนียม</th>
			<th class="smail" align="right" class="right" width="18%"><div class ="smail">  ยอดเงินสุทธิ</div></th>
		</tr>
	</thead>
<tbody>
 <?php // $total= 0 ; $sum = 0; if (!empty($arrTmp)): foreach ($arrTmp as $lst): ?>
 <?php 
 //$allamount = [];
 //$allamountItem = [];

 ?>
		<tr>
			<td align="center"> <?php // echo $i; ?> </td>
			<td align="left"><?php //echo $arrPayee[$lst["payment"]["payTo"]]["name"];?></td>
			<td align="left"><?php //echo $lst['bankAccount'] ; ?></td>
			<td align="right"><?php
			/* $amounts = sprintf("%01.2f",$lst['amountPayee']+$lst['salary']);
			echo  number_format($amounts,2, '.', ','); */ ?></td>
			<td align="right"><?php //echo $lst['charge'];?></td>
			<td align="right"><?php //echo number_format($lst['amountPayee']+$lst['salary']-$lst['charge'],2) ?></td>
		</tr>
		<?php  
		/* $arrtotalp[] = $lst['charge'];
		$arrsump[] = sprintf("%01.2f",$lst['amountPayee'])+$lst['salary'] ;
		$i++;
		endforeach; endif;
		$allsump = array_sum($arrsum);
		$alltotalp = array_sum($arrtotal);
		$fullSump = $allsum - $alltotal  ; */
		?>
	</tbody>
	</table>
	<?php //	if($pageSet == $mpage){ ?>
	<table border="0" cellpadding="3" width ="95%">
					<tr>
						<td colspan="3" class="text-right">รวม </td>
						<td width="15.8%"  align="right" class="text-right border"> <?php // echo number_format($allsump,2)?></td>
						<td width="15.7%"  align="right" class="text-right border"> <?php //echo number_format($alltotalp,2)?></td>
						<td width="18.9%" class="text-right border"><?php // echo number_format($fullSump,2)?> </td>
					</tr>

	</table>
	<br><br><br><br>
								
								 <table border="0" width="100%">
								 <tr>
								 <td width="25%" align = "center"> 
								  <h5 class = "text-center">
								 ---------------------------------- <br />
											
											   ผู้ส่ง <br />
								  </h5></td>
								 <td width="45%">   </td>
								 <td width="30%"  align = "center" ">   
								 <h5 class = "text-center"> 
								------------------------------------ <br>
								
								ผู้รับ<br /> </h5>
								 </td>
								 </tr> 
								 </table>
	<?php // }?>
	</div>
			<?php
			/* $pageSet ++;
			$number[] = $i;
			$start += $max;
			$page++; */
		//}?>
</div>