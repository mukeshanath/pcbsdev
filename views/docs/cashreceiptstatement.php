<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Receipt-<?php if(isset($_GET['cash_receipt_no'])){echo $_GET['cash_receipt_no'];}?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    
</head>
<style>
     @page {
        size: A4;
        margin: 4mm 4mm 4mm 4mm;
       }
     @media print{
        body{
            width: 100% !important;
           }
         
           .totalist{
              width: 650px !important;
           }
           table.GeneratedTable1 {
            width: 100% !important;
            color: #000000 !important;
               }
               .table_cntent{
                margin-left:470px !important;
               }
               .row1{
                width: 100% !important;
               }
               .office{
                text-align: center !important;
                margin-left:0px !important;
               }
               .column14{
                page-break-inside: avoid;
                width:100%;
               }
               .column_total{
                page-break-inside: avoid;
               }
              .rowflex{
                page-break-inside: avoid;
              }
              .invnodiv{
                padding-left:0px !important;
              }


       }
  .header{
    margin-left:310px;
  }
  .customer_details{
    margin-left:80px;
    margin-top: 70px;
  }
  .customer_details1{
    margin-left:80px;
    margin-top: 22px;
  }
  span{
    margin-left:10px;
  }
  table{
    width:80%;
    
  }

  
table.GeneratedTable {
  width: 100%;
  background-color: #ffffff;
  border-collapse: collapse;
  border-width: 2px;
  border-color: #171616;
  border-style: solid;
  color: #000000;
  border-left: none;
  border-right: none;
  border-bottom: none;
}

 table.GeneratedTable th {
  border-width: 2px;
  border-color: #171616;
  border-style: solid;
  padding: 3px;
  border-left: none;
  border-right: none;
}



table.GeneratedTable1 {
  width: 100%;
  color: #000000;
}



table.GeneratedTable1 thead {
  background-color: #fafafa;
}

.total{
    margin-left:560px;
}
body{
    width: 50%;
}
.totalist{
    line-height:8px;
    margin-top:40px;
    width: 719px;
}
.amt_details{
  float:right;
}
table td{
  text-align:center;
}
.amt_td{
  float:right;
  padding:10px;
  width: 100%;
  text-align:left;
}
.paddind_data{
  padding:10px;
  float:left;
  width: 100%;
  text-align:left;
}


.row {
  display: flex;
}
.image{
  width: 330px;
}
#content {
    display: table;
}

#pageFooter {
    display: table-footer-group;
}

#pageFooter:after {
    counter-increment: page;
    content: counter(page) "of "counter(page);
    font-weight:bold;
}

</style>


<!-- Codes by Quackit.com -->


 
</style>
<body>
    <?php
error_reporting(0);
include 'db.php';


$getuserstation = $db->query("SELECT * FROM users WHERE user_name='".$_SESSION['user_name']."'");
$datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
$rowuser = $datafetch['last_compcode'];
if(!empty($rowuser)){
     $stationcode = $rowuser;
}
else{
    $userstations = $datafetch['stncodes'];
    $stations = explode(',',$userstations);
    $stationcode = $stations[0];     
} 



      if(isset($_GET['cash_receipt_no'])){
        $cash_receipt_no = $_GET['cash_receipt_no'];
        $getcash_receipt = $db->query("SELECT * FROM cash_receipt WHERE cash_receipt_no='$cash_receipt_no' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
     
        $stn_details = $db->query("SELECT * FROM station WHERE stncode='$getcash_receipt->stncode'")->fetch(PDO::FETCH_OBJ);  
        
?>
<div class="row">
  <div class="column" style="line-height:4px;margin-top:10px;font-size:13px;">
    <p>GSTN NO<span>: <?=$stn_details->stn_gstin?></span></p>
    <p>PAN NO<span>: <?=$stn_details->stn_pan?></span></p>
    <p>Category<span>: Courier Service</span></p>
    <p>Service Accounting Code<span>: 996812</span></p>
  </div>
  <div class="column">
  <img src="<?php echo __ROOT__ ?>cr_logo.png" class="image">
  </div>
  <div class="column" style="line-height:4px;margin-top:10px;font-size:13px;">
    <p><span><b>ORIGINAL FOR RECEIPT</b></span></p>
    <p>Billing Queries<span>: billing@tpcmumbai.in</span></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>: kurro-edp@tpcglobe.co.in</span></p>
    <p>Payment Queries<span>: 022-27566244</span></p>
    <p>Email<span>: kurro-ccd@tpcglobe.co.in</span></p>
    <p>Website<span>: WWW.tpcglobe.com</span></p>
  </div>
</div>
<?php $margin_lf = ((strlen($stn_details->stn_addr) > 50)? '120px' : '150px');  ?>
<div class="office" style="font-size:12px;margin-left:<?=$margin_lf;?>">
 <p><b>Corporate Office :</b><span> <?=($stn_details->stn_addr.",".$stn_details->stn_city.",".$stn_details->stn_state);?></span></p>
</div>
<div class="border" style="border-bottom:2px solid black;width: 770px;">

</div>
<div class="header"style="font-size:22px;margin-top:10px;">
 <b>TAX INVOICE</b>
</div>

<div class="invdate"style="font-size:18px;margin-top:10px;margin-left:160px;margin-bottom:20px;">
 <b>Invoice for the Period : <span><?=date("d-M-Y",strtotime($getcash_receipt->frm_date))?></span>  &nbsp;To<span><?=date("d-M-Y",strtotime($getcash_receipt->to_date))?></span></b>
</div>
<div class="row">
  <div class="column" style="line-height:17px;margin-top:10px;margin-left: 30px;font-size:16px;padding-right:210px;">
    <p><b><?=strtoupper($getcash_receipt->cust_name)?></b></p>
    <p style="line-height:17px;"><b><?php $str = strtoupper($getcash_receipt->customer_addr);
        echo wordwrap($str,40,"<br>\n");  ?> </b></p>
    <p><b>GSTIN: <?=$getcash_receipt->gstin?></b></p>
    
  </div>

  <div class="column invnodiv" style="margin-top:10px;font-size:16px;line-height:1px;align-items: flex-end;padding-left:90px;">
  <table  class="GeneratedTable1">

       <tr>
          <td class="paddind_data"><b>Invoice No</b></td>
          <td><b>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          <td class="amt_td"><?=$getcash_receipt->cash_receipt_no?></td>
        </tr>
        <tr>
          <td class="paddind_data"><b>Invoice Date</b></td>
          <td><b>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          <td class="amt_td"><?=date("d-M-Y",strtotime($getcash_receipt->cash_receipt_date))?></td> 
        </tr>
        <tr>
          <td class="paddind_data"><b>HNC/SAC</b></td>
          <td><b>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          <td class="amt_td">996812</td>
        </tr>
      
         </table>
  </div>
</div>
 

 <div class="table" style="width:90%;margin-left:25px;margin-top:10px;">
 <table  class="GeneratedTable">
       
 
       <tr>
      
           <th>Sno</th>
           <th>Date</th>
           <th>Cnote Number</th>
           <th>Destination</th>
           <th>Weight</th>
           <th style="text-align:right;">Amount</th>
         
       </tr>
       <tbody class="tablebody">
       <?php

     
$getcash_receipt_detail = $db->query("SELECT * FROM cash WHERE cash_receipt_no='$cash_receipt_no' AND comp_code='$stationcode'");
$getcash_total = $db->query("SELECT SUM(ISNULL(CAST(TRY_CONVERT(money, charges, 1) AS DECIMAL(18, 2)), 0)) as total
,SUM(ISNULL(CAST(TRY_CONVERT(money, cgst, 1) AS DECIMAL(18, 2)), 0)) as cgst_total
,SUM(ISNULL(CAST(TRY_CONVERT(money, sgst, 1) AS DECIMAL(18, 2)), 0)) as sgst_total
,SUM(ISNULL(CAST(TRY_CONVERT(money, igst, 1) AS DECIMAL(18, 2)), 0)) as igst_total
,SUM(ramt) as amount
 FROM cash WHERE cash_receipt_no='$cash_receipt_no' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
$i;
while($rowcash = $getcash_receipt_detail->fetch(PDO::FETCH_OBJ)){$i++;
  $destination = $db->query("SELECT * FROM destination where comp_code='$getcash_receipt->stncode' AND dest_code='$rowcash->dest_code' OR dest_code='$rowcash->dest_code' OR dest_name='$rowcash->dest_code' OR zone_cash='$rowcash->dest_code'")->fetch(PDO::FETCH_OBJ);  

?>
                   </tbody>
       <tr>            
      
            <td><?=$i?></td>
            <td><?=date("d-M-Y",strtotime($rowcash->tdate))?></td>
            <td> <?=$rowcash->cno?></td>
            <td> <?=$destination->dest_name?></td>
            <td> <?=number_format($rowcash->wt,3)?></td>
            <td style="text-align:right;"> <?=number_format($rowcash->charges,2)?></td>           
       </tr>
       
      
<?php  } ?>
                  
    
   </table >
 </div>
 <div class="border" style="border-bottom:2px solid black;width: 770px;margin-top:110px;">

</div>
<div class="row">
<div class="column">
<div class="column1" style="line-height:18px;margin-top:10px;margin-left: 14px;font-size:16px;">
   <?php
function AmountInWords(float $amount)
{
$amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
// Check if there is any number after decimal
$amt_hundred = null;
$count_length = strlen($num);
$x = 0;
$string = array();
$change_words = array(0 => '', 1 => 'One', 2 => 'Two',
     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
$here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
while( $x < $count_length ) {
     $get_divider = ($x == 2) ? 10 : 100;
     $amount = floor($num % $get_divider);
     $num = floor($num / $get_divider);
     $x += $get_divider == 10 ? 1 : 2;
     if ($amount) {
     $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
     $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
     $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
     '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
     '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
     }
else $string[] = null;
}
$implode_to_Rupees = implode('', array_reverse($string));
$get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
" . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
}
$get_amount= AmountInWords(($getcash_total->amount));
         
   ?>
    <p style="margin-left: 14px;"><?=$get_amount?></p>
  </div>
  <div class="column14" style="line-height:9px;margin-top:10px;margin-left: 15px;font-size:16px;">
   
    <p style="margin-left: 14px;"><b>Our Bank Details</b></p>
    <div class="column14" style="margin-top:10px;font-size:13.5px;width:100%;line-height:2px;text-align:left;">
  <table  class="GeneratedTable1">

       <tr>
          <td class="paddind_data"><b>Name</b></td>
          <td><b>:&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          <td class="amt_td"><?=$stn_details->bankname;?></td>
        </tr>
        <tr>
          <td class="paddind_data"><b>A/c Name<br></b></td>
          <td><b>:&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          <td class="amt_td"><?=$stn_details->baccname;?></td> 
        </tr>
        <tr>
          <td class="paddind_data"><b>A/c No</b></td>
          <td><b>:&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          <td class="amt_td"><?=$stn_details->baccno;?></td>
        </tr>
        <tr>
          <td class="paddind_data"><b>IFSC Code</b></td>
          <td><b>:&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          <td class="amt_td"><?=$stn_details->ifsc;?></td>
        </tr>
        <tr>
          <td class="paddind_data"><b>Pan No</b></td>
          <td><b>:&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
          <td class="amt_td"><?=$stn_details->stn_pan;?></td>
        </tr>
        <tr style="line-height:17px;">
          <td class="paddind_data" style="padding-bottom:27px;"><b>Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b></td>
          <td><b></b></td>
          <td class="amt_td"><?=$stn_details->stn_addr.",".$stn_details->stn_city.",".$stn_details->stn_state;?></td>
        </tr>
        
         </table>
  </div>
    
  </div>
</div>

  <div class="column_total" style="margin-top:10px;font-size:16px;line-height:1px;margin-left: 0px;">

        <table  class="GeneratedTable3" style="width:240px;">
        <tr>
          <td style="text-align:left;border-bottom:2px solid black;"><b>Bill Amount</b></td>
          
          <td class="amt_td" style="text-align:left;border-bottom:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$getcash_total->total?></td>
        </tr>
       <tr>
          <td style="text-align:left;"><b>Taxable Amount</b></td>
          
          <td class="amt_td" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$getcash_total->total?></td>
        </tr>
        <tr>
          <td style="text-align:left;"><b>SGST(9.00%)</b></td>
          
          <td class="amt_td" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=number_format($getcash_total->sgst_total,2)?></td>
        </tr>
        <tr>
          <td style="text-align:left;"><b>CGST(9.00%)</b></td>
          
          <td class="amt_td" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=number_format($getcash_total->cgst_total,2)?></td>
        </tr>
        <tr>
          <td style="text-align:left;"><b>IGST(18.00%)</b></td>
          
          <td class="amt_td" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=number_format($getcash_total->igst_total,2)?></td>
        </tr>
        <tr>
          <td style="text-align:left;border-top:2px solid black;"><b>Total Amount</b></td>
          
          <td class="amt_td" style="text-align:left;border-top:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=number_format($getcash_total->amount,2)?></td>
        </tr>
        </table >
        
  </div>
</div>


<div class="border" style="border-bottom:2px solid black;width: 770px;margin-top:20px;">

</div>
<div class="row rowflex">
  <div class="column" style="line-height:17px;margin-top:10px;margin-left: 25px;font-size:16px;">
    <p><b>Payment Should be made by crossed cheque or<br>Draft Payable to THE PROFESSION COURIERS<br>MUMBAI CENTRAL PVT LTD Within seven days</b></p>
  </div>

  <div class="column" style="margin-top:10px;font-size:16px;line-height:1px;margin-left: 35px;">
   <div class="end1">
   <p>For The Professional Courier Mumbai Central Pvt Ltd</p>
   </div>
   <div class="end2" style="margin-top:55px;">
   <p>Authorized Signature</p>
   </div>
  </div>
</div>

 <?php
 }
 ?>
  <script>
        $(document).ready(function(){
            window.print();
            if(window.location.host!='localhost')
           window.history.pushState('PCNL', 'Title', '/');
        });
      </script>
</body>
</html>