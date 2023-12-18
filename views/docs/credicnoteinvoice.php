<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Note Invoice-<?php if(isset($_GET['invoicenumber'])){echo $_GET['invoicenumber'];}?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
</head>
<style>
    @page {
  size: A4;
  margin: 4mm 4mm 4mm 4mm;
 }
 @media print {
         body{
            width:100%!important;;
         }
      }
      
 .container{
    width:100%;
 }  
 .image{
    width:290px;
 } 
 .label{
    margin-left:32px;
   
 }
 .top1{
    display:flex;
 }
 .label2{
  margin-left:270px;
  float:left;
  line-height:3px;
   
 }
 .maintable, table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  font-size: 14px;
 
}
 th, td {
padding-left:10px;
}
/* #B6B6B6; */
body{
    width:55%;
}
.barcode{
  margin-top:14px;
  height: 40px;
  width: 180px;
  margin-left: 18px;
}
</style>
<body>
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

session_start();
$user = $_SESSION['user_name'];

$getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
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
if(isset($_GET['crnnumber'])){
    $credit_no = $_GET['crnnumber'];

    $fetchdata = $db->query("SELECT * FROM credit_note WHERE comp_code='$stationcode' AND crn_no='$credit_no'");
     $rowinvoice = $fetchdata->fetch(PDO::FETCH_OBJ);

     $getcompanyname = $db->query("SELECT * FROM station WHERE stncode='$rowinvoice->comp_code'");
     $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);

     $getcustomerinfo = $db->query("SELECT * FROM customer WHERE comp_code='$stationcode' AND cust_code='$rowinvoice->cust_code'");
     $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);

     $fetchdata1 = $db->query("SELECT * FROM invoice WHERE comp_code='$stationcode' AND invno='$rowinvoice->invno'");
     $fetchinvoice = $fetchdata1->fetch(PDO::FETCH_OBJ);


     $gstnormal_value = 100 / 118;
     $calculated_value  =   number_format((float)$rowinvoice->net_amt * $gstnormal_value, 2, '.', '');
     $total_value  =   number_format((float)$rowinvoice->net_amt - $calculated_value, 2, '.', '');
     $charges = number_format((float)$calculated_value, 2, '.', '');
     $igst_value = number_format((float)$rowinvoice->net_amt - $calculated_value, 2, '.', '');
     $sgst_cgst  =   number_format((float)$total_value/2, 2, '.', '');
     $gst = (empty($rowcustomer->cust_gstin) || substr($rowcompany->stn_gstin,0,2)==substr($rowcustomer->cust_gstin,0,2) ? $sgst_cgst+$sgst_cgst : $igst_value);    


}
?>
    <div class="container">
    <div class=""><img src="https://www.tpcindia.com/images/logo.png" class="image"></div>
    </div>
    <div class="top1">   <!-- // <div class="label"><p>Reach Us to Reach Them</p></div><div class="label2"><p><strong>Credit Note </strong>  </p><div class="col-sm-6" style="float:right;margin-left:167px;"><img src="<?=__ROOT__?>barcode?text=<?=$credit_noteno?>&codetype=code128&orientation=horizontal&size=30&print=<?=$credit_noteno?>" class="barcode"></div></div> -->

    <div class="label"><p></p></div><div class="label2"><p><h2>Credit Note </h2><br><p>Original for Recipient</p> </p></div>
    <!-- <div class="col-sm-6" style="float:right;margin-left:137px;"><img src="<?=__ROOT__?>barcode?text=<?=$credit_no?>&codetype=code128&orientation=horizontal&size=30&print=<?=$credit_no?>" class="barcode"></div> -->

    </div>

    <div class="main" style="display:flex">
    <table style="width:70%" class="maintable">
  <!-- <tr>
    <th colspan="2" style="text-align:left;">IRN :</th>
  </tr>
  <tr>
    <td style="width:160px;"><b>Signed By</b></td>
    <td>Nic</td>
  </tr>
  <tr>
    <td style="width:160px;"><b>ACK NO</b></td>
    <td>56456546546546546</td>
  </tr>
  <tr>
    <td style="width:160px;"><b>ACK DATE</b></td>
    <td>21-02-2023 10:09 AM</td>
  </tr> -->
  <tr>
    <td style="width:160px;"><b>Supply Type</b></td>
    <td>B2B</td>
  </tr>
  <tr>
    <td style="width:160px;"><b>Credit Note No</b></td>
    <td><?=$credit_no?></td>
  </tr>
  <tr>
    <td style="width:160px;"><b>Credit Date</b></td>
    <td><?=$rowinvoice->crn_date?></td>
  </tr>
  <tr>
    <td style="width:160px;"><b>Place of Supply</b></td>
    <td><?=$rowcompany->stn_state;?></td>
  </tr>
  <!-- <tr>
    <td style="width:160px;"><b>Reverse Charge</b></td>
    <td>No</td>
  </tr>
  <tr>
    <td style="width:160px;"><b>E-COM GSTIN</b></td>
    <td>-</td>
  </tr> -->
</table>
  <div class="qrcode" style="border :1px solid black;width:210px;border-left:none;"> 

  <img src="<?=__ROOT__?>barcode?text=<?=$credit_no?>&codetype=code128&orientation=horizontal&size=30&print=<?=$credit_no?>" class="barcode">   
  </div>

    </div>
  <div class="address" style="display:flex;margin-top:20px;word-break: all;">
     <div class="address1">
      <b>Seller</b>
      <p><strong><?=$rowcompany->stnname;?></strong><br>
      <?=$rowcompany->stn_addr;?><br>
      <?=$rowcompany->stn_city;?><br>
    
    State: <?=$rowcompany->stn_state;?><br>
    Phone No : <?=$rowcompany->stn_mobile;?><br>
     <a href="mailto:<?=$rowcompany->stn_email;?>"><?=$rowcompany->stn_email;?></a><br>
    Domestic & International - Courier-Cargo<br>
    <strong>GSTIN: <?=$rowcompany->stn_gstin;?></strong></p>
     </div>
     <div class="address2"  style="margin-left:110px;">
     <b>Buyer</b>
     <p><strong><?=$rowcustomer->cust_name?></strong><br>
     <?=$rowcustomer->cust_addr;?><br>
     <?=$rowcustomer->cust_city;?><br>
    State: <?=$rowcustomer->cust_state;?><br>
     Pincode: -<br>   
    <strong>GSTIN: <?=$rowcustomer->cust_gstin;?></strong></p>
     </div>
  </div>

  <div class="totaltable">
  <table style="width:98%">
  <tr>
  <th>#</th>
    <th>Description</th>
    <th>HSN</th>
     <th>Taxable Value</th>
     <?php if(empty($rowcustomer->cust_gstin) || substr($rowcompany->stn_gstin,0,2)==substr($rowcustomer->cust_gstin,0,2)): ?>
     <th>CGST</th>
     <th>SGST</th>
     <?php else:  ?>
      <th>IGST</th>
      <?php endif; ?>
     <th>Total</th>
  </tr>
  <tr style="margin-letf:40px;">
    <td>1</td>
    <td style="text-align:center;">Courier</td>
    <td style="text-align:center;">996812</td>
    <td style="text-align:center;"><?=$charges?></td>
    <?php if(empty($rowcustomer->cust_gstin) || substr($rowcompany->stn_gstin,0,2)==substr($rowcustomer->cust_gstin,0,2)): ?>
    <td style="text-align:center;"><?=$sgst_cgst?><br>(9%)</td>
    <td style="text-align:center;"><?=$sgst_cgst?><br>(9%)</td>
    <?php else:  ?>
      <td style="text-align:center;"><?=$igst_value?><br>(18%)</td>
      <?php endif; ?>
    <td style="text-align:center;"><?=(number_format($charges+$gst,2))?></td>
  </tr>
</table>
  </div>
  <div class="bottom1" style="margin-right:25px;width:490px;margin-top:20px;margin-left:240px;">
  <table id="tableclass" style="width:98%;border:none;">
  <tr>

    <th style="text-align:right;padding-right:250px;border:none;">Total Taxable Value (Rs)</th>
    <th style="text-align:right;border:none;"><?=$charges?></th>

   </tr>
   <?php if(empty($rowcustomer->cust_gstin) || substr($rowcompany->stn_gstin,0,2)==substr($rowcustomer->cust_gstin,0,2)): ?>
   <tr>

   <th style="text-align:right;padding-right:250px;border:none;">Total CGST (Rs)</th>
   <th style="text-align:right;border:none;"><?=$sgst_cgst?></th>
   
   </tr>
   <tr>
   
   <th  style="text-align:right;padding-right:250px;border:none;">Total SGST (Rs)</th>
   <th  style="text-align:right;border:none;"><?=$sgst_cgst?></th>

   </tr>
   <?php else:  ?>
    <tr>
   
   <th  style="text-align:right;padding-right:250px;border:none;">Total IGST (Rs)</th>
   <th  style="text-align:right;border:none;"><?=$igst_value?></th>
  
   </tr>
   <?php endif; ?>
   <tr>
   
   <th  style="text-align:right;padding-right:250px;border:none;">Grand Total (Rs)</th>
   <th  style="text-align:right;border:none;"><p style="border-top:1px solid black;border-bottom:1px solid black;width:50px;"><?=(number_format($charges+$gst,2))?></p></th>
   
   </tr>
</table>

  </div>
  <div class="amountw" style="">
    <p><strong>
    <?php    
       function getIndianCurrency(float $number)
       {
           $decimal = round($number - ($no = floor($number)), 2) * 100;
           $hundred = null;
           $digits_length = strlen($no);
           $i = 0;
           $str = array();
           $words = array(0 => '', 1 => 'one', 2 => 'two',
               3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
               7 => 'seven', 8 => 'eight', 9 => 'nine',
               10 => 'ten', 11 => 'eleven', 12 => 'twelve',
               13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
               16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
               19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
               40 => 'forty', 50 => 'fifty', 60 => 'sixty',
               70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
           $digits = array('', 'hundred','thousand','lakh', 'crore');
       
           while( $i < $digits_length ) {
               $divider = ($i == 2) ? 10 : 100;
               $number = floor($no % $divider);
               $no = floor($no / $divider);
               $i += $divider == 10 ? 1 : 2;
               if ($number) {
                   $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                   $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                   $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
               } else $str[] = null;
           }
       
           $rupees = implode('', array_reverse($str));
           $paise = '';
       
           if ($decimal) {
               $paise = 'and ';
               $decimal_length = strlen($decimal);
       
               if ($decimal_length == 2) {
                   if ($decimal >= 20) {
                       $dc = $decimal % 10;
                       $td = $decimal - $dc;
                       $ps = ($dc == 0) ? '' : '-' . $words[$dc];
       
                       $paise .= $words[$td] . $ps;
                   } else {
                       $paise .= $words[$decimal];
                   }
               } else {
                   $paise .= $words[$decimal % 10];
               }
       
               $paise .= ' paise';
           }
       
           return ($rupees ? $rupees . 'rupees ' : '') . $paise ;
       }

          $get_amount= getIndianCurrency((($charges+$gst)));
          echo '<h3>'.$get_amount.'</h3>';
 ?>
    </strong></p>
  </div>
  <div class="footer_content" style="">
    <p><strong>Reference :</strong></p>
    <table id="tableclass" style="width:98%;border:none;">
  <tr>

    <th style="width:290px;text-align:left;padding-left:10px;">Remarks</th>
    <th style="text-align:left;padding-left:17px;"><?=$rowinvoice->invno?> &nbsp;&nbsp;DT:<?=$rowinvoice->invdate?>&nbsp;&nbsp;/&nbsp;&nbsp;<?=$rowinvoice->cust_code?></th>

   </tr>
</table>
  </div>
</body>
</html>
<script>
$(document).ready(function(){
     window.print();
     if(window.location.host!='localhost')
     window.history.pushState('PCNL', 'Title', '/');
  
});
      </script>