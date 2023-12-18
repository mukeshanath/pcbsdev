<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice-Print</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    

  </head>
  <style>
 @page {
  size: A4;
  margin: 4mm 4mm 4mm 4mm;
 }
 @media print{
    body {-webkit-print-color-adjust: exact;}
     /* header {
          position:fixed;
          background:#fff;
          float:none;
     }
     section{
          position: relative; top: 270px;
     }
    
     footer{
          page-break-inside: avoid;
          bottom:0;
     } */
     header { page-break-before: always; }
     .amountcalc{
          page-break-inside: avoid;
     }
     .flex-container{
          page-break-inside: avoid;
     }
     table { page-break-after:auto }                          
     tr    { page-break-inside:avoid; page-break-after:auto }
     td    { page-break-inside:avoid; page-break-after:auto }
     thead { display:table-header-group }
     tfoot { display:table-footer-group }  
 }
  body{
     margin: auto;
    
     padding:15px;
     background:#fff;
     font-family:"Times New Roman", Times, serif;
  }
.flex-container{
     display:flex;
}
.image{
     width:60%;
}
.dom{
     font-size:14px;
}
#company{
     width:350px;
     border:1px solid black;
     padding:15px;
}
#project{
    width:450px; 
    border:1px solid black;
    margin-left:1%;
    line-height:1.4;
}
.gsthead{
     margin-top:20px;
}
.left-20{
     margin-left:2%;
}
.billdata{
     font-size:12px;
     padding:none;
     height:8%;
     display:flex;
     margin-top:5px;
     border-bottom:1px solid black;
}
.customerbox{
     padding:10px;
     font-size:18px;
}
.flex-box-3{
     width:33.3333%;
}
.transdate{
     height:20px;
     width:830px;
     padding:10px;
     border-bottom: 1px solid black;
}
.centertb{
     text-align:right;
     margin-left:8px;
}
.center{
     text-align:left;
     margin-left:2px;
}
.tablehead{
     font-size:12px;
     line-height:2;
}
.tablehead th{
     border-bottom: 1px solid black; 
}
.border-bottom{
     border-bottom: 1px solid black; 
}
.cnotestable{
     width:850px;
}
.tablebody{
     font-size:12px;
}
.calculations{
     max-width:850px;
}
.righttb{
     text-align:right;
}
.lefttb{
     text-align:left;
}
.border-top{
     border-top:1px solid black;
}
.footer{
     width:800px;
}
.note{
     width:800px;
     padding:40px;
     font-size:12px;
}
.notehead{
     font-weight:bold;
     text-decoration: underline;
}
.bank-details{
     font-size:14px;
     padding:25px;
     margin-bottom:30px;
}
.bankhead{
     font-weight:bold;
}
.sac_code{
     float: right;
     padding: 0;
    margin: 0;
}
#HASH {
  display: flex;
  justify-content: space-between;
}
.tablehead1 th{
     border-bottom: 1px solid white; 
}
.qrcode img{
     width:120px !important;
}
  </style>
  <body id="printable">
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(isset($_GET['print_station'])){
     $print_station = $_GET['print_station'];
     $invno_json = explode(",",$_GET['invno_json']);
    
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
  ///// get fsc value ------- start-------------------      
  function getcustomerfscandhandling($db,$station,$custcode,$amount){
     $getfscofcustomer = $db->query("SELECT cust_fsc,cust_gstin,fsc_type,br_code FROM customer WITH (NOLOCK) WHERE cust_code='$custcode' AND comp_code='$station'");
     $rowgetfsc = $getfscofcustomer->fetch(PDO::FETCH_OBJ);
     if($rowgetfsc->fsc_type=='D'){
          $fscbr = $db->query("SELECT branchtype FROM branch WITH (NOLOCK) WHERE stncode='$station' AND br_code='$rowgetfsc->br_code'")->fetch(PDO::FETCH_OBJ);
          $getfsc = $db->query("SELECT fsc,f_hand_charge FROM fscmaster WITH (NOLOCK) WHERE comp_code='$station' AND min_sales_amt<='$amount' AND max_sales_amt>='$amount' AND branchtype='$fscbr->branchtype' ORDER BY tdate DESC");
          if($getfsc->rowCount()==0){
               $customer_fsc = 0;
               $f_hand_charge = 0;
          }
          else{
               $fscval = $getfsc->fetch(PDO::FETCH_OBJ);
               $customer_fsc = $fscval->fsc;
               $f_hand_charge = $fscval->f_hand_charge;
          }
     }
     else{
          $customer_fsc = $rowgetfsc->cust_fsc;
          $f_hand_charge = 0;
     }
     return array($customer_fsc,$f_hand_charge);
}

 ///// get fsc value ------- end------------------- 
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

    


function getcoderate($stationcode,$db,$cust_code){
     $getcustomerinfo = $db->query("SELECT * FROM customer WHERE comp_code='$stationcode' AND cust_code='$cust_code'");
     $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
     $cod_per_kg = $rowcustomer->cod;
     return round($cod_per_kg);
}




 $i=0;
    // print_r($invno_json);
 foreach($invno_json as $invno){
 
     $i++;
         
         //$invno = $invno_json[0];
         //echo $invno;
     //Invoice Info
     $fetchdata = $db->query("SELECT * FROM invoice WHERE invno='$invno' AND comp_code='$stationcode'");
     $rowinvoice = $fetchdata->fetch(PDO::FETCH_OBJ);
     $customercode = $rowinvoice->cust_code;
     $origfrmDate = "$rowinvoice->fmdate";
     $frmDate = date("d-m-Y", strtotime($origfrmDate));

     $origtoDate = "$rowinvoice->todate";
     $toDate = date("d-m-Y", strtotime($origtoDate));
     //Company Info
     $getcompanyname = $db->query("SELECT * FROM station WHERE stncode='$rowinvoice->comp_code'");
     $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);
     //Customer Info
     $getcustomerinfo = $db->query("SELECT * FROM customer WHERE cust_code='$rowinvoice->cust_code'");
     $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
     $fetchdiscount = $db->query("SELECT spl_discountD FROM customer WHERE comp_code='$stationcode' and cust_code = '$rowinvoice->cust_code'");
     $rowinvoicediscount = $fetchdiscount->fetch(PDO::FETCH_OBJ);
     //POD info
     $gstcode = substr($rowcustomer->cust_gstin,0,2);
     $statename = $db->query("SELECT state_name FROM state WHERE gstcode='$gstcode'")->fetch(PDO::FETCH_OBJ);

     $getcode_consignment = $db->query("SELECT count(cno) as countcod FROM credit  WHERE comp_code='$stationcode' AND invno='$invno' AND status='Invoiced' AND cnote_serial='COD'")->fetch(PDO::FETCH_OBJ);
     $codcount = $getcode_consignment->countcod;

     $No_Delivery = $db->query("SELECT count(cno) as countcod FROM credit  WHERE comp_code='$stationcode' AND invno='$invno' AND status='Noservice-return' AND cnote_serial='COD'")->fetch(PDO::FETCH_OBJ);
     $nodelivery = $No_Delivery->countcod;

     $deliverystatus = $db->query("SELECT count(status) as counting FROM credit WHERE comp_code='$stationcode' AND invno='$invno' AND status='Noservice-return' AND cnote_serial='COD'")->fetch(PDO::FETCH_OBJ);


     $gst=($rowinvoice->invamt/100)*18;
     if(empty($rowcustomer->cust_gstin)){
          $sgst = $gst/2;
          $cgst = $gst/2;
          $igst = '0';
     }
     else if(substr($rowcustomer->cust_gstin,0,2)==substr($rowcompany->stn_gstin,0,2)){
          $sgst = $gst/2;
          $cgst = $gst/2;
          $igst = '0';
     }
     else{
          $sgst = '0';
          $cgst = '0';
          $igst = $gst;
     }
     
     $cod_total = $rowinvoice->cod_per_keg_total;
     $totnetamt = $rowinvoice->grandamt;
     $fsc = $rowinvoice->fsc;
     $hc = $rowinvoice->fhandcharge;
     $totinvamt =  $totnetamt +  $fsc + $hc + $cod_total;
     $custdisc = $rowinvoicediscount->spl_discountD;
     $discval =  $custdisc/100;
     $invamtafterdisc =  $totinvamt*$discval;

?>
    <header>
     
    <div class="flex-container">
    <div id="company" class="clearfix">
         <div class=""><img src="https://www.tpcindia.com/images/logo.png" class="image"></div>
         <div class="dom"><strong>DOMESTIC & INTERNATIONAL</strong></div>
        <div><?=$rowcompany->stnname;?></div>
        <div><?=$rowcompany->stn_addr;?>,<br /><?=$rowcompany->stn_city;?>,<?=$rowcompany->stn_state;?></div>
        <div>Contact: <?=$rowcompany->stn_mobile;?></div>
        <div><a href="mailto:<?=$rowcompany->stn_email;?>"><?=$rowcompany->stn_email;?></a></div>
        <div class="gsthead"><strong>GSTIN</strong>: <?=$rowcompany->stn_gstin;?><p class="sac_code"><strong>SAC</strong> : 996812</p></div>

      </div>
      <div id="project">
        <div class="billdata" style="padding-bottom:10px;"><div class="flex-box-3" style="width:220px;font-size:16px;">&#160;&#160;<strong>Bill No : </strong><strong><?=$invno;?></strong></div><div class="flex-box-3" style="width:160px;font-size:16px;"> <strong class="left-20">Dt :  </strong><strong><?=date("d-M-Y",strtotime($rowinvoice->invdate))?></strong></div><div class="flex-box-3" style="width:170px;font-size:16px;"><strong class="left-20">Code : </strong><strong><?=$rowinvoice->cust_code;?></strong></div></div>
        <div class="customerbox"><div class="col-sm-12"><div class="col-sm-6" style="float:left"><?=$rowcustomer->cust_name;?></div></div><br>
        <div><?=$rowcustomer->cust_addr;?>,</div>
        <div><?=$rowcustomer->cust_city;?>,</div>
        <div><?=$rowcustomer->cust_state;?>,</div><br>
        <div id="HASH" class="blue-msg">
     <span id="time-HASH" class="smalltext">Branch : <?=$rowinvoice->br_code;?>&nbsp;&nbsp;<span class="ios-circle"><div class="col-sm-6" style="float:right"><img src="<?=__ROOT__?>barcode?text=<?=$invno?>&codetype=code128&orientation=horizontal&size=30&print=<?=$invno?>" class="barcode float-right"></div></span></span>
      </div>
        <div>
             <div style="width:50%;float:left;font-size:15px"><p style="vertical-align: text-bottom;"><strong>GSTIN : </strong><?=$rowcustomer->cust_gstin;?></p></div>
             <div style="width:50%;float:left;font-size:15px"><p style="vertical-align: text-bottom;"><strong>POS : </strong><?=substr($rowcustomer->cust_gstin,0,2).' - '.$statename->state_name;?></p></div>
             <input type="hidden" id="signedqrcode" value="<?=$rowinvoice->signedirn?>">

     </div>
             
     </div>
      </div>
      <!-- <div class="transdate flex-container">
              <div><strong>BILL/Statement of Transaction between </strong></div>
              <div style="margin-left:5%"><?=$frmDate;?>&#160;&#160; AND&#160;&#160; <?=$toDate;?></div>
         </div> -->
    </header>
    <section>
    <h3 style="margin-left:380px;">TAX INVOICE</h3>
      <table class="cnotestable">
        <thead>
          <tr class="tablehead1">
            <th style="width:25px;"></th>
            <th style="width:70px;"></th>
            <th style="width:70px;"> </th>
            <th style="width:90px;"></th>
            <th style="width:50px;"></th>
            <th style="width:70px;"></th>            
            <th style="width:25px;margin-right:10px;"></th>
            <th style="width:70px;"></th>
            <th style="width:70px;"> </th>
            <th style="width:90px;"></th>
            <th style="width:50px;"></th>
            <th style="width:50px;"></th>  
          </tr>
        </thead>
        
        <tbody class="amountcalc"> 
                    <!-- </tr> -->
                    <tr class="gsthead" style="height:50px;">
                    <td></td>
                    <td class='righttb' colspan="2" style="text-align:left;">Courier Charges</td>
                    <td colspan="8"></td>
                    <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->grandamt,2);?></td>
                    </tr>
                    <tr class="gsthead" style="height:50px;">
                    <td></td>
                    <td class='righttb' colspan="2" style="text-align:left;">Fuel Charges <?=getcustomerfscandhandling($db,$stationcode,$customercode,$rowinvoice->grandamt)[0];?> %</td>
                    <td colspan="8"></td>
                    <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->fsc,2);?></td>
                    </tr>
                    <?php if($codcount==0){        ?>
                     <?php    }else{  ?>
                    <tr class="gsthead">
                    <td></td>
                    <td class='righttb' colspan="2" style="text-align:left;">Cod Cnotes&nbsp;&nbsp;(<?=((($codcount==0) ? '0 * '.getcoderate($stationcode,$db,$customercode).'' : $codcount. "*" .getcoderate($stationcode,$db,$customercode)));?>)</td>
                    <td colspan="8"></td>
                    <td class='righttb ' style="text-align:right;"><?=number_format($rowinvoice->cod_per_keg_total,2);?></td>
                    </tr>
                    <?php    }  ?>
                    <?php if($nodelivery <=0 || is_null($nodelivery)){        ?>
                     <?php    }else{  ?>
                    <tr class="gsthead">
                    <td></td>
                    <td class='righttb' colspan="2">(COD <?=$deliverystatus->counting?>)No-Service</td>
                    <td colspan="8"></td>
                    <td class='righttb ' style="text-align:right;">0.00</td>
                    </tr>
                    <?php    }  ?>

                    
                    <?php if($rowinvoice->fhandcharge==0 OR empty($rowinvoice->fhandcharge) OR is_null($rowinvoice->fhandcharge)){        ?>
                    <?php    }else{  ?>

                   <tr class="gsthead">
                   <td></td>
                   <td class='righttb' colspan="2" style="text-align:left;">Handling Charges</td>
                   <td colspan="8"></td>
                   <td class='righttb border-bottom' id="fhanding" style="text-align:right;"><?=number_format($rowinvoice->fhandcharge,2);?></td>
                   </tr>
                   <?php    }  ?>

                   <?php $setoprator = (($rowinvoice->fhandcharge==0 OR empty($rowinvoice->fhandcharge) OR is_null($rowinvoice->fhandcharge)) ? 'border-top' : '');      ?>
                    <tr class="gsthead" style="height:10px;">
                    <td></td>
                    <td colspan="10"></td>
                    <td class='righttb <?=$setoprator?>' style="text-align:right;"><?=number_format($rowinvoice->grandamt+$rowinvoice->fsc+$rowinvoice->fhandcharge+$rowinvoice->cod_per_keg_total,2);?></td>
                    </tr>
                    <tr class="gsthead" style="height:10px;">
                    <td></td>
                    <td colspan="10"></td>
                    <!-- <td class='righttb'>0.00</td> -->
                    </tr>
                     <?php if(empty($custdisc) OR $custdisc==0){     ?>

                         <?php    }else{  ?>
                    <tr class="gsthead" style="height:30px;">
                     <td></td>
                     <td  class='righttb' colspan="2" style="text-align:left;">Discount&nbsp;<?=$custdisc;?>&nbsp;%</td>
                     <td colspan="8"></td>
                     <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->spl_discount,2);?></td>                    
                     </tr>
                     <?php    }  ?>

                    <tr class="gsthead" style="height:2px;">
                    <td></td>
                    <td class='righttb' colspan="2" style="text-align:left;">Invoice Value</td>
                    <td colspan="8"></td>
                    <td class='righttb' style="text-align:right;"><p style="border-top:1px solid black;border-bottom:1px solid black;padding-top:3px;padding-bottom:3px;"><?=number_format($rowinvoice->invamt,2);?><p></td>

                    </tr>                                 
                    <tr class="gsthead" style="height:20px;">
                    <td></td>
                    <td class='righttb' colspan="2" style="text-align:left;">IGST @ 18 %</td>
                    <td colspan="8"></td>
                    <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->igst,2);?></td>
                    </tr>
                    <tr class="gsthead" style="height:20px;">
                    <td></td>
                    <td class='righttb' colspan="2" style="text-align:left;">SGST @ 9 %</td>
                    <td colspan="8"></td>
                    <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->cgst,2);?></td>
                    </tr>
                    <tr class="gsthead" style="height:20px;">
                    <td></td>
                    <td class='righttb' colspan="2" style="text-align:left;">CGST @ 9 %</td>
                    <td colspan="8"></td>
                    <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->sgst,2);?></td>
                    </tr>
                    <tr class="gsthead" style="height:30px;">
                    <td></td>
                    <td class='righttb' colspan="2" style="text-align:left;"><strong>Net Payable</strong></td>
                    <td colspan="8"></td>
                    <td class='righttb border-bottom border-top' style="text-align:right;"><strong><?=number_format($rowinvoice->netamt,2);?></strong></td>
                    <td></td>
                    </tr>               

        </tbody>
      </table>
      <div id="calculations">
      <?php

          $get_amount= AmountInWords($rowinvoice->netamt);
          echo '<h3 style="margin-left:100px">'.$get_amount.'</h3>';
 ?> 
      </div>

    </section>
    <footer class="footer">
      <div style="text-align:right;height:70px;">for <?=$rowcompany->stnname;?></div>
      <div style="text-align:right;height:40px;">Authorised Signatory</div>

      <div class="note">
      <div class="notehead">Note:</div>
      <div>1. Payment should be mad within 10 days from the date of receipt of invoice, failing which we will be forcing to stop forwarding you further consignments.</div>
          <div>2. Payment should be made by the way of NEFT/CHEQUE/DD and forwarded to <?=$rowcompany->stnname;?>, quoting your Inv.No. and Inv.date.</div>
          <div>3. We will receive payment only bill to bill by the way of NEFT/CHEQUE/DD/CASH.</div>          
          <div>4. For Cheque bounching a penaly of Rs.250/- will be collected from you.</div>
          <div>5. Any delay in payment an interest of 18% per annum will be charged.</div>
          <div>6. Bill correction, if any to be received within 7 days time.</div>
          <div>7. GST must be paid as per Govt. of India norms. GST No : <?=$rowcompany->stn_gstin;?> || PAN No : AAOFT6830F</div>
          <div>8. If you are a defaulter licensee, please make payment by DEMAND DRAFT/CASH only.</div>      

      </div>
      <div class="bank-details">
      <span class="bankhead">Bank Details:</span>
      <table>
      <tr>
      <td>ACCOUNT NAME</td><td>:</td><td><?=$rowcompany->baccname;?></td><td>IFSC CODE</td><td>:</td><td><?=$rowcompany->ifsc;?></td>
      </tr>
      <tr>
      <td>ACCOUNT NO AND ACCOUNT TYPE </td><td>:</td><td><?=$rowcompany->baccno;?>/<?=$rowcompany->bacctype;?></td><td>|| MICR CODE</td><td>:</td><td><?=$rowcompany->micr;?></td>
      </tr>
      <tr>
      <td>BANK NAME</td><td>:</td><td><?=$rowcompany->bankname;?></td><td>SWIFT CODE</td><td>:</td><td><?=$rowcompany->swift;?></td>
      </tr>
      <tr>
      <td>BRANCH PLACE</td><td>:</td><td><?=$rowcompany->bbranch;?></td>
      </tr>
      </table>
</div>
      </footer>
      <?php if($rowinvoice->irn_status=='Issued'):   ?>
           <div class="irn_number flex-container" style="margin-top:10px;margin-left:25px;">
              <div><strong>Irn No : </strong></div>
              <div style="margin-left:5px"><?=$rowinvoice->irn_no?></div><br>
              <div id="<?=$invno?>" class="qrcode" style="margin-left:120px;"></div>  
              <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

          
         </div>
         <?php else: ?>

          <?php endif;  ?>


      <div class="transdate flex-container">
              <div><strong>BILL/Statement of Transaction between </strong></div>
              <div style="margin-left:5%"><?=$frmDate;?>&#160;&#160; AND&#160;&#160; <?=$toDate;?></div>
         </div>
      <table class="cnotestable">
        <thead>
          <tr class="tablehead">
            <th style="width:25px;">SNo</th>
            <th style="width:70px;">DATE</th>
            <th style="width:70px;">POD NO</th>
            <th style="width:90px;">DESCRIPTION</th>
            <th style="width:50px;">WT</th>
            <th style="width:70px;">AMOUNT</th>            
            <th style="width:25px;margin-right:10px;">SNo</th>
            <th style="width:70px;">DATE</th>
            <th style="width:70px;">POD NO</th>
            <th style="width:90px;">DESCRIPTION</th>
            <th style="width:50px;">WT</th>
            <th style="width:50px;">AMOUNT</th>  
          </tr>
        </thead>
        <tbody class="tablebody">
             <!-- <tr> -->
          <?php 
                    if($rowinvoice->br_code=='TR'){
                         $getpod = $db->query("SELECT * FROM ccharge WHERE invno='$invno' AND comp_code='$stationcode' ORDER BY tdate");
                         $i = 0;
                         while ($rowpod = $getpod->fetch(PDO::FETCH_OBJ))
                         {
                         $fetchdestname = $db->query("SELECT TOP 1 dest_name FROM destination,pincode WHERE destination.dest_code=pincode.dest_code AND (pincode='$rowpod->destn' OR destination.dest_code='$rowpod->destn')")->fetch(PDO::FETCH_OBJ);
                         $i++;
                         if($i % 2 != 0){
                              echo "</tr>";   
                         }
                         echo "<td style='width:25px;text-align:right;padding-right:5px'>".$i."</td>";
                         echo "<td style='width:70px;'>".date("d-m-Y", strtotime($rowpod->tdate))."</td>";
                         echo "<td style='width:70px;'>".$rowpod->cno."</td>";
                         echo "<td style='width:90px;'>".substr($fetchdestname->dest_name,0,11)."</td>";
                         echo "<td style='width:50px;text-align:right;'>".number_format($rowpod->wt,3)."</td>";
                         echo "<td style='width:70px;text-align:right;padding-right: 12px;'>".number_format($rowpod->amount,2)."</td>";
                         if($i % 2 == 0){
                         echo "</tr>";   
                         }
                         }
                    }
                    else{
                    $getpod = $db->query("SELECT * FROM credit WHERE invno='$invno' AND comp_code='$stationcode' ORDER BY tdate");
                    $i = 0;
                    while ($rowpod = $getpod->fetch(PDO::FETCH_OBJ))
                    {
                    $fetchdestname = $db->query("SELECT TOP 1 dest_name FROM destination LEFT JOIN pincode ON destination.dest_code=pincode.dest_code WHERE (pincode='$rowpod->dest_code' OR destination.dest_code='$rowpod->dest_code' OR destination.dest_name='$rowpod->dest_code')")->fetch(PDO::FETCH_OBJ);
                    $i++;
                    if($i % 2 != 0){
                         echo "</tr>";   
                    }
                    echo "<td style='width:25px;text-align:right;padding-right:5px;background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".$i."</td>";
                    echo "<td style='width:70px;background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".date("d-M-Y", strtotime($rowpod->tdate))."</td>";
                    echo "<td style='width:70px;background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".$rowpod->cno."</td>";
                    echo "<td style='width:90px;background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".substr($fetchdestname->dest_name,0,11)."</td>";
                    echo "<td style='width:50px;text-align:right;background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".number_format($rowpod->wt,3)."</td>";
                    echo "<td style='width:70px;text-align:right;padding-right: 12px;background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".number_format($rowpod->amt,2)."</td>";
                    if($i % 2 == 0){
                    echo "</tr>";   
                    }
                    }
                    }                                  
                    ?>
                    </tbody>
                   
      </table>
      <?php
}
}

?>
      <script>
        $(document).ready(function(){
     //        window.print();
     //        if(window.location.host!='localhost')
     // window.history.pushState('PCNL', 'Title', '/');
        });
      </script>
  </body>
</html>
<script>

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
</script>
     
<script>
 
     function fetch_signedirn(invno){
          var data;
          var station = '<?=$stationcode?>';
            $.ajax({
            url:"<?php echo __ROOT__ ?>bulkinvoiceprint",
            method:"POST",
            async: false,
            data:{invno,station,action:"fetch_signedinvoice"},
            dataType:"text",
            success:function(message)
            { 
               var res = JSON.parse(message);
                response = res;
            }
            });
            return response;
     }


</script>


    <script>
      var array_inv = '<?=$_GET['invno_json']?>';
      let nowArray = array_inv.split(",");
       
      $.each(nowArray, function (index, value) {
         

 var signedqrcode = fetch_signedirn(""+value+"");
       

       var decodedString = atob(signedqrcode.split(".")[1]);
        var data = JSON.parse(decodedString);
       
        var res = JSON.parse(data.data); 
     //    console.log(res);
     //    console.log(res['DocDtls']['Dt']);
       // qrCodeText = qrCodeText.padEnd(220);
       var qrtext = "Inv No: "+res['DocDtls']['No']+"\nInv Date: "+res['DocDtls']['Dt']+"\nInvoice Amount: "+number_format(res['ValDtls']['AssVal'],2)+"\nCGST : "+number_format(res['ValDtls']['CgstVal'],2)+"\nSGST : "+number_format(res['ValDtls']['SgstVal'],2)+"\nIGST : "+number_format(res['ValDtls']['IgstVal'],2)+"\nTotal Inv Amt: "+number_format(res['ValDtls']['TotInvVal'],2)+"";
        var qrcode = new QRCode(value, {
          logo: "https://www.tpcindia.com/images/logo.png",
      text: "Inv No: "+res['DocDtls']['No']+"\nInv Date: "+res['DocDtls']['Dt']+"\nInvoice Amt: "+number_format(res['ValDtls']['AssVal'],2)+"\nCGST : "+number_format(res['ValDtls']['CgstVal'],2)+"\nSGST : "+number_format(res['ValDtls']['SgstVal'],2)+"\nIGST : "+number_format(res['ValDtls']['IgstVal'],2)+"\nTotal Amt: "+number_format(res['ValDtls']['TotInvVal'],2)+"",
      width: 256,
      height: 256,
      colorDark: '#000000',
      colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.H,
      logoWidth: undefined,
      logoHeight: undefined,
      logoBackgroundColor: '#ffffff',
      logoBackgroundTransparent: false,
  });
 });



    </script>