<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Collection-<?php if(isset($_GET['collectionnumber'])){echo $_GET['collectionnumber'];}?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    
  </head>
  <style>
 @page {
  size: A4;
  margin: 4mm 4mm 4mm 4mm;
 }
 @media print{
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
     .amountcalc{
          page-break-inside: avoid;
     }
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
     margin-top: 40px;
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
}
.bankhead{
     font-weight:bold;
}
  </style>
  <body id="printable">
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
//error_reporting(0);
session_start();
include 'db.php';
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


if(isset($_GET['collectionnumber'])){
     $collectionnumber = $_GET['collectionnumber'];
     //Invoice Info
     $fetchdata = $db->query("SELECT * FROM collection WHERE colno='$collectionnumber' AND comp_code='$stationcode'");
     $rowinvoice = $fetchdata->fetch(PDO::FETCH_OBJ);
     //Company Info
     $getcompanyname = $db->query("SELECT * FROM station WHERE stncode='$rowinvoice->comp_code'");
     $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);
     //Customer Info
     $getcustomerinfo = $db->query("SELECT * FROM customer WHERE cust_code='$rowinvoice->cust_code' AND comp_code='$stationcode'");
     $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
     //POD info

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
     
}
?>
    <header>
    <div class="flex-container">
    <div id="company" class="clearfix">
         <div class=""><img src="views/image/TPC-Logo.jpg" class="image"></div>
         <div class="dom"><strong>DOMESTIC & INTERNATIONAL</strong></div>
        <div><?=$rowcompany->stnname;?></div>
        <div><?=$rowcompany->stn_addr;?>,<br /><?=$rowcompany->stn_city;?>,<?=$rowcompany->stn_state;?></div>
        <div>Contact: <?=$rowcompany->stn_mobile;?></div>
        <div><a href="mailto:<?=$rowcompany->stn_email;?>"><?=$rowcompany->stn_email;?></a></div>
        <div class="gsthead"><strong>GSTIN</strong>: <?=$rowcompany->stn_gstin;?></div>

      </div>
      <div id="project">
        <div class="billdata"><div class="flex-box-3">&#160;&#160;<strong>Coll No : </strong><?=$collectionnumber;?></div><div class="flex-box-3">&#160;&#160;<strong>Invoice No : </strong><?=$collectionnumber;?></div><div class="flex-box-3"> <strong class="left-20">Date :  </strong><?=$rowinvoice->invdate;?></div><div class="flex-box-3"><strong class="left-20">Code : </strong><?=$rowinvoice->cust_code;?></div></div>
        <div class="customerbox"><div><?=$rowcustomer->cust_name;?></div>
        <div><?=$rowcustomer->cust_addr;?></div>
        <div><?=$rowcustomer->cust_city;?></div>
        <div><?=$rowcustomer->cust_state;?></div>
        <div><p style="vertical-align: text-bottom;">Branch : <?=$rowinvoice->br_code;?></p></div>
        <div><p style="vertical-align: text-bottom;"><strong>GSTIN : </strong><?=$rowcustomer->cust_gstin;?></p></div>
        </div>
     </div>
      </div>
      <div class="transdate flex-container">
              <div><strong>BILL/Statement of Transaction </strong></div>
              <div style="margin-left:5%"><?=$rowinvoice->coldate;?></div>
         </div>
    </header>
    <section>
    
      <table class="cnotestable">
                    <tbody class="amountcalc">
                    <tr class="" style="height:40px;">
                    <td  style="width:150px;"></td>
                    <td class='' colspan="2">Invoice Total Amount</td>
                    <td colspan=""></td>
                    <td class='righttb'><?=number_format($rowinvoice->invamt,2);?></td>
                    </tr>
                    <tr class="" style="height:40px;">
                    <td  style="width:150px;"></td>
                    <td class='' colspan="2">Collected Amount</td>
                    <td colspan=""></td>
                    <td class='righttb'><?=number_format($rowinvoice->colamt,2);?></td>
                    </tr>
                    <tr class="" style="height:40px;">
                    <td  style="width:150px;"></td>
                    <td class='' colspan="2">Balance Amount</td>
                    <td colspan=""></td>
                    <td class='righttb'><?=number_format($rowinvoice->balamt,2);?></td>
                    </tr>
        </tbody>
      </table>
      <div id="calculations">
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

          $get_amount= AmountInWords(round($rowinvoice->colamt));
          echo '<h3 style="margin-left:200px">'.$get_amount.'</h3>';
 ?> 
      </div>

    </section>
    <footer class="footer">
      <div style="text-align:right;height:70px;">for <?=$rowcompany->stnname;?></div>
      <div style="text-align:right;height:40px;">Authorised Signatory</div>

      <div class="note">
      <div class="notehead">Note:</div>
          <div>1. If you are a defaulter licensee, please make payment by DEMAND DRAFT only</div>
          <div>2. Payment should be mad within 7 days from the date of receipt of invoice, failing which we will be forcing to stop forwarding you further consignments </div>
          <div>3. Payment should be made by the way of CHEQUE / DD and forwarded to <?=$rowcompany->stnname;?>, 172-5024560/6570738 quoting your Inv.No. and Inv.date </div>
          <div>4. We will receive payment only bill to bill by the way of CHEQUE/DD. No cash payment will be accepted.</div>
          <div>5. It is compulsory for you to receive receipt against Crossing Charges Payments.</div>
          <div>6. For Cheque bounching a penaly of Rs.1000/- will be collected from you.</div>
          <div>7. Any delay in payment an interest of 18% per annum will be charged.</div>
          <div>8. Bill correction, if any to be received within 7 days time.</div>
          <div>9. Penalty @ Rs.100 per missing POD to be charged</div>
          <div>10. GST must be paid as per Govt. of India norms. GST No : <?=$rowcompany->stn_gstin;?> || PAN No : AAECP1918R</div>

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
      </footer>
      <script>
// $(document).ready(function(){
//      window.print();
// });
      </script>
  </body>
</html>