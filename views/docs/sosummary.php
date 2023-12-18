<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice-<?php if(isset($_GET['sonumber'])){echo $_GET['sonumber'];}?></title>
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
     text-align:center;
     margin-left:12px;
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
}
.bankhead{
     font-weight:bold;
}
  </style>
  <body id="printable">
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';
error_reporting(0);
if(isset($_GET['sonumber'])){
     $sonumber = $_GET['sonumber'];
     $rowso = $db->query("SELECT * FROM cnotesales WHERE salesorderid='$sonumber'")->fetch(PDO::FETCH_OBJ);
     $rows_o = $db->query("SELECT * FROM cnote_so_tb WHERE salesorderid='$sonumber'")->fetch(PDO::FETCH_OBJ);
     $rowcompany = $db->query("SELECT * FROM station WHERE stncode='$rowso->stncode'")->fetch(PDO::FETCH_OBJ);
     $rowcustomer = $db->query("SELECT * FROM customer WHERE comp_code='$rowso->stncode' AND cust_code='$rowso->cust_code'")->fetch(PDO::FETCH_OBJ);
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
        <div class="billdata"><div class="flex-box-3">&#160;&#160;<strong>Bill No : </strong><?=$sonumber;?></div><div class="flex-box-3"> <strong class="left-20">Date :  </strong><?=$rowso->date_added;?></div><div class="flex-box-3"><strong class="left-20">Code : </strong><?=$rowso->cust_code; ?></div></div>
        <div class="customerbox"><div><?=$rowcustomer->cust_name;?></div>
        <div><?=$rowcustomer->cust_addr;?></div>
        <div><?=$rowcustomer->cust_city;?></div>
        <div><?=$rowcustomer->cust_state;?></div>
        <div><p style="vertical-align: text-bottom;">Mobile : <?=$rowcustomer->cust_mobile;?></p></div>
        <div><p style="vertical-align: text-bottom;"><strong>Email : </strong><?=$rowcustomer->cust_email;?></p></div>
        <div><p style="vertical-align: text-bottom;"><strong>GSTIN : </strong><?=$rowcustomer->cust_gstin;?></p></div>
        </div>
     </div>
      </div>
    </header>
    <section>
    
      <table class="cnotestable">
        <thead>
          <tr class="tablehead">
            <th class="centertb">SNo</th>
            <th class="centertb">Serial Type</th>
            <th class="centertb">C.No Start</th>
            <th class="centertb">C.No End</th>
            <th class="centertb">Quantity</th>
            <th class="centertb">Rate</th>
            <th class="centertb">Amount</th>
          </tr>
        </thead>
        <tbody class="tablebody">
             <!-- <tr> -->
          <?php
          //cnotecharge
          // if($rowso->cnote_serial=='PRO'){
          //      $custcnotecharge = $rowcustomer->pro;
          // }
          // else if ($rowso->cnote_serial=='PRC'){
          //      $custcnotecharge = $rowcustomer->prc;
          // }
          // else if ($rowso->cnote_serial=='PRD'){
          //      $custcnotecharge = $rowcustomer->prd;
          // }
          // else{
               $custcnotecharge = $rowso->cnote_charge/$rowso->cnote_count;
          //}
          //cnotecharge

                    $sod = $db->query("SELECT * FROM cnotesalesdetail WHERE salesorderid='$sonumber'");  $i=0; 
                    while($rowsod = $sod->fetch(PDO::FETCH_OBJ)){ $i++;
                    echo "<tr>";   
                    echo "<td class='centertb'>".$i."</td>";
                    echo "<td class='centertb'>".$rowsod->cnote_serial."</td>";
                    echo "<td class='centertb'>".$rowsod->cnote_start."</td>";
                    echo "<td class='centertb'>".$rowsod->cnote_end."</td>";
                    echo "<td class='centertb'>".$rowsod->cnote_count."</td>";
                    echo "<td class='centertb'>".$custcnotecharge."</td>";
                    echo "<td class='centertb'>".$rowsod->cnote_count*$custcnotecharge."</td>";
                   
                    echo "</tr>";   
                    } 
                    
                 //check gst of customer for seperate igst,cgst,sgst
                    $gst = $rows_o->gst;
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
                    ?>
                    <!-- <tr class="gsthead" style="height:30px">
                    <td colspan="4"></td>
                    <td class='centertb'><?=$rowso->cnote_count;?></td>
                    <td class='centertb'></td>
                    <td class='centertb'><?=round($rowso->cnote_charge);?></td>
                   
                    </tr> -->
                    </tbody>
                    
                    <tbody class="amountcalc">
                    <tr class="gsthead" style="height:50px;">
                    <td></td>
                    <td class='righttb'  colspan="3"></td>
                    <td  colspan="1"></td><td></td>
                    <td class='righttb'><?=$rowinvoice->fsc;?></td>
                    </tr>
                    <tr class="gsthead">
                    <td></td>
                    <td class='righttb' colspan="3">Total Amount</td>
                    <td  colspan="1"></td>
                    <td></td>
                    <td class='righttb border-bottom'><?=$rows_o->cnote_charge?></td>
                    </tr>
                    <tr class="gsthead" style="height:50px;">
                    <td></td>
                    <td class='righttb' colspan="3">Grand Amt</td>
                    <td  colspan="1"></td><td></td>
                    <td class='righttb'><?=$rows_o->cnote_charge?></td>
                    </tr>
                    <tr class="gsthead" style="height:50px;">
                    <td></td>
                    <td class='righttb' colspan="3">Transhipment Charges</td>
                    <td  colspan="1"></td><td></td>
                    <td class='righttb'><?=((!empty($rows_o->tranship_charge))?number_format($rows_o->tranship_charge,2):'0')?></td>
                    </tr>
                    <tr class="gsthead" style="height:20px;">
                    <td></td>
                    <td class='righttb' colspan="3">IGST @ 18 %</td>
                    <td  colspan="1"></td><td></td>
                    <td class='righttb'><?=number_format($igst,2);?></td>
                    </tr>
                    <tr class="gsthead" style="height:20px;">
                    <td></td>
                    <td class='righttb' colspan="3">SGST @ 9 %</td>
                    <td  colspan="1"></td><td></td>
                    <td class='righttb'><?=number_format($sgst,2);?></td>
                    </tr>
                    <tr class="gsthead" style="height:20px;">
                    <td></td>
                    <td class='righttb' colspan="3">CGST @ 9 %</td>
                    <td  colspan="1"></td><td></td>
                    <td class='righttb'><?=number_format($cgst,2);?></td>
                    </tr>
                    <tr class="gsthead" style="height:30px;">
                    <td></td>
                    <td class='righttb' colspan="3">Net Payable (Round of to)</td>
                    <td  colspan="1"></td><td></td>
                    <td class='righttb border-bottom border-top'><?=round($rows_o->net_amt);?>.00</td>
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

          $get_amount= AmountInWords(round($rows_o->net_amt));
          echo '<h3 style="margin-left:200px">'.$get_amount.'</h3>';
 ?> 
      </div>

    </section>

      <script>
     $(document).ready(function(){
          window.print();
     });
      </script>
  </body>
</html>