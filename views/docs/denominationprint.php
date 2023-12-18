<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Denomination-<?=((isset($_GET['denid']))?$_GET['denid']:'');?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    

  </head>
 <body>
      <style> 

      body{
          font-family: 'Courier New', monospace;
          font-size:13px;                                     
      }
      .uppercase{
          text-transform:uppercase;
      }
      .company-name{
           margin-bottom:20px;
      }
      .company-city{
           float:left;
           margin-right:20px;
      }
      .company-phone{
          float:left;
      }
      .line-divider{
           margin:10px 0px 10px 0px;
          border-bottom:1px dashed black;
      }
      .comp_code{
          float:left;
          margin-right:50px;
      }
      .table-top table tr .border-right{
           border-right:1px dashed black;
      }
      .table-top table tr td{
           padding:5px;
           text-align:right;
      }
      .table-top table tr td{
          border-bottom:1px dashed black;
      }
      .sheetno{
           padding:10px;
      }
      .totalcashcoll{
          text-decoration: underline dashed black;
      }
      .den_head{
          text-decoration: underline dashed black;
          text-align:center;
          width:200px;
          margin-top:30px;
          text-transform:uppercase;
          font-weight:bold;
      }
      .den-table table{
           width:40%;
           margin-top:20px;
      }
      .den-table table{
           width:50%;
           margin-top:20px;
      }
      .den-table table tr td{
           text-align:right;
           line-height:20px;
          border-bottom:1px dashed black;
      }
      .amtinword{
           padding:20px;
      }
      .signature{
          display:flex;
          width:100%;
          margin-top:20px;
      }
       .column{
          width:33.32%;
       }
      </style>
      <?php
session_start();

include 'db.php';
$user = $_SESSION['user_name'];
$den_id = $_GET['denid'];

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
return strtoupper(($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise);
}

$company = $db->query("SELECT * FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
$denomination = $db->query("SELECT * FROM denomination WHERE den_id='$den_id' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
$domestic_cash = $db->query("SELECT count(*) as datacount,sum(ramt) sum_amt FROM cash WHERE cast(tdate as date)='$denomination->col_date' AND pod_origin NOT IN ('PRO','PRC','PRD','COD') AND RIGHT(zone_code,3) NOT IN ('100') AND br_code='$denomination->br_code' AND comp_code='$stationcode' AND status = 'Billed'-- GROUP BY comp_code,br_code")->fetch(PDO::FETCH_OBJ);
$international_cash = $db->query("SELECT count(*) as datacount,sum(ramt) sum_amt FROM cash WHERE cast(tdate as date)='$denomination->col_date' AND RIGHT(zone_code,3) IN ('100') AND br_code='$denomination->br_code' AND comp_code='$stationcode' AND status = 'Billed'")->fetch(PDO::FETCH_OBJ);
$proprc_cash = $db->query("SELECT count(*) as datacount,sum(ramt) sum_amt FROM cash WHERE cast(tdate as date)='$denomination->col_date' AND pod_origin IN ('PRO','PRC','PRD','COD') AND br_code='$denomination->br_code' AND comp_code='$stationcode' AND status = 'Billed'")->fetch(PDO::FETCH_OBJ);
$branch = $db->query("SELECT * FROM branch WHERE stncode='$stationcode' AND br_code='$denomination->br_code'")->fetch(PDO::FETCH_OBJ);


?>
      <div class="company-name uppercase"><?=$company->stnname?></div>
      <div class="company-city uppercase"><?=$branch->br_name?></div>
      <div class="company-phone">Ph: <?=$branch->br_phone?></div><br><br>
      <div class="heading">CASH COLLECTION SUMMARY</div>
      <div class="line-divider"></div>
      <div class="comp_code">Service Center : <b><?=$denomination->br_code." / ".$stationcode?></b></div>
      <div class="coldate"><b><?=$denomination->col_date?></b></div>
      <div class="line-divider"></div>

      <div class="table-top">
           <table>
           <tr>
                <td class="border-right">DESCRIPTION</td>
                <td class="border-right">COUNT</td>
                <td class="border-right">COURIER CHARGE</td>
                <td class="border-right">GST @18% on CC</td>
                <td class="border-right"></td>
                <td class="border-right"></td>
                <td>Total Amt Rs.</td>
          </tr>
           <tr>
                <td class="border-right">DOMESTIC</td>
                <td class="border-right"><?=$domestic_cash->datacount?></td>
                <td class="border-right"><?=number_format($domestic_cash->sum_amt*0.82,2)?></td>
                <td class="border-right"><?=number_format($domestic_cash->sum_amt*0.18,2)?></td>
                <td class="border-right">0.00</td>
                <td class="border-right">0.00</td>
                <td><?=number_format($domestic_cash->sum_amt,2)?></td>
          </tr>
           <tr>
                <td class="border-right">INTERNATIONAL</td>
                <td class="border-right"><?=$international_cash->datacount?></td>
                <td class="border-right"><?=number_format($international_cash->sum_amt*0.82,2)?></td>
                <td class="border-right"><?=number_format($international_cash->sum_amt*0.18,2)?></td>
                <td class="border-right">0.00</td>
                <td class="border-right">0.00</td>
                <td><?=number_format($international_cash->sum_amt,2)?></td>
          </tr>
           <tr>
                <td class="border-right">PRO / PRC</td>
                <td class="border-right"><?=$proprc_cash->datacount?></td>
                <td class="border-right"><?=number_format($proprc_cash->sum_amt*0.82,2)?></td>
                <td class="border-right"><?=number_format($proprc_cash->sum_amt*0.18,2)?></td>
                <td class="border-right">0.00</td>
                <td class="border-right">0.00</td>
                <td><?=number_format($proprc_cash->sum_amt,2)?></td>
          </tr>
          </table>
      </div>

      <div class="sheetno">No of Cash Sheets : </div>
      <div class="line-divider"></div>
      <div class="sheetno">No of A/C Copies : <?=(round($domestic_cash->datacount)+round($international_cash->datacount)+round($proprc_cash->datacount))?></div>
      <div class="line-divider"></div>
      <div class="totalcashcoll">Total Cash Collection : </div>
      
      <div class="totaltable">
           <table>
                <tbody>
                <tr>
                     <td style="width:200px">DOMESTIC</td>
                     <td>Rs.<?=number_format($domestic_cash->sum_amt,2)?></td>
               </tr>
                <tr>
                     <td style="width:200px">INTERNATIONAL</td>
                     <td>Rs.<?=number_format($international_cash->sum_amt,2)?></td>
               </tr>
                <tr>
                     <td>PRO / PRC</td>
                     <td style="border-bottom:1px dashed black;">Rs.<?=number_format($proprc_cash->sum_amt,2)?></td>
               </tr>
                <tr>
                     <td>Total</td>
                     <td style="border-bottom:1px dashed black;line-height:30px">Rs.<?=number_format($domestic_cash->sum_amt+$international_cash->sum_amt+$proprc_cash->sum_amt,2)?></td>
               </tr>
               </tbody>
          </table>
      </div>

      <div class="denomination">
           <div class="den_head">Denomination</div>
      </div>
      <div class="den-table">
           <table>
                <tr>
                     <td>2000 X <?=round($denomination->c2000)?></td>
                     <td>Rs. <?=number_format($denomination->m2000,2)?></td>
                </tr>
                <tr>
                     <td>500 X <?=round($denomination->c500)?></td>
                     <td>Rs. <?=number_format($denomination->m500,2)?></td>
                </tr>
                <tr>
                     <td>200 X <?=round($denomination->c200)?></td>
                     <td>Rs. <?=number_format($denomination->m200,2)?></td>
                </tr>
                <tr>
                     <td>100 X <?=round($denomination->c100)?></td>
                     <td>Rs. <?=number_format($denomination->m100,2)?></td>
                </tr>
                <tr>
                     <td>50 X <?=round($denomination->c50)?></td>
                     <td>Rs. <?=number_format($denomination->m50,2)?></td>
                </tr>
                <tr>
                     <td>20 X <?=round($denomination->c20)?></td>
                     <td>Rs. <?=number_format($denomination->m20,2)?></td>
                </tr>
                <tr>
                     <td>10 X <?=round($denomination->c10)?></td>
                     <td>Rs. <?=number_format($denomination->m10,2)?></td>
                </tr>
                <tr>
                     <td>5 X <?=round($denomination->c5)?></td>
                     <td>Rs. <?=number_format($denomination->m5,2)?></td>
                </tr>
                <tr>
                     <td>Coins</td>
                     <td>Rs. <?=number_format($denomination->coins,2)?></td>
                </tr>
                <tr>
                     <td>Chq Det <?=round($denomination->ccheq)?> X</td>
                     <td>Rs. <?=number_format($denomination->cheqamt,2)?></td>
                </tr>
                <tr>
                     <td>UPI Amt <?=round($denomination->cupi)?> X</td>
                     <td>Rs. <?=number_format($denomination->upiamt,2)?></td>
                </tr>
                <tr>
                     <td>NEFT Amt <?=round($denomination->cneft)?> X</td>
                     <td>Rs. <?=number_format($denomination->neftamt,2)?></td>
                </tr>
                <tr>
                     <td>Total</td>
                     <td>Rs. <?=number_format($denomination->collamt,2)?></td>
                </tr>
          </table>
      </div>
      <div class="amtinword">Rs.&nbsp;&nbsp;&nbsp;&nbsp;<?=AmountInWords($denomination->collamt)?></div>

      <div class="signature">
          <div class="column">
               Cash Received<br>
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               By
          </div>
          <div class="column">
               Cash Sent<br>
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               By
          </div>
          <div class="column">
               Cash Prepared<br>
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               By
          </div>
      </div>

      <script>
          $(document).ready(function(){
               window.print();
               if(window.location.host!='localhost')
               window.history.pushState('PCNL', 'Title', '/');
          });

      </script>
  </body>
</html>