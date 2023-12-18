<?php 
/* File name   : tax.php
 Begin       : 2020-03-04
 Description : class tax file saves master tax slab in appliaction
               Default Header and Footer

 Author: zabiullah

 (c) Copyright:
              codim solutions
============================================================+*/

/**
 * render the tax in to class 
 * get database string to establish connection from sql server
 * Capture the tax data and save ion to database
 * @author zabi  
 * @since 2020-03-04
 */

/* Include the main tax master (search for installation path). */

defined('__ROOT__') OR exit('No direct script access allowed');

 include 'db.php'; 

	 class phpmail extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - phpmailer';
             // $this->view->render('cnote/fetch');
          }
      }
      
      require 'controllers/phpmailer/dompdf/autoload.inc.php';
      require 'controllers/phpmailer/PHPMailerAutoload.php';

      //require 'db.php';
      use Dompdf\Dompdf;
      
     if(isset($_POST['invno'])){

   
      //generating pdf using dompdf
      
      ob_start();
      
      ?>
      <!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>Invoice</title>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
          
        </head>
        <style>
       @page {
        size: A4;
        margin: 4mm 4mm 4mm 4mm;
       }
       @media print{
        body {-webkit-print-color-adjust: exact !important;}

     table { page-break-after:auto }                          
     tr    { page-break-inside:avoid; page-break-after:auto }
     td    { page-break-inside:avoid; page-break-after:auto }
     thead { display:table-header-group }
     tfoot { display:table-footer-group }                      
     .amountcalc{
          page-break-inside: avoid;
     }
     .bank-details{
          page-break-before: always;
     }
     .transdate{
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
           width:320px;
           border:1px solid black;
           padding:15px;
           position: relative;
          
      }
      #project{
           margin-left:50%;
           margin-bottom:-210px;
           height:260px;
          width:350px; 
          border:1px solid black;
          /* margin-left:1%; */
          line-height:1.4;
          position: relative;
      }
      .gsthead{
           margin-top:20px;
      }
      .left-20{
           margin-left:100%;
      }
      .billdata{
           width:100%;
           font-size:12px;
           margin-top:5px;
           height:27px;
           border-bottom:1px solid black;
      }
      .customerbox{
           padding:10px;
           font-size:18px;
      }
      .flex-box-3{
           
           float: left;
           
      }
      .transdate{
           height:20px;
           width:100%;
           padding:10px;
           /* margin-top:-200px; */
           /*border-bottom: 1px solid black;*/
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
           font-size:10px;
           line-height:2;
      }
      .tablehead th{
           border-bottom: 1px solid black; 
           border-top: 1px solid black; 
      }
      .border-bottom{
           border-bottom: 1px solid black; 
      }
      .cnotestable{
           width:550px;
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
           width:600px;
      }
      .note{
           width:600px;
           padding:5px;
           font-size:12px;
           margin-left:30px;
      }
      .notehead{
           font-weight:bold;
           text-decoration: underline;
      }
      .bank-details{
           font-size:14px;
           padding:25px;
           line-height: 30px;
      }
      .bankhead{
           font-weight:bold;
      }
      .amountcalc{
          border-top: 1px solid white;          
          page-break-inside: avoid;
          font-size: 13px;
     }
      .red{
           background:red!important;
      }
      .p{
           line-height:2px;
      }
      .tablehead1 th{
     border-bottom: 1px solid white; 
      }
      .transdate{
          page-break-inside: avoid;
     }
     .flex-container{
          margin-bottom: 0px;
     }
     #qrcode img{
     width:120px !important;
}
        </style>
       
        <body id="printable">
      <?php 
      include 'db.php';
      
           $invno = $_POST['invno'];
           $invnostn = $_POST['station'];
           //Invoice Info
           $fetchdata = $db->query("SELECT * FROM invoice WHERE invno='$invno' AND comp_code='$invnostn'");
           $rowinvoice = $fetchdata->fetch(PDO::FETCH_OBJ);
           $origfrmDate = "$rowinvoice->fmdate";
           $frmDate = date("d-m-Y", strtotime($origfrmDate));
      
           $origtoDate = "$rowinvoice->todate";
           $toDate = date("d-m-Y", strtotime($origtoDate));
           //Company Info
           $deliverystatus = $db->query("SELECT count(status) as counting FROM credit WHERE comp_code='$invnostn' AND invno='$invno' AND status='Noservice-return' AND cnote_serial='COD'")->fetch(PDO::FETCH_OBJ);
           $getcompanyname = $db->query("SELECT * FROM station WHERE stncode='$rowinvoice->comp_code'");
           $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);
           //Customer Info
           $getcustomerinfo = $db->query("SELECT * FROM customer WHERE cust_code='$rowinvoice->cust_code' AND comp_code='$invnostn'");
           $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
           $cust_code = $rowinvoice->cust_code;
           //POD info
           $fetchdiscount = $db->query("SELECT spl_discountD FROM customer WHERE comp_code='$invnostn' and cust_code = '$rowinvoice->cust_code'");
           $rowinvoicediscount = $fetchdiscount->fetch(PDO::FETCH_OBJ);

           $getcode_consignment = $db->query("SELECT tdate,count(cno) as countcod FROM credit  WHERE comp_code='$invnostn' AND invno='$invno' AND cnote_serial='COD' AND status='Invoiced' group by tdate ORDER BY tdate")->fetch(PDO::FETCH_OBJ);
           $codcount = $getcode_consignment->countcod;
           
            $No_Delivery = $db->query("SELECT count(cno) as countcod FROM credit  WHERE comp_code='$invnostn' AND invno='$invno' AND status='Noservice-return' AND cnote_serial='COD'")->fetch(PDO::FETCH_OBJ);
            $nodelivery = $No_Delivery->countcod;
            
           $customercode = $rowinvoice->cust_code;
           $total_amt = $rowinvoice->grandamt;



           
           function getcustomerfscandhandling($db,$invnostn,$customercode,$total_amt){
                $getfscofcustomer = $db->query("SELECT cust_fsc,cust_gstin,fsc_type,br_code FROM customer WITH (NOLOCK) WHERE cust_code='$customercode' AND comp_code='$invnostn'");
                $rowgetfsc = $getfscofcustomer->fetch(PDO::FETCH_OBJ);
                if($rowgetfsc->fsc_type=='D'){
                     $fscbr = $db->query("SELECT branchtype FROM branch WITH (NOLOCK) WHERE stncode='$invnostn' AND br_code='$rowgetfsc->br_code'")->fetch(PDO::FETCH_OBJ);
                     $getfsc = $db->query("SELECT fsc,f_hand_charge FROM fscmaster WITH (NOLOCK) WHERE comp_code='$invnostn' AND min_sales_amt<='$total_amt' AND max_sales_amt>='$total_amt' AND branchtype='$fscbr->branchtype' ORDER BY tdate DESC");
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
           
              $rowcustcode = $rowinvoice->cust_code;
              function getcoderate($invnostn,$db,$rowcustcode){
               $getcustomerinfo = $db->query("SELECT * FROM customer WHERE comp_code='$invnostn' AND cust_code='$rowcustcode'");
               $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
               $cod_per_kg = $rowcustomer->cod;
               return round($cod_per_kg);
          }
          
               $gstcode = substr($rowcustomer->cust_gstin,0,2);
               $statename = $db->query("SELECT state_name FROM state WHERE gstcode='$gstcode'")->fetch(PDO::FETCH_OBJ);
           $gst=($rowinvoice->invamt/100)*18;
           if(empty($rowcustomer->cust_gstin)){
                $sgst = '0';
                $cgst = '0';
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


          <div class="flex-container">
          <div id="company" class="clearfix">
               <div class=""><img src="views/image/TPC-Logo.jpg" class="image"></div>
               <div class="dom"><strong>DOMESTIC & INTERNATIONAL</strong></div>
              <div><?=$rowcompany->stnname;?></div>
              <div><?=$rowcompany->stn_addr;?>,<br /><?=$rowcompany->stn_city;?>,<?=$rowcompany->stn_state;?></div>
              <div>Contact: <?=$rowcompany->stn_mobile;?></div>
              <div><a href="mailto:<?=$rowcompany->stn_email;?>"><?=$rowcompany->stn_email;?></a><br><br><strong>GSTIN</strong>: <?=$rowcompany->stn_gstin;?>&nbsp;&nbsp;&nbsp;&nbsp; <strong>SAC</strong> : 996812</div>
      
            </div>
            <div id="project">
              <div class="billdata" style="width:350px;">
                     <div class="flex-box-3" style="font-size:13px;margin-left:9px;"><b>Bill No : <?=$invno;?></b></div>
                     <div class="flex-box-3" style="margin-left:7px;font-size:13px;"><b>Dt : <?=$rowinvoice->invdate;?></b></div>
                     <div class="flex-box-3" style="margin-left:7px;font-size:13px;"><b>Code : <?=$rowinvoice->cust_code;?></b></div>
                     </div>
              <div class="customerbox"><div><?=$rowcustomer->cust_name;?></div>
              <div><?=$rowcustomer->cust_addr;?></div>
              <div><?=$rowcustomer->cust_city;?></div>
              <div><?=$rowcustomer->cust_state;?></div>
              <div  style="font-size:14px;"><p class="p"><strong>Branch : </strong><?=$rowinvoice->br_code;?></p></div>
              <div style="font-size:12.5px;"><p class="p"><strong>GSTIN : </strong><?=$rowcustomer->cust_gstin;?> <strong>POS : </strong><?=substr($rowcustomer->cust_gstin,0,2).' - '.$statename->state_name;?></p></div>
              <input type="hidden" id="signedqrcode" value="<?=$rowinvoice->signedirn?>">

              </div>
           </div>
            </div>
            
            <h3 style="margin-left:280px;margin-top:0px;padding-top:0px;">TAX INVOICE</h3>
      
           <section>
          
            <table class="cnotestable">
              <thead>
                <tr class="tablehead1">
                  <th style="width:25px;"></th>
                  <th style="width:60px;"></th>
                  <th style="width:60px;"></th>
                  <th style="width:80px;"></th>
                  <th style="width:50px;"></th>
                  <th style="width:50px;"></th>            
                  <th style="width:25px;margin-left:20px;"></th>
                  <th style="width:60px;"></th>
                  <th style="width:60px;"></th>
                  <th style="width:90px;"></th>
                  <th style="width:50px;"></th>
                  <th style="width:50px;"></th>  
                </tr>
              </thead>
               <tbody class="tablebody">
           
                    </tbody>
                          <tbody class="amountcalc">
                          <tr class="gsthead" style="height:50px;">
                          <td></td>
                          <td class='righttb' colspan="2"  style="text-align:left;">Courier Charges</td>
                          <td colspan="8"></td>
                          <td class='righttb'><?=number_format($rowinvoice->grandamt,2);?></td>
                          </tr>
                          <tr class="gsthead" style="height:50px;">
                          <td></td>
                          <td class='righttb' colspan="2"  style="text-align:left;">Fuel Charges <?=getcustomerfscandhandling($db,$invnostn,$customercode,$total_amt)[0];?> %</td>
                          <td colspan="8"></td>
                          <td class='righttb'><?=number_format($rowinvoice->fsc,2);?></td>
                          </tr>
                          <?php if($codcount==0){        ?>
                     <?php    }else{  ?>
                    <tr class="gsthead">
                    <td></td>
                    <td class='righttb' colspan="2"  style="text-align:left;">Cod Cnotes&nbsp;&nbsp;(<?=((($codcount==0) ? '0 * '.getcoderate($invnostn,$db,$rowcustcode).'' : $codcount. "*" .getcoderate($invnostn,$db,$rowcustcode)));?>)</td>
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
                   <td class='righttb' colspan="2"  style="text-align:left;">Handling Charges</td>
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
                    <td  class='righttb' colspan="2"  style="text-align:left;">Discount&nbsp;<?=$custdisc;?>&nbsp;%</td>
                    <td colspan="8"></td>
                    <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->spl_discount,2);?></td>                    
                    </tr>
                    <?php    }  ?>
                    <tr class="gsthead" style="height:2px;">
                    <td></td>
                    <td class='righttb' colspan="2"  style="text-align:left;">Invoice Value</td>
                    <td colspan="8"></td>
                    <td class='righttb' style="text-align:right;"><p style="border-top:1px solid black;border-bottom:1px solid black;padding-top:3px;padding-bottom:3px;"><?=number_format($rowinvoice->invamt,2);?><p></td>

                    </tr>  
                          <tr class="gsthead" style="height:20px;">
                          <td></td>
                          <td class='righttb' colspan="2"  style="text-align:left;">IGST @ 18 %</td>
                          <td colspan="8"></td>
                          <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->igst,2);?></td>
                          </tr>
                          <tr class="gsthead" style="height:20px;">
                          <td></td>
                          <td class='righttb' colspan="2"  style="text-align:left;">SGST @ 9 %</td>
                          <td colspan="8"></td>
                          <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->cgst,2);?></td>
                          </tr>
                          <tr class="gsthead" style="height:20px;">
                          <td></td>
                          <td class='righttb' colspan="2"  style="text-align:left;">CGST @ 9 %</td>
                          <td colspan="8"></td>
                          <td class='righttb' style="text-align:right;"><?=number_format($rowinvoice->sgst,2);?></td>
                          </tr>
                          <tr class="gsthead" style="height:30px;">
                          <td></td>
                          <td class='righttb' colspan="2"><strong>Net Payable</strong></td>
                          <td colspan="8"></td>
                          <td class='righttb border-bottom border-top'><?=number_format($rowinvoice->netamt,2);?></td>
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
      
                $get_amount= AmountInWords(round($rowinvoice->netamt));
                echo '<h3 style="margin-left:100px">'.$get_amount.'</h3>';
       ?> 
            </div>
      
          </section>
          <footer class="footer" style="margin-top:0px;">
            <div style="text-align:right;height:40px;margin-left:80px;">for <?=$rowcompany->stnname;?></div>
            <div style="text-align:right;height:30px;margin-left:80px;">Authorised Signatory</div>
      
            <div class="note" style="margin-top:0px;">
            <div class="notehead">Note:</div>
            <div>1. Payment should be mad within 10 days from the date of receipt of invoice, failing which we will be forcing to stop forwarding you further consignments.</div>
          <div>2. Payment should be made by the way of NEFT/CHEQUE/DD and forwarded to <?=$rowcompany->stnname;?>, quoting your Inv.No. and Inv.date.</div>
          <div>3. We will receive payment only bill to bill by the way of NEFT/CHEQUE/DD/CASH.</div>          
          <div>4. For Cheque bounching a penaly of Rs.250/- will be collected from you.</div>
          <div>5. Any delay in payment an interest of 18% per annum will be charged.</div>
          <div>6. Bill correction, if any to be received within 7 days time.</div>
          <div>7. GST must be paid as per Govt. of India norms. GST No : <?=$rowcompany->stn_gstin;?> || PAN No : AAOFT6830F</div>
          <div>8. If you are a defaulter licensee, please make payment by DEMAND DRAFT/CASH only.</div>      
      
          <br><br>
               <div><span class="bankhead">Bank Details:</span></div>
               <div>ACCOUNT NAME  :  <?=$rowcompany->baccname;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IFSC CODE  : <?=$rowcompany->ifsc;?></div>
               <div>ACCOUNT NO AND ACCOUNT TYPE :  <?=$rowcompany->baccno;?>/<?=$rowcompany->bacctype;?>&nbsp;&nbsp; ||&nbsp;&nbsp; MICR CODE  :  <?=$rowcompany->micr;?></div>
               <div>BANK NAME : <?=$rowcompany->bankname;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SWIFT CODE  :   <?=$rowcompany->swift;?></div>
               <div>BRANCH PLACE  :  <?=$rowcompany->bbranch;?></div>
            </div>
           
            </footer> 
            
           
        
           

          
            
            <div class="transdate <?=(($rowinvoice->irn_status=='Issued')? '' : 'flex-container')?>" style="<?=(($rowinvoice->irn_status=='Issued')? 'margin-bottom:35px;' : '')?><?=(($rowinvoice->irn_status=='Issued')? '' : 'margin-top:60px;')?>margin-left:25px;">
                     <?php if($rowinvoice->irn_status=='Issued'):   ?>
                   <div class="data1 flex-container">
                   <div ><strong>Irn No :</strong> <?=$rowinvoice->irn_no?></div>
                    <div style="margin-left:80%;"></div>
                    <!--<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=fghghghghgh&choe=UTF-8" title="Link to Google.com" width="110" height="100" />-->
                   </div>
                   <?php else: ?>

                    <?php endif;  ?> 
                    <?php if($rowinvoice->irn_status=='Issued'):   ?><div class="data2 flex-container"> <?php else: ?>  <?php endif;  ?>
                   <div ><strong>BILL/Statement of Transaction between </strong></div>
                    <div style=";margin-left:50%"><?=$frmDate;?>&#160;&#160; AND&#160;&#160; <?=$toDate;?></div>
                    <?php if($rowinvoice->irn_status=='Issued'):   ?></div> <?php else: ?>  <?php endif;  ?>
                   
                    
               </div>
          
         
          
            <table class="cnotestable">
              <thead>
                <tr class="tablehead">
                  <th style="width:25px;">SNo</th>
                  <th style="width:60px;">DATE</th>
                  <th style="width:60px;">POD NO</th>
                  <th style="width:80px;">DESCRIPTION</th>
                  <th style="width:50px;">WT</th>
                  <th style="width:50px;">AMOUNT</th>            
                  <th style="width:25px;margin-left:20px;">SNo</th>
                  <th style="width:60px;">DATE</th>
                  <th style="width:60px;">POD NO</th>
                  <th style="width:90px;">DESCRIPTION</th>
                  <th style="width:50px;">WT</th>
                  <th style="width:50px;">AMOUNT</th>  
                </tr>
              </thead>
               <tbody class="tablebody">
                <?php 
                          if($rowinvoice->br_code=='TR'){
                               $getpod = $db->query("SELECT * FROM ccharge WHERE invno='$invno' ORDER BY tdate");
                               $i = 0;
                               while ($rowpod = $getpod->fetch(PDO::FETCH_OBJ))
                               {
                               $fetchdestname = $db->query("SELECT dest_name FROM destination WHERE dest_code='$rowpod->destn' AND comp_code='$rowinvoice->comp_code'")->fetch(PDO::FETCH_OBJ);
                               $i++;
                               if($i % 2 != 0){
                                    echo "<tr>";   
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
                          $getpod = $db->query("SELECT * FROM credit WHERE invno='$invno' ORDER BY tdate");
                          $i = 0;
                          while ($rowpod = $getpod->fetch(PDO::FETCH_OBJ))
                          {
                          $fetchdestname = $db->query("SELECT dest_name FROM destination WHERE dest_code='$rowpod->dest_code' AND comp_code='$rowinvoice->comp_code'")->fetch(PDO::FETCH_OBJ);
                          $i++;
                          if($i % 2 != 0){
                               echo "<tr>";   
                          }
                          echo "<td style='width:25px;text-align:right;padding-right:5px;background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".$i."</td>";
                          echo "<td style='background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".date("d-m-Y", strtotime($rowpod->tdate))."</td>";
                          echo "<td style='background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".$rowpod->cno."</td>";
                          echo "<td style='background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".substr($fetchdestname->dest_name,0,11)."</td>";
                          echo "<td style='background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".number_format($rowpod->wt,3)."</td>";
                          echo "<td style='background-color:".(($rowpod->status=='Noservice-return')? 'green' : '').";color:".(($rowpod->status=='Noservice-return')? 'white' : '').";'>".number_format($rowpod->amt,2)."</td>";
                          if($i % 2 == 0){
                          echo "</tr>";   
                          }
                          }
                          }                                  
                          ?>
                  
            </table>
    
        </body>

      </html>
      
      <?php
 
      
      $dompdf = new Dompdf();
      $htmlcontents = ob_get_clean();
      $dompdf->loadHtml($htmlcontents);
     $options = $dompdf->getOptions(); 
    $options->set(array('isRemoteEnabled' => true));
    $dompdf->setOptions($options);
      $dompdf->setPaper('A3', 'landscape');
      $dompdf->render();
      $attachment_file = $dompdf->output();
            
      $mail = new PHPMailer;
      //$mail->SMTPDebug = 2;
      $mail->IsSMTP();   // Set mailer to use SMTP
      $mail->Host = 'smtp.bizmail.yahoo.com';   // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;  // Enable SMTP authentication
      $mail->Username = 'pcbs@pcnl.in'; // your email id
      $mail->Password = 'hcygtgklfglvigvw'; // your password                      
      $mail->Port = 465;     //587 is used for Outgoing Mail (SMTP) Server.
      $mail->SMTPSecure = 'ssl';                     
      $mail->SetFrom('pcbs@pcnl.in', 'TPC'.$invnostn);
      $mail->addAddress($rowcustomer->cust_email);   // Add a recipient
      //$mail->AddReplyTo($rowcompany->stn_email, 'PCNL '.$invnostn);
      $mail->isHTML(true);  // Set email format to HTML
      
      $bodyContent = '<h7><strong>Dear Customer,</strong></h7>';
      $bodyContent .= '<p>Your Invoice has been generated on '.$rowinvoice->invdate.'for amount Rs.'.round($rowinvoice->netamt).'/-.</p>';
      $bodyContent .= '<p>Please find the attached invoice with this mail.</p><br>';
      $bodyContent .= '<p><strong>Kind Regards,</strong></p><p>TPC-MAA '.$invnostn.'</p>';
      $mail->Subject = 'Invoice '.$invno.' from TPC-MAA';
      $mail->addStringAttachment($attachment_file,''.$rowinvoice->cust_code.'-'.$invno.'.pdf');
      
      $mail->Body    = $bodyContent;
      $mail_user    = $rowcustomer->cust_email;
      if(!$mail->send()) {
        echo 'Email was not sent.';
        echo 'Mailer error: ' . $mail->ErrorInfo;
        $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','Invoice','Created','Email was not Sent to Customer $rowinvoice->cust_code - ERROR : $mail->ErrorInfo','$mail_user',getdate())");   

      } else {
          $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','$invnostn','$invno','Email has been Sent to Customer $rowinvoice->cust_code','$mail_user',getdate())");   
        echo 'Email has been sent.';
      }
      
     }
      ?>
     