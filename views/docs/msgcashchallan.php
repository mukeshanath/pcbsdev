<?php
//testing
//  $http_array = base64_decode($_GET['cno']);

//  parse_str($http_array,$cnotes);

// echo json_encode($cnotes);exit;
//testing

// encodedtype = $_GET['type'];
// $decoded_type = base64_decode($encodedtype);
// $print_customer = base64_decode($_GET['customer']);$

//echo $decoded_type;exit;
//echo $print_customer;exit;

// if(trim($decoded_type)=='printed' || trim($decoded_type)=='reprinted'){
     
// }
// else{
//      echo "Some Data has been missed";exit;
// }

session_start();

?>
<style>

@import url('http://fonts.cdnfonts.com/css/calibri-light');
      @page {
  size: A4;
  /* size: portrait; */
  margin: 4mm 4mm 4mm 4mm;
 }
 @media print{
     .main-box{
     width:100%!important;
     }
     #single-cnote{
          page-break-after: always;
     }
     .sender-documents table tr th{
     color: black;
     background:black;
     }
}
.container-page{
     padding:10px;
     font-family: 'Gill Sans', 'Gill Sans MT' ,'Trebuchet MS', sans-serif;
     font-size: 10px;
}
.main-box{
     /* color:red; */
     display: flex;
     padding: 0px;
     border: 1.5px solid black;
     width:900px;
     margin: 0 auto;
}
.left{
     width:45%;
     border-right:1.5px solid black;
}
.right{
     width:40%;
     border-right: 1px solid black;
}
.right-corner{
     width:17%;
}
.text-center{
     text-align: center;
     }
     .from-top{
          height:130px;
          border-bottom: 1px solid black;
     }
     .to-top{
          height:24px;
     }
.to-top,.receiver-details,.receiver-tracking{
     display:flex;
     border-bottom: 1.5px solid black;
}
.from-menu{
     width:10%;
}
.from-menu,.from,.receiver-head,.receiver-destn,.receiver-mode{
     border-right:1.5px solid black;
}
.from,.from-date{
     width:45%;
}
.from-date,.receiver-destn{
     padding:5px;
}
.from{
     font-weight: bold;
     font-size: 20px;
}
.margin-auto{
     padding:0px;
}
.sender-details{
     height: 57px;
    padding: 2px;
    line-height: 3px;
    border-bottom: 1.5px solid black;
}
.sender-documents{
     display: flex;
     border-bottom: 1.5px solid black;
     height:28px;
     font-size: 9px;
}
.sender-documents table{
     font-size:9px;
     width: 100%;
}
.sender-documents table td,.sender-documents table th{
     border-left: 1px solid black;
}
.sender-documents table tr th{
     font-weight: 100;
     color: #fff;
     background-color: black;
}
.sender-documents table tr td{
     font-weight: bold;
     color: black;
     font-family: 'Gill Sans', 'Gill Sans MT','Trebuchet MS', sans-serif;
}
.sd-value{
     margin-left: 50px;
}
.auth-value{
     display: flex;
     margin-top: 10px;
}
.auth-sign{
     margin-left: 80px;
}
.image{
     width:80%;
}
.sender-company{
     text-align: center;
     line-height: 0px;
     height:130px;
}
.sender-authority{
     /* padding: 5px; */
     height:80px;
     color: #000;
     font-weight: bold;
     font-family: 'Gill Sans', 'Gill Sans MT', 'Trebuchet MS', sans-serif;
}
.barcode{
     width:35%;
}
.company-address{
     line-height: 10px;
}
.receiver-head{
     width:15%;
}
.receiver-destn{
     width:50%;
}
.receiver-mode{
     width: 30%;
}
.receiver-details{
     height:85px;
     width: 100%;
}
.receiver-address{
     /* padding:10px; */
     width:100%;
     line-height: 10px;
     margin-top: 2px;
}
.weight{
     display: flex;
     text-align: center;
     border: 1.5px solid black;
     height:93%;
     width:98%;
}
.kg{
     width:50%;
     border-right: 1.5px solid black;
}
.gms{
     width:50%;
}
.wt-box{
     padding:3px;
     width:37%;
}
.wt-val{
     line-height:56px;
}
.receiver-tracking{
     height:13px;
}
.track-url{
     width:60%;
     border-right: 1.5px solid black;
}
.challan-belongsto{
     width:39%;
     text-align: center;
}
.consignment-details{
     display: flex;
     height:80px;
}
.cnote-messages-left{
     padding:6px;
     width:60%;
     border-right: 1.5px solid black;
}
.cnote-messages-right{
     padding-top: 20px;
     width: 41.6%;
     text-align: center;
}
.other-barcode{
     margin-top: 15px;
     border-top: 1.5px solid black;
}
.other-barcode1{
     width:80%;
}
.footer-text{
     font-size: 9px;
     width:800px;
     margin:0 auto;
     text-align: right;
}
.dashed-line{
     text-align: center;
}

.company-name-logo{
     margin-top: -10px;
     color: #000;
     text-align: center;
     font-weight: bold;
     font-family: 'Gill Sans', 'Gill Sans MT','Trebuchet MS', sans-serif;
}
.company-name-logo-bottom{
     font-size: 10px;
     margin-top: -10px;
     color: #DA231B;
}
.top-company-logo{
     text-align: center;
}
.company-logo-bottom{
     margin-left: 3px;
     padding: 5px;
     line-height: 20px;
}
/* Right corner div */
.form-number{
     margin-top: 0px;
     text-align: center;
     height: auto;
    
     border-bottom: 1px solid black;
     font-size: 40px;
}
.br-ncode{
     margin-top: 0px;
     text-align: center;
     height: auto;
     border-bottom: 1px solid black;
     font-size: 24px;
}
.date-time-pcs{
     height: auto;
     color: #000;
     border-bottom: 1px solid black;
     font-size: 10px;
     padding: 10px 0px 10px 8px;
}
.wt-head{
     height: auto;
     color: #000;
     border-bottom: 1px solid black;
     font-size: 10px;
     padding: 2px 0px 2px 8px;
}
.wt-bd{
     height: auto;
     color: #000;
     display: flex;
     border-bottom: 1px solid black;
     font-size: 10px;
     padding: 2px 0px 2px 8px;
}
.charges{
     height: auto;
     color: #000;
     border-bottom: 1px solid black;
     font-size: 10px;
     padding: 2px 0px 2px 8px;
}

.wt-child{
width: 49%;
}
.cashlabel{
   height: 60px;
   text-align: center;
   color: #000;
     font-size: 15px;
     font-weight: bold;
     font-family: sans-serif;
}
</style>
<?php

 error_reporting(0);

include 'db.php';
//  $user = $_SESSION['user_name'];

// $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
// $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
// $rowuser = $datafetch['last_compcode'];
// if(!empty($rowuser)){
//      $stationcode = $rowuser;
// }
// else{
//     $userstations = $datafetch['stncodes'];
//     $stations = explode(',',$userstations);
//     $stationcode = $stations[0];     
// } 

for($a=0;$a<1;$a++){
     $cno = $_GET['cno'];
     $stationcode = $_GET['stationcode'];
//fetch data for print notes
// $challan_bottom = array();

// $printflags = $db->query("SELECT credit_accounts_copy,credit_pod_copy,credit_consignor_copy FROM customer WHERE cust_code='$print_customer' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
// if($printflags->credit_pod_copy){
//      array_push($challan_bottom,"CREDIT - POD COPY");
// }
// if($printflags->credit_consignor_copy){
//      array_push($challan_bottom,"CREDIT - CONSIGNOR COPY");
// }
// if($printflags->credit_accounts_copy){
//      array_push($challan_bottom,"CREDIT - ACCOUNTS COPY");
// }
//$challan_bottom = array("CREDIT - POD COPY","CREDIT - CONSIGNOR COPY","CREDIT - ACCOUNTS COPY");
$challan_bottom = array("CONSIGNOR COPY"); // accounts copy removed by cr PT-227 jira list -- ,"ACCOUNTS COPY"


for($i=0;$i<count($challan_bottom);$i++){
    
     $challanbg = array("#fff","#f5f5c2");
     //get cnote details
     $cashcno = $db->query("SELECT *, FORMAT(date_added, 'yyyy/MM/dd HH:mm') AS time,(select br_address from branch where branch.br_code=cash.br_code  and branch.stncode=cash.comp_code) as branchaddr,(select br_city from branch where branch.br_code=cash.br_code  and branch.stncode=cash.comp_code) as branchcity,(select br_state from branch where branch.br_code=cash.br_code  and branch.stncode=cash.comp_code) as branchstate,(select br_mobile from branch where branch.br_code=cash.br_code  and branch.stncode=cash.comp_code) as branchmobile FROM cash  WHERE cno='$cno' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
     //get bookcounter details
     $bcounter = $db->query("SELECT * FROM bcounter WHERE book_code='$cashcno->book_counter' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);

     //get branch address det
     //get company Details
     $company = $db->query("SELECT * FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);

     $vehicle_number = $db->query("SELECT * from destination where dest_code='$cashcno->dest_code' and comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
     $vehicle_no = $vehicle_number->vehicle_no;
     
?>




<title>Cashchallan-<?=$stationcode.$cno?></title>
<div id="single-cnote">
<div class="container-page">
     <div class="main-box" _style="background:<?=$challanbg[$i]?>">
          <div class="left">
               <div class="from-top">

                    <div class="top-company-logo">
                          <img src="views/image/TPC-Logo.jpg" class="image">
                    </div>
                    <div class="company-name-logo">
                         <?php
                             $gta_weight = 0.5;
                             $weightsmax = max($cashcno->v_wt,$cashcno->wt);
                             $gtaval = (($company->gta=='Y' && empty($cashcno->gst) && ($gta_weight<=$weightsmax) && $cashcno->mode_code=='ST') ? '105' : '118');

                             $sac = (($gtaval=='105') ? '996791' : '996812');
                         ?>
                    <p style="font-weight:normal;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <b>SAC:&nbsp;</b><?=$sac;?><br>
                    <b><?=$company->stnname?></b></p>
                    </div>
                    <?php

if($challan_bottom[$i]=="CONSIGNOR COPY")
 {
  echo '<div class="company-name-logo" style="line-height:3px;margin-top:10px;">
  <p><b>'.$cashcno->branchaddr.'</b></p>
  <p><b>'.$cashcno->branchcity.'</b></p>
  <p><b>'.$cashcno->branchmobile.'</b>,<b>'.$cashcno->branchmail.'</b></p>
   </div>';

}
?>
                    <?php 
               if($challan_bottom[$i]=="PROOF OF DELIVERY")
               {
                    ?>
                    <div class="">
                         
                    </div>
                    <p style="text-align: center;line-height:14px;"><?=$company->stn_addr?>,<?=$company->stn_city?>,<?=$company->stn_state?>,
                    <br><?=$company->stn_phone?>,<?=$company->stn_mobile?>,<?=$company->stn_email?>.&nbsp;&nbsp;&nbsp;
                      <b>GSTIN : </b><?=$company->stn_gstin?></p>
                      <?php } ?>
                    <!-- <div class="from-menu">
                         Shipper's Details
                    </div>
                    <div class="from text-center margin-auto">
                         <div class="margin-auto"><?=$stationcode?></div>  
                    </div>
                    <div class="from-date text-center">
                         Date: <?=$cashcno->tdate?>
                    </div> -->
               </div>
               <div class="cus" style="margin-left: 10px; margin-top:8px;">
               <b><u>Shipper Details</u></b>
               <div class="sender-details">
              
                    <p><?=(!empty($cashcno->shp_name)?$cashcno->shp_name:'NA')?></p>
                    <p><?=(!empty($cashcno->shp_addr)?$cashcno->shp_addr:'NA')?>,<?=(!empty($cashcno->shp_pin)?$cashcno->shp_pin:'NA')?></p>
                    <p>Mobile : <?=(!empty($cashcno->shp_mob)?$cashcno->shp_mob:'NA')?></p>
                    <p>GSTIN : <?=(!empty($cashcno->gst)?$cashcno->gst:'NA')?></p>
                    
                  
                     <!-- <?=$bcounter->book_addr?> -->
                    <!-- <p><?=$bcounter->book_phone?>, <?=$bcounter->book_email?></p> -->
                    <!-- Flyer No.: Ref No.:  -->
                    <!-- GSTIN : <?=$bcounter->book_gstin?> -->
               </div>
               </div>
               
               <div class="sender-documents">
                    <table>
                         <tr>
                              <th>CONTENTS</th>
                              <th>DECLARED VALUE</th>
                              <th>VOLUMETRIC WEIGHT</th>
                         </tr>
                         <tr>
                              <td style="text-align:center;"><?=$cashcno->contents?></td>
                              <td style="text-align:center;"><?=$cashcno->dc_value?></td>
                              <td style="text-align: center;"><?=number_format((float)$cashcno->v_wt, 3, '.', '')?> Kg </td>
                         </tr>
                    </table>
               </div>
               <div class="sender-authority" style="margin-left: 10px;text-align: justify; margin-top:10px;">
               <?php 
               if($challan_bottom[$i]=="PROOF OF DELIVERY")
               {
                    ?>
                    <div style="text-align:center">
                    <img style="width:180px;margin-bottom:5px;" src="barcode?text=<?=$stationcode.$cashcno->cno?>&codetype=code128&orientation=horizontal&size=40&print=<?=$stationcode.$cashcno->cno?>" class="barcode"><h1 style="color:black;margin-top:1%;"><?=$stationcode.$cashcno->cno?><?=($decoded_type=='reprinted'?' (Duplicate)':'')?></h1>
                    </div>
                    
                    <?php } else { ?>
                    <div class="auth-content" style="margin-right: 10px;">
                    I/We declare that this consignment does not contain personal mail, cash, jewellery, contraband, illegal drugs, any prohibited items and commodities which can cause safely hazards while transported by air and Surface.
                    
                    <br>
                    Non Negotiable Consignment Note / Subject to Chennai Jurisdiction. Please refer to all the terms & conditions printed overleaf of this consignment note.
                     </div>
                    <div class="auth-value">
                    <!-- <div class="auth-name"> Sender's Name:</div> -->
                    <div class="auth-sign" style="margin-left: 10px; ">SENDER'S SIGN:</div>
                    </div>
                    <?php } ?>
               </div>
               <?php 
               if($challan_bottom[$i]!="PROOF OF DELIVERY")
               {
                    ?>
               <!-- <div class="sender-company">
                    <div class="sender-barcode">
                    <img src="https://pcbsdev.cdssapps.in/barcode?text=<?=$stationcode.$cashcno->cno?>&codetype=code128&orientation=horizontal&size=30&print=<?=$stationcode.$cashcno->cno?>" class="barcode">
                    <h3><?=$stationcode.$cashcno->cno?></h3>
                    </div>
                    <div class="company-logo">
                          <img src="views/image/TPC-Logo.jpg" class="image">
                    </div>
                    <div class="company-address">
                    <p><?=$company->stn_addr?>,<?=$company->stn_city?>,<?=$company->stn_state?>,
                    <?=$company->stn_phone?>,<?=$company->stn_mobile?>,<?=$company->stn_email?>.<br>
                    GSTIN:<?=$company->stn_gstin?></p>
                    </div>
               </div> -->
               <?php }  ?>
          </div>
          <div class="right">
               <div class="to-top">
               <div class="receiver-destn">From : <?=$stationcode?></div>
                    <div class="receiver-destn" style="border-right:0px">Destn:  <strong><?=$cashcno->dest_code?></strong></div>
                   
                         <!-- <div class="receiver-pcs">
                         No. of Pieces: <br> &nbsp; <?=$cashcno->pcs?>
                         </div> -->
               </div>
               <div class="receiver-details">
               <!-- <div style="height: 90px;padding: 2px;line-height: 8px;">
                    <p>Name :<?=$cashcno->consignee?></p>
                    <p>Address: <?=$cashcno->consignee_addr?></p>
                    <p>Mobile: <?=$cashcno->consignee_mob?></p>
               </div> -->
                    <div class="receiver-address" style="text-align:center">
                    AIRWAY BILL NO<br>
                    <img style="width:180px;margin-bottom:5px;" src="barcode?text=<?=$stationcode.$cashcno->cno?>&codetype=code128&orientation=horizontal&size=40&print=<?=$stationcode.$cashcno->cno?>" class="barcode"><h1 style="color:black;margin-top:0%;"><?=$stationcode.$cashcno->cno?><?=($decoded_type=='reprinted'?' (Duplicate)':'')?></h1>
                    
                    </div>
                    <!-- <div class="wt-box">
                         <div class="weight">
                              <div class="kg"><strong>Kg.</strong><br>
                              <div class="wt-val"><?=explode(".",$cashcno->wt)[0]?></div>
                              </div>
                              <div class="gms"><strong>Gms.</strong><br>
                              <div class="wt-val"><?php if(empty(explode(".",$cashcno->wt)[1])){echo '0';}else{echo explode(".",$cashcno->wt)[1];} ?></div>
                              </div>
                         </div>
                    </div> -->
               </div>
               <div class="sender" style="margin-left: 10px; margin-top:8px;">
               <b><u>Consignee Details</u></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Vehi No:&nbsp;</b> <?=$vehicle_no;?>
               <div class="consignment-details receiver-details">
                    
               <div style="height: 90px;padding: 2px;line-height: 8px;">
                    <p><?=$cashcno->consignee?></p>
                    <p><?=$cashcno->consignee_addr?>,<?=$cashcno->consignee_pin?></p>
                    <p>Mobile: <?=$cashcno->consignee_mobile?></p>
               </div> 
                      
               </div>
                 
                    <!-- <div class="cnote-messages-right">
                         This is a non-negotiable
                         consignment note and is
                         subject to standard
                         conditions of carriage.
                         Carriers liability is limited
                         to Rs.100/- per consignment
                         for any cause<br>
                         <div class="other-barcode">
                          <img src="barcode?text=BLARE&codetype=code128&orientation=horizontal&size=30&print=BLARE" class="other-barcode1"> 
                         <h1>CASH</h2>
                         <?php
                         if($challan_bottom[$i]=='CONSIGNOR COPY'){
                              echo "<h1>Rs. ".round($cashcno->ramt);.00."</h1>";
                         }
                         ?>
                         </div>
                    </div> -->
               </div>
               <div class="sender-documents right-send-doc">
                    <table>
                         <tr>
                              <th style="width:40%">ACTUAL WEIGHT</th>
                              <th colspan="2">MODE</th>
                         </tr>
                         <tr>
                              <td style="padding-left: 8px;" class="text-center"><?=number_format((float)$cashcno->wt, 3, '.', ''); ?> Kg</td>
                              <td class="text-center"><?=($cashcno->mode_code)?></td>
                            
                         </tr>
                    </table>
               </div>
              
               <div class="company-logo-bottom">
               <?php 
               if($challan_bottom[$i]=="PROOF OF DELIVERY")
               {
                    ?>
                    <div style="text-align:left;line-height: 5px;">
                    <b>RECEIVED &nbsp;IN &nbsp;GOOD &nbsp;CONDITION</b>
                    <p>Sign & Seal :</p>
                    <p>Name :</p>
                    <p>Relationship :</p>
                    <p>Mobile No : </p>
                    <p>I.D No : </p>
                    </div>
                    
                    <?php } else { ?>
                    <b><u>Company Details</u></b> 
                    <div class="company" style="line-height: 8px; text-align:center;">    
                    <p><b><?=$company->stnname?></b></p>
                         <p><b><?=$company->stn_addr?></b></p>
                         <p><b>Contact Number : </b><?=$company->stn_phone?></p>
                         <p><b>GSTIN : </b> <?=$company->stn_gstin?></p>
                    </div>
                    <?php } ?>
                    </div>
                    <!-- <div class="company-name-logo-bottom text-center">
                         <p style="font-family: Arial, Helvetica, sans-serif;font-size:8px;font-weight:bold">THE PROFESSIONAL COURIERS (CHENNAI) LLP</p>
               </div> -->
          </div>
          <div class="right-corner">
                         <div class="form-number">
                              5
                         </div>
                         <div class="br-ncode">
                              2 - <?=$cashcno->br_code?>
                         </div>
                         <div class="dt date-time-pcs">
                              
                         Date : <?=date("d-M-Y H:i",strtotime($cashcno->time))?> Hrs
                              
                         </div>
                        
                         <div class="pcs date-time-pcs">
                              Pieces : <?=$cashcno->pcs?>
                         </div>
                         <div class="wt-head">
                              Chargeable Wt : <b><?=number_format((float)$cashcno->wt, 3, '.', '')?></b> Kg
                         </div>
                         <!-- <div class="wt-bd">
                              <div class="l-wt wt-child">
                              Kg
                              </div>
                               <div class="r-wt">
                               gms
                               </div>
                         </div>
                         <div class="wt-bd">
                              <div class="l-wt wt-child">
                              <?=explode(".",$cashcno->wt)[0]?>
                              </div>
                               <div class="r-wt">
                               <?=explode(".",$cashcno->wt)[1]?>
                               </div>
                         </div> -->
                         <?php
                        $gst = 0.18;

                        $gta_weight = 0.5;
                        $weightsmax = max($cashcno->v_wt,$cashcno->wt);
                        $gtaval = (($company->gta=='Y' && empty($cashcno->gst) && ($gta_weight<=$weightsmax) && $cashcno->mode_code=='ST') ? '105' : '118');
                        $gstnormal_value = 100 / $gtaval;
                        $calculated_value  =   number_format((float)$cashcno->ramt * $gstnormal_value, 2, '.', '');
                        $total_value  =   number_format((float)$cashcno->ramt - $calculated_value, 2, '.', '');
                        $igst_value = number_format((float)$cashcno->ramt - $calculated_value, 2, '.', '');

                         ?>
                         <div class="charges" style="margin-top:5px;">
                              <b><u style="color: black;margin-top:15px;">Amount</u></b>
                              <!-- <p>Charges :  <?= number_format((float)$cashcno->amt * $gstnormal_value, 2, '.', '') ?></p>
                              <p>GST : <?= $cashcno->ramt * $gst ?></p> -->
                              <?php
                         if($challan_bottom[$i]!='PROOF OF DELIVERY'){
                              echo "<p>Charges : ".number_format((float)$calculated_value, 2, '.', '')."</p>";
                              echo "<p>SGST : ".((empty($cashcno->gst) || substr($company->stn_gstin,0,2)==substr($cashcno->gst,0,2))?number_format((float)(($total_value)/2), 2, '.', ''):0.00)."</p>";
                              echo "<p>CGST : ".((empty($cashcno->gst) || substr($company->stn_gstin,0,2)==substr($cashcno->gst,0,2))?number_format((float)(($total_value)/2), 2, '.', ''):0.00)."</p>";
                              echo "<p>IGST : ".((empty($cashcno->gst) || substr($company->stn_gstin,0,2)==substr($cashcno->gst,0,2))?0.00:number_format((float)(($igst_value)), 2, '.', ''))."</p>";
                              echo "<h2>Total : ".number_format((float)$cashcno->ramt, 2, '.', '')."</h2>";
                         }else{
                              echo "<p>Charges : NA</p>";
                              echo "<p>GST : NA</p>";
                              echo "<p>Total : NA</p>";
                         }
                         ?>
                              
                         </div>
                         <div class="cashlabel" style="margin-top:10px;font-family: 'Gill Sans', 'Gill Sans MT','Trebuchet MS', sans-serif;">
                              <h1>CASH</h1>
                              <?php
                         // if($challan_bottom[$i]=='CREDIT - ACCOUNTS COPY'){
                         //      echo "<h1 style='font-size:27px;'>Rs. ".round($cashcno->amt);.00."</h1>";
                         // }else{
                            
                             echo "<p style='font-size: 21px;'>".$bcounter->cust_code."</p>";
                        // }
                         ?>
                              
                         </div>
                    </div>
     </div>
</div>
<div class="footer-text">
     <div class="text">
          <?php echo $challan_bottom[$i]; ?>
     </div>
</div><br>
                    </div>
<?php 
if($i<=1){  
     ?>
     <!-- <div class="dashed-line">
     --------------------------------------------------------------------------------------------------------------------------------------------
     </div> -->
<?php
}
}
}
?>
<script>

if(window.location.host!='localhost')
window.history.pushState('PCNL', 'Title', '/');

</script>
