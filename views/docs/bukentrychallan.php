<?php


if(isset($_GET['start_cnote'])){


    for ($x = $_GET['start_cnote']; $x <= $_GET['end_cnote']; $x++) :
      
      ?>


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
     .watermark {
    /* Used to position the watermark */
    position: relative !important;
}

.watermark__inner {
    /* Center the content */
    align-items: center!important;
    display: flex!important;
    justify-content: center!important;

    /* Absolute position */
    left: 0px!important;
    position: absolute!important;
    bottom: 0px!important;

    /* Take full size */
    height: 100%!important;
    width: 100%!important;
}

.watermark__body {
    /* Text color */
    color: rgba(0, 0, 0, 0.2)!important;

    /* Text styles */
    font-size: 3rem!important;
    font-weight: bold!important;
    text-transform: uppercase!important;

    /* Rotate the text */
    transform: rotate(-25deg)!important;
    margin-bottom:400px!important;
    /* Disable the selection */
    user-select: none!important;
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
     height:60px;
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
    color:red;
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
.watermark {
    /* Used to position the watermark */
    position: relative;
}

.watermark__inner {
    /* Center the content */
    align-items: center;
    display: flex;
    justify-content: center;

    /* Absolute position */
    left: 0px;
    position: absolute;
    bottom: 0px;

    /* Take full size */
    height: 100%;
    width: 100%;
}

.watermark__body {
    /* Text color */
    color: rgba(0, 0, 0, 0.2);

    /* Text styles */
    font-size: 3rem;
    font-weight: bold;
    text-transform: uppercase;

    /* Rotate the text */
    transform: rotate(-25deg);
    margin-bottom:400px;
    /* Disable the selection */
    user-select: none;
}
.amt_left{
     float: right;
}
</style>
<?php

 error_reporting(0);

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

for($a=0;$a<1;$a++){
     $cno = $_GET['cno'];
$challan_bottom = array("CONSIGNOR COPY","PROOF OF DELIVERY"); 


for($i=0;$i<count($challan_bottom);$i++){
    
     $challanbg = array("#fff","#f5f5c2");

     $company = $db->query("SELECT * FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
       
     $br_ncode = $db->query("SELECT * FROM branch WHERE br_code='".$_GET['br_code']."' AND stncode='".$_GET['stn_code']."'")->fetch(PDO::FETCH_OBJ);
     

     $desttbverify = $db->query("SELECT * FROM destination WHERE comp_code='$stationcode' AND dest_code='".$_GET['dest_code']."' OR dest_name='".$_GET['dest_code']."'");
     if($desttbverify->rowCount()==0){
         $dest_verify = $db->query("SELECT * FROM pincode WHERE pincode='".$_GET['dest_code']."' OR city_name='".$_GET['dest_code']."' OR dest_code='".$_GET['dest_code']."'"); 
         if($dest_verify->rowCount()==0){
             $pincode  	 = $_GET['dest_code'];
             $dest_code  	 = $_GET['dest_code'];
             $zonecode  	 = $_GET['dest_code'];
         }
         else{
             $rowdest_verify = $dest_verify->fetch(PDO::FETCH_OBJ);
             $pincode  	 = $rowdest_verify->pincode;
             $dest_code  	 = $rowdest_verify->dest_code;

             $getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$stationcode' AND dest_code='".$_GET['dest_code']."'");
             $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
             $zonecode = $rowgetzonecode['zone_cash'];
         }
     }
     else{
         $rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
         $dest_code  = $rowdestntn->dest_code;
         $pincode    = $rowdestntn->dest_code;

         $getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$stationcode' AND dest_code='".$_GET['dest_code']."'");
         $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
         $zonecode = $rowgetzonecode['zone_cash'];
     }


     


     $company = $db->query("SELECT * FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
     $gta_weight = 0.5;
     $wt= max($_GET['wt'],$_GET['v_wt']);
     if($company->gta=='Y' && empty($_GET['gstin']) && ($gta_weight<=$wt) && $_GET['mode']=='ST'){
       $getvehicel_1_2 = $db->query("SELECT * FROM vehicle WHERE comp_code='$stationcode' AND dest_code='$dest_code'")->fetch(PDO::FETCH_OBJ);
       $vechicle_one = $getvehicel_1_2->vehicle_1;
       $vechicle_two = $getvehicel_1_2->vehicle_2;
         //vechicel 1 is mon,wed,fri
         $day = $_GET['tdate'];
         $getday = date('D', strtotime($day));
         if($getday=='Mon'){
           $vechicle_no = $vechicle_one;
         }
         else if($getday=='Wed'){
           $vechicle_no = $vechicle_one;
         }
         else if($getday=='Fri'){
           $vechicle_no = $vechicle_one;
         }
         //vehicle two
         else if($getday=='Tue'){
           $vechicle_no = $vechicle_two;
         }
         else if($getday=='Thu'){
           $vechicle_no = $vechicle_two;
         }
         else if($getday=='Sat'){
           $vechicle_no = $vechicle_two;
         }
         else if($getday=='Sun'){
           $vechicle_no = $vechicle_two;
         }

        if($getday=='Mon' || $getday=='Wed' || $getday=='Fri'){
         $getvehiclenumber = $db->query("SELECT * FROM vehicle WHERE comp_code='$stationcode' AND dest_code='$dest_code' AND vehicle_1='$vechicle_no'")->fetch(PDO::FETCH_OBJ);
         $get_vechicle = $getvehiclenumber->vehicle_1;
        }else{
         $getvehiclenumber = $db->query("SELECT * FROM vehicle WHERE comp_code='$stationcode' AND dest_code='$dest_code' AND vehicle_2='$vechicle_no'")->fetch(PDO::FETCH_OBJ);
         $get_vechicle = $getvehiclenumber->vehicle_2;
        }

    }else{
        
    }

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
                             $weightsmax = max($_GET['wt'],$_GET['v_wt']);
                             $gtaval = (($company->gta=='Y' && empty($_GET['gstin']) && ($gta_weight<=$weightsmax) && $_GET['mode']=='ST') ? '105' : '118');

                             $sac = (($gtaval=='105') ? '996791' : '996812');
                         ?>
                     <p style="font-weight:normal;">
                   
                    
                    
                    
                    <!-- <b>SAC:&nbsp;</b><?=$sac;?><br> -->
                    <b style="margin-right:"><?=$company->stnname?></b><span style="padding-left:15px;"><b>SAC:</b>&nbsp;<?=$sac;?></span></p>
                    </div>
                    <?php

if($challan_bottom[$i]=="CONSIGNOR COPY")
 {
  echo '<div class="company-name-logo" style="margin-top:2px;">
  <p style="line-height:11px;font-weight:normal;"><b>'.$br_ncode->br_address.'</b><br><b>'.$br_ncode->br_city.'</b><br><b>'.$br_ncode->br_mobile.'</b>,<b>'.$br_ncode->br_email.'</b></p>
  
  
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

                      
               </div>
               <div class="cus" style="margin-left: 10px; margin-top:8px;">
               <b><u>Shipper Details</u></b>
               <div class="sender-details">
              
                    <p><?=(!empty($_GET['shp_name'])?$_GET['shp_name']:'NA')?></p>
                    <p><?=(!empty($_GET['shp_addr'])?$_GET['shp_addr']:'NA')?>,<?=(!empty($_GET['shp_pin'])?$_GET['shp_pin']:'NA')?></p>
                    <p>Mobile : <?=(!empty($_GET['shp_mob'])?$_GET['shp_mob']:'NA')?></p>
                    <p>GSTIN : <?=(!empty($_GET['gstin'])?$_GET['gstin']:'NA')?></p>
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
                              <td style="text-align:center;"><?=$_GET['contents']?></td>
                              <td style="text-align:center;"><?=$_GET['dc_value']?></td>
                              <td style="text-align: center;"><?=number_format((float)$_GET['v_wt'], 3, '.', '')?> Kg </td>
                         </tr>
                    </table>
               </div>
               <div class="sender-authority" style="margin-left: 10px;text-align: justify; margin-top:10px;">
               <?php 
               if($challan_bottom[$i]=="PROOF OF DELIVERY")
               {
                    ?>
                    <div style="text-align:center">
                    <img style="width:180px;margin-bottom:5px;" src="barcode?text=<?=$stationcode.$x?>&codetype=code128&orientation=horizontal&size=40&print=<?=$stationcode.$x?>" class="barcode"><h1 style="color:black;margin-top:1%;"><?=$stationcode.$x?><?=($decoded_type=='reprinted'?' (Duplicate)':'')?></h1>
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


               <?php }  ?>
          </div>
          <div class="right">
               <div class="to-top">
               <div class="receiver-destn">From : <?=$stationcode?>-<?=$_GET['br_code']?></div>
                    <div class="receiver-destn" style="border-right:0px">Destn:  <strong><?=$dest_code;?></strong></div>
               </div>
               <div class="receiver-details">

               
                    <div class="receiver-address" style="text-align:center">
                    AIRWAY BILL NO<br>
                    <img style="width:180px;margin-bottom:5px;" src="barcode?text=<?=$stationcode.$x?>&codetype=code128&orientation=horizontal&size=40&print=<?=$stationcode.$x?>" class="barcode"><h1 style="color:black;margin-top:0%;"><?=$stationcode.$x?><?=($decoded_type=='reprinted'?' (Duplicate)':'')?></h1>
                    
                    </div>
               </div>
               <div class="sender" style="margin-left: 10px; margin-top:8px;">
               <b><u>Consignee Details</u></b>
               <div class="consignment-details receiver-details">
                    
               <div style="height: 90px;padding: 2px;line-height: 8px;">
                    <p><?=$_GET['consignee_name']?></p>
                    <p><?=$_GET['consignee_addr']?>,<?=$_GET['consignee_pin']?></p>
                    <p>Mobile: <?=$_GET['consignee_mob']?></p>
               </div> 
                      
               </div>
               </div>
               <?php
               $mode = $db->query("SELECT mode_name FROM mode where mode_code='".$_GET['mode']."'")->fetch(PDO::FETCH_OBJ);


              ?>
               <div class="sender-documents right-send-doc">
                    <table>
                         <tr>
                              <th style="width:40%">ACTUAL WEIGHT</th>
                              <th colspan="2">MODE</th>
                         </tr>
                         <tr>
                              <td style="padding-left: 8px;" class="text-center"><?=number_format((float)$_GET['wt'], 3, '.', ''); ?> Kg</td>
                              <td class="text-center"><?=($mode->mode_name)?></td>
                            
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
                    <b><u>Company Details</u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Vehi No:&nbsp;</b> <?=$get_vechicle;?>
                    <div class="company" style="line-height: 8px; text-align:center;">
                    <p><b><?=$company->stnname?></b></p>
                         <p><b><?=$company->stn_addr?></b></p>
                         <p><b>Contact Number : </b><?=$company->stn_phone?></p>
                         <p><b>GSTIN : </b> <?=$company->stn_gstin?></p>
                    </div>
                    <?php } ?>
                    </div>
          </div>

           
          <div class="right-corner">
                         <div class="form-number">
                         <?=$dest_code?>
                         </div>
                         <div class="br-ncode">
                              <?=$br_ncode->br_ncode?> - <?=$br_ncode->br_code?>
                         </div>
                         <div class="dt date-time-pcs">
                              
                         Date : <?=date("d-M-Y H:i")?> Hrs
                              
                         </div>
                        
                         <div class="pcs date-time-pcs">
                              Pieces : <?=$_GET['pcs']?>
                         </div>
                         <div class="wt-head">
                            <?php  $wt_value = max($_GET['wt'],$_GET['v_wt']);    ?>
                              Chargeable Wt : <b><?=number_format((float)$wt_value, 3, '.', '')?></b> Kg
                         </div>
                         <?php
                        $gst = 0.18;

                        $gta_weight = 0.5;
                        $weightsmax = max($_GET['wt'],$_GET['v_wt']);
                        $gtaval = (($company->gta=='Y' && empty($_GET['gstin']) && ($gta_weight<=$weightsmax) && $_GET['mode']=='ST') ? '105' : '118');
                        $gstnormal_value = 100 / $gtaval;
                        $calculated_value  =   number_format((float)$_GET['amount'] * $gstnormal_value, 2, '.', '');
                        $total_value  =   number_format((float)$_GET['amount'] - $calculated_value, 2, '.', '');
                        
                        $igst_value = number_format((float)$_GET['amount'] - $calculated_value, 2, '.', '');

                         ?>
                         <div class="charges" style="margin-top:5px;">
                              <b><u style="color: black;margin-top:15px;">Amount</u></b>
                              <!-- <p>Charges :  <?= number_format((float)$_GET['amount'] * $gstnormal_value, 2, '.', '') ?></p>
                              <p>GST : <?= $_GET['amount'] * $gst ?></p> -->
                              <?php
                         if($challan_bottom[$i]!='PROOF OF DELIVERY'){
                          $width_amt  = (($_GET['amount'] >= 1000)? '110px;' : '90px;');
                              echo "<div class'calculate_amt' style='width:$width_amt'>";
                              echo "<p>Charges : <span class='amt_left'>".number_format((float)$calculated_value, 2, '.', '')."</span></p>";
                              
                              echo "<p>SGST : <span class='amt_left'>".((empty($_GET['gstin']) || substr($company->stn_gstin,0,2)==substr($_GET['gstin'],0,2))?number_format((float)(($total_value)/2), 2, '.', ''):0.00)."</span></p>";
                              echo "<p>CGST : <span class='amt_left'>".((empty($_GET['gstin']) || substr($company->stn_gstin,0,2)==substr($_GET['gstin'],0,2))?number_format((float)(($total_value)/2), 2, '.', ''):0.00)."</span></p>";
                              echo "<p>IGST : <span  class='amt_left'>".((empty($_GET['gstin']) || substr($company->stn_gstin,0,2)==substr($_GET['gstin'],0,2))?0.00:number_format((float)(($igst_value)), 2, '.', ''))."</span></p>";
                            
                              echo "</div>";
                              echo "<h2>Total : ".number_format((float)$_GET['amount'], 2, '.', '')."</h2>";
                         }else{
                              echo "<p>Charges : NA</p>";
                              echo "<p>GST : NA</p>";
                              echo "<h2>Total : ".(($zonecode >=100 && $zonecode <=114)? number_format((float)$_GET['amount'], 2, '.', '') : 'NA')."</h2>";
                         }
                         ?>
                              
                         </div>
                         <div class="cashlabel" style="margin-top:10px;font-family: 'Gill Sans', 'Gill Sans MT','Trebuchet MS', sans-serif;">
                              <h1>CASH</h1>
                              <?php

                         ?>
                              
                         </div>
                    </div>
     </div>
</div>
<div class="footer-text">
     <div class="text">
          <?php echo $challan_bottom[$i]; ?>

          <div class="watermark">
    <div class="watermark__inner">
        <!-- The watermark -->
        <div class="watermark__body"><?php echo $challan_bottom[$i]; ?></div>
    </div>
</div>


     </div>
</div><br>
                    </div>
               
<?php 
if($i<=1){  
     ?>

<?php
}
}
}
?>
<script>
window.print();
if(window.location.host!='localhost')
window.history.pushState('PCNL', 'Title', '/');

</script>

<?php
    endfor;
    
}
?>

