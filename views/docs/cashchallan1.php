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
     /* .main-box{
     width:100%!important;
     } */
     #single-cnote{
         


          page-break-after: always;
     }
     .sender-documents table tr th{
     color: black;
     background:black;
     }
   
}
.container-page{
   
border:white;
     padding:10px;
     font-family: 'Gill Sans', 'Gill Sans MT' ,'Trebuchet MS', sans-serif;
     font-size: 10px;
}
.main-box{
     display: none;


     /* color:red; */
     display: flex;
     padding: 0px;
     border: 1.5px solid white;
     width:800px;
     margin: 0 auto;
     height:376px   ;
}
.left{
     
     width:43%;
     border-right:1.5px solid white;
     height:169px;
}
.right{
     width:36%;
     border-right: 1px solid white;
}
.right-corner{
     width:17%;
}
.text-center{
     text-align: center;
     }
     .from-top{
          height:133px;
          border-bottom: 1px solid white;
          width:344px;
         
     }
     .to-top{
          height:24px;
     }
.to-top,.receiver-details,.receiver-tracking{
       display: flex; 
    display:flex;
     
}
.from-menu{
     display: flex;
     width:10%;
}
.from-menu,.from,.receiver-head,.receiver-destn,.receiver-mode{
     display: flex;
     border-right:1.5px solid white;
}
.from,.from-date{
     display: flex;
     width:45%;
}
.from-date,.receiver-destn{
     display: flex;
     padding:5px;
}
.from{
     display: flex;
     font-weight: bold;
     font-size: 20px;
}
.margin-auto{
     /* display: none; */
     padding:0px;
}
.sender-details{
     display: none;
     height: 30px;
    padding: 2px;
    line-height: 9px;
  
    width: 321px;
}
.sender-details1{
     height: 57px;
    padding: 2px;
    line-height: 8px;
    font-weight:normal;
    margin-left:4px;
    font-size:14px;
    
}
.center-details{
     display: none;
     border-bottom: 1.5px solid white;
     width:633px;
}
.sender-documents{
     display: none;
     /* display: flex; */
    /* // border-bottom: 1.5px solid black; */
     /* height:75px; */
     font-size: 9px;
}
.sender-documents table{
     /* display: none; */
     font-size:9px;
     width: 100%;
}
.sender-documents table td,.sender-documents table th{
     /* display: none; */
     border-left: 1px solid white;
}
.sender-documents table tr th{
     /* display: none; */
     font-weight: 100;
     color: #fff;
     background-color: black;
}
.sender-documents table tr td{
     /* display: none; */
     font-weight: bold;
     color: black;
     font-family: 'Gill Sans', 'Gill Sans MT','Trebuchet MS', sans-serif;
}
.sd-value{
     /* display: none; */
     margin-left: 50px;
}
.auth-value{
     display: none;
     /* display: flex; */
     margin-top: 10px;
}
.auth-sign{
     display: none;
     margin-left: 80px;
}
.image{
     display: none;
     width:80%;
}
.sender-company{
     display: none;
     text-align: center;
     line-height: 0px;
     height:130px;
}
.sender-authority{
     /* padding: 5px; */
     
     height:153px;
     color: #000;
     font-weight: bold;
     font-family: 'Gill Sans', 'Gill Sans MT', 'Trebuchet MS', sans-serif;
}
.sender{
     display: none;
     border-bottom: none;
}
.barcode{
     display: none;
     width:35%;
}
.company-address{
     display: none;
     line-height: 10px;
}
.receiver-head{
     display: none;
     width:15%;
}
.receiver-destn{
     display: none;
     width:50%;
}
.receiver-mode{
     display: none;
     width: 30%;
}
.receiver-details{
     height:106px;
     width: 100%;
}
.receiver-address{
     display: none;
     /* padding:10px; */
     width:100%;
     line-height: 10px;
     margin-top: 2px;
}
.weight{
     display: none;
     /* display: flex; */
     text-align: center;
     border: 1.5px solid white;
     height:93%;
     width:98%;
}
.kg{
     display: none;
     width:50%;
     border-right: 1.5px solid white;
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
     border-right: 1.5px solid white;
}
.challan-belongsto{
     width:39%;
     text-align: center;
}
.consignment-details{
     display: none;
     height:34px;
}
.cnote-messages-left{
     display: none;
     padding:6px;
     width:60%;
     border-right: 1.5px solid white;
}
.cnote-messages-right{
     display: none;
     padding-top: 20px;
     width: 41.6%;
     text-align: center;
}
.other-barcode{
     display: none;
     margin-top: 15px;
     border-top: 1.5px solid white;
}
.other-barcode1{
     display: none;
     width:80%;
}
.footer-text{
     display: none;
     font-size: 9px;
     width:800px;
     margin:0 auto;
     text-align: right;
}
.dashed-line{
     display: none;
     text-align: center;
}

.company-name-logo{
     display: none;
     margin-top: -10px;
     color: #000;
     text-align: center;
     font-weight: bold;
     font-family: 'Gill Sans', 'Gill Sans MT','Trebuchet MS', sans-serif;
}
.company-name-logo-bottom{
     display: none;
     font-size: 10px;
     margin-top: -10px;
     color: #DA231B;
}
.top-company-logo{
     display: none;
     text-align: center;
}
.company-logo-bottom{
     display: none;
     margin-left: 3px;
     padding: 5px;
     line-height: 20px;
}
/* Right corner div */
.form-number{
     display: none;
     margin-top: 0px;
     text-align: center;
     height: 30px;
    
     /* border-bottom: 1px solid black; */
     font-size: 40px;
}
.br-ncode{
     display: none;
     margin-top: 0px;
     text-align: center;
     height: auto;
     /* border-bottom: 1px solid black; */
     font-size: 24px;
}
.date-time-pcs{
     display: none;
     height: auto;
     color: #000;
     /* border-bottom: 1px solid black; */
     font-size: 10px;
     padding: 10px 0px 10px 8px;
}
.wt-head{
     display: none;

     height: auto;
     color: #000;
     /* border-bottom: 1px solid black; */
     font-size: 10px;
     padding: 2px 0px 2px 8px;
}
.wt-bd{
     display: none;

     height: auto;
     color: #000;
     display: flex;
     /* border-bottom: 1px solid black; */
     font-size: 10px;
     padding: 2px 0px 2px 8px;
}
.charges{
     height: auto;
     color: #000;
     /* border-bottom: 1px solid black; */
     font-size: 10px;
     padding: 2px 0px 2px 8px;
}

.wt-child{
width: 49%;
}
.cashlabel{
     display: none;

   height: 60px;
   text-align: center;
   color: #000;
     font-size: 15px;
     font-weight: bold;
     font-family: sans-serif;
}
.flex-container {
     display: none;

  /* display: flex; */
  
}

.flex-container > div {

 
  
  font-size: 30px;
}
.flex-container1 {
     display: none;

/* display: flex; */
  
}

.flex-container1 > div {
     display: none;


     margin: 10px;
 
  font-size: 15px;;
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

$challan_bottom = array("CONSIGNOR COPY"); 


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
                <div class="headertag" style="border-bottom:1px solid white;width:633px;height:30px;">
                <div class="sidebar" style="border-left:1px solid white;">
                <h1 style="float:left;margin-top: 4.4;margin-left:5px;margin-bottom: 2.4;color:white;"></h1><h1 style="float:left;margin-top: 0;height:29px;margin-left:5px;margin-bottom: 2.4;margin-bottom:0;border:1px solid white;"></h1>
                </div>
                </div>
             
                    <div class="customer_details">
                        <h2 style="margin-top: 1.45;float:left;margin-left:6px;font-weight:normal;margin-bottom: 0px;"></h2>
                    </div>

                    <div class="sender-details1" >
               <p style="margin-top:21px;"><?=(!empty($cashcno->shp_name)?$cashcno->shp_name:'NA')?></p>  
               <p style="width:390px;"><?=(!empty($cashcno->shp_addr)?wordwrap($cashcno->shp_addr, 30, "<br /><br /><br />\n"):'NA')?>,<br><br><br><br><?=(!empty($cashcno->shp_pin)?$cashcno->shp_pin:'NA')?></p>
               
              <p style="width:390px;margin-left:80px;"><?=(!empty($cashcno->shp_mob)?$cashcno->shp_mob:'NA')?></p>
              <p style="margin-bottom:0px;">   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?=(!empty($cashcno->gst)?$cashcno->gst:'NA')?></p>
               </div>        
               </div>
               <div class="sender-details">
              <P  style="margin-top: 2px;margin-bottom: 2px;font-size:9px;">This is Non-Negotiable Consigment not subject to standard Condition<br>of Carrier's stability to Rs. 100/- per consignment for any<br>cause P.T.O for terms & conditions of carriage &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               SENDERS SIGN</P>
         
               </div>
               <div class="center-details">
               </div>
               
               <div class="sender-documents">
                           <div class="companylogo" style="height:10px;">
                          <img src="views/image/TPC-Logo.jpg" class="image" style="width:259px;height:55px;margin-top:5px;">
                          </div>
                          
                          <div class="companyaddress">
                        <p  style="margin-top: 2px;margin-bottom: 2px;width:140px;line-height:8px;text-align:right;font-size:8px;margin-bottom:0px;">Corporate Ofiice<br>301, Monarch Plaza, Plot No. 54,<br>Sector- 11 CBO Belapur,<br>Navi Mumbai - 400 614<br>Tel : 2756 6244-45<br>E-mail : Kurla@tpcglobal.co.in<br><p style="margin-top:2px">CUSTOMER CARE : 022 2756 2255</p></p>
                          </div>
                          
                        </div>
          
                        

               <div class="sender-authority" style="text-align: justify;">
                    <div class="auth-content" style="border: 1px solid white;height:45px;width:403px;border-left:none;border-right:none;margin-top: 100px;">
                    <table class="table" style="height:25px;width:290px;border-left:none;">
                    <tr><th style="float:left;"><p style="font-size:12px;border-top:1px solid white;width:40px;float:left;transform: rotate(90deg);"></p><b style="padding-top:160px;font-weight:normal;"><?= number_format((float)$cashcno->wt, 3, '.', '')?></b> <p></p></th><th style="float:left;"><p style="font-size:10.9px;border-top:1px solid white;width:40px;float:left;transform: rotate(90deg);border-bottom:1px solid white;"></p><p style="padding-top:0;width:110px;"><?= $cashcno->contents?></p> <p></p></th><th style="float:left;"><p style="font-size:12px;border-top:1px solid white;width:40px;float:left;transform: rotate(90deg);border-bottom:1px solid white;"></p><b><?= $cashcno->dc_value;?></b> <p></p></th></tr>
                    
                    </table>
                    <div style="text-align:center;margin-top:9px;">
                    <img style="width:200px;margin-bottom:5px;height:25px;" src="barcode?text=<?=$stationcode.$cashcno->cno?>&codetype=code128&orientation=horizontal&size=40&print=<?=$stationcode.$cashcno->cno?>" class="barcode">
                    <h1 style="color:black;margin-top:1%;"></h1>
                    </div>
                     </div>
               </div>
              
          </div>
          <div class="right">
               <div class="to-top">
               
               <h1 style="float:left;margin-top: 4.4;margin-left:5px;color:white;"></h1><h1 style="float:left;margin-top: 0;margin-left:5px;border:1px solid white;height:30px;"></h1>
               </div>
               <div class="receiver-details">
               
               <div class="sender-details1" >
               <h2 style="float:left;margin-top: 8px;padding-top:5px;font-weight:normal;font-size:15px;color:white;"></h2>
               <div class="lable" style="margin-top:37px;">
               <p><?=$cashcno->consignee?></p>   
               <p style="line-height:13px;height:46px;"><?=wordwrap($cashcno->consignee_addr, 30, "<br /><br /><br />\n");?>,<?=$cashcno->consignee_pin?><br><br><br><br></p>
              <p style="width:390px;margin-left:80px;"><?=$cashcno->consignee_mobile?> </p>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?=$company->stn_gstin?></p>
               </div>
               </div>
               </div>
               
               
    


               <div class="sender">

               <div class="consignment-details receiver-details">
                    
            
                      
               </div>
                 
          
               </div>
               <div class="sender-documents right-send-doc">
           
               </div>
              
               <div class="company-logo-bottom">

                         <div class="right_corner" style="border-left:1px solid white;height:209px;margin-left:50px;">
                         <p style="margin-top: 0px;line-height:11px;margin-bottom: 0px;height:25px;width:300px;font-size:10px;padding-top:4px;padding-left:10px;letter-spacing: 0.5px;">FOR MOST URGENT AND HIGH VALUE<br>CONSIGNMENT USE OUR EXPRESS SERVICE</p>
                         <div class="company" style="line-height: 8px; text-align:center;height:70px;">    
                    <div class="flex-container" >
                       <div><img src="views/image/midday.jfif" style="width:112px;height:75px;border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;border-left:1px solid white;"></div>
                       <div><img src="views/image/midday.jfif" style="width:112px;height:75px;border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;border-left:1px solid white;"></div>
                  </div>
                  <div class="img_header" style="height:10px;margin-left:0px;">
                  <P style="margin-top:4px;text-align:center;font-size:9px;">Track Consignment @www.tpcglobe.com</P>
                  </div>
                 <div class="flex-container">
                   <div><img src="views/image/midday.jfif" style="width:112px;height:85px;border-right:1px solid white;border-top:1px solid white;border-left:1px solid white;"></div>
                   <div><img src="views/image/midday.jfif" style="width:112px;height:85px;border-right:1px solid white;border-top:1px solid white;border-left:1px solid white;"></div>
                   
                 </div>
                    </div>
                          </div>

                    </div>
          </div>
  <?php
   $amount = number_format($cashcno->ramt,2)*0.18;
 
  ?>
          <div class="right-corner">
          <table class="right_corner" style="border-collapse: collapse;width:168px;border-right:none;border-left:none;height:240px;border-top:none;">
                    <tr><th></th><th></th><tr>
                   <tr><td style="border: 1px solid white;border-collapse: collapse;border-top:none;"><P style="font-size:13px;margin-left:8px;height:21px;border-top:none;display:none;">DATE</P></td> <td style="border: 1px solid white;border-collapse: collapse;border-right:none;border-top:none;font-size:18px;"><P style="font-size:12px;"><?=$cashcno->tdate;?></p></td><tr>
                    <tr><td style="border: 1px solid white;border-collapse: collapse;"><P style="font-size:13px;margin-left:8px;border-top:none;display:none;">WEIGHT</P></td><td style="border: 1px solid white;border-collapse: collapse;border-right:none;"><P style="font-size:13px;margin-left:8px;"><b style="padding-top:20px;font-size:18px;"><?= number_format((float)$cashcno->wt, 3, '.', '')?></b></p></td><tr>
                    <tr><td style="border: 1px solid white;border-collapse: collapse;"><P style="font-size:13px;margin-left:8px;display:none;">COURIER<br>CHARGES</P></td><td style="border: 1px solid white;border-collapse: collapse;border-right:none;"><p style="margin-left:6px;font-size:18px;"><?=$cashcno->charges;?></p></td><tr>
                    <tr><td style="border: 1px solid white;border-collapse: collapse;"><P style="font-size:13px;margin-left:8px;"> <br></P></td><td style="border: 1px solid white;border-collapse: collapse;border-right:none;"><P style="font-size:13px;margin-left:6px;"></p></td><tr>
                    <tr><td style="border: 1px solid white;border-collapse: collapse;"><P style="font-size:13px;margin-left:8px;"><br></P></td><td style="border: 1px solid white;border-collapse: collapse;border-right:none;"><P style="font-size:13px;margin-left:6px;"></p></td><tr>
                    <tr><td style="border: 1px solid white;border-collapse: collapse;"><P style="font-size:13px;margin-left:8px;"><br></P></td><td style="border: 1px solid white;border-collapse: collapse;border-right:none;"><P style="font-size:13px;margin-left:6px;font-size:18px;"><?=($cashcno->sgst+$cashcno->cgst+$cashcno->igst)?></p></td><tr>
                    <tr><td style="border: 1px solid white;border-collapse: collapse;"><P style="font-size:13px;margin-left:8px;color:white"></P></td><td style="border: 1px solid white;border-collapse: collapse;border-right:none;"><P style="font-size:13px;margin-left:6px;font-size:18px;"><?=number_format($cashcno->ramt,2)?></p></td><tr> 
                      
                  
               </table>
               <div class="air" style="border-bottom:1px solid white;width:168px;font-size:28px;height:34px;text-align:center;">
                              
                             <p style="margin-top: 3px;margin-left:10px;"><b style=""></b></p> 
                                   
                              </div>
                              <div class="retail" style="border-bottom:1px solid white;height:25px;padding-left:60px;font-size:17px;width:108px;margin-top:5px;">
                              <b></b>   
                              </div>
                              <div class="retail" style="font-size:8.5px;width:210px;margin-top:5px;padding-left:14px;">
                              <b></b>   
                              </div>
                              <div class="retail" style="font-size:8.5px;width:199px;margin-top:5px;padding-left:21px;padding-left:14px;">
                              <b></b>   
                              </div>
                              <div class="retail" style="font-size:8.5px;width:199px;margin-top:5px;padding-left:14px;">
                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>   
                              </div>
                         <!-- <div class="form-number">
                            
                         </div>
       
                         <div class="dt date-time-pcs" style="border-right: 1px solid black;">
                              
                         Date : 
                              
                         </div>
                         <div class="dt date-time-pcs">
                              
                         Date : <?=date("d-M-Y H:i",strtotime($cashcno->time))?> Hrs
                              
                         </div>
                        
                         <div class="pcs date-time-pcs">
                              Pieces : <?=$cashcno->pcs?>
                         </div>
                         <div class="wt-head">
                              Chargeable Wt : <b><?=number_format((float)$cashcno->wt, 3, '.', '')?></b> Kg
                         </div> -->
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
                              <!-- <b><u style="color: black;margin-top:15px;">Amount</u></b> -->
                              <!-- <p>Charges :  <?= number_format((float)$cashcno->amt * $gstnormal_value, 2, '.', '') ?></p>
                              <p>GST : <?= $cashcno->ramt * $gst ?></p> -->
                              <?php
                         if($challan_bottom[$i]!='PROOF OF DELIVERY'){
                              // echo "<p>Charges : ".number_format((float)$calculated_value, 2, '.', '')."</p>";
                              // echo "<p>SGST : ".((empty($cashcno->gst) || substr($company->stn_gstin,0,2)==substr($cashcno->gst,0,2))?number_format((float)(($total_value)/2), 2, '.', ''):0.00)."</p>";
                              // echo "<p>CGST : ".((empty($cashcno->gst) || substr($company->stn_gstin,0,2)==substr($cashcno->gst,0,2))?number_format((float)(($total_value)/2), 2, '.', ''):0.00)."</p>";
                              // echo "<p>IGST : ".((empty($cashcno->gst) || substr($company->stn_gstin,0,2)==substr($cashcno->gst,0,2))?0.00:number_format((float)(($igst_value)), 2, '.', ''))."</p>";
                              // echo "<h2>Total : ".number_format((float)$cashcno->ramt, 2, '.', '')."</h2>";
                         }else{
                              // echo "<p>Charges : NA</p>";
                              // echo "<p>GST : NA</p>";
                              // echo "<p>Total : NA</p>";
                         }
                         ?>
                              
                         </div>
                         <!-- <div class="cashlabel" style="margin-top:10px;font-family: 'Gill Sans', 'Gill Sans MT','Trebuchet MS', sans-serif;"> -->
                              <!-- <h1>CASH</h1> -->
                              <?php
                         // if($challan_bottom[$i]=='CREDIT - ACCOUNTS COPY'){
                         //      echo "<h1 style='font-size:27px;'>Rs. ".round($cashcno->amt);.00."</h1>";
                         // }else{
                            
                             echo "<p style='font-size: 21px;'>".$bcounter->cust_code."</p>";
                        // }
                         ?>
<!--                               
                         </div> -->
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
window.print();
if(window.location.host!='localhost')
window.history.pushState('PCNL', 'Title', '/');

</script>