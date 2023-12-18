<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>BA Inbound-<?php if(isset($_GET['mfno'])){echo $_GET['mfno'];}?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    
  </head>
  <style>
      @page       {
        size: 8.5in 10.5in;
          }
      @media print{
          .amountcalc{
               page-break-inside: avoid;
          }
     }
     body{
          margin: auto;
          width:1800px;
          font-family: "Calibri";
          padding:25px;
          background:#fff;
          overflow-x: hidden;
     }
     header{
          float:none;
     }
     .left-20{
          margin-left:2%;
     }
     .small-heading{
          font-size: 10px;
          margin-left: 90px;
     }
     #company{
          float:left;
          line-height: 20px;
          height:100px;
          width:50%;
          font-size: 19px;
     }
     #project{
          width:50%;
          height:100px;
          float: right;
          font-size: 19px;
          line-height: 30px;
     }
     .mft_no{
          width:100%;
     }
     .tablehead th{
          border-bottom: dashed 1px black;
     }
  
     .consigne-table{
          width:1800px;
     }
     .centertb{
          text-align: center;
     }
     .stars{
          font-size: 25px;
          margin-top: 20px;;
     }
     .information{
          width:100%;
          margin-bottom: 20px;
     }
     .information .title{
          font-size:20px;
     }
     .information .content, .request, .payment-note{
          font-size:16px;
          word-spacing: 5px;
          line-height:25px;
     }
     .outstanding-data{
          font-size:19px;
     }
     .outstanding-table th{
          font-size:19px;
          font-weight: 700;
          border-bottom:1px dashed black;
     }
     .outstanding-table td{
          height:22px;
     }
     .conformation{
          white-space:pre;
     }
     .request{
          margin-top:20px ;
     }
  </style>
  <body id="printable">
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';
error_reporting(0);
if(isset($_GET['mfmasno'])){
     $gmsmasno = $_GET['mfmasno'];
     date_default_timezone_set('Asia/Kolkata');

     $mf_no = $_GET['mfno'];
     $mf_bacode = $_GET['bacode'];
     //getmfmasterdata
     $mfmaster = $db->query("SELECT * FROM bainboundbatch_master WHERE bainboundmasterid='$gmsmasno'")->fetch(PDO::FETCH_OBJ); 
     //Invoice Info
     // $fetchdata = $db->query("SELECT * FROM bainbound WHERE mf_no='$mfmaster->mf_no' AND bacode='$mfmaster->ba_code'");
     // $rowinvoice = $fetchdata->fetch(PDO::FETCH_OBJ);
     //Company Info
     $getcompanyname = $db->query("SELECT * FROM station WHERE stncode='$mfmaster->comp_code'");
     $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);
     //Customer Info
     $getcustomerinfo = $db->query("SELECT * FROM customer WHERE cust_code='$mfmaster->ba_code' AND comp_code='$mfmaster->comp_code'");
     $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
     //POD info
     $getpod = $db->query("SELECT cno,comp_code FROM bainboundbatch WHERE mf_no='$mfmaster->mf_no' AND bacode='$mfmaster->ba_code' AND cast(date_added as date)='$mfmaster->date_added' GROUP BY cno,comp_code");
     
     $getbaname = $db->query("SELECT cust_name FROM customer WHERE cust_code='$mfmaster->ba_code' AND comp_code='$mfmaster->comp_code'")->fetch(PDO::FETCH_OBJ);
}
?>
    <header>
    <div class="flex-container">
    <div id="company" class="clearfix">
         <div>THE PROFESSIONAL COURIERS</div>
         <div class="small-heading">BA/FRA LOAD INBOUND - FBD</div>
        <div>STATION :<?=$rowcompany->stncode;?></div>
        <div>BA/FRA : <?=$mfmaster->ba_code." ".$getbaname->cust_name;?></div>
       

      </div>
      <div id="project">
       <div> DATE  :  <?=date("d-m-Y", strtotime($mfmaster->date_added));?></div>
       <div> PNT NO : 69523467</div>
     </div>
      </div>
   
    </header>
   
    <section>
    <div class="seperator">
    &#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;
    </div>
    <div class="mft_no">
         MFT NO : <?=$mfmaster->mf_no; ?>
    </div>
    <div class="seperator">
    &#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;
    </div>
    <div class="consigne-table">
      <table>
        <thead>
          <tr class="tablehead">
            <th class="centertb" style="width:40px;">SNo</th>
            <th class="centertb" style="width:160px;">C.NO</th>
            <th class="centertb" style="width:80px;">Wt</th>
            <th class="centertb" style="width:40px;">Mode</th>
            <th class="centertb" style="width:100px;">Destn</th>
            <th class="centertb" style="width:80px;">Amount</th>
            <th class="centertb" style="width:40px;">SNo</th>
            <th class="centertb" style="width:160px;">C.No</th>
            <th class="centertb" style="width:80px;">Wt</th>
            <th class="centertb" style="width:40px;">Mode</th>
            <th class="centertb" style="width:100px;">Destn</th>
            <th class="centertb" style="width:80px;">Amount</th>
          </tr>
        </thead>
        
        <tbody class="tablebody">
          <?php
                    $i = 0;
                    while ($rowpod = $getpod->fetch(PDO::FETCH_OBJ))
                    {      $i++;  
                    $rowcopy = $db->query("SELECT TOP(1) * FROM bainboundbatch WHERE cno='$rowpod->cno' AND comp_code='$rowpod->comp_code'")->fetch(PDO::FETCH_OBJ);
                             
                    if($i % 2 != 0){
                         echo "</tr>";   
                    } 
                    echo "<td class='centertb'>".$i."</td>";
                    echo "<td class='centertb'>".$rowpod->cno."</td>";
                    echo "<td class='centertb'>".number_format($rowcopy->wt,3)."</td>";
                    echo "<td class='centertb'>".$rowcopy->mode_code."</td>";
                    echo "<td class='centertb'>".$rowcopy->dest_code."</td>";
                    echo "<td class='centertb'>".round($rowcopy->amt)."</td>";
                    if($i % 2 == 0){
                         echo "</tr>";   
                         }
                    }                                  
                    ?>
                    </tbody>
                    
      </table>
     </div>
     <div class="seperator">
    &#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;
    </div>
     <?php 
     $getpendinginv = $db->query("SELECT * FROM invoice WHERE cust_code='$mfmaster->ba_code' AND comp_code='$rowcompany->stncode' AND status='Pending'");
     if($getpendinginv->rowCount()==0){

     }
     else{
          $pendingtotal[] = '';
          $getpendinginvcount = $db->query("SELECT COUNT(*) as 'invcounts' FROM invoice WHERE cust_code='$mfmaster->ba_code' AND comp_code='$rowcompany->stncode' AND status='Pending'")->fetch(PDO::FETCH_OBJ);
     ?>
    <div class="stars">
         ************************************************************************************
    </div>
    <div class="information">
         <div class="title">Dear Sir</div>
         <div class="content">As per our books of accounts, &nbsp;&nbsp;You have <?=$getpendinginvcount->invcounts; ?> bills outstanding. Pay the earlier bill immediately. otherwise your loads will not be accepted<br> for forwarded by the Computer</div>
    </div>
    <div class="outstanding-data">Your outstanding details are as follows</div>
    <div class="seperator">
    &#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;
    </div>
    <div class="outstanding-table">
    <table>
         <tr>
              <th class="centertb" style="width:200px;">Invoice Date</th>
              <th class="centertb" style="width:200px;">Invoice No</th>
              <th class="centertb" style="width:200px;">Amount</th>
              <th class="centertb" style="width:200px;">Received</th>
              <th class="centertb" style="width:200px;">Balance</th>
         </tr>
         <?php while($pendinvrow = $getpendinginv->fetch(PDO::FETCH_OBJ)){  
          array_push($pendingtotal,round($pendinvrow->netamt)-round($pendinvrow->rcvdamt));    
          ?>
         <tr>
              <td class="centertb"><?=$pendinvrow->invdate; ?></td>
              <td class="centertb"><?=$pendinvrow->invno; ?></td>
              <td class="centertb"><?=round($pendinvrow->netamt); ?></td>
              <td class="centertb"><?=round($pendinvrow->rcvdamt); ?></td>
              <td class="centertb"><?=round($pendinvrow->netamt)-round($pendinvrow->rcvdamt); ?></td>
         </tr>
         <?php } ?>
         <tr>
              <td style="border-top:1px dashed black;"></td>
              <td style="border-top:1px dashed black;"></td>
              <td style="border-top:1px dashed black;"></td>
              <td class="centertb" style="border-top:1px dashed black;">Total</td>
              <td class="centertb" style="border-top:1px dashed black;"><?=array_sum($pendingtotal);?></td>
         </tr>
    </table>
    </div>
     <div class="seperator">
    &#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;
    </div>
    <div class="request">
         We therefore request to check with your records of accounts, and make immediate arrangements to clear the ques without anymore delay.
    </div>
    <div class="payment-note">
         If the payments are already settled, kindly provide the details of cheque/cash
               </div>
               <?php } ?>
    <div class="stars">
         ************************************************************************************
    </div>
    <div class="conformation">
         Received By:                                                                    Incharge                                                                        BA/Fra Sign
    </div>
    </section>
  
      <script>
// $(document).ready(function(){
//      window.print();
// });
      </script>
  </body>
</html>