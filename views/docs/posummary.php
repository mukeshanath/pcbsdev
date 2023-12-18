<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice-<?php if(isset($_GET['invoicenumber'])){echo $_GET['invoicenumber'];}?></title>
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
     text-align:right;
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
if(isset($_GET['ponumber'])){
     $ponumber = $_GET['ponumber'];
     $rowpo = $db->query("SELECT * FROM cnote WHERE cnotepono='$ponumber'")->fetch(PDO::FETCH_OBJ);
     $rowcompany = $db->query("SELECT * FROM station WHERE stncode='$rowpo->cnote_station'")->fetch(PDO::FETCH_OBJ);
     $rowvendor = $db->query("SELECT * FROM vendor WHERE stncode='$rowpo->cnote_station' AND vendor_code='$rowpo->vendor_code'")->fetch(PDO::FETCH_OBJ);
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
        <div class="billdata"><div class="flex-box-3">&#160;&#160;<strong>PO No : </strong><?=$ponumber;?></div><div class="flex-box-3"> <strong class="left-20">Date :  </strong><?=$rowpo->date_added;?></div><div class="flex-box-3"><strong class="left-20">Code : </strong><?=$rowvendor->vendor_code; ?></div></div>
        <div class="customerbox"><div><?=$rowvendor->vendor_name;?></div>
        <div><?=$rowvendor->vendor_add;?></div>
        <div><?=$rowvendor->vendor_city;?></div>
        <div><?=$rowvendor->vendor_state;?></div>
        <div><p style="vertical-align: text-bottom;">Mobile : <?=$rowvendor->vendor_mobile;?></p></div>
        <div><p style="vertical-align: text-bottom;"><strong>Email : </strong><?=$rowvendor->vendor_email;?></p></div>
        </div>
     </div>
      </div>
      <!-- <div class="transdate flex-container">
              <div><strong>BILL/Statement of Transaction between </strong></div>
              <div style="margin-left:5%"><?=$rowinvoice->fmdate;?>&#160;&#160; AND&#160;&#160; <?=$rowinvoice->todate;?></div>
         </div> -->
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
          </tr>
        </thead>
        <tbody class="tablebody">
             <!-- <tr> -->
          <?php
                    $purchaseorder = $db->query("SELECT * FROM cnote WHERE cnotepono='$ponumber'");
                    $i = 0;
                    while ($rowpod = $purchaseorder->fetch(PDO::FETCH_OBJ))
                    {$i++;
                    echo "</tr>";   
                    echo "<td class='centertb'>".$i."</td>";
                    echo "<td class='centertb'>".$rowpod->cnote_serial."</td>";
                    echo "<td class='centertb'>".$rowpod->cnote_start."</td>";
                    echo "<td class='centertb'>".$rowpod->cnote_end."</td>";
                    echo "<td class='centertb'>".$rowpod->cnote_count."</td>";
                    echo "</tr>";   
                    }                                  
                    ?>
                    </tbody>
  
      </table>


    </section>

      <script>
$(document).ready(function(){
     window.print();
});
      </script>
  </body>
</html>