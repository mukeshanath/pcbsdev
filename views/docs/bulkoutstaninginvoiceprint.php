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
        header { page-break-before: always; }
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
           table{
            width: 100%;
           }
       }
      
    
    
      .tablehead{
           font-size:10px;
           line-height:2;
      }
      .tablehead th{
           border-bottom: 1px solid black; 
      }
      /* tbody{
          border-bottom: 1px solid black;  
      } */
      .border-bottom{
           border-bottom: 1px solid black; 
      }
      .cnotestable{
           width:50%;
      }
      .tablebody{
           font-size:12px;
      }
 
   
   
      .border-top{
           border-top:1px solid black;
      }
     th{
        border-bottom: 1px solid black; 
     }
    table{
        width: 90%;
    }
    td{
        text-align: center;
    }
  
    
       img{
        width: 320px;
        margin-left: 40px;
      }
      .row {
  display: flex;
}

/* Create two equal columns that sits next to each other */
.column {

  padding: 10px;
/* Should be removed. Only for demonstration */
}
th{
    text-align: right;
}
.column1 p{
  font-weight: normal;
}
.authroty p{
  font-weight: normal;
}
td{
  font-weight: normal;
  text-align:right;
}
        </style>
  <body id="printable">
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(isset($_GET['print_station'])){

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

     $print_station = $_GET['print_station'];
     $invno_json = explode(",",$_GET['invno_json']);
    // print_r($invno_json);
 foreach($invno_json as $inv=>$invno){
         
     $fetchdata = $db->query("SELECT *,(select cust_name from customer as c where i.cust_code=c.cust_code) as customername
     ,(select cust_addr from customer as c where i.cust_code=c.cust_code) as custaddress,(select cust_city from customer as c where i.cust_code=c.cust_code) as custcity
     ,(select cust_state from customer as c where i.cust_code=c.cust_code) as custstate
     ,(select cust_phone from customer as c where i.cust_code=c.cust_code) as custphone FROM invoice as i WHERE cust_code='$invno' AND comp_code='$stationcode'");
     $rowinvoice = $fetchdata->fetch(PDO::FETCH_OBJ);

     $getcompanyname = $db->query("SELECT * FROM station WHERE stncode='$rowinvoice->comp_code'");
     $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);
  
    //  $getcustomerinfo = $db->query("SELECT * FROM customer WHERE cust_code='$rowinvoice->cust_code'");
    //  $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ); 
   
     $gettotal = $db->query("SELECT sum(netamt-rcvdamt) as total FROM invoice WHERE cust_code='$invno'");
     $total = $gettotal->fetch(PDO::FETCH_OBJ);

 

?>
 <div  style="padding:100px 0px 0px 5px;";>
  <header>
   
    <div class="row">
    <div class="column">

    <b><?=$rowcompany->stnname;?></b><br>
    <div style = "font-weight: normal;"><?=$rowcompany->stn_addr;?>,<br /><?=$rowcompany->stn_city;?><?=$rowcompany->stn_state;?><br>Phone :<?=$rowcompany->stn_mobile;?></div>
                      </div>
  <div class="column">
  <img src="views/image/TPC-Logo.jpg" class="image">
  </div>
</div>

<div class="row">
<div class="column"><strong>To,</strong><br>
<b><?=$rowinvoice->customername?></b><br>


<div style = "font-weight: normal;"><?=$rowinvoice->custaddress?>,<br/><?=$rowinvoice->custcity;?><?=$rowinvoice->custstate;?><br>Phone :<?=$rowinvoice->custphone;?><br>Customer Code: <?=$rowinvoice->cust_code?><br></div>
                  </div>
  <div class="column" style="margin-left: 270px;">
  
  </div>
</div>

<div class="row">
  <div class="column"> <p>Dear Sir / Madam,<p></div>
  
</div>
<div class="row">
  <div class="column"><b>Sub: Pending outstanding invoices and it's details<b></div>
  
</div>
<div class="row">
  <div class="column1" style="margin-left: 10px;"><p>Please find the list of pending outstanding invoice details and it's amount. Please clear it immediately.<p></div>
  
</div>
</header>
<section>
          









          <table style="border-top:1px solid black";>
       
  
        <tr>
            <th>SNo</th>
            <th>Inv No</th>
            <th>Bill Date</th>
            <th>Bill Amount</th>
            <th>Total Collection</th>
            <th>Balance</th>
        </tr>
        <tbody class="tablebody">
               <?php

 $query = $db->query("SELECT * FROM invoice WHERE comp_code='$stationcode' AND cust_code='$invno' AND status='Pending'"); 
 
 $i=1;
 while ($row = $query->fetch(PDO::FETCH_OBJ))
 {
  
               ?>
                    </tbody>
        <tr>             <td><?=$i;?></td>
                        <td><?=$row->invno;?></td>
                       
                        <td><?=date("d-m-Y", strtotime($row->invdate));?></td>
                        <td><?=number_format($row->netamt,2)?></td>
                        <td><?=number_format($row->rcvdamt,2)?></td>
                        <td><?=number_format($row->netamt-$row->rcvdamt,2)?></td>
        </tr>
        <?php $i++; ?>
        <?php   }   ?> 
                  <tfoot>
                       <tr>
                       <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Total</b></td>
                        <td style="border-bottom: 1px solid black;border-top: 1px solid black;"><b><?=number_format($total->total,2)?></b></td>
                       </tr>
                  </tfoot >   
     
    </table ><br>
         <div class="content1" style="padding: 10px;border-top:1px solid black;width:88%;">
         <b>Note : If the payments are already settled, kindly provide the payment infomation</b>
         </div>
         <div class="content2" style="padding: 10px;">
         <b>PLEASE TREAT THIS LETTER AS TOP MOST URGENT</b>
         </div>
         <div class="content3" style="padding: 10px;">
         <b>Thanking you</b>
         </div>
         <footer style="margin-top: 90px;">
           for <b><?=$rowcompany->stnname;?></b>
           <div class="authroty" style="margin-top: 40px;">
               <p>Authorized Signatory</p>
           </div>
           </footer>
 </section>
 </div>
      <?php
}
}

?>
      <script>
        $(document).ready(function(){
            window.print();
        });
      </script>
  </body>
</html>