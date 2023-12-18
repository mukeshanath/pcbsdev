<?php   include 'db.php';  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Report</title>
</head>
<style>
@page {
  size: A4;
  margin: 4mm 4mm 4mm 4mm;
  size: portrait;
 }
 @media print{
     .cnotestable{
     width:850px !important;
     padding-top: 0px !important; 
   
    
}
.tablehead tr{
     font-size:11px !important; 
     border-bottom: 1px solid black; 
}

 .headingh2{
     font-size:18px !important;
 }  

 }
  body{
     margin: auto;
    
     padding:15px;
     background:#fff;
     font-family:"Times New Roman", Times, serif;
  }
  .tablebody{
     font-size:12px;
}
.cnotestable{
     width:800px;
     padding: 20px;  
    
}
table, thead, tr, th{
    padding-top:5px;
    padding-bottom:5px;
}
table, thead, tr, th, .cno{
    padding-top:5px;
    padding-bottom:5px;
}
.tablehead th{
     border-bottom: 1px solid black; 
}
.row {
  display: flex;
}
</style>

<body>
    <?php
    if(isset($_GET['book_date_from'])){

        $book_date_from = $_GET['book_date_from'];
        $book_date_to = $_GET['book_date_to'];
        $weight_from = $_GET['weight_from'];
        $weight_to = $_GET['weight_to'];
        $bk_branch = $_GET['br_brcode'];
        $bk_customer = $_GET['br_custcode'];
        $book_destination = $_GET['destination'];
        $bkrpt_podorigin = $_GET['bkrpt_podorigin'];
        $book_zone = $_GET['zone'];
        $book_rep_type = $_GET['book_rep_type'];
        $bookstn = $_GET['bookstn'];
        $current_station = $_GET['current_station'];
        $cc_code = $_GET['cc_code'];
        $station = $_GET['station'];   
        $payment_mode = $_GET['payment_mode'];   
        $exbainbound  = $_GET['exbainbound'];
        $excustomer   = $_GET['excustomer'];
        $mode         = $_GET['mode'];
        ?>
          <div>
            <img src="views/image/TPC-Logo.jpg" class="image" style="margin-left:12px;width:250px;">
         </div>
<div class="row">
  <div class="column" style="margin-top:10px;margin-left: 12px;font-size:16px;padding-right:0px;">
  <div style='float:left;'>
            <h4 class="headingh2" style="font-family:Arial, Helvetica, sans-serif;font-weight:600;padding-bottom:0px;margin-top: 0px;margin-bottom: 6px;"><?php $stationname = $db->query("SELECT stnname FROM station WHERE stncode='$current_station'")->fetch(PDO::FETCH_OBJ); echo $stationname->stnname ?></h4>
            <p style="margin-top:4px;font-size:16px" id='report-title'>Statement of Booking Entry Between <?=$book_date_from;?> and <?=$book_date_to;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?=((empty($bk_branch) || $bk_branch=='ALL') ? '' : '<b>Branch :</b>'.$bk_branch.'')?></span></p>
          </div>
    
  </div>

  
    </div>





<?php

    
    
    
      if($book_rep_type=='bookcash'){
        $table = 'cash';
        $cust_type_condition = '';
        $columnramt = "";
        $podcolumn = "pod_origin";
       }
    
    $book_conditions = array();
    if(!empty($book_date_from)){
         $book_conditions[] = "tdate BETWEEN '$book_date_from' AND '$book_date_to'";
    }
    if($book_rep_type=='bookcash'){
         $book_conditions[] = "status not in ('Billed-Void','Invoiced-Void')";
         
    }
    // if($book_rep_type=='bookcash' && $cc_code=='ALL'){
             
    // }else{
    //      $book_conditions[] = "cc_code='$cc_code'";
    // }
    if($book_rep_type=='overallbookcash'){
         $book_conditions[] = "status not in ('Billed-Void','Invoiced-Void')";
    
         
    }
    if($book_rep_type=='bookcredit'){
         $book_conditions[] = "status not in ('Billed-Void','Invoiced-Void')";
    }
    if($book_rep_type=='bookcash'){
      if($payment_mode=='ALL'){
          
      }else{
           $book_conditions[] = "payment_mode='$payment_mode'";
      }    
    }
    //  if($book_rep_type=='bookcash'){
    //   if($mode=='ALL'){
          
    //   }else{
    //        $book_conditions[] = "mode_code='$mode'";
    //   }    
    // }
    if(!empty($weight_from)){
        $book_conditions[] = "wt BETWEEN '$weight_from' AND '$weight_to'";
    }
    if(empty($bk_branch) || $bk_branch=='ALL'){
        
    }
    else{
        $book_conditions[] = "br_code ='$bk_branch'";
    }
    if(empty($cc_code) || $cc_code=='ALL'){
        
    }
    else{
        $book_conditions[] = "cc_code ='$cc_code'";
    }
    
    if(empty($bk_customer) || $bk_customer=='ALL'){
        
    }
    else{
        $book_conditions[] = "$customercolumn ='$bk_customer'";
    }
    if(!empty($book_destination)){
        $book_conditions[] = "dest_code = '$book_destination'";
    }
    if(!empty($book_zone)){
        $book_conditions[] = "zone_code = '$book_zone'";
    }
    if(!empty($bookstn)){
        $book_conditions[] = "comp_code = '$bookstn'";
    }
    if($bkrpt_podorigin=='ALL'){
    }
    else if($bkrpt_podorigin==$bookstn){
         $book_conditions[] = "  $podcolumn not in ('PRO','PRC','PRD','COD')";
    }
    else{
         if($exbainbound=='exbainbound'){
              $book_conditions[] = " cust_code='$excustomer'";
    
         }else{
              $book_conditions[] = "  $podcolumn='$bkrpt_podorigin'";
         }
    }
    $book_condition = implode(' AND ', $book_conditions);



?>
<div class="scrollable2">


<table class="cnotestable">
        <thead>
          <tr class="tablehead" style="font-size:13px;">
            <th style="">SNo</th>
            <th style="text-align:left;">Cno</th>
            <th style="text-align:right;">Weight</th>
            <th style="text-align:left;">Mode</th>
            <th style="text-align:left;">Pincode</th>
            <th style="text-align:right;">Amount</th>            
            <th style="margin-right:10px;">SNo</th>
            <th style="text-align:left;">Cno</th>
            <th style="text-align:right;">Weight</th>
            <th style="text-align:left;">Mode</th>
            <th style="text-align:left;">Pincode</th>
            <th style="text-align:right;">Amount</th>   
          </tr>
        </thead>
        <tbody class="tablebody">
      
        <?php
         $booking_report = $db->query("SELECT * FROM $table WITH (NOLOCK)  WHERE $book_condition $cust_type_condition ORDER BY tdate");
        
          //04/09/2022
          if($table=='cash'){
            $totalbookamt = $db->query("SELECT sum(amt) as 'total', SUM(convert(float,igst)) as 'igstval', SUM(convert(float,sgst)) as 'sgstval', SUM(convert(float,cgst)) as 'cgstval', SUM(convert(float,charges)) as 'charges' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition")->fetch(PDO::FETCH_OBJ);
        }else{
            $totalbookamt = $db->query("SELECT sum(amt) as 'total' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition")->fetch(PDO::FETCH_OBJ);
        }
         //04/09/2022
       
       
       if($book_rep_type=='bookcash'){
            $validatecount = $db->query("SELECT count(*) as 'rctotal' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition AND ramt is not null AND ramt!=''")->fetch(PDO::FETCH_OBJ);
            
            if($validatecount->rctotal>0){
                $totalbookramt = $db->query("SELECT sum(ramt) as 'rtotal' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition AND ramt>0")->fetch(PDO::FETCH_OBJ);
            }
       }

       

         $b_sno=0;
         while($bkrow = $booking_report->fetch(PDO::FETCH_OBJ)){ $b_sno++;
                      $i++;
                         if($i % 2 != 0){
                              echo "</tr>";   
                         }
                 echo "<td style='text-align:center;'>".$b_sno."</td>
                 <td>".$bkrow->cno."</td>
                  <td style='text-align:right;'>".number_format($bkrow->wt,3)."</td>
                  <td>&nbsp;&nbsp;&nbsp;".$bkrow->mode_code."</td>";
                  echo "<td>".((empty($bkrow->consignee_pin) || is_null($bkrow->consignee_pin)) ? $bkrow->dest_code : $bkrow->consignee_pin)."</td>
                <td style='text-align:right;'>".number_format($bkrow->ramt,2)."</td>";
                  
                if($i % 2 == 0){
                    echo "</tr>";   
                    }
         }
         echo "<tr style='margin-top:30px;'>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black'></td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black;text-align:right;'>Total</td>
         <td style='font-weight:bold;line-height:20px;border-top:1px solid black;border-bottom:1px solid black;text-align:right;'>".(($validatecount->rctotal>0)?number_format($totalbookramt->rtotal, 2):'0')."</td>";
        
       
   
        echo" </tr>";



        
        
               ?>       
                    </tbody>                   
      </table>

               </div>
</body>
<script>
     window.print();
     if(window.location.host!='localhost')
     window.history.pushState('PCNL', 'Title', '/');

      </script>
</html>


<?php } ?>