<?php
session_start();

?>
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
    text-align:center;
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
  text-align:center;
}
.content1{
    font-weight: normal;
}
        </style>
  <body id="printable">
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


    if(isset($_GET['collectioninvoice'])){
        $cc_code = $_GET['collectioninvoice'];
        $from_date = $_GET['fromdate'];
        $to_date = $_GET['todate'];
        $stncode = $_GET['stncode'];
        $br_code = $_GET['br_code'];
        $ccinvno = $_GET['ccinvno'];
    
 
     //SELECT * FROM cash WHERE comp_code='AMB' AND br_code='AMBINT' AND cc_code='AMBCC10002' AND tdate BETWEEN '2022-08-11' AND '2022-10-20'


     


 

    $getcompanyname = $db->query("SELECT * FROM collectioncenter WHERE cc_code='$cc_code'");
    $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);

      $getcustomerinfo = $db->query("SELECT * FROM ccinvoice WHERE cc_code='$cc_code' AND br_code='$br_code' and from_date='$from_date' and to_date='$to_date' and ccinvno = '$ccinvno'");
      $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);

      $getcomp = $db->query("SELECT * FROM station WHERE stncode='$stncode'");
      $rowcomp = $getcomp->fetch(PDO::FETCH_OBJ);

      $getcommission = $db->query("SELECT percent_domscomsn FROM collectioncenter WHERE cc_code='$cc_code'");
      $rowcommission = $getcommission->fetch(PDO::FETCH_OBJ);


?>

<div  style="padding:0px 0px 0px 5px;";>
  <header>
   <h3 style="text-align:center;"><?=$rowcompany->cc_name?></h3>
    <div class="row"style="line-height: 0.3;">
    <div class="column" style="width:100%;">
    <div style = "font-weight: normal;">
     <p>SVC Name  : <?=$rowcustomer->br_code;?></p>
     <p>CC Code  : <?=$rowcustomer->cc_code;?></p>
     <p>Invoice No  : <?=$rowcustomer->ccinvno;?></p>
     <p>Inv. Date  : <?=$rowcustomer->ccinvdate;?></p>
     <p>PANCARD No  : <?=$rowcompany->pancardno?></p>
     <p>Cheque in fav  : <?=$rowcompany->checkue?></p>

</div>
                      </div>
  <div class="column" style="margin-left:100px;width:100%;">
  <div style = "font-weight: normal;">

     <p>Bank Name : <?=$rowcompany->bank_name?></p>
     <p>Bank Branch  : <?=$rowcompany->bank_branch?></p>
     <p>A/C No  : <?=$rowcompany->bank_acc_no?></p>
     <p>IFSC Code  : <?=$rowcompany->ifsc?></p>
    
     
</div>
  </div>
</div>

<div class="row">
<div class="column"><strong>To,</strong><br>
</div>
</div>

<div class="row">
  <div class="column1" style="margin-left: 2px;font-size:16px;"><p><?=$rowcomp->stnname?>,<?=$rowcomp->stn_addr?>,<?=$rowcomp->stn_city?><p></div>
  
</div>
</header>
<section>
          









          <table style="border-top:1px solid black";>
       
 
        <tr>
       
            <th>Date</th>
            <th>No of Consignment</th>
            <th>Amount</th>
            <th>Commision - <?=$rowcommission->percent_domscomsn?>%</th>
          
        </tr>
        <tbody class="tablebody">
        <?php

      
$getdata = $db->query("SELECT COUNT(tdate) as count,SUM(ramt) as amount, tdate,(select percent_domscomsn from collectioncenter as cc where cc.cc_code=c.cc_code) as spldiscount FROM cash as c where cc_status='Invoiced' AND status='Billed' AND comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' AND ccinvno='$ccinvno' AND tdate BETWEEN '$from_date' AND '$to_date' GROUP BY c.tdate,c.cc_code order by tdate asc");
//echo json_encode($getdata);
while($rowcash = $getdata->fetch(PDO::FETCH_OBJ)){

$discount = $rowcash->spldiscount;
 $rate = $rowcash->amount * $discount;  
 $value =  $rate/100;
 
?>
                    </tbody>
        <tr>            
       
             <td><?=date("d-m-Y", strtotime($rowcash->tdate));?></td>
             <td><?=$rowcash->count?></td>
             <td> <?=number_format($rowcash->amount,2)?></td>
             <td> <?=number_format($value,2)?></td>           
        </tr>
        
       
 <?php  } ?>
                  <tfoot>
                    <?php
                    //   $cc_code = $_GET['collectioninvoice'];
                    //   $from_date = $_GET['fromdate'];
                    //   $to_date = $_GET['todate'];
                    //   $stncode = $_GET['stncode'];
                    //   $br_code = $_GET['br_code'];
$totalper = $db->query("select sum(ramt) as total from cash where status='Billed' AND ccinvno='$ccinvno' AND tdate BETWEEN '$from_date' AND '$to_date' AND  comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' AND cc_status='Invoiced'")->fetch(PDO::FETCH_OBJ);


                    //   $br_code = $_GET['br_code'];
                    $count = $db->query("SELECT tdate, COUNT(tdate) AS Count, SUM(COUNT(tdate)) OVER() AS Sum 
                    FROM cash WHERE status='Billed' AND cc_status='Invoiced' AND ccinvno='$ccinvno' AND comp_code='MAA' AND br_code='$br_code' AND cc_code='$cc_code' AND tdate BETWEEN '$from_date' AND '$to_date' 
                    GROUP BY tdate");
                    $ratecount = $count->fetch(PDO::FETCH_OBJ);

               	$getdata1 = $db->query("SELECT COUNT(tdate) as count,SUM(ramt) as amount, tdate,(select percent_domscomsn from collectioncenter as cc where cc.cc_code=c.cc_code) as spldiscount FROM cash as c where status='Billed' AND cc_status='Invoiced' AND ccinvno='$ccinvno' AND comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' AND tdate BETWEEN '$from_date' AND '$to_date' GROUP BY c.tdate,c.cc_code")->fetch(PDO::FETCH_OBJ);

			$discount1 = $getdata1->spldiscount;
            $rate =   $totalper->total * $discount1;  
              $value1 =  $rate/100;


//convert number to wordf
function getIndianCurrency(float $number)
    {
        $no = floor($number);
        $decimal = round($number - $no, 2) * 100;
        $decimal_part = $decimal;
        $hundred = null;
        $hundreds = null;
        $digits_length = strlen($no);
        $decimal_length = strlen($decimal);
        $i = 0;
        $str = array();
        $str2 = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }

        $d = 0;
        while( $d < $decimal_length ) {
            $divider = ($d == 2) ? 10 : 100;
            $decimal_number = floor($decimal % $divider);
            $decimal = floor($decimal / $divider);
            $d += $divider == 10 ? 1 : 2;
            if ($decimal_number) {
                $plurals = (($counter = count($str2)) && $decimal_number > 9) ? 's' : null;
                $hundreds = ($counter == 1 && $str2[0]) ? ' and ' : null;
                @$str2 [] = ($decimal_number < 21) ? $words[$decimal_number].' '. $digits[$decimal_number]. $plural.' '.$hundred:$words[floor($decimal_number / 10) * 10].' '.$words[$decimal_number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str2[] = null;
        }
        
        $Rupees = implode('', array_reverse($str));
        $paise = implode('', array_reverse($str2));
        $paise = ($decimal_part > 0) ? $paise . ' Paise' : '';
        return ($Rupees ? ucwords($Rupees) . 'Rupees Only' : '') . $paise;
    }

?>
                       <tr>
                       <td style="border-bottom: 1px solid black;border-top: 1px solid black;"><b>Total</b></td>
                        <td style="border-bottom: 1px solid black;border-top: 1px solid black;"><b><?=$ratecount->Sum?></b></td>
                        <td style="border-bottom: 1px solid black;border-top: 1px solid black;"><b><?=number_format($totalper->total,2)?></b></td>       
                        <td style="border-bottom: 1px solid black;border-top: 1px solid black;"><b><?=number_format($value1,2)?></b></td>
                       </tr>
                  </tfoot>   
     
    </table ><br>
         <div class="content1"style = "font-weight: normal;">
      Rupees : <?=getIndianCurrency($totalper->total)?>
         </div>
         <div class="content2" style="padding: 10px;margin-left:450px;line-height: 1.6;">
        <b style=""> Net Amount (round off) - <?=number_format(round($value1),2)?><br><p style="font-size:14px;"><?=$rowcompany->cc_name?><p></b>
         </div>
         <div class="fotclass" style="display:flex">
         <div class="content3">
         <P>Branch Manager/Incharge Sign<p>
         </div>
         <div class="content3" style="margin-left:260px;">
         <P>Authorised Signatory<br><?=$rowcompany->cc_phone?><p>
         </div>
         </div>
         
       
 </section>
 </div>
      <?php

}
?>


      <script>
        $(document).ready(function(){
            window.print();
        });
      </script>
  </body>
</html>