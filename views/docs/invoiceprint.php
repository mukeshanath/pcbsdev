
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';
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



  if(isset($_POST['action']) && $_POST['action'] == 'printinvoice'){
     $invno = $_POST['invno'];
     //Invoice Info
     $fetchdata = $db->query("SELECT * FROM invoice WHERE comp_code='$stationcode' AND invno='$invno'");
     $rowinvoice = $fetchdata->fetch(PDO::FETCH_OBJ);
     $origfrmDate = "$rowinvoice->fmdate";
     $frmDate = date("d-m-Y", strtotime($origfrmDate));

     $origtoDate = "$rowinvoice->todate";
     $toDate = date("d-m-Y", strtotime($origtoDate));
     //Company Info
     $getcompanyname = $db->query("SELECT * FROM station WHERE stncode='$rowinvoice->comp_code'");
     $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);
     $fetchdiscount = $db->query("SELECT spl_discountD FROM customer WHERE comp_code='$stationcode' and cust_code = '$rowinvoice->cust_code'");
     $rowinvoicediscount = $fetchdiscount->fetch(PDO::FETCH_OBJ);
     //Customer Info
     $getcustomerinfo = $db->query("SELECT * FROM customer WHERE comp_code='$stationcode' AND cust_code='$rowinvoice->cust_code'");
     $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
     //POD info
     //GST state info
     $gstcode = substr($rowcustomer->cust_gstin,0,2);
     $statename = $db->query("SELECT state_name FROM state WHERE gstcode='$gstcode'")->fetch(PDO::FETCH_OBJ);

     $gst=($rowinvoice->invamt/100)*18;
     if(empty($rowcustomer->cust_gstin)){
          $sgst = $gst/2;
          $cgst = $gst/2;
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
     
                    $totnetamt = $rowinvoice->grandamt;
                    $fsc = $rowinvoice->fsc;
                    $hc = $rowinvoice->fhandcharge;
                    $totinvamt =  $totnetamt +  $fsc + $hc;
                    $custdisc = $rowinvoicediscount->spl_discountD;
                    $discval =  $custdisc/100;
                    $invamtafterdisc =  $totinvamt*$discval;
                              
                           
    
}
?>
   
    
  
        
      
             <!-- <tr> -->
          <?php error_reporting();
                    if($rowinvoice->br_code=='TR'){
                         $getpod = $db->query("SELECT * FROM opdata WHERE invno='$invno' ORDER BY tdate");
                         $i = 0;
                         while ($rowpod = $getpod->fetch(PDO::FETCH_OBJ))
                         {
                         $fetchdestname = $db->query("SELECT TOP 1 dest_name FROM destination,pincode WHERE destination.dest_code=pincode.dest_code AND comp_code = '$stationcode' AND (pincode='$rowpod->destn' OR destination.dest_code='$rowpod->destn')")->fetch(PDO::FETCH_OBJ);
                         $i++;
                         if($i % 2 != 0){
                              echo "</tr>";   
                         }
                         echo "<td style='width:25px;text-align:right;padding-right:5px'>".$i."</td>";
                         echo "<td style='width:70px;'>".date("d-M-Y", strtotime($rowpod->tdate))."</td>";
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
                    $getcount = $db->query("SELECT count(*) AS counting FROM credit WHERE comp_code='$stationcode' AND invno='$invno'")->fetch(PDO::FETCH_OBJ);
                    $getpod = $db->query("SELECT tdate,cno,wt,amt
                    ,(SELECT TOP 1 dest_name FROM destination LEFT JOIN pincode ON destination.dest_code=pincode.dest_code WHERE  destination.comp_code='$stationcode' AND (pincode=c.dest_code OR destination.dest_code=c.dest_code OR destination.dest_name=c.dest_code)) as destDescName
                     FROM credit c WHERE comp_code='$stationcode' AND invno='$invno' ORDER BY tdate");
                 
                    $i = 0;
                    while ($rowpod = $getpod->fetch(PDO::FETCH_OBJ))
                    {
                    //$fetchdestname = $db->query("SELECT TOP 1 dest_name FROM destination LEFT JOIN pincode ON destination.dest_code=pincode.dest_code WHERE (pincode='$rowpod->dest_code' OR destination.dest_code='$rowpod->dest_code' OR destination.dest_name='$rowpod->dest_code')")->fetch(PDO::FETCH_OBJ);
                    $i++;
                    if($i % 2 != 0){
                         echo "</tr>";   
                    }
                    echo "<td style='width:25px;text-align:right;padding-right:5px'>".$i."</td>";
                    echo "<td style='width:70px;'>".date("d-M-Y", strtotime($rowpod->tdate))."</td>";
                    echo "<td style='width:70px;'>".$rowpod->cno."</td>";
                    echo "<td style='width:90px;'>".substr($rowpod->destDescName,0,11)."</td>";
                    echo "<td style='width:50px;text-align:right;'>".number_format($rowpod->wt,3)."</td>";
                    echo "<td style='width:70px;text-align:right;padding-right: 12px;'>".number_format($rowpod->amt,2)."</td>";
                    if($i % 2 == 0){
                    echo "</tr>";   
                    }
                    }
                    }       
                      
                         
                    
                    echo json_encode(array($getcount->counting));
                    ?>
            
                   

   
    
