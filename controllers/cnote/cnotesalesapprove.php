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

      class cnotesalesapprove extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - cnotesales';
              $this->view->render('cnote/cnotesalesapprove');
          }
      }

      if(isset($_POST['approvecnotesales'])) 
      {
          date_default_timezone_set('Asia/Kolkata');
        
          $stncode           = $_POST['stncode'];
          $stnname           = $_POST['stnname'];
          $br_code           = $_POST['br_code'];
          $br_name           = $_POST['br_name'];
          $cust_code         = $_POST['cust_code'];
          $cust_name         = $_POST['cust_name'];
          $tranship_charge   = $_POST['tranship_charge'];
          $payment_type      = $_POST['paymenttype'];
          $chqdep_no         = $_POST['payment_reference'];
          $grnd_amt          = $_POST['grnd_amt'];
          $gst               = $_POST['gst'];
          $net_amt           = $_POST['net_amt'];
          $username          = $_POST['user_name'];
          
          $date_added        = date('Y-m-d H:i:s');
          $cnote_serial      = $_POST['cnote_serial'];
          $cnote_count       = $_POST['cnote_count'];
          $cnote_start       = $_POST['cnote_start'];
          $request_id         = $_POST['request_id'];
          $cnote_procured    = $_POST['cnote_procured'];
          // $cnote_void        = $_POST['cnote_void'];
          $cnote_amount      = $_POST['cnote_amount'];
       
          //generate sales order
          $soid = $db->query("SELECT TOP 1 salesorderid FROM cnotesales ORDER BY cnotesalesid DESC");
          if($soid->rowCount()==0){
              $salesorderid = 'SO'.str_pad('1', 5, "0", STR_PAD_LEFT);
          }
          else{
              $rowsoid = $soid->fetch(PDO::FETCH_OBJ);
              $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$rowsoid->salesorderid);
              $salesorderid = $arr[0].str_pad(($arr[1]+1), 5, "0", STR_PAD_LEFT);
          }

         $sql = "INSERT INTO cnotesales (flag,stncode,stnname,br_code,br_name,salesorderid,cust_code,cust_name,cnote_serial,cnote_count,cnote_start,cnote_status,cnote_procured,cnote_charge,CNote_Stationary_Charge,GST,GrandAmt,NetAmt,pMode,RcptNo,date_added,user_name) 
                                 VALUES (1,'$stncode','$stnname','$br_code','$br_name','$salesorderid','$cust_code','$cust_name','$cnote_serial','$cnote_count','$cnote_start','Allotted','$cnote_procured','$cnote_amount','$cnote_amount','$gst','$grnd_amt','$net_amt','$payment_type','$chqdep_no','$date_added','$username')";
         $result  = $db->query($sql);



          for($i = 0; $i < count($_POST['salespono']); $i++){
              $salespono  = stripslashes($_POST['salespono'][$i]);
              $salesstart = stripslashes($_POST['salesstart'][$i]);
              $salesend   = stripslashes($_POST['salesend'][$i]);
              $salescount = stripslashes($_POST['salesvalid'][$i]);

              $allotted = "UPDATE cnotenumber SET cnote_status ='Allotted', br_code='$br_code', br_name='$br_name',cust_code='$cust_code',cust_name='$cust_name',allot_date='$date_added', allot_po_no='$salesorderid' WHERE cnote_status='Printed' AND cnote_station='$stncode' AND cnote_number BETWEEN '$salesstart' AND '$salesend'";
              $allotted  = $db->query($allotted); 

              $insertsalesdetail = $db->query("INSERT INTO cnotesalesdetail(stncode,stnname,br_code,br_name,salesorderid,cnotepono,cust_code,cust_name,cnote_serial,cnote_count,cnote_start,cnote_end,cnote_status,date_added,user_name)
               VALUES ('$stncode','$stnname','$br_code','$br_name','$salesorderid','$salespono','$cust_code','$cust_name','$cnote_serial','$salescount','$salesstart','$salesend','Allotted','$date_added','$username')");
          }

          $so= "INSERT INTO cnote_so_tb (salesorderid,cnote_charge,tranship_charge,payment_type,chqdep_no,grnd_amt,gst,net_amt,date_added) 
                                  VALUES ('$salesorderid','$cnote_amount','$tranship_charge','$payment_type','$chqdep_no','$grnd_amt','$gst','$net_amt','$date_added')";
          $salesorder = $db->query($so);
       
          //update externalsalesrequest
          $db->query("UPDATE cnotesalesrequest SET salesorderid='$salesorderid',status='Approved' WHERE request_id='$request_id'");

     header("location: cnotesalesrequestlist?success=1");
       }
       
      
?>