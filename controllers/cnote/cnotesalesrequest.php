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

      class cnotesalesrequest extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - cnotesalesrequest';
              $this->view->render('cnote/cnotesalesrequest');
          }
      }

      if(isset($_POST['addcnoterequest'])){
        date_default_timezone_set('Asia/Kolkata');
              
          $comp_code          = $_POST['stncode'];
          $br_code            = $_POST['br_code'];
          $br_name            = $_POST['br_name'];
          $cust_code          = $_POST['cust_code'];
          $cust_name          = $_POST['cust_name'];
          $cnote_serial       = $_POST['cnote_serial'];
          $cnote_count        = $_POST['cnote_count'];
          $cnote_amount       = $_POST['cnote_amount'];
          $tranship_charge    = $_POST['tranship_charge'];
          $grnd_amt           = $_POST['grnd_amt'];
          $gst                = $_POST['gst'];
          $net_amt            = $_POST['net_amt'];
          $paymenttype        = $_POST['paymenttype'];
          $payment_reference  = $_POST['payment_reference'];
          $user_name          = $_POST['user_name'];
          $date_added         = date('Y-m-d H:i:s');
         //generate request id
          $get_prev_reqid = $db->query("SELECT TOP 1 * FROM cnotesalesrequest WHERE comp_code='$comp_code' ORDER BY sales_req_id DESC");
          if($get_prev_reqid->rowCount()==0){
              $request_id = 'SR1000001';
          }else{
              $lastreqidrow = $get_prev_reqid->fetch(PDO::FETCH_OBJ);
              $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastreqidrow->request_id);
              $request_id = 'SR'.($arr[1]+1);
          }

          $db->query("INSERT INTO cnotesalesrequest (comp_code,br_code,br_name,cust_code,cust_name,request_id,serial_type,cnote_quantity,stationary_charge,advance_ts_charge,grand_amt,gst,net_amt,payment_type,user_name,date_added,status,payment_reference)
         VALUES ('$comp_code','$br_code','$br_name','$cust_code','$cust_name','$request_id','$cnote_serial','$cnote_count','$cnote_amount','$tranship_charge','$grnd_amt','$gst','$net_amt','$paymenttype','$user_name','$date_added','Requested','$payment_reference')");

         header("Location:extcnotesalesrequestlist?success=1");
      }