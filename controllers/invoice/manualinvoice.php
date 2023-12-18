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

class manualinvoice extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - manualinvoice';
		$this->view->render('invoice/manualinvoice');
	}
}

if(isset($_POST['generatemanualinvoice'])){
     date_default_timezone_set('Asia/Kolkata');
     $stncode           = $_POST['stncode'];
     $stnname           = $_POST['stnname'];
     $br_code           = strtoupper($_POST['br_code']);
     $br_name           = $_POST['br_name'];
     $cust_code         = strtoupper($_POST['cust_code']);
     $trans_date_from   = $_POST['trans_date_from'];
     $trans_date_to     = $_POST['trans_date_to'];
     $inv_amt_thresholdval = $_POST['inv_amt_threshold'];
     $inv_date          = $_POST['inv_date'];
     $inv_no            = $_POST['inv_no'];
     $category          = $_POST['category'];
     $total              = $_POST['total'];
     $fsc_hc             = $_POST['fsc_hc'];
     $fsc_handling       = $_POST['fsc_handling'];
     $inv_amt            = $_POST['inv_amt'];
     $gst                = $_POST['s_tax'];
     $net_amt            = $_POST['net_amt'];
     $user_name          = $_POST['user_name'];
     $date_added         = date('Y-m-d H:i:s');
     $description        = $_POST['description'];

     $customer = $db->query("SELECT cust_gstin FROM customer WHERE cust_code='$cust_code' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
     $customergst = $customer->cust_gstin;
     $getcompdetail = $db->query("SELECT stn_gstin FROM station WHERE stncode='$stncode'")->fetch(PDO::FETCH_OBJ);
				$companygst = $getcompdetail->stn_gstin;
				if(substr($customergst,0,2)==substr($companygst,0,2)){
					$sgst = $gst/2;
					$cgst = $gst/2;
					$igst = '0';
				}
				else if(empty($customergst)){
					$sgst = $gst/2;
					$cgst = $gst/2;
					$igst = '0';
				}
				else{
					$sgst = '0';
					$cgst = '0';
					$igst = $gst;
				}
                    $invoice_insert = $db->query("INSERT INTO invoice (fhandcharge,invno,invdate,comp_code,br_code,cust_code,fmdate,todate,grandamt,fsc,igst,cgst,sgst,invamt,netamt,status,user_name,date_added,rcvdamt,inv_type,description)
                    VALUES ('$fsc_handling','$inv_no','$inv_date','$stncode','$br_code','$cust_code','$trans_date_from','$trans_date_to','$total','$fsc_hc','$igst','$cgst','$sgst','$inv_amt','$net_amt','Pending','$user_name','$date_added','0','manual','$description')");
                    Header("Location:manualinvoicelist?success=1");
}