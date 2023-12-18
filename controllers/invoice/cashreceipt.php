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

class cashreceipt extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - cashreceipt';
		$this->view->render('invoice/cashreceipt');
	}
}
error_reporting(0);
if(isset($_POST['action']) && $_POST['action'] == 'branch_name'){
   $br_code  = $_POST['br_code'];
   $station  = $_POST['station'];
   $br_name = $db->query("SELECT br_name FROM branch WITH(NOLOCK) WHERE br_code='$br_code' AND stncode='$station'")->fetch(PDO::FETCH_OBJ);

   $cc_code = $db->query("SELECT cc_code FROM collectioncenter WITH(NOLOCK) WHERE  stncode='$station'");    
   while($getautoppl8row = $cc_code->fetch(PDO::FETCH_ASSOC))
   {

     $arrayvalues[] = $getautoppl8row['cc_code'];

     
   }
   echo json_encode(array($br_name->br_name,$arrayvalues));
   
   exit();
}

if(isset($_POST['action']) && $_POST['action'] == 'cc_name'){
    $cc_code  = $_POST['cc_code'];
    $station  = $_POST['station'];
    $cc_name = $db->query("SELECT cc_name FROM collectioncenter WITH(NOLOCK) WHERE cc_code='$cc_code' AND stncode='$station'")->fetch(PDO::FETCH_OBJ);
 
    echo $cc_name->cc_name;
    exit();
 }

 if(isset($_POST['action']) && $_POST['action'] == 'fetch_shipper'){
     $stationcode   = $_POST['stationcode'];
     $branch_code   = $_POST['branch_code'];
     $gstin   = $_POST['gstin'];

     $fetch_shipper = $db->query("SELECT TOP(1) * FROM cash WITH (NOLOCK) WHERE gst='$gstin' AND br_code='$branch_code' and comp_code='$stationcode' ORDER BY date_added DESC")->fetch(PDO::FETCH_OBJ);
     $shippername = $fetch_shipper->shp_name;
     $shipperaddr = $fetch_shipper->shp_addr;
     $shippermob = $fetch_shipper->shp_mob;
     $shipperpin = $fetch_shipper->shp_pin;
     echo json_encode(array($shippername,$shipperaddr,$shippermob,$shipperpin));
     exit();
  }


 if(isset($_POST['addcashreceipt'])){
   $stncode  = $_POST['stncode'];
   $br_code  = $_POST['br_code'];
   $br_name  = $_POST['br_name'];
   $trans_date_from  = $_POST['trans_date_from'];
   $trans_date_to    = $_POST['trans_date_to'];
   $cc_code  = $_POST['cc_code'];
   $cc_name  = $_POST['cc_name'];
   $customer_name = $_POST['customer_name'];
   $gstin = $_POST['gstin'];
   $cr_date  = $_POST['cr_date'];
   $shp_mob = $_POST['shp_mob'];
   $shp_addr = $_POST['shp_addr'];
   $shp_pin = $_POST['shp_pin'];
  

   $book_conditions = array();
   if(!empty($cc_code)){
        $book_conditions[] = "AND cc_code = '$cc_code'";
   }else{
        $book_conditions[] = "     ";

   }
        $book_conditions[] = "tdate BETWEEN '$trans_date_from' AND '$trans_date_to'";

        $book_conditions[] = "status = 'Billed'";

        $book_conditions[] = "br_code = '$br_code'";

        $book_conditions[] = "comp_code = '$stncode'";

        $book_conditions[] = "gst = '$gstin'";


        $book_condition = implode(' AND ', $book_conditions);
        

   $rowcount = $db->query("SELECT * FROM cash WITH(NOLOCK) WHERE gst != '' $book_condition AND cash_receipt_no IS NULL");
  
   if($rowcount->rowCount()==0){
      header('location:cashreceipt?emptycr');
   }else{
    $lastinvno = $db->query("SELECT TOP(1) * FROM cash_receipt WITH (NOLOCK) WHERE stncode='$stncode' AND SUBSTRING(cash_receipt_no,0,4)=stncode AND cast(dbo.GetNumericValue(cash_receipt_no) AS decimal)>00001 ORDER BY cast(dbo.GetNumericValue(cash_receipt_no) AS decimal) desc");
    if($lastinvno->rowCount()==0){
        $cash_receipt_no = $stncode.'/'.'00001'.'/'.date("Y"); 
    }
    else{
        $fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);                
        $arr = $fetchinvno->cash_receipt_no; 
        $pattern = "#(?<!\S)\d+(?:/\d+)+(?!\S)(*SKIP)(*F)|[/\\\\_\s]+#u";
        $splitted = preg_split($pattern, $arr);
        $id = str_pad($splitted[1] + 1, 5,0, STR_PAD_LEFT);
        echo json_encode($splitted[1]+1);
        
        $cash_receipt_no =  $stncode.'/'.($id).'/'.date("Y");                                     
    }
 
    $cashtb = $db->query("SELECT count(gst) AS gstcount FROM cash WITH(NOLOCK) WHERE gst != '' $book_condition AND cash_receipt_no IS NULL GROUP BY gst")->fetch(PDO::FETCH_OBJ);
 
    $db->query("UPDATE cash WITH (ROWLOCK) SET cash_receipt_no='$cash_receipt_no' WHERE gst != '' $book_condition AND cash_receipt_no IS NULL");
 
    $db->query("INSERT INTO cash_receipt WITH (ROWLOCK) (cash_receipt_no, stncode, br_code, br_name, cc_code, cc_name, cash_receipt_date,frm_date,to_date,gstin,cust_name,cash_cnote_count,cr_status,customer_addr,customer_mob,customer_pin) VALUES
  ('$cash_receipt_no','$stncode','$br_code','$br_name','$cc_code','$cc_name','$cr_date','$trans_date_from','$trans_date_to','$gstin','$customer_name','$cashtb->gstcount','CR-Invoiced','$shp_addr','$shp_mob','$shp_pin')");   

   header('location:cashreceipt?success=11');

   }
  
  

 }


 //delete invoice
 if(isset($_POST['del_invno'])){
     //call function of delete invoice
     deletecashreceipt($db,$_POST['del_invno'],$_POST['del_invstn'],$_POST['del_inv_user']);
}

//set function for delete invoice
function deletecashreceipt($db,$del_invno,$del_invstn,$del_inv_user){

      $db->query("UPDATE cash SET cash_receipt_no=NULL WHERE cash_receipt_no='$del_invno'");

      $db->query("UPDATE cash_receipt SET cr_status='Deleted' WHERE cash_receipt_no='$del_invno'");

     $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Cash Receipt Delete','Cash Receipt','$del_invno','Cash Receipt has been Deleteed$del_invno','$del_inv_user',getdate())");   

}