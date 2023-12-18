<?php 

/* File name   : bainbound
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
//session idle 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
	session_unset();     
	session_destroy();
	header("location: login?invalidtoken");  
  }
  $_SESSION['LAST_ACTIVITY'] = time();
//session idle 

include 'db.php';

    class bainboundbatch extends My_controller
    {
      public function index()
      {
            $this->view->title = __SITE_NAME__ . ' - bainbound';
            $this->view->render('billing/bainboundbatch');
      }
    }
    
 date_default_timezone_set('Asia/Kolkata');
 if(isset($_POST['cust_code'])){
  try
{
  bainboundbatch($db);
}

catch(PDOException $e){
  $date_added	= date('Y-m-d H:i:s');
   
  $user_name	= $_POST['user_name'];
  $comp_code	= $_POST['comp_code'];
  $cnote_no		= $_POST['cnote_no'];
  $error_info	= $e->errorInfo[1];
  $error_detail	= explode("]",$e->errorInfo[2]);
  $err_desc = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', END($error_detail));

  $db->query("INSERT INTO logs_tb (log_datetime,user_name,status) VALUES ('$date_added','$user_name','Transaction failed at BAIB Batch. ERROR_CODE:$error_info-$err_desc. CNO:$comp_code$cnote_no')"); 

}
}
    function bainboundbatch($db){
        $comp_code          	 = $_POST['stncode'];
        $br_code 			      = $_POST['br_code'];
        $origin 			      = $_POST['stncode'];
        $podorigin 	      	 = $_POST['podorigin'];
        $pcs				      = $_POST['pcs'];
        $mf_no			      = strtoupper($_POST['mfno']);
        $cust_code		      = $_POST['cust_code'];
        $cust_name		      = $_POST['cust_name'];
         $wt 				      = max($_POST['wt'],$_POST['v_wt']);
          $weight = $_POST['wt'];
		   	$v_wt = $_POST['v_wt'];
        $amount			      = $_POST['amount'];
        $cnote_serial			= $_POST['cnote_serial'];
        $pcs			        = $_POST['pcs'];
        $mode			        = $_POST['mode'];
        $dest_code	      	 = explode('-',$_POST['destn'])[0];
        $cnote_no	   	      	 = $_POST['cnote_no'];
        $user_name          	 = $_POST['user_name'];       
        $date_added         	 = date('Y-m-d H:i:s');
        $bdate              	 = $_POST['bdate'];
        $edate              	 = date('Y-m-d H:i:s');
        $consignee     =       $_POST['consignee'];
        $consignee_addr     =       $_POST['consignee_addr'];
        $consignee_mobile     =       $_POST['consignee_mobile'];
        $consignee_pin     =       $_POST['consignee_pin'];

        if(empty($cnote_serial)){
          $cnote_serial = getcnote_serial($db,$cnote_no,$comp_code,$podorigin);
        }

         // fetch dest code and dest name
        $desttbverify = $db->query("SELECT * FROM destination  WHERE comp_code='$comp_code' AND dest_code='$dest_code' OR dest_name='$dest_code'");
        if($desttbverify->rowCount()==0){
          $dest_verify = $db->query("SELECT * FROM pincode WHERE pincode='$dest_code' OR city_name='$dest_code' OR dest_code='$dest_code'"); 
              if($dest_verify->rowCount()==0){
            $pincode  	 = $dest_code;
           }
           else
           {
            $rowdest_verify = $dest_verify->fetch(PDO::FETCH_OBJ);
            $pincode  	 = $rowdest_verify->pincode;
           }
        }
        else
        {
          $rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
          $pincode    = $rowdestntn->dest_code;
        }
        $creditmaster_verify = $db->query("SELECT * FROM bainboundbatch_master WHERE mf_no='$mf_no' AND ba_code='$cust_code' AND date_added='$date_added'");
					if($creditmaster_verify->rowCount()==0)
					{
						$db->query("INSERT INTO bainboundbatch_master (comp_code,mf_no,br_code,ba_code,ba_name,total_cnotes,user_name,date_added)
						VALUES ('$comp_code','$mf_no','$br_code','$cust_code','$cust_name','1','$user_name','$date_added')");
					}
					else
					{
						$db->query("UPDATE bainboundbatch_master SET total_cnotes=total_cnotes+1 WHERE mf_no='$mf_no' AND ba_code='$cust_code' AND date_added='$date_added'");
					}
        
          $db->query("INSERT INTO bainboundbatch (cnote_serial,origin,podorigin,cno,dest_code,bacode,consignee,wt,ec_wt,pcs,mode_code,amt,mf_no,br_code,comp_code,user_name,date_added,bdate,tdate,edate,status,consignee_mobil,consignee_addr,consignee_pin,v_wt,pincode) VALUES
          ('$cnote_serial','$origin','$podorigin','$cnote_no','$dest_code','$cust_code',' $consignee','$weight','$wt','$pcs','$mode','$amount','$mf_no','$br_code','$comp_code','$user_name','$date_added','$bdate','$bdate','$edate','Billed','$consignee_mobile','$consignee_addr','$consignee_pin','$v_wt','$pincode')");
        if(isset($_POST['type']) && $_POST['type']=='Save'){
          $db->query("EXEC IBQ_OnTime_Update");
        }
    }

    //delete batch cnotes
    if(isset($_POST['delbatchcompany'])){
      $delbatchcompany = $_POST['delbatchcompany'];
      $delbatchcnote = $_POST['delbatchcnote'];
      $delbatchuser = $_POST['delbatchuser'];
      $db->query("DELETE FROM bainboundbatch WHERE cno='$delbatchcnote' AND comp_code='$delbatchcompany'");
      $db->query("DELETE FROM credit WHERE cno='$delbatchcnote' AND comp_code='$delbatchcompany'");
      $db->query("DELETE FROM bainbound WHERE cno='$delbatchcnote' AND comp_code='$delbatchcompany'");
      $db->query("UPDATE cnotenumber SET bilwt =null, bildest=null,bilrate=null,bdate=null,bilmode=null,cnote_status='Allotted' WHERE cnote_number='$delbatchcnote' AND cnote_station='$delbatchcompany'");

      //store audit log
      $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Billing','BAInbound','Deleted','Cnote $delbatchcnote has been Deleted in Station $delbatchcompany','$delbatchuser',getdate())");
    }

    function getcnote_serial($db,$cno,$comp_code,$category){
      if($category=='gl' || empty($category) || trim($category)==trim($comp_code)){
        $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND (SELECT grp_type FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)='gl'")->fetch(PDO::FETCH_OBJ);
      }
      else{
        $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND cnote_serial='$category'")->fetch(PDO::FETCH_OBJ);
      }
      return $serial->cnote_serial;
    }