<?php 

/* File name : cash controller.php
 Begin       : 2020-03-04
 Description : class tax file saves master tax slab in appliaction
               Default Header and Footer

 Author: zabiullah

 (c) Copyright:
             CDSS
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

class cash extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - cash';
		$this->view->render('billing/cash');
	}
}

	if(isset($_POST['addbillingcash']))
	{
		try
		{

			function getcnote_serial($db,$cno,$comp_code,$category){
				if($category=='gl' || empty($category) || trim($category)==trim($comp_code)){
				  $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND (SELECT grp_type FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)='gl'")->fetch(PDO::FETCH_OBJ);
				}
				else{
				  $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND cnote_serial='$category'")->fetch(PDO::FETCH_OBJ);
				}
				return $serial->cnote_serial;
			  }

		date_default_timezone_set('Asia/Kolkata');

		
		$comp_code          = $_POST['comp_code'];
		$br_code 			= strtoupper($_POST['br_code']);
		$origin 			= $_POST['origin'];
		$cc_code 			= $_POST['cc_code'];
		$cc_name 			= $_POST['cc_name'];
		$consignee_name		= $_POST['consignee_name'];
		$consignee_addr		= $_POST['consignee_addr'];
		$consignee_mob 		= $_POST['consignee_mob'];
		$consignee_pin 		= $_POST['consignee_pin'];
		$book_counter		= strtoupper($_POST['book_counter']);
		$book_counter_name	= $_POST['book_counter_name'];
		$pcs				= $_POST['pcs'];
		$shp_name				= $_POST['shp_name'];
		$shp_addr				= $_POST['shp_addr'];
		$payment_mode		= $_POST['payment_mode'];
		$payment_reference	= $_POST['payment_reference'];
		$shp_mob				= $_POST['shp_mob'];
		$shp_pin				= $_POST['shp_pin'];
		$podorigin				= $_POST['podorigin'];
		$gstin				= $_POST['gstin'];
		$mf_no			= $_POST['mf_no'];
		$bkgstaff			= $_POST['bkgstaff'];
        $date           = date_create($_POST['bdate']);
        $bdate          =  date_format($date,"Y/m/d");
		$wt 				= max($_POST['wt'],$_POST['v_wt']);
		$weight = $_POST['wt'];
		$v_weight = $_POST['v_wt'];
		$cnote_type     = $_POST['cnote_type'];
		$amount			= $_POST['amount'];
		$mode			= $_POST['mode'];
		$destination	     = explode('-',$_POST['destination'])[0];
		$cnote_no	   	     = $_POST['cnote_no'];
		$user_name          = $_POST['user_name'];
        $contents          = $_POST['contents'];
        $dc_value          = $_POST['dc_value']; 
        $vechicle_no       = $_POST['vechicle_no'];
       //$cnserial = $_POST['cnser'];
       $cnote_serial = getcnote_serial($db,$cnote_no,$comp_code,$podorigin);
       $date_added        = date('Y-m-d H:i:s');
   
       function getrate($br_code,$db,$comp_code){
         $getbranch = $db->query("SELECT * FROM branch WHERE br_code='$br_code' AND stncode='$comp_code'")->fetch(PDO::FETCH_OBJ);
         if($getbranch->rate=='' OR $getbranch->rate==NULL){
           $setrate = 'RATE1';
         }else{
           $setrate = $getbranch->rate;
         }
         return $setrate;
       }
       $c_amt = $db->query("SELECT dbo.IBQamountupdate('".getrate($br_code,$db,$comp_code)."','$comp_code','$destination','$wt','$mode','$podorigin') as amount")->fetch(PDO::FETCH_OBJ);		//fetch customer zone
		$getcustzncode = $db->query("SELECT TOP (1) zncode FROM customer WHERE cust_code='RATE1' AND comp_code='$comp_code'");
		$rowzncode = $getcustzncode->fetch(PDO::FETCH_OBJ);

		$desttbverify = $db->query("SELECT * FROM destination WHERE comp_code='$comp_code' AND dest_code='$destination' OR dest_name='$destination'");
		if($desttbverify->rowCount()==0){
			$dest_verify = $db->query("SELECT * FROM pincode WHERE pincode='$destination' OR city_name='$destination' OR dest_code='$destination'"); 
			 if($dest_verify->rowCount()==0){
				$pincode  	 = $destination;
				$dest_code   = $destination;
				$zonecode  	 = $destination;
			 }
			 else{
				$rowdest_verify = $dest_verify->fetch(PDO::FETCH_OBJ);
				$pincode  	 = $rowdest_verify->pincode;
				$dest_code  	 = $rowdest_verify->dest_code;

					$getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
					$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
					$zonecode = $rowgetzonecode['zone_cash'];
			 }
		}
		else{
			$rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
			$dest_code  = $rowdestntn->dest_code;
			$pincode    = $rowdestntn->dest_code;

			$getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
			$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
			$zonecode = $rowgetzonecode['zone_cash'];
		}
		
		$checkallotted = $db->query("SELECT * FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cnote_no' AND cnote_status='Allotted'");
		if($checkallotted->rowCount()!=0){
				$cashmaster_verify = $db->query("SELECT * FROM cash_master WHERE bookcounter_code='$book_counter' AND date_added='$bdate' AND comp_code='$comp_code'");
				if($cashmaster_verify->rowCount()==0)
				{

					$insert_cashmaster = $db->query("INSERT INTO cash_master (comp_code,br_code,bookcounter_code,bookcounter_name,total_cnotes,user_name,date_added)
					VALUES ('$comp_code','$br_code','$book_counter','$book_counter_name','1','$user_name','$bdate')");
				}
				else
				{
					$update_cashmaster = $db->query("UPDATE cash_master SET total_cnotes=total_cnotes+1 WHERE bookcounter_code='$book_counter' AND date_added='$bdate' AND comp_code='$comp_code'");
				}
				//Update Billing Cash in Cnotenumbertb
				$update_cnotenumbertb = $db->query("UPDATE cnotenumber SET bilwt='$wt',bilrate='$c_amt->amount',bilmode='$mode',bildest='$destination',bilpincode='$destination',bdate='$bdate',cnote_status='Billed' WHERE cnote_number='$cnote_no' AND cnote_station='$comp_code'  AND cnote_serial='$cnote_serial'");
				//Insert into cashtb
				$checkduplicatec = $db->query("SELECT * FROM cash WHERE comp_code='$comp_code' AND cno='$cnote_no' AND cnote_serial='$cnote_serial'");
				if($checkduplicatec->rowCount()==0){
				/*Cash GST Start */ 
					$getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user_name'");
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
		
					$company = $db->query("SELECT * FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
		
				



					  //cash chellan gst calculation	
					  $gst = 0.18;
					  $gta_weight = 0.5;
                          $gtaval = (($company->gta=='Y' && empty($gstin) && ($gta_weight<=$wt) && $mode=='ST') ? '105' : '118');
					  $gstnormal_value = 100 / $gtaval;
					  $calculated_value  =   number_format((float)$amount * $gstnormal_value, 2, '.', '');
					 $charges = number_format((float)$calculated_value, 2, '.', '');
					  $total_value  =   number_format((float)$amount - $calculated_value, 2, '.', '');
	  
	  
					  $sgst_cgst  =   number_format((float)$total_value/2, 2, '.', '');
					  $sgst_ter = (empty($gstin) || substr($company->stn_gstin,0,2)==substr($gstin,0,2))?number_format((float)(($sgst_cgst)), 2, '.', ''):0.00;
		  
					  $igst_value = number_format((float)$amount - $calculated_value, 2, '.', '');
	  
					  $igst_ter = (empty($gstin) || substr($company->stn_gstin,0,2)==substr($gstin,0,2))?0.00:number_format((float)(($igst_value)), 2, '.', '');
				
					  $insertcash = "INSERT INTO cash (cc_code,bkgstaff,payment_mode,payment_reference,gst,shp_pin,consignee_pin,pod_origin,shp_name,shp_addr,shp_mob,origin,cno,pincode,dest_code,zone_code,book_counter,consignee,consignee_addr,consignee_mobile,wt,ec_wt,pcs,mode_code,amt,ramt,mf_no,br_code,comp_code,edate,tdate,user_name,date_added,status,v_wt,sgst,cgst,igst,charges,cnote_type,Dup_challan,cnote_serial,contents,dc_value,Vehicle_no) 
					  VALUES('$cc_code','$bkgstaff','$payment_mode','$payment_reference','$gstin','$shp_pin','$consignee_pin','$podorigin','$shp_name','$shp_addr','$shp_mob','$origin','$cnote_no','$pincode','$dest_code','$zonecode','$book_counter','$consignee_name','$consignee_addr','$consignee_mob','$weight','$wt','$pcs','$mode','$c_amt->amount','$amount','$mf_no','$br_code','$comp_code','$date_added','$bdate','$user_name','$date_added','Billed','$v_weight','$sgst_ter','$sgst_ter','$igst_ter','$charges','$cnote_type','Not Printed','$cnote_serial','$contents','$dc_value','$vechicle_no')";
					  $insert = $db->query($insertcash);

					  $result = $db->query("EXEC TPC_Cashdata_Push_live @cnote = '$cnote_no',@cnote_serials = '$cnote_serial',@compcode = '$comp_code',@pod = '$podorigin'");

					/*Cash GST end */ 
				}
				//add logs
				$yourbrowser   = getBrowser()['name'];
				$user_surename = get_current_user();
				$platform      = getBrowser()['platform'];   
				$remote_addr   = getBrowser()['remote_addr'];  
				$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$comp_code$cnote_no','Billing-Cash','Insert','Cnote $cnote_no has been INSERTED IN $br_code','$user_name',getdate())");

				//$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Cash Cnotenumber $comp_code$cnote_no has Billed','$yourbrowser')");
			}

			//inserting consignee
			if(!empty($consignee_mob)){
				$consigneecount = $db->query("SELECT count(*) counts FROM consignee WHERE consignee_mobile='$consignee_mob'")->fetch(PDO::FETCH_OBJ);
				if($consigneecount->counts==0){
					$db->query("INSERT INTO consignee (consignee_mobile,consignee_name,consignee_addr,consignee_pincode)
					VALUES ('$consignee_mob','$consignee_name','$consignee_addr','$consignee_pin')");
				} else {
					$db->query("UPDATE consignee SET consignee_name='$consignee_name',consignee_addr='$consignee_addr',consignee_pincode='$consignee_pin' WHERE consignee_mobile='$consignee_mob'");
				}
			}
			if(!empty($shp_mob)){
				$shp_mobcount = $db->query("SELECT count(*) counts FROM consignee WHERE consignee_mobile='$shp_mob'")->fetch(PDO::FETCH_OBJ);
				if($shp_mobcount->counts==0){
					$db->query("INSERT INTO consignee (consignee_mobile,consignee_name,consignee_addr,consignee_pincode)
					VALUES ('$shp_mob','$shp_name','$shp_addr','$shp_pin')");
				} else {
					$db->query("UPDATE consignee SET consignee_name='$shp_name',consignee_addr='$shp_addr',consignee_pincode='$shp_pin' WHERE consignee_mobile='$shp_mob'");
				}
			}
				header("Location: cashlist?success=1&cno=$cnote_no&cnotetype=".base64_encode($cnote_type)."");
			}
		
			catch(PDOException $e){
				$date_added	= date('Y-m-d H:i:s');
				$yourbrowser   = getBrowser()['name'];
				$user_surename = get_current_user();
				$platform      = getBrowser()['platform'];   
				$remote_addr   = getBrowser()['remote_addr']; 
				$user_name	= $_POST['user_name'];
				$comp_code	= $_POST['comp_code'];
				$cnote_no		= $_POST['cnote_no'];
				$error_info	= $e->errorInfo[1];
				$error_detail	= explode("]",$e->errorInfo[2]);
				$err_desc = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', END($error_detail));
	
				$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Transaction failed at Credit. ERROR_CODE:$error_info-$err_desc. CNO:$comp_code$cnote_no','$yourbrowser')"); 
	
					header("Location: cashlist?success=1");
			}
	}

	if(isset($_POST['addanotherbillingcash']))
	{
		date_default_timezone_set('Asia/Kolkata');

		function getcnote_serial($db,$cno,$comp_code,$category){
			if($category=='gl' || empty($category) || trim($category)==trim($comp_code)){
			  $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND (SELECT grp_type FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)='gl'")->fetch(PDO::FETCH_OBJ);
			}
			else{
			  $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND cnote_serial='$category'")->fetch(PDO::FETCH_OBJ);
			}
			return $serial->cnote_serial;
		  }



		$comp_code          = $_POST['comp_code']; 
		$br_code 			= $_POST['br_code'];
		$origin 			= $_POST['origin'];
		$cc_code 			= $_POST['cc_code'];
		$cc_name 			= $_POST['cc_name'];
		$consignee_name 		= $_POST['consignee_name'];
		$consignee_addr 		= $_POST['consignee_addr'];
		$consignee_mob 		= $_POST['consignee_mob'];	
		$consignee_pin 		= $_POST['consignee_pin'];	
		$book_counter		= $_POST['book_counter'];
		$payment_mode		= $_POST['payment_mode'];
		$payment_reference	= $_POST['payment_reference'];
		$book_counter_name	= $_POST['book_counter_name'];
		$pcs				= $_POST['pcs'];
		$shp_name				= $_POST['shp_name'];
		$podorigin				= $_POST['podorigin'];
		$shp_addr				= $_POST['shp_addr'];
		$shp_mob				= $_POST['shp_mob'];
		$shp_pin				= $_POST['shp_pin'];
		$gstin				= $_POST['gstin'];
		$mf_no			= $_POST['mf_no'];
		$bkgstaff			= $_POST['bkgstaff'];
		$date=date_create($_POST['bdate']);
        $bdate =  date_format($date,"Y/m/d");
		$wt 				= max($_POST['wt'],$_POST['v_wt']);
		$weight = $_POST['wt'];
		$v_weight = $_POST['v_wt'];
		$amount			= $_POST['amount'];
		$mode			= $_POST['mode'];
		$destination	     = explode('-',$_POST['destination'])[0];
		$cnote_no	   	     = $_POST['cnote_no'];
		$cnote_type     = $_POST['cnote_type'];
		$user_name          = $_POST['user_name'];
		//$cnserial            = $_POST['cnser'];
		$cnote_serial = getcnote_serial($db,$cnote_no,$comp_code,$podorigin);           
		$date_added        = date('Y-m-d H:i:s');
        $contents          = $_POST['contents'];
		$dc_value          = $_POST['dc_value'];
        $vechicle_no       = $_POST['vechicle_no'];
		$check_shipper   = $_POST['check_shipper'];
		$check_shipper2   = $_POST['check_shipper2'];

        function getrate($br_code,$db,$comp_code){
          $getbranch = $db->query("SELECT * FROM branch WHERE br_code='$br_code' AND stncode='$comp_code'")->fetch(PDO::FETCH_OBJ);
          if($getbranch->rate=='' OR $getbranch->rate==NULL){
               $setrate = 'RATE1';
          }else{
               $setrate = $getbranch->rate;
          }
          return $setrate;
     } 


     $c_amt = $db->query("SELECT dbo.IBQamountupdate('".getrate($br_code,$db,$comp_code)."','$comp_code','$destination','$wt','$mode','$podorigin') as amount")->fetch(PDO::FETCH_OBJ);
  
			//fetch customer zone
			$getcustzncode = $db->query("SELECT TOP (1) zncode FROM customer WHERE cust_code='RATE1' AND comp_code='$comp_code'");
			$rowzncode = $getcustzncode->fetch(PDO::FETCH_OBJ);

			$desttbverify = $db->query("SELECT * FROM destination WHERE comp_code='$comp_code' AND dest_code='$destination' OR dest_name='$destination'");
			if($desttbverify->rowCount()==0){
				$dest_verify = $db->query("SELECT * FROM pincode WHERE pincode='$destination' OR city_name='$destination' OR dest_code='$destination'"); 
				if($dest_verify->rowCount()==0){
					$pincode  	 = $destination;
					$dest_code  	 = $destination;
					$zonecode  	 = $destination;
				}
				else{
					$rowdest_verify = $dest_verify->fetch(PDO::FETCH_OBJ);
					$pincode  	 = $rowdest_verify->pincode;
					$dest_code  	 = $rowdest_verify->dest_code;

					$getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
					$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
					$zonecode = $rowgetzonecode['zone_cash'];
				}
			}
			else{
				$rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
				$dest_code  = $rowdestntn->dest_code;
				$pincode    = $rowdestntn->dest_code;

				$getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
				$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
				$zonecode = $rowgetzonecode['zone_cash'];
			}
		 $checkallotted = $db->query("SELECT * FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cnote_no' AND cnote_status='Allotted'");
		 if($checkallotted->rowCount()!=0){
		 $cashmaster_verify = $db->query("SELECT * FROM cash_master WHERE bookcounter_code='$book_counter' AND date_added='$bdate' AND comp_code='$comp_code'");
		 if($cashmaster_verify->rowCount()==0)
		 {
		 $insert_cashmaster = $db->query("INSERT INTO cash_master (comp_code,br_code,bookcounter_code,bookcounter_name,total_cnotes,user_name,date_added)
		 VALUES ('$comp_code','$br_code','$book_counter','$book_counter_name','1','$user_name','$bdate')");
		 }
		 else
		 {
		 $update_cashmaster = $db->query("UPDATE cash_master SET total_cnotes=total_cnotes+1 WHERE bookcounter_code='$book_counter' AND date_added='$bdate' AND comp_code='$comp_code'");
		 }
		 //Update Billing Cash in Cnotenumbertb
		 $update_cnotenumbertb = $db->query("UPDATE cnotenumber SET bilwt='$wt',bilrate='$c_amt->amount',bilmode='$mode',bildest='$destination',bilpincode='$destination',bdate='$bdate',cnote_status='Billed' WHERE cnote_number='$cnote_no' AND cnote_station='$comp_code' AND cnote_serial='$cnote_serial'");
		 //Insert into cashtb
		 $checkduplicatec = $db->query("SELECT * FROM cash WHERE comp_code='$comp_code' AND cno='$cnote_no' AND cnote_serial='$cnote_serial'");
		 if($checkduplicatec->rowCount()==0){

		 /*	cash gst start 2*/
		 $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user_name'");
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

		 $company = $db->query("SELECT * FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
        


		   //cash chellan gst calculation	
		   $gst = 0.18;
		   $gta_weight = 0.5;
		   $gtaval = (($company->gta=='Y' && empty($gstin) && ($gta_weight<=$wt) && $mode=='ST') ? '105' : '118');
		   $gstnormal_value = 100 / $gtaval;
		   $calculated_value  =   number_format((float)$amount * $gstnormal_value, 2, '.', '');
		  $charges = number_format((float)$calculated_value, 2, '.', '');
		   $total_value  =   number_format((float)$amount - $calculated_value, 2, '.', '');


		   $sgst_cgst  =   number_format((float)$total_value/2, 2, '.', '');
		   $sgst_ter = (empty($gstin) || substr($company->stn_gstin,0,2)==substr($gstin,0,2))?number_format((float)(($sgst_cgst)), 2, '.', ''):0.00;

		   $igst_value = number_format((float)$amount - $calculated_value, 2, '.', '');

		   $igst_ter = (empty($gstin) || substr($company->stn_gstin,0,2)==substr($gstin,0,2))?0.00:number_format((float)(($igst_value)), 2, '.', '');

             $insertcash = "INSERT INTO cash (cc_code,bkgstaff,payment_mode,payment_reference,gst,shp_pin,consignee_pin,pod_origin,shp_name,shp_addr,shp_mob,origin,cno,pincode,dest_code,zone_code,book_counter,consignee,consignee_addr,consignee_mobile,wt,ec_wt,pcs,mode_code,amt,ramt,mf_no,br_code,comp_code,edate,tdate,user_name,date_added,status,v_wt,sgst,cgst,igst,charges,cnote_type,Dup_challan,cnote_serial,contents,dc_value,Vehicle_no) VALUES
		   ('$cc_code','$bkgstaff','$payment_mode','$payment_reference','$gstin','$shp_pin','$consignee_pin','$podorigin','$shp_name','$shp_addr','$shp_mob','$origin','$cnote_no','$pincode','$dest_code','$zonecode','$book_counter','$consignee_name','$consignee_addr','$consignee_mob','$weight','$wt','$pcs','$mode','$c_amt->amount','$amount','$mf_no','$br_code','$comp_code','$date_added','$bdate','$user_name','$date_added','Billed','$v_weight','$sgst_ter','$sgst_ter','$igst_ter','$charges','$cnote_type','Not Printed','$cnote_serial','$contents','$dc_value','$vechicle_no')";
		   $insert = $db->query($insertcash);
		 /*cash gst end 2*/
	       $result = $db->query("EXEC TPC_Cashdata_Push_live @cnote = '$cnote_no',@cnote_serials = '$cnote_serial',@compcode = '$comp_code',@pod = '$podorigin'");

		

		 }
			//add logs
			$yourbrowser   = getBrowser()['name'];
			$user_surename = get_current_user();
			$platform      = getBrowser()['platform'];   
			$remote_addr   = getBrowser()['remote_addr'];  
			$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$comp_code$cnote_no','Billing-Cash','Insert','Cnote $cnote_no has been INSERTED IN $br_code','$user_name',getdate())");
			//$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Cash Cnotenumber $comp_code$cnote_no has Billed','$yourbrowser')");
		}
		//inserting consignee
		if(!empty($consignee_mob)){
			$consigneecount = $db->query("SELECT count(*) counts FROM consignee WHERE consignee_mobile='$consignee_mob'")->fetch(PDO::FETCH_OBJ);
			if($consigneecount->counts==0){
				$db->query("INSERT INTO consignee (consignee_mobile,consignee_name,consignee_addr,consignee_pincode)
				 VALUES ('$consignee_mob','$consignee_name','$consignee_addr','$consignee_pin')");
			} else {
				$db->query("UPDATE consignee SET consignee_name='$consignee_name',consignee_addr='$consignee_addr',consignee_pincode='$consignee_pin' WHERE consignee_mobile='$consignee_mob'");
			}	
		}
		if(!empty($shp_mob)){
			$shp_mobcount = $db->query("SELECT count(*) counts FROM consignee WHERE consignee_mobile='$shp_mob'")->fetch(PDO::FETCH_OBJ);
			if($shp_mobcount->counts==0){
				$db->query("INSERT INTO consignee (consignee_mobile,consignee_name,consignee_addr,consignee_pincode)
				 VALUES ('$shp_mob','$shp_name','$shp_addr','$shp_pin')");
			} else {
				$db->query("UPDATE consignee SET consignee_name='$shp_name',consignee_addr='$shp_addr',consignee_pincode='$shp_pin' WHERE consignee_mobile='$shp_mob'");
			}
		}
		header("Location: cash?cnote_serial=$cnote_serial&ch_shipper2=$check_shipper2&c_name=$consignee_name&c_mob=$consignee_mob&c_addr=$consignee_addr&consignee_pin=$consignee_pin&ch_shipper=$check_shipper&sh_name=$shp_name&sh_mob=$shp_mob&sh_addr=$shp_addr&sh_pin=$shp_pin&cno=$cnote_no&pincode=$consignee_pin&message=$response&cust_code=$challan&destination=$destination&podorigin=$podorigin&bc=$book_counter&bn=$book_counter_name&cc=$cc_code&cn=$cc_name&destcode=$dest_code&wt=$wt&pieces=$pcs&bdate=$bdate&cnotetype=".base64_encode($cnote_type)."");
			
	}

	if(isset($_POST['updatecredit'])){
		date_default_timezone_set('Asia/Kolkata');

		$comp_code          = $_POST['comp_code']; 
		$br_code 			= $_POST['br_code'];
		$origin 			= $_POST['origin'];
		$cc_code 			= $_POST['cc_code'];
		$consignee_name 		= $_POST['consignee_name'];
		$consignee_addr 		= $_POST['consignee_addr'];
		$consignee_mob 		= $_POST['consignee_mob'];	
		$consignee_pin 		= $_POST['consignee_pin'];	
		$book_counter		= $_POST['book_counter'];
		$payment_mode		= $_POST['payment_mode'];
		$payment_reference	= $_POST['payment_reference'];
		$book_counter_name	= $_POST['book_counter_name'];
		$pcs				= $_POST['pcs'];
		$shp_name				= $_POST['shp_name'];
		$podorigin				= $_POST['podorigin'];
		$shp_addr				= $_POST['shp_addr'];
		$shp_mob				= $_POST['shp_mob'];
		$shp_pin				= $_POST['shp_pin'];
		$gstin				= $_POST['gstin'];
		$mf_no			= $_POST['mf_no'];
		$bkgstaff			= $_POST['bkgstaff'];
		$date=date_create($_POST['bdate']);
        $bdate =  date_format($date,"Y/m/d");
		$wt 				= max($_POST['wt'],$_POST['v_wt']);
		$weight = $_POST['wt'];
		$v_weight = $_POST['v_wt'];
		$amount			= $_POST['amount'];
		$mode			= $_POST['mode'];
		$destination	     = explode('-',$_POST['destination'])[0];
		$cnote_no	   	     = $_POST['cnote_no'];
		$cnote_type     = $_POST['cnote_type'];
		$user_name          = $_POST['user_name'];
		$date_added        = date('Y-m-d H:i:s');
		$cnserial            = $_POST['cnser'];
        $contents          = $_POST['contents'];
        $dc_value          = $_POST['dc_value'];
        $vechicle_no       = $_POST['vechicle_no']; 
        function getrate($br_code,$db,$comp_code){
          $getbranch = $db->query("SELECT * FROM branch WHERE br_code='$br_code' AND stncode='$comp_code'")->fetch(PDO::FETCH_OBJ);
          if($getbranch->rate=='' OR $getbranch->rate==NULL){
            $setrate = 'RATE1';
          }else{
            $setrate = $getbranch->rate;
          }
          return $setrate;
        } 
    
        $c_amt = $db->query("SELECT dbo.IBQamountupdate('".getrate($br_code,$db,$comp_code)."','$comp_code','$destination','$wt','$mode','$podorigin') as amount")->fetch(PDO::FETCH_OBJ);
    
			//fetch customer zone
			$getcustzncode = $db->query("SELECT TOP (1) zncode FROM customer WHERE cust_code='RATE1' AND comp_code='$comp_code'");
			$rowzncode = $getcustzncode->fetch(PDO::FETCH_OBJ);

			$desttbverify = $db->query("SELECT * FROM destination WHERE comp_code='$comp_code' AND dest_code='$destination' OR dest_name='$destination'");
			if($desttbverify->rowCount()==0){
				$dest_verify = $db->query("SELECT * FROM pincode WHERE pincode='$destination' OR city_name='$destination' OR dest_code='$destination'"); 
				if($dest_verify->rowCount()==0){
					$pincode  	 = $destination;
					$dest_code  	 = $destination;
					$zonecode  	 = $destination;
				}
				else{
					$rowdest_verify = $dest_verify->fetch(PDO::FETCH_OBJ);
					$pincode  	 = $rowdest_verify->pincode;
					$dest_code  	 = $rowdest_verify->dest_code;

					$getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
					$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
					$zonecode = $rowgetzonecode['zone_cash'];
				}
			}
			else{
				$rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
				$dest_code  = $rowdestntn->dest_code;
				$pincode    = $rowdestntn->dest_code;

				$getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
				$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
				$zonecode = $rowgetzonecode['zone_cash'];
			}
		 //$checkallotted = $db->query("SELECT * FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cnote_no' AND cnote_status='Allotted'");
		// if($checkallotted->rowCount()!=0){
		// $cashmaster_verify = $db->query("SELECT * FROM cash_master WHERE bookcounter_code='$book_counter' AND date_added='$date_added' AND comp_code='$comp_code'");
		// if($cashmaster_verify->rowCount()==0)
		// {
		// $insert_cashmaster = $db->query("INSERT INTO cash_master (comp_code,br_code,bookcounter_code,bookcounter_name,total_cnotes,user_name,date_added)
		// VALUES ('$comp_code','$br_code','$book_counter','$book_counter_name','1','$user_name','$date_added')");
		// }
		//  else
		//  {
		//  $update_cashmaster = $db->query("UPDATE cash_master SET total_cnotes=total_cnotes+1 WHERE bookcounter_code='$book_counter' AND date_added='$date_added' AND comp_code='$comp_code'");
		//  }
		 //Update Billing Cash in Cnotenumbertb
		 $update_cnotenumbertb = $db->query("UPDATE cnotenumber SET bilwt='$wt',bilrate='$c_amt->amount',bilmode='$mode',bildest='$destination',bilpincode='$destination',bdate='$bdate',cnote_status='Billed' WHERE cnote_number='$cnote_no' AND cnote_station='$comp_code'  AND cnote_serial='$cnserial'");
		 //Insert into cashtb
		 //$checkduplicatec = $db->query("SELECT * FROM cash WHERE comp_code='$comp_code' AND cno='$cnote_no'");
		 //if($checkduplicatec->rowCount()==0){
		/*cash gst start 3 */

		$getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user_name'");
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

		$company = $db->query("SELECT * FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);



		//cash chellan gst calculation	
		$gst = 0.18;
		$gta_weight = 0.5;
		$gtaval = (($company->gta=='Y' && empty($gstin) && ($gta_weight<=$wt) && $mode=='ST') ? '105' : '118');
		$gstnormal_value = 100 / $gtaval;
		$calculated_value  =   number_format((float)$amount * $gstnormal_value, 2, '.', '');
	   $charges = number_format((float)$calculated_value, 2, '.', '');
		$total_value  =   number_format((float)$amount - $calculated_value, 2, '.', '');


		$sgst_cgst  =   number_format((float)$total_value/2, 2, '.', '');
		$sgst_ter = (empty($gstin) || substr($company->stn_gstin,0,2)==substr($gstin,0,2))?number_format((float)(($sgst_cgst)), 2, '.', ''):0.00;

		$igst_value = number_format((float)$amount - $calculated_value, 2, '.', '');

		$igst_ter = (empty($gstin) || substr($company->stn_gstin,0,2)==substr($gstin,0,2))?0.00:number_format((float)(($igst_value)), 2, '.', '');



	 $updatecash = "UPDATE cash SET 
	 gst='$gstin',
	 shp_pin='$shp_pin',
	 cc_code='$cc_code',
	 consignee_pin='$consignee_pin',
	 shp_name='$shp_name',
	 shp_addr='$shp_addr',
	 shp_mob='$shp_mob',
	 pincode='$pincode',
	 dest_code='$dest_code',
	 zone_code='$zonecode',
	 book_counter='$book_counter',
	 consignee='$consignee_name',
	 consignee_addr='$consignee_addr',
	 consignee_mobile='$consignee_mob',
	 bkgstaff='$bkgstaff',
	 payment_mode='$payment_mode',
	 payment_reference='$payment_reference',
	 wt='$weight',
	 ec_wt='$wt',
	 pcs='$pcs',
	 mode_code='$mode',
	 amt='$c_amt->amount',
	 ramt='$amount',
	 mf_no='$mf_no',
	 date_modified='$date_added',
	 v_wt='$v_weight',
	 sgst='$sgst_ter',
	 cgst='$sgst_ter',
	 igst='$igst_ter',
	 charges='$charges',
	 cnote_serial='$cnserial',
     contents='$contents',
     dc_value='$dc_value',
	 tdate='$bdate' WHERE cno='$cnote_no' AND comp_code='$comp_code' AND cnote_serial='$cnserial'";
	 /*cash gst end 3 */
		 
		 $update = $db->query($updatecash);
		// }
			//add logs
			$yourbrowser   = getBrowser()['name'];
			$user_surename = get_current_user();
			$platform      = getBrowser()['platform'];   
			$remote_addr   = getBrowser()['remote_addr'];  
			$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$comp_code$cnote_no','Billing-Cash','Update','Cnote $cnote_no has been Updated in $br_code','$user_name',getdate())");

			//$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Cash Cnotenumber $comp_code$cnote_no has Updated','$yourbrowser')");
		//}

		//inserting consignee
		if(!empty($consignee_mob)){
			$consigneecount = $db->query("SELECT count(*) counts FROM consignee WHERE consignee_mobile='$consignee_mob'")->fetch(PDO::FETCH_OBJ);
			if($consigneecount->counts==0){
				$db->query("INSERT INTO consignee (consignee_mobile,consignee_name,consignee_addr,consignee_pincode)
				 VALUES ('$consignee_mob','$consignee_name','$consignee_addr','$consignee_pin')");
			} else {
				$db->query("UPDATE consignee SET consignee_name='$consignee_name',consignee_addr='$consignee_addr',consignee_pincode='$consignee_pin' WHERE consignee_mobile='$consignee_mob'");
			}
		}
		if(!empty($shp_mob)){
			$shp_mobcount = $db->query("SELECT count(*) counts FROM consignee WHERE consignee_mobile='$shp_mob'")->fetch(PDO::FETCH_OBJ);
			if($shp_mobcount->counts==0){
				$db->query("INSERT INTO consignee (consignee_mobile,consignee_name,consignee_addr,consignee_pincode)
				 VALUES ('$shp_mob','$shp_name','$shp_addr','$shp_pin')");
			} else {
				$db->query("UPDATE consignee SET consignee_name='$shp_name',consignee_addr='$shp_addr',consignee_pincode='$shp_pin' WHERE consignee_mobile='$shp_mob'");
			}
		}
			header("Location: cash");
			
	}


	if(isset($_POST['action']) && $_POST['action']=='p_controller'){
		$usr_name = $_POST['usr_name'];
		$usr_tb="SELECT * FROM users WHERE user_name='$usr_name'";
		$user_data = $db->query($usr_tb);
		$user_row = $user_data->fetch(PDO::FETCH_ASSOC);

		$user = $user_row['last_compcode'];
		if(!empty($user)){
			$companies = $db->query("SELECT stncode,stnname FROM stncode WHERE stncode='$user'");
			$row_companies = $companies->fetch(PDO::FETCH_ASSOC);
			echo $user.",".$row_companies['stnname'];
			exit();
		}
		else{
			$userstations = $user_row['stncodes'];
			$stations = explode(',',$userstations);
			$station0 = $stations[0];
			$companies = $db->query("SELECT stncode,stnname FROM stncode WHERE stncode='$station0'");
			$row_companies = $companies->fetch(PDO::FETCH_ASSOC);
			echo $stations[0].",".$row_companies['stnname'];
			exit();
		}
       }


	//delete credit cnotes
	if(isset($_POST['delcash_ecompany'])){
		$delcash_ecompany = $_POST['delcash_ecompany'];
		$delcash_ecnote = $_POST['delcash_ecnote'];
		$delcash_serial = $_POST['serial'];
		$delcash_euser = $_POST['delcash_euser'];


		//select book count
	    $getccount = $db->query("SELECT status,book_counter,CAST(date_added AS DATE) AS date FROM cash WHERE cno='$delcash_ecnote' AND comp_code='$delcash_ecompany' AND cnote_serial='$delcash_serial'")->fetch(PDO::FETCH_OBJ);
		$book_counter = $getccount->book_counter;
		$date = $getccount->date;
        if($getccount->status=='Billed'){
			$db->query("DELETE FROM cash WHERE cno='$delcash_ecnote' AND comp_code='$delcash_ecompany' AND cnote_serial='$delcash_serial'");
			//update query in book count
			$db->query("UPDATE cash_master SET total_cnotes=total_cnotes-1 WHERE bookcounter_code='$book_counter' AND date_added='$date'");
	
		$db->query("UPDATE cnotenumber SET bilwt =null,bilpincode=null,bildest=null,bilrate=null,bdate=null,bilmode=null,cnote_status='Allotted' WHERE cnote_number='$delcash_ecnote' AND  cnote_station='$delcash_ecompany' AND cnote_serial='$delcash_serial'");
	
			//store audit log
			$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$delcash_ecompany$delcash_ecnote','Billing-Cash','Delete','Cnote $delcash_ecnote has been Deleted in $delcash_ecompany','$delcash_euser',getdate())");
		}else{
			}
		}
    if(isset($_POST['action']) && $_POST['action'] == 'cashllish'){
			echo select10rows($db,$_POST['dtbquery'],$_POST['dtbpageno'],$_POST['dtbcolumns']);
			exit();
		  }
		
			  function select10rows($db,$dtbquery,$dtbpageno,$dtbcolumn2){
				  $dtbcolumns = explode(",",$dtbcolumn2);
				  $t10query = $db->query($dtbquery." OFFSET ".$dtbpageno." ROWS  FETCH NEXT 10 ROWS ONLY");
				  $datum = array();
				  $index = 0;
				  while($t10row=$t10query->fetch(PDO::FETCH_OBJ)){$index++;
					  $data = array();
						foreach($dtbcolumns as $dtbcolumn){
					  //console_log($dtbcolumn);
						  $data[$dtbcolumn] = $t10row->$dtbcolumn;
						}
					$datum[$index]=$data;
				  }
				  return json_encode($datum);
			  }
		//logs function
		function getBrowser()
		{
		    $u_agent = $_SERVER['HTTP_USER_AGENT'];
		    $bname = 'Unknown';
		    $platform = 'Unknown';
		    $version= "";
		
		    //First get the platform?
		    if (preg_match('/linux/i', $u_agent)) {
			   $platform = 'linux';
		    }
		    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			   $platform = 'mac';
		    }
		    elseif (preg_match('/windows|win32/i', $u_agent)) {
			   $platform = 'windows';
		    }
		
		    // Next get the name of the useragent yes seperately and for good reason
		    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		    {
			   $bname = 'Internet Explorer';
			   $ub = "MSIE";
		    }
		    elseif(preg_match('/Firefox/i',$u_agent))
		    {
			   $bname = 'Mozilla Firefox';
			   $ub = "Firefox";
		    }
		    elseif(preg_match('/Chrome/i',$u_agent))
		    {
			   $bname = 'Google Chrome';
			   $ub = "Chrome";
		    }
		    elseif(preg_match('/Safari/i',$u_agent))
		    {
			   $bname = 'Apple Safari';
			   $ub = "Safari";
		    }
		    elseif(preg_match('/Opera/i',$u_agent))
		    {
			   $bname = 'Opera';
			   $ub = "Opera";
		    }
		    elseif(preg_match('/Netscape/i',$u_agent))
		    {
			   $bname = 'Netscape';
			   $ub = "Netscape";
		    }
		    
		    if (!empty($_SERVER['HTTP_CLIENT_IP']))   
			   {
				  $remote_addr = $_SERVER['HTTP_CLIENT_IP'];
			   }
			   //whether ip is from proxy
			   elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
			   {
				  $remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
			   }
			   //whether ip is from remote address
			   else
			   {
				  $remote_addr = $_SERVER['REMOTE_ADDR'];
			   }
		    // finally get the correct version number
		    $known = array('Version', $ub, 'other');
		    $pattern = '#(?<browser>' . join('|', $known) .
		    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		    if (!preg_match_all($pattern, $u_agent, $matches)) {
			   // we have no matching number just continue
		    }
		
		    // see how many we have
		    $i = count($matches['browser']);
		    if ($i != 1) {
			   //we will have two since we are not using 'other' argument yet
			   //see if version is before or after the name
			   if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				  $version= $matches['version'][0];
			   }
			   else {
				  $version= $matches['version'][1];
			   }
		    }
		    else {
			   $version= $matches['version'][0];
		    }
		
		    // check if we have a number
		    if ($version==null || $version=="") {$version="?";}
		
		    return array(
			   'userAgent' => $u_agent,
			   'name'      => $bname,
			   'version'   => $version,
			   'platform'  => $platform,
			   'pattern'    => $pattern,
			   'remote_addr' => $remote_addr
		    );
		}
		if(isset($_POST['action']) && $_POST['action'] =='check_denomination'){
			$current_bill_date  = $_POST['current_bill_date'];
			$br_code  = $_POST['br_code'];
			$stncode  = $_POST['stncode'];
			$get_denomination_list = $db->query("SELECT count(col_date) as date FROM denomination WHERE comp_code='$stncode' AND br_code='$br_code' AND col_date='$current_bill_date'")->fetch(PDO::FETCH_OBJ);
			echo json_encode(array($get_denomination_list->date));
			exit();
		 }
		//end logs
        if(isset($_POST['action']) && $_POST['action'] =='cc_total_amount'){
			$cc_code  = $_POST['cc_code'];
			$br_code  = $_POST['br_code'];
			$stncode  = $_POST['stncode'];
			$bk_date = $_POST['bk_date'];
		
			 $cc_total_amount = $db->query("SELECT SUM(ramt) AS amount FROM cash WHERE comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' AND tdate='$bk_date' AND cc_status is NULL AND ccinvno is NULL")->fetch(PDO::FETCH_OBJ);
			 echo json_encode(array(number_format($cc_total_amount->amount,2)));
			 exit();
		 }