<?php 

/* File name   : TPC - Credit Controller
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

class credit extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - credit';
		$this->view->render('billing/credit');
	}
}
date_default_timezone_set('Asia/Kolkata');

if(isset($_POST['addbillingcredit']))
	{
		try
		{
		savecredit($db);
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

				header("Location: creditlist?success=1");
		}
	}

	function savecredit($db)
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
			$dup          = 'Not Printed';
			$comp_code    = $_POST['comp_code'];
			$br_code 			= $_POST['br_code'];
			$origin 			= $_POST['origin'];
			$podorigin 		= $_POST['podorigin'];
			$pcs				= $_POST['pcs'];
			$mf_no			= $_POST['mf_no'];
			$bkgstaff			= $_POST['bkgstaff'];
			$cust_code		= $_POST['cust_code'];
			$cust_name		= $_POST['cust_name'];
			$consignee		= $_POST['consignee'];
			$consignee_mob		= $_POST['consignee_mob'];
			$consignee_addr		= $_POST['consignee_addr'];
			$consignee_pin		= $_POST['consignee_pin'];
			$wt 				= max($_POST['wt'],$_POST['v_wt']);
      		$weight = $_POST['wt'];
			$v_weight = $_POST['v_wt'];
			$amount			= $_POST['amount'];
			$cnote_type		= $_POST['cnote_type'];
			//$cnote_serial			= $_POST['cnote_serial'];
			$packing_charges			= $_POST['packing_charges'];
			$insurance_charges			= $_POST['insurance_charges'];
			$other_charges		= $_POST['other_charges'];
			$mode			= $_POST['mode'];
			$destination	     = explode('-',$_POST['destination'])[0];
			$cnote_no	   	     = $_POST['cnote_no'];
			$user_name          = $_POST['user_name'];       
			$date_added         = date('Y-m-d H:i:s');
			$date		=	date_create($_POST['bdate']);
			$bdate =  date_format($date,"Y/m/d");
			$edate              = date('Y-m-d H:i:s');
      		$contents          = $_POST['contents'];
      		$dc_value          = $_POST['dc_value']; 
	
			//GET Flag Value
			$getflag = $db->query("SELECT push_to_website FROM customer WHERE cust_code='$cust_code'");
			$getflagvalue	 = $getflag->fetch(PDO::FETCH_ASSOC);
			$flag		 = $getflagvalue['push_to_website'];
      $cnote_serial	= getcnote_serial($db,$cnote_no,$comp_code,$podorigin);

	  

			$actuel_amt = $db->query("SELECT dbo.KUR_Credit_Rate('$cust_code','$comp_code','$destination','$wt','$mode','$podorigin') as amount")->fetch(PDO::FETCH_OBJ);
			if($actuel_amt->amount<=0){
				header("Location: creditlist?unsuccess=4");
			}
			else{

					if($podorigin=='gl'){
						$pod_condition = "AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD'";
						$pod_origin = $comp_code;
					}
					else{
						$pod_condition = "AND cnote_serial='$podorigin'";
						$pod_origin = $podorigin;
					}
					//fetch customer zone
					$getcustzncode = $db->query("SELECT zncode FROM customer  WHERE cust_code='$cust_code' AND comp_code='$comp_code'");
					$rowzncode = $getcustzncode->fetch(PDO::FETCH_OBJ);

					$desttbverify = $db->query("SELECT * FROM destination  WHERE comp_code='$comp_code' AND (dest_code='$destination' OR dest_name='$destination')");
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

								if($rowzncode->zncode=='A'){
								$getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_a'];
								}
								if($rowzncode->zncode=='B' || empty($rowzncode->zncode)){
									$getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_b'];
								}
								if($rowzncode->zncode=='C'){
								$getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_c'];
								}
						 }
					}
					else{
						$rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
						$dest_code  = $rowdestntn->dest_code;
						$pincode    = $rowdestntn->dest_code;

							if($rowzncode->zncode=='A'){
							$getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_a'];
							}
							if($rowzncode->zncode=='B' || empty($rowzncode->zncode)){
								$getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_b'];
							}
							if($rowzncode->zncode=='C'){
							$getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_c'];
							}
					}
					
					$checkallotted = $db->query("SELECT * FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_status='Allotted' AND cnote_number='$cnote_no'");
					if($checkallotted->rowCount()!=0){
						$creditmaster_verify = $db->query("SELECT * FROM credit_master WHERE cust_code='$cust_code' AND date_added='$bdate'");
						//echo json_encode($creditmaster_verify);
			
						if($creditmaster_verify->rowCount()==0)
						{
							$db->query("INSERT INTO credit_master (comp_code,br_code,cust_code,cust_name,total_cnotes,user_name,date_added)
							VALUES ('$comp_code','$br_code','$cust_code','$cust_name','1','$user_name','$bdate')");
						}
						else
						{
							$db->query("UPDATE credit_master SET total_cnotes=total_cnotes+1 WHERE cust_code='$cust_code' AND date_added='$bdate'");
						}
						$actual_charge = ((substr($zonecode,-3)=='100')?$amount:$actuel_amt->amount)+$packing_charges+$insurance_charges+$other_charges;

						$checkduplicate = $db->query("SELECT * FROM credit WHERE comp_code='$comp_code' AND cno='$cnote_no' AND cnote_serial='$cnote_serial'");
						if($checkduplicate->rowCount()==0){
							$db->query("EXEC insert_credit 
							@origin ='$origin',
							@podorigin ='$pod_origin',
							@cno ='$cnote_no',
							@cnote_serial ='$cnote_serial',
							@pincode ='$pincode',
							@dest_code ='$dest_code',
							@zone_code ='$zonecode',
							@cust_code ='$cust_code',
							@consignee ='$consignee',
							@consignee_mobile ='$consignee_mob',
							@consignee_addr ='$consignee_addr',
							@consignee_pin ='$consignee_pin',
							@wt ='$weight',
              				@v_weight ='$v_weight',
							@pcs ='$pcs',
							@mode_code ='$mode',
							@amt ='$actual_charge',
							@packing_charges ='$packing_charges',
							@insurance_charges ='$insurance_charges',
							@other_charges ='$other_charges',
							@mf_no ='$mf_no',
							@bkgstaff ='$bkgstaff',
							@br_code ='$br_code',
							@comp_code ='$comp_code',
							@flag ='1',
							@user_name ='$user_name',
							@date_added ='$date_added',
							@bdate  ='$bdate',
							@tdate ='$bdate',
							@edate ='$edate',
							@cnote_type = '$cnote_type',
							@Dup_challan = '$dup',
              				@contents = '$contents',
              				@dc_value = '$dc_value',
							@status = 'Billed'");
						}

						//inserting consignee
						if(!empty($consignee_mob)){
							$consigneecount = $db->query("SELECT count(*) counts FROM consignee WHERE consignee_mobile='$consignee_mob'")->fetch(PDO::FETCH_OBJ);
							if($consigneecount->counts==0){
								$db->query("INSERT INTO consignee (consignee_mobile,consignee_name,consignee_addr,consignee_pincode)
								VALUES ('$consignee_mob','$consignee','$consignee_addr','$consignee_pin')");
							}
							else{
								$db->query("UPDATE consignee set consignee_name='$consignee',consignee_addr='$consignee_addr',consignee_pincode='$consignee_pin' WHERE consignee_mobile='$consignee_mob'");
							}
						}
						//add logs
						$yourbrowser   = getBrowser()['name'];
						$user_surename = get_current_user();
						$platform      = getBrowser()['platform'];   
						$remote_addr   = getBrowser()['remote_addr'];  

						//$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Credit Cnotenumber $comp_code$cnote_no has Billed','$yourbrowser')");
           
						$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$comp_code$cnote_no','Billing-Credit','Insert','$comp_code$cnote_no has been Billed','$user_name',getdate())");

				}
					header("Location: creditlist?cust_code=$cust_code&cnotenumber=$cnote_no&bdate=$bdate&podorigin=$podorigin&cnotetype=".base64_encode($cnote_type)."");
		}
	}

		if(isset($_POST['addanotherbillingcredit']))
		{
			try{
			saveancredit($db);
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
				$cust_code	= $_POST['cust_code'];
				$podorigin	= $_POST['podorigin'];
                $cnote_type= $_POST['cnote_type'];
				$bdate		= $_POST['bdate'];
				$error_info	= $e->errorInfo[1];
				$error_detail	= explode("]",$e->errorInfo[2]);
				$err_desc = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', END($error_detail));

				$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Transaction failed at Credit. ERROR_CODE:$error_info-$err_desc. CNO:$comp_code$cnote_no','$yourbrowser')"); 

header("Location: credit?cust_code=$cust_code&cnotetype=$cnote_type&cnotenumber=$cnote_no&bdate=$bdate&podorigin=$podorigin");
			}
		}

		function saveancredit($db)
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
			$dup  = 'Not Printed';
			$comp_code          = $_POST['comp_code'];
			$br_code 			= $_POST['br_code'];
      		$br_name            = $_POST['br_name'];
			$origin 			= $_POST['origin'];
			$podorigin 		= $_POST['podorigin'];
			$pcs				= $_POST['pcs'];
			$mf_no			= $_POST['mf_no'];
			$bkgstaff			= $_POST['bkgstaff'];
			$cust_code		= $_POST['cust_code'];
			$cnote_type		= $_POST['cnote_type'];
			$cust_name		= $_POST['cust_name'];
			$consignee		= $_POST['consignee'];
			$consignee_mob		= $_POST['consignee_mob'];
			$consignee_addr		= $_POST['consignee_addr'];
			$consignee_pin		= $_POST['consignee_pin'];
			$wt 				= max($_POST['wt'],$_POST['v_wt']);
      		$weight = $_POST['wt'];
			$v_weight = $_POST['v_wt'];
			$amount			= $_POST['amount'];
			//$cnote_serial			= $_POST['cnote_serial'];
			$packing_charges			= $_POST['packing_charges'];
			$insurance_charges			= $_POST['insurance_charges'];
			$other_charges		= $_POST['other_charges'];

			$mode			= $_POST['mode'];
			$destination	     = explode('-',$_POST['destination'])[0];
			$cnote_no	   	     = $_POST['cnote_no'];
			$user_name          = $_POST['user_name'];       
			$date_added         = date('Y-m-d H:i:s');
            $date  =date_create($_POST['bdate']);
			$bdate =  date_format($date,"Y/m/d");
			$edate              = date('Y-m-d H:i:s');
            $cnote_serial= getcnote_serial($db,$cnote_no,$comp_code,$podorigin);
            $cnote_type= $_POST['cnote_type'];
            $contents          = $_POST['contents'];
            $dc_value          = $_POST['dc_value'];
	        $destination1          = $_POST['destination1'];
            //$ch_consignee
			$ch_consignee= $_POST['check_consignee'];
			//GET Flag Value
			$getflag = $db->query("SELECT push_to_website FROM customer WHERE cust_code='$cust_code'");
			$getflagvalue	 = $getflag->fetch(PDO::FETCH_ASSOC);
			$flag			 = $getflagvalue['push_to_website'];
	
			
			$actuel_amt = $db->query("SELECT dbo.KUR_Credit_Rate('$cust_code','$comp_code','$destination','$wt','$mode','$podorigin') as amount")->fetch(PDO::FETCH_OBJ);
			if($actuel_amt->amount<=0){
				header("Location: creditlist?unsuccess=4");
				
			}
			else{
					if($podorigin=='gl'){
						$pod_condition = "AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD'";
						$pod_origin = $comp_code;
					}
					else{
						$pod_condition = "AND cnote_serial='$podorigin'";
						$pod_origin = $podorigin;
					}

					//fetch customer zone
					$getcustzncode = $db->query("SELECT zncode FROM customer WHERE cust_code='$cust_code' AND comp_code='$comp_code'");
					$rowzncode = $getcustzncode->fetch(PDO::FETCH_OBJ);

					$desttbverify = $db->query("SELECT * FROM destination  WHERE comp_code='$comp_code' AND (dest_code='$destination' OR dest_name='$destination')");
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

								if($rowzncode->zncode=='A'){
								$getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_a'];
								}
								if($rowzncode->zncode=='B' || empty($rowzncode->zncode)){
									$getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_b'];
								}
								if($rowzncode->zncode=='C'){
								$getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_c'];
								}
						 }
					}
					else{
						$rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
						$dest_code  = $rowdestntn->dest_code;
						$pincode    = $rowdestntn->dest_code;

							if($rowzncode->zncode=='A'){
							$getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_a'];
							}
							if($rowzncode->zncode=='B' || empty($rowzncode->zncode)){
								$getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_b'];
							}
							if($rowzncode->zncode=='C'){
							$getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_c'];
							}
					}

					$checkallotted = $db->query("SELECT * FROM cnotenumber  WHERE cnote_station='$comp_code' AND cnote_status='Allotted' AND cnote_number='$cnote_no'");
					if($checkallotted->rowCount()!=0){
					$creditmaster_verify = $db->query("SELECT * FROM credit_master WHERE cust_code='$cust_code' AND date_added='$bdate'");
					//echo json_encode($creditmaster_verify);
		
					if($creditmaster_verify->rowCount()==0)
					{
						$db->query("INSERT INTO credit_master (comp_code,br_code,cust_code,cust_name,total_cnotes,user_name,date_added)
						VALUES ('$comp_code','$br_code','$cust_code','$cust_name','1','$user_name','$bdate')");
					}
					else
					{
						$db->query("UPDATE credit_master SET total_cnotes=total_cnotes+1 WHERE cust_code='$cust_code' AND date_added='$bdate'");
					}
						
					$actual_charge = ((substr($zonecode,-3)=='100')?$amount:$actuel_amt->amount)+$packing_charges+$insurance_charges+$other_charges;
          			$select_branch = $db->query("SELECT virtual_edit FROM branch WHERE br_code='$br_code' AND br_name='$br_name' AND stncode='$comp_code'")->fetch(PDO::FETCH_OBJ);
					$virutaledit = $select_branch->virtual_edit;

				

						//Insert into credit 
						$checkduplicate = $db->query("SELECT * FROM credit  WHERE comp_code='$comp_code' AND cno='$cnote_no' AND cnote_serial='$cnote_serial'");
						if($checkduplicate->rowCount()==0){
							$db->query("EXEC insert_credit 
							@origin ='$origin',
							@podorigin ='$pod_origin',
							@cno ='$cnote_no',
							@cnote_serial ='$cnote_serial',
							@pincode ='$pincode',
							@dest_code ='".strtoupper($dest_code)."',
							@zone_code ='$zonecode',
							@cust_code ='$cust_code',
							@consignee ='$consignee',
							@consignee_mobile ='$consignee_mob',
							@consignee_addr ='$consignee_addr',
							@consignee_pin ='$consignee_pin',
              				@wt ='$weight',
							@v_weight ='$v_weight',
							@pcs ='$pcs',
							@mode_code ='$mode',
							@amt ='$actual_charge',
							@packing_charges ='$packing_charges',
							@insurance_charges ='$insurance_charges',
							@other_charges ='$other_charges',
							@mf_no ='$mf_no',
							@bkgstaff ='$bkgstaff',
							@br_code ='$br_code',
							@comp_code ='$comp_code',
							@flag ='$flag',
							@user_name ='$user_name',
							@date_added ='$date_added',
							@bdate  ='$bdate',
							@tdate ='$bdate',
							@edate ='$edate',
							@cnote_type = '$cnote_type',
							@Dup_challan = '$dup',
              				@contents = '$contents',
                            @dc_value = '$dc_value', 
							@status = 'Billed',
							@destination1 = '$destination1'
							");
						}

						

						
						//inserting consignee
						if(!empty($consignee_mob)){
							$consigneecount = $db->query("SELECT count(*) counts FROM consignee WHERE consignee_mobile='$consignee_mob'")->fetch(PDO::FETCH_OBJ);
							if($consigneecount->counts==0){
								$db->query("INSERT INTO consignee (consignee_mobile,consignee_name,consignee_addr,consignee_pincode)
								VALUES ('$consignee_mob','$consignee','$consignee_addr','$consignee_pin')");
							}
							else{
								$db->query("UPDATE consignee set consignee_name='$consignee',consignee_addr='$consignee_addr',consignee_pincode='$consignee_pin' WHERE consignee_mobile='$consignee_mob'");
							}
						}
						//add logs
						$yourbrowser   = getBrowser()['name'];
						$user_surename = get_current_user();
						$platform      = getBrowser()['platform'];   
						$remote_addr   = getBrowser()['remote_addr'];  

						//$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Credit Cnotenumber $comp_code$cnote_no has Billed','$yourbrowser')");
           
						$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$comp_code$cnote_no','Billing-Credit','Insert','$comp_code$cnote_no has been Billed','$user_name',getdate())");

				}
				

						// header("Location:credit?ch_consignee=$ch_consignee&con_name=$consignee&con_addr=$consignee_addr&con_mobile=$consignee_mob&con_pin=$consignee_pin&cust_code=$cust_code&destination=$dest_code&virtualedit=$virutaledit&cnotetype=$cnote_type&cnotenumber=$cnote_no&bdate=$bdate&podorigin=$podorigin&cnotetype=".base64_encode($cnote_type)."");
						

					
					}
				}

            if(isset($_POST['updatecredit']))
			{
			   updatecredit($db);
			}

			function updatecredit($db){
				date_default_timezone_set('Asia/Kolkata');
				
				$dup  = 'Not Printed';
				$cnote_type = $_POST['cnote_type'];
				$comp_code          = $_POST['comp_code'];
				$br_code 			= $_POST['br_code'];
				$origin 			= $_POST['origin'];
				$podorigin 		= $_POST['podorigin'];
				$pcs				= $_POST['pcs'];
				$mf_no			= $_POST['mf_no'];
				$bkgstaff			= $_POST['bkgstaff'];
				$cust_code		= $_POST['cust_code'];
				$cnote_serial			= $_POST['cnote_serial'];
				$cust_name		= $_POST['cust_name'];
				$consignee		= $_POST['consignee'];
				$consignee_mob		= $_POST['consignee_mob'];
				$consignee_addr		= $_POST['consignee_addr'];
				$consignee_pin		= $_POST['consignee_pin'];
				$wt 				= max($_POST['wt'],$_POST['v_wt']);
       			$weight = $_POST['wt'];
		    	$v_weight = $_POST['v_wt'];
				$amount			= $_POST['amount'];
				$packing_charges			= $_POST['packing_charges'];
				$insurance_charges			= $_POST['insurance_charges'];
				$other_charges		= $_POST['other_charges'];
				$mode			= $_POST['mode'];
				$destination	     = explode('-',$_POST['destination'])[0];
				$cnote_no	   	     = $_POST['cnote_no'];
				$user_name          = $_POST['user_name'];       
				$date_added         = date('Y-m-d H:i:s');
				$date=date_create($_POST['bdate']);
				$bdate =  date_format($date,"Y/m/d");
        		$contents          = $_POST['contents'];
         		$dc_value          = $_POST['dc_value'];
				// $edate              = date('Y-m-d H:i:s');

					if($podorigin=='gl'){
						$pod_origin = $comp_code;
					}

					else{
						$pod_origin = $podorigin;
					}

					$actuel_amt = $db->query("SELECT dbo.KUR_Credit_Rate('$cust_code','$comp_code','$destination','$wt','$mode','$podorigin') as amount")->fetch(PDO::FETCH_OBJ);
					if($actuel_amt->amount<=0){
						header("Location: creditlist?unsuccess=4");
						
					}
					
					//fetch customer zone
					$getcustzncode = $db->query("SELECT zncode FROM customer WHERE cust_code='$cust_code' AND comp_code='$comp_code'");
					$rowzncode = $getcustzncode->fetch(PDO::FETCH_OBJ);

					$desttbverify = $db->query("SELECT * FROM destination WHERE comp_code='$comp_code' AND (dest_code='$destination' OR dest_name='$destination')");
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

								if($rowzncode->zncode=='A'){
								$getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_a'];
								}
								if($rowzncode->zncode=='B' || empty($rowzncode->zncode)){
									$getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_b'];
								}
								if($rowzncode->zncode=='C'){
								$getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
								$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
								$zonecode = $rowgetzonecode['zonecode_c'];
								}
						 }
					}
					else{
						$rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
						$dest_code  = $rowdestntn->dest_code;
						$pincode    = $rowdestntn->dest_code;

							if($rowzncode->zncode=='A'){
							$getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_a'];
							}
							if($rowzncode->zncode=='B' || empty($rowzncode->zncode)){
								$getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_b'];
							}
							if($rowzncode->zncode=='C'){
							$getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$origin' AND dest_code='$dest_code'");
							$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
							$zonecode = $rowgetzonecode['zonecode_c'];
							}
					}

					$actual_charge = ((substr($zonecode,-3)=='100')?$amount:$actuel_amt->amount)+$packing_charges+$insurance_charges+$other_charges;
					
				//update credit
				$db->query("UPDATE credit SET 
				consignee_addr='$consignee_addr',
				consignee_mobile='$consignee_mob',
				consignee_pin='$consignee_pin',
				consignee='$consignee',
				bkgstaff='$bkgstaff',
				br_code='$br_code',
				cust_code='$cust_code',
				wt='$weight',ec_wt='$wt',
				pcs='$pcs',tdate='$bdate',
				mode_code='$mode',
				amt ='$actual_charge',
				packing_charges='$packing_charges',
				insurance_charges='$insurance_charges',
				other_charges='$other_charges',
				dest_code='$destination',
				zone_code='$zonecode',
				pincode='$pincode',
				podorigin='$pod_origin',
       			 mf_no='$mf_no',v_wt='$v_weight',contents='$contents',dc_value='$dc_value' WHERE cno='$cnote_no' AND comp_code='$comp_code' AND cnote_serial='$cnote_serial'");

				//update bainbound
				$db->query("UPDATE bainbound SET br_code='$br_code',bacode='$cust_code',wt='$wt',ec_wt='$wt',pcs='$pcs',tdate='$bdate',mode_code='$mode',amt='$actual_charge',dest_code='$destination',zone_code='$zonecode',pincode='$pincode',podorigin='$pod_origin',mf_no='$mf_no' WHERE cno='$cnote_no' AND comp_code='$comp_code' AND cnote_serial='$cnote_serial'");
				
				//update cnotenumber
				$db->query("UPDATE cnotenumber SET br_code='$br_code',cust_code='$cust_code',bilwt='$wt',bilmode='$mode',bdate='$bdate',bilrate='$actual_charge',bildest='$destination',bilzone='$zonecode',bilpincode='$pincode' WHERE cnote_number='$cnote_no' AND cnote_station='$comp_code' AND cnote_serial='$cnote_serial'");
					
				//add logs
					$yourbrowser   = getBrowser()['name'];
					$user_surename = get_current_user();
					$platform      = getBrowser()['platform'];   
					$remote_addr   = getBrowser()['remote_addr'];  

					//$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Credit Cnotenumber $comp_code$cnote_no has Updated','$yourbrowser')");
					
					$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$comp_code$cnote_no','Billing-Credit','Update','$comp_code$cnote_no has been Updated','$user_name',getdate())");
			}


		//delete credit cnotes
		if(isset($_POST['delcredit_ecompany'])){
			$delcredit_ecompany = $_POST['delcredit_ecompany'];
			$delcredit_ecnote = $_POST['delcredit_ecnote'];
			$delcredit_euser = $_POST['delcredit_euser'];
      		$serial  = $_POST['delcredit_serial'];
			$selectcredit = $db->query("SELECT cust_code,CAST(date_added AS DATE) AS date FROM credit WHERE cno='$delcredit_ecnote' AND comp_code='$delcredit_ecompany' AND cnote_serial='$serial'")->fetch(PDO::FETCH_OBJ);
			$customer_code = $selectcredit->cust_code;
			$date = $selectcredit->date;
			$db->query("DELETE FROM credit WHERE cno='$delcredit_ecnote' AND comp_code='$delcredit_ecompany' AND cnote_serial='$serial'");
			$db->query("UPDATE credit_master SET total_cnotes=total_cnotes-1 WHERE cust_code='$customer_code' AND date_added='$date'");
			$db->query("UPDATE creditbulkuploadbatch SET status='Deleted' WHERE cno='$delcredit_ecnote' AND comp_code='$delcredit_ecompany'");//delete from bulkupload if available
			$db->query("UPDATE cnotenumber SET bilwt =null,bildest=null,bilpincode=null,bilrate=null,bdate=null,bilmode=null,cnote_status='Allotted' WHERE cnote_number='$delcredit_ecnote' AND cnote_station='$delcredit_ecompany' AND  cnote_serial='$serial'");

			//store audit log
			$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Billing','Credit','Deleted','Cnote $delcredit_ecnote has been Deleted in Station $delcredit_ecompany','$delcredit_euser',getdate())");
		}



		



  //Calculate Amount for Credit
  if(isset($_POST['customer']))
  {
	$ccustomer    = $_POST['customer'];
	$creditorigin      = $_POST['origin'];
	$destcode_ofcredit = $_POST['destination'];
	$creditweight      = $_POST['weight'];
	$branchfind       = $_POST['branch'];
	$table     = $_POST['table'];


		  
	function getrate($branchfind,$db,$creditorigin){
	   $getbranch = $db->query("SELECT * FROM branch WHERE br_code='$branchfind' AND stncode='$creditorigin'")->fetch(PDO::FETCH_OBJ);
	   if(empty($getbranch->rate) OR is_null($getbranch->rate)){
		 $setrate = 'TLRRATE1';
	   }else{
		 $setrate = $getbranch->rate;
	   }
	   return $setrate;
	 } 

	 $company_origins = array("KUR", "PNQ", "DBL");
	 if($table=='cash' && in_array($company_origins,$mode_origin)){
   $creditcustomer = getrate($branchfind,$db,$creditorigin);
  }else{
   $crate = $db->query("SELECT rate FROM customer WITH (NOLOCK) WHERE comp_code='$creditorigin' AND cust_code='$ccustomer'");
   $valcrate = $crate->fetch(PDO::FETCH_OBJ);
   if(!empty($valcrate->rate)){
	 $creditcustomer = $valcrate->rate;
   }
   else{
	 $creditcustomer = $ccustomer;
   }
  }
  
	$getcustzncode = $db->query("SELECT zncode FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
   $rowzncode = $getcustzncode->fetch(PDO::FETCH_ASSOC);

   $getdestfrompin = $db->query("SELECT TOP ( 1 ) dest_code FROM pincode WITH (NOLOCK) WHERE pincode='$destcode_ofcredit' OR dest_code='$destcode_ofcredit' OR city_name='$destcode_ofcredit'");
   if($getdestfrompin->rowCount()==0){
	 $verifydestination = $db->query("SELECT TOP ( 1 ) dest_code FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$destcode_ofcredit' OR dest_name='$destcode_ofcredit'");
	 $rowdestdata = $verifydestination->FETCH(PDO::FETCH_OBJ);
	 $val_rowdestdata = $rowdestdata->dest_code;
	 $verifyratecode = $db->query("SELECT con_code FROM rate_custcontract WITH (NOLOCK) WHERE origin='$creditorigin' AND destn='$val_rowdestdata' AND cust_code='$creditcustomer'");
	 if($verifyratecode->rowCount()==0)
	 {
	   // Customer type A rate setup populate
	  if($rowzncode['zncode']=='A'){
	   $getrowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
	   if($getrowdest->rowCount()==0){
		 $creditdestination =  $destcode_ofcredit;
	   }else{
	   $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	   $creditdestination = $rowgetzonecode['zonecode_a'];
	   }
	  }
	
	 // Customer type B rate setup populate
	if($rowzncode['zncode']=='B'){
	   $getrowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata' ");
	   if($getrowdest->rowCount()==0){
		 $creditdestination = $destcode_ofcredit;
	   }else{
	   $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	   $creditdestination = $rowgetzonecode['zonecode_b'];
	   }
	  }
 
	 // Customer type C rate setup populate
	if($rowzncode['zncode']=='C'){
	   $getrowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
	   if($getrowdest->rowCount()==0){
		 $creditdestination =  $destcode_ofcredit;
	   }else{
	   $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	   $creditdestination = $rowgetzonecode['zonecode_c'];
	   }
	  }
 
	 // Cash customer rate setup populate
	if($rowzncode['zncode']=='cash'){
	   $getrowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
	   if($getrowdest->rowCount()==0){
		 $creditdestination = $destcode_ofcredit;
	   }else{
	   $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	   $creditdestination = $rowgetzonecode['zone_cash'];
	   }
	  }
   }else{
	 $creditdestination = $rowdestdata->dest_code;
   }
   }
   else{
	 $rowactualdest = $getdestfrompin->fetch(PDO::FETCH_ASSOC); 
	 $valactueldest = $rowactualdest["dest_code"];
	 $checkdestn = $db->query("SELECT * FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='".$rowactualdest["dest_code"]."'");
	 $rowcheckdestn = $checkdestn->fetch(PDO::FETCH_OBJ);

	 $dest_rate = $db->query("SELECT * from rate_custcontract WHERE stncode='$creditorigin' AND destn='$rowcheckdestn->dest_code' AND cust_code='$creditcustomer'");
	 if($dest_rate->rowCount()==0){
	 if($rowzncode['zncode']=='A'){
		$getrowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
		$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
		$creditdestination = $rowgetzonecode['zonecode_a'];
	   }
	 if($rowzncode['zncode']=='B'){
		$getrowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
		$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
		$creditdestination = $rowgetzonecode['zonecode_b'];
	   }
	 if($rowzncode['zncode']=='C'){
		$getrowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
		$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
		$creditdestination = $rowgetzonecode['zonecode_c'];
	   }
	 if($rowzncode['zncode']=='cash'){
		$getrowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
		$rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
		$creditdestination = $rowgetzonecode['zone_cash'];
	   }
	 }else{
	   $creditdestination = $rowcheckdestn->dest_code;
	 }
   }
   
	  $pod_amt         = $_POST['pod_amt'];
 
	   if($pod_amt=='gl'){
		  $pro_rate = '0';
	   }
	   else if($pod_amt=='PRO' && $_POST['weight']>1){
		 $getsp = $db->query("SELECT pro_per_kg FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
		 $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
		 $proamount = $rowsp->pro_per_kg;
		 $pro = ceil($creditweight)-1;
		 $pro_rate = $pro * $proamount;
	   }
	   else if($pod_amt=='PRC' && $_POST['weight']>1){
		 $getsp = $db->query("SELECT prc_per_kg FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
		 $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
		 $proamount = $rowsp->prc_per_kg;
		 $pro = ceil($creditweight)-1;
		 $pro_rate = $pro * $proamount;
	   }
	   else if($pod_amt=='PRD' && $_POST['weight']>1){
		 $getsp = $db->query("SELECT prd_per_kg FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
		 $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
		 $proamount = $rowsp->prd_per_kg;
		 $pro = ceil($creditweight)-1;
		 $pro_rate = $pro * $proamount;
	   }
	  //  else if($pod_amt=='COD' && $_POST['weight']>1){
	  //    $getsp = $db->query("SELECT cod_per_kg FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
	  //    $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
	  //    $proamount = $rowsp->cod_per_kg;
	  //    $pro = ceil($creditweight)-1;
	  //    $pro_rate = $pro * $proamount;
	  //  }
	   
   //end special cnotes
   $creditpcs         = $_POST['pcs'];
   $creditmode         = trim($_POST['mode']);
   

   ///very very important line

	///////change 1
   $getcontract_rate1  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC")->fetch(PDO::FETCH_OBJ);

   $getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC");
   $cust_type   = $db->query("SELECT cust_type FROM customer WITH (NOLOCK) WHERE comp_code='$creditorigin' AND cust_code='$creditcustomer'")->fetch(PDO::FETCH_OBJ);
   //Special MODE FROM CASH


   ///////change 2
   if($getcontract->rowCount()==0 && ($creditmode=='PRO' || $creditmode=='PRC') && $cust_type->cust_type=='D')
   {
	 

	 $cashdestn = (isset($val_rowdestdata)?$val_rowdestdata:$valactueldest);

	 $getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='RATE1' AND origin='$creditorigin' AND destn='$cashdestn' AND mode='$creditmode' ORDER BY custcontid DESC");
	 if($getcontract->rowCount()==0){

		 $cashzone = $db->query("SELECT TOP 1 zncode FROM customer WITH (NOLOCK) WHERE cust_code='RATE1' AND comp_code='$creditorigin'")->fetch(PDO::FETCH_OBJ);


	   if($cashzone->zncode=='A'){
		 $getrowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$cashdestn'");
		 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
		 $creditdestination = $rowgetzonecode['zonecode_a'];
		}
	  if($cashzone->zncode=='B'){
		 $getrowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$cashdestn'");
		 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
		 $creditdestination = $rowgetzonecode['zonecode_b'];
		}
	  if($cashzone->zncode=='C'){
		 $getrowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$cashdestn'");
		 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
		 $creditdestination = $rowgetzonecode['zonecode_c'];
		}
	  if($cashzone->zncode=='cash'){
		 $getrowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$cashdestn'");
		 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
		 $creditdestination = $rowgetzonecode['zone_cash'];

		}
		$getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='RATE1' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC");

	 }


   }

///////change 1
 function  gst($amount){
  $net_amount = ($amount*100);
  $total_value = ($net_amount/118);
  return $total_value;
 }
 $cust_type_fsc = $db->query("SELECT cust_fsc,fsc_type FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'")->fetch(PDO::FETCH_OBJ);
 if($cust_type_fsc->fsc_type=='F'){
   $custtype = $cust_type_fsc->cust_fsc;
 }else{
   $custtype = 0;
 }
$fsccharges = (100+$custtype);

   //PRO MODE FROM CASH
   $rowcontract  = $getcontract->fetch(PDO::FETCH_ASSOC);
	$contractcode = $rowcontract['con_code'];
   // echo $contractcode; exit();
   //echo json_encode(array($creditdestination,("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC")));
   //validate weight
   $weightval = $db->query("SELECT MAX (upperwt) as maxweight FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin'");
   $rowmaxweight = $weightval->fetch(PDO::FETCH_OBJ);
   if($rowmaxweight->maxweight<$creditweight && $creditweight>0){
	 echo 0;
	 exit();
   }else{
	   //end
   $getamount    = $db->query("SELECT * FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight' AND stncode='$creditorigin'");
   $rowamount    = $getamount->fetch(PDO::FETCH_ASSOC);
	 //Bands calculation
	 if($rowamount['onadd']==0){    

	   $getamount_c    = $db->query("SELECT sum(rate) as rate FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND lowerwt >='0' AND upperwt <='".$rowamount['upperwt']."' AND onadd=0 AND stncode='$creditorigin'");
	   $rowamount_c    = $getamount_c->fetch(PDO::FETCH_ASSOC);

	 //  if($table=="credit" && $cust_type->cust_type=='D' && ($creditmode=='PRO' || $creditmode=='PRC') && count($getcontract_rate1->con_code)==0){   ///////change 1
	 //    $rate1 = (($rowamount['rate'])*$creditpcs); 
	 //    $gstvalue = (gst($rate1));
	 //    $fsc_total = ($gstvalue*100);
	 //    $fsc_percentage = number_format($fsc_total/$fsccharges,2);
	 //    echo $fsc_percentage+$pro_rate;  
	 //    exit();
	 //  }else{
	 //    echo ($rowamount['rate']+$pro_rate)*$creditpcs;
	 //   exit();
	 //  }
	 echo ($rowamount_c['rate']+$pro_rate)*$creditpcs;
	 exit();
	 }
	 else{
		$zero_onadd = $db->query("SELECT SUM(rate) AS rate FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND onadd=0 AND upperwt<='$creditweight'")->fetch(PDO::FETCH_OBJ);
		$rate1 = (int)$zero_onadd->rate;


		$get_fullrow = $db->query("SELECT rate,upperwt,multiple,onadd,lowerwt FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND onadd !=0 AND upperwt<'".$rowamount['upperwt']."' ORDER BY upperwt DESC");
		 while($rowdata = $get_fullrow->fetch(PDO::FETCH_OBJ)){
		   $rates = $rowdata->rate;
		   $upperwt = number_format($rowdata->upperwt,2);
		   $lowerwts = number_format($rowdata->lowerwt,2);
		   $multiples = number_format($rowdata->multiple,2);
		   $second_rate = number_format(($upperwt-$lowerwts)/$multiples,2);
		   $rate2[] = ceil($second_rate) * $rates;
		  
		 }
	  

		$custcontdetailid = $rowamount['custcontdetailid'];
		$get_onadd = $db->query("SELECT TOP 1 custcontdetailid FROM rate_contractdetail WITH (NOLOCK) WHERE custcontdetailid < '$custcontdetailid' AND con_code='$contractcode' AND stncode='$creditorigin' ORDER BY custcontdetailid DESC")->fetch(PDO::FETCH_OBJ);

		

		$onnadd_data = $db->query("SELECT onadd FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND custcontdetailid='$get_onadd->custcontdetailid'")->fetch(PDO::FETCH_OBJ);

		$onnadd_count = $db->query("SELECT count(onadd) as conadd FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND onadd='".$rowamount['onadd']."'")->fetch(PDO::FETCH_OBJ);

		if($onnadd_count->conadd > 1){
		 $order = "ASC";
		}else{
		 $order = "DESC";
		}
		$getfirst_rate = $db->query("SELECT rate,onadd,upperwt FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND onadd='$onnadd_data->onadd' AND upperwt<='$creditweight' ORDER BY upperwt $order");
		$rowfirst_rate = $getfirst_rate->fetch(PDO::FETCH_ASSOC);

	  
		$first_rate = number_format($rowfirst_rate['upperwt'],2);

		$second_rate1 = (ceil(($creditweight-$first_rate)/$rowamount['multiple'])*$rowamount['multiple'])/$rowamount['onadd'];
		$rate7 = $second_rate1 * $rowamount['rate'];


		
		  if($rate2==null){
			$finalamount[]  = $rate2;
		  }else{
		   $finalamount  = $rate2;
		  }
		  $pushdata = array_push($finalamount,$rate1,$rate7);
	   echo (array_sum($finalamount)+$pro_rate)*$creditpcs;
		exit();
	 }
	
   }
 	
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
		//end logs