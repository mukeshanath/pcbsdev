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


include 'db.php';

    class bainbound extends My_controller
    {
      public function index()
      {
            $this->view->title = __SITE_NAME__ . ' - bainbound';
            $this->view->render('billing/bainbound');
      }
    }
    date_default_timezone_set('Asia/Kolkata');

if(isset($_POST['addbainbound']))
	{
		try{
		addbainbound($db);
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

			$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Transaction failed at Bainbound. ERROR_CODE:$error_info - $err_desc CNO:$comp_code$cnote_no','$yourbrowser')"); 

		header("Location: bainboundlist?success=1");
		}
	}

	function addbainbound($db)
	{
		date_default_timezone_set('Asia/Kolkata');

			$comp_code          	 = $_POST['comp_code'];
			$br_code 			      = $_POST['br_code'];
			$origin 			      = $_POST['origin'];
			$podorigin 	         	 = $_POST['podorigin'];
			$pcs				      = $_POST['pcs'];
			$mf_no			      = $_POST['mf_no'];
			$cust_code		      = $_POST['cust_code'];
			$cust_name		      = $_POST['cust_name'];
			$consignee		      = $_POST['consignee'];
			$wt 				      = max($_POST['wt'],$_POST['v_wt']);
			//$amount			      = $_POST['amount'];
			$mode			      = $_POST['mode'];
			$destination	      	 = explode('-',$_POST['destination'])[0];
			$cnote_no	   	      	 = $_POST['cnote_no'];
			$user_name          	 = $_POST['user_name'];       
			$date_added         	 = date('Y-m-d H:i:s');
			$date=date_create($_POST['bdate']);
			$bdate =  date_format($date,"Y/m/d");
			$edate              	 = date('Y-m-d H:i:s');
			$amount				 = calculateamount($db,$cust_code,$origin,$destination,$wt,$mode,$podorigin);
	
			//GET Flag Value
			$getflag = $db->query("SELECT push_to_website FROM customer WHERE cust_code='$cust_code'");
			$getflagvalue	 = $getflag->fetch(PDO::FETCH_ASSOC);
			$flag			 = $getflagvalue['push_to_website'];
	
				
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

					$desttbverify = $db->query("SELECT * FROM destination  WHERE comp_code='$comp_code' AND dest_code='$destination' OR dest_name='$destination'");
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
	
					$checkallotted = $db->query("SELECT * FROM cnotenumber  WHERE cnote_station='$comp_code' AND cnote_number='$cnote_no' AND cnote_status='Allotted'");
					if($checkallotted->rowCount()!=0){
					$creditmaster_verify = $db->query("SELECT * FROM bainbound_master WHERE mf_no='$mf_no' AND ba_code='$cust_code' AND date_added='$date_added'");
					if($creditmaster_verify->rowCount()==0)
					{
						$db->query("INSERT INTO bainbound_master (comp_code,mf_no,br_code,ba_code,ba_name,total_cnotes,user_name,date_added)
						VALUES ('$comp_code','$mf_no','$br_code','$cust_code','$cust_name','1','$user_name','$date_added')");
					}
					else
					{
						$db->query("UPDATE bainbound_master SET total_cnotes=total_cnotes+1 WHERE mf_no='$mf_no' AND ba_code='$cust_code' AND date_added='$date_added'");
					}
						//Update Billing Cash in Cnotenumbertb
						//$db->query("UPDATE cnotenumber SET bilwt='$wt',bilrate='$amount',bilmode='$mode',bildest='$dest_code',bilpincode='$dest_code',bdate='$bdate',cnote_status='Billed' WHERE cnote_station='$comp_code' AND cnote_number='$cnote_no'");
						//Insert into cashtb
						$checkduplicateba = $db->query("SELECT * FROM bainbound  WHERE comp_code='$comp_code' AND cno='$cnote_no'");
						if($checkduplicateba->rowCount()==0){
						$db->query("EXEC insert_bainbound 
						@origin ='$origin',
						@podorigin ='$pod_origin',
						@cno ='$cnote_no',
						@pincode ='$pincode',
						@dest_code ='$dest_code',
						@zone_code ='$zonecode',
						@bacode ='$cust_code',
						@consignee ='$consignee',
						@wt ='$wt',
						@pcs ='$pcs',
						@mode_code ='$mode',
						@amt ='$amount',
						@mf_no ='$mf_no',
						@br_code ='$br_code',
						@comp_code ='$comp_code',
						@flag ='$flag',
						@user_name ='$user_name',
						@date_added ='$date_added',
						@bdate  ='$bdate',
						@tdate ='$bdate',
						@edate ='$edate',
						@status = 'Billed'");
						}
						//insert into credit
						$checkduplicatec = $db->query("SELECT * FROM credit  WHERE comp_code='$comp_code' AND cno='$cnote_no'");
						if($checkduplicatec->rowCount()==0){
						$db->query("EXEC insert_credit 
						@origin ='$origin',
						@podorigin ='$pod_origin',
						@cno ='$cnote_no',
						@pincode ='$pincode',
						@dest_code ='$dest_code',
						@zone_code ='$zonecode',
						@cust_code ='$cust_code',
						@consignee ='$consignee',
						@wt ='$wt',
						@pcs ='$pcs',
						@mode_code ='$mode',
						@amt ='$amount',
						@mf_no ='$mf_no',
						@br_code ='$br_code',
						@comp_code ='$comp_code',
						@flag ='$flag',
						@user_name ='$user_name',
						@date_added ='$date_added',
						@bdate  ='$bdate',
						@tdate ='$bdate',
						@edate ='$edate',
						@status = 'Billed'");
						}
						//add logs
						$yourbrowser   = getBrowser()['name'];
						$user_surename = get_current_user();
						$platform      = getBrowser()['platform'];   
						$remote_addr   = getBrowser()['remote_addr'];  
						$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','BaInbound Cnotenumber $comp_code$cnote_no has Billed','$yourbrowser')");
					}
					header("Location: bainboundlist?success=1");
		}

		if(isset($_POST['addanotherbainbound']))
		{
			try{
			addanotherbainbound($db);
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
				$mf_no		= $_POST['mf_no'];
			     $cust_code	= $_POST['cust_code'];
				$error_info	= $e->errorInfo[1];
				$error_detail	= explode("]",$e->errorInfo[2]);
				$err_desc = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', END($error_detail));
				$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Transaction failed at Bainbound. ERROR_CODE:$error_info - $err_desc. CNO:$comp_code$cnote_no','$yourbrowser')"); 

			header("Location: bainbound?cust_code=$cust_code&cnotenumber=$cnote_no&mfno=$mf_no");
			}
		}

		function addanotherbainbound($db)
		{
			date_default_timezone_set('Asia/Kolkata');

			$comp_code          	 = $_POST['comp_code'];
			$br_code 			      = $_POST['br_code'];
			$origin 			      = $_POST['origin'];
			$pod_origin 		      = $_POST['podorigin'];
			$pcs				      = $_POST['pcs'];
			$mf_no			      = $_POST['mf_no'];
			$cust_code		      = $_POST['cust_code'];
			$cust_name		      = $_POST['cust_name'];
			$consignee		      = $_POST['consignee'];
			$wt 				      = max($_POST['wt'],$_POST['v_wt']);
			//$amount			      = $_POST['amount'];
			$mode		           = $_POST['mode'];
			$destination	      	 = explode('-',$_POST['destination'])[0];
			$cnote_no	   	      	 = $_POST['cnote_no'];
			$user_name          	 = $_POST['user_name'];       
			$date_added         	 = date('Y-m-d H:i:s');
			$date=date_create($_POST['bdate']);
			$bdate =  date_format($date,"Y/m/d");
			$edate              	 = date('Y-m-d H:i:s');
			$amount				= calculateamount($db,$cust_code,$origin,$destination,$wt,$mode,$pod_origin);
	
			//GET Flag Value
			$getflag = $db->query("SELECT push_to_website FROM customer WHERE cust_code='$cust_code'");
			$getflagvalue	 = $getflag->fetch(PDO::FETCH_ASSOC);
			$flag			 = $getflagvalue['push_to_website'];
	

			
					// if($podorigin=='PRO' || $podorigin=='PRD' || $podorigin=='PRC'){
					// 	$pod_condition = "AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD'";
					// 	$pod_origin = $comp_code;
					// }
					// else{
					// 	$pod_condition = "AND cnote_serial='$podorigin'";
					// 	$pod_origin = $podorigin;
					// }

					//fetch customer zone
					$getcustzncode = $db->query("SELECT zncode FROM customer  WHERE cust_code='$cust_code' AND comp_code='$comp_code'");
					$rowzncode = $getcustzncode->fetch(PDO::FETCH_OBJ);

					$desttbverify = $db->query("SELECT * FROM destination  WHERE comp_code='$comp_code' AND dest_code='$destination' OR dest_name='$destination'");
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
					$checkallotted = $db->query("SELECT * FROM cnotenumber  WHERE cnote_station='$comp_code' AND cnote_number='$cnote_no' AND cnote_status='Allotted'");
					if($checkallotted->rowCount()!=0){
					$creditmaster_verify = $db->query("SELECT * FROM bainbound_master WHERE mf_no='$mf_no' AND ba_code='$cust_code' AND date_added='$date_added'");
					if($creditmaster_verify->rowCount()==0)
					{
						$db->query("INSERT INTO bainbound_master (comp_code,mf_no,br_code,ba_code,ba_name,total_cnotes,user_name,date_added)
						VALUES ('$comp_code','$mf_no','$br_code','$cust_code','$cust_name','1','$user_name','$date_added')");
					}
					else
					{
						$db->query("UPDATE bainbound_master SET total_cnotes=total_cnotes+1 WHERE mf_no='$mf_no' AND ba_code='$cust_code' AND date_added='$date_added'");
					}
						//Insert into cashtb
						$checkduplicateba = $db->query("SELECT * FROM bainbound  WHERE comp_code='$comp_code' AND cno='$cnote_no'");
						if($checkduplicateba->rowCount()==0){
						$db->query("EXEC insert_bainbound 
						@origin ='$origin',
						@podorigin ='$pod_origin',
						@cno ='$cnote_no',
						@pincode ='$pincode',
						@dest_code ='$dest_code',
						@zone_code ='$zonecode',
						@bacode ='$cust_code',
						@consignee ='$consignee',
						@wt ='$wt',
						@pcs ='$pcs',
						@mode_code ='$mode',
						@amt ='$amount',
						@mf_no ='$mf_no',
						@br_code ='$br_code',
						@comp_code ='$comp_code',
						@flag ='$flag',
						@user_name ='$user_name',
						@date_added ='$date_added',
						@bdate  ='$bdate',
						@tdate ='$bdate',
						@edate ='$edate',
						@status = 'Billed'");
						}
						$checkduplicatec = $db->query("SELECT * FROM credit  WHERE comp_code='$comp_code' AND cno='$cnote_no'");
						if($checkduplicatec->rowCount()==0){
 						$db->query("EXEC insert_credit 
						@origin ='$origin',
						@podorigin ='$pod_origin',
						@cno ='$cnote_no',
						@pincode ='$pincode',
						@dest_code ='$dest_code',
						@zone_code ='$zonecode',
						@cust_code ='$cust_code',
						@consignee ='$consignee',
						@wt ='$wt',
						@pcs ='$pcs',
						@mode_code ='$mode',
						@amt ='$amount',
						@mf_no ='$mf_no',
						@br_code ='$br_code',
						@comp_code ='$comp_code',
						@flag ='$flag',
						@user_name ='$user_name',
						@date_added ='$date_added',
						@bdate  ='$bdate',
						@tdate ='$bdate',
						@edate ='$edate',
						@status = 'Billed'");
						}
						//add logs
						$yourbrowser   = getBrowser()['name'];
						$user_surename = get_current_user();
						$platform      = getBrowser()['platform'];   
						$remote_addr   = getBrowser()['remote_addr'];  
						$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','BaInbound Cnotenumber $comp_code$cnote_no has Billed','$yourbrowser')");
						
						//Update Billing Cash in Cnotenumbertb
						$db->query("UPDATE cnotenumber SET bilwt='$wt',bilrate='$amount',bilmode='$mode',bildest='$dest_code',bilpincode='$dest_code',bdate='$bdate',cnote_status='Billed' WHERE cnote_station='$comp_code' AND cnote_number='$cnote_no'");
					
	
					}
						header("Location: bainbound?cust_code=$cust_code&cnotenumber=$cnote_no&mfno=$mf_no");
					}

//Calculate Amount for Credit
function calculateamount($db,$custcode,$r_origin,$r_destn,$r_weight,$r_mode,$r_pod_amt)
{
  $ccustomer    	  = $custcode;
  $creditorigin      = $r_origin;
  $destcode_ofcredit = $r_destn;
  $creditweight      = $r_weight;

  $crate = $db->query("SELECT rate FROM customer WHERE comp_code='$creditorigin' AND cust_code='$ccustomer'");
  $valcrate = $crate->fetch(PDO::FETCH_OBJ);
  if(!empty($valcrate->rate)){
    $creditcustomer = $valcrate->rate;
  }
  else{
    $creditcustomer = $ccustomer;
  }

  $getcustzncode = $db->query("SELECT zncode FROM customer WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
  $rowzncode = $getcustzncode->fetch(PDO::FETCH_ASSOC);

  $getdestfrompin = $db->query("SELECT TOP ( 1 ) dest_code FROM pincode WHERE pincode='$destcode_ofcredit' OR dest_code='$destcode_ofcredit' OR city_name='$destcode_ofcredit'");
  if($getdestfrompin->rowCount()==0){
    $verifydestination = $db->query("SELECT TOP ( 1 ) dest_code FROM destination WHERE comp_code='$creditorigin' AND dest_code='$destcode_ofcredit' OR dest_name='$destcode_ofcredit'");
    $rowdestdata = $verifydestination->FETCH(PDO::FETCH_OBJ);
    $val_rowdestdata = $rowdestdata->dest_code;
    $verifyratecode = $db->query("SELECT con_code FROM rate_custcontract WHERE origin='$creditorigin' AND destn='$val_rowdestdata' AND cust_code='$creditcustomer'");
    if($verifyratecode->rowCount()==0)
    {
	 // Customer type A rate setup populate
	if($rowzncode['zncode']=='A'){
	 $getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
	 if($getrowdest->rowCount()==0){
	   $creditdestination =  $destcode_ofcredit;
	 }else{
	 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	 $creditdestination = $rowgetzonecode['zonecode_a'];
    }
	}
   
    // Customer type B rate setup populate
   if($rowzncode['zncode']=='B'){
	 $getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata' ");
	 if($getrowdest->rowCount()==0){
	   $creditdestination = $destcode_ofcredit;
	 }else{
	 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	 $creditdestination = $rowgetzonecode['zonecode_b'];
	 }
	}

    // Customer type C rate setup populate
   if($rowzncode['zncode']=='C'){
	 $getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
	 if($getrowdest->rowCount()==0){
	   $creditdestination =  $destcode_ofcredit;
	 }else{
	 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	 $creditdestination = $rowgetzonecode['zonecode_c'];
	 }
	}

    // Cash customer rate setup populate
   if($rowzncode['zncode']=='cash'){
	 $getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
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
    $checkdestn = $db->query("SELECT * FROM destination WHERE comp_code='$creditorigin' AND dest_code='".$rowactualdest["dest_code"]."'");
    $rowcheckdestn = $checkdestn->fetch(PDO::FETCH_OBJ);

    $dest_rate = $db->query("SELECT * from rate_custcontract WHERE stncode='$creditorigin' AND destn='$rowcheckdestn->dest_code' AND cust_code='$creditcustomer'");
    if($dest_rate->rowCount()==0){
    if($rowzncode['zncode']=='A'){
	  $getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
	  $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	  $creditdestination = $rowgetzonecode['zonecode_a'];
	 }
    if($rowzncode['zncode']=='B'){
	  $getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
	  $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	  $creditdestination = $rowgetzonecode['zonecode_b'];
	 }
    if($rowzncode['zncode']=='C'){
	  $getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
	  $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	  $creditdestination = $rowgetzonecode['zonecode_c'];
	 }
    if($rowzncode['zncode']=='cash'){
	  $getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
	  $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	  $creditdestination = $rowgetzonecode['zone_cash'];
	 }
    }else{
	 $creditdestination = $rowcheckdestn->dest_code;

    }
  }
 
	$pod_amt         = $r_pod_amt;

	if($pod_amt=='gl'){
	    $pro_rate = '0';
	 }
	 else if($pod_amt=='PRO' && $creditweight>1){
	   $getsp = $db->query("SELECT pro_per_kg FROM customer WHERE cust_code='$ccustomer'");
	   $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
	   $proamount = $rowsp->pro_per_kg;
	   $pro = ceil($creditweight) - 1;
	   $pro_rate = $pro * $proamount;
	 }
	 else if($pod_amt=='PRC' && $creditweight>1){
	   $getsp = $db->query("SELECT prc_per_kg FROM customer WHERE cust_code='$ccustomer'");
	   $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
	   $proamount = $rowsp->prc_per_kg;
	   $pro = ceil($creditweight) - 1;
	   $pro_rate = $pro * $proamount;
	 }
	 else if($pod_amt=='PRD' && $creditweight>1){
	   $getsp = $db->query("SELECT prd_per_kg FROM customer WHERE cust_code='$ccustomer'");
	   $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
	   $proamount = $rowsp->prd_per_kg;
	   $pro = ceil($creditweight) - 1;
	   $pro_rate = $pro * $proamount;
	 }
	
  //end special cnotes
  $creditpcs         = '1';
  $creditmode         = $r_mode;
  
  $getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC");
  $rowcontract  = $getcontract->fetch(PDO::FETCH_ASSOC);
  $contractcode = $rowcontract['con_code'];
  //validate weight
  $weightval = $db->query("SELECT MAX (upperwt) as maxweight FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin'");
  $rowmaxweight = $weightval->fetch(PDO::FETCH_OBJ);
  if($rowmaxweight->maxweight<$creditweight && $creditweight>0){
	return "0";
  }
  //end
  $getamount    = $db->query("SELECT * FROM rate_contractdetail WHERE con_code='$contractcode' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight' AND stncode='$creditorigin'");
  $rowamount    = $getamount->fetch(PDO::FETCH_ASSOC);
  if($rowamount['onadd']=='0'){
	return ($rowamount['rate']+$pro_rate) * $creditpcs;
  }
  else if($rowamount['onadd']=='.25'){
    $floor = floor($creditweight/$rowamount['multiple'])*$rowamount['multiple'];
    $isDecimal = $creditweight/$rowamount['multiple'];
    $decimal = ((float) $isDecimal !== floor($isDecimal));
    if(!empty($decimal)){
	   $round = $floor+$rowamount['multiple'];
    }
    else{
	   $round = $floor;
    }

    $first_rate = $rowamount['onadd'];
    $getfirst_rate = $db->query("SELECT rate,onadd FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$first_rate' AND upperwt >= '$first_rate'");
    $rowfirst_rate = $getfirst_rate->fetch(PDO::FETCH_ASSOC);
    $rate1 = $rowfirst_rate['rate'];

    $second_rate = ($round-$first_rate)/$first_rate;
    $rate2 = $second_rate * $rowamount['rate'];
    return (($rate1+$rate2)+$pro_rate)*$creditpcs;
  }
  else if($rowamount['onadd']=='.5'){

	$floor = floor($creditweight/$rowamount['multiple'])*$rowamount['multiple'];
	$isDecimal = $creditweight/$rowamount['multiple'];
	$decimal = ((float) $isDecimal !== floor($isDecimal));
	if(!empty($decimal)){
	    $roundvalue = $floor+$rowamount['multiple'];
	}
	else{
	    $roundvalue = $floor;
	}

	$getrate2 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '.5' AND stncode='$creditorigin'");
	$getratewt1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '1' AND stncode='$creditorigin'");
	$getratewt3 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '.25' AND stncode='$creditorigin'");
    
	 if($getratewt1->rowCount()!=0 && $creditweight>1){
		 $subavailwt = $creditweight-1;
		 $floor = floor($subavailwt/$rowamount['multiple'])*$rowamount['multiple'];
		 $isDecimal = $subavailwt/$rowamount['multiple'];
		 $decimal = ((float) $isDecimal !== floor($isDecimal));
		 if(!empty($decimal)){
			$roundvalue2 = $floor+$rowamount['multiple'];
		 }
		 else{
			$roundvalue2 = $floor;
		 }
		 $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
		 $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
		 $getrate2row = $getratewt1->fetch(PDO::FETCH_OBJ);
		 $rate2 = $getrate2row->rate;
	  }
	  else if($getrate2->rowCount()!=0 && $creditweight>.5){
	    $subavailwt = $creditweight-.5;
	    $floor = floor($subavailwt/$rowamount['multiple'])*$rowamount['multiple'];
	    $isDecimal = $subavailwt/$rowamount['multiple'];
	    $decimal = ((float) $isDecimal !== floor($isDecimal));
	    if(!empty($decimal)){
		   $roundvalue2 = $floor+$rowamount['multiple'];
	    }
	    else{
		   $roundvalue2 = $floor;
	    }
	    $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
	    $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
	    $getrate2row = $getrate2->fetch(PDO::FETCH_OBJ);
	    $rate2 = $getrate2row->rate;
	}
	  else if($getratewt3->rowCount()!=0){
	    $subavailwt = $creditweight-.25;
	    $floor = floor($subavailwt/$rowamount['multiple'])*$rowamount['multiple'];
	    $isDecimal = $subavailwt/$rowamount['multiple'];
	    $decimal = ((float) $isDecimal !== floor($isDecimal));
	    if(!empty($decimal)){
		   $roundvalue2 = $floor+$rowamount['multiple'];
	    }
	    else{
		   $roundvalue2 = $floor;
	    }
	    $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
	    $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
	    $getrate3row = $getratewt3->fetch(PDO::FETCH_OBJ);
	    $rate2 = $getrate3row->rate;
	}
	else{
	    $subavailwt = $creditweight-.25;
	    $floor = floor($subavailwt/$rowamount['multiple'])*$rowamount['multiple'];
	    $isDecimal = $subavailwt/$rowamount['multiple'];
	    $decimal = ((float) $isDecimal !== floor($isDecimal));
	    if(!empty($decimal)){
		   $roundvalue2 = $floor+$rowamount['multiple'];
	    }
	    else{
		  $roundvalue2 = $floor;
	    }
		$getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
		$rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
		$getrate3 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND upperwt = '.25'")->fetch(PDO::FETCH_OBJ);
		$rate2 = $getrate3->rate;
	}
    
	return $rate1+$rate2+$pro_rate;
   }
  else if($rowamount['onadd']=='1'){
    $floor = floor($creditweight/$rowamount['multiple'])*$rowamount['multiple'];
    $isDecimal = $creditweight/$rowamount['multiple'];
    $decimal = ((float) $isDecimal !== floor($isDecimal));
    if(!empty($decimal)){
	   $round = $floor+$rowamount['multiple'];
    }
    else{
	   $round = $floor;
    }
   
    return (($round*$rowamount['rate'])+$pro_rate)*$creditpcs;
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