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

      class customer extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - customer';
              $this->view->render('masters/customer');
          }
      }


      $errors = array();

          if(isset($_POST['addcustomer'])){
            savecustomer($db);
          }
            function savecustomer($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_added = date('Y-m-d H:i:s');
                $doe = date('Y-m-d H:i:s');

                $stncode				= $_POST['comp_code'];
                $stnname				= $_POST['comp_name'];
			    $br_code				= strtoupper($_POST['br_code']);
                $br_name				= $_POST['br_name'];
                $cust_code			= $_POST['cust_code'];
                $cust_name			= $_POST['cust_name'];
                $exe_code			= $_POST['exe_code'];
			    $exe_name			= $_POST['exe_name'];
			    $role				= $_POST['role'];
                // $cust_addrtype		= $_POST['cust_addrtype'];
                $cust_addr 			= $_POST['cust_addr'];
                $cust_phone			= $_POST['cust_phone'];
                $cust_mobile	    		= $_POST['cust_mobile'];
                $cust_city	    		= $_POST['cust_city'];
                $cust_state	    		= $_POST['cust_state'];
                $cust_country	    		= $_POST['cust_country'];
                $cust_fax			= $_POST['cust_fax'];
                $cust_email			= $_POST['cust_email'];
                $cctr				= $_POST['cctr'];
                $ccdly				= $_POST['ccdly'];
                $credit_control		= $_POST['credit_control'];
                $other_charges		= $_POST['other_charges'];
                $c_person			= $_POST['c_person'];
                $zncode				= $_POST['zncode'];
                $cust_type 			= $_POST['cust_type'];
                $transit_hub			= $_POST['transit_hub'];
                $cust_remarks			= $_POST['cust_remarks'];
                $push_to_website		= $_POST['push_to_web'];
                $cust_active			= $_POST['cust_active'];
                $spl_discountD		= $_POST['spl_discountD'];
                $spl_discountI		= $_POST['spl_discountI'];
                $cnote_adv			= $_POST['cnote_adv'];
                $cnote_charges		= $_POST['cnote_charges'];
                $pro 				= $_POST['pro'];
				$prc				= $_POST['prc'];
				$prd				= $_POST['prd'];
				$pro_per_kg			= $_POST['pro_per_kg'];
				$prc_per_kg			= $_POST['prc_per_kg'];
				$prd_per_kg			= $_POST['prd_per_kg'];
				$cod_per_kg			= $_POST['cod_per_kg'];
				$cod				= $_POST['cod'];
                $cust_fsc			= $_POST['cust_fsc'];
				$fsc_type			= $_POST['fsc_type'];
				$grace_period       = $_POST['grace_period'];
				$credit_invoice	    = $_POST['credit_invoice'];
				$cust_msr	        = $_POST['cust_msr'];
				$cust_bus_vol	    = $_POST['cust_bus_vol'];
                $deposit			= $_POST['deposit'];
                $date_contract		= $_POST['date_contract'];
                $contract_period	= $_POST['contract_period'];
                $cust_gstin			= $_POST['cust_gstin'];
                $cust_pan			= $_POST['cust_pan'];
                $cust_tin 			= $_POST['cust_tin'];
                $cust_uin			= $_POST['cust_uin'];
                $quote_no			= $_POST['quote_no'];
                $credit_limit		= $_POST['credit_limit'];
                $track_id			= $_POST['track_id'];
				$rate				= explode("-",$_POST['rate'])[0];
				$insurance_charges			= $_POST['insurance_charges'];
				$packing_charges			= $_POST['packing_charges'];
                $username	        = $_POST['user_name'];
				$credit_accounts_copy   = $_POST['credit_accounts_copy'];
                $credit_pod_copy        = $_POST['credit_pod_copy'];
                $credit_consignor_copy  = $_POST['credit_consignor_copy'];
                $f_handling_charge    = $_POST['f_handling_charge'];
                $tr_lock              = $_POST['tr_lock'];
                $tr_lock_date              = $_POST['transaction_date'];
				$cust_pincode       = $_POST['cust_pincode'];

                $sqlcheck = "SELECT * from customer WHERE comp_code='$stncode' AND br_code='$br_code' AND cust_code='$cust_code'";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                    $brcode = $row->br_code;
                    $stn_code = $row->comp_code;
                    $custcode = $row->$cust_code;
                }

                if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($stncode==$stn_code AND $br_code==$brcode AND $cust_code==$custcode)
                    {
                      header("location: customer?success=4"); 
                      } 
                      else {    $sql = "INSERT INTO customer (comp_code,
              								  comp_name,
              								  br_code,
              								  br_name,
              								  cust_code,
              								  cust_name,
              								  exe_code,
              								  exe_name, 
											  role,                								
              								  cust_addr,
              								  cust_phone,
              								  cust_mobile,
              								  cust_fax,
              								  cust_email,
              								  cctr,
              								  ccdly,
              								  credit_control,
              								  cust_city,
              								  cust_state,
              								  cust_country,
              								  c_person,
              								  zncode,
              								  cust_type,
              								  transit_hub,
              								  cust_remarks,
										  	  push_to_website,
              								  cust_active,
              								  spl_discountD,
              								  spl_discountI,
              								  cnote_adv,
              								  cnote_charges,
              								  pro,
              								  prc,
											  prd,
											  pro_per_kg,
											  prc_per_kg,
											  prd_per_kg,
											  cod,
											  cod_per_kg,
              								  cust_fsc,
              								  fsc_type,
											  grace_period,
              								  credit_invoice,
											  cust_msr,
											  cust_bus_vol,
              								  deposit,
              								  date_contract,
              								  contract_period,
              								  cust_gstin,
              								  cust_pan,
              								  cust_tin,
              								  cust_uin,
              								  quote_no,
              								  credit_limit,
              								  track_id,
              								  rate,
              								  user_name,
              								  doe, 
              								  flag, 
              								  date_added,
												insurance_charges,
												packing_charges,
												credit_accounts_copy,
                                              credit_pod_copy,
                                              credit_consignor_copy,
											  fhandling_charges,
                                              tr_lock,
                                              tr_lock_date,
											  pincode,
												other_charges) VALUES ('$stncode',
              								  					  '$stnname',
              								  					  '$br_code',
              								  					  '$br_name',
              								  					  '$cust_code',
              								  					  '$cust_name',
              								  					  '$exe_code',
              								  					  '$exe_name',
															  '$role',              								  					
              								  					  '$cust_addr',
              								  					  '$cust_phone',
              								  					  '$cust_mobile',
              								  					  '$cust_fax',
              								  					  '$cust_email',
              								  					  '$cctr',
              								  					  '$ccdly',
              								  					  '$credit_control',
              								  					  '$cust_city',
              								  					  '$cust_state',
              								  					  '$cust_country',
              								  					  '$c_person',
              								  					  '$zncode',
              								  					  '$cust_type',
              								  					  '$transit_hub',
              								  					  '$cust_remarks',
              								  					  '$push_to_website',
              								  					  '$cust_active',
              								  					  '$spl_discountD',
              								  					  '$spl_discountI',
              								  					  '$cnote_adv',
              								  					  '$cnote_charges',
              								  					  '$pro',
              								  					  '$prc',
																  '$prd',
																  '$pro_per_kg',
																  '$prc_per_kg',
																  '$prd_per_kg',
																  '$cod',
											  					  '$cod_per_kg',
              								  					  '$cust_fsc',
              								  					  '$fsc_type',
																  '$grace_period',
              								  					  '$credit_invoice',
																  '$cust_msr',
																  '$cust_bus_vol',
              								  					  '$deposit',
              								  					  '$date_contract',
              								  					  '$contract_period',
              								  					  '$cust_gstin',
              								  					  '$cust_pan',
              								  					  '$cust_tin',
              								  					  '$cust_uin',
              								  					  '$quote_no',
              								  					  '$credit_limit',
              								  					  '$track_id',
              								  					  '$rate',              								  					  
              								  					  '$username',
              								  					  '$doe',
              								  					  '1',
                                            '$date_added',
											'$insurance_charges',
								   			'$packing_charges',
											   '$credit_accounts_copy',
                                               '$credit_pod_copy',
                                               '$credit_consignor_copy',
											   '$f_handling_charge',
                         '$tr_lock',
'$tr_lock_date',
'$cust_pincode',
                                            '$other_charges'
											)";
                      }
                      if($result  = $db->query($sql))
                      {

						if(file_exists($_FILES["file"]["tmp_name"])){
							$tmpname = $_FILES["file"]["tmp_name"];
							$name = $_FILES["file"]["name"];
			
							$dataString = file_get_contents($tmpname);
							$encodedfile = base64_encode($dataString);
							
							$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Rate Master','Customer Rate','Updated','RateContract file has been updated','$username',getdate())");   
			
							$db->query("INSERT INTO contract_files (
							comp_code
							,br_code
							,cust_code
							,contract_files
							,file_name
							,date_modified
							,date_added) VALUES
							 (
							 '$stncode'
							 ,'$br_code'
							 ,'$cust_code'
							 ,'$encodedfile'
							 ,'$name'
							 ,getdate()
							 ,getdate()
							 )");
							}

                      header("location: customerlist?success=1"); 
                      }
                      
                    }
                 }

             // add an record funcation call

             if(isset($_POST['addancustomer']))            
            {
              saveancustomer($db);
            }
            
            function saveancustomer($db)
		  {
			date_default_timezone_set('Asia/Kolkata');
			$date_added = date('Y-m-d H:i:s');
			$doe = date('Y-m-d H:i:s');

			$stncode 	  		= $_POST['comp_code'];
			$stnname	  		= $_POST['comp_name'];
			    $br_code 		    = strtoupper($_POST['br_code']);
			$br_name			= $_POST['br_name'];
			$cust_code          = $_POST['cust_code'];
			$cust_name			= $_POST['cust_name'];
			$exe_code			= $_POST['exe_code'];
			    $exe_name			= $_POST['exe_name'];
			    $role		    	= $_POST['role'];
				$other_charges		= $_POST['other_charges'];
				$cust_addr 			= $_POST['cust_addr'];
			$cust_phone			= $_POST['cust_phone'];
			$cust_mobile	    		= $_POST['cust_mobile'];
			$cust_city	    		= $_POST['cust_city'];
			$cust_state	    		= $_POST['cust_state'];
			$cust_country	    		= $_POST['cust_country'];
			$cust_fax			= $_POST['cust_fax'];
			$cust_email			= $_POST['cust_email'];
			$cctr				= $_POST['cctr'];
			$ccdly				= $_POST['ccdly'];
			$credit_control		= $_POST['credit_control'];
			$c_person			= $_POST['c_person'];
			$zncode				= $_POST['zncode'];
			$cust_type 			= $_POST['cust_type'];
			$transit_hub	    = $_POST['transit_hub'];
			$cust_remarks	    = $_POST['cust_remarks'];
			$push_to_website	    = $_POST['push_to_web'];
			$cust_active	    = $_POST['cust_active'];
			$spl_discountD	    = $_POST['spl_discountD'];
			$spl_discountI	    = $_POST['spl_discountI'];
			$cnote_adv			= $_POST['cnote_adv'];
			$cnote_charges	    = $_POST['cnote_charges'];
			$pro 				= $_POST['pro'];
			    $prc				= $_POST['prc'];
			    $prd				= $_POST['prd'];
				$cod				= $_POST['cod'];
			    $pro_per_kg			= $_POST['pro_per_kg'];
			    $prc_per_kg			= $_POST['prc_per_kg'];
			    $prd_per_kg			= $_POST['prd_per_kg'];
				$cod_per_kg			= $_POST['cod_per_kg'];
			$cust_fsc			= $_POST['cust_fsc'];
			    $fsc_type			= $_POST['fsc_type'];
			    $grace_period       = $_POST['grace_period'];
			    $credit_invoice	    = $_POST['credit_invoice'];
			    $cust_msr	        = $_POST['cust_msr'];
			    $cust_bus_vol	    = $_POST['cust_bus_vol'];
			$deposit			= $_POST['deposit'];
			$date_contract		= $_POST['date_contract'];
			$contract_period	= $_POST['contract_period'];
			$cust_gstin			= $_POST['cust_gstin'];
			$cust_pan			= $_POST['cust_pan'];
			$cust_tin 			= $_POST['cust_tin'];
			$cust_uin			= $_POST['cust_uin'];
			$quote_no			= $_POST['quote_no'];
			$credit_limit		= $_POST['credit_limit'];
			$track_id			= $_POST['track_id'];
			$track_id			= $_POST['track_id'];
			$insurance_charges			= $_POST['insurance_charges'];
			$packing_charges			= $_POST['packing_charges'];
			    $rate				= explode("-",$_POST['rate'])[0];
			$username	        = $_POST['user_name'];
			$credit_accounts_copy   = $_POST['credit_accounts_copy'];
                $credit_pod_copy        = $_POST['credit_pod_copy'];
                $credit_consignor_copy  = $_POST['credit_consignor_copy'];
				$f_handling_charge    = $_POST['f_handling_charge'];
        $tr_lock              = $_POST['tr_lock'];
        $tr_lock_date                = $_POST['transaction_date'];
		$cust_pincode       = $_POST['cust_pincode'];

			$sqlcheck = "SELECT * from customer WHERE comp_code='$stncode' AND br_code='$br_code' AND cust_code='$cust_code'";
			$sqlresult  = $db->query($sqlcheck);
	  
			while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
			{
			    $brcode = $row->br_code;
			    $stn_code = $row->comp_code;
			    $custcode = $row->$cust_code;
			}

			if($sqlresult->rowCount() >= 0)
			{
			  
			  if ($stncode==$stn_code AND $br_code==$brcode AND $cust_code==$custcode)
			    {
				 header("location: customer?success=4"); 
				 } 
				 else {    $sql = "INSERT INTO customer (comp_code,
											comp_name,
											br_code,
											br_name,
											cust_code,
											cust_name,
											exe_code,
											exe_name, 
											 role,                								
											cust_addr,
											cust_phone,
											cust_mobile,
											cust_fax,
											cust_email,
											cctr,
              								  	ccdly,
              								  	credit_control,
											cust_city,
											cust_state,
											cust_country,
											c_person,
											zncode,
											cust_type,
											transit_hub,
											cust_remarks,
										 push_to_website,
											cust_active,
											spl_discountD,
											spl_discountI,
											cnote_adv,
											cnote_charges,
											pro,
											prc,
											 prd,
											 pro_per_kg,
											 prc_per_kg,
											 prd_per_kg,
											 cod,
											cod_per_kg,
											cust_fsc,
											fsc_type,
											 grace_period,
											credit_invoice,
											 cust_msr,
											 cust_bus_vol,
											deposit,
											date_contract,
											contract_period,
											cust_gstin,
											cust_pan,
											cust_tin,
											cust_uin,
											quote_no,
											credit_limit,
											track_id,
											rate,
											user_name,
											doe, 
											flag, 
											date_added,
											insurance_charges,
											packing_charges,
											credit_accounts_copy,
                                              credit_pod_copy,
                                              credit_consignor_copy,
											  fhandling_charges,
                                              tr_lock,
                                              tr_lock_date,
											  pincode,
											other_charges) VALUES ('$stncode',
																  '$stnname',
																  '$br_code',
																  '$br_name',
																  '$cust_code',
																  '$cust_name',
																  '$exe_code',
																  '$exe_name',
															 		'$role',              								  					
																  '$cust_addr',
																  '$cust_phone',
																  '$cust_mobile',
																  '$cust_fax',
																  '$cust_email',
																  '$cctr',
              								  					  	  '$ccdly',
              								  					  	  '$credit_control',
																  '$cust_city',
																  '$cust_state',
																  '$cust_country',
																  '$c_person',
																  '$zncode',
																  '$cust_type',
																  '$transit_hub',
																  '$cust_remarks',
																  '$push_to_website',
																  '$cust_active',
																  '$spl_discountD',
																  '$spl_discountI',
																  '$cnote_adv',
																  '$cnote_charges',
																  '$pro',
																  '$prc',
																 '$prd',
																 '$pro_per_kg',
																 '$prc_per_kg',
																 '$prd_per_kg',
																 '$cod',
											  					  '$cod_per_kg',
																  '$cust_fsc',
																  '$fsc_type',
																 '$grace_period',
																  '$credit_invoice',
																 '$cust_msr',
																 '$cust_bus_vol',
																  '$deposit',
																  '$date_contract',
																  '$contract_period',
																  '$cust_gstin',
																  '$cust_pan',
																  '$cust_tin',
																  '$cust_uin',
																  '$quote_no',
																  '$credit_limit',
																  '$track_id',
																  '$rate',              								  					  
																  '$username',
																  '$doe',
																  '1',
								   '$date_added',
								   '$insurance_charges',
								   '$packing_charges',
								   '$credit_accounts_copy',
                                               '$credit_pod_copy',
                                               '$credit_consignor_copy',
											   '$f_handling_charge',
                                     '$tr_lock',
                                 '$tr_lock_date',
								 '$cust_pincode',
								   '$other_charges'
								   )";
				 }
				 if($result  = $db->query($sql))
				 {
					if(file_exists($_FILES["file"]["tmp_name"])){
						$tmpname = $_FILES["file"]["tmp_name"];
						$name = $_FILES["file"]["name"];
		
						$dataString = file_get_contents($tmpname);
						$encodedfile = base64_encode($dataString);
						
						$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Rate Master','Customer Rate','Updated','RateContract file has been updated','$username',getdate())");   
		
						$db->query("INSERT INTO contract_files (
						comp_code
						,br_code
						,cust_code
						,contract_files
						,file_name
						,date_modified
						,date_added) VALUES
						 (
						 '$stncode'
						 ,'$br_code'
						 ,'$cust_code'
						 ,'$encodedfile'
						 ,'$name'
						 ,getdate()
						 ,getdate()
						 )");
						}

				 header("location: customerlist?success=1"); 
				 }
				 
			    }
			 }
?>