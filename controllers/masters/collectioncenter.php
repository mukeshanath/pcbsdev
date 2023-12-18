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

      class collectioncenter extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - collectioncenter';
              $this->view->render('masters/collectioncenter');
          }
      }


      $errors = array();

          if(isset($_POST['addcounter'])){
			  savecounter($db);
		  }

		  	function savecounter($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_added      	= date('Y-m-d H:i:s');       

                $stncode 	    	   	= $_POST['stncode'];
                $stnname		       	= $_POST['stnname'];
                $br_code		      	= $_POST['br_code'];
                $br_name		      	= $_POST['br_name'];
                $cc_code	  	  	= $_POST['cc_code'];
                $cc_name		    	= $_POST['cc_name'];
               // $cc_addrtype	   	= $_POST['cc_addrtype'];
                $cc_addr 		     	= $_POST['cc_addr'];
				$cc_city 		     	= $_POST['cc_city']; 
				$cc_state 		   	= $_POST['cc_state'];
				$cc_country 		   	= $_POST['cc_country'];
                $cc_phone		     	= $_POST['cc_phone'];
                $cc_fax		       	= $_POST['cc_fax'];
                $cc_email		  	  = $_POST['cc_email'];
                $c_person		      	= $_POST['c_person'];
                $cc_remarks	    	= $_POST['cc_remarks'];
                $cc_active	     	= $_POST['cc_active'];
                $proprietor         = $_POST['proprietor'];
                $cc_gstin         = $_POST['cc_gstin'];
                $cc_tin           = $_POST['cc_tin'];
                $cc_fsc           = $_POST['cc_fsc'];
                $cc_deposit       = $_POST['cc_deposit'];
                $cnote_charges      = $_POST['cnote_charges'];
                $percent_domscomsn  = $_POST['percent_domscomsn'];
                $percent_intlcomsn  = $_POST['percent_intlcomsn'];
                $pro_commission     = $_POST['pro_commission'];
                $spl_discount       = $_POST['spl_discount'];
                $deposit_date       = $_POST['deposit_date'];
                $username           = $_POST['user_name'];
				$baccname = $_POST['baccname'];                 
                $baccno = $_POST['baccno'];
				$bankname  = $_POST['bankname'];
				$bbranch  = $_POST['bbranch'];
                $bacctype = $_POST['bacctype'];
				$ifsc = $_POST['ifsc'];
                $micr  = $_POST['micr'];
				$swift = $_POST['swift'];
                $account_id = $_POST['account_id'];
                                
            
              $sql = "INSERT INTO collectioncenter (stncode,
              								  stnname,
              								  br_code,
              								  br_name,
              								  cc_code,
              								  cc_name,           							
              								  cc_addr,
											  cc_city,
											  cc_state,
											  cc_country,
              								  cc_phone,              								
              								  cc_fax,
              								  cc_email,
              								  c_person,
              								  cc_remarks,
											  cc_active,
											  proprietor,
											  cc_gstin,
											  cc_tin,
											  cc_fsc,
											  cc_deposit,
											  cnote_charges,
											  percent_domscomsn,
											  percent_intlcomsn,
											  pro_commission,
											  spl_discount,
											  deposit_date,              								 
              								  date_added,
											  user_name,
											  bank_accname,
											  bank_acc_no,
											  bank_name, 
											  bank_branch, 
											  account_type,  
											  ifsc, 
											  micr,  
											  pancardno,
											  acc_id) VALUES ('$stncode',
              								  					  '$stnname',
              								  					  '$br_code',
              								  					  '$br_name',
              								  					  '$cc_code',
              								  					  '$cc_name',              								  					  
              								  					  '$cc_addr',
																  '$cc_city',  
																  '$cc_state',
																  '$cc_country',
              								  					  '$cc_phone',
              								  					  '$cc_fax',
              								  					  '$cc_email',
              								  					  '$c_person',
              								  					  '$cc_remarks',
              								  					  '$cc_active',
              								  					  '$proprietor',
              								  					  '$cc_gstin',
              								  					  '$cc_tin',
              								  					  '$cc_fsc',
              								  					  '$cc_deposit',
              								  					  '$cnote_charges',
              								  					  '$percent_domscomsn',
              								  					  '$percent_intlcomsn',
              								  					  '$pro_commission',
              								  					  '$spl_discount',
              								  					  '$deposit_date',      								  					               								  					  					  					  
              								  					  '$date_added',
																  '$username',
																  '$baccname',
																  '$baccno',
																  '$bankname',
																  '$bbranch',
																  '$bacctype',
																  '$ifsc',
																  '$micr',
																  '$swift',
																  '$account_id')";
              $result  = $db->query($sql);

              
              header("location: collectioncenterlist?success=1"); 
			 }
			 
			 if(isset($_POST['addancounter'])){
				saveancounter($db);
			}
  
				function saveancounter($db)
			  {
				  date_default_timezone_set('Asia/Kolkata');
				  $date_added         = date('Y-m-d H:i:s');
				  
  
				  $stncode 	    	   	= $_POST['stncode'];
				  $stnname		       	= $_POST['stnname'];
				  $br_code		      	= $_POST['br_code'];
				  $br_name		      	= $_POST['br_name'];
				  $cc_code	  	  	= $_POST['cc_code'];
				  $cc_name		    	= $_POST['cc_name'];
				 // $cc_addrtype	   	= $_POST['cc_addrtype'];
				  $cc_addr 		     	= $_POST['cc_addr'];
				  $cc_city 		     	= $_POST['cc_city']; 
				  $cc_state 		   	= $_POST['cc_state'];
				  $cc_country 		   	= $_POST['cc_country'];
				  $cc_phone		     	= $_POST['cc_phone'];
				  $cc_fax		       	= $_POST['cc_fax'];
				  $cc_email		  	  = $_POST['cc_email'];
				  $c_person		      	= $_POST['c_person'];
				  $cc_remarks	    	= $_POST['cc_remarks'];
				  $cc_active	     	= $_POST['cc_active'];
				  $proprietor         = $_POST['proprietor'];
				  $cc_gstin         = $_POST['cc_gstin'];
				  $cc_tin           = $_POST['cc_tin'];
				  $cc_fsc           = $_POST['cc_fsc'];
				  $cc_deposit       = $_POST['cc_deposit'];
				  $cnote_charges      = $_POST['cnote_charges'];
				  $percent_domscomsn  = $_POST['percent_domscomsn'];
				  $percent_intlcomsn  = $_POST['percent_intlcomsn'];
				  $pro_commission     = $_POST['pro_commission'];
				  $spl_discount       = $_POST['spl_discount'];
				  $deposit_date       = $_POST['deposit_date'];
				  $username           = $_POST['user_name'];
				  $baccname = $_POST['baccname'];                 
				  $baccno = $_POST['baccno'];
				  $bankname  = $_POST['bankname'];
				  $bbranch  = $_POST['bbranch'];
				  $bacctype = $_POST['bacctype'];
				  $ifsc = $_POST['ifsc'];
				  $micr  = $_POST['micr'];
				  $swift = $_POST['swift'];
				  $account_id = $_POST['account_id'];				 
				  
			  if(empty($c_person)){
				  array_push($errors, "please enter state");
				}
  
			   
				
			  if (count($errors) == 0) 
				{
				$sql = "INSERT INTO collectioncenter (stncode,
												  stnname,
												  br_code,
												  br_name,
												  cc_code,
												  cc_name,              							
												  cc_city,
												cc_state,
												cc_country,
												  cc_addr,
												  cc_phone,              								
												  cc_fax,
												  cc_email,
												  c_person,
												  cc_remarks,
												cc_active,
												proprietor,
												cc_gstin,
												cc_tin,
												cc_fsc,
												cc_deposit,
												cnote_charges,
												percent_domscomsn,
												percent_intlcomsn,
												pro_commission,
												spl_discount,
												deposit_date,              								 
												date_added,
												user_name,
												bank_accname,
											  bank_acc_no,
											  bank_name, 
											  bank_branch, 
											  account_type,  
											  ifsc, 
											  micr,  
											  pancardno,
											  acc_id) VALUES ('$stncode',
																		'$stnname',
																		'$br_code',
																		'$br_name',
																		'$cc_code',
																		'$cc_name',              								  					  
																		'$cc_addr',
																	'$cc_city',  
																	'$cc_state',
																	'$cc_country',
																		'$cc_phone',
																		'$cc_fax',
																		'$cc_email',
																		'$c_person',
																		'$cc_remarks',
																		'$cc_active',
																		'$proprietor',
																		'$cc_gstin',
																		'$cc_tin',
																		'$cc_fsc',
																		'$cc_deposit',
																		'$cnote_charges',
																		'$percent_domscomsn',
																		'$percent_intlcomsn',
																		'$pro_commission',
																		'$spl_discount',
																		'$deposit_date',      								  					               								  					  					  					  
																		'$date_added',
																		'$username',
											                       '$baccname',
																  '$baccno',
																  '$bankname',
																  '$bbranch',
																  '$bacctype',
																  '$ifsc',
																  '$micr',
																  '$swift',
																  '$account_id')";
				$result  = $db->query($sql);
  
				}
				else
				{
				 echo "data not added";
				}
				header("location: collectioncenter?success=1"); 
			   }

?>