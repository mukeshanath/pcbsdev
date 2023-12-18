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

      class bookingcounter extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - bookingcounter';
              $this->view->render('masters/bookingcounter');
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
                $book_code	  	  	= $_POST['book_code'];
                $book_name		    	= $_POST['book_name'];
               // $book_addrtype	   	= $_POST['book_addrtype'];
                $book_addr 		     	= $_POST['book_addr'];
				$book_city 		     	= $_POST['book_city']; 
				$book_state 		   	= $_POST['book_state'];
				$book_country 		   	= $_POST['book_country'];
                $book_phone		     	= $_POST['book_phone'];
                $book_fax		       	= $_POST['book_fax'];
                $book_email		  	  = $_POST['book_email'];
                $c_person		      	= $_POST['c_person'];
                $book_remarks	    	= $_POST['book_remarks'];
                $book_active	     	= $_POST['book_active'];
                $proprietor         = $_POST['proprietor'];
                $book_gstin         = $_POST['book_gstin'];
                $book_tin           = $_POST['book_tin'];
                $book_fsc           = $_POST['book_fsc'];
                $book_deposit       = $_POST['book_deposit'];
                $cnote_charges      = $_POST['cnote_charges'];
                $percent_domscomsn  = $_POST['percent_domscomsn'];
                $percent_intlcomsn  = $_POST['percent_intlcomsn'];
                $pro_commission     = $_POST['pro_commission'];
                $spl_discount       = $_POST['spl_discount'];
                $deposit_date       = $_POST['deposit_date'];
                $username           = $_POST['user_name'];
                                
                
            
              $sql = "INSERT INTO bcounter (stncode,
              								  stnname,
              								  br_code,
              								  br_name,
              								  book_code,
              								  book_name,           							
              								  book_addr,
											  book_city,
											  book_state,
											  book_country,
              								  book_phone,              								
              								  book_fax,
              								  book_email,
              								  c_person,
              								  book_remarks,
											  book_active,
											  proprietor,
											  book_gstin,
											  book_tin,
											  book_fsc,
											  book_deposit,
											  cnote_charges,
											  percent_domscomsn,
											  percent_intlcomsn,
											  pro_commission,
											  spl_discount,
											  deposit_date,              								 
              								  date_added, user_name) VALUES ('$stncode',
              								  					  '$stnname',
              								  					  '$br_code',
              								  					  '$br_name',
              								  					  '$book_code',
              								  					  '$book_name',              								  					  
              								  					  '$book_addr',
																  '$book_city',  
																  '$book_state',
																  '$book_country',
              								  					  '$book_phone',
              								  					  '$book_fax',
              								  					  '$book_email',
              								  					  '$c_person',
              								  					  '$book_remarks',
              								  					  '$book_active',
              								  					  '$proprietor',
              								  					  '$book_gstin',
              								  					  '$book_tin',
              								  					  '$book_fsc',
              								  					  '$book_deposit',
              								  					  '$cnote_charges',
              								  					  '$percent_domscomsn',
              								  					  '$percent_intlcomsn',
              								  					  '$pro_commission',
              								  					  '$spl_discount',
              								  					  '$deposit_date',      								  					               								  					  					  					  
              								  					  '$date_added',
                                                                  '$username')";
              $result  = $db->query($sql);

              
              header("location: bookingcounterlist?success=1"); 
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
				  $book_code	  	  	= $_POST['book_code'];
				  $book_name		    	= $_POST['book_name'];
				 // $book_addrtype	   	= $_POST['book_addrtype'];
				  $book_addr 		     	= $_POST['book_addr'];
				  $book_city 		     	= $_POST['book_city']; 
				  $book_state 		   	= $_POST['book_state'];
				  $book_country 		   	= $_POST['book_country'];
				  $book_phone		     	= $_POST['book_phone'];
				  $book_fax		       	= $_POST['book_fax'];
				  $book_email		  	  = $_POST['book_email'];
				  $c_person		      	= $_POST['c_person'];
				  $book_remarks	    	= $_POST['book_remarks'];
				  $book_active	     	= $_POST['book_active'];
				  $proprietor         = $_POST['proprietor'];
				  $book_gstin         = $_POST['book_gstin'];
				  $book_tin           = $_POST['book_tin'];
				  $book_fsc           = $_POST['book_fsc'];
				  $book_deposit       = $_POST['book_deposit'];
				  $cnote_charges      = $_POST['cnote_charges'];
				  $percent_domscomsn  = $_POST['percent_domscomsn'];
				  $percent_intlcomsn  = $_POST['percent_intlcomsn'];
				  $pro_commission     = $_POST['pro_commission'];
				  $spl_discount       = $_POST['spl_discount'];
				  $deposit_date       = $_POST['deposit_date'];
				  $username           = $_POST['user_name'];
								  
				  
			  if(empty($c_person)){
				  array_push($errors, "please enter state");
				}
  
			   
				
			  if (count($errors) == 0) 
				{
				$sql = "INSERT INTO bcounter (stncode,
												  stnname,
												  br_code,
												  br_name,
												  book_code,
												  book_name,              							
												  book_city,
												book_state,
												book_country,
												  book_addr,
												  book_phone,              								
												  book_fax,
												  book_email,
												  c_person,
												  book_remarks,
												book_active,
												proprietor,
												book_gstin,
												book_tin,
												book_fsc,
												book_deposit,
												cnote_charges,
												percent_domscomsn,
												percent_intlcomsn,
												pro_commission,
												spl_discount,
												deposit_date,              								 
												  date_added, user_name) VALUES ('$stncode',
																		'$stnname',
																		'$br_code',
																		'$br_name',
																		'$book_code',
																		'$book_name',              								  					  
																		'$book_addr',
																	'$book_city',  
																	'$book_state',
																	'$book_country',
																		'$book_phone',
																		'$book_fax',
																		'$book_email',
																		'$c_person',
																		'$book_remarks',
																		'$book_active',
																		'$proprietor',
																		'$book_gstin',
																		'$book_tin',
																		'$book_fsc',
																		'$book_deposit',
																		'$cnote_charges',
																		'$percent_domscomsn',
																		'$percent_intlcomsn',
																		'$pro_commission',
																		'$spl_discount',
																		'$deposit_date',      								  					               								  					  					  					  
																		'$date_added',
											  '$username')";
				$result  = $db->query($sql);
  
				}
				else
				{
				 echo "data not added";
				}
				header("location: bookingcounter?success=1"); 
			   }

?>