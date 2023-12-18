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

      class vendorsedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - vendorsedit';
              $this->view->render('masters/vendorsedit');
          }
      }

         

          if(isset($_POST['updatevendors']))
          {
              altervendor($db);
          }
            function altervendor($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $vend_id                    = $_POST['vend_id'];
                $stncode                    = $_POST['stncode'];
                $stnname                    = $_POST['stnname'];
                $vendor_code                = $_POST['vendor_code'];
                $vendor_name                = $_POST['vendor_name'];
                $vendor_add				    = $_POST['vendor_add'];
                $vendor_city   			   	=	$_POST['vendor_city'];
                $vendor_state  			  	=	$_POST['vendor_state'];
                $vendor_country   	      	=	$_POST['vendor_country'];
                $vendor_phone			   	=	$_POST['vendor_phone'];
                $vendor_mobile			   	=	$_POST['vendor_mobile'];            
                $vendor_fax				    =	$_POST['vendor_fax'];
                $vendor_email			    =	$_POST['vendor_email'];
                $vendor_contactperson	  	=	$_POST['vendor_contactperson'];
                $vendor_remarks			   	=	$_POST['vendor_remarks'];
                $vendor_status			   	=	$_POST['vendor_status'];
                $username                   = $_POST['user_name'];
                $date_modified              = date('Y-m-d H:i:s');
                
           
            
            
            
              $sql = "UPDATE vendortb set stncode = '".$stncode."',
                                        stnname = '".$stnname."',
                                        vendor_code = '".$vendor_code."',
                                        vendor_name = '".$vendor_name."',
                                        vendor_add = '".$vendor_add."',
                                        vendor_city = '".$vendor_city."',
                                        vendor_state = '".$vendor_state."',
                                        vendor_country = '".$vendor_country."',
                                        vendor_phone = '".$vendor_phone."',
                                        vendor_mobile = '".$vendor_mobile."',
                                        vendor_fax = '".$vendor_fax."',
                                        vendor_email = '".$vendor_email."',
                                        vendor_contactperson = '".$vendor_contactperson."',
                                        vendor_remarks = '".$vendor_remarks."',
                                        vendor_status = '".$vendor_status."',
                                        user_name = '".$username."',
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE vend_id='".$vend_id."' ";
              $result  = $db->query($sql);
             
              header("location: vendorslist?success=3"); 
             }
?>
