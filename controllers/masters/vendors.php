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

      class vendors extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - vendors';
              $this->view->render('masters/vendors');
          }
      }

      if(isset($_POST['addvendors'])){
        savevendors($db);
      }

      function savevendors($db)  
        {
            date_default_timezone_set('Asia/Kolkata');
            $date_added               	= date('Y-m-d H:i:s');            
            $stncode                    = $_POST['stncode'];
            $stnname                    = $_POST['stnname'];
            $vendor_code                = $_POST['vendor_code'];
            $vendor_name                = $_POST['vendor_name'];
            $vendor_add				          =	$_POST['vendor_add'];
            $vendor_city   			      	=	$_POST['vendor_city'];
            $vendor_state  			      	=	$_POST['vendor_state'];
            $vendor_country   	      	=	$_POST['vendor_country'];
            $vendor_phone			        	=	$_POST['vendor_phone'];
            $vendor_mobile			      	=	$_POST['vendor_mobile'];            
            $vendor_fax				        	=	$_POST['vendor_fax'];
            $vendor_email			          =	$_POST['vendor_email'];
            $vendor_contactperson	    	=	$_POST['vendor_contactperson'];
            $vendor_remarks			      	=	$_POST['vendor_remarks'];
            $vendor_status			      	=	$_POST['vendor_status'];
            $username			              =	$_POST['user_name'];

            $sqlcheck = "SELECT * from vendor WHERE stncode='$stncode' AND vendor_code='$vendor_code' ";
            $sqlresult  = $db->query($sqlcheck);
    
            while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
            {
                $vendorcode = $row->vendor_code;
                $stn_code = $row->stncode;
            }

            if($sqlresult->rowCount() >= 0)
            {
              
              if ($stncode==$stn_code AND $vendor_code==$vendorcode)
                {
                  header("location: vendor?success=4"); 
                  } 
                  else {
                $sql = "INSERT INTO vendor (stncode,
                                            stnname,
                                            vendor_code,
                                            vendor_name,
                                            vendor_add,
                                            vendor_city,
                                            vendor_state,
                                            vendor_country,
                                            vendor_phone,
                                            vendor_mobile,
                                            vendor_fax,
                                            vendor_email,
                                            vendor_contactperson,
                                            vendor_remarks,
                                            vendor_status,          
                                            user_name,            
                                            date_added) VALUES ('$stncode',
                                                        '$stnname',
                                                        '$vendor_code',
                                                        '$vendor_name',
                                                        '$vendor_add',
                                                        '$vendor_city',
                                                        '$vendor_state',
                                                        '$vendor_country',
                                                        '$vendor_phone',                                             	
                                                        '$vendor_mobile',
                                                        '$vendor_fax,',
                                                        '$vendor_email',                                               
                                                        '$vendor_contactperson',                                       					  
                                                        '$vendor_remarks',                                   					  
                                                        '$vendor_status',                                    					  
                                                        '$user_name',                                     					  
                                                        '$date_added')";
              }
              if($result  = $db->query($sql))
              {
              header("location: vendorslist?success=1");
              exit; 
              }
              
            }
         }

         if(isset($_POST['addanvendors'])){
          saveanvendors($db);
        }
  
        function saveanvendors($db)  
          {
              date_default_timezone_set('Asia/Kolkata');
              $date_added               	= date('Y-m-d H:i:s');            
              $stncode                    = $_POST['stncode'];
              $stnname                    = $_POST['stnname'];
              $vendor_code                = $_POST['vendor_code'];
              $vendor_name                = $_POST['vendor_name'];
              $vendor_add				          =	$_POST['vendor_add'];
              $vendor_city   			      	=	$_POST['vendor_city'];
              $vendor_state  			      	=	$_POST['vendor_state'];
              $vendor_country   	      	=	$_POST['vendor_country'];
              $vendor_phone			        	=	$_POST['vendor_phone'];
              $vendor_mobile			      	=	$_POST['vendor_mobile'];            
              $vendor_fax				        	=	$_POST['vendor_fax'];
              $vendor_email			          =	$_POST['vendor_email'];
              $vendor_contactperson	    	=	$_POST['vendor_contactperson'];
              $vendor_remarks			      	=	$_POST['vendor_remarks'];
              $vendor_status			      	=	$_POST['vendor_status'];
              $username			              =	$_POST['user_name'];
  
              $sqlcheck = "SELECT * from vendor WHERE stncode='$stncode' AND vendor_code='$vendor_code' ";
              $sqlresult  = $db->query($sqlcheck);
      
              while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
              {
                  $vendorcode = $row->vendor_code;
                  $stn_code = $row->stncode;
              }
  
              if($sqlresult->rowCount() == 0)
              {
                
                if ($stncode==$stn_code AND $vendor_code==$vendorcode)
                  {
                    header("location: vendor?success=4"); 
                    } 
                    else {
                  $sql = "INSERT INTO vendor (stncode,
                                              stnname,
                                              vendor_code,
                                              vendor_name,
                                              vendor_add,
                                              vendor_city,
                                              vendor_state,
                                              vendor_country,
                                              vendor_phone,
                                              vendor_mobile,
                                              vendor_fax,
                                              vendor_email,
                                              vendor_contactperson,
                                              vendor_remarks,
                                              vendor_status,          
                                              user_name,            
                                              date_added) VALUES ('$stncode',
                                                          '$stnname',
                                                          '$vendor_code',
                                                          '$vendor_name',
                                                          '$vendor_add',
                                                          '$vendor_city',
                                                          '$vendor_state',
                                                          '$vendor_country',
                                                          '$vendor_phone',                                             	
                                                          '$vendor_mobile',
                                                          '$vendor_fax,',
                                                          '$vendor_email',                                               
                                                          '$vendor_contactperson',                                       					  
                                                          '$vendor_remarks',                                   					  
                                                          '$vendor_status',                                    					  
                                                          '$user_name',                                     					  
                                                          '$date_added')";
                }
                if($result  = $db->query($sql))
                {
                header("location: vendors?success=1"); 
                exit;
                }
                
              }
           }
          
?>
