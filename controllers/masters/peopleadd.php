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

      class peopleadd extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - peopleadd';
              $this->view->render('masters/peopleadd');
          }
      }

      if(isset($_POST['addpeopleexe'])){
        savepeople($db);
      }

      function savepeople($db)  
        {
            date_default_timezone_set('Asia/Kolkata');
            $date_added               	= date('Y-m-d H:i:s');      

            $stncode                    = $_POST['comp_code'];
            $stnname                    = $_POST['comp_name'];
            $people_type                = $_POST['people_type'];
            $exe_code                   = $_POST['exe_code'];
            $exe_name                   = $_POST['exe_name'];
            $exe_add				            =	$_POST['exe_add'];
            $exe_city   			         	=	$_POST['exe_city'];
            $exe_state  			        	=	$_POST['exe_state'];
            $exe_country   	          	=	$_POST['exe_country'];           
            $exe_mobile			          	=	$_POST['exe_mobile'];            
            $exe_doj			            	=	$_POST['exe_doj']; 
            $exe_email			            =	$_POST['exe_email'];
            $exe_qualification	      	=	$_POST['exe_qualification'];
            $exe_remarks			        	=	$_POST['remarks'];
            $exe_status			          	=	$_POST['active'];
            $user_name			            =	$_POST['user_name'];

            $duplicate_check = $db->query("SELECT * FROM staff WHERE exe_code='$exe_code'");
            if($duplicate_check->rowCount()==0)
              {
                $sql = "INSERT INTO staff (comp_code,
                                            comp_name,
                                            people_type,
                                            exe_code,
                                            exe_name,
                                            exe_add,
                                            exe_city,
                                            exe_state,
                                            exe_country,
                                            exe_mobile,
                                            exe_doj,
                                            exe_email,
                                            exe_qualification,
                                            remarks,
                                            active,          
                                            user_name,            
                                            date_added) VALUES ('$stncode',
                                                        '$stnname',
                                                        '$people_type',  
                                                        '$exe_code',
                                                        '$exe_name',
                                                        '$exe_add',
                                                        '$exe_city',
                                                        '$exe_state',
                                                        '$exe_country',                                                                                                  	
                                                        '$exe_mobile',                                                        
                                                        '$exe_doj', 
                                                        '$exe_email',                                               
                                                        
                                                        '$exe_qualification',                                      					  
                                                        '$exe_remarks',                                   					  
                                                        '$exe_status',                                    					  
                                                        '$user_name',                                     					  
                                                        '$date_added')";
              
              if($result  = $db->query($sql))
              {
              header("location: peoplelist?success=1");
              }
              }
              else{
                header("location: peopleadd?unsuccess=4"); 
              }
         }

         if(isset($_POST['addanpeopleexe'])){
          saveanpeople($db);
        }
  
        function saveanpeople($db)  
          {
            date_default_timezone_set('Asia/Kolkata');
            $date_added               	= date('Y-m-d H:i:s');      

            $stncode                    = $_POST['comp_code'];
            $stnname                    = $_POST['comp_name'];
            $people_type                = $_POST['people_type'];
            $exe_code                   = $_POST['exe_code'];
            $exe_name                   = $_POST['exe_name'];
            $exe_add				            =	$_POST['exe_add'];
            $exe_city   			         	=	$_POST['exe_city'];
            $exe_state  			        	=	$_POST['exe_state'];
            $exe_country   	          	=	$_POST['exe_country'];           
            $exe_mobile			          	=	$_POST['exe_mobile'];            
            $exe_doj			            	=	$_POST['exe_doj']; 
            $exe_email			            =	$_POST['exe_email'];
            $exe_qualification	      	=	$_POST['exe_qualification'];
            $exe_remarks			        	=	$_POST['remarks'];
            $exe_status			          	=	$_POST['active'];
            $user_name			            =	$_POST['user_name'];

            $duplicate_check = $db->query("SELECT * FROM staff WHERE exe_code='$exe_code'");
            if($duplicate_check->rowCount()==0)
              {
                $sql = "INSERT INTO staff (comp_code,
                                            comp_name,
                                            people_type,
                                            exe_code,
                                            exe_name,
                                            exe_add,
                                            exe_city,
                                            exe_state,
                                            exe_country,
                                            exe_mobile,
                                            exe_doj,
                                            exe_email,
                                            exe_qualification,
                                            remarks,
                                            active,          
                                            user_name,            
                                            date_added) VALUES ('$stncode',
                                                        '$stnname',
                                                        '$people_type',  
                                                        '$exe_code',
                                                        '$exe_name',
                                                        '$exe_add',
                                                        '$exe_city',
                                                        '$exe_state',
                                                        '$exe_country',                                                                                                  	
                                                        '$exe_mobile',                                                        
                                                        '$exe_doj', 
                                                        '$exe_email',                                               
                                                        '$exe_qualification',                                      					  
                                                        '$exe_remarks',                                   					  
                                                        '$exe_status',                                    					  
                                                        '$user_name',                                     					  
                                                        '$date_added')";
              
              if($result  = $db->query($sql))
              {
              header("location: peopleadd?success=1");
              }
              }
              else{
                header("location: peopleadd?unsuccess=4"); 
              }
         }
          
?>
