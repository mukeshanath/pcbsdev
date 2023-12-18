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

      class peopleedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - peopleedit';
              $this->view->render('masters/peopleedit');
          }
      }

         

          if(isset($_POST['updatepeopleexe']))
          {
              alterpeopleexec($db);
          }
            function alterpeopleexec($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_modified               	= date('Y-m-d H:i:s');      

                $staff_id                   = $_POST['staff_id'];
                $stncode                    = $_POST['comp_code'];
                $stnname                    = $_POST['comp_name'];
                $br_code                    = $_POST['br_code'];
                $br_name                    = $_POST['br_name'];
                $people_type                = $_POST['people_type'];
                $exe_code                   = $_POST['exe_code'];
                $exe_name                   = $_POST['exe_name'];
                $exe_add		            = $_POST['exe_add'];
                $exe_city   	         	= $_POST['exe_city'];
                $exe_state  	        	= $_POST['exe_state'];
                $exe_country   	         	= $_POST['exe_country'];           
                $exe_mobile		          	= $_POST['exe_mobile'];            
                $exe_doj		           	= $_POST['exe_doj']; 
                $exe_email		            = $_POST['exe_email'];
                $exe_qualification	      	= $_POST['exe_qualification'];
                $exe_remarks	        	= $_POST['remarks'];
                $exe_status		         	= $_POST['active'];
                $user_name		            = $_POST['user_name'];
                
           
            
              $sql = "UPDATE staff set comp_code = '".$stncode."',
                                        comp_name = '".$stnname."',
                                        br_code = '".$br_code."',
                                        br_name = '".$br_name."',
                                        people_type = '".$people_type."',
                                        exe_code = '".$exe_code."',
                                        exe_name = '".$exe_name."',
                                        exe_add = '".$exe_add."',
                                        exe_city = '".$exe_city."',
                                        exe_state = '".$exe_state."',
                                        exe_country = '".$exe_country."',
                                        exe_mobile = '".$exe_mobile."',
                                        exe_doj = '".$exe_doj."',
                                        exe_qualification = '".$exe_qualification."',
                                        remarks = '".$exe_remarks."',
                                        active = '".$exe_status."',
                                        user_name = '".$user_name."',
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE staff_id='".$staff_id."' ";
              $result  = $db->query($sql);
             
              header("location: peoplelist?success=3"); 
             }
?>
