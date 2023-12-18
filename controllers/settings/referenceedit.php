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

      class referenceedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - referenceedit';
              $this->view->render('settings/referenceedit');
          }
      }

          $errors = array();

          if(isset($_POST['updatereference']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $rid      = $_POST['rid'];
                $ref_code = $_POST['ref_code'];
                $ref_type = $_POST['ref_type'];
                $username   = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($ref_code)){
                array_push($errors, "please enter people code");
              }

              if(empty($ref_type)){
                array_push($errors, "please enter people type");
              }

              
            if (count($errors) == 0) 
              {
             $sql = "UPDATE reference set ref_code = '".$ref_code."',
                                        ref_type = '".$ref_type."',
                                        user_name = '".$username."',
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE rid='".$rid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: referencelist?success=2"); 
             }
?>
