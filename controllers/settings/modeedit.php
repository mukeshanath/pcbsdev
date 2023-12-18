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

      class modeedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - modeedit';
              $this->view->render('settings/modeedit');
          }
      }

          $errors = array();

          if(isset($_POST['updatemode']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $mid      = $_POST['mid'];
                $mode_code = $_POST['mode_code'];
                $mode_name = $_POST['mode_name'];
                $username   = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($mode_code)){
                array_push($errors, "please enter state");
              }

              if(empty($mode_code)){
                array_push($errors, "please enter state name");
              }

              
            if (count($errors) == 0) 
              {
              $sql = "UPDATE mode set mode_code = '".$mode_code."',
                                        mode_name = '".$mode_name."',  
                                        user_name = '".$username."',                                    
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE mid='".$mid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: modelist?success=2"); 
             }
?>
