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

      class pincodeedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - pincodeedit';
              $this->view->render('settings/pincodeedit');
          }
      }

          $errors = array();

          if(isset($_POST['updatepincode']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $pid     = $_POST['pid'];
                $pincode = $_POST['pincode'];
                $city_name = $_POST['city_name'];
                $dest_code = $_POST['dest_code'];
                $username  = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($pincode)){
                array_push($errors, "please enter pincode");
              }

              if(empty($city_name)){
                array_push($errors, "please enter city name");
              }

              if(empty($dest_code)){
                array_push($errors, "please enter destination code");
              }
            
            if (count($errors) == 0) 
              {
              $sql = "UPDATE pincode set pincode = '".$pincode."',
                                        city_name = '".$city_name."',
                                        dest_code = '".$dest_code."',
                                        user_name = '".$username."',
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE pid='".$pid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: pincodelist?success=2"); 
             }
?>
