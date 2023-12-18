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

      class addressedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - addressedit';
              $this->view->render('settings/addressedit');
          }
      }

          $errors = array();

          if(isset($_POST['updateaddress']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $aid       = $_POST['aid'];
                $addr_code = $_POST['addr_code'];
                $addr_type = $_POST['addr_type'];
                $username   = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($addr_code)){
                array_push($errors, "please enter address code");
              }

              if(empty($addr_type)){
                array_push($errors, "please enter address type");
              }

              
            if (count($errors) == 0) 
              {
            $sql = "UPDATE address set  addr_code = '".$addr_code."',
                                          addr_type = '".$addr_type."',
                                          user_name = '".$username."',
                                          date_modified = '".$date_modified."'
                                        
                                        WHERE aid='".$aid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: address?success=2"); 
             }
?>
