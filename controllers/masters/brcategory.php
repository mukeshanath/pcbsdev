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

      class brcategory extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - brcategory';
              $this->view->render('masters/brcategory');
          }
      }

          $errors = array();

          if(isset($_POST['addbrcategory']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $brcate_code = $_POST['brcate_code'];
                $brcate_name = $_POST['brcate_name'];
                $username = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
            if(empty($brcate_code)){
                array_push($errors, "please enter state");
              }

              if(empty($brcate_name)){
                array_push($errors, "please enter state name");
              }

              
            if (count($errors) == 0) 
              {
              $sql = "INSERT INTO brcategorytb (brcate_code,brcate_name,user_name,date_added) VALUES ('$brcate_code','$brcate_name','$username','$date_added')";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: brcategory?success=1"); 
             }
?>
