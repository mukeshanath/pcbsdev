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

      class item extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - item';
              $this->view->render('masters/itemtype');
          }
      }

          $errors = array();

          if(isset($_POST['additem']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $item_code = $_POST['item_code'];
                $item_name = $_POST['item_name'];
                $spl_rate  = $_POST['spl_rate'];
                $oth_chrg  = $_POST['oth_chrg'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
            if(empty($item_code)){
                array_push($errors, "please enter item code");
              }

              if(empty($item_name)){
                array_push($errors, "please enter item name");
              }

              if(empty($spl_rate)){
                array_push($errors, "please enter special rate");
              }

              if(empty($oth_chrg)){
                array_push($errors, "please enter Other charges");
              }

              
            if (count($errors) == 0) 
              {
             $sql = "INSERT INTO item (item_code,item_name,spl_rate,oth_chrg,user_name,date_added) VALUES ('$item_code','$item_name','$spl_rate','$oth_chrg','$username','$date_added')";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: itemlist?success=1"); 
             }

             if(isset($_POST['addanitem']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $item_code = $_POST['item_code'];
                $item_name = $_POST['item_name'];
                $spl_rate  = $_POST['spl_rate'];
                $oth_chrg  = $_POST['oth_chrg'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
            if(empty($item_code)){
                array_push($errors, "please enter item code");
              }

              if(empty($item_name)){
                array_push($errors, "please enter item name");
              }

              if(empty($spl_rate)){
                array_push($errors, "please enter special rate");
              }

              if(empty($oth_chrg)){
                array_push($errors, "please enter Other charges");
              }

              
            if (count($errors) == 0) 
              {
             $sql = "INSERT INTO item (item_code,item_name,spl_rate,oth_chrg,user_name,date_added) VALUES ('$item_code','$item_name','$spl_rate','$oth_chrg','$username','$date_added')";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: item?success=1"); 
             }
?>
