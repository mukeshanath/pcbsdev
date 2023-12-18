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

      class itemedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - itemedit';
              $this->view->render('masters/itemedit');
          }
      }

          $errors = array();

          if(isset($_POST['updateitem']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $iid       = $_POST['iid'];
                $item_code = $_POST['item_code'];
                $item_name = $_POST['item_name'];
                $spl_rate  = $_POST['spl_rate'];
                $oth_chrg  = $_POST['oth_chrg'];
                $username   = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
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
            $sql = "UPDATE item set  item_code = '".$item_code."',
                                        item_name = '".$item_name."',
                                        spl_rate = '".$spl_rate."',
                                        oth_chrg = '".$oth_chrg."',
                                        user_name = '".$username."',
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE iid='".$iid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: item?success=2"); 
             }
?>
