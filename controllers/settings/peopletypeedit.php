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

      class peopletypeedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - peopletypeedit';
              $this->view->render('settings/peopletypeedit');
          }
      }

          $errors = array();

          if(isset($_POST['updatepeople']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $plid     = $_POST['plid'];
                $ple_code = $_POST['ple_code'];
                $ple_type = $_POST['ple_type'];
                $username   = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($ple_code)){
                array_push($errors, "please enter people code");
              }

              if(empty($ple_type)){
                array_push($errors, "please enter people type");
              }

              
            if (count($errors) == 0) 
              {
            $sql = "UPDATE people set ple_code = '".$ple_code."',
                                        ple_type = '".$ple_type."', 
                                        user_name = '".$username."',                                     
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE plid='".$plid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: peopletypelist?success=2"); 
             }
?>
