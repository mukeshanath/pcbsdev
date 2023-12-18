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

      class stateedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - stateedit';
              $this->view->render('settings/stateedit');
          }
      }

         

          $errors = array();

          if(isset($_POST['updatestate']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $stid       = $_POST['stid'];
                $statecode  = $_POST['statecode'];
                $state_name = $_POST['state_name'];
                $username = $_POST['user_name'];

                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($statecode)){
                array_push($errors, "please enter state");
              }

              if(empty($state_name)){
                array_push($errors, "please enter state name");
              }

              
            if (count($errors) == 0) 
              {
              $sql = "UPDATE state set statecode = '".$statecode."',
                                        state_name = '".$state_name."',
                                        user_name = '".$username."',
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE stid='".$stid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: statelist?success=2"); 
             }
?>
