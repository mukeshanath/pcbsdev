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

      class statusedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - statusedit';
              $this->view->render('settings/statusedit');
          }
      }

          $errors = array();

          if(isset($_POST['updatestatus']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $sid       = $_POST['sid'];
                $stat_code = $_POST['stat_code'];
                $stat_type = $_POST['stat_type'];
                $username = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($stat_code)){
                array_push($errors, "please enter people code");
              }

              if(empty($stat_type)){
                array_push($errors, "please enter people type");
              }

              
            if (count($errors) == 0) 
              {
            $sql = "UPDATE status set stat_code = '".$stat_code."',
                                        stat_type = '".$stat_type."',
                                        user_name = '".$username."',
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE sid='".$sid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: statuslist?success=2"); 
             }
?>
