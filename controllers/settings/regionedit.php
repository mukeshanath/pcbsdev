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

      class regionedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - regionedit';
              $this->view->render('settings/regionedit');
          }
      }

          $errors = array();

          if(isset($_POST['updateregion']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $regid         = $_POST['regid'];
                $regioncode    = $_POST['regioncode'];
                $regionname    = $_POST['regionname'];
                $statenames    = implode(', ', $_POST['statenames']);
                $reg_active    = $_POST['active'];
                $username      = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
               $sql = "UPDATE region set regioncode = '".$regioncode."',
                                        regionname = '".$regionname."',
                                        statenames = '".$statenames."',
                                        active = '".$reg_active."',
                                        user_name = '".$username."',
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE regid='".$regid."' ";

              $result  = $db->query($sql);
              
               header( 'Location: regionlist?success=2');
              
              
             }
?>
