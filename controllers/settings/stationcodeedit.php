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

      class stationcodeedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - stationcodeedit';
              $this->view->render('settings/stationcodeedit');
          }
      }

          $errors = array();
          
          if(isset($_POST['updatestncode']))
          {
            alterstncode($db);
          }
            function alterstncode($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $stnid   = $_POST['stnid'];
                $stncode = $_POST['stncode'];
                $stnname = $_POST['stnname'];
                $state   = $_POST['state'];
                $stname  = $_POST['stname'];
                $coun_code = $_POST['coun_code'];
                $coun_name = $_POST['coun_name'];
                $username = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($stncode)){
                array_push($errors, "please enter pincode");
              }

              
            
            if (count($errors) == 0) 
              {
              $sql = "UPDATE stncode set stncode = '".$stncode."',
                                           stnname = '".$stnname."', 
                                           state = '".$state."', 
                                           stname = '".$stname."', 
                                           coun_code = '".$coun_code."', 
                                           coun_name = '".$coun_name."', 
                                           user_name = '".$username."',                                       
                                           date_modified = '".$date_modified."'
                                        
                                        WHERE stnid='".$stnid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: stationcodelist?success=2"); 
             }
?>
