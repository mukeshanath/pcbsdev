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

      class destinationedit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - destinationedit';
              $this->view->render('masters/destinationedit');
          }
      }

     

          
      if(isset($_POST['updatedestination']))
      {
        alterdestination($db);
      }

      function alterdestination($db)  
        {
            date_default_timezone_set('Asia/Kolkata');
               $zone_destid      = $_POST['zone_destid'];
              $comp_code      = $_POST['comp_code'];
              $dest_code      = $_POST['dest_code']; 
              $dest_name      = $_POST['dest_name']; 
              $state          = $_POST['state'];
              $stname         = $_POST['stname'];
              $zone_a         = explode('-',$_POST['zonecode_a']);
              $zone_b         = explode('-',$_POST['zonecode_b']);
              $zone_c         = explode('-',$_POST['zonecode_c']);
              $zone_casharr   = explode('-',$_POST['zone_cash']);
              $zone_tsarr     = explode('-',$_POST['zone_ts']);
              $zonecode_a     = trim($zone_a[0]);
              $zonecode_b     = trim($zone_b[0]);
              $zonecode_c     = trim($zone_c[0]);
              $zone_cash      = trim($zone_casharr[0]);
              $zone_ts        = trim($zone_tsarr[0]);              
              $hub1           = $_POST['hub1'];
              $hub2           = $_POST['hub2'];
              $username       = $_POST['user_name'];
              $date_added = date('Y-m-d H:i:s');
            
       
          $sql = "UPDATE destination set 
                                     
                                    dest_name = '".$dest_name."',
                                    state = '".$state."',
                                    stname = '".$stname."',  
                                    zonecode_a = '".$zonecode_a."', 
                                    zonecode_b = '".$zonecode_b."', 
                                    zonecode_c = '".$zonecode_c."', 
                                    zone_cash = '".$zone_cash."', 
                                    zone_ts = '".$zone_ts."', 
                                    hub1 = '".$hub1."', 
                                    hub2 = '".$hub2."', 
                                    user_name = '".$username."',                                   
                                    date_modified = '".$date_added."'
                                    
                                    WHERE zone_destid='".$zone_destid."' AND comp_code = '".$comp_code."'";
          $result  = $db->query($sql);
          
          $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) 
          VALUES  ('Master','Destination','Modified','DestCode $dest_code has been Modified in Station $comp_code','$username',getdate())");

          header("location: destinationlist?success=2"); 
         }

      ?>