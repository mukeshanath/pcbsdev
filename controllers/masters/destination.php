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

      class destination extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - destination';
              $this->view->render('masters/destination');
          }
      }

           if(isset($_POST['adddestination']))
           {
             savedestination($db);
           }


           function savedestination($db) 
            {
              date_default_timezone_set('Asia/Kolkata');
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
              $zone_ts        = trim($zone_casharr[0]);              
              $hub1           = $_POST['hub1'];
              $hub2           = $_POST['hub2'];
              $date_added = date('Y-m-d H:i:s');


             $sqlcheck = "SELECT * from destination WHERE comp_code='$comp_code' AND dest_code='$dest_code' ";
             $sqlresult  = $db->query($sqlcheck);

             if($sqlresult->rowCount() == 0)
             {
             //Zones Validation
             $zonecodeA_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_a' AND zone_code NOT LIKE 'B%' AND zone_code NOT LIKE 'C%' AND comp_code='$comp_code' AND zone_type='credit'");
             $zonecodeB_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_b' AND comp_code='$comp_code' AND zone_type='credit' AND zone_code LIKE 'B%'");
             $zonecodeC_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_c' AND comp_code='$comp_code' AND zone_type='credit' AND zone_code LIKE 'C%'");
             $zonecash_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_a' AND comp_code='$comp_code' AND zone_type='credit'");
             $zonets_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_a' AND comp_code='$comp_code' AND zone_type='credit'");
             $dest_validation = $db->query("SELECT stncode FROM stncode WHERE stncode='$dest_code'");
             if($zonecodeA_validation->rowCount()==0 || $zonecodeB_validation->rowCount()==0 || $zonecodeC_validation->rowCount()==0 || $zonecash_validation->rowCount()==0 || $zonets_validation->rowCount()==0 || $dest_validation->rowCount()==0)
             {
              header("location: destination?unsuccess=4"); 
             }
             else{
               
             $sql = "INSERT INTO destination(comp_code,dest_code,dest_name,state,stname,zonecode_a,zonecode_b,zonecode_c,zone_cash,zone_ts,hub1,hub2,user_name, date_added)
             VALUES('$comp_code','$dest_code','$dest_name','$state','$stname','$zonecode_a','$zonecode_b','$zonecode_c','$zone_cash','$zone_ts','$hub1','$hub2','$username','$date_added')";
         
             if($result  = $db->query($sql))
               {
               header("location: destinationlist?success=1");
               exit; 
               }
             }
           }
           else{
           
               header("location: destination?unsuccess=4"); 
        }
        }

           if(isset($_POST['addandestination']))
           {
             saveandestination($db);
           }


           function saveandestination($db) 
            {
              date_default_timezone_set('Asia/Kolkata');
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
              $zone_ts        = trim($zone_casharr[0]);
              
              $hub1           = $_POST['hub1'];
              $hub2           = $_POST['hub2'];
              $date_added = date('Y-m-d H:i:s');


             $sqlcheck = "SELECT * from destination WHERE comp_code='$comp_code' AND dest_code='$dest_code' ";
             $sqlresult  = $db->query($sqlcheck);

             if($sqlresult->rowCount() == 0)
             {
             //Zones Validation
             $zonecodeA_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_a' AND zone_code NOT LIKE 'B%' AND zone_code NOT LIKE 'C%' AND comp_code='$comp_code' AND zone_type='credit'");
             $zonecodeB_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_b' AND comp_code='$comp_code' AND zone_type='credit' AND zone_code LIKE 'B%'");
             $zonecodeC_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_c' AND comp_code='$comp_code' AND zone_type='credit' AND zone_code LIKE 'C%'");
             $zonecash_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_a' AND comp_code='$comp_code' AND zone_type='credit'");
             $zonets_validation = $db->query("SELECT zone_code FROM zone WHERE zone_code='$zonecode_a' AND comp_code='$comp_code' AND zone_type='credit'");
             $dest_validation = $db->query("SELECT stncode FROM stncode WHERE stncode='$dest_code'");
             if($zonecodeA_validation->rowCount()==0 || $zonecodeB_validation->rowCount()==0 || $zonecodeC_validation->rowCount()==0 || $zonecash_validation->rowCount()==0 || $zonets_validation->rowCount()==0 || $dest_validation->rowCount()==0)
             {
              header("location: destination?unsuccess=4"); 
             }
             else{
               
             $sql = "INSERT INTO destination(comp_code,dest_code,dest_name,state,stname,zonecode_a,zonecode_b,zonecode_c,zone_cash,zone_ts,hub1,hub2,user_name, date_added)
             VALUES('$comp_code','$dest_code','$dest_name','$state','$stname','$zonecode_a','$zonecode_b','$zonecode_c','$zone_cash','$zone_ts','$hub1','$hub2','$username','$date_added')";
         
             if($result  = $db->query($sql))
               {
               header("location: destination?success=1");
               exit; 
               }
             }
           }
           else{
           
               header("location: destination?unsuccess=4"); 
        }
       }
          