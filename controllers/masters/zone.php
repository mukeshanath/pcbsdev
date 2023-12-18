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

      class zone extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - zone';
              $this->view->render('masters/zone');
          }
      }

          if(isset($_POST['addzone']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $comp_code      = $_POST['comp_code'];               
                $zone_type      = $_POST['zone_type'];
                $zone_code      = $_POST['zone_code'];
                $zone_name      = $_POST['zone_name'];
                $username   =  $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');

		            $sqlcheck = "SELECT * from zone WHERE comp_code='$comp_code' AND zone_type='$zone_type' AND zone_code='$zone_code'";
                $sqlresult  = $db->query($sqlcheck);
    
            while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
            {
                
                $compcode = $row->comp_code;
	              $zonetype = $row->zone_type;
		            $zonecode = $row->zone_code;
            } 
		        if($sqlresult->rowCount() >= 0)
            {
              
              if ($comp_code==$compcode AND $zone_type==$zonetype AND $zone_code==$zonecode )
                {
                  header("location: zone?success=4"); 
                  } 
                  else {


                $sql = "INSERT INTO zone (comp_code,zone_type,zone_code,zone_name,user_name,date_added) 
                VALUES ('$comp_code','$zone_type','$zone_code','$zone_name','$username','$date_added')";
                }
              if($result  = $db->query($sql))
              {
              header("location: zonelist?success=1");
              exit; 
              }
              
            }
         }
         //Addan Another
          if(isset($_POST['addananotherzone']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $comp_code      = $_POST['comp_code'];               
                $zone_type      = $_POST['zone_type'];
                $zone_code      = $_POST['zone_code'];
                $zone_name      = $_POST['zone_name'];
                $username   =  $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');

		            $sqlcheck = "SELECT * from zone WHERE comp_code='$comp_code' AND zone_type='$zone_type' AND zone_code='$zone_code'";
                $sqlresult  = $db->query($sqlcheck);
    
            while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
            {
                
                $compcode = $row->comp_code;
	              $zonetype = $row->zone_type;
		            $zonecode = $row->zone_code;
            } 
		        if($sqlresult->rowCount() >= 0)
            {
              
              if ($comp_code==$compcode AND $zone_type==$zonetype AND $zone_code==$zonecode )
                {
                  header("location: zone?success=4"); 
                  } 
                  else {


                $sql = "INSERT INTO zone (comp_code,zone_type,zone_code,zone_name,user_name,date_added) 
                VALUES ('$comp_code','$zone_type','$zone_code','$zone_name','$username','$date_added')";
                }
              if($result  = $db->query($sql))
              {
              header("location: zone?success=1");
              exit; 
              }
              
            }
         }
?>
