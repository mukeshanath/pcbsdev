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
        //error_reporting(0);
      class zoneedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - zoneedit';
              $this->view->render('masters/zoneedit');
          }
      }

          $errors = array();


          if(isset($_POST['updatezone']))
            {
              // echo json_encode($_POST); exit;
              date_default_timezone_set('Asia/Kolkata');
              $znid           = $_POST['znid'];
              $comp_code      = $_POST['comp_code'];               
              $zone_type      = $_POST['zone_type'];
              $zone_code      = $_POST['zone_code'];
              $zone_name      = $_POST['zone_name'];
              $zone_number    = $_POST['zone_number'];
              $username       =  $_POST['user_name'];
              $date_modified  = date('Y-m-d H:i:s');

              try{

              $sql = "UPDATE zone set 
                                        zone_type = '".$zone_type."', 
                                        zone_code = '".$zone_code."',   
                                        zone_name = '".$zone_name."',										
                                        user_name = '".$username."',                                    
                                        date_modified = '".$date_modified."'
                                        
                                        WHERE znid='".$znid."' AND comp_code = '".$comp_code."'";
              $db->query($sql);
              }
              catch(PDOException $e){
                echo $e;exit;
              }
            //generate description for audit_log
            if($zone_type=='credit'){
              $destns_condition = " '$zone_code' IN (zonecode_a,zonecode_b,zonecode_c)";
            }
            else if($zone_type=='cash'){
              $destns_condition = " zone_cash='$zone_code'";
            }
            else if($zone_type=='ts'){
              $destns_condition = " zone_ts='$zone_code'";
            }

            $current_destination = [];
            $newadded_destination = [];
            $destns = $db->query("SELECT dest_code FROM destination WHERE $destns_condition");
            while($drow = $destns->fetch(PDO::FETCH_OBJ)){
              $current_destination[$drow->dest_code]=$drow->dest_code;
            }
            //generate description for audit_log
            
                                        if($zone_type=='credit'){
                                            //$db->query("UPDATE destination SET zonecode_a='' WHERE comp_code='$comp_code' AND zonecode_a='$zone_code'");
                                            //$db->query("UPDATE destination SET zonecode_b='' WHERE comp_code='$comp_code' AND zonecode_b='$zone_code'");
                                            //$db->query("UPDATE destination SET zonecode_c='' WHERE comp_code='$comp_code' AND zonecode_c='$zone_code'");
                                        }
                                        else if($zone_type=='cash'){
                                            //$db->query("UPDATE destination SET zone_cash='' WHERE comp_code='$comp_code' AND zone_cash='$zone_code'");
                                        }
                                        else{
                                            //$db->query("UPDATE destination SET zone_ts='' WHERE comp_code='$comp_code' AND zone_ts='$zone_code'");
                                        }

            
            for($i=0;$i<=count($_POST['dest_code'])-1;$i++){  
                       //generate description for audit_log
                       if(isset($current_destination[$_POST['dest_code'][$i]])){
                        unset($current_destination[$_POST['dest_code'][$i]]);
                       }else{
                        array_push($newadded_destination,$_POST['dest_code'][$i]);
                       }
                       //generate description for audit_log


            if(isset($_POST['zone_a'][$_POST['dest_code'][$i]])){
                $zonea1 = $_POST['zone_a'][$_POST['dest_code'][$i]];
                //$db->query("UPDATE destination SET zonecode_a='$zonea1' WHERE  comp_code='$comp_code' AND dest_code=UPPER('".$_POST['dest_code'][$i]."')");
            }
            if(isset($_POST['zone_b'][$_POST['dest_code'][$i]])){
                 $zoneb1 = $_POST['zone_b'][$_POST['dest_code'][$i]];
                 //$db->query("UPDATE destination SET zonecode_b='$zoneb1' WHERE  comp_code='$comp_code' AND dest_code=UPPER('".$_POST['dest_code'][$i]."')");
            }
            if(isset($_POST['zone_c'][$_POST['dest_code'][$i]])){
                  $zonec1 = $_POST['zone_c'][$_POST['dest_code'][$i]];
                 //$db->query("UPDATE destination SET zonecode_c='$zonec1' WHERE  comp_code='$comp_code' AND dest_code=UPPER('".$_POST['dest_code'][$i]."')");
            }
            if(isset($_POST['zone_cash'][$_POST['dest_code'][$i]])){
                  $zonecash1 = $_POST['zone_cash'][$_POST['dest_code'][$i]];
                 //$db->query("UPDATE destination SET zone_cash='$zonecash1' WHERE  comp_code='$comp_code' AND dest_code=UPPER('".$_POST['dest_code'][$i]."')");
            }
            if(isset($_POST['zone_ts'][$_POST['dest_code'][$i]])){
                  $zonets1 = $_POST['zone_ts'][$_POST['dest_code'][$i]];
                 //$db->query("UPDATE destination SET zone_ts='$zonets1' WHERE  comp_code='$comp_code' AND dest_code=UPPER('".$_POST['dest_code'][$i]."')");
            }
            }
            
            //auditlog
            $removed_destinations = implode(",",$current_destination);
            $newlyadded_destinations = implode(",",$newadded_destination);

            $auditlog_description = "Zone code $zone_code has modified in station $comp_code , ";

            if(count($current_destination)>0 || count($newadded_destination)>0){
            //$auditlog_description .= ((count($current_destination)>0)?" removed destinations - ".$removed_destinations:"").((count($newadded_destination)>0)?" added destinations - ".$newlyadded_destinations:"");
            }
            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Masters','Zone','Modified','$auditlog_description','$username',getdate())");

            header("location: zonelist?success=3"); 
              
            }
?>