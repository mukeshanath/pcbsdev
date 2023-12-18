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

      class pincode extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - pincode';
              $this->view->render('settings/pincode');
          }
      }

         

          if(isset($_POST['addpincode']))
	  {
 	   savepincode($db);
           }
            function savepincode($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $pincode = $_POST['pincode'];
                $city_name = $_POST['city_name'];
                $dest_code = $_POST['dest_code'];
                $username  = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
            $sqlcheck = "SELECT * from pincode WHERE pincode='$pincode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $pin_code = $row->pincode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($pincode==$pin_code )
                    {
                      header("location: pincode?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO pincode (pincode,city_name,dest_code,user_name,date_added) VALUES ('$pincode','$city_name','$dest_code','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: pincodelist?success=1"); 
               }
               
             }
          }

        if(isset($_POST['addanpincode']))
	  {
 	   saveanpincode($db);
           }
            function saveanpincode($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $pincode = $_POST['pincode'];
                $city_name = $_POST['city_name'];
                $dest_code = $_POST['dest_code'];
                $username  = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
            $sqlcheck = "SELECT * from pincode WHERE pincode='$pincode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $pin_code = $row->pincode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($pincode==$pin_code )
                    {
                      header("location: pincode?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO pincode (pincode,city_name,dest_code,user_name,date_added) VALUES ('$pincode','$city_name','$dest_code','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: pincode?success=1"); 
               }
               
             }
          }
            
            
             
              
?>
