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

      class region extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - region';
              $this->view->render('settings/region');
          }
      }

          

          if(isset($_POST['addregion']))
		{ saveregion($db);
	        }
            function saveregion($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $regioncode = $_POST['regioncode'];
                $regionname = $_POST['regionname'];
                $statenames = implode(', ', $_POST['statenames']);
                $reg_active = $_POST['active'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');

                $sqlcheck = "SELECT * from region WHERE regioncode='$regioncode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $region_code = $row->regioncode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($regioncode==$region_code )
                    {
                      header("location: region?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO region (regioncode,regionname,statenames,active,user_name,date_added) VALUES ('$regioncode','$regionname','$statenames','$reg_active','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: regionlist?success=1"); 
               }
               
             }
          }


              if(isset($_POST['addanregion']))
		{ saveanregion($db);
	        }
            function saveanregion($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $regioncode = $_POST['regioncode'];
                $regionname = $_POST['regionname'];
                $statenames = implode(', ', $_POST['statenames']);
                $reg_active = $_POST['active'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');

                $sqlcheck = "SELECT * from region WHERE regioncode='$regioncode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $region_code = $row->regioncode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($regioncode==$region_code )
                    {
                      header("location: region?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO region (regioncode,regionname,statenames,active,user_name,date_added) VALUES ('$regioncode','$regionname','$statenames','$reg_active','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: region?success=1"); 
               }
               
             }
          }
                
           
             
              
?>
