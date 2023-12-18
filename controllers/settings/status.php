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

      class status extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - status';
              $this->view->render('settings/statustype');
          }
      }

          
          if(isset($_POST['addstatus']))
		{
		savestatus($db);
		}
            function savestatus($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $stat_code = $_POST['stat_code'];
                $stat_type = $_POST['stat_type'];
                $username = $_POST['user_name'];

                $date_added = date('Y-m-d H:i:s');
                
               $sqlcheck = "SELECT * from status WHERE stat_code='$stat_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $statcode = $row->stat_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($stat_code==$statcode )
                    {
                      header("location: status?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO status (stat_code,stat_type,user_name,date_added) VALUES ('$stat_code','$stat_type','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: statuslist?success=1"); 
               }
               
             }
          }



              if(isset($_POST['addanstatus']))
		{
		saveanstatus($db);
		}
            function saveanstatus($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $stat_code = $_POST['stat_code'];
                $stat_type = $_POST['stat_type'];
                $username = $_POST['user_name'];

                $date_added = date('Y-m-d H:i:s');
                
               $sqlcheck = "SELECT * from status WHERE stat_code='$stat_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $statcode = $row->stat_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($stat_code==$statcode )
                    {
                      header("location: status?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO status (stat_code,stat_type,user_name,date_added) VALUES ('$stat_code','$stat_type','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: status?success=1"); 
               }
               
             }
          }

              
           
            
              
?>
