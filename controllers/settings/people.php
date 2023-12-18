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

      class people extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - people';
              $this->view->render('settings/peopletype');
          }
      }

         

          if(isset($_POST['addpeople'])){
		savepeople($db);
		}
            function savepeople($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $ple_code = $_POST['ple_code'];
                $ple_type = $_POST['ple_type'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
                $sqlcheck = "SELECT * from people WHERE ple_code='$ple_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $plecode = $row->ple_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($plecode==$ple_code )
                    {
                      header("location: people?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO people (ple_code,ple_type,user_name,date_added) VALUES ('$ple_code','$ple_type','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: peoplelist?success=1"); 
               }
               
             }
          }


             if(isset($_POST['addanpeople'])){
		saveanpeople($db);
		}

            function saveanpeople($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $ple_code = $_POST['ple_code'];
                $ple_type = $_POST['ple_type'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
                $sqlcheck = "SELECT * from people WHERE ple_code='$ple_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $plecode = $row->ple_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($plecode==$ple_code )
                    {
                      header("location: people?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO people (ple_code,ple_type,user_name,date_added) VALUES ('$ple_code','$ple_type','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: people?success=1"); 
               }
               
             }
          }

              
           
?> 
