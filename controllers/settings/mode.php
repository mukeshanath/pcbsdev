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

      class mode extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - mode';
              $this->view->render('settings/mode');
          }
      }

         

          if(isset($_POST['addmode']))
	  {
           savemode($db);
           }


	    function savemode($db)            
            {
                date_default_timezone_set('Asia/Kolkata');
                $mode_code = $_POST['mode_code'];
                $mode_name = $_POST['mode_name'];
                $username   = $_POST['user_name'];

                $date_added = date('Y-m-d H:i:s');
                
           
                $sqlcheck = "SELECT * from mode WHERE mode_code='$mode_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $modecode = $row->mode_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($mode_code==$modecode )
                    {
                      header("location: mode?unsuccess=4"); 
                      } 
                      else { 
           
           $sql = "INSERT INTO mode (mode_code,mode_name,user_name,date_added) VALUES ('$mode_code','$mode_name','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: modelist?success=1"); 
               }
               
             }
          }


          if(isset($_POST['addanmode']))
	  {
           saveanmode($db);
           }


	    function saveanmode($db)            
            {
                date_default_timezone_set('Asia/Kolkata');
                $mode_code = $_POST['mode_code'];
                $mode_name = $_POST['mode_name'];
                $username   = $_POST['user_name'];

                $date_added = date('Y-m-d H:i:s');
                
           
                $sqlcheck = "SELECT * from mode WHERE mode_code='$mode_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $modecode = $row->mode_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($mode_code==$modecode )
                    {
                      header("location: mode?unsuccess=4"); 
                      } 
                      else { 
           
           $sql = "INSERT INTO mode (mode_code,mode_name,user_name,date_added) VALUES ('$mode_code','$mode_name','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: mode?success=1"); 
               }
               
             }
          }
              
            
           
              
             
?>
