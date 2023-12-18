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

      class state extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - state';
              $this->view->render('settings/state');
          }
      }

          

          if(isset($_POST['addstate']))
            {
		savestate($db);
	    }

	    function savestate($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $statecode = $_POST['statecode'];
                $state_name = $_POST['state_name'];
                $username = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
                $sqlcheck = "SELECT * from state WHERE statecode='$statecode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $state_code = $row->statecode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($statecode==$state_code )
                    {
                      header("location: state?unsuccess=4"); 
                      } 
                      else { 
           
           $sql = "INSERT INTO state (statecode,state_name,user_name,date_added) VALUES ('$statecode','$state_name','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: statelist?success=1"); 
               }
               
             }
          }


          if(isset($_POST['addanstate']))
            {
		saveanstate($db);
	    }

	    function saveanstate($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $statecode = $_POST['statecode'];
                $state_name = $_POST['state_name'];
                $username = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
                $sqlcheck = "SELECT * from state WHERE statecode='$statecode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $state_code = $row->statecode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($statecode==$state_code )
                    {
                      header("location: state?unsuccess=4"); 
                      } 
                      else { 
           
           $sql = "INSERT INTO state (statecode,state_name,user_name,date_added) VALUES ('$statecode','$state_name','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: state?success=1"); 
               }
               
             }
          }
            

              
           
              
             
?>
