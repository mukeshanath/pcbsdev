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

      class country extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - country';
              $this->view->render('settings/country');
          }
      }

        

             if(isset($_POST['addcountry'])){
		savecountry($db);
		}

		
            function savecountry($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $coun_code  = $_POST['coun_code'];
                $coun_name  = $_POST['coun_name'];
                $user_name   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
                $sqlcheck = "SELECT * from country WHERE coun_code='$coun_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $councode = $row->coun_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($coun_code==$councode )
                    {
                      header("location: country?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO country (coun_code,coun_name,user_name,date_added) VALUES ('$coun_code','$coun_name','$user_name','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: countrylist?success=1"); 
               }
               
             }
          }


	  if(isset($_POST['addancountry'])){
		saveancountry($db);
		}

		
            function saveancountry($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $coun_code  = $_POST['coun_code'];
                $coun_name  = $_POST['coun_name'];
                $user_name   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
                $sqlcheck = "SELECT * from country WHERE coun_code='$coun_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $councode = $row->coun_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($coun_code==$councode )
                    {
                      header("location: country?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO country (coun_code,coun_name,user_name,date_added) VALUES ('$coun_code','$coun_name','$user_name','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: country?success=1"); 
               }
               
             }
          }
?>
