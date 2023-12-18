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

      class reference extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - reference';
              $this->view->render('settings/referencetype');
          }
      }

          

          if(isset($_POST['addreference']))
		{
		savereference($db);
		}
            function savereference($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $ref_code = $_POST['ref_code'];
                $ref_type = $_POST['ref_type'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
		 $sqlcheck = "SELECT * from reference WHERE ref_code='$ref_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $refcode = $row->ref_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($ref_code==$refcode)
                    {
                      header("location: reference?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO reference (ref_code,ref_type,user_name,date_added) VALUES ('$ref_code','$ref_type','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: referencelist?success=1"); 
               }
               
             }
          }


              if(isset($_POST['addanreference']))
		{
		saveanreference($db);
		}
            function saveanreference($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $ref_code = $_POST['ref_code'];
                $ref_type = $_POST['ref_type'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
		 $sqlcheck = "SELECT * from reference WHERE ref_code='$ref_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $refcode = $row->ref_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($ref_code==$refcode)
                    {
                      header("location: reference?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO reference (ref_code,ref_type,user_name,date_added) VALUES ('$ref_code','$ref_type','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: reference?success=1"); 
               }
               
             }
          }       

                        
              
?>
