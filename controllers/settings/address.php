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

      class address extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - address';
              $this->view->render('settings/addresstype');
          }
      }

          

          if(isset($_POST['addaddress']))
	  {
          saveaddress($db);
          }
            function saveaddress($db)
            {
              if(isset($_POST['addaddress'])){
                date_default_timezone_set('Asia/Kolkata');
              
                $addr_code = $_POST['addr_code'];
                $addr_type = $_POST['addr_type'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
               $sqlcheck = "SELECT * from address WHERE addr_code='$addr_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $addrcode = $row->addr_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($addr_code==$addrcode )
                    {
                      header("location: address?unsuccess=4"); 
                      } 
                      else { 
           
              $sql = "INSERT INTO address (addr_code,addr_type,user_name,date_added) VALUES ('$addr_code','$addr_type','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: addresslist?success=1"); 
               }
               
             }
            }
          }


          if(isset($_POST['addanaddress']))
	  {
          saveanaddress($db);
          }
            function saveanaddress($db)
            {
                date_default_timezone_set('Asia/Kolkata');
              
                $addr_code = $_POST['addr_code'];
                $addr_type = $_POST['addr_type'];
                $username   = $_POST['user_name'];
                $date_added = date('Y-m-d H:i:s');
                
               $sqlcheck = "SELECT * from address WHERE addr_code='$addr_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $addrcode = $row->addr_code;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($addr_code==$addrcode )
                    {
                      header("location: address?unsuccess=4"); 
                      } 
                      else { 
           
              $sql = "INSERT INTO address (addr_code,addr_type,user_name,date_added) VALUES ('$addr_code','$addr_type','$username','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: address?success=1"); 
               }
               
             }
          }


	  
?>
