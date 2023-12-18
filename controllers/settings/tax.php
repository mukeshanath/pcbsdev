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

      class tax extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - tax';
              $this->view->render('settings/tax');
          }
      }

			

          if(isset($_POST['addtax']))
	  {
		savetax($db);
	  }
           function savetax($db) 
            {
                date_default_timezone_set('Asia/Kolkata');
                $taxcode = $_POST['taxcode'];
                $taxval = $_POST['taxval'];
                $tax_active = $_POST['tax_active'];

                $user_name = $_POST['user_name'];

                $date_added = date('Y-m-d H:i:s');
                
                $sqlcheck = "SELECT * from tax WHERE taxcode='$taxcode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $tax_code = $row->taxcode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($taxcode==$tax_code )
                    {
                      header("location: tax?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO taxtb (taxcode,taxval,tax_active,user_name,date_added)
               VALUES ('$taxcode','$taxval','$tax_active','$user_name','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: taxlist?success=1"); 
               }
               
             }
          }


         if(isset($_POST['addantax']))
	  {
		saveantax($db);
	  }
           function saveantax($db) 
            {
                date_default_timezone_set('Asia/Kolkata');
                $taxcode = $_POST['taxcode'];
                $taxval = $_POST['taxval'];
                $tax_active = $_POST['tax_active'];

                $user_name = $_POST['user_name'];

                $date_added = date('Y-m-d H:i:s');
                
                $sqlcheck = "SELECT * from taxtb WHERE taxcode='$taxcode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $tax_code = $row->taxcode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($taxcode==$tax_code )
                    {
                      header("location: tax?unsuccess=4"); 
                      } 
                      else { 
           
            $sql = "INSERT INTO taxtb (taxcode,taxval,tax_active,user_name,date_added)
               VALUES ('$taxcode','$taxval','$tax_active','$user_name','$date_added')";
              
            }
               if($result  = $db->query($sql))
               {
               header("location: tax?success=1"); 
               }
               
             }
          }
                          
           
             
             


?>
