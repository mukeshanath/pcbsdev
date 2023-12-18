<?php 

/* File name   : tax.php
 Begin       : 2020-03-04
 Description : class taxedit file update master tax slab in appliaction
               Default Header and Footer

 Author: zabiullah

 (c) Copyright:
              codim solutions
============================================================+*/

/**
 * render the taxedit in to class 
 * get database string to establish connection from sql server
 * Capture the tax data and save ion to database
 * @author zabi  
 * @since 2020-03-04
 */

/* Include the main taxedit master (search for installation path). */


defined('__ROOT__') OR exit('No direct script access allowed');

      include 'db.php'; 

      class taxedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - taxedit';
              $this->view->render('settings/taxedit');
          }
      }

          $errors = array();

          if(isset($_POST['updatetax']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $taxid = $_POST['taxid'];
                $taxcode = $_POST['taxcode'];
                $taxval = $_POST['taxval'];
                $tax_active = $_POST['tax_active'];
                $username = $_POST['user_name'];

                $date_modified = date('Y-m-d H:i:s');
                
               $sql = "UPDATE tax set taxcode = '".$taxcode."',
                                        taxval = '".$taxval."',
                                        date_modified = '".$date_modified."',
                                        tax_active = '".$tax_active."',
                                        user_name = '".$username."'
                                        WHERE taxid='".$taxid."' ";

              $result  = $db->query($sql);
              
              header("location: taxlist?success=2"); 
              
              
              
             }
?>


 

 

 



 
