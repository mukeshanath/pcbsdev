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

      class countryedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - countryedit';
              $this->view->render('settings/countryedit');
          }
      }

          $errors = array();

          if(isset($_POST['updatecountry']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $coun_id       = $_POST['coun_id'];
                $coun_code = $_POST['coun_code'];
                $coun_name = $_POST['coun_name'];
                $username   = $_POST['user_name'];
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($coun_code)){
                array_push($errors, "please enter address code");
              }

              if(empty($coun_name)){
                array_push($errors, "please enter address type");
              }

              
            if (count($errors) == 0) 
              {
            $sql = "UPDATE country set  coun_code = '".$coun_code."',
                                          coun_name = '".$coun_name."',
                                          user_name = '".$username."',
                                          date_modified = '".$date_modified."'
                                        
                                        WHERE coun_id='".$coun_id."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: countrylist?success=2"); 
             }
?>
