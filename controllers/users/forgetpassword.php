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
    // require_once ('models/login.php');

   

      class forgetpassword extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - forgetpassword';
              $this->view->render_in('users/forgetpassword');
          }

      }


      if (isset($_POST['user_email'])) {
          updatepassword($db);
       }

       function updatepassword($db){
          $user_name = $_POST['user_email'];
          $password = $_POST['new_password'];
          $cpassword = $_POST['confirm_password'];

          if($password==$cpassword){
               $user = $db->query("SELECT * FROM users WHERE user_email='$user_name'");
               if($user->rowCount()==0){
                    Header("location:forgetpassword?unsuccess=2");
               }
               else{
                    $pass = md5($cpassword);
                    $db->query("UPDATE users SET cpassword='$pass' WHERE user_email='$user_name'");
                    Header("location:login?success=1");
               }
          }
          else{
               Header("location:forgetpassword?unsuccess=4");
          }
       }