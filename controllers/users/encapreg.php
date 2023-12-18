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

      class encapreg extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - encapreg';
              $this->view->render('users/encapreg');
          }
      }

      if (isset($_POST['adminreg'])) {
        // receive all input values from the form
                $username =  $_POST['user_name']; 
                $stncode  =  $_POST['stncodes'];
               
                $password_1 = $_POST['password_1'];
                $password_2 = $_POST['password_2'];
      
                // form validation: ensure that the form is correctly filled ...
                // by adding (array_push()) corresponding error unto $errors array
                if (empty($username)) { array_push($errors, "Username is required"); }
               
                if (empty($password_1)) { array_push($errors, "Password is required"); }
                if ($password_1 != $password_2) {
                  array_push($errors, "The two passwords do not match");
                }
      
                // first check the database to make sure 
                // a user does not already exist with the same username and/or email
                $user_check_query = "SELECT TOP 1 * FROM users WHERE user_name='$username' ";
                $result  = $db->query($user_check_query);
                $user =$result->fetch(PDO::FETCH_ASSOC);
              
                if ($user) { // if user exists
                  if ($user['user_name'] === $username) {
                    array_push($errors, "Username already exists");
                  }
                       
                }
      
                // Finally, register user if there are no errors in the form
                if (count($errors) == 0) {
                    $password = md5($password_1);//encrypt the password before saving in the database
      
                    $query = "INSERT INTO users (user_name, stncodes,  cpassword) VALUES('$username','$stncode', '$password')";
                    $result  = $db->query($query);
                    $_SESSION['user_name'] = $username;
                    $_SESSION['success'] = "You are now logged in";
                    header('location: home');
        }
      }


?>
