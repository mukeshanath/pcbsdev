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

      class userform extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - userform';
              $this->view->render('people/userform');
          }
      }

      $errors = array();

      if (isset($_POST['addusers']))
      {
        newuser($db);
      }
      if (isset($_POST['addanusers']))
      {
        addanusers($db);
      }

      function newuser($db)
      {
                // receive all input values from the form
                date_default_timezone_set('Asia/Kolkata');

                $stncode      =  $_POST['stncode'];
                $username     =  $_POST['user_name']; 
                $usr_email    =  $_POST['usr_email'];
                $user_grp     =  $_POST['group_name'];
                $password_1   =  $_POST['password_1'];
                $password_2   =  $_POST['password_2'];
                $usr_status   =  $_POST['usr_status'];
                $br_code      =  $_POST['br_code'];
                $cust_code    =  $_POST['cust_code'];
                $user_type    =  $_POST['user_type'];
                $user_n       =  "";

                $date_added = date('Y-m-d H:i:s');


                //parse branch data from select to json
                $allbranchesjson = "";
                if(count($br_code)>0){
                $branchesarr = json_encode($br_code);
                $decoded = json_decode($branchesarr);
                $json = array();
                for($u=0;$u<count($decoded);$u++)
                {
                  $stn_joined = explode(":",$decoded[$u]);
                  $json[$stn_joined[0]][]=$stn_joined[1];
                }
                $allbranchesjson = json_encode($json);
               }
                //parse branch data from select to json

                $tmpstn = $_POST['stncode'][0];
                $tmpbr = ((!empty($json[$tmpstn][0]))?$json[$tmpstn][0]:NULL);

                if(empty($user_grp)){
                  $errors[] = 'User group is to be filled.';
                }
                if(count($_POST['stncode'])==0){
                  $errors[] = 'Please select station.';
                }
                // first check the database to make sure 
                // a user does not already exist with the same username and/or email
                $user_check_query = $db->query("SELECT count(*) as counts FROM users WHERE user_name='$username' OR user_email='$usr_email'")->fetch(PDO::FETCH_OBJ);

                if($user_check_query->counts>0) {
                  $errors[] = 'User already exists.';
                }
             
                if(count($errors)>0){
                  
                 $serialize = serialize(implode(",",$errors));
                 //echo unserialize($serialize);exit;
                  header("location: userform?unsuccess=".$serialize."");
                }
                else{
                  $password = md5($password_1);//encrypt the password before saving in the database
      
                  $query = "INSERT INTO users (br_codes,last_compcode,stncodes,br_code,cust_code,user_type,user_name,user_email,group_name,cpassword,user_status,date_added,user_n,flag) 
                  VALUES('$allbranchesjson','$tmpstn','$stncode','$tmpbr','$cust_code','$user_type','$username','$usr_email','$user_grp','$password','$usr_status','$date_added','$user_n','1')";       
                  $result  = $db->query($query);
                 
                  header('location: userlist?success=2');
                }
                // Finally, register user if there are no errors in the form
      }
      function addanusers($db)
      {
        // receive all input values from the form

                date_default_timezone_set('Asia/Kolkata');

                $stncode      =  $_POST['stncode'];
                $username     =  $_POST['user_name'];
                $usr_email    =  $_POST['usr_email'];
                $user_grp     =  $_POST['group_name'];
                $password_1   =  $_POST['password_1'];
                $password_2   =  $_POST['password_2'];
                $usr_status   =  $_POST['usr_status'];
                $br_code      =  $_POST['br_code'];
                $cust_code    =  $_POST['cust_code'];
                $user_type    =  $_POST['user_type'];
                $user_n       =  "";

                $date_added = date('Y-m-d H:i:s');
         
                if(empty($user_grp)){
                  $errors[] = 'User group is to be filled.';
                }
                if(count($_POST['stncode'])==0){
                  $errors[] = 'Please select station.';
                }
                // first check the database to make sure 
                // a user does not already exist with the same username and/or email
                $user_check_query = $db->query("SELECT count(*) as counts FROM users WHERE user_name='$username' OR user_email='$usr_email'")->fetch(PDO::FETCH_OBJ);

                if($user_check_query->counts>0) {
                  $errors[] = 'User already exists.';
                }

                if(count($errors)>0){
                  
                  $serialize = serialize(implode(",",$errors));
                  //echo unserialize($serialize);exit;
                   header("location: userform?unsuccess=".$serialize."");
                 }
                 else{
                  $password = md5($password_1);//encrypt the password before saving in the database
      
                  $query = "INSERT INTO users (stncodes,br_code,cust_code,user_type,user_name,user_email,group_name,cpassword,user_status,date_added,user_n,flag) VALUES('$stncode','$br_code','$cust_code','$user_type','$username','$usr_email','$user_grp','$password','$usr_status','$date_added','$user_n','1')";       
                  $result  = $db->query($query);
                 
                  header('location: userform?success=2');
                 }
                // Finally, register user if there are no errors in the form
      }

?>