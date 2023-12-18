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

      class useredit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - useredit';
              $this->view->render('people/useredit');
          }
      }

      $errors = array();

      if (isset($_POST['updateusers']))
      {
        alteruser($db);
      }
      if (isset($_POST['changepassword']))
      {
        updatepassword($db);
      }

      function alteruser($db)
      {
        // receive all input values from the form
                date_default_timezone_set('Asia/Kolkata');

                $stncode      =  $_POST['stncode'];
                $username     =  $_POST['user_name'];
                $usr_email    =  $_POST['user_email'];
                $user_grp     =  $_POST['group_name'];
                // $password_1   =  $_POST['password_1'];
                $flag   =  $_POST['flag'];
                $user_type    =  $_POST['user_type'];
                $user_n       =  $_POST['user_n'];
                $br_code       =  $_POST['br_code'];
                $cust_code       =  $_POST['cust_code'];
                $usr_id       =  $_POST['usr_id'];

                $date_added = date('Y-m-d H:i:s');
      
                //parse branch data from select to json
                $allbranchesjson = "";
                 
                if(count($br_code)>0){
                   $branchesarr = json_encode($br_code);
                   $decoded = json_decode($branchesarr);
                   $currentbranch = explode(":",$decoded[0])[1];
                   $json = array();
                   for($u=0;$u<count($decoded);$u++)
                   {
                     $stn_joined = explode(":",$decoded[$u]);
                     $json[$stn_joined[0]][]=$stn_joined[1];
                   }
                   $allbranchesjson = json_encode($json);
                }
                //parse branch data from select to json

                if(empty($user_grp)){
                  $errors[] = 'User group is to be filled.';
                }
                if(count($_POST['stncode'])==0){
                  $errors[] = 'Please select station.';
                }

                $user_check_query = $db->query("SELECT count(*) as counts FROM users WHERE (user_name='$username' OR user_email='$usr_email') AND usrid!=$usr_id")->fetch(PDO::FETCH_OBJ);

                if($user_check_query->counts>0) {
                  $errors[] = 'User already exists.';
                }
                if(count($errors)>0){
                  
                 $serialize = serialize(implode(",",$errors));
                 //echo unserialize($serialize);exit;
                 header("location: useredit?usr_name=$username&unsuccess=".$serialize."");
                 
                }
                else{
                  //$password = md5($password_1);//encrypt the password before saving in the database
      
                  $query = $db->query("UPDATE users SET br_codes='$allbranchesjson',stncodes='$stncode',user_type='$user_type',br_code='$currentbranch',cust_code='$cust_code',user_name='$username',user_email='$usr_email',group_name='$user_grp',flag='$flag' WHERE usrid='$usr_id'");
                  $result  = $db->query($query);
                 
                  header('location: userlist?success=2');
                }
                // Finally, register user if there are no errors in the form
      }
      //update password
      function updatepassword($db){
        $usr_id       =  $_POST['usr_id'];
        $password_2 = $_POST['password_2'];
        $password = md5($password_2);
        $db->query("UPDATE users SET cpassword='$password' WHERE usrid='$usr_id'");
        header('location: userlist?success=4');
      }