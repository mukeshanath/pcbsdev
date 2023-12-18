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


      class usergroup extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - usergroup';
              $this->view->render('people/usergroup');
          }
      }


      if(isset($_POST['addgrp'])){

            date_default_timezone_set('Asia/Kolkata');
            $date_added       = date('Y-m-d H:i:s');
            $usergroup        = $_POST['usergroup'];
            $username         =  $_POST['user_name'];
            $user_type         =  $_POST['user_type'];
            //Duplication Check
            $check = $db->query("SELECT * FROM users_group WHERE group_name='$usergroup'");
            if($check->rowCount()==0)
            {
                for($i=0;$i<count($_POST['module']);$i++){
                    $module = explode(',',$_POST['module'][$i]);
                    $modulename = $module[0];
                    $moduleaccess = $module[1];
                    $insert = $db->query("INSERT INTO users_group (group_name,module,$moduleaccess,date_added,user_type,user_name) VALUES ('$usergroup','$modulename','Yes','$date_added','$user_type','$username')");
                }
                header("Location:usergrouplist?success=1");
            }else {
                header("Location:usergroup?unsuccess=$usergroup");
            }
      }


     	   

      ?>
