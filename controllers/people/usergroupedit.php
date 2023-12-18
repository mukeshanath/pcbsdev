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


      class usergroupedit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - usergroup';
              $this->view->render('people/usergroupedit');
          }
      }

      if(isset($_POST['updategrp'])){
        // echo "<pre>";
        // var_dump($_POST);
        // exit();
        date_default_timezone_set('Asia/Kolkata');
        $date_added       = date('Y-m-d H:i:s');
        $usergroup        = $_POST['usergroup'];
        $user_type        =  $_POST['user_type'];
        $username         =  $_POST['user_name'];
        $deleteolddata = $db->query("DELETE FROM users_group WHERE group_name='$usergroup'");

        for($i=0;$i<count($_POST['module']);$i++){
            $module = explode(',',$_POST['module'][$i]);
            $modulename = $module[0];
            $moduleaccess = $module[1];
            $insert = $db->query("INSERT INTO users_group (group_name,module,$moduleaccess,user_type,date_added,user_name) VALUES ('$usergroup','$modulename','Yes','$user_type','$date_added','$username')");

        }
        header("Location:usergrouplist?success=1");
  }
