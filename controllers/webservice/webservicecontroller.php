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

    class webservicecontroller extends My_controller
    {
        public function index()
        {
            $this->view->title = __SITE_NAME__ . ' - webservicecontroller';
            //$this->view->render('invoice/invoice');
        }
    }

    if(isset($_POST['data1']))
    {
        $data1    = $_POST['data1'];
        $POD_NO1    = $_POST['POD_NO1'];
        $updateresult = $db->query("UPDATE credit SET webupload='$data1' WHERE cno='$POD_NO1'");
    }
