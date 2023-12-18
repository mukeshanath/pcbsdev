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

class autoinvoicehandler extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - invoice';
	}
}

if(isset($_GET['start'])){
    lockinginvoice($db);
     echo "<script>window.close();</script>";
}
else{
    echo "You Are Not Allowed to Run Automatic Script.......It will Run Automatically By Server System<br>";

}

function lockinginvoice($db){

}