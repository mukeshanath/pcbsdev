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

class form extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - credit';
		//$this->view->render('utilities/creditimportlist');
	}
}

$run = $db->query("SELECT * FROM opdata where  comp_code='JAL' and tdate='2021-03-27' ");



while($row=$run->fetch(PDO::FETCH_OBJ)){
     $db->query("UPDATE cnotenumber SET opwt='$row->wt',opmode='$row->mode_code',oporigin='$row->origin',opdest='$row->destn',tdate='$row->tdate' WHERE cnote_number='$row->cno' AND cnote_station='$row->comp_code' ");
}
echo "DATA UPLOADED SUCCESSFULLY";

//BETWEEN '2020-11-10' AND '2020-12-2'
?>