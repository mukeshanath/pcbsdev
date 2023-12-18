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

class datapull extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - datapull';
		//$this->view->render('invoice/invoice');
	}
}

    if(isset($_POST['POD_NO']))
    {
        $POD_NO         = $_POST['POD_NO'];
        $WEIGHT         = $_POST['WEIGHT'];
        $PIECES         = $_POST['PIECES'];
        $AMOUNT         = $_POST['AMOUNT'];
        $DESTINATION    = $_POST['DESTINATION'];
        $ORIGIN         = $_POST['ORIGIN'];
        
        //check duplicates
        $getpodno = $db->query("SELECT * FROM refdata WHERE pod_no='$POD_NO'");
        if($getpodno->rowCount()==0){
        $insert   = $db->query("INSERT INTO refdata (pod_no,weight,pieces,amount,destination,origin)
         VALUES ('$POD_NO','$WEIGHT','$PIECES','$AMOUNT','$DESTINATION','$ORIGIN')");

         echo "Successfully Updated";
        }
        else{
            echo "Update failed";
        }
    }
