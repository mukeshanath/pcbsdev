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

class as2_opd extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - opdata';
		//$this->view->render('invoice/invoice');
	}
}

date_default_timezone_set('Asia/Kolkata');
  

    if(isset($_POST['cno']))
    {
        try{
        $api_key          = $_POST['api_key'];
        if($api_key=='opd@2358963')
        {
        $cno                = $_POST['cno'];
        $tdate              = $_POST['tdate'];
        $origin             = $_POST['origin'];
        $destn              = $_POST['destn'];
        $wt                 = $_POST['wt'];
        $mode_code          = $_POST['mode_code'];
        $wayno              = $_POST['wayno'];
        $ttype              = $_POST['ttype'];
        $type               = $_POST['type'];
        $comp_code          = $_POST['comp_code'];
        $br_code            = $_POST['br_code'];
        $rcode              = $_POST['rcode'];
        $staff_code         = $_POST['staff_code'];
        $date_added         = date('Y-m-d H:i:s');

            if($insert_opdata  = $db->query("INSERT INTO opdatabatch with (rowlock) (cno,tdate,origin,destn,wt,mode_code,wayno,ttype,type,comp_code,br_code,rcode,staff_code,date_added) VALUES 
            ('$cno','$tdate','$origin','$destn','$wt','$mode_code','$wayno','$ttype','$type','$comp_code','$br_code','$rcode','$staff_code','$date_added')"))
            {
            echo "Successfully Updated";
            }
            else{
                echo "Update Failed";
            }
    }
    else{
        echo "API key Error";
    }
        }catch(Exception $e){
            $error_info	= $e->errorInfo[1];
			$error_detail	= explode("]",$e->errorInfo[2]);

			$err_desc = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', END($error_detail));
            $db->query("INSERT INTO logs_tb (log_datetime,status) VALUES ('$date_added','$err_desc')");
        }
    }

    if(isset($_GET['cno']))
    {
        try{
        $api_key          = $_GET['api_key'];
        if($api_key=='opd@2358963')
        {
        $cno                = $_GET['cno'];
        $tdate              = $_GET['tdate'];
        $origin             = $_GET['origin'];
        $destn              = $_GET['destn'];
        $wt                 = $_GET['wt'];
        $mode_code          = $_GET['mode_code'];
        $wayno              = $_GET['wayno'];
        $ttype              = $_GET['ttype'];
        $type               = $_GET['type'];
        $comp_code          = $_GET['comp_code'];
        $br_code            = $_GET['br_code'];
        $rcode              = $_GET['rcode'];
        $staff_code         = $_GET['staff_code'];
        $date_added         = date('Y-m-d H:i:s');

            if($insert_opdata      = $db->query("INSERT INTO opdatabatch with (rowlock) (cno,tdate,origin,destn,wt,mode_code,wayno,ttype,type,comp_code,br_code,rcode,staff_code,date_added) VALUES 
            ('$cno','$tdate','$origin','$destn','$wt','$mode_code','$wayno','$ttype','$type','$comp_code','$br_code','$rcode','$staff_code','$date_added')"))
            {
            echo "Successfully Updated";
            }
            else{
                echo "Update Failed";
            }
    }
    else{
        echo "API key Error";
    }
        }catch(Exception $e){
            $error_info	= $e->errorInfo[1];
			$error_detail	= explode("]",$e->errorInfo[2]);

		    $err_desc = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', END($error_detail));
            $db->query("INSERT INTO logs_tb (log_datetime,status) VALUES ('$date_added','$err_desc')");
        }
}
else{
    echo "<span>(c) Copyright: 2020-03-04 ... AS2 Web Service API......</span> <span style='font-weight:bold'>Operation Data</span>";
}