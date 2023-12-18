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

class opdata extends My_controller
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
    $api_key          = $_POST['api_key'];
    if($api_key=='opd@2358963')
    {
        $tdate              = $_GET['tdate'];
        $origin             = $_GET['origin'];
        $cno                = $_GET['cno']; 
        $destn              = $_GET['destn'];
        $wt                 = $_GET['wt'];
        $mode_code          = $_GET['mode_code'];
        $wayno              = $_GET['wayno'];
        $ttype              = $_GET['ttype'];
        $type               = $_GET['type'];
        $udate              = $_GET['udate'];
        $comp_code          = $_GET['stncode'];
        $drsno              = $_GET['drsno'];
        $dbranch            = $_GET['dbranch'];        
        $rcode              = $_GET['rcode'];
        $aorigin            = $_GET['aorigin'];
        $staff_code         = $_GET['stfcode'];
        $ddate              = $_GET['ddate'];
        $br_code            = $_GET['br_code'];
        $date_added         = date('Y-m-d H:i:s');


    //Duplicate Restriction
    $opdata = $db->query("SELECT * FROM opdata WHERE cno='$cno'");
    if($opdata->rowCount()==0){
        if($insert_opdata      = $db->query("INSERT INTO opdata (tdate,origin,cno,destn,wt,mode_code,wayno,ttype,type,udate,stncode,drsno,dbranch,rcode,aorigin,stfcode,ddate,br_code,date_added) VALUES 
        ('$tdate','$origin','$cno','$destn','$wt','$mode_code','$wayno','$ttype','$type','$udate','$comp_code','$drsno','$dbranch','$rcode','$aorigin','$staff_code','$ddate','$br_code','$date_added')"))
       {
        echo "Successfully Updated";
        }
        else{
            echo "Update Failed";
        }
    }
    else{
        echo "Data Already Added";
    }
}
else{
    echo "API key Error";
}
}

if(isset($_GET['cno']))
{
    $api_key          = $_GET['api_key'];
    if($api_key=='opd@2358963')
    {
    $tdate              = $_GET['tdate'];
    $origin             = $_GET['origin'];
    $cno                = $_GET['cno']; 
    $destn              = $_GET['destn'];
    $wt                 = $_GET['wt'];
    $mode_code          = $_GET['mode_code'];
    $wayno              = $_GET['wayno'];
    $ttype              = $_GET['ttype'];
    $type               = $_GET['type'];
    $udate              = $_GET['udate'];
    $comp_code          = $_GET['stncode'];
    $drsno              = $_GET['drsno'];
    $dbranch            = $_GET['dbranch'];        
    $rcode              = $_GET['rcode'];
    $aorigin            = $_GET['aorigin'];
    $staff_code         = $_GET['stfcode'];
    $ddate              = $_GET['ddate'];
    $br_code            = $_GET['br_code'];
    $date_added         = date('Y-m-d H:i:s');


    //Duplicate Restriction
    $opdata = $db->query("SELECT * FROM opdata WHERE cno='$cno'");
    if($opdata->rowCount()==0){
        if($insert_opdata      = $db->query("INSERT INTO opdata (tdate,origin,cno,destn,wt,mode_code,wayno,ttype,type,udate,stncode,drsno,dbranch,rcode,aorigin,stfcode,ddate,br_code,date_added) VALUES 
        ('$tdate','$origin','$cno','$destn','$wt','$mode_code','$wayno','$ttype','$type','$udate','$comp_code','$drsno','$dbranch','$rcode','$aorigin','$staff_code','$ddate','$br_code','$date_added')"))
        {
        echo "Successfully Updated";
        }
        else{
            echo "Update Failed";
        }
    }
    else{
        echo "Data Already Added";
    }
}
else{
    echo "API key Error";
}
}
else{
echo "<span>(c) Copyright: 2020-03-04 ... AS2 Web Service API......</span> <span style='font-weight:bold'>Operation Data</span>";
}