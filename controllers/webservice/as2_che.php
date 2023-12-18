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

    class as2_che extends My_controller
    {
        public function index()
        {
            $this->view->title = __SITE_NAME__ . ' - cashentryreq';
        }
    }

    //POST
    if(isset($_POST['cno']))
    {
        $api_key          = $_POST['api_key'];
        if($api_key=='che@47852369')
        {
        $cno                = $_POST['cno'];
        $tdate              = $_POST['tdate'];
        $origin             = $_POST['origin'];
        $pincode            = $_POST['pincode'];
        $book_counter       = $_POST['book_counter'];
        $wt                 = $_POST['wt'];
        $mode_code          = $_POST['mode_code'];
        $amt                = $_POST['amt'];
        $comp_code          = $_POST['comp_code'];
        $br_code            = $_POST['br_code'];
        $gst                = $_POST['gst'];
        //Duplicate Check
        $cash = $db->query("SELECT * FROM cash WHERE cno='$cno'");
        if($cash->rowCount()==0)
        {
            $sql = "INSERT INTO cash (cno,tdate,origin,pincode,book_counter,wt,mode_code,amt,comp_code,br_code,gst) VALUES ('$cno','$tdate','$origin','$pincode','$book_counter','$wt','$mode_code','$amt','$comp_code','$br_code','$gst')";
            if($insert = $db->query($sql)){
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
    //GET
    if(isset($_GET['cno']))
    {
        $api_key          = $_GET['api_key'];
        if($api_key=='che@47852369')
        {
        $cno                = $_GET['cno'];
        $tdate              = $_GET['tdate'];
        $origin             = $_GET['origin'];
        $pincode            = $_GET['pincode'];
        $book_counter       = $_GET['book_counter'];
        $wt                 = $_GET['wt'];
        $mode_code          = $_GET['mode_code'];
        $amt                = $_GET['amt'];
        $comp_code          = $_GET['comp_code'];
        $br_code            = $_GET['br_code'];
        $gst                = $_GET['gst'];
        //Duplicate Check
        $cash = $db->query("SELECT * FROM cash WHERE cno='$cno'");
        if($cash->rowCount()==0)
        {
            $sql = "INSERT INTO cash (cno,tdate,origin,pincode,book_counter,wt,mode_code,amt,comp_code,br_code,gst) VALUES ('$cno','$tdate','$origin','$pincode','$book_counter','$wt','$mode_code','$amt','$comp_code','$br_code','$gst')";
            if($insert = $db->query($sql)){
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
        echo "<span>(c) Copyright: 2020-03-04 ... AS2 Web Service API......</span> <span style='font-weight:bold'>CashEntryRequest</span>";
    }