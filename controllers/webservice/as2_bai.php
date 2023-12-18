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

    class as2_bai extends My_controller
    {
        public function index()
        {
            $this->view->title = __SITE_NAME__ . ' - AS2 BA-Inbound';
            //$this->view->render('invoice/invoice');
        }
    }
    //POST
    if(isset($_POST['cno']))
    {
        $api_key          = $_POST['api_key'];
        if($api_key=='bai@87924586')
        {
        $cno              = $_POST['cno'];
        $tdate            = $_POST['tdate'];
        $comp_code        = $_POST['comp_code'];
        $opcode           = $_POST['opcode'];
        $bacode           = $_POST['bacode'];
        $origin           = $_POST['origin'];
        $pntno            = $_POST['pntno'];
        $weight           = $_POST['weight'];
        $mfno             = $_POST['mfno'];
        $adestn           = $_POST['adestn'];
        $mode             = $_POST['mode'];

        //Duplicate Check
        $bainbound = $db->query("SELECT * FROM bainbound WHERE cno='$cno'");
        if($bainbound->rowCount()==0){
        $sql = "INSERT INTO bainbound (cno,tdate,comp_code,opcode,bacode,origin,pntno,wt,mf_no,dest_code,mode_code) VALUES ('$cno','$tdate','$comp_code','$opcode','$bacode','$origin','$pntno','$weight','$mfno','$adestn','$mode')";
        if($insert = $db->query($sql))
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

    //GET
    if(isset($_GET['cno']))
    {
        $api_key          = $_GET['api_key'];
        if($api_key=='bai@87924586')
        {
        $cno              = $_GET['cno'];
        $tdate            = $_GET['tdate'];
        $comp_code        = $_GET['comp_code'];
        $opcode           = $_GET['opcode'];
        $bacode           = $_GET['bacode'];
        $origin           = $_GET['origin'];
        $pntno            = $_GET['pntno'];
        $weight           = $_GET['weight'];
        $mfno             = $_GET['mfno'];
        $adestn           = $_GET['adestn'];
        $mode             = $_GET['mode'];

        //Duplicate Check
        $bainbound = $db->query("SELECT * FROM bainbound WHERE cno='$cno'");
        if($bainbound->rowCount()==0){
        $sql = "INSERT INTO bainbound (cno,tdate,comp_code,opcode,bacode,origin,pntno,wt,mf_no,dest_code,mode_code) VALUES ('$cno','$tdate','$comp_code','$opcode','$bacode','$origin','$pntno','$weight','$mfno','$adestn','$mode')";
        if($insert = $db->query($sql))
        {
            echo "Successfully Updated";
        }
        else{
            echo "Update Failed";
        }}
        else{
            echo "Data Already Added";
        }
        }
        else{
            echo "API Key Error";
        }
    }
    else{
        echo "<span>(c) Copyright: 2020-03-04 ... AS2 Web Service API......</span> <span style='font-weight:bold'>BA Inbound</span>";
    }

