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
include 'vendor/bootstrap/simplexlxs.php';

class cashbulkupload extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - cashbulkupload';
		$this->view->render('utilities/cashbulkupload');
	}
}
if(isset($_POST['cashbulkupload'])){

     date_default_timezone_set('Asia/Kolkata');

     $file = $_FILES["importfile"]["tmp_name"];

     $user_name     = $_POST['user_name'];
     $date_added    = date('Y-m-d H:i:s');
     $xlsx = SimpleXLSX::parse($file); 
     $xlsxrow = $xlsx->rows(); 
     for($i=0;$i<count($xlsxrow);$i++){
          if($i==0){
                         
          }
          else{
              $tdate          =  $xlsxrow[$i][0];
              $origin         =  $xlsxrow[$i][1];
              $cno            =  $xlsxrow[$i][2];
              $pincode        =  $xlsxrow[$i][3];
              $dest_code      =  $xlsxrow[$i][4];
              $zone_code      =  $xlsxrow[$i][5];
              $book_counter   =  $xlsxrow[$i][6];
              $wt             =  $xlsxrow[$i][7];
              $pcs            =  $xlsxrow[$i][8];
              $mode_code      =  $xlsxrow[$i][9];
              $amt            =  $xlsxrow[$i][10];
              $mf_no          =  $xlsxrow[$i][11];
              $fMF_PtoP_No    =  $xlsxrow[$i][12];
              $br_code        =  $xlsxrow[$i][13];
              $comp_code      =  $xlsxrow[$i][14];
              $invno          =  $xlsxrow[$i][15];
              $ucode          =  $xlsxrow[$i][16];
              $remark         =  $xlsxrow[$i][17];
              $ramt           =  $xlsxrow[$i][18];
              $oprate         =  $xlsxrow[$i][19];
              $opdestn        =  $xlsxrow[$i][20];
              $edate          =  $xlsxrow[$i][21];
              $gst            =  $xlsxrow[$i][22];
              $opwt           =  $xlsxrow[$i][23];
              $intl_destn     =  $xlsxrow[$i][24];
              $flag           =  $xlsxrow[$i][25];
              $bkgstaff       =  $xlsxrow[$i][26];
             $db->query("INSERT INTO cash (tdate,origin,cno,pincode,dest_code,zone_code,book_counter,wt,pcs,mode_code,amt,mf_no,fMF_PtoP_No,br_code,comp_code,invno,ucode,remark,ramt,oprate,opdestn,edate,gst,opwt,intl_destn,flag,bkgstaff,user_name,date_added,import)
             VALUES ('$tdate','$origin','$cno','$pincode','$dest_code','$zone_code','$book_counter','$wt','$pcs','$mode_code','$amt','$mf_no','$fMF_PtoP_No','$br_code','$comp_code','$invno','$ucode','$remark','$ramt','$oprate','$opdestn','$edate','$gst','$opwt','$intl_destn','$flag','$bkgstaff','$user_name','$date_added','1')"); 
             }
          }
          Header('location:cashbulkuploadlist?success=1');
}
