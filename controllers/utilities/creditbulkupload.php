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

class creditbulkupload extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - creditbulkupload';
		$this->view->render('utilities/creditbulkupload');
	}
}

if(isset($_POST['creditbulkupload'])){

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
        echo      $tdate          =  $xlsxrow[$i][0];
              $origin         =  $xlsxrow[$i][1];
              $cno            =  $xlsxrow[$i][2];
              $pincode        =  $xlsxrow[$i][3];
              $dest_code      =  $xlsxrow[$i][4];
              $zone_code      =  $xlsxrow[$i][5];
              $cust_code      =  $xlsxrow[$i][6];
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
              $opdestn        =  $xlsxrow[$i][18];
              $oprate         =  $xlsxrow[$i][19];
              $edate          =  $xlsxrow[$i][20];
              $cmat           =  $xlsxrow[$i][21];
              $other_charges  =  $xlsxrow[$i][22];
              $net_amt        =  $xlsxrow[$i][23];
              $opwt           =  $xlsxrow[$i][24];
              $intl_destn     =  $xlsxrow[$i][25];
              $muser          =  $xlsxrow[$i][26];
              $mdate          =  $xlsxrow[$i][27];
              $flag           =  $xlsxrow[$i][28];
              $bkg_br_code    =  $xlsxrow[$i][29];
              $rate_cnote_stationary       =  $xlsxrow[$i][30];
              $rate_cnote_advance          =  $xlsxrow[$i][31];
              $webupload      =  $xlsxrow[$i][32];
              $bkgstaff       =  $xlsxrow[$i][33];
              $opmode         =  $xlsxrow[$i][34];
              $podorigin      =  $xlsxrow[$i][35];
          //     $db->query("INSERT INTO credit (tdate,origin,cno,pincode,dest_code,zone_code,cust_code,wt,pcs,mode_code,amt,mf_no,fMF_PtoP_No,br_code,comp_code,invno,ucode,remark,opdestn,oprate,edate,cmat,other_charges,net_amt,opwt,intl_destn,muser,mdate,flag,bkg_br_code,rate_cnote_stationary,rate_cnote_advance,webupload,bkgstaff,opmode,podorigin,import,date_added,user_name) 
          //     VALUES ('$tdate','$origin','$cno','$pincode','$dest_code','$zone_code','$cust_code','$wt','$pcs','$mode_code','$amt','$mf_no','$fMF_PtoP_No','$br_code','$comp_code','$invno','$ucode','$remark','$opdestn','$oprate','$edate','$cmat','$other_charges','$net_amt','$opwt','$intl_destn','$muser','$mdate','$flag','$bkg_br_code','$rate_cnote_stationary','$rate_cnote_advance','$webupload','$bkgstaff','$opmode','$podorigin','1','$date_added','$user_name')");
           }
          }
          Header('location:creditbulkuploadlist?success=1');
}