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

class cashimport extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - cash';
		$this->view->render('utilities/cashimport');
	}
}

include 'vendor/bootstrap/simplexlxs.php';


if(isset($_POST['comp_code'])){
echo $comp_code;exit;
	//echo "success";exit;
	date_default_timezone_set('Asia/Kolkata');


	$comp_code = $_POST['comp_code'];
	$user_name = $_POST['user_name'];

	$oldimpid = $db->query("SELECT TOP(1) import_id FROM cash_upload_master WHERE import_id IS NOT NULL ORDER BY id DESC");
	if($oldimpid->rowCount()==0){
		$importid = "CASHIMP000001";
	}
	else{ 
		$lastimprow = $oldimpid->fetch(PDO::FETCH_OBJ);
		$last_import = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastimprow->import_id);
		$importid = "CASHIMP".str_pad(($last_import[1]+1), 6, "0", STR_PAD_LEFT);
	}


$file = $_FILES['importfile']['tmp_name'];
$file_ext = pathinfo($_FILES["importfile"]['name'], PATHINFO_EXTENSION);

if($file_ext == 'xlsx'){
  $xlsx = SimpleXLSX::parse($file);
  $xlsxrow = $xlsx->rows();
}
else if($file_ext == 'csv'){
ini_set('auto_detect_line_endings', TRUE);

$header = '';
$xlsxrow = array_map('str_getcsv', file($file));
}
//end
$r=0;
$i=0;
  foreach($xlsxrow as $rows){
	$r++;
	if($r==1){

	}else if(!empty($rows[0])){ $i++;
		$comp_code = $rows[0];
		$origin = $rows[0];
		$podorigin = $rows[1];
		$tdate = $rows[2];
		$br_code = $rows[3];
		$cno = $rows[4];
		$dest_code = $rows[5];
		$wt = $rows[6];
		$mode_code = $rows[7];
		$cnote_serial = getcnote_serial($db,$cno,$comp_code,$podorigin);
		$db->query("INSERT INTO cashbulkupload (
		 comp_code
		,br_code
		,origin
		,pod_origin
		,cno
		,dest_code
		,wt
		,pcs
		,mode_code
		,tdate
		,import_id
		,status
		,cnote_serial
		,user_name
		,date_added
		)
	   VALUES (
	   '$comp_code'
      ,'$br_code'
      ,'$origin'
      ,'$podorigin'
      ,'$cno'
      ,'$dest_code'
      ,'$wt'
      ,'1'
      ,'$mode_code'
      ,'$tdate'
      ,'$importid'
      ,'Pending'
	  ,'$cnote_serial'
      ,'$user_name'
      ,cast(getdate() as date)
	  )
	  ");
	}
}

$db->query("INSERT INTO cash_upload_master (comp_code,import_id,total_cnotes,flag,user_name,date_added,status)
VALUES ('$comp_code','$importid','$i',1,'$user_name',getdate(),'Initiated')");

$db->query("EXEC Cash_bulk_Update @importid='$importid'");

header('Location:cashimportlist?success=1');
}


if(isset($_POST['action']) && $_POST['action']=='bulk_cash_cnote'){
	$stncode = $_POST['stncode'];
	$import_id = $_POST['import_id'];
	$fetch_flag = $db->query("SELECT TOP 1 print_flag FROM cashbulkupload WHERE import_id='$import_id' AND comp_code='$stncode' AND print_flag='Printed'")->fetch(PDO::FETCH_OBJ);
	$fetch_date = $db->query("SELECT TOP 1 CAST(date_added as date) as date FROM cashbulkupload WHERE import_id='$import_id' AND comp_code='$stncode' AND status !='Pending'")->fetch(PDO::FETCH_OBJ);

	$fetch_bulk_upload = $db->query("SELECT * FROM cashbulkupload WITH(NOLOCK) WHERE import_id='$import_id' AND comp_code='$stncode' AND status='Billed'  AND (SELECT v_flag FROM groups WITH (NOLOCK) WHERE stncode=cashbulkupload.comp_code AND cate_code=cashbulkupload.cnote_serial)=1");
	while($rowcnote = $fetch_bulk_upload->fetch(PDO::FETCH_OBJ)){
		$cnote[] = $rowcnote->cno;
	}
	if($fetch_bulk_upload->rowCount() > 0){

		$date_added = date('Y-m-d');
		if ($date_added <= $fetch_date->date) {
			$bulkchallan = "Approve";
		}else{
			$bulkchallan = "NotApprove";
		}
		
		
		echo json_encode([
			'success' => true,
			'cnote' => $cnote,
			'flag' => $fetch_flag->print_flag,
			'Aprovestatus' => $bulkchallan
		]);
		exit();
	}else{
		echo json_encode([
			'success' => false,
			'cnote' => 'No cnotes in the cashbulk upload'
		]);
		exit();
	}
	

 }

function getcnote_serial($db,$cno,$comp_code,$category){
	if($category=='gl' || empty($category) || trim($category)==trim($comp_code)){
	  $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND (SELECT grp_type FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)='gl'")->fetch(PDO::FETCH_OBJ);
	}
	else{
	  $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND cnote_serial='$category'")->fetch(PDO::FETCH_OBJ);
	}
	return $serial->cnote_serial;
  }