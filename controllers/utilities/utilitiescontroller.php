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

	 class utilitiescontroller extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - fetch';
             // $this->view->render('cnote/fetch');
          }
      }
    error_reporting(0);
   
    
    include 'vendor/bootstrap/simplexlxs.php';

    // echo 'comp_code';exit;

if(isset($_FILES['importfile'])){
	//echo "success";exit;
	date_default_timezone_set('Asia/Kolkata');

	try{
	$comp_code = $_POST['comp_code'];
	$user_name = $_POST['user_name'];
    $date_added 	= date('Y-m-d H:i:s');

	$oldimpid = $db->query("SELECT TOP(1) import_id FROM cash_upload_master WHERE import_id IS NOT NULL ORDER BY id DESC");
	if($oldimpid->rowCount()==0){
		$importid = "CASHIMP000001";
	}
	else{ 
		$lastimprow = $oldimpid->fetch(PDO::FETCH_OBJ);
		$last_import = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastimprow->import_id);
		$importid = "CASHIMP".str_pad(($last_import[1]+1), 6, "0", STR_PAD_LEFT);
	}

	$queryinsert = $db->query("BEGIN TRAN INSERT INTO cash_upload_master (comp_code,import_id,total_cnotes,flag,user_name,date_added,status) VALUES ('$comp_code','$importid','Calculating...',1,'$user_name',getdate(),'Initiated');  COMMIT TRAN");

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

	}else if(!empty($rows[0])){ 
		//$comp_code = $rows[0];
		$origin = strtoupper($rows[1]);
		$podorigin = strtoupper($rows[2]);
		$tdate = $rows[3];
		$br_code = strtoupper($rows[4]);
		$bookingcounter = strtoupper($rows[5]);
		$cc_code = $rows[6];
		$cno = $rows[7];
		$dest_code = strtoupper($rows[8]);
		$zone = strtoupper($rows[9]);
		$wt = $rows[10];
		$mode_code = strtoupper($rows[11]);
		$ramt = strtoupper($rows[12]);
		$ship_mob = $rows[13];
		$consign_mob = $rows[14];
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
		,ramt
		,bookingcounter
		,zone
		,user_name
		,cc_code
		,date_added
		,ship_mob
		,consign_mob
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
	  ,'$ramt'
	  ,'$bookingcounter'
	  ,'$zone'
      ,'$user_name'
	  ,'$cc_code'
      ,'$date_added'
	  ,'$ship_mob'
	  ,'$consign_mob'
	  )
	  ");

	$i++;
	}
}

$queryupdate = $db->query("BEGIN TRAN UPDATE cash_upload_master SET total_cnotes='$i',status='Processing' WHERE import_id='$importid' AND comp_code='$comp_code';  COMMIT TRAN");
//$uploadcredit = $db->query("EXEC Cash_bulk_Update @importid='$importid'");
echo json_encode(array('success'=>true,'importid'=>$importid));


} catch (PDOException $e) {
	$queryerrorupdate = $db->query("BEGIN TRAN UPDATE cash_upload_master SET total_cnotes='$i',status='Failed' WHERE import_id='$importid' AND comp_code='$comp_code';  COMMIT TRAN");
	echo json_encode(array('success'=>false));
}

}



if(isset($_POST['action']) && $_POST['action']=='bulk_credit_cnote'){
	$stncode = $_POST['stncode'];
	$import_id = $_POST['import_id'];
	$fetch_flag = $db->query("SELECT TOP 1 print_flag FROM creditbulkuploadbatch WHERE import='$import_id' AND comp_code='$stncode' AND print_flag='Printed'")->fetch(PDO::FETCH_OBJ);
	$fetch_bulk_upload = $db->query("SELECT * FROM creditbulkuploadbatch WITH(NOLOCK) WHERE import='$import_id' AND comp_code='$stncode' AND status='Billed'  AND (SELECT v_flag FROM groups WITH (NOLOCK) WHERE stncode=creditbulkuploadbatch.comp_code AND cate_code=cashbulkupload.cnote_serial)=1");
	while($rowcnote = $fetch_bulk_upload->fetch(PDO::FETCH_OBJ)){
		$cnote[] = $rowcnote->cno;
	}
	if($fetch_bulk_upload->rowCount() > 0){
	
		// $db->query("UPDATE cashbulkupload WITH (ROWLOCK) SET print_flag='Printed' WHERE comp_code='$stncode' AND import='$import_id'");
	
		echo json_encode([
			'success' => true,
			'cnote' => $cnote,
			'flag' => $fetch_flag->print_flag
		]);
		exit();
	}else{
		echo json_encode([
			'success' => false,
			'cnote' => 'No cnotes in the cashbulk upload'
		]);
		exit();
	}
	
exit();
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