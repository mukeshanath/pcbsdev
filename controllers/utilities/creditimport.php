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

class creditimport extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - credit';
		$this->view->render('utilities/creditimport');
	}
}

include 'vendor/bootstrap/simplexlxs.php';

if(isset($_FILES["importfile"])){
	date_default_timezone_set('Asia/Kolkata');
	$date_added 		= date('Y-m-d H:i:s');
	$user_name 			= $_POST['user_name'];

	$oldimpid = $db->query("SELECT TOP(1) import FROM creditbulkuploadbatch WHERE import IS NOT NULL ORDER BY creditbulk_id DESC");
	if($oldimpid->rowCount()==0){
		$import = "IMP000001";
	}
	else{ 
		$lastimprow = $oldimpid->fetch(PDO::FETCH_OBJ);
		$last_import = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastimprow->import);
		$import = "IMP".str_pad(($last_import[1]+1), 6, "0", STR_PAD_LEFT);
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
	  foreach($xlsxrow as $rows){
	    $r++;
		if($r==1){
	
		}else{
			$comp_code = $rows[0];
			$origin = $rows[0];
			$podorigin = $rows[1];
			$tdate = date(''.$rows[2].' H:i:s');  
			$br_code = $rows[3];
			$cust_code = $rows[4];
			$cno = $rows[5];
			$dest_code = $rows[6];
			$wt = $rows[7];
			$mode_code = $rows[8];
			$consignee_mob = $rows[9];
			$cnote_serial = getcnote_serial($db,$cno,$comp_code,$podorigin);
		    $db->query("INSERT INTO creditbulkuploadbatch (cnote_serial,comp_code,origin,podorigin,tdate,br_code,cust_code,cno,dest_code,wt,mode_code,status,user_name,date_added,import,edate,consign_mob)
		   VALUES ('$cnote_serial','$comp_code','$origin','$podorigin','$tdate','$br_code','$cust_code','$cno','$dest_code','$wt','$mode_code','Pending','$user_name','$date_added','$import','$date_added','$consignee_mob')");
	    }
	}	
	Header("Location:creditimportlist?success=1");
	$result = $db->query("EXEC Credit_bulk_Update");

    }


	if(isset($_POST['action']) && $_POST['action']=='bulk_credit_cnote'){
		$stncode = $_POST['stncode'];
		$import_id = $_POST['import_id'];
		$fetch_flag = $db->query("SELECT TOP 1 print_flag FROM creditbulkuploadbatch WHERE import='$import_id' AND comp_code='$stncode' AND print_flag='Printed'")->fetch(PDO::FETCH_OBJ);
		$fetch_date = $db->query("SELECT TOP 1 CAST(date_added as date) as date FROM creditbulkuploadbatch WHERE import='$import_id' AND comp_code='$stncode' AND status !='Pending'")->fetch(PDO::FETCH_OBJ);

		$fetch_bulk_upload = $db->query("SELECT * FROM creditbulkuploadbatch WITH(NOLOCK) WHERE import='$import_id' AND comp_code='$stncode' AND status='Billed'  AND (SELECT v_flag FROM groups WITH (NOLOCK) WHERE stncode=creditbulkuploadbatch.comp_code AND cate_code=creditbulkuploadbatch.cnote_serial)=1");
		while($rowcnote = $fetch_bulk_upload->fetch(PDO::FETCH_OBJ)){
			$cnote[] = $rowcnote->cno;
			$custcode[] = $rowcnote->cust_code;
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
				'custcode' => $custcode, 
				'flag' => $fetch_flag->print_flag,
				'Aprovestatus' => $bulkchallan,
				'date' => [
					$date_added,
					$fetch_date->date
				]
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




	function getcnote_serial($db,$cno,$comp_code,$category)
	{
		if($category=='gl' || empty($category) || trim($category)==trim($comp_code))
		{
		  $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND (SELECT grp_type FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)='gl'")->fetch(PDO::FETCH_OBJ);
		}
		else
		{
		  $serial = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND cnote_serial='$category'")->fetch(PDO::FETCH_OBJ);
		}
		return $serial->cnote_serial;
	  }