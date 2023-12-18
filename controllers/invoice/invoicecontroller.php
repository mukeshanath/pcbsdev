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

class invoicecontroller extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - invoiceinvoicecontroller';
		//$this->view->render('invoice/invoice');
	}
}
error_reporting(0);

require 'controllers/phpmailer/PHPMailerAutoload.php';


date_default_timezone_set('Asia/Kolkata');



if(isset($_POST['tr_cust_code'])){
	$cust_code = $_POST['tr_cust_code'];
	$stationcode = $_POST['stationcode'];

	$getcust_lockstatus = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE comp_code='$stationcode' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);
	$string = $getcust_lockstatus->tr_lock_date;
	$data   = preg_split('/To/', $string);
	$getdate = date('Y-m-d');
	$currentdate = date('Y-m-d', strtotime($getdate));
	if(($currentdate >= trim($data[0])) && ($currentdate <= trim($data[1]))){
	   $value = 1;
	}else{
		$value = 0;
	} 

	echo json_encode(array($value));
	exit();
}



	//Delete cnote From Invoice
	
	if(isset($_POST['void_inv'])){
		$void_inv = $_POST['void_inv'];
		$inv_comp = $_POST['inv_comp'];
		$inv_no = $_POST['inv_no'];
		$inv_customer = $_POST['inv_customer'];
		$table = $_POST['table'];
		if($table=='credit'){

		$alter_cnote = $db->query("UPDATE cnotenumber SET invno=NULL,cnote_status='Billed' WHERE cnote_number='$void_inv' AND cnote_status='Invoiced' AND cnote_station='$inv_comp'");
		$delete_credit = $db->query("UPDATE credit SET invno=NULL,status='Billed' WHERE cno='$void_inv' AND status='Invoiced' AND comp_code='$inv_comp'");
		
		// $getcnoteamt = $db->query("SELECT amt FROM credit WITH (NOLOCK) WHERE cno='$void_inv' AND comp_code='$inv_comp'");
		// $rowGetCnoteAmt = $getcnoteamt->fetch(PDO::FETCH_OBJ);
		// $cnoterate = $rowGetCnoteAmt->amt;//cnote bill amount
		// $get_invamt = $db->query("SELECT grandamt,fsc FROM invoice WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$inv_comp'");
		// $row_inv_amt = $get_invamt->fetch(PDO::FETCH_OBJ);
		// $invoice_amt = $row_inv_amt->grandamt;//invoice amount
		 $get_cust_fsc = $db->query("SELECT cust_gstin FROM customer WITH (NOLOCK) WHERE cust_code='$inv_customer' AND comp_code='$inv_comp'");
		 $row_fsc = $get_cust_fsc->fetch(PDO::FETCH_OBJ);
           $invoice_gstin = $db->query("SELECT cust_gstin FROM invoice WHERE invno ='$inv_no'")->fetch(PDO::FETCH_OBJ);

		//Set new invoice rate details
		$total_amount = $db->query("SELECT SUM(amt) AS total FROM credit WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$inv_comp'")->fetch(PDO::FETCH_OBJ);


		$dis_rs = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE cust_code='$inv_customer' AND comp_code='$inv_comp'")->fetch(PDO::FETCH_OBJ);
		$discount_pc = $dis_rs->spl_discountD/100;

		$getcod_weight = $db->query("SELECT count(cno) as codweight FROM credit WHERE cnote_serial='COD' AND status='Invoiced' AND invno='$inv_no' AND comp_code='$inv_comp'")->fetch(PDO::FETCH_OBJ);
		$calculated_cod_weight = $getcod_weight->codweight;

        $getcode_consignment = $db->query("SELECT tdate,count(cno) as countcod FROM credit  WHERE comp_code='$inv_comp' AND invno='$inv_no' AND cnote_serial='COD' group by tdate ORDER BY tdate")->fetch(PDO::FETCH_OBJ);
	    $codcount = $getcode_consignment->countcod;

		function getcoderate($inv_comp,$db,$inv_customer){
		  $getcustomerinfo = $db->query("SELECT * FROM customer WHERE comp_code='$inv_comp' AND cust_code='$inv_customer'");
		  $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
		  $cod_per_kg = $rowcustomer->cod;
		  return $cod_per_kg;
	   }

	   $codvalue_wt = (($codcount==0)? '0' : getcoderate($inv_comp,$db,$inv_customer));
	   $codvalue =  $calculated_cod_weight*$codvalue_wt;

	//    $codvalue = ($codcount*getcoderate($inv_comp,$db,$inv_customer));
		$newgrand = ($total_amount->total);
		$newfscandhandling = getcustomerfscandhandling($db,$inv_comp,$inv_customer,$newgrand);
		$newfsc = (($newgrand/100)*$newfscandhandling[0]);
	
		$newhandling = $newfscandhandling[1];
		
		$newinvamt = $newgrand + $newfsc + $newhandling + $codvalue;
		$cal_dis_amt = (($newinvamt)*($discount_pc));
		$newinvamt = (($newinvamt)-($cal_dis_amt));


		
		$newGst = ($newinvamt/100)*18;
		$newNet = $newinvamt+$newGst;

		//check gst for local and national
		$customergst = $row_fsc->cust_gstin;
		$getcompdetail = $db->query("SELECT stn_gstin FROM station WITH (NOLOCK) WHERE stncode='$inv_comp'")->fetch(PDO::FETCH_OBJ);
		$companygst = $getcompdetail->stn_gstin;
		if(substr($customergst,0,2)==substr($companygst,0,2)){
			$sgst = $newGst/2;
			$cgst = $newGst/2;
			$igst = '0';
		}
		else if(empty($customergst)){
			$sgst = $newGst/2;
			$cgst = $newGst/2;
			$igst = '0';
		}
		else{
			$sgst = '0';
			$cgst = '0';
			$igst = $newGst;
		}

		//update invoice

		$alter_invoice = $db->query("UPDATE invoice SET fhandcharge='$newhandling',grandamt='".number_format($newgrand,2)."',fsc='$newfsc',spl_discount='".number_format($cal_dis_amt,2)."',invamt='".number_format($newinvamt,2)."',igst='$igst',cgst='$cgst',sgst='$sgst',netamt='".number_format($newNet,2)."',cod_per_keg_total='".number_format($codvalue,2)."' WHERE invno='$inv_no' AND comp_code='$inv_comp'");

	}
		else if($table=='opdata'){
			$getcnoteamt = $db->query("SELECT amt FROM opdata WITH (NOLOCK) WHERE cno='$void_inv' AND comp_code='$inv_comp'");
			$rowGetCnoteAmt = $getcnoteamt->fetch(PDO::FETCH_OBJ);
			$cnoterate = $rowGetCnoteAmt->amt;//cnote bill amount
			$get_invamt = $db->query("SELECT grandamt,fsc FROM invoice WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$inv_comp'");
			$row_inv_amt = $get_invamt->fetch(PDO::FETCH_OBJ);
			$invoice_amt = $row_inv_amt->grandamt;//invoice amount
			$get_cust_fsc = $db->query("SELECT cust_fsc FROM customer WITH (NOLOCK) WHERE cust_code='$inv_customer' AND comp_code='$inv_comp'");
			$row_fsc = $get_cust_fsc->fetch(PDO::FETCH_OBJ);
			//Set new invoice rate details
			$newgrand = $invoice_amt-$cnoterate;
			$newfsc = ($newgrand/100)*$row_fsc->cust_fsc;
			$newinvamt = $newgrand + $newfsc;
			$newGst = ($newinvamt/100)*18;
			$newNet = $newinvamt+$newGst;
	
			$alter_invoice = $db->query("UPDATE invoice SET fhandcharge='$newhandling',grandamt='$newgrand',fsc='$newfsc',invamt='$newinvamt',igst='$newGst',netamt='$newNet' WHERE invno='$inv_no' AND comp_code='$inv_comp'");
	
			$delete_credit = $db->query("UPDATE opdata SET invno=NULL,status='Billed',inv_date=null WHERE cno='$void_inv' AND comp_code='$inv_comp'");
		
		}
	}








	
	//Fetch Customer Name
	if(isset($_POST['customerlookup']))
	{
	  $customerlookup = $_POST['customerlookup'];
	  $get_customer = $db->query("SELECT cust_name FROM customer WITH (NOLOCK) WHERE cust_code='$customerlookup'");
	  if($get_customer->rowCount()==0)
	  {}else{
	  $customer_row = $get_customer->fetch(PDO::FETCH_ASSOC);
	  echo $customer_row['cust_name'];
	  }
	}
	//Fetch dataa of cnote to Invoice edit
	if(isset($_POST['revcno'])){
		$cnoteincredit = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE cno='".$_POST['revcno']."' AND (status='Billed' OR status='Noservice-return') AND origin='".$_POST['revcomp']."'");
		if($cnoteincredit->rowCount()==0){
	     echo "Not Found";
		}else{
		$rowinvadd = $cnoteincredit->fetch(PDO::FETCH_OBJ);
		echo number_format($rowinvadd->wt, 3).",".$rowinvadd->v_wt.",".$rowinvadd->amt.",".$rowinvadd->mode_code.",".$rowinvadd->dest_code;
		}
	}
	//Add cnote to existing invoice
	if(isset($_POST['savecno'])){

		$revinvno = $_POST['revinvno'];
		$savecno = $_POST['savecno'];
		$savecomp = $_POST['savecomp'];
		$rev_customer = $_POST['rev_customer'];
		//updatecnotenumbertb
		$altercnotenumbertb_save = $db->query("UPDATE cnotenumber SET cnote_status='Invoiced' WHERE cnote_status NOT IN ('Noservice-return') AND cnote_number='".$_POST['savecno']."' AND cnote_station='".$_POST['savecomp']."'");
		$altercnotenumbertb = $db->query("UPDATE cnotenumber SET invno='".$_POST['revinvno']."' WHERE cnote_number='".$_POST['savecno']."' AND cnote_station='".$_POST['savecomp']."'");
		//update credit
	
		$updatecredit_save = $db->query("UPDATE credit SET status='Invoiced' WHERE status NOT IN ('Noservice-return') AND cno='".$_POST['savecno']."' AND comp_code='".$_POST['savecomp']."'");

		$updatecredit = $db->query("UPDATE credit SET invno='".$_POST['revinvno']."' WHERE  cno='".$_POST['savecno']."' AND comp_code='".$_POST['savecomp']."'");
		//get invoice amount
		// $getinvamt = $db->query("SELECT grandamt FROM invoice WITH (NOLOCK) WHERE invno='".$_POST['revinvno']."'");
		// $rowamt = $getinvamt->fetch(PDO::FETCH_OBJ);
		
		$total_amount = $db->query("SELECT SUM(amt) AS total FROM credit WITH (NOLOCK) WHERE invno='".$_POST['revinvno']."' AND comp_code='".$_POST['savecomp']."'")->fetch(PDO::FETCH_OBJ);

		$newtotal = $total_amount->total;
		//get fsc
		$custfsc = $db->query("SELECT cust_gstin FROM customer WITH (NOLOCK) WHERE cust_code='".$_POST['rev_customer']."' AND comp_code='".$_POST['savecomp']."'");
		$rowfsc = $custfsc->fetch(PDO::FETCH_OBJ);
		$invoice_gstin = $db->query("SELECT cust_gstin FROM invoice WHERE invno ='$revinvno'")->fetch(PDO::FETCH_OBJ);


		$getcod_weight = $db->query("SELECT count(cno) as codweight FROM credit WHERE cnote_serial='COD' AND status='Invoiced' AND invno='".$_POST['revinvno']."' AND comp_code='".$_POST['savecomp']."'")->fetch(PDO::FETCH_OBJ);
		$calculated_cod_weight = $getcod_weight->codweight;


		$dis_rs = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE cust_code='".$_POST['rev_customer']."' AND comp_code='".$_POST['savecomp']."'")->fetch(PDO::FETCH_OBJ);
		$discount_pc = $dis_rs->spl_discountD/100;

		$getcode_consignment = $db->query("SELECT tdate,count(cno) as countcod FROM credit  WHERE comp_code='$savecomp' AND invno='$revinvno' AND cnote_serial='COD' group by tdate ORDER BY tdate")->fetch(PDO::FETCH_OBJ);
		$codcount = $getcode_consignment->countcod;
		function getcoderate($savecomp,$db,$rev_customer){
			$getcustomerinfo = $db->query("SELECT * FROM customer WHERE comp_code='$savecomp' AND cust_code='$rev_customer'");
		   $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
		   $cod_per_kg = $rowcustomer->cod;
			return $cod_per_kg;
	   }

	   $codvalue_wt =  (($codcount==0)? '0' : getcoderate($savecomp,$db,$rev_customer));
	  
	   $codvalue =  ($calculated_cod_weight*$codvalue_wt);

		$newfscandhandling = getcustomerfscandhandling($db,$savecomp,$rev_customer,$newtotal);
		$addfsc = (($newtotal/100)*$newfscandhandling[0]);
		$newhandling = $newfscandhandling[1];
		$grandinvamt = $newtotal+$addfsc+$newhandling+$codvalue;

		$cal_dis_amt = ($grandinvamt)*($discount_pc); 
		$grandinvamt = ($grandinvamt)-$cal_dis_amt;
	

		$addgst = (($grandinvamt)/100)*18;
		$newinvamt = $grandinvamt+$addgst;
		//update invoice 
		//check gst for local and national
		$customergst = $rowfsc->cust_gstin;
		$getcompdetail = $db->query("SELECT stn_gstin FROM station WITH (NOLOCK) WHERE stncode='$savecomp'")->fetch(PDO::FETCH_OBJ);
		$companygst = $getcompdetail->stn_gstin;
		if(substr($customergst,0,2)==substr($companygst,0,2)){
			$sgst = $addgst/2;
			$cgst = $addgst/2;
			$igst = '0';
		}
		else if(empty($customergst)){
			$sgst = $addgst/2;
			$cgst = $addgst/2;
			$igst = '0';
		}
		else{
			$sgst = '0';
			$cgst = '0';
			$igst = $addgst;
		}
		$updateinv = $db->query("UPDATE invoice SET fhandcharge='$newhandling',grandamt='".number_format($newtotal,2)."',invamt='".number_format($grandinvamt,2)."',fsc='$addfsc',spl_discount='".number_format($cal_dis_amt,2)."',igst='$igst',cgst='$cgst',sgst='$sgst',netamt='".number_format($newinvamt,2)."',cod_per_keg_total='".number_format($codvalue,2)."' WHERE invno='".$_POST['revinvno']."' AND comp_code='".$_POST['savecomp']."'");
		
	}
	        // auto populate customer based on branch
	        // auto populate customer based on branch
		   if(isset($_POST['branch8']))
		   {
			$branch8  = $_POST['branch8'];
			$comp8    = $_POST['comp8'];
			$getautoppl8 = $db->query("SELECT cust_code FROM customer WITH (NOLOCK) WHERE comp_code='$comp8' AND br_code='$branch8'");
			if($getautoppl8->rowCount()==0){
	
			}else{
			while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
			{
			  $arrayvalues[] = $getautoppl8row['cust_code'];
			}
			echo json_encode($arrayvalues);
		   }
		   }
			// auto populate customer based on branch
		   if(isset($_POST['branch11']))
		   {
			$branch11  = $_POST['branch11'];
			$comp11    = $_POST['comp11'];
			$getautopp11 = $db->query("SELECT cust_name FROM customer WITH (NOLOCK) WHERE comp_code='$comp11' AND br_code='$branch11'");
			if($getautopp11->rowCount()==0){
	
			}else{
			while($getautopp11row = $getautopp11->fetch(PDO::FETCH_ASSOC))
			{
			  $arrayvalues11[] = $getautopp11row['cust_name'];
			}
			echo json_encode($arrayvalues11);
		   }
		   }
	   //fetch branch code
        if(isset($_POST['s_bname'])){
		$s_bname = $_POST['s_bname'];
		$s_comp = $_POST['s_comp'];
		echo branchcode($s_bname,$s_comp,$db);
		}
         function branchcode($s_bname,$s_comp,$db){
          $brname = $db->query("SELECT br_code FROM branch WITH (NOLOCK) WHERE br_name='$s_bname' AND stncode='$s_comp'")->fetch(PDO::FETCH_OBJ);
          return $brname->br_code;
		}
		//fetch branch namespace//Fetch Branch Name
			if(isset($_POST['branchlookup']))
			{
			$branchlookup = $_POST['branchlookup'];
			$br_stncode = $_POST['br_stncode'];
			$get_branch = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$branchlookup' AND stncode='$br_stncode' AND br_active='Y'");
			$branch_row = $get_branch->fetch(PDO::FETCH_ASSOC);
			echo $branch_row['br_name'];
			}
		 //Fetch Customer code
		 if(isset($_POST['cccust_name'])){
			$cccust_name = $_POST['cccust_name'];
			$cc_namestn = $_POST['cc_namestn'];
			$cc_namebr = $_POST['cc_namebr'];
			$rowcustcode = $db->query("SELECT cust_code FROM customer WITH (NOLOCK) WHERE cust_name='$cccust_name' AND comp_code='$cc_namestn' AND br_code='$cc_namebr'")->fetch(PDO::FETCH_OBJ);
			echo $rowcustcode->cust_code;
		   }
		   //edit invoiced cnote_station
		   if(isset($_POST['edit_cno'])){
			   $edit_cno = $_POST['edit_cno'];
			   $edit_stn = $_POST['edit_stn'];
			   $edit_wt = $_POST['edit_wt'];
			   $edit_rate = $_POST['edit_rate'];
			   $edit_mode = $_POST['edit_mode'];
			   $edit_destn = $_POST['edit_destn'];
			   $getoldcd = $db->query("SELECT * FROM cnotenumber WITH (NOLOCK) WHERE cnote_number='$edit_cno' AND cnote_station='$edit_stn'")->fetch(PDO::FETCH_OBJ);//get old cnote data
			   $getoldinvdata = $db->query("SELECT grandamt FROM invoice WITH (NOLOCK) WHERE invno='$getoldcd->invno' AND comp_code='$edit_stn'")->fetch(PDO::FETCH_OBJ);//get old inv amt
			   $edit_fsc = $db->query("SELECT cust_fsc FROM customer WITH (NOLOCK) WHERE cust_code='$getoldcd->cust_code' AND comp_code='$edit_stn'")->fetch(PDO::FETCH_OBJ);//get cust fsc
			   $edit_grandamt = round(($getoldinvdata->grandamt - $getoldcd->bilrate) + $edit_rate);
			   $edit_fsc = round(($edit_grandamt/100)*$edit_fsc->cust_fsc);
			   $edit_invamt = round($edit_grandamt+$edit_fsc);
			   $edit_gst = round(($edit_invamt/100)*18);
			   $edit_netamt = round($edit_gst + $edit_invamt);
			   $updateinvedit = $db->query("UPDATE invoice SET grandamt='$edit_grandamt',fsc='$edit_fsc',igst='$edit_gst',invamt='$edit_invamt',netamt='$edit_netamt' WHERE invno='$getoldcd->invno' AND comp_code='$edit_stn'");//update invoice tb
			   $updateeditcnotetb = $db->query("UPDATE cnotenumber SET bilwt='$edit_wt',bilrate='$edit_rate',bilmode='$edit_mode',bildest='$edit_destn' WHERE cnote_number='$edit_cno' AND cnote_station='$edit_stn'");//update cnotenumbertb
			   $updateeditcredit = $db->query("UPDATE credit SET wt='$edit_wt',amt='$edit_rate',mode_code='$edit_mode',dest_code='$edit_destn' WHERE cno='$edit_cno' AND comp_code='$edit_stn'");//update credit
		   }
		//generate invoice number
		if(isset($_POST['br_invno'])){
			echo generateinvoicenumber($db,$_POST['br_invno'],$_POST['br_stncode']);
		}
		function generateinvoicenumber($db,$br_invno,$br_stncode){
			$verify_branch = $db->query("SELECT COUNT(*) as brcount FROM branch WITH (NOLOCK) WHERE br_code='$br_invno' AND stncode='$br_stncode'")->fetch(PDO::FETCH_OBJ);
			if($verify_branch->brcount>0){
			if(strtoupper(trim($br_invno))=='TR'){
				$lastinvno = $db->query("SELECT TOP(1) * FROM invoice WHERE invno WITH (NOLOCK) LIKE '$br_stncode".'TR'."%' AND comp_code='$br_stncode' ORDER BY cast(dbo.GetNumericValue(invno) AS decimal) desc");
				if($lastinvno->rowCount()==0){
					return $br_stncode.'TR000001';
				}
				else{
					$fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);
					$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$fetchinvno->invno);
					return $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
				}
			}
			else if(strtoupper(trim($br_invno))=='CD'){
				$lastinvno = $db->query("SELECT TOP(1) * FROM invoice WITH (NOLOCK) WHERE invno LIKE '$br_stncode".'CD'."%' AND comp_code='$br_stncode' ORDER BY cast(dbo.GetNumericValue(invno) AS decimal) desc");
				if($lastinvno->rowCount()==0){
					return $br_stncode.'CD000001';
				}
				else{
					$fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);               
					$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$fetchinvno->invno);
					return $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);      
				}
			}else{
			//$be_cate_code = $db->query("SELECT brcate_code FROM branch WHERE br_code='$br_invno' AND stncode='$br_stncode'")->fetch(PDO::FETCH_OBJ);
			$lastinvno = $db->query("SELECT TOP(1) * FROM invoice WITH (NOLOCK) WHERE comp_code='$br_stncode' AND SUBSTRING(invno,0,4)=comp_code AND cast(dbo.GetNumericValue(invno) AS decimal)>30000000 ORDER BY cast(dbo.GetNumericValue(invno) AS decimal) desc");
			if($lastinvno->rowCount()==0){
				return $br_stncode.'30000001';
			}
			else{
				$fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);
				$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$fetchinvno->invno);
				return $br_stncode.($arr[1]+1);
			}
			}
			}
			else{
				return '';
			}
		}
		//check for branch category and change category select option to similar type
		if(isset($_POST['branch12'])){
			$branch12 = $_POST['branch12'];
			$comp12   = $_POST['comp12'];
			
			echo getbranchcategory($db,$branch12,$comp12);
		}
			function getbranchcategory($db,$branch12,$comp12){
				$getcategory = $db->query("SELECT brcate_code FROM branch WITH (NOLOCK) WHERE br_code='$branch12' AND stncode='$comp12'")->fetch(PDO::FETCH_OBJ);
				$getbrcate = $db->query("SELECT cate_code FROM brcategory WITH (NOLOCK) WHERE comp_code='DEL'");
				$select = '';
				$select .="<option value=''>Select Category</option>";
				while($row = $getbrcate->fetch(PDO::FETCH_OBJ)){
					if($row->cate_code==$getcategory->brcate_code){
						$selected = 'Selected';
					}else{
						$selected = '';
					}
					$select .="<option $selected value='$row->cate_code'>$row->cate_code</option>";
				}
				return $getcategory->brcate_code;
			}
		//rerun invoice
		if(isset($_POST['re_invno'])){
			reruninvoice($db,$_POST['re_invno'],$_POST['re_stn'],$_POST['re_cust'],$_POST['re_user']);
		}
		function reruninvoice($db,$re_invno,$re_stn,$re_cust,$re_user){
			//get customer row_fsc
			$getfscofcustomer = $db->query("SELECT cust_fsc,cust_gstin FROM customer WITH (NOLOCK) WHERE comp_code='$re_stn' AND cust_code='$re_cust'");
			$rowgetfsc = $getfscofcustomer->fetch(PDO::FETCH_ASSOC);
			$getinvoice = $db->query("SELECT * FROM invoice WITH (NOLOCK) WHERE invno='$re_invno' AND comp_code='$re_stn' AND cust_code='$re_cust'")->fetch(PDO::FETCH_OBJ);
			//update credit table
			$db->query("UPDATE credit SET status='Invoiced' WHERE comp_code='$re_stn' AND cust_code='$re_cust' AND status NOT IN ('Noservice-return') AND invno IS NULL AND tdate BETWEEN '$getinvoice->fmdate' AND '$getinvoice->todate'");

			$db->query("UPDATE credit SET invno='$re_invno' WHERE comp_code='$re_stn' AND cust_code='$re_cust' AND invno IS NULL AND tdate BETWEEN '$getinvoice->fmdate' AND '$getinvoice->todate'");

			//update cnotenumber table
			$db->query("UPDATE cnotenumber SET cnote_status='Invoiced' WHERE cust_code='$re_cust' AND cnote_station='$re_stn' AND bdate BETWEEN '$getinvoice->fmdate' AND '$getinvoice->todate' AND invno IS NULL AND cnote_status='Billed'");

			$db->query("UPDATE cnotenumber SET invno='$re_invno' WHERE cust_code='$re_cust' AND cnote_station='$re_stn' AND bdate BETWEEN '$getinvoice->fmdate' AND '$getinvoice->todate' AND invno IS NULL");
			//calculate amount
			$total_amount = $db->query("SELECT SUM(amt) AS total FROM credit WITH (NOLOCK) WHERE invno='$re_invno' AND comp_code='$re_stn'");
			
			$dis_rs = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE cust_code='$re_cust' AND comp_code='$re_stn'")->fetch(PDO::FETCH_OBJ);
			$discount_pc = $dis_rs->spl_discountD/100;



			$getcod_weight = $db->query("SELECT count(cno) as codweight FROM credit WHERE cnote_serial='COD' AND status='Invoiced' AND invno='$re_invno' AND comp_code='$re_stn'")->fetch(PDO::FETCH_OBJ);
			$calculated_cod_weight = $getcod_weight->codweight;
	

			$getcode_consignment = $db->query("SELECT tdate,count(cno) as countcod FROM credit  WHERE comp_code='$re_stn' AND invno='$re_invno' AND cnote_serial='COD' group by tdate,cno ORDER BY tdate")->fetch(PDO::FETCH_OBJ);
			$codcount = $getcode_consignment->countcod;

			function getcoderate($re_stn,$db,$re_cust){
				$getcustomerinfo = $db->query("SELECT * FROM customer WHERE comp_code='$re_stn' AND cust_code='$re_cust'");
			   $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
			   $cod_per_kg = $rowcustomer->cod;
				return $cod_per_kg;
		   }
  
		   $codvalue_wt = (($codcount==0)? '0' : (getcoderate($re_stn,$db,$re_cust)));
		   $codvalue =  ($calculated_cod_weight*$codvalue_wt);

		//    $codvalue = ($codcount*getcoderate($re_stn,$db,$re_cust));

			$row_total = $total_amount->fetch(PDO::FETCH_ASSOC);
			$total_amt = ($row_total['total']);

			$newfscandhandling = getcustomerfscandhandling($db,$re_stn,$re_cust,$total_amt);
			$fsc_cust = $newfscandhandling[0];
			$fsc_custhandling = $newfscandhandling[1];

			$fsc= ($total_amt/100*$fsc_cust);
			$invamt= ($total_amt+$fsc+$fsc_custhandling+$codvalue);

			$cal_dis_amt = ($invamt)*($discount_pc); 
			$invamt =  ($invamt)-($cal_dis_amt);


           
			$gst= ($invamt/100)*18;

			//check gst for local and national
			$customergst = $rowgetfsc['cust_gstin'];
			$getcompdetail = $db->query("SELECT stn_gstin FROM station WITH (NOLOCK) WHERE stncode='$re_stn'")->fetch(PDO::FETCH_OBJ);
			$companygst = $getcompdetail->stn_gstin;
			if(substr($customergst,0,2)==substr($companygst,0,2)){
				$sgst = $gst/2;
				$cgst = $gst/2;
				$igst = '0';
			}
			else if(empty($customergst)){
				$sgst = $gst/2;
				$cgst = $gst/2;
				$igst = '0';
			}
			
			else{
				$sgst = '0';
				$cgst = '0';
				$igst = $gst;
			}

			$tax = $igst+$cgst+$sgst;
			$net= ($invamt+$tax);
		// 	//update invoice
			$db->query("UPDATE invoice SET fhandcharge='$fsc_custhandling',grandamt='".number_format($total_amt,2)."',fsc='$fsc',spl_discount='".number_format($cal_dis_amt,2)."',igst='$igst',cgst='$cgst',sgst='$sgst',invamt='".number_format($invamt,2)."',netamt='".number_format($net,2)."',cod_per_keg_total='".number_format($codvalue,2)."' WHERE invno='$re_invno' AND comp_code='$re_stn' AND cust_code='$re_cust'");
			$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','Invoice','$re_invno','User $re_user initiated Re-Run for Inv#$re_invno','$re_user',getdate())");   

		echo json_encode($codvalue);
		}

		//rerun invoice rates
		if(isset($_POST['ratere_invno']))
		{
			$ratere_invno = $_POST['ratere_invno'];
			$ratere_stn = $_POST['ratere_stn'];
			$ratere_cust = $_POST['ratere_cust'];
			$ratere_user = $_POST['ratere_user'];
			$revisecredent = $_POST['revisecredent'];
			
			$db->query("EXEC [Invoice_Rerun] 
		    @invno		  = '$ratere_invno',
			@cust_code    = '$ratere_cust',
			@comp_code    = '$ratere_stn',
			@user_name    = '$ratere_user',
			@revice_entry = '$revisecredent'");
		}
		
		
		//delete invoice
		if(isset($_POST['del_invno'])){
			//call function of delete invoice
			deleteinvoice($db,$_POST['del_invno'],$_POST['del_invstn'],$_POST['del_inv_user']);
           

			  $fetch_irn = $db->query("SELECT * FROM invoice WHERE invno='".$_POST['del_invno']."' AND comp_code='".$_POST['del_invstn']."'")->fetch(PDO::FETCH_OBJ);
			$gen_irn = $fetch_irn->irn_no;
			if($fetch_irn->irn_status=='Issued' && !empty($fetch_irn->irn_no)){
				$urlorigin = "https://saralgsp.com/authentication/Authenticate";

				$curl = curl_init($urlorigin);
				curl_setopt($curl, CURLOPT_URL, $urlorigin);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				
				$headers = array(
				   "ClientId: 43cc5941-2be5-4e2f-9a09-2536f0e29fbf",
				   "ClientSecret: Fsx2IecwjFtWEsk0fxqEKs18jMjCN62v"
				);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				//for debug only!
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				
				$resp = curl_exec($curl);
				
				curl_close($curl);
				
				
				$data = json_decode($resp, true);
				$authenticationToken = $data['authenticationToken'];
				$subscriptionId = $data['subscriptionId'];
				$authurl = "https://demo.saralgsp.com/eivital/v1.04/auth";
				
				
				
				
				
				function postauth($url,$authenticationToken,$subscriptionId){
					$request = curl_init();
				curl_setopt($request, CURLOPT_URL,$url);
				curl_setopt($request, CURLOPT_POST, 1);
				// catch the response
				$headers1 = array(
					"AuthenticationToken: $authenticationToken",
					"SubscriptionId: $subscriptionId",
					"UserName: eRelyon",
					"Password: Saral@123",
					"Gstin: 29AABCR7796N000"
				 );
				curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($request, CURLOPT_HTTPHEADER, $headers1);
				$response = curl_exec($request);
				curl_close ($request);
				return $response;
				}
				
				$data1 = json_decode(postauth($authurl,$authenticationToken,$subscriptionId), true);
				$authToken = $data1['data']['authToken'];
				$sek = $data1['data']['sek'];
	
	
				function cancel_irn($authenticationToken,$SubscriptionId,$authToken,$sek,$irn){
					$data = array(
					  'Irn' => $irn,
					  'CnlRsn' => '1',
					  'CnlRem' => 'cancel Remarks'
				  );
				  
				  $json = json_encode($data);
				  $url = 'https://demo.saralgsp.com/eicore/v1.03/Invoice/Cancel';
				  $ch = curl_init($url);
				  
				  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
				  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					"Content-Type: application/json",
					"AuthenticationToken: $authenticationToken",
					"SubscriptionId: $SubscriptionId",
					"Gstin: 29AABCR7796N000",
					"UserName: eRelyon",
					"AuthToken: $authToken",
					"Sek: $sek"
				  ));
				  
				  $response = curl_exec($ch);
				  if(curl_errno($ch)) {
					  $responsedata = 'Error: ' . curl_error($ch);
				  } else {
					  $responsedata = $response;
				  }
				  curl_close($ch);
				  $res = json_decode($responsedata, true);
				  if(!empty($res['cancelDate'])){
					return $res['cancelDate'];
				  }else{
					return $res['errorDetails'][0]['errorMessage'];
				  }
				  }

				
				$db->query("UPDATE invoice SET irn_status='Canceled',cancel_date='".cancel_irn($authenticationToken,$SubscriptionId,$authToken,$sek,$gen_irn)."' WHERE invno='".$_POST['del_invno']."' AND comp_code='".$_POST['del_invstn']."'");
			}else{
				
			}
		}
		
		//set function for delete invoice
		function deleteinvoice($db,$del_invno,$del_invstn,$del_inv_user){
			//check invoice type
			$branchtype = $db->query("SELECT br_code FROM invoice WITH (NOLOCK) WHERE invno='$del_invno' AND comp_code='$del_invstn'")->fetch(PDO::FETCH_OBJ);
			//check invoice type
			if($branchtype->br_code=='TR' || $branchtype->br_code=='CD'){
				$db->query("UPDATE opdata SET invno= null, inv_date = null,status='Billed' WHERE invno='$del_invno' AND comp_code='$del_invstn'");
			}
			else{
				$db->query("UPDATE credit SET invno=null,status='Billed' WHERE invno='$del_invno' AND status='Invoiced' AND comp_code='$del_invstn'");//rollback invoiced cnotes to billed cnote in credit
				$db->query("UPDATE cnotenumber SET invno=null,cnote_status='Billed' WHERE invno='$del_invno' AND cnote_status='Invoiced' AND cnote_station='$del_invstn'");//rollback invoiced cnotes to billed cnote in master
				$db->query("UPDATE credit SET invno=null WHERE invno='$del_invno' AND status='Noservice-return' AND comp_code='$del_invstn'");//rollback invoiced cnotes to billed cnote in credit
				$db->query("UPDATE cnotenumber SET invno=null WHERE invno='$del_invno' AND cnote_status='Noservice-return' AND cnote_station='$del_invstn'");//rollback invoiced cnotes to billed cnote in master
			//prepare for log
			}
			$db->query("UPDATE invoice SET flag=0,status=status+'-Void',grandamt='0',fsc='0',igst='0',cgst='0',sgst='0',invamt='0',netamt='0',rcvdamt='0',fhandcharge='0' WHERE invno='$del_invno' AND comp_code='$del_invstn'");//rollback invoice master to void

			$yourbrowser   = getBrowser()['name'];
			$user_surename = get_current_user();
			$platform      = getBrowser()['platform'];   
			$remote_addr   = getBrowser()['remote_addr']; 
			$date_added    = date('Y-m-d H:i:s');
			
			$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$del_invno','Invoice','Deleted','User $del_inv_user has deleted the invoice $del_invno','$del_inv_user',getdate())");   


			//$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$del_inv_user','Invoice No $del_invno has been Deleted','$yourbrowser')"); 
		}
		//calculate amounts for manual invoice
		if(isset($_POST['m_inv_cust'])){
			echo calculateinvoiceamounts($db,$_POST['m_inv_stn'],$_POST['m_inv_cust'],$_POST['m_inv_total']);
		}
		function calculateinvoiceamounts($db,$comp_code,$cust_code,$totalamt){
				$custfsc_and_handling = getcustomerfscandhandling($db,$comp_code,$cust_code,$totalamt);
				$custfsc = $custfsc_and_handling[0];
				$customer_fschandling = round($custfsc_and_handling[1]);
				$fsc= round(($totalamt/100)*$custfsc);
				$invamt= round($totalamt+$fsc+$customer_fschandling);
				$gst= ($invamt/100)*18;
				$net= round($totalamt+$fsc+$gst+$customer_fschandling);
				return $fsc.",".$invamt.",".$gst.",".$net.",".$customer_fschandling;
		}


		//get customers for create invoice
		if(isset($_POST['newinv_ccodes_stncode'])){
			$newinv_ccodes_stncode = $_POST['newinv_ccodes_stncode'];
			$newinv_ccodes_br_code = $_POST['newinv_ccodes_br_code'];
			$newinv_ccodes_trans_date_from = $_POST['newinv_ccodes_trans_date_from'];
			$newinv_ccodes_trans_date_to = $_POST['newinv_ccodes_trans_date_to'];
			$newinv_inv_amt_threshold = $_POST['newinv_inv_amt_threshold'];
			$newinv_category = $_POST['newinv_category'];
			//if branch category is CRO
			if($newinv_category=='CRO'){
				if(empty($newinv_inv_amt_threshold)){
					$newinv_inv_amt_threshold = '0';
				}
				else{
					$newinv_inv_amt_threshold = $newinv_inv_amt_threshold;
				}
				if($newinv_ccodes_br_code=='TR')
				{
					$getcustomersforinv = $db->query("SELECT DISTINCT origin FROM opdata WITH (NOLOCK) WHERE comp_code='$newinv_ccodes_stncode' AND ttype='MFT' AND tdate BETWEEN '$newinv_ccodes_trans_date_from' AND '$newinv_ccodes_trans_date_to' AND status='Billed' AND amt>='$newinv_inv_amt_threshold'");
				}
				else if($newinv_ccodes_br_code=='CD')
				{
					$getcustomersforinv = $db->query("SELECT DISTINCT origin FROM opdata WITH (NOLOCK) WHERE comp_code='$newinv_ccodes_stncode' AND ttype='DRS' AND tdate BETWEEN '$newinv_ccodes_trans_date_from' AND '$newinv_ccodes_trans_date_to' AND status='Billed' AND amt>='$newinv_inv_amt_threshold'");
				}				if($getcustomersforinv->rowCount()==0)
				{
				echo 0;
				}
				else{
					if($newinv_ccodes_br_code=='TR')
					{
					while($rowgetcustomersforinv = $getcustomersforinv->fetch(PDO::FETCH_OBJ)){
						$array_getcustomersforinv[] = $rowgetcustomersforinv->origin."TR";
					}
					}
					else if($newinv_ccodes_br_code=='CD')
					{
					while($rowgetcustomersforinv = $getcustomersforinv->fetch(PDO::FETCH_OBJ)){
						$array_getcustomersforinv[] = $rowgetcustomersforinv->origin."CD";
					}
					}
					echo json_encode($array_getcustomersforinv);
				}
			}
			//if branch category not cro
			else{
				$getcustomersforinv = $db->query("SELECT DISTINCT cust_code FROM credit WITH (NOLOCK) WHERE comp_code='$newinv_ccodes_stncode' AND br_code='$newinv_ccodes_br_code' AND tdate BETWEEN '$newinv_ccodes_trans_date_from' AND '$newinv_ccodes_trans_date_to' AND status='Billed'");
				if($getcustomersforinv->rowCount()==0)
				{
				echo 0;
				}
				else{
					while($rowgetcustomersforinv = $getcustomersforinv->fetch(PDO::FETCH_OBJ)){
						$array_getcustomersforinv[] = $rowgetcustomersforinv->cust_code;
					}
					echo json_encode($array_getcustomersforinv);
				}
			   }
		      }











		//generate invoice-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		if(isset($_POST['stncode'])){
			echo generatenewinvoice($db,$_POST['stncode'],$_POST['br_code'],$_POST['cust_code'],$_POST['trans_date_from'],$_POST['trans_date_to'],$_POST['inv_date'],$_POST['user_name'],$_POST['category'],$_POST['inv_amt_threshold']);
		}
		function generatenewinvoice($db,$stncode,$br_code,$cust_code,$trans_date_from,$trans_date_to,$inv_date,$user_name,$category,$inv_amt_thresholdval){
			$date_added        = date('Y-m-d H:i:s');
			if($category=='CRO'){
				if(empty($inv_amt_thresholdval)){
					$inv_amt_threshold = '0';
				}
				else{
					$inv_amt_threshold = $inv_amt_thresholdval;
				}
					
					//getcustomers
					$crocustomer = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE cust_code='$cust_code' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);

					
										//filter for tr and dly
					if($crocustomer->cctr=='Y' && $crocustomer->ccdly=='Y'){
						$ccfilter = " AND (ttype='DRS' or ttype='MFT')";
					}
					else{
						
						if($crocustomer->cctr=='Y'){
							
							$ccfilter = " AND ttype='MFT'";
						}
						else if($crocustomer->ccdly=='Y'){
							$ccfilter = " AND ttype='DRS'";
						}
						else{
							$ccfilter = "AND mode_code='NONE'";
						}
					}
					
					//end
					$getorigin = substr(trim($cust_code),0,3);
					$chkopdatarow = $db->query("SELECT count(*) as counts FROM opdata WITH (NOLOCK)  WHERE origin='$getorigin' AND comp_code='$stncode' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed' $ccfilter")->fetch(PDO::FETCH_OBJ);
					$chkinvamtthreshold = $db->query("SELECT sum(amt) as totalamt FROM opdata WITH (NOLOCK)  WHERE origin='$getorigin' AND comp_code='$stncode' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed' $ccfilter")->fetch(PDO::FETCH_OBJ);
					 
					// if($chkinvamtthreshold->totalamt<(int)$inv_amt_threshold){
					// 	return "Invoice Not generated";
					// }
					// else{
					// 	return "Invoice generated";
					// }
					//exit;
					if($chkopdatarow->counts==0 || $chkinvamtthreshold->totalamt<(int)$inv_amt_threshold){
						return 'empty';
					}
					else{
					//generate invno
					if($br_code=='TR'){
						$lastinvno = $db->query("SELECT TOP(1) * FROM invoice WITH (NOLOCK) WHERE invno LIKE '$stncode".'TR'."%' ORDER BY cast(dbo.GetNumericValue(invno) AS decimal) desc");
						if($lastinvno->rowCount()==0){
							$inv_no = $stncode.'TR0000001';
						}
						else{
							$fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);
							$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$fetchinvno->invno);
							$inv_no = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
						}
					}
					else if($br_code=='CD'){
						$lastinvno = $db->query("SELECT TOP(1) * FROM invoice WITH (NOLOCK) WHERE invno LIKE '$stncode".'CD'."%' ORDER BY cast(dbo.GetNumericValue(invno) AS decimal) desc");
						if($lastinvno->rowCount()==0){
							$inv_no = $stncode.'CD0000001';
						}
						else{
							$fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);
							$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$fetchinvno->invno);
							$inv_no = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
						}
					}
					
					//insert into invoice
					$invoice_insert = $db->query("INSERT INTO invoice (invno,invdate,comp_code,br_code,cust_code,fmdate,todate,grandamt,fsc,igst,cgst,sgst,invamt,netamt,status,user_name,date_added,rcvdamt)
					VALUES ('$inv_no','$inv_date','$stncode','$br_code','$cust_code','$trans_date_from','$trans_date_to','0','0','0','0','0','0','0','Pending','$user_name','$date_added','0')");
					//get data from opdata
					
					
					$db->query("UPDATE opdata SET invno='$inv_no',status='Invoiced',inv_date='$inv_date' WHERE origin='$getorigin' AND comp_code='$stncode' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed' $ccfilter");
					$croinvoiceamt = $db->query("SELECT SUM(amt) as crototal FROM opdata WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
					//amount calculations
					$total_amt = round($croinvoiceamt->crototal);
					//calculate fsc
					$custfsc_and_handling = getcustomerfscandhandling($db,$stncode,$cust_code,$total_amt);
					$customer_fsc = $custfsc_and_handling[0];
					$customer_fschandling = round($custfsc_and_handling[1]);

					//enc fsc
					$fsc= $total_amt/100*$customer_fsc;
					$invamt= round($total_amt+$fsc+$customer_fschandling);
					$gst= round(($invamt/100)*18);
					//$net= round($total_amt+$fsc+$gst);
	
					//check gst for local and national
					$customergst = $crocustomer->cust_gstin;
					$getcompdetail = $db->query("SELECT stn_gstin FROM station WITH (NOLOCK) WHERE stncode='$stncode'")->fetch(PDO::FETCH_OBJ);
					$companygst = $getcompdetail->stn_gstin;
					if(substr($customergst,0,2)==substr($companygst,0,2)){
						$sgst = $gst/2;
						$cgst = $gst/2;
						$igst = '0';
					}
					else if(empty($customergst)){
						$sgst = $gst/2;
						$cgst = $gst/2;
						$igst = '0';   
					}
					else{
						$sgst = '0';
						$cgst = '0';
						$igst = $gst;
					}
					$tax = $igst+$cgst+$sgst;
					$net= round($total_amt+$fsc+$tax+$customer_fschandling);
					//update into invoice
					$invoice_update = $db->query("UPDATE invoice SET fhandcharge='$customer_fschandling',grandamt='$total_amt',fsc='$fsc',igst='$igst',cgst='$cgst',sgst='$sgst',invamt='$invamt',netamt='$net' WHERE invno='$inv_no' AND comp_code='$stncode'");
				}
				//return $inv_no;
			}
			else
			{
			$getcustomer = $db->query("SELECT cust_code FROM credit WITH (NOLOCK) WHERE cust_code='$cust_code' AND invno IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND comp_code='$stncode'");
			if($getcustomer->rowCount()==0)
			   {
			   return 'empty';
			   }
		   else{
			$getcust_lockstatus = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE comp_code='$stncode' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);
			$string = $getcust_lockstatus->tr_lock_date;
            $data   = preg_split('/To/', $string);
            $getdate = date('Y-m-d');
            $currentdate = date('Y-m-d', strtotime($getdate));
			if(($currentdate >= trim($data[0])) && ($currentdate <= trim($data[1])) && $getcust_lockstatus->tr_lock == 1 || $getcust_lockstatus->tr_lock == 1){

				
			}else{
			//$be_cate_code = $db->query("SELECT brcate_code FROM branch WITH (NOLOCK) WHERE br_code='$br_code' AND stncode='$stncode'")->fetch(PDO::FETCH_OBJ);
			$lastinvno = $db->query("SELECT TOP(1) * FROM invoice WITH (NOLOCK) WHERE comp_code='$stncode' AND SUBSTRING(invno,0,4)=comp_code AND cast(dbo.GetNumericValue(invno) AS decimal)>30000000 ORDER BY cast(dbo.GetNumericValue(invno) AS decimal) desc");
			if($lastinvno->rowCount()==0){
				$inv_no = $stncode.'30000001'; 
			}
			else{
				$fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);                
				$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$fetchinvno->invno);
				$inv_no = $stncode.($arr[1]+1);                                 
			}
			   //insert into invoice
			   $db->query("INSERT INTO invoice (invno,invdate,comp_code,br_code,cust_code,fmdate,todate,grandamt,fsc,igst,cgst,sgst,invamt,netamt,spl_discount,status,user_name,date_added,rcvdamt)
			   VALUES ('$inv_no','$inv_date','$stncode','$br_code','$cust_code','$trans_date_from','$trans_date_to','0','0','0','0','0','0','0','0','Pending','$user_name','$date_added','0')");
			   
			   $getfscofcustomer = $db->query("SELECT cust_fsc,cust_gstin,fsc_type FROM customer WITH (NOLOCK) WHERE cust_code='$cust_code' AND comp_code='$stncode'");
			   $rowgetfsc = $getfscofcustomer->fetch(PDO::FETCH_ASSOC);
			   $fsc_cust = $rowgetfsc['cust_fsc'];
			   $db->query("UPDATE credit WITH (ROWLOCK) SET invno='$inv_no',status='Invoiced' WHERE cust_code='$cust_code' AND invno IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND comp_code='$stncode' AND status NOT IN ('Noservice-return')");
			   $db->query("UPDATE credit WITH (ROWLOCK) SET invno='$inv_no' WHERE cust_code='$cust_code' AND invno IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND comp_code='$stncode' AND status='Noservice-return'");
			   $getcnotes = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$stncode'");
			   $getcnotes1 = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$stncode' AND status='Noservice-return'");

			   while($cnotesrow = $getcnotes->fetch(PDO::FETCH_ASSOC))
			   {
				   $cno = $cnotesrow['cno'];
				   $cnote_serial = $cnotesrow['cnote_serial'];
				   $db->query("UPDATE cnotenumber WITH (ROWLOCK) SET invno='$inv_no',cnote_status='Invoiced' WHERE cnote_number='$cno' AND cnote_serial='$cnote_serial' AND cnote_station='$stncode' AND bdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND cnote_status NOT IN ('Noservice-return')");
				//    $db->query("UPDATE cnotenumber WITH (ROWLOCK) SET invno='$inv_no' WHERE cnote_number='$cno' AND cnote_station='$stncode' AND bdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND cnote_status = 'Noservice-return'");
			   }

			   while($cnotesrow1 = $getcnotes1->fetch(PDO::FETCH_ASSOC))
			   {
				   $cno = $cnotesrow1['cno'];
				   $cnote_serial = $cnotesrow1['cnote_serial'];
				   $db->query("UPDATE cnotenumber WITH (ROWLOCK) SET invno='$inv_no' WHERE cnote_number='$cno' AND cnote_serial='$cnote_serial' AND cnote_station='$stncode' AND bdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND cnote_status = 'Noservice-return'");
			   }

               $getcod_weight = $db->query("SELECT count(cno) as codweight FROM credit WHERE cnote_serial='COD' AND status='Invoiced' AND invno='$inv_no' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
            $calculated_cod_weight = $getcod_weight->codweight;

			   $getcode_consignment = $db->query("SELECT tdate,count(cno) as countcod FROM credit  WHERE comp_code='$stncode' AND invno='$inv_no' AND cnote_serial='COD' group by tdate ORDER BY tdate")->fetch(PDO::FETCH_OBJ);
               $codcount = $getcode_consignment->countcod;


               function getcoderate($stncode,$db,$cust_code){
                    $getcustomerinfo = $db->query("SELECT * FROM customer WHERE comp_code='$stncode' AND cust_code='$cust_code'");
                    $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);
                    $cod_per_kg = $rowcustomer->cod;
                    return $cod_per_kg;
               }

               $codvalue_wt = (($codcount==0)? '0':getcoderate($stncode,$db,$cust_code));
			   $codvalue =  ($calculated_cod_weight*$codvalue_wt);


			   $total_amount = $db->query("SELECT SUM(amt) AS total FROM credit WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$stncode'");
			   $row_total = $total_amount->fetch(PDO::FETCH_ASSOC);
			   $total_amt = ($row_total['total']);
			   //calculate fsc
			   $custfsc_and_handling = getcustomerfscandhandling($db,$stncode,$cust_code,$total_amt);

			   $customer_fsc = $custfsc_and_handling[0];
			   $customer_fschandling = ($custfsc_and_handling[1]);
			   //enc fsc
			   $fsc= $total_amt/100*$customer_fsc;
			  // $invamt= round($total_amt+$fsc+$customer_fschandling);


			  $dis_rs = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE cust_code='$cust_code' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
			  $discount_pc = $dis_rs->spl_discountD/100;

			   $cal_dis_amt = ($total_amt+$fsc+$customer_fschandling+$codvalue)*($discount_pc); 
			   $invamt = ($total_amt+$fsc+$customer_fschandling+$codvalue)-$cal_dis_amt;
			   $gst= (($invamt/100)*18);

			   //check gst for local and national
			   $customergst = $rowgetfsc['cust_gstin'];
			   $getcompdetail = $db->query("SELECT stn_gstin FROM station WITH (NOLOCK) WHERE stncode='$stncode'")->fetch(PDO::FETCH_OBJ);
			   $companygst = $getcompdetail->stn_gstin;
			   if(substr($customergst,0,2)==substr($companygst,0,2)){
				   $sgst = $gst/2;
				   $cgst = $gst/2;
				   $igst = '0';   
			   }
			   else if(empty($customergst)){
				   $sgst = $gst/2;
				   $cgst = $gst/2;
				   $igst = '0';
			   }
			   else{
				   $sgst = '0';
				   $cgst = '0';
				   $igst = $gst;
			   }

			   
			   $tax = $igst+$cgst+$sgst;
			  
			   $net = number_format($invamt+$tax,2);

				//$rowinvoice->grandamt+$rowinvoice->fsc+$rowinvoice->fhandcharge
				// $multiply = '*';
				// $calculate_dicount = number_format($total_amt+$fsc+$customer_fschandling,2); 
				// $spl = $calculate_dicount;
		

			
			   //update into invoice
			  $db->query("UPDATE invoice WITH (ROWLOCK) SET fhandcharge='$customer_fschandling',grandamt='".number_format($total_amt,2)."',fsc='$fsc',spl_discount='".number_format($cal_dis_amt,2)."',igst='$igst',cgst='$cgst',sgst='$sgst',invamt='".number_format($invamt,2)."',netamt='$net',cod_per_keg_total='".number_format($codvalue,2)."',cust_gstin='$customergst' WHERE invno='$inv_no' AND comp_code='$stncode'");
			   //add logs
			   $yourbrowser   = getBrowser()['name'];
			   $user_surename = get_current_user();
			   $platform      = getBrowser()['platform'];   
			   $remote_addr   = getBrowser()['remote_addr'];  
			   $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Invoice No $inv_no has Generated','$yourbrowser')");
			   //end
			   return $inv_no;
			   }
			}
			}
			echo json_encode(array(($total_amt+$fsc+$customer_fschandling),($discount_pc)));
		}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------









		function getcustomerfscandhandling($db,$comp_code,$cust_code,$total_amt){
			$getfscofcustomer = $db->query("SELECT fhandling_charges,cust_fsc,cust_gstin,fsc_type,br_code FROM customer WITH (NOLOCK) WHERE cust_code='$cust_code' AND comp_code='$comp_code'");
			$rowgetfsc = $getfscofcustomer->fetch(PDO::FETCH_OBJ);
			if($rowgetfsc->fsc_type=='D'){
				$fscbr = $db->query("SELECT branchtype FROM branch WITH (NOLOCK) WHERE stncode='$comp_code' AND br_code='$rowgetfsc->br_code'")->fetch(PDO::FETCH_OBJ);
				$getfsc = $db->query("SELECT fsc,f_hand_charge FROM fscmaster WITH (NOLOCK) WHERE comp_code='$comp_code' AND min_sales_amt<='$total_amt' AND max_sales_amt>='$total_amt' AND branchtype='$fscbr->branchtype' ORDER BY tdate DESC");
				if($getfsc->rowCount()==0){
					$customer_fsc = 0;
					$f_hand_charge = 0;
				}
				else{
					$fscval = $getfsc->fetch(PDO::FETCH_OBJ);
					$customer_fsc = $fscval->fsc;
					$f_hand_charge = $fscval->f_hand_charge;
				}
			}
			else{
				$customer_fsc = $rowgetfsc->cust_fsc;
                $f_hand_charge = $rowgetfsc->fhandling_charges;
			}
			return array($customer_fsc,$f_hand_charge);
		}

		//send outstanding report by mail
		if(isset($_POST['rep_cust_code'])){
			 sendcustomeroutstandingbymail($db,$_POST['rep_cust_code'],$_POST['rep_station'],$_POST['rep_mail_user']);
		}
		function sendcustomeroutstandingbymail($db,$rep_cust_code,$rep_station,$rep_mail_user){
			//get customer mail
			$custemail = $db->query("SELECT cust_email FROM customer WITH (NOLOCK) WHERE comp_code='$rep_station' AND cust_code='$rep_cust_code'")->fetch(PDO::FETCH_OBJ);
			//get pending value
			$getpendingvalue = $db->query("SELECT sum(netamt) totalpending FROM invoice WITH (NOLOCK) WHERE comp_code='$rep_station' AND cust_code='$rep_cust_code'")->fetch(PDO::FETCH_OBJ);
			//get invoices count
			$getpendingcount = $db->query("SELECT count(*) as counts FROM invoice WITH (NOLOCK) WHERE comp_code='$rep_station' AND cust_code='$rep_cust_code' AND status='pending'")->fetch(PDO::FETCH_OBJ);

			$mail = new PHPMailer;
			//$mail->SMTPDebug = 2;
			$mail->IsSMTP();   // Set mailer to use SMTP
			$mail->isSendMail();
			$mail->Host = 'smtp.bizmail.yahoo.com';   // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;  // Enable SMTP authentication
			$mail->Username = 'pcbs@pcnl.in'; // your email id
			$mail->Password = 'hcygtgklfglvigvw'; // your password                      
			$mail->Port = 465;     //587 is used for Outgoing Mail (SMTP) Server.
			$mail->SMTPSecure = 'ssl';                     
			$mail->SetFrom('pcbs@pcnl.in', 'PCNL '.$rep_station);
			$mail->addAddress($custemail->cust_email);   // Add a recipient
			//$mail->AddReplyTo($rowcompany->stn_email, 'PCNL '.$invnostn);
			$mail->isHTML(true);  // Set email format to HTML
			
			$bodyContent = '<h7><strong>Dear Customer,</strong></h7>';
			$bodyContent .= '<p>You have '.$getpendingcount->counts.' Invoices pending for amount Rs.'.round($getpendingvalue->totalpending).'/-.</p>';
			//$bodyContent .= '<p>Please find the attached invoice with this mail.</p><br>';
			$bodyContent .= '<p><strong>Kind Regards,</strong></p><p>PCNL '.$rep_station.'</p>';
			$mail->Subject = 'Invoice Outstanding Report from PCNL';
			//$mail->addStringAttachment($attachment_file,''.$rowinvoice->cust_code.'-'.$invno.'.pdf');
			$mail->Body    = $bodyContent;
			if(!$mail->send()) 
			{
			  echo 'Email was not sent.';
			  echo 'Mailer error: ' . $mail->ErrorInfo;
			  $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','OutstandingInvoice','Created','Email was not Sent to Customer $rep_cust_code - ERROR : $mail->ErrorInfo','$rep_mail_user',getdate())");   
	    
			}
			 else
			 {
			  $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','OutstandingInvoice','$rep_cust_code','Email has been Sent to Customer $rep_cust_code','$rep_mail_user',getdate())");   
			  echo 'Email has been sent.';
			}
		}

		//get invoice details for payment
		if(isset($_POST['de_invno'])){
			echo getinvoicedetails($db,$_POST['de_invno'],$_POST['de_stn'],$_POST['de_cust']);
		}
		function getinvoicedetails($db,$de_invno,$de_stn,$de_cust){
			 $invdata = $db->query("SELECT * FROM invoice WITH (NOLOCK) WHERE comp_code='$de_stn' AND cust_code='$de_cust' AND invno='$de_invno'")->fetch(PDO::FETCH_OBJ);
			 return json_encode($invdata);
		}
		//get invoice details for payment
		//get invoices details for payment
		if(isset($_POST['selectedInvoices'])){
			echo getinvoicesdetails($db,$_POST['selectedInvoices'],$_POST['stationcde'],$_POST['customercde']);
		}
		function getinvoicesdetails($db,$selectedInvoices,$stationcde,$customercde){
			$invoices = "'".implode("','",$selectedInvoices)."'";
			$invdata = $db->query("SELECT invno,invdate,br_code,cust_code,grandamt,fsc,(igst+sgst+cgst) as gstsum,invamt,netamt,rcvdamt,(netamt-rcvdamt) as balamt FROM invoice WITH (NOLOCK) WHERE comp_code='$stationcde' AND cust_code='$customercde' AND invno in ($invoices) ORDER BY invdate,invno");
			$json = [];
			while($row = $invdata->fetch(PDO::FETCH_OBJ)){
				$json[]=$row;
			}
			return json_encode($json);
		}
		//get invoices details for payment
		//load data for pagination in destination table
		if(isset($_POST['dtbquery'])){
			echo select10rows($db,$_POST['dtbquery'],$_POST['dtbpageno'],$_POST['dtbcolumns']);
		  }
		  function select10rows($db,$dtbquery,$dtbpageno,$dtbcolumn2){
			  $dtbcolumns = explode(",",$dtbcolumn2);
			  $t10query = $db->query($dtbquery." OFFSET ".$dtbpageno." ROWS  FETCH NEXT 10 ROWS ONLY");
			  $datum = array();
			  $index = 0;
			  while($t10row=$t10query->fetch(PDO::FETCH_OBJ)){$index++;
				  $data = array();
					foreach($dtbcolumns as $dtbcolumn){
				  //console_log($dtbcolumn);
					  $data[$dtbcolumn] = $t10row->$dtbcolumn;
					}
				$datum[$index]=$data;
			  }
			  return json_encode($datum);
		  }
		//logs function
		function getBrowser()
		{
		    $u_agent = $_SERVER['HTTP_USER_AGENT'];
		    $bname = 'Unknown';
		    $platform = 'Unknown';
		    $version= "";
		
		    //First get the platform?
		    if (preg_match('/linux/i', $u_agent)) {
			   $platform = 'linux';
		    }
		    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			   $platform = 'mac';
		    }
		    elseif (preg_match('/windows|win32/i', $u_agent)) {
			   $platform = 'windows';
		    }
		
		    // Next get the name of the useragent yes seperately and for good reason
		    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		    {
			   $bname = 'Internet Explorer';
			   $ub = "MSIE";
		    }
		    elseif(preg_match('/Firefox/i',$u_agent))
		    {
			   $bname = 'Mozilla Firefox';
			   $ub = "Firefox";
		    }
		    elseif(preg_match('/Chrome/i',$u_agent))
		    {
			   $bname = 'Google Chrome';
			   $ub = "Chrome";
		    }
		    elseif(preg_match('/Safari/i',$u_agent))
		    {
			   $bname = 'Apple Safari';
			   $ub = "Safari";
		    }
		    elseif(preg_match('/Opera/i',$u_agent))
		    {
			   $bname = 'Opera';
			   $ub = "Opera";
		    }
		    elseif(preg_match('/Netscape/i',$u_agent))
		    {
			   $bname = 'Netscape';
			   $ub = "Netscape";
		    }
		    
		    if (!empty($_SERVER['HTTP_CLIENT_IP']))   
			   {
				  $remote_addr = $_SERVER['HTTP_CLIENT_IP'];
			   }
			   //whether ip is from proxy
			   elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
			   {
				  $remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
			   }
			   //whether ip is from remote address
			   else
			   {
				  $remote_addr = $_SERVER['REMOTE_ADDR'];
			   }
		    // finally get the correct version number
		    $known = array('Version', $ub, 'other');
		    $pattern = '#(?<browser>' . join('|', $known) .
		    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		    if (!preg_match_all($pattern, $u_agent, $matches)) {
			   // we have no matching number just continue
		    }
		
		    // see how many we have
		    $i = count($matches['browser']);
		    if ($i != 1) {
			   //we will have two since we are not using 'other' argument yet
			   //see if version is before or after the name
			   if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				  $version= $matches['version'][0];
			   }
			   else {
				  $version= $matches['version'][1];
			   }
		    }
		    else {
			   $version= $matches['version'][0];
		    }
		
		    // check if we have a number
		    if ($version==null || $version=="") {$version="?";}
		
		    return array(
			   'userAgent' => $u_agent,
			   'name'      => $bname,
			   'version'   => $version,
			   'platform'  => $platform,
			   'pattern'    => $pattern,
			   'remote_addr' => $remote_addr
		    );
		}
		//end logs

		function console_log($output, $with_script_tags = true) {
			$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
			');';
			if ($with_script_tags) {
				$js_code = '<script>' . $js_code . '</script>';
			}
			echo $js_code;
		}