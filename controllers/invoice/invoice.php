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
error_reporting(0);

class invoice extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - invoice';
		$this->view->render('invoice/invoice');
	}
}

	if(isset($_POST['generateinvoice']))
	{
		try{
		date_default_timezone_set('Asia/Kolkata');
		$stncode           = $_POST['stncode'];
		$stnname           = $_POST['stnname'];
		$br_code           = strtoupper($_POST['br_code']);
		$br_name           = $_POST['br_name'];
		$cust_code         = strtoupper($_POST['cust_code']);
		$trans_date_from   = $_POST['trans_date_from'];
		$trans_date_to     = $_POST['trans_date_to'];
		$inv_amt_thresholdval = $_POST['inv_amt_threshold'];
		$inv_date          = $_POST['inv_date'];
		$inv_no            = $_POST['inv_no'];
		$category          = $_POST['category'];
		$user_name         = $_POST['user_name'];
		$date_added        = date('Y-m-d H:i:s');

		if(!empty($trans_date_from) && !empty($trans_date_to))
		{
		if($category=='CRO'){
			if(empty($inv_amt_thresholdval)){
				$inv_amt_threshold = '0';
			}
			else{
				$inv_amt_threshold = $inv_amt_thresholdval;
			}
			if($cust_code == 'ALL' || trim($cust_code) =='')
			{  
				$getcrocusts = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE br_code='TR' AND comp_code='$stncode'");
				while($rowgetcrocusts = $getcrocusts->fetch(PDO::FETCH_OBJ)){
				$lastinvno = $db->query("SELECT TOP(1) * FROM invoice WITH (NOLOCK) WHERE invno LIKE '$stncode".'TR'."%' ORDER BY invdate DESC,cast(dbo.GetNumericValue(invno) AS decimal) desc");
				if($lastinvno->rowCount()==0){
					$inv_no = $stncode.'TR0000001';
				}
				else{
					$fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);
					$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$fetchinvno->invno);
					$inv_no = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
				}
				//filter for tr and dly
				if($rowgetcrocusts->cctr=='Y' && $rowgetcrocusts->ccdly=='Y'){
					$ccfilter = "AND (mode_code='DE' or ttype='MFT')";
				}
				else{
					if($rowgetcrocusts->cctr=='Y'){
						$ccfilter = "AND ttype='MFT'";
					}
					else if($rowgetcrocusts->ccdly=='Y'){
						$ccfilter = "AND mode_code='DE'";
					}
					else{
						$ccfilter = "";
					}
				}
				
				//end
				//get data from ccharge
				$getorigin = rtrim(trim($rowgetcrocusts->cust_code),'TR');
                    $checkdataempty = $db->query("SELECT * FROM opdata WHERE origin='$getorigin' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed' AND amt>='$inv_amt_threshold' $ccfilter");
                    if($checkdataempty->rowCount()==0){
				//for empty invoice
                    }else{
				//insert into invoice
				$invoice_insert = $db->query("INSERT INTO invoice (invno,invdate,comp_code,br_code,cust_code,fmdate,todate,grandamt,fsc,igst,cgst,sgst,invamt,netamt,status,user_name,date_added,rcvdamt)
			     VALUES ('$inv_no','$inv_date','$stncode','$br_code','$rowgetcrocusts->cust_code','$trans_date_from','$trans_date_to','0','0','0','0','0','0','0','Pending','$user_name','$date_added','0')");

				$db->query("UPDATE opdata SET invno='$inv_no',status='Invoiced',inv_date='$inv_date' WHERE origin='$getorigin' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed' AND amt>='$inv_amt_threshold'  $ccfilter");
				$croinvoiceamt = $db->query("SELECT SUM(amt) as crototal FROM opdata WHERE invno='$inv_no' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);

				//amount calculations
				$total_amt = round($croinvoiceamt->crototal);
				//calculate fsc
				if($rowgetcrocusts->fsc_type=='D'){
					$fscbr = $db->query("SELECT cate_code FROM branch WHERE stncode='$stncode' AND br_code='$br_code'")->fetch(PDO::FETCH_OBJ);
					$getfsc = $db->query("SELECT fsc,f_hand_charge FROM fscmaster WHERE comp_code='$stncode' AND min_sales_amt<='$total_amt' AND max_sales_amt>='$total_amt' AND cate_code='$fscbr->cate_code' ORDER BY tdate DESC");
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
					$customer_fsc = $rowgetcrocusts->cust_fsc;
					$f_hand_charge = 0;
				}
				//enc fsc
				$fsc= round(($total_amt/100)*$customer_fsc);
				$invamt= round($total_amt+$fsc);
				$gst= round(($invamt/100)*18);
				//$net= round($total_amt+$fsc+$gst);

				//check gst for local and national
				$customergst = $rowgetcrocusts->cust_gstin;
				$getcompdetail = $db->query("SELECT stn_gstin FROM station WHERE stncode='$stncode'")->fetch(PDO::FETCH_OBJ);
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
				$net= round($total_amt+$fsc+$tax);
				//update into invoice
				$invoice_update = $db->query("UPDATE invoice SET grandamt='$total_amt',fsc='$fsc',igst='$igst',cgst='$cgst',sgst='$sgst',invamt='$invamt',netamt='$net' WHERE invno='$inv_no' AND comp_code='$stncode'");
				}
				}
			}
			else{
				
				//getcustomers
				$crocustomer = $db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
									//filter for tr and dly
				if($crocustomer->cctr=='Y' && $crocustomer->ccdly=='Y'){
					$ccfilter = "AND (mode_code='DE' or ttype='MFT')";
				}
				else{
					
					if($crocustomer->cctr=='Y'){
						
						$ccfilter = "AND ttype='MFT'";
					}
					else if($crocustomer->ccdly=='Y'){
						$ccfilter = "AND mode_code='DE'";
					}
					else{
						$ccfilter = "AND mode_code='NONE'";
					}
				}
				
				//end
				$getorigin = rtrim(trim($cust_code),'TR');
				$chkopdatarow = $db->query("SELECT count(*) as counts FROM opdata  WHERE origin='$getorigin' AND comp_code='$stncode' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed' AND amt>='$inv_amt_threshold' $ccfilter")->fetch(PDO::FETCH_OBJ);
				if($chkopdatarow->counts==0){
					header("Location:invoice?emptyinvoice");
					exit;
				}
				else{
				
				//insert into invoice
				$invoice_insert = $db->query("INSERT INTO invoice (invno,invdate,comp_code,br_code,cust_code,fmdate,todate,grandamt,fsc,igst,cgst,sgst,invamt,netamt,status,user_name,date_added,rcvdamt)
				VALUES ('$inv_no','$inv_date','$stncode','$br_code','$cust_code','$trans_date_from','$trans_date_to','0','0','0','0','0','0','0','Pending','$user_name','$date_added','0')");
				//get data from opdata
				
				
				$db->query("UPDATE opdata SET invno='$inv_no',status='Invoiced',inv_date='$inv_date' WHERE origin='$getorigin' AND comp_code='$stncode' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed' AND amt>='$inv_amt_threshold' $ccfilter");
				$croinvoiceamt = $db->query("SELECT SUM(amt) as crototal FROM opdata WHERE invno='$inv_no' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
				//amount calculations
				$total_amt = round($croinvoiceamt->crototal);
				//calculate fsc
				if($crocustomer->fsc_type=='D'){
					$fscbr = $db->query("SELECT cate_code FROM branch WHERE stncode='$stncode' AND br_code='$br_code'")->fetch(PDO::FETCH_OBJ);
					$getfsc = $db->query("SELECT fsc,f_hand_charge FROM fscmaster WHERE comp_code='$stncode' AND min_sales_amt<='$total_amt' AND max_sales_amt>='$total_amt' AND cate_code='$fscbr->cate_code' ORDER BY tdate DESC");
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
					$customer_fsc = $crocustomer->cust_fsc;
					$f_hand_charge = 0;
				}
				//enc fsc
				$fsc= round(($total_amt/100)*$customer_fsc);
				$invamt= round($total_amt+$fsc);
				$gst= round(($invamt/100)*18);
				//$net= round($total_amt+$fsc+$gst);

				//check gst for local and national
				$customergst = $crocustomer->cust_gstin;
				$getcompdetail = $db->query("SELECT stn_gstin FROM station WHERE stncode='$stncode'")->fetch(PDO::FETCH_OBJ);
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
				$net= round($total_amt+$fsc+$tax);
				//update into invoice
				$invoice_update = $db->query("UPDATE invoice SET grandamt='$total_amt',fsc='$fsc',igst='$igst',cgst='$cgst',sgst='$sgst',invamt='$invamt',netamt='$net' WHERE invno='$inv_no' AND comp_code='$stncode'");
			}
			}
			Header("Location:invoice?inv_no=$inv_no&&total=$total_amt&&fsc=$fsc&&invamt=$invamt&&gst=$tax&&net=$net&type='ccharge'");
		}
		else
		{
		if($cust_code == 'ALL' || trim($cust_code) =='')
		{  
			$getcustomers = $db->query("SELECT DISTINCT cust_code FROM credit WITH (NOLOCK) WHERE comp_code='$stncode' AND br_code='$br_code' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed'");
		if($getcustomers->rowCount()==0)
			{
			header("Location:invoice?emptyinvoice=$cust_code");
			}
		else{
			while($rowgetcustomers = $getcustomers->fetch(PDO::FETCH_ASSOC))
			{
			$be_cate_code = $db->query("SELECT brcate_code FROM branch WITH (NOLOCK) WHERE br_code='$br_code' AND stncode='$stncode'")->fetch(PDO::FETCH_OBJ);
			$lastinvno = $db->query("SELECT TOP(1) * FROM invoice WITH (NOLOCK) WHERE (SELECT dbo.fn_GetAlphabetsOnly(invno))='$be_cate_code->brcate_code' AND comp_code='$stncode' ORDER BY invdate DESC,cast(dbo.GetNumericValue(invno) AS decimal) desc");
			if($lastinvno->rowCount()==0){
				$invno2 = $be_cate_code->brcate_code.'10001'; 
			}
			else{
				$fetchinvno = $lastinvno->fetch(PDO::FETCH_OBJ);
				$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$fetchinvno->invno);
				$invno2 = $be_cate_code->brcate_code.($arr[1]+1);
			}

			$cust_code1 = $rowgetcustomers['cust_code'];
			//insert into invoice
			$invoice_insert = $db->query("INSERT INTO invoice (invno,invdate,comp_code,br_code,cust_code,fmdate,todate,grandamt,fsc,igst,cgst,sgst,invamt,netamt,status,user_name,date_added,rcvdamt)
			VALUES ('$invno2','$inv_date','$stncode','$br_code','$cust_code1','$trans_date_from','$trans_date_to','0','0','0','0','0','0','0','Pending','$user_name','$date_added','0')");
			
			$getfscofcustomer = $db->query("SELECT cust_fsc,cust_gstin,fsc_type FROM customer WITH (NOLOCK) WHERE cust_code='$cust_code1' AND comp_code='$stncode'");
			$rowgetfsc = $getfscofcustomer->fetch(PDO::FETCH_ASSOC);
			$fsc_cust = $rowgetfsc['cust_fsc'];
			$updatecredittb = $db->query("UPDATE credit WITH (ROWLOCK) SET invno='$invno2',status='Invoiced' WHERE cust_code='$cust_code1' AND invno IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND comp_code='$stncode'");
			$getcnotes = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE invno='$invno2' AND comp_code='$stncode'");
			while($cnotesrow = $getcnotes->fetch(PDO::FETCH_ASSOC))
			{
				$cno = $cnotesrow['cno'];
				$updatecnotenumbertb = $db->query("UPDATE cnotenumber SET invno='$invno2',cnote_status='Invoiced' WHERE cnote_number='$cno'  AND cnote_station='$stncode' AND bdate BETWEEN '$trans_date_from' AND '$trans_date_to'");
			}
			$total_amount = $db->query("SELECT SUM(amt) AS total FROM credit WITH (NOLOCK) WHERE invno='$invno2' AND comp_code='$stncode'");
			$row_total = $total_amount->fetch(PDO::FETCH_ASSOC);
			$total_amt = round($row_total['total']);
			//calculate fsc
			if($rowgetfsc['fsc_type']=='D'){
				$fscbr = $db->query("SELECT cate_code FROM branch WITH (NOLOCK) WHERE stncode='$stncode' AND br_code='$br_code'")->fetch(PDO::FETCH_OBJ);
				$getfsc = $db->query("SELECT fsc,f_hand_charge FROM fscmaster WITH (NOLOCK) WHERE comp_code='$stncode' AND min_sales_amt<='$total_amt' AND max_sales_amt>='$total_amt' AND cate_code='$fscbr->cate_code' ORDER BY tdate DESC");
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
				$customer_fsc = $fsc_cust;
				$f_hand_charge = 0;
			}
			//enc fsc
			$fsc= round(($total_amt/100)*$customer_fsc);
			$invamt= round($total_amt+$fsc);
			$gst= round(($invamt/100)*18);
			//$net= round($total_amt+$fsc+$gst);

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
			$net= round($total_amt+$fsc+$tax);

			//update into invoice
			$invoice_update = $db->query("UPDATE invoice WITH (ROWLOCK) SET grandamt='$total_amt',fsc='$fsc',igst='$igst',cgst='$cgst',sgst='$sgst',invamt='$invamt',netamt='$net' WHERE invno='$invno2' AND comp_code='$stncode'");
			
			//add logs
			$yourbrowser   = getBrowser()['name'];
			$user_surename = get_current_user();
			$platform      = getBrowser()['platform'];   
			$remote_addr   = getBrowser()['remote_addr'];  
			$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Invoice No $invno2 has Generated','$yourbrowser')");
			//end
			$totalarray[] = $total_amt;$fscarray[] = $fsc;$invamtarray[] = $invamt;$gstarray[] = $tax;$netarray[] = $net;
			}
			$alltotal = array_sum($totalarray);$allfsc = array_sum($fscarray);$allinvamt = array_sum($invamtarray);$allgst = array_sum($gstarray);$allnet = array_sum($netarray); 
			 header("Location: invoice?inv_no=$inv_no&&total=$alltotal&&fsc=$allfsc&&invamt=$allinvamt&&gst=$allgst&&net=$allnet");
		}
		}
		else{
			  $getcustomer = $db->query("SELECT cust_code FROM credit WITH (NOLOCK) WHERE cust_code='$cust_code' AND invno IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND comp_code='$stncode'");
			  if($getcustomer->rowCount()==0)
				{
				header("Location:invoice?emptyinvoice=$cust_code");
				}
			else{
				//insert into invoice
				$invoice_insert = $db->query("INSERT INTO invoice (invno,invdate,comp_code,br_code,cust_code,fmdate,todate,grandamt,fsc,igst,cgst,sgst,invamt,netamt,status,user_name,date_added,rcvdamt)
				VALUES ('$inv_no','$inv_date','$stncode','$br_code','$cust_code','$trans_date_from','$trans_date_to','0','0','0','0','0','0','0','Pending','$user_name','$date_added','0')");
				
				$getfscofcustomer = $db->query("SELECT cust_fsc,cust_gstin,fsc_type FROM customer WITH (NOLOCK) WHERE cust_code='$cust_code' AND comp_code='$stncode'");
				$rowgetfsc = $getfscofcustomer->fetch(PDO::FETCH_ASSOC);
				$fsc_cust = $rowgetfsc['cust_fsc'];
				$updatecredittb = $db->query("UPDATE credit WITH (ROWLOCK) SET invno='$inv_no',status='Invoiced' WHERE cust_code='$cust_code' AND invno IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND comp_code='$stncode'");
				$getcnotes = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$stncode'");
				while($cnotesrow = $getcnotes->fetch(PDO::FETCH_ASSOC))
				{
					$cno = $cnotesrow['cno'];
					$updatecnotenumbertb = $db->query("UPDATE cnotenumber WITH (ROWLOCK) SET invno='$inv_no',cnote_status='Invoiced' WHERE cnote_number='$cno' AND cnote_station='$stncode' AND bdate BETWEEN '$trans_date_from' AND '$trans_date_to'");
				}
				$total_amount = $db->query("SELECT SUM(amt) AS total FROM credit WITH (NOLOCK) WHERE invno='$inv_no' AND comp_code='$stncode'");
				$row_total = $total_amount->fetch(PDO::FETCH_ASSOC);
				$total_amt = round($row_total['total']);
				//calculate fsc
				if($rowgetfsc['fsc_type']=='D'){
					$fscbr = $db->query("SELECT cate_code FROM branch WITH (NOLOCK) WHERE stncode='$stncode' AND br_code='$br_code'")->fetch(PDO::FETCH_OBJ);
					$getfsc = $db->query("SELECT fsc,f_hand_charge FROM fscmaster WITH (NOLOCK) WHERE comp_code='$stncode' AND min_sales_amt<='$total_amt' AND max_sales_amt>='$total_amt' AND cate_code='$fscbr->cate_code' ORDER BY tdate DESC");
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
					$customer_fsc = $fsc_cust;
					$f_hand_charge = 0;
				}
				//enc fsc
				$fsc= round(($total_amt/100)*$customer_fsc);
				$invamt= round($total_amt+$fsc);
				$gst= round(($invamt/100)*18);

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
				$net= round($total_amt+$fsc+$tax);

				//update into invoice
				$invoice_update = $db->query("UPDATE invoice WITH (ROWLOCK) SET grandamt='$total_amt',fsc='$fsc',igst='$igst',cgst='$cgst',sgst='$sgst',invamt='$invamt',netamt='$net' WHERE invno='$inv_no' AND comp_code='$stncode'");
			
				//add logs
				$yourbrowser   = getBrowser()['name'];
				$user_surename = get_current_user();
				$platform      = getBrowser()['platform'];   
				$remote_addr   = getBrowser()['remote_addr'];  
				$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Invoice No $inv_no has Generated','$yourbrowser')");
				//end
				header("Location: invoice?inv_no=$inv_no&&total=$total_amt&&fsc=$fsc&&invamt=$invamt&&gst=$tax&&net=$net");
				}
				}
		}
			}
		else{
			header("Location: invoice?unsuccess=1");
		}
		}catch(PDOException $e){
			$yourbrowser   = getBrowser()['name'];
			$user_surename = get_current_user();
			$platform      = getBrowser()['platform'];   
			$remote_addr   = getBrowser()['remote_addr']; 
			$error_info	   = $e->errorInfo[1];
			$db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Unknown Error Occurred at Invoice ERROR_CODE:$error_info','$yourbrowser')"); 

		header("Location: invoicelist?error=2");
	}
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



		if(isset($_POST['action']) && $_POST['action']=='genrate_irn'){

			$invno = $_POST['invno'];
			$stncode = $_POST['stncode'];
			$user_name = $_POST['user_name'];

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
		  
		  // $inv_no = "AMB30000021";
		
		  // $stncode = "AMB";
		  // echo return_irn($authenticationToken,$SubscriptionId,$authToken,$sek,$inv_no,$db,$stncode)[0];
	  
	  
		  
			  $invdata = $db->query("SELECT *,CONVERT(DECIMAL(10,2),cgst) as cgstval,CONVERT(DECIMAL(10,2),sgst) as sgstval,CONVERT(DECIMAL(10,2),igst) as igstval FROM invoice WITH(NOLOCK) WHERE invno='$invno' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
			  $cnote_count = $db->query("SELECT COUNT(*) AS cnote FROM credit WITH(NOLOCK) WHERE invno='$invno' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
			  $getcompanyname = $db->query("SELECT TOP 1 * FROM station WHERE stncode='$invdata->comp_code'")->fetch(PDO::FETCH_OBJ);
			  $customer = $db->query("SELECT * FROM customer WHERE cust_code='$invdata->cust_code' AND comp_code='$invdata->comp_code'")->fetch(PDO::FETCH_OBJ);
			  $gstcode = substr($customer->cust_gstin,0,2);
			  $statename = $db->query("SELECT state_name FROM state WHERE gstcode='$gstcode'")->fetch(PDO::FETCH_OBJ);
	  
			$ch = curl_init();
		  $headers  = [
					"Content-Type: application/json",
					"AuthenticationToken: $authenticationToken",
					"SubscriptionId: $SubscriptionId",
					"Gstin: 29AABCR7796N000",
					"UserName: eRelyon",
					"AuthToken: $authToken",
					"Sek: $sek"
				  ];
				  $irndata = [
					"Irn"=> "",
					"Version"=> "1.1",
					"TranDtls"=> [
					  "TaxSch"=> "GST",
					  "SupTyp"=> "B2B",
					  "RegRev"=> "N",
					  "IgstOnIntra"=> "N"
					],
					"DocDtls"=> [
						"Typ"=> "INV",
						"No"=> "$invno",
						"Dt"=> "".date("d/m/Y",strtotime($invdata->invdate)).""
					],
					"SellerDtls"=> [
						"Gstin"=> "29AABCR7796N000",
						"LglNm"=> "$getcompanyname->stnname",
						"TrdNm"=> "$getcompanyname->stnname ",
						"Addr1"=> "$getcompanyname->stn_addr",
						"Loc"=> "$getcompanyname->stn_city",
						"Pin"=> 560086,
						"Stcd"=> "29",
						"Ph"=> "$getcompanyname->stn_phone",
						"Em"=> "$getcompanyname->stn_email"
				],
					"BuyerDtls"=> [
						"Gstin"=> "$customer->cust_gstin",
						"LglNm"=> "$customer->comp_name",
						"TrdNm"=> "$customer->br_name",
						"Pos"=> "".substr($customer->cust_gstin,0,2)."",
						"Addr1"=> "$customer->cust_addr",
						"Addr2"=> "$customer->cust_state",
						"Loc"=> "$statename->state_name",
						"Pin"=> (int)$customer->pincode,
						"Stcd"=> "".substr($customer->cust_gstin,0,2)."",
					],
					"ItemList"=> [
					  [
						"SlNo"=> "1",
						"PrdDesc"=> "COURIER",
						"IsServc"=> "Y",
						"HsnCd"=> "996812",
						"Qty"=> 1,
						"FreeQty"=> 0,
						"Unit"=> "PAC",
						"UnitPrice"=> (float)str_replace(',','',number_format($invdata->grandamt,2)),
						"TotAmt"=> (float)str_replace(',','',number_format($invdata->grandamt+$invdata->fsc+$invdata->fhandcharge+$invdata->cod_per_keg_total,2)),
						"Discount"=> (float)str_replace(',','',number_format($invdata->spl_discount,2)),
						"PreTaxVal"=> 0,
						"AssAmt"=> (float)str_replace(',','',number_format($invdata->invamt,2)),
						"GstRt"=> 18,
						"IgstAmt"=> (float)str_replace(',','',number_format($invdata->igst,2)),
						"CgstAmt"=> (float)str_replace(',','',number_format($invdata->cgst,2)),
						"SgstAmt"=> (float)str_replace(',','',number_format($invdata->sgst,2)),
						"CesAmt"=> 0,
						"CesRt"=> 0,
						"CesNonAdvlAmt"=> 0,
						"StateCesRt"=> 0,
						"StateCesAmt"=> 0,
						"StateCesNonAdvlAmt"=> 0,
						"OthChrg"=> 0,
						"TotItemVal"=> (float)str_replace(',','',number_format($invdata->netamt,2))
					  ]
					],
					"ValDtls"=> [
					  "AssVal"=> (float)str_replace(',','',number_format($invdata->invamt,2)),
					  "CgstVal"=> (float)str_replace(',','',number_format($invdata->cgst,2)),
					  "SgstVal"=> (float)str_replace(',','',number_format($invdata->sgst,2)),
					  "IgstVal"=> (float)str_replace(',','',number_format($invdata->igst,2)),
					  "CesVal"=> 0.0,
					  "StCesVal"=> 0.0,
					  "Discount"=> 0.0,
					  "OthChrg"=> 0.0,
					  "RndOffAmt"=> 0.0,
					  "TotInvVal"=> (float)str_replace(',','',number_format($invdata->netamt,2)),
					  "TotInvValFc"=> 0.0
					]
					
					
					];
		  
		  
					curl_setopt($ch, CURLOPT_URL,"https://demo.saralgsp.com/eicore/v1.03/Invoice");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($irndata));           
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					$result     = curl_exec($ch);
					$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					
					$data = json_decode($result, true);
					//echo $data['signedInvoice'];
					// echo $data['ackNo'];
					
				   // echo $data['ackDt'];
				   // echo $data['signedInvoice'];
				   // echo $data['signedQRCode'];
				   // echo $data['status'];
				   //echo $result;
				//    return array($data['irn'],$data['signedInvoice'],$result,$data['status'],json_encode($irndata));
		 
	  	  
		

             if(!empty($data['signedInvoice'])){
				$db->query("UPDATE invoice SET irn_no='".$data['irn']."',signedirn='".$data['signedInvoice']."',irn_status='Issued' WHERE invno='$invno' AND comp_code='$stncode'");
				$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$invno','Genrate Irn No','Invoice','Irn has been genrated successfully for $invno','$user_name',getdate())");

			 }else{
				$db->query("UPDATE invoice SET irn_status='invalid' WHERE invno='$invno' AND comp_code='$stncode'");

			 }

		  echo json_encode(array($result)); 
		  exit();
		}

		

		if(isset($_POST['action']) && $_POST['action']=='update_irn_error'){

			$invno = $_POST['invno'];
			$stncode = $_POST['stncode'];
			$db->query("UPDATE invoice SET irn_status='Invalid' WHERE invno='$invno' AND comp_code='$stncode'");
            echo json_encode(['success'=>200]);
		}




		if(isset($_POST['action']) && $_POST['action']=='genrateirn'){
            $invno_json = $_POST['invno_json'];
			$stncode = $_POST['print_station'];
            $user_name = $_POST['user_name'];

            $authenticationToken = genrateAuth1()[0];
            $subscriptionId = genrateAuth1()[1];
            $authurl = genrateAuth1()[2];

			$authToken = postauth1($authurl,$authenticationToken,$subscriptionId)[0];
			$sek = postauth1($authurl,$authenticationToken,$subscriptionId)[1];

			foreach($invno_json as $invnumber){
				$invno = $invnumber;
				  
				

				$invdata = $db->query("SELECT *,CONVERT(DECIMAL(10,2),cgst) as cgstval,CONVERT(DECIMAL(10,2),sgst) as sgstval,CONVERT(DECIMAL(10,2),igst) as igstval FROM invoice WITH(NOLOCK) WHERE invno='$invno' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
				$cnote_count = $db->query("SELECT COUNT(*) AS cnote FROM credit WITH(NOLOCK) WHERE invno='$invno' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
				$getcompanyname = $db->query("SELECT TOP 1 * FROM station WHERE stncode='$invdata->comp_code'")->fetch(PDO::FETCH_OBJ);
				$customer = $db->query("SELECT * FROM customer WHERE cust_code='$invdata->cust_code' AND comp_code='$invdata->comp_code'")->fetch(PDO::FETCH_OBJ);
				$gstcode = substr($customer->cust_gstin,0,2);
				$statename = $db->query("SELECT state_name FROM state WHERE gstcode='$gstcode'")->fetch(PDO::FETCH_OBJ);
		
			  $ch = curl_init();
			$headers  = [
					  "Content-Type: application/json",
					  "AuthenticationToken: $authenticationToken",
					  "SubscriptionId: $SubscriptionId",
					  "Gstin: 29AABCR7796N000",
					  "UserName: eRelyon",
					  "AuthToken: $authToken",
					  "Sek: $sek"
					];
					$irndata = [
					  "Irn"=> "",
					  "Version"=> "1.1",
					  "TranDtls"=> [
						"TaxSch"=> "GST",
						"SupTyp"=> "B2B",
						"RegRev"=> "N",
						"IgstOnIntra"=> "N"
					  ],
					  "DocDtls"=> [
						  "Typ"=> "INV",
						  "No"=> "$invno",
						  "Dt"=> "".date("d/m/Y",strtotime($invdata->invdate)).""
					  ],
					  "SellerDtls"=> [
						  "Gstin"=> "29AABCR7796N000",
						  "LglNm"=> "$getcompanyname->stnname",
						  "TrdNm"=> "$getcompanyname->stnname ",
						  "Addr1"=> "$getcompanyname->stn_addr",
						  "Loc"=> "$getcompanyname->stn_city",
						  "Pin"=> 560086,
						  "Stcd"=> "29",
						  "Ph"=> "$getcompanyname->stn_phone",
						  "Em"=> "$getcompanyname->stn_email"
				  ],
					  "BuyerDtls"=> [
						  "Gstin"=> "$customer->cust_gstin",
						  "LglNm"=> "$customer->comp_name",
						  "TrdNm"=> "$customer->br_name",
						  "Pos"=> "".substr($customer->cust_gstin,0,2)."",
						  "Addr1"=> "$customer->cust_addr",
						  "Addr2"=> "$customer->cust_state",
						  "Loc"=> "$statename->state_name",
						  "Pin"=> (int)$customer->pincode,
						  "Stcd"=> "".substr($customer->cust_gstin,0,2)."",
					  ],
					  "ItemList"=> [
						[
						  "SlNo"=> "1",
						  "PrdDesc"=> "COURIER",
						  "IsServc"=> "Y",
						  "HsnCd"=> "996812",
						  "Qty"=> 1,
						  "FreeQty"=> 0,
						  "Unit"=> "PAC",
						  "UnitPrice"=> (float)str_replace(',','',number_format($invdata->grandamt,2)),
						  "TotAmt"=> (float)str_replace(',','',number_format($invdata->grandamt+$invdata->fsc+$invdata->fhandcharge+$invdata->cod_per_keg_total,2)),
						  "Discount"=> (float)str_replace(',','',number_format($invdata->spl_discount,2)),
						  "PreTaxVal"=> 0,
						  "AssAmt"=> (float)str_replace(',','',number_format($invdata->invamt,2)),
						  "GstRt"=> 18,
						  "IgstAmt"=> (float)str_replace(',','',number_format($invdata->igst,2)),
						  "CgstAmt"=> (float)str_replace(',','',number_format($invdata->cgst,2)),
						  "SgstAmt"=> (float)str_replace(',','',number_format($invdata->sgst,2)),
						  "CesAmt"=> 0,
						  "CesRt"=> 0,
						  "CesNonAdvlAmt"=> 0,
						  "StateCesRt"=> 0,
						  "StateCesAmt"=> 0,
						  "StateCesNonAdvlAmt"=> 0,
						  "OthChrg"=> 0,
						  "TotItemVal"=> (float)str_replace(',','',number_format($invdata->netamt,2))
						]
					  ],
					  "ValDtls"=> [
						"AssVal"=> (float)str_replace(',','',number_format($invdata->invamt,2)),
						"CgstVal"=> (float)str_replace(',','',number_format($invdata->cgst,2)),
						"SgstVal"=> (float)str_replace(',','',number_format($invdata->sgst,2)),
						"IgstVal"=> (float)str_replace(',','',number_format($invdata->igst,2)),
						"CesVal"=> 0.0,
						"StCesVal"=> 0.0,
						"Discount"=> 0.0,
						"OthChrg"=> 0.0,
						"RndOffAmt"=> 0.0,
						"TotInvVal"=> (float)str_replace(',','',number_format($invdata->netamt,2)),
						"TotInvValFc"=> 0.0
					  ]
					  
					  
					  ];
			
			
					  curl_setopt($ch, CURLOPT_URL,"https://demo.saralgsp.com/eicore/v1.03/Invoice");
					  curl_setopt($ch, CURLOPT_POST, 1);
					  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($irndata));           
					  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					  $result     = curl_exec($ch);
					  $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					  
					  $data = json_decode($result, true);
					  //echo $data['signedInvoice'];
					  // echo $data['ackNo'];
					  
					 // echo $data['ackDt'];
					 // echo $data['signedInvoice'];
					 // echo $data['signedQRCode'];
					 // echo $data['status'];
					 //echo $result;
				  //    return array($data['irn'],$data['signedInvoice'],$result,$data['status'],json_encode($irndata));
		   
			  
		  
  
			   if(!empty($data['signedInvoice'])){
				  $db->query("UPDATE invoice SET irn_no='".$data['irn']."',signedirn='".$data['signedInvoice']."',irn_status='Issued' WHERE invno='$invno' AND comp_code='$stncode'");
				  $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$invno','Invoice','Genrate Irn','Irn has been genrated successfully for $invno','$user_name',getdate())");

			   }else{
				  $db->query("UPDATE invoice SET irn_status='invalid' WHERE invno='$invno' AND comp_code='$stncode'");
  
			   }









                 $json1 = $result;
				 $json2 = '{"field_name":{"Invno":"'.$invno.'"}}';

				 $arr = array_merge(json_decode($json1, true), json_decode($json2, true));
                $json = json_encode($arr);
				 $response[] = $result;
				 








				
			}
           echo json_encode(array($response));
		   exit();
		}


		
		function genrateAuth1(){
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
			return array($authenticationToken,$subscriptionId,$authurl);
		  }
	
		 
	
	
		function postauth1($url,$authenticationToken,$subscriptionId){
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
			$data1 = json_decode($response, true);
			$authToken = $data1['data']['authToken'];
			$sek = $data1['data']['sek'];
			return array($authToken,$sek);
			}


			function return_irn1($authenticationToken,$SubscriptionId,$authToken,$sek,$inv_no,$db,$stncode){
				$invdata = $db->query("SELECT * FROM invoice WITH(NOLOCK) WHERE invno='$inv_no' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
				$cnote_count = $db->query("SELECT COUNT(*) AS cnote FROM credit WITH(NOLOCK) WHERE invno='$inv_no' AND comp_code='$stncode'")->fetch(PDO::FETCH_OBJ);
				$getcompanyname = $db->query("SELECT TOP 1 * FROM station WHERE stncode='$invdata->comp_code'")->fetch(PDO::FETCH_OBJ);
				$customer = $db->query("SELECT * FROM customer WHERE cust_code='$invdata->cust_code' AND comp_code='$invdata->comp_code'")->fetch(PDO::FETCH_OBJ);
				$gstcode = substr($customer->cust_gstin,0,2);
				$statename = $db->query("SELECT state_name FROM state WHERE gstcode='$gstcode'")->fetch(PDO::FETCH_OBJ);
		
			  $ch = curl_init();
			$headers  = [
					  "Content-Type: application/json",
					  "AuthenticationToken: $authenticationToken",
					  "SubscriptionId: $SubscriptionId",
					  "Gstin: 29AABCR7796N000",
					  "UserName: eRelyon",
					  "AuthToken: $authToken",
					  "Sek: $sek"
					];
					$irndata = [
					  "Irn"=> "",
					  "Version"=> "1.1",
					  "TranDtls"=> [
						"TaxSch"=> "GST",
						"SupTyp"=> "B2B",
						"RegRev"=> "N",
						"IgstOnIntra"=> "N"
					  ],
					  "DocDtls"=> [
						  "Typ"=> "INV",
						  "No"=> "$inv_no",
						  "Dt"=> "".date("d/m/Y",strtotime($invdata->invdate)).""
					  ],
					  "SellerDtls"=> [
						  "Gstin"=> "29AABCR7796N000",
						  "LglNm"=> "$getcompanyname->stnname",
						  "TrdNm"=> "$getcompanyname->stnname ",
						  "Addr1"=> "$getcompanyname->stn_addr",
						  "Loc"=> "$getcompanyname->stn_city",
						  "Pin"=> 560086,
						  "Stcd"=> "29",
						  "Ph"=> "$getcompanyname->stn_phone",
						  "Em"=> "$getcompanyname->stn_email"
				  ],
					  "BuyerDtls"=> [
						  "Gstin"=> "$customer->cust_gstin",
						  "LglNm"=> "$customer->comp_name",
						  "TrdNm"=> "$customer->br_name",
						  "Pos"=> "".substr($customer->cust_gstin,0,2)."",
						  "Addr1"=> "$customer->cust_addr",
						  "Addr2"=> "$customer->cust_state",
						  "Loc"=> "$statename->state_name",
						  "Pin"=> (int)$customer->pincode,
						  "Stcd"=> "".substr($customer->cust_gstin,0,2)."",
					  ],
					  "ItemList"=> [
						[
						  "SlNo"=> "1",
						  "PrdDesc"=> "COURIER",
						  "IsServc"=> "Y",
						  "HsnCd"=> "996812",
						  "Qty"=> 1,
						  "FreeQty"=> 0,
						  "Unit"=> "PAC",
						  "UnitPrice"=> (float)str_replace(',','',number_format($invdata->grandamt,2)),
						  "TotAmt"=> (float)str_replace(',','',number_format($invdata->grandamt+$invdata->fsc+$invdata->fhandcharge+$invdata->cod_per_keg_total,2)),
						  "Discount"=> (float)str_replace(',','',number_format($invdata->spl_discount,2)),
						  "PreTaxVal"=> 0,
						  "AssAmt"=> (float)str_replace(',','',number_format($invdata->invamt,2)),
						  "GstRt"=> 18,
						  "IgstAmt"=> (float)str_replace(',','',number_format($invdata->igst,2)),
						  "CgstAmt"=> (float)str_replace(',','',number_format($invdata->cgst,2)),
						  "SgstAmt"=> (float)str_replace(',','',number_format($invdata->sgst,2)),
						  "CesAmt"=> 0,
						  "CesRt"=> 0,
						  "CesNonAdvlAmt"=> 0,
						  "StateCesRt"=> 0,
						  "StateCesAmt"=> 0,
						  "StateCesNonAdvlAmt"=> 0,
						  "OthChrg"=> 0,
						  "TotItemVal"=> (float)str_replace(',','',number_format($invdata->netamt,2))
						]
					  ],
					  "ValDtls"=> [
						"AssVal"=> (float)str_replace(',','',number_format($invdata->invamt,2)),
						"CgstVal"=> (float)str_replace(',','',number_format($invdata->cgst,2)),
						"SgstVal"=> (float)str_replace(',','',number_format($invdata->sgst,2)),
						"IgstVal"=> (float)str_replace(',','',number_format($invdata->igst,2)),
						"CesVal"=> 0.0,
						"StCesVal"=> 0.0,
						"Discount"=> 0.0,
						"OthChrg"=> 0.0,
						"RndOffAmt"=> 0.0,
						"TotInvVal"=> (float)str_replace(',','',number_format($invdata->netamt,2)),
						"TotInvValFc"=> 0.0
					  ]
					  
					  
					  ];
			
			
					  curl_setopt($ch, CURLOPT_URL,"https://demo.saralgsp.com/eicore/v1.03/Invoice");
					  curl_setopt($ch, CURLOPT_POST, 1);
					  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($irndata));           
					  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					  $result     = curl_exec($ch);
					  $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					  
					  $data = json_decode($result, true);
					  //echo $data['signedInvoice'];
					  // echo $data['ackNo'];
					  
					 // echo $data['ackDt'];
					 // echo $data['signedInvoice'];
					 // echo $data['signedQRCode'];
					 // echo $data['status'];
					 //echo $result;
					 return array($data['irn'],$data['signedInvoice'],$result,$data['status'],json_encode($irndata));
			}

			function getbyirn1($authenticationToken,$SubscriptionId,$authToken,$sek,$irn){
				$curl = curl_init();
			  $headers  = [
				"Content-Type: application/json",
				"AuthenticationToken: $authenticationToken",
				"SubscriptionId: $SubscriptionId",
				"Gstin: 29AABCR7796N000",
				"UserName: eRelyon",
				"AuthToken: $authToken",
				"Sek: $sek"
			  ];
			  curl_setopt_array($curl, array(
			  CURLOPT_RETURNTRANSFER => 1,
			  CURLOPT_URL => 'https://demo.saralgsp.com/eicore/v1.03/Invoice?irn_no='.$irn.'',
			  CURLOPT_USERAGENT => 'Simple cURL'
			  ));
			  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			  $resp = curl_exec($curl);
			  curl_close($curl);
			  
			  return $resp;
			  }