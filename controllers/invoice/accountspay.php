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

class accountspay extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - accountspay';
		$this->view->render('invoice/accountspay');
	}
}



if(isset($_POST['addcollection']))
	{
		$ccinvdate = $_POST['inv_date'];

		$cc_code = $_POST['cc_code'];
		$cc_name = $_POST['cc_name'];
		$trans_date_from = $_POST['trans_date_from'];
		$trans_date_to = $_POST['trans_date_to'];
		$stncode  = $_POST['stncode'];
		$br_code = $_POST['br_code'];
		$br_name = $_POST['br_name'];
		$add  = $_POST['addcollection'];


      $getstatus = $db->query("SELECT count(*) as counting from cash where tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND  comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' and cc_status IS NULL")->fetch(PDO::FETCH_OBJ);
      if($getstatus->counting=='0'){
		header("Location:accountspayinvoicelist?emptyinvoice");
	  }else{
		$commision = $db->query("SELECT COUNT(tdate) as count,SUM(ramt) as amount, tdate,(select percent_domscomsn from collectioncenter as cc where cc.cc_code=c.cc_code) as spldiscount FROM cash as c where status='Billed' AND  comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' AND cc_status IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' GROUP BY c.tdate,c.cc_code")->fetch(PDO::FETCH_OBJ);
        if(is_null($commision->spldiscount) || $commision->spldiscount==0 || empty($commision->spldiscount)){
			header("Location:accountspayinvoicelist?nocomission");
		}else{
		$total = $db->query("select sum(ramt) as total from cash where tdate BETWEEN '$trans_date_from' AND '$trans_date_to' AND status='Billed' AND  comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' and  cc_status IS NULL")->fetch(PDO::FETCH_OBJ);
		$getdata = $db->query("SELECT COUNT(tdate) as count,SUM(ramt) as amount, tdate,(select percent_domscomsn from collectioncenter as cc where cc.cc_code=c.cc_code) as spldiscount FROM cash as c where status='Billed' AND  comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' AND cc_status IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' GROUP BY c.tdate,c.cc_code")->fetch(PDO::FETCH_OBJ);
	
		$discount = $getdata->spldiscount;
		$rate =   $total->total * $discount;  
		$value =  $rate/100;
	
		$count = $db->query("SELECT tdate, COUNT(tdate) AS Count, SUM(COUNT(tdate)) OVER() AS Sum 
		FROM cash  WHERE status='Billed' AND  comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code'  and cc_status IS NULL AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' 
		GROUP BY tdate");
		$ratecount = $count->fetch(PDO::FETCH_OBJ);
	
    	$genratenumbers = $db->query("SELECT ccinvno FROM ccinvoice ORDER BY ccinvno desc")->fetch(PDO::FETCH_OBJ);
        $lastinvno = $genratenumbers->ccinvno;
		
        if(empty($lastinvno)){
            $number = "CC0000001";
		}else{
		    $idd = str_replace("CC","","$lastinvno");
			$id = str_pad($idd + 1, 7,0, STR_PAD_LEFT);
			$number = 'CC' . $id;
			//$number = $id;
		}



		$db->query("INSERT INTO ccinvoice (ccinvno, ccinvdate, ccinvamt, br_code,br_name, cc_tot_cons, cc_tot_comm, status,cc_code,cc_name,from_date,to_date,station_code) VALUES  ('$number','$ccinvdate','$total->total','$br_code','$br_name','$ratecount->Sum','$value','Invoiced','$cc_code','$cc_name','$trans_date_from','$trans_date_to','$stncode')");   
		$db->query("UPDATE cash WITH (ROWLOCK) SET cc_status='Invoiced',ccinvno='$number' WHERE status='Billed' AND br_code='$br_code' AND cc_code='$cc_code' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to'and cc_status IS NULL");
		$db->query("UPDATE cnotenumber WITH (ROWLOCK) SET cc_status='Invoiced',ccinvno='$number' WHERE cnote_status='Billed' AND cnote_station='$stncode' AND br_code='$br_code' AND cust_code='$cc_code' AND bdate BETWEEN '$trans_date_from' AND '$trans_date_to' and cc_status IS NULL");
	//  update cnotenumber set cust_code='AMBCC10002' where cnote_station='AMB' AND br_code='AMB'  AND bdate BETWEEN '2022-04-07' AND '2022-09-30'
	
		header("Location: accountspayinvoicelist?success=10");
	  }

	}

	}


	if(isset($_POST['delcc_ecnote'])){
		$delcc_ecompany = $_POST['delcc_ecompany'];
		$delcc_ecnote = $_POST['delcc_ecnote'];
		$delcash_eusercc = $_POST['delcash_eusercc'];

		//$db->query("DELETE FROM ccinvoice WHERE ccinvno='$delcc_ecnote'");
		$db->query("UPDATE ccinvoice SET status='Deleted' WHERE ccinvno='$delcc_ecnote'");

		$db->query("UPDATE cash SET cc_status=NULL WHERE ccinvno='$delcc_ecnote' and cc_status='Invoiced'");

		$db->query("UPDATE cnotenumber SET cc_status=NULL WHERE ccinvno='$delcc_ecnote' AND cc_status='Invoiced'");

		//store audit log
		$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('ccinvoice','accountspay','Deleted','ccinvoice $delcc_ecnote has been Deleted in Station $delcc_ecompany','$delcash_eusercc',getdate())");
	}