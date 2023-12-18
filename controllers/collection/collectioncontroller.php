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

	 class collectioncontroller extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - fetch';
             // $this->view->render('cnote/fetch');
          }
      }
       error_reporting(0);
      if(isset($_POST['stationscode']))
      {
          $stationscode = $_POST['stationscode'];
          $branchcodes = $_POST['branchcodes'];
          $fetchbr_name = $db->query("SELECT br_name FROM branch WHERE stncode='$stationscode' AND br_code='$branchcodes'");
          $branchcoderow2 = $fetchbr_name->fetch(PDO::FETCH_ASSOC); 
          echo $branchcoderow2['br_name'];
          
      }
       //Fetch Customer Name
       if(isset($_POST['brcodes']))
       {
         $brcodes = $_POST['brcodes'];
         $ccode = $_POST['ccode'];
         $get_customer = $db->query("SELECT cust_name FROM customer WHERE br_code='$brcodes' AND cust_code='$ccode'");
         $customer_row = $get_customer->fetch(PDO::FETCH_ASSOC);
         echo $customer_row['cust_name'];
       }
        // auto populate customer based on branch
        if(isset($_POST['branch8']))
        {
          $branch8  = $_POST['branch8'];
          $comp8    = $_POST['comp8'];
          $getautoppl8 = $db->query("SELECT cust_code FROM customer WHERE comp_code='$comp8' AND br_code='$branch8'");
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
          $getautopp11 = $db->query("SELECT cust_name FROM customer WHERE comp_code='$comp11' AND br_code='$branch11'");
          if($getautopp11->rowCount()==0){

          }else{
          while($getautopp11row = $getautopp11->fetch(PDO::FETCH_ASSOC))
          {
            $arrayvalues11[] = $getautopp11row['cust_name'];
          }
          echo json_encode($arrayvalues11);
        }
        }
        //Verify Customer is Valid
      if(isset($_POST['cust_verify_br']))
      {
        $cust_verify_br = $_POST['cust_verify_br'];
        $cust_verify_cust = $_POST['cust_verify_cust'];
        $custcode_verify = $db->query("SELECT cust_code,cust_status FROM customer WHERE br_code='$cust_verify_br' AND cust_code='$cust_verify_cust'");
        $getinvoicedetail = $db->query("SELECT TOP 1 invno,invdate,netamt FROM invoice WHERE cust_code='$cust_verify_cust' ORDER BY invoice_id DESC");
        $rowinvoicedetail = $getinvoicedetail->fetch(PDO::FETCH_ASSOC);
        $cust_lockstatus = $custcode_verify->fetch(PDO::FETCH_OBJ);
        $gettotalamt = $db->query("SELECT TOP 1 balamt FROM collection WHERE cust_code='$cust_verify_cust' ORDER BY col_id DESC");
        $fetchcrndata = $db->query("SELECT TOP(1) * FROM credit_note WHERE cust_code='$cust_verify_cust' ORDER BY credit_note_id DESC");
        if($fetchcrndata->rowCount()==0){
          $c_crnno = '';
          $c_crnbal = '';
        }else{
          $rowfetchcrndata = $fetchcrndata->fetch(PDO::FETCH_OBJ);
          $c_crnno = $rowfetchcrndata->crn_no;
          $c_crnbal = bcdiv($rowfetchcrndata->net_amt,1,2);
        }
        if($gettotalamt->rowCount()==0)
        {
            $totalamt4 = bcdiv($rowinvoicedetail['netamt'],1,2);
        }
        else{
            $rowtotalamt = $gettotalamt->fetch(PDO::FETCH_ASSOC);
            $totalamt4 = bcdiv($rowtotalamt['balamt'],1,2);
        }
        echo $custcode_verify->rowCount().",".$rowinvoicedetail['invno'].",".$rowinvoicedetail['invdate'].",".number_format($rowinvoicedetail['netamt'],2).",".number_format($totalamt4,2).",".$cust_lockstatus->cust_status.",".$c_crnno.",".$c_crnbal;
      }
      //Verify Branch is Valid
      if(isset($_POST['branch_verify']))
      {
        $stationcode = $_POST['stationcode'];
        $branch_verify = $_POST['branch_verify'];
        $brcode_verify = $db->query("SELECT br_code FROM branch WHERE br_code='$branch_verify' AND stncode='$stationcode'");
        echo $brcode_verify->rowCount();
      }
      //Fetch invoice detail on enter invno
      if(isset($_POST['invno'])){
          $collinvno = $_POST['invno'];
          $inv_cstn = $_POST['inv_cstn'];
          $invdata   = $db->query("SELECT * FROM invoice WHERE invno='$collinvno' AND comp_code='$inv_cstn'");
          if($invdata->rowCount()==0){
            }  
          else{
            $rowinvdata = $invdata->fetch(PDO::FETCH_OBJ);
            $getbrcoll = $db->query("SELECT br_name FROM branch WHERE br_code='$rowinvdata->br_code' AND stncode='$inv_cstn'");
            $rowbrcoll  = $getbrcoll->fetch(PDO::FETCH_OBJ);//fetch brname
            $getcustcoll = $db->query("SELECT * FROM customer WHERE cust_code='$rowinvdata->cust_code' AND comp_code='$inv_cstn'");
            $rowcustcoll  = $getcustcoll->fetch(PDO::FETCH_OBJ);//fetch customer name
            $balanccoll = $db->query("SELECT TOP(1) * FROM collection WHERE invno='$collinvno' AND comp_code='$inv_cstn' AND status NOT LIKE '%Void' ORDER BY coldate DESC,col_id DESC");
            $getrunbal = $db->query("SELECT TOP(1) * FROM collection_master WHERE cust_code='$rowinvdata->cust_code' AND comp_code='$inv_cstn'  AND status NOT LIKE '%Void'  ORDER BY col_mas_id DESC");
            $getinvdatecoll = $db->query("SELECT invdate FROM invoice WHERE invno='$collinvno' AND comp_code='$inv_cstn'")->fetch(PDO::FETCH_OBJ);
            $rowrunbal = $getrunbal->fetch(PDO::FETCH_OBJ);
            $getcrndata = $db->query("SELECT TOP(1) * FROM credit_note WHERE cust_code='$rowinvdata->cust_code' ORDER BY credit_note_id DESC");
            $getecptpayref = $db->query("SELECT TOP(1) rcptno,chqddno,chqbank,depbank FROM collection_master WHERE invno='$collinvno' AND comp_code='$inv_cstn' ORDER BY col_mas_id DESC")->fetch(PDO::FETCH_OBJ);

            $getcust_lockstatus = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE comp_code='$inv_cstn' AND cust_code='$rowinvdata->cust_code'")->fetch(PDO::FETCH_OBJ);
		      	$string = $getcust_lockstatus->tr_lock_date;
            $data   = preg_split('/To/', $string);
            $getdate = date('Y-m-d');
            $currentdate = date('Y-m-d', strtotime($getdate));
			      if(($currentdate >= trim($data[0])) && ($currentdate <= trim($data[1]))){
               $value = 1;
			      }else{
			    	$value = 0;
			     } 
            if($getcrndata->rowCount()==0){
              $crnno = '';
              $crn_bal = '';
            }
            else{
            $rowcrndata = $getcrndata->fetch(PDO::FETCH_OBJ);
            $crnno = $rowcrndata->crn_no;
            $crn_bal = bcdiv($rowcrndata->net_amt,1,2);
            }
            if($rowrunbal->running_balance=='0'){
              $run_bal = '0';
              $total_bal = '0';
            }
            else{
              $run_bal = $rowrunbal->running_balance;
              $total_bal = bcdiv($rowrunbal->totamt,1,2);
            }
            //if($balanccoll->rowCount()==0){
              $collbalamt = ($rowinvdata->netamt-$rowinvdata->rcvdamt);
            // }else{
            //   $rowbalanccoll = $balanccoll->fetch(PDO::FETCH_OBJ);
            //   $collbalamt = bcdiv($rowbalanccoll->balamt,1,2);
            // }

            echo $rowinvdata->br_code."|".$rowbrcoll->br_name."|".$rowinvdata->cust_code."|".$rowcustcoll->cust_name."|".number_format($rowinvdata->netamt,2)."|".number_format($collbalamt,2)."|".number_format($total_bal,2)."|".number_format($run_bal,2)."|".$collinvno."|".$getinvdatecoll->invdate."|".$crnno."|".$crn_bal."|".$rowcustcoll->cust_status."|".$getecptpayref->rcptno."|".$getecptpayref->chqddno."|".$invdata->rowCount()."|".$rowinvdata->status."|".$getecptpayref->chqbank."|".$getecptpayref->depbank."|".$rowbalanccoll->sheetno."|".$value;
          }
      }



      //Fetch Table data for collection
      if(isset($_POST['invoicetb']))
      {
        $invoicetb =  $_POST['invoicetb'];
        $coll_rev_stn =  $_POST['coll_rev_stn'];
        $getinvcustomer = $db->query("SELECT cust_code FROM invoice WHERE invno='$invoicetb' AND comp_code='$coll_rev_stn'");
        $rowgetinvcustomer = $getinvcustomer->fetch(PDO::FETCH_OBJ);
        $getcollectionlist = $db->query("SELECT * FROM collection WHERE cust_code='$rowgetinvcustomer->cust_code' AND comp_code='$coll_rev_stn' AND invno='$invoicetb'  ORDER BY coldate DESC");
        while($collinvrow = $getcollectionlist->fetch(PDO::FETCH_OBJ)){
           echo "<tr>";
           echo "<td class='center first-child'>
                   <label>
                      <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                       <span class='lbl'></span>
                    </label>
                 </td>";
                 echo "<td>".$collinvrow->colno."</td>";
                 echo "<td>".$collinvrow->coldate."</td>";
                 echo "<td>".$collinvrow->invno."</td>";
                 echo "<td>".$collinvrow->invdate."</td>";     
                 echo "<td>".$collinvrow->cust_code."</td>";
                 echo "<td>".round($collinvrow->invamt)."</td>";
                 echo "<td>".round($collinvrow->colamt)."</td>";
                 echo "<td>".round($collinvrow->tds)."</td>";
                 echo "<td>".round($collinvrow->reduction)."</td>";
                 echo "<td>".round($collinvrow->netcollamt)."</td>";
                 echo "<td>".round($collinvrow->balamt)."</td>";
                 echo "<td>".$collinvrow->status."</td>";
                 echo "<td>".$collinvrow->remarks."</td>";
                 echo "</tr>";
        }
      }
      //Fetch Table data for collection
      if(isset($_POST['colinvrev']))
      {
        $colinvrev =  $_POST['colinvrev'];  
        $colinvclist =  $_POST['colinvclist'];
        $getcolinvrevcust = $db->query("SELECT * FROM invoice WHERE invno='$colinvrev' AND comp_code='$colinvclist'");
        $rowcolinvrev2 = $getcolinvrevcust->fetch(PDO::FETCH_OBJ);
        $getcolinvrev2 = $db->query("SELECT * FROM invoice WHERE cust_code='$rowcolinvrev2->cust_code' AND status='Pending' AND comp_code='$colinvclist' ORDER BY invdate DESC");
        while($rowcolinvrev = $getcolinvrev2->fetch(PDO::FETCH_OBJ)){
           echo "<tr>";
           echo "<td class='center first-child'>
                   <label>
                      <input type='radio' name='chkIds[]' value='$rowcolinvrev->invno' class='ace invoiceradio'/>
                       <span class='lbl'></span>
                    </label>
                 </td>";
          
                 echo "<td>".$rowcolinvrev->invno."</td>";
                 echo "<td>".$rowcolinvrev->invdate."</td>";     
                 echo "<td>".$rowcolinvrev->br_code."</td>";
                 echo "<td>".$rowcolinvrev->cust_code."</td>";
                 echo "<td>".round($rowcolinvrev->grandamt)."</td>";
                 echo "<td>".round($rowcolinvrev->fsc)."</td>";
                 echo "<td>".round($rowcolinvrev->invamt)."</td>";
                 echo "<td>".round($rowcolinvrev->STax)."</td>";
                 echo "<td>".round($rowcolinvrev->netamt)."</td>";
                 echo "<td>".$rowcolinvrev->status."</td>";
                 echo "<td>".$rowcolinvrev->remarks."</td>";
                 echo "</tr>";
        }
      }
       //fetch branch code
       if(isset($_POST['s_bname'])){
        $s_bname = $_POST['s_bname'];
        $s_comp = $_POST['s_comp'];
        echo branchcode($s_bname,$s_comp,$db);
        }
         //fetch BRANCH CODE FUNCTION
             function branchcode($s_bname,$s_comp,$db){
              $brname = $db->query("SELECT br_code FROM branch WHERE br_name='$s_bname' AND stncode='$s_comp'")->fetch(PDO::FETCH_OBJ);
              return $brname->br_code;
        }
        //Fetch Customer code
		 if(isset($_POST['cccust_name'])){
			$cccust_name = $_POST['cccust_name'];
			$cc_namestn = $_POST['cc_namestn'];
			$cc_namebr = $_POST['cc_namebr'];
			$rowcustcode = $db->query("SELECT cust_code FROM customer WHERE cust_name='$cccust_name' AND comp_code='$cc_namestn' AND br_code='$cc_namebr'")->fetch(PDO::FETCH_OBJ);
			echo $rowcustcode->cust_code;
		   }
       //validate invoice
       if(isset($_POST['valid_invno'])){
         $valid_invno = $_POST['valid_invno']; 
         $valid_inv_cstn = $_POST['valid_inv_cstn'];
         $validateinv = $db->query("SELECT * FROM invoice WHERE invno='$valid_invno' AND comp_code='$valid_inv_cstn'");
         echo $validateinv->rowCount();
       }
       //delete collection
       if(isset($_POST['del_collno'])){
          deletecollection($db,$_POST['del_collno'],$_POST['del_collstn'],$_POST['del_coll_user']);
       }
       function deletecollection($db,$del_collno,$del_collstn,$del_coll_user){
        $updtdcol = $db->query("SELECT colno,invno,col_mas_rcptno,netcollamt FROM collection WHERE col_id='$del_collno' AND comp_code='$del_collstn'")->fetch(PDO::FETCH_OBJ);//set void for collection table
        $db->query("UPDATE collection SET status=status+'-Void' WHERE col_id='$del_collno' AND comp_code='$del_collstn'");//set void for collection table
        $db->query("UPDATE collection_master SET status=status+'-Void' WHERE col_mas_rcptno='$updtdcol->col_mas_rcptno' AND invno='$updtdcol->invno' AND comp_code='$del_collstn'");//set void for collection master table
        $db->query("UPDATE invoice SET rcvdamt=rcvdamt-$updtdcol->netcollamt,status='Pending' WHERE invno='$updtdcol->invno' AND comp_code='$del_collstn'");//update invoice
     
        //delete creditNote
        $db->query("UPDATE credit_note SET flag='0' WHERE col_no='$updtdcol->colno' AND comp_code='$del_collstn'");
        //delete creditNote
       //prepare for log
       $yourbrowser   = getBrowser()['name'];
       $user_surename = get_current_user();
       $platform      = getBrowser()['platform'];   
       $remote_addr   = getBrowser()['remote_addr']; 
       $date_added        = date('Y-m-d H:i:s');
       $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$del_coll_user','Collection Master No $del_collno has been Deleted','$yourbrowser')"); 
       $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','Collection','Deleted','Collection Master No $del_collno has been Deleted','$del_coll_user',getdate())");   
       }

        //delete credit note
        if(isset($_POST['del_cnno'])){
          deletecreditnote($db,$_POST['del_cnno'],$_POST['del_cnstn'],$_POST['del_cn_user']);
        }
        function deletecreditnote($db,$del_cnno,$del_cnstn,$del_cn_user){
          //prepare for log
          $yourbrowser   = getBrowser()['name'];
          $user_surename = get_current_user();
          $platform      = getBrowser()['platform'];   
          $remote_addr   = getBrowser()['remote_addr']; 
          $date_added        = date('Y-m-d H:i:s');
          $db->query("UPDATE credit_note SET flag='0' WHERE credit_note_id='$del_cnno' AND comp_code='$del_cnstn'");
          $collno = $db->query("SELECT col_no FROM credit_note WHERE credit_note_id='$del_cnno' AND comp_code='$del_cnstn'")->fetch(PDO::FETCH_OBJ);
          //delete collection
          $updtdcol = $db->query("SELECT invno,col_mas_rcptno,netcollamt FROM collection WHERE colno='$collno->col_no' AND comp_code='$del_cnstn'")->fetch(PDO::FETCH_OBJ);//set void for collection table
          $db->query("UPDATE collection SET status=status+'-Void' WHERE colno='$collno->col_no' AND comp_code='$del_cnstn'");//set void for collection table
          $db->query("UPDATE collection_master SET status=status+'-Void' WHERE col_mas_rcptno='$updtdcol->col_mas_rcptno' AND invno='$updtdcol->invno' AND comp_code='$del_cnstn'");//set void for collection master table
          $db->query("UPDATE invoice SET rcvdamt=rcvdamt-$updtdcol->netcollamt,status='Pending' WHERE invno='$updtdcol->invno' AND comp_code='$del_cnstn'");//update invoice
       
          //delete collection
          $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','CreditNote','Deleted','CreditNote id $del_cnno has been Deleted','$del_cn_user',getdate())");   
          $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$del_cn_user','CreditNote Id $del_cnno has been Deleted','$yourbrowser')"); 
        }

           //get amounts for denomination
           if(isset($_POST['den_stn'])){
            echo json_encode(gettotalamounts($db,$_POST['den_stn'],$_POST['den_br'],$_POST['den_bdate']));
          }
      function gettotalamounts($db,$stationcode,$br_code,$den_bdate){
      //Total cheque and entry count of cheque
      $data = $db->query("SELECT ISNULL(sum(ramt),0) as totalchq,count(amt) as datacount FROM cash WHERE br_code='$br_code' AND payment_mode='Cheque' AND comp_code='$stationcode' AND cast(tdate as date)=cast('$den_bdate' as date) AND status ='Billed'")->fetch(PDO::FETCH_OBJ);
      $gettotalcheque = array($data->datacount,floatval($data->totalchq));
      //Total cheque and entry count of cheque
  
      //Total upi and entry count of upi
      $data = $db->query("SELECT ISNULL(sum(ramt),0) as totalchq,count(amt) as datacount FROM cash WHERE br_code='$br_code' AND payment_mode='UPI' AND comp_code='$stationcode' AND cast(tdate as date)=cast('$den_bdate' as date) AND status ='Billed'")->fetch(PDO::FETCH_OBJ);
      $gettotalupi = array($data->datacount,floatval($data->totalchq));
      //Total upi and entry count of upi
  
      //Total neft and entry count of neft
      $data = $db->query("SELECT ISNULL(sum(ramt),0) as totalchq,count(amt) as datacount FROM cash WHERE br_code='$br_code' AND payment_mode='NEFT' AND comp_code='$stationcode' AND cast(tdate as date)=cast('$den_bdate' as date) AND status ='Billed'")->fetch(PDO::FETCH_OBJ);
      $gettotalneft = array($data->datacount,floatval($data->totalchq));
      //Total neft and entry count of neft
  
       //Total cash and entry count of cash
      $data = $db->query("SELECT ISNULL(sum(ramt),0) as totalcash FROM cash WHERE br_code='$br_code' AND comp_code='$stationcode' AND cast(tdate as date)=cast('$den_bdate' as date) AND status ='Billed'")->fetch(PDO::FETCH_OBJ);
      $getsumoftotalentryamt = array(floatval($data->totalcash));
      //Total cash and entry count of cash
  
      return array('cheque'=>$gettotalcheque,'upi'=>$gettotalupi,'neft'=>$gettotalneft,'totalcash'=>$getsumoftotalentryamt);
      }

             //delete denomination
             if(isset($_POST['del_den_id'])){
              echo deletedenomination($db,$_POST['del_den_id'],$_POST['del_den_stn'],$_POST['del_den_user']);
           }
           function deletedenomination($db,$del_den_id,$del_den_stn,$del_den_user){
    
            $delete = $db->query("DELETE FROM denomination WHERE den_id='$del_den_id' AND comp_code='$del_den_stn'");//set 0 flag for denomination table
            //prepare for log
            $yourbrowser   = getBrowser()['name'];
            $user_surename = get_current_user();
            $platform      = getBrowser()['platform'];   
            $remote_addr   = getBrowser()['remote_addr']; 
            $date_added        = date('Y-m-d H:i:s');
            $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$del_den_user','Denomination Master No $del_den_id has been Deleted','$yourbrowser')"); 
            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','Denomination','Deleted','Denomination No $del_den_id has been Deleted','$del_den_user',getdate())");   
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