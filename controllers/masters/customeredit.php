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

      class customeredit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - customeredit';
              $this->view->render('masters/customeredit');
          }
      }


      $errors = array();

          if(isset($_POST['updatecustomer'])){
              try{
              altercustomer($db);
              }
              catch(PDOException $e){
                  echo json_encode($e);
              }
          }
            function altercustomer($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_modified = date('Y-m-d H:i:s');
                $doe = date('Y-m-d H:i:s');

                $cusid                   = $_POST['cusid'];
                $stncode 		         = $_POST['comp_code'];
                $stnname		         = $_POST['comp_name'];
                $br_code			     = $_POST['br_code'];
                $br_name			     = $_POST['br_name'];
                $cust_code			     = $_POST['cust_code'];
                $cust_name			     = $_POST['cust_name'];
                $exe_code			     = $_POST['exe_code'];
                $exe_name			     = $_POST['exe_name'];   
                $role			         = $_POST['role'];            
                $cust_addr 			     = $_POST['cust_addr'];                
                $cust_city			     = $_POST['cust_city'];
                $cust_state			     = $_POST['cust_state'];
                $cust_country		     = $_POST['cust_country'];
                $cust_phone			     = $_POST['cust_phone'];
                $cust_mobile		     = $_POST['cust_mobile'];
                $cust_fax			     = $_POST['cust_fax'];
                $cust_email			     = $_POST['cust_email'];
                $cctr				     = $_POST['cctr'];
                $ccdly				     = $_POST['ccdly'];
                $credit_control          = $_POST['credit_control'];
                $cust_status             = $credit_control=='Y'?"Unlocked":"Locked";
                $c_person			     = $_POST['c_person'];
                $zncode				     = $_POST['zncode'];
                $cust_type 			     = $_POST['cust_type'];
                $transit_hub		     = $_POST['transit_hub'];
                $cust_remarks		     = $_POST['cust_remarks'];
                $push_to_website	     = $_POST['push_to_web'];
                $cust_active		     = $_POST['cust_active'];
                $spl_discountD		     = $_POST['spl_discountD'];
                $spl_discountI		     = $_POST['spl_discountI'];
                $other_charges		     = $_POST['other_charges'];
                $cnote_adv			     = $_POST['cnote_adv'];
                $cnote_charges		     = $_POST['cnote_charges'];
                $pro 				     = $_POST['pro'];
                $prc				     = $_POST['prc'];
                $prd				     = $_POST['prd'];
				$pro_per_kg			     = $_POST['pro_per_kg'];
				$prc_per_kg		         = $_POST['prc_per_kg'];
				$prd_per_kg			     = $_POST['prd_per_kg'];
                $cod_per_kg			     = $_POST['cod_per_kg'];
				$cod				     = $_POST['cod'];
                $cust_fsc			     = $_POST['cust_fsc'];
                $fsc_type			     = $_POST['fsc_type'];
                $grace_period            = $_POST['grace_period'];
                $credit_invoice		     = $_POST['credit_invoice'];
                $cust_msr	             = $_POST['cust_msr'];
				$cust_bus_vol	         = $_POST['cust_bus_vol'];
                $deposit		         = $_POST['deposit'];
                $date_contract		     = $_POST['date_contract'];
                $contract_period	     = $_POST['contract_period'];
                $cust_gstin			     = $_POST['cust_gstin'];
                $cust_pan			     = $_POST['cust_pan'];
                $cust_tin 			     = $_POST['cust_tin'];
                $cust_uin			     = $_POST['cust_uin'];
                $quote_no			     = $_POST['quote_no'];
                $credit_limit		     = $_POST['credit_limit'];
                $track_id			     = $_POST['track_id'];
                $rate		     		= explode("-",$_POST['rate'])[0];
                $username		         = $_POST['user_name'];        
                $insurance_charges			= $_POST['insurance_charges'];
				$packing_charges			= $_POST['packing_charges'];
                $credit_accounts_copy   = $_POST['credit_accounts_copy'];
                $credit_pod_copy        = $_POST['credit_pod_copy'];
                $credit_consignor_copy  = $_POST['credit_consignor_copy'];
                $f_handling_charge      = $_POST['f_handling_charge'];
                $tr_lock                = $_POST['tr_lock'];
                $tr_lock_date           = $_POST['transaction_date'];
                $cust_pincode           = $_POST['cust_pincode'];
                $sql = "UPDATE customer set
                                          comp_code       = '".$stncode."',
                                          comp_name       = '".$stnname."',
                                          br_code         = '".$br_code."',
                                          br_name         = '".$br_name."',
                                          cust_code       = '".$cust_code."',
                                          cust_name       = '".$cust_name."',
                                          exe_code        = '".$exe_code."',
                                          exe_name        = '".$exe_name."',
                                          role            = '".$role."',                                          
                                          cust_addr       = '".$cust_addr."',
                                          cust_city       = '".$cust_city."',
                                          cust_state      = '".$cust_state."',
                                          cust_country    = '".$cust_country."',
                                          cust_phone      = '".$cust_phone."',
                                          cust_mobile     = '".$cust_mobile."',
                                          cust_fax        = '".$cust_fax."',
                                          cust_email      = '".$cust_email."',
                                          cctr            = '".$cctr."',
                                          ccdly           = '".$ccdly."',
                                          credit_control  = '".$credit_control."',
                                          cust_status     = '".$cust_status."',
                                          c_person        = '".$c_person."',
                                          zncode          = '".$zncode."',
                                          cust_type       = '".$cust_type."',                                         
                                          transit_hub     = '".$transit_hub."',
                                          cust_remarks    = '".$cust_remarks."',
                                          push_to_website    = '".$push_to_website."',
                                          cust_active     = '".$cust_active."',
                                          spl_discountD   = '".$spl_discountD."',
                                          spl_discountI   = '".$spl_discountI."',
                                          cnote_adv       = '".$cnote_adv."',
                                          cnote_charges   = '".$cnote_charges."',
                                          pro             = '".$pro."',
                                          prc             = '".$prc."',
                                          prd             = '".$prd."',
                                          cod             = '".$cod."',
                                          pro_per_kg      = '".$pro_per_kg."',
                                          prc_per_kg      = '".$prc_per_kg."',
                                          prd_per_kg      = '".$prd_per_kg."',
                                          cod_per_kg      = '".$cod_per_kg."',
                                          cust_fsc        = '".$cust_fsc."',
                                          fsc_type        = '".$fsc_type."',
                                          grace_period    = '".$grace_period."',
                                          credit_invoice  = '".$credit_invoice."',
                                          cust_msr        = '".$cust_msr."',
                                          cust_bus_vol    = '".$cust_bus_vol."',
                                          deposit         = '".$deposit."',
                                          date_contract   = '".$date_contract."',
                                          contract_period = '".$contract_period."',
                                          cust_gstin      = '".$cust_gstin."',
                                          cust_pan        = '".$cust_pan."',
                                          cust_tin        = '".$cust_tin."',
                                          cust_uin        = '".$cust_uin."',
                                          quote_no        = '".$quote_no."',
                                          credit_limit    = '".$credit_limit."',
                                          track_id        = '".$track_id."',
                                          rate            = '".$rate."',
                                          user_name        = '".$username."',
                                          other_charges     = '".$other_charges."',
                                          credit_accounts_copy  = '".$credit_accounts_copy."', 
                                          credit_pod_copy       = '".$credit_pod_copy."', 
                                          credit_consignor_copy = '".$credit_consignor_copy."', 
                                          insurance_charges     = '".$insurance_charges."',
                                          packing_charges     = '".$packing_charges."',
                                          doe             = '".$doe."', 
                                          fhandling_charges = '".$f_handling_charge."',
                                          tr_lock           = '".$tr_lock."',
                                          tr_lock_date      = '".$tr_lock_date."',
                                          pincode           = '".$cust_pincode."',
                                          date_modified   = '".$date_modified."'

                                          WHERE cusid='".$cusid."' AND cust_code='".$cust_code."' " ;
              
              $result  = $db->query($sql);

              if(file_exists($_FILES["file"]["tmp_name"])){
				$tmpname = $_FILES["file"]["tmp_name"];
				$name = $_FILES["file"]["name"];

				$dataString = file_get_contents($tmpname);
				$encodedfile = base64_encode($dataString);
				
				$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Rate Master','Customer Rate','Updated','RateContract file has been updated','$username',getdate())");   

				$db->query("INSERT INTO contract_files (
                comp_code
                ,br_code
                ,cust_code
                ,contract_files
                ,file_name
                ,date_modified
                ,date_added) VALUES
                 (
                 '$stncode'
                 ,'$br_code'
                 ,'$cust_code'
                 ,'$encodedfile'
                 ,'$name'
                 ,getdate()
                 ,getdate()
                 )");
				}

              $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Masters','Customer','Modified','Customer $cust_code has been Modified in Station $stncode','$username',getdate())");

              header("location: customerlist?success=2");
              
              }
            
              if(isset($_POST['action']) && $_POST['action']=='update_transaction_lock'){
                date_default_timezone_set('Asia/Kolkata');
                $tr_lock_status = $_POST['tr_lock_status'];
                $stncode = $_POST['stncode'];
                $cust_code = $_POST['cust_code'];
                $tr_date  = $_POST['tr_date'];
                $user_name = $_POST['user_name'];
                
                $date_added	= date('Y-m-d H:i:s');
                
                $lock_status_message = (($tr_lock_status==1)? ''.$cust_code.' Customer Transaction Lock is Enabled from '.$tr_date.'' : ''.$cust_code.' Customer Transcation Lock is Disabled on '.$date_added.'');
                $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Customer','Customer Update','Modified','$lock_status_message','$user_name',getdate())");
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
    if(isset($_POST['tr_lock_status'])){
			$tr_lock_status = $_POST['tr_lock_status'];
			$stncode = $_POST['stncode'];
			$cust_code = $_POST['cust_code'];

			$getcust_lockstatus = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE comp_code='$stncode' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);
			$string = $getcust_lockstatus->tr_lock;
			echo trim($string);
			exit();
		}
?>