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

class customerrate extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - customerrate';
		$this->view->render('rate/customerrate');
	}
}


			//Save Customerrate
			if(isset($_POST['addcustomerrate']))
			{
                    sleep(1);
				savecustomerrate($db);
			}
			//Save and another Customerrate
			if(isset($_POST['addananothercustomerrate'])) 
			{
                    sleep(1);
				saveanothercustomerrate($db);
			}
			//Update Contract
			if(isset($_POST['updatecustomerrate'])) 
			{
				try{
				updatecustomerrate($db);
				}
				catch(PDOException $e){
					print_r($e);
					//header("Location: customerratedetail?success=1 && br_code=".$_POST['br_code']." && br_name=".$_POST['br_name']." && cust_code=".$_POST['cust_code']." && cust_name=".$_POST['cust_name']." && contract_period=".$_POST['contract_period']." && date_contract=".$_POST['date_contract']."");
				}
			}




			function savecustomerrate($db){
			// error_reporting(0);
			date_default_timezone_set('Asia/Kolkata');
			$date_added       = date('Y-m-d H:i:s');
			$cust_code                =  $_POST['cust_code'];
			$stncode                  =  $_POST['stncode'];
			$rate_type                =  $_POST['rate_type'];
			$origin                   =  $_POST['origin'];
			$destn                    =  explode('-',$_POST['zonedest'])[0];
			$mode                     =  $_POST['mode'];
			$calcmethod               =  $_POST['calcmethod'];
			$splcharges               =  $_POST['splcharges'];
			$username                 =  $_POST['user_name'];

			if(file_exists($_FILES["file"]["tmp_name"])){
				$tmpname = $_FILES["file"]["tmp_name"];
				$name = $_FILES["file"]["name"];

				$dataString = file_get_contents($tmpname);
				$encodedfile = base64_encode($dataString);
				
				$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Rate Master','Customer Rate','Updated','RateContract file has been updated','$username',getdate())");   

				$db->query("UPDATE customer SET rate_file_name='$name',rate_file='$encodedfile' WHERE comp_code='$stncode' AND cust_code='$cust_code'");
				}
			// Verify Zone code is valid
			$val_zonecust = $db->query("SELECT zncode FROM customer WHERE cust_code='$cust_code' AND comp_code='$stncode'");
			$row_valzone = $val_zonecust->fetch(PDO::FETCH_ASSOC);
			 $destn;
			if($row_valzone['zncode']=='A'){ 
			  $zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$stncode' AND zone_type='credit' AND zone_code='$destn' AND zone_code LIKE '[0-9]%'")->fetch(PDO::FETCH_OBJ);
			}
			else if($row_valzone['zncode']=='B'){
			  $zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$stncode' AND zone_type='credit' AND zone_code='$destn' AND zone_code LIKE 'B%'")->fetch(PDO::FETCH_OBJ);
			}
			else if($row_valzone['zncode']=='C'){
			  $zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$stncode' AND zone_type='credit' AND zone_code='$destn' AND zone_code LIKE 'C%'")->fetch(PDO::FETCH_OBJ);
			}
			else if($row_valzone['zncode']=='cash'){
			  $zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$stncode' AND zone_type='cash' AND zone_code='$destn'")->fetch(PDO::FETCH_OBJ);
			}

			$dest_verify = $db->query("SELECT stncode FROM stncode WHERE stncode='$destn'");
			if($zone_verify->counts2==0 && $dest_verify->rowCount()==0)
			{
				header("Location: customerrate?zonedest");
			}
			else{
			//Generating Con_code
			$ccode="SELECT TOP 1 con_code FROM rate_custcontract ORDER BY cast(dbo.GetNumericValue(con_code) AS decimal) DESC";
			$code=$db->query($ccode);
			$coderow=$code->fetch(PDO::FETCH_ASSOC);
			$row=$coderow['con_code'];
			if($code->rowCount()== 0){
				$con_code = 'CO'.str_pad('1', 7, "0", STR_PAD_LEFT);
			}
			else{
				$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$row);
				// $arr[0];PO
				// $arr[1]+1;numbers
				$con_code = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
			}
			//Generating Rate Id
			$fetchrateid = $db->query("SELECT TOP 1 cust_rate_id FROM rate_custcontract ORDER BY cust_rate_id DESC");
			if($fetchrateid->rowCount()==0){
				$cust_rate_id = 'RATE'.str_pad(1, 3, "0", STR_PAD_LEFT);
			}
			else{
				$fetchrateidbycustomer = $db->query("SELECT cust_rate_id FROM rate_custcontract WHERE cust_code='$cust_code'");
				if($fetchrateidbycustomer->rowCount()==0){
					$rownewrateid = $fetchrateid->fetch(PDO::FETCH_ASSOC);
					$lastrateid = $rownewrateid['cust_rate_id'];
					$arrrateid = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastrateid);
					$cust_rate_id = 'RATE'.str_pad($arrrateid[1]+1, 3, "0", STR_PAD_LEFT);
				}
				else{
					$rowrateid = $fetchrateidbycustomer->fetch(PDO::FETCH_ASSOC);
					$cust_rate_id = $rowrateid['cust_rate_id'];
				}
			}
			$check_concode = $db->query("SELECT COUNT(con_code) AS concount FROM rate_custcontract WITH (NOLOCK) WHERE con_code='$con_code'")->fetch(PDO::FETCH_OBJ);
		if($check_concode->concount==0){
			$mode_validation = $db->query("SELECT * FROM rate_custcontract WHERE stncode='$stncode' AND cust_code='$cust_code' AND destn='$destn' AND rate_type='$rate_type' AND mode='$mode'");
				if($mode_validation->rowCount()==0)
				{
					//INSERTING RATE_CUST_CONTRACT TABLE
					$rate_cust = "INSERT INTO rate_custcontract (cust_rate_id,con_code,cust_code,stncode,rate_type,origin,destn,mode,calcmethod,splcharges,date_added,user_name) VALUES
					('$cust_rate_id','$con_code','$cust_code','$stncode','$rate_type','$origin','$destn','$mode','$calcmethod','$splcharges','$date_added','$username')";
					$ratecust = $db->query($rate_cust);

					$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$con_code','Customer Rate','Insert','RateContract $cust_code has been Updated in $stncode','$username',getdate())");   
					for($i = 0; $i < count($_POST['onadd']); $i++)
					{
						$onadd             = stripslashes($_POST['onadd'][$i]);
						$lowerwt           = stripslashes($_POST['lowerwt'][$i]);
						$upperwt           = stripslashes($_POST['upperwt'][$i]);
						$rate              = stripslashes($_POST['rate'][$i]);
						$multiple          = stripslashes($_POST['multiple'][$i]);

						//INSERTING RATE_CONTRACT_DETAIL TABLE
						$detail = "INSERT INTO rate_contractdetail (con_code,stncode,onadd,lowerwt,upperwt,rate,multiple,date_added,user_name)
						VALUES ('$con_code','$stncode','$onadd','$lowerwt','$upperwt','$rate','$multiple','$date_added','$username')";
						$rate_detail = $db->query($detail);
					}
					$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$con_code','Customer Rate','Insert','RateContract $con_code has been Created in $stncode','$username',getdate())");   
					//$db->query("UPDATE rate_custcontract SET ratefile='$filepath' WHERE cust_code='$cust_code' AND stncode='$stncode'");
					header("Location: customerratelist?success=1");
				}
				else{
					header("Location: customerrate?unsuccess=1");
				}
               }
               else{
                    header("Location: customerrate?unsuccess=5");
               }
				}
			}

			function saveanothercustomerrate($db){
				//error_reporting(0);
				date_default_timezone_set('Asia/Kolkata');
				$date_added       = date('Y-m-d H:i:s');
				$cust_code                =  $_POST['cust_code'];
				$stncode                  =  $_POST['stncode'];
				$rate_type                =  $_POST['rate_type'];
				$origin                   =  $_POST['origin'];
				$destn                    =  explode('-',$_POST['zonedest'])[0];
				$mode                     =  $_POST['mode'];
				$calcmethod               =  $_POST['calcmethod'];
				$splcharges               =  $_POST['splcharges'];
				$username                 =  $_POST['user_name'];
	
				if(file_exists($_FILES["file"]["tmp_name"])){
					$tmpname = $_FILES["file"]["tmp_name"];
					$name = $_FILES["file"]["name"];

					$dataString = file_get_contents($tmpname);
					$encodedfile = base64_encode($dataString);
					
					$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Rate Master','Customer Rate','Updated','RateContract file has been updated','$username',getdate())");   

					$db->query("UPDATE customer SET rate_file_name='$name',rate_file='$encodedfile' WHERE comp_code='$stncode' AND cust_code='$cust_code'");
					}
				// Verify Zone code is valid
				$val_zonecust = $db->query("SELECT zncode FROM customer WHERE cust_code='$cust_code' AND comp_code='$stncode'");
				$row_valzone = $val_zonecust->fetch(PDO::FETCH_ASSOC);
				if($row_valzone['zncode']=='A'){ 
					$zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$stncode' AND zone_type='credit' AND zone_code='$destn' AND zone_code LIKE '[0-9]%'")->fetch(PDO::FETCH_OBJ);
				  }
				  else if($row_valzone['zncode']=='B'){
					$zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$stncode' AND zone_type='credit' AND zone_code='$destn' AND zone_code LIKE 'B%'")->fetch(PDO::FETCH_OBJ);
				  }
				  else if($row_valzone['zncode']=='C'){
					$zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$stncode' AND zone_type='credit' AND zone_code='$destn' AND zone_code LIKE 'C%'")->fetch(PDO::FETCH_OBJ);
				  }
				  else if($row_valzone['zncode']=='cash'){
					$zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$stncode' AND zone_type='cash' AND zone_code='$destn'")->fetch(PDO::FETCH_OBJ);
				  }
	
				$dest_verify = $db->query("SELECT stncode FROM stncode WHERE stncode='$destn'");
	
				if($zone_verify->counts2==0 && $dest_verify->rowCount()==0)
				{
					header("Location: customerrate?zonedest");
				}
				else{
				//Generating Con_code
				$ccode="SELECT TOP 1 con_code FROM rate_custcontract ORDER BY cast(dbo.GetNumericValue(con_code) AS decimal) DESC";
				$code=$db->query($ccode);
				$coderow=$code->fetch(PDO::FETCH_ASSOC);
				$row=$coderow['con_code'];
				if($code->rowCount()== 0){
					$con_code = 'CO'.str_pad('1', 7, "0", STR_PAD_LEFT);
				}
				else{
					$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$row);
					// $arr[0];PO
					// $arr[1]+1;numbers
					$con_code = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
				}
				
						//Generating Rate Id
					$fetchrateid = $db->query("SELECT TOP 1 cust_rate_id FROM rate_custcontract ORDER BY cust_rate_id DESC");
					if($fetchrateid->rowCount()==0){
						$cust_rate_id = 'RATE'.str_pad(1, 3, "0", STR_PAD_LEFT);
					}
					else{
						$fetchrateidbycustomer = $db->query("SELECT cust_rate_id FROM rate_custcontract WHERE cust_code='$cust_code'");
						if($fetchrateidbycustomer->rowCount()==0){
							$rownewrateid = $fetchrateid->fetch(PDO::FETCH_ASSOC);
							$lastrateid = $rownewrateid['cust_rate_id'];
							$arrrateid = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastrateid);
							$cust_rate_id = 'RATE'.str_pad($arrrateid[1]+1, 3, "0", STR_PAD_LEFT);
						}
						else{
							$rowrateid = $fetchrateidbycustomer->fetch(PDO::FETCH_ASSOC);
							$cust_rate_id = $rowrateid['cust_rate_id'];
						}
					}
                         $check_concode = $db->query("SELECT COUNT(con_code) AS concount FROM rate_custcontract WITH (NOLOCK) WHERE con_code='$con_code'")->fetch(PDO::FETCH_OBJ);
                         if($check_concode->concount==0){
				$mode_validation = $db->query("SELECT * FROM rate_custcontract WHERE stncode='$stncode' AND cust_code='$cust_code' AND destn='$destn' AND rate_type='$rate_type' AND mode='$mode'");
					if($mode_validation->rowCount()==0)
					{
						//INSERTING RATE_CUST_CONTRACT TABLE
						$rate_cust = "INSERT INTO rate_custcontract (cust_rate_id,con_code,cust_code,stncode,rate_type,origin,destn,mode,calcmethod,splcharges,date_added,user_name) VALUES
						('$cust_rate_id','$con_code','$cust_code','$stncode','$rate_type','$origin','$destn','$mode','$calcmethod','$splcharges','$date_added','$username')";
						$ratecust = $db->query($rate_cust);
						$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$con_code','Customer Rate','Insert','RateContract $cust_code has been Updated in $stncode','$username',getdate())");   
	
						for($i = 0; $i < count($_POST['onadd']); $i++)
						{
							$onadd             = stripslashes($_POST['onadd'][$i]);
							$lowerwt           = stripslashes($_POST['lowerwt'][$i]);
							$upperwt           = stripslashes($_POST['upperwt'][$i]);
							$rate              = stripslashes($_POST['rate'][$i]);
							$multiple          = stripslashes($_POST['multiple'][$i]);
	
							//INSERTING RATE_CONTRACT_DETAIL TABLE
							$detail = "INSERT INTO rate_contractdetail (con_code,stncode,onadd,lowerwt,upperwt,rate,multiple,date_added,user_name)
							VALUES ('$con_code','$stncode','$onadd','$lowerwt','$upperwt','$rate','$multiple','$date_added','$username')";
							$rate_detail = $db->query($detail);
						}
						$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$con_code','Customer Rate','Insert','RateContract $con_code has been Created in $stncode','$username',getdate())");   
						//$db->query("UPDATE rate_custcontract SET ratefile='$filepath' WHERE cust_code='$cust_code' AND stncode='$stncode'");
						header("Location: customerrate?success=1 && br_code=".$_POST['br_code']." && br_name=".$_POST['br_name']." && cust_code=".$_POST['cust_code']." && cust_name=".$_POST['cust_name']." && contract_period=".$_POST['contract_period']." && date_contract=".$_POST['date_contract']."");
					}
					else{
						header("Location: customerrate?unsuccess=1");
					}
                    }else{
                         header("Location: customerrate?unsuccess=5");
                    }
					}
				}
				

				function updatecustomerrate($db)
				{
					date_default_timezone_set('Asia/Kolkata');
					$cust_code              =  $_POST['cust_code'];
					$stncode                =  $_POST['stncode'];
					$mode					= $_POST['mode'];
					$rate_contract_file_name= $_POST['rate_contract_file_name'];
					$calcmethod				= $_POST['calcmethod'];
					$splcharges				= $_POST['splcharges'];
					$updateconcode			= $_POST['updateconcode'];
					$username               =  $_POST['user_name'];

					for($k=0;$k<count($_POST['updateonadd']);$k++){
						$custcontdetailid = stripslashes($_POST['custcontdetailid'][$k]);
						$updateonadd = stripslashes($_POST['updateonadd'][$k]);
						$updatelowerwt = stripslashes($_POST['updatelowerwt'][$k]);
						$updateupperwt = stripslashes($_POST['updateupperwt'][$k]);
						$updaterate = stripslashes($_POST['updaterate'][$k]);
						$updatemultiple = stripslashes($_POST['updatemultiple'][$k]);
						$updatecontract = $db->query("UPDATE TOP ( 1 ) rate_contractdetail SET onadd='$updateonadd',lowerwt='$updatelowerwt',upperwt='$updateupperwt',rate='$updaterate',multiple='$updatemultiple' WHERE con_code='$updateconcode' AND custcontdetailid='$custcontdetailid'");
					}


					if(empty($rate_contract_file_name)){
						$db->query("UPDATE customer SET rate_file_name=null,rate_file=null WHERE comp_code='$stncode' AND cust_code='$cust_code'");
						$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$updateconcode','Customer Rate','Update','Rates of $cust_code has been updated in $stncode','$username',getdate())");   

					}
					if(file_exists($_FILES["file"]["tmp_name"])){
						$tmpname = $_FILES["file"]["tmp_name"];
						$name = $_FILES["file"]["name"];

						$dataString = file_get_contents($tmpname);
						$encodedfile = base64_encode($dataString);
						

						$db->query("UPDATE customer SET rate_file_name='$name',rate_file='$encodedfile' WHERE comp_code='$stncode' AND cust_code='$cust_code'");
						$db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$updateconcode','Customer Rate','Update','Rates of $cust_code has been Updated in $stncode','$username',getdate())");   
						}

					header("Location: customerratedetail?success=1 && br_code=".$_POST['br_code']." && br_name=".$_POST['br_name']." && cust_code=".$_POST['cust_code']." && cust_name=".$_POST['cust_name']." && contract_period=".$_POST['contract_period']." && date_contract=".$_POST['date_contract']."");
				}