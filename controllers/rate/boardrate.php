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

class boardrate extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - boardrate';
		$this->view->render('rate/boardrate');
	}
}
		error_reporting(0);

		if(isset($_POST['addcustomerrate']))
		{
			saveboardrate($db);
		}
		
		function saveboardrate($db)
		{

			date_default_timezone_set('Asia/Kolkata');

			$stncode                  =  $_POST['stncode'];
			$stnname                  =  $_POST['stnname'];
			$br_code                  =  $_POST['br_code'];
			$br_name                  =  $_POST['br_name'];
			$cnote_serial             =  $_POST['cnote_serial'];
			$username                 =  $_POST['user_name'];
			$date_added               =  date('Y-m-d');

			for($i = 0; $i < count($_POST['zone']); $i++)
					{
						$zone                 = stripslashes($_POST['zone'][$i]);
						$mode                 = stripslashes($_POST['mode'][$i]);
						$wt0                  = stripslashes($_POST['wt0'][$i]);
						$wt101                = stripslashes($_POST['wt101'][$i]);
						$wt251                = stripslashes($_POST['wt251'][$i]);
						$wt401                = stripslashes($_POST['wt401'][$i]);
						$wt501                = stripslashes($_POST['wt501'][$i]);
						$wt1kg                = stripslashes($_POST['wt1kg'][$i]);
						$wt2kgabove           = stripslashes($_POST['wt2kgabove'][$i]);
						


						//fetch RateCode
						$rcode=$db->query("SELECT TOP 1 rate_code FROM rate_stnboardratetb ORDER BY brdrtid DESC");
						$coderow=$rcode->fetch(PDO::FETCH_ASSOC);
						$row = $coderow['rate_code'];
						//Gnerating Rate Code
						if($rcode->rowCount()==0){
							$rate_code = 'RT1000001';
						}
						else{
							$arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$row);
							$arr[0];//PO
							$arr[1]+1;//numbers
							$rate_code = $arr[0].($arr[1]+1);
						}

						//fetching Destinations
						$dest = $db->query("SELECT dest_code FROM zonetb WHERE zncode='$zone'");
						$destrow=$dest->fetch(PDO::FETCH_ASSOC);
						$destinations = $destrow['dest_code'];
						//Inserting Data
						$boardrate = "INSERT INTO rate_stnboardratetb (stncode,stnname,br_code,br_name,rate_code,zone,destinations,mode,wt0,wt101,wt251,wt401,wt501,wt1kg,wt2kgabove,date_added,user_name)
						VALUES ('$stncode','$stnname','$br_code','$br_name','$rate_code','$zone','$destinations','$mode','$wt0','$wt101','$wt251','$wt401','$wt501','$wt1kg','$wt2kgabove','$date_added','$username')";
						$insert = $db->query($boardrate);
					}


}
