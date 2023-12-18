<style>
    table tr th, table tr td{
        border:.1px solid black;
    }
</style>
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


      class Ratecheckexcel extends My_controller
      {
          public function index()
          {
              $this->view->title = 'OFFLINE - ratedeviationcheck';
              //$this->view->render('billing/ratedeviationcheck');
          }
      }



include 'vendor/bootstrap/simplexlxs.php';

date_default_timezone_set('Asia/Kolkata');
$date_modified       	 = date('Y-m-d H:i:s');  

            $yourbrowser   = getBrowser()['name'];
						$user_surename = get_current_user();
						$platform      = getBrowser()['platform'];   
						$remote_addr   = getBrowser()['remote_addr']; 

if(isset($_POST['saveall']))
{
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=ratedeviation.xlsx");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
        echo "<table>
        <tr>
        <th>origin</th>
        <th>POD origin</th>
        <th>Cust code</th>
        <th>cno</th>
        <th>destn</th>
        <th>wt</th>
        <th>pcs</th>
        <th>mode</th>
        <th>amt</th>
        <th>tdate</th>
        <th>correct amount</th>
        </tr>";
        $file = $_FILES["rate_excel"]["tmp_name"];
        $xlsx = SimpleXLSX::parse($file); 
        $xlsxrow = $xlsx->rows(); 
        for($i=0;$i<count($xlsxrow);$i++){
             if($i==0){
        
             }
             else{
             $j=0;
             foreach($xlsxrow[$i] as $data){
               $j++;
                  if($j>=28){ }
                  else{        
                  echo "<td>$data</td>";
                   if($j==1){
                      $r_origin = $data;
                    }
                    if($j==2){
                        $r_pod = $data;
                    }
                    if($j==3){
                      $custcode = $data;
                    }
                    if($j==4){
                      $up_cnote = $data;
                    }
                    if($j==5){
                        $r_destn = $data;
                    }
                    if($j==6){
                        $r_weight = $data;
                    }
                    if($j==8){
                        $r_mode = $data;
                    }
                    if($j==9){
                        $r_rate = $data;
                    }
                  }
                  $verifycno = $db->query("SELECT count(cnote_number) as validcount FROM cnotenumber WHERE cnote_number='$up_cnote' AND cnote_station='$r_origin'")->fetch(PDO::FETCH_OBJ);
                  }
                  echo "<td>".$crctamt = amountcalculation($db,$custcode,$r_origin,$r_destn,$r_weight,$r_mode,$r_pod)."</td>";
                  if($r_rate==$crctamt){
                       echo "<td>0</td>";
                  }
                  else{
                    echo "<td>1</td>";
                  }
                  echo "<td>$verifycno->validcount</td>";
                  echo "</tr>";
             }
        }
    echo "</table>";
}


if(isset($_POST['updateall'])){
      $user_name = $_POST['user_name'];
      $file = $_FILES["rate_excel_update"]["tmp_name"];
      $xlsx = SimpleXLSX::parse($file); 
      $xlsxrow = $xlsx->rows(); 
      for($i=0;$i<count($xlsxrow);$i++){
          if($i==0){
      
          }
          else{
          $j=0;
          foreach($xlsxrow[$i] as $data){
            $j++;
                if($j>=28){ }
                else{        
                  
                  if($j==1){
                      $r_origin = $data;
                  }
                  if($j==2){
                      $r_pod = $data;
                  }
                  if($j==3){
                    $custcode = $data;
                  }
                  if($j==4){
                    $up_cnote = $data;
                  }
                  if($j==5){
                      $r_destn = $data;
                  }
                  if($j==6){
                      $r_weight = $data;
                  }
                  if($j==8){
                      $r_mode = $data;
                  }
                  if($j==9){
                      $r_rate = $data;
                  }
                }
                
                }

                $crctamt = amountcalculation($db,$custcode,$r_origin,$r_destn,$r_weight,$r_mode,$r_pod);

                //update cnotes
                $upd_credit = $db->query("UPDATE credit SET amt='$crctamt',date_modified='$date_modified' OUTPUT Inserted.invno WHERE cno='$up_cnote' AND comp_code='$r_origin'")->fetch(PDO::FETCH_OBJ);
                $db->query("UPDATE cash SET amt='$crctamt',date_modified='$date_modified' WHERE cno='$up_cnote' AND comp_code='$r_origin'");
                $upd_cno = $db->query("UPDATE cnotenumber SET bilrate='$crctamt',date_modified='$date_modified' OUTPUT Inserted.cnotenoid WHERE cnote_number='$up_cnote' AND cnote_station='$r_origin'")->fetch(PDO::FETCH_OBJ);
                if($r_rate!=$crctamt){
                $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application)VALUES ('$remote_addr','$platform','$date_modified','$user_surename','$user_name','Masterid :$upd_cno->cnotenoid, cno :$up_cnote, customer :$custcode, amt :$r_rate, revised_amt :$crctamt, invno :$upd_credit->invno, Revised','$yourbrowser')"); 
              }
          }
      }
      Header('Location:rateupdate?success=1');
}

//Calculate Amount for Credit
function amountcalculation($db,$custcode,$r_origin,$r_destn,$r_weight,$r_mode,$r_pod)
{
  $ccustomer    	  = $custcode;
  $creditorigin      = $r_origin;
  $destcode_ofcredit = $r_destn;
  $creditweight      = $r_weight;

  $crate = $db->query("SELECT rate FROM customer WHERE comp_code='$creditorigin' AND cust_code='$ccustomer'");
  $valcrate = $crate->fetch(PDO::FETCH_OBJ);
  if(!empty($valcrate->rate)){
    $creditcustomer = $valcrate->rate;
  }
  else{
    $creditcustomer = $ccustomer;
  }

  $getcustzncode = $db->query("SELECT zncode FROM customer WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
  $rowzncode = $getcustzncode->fetch(PDO::FETCH_ASSOC);

  $getdestfrompin = $db->query("SELECT TOP ( 1 ) dest_code FROM pincode WHERE pincode='$destcode_ofcredit' OR dest_code='$destcode_ofcredit' OR city_name='$destcode_ofcredit'");
  if($getdestfrompin->rowCount()==0){
    $verifydestination = $db->query("SELECT TOP ( 1 ) dest_code FROM destination WHERE comp_code='$creditorigin' AND dest_code='$destcode_ofcredit' OR dest_name='$destcode_ofcredit'");
    $rowdestdata = $verifydestination->FETCH(PDO::FETCH_OBJ);
    $val_rowdestdata = $rowdestdata->dest_code;
    $verifyratecode = $db->query("SELECT con_code FROM rate_custcontract WHERE origin='$creditorigin' AND destn='$val_rowdestdata' AND cust_code='$creditcustomer'");
    if($verifyratecode->rowCount()==0)
    {
	 // Customer type A rate setup populate
	if($rowzncode['zncode']=='A'){
	 $getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
	 if($getrowdest->rowCount()==0){
	   $creditdestination =  $destcode_ofcredit;
	 }else{
	 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	 $creditdestination = $rowgetzonecode['zonecode_a'];
    }
	}
   
    // Customer type B rate setup populate
   if($rowzncode['zncode']=='B'){
	 $getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata' ");
	 if($getrowdest->rowCount()==0){
	   $creditdestination = $destcode_ofcredit;
	 }else{
	 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	 $creditdestination = $rowgetzonecode['zonecode_b'];
	 }
	}

    // Customer type C rate setup populate
   if($rowzncode['zncode']=='C'){
	 $getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
	 if($getrowdest->rowCount()==0){
	   $creditdestination =  $destcode_ofcredit;
	 }else{
	 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	 $creditdestination = $rowgetzonecode['zonecode_c'];
	 }
	}

    // Cash customer rate setup populate
   if($rowzncode['zncode']=='cash'){
	 $getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
	 if($getrowdest->rowCount()==0){
	   $creditdestination = $destcode_ofcredit;
	 }else{
	 $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	 $creditdestination = $rowgetzonecode['zone_cash'];
	 }
	}
  }else{
    $creditdestination = $rowdestdata->dest_code;
  }
  }
  else{
    $rowactualdest = $getdestfrompin->fetch(PDO::FETCH_ASSOC); 
    $valactueldest = $rowactualdest["dest_code"];
    $checkdestn = $db->query("SELECT * FROM destination WHERE comp_code='$creditorigin' AND dest_code='".$rowactualdest["dest_code"]."'");
    $rowcheckdestn = $checkdestn->fetch(PDO::FETCH_OBJ);

    $dest_rate = $db->query("SELECT * from rate_custcontract WHERE stncode='$creditorigin' AND destn='$rowcheckdestn->dest_code' AND cust_code='$creditcustomer'");
    if($dest_rate->rowCount()==0){
    if($rowzncode['zncode']=='A'){
	  $getrowdest = $db->query("SELECT zonecode_a FROM destination WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
	  $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	  $creditdestination = $rowgetzonecode['zonecode_a'];
	 }
    if($rowzncode['zncode']=='B'){
	  $getrowdest = $db->query("SELECT zonecode_b FROM destination WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
	  $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	  $creditdestination = $rowgetzonecode['zonecode_b'];
	 }
    if($rowzncode['zncode']=='C'){
	  $getrowdest = $db->query("SELECT zonecode_c FROM destination WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
	  $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	  $creditdestination = $rowgetzonecode['zonecode_c'];
	 }
    if($rowzncode['zncode']=='cash'){
	  $getrowdest = $db->query("SELECT zone_cash FROM destination WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
	  $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
	  $creditdestination = $rowgetzonecode['zone_cash'];
	 }
    }else{
	 $creditdestination = $rowcheckdestn->dest_code;

    }
  }
 
	$pod_amt         = $r_pod;

	
	 if($pod_amt=='PRO' && $creditweight>1){
	   $getsp = $db->query("SELECT pro_per_kg FROM customer WHERE cust_code='$ccustomer'");
	   $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
	   $proamount = $rowsp->pro_per_kg;
	   $pro = ceil($creditweight) - 1;
	   $pro_rate = $pro * $proamount;
	 }
	 else if($pod_amt=='PRC' && $creditweight>1){
	   $getsp = $db->query("SELECT prc_per_kg FROM customer WHERE cust_code='$ccustomer'");
	   $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
	   $proamount = $rowsp->prc_per_kg;
	   $pro = ceil($creditweight) - 1;
	   $pro_rate = $pro * $proamount;
	 }
	 else if($pod_amt=='PRD' && $creditweight>1){
	   $getsp = $db->query("SELECT prd_per_kg FROM customer WHERE cust_code='$ccustomer'");
	   $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
	   $proamount = $rowsp->prd_per_kg;
	   $pro = ceil($creditweight) - 1;
	   $pro_rate = $pro * $proamount;
	 }
   else{
    $pro_rate = '0';
   }
	
  //end special cnotes
  $creditpcs         = '1';
  $creditmode         = $r_mode;
  
  $getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC");
  $rowcontract  = $getcontract->fetch(PDO::FETCH_ASSOC);
  $contractcode = $rowcontract['con_code'];
  //validate weight
  $weightval = $db->query("SELECT MAX (upperwt) as maxweight FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin'");
  $rowmaxweight = $weightval->fetch(PDO::FETCH_OBJ);
  if($rowmaxweight->maxweight<$creditweight && $creditweight>0){
	return "0";
  }
  //end
  $getamount    = $db->query("SELECT * FROM rate_contractdetail WHERE con_code='$contractcode' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight' AND stncode='$creditorigin'");
  $rowamount    = $getamount->fetch(PDO::FETCH_ASSOC);
  if($rowamount['onadd']=='0'){
	return ($rowamount['rate']+$pro_rate) * $creditpcs;
  }
  else if($rowamount['onadd']=='.25'){
    $floor = floor($creditweight/$rowamount['multiple'])*$rowamount['multiple'];
    $isDecimal = $creditweight/$rowamount['multiple'];
    $decimal = ((float) $isDecimal !== floor($isDecimal));
    if(!empty($decimal)){
	   $round = $floor+$rowamount['multiple'];
    }
    else{
	   $round = $floor;
    }

    $first_rate = $rowamount['onadd'];
    $getfirst_rate = $db->query("SELECT rate,onadd FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$first_rate' AND upperwt >= '$first_rate'");
    $rowfirst_rate = $getfirst_rate->fetch(PDO::FETCH_ASSOC);
    $rate1 = $rowfirst_rate['rate'];

    $second_rate = ($round-$first_rate)/$first_rate;
    $rate2 = $second_rate * $rowamount['rate'];
    return (($rate1+$rate2)+$pro_rate)*$creditpcs;
  }
  else if($rowamount['onadd']=='.5'){

	$floor = floor($creditweight/$rowamount['multiple'])*$rowamount['multiple'];
	$isDecimal = $creditweight/$rowamount['multiple'];
	$decimal = ((float) $isDecimal !== floor($isDecimal));
	if(!empty($decimal)){
	    $roundvalue = $floor+$rowamount['multiple'];
	}
	else{
	    $roundvalue = $floor;
	}

	$getrate2 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '.5' AND stncode='$creditorigin'");
	$getratewt1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '1' AND stncode='$creditorigin'");
	$getratewt3 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '.25' AND stncode='$creditorigin'");
    
	 if($getratewt1->rowCount()!=0 && $creditweight>1){
		 $subavailwt = $creditweight-1;
		 $floor = floor($subavailwt/$rowamount['multiple'])*$rowamount['multiple'];
		 $isDecimal = $subavailwt/$rowamount['multiple'];
		 $decimal = ((float) $isDecimal !== floor($isDecimal));
		 if(!empty($decimal)){
			$roundvalue2 = $floor+$rowamount['multiple'];
		 }
		 else{
			$roundvalue2 = $floor;
		 }
		 $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
		 $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
		 $getrate2row = $getratewt1->fetch(PDO::FETCH_OBJ);
		 $rate2 = $getrate2row->rate;
	  }
	  else if($getrate2->rowCount()!=0 && $creditweight>.5){
	    $subavailwt = $creditweight-.5;
	    $floor = floor($subavailwt/$rowamount['multiple'])*$rowamount['multiple'];
	    $isDecimal = $subavailwt/$rowamount['multiple'];
	    $decimal = ((float) $isDecimal !== floor($isDecimal));
	    if(!empty($decimal)){
		   $roundvalue2 = $floor+$rowamount['multiple'];
	    }
	    else{
		   $roundvalue2 = $floor;
	    }
	    $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
	    $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
	    $getrate2row = $getrate2->fetch(PDO::FETCH_OBJ);
	    $rate2 = $getrate2row->rate;
	}
	  else if($getratewt3->rowCount()!=0){
	    $subavailwt = $creditweight-.25;
	    $floor = floor($subavailwt/$rowamount['multiple'])*$rowamount['multiple'];
	    $isDecimal = $subavailwt/$rowamount['multiple'];
	    $decimal = ((float) $isDecimal !== floor($isDecimal));
	    if(!empty($decimal)){
		   $roundvalue2 = $floor+$rowamount['multiple'];
	    }
	    else{
		   $roundvalue2 = $floor;
	    }
	    $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
	    $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
	    $getrate3row = $getratewt3->fetch(PDO::FETCH_OBJ);
	    $rate2 = $getrate3row->rate;
	}
	else{
	    $subavailwt = $creditweight-.25;
	    $floor = floor($subavailwt/$rowamount['multiple'])*$rowamount['multiple'];
	    $isDecimal = $subavailwt/$rowamount['multiple'];
	    $decimal = ((float) $isDecimal !== floor($isDecimal));
	    if(!empty($decimal)){
		   $roundvalue2 = $floor+$rowamount['multiple'];
	    }
	    else{
		  $roundvalue2 = $floor;
	    }
		$getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
		$rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
		$getrate3 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND stncode='$creditorigin' AND upperwt = '.25'")->fetch(PDO::FETCH_OBJ);
		$rate2 = $getrate3->rate;
	}
    
	return $rate1+$rate2+$pro_rate;
   }
  else if($rowamount['onadd']=='1'){
    $floor = floor($creditweight/$rowamount['multiple'])*$rowamount['multiple'];
    $isDecimal = $creditweight/$rowamount['multiple'];
    $decimal = ((float) $isDecimal !== floor($isDecimal));
    if(!empty($decimal)){
	   $round = $floor+$rowamount['multiple'];
    }
    else{
	   $round = $floor;
    }
   
    return (($round*$rowamount['rate'])+$pro_rate)*$creditpcs;
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