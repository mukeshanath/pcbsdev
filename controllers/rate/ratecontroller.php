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

	 class ratecontroller extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - fetch';
             // $this->view->render('cnote/fetch');
          }
      }
      error_reporting(0);

 //Fetching Customer Details in CustomerRate 
      if(isset($_POST['cust_rate']))
      {
      $codeno = $_POST['cust_rate'];
      $codestn = $_POST['getzn_stn'];
      $cudetail = $db->query("SELECT * FROM customer WHERE cust_code='$codeno' AND comp_code='$codestn'");
      $custdatarow = $cudetail->fetch(PDO::FETCH_ASSOC);

      echo  $custdatarow['date_contract'].",".$custdatarow['contract_period'].",".$custdatarow['stncode'].",".$custdatarow['stnname'].",".$custdatarow['br_code'].",".$custdatarow['br_name'].",".$custdatarow['cust_name'].",".$custdatarow['zncode'];
      }

      //Fetch Customer Contracts
      if(isset($_POST['ccode']))
      {
        $cus_code=$_POST['ccode'];
        $cust="SELECT * FROM rate_custcontract WHERE cust_code='$cus_code'";
        $custom=$db->query($cust);
        while($cusrow = $custom->fetch(PDO::FETCH_ASSOC))
        { ?>
          
          
          <tr class="table5" id="<?php echo $cusrow['con_code']; ?>">
          <td><?php echo $cusrow['custcontid']; ?></td>
          <td><?php echo $cusrow['con_code']; ?></td>
          <td><?php echo $cusrow['origin']; ?></td>
          <td><?php echo $cusrow['destn']; ?></td>
          <td><?php echo $cusrow['mode']; ?></td>
          </tr>
          
        <?php }
      }

        if(isset($_POST['test']))
        {
        $concode= $_POST['test']; 

        $getconcode = "SELECT * FROM rate_contractdetail WHERE con_code='$concode'";
        $get_concode = $db->query($getconcode);
        while($getrow = $get_concode->fetch(PDO::FETCH_ASSOC))
          { ?>
            <tr >
            <td><?php echo $getrow['custcontdetailid']; ?></td>
            <td><?php echo $getrow['onadd']; ?></td>
            <td><?php echo number_format($getrow['lowerwt'], 3); ?></td>
            <td><?php echo number_format($getrow['upperwt'], 3); ?></td>
            <td><?php echo $getrow['rate']; ?></td>
            <td><?php echo $getrow['multiple']; ?></td>
            </tr>
        <?php }
        }

        //Verify Destination is Valid
        if(isset($_POST['dest_code1']))
        {
          $dest_code1 = $_POST['dest_code1'];
          $dest_verify = $db->query("SELECT stncode FROM stncode WHERE stncode='$dest_code1'");
          echo $dest_verify->rowCount();
        }
        //Verify Zone is Valid
        if(isset($_POST['zone_code1']))
        {  
          $zone_code1 = $_POST['zone_code1'];
          $cust_zone = $_POST['cust_zone']; 
          $zoneval_stn = $_POST['zoneval_stn'];
          $val_zonecust = $db->query("SELECT zncode FROM customer WHERE cust_code='$cust_zone' AND comp_code='$zoneval_stn'");
          $row_valzone = $val_zonecust->fetch(PDO::FETCH_ASSOC);
          if($row_valzone['zncode']=='A'){
            $zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$zoneval_stn' AND zone_type='credit' AND zone_code='$zone_code1' AND zone_code LIKE '[0-9]%'")->fetch(PDO::FETCH_OBJ);
          }
          else if($row_valzone['zncode']=='B'){
            $zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$zoneval_stn' AND zone_type='credit' AND zone_code='$zone_code1' AND zone_code LIKE 'B%'")->fetch(PDO::FETCH_OBJ);
          }
          else if($row_valzone['zncode']=='C'){
            $zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$zoneval_stn' AND zone_type='credit' AND zone_code='$zone_code1' AND zone_code LIKE 'C%'")->fetch(PDO::FETCH_OBJ);
          }
          else if($row_valzone['zncode']=='cash'){
            $zone_verify = $db->query("SELECT count(*) as counts2 FROM zone WHERE comp_code='$zoneval_stn' AND zone_code='$zone_code1' AND zone_type='cash'")->fetch(PDO::FETCH_OBJ);
          }
          echo $zone_verify->counts2;
        }

        //Fetch Customer Contract Detail
        if(isset($_POST['contractcode1']))
        {
          $contractcode1 = $_POST['contractcode1'];
          $cont_stncode = $_POST['cont_stncode'];
          $fetchcontractcode1 = $db->query("SELECT * FROM rate_contractdetail WHERE con_code='$contractcode1' AND stncode='$cont_stncode'");
          while($rowcontcode1 = $fetchcontractcode1->fetch(PDO::FETCH_OBJ))
          { ?>
               <tr>
               <td></td>
            <td><?php echo $rowcontcode1->onadd; ?></td>
            <td><?php echo number_format($rowcontcode1->lowerwt,3); ?></td>
            <td><?php echo number_format($rowcontcode1->upperwt,3); ?></td>            
            <td><?php echo $rowcontcode1->rate; ?></td>
            <td><?php echo $rowcontcode1->multiple; ?></td>
            </tr>
          <?php }

        }
       
         //Autocomplete Zonecode based on customer in Rate Master
        if(isset($_POST['cust_destn']))
        {
           $cust_destn  = $_POST['cust_destn'];
           $comp_destn    = $_POST['comp_destn'];
           $get_zonecust = $db->query("SELECT zncode FROM customer WHERE cust_code='$cust_destn' AND comp_code='$comp_destn'");
           $row_zncode = $get_zonecust->fetch(PDO::FETCH_ASSOC);
          if($row_zncode['zncode']=='A'){
            $get_zonecode = $db->query("SELECT zone_code,zone_name FROM zone WHERE comp_code='$comp_destn' AND zone_type='credit' AND zone_code LIKE '[0-9]%'");
            while($rowget_zonecode = $get_zonecode->fetch(PDO::FETCH_ASSOC))
            {
              $zonevalues[] = $rowget_zonecode['zone_code']."-".$rowget_zonecode['zone_name'];
            }
          }
          else if($row_zncode['zncode']=='B'){
            $get_zonecode = $db->query("SELECT zone_code,zone_name FROM zone WHERE comp_code='$comp_destn' AND zone_type='credit' AND zone_code LIKE 'B%'");
            while($rowget_zonecode = $get_zonecode->fetch(PDO::FETCH_ASSOC))
            {
              $zonevalues[] = $rowget_zonecode['zone_code']."-".$rowget_zonecode['zone_name'];
            }
          }
          else if($row_zncode['zncode']=='C'){
            $get_zonecode = $db->query("SELECT zone_code,zone_name FROM zone WHERE comp_code='$comp_destn' AND zone_type='credit' AND zone_code LIKE 'C%'");
            while($rowget_zonecode = $get_zonecode->fetch(PDO::FETCH_ASSOC))
            {
              $zonevalues[] = $rowget_zonecode['zone_code']."-".$rowget_zonecode['zone_name'];
            }
          }
          else if($row_zncode['zncode']=='cash'){
            $get_zonecode = $db->query("SELECT zone_code,zone_name FROM zone WHERE comp_code='$comp_destn' AND zone_type='cash'");
            while($rowget_zonecode = $get_zonecode->fetch(PDO::FETCH_ASSOC))
            {
              $zonevalues[] = $rowget_zonecode['zone_code']."-".$rowget_zonecode['zone_name'];
            }
          }
            echo json_encode($zonevalues);
        }
        //Fetch Contract Details for Edit form
        if(isset($_POST['editcontract']))
        {
          $editcontract  = $_POST['editcontract'];
          $getcontract = $db->query("SELECT * FROM rate_custcontract WHERE con_code='$editcontract'");
          $rowgetcontract = $getcontract->fetch(PDO::FETCH_ASSOC);
          echo $rowgetcontract['origin'].",".$rowgetcontract['destn'].",".$rowgetcontract['mode'].",".$rowgetcontract['calcmethod'].",".$rowgetcontract['splcharges'];
          
        }
        if(isset($_POST['editcontractbands']))
        {
          $editcontractbands = $_POST['editcontractbands'];
          $editcont_stncode = $_POST['editcont_stncode'];
          $getcontractdetails = $db->query("SELECT * FROM rate_contractdetail WHERE con_code='$editcontractbands' AND stncode='$editcont_stncode'");
          $a=0;
          while($rowgetcontractdetails = $getcontractdetails->fetch(PDO::FETCH_ASSOC)){
            $a++;
            echo '<tr>
                  <td style="display:none"><input  value="'.$rowgetcontractdetails['custcontdetailid'].'" type="hidden" name="custcontdetailid[]"></td>
                  <td><input class="rate_detil onadd'.$a.'" onkeyup="onadd()" value="'.$rowgetcontractdetails['onadd'].'" type="text" name="updateonadd[]"></td>
                  <td><input class="rate_detil" id="lower_wt1" value="'.number_format($rowgetcontractdetails['lowerwt'],3).'" type="text" name="updatelowerwt[]" readonly></td>
                  <td><input class="rate_detil" id="upper_wt1" value="'.number_format($rowgetcontractdetails['upperwt'],3,'.','').'" type="text" name="updateupperwt[]"></td>
                  <td><input class="rate_detil" value="'.$rowgetcontractdetails['rate'].'" type="text" name="updaterate[]"></td>
                  <td><input class="rate_detil multiple'.$a.'" value="'.$rowgetcontractdetails['multiple'].'" type="text" name="updatemultiple[]">
                  </tr>';  
             }
        }
        // auto populate customer based on branch
        if(isset($_POST['branch8']))
        {
          $branch8  = $_POST['branch8'];
          $comp8    = $_POST['comp8'];
          $getautoppl8 = $db->query("SELECT cust_code FROM customer WHERE comp_code='$comp8' AND br_code='$branch8' AND cust_active='Y'");
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
          $getautopp11 = $db->query("SELECT cust_name FROM customer WHERE comp_code='$comp11' AND br_code='$branch11' AND cust_active='Y'");
          if($getautopp11->rowCount()==0){

          }else{
          while($getautopp11row = $getautopp11->fetch(PDO::FETCH_ASSOC))
          {
            $arrayvalues11[] = $getautopp11row['cust_name'];
          }
          echo json_encode($arrayvalues11);
        }
        }
         //Fetch Branch Name
        if(isset($_POST['branchlookup']))
        {
          $branchlookup = $_POST['branchlookup'];
          $get_branch = $db->query("SELECT br_name FROM branch WHERE br_code='$branchlookup' AND br_active='Y'");
          $branch_row = $get_branch->fetch(PDO::FETCH_ASSOC);
          echo $branch_row['br_name'];
        }
          //fetch branch code
        if(isset($_POST['s_bname'])){
        $s_bname = $_POST['s_bname'];
        $s_comp = $_POST['s_comp'];
        echo branchname($s_bname,$s_comp,$db);
        }
          //Fetch Customer code
          if(isset($_POST['cccust_name'])){
            $cccust_name = $_POST['cccust_name'];
            $cc_namestn = $_POST['cc_namestn'];
            $cc_namebr = $_POST['cc_namebr'];
            $rowcustcode = $db->query("SELECT cust_code FROM customer WHERE cust_name='$cccust_name' AND comp_code='$cc_namestn' AND br_code='$cc_namebr' AND cust_active='Y'")->fetch(PDO::FETCH_OBJ);
            echo $rowcustcode->cust_code;
          }
         //fetch BRANCH CODE FUNCTION
         function branchname($s_bname,$s_comp,$db){
          $brname = $db->query("SELECT br_code FROM branch WHERE br_name='$s_bname' AND stncode='$s_comp' AND br_active='Y'")->fetch(PDO::FETCH_OBJ);
          return $brname->br_code;
          }
        //delete masters data
        if(isset($_POST['deletetb'])){
          $deletetb = $_POST['deletetb'];
          $deleterow = $_POST['deleterow'];
          $deletecol = $_POST['deletecol'];
          $delete_user = $_POST['delete_user'];
          $delete_station = $_POST['delete_station'];
          $delete = $db->query("DELETE FROM $deletetb WHERE $deletecol='$deleterow' AND stncode='$delete_station'");
          $delete = $db->query("DELETE FROM rate_contractdetail WHERE $deletecol='$deleterow' AND stncode='$delete_station'");
          //add logs
						$yourbrowser   = getBrowser()['name'];
						$user_surename = get_current_user();
						$platform      = getBrowser()['platform'];   
						$remote_addr   = getBrowser()['remote_addr'];  
            $date_added         	 = date('Y-m-d H:i:s');
        //  $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$delete_user','$deletecol  $deleterow has been deleted from $deletetb - station : $delete_station','$yourbrowser')");
          $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$deleterow','Customer Rate','Delete','RateContract $deleterow has been deleted in $delete_station','$delete_user',getdate())");

        }
        //get all contracts by customer json
        if(isset($_POST['bprintcustcode1'])){
          echo getallcust_contracts($db,$_POST['bprintcustcode1'],$_POST['bprintcont_stncode'],$_POST['bprint_custzone']);
        }
        function getallcust_contracts($db,$bprintcustcode1,$bprintcont_stncode,$bprint_custzone)
        {
          $contracts = array();

          $getcontracts = $db->query("SELECT 
          con_code
          ,cust_code
          ,destn
          ,mode
        ,z.zone_name
        FROM rate_custcontract rc LEFT JOIN zone z ON z.comp_code=rc.stncode and z.zone_type='$bprint_custzone' and z.zone_code=destn
        where cust_code='$bprintcustcode1' and stncode='$bprintcont_stncode'");
        $contracts_index = 0;
        while($rowgetcontracts = $getcontracts->fetch(PDO::FETCH_OBJ))
        {
        $contracts[$contracts_index]['CONCODE']=$rowgetcontracts->con_code;
        $contracts[$contracts_index]['MODE']=$rowgetcontracts->mode;
        $contracts[$contracts_index]['DESTN']=$rowgetcontracts->destn." - ".$rowgetcontracts->zone_name;
        $getsubcontracts = $db->query("SELECT onadd,lowerwt,upperwt,rate,multiple FROM rate_contractdetail WHERE stncode='$bprintcont_stncode' AND con_code='$rowgetcontracts->con_code'");
        $bands = array();
        $bands_index=0;
        while($rowgetsubcontracts = $getsubcontracts->fetch(PDO::FETCH_OBJ)){
          $bands[$bands_index]['ONADD'] = $rowgetsubcontracts->onadd;
          $bands[$bands_index]['LW']    = $rowgetsubcontracts->lowerwt;
          $bands[$bands_index]['UW']    = $rowgetsubcontracts->upperwt;
          $bands[$bands_index]['RATE']  = $rowgetsubcontracts->rate;
          $bands[$bands_index]['MULTIPLE']  = $rowgetsubcontracts->multiple;
          $bands_index++;
        }
        $contracts[$contracts_index]['BANDS']=$bands;
        $contracts_index++;
        }
          
          return json_encode($contracts);
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