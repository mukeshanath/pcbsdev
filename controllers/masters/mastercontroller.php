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

 require 'vendor/dompdf/autoload.inc.php';

	 class mastercontroller extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - fetch';
             // $this->view->render('cnote/fetch');
          }
      }
      error_reporting(0);
      //Fetch State code for Station code form
      if(isset($_POST['state']))
      {
          $state = $_POST['state'];
          $state_name = $db->query("SELECT state_name FROM state WHERE statecode='$state'");
          $state_row=$state_name->fetch();
          echo $state_row['state_name'];
      }
      //Fetch Country code for Station code tb
      if(isset($_POST['country']))
      {
          $country = $_POST['country'];
          $country_name = $db->query("SELECT coun_name FROM country WHERE coun_code='$country'");
          $country_row=$country_name->fetch();
          echo $country_row['coun_name'];
      }
       //Fetch State and dest name for Destination tb
       if(isset($_POST['dest_code']))
       {
           $dest_code = $_POST['dest_code'];
           $dest_name = $db->query("SELECT * FROM stncode WHERE stncode='$dest_code'");
           $row=$dest_name->fetch(PDO::FETCH_ASSOC);
           echo $row['stnname'].",".$row['state'].",".$row['stname'];
       }
       //Fetch Destinatio Code
       if(isset($_POST['dest_name1']))
       {
           $dest_name1 = $_POST['dest_name1'];
           $dest_code1 = $db->query("SELECT * FROM stncode WHERE stnname='$dest_name1'");
           $row1=$dest_code1->fetch(PDO::FETCH_ASSOC);
           echo $row1['stncode'].",".$row1['state'].",".$row1['stname'];
       }
       //Generate Customer Code for customer master form
       if(isset($_POST['br_code']))
       {
           $br_code = $_POST['br_code'];
           $genccode_stn = $_POST['genccode_stn'];
           $getcustcode = $db->query("SELECT TOP 1 cust_code,br_code FROM customer WHERE  br_code='$br_code' AND comp_code='$genccode_stn'  AND flag=1 AND cust_code LIKE '$br_code%' AND cust_code LIKE '%[0-9]%' ORDER BY cast(dbo.GetNumericValue(cust_code) AS decimal) DESC");
           $formrow = $getcustcode->fetch(PDO::FETCH_ASSOC);
           $lastcustcode = $formrow['cust_code'];
           if($getcustcode->rowCount()== 0){
            $cust_code = $br_code.str_pad('1', 4, "0", STR_PAD_LEFT);
            }
            else
            {
                $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastcustcode);
                        $arr[0];//StationCode
                        $arr[1]+1;//numbers
                        $cust_code = $arr[0].str_pad(($arr[1]+1), 4, "0", STR_PAD_LEFT);
            }
            //check for duplicates
            $checkforccode = $db->query("SELECT count(*) as counts FROM customer WHERE br_code='$br_code' AND comp_code='$genccode_stn' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);
  
            echo $cust_code.",".$checkforccode->counts;
       }

       //Fetching City, State, Country for Company Master form
        if(isset($_POST['stncode']))
        {
            $stncode = $_POST['stncode'];
            $codetb = $db->query("SELECT * FROM stncode WHERE stncode='$stncode'");
            $codetbrow = $codetb->fetch(PDO::FETCH_ASSOC);
            echo $codetbrow['stnname'].",".$codetbrow['stname'].",".$codetbrow['coun_name'];
        }
        //Generating Vendor Code
        if(isset($_POST['station']))
        {
            $stncode = $_POST['station'];
            $prev_vendcode = $db->query("SELECT vendor_code FROM vendor WHERE stncode='$stncode' ORDER BY vend_id DESC");
            $row_vendcode = $prev_vendcode->fetch(PDO::FETCH_ASSOC);
            $last_vendorcode = $row_vendcode['vendor_code'];
            if($prev_vendcode->rowCount()== 0)
            {
                $vendr_code = $stncode.'VN'.str_pad('1', 4, "0", STR_PAD_LEFT);
            }
            else
            {
                $arr_vn = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$last_vendorcode);
                $vendr_code = $arr_vn[0].str_pad(($arr_vn[1]+1), 4, "0", STR_PAD_LEFT);
            }
            echo $vendr_code;
        }
        //Fetch company code and name header
        if(isset($_POST['stncodes_head']))
        {
          $stncodes_head = $_POST['stncodes_head'];
          $usr_head = $_POST['usr_head'];
          $companies = $db->query("SELECT stncode,stnname FROM stncode WHERE stncode='$stncodes_head'");
          $row_companies = $companies->fetch(PDO::FETCH_ASSOC);
          //update branch
          $getbrhead = $db->query("SELECT br_codes FROM users WHERE user_name='$usr_head'")->fetch(PDO::FETCH_OBJ);
          $newHeadBr = json_decode($getbrhead->br_codes,true)[$stncodes_head][0];
          $update_superadmin = $db->query("UPDATE users SET last_compcode = '$stncodes_head',br_code='$newHeadBr' WHERE user_name='$usr_head'");
          //update branch
          echo $row_companies['stncode'].",".$row_companies['stnname'];
        }
        //Verify Destination is Valid
        if(isset($_POST['dest_code1']))
        {
          $dest_code1 = $_POST['dest_code1'];
          $dest_verify = $db->query("SELECT stncode FROM stncode WHERE stncode='$dest_code1'");
          echo $dest_verify->rowCount();
        }
        //Verify Zones is Valid
        if(isset($_POST['zonecodea_val']))
        {
           $zonecodea_val = explode('-',$_POST['zonecodea_val']);
           $stationazone = $_POST['stationazone'];
           $zonecodesa_verify = $db->query("SELECT zone_name FROM zone WHERE zone_code='$zonecodea_val[0]' AND comp_code='$stationazone' AND zone_type='credit' AND zone_code NOT LIKE 'B%' AND zone_code NOT LIKE 'C%'");
          echo $zonecodesa_verify->rowCount();
        }
        if(isset($_POST['zonecodeb_val']))
        {
           $zonecodeb_val = explode('-',$_POST['zonecodeb_val']);
           $stationbzone = $_POST['stationbzone'];
          $zonecodesb_verify = $db->query("SELECT zone_name FROM zone WHERE comp_code='$stationbzone' AND zone_type='credit' AND zone_code='$zonecodeb_val[0]' AND zone_code LIKE 'B%'");
          echo $zonecodesb_verify->rowCount();
        }
        if(isset($_POST['zonecodec_val']))
        {
           $zonecodec_val = explode('-',$_POST['zonecodec_val']);
           $stationczone = $_POST['stationczone'];
          $zonecodesc_verify = $db->query(" SELECT zone_name FROM zone WHERE comp_code='$stationczone' AND zone_type='credit' AND zone_code='$zonecodec_val[0]' AND zone_code LIKE 'C%'");
          echo $zonecodesc_verify->rowCount();
        }
        if(isset($_POST['zonecodcash_val']))
        {
           $zonecodecash_val = explode('-',$_POST['zonecodecash_val']);
           $stationcashzone = $_POST['stationcashzone'];
          $zonecodescash_verify = $db->query(" SELECT zone_name FROM zone WHERE comp_code='$stationcashzone' AND zone_type='cash' AND zone_code='$zonecodecash_val[0]'");
          echo $zonecodescash_verify->rowCount();
        }
        if(isset($_POST['zonecodets_val']))
        {
           $zonecodets_val = explode('-',$_POST['zonecodets_val']);
           $stationtszone = $_POST['stationtszone'];
          $zonecodests_verify = $db->query(" SELECT zone_name FROM zone WHERE comp_code='$stationtszone' AND zone_type='ts' AND zone_code='$zonecodets_val[0]' ");
          echo $zonecodests_verify->rowCount();
        }
        //Verify Duplication of Groups
        if(isset($_POST['cate_code']))
        {
          $cate_code = $_POST['cate_code'];
          $station_code = $_POST['station_code'];
          $catecode_val = $db->query("SELECT cate_code FROM groups WHERE cate_code='$cate_code' AND stncode='$station_code'");
          echo $catecode_val->rowCount();
        }
        //Fetch Branchname
        if(isset($_POST['br_code5']))
        {
          $br_code5 = $_POST['br_code5'];
          $fetchbranchname = $db->query("SELECT br_name FROM branch WHERE br_code='$br_code5'");
          $rowbranchcode   = $fetchbranchname->fetch(PDO::FETCH_ASSOC);
          echo $rowbranchcode['br_name'];
        }
         //Verify Branch is Valid
        if(isset($_POST['branch_verify']))
        {
          $stationcode = $_POST['stationcode'];
          $branch_verify = $_POST['branch_verify'];
          $brcode_verify = $db->query("SELECT br_code FROM branch WHERE br_code='$branch_verify' AND stncode='$stationcode'");
          echo $brcode_verify->rowCount();
        }
        //Fetch Exe name and Role
        if(isset($_POST['exename_lookup']))
        {
          $exename_lookup = $_POST['exename_lookup'];
          $fetchexename = $db->query("SELECT exe_name,people_type FROM staff WHERE exe_code='$exename_lookup'");
          $rowexename   = $fetchexename->fetch(PDO::FETCH_ASSOC);
          echo $rowexename['exe_name'].",".$rowexename['people_type'];
        }
        //fetch branch code
        if(isset($_POST['brstn']))
        {
          $brstn = $_POST['brstn'];
          $brname = $_POST['brname'];
          echo branchcode($brstn,$brname,$db);
        }
        if(isset($_POST['execode_lookup'])){
          $execode_lookup = $_POST['execode_lookup'];
          $exestn_lookup = $_POST['exestn_lookup'];
          echo execode($execode_lookup,$exestn_lookup,$db);
        }
        //function brcode
        function branchcode($brstn,$brname,$db){
          $brcode = $db->query("SELECT br_code FROM branch WHERE br_name='$brname' AND stncode='$brstn' AND br_active='Y'")->fetch(PDO::FETCH_OBJ);
          return $brcode->br_code;
        }
        //function execode_lookup
        function execode($execode_lookup,$exestn_lookup,$db){
          $sql = $db->query("SELECT exe_code,people_type FROM staff WHERE exe_name='$execode_lookup' AND comp_code='$exestn_lookup'")->fetch(PDO::FETCH_OBJ);
          return $sql->exe_code.",".$sql->people_type;
        }
        if(isset($_POST['peop_code'])){
          $peop_code = $_POST['peop_code'];
          $peop_stn = $_POST['peop_stn'];
          $fetchpeop = $db->query("SELECT * FROM staff WHERE exe_code='$peop_code' AND comp_code='$peop_stn'");
          echo $fetchpeop->rowCount();
        }
        //delete masters data
        if(isset($_POST['deletetb'])){
          $deletetb = $_POST['deletetb'];
          $deleterow = $_POST['deleterow'];
          $deletecol = $_POST['deletecol'];
          $username = $_POST['delu_user_name'];
          $deletecomp  = $_POST['deletecomp'];
          $delete = $db->query("DELETE FROM $deletetb WHERE $deletecol='$deleterow'");
           //add logs
			   $yourbrowser   = getBrowser()['name'];
			   $user_surename = get_current_user();
			   $platform      = getBrowser()['platform'];   
			   $remote_addr   = getBrowser()['remote_addr'];  
			//   $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_surename','$deleterow has been removed from $deletetb','$yourbrowser')");
            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$deletecomp','$deletetb','Deleted','$deletetb Id - $deleterow has been Deleted in $deletecomp','$username',getdate())");

			   //end
        }
        //load data for pagination in destination table
		if(isset($_POST['dtbquery'])){
      function delete($db,$user){
        $module = $_POST['datatable_module'];
        $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
        $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
        $user_group = $rowusergrp->group_name;
      
        $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
            if($verify_user->rowCount()=='0'){
                return 'swal("You Dont Have Permission to Delete this Record!")';
            }
            else{
                return "deleterecord(this.id)";
            }
        }
        $sessionuser = $_POST['sessionuser'];
        $dtbquery = $_POST['dtbquery'];
        $dtbpageno = $_POST['dtbpageno'];
        $dtbcolumns = explode(",",$_POST['dtbcolumns']);
        $cno = 'cno';
        $t10query = $db->query($dtbquery." OFFSET ".$dtbpageno." ROWS  FETCH NEXT 10 ROWS ONLY");
        while($t10row=$t10query->fetch(PDO::FETCH_OBJ)){
          echo "<tr><td class='center first-child'>
          <label>
            <input type='checkbox' name='chkIds[]' value='1' class='ace' />
              <span class='lbl'></span>
          </label>
          </td>";
          foreach($dtbcolumns as $dtbcolumn){
            echo "<td>".$t10row->$dtbcolumn."</td>";
          }
          if($_POST['datatable_page']=='destinationlist'){
            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
            <a class='btn btn-success btn-minier' href='destinationedit?zone_destid=".$t10row->zone_destid."'>
                  <i class='ace-icon fa fa-pencil bigger-130'></i>
              </a>

                <a  class='btn btn-danger btn-minier bootbox-confirm' id='destination,$t10row->zone_destid,zone_destid'  onclick='".delete($db,$sessionuser)."'>
                    <i class='ace-icon fa fa-trash-o bigger-130'></i>
                </a>
              </div>
            </td></tr>";
          }
          if($_POST['datatable_page']=='customerlist'){
            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
			<a  class='btn btn-primary btn-minier' data-rel='tooltip' title='View' href='customerdetail?cusid=".$t10row->cusid."&cust_code=".$t10row->cust_code."'>
			<i class='ace-icon fa fa-eye bigger-130'></i>
                                </a>

								<a class='btn btn-success btn-minier' href='customeredit?cusid=".$t10row->cusid."&cust_code=".$t10row->cust_code."' data-rel='tooltip' title='Edit'>
								<i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>
                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='customer,$t10row->cusid,cusid' onclick='".delete($db,$sessionuser)."'>
                                    <i class='ace-icon fa fa-trash-o bigger-130'></i>
                                 </a>
                               </div>
                           </td>";
          }
        }
      }
      //check branch category and make editable cust_code
      if(isset($_POST['btypr_br'])){
        echo getbranchcategory($db,$_POST['btypr_br'],$_POST['btype_stncode']);
      }
      function getbranchcategory($db,$branch12,$comp12){
				$getcategory = $db->query("SELECT brcate_code FROM branch WHERE br_code='$branch12' AND stncode='$comp12'")->fetch(PDO::FETCH_OBJ);
				return $getcategory->brcate_code;
			}

        //zone validation for destination
        if(isset($_POST['valueofname'])){
          echo getdestinationdata($db,$_POST['valueofname'],$_POST['dest_zone_check_station']);
        }
        function getdestinationdata($db,$valueofname,$dest_zone_check_station){
          $destinationdata = $db->query("SELECT dest_code,dest_name,zonecode_a,zonecode_b,zonecode_c,zone_cash,zone_ts FROM destination WHERE comp_code='$dest_zone_check_station' AND dest_code='$valueofname'")->fetch(PDO::FETCH_OBJ);
          return json_encode($destinationdata);
        }
        //zone validation for destination

        
      //export to excel
      if(isset($_POST['excel_query'])){
        echo"<style>
        table tr th, table tr td{
            border:.5px solid black;
        }
        </style>";
        header("Content-Type: application/xls");    
        header("Content-Disposition: attachment; filename=cust_tbl.xls");  
        header("Pragma: no-cache"); 
        header("Expires: 0");

        $excel_columns = $_POST['excel_columns'];
        $excel_query = $_POST['excel_query'];
        $exceldata = $db->query($excel_query);
        echo "<table>
        <thead>
        <tr>
        <th>S.no</th>";
        foreach(explode(",",$excel_columns) as $excolumn){
        echo "<th>$excolumn</th>";
        }
        echo "</tr>
       </thead>";
       echo "<tbody>";
       $sno=0;
       while($rowexceldata = $exceldata->fetch(PDO::FETCH_OBJ))
       {$sno++;
         echo "<tr>
           <td>$sno</td>";
          foreach(explode(",",$excel_columns) as $excolumn){
          echo "<td>".$rowexceldata->$excolumn."</td>";
          }
          echo "</tr>";
       }
       echo "</tbody>
        </table>";
      }
      //export to pdf
      use Dompdf\Dompdf;
      if(isset($_POST['pdf_query'])){
        header("Content-Disposition: attachment; filename=customerMaster.pdf");
            ob_start();
        $pdf_columns = $_POST['pdf_columns'];
        $pdf_query = $_POST['pdf_query'];
        $pdfdata = $db->query($pdf_query);
        echo "<style>
        table tr th{
          border:.5px solid black;
        }
        table tr td{
            border-bottom:.5px solid black;
        }
        </style>
        <table>
        <thead>
        <tr>
        <th>S.no</th>";
        foreach(explode(",",$pdf_columns) as $pdfcolumn){
        echo "<th>$pdfcolumn</th>";
        }
        echo "</tr>
       </thead>";
       echo "<tbody>";
       $sno=0;
       while($rowpdfdata = $pdfdata->fetch(PDO::FETCH_OBJ))
       {$sno++;
         echo "<tr>
           <td>$sno</td>";
          foreach(explode(",",$pdf_columns) as $pdfcolumn){
          echo "<td>".$rowpdfdata->$pdfcolumn."</td>";
          }
          echo "</tr>";
       }
       echo "</tbody>
        </table>";
      $dompdf = new Dompdf();
      $htmlcontents = ob_get_clean();
      $dompdf->loadHtml($htmlcontents);
      $dompdf->setPaper('A3', 'landscape');
      $dompdf->render();
      $attachment_file = $dompdf->output();
      $file_to_save = $attachment_file;
      $dompdf->stream();
      header("Content-Type: application/octet-stream");
      header("Content-Length: " . filesize($file_to_save));
      header("Connection: close");
      }

      //check entered range available in group
      if(isset($_POST['grp_val_start'])){
        echo validaterangesofgroup($db,(int)$_POST['grp_val_start'],(int)$_POST['grp_val_end'],$_POST['grp_val_comp_code']);
      }
      function validaterangesofgroup($db,$start,$end,$comp_code){
        $counts = $db->query("SELECT dbo.fn_validateranges_group($start,$end,'$comp_code') as counts")->fetch(PDO::FETCH_OBJ);
        return $counts->counts;
      }

      //GENERATE BOOKING COUNTER CODE
      if(isset($_POST['bookcode_brcode'])){
        echo generatebookingcountercode($db,$_POST['bookcode_brcode'],$_POST['bookcode_stn']);
      }
      function generatebookingcountercode($db,$br_code,$comp_code){
        $book_code=$db->query("SELECT TOP 1 book_code FROM bcounter WHERE stncode='$comp_code' AND br_code='$br_code' ORDER BY CAST(dbo.GetNumericValue(book_code) AS decimal) DESC");
          if($book_code->rowCount()== 0)
            {
            $bookingcode = trim($br_code).str_pad('1', 5, "0", STR_PAD_LEFT);
            }
        else{
            $book_coderow=$book_code->fetch(PDO::FETCH_ASSOC);
            $lastbook_code=$book_coderow['book_code'];
            $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastbook_code);
            $arr[0];//PO
            $bookingcode = trim($br_code).str_pad($arr[1]+1, 5, "0", STR_PAD_LEFT);
            }
         return $bookingcode;
      }
      
      //get file preview data for history contract files 
      if(isset($_POST['history_file_id'])){
        echo previewhistorycontractfile($db,$_POST['history_file_id'],$_POST['hist_file_compcode']);
      }
      function previewhistorycontractfile($db,$history_file_id,$hist_file_compcode){
        $file = $db->query("SELECT file_name,contract_files FROM contract_files WHERE comp_code='$hist_file_compcode' AND id=$history_file_id")->fetch(PDO::FETCH_OBJ);
        $endfilename = explode(".",$file->file_name);
            $ext = end($endfilename);
            if($ext=='pdf'){
              $output = '<embed style="width:100%;min-height:800px" src="data:application/pdf;base64,'. $file->contract_files .'"/>'; 
            }
            else{
              $output = '<embed style="width:100%;" src="data:image/jpg;base64,'. $file->contract_files .'"/>'; 
            }
            return $output;
      }

      //Fetch branch code and name header
      if(isset($_POST['brcodes_head']))
      {
          $brcodes_head = $_POST['brcodes_head'];
          $usr_brhead = $_POST['usr_brhead'];
          $update_superadmin = $db->query("UPDATE users SET br_code = '$brcodes_head' WHERE user_name='$usr_brhead'");
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