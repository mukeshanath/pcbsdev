<?php 
/* File name   : cnote controller.php
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

	 class cnotecontroller extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - fetch';
             // $this->view->render('cnote/fetch');
          }
      }
      error_reporting(0);
      //this function check variable is empty
      function checkempty($data){
        if(empty($data)){  return 'No Data'; } else{ return $data; }
      }
      function emptydate($data)
      {
        if(empty($data)){  return 'No Data'; } else{ return date("d-m-Y", strtotime($data)); }
      }
      //FETCHING DATA FOR SALES FORM
      if(isset($_POST['option']))
      {     
      $cnotenumber="SELECT TOP ( 1 ) cnote_number FROM cnotenumber WHERE cnote_status='Printed' AND cnote_serial='".$_POST["option"]. "' AND cnote_station='".$_POST["station2"]. "' ORDER BY cnote_number";
      $firstnumber=$db->query($cnotenumber);
      $result=$firstnumber->fetch(PDO::FETCH_ASSOC);
        if(!empty($result['cnote_number']))
        {
          echo $result['cnote_number'];
        }
          else
            {
              echo "No cnotes available to allot";
            }
      }

      //FETCHING DATA FOR SALES FORM
      if(isset($_POST['count_starts']))
      {     
      $station3=$_POST['station3'];
      $count_starts=$_POST['count_starts'];
      $count_ends=$_POST['count_starts']+$_POST['count']-1;
      $serial=$_POST['serial'];
      $cust_code=$_POST['cust_code'];
      $salescount = $_POST['count'];

      if($serial=='PRO')
      {
        $charge=$db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$station3'");
        $rowcharge=$charge->fetch(PDO::FETCH_OBJ);
        $cnote_charge = $rowcharge->pro;
      }
      else if($serial=='PRC')
      {
        $charge=$db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$station3'");
        $rowcharge=$charge->fetch(PDO::FETCH_OBJ);
        $cnote_charge = $rowcharge->prc;
      }
      else if($serial=='PRD'){
        $charge=$db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$station3'");
        $rowcharge=$charge->fetch(PDO::FETCH_OBJ);
        $cnote_charge = $rowcharge->prd;
      }
      else{
        $charge=$db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$station3'");
        $rowcharge=$charge->fetch(PDO::FETCH_OBJ);
        $cnote_charge = $rowcharge->cnote_charges;
      }

      $procured=$db->query("SELECT COUNT(*) AS 'no_of_proc' FROM cnotenumber WHERE cnote_serial='$serial' AND cnote_status='Printed' AND cnote_station='$station3'");

      $noofrow=$procured->fetch(PDO::FETCH_OBJ);
      
      if($noofrow->no_of_proc<$salescount){
        $validcountsales = $noofrow->no_of_proc;
      }
      else if($noofrow->no_of_proc>=$salescount){
        $validcountsales = $salescount;
      }
      $amount=$validcountsales * $cnote_charge;
      $transhipment_charge = "0";
      $grand_amount = $amount + $transhipment_charge;
      $gst = ($grand_amount / 100) * 18;
      $net_amt = $gst + $grand_amount;
       echo $validcountsales.",".$cnote_charge.",".$grand_amount.",".$gst.",".$net_amt;
    
    }
    if(isset($_POST['partstation'])){
      $partstation = $_POST['partstation'];
      $partserial  = $_POST['partserial'];
      $partcount  = $_POST['partcount'];
      $partstart  = $_POST['partstart'];
      $i=0;

      $partitions = $db->query("SELECT DISTINCT cnotepono FROM cnotenumber WHERE cnote_serial = '$partserial' AND cnote_station='$partstation' AND cnote_status='Printed' ORDER BY cnotepono");
      while($rowpartpono = $partitions->fetch(PDO::FETCH_OBJ)){
        $i++;

        $partsstart = $db->query("SELECT TOP ( 1 ) cnote_number FROM cnotenumber WHERE cnote_status='Printed' AND cnote_serial='$partserial' AND cnote_station='$partstation' AND cnotepono='$rowpartpono->cnotepono' ORDER BY cnote_number");

        $partsend   = $db->query("SELECT TOP ( 1 ) cnote_number FROM cnotenumber WHERE cnote_status='Printed' AND cnote_serial='$partserial' AND cnote_station='$partstation' AND cnotepono='$rowpartpono->cnotepono' ORDER BY cnote_number DESC");

        $rowpartsstart = $partsstart->fetch(PDO::FETCH_OBJ);
        $rowpartsend = $partsend->fetch(PDO::FETCH_OBJ);
        $parttotal =  ($rowpartsend->cnote_number-$rowpartsstart->cnote_number) +1;//row total

        if($parttotal >= $partcount)
        {
          $rowparttotal = $partcount;
        }
        else if($parttotal <= $partcount)
        {
          $rowparttotal = $parttotal;
        }
        
        $actuelpartend = ($rowpartsstart->cnote_number + $rowparttotal)-1;
        if($partcount>0){
          $valuegetvalid = $db->query("SELECT COUNT(*) AS 'procd' FROM cnotenumber WHERE cnote_serial='$partserial' AND cnote_station='$partstation' AND cnote_status='Printed' AND cnote_number BETWEEN $rowpartsstart->cnote_number AND $actuelpartend")->fetch(PDO::FETCH_OBJ);
        
        echo "<tr>
        
              <td>$i</td>

              <td><input type='text' class='form-control' name='salespono[]' value='$rowpartpono->cnotepono' readonly style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>

              <td><input type='text' class='form-control' name='salesstart[]' value='$rowpartsstart->cnote_number' readonly  style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>

              <td><input type='text' class='form-control' name='salesend[]' value='$actuelpartend' readonly  style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>

              <td><input type='text' class='form-control' name='salescount[]' value='$rowparttotal' readonly  style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>

              <td><input type='text' class='form-control val_proc' name='salesvalid[]' value='$valuegetvalid->procd' readonly  style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>
             </tr>";
        }
        $partcount = $partcount - $valuegetvalid->procd;
      }

    }
      //Calculate valid for Allotment
      if(isset($_POST['v_a_count'])){
          $v_a_serial = $_POST['v_a_serial'];
          $v_a_count = $_POST['v_a_count'];
          $v_a_starts = $_POST['v_a_starts'];
          $v_a_stncode = $_POST['v_a_stncode'];
          $check_allot_c = $db->query("SELECT COUNT(*) AS 'allot_proc' FROM cnotenumber WHERE cnote_serial='$v_a_serial' AND cnote_status='Printed' AND cnote_station='$v_a_stncode'");
          $valallot_c = $check_allot_c->fetch(PDO::FETCH_OBJ);
          if($valallot_c->allot_proc<$v_a_count){
            $valid_a = $valallot_c->allot_proc;
          }
          else{
            $valid_a = $v_a_count;
          }
          echo $valid_a;
      }
      
      if(isset($_POST['purordno']))
      {       
        $poid=$_POST['purordno'];
        $pod="SELECT * FROM cnote WHERE cnotepono='$poid' AND cnote_status='Procured'";
        $podresult=$db->query($pod);
        while($podrow = $podresult->fetch(PDO::FETCH_ASSOC))
        { ?>
          <tr>
          <td class="center">
              <label class="pos-rel">
              <input type="checkbox" class="ace" />
                   <span class="lbl"></span>
              </label>
          </td>
          <td><input type="text" style="border:none;height:15px;color:black;width:50px;" value="<?php echo $podrow['cnoteid']; ?>"></td>
          <td><input type="text" style="border:none;height:15px;color:black;width:100px;" name="cnote_serial[]" value="<?php echo $podrow['cnote_serial']; ?>"></td>
          <td><input type="text" style="border:none;height:15px;color:black;width:100px;" name="cnote_count[]" value="<?php echo $podrow['cnote_count']; ?>"></td>
          <td><input type="text" style="border:none;height:15px;color:black;width:100px;" name="cnote_start[]" value="<?php echo $podrow['cnote_start']; ?>"></td>
          <td><input type="text" style="border:none;height:15px;color:black;width:100px;" name="cnote_end[]" value="<?php echo $podrow['cnote_end']; ?>"></td>
          <td><div class='hidden-sm hidden-xs action-buttons'>

              <a class='btn btn-primary btn-minier' href='form.php?cnotepono="100"'>
              <i class='ace-icon fa fa-print bigger-130'></i>
              </a>
              <a class='btn btn-success btn-minier' href='#'>
              <i class='ace-icon fa fa-pencil bigger-130'></i>
              </a>
              <a  class='btn btn-danger btn-minier bootbox-confirm' >
              <i class='ace-icon fa fa-trash-o bigger-130'></i>
              </a>
              </div></td>
          </tr>
        <?php }
      }

      //Fetching Field Values for Receive Form from Cnotetb
      if(isset($_POST['purno']))
      {       
        $poid = $_POST['purno'];
        $details = $db->query("SELECT TOP 1 * FROM cnote WHERE cnotepono='$poid'");
        $detail = $details->fetch(PDO::FETCH_ASSOC);
        echo $detail['cnote_station'].",".$detail['station_name'].",".$detail['br_code'].",".$detail['br_name'];
      }
 ///fetch cnote serial from cnotenumber
 if(isset($_POST['cnote_from']))
 {  
   $cnote_from = $_POST['cnote_from'];
   $stationcode = $_POST['stationcode'];

    $modearray_genral = array();

   $serials = $db->query("SELECT distinct cnote_serial FROM cnotenumber WITH(NOLOCK) WHERE cnote_number='$cnote_from' and cnote_station='$stationcode'");
   while($rowserials = $serials->fetch(PDO::FETCH_ASSOC))
   {
     
      array_push($modearray_genral,trim($rowserials['cnote_serial']));
     
   }
   array_unshift($modearray_genral , 'Select Serial');

  foreach($modearray_genral as $mode){
   echo '<option  value="'.$mode.'">'.$mode.'</option>';
  }
  

 }
      //Fetch Serial Type For Cancel Form
      if(isset($_POST['cancel_from']))
      {    
        
      $cancel_from = $_POST['cancel_from'];
      $cancelcnote_to  = $_POST['cancel_to'];
      $stationcode  = $_POST['stationcode'];
      $cancel_serial = $_POST['cancel_serial'];
      $cancel_to = ($cancel_from + $_POST['cancel_count'])-1;
      $srl="SELECT distinct cnote_serial FROM cnotenumber WITH(NOLOCK) WHERE cnote_number='$cancel_from' and cnote_station='$stationcode'";
      $serial = $db->query($srl);
      $resultserial=$serial->fetch(PDO::FETCH_ASSOC);
      $serials = $resultserial['cnote_serial'];
      

      $recnote="SELECT cnote_status FROM cnotenumber WITH(NOLOCK) WHERE cnote_number BETWEEN '$cancel_from' AND '$cancel_to' AND cnote_status='Return' and cnote_station='$stationcode' AND cnote_serial='$cancel_serial'";
      $returncnote = $db->query($recnote);
      $rowreturn=$returncnote->fetch(PDO::FETCH_ASSOC);



      $invoicedcnote="SELECT cnote_status FROM cnotenumber WITH(NOLOCK) WHERE cnote_number BETWEEN '$cancel_from' AND '$cancel_to' AND cnote_status='Invoiced' and cnote_station='$stationcode' AND cnote_serial='$cancel_serial'";
      $invcnote = $db->query($invoicedcnote);
      $inv=$invcnote->fetch(PDO::FETCH_ASSOC);

      $singlcnote="SELECT  opstatus FROM cnotenumber WITH(NOLOCK) WHERE cnote_number BETWEEN '$cancel_from' AND '$cancel_to' AND  opstatus='Unbilled' and cnote_station='$stationcode' AND cnote_serial='$cancel_serial'";
      $singlecnote = $db->query($singlcnote);
      $opstatus=$singlecnote->fetch(PDO::FETCH_ASSOC);

      $b_void="SELECT cnote_number FROM cnotenumber WITH(NOLOCK) WHERE cnote_number BETWEEN '$cancel_from' AND '$cancel_to' AND cnote_status IN ('Billed-Void','Allotted-Void','Invoiced','Noservice-return') and cnote_station='$stationcode' AND cnote_serial='$cancel_serial'";
      $voidrow = $db->query($b_void);
      $bilvoid=$voidrow->fetch(PDO::FETCH_ASSOC);

      $b_void1="SELECT top 1 cnote_status FROM cnotenumber WITH(NOLOCK) WHERE cnote_number BETWEEN '$cancel_from' AND '$cancel_to' AND cnote_status IN ('Billed-Void','Allotted-Void','Invoiced','Noservice-return') and cnote_station='$stationcode' AND cnote_serial='$cancel_serial'";
      $voidrow1 = $db->query($b_void1);
      $bilvoid1=$voidrow1->fetch(PDO::FETCH_ASSOC);

      $cccnote = $db->query("SELECT cnote_number  FROM cnotenumber WITH(NOLOCK) WHERE cnote_number BETWEEN '$cancel_from' AND '$cancel_to' AND  cc_status='Invoiced' and cnote_station='$stationcode' AND cnote_serial='$cancel_serial'")->fetch(PDO::FETCH_OBJ);



      $bdate = $db->query("SELECT tdate,br_code FROM cash WITH(NOLOCK) WHERE cno BETWEEN '$cancel_from' AND '$cancel_to' AND  comp_code='$stationcode' AND cnote_serial='$cancel_serial'");
      while($brow = $bdate->fetch(PDO::FETCH_ASSOC))
      {
        $bildate = $brow['tdate'];
        $br_code = $brow['br_code'];
        $demdate = $db->query("SELECT count(col_date) as date FROM denomination WHERE comp_code='$stationcode' AND br_code='$br_code' AND col_date='$bildate'");
        while($demrow = $demdate->fetch(PDO::FETCH_ASSOC))
        {
          $demarray[] = $demrow['date'];

        }
      }
     
      
     

      echo json_encode(array($cancel_to,$cancel_serial,count($inv['cnote_status']),count($opstatus['opstatus']),$demarray,count($bilvoid['cnote_number']),count($cccnote->cnote_number),count($rowreturn['cnote_status']),$cnote_serials,$bilvoid1['cnote_status']));
      exit();
      }

      //Fetch Serial Type For Cancel Form
      if(isset($_POST['single_cnote']))
      {     
      $single_cnote = $_POST['single_cnote'];
      $stationcode1 = $_POST['stationcode'];
      $singlcnote = $db->query("SELECT cnote_serial FROM cnotenumber WHERE cnote_number='$single_cnote' and cnote_station='$stationcode1'");
      $row = $singlcnote->fetch(PDO::FETCH_ASSOC);    
      echo  $row['cnote_serial'];
      }

//function for cnote cancel
 //Fetch Serial Type For Cancel Form
 if(isset($_POST['single_cnote1']))
 {     
 $single_cnote = $_POST['single_cnote1'];
 $stationcode1 = $_POST['stationcode'];
 $single_serial = $_POST['single_serial'];

 $cccnote = $db->query("SELECT br_code,tdate  FROM cash WITH(NOLOCK) WHERE cno ='$single_cnote' AND  comp_code ='$stationcode1' AND cnote_serial='$single_serial'")->fetch(PDO::FETCH_OBJ);
 $br_code = $cccnote->br_code;
 $tdate   = $cccnote->tdate;

 $get_status = $db->query("SELECT cnote_status FROM cnotenumber WHERE cnote_number='$single_cnote' and cnote_station='$stationcode1' AND cnote_serial='$single_serial'")->fetch(PDO::FETCH_OBJ);
 

 $singlcnote="SELECT cnote_serial FROM cnotenumber WHERE cnote_number='$single_cnote' and cnote_station='$stationcode1' AND cnote_serial='$single_serial'";
 $singlecnote = $db->query($singlcnote);
 $single12=$singlecnote->fetch(PDO::FETCH_ASSOC);

 $invoicedcnote="SELECT cnote_status FROM cnotenumber WHERE cnote_number='$single_cnote' AND cnote_status='Invoiced' and cnote_station='$stationcode1' AND cnote_serial='$single_serial'";
 $invcnote = $db->query($invoicedcnote);
 $inv=$invcnote->fetch(PDO::FETCH_ASSOC);

 $void="SELECT cnote_status FROM cnotenumber WHERE cnote_number='$single_cnote' AND cnote_status='Billed-void' and cnote_station='$stationcode1' AND cnote_serial='$single_serial'";
 $billedvoid = $db->query($void);
 $bilvoid=$billedvoid->fetch(PDO::FETCH_ASSOC);

 $allotedvoid="SELECT cnote_status FROM cnotenumber WHERE cnote_number='$single_cnote' AND cnote_status='Allotted-void' and cnote_station='$stationcode1' AND cnote_serial='$single_serial'";
 $allvoid = $db->query($allotedvoid);
 $al_void=$allvoid->fetch(PDO::FETCH_ASSOC);

 $voidreturn="SELECT cnote_status FROM cnotenumber WHERE cnote_number='$single_cnote' AND cnote_status='Return' and cnote_station='$stationcode1' AND cnote_serial='$single_serial'";
 $return = $db->query($voidreturn);
 $rowreturn=$return->fetch(PDO::FETCH_ASSOC);

 $denomination="SELECT count(col_date) as date FROM denomination WHERE comp_code='$stationcode1' AND br_code='$br_code' AND col_date='$tdate'";
 $denom_cnote = $db->query($denomination);
 $rowdenom=$denom_cnote->fetch(PDO::FETCH_ASSOC);

 $singlcnote="SELECT  opstatus FROM cnotenumber WHERE cnote_number='$single_cnote' and cnote_station='$stationcode1' AND cnote_serial='$single_serial'";
 $singlecnote = $db->query($singlcnote);
 $opstatus=$singlecnote->fetch(PDO::FETCH_ASSOC);
// echo json_encode(array($single12['cnote_serial'],count($single12['opstatus'])));
 echo json_encode(array($single_serial,count($inv['cnote_status']),count($opstatus['opstatus']),count($bilvoid['cnote_status']),$single_cnote,count($rowreturn['cnote_status']),$rowdenom['date'],$get_status->cnote_status));
 exit();
 }       
       //Fetch Start count for Procurement
      if(isset($_POST['stncode2']))
      {
        $serial_type = $_POST['serial_type'];
        $stncode2 = $_POST['stncode2'];
        $start = $db->query("SELECT TOP 1 cnote_end FROM cnote WHERE cnote_serial='$serial_type' AND cnote_station='$stncode2' ORDER BY cnoteid DESC");
        $row = $start->fetch(PDO::FETCH_ASSOC);

        $rangestart = $db->query("SELECT range_starts,grp_type FROM groups WHERE cate_code='$serial_type' AND stncode='$stncode2'");
        $range = $rangestart->fetch(PDO::FETCH_ASSOC);
        if ($start->rowCount()== 0)
        {
          $result = $range['range_starts'];
        }
        else 
        {
          $result = $row['cnote_end'] + 1;
        }
        echo $result.",".$range['grp_type'];
        //echo '100'.",".'150';
      }
      //Fetch Invalid Cnotes in Sales
      if(isset($_POST['count_inv']))
      {
        $count_inv = $_POST['count_inv'];
        $start_inv = $_POST['start_inv'];
        $invalidstncode = $_POST['invalidstncode'];
        $end_inv   = $count_inv + $start_inv;
        $inv = $db->query("SELECT * FROM cnotenumber WHERE cnote_status LIKE '%Void' AND cnote_station='$invalidstncode' AND cnote_number BETWEEN $start_inv AND $end_inv");
        while($inv_row = $inv->fetch(PDO::FETCH_ASSOC))
        {?>
          <tr>
          <td><?php echo $inv_row['cnotenoid']; ?></td>
          <td><?php echo $inv_row['cust_name']; ?></td>
          <td><?php echo $inv_row['cnote_serial']; ?></td>
          <td><?php echo $inv_row['cnote_number']; ?></td>
          <td><?php echo $inv_row['cnote_status']; ?></td>
          </tr>
        <?php }
       
       } 

      //Fetch Cnote End for Procurement
      if(isset($_POST['cnote_count']))
      {
        $cnote_start = $_POST['cnote_start'];
        echo $result = $cnote_start + $_POST['cnote_count'] - 1;
      }
      //Fetch Vendor Name  on Procurement 
      if(isset($_POST['vendor_code']))
      {
        $vendor_code = $_POST['vendor_code'];
        $stncode_ven = $_POST['stncode_ven'];
        $vendor_name = $db->query("SELECT vendor_name FROM vendor WHERE vendor_code='$vendor_code' AND stncode='$stncode_ven'");
        $vendor_row = $vendor_name->fetch(PDO::FETCH_ASSOC);
        echo $vendor_row['vendor_name'].",".$vendor_name->rowCount();
      }
      //Fetch Branch Name
      if(isset($_POST['branchlookup']))
      {
        $branchlookup = $_POST['branchlookup'];
        $get_branch = $db->query("SELECT br_name FROM branch WHERE br_code='$branchlookup' AND br_active='Y'");
        $branch_row = $get_branch->fetch(PDO::FETCH_ASSOC);
        echo $branch_row['br_name'];
      }
      //Fetch Customer Name
      if(isset($_POST['customerlookup']))
      {
        $customerlookup = $_POST['customerlookup'];
        $get_customer = $db->query("SELECT cust_name FROM customer WHERE cust_code='$customerlookup' AND cust_active='Y'");
        $customer_row = $get_customer->fetch(PDO::FETCH_ASSOC);
        echo $customer_row['cust_name'];
      }
      //Verify Branch is Valid
      if(isset($_POST['branch_verify']))
      {
        $stationcode = $_POST['stationcode'];
        $branch_verify = $_POST['branch_verify'];
        $brcode_verify = $db->query("SELECT br_code FROM branch WHERE br_code='$branch_verify' AND stncode='$stationcode' AND br_active='Y' AND cate_code NOT LIKE 'DC'");
        echo $brcode_verify->rowCount();
      }
      //Verify DC Branch is Valid
      if(isset($_POST['branch_verifydc']))
      {
        $stationcodedc = $_POST['stationcodedc'];
        $branch_verifydc = $_POST['branch_verifydc'];
        $brcode_verifydc = $db->query("SELECT br_code FROM branch WHERE br_code='$branch_verifydc' AND stncode='$stationcodedc' AND branchtype='D'");
        echo $brcode_verifydc->rowCount();
      }
      //Verify Customer is Valid
      if(isset($_POST['cust_verify_br']))
      {
        $cust_verify_br = $_POST['cust_verify_br'];
        $cust_verify_cust = $_POST['cust_verify_cust'];
        $custcode_verify = $db->query("SELECT cust_code FROM customer WHERE br_code='$cust_verify_br' AND cust_code='$cust_verify_cust'");
        echo $custcode_verify->rowCount();
      }
      
      // auto populate customer based on branch
      if(isset($_POST['branch8']))
      {
        $branch8  = $_POST['branch8'];
        $comp8    = $_POST['comp8'];
        $getautoppl8 = $db->query("SELECT cc_code FROM collectioncenter WHERE br_code='$branch8'");
        while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
        {
          $arrayvalues1[] = $getautoppl8row['cc_code'];
        }
        $getautoppl8 = $db->query("SELECT cust_code FROM customer WHERE comp_code='$comp8' AND br_code='$branch8' AND cust_active='Y'");
        while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
        {
          $arrayvalues[] = $getautoppl8row['cust_code'];
        }
        // echo json_encode($arrayvalues);
        echo json_encode(array($arrayvalues,$arrayvalues1));
      }
	  //get autocompleate  value in cc_code
      if(isset($_POST['branch10']))
      {
        $branch10  = $_POST['branch10'];
        $comp8    = $_POST['comp8'];
        $getautoppl8 = $db->query("SELECT cc_code FROM collectioncenter WHERE br_code='$branch10'");
        while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
        {
          $arrayvalues1[] = $getautoppl8row['cc_code'];
        }
        $getautoppl8 = $db->query("SELECT cust_code FROM customer WHERE comp_code='$comp8' AND br_code='$branch10' AND cust_active='Y'");
  while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
  {
    $arrayvalues[] = $getautoppl8row['cust_code'];
  }
  // echo json_encode($arrayvalues);
  echo json_encode(array($arrayvalues1,$arrayvalues));
}

if(isset($_POST['action']) && $_POST['action'] == 'missbranch'){
  $company = $_POST['company'];
  $branch = $_POST['branch'];
  $getautoppl8 = $db->query("SELECT cc_code FROM collectioncenter WHERE br_code='$branch'");
  while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
  {
    $arrayvalues1[] = $getautoppl8row['cc_code'];
  }
  $getautoppl8 = $db->query("SELECT cust_code FROM customer WHERE comp_code='$company' AND br_code='$branch' AND cust_active='Y'");
  while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
  {
    $arrayvalues[] = $getautoppl8row['cust_code'];
  }
  // echo json_encode($arrayvalues);
  echo json_encode(array($arrayvalues1,$arrayvalues));
}
      
      // auto populate customer name based on branch
      if(isset($_POST['c_name_br']))
      {
        $c_name_br  = $_POST['c_name_br'];
        $c_name_comp    = $_POST['c_name_comp'];
        $getautonamel8 = $db->query("SELECT cust_name FROM customer WHERE comp_code='$c_name_comp' AND br_code='$c_name_br' AND cust_active='Y'");
        while($getautoppnamerow = $getautonamel8->fetch(PDO::FETCH_ASSOC))
        {
          $arrayc_name[] = $getautoppnamerow['cust_name'];
        }
        echo json_encode($arrayc_name);
      }
      //fetch branch code
      if(isset($_POST['s_bname'])){
        $s_bname = $_POST['s_bname'];
        $s_comp = $_POST['s_comp'];
        echo branchname($s_bname,$s_comp,$db);
        }
                  //fetch customer code
       if(isset($_POST['c_name'])){
        $c_name = $_POST['c_name'];
        $s_c_comp = $_POST['s_c_comp'];
       echo custname($c_name,$s_c_comp,$db);
      }
       //fetch vendor code for
       if(isset($_POST['vend_name'])){
        $vend_name = $_POST['vend_name'];
        $vend_stn = $_POST['vend_stn'];
        echo fetchvendorcode($vend_name,$vend_stn,$db);
      }
     
        //fetch BRANCH CODE FUNCTION
        function branchname($s_bname,$s_comp,$db){
          $brname = $db->query("SELECT br_code FROM branch WHERE br_name='$s_bname' AND stncode='$s_comp' AND br_active='Y'")->fetch(PDO::FETCH_OBJ);
          return $brname->br_code;
          }
        //fetch CUSTOMER CODE FUNCTION
        function custname($c_name,$s_c_comp,$db){
          $custname = $db->query("SELECT cust_code FROM customer WHERE cust_name='$c_name' AND comp_code='$s_c_comp' AND cust_active='Y'")->fetch(PDO::FETCH_OBJ);
          return $custname->cust_code;
          }
          //vendor code
        function fetchvendorcode($vend_name,$vend_stn,$db){
          $sql = $db->query("SELECT vendor_code FROM vendor WHERE vendor_name='$vend_name' AND stncode='$vend_stn'")->fetch(PDO::FETCH_OBJ);
          return $sql->vendor_code;
        }
     
        //data for cnote inventory
        if(isset($_POST['po_inventory'])){
          echo po_inventory($_POST['po_inventory'],$_POST['stn_inventory'],$db);
        }
         function po_inventory($po_inventory,$stn_inventory,$db){
          //return 'test';
          $cnote = $db->query("SELECT * FROM cnote WHERE cnotepono='$po_inventory' AND cnote_station='$stn_inventory'")->fetch(PDO::FETCH_OBJ);
          $serial = $cnote->cnote_serial;
          $count  = $cnote->cnote_count;
          $cnote_start = $cnote->cnote_start;
          $cnote_end = $cnote->cnote_end;
          //procured counts
          $Procured = $db->query("SELECT COUNT(*) AS Procured FROM cnotenumber WHERE cnotepono='$po_inventory' AND cnote_station='$stn_inventory' AND cnote_status='Procured'")->fetch(PDO::FETCH_OBJ);
          $Received = $db->query("SELECT COUNT(*) AS Received FROM cnotenumber WHERE cnotepono='$po_inventory' AND cnote_station='$stn_inventory' AND cnote_status!='Procured'")->fetch(PDO::FETCH_OBJ);
          $Allotted = $db->query("SELECT COUNT(*) AS Allotted FROM cnotenumber WHERE cnotepono='$po_inventory' AND cnote_station='$stn_inventory' AND cnote_status!='Procured' AND cnote_status!='Printed'")->fetch(PDO::FETCH_OBJ);
          $Billed   = $db->query("SELECT COUNT(*) AS Billed   FROM cnotenumber WHERE cnotepono='$po_inventory' AND cnote_station='$stn_inventory' AND cnote_status!='Procured' AND cnote_status!='Printed' AND cnote_status!='Allotted'")->fetch(PDO::FETCH_OBJ);
          $Invoiced = $db->query("SELECT COUNT(*) AS Invoiced FROM cnotenumber WHERE cnotepono='$po_inventory' AND cnote_station='$stn_inventory' AND cnote_status='Invoiced'")->fetch(PDO::FETCH_OBJ);
          //return values
          return $serial.",".$count.",".$cnote_start.",".$cnote_end.",".$Procured->Procured.",".$Received->Received.",".$Allotted->Allotted.",".$Billed->Billed.",".$Invoiced->Invoiced;
         }
        //data for external cnote inventory
        if(isset($_POST['extpo_inventory'])){
          echo extpo_inventory($_POST['extpo_inventory'],$_POST['extstn_inventory'],$_POST['extcust_inventory'],$db);
        }
         function extpo_inventory($extpo_inventory,$extstn_inventory,$extcust_inventory,$db){
          //return 'test';
          $cnote = $db->query("SELECT * FROM cnotesales WHERE salesorderid='$extpo_inventory' AND stncode='$extstn_inventory' AND cust_code='$extcust_inventory'")->fetch(PDO::FETCH_OBJ);
          $serial = $cnote->cnote_serial;
          $count  = $cnote->cnote_count;
          $cnote_start = $cnote->cnote_start;
          $cnote_end = $cnote->cnote_end;
          //procured counts
          $Procured = $db->query("SELECT COUNT(*) AS Procured FROM cnotenumber WHERE allot_po_no='$extpo_inventory' AND cnote_station='$extstn_inventory' AND cnote_status='Procured'")->fetch(PDO::FETCH_OBJ);
          $Received = $db->query("SELECT COUNT(*) AS Received FROM cnotenumber WHERE allot_po_no='$extpo_inventory' AND cnote_station='$extstn_inventory' AND cnote_status!='Procured'")->fetch(PDO::FETCH_OBJ);
          $Allotted = $db->query("SELECT COUNT(*) AS Allotted FROM cnotenumber WHERE allot_po_no='$extpo_inventory' AND cnote_station='$extstn_inventory' AND cnote_status!='Procured' AND cnote_status!='Printed'")->fetch(PDO::FETCH_OBJ);
          $Billed   = $db->query("SELECT COUNT(*) AS Billed   FROM cnotenumber WHERE allot_po_no='$extpo_inventory' AND cnote_station='$extstn_inventory' AND cnote_status!='Procured' AND cnote_status!='Printed' AND cnote_status!='Allotted'")->fetch(PDO::FETCH_OBJ);
          $Invoiced = $db->query("SELECT COUNT(*) AS Invoiced FROM cnotenumber WHERE allot_po_no='$extpo_inventory' AND cnote_station='$extstn_inventory' AND cnote_status='Invoiced'")->fetch(PDO::FETCH_OBJ);
          //return values
          return $serial.",".$count.",".$cnote_start.",".$cnote_end.",".$Procured->Procured.",".$Received->Received.",".$Allotted->Allotted.",".$Billed->Billed.",".$Invoiced->Invoiced;
         }
         //fetch data for cnote tracking
         if(isset($_POST['cno_tracking'])){
           $cno_tracking = $_POST['cno_tracking'];
           $cnoend_tracking = $_POST['cnoend_tracking'];
           $stn_tracking = $_POST['stn_tracking'];
           $tracking_type = $_POST['tracking_type'];
           $trk_bdate_to = $_POST['trk_bdate_to'];
           $trk_bdate_frm = $_POST['trk_bdate_frm'];
           $trk_destcode = $_POST['trk_destcode'];
           $trk_custcode = $_POST['trk_custcode'];
           //check condition for single or bulk
           if($cnoend_tracking=='1'){
              $track_numberfilter =  "cnote_number='$cno_tracking' AND ";
              $trotb_numberfilter =  "cno='$cno_tracking'";
           }
           else{
             if(!empty($cno_tracking) && !empty($cnoend_tracking)){
              $track_numberfilter =  "cnote_number BETWEEN '$cno_tracking' AND '$cnoend_tracking' AND";
              $trotb_numberfilter =  "cno BETWEEN '$cno_tracking' AND '$cnoend_tracking'";
             }
             else{
              $track_numberfilter =  "";
              $trotb_numberfilter =  "";
             }
           }
           //dynamic filters
           $track_conditions = array();
           if(!empty($trk_custcode)){
             $track_conditions[] = "cust_code='$trk_custcode'";
           }
           if(!empty($trk_bdate_frm)){
             $track_conditions[] = "bdate BETWEEN '$trk_bdate_frm' AND '$trk_bdate_to'";
           }
           if(!empty($trk_destcode)){
             $track_conditions[] = "bildest = '$trk_destcode'";
           }
           $track_condition = implode(' AND ', $track_conditions);
           if(count($track_conditions)>=1){
              $trk_condition = "AND $track_condition";
           }
           else{
             $trk_condition = "";
           }
           //dynamic filters end
           //if the type is inbound
           if($tracking_type=='inbound'){
           echo "<table>
                 <tr>
                 <th>S.no</th>
                 <th>Cnote No</th>
                 <th>Branch</th>
                 <th>Customer code</th>
                 <th>Cnote Serial</th>
                 <th>Procured date</th>
                 <th>Allotted date</th>
                 <th>Billed date</th>
                 <th>Origin</th>
                 <th>Destn</th>
                 <th>Ch wt</th>
                 <th>pcs</th>
                 <th>mode</th>
                 <th>rate</th>
                 <th>Inv no</th>
                 <th>Inv date</th>
                 <th>Status</th>
                 </tr>";
            $trackcnotes = $db->query("SELECT * FROM cnotenumber WHERE $track_numberfilter  cnote_station='$stn_tracking' $trk_condition");$sno=0;
            while($trcnorow = $trackcnotes->fetch(PDO::FETCH_OBJ)){$sno++;
            // $allottrkdate    = $db->query("SELECT date_added FROM cnotereceivedetail WHERE cnote_start<='$trcnorow->cnote_number' AND cnote_end>='$trcnorow->cnote_number' AND cnote_station='$stn_tracking'")->fetch(PDO::FETCH_OBJ);//get receive date
            $trackcheckcredit = $db->query("SELECT * FROM credit WHERE cno='$trcnorow->cnote_number' AND comp_code='$stn_tracking' AND cnote_serial='$trcnorow->cnote_serial'");
            if($trackcheckcredit->rowCount()==0){
              $trackcrequery = $db->query("SELECT *,ramt as amt FROM cash WHERE cno='$trcnorow->cnote_number' AND comp_code='$stn_tracking' AND cnote_serial='$trcnorow->cnote_serial'");
            }
            else{
              $trackcrequery = $trackcheckcredit; 
            }
            $trackfrmcredit = $trackcrequery->fetch(PDO::FETCH_OBJ);//get credit data
            $ch_weight = max($trackfrmcredit->wt,$trackfrmcredit->v_wt);
            $trackfrminvoice= $db->query("SELECT invdate FROM invoice WHERE invno='$trackfrmcredit->invno' AND comp_code='$stn_tracking'")->fetch(PDO::FETCH_OBJ);//get inv date
            echo "<tr>
                  <td>".checkempty($sno)."</td>
                  <td>".checkempty($trcnorow->cnote_number)."</td>
                  <td>".checkempty($trcnorow->br_code)."</td>
                  <td>".checkempty($trcnorow->cust_code)."</td>
                  <td>".checkempty($trcnorow->cnote_serial)."</td>
                  <td>".emptydate($trcnorow->date_added)."</td>
                  <td>".emptydate($trcnorow->allot_date)."</td>
                  <td>".(($trackfrmcredit->tdate=='') ? 'No Data' : date("d-M-Y",strtotime($trackfrmcredit->tdate)))."</td>
                  <td>".checkempty($trackfrmcredit->origin)."</td>
                  <td>".checkempty($trackfrmcredit->dest_code)."</td>
                  <td>".checkempty(number_format($ch_weight,3))."</td>
                  <td>".checkempty($trackfrmcredit->pcs)."</td>
                  <td>".checkempty($trackfrmcredit->mode_code)."</td>
                  <td>".checkempty(number_format($trackfrmcredit->amt,2))."</td>
                  <td>".checkempty($trackfrmcredit->invno)."</td>
                  <td>".emptydate($trackfrminvoice->invdate)."</td>
                  <td>".checkempty($trcnorow->cnote_status)."</td>
                </tr>";
           }
           echo "</table>";
          }
          //end inbound
          //outbound
           if($tracking_type=='outbound'){
           echo "<table>
                 <tr>
                 <th>S.no</th>
                 <th>Cnote No</th>
                 <th>Origin</th>
                 <th>Destn</th>
                 <th>wt</th>
                 <th>mode</th>
                 <th>origin</th>
                 </tr>";
            $trackoutcnotes   = $db->query("SELECT * FROM opdata WHERE $trotb_numberfilter AND comp_code='$stn_tracking'");$sno=0;

       
            while($troutcnorow = $trackoutcnotes->fetch(PDO::FETCH_OBJ)){$sno++;
                echo "<tr>
                  <td>$sno</td>
                  <td>".checkempty($troutcnorow->cno)."</td>
                  <td>".checkempty($troutcnorow->origin)."</td>
                  <td>".checkempty($troutcnorow->destn)."</td>
                  <td>".checkempty($troutcnorow->wt)."</td>
                  <td>".checkempty($troutcnorow->mode_code)."</td>
                  <td>".checkempty($troutcnorow->origin)."</td>
                </tr>";
           }
           echo "</table>";
          }
          //end inbound
         }
//delete cc allotment list
if(isset($_POST['del_salesno_cc'])){
     deletecnoteslesallocation($db,$_POST['del_salesno_cc'],$_POST['del_salesstn_cc'],$_POST['del_sales_user_cc']);
   }
 
   function deletecnoteslesallocation($db,$del_salesno_cc,$del_salesstn_cc,$del_sales_user_cc){
 
    $db->query("UPDATE cnoteallocationcc SET flag='0' WHERE salesorderid='$del_salesno_cc' AND stncode='$del_salesstn_cc'");
    $db->query("UPDATE cnotenumber SET cnote_status='Allotted',cust_code=null,cust_name=null WHERE allot_cc_no='$del_salesno_cc' AND cnote_station='$del_salesstn_cc'");
   //  prepare for log
    $yourbrowser   = getBrowser()['name'];
    $user_surename = get_current_user();
    $platform      = getBrowser()['platform'];   
    $remote_addr   = getBrowser()['remote_addr']; 
    $date_added    = date('Y-m-d H:i:s');
    $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$del_salesno_cc','Sales','Deleted','Salesorderid $del_salesno_cc has been Deleted in Station $del_salesstn_cc','$del_sales_user_cc',getdate())");
 
   // $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$del_sales_user_cc','CnoteSales Id $del_salesno_cc has been Deleted','$yourbrowser')"); 
   }
 
         //delete cnote sales
         if(isset($_POST['del_salesno'])){
           deletecnotesles($db,$_POST['del_salesno'],$_POST['del_salesstn'],$_POST['del_sales_user']);
         }

         function deletecnotesles($db,$del_salesno,$del_salesstn,$del_sales_user){

          $db->query("UPDATE cnotesales SET flag='0' WHERE salesorderid='$del_salesno' AND stncode='$del_salesstn'");
          $db->query("UPDATE cnotenumber SET cnote_status='Printed',br_code=null,br_name=null,cust_code=null,cust_name=null,allot_date=null,allot_po_no=null WHERE allot_po_no='$del_salesno' AND cnote_station='$del_salesstn'");
          //prepare for log
          $yourbrowser   = getBrowser()['name'];
          $user_surename = get_current_user();
          $platform      = getBrowser()['platform'];   
          $remote_addr   = getBrowser()['remote_addr']; 
          $date_added    = date('Y-m-d H:i:s');
          $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$del_salesno','Sales','Deleted','Salesorderid $del_salesno has been Deleted in Station $del_salesstn','$del_sales_user',getdate())");

         // $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$del_sales_user','CnoteSales Id $del_salesno has been Deleted','$yourbrowser')"); 
         }

         //delete cnote allottment
         if(isset($_POST['del_allotno'])){
           deletecnoteallottment($db,$_POST['del_allotno'],$_POST['del_allotstn'],$_POST['del_allot_user']);
         }

         function deletecnoteallottment($db,$del_allotno,$del_allotstn,$del_allot_user){

            $db->query("UPDATE cnoteallot SET flag='0' WHERE allotorderid='$del_allotno' AND stncode='$del_allotstn'");
            $db->query("UPDATE cnotenumber SET cnote_status='Printed',br_code=null,br_name=null,cust_code=null,cust_name=null,allot_date=null,allot_po_no=null WHERE allot_po_no='$del_allotno' AND cnote_station='$del_allotstn'");
            //prepare for log
            $yourbrowser   = getBrowser()['name'];
            $user_surename = get_current_user();
            $platform      = getBrowser()['platform'];   
            $remote_addr   = getBrowser()['remote_addr']; 
            $date_added    = date('Y-m-d H:i:s');
            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$del_allotno','Allotment','Deleted','Allotorderid $del_allotno has been Deleted in Station $del_allotstn','$del_allot_user',getdate())");

          //  $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$del_allot_user','CnoteSales Id $del_allotno has been Deleted','$yourbrowser')"); 
          }

           //delete cnote cc allottment
           if(isset($_POST['del_ccsalesno'])){
           
            deletecnoteccallottment($db,$_POST['del_ccsalesno'],$_POST['del_ccsalesstn'],$_POST['del_ccsales_user']);
          }
          function deletecnoteccallottment($db,$del_ccsalesno,$del_ccsalesstn,$del_ccsales_user){

            $db->query("UPDATE cnoteallocationcc SET flag='0' WHERE salesorderid='$del_ccsalesno' AND stncode='$del_ccsalesstn'");
            $db->query("UPDATE cnotenumber SET cust_code=null,cust_name=null WHERE allot_cc_no='$del_ccsalesno' AND cnote_station='$del_ccsalesstn'");
            //prepare for log
            $yourbrowser   = getBrowser()['name'];
            $user_surename = get_current_user();
            $platform      = getBrowser()['platform'];   
            $remote_addr   = getBrowser()['remote_addr']; 
            $date_added    = date('Y-m-d H:i:s');
            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$del_ccsalesno','CCAllotment','Deleted','CCallotid $del_ccsalesno has been Deleted in Station $del_ccsalesstn','$del_ccsales_user',getdate())");
                                                                                                                                                                                                                                                            
          //  $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$del_ccsales_user','CnoteSales Id $del_ccsalesno has been Deleted','$yourbrowser')"); 
          }

          //validate procurement cnote ranges for valid start end from group and duplicate available
          if(isset($_POST['proc_start'])){
            echo validateProcurmentRanges($db
            ,$_POST['proc_start']
            ,$_POST['proc_end']
            ,$_POST['proc_comp_code']
            ,$_POST['proc_serial']
            ,$_POST['proc_type']
            );
          }
          function validateProcurmentRanges($db,$start,$end,$comp_code,$serial,$type){
            $getserial = $db->query("SELECT grp_type FROM groups WHERE stncode='$comp_code' AND cate_code='$serial'")->fetch(PDO::FETCH_OBJ);
            if($getserial->grp_type=='gl'){
              $validategroup = $db->query("SELECT count(*) as result FROM groups WHERE stncode='$comp_code' AND cate_code='$serial' AND (cast(range_starts as bigint)<=$start and cast(range_ends as bigint)>=$start) AND (cast(range_starts as bigint)<=$end AND cast(range_ends as bigint)>=$end)")->fetch(PDO::FETCH_OBJ);
            }
            else if($getserial->grp_type=='sp'){
              $validategroup = $db->query("SELECT count(*) as result FROM groups WHERE stncode='$comp_code' AND cate_code='$serial'")->fetch(PDO::FETCH_OBJ);
            }
            //return $validategroup->result;
            if($validategroup->result==1){
              $duplicate_validation = $db->query("SELECT count(*) as result FROM cnotenumber WHERE cnote_station='$comp_code' AND (SELECT grp_type FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)='$type' AND cast(cnote_number as bigint) BETWEEN $start AND $end")->fetch(PDO::FETCH_OBJ);
              if($duplicate_validation->result>0){
                return 0;
              }
              else{
                return 1;
              } 
            }
            else{
              return 0;
            }
            //0-invalid
            //1-valid
          }
          //validate procurement cnote ranges for valid start end from group and duplicate available
              //logs function
              if(isset($_POST['cheack_cnote']))
              {     
              $single_cnote = $_POST['cheack_cnote'];
              $compcode1 = $_POST['stationcode2'];
              $singlcnote="SELECT  opstatus,cnote_status FROM cnotenumber WHERE cnote_number=$single_cnote and cnote_station='$compcode1'";
              $singlecnote = $db->query($singlcnote);
              $single12=$singlecnote->fetch(PDO::FETCH_ASSOC);
        
              $getinvoiced="SELECT cnote_status FROM cnotenumber WHERE cnote_number=$single_cnote and cnote_status='Invoiced' and cnote_station='$compcode1'";
              $invoiced_cnote = $db->query($getinvoiced);
              $invoicedcnote=$invoiced_cnote->fetch(PDO::FETCH_ASSOC);
              echo json_encode(array(count($single12['opstatus']),count($invoicedcnote['cnote_status'])));
              }
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



    if(isset($_POST["employee_id"]))  
    {  
         $output = '';  
         $viewdetails = $db->query("SELECT * FROM cnotesalesdetail WHERE salesorderid='".$_POST["employee_id"]."'");
         
         $counter=0;
         $output .= '  
               <h3 style="text-align:center;">Details</h3>
         <div class="table-responsive">  
              <table class="table table-bordered">
              <tr>
              <th>S.No</th>
              <th>Sales Order Id</th>
              <th>cnote_start</th>
              <th>cnote_end</th>
              </tr>';
         
              while($saledatarow = $viewdetails->fetch(PDO::FETCH_OBJ)){  
                $counter=$counter+1;
                $output .= '  
                   <tr>  
                        <td width="20%">'.$counter.'</td>  
                        <td width="70%">'.$saledatarow->salesorderid.'</td>  
                        <td width="70%">'.$saledatarow->cnote_start.'</td>  
                        <td width="70%">'.$saledatarow->cnote_end.'</td>  
                        </tr>
                       
                '; 
              }  
         $output .= '  
              </table>  
         </div>  
         ';  
         echo $output;  
    }  



    //cnote details

    if(isset($_POST["cnote_detail"]))  
    {  
         $output = '';  
        
          $viewdetails =  $viewdetails = $db->query("SELECT * FROM cnoteallotdetail WHERE allotorderid='".$_POST["cnote_detail"]."'");
         
    
      $counter=0;
         $output .= '  
               <h3 style="text-align:center;">Details</h3>
         <div class="table-responsive">  
              <table class="table table-bordered">
              <tr>
              <th>S.No</th>
              <th>Allot Order Id</th>
              <th>cnote_start</th>
              <th>cnote_end</th>
              </tr>';
         
              while($cnotedetail = $viewdetails->fetch(PDO::FETCH_OBJ)){ 
                $counter=$counter+1;
            $output .= '  
            
                   <tr>  
                        <td width="20%">'.$counter.'</td>  
                        <td width="70%">'.$cnotedetail->allotorderid.'</td>  
                        <td width="70%">'.$cnotedetail->cnote_start.'</td>  
                        <td width="70%">'.$cnotedetail->cnote_end.'</td>  
                        </tr>
                       
              '; 
         }  
         $output .= '  
              </table>  
         </div>  
         ';  
         echo $output;  
    }  
    //get valid counts for cc allotment
    if(isset($_POST['ccstation3'])){
      echo getstartnumber($db
      ,$_POST['ccstation3']
      ,$_POST['cccount']
      ,$_POST['cccount_starts']
      ,$_POST['ccserial']
      ,$_POST['cc_code']
      ,$_POST['ccbrcode']);
    }

    function getstartnumber($db,$ccstation3,$cccount,$cccount_starts,$ccserial,$cc_code,$ccbrcode){

      $procured=$db->query("SELECT COUNT(*) AS 'no_of_proc' FROM cnotenumber WHERE cnote_serial='$ccserial' AND cnote_status='Allotted' AND cnote_station='$ccstation3' AND br_code='$ccbrcode' AND cust_code IS NULL");
      $noofrow=$procured->fetch(PDO::FETCH_OBJ);
      if($noofrow->no_of_proc<$cccount){
        $validcountsales = $noofrow->no_of_proc;
      }
      else if($noofrow->no_of_proc>=$cccount){
        $validcountsales = $cccount;
      }
      return $validcountsales;
    }
    //FETCHING DATA FOR COLLECTION CENTER ALLOTMENT FORM
    if(isset($_POST['ccoption']))
    {     
      echo getstartforcollectioncenter($db,$_POST['ccoption'],$_POST['ccstation2'],$_POST['cccode']);
    }
    function getstartforcollectioncenter($db,$ccoption,$ccstation2,$cccode){
      $cnotenumber="SELECT TOP ( 1 ) cnote_number FROM cnotenumber WHERE cnote_status='Allotted' AND cnote_serial='".$ccoption. "' AND cnote_station='".$ccstation2. "' AND br_code='".$cccode. "' AND cust_code IS NULL  ORDER BY cnote_number";
      $firstnumber=$db->query($cnotenumber);
      $result=$firstnumber->fetch(PDO::FETCH_ASSOC);
        if(!empty($result['cnote_number']))
        {
          return $result['cnote_number'];
        }
        else
        {
          return "No cnotes available to allot";
        }
    }

    //make partition for allottment cc
    if(isset($_POST['ccpartstation'])){
      echo fetchpartition($db
      ,$_POST['ccpartstation']
      ,$_POST['ccpartserial']
      ,$_POST['ccpartcount']
      ,$_POST['ccpartstart']
      ,$_POST['allccbrcode']);
    }
    function fetchpartition($db
    ,$ccpartstation
    ,$ccpartserial
    ,$ccpartcount
    ,$ccpartstart
    ,$allccbrcode) {
      $i=0;
      $html='';
      $partitions = $db->query("SELECT DISTINCT allot_po_no FROM cnotenumber WHERE cnote_serial = '$ccpartserial' AND cnote_station='$ccpartstation' AND br_code='$allccbrcode' AND cnote_status='Allotted'  AND cust_code IS NULL ORDER BY allot_po_no");
      while($rowpartpono = $partitions->fetch(PDO::FETCH_OBJ)){
        $i++;
        $partsstart = $db->query("SELECT TOP ( 1 ) cnote_number FROM cnotenumber WHERE cnote_status='Allotted' AND cnote_serial='$ccpartserial' AND cnote_station='$ccpartstation' AND br_code='$allccbrcode'  AND cust_code IS NULL AND allot_po_no='$rowpartpono->allot_po_no' ORDER BY cnote_number");
        $partsend   = $db->query("SELECT TOP ( 1 ) cnote_number FROM cnotenumber WHERE cnote_status='Allotted' AND cnote_serial='$ccpartserial' AND cnote_station='$ccpartstation' AND br_code='$allccbrcode'  AND cust_code IS NULL AND allot_po_no='$rowpartpono->allot_po_no' ORDER BY cnote_number DESC");
        $rowpartsstart = $partsstart->fetch(PDO::FETCH_OBJ);
        $rowpartsend = $partsend->fetch(PDO::FETCH_OBJ);
        $parttotal =  ($rowpartsend->cnote_number-$rowpartsstart->cnote_number) +1;//row total
        if($parttotal >= $ccpartcount)
        {
          $rowparttotal = $ccpartcount;
        }
        else if($parttotal <= $ccpartcount)
        {
          $rowparttotal = $parttotal;
        }
        $actuelpartend = ($rowpartsstart->cnote_number + $rowparttotal)-1;
        if($ccpartcount>0){
          $valuegetvalid = $db->query("SELECT COUNT(*) AS 'procd' FROM cnotenumber WHERE cnote_serial='$ccpartserial' AND cnote_station='$ccpartstation'  AND cust_code IS NULL AND br_code='$allccbrcode' AND cnote_status='Allotted' AND cnote_number BETWEEN $rowpartsstart->cnote_number AND $actuelpartend")->fetch(PDO::FETCH_OBJ);
        
        $html.= "<tr>
              <td>$i</td>
              <td><input type='text' class='form-control' name='salespono[]' value='$rowpartpono->allot_po_no' readonly style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>
              <td><input type='text' class='form-control' name='salesstart[]' value='$rowpartsstart->cnote_number' readonly  style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>
              <td><input type='text' class='form-control' name='salesend[]' value='$actuelpartend' readonly  style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>
              <td><input type='text' class='form-control' name='salescount[]' value='$rowparttotal' readonly  style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>
              <td><input type='text' class='form-control val_proc' name='salesvalid[]' value='$valuegetvalid->procd' readonly  style='background:transparent!important;color:black!important;outline: none;border: 0px solid;font-weight:100!important;'></td>
             </tr>";
        }
        $ccpartcount = $ccpartcount - $valuegetvalid->procd;
      }
      return $html;
    }
    //make partition for allottment cc
  // auto populate collection center
  if(isset($_POST['branchcc']))
  {
    $branchcc  = $_POST['branchcc'];
    $compcc    = $_POST['compcc'];
    $arrayvalues=[];
    $getautoppl8 = $db->query("SELECT cc_code FROM collectioncenter WHERE stncode='$compcc' AND br_code='$branchcc'");
    while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
    {
      $arrayvalues[] = $getautoppl8row['cc_code'];
    }
    echo json_encode($arrayvalues);
  }
  // auto populate collection name based on branch
  if(isset($_POST['c_name_brcc']))
  {
    $c_name_brcc  = $_POST['c_name_brcc'];
    $c_name_compcc    = $_POST['c_name_compcc'];
    $arrayc_name=[];
    $getautonamel8 = $db->query("SELECT cc_name FROM collectioncenter WHERE stncode='$c_name_compcc' AND br_code='$c_name_brcc'");
    while($getautoppnamerow = $getautonamel8->fetch(PDO::FETCH_ASSOC))
    {
      $arrayc_name[] = $getautoppnamerow['cust_name'];
    }
    echo json_encode($arrayc_name);
  }
  //list collection center allotments for reclaim form
  if(isset($_POST['rc_compcode'])){
    echo listallotmentsforreclaim($db,$_POST['rc_compcode'],$_POST['rccc_code'],$_POST['rcccbrcode']);
  }
  function listallotmentsforreclaim($db
  ,$rc_compcod
  ,$rccc_code
  ,$rcccbrcode){

    $allotments = $db->query("SELECT salesorderid,cnote_serial,cnote_count,cnote_start
    ,(select TOP 1 cnote_end from cnoteallocationccdetail where salesorderid=cnoteallocationcc.salesorderid and stncode=cnoteallocationcc.stncode order by allocationccdetailid desc) as endno 
    ,(select count(*) from cnotenumber where cnote_station=cnoteallocationcc.stncode and allot_cc_no=cnoteallocationcc.salesorderid and cnote_status='Allotted') as validcounts 
    FROM cnoteallocationcc WHERE stncode='$rc_compcod' AND cc_code='$rccc_code'");

    $html = '';$sno=0;
    while($rowallotments = $allotments->fetch(PDO::FETCH_OBJ)){
      if((int)$rowallotments->validcounts>0){ $sno++;
      $html .= "<tr>
      <td><input type='radio' name='allotment' value='$rowallotments->salesorderid' onclick='getcnotesofallotment(this.value)'></td>
      <td>$sno</td>
      <td>$rowallotments->salesorderid</td>
      <td>$rowallotments->cnote_start</td>
      <td>$rowallotments->endno</td>
      <td>$rowallotments->cnote_count</td>
      <td>$rowallotments->validcounts</td>
      </tr>";
      }
    }
    return $html;
  }
  //list cnotes for salesorder id
  if(isset($_POST['rccn_br'])){
    echo getcnotesofsalesid($db,$_POST['rccn_br'],$_POST['rccn_stn'],$_POST['ccsalesid']);
  }
  function getcnotesofsalesid($db
  ,$rccn_br
  ,$rccn_stn
  ,$ccsalesid){

    $cnotes = $db->query("SELECT cnote_number,cnote_serial,cnote_station,cnote_status FROM cnotenumber
     WHERE cnote_station='$rccn_stn' AND allot_cc_no='$ccsalesid' AND cnote_status='Allotted' ORDER BY cnote_number");

     $html = ""; $sno=0;
     while($row = $cnotes->fetch(PDO::FETCH_OBJ)){$sno++;
       $html .= "<tr>
       <td><input type='checkbox' id='cnotenumbers' name='cnotenumbers[]' value='$row->cnote_number'></td>
       <td>$sno</td>
       <td>$row->cnote_station</td>
       <td>$row->cnote_number</td>
       <td>$row->cnote_status</td>
       </tr>";
     }
     return $html;
  }
   //get collectioncenters json
   if(isset($_POST['getjsoncc_br'])){
    echo getcustomersjson($db,$_POST['getjsoncc_br'],$_POST['getjsoncc_stn']);
  }
  function getcustomersjson($db,$getjsoncc_br,$getjsoncc_stn){
    $query = $db->query("SELECT cc_code,cc_name FROM collectioncenter WHERE stncode='$getjsoncc_stn' AND br_code='$getjsoncc_br'");
    $json = [];
    while($row = $query->fetch(PDO::FETCH_OBJ)){
      $json[trim($row->cc_code)] = trim($row->cc_name);
    }
    return json_encode($json);
  }
  //get collectioncenters json

  function console_log($output, $with_script_tags = true) {
          $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
          ');';
          if ($with_script_tags) {
              $js_code = '<script>' . $js_code . '</script>';
          }
          echo $js_code;
      }