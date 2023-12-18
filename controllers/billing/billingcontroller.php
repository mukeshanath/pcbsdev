<?php 

/* File name   : TPC - Billing controller
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

class billingcontroller extends My_controller
{
  public function index()
  {
        $this->view->title = __SITE_NAME__ . ' - billingcontroller';
    //$this->view->render('billing/cash');
  }
}

    error_reporting(0);
    //Fetch Branch code and name for cnote number entering
    if(isset($_POST['cnote_no']))
    {
        $cnote_no = $_POST['cnote_no'];
        $cnote_station = $_POST['cnote_station'];
        $podorigin = $_POST['podorigin'];
        $v_flagging = $_POST['v_flagging'];

        //virtual check condition
        if($v_flagging=='Virtual'){
          $flagvirtualccond = " AND (SELECT v_flag FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)=1";
        }
        else if($v_flagging=='Regular'){
          $flagvirtualccond = " AND (SELECT v_flag FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)=0";
        }
        //virtual check condition
        if($podorigin=='gl' || empty($podorigin) || trim($podorigin)==trim($cnote_station)){
          $pod_condition = "AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD' AND NOT cnote_serial ='COD'";
        }else{
          $pod_condition = "AND cnote_serial='$podorigin'";
        }
        $cash_pull = $db->query("SELECT TOP ( 1 ) * FROM cnotenumber WITH (NOLOCK) WHERE cnote_number='$cnote_no' AND cnote_station='$cnote_station' $pod_condition $flagvirtualccond ORDER BY cnote_number");
        $cash_pullrow = $cash_pull->fetch(PDO::FETCH_ASSOC);
        $check_custlock = $db->query("SELECT * FROM customer WHERE cust_code='".$cash_pullrow['cust_code']."' AND comp_code='$cnote_station' AND br_code='".$cash_pullrow['br_code']."'")->fetch(PDO::FETCH_OBJ);
        $getcnobrname = $db->query("SELECT * FROM branch WHERE stncode='$cnote_station' AND br_code='".$cash_pullrow['br_code']."'")->fetch(PDO::FETCH_OBJ);
        echo $cash_pullrow['br_code'].",".$getcnobrname->br_name.",".$cash_pullrow['cnote_status'].",".$cash_pullrow['cust_code'].",".$check_custlock->cust_name.",".$check_custlock->cust_status;

    }
    //Fetch Branch Name in Billing Controller
    if(isset($_POST['br_code']))
    {
        $br_code = $_POST['br_code'];
        $branch_pull = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$br_code'");
        $branch_pullrow = $branch_pull->fetch(PDO::FETCH_ASSOC);
       echo  $branch_pullrow['br_name'];
    }

    //Verify Branch is Valid
    if(isset($_POST['branch_verify']))
    {
      $stationcode = $_POST['stationcode'];
      $branch_verify = $_POST['branch_verify'];
      $brcode_verify = $db->query("SELECT br_code FROM branch WITH (NOLOCK) WHERE br_code='$branch_verify' AND stncode='$stationcode'");
      echo $brcode_verify->rowCount();
    }
    //Verify Customer is Valid
    if(isset($_POST['customer_verify']))
    {
      $customer_verify = $_POST['customer_verify'];
      $branch_code = $_POST['branch_code'];
      $podorigin2 = $_POST['podorigin2'];
      $cnote_type = $_POST['cnote_type'];

      //virtual check condition
      if($cnote_type=='Virtual'){
        $virtualccond = " AND (SELECT v_flag FROM groups WITH (NOLOCK) WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)=1 ";
      }
      else if($cnote_type=='Regular'){
        $virtualccond = " AND (SELECT v_flag FROM groups WITH (NOLOCK) WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)=0 ";
      }
      //virtual check condition

      if($podorigin2=='gl' || empty($podorigin2)){
        $pod_condition2 = "AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD' AND NOT cnote_serial ='COD'";
      }else{
        $pod_condition2 = "AND cnote_serial='$podorigin2'";
      }
      $cust_code_verify = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE cust_code='$customer_verify' AND br_code='$branch_code'");
      $rowcust_code_verify = $cust_code_verify->fetch(PDO::FETCH_ASSOC);
      $getcnotenumber = $db->query("SELECT TOP ( 1 ) * FROM cnotenumber WITH (NOLOCK) WHERE br_code='$branch_code' AND cust_code='$customer_verify' AND cnote_status='Allotted' $pod_condition2 $virtualccond ORDER BY cnotenoid");
      $rowgetcnotenumber = $getcnotenumber->fetch(PDO::FETCH_ASSOC);
      echo $cust_code_verify->rowCount()."|".$rowcust_code_verify['cust_name']."|".$rowgetcnotenumber['cnote_number']."|".$rowcust_code_verify['cust_status']."|".$rowgetcnotenumber['cnote_status'];
    }
     //Verify Destination is Valid
    
     if(isset($_POST['destcode2']))
     {
       $destcode2 = $_POST['destcode2'];
       $destval_cust = $_POST['destval_cust'];
       $dest_station = $_POST['dest_station'];
       $dest_verify = $db->query("SELECT * FROM pincode WITH (NOLOCK) WHERE pincode='$destcode2' OR city_name='$destcode2' OR dest_code='$destcode2'"); 
       if($dest_verify->rowCount()=='0'){
          //$getdestrowdest = $db->query("SELECT * FROM destination WITH (NOLOCK) WHERE zonecode_a='$destcode2' OR zonecode_b='$destcode2' OR zonecode_c='$destcode2' OR zone_cash='$destcode2' OR dest_code='$destcode2' OR dest_name='$destcode2'");
		  $getdestrowdest = $db->query("SELECT * from  destination WHERE active='Y' AND comp_code='$dest_station'  AND dest_code='$destcode2' OR zonecode_a='$destcode2' OR zonecode_b='$destcode2' OR zonecode_c='$destcode2' OR zone_cash='$destcode2' OR dest_name='$destcode2'");
		  echo $getdestrowdest->rowCount();
        }
        else{
          echo $dest_verify->rowCount();
        }
      }
     //Verify Cnotenumber is Valid
     if(isset($_POST['cnotestation_code']))
     {
       $cnote_no1 = $_POST['cnote_no1'];
       $cnotestation_code = $_POST['cnotestation_code'];
       $cnote_no_verify = $db->query("SELECT cnote_status FROM cnotenumber WITH (NOLOCK) WHERE cnote_number='$cnote_no1' AND cnote_station='$cnotestation_code' AND cnote_status='Allotted'");
       echo $cnote_no_verify->rowCount();
     }
        // auto populate customer based on branch
      if(isset($_POST['branch8']))
      {
        $branch8  = $_POST['branch8'];
        $comp8    = $_POST['comp8'];
        $getautoppl8 = $db->query("SELECT cust_code FROM customer WITH (NOLOCK) WHERE comp_code='$comp8' AND br_code='$branch8' AND cust_active='Y'");
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
        $getautopp11 = $db->query("SELECT cust_name FROM customer WITH (NOLOCK) WHERE comp_code='$comp11' AND br_code='$branch11' AND cust_active='Y'");
        if($getautopp11->rowCount()==0){

        }else{
        while($getautopp11row = $getautopp11->fetch(PDO::FETCH_ASSOC))
        {
          $arrayvalues11[] = $getautopp11row['cust_name'];
        }
        echo json_encode($arrayvalues11);
      }
      }
       //Calculate Amount for Credit
     if(isset($_POST['customer']))
     {
      error_reporting(0);
       $ccustomer    = $_POST['customer'];
       $creditorigin      = $_POST['origin'];
       $destcode_ofcredit = $_POST['destination'];
       $creditweight      = $_POST['weight'];
       $branchfind       = $_POST['branch'];
       $table     = $_POST['table'];


             
       function getrate($branchfind,$db,$creditorigin){
          $getbranch = $db->query("SELECT * FROM branch WHERE br_code='$branchfind' AND stncode='$creditorigin'")->fetch(PDO::FETCH_OBJ);
          if(empty($getbranch->rate) OR is_null($getbranch->rate)){
            $setrate = 'TLRRATE1';
          }else{
            $setrate = $getbranch->rate;
          }
          return $setrate;
        } 
  
        $company_origins = array("KUR", "PNQ", "DBL");
        if($table=='cash' && in_array($company_origins,$mode_origin)){
      $creditcustomer = getrate($branchfind,$db,$creditorigin);
     }else{
      $crate = $db->query("SELECT rate FROM customer WITH (NOLOCK) WHERE comp_code='$creditorigin' AND cust_code='$ccustomer'");
      $valcrate = $crate->fetch(PDO::FETCH_OBJ);
      if(!empty($valcrate->rate)){
        $creditcustomer = $valcrate->rate;
      }
      else{
        $creditcustomer = $ccustomer;
      }
     }
     
       $getcustzncode = $db->query("SELECT zncode FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
      $rowzncode = $getcustzncode->fetch(PDO::FETCH_ASSOC);

      $getdestfrompin = $db->query("SELECT TOP ( 1 ) dest_code FROM pincode WITH (NOLOCK) WHERE pincode='$destcode_ofcredit' OR dest_code='$destcode_ofcredit' OR city_name='$destcode_ofcredit'");
      if($getdestfrompin->rowCount()==0){
        $verifydestination = $db->query("SELECT TOP ( 1 ) dest_code FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$destcode_ofcredit' OR dest_name='$destcode_ofcredit'");
        $rowdestdata = $verifydestination->FETCH(PDO::FETCH_OBJ);
        $val_rowdestdata = $rowdestdata->dest_code;
        $verifyratecode = $db->query("SELECT con_code FROM rate_custcontract WITH (NOLOCK) WHERE origin='$creditorigin' AND destn='$val_rowdestdata' AND cust_code='$creditcustomer'");
        if($verifyratecode->rowCount()==0)
        {
          // Customer type A rate setup populate
         if($rowzncode['zncode']=='A'){
          $getrowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
          if($getrowdest->rowCount()==0){
            $creditdestination =  $destcode_ofcredit;
          }else{
          $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
          $creditdestination = $rowgetzonecode['zonecode_a'];
          }
         }
       
        // Customer type B rate setup populate
       if($rowzncode['zncode']=='B'){
          $getrowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata' ");
          if($getrowdest->rowCount()==0){
            $creditdestination = $destcode_ofcredit;
          }else{
          $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
          $creditdestination = $rowgetzonecode['zonecode_b'];
          }
         }
    
        // Customer type C rate setup populate
       if($rowzncode['zncode']=='C'){
          $getrowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
          if($getrowdest->rowCount()==0){
            $creditdestination =  $destcode_ofcredit;
          }else{
          $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
          $creditdestination = $rowgetzonecode['zonecode_c'];
          }
         }
    
        // Cash customer rate setup populate
       if($rowzncode['zncode']=='cash'){
          $getrowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$val_rowdestdata'");
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
        $checkdestn = $db->query("SELECT * FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='".$rowactualdest["dest_code"]."'");
        $rowcheckdestn = $checkdestn->fetch(PDO::FETCH_OBJ);

        $dest_rate = $db->query("SELECT * from rate_custcontract WHERE stncode='$creditorigin' AND destn='$rowcheckdestn->dest_code' AND cust_code='$creditcustomer'");
        if($dest_rate->rowCount()==0){
        if($rowzncode['zncode']=='A'){
           $getrowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
           $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
           $creditdestination = $rowgetzonecode['zonecode_a'];
          }
        if($rowzncode['zncode']=='B'){
           $getrowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
           $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
           $creditdestination = $rowgetzonecode['zonecode_b'];
          }
        if($rowzncode['zncode']=='C'){
           $getrowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
           $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
           $creditdestination = $rowgetzonecode['zonecode_c'];
          }
        if($rowzncode['zncode']=='cash'){
           $getrowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$valactueldest'");
           $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
           $creditdestination = $rowgetzonecode['zone_cash'];
          }
        }else{
          $creditdestination = $rowcheckdestn->dest_code;
        }
      }
      
         $pod_amt         = $_POST['pod_amt'];
    
          if($pod_amt=='gl'){
             $pro_rate = '0';
          }
          else if($pod_amt=='PRO' && $_POST['weight']>1){
            $getsp = $db->query("SELECT pro_per_kg FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
            $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
            $proamount = $rowsp->pro_per_kg;
            $pro = ceil($creditweight)-1;
            $pro_rate = $pro * $proamount;
          }
          else if($pod_amt=='PRC' && $_POST['weight']>1){
            $getsp = $db->query("SELECT prc_per_kg FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
            $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
            $proamount = $rowsp->prc_per_kg;
            $pro = ceil($creditweight)-1;
            $pro_rate = $pro * $proamount;
          }
          else if($pod_amt=='PRD' && $_POST['weight']>1){
            $getsp = $db->query("SELECT prd_per_kg FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
            $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
            $proamount = $rowsp->prd_per_kg;
            $pro = ceil($creditweight)-1;
            $pro_rate = $pro * $proamount;
          }
         //  else if($pod_amt=='COD' && $_POST['weight']>1){
         //    $getsp = $db->query("SELECT cod_per_kg FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'");
         //    $rowsp = $getsp->fetch(PDO::FETCH_OBJ);
         //    $proamount = $rowsp->cod_per_kg;
         //    $pro = ceil($creditweight)-1;
         //    $pro_rate = $pro * $proamount;
         //  }
          
      //end special cnotes
      $creditpcs         = $_POST['pcs'];
      $creditmode         = trim($_POST['mode']);
      

      ///very very important line

       ///////change 1
      $getcontract_rate1  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC")->fetch(PDO::FETCH_OBJ);

      $getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC");
      $cust_type   = $db->query("SELECT cust_type FROM customer WITH (NOLOCK) WHERE comp_code='$creditorigin' AND cust_code='$creditcustomer'")->fetch(PDO::FETCH_OBJ);
      //Special MODE FROM CASH


      ///////change 2
      if($getcontract->rowCount()==0 && ($creditmode=='PRO' || $creditmode=='PRC') && $cust_type->cust_type=='D')
      {
        

        $cashdestn = (isset($val_rowdestdata)?$val_rowdestdata:$valactueldest);

        $getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='RATE1' AND origin='$creditorigin' AND destn='$cashdestn' AND mode='$creditmode' ORDER BY custcontid DESC");
        if($getcontract->rowCount()==0){

            $cashzone = $db->query("SELECT TOP 1 zncode FROM customer WITH (NOLOCK) WHERE cust_code='RATE1' AND comp_code='$creditorigin'")->fetch(PDO::FETCH_OBJ);


          if($cashzone->zncode=='A'){
            $getrowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$cashdestn'");
            $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
            $creditdestination = $rowgetzonecode['zonecode_a'];
           }
         if($cashzone->zncode=='B'){
            $getrowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$cashdestn'");
            $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
            $creditdestination = $rowgetzonecode['zonecode_b'];
           }
         if($cashzone->zncode=='C'){
            $getrowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$cashdestn'");
            $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
            $creditdestination = $rowgetzonecode['zonecode_c'];
           }
         if($cashzone->zncode=='cash'){
            $getrowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$creditorigin' AND dest_code='$cashdestn'");
            $rowgetzonecode = $getrowdest->fetch(PDO::FETCH_ASSOC);
            $creditdestination = $rowgetzonecode['zone_cash'];

           }
           $getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='RATE1' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC");

        }


      }

///////change 1
    function  gst($amount){
     $net_amount = ($amount*100);
     $total_value = ($net_amount/118);
     return $total_value;
    }
    $cust_type_fsc = $db->query("SELECT cust_fsc,fsc_type FROM customer WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND comp_code='$creditorigin'")->fetch(PDO::FETCH_OBJ);
    if($cust_type_fsc->fsc_type=='F'){
      $custtype = $cust_type_fsc->cust_fsc;
    }else{
      $custtype = 0;
    }
   $fsccharges = (100+$custtype);

      //PRO MODE FROM CASH
      $rowcontract  = $getcontract->fetch(PDO::FETCH_ASSOC);
       $contractcode = $rowcontract['con_code'];
      // echo $contractcode; exit();
      //echo json_encode(array($creditdestination,("SELECT TOP 1 con_code FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC")));
      //validate weight
      $weightval = $db->query("SELECT MAX (upperwt) as maxweight FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin'");
      $rowmaxweight = $weightval->fetch(PDO::FETCH_OBJ);
      if($rowmaxweight->maxweight<$creditweight && $creditweight>0){
        echo 0;
      }else{
          //end
      $getamount    = $db->query("SELECT * FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight' AND stncode='$creditorigin'");
      $rowamount    = $getamount->fetch(PDO::FETCH_ASSOC);
        //Bands calculation
        if($rowamount['onadd']==0){    

          $getamount_c    = $db->query("SELECT sum(rate) as rate FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND lowerwt >='0' AND upperwt <='".$rowamount['upperwt']."' AND onadd=0 AND stncode='$creditorigin'");
          $rowamount_c    = $getamount_c->fetch(PDO::FETCH_ASSOC);

        //  if($table=="credit" && $cust_type->cust_type=='D' && ($creditmode=='PRO' || $creditmode=='PRC') && count($getcontract_rate1->con_code)==0){   ///////change 1
        //    $rate1 = (($rowamount['rate'])*$creditpcs); 
        //    $gstvalue = (gst($rate1));
        //    $fsc_total = ($gstvalue*100);
        //    $fsc_percentage = number_format($fsc_total/$fsccharges,2);
        //    echo $fsc_percentage+$pro_rate;  
        //    exit();
        //  }else{
        //    echo ($rowamount['rate']+$pro_rate)*$creditpcs;
        //   exit();
        //  }
        echo ($rowamount_c['rate']+$pro_rate)*$creditpcs;
       // echo json_encode($rowamount['upperwt']);
        }
        else{
           $zero_onadd = $db->query("SELECT SUM(rate) AS rate FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND onadd=0 AND upperwt<='$creditweight'")->fetch(PDO::FETCH_OBJ);
           $rate1 = (int)$zero_onadd->rate;


           $get_fullrow = $db->query("SELECT rate,upperwt,multiple,onadd,lowerwt FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND onadd !=0 AND upperwt<'".$rowamount['upperwt']."' ORDER BY upperwt DESC");
            while($rowdata = $get_fullrow->fetch(PDO::FETCH_OBJ)){
              $rates = $rowdata->rate;
              $upperwt = number_format($rowdata->upperwt,2);
              $lowerwts = number_format($rowdata->lowerwt,2);
              $multiples = number_format($rowdata->multiple,2);
              $second_rate = number_format(($upperwt-$lowerwts)/$multiples,2);
              $rate2[] = ceil($second_rate) * $rates;
             
            }
         

           $custcontdetailid = $rowamount['custcontdetailid'];
           $get_onadd = $db->query("SELECT TOP 1 custcontdetailid FROM rate_contractdetail WITH (NOLOCK) WHERE custcontdetailid < '$custcontdetailid' AND con_code='$contractcode' AND stncode='$creditorigin' ORDER BY custcontdetailid DESC")->fetch(PDO::FETCH_OBJ);

           

           $onnadd_data = $db->query("SELECT onadd FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND custcontdetailid='$get_onadd->custcontdetailid'")->fetch(PDO::FETCH_OBJ);

           $onnadd_count = $db->query("SELECT count(onadd) as conadd FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND onadd='".$rowamount['onadd']."'")->fetch(PDO::FETCH_OBJ);

           if($onnadd_count->conadd > 1){
            $order = "ASC";
           }else{
            $order = "DESC";
           }
           $getfirst_rate = $db->query("SELECT rate,onadd,upperwt FROM rate_contractdetail WITH (NOLOCK) WHERE con_code='$contractcode' AND stncode='$creditorigin' AND onadd='$onnadd_data->onadd' AND upperwt<='$creditweight' ORDER BY upperwt $order");
           $rowfirst_rate = $getfirst_rate->fetch(PDO::FETCH_ASSOC);

         
           $first_rate = number_format($rowfirst_rate['upperwt'],2);
   
           $second_rate1 = (ceil(($creditweight-$first_rate)/$rowamount['multiple'])*$rowamount['multiple'])/$rowamount['onadd'];
           $rate7 = $second_rate1 * $rowamount['rate'];


           
             if($rate2==null){
               $finalamount[]  = $rate2;
             }else{
              $finalamount  = $rate2;
             }
             $pushdata = array_push($finalamount,$rate1,$rate7);
          echo (array_sum($finalamount)+$pro_rate)*$creditpcs;
    
   // echo json_encode(array($finalamount,$rate1));
           exit();
        }
       
      }
    

       
     }
     
       //Fetch Mode based on destination for credit
       if(isset($_POST['dest_mode'])){
        $dest_mode = $_POST['dest_mode'];
        $mode_origin = $_POST['mode_origin'];
        $mcustomer = $_POST['mode_cust'];
        $podorigin  = $_POST['podorigin'];
        $arrmode = $_POST['arr'];
        $branch = $_POST['branch'];
        $table = $_POST['table'];
        

       
        function cust_type($mode_origin,$db,$mcustomer){
          $custtype = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE comp_code='$mode_origin' AND cust_code='$mcustomer'")->fetch(PDO::FETCH_OBJ);
            $type = $custtype->cust_type;
          return $type;
        }

        function getrate($branch,$db,$mode_origin){
          $getbranch = $db->query("SELECT * FROM branch WHERE br_code='$branch' AND stncode='$mode_origin'")->fetch(PDO::FETCH_OBJ);
          if(empty($getbranch->rate) OR is_null($getbranch->rate)){
            $setrate = 'TLRRATE1';
          }else{
            $setrate = $getbranch->rate;
          }
          return $setrate;
        } 
        $company_origins = array("KUR", "PNQ", "DBL");
     if($table=='cash' && in_array($company_origins,$mode_origin)){
      $mode_cust = getrate($branch,$db,$mode_origin);
     }else{
      $mrate = $db->query("SELECT rate FROM customer WITH (NOLOCK) WHERE comp_code='$mode_origin' AND cust_code='$mcustomer'");
      $valmrate = $mrate->fetch(PDO::FETCH_OBJ);
      if(!empty($valmrate->rate)){
         $mode_cust = $valmrate->rate;
      }
      else{
         $mode_cust = $mcustomer;
      }
     }

        $getmodefrompin = $db->query("SELECT TOP ( 1 ) dest_code FROM pincode WITH (NOLOCK) WHERE pincode='$dest_mode' OR dest_code='$dest_mode' OR city_name='$dest_mode'");
        if($getmodefrompin->rowCount()==0){
          $modeverifydestn = $db->query("SELECT TOP ( 1 ) dest_code FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$dest_mode' OR dest_name='$dest_mode'");
          $rowmodeverifydestn = $modeverifydestn->fetch(PDO::FETCH_OBJ);
          $destinationdestcode = $rowmodeverifydestn->dest_code;
          $modeverifycontract = $db->query("SELECT con_code FROM rate_custcontract WITH (NOLOCK) WHERE origin='$mode_origin' AND destn='$destinationdestcode' AND cust_code='$mode_cust'");
          if($modeverifycontract->rowCount()==0){
            $getmodezncode = $db->query("SELECT zncode FROM customer WITH (NOLOCK) WHERE cust_code='$mode_cust' AND comp_code='$mode_origin'");
            $rowmodezncode = $getmodezncode->fetch(PDO::FETCH_ASSOC);
            
          if($rowmodezncode['zncode']=='A'){
            $getmoderowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$destinationdestcode'");
            if($getmoderowdest->rowCount()==0){
              $credit_mode = $dest_mode;
            }
            else{
            $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
            $credit_mode = $rowgetmodecode['zonecode_a'];
            }
           }
         if($rowmodezncode['zncode']=='B'){
            $getmoderowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$destinationdestcode'");
            if($getmoderowdest->rowCount()==0){
              $credit_mode = $dest_mode;
            }
            else{
            $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
            $credit_mode = $rowgetmodecode['zonecode_b'];
            }
           }
         if($rowmodezncode['zncode']=='C'){
            $getmoderowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$destinationdestcode'");
            if($getmoderowdest->rowCount()==0){
              $credit_mode = $dest_mode;
            }
            else{
            $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
            $credit_mode = $rowgetmodecode['zonecode_c'];
            }
           }
         if($rowmodezncode['zncode']=='cash'){
            $getmoderowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$destinationdestcode'");
            if($getmoderowdest->rowCount()==0){
              $credit_mode = $dest_mode;
            }
            else{
            $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
            $credit_mode = $rowgetmodecode['zone_cash'];
            }
           }
          }else{
            $credit_mode = $destinationdestcode;
          }
        }
        else{
        
        $rowactualmode = $getmodefrompin->fetch(PDO::FETCH_ASSOC);
        $actueldestcode = $rowactualmode["dest_code"];
        $checkmodedestntb = $db->query("SELECT * FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actueldestcode'");
        $rowcheckmodedestntb = $checkmodedestntb->fetch(PDO::FETCH_OBJ);
        $valdestinationdest = $rowcheckmodedestntb->dest_code;
        $modedest_rate = $db->query("SELECT * from rate_custcontract WITH (NOLOCK) WHERE stncode='$mode_origin' AND destn='$valdestinationdest' AND cust_code='$mode_cust'");
        if($modedest_rate->rowCount()==0){
        
          $getmodezncode = $db->query("SELECT zncode FROM customer WITH (NOLOCK) WHERE cust_code='$mode_cust' AND comp_code='$mode_origin'");
          $rowmodezncode = $getmodezncode->fetch(PDO::FETCH_ASSOC);
         
        if($rowmodezncode['zncode']=='A'){
          
           $getmoderowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actueldestcode'");
           $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
           $credit_mode = $rowgetmodecode['zonecode_a'];
          }
        if($rowmodezncode['zncode']=='B'){
           $getmoderowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actueldestcode'");
           $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
           $credit_mode = $rowgetmodecode['zonecode_b'];
          }
        if($rowmodezncode['zncode']=='C'){
           $getmoderowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actueldestcode'");
           $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
           $credit_mode = $rowgetmodecode['zonecode_c'];
          }
        if($rowmodezncode['zncode']=='cash'){
           $getmoderowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actueldestcode'");
           $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
           $credit_mode = $rowgetmodecode['zone_cash'];
          }
        }else{
          $credit_mode = $rowcheckmodedestntb->dest_code;
        }
      }
    
            //GETTING CASH ZONE
            $actuelcashdestcode = (empty($actueldestcode)?$destinationdestcode:$actueldestcode);
            $cashzonemode = $db->query("SELECT TOP 1 zncode FROM customer WITH (NOLOCK) WHERE cust_code='RATE1' AND comp_code='$mode_origin'")->fetch(PDO::FETCH_OBJ);
            
            if($cashzonemode->zncode=='A'){
              $getmoderowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'");
              $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
              $cash_mode = $rowgetmodecode['zonecode_a'];
             }
           if($cashzonemode->zncode=='B'){
              $getmoderowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'");
              $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
              $cash_mode = $rowgetmodecode['zonecode_b'];
             }
           if($cashzonemode->zncode=='C'){
              $getmoderowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'");
              $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
              $cash_mode = $rowgetmodecode['zonecode_c'];
             }
           if($cashzonemode->zncode=='cash'){
              $getmoderowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'");
              $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
              $cash_mode = $rowgetmodecode['zone_cash'];
             }
            //GETTING CASH ZONE
    
            //GETTING CASH ZONE
            $actuelcashdestcode = (empty($actueldestcode)?$destinationdestcode:$actueldestcode);
            $cashzonemode = $db->query("SELECT TOP 1 zncode FROM customer WITH (NOLOCK) WHERE cust_code='RATE1' AND comp_code='$mode_origin'")->fetch(PDO::FETCH_OBJ);
            
            if($cashzonemode->zncode=='A'){
              $getmoderowdest = $db->query("SELECT zonecode_a FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'");
              $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
              $cash_mode = $rowgetmodecode['zonecode_a'];
             }
           if($cashzonemode->zncode=='B'){
              $getmoderowdest = $db->query("SELECT zonecode_b FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'");
              $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
              $cash_mode = $rowgetmodecode['zonecode_b'];
             }
           if($cashzonemode->zncode=='C'){
              $getmoderowdest = $db->query("SELECT zonecode_c FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'");
              $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
              $cash_mode = $rowgetmodecode['zonecode_c'];
             }
           if($cashzonemode->zncode=='cash'){
              $getmoderowdest = $db->query("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'");
              $rowgetmodecode = $getmoderowdest->fetch(PDO::FETCH_ASSOC);
              $cash_mode = $rowgetmodecode['zone_cash'];
             }









             
            //GETTING CASH ZONE
            if($table=='cash'){
              $getmode = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_cust' AND origin='$mode_origin' AND (destn='$cash_mode' OR destn='$credit_mode') AND (mode !='PRO' AND mode !='PRC') ORDER BY mode ASC");
              $getpromode = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_cust' AND origin='$mode_origin' AND (destn='$cash_mode' OR destn='$credit_mode') AND mode ='PRO' ORDER BY mode ASC");
              $getpromoderate = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_csut' AND origin='$mode_origin' AND (destn='$cash_mode' OR destn='$credit_mode') AND mode ='PRO' ORDER BY mode ASC");

              $getprcmode = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_cust' AND origin='$mode_origin' AND (destn='$cash_mode' OR destn='$credit_mode') AND mode ='PRC' ORDER BY mode ASC");
              $getprcmoderate = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_cust' AND origin='$mode_origin' AND (destn='$cash_mode' OR destn='$credit_mode') AND mode ='PRC' ORDER BY mode ASC");

            }else{
              $getmode = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_cust' AND origin='$mode_origin' AND destn='$credit_mode' AND (mode !='PRO' AND mode !='PRC') ORDER BY mode ASC");

              $getpromode = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_cust' AND origin='$mode_origin' AND destn='$credit_mode' AND mode ='PRO' ORDER BY mode ASC");
              $getpromoderate = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='".((cust_type($mode_origin,$db,$mcustomer)=='D') ?  'RATE1' : $mode_cust)."' AND origin='$mode_origin' AND destn='$cash_mode' AND mode ='PRO' ORDER BY mode ASC");


              $getprcmode = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_cust' AND origin='$mode_origin' AND destn='$credit_mode' AND mode ='PRC' ORDER BY mode ASC");
              $getprcmoderate = $db->query("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='".((cust_type($mode_origin,$db,$mcustomer)=='D') ?  'RATE1' : $mode_cust)."' AND origin='$mode_origin' AND destn='$cash_mode' AND mode ='PRC' ORDER BY mode ASC");
            }
           




            $modearray = array();
            $modearray_genral = array();
            $modearray_prc = array();

          
            if(in_array($podorigin,$arrmode)){
              if($getmode->rowCount()==0){
                echo '<option  value="">Mode Empty</option>';
              } else {
              while($rowgetmode = $getmode->fetch(PDO::FETCH_ASSOC)){
                array_push($modearray,trim($rowgetmode['mode']));
                echo json_encode($rowgetmode['mode']);
              }
              $unique_mode = array_unique($modearray);
              foreach($unique_mode as $mode){
                if(!empty($mode))
                echo '<option  value="'.$mode.'">'.$mode.'</option>';
                
              }
             }
            }else{
              if($podorigin=='PRO'){
               if($getpromode->rowCount()==0 && $getpromoderate->rowCount()==0){
                    if($getmode->rowCount()==0){
                         echo '<option  value="">Mode Empty</option>';
                       } else {
                         
                       while($rowgetmode = $getmode->fetch(PDO::FETCH_ASSOC)){
                         array_push($modearray,trim($rowgetmode['mode']));
                         echo json_encode($rowgetmode['mode']);
                       }
                       $unique_mode = array_unique($modearray);
                       foreach($unique_mode as $mode){
                         if(!empty($mode))
                         echo '<option  value="'.$mode.'">'.$mode.'</option>';
                       }
                      }              
                     } else {
               while($rowgetpromode = $getpromode->fetch(PDO::FETCH_ASSOC)){
                 array_push($modearray_genral,trim($rowgetpromode['mode']));
                 //echo '<option  value="'.$rowgetmode['mode'].'">'.$rowgetmode['mode'].'</option>';
               }
               while($rowgetpromoderate = $getpromoderate->fetch(PDO::FETCH_ASSOC)){
                array_push($modearray_genral,trim($rowgetpromoderate['mode']));
                //echo '<option  value="'.$rowgetmode['mode'].'">'.$rowgetmode['mode'].'</option>';
              }
               $unique_mode1 = array_unique($modearray_genral);
               foreach($unique_mode1 as $promode){
                 if(!empty($promode))
                 echo '<option  value="'.$promode.'">'.$promode.'</option>';
               }
              }
              }else{
               if($getprcmode->rowCount()==0  && $getprcmoderate->rowCount()==0){
                    if($getmode->rowCount()==0){
                         echo '<option  value="">Mode Empty</option>';
                       } else {
                         
                       while($rowgetmode = $getmode->fetch(PDO::FETCH_ASSOC)){
                         array_push($modearray,trim($rowgetmode['mode']));
                         echo json_encode($rowgetmode['mode']);
                       }
                       $unique_mode = array_unique($modearray);
                       foreach($unique_mode as $mode){
                         if(!empty($mode))
                         echo '<option  value="'.$mode.'">'.$mode.'</option>';
                       }
                      }               } else {
               while($rowgetmodeprc = $getprcmode->fetch(PDO::FETCH_ASSOC)){
                 array_push($modearray_prc,trim($rowgetmodeprc['mode']));
                 //echo '<option  value="'.$rowgetmode['mode'].'">'.$rowgetmode['mode'].'</option>';
               }
               while($rowgetmodeprcrate = $getprcmoderate->fetch(PDO::FETCH_ASSOC)){
                array_push($modearray_prc,trim($rowgetmodeprcrate['mode']));
                //echo '<option  value="'.$rowgetmode['mode'].'">'.$rowgetmode['mode'].'</option>';
              }
               $unique_mode2 = array_unique($modearray_prc);
               foreach($unique_mode2 as $prcmode){
                 if(!empty($prcmode))
                 echo '<option  value="'.trim($prcmode).'">'.trim($prcmode).'</option>';
               }
              }
              }
           }
         
        echo json_encode(array(("SELECT mode FROM rate_custcontract WITH (NOLOCK) WHERE cust_code='$mode_cust' AND origin='$mode_origin' AND destn='$cash_mode' AND (mode !='PRO' AND mode !='PRC') ORDER BY mode DESC"),$getmode->rowCount(),$cashzonemode->zncode,$rowgetmodecode['zone_cash'],("SELECT zone_cash FROM destination WITH (NOLOCK) WHERE comp_code='$mode_origin' AND dest_code='$actuelcashdestcode'"),$table));

      }







      

          //Fetch cnotenumber for Cash
          if(isset($_POST['dcbranch'])){
            $dcbranch = $_POST['dcbranch'];
            $dcstation = $_POST['dcstation'];
            $fetchcashcnote  = $db->query("SELECT TOP(1) cnote_number FROM cnotenumber WITH (NOLOCK) WHERE cnote_serial='DC' AND br_code='$dcbranch' AND cnote_station='$dcstation' ORDER BY cnotenoid");
            $rowcashnote = $fetchcashcnote->fetch(PDO::FETCH_OBJ);
            echo $rowcashnote->cnote_number;
          }
          //Fetch Branch code
          if(isset($_POST['br_namestn'])){
            $br_namestn = $_POST['br_namestn'];
            $ccbr_name = $_POST['br_name'];
            $rowbrcode = $db->query("SELECT br_code FROM branch WITH (NOLOCK) WHERE br_name='$ccbr_name' AND stncode='$br_namestn'")->fetch(PDO::FETCH_OBJ);
            echo $rowbrcode->br_code;
          }
          //Fetch Customer code
          if(isset($_POST['cccust_name'])){
            $cccust_name = $_POST['cccust_name'];
            $cc_namestn = $_POST['cc_namestn'];
            $cc_namebr = $_POST['cc_namebr'];
            $rowcustcode = $db->query("SELECT cust_code FROM customer WITH (NOLOCK) WHERE cust_name='$cccust_name' AND comp_code='$cc_namestn' AND br_code='$cc_namebr'")->fetch(PDO::FETCH_OBJ);
            echo $rowcustcode->cust_code;
          }
          //Validate mf_mo
          if(isset($_POST['mf_cust'])){
            date_default_timezone_set('Asia/Kolkata');
            $mf_cust = $_POST['mf_cust'];
            $mfmf_no = $_POST['mfmf_no'];
            $today = date('Y-m-d');
            $mfvalid = $db->query("SELECT * FROM bainbound_master WITH (NOLOCK) WHERE mf_no='$mfmf_no' AND date_added='$today'");
            if($mfvalid->rowCount()==0){
              echo 1;
            }
            else{
              $mfcvalid = $db->query("SELECT * FROM bainbound_master WITH (NOLOCK) WHERE mf_no='$mfmf_no' AND ba_code='$mf_cust' AND date_added='$today'");
              echo $mfcvalid->rowCount();
            }
          }
        //fetch cnotenumber for cash_pullrow
        if(isset($_POST['ba_branch'])){
          $ba_branch = $_POST['ba_branch'];
          $ba_stn = $_POST['ba_stn'];
          $cashorigin = $_POST['cashorigin'];
          $cashcccode = $_POST['cashcccode'];
          $cashcnote_type = $_POST['cashcnote_type'];
          $branchname = $_POST['branchname'];
           //virtual check condition
         if($cashcnote_type=='Virtual'){
           $virtualccond = " AND (SELECT v_flag FROM groups WITH (NOLOCK) WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)=1 ";
         }
         else if($cashcnote_type=='Regular'){
           $virtualccond = " AND (SELECT v_flag FROM groups WITH (NOLOCK) WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)=0 ";
         }
         //virtual check conditio

           if($cashorigin=='gl'){
             $cash_condition = " AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD'";
           }else{
             $cash_condition = " AND cnote_serial='$cashorigin'";
           }
           if(!empty($cashcccode)){
             $cc = $db->query("SELECT count(*) as countd FROM collectioncenter WHERE stncode='$ba_stn' AND cc_code='$cashcccode'")->fetch(PDO::FETCH_OBJ);
             if($cc->countd>0){
               $cashcustcondition = "cust_code='$cashcccode' ";
             }
             else{
               $cashcustcondition = " cust_code IS NULL ";
             }
           }
           else{
               $cashcustcondition = " cust_code IS NULL ";
           }
           $select_branch = $db->query("SELECT virtual_edit FROM branch WHERE br_code='$ba_branch' AND br_name='$branchname' AND stncode='$ba_stn'")->fetch(PDO::FETCH_OBJ);
           $virutaledit = $select_branch->virtual_edit;
          $gettopcashcno = $db->query("SELECT TOP(1) cnote_number,cnote_serial FROM cnotenumber WHERE cnote_status='Allotted' AND $cashcustcondition AND br_code='$ba_branch' AND cnote_station='$ba_stn' $cash_condition $virtualccond ORDER BY cnotenoid")->fetch(PDO::FETCH_OBJ);
          echo json_encode(array($gettopcashcno->cnote_number,$gettopcashcno->cnote_serial,$virutaledit));
       }
          //fetch v_flag for cnote
          if(isset($_POST['vba_stn'])){
            echo getvflagofcno($db,$_POST['vcno'],$_POST['vba_stn'],$_POST['vcashorigin']);
          }
          function getvflagofcno($db,$vcno,$vba_stn,$vcashorigin){
            $cashorigin = $vcashorigin;
              if($cashorigin=='gl'){
                $cash_condition = " AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD' AND NOT cnote_serial ='COD'";
              }else{
                $cash_condition = " AND cnote_serial='$cashorigin'";
              }
            $gettopcashcno = $db->query("SELECT TOP(1) cnote_number,(select v_flag from groups where cate_code=cnotenumber.cnote_serial and stncode=cnotenumber.cnote_station) as vflag FROM cnotenumber WITH (NOLOCK) WHERE cnote_status='Allotted' AND cust_code IS NULL AND cnote_station='$vba_stn' AND cnote_number='$vcno' $cash_condition ORDER BY cnotenoid")->fetch(PDO::FETCH_OBJ);
            //return json_encode($gettopcashcno);
            return $gettopcashcno->vflag;
          }
          //fetch book name on entering cust_code
          if(isset($_POST['bk_code'])){
            $bk_code = $_POST['bk_code'];
            $bk_stn = $_POST['bk_stn'];
            $bookname = $db->query("SELECT book_name FROM bcounter WITH (NOLOCK) WHERE book_code='$bk_code' AND stncode='$bk_stn'")->fetch(PDO::FETCH_OBJ);
            echo $bookname->book_name;
          }
          //fetch book name on entering cust_code
          if(isset($_POST['bk_name'])){
            $bk_name = $_POST['bk_name'];
            $bk_stn2 = $_POST['bk_stn2'];
            $bookcode = $db->query("SELECT book_code FROM bcounter WITH (NOLOCK) WHERE book_name='$bk_name' AND stncode='$bk_stn2'")->fetch(PDO::FETCH_OBJ);
            echo $bookcode->book_code;
          }
          
          //verify cnotenumber for cash billing
          if(isset($_POST['cno_cash_br']))
          {
            $cno_cash_br = $_POST['cno_cash_br'];
            $cno_cash_stn = $_POST['cno_cash_stn'];
            $cash_cno = $_POST['cash_cno'];
            $cno_cash_cc = $_POST['cno_cash_cc'];
            $cashorigin = $_POST['cashorigin'];

            if($cashorigin=='gl'){
              $cash_condition = " AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD'";
            }else{
              $cash_condition = " AND cnote_serial='$cashorigin'";
            }
            if(!empty($cno_cash_cc)){
              $cc = $db->query("SELECT count(*) as countd FROM collectioncenter WHERE stncode='$cno_cash_stn' AND cc_code='$cno_cash_cc'")->fetch(PDO::FETCH_OBJ);
                if($cc->countd>0){
                $collcentercondition_cash = " AND cust_code='$cno_cash_cc' ";
                }
                else{
                  $collcentercondition_cash = " AND cust_code IS NULL";
                }
            }
            else{
              $collcentercondition_cash = " AND cust_code IS NULL";
            }
            $cashverifycno3 = $db->query("SELECT * FROM cnotenumber WHERE cnote_station='$cno_cash_stn' AND br_code='$cno_cash_br' AND cnote_number='$cash_cno' AND cnote_status  IN ('Allotted','Billed') $collcentercondition_cash $cash_condition");
            echo $cashverifycno3->rowCount();
          }
          if(isset($_POST['op_cno'])){
            $op_cno = $_POST['op_cno'];
            $op_stn = $_POST['op_stn'];
            $op_cst = $_POST['op_cst'];
            $highlight = "style='background:yellow'";
            $get_opcnotes = $db->query("SELECT cnote_number,tdate,opwt,opdest,opmode,br_code FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$op_stn' AND (cust_code='$op_cst' OR cnote_number='$op_cno') AND cnote_status='Allotted' AND NOT opdest IS NULL ORDER by CASE WHEN cnote_number = '$op_cno' THEN 0 ELSE 1 END");
            while($oprow = $get_opcnotes->fetch(PDO::FETCH_OBJ)){
              if($oprow->cnote_number==$op_cno){
                $highlight = "style='background:yellow'";
              }else{$highlight='';}
              echo "<tr $highlight>
              <td>$oprow->tdate</td>
              <td>$oprow->cnote_number</td>
              <td>$oprow->opwt</td>
              <td>$oprow->opdest</td>
              <td>$oprow->opmode</td>
              <td>$oprow->br_code</td>
                  </tr>";
            }
          }
          //fetch billed datum of billed cnotes in credit
          if(isset($_POST['billcnote_cno'])){
            $billcnote_cno = $_POST['billcnote_cno'];
            $billcnote_stn = $_POST['billcnote_stn'];
            $billcnote_sr = $_POST['billcnote_sr'];
            if($billcnote_sr=='gl'){
            $billedcnotes = $db->query("SELECT TOP 1 * FROM credit WITH (NOLOCK) WHERE comp_code='$billcnote_stn' AND cno='$billcnote_cno' AND (SELECT grp_type FROM groups WHERE stncode=credit.comp_code AND cate_code=credit.cnote_serial)='gl'")->fetch(PDO::FETCH_OBJ);
            }
            else{
              $billedcnotes = $db->query("SELECT TOP 1 * FROM credit WITH (NOLOCK) WHERE comp_code='$billcnote_stn' AND cno='$billcnote_cno' AND cnote_serial='$billcnote_sr'")->fetch(PDO::FETCH_OBJ);
            }
            echo json_encode($billedcnotes);
          }    
          //check the entered cnote is alloted to entered customer for save
          if(isset($_POST['verifyentrycustomer'])){
            $verifyentrycustomer = $_POST['verifyentrycustomer'];
            $verifyentrystation = $_POST['verifyentrystation'];
            $verifyentrycnote = $_POST['verifyentrycnote'];

            $verifyentrycnotes = $db->query("SELECT * FROM cnotenumber WITH (NOLOCK) WHERE cnote_number='$verifyentrycnote' AND cnote_station='$verifyentrystation' AND cust_code='$verifyentrycustomer'");
            if($verifyentrycnotes->rowCount()==0){
              //get customer's branch
              $verifyentrybranch = $db->query("SELECT br_code FROM customer WHERE cust_code='$verifyentrycustomer' AND comp_code='$verifyentrystation'")->fetch(PDO::FETCH_OBJ);
              $verifyentryforbranchcnotes = $db->query("SELECT * FROM cnotenumber WITH (NOLOCK) WHERE cnote_number='$verifyentrycnote' AND cnote_station='$verifyentrystation' AND br_code='$verifyentrybranch->br_code' AND cust_code IS NULL");
              echo $verifyentryforbranchcnotes->rowCount();
            }
            else{
              echo $verifyentrycnotes->rowCount();
            }
          }
          //check the entered cnote is alloted to entered customer for update
          if(isset($_POST['uverifyentrybranch'])){
             echo verifycustomerupdate($db,$_POST['uverifyentrystation'],$_POST['uverifyentrycnote'],$_POST['uverifyentrybranch'],$_POST['uverifyentrycustomer']);
            }
          function verifycustomerupdate($db,$uverifyentrystation,$uverifyentrycnote,$uverifyentrybranch,$uverifyentrycustomer){
            $verifyentrystation = $uverifyentrystation;
            $verifyentrycnote = $uverifyentrycnote;
            $verifyentrybranch = $uverifyentrybranch;

            $validatebr_cno = $db->query("SELECT br_code FROM cnotenumber WHERE cnote_number='$verifyentrycnote' AND cnote_station='$verifyentrystation'")->fetch(PDO::FETCH_OBJ);
            if($validatebr_cno->br_code==$verifyentrybranch){
              $checkowncnote = $db->query("SELECT * FROM cnotenumber WHERE cnote_number='$uverifyentrycnote' AND cnote_station='$uverifyentrystation' AND (cust_code='$uverifyentrycustomer' OR (allot_po_no like 'AO%' AND br_code='$uverifyentrybranch'))");
            if($checkowncnote->rowCount()==0){
            //check entered br type
            $chkebrtype = $db->query("SELECT branchtype FROM branch WHERE br_code='$verifyentrybranch' AND stncode='$verifyentrystation'")->fetch(PDO::FETCH_OBJ);
            //get branch of cnote cnote_number
            $br_of_cno = $db->query("SELECT br_code FROM cnotenumber WITH (NOLOCK) WHERE cnote_number='$verifyentrycnote' AND cnote_station='$verifyentrystation'")->fetch(PDO::FETCH_OBJ);
            $chkabrtype = $db->query("SELECT branchtype FROM branch WHERE br_code='$br_of_cno->br_code' AND stncode='$verifyentrystation'")->fetch(PDO::FETCH_OBJ);
            if(trim($chkebrtype->branchtype)=='D' & trim($chkabrtype->branchtype)=='D')
            {
              return 1;
            }
            else{
              return 0;
            }
            }
            else{
              return 1;
            }
           }
           else{
            return 0;
           }
          }

          //check special serial amount is valid for customer
          if(isset($_POST['cg_serial'])){
           echo checkserialisvalidforcustomer($db,$_POST['cg_serial'],$_POST['cg_cust'],$_POST['cg_stn']);
          }
          function checkserialisvalidforcustomer($db,$cg_serial,$cg_cust,$cg_stn){
            $ratecustomer = $db->query("SELECT rate FROM customer WHERE cust_code='$cg_cust' AND comp_code='$cg_stn'")->fetch(PDO::FETCH_OBJ);
            if(empty($ratecustomer->rate)){
               $count = $db->query("SELECT ".$cg_serial."_per_kg FROM customer WHERE cust_code='$cg_cust' AND comp_code='$cg_stn' AND ".$cg_serial."_per_kg>=0");
               return $count->rowCount();
            }
            else{
             $count = $db->query("SELECT ".$cg_serial."_per_kg FROM customer WHERE cust_code='$ratecustomer->rate' AND comp_code='$cg_stn' AND ".$cg_serial."_per_kg>=0");
             return $count->rowCount();
            }
             
           }
          //check customer lock status
          if(isset($_POST['lock_custcode'])){
            echo check_customer_lockstatus($db,$_POST['lock_custcode'],$_POST['lock_compcode']);
          }
          function check_customer_lockstatus($db,$lock_custcode,$lock_compcode){
            $flag = $db->query("EXEC Invoice_Threshold @billing_customer='$lock_custcode',@billing_company='$lock_compcode'")->fetch(PDO::FETCH_OBJ);
            return $flag->flag;
          }

          //get customer data json
          if(isset($_POST['getjsoncust_br'])){
            echo getcustomersjson($db,$_POST['getjsoncust_br'],$_POST['getjsoncust_stn']);
          }
          function getcustomersjson($db,$getjsoncust_br,$getjsoncust_stn){
            $customers = $db->query("SELECT cust_code,cust_name FROM customer WHERE comp_code='$getjsoncust_stn' AND br_code='$getjsoncust_br' AND cust_active='Y'");
            $json = [];
            while($row = $customers->fetch(PDO::FETCH_OBJ)){
              $json[trim($row->cust_code)] = trim($row->cust_name);
            }
            return json_encode($json);
          }

          //get customer charges
          if(isset($_POST['getcharges_customer'])){
            echo getextrachargesflag($_POST['getcharges_customer'],$_POST['getcharges_station'],$db);
          }
          function getextrachargesflag($getcharges_customer,$getcharges_station,$db){
            $query = $db->query("SELECT packing_charges,insurance_charges,other_charges FROM customer WHERE comp_code='$getcharges_station' AND cust_code='$getcharges_customer'")->fetch(PDO::FETCH_OBJ);
            return json_encode(array("packing_charges"=>$query->packing_charges,"insurance_charges"=>$query->insurance_charges,"other_charges"=>$query->other_charges));
          }

          //get shipper and consignee details
          if(isset($_POST['shpmobile'])){
            echo getshipperconsigneedata($db,$_POST['shpmobile']);
          }
          function getshipperconsigneedata($db,$shpmobile){
            $data = $db->query("SELECT * FROM consignee WHERE consignee_mobile='$shpmobile'")->fetch(PDO::FETCH_OBJ);
            $data1 = $db->query("SELECT  gst FROM cash WHERE (shp_mob='$shpmobile' OR consignee_mobile='$shpmobile') AND gst !='' AND gst IS NOT NULL ORDER BY date_added DESC")->fetch(PDO::FETCH_OBJ);
            if($data || $data1){
              return json_encode(array($data,$data1));
            }
            else{
              return false;
            }
          }
           //get cnote serial for billing entry
           if(isset($_POST['serial_cno'])){
            echo getcnote_serial($db
            ,$_POST['serial_cno']
            ,$_POST['serial_comp_code']
            ,$_POST['serial_category']);
          }
          function getcnote_serial($db,$cno,$comp_code,$category){
            if($category=='gl' || empty($category) || trim($category)==trim($comp_code)){
              $serial = $db->query("SELECT cnote_serial FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND (SELECT grp_type FROM groups WHERE stncode=cnotenumber.cnote_station AND cate_code=cnotenumber.cnote_serial)='gl'")->fetch(PDO::FETCH_OBJ);
            }
            else{
              $serial = $db->query("SELECT cnote_serial FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$comp_code' AND cnote_number='$cno' AND cnote_serial='$category'")->fetch(PDO::FETCH_OBJ);
            }
            return $serial->cnote_serial;
          }
          //fetch data for operation cnotes
          if(isset($_POST['oprow_cno'])){
           echo getrowopdata($db,$_POST['oprow_cno']
                            ,$_POST['oprow_stn']
                            ,$_POST['oprow_cst']
                            ,$_POST['oprow_pod']
                            );
          }
          function getrowopdata($db,
          $oprow_cno,
          $oprow_stn,
          $oprow_cst,
          $oprow_pod){
            if($oprow_pod=='gl' || empty($oprow_pod) || trim($oprow_pod)==trim($oprow_stn)){
              $pod_condition = "AND NOT cnote_serial ='PRO' AND NOT cnote_serial ='PRC' AND NOT cnote_serial ='PRD' AND NOT cnote_serial ='COD'";
            }else{
              $pod_condition = "AND cnote_serial='$oprow_pod'";
            }
            $get_opcnotes = $db->query("SELECT cnote_number,tdate,opwt,opdest,opmode,br_code FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$oprow_stn' AND cnote_number='$oprow_cno' AND cnote_status='Allotted' AND NOT opdest IS NULL $pod_condition")->fetch(PDO::FETCH_OBJ);
            return json_encode($get_opcnotes);
          }

                //load data for pagination in destination table
      if(isset($_POST['dtbquery'])){
        echo select10rows($db,$_POST['dtbquery'],$_POST['dtbpageno'],$_POST['dtbcolumns']);
        }
		  function select10rows($db,$dtbquery,$dtbpageno,$dtbcolumn2){
			  $dtbcolumns = explode(",",$dtbcolumn2);
			  $t10query = $db->query($dtbquery." OFFSET ".$dtbpageno." ROWS  FETCH NEXT 10 ROWS ONLY");
			  $datum = array();
			  $index = 0;
			  while($t10row=$t10query->fetch(PDO::FETCH_OBJ)){$index++;
				  $data = array();
					foreach($dtbcolumns as $dtbcolumn){
				  //console_log($dtbcolumn);
					  $data[$dtbcolumn] = $t10row->$dtbcolumn;
					}
				$datum[$index]=$data;
			  }
			  return json_encode($datum);
		  }
      //load data for pagination in destination table

      //get cash cnote details for edit
      if(isset($_POST['cashpodorigin'])){
         echo getcashcnotedetail($db,$_POST['cashcno'],$_POST['cash_stn'],$_POST['cashpodorigin']);
       }
       function getcashcnotedetail($db,$cashcno,$cash_stn,$cashpodorigin){
         if($cashpodorigin=='gl' || empty($cashpodorigin) || trim($cashpodorigin)==trim($cash_stn)){
           $pod_condition = " AND pod_origin NOT IN ('PRO','PRC','PRD','COD')";
         }else{
           $pod_condition = " AND pod_origin='$cashpodorigin'";
         }
         $get_opcnotes = $db->query("SELECT *,(select TOP 1 cc_name FROM collectioncenter WHERE cc_code=cash.cc_code AND comp_code=cash.comp_code) cc_name,(SELECT count(*) FROM denomination where col_date=cash.tdate AND comp_code=cash.comp_code and br_code=cash.br_code) den_counts FROM cash WITH (NOLOCK) WHERE comp_code='$cash_stn' AND cno='$cashcno' $pod_condition")->fetch(PDO::FETCH_OBJ);
         return json_encode($get_opcnotes);
       }
       
	  if(isset($_POST['action']) && $_POST['action'] =='focousout'){
 
        $billcnote_stn = $_POST['billcnote_stn'];
        $cnote_number = $_POST['cno1'];
        $type = $_POST['cnote_type'];
    
          $virtual_cno = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE comp_code='$billcnote_stn' AND cno='$cnote_number'")->fetch(PDO::FETCH_OBJ);
    
            



        

      }
	      
	   if(isset($_POST['action']) && $_POST['action'] =='cnotenumber'){
        $cnotenumber = $_POST['cnotenumber'];
        $entrytype = $_POST['entrytype'];
        $datefield = $_POST['datefield'];
		  $companycode = $_POST['compcode'];
        $printed = $db->query("SELECT Dup_challan FROM $entrytype WHERE cno='$cnotenumber' AND cnote_type='Virtual'");
        $print =  $printed->fetch(PDO::FETCH_OBJ);
        $dupchallan = $print->Dup_challan;
        $billdate = $db->query("SELECT cast(date_added as date) AS date FROM $entrytype WHERE cno='$cnotenumber' AND cnote_type='Virtual' and (Dup_challan = 'not printed' OR Dup_challan IS NULL) and comp_code = '$companycode'");
         $rowdate =  $billdate->fetch(PDO::FETCH_OBJ);
         $bildat = $rowdate->date;
        if($entrytype=='cash'){
         $duplicatechallan = $db->query("SELECT cnote_type,dup_challan FROM cash WITH (NOLOCK) WHERE cno='$cnotenumber' AND cnote_type='Virtual' and (Dup_challan = 'not printed' OR Dup_challan IS NULL)")->fetch(PDO::FETCH_OBJ);
        if($bildat==$datefield && $entrytype=='cash'){
          $update = $db->query("UPDATE cash SET Dup_challan='Printed' WHERE cno='$cnotenumber' AND cnote_type='Virtual'");
        }
        }else if($entrytype=='credit'){
          $duplicatechallan = $db->query("SELECT cnote_type,dup_challan,cust_code FROM credit WITH (NOLOCK) WHERE cno='$cnotenumber' AND cnote_type='Virtual' and (Dup_challan = 'not printed' OR Dup_challan IS NULL)")->fetch(PDO::FETCH_OBJ);
          if($bildat==$datefield && $entrytype=='credit'){
            $update = $db->query("UPDATE credit SET Dup_challan='Printed' WHERE cno='$cnotenumber' AND cnote_type='Virtual'");
          }
        }
       
         echo json_encode(array($duplicatechallan,$rowdate->date,$dupchallan)); 
       }      
        if(isset($_POST['branchcode']))
       {
         $branch8  = $_POST['branchcode'];
         $comp8    = $_POST['comp8'];
        $name = $db->query("SELECT * FROM cash WHERE cno='$branch8'");
         $getautoppl8 = $db->query("SELECT * FROM cash WHERE cno='$branch8'");
         while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
         {
           $arrayvalues[] = $getautoppl8row['cnote_type'];
         }
         echo json_encode(array($arrayvalues,$name->cnote_type));
       }
 
 
       if(isset($_POST['creditcnote']))
       {
         $branch8  = $_POST['creditcnote'];
         $comp8    = $_POST['comp8'];
        $name = $db->query("SELECT * FROM credit WHERE cno='$branch8'");
         $getautoppl8 = $db->query("SELECT * FROM credit WHERE cno='$branch8'");
         while($getautoppl8row = $getautoppl8->fetch(PDO::FETCH_ASSOC))
         {
           $arrayvalues[] = $getautoppl8row['cnote_type'];
         }
         echo json_encode(array($arrayvalues,$name->cnote_type));
       }

       //Pincode retain in Consignee function

       if(isset($_POST['action']) && $_POST['action'] =='getpincode'){
        $pincode = $_POST['pincode'];
        $sql2=$db->query("SELECT pincode FROM pincode  where pincode='$pincode'")->fetch(PDO::FETCH_OBJ);
        echo json_encode(array($sql2->pincode));
       }

       // Get Vehicle Number in day wise
       if(isset($_POST['action']) && $_POST['action'] =='getvehicle_number'){
        $tdate = $_POST['tdate'];
        $gstin = $_POST['gstin'];
        $mode = $_POST['mode'];
        $wt = $_POST['wt'];
        $v_wt = $_POST['v_wt'];
        $weight = max($wt,$v_wt);
        $destcode	= explode('-',$_POST['destcode'])[0];
        $stationcode = $_POST['stationcode'];
        $desttbverify = $db->query("SELECT * FROM destination WHERE comp_code='$stationcode' AND dest_code='$destcode' OR dest_name='$destcode'");
        if($desttbverify->rowCount()==0){
          $dest_verify = $db->query("SELECT * FROM pincode WHERE pincode='$destcode' OR city_name='$destcode' OR dest_code='$destcode'"); 
          if($dest_verify->rowCount()==0){
            $dest_code  	 = $destination;        
          }
          else{
            $rowdest_verify = $dest_verify->fetch(PDO::FETCH_OBJ);    
            $dest_code  	 = $rowdest_verify->dest_code;
          }
        }
        else{
          $rowdestntn = $desttbverify->fetch(PDO::FETCH_OBJ);
          $dest_code  = $rowdestntn->dest_code;
        }
        $company = $db->query("SELECT * FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
        $gta_weight = 0.5;
        if($company->gta=='Y' && empty($gstin) && ($gta_weight<=$weight) && $mode=='ST'){
          $getvehicel_1_2 = $db->query("SELECT * FROM vehicle WHERE comp_code='$stationcode' AND dest_code='$dest_code'")->fetch(PDO::FETCH_OBJ);
          $vechicle_one = $getvehicel_1_2->vehicle_1;
          $vechicle_two = $getvehicel_1_2->vehicle_2;
            //vechicel 1 is mon,wed,fri
            $day = $tdate;
            $getday = date('D', strtotime($day));
            if($getday=='Mon'){
              $vechicle_no = $vechicle_one;
            }
            else if($getday=='Wed'){
              $vechicle_no = $vechicle_one;
            }
            else if($getday=='Fri'){
              $vechicle_no = $vechicle_one;
            }
            //vehicle two
            else if($getday=='Tue'){
              $vechicle_no = $vechicle_two;
            }
            else if($getday=='Thu'){
              $vechicle_no = $vechicle_two;
            }
            else if($getday=='Sat'){
              $vechicle_no = $vechicle_two;
            }
            else if($getday=='Sun'){
              $vechicle_no = $vechicle_two;
            }
  
           if($getday=='Mon' || $getday=='Wed' || $getday=='Fri'){
            $getvehiclenumber = $db->query("SELECT * FROM vehicle WHERE comp_code='$stationcode' AND dest_code='$dest_code' AND vehicle_1='$vechicle_no'")->fetch(PDO::FETCH_OBJ);
            $get_vechicle = $getvehiclenumber->vehicle_1;
           }else{
            $getvehiclenumber = $db->query("SELECT * FROM vehicle WHERE comp_code='$stationcode' AND dest_code='$dest_code' AND vehicle_2='$vechicle_no'")->fetch(PDO::FETCH_OBJ);
            $get_vechicle = $getvehiclenumber->vehicle_2;
           }
         
           
    
           
        }else{

        }
    

        echo json_encode(array($get_vechicle,$getday,$company->gta,$gstin,$mode,$weight));

       }
       if(isset($_POST['action']) && $_POST['action'] =='getcc_enable'){
        $branch = $_POST['branch'];
        $stationcode = $_POST['stationcode'];

        $getcc = $db->query("SELECT * FROM branch WHERE stncode='$stationcode' AND br_code='$branch'")->fetch(PDO::FETCH_OBJ);

        echo $getcc->cc_enable;
       //echo json_encode(array($getcc->cc_enable)); 
       }

       if(isset($_POST['action']) && $_POST['action'] =='customer_type'){
        $stationcode = $_POST['station'];
        
        $cus = $_POST['cus'];

        $gettype = $db->query("SELECT * FROM customer WHERE cust_code='$cus' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);

        echo $gettype->cust_type;
       //echo json_encode(array($getcc->cc_enable)); 
       }

function console_log($output, $with_script_tags = true) {
  $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
  ');';
  if ($with_script_tags) {
      $js_code = '<script>' . $js_code . '</script>';
  }
  echo $js_code;
}