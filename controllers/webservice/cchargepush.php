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

    class cchargepush extends My_controller
    {
        public function index()
        {
            $this->view->title = __SITE_NAME__ . ' - cchargepush';
        }
    }


    //get opdata
    $getopdata = $db->query("SELECT TOP(1) * FROM opdata");
    while($oprow = $getopdata->fetch(PDO::FETCH_OBJ)){
         $chargecustomercode = trim($oprow->origin).'TR';
     echo "<table>
     <tr>
     <th>Company</th>
     <th>Origin</th>
     <th>Cno</th>
     <th>Destn</th>
     <th>Weight</th>
     <th>Mode code</th>
     <th>Amount</th>
     </tr>
     <tr>
          <td>$oprow->comp_code</td>
          <td>$oprow->origin</td>
          <td>$oprow->cno</td>
          <td>$oprow->destn</td>
          <td>$oprow->wt</td>
          <td>AT</td>
          <td>".ratecalculation($db,'CRO',$oprow->comp_code,'8','3.250','ST')."</td>
     </tr>
     </table>";
    }

    //rate calculation 
function ratecalculation($db,$ccustomer,$creditorigin,$destcode_ofcredit,$creditweight,$creditmode){
     //  $ccustomer         = $_POST['customer'];
     //  $creditorigin      = $_POST['origin'];
     //  $destcode_ofcredit = $_POST['destination'];
     //  $creditweight      = $_POST['weight'];

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
     
         $pod_amt         = 'gl';
    
          if($pod_amt=='gl'){
             $pro_rate = '0';
          }
          else if($pod_amt=='PRO' && $creditweight>1){
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
          

      //end special cnotes
      $creditpcs         = '1';
     // $creditmode         = $_POST['mode'];
      
      $getcontract  = $db->query("SELECT TOP 1 con_code FROM rate_custcontract WHERE cust_code='$creditcustomer' AND origin='$creditorigin' AND destn='$creditdestination' AND mode='$creditmode' ORDER BY custcontid DESC");
      $rowcontract  = $getcontract->fetch(PDO::FETCH_ASSOC);
      $contractcode = $rowcontract['con_code'];
      //validate weight
      $weightval = $db->query("SELECT MAX (upperwt) as maxweight FROM rate_contractdetail WHERE con_code='$contractcode'");
      $rowmaxweight = $weightval->fetch(PDO::FETCH_OBJ);
      if($rowmaxweight->maxweight<$creditweight && $creditweight>0){
          return "Maximum";
      }
      //end
      $getamount    = $db->query("SELECT * FROM rate_contractdetail WHERE con_code='$contractcode' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'");
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
        $getfirst_rate = $db->query("SELECT rate,onadd FROM rate_contractdetail WHERE con_code='$contractcode' AND lowerwt <= '$first_rate' AND upperwt >= '$first_rate'");
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

        $getrate2 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '.5'");
        $getratewt1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '1'");
          if($getratewt1->rowCount()!=0){
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
              $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
              $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
              $getrate2row = $getratewt1->fetch(PDO::FETCH_OBJ);
              $rate2 = $getrate2row->rate;
          }
          else if($getrate2->rowCount()!=0){
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
            $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
            $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
            $getrate2row = $getrate2->fetch(PDO::FETCH_OBJ);
            $rate2 = $getrate2row->rate;
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
             $getrate1 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND lowerwt <= '$creditweight' AND upperwt >= '$creditweight'")->fetch(PDO::FETCH_OBJ);
             $rate1 = ($roundvalue2/$rowamount['onadd'])*$getrate1->rate;
             $getrate3 = $db->query("SELECT rate FROM rate_contractdetail WHERE con_code='$contractcode' AND upperwt = '.25'")->fetch(PDO::FETCH_OBJ);
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