<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

error_reporting(0);
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'creditimport';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;

    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return $access='False';
        }
        else{
            return $access='True';
        }
}

if(authorize($db,$user)=='False'){

    echo "<div class='alert alert-danger alert-dismissible'>
         
            <h4><i class='icon fa fa-times'></i> Alert!</h4>
            You don't have permission to access this Page.
            </div>";
            exit;
  }
  
  $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
  $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
  $rowuser = $datafetch['last_compcode'];
  if(!empty($rowuser)){
       $stationcode = $rowuser;
  }
  else{
      $userstations = $datafetch['stncodes'];
      $stations = explode(',',$userstations);
      $stationcode = $stations[0];     
  } 

  include 'vendor/bootstrap/simplexlxs.php';

?>
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


<div id="col-form1" class="col_form lngPage col-sm-25">
<div class="au-breadcrumb m-t-75" style="margin-bottom: -5px;">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="au-breadcrumb-content">
                                <div class="au-breadcrumb-left">
                                    <span class="au-breadcrumb-span"></span>
                                    <ul class="list-unstyled list-inline au-breadcrumb__list">
                                    <li class="list-inline-item active">
                                        <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                        </li>                                        
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'creditimportlist'; ?>">Credit Upload list</a>
                                      </li>
                                    </ul>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="alert-primary" role="alert">

                <?php 
                    if ( isset($_GET['success']) && $_GET['success'] == 1 )
                {
                        echo 
                "<div class='alert alert-success alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                Record Added successfully.
                </div>";
                }

                ?>

                </div>
                <form method="POST" action="<?php echo __ROOT__ ?>creditimport">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Import</p>
              <div class="align-right" style="margin-top:-30px;">
              <input type="text" name="stncode" class="hidden" value="<?=$stationcode;?>">
              <input type="text" name="username" class="hidden" value="<?=$user;?>">
                    <button type="submit" class="btn btn-success btnattr_cmn" id="uploaddata" name="uploaddata">
                    <i class="fa  fa-upload"></i>&nbsp; Approve
                    </button>
                <a style="color:white" href="<?php echo __ROOT__ ?>creditimportlist"><button type="button" class="btn btn-danger btnattr_cmn" >
                <i class="fa fa-times" ></i>
                </button></a>

                </div>
        </div>
        <div style="background:#fff;border:1px solid var(--heading-primary);">
    <table class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th>
               S.no
              </th>            
               <th>Origin</th>
               <th>Tdate</th>
               <th>Branch Code</th>
               <th>Cust code</th>
               <th style='width:100px'>C No</th>
               <th>Destn</th>              
               <th>Wt</th> 
               <th>Mode</th>
               <th>Amt</th>
               <th>Status</th>
              </tr>
        </thead>
              <tbody>
                  <?php 

                    if(isset($_FILES["importfile"])){
                        $file = $_FILES['importfile']['tmp_name'];
                        $file_ext = pathinfo($_FILES["importfile"]['name'], PATHINFO_EXTENSION);
                        //if ( $xlsx = SimpleXLSX::parse($file) ) {
                            // $xlsxrow = $xlsx->rows();
                            //  
                            //new
                          // echo   $file_ext;
                          // die;
                        if($file_ext == 'xlsx'){
                          $xlsx = SimpleXLSX::parse($file);
                          $xlsxrow = $xlsx->rows();
                        }
                        else if($file_ext == 'csv'){
                        ini_set('auto_detect_line_endings', TRUE);

                        $header = '';
                        $xlsxrow = array_map('str_getcsv', file($file));
                        }
                        //$header = array_shift($xlsxrow);
                        //$csv = array();
                            //end
                            $r=0;
                          foreach($xlsxrow as $rows){
                            $r++;
                             if($r==1){
                        
                             }else{
                                
                            echo "<tr style='width:100%'><td>".($r-1)."</td>";
                                $c=0;
                            foreach($rows as $data){
                                //echo print_r($rows)."<br>";
                                if($c>=9){

                                }
                                else{
                                $c++;
                                echo "<td style='width:150px'><input type='text' readonly name='box".$c."[]' class='form-control' value='$data' style='border:none;background:transparent!important'></td>";
                                }
                               }
                               
                               echo "<td><input type='text' readonly name='box".($c+1)."[]' class='form-control' value='".$camount = calculateamount($db,$rows[3],$rows[0],$rows[5],$rows[6],$rows[7],'gl')."' style='border:none;background:transparent!important'></td>";
                               if($rows[0]!=$stationcode || $camount==0 || empty($camount)){
                                   $status = 'Invalid';
                               }
                               else{
                                   $status = 'Valid';
                               }
                               echo "<td><input type='text' readonly name='box".($c+2)."[]' class='form-control' value='$status' style='border:none;background:transparent!important'></td></tr>";
                            }
                        }
                        // } else {
                        //   echo SimpleXLSX::parse_error();
                        // }  
                         }          
   

//Calculate Amount for Credit
function calculateamount($db,$custcode,$r_origin,$r_destn,$r_weight,$r_mode,$r_pod_amt)
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
 
	$pod_amt         = $r_pod_amt;

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
                      ?>

                   
                 </tbody>
            </table>
              </div>
              </div>
        </form>
</section>
