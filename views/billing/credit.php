<!-- TPC - Credit Form -->
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $module = 'credit';
    $getusergrp = $db->query("SELECT group_name FROM users WITH (NOLOCK) WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;

    $verify_user = $db->query("SELECT module FROM users_group WITH (NOLOCK) WHERE group_name='$user_group' AND module='$module'");
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
 
  function delete($db,$user){
    $module = 'creditdelete';
    $getusergrp = $db->query("SELECT group_name FROM users WITH (NOLOCK) WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;
  
    $verify_user = $db->query("SELECT module FROM users_group WITH (NOLOCK) WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'swal("You Dont Have Permission to Delete this Record!")';
        }
        else{
            return "deletequeue(this.id,this.value)";
        }
  }

function loadmode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM mode WITH (NOLOCK) ORDER BY mid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option value="'.$row->mode_code.'">'.$row->mode_code.'</option>';
         }
         return $output;    
        
    }
error_reporting(0);

    $getuserstation = $db->query("SELECT * FROM users WITH (NOLOCK) WHERE user_name='$user'");
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
    if( isset($_GET['cust_code'])){
    $getpod = $_GET['podorigin'];
    }
    else{
        $getpod = '';
    }

function podorigin($db,$stationcode,$getpod)
    {
        $output='';
        $query = $db->query("SELECT * FROM groups WITH (NOLOCK) WHERE grp_type='sp' AND stncode='$stationcode'");
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code 
        if($row->cate_code==$getpod){   
            $output .='<option selected value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
        }
        else{
            $output .='<option value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
        }
        }
         return $output;    
        
    }
    $lock_status = 0;

    $br_code = "";
    $br_name  = "";
 //branch filter conditions
 $sessionbr_code = $datafetch['br_code'];
    if(!empty($sessionbr_code)){
    $br_code = $sessionbr_code;
    $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
    $br_name     = $fetchbrname->br_name;
    }
 //branch filter conditions

    if(isset($_GET['cust_code']))
    {
        $cust_code   = $_GET['cust_code'];
        $fetchcustomer = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE cust_code='$cust_code' AND comp_code='$stationcode'");
        $rowcustomer = $fetchcustomer->fetch(PDO::FETCH_ASSOC);
        $cust_name   = $rowcustomer['cust_name'];
        $br_code     = $rowcustomer['br_code'];
        $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$br_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
        $br_name     = $fetchbrname->br_name;
        $company     = $rowcustomer['comp_code'];
        $con_name    = $_GET['con_name'];
        
        $con_addr    = $_GET['con_addr'];
        $con_mobile  = $_GET['con_mobile'];
        $con_pin     = $_GET['con_pin'];
        $ch_consignee  = $_GET['ch_consignee'];
        if($getpod=='gl'){
            $podfilter =  "AND cnote_serial NOT LIKE 'PRO' AND cnote_serial NOT LIKE 'PRC' AND cnote_serial NOT LIKE 'PRD'";
        }
        else{
            $podfilter = "AND cnote_serial = '$getpod'";
        }
        // $fetchcnotenumber = $db->query("SELECT TOP ( 1 ) cnote_number FROM cnotenumber WHERE cnote_station='$company' AND br_code='$br_code' AND cust_code='$cust_code' AND cnote_status='Allotted' $podfilter ORDER BY cnotenoid");
        // $rowcnotenumber = $fetchcnotenumber->fetch(PDO::FETCH_ASSOC);
        $cno3 = $_GET['cnotenumber']+1;
        // $verifycno = $db->query("SELECT TOP(1) cnote_number,cnote_serial FROM cnotenumber WITH (NOLOCK) WHERE cust_code='$cust_code' AND cnote_station='$company' AND cnote_status='Allotted' AND cnote_number='$cno3'");
        // if($verifycno->rowCount()==0){
        //     $cnote_number= "";
        //     $cnote_serial= "";
        // }
        // else{
            //$rowverifycno = $verifycno->fetch(PDO::FETCH_OBJ);
            $cnote_number= $_GET['cnotenumber']+1;
        //     $cnote_serial= $rowverifycno->cnote_serial;
        // }
        $bdate = $_GET['bdate'];
        //update lock status
        $lock_status = check_customer_lockstatus($db,$cust_code,$stationcode);

        //cnotetype
        $cnote_type = base64_decode($_GET['cnotetype']);

        $cnodetailsofprev = $db->query("SELECT TOP(1) cnote_number,cnote_serial,(select v_flag from groups where cate_code=cnotenumber.cnote_serial and stncode=cnotenumber.cnote_station) as vflag FROM cnotenumber WITH (NOLOCK) WHERE cust_code='$cust_code' AND cnote_station='$company' AND cnote_number='".$_GET['cnotenumber']."'")->fetch(PDO::FETCH_OBJ);
$challan_credit = $db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
$creditchal_1 =  $challan_credit->credit_accounts_copy;
$creditchal_2 =  $challan_credit->credit_pod_copy;
$creditchal_3 =  $challan_credit->credit_consignor_copy;
$chellanvalue = ($creditchal_1+$creditchal_2+$creditchal_3);
    }
   


 
    function check_customer_lockstatus($db,$lock_custcode,$lock_compcode){
        $flag = $db->query("EXEC Invoice_Threshold @billing_customer='$lock_custcode',@billing_company='$lock_compcode'")->fetch(PDO::FETCH_OBJ);
        return $flag->flag;
      }
?>
        <!-- Autocomplete -->
        <script type="text/javascript" src="vendor/jqueryautocomplete.js"></script>
        <link rel="stylesheet" href="vendor/jqueryautocompletestyle.css">
        <!-- end -->
   
        <head>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
 
</head>
<style>
    .input-group-addon{
    background-color: white;
}


input{
  background-color: white !important;
  color:black !important;
}
select{
    color:black !important;
}
.form-control::-webkit-input-placeholder {
    color: #696969;
}

</style> 
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  

<div id="col-form1" class="col_form lngPage col-sm-25">

        <!-- BreadCrum -->
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
                                        <a href="<?php echo __ROOT__ . 'creditlist'; ?>">Credit List</a>
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
                                  Booking Counter Record Added successfully.
                  </div>";
                  }
                  if ( isset($_GET['pindest']) )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Entered Pincode or Destination is Not Valid. Please Try Valid Destination.
                                </div>";
                  }
                  if ( isset($_GET['error']) )
                  {
                       echo 
                  "<script>swal('Cnote submission failed. Please re-submit the data !! ... Cnote प्रस्तुत करने में विफल रहा। कृपया डेटा पुनः सबमिट करें');</script>";
                  }
               ?>

             </div>
             <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>credit" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

    <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Entry Form</p>
        <div class="align-right" style="margin-top:-30px;">
    
        <a style="color:white;" href="<?php echo __ROOT__ . 'creditlist'; ?>">         
       <button type="button" class="btn btn-danger btnattr_cmn" >
        <i class="fa fa-times" ></i>
       </button></a>
            </div>
               </div>  
               <div class="content panel-body" style="background:#fff;border: 1px solid var(--heading-primary);">
               <div class="tab_form" style="border:0px;">
               <?php $value = ((empty($cnote_type))? '12' :(((($cnote_type=='Virtual'))? '12' : '8'))); ?>

  <div class="col-sm-<?=$value?>" style="border-right: 1px solid #2160a0;" id="changeformvalue">              
    <table class="table">	
           <tr>		
            <td>Company Code</td>
            <td>
                <div class="col-sm-9">
                     <input class="form-control border-form stncode" name="comp_code" type="text" id="stncode" readonly>
                </div>
            </td>              
            <td>Company Name</td>
            <td>
                <div class="col-sm-9">
                    <input class="form-control border-form " onfocusout="stnname_val(this)"  name="stnname" type="text" id="stnname" placeholder="Station Name" readonly>
                     <p id="stnname_val" class="form_valid_strings">Special Characters not allowed. </p> 
                     <p id="stnname_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>
       </tr>
           <tr>		
            <td>Branch Code</td>
            <td>
                <form method="POST" id="fetchcust">
                <div class="col-sm-9">
                <input placeholder="" class="form-control border-form br_code text-uppercase" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?php echo $br_code; ?>" name="br_code" type="text" id="br_code" autofocus>
                <div id="result"></div>
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td>Branch Name</td>
            <td>
                <div class="col-sm-9">
                <input class="form-control border-form text-uppercase" tabindex="-1" name="br_name" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?php echo $br_name; ?>" type="text" id="br_name" >
                   
                </div>
            </td>
       </tr>
        <tr>
        <td>Customer Code</td>
        <td>
        <div class="col-sm-9 " >
            <input id="cust_code" <?php if(isset($_POST["br_code"])){ echo "autofocus";  } ?> name="cust_code"  value="<?php if(isset($_GET['cust_code'])){echo $_GET['cust_code'];} ?>"  oninput="resetdestn();$('#podoption').attr('selected', true);" class="form-control input-sm cust_code text-uppercase" type="text">
            <p id="customer_validation" class="form_valid_strings">Enter Valid Customer Code</p>
            </div>
                </form>
        </td>
        <!--  -->
        <td>Customer Name</td>
        <td>
        <div class="col-sm-9">
        <input id="cust_name" name="cust_name" tabindex="-1" value="<?php if(isset($_GET['cust_code'])){ echo $cust_name; }?>" class="form-control input-sm text-uppercase" _onfocusout="cust_cnote_verify()" type="text" oninput="resetdestn();$('#podoption').attr('selected', true);">
        </div>
        </td>
        </tr>
        <tr>
        <td>Origin</td>
        <td>
        <div class="col-sm-9">
        <input id="origin" name="origin" tabindex="-1" value="" class="form-control input-sm" type="text" readonly>
            </div>
        </td>
        <td>POD Origin</td>
        <td>
        <div class="col-sm-9">
            <select id="podorigin" name="podorigin" tabindex="-1" class="form-control input-sm" onchange="calculateamount();pod_verify();checkgroupisvalid();cust_cnote_verify();checkpod();">
                <option id="podoption"></option>
                <?php echo podorigin($db,$stationcode,$getpod); ?>
            </div>
        </td>
        </tr>
        
        <tr>
          <td>Booking Date</td>
          <td>
          <div class="col-sm-9">
          <input type = "text" <?=(($cnote_type=='Virtual')?'readonly':'')?> name="bdate" class="form-control" id = "bdate" value="<?=date('d-M-Y')?>" onkeypress="return false;">
          </td>
          </div>
          <td>Booking Staff</td>
        <td>
        <div class="col-sm-9">
            <input type="text" id="bkgstaff" tabindex="-1" name="bkgstaff" class="form-control text-uppercase">
            </div>
        </td>
          </tr>
                  
        <tr>
        <!-- <td>Cnote No</td>
        <td>
        <div class="col-sm-9">
           
            </div>
        </td> -->
        <td>Mf/Ref Number</td>
        <td>
            <div class="col-sm-9">
                <input type="text" id="mf_no" tabindex="-1" name="mf_no" class="form-control text-uppercase">
            </div>
        </td>         
        <td>Other Charges</td>
        <td>
            <div class="col-sm-9">
                 <input type="number" min="0" tabindex="-1" onfocusout="($(this).val().trim()==''?this.value=0:'')" id="other_charges" name="other_charges" value="0" class="form-control" readonly>
            <div>
        </td>          
        </tr>
        <tr>
        <td>Packing Charges</td>
        <td>
            <div class="col-sm-9">
                <input type="number" min="0" tabindex="-1" id="packing_charges" readonly name="packing_charges" class="form-control" value='0' onfocusout="($(this).val().trim()==''?this.value=0:'')">
            </div>
        </td>
        <td>Insurance Charges</td>
        <td>
            <div class="col-sm-9">
                <input type="number" tabindex="-1" id="insurance_charges" readonly name="insurance_charges" class="form-control" value='0' onfocusout="($(this).val().trim()==''?this.value=0:'')">
            </div>
        </td>
        </tr>
        <tr>
        <td>Cnote Type</td> 
        <td>
            <div class="col-sm-9">
                <select id="cnote_type" tabindex="-1" name="cnote_type" class="form-control" onchange="cust_cnote_verify();verifyvirtual(this);$('#cnote_no').val('');change_form(this)">
                <option <?=(($cnote_type=='Virtual')?'Selected':'')?> value="Virtual">Virtual</option>
                    <option <?=(($cnote_type=='Regular')?'Selected':'')?> value="Regular">Regular</option>
                </select>
            </div>
        </td>
        <td>Contents</td> 
        <td>
            <div class="col-sm-9">
            <input type="text" id="contents" tabindex="-1" name="contents" class="form-control text-uppercase">

            </div>
        </td>
        </tr>
        <tr>
        <td>Declared Value</td> 
        <td>
            <div class="col-sm-9">
            <input type="number" min="0" value="0" step="0.01" id="dc_value" tabindex="-1" name="dc_value" class="form-control">

            </div>
        </td>
        <td>From Destination</td> 
        <td>
            <div class="col-sm-9">
            <input type="text" name="destination1" id="destn1" class="form-control destbox text-uppercase">
            </div>
        </td>
        </tr>
            </tbody>
              
           </table>        
 
        <div class="col-sm-12">
                                <!-- <div class="table-responsive margin-top10"> -->
                                <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field">Cnote Number</th>
                                        <th class="text-center">Destination</th>
                                        <th class="text-center" style="width: 70px;">Wt</th>
                                        <th class="text-center" style="width: 70px;">V.Wt</th>
                                        <th class="text-center" style="width: 70px;">Pcs</th>
                                        <th class="text-center invoice_fields" style="width: 190px;">Mode</th>
                                        <th class="text-center invoice_fields" style="width: 70px;">Amount</th>                                      
                                    </tr>
                                </thead>
                            
                                <tbody id="addinvoiceItem">
                                      <tr>
                                      <td id="cnotewidth"  style="<?=((empty($cnote_type))? 'width:130px;' :(((($cnote_type=='Virtual'))? 'width:130px;' : 'width:')))?>">
                                        <input type="number" <?=(($cnote_type=='Virtual')?' ':' ')?> id="cnote_no" name="cnote_no" class="form-control" value="<?php if(isset($_GET['cust_code'])){ echo $cnote_number; }?>" onfocusout="operationdata();cnoteval()">
                                        <input type="hidden" id="cnote_serial" readonly name="cnote_serial" class="form-control" value="<?php if(isset($_GET['cust_code'])){ echo $cnote_serial; }?>">
                                        <p id="cnote_no_validation" class="form_valid_strings">Please enter Cnote No.</p>
                                        <p id="cnote_no_invalid" class="form_valid_strings">Cnote No Invalid.</p>   
                                        </td>
                                         <td style="width:180px!important">
                                            <div class="autocomplete col-sm-12">
                                            <input type="text" name="destination" id="destn" class="form-control destbox text-uppercase" onchange="pincodeview(this)" onfocusout="calculateamount();checkpod();">
                                            <p id="dest_validation" class="form_valid_strings">Enter Valid Destination.</p>
                                            <p id="destnull_validation" class="form_valid_strings">Please Enter Destination.</p>
                                            </div>
                                            </td>
                                        <td>
                                            <input name="wt" id="wt" class="form-control " value="0" aria-invalid="false" type="text" pattern="[0-9]+([\.,][0-9]+)?" step="0.001" onfocusout="calculateamount();" oninput="$('#wt_validation').css('display','none');">
                                            <p id="wt_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                            <p id="max_weight" class="form_valid_strings">Weight exceed to Maximum.</p>
                                        </td>
                                        <td>
                                        <input name="v_wt" id="v_wt" class="form-control" value="0" aria-invalid="false" type="text" tabindex="-1" pattern="[0-9]+([\.,][0-9]+)?" step="0.001" onfocusout="calculateamount();" oninput="$('#wt_validation').css('display','none');">
                                            <p id="wt_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                            <p id="max_weight" class="form_valid_strings">Weight exceed to Maximum.</p>

                                        </td>
                                        <td>
                                        <input type="number" name="pcs" class="form-control" id="pcs" tabindex="-1" value="1" _onfocusout="calculateamount()">
                                            <p id="pcs_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                        </td>
                                        <td>
                                            <select name="mode" id="mode" class="form-control " onchange="calculateamount()">
                                           <?php echo loadmode($db); ?>
                                            </select>
                                        </td>
                                        <td>
                                        <input type="text" name="amount" tabindex="-1" id="amount"  class="form-control " min="0" placeholder="0">
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                            <!-- Consignee details -->
                            <div class="col-sm-12">
                <table class="table table-bordered table-hover">
                    <tr style="color: #707070;font-weight: 400;background: repeat-x #f2f2f2;">
                        <th>Consignee Details</th>
                    </tr>
                    <tr>
                    <td style="width:20%;"><div class="input-group"> <span class="input-group-addon my-addon">
                    <input type="checkbox" <?=(($ch_consignee==1)? 'checked' : '')?>  aria-label="..." value='1' id="check1" name="check_consignee">
                    </span>
                    <input type="text" oninput="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" maxlength="10" class="form-control" placeholder="Consignee Mobile" name="consignee_mob" id="consignee_mob"></div>
                </td>                       
                         <td style="width:20%"><input type="text" class="form-control text-uppercase" placeholder="Consignee Name" name="consignee" id="consignee"></td>
                       
                         <td><input type="text" class="form-control text-uppercase" placeholder="Address" name="consignee_addr" id="consignee_addr"></td>
                         <td style="width:13%"><input type="number" class="form-control" placeholder="Pincode" name="consignee_pin" id="consignee_pin"></td>
                    </tr>
                </table>
                </div>
                        <!-- Consignee details -->
                        </div>
                        <div style="padding-bottom:5px;padding-left:5px;">
                        <!-- <input type="text" class="form-control" placeholder="Consignee" style="width:40%;padding:15px;float:left"  name="consignee" id="consignee"> -->
                        <div class="align-right btnstyle">

                        <button type="button" id="addanothercredit" class="btn btn-success btnattr_cmn" onclick="addanother()" name="addanotherbillingcredit">
                        <i class="fa  fa-save"></i>&nbsp; Save & Add
                        </button>

                        <button type="button" id="addcredit" class="btn btn-primary btnattr_cmn" onclick="add()" name="addbillingcredit">
                        <i class="fa  fa-save"></i>&nbsp; Save
                        </button>

                        <button type="button" class="btn btn-default btnattr_cmn" onclick="updte()" name="updatecredit" id="updatecredit">
                        <i class="fa fa-upload"></i>&nbsp; Update
                        </button>   
                        <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                        <i class="fa fa-eraser"></i>&nbsp; Reset
                        </button>   


                        </div>
                   
                </div>
       
</div>
</form>
<!-- Billed data table -->


<!-- operation data table -->
<div class="col-sm-4"  id="operation_data"  style="display:<?=((empty($cnote_type))? 'none' :(((($cnote_type=='Virtual'))? 'none' : 'block')))?>">
<div class="col-sm-12 data-table-div" style="margin-top: -1px;height: 700px;overflow-y:scroll">

<div class="title_1 title ">
<p style="font-weight:600;font-size:20px;color:#2160A0;margin-top:6px;margin-left:10px;">Operation Data</p>

</div>
<table id="" class="table data-table" cellspacing="0" cellpadding="0" align="center">
<thead><tr>
   <!-- <th class="center">
       <label class="pos-rel">
           <input type="checkbox" class="ace" />
       <span class="lbl"></span>
       </label>
     </th> -->
           <th>Tdate</th>
           <th>Cnote</th>
           <th>Wt</th>
           <th>Destination</th>
           <th>Mode</th>
           <th>Branch</th>
          
     </tr>
    </thead>
<tbody id="operationdata">
    <?php
      if(isset($_GET['cust_code'])){

        $get_opcnotes = $db->query("SELECT * FROM cnotenumber WHERE cnote_station='$stationcode' AND cust_code='".$_GET['cust_code']."' AND cnote_status='Allotted' AND NOT opdest IS NULL");
        while($oprow = $get_opcnotes->fetch(PDO::FETCH_OBJ)){
          if($oprow->cnote_number==$cnote_number){
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
    ?>
</tbody>
</table>
</div>



</div>

</div>


</div>
<?php $value = ((empty($cnote_type))? '12' :(((($cnote_type=='Virtual'))? '12' : '8')));  ?>

<div class="col-sm-<?=$value?> data-table-div"  id="changetabledata" style="background-color:var(--table-head-main);">
   <!-- pagination search  -->


   <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text"></div>
           </div>
           <!-- pagination search  -->
<table  class="table data-table" cellspacing="0" cellpadding="0" align="center">
<thead><tr class="form_cat_head">
    <th class="center">
        <label class="pos-rel">
            <input type="checkbox" class="ace" />
        <span class="lbl"></span>
        </label>
      </th>
      <th>Cnote No</th>
      <th>Customer</th>
      <th>Destination</th>
      <th>Weight</th>
      <th>Mode</th>
      <th>Amount</th>
      <th>User</th>
      <th>Op.Destn</th>
      <th>Action</th>
      </tr>
        </thead>
      <tbody id="datatable-tbody">
        <?php 

            
            if(isset($_GET['cust_code']))
            {
                date_default_timezone_set('Asia/Kolkata');
                $today_date        = date('Y-m-d H:i:s');

                $query = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE  cust_code='$cust_code' AND CAST(date_added AS DATE)='$today_date' ORDER BY credit_id DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");
                while ($row = $query->fetch(PDO::FETCH_OBJ))
                {
                  echo "<tr>";
                  echo "<td class='center first-child'>
                          <label>
                              <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                              <span class='lbl'></span>
                            </label>
                        </td>";
                  
                  echo "<td>".$row->cno."</td>";
                  echo "<td>".$row->cust_code."</td>";
                  echo "<td>".$row->dest_code."</td>";
                  echo "<td>".number_format((float)$row->wt, 3)."</td>";
                  echo "<td>".$row->mode_code."</td>";                           
                  echo "<td>".number_format($row->amt,2)."</td>";
                  echo "<td>".$row->user_name."</td>";
                  echo "<td>".$row->dest_code."</td>";
                  echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                  
                  <button type='button' class='btn btn-danger btn-minier bootbox-confirm' value='$row->cnote_serial' id='$row->cno' onclick='".delete($db,$user)."'>
                       <i class='ace-icon fa fa-trash-o bigger-130'></i>
                    </button>
                     </div>
                     <div class='hidden-md hidden-lg'>
                       <div class='inline pos-rel'>
                        <button class='btn btn-minier btn-yellow dropdown-toggle' data-toggle='dropdown' data-position='auto'>
                         <i class='ace-icon fa fa-caret-down icon-only bigger-120'></i>
                        </button>
                        <ul class='dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close'>
                          <li>
                             <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                             <span class='blue'>
                             <i class='ace-icon fa fa-eye bigger-120'></i>
                             </span>
                             </a>
                          </li>
                          <li>
                             <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                              <span class='green'>
                              <i class='ace-icon fa fa-pencil-square-o bigger-120'></i>
                              </span>
                             </a>
                          </li>

                        <li>
                         <a  class='tooltip-error bootbox-confirm' data-rel='tooltip' title='Delete'>
                         <span class='red'>
                          <i class='ace-icon fa fa-trash-o bigger-120'></i>
                          </span>
                          </a>
                        </li>
                      </ul>
                                    </div>
                                </div>
                    </td>";
                         echo "</tr>";
                 
                    }                       
                }

                
            ?>

         
       </tbody>
    </table>
      <!-- pagination -->
      <div class="table-paginate-btns">
            <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM credit WHERE  cust_code='$cust_code' AND CAST(date_added AS DATE)='$today_date'")->fetch(PDO::FETCH_OBJ);
                for($i=1;$i<=ceil($noofpages->pages / 10);$i++){
                  //pagination control
                  if(ceil($noofpages->pages / 10)>5){
                    //dot property
                    if($i==3){
                      $leftdot = '...';
                    }
                    else{
                      $leftdot = '';
                    }
                    if($i==ceil($noofpages->pages / 10)-1){
                      $rightdot = '...';
                    }
                    else{
                      $rightdot = '';
                    }
                    //end dot property
                    //number btn display property
                    if($i==1||$i==2||$i==ceil($noofpages->pages / 10)||$i==round(ceil($noofpages->pages / 10)/2)||$i==round(ceil($noofpages->pages / 10)/2)-1||$i==round(ceil($noofpages->pages / 10)/2+1)){
                      $style = "style='display:'";
                    }else{
                      $style = "style='display:none'";
                    }
                    //end number btn display property
                  }
                  else{
                    $leftdot = '';
                    $rightdot = '';
                    $style= '';
                  }
                  //end pagination control
                  echo "$leftdot<input type='button' $style class='btn btn-primary paginate-btn-nums' value='$i' id='$i'>$rightdot";
                }
              ?>
              <button type="button" class="btn btn-primary paginate-btn-nums" id="next" value="2">Next</button>
            </div>
            <!-- pagination -->
    <input type="text" id="lock_status" value="<?php if(isset($_GET['cust_code'])){ echo $lock_status; } ?>" style="display:none">
    <input type="text" id="cno_status" value="" style="display:none">
    <!-- <input type="text" id="sp_rate_status" value="" style="display:"> -->
      </div>
      
</section>



  <!-- Apostrophe Validation -->

<script>
function pasteNotAllowFunc(xid){
 let myInput = document.getElementById(xid);
     myInput.onpaste = (e) => e.preventDefault();
} 
pasteNotAllowFunc('bdate');
</script>

  
<script>
    $('#formcustomerrate').on('keypress', function (e) {    
        var addr = $(this).val();
      addr.replace(/["']+/g, '');
        var ingnore_key_codes = [34, 39];
        if ($.inArray(e.which, ingnore_key_codes) >= 0) {
            e.preventDefault();
        }
    });
</script>
<script>
   function change_form(element){
    if(element.value=='Regular'){
        $('#changeformvalue').attr('class','col-sm-8');
        $('#changetabledata').attr('class','col-sm-8');
        $('#operation_data').css('display','block');
        $('.xdsoft_autocomplete_hint').css('width','0px');
         $('#cnotewidth').css('width','');
       
    }else{
        $('#changeformvalue').attr('class','col-sm-12');
        $('#changetabledata').attr('class','col-sm-12');
        $('#operation_data').css('display','none');
        $('#cnotewidth').css('width','130px');
        $('.xdsoft_autocomplete_hint').css('width','0px');
    }
   }
</script>
<script>
    $(window).ready(function(){
 var checked2 = '<?= $ch_consignee ?>';
 if(checked2==1){
    $('#consignee').val('<?= $con_name ?>');
    $('#consignee_mob').val('<?= $con_mobile ?>');
    $('#consignee_addr').val('<?= $con_addr ?>');
    $('#consignee_pin').val('<?= $con_pin ?>');
 }else{
    $('#consignee').val('');
    $('#consignee_mob').val('');
    $('#consignee_addr').val('');
    $('#consignee_pin').val('');
 }
 });
</script>
<script>
    $(document).ready(function(){
   $("#br_code").prop('autofocus',true);
    });
</script>
 <script>
    function checkpod(){
        var podorigin = $('#podorigin').val();
        var mode = $('#mode').val();
        var station = $('#stncode').val();
        var cus =  $('#cust_code').val();
 
        //console.log(custcode);
              $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                async: false,
                data:{cus,station,action : 'customer_type'},
                dataType:"text",
                success:function(result){
                   type = result;
                }
                
                });
      console.log(type);
     if(podorigin=='PRO' && type=='D'){
    var data  = ((podorigin==mode) ?  'Equal' : 'NotEqual');
     }
     else if(podorigin=='PRC' && type=='D'){
    var data  =  ((podorigin==mode) ? 'Equal' : 'NotEqual');
     }
     else if(podorigin=='gl' && type=='D'){
        var data = ((mode != 'PRO') ? 'Equal' : 'NotEqual');
     }
    return data;
    }
</script>
<script>
    $(document).ready(function(){
    $("#destn").focusout(function(){
        var destini = $("#destn").val();
       var convar = destini.split("-");
       var pincode = convar[0];
       
        $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{pincode,action : 'getpincode'},
                dataType:"text",
                success:function(result){
                  
                  var pincode = JSON.parse(result);
                  console.log(pincode);
                  $("#consignee_pin").val(pincode[0]);
                }
                
        });
    });
    });
</script>
<!-- inline scripts related to this page -->       
<script>
    $(document).ready(function(){
     $("#br_code").focusout(function(){
        var branch = $("#br_code").val();
        var brname = $("#br_name").val();
        var station1 = $("#stncode").val();
        var cnote_type_val = $("#cnote_type").val();
        $.ajax({
        url:"<?php echo __ROOT__ ?>branch",
        method:"POST",
        data:{branch,brname,station1,action : 'virtual_check'},
        dataType:"text",
        success:function(result)
        {

            cnotetype = JSON.parse(result);
          console.log(cnotetype);
            if(cnote_type_val=='Regular'){

            }else{
             if(cnotetype[0]=='Y'){
              $("#bdate").attr('readonly',true);
              $("#cnote_no").attr('readonly',false);
            }else{
                if(branch !==''){
               $("#bdate").attr('readonly',true);
               $("#cnote_no").attr('readonly',true);
                }else{
                    $("#cnote_no").focusout(function(){
                        var cnote_type_val = $("#cnote_type").val();
                        
                        if(cnote_type_val==='Virtual'){
                          $("#cnote_no").attr('readonly',true);
                        }else{
                            $("#cnote_no").attr('readonly',false); 
                        }
                       
                    });
                }
            }
            }

        }
      }); 
     });
    });
</script>
<script>
    $(document).ready(function(){
   var cnote_type = $("#cnote_type").val();
   if(cnote_type=='Virtual'){
    $("#bdate").attr('readonly',true)
   }
    });
</script>



<script>
  $(document).ready(function(){
    $('#cnote_type').change(function(){
        var branchcode = $("#br_code").val();
        var branchname = $("#br_name").val();
        var station = $("#stncode").val();
        var cnote_type = $("#cnote_type").val();
        $.ajax({
        url:"<?php echo __ROOT__ ?>branch",
        method:"POST",
        data:{branchcode,branchname,station,action : 'virtual_edit'},
        dataType:"text",
        success:function(data)
        {

            console.log(data);
            virtualtype = JSON.parse(data);
          console.log(virtualtype[0]);
           if(cnote_type=='Regular'){

           }else{
            if(virtualtype[0]=='Y'){
             $("#bdate").attr('readonly',true);
             $("#cnote_no").attr('readonly',false);
           }else{
            $("#bdate").attr('readonly',true);
             $("#cnote_no").attr('readonly',true);
           }
           }


         
        }
      });  
     });
  })
</script>

<!-- inline scripts related to this page -->       
<script>
var url_string = window.location.href; 

var url = new URL(url_string);

var bdate = url.searchParams.get("bdate");
var cnotetype = url.searchParams.get("cnotetype");
var virtualedit = url.searchParams.get("virtualedit");
var destination = url.searchParams.get("destination");

const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
let current_datetime = new Date(bdate)
let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear()
console.log(formatted_date);
if(bdate){
    $('#bdate').val(formatted_date);
}
if(cnotetype){
    $('#cnote_type').val(cnotetype); 
}
if(cnotetype=='Regular'){

}
if(destination){

$("#destn").prop('autofocus', true);

}
else{
    if(virtualedit=='Y')
    {
    $('#cnote_no').attr('readonly',false); 
}
else
{
    if(virtualedit=='N')
    {
        $('#cnote_no').attr('readonly',true); 
    }
    else
    {
        $('#cnote_no').attr('readonly',false); 
    }
   
}
}
</script>
<script>
$("#bdate").keydown(function (e) {
  var key = e.keyCode || e.charCode;
  if (key == 8 || key == 46) {
      e.preventDefault();
      e.stopPropagation();
  }
});
</script>
<script>

         $(function() {
            $( "#bdate" ).datepicker({
                dateFormat: "d-M-yy",
        firstDay: 1,
        showAnim: "slide",
        maxDate: new Date(),
        minDate: '-60',
        changeYear: false,
        beforeShow: function(i) {
        if ($(this).prop('readonly')) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
        }
    }
    });
         });
      </script>


<script>
  $('.paginate-btn-nums').click(function(){
    //console.log("dsds");

    var inc = parseInt($(this).val())+1;
            var dec = parseInt($(this).val())-1;
            var curt = parseInt($(this).val());
            var end = parseInt(<?php echo ceil($noofpages->pages / 10); ?>);
            if($(this).val()==0||$(this).val()>end){
              process.end;
            }
    $('#datatable-tbody').css('opacity','0.5');
    $('.paginate-btn-nums').removeClass('active');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT * FROM credit WITH (NOLOCK) WHERE  cust_code='<?=$cust_code?>' AND CAST(date_added AS DATE)='<?=$today_date?>' ORDER BY credit_id DESC";
                          
     console.log(dtbquery);
      var dtbcolumns = 'cno,cust_code,dest_code,wt,mode_code,amt,user_name,dest_code';
    
      var dtbpageno = ($(this).val()-1)*10;
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns},
            dataType:"json",
            success:function(datum){
             console.log('<?=delete($db,$user)?>');
            $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var datacount = Object.keys(datum).length;
      
            var html='';
            for(var i=1;i<=datacount;i++){
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
            
             
              html += "<td>"+datum[i]['cno']+"</td>";
              html += "<td>"+datum[i]['cust_code']+"</td>";
              html += "<td>"+datum[i]['dest_code']+"</td>";
              html += "<td>"+datum[i]['wt']+"</td>";
              html += "<td>"+datum[i]['mode_code']+"</td>";
              html += "<td>"+datum[i]['amt']+"</td>";
              html += "<td>"+datum[i]['user_name']+"</td>";
              html += "<td>"+datum[i]['dest_code']+"</td>";
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";

              html += "<a class='btn btn-primary btn-minier' href='#' data-rel='tooltip' title='View'><i class='ace-icon fa fa-eye bigger-130'></i></a>";                                                      
              html += "<a class='btn btn-success btn-minier' href='#' data-rel='tooltip' title='Edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";                                                     
              html += "<a class='btn btn-danger btn-minier bootbox-confirm' id='"+datum[i]['cno']+"' data-rel='tooltip' title='Delete' onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";                                                      

              //action
              html += "</div></td></tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
            }

            
          }); 
          //pagination dynamic buttons
            if(end>5){
              $('#'+curt).addClass('active');
              $('.paginate-btn-nums').css('display','none');
              $('#'+curt).css('display','');
              $('#1').css('display','');
              $('#'+inc).css('display','');
              $('#'+dec).css('display','');
              // $('#'+dec).css('display','');
              $('#'+end).css('display','');
              $('#'+end).css('display','');
              $('#prev').css('display','');
              $('#prev').val(dec);
              $('#next').css('display','');
              $('#next').val(inc);
            }
            else{
              $('#'+curt).addClass('active');
              $('#prev').val(dec);
              $('#next').val(inc);
            }
          //end pagination dynamic buttons
  });
</script>

<script>
  $('.table-search-right-text').keyup(function(event){
    // event.preventDefault();
    var fieldvalue = $(this).val();
      sessionStorage.setItem("credittable", fieldvalue);
      
      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT * FROM credit WITH (NOLOCK) WHERE  cust_code='<?=$cust_code?>' AND CAST(date_added AS DATE)='<?=$today_date?>' AND (cno like '%"+fieldvalue+"%' OR dest_code LIKE '%"+fieldvalue+"%') ORDER BY credit_id DESC";
     console.log(dtbquery);
      var dtbcolumns = 'cno,cust_code,dest_code,wt,mode_code,amt,user_name,dest_code';
      var dtbpageno = '0';
      console.log(dtbquery);
     
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns},
            dataType:"json",
            success:function(datum){
              console.log(datum);
            $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var datacount = Object.keys(datum).length;
            var html='';
            for(var i=1;i<=datacount;i++){
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
        
              html += "<td>"+datum[i]['cno']+"</td>";
              html += "<td>"+datum[i]['cust_code']+"</td>";
              html += "<td>"+datum[i]['dest_code']+"</td>";
              html += "<td>"+datum[i]['wt']+"</td>";
              html += "<td>"+datum[i]['mode_code']+"</td>";
              html += "<td>"+datum[i]['amt']+"</td>";
              html += "<td>"+datum[i]['user_name']+"</td>";
              html += "<td>"+datum[i]['dest_code']+"</td>";
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
             
              html += "<a class='btn btn-primary btn-minier' href='#' data-rel='tooltip' title='View'><i class='ace-icon fa fa-eye bigger-130'></i></a>";                                                      
              html += "<a class='btn btn-success btn-minier' href='#' data-rel='tooltip' title='Edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";                                                  
              html += "<a class='btn btn-danger btn-minier bootbox-confirm' href='#' id='"+datum[i]['cno']+"' data-rel='tooltip' title='Delete' onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";                                                      
              //action
              html += "</div></td></tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
            }

            
          }); 
       });

      

</script>
<script>
  $(document).ready(function(){
       // $('.data-table').DataTable();
    });
</script>
<script>
function calculateamount(){
    if(parseFloat($('#v_wt').val())>=parseFloat($('#wt').val())){
      var weight = $('#v_wt').val();
    }
    else if(parseFloat($('#v_wt').val())<=parseFloat($('#wt').val())){
      var weight = $('#wt').val();
    }
    
    var customer     = $('#cust_code').val();
    var origin       = $('#origin').val();
    var destination  = $('#destn').val().split('-')[0];
    var mode         = $('#mode').val();
    var pod_amt      = $('#podorigin').val();
    // var weight       = $('#wt').val();
    var table  = 'credit';
    pcs          = '1';
    $.ajax({
            url:"<?php echo __ROOT__ ?>credit",
            method:"POST",
            data:{customer:customer,origin:origin,destination:destination,weight:weight,pcs:pcs,mode:mode,pod_amt:pod_amt,table:table},
            dataType:"text",
            success:function(amount)
            {
                // var jsondata = JSON.parse(amount);
                // console.log(jsondata);
                console.log(amount);
                $('#amount').val(amount);
                if($('#amount').val()=='Maximum'){
                    $('#max_weight').css('display','block');
                }else{
                    $('#max_weight').css('display','none');
                }
            }
        });
}
</script>
<script>
    $('#wt').focusout(function(){
        console.log(parseFloat($(this).val()));
        if($(this).val()>=100){
        swal('Entered weight is 100 kg or more than 100kg !\n दर्ज वजन 100 किलोग्राम या 100 किलोग्राम से अधिक है !');
    }
    });
    $('#v_wt').focusout(function(){
        if($(this).val()>=100){
        swal('Entered weight is 100 kg or more than 100kg !\n दर्ज वजन 100 किलोग्राम या 100 किलोग्राम से अधिक है !');
    }
    });
</script>
<script>
    $(window).on('load', function () {

   var usr_name = $('#user_name').val();
        
        $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{usr_name:usr_name},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#stncode').val(data[0]);
            $('#stnname').val(data[1]);
            $('#origin').val(data[0]);
            $('#podoption').val('gl');
            $('#podoption').html(data[0]);

            if($('#cnote_no').val().trim()!=''){
                operationdata();
                getoperationofcno();
            }

                if("<?=$_GET['cust_code']?>".trim().length>0){
                    var fetchserialcno = '<?=$cno3?>';
                    var fetchcompany = '<?=$stationcode?>';
                    var fetch_cate = '<?=$getpod?>';
                    getcnote_serial(fetchserialcno,fetchcompany,fetch_cate);
                    cnoteval();
                }

            }
    });


    
  });

</script>
<script>
    function addanother()
    {
       console.log(checkpod());

        if($('#cnote_no').val().trim()=='')
        {
            $('#cnote_no_validation').css('display','block');
        }
        else if($('#cno_status').val().trim()=='Billed')
        {
            swal("Cnote Already Billed !! Don't Make Credit Entry.. Use only Update Button");
        }
        else if($('#cno_status').val().trim()=='Invoiced')
        {
            swal("Don't Make Credit Entry for invoiced cnote.. Please Remove cnote from invoice and Try again");
        }
        else if($('#cnote_no_invalid').css('display')==='block')
        {
            alert('Enter Valid Cnote');
        }
        else if($('#pcs').val().trim()=='')
        {
            $('#pcs_validation').css('display','block');
        }
        else if($('#destn').val().trim()=='')
        {
            $('#destnull_validation').css('display','block');
        }
     //    else if($('#cnote_serial').val().trim()=='')
     //    {
     //        swal("Invalid Cnote Serial")
     //    }
        else if($('#dest_validation').css('display')==='block')
        {
            swal('Enter Valid Destination');
        }
        else if((parseFloat(opwt)-parseFloat($('#wt').val()))>=0.10)
        {
            swal('Entered wt is less than operation wt');
        }
        else if($('#mode').val().trim()=='')
        {
            alert('Enter Valid Mode');
        }
        else if($('#cust_code').val().trim()=='')
        {
            swal('Please Enter Customer Code');
        }
        else if(lock_flag==1)
        {
            swal({
            icon: "warning",
            text: "This Customer credit note is temporarily locked due to pending invoices",
            });
        }
        else if($('#amount').val().trim()=='' || $('#amount').val().trim()=='0' || $('#amount').val().trim()=='Maximum')
        {
            alert('Amount Not Valid');
        }
        else if(checkpod()=='NotEqual'){
              swal("Please Select Correct Pod Origin");
        }
        else if($('#cnote_type').val().trim()=='Virtual' && $("#consignee_mob").val().trim()=='')
        {
            swal("Please Enter Mobile Number");
        }
        else{
            if(parseFloat($('#wt').val())>0 || parseFloat($('#v_wt').val())>0)
            {
            if(checkcnoandcustomer()==0){
                swal("Entered Cnote is allotted to another customer !! Please Enter valid customer code");
            }
            else{
            $('#addanothercredit').html("<i class='fa fa-spinner'></i>&nbsp; Save & Add");
            $('#addanothercredit').attr('type','submit');
            $('#addanothercredit').attr('name','addanotherbillingcredit');
            }
            }else{
                $('#wt_validation').css('display','block');
            }
        }
    }
    function add()
    {
        if($('#cnote_no').val().trim()=='')
        {
            $('#cnote_no_validation').css('display','block');
        }
        else if($('#cno_status').val().trim()=='Billed')
        {
            swal("Cnote Already Billed !! Don't Make Credit Entry.. Use only Update Button");
        }
        else if($('#cno_status').val().trim()=='Invoiced'){
            swal("Don't Make Credit Entry for invoiced cnote.. Please Remove cnote from invoice and Try again");
        }
        else if($('#cnote_no_invalid').css('display')==='block')
        {
            alert('Enter Valid Cnote');
        }
        else if($('#pcs').val().trim()=='')
        {
            $('#pcs_validation').css('display','block');
        }
        else if($('#destn').val().trim()=='')
        {
            $('#destnull_validation').css('display','block');
        }
        else if($('#dest_validation').css('display')==='block')
        {
            swal('Enter Valid Destination');
        }
        else if((parseFloat(opwt)-parseFloat($('#wt').val()))>=0.10)
        {
            swal('Entered wt is less than operation wt');
        }
     //    else if($('#cnote_serial').val().trim()=='')
     //    {
     //        swal("Invalid Cnote Serial")
     //    }
        else if($('#mode').val().trim()=='')
        {
            alert('Enter Valid Mode');
        }
        else if($('#cust_code').val().trim()=='')
        {
            swal('Please Enter Customer Code');
        }
        else if(lock_flag==1)
        {
            swal({
            icon: "warning",
            text: "This Customer credit note is temporarily locked due to pending invoices",
            });
        }
        else if($('#amount').val().trim()=='' || $('#amount').val().trim()=='0' || $('#amount').val().trim()=='Maximum')
        {
            alert('Amount Not Valid');
        }
        else if($('#cnote_type').val().trim()=='Virtual' && $("#consignee_mob").val().trim()=='')
        {
            swal("Please Enter Mobile Number");
        }
        else{
            if(parseFloat($('#wt').val())>0 || parseFloat($('#v_wt').val())>0)
            {
                if(checkcnoandcustomer()==0  ){
                    swal("Entered Cnote is allotted to another customer !! Please Enter valid customer code");
                }
                else{
                $('#addcredit').html("<i class='fa fa-spinner'></i>&nbsp; Save");
                $('#addcredit').attr('type','submit');
                $('#addcredit').attr('name','addbillingcredit');
                }
            }else{
                    $('#wt_validation').css('display','block');
                }
            }
    }
    function updte()
    {
        if($('#cnote_no').val().trim()=='')
        {
            $('#cnote_no_validation').css('display','block');
        }
        else if($('#cno_status').val().trim()!='Billed')
        {
            swal("Cnote is in "+$('#cno_status').val()+" Status !! Not able to Update");
        }
        else if($('#cnote_no_invalid').css('display')==='block')
        {
            alert('Enter Valid Cnote');
        }
        else if($('#pcs').val().trim()=='')
        {
            $('#pcs_validation').css('display','block');
        }
        else if($('#destn').val().trim()=='')
        {
            $('#destnull_validation').css('display','block');
        }
        else if((parseFloat(opwt)-parseFloat($('#wt').val()))>=0.10)
        {
            swal('Entered wt is less than operation wt');
        }
        else if($('#dest_validation').css('display')==='block')
        {
            swal('Enter Valid Destination');
        }
        else if($('#cnote_serial').val().trim()=='')
        {
            swal("Invalid Cnote Serial")
        }
        else if($('#mode').val().trim()=='')
        {
            alert('Enter Valid Mode');
        }
        else if($('#cust_code').val().trim()=='')
        {
            swal('Please Enter Customer Code');
        }
        else if(lock_flag==1)
        {
            swal({
            icon: "warning",
            text: "This Customer credit note is temporarily locked due to pending invoices",
            });
        }
        else if($('#amount').val().trim()=='' || $('#amount').val().trim()=='0' || $('#amount').val().trim()=='Maximum')
        {
            alert('Amount Not Valid');
        }
        else if(checkpod()=='NotEqual'){
              swal("Please Select Correct Pod Origin");
        }
        else if(checkcnoandcustomerupdate()==0  ){
            swal("Entered Cnote is allotted to another customer !! Please Enter valid customer code");
        }
        else{
            if(parseFloat($('#wt').val())>0 || parseFloat($('#v_wt').val())>0)
            {
            $('#updatecredit').attr('type','submit');
            $('#updatecredit').attr('name','updatecredit');
            }else{
                    $('#wt_validation').css('display','block');
                }
            }
    }

</script>
<script>
    function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
</script>
<script>
  $(document).ready(function(){
    var customers = {};

        $('#br_code').focusout(function(){
            $('#brcode_validation').css('display','none');
            var br_code = $('#br_code').val().toUpperCase().trim();

            if(branches[br_code]!==undefined){
                //$('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
                fetchcustomer();
                getcustomersjson();
                bacnoteverify();
            }
            else{
                $('#br_name').val('');
                //$('#brcode_validation').css('display','block');
            }
        });
        //fetch code of entering name
        $('#br_name').focusout(function(){
            $('#brcode_validation').css('display','none');
            var br_name = $(this).val().trim();
            var gccode = getKeyByValue(branches,br_name);
            
            if(gccode!==undefined){
                //$('#brcode_validation').css('display','none');
                $('#br_code').val(gccode);
                fetchcustomer();
                getcustomersjson();
            }
            else{
                $('#br_code').val('');
                //$('#brcode_validation').css('display','block');
            }
           
        });

        $('#br_code,#br_name').focusout(function(){
            if($(this).val().trim()!='')
            {
                validatebranch();
            }
        });

        function validatebranch(){
            var br_code = $('#br_code').val().toUpperCase().trim();
            if(branches[br_code]!==undefined){
                $('#brcode_validation').css('display','none');
            }
            else{
                $('#brcode_validation').css('display','block');
            }
        }


        //Verify Destination is Valid
        $('#destn').focusout(function(){
         var destcode2 = $(this).val().split('-')[0];
         var cnote_station = $('#stncode').val();
        //Destination Verification
        var mode_cust = $('#cust_code').val();
        var podorigin = $('#podorigin').val();
        var table = 'credit';
           //Fetch Mode based on customer and destination
           var mode_origin = $('#origin').val();
           //var mode_cust = $('#cust_code').val();
           var arr = $("#podorigin option").map(function(){ return this.value; }).get();
        filesAry        =   arr;
        newDelFilesAry  =   ['PRO','PRC'];
       arr  =   [];
      $.grep(filesAry, function(el) { 
       if ($.inArray(el, newDelFilesAry) == -1) arr.push(el);
       });
            $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{dest_mode:destcode2,mode_origin:mode_origin,mode_cust:mode_cust,arr:arr,podorigin:podorigin,table},
                dataType:"text",
                success:function(modes)
                {
                    console.log(modes);
                    $('#mode').html(modes);
                }
            });
           $('#destnull_validation').css('display','none');
    });

            //Verify Destination is Valid
    $('#destn').focusout(function(){
         var destcode2 = $(this).val().split('-')[0];
         var cnote_station = $('#stncode').val();
        //Destination Verification
        var mode_cust = $('#cust_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{destcode2:destcode2,destval_cust:mode_cust,dest_station:cnote_station},
            dataType:"text",
            success:function(destcode)
            {
              if(destcode == 0){
              $('#dest_validation').css('display','block');
              }
              else{
              $('#dest_validation').css('display','none');
               }
            }
           });

           $('#destnull_validation').css('display','none');
        });
    });

</script>
<script>
//check special serial amount is valid for customer
function checkgroupisvalid(){
    $('#podoption').attr('selected', false);
    if($('#cust_code').val().trim()==''){
        swal("Please Enter customer code");
        $('#podoption').attr('selected', true);
    }
    else{
    var cg_stn =  $('#stncode').val();
    var cg_cust = $('#cust_code').val();
    var cg_serial = $('#podorigin').val();
    if(cg_serial=='PRO' || cg_serial=='PRC' || cg_serial=='PRD' || cg_serial=='COD'){
    $.ajax({
        url:"<?php echo __ROOT__ ?>billingcontroller",
        method:"POST",
        data:{cg_stn,cg_cust,cg_serial},
        success:function(isvalid)
        {
           if(isvalid==0){
               swal("This Customer Does not have "+$('#podorigin').val()+" Rate");
               //location.reload();
               $('#podoption').attr('selected', true);
               //$('#sp_rate_status').val(isvalid);
           }
        }
      });
    }
    else{
        $('#sp_rate_status').val('1');
    }
}
}

</script>
<script>
    function getcnote_serial(cno,comp_code,category){
            $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{serial_cno:cno
                    ,serial_comp_code:comp_code
                    ,serial_category:category},
                dataType:"text",
                success:function(cnoteserial)
                {
                    //console.log(cnoteserial);
                    $('#cnote_serial').val(cnoteserial.trim());
                }
            });
    }
    </script>
    <script>
    $(document).ready(function(){
   $("#cnote_no").focusout(function(){
    var cnotenumber = $("#cnote_no").val(); 
        var cnote_station = $('#stncode').val();
    console.log(cnotenumber);
      $.ajax({
        url:"<?php echo __ROOT__ ?>cash",
        method:"POST",
        data:{cnotenumber,cnote_station,action : 'cnotetype'},
        dataType:"text",
        success:function(data)
        {
            cnotetype = JSON.parse(data);
          console.log(cnotetype[1].cnote_status);
        //   if(cnotetype[1].cnote_status=='Allotted'){
            
        //   }else{
        //     $("#cnote_type").val(cnotetype[0]);
        //   }
        
          if(cnotetype[0]=='Virtual'){
            $("#bdate").attr('readonly',true);
            $("#cnote_no").attr('readonly',true);
          }
        }
      });
   });
    });
</script>
<script>
function cnoteval(){
            var cnote_no = $('#cnote_no').val();
            var cnote_station = $('#stncode').val();
            var podorigin = $('#podorigin').val();
            var v_flagging = $('#cnote_type').val();
            $('#other_charges').attr('readonly',false);
            $('#insurance_charges').attr('readonly',false);
            $('#packing_charges').attr('readonly',false);
            getoperationofcno();
            $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{cnote_no:cnote_no,cnote_station:cnote_station,podorigin:podorigin,v_flagging},
            dataType:"text",
            success:function(value)
            {
                var data = value.split(",");
                console.log('Output 2 : '+ data[2]);

                $('#cno_status').val(data[2]);
                //fetch cnoteserial

				//Tdate change to Get date for Allotted
				if(data[2]=='Allotted'){
                    $('#bdate').val('<?=date('d-M-Y')?>');
                }else{

                }

                if(data[2]=='Allotted' || data[2]=='Billed' || data[2]=='Unbilled' || data[2]=='Invoiced'){
                    $('#cnote_no_invalid').css('display','none');
                    getcnote_serial(cnote_no,cnote_station,$('#podorigin').val());

                    if($('#cust_code').val().trim()=='')
                    {
                    $('#cust_code').val(data[3]);
                    $('#cust_name').val(data[4]); 
                    checkpacking_insurance(data[3]);
                    //console.log(cnote_station);
                    checkcustlockstatus(data[3],cnote_station);
                    }
                    if($('#br_code').val().trim()==''){
                        $('#br_code').val(data[0]);
                        $('#br_name').val(data[1]);
                    }
                        var billcnote_stn = $('#stncode').val();
                        var billcnote_cno = $('#cnote_no').val();
                        var billcnote_sr = $('#cnote_serial').val();
                        $.ajax({
                            url:"<?php echo __ROOT__ ?>billingcontroller",
                            method:"POST",
                            data:{billcnote_stn,billcnote_cno,billcnote_sr},
                            dataType:"json",
                            success:function(billdatas)
                            {
                                //var values = billdatas.split(",");
                                console.log(billdatas);
                                if(billdatas)
                                {
                                    const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                                    var newdate = billdatas['tdate'];
                                    let current_datetime = new Date(newdate)
                                    let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear()
                                if(billdatas['tdate']!=''){
                                    $('#bdate').val(formatted_date);
                                }
                                if(billdatas['cnote_type']!=''){
                                    // $('#cnote_type').val(billdatas['cnote_type']);
                                    $('#cnote_type').html('<option value="'+billdatas['cnote_type']+'">'+billdatas['cnote_type']+'</option>');
                                }
                                if(billdatas['mf_no']!=''){
                                    $('#mf_no').val(billdatas['mf_no']);
                                }
                                if(billdatas['contents']!=''){
                                    $('#contents').val(billdatas['contents']);
                                }
                                if(billdatas['dc_value']!=''){
                                    $('#dc_value').val(billdatas['dc_value']);
                                }
                                if(billdatas['bkgstaff']!=''){
                                    $('#bkgstaff').val(billdatas['bkgstaff']);
                                }
                                if(billdatas['dest_code'].trim()!=''){
                                $('#destn').val(billdatas['dest_code']);
                                }
                                if(billdatas['wt'].trim()!=''){
                                $('#wt').val(parseFloat(billdatas['wt']));
                                }
                                if(billdatas['v_wt'].trim()!=''){
                                $('#v_wt').val(parseFloat(billdatas['v_wt']));
                                }
                                if(billdatas['mode_code']!=''){
                                    var modetemp = billdatas['mode_code'];
                                $('#mode').html('<option value="'+modetemp+'">'+modetemp+'</option>');
                                }
                                if(billdatas['amt']!=''){
                                    $('#amount').val(billdatas['amt']-billdatas['packing_charges']-billdatas['insurance_charges']-billdatas['other_charges']);
                                    if(billdatas['packing_charges']>0){
                                    $('#packing_charges').val(parseFloat(billdatas['packing_charges']).toFixed(2));
                                    }
                                    if(billdatas['insurance_charges']>0){
                                    $('#insurance_charges').val(parseFloat(billdatas['insurance_charges']).toFixed(2));
                                    }
                                    if(billdatas['other_charges']>0){
                                    $('#other_charges').val(parseFloat(billdatas['other_charges']).toFixed(2));
                                    }
                                }
                                if(billdatas['pcs']!=''){
                                $('#pcs').val(billdatas['pcs']);
                                }

                                $('#consignee_mob').val(billdatas['consignee_mobile']);
                                $('#consignee').val(billdatas['consignee']);
                                $('#consignee_addr').val(billdatas['consignee_addr']);
                                $('#consignee_pin').val(billdatas['consignee_pin']);
                            }
                        }
                        });
                }else{
                    $('#cnote_no_invalid').css('display','block');
                }
                             
            }
            });
            $('#cnoteno1').val(cnote_no);
            $('#cnote_no_validation').css('display','none');
        }
</script>
<script>
    function getoperationvalues(){

    }
    </script>
<script> 
    function checkpacking_insurance(cust_code){

        $.ajax({
            async: true,
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{getcharges_customer:cust_code,getcharges_station:'<?=$stationcode?>'},
            dataType:"text",
            success:function(jsonvalue)
            {
               jsonvalue = JSON.parse(jsonvalue);

                // console.log(jsonvalue['packing_charges']);
                if(jsonvalue['packing_charges']==1){
                    $('#packing_charges').attr('readonly',false);
                }
                else{
                    $('#packing_charges').val('0');
                    $('#packing_charges').attr('readonly',true);
                }
                if(jsonvalue['insurance_charges']==1){
                    $('#insurance_charges').attr('readonly',false);
                }
                else{
                    $('#insurance_charges').val('0');
                    $('#insurance_charges').attr('readonly',true);
                }
                //other
                if(jsonvalue['other_charges']==1){
                    $('#other_charges').attr('readonly',false);
                }
                else{
                    $('#other_charges').val('0');
                    $('#other_charges').attr('readonly',true);
                }
            }
            });
    }
  
    if($('#cust_code').val().trim()!=''){
        var getccode = $('#cust_code').val().trim().toUpperCase();
        checkpacking_insurance(getccode)
    }
</script>
<script>
//check the entered cnote is alloted to entered customer
    function checkcnoandcustomer(){
       var verifyentrycustomer = $('#cust_code').val();
       var verifyentrystation = $('#stncode').val();  
       var verifyentrycnote = $('#cnote_no').val();
       var res = 'd';
       $.ajax({
            async: false,
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{verifyentrycustomer,verifyentrystation,verifyentrycnote},
            dataType:"text",
            success:function(valid)
            {
                 res=valid;
            }
            });
            return res;
    }
</script>
<script>
//check the entered cnote is alloted to entered customer for update
    function checkcnoandcustomerupdate(){
        var uverifyentrycustomer = $('#cust_code').val();
       var uverifyentrybranch = $('#br_code').val();
       var uverifyentrystation = $('#stncode').val();  
       var uverifyentrycnote = $('#cnote_no').val();
       var res = 'd';
       $.ajax({
            async: false,
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{uverifyentrybranch,uverifyentrystation,uverifyentrycnote,uverifyentrycustomer},
            dataType:"text",
            success:function(valid)
            {
                 res=valid;
            }
            });
            return res;
    }
</script>
<script>
    //Verifying Customer is Valid
    function cust_cnote_verify(){
        var customer_verify = $('#cust_code').val().toUpperCase().trim();
        var branch_code = $('#br_code').val().toUpperCase().trim();
        var podorigin = $('#podorigin').val();
        var cnote_type = $('#cnote_type').val();
        console.log('Input : '+customer_verify,branch_code,podorigin,cnote_type);
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{customer_verify:customer_verify,branch_code:branch_code,podorigin2:podorigin,cnote_type},
            dataType:"text",
            success:function(value)
            {
            var customercount = value.split("|");
            console.log('Output : '+customercount[2].length);

                if(customercount[0] == 0)
                {
                    //$('#customer_validation').css('display','block');
                }
                else{
                    //$('#customer_validation').css('display','none');
                   // $('#cust_name').val(customercount[1]);
                    if(customercount[2].length>0 && $('#cnote_no').val().trim().length==0){
                    //   $('#cnote_no').val(customercount[2]);
                        cnoteval();
                        $('#cno_status').val(customercount[4]);
                        getcnote_serial(customercount[2],'<?=$stationcode?>',$('#podorigin').val());
                    }
                    $('#lock_status').val(customercount[3]);
                    operationdata();
                    }
                    bacnoteverify();
            }
            });
        
        }
</script>
<script>
            //Verifying Customer is Valid
        function pod_verify(){
        var customer_verify = $('#cust_code').val();
        var branch_code = $('#br_code').val();
        var podorigin = $('#podorigin').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{customer_verify:customer_verify,branch_code:branch_code,podorigin2:podorigin},
            dataType:"text",
            success:function(value)
            {
             var customercount = value.split("|");
                    $('#cnote_no').val(customercount[2]);
                    if(customercount[2].trim()!=''){
                        getcnote_serial(customercount[2],'<?=$stationcode?>',podorigin);
                    }
            }
            });
        }
</script>
<script>
 function resetdestn(){
    //  $('#destn').val('');
    //  $('#wt').val('0');
    //  $('#v_wt').val('0');
    //  $('#amount').val('');
    //  $('#consignee').val('');
 }
</script>
<script>
 

var destination =[<?php  
    $sql1=$db->query("SELECT dest_code,dest_name FROM destination where comp_code='$stationcode' AND active != 'N'");
    while($row1=$sql1->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row1['dest_code']."-".$row1['dest_name']."',";
    }
    $sql2=$db->query("SELECT * FROM pincode");
    while($row2=$sql2->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row2['pincode']."-".$row2['dest_code']."',";
    }
   
    
 ?>];


</script>

<script>
// autocomplete(document.getElementById("destn"), destination);
$('#destn').autocomplete({
    source:[destination]
});
</script>


<script>
var branchcode =[<?php  

$brcode=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
while($rowbrcode=$brcode->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrcode['br_code']."',";
}

?>];
autocomplete(document.getElementById("br_code"), branchcode);

var branchname =[<?php  

$brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode'");
while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrname['br_name']."',";
}

?>];
 
 autocomplete(document.getElementById("br_name"), branchname);

</script>

<script>
//branches array
var branches = {
    <?php
    $branches = $db->query("SELECT br_code,br_name FROM branch WHERE stncode='$stationcode'");
    while($rowbranches=$branches->fetch(PDO::FETCH_OBJ))
    {
    echo $rowbranches->br_code.":'".$rowbranches->br_name."',";
    }
    ?>
}
</script>

<script>
function fetchcustomer(){

   var branch8 = $('.br_code').val().toUpperCase();
   var comp8 = '<?=$stationcode?>'.toUpperCase();
   //console.log(branch8,comp8);

       $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("cust_code"), cust8); 
           //console.log(cust8);

       }
       });
       $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(custname)
       {
           autocomplete(document.getElementById("cust_name"), custname); 
       }
       });

}
</script>
<script> 

$(document).ready(function(){
     
     $('#cust_code').focusout(function(){
        $('#customer_validation').css('display','none');
         var cust_code = $('#cust_code').val().toUpperCase().trim();

        if(customers[cust_code]!==undefined){
            $('#cust_name').val(customers[cust_code]);
            checkpacking_insurance(cust_code);
            //$('#customer_validation').css('display','none');
            checkcustlockstatus(cust_code,'<?=$stationcode?>');
            cust_cnote_verify();
        }
        else{
            $('#cust_name').val('');
            //$('#customer_validation').css('display','block');
        }
     });

     $('#cust_code,#cust_name').focusout(function(){
        if($(this).val().trim()!='')
        {
        validatecustomer();
        }
     });

     //fetch code of entering name
     $('#cust_name').focusout(function(){
         $('#customer_validation').css('display','none');
         var cust_name = $(this).val();
         var ccode = getKeyByValue(customers,cust_name);

         if(ccode!==undefined){
            $('#cust_code').val(ccode);
            checkpacking_insurance(ccode);
            checkcustlockstatus(cust_code,'<?=$stationcode?>');
            //$('#customer_validation').css('display','none');
            
            }
            else{
                $('#cust_code').val('');
                //$('#customer_validation').css('display','block');
            }
     });
});

function validatecustomer(){
    var cust_code = $('#cust_code').val().toUpperCase().trim();
    if(customers[cust_code]!==undefined){
            $('#customer_validation').css('display','none');
            cust_cnote_verify();
        }
        else{
            $('#customer_validation').css('display','block');
        }
}
</script>

<script> 
function getcustomersjson(){
   //get customers json
   var branch8 = $('.br_code').val().toUpperCase();
   var comp8 = '<?=$stationcode?>'.toUpperCase();
   $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{getjsoncust_br:branch8,getjsoncust_stn:comp8},
       dataType:"text",
       success:function(customerjson)
       {
          customers = JSON.parse(customerjson);
          //console.log(customers);
       }
       });
    }
</script>
<script>
//today added
function operationdata(){
  var op_cno = $('#cnote_no').val().trim();
    var op_stn = $('#stncode').val();
    var op_cst = $('#cust_code').val();
    $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{op_cno,op_stn,op_cst},
       dataType:"text",
       success:function(op_cnotes)
       {
        $('#operationdata').html(op_cnotes);
       }
    });
}
//end
</script>
<script> 
var opwt;
function getoperationofcno(){
    var oprow_cno = $('#cnote_no').val().trim();
    var oprow_stn = $('#stncode').val();
    var oprow_cst = $('#cust_code').val();
    var oprow_pod = $('#podorigin').val();
    if(oprow_cno.length>0){
    $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{oprow_cno,oprow_stn,oprow_cst,oprow_pod},
       dataType:"json",
       success:function(oprow_cnotes)
       {
        console.log('opdata loaded',oprow_cnotes);
        if(oprow_cnotes['opwt']!=undefined){
            opwt = oprow_cnotes['opwt'];
            console.log(parseFloat(opwt));
            $('#wt').val(oprow_cnotes['opwt']);
            $('#destn').val(oprow_cnotes['opdest']);
            calculateamount();
        }
        else{
            opwt=0;
            // $('#wt').val('0');
            // $('#amount').val('0');
            // $('#destn').val('');
        }

       }
    });
    }
}
</script>
<script>
function deletequeue(cnotenumber,serial){
    swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this record file!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
  })
  .then((willDelete) => {

    if (willDelete) {
      var delcredit_ecompany = $('#stncode').val();
      var delcredit_ecnote = cnotenumber;
      var delcredit_serial = serial;
      var delcredit_euser = '<?=$user?>'
      $.ajax({
                url:"<?php echo __ROOT__ ?>credit",
                method:"POST",
                data:{delcredit_ecompany,delcredit_ecnote,delcredit_euser,delcredit_serial},
                dataType:"text",
                
        });
      swal("Your Record has been deleted!", {
        icon: "success",
      });
      $('#'+cnotenumber).closest('tr').remove();
      
      
    } 
    else 
    {
      swal("Your Record is safe!");
    }
  });
  
}

</script>
<script>
    var lock_flag = <?=$lock_status?>;
    function checkcustlockstatus(cust_code,comp_code){
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{lock_custcode:cust_code,lock_compcode:comp_code},
            dataType:"text",
            success:function(lockflag)
            {
                lock_flag = lockflag;
                //console.log("Customer lock flag : "+lockflag);
            }
        });
    }
    </script>
    <script>
        function verifyvirtual(element){
            if(element.value=='Virtual'){
              $('#pcs').val('1');
                $('#cnote_no').attr('readonly',true);
                $('#bdate').val("<?=date('d-M-Y')?>");
                $('#bdate').attr('readonly',true);
                $('#consignee_mob').attr('required',true);
                $('#other_charges').attr('readonly',false);
                $('#packing_charges').attr('readonly',false);
                $('#insurance_charges').attr('readonly',false);
            }
            else{
                $('#cnote_no').attr('readonly',false);
                $('#bdate').attr('readonly',false);
                $('#consignee_mob').attr('required',false);
            }

            $('#destn').val('');
            $('#wt').val('0');
            $('#v_wt').val('0');
            $('#amount').val('0');
        }
        </script>
<script>
    $(document).ready(function(){
        if(<?=!empty($sessionbr_code)?>){
            fetchcustomer();
            getcustomersjson();
            //console.log('cust code fetched');
        }
    });
    </script>
<script>

function resetFunction() 
{

    document.getElementById('cnote_no').value = "";
   document.getElementById('mf_no').value = "";
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cust_code').value = "";
    document.getElementById('cust_name').value = "";
    document.getElementById('wt').value = "";
    document.getElementById('pcs').value = "";
    document.getElementById('mode').value = "";
    document.getElementById('amount').value = "";
    document.getElementById('destn').value = "";
    document.getElementById('bkgstaff').value = "";
    document.getElementById('consignee').value = "";
    
 }
</script>
<script>

    $('#consignee_mob').focusout(function(){
        if($(this).val().length>9){
            $('#consignee_mob').css('border','1px solid green');
            var shpmobile = $(this).val();
            $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{shpmobile},
                dataType:"text",
                success:function(consigneedata)
                {
                    cdata = JSON.parse(consigneedata);
                    $('#consignee').val(cdata[0]['consignee_name']);
                    $('#consignee_addr').val(cdata[0]['consignee_addr']);
                    $('#consignee_pin').val(cdata[0]['consignee_pincode']);
                }
            });
        }
        else{
            $('#consignee_mob').css('border','1px solid red');
        }
    });
    </script>
   <script>
    function bacnoteverify(){
        var ba_stn = $('#stncode').val();
        var ba_branch = $('#br_code').val();
        var cashorigin = $('#podorigin').val();
        var cashcccode = '';
        var cnote_type = $('#cnote_type').val();
        var branchname = $("#br_name").val();
        console.log('cnote verify initiated');
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{branchname:branchname,ba_stn:ba_stn,ba_branch:ba_branch,cashorigin:cashorigin,cashcccode,cashcnote_type:cnote_type},
            dataType:"text",
            success:function(cno)
            {
                if(cno.length>0 && $('#cnote_no').val().trim().length==0){
              //  $('#cnote_no').val(cno);
              cnote = JSON.parse(cno);
              console.log(cnote);
               if(cnote_type=='Regular'){
                $('#cnote_no').val(cnote[0]);
               }else{
                if(cnote[2]=='Y'){ 
                      
                }else{
                 $('#cnote_no').val(cnote[0]);
                 
                }   
             }
                }
                //cashcnotevalidation();
                console.log('cnote verify completed',cno);

            }
            });
    }
</script>
<script>
    $(window).on('load', function() {  

         var cnotetype = $("#cnote_type").val();
         console.log("<?=$_GET['cnotenumber']?>"," flag = ",'<?=$cnodetailsofprev->vflag?>',cnotetype,'<?=$chellanvalue?>');
        if('<?=$_GET['cnotenumber']?>'.trim().length>0 && '<?=$cnodetailsofprev->vflag?>'==1 && '<?=$chellanvalue?>'==1 || '<?=$chellanvalue?>'==2 || '<?=$chellanvalue?>'==3){
            if(cnotetype.trim()=='Virtual'){
                window.open('creditchallan?cno=<?=base64_encode(http_build_query(array($_GET['cnotenumber'])))?>&type=<?=base64_encode('printed')?>&customer=<?=base64_encode($cust_code)?>');

            }
        }
      
    });

    // if(window.location.host!='localhost')
    // window.history.pushState('Credit', 'Title', '/credit');

</script>

<script>
      function pincodeview(destpincode){
        var conpin = document.getElementById('consignee_pin');
        conpin.value = destpincode.value;
      }
  
      </script>
