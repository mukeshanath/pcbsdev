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
            return "deletequeue(this.id)";
        }
  }

function loadmode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM mode WITH (NOLOCK) ORDER BY mode_code ASC');
       
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


 //branch filter conditions
 $sessionbr_code = $datafetch['br_code'];
 //branch filter conditions

    if(!empty($sessionbr_code)){
        $br_code = $sessionbr_code;
        $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$br_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
        $br_name     = $fetchbrname->br_name;
    }
    else if(isset($_GET['cust_code']))
    {
        $cust_code   = $_GET['cust_code'];
        $fetchcustomer = $db->query("SELECT * FROM customer WITH (NOLOCK) WHERE cust_code='$cust_code'");
        $rowcustomer = $fetchcustomer->fetch();
        $cust_name   = $rowcustomer['cust_name'];
        $br_code     = $rowcustomer['br_code'];
        $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$br_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
        $br_name     = $fetchbrname->br_name;
        $company     = $rowcustomer['comp_code'];
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

    }
    else{
        $br_code = "";
        $br_name  = "";
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
  <div class="col-sm-8" style="border-right: 1px solid #2160a0;">           
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
                    <input class="form-control border-form " onkeyup="stnname_val(this)"  name="stnname" type="text" id="stnname" placeholder="Station Name" readonly>
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
                <input placeholder="" class="form-control border-form br_code text-uppercase" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?php echo $br_code; ?>" name="br_code" type="text" id="br_code">
                <div id="result"></div>
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td>Branch Name</td>
            <td>
                <div class="col-sm-9">
                <input class="form-control border-form" tabindex="-1" name="br_name" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?php echo $br_name; ?>" type="text" id="br_name" >
                   
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
        <input id="cust_name" name="cust_name" tabindex="-1" value="<?php if(isset($_GET['cust_code'])){ echo $cust_name; }?>" class="form-control input-sm" _onfocusout="cust_cnote_verify()" type="text" oninput="resetdestn();$('#podoption').attr('selected', true);">
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
            <select id="podorigin" name="podorigin" tabindex="-1" class="form-control input-sm" onchange="calculateamount();pod_verify();checkgroupisvalid();cust_cnote_verify()">
                <option id="podoption"></option>
                <?php echo podorigin($db,$stationcode,$getpod); ?>
            </div>
        </td>
        </tr>
        
        <tr>
          <td>Booking Date</td>
          <td>
          <div class="col-sm-9">
           <input type="date" <?=(($cnote_type=='Virtual')?'readonly':'')?> id="bdate" name="bdate" class="today-max form-control <?php if(isset($_GET['cust_code'])){ echo ''; }else{ echo 'today'; } ?>" value="<?php if(isset($_GET['cust_code'])){ echo $bdate; } ?>">
          </td>
          </div>
          <td>Booking Staff</td>
        <td>
        <div class="col-sm-9">
            <input type="text" id="bkgstaff" tabindex="-1" name="bkgstaff" class="form-control">
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
                <input type="text" id="mf_no" tabindex="-1" name="mf_no" class="form-control">
            </div>
        </td>         
        <td>Other Charges</td>
        <td>
            <div class="col-sm-9">
                 <input type="number" min="0" tabindex="-1" onkeyup="($(this).val().trim()==''?this.value=0:'')" id="other_charges" name="other_charges" value="0" class="form-control" readonly>
            <div>
        </td>          
        </tr>
        <tr>
        <td>Packing Charges</td>
        <td>
            <div class="col-sm-9">
                <input type="number" min="0" tabindex="-1" id="packing_charges" readonly name="packing_charges" class="form-control" value='0' onkeyup="($(this).val().trim()==''?this.value=0:'')">
            </div>
        </td>
        <td>Insurance Charges</td>
        <td>
            <div class="col-sm-9">
                <input type="number" tabindex="-1" id="insurance_charges" readonly name="insurance_charges" class="form-control" value='0' onkeyup="($(this).val().trim()==''?this.value=0:'')">
            </div>
        </td>
        </tr>
        <tr>
        <td>Cnote Type</td> 
        <td>
            <div class="col-sm-9">
                <select id="cnote_type" tabindex="-1" name="cnote_type" class="form-control" onchange="cust_cnote_verify();verifyvirtual(this);$('#cnote_no').val('');consignee_required()">
                    <option <?=(($cnote_type=='Regular')?'Selected':'')?> value="Regular">Regular</option>
                    <option <?=(($cnote_type=='Virtual')?'Selected':'')?> value="Virtual">Virtual</option>
                </select>
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
                                        <td>
                                        <input type="text" <?=(($cnote_type=='Virtual')?' Readonly ':' ')?> id="cnote_no" name="cnote_no" class="form-control" value="<?php if(isset($_GET['cust_code'])){ echo $cnote_number; }?>" onfocusout="operationdata();cnoteval()">
                                        <input type="hidden" id="cnote_serial" name="cnote_serial" class="form-control" value="<?php if(isset($_GET['cust_code'])){ echo $cnote_serial; }?>">
                                        <p id="cnote_no_validation" class="form_valid_strings">Please enter Cnote No.</p>
                                        <p id="cnote_no_invalid" class="form_valid_strings">Cnote No Invalid.</p>   
                                        </td>
                                         <td style="width:180px!important">
                                            <div class="autocomplete col-sm-12">
                                            <input type="text" name="destination" id="destn" class="form-control destbox text-uppercase" onfocusout="calculateamount()">
                                            <p id="dest_validation" class="form_valid_strings">Enter Valid Destination.</p>
                                            <p id="destnull_validation" class="form_valid_strings">Please Enter Destination.</p>
                                            </div>
                                            </td>
                                        <td>
                                            <input name="wt" id="wt" class="form-control " value="0" aria-invalid="false" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.001" onfocusout="calculateamount();" oninput="$('#wt_validation').css('display','none');">
                                            <p id="wt_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                            <p id="max_weight" class="form_valid_strings">Weight exceed to Maximum.</p>
                                        </td>
                                        <td>
                                            <input name="v_wt" id="v_wt" class="form-control" value="0" aria-invalid="false" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="00.01" onfocusout="calculateamount()">
                                            <!-- <p id="wt_validation" class="form_valid_strings">Please Fillout the fields.</p> -->
                                        </td>
                                        <td>
                                            <input type="text" name="pcs" class="form-control" id="pcs" value="1" _onkeyup="calculateamount()">
                                            <p id="pcs_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                        </td>
                                        <td>
                                            <select name="mode" id="mode" class="form-control " onchange="calculateamount()">
                                           <?php echo loadmode($db); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="amount" id="amount" class="form-control " min="0" placeholder="0">
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
                         <td style="width:23%"><input type="number" oninput="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" maxlength="10" class="form-control" placeholder="Consignee Mobile" name="consignee_mob" id="consignee_mob"></td>
                         <td style="width:20%"><input type="text" class="form-control" placeholder="Consignee Name" name="consignee" id="consignee"></td>
                         <td><input type="text" class="form-control" placeholder="Address" name="consignee_addr" id="consignee_addr"></td>
                         <td style="width:13%"><input type="text" class="form-control" placeholder="Pincode" name="consignee_pin" id="consignee_pin"></td>
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
<div class="col-sm-4" style="height:100px">
<div class="col-sm-12 data-table-div" style="margin-top: -1px;height: 700px;overflow-y:scroll">

<div class="title_1 title ">
<p style="font-weight:600;font-size:20px;color:#2160A0;margin-top:6px;margin-left:10px;">Operation Data</p>

</div>
<table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
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

<div class="col-sm-8 data-table-div">

<table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
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
      <tbody>
        <?php 

            
            if(isset($_GET['cust_code']))
            {
                date_default_timezone_set('Asia/Kolkata');
                $today_date        = date('Y-m-d H:i:s');

                $query = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE cust_code='$cust_code' AND date_added='$today_date' ORDER BY credit_id DESC");
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
                  echo "<td>".round($row->amt)."</td>";
                  echo "<td>".$row->user_name."</td>";
                  echo "<td>".$row->dest_code."</td>";
                  echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                      <a  class='btn btn-primary btn-minier' data-rel='tooltip' title='View'  >
                        <i class='ace-icon fa fa-eye bigger-130'></i>
                      </a>

                       <a class='btn btn-success btn-minier' data-rel='tooltip' title='Edit'   >
                        <i class='ace-icon fa fa-pencil bigger-130'></i>
                       </a>

                       <a  class='btn btn-danger btn-minier bootbox-confirm' id='$row->cno' onclick='".delete($db,$user)."'>
                          <i class='ace-icon fa fa-trash-o bigger-130'></i>
                       </a>
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
    <input type="text" id="lock_status" value="<?php if(isset($_GET['cust_code'])){ echo $lock_status; } ?>" style="display:none">
    <input type="text" id="cno_status" value="" style="display:none">
    <!-- <input type="text" id="sp_rate_status" value="" style="display:"> -->
      </div>
      
</section>
<!-- inline scripts related to this page -->        
<script>
  $(document).ready(function(){
        $('.data-table').DataTable();
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
    pcs          = '1';
    $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{customer:customer,origin:origin,destination:destination,weight:weight,pcs:pcs,mode:mode,pod_amt:pod_amt},
            dataType:"text",
            success:function(amount)
            {
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
        else if($('#cnote_serial').val().trim()=='')
        {
            swal("Invalid Cnote Serial")
        }
        else if($('#dest_validation').css('display')==='block')
        {
            alert('Enter Valid Destination');
        }
        else if((parseFloat(opwt)-parseFloat($('#wt').val()))>=0.10)
        {
            swal('Entered wt is less than operation wt');
        }
        else if($('#mode').val().trim()=='')
        {
            alert('Enter Valid Mode');
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
            alert('Enter Valid Destination');
        }
        else if((parseFloat(opwt)-parseFloat($('#wt').val()))>=0.10)
        {
            swal('Entered wt is less than operation wt');
        }
        else if($('#cnote_serial').val().trim()=='')
        {
            swal("Invalid Cnote Serial")
        }
        else if($('#mode').val().trim()=='')
        {
            alert('Enter Valid Mode');
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
            alert('Enter Valid Destination');
        }
        else if($('#cnote_serial').val().trim()=='')
        {
            swal("Invalid Cnote Serial")
        }
        else if($('#mode').val().trim()=='')
        {
            alert('Enter Valid Mode');
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
        
           //Fetch Mode based on customer and destination
           var mode_origin = $('#origin').val();
           //var mode_cust = $('#cust_code').val();

            $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{dest_mode:destcode2,mode_origin:mode_origin,mode_cust:mode_cust},
                dataType:"text",
                success:function(modes)
                {
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
    if(cg_serial=='PRO' || cg_serial=='PRC' || cg_serial=='PRD'){
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
function cnoteval(){
            var cnote_no = $('#cnote_no').val();
            var cnote_station = $('#stncode').val();
            var podorigin = $('#podorigin').val();
            var v_flagging = $('#cnote_type').val();
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

                if(data[2]=='Allotted' || data[2]=='Billed' || data[2]=='Unbilled' || data[2]=='Invoiced'){
                    $('#cnote_no_invalid').css('display','none');
                    getcnote_serial(cnote_no,cnote_station,$('#podorigin').val());

                    if($('#cust_code').val().trim()=='')
                    {
                    $('#cust_code').val(data[3]);
                    $('#cust_name').val(data[4]); 
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
                            dataType:"text",
                            success:function(billdatas)
                            {
                                var values = billdatas.split(",");
                                //console.log(values);
                                if(values[5]!=''){
                                    $('#bdate').val(values[5]);
                                }
                                if(values[6]!=''){
                                    $('#mf_no').val(values[6]);
                                }
                                
                                if(values[2].trim()!=''){
                                $('#destn').val(values[2]);
                                }
                                if(values[0].trim()!=''){
                                $('#wt').val(parseFloat(values[0]));
                                }
                                if(values[1]!=''){
                                $('#mode').html('<option value="'+values[1]+'">'+values[1]+'</option>');
                                }
                                if(values[3]!=''){
                                    $('#amount').val(values[3]-values[7]-values[8]-values[9]);
                                    if(values[7]>0){
                                    $('#packing_charges').val(values[7]);
                                    }
                                    if(values[8]>0){
                                    $('#insurance_charges').val(values[8]);
                                    }
                                    if(values[9]>0){
                                    $('#other_charges').val(values[9]);
                                    }
                                }
                                if(values[4]!=''){
                                $('#pcs').val(values[4]);
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
                        $('#cnote_no').val(customercount[2]);
                        cnoteval();
                        $('#cno_status').val(customercount[4]);
                        getcnote_serial(customercount[2],'<?=$stationcode?>',$('#podorigin').val());
                    }
                    $('#lock_status').val(customercount[3]);
                    operationdata();
                    }
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
     $('#destn').val('');
     $('#wt').val('0');
     $('#v_wt').val('0');
     $('#amount').val('');
     $('#consignee').val('');
 }
</script>
<script>
 

var destination =[<?php  
    $sql1=$db->query("SELECT dest_code,dest_name FROM destination where comp_code='$stationcode'");
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
            $('#wt').val('0');
            $('#amount').val('0');
            $('#destn').val('');
        }

       }
    });
    }
}
</script>
<script>
function deletequeue(cnotenumber){
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
      var delcredit_euser = '<?=$user?>'
      $.ajax({
                url:"<?php echo __ROOT__ ?>credit",
                method:"POST",
                data:{delcredit_ecompany,delcredit_ecnote,delcredit_euser},
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
                $('#cnote_no').attr('readonly',true);
                $('#bdate').val("<?=date('Y-m-d')?>");
                $('#bdate').attr('readonly',true);
            }
            else{
                $('#cnote_no').attr('readonly',false);
                $('#bdate').attr('readonly',false);
            }
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
                    $('#consignee').val(cdata['consignee_name']);
                    $('#consignee_addr').val(cdata['consignee_addr']);
                    $('#consignee_pin').val(cdata['consignee_pincode']);
                }
            });
        }
        else{
            $('#consignee_mob').css('border','1px solid red');
        }
    });
    </script>
<script>
    $(window).on('load', function() {
        console.log("<?=$_GET['cnotenumber']?>"," flag = ",'<?=$cnodetailsofprev->vflag?>');


        if('<?=$_GET['cnotenumber']?>'.trim().length>0 && '<?=$cnodetailsofprev->vflag?>'==1){
            window.open('creditchallan?cno=<?=base64_encode(http_build_query(array($_GET['cnotenumber'])))?>&type=<?=base64_encode('printed')?>&customer=<?=base64_encode($cust_code)?>');
        }
        // if(<?=!empty($sessionbr_code)?>){
        //     fetchcustomer();
        //     getcustomersjson();
        //     console.log('cust code fetched');
        // }
    })
</script>
<script>
    function consignee_required(){
        var cnote_type = $("#cnote_type").val()
        if(cnote_type =='Regular'){
           $("#consignee_mob").attr('required',false);
        }else if(cnote_type =='Virtual'){
            $("#consignee_mob").attr('required',true); 
        }
    }
</script>