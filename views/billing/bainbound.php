<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'bainbound';
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
  
function loadmode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM mode ORDER BY mode_code ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->mode_code.'">'.$row->mode_name.'</option>';
         }
         return $output;    
        
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
function podorigin($db,$stationcode)
    {
        $output='';
        $query = $db->query("SELECT * FROM groups WHERE grp_type='sp' AND stncode='$stationcode'");
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
         }
         return $output;    
        
    }

    if(isset($_GET['cust_code']))
    {
        $cust_code   = $_GET['cust_code'];
        $fetchcustomer = $db->query("SELECT * FROM customer WHERE cust_code='$cust_code'");
        $rowcustomer = $fetchcustomer->fetch();
        $cust_name   = $rowcustomer['cust_name'];
        $br_code     = $rowcustomer['br_code'];
        $fetchbrname = $db->query("SELECT br_name FROM branch WHERE br_code='$br_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
        $br_name     = $fetchbrname->br_name;
        $company     = $rowcustomer['comp_code'];
        $fetchcnotenumber = $db->query("SELECT TOP ( 1 ) cnote_number FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$company' AND br_code='$br_code' AND cust_code='$cust_code' AND cnote_status='Allotted' OR cnote_status='UnBilled' AND cnote_serial NOT LIKE 'PRO' AND cnote_serial NOT LIKE 'PRC' AND cnote_serial NOT LIKE 'PRD' ORDER BY cnotenoid");
        $rowcnotenumber = $fetchcnotenumber->fetch(PDO::FETCH_ASSOC);
        $cnote_number= $_GET['cnotenumber']+1;
        //check cnote status
        $fetchcnostatus = $db->query("SELECT cnote_status FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$stationcode' AND cnote_number='$cnote_number'")->fetch(PDO::FETCH_OBJ);
        $lock_status = $rowcustomer['cust_status'];
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
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Billing</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">BA Inbound</li>
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
             <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>bainbound" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
    <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">BA Inbound Form</p>
        <div class="align-right" style="margin-top:-30px;">
    
        <a style="color:white;" href="<?php echo __ROOT__ . 'bainboundlist'; ?>">         
       <button type="button" class="btn btn-danger btnattr_cmn" >
        <i class="fa fa-times" ></i>
       </button></a>
            </div>
               </div> 
 
               <div class="content panel-body" style="background:#fff;border: 1px solid var(--heading-primary);">
               <div class="tab_form" style="border:0px;">
        <div class="col-sm-12" style="border-right: 1px solid #2160a0;">           
        <table class="table">	
           <tr>		
            <td>Company Code</td>
            <td>
                <div class="col-sm-12">
                     <input class="form-control border-form stncode" name="comp_code" type="text" id="stncode" readonly>
                 
                </div>
            </td>
              
            <td>Company Name</td>
            <td>
                <div class="col-sm-12">
                    <input class="form-control border-form " onkeyup="stnname_val(this)"  name="stnname" type="text" id="stnname" placeholder="Station Name" readonly>
                     <p id="stnname_val" class="form_valid_strings">Special Characters not allowed. </p> 
                     <p id="stnname_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>
       <!-- </tr>
           <tr>		 -->
            <td>Branch Code</td>
            <td><form method="POST" id="fetchcust">
                <div class="col-sm-12">
                <input placeholder="" class="form-control border-form br_code text-uppercase" onkeyup="fetchcustomer();branchverify()"  value="<?php if(isset($_GET['cust_code'])){ echo $br_code; } ?>" name="br_code" type="text" id="br_code">
                <div id="result"></div>
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
              
            <td>Branch Name</td>
            <td>
                <div class="col-sm-12">
                <input class="form-control border-form" name="br_name" value="<?php if(isset($_GET['cust_code'])){ echo $br_name; } ?>" type="text" id="br_name" onfocusout="fetchcustomer()">
                   
                </div>
            </td>
       </tr>
        <tr>
        <td>Customer Code</td>
        <td>
        <div class="col-sm-12 " >
            <input id="cust_code" <?php if(isset($_POST["br_code"])){ echo "autofocus";  } ?> name="cust_code"  value="<?php if(isset($_GET['cust_code'])){echo $_GET['cust_code'];} ?>" onkeyup="cust_cnote_verify();  resetdestn(); validmf_no()" class="form-control input-sm cust_code text-uppercase" type="text">
            <p id="customer_validation" class="form_valid_strings">Enter Valid Customer Code</p>
            </div>
                </form>
        </td>
        <!--  -->
        <td>Customer Name</td>
        <td>
        <div class="col-sm-12">
        <input id="cust_name" name="cust_name" value="<?php if(isset($_GET['cust_code'])){ echo $cust_name; }?>" class="form-control input-sm" type="text">
        </div>
        </td>
        <!-- </tr>
        <tr> -->
        <td>Origin</td>
        <td>
        <div class="col-sm-12">
        <input id="origin" name="origin" value="" class="form-control input-sm" type="text" readonly>
            </div>
        </td>
        <td>POD Origin</td>
        <td>
        <div class="col-sm-12">
            <select id="podorigin" name="podorigin" class="form-control input-sm" onchange="cust_cnote_verify(); calculateamount();">
                <option id="podoption"></option>
                <?php echo podorigin($db,$stationcode); ?>
            </div>
        </td>
        </tr>
        
        <tr>
          <td>Booking Date</td>
          <td>
          <div class="col-sm-12">
           <input type="date" id="bdate" name="bdate" class="form-control today">
          </td>
          </div>
          <td>Booking Staff</td>
        <td>
        <div class="col-sm-12">
            <input type="text" id="bkgstaff" name="bkgstaff" class="form-control">
            </div>
        </td>
          <!-- </tr>
                  
        <tr> -->
        <!-- <td>Cnote No</td>
        <td>
        <div class="col-sm-12">
            
            </div>
        </td> -->
        <td>Mf/Ref Number</td>
        <td>
        <div class="col-sm-12">
            <input type="text" id="mf_no" name="mf_no" class="form-control text-uppercase" value="<?php if(isset($_GET['mfno'])){ echo $_GET['mfno']; } ?>" onkeyup="validmf_no()">
            <p id="mf_noval" class="form_valid_strings">Enter Mf Number.</p>
            <p id="mf_validation" class="form_valid_strings">Enter Valid Mf Number.</p>
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
                                        <th class="text-center" style="width: 100px;">Wt</th>
                                        <th class="text-center" style="width: 100px;">V.Wt</th>
                                        <th class="text-center" style="width: 100px;">Pcs</th>
                                        <th class="text-center invoice_fields" style="width: 190px;">Mode</th>
                                        <th class="text-center invoice_fields" style="width: 100px;">Amount</th>                                      
                    
                                    </tr>
                                </thead>
                            
                                <tbody id="addinvoiceItem">
                                    <tr>
                                        <td>
                                        <input type="text" id="cnote_no" name="cnote_no" class="form-control" value="<?php if(isset($_GET['cust_code'])){ echo $cnote_number; }?>" onkeyup="cnoteval()" >
                                        <p id="cnote_no_validation" class="form_valid_strings">Please enter Cnote No.</p>
                                        <p id="cnote_no_invalid" class="form_valid_strings">Cnote No Invalid.</p>
                                        </td>
                                         <td>
                                            <div class="autocomplete col-sm-12">
                                            <input type="text" name="destination" id="destn" class="form-control destbox text-uppercase" onkeyup="calculateamount();modedestination()" onfocusout="modedestination()">
                                            <p id="dest_validation" class="form_valid_strings">Enter Valid Destination.</p>
                                            <p id="destnull_validation" class="form_valid_strings">Please Enter Destination.</p>
                                            </div>
                                        </td>
                                        <td>
                                            <input name="wt" id="wt" class="form-control " value="0" aria-invalid="false" type="text" onkeyup="calculateamount()" oninput="$('#wt_validation').css('display','none');">
                                            <p id="wt_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                            <p id="max_weight" class="form_valid_strings">Weight exceed to Maximum.</p>
                                        </td>
                                        <td>
                                            <input name="v_wt" id="v_wt" class="form-control" value="0" aria-invalid="false" type="text" onkeyup="calculateamount()">
                                            <!-- <p id="wt_validation" class="form_valid_strings">Please Fillout the fields.</p> -->
                                        </td>
                                        <td>
                                            <input type="text" name="pcs" class="form-control " id="pcs" value="1" onkeyup="calculateamount()">
                                            <p id="pcs_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                        </td>
                                        <td>
                                            <select name="mode" id="mode" class="form-control " onchange="calculateamount()">
                                           <?php echo loadmode($db); ?>
                                           </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control " readonly>
                                            <input type="text" name="amount" id="amount" class="form-control " min="0" placeholder="0" style="display:none">
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        <!-- </div> -->
                        </div>
        <!-- <table> -->
        <div style="padding-bottom:5px;padding-left:5px;">
                      
        <input type="text" class="form-control" placeholder="Consignee" style="width:40%;padding:15px;float:left" name="consignee" id="consignee">
        <div class="align-right btnstyle" >

        <button type="button" id="addanothercredit" class="btn btn-success btnattr_cmn" onclick="addanother();progress()" name="addanotherbainbound">
        <i class="fa  fa-save"></i>&nbsp; Save & Add
        </button>

        <button type="button" id="addcredit" class="btn btn-primary btnattr_cmn" onclick="add()" name="addbainbound">
        <i class="fa  fa-save"></i>&nbsp; Save
        </button>

        <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
        <i class="fa fa-eraser"></i>&nbsp; Reset
        </button>   


        </div>
        </div>
        <!-- </table> -->
       
</div>
</form>
<!-- Billed data table -->


<!-- operation data table -->
<!-- <div class="col-sm-4" style="height:100px">
<div class="col-sm-12 data-table-div" style="margin-top: -1px;">

<div class="title_1 title ">
<p style="font-weight:600;font-size:20px;color:#2160A0;margin-top:6px;margin-left:10px;">Operation Data</p>

</div>
<table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
<thead><tr>
   <th class="center">
       <label class="pos-rel">
           <input type="checkbox" class="ace" />
       <span class="lbl"></span>
       </label>
     </th>
           <th>Tdate</th>
           <th>Cnote</th>
           <th>Wt</th>
           <th>Destination</th>
           <th>Mode</th>
           <th>Branch</th>
          
     </tr>
    </thead>
     <tbody>
     <?php 
       
      
     ?>
</tbody>
</table>
</div>



</div> -->

</div>


</div>

<div class="col-sm-12 data-table-div">

<table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
<thead><tr class="form_cat_head">
    <th class="center">
        <label class="pos-rel">
            <input type="checkbox" class="ace" />
        <span class="lbl"></span>
        </label>
      </th>
      <th>MF No</th>
      <th>Cnote No</th>
      <th>Customer</th>
      <th>Destination</th>
      <th>Weight</th>
      <th>Mode</th>
      <!-- <th>Amount</th> -->
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

                $query = $db->query("SELECT * FROM bainbound WHERE mf_no='".$_GET['mfno']."' AND date_added='$today_date' ORDER BY bainbound_id DESC");
                while ($row = $query->fetch(PDO::FETCH_OBJ))
                {
                  echo "<tr>";
                  echo "<td class='center first-child'>
                          <label>
                              <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                              <span class='lbl'></span>
                            </label>
                        </td>";
                  
                  echo "<td>".$row->mf_no."</td>";
                  echo "<td>".$row->cno."</td>";
                  echo "<td>".$row->bacode."</td>";
                  echo "<td>".$row->dest_code."</td>";
                  echo "<td>".number_format((float)$row->wt, 3)."</td>";
                  echo "<td>".$row->mode_code."</td>";                           
                //   echo "<td>".$row->amt."</td>";
                  echo "<td>".$row->user_name."</td>";
                  echo "<td>".$row->dest_code."</td>";
                  echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                      <a  class='btn btn-primary btn-minier' data-rel='tooltip' title='View'  >
                        <i class='ace-icon fa fa-eye bigger-130'></i>
                      </a>

                       <a class='btn btn-success btn-minier' data-rel='tooltip' title='Edit'   >
                        <i class='ace-icon fa fa-pencil bigger-130'></i>
                       </a>

                       <a  class='btn btn-danger btn-minier bootbox-confirm' >
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
    <input type="text" id="lock_status"  value="<?php if(isset($_GET['cust_code'])){ echo $lock_status; } ?>" style="display:none">
    <input type="text" id="cno_status" value="<?php if(isset($_GET['cust_code'])){ echo $fetchcnostatus->cnote_status; } ?>" style="display:none">
    <!-- <div class="progress active">
            <div class="progress-bar progress-bar-striped progress-bar-success"  style="width:0%" ></div>
        </div> -->
      </div>
     
</section>
<!--    
<script>
   function progress(){
    $loadingtime = 60;
    $(".progress-bar").animate({
        width: "100%"
    }, $loadingtime);

    $('#header').css('filter','blur(5px)');
    $('nav').css('filter','blur(5px)');
    $('section').css('filter','blur(5px)');
    $('footer').css('filter','blur(5px)');
    $(document).on('keydown', disableF5);
    }
</script>
<script>
 function disableF5(e) {
      if ((e.which || e.keyCode) == 116) e.preventDefault(); 
    };

</script> -->
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
            }
    });
  });

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
    function addanother()
    {
        if($('#cnote_no').val().trim()=='')
        {
            $('#cnote_no_validation').css('display','block');
        }
        else if($('#cnote_no_invalid').css('display')==='block')
        {
            swal('Enter Valid Cnote');
        }
        else if($('#cno_status').val()=='Billed' || $('#cno_status').val()=='Invoiced')
        {
            swal('Entered Cnote Already Billed');
        }
        else if($('#pcs').val().trim()=='')
        {
            $('#pcs_validation').css('display','block');
        }
        else if($('#destn').val().trim()=='')
        {
            $('#destnull_validation').css('display','block');
        }
        else if($('#wt').val().trim()=='' && $('#v_wt').val().trim()=='')
        {
            $('#wt_validation').css('display','block');
        }
        else if($('#dest_validation').css('display')==='block')
        {
            swal('Enter Valid Destination');
        }
        else if($('#mode').val().trim()=='')
        {
            swal('Enter Valid Mode');
        }
        else if($('#mf_no').val().trim()=='')
        {
            swal('Please Enter Mf Number');
            $('#mf_noval').css('display','block');
        }
        // else if($('#lock_status').val().trim()=='Locked')
        // {
        //     swal({
        //     icon: "warning",
        //     text: "This Customer credit note is temporarily locked due to pending invoices",
        //     });
        // }
        else{
            if(parseFloat($('#wt').val())>0 || parseFloat($('#v_wt').val())>0)
            {
                if(checkcnoandcustomer()==0){
                    swal("Entered Cnote is allotted to another customer !! Please Enter valid customer code");
                }
                else{
                $('#addanothercredit').html("<i class='fa fa-spinner'></i>&nbsp; Save & Add");
                $('#addanothercredit').attr('type','submit');
                $('#addanothercredit').attr('name','addanotherbainbound');
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
        else if($('#cnote_no_invalid').css('display')==='block')
        {
            alert('Enter Valid Cnote');
        }
        else if($('#cno_status').val()=='Billed' || $('#cno_status').val()=='Invoiced')
        {
            swal('Entered Cnote Already Billed');
        }
        else if($('#pcs').val().trim()=='')
        {
            $('#pcs_validation').css('display','block');
        }
        else if($('#destn').val().trim()=='')
        {
            $('#destnull_validation').css('display','block');
        }
        else if($('#wt').val().trim()=='')
        {
            $('#wt_validation').css('display','block');
        }
        else if($('#dest_validation').css('display')==='block')
        {
            swal('Enter Valid Destination');
        }
        else if($('#mode').val().trim()=='')
        {
            swal('Enter Valid Mode');
        }
        else if($('#mf_no').val().trim()=='')
        {
            swal('Enter Mf Number');
            $('#mf_noval').css('display','block');
        }
        // else if($('#lock_status').val().trim()=='Locked')
        // {
        //     swal({
        //     icon: "warning",
        //     text: "This Customer credit note is temporarily locked due to pending invoices",
        //     });
        // }
        else{
            if(parseFloat($('#wt').val())>0 || parseFloat($('#v_wt').val())>0)
            {
                if(checkcnoandcustomer()==0){
                    swal("Entered Cnote is allotted to another customer !! Please Enter valid customer code");
                }
                else{
                $('#addcredit').html("<i class='fa fa-spinner'></i>&nbsp; Save");
                $('#addcredit').attr('type','submit');
                $('#addcredit').attr('name','addbainbound');
            }
            }else{
                $('#wt_validation').css('display','block');
            }
        }
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
//check the entered cnote is alloted to entered customer
    function checkcnoandcustomer(){
       var verifyentrycustomer = $('#cust_code').val();
       var verifyentrystation = $('#stncode').val();  
       var verifyentrycnote = $('#cnote_no').val();
       $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{verifyentrycustomer,verifyentrystation,verifyentrycnote},
            dataType:"text",
            success:function(valid)
            {
                return valid;
            }
            });
    }
</script>
<script>
    $('#wt').keyup(function(){
        if($(this).val()>=100){
        swal('Entered weight is 100 kg or more than 100kg !\n दर्ज वजन 100 किलोग्राम या 100 किलोग्राम से अधिक है !');
    }
    });
    $('#v_wt').keyup(function(){
        if($(this).val()>=100){
        swal('Entered weight is 100 kg or more than 100kg !\n दर्ज वजन 100 किलोग्राम या 100 किलोग्राम से अधिक है !');
    }
    });
</script>
<script>
  $(document).ready(function(){
     
        $('#br_code').keyup(function(){
            var br_code = $('#br_code').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{br_code:br_code},
            dataType:"text",
            success:function(br_name)
            {
                $('#br_name').val(br_name);
            }
            });
        });
         //fetch code of entering name
         $('#br_name').keyup(function(){
            var br_name = $(this).val();
            var br_namestn = $('#stncode').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{br_name:br_name,br_namestn:br_namestn},
            dataType:"text",
            success:function(br_code)
            {
                $('#br_code').val(br_code);
                branchverify();
            }
            });
        });

    });
</script>
<script>
    
        //Verify Destination is Valid
        function modedestination(){
         var destcode2 = $('#destn').val().split('-')[0];
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
           //Fetch Mode based on customer and destination
           var mode_origin = $('#origin').val();
         //  var mode_cust = $('#cust_code').val();

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
    }
</script>
<script>
            //Verifying Branch code is Valid
    function branchverify(){
        var branch_verify = $('#br_code').val();
        var stationcode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{branch_verify:branch_verify,stationcode:stationcode},
            dataType:"text",
            success:function(branchcount)
            {
            if(branchcount == 0)
            {
                $('#brcode_validation').css('display','block');
            }
            else{
                $('#brcode_validation').css('display','none');
            }
            }
    });
    }
</script>
<script>
            //fetch code of entering name
            $('#cust_name').keyup(function(){
            var cccust_name = $(this).val();
            var cc_namestn = $('#stncode').val();
            var cc_namebr = $('#br_code').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{cccust_name:cccust_name,cc_namestn:cc_namestn,cc_namebr:cc_namebr},
            dataType:"text",
            success:function(cust_code)
            {
                $('#cust_code').val(cust_code);
                cust_cnote_verify();
            }
            });
            });
</script>
<script>
function cnoteval(){
            if($('#cnote_no').val().trim()==''){

            }
            else{
            var cnote_no = $('#cnote_no').val().split(/([0-9]+)/)[1];
            var cnote_station = $('#stncode').val();
            if($('#podorigin').val()=='PRO' || $('#podorigin').val()=='PRC' || $('#podorigin').val()=='PRD'){
            var podorigin = $('#podorigin').val();
            }
            else{
            var podorigin = 'gl';
            }
            $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{cnote_no:cnote_no,cnote_station:cnote_station,podorigin:podorigin},
            dataType:"text",
            success:function(value)
            { 
                var data = value.split(",");  
                $('#cno_status').val(data[2]);
                if(data[2]=='Allotted' || data[2]=='Billed' || data[2]=='Unbilled'){
                    $('#cnote_no_invalid').css('display','none');
                    $('#br_code').val(data[0]);
                    $('#br_name').val(data[1]);
                    $('#cust_code').val(data[3]);
                    $('#cust_name').val(data[4]); 
                    //cust_cnote_verify(); 
                }else{
                    $('#cnote_no_invalid').css('display','block');
                }
                             
            }
            });
            // $('#cnoteno1').val(cnote_no);
            $('#cnote_no_validation').css('display','none');
        }
        }
</script>
<script>
            //Verifying Customer is Valid
        function cust_cnote_verify(){
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
                if(customercount[0] == 0)
                {
                    $('#customer_validation').css('display','block');
                }
                else{
                    $('#customer_validation').css('display','none');
                    $('#cust_name').val(customercount[1]);
                    if($('#cnote_no').val().trim()==''){
                    $('#cnote_no').val(customercount[2]);
                    }
                    $('#lock_status').val(customercount[3]);
                    $('#cno_status').val(customercount[4]);
                    }
            }
        });
    
    }
</script>
<script>
function validmf_no(){
    $('#mf_noval').css('display','none');
    var mf_cust = $('#cust_code').val();
    var mf_no = $('#mf_no').val();
    $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{mf_cust:mf_cust,mfmf_no:mf_no},
            dataType:"text",
            success:function(isvalid)
            {
               if(isvalid==0){
                $('#mf_validation').css('display','block');
               }
               else{
                $('#mf_validation').css('display','none');
               }
            }
            });

}
</script>
<script>
        $('#cnote_no').focusout(function(){
        var pod = $(this).val().split(/([0-9]+)/);
        $('#podorigin').html("<option value='"+pod[0]+"'>"+pod[0]+"</option>");
        $('#cnote_no').val(pod[1]);
        cnoteval();
    });
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
function fetchcustomer(){
   var branch8 = $('.br_code').val();
   var comp8 = $('.stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("cust_code"), cust8); 
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

function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cust_code').value = "";
    document.getElementById('cust_name').value = "";
    document.getElementById('cust_addr').value = "";
    document.getElementById('cust_mobile').value = "";
    document.getElementById('consignee').value = "";
    document.getElementById('book_counter').value = "";
    document.getElementById('book_counter_name').value = "";
    document.getElementById('bk_date').value = "";
    document.getElementById('bkgstaff').value = "";
    document.getElementById('cnote_no').value = "";
    document.getElementById('mf_no').value = "";
    document.getElementById('cnote_no1').value = "";
    document.getElementById('dest_check').value = "";
    document.getElementById('pincode_check').value = "";
    document.getElementById('pincode_autocorr').value = "";
    document.getElementById('dest_autocorr').value = "";
    document.getElementById('wt').value = "";
    document.getElementById('pcs').value = "";
    document.getElementById('mode').value = "";
    document.getElementById('amount').value = "";
}
</script>