        <!-- Autocomplete -->
        <script type="text/javascript" src="vendor/jqueryautocomplete.js"></script>
        <link rel="stylesheet" href="vendor/jqueryautocompletestyle.css">
        <!-- end -->
<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

error_reporting(0);
function authorize($db,$user)
{
    $module = 'cash';
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
        $query = $db->query('SELECT * FROM mode ORDER BY mid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->mode_code.'">'.$row->mode_name.'</option>';
         }
         return $output;    
        
    }
    //Fetch Current Date
function currentdate()
    {
        $output='';
        date_default_timezone_set('Asia/Kolkata');
        $date_added      	= date('Y-m-d H:i:s');       
            
        //menu list in home page using this code    
        $output .=$date_added ;

         return $output;    
        
    }

    function podorigin($db,$stationcode,$podorigin)
    {
        $output='';
        $query = $db->query("SELECT * FROM groups WHERE grp_type='sp' AND stncode='$stationcode'");
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option '.(($podorigin==$row->cate_code)?"Selected":"").' value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
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

    function delete($db,$user){
        $module = 'cashdelete';
        $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
        $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
        $user_group = $rowusergrp->group_name;
      
        $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
            if($verify_user->rowCount()=='0'){
                return 'swal("You Dont Have Permission to Delete this Record!")';
            }
            else{
                return "deletequeue(this.id,this.value,this.name,this.role)";
            }
      }

      $brcode = "";
        $brname = "";
        $podorigin = '';
//branch filter conditions
 $sessionbr_code = $datafetch['br_code'];
 $getbrname = $db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND br_code='$sessionbr_code'")->fetch(PDO::FETCH_OBJ);
//branch filter conditions
    if(!empty($sessionbr_code)){
        $brcode = $sessionbr_code;
        $brname = $getbrname->br_name;
    }
// changes by zabi
    $user_cc = $datafetch['cust_code'];
 if(!empty($user_cc)){
    $getccname = $db->query("SELECT cc_name FROM collectioncenter WHERE stncode='$stationcode' AND cc_code='$user_cc'")->fetch(PDO::FETCH_OBJ);
    $user_ccname = $getccname->cc_name;
 }
 else if(isset($_GET['cc_code'])){
     $collcentr_code = $_GET['cc_code'];
     $collcentr_name = $_GET['cname'];
 }
 else{
    $collcentr_code = "";
    $collcentr_name = "";
 }

 // changes by zabi
    if(isset($_GET['cno'])){
        $cno3 = $_GET['cno']+1;
        $verifycno = $db->query("SELECT * FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$stationcode' AND cnote_status='Allotted' AND cnote_number='$cno3'");
        $cnodata = $verifycno->fetch(PDO::FETCH_OBJ);
        if($verifycno->rowCount()==0){
            $cnote_number= "";
            $brcode = $cnodata->br_code;
            $brname = $cnodata->br_name;
        }
        else{
            $cnote_number= $_GET['cno']+1;
            $brcode = $cnodata->br_code;
            $brname = $cnodata->br_name;
        }
    $cnote_type = base64_decode($_GET['cnotetype']);
    $podorigin = $_GET['podorigin'];
        //echo $cnote_type;exit;
    $bookcounter = $_GET['bc'];
    $bookcountername = $_GET['bn'];
    $collcentr_code = $_GET['cc'];
    $collcentr_name = $_GET['cn'];
    $cnserial =  $_GET['cnote_serial'];

      $getgst = $db->query("SELECT * FROM cash WHERE cno='".$_GET['cno']."' AND comp_code='$stationcode' and cnote_serial='".$_GET['cnote_serial']."'")->fetch(PDO::FETCH_OBJ);
    
    $details = array(
        'sh_name' => $_GET['sh_name'],
        'sh_mob' => $_GET['sh_mob'],
        'sh_addr' => $_GET['sh_addr'],
        'sh_pin' => $_GET['sh_pin'],
        'ch_shipper' => $_GET['ch_shipper'],
        'c_name' => $_GET['c_name'],
        'c_mob' => $_GET['c_mob'],
        'c_addr' => $_GET['c_addr'],
        'c_pin' => $_GET['consignee_pin'],
        'ch_shipper2' => $_GET['ch_shipper2']
     );

    $cc_total_amount = $db->query("SELECT SUM(ramt) AS amount FROM cash WHERE comp_code='$stationcode' AND br_code='$brcode' AND cc_code='$collcentr_code' AND cc_status is NULL AND ccinvno is NULL");
    $cc_row = $cc_total_amount->fetch(PDO::FETCH_OBJ);
    }
    

?>
<head>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
      
</head>
<style>
   
   html {
    height:100%;
    width:100%;
    margin:0%;
    padding:0%;
    overflow-x: hidden;
}

body {
    overflow-x: initial;
}
/* .hasDatepicker {
    position: relative;
    z-index: 9999;
} */
.ui-datepicker { z-index: 10000 !important; }
.ui-datepicker td > a.ui-state-active {
    background-color: #2283c5;
    color: #196963;
}
.swal-button--cancel{
    display: none;
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
                                        <a href="<?php echo __ROOT__ . 'cashlist'; ?>">Cash List</a>
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
                                  Cash Added successfully.
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
                   ?>
                
             </div>
            <form method="POST" autocomplete="off" class="form-horizontal" name="formdestination" id="formdestination">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Bulk Cnote Entry Form</p>
                <div class="align-right" style="margin-top:-30px;">
                

            <a style="color:white" href="<?php echo __ROOT__ . 'cashlist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
         
            </div> 
        <div class="content panel-body">


          <div class="tab_form">


           <table class="table">	
           <tr>		
            <td>Company Code</td>
            <td>
                <div class="col-sm-12">
                     <input class="form-control border-form" name="comp_code" type="text" id="stncode" readonly>
                </div>
            </td>
              
            <td>Company Name</td>
            <td>
                <div class="col-sm-12">
                    <input class="form-control border-form " onkeyup="stnname_val(this)"  name="stnname" type="text" id="stnname" placeholder="Station Name" readonly>
                     <p id="stnname_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>
      	
            <td>Branch Code</td>
            <td>
                <div class="col-sm-12 autocomplete">
                <input class="form-control border-form br_code text-uppercase" name="br_code" type="text" id="br_code" <?=(!empty($sessionbr_code)?" readonly":"")?> value="<?=$brcode;?>" <?=(empty($cno3)?" autofocus ":"")?>>
                     <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td>Branch Name</td>
            <td>
                <div class="col-sm-12">
                    <input class="form-control border-form" tabindex="-1" name="br_name" type="text" id="br_name" <?=(!empty($sessionbr_code)?" readonly":"")?> value="<?=$brname;?>">
                     <p id="stnname_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>
       </tr>
        <tr>
        <td>CollectionCenter Code</td>
          <td>
          <div class="col-sm-12">
           <input type="text" id="cc_code" name="cc_code" class="form-control text-uppercase" <?=(!empty($user_cc)?" readonly value='$user_cc'":" value='$collcentr_code' ")?>  onfocusout="cc_total_amount();">
           <p id="cccode_validation" class="form_valid_strings">Enter Valid CollectionCenter</p>  
        </td>
          </div>
          <td>CollectionCenter Name</td>
        <td>
        <div class="col-sm-12">
            <input type="text" id="cc_name" name="cc_name" tabindex="-1" class="form-control " <?=(!empty($user_cc)?" readonly value='$user_ccname'":" value='$collcentr_name' ")?>>
            </div>
        </td>
        <td>Booking Counter Code</td>
          <td>
          <div class="col-sm-12">
           <input type="text" id="book_counter" name="book_counter" tabindex="-1" class="form-control text-uppercase" value="<?=(isset($bookcounter)?$bookcounter:'')?>">
          </td>
          </div>
          <td>Booking Counter Name</td>
        <td>
        <div class="col-sm-12">
            <input type="text" id="book_counter_name" tabindex="-1" name="book_counter_name" class="form-control " value="<?=(isset($bookcounter)?$bookcountername:'')?>">
            </div>
        </td>
         
         
        </tr>
               
        <tr>
       
        <td>Origin</td>
        <td>
        <div class="col-sm-12">
            <input id="origin" name="origin" value="" tabindex="-1" class="form-control input-sm" type="text" readonly>
            </div>
        </td>
        <td>POD Origin</td>
        <td>
        <div class="col-sm-9">
            <select id="podorigin" tabindex="-1" name="podorigin" class="form-control input-sm" onchange="calculateamount();bacnoteverify();getcashcnotedetail();checkpod();">
                <option id="podoption"></option>
                <?php echo podorigin($db,$stationcode,$podorigin); ?>
            </div>
        </td>
        <td>Booking Staff</td>
        <td>
        <div class="col-sm-12">
            <input type="text" id="bkgstaff" tabindex="-1" name="bkgstaff" class="form-control">
            </div>
        </td>
        <td>Cnote Type</td>
        <td>
            <div class="col-sm-9">
                <select id="cnote_type" tabindex="-1" name="cnote_type" class="form-control" onchange="verifyvirtual(this);verify_bacnoteverify();$('#cnote_no').val('');validatecnoteverify();">
                <option <?=(($cnote_type=='Virtual')?'Selected':'')?> value="Virtual">Virtual</option>
                    <option <?=(($cnote_type=='Regular')?'Selected':'')?> value="Regular">Regular</option>
                    
                </select>
            </div>
        </td>
        </tr>
                  <tr>
                  <td>Payment Mode</td>
        <td>
        <div class="col-sm-12">
            <select id="payment_mode" name="payment_mode" tabindex="-1" class="form-control">
                <option value="Cash">Cash</option>
                <option value="NEFT">NEFT</option>
                <option value="Cheque">Cheque</option>
                <option value="UPI">UPI</option>
                </select>
            </div>
                </td>
                <td>Payment Reference</td>
        <td>
        <div class="col-sm-12">
            <input type="text" id="payment_reference" tabindex="-1" name="payment_reference" class="form-control">
            </div>
        </td>
      <td>GST</td>
        <td>
            <div class="col-sm-12">
                <input type="text" id="gstin" value="<?=(($_GET['ch_shipper']==1)? $getgst->gst : '')?>" name="gstin" class="form-control" tabindex="-1" onfocusout="(($(this).val().trim().length>0)?gst_val(this):$('#gst_val').css('display','none'))">
                <p id="gst_val" class="form_valid_strings">Enter valid GSTIN.</p>

            </div>
        </td>
        <td>Mf/Ref Number</td>
        <td>
        <div class="col-sm-12">
            <input type="text" id="mf_no" name="mf_no" tabindex="-1" class="form-control text-uppercase">
            </div>
                </td>	
              
        </tr>
        <tr>
                <td>Booking Date</td>
          <td>
          <div class="col-sm-12">
          <input type = "text" name="bdate" <?=((empty($cnote_type))? 'readonly' :(((($cnote_type=='Virtual'))? 'readonly' : '')))?> tabindex="-1" class="form-control" id = "bk_date" value="<?=date('d-M-Y')?>" onkeypress="return false;">
           </div>
          </td>
          <td>Contents</td>
         
          <td>
          <div class="col-sm-12">
          
          <input type="text" tabindex="-1" id="contents" name="contents" class="form-control">  
         
           </div>
          </td>
          <td>Declared Value</td>
         
         <td>
         <div class="col-sm-12">
         
         <input type="number" tabindex="-1" min="0" value="0" step="0.01" id="dc_value" name="dc_value" class="form-control">
         </div>
         </td>
         <td>Vehicle No</td>
         
         <td>
         <div class="col-sm-12">
         
         <input type="text" tabindex="-1" id="vechicle_no" name="vechicle_no" class="form-control" readonly>
        
          </div>
         </td>
          <!-- <td>cnote_serial</td> -->
          <td>
          <div class="col-sm-12">
           <input type="hidden" id="cnser"  name="cnser" class="" value="<?=$cnserial;?>"  >     
          
          </div>
          </td>
        </tr>
        <tr>
                <td>CC Total Billed Amt</td>
          <td>
          <div class="col-sm-12">
          <input type = "text" name="cc_total_billed_amt" class="form-control" id = "cc_total_billed_amt" value="<?=number_format($cc_row->amount,2);?>" readonly>
           </div>
          </td>
        </tr>
    </tbody>
              </table>
           </table>
           
                            <div class="col-sm-12">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                      
                                        <th class="text-center product_field">From Cnote Number</th>
                                        <th class="text-center product_field">To Cnote Number</th>
                                        <th class="text-center">Destination</th>
                                        <th class="text-center" style="width: 100px;">Wt</th>
                                        <th class="text-center" style="width: 100px;">V.Wt</th>
                                        <th class="text-center" style="width: 100px;">Pcs </th>
                                        <th class="text-center invoice_fields" style="width: 190px;">Mode</th>
                                        <th class="text-center invoice_fields" style="width: 100px;">Amount</th>                                      
                    
                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                    <tr>
                                       
                                    <td style="width:300px!important">  
                                            <div class="autocomplete col-sm-12">
                                            <input type="text"  name="from_cnote" id="from_cnote"  class="form-control">
                                            
                                            </div>
                                          
                                        </td>
                                        <td style="width:300px!important">  
                                            <div class="autocomplete col-sm-12">
                                            <input type="text"  name="to_cnote" id="to_cnote" tabindex="-1" class="form-control" readonly>
                                            </div>
                                           
                                        </td>
                                        
                                        <td style="width:300px!important">  
                                            <div class="autocomplete col-sm-12">
                                            <input type="text"  name="destination" id="destn" onchange="pincodeview(this)" class="form-control" onfocusout="calculateamount();checkpod();">
                                            </div>
                                            <p id="dest_validation" class="form_valid_strings">Enter Valid Destination.</p>
                                        </td>
                                        <td>
                                            <input name="wt" id="wt" class="form-control "  aria-invalid="false" type="text" value="0" onfocusout="calculateamount();validate_cnote();">
                                            <p id="wt_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                        </td>
                                        <td>
                                            <input name="v_wt" id="v_wt" class="form-control " tabindex="-1"  aria-invalid="false" type="text" value="0" onfocusout="calculateamount()">
                                            <p id="wt_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                        </td>
                                        <td>
                                            <input type="text" name="pcs" class="form-control " tabindex="-1" id="pcs" value="1"  onfocusout="calculateamount()">
                                            <p id="pcs_validation" class="form_valid_strings">Please Fillout the fields.</p>
                                        </td>
                                        <td>
                                            <select name="mode" id="mode" class="form-control "  onchange="calculateamount()">
                                            <?php echo loadmode($db); ?>
                                            </select>
                                            
                                        </td>
                                        <td>
                                            <input type="text" name="amount" id="amount" class="form-control" placeholder="0" min="0" oninput="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null">
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- </div> -->
            <div class="col-sm-12">
                <div class="col-sm-12" style="padding-right:10px">
                   <table class="table table-bordered table-hover">
                        <tr style="color: #707070;font-weight: 400;background: repeat-x #f2f2f2;">
                            <th>Shipper Details</th>
                            <!-- <th>Shipper Address</th>
                            <th>Shipper Mobile</th> -->
                            
                        </tr>
                        
                        <tr >

                       
                    <td style="width:20%;"><div class="input-group"> <span class="input-group-addon my-addon">
                    <input type="checkbox" <?=(($details['ch_shipper']==1)? 'checked' : '')?>  aria-label="..." value='1' id="check1" name="check_shipper">
                    </span>
                    <input type="text" value="<?=(($details['ch_shipper']==1)? $details['sh_mob'] : '');?>" oninput="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="form-control box" placeholder="Shipper Mobile" name="shp_mob" id="shp_mob">
                    </div>
                  </td>
                    <td style="width:20%"><input type="text" data-id="shippername" value="<?=(($details['ch_shipper']==1)? $details['sh_name'] : '');?>"  class="form-control" placeholder="Shipper Name"  name="shp_name" id="shp_name">
                    
                </td>
                    <td><input type="text" data-id="shipper" class="form-control" value="<?=(($details['ch_shipper']==1)? $details['sh_addr'] : '');?>" placeholder="Shipper Address" name="shp_addr" id="shp_addr">
                    

                </td>
                    <td style="width:20%"><input type="text" value="<?=(($details['ch_shipper']==1)? $details['sh_pin'] : '');?>" class="form-control" placeholder="Shipper Pincode" name="shp_pin" id="shp_pin"></td>
                   
                    
                    </tr>
                </table>
                </div>
                <div class="col-sm-12">
                <table class="table table-bordered table-hover">
                    <tr style="color: #707070;font-weight: 400;background: repeat-x #f2f2f2;">
                        <th>Consignee Details</th>
                        <!-- <th>Consignee Address</th>
                        <th>Consignee Mobile</th> -->
                    </tr>
                    <tr>
                    <td style="width:20%;"><div class="input-group"> <span class="input-group-addon my-addon">
                    <input type="checkbox" class="hasborder" aria-label="..." <?=(($details['ch_shipper2']==1)? 'checked' : '')?> value='1' name="check_shipper2">
                    </span>
                    <input type="number" value="<?=(($details['ch_shipper2']==1)? $details['c_mob'] : '')?>" <?=(($cnote_type=='Virtual')?' required ':' ')?> class="form-control" placeholder="Consignee Mobile" maxlength="10" name="consignee_mob" id="consignee_mob"></div>
                    </td>  
                         <td style="width:20%"><input type="text" value="<?=(($details['ch_shipper2']==1)? $details['c_name'] : '')?>" class="form-control" placeholder="Consignee" name="consignee_name" id="consignee_name">
                       
                        </td>
                         <td><input type="text" class="form-control" value="<?=(($details['ch_shipper2']==1)? $details['c_addr'] : '')?>" placeholder="Consignee Address" name="consignee_addr" id="consignee_addr">
                       
                        </td>
                         <td style="width:20%"><input type="number" value="<?=(($details['ch_shipper2']==1)? $details['c_pin'] : '')?>" class="form-control" placeholder="Consignee Pincode" name="consignee_pin" id="consignee_pin"></td>
                       <input type="hidden"  class="form-control"  name="get_cr_inv" id="get_cr_inv">
                    </tr>
                </table>
                </div>
        </div>
        
           <table>
           <div style="padding-bottom:5px;padding-left:5px;">
                 
          <div class="align-right btnstyle" >
          <button type="button" class="btn btn-success btnattr_cmn" id="submitaddbilling" onclick="verify()">
          <i class="fa  fa-save"></i>&nbsp; Genrate Bulk Entry
          </button>

          <!-- <button type="button" class="btn btn-primary btnattr_cmn" id="submitadd" onclick="add()">
          <i class="fa  fa-save"></i>&nbsp; Save
          </button>

          <button type="button" class="btn btn-default btnattr_cmn" onclick="update()" name="updatecredit" id="updatecredit">
            <i class="fa fa-upload"></i>&nbsp; Update
          </button> 

         <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
          <i class="fa fa-eraser"></i>&nbsp; Reset
          </button>    -->


          </div>
          </div>
          </table>
        </form>
 
          </div>
 <!-- Fetching Mode Details in Table  -->
    <!-- <div class="col-12 data-table-div" >

      <table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
      <thead><tr class="form_cat_head">
          <th class="center">
              <label class="pos-rel">
                  <input type="checkbox" class="ace" />
              <span class="lbl"></span>
              </label>
            </th>
            <th>Cnote No</th>
            <th>Booking Counter</th>
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

                      date_default_timezone_set('Asia/Kolkata');
                      $today = date('Y-m-d H:i:s');
                      $query = $db->query("SELECT * FROM cash WHERE comp_code='$stationcode' AND user_name='$user' AND CAST(date_added AS DATE)='$today' ORDER BY cash_id DESC");
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
                        echo "<td>".$row->book_counter."</td>";
                        echo "<td>".$row->dest_code."</td>";
                        echo "<td>".number_format((float)$row->wt, 3)."</td>";
                        echo "<td>".$row->mode_code."</td>";                           
                        echo "<td>".round($row->ramt)."</td>";
                        echo "<td>".$row->user_name."</td>";
                        echo "<td>".$row->dest_code."</td>";
                        echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                            <a  class='btn btn-primary btn-minier' data-rel='tooltip' title='View'  >
                              <i class='ace-icon fa fa-eye bigger-130'></i>
                            </a>

                             <a class='btn btn-success btn-minier' data-rel='tooltip' title='Edit'   >
                              <i class='ace-icon fa fa-pencil bigger-130'></i>
                             </a>

                             <button type='button' class='btn btn-danger btn-minier bootbox-confirm' role='".COUNT($row->cash_receipt_no)."' name='$row->status' value='$row->cnote_serial' id='$row->cno' onclick='".delete($db,$user)."'>
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
                
                      
                  ?>

               
             </tbody>
          </table>
            </div> -->
            
              </div>
          </div>
          </div>
</section>
<script>
function suming(sells){
    var total = 0;
sells.forEach(function(sell) {
    total += +sell;
});
return total;
}


$(document).on('keyup','#from_cnote',function(){
    var data = [$('#from_cnote').val(),50];
    $('#to_cnote').val(suming(data));
});
</script>

<script>
    function checkpod(){
        var podorigin = $('#podorigin').val();
     var mode = $('#mode').val();
     if(podorigin=='PRO'){
    var data  = ((podorigin==mode) ?  'Equal' : 'NotEqual');
     }
     else if(podorigin=='PRC'){
    var data  =  ((podorigin==mode) ? 'Equal' : 'NotEqual');
     }
     else if(podorigin=='gl'){
        var data = ((mode != 'PRO') ? 'Equal' : 'NotEqual');
     }
    return data;
    }
</script>

<script>
        function cc_total_amount(){
            var cc_code  = $('#cc_code').val();
         var stncode  = $('#stncode').val();
         var br_code  = $('#br_code').val();
        $.ajax({
                url:"<?php echo __ROOT__ ?>cash",
                method:"POST",
                async: false,
                data:{br_code,stncode,cc_code,action : 'cc_total_amount'},
                dataType:"text",
                success:function(result){
                    var res = JSON.parse(result);
                     console.log(result);
                     $('#cc_total_billed_amt').val(res[0]);

                }
                
         }); 
        }
</script>
<script>

function formatDate(date) {
    var d = new Date(date),   
        day = '' + d.getDate(),
        month = '' + (d.getMonth() + 1),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}
    $(document).ready(function(){
     $('#bk_date').change(function(){

         var current_bill_date = formatDate($(this).val());
         var stncode  = $('#stncode').val();
         var br_code  = $('#br_code').val();
         var col_date;
         $.ajax({
                url:"<?php echo __ROOT__ ?>cash",
                method:"POST",
                async: false,
                data:{br_code,stncode,current_bill_date,action : 'check_denomination'},
                dataType:"text",
                success:function(result){
                    var res = JSON.parse(result);
                    if(res[0]=='1'){
                        swal("Denomination is completed on this Date");
                       
                    }else{
                        $('#submitadd').attr('type','submit');
                    }
                   
                }
                
        });
     });
    });
</script>

<script>
      function getcoldate() { 
      
        var current_bill_date = $('#bk_date').val();
         var stncode  = $('#stncode').val();
         var br_code  = $('#br_code').val();
         var col_date;
         $.ajax({
                url:"<?php echo __ROOT__ ?>cash",
                method:"POST",
                async: false,
                data:{br_code,stncode,current_bill_date,action : 'check_denomination'},
                dataType:"text",
                success:function(result){
                    var res = JSON.parse(result);
                    response12 = res[0];  
                }
                
        });
        return response12;
      }
</script>
<script>
$("#bk_date").keydown(function (e) {
  var key = e.keyCode || e.charCode;
  if (key == 8 || key == 46) {
      e.preventDefault();
      e.stopPropagation();
  }
});




$('#formdestination').on('keypress', function (e) {    
    var addr = $(this).val();
      addr.replace(/["']+/g, '');
        var ingnore_key_codes = [34, 39];
        if ($.inArray(e.which, ingnore_key_codes) >= 0) {
            e.preventDefault();
        }
    });
</script>
<script>
    $(document).ready(function(){
        $('#mode').change(function(){
            getvechicle_onchange();
            function getvechicle_onchange(){
        var destcode = $("#destn").val();
        var tdate = $("#bk_date").val();
        var gstin = $("#gstin").val();
        var wt = $("#wt").val();
        var v_wt = $("#v_wt").val();
        var mode = $("#mode").val();
        var stationcode = '<?=$stationcode;?>';
        $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{mode,wt,v_wt,gstin,tdate,stationcode,destcode,action : 'getvehicle_number'},
                dataType:"text",
                success:function(result){
                  
                  var vehicle = JSON.parse(result);
                  console.log(vehicle);
                  $("#vechicle_no").val(vehicle[0]);
                 
                }
                
        });
       }
});
    });
</script>


<script>
    $(document).ready(function(){
        
        $('#wt,#v_wt,#gstin').keyup(function(){
            getvechicle_onchange();
       function getvechicle_onchange(){
        var destcode = $("#destn").val();
        var tdate = $("#bk_date").val();
        var gstin = $("#gstin").val();
        var wt = $("#wt").val();
        var v_wt = $("#v_wt").val();
        var mode = $("#mode").val();
        var stationcode = '<?=$stationcode;?>';
        $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{mode,wt,v_wt,gstin,tdate,stationcode,destcode,action : 'getvehicle_number'},
                dataType:"text",
                success:function(result){
                  
                  var vehicle = JSON.parse(result);
                  console.log(vehicle);
                  $("#vechicle_no").val(vehicle[0]);
                 
                }
                
        });
       }
});
    });
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
<script>
var url_string = window.location.href; 

var url = new URL(url_string);
var cnotetype = url.searchParams.get("cnotetype");
var bdate = url.searchParams.get("bdate");
var pincode = url.searchParams.get("pincode");
var destination = url.searchParams.get("destination");
var cnoteing = url.searchParams.get("cno");
const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
let current_datetime = new Date(bdate)
let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear()
if(bdate){
    $('#bk_date').val(formatted_date);
}
if(pincode){
    $('#consignee_pin').val(pincode);
}
if(bdate){   
    $("#destn").prop('autofocus', true); 
}
if(cnotetype=='Virtual'){
    $('#cnote_no').attr('readonly',true);
                $('#bk_date').val("<?=date('d-M-Y')?>");
                $('#bk_date').attr('readonly',true);
                $('#consignee_mob').attr('required',true);
}
//console.log(c);
//Write it out
console.log(cnotetype);
</script>
<script>
    
    // $('html').css('overflow-x', 'initial');
         $(function() {
         
            $( "#bk_date" ).datepicker({
        dateFormat: "d-M-yy",
        firstDay: 1,
        maxDate: new Date(),
        showAnim: "slide",
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
  $(document).ready(function(){
        $('#data-table').DataTable();
    });
</script>
  <script>
function deletequeue(cnotenumber,cnoteserial,statuscno,cr_invoiced){
    if(cr_invoiced > 0){
        swal("Cnote is Already Cr Invoiced..");
    }else{
        swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this record file!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
  })
  .then((willDelete) => {

    if (willDelete) {
      var delcash_ecompany = $('#stncode').val();
      var delcash_ecnote = cnotenumber;
      var serial  = cnoteserial;
      var status = statuscno;
      var delcash_euser = '<?=$user?>'
    
      $.ajax({
                url:"<?php echo __ROOT__ ?>cash",
                method:"POST",
                data:{delcash_ecompany,delcash_ecnote,delcash_euser,serial},
                dataType:"text",
                
        });
        if(statuscno=='Invoiced'){
          swal("Already invoiced");
        }else{
      swal("Your Record has been deleted!", {
        icon: "success",
      });
      $('#'+cnotenumber).closest('tr').remove();
    }
      
    } else {
      swal("Your Record is safe!");
    }
  });
    }
    
}

</script>
    <script>
   $(window).on('load', function () {
   var usr_name = $('#user_name').val();
   var br_code = $('#br_code').val();
   var cnote_number = '<?= $cno3 ?>';
   var cno = $('#cnote_no').val();
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
            if($('#stnname').val().trim()!=''){
                cc_autopopulate();
                bacnoteverify();
                getcollectioncentersjson();
            }
            }
    });
  });

</script>
<script>
        function verifyvirtual(element){
            if(element.value=='Virtual'){
                $('#cnote_no').attr('readonly',true);
                $('#bk_date').val("<?=date('d-M-Y')?>");
                $('#bk_date').attr('readonly',true);
                $('#consignee_mob').attr('required',true);
            }
            else{
                $('#cnote_no').attr('readonly',false);
                $('#bk_date').attr('readonly',false);
                $('#consignee_mob').attr('required',false);

            }

            $('#destn').val('');
            $('#wt').val('0');
            $('#v_wt').val('0');
            $('#amount').val('0');
        }
        </script>
<script>
    function getKeyByValue(object, value) {
        return Object.keys(object).find(key => object[key] === value);
    }
</script>
<script>
  $(document).ready(function(){

        $('#br_code').focusout(function(){
            var br_code = $('#br_code').val().toUpperCase().trim();
            if(branches[br_code]!==undefined){
                //$('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
                bacnoteverify();
            }
            else{
                //$('#brcode_validation').css('display','block');
            }
        });
        //fetch code of entering name
        $('#br_name').focusout(function(){
            var br_name = $(this).val().trim();
            var gccode = getKeyByValue(branches,br_name);
            
            if(gccode!==undefined){
                //$('#brcode_validation').css('display','none');
                $('#br_code').val(gccode);
                bacnoteverify();
            }
            else{
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
                getcollectioncentersjson();
                cc_autopopulate();
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
        var mode_cust = 'RATE1';
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
           var table = 'cash';
           var podorigin = $('#podorigin').val();
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
                data:{dest_mode:destcode2,mode_origin:mode_origin,mode_cust:mode_cust,arr:arr,table:table,podorigin:podorigin},
                dataType:"text",
                success:function(modes)
                {
                    $('#mode').html(modes);
                    console.log(modes);
                }
            
            });
           $('#destnull_validation').css('display','none');
    });

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
  
    var customer     = 'RATE1';
    var origin       = $('#origin').val();
    var destination  = $('#destn').val().split('-')[0];
    var mode         = $('#mode').val();
    var pod_amt      = $('#podorigin').val();
    var pcs          = '1';
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
    $('#shp_mob').focusout(function(){
        if($(this).val().length>9){
            $(this).css('border','1px solid green');
            var shpmobile = $(this).val();
            $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{shpmobile},
                dataType:"text",
                success:function(shipperdata)
                {
                    sdata = JSON.parse(shipperdata);
                    console.log(sdata.length);
                    if(sdata[0]['consignee_name'] !=='' || sdata[0]['consignee_addr'] !=='' || sdata[0]['consignee_pincode'] !=='' && sdata[1]['gst'] !==''){
                        if(sdata[0]['consignee_name'] ==''){
                            $('#shp_addr').attr('tabindex',0);
                        }else{
                            $('#shp_name').attr('tabindex',-1);
                        }
                        if(sdata[0]['consignee_addr'] ==''){
                            $('#shp_addr').attr('tabindex',0);
                        }else{
                            $('#shp_addr').attr('tabindex',-1);
                        }
                        if(sdata[0]['consignee_pincode'] ==''){
                            $('#shp_addr').attr('tabindex',0);
                        }else{
                            $('#shp_pin').attr('tabindex',-1);
                        }                
                        $('#shp_name').val(sdata[0]['consignee_name']);
                        $('#shp_addr').val(sdata[0]['consignee_addr']);
                        $('#shp_pin').val(sdata[0]['consignee_pincode']);  
                        if(sdata[1]['gst']=='' || sdata[1]['gst']==null){
                            $('#gstin').val(''); 
                        }else{
                            $('#gstin').val(sdata[1]['gst']); 
                        }
                        
                    }else{
                                       
        
                }
                }
            });
        }
        else{
            $(this).css('border','1px solid red');
            $('#gstin').val(''); 
        }
    });
    $('#consignee_mob').focusout(function(){
        if($(this).val().length>9){
            var shpmobile = $(this).val();
            $.ajax({
                url:"<?php echo __ROOT__ ?>billingcontroller",
                method:"POST",
                data:{shpmobile},
                dataType:"text",
                success:function(consigneedata)
                {
                    cdata = JSON.parse(consigneedata);
                    
                    if(cdata[0]['consignee_name'] !=='' || cdata[0]['consignee_addr'] !=='' || cdata[0]['consignee_pincode'] !==''){
                        if(cdata[0]['consignee_name'] ==''){
                            $('#consignee_addr').attr('tabindex',0);
                        }else{
                            $('#consignee_name').attr('tabindex',-1);
                        }
                        if(cdata[0]['consignee_addr'] ==''){
                            $('#consignee_addr').attr('tabindex',0);
                        }else{
                            $('#consignee_addr').attr('tabindex',-1);
                        }
                        if(cdata[0]['consignee_pincode'] ==''){
                            $('#consignee_addr').attr('tabindex',0);
                        }else{
                            $('#consignee_pin').attr('tabindex',-1);
                        }  
                        $('#consignee_name').val(cdata[0]['consignee_name']);
                       $('#consignee_addr').val(cdata[0]['consignee_addr']);
                       $('#consignee_pin').val(cdata[0]['consignee_pincode']); 
                      
                      
                    }else{

                    }
                }
            });
        }
    });
    </script>
<script>
    $('#wt').focusout(function(){
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
    function verify_bacnoteverify(){
        var ba_stn = $('#stncode').val();
        var ba_branch = $('#br_code').val();
        var cashorigin = $('#podorigin').val();
        var cashcccode = $('#cc_code').val();
        var cnote_type = $('#cnote_type').val();
        var br_code = $('#br_code').val();
        var cnote_number = '<?=$cno3;?>';

        console.log('cnote verify initiated');
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{ba_stn:ba_stn,ba_branch:ba_branch,cashorigin:cashorigin,cashcccode,cashcnote_type:cnote_type},
            dataType:"text",
            success:function(cno)
            {
                cnote = JSON.parse(cno);
                console.log(cnote);
                $('#cnote_no').val(cnote[0]);
                $('#cnser').val(cnote[1]);
                cashcnotevalidation();
                console.log('cnote verify completed',cno);

            }
            });
    }
</script>

<script>    
       function validate_cnote() {
        var station = $('#stncode').val();
        var branch = $('#br_code').val();
        var origin = $('#podorigin').val();
        var cc_code = $('#cc_code').val();
        var cnote_type = $('#cnote_type').val();
        var cno = $('#cnote_no').val();

             $.ajax({
             url:"<?php echo __ROOT__ ?>billingcontroller",
             method:"POST",
             async: false,
             data:{cno,branch,station,origin,cc_code,cnote_type,action : 'validate_cnote'},
             dataType:"text",
             success:function(cc_flag)
             {
                response1 = cc_flag;
             }
             });
             console.log(response1);
             return response1;
          }
</script>


<script>
    function bacnoteverify(){
        var ba_stn = $('#stncode').val();
        var ba_branch = $('#br_code').val();
        var cashorigin = $('#podorigin').val();
        var cashcccode = $('#cc_code').val();
        var cnote_type = $('#cnote_type').val();
        var br_code = $('#br_code').val();
        var cnote_number = '<?=$cno3;?>';

        console.log('cnote verify initiated');
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{ba_stn:ba_stn,ba_branch:ba_branch,cashorigin:cashorigin,cashcccode,cashcnote_type:cnote_type},
            dataType:"text",
            success:function(cno)
            {
                $('#cnote_no').val(cno);
                cnote = JSON.parse(cno);
                console.log(cnote);
                if(cnoteing==null)
                {
                    $('#cnote_no').val(cnote[0]);
                  }else{
                    $('#cnote_no').val('<?=$cno3;?>');
                  }
                $('#cnser').val(cnote[1]);
                cashcnotevalidation();
                console.log('cnote verify completed',cno);

            }
            });
    }
</script>
<script> 
function getvflag(cno){
    var vba_stn = $('#stncode').val();
    var vcashorigin = $('#podorigin').val();
    var vcno = $('#cnote_no').val();
    console.log('v flag initialised'+cno);
    $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{vba_stn,vcashorigin,vcno},
            dataType:"text",
            success:function(v_flag)
            {
                console.log('value :'+v_flag);
                if(v_flag.trim()=='1'){
                    $('#bk_date').val('<?php echo date('Y-m-d'); ?>');
                    $('#bk_date').attr('readonly',true);
                }
                else{
                    $('#bk_date').attr('readonly',false);
                }
            }
            });
}
</script>

<script>
        
       function getcc() {
        var branch = $('#br_code').val();
        var stationcode = '<?=$stationcode;?>';
    $.ajax({
        url:"<?php echo __ROOT__ ?>billingcontroller",
        method:"POST",
        async: false,
        data:{branch,stationcode,action : 'getcc_enable'},
        dataType:"text",
        success:function(cc_flag)
        {
           response1 = cc_flag;
           
        }
        });
// ajax asynchronus request 
return response1; // return data from the ajax request
  }
</script>





<script>
    function verify()
    {
       
         if(suming([$('#to_cnote').val().trim()]) < suming([$('#from_cnote').val().trim()])){
            swal("Please Enter Valid Cnote");
         }
        else if($('#payment_mode').val().trim()!='Cash' && $('#payment_reference').val().trim()==''){
            swal("Please Make payment reference");
        }
        else if(updateflag){
            swal("Cnote Already Billed !! Don't Make Cash Entry.. Use only Update Button");
        }
        else if($('#wt').val().trim()=='')
        {
            $('#wt_validation').css('display','block');
        }

        else if($("#cnote_no_invalid").css("display")==="block")
        {
            swal("Cnote Already Billed.. Please Enter valid cnote");
        }
        
        else if(getcc().trim()=="Y" && $('#cc_code').val().trim()=='')
        {
            swal("Please Enter CollectionCenter");
        }
        else if(validate_cnote().trim()=='PreviousAllotted' && $('#cnote_no').is('[readonly]') && $('#cnote_type').val().trim()=='Virtual'){
            swal({
            text: "Previous Cnote Number Is Allotted",
            icon: "info",
             buttons: true,
            }).then((okey)=>{
           
                window.location.reload();
                
               
            });
        }
        else if($("#cccode_validation").css("display")==="block")
        {
            swal("Please Enter valid CollectionCenter");
        }
        else if($("#brcode_validation").css("display")==="block")
        {
            swal("Invalid branch.. Please Enter valid branch code");
        }
        else if($("#gst_val").css("display")==="block")
        {
            swal("Invalid GST.. Please Enter valid GST");
        }
		else if($('#dest_validation').css('display')==='block')
        {
            swal('Enter Valid Destination');
        }
        else if(getcoldate()==1){
            swal('Denomination is completed on this date');
        }
        else if($('#amount').val().trim()=='' || parseInt($('#amount').val().trim())<=0)
        {
            alert('Amount Not Valid');
        }
        else if(checkpod()=='NotEqual'){
            swal("Please select correct pod origin");
        }
        else if($('#mode').val().trim()==''){
            swal('Please select mode');
        }
        else if($('#consignee_mob').val().trim()=='' && $('#cnote_type').val().trim()=='Virtual'){
            swal("Please Enter Consignee Number");
       }
       else if($('#error-keyup-1').css('color')==='rgb(255, 0, 0)' || $('#error-keyup-2').css('color')==='rgb(255, 0, 0)'){
            swal("Please Enter Valid Address");
        }
        else if($('#error-keyup-n2').css('color')==='rgb(255, 0, 0)' || $('#error-keyup-n21').css('color')==='rgb(255, 0, 0)'){
        swal("Please Enter Valid Name");
        } 
        else{
            window.open('<?php echo __ROOT__ ?>bukentrychallan?start_cnote='+$('#from_cnote').val().trim()+'&end_cnote='+$('#to_cnote').val().trim()+'&stn_code='+$('#stncode').val().trim()+'&br_code='+$('#br_code').val().trim()+'&dest_code='+$('#destn').val().split("-")[0]
            +'&shp_mob='+$('#shp_mob').val().trim()+'&shp_name='+$('#shp_name').val().trim()+'&shp_addr='+$('#shp_addr').val().trim()+'&shp_pin='+$('#shp_pin').val().trim()+'&gstin='+$('#gstin').val().trim()
            +'&consignee_mob='+$('#consignee_mob').val().trim()+'&consignee_name='+$('#consignee_name').val().trim()+'&consignee_addr='+$('#consignee_addr').val().trim()+'&consignee_pin='+$('#consignee_pin').val().trim()
            +'&pcs='+$('#pcs').val().trim()+'&tdate='+$('#bk_date').val().trim()
            +'&wt='+$('#wt').val().trim()+'&v_wt='+$('#v_wt').val().trim()+'&mode='+$('#mode').val().trim()+'&amount='+$('#amount').val().trim()
            +'&contents='+$('#contents').val().trim()+'&dc_value='+$('#dc_value').val().trim()+'&mode='+$('#mode').val().trim()+'&amount='+$('#amount').val().trim(), '_blank');

            // if(parseFloat($('#wt').val())>0 || parseFloat($('#v_wt').val())>0)
            // {
            // $('#submitaddbilling').html("<i class='fa fa-spinner'></i>&nbsp; Save & Add");
            // $('#submitaddbilling').attr('type','submit');
            // $('#submitaddbilling').attr('name','addanotherbillingcash');
            
            // }
            // else{
            //     $('#wt_validation').css('display','block');
            // }
        }
    }






    // function add()
    // {
    //     if($('#cnote_no').val().trim()=='')
    //     {
    //         $('#cnote_no_validation').css('display','block');
    //     }
    //     else if($('#payment_mode').val().trim()!='Cash' && $('#payment_reference').val().trim()==''){
    //         swal("Please Make payment reference");
    //     }
    //     else if(updateflag){
    //         swal("Cnote Already Billed !! Don't Make Cash Entry.. Use only Update Button");
    //     }
    //     else if($('#wt').val().trim()=='')
    //     {
    //         $('#wt_validation').css('display','block');
    //     }
    //     else if($("#brcode_validation").css("display")==="block")
    //     {
    //         swal("Invalid branch.. Please Enter valid branch code");
    //     }
    //     else if($('#pcs').val().trim()=='')
    //     {
    //         $('#pcs_validation').css('display','block');
    //     }
    //     else if($("#gst_val").css("display")==="block")
    //     {
    //         swal("Invalid GST.. Please Enter valid GST");
    //     }
    //     else if(getcc().trim()=="Y" && $('#cc_code').val().trim()=='')
    //     {
    //         swal("Please Enter CollectionCenter");
    //     }
    //     else if(validate_cnote().trim()=='PreviousAllotted' && $('#cnote_no').is('[readonly]') && $('#cnote_type').val().trim()=='Virtual'){
    //         swal({
    //         text: "Previous Cnote Number Is Allotted",
    //         icon: "info",
    //          buttons: true,
    //         }).then((okey)=>{
    //             window.location.reload();
    //         });  
    //     }
    //     else if($("#cccode_validation").css("display")==="block")
    //     {
    //         swal("Please Enter valid CollectionCenter");
    //     }
    //     else if($("#cnote_no_invalid").css("display")==="block")
    //     {
    //         swal("Cnote Already Billed.. Please Enter valid cnote");
    //     }
	// 	else if($('#dest_validation').css('display')==='block')
    //     {
    //         swal('Enter Valid Destination');
    //     }
    //     else if(getcoldate()==1){
    //         swal('Denomination is completed on this date');
    //     }
    //     else if($('#amount').val().trim()=='' || parseInt($('#amount').val().trim())<=0)
    //     {
    //         alert('Amount Not Valid');
    //     }
    //     else if(checkpod()=='NotEqual'){
    //         swal("Please select correct POD origin");
    //     }
    //     else if($('#mode').val().trim()==''){
    //         swal('Please select mode');
    //     }
    //     else if($('#consignee_mob').val().trim()=='' && $('#cnote_type').val().trim()=='Virtual'){
    //         swal("Please Enter Consignee Number");
    //    }
    //    else if($('#error-keyup-1').css('color')==='rgb(255, 0, 0)' || $('#error-keyup-2').css('color')==='rgb(255, 0, 0)'){
    //         swal("Please Enter Valid Address");
    //     }
    //     else if($('#error-keyup-n2').css('color')==='rgb(255, 0, 0)' || $('#error-keyup-n21').css('color')==='rgb(255, 0, 0)'){
    //     swal("Please Enter Valid Name");
    //     } 
    //     else{
    //         if(parseFloat($('#wt').val())>0 || parseFloat($('#v_wt').val())>0)
    //         {
    //         $('#submitadd').html("<i class='fa fa-spinner'></i>&nbsp; Save");
    //         $('#submitadd').attr('type','submit');
    //         $('#submitadd').attr('name','addbillingcash');
            
    //         }else{
    //                 $('#wt_validation').css('display','block');
    //             }
    //     }
    // }
    // function update()
    // {
    //     if(parseInt(dencount)>0){
    //         swal("Cannot edit this Cnote as it is added in Denomination");
    //     }
    //     else if($('#cnote_no').val().trim()=='')
    //     {
    //         $('#cnote_no_validation').css('display','block');
    //     }
    //     else if(!updateflag){
    //         swal("Cnote is in Allotted Status !! Don't Update.. Use Save Button"); 
    //     }
    //     else if($('#get_cr_inv').val().trim().length > 0)
    //     {
    //         swal("This Cnote have already Cash Receipt"); 
    //     }
    //     else if($('#wt').val().trim()=='')
    //     {
    //         $('#wt_validation').css('display','block');
    //     }
    //     else if($("#brcode_validation").css("display")==="block")
    //     {
    //         swal("Invalid branch.. Please Enter valid branch code");
    //     }
    //     else if($('#pcs').val().trim()=='')
    //     {
    //         $('#pcs_validation').css('display','block');
    //     }
    //     else if($("#gst_val").css("display")==="block")
    //     {
    //         swal("Invalid GST.. Please Enter valid GST");
    //     }
    //     else if($("#cnote_no_invalid").css("display")==="block")
    //     {
    //         swal("Cnote Already Billed.. Please Enter valid cnote");
    //     }
	// 	else if($('#dest_validation').css('display')==='block')
    //     {
    //         swal('Enter Valid Destination');
    //     }
    //     else if($('#amount').val().trim()=='' || parseInt($('#amount').val().trim())<=0)
    //     {
    //         alert('Amount Not Valid');
    //     }
    //     else if(checkpod()=='NotEqual'){
    //         swal("Please select correct POD origin");
    //     }
    //     else if($('#mode').val().trim()==''){
    //         swal('Please select a MODE');
    //     }
    //     else if($("#cccode_validation").css("display")==="block")
    //     {
    //         swal("Please Enter valid CollectionCenter");
    //     }
    //     else if($('#consignee_mob').val().trim()=='' && $('#cnote_type').val().trim()=='Virtual'){
    //         swal("Please Enter Consignee Number");
    //    }
    //    else if($('#error-keyup-1').css('color')==='rgb(255, 0, 0)' || $('#error-keyup-2').css('color')==='rgb(255, 0, 0)'){
    //         swal("Please Enter Valid Address");
    //     }
    //     else if($('#error-keyup-n2').css('color')==='rgb(255, 0, 0)' || $('#error-keyup-n21').css('color')==='rgb(255, 0, 0)'){
    //     swal("Please Enter Valid Name");
    //     } 
    //     else{
    //         if(parseFloat($('#wt').val())>0 || parseFloat($('#v_wt').val())>0)
    //         {
    //             // alert('update done');
    //         $('#updatecredit').html("<i class='fa fa-spinner'></i>&nbsp; Save");
    //         $('#updatecredit').attr('type','submit');
    //         $('#updatecredit').attr('name','updatecredit');
            
    //         }else{
    //                 $('#wt_validation').css('display','block');
    //             }
    //     }
    // }

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


  var bookcode =[<?php   
    $bc=$db->query("SELECT book_code FROM bcounter WHERE stncode='$stationcode'");
    while($rowbc=$bc->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$rowbc['book_code']."', ";
    }
 ?>];

  var bookname =[<?php   
    $bcn=$db->query("SELECT book_name FROM bcounter WHERE stncode='$stationcode'");
    while($rowbcn=$bcn->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$rowbcn['book_name']."', ";
    }
 ?>];
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
//branches array
var bookingcounters = {
    <?php
    $bookingcodes = $db->query("SELECT book_code,book_name FROM bcounter WHERE stncode='$stationcode'");
    while($rowbookingcodes=$bookingcodes->fetch(PDO::FETCH_OBJ))
    {
    echo $rowbookingcodes->book_code.":'".$rowbookingcodes->book_name."',";
    }
    ?>
}
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
$('#destn').autocomplete({
    source:[destination]
});
autocomplete(document.getElementById("book_counter"), bookcode);
autocomplete(document.getElementById("book_counter_name"), bookname);
</script>
<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cust_name').value = "";
    document.getElementById('cust_addr').value = "";
    document.getElementById('cust_mobile').value = "";
    document.getElementById('book_counter').value = "";
    document.getElementById('book_counter_name').value = "";
    document.getElementById('bk_date').value = "";
    document.getElementById('bkgstaff').value = "";
    document.getElementById('cnote_no').value = "";
    document.getElementById('mf_no').value = "";
    document.getElementById('cnote_no1').value = "";
    document.getElementById('destn').value = "";
    document.getElementById('pincode_check').value = "";
    document.getElementById('pin_validation').value = "";
    document.getElementById('dest_autocorr').value = "";
    document.getElementById('wt').value = "";
    document.getElementById('pcs').value = "";
    document.getElementById('mode').value = "";
    document.getElementById('amount').value = "";
}
</script>
<script>
    $(document).ready(function(){
          //fetch name of entering name
          $('#book_counter').focusout(function(){
            var bk_code = $(this).val();
            if(bookingcounters[bk_code]!==undefined){
                $('#brcode_validation').css('display','none');
                $('#book_counter_name').val(bookingcounters[bk_code]);
            }
            else{
               // $('#brcode_validation').css('display','block');
            }
        });
          //fetch code of entering name
          $('#book_counter_name').focusout(function(){
            var bk_name = $(this).val();
            var gbccode = getKeyByValue(bookingcounters,bk_name);
            
            if(gbccode!==undefined){
                $('#brcode_validation').css('display','none');
                $('#book_counter').val(gbccode);
            }
            else{
               // $('#brcode_validation').css('display','block');
            }
        });
    });
</script>
<script>
    function cashcnotevalidation(){
        var cash_cno = $('#cnote_no').val();
        var cno_cash_stn = $('#stncode').val();
        var cno_cash_br = $('#br_code').val();
        var cno_cash_cc = $('#cc_code').val();
        var cashorigin = $('#podorigin').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{cno_cash_br:cno_cash_br,cno_cash_stn:cno_cash_stn,cash_cno:cash_cno,cno_cash_cc,cashorigin},
            dataType:"text",
            success:function(ccount)
            {
                if(ccount==0){
                    $('#cnote_no_invalid').css('display','block');
                }else{
                    $('#cnote_no_invalid').css('display','none');
                }
            }
            });
    }
</script>



<script> 
var updateflag=false;
var dencount=0;
function getcashcnotedetail(){
   var cashcno = $('#cnote_no').val();
   var cashpodorigin = $('#podorigin').val();
   var cash_stn = '<?=$stationcode?>';

   console.log('getting cnote data '+ cashcno + cashpodorigin + cash_stn +'<?php echo __ROOT__ ?>billingcontroller');

   $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{cashpodorigin,cashcno,cash_stn},
            dataType:"json",
            success:function(data)
            {
                if(data){
                    if(data['status']=='Invoiced'){
                    swal("This Cnote is Invoiced");
                 }else{
                    updateflag=true;
                    dencount = data['den_counts'];
                    console.log(data); 
                    if(data['cnote_type']=="Virtual"){
                         $('#cnote_no').attr('readonly',true);
                         $('#bk_date').attr('readonly',true);
                     }
                    $('#cnote_no_invalid').css('display','none');
                    $('#br_code').val(data['br_code']);
                    $('#cc_code').val(data['cc_code']);
                    $('#cc_name').val(data['cc_name']);
                    if(branches[data['br_code']]!==undefined){
                        $('#br_name').val(branches[data['br_code']]);
                    }
                    $('#book_counter').val(data['book_counter']);
                    if(bookingcounters[data['book_counter']]!==undefined){
                        $('#brcode_validation').css('display','none');
                        $('#book_counter_name').val(bookingcounters[data['book_counter']]);
                    }
                    const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                  var newdate = data['tdate'];
                   let current_datetime = new Date(newdate)
                    let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear()

                    $('#cnote_type').val(data['cnote_type']);
                    $('#mf_no').val(data['mf_no']);
                    $('#bkgstaff').val(data['bkgstaff']);
                    $('#gstin').val(data['gst']);
                    $('#bk_date').val(formatted_date);
                    $('#contents').val(data['contents']);
                    $('#dc_value').val(data['dc_value']);
                    $('#vechicle_no').val(data['Vehicle_no']);
                    $('#get_cr_inv').val(data['cash_receipt_no']);

                    $('#destn').val(data['dest_code']);
                    $('#wt').val(parseFloat(data['wt']).toFixed(3));
                    $('#v_wt').val(parseFloat(data['v_wt']).toFixed(3));
                    $('#mode').html("<option value='"+data['mode_code']+"'>"+data['mode_code']+"</option>");
                    $('#amount').val(parseFloat(data['ramt']).toFixed(2));

                    //setting consignee
                    $('#consignee_mob').val(data['consignee_mobile']);
                    $('#consignee_name').val(data['consignee']);
                    $('#consignee_addr').val(data['consignee_addr']);
                    $('#consignee_pin').val(data['consignee_pin']);
                    $('#cnser').val(data['cnote_serial']);


                    //setting shipper
                    $('#shp_mob').val(data['shp_mob']);
                    $('#shp_name').val(data['shp_name']);
                    $('#shp_pin').val(data['shp_pin']);
                    $('#shp_addr').val(data['shp_addr']);

                    //Setting payment_mode
                    $("#payment_mode:selected").removeAttr("selected");
                    $("option[value="+data['payment_mode']).attr('selected',true);
                    $('#payment_reference').val(data['payment_reference']);

                    cashcnotevalidation();
                }
                } else {
                    updateflag=false;
                    console.log('No data available'); 
                }
            }
            });
}
</script>
<script> 
collectioncenters = {}

function getcollectioncentersjson(){
   //get collectioncenters json
   var branch8 = $('#br_code').val().toUpperCase();
   var comp8 = $('#stncode').val().toUpperCase();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{getjsoncc_br:branch8,getjsoncc_stn:comp8},
       dataType:"text",
       success:function(ccomerjson)
       {
          console.log(ccomerjson);
          collectioncenters = JSON.parse(ccomerjson);
       }
       });
    }
</script>
<script>
function cc_autopopulate(){
   var branch8 = $('#br_code').val();
   var comp8 = $('#stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{branchcc:branch8,compcc:comp8},
       dataType:"text",
       success:function(cc8)
       {
           var cc8json = JSON.parse(cc8);
           //console.log(cc8);
           autocomplete(document.getElementById("cc_code"), cc8json); 
       }
   });
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"text",
       data:{c_name_brcc:branch8,c_name_compcc:comp8},
       dataType:"text",
       success:function(ccname)
       {
            var ccnamejson = JSON.parse(ccname);
           autocomplete(document.getElementById("cc_name"), ccnamejson); 
       }
   });
   getcollectioncentersjson();
}
</script>
<script> 

$(document).ready(function(){
     
     $('#cc_code').focusout(function(){
        if($(this).val().trim()!=''){
         var cc_code = $('#cc_code').val().toUpperCase().trim();
        if(collectioncenters[cc_code]!==undefined){
            $('#cc_name').val(collectioncenters[cc_code]);
            $('#cccode_validation').css('display','none');
            verify_bacnoteverify();
        }
        else{
            $('#cccode_validation').css('display','block');
        }
        }
        else{
            $('#cccode_validation').css('display','none');
        }
     });
     //fetch code of entering name
     $('#cc_name').focusout(function(){
        if($(this).val().trim()!=''){
         var cc_name = $(this).val();
         var ccode = getKeyByValue(collectioncenters,cc_name);
         if(ccode!==undefined){
            $('#cc_code').val(ccode);
            $('#cccode_validation').css('display','none');
            bacnoteverify();
            }
            else{
                $('#cccode_validation').css('display','block');
            }
        }
        else{
            $('#cccode_validation').css('display','none');
        }
     });
});

</script>
<script>
    $(window).on('load', function() {
        if('<?=$_GET['cno']?>'.trim().length>0 && '<?=$cnote_type?>'=='Virtual'){
            window.open('cashchallan?cno=<?=$_GET['cno']?>'); 
        }
        if('<?=(!empty($sessionbr_code))?>'){
            bacnoteverify();
        }
    })   

     if(window.location.host!='localhost')
     window.history.pushState('Credit', 'Title', '/BulkBilling');

    </script>

    <script>
      function pincodeview(destpincode){
        var conpin = document.getElementById('consignee_pin');
        conpin.value = destpincode.value;
      }

    </script>