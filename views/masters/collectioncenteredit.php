<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

//if user is not logged in, they cannot access this page
	
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'collectioncenteredit';
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

function loadaddresstype($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM address ORDER BY aid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->addr_type.'">'.$row->addr_type.'</option>';
         }
         return $output;    
        
    }

    function loadtaxslab($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM tax ORDER BY taxid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->taxval.'">'.$row->taxval.'</option>';
         }
         return $output;    
        
    }

    function loadregion($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM region ORDER BY regid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->regioncode.'">'.$row->regioncode.'</option>';
         }
         return $output;    
        
    }

    $user = $_SESSION['user_name'];

    function countercode($db,$user)
    {
        $stncode = $db->query("SELECT stncodes,last_compcode FROM users WHERE user_name='$user'");
        $station = $stncode->fetch(PDO::FETCH_ASSOC);
        $station_codes = $station['last_compcode'];
        if(!empty($station_codes)){
            $station_code = $station_codes;
        }
        else{
            $userstations = $station['stncodes'];
            $stations = explode(',',$userstations);
            $station_code = $stations[0]; 
        }
        $cc_code=$db->query("SELECT TOP 1 cc_code FROM bcounter WHERE stncode='$station_code' ORDER BY bkcid DESC");
        if($cc_code->rowCount()== 0)
            {
            $ccingcode = $station_code.'BC10001';
            }
        else{
            $cc_coderow=$cc_code->fetch(PDO::FETCH_ASSOC);
            $lastcc_code=$cc_coderow['cc_code'];
            $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastcc_code);
            $arr[0];//PO
            $arr[1]+1;//numbers
            $ccingcode = $arr[0].($arr[1]+1);
            }
         return $ccingcode;    
        
    }

 

if (isset($_GET['ccid'])) {
$bkcid_id = $_GET['ccid'];
$sql = $db->query("SELECT * FROM collectioncenter WHERE bkcid = '$bkcid_id'");
$row = $sql->fetch();
$bkcid              = $row['bkcid'];
$stncode            = $row['stncode'];
$stnname            = $row['stnname'];
$br_code            = $row['br_code'];
$br_name            = $row['br_name'];
$cc_code          = $row['cc_code'];
$cc_name          = $row['cc_name'];
$cc_addr          = $row['cc_addr'];
$cc_city 		     	= $row['cc_city']; 
$cc_state 		   	= $row['cc_state'];
$cc_country 		 	= $row['cc_country'];
$cc_phone         = $row['cc_phone'];
$cc_fax           = $row['cc_fax'];
$cc_email         = $row['cc_email'];
$c_person           = $row['c_person'];
$cc_remarks       = $row['cc_remarks'];
$cc_active        = $row['cc_active'];
$proprietor         = $row['proprietor'];
$cc_gstin         = $row['cc_gstin'];
$cc_tin           = $row['cc_tin'];
$cc_fsc           = $row['cc_fsc'];
$cc_deposit       = $row['cc_deposit'];
$cnote_charges      = $row['cnote_charges'];
$percent_domscomsn  = $row['percent_domscomsn'];
$percent_intlcomsn  = $row['percent_intlcomsn'];
$pro_commission     = $row['pro_commission'];
$spl_discount       = $row['spl_discount'];
$deposit_date       = $row['deposit_date'];
$username  = $row['user_name'];
$bank_accname           = $row['bank_accname'];
$bank_accno           = $row['bank_acc_no'];
$bankname       = $row['bank_name'];
$bankbranch      = $row['bank_branch'];
$accounttype  = $row['account_type'];
$ifsc  = $row['ifsc'];
$micr     = $row['micr'];
$pancardno       = $row['pancardno'];
$account_id       = $row['acc_id'];
$checkque  = $row['checkue'];
}  
?>
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


<div id="col-form1" class="col_form lngPage col-sm-25">
<!-- Bread Crumb -->
 <div class="au-breadcrumb m-t-75" style="margin-bottom:-8px;">
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
                                            <li class="list-inline-item">Masters</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                           
                                            <li class="list-inline-item">Collection Center</li>
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
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  Collection Center Added successfully.
                                </div>";
                  }

                   ?>

             </div>

<form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>collectioncenteredit" accept-charset="UTF-8" class="form-horizontal" id="formcompany" enctype="multipart/form-data">
<input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
<input  class="form-control border-form" name="bkcid" type="text" id="bkcid" value="<?= $bkcid; ?>" style="display:none;">

 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Collection Center Form</p>
          
    <div class="align-right" style="margin-top:-35px;">
      
       <button type="submit" class="btn btn-primary btnattr_cmn" id="addupdate" name="updatecounter">
       <i class="fa  fa-save"></i>&nbsp; Update
       </button>
                                                           
     
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button> 

       <a style="color:white" href="<?php echo __ROOT__ . 'collectioncenterlist'; ?>">          
       <button type="button" class="btn btn-danger btnattr_cmn" >
        <i class="fa fa-times" ></i>
       </button></a>
    </div>
    </div> 
	<div class="content panel-body">
            <!-- Navigation Tabs -->
      <ul class="nav nav-tabs" id="myTab4">
        <li id="gen_li" class="active">
            <a id="gen_a" data-toggle="tab" href="#general" >General</a>
        </li>
        <li id="reg_li">
            <a id="reg_a" data-toggle="disabled" href="#regulatory" >Regulatory</a>
        </li>
		<li id="bank_li">
            <a id="bank_a" data-toggle="tab" href="#banking">Banking</a>
        </li>
        
      </ul>
   <div class="tab_form" >
    <div class="tab-content">
  	    <!-- Tab Form 1 -->
     <div id="general" class="tab-pane active" style="margin-left: 25px;">
     <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	    <tbody>
        <tr>		
            <td>Company Code</td>
            <td >
                <div class="col-sm-9 autocomplete">
                     <input  class="form-control border-form" value="<?= $stncode; ?>" name="stncode" type="text" id="stncode" autocomplete="off" readonly>
                      
                   
                </div>
            </td>
              
            <td>Company Name</td>
            <td>
                <div class="col-md-9">
                    <input class="form-control border-form " value="<?= $stnname; ?>" name="stnname" type="text" id="stnname"  readonly>
                           
                </div>
            </td>
       </tr>
       <tr>		
            <td>Branch Code</td>
            <td >
                <div class="col-sm-9 autocomplete">
                <input placeholder="" onfocusout="br_code_val(this)" value="<?= $br_code; ?>" class="form-control border-form br_code" name="br_code" type="text" id="br_code" autofocus required>
                    <p id="br_code_val" class="form_valid_strings">Please enter only strings.</p>
                    <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                     <p id="br_code_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>
              
            <td>Branch Name</td>
            <td>
                <div class="col-md-9">
                <input class="form-control border-form" value="<?= $br_name; ?>" name="br_name" type="text" id="br_name"  readonly>
                       
                </div>
            </td>
       </tr>
      <tr>	
      <tr>		
            <td>Center Code</td>
            <td >
                <div class="col-sm-9 autocomplete">
                <input class="form-control border-form" value="<?= $cc_code; ?>" name="cc_code"  type="text" id="cc_code" readonly>
                </div>
            </td>
              
            <td>Center Name</td>
            <td>
                <div class="col-md-9">
                <input class="form-control border-form " value="<?= $cc_name; ?>" name="cc_name" type="text" id="cc_name"  >       
                </div>
            </td>
       </tr>
      <tr>				
			  <td>Address</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $cc_addr; ?>"  name="cc_addr" type="text" id="cc_addr"  >
                   
                </div>
             </td>
             <td>City</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $cc_city; ?>" name="cc_city" type="text" id="cc_city">
                </div>
             </td>
                </tr>
    <tr>
    <tr>			
			  <td>State</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $cc_state; ?>" name="cc_state" type="text" id="cc_state">
                   
                </div>
             </td>
             <td>Country</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $cc_country; ?>" name="cc_country" type="text" id="cc_country">
                 
                </div>
             </td>
                </tr>

    <tr>
        <td>Phone</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" value="<?= $cc_phone; ?>" name="cc_phone" type="text" id="cc_phone">
                   
                   
              </div>
        </td>
        <td>Fax</td>
		  	<td>
            <div class="col-sm-9">
                 <input  class="form-control border-form" value="<?= $cc_fax; ?>" name="cc_fax" type="text" id="cc_fax"  >
                    
            </div>  
        </td>
    </tr>
    <tr>	
		  
			
		  	<td>Email</td>
		  	<td>
             <div class="col-sm-9">
                  <input  class="form-control border-form" value="<?= $cc_email; ?>" onfocusout="email_val(this)" name="cc_email" type="text" id="cc_email" placeholder="" >
                  <p id="email_val" class="form_valid_strings">Enter valid Email.</p>   
                               
             </div>
		  	</td>	
              <td>Contact Person</td>
		  	<td>
            <div class="col-sm-9">
            <input  class="form-control border-form" value="<?= $c_person; ?>" name="c_person" type="text" id="c_person" placeholder="" >      
            </div>  
        </td>
    </tr>
    <tr>	
		  
			
        <td>Active</td>
		  	<td>
            <div class="col-sm-9">
                <select class="form-control" name="cc_active" id="cc_active">
                      <option value="Yes" selected="selected">Yes</option>
                      <option value="No">No</option>
                    </select> 
                </div>            
         </td>
         <td>Remarks</td>
		  	<td>
            <div class="col-sm-9">
            <input  class="form-control border-form" rows="1" value="<?= $cc_remarks; ?>" name="cc_remarks" cols="50" id="cc_remarks">     
            </div>  
        </td>

    </tr>
   </tbody>
  </table>
  <div class="align-right">
        <button type="button" class="btn btn-primary " data-toggle="tab" onclick="regulatory()" id="btn_regul">
      Next  &#8594;
      </button>
</div>
 </div>
 <div id="regulatory" class="tab-pane" style="margin-left: 25px;">
    <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
        <tbody>
            <tr>
            <td>Proprietor</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $proprietor; ?>" name="proprietor" type="text" id="proprietor"  >  
              </div>
          </td>
            
          <td>GSTIN</td>
          <td>
              <div class="col-sm-9">
                <input class="form-control border-form" value="<?= $cc_gstin; ?>" onfocusout="gst_val(this)"  name="cc_gstin" type="text" id="cc_gstin" >	
                <p id="gst_val" class="form_valid_strings">Enter valid GST Number.</p>                                   
                </div>
          </td>
                    </tr><tr>
          <td>TIN</td>
          <td>
              <div class="col-sm-9">
              <input class="form-control border-form " value="<?= $cc_tin; ?>" name="cc_tin" type="text" id="cc_tin" >
              </div> 
          </td>	

           <td>FSC</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $cc_fsc; ?>" name="cc_fsc" type="text" id="cc_fsc"  >
              </div> 
          </td>	
        
            </tr>
            <tr>
            <td>Deposit</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $cc_deposit; ?>" name="cc_deposit" type="text" id="cc_deposit"  >
              </div>
          </td>	
          <td>C-Note Charge</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $cnote_charges; ?>" name="cnote_charges" type="text" id="cnote_charges"  >
              </div>
          </td>	
                </tr>

                <tr>
            <td>Commission % D</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $percent_domscomsn; ?>" name="percent_domscomsn" type="text" id="percent_domscomsn"  >
              </div>
          </td>	
          <td>Commission % I</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $percent_intlcomsn; ?>" name="percent_intlcomsn" type="text" id="percent_intlcomsn"  >
      </div>
              </div>
          </td>	
                </tr>

                <tr>
            <td>PRO Commission</td>
          <td>
              <div class="col-sm-9">
              <input class="form-control border-form " value="<?= $pro_commission; ?>" name="pro_commission" type="text" id="pro_commission" >
              </div>
          </td>	
          <td>Special Discount</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $spl_discount; ?>" name="spl_discount" type="text" id="spl_discount"  >
           </div>
              </div>
          </td>	
                </tr>

           <tr>
            <td>Deposit Date</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $deposit_date; ?>" name="deposit_date" type="date" id="deposit_date" >
              </div>
          </td>	
          
          </tr>
        </tbody>
        </table>
		<div class="align-right">
         <button type="button" class="btn btn-primary" data-toggle="tab" id="btn_web" onclick="webb()" style="background-color:#3E01A4;">
      Next  &#8594;
      </button>
        </div>
        </div>      
        <div id="banking" class="tab-pane" style="margin-left: 25px;">
   <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	   <tbody>
    <tr>		
          <td>Bank Account Name</td>
          <td>
              <div class="col-sm-9">
                     <input class="form-control border-form "  name="baccname" type="text" id="baccname" value="<?= $bank_accname; ?>" >
              </div>
          </td>
            
          <td>Bank Account No</td>
          <td>
              <div class="col-sm-9">
                  <input class="form-control border-form "  name="baccno" type="text" id="baccno" value="<?= $bank_accno; ?>" >
              </div>
          </td>
                    </tr><tr>
          <td>Bank Name</td>
          <td>
              <div class="col-sm-9">
                    <input class="form-control border-form "  name="bankname" type="text" id="bankname" value="<?= $bankname; ?>"  >
              </div> 
          </td>	
          
        
          <td>Bank Branch</td>
          <td>
              <div class="col-sm-9">
                    <input class="form-control border-form "  name="bbranch" type="text" id="bbranch" value="<?= $bankbranch; ?>" >
              </div>
          </td>			
		</tr>
    <tr>		
          <td>Account Type</td>
          <td>
              <div class="col-sm-9">
                         <input type="text" class="form-control" name="bacctype" value="<?= $accounttype; ?>" >           
                    </div>
          </td>
          <td>IFSC</td>
          <td>
              <div class="col-sm-9">
                     <input  class="form-control border-form" name="ifsc" type="text" id="ifsc" value="<?= $ifsc; ?>" >
              </div>
          </td>	
    </tr>
    <tr>		
          <td>MICR</td>
          <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="micr" type="text" id="micr"  value="<?= $micr; ?>" >
              </div>
          </td>
          <td>PANCARD NO</td>
          <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="pancard" type="text" id="pancard"  value="<?= $pancardno; ?>" >
              </div>
          </td>
    </tr>
    <tr>		
          <td>Account Id</td>
          <td>
              <div class="col-sm-9">
                     <input  class="form-control border-form" name="account_id" type="text" id="account_id" value="<?= $account_id; ?>">
              </div>
          </td>
          <td>Cheque in fav</td>
          <td>
              <div class="col-sm-9">
                     <input  class="form-control border-form" name="checkue" type="text" id="checkue" value="<?= $checkque; ?>">
              </div>
          </td>
         
    </tr>
   </tbody>
   </table>
     
  </div>
        </div>      
        </form>
	</div>
	</div>
	</div>
</div>
<style>
	#POSCustErrorMsg .error{
	color : #FE2E64;
	}
</style>


						
</section>

<!-- inline scripts related to this page -->


<script>
function webb(){
    $("#web_li").attr("class","active");
    $("#bank_li").attr("class","");
    $("#web_a").attr('data-toggle','tab');
    $("#btn_web").attr('href','#banking');
}
function regulatory(){
    
    if($("#stncode").val()==""){
        $('#stncode_empty').css('display','block');
    }
    if($("#stnname").val()==""){
        $('#stnname_empty').css('display','block');
    }
    
   
   
    else{
      if($("#stnname_val").css("display")==="block")
        {
            return false;
        }
        if($("#stncode_val").css("display")==="block")
        {
            return false;
        }
        if($("#stnname_empty").css("display")==="block")
        {
            return false;
        }
        if($("#stncode_empty").css("display")==="block")
        {
            return false;
        }
       
        
       
        
        else{
            $("#reg_li").attr("class","active");
            $("#gen_li").attr("class","");
            $("#reg_a").attr('data-toggle','tab');
            $("#btn_regul").attr('href','#regulatory');
        }
    }
}


//Suggession of Zone Destination Textfields
    $('#stncode').focusout(function(){
        var keyval = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{keyval:keyval},
            dataType:"text",
            success:function(data)
            {
            $('#stationlist_sugg').html(data);
            }
    });
    });
    $(document).on('click', '#sugg_li', function(){  
           $('#stncode').val($(this).text());  
           $('#stationlist_sugg').empty();  
      }); 

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
            if($('#stncode').val() == '')
            {
                // $('#companysection').css('display','block');
                var superadmin_stn = $('#stncode1').val();
                //var superadmin_stn = 'DAA';
                $.ajax({
                url:"<?php echo __ROOT__ ?>peoplecontroller",
                method:"POST",
                data:{superadmin_stn:superadmin_stn},
                dataType:"text",
                success:function(value)
                {
                    var data = value.split(",");
                    $('#stncode').val(data[0]);
                    $('#stnname').val(data[1]);
                }
                });
            }
            }
    });
  });

  var stationcode =[<?php  
    $sql=$db->query("SELECT * FROM stncode");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['stncode']."', ";
    }
 ?>];
 </script>
 <script>
//Fetch Branch Name
$('.br_code').focusout(function(){
        var br_code5 = $('#br_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{br_code5:br_code5},
            dataType:"text",
            success:function(data)
            {
            $('#br_name').val(data);
            }
    });
    });
</script>
<script>
    //Verifying Branch code is Valid
    $('.br_code').focusout(function(){
        var branch_verify = $('#br_code').val();
        var stationcode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{branch_verify:branch_verify,stationcode:stationcode},
            dataType:"text",
            success:function(branchcount)
            {
		
            if(branchcount== 0)
            {
                $('#brcode_validation').css('display','block');
                
	            	$('#addupdate').attr('type','button');
              
            }
            else{
                $('#brcode_validation').css('display','none');
               
	            	$('#addupdate').attr('type','submit');
                
            }
            }
    });
    });
</script>
 <script>

 //Branch Code Autocomplete
 var branchcode =[<?php  
    $user_name2 = $_SESSION['user_name'];
    $getstncodes = $db->query("SELECT stncodes,group_name FROM users WHERE user_name='$user_name2'");
    $resultstncodes = $getstncodes->fetch(PDO::FETCH_ASSOC);
    if(!empty($resultstncodes['stncodes']))
    {
        $stncodesrow = $resultstncodes['stncodes'];
    }
    else{
        $stncodesrow = $resultstncodes['group_name'];
    }    
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stncodesrow'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];
</script>

<script>
autocomplete(document.getElementById("stncode"), stationcode);
autocomplete(document.getElementById("br_code"), branchcode);
</script>



<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cc_code').value = "";
    document.getElementById('cc_name').value = "";
    document.getElementById('cc_addr').value = "";
    document.getElementById('cc_city').value = "";
    document.getElementById('cc_state').value = "";
    document.getElementById('cc_country').value = "";
    document.getElementById('cc_phone').value = "";
    document.getElementById('cc_fax').value = "";
    document.getElementById('cc_email').value = "";
    document.getElementById('cc_active').value = "";
    document.getElementById('cc_remarks').value = "";
    document.getElementById('proprietor').value = "";
    document.getElementById('cc_gstin').value = "";
    document.getElementById('cc_tin').value = "";
    document.getElementById('cc_fsc').value = "";
    document.getElementById('cc_deposit').value = "";
    document.getElementById('cnote_charges').value = "";
    document.getElementById('percent_domscomsn').value = "";
    document.getElementById('percent_intlcomsn').value = "";
    document.getElementById('pro_commission').value = "";
    document.getElementById('spl_discount').value = "";
    document.getElementById('deposit_date').value = "";
}
</script>