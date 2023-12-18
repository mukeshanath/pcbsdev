<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

//if user is not logged in, they cannot access this page
	
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $module = 'company';
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
?>
<div class='alert alert-danger alert-dismissible' id="restriction" style="display:none">
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  You do not have permission to Acces this Page.
                                </div>
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;" id="companysection">  


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
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'companylist'; ?>">Company List</a>
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
                                  Company Added successfully.
                                </div>";
                  }

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 1 )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Company Already Exist.
                                </div>";
                  }

                   ?>

             </div>

<form method="POST" action="<?php echo __ROOT__ ?>company" accept-charset="UTF-8" class="form-horizontal" id="formcompany" enctype="multipart/form-data">
<input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Company Form</p>
          
    <div class="align-right" style="margin-top:-35px;">
       <button type="submit" class="btn btn-success btnattr_cmn" name="addancompany">
       <i class="fa fa-save"></i>&nbsp; Save & Add Another
       </button>

       <button type="submit" class="btn btn-primary btnattr_cmn" name="addcompany">
       <i class="fa  fa-save"></i>&nbsp; Save
       </button>
                                                           
     
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>     

       <button type="button" class="btn btn-danger btnattr_cmn" >
        <a style="color:white" href="<?php echo __ROOT__ . 'companylist'; ?>"><i class="fa fa-times" ></i></a>
       </button>
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
            <a id="bank_a" data-toggle="disabled" href="#banking">Banking</a>
        </li>
        <li id="web_li">
            <a id="web_a" data-toggle="disabled" href="#web">Web</a>
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
                     <input  class="form-control border-form" onfocusout="stncode_val(this)" name="stncode" type="text" id="stncode" autocomplete="off" readonly>
                      
                    <p id="stncode_val" class="form_valid_strings">Please enter only strings.</p>
                    <p id="stncode_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>
              
            <td>Company Name</td>
            <td>
                <div class="col-md-9">
                    <input class="form-control border-form " onfocusout="stnname_val(this)" name="stnname" type="text" id="stnname"  required>
                     <p id="stnname_val" class="form_valid_strings">Special Characters not allowed. </p> 
                     <p id="stnname_empty" class="form_valid_strings">Field Cannot be Empty.</p>             
                </div>
            </td>
       </tr>
      <tr>			
			  <td>Address</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="stn_addr" type="text" id="stn_addr"  required>
                    <p id="addr_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
             </td>
             <td>City</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="stn_city" type="text" id="stn_city">
                </div>
             </td>
                </tr>

    <tr>
    <tr>			
			  <td>State</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" name="stn_state"  type="text" id="stn_state">
                    <p id="addr_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
             </td>
             <td>Country</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" name="stn_country"  type="text" id="stn_country">
                    <p id="addr_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
             </td>
                </tr>

    <tr>
        <td>Mobile</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" onfocusout="mob_val(this)" maxlength="10" name="stn_mobile" type="number" id="stn_mobile" required>
                    <p id="mob_val" class="form_valid_strings">Invalid Mobile Number.</p>
                    <p id="mob_empty" class="form_valid_strings">Field Cannot be Empty.</p>
              </div>
        </td>
        <td>Phone</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" onfocusout="phno_val(this)" name="stn_phone" type="number" id="stn_phone">
                    <p id="phno_val" class="form_valid_strings">Invalid Phone Number.</p>
                    <p id="phno_empty" class="form_valid_strings">Field Cannot be Empty.</p>
              </div>
        </td>
    </tr>
    <tr>	
		  	<td>Fax</td>
		  	<td>
            <div class="col-sm-9">
                 <input  class="form-control border-form" onfocusout="fax_val(this)" name="stn_fax" type="number" id="stn_fax"  >
                <p id="fax_val" class="form_valid_strings">Enter Only Numbers.</p>          
            </div>  
        </td>
			
		  	<td>Email</td>
		  	<td>
             <div class="col-sm-9">
                  <input  class="form-control border-form" onfocusout="email_val(this)" name="stn_email" type="text" id="stn_email" placeholder="" required >
                  <p id="email_val" class="form_valid_strings">Enter valid Email.</p>   
                  <p id="email_empty" class="form_valid_strings">Field Cannot be Empty.</p>                   
             </div>
		  	</td>	
			
    </tr>
    <tr>	
		  	<td>Active</td>
		  	<td>
            <div class="col-sm-9">
                <select class="form-control" name="stn_active" id="stn_active">
                      <option value="Y" selected="selected">Yes</option>
                      <option value="N">No</option>
                    </select> 
                </div>            
         </td>
         
         <td>Region</td>
         <td>
              <div class="col-sm-9">
            <select class="form-control" name="stn_region" id="stn_region">
                    <option value="0">Select Region</option>
                    <?php echo loadregion($db); ?>
                  </select>  
                </div>	
           </td>
			
    </tr>

        <tr>	
          <td>Hub</td>
          <td>
              <div class="col-sm-9">
                  <input  class="form-control border-form" onfocusout="hub_val(this)" name="transit_hub" type="text" id="transit_hub" placeholder="" >
                  <p id="hub_val" class="form_valid_strings">Enter Only Strings.</p>                                   
              </div>	
            </td>
			<td>Station Auto</td>
          <td>
              <div class="col-sm-9">
                  <select class="form-control" name="stnauto" id="stnauto">
                    <option value="Y" selected="selected">Yes</option>
                    <option value="N">No</option>
                  </select>
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
            <td>Tax</td>
          <td>
              <div class="col-sm-9">
                  <input type="text" value="18" class="form-control" name="stn_taxval" readonly>         
              </div>
          </td>
            
          <td>GSTIN</td>
          <td>
              <div class="col-sm-9">
                <input class="form-control border-form" onfocusout="gst_val(this)"  name="stn_gstin" type="text" id="stn_gstin" required>	
                <p id="gst_val" class="form_valid_strings">Enter valid GST Number.</p>                                   
                </div>
          </td>
                    </tr><tr>
          <td>PAN</td>
          <td>
              <div class="col-sm-9">
                  <input class="form-control border-form "  name="stn_pan" type="text" id="stn_pan"  >
              </div> 
          </td>	

           <td>TIN </td>
          <td>
              <div class="col-sm-9">
                  <input class="form-control border-form "  name="stn_tin" type="text" id="stn_tin"  >
              </div> 
          </td>	
        
         
            </tr>
            
        </tbody>
        </table>
        <div class="align-right">
        <button type="button" class="btn btn-primary " data-toggle="tab" onclick="bankng()" id="btn_bank">
            Next &#8594;
        </button>
            </div>
</div>

            <!-- Third Tab     -->
<div id="banking" class="tab-pane" style="margin-left: 25px;">
   <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	   <tbody>
    <tr>		
          <td>Bank Account Name</td>
          <td>
              <div class="col-sm-9">
                  <input class="form-control border-form " onfocusout="bankaccname_val(this)" name="baccname" type="text" id="baccname" >                
                  <p id="bankaccname_val" class="form_valid_strings">Enter Valid Name.</p>                                   
              </div>
          </td>
            
          <td>Bank Account No</td>
          <td>
              <div class="col-sm-9">
                   <input class="form-control border-form " onfocusout="bankaccnum_val(this)" name="baccno" type="text" id="baccno" >
                    <p id="bankaccnum_val" class="form_valid_strings">Invalid Account Number.</p>                                   
              </div>
          </td>
                    </tr><tr>
          <td>Bank Name</td>
          <td >
              <div class="col-sm-9">
                  <input class="form-control border-form "  name="bankname" type="text" id="bankname"  >
              </div> 
          </td>	
          
        
          <td>Bank Branch</td>
          <td>
              <div class="col-sm-9">
                   <input class="form-control border-form "  name="bbranch" type="text" id="bbranch" >
              </div>
          </td>			
		</tr>
    <tr>		
          <td>Account Type</td>
          <td >
              <div class="col-sm-9">
                <select class="form-control" name="bacctype" id="bacctype">
                      <option value="0" selected="selected">Select Account</option>
                      <option value="CA">Current Account</option>
                      <option value="SA">Saving Account</option>
                    </select>            
                    </div>
          </td>
          <td>IFSC</td>
          <td>
              <div class="col-sm-9">
                   <input  class="form-control border-form" name="ifsc" type="text" id="ifsc"  >
              </div>
          </td>	
    </tr>
    <tr>		
          <td>MICR</td>
          <td>
              <div class="col-sm-9">
                     <input  class="form-control border-form" name="micr" type="text" id="micr"  >
              </div>
          </td>
          <td>SWIFT</td>
          <td>
              <div class="col-sm-9">
                     <input class="form-control border-form" name="swift" type="text" id="swift"  >
              </div>
          </td>
    </tr>
    <tr>		
          <td>Account Id</td>
          <td>
              <div class="col-sm-9">
                     <input  class="form-control border-form" name="account_id" type="text" id="account_id"  >
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

              <!-- Third Tab -->
    <div id="web" class="tab-pane" style="margin-left: 25px;">
    <table  id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
     <tbody>
     <tr>		
          <td>Web Login</td>
          <td>
              <input class="form-control border-form "  name="weblogin" type="text" id="weblogin" >
          </td>
				
          <td>Web Password</td>
          <td>
              <input class="form-control border-form "  name="webpassword" type="text" id="webpassword" >
          </td>
      </tr>
      <tr>
		    	<td>WebID</td>
		    	<td >
             <div class="col-sm-9">
                   <input class="form-control border-form "  name="webid" type="text" id="webid" >
             </div> 
		    	</td>	
	
		    	<td>Remarks</td>
	    		<td>
             <div class="col-sm-9">
             <input type="text" class="form-control border-form"  rows="1" name="stn_remarks" cols="50" id="stn_remarks">
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

function regulatory(){
    
    if($("#stncode").val()==""){
        $('#stncode_empty').css('display','block');
    }
    if($("#stnname").val()==""){
        $('#stnname_empty').css('display','block');
    }
    if($("#stn_mobile").val()==""){
        $('#mob_empty').css('display','block');
    }
    if($("#stn_phone").val()==""){
        $('#phno_empty').css('display','block');
    }
    if($("#stn_email").val()==""){
        $('#email_empty').css('display','block');
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
        if($("#mob_val").css("display")==="block")
        {
            return false;
        }
        if($("#phno_val").css("display")==="block")
        {
            return false;
        }
        if($("#fax_val").css("display")==="block")
        {
            return false;
        }
        if($("#email_val").css("display")==="block")
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
function bankng(){
    $("#bank_li").attr("class","active");
    $("#reg_li").attr("class","");
    $("#bank_a").attr('data-toggle','tab');
    $("#btn_bank").attr('href','#banking');
}
function webb(){
    $("#web_li").attr("class","active");
    $("#bank_li").attr("class","");
    $("#web_a").attr('data-toggle','tab');
    $("#btn_web").attr('href','#web');
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

            // if($('#stncode').val() == '')
            // {
            //     $('#companysection').css('display','block');
            //     var superadmin_stn = $('#stncode1').val();
            //     //var superadmin_stn = 'DAA';
            //     $.ajax({
            //     method:"POST",
            //     data:{superadmin_stn:superadmin_stn},
            //     dataType:"text",
            //     success:function(value)
            //     {
            //         var data = value.split(",");
            //         $('#stncode').val(data[0]);
            //         $('#stnname').val(data[1]);
            //     }
            //     });
            // }
            // else{
            //     $('#companysection').css('display','none');
            //     $('#restriction').css('display','block');
            // }
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
autocomplete(document.getElementById("stncode"), stationcode);
</script>
<script>
$('#stncode').focusout(function(){
    var stncode = $('#stncode').val();
    $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{stncode:stncode},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#stn_city').val(data[0]);
            $('#stn_state').val(data[1]);
            $('#stn_country').val(data[2]);
            }
    });
});
</script>
<script>
function resetFunction() {
    document.getElementById('stn_addr').value = "";
    document.getElementById('stn_city').value = "";
    document.getElementById('stn_state').value = "";
    document.getElementById('stn_country').value = "";
    document.getElementById('stn_mobile').value = "";
    document.getElementById('stn_phone').value = "";
    document.getElementById('stn_fax').value = "";
    document.getElementById('stn_email').value = "";
    document.getElementById('stn_active').value = "";
    document.getElementById('stn_region').value = "";
    document.getElementById('transit_hub').value = "";
    document.getElementById('stnauto').value = "";
    document.getElementById('stn_gstin').value = "";
    document.getElementById('stn_pan').value = "";
    document.getElementById('stn_tin').value = "";
    document.getElementById('baccname').value = "";
    document.getElementById('baccno').value = "";
    document.getElementById('bankname').value = "";
    document.getElementById('bbranch').value = "";
    document.getElementById('bacctype').value = "";
    document.getElementById('ifsc').value = "";
    document.getElementById('micr').value = "";
    document.getElementById('swift').value = "";
    document.getElementById('weblogin').value = "";
    document.getElementById('webpassword').value = "";
    document.getElementById('webid').value = "";
    document.getElementById('stn_remarks').value = "";
}
</script>