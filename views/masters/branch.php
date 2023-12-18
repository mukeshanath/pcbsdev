<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'branch';
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

   
 
   function loadgroup($db,$stationcode)
           {
               $output='';
            
               $query = $db->query("SELECT * FROM groups  WHERE stncode='$stationcode' ORDER BY gpid ASC");
              
                while ($row = $query->fetch(PDO::FETCH_OBJ)){
                   
               //menu list in home page using this code    
               $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
                }
                return $output;    
               
           }

    function loadbrcat($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM brcategorytb ORDER BY brcid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->brcate_code.'">'.$row->brcate_code.'</option>';
         }
         return $output;    
        }
        
         function loadpeopletype($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM people ORDER BY plid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->ple_type.'">'.$row->ple_type.'</option>';
         }
         return $output;    
        
    }
         function loadbrcategory($db,$stationcode)
    {
        $output='';
        $query = $db->query("SELECT * FROM brcategory WHERE comp_code='$stationcode' ORDER BY brcid ASC");
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
         }
         return $output;    
        
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
                                            <a href="<?php echo __ROOT__ . 'branchlist'; ?>">Branchlist</a>
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
  
                    if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Branch Added successfully.
                                </div>";
                  }

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 4 )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Branch Code is already added.
                                </div>";
                  }

                  

                   ?>
                
     </div>
        <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>branch" accept-charset="UTF-8" class="form-horizontal" id="formbranch" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Branch Form</p>
          
    <div class="align-right" style="margin-top:-35px;">
       <button type="submit" class="btn btn-success btnattr_cmn" id="addanbranch" name="addanbranch">
       <i class="fa fa-save"></i>&nbsp; Save & Add
       </button>

       <button type="submit" class="btn btn-primary btnattr_cmn" id="addbranch" name="addbranch">
       <i class="fa  fa-save"></i>&nbsp; Save
       </button>
                                                           
     
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>   

       <a style="color:white" href="<?php echo __ROOT__ . 'branchlist'; ?>">         
       <button type="button" class="btn btn-danger btnattr_cmn" >
       <i class="fa fa-times" ></i>
       </button></a>
    </div>
    </div> 
	<div class="content panel-body">
            <!-- Navigation Tabs -->
      <ul class="nav nav-tabs" id="myTab4">
        <li id="gen_li" class="active">
            <a id="gen_a" data-toggle="tab" href="#general">General</a>
        </li>
        <li id="rate_li">
            <a id="rate_a" data-toggle="disabled" href="#ratefactor"> Rate Factor</a>
        </li>
        <li id="off_li">
            <a id="off_a" data-toggle="disabled" href="#office">Office</a>
        </li>
      </ul>
   <div class="tab_form" >

  
	    
    <div class="tab-content">
  	    <!-- Tab Form 1 -->

     <div id="general" class="tab-pane active" style="margin-left: 25px;">
     <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	    <tbody>
        <tr>		
            <td style="width:15%">Company Code</td>
            <td  style="width:25%">
                <div class="col-sm-9">
                     <input placeholder="" onkeyup="stncode_val(this)" class="form-control border-form" name="stncode" type="text" id="stncode" readonly>
                     <!-- <p id="stncode_val" class="form_valid_strings">Please enter only strings.</p>
                     <p id="stncode_empty" class="form_valid_strings">Field Cannot be Empty.</p> -->
                </div>
            </td>
              
            <td  style="width:15%">Company Name</td>
            <td  style="width:25%">
                <div class="col-sm-9">
                    <input class="form-control border-form " onkeyup="stnname_val(this)"  name="stnname" type="text" id="stnname" placeholder="Station Name" readonly>
                     <p id="stnname_val" class="form_valid_strings">Special Characters not allowed. </p> 
                     <p id="stnname_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>
       </tr>
       <tr>
            <td>Branch Code</td>
            <td >
                <div class="col-sm-9 autocomplete">
                    <input onkeyup="brcode_val(this)" class="form-control border-form  text-uppercase" name="br_code" type="text" id="br_code" required autocomplete="Hello">
                    <p id="br_code_val" class="form_valid_strings">Please enter only strings.</p>
                     <p id="br_code_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div> 
            </td>	
            <td>Branch Name</td>
            <td>
                <div class="col-sm-9">
                      <input class="form-control border-form " onkeyup="brname_val(this)" name="br_name" type="text" id="br_name"  required>
                      <p id="br_name_val" class="form_valid_strings">Special Characters not allowed. </p> 
                     <p id="br_name_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>			
		</tr>
    <tbody id="dynamic_form">
    <tr>		
		<td>People Code</td>
        <td>
        <div class="col-sm-9 autocomplete">
         <input class="form-control border-form "  name="exe_code[]" type="text" id="exe_code">
         </div>
         </td>
               
        <td>People Name</td>
		  	<td>
            <div class="col-sm-7">
            <input class="form-control border-form"  name="exe_name[]" type="text" id="exe_name">
            </div>
            <div class="col-sm-2">
            <input class="form-control border-form"  name="role[]" type="text" id="role" readonly >
            </div>
            <span>
              <button style="margin-left:322px;margin-top: -62px;" type="button" class="btn btn-success btn-xs" id="addbtn" ><i class="fa fa-plus-circle"></i></button>
              </span>
              <span><button type="button" style="margin-top: -62px;" class="btn btn-danger btn-xs" onclick="remove(this)" id="remove" ><i class="fa fa-minus-circle"></i></button></span>
        </td>	
                               
    </tr>
    </tbody> 
   
   
    <tr>	
       	<td>Address</td>
		<td>
          <div class="col-sm-9">
            <input class="form-control border-form"  name="br_address" type="text" id="br_address"  >
          </div>
        </td>
        <td>City</td>
        <td>
          <div class="col-sm-9">
            <input class="form-control border-form" name="br_city" type="text" id="br_city"  >
          </div>
        </td>
        
    </tr>
    <tr>	
    <td>State</td>
        <td>
          <div class="col-sm-9">
            <input class="form-control border-form" name="br_state" type="text" id="br_state"  >
          </div>
        </td>
        <td>Country</td>
        <td>
          <div class="col-sm-9">
            <input class="form-control border-form" name="br_country" type="text" id="br_country" >
          </div>
        </td>
    </tr>
    <tr>
        <td>Branch Group</td>
            <td >
                <div class="col-sm-9">
                    <select class="form-control" name="cate_code">
                    <option value="0">Select</option>
                   <?php echo loadgroup($db,$stationcode); ?>
                    </select>                
                  </div>
            </td>
            <td>Branch Type</td>
            <td>
                <div class="col-sm-9">
                    <select class="form-control" name="branchtype">
                    <option value="B">Business Asscoiates</option>
                    <option value="F">Franchisee</option>
                    <option value="D">Direct Customer</option>
                    <option value="C">Collection Center</option>
                    </select>                
                </div>
            </td>
                
           
    </tr>
    <tr>
        <td>Branch Category</td>
            <td >
                <div class="col-sm-9">
                    <select class="form-control" name="brcate_code">
                    <option value="0">Select</option>
                   <?php echo loadbrcategory($db,$stationcode); ?>
                    </select>                
                  </div>
            </td>
            <td>Origin</td>
			  <td>
            <div class="col-sm-9">
                <input  class="form-control" name="origin" type="text" id="origin" onkeyup="stncode_val(this)" >
            </div>
			  </td>  
		  	
    </tr>
   
    <tr>
        <td>Mobile</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" onkeyup="mob_val(this)" name="br_mobile" maxlength="10" type="number" id="br_mobile" required>
                    <p id="mob_val" class="form_valid_strings">Invalid Mobile Number.</p>
                    <p id="mob_empty" class="form_valid_strings">Field Cannot be Empty.</p>
              </div>
        </td>
        <td>Phone</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="br_phone" maxlength="10" type="number" id="br_phone">
                   
              </div>
        </td>
    </tr>
    <tr>	
		  	<td>Fax</td>
		  	<td>
            <div class="col-sm-9">
                 <input  class="form-control border-form"  name="br_fax" type="number" id="br_fax">
                
            </div>  
        </td>
			
		  	<td>Email</td>
		  	<td>
             <div class="col-sm-9">
                  <input  class="form-control border-form" onkeyup="email_val(this)" name="br_email" type="text" id="br_email">
                  <p id="email_val" class="form_valid_strings">Enter valid Email.</p>
                 
             </div>
		  	</td>	
			
    </tr>
    <tr>	
		  	<td>Transit Hub</td>
		  	<td>
            <div class="col-sm-9">
                <input  class="form-control border-form" name="transit_hub" type="text" id="transit_hub" placeholder="" >
            </div>  
         </td>        
         <td>Active</td>
          <td>
              <div class="col-sm-9">
                  <select class="form-control" name="br_active" id="br_active">
                  <option value="Y" selected="selected">Yes</option>
                  <option value="N">No</option>
                  </select>		
              </div>	
        </td>
			
    </tr>
    <tr>	
		  	 
		  	<td>Remarks</td>
		  	<td>
            <div class="col-sm-9">
                  <input type="text" class="form-control border-form" rows="1" name="br_remarks" cols="50" id="br_remarks">
            </div>
		  	</td>
              <td>Virtual Edit</td>
          <td>
              <div class="col-sm-9">
                      <select class="form-control" name="virtual_edit">
                      <option value="N">No</option>	
                      <option value="Y">Yes</option>	   
              </div>	

         
    </tr>
    <tr>	
      
	
      <td>CC Enable</td>
  <td>
      <div class="col-sm-9">
              <select class="form-control" name="cc_enable">
              <option value="Y">Yes</option>	
              <option value="N">No</option>	
                 
      </div>
    
</tr>
   </tbody>
  </table>
  <div class="align-right">
        <button type="button" class="btn btn-primary" id="btn_rate" data-toggle="tab" onclick="ratefact()">
        Next  &#8594;
        </button>
        </div>
 </div>

            <!-- Second Tab     -->
<div id="ratefactor" class="tab-pane" style="margin-left: 25px;">
   <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	   <tbody>
    <tr>		
    <td>Tax</td>
		  	<td>
            <div class="col-sm-9">
            <input type="text" value="18" class="form-control" name="br_tax" readonly>         
                 </div>
		  	</td>	
            
          <td>Current Invoice</td>
          <td>
              <div class="col-sm-9">
                  <input  class="form-control border-form" onkeyup="currinv_val(this)" name="curinvno" type="text" id="curinvno">
                  <p id="currinv_val" class="form_valid_strings">Invalid Invoice Number.</p>  
              </div>
          </td>
                    </tr><tr>
          <td>Current Collection</td>
          <td >
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="curcolno" type="text" id="curcolno"  >
              </div> 
          </td>	
          
        
          <td>Rate</td>
          <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="rate" type="text" id="rate"  >
              </div>
          </td>			
		</tr>
    <tr>		
          <td>Intl Rate</td>
          <td >
              <div class="col-sm-9">
                   <input  class="form-control border-form" name="intl_rate" type="text" id="intl_rate"  >
              </div>
          </td>

          <td>Branch Ncode</td>
          <td>
              <div class="col-sm-9">
                  <input  class="form-control border-form" name="br_ncode" type="text" id="br_ncode"  >
              </div>
          </td>
         
    </tr>
    <tr>		
    <td></td>
          <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="podall" type="hidden" id="podall"  >
              </div>
          </td>	
    </tr>
     
   </tbody>
   </table>
   <div class="align-right">
   <button type="button" class="btn btn-primary" data-toggle="tab" id="btn_offz" onclick="offz()" >
    Next &#8594;
    </button>
    </div>
  </div>

              <!-- Third Tab -->
    <div id="office" class="tab-pane" style="margin-left: 25px;">
    <table  id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
     <tbody>
     <tr>		
          <td>Manifest</td>
          <td>
              <div class="col-sm-9">
                  <select class="form-control" name="manifest" id="manifest">
                  <option value="No" selected="selected">No</option>
                  <option value="Yes">Yes</option>
                  </select>                
                </div>
          </td>
				
          <td>Bkg Staff</td>
          <td>
              <div class="col-sm-9">
                  <select class="form-control" name="bkgstaff" id="bkgstaff">
                  <option value="No" selected="selected">No</option>
                  <option value="Yes">Yes</option>
                  </select>               
           </div>
          </td>
      </tr>
      <tr>
		    	<td>Proprietor</td>
		    	<td >
             <div class="col-sm-9">
                 <input class="form-control border-form "  name="proprietor" type="text" id="proprietor" >
             </div> 
		    	</td>	
	
		    	<td>Commission</td>
	    		<td>
             <div class="col-sm-9">
                 <input class="form-control border-form "  name="commission" type="text" id="commission" >
             </div>
		    	</td>			
	  	</tr>
       <tr>		
		    	<td>PRO Commission</td>
          <td >
              <div class="col-sm-9">
                  <input class="form-control border-form "  name="pro_commission" type="text" id="pro_commission" >
              </div>
          </td>
                
         <td>Area Manager</td>
          <td>
              <div class="col-sm-9">
                   <input class="form-control border-form "  name="am" type="text" id="am" >
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

function ratefact(){
     if($("#stncode").val()==""){
        $('#stncode_empty').css('display','block');
    }
    if($("#stnname").val()==""){
        $('#stnname_empty').css('display','block');
    }
    if($("#br_code").val()==""){
        $('#br_code_empty').css('display','block');
    }
    if($("#br_name").val()==""){
        $('#br_name_empty').css('display','block');
    }
    if($("#br_mobile").val()==""){
        $('#mob_empty').css('display','block');
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
        if($("#br_name_val").css("display")==="block")
        {
            return false;
        }
        if($("#br_code_val").css("display")==="block")
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
        
        if($("#email_val").css("display")==="block")
        {
            return false;
        } 
        if($("#check_br_stn").css("display")==="block")
        {
            return false;
        }
        else{
            $("#rate_li").attr('class','active');
            $("#gen_li").attr('class','');
            $("#rate_a").attr('data-toggle','tab');
            $("#btn_rate").attr('href','#ratefactor');
        }
    }
}    
function offz(){
    $("#off_li").attr('class','active');
    $("#rate_li").attr('class','');
    $("#off_a").attr('data-toggle','tab');
    $("#btn_offz").attr('href','#office');
}

</script>
<script>
     //Fetch Executive Name
$(document).ready(function(){
     $('#exe_code').keyup(function(){
        var exename_lookup = $('#exe_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{exename_lookup:exename_lookup},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#exe_name').val(data[0]);
            $('#role').val(data[1]);
            }
    });
    });
     $('#exe_name').keyup(function(){
        var execode_lookup = $(this).val();
        var exestn_lookup = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{execode_lookup:execode_lookup,exestn_lookup:exestn_lookup},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#exe_code').val(data[0]);
            $('#role').val(data[1]);
            }
    });
    });
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
            }
    });
  });

</script>



<script>


var stationcode =[<?php  
    $stncode=$db->query("SELECT stncode FROM stncode");
    while($rowcode=$stncode->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$rowcode['stncode']."', ";
    }
 ?>];
</script>

<script>

autocomplete(document.getElementById("transit_hub"), stationcode);

</script>

<script>
 //Dynamic field 
 var addbtn=document.querySelector("#addbtn");
  var i=1;
 addbtn.onclick=function(){
     i++;
     var div=document.createElement("tr");
     div.setAttribute("id","form_group");
     div.innerHTML=gen();
     document.getElementById("dynamic_form").appendChild(div); 
     }
     function gen()
     {
        return '<td>People Code</td> <td> <div class="col-sm-9 autocomplete"> <input class="form-control border-form "  name="exe_code[]" type="text" id="exe_code"> </div> </td> <td>People Name</td><td><div class="col-sm-7"> <input class="form-control border-form"  name="exe_name[]" type="text" id="exe_name"> </div> <div class="col-sm-2"><input class="form-control border-form"  name="role[]" type="text" id="role" readonly > </div></td>';
     }
     function remove()
    {
        if(confirm("Are You sure Want to delete") == true)
        {
            document.getElementById('form_group').remove();
        }
        else{
            return false;
        }
    }

</script>
<script>
   var exename =[<?php 
    $exname=$db->query("SELECT exe_name FROM staff WHERE comp_code='$stationcode'");
    while($rowname=$exname->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$rowname['exe_name']."', ";
    }
 ?>];

var execode =[<?php 
    
    $sql=$db->query("SELECT exe_code FROM staff WHERE comp_code='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['exe_code']."', ";
    }
 ?>];

</script>
<script>
autocomplete(document.getElementById("exe_name"), exename);
autocomplete(document.getElementById("exe_code"), execode);
</script>
<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('exe_code').value = "";
    document.getElementById('exe_name').value = "";
    document.getElementById('br_address').value = "";
    document.getElementById('br_city').value = "";
    document.getElementById('br_state').value = "";
    document.getElementById('br_country').value = "";
    document.getElementById('cate_code').value = "";
    document.getElementById('branchtype').value = "";
    document.getElementById('br_mobile').value = "";
    document.getElementById('br_phone').value = "";
    document.getElementById('br_fax').value = "";
    document.getElementById('br_email').value = "";
    document.getElementById('transit_hub').value = "";
    document.getElementById('origin').value = "";
    document.getElementById('br_remarks').value = "";
    document.getElementById('br_active').value = "";
    document.getElementById('curinvno').value = "";
    document.getElementById('curcolno').value = "";
    document.getElementById('rate').value = "";
    document.getElementById('intl_rate').value = "";
    document.getElementById('br_ncode').value = "";
    document.getElementById('podall').value = "";
    document.getElementById('manifest').value = "";
    document.getElementById('bkgstaff').value = "";
    document.getElementById('proprietor').value = "";
    document.getElementById('commission').value = "";
    document.getElementById('pro_commission').value = "";
    document.getElementById('am').value = "";
}


</script>