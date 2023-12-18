<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'companyedit';
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

if(authorize($db,$user)=='False')

{

    echo "<div class='alert alert-danger alert-dismissible'>
           
            <h4><i class='icon fa fa-times'></i> Alert!</h4>
            You don't have permission to access this Page.
            </div>";
            exit;
  }

if (isset($_GET['stnid'])) {
$stn_id = $_GET['stnid'];
$sql = $db->query("SELECT * FROM station WHERE stnid = '$stn_id'");
$row = $sql->fetch();
$stnid            = $row['stnid'];
$stncode          = $row['stncode'];
$stnname          = $row['stnname'];
$station          = $row['station'];
$stn_addrtype     = $row['stn_addrtype'];
$stn_addr         = $row['stn_addr'];
$stn_city         = $row['stn_city'];
$stn_state        = $row['stn_state'];
$stn_country      = $row['stn_country'];
$stn_mobile       = $row['stn_mobile'];
$stn_phone        = $row['stn_phone'];
$stn_email        = $row['stn_email'];
$stn_fax          = $row['stn_fax'];
$stn_gstin        = $row['stn_gstin'];
$stn_taxval       = $row['stn_taxval'];
$stn_pan          = $row['stn_pan'];
$stn_tin          = $row['stn_tin'];
$stn_active       = $row['stn_active'];
$curinvno         = $row['curinvno'];
$stnauto          = $row['stnauto'];
$baccname         = $row['baccname'];
$baccno           = $row['baccno'];
$bacctype         = $row['bacctype'];
$bankname         = $row['bankname'];
$bbranch          = $row['bbranch'];
$ifsc             = $row['ifsc'];
$micr             = $row['micr'];
$swift            = $row['swift'];
$transit_hub      = $row['transit_hub'];
$weblogin         = $row['weblogin'];
$webpassword      = $row['webpassword'];
$webid            = $row['webid'];
$stn_region       = $row['stn_region'];
$stn_remarks      = $row['stn_remarks'];
$username         = $row['user_name'];
$account_id      = $row['account_id'];
$gta      = $row['gta'];

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
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" id="companysection" style="background-color : #eee;">  


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

     <form method="POST" action="<?php echo __ROOT__ ?>companyedit" accept-charset="UTF-8" class="form-horizontal" id="formstation" enctype="multipart/form-data">

            <input  class="form-control border-form" name="stnid" type="text" id="stnid" value="<?= $stnid; ?>" style="display:none">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;"> 
 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Company Alter Form</p>
          
    <div class="align-right" style="margin-top:-35px;">
 
       <button type="submit" class="btn btn-primary btnattr_cmn" name="updatecompany">
       <i class="fa  fa-archive"></i>&nbsp; Update
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
            <a id="reg_a" data-toggle="tab" href="#regulatory" >Regulatory</a>
        </li>
        <li id="bank_li">
            <a id="bank_a" data-toggle="tab" href="#banking">Banking</a>
        </li>
        <li id="web_li">
            <a id="web_a" data-toggle="tab" href="#web">Web</a>
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
            <td>
                <div class="col-sm-9">
                         <input class="form-control border-form" name="stncode" type="text" id="stncode" value="<?= $stncode; ?>" readonly >
                </div>
            </td>
              
            <td>Company Name</td>
            <td>
                <div class="col-sm-9">
                     <input type="text" class="form-control border-form "  name="stnname"  id="stnname" value="<?= $stnname; ?>" readonly>
                </div>
            </td>
       </tr>
      <tr>	
        		
			  <td>Address</td>
			  <td>
               <div class="col-sm-9">
                     <input class="form-control border-form "  name="stn_addr" type="text" id="stn_addr"  value="<?= $stn_addr; ?>" >
                </div>
        </td>
        <td>City</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="stn_city" type="text" id="stn_city"  value="<?= $stn_city; ?>">
                </div>
             </td>
                </tr>
                <tr>			
			  <td>State</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="stn_state" type="text" id="stn_state"  value="<?= $stn_state; ?>">
                </div>
             </td>
             <td>Country</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="stn_country" type="text" id="stn_country"  value="<?= $stn_country; ?>">
                </div>
             </td>
                </tr>
            <tr>
        <td>Mobile</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="stn_mobile" type="text" id="stn_mobile" value="<?= $stn_mobile; ?>"  >
              </div>
        </td>
        <td>Phone</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="stn_phone" type="text" id="stn_phone" value="<?= $stn_phone; ?>"  >
              </div>
        </td>
    </tr>
    <tr>	
		  	<td>Fax</td>
		  	<td>
            <div class="col-sm-9">
                    <input  class="form-control border-form" name="stn_fax" type="text" id="stn_fax"  value="<?= $stn_fax; ?>" >
            </div>  
        </td>
			
		  	<td>Email</td>
		  	<td>
             <div class="col-sm-9">
                    <input  class="form-control border-form" name="stn_email" type="text" id="stn_email" placeholder="" value="<?= $stn_email; ?>" >
             </div>
		  	</td>	
			
    </tr>
    <tr>	
		<td>Active</td>
			<td>
            <div class="col-sm-9">
                <select class="form-control" name="stn_active">
                      <option value="Yes" selected="selected">Yes</option>
                      <option value="No">No</option>
                    </select> 
                </div>             
         </td>
         <td>Region</td>
             <td>
              <div class="col-sm-9">
                    <input type="text" class="form-control" name="stn_region" value="<?= $stn_region; ?>" >  
                </div>	
           </td>
     </tr>
        <tr>	
          <td>Hub</td>
          <td>
              <div class="col-sm-9">
                     <input  class="form-control border-form" name="transit_hub" type="text" id="transit_hub" placeholder="" value="<?= $transit_hub; ?>" >
              </div>	
            </td>
            <td>Station Auto</td>
          <td>
              <div class="col-sm-9">
                      <input type="text" class="form-control" name="stnauto" value="<?= $stnauto; ?>" >
            </div>	
            </td>
    </tr>
<tr>
    <td>GTA</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="gta">    
      <option <?=($gta)?"selected":"";?> value="N">No</option>
      <option <?=($gta)?"selected":"";?> value="Y">Yes</option>
      </select>	
       </div>
			</td>
        
    </tr>
   </tbody>
  </table>
      
  <div class="align-right">
        <button type="button" class="btn btn-primary align-right" data-toggle="tab" onclick="regulatory()" id="btn_regul">
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
                  <input type="text" value="18" class="form-control" name="stn_taxval" value="<?=$stn_taxval?>">         
              </div>
          </td>
            
          <td>GSTIN</td>
          <td>
              <div class="col-sm-9">
                <input class="form-control border-form" onfocusout="gst_val(this)"  name="stn_gstin" type="text" id="stn_gstin" value="<?=$stn_gstin?>">	
                <p id="gst_val" class="form_valid_strings">Enter valid GST Number.</p>                                   
                </div>
          </td>
                    </tr><tr>
          <td>PAN</td>
          <td>
              <div class="col-sm-9">
                  <input class="form-control border-form "  name="stn_pan" type="text" id="stn_pan" value="<?=$stn_pan?>" >
              </div> 
          </td>	

           <td>TIN </td>
          <td>
              <div class="col-sm-9">
                  <input class="form-control border-form "  name="stn_tin" type="text" id="stn_tin" value="<?=$stn_tin?>" >
              </div> 
          </td>	
        
         
            </tr>
            <tr>
            <td>Current Invoice</td>
          <td>
              <div class="col-sm-9">
                   <input class="form-control border-form "  name="bbranch" type="text" id="bbranch" >
              </div>
          </td>	
                </tr>
        </tbody>
        </table>
        <div class="align-right">
        <button type="button" class="btn btn-primary align-right" data-toggle="tab" onclick="bankng()" id="btn_bank">
            Next  &#8594;
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
                     <input class="form-control border-form "  name="baccname" type="text" id="baccname" value="<?= $baccname; ?>" >
              </div>
          </td>
            
          <td>Bank Account No</td>
          <td>
              <div class="col-sm-9">
                  <input class="form-control border-form "  name="baccno" type="text" id="baccno" value="<?= $baccno; ?>" >
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
                    <input class="form-control border-form "  name="bbranch" type="text" id="bbranch" value="<?= $bbranch; ?>" >
              </div>
          </td>			
		</tr>
    <tr>		
          <td>Account Type</td>
          <td>
              <div class="col-sm-9">
                         <input type="text" class="form-control" name="bacctype" value="<?= $bacctype; ?>" >           
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
          <td>SWIFT</td>
          <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" name="swift" type="text" id="swift"  value="<?= $swift; ?>" >
              </div>
          </td>
    </tr>
    <tr>		
          <td>Account Id</td>
          <td>
              <div class="col-sm-9">
                     <input  class="form-control border-form" name="account_id" type="text" id="account_id" value="<?=$account_id?>">
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
                <div class="col-sm-9">
                        <input class="form-control border-form "  name="weblogin" type="text" id="weblogin" value="<?= $weblogin; ?>" >
                </div>
                </td>
                        
                <td>Web Password</td>
                <td>
                <div class="col-sm-9">
                    <input class="form-control border-form "  name="webpassword" type="text" id="webpassword" value="<?= $webpassword; ?>"  >
                </div>
                </td>
            </tr>
            <tr>
                <td>WebID</td>
		    	<td>
             <div class="col-sm-9">
                   <input class="form-control border-form "  name="webid" type="text" id="webid" value="<?= $webid; ?>" >
             </div> 
		    	</td>	
	
		    	<td>Remarks</td>
	    		<td>
             <div class="col-sm-9">
             <input type="text" class="form-control border-form"  rows="1" name="stn_remarks" cols="50" id="stn_remarks"  value="<?= $stn_remarks; ?>" >
             </div>
		    	</td>			
	  	</tr>
            
  </tbody>             
</table>
    </div>       
	</div>
	</div>
  </div>
  </form>
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
            $("#reg_li").attr("class","active");
            $("#gen_li").attr("class","");
            $("#reg_a").attr('data-toggle','tab');
            $("#btn_regul").attr('href','#regulatory');
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