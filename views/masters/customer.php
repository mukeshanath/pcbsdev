<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';


if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'customer';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;

    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'False';
        }
        else{
            return 'True';
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

    function loadgroup($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM groups ORDER BY gpid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
         }
         return $output;    
        
    }

    function loadbrcat($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM brcategory ORDER BY brcid ASC');
       
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

    function currentdate()
    {
        $output='';
        date_default_timezone_set('Asia/Kolkata');
        $date_added      	= date('Y-m-d H:i:s');  
        $newDate = date("d-m-Y", strtotime($date_added ));     
            
        //menu list in home page using this code    
        $output .=$date_added ;
  
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

    
 //branch filter conditions
 $sessionbr_code = $datafetch['br_code'];
if(!empty($sessionbr_code)){
    $br_code = $sessionbr_code;
    $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
    $br_name     = $fetchbrname->br_name;
    }
 //branch filter conditions
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
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'customerlist'; ?>">Customer List</a>
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

                  

                   ?>
                
             </div>
          <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>customer" accept-charset="UTF-8" class="form-horizontal" id="formcustomer" enctype="multipart/form-data">
          <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

	        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Form</p>
          
   <div class="align-right" style="margin-top:-35px;">
       <button type="button" class="btn btn-success btnattr_cmn" id="addancust" name="addancustomer">
       <i class="fa fa-save"></i>&nbsp; Save & Add Another
       </button>

       <button type="button" class="btn btn-primary btnattr_cmn" id="addcust" name="addcustomer">
       <i class="fa  fa-save"></i>&nbsp; Save
       </button>
                                                           
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>   

       <a style="color:white" href="<?php echo __ROOT__ . 'customerlist'; ?>">          
       <button type="button" class="btn btn-danger btnattr_cmn" >
        <i class="fa fa-times" ></i>
       </button></a>
    </div>

    </div> 
	        <div class="content panel-body">
        <ul class="nav nav-tabs" id="myTab4">
        <li id="gen_li" class="active">
            <a id="gen_a" data-toggle="tab" href="#general" >General</a>
        </li>
        <li id="rate_li">
            <a id="rate_a" data-toggle="disabled" href="#ratefactor">Rate Factor</a>
        </li>
        <li id="reg_li">
            <a id="reg_a" data-toggle="disabled" href="#regulatory">Regulatory</a>
        </li>
      </ul>
   <div class="tab_form" > 
	    
    <div class="tab-content">
  	    
     <div id="general" class="tab-pane active" style="margin-left: 25px;">
     <table id="formPOSpurchase"  class="table">
	    	<tbody>
        <tr>		
			<td style="width:15%">Company Code</td>
			<td style="width:25%">
      <div class="col-sm-9 autocomplete">
      <input placeholder="" onfocusout="stncode_val(this)" class="form-control border-form" name="comp_code" type="text" id="stncode" readonly>
       <p id="stncode_val" class="form_valid_strings">*Please enter only strings.</p>
          <p id="stncode_empty" class="form_valid_strings">*Field Cannot be Empty.</p>
      </div>
      </td>
				
			<td style="width:15%">Company Name</td>
			<td style="width:25%">
      <div class="col-sm-9">
      <input class="form-control border-form "  onfocusout="stnname_val(this)" name="comp_name" type="text" id="stnname"  readonly>
       <p id="stnname_val" class="form_valid_strings">*Special Characters not allowed. </p> 
        <p id="stnname_empty" class="form_valid_strings">*Field Cannot be Empty.</p>
      </div>
			</td>
      </tr><tr>
			<td>Branch Code</td>
			<td >
      <div class="col-sm-9">
      <input placeholder="" class="form-control border-form br_code text-uppercase" name="br_code" type="text" id="br_code" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?php echo !empty($sessionbr_code)?$br_code:''; ?>" onfocusout="getcust_code()"  autofocus required>
                    <p id="br_code_val" class="form_valid_strings">Please enter only strings.</p>
                    <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                     <p id="br_code_empty" class="form_valid_strings">Field Cannot be Empty.</p>
      </div> 
			</td>	
			
		
			<td>Branch Name</td>
			<td>
      <div class="col-sm-9 autocomplete">
      <input class="form-control border-form text-uppercase"  name="br_name" type="text" id="br_name" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?php echo !empty($sessionbr_code)?$br_name:''; ?>">
       
       </div>
			</td>			
		</tr>
    <tbody id="dynamic_form">
    <tr>		
		<td>People Code</td>
        <td>
        <div class="col-sm-9 autocomplete">
         <input class="form-control border-form capitalise"  name="exe_code[]" type="text" id="exe_code" onfocusout="validpeople();">
         <p id="exe_verify" class="form_valid_strings">Enter valid People.</p>
         </div>
         </td>
               
        <td>People Name</td>
		  	<td>
            <div class="col-sm-7">
            <input class="form-control border-form"  name="exe_name[]" type="text" id="exe_name" onfocusout="validpeople();">
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
			<td>Customer Code</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form "  name="cust_code" type="text" id="cust_code" readonly> 
      <p id="cust_code_duplicatecheck" class="form_valid_strings">Customer Code is Already Exists.</p>
      </div>
			</td>

      <td>Customer Name</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form "  name="cust_name" type="text" id="cust_name" required> 
      </div>
			</td>
			
    </tr>
    <tr>	
    	<td>Address</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form"  name="cust_addr" type="text" id="cust_addr"  >
      </div>
      </td>
      <td>City</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form"   type="text" id="cust_city"  >
      </div>
      </td>
      </tr>
      <tr>	
    	<td>State</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form"   type="text" id="cust_state" >
      </div>
      </td>
      
      <td>Country</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form"   type="text" id="cust_state"  >
      </div>
      </td>
      </tr>
      <tr>
      <td>Mobile</td>
			<td>
      <div class="col-sm-9">
      <input  class="form-control border-form" onfocusout="mob_val(this)" name="cust_mobile" type="number" id="cust_mobile" required>
                  <p id="mob_val" class="form_valid_strings">*Invalid Mobile Number.</p>
                    <p id="mob_empty" class="form_valid_strings">Field Cannot be Empty.</p>
        </div>
      </td>
      <td>Phone</td>
			<td>
      <div class="col-sm-9">
      <input  class="form-control border-form"  name="cust_phone" type="number" id="cust_phone">
          

      </div>
      </td>
      </tr>
      <tr>	
			<td>Pincode</td>
			<td>
      <div class="col-sm-9">
      <input  class="form-control border-form"  name="cust_pincode" type="number" id="cust_pincode" onfocusout="validate_pincode();">
     
      </div>  
    </td>
			
			<td>Email</td>
			<td>
      <div class="col-sm-9">
      <input  class="form-control border-form" onfocusout="email_val(this)" name="cust_email" type="text" id="cust_email">
                <p id="email_val" class="form_valid_strings">Enter valid Email.</p>
                </div>
			</td>	
			
    </tr>
    <tr>
        <tr>	
			<td>Contact Person</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="c_person" type="text" id="c_person" placeholder="" >
                </div>  
    </td>
			
			<td>Zone</td>
			<td>
      <div class="col-sm-9">
       
        <select class="form-control" name="zncode" type="text" id="zncode">            
                <option value="">Select Zone</option>
                <option value="A">Zone A</option>
                <option value="B">Zone B</option>
                <option value="C">Zone C</option>
              </select>
              
         </div>
			</td>	
			
    </tr>
        <tr>
        <tr>	
			<td>Customer Type</td>
			<td>
      <div class="col-sm-9">
          <select class="form-control" name="cust_type">            
                <option value="D">Domestic</option>
                <option value="I">Internation</option>
              </select>
         </div>  
    </td>
    <td>Transit Hub</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="transit_hub" type="text" id="transit_hub" >
      </div>  
    </td>
    </tr>
    <tr>	
		
			<td>Remarks</td>
			<td>
      <div class="col-sm-9">
      <input type="text" class="form-control border-form"  rows="1" name="cust_remarks" cols="50" id="cust_remarks" >
       </div>
			</td>	

      <td>Active</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="cust_active">
      <option value="Y" selected="selected">Yes</option>
      <option value="N">No</option>
    </select>		
      </div>	
  </td>
			
    </tr>
    <tr>	
		
			<td>Push to Website</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="push_to_web">
      <option value="1" selected="selected">Yes</option>
      <option value="0">No</option>
      </select>	
       </div>
			</td>	
      			
		  <td>Credit - Accounts Copy</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="credit_accounts_copy">
      <option value="1">Yes</option>
      <option value="0" selected="selected">No</option>
      </select>	
       </div>
			</td>		
				
		
      </tr>
    <tr>	
    <td>Credit - POD Copy</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="credit_pod_copy">
      <option value="1" selected="selected">Yes</option>
      <option value="0">No</option>
      </select>	
       </div>
			</td>		
      
			<td>Credit - Consignor Copy</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="credit_consignor_copy">
      <option value="1" selected="selected">Yes</option>
      <option value="0">No</option>
      </select>	
       </div>
			</td>			

      </tr>
      <tr>
      <td>Fax</td>
			<td>
      <div class="col-sm-9">
      <input  class="form-control border-form"  name="cust_fax" type="number" id="cust_fax">
     
      </div>  
    </td>
      </tr>
   </tbody>
    </table>
     <div class="align-right">
      <button type="button" class="btn btn-primary" data-toggle="tab" id="btn_rate" onclick="ratefact()" style="background-color:#3E01A4;">
      Next  &#8594;
      </button>
      </div>
    </div>

            <!-- Second Tab     -->
   <div id="ratefactor" class="tab-pane" style="margin-left: 25px;">
   <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	    	<tbody>
        <tr>		
			<td>Discount % D</td>
			<td>
      <div class="col-sm-9">
            <input  class="form-control border-form" name="spl_discountD" type="number" id="spl_discountD"  > 
      </div>
      </td>
				
			<td>Discount % I</td>
			<td>
      <div class="col-sm-9">
           <input  class="form-control border-form" readonly name="spl_discountI" type="number" id="spl_discountI"  >
                </div>
			</td>
                </tr><tr>
			<td>C-Note Advance</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form" name="cnote_adv" type="number" id="cnote_adv" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
      </div> 
			</td>	
			
		
			<td>C-Note Charge</td>
			<td>
      <div class="col-sm-9">
            <input  class="form-control border-form" name="cnote_charges" type="number" id="cnote_charges" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
                </div>
			</td>			
		</tr>
    <tr>		
			<td>PRO</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" name="pro" type="number" id="pro" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
        </div>
      </td>

      <td>PRO Excess Kg</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" name="pro_per_kg" type="number" id="pro_per_kg" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
        </div>
      </td>
      

      </tr>
      <tr>
        <td>PRC</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="prc" type="number" id="prc" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
                </div>
      </td>	

      <td>PRC Excess Kg</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="prc_per_kg" type="number" id="prc_per_kg" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
                </div>
      </td>	
      </tr>
      <tr>		
			<td>PRD</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" name="prd" type="number" id="prd" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
      </td>
                </div>
      <td>PRD Excess Kg</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="prd_per_kg" type="number" id="prd_per_kg" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
      </div>
      </td>	
      </tr>
      <tr>		
			<td>COD</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" name="cod" type="number" id="cod" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
      </td>
                </div>
      <td>COD Excess Kg</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="cod_per_kg" type="number" id="cod_per_kg" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
      </div>
      </td>	
      </tr>
      
      <tr>		
			<td>FSC</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="cust_fsc" type="number" id="cust_fsc">
    </div>
      </td>
				
			<td>FSC Type</td>
			<td>
      <div class="col-sm-9">
          <select class="form-control" name="fsc_type">         
          <option value="F">Fixed</option>
          <option value="D">Dynamic</option>
        </select>               
     </div>
			</td>
      </tr>
     <tr>
     <td>F Handling Charge</td>
      <td>
      <div class="col-sm-9">
      <input  class="form-control border-form" name="f_handling_charge" type="number" id="f_handling_charge" value="<?= $f_handling_charge; ?>"  >
                   
     </div>
        </td>
      
			</td>	
      <td>Credit Limit</td>
      <td>
      <div class="col-sm-9">
      <input type="number" class="form-control" name="credit_limit" id="credit_limit" >         
      </div> 
        </td>
			</td>	
			
		</tr>
    
      <tr>
        <td>Credit Invoice</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form "  name="credit_invoice" type="number" id="credit_invoice" >
      </div>
      </td>			
      
      <td>Rate</td>
			<td>
      <div class="col-sm-9">
       <input class="form-control border-form"  name="rate" type="text" id="rate" style="text-transform:uppercase;">
      </div>
      </td>	
      </tr>
      <tr>	
      <td>Grace Period (Days)</td>
      <td>
      <div class="col-sm-9">
      <input  class="form-control border-form" name="grace_period" type="number" id="grace_period"  >
                   
     </div>
        </td>

      	
        <td>Period</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="contract_period" type="number" id="contract_period" placeholder="Months" >
       </div>
      </td>	
      </tr>
      <tr>
      <td>Contract Date</td>
      <td>
      <div class="col-sm-9">
        <input  class="form-control border-form today" type="date" name="date_contract" id="date_contract" required>
      </div>
      </td>
      <td>Cross Charge CRO</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="cctr">
      <option value="Y" selected="selected">Yes</option>
      <option value="N">No</option>
      </select>	
       </div>
			</td>				
    </tr>
    <tr>
    <td>Cross Charge DLY</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="ccdly">
      <option value="Y" selected="selected">Yes</option>
      <option value="N">No</option>
      </select>	
       </div>
			</td>		
    <td>Credit Control Override</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="credit_control">
      <option value="Y">Yes</option>
      <option value="N" selected="selected">No</option>
      </select>	
       </div>
			</td>		
      </tr>
    <tr>
    <td>Packing Charges</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="packing_charges">
      <option value="1">Yes</option>
      <option value="0" selected="selected">No</option>
      </select>	
       </div>
			</td>		
      <td>Insurance Charges</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="insurance_charges">
      <option value="1">Yes</option>
      <option value="0" selected="selected">No</option>
      </select>	
       </div>
			</td>		
      </tr>
    <tr>
    <td>Other Charges</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="other_charges">
      <option value="1">Yes</option>
      <option value="0" selected="selected">No</option>
      </select>	
       </div>
			</td>
      <td>Upload ContractFile</td>
        <td>
        <div class="col-sm-9">
            <input type="file" class="form-control  input-sm" name="file" accept=".jpg,.pdf" onchange="ValidateSingleInput(this);" id="contract_files" data-max-size="1">
  
               </div>
          </td>		
      </tr>
      <tr>
      <td>Deposit</td>
			<td>
      <div class="col-sm-9">
           <input  class="form-control border-form" name="deposit" type="number" id="deposit"  >
      </div>
			</td>
      <td>Transaction Locking</td>
			<td>
      <div class="col-sm-9">
           
      <select class="form-control" name="tr_lock" id="tr_lock">
      <option value="1">Yes</option>
      <option value="0" selected="selected">No</option>
      </select>	
      </div>
			</td>	
        </tr>
        <tr>
        <td id="tr_dateheading" style="display:none;">Transaction From - To Date</td>
			<td id="tr_datedvi" style="display:none;">
      <div class="col-sm-9">
      <input class="form-control border-form"  name="transaction_date" type="text" id="transaction_date" readonly>
  
               </div>
			</td>
               </tr>
   </tbody>
            </table>
            <div class="align-right">
              <button type="button" class="btn btn-primary" data-toggle="tab" href="#regulatory" onclick="regulatory()" style="background-color:#3E01A4;">
                Next  &#8594;
                </button>
              </div>
              </div>

              <!-- Third Tab -->
    <div id="regulatory" class="tab-pane" style="margin-left: 25px;">
    <table  id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
   <tbody>
          <tr>		
			<td>GSTIN</td>
			<td>
      <div class="col-sm-9">
              <input class="form-control border-form " _pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" name="cust_gstin" type="text" id="cust_gstin" onkeyup="gst_val(this)">
              <p id="gst_val" class="form_valid_strings">Enter valid GSTIN.</p>
                </div>
      </td>
				
			<td>PAN </td>
			<td>
      <div class="col-sm-9">
              <input class="form-control border-form "  name="cust_pan" type="text" id="cust_pan" >
                </div>
			</td>
                </tr><tr>
			<td>TIN </td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form "  name="cust_tin" type="text" id="cust_tin" >
                </div> 
			</td>	
			
		
			<td>UIN </td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form "  name="cust_uin" type="text" id="cust_uin" >
                </div>
			</td>			
		</tr>
    <tr>		
			<td>Quote </td>
			<td >
      <div class="col-sm-9">
        <input class="form-control border-form "  name="quote_no" type="text" id="quote_no" >
        </div>
      </td>
      <td>Track ID</td>
      <td>
      <div class="col-sm-9">
        <input class="form-control border-form "  name="track_id" type="text" id="track_id" >
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
  function validate_pincode(){
    var pin_code=document.getElementById("cust_pincode");

    var pincodevalidate = /^[0-9]{0,6}$/;
    if(!pincodevalidate.test(pin_code.value))
     {
     swal("Pin code should be 6 digits ");
     return false;
     }
  }
</script>

<script>
  $(document).on('change','#tr_lock',function(){
    var tr_lock_status = $(this).val();
    var stncode = $('#stncode').val();
    var cust_code = $('#cust_code').val();
  console.log(cust_code);
    var current_date = add45Days(new Date(), 0);

    var deudate = add45Days(new Date(), 45);
   
    if(tr_lock_status.trim()==1){
        $('#tr_dateheading').show();
        $('#tr_datedvi').show();
        $('#transaction_date').val(current_date+" "+"To"+" "+deudate);
    }else{
      $('#tr_dateheading').hide();
      $('#tr_datedvi').hide();
      $('#transaction_date').val('');

    }
   
  });
</script>


<script>
function add45Days(theDate, days) {
 var duedate = new Date(theDate.getTime() + days*24*60*60*1000);
  var  tabledate = duedate;
  let current_datetime = new Date(tabledate)
  let formatted_date = current_datetime.getFullYear() + '-'+ ('0' + (current_datetime.getMonth()+1)).slice(-2) + '-'+ ('0' + current_datetime.getDate()).slice(-2)
    return formatted_date;
}


</script>
<script>
  $(document).ready(function(){
   $("#cust_gstin").keyup(function(){
     var gst = $("#cust_gstin").val();
      $("#cust_pan").val(gst.slice(2,-3));
   });
  });
</script>



<script>
  $('#addancust').click(function(){
      if($("#cust_code_duplicatecheck").css("display")==="block")
          {             
              swal("Cust code already exists");
              return false;
          }
      if($("#brcode_validation").css("display")==="block")
          {
              swal("Please enter valid branch");
              return false;
          }
        if($("#gst_val").css("display")==="block")
        {
          swal("Please enter valid GST Number");
            return false;
        }
        if(validate_pincode()==false){
          swal("Pin code should be 6 digits ");
              return false;
        }
        else{
          $('#addancust').attr('type','submit');
	        $('#addcust').attr('type','submit');
        }
  });
  $('#addcust').click(function(){
      if($("#cust_code_duplicatecheck").css("display")==="block")
          {
              swal("Cust code already exists");
              return false;
          }
      if($("#brcode_validation").css("display")==="block")
          {
              swal("Please enter valid branch");
              return false;
          }
          if($("#gst_val").css("display")==="block")
        {
          swal("Please enter valid GST Number");
            return false;
        }
        if(validate_pincode()==false){
          swal("Pin code should be 6 digits ");
              return false;
        }
        else{
          $('#addancust').attr('type','submit');
	        $('#addcust').attr('type','submit');
        }
  });
</script>
<script>
  function validatebranchgrp(){
    var br_code = $('#br_code').val();
      var genccode_stn = $('#stncode').val();
        //check branch category and make editable cust_code
        $.ajax({
              url:"<?php echo __ROOT__ ?>mastercontroller",
              method:"POST",
              data:{btypr_br:br_code,btype_stncode:genccode_stn},
              dataType:"text",
              success:function(data)
              {
              if(data.trim()=='CRO' || data.trim()=='DLY'){
                $('#cust_code').removeAttr('readonly');
              }
              else{
                $('#cust_code').attr('readonly','readonly');
              }
              }
      });
    }
</script>
<script>

  function getcust_code(){
       validatebranchgrp();
      var br_code = $('#br_code').val().toUpperCase().trim();
      var genccode_stn = $('#stncode').val();
      $.ajax({
              url:"<?php echo __ROOT__ ?>mastercontroller",
              method:"POST",
              data:{br_code:br_code,genccode_stn},
              dataType:"text",
              success:function(data)
              {
                console.log(data);
              var data1 = data.split(",");
              if(data1[1]=='0'){
                $('#cust_code_duplicatecheck').css('display','none');
              }else{
                $('#cust_code_duplicatecheck').css('display','block');
              }
              $('#cust_code').val(data1[0]);
              }
      });
  }

</script>
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
    if($("#cust_mobile").val()==""){
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
        if($("#cust_code_duplicatecheck").css("display")==="block")
        {
            swal("Cust code already exists");
        }
        if($("#mob_val").css("display")==="block")
        {
            return false;
        }
        if($("#exe_verify").css("display")==="block")
        {
            return false;
        }
        if($("#gst_val").css("display")==="block")
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

function regulatory(){
    document.getElementById("reg_li").className = "active";
    document.getElementById("rate_li").className = "";
    document.getElementById("reg_a").setAttribute('data-toggle','tab');

}

</script>
<script>
     //Fetch Executive Name
$(document).ready(function(){
     $('#exe_code').focusout(function(){
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
    $('#exe_name').focusout(function(){
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
            validpeople();
            }
    });
    });
});
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
//Fetch Branch Name
$('#br_name').focusout(function(){
        var brname = $(this).val();
        var brstn = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{brname:brname,brstn:brstn},
            dataType:"text",
            success:function(brcode)
            {
            $('#br_code').val(brcode);
            validatebranchgrp();
            getcust_code();
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
                // $('#addancust').attr('type','button');
	            	// $('#addcust').attr('type','button');
              
            }
            else{
                $('#brcode_validation').css('display','none');
                // $('#addancust').attr('type','submit');
	            	// $('#addcust').attr('type','submit');
                
            }
            }
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
            if($('#stncode').val() == '')
            {
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

</script>
<script>

var zones =[<?php  
    $sql=$db->query("SELECT zone_code FROM zone WHERE zone_type = 'multi'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']."', ";
    }
 ?>];

 
var branchcode =[<?php  
      
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode' AND br_active='Y'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];

var rateid =[<?php  
      
      $sql=$db->query("SELECT cust_code,cust_name FROM customer WHERE comp_code='$stationcode'");
      while($row=$sql->fetch(PDO::FETCH_ASSOC))
      {
      echo "'".$row['cust_code']."-".$row['cust_name']."',";
      }

 ?>];


</script>


<script>

var branchname = [<?php
 $brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND br_active='Y'");
 while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
 {
 echo "'".$rowbrname['br_name']."',";
 }
 ?>]

</script>
<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);
autocomplete(document.getElementById("zncode"), zones);
autocomplete(document.getElementById("rate"), rateid);
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
      return '<td>People Code</td> <td> <div class="col-sm-9 autocomplete"> <input class="form-control border-form "  name="exe_code[]" type="text" id="exe_code"> </div> </td> <td>People Name</td><td><div class="col-sm-7"> <input class="form-control border-form"  name="exe_name[]" type="text" id="exe_name" readonly > </div> <div class="col-sm-2"><input class="form-control border-form"  name="role[]" type="text" id="role" readonly > </div></td>';
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
  function validpeople(){
    var peop_code = $('#exe_code').val();
    var peop_stn = $('#stncode').val();
    $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{peop_code:peop_code,peop_stn:peop_stn},
            dataType:"text",
            success:function(exe_count)
            {
              if(exe_count==0){
                $('#exe_verify').css('display','block');
              }else{
                $('#exe_verify').css('display','none');
              }
            }
    });
  }
  </script>
<script>
     var _validFileExtensions = [".jpg", ".pdf"];    
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
            if (!blnValid) {
                alert("Sorry, file is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}
</script>
<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('exe_code').value = "";
    document.getElementById('exe_name').value = "";
    document.getElementById('cust_code').value = "";
    document.getElementById('cust_name').value = "";
    document.getElementById('cust_addr').value = "";
    document.getElementById('cust_city').value = "";
    document.getElementById('cust_state').value = "";
    document.getElementById('cust_country').value = "";
    document.getElementById('cust_mobile').value = "";
    document.getElementById('cust_phone').value = "";
    document.getElementById('cust_fax').value = "";
    document.getElementById('cust_email').value = "";
    document.getElementById('c_person').value = "";
    document.getElementById('zncode').value = "";
    document.getElementById('cust_type').value = "";
    document.getElementById('transit_hub').value = "";
    document.getElementById('cust_remarks').value = "";
    document.getElementById('cust_active').value = "";
    document.getElementById('spl_discountD').value = "";
    document.getElementById('spl_discountI').value = "";
    document.getElementById('cnote_adv').value = "";
    document.getElementById('cnote_charges').value = "";
    document.getElementById('pro').value = "";
    document.getElementById('prc').value = "";
    document.getElementById('cust_fsc').value = "";
    document.getElementById('fsc_type').value = "";
    document.getElementById('grace_period').value = "";
    document.getElementById('credit_invoice').value = "";
    document.getElementById('credit_limit').value = "";
    document.getElementById('rate').value = "";
    document.getElementById('deposit').value = "";
    document.getElementById('contract_period').value = "";
    document.getElementById('date_contract').value = "";
    document.getElementById('cust_gstin').value = "";
    document.getElementById('cust_pan').value = "";
    document.getElementById('cust_tin').value = "";
    document.getElementById('cust_uin').value = "";
    document.getElementById('quote_no').value = "";
    document.getElementById('track_id').value = "";
}

$(window).on('load', function () {
    if(<?=isset($sessionbr_code)?>)
    getcust_code();
  });
</script>