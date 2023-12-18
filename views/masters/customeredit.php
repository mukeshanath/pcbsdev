<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $module = 'customeredit';
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

if (isset($_GET['cusid'])) {
$cus_id = $_GET['cusid'];
$cus_code = $_GET['cust_code'];
$sql = $db->query("SELECT * FROM customer WHERE cusid = '$cus_id' AND cust_code='$cus_code' AND comp_code='$stationcode'");
$row = $sql->fetch();
$cusid             = $row['cusid'];
$stncode           = $row['comp_code'];
$stnname           = $row['comp_name'];
$br_code           = $row['br_code'];
$br_name           = $row['br_name'];
$cust_code         = $row['cust_code'];
$cust_name         = $row['cust_name'];
$exe_code          = $row['exe_code'];
$exe_name          = $row['exe_name'];
$role              = $row['role'];
$cust_addr         = $row['cust_addr'];
$cust_city         = $row['cust_city'];
$cust_state        = $row['cust_state'];
$cust_country      = $row['cust_country'];
$cust_phone        = $row['cust_phone'];
$cust_mobile       = $row['cust_mobile'];
$cust_fax          = $row['cust_fax'];
$cust_email        = $row['cust_email'];
$c_person          = $row['c_person'];
$zncode            = $row['zncode'];
$cust_type         = $row['cust_type'];
$transit_hub       = $row['transit_hub'];
$cust_remarks      = $row['cust_remarks'];
$cust_active       = $row['cust_active'];
$spl_discountD     = $row['spl_discountD'];
$spl_discountI     = $row['spl_discountI'];
$cnote_adv         = $row['cnote_adv'];
$cnote_charges     = $row['cnote_charges'];
$pro               = $row['pro'];
$prc               = $row['prc'];
$prd				       = $row['prd'];
$pro_per_kg			   = $row['pro_per_kg'];
$prc_per_kg		     = $row['prc_per_kg'];
$prd_per_kg			   = $row['prd_per_kg'];
$cod_per_kg			   = $row['cod_per_kg'];
$cod      			   = $row['cod'];
$cust_fsc          = $row['cust_fsc'];
$fsc_type          = $row['fsc_type'];
$grace_period      = $row['grace_period'];
$credit_invoice    = $row['credit_invoice'];
$cust_msr          = $row['cust_msr'];
$cust_bus_vol      = $row['cust_bus_vol'];
$deposit           = $row['deposit'];
$date_contract     = $row['date_contract'];
$contract_period   = $row['contract_period'];
$cust_gstin        = $row['cust_gstin'];
$cust_pan          = $row['cust_pan'];
$cust_tin          = $row['cust_tin'];
$cust_uin          = $row['cust_uin'];
$quote_no          = $row['quote_no'];
$credit_limit      = $row['credit_limit'];
$track_id          = $row['track_id'];
$rate              = $row['rate'];
$username          = $row['user_name'];
$push_to_web       = $row['push_to_website'];
$cctr              = $row['cctr'];
$ccdly             = $row['ccdly'];
$credit_control    = $row['credit_control'];
$other_charges     = $row['other_charges'];
$packing_charges   = $row['packing_charges'];
$insurance_charges = $row['insurance_charges'];
$credit_accounts_copy = $row['credit_accounts_copy'];
$credit_pod_copy    = $row['credit_pod_copy'];
$credit_consignor_copy = $row['credit_consignor_copy'];
$f_handling_charge  = $row['fhandling_charges'];
$tr_lock            = $row['tr_lock'];
$tr_lock_date            = $row['tr_lock_date'];
$cust_pincode       = $row['pincode'];

$ratefile = $db->query("SELECT TOP 1 * FROM contract_files WHERE comp_code='$stncode' AND cust_code='$cust_code' ORDER BY date_modified DESC")->fetch(PDO::FETCH_OBJ);
$ratefilehistory = $db->query("SELECT id,file_name,date_modified FROM contract_files WHERE comp_code='$stncode' AND cust_code='$cust_code' ORDER BY date_modified DESC");

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

    function loadzone($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM zone ORDER BY znid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->zncode.'">'.$row->zncode.'</option>';
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
 
    <form method="POST" action="<?php echo __ROOT__ ?>customeredit" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="formcustomer" enctype="multipart/form-data">
      <input placeholder="" class="form-control border-form" name="cusid" type="text" id="cusid" value="<?= $cusid; ?>" style="display:none;">
      <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;"> 
	        <div class="boxed boxBt panel panel-primary">
           <div class="title_1 title panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Alter Form</p>
          
           <div class="align-right" style="margin-top:-30px;">
 
            <button type="submit" class="btn btn-primary btnattr_cmn" name="updatecustomer" id="updatecust">
            <i class="fa  fa-archive"></i>&nbsp; Update
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
            <a id="rate_a" data-toggle="tab" href="#ratefactor">Rate Factor</a>
        </li>
        <li id="reg_li">
            <a id="reg_a" data-toggle="tab" href="#regulatory">Regulatory</a>
        </li>
      </ul>
   <div class="tab_form" >

  
	    
    <div class="tab-content">
  	    
     <div id="general" class="tab-pane active" style="margin-left: 25px;">
     <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	    	<tbody>
        <tr>		
			<td>Company Code</td>
			<td>
      <div class="col-sm-9">
        <input placeholder="" style="background: ;" class="form-control border-form" value="<?= $stncode; ?>" name="comp_code" type="text" id="stncode" readonly>
                </div>
      </td>
				
			<td>Company Name</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form " value="<?= $stnname; ?>"  name="comp_name" type="text" id="stnname" readonly >
                </div>
			</td>
      </tr>
       <tr>
			<td>Branch Code</td>
			<td >
      <div class="col-sm-9">
      <input placeholder="" value="<?= $br_code; ?>" class="form-control border-form" name="br_code" type="text" id="br_code" readonly>
       </div> 
			</td>	
			
			<td>Branch Name</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form" value="<?= $br_name; ?>" name="br_name" type="text" id="br_name" readonly >
      </div>
			</td>			
		</tr>
    <tr>		
		
      <td>People Code</td>
        <td>
        <div class="col-sm-9 autocomplete">
        <input  class="form-control border-form" value="<?= $exe_code; ?>" name="exe_code" type="text" id="exe_code">
         </div>
         </td>
               
         <td>People Name</td>
		  	<td>
            <div class="col-sm-7">
            <input class="form-control border-form" value="<?= $exe_name; ?>" name="exe_name" type="text" id="exe_name">
            </div>
            <div class="col-sm-2">
            <input class="form-control border-form" value="<?= $role; ?>" name="role" type="text" id="role" >
            </div>
        </td>	
      </tr>
      <tr>
        <td>Customer Code</td>
          <td >
              <div class="col-sm-9">
                  <input placeholder=""  value="<?= $cust_code; ?>" class="form-control border-form" name="cust_code" type="text" id="cust_code" readonly>
                </div> </td>
      <td>Customer Name</td>
      <td>
      <div class="col-sm-9">
        <input class="form-control border-form" value="<?= $cust_name; ?>"  name="cust_name" type="text" id="cust_name" >

                </div>
                </td>
    </tr>
    <tr>	
    	<td>Address</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form" value="<?= $cust_addr; ?>" name="cust_addr" type="text" id="cust_addr">
       </div>
       </td>

       <td>City</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form" value="<?= $cust_city; ?>" name="cust_city" type="text" id="cust_city" >
       </div>
       </td>
      </tr>
      <tr>	
    	<td>State</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form" value="<?= $cust_state; ?>" name="cust_state" type="text" id="cust_state">
       </div>
       </td>

       <td>Country</td>
			<td>
      <div class="col-sm-9">
      <input class="form-control border-form" value="<?= $cust_country; ?>" name="cust_country" type="text" id="cust_country" >
       </div>
       </td>
      </tr>
      <tr>
      <td>Mobile</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $cust_mobile; ?>" name="cust_mobile" type="text" id="cust_mobile"  >
                </div>
      </td>
      <td>Phone</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $cust_phone; ?>" name="cust_phone" type="text" id="cust_phone"  >
                </div>
      </td>
      </tr>
      <tr>	
			<td>Pincode</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form"  value="<?= $cust_pincode; ?>" name="cust_pincode" type="text" id="cust_pincode"   >
                </div>  
    </td>
			
			<td>Email</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $cust_email; ?>" name="cust_email" type="text" id="cust_email"  >
                </div>
			</td>	
			
    </tr>
        <tr>	
			<td>Contact Person</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $c_person ?>" name="c_person" type="text" id="c_person" placeholder="" >
                </div>  
    </td>
			
			<td>Zone</td>
			<td>
      <div class="col-sm-9">
           <input type="text" class="form-control" name="zncode" value="<?= $zncode; ?>" >
         </div>
			</td>	
			
    </tr>
        <tr>	
			<td>Customer Type</td>
			<td>
      <div class="col-sm-9">
             <input type="text" class="form-control" name="cust_type" value="<?= $cust_type; ?>" >
         </div>  
    </td>
    <td>Transit Hub</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $transit_hub; ?>" name="transit_hub" type="text" id="transit_hub"  >
      </div>  
    </td>
			
			
    </tr>
    <tr>	
			<td>Remarks</td>
			<td>
      <div class="col-sm-9">
      <input type="text" class="form-control border-form" value="<?= $cust_remarks; ?>"  rows="1" name="cust_remarks" cols="50" id="cust_remarks" >
      </div>          
			</td>	

      <td>Active</td>
			<td>
      <div class="col-sm-9">
          <input type="text" class="form-control" name="cust_active" value="<?= $cust_active; ?>" >		
      </div>	
  </td>
			
    </tr>
    <tr>	
		
    <td>Push to Website</td>
    <td>
    <div class="col-sm-9">
    <select class="form-control" name="push_to_web">
      <?php 
      if($push_to_web=='1'){
        echo "<option value='1'>Yes</option>
        <option value='0'>No</option>";
      }else{
        echo "<option value='0'>No</option>
        <option value='1'>Yes</option>";
      }
      ?>
    </select>		
     </div>
    </td>		
    <td>Credit - Accounts Copy</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="credit_accounts_copy">
      <option value="1" <?=($credit_accounts_copy)?"selected":"";?>>Yes</option>
      <option value="0" <?=(!$credit_accounts_copy)?"selected":"";?>>No</option>
      </select>	
       </div>
			</td>		
				
		
      </tr>
    <tr>	
    <td>Credit - POD Copy</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="credit_pod_copy">
      <option value="1" <?=($credit_pod_copy)?"selected":"";?>>Yes</option>
      <option value="0" <?=(!$credit_pod_copy)?"selected":"";?>>No</option>
      </select>	
       </div>
			</td>		
      
			<td>Credit - Consignor Copy</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="credit_consignor_copy">
      <option value="1" <?=($credit_consignor_copy)?"selected":"";?>>Yes</option>
      <option value="0" <?=(!$credit_consignor_copy)?"selected":"";?>>No</option>
      </select>	
       </div>
			</td>				
  		
  </tr>
  <tr>
  <td>Fax</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form"  value="<?= $cust_fax; ?>" name="cust_fax" type="text" id="cust_fax"   >
                </div>  
    </td>
  </tr>
   </tbody>
               </table>
               <div class="align-right">
               <button type="button" class="btn btn-primary" data-toggle="tab" href="#ratefactor" onclick="ratefact()" style="background-color:#3E01A4;">
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
          <input  class="form-control border-form" value="<?= $spl_discountD; ?>" name="spl_discountD" type="text" id="spl_discountD"  >
      </div>
      </td>
				
			<td>Discount % I</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" readonly value="<?= $spl_discountI; ?>" name="spl_discountI" type="text" id="spl_discountI"  >
                </div>
			</td>
                </tr><tr>
			<td>C-Note Advance</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $cnote_adv; ?>" name="cnote_adv" type="text" id="cnote_adv"  >
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
			<td>PRO</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $pro; ?>" name="pro" type="text" id="pro"  >
        </div>
      </td>

      <td>PRO Excess Kg</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $pro_per_kg; ?>" name="pro_per_kg" type="text" id="pro_per_kg"  >
        </div>
      </td>
      

      </tr>
      <tr>
        <td>PRC</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $prc; ?>" name="prc" type="text" id="prc"  >
                </div>
      </td>	

      <td>PRC Excess Kg</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $prc_per_kg; ?>" name="prc_per_kg" type="text" id="prc_per_kg"  >
      </div>
      </td>	
      </tr>
      <tr>		
			<td>PRD</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $prd; ?>" name="prd" type="text" id="prd"  >
      </td>
                </div>
      <td>PRD Excess Kg</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $prd_per_kg; ?>" name="prd_per_kg" type="text" id="prd_per_kg"  >
      </div>
      </td>	
      </tr>
      <tr>		
			<td>COD</td>
			<td >
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $cod; ?>" name="cod" type="number" id="cod" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
      </td>
                </div>
      <td>COD Excess Kg</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $cod_per_kg; ?>" name="cod_per_kg" type="number" id="cod_per_kg" pattern="[0-9]+([\.,][0-9]+)?" step="0.001">
      </div>
      </td>	
      </tr>
              <tr>		
			<td>FSC</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" value="<?= $cust_fsc; ?>" name="cust_fsc" type="text" id="cust_fsc"  >
    </div>
      </td>
				
			<td>FSC Type</td>
			<td>
      <!-- <div class="col-sm-9">
             <input type="text" class="form-control" name="fsc_type" value="<?= $fsc_type; ?>" >             
   </div> -->
   <div class="col-sm-9">
          <select class="form-control" name="fsc_type">         
          <option <?=(($fsc_type=='F')?'Selected':''); ?> value="F">Fixed</option>
          <option <?=(($fsc_type=='D')?'Selected':''); ?> value="D">Dynamic</option>
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
      <input type="number" class="form-control" name="credit_limit" id="credit_limit"  value="<?= $credit_limit; ?>">     
       
      </div> 
        </td>
			</td>	
			
		</tr>
    
      <tr>
        <td>Credit Invoice</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form "  name="credit_invoice" type="text" id="credit_invoice" value="<?= $credit_invoice; ?>" >
      </div>
      </td>			
      
      <td>Rate</td>
			<td>
      <div class="col-sm-9">
       <input class="form-control border-form text-uppercase"  name="rate" type="text" id="rate" value="<?= $rate; ?>" >
      </div>
      </td>	
      </tr>
      <tr>
        <td>MSR Value</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form "  name="cust_msr" type="text" id="cust_msr" value="<?= $cust_msr; ?>" >
      </div>
      </td>			
      <td>Grace Period (Days)</td>
      <td>
      <div class="col-sm-9">
      <input  class="form-control border-form" name="grace_period" type="number" id="grace_period" value="<?= $grace_period; ?>"  >
                   
     </div>
        </td>
      	
      </tr>
      <tr>		
      <td>Deposit</td>
			<td>
      <div class="col-sm-9">
           <input  class="form-control border-form" name="deposit" type="text" id="deposit"  value="<?= $deposit; ?>" >
      </div>
			</td>	
        <td>Period</td>
			<td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="contract_period" type="text" id="contract_period" placeholder="Months" value="<?= $contract_period; ?>">
       </div>
      </td>	
      </tr>
      <tr>
      <td>Contract Date</td>
      <td>
      <div class="col-sm-9">
        <input  class="form-control border-form" name="date_contract" type="date" id="date_contract" value="<?= $date_contract; ?>" >
      </div>
      </td>
      <td>Cross Charge TR</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="cctr">
      <?php if($cctr=='Y'){
          echo "<option value='Y'>Yes</option>
          <option value='N'>No</option>";
            }else{
              echo "<option value='N'>No</option>
              <option value='Y'>Yes</option>";
          }
      ?>
      </select>	
       </div>
			</td>				
    </tr>
    <tr>
    <td>Cross Charge DLY</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="ccdly">
      <?php if($ccdly=='Y'){
          echo "<option value='Y'>Yes</option>
          <option value='N'>No</option>";
            }else{
              echo "<option value='N'>No</option>
              <option value='Y'>Yes</option>";
          }
      ?>
      </select>	
       </div>
			</td>	

    <td>Credit Control Override</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="credit_control">

      <option value="Y" <?= $credit_control=='Y'?"selected":"";?> >Yes</option>
      <option value="N" <?= $credit_control=='N'?"selected":"";?> >No</option>
      </select>	
       </div>
			</td>		
      </tr>
      <tr>
     <td>Packing Charges</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="packing_charges">
      <option value="1" <?= ($packing_charges== True)?"selected":"";?>>Yes</option>
      <option value="0" <?= ($packing_charges==False)?"selected":"";?>>No</option>
      </select>	
       </div>
			</td>		
      <td>Insurance Charges</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="insurance_charges">
      <option value="1" <?=($insurance_charges== True)?"selected":"";?>>Yes</option>
      <option value="0" <?=($insurance_charges==False)?"selected":"";?>>No</option>
      </select>	
       </div>
			</td>		
      </tr>
      <tr>
    <td>Other Charges</td>
			<td>
      <div class="col-sm-9">
      <select class="form-control" name="other_charges">
      <option value="1" <?= ($other_charges==True)?"selected":"";?>>Yes</option>
      <option value="0" <?= ($other_charges==False)?"selected":"";?>>No</option>
      </select>	
       </div>
			</td>	
               <td>Business Volume</td>
			<td>
      <div class="col-sm-9">
       <input class="form-control border-form"  name="cust_bus_vol" type="text" id="cust_bus_vol" value="<?= $cust_bus_vol; ?>" >
      </div>
      </td>	
        </tr>
        <tr>
        <td>Upload ContractFile</td>
        <td>
        <div class="col-sm-9">
            <input type="file" class="form-control  input-sm" name="file" accept=".jpg,.pdf" onchange="ValidateSingleInput(this);" id="contract_files" data-max-size="1">
  
               </div>
          </td>
      <?php
            if($ratefile)
            {
            ?>
            <td>ContractFile</td>
            <td>
            <div class="col-sm-9">  
            <input class="form-control border-form" value="<?=$ratefile->file_name;?>" name="rate_contract_file_name"  id="rate_contract_file_name"  readonly>
            </div>
            <div class="col-sm-3">
            <button data-toggle="modal" data-target="#ratefile" class="btn btn-xs btn-primary" aria-controls="data-table" type="button"><i class="fa fa-folder-open" aria-hidden="true"></i></button> 
            <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#historyratefile" type="button" aria-controls="data-table"><i class="fa fa-info"  aria-hidden="true"></i></button> 
            </div>
          </td>
           
            <?php
             }
            ?>
      </tr>
      <tr>
      <td>Transaction Locking</td>
			<td>
      <div class="col-sm-9">
           
      <select class="form-control" name="tr_lock" id="tr_lock">
      <option value="1" <?= ($tr_lock==1)?"selected":"";?>>Yes</option>
      <option value="0" <?= ($tr_lock==0)?"selected":"";?>>No</option>
      </select>	
      </div>
			</td>	
      <td id="tr_dateheading">Transaction From - To Date</td>
			<td id="tr_datedvi">
      <div class="col-sm-9">
      <input class="form-control border-form"  name="transaction_date" type="text" id="transaction_date" value="<?=$tr_lock_date;?>" readonly>
  
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
        <input class="form-control border-form" value="<?= $cust_gstin; ?>" name="cust_gstin" type="text" id="cust_gstin" _pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" onkeyup="gst_val(this)">
        <p id="gst_val" class="form_valid_strings">Enter valid GSTIN.</p>
                </div>
      </td>
				
			<td>PAN No</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form" value="<?= $cust_pan; ?>" name="cust_pan" type="text" id="cust_pan"  >
                </div>
			</td>
                </tr><tr>
			<td>TIN No</td>
			<td >
      <div class="col-sm-9">
        <input class="form-control border-form" value="<?= $cust_tin; ?>"  name="cust_tin" type="text" id="cust_tin"  >
                </div> 
			</td>	
			
		
			<td>UIN No</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form" value="<?= $cust_uin; ?>" name="cust_uin" type="text" id="cust_uin"  >
                </div>
			</td>			
		</tr>
    <tr>		
			<td>Quote No</td>
			<td >
      <div class="col-sm-9">
        <input class="form-control border-form" value="<?= $quote_no; ?>" name="quote_no" type="text" id="quote_no"  >
      </td>
                </div>
        <td>Credit Limit</td>
			<td>
      <div class="col-sm-9">
        <input class="form-control border-form" value="<?= $credit_limit; ?>" name="credit_limit" type="text" id="credit_limit"  >
                </div>
      </td>	
      </tr>
        <tr>		
      <td>Track ID</td>
      <td>
      <div class="col-sm-9">
        <input class="form-control border-form" value="<?= $track_id; ?>" name="track_id" type="text" id="track_id"  >
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
<!-- Modal for Current Contract file-->
<div class="modal fade" id="ratefile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:40%">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Contract File of Customer - <?=$cust_code;?></h5>
                <button type="button" style="margin-top:-25px" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <?php 
            $endfilename = explode(".",$ratefile->file_name);
            $ext = end($endfilename);
            if($ext=='pdf'){
                echo '<embed style="width:100%;min-height:800px" src="data:application/pdf;base64,'. $ratefile->contract_files .'"/>'; 
            }
            else{
                echo '<embed style="width:100%;" src="data:image/jpg;base64,'. $ratefile->contract_files .'"/>'; 
            }
             ?>  <br>
                  <!-- <embed style="width:100%;" src="filesystem/ratedetailfiles/IMG_20200707_183209.jpg"> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
        <!-- End -->
<!-- Modal for History Contract file-->
<div class="modal fade" id="historyratefile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:80%">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Contract File History of Customer - <?=$cust_code;?></h5>
                <button type="button" style="margin-top:-25px" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="filelistwithprev" style="display: flex;">
            <div class="historylist" style="width:35%">
              <table  class="table table-bordered">
                <tr>
                  <th>S.No</th>
                  <th>Filename</th>
                  <th>DateAdded</th>
                  <th>Action</th>
                </tr>
            
            <?php 
            $sno=0;
            while($rowratefilehistory=$ratefilehistory->fetch(PDO::FETCH_OBJ)){
              $sno++;
              echo "<tr>
              <td>$sno</td>
              <td>$rowratefilehistory->file_name</td>
              <td>$rowratefilehistory->date_modified</td>
              <td><div class='hidden-sm hidden-xs action-buttons'>
              <a class='btn btn-primary btn-minier' data-rel='tooltip' id='$rowratefilehistory->id' onclick='previewhistorycontractfile(this.id)' title='View'>
                <i class='ace-icon fa fa-folder-open'></i>
              </a>
               <a class='btn btn-danger btn-minier bootbox-confirm' id=''>
                  <i class='ace-icon fa fa-trash-o bigger-130'></i>
               </a>
             </div>
             </td>
              </tr>";
            
            }
             ?>  
            </table>
            </div>
            <div class="filewindow table-bordered" style="margin-left:10px;width:60%;padding:10px">
              <h5>File Preview</h5>
              <div class="prevarea"></div>
            </div>
                  <!-- <embed style="width:100%;" src="filesystem/ratedetailfiles/IMG_20200707_183209.jpg"> -->
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
        <!-- End -->
<style>
	#POSCustErrorMsg .error{
	color : #FE2E64;
	}
</style>


						
					</section>

<!-- inline scripts related to this page -->
<script>
  $(document).on('focusout','#cust_pincode',function(){
    var pin_code=document.getElementById("cust_pincode");

    var pincodevalidate = /^[0-9]{0,6}$/;
    if(!pincodevalidate.test(pin_code.value))
     {
     swal("Pin code should be 6 digits ");
     return false;
     }
      console.log($(this).val());
  });
</script>

<script>
  $(document).on('change','#tr_lock',function(){
    var tr_lock_status = $(this).val();
    var stncode = $('#stncode').val();
    var cust_code = $('#cust_code').val();
    var user_name = $('#user_name').val();


    var current_date = add45Days(new Date(), 0);
    var deudate = add45Days(new Date(), 45);
    if(tr_lock_status.trim()==1){
        // $('#tr_dateheading').show();
        // $('#tr_datedvi').show();
        $('#transaction_date').val(current_date+" "+"To"+" "+deudate);
    }else{
      // $('#tr_dateheading').hide();
      // $('#tr_datedvi').hide();
      $('#transaction_date').val('0');

    }
    var tr_date = current_date+" "+"To"+" "+deudate;
    $.ajax({
          url:"<?php echo __ROOT__ ?>customeredit",
          method:"POST",
          async: false,
          data:{tr_lock_status,stncode,cust_code,tr_date,user_name,action : 'update_transaction_lock'},
          dataType:"text",
          success:function(res){
               
                   var data = JSON.parse(res);
                   console.log(data);
             }
                
        });
   
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

var newDate = add45Days(new Date(), 0);


           console.log(newDate);
</script>
<script>
$('#updatecust').click(function(){
          if($("#gst_val").css("display")==="block")
        {
          swal("Please enter valid GST Number");
            return false;
        }
        else{
	        $('#updatecust').attr('type','submit');
        }
  });
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
    function ratefact(){
    document.getElementById("rate_li").className = "active";
    document.getElementById("gen_li").className = "";
    document.getElementById("rate_a").setAttribute('data-toggle','tab');
}
function regulatory(){
    document.getElementById("reg_li").className = "active";
    document.getElementById("rate_li").className = "";
    document.getElementById("reg_a").setAttribute('data-toggle','tab');

}
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
      
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];
</script>
<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("zncode"), zones);
</script>
<script>
  var rateid =[<?php  
      
    $sql=$db->query("SELECT cust_code,cust_name FROM customer WHERE comp_code='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['cust_code']."-".$row['cust_name']."',";
    }

 ?>];

</script>
<script>

autocomplete(document.getElementById("rate"), rateid);
</script>
<script>
var execode =[<?php 
      
    $sql=$db->query("SELECT exe_code FROM staff WHERE comp_code='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['exe_code']."', ";
    }
 ?>];
</script>
<script>
autocomplete(document.getElementById("exe_code"), execode);
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
function previewhistorycontractfile(history_file_id){
  var hist_file_compcode = '<?=$stncode?>';
  console.log(hist_file_compcode);
  console.log(history_file_id);
  $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{history_file_id,hist_file_compcode},
            dataType:"text",
            success:function(data){
              $('.prevarea').html(data);
            }
          }); 
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
</script>