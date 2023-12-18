<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'branchdetail';
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

if (isset($_GET['brid'])) {
$branch_id = $_GET['brid'];
$sql = $db->query("SELECT * FROM branch WHERE brid = '$branch_id'");
$row = $sql->fetch();
$br_code             = $row['br_code'];
$br_name             = $row['br_name'];
$br_address          = $row['br_address'];
$br_city             = $row['br_city'];
$br_state            = $row['br_state'];
$br_country          = $row['br_country'];
$br_phone            = $row['br_phone'];
$br_mobile           = $row['br_mobile'];
$br_email            = $row['br_email'];
$br_fax              = $row['br_fax'];
$br_head             = $row['br_head'];
$br_tax              = $row['br_tax'];
$br_remarks          = $row['br_remarks'];
$br_active           = $row['br_active'];
$manifest            = $row['manifest'];
$exe_code            = $row['exe_code'];
$exe_name            = $row['exe_name'];
$role                = $row['role'];
$cate_code           = $row['cate_code'];
$brcate_code         = $row['brcate_code'];
$stncode             = $row['stncode'];
$transit_hub         = $row['transit_hub'];
$curinvno            = $row['curinvno'];
$curcolno            = $row['curcolno'];
$rate                = $row['rate'];
$podall              = $row['podall'];
$br_ncode            = $row['br_ncode'];
$origin              = $row['origin'];
$bkgstaff            = $row['bkgstaff'];
$proprietor          = $row['proprietor'];
$commission          = $row['commission'];
$pro_commission      = $row['pro_commission'];
$branchtype          = $row['branchtype'];
$intl_rate           = $row['intl_rate'];
$am                  = $row['am'];
$br_headcode         = $row['br_headcode'];
$stnname             = $row['stnname'];
$username  = $row['user_name'];
$virtual_edit  = $row['virtual_edit'];
$cc_enable  = $row['cc_enable'];
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
        $query = $db->query('SELECT * FROM brcategorytb ORDER BY brcid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->brcate_code.'">'.$row->brcate_code.'</option>';
         }
         return $output;    
        
    }
    function loadbrcategory($db,$stationcode,$brcate_code)
    {
        $output='';
        $query = $db->query("SELECT * FROM brcategory WHERE comp_code='$stationcode' ORDER BY brcid ASC");
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            if($brcate_code==$row->cate_code){
                $selected = 'Selected';
            }
            else{
                $selected = '';
            } 
        //menu list in home page using this code    
        $output .='<option '.$selected.' value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
         }
         return $output;    
        
    }
?>
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


<div id="col-form1" class="col_form lngPage col-sm-25">
<!-- Bread Crumb -->
 <div class="au-breadcrumb m-t-75" style="margin-bottom:-5px;">
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
 
 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Branch Detail</p>
          
    <div class="align-right" style="margin-top:-35px;">

                  
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
            <a id="gen_a" data-toggle="tab" href="#general" >General</a>
        </li>
        <li id="rate_li">
            <a id="rate_a" data-toggle="tab" href="#ratefactor"> Rate Factor</a>
        </li>
        <li id="off_li">
            <a id="off_a" data-toggle="tab" href="#office">Office</a>
        </li>
      </ul>
   <div class="tab_form" >

  
	    
    <div class="tab-content">
  	    <!-- Tab Form 1 -->

     <div id="general" class="tab-pane active" style="margin-left: 25px;">
     <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	    	<tbody>
        <tr>		
            <td>Station Code</td>
            <td>
                <div class="col-sm-9">
                   <input placeholder="" style="background: ;" value="<?= $stncode; ?>" class="form-control border-form" name="stncode" type="text" id="stncode" readonly>
                </div>
            </td>
              
            <td>Station Name</td>
            <td>
                <div class="col-sm-9">
                      <input class="form-control border-form "  name="stnname" value="<?= $stnname; ?>" type="text" id="stnname" placeholder="Station Name" readonly>
                </div>
            </td>
       </tr>
       <tr>
            <td>Branch Code</td>
            <td >
                <div class="col-sm-9">
                       <input placeholder="" style="background: khaki;" value="<?= $br_code; ?>" class="form-control border-form" name="br_code" type="text" id="br_code" readonly>
                </div> 
            </td>	
            <td>Branch Name</td>
            <td>
                <div class="col-sm-9">
                     <input class="form-control border-form " value="<?= $br_name; ?>" name="br_name" type="text" id="br_name" placeholder="Branch Name" readonly>
                </div>
            </td>			
		</tr>
        <tr>		
		  	<td>People Code</td>
        <td>
        <div class="col-sm-9 autocomplete">
         <input class="form-control border-form" value="<?= $exe_code; ?>" name="exe_code" type="text" id="exe_code" readonly>
         </div>
         </td>
               
        <td>People Name</td>
		  	<td>
            <div class="col-sm-7">
            <input class="form-control border-form" value="<?= $exe_name; ?>" name="exe_name" type="text" id="exe_name" readonly >
            </div>
            <div class="col-sm-2">
            <input class="form-control border-form" value="<?= $role; ?>" name="role" type="text" id="role" readonly >
            </div>
        </td>	
    </tr>
   
    <tr>	
       	
			  <td>Address</td>
			  <td>
               <div class="col-sm-9">
                     <input class="form-control border-form" value="<?= $br_address; ?>" name="br_address" type="text" id="br_address" placeholder="Address" readonly>
                </div>
        </td>
        <td>city</td>
			  <td>
               <div class="col-sm-9">
                     <input class="form-control border-form" value="<?= $br_city; ?>"  type="text" id="br_city"  readonly >
                </div>
              </td>
    </tr>
    <tr>	
        	  <td>State</td>
			  <td>
               <div class="col-sm-9">
                     <input class="form-control border-form" value="<?= $br_state; ?>" type="text" id="br_state" readonly  >
                </div>
              </td>
              <td>Country</td>
			  <td>
               <div class="col-sm-9">
                     <input class="form-control border-form" value="<?= $br_country; ?>"  type="text" id="br_country" readonly >
                </div>
              </td>
    </tr>
    <tr>
        <td>Branch Group</td>
            <td >
                <div class="col-sm-9">
                        <input type="text" class="form-control" name="cate_code" value="<?= $cate_code; ?>" readonly>
                  </div>
            </td>

            <td>Branch type</td>
            <td >
                <div class="col-sm-9">
                        <input type="text" class="form-control" name="branchtype" value="<?= $branchtype; ?>" readonly>
                  </div>
            </td>

           
                  
           
    </tr>
    <tr>
        <td>Branch Category</td>
            <td >
                <div class="col-sm-9">
                    <select class="form-control" name="brcate_code" readonly>
                    <option value="0">Select</option>
                   <?php echo loadbrcategory($db,$stationcode,$brcate_code); ?>
                    </select>                
                  </div>
            </td>
            <td>Origin</td>
			  <td>
            <div class="col-sm-9">
               <input  class="form-control border-form" value="<?= $origin; ?>" name="origin" type="text" id="origin" readonly>
            </div>
			  </td> 
		  	
    </tr>
    <tr>
        <td>Mobile</td>
			  <td>
              <div class="col-sm-9">
                   <input  class="form-control border-form" value="<?= $br_mobile; ?>" name="br_mobile" type="text" id="br_mobile"  readonly>
              </div>
        </td>
        <td>Phone</td>
			  <td>
              <div class="col-sm-9">
                  <input  class="form-control border-form" value="<?= $br_phone; ?>" name="br_phone" type="text" id="br_phone"  readonly>
              </div>
        </td>
    </tr>
    <tr>	
		  	<td>Fax</td>
		  	<td>
            <div class="col-sm-9">
                 <input  class="form-control border-form" value="<?= $br_fax; ?>" name="br_fax" type="text" id="br_fax"  readonly>
            </div>  
        </td>
			
		  	<td>Email</td>
		  	<td>
             <div class="col-sm-9">
                  <input  class="form-control border-form" value="<?= $br_email; ?>" name="br_email" type="text" id="br_email" placeholder="" readonly>
             </div>
		  	</td>	
			
    </tr>
    <tr>	
		  	<td>Transit Hub</td>
		  	<td>
            <div class="col-sm-9">
                 <input  class="form-control border-form" value="<?= $transit_hub; ?>" name="transit_hub" type="text" id="transit_hub" placeholder="" readonly>
            </div>  
         </td>
         <td>Active</td>
          <td>
              <div class="col-sm-9">
                      <input type="text" class="form-control" name="br_active" value="<?= $br_active; ?>" readonly>	
              </div>	
        </td>
    </tr>
    <tr>	
        
		<td>Remarks</td>
		  	<td>
            <div class="col-sm-9">
                  <input type="text" class="form-control border-form" value="<?= $br_remarks; ?>" rows="1" name="br_remarks" cols="50" id="br_remarks" readonly>
            </div>
		  	</td>	
              <td>Virtual Edit</td>
          <td>
              <div class="col-sm-9">
              <input type="text" class="form-control border-form" value="<?=$virtual_edit;?>" rows="1" name="virtual_edit" cols="50" id="virtual_edit" readonly>	   
              </div>	
    </tr>
    <tr>	
      
	
      <td>CC Enable</td>
  <td>
  <div class="col-sm-9">
                      <input type="text" class="form-control" name="cc_enable" value="<?= $cc_enable; ?>" readonly>	
              </div>
    
</tr>
   </tbody>
  </table>
 <div class="align-right">
<button type="button" class="btn btn-primary" data-toggle="tab" href="#ratefactor" onclick="ratefact()" >
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
                      <input type="text" class="form-control" name="br_tax" value="<?= $br_tax; ?>" readonly>               
              </div>
          </td>
            
          <td>Current Invoice</td>
          <td>
              <div class="col-sm-9">
                 <input  class="form-control border-form" value="<?= $curinvno; ?>" name="curinvno" type="text" id="curinvno" readonly >
              </div>
          </td>
                    </tr><tr>
          <td>Current Collection</td>
          <td >
              <div class="col-sm-9">
                 <input  class="form-control border-form" value="<?= $curcolno; ?>" name="curcolno" type="text" id="curcolno" readonly >
              </div> 
          </td>	
          
        
          <td>Rate</td>
          <td>
              <div class="col-sm-9">
                   <input  class="form-control border-form" value="<?= $rate; ?>" name="rate" type="text" id="rate" readonly >
              </div>
          </td>			
		</tr>
    <tr>		
          <td>Intl Rate</td>
          <td >
              <div class="col-sm-9">
                  <input  class="form-control border-form" value="<?= $intl_rate; ?>" name="intl_rate" type="text" id="intl_rate" readonly >
              </div>
          </td>
          <td>Branch Ncode</td>
          <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" value="<?= $br_ncode; ?>" name="br_ncode" type="text" id="br_ncode" readonly >
              </div>
          </td>
         
    </tr>
    <tr>		
          
          
          <td>
              <div class="col-sm-9">
                   <input  class="form-control border-form" value="<?= $podall; ?>" name="podall" type="hidden" id="podall" readonly  >
              </div>
          </td>	
    </tr>
     
   </tbody>
   </table>
   <div class="align-right">
   <button type="button" class="btn btn-primary" data-toggle="tab" href="#office" onclick="offz()" style="background-color:#3E01A4;">
    Next  &#8594;
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
                      <input type="text" class="form-control" name="manifest" id="manifest" value="<?= $manifest; ?>" readonly>                
                </div>
          </td>
				
          <td>Bkg Staff</td>
          <td>
              <div class="col-sm-9">
                      <input type="text" class="form-control" name="bkgstaff" id="bkgstaff" value="<?= $bkgstaff; ?>" readonly>              
           </div>
          </td>
      </tr>
      <tr>
		    	<td>Proprietor</td>
		    	<td >
             <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $proprietor; ?>" name="proprietor" type="text" id="proprietor" readonly>
             </div> 
		    	</td>	
	
		    	<td>Commission</td>
	    		<td>
             <div class="col-sm-9">
                 <input class="form-control border-form" value="<?= $commission; ?>" name="commission" type="text" id="commission" readonly>
             </div>
		    	</td>			
	  	</tr>
       <tr>		
		    	<td>PRO Commission</td>
          <td >
              <div class="col-sm-9">
                  <input class="form-control border-form" value="<?= $pro_commission; ?>" name="pro_commission" type="text" id="pro_commission" readonly>
              </div>
          </td>
                
         <td>Area Manager</td>
          <td>
              <div class="col-sm-9">
                   <input class="form-control border-form" value="<?= $am; ?>" name="am" type="text" id="am" readonly>
              </div>
          </td>	
      </tr>
      
  </tbody>             
</table>
    </div>       
	</div>
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
function resetFunction() {
  document.getElementById("formbranch").reset();
}
</script>

