<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

//if user is not logged in, they cannot access this page
	
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 




$user = $_SESSION['user_name'];

    

    function authorize($db,$user)
{
    
    $module = 'bookingcounterdetail';
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

if (isset($_GET['bkcid'])) {
$bkcid_id = $_GET['bkcid'];
$sql = $db->query("SELECT * FROM bcounter WHERE bkcid = '$bkcid_id'");
$row = $sql->fetch();
$bkcid              = $row['bkcid'];
$stncode            = $row['stncode'];
$stnname            = $row['stnname'];
$br_code            = $row['br_code'];
$br_name            = $row['br_name'];
$book_code          = $row['book_code'];
$book_name          = $row['book_name'];
$book_addr          = $row['book_addr'];
$book_city 		     	= $row['book_city']; 
$book_state 		   	= $row['book_state'];
$book_country 		 	= $row['book_country'];
$book_phone         = $row['book_phone'];
$book_fax           = $row['book_fax'];
$book_email         = $row['book_email'];
$c_person           = $row['c_person'];
$book_remarks       = $row['book_remarks'];
$book_active        = $row['book_active'];
$proprietor         = $row['proprietor'];
$book_gstin         = $row['book_gstin'];
$book_tin           = $row['book_tin'];
$book_fsc           = $row['book_fsc'];
$book_deposit       = $row['book_deposit'];
$cnote_charges      = $row['cnote_charges'];
$percent_domscomsn  = $row['percent_domscomsn'];
$percent_intlcomsn  = $row['percent_intlcomsn'];
$pro_commission     = $row['pro_commission'];
$spl_discount       = $row['spl_discount'];
$deposit_date       = $row['deposit_date'];
$username  = $row['user_name'];
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
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'bookingcounterlist'; ?>">Booking Counter list</a>
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
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  Booking Counter Added successfully.
                                </div>";
                  }

                   ?>

             </div>

<form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>bookingcounter" accept-charset="UTF-8" class="form-horizontal" id="formcompany" enctype="multipart/form-data">
<input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
<input  class="form-control border-form" name="bkcid" type="text" id="bkcid" value="<?= $bkcid; ?>" style="display:none;">

 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Booking Counter Form</p>
          
    <div class="align-right" style="margin-top:-35px;">
      
       <a style="color:white" href="<?php echo __ROOT__ . 'bookingcounterlist'; ?>">          
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
            <a id="reg_a" data-toggle="tab" href="#regulatory" >Regulatory</a>
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
                <input  value="<?= $br_code; ?>" class="form-control border-form br_code" name="br_code" type="text" id="br_code" readonly >
                    
                </div>
            </td>
              
            <td>Branch Name</td>
            <td>
                <div class="col-md-9">
                <input class="form-control border-form " value="<?= $br_name; ?>" name="br_name" type="text" id="br_name"  readonly>
                       
                </div>
            </td>
       </tr>
      <tr>	
      <tr>		
            <td>Counter Code</td>
            <td >
                <div class="col-sm-9 autocomplete">
                <input class="form-control border-form" value="<?= $book_code; ?>" name="book_code" type="text" id="book_code" readonly>
                </div>
            </td>
              
            <td>Counter Name</td>
            <td>
                <div class="col-md-9">
                <input class="form-control border-form " value="<?= $book_name; ?>" name="book_name" type="text" id="book_name" readonly >       
                </div>
            </td>
       </tr>
      <tr>				
			  <td>Address</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $book_addr; ?>"  name="book_addr" type="text" id="book_addr" readonly  >
                   
                </div>
             </td>
             <td>City</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $book_city; ?>" name="book_city" type="text" id="book_city" readonly>
                </div>
             </td>
                </tr>
    <tr>
    <tr>			
			  <td>State</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $book_state; ?>" name="book_state" type="text" id="book_state" readonly>
                   
                </div>
             </td>
             <td>Country</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form" value="<?= $book_country; ?>" name="book_country" type="text" id="book_country" readonly>
                 
                </div>
             </td>
                </tr>

    <tr>
        <td>Phone</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form" value="<?= $book_phone; ?>" name="book_phone" type="text" id="book_phone" readonly>
                   
                   
              </div>
        </td>
        <td>Fax</td>
		  	<td>
            <div class="col-sm-9">
                 <input  class="form-control border-form" value="<?= $book_fax; ?>" name="book_fax" type="text" id="book_fax" readonly >
                    
            </div>  
        </td>
    </tr>
    <tr>	
		  
			
		  	<td>Email</td>
		  	<td>
             <div class="col-sm-9">
                  <input  class="form-control border-form" value="<?= $book_email; ?>"  name="book_email" type="text" id="book_email" readonly >
                 
                               
             </div>
		  	</td>	
              <td>Contact Person</td>
		  	<td>
            <div class="col-sm-9">
            <input  class="form-control border-form" value="<?= $c_person; ?>" name="c_person" type="text" id="c_person" readonly >      
            </div>  
        </td>
    </tr>
    <tr>	
		  
			
        <td>Active</td>
		  	<td>
            <div class="col-sm-9">
                <select class="form-control" name="book_active" id="book_active" >
                      <option value="Yes" selected="selected">Yes</option>
                      <option value="No">No</option>
                    </select> 
                </div>            
         </td>
         <td>Remarks</td>
		  	<td>
            <div class="col-sm-9">
            <input  class="form-control border-form" rows="1" value="<?= $book_remarks; ?>" name="book_remarks" cols="50" id="book_remarks" readonly>     
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
              <input  class="form-control border-form" value="<?= $proprietor; ?>" name="proprietor" type="text" id="proprietor" readonly >  
              </div>
          </td>
            
          <td>GSTIN</td>
          <td>
              <div class="col-sm-9">
                <input class="form-control border-form" value="<?= $book_gstin; ?>"  name="book_gstin" type="text" id="book_gstin" readonly>	
                                                 
                </div>
          </td>
                    </tr><tr>
          <td>TIN</td>
          <td>
              <div class="col-sm-9">
              <input class="form-control border-form " value="<?= $book_tin; ?>" name="book_tin" type="text" id="book_tin" readonly >
              </div> 
          </td>	

           <td>FSC</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $book_fsc; ?>" name="book_fsc" type="text" id="book_fsc" readonly >
              </div> 
          </td>	
        
            </tr>
            <tr>
            <td>Deposit</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $book_deposit; ?>" name="book_deposit" type="text" id="book_deposit" readonly >
              </div>
          </td>	
          <td>C-Note Charge</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $cnote_charges; ?>" name="cnote_charges" type="text" id="cnote_charges" readonly >
              </div>
          </td>	
                </tr>

                <tr>
            <td>Commission % D</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $percent_domscomsn; ?>" name="percent_domscomsn" type="text" id="percent_domscomsn" readonly >
              </div>
          </td>	
          <td>Commission % I</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $percent_intlcomsn; ?>" name="percent_intlcomsn" type="text" id="percent_intlcomsn" readonly >
      </div>
              </div>
          </td>	
                </tr>

                <tr>
            <td>PRO Commission</td>
          <td>
              <div class="col-sm-9">
              <input class="form-control border-form " value="<?= $pro_commission; ?>" name="pro_commission" type="text" id="pro_commission" readonly>
              </div>
          </td>	
          <td>Special Discount</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $spl_discount; ?>" name="spl_discount" type="text" id="spl_discount"  readonly>
           </div>
              </div>
          </td>	
                </tr>

           <tr>
            <td>Deposit Date</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" value="<?= $deposit_date; ?>" name="deposit_date" type="date" id="deposit_date" readonly>
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






