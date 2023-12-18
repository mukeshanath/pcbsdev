<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
  
    $module = 'creditnote';
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

function loadmode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM mode ORDER BY mid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->mode_name.'">'.$row->mode_name.'</option>';
         }
         return $output;    
        
    }

    if(isset($_GET['crn_no'])){
         $crnno = $_GET['crn_no'];
         $crn_data = $db->query("SELECT * FROM credit_note WHERE comp_code='$stationcode' AND crn_no='$crnno'")->fetch(PDO::FETCH_OBJ);
         $fetchbrname = $db->query("SELECT br_name FROM branch WHERE br_code='$crn_data->br_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
         $br_name     = $fetchbrname->br_name;
         $fetchcustomer = $db->query("SELECT cust_name FROM customer WHERE cust_code='$crn_data->cust_code' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
         $fetchinvoice = $db->query("SELECT (netamt-rcvdamt) as balance FROM invoice WHERE invno='$crn_data->invno' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
    }

?>
<style>
  
</style>

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
                                        <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                        </li>
                                        
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'creditnotelist'; ?>">Credit note</a>
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
                                  Collection Record Added successfully.
                  </div>";
                  }

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 1  )
                  {
                       echo 
                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Please Check form Errors.
                  </div>";
                  }

               ?>

             </div>
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Note Edit</p>
        <div class="align-right" style="margin-top:-35px;">

        <a style="color:white" href="<?php echo __ROOT__ ?>creditnotelist"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>

          </div>
        </div> 
        
        <div class="content panel-body" >
        <div class="tab_form" style="background:#fff;border: 1px solid var(--heading-primary);">

        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>
  
            <td>Company</td>
            <td style="width:100px;">
                <input placeholder="" class="form-control stncode" name="comp_code" type="text"  id="stncode" readonly>
            </td>
            <td>
                <input class="form-control border-form "  name="comp_name" type="text" id="stnname" readonly>
            </td>
            <td>Branch</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" class="form-control br_code" name="br_code" type="text"  id="br_code" onkeyup="branchvalidation();fetchcustcode()" value="<?=$crn_data->br_code?>" readonly>
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form "  name="br_name" type="text" id="br_name" value="<?=$br_name?>" readonly></div>
            </td>
            <td>Customer</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" class="form-control cust_code" name="cust_code" type="text"  id="cust_code" onkeyup="customervalidation()" value="<?=$crn_data->cust_code?>" readonly>
                <p id="custcode_validation" class="form_valid_strings">Enter Valid Customer Code</p>
                </div>
            </td>
            <td> <div class="autocomplete col-sm-12">
                <input class="form-control border-form "  name="cust_name" type="text" id="cust_name"  value="<?=$fetchcustomer->cust_name?>" readonly></div>
            </td>
            
        </tr>
                      
        </tbody>
        </table>
        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>
  
                        
            <td>Credit Note No</td>
            <td>
                <input placeholder="" class="form-control " name="credit_note_no" type="text"  id="credit_note_no" value="<?=$crn_data->crn_no?>" readonly>
            </td>
            <td>Credit Note Date</td>
            <td>
                <input placeholder="" class="form-control today" name="credit_note_date" type="date"  id="credit_note_date" value="<?=$crn_data->date_added?>" readonly>
            </td>
            
            
        </tr>
        <tr>
  
                        
        <td>Invoice No</td>
        <td>
            <input placeholder="" class="form-control " name="invno" type="text"  id="invno" value="<?=$crn_data->invno?>" readonly>
        </td>
        <td>Invoice Date</td>
        <td>
            <input placeholder="" class="form-control" name="invdate" type="date"  id="invdate" value="<?=$crn_data->invdate?>" readonly>
        </td>
        
        
        </tr>
        <tr>
        
                                
        <td>Invoice Amount</td>
        <td>
            <input placeholder="" class="form-control " name="invamt" type="text"  id="invamt" value="<?=$crn_data->grand_amt?>" readonly>
        </td>
        <td>Balance</td>
        <td>
            <input placeholder="" class="form-control " name="balamt" type="text"  id="balamt" value="<?=number_format($fetchinvoice->balance,2)?>" readonly>
        </td>
        
        
        </tr>
        
       
                      
        </tbody>
        </table>
        </div>

        <!-- collection entry 2 form -->
        
            <div  class="col-sm-12" style="border :1px solid var(--heading-primary) ;background:#fff;margin-top:5px">
          
            <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            
        <tr>
        
                                
        <td style="width: 19.7%;">Credit Note Amount</td>
        <td style="width: 29.3%;">
            <input  class="form-control " name="totamt" type="text"  id="totamt"  value="<?=$crn_data->net_amt?>" readonly/>
        </td>
      
        <td >Remarks</td>
          <td>
          <select class="form-control" name="remarks">
                    <option value="BAD">Bad Debts</option>
                    <option value="CAS">Cash Discount</option>
                    <option value="CLA">Claim</option>
                    <option value="CRE">Credit Note</option>
                    <option value="DEL">Delivery Charge</option>
                    <option value="IAT">Invoice Adjustment</option>
                    <option value="LAT">Late Delivery Claim</option>
                    <option value="OTC">OTC Charge</option>
                    <option value="OTH">Other Charges</option>
                    <option value="SEC">Security</option>
                    
                    </select>    
          </td>
        
        
        </tr>
            </tbody>
            </table>
            
            </div>

               <!-- Fetching Mode Details in Table  -->
               <div class="col-sm-12 data-table-div" >

<div class="title_1 title panel-heading">
<p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Invoices List</p>
<div class="align-right" style="margin-top:-33px;" id="buttons"></div>
</div>
<table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
<thead><tr class="form_cat_head">
<th class="center">
<label class="pos-rel">
   <input type="checkbox" class="ace" />
<span class="lbl"></span>
</label>
</th>
<th>Invoice No</th>
<th>Invoice Date</th>
<th>Branch Code</th>
<th>Customer Code</th>
<th>Grand Amt</th>     
<th>FSC</th>
<th>Invoice Amount</th>
<th>GST</th>
<th>Net Amt</th>
<th>Status</th>
</tr>
</thead>
<tbody id="collectioninvtb">

</tbody>
</table>
</div>           
          </div> 
         

  </div>
  </div>
</div>
</div>
</section>

<!-- inline scripts related to this page -->
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