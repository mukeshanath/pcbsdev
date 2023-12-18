<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'collection';
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
        $output .='<option  value="'.$row->mode_name.'">'.$row->mode_name.'</option>';
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


      //branch filter conditions
  $sessionbr_code = $datafetch['br_code'];
  if(!empty($sessionbr_code)){
      $br_code = $sessionbr_code;
      $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
      $br_name     = $fetchbrname->br_name;
      }
   //branch filter conditions

    function colmasno($db,$stationcode)
    {
    
    $prevcolmasno=$db->query("SELECT TOP 1 col_mas_rcptno FROM collection_master WHERE comp_code='$stationcode' ORDER BY col_mas_id DESC");
    if($prevcolmasno->rowCount()== 0)
        {
        $colomasno = $stationcode.str_pad('1', 7, "0", STR_PAD_LEFT);
        }
    else{
        $prevcolmasrow=$prevcolmasno->fetch(PDO::FETCH_ASSOC);
        $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$prevcolmasrow['col_mas_rcptno']);
        $arr[0];//PO
        $arr[1]+1;//numbers
        $colomasno = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
        }
     return $colomasno;    
        
    }

    function collectionno($db,$stationcode)
    {
        $prevcolno=$db->query("SELECT TOP 1 colno FROM collection WHERE comp_code='$stationcode' ORDER BY col_id DESC");
         if($prevcolno->rowCount()== 0){
            $colono = 10001;
            }
        else{
            $prevcolrow=$prevcolno->fetch(PDO::FETCH_ASSOC);
            $colono = $prevcolrow['colno']+1;
            }
        return $colono;        
    }

    if(isset($_GET['invno']))
      {
        $invoicenumber = $_GET['invno'];
        $collection = $db->query("SELECT TOP(1) * FROM collection WHERE invno='$invoicenumber' ORDER BY coldate DESC,col_id DESC");
        $row = $collection->fetch(PDO::FETCH_OBJ);

        $getrunbal = $db->query("SELECT TOP(1) totamt,running_balance FROM collection_master WHERE cust_code='$row->cust_code' ORDER BY col_mas_id DESC");
        $rowrunbal = $getrunbal->fetch(PDO::FETCH_OBJ);

        $getecptpayref = $db->query("SELECT TOP(1) rcptno,chqddno FROM collection_master WHERE cust_code='$row->cust_code' ORDER BY col_mas_id DESC")->fetch(PDO::FETCH_OBJ);

        $br_code = $row->br_code;
      }
?>
<style>
   .swal-button--cancel{
    display:none;
}
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
                                        <a href="<?php echo __ROOT__ . 'collectionlist'; ?>">Collection List</a>
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

                  if ( isset($_GET['unsuccess'])  )
                  {
                       echo 
                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Please Check form Errors. <strong>".$_GET['unsuccess']."</strong>
                  </div>";
                  }

               ?>

             </div>
      <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>collection" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Collection Entry Form</p>
        <div class="align-right" style="margin-top:-35px;">

        <a style="color:white" href="<?php echo __ROOT__ ?>collectionlist"><button type="button" class="btn btn-danger btnattr" >
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
                <input placeholder="" class="form-control br_code" name="br_code" readonly type="text"  id="br_code" value="<?=(!empty($br_code))?$br_code:''; ?>" onkeyup="fetchcustcode();branchvalidation()">
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form "  name="br_name" readonly type="text" id="br_name" value="<?=(!empty($br_name))?$br_name:''; ?>">
            </div></td>
            <td>Customer</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" class="form-control cust_code" readonly name="cust_code" type="text"  id="cust_code" value="<?php if(isset($_GET['invno'])){echo $row->cust_code; } ?>" onkeyup="customervalidation()">
                <p id="custcode_validation" class="form_valid_strings">Enter Valid Customer Code</p>
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form" readonly name="cust_name" type="text" id="cust_name" value="<?php if(isset($_GET['invno'])){echo $row->cust_name; } ?>" >
            </div></td>
            
        </tr>
                      
        </tbody>
        </table>
        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>
        <td>Collection Mas No</td>
          <td>
              <input placeholder="" class="form-control " readonly name="col_mas_rcptno" type="text" value="<?php echo colmasno($db,$stationcode); ?>" id="col_mas_rcptno" >
          </td>
        
          <td>Collection No</td>
          <td>
              <input placeholder="" class="form-control" readonly name="col_no" type="text" value="<?php echo collectionno($db,$stationcode); ?>" id="col_no" >
          </td>
          <td>Collection Date</td>
            <td >
                <input placeholder="" class="form-control today" name="coldate" type="date"  id="coldate" >
            </td>
            <td>Receipt No</td>
            <td >
                <input placeholder="" class="form-control " name="rcptno" type="text"  id="rcptno" value="<?php if(isset($_GET['invno'])){  if($rowrunbal->running_balance=='0'){}else{ echo $getecptpayref->rcptno; } } ?>">
            </td>

        </tr>
        
        <tr>
        <td>Payment Mode</td>
          <td >
          <select class="form-control" name="cmode">
                    <option value="B">Cheque</option>
                    <option value="F">Cash</option>
                    <option value="D">DD</option>
                    <option value="N">NEFT</option>
                    </select>    
          </td>
           
          <td>Payment Reference</td>
            <td>
                <input placeholder="" class="form-control " name="chqddno" type="text"  id="chqddno" value="<?php if(isset($_GET['invno'])){ if($rowrunbal->running_balance=='0'){}else{echo $getecptpayref->chqddno; } }?>">
          </td>

          <td>Payment Date</td>
            <td >
                <input placeholder="" class="form-control today " name="chdt" type="date"  id="chdt">
         </td>
           
            <td>Total Amount</td>
            <td>
                <input placeholder="" class="form-control" readonly min="0" oninput="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"  name="totamt" type="text"  id="totamt" value="<?php if(isset($_GET['invno'])){ if($rowrunbal->running_balance=='0'){}else{echo $rowrunbal->totamt;} } ?>" onkeyup="$('#run_bal').val($(this).val()); if(parseInt($('#balamt').val()) > parseInt($('#run_bal').val())){   $('#colamt').val($('#run_bal').val());   } else{ $('#colamt').val($('#balamt').val());   }">
            </td>
            
        </tr>
        <tr>
  
            <td>Customer Bank</td>
            <td >
                <input placeholder="" class="form-control " name="chqbank" type="text"  id="chqbank">
            </td>
            <td>Company Bank</td>
            <td>
                <input placeholder="" class="form-control " name="depbank" type="text"  id="depbank">
            </td>
            <td>Sheet No</td>
            <td>
                <input placeholder="" class="form-control " name="sheetno" type="text"  id="sheetno">
            </td>
            <td>Running Balance</td>
            <td>
                <input placeholder="" class="form-control " name="run_bal" readonly type="text"  id="run_bal" value="<?php if(isset($_GET['invno'])){if($rowrunbal->running_balance=='0'){}else{ echo $rowrunbal->running_balance;} } ?>">
            </td>
            
        </tr>
                      
        </tbody>
        </table>
        </div>

        <!-- collection entry 2 form -->
        
            <div  class="col-sm-12" style="border :1px solid var(--heading-primary) ;background:#fff;margin-top:5px">
          
            <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
            <tr>
  
            <td>Invoice No</td>
            <td >
                <input placeholder="" class="form-control text-uppercase" name="invno" type="text"  id="invno" value="<?php if(isset($_GET['invno'])){echo $row->invno; } ?>">
            </td>

            <td>Invoice Date</td>
            <td >
                <input placeholder="" class="form-control today" readonly name="invdate" type="date"  id="invdate" value="<?php if(isset($_GET['invno'])){echo $row->invdate; } ?>">
            </td>
            
            <td>Invoice Amount</td>
            <td>
                <input placeholder="" readonly class="form-control " name="invamt" type="text"  id="invamt" value="<?php if(isset($_GET['invno'])){echo $row->invamt;} ?>">
            </td>
            <td>CRN No</td>
            <td>
                <input class="form-control " name="crn_no" type="text"  id="crn_no" value="<?php if(isset($_GET['invno'])){  } ?>">
            </td>
            
            
            </tr>
            <tr>
            <td>Collected Amount</td>
            <td >
              <input  class="form-control " name="colamt" type="text"  id="colamt" value="<?php if(isset($_GET['invno'])){ if($rowrunbal->running_balance=='0'){}else{echo $rowrunbal->totamt;} } ?>">
            </td>
                   
            <td>TDS</td>
          <td>
              <input class="form-control " name="tds" type="number"  id="tds" min="0"  step="0.01">
          </td>
          <td>Invoice Balance</td>
            <td>
                <input placeholder="" readonly class="form-control " name="balamt" type="text"  id="balamt" value="<?php if(isset($_GET['invno'])){echo number_format($row->balamt,2); } ?>">
            </td>
          <td>CRN Amount</td>
            <td>
                <input placeholder="" class="form-control " name="crn_bal" type="text"  id="crn_bal" value="<?php if(isset($_GET['invno'])){ } ?>">
            </td>
          </tr>
          <tr>

          <td>Deduction Type</td>
          <td>
          <select class="form-control" name="remarks">
                    <option value="">Select Type</option>
                    <option value="BAD">Bad Debts</option>
                    <option value="CAS">Cash Discount</option>
                    <option value="CLA">Claim</option>
                    <option value="CRE">Credit Note</option>
                    <option value="DEL">Delivery Charge</option>
                    <option value="IAT">Invoice Adjustment</option>
                    <option value="DEL">POD Deduction</option>
                    <option value="LAT">Late Delivery Claim</option>
                    <option value="OTC">OTC Charge</option>
                    <option value="OTH">Other Charges</option>
                    <option value="SEC">Security</option>
                    <option value="SEC">Wrong Bill</option>
                    </select>    
          </td>
          <td>Deduction Amount</td>
          <td>
              <input placeholder="" class="form-control " name="reduction" type="number"  step="0.01"  id="reduction" min="0" >
          </td>
          <td>Deduction Detail</td>
          <td>
              <input placeholder="" class="form-control " name="narration" type="text"  id="narration" >
          </td>
 
     
            </tr>
            </tbody>
            </table>
            <table class="table">
              <tr>	           
              <div class="align-right btnstyle" >

                  <button type="button" class="btn btn-success btnattr_cmn" onclick="addancollection1()" id="addancollection">
                  <i class="fa fa-save"></i>&nbsp; Save & Add
                  </button>

                  <button type="button" class="btn btn-primary btnattr_cmn" onclick="addcollection1()" id="addcollection" >
                  <i class="fa fa-save"></i>&nbsp; Save
                  </button>

                  <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                  <i class="fa fa-eraser"></i>&nbsp; Reset
                  </button> 

                  </div>

              </tr>

          </table>

            
            </div>

            
          </div> 
          <div class="col-sm-12 data-table-div" >

<div class="title_1 title panel-heading">
<p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice Review</p>
<div class="align-right" style="margin-top:-33px;" id="buttons"></div>
</div>
<table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
<thead><tr class="form_cat_head">
<th class="center">
<label class="pos-rel">
   <input type="radio" class="ace" />
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
 <?php 
            if(isset($_GET['invno'])){

            $getpendinginv = $db->query("SELECT * FROM invoice WHERE cust_code='$row->cust_code' AND status='Pending'");
            
            while($rowinv = $getpendinginv->fetch(PDO::FETCH_OBJ)){
            echo "<tr>";
            echo "<td class='center first-child'>
                    <label>
                        <input type='radio' name='chkIds[]' value='$rowinv->invno' class='ace invoiceradio'/>
                        <span class='lbl'></span>
                        </label>
                    </td>";
            
                    echo "<td>".$rowinv->invno."</td>";
                    echo "<td>".$rowinv->invdate."</td>";     
                    echo "<td>".$rowinv->br_code."</td>";
                    echo "<td>".$rowinv->cust_code."</td>";
                    echo "<td>".number_format($rowinv->grandamt,2)."</td>";
                    echo "<td>".number_format($rowinv->fsc,2)."</td>";
                    echo "<td>".number_format($rowinv->invamt,2)."</td>";
                    echo "<td>".number_format($rowinv->STax,2)."</td>";
                    echo "<td>".number_format($rowinv->netamt,2)."</td>";
                    echo "<td>".$rowinv->status."</td>";
            echo "</tr>";
            }
        }
     ?>

  
</tbody>
</table>
</div>
          <div class="col-sm-12 data-table-div" >

<div class="title_1 title panel-heading">
<p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Collection Review</p>
<div class="align-right" style="margin-top:-33px;" id="buttons"></div>
</div>
<table id="collectiontbl" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
<thead><tr class="form_cat_head">
<th class="center">
<label class="pos-rel">
   <input type="checkbox" class="ace" />
<span class="lbl"></span>
</label>
</th>
<th>Collection No</th>
<th>Collection Date</th>
<th>Invoice No</th>
<th>Invoice Date</th>
<th>Customer</th>              
<th>Net Amount</th>
<th>Col Amt</th>
<th>TDS</th>
<th>Deduction </th>
<th>Net Collected</th>
<th>InvNet Balance</th>
<th>Status</th>            
</tr>
</thead>
<tbody id="collectiontb">
 <?php 
            if(isset($_GET['invno'])){

            $getcollslist = $db->query("SELECT * FROM collection WHERE cust_code='$row->cust_code' AND invno='".$_GET['invno']."'");
            while($collsrow = $getcollslist->fetch(PDO::FETCH_OBJ)){
            echo "<tr>";
            echo "<td class='center first-child'>
                    <label>
                        <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                        <span class='lbl'></span>
                        </label>
                    </td>";
            
                    echo "<td>".$collsrow->colno."</td>";
                    echo "<td>".$collsrow->coldate."</td>";
                    echo "<td>".$collsrow->invno."</td>";
                    echo "<td>".$collsrow->invdate."</td>";     
                    echo "<td>".$collsrow->cust_code."</td>";
                    echo "<td>".number_format($collsrow->invamt,2)."</td>";
                    echo "<td>".number_format($collsrow->colamt,2)."</td>";
                    echo "<td>".number_format($collsrow->tds,2)."</td>";
                    echo "<td>".number_format($collsrow->reduction,2)."</td>";
                    echo "<td>".number_format($collsrow->netcollamt,2)."</td>";
                    echo "<td>".number_format($collsrow->balamt,2)."</td>";
                    echo "<td>".$collsrow->status."</td>";
            echo "</tr>";
            }
        }
     ?>

  
</tbody>
</table>
</div>

  </form>
  </div>
  </div>
</div>
</div>
</section>

<!-- inline scripts related to this page -->
<script>


  function check_tr_lockstatus(){
    var cust_code = $('#cust_code').val();
    return cust_code;
  }
</script>
<script>
   var data;
   var response;
function getResponse(response) {
    data = response;
    return data;
}
</script>

<script>
function addcollection1()
{
 var totalAmount =  (($('#colamt').val().trim())+($('#tds').val().trim())+($('#reduction').val().trim()));
 if(data[20]==1){
            swal({
            text: "This Customer Has Transcation Lock",
            icon: "info",
             buttons: true,
            }).then((okey)=>{
                window.location.reload();  
            });
        }
        else if($('#colamt').val().trim().length === 0 && $('#tds').val().trim().length === 0 && $('#reduction').val().trim().length === 0)
        {
            swal("Please Enter Collected Amount");
        }
 else if(parseInt($('#balamt').val().trim()=='0')){
            swal("Invoice Already Collected");
        }
        else if(($('#balamt').val()) < parseFloat(totalAmount)){
            swal('Please Enter Valid Collected Amount');
            $('#colamt').css('border','1px solid #d05c48');
        }
        else{
            $('#addcollection').attr('type','submit');
            $('#addcollection').attr('name','addcollection');
        }
}
</script>
<script>
function addancollection1()
{
    var totalAmount =  (($('#colamt').val().trim())+($('#tds').val().trim())+($('#reduction').val().trim()));
    if(data[20]==1){
            swal({
            text: "This Customer Has Transcation Lock",
            icon: "info",
             buttons: true,
            }).then((okey)=>{
                window.location.reload();  
            });
        }
        else if($('#colamt').val().trim().length === 0 && $('#tds').val().trim().length === 0 && $('#reduction').val().trim().length === 0)
        {
            swal("Please Enter Collected Amount");
        }
    else if(parseInt($('#balamt').val().trim()=='0')){
            swal("Invoice Already Collected");
        }
        else if(($('#balamt').val()) < parseFloat(totalAmount)){
            swal('Please Enter Valid Collected Amount');
            $('#colamt').css('border','1px solid #d05c48');
        }
        else{
            $('#addancollection').attr('type','submit');
           $('#addancollection').attr('name','addancollection');
        }
}
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>




<script>
console.log(suming(["28,285.00"]));
function suming(sells){
    var total = 0;
sells.forEach(function(sell) {
    total += +sell.replace(/,/g,'');
});
return total;
}

//console.log(total);
    </script>
<script>
//Fetch Branch Name
$('.br_code').keyup(function(){
        var branchcodes = $('.br_code').val();
        var stationscode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{branchcodes:branchcodes,stationscode:stationscode},
            dataType:"text",
            success:function(data3)
            {
                // document.write(data3);
            $('#br_name').val(data3);
            }
    });
    });
</script>
<script>

    $(document).ready(function(){
    $('#cust_code').keyup(function(){
        var ccode = $('#cust_code').val();
        var brcodes = $('#br_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{ccode:ccode,brcodes:brcodes},
            dataType:"text",
            success:function(data)
            {
            $('#cust_name').val(data);
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
  var branchcode =[<?php    
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];

var branchname =[<?php  

$brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode'");
while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrname['br_name']."',";
}

?>];
</script>

<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);
autocomplete(document.getElementById("cust_code"), customercode);
</script>
<script>
    //Fetch Branch code
    $('#br_name').keyup(function(){
            var ratebrname = $(this).val();
            var ratestnname = $('#stncode').val();
            $.ajax({
                url:"<?php echo __ROOT__ ?>collectioncontroller",
                method:"POST",
                data:{s_bname:ratebrname,s_comp:ratestnname},
                dataType:"text",
                success:function(data)
                {
                $('#br_code').val(data);
                fetchcustcode();
                branchvalidation();
                }
            });
        });
</script>
<script>
            //fetch code of entering name
            $('#cust_name').keyup(function(){
            var cccust_name = $(this).val();
            var cc_namestn = $('#stncode').val();
            var cc_namebr = $('#br_code').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{cccust_name:cccust_name,cc_namestn:cc_namestn,cc_namebr:cc_namebr},
            dataType:"text",
            success:function(cust_code)
            {
                $('#cust_code').val(cust_code);
                customervalidation();
            }
            });
            });
</script>
<script>
function fetchcustcode(){
    var branch8 = $('#br_code').val();
    var comp8 = $('#stncode').val();
       $.ajax({
       url:"<?php echo __ROOT__ ?>collectioncontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(ratecustcode)
       {
           autocomplete(document.getElementById("cust_code"), ratecustcode); 
       }
       });
       $.ajax({
       url:"<?php echo __ROOT__ ?>collectioncontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(ratecustname)
       {
           autocomplete(document.getElementById("cust_name"), ratecustname); 
       }
       });
}
</script>

<script>
  //Verifying Branch code is Valid
  function branchvalidation(){
        var branch_verify = $('.br_code').val();
        var stationcode = $('.stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{branch_verify:branch_verify,stationcode:stationcode},
            dataType:"text",
            success:function(branchcount)
            {
            if(branchcount == 0)
            {
                $('#brcode_validation').css('display','block');
            }
            else{
                $('#brcode_validation').css('display','none');
            }
            }
    });
    }
</script>
<script>
    $('#invno').keyup(function(){
        var invno = $(this).val();
        var customer = $('#cust_code').val();
        var inv_cstn = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{invno:invno,inv_cstn},
            dataType:"text",
            success:function(invdata)
            {
                var data = invdata.split("|");	
                console.log(data);			     
                if(data[16]=='Collected'){
                    swal("Entered Invoice number is already Collected");
                    $('#invno').val('');
                }
                else{
                  getResponse(data);
                    $('#rcptno').val(data[13]);
                    $('#chqddno').val(data[14]);
                    $('#chqbank').val(data[17]);
                    $('#depbank').val(data[18]);
                    $('#sheetno').val(data[19]);
                    if($('#totamt').val().trim() == ''){
                  
                    }
                    if($('#rcptno').val()==''){
                    }
                    if($('#chqddno').val()==''){
                    }
                    if(parseInt($('#balamt').val()) > parseInt($('#run_bal').val())){
                        $('#colamt').val($('#run_bal').val());  
                    }
                    else{
                        $('#colamt').val(data[5]);  
                    }

                    $('#br_code').val(data[0]);
                    $('#br_name').val(data[1]);
                    $('#cust_code').val(data[2]);
                    $('#cust_name').val(data[3]);
                    $('#invamt').val(data[4]);
                    $('#balamt').val(data[5]);
                    
                    $('#invdate').val(data[9]);
                    $('#coldate').val('<?php echo date('Y-m-d'); ?>');
                    $('#coldate').attr('min',data[9]);
                    $('#crn_no').val(data[10]);
                    $('#crn_bal').val(data[11]); 

                    if($('#totamt').val()=='0'){
                    }

                }    
            }
            });
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{invoicetb:invno,coll_rev_stn:inv_cstn},
            dataType:"text",
            success:function(collectiontb)
            {
               $('#collectiontb').html(collectiontb);
            }
            });
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{colinvrev:invno,colinvclist:inv_cstn},
            dataType:"text",
            success:function(collectioninvtb)
            {
               $('#collectioninvtb').html(collectioninvtb);
            }
            });
    });
</script>

<script>
 $(document).on('click', '.invoiceradio', function(){ 
        var invno = $(this).val();
        var inv_cstn = $('#stncode').val();
        // alert(invno);
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{invno:invno,inv_cstn},
            dataType:"text",
            success:function(invoicedata)
            {
                var data = invoicedata.split("|");
                $('#rcptno').val(data[13]);
                    $('#chqddno').val(data[14]);
                    $('#chqbank').val(data[17]);
                    $('#depbank').val(data[18]);
                    $('#sheetno').val(data[19]);
                if($('#totamt').val().trim() == ''){
                    //$('#totamt').val(data[6]);
                    //$('#run_bal').val(data[7]);
                    //$('#colamt').val(data[6]);  
                    }
                if($('#rcptno').val()==''){
                   // $('#rcptno').val(data[13]);
                }
                if($('#chqddno').val()==''){
                    //$('#chqddno').val(data[14]);
                }
                if(parseInt($('#balamt').val()) > parseInt($('#totamt').val())){
                    $('#colamt').val($('#totamt').val());  
                }
                else{
                    $('#colamt').val(data[5]);  
                }
                $('#br_code').val(data[0]);
                $('#br_name').val(data[1]);
                $('#cust_code').val(data[2]);
                $('#cust_name').val(data[3]);
                $('#cust_name').val(data[3]);
                $('#invamt').val(data[4]);
                $('#balamt').val(data[5]);
                // $('#colamt').val(data[5]);
                // $('#totamt').val(data[6]);
                // $('#run_bal').val(data[7]);
                $('#invno').val(data[8]);
                $('#invdate').val(data[9]);
                $('#coldate').val('<?php echo date('Y-m-d'); ?>');
                $('#coldate').attr('min',data[9]);
                // $('#rcptno').val(data[13]);
                // $('#chqddno').val(data[14]);
            }
            });
    });
</script>
<script>
function resetFunction() {   
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cust_code').value = "";
    document.getElementById('cust_name').value = "";
    document.getElementById('coldate').value = "";
    document.getElementById('rcptno').value = "";
    document.getElementById('totamt').value = "";
    document.getElementById('chdt').value = "";
    document.getElementById('chqbank').value = "";
    document.getElementById('depbank').value = "";
    document.getElementById('invno').value = "";
    document.getElementById('invamt').value = "";
    document.getElementById('invdate').value = "";
    document.getElementById('chqddno').value = "";
    document.getElementById('sheetno').value = "";
    document.getElementById('cmode').value = "";
    document.getElementById('remarks').value = "";
    document.getElementById('narration').value = "";  
    document.getElementById('colamt').value = "";
    document.getElementById('tds').value = "";
    }
</script>
<script>
    
     var table = $('#collectiontbl').DataTable();
 
        // var buttons = new $.fn.dataTable.Buttons(table, {
        //     buttons: [
        //       'excelHtml5',
        //       'pdfHtml5',
        //     ]
        // }).container().appendTo($('#buttons'));

     </script>