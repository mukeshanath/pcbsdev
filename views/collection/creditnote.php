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
    // $user2 = $_SESSION['user_name'];
    
    function creditnoteno($db,$user)
    {

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
        $prevcrn_no=$db->query("SELECT TOP 1 crn_no FROM credit_note WHERE comp_code='$stationcode' ORDER BY credit_note_id DESC");
        if($prevcrn_no->rowCount()== 0)
            {
            $crn_no = $stationcode.str_pad('1', 7, "0", STR_PAD_LEFT);
            }
        else{
            $prevcrn_norow=$prevcrn_no->fetch(PDO::FETCH_ASSOC);
            $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$prevcrn_norow['crn_no']);
            $arr[0];//PO
            $arr[1]+1;//numbers
            $crn_no = $stationcode.str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
            }
        return $crn_no;    
        
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
      <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>creditnote" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Note Form</p>
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
                <input placeholder="" class="form-control br_code" name="br_code" type="text"  id="br_code" onfocusout="branchvalidation();fetchcustcode()">
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form " tabindex="-1" name="br_name" type="text" id="br_name" value=""></div>
            </td>
            <td>Customer</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" class="form-control cust_code" name="cust_code" type="text"  id="cust_code" onfocusout="customervalidation()">
                <p id="custcode_validation" class="form_valid_strings">Enter Valid Customer Code</p>
                </div>
            </td>
            <td> <div class="autocomplete col-sm-12">
                <input class="form-control border-form " tabindex="-1"  name="cust_name" type="text" id="cust_name" value="" ></div>
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
                <input placeholder="" class="form-control " tabindex="-1" name="credit_note_no" type="text"  id="credit_note_no" value="<?php echo creditnoteno($db,$user); ?>" readonly>
            </td>
            <td>Credit Note Date</td>
            <td>
                <input placeholder="" class="form-control today" tabindex="-1" name="credit_note_date" type="date"  id="credit_note_date" >
            </td>
            
            
        </tr>
        <tr>
  
                        
        <td>Invoice No</td>
        <td>
            <input placeholder="" class="form-control "  name="invno" type="text"  id="invno">
        </td>
        <td>Invoice Date</td>
        <td>
            <input placeholder="" class="form-control today" tabindex="-1" name="invdate" type="date"  id="invdate">
        </td>
        
        
        </tr>
        <tr>
        
                                
        <td>Invoice Amount</td>
        <td>
            <input placeholder="" class="form-control " tabindex="-1" name="invamt" type="text"  id="invamt" readonly>
        </td>
        <td>Balance</td>
        <td>
            <input placeholder="" class="form-control " tabindex="-1" name="balamt" type="text"  id="balamt" readonly>
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
            <input  class="form-control " name="totamt" type="number" _min="0" step="0.01" id="totamt" />
        </td>
      
        <td >Remarks</td>
          <td>

          <input  class="form-control" name="remarks" tabindex="-1"  id="remarks" type="text" readonly/>
          <!-- <select class="form-control" name="remarks">
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
                    
                    </select>     -->
          </td>
        
        
        </tr>
            </tbody>
            </table>
            <input type="text" id="lockstatus" name="lockstatus" style="display:none">
            <table class="table">
              <tr>	           
              <div class="align-right btnstyle" >
                  <button type="button" class="btn btn-primary btnattr_cmn" id="addcreditnote" onclick="verify()">
                  <i class="fa fa-save"></i>&nbsp; Save
                  </button>

                  <button type="submit" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                  <i class="fa fa-eraser"></i>&nbsp; Reset
                  </button> 

                  </div>

              </tr>

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
         

  </form>
  </div>
  </div>
</div>
</div>
</section>

<!-- inline scripts related to this page -->

<script>
  $(document).ready(function(){
 $("#cust_code").on('keyup focustout', function (){

    var tr_cust_code = $(this).val();
    var stationcode = $('#stncode').val();
     $('#user_name').attr('data-lock','lock');
    $.ajax({
        url:"<?php echo __ROOT__ ?>invoicecontroller",
        method:"POST",
        async: false,
        global: false,
        data:{tr_cust_code,stationcode},
        dataType:"text",
        success:function(data)
        {
		 var tr_lockstatus = JSON.parse(data);
            // console.log(cnote_serial);
            console.log(tr_lockstatus);
            getResponse(tr_lockstatus);
          
           
        }
});
});
});
</script>
<script>
   var data;
   var response;
function getResponse(response) {
    data = response;
    return data;
}

   var res;
   var message;
function getResponseData(message) {
    res = message;
    return res;
} 
</script>


<script>
function resetFunction() {
  document.getElementById("formcustomerrate").reset();

}
</script>
<script>
 function verify()
    {
        if($('#user_name').data('lock')=='lock'){
            var lock = data[0];
        }else{
            var lock = 0;
        }

        if($('#br_code').val().trim()==''){
            swal("Enter the Branch Code");
        }
        else if($('#cust_code').val().trim()==''){
            swal("Enter the Customer Code");
        }
        else if(lock ==1 || res[20]==1){
            swal({
            text: "This Customer Has Transcation Lock",
            icon: "info",
             buttons: true,
            }).then((okey)=>{
                window.location.reload();  
            });
        }
        else if($('#invno').val().trim()==''){
            swal("Enter valid Invoice No");
        }
      
        else if(parseInt($('#balamt').val().trim()=='0')){
            swal("Invoice Already Collected");
        }
        else if(($('#balamt').val())<parseFloat($('#totamt').val()))
        {
            swal("Credit Note amount should be lesser or equal to the Balance amount");
        }
        else if(parseFloat($('#totamt').val())<1 || $('#totamt').val().trim()=='')
        {
            swal("Enter valid credit amount");
        }
         else{
            $('#addcreditnote').attr('type','submit');
            $('#addcreditnote').attr('name','addcreditnote');
        }
    }
</script>
<script>
$('#invno').focusout(function(){
    var invno = $(this).val();
    var inv_cstn = $('#stncode').val();
    $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{invno:invno,inv_cstn},
            dataType:"text",
            success:function(invdata)
            {
                var data = invdata.split("|");
                getResponseData(data)
                $('#br_code').val(data[0]);
                $('#br_name').val(data[1]);
                $('#cust_code').val(data[2]);
                $('#cust_name').val(data[3]);
                $('#invamt').val(data[4]);
                $('#balamt').val(data[5]);
                $('#invdate').val(data[9]);
                $('#lockstatus').val(data[12]);
                $('#remarks').val("Cr Note for Inv - "+invno);
                if(data[12]=='Locked'){
                   // alert("This Customer credit note is temporarily locked due to pending invoices");
                }
                
            }
            });
            $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{colinvrev:invno},
            dataType:"text",
            success:function(collectioninvtb)
            {
               $('#collectioninvtb').html(collectioninvtb);
            }
            });
});
</script>
<script>
//Fetch Branch Name
$('.br_code').focusout(function(){
        var branchcodes = $('.br_code').val();
        var stationscode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{branchcodes:branchcodes,stationscode:stationscode},
            dataType:"text",
            success:function(data3)
            {
            $('#br_name').val(data3);
            }
    });
    });
</script>
<script>

    $(document).ready(function(){
    $('#cust_code').focusout(function(){
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
//Verifying Customer code is Valid
function customervalidation(){
        var cust_verify_br = $('.br_code').val();
        var cust_verify_cust = $('.cust_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{cust_verify_br:cust_verify_br,cust_verify_cust:cust_verify_cust},
            dataType:"text",
            success:function(value)
            {
                var customercount = value.split(",");
                if(customercount[0] == 0)
                {
                    $('#custcode_validation').css('display','block');
                }
                else{
                    $('#custcode_validation').css('display','none');
                }
                $('#invno').val(customercount[1]);
                $('#invdate').val(customercount[2]);
                $('#invamt').val(customercount[3]);
                $('#balamt').val(customercount[4]);
                $('#lockstatus').val(customercount[5]);
                if(customercount[5]=='Locked'){
                   // alert("This Customer credit note is temporarily locked due to pending invoices");
                }

                $.ajax({
                url:"<?php echo __ROOT__ ?>collectioncontroller",
                method:"POST",
                data:{colinvrev:$('#invno').val()},
                dataType:"text",
                success:function(collectioninvtb)
                {
                $('#collectioninvtb').html(collectioninvtb);
                }
                });
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
                console.log(branchcount);
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
    //Fetch Branch code
    $('#br_name').focusout(function(){
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
            $('#cust_name').focusout(function(){
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