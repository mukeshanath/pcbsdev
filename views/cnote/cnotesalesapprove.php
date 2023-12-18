<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'cnotesalesapprove';
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

 
   //For Generating Request Number on form load
  function loadgroup($db,$stationcode)
          {
              $output='';
              $query = $db->query("SELECT * FROM groups WHERE stncode='$stationcode' ORDER BY gpid ASC");
             
               while ($row = $query->fetch(PDO::FETCH_OBJ)){
                  
              //menu list in home page using this code    
              $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
               }
               return $output;    
              
          }
  

if(isset($_GET['request_id'])){
     $request_id = $_GET['request_id'];
     $salesrequest = $db->query("SELECT * FROM cnotesalesrequest WHERE comp_code='$stationcode' AND request_id='$request_id'")->fetch(PDO::FETCH_OBJ);
}
?>
    
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
                                        <a href="<?php echo __ROOT__ . 'cnotesaleslist'; ?>">Cnote Sales list</a>
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
                                Booking Counter Record Added successfully.
                </div>";
                }

                ?>

                </div>
                <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>cnotesalesapprove" accept-charset="UTF-8" class="form-horizontal" id="formcnotesales" enctype="multipart/form-data">
                <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
                <input placeholder="" class="form-control border-form" name="request_id" type="text" id="request_id" value="<?php echo $request_id ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">

        <!-- Tittle -->
        <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Sales</p>
        <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>cnotesalesrequestlist"><button type="button" class="btn btn-danger btnattr" >
                        <i class="fa fa-times" ></i>
                </button></a>

                </div>
             
               </div> 
        <div class="content panel-body">

        <div class="tab_form">

        
        <table id="formPOSpurchase" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>		
        <td>Company Code</td>
        <td><input id="stncode" name="stncode" readonly type="text" value="" class="form-control input-sm stncode" style="text-transform:uppercase;"></td>
            
        <td>Company Name</td>
        <td>
            <input id="stnname" name="stnname" readonly value="" class="form-control input-sm" type="text">
        </td>
        <td>Branch Code</td>
        <td><div class="autocomplete col-sm-12">
        <input id="br_code" name="br_code" class="form-control input-sm br_code" type="text" readonly  value="<?=$salesrequest->br_code?>" oninput="$('#cust_code').val('');$('#cust_name').val('')" onkeyup="cust_autopopulate();verifybranch()">
        <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
        </div>
        </td>
        <td>Branch Name</td>
        <td><div class="col-sm-12">
             <input id="br_name" name="br_name"  value="<?=$salesrequest->br_name?>"  class="form-control input-sm" readonly type="text"></div>
             </div>
        </td>	
        </tr>
        <!-- Second Row	 -->
        <tr>
        <td>Customer Code</td>
        <td>
        <div class="autocomplete col-sm-12 ">
              <input class="form-control input-sm cust_code" readonly name="cust_code" type="text" id="cust_code" value="<?=$salesrequest->cust_code?>" onkeyup="verifycustomer()">
              <p id="custcode_validation" class="form_valid_strings">Enter Valid Customer Code</p>
              </div>
        </td>	
        <td>Customer Name</td>
        <td><div class="autocomplete col-sm-12">
                <input id="cust_name" name="cust_name" readonly type="text"  value="<?=$salesrequest->cust_name?>" class="form-control input-sm">
            </div>
        </td>
        <td>Serial Type</td>
        <td>
        <select  type="text" id="cnote_serial" name="cnote_serial" readonly class="form-control input-sm">
            <option value="<?=$salesrequest->serial_type?>"><?=$salesrequest->serial_type?></option>
            </select>
        </td>	
        <td>Quantity</td>
        <td>
            <input  class="form-control input-sm cnote_count" name="cnote_count" type="text" id="cnote_count" readonly value="<?=$salesrequest->cnote_quantity?>">
            <p id="quantity_verification" class="form_valid_strings">Enter Cnote Quantity</p>
            <p id="quantityerror" class="form_valid_strings">Entered Quantity Unavailable!!</p>
            </td>

        </tr> 
        <tr> 
         <!-- <td>Cnote Start</td>
            <td> -->
            <input tabindex="-1" readonly id="cnote_start" name="cnote_start" style="display:none" class="form-control input-sm"  type="text" >
            <!-- </td> -->
             <td>Valid Cnotes</td>
            <td><input tabindex="-1" readonly class="form-control input-sm "  name="cnote_procured" type="text" id="cnote_procured" ></td>
            <!-- <td></td>
            <td></td> -->
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td> <button type="button" class="btn btn-primary btnattr_cmn" onclick=" fetchsalesnote()">
                            <i class="fa fa-crosshairs"></i>&nbsp; View quantity
                            </button>                           
        </tr> 
        </tbody></table>
     <div>
        <div class="col-sm-7" style="overflow-y:scroll;overflow-x:hidden;height:410px;border:1px solid #2F70B1;padding-right:0px; margin-top:15px;background:#fff">
        <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Lists</p>
        </div>

        <table style="width:100%;margin-top:10px;margin-left:10px;" class="data-table">
            <thead>
            <tr style="max-width:100%">
                <th>S.No</th>
                <th>PO No</th>
                <th>Cnote Start</th>
                <th>Cnote End</th>
                <th>Total Counts</th>
                <th>Valid Cnotes</th>
            </tr>
            </thead>
            <tbody id="partition_cnotes">
                
            </tbody>
        </table>

                        </div>

        <!-- Sales Order Details Table -->
        <div class="col-sm-5 posSalsCalc" style="margin-top:15px;background:#fff;height:409.7px;border-bottom:1px solid #2f70b0;padding-left:10px">
		<table class="table">
			<tbody>
				<tr style="background : #668098; color : #fff;">
					<td colspan="4" style="padding : 8px;">Sales Info</td>
				</tr>
				<tr>
					<td>Stationary Charge</td>
					<td><input tabindex="-1" readonly class="form-control input-sm "  name="cnote_amount" type="text" id="cnote_amount" value="0"> </td>
					</tr></tr>
                    <td>Advance TS charges</td>
					<td>
                       <input class="form-control border-form numbers-only" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  name="tranship_charge" type="text" id="tranship_charge" onkeyup="sum()">
                    </td>
				</tr>
				
				<tr>
					<td>Grand Amount</td>
					<td>
                       <input tabindex="-1" readonly class="form-control border-form" name="grnd_amt" type="text" id="grnd_amt">
                    </td>
                    </tr></tr>
                    <td>Total GST</td>
					<td>
                      <input tabindex="-1" readonly class="form-control border-form" name="gst" type="text" id="gst">
                    </td>
				</tr>
				
				<tr>
					<td>Net Amount</td>
					<td>
                     <input tabindex="-1" readonly class="form-control border-form" name="net_amt" type="text" id="net_amt">
                    </td>
                    <td></td>
				</tr>
                <tr>
                <td>Payment Type</td>
                <td><select name="paymenttype" id="paymenttype">
                    <option value="<?=$salesrequest->payment_type?>"><?=$salesrequest->payment_type?></option>
                    <!-- <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="cheque">Cheque</option> -->
                    </select>
                    </td>
                    </tr>
                    <tr>
                    <td>Payment Reference</td>
                    <td>
                      <input tabindex="-1" readonly class="form-control border-form" value="<?=$salesrequest->payment_reference?>" name="payment_reference" type="text" id="payment_reference">
                    </td>
                    </tr>
			</tbody>
		</table>

                <!-- CashMode Table -->
		<div class="SnglPg-ReceiptMode">
	
                            <table class="table">
								<tbody>
								<tr class="ReceiptModeRow card"  id="card_info" style="display:none">
								<td>Card Amt</td>
								<td><input id="card_Amount" readonly name="possales.cardAmount" type="text" value="0.00" class="form-control input-sm"></td>
								
								<td id="lvouchers">Bank</td>
		                        <td style="padding: 1px 0px;">
									<select id="card_Bank" name="possales.cardBank" class="form-control input-sm">
                                        <option value="#">Select Bank</option>
				                     </select>
			                    </td>
			                    
								<td>Card Dtls</td>
								<td><input id="cardDetails" name="possales.cardDetails" type="text" value="" class="form-control input-sm"></td>
								</tr>
								</tbody>
							</table>
						</td></tr>
												<!-- Dynamic Cheque Detail Box -->

						 <tr class="ReceiptModeRow cheque">
								</tr></tbody></table><table class="table">
								<tbody><tr class="ReceiptModeRow cheque"  id="cheque_info" style="display:none">
								<td>Cheque Amt</td>
								<td><input id="cheque_Amount" readonly name="possales.chequeAmount" type="text" value="0.00" class="form-control input-sm"></td>
								<td id="lvouchers">Bank</td>
		                        <td style="padding: 1px 0px;">
								<select id="cheque_Bank" name="possales.chequeBank" class="form-control input-sm">
                                <option value="#">Select Bank</option>			                       
			                     </select>
			                    </td>
								<td>Cheque Dtls</td>
								<td><input name="possales.chequeDetails" type="text" value="" class="form-control input-sm"></td>
								</tr>
							</tbody></table>
						
						
							<!-- Dynamic Cash Detail Box -->
					
							<table class="table">
								<tbody><tr class="ReceiptModeRow cash" id="cash_info" style="display:none">
								<td>Countr Cash </td>
								<td><input id="counter_cash" name="possales.counterCash" type="text" value="0.00" class="form-control input-sm"></td>
								<td>Cash </td>
								<td><input id="cash_Amount" name="possales.cashAmount" readonly type="text" value="0.00" class="form-control input-sm"></td>
		      					</tr>
                                
		      				</tbody></table>
						  
                              <table>

                            <div class="align-right btnstyle" >

                            <button type="button" id="submitsales" class="btn btn-primary btnattr_cmn" onclick="verify()">
                            <i class="fa  fa-save"></i>&nbsp; Save
                            </button>
                                                                            

                            <!-- <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                            <i class="fa fa-eraser"></i>&nbsp; Reset
                            </button>    -->


                            </div>
                            <!-- name="addcnotesales" -->
                            </table>
		</div>	
	</div>
 </div>
        </div>
        </form>
        </div>
        </div>
        </div>
<input type="text" class="hidden" id="cust_cnotecharge">
</section>

<!-- inline scripts related to this page -->
<script>
    function verify()
    {
        if($('#br_code').val().trim()=='')
        {
            $('#brcode_validation').css('display','block');
        }
        else if($('#cust_code').val().trim()=='')
        {
            $('#custcode_validation').css('display','block');
        }
        else if($('#cnote_count').val().trim()=='')
        {
            $('#quantity_verification').css('display','block');
        }
        else if($('#cnote_procured').val().trim()=='')
        {
             swal("Please Calculate Cnote First!!");
        }
        else if($("#quantityerror").css("display")==="block")
        {
           swal("Entered Quantity Unavailable!!");
        }
        else if($("#custcode_validation").css("display")==="block")
        {
           swal("Enter Valid Customer!!");
        }
        else if($("#cnote_amount").val()=='0')
        {
           swal("Please Calculate Cnote First!!");
        }
        else{
            $('#submitsales').attr('type','submit');
            $('#submitsales').attr('name','approvecnotesales');
        }
    }

</script>
<script>
   $(document).ready(function(){

    $('#paymenttype').change(function(){
        var paymenttype = $('#paymenttype').val();
       if(paymenttype == 'cash')
       {
        document.getElementById("cash_info").style.display = "block";
       }
       else
       {
        document.getElementById("cash_info").style.display = "none";
       }
       if(paymenttype == 'card')
       {
        document.getElementById("card_info").style.display = "block";
       }
       else
       {
        document.getElementById("card_info").style.display = "none";
       }
       if(paymenttype == 'cheque')
       {
        document.getElementById("cheque_info").style.display = "block";
       }
       else
       {
        document.getElementById("cheque_info").style.display = "none";
       }
      });
    });
</script>
<script>
function sum(){
    var grand = parseFloat($('#cnote_amount').val())+parseFloat($('#tranship_charge').val());
    var gst = (parseFloat(grand)/100)*18;
    var net = parseFloat(grand)+parseFloat(gst);
    if(!isNaN(grand,gst,net)){
    $('#grnd_amt').val(grand);
    $('#gst').val(gst);
    $('#net_amt').val(net);
    $('#cheque_Amount').val(net);
    $('#card_Amount').val(net);
    $('#cash_Amount').val(net);
    }
}
    </script>
<script> 

function getcnotestart(){
        var option = $('#cnote_serial').val();
        var station2 = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{option:option,station2:station2},
            dataType:"text",
            success:function(data)
            {
            $('#cnote_start').val(data);
            }
    });
    }
</script>
<script>
   
   $(document).ready(function(){

    //Fetch Branch Name
    $('.br_code').keyup(function(){
        var branchlookup = $('#br_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{branchlookup:branchlookup},
            dataType:"text",
            success:function(data)
            {
            $('#br_name').val(data);
            }
    });
    });
     //Fetch Customer Name
     $('.cust_code').keyup(function(){
        var customerlookup = $('#cust_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{customerlookup:customerlookup},
            dataType:"text",
            success:function(data)
            {
            $('#cust_name').val(data);
            }
    });
    });
});


$(document).ready(function(){

$('.cnote_count').keyup(function(){
    var count_inv = $('.cnote_count').val();
    var start_inv = $('#cnote_start').val();
    var invalidstncode = $("#stncode").val();
    $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        data:{count_inv:count_inv,start_inv:start_inv,invalidstncode:invalidstncode},
        dataType:"text",
        success:function(data)
        {
        $('#invalid_cnotes').html(data);
        }
});
});
});
  </script>
  <script>
  // Procured And Void
    function fetchsalesnote(){
        var station3 = $('#stncode').val();
        var count = $('.cnote_count').val().trim();
        var serial = $('#cnote_serial').val();
        var cust_code = $('#cust_code').val();
        var count_starts = $("#cnote_start").val();
        
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{station3:station3,count:count,count_starts:count_starts,serial:serial,cust_code:cust_code},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#cnote_procured').val(data[0]);
            $('#cust_cnotecharge').val(data[1]);
            calculateforprocured();
            //$('#cnote_amount').val(data[1]);
            //$('#grnd_amt').val(data[2]);
            //$('#gst').val(data[3]);
            //$('#net_amt').val(data[4]);
            //$('#cheque_Amount').val(data[4]);
            //$('#card_Amount').val(data[4]);
            //$('#cash_Amount').val(data[4]);
            if(parseInt(count)>data[0]){
                $('#quantityerror').css('display','block');
            }
            else{
                $('#quantityerror').css('display','none');
            }
            }
    });

    $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        data:{partstation:station3,partserial:serial,partcount:count,partstart:count_starts},
        dataType:"text",
        success:function(part)
        {
        $('#partition_cnotes').html(part);
        calculateforprocured();
        }
        });

    }
</script>
<script>
 function calculateforprocured(){
     var cnoteamount = 0;
    $(".val_proc").each(function(){
        cnoteamount += +$(this).val();
    });
    var cnoteamt = parseFloat(cnoteamount)*parseFloat($('#cust_cnotecharge').val());
    $('#cnote_amount').val(cnoteamt.toFixed(2));
    $('#grnd_amt').val(cnoteamt.toFixed(2));
    $('#gst').val(((parseFloat(cnoteamt)/100)*18).toFixed(2));
    $('#net_amt').val(((parseFloat(cnoteamt)/100)*18 + parseFloat(cnoteamt)).toFixed(2));
    $('#cheque_Amount').val((parseFloat(cnoteamt)/100)*18 + parseFloat(cnoteamt).toFixed(2));
    $('#card_Amount').val((parseFloat(cnoteamt)/100)*18 + parseFloat(cnoteamt).toFixed(2));
    $('#cash_Amount').val((parseFloat(cnoteamt)/100)*18 + parseFloat(cnoteamt).toFixed(2));
 }
</script>
<script>
            //Verifying Customer code is Valid
    function verifycustomer(){
        var cust_verify_br = $('#br_code').val();
        var cust_verify_cust = $('#cust_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{cust_verify_br:cust_verify_br,cust_verify_cust:cust_verify_cust},
            dataType:"text",
            success:function(customercount)
            {
            if(customercount == 0)
            {
                $('#custcode_validation').css('display','block');
            }
            else{
                $('#custcode_validation').css('display','none');
            }
            }
    });
    }
</script>
<script>
        //Verifying Branch code is Valid
        function verifybranch(){
        var branch_verify = $('#br_code').val();
        var stationcode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
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
        //fetch customer code
        $('#cust_name').keyup(function(){
        var c_name = $(this).val();
        var s_c_comp = $('.stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{c_name:c_name,s_c_comp:s_c_comp},
            dataType:"text",
            success:function(custcode)
            {
                $('#cust_code').val(custcode);
                verifycustomer();
            }
        });
        });
</script>
  <script>
  //Fetch Company code and name based on user
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
            getcnotestart();
            fetchsalesnote();
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
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode' AND cate_code NOT LIKE 'DC'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];

 var branchname = [<?php
 $brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND cate_code NOT LIKE 'DC'");
 while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
 {
 echo "'".$rowbrname['br_name']."',";
 }
 ?>]

</script>
<script>
function cust_autopopulate(){
   var branch8 = $('.br_code').val();
   var comp8 = $('.stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("cust_code"), cust8); 
       }
   });
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{c_name_br:branch8,c_name_comp:comp8},
       dataType:"json",
       success:function(custname)
       {
           autocomplete(document.getElementById("cust_name"), custname); 
       }
   });
}
</script>
<script>
$('#br_name').keyup(function(){
   var s_bname = $(this).val();
   var s_comp = $('.stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{s_bname:s_bname,s_comp:s_comp},
       dataType:"text",
       success:function(brcode)
       {
           $('#br_code').val(brcode);
            cust_autopopulate();
            verifybranch();
       }
});
});
</script>
<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);
</script>

<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cust_code').value = "";
    document.getElementById('cust_name').value = "";
    document.getElementById('cnote_count').value = "";
   
}
</script>