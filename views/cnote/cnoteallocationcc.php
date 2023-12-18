<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $module = 'cnoteallocationcc';
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

    $sessionbr_code = $datafetch['br_code'];
    if(!empty($sessionbr_code)){
        $getbrname = $db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND br_code='$sessionbr_code'")->fetch(PDO::FETCH_OBJ);
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
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Cnote</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">CC Allocation</li>
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
                <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>cnoteallocationcc" accept-charset="UTF-8" class="form-horizontal" id="formcnotesales" enctype="multipart/form-data">
                <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">

        <!-- Tittle -->
        <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">CC Allocation</p>
        <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>cnoteallocationlistcc"><button type="button" class="btn btn-danger btnattr" >
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
            <input id="br_code" <?=(!empty($sessionbr_code)?" value='$sessionbr_code' readonly ":"")?> name="br_code" class="form-control input-sm br_code text-uppercase" type="text" autofocus oninput="$('#cc_code').val('');$('#cc_name').val('');getcollectioncentersjson();">
        <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
        </div>
        </td>
        <td>Branch Name</td>
        <td><div class="col-sm-12">
             <input id="br_name" <?=(!empty($sessionbr_code)?" value='$getbrname->br_name' readonly ":"")?> name="br_name" onfocusout="getcollectioncentersjson()" class="form-control input-sm text-uppercase" type="text"></div>
             </div>
        </td>	
        </tr>
        <!-- Second Row	 -->
        <tr>
        <td>CollectionCenter Code</td>
        <td>
        <div class="autocomplete col-sm-12 ">
              <input class="form-control input-sm cc_code text-uppercase"  name="cc_code" type="text" id="cc_code">
              <p id="cccode_validation" class="form_valid_strings">Enter Valid CollectionCenter Code</p>
              </div>
        </td>	
        <td>CollectionCenter Name</td>
        <td><div class="autocomplete col-sm-12">
                <input id="cc_name" name="cc_name" type="text" value="" class="form-control input-sm text-uppercase">
            </div>
        </td>
        <td>Serial Type</td>
        <td>
        <select  type="text" id="cnote_serial" name="cnote_serial" class="form-control input-sm">
            <option value="0">Select Serial</option>
            <?php echo loadgroup($db,$stationcode); ?>
            </select>
        </td>	
        <td>Quantity</td>
        <td>
            <input  class="form-control input-sm cnote_count" name="cnote_count" type="text" id="cnote_count">
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
        <div class="col-sm-7" style="overflow-y:scroll;overflow-x:hidden;height:430px;border:1px solid #2F70B1;padding-right:0px; margin-top:15px;background:#fff">
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
        <div class="col-sm-5 posSalsCalc" style="margin-top:15px;background:#fff;height:430px;border-bottom:1px solid #2f70b0;padding-left:10px">
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
                    <!-- <option value="">Select Payment mode</option> -->
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="cheque">Cheque</option>
                    </select>
			</tbody>
		</table>

                <!-- CashMode Table -->
		<div class="SnglPg-ReceiptMode">
	
                            <table class="table">
								<tbody>
								<tr class="ReceiptModeRow card"  id="card_info" style="display:none">
								<td>Card Amt</td>
								<td><input id="card_Amount" readonly name="possales.cardAmount" type="text" value="0.00" class="form-control input-sm"></td>
								
								<!-- <td id="lvouchers">Bank</td>
		                        <td style="padding: 1px 0px;">
									<select id="card_Bank" name="possales.cardBank" class="form-control input-sm">
                                        <option value="#">Select Bank</option>
				                     </select>
			                    </td> -->
			                    
								<td>Card Details</td>
								<td><input id="cardDetails" name="card" type="text" value="" class="form-control input-sm"></td>
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
								<!-- <td id="lvouchers">Bank</td>
		                        <td style="padding: 1px 0px;">
								<select id="cheque_Bank" name="possales.chequeBank" class="form-control input-sm">
                                <option value="#">Select Bank</option>			                       
			                     </select>
			                    </td> -->
								<td>Cheque Details</td>
								<td><input name="cheque" type="text" value="" class="form-control input-sm"></td>
								</tr>
							</tbody></table>
						
						
							<!-- Dynamic Cash Detail Box -->
					
							<table class="table">
								<tbody><tr class="ReceiptModeRow cash" id="cash_info" style="display:block">
								<td>Counter Cash </td>
								<td><input id="cash_Amount" name="possales.counterCash" type="text" value="0.00" class="form-control input-sm"></td>
								<td>Receipt No</td>
								<td><input id="" name="cash" type="text" class="form-control input-sm"></td>
		      					</tr>
                                
		      				</tbody></table>
						  
                              <table>

                            <div class="align-right btnstyle">

                            <button type="button" id="submitsales" class="btn btn-primary btnattr_cmn" onclick="verify()">
                            <i class="fa  fa-save"></i>&nbsp; Save
                            </button>
                                                                            

                            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                            <i class="fa fa-eraser"></i>&nbsp; Reset
                            </button>   


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
<input type="text" class="hidden" id="cc_cnotecharge">
</section>
<script>
    $(document).ready(function(){
        $("#cnote_serial").change(function(){
            console.log($(this).val());
        
         var cnote_serial = $(this).val();
         var stockrange_stn = $('#stncode').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{stockrange_stn,cnote_serial,action : 'stock_alert'},
            dataType:"text",
            success:function(value)
            {
            stock = JSON.parse(value);
        
                if(stock[0]=="1"){
                swal("Cnotes are going Out Of Stock");
            }else{
               
            }
       
                
       
            }
    });
       });
    });
</script>
<!-- inline scripts related to this page -->
<script>
    function verify()
    {
        if($('#br_code').val().trim()=='')
        {
            $('#brcode_validation').css('display','block');
        }
        else if($('#cc_code').val().trim()=='')
        {
            $('#cccode_validation').css('display','block');
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
        else if($("#cccode_validation").css("display")==="block")
        {
           swal("Enter Valid CollectionCenter!!");
        }
        else if($("#cnote_amount").val()=='0')
        {
           swal("Please Calculate Cnote First!!");
        }
        else{
            $('#submitsales').attr('type','submit');
            $('#submitsales').attr('name','addcnotesales');
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
   
   $(document).ready(function(){

    $('#cnote_serial').change(function(){
        var option = $('#cnote_serial').val();
        var station2 = $('#stncode').val();
        var cccode = $('#br_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{ccoption:option,ccstation2:station2,cccode},
            dataType:"text",
            success:function(data)
            {
            $('#cnote_start').val(data);
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
        var cc_code = $('#cc_code').val();
        var count_starts = $("#cnote_start").val();
        var ccbrcode = $("#br_code").val();
        
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{ccstation3:station3,cccount:count,cccount_starts:count_starts,ccserial:serial,cc_code:cc_code,ccbrcode},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#cnote_procured').val(data[0]);
            $('#cc_cnotecharge').val(0);//data[1]
            calculateforprocured();
        
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
        data:{ccpartstation:station3,ccpartserial:serial,ccpartcount:count,ccpartstart:count_starts,allccbrcode:ccbrcode},
        dataType:"text",
        success:function(part)
        {
        $('#partition_cnotes').html(part);
        calculateforprocured();
        console.log(part);
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
    var cnoteamt = parseFloat(cnoteamount)*parseFloat($('#cc_cnotecharge').val());
    $('#cnote_amount').val(cnoteamt.toFixed(2));
    $('#grnd_amt').val(cnoteamt.toFixed(2));
    $('#gst').val(((parseFloat(cnoteamt)/100)*18).toFixed(2));
    $('#net_amt').val(((parseFloat(cnoteamt)/100)*18 + parseFloat(cnoteamt)).toFixed(2));
    $('#cheque_Amount').val(((parseFloat(cnoteamt)/100)*18 + parseFloat(cnoteamt)).toFixed(2));
    $('#card_Amount').val(((parseFloat(cnoteamt)/100)*18 + parseFloat(cnoteamt)).toFixed(2));
    $('#cash_Amount').val(((parseFloat(cnoteamt)/100)*18 + parseFloat(cnoteamt)).toFixed(2));
 }
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
            if('<?=$sessionbr_code?>'.trim()!=''){
                console.log('test');
                cc_autopopulate();
                getcollectioncentersjson()

            }
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

    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode' AND branchtype='C'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];

 var branchname = [<?php
 $brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND branchtype='C'");
 while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
 {
 echo "'".$rowbrname['br_name']."',";
 }
 ?>]

</script>
<script>
function cc_autopopulate(){
    console.log('l1');
   var branch8 = $('.br_code').val();
   var comp8 = $('.stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{branchcc:branch8,compcc:comp8},
       dataType:"text",
       success:function(cc8)
       {
           var cc8json = JSON.parse(cc8);
           //console.log(cc8);
           autocomplete(document.getElementById("cc_code"), cc8json); 
       }
   });
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"text",
       data:{c_name_brcc:branch8,c_name_compcc:comp8},
       dataType:"text",
       success:function(ccname)
       {
            var ccnamejson = JSON.parse(ccname);
           autocomplete(document.getElementById("cc_name"), ccnamejson); 
       }
   });
}
</script>
<script>
//branches array
var branches = {
    <?php
    $branches = $db->query("SELECT br_code,br_name FROM branch WHERE stncode='$stationcode' AND branchtype='C'");
    while($rowbranches=$branches->fetch(PDO::FETCH_OBJ))
    {
    echo $rowbranches->br_code.":'".$rowbranches->br_name."',";
    }
    ?>
}
</script>
<script>
    function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
</script>
<script> 

$(document).ready(function(){
     
     $('#cc_code').focusout(function(){
         var cc_code = $('#cc_code').val().toUpperCase().trim();
         //console.log(collectioncenters);

        if(collectioncenters[cc_code]!==undefined){
            //console.log(collectioncenters[cc_code]);
            $('#cc_name').val(collectioncenters[cc_code]);
            $('#cccode_validation').css('display','none');
        }
        else{
            $('#cccode_validation').css('display','block');
        }
     });
     //fetch code of entering name
     $('#cc_name').focusout(function(){
         var cc_name = $(this).val();
         var ccode = getKeyByValue(collectioncenters,cc_name);

         if(ccode!==undefined){
            //console.log(ccode);
            $('#cc_code').val(ccode);
            $('#cccode_validation').css('display','none');
            }
            else{
                $('#cccode_validation').css('display','block');
            }
     });
});

</script>
<script>
  $(document).ready(function(){
     
        $('#br_code').focusout(function(){
            var br_code = $('#br_code').val().toUpperCase().trim();
            if(branches[br_code]!==undefined){
                $('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
                cc_autopopulate();
            }
            else{
                $('#brcode_validation').css('display','block');
            }
        });
        //fetch code of entering name
        $('#br_name').focusout(function(){
            var br_name = $(this).val().trim();
            var gccode = getKeyByValue(branches,br_name);
            
            if(gccode!==undefined){
                $('#brcode_validation').css('display','none');
                $('#br_code').val(gccode);
                cc_autopopulate();
            }
            else{
                $('#brcode_validation').css('display','block');
            }
           
        });
    });
    </script>
<script> 
collectioncenters = {}

function getcollectioncentersjson(){
   //get collectioncenters json
   var branch8 = $('#br_code').val().toUpperCase();
   var comp8 = $('#stncode').val().toUpperCase();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{getjsoncc_br:branch8,getjsoncc_stn:comp8},
       dataType:"text",
       success:function(ccomerjson)
       {
          console.log(ccomerjson);
          collectioncenters = JSON.parse(ccomerjson);
       }
       });
    }
</script>
<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);
</script>

<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cc_code').value = "";
    document.getElementById('cc_name').value = "";
    document.getElementById('cnote_count').value = "";
   
}
</script>