<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'cnoteallotmentrequest';
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


    function loadstatus($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM status ORDER BY sid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stat_type.'">'.$row->stat_type.'</option>';
         }
         return $output;    
        
    }
    
    $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
    $datafetch = $getuserstation->fetch(PDO::FETCH_OBJ);
    $rowuser = $datafetch->last_compcode;
    if(!empty($rowuser)){
            $stationcode = $rowuser;
    }
    else{
        $userstations = $datafetch->stncodes;
        $stations = explode(',',$userstations);
        $stationcode = $stations[0];     
    }
    $customer = $db->query("SELECT * FROM branch WHERE br_code='$datafetch->br_code' AND stncode='$stationcode'");
    if($customer->rowCount()==0){
    $br_code = '';
    $br_name = '';
    }
    else{
    $customerrow = $customer->fetch(PDO::FETCH_OBJ);
    $br_code = $customerrow->br_code;
    $br_name = $customerrow->br_name;
    }

    function loadgroup($db,$stationcode)
    {
        $output='';
        $query = $db->query("SELECT * FROM groups WHERE stncode='$stationcode' AND grp_type='sp' ORDER BY gpid ASC");
       
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
                                        <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                        </li>
                                        
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'cnoteallotmentrequestlist'; ?>">Cnote Allotment list</a>
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
                                Cnote Alloted successfully.
                </div>";
                }

                ?>

                </div>
                <form method="POST" action="<?php echo __ROOT__ ?>cnoteallotmentrequest" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="formcnotesales" enctype="multipart/form-data">
                <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">

        <!-- Tittle -->
        <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Allotment Request</p>
        <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>extcnoteallotmentrequestlist"><button type="button" class="btn btn-danger btnattr" >
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
        <td><input id="stncode" name="stncode" readonly type="text" value="" class="form-control input-sm" style="text-transform:uppercase;"></td>
            
        <td>Company Name</td>
        <td>
            <input id="stnname" name="stnname" readonly value="" class="form-control input-sm" type="text">
        </td>
        <td>Branch Code</td>
        <td><div class="autocomplete col-sm-12">
        <input id="br_code" name="br_code" class="form-control input-sm br_code" type="text" readonly onkeyup="verifybranch()" autofocus value="<?=$br_code //if(isset($_POST['lookupcustomer'])){ echo $_POST['br_code']; } ?>">
        <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
        </div>
        </td>
        <td>Branch Name</td>
        <td><div class="col-sm-12">
             <input id="br_name" name="br_name" tabindex="-1" class="form-control input-sm" readonly type="text" value="<?=$br_name?>"></div>
             </div>
        </td>	
        </tr>
        <!-- Second Row	 -->
        <tr>
        <td>Serial Type</td>
        <td>
        <select  type="text" id="cnote_serial" name="cnote_serial" class="form-control input-sm">
            <option value="BA">BA</option>
            <option value="DC">DC</option>
            <?php echo loadgroup($db,$stationcode)  ?>       
            </select>
        </td>	
        <td>Quantity</td>
        <td>
            <input  class="form-control input-sm cnote_count" name="cnote_count" type="text" id="cnote_count">
            </td>
            <!-- <td>Cnote Start</td>
            <td>
            <input tabindex="-1" readonly id="cnote_start" name="cnote_start" placeholder="" style="background: ;" value="<?php //echo startcount($db,$stationcode); ?>"  class="form-control input-sm"  type="text" >
            </td>
            <td>Valid Cnotes</td>
            <td><input tabindex="-1" readonly class="form-control input-sm "  name="cnote_procured" type="text" id="cnote_procured" ></td> -->
        </tr> 
       
        </tbody></table>
        <div class="align-right btnstyle" >

          <button type="button" id="submitsales" class="btn btn-primary btnattr_cmn" onclick="verify()">
          <i class="fa  fa-save"></i>&nbsp; Request
          </button>
                                                       

          <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
          <i class="fa fa-eraser"></i>&nbsp; Reset
          </button>   


          </div>
        <!-- <div class="align-right" style="padding:10px">
        <button type="button" class="btn btn-primary btnattr_cmn view-quantity" onclick=" fetchsalesnote()">
                            <i class="fa fa-crosshairs"></i>&nbsp; View quantity
                            </button>
            </div> -->
     <!-- <div>
        <div class="col-sm-7" style="height:410px;border:1px solid #2F70B1;border-right:none;padding-right:7px; margin-top:15px;background:#fff">
        <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Alloted Cnotes</p>
             
               </div>    
                    <div style="overflow-y:scroll;height:350px;">
                    <table cellpadding="0" cellspacing="0" class="table  data-table">
                            <thead>
                            <tr>
                                 <th>S.no</th>
                                 <th>PO No</th>
                                 <th>Cnote Start</th>
                                 <th>Cnote End</th>
                                 <th>Total Counts</th>
                                 <th>Valid Cnotes</th>
                                 </tr>
                            </thead>
                            <tbody id="allot_cnotes">
                            
                            </tbody>
                            
                        </table>
                        </div>
                        </div>

         Sales Order Details Table 
        <div class="col-sm-5 posSalsCalc" style="margin-top:15px;background:#fff">
		<table class="table">
			<tbody>
				<tr style="background : #668098; color : #fff;">
					<td colspan="4" style="padding : 8px;">Sales Info</td>
				</tr>
				<tr>
					<td>Stationary Charge</td>
					<td><input tabindex="-1" readonly class="form-control input-sm "  name="cnote_amount" type="text" id="cnote_amount" value="0.00"> </td>
					</tr></tr>
                    <td>Advance TS charges</td>
					<td>
                       <input tabindex="-1" readonly class="form-control border-form " value="0.00"  name="tranship_charge" type="text" id="tranship_charge">
                    </td>
				</tr>
				
				<tr>
					<td>Grand Amount</td>
					<td>
                       <input tabindex="-1" readonly class="form-control border-form" name="grnd_amt" value="0.00" type="text" id="grnd_amt">
                    </td>
                    </tr></tr>
                    <td>Total GST</td>
					<td>
                      <input tabindex="-1" readonly class="form-control border-form" name="gst" type="text" id="gst" value="0.00">
                    </td>
				</tr>
				
				<tr>
					<td>Net Amount</td>
					<td>
                     <input tabindex="-1" readonly class="form-control border-form" name="net_amt" type="text" id="net_amt" value="0.00">
                    </td>
                    <td></td>
				</tr>
                <tr>
               
			</tbody>
		</table>

                CashMode Table 
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
												 Dynamic Cheque Detail Box

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
						
						
							 Dynamic Cash Detail Box 
					
							<table class="table">
								<tbody><tr class="ReceiptModeRow cash" id="cash_info" style="display:none">
								<td>Countr Cash </td>
								<td><input id="counter_cash" name="possales.counterCash" onkeypress="return ValidateNumberDot(this, event);" onkeyup="extractNumber(this,2,true);" type="text" value="0.00" class="form-control input-sm"></td>
								<td>Cash </td>
								<td><input id="cash_Amount" name="possales.cashAmount" readonly type="text" value="0.00" class="form-control input-sm"></td>
		      					</tr>
                                
		      				</tbody></table>
						  
                              <table>

                            <div class="align-right btnstyle" >

                            <button type="button" id="submitsales" class="btn btn-primary btnattr_cmn" onclick="verify()">
                            <i class="fa  fa-save"></i>&nbsp; Save
                            </button>
                                                                            

                            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                            <i class="fa fa-eraser"></i>&nbsp; Reset
                            </button>   


                            </div>
                            name="addcnotesales" 
                            </table>
						
                       
						
					
				
			
		</div>	
	</div>
 </div> -->
        </div>
        </form>
        </div>
        </div>
        </div>

</section>

<!-- inline scripts related to this page -->
<script>
    function verify()
    {
         if($('#cnote_count').val().trim()=='')
        {
            swal('Enter cnote quantity');
        }
        else{
            $('#submitsales').attr('type','submit');
            $('#submitsales').attr('name','addcnoteallotment');
        }
    }

</script>

<script>
function resetFunction() {
  document.getElementById("formcnotesales").reset();
}  
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
});

$(document).ready(function(){

        $('#cnote_serial').change(function(){
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
            }
    });
  });
  </script>
  <script>
  // Procured And Void
  function fetchsalesnote(){
        var count = $('.cnote_count').val().trim();
        var serial = $('#cnote_serial').val();
        var count_starts = $("#cnote_start").val();
        var allotstncode = $("#stncode").val();
        
        $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        data:{partstation:allotstncode,partserial:serial,partcount:count,partstart:count_starts},
        dataType:"text",
        success:function(part)
        {
        $('#allot_cnotes').html(part);
        }
        });

        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{v_a_count:count,v_a_starts:count_starts,v_a_stncode:allotstncode,v_a_serial:serial},
            dataType:"text",
            success:function(proc)
            {
                $('#cnote_procured').val(proc);
                if(parseInt($('.cnote_count').val())>proc){
                    $('#quantityerror').css('display','block');
                }
                else{
                    $('#quantityerror').css('display','none');
                }
            }
        });
        $('#quantity_verification').css('display','none');
    }
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
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode' AND branchtype='D'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];

var branchname = [<?php
 $brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND branchtype='D'");
 while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
 {
 echo "'".$rowbrname['br_name']."',";
 }
 ?>]
</script>

<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);
</script>

<script>
        //Verifying Branch code is Valid
        function verifybranch(){
        var branch_verify = $('#br_code').val();
        var stationcode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{branch_verifydc:branch_verify,stationcodedc:stationcode},
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
$('#br_name').keyup(function(){
   var s_bname = $(this).val();
   var s_comp = $('#stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{s_bname:s_bname,s_comp:s_comp},
       dataType:"text",
       success:function(brcode)
       {
           $('#br_code').val(brcode);
            verifybranch();
       }
});
});
</script>
<?php
// if(startcount($db,$stationcode)=='Cnotes Empty')
//     {
//         echo "<script>alert('Cnotes Not Available to Allot');</script>";
//     }
?>

<script>
function resetFunction() {
    // document.getElementById('br_code').value = "";
    // document.getElementById('br_name').value = "";
    document.getElementById('cnote_serial').value = "Select type";
    document.getElementById('cnote_count').value = "";
    // document.getElementById('cnote_start').value = "";
    // document.getElementById('cnote_end').value = "";
    // document.getElementById('cnote_procured').value = "";
    // document.getElementById('cnote_void').value = "";
    // document.getElementById('cnote_amount').value = "";
    // document.getElementById('cnote_amount').value = "";
    // document.getElementById('tranship_charge').value = "";
    // document.getElementById('grnd_amt').value = "";
    // document.getElementById('gst').value = "";
    // document.getElementById('net_amt').value = "";
    // document.getElementById('card_Amount').value = "";
    // document.getElementById('card_Bank').value = "";
    // document.getElementById('cardDetails').value = "";
    // document.getElementById('cheque_Amount').value = "";
    // document.getElementById('cheque_Bank').value = "";
    // document.getElementById('chequeDetails').value = "";
    // document.getElementById('counter_cash').value = "";
    // document.getElementById('cash_Amount').value = "";
    // document.getElementById('allot_cnotes').value = "";

}
</script>