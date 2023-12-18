<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

include 'genrate_irn.php';
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'invoice';
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
  
//error_reporting(0);



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

$month_ini = new DateTime("first day of last month");
$month_end = new DateTime("last day of last month");

  //branch filter conditions
  $sessionbr_code = $datafetch['br_code'];
  if(!empty($sessionbr_code)){
      $br_code = $sessionbr_code;
      $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
      $br_name     = $fetchbrname->br_name;
      }
   //branch filter conditions

function loadbrcategory($db,$stationcode)
{
    $output='';
    $query = $db->query("SELECT * FROM brcategory WHERE comp_code='$stationcode' ORDER BY brcid ASC");
   
     while ($row = $query->fetch(PDO::FETCH_OBJ)){
        
    //menu list in home page using this code    
    $output .='<option id="'.$row->cate_code.'" value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
     }
     return $output;    
    
}









?>
<style>
#content{
    padding-top: 25px;
}
.swal-button--cancel{
    display:none;
}
</style>
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;float:none">  


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
                                    <li class="list-inline-item active">
                                        <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                        </li>
                                        
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'invoicelist'; ?>">Invoice list</a>
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
                        Invoice Generated successfully.
                      </div>";
        }

        if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 1 )
        {
            echo 

        "<div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-times'></i> Alert!</h4>
                        Please Select Transaction Between Date.
                      </div>";
        }
        if ( isset($_GET['emptyinvoice']))
        {
            echo "<script> swal('There is No transaction to Generate Invoice'); </script>";
                     
        }

        ?>
                
             </div>

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">New Invoice</p>
         <div class="align-right" style="margin-top:-35px;">

       
        <a style="color:white" href="<?php echo __ROOT__ ?>invoicelist"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>

          </div>
        </div>

      <div class="content panel-body">
      

        <div class="tab_form">
       

        <div id="form_error" class="alert alert-danger fade in errMrg" style="display: none;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <span class="error" style="color : #fff;"></span>
        </div>  

       <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->
       <form method="POST" action="<?php echo __ROOT__ ?>invoice" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="formitem" enctype="multipart/form-data">


       <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
       <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>
  
            <td>Company</td>
            <td style="width:100px;">
                <input placeholder="" class="form-control stncode" name="stncode" type="text"  id="stncode" readonly>
            </td>
            <td>
                <input class="form-control border-form "  name="stnname" type="text" id="stnname" readonly>
            </td>
            <td>Branch</td>
            <td style="width:100px;">
            <div class="autocomplete">
            <input id="br_code" name="br_code" class="form-control input-sm text-uppercase br_code" type="text" autofocus <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_code))?$br_code:''; ?>" onfocusout="fetchcustcode()">
                </div>
            </td>
            <td><div class="col-sm-12">
            <input id="br_name" name="br_name" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_name))?$br_name:''; ?>" tabindex="-1"  class="form-control input-sm"  type="text"></div>
            </td>
            <td>Customer</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" class="form-control text-uppercase" name="cust_code" type="text"  id="cust_code">
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form "  name="cust_name" type="text" id="cust_name"></div>
            </td>
            
        </tr>
                      
        </tbody>
        </table>
          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>
        <td>Transaction Between</td>
        <td>
        <div class="autocomplete col-sm-12">
              <input class="form-control input-sm"  name="trans_date_from" type="date" id="trans_date_from" value="<?=$month_ini->format('Y-m-d'); // First Date of Prev Month?>">
              </div>
        </td>	
        <td>&nbsp;to</td>
        <td>
            <input id="trans_date_to" name="trans_date_to" type="date" class="form-control input-sm" value="<?=$month_end->format('Y-m-d'); // Last Date of Prev Month?>">
        </td>
        <td>Invoice Date</td>
        <td>
        <input  type="date" id="inv_date" name="inv_date" class="form-control input-sm lastdate" value="<?=date('Y-m').'-05'?>">
           
        </td>	
        <td>Invoice No</td>
        <td>
            <input  class="form-control input-sm cnote_count" name="inv_no" value="" type="text" id="inv_no" readonly>
            </td>
            <td>Category</td>
            <td>
            <input  id="category" name="category" class="form-control input-sm" readonly>
            <!-- <option value="">Select Category</option>
          
            </select> -->
            </td>
        </tr> 
       </tbody>
     </table>
     <table class="table" cellspacing="0" cellpadding="0" align="center">
     <tr id="inv_amt_threshold_field" style="display:none">
           <td>Invoice Amount </td>
           <td>Threshold</td>
            <td><input type="text" class="form-control input-sm" name="inv_amt_threshold" id="inv_amt_threshold"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr> 
     </table>
     <table>

        <div class="align-right btnstyle" >
        <button type="button" class="btn btn-success btnattr_cmn" name="generateinvoice" onclick="newinvoicerequest()" id="invgenbtn">
       <i class="fa fa-save"></i>&nbsp; Generate
       </button>

                                                            
     
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction();">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>   
        </div>
        </table> 

     </div> 

<!-- Fetching Mode Details in Table  -->
<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
        </div>
        <!-- <div  style="height:440px;overflow-y:scroll"> -->
<table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>
            
                    <th >S.No</th>
                    <th >Date</th>
                    <th >Cnote Number</th>
                    <th >Destination</th>
                    <th >Weight</th>
                    <!-- <th >V-Weight</th> -->
                    <th >Mode</th>
                    <th >Amount</th>
                    <!-- <th >User</th> -->
                    <!-- <th >Op.Destination</th> -->
              </tr>
    </thead>
              <tbody>
                  <?php 
                  if(isset($_GET['inv_no']))
                  {
                        $inv_no  = $_GET['inv_no'];
                        if(isset($_GET['type'])){
                            $query = $db->query("SELECT * FROM ccharge WHERE invno='$inv_no' ORDER BY ccharge_id ASC");
                   
                            $sno=0;
                             while ($row = $query->fetch(PDO::FETCH_OBJ))
                              {$sno++;
                                echo "<tr>";
                                echo "<td class='center first-child'>
                                        <label>
                                           <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                            <span class='lbl'></span>
                                         </label>
                                      </td>";
                               
                                echo "<td>".$sno."</td>";
                                echo "<td>".$row->tdate."</td>";
                                echo "<td>".$row->cno."</td>";
                                echo "<td>".$row->destn."</td>";
                                echo "<td>".number_format($row->wt, 3)."</td>";
                                // echo "<td>".$row->bilvwt."</td>";
                                echo "<td>".$row->mode_code."</td>";
                                echo "<td>".round($row->amount)."</td>";
                                // echo "<td>".$row->user_name."</td>";
                                // echo "<td>".$row->bildest."</td>";
                              
                                       echo "</tr>";
                                                        
                              }
                         }
                         else
                         {
                         $query = $db->query("SELECT * FROM credit WHERE invno='$inv_no' ORDER BY credit_id ASC");
                   
                        $sno=0;
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {$sno++;
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                           
                            echo "<td>".$sno."</td>";
                            echo "<td>".$row->tdate."</td>";
                            echo "<td>".$row->cno."</td>";
                            echo "<td>".$row->dest_code."</td>";
                            echo "<td>".number_format($row->wt, 3)."</td>";
                            // echo "<td>".$row->bilvwt."</td>";
                            echo "<td>".$row->mode_code."</td>";
                            echo "<td>".round($row->amt)."</td>";
                            // echo "<td>".$row->user_name."</td>";
                            // echo "<td>".$row->bildest."</td>";
                          
                                   echo "</tr>";
                           
                                                    
                          }
                        }
                        }
                       ?>

                   
                 </tbody>
            </table>
            
              <!-- </div> -->
 
              </div>
             
            <div style="border:1px solid #2F70B1;margin-top:6px;background:#fff;">
 
            <table id="formPOSpurchase" class="table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
       
 
        <tr> 
         <td>Total</td>
            <td>
            <input  id="total" name="total" value="<?php if(isset($_GET['total']))echo $_GET['total']; ?>" class="form-control input-sm"  type="text" >
            </td>
            <td>FSC</td>
            <td>
            <input class="form-control input-sm " value="<?php if(isset($_GET['total']))echo $_GET['fsc']; ?>"  name="fsc_hc" type="text" id="fsc_hc">
            </td>
            <td>Invoice Amount</td>
            <td>
            <input class="form-control input-sm " value="<?php if(isset($_GET['total']))echo $_GET['invamt']; ?>"  name="fsc_hc" type="text" id="fsc_hc">
            </td>
            <td>GST</td>
            <td><input  class="form-control input-sm " value="<?php if(isset($_GET['total']))echo $_GET['gst']; ?>" name="s_tax" type="text" id="s_tax" ></td>
            <td>Discount</td>
            <td><input class="form-control input-sm " value="0" name="discount" type="text" id="discount" ></td>
            <td>Net Amount</td>
            <td><input class="form-control input-sm " value="<?php if(isset($_GET['total']))echo $_GET['net']; ?>" name="net_amt" type="text" id="net_amt"></td>
            <td>CNote Advance</td>
            <td><input class="form-control input-sm "  name="cnote_advance" type="text" id="cnote_advance"></td>
            <!-- <td><input class="form-control input-sm "  name="customer" type="text" id="customer"></td> -->

        </tr> 
        </tbody></table>
              </div>
              </div>
          </div>
          </div>
          </form>

</section>
<div class="progress active">
            <div class="progress-bar progress-bar-striped progress-bar-success"  style="width: 0%" ></div>
        </div>
<script>
  $(document).ready(function(){
        $('#data-table').DataTable();
    });
    </script>
	<script>
  $(document).ready(function(){
 $("#cust_code, #trans_date_from, #trans_date_to").on('keyup focustout keypress change', function (){

    var tr_cust_code = $('#cust_code').val();
    var stationcode = $('#stncode').val();

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
</script>
<script>
    //Fetch Branch code
    $('#br_name').focusout(function(){
            var ratebrname = $(this).val();
            var ratestnname = $('#stncode').val();
            $.ajax({
                url:"<?php echo __ROOT__ ?>invoicecontroller",
                method:"POST",
                data:{s_bname:ratebrname,s_comp:ratestnname},
                dataType:"text",
                success:function(data)
                {
                $('#br_code').val(data);
                fetchcustcode();
                }
            });
        });
</script>
<script>
    function newinvoicerequest(){
        $('#invgenbtn').attr({"disabled": true, "readonly": true});
         if($('#cust_code').val().trim()== ''){
             var lockst = 0
         }else{
            var lockst = data[0]
         }
        var stncode = $('#stncode').val();
        var br_code = $('#br_code').val();
        var cust_code = $('#cust_code').val();
        var trans_date_from = $('#trans_date_from').val();
        var trans_date_to = $('#trans_date_to').val();
        var inv_date = $('#inv_date').val();
        var user_name = $('#user_name').val();
        var category = $('#category').val();
        var inv_amt_threshold = $('#inv_amt_threshold').val();
        if($('#category').val().trim()==''){
            swal("Please Select Category");
            $('#category').css('border-color','red');
            $('#invgenbtn').attr({"disabled": false, "readonly": false});
        }
        
        else if(lockst==1){
            swal({
            text: "This Customer Have Lock Transaction",
            icon: "info",
             buttons: true,
            }).then((okey)=>{
                window.location.reload();  
            });
        }
		else if($('#cust_code').val().trim().toUpperCase()=='ALL' || $('#cust_code').val().trim()==''){
            progress();
            $('.progress-bar').html('<p>Please wait.... Fetching Customers');
            $.ajax({
            async:false,
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{newinv_ccodes_stncode:stncode,newinv_ccodes_br_code:br_code,newinv_ccodes_trans_date_from:trans_date_from,newinv_ccodes_trans_date_to:trans_date_to,newinv_category:category,newinv_inv_amt_threshold:inv_amt_threshold},
            dataType:"json",
            success:function(customerslist)
            {
            stopprogress();
            //console.log(customerlist.join());
            if(customerslist==0){
                swal('There is No transaction to Generate Invoice'); 
                $('#invgenbtn').attr({"disabled": false, "readonly": false});
            }
            else{
                    customerslist.forEach(eachinvoice);               
            }
            }
            });
        }
        else{ 
        progress();
        $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{stncode,br_code,cust_code,trans_date_from,trans_date_to,inv_date,user_name,category,inv_amt_threshold},
            dataType:"text",
            success:function(data)
            {
            if(data.trim()=='empty'){
                swal('There is No transaction to Generate Invoice'); 
                stopprogress();
            }
            else{
                $('.progress-bar').html('<p>Please wait.... Invoice is now Processing for '+cust_code);
                stopprogress();
                swal('Invoice has been Generated successfully');
            }
            $('#invgenbtn').attr({"disabled": false, "readonly": false});
            }
            });
        }
    }
</script>
<script>
function customerlistinvnew(customerslist){
    // customerslist.forEach(eachinvoice);
                }
</script>
<script>
 function eachinvoice(item,index,array){
    //console.log(item);
    setTimeout(() => {
    var arrlen = array.length;
    var currentlen = Math.round(100/parseInt(arrlen)*(index+1));
  
      var stncode = $('#stncode').val();
                var br_code = $('#br_code').val();
                var trans_date_from = $('#trans_date_from').val();
                var trans_date_to = $('#trans_date_to').val();
                var inv_date = $('#inv_date').val();
                var user_name = $('#user_name').val();
                var category = $('#category').val();
                var inv_amt_threshold = $('#inv_amt_threshold').val();
                var cust_code = item;
                $(".progress-bar").width(currentlen+"%");
                $('.progress-bar').html('<p>Please wait.... Invoice is now Processing for '+item+'....'+currentlen+'%');
                $.ajax({
            async:true,
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{stncode,br_code,cust_code,trans_date_from,trans_date_to,inv_date,user_name,category,inv_amt_threshold},
            dataType:"text",
            success:function(data)
            {
            if(currentlen==100){
                $('#invgenbtn').attr({"disabled": false, "readonly": false});
                stopprogress();
                swal('Invoices has been Generated successfully');
            }
            }
            });
        }, 3000 * index);
}
</script>
<script>

function sleep(ms) {
  return new Promise(
    resolve => setTimeout(resolve, ms)
  );
}

function increaseloading(loading){
    $(".progress-bar").width(loading+"%");
    $('.progress-bar').html("<p>Please wait.... Invoice is now Processing for '+cust_code+'...and '+loading+'%</p>");
    //return 1;
}
</script>
<script>
   function progress(){
        //start progress
    // $loadingtime = 1;
    // $(".progress-bar").animate({
    //     width: "100%"
    // }, $loadingtime);
    $(".progress-bar").css("width","100%");
    $('#header').css('filter','blur(5px)');
    $('nav').css('filter','blur(5px)');
    $('section').css('filter','blur(5px)');
    $('footer').css('filter','blur(5px)');
    }
    //stop progress-bar
    function stopprogress(){
     $(".progress-bar").css("width","0%");
     $('#header').css('filter','');
    $('nav').css('filter','');
    $('section').css('filter','');
    $('footer').css('filter','');
    }
</script>
<script>
    function generateinvoice1(){
        if($('#category').val().trim()==''){
            swal("Please Select Category");
            $('#category').css('border-color','red');
        }
        else{
            $('#invgenbtn').attr('type','submit');
        }
    }
</script>
    <script>
     //Fetch Branch Name
     $('#br_code').focusout(function(){
        var branchlookup = $('#br_code').val();
        var br_stncode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{branchlookup:branchlookup,br_stncode},
            dataType:"text",
            success:function(data)
            {
            $('#br_name').val(data);
            }
    });
        $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{br_invno:branchlookup,br_stncode},
            dataType:"text",
            success:function(invno)
            {
            $('#inv_no').val(invno);
            }
    });

    });
     //Fetch Customer Name
     $('#cust_code').focusout(function(){
        var customerlookup = $('#cust_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{customerlookup:customerlookup},
            dataType:"text",
            success:function(data)
            {
            $('#cust_name').val(data);
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
            if($('#stncode').val() == '')
            {
                var superadmin_stn = $('#stncode1').val();
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

</script>
<script>
function fetchcustcode(){
    var branch8 = $('#br_code').val();
    var comp8 = $('#stncode').val();
       $.ajax({
       url:"<?php echo __ROOT__ ?>invoicecontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(ratecustcode)
       {
           autocomplete(document.getElementById("cust_code"), ratecustcode); 
       }
       });
       $.ajax({
       url:"<?php echo __ROOT__ ?>invoicecontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(ratecustname)
       {
           autocomplete(document.getElementById("cust_name"), ratecustname); 
       }
       });

       //check for branch category and change category select option to similar type
       $.ajax({
       url:"<?php echo __ROOT__ ?>invoicecontroller",
       method:"POST",
       data:{branch12:branch8,comp12:comp8},
       dataType:"text",
       success:function(brcategory)
       {
           $('#category').val(brcategory);
           invamtthreshhold();
       }
       });
}
</script>
<script>
//show or hide Invoice amount threshhold textbox while changing category
    function invamtthreshhold(){
        if($('#category').val()=='CRO'){
            $('#inv_amt_threshold_field').css('display','block');
        }else{
            $('#inv_amt_threshold_field').css('display','none');
        }
    }
</script>
<script>
            //fetch code of entering name
            $('#cust_name').focusout(function(){
            var cccust_name = $(this).val();
            var cc_namestn = $('#stncode').val();
            var cc_namebr = $('#br_code').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{cccust_name:cccust_name,cc_namestn:cc_namestn,cc_namebr:cc_namebr},
            dataType:"text",
            success:function(cust_code)
            {
                if(cust_code.trim()==''){

                }else{
                $('#cust_code').val(cust_code);
                }
            }
            });
            });
</script>
<script> 
    function resetFunction(){
         $('#invgenbtn').attr({"disabled": false, "readonly": false});
    }
</script>