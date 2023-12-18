<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'accountspay';
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

if(isset($_POST['cc_code'])){
    //echo json_encode($_POST);exit;

    $cc_code = $_POST['cc_code'];
    $cc_name = $_POST['cc_name'];
    $stncode = $_POST['stncode'];
    $br_code = $_POST['br_code'];
    $br_name = $_POST['br_name'];
    $trans_date_from = $_POST['trans_date_from'];
    $trans_date_to = $_POST['trans_date_to'];
   // $cust_code = $_POST['cust_code'];

    //get cash cnotes
  //  $cnotes = $db->query("SELECT * FROM ccinvoice");
    //$sum = $db->query("SELECT sum(amt) as invamt FROM cash WHERE comp_code='$stncode' AND br_code='$br_code' AND cc_code='$cc_code' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to'")->FETCH(PDO::FETCH_OBJ);
   

    // $insert = $db->query("INSERT INTO ccinvoice (ccinvid,ccinvno, ccinvdate, ccinvamt, svcname, cctotcons, cctotcomm)
    // SELECT COUNT(tdate) as count,COUNT(tdate) as count,tdate,SUM(ramt) as amount,c.br_code,c.cc_code,(select spl_discount from collectioncenter as cc where cc.cc_code=c.cc_code) as spldiscount FROM cash AS c where comp_code='AMB' AND br_code='$br_code' AND cc_code='$cc_code' AND tdate BETWEEN '$trans_date_from' AND '$trans_date_to' GROUP BY c.tdate,c.cc_code,c.br_code");




}
?>
<style>
#content{
    padding-top: 25px;
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
                                            <a href="#">Dashboard</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Accounts pay Invoice list</li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Accounts Pay</li>
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
  
          if ( isset($_GET['success']) && $_GET['success'] == 10 )
        {
            echo 

        "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Collection Invoice Generated successfully.
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
            echo "<script> swal('There is No transaction to Generate Accounts Pay'); </script>";
                     
        }

        ?>
                
             </div>

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">New Accounts Pay</p>
         <div class="align-right" style="margin-top:-35px;">

       
        <a style="color:white" href="<?php echo __ROOT__ ?>accountspayinvoicelist"><button type="button" class="btn btn-danger btnattr" >
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
       <form method="POST" _action="<?php echo __ROOT__ ?>invoice" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="configform" enctype="multipart/form-data">


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
            <input id="br_code" name="br_code" class="form-control input-sm text-uppercase br_code" type="text" autofocus value="<?=(isset($br_code)?$br_code:'')?>" onkeyup="fetchcustcode()">
                </div>
            </td>
            <td><div class="col-sm-12">
            <input id="br_name" name="br_name" value="<?=(isset($br_name)?$br_name:'')?>"  tabindex="-1"  class="form-control input-sm"  type="text"></div>
            </td>
            <td>Collection Center</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input value="<?=(isset($cc_code)?$cc_code:'')?>" class="form-control text-uppercase" name="cc_code" type="text"  id="cc_code">
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form " value="<?=(isset($cc_name)?$cc_name:'')?>"  tabindex="-1"  name="cc_name" type="text" id="cc_name"></div>
            </td>
            
        </tr>
                      
        </tbody>
        </table>
          <table class="table" style="width:50%;float:left" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>
        <td>Transaction Between</td>
        <td>
        <div class="autocomplete col-sm-12">
              <input class="form-control input-sm"  name="trans_date_from" type="date" id="trans_date_from" value="<?=(isset($trans_date_from)?$trans_date_from:$month_ini->format('Y-m-d'))?>">
              </div>
        </td>	
        <td>&nbsp;to</td>
        <td>
            <input id="trans_date_to" name="trans_date_to" type="date" class="form-control input-sm" value="<?=(isset($trans_date_to)?$trans_date_to:$month_end->format('Y-m-d'));?>">
        </td>
        <td>Invoice Date</td>
        <td>
        <input  type="date" id="inv_date" name="inv_date" class="form-control input-sm lastdate" value="<?=date('Y-m').'-05'?>">
           
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
        <!-- <a class='btn btn-success btn-minier'   target='_blank' data-rel='tooltip' title='Print' id="collectionprint">
                          <i class='ace-icon fa fa-print bigger-130'></i>
    </a> -->
    <button type="button" class="btn btn-success btnattr_cmn"  name="generateinvoice" id="invgenbtn" onclick="add()" value='1'>
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Accounts Pay List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
        </div>
        <!-- <div  style="height:440px;overflow-y:scroll"> -->
<table id="data-table1" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>
            
                    <th >S.No</th>
                    <th >CCinvoice No</th>
                    <th >Date</th>
                    <th >Branch Code</th>
                    <th >total Customer</th>
                    <!-- <th >V-Weight</th> -->
                    <th >Commission</th>
                    <th >Status</th>
                    <th >CC code</th>
                    <th >Action Items</th>
              </tr>
    </thead>
              <tbody>
                  <!-- <?php 
                  
                                  
                            $sno=0;
                            $cnotes = $db->query("SELECT * FROM ccinvoice");
                             while ($row = $cnotes->fetch(PDO::FETCH_OBJ))
                              {


                                $sno++;
                                echo "<tr>";
                                echo "<td class='center first-child'>
                                        <label>
                                           <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                            <span class='lbl'></span>
                                         </label>
                                      </td>";
                               
                                echo "<td>".$sno."</td>";
                                echo "<td>".$row->ccinvno."</td>";
                                echo "<td>".$row->ccinvdate."</td>";
                                echo "<td>".$row->svcname."</td>";
                                echo "<td>".$row->cctotcons."</td>";
                                echo "<td>".$row->cctotcomm."</td>";
                                echo "<td>".$row->status."</td>";
                                echo "<td>".$row->cc_code."</td>";
                                echo "<td><a class='btn btn-success btn-minier' href='collectioncenterinvoice?collectioninvoice=".$row->cc_code."&fromdate=".$row->fromdtae."&todate=".$row->todate."&stncode=".$row->station_code."&br_code=".$row->svcname."&ccinvno=".$row->ccinvno."'  target='_blank' data-rel='tooltip' title='Print'>
                                <i class='ace-icon fa fa-print bigger-130'></i>
                               </a></td>";
                              
                                echo "</tr>";
                                //window.open("collectioncenterinvoice?collectioninvoice="+cc_code+"&fromdate="+trans_date_from+"&todate="+trans_date_to+"&stncode="+stncode+"&br_code="+br_code+"");                           
                          
                         
                    ?> -->

                   
                 </tbody>
               
                 <!-- <?php      }  ?> -->
            </table>
            
              <!-- </div> -->
 
              </div>
             
            <!-- <div style="border:1px solid #2F70B1;margin-top:6px;background:#fff;"> -->
 
            <!-- <table id="formPOSpurchase" class="table" style="width:30%;text-align:left" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <tr> 
         <!-- <td>Total</td>
            <td>
            <input  id="total" name="total" value="<?=(isset($sum->invamt)?number_format($sum->invamt,2):'')?>" class="form-control col-sm-1 input-sm"  type="text" >
            </td> -->
           
    <!-- </tr> 
        </tbody></table>  -->
              <!-- </div> -->
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
    function add()
    {
        
        var cc_code = $('#cc_code').val();
        var brcode =  $('#br_code').val();

if(brcode==''){
    swal("Please Enter branch code");
   

}
else if(cc_code==''){
        swal("Please Enter cc code");
    }
else{
    $('#invgenbtn').html("<i class='fa fa-spinner'></i>&nbsp; Save");
    $('#invgenbtn').attr('type','submit');
        $('#invgenbtn').attr('name','addcollection');
       
}
      
    }
</script>

<script>
    //href='collectioncenterinvoice?invoicenumber="<?php $cc_code ?>"'
    //Fetch Branch code
    $('#br_name').keyup(function(){
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
    $(document).ready(function(){
        $("#collectionprint").click(function(){
            var cc_code = $("#cc_code").val();
   var trans_date_from = $("#trans_date_from").val();
   var trans_date_to = $("#trans_date_to").val();
   var stncode = $("#stncode").val();
   var br_code = $("#br_code").val(); 
   var commission = '<?php    ?>';
   $.ajax({
    url:"<?php echo __ROOT__ ?>billingcontroller",
    method:"POST",
    data:{cc_code,trans_date_from,trans_date_to,stncode,br_code,action : 'invoice'},
    success:function(msg){
       
        window.open("collectioncenterinvoice?collectioninvoice="+cc_code+"&fromdate="+trans_date_from+"&todate="+trans_date_to+"&stncode="+stncode+"&br_code="+br_code+"");
    }
   });
        });
    })
</script>
<!-- <script>
      $(document).ready(function(){
        $("#collectionprint").click(function(){

  
          window.open("collectioncenterinvoice?collectioninvoice="+cc_code+"&fromdate="+trans_date_from+"&todate="+trans_date_to+"&stncode="+stncode+"&br_code="+br_code+"");
      
     
        });
    });  
</script> -->



<script>
    // function newinvoicerequest(){
    //     $('#invgenbtn').attr({"disabled": true, "readonly": true});

    //     var stncode = $('#stncode').val();
    //     var br_code = $('#br_code').val();
    //     var cc_code = $('#cc_code').val();
    //     var trans_date_from = $('#trans_date_from').val();
    //     var trans_date_to = $('#trans_date_to').val();
    //     var inv_date = $('#inv_date').val();
    //     var user_name = $('#user_name').val();
    //     var category = $('#category').val();
    //     var inv_amt_threshold = $('#inv_amt_threshold').val();
    //     if($('#category').val().trim()==''){
    //         swal("Please Select Category");
    //         $('#category').css('border-color','red');
    //         $('#invgenbtn').attr({"disabled": false, "readonly": false});
    //     }
    //     // else if($('#category').val().trim()=='CRO'){
    //     //     swal('There is No transaction to Generate Invoice');
    //     // }
    //     else if($('#cc_code').val().trim().toUpperCase()=='ALL' || $('#cc_code').val().trim()==''){
    //         progress();
    //         $('.progress-bar').html('<p>Please wait.... Fetching Customers');
    //         $.ajax({
    //         async:false,
    //         url:"<?php echo __ROOT__ ?>invoicecontroller",
    //         method:"POST",
    //         data:{newinv_ccodes_stncode:stncode,newinv_ccodes_br_code:br_code,newinv_ccodes_trans_date_from:trans_date_from,newinv_ccodes_trans_date_to:trans_date_to,newinv_category:category,newinv_inv_amt_threshold:inv_amt_threshold},
    //         dataType:"json",
    //         success:function(customerslist)
    //         {
    //         stopprogress();
    //         //console.log(customerlist.join());
    //         if(customerslist==0){
    //             swal('There is No transaction to Generate Invoice'); 
    //             $('#invgenbtn').attr({"disabled": false, "readonly": false});
    //         }
    //         else{
    //                 customerslist.forEach(eachinvoice);               
    //         }
    //         }
    //         });
    //     }
    //     else{ 
    //     progress();
    //     $.ajax({
    //         url:"<?php echo __ROOT__ ?>invoicecontroller",
    //         method:"POST",
    //         data:{stncode,br_code,cc_code,trans_date_from,trans_date_to,inv_date,user_name,category,inv_amt_threshold},
    //         dataType:"text",
    //         success:function(data)
    //         {
    //         if(data.trim()=='empty'){
    //             swal('There is No transaction to Generate Invoice'); 
    //             stopprogress();
    //         }
    //         else{
    //             $('.progress-bar').html('<p>Please wait.... Invoice is now Processing for '+cc_code);
    //             stopprogress();
    //             swal('Invoice has been Generated successfully');
    //         }
    //         $('#invgenbtn').attr({"disabled": false, "readonly": false});
    //         }
    //         });
    //     }
    // }
</script>
<script>
function customerlistinvnew(customerslist){
    // customerslist.forEach(eachinvoice);
                }
</script>
<script>
//  function eachinvoice(item,index,array){
//     //console.log(item);
//     setTimeout(() => {
//     var arrlen = array.length;
//     var currentlen = Math.round(100/parseInt(arrlen)*(index+1));
  
//       var stncode = $('#stncode').val();
//                 var br_code = $('#br_code').val();
//                 var trans_date_from = $('#trans_date_from').val();
//                 var trans_date_to = $('#trans_date_to').val();
//                 var inv_date = $('#inv_date').val();
//                 var user_name = $('#user_name').val();
//                 var category = $('#category').val();
//                 var inv_amt_threshold = $('#inv_amt_threshold').val();
//                 var cc_code = item;
//                 $(".progress-bar").width(currentlen+"%");
//                 $('.progress-bar').html('<p>Please wait.... Invoice is now Processing for '+item+'....'+currentlen+'%');
//                 $.ajax({
//             async:true,
//             url:"<?php echo __ROOT__ ?>invoicecontroller",
//             method:"POST",
//             data:{stncode,br_code,cc_code,trans_date_from,trans_date_to,inv_date,user_name,category,inv_amt_threshold},
//             dataType:"text",
//             success:function(data)
//             {
//             if(currentlen==100){
//                 $('#invgenbtn').attr({"disabled": false, "readonly": false});
//                 stopprogress();
//                 swal('Invoices has been Generated successfully');
//             }
//             }
//             });
//         }, 3000 * index);
// }
</script>
<script>

function sleep(ms) {
  return new Promise(
    resolve => setTimeout(resolve, ms)
  );
}

function increaseloading(loading){
    $(".progress-bar").width(loading+"%");
    $('.progress-bar').html("<p>Please wait.... Invoice is now Processing for '+cc_code+'...and '+loading+'%</p>");
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
    // function generateinvoice1(){
    //     if($('#category').val().trim()==''){
    //         swal("Please Select Category");
    //         $('#category').css('border-color','red');
    //     }
    //     else{
    //         $('#invgenbtn').attr('type','submit');
    //     }
    // }
</script>
<script>
    function getKeyByValue(object, value) {
        return Object.keys(object).find(key => object[key] === value);
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
carnr = "<?php print($stationcode); ?>"
  console.log(carnr);
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

$brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND branchtype='C'");
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
function cc_autopopulate(){
   var branch8 = $('#br_code').val();
   var comp8 = $('#stncode').val();
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
   getcollectioncentersjson();
}
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

$(document).ready(function(){
     
     $('#cc_code').focusout(function(){
         var cc_code = $('#cc_code').val().toUpperCase().trim();
         //console.log(collectioncenters);

        if(collectioncenters[cc_code]!==undefined){
            //console.log(collectioncenters[cc_code]);
            $('#cc_name').val(collectioncenters[cc_code]);
            $('#cccode_validation').css('display','none');
            bacnoteverify();
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
            bacnoteverify();
            }
            else{
                $('#cccode_validation').css('display','block');
            }
     });
});

</script>
<script> 
    function resetFunction(){
         $('#invgenbtn').attr({"disabled": false, "readonly": false});
    }
</script>