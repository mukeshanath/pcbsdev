<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'cashreceipt';
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

$sessionbr_code = $datafetch['br_code'];
if(empty($sessionbr_code)){
    $branch_code = "";
}else{
    $branch_code = $datafetch['br_code'];
    $getbrname = $db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND br_code='$sessionbr_code'")->fetch(PDO::FETCH_OBJ);
    $branch_name = $getbrname->br_name;

}

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
.autocomplete-items div {
    width: 110px;
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
                                        <li class="list-inline-item">Cash Receipt list</li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Cash Receipt</li>
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
  
          if ( isset($_GET['success']) && $_GET['success'] == 11 )
        {
             
            echo "<script> swal('Cash Receipt Generated successfully.'); </script>";

        }

        if ( isset($_GET['emptycr']))
        {
            echo "<script> swal('There is No transaction to Generate Cash Receipt'); </script>";
                     
        }

        ?>
                
             </div>

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">New Cash Receipt</p>
         <div class="align-right" style="margin-top:-35px;">

       
        <a style="color:white" href="<?php echo __ROOT__ ?>cashreceiptlist"><button type="button" class="btn btn-danger btnattr" >
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
            <input id="br_code" name="br_code" <?=((!empty($branch_code)? 'readonly' : ''))?> class="form-control input-sm text-uppercase br_code" type="text" <?=((empty($branch_code)? 'autofocus' : ''))?> value="<?=(isset($branch_code)?$branch_code:'')?>"> 
                </div>
            </td>
            <td><div class="col-sm-10">
            <input id="br_name" name="br_name" value="<?=(isset($branch_name)?$branch_name:'')?>"  tabindex="-1"  class="form-control input-sm"  type="text" readonly></div>
            </td>
            <td>Collection Center</td>
            <td style="width:100px;">
            <div class="autocomplete">
            <input value="<?=(isset($cc_code)?$cc_code:'')?>" <?=((!empty($branch_code)? 'autofocus' : ''))?> class="form-control text-uppercase" name="cc_code" type="text"  id="cc_code">
                </div>
            </td>
            <td><div class="col-sm-10">
            <input class="form-control border-form " value="<?=(isset($cc_name)?$cc_name:'')?>" name="cc_name" type="text" id="cc_name"></div>
            </td>
           
            
        </tr>
                      
        </tbody>
        </table>
          <table class="table" style="width:70%;float:left" cellspacing="0" cellpadding="0" align="center">	
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
        <td>Gstin</td>
        <td>
        <input class="form-control border-form "  name="gstin" type="text" id="gstin" onfocusout="(($(this).val().trim().length>0)?gst_val(this):$('#gst_val').css('display','none'))">
        <p id="gst_val" class="form_valid_strings">Enter valid GSTIN.</p>
    </div>
           
        </td>
        
        <td>Cr Date</td>
        <td>
        <input type = "date" name="cr_date" class="form-control" id = "cr_date" value="<?=date('Y-m-d')?>">
           
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
     <table class="table table-bordered table-hover">
                        <tr style="color: #707070;font-weight: 400;background: repeat-x #f2f2f2;margin-left:10px;width:10px;">
                            <th>Shipper Details</th>
                            <!-- <th>Shipper Address</th>
                            <th>Shipper Mobile</th> -->
                            
                        </tr>
                        
                        <tr >
                    <td style="width:20%"><input type="text" oninput="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" maxlength="10" class="form-control" placeholder="Shipper Mobile" name="shp_mob" id="shp_mob"></td>
                    <td style="width:20%"><input type="text" class="form-control" placeholder="Shipper Name"  name="customer_name" id="customer_name"></td>
                    <td><input type="text" class="form-control" placeholder="Shipper Address" name="shp_addr" id="shp_addr"></td>
                    <td style="width:20%"><input type="text" class="form-control" placeholder="Shipper Pincode" name="shp_pin" id="shp_pin"></td>
                   

                    </tr>
                </table>
     <table>
  
        <div class="align-right btnstyle" >
        <!-- <a class='btn btn-success btn-minier'   target='_blank' data-rel='tooltip' title='Print' id="collectionprint">
                          <i class='ace-icon fa fa-print bigger-130'></i>
    </a> -->
    <button type="button" class="btn btn-success btnattr_cmn"  name="generatecashreceipt" id="cashreceiptbtn" onclick="genrate_cashreceipt()">
       <i class="fa fa-save"></i>&nbsp; Generate
    </button>

                                                            
     
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction();">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>   
        </div>
        </table> 

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
    function genrate_cashreceipt()
    {
        var gstin = $('#gstin').val();
        var brcode =  $('#br_code').val();
        var customer_name = $('#customer_name').val();

        if(brcode.trim()==''){
            swal("Please Enter branch code");
        }
        else if(gstin.trim()==''){
            swal("Please Enter gst Code");
        }
        else if(customer_name.trim()==''){
            swal("Please Enter Name");
        }
        else{
            $('#cashreceiptbtn').html("<i class='fa fa-spinner'></i>&nbsp; Save");
            $('#cashreceiptbtn').attr('type','submit');
            $('#cashreceiptbtn').attr('name','addcashreceipt');     
        }
    }
</script>
<script>
    $(document).ready(function(){
      $("#gstin").on('focusout', function (){
        var gstin = $('#gstin').val(); 
        var branch_code  = $('#br_code').val();      
        var stationcode = '<?=$stationcode?>';
        console.log(stationcode);
        $.ajax({
            url:"<?php echo __ROOT__ ?>cashreceipt",
            method:"POST",
            data:{gstin,branch_code,stationcode,action : 'fetch_shipper'},
            dataType:"text",
            
            success:function(value)
            {
              console.log(value);
              var shp_details = JSON.parse(value);
              $('#shp_mob').val(shp_details[2]);
              $('#customer_name').val(shp_details[0]);
              $('#shp_addr').val(shp_details[1]);
              $('#shp_pin').val(shp_details[3]);
            }
    });
    });
  });
</script>
<script>
    $(document).ready(function(){
          //fetch name of entering name
          $('#br_code').focusout(function(){
            var br_code = $(this).val();
            var station = '<?=$stationcode;?>';
            $.ajax({
            url:"<?php echo __ROOT__ ?>cashreceipt",
            method:"POST",
            data:{station,br_code,action : 'branch_name'},
            dataType:"text",
            success:function(data)
            {
                console.log(data);
                var res = JSON.parse(data);
                $('#br_name').val(res[0]);
                autocomplete(document.getElementById("cc_code"), res[1]); 

            }
            });
        });
    });      
</script>
<script>
    $(document).ready(function(){
          //fetch name of entering name
          $('#cc_code').focusout(function(){
            var cc_code = $(this).val();
            var station = '<?=$stationcode;?>';
            $.ajax({
            url:"<?php echo __ROOT__ ?>cashreceipt",
            method:"POST",
            data:{station,cc_code,action : 'cc_name'},
            dataType:"text",
            success:function(data)
            {
                console.log(data);
                 $('#cc_name').val(data);
        

            }
            });
        });
    });      
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
