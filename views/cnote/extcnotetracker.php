<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'extcnotetracker';
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
  ?>
  <style>
      .inv_label{
      font-weight:600;font-size:14px;margin-right:20px;
      }
      .tracking-table th{
        font-weight:400!important;
      }
  </style>
    <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  
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
<input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
    <!-- Tittle -->
    <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Tracking Filter</p>
        <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>cnotesaleslist"><button type="button" class="btn btn-danger btnattr" >
                        <i class="fa fa-times" ></i>
                </button></a>

                </div>
             
               </div> 
               <div class="content panel-body">
               <ul class="nav nav-tabs" id="myTab4">
                <li class="active">
                    <a data-toggle="tab" href="#single">Single</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#bulk">Bulk</a>
                </li>
            </ul>
               <div class="tab_form">
               <div class="tab-content" style="border:0!important;">
                     <!-- Tab 1 -->
                     <div id="single" class="tab-pane active" style="margin-left: 25px;">
               <table class="table" cellspacing="0" cellpadding="0" align="center">	
                        <tr>		
                        <td style="border-top:0px">Company Code</td>
                        <td style="border-top:0px"><input id="stncode" name="stncode" readonly type="text" value="" class="form-control input-sm stncode" style="text-transform:uppercase;"></td>
                            
                        <td style="border-top:0px">Company Name</td>
                        <td style="border-top:0px">
                            <input id="stnname" name="stnname" readonly value="" class="form-control input-sm stnname" type="text">
                        </td>
                        <td style="border-top:0px">Type</td>
                        <td style="border-top:0px">
                            <select id="tracking_type_single" name="tracking_type_single" autofocus class="form-control input-sm">
                            <option value="inbound">Inbound</option>
                            <!-- <option value="outbound">Outbound</option> -->
                        </td>
                        <td style="border-top:0px">Cnote Number</td>
                        <td style="border-top:0px"><div class="autocomplete col-sm-12">
                        <input id="cnote_number_single" name="cnote_number_single" class="form-control input-sm br_code" type="text" value="">
                        </div>
                        </td>
                </tr>
                </table>
                <div class="align-right btnstyle" >

                    <button class="btn btn-primary btnattr_cmn" type="button" id="tracking_btn_single">
                      <i class="fa fa-search bigger-110"></i>  Track                       
                    </button> 

                    </div>
                </div>
                     <!-- Tab 2 -->
                     <div id="bulk" class="tab-pane" style="margin-left: 25px;">
                <table class="table" cellspacing="0" cellpadding="0" align="center">	
                        <tr>		
                        <td style="border-top:0px">Company Code</td>
                        <td style="border-top:0px"><input id="" name="" readonly type="text" value="" class="form-control input-sm stncode" style="text-transform:uppercase;"></td>
                            
                        <td style="border-top:0px">Company Name</td>
                        <td style="border-top:0px">
                            <input id="" name="" readonly value="" class="form-control input-sm stnname" type="text">
                        </td>
                        <td style="border-top:0px">Type</td>
                        <td style="border-top:0px">
                            <select id="tracking_type_bulk" name="tracking_type_bulk" autofocus class="form-control input-sm">
                            <option value="inbound">Inbound</option>
                            <!-- <option value="outbound">Outbound</option> -->
                        </td>
                        <td style="border-top:0px">Cnote Start</td>
                        <td style="border-top:0px"><div class="autocomplete col-sm-12">
                            <input id="cnote_start_bulk" name="cnote_start_bulk" class="form-control input-sm br_code" type="text" value="">
                        </div>
                        </td>
                        <td style="border-top:0px">Cnote End</td>
                        <td style="border-top:0px"><div class="autocomplete col-sm-12">
                            <input id="cnote_end_bulk" name="cnote_end_bulk" class="form-control input-sm br_code" type="text" value="">
                        </div>
                        </td>
                </tr>
                        <tr>		
                        <td style="border-top:0px">Customer Code</td>
                        <td style="border-top:0px"><input id="trk_custcode" readonly name="trk_custcode" type="text" value="<?=$datafetch['cust_code']?>" class="form-control input-sm" style="text-transform:uppercase;"></td>
                            
                        <td style="border-top:0px">Customer Name</td>
                        <td style="border-top:0px">
                            <input id="trk_custname" readonly name="trk_custname" value="<?=$db->query("SELECT cust_name FROM customer WHERE cust_code='".$datafetch['cust_code']."' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ)->cust_name?>" class="form-control input-sm" type="text">
                        </td>
                        <td style="border-top:0px">Destination Code</td>
                        <td style="border-top:0px"><input id="trk_destcode" name="trk_destcode" type="text" value="" class="form-control input-sm" style="text-transform:uppercase;"></td>
                            
                        <td style="border-top:0px">Destination Name</td>
                        <td style="border-top:0px">
                            <input id="trk_destname" name="trk_destname" value="" class="form-control input-sm" type="text">
                        </td>
                        <td style="border-top:0px">Booking date</td>
                        <td style="border-top:0px">
                            <input id="trk_bdate_frm" name="trk_bdate_frm" class="form-control input-sm" type="date">to
                            <input id="trk_bdate_to" name="trk_bdate_to" class="form-control input-sm" type="date">
                        </td>
                </tr>
                   
                </table>
                <div class="align-right btnstyle" >

                <button class="btn btn-success btnattr_cmn" type="button" id="tracking_btn_bulk">
                Apply <i class="fa fa-filter bigger-110"></i>                         
                </button> 

                </div>
                </div>
                </div>

</div>

<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Tracking List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"><div class="loader"></div></div>
        </div>
        <table id="data-table" class="table tracking-table" cellspacing="0" cellpadding="0" align="center">	
       
</table>  
</div>
</div>
</section>
<script>
         //Fetch Inventory Data
    $('#tracking_btn_single').click(function(){
        if($('#cnote_number_single').val().trim()==''){
            swal('Please enter cnotenumber to proceed');
            process.exit();
        }
        var cno_tracking = $('#cnote_number_single').val();
        var stn_tracking = $('#stncode').val();
        var tracking_type = $('#tracking_type_single').val();
        var trk_custcode = '<?=$datafetch['cust_code']?>';
        if(trk_custcode.trim()==''){
            swal('Could not process rour request....Invalid customer code!!')
        }
        else{
        $('.loader').css('display','block');
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{cno_tracking,stn_tracking,tracking_type,trk_bdate_to:'',trk_bdate_frm:'',trk_destcode:'',cnoend_tracking:'1',trk_custcode},
            dataType:"text",
            success:function(data)
            {
            $('.loader').css('display','none');
            $('.tracking-table').html(data);
            }
        });
        }
    });
</script>
<script>
         //Fetch Inventory Data
    $('#tracking_btn_bulk').click(function(){

        var cno_tracking = $('#cnote_start_bulk').val();
        var cnoend_tracking = $('#cnote_end_bulk').val();
        var stn_tracking = $('#stncode').val();
        var tracking_type = $('#tracking_type_bulk').val();
        var trk_custcode = '<?=$datafetch['cust_code']?>';
        var trk_destcode = $('#trk_destcode').val();
        var trk_bdate_frm = $('#trk_bdate_frm').val();
        var trk_bdate_to = $('#trk_bdate_to').val();
        if(trk_custcode.trim()==''){
            swal('Could not process rour request....Invalid customer code!!')
        }
        if(cno_tracking.trim()=='' || cnoend_tracking.trim()==''){
            swal('Please enter cnote range to proceed');
        }
        else{
        $('.loader').css('display','block');
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{cno_tracking,stn_tracking,tracking_type,trk_bdate_to,trk_bdate_frm,trk_destcode,cnoend_tracking,trk_custcode},
            dataType:"text",
            success:function(data)
            {
            $('.loader').css('display','none');
            $('.tracking-table').html(data);
            }
    });
        }
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
            $('.stncode').val(data[0]);
            $('.stnname').val(data[1]);
            }
    });
  });
  </script>