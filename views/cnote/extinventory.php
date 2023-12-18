<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'inventory';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;

    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        //if($verify_user->rowCount()=='0'){
            return $access='False';
     //    }
     //    else{
     //        return $access='True';
     //    }
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
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Inventory</p>
        <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>cnotesaleslist"><button type="button" class="btn btn-danger btnattr" >
                        <i class="fa fa-times" ></i>
                </button></a>

                </div>
             
               </div> 
               <div class="content panel-body">
               <div class="tab_form" style="padding:20px">
               <table class="table" cellspacing="0" cellpadding="0" align="center">	
        <!-- First Row -->
        <tr>		
        <td style="border-top:0px">Company Code</td>
        <td style="border-top:0px"><input id="stncode" name="stncode" readonly type="text" value="" class="form-control input-sm stncode" style="text-transform:uppercase;"></td>
            
        <td style="border-top:0px">Company Name</td>
        <td style="border-top:0px">
            <input id="stnname" name="stnname" readonly value="" class="form-control input-sm" type="text">
        </td>
        <td style="border-top:0px">Sales PO Number</td>
        <td style="border-top:0px"><div class="autocomplete col-sm-12">
        <input id="po_number" name="po_number" class="form-control input-sm br_code" type="text" autofocus value="">
        </div>
        </td>
</tr>
</table>
<div class="align-right btnstyle" >

<button class="btn btn-info btnattr_cmn" type="submit" id="inventory_btn">
Apply <i class="fa fa-filter bigger-110"></i>                         
</button> 

</div>
</div>
<!-- <div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">PO Detail</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
        </div>
        <table class="table" cellspacing="0" cellpadding="0" align="center">	
        <tr>		
        <td style="border-top:0px">Serial Type</td>
        <td style="border-top:0px"><input id="serial" name="serial" readonly type="text" value="" class="form-control input-sm stncode" style="text-transform:uppercase;"></td>
            
        <td style="border-top:0px">Total Cnote</td>
        <td style="border-top:0px">
            <input id="total_cnote" name="total_cnote" value="" readonly class="form-control input-sm" type="text">
        </td>
        <td style="border-top:0px">Cnote Start</td>
        <td style="border-top:0px"><div class="autocomplete col-sm-12">
        <input id="cnote_start" name="cnote_start" class="form-control input-sm br_code" type="text" readonly value="">
        </div>
        </td>
        <td style="border-top:0px">Cnote End</td>
        <td style="border-top:0px"><div class="autocomplete col-sm-12">
        <input id="cnote_end" name="cnote_end" class="form-control input-sm br_code" type="text" readonly value="">
        </div>
        </td>
</tr>
</table>

</div> -->
<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Inventory</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"><div class="loader"></div></div>
        </div>
        <table id="data-table" class="table" cellspacing="0" cellpadding="0" align="center">	
        <!-- First Row -->
        <tr>		
            <th>  </th>
            <th>Serial Type</th>
            <th>Total Cnote</th>
            <th>Cnote Start</th>
            <th>Cnote End</th>
            <th>Procured</th>
            <th>Printed</th>
            <th>Allotted</th>
            <th>Billed</th>
            <th>Invoiced</th>
        </tr>
        <tr>
            <td id=""></td>
            <td id="serial"></td>
            <td id="total_cnote"></td>
            <td id="cnote_start"></td>
            <td id="cnote_end"></td>
            <td id="proc"></td>
            <td id="prin"></td>
            <td id="allot"></td>
            <td id="billd"></td>
            <td id="invced"></td>
        </tr>
</table>  
</div>
</div>
</section>
<script>
         //Fetch Inventory Data
    $('#inventory_btn').click(function(){
        var extpo_inventory = $('#po_number').val();
        var extstn_inventory = $('#stncode').val();
        var extcust_inventory = '<?=$datafetch['cust_code']?>';
        $('.loader').css('display','block');
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{extpo_inventory,extstn_inventory,extcust_inventory},
            dataType:"text",
            success:function(data)
            {
               $('.loader').css('display','none');
            var value = data.split(",");
            $('#serial').html(value[0]);
            $('#total_cnote').html(value[1]);
            $('#cnote_start').html(value[2]);
            $('#cnote_end').html(value[3]);
            $('#proc').html(value[1]);
            $('#prin').html(value[5]);
            $('#allot').html(value[6]);
            $('#billd').html(value[7]);
            $('#invced').html(value[8]);
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
            }
    });
  });
</script>