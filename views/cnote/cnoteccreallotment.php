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

          function get_cnote_count($c_start,$e_start){
            $key = array();
            for ($i = $c_start; $i <= $e_start; $i++) {
                array_push($key, $i);
            }
            return sizeof($key);
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
                                            <li class="list-inline-item">CC ReAllocation</li>
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
                    if ( isset($_GET['success']) && $_GET['success'] == 2 || isset($_GET['unsuccess']) && $_GET['unsuccess'] == 3)
                {
                    if(empty($_GET['success'])){
                        $alertcolor = 'danger';
                        $err_msg = 'Invalid Cnote details pls check!';
                    }else{
                        $alertcolor = 'success';
                        $err_msg = 'Cnotes are reallocated to collection center.';
                    }
                        echo 
                "<div class='alert alert-".$alertcolor." alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                ".$err_msg."
                </div>";
                }

                ?>

                </div>
                <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>cnoteccreallotment" accept-charset="UTF-8" class="form-horizontal" id="formcnotesales" enctype="multipart/form-data">
                <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">

        <!-- Tittle -->
        <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Collection Center ReAllocation</p>
        <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
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
              <input id="pre_cc_code" name="pre_cc_code" class="form-control input-sm" type="hidden" >
              </div>
        </td>	
        <td>CollectionCenter Name</td>
        <td><div class="autocomplete col-sm-12">
                <input id="cc_name" name="cc_name" type="text" value="" class="form-control input-sm text-uppercase">
            </div>
        </td>
        <td>Serial Type</td>
        <td>
        <select  type="text" id="cnote_serial_cn" name="cnote_serial_cn" class="form-control input-sm">
            <option value="0">Select Serial</option>
            <?php echo loadgroup($db,$stationcode); ?>
            </select>
        </td>	
       

        </tr> 
        <tr>
        		   <td>Cnote start</td>
                       <td><div class="autocomplete col-sm-12">
                       <input id="cnotestart" name="cnote_start" class="form-control input-sm" type="number">
                    
                       </div>
                       </td>
                       <td>Cnote End</td>
                       <td><div class="col-sm-12">
                            <input id="cnoteend" name="cnote_end"  class="form-control input-sm"  type="number"></div>
                            </div>
                       </td>
                     
                       </tr>
        
        </tbody></table>
        <div class="align-right btnstyle" >

  <button type="button" class="btn btn-info" id="reallocate_cc" name="reallocate_cc" onclick="reallocate();">
                        ReAllocate <i class="fa fa-filter bigger-110"></i>                         
                        </button> 

</div>
     

       
        <!-- Sales Order Details Table -->
            </div>
	</div>
 </div>
        </div>
        </form>

        <div class="col-12 data-table-div" >
              <style>
  .table-search-right {
    float: right;
    margin-left: 10px;
}
.table-search-right input {
    margin-left: 10px;
    height: 30px;
}
#data-table_length, #data-table_filter, #data-table_info, #data-table_paginate, .buttons-excel, .buttons-pdf{
  display:none !important;
}
</style>
  <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Collection Center ReAllocation List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
        </div>
        <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text"></div>
               <div class="table-search-right">Date Filter<input type="month" data-date='defined' class="table-search-right-datefilter this-month" id="datefilter"></div>
           </div>
      <table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
          <thead><tr>
              <th class="center">
                  <label class="pos-rel">
                      <input type="checkbox" class="ace" />
                  <span class="lbl"></span>
                  </label>
                </th>
                      <th>S.N.</th>
                      <th>Cnote Station</th>
                      <th>Cnote Branch</th>
                      <th>CC Code</th>
                      <th>Cnote Serial</th>
                      <th>Cnote Start</th>
                      <th>Cnote End</th>
                      <th>Cnote_count</th>
                      <th>Cnote CC Re-Allotment</th>                   
                      <th>Date</th>
                     
                </tr>
                  </thead>
                <tbody  id="datatable-tbody">
                    <?php 

function get_current_month(){
     $transdate = date('m-d-Y', time());
     $d = date_parse_from_format("m-d-y",$transdate);
     $month = $d["month"];
     return $month;
     }

     $y = date('Y',strtotime(date('Y-m-d H:i:s')));
     
    $conditions = array();    
    if(empty($sessionbr_code)){
      $conditions[] = "stncode='$stationcode'";  

    }else{
      $conditions[] = "stncode='$stationcode' AND br_code='$sessionbr_code'";  

    }
    
      

    $conditions[] = "Month(date_added)='".get_current_month()."' AND Year(date_added)='".$y."'";

   
    $conditons = implode(' AND ', $conditions);
    
$query = $db->query("SELECT * FROM cc_reallotment WITH(NOLOCK) WHERE $conditons order by cc_reallotment_id desc OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");

while ($row = $query->fetch(PDO::FETCH_OBJ))
                            {
                              echo "<tr>";
                              echo "<td class='center first-child'>
                                      <label>
                                        <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                          <span class='lbl'></span>
                                      </label>
                                    </td>";
                              echo "<td>".$row->cc_reallotment_id."</td>";
                              echo "<td>".$row->stncode."</td>";
                              echo "<td>".$row->br_code."</td>";
                              echo "<td>".$row->cc_code."</td>";
                              echo "<td>".$row->cnote_serial."</td>";                            
                              echo "<td>".$row->cnote_start."</td>";
                              echo "<td>".$row->cnote_end."</td>";
                              echo "<td>".get_cnote_count($row->cnote_start,$row->cnote_end)."</td>";
                              echo "<td>".$row->realloted_from."&nbsp;<span id='arrowdata'>&#8596;</span>&nbsp;".$row->cc_code."</td>";
                              echo "<td>".date("d-M-Y",strtotime($row->date_added))."</td>";
                             
                              echo "<td>
							  
                                <div class='hidden-md hidden-lg'>
                                  <div class='inline pos-rel'>
                                    <button class='btn btn-minier btn-yellow dropdown-toggle' data-toggle='dropdown' data-position='auto'>
                                    <i class='ace-icon fa fa-caret-down icon-only bigger-120'></i>
                                    </button>
                                    <ul class='dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close'>
                                      <li>
                                        <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                                        <span class='blue'>
                                        <i class='ace-icon fa fa-eye bigger-120'></i>
                                        </span>
                                        </a>
                                      </li>
                                      <li>
                                        <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                                          <span class='green'>
                                          <i class='ace-icon fa fa-pencil-square-o bigger-120'></i>
                                          </span>
                                        </a>
                                      </li>

                                    <li>
                                    <a  class='tooltip-error bootbox-confirm' data-rel='tooltip' title='Delete'>
                                    <span class='red'>
                                      <i class='ace-icon fa fa-trash-o bigger-120'></i>
                                      </span>
                                      </a>
                                    </li>
                                  </ul>
                                                </div>
                                            </div>
                            </td>";
                                    echo "</tr>";
                            
                                                      
                            }

                            
                        ?>

                    
                  </tbody>
              </table>
                <!-- pagination -->
                <div class="table-paginate-btns" id="setpages">
            <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>

            <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM cc_reallotment WHERE $conditons")->fetch(PDO::FETCH_OBJ);
            // echo json_encode("SELECT COUNT(*) as pages FROM cash WHERE $conditons");
                for($i=1;$i<=ceil($noofpages->pages / 10);$i++){
                  //pagination control
                  if(ceil($noofpages->pages / 10)>5){
                    //dot property
                    if($i==3){
                      $leftdot = '...';
                    }
                    else{
                      $leftdot = '';
                    }
                    if($i==ceil($noofpages->pages / 10)-1){
                      $rightdot = '...';
                    }
                    else{
                      $rightdot = '';
                    }
                    //end dot property
                    //number btn display property
                    if($i==1||$i==2||$i==ceil($noofpages->pages / 10)||$i==round(ceil($noofpages->pages / 10)/2)||$i==round(ceil($noofpages->pages / 10)/2)-1||$i==round(ceil($noofpages->pages / 10)/2+1)){
                      $style = "style='display:'";
                    }else{
                      $style = "style='display:none'";
                    }
                    //end number btn display property
                  }
                  else{
                    $leftdot = '';
                    $rightdot = '';
                    $style= '';
                  }
                  //end pagination control
                  echo "$leftdot<input type='button' $style class='btn btn-primary paginate-btn-nums' value='$i' id='$i'>$rightdot";
                }
              ?>
              <button type="button" class="btn btn-primary paginate-btn-nums" id="next" value="2">Next</button>
            </div>
            <!-- pagination -->
                </div>
        </div>
        </div>
        </div>

</section>

<script>
    function reallocate(){
         if($("#brcode_validation").css("display")==="block")
        {
            swal("Please Enter Branch Code");
        }
        else if($('#cnotestart').val().trim()==''){
            swal("Please Enter Cnote Start Number");
        }
        else if($('#cnotestart').val().trim()==''){
            swal("Please Enter Cnote End Number");
        }
       else if(validate_branch()=='Invalid'){
            swal("Please Enter Valid Cnote Details");
        }else{
           $('#reallocate_cc').attr('type','submit');
        }
    }
</script>

<script>

function validate_branch(){
    var cnote_number = $('#cnotestart').val();
         var stncode  = $('#stncode').val();
         var br_code  = $('#br_code').val();
         var cnote_serial = $('#cnote_serial_cn').val();
         var cnoteend = $('#cnoteend').val();
         $.ajax({
                url:"<?php echo __ROOT__ ?>cnoteccreallotment",
                method:"POST",
                async: false,
                data:{cnoteend,cnote_number,stncode,br_code,cnote_serial,action : 'validate_cnote_number'},
                dataType:"text",
                success:function(result){
                    var res = JSON.parse(result);
                    console.log(res);
                    response12 = res[0];  
                }
                
        });
        return response12;
}
</script>
<script>
    $(document).on('focusout','input[name="cnote_start"]',function(){
        var stncode = $('#stncode').val();
        var cnote_serial = $('#cnote_serial_cn').val();
        var cnote_number = $(this).val();
        var br_code = $('#br_code').val();
        var component = 'ccreallotment';
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnoteccreallotment",
            method:"POST",
            data:{component,br_code,stncode,cnote_serial,cnote_number,action:"fetchreallot"},
            dataType:"json",
            success:function(data)
            {
              console.log(data);
              $('#pre_cc_code').val(data);
            }
    });
    })
</script>


<script>
    $(document).on('change','#cnote_serial_cn',function(){
        var stncode = $('#stncode').val();
        var cnote_serial = $('#cnote_serial_cn').val();
        var cnote_number = $('#cnotestart').val();
        var br_code = $('#br_code').val();
        var component = 'ccreallotment';
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnoteccreallotment",
            method:"POST",
            data:{component,br_code,stncode,cnote_serial,cnote_number,action:"fetchreallot"},
            dataType:"json",
            success:function(data)
            {
              console.log(data);
              $('#pre_cc_code').val(data);
            }
    });
    })
</script>


<script>
     function get_count(start_number,end_number){
    var sub_array = [];
    var super_array =[];
    for (var i=start_number;i<=end_number;i++){
        sub_array = sub_array.slice(0,sub_array.length);
        sub_array.push(i);
    }
    return sub_array.length;
}
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
        // calculateforprocured();
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

<script>
 $(document).on('click','.paginate-btn-nums',function(){   
    var inc = parseInt($(this).val())+1;
            var dec = parseInt($(this).val())-1;
            var curt = parseInt($(this).val());
            var datefilter = $('#datefilter').data('date');
            if(datefilter=='defined'){
                var end = parseInt(<?php echo ceil($noofpages->pages / 10); ?>);
            }else{
                if(res[2].trim()=='Onchange'){
              var end = parseInt(Math.ceil(res[1]/10));
            }else{
              var end = parseInt(<?php echo ceil($noofpages->pages / 10); ?>);
            }
            }
           
            console.log(end);
            if($(this).val()==0||$(this).val()>end){
              process.end;
            }

          var comp_branch;
           var datefilter = $('#datefilter').val().trim();
           var year = datefilter.split("-")[0];
           var month = datefilter.split("-")[1];
           var session_brcode = '<?=$sessionbr_code?>';
           if(session_brcode.length==0){
             comp_branch = "stncode='<?=$stationcode?>'";
           }else{
            comp_branch = "stncode='<?=$stationcode?>' AND br_code='"+session_brcode+"'";

           }

          
           const condition = [comp_branch," Month(date_added)='"+month+"' AND Year(date_added)='"+year+"'"];
             let conditions = condition.join(" AND ");




          $('#datatable-tbody').css('opacity','0.5');
          $('.paginate-btn-nums').removeClass('active');
   
    var dtbquery = "SELECT cc_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial,cc_code FROM cc_reallotment WITH(NOLOCK) WHERE  "+conditions+" order by cc_reallotment_id desc";

      var dtbcolumns = 'cc_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial,cc_code';
      var dtbpageno = ($(this).val()-1)*10;
      console.log("dfgfgh",[dtbpageno,$(this).val()]);
      $.ajax({
            url:"<?php echo __ROOT__ ?>cancellation",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,action: 'cashllish'},
            dataType:"json",
            success:function(datum)
            {
           console.log(datum);
           $('#datatable-tbody').html('');
           var datacount = Object.keys(datum).length;

         const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
          
           var html='';
            for(var i=1;i<=datacount;i++){
              var  tabledate = datum[i]['date_added'];
               let current_datetime = new Date(tabledate)
           let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear() 
           
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['cc_reallotment_id']+"</td>";
              html += "<td>"+datum[i]['stncode']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cc_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_start']+"</td>";
              html += "<td>"+datum[i]['cnote_end']+"</td>";
              html += "<td>"+get_count(datum[i]['cnote_start'],datum[i]['cnote_end'])+"</td>";
              html += "<td>"+datum[i]['realloted_from']+"&nbsp;<span id='arrowdata'>&#8596;</span>&nbsp;"+datum[i]['cc_code']+"</td>";
            
              html += "<td>"+formatted_date+"</td>";
           
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
            
              //action
              html += "</div></td></tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
            }
    });
 //pagination dynamic buttons
 if(end>5){
              $('#'+curt).addClass('active');
              $('.paginate-btn-nums').css('display','none');
              $('#'+curt).css('display','');
              $('#1').css('display','');
              $('#'+inc).css('display','');
              $('#'+dec).css('display','');
              // $('#'+dec).css('display','');
              $('#'+end).css('display','');
              $('#'+end).css('display','');
              $('#prev').css('display','');
              $('#prev').val(dec);
              $('#next').css('display','');
              $('#next').val(inc);
            }
            else{
              $('#'+curt).addClass('active');
              $('#prev').val(dec);
              $('#next').val(inc);
            }
          //end pagination dynamic buttons
  });
</script>
<script>
  $('.table-search-right-text').keyup(function(event){
    // event.preventDefault();
    var fieldvalue = $(this).val();
      sessionStorage.setItem("cashlist", fieldvalue);
       var datefilter = $('#datefilter').val().trim();
       var comp_branch;
           var year = datefilter.split("-")[0];
           var month = datefilter.split("-")[1];
           var session_brcode = '<?=$sessionbr_code?>';
           if(session_brcode.length==0){
             comp_branch = "stncode='<?=$stationcode?>'";
           }else{
            comp_branch = "stncode='<?=$stationcode?>' AND br_code='"+session_brcode+"'";

           }
           const condition = [comp_branch," Month(date_added)='"+month+"' AND Year(date_added)='"+year+"'"];
             let conditions = condition.join(" AND ");

      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT cc_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial,cc_code FROM cc_reallotment WITH(NOLOCK)  WHERE "+conditions+" AND (cnote_start like '%"+fieldvalue+"%' OR cnote_end LIKE '%"+fieldvalue+"%') order by cc_reallotment_id desc";
      var dtbcolumns = 'cc_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial,cc_code';
      var dtbpageno = '0';
      console.log(dtbquery);
     
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>cancellation",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,action: 'cashllish'},
            dataType:"json",
            success:function(datum){
              console.log(datum);
              const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var datacount = Object.keys(datum).length;
            
            var html='';
            console.log(months);
            for(var i=1;i<=datacount;i++){
              var  tabledate = datum[i]['date_added'];
               let current_datetime = new Date(tabledate)
           let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear() 
           html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['cc_reallotment_id']+"</td>";
              html += "<td>"+datum[i]['stncode']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cc_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_start']+"</td>";
              html += "<td>"+datum[i]['cnote_end']+"</td>";
              html += "<td>"+get_count(datum[i]['cnote_start'],datum[i]['cnote_end'])+"</td>";
              html += "<td>"+datum[i]['realloted_from']+"&nbsp;<span id='arrowdata'>&#8596;</span>&nbsp;"+datum[i]['cc_code']+"</td>";
            
              html += "<td>"+formatted_date+"</td>";
           
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
             
             
              //action
              html += "</div></td></tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
            }

            
          }); 
       });
</script>
<script>
  $('#datefilter').change(function(event){
   
    var datefilter = $(this).val().trim();
    var comp_branch;
      var year = datefilter.split("-")[0];
      var month = datefilter.split("-")[1];
      var session_brcode = '<?=$sessionbr_code?>';
           if(session_brcode.length==0){
             comp_branch = "stncode='<?=$stationcode?>'";
           }else{
            comp_branch = "stncode='<?=$stationcode?>' AND br_code='"+session_brcode+"'";

           }
           const condition = [comp_branch," Month(date_added)='"+month+"' AND Year(date_added)='"+year+"'"];
      let conditions = condition.join(" AND ");
      $('#datatable-tbody').css('opacity','0.5');
      var dtbquery = "SELECT cc_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial,cc_code FROM cc_reallotment WITH(NOLOCK) WHERE  "+conditions+" order by cc_reallotment_id desc";
      console.log(dtbquery);
      var dtbcolumns = 'cc_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial,cc_code';
      var dtbpageno = '0';
          $.ajax({
            url:"<?php echo __ROOT__ ?>cancellation",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,action: 'cashllish'},
            dataType:"json",
            success:function(datum){
              console.log(datum);
              const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var datacount = Object.keys(datum).length;
            
            var html='';
            console.log(months);
            for(var i=1;i<=datacount;i++){
              var  tabledate = datum[i]['date_added'];
               let current_datetime = new Date(tabledate)
           let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear() 
           html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['cc_reallotment_id']+"</td>";
              html += "<td>"+datum[i]['stncode']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cc_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_start']+"</td>";
              html += "<td>"+datum[i]['cnote_end']+"</td>";
              html += "<td>"+get_count(datum[i]['cnote_start'],datum[i]['cnote_end'])+"</td>";
              html += "<td>"+datum[i]['realloted_from']+"&nbsp;<span id='arrowdata'>&#8596;</span>&nbsp;"+datum[i]['cc_code']+"</td>";
           
              html += "<td>"+formatted_date+"</td>";
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              html += "</div></td></tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
            }

            
          }); 
       });
</script>
 
<script>
  $(document).on('change','#datefilter',function(e) {
    $(this).attr('data-date','undefined');
     var datefilter = $(this).val().trim();
     var comp_branch;
      var year = datefilter.split("-")[0];
      var month = datefilter.split("-")[1];
      var session_brcode = '<?=$sessionbr_code?>';
      if(session_brcode.length==0){
             comp_branch = "stncode='<?=$stationcode?>'";
           }else{
            comp_branch = "stncode='<?=$stationcode?>' AND br_code='"+session_brcode+"'";

           }

      const condition = [comp_branch," Month(date_added)='"+month+"' AND Year(date_added)='"+year+"'"];
      let conditions = condition.join(" AND ");
      console.log(conditions);
    $.ajax({
        url:"<?php echo __ROOT__ ?>cnoteccreallotment",
        method:"POST",
        async: false,
        global: false,
        data:{conditions,action:'change_datefilter'},
        dataType:"text",
        success:function(data)
        {
		          console.log(data);
              var res = JSON.parse(data);
              getResponseData(res)
              console.log(res[1]);
              $('#setpages').html(res[0]);
              $('#datefilterhidden').attr('data-id2','filter_onchange');
        }
});
});
</script>
<script>
   var res;
   var message;
function getResponseData(message) {
  res = message;
    return res;
}
</script>

