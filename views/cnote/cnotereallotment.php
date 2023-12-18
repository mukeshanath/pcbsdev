<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'cnotereallotment';
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

   //branch filter conditions
   $sessionbr_code = $datafetch['br_code'];
   if(!empty($sessionbr_code)){
       $br_code = $sessionbr_code;
       $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
       $br_name     = $fetchbrname->br_name;
       }
    //branch filter conditions 

    function loadgroup($db,$user)
   {
       $output='';
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

   if(isset($_POST['lookupcustomer'])){ $br_code = $_POST['br_code']; }

  ?>
  <style>
      .inv_label{
      font-weight:600;font-size:14px;margin-right:20px;
      }
      #arrowdata{
         font-weight: bold;
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
    if ( isset($_GET['success']) && $_GET['success'] == 2 )
{
        echo 
"<div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-check'></i> Alert!</h4>
                Cnotes are reallocated to branch.
</div>";
}

?>
</div>
<form method='POST' action='<?php echo __ROOT__ ?>cnotereallotment' accept-charset="UTF-8" class="form-horizontal" id="formbranch" enctype="multipart/form-data">
<input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
    <!-- Tittle -->
    <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote branch reallocation</p>
        <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
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
        <td>Branch Code</td>
        <td><div class="autocomplete col-sm-12">
        <input id="br_code" name="br_code" class="form-control input-sm br_code text-uppercase" type="text" >
        <input id="pre_br_code" name="pre_br_code" class="form-control input-sm" type="hidden" >

        </td>
        <td>Branch Name</td>
        <td><div class="col-sm-12">
             <input id="br_name" name="br_name"  tabindex="-1"  class="form-control input-sm text-uppercase"  type="text"></div>
             </div>
        </td>	
        </tr>
        <td>Serial Type</td> 
        <td>
        <select  type="text" id="cnote_serial" name="cnote_serial" class="form-control input-sm">
        <option value="">Select Serial</option>
            <?php echo loadgroup($db,$user)  ?>       
            </select>
        </td>	
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
</table>
<div class="align-right btnstyle" >

<button type="button" class="btn btn-info" id="reallocate" name="reallocate" onClick="reallocate_br();">
ReAllocate <i class="fa fa-filter bigger-110"></i>                         
</button> 

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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote branch reallocation List</p>
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
                      <th>Cnote Serial</th>
                      <th>Cnote Start</th>
                      <th>Cnote End</th>
                      <th>Cnote Count</th>
                      <th>Cnote Re-Allotment</th>                   
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
$query = $db->query("SELECT * FROM br_reallotment WITH(NOLOCK) WHERE $conditons order by br_reallotment_id desc OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");

while ($row = $query->fetch(PDO::FETCH_OBJ))
                            {
                              echo "<tr>";
                              echo "<td class='center first-child'>
                                      <label>
                                        <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                          <span class='lbl'></span>
                                      </label>
                                    </td>";
                              echo "<td>".$row->br_reallotment_id."</td>";
                              echo "<td>".$row->stncode."</td>";
                              echo "<td>".$row->br_code."</td>";
                              echo "<td>".$row->cnote_serial."</td>";                            
                              echo "<td>".$row->cnote_start."</td>";
                              echo "<td>".$row->cnote_end."</td>";
                              echo "<td>".get_cnote_count($row->cnote_start,$row->cnote_end)."</td>";
                              echo "<td>".$row->realloted_from."&nbsp;<span id='arrowdata'>&#8596;</span>&nbsp;".$row->br_code."</td>";
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

            <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM br_reallotment WHERE $conditons")->fetch(PDO::FETCH_OBJ);
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

<br></br>
               
                       
</section>
<script>
    function reallocate_br(){
         if($('#br_code').val().trim()==''){
            swal("Please Enter Branch");
        }
        else if($('#cnotestart').val().trim()==''){
            swal("Please Enter Cnote Start Number");
        }
        else if($('#cnoteend').val().trim()==''){
            swal("Please Enter Cnote End Number");
        }
       else if(validate_branch()=='Invalid'){
            swal("Please Enter Valid Cnote Details");
        }else{
           $('#reallocate').attr('type','submit');
        }
    
    }
</script>
<script>
    $(document).on('focusout','input[name="cnote_start"]',function(){
        var stncode = $('#stncode').val();
        var cnote_serial = $('#cnote_serial').val();
        var cnote_number = $(this).val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotereallotment",
            method:"POST",
            data:{stncode,cnote_serial,cnote_number,action:"fetchreallot"},
            dataType:"json",
            success:function(data)
            {
              console.log(data);
              $('#pre_br_code').val(data);
            }
    });
    })
</script>

<script>
    $(document).on('change','#cnote_serial',function(){
      console.log(validate_branch());
        var stncode = $('#stncode').val();
        var cnote_serial = $('#cnote_serial').val();
        var cnote_number = $('#cnotestart').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotereallotment",
            method:"POST",
            data:{stncode,cnote_serial,cnote_number,action:"fetchreallot"},
            dataType:"json",
            success:function(data)
            {
              console.log(data);
              $('#pre_br_code').val(data);
            }
    });
    })
</script>

<script>

function validate_branch(){
    var cnote_number = $('#cnotestart').val();
         var stncode  = $('#stncode').val();
         var br_code  = $('#br_code').val();
         var cnote_serial = $('#cnote_serial').val();
         var cnoteend = $('#cnoteend').val();
         $.ajax({
                url:"<?php echo __ROOT__ ?>cnotereallotment",
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
         //Fetch Inventory Data
    $('#inventory_btn').click(function(){
        var brcode = $('#br_code').val();
        var stn_inventory = $('#stncode').val();
        var serial = $('#cnote_serial').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{brcode,stn_inventory,serial},
            dataType:"text",
            success:function(data)
            {
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
  // Procured And Void
  function fetchsalesnote(){
        var brcode = $('#br_code').val();
        var stn_inventory = $('#stncode').val();
        var serial = $('#cnote_serial').val();
        
        $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        data:{cnotebrcode:brcode,cnotestncode:stn_inventory,cnoteserial:serial},
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
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode' ");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];

var branchname = [<?php
 $brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' ");
 while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
 {
 echo "'".$rowbrname['br_name']."',";
 }
 ?>]
</script>
<script>
//branches array
var branches = {
    <?php
    $branches = $db->query("SELECT br_code,br_name FROM branch WHERE stncode='$stationcode'");
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
        $('#br_code').focusout(function(){
            var br_code = $('#br_code').val().toUpperCase().trim();
            if(branches[br_code]!==undefined){
                $('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
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
            }
            else{
                $('#brcode_validation').css('display','block');
            }
           
        });
    });
    </script>

<script>
        //Verifying Branch code is Valid
        function verifybranch(){
        var branch_verify = $('#br_code_cn').val();
        var stationcode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{branch_verifydc_cn:branch_verify,stationcodedc:stationcode},
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
$('#br_name_cn').keyup(function(){
   var s_bname_cn = $(this).val();
   var s_comp = $('#stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{s_bname_cn:s_bname_cn,s_comp:s_comp},
       dataType:"text",
       success:function(brcode)
       {
           $('#br_code_cn').val(brcode);
            verifybranch();
       }
});
});
</script>
<script>
  $(document).ready(function(){
        $('#br_code_cn').focusout(function(){
            var br_code = $('#br_code_cn').val().toUpperCase().trim();
            if(branches[br_code]!==undefined){
                $('#brcode_validation').css('display','none');
                $('#br_name_cn').val(branches[br_code]);
            }
            else{
                $('#brcode_validation').css('display','block');
            }
        });
        //fetch code of entering name
        $('#br_name_cn').focusout(function(){
            var br_name = $(this).val().trim();
            var gccode = getKeyByValue(branches,br_name);
            
            if(gccode!==undefined){
                $('#brcode_validation').css('display','none');
                $('#br_code_cn').val(gccode);
            }
            else{
                $('#brcode_validation').css('display','block');
            }
           
        });
    });
    </script>
<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);
autocomplete(document.getElementById("br_code_cn"), branchcode);
autocomplete(document.getElementById("br_name_cn"), branchname);
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
   
    var dtbquery = "SELECT br_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial FROM br_reallotment WITH(NOLOCK) WHERE  "+conditions+" order by br_reallotment_id desc";

      var dtbcolumns = 'br_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial';
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
              html += "<td>"+datum[i]['br_reallotment_id']+"</td>";
              html += "<td>"+datum[i]['stncode']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_start']+"</td>";
              html += "<td>"+datum[i]['cnote_end']+"</td>";
              html += "<td>"+get_count(datum[i]['cnote_start'],datum[i]['cnote_end'])+"</td>";
              html += "<td>"+datum[i]['realloted_from']+"&nbsp;<span id='arrowdata'>&#8596;</span>&nbsp;"+datum[i]['br_code']+"</td>";
            
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
      var dtbquery = "SELECT br_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial FROM br_reallotment WITH(NOLOCK)  WHERE "+conditions+" AND (cnote_start like '%"+fieldvalue+"%' OR cnote_end LIKE '%"+fieldvalue+"%') order by br_reallotment_id desc";
      var dtbcolumns = 'br_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial';
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
              html += "<td>"+datum[i]['br_reallotment_id']+"</td>";
              html += "<td>"+datum[i]['stncode']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_start']+"</td>";
              html += "<td>"+datum[i]['cnote_end']+"</td>";
              html += "<td>"+get_count(datum[i]['cnote_start'],datum[i]['cnote_end'])+"</td>";
              html += "<td>"+datum[i]['realloted_from']+"&nbsp;<span id='arrowdata'>&#8596;</span>&nbsp;"+datum[i]['br_code']+"</td>";
            
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
      var dtbquery = "SELECT br_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial FROM br_reallotment WITH(NOLOCK) WHERE  "+conditions+" order by br_reallotment_id desc";
      console.log(dtbquery);
      var dtbcolumns = 'br_reallotment_id,stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial';
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
              html += "<td>"+datum[i]['br_reallotment_id']+"</td>";
              html += "<td>"+datum[i]['stncode']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_start']+"</td>";
              html += "<td>"+datum[i]['cnote_end']+"</td>";
              html += "<td>"+get_count(datum[i]['cnote_start'],datum[i]['cnote_end'])+"</td>";
              html += "<td>"+datum[i]['realloted_from']+"&nbsp;<span id='arrowdata'>&#8596;</span>&nbsp;"+datum[i]['br_code']+"</td>";
           
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
        url:"<?php echo __ROOT__ ?>cnotereallotment",
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