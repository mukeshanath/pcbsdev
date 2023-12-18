<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'destinationedit';
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
  
function loadzone($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM zone ORDER BY znid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->zncode.'">'.$row->zncode.'</option>';
         }
         return $output;    
        
    }

    $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
    $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
    $rowuser = $datafetch['last_compcode'];
    if(!empty($rowuser)){
         $stncodesrow = $rowuser;
    }
    else{
        $userstations = $datafetch['stncodes'];
        $stations = explode(',',$userstations);
        $stncodesrow = $stations[0];     
    }    

    if (isset($_GET['zone_destid'])) {
      $dest_id = $_GET['zone_destid'];
      $sql = $db->query("SELECT * FROM destination WHERE zone_destid = '$dest_id' AND comp_code='$stncodesrow'");
      $row = $sql->fetch();
      $dest_id        = $row['zone_destid'];
      $comp_code      = $row['comp_code'];
      $dest_code      = $row['dest_code'];
      $dest_name      = $row['dest_name'];
      $zonecode_a     = $row['zonecode_a'];
      $zonecode_b     = $row['zonecode_b'];
      $zonecode_c     = $row['zonecode_c'];
      $zone_cash      = $row['zone_cash'];
      $zone_ts        = $row['zone_ts'];
      $state          = $row['state'];
      $stname         = $row['stname'];
      $hub1           = $row['hub1'];
      $hub2           = $row['hub2'];
      $username       = $row['user_name'];
    }
?>
<script>
      $(document).ready(function(){
        $('#data-table').DataTable();
     });
</script>

  <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


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
                                          <li class="list-inline-item">Masters</li>
                                          <li class="list-inline-item seprate">
                                              <span>/</span>
                                          </li>
                                          <li class="list-inline-item">Destination Zone</li>
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
                                  Destination Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Destination updated successfully.
                                </div>";
                  }

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 4 )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Destination is already Added.
                                </div>";
                  }
                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 5 )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Please Check the Form Errors.
                                </div>";
                  }

                   ?>
                
             </div>
            <form method="POST" action="<?php echo __ROOT__ ?>destinationedit" autocomplete="off" class="form-horizontal" name="formdestination" id="formdestination">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
            <input  class="form-control border-form" name="zone_destid" type="text" id="zone_destid" value="<?= $dest_id ; ?>" style="display:none;">     

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Dest.Zone Form</p>
                <div class="align-right" style="margin-top:-30px;">
                

            <a style="color:white" href="<?php echo __ROOT__ . 'destinationlist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
         
            </div> 
        <div class="content panel-body">

          <div class="tab_form">
           
           <table class="table">	
           <tr>		
                              
            <td style="width:20%">Company Code</td>
              <td style="width:30%"><div class=" autocomplete col-sm-7">
              <input placeholder="" class="form-control cate_code" name="comp_code" type="text" id="stncode" readonly>
              </div>
              </td>
                  
              <td>Company Name</td>
              <td style="width:30%"><div class="col-sm-7">
                  <input class="form-control border-form" name="stnname" type="text" id="stnname" readonly>
                  </div>
              </td>
             
                   </tr>
           
            <tr>
           <td>Destination Code</td>
                <td style="width:30%"><div class="autocomplete col-sm-7 ">
                
                 <input  class="form-control border-form" name="dest_code" type="text" id="dest_code"  value="<?php echo $row['dest_code']; ?>">
                 <p id="dest_validation" class="form_valid_strings">Enter Valid Destination</p>
                  </div>          
                </td>
                
                <td>Destination Name</td>
                <td style="width:30%">
                <div class="autocomplete col-sm-7">
                 <input  class="form-control border-form" name="dest_name" type="text" id="dest_name"  value="<?php echo $row['dest_name']; ?>">
                </div>           
                </td>
           
                </tr>
                <tr>
               <td>State Code</td>
                <td style="width:30%"><div class="autocomplete col-sm-7">
                
                 <input  class="form-control border-form" name="state" type="text" id="state" readonly value="<?php echo $row['state']; ?>">
                 </div>      
                </td>
                
                <td >State Name</td>
                <td >
                <div class="col-sm-7">
                 <input  class="form-control border-form" name="stname" type="text" id="stname" readonly value="<?php echo $row['stname']; ?>">
                      </div>        
                </td>
           
                </tr>
                  <tr>
                  <td>Zone A</td>
                    <td><div class="autocomplete col-sm-7">
                    <div class="col-sm-11">
                    <input  class="form-control border-form" name="zonecode_a" type="text" id="zonecode_a" value="<?php echo $row['zonecode_a']; ?>"></div>
                    <div class="col-sm-1">
                    <button type="button"  data-toggle="modal" class="btn btn-primary" style="height:33px" data-target="#zonea"><i style="font-weight:bold"><i style="font-weight:bold">i</i></button>
                    </div>
                    </div> <p id="a_validation" class="form_valid_strings">Enter Valid Code</p></td>
                    <td>Zone B</td>
                    <td><div class=" autocomplete col-sm-7">
                    <div class="col-sm-11">
                    <input  class="form-control border-form" name="zonecode_b" type="text" id="zonecode_b" value="<?php echo $row['zonecode_b']; ?>"></div>
                    <div class="col-sm-1">
                    <button type="button"  data-toggle="modal" class="btn btn-primary" style="height:33px" data-target="#zoneb"><i style="font-weight:bold">i</button>
                    </div>
                    </div> <p id="b_validation" class="form_valid_strings">Enter Valid Code</p>
                    </td>
                  </tr>
                  <tr>
                  <td>Zone C</td>
                    <td><div class=" autocomplete col-sm-7">
                    <div class="col-sm-11">
                    <input  class="form-control border-form" name="zonecode_c" type="text" id="zonecode_c" value="<?php echo $row['zonecode_c']; ?>"></div>
                    <div class="col-sm-1">
                    <button type="button"  data-toggle="modal" class="btn btn-primary" style="height:33px" data-target="#zonec"><i style="font-weight:bold">i</button>
                    </div>
                    </div> <p id="c_validation" class="form_valid_strings">Enter Valid Code</p></td>
                    <td>Cash Zone</td>
                    <td>
                    <div class="autocomplete col-sm-7">
                    <div class="col-sm-11">
                    <input  class="form-control border-form" name="zone_cash" type="text" id="zone_cash" value="<?php echo $row['zone_cash']; ?>"></div>
                    <div class="col-sm-1">
                    <button type="button"  data-toggle="modal" class="btn btn-primary" style="height:33px" data-target="#zcash"><i style="font-weight:bold">i</button>
                    </div>
                    <p id="cash_validation" class="form_valid_strings">Enter Valid Code</p>
                    </div>
                    </td>
                  </tr>
            <tr>
            
            <td>TS Zone </td>
            <td>
            <div class="autocomplete col-sm-7">
            <div class="col-sm-11">
             <input  class="form-control border-form" name="zone_ts" type="text" id="zone_ts" value="<?php echo $row['zone_ts']; ?>"></div>
             <div class="col-sm-1">
                    <button type="button"  data-toggle="modal" class="btn btn-primary" style="height:33px" data-target="#zts"><i style="font-weight:bold">i</button>
                    </div>
              <p id="ts_validation" class="form_valid_strings">Enter Valid Code</p>
             </div>
             </td>
             <td>Hub 1</td>
            <td>
            <div class="autocomplete col-sm-7">
            <input  class="form-control border-form" name="hub1" type="text" id="hub1" value="<?php echo $row['hub1']; ?>">
           
            </div>
            </td>
            </tr>
            <tr>
            
            <td>Hub 2</td>
            <td>
            <div class="autocomplete col-sm-7">
             <input  class="form-control border-form" name="hub2" type="text" id="hub2" value="<?php echo $row['hub2']; ?>">
           
             </div>
             </td>
             <td></td>
             <td></td>
           
            </tr>
            </tbody>
              
           </table>
           <table>

          <div class="align-right btnstyle" >

          <button type="submit" class="btn btn-primary btnattr_cmn" name="updatedestination">
          <i class="fa  fa-save"></i>&nbsp; Save
          </button>
                                                            

          <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
          <i class="fa fa-eraser"></i>&nbsp; Reset
          </button>   


          </div>
          </table>
        </form>
 


          </div>
<style>
.modellist{
    cursor:pointer;
    padding:5px;
}
.modellist:hover{
    background:#2f70b0;
    color:white;
    font-weight:bold;
}
.model-ul{
    list-style-type:none;
}
</style>
<!-- Modal for Zone code A-->
<div class="modal fade" id="zonea" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Zone Code A</h5>
      </div>
      <div class="modal-body">
       <?php
       $model_a=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='credit' AND zone_code NOT LIKE 'B%' AND zone_code NOT LIKE 'C%'");
       while($row_a=$model_a->fetch(PDO::FETCH_ASSOC))
       {
       echo "<ul class='model-ul'>";    
       echo "<li class='model-list-a modellist' data-dismiss='modal'>".$row_a['zone_code']." - ".$row_a['zone_name']."</li>";
       echo "</ul>";
       }
       ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal for Zone code B-->
<div class="modal fade" id="zoneb" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Zone Code B</h5>
      </div>
      <div class="modal-body">
       <?php
       $model_b=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='credit' AND zone_code LIKE 'B%'");
       while($row_b=$model_b->fetch(PDO::FETCH_ASSOC))
       {
       echo "<ul class='model-ul'>";    
       echo "<li class='model-list-b modellist' data-dismiss='modal'>".$row_b['zone_code']." - ".$row_b['zone_name']."</li>";
       echo "</ul>";
       }
       ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End -->
<!-- Modal for Zone code C-->
<div class="modal fade" id="zonec" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ZoneCode C</h5>
      </div>
      <div class="modal-body">
       <?php
       $model_c=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='credit' AND zone_code LIKE 'C%'");
       while($row_c=$model_c->fetch(PDO::FETCH_ASSOC))
       {
       echo "<ul class='model-ul'>";    
       echo "<li class='model-list-c modellist' data-dismiss='modal'>".$row_c['zone_code']." - ".$row_c['zone_name']."</li>";
       echo "</ul>";
       }
       ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End -->
<!-- Modal for Zone code Cash-->
<div class="modal fade" id="zcash" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Zone Cash</h5>
      </div>
      <div class="modal-body">
       <?php
       $model_cash=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='cash' AND zone_code NOT LIKE 'A%' AND zone_code NOT LIKE 'B%' AND zone_code NOT LIKE 'C%' ");
       while($row_cash=$model_cash->fetch(PDO::FETCH_ASSOC))
       {
       echo "<ul class='model-ul'>";    
       echo "<li class='model-list-cash modellist' data-dismiss='modal'>".$row_cash['zone_code']." - ".$row_cash['zone_name']."</li>";
       echo "</ul>";
       }
       ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End -->
<!-- Modal for Zone code TS-->
<div class="modal fade" id="zts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Zone TS</h5>
      </div>
      <div class="modal-body">
       <?php
       $model_a=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='credit' AND zone_code NOT LIKE 'B%' AND zone_code NOT LIKE 'C%'");
       while($row_a=$model_a->fetch(PDO::FETCH_ASSOC))
       {
       echo "<ul class='model-ul'>";    
       echo "<li class='model-list-ts modellist' data-dismiss='modal'>".$row_a['zone_code']." - ".$row_a['zone_name']."</li>";
       echo "</ul>";
       }
       ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End -->
            </div>
            </div>
            </div>
  </section>
           
<!-- inline scripts related to this page -->
<script>
$('.model-list-a').click(function(){
    $('#zonecode_a').val($(this).text());
});
$('.model-list-b').click(function(){
    $('#zonecode_b').val($(this).text());
});
$('.model-list-c').click(function(){
    $('#zonecode_c').val($(this).text());
});
$('.model-list-cash').click(function(){
    $('#zone_cash').val($(this).text());
});
$('.model-list-ts').click(function(){
    $('#zone_ts').val($(this).text());
});
</script>
<script>

$('#dest_code').focusout(function(){
        var dest_code = $('#dest_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dest_code:dest_code},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#dest_name').val(data[0]);
            $('#state').val(data[1]);
            $('#stname').val(data[2]);
            }
    });
    });
  
</script>
<script>
$('#dest_name').focusout(function(){
        var dest_name = $('#dest_name').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dest_name1:dest_name},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#dest_code').val(data[0]);
            $('#state').val(data[1]);
            $('#stname').val(data[2]);
            }
    });
    });
</script>
<script>
//Verifying Destination code is Valid
    $('#dest_code').focusout(function(){
        var dest_code1 = $('#dest_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dest_code1:dest_code1},
            dataType:"text",
            success:function(destcount)
            {
               //document.write(destcount);
                if(destcount == 0)
                {
                    $('#dest_validation').css('display','block');
                }
                else{
                    $('#dest_validation').css('display','none');
                }
            }
    });
    });
</script>
<script>
//Verifying Zones code is Valid
$(document).ready(function(){
    //Zonecode A
    
    $('#zonecode_a').focusout(function(){
        var zonecodea_val = $('#zonecode_a').val();
        var stationazone = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{zonecodea_val:zonecodea_val,stationazone:stationazone},
            dataType:"text",
            success:function(zoneacount)
            {
                if(zoneacount == 0){
                    $('#a_validation').css('display','block');
                }else{
                    $('#a_validation').css('display','none');
                }
            }
            });
        });
        //Zonecode B
    $('#zonecode_b').focusout(function(){
        var zonecodeb_val = $('#zonecode_b').val();
        var stationbzone = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{zonecodeb_val:zonecodeb_val,stationbzone:stationbzone},
            dataType:"text",
            success:function(zonebcount)
            {
                if(zonebcount == 0){
                    $('#b_validation').css('display','block');
                }else{
                    $('#b_validation').css('display','none');
                }
            }
            });
        });
        //Zonecode C
    $('#zonecode_c').focusout(function(){
        var zonecodec_val = $('#zonecode_c').val();
        var stationzone = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{zonecodec_val:zonecodec_val,stationczone:stationzone},
            dataType:"text",
            success:function(zoneccount)
            {
                if(zoneccount == 0){
                    $('#c_validation').css('display','block');
                }else{
                    $('#c_validation').css('display','none');
                }
            }
            });
        });
        //Cash Zone
    $('#zone_cash').focusout(function(){
        var zonecode_val = $('#zone_cash').val();
        var stationzone = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{zonecodecash_val:zonecodecash_val,stationcashzone:stationzone},
            dataType:"text",
            success:function(zonecashcount)
            {
                if(zonecashcount == 0){
                    $('#cash_validation').css('display','block');
                }else{
                    $('#cash_validation').css('display','none');
                }
            }
            });
        });
        //TS Zone
    $('#zone_ts').focusout(function(){
        var zonecode_val = $('#zone_ts').val();
        var stationzone = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{zonecodets_val:zonecodets_val,stationtszone:stationzone},
            dataType:"text",
            success:function(zonetscount)
            {
                if(zonetscount == 0){
                    $('#ts_validation').css('display','block');
                }else{
                    $('#ts_validation').css('display','none');
                }
            }
            });
        });
        //Hub 1
    $('#hub1').focusout(function(){
        var dest_code1 = $('#hub1').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dest_code1:dest_code1},
            dataType:"text",
            success:function(destcount)
            {
                if(destcount == 0){
                    $('#h1_validation').css('display','block');
                }else{
                    $('#h1_validation').css('display','none');
                }
            }
            });
        });
        //Hub 2
    $('#hub2').focusout(function(){
        var dest_code1 = $('#hub2').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dest_code1:dest_code1},
            dataType:"text",
            success:function(destcount)
            {
                if(destcount == 0){
                    $('#h2_validation').css('display','block');
                }else{
                    $('#h2_validation').css('display','none');
                }
            }
            });
        });
    });
</script>
<script>
 var btn=document.querySelector(".addbtn");
 btn.onclick=function create(){
     var div=document.createElement("tr");
     div.setAttribute("id","form_group");
     div.innerHTML=gen();
     document.getElementById("dynamic_form").appendChild(div); 
    }
     function gen()
     {
        return '<td><select id="zone_dc" style="border-color:#337AB7" name="zone_dc[]" class="form-control input-sm" value=""><option value="0">Select Zone</option><?php echo loadzone($db); ?></select></td><td><select id="zone_ba" style="border-color:#337AB7" name="zone_ba[]" class="form-control input-sm" value=""><option value="0">Select Zone</option><?php echo loadzone($db); ?></select></td><td><select id="zone_cash" style="border-color:#337AB7" name="zone_cash[]" class="form-control input-sm" value=""><option value="0">Select Zone</option><?php echo loadzone($db); ?></select></td><td><div class="input-group"><select id="zone_ts[]" style="border-color:#337AB7;width: 125%;" name="zone_ts" class="form-control input-sm" value=""><option value="0">Select Zone</option><?php echo loadzone($db); ?></select></td> ';
     }
     function remove()
     {
         document.getElementById("form_group").remove();
     }

    function searchcollection()
    {
        document.getElementsByClassName('searchcollection').style.display='block';
    }

  </script>
  <script>
  
 var zonecode_a =[<?php 
   
    $sql=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='credit' AND zone_code NOT LIKE 'B%' AND zone_code NOT LIKE 'C%'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']." - ".$row['zone_name']."', ";
    }
 ?>];

var zonecode_b =[<?php  
    $sql=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='credit' AND zone_code LIKE 'B%'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']." - ".$row['zone_name']."', ";
    }
 ?>];

 var zonecode_c =[<?php  
    $sql=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='credit' AND zone_code LIKE 'C%'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']." - ".$row['zone_name']."', ";
    }
 ?>];

 var zonecash =[<?php  
    $sql=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='cash' AND zone_type='cash'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']." - ".$row['zone_name']."', ";
    }
 ?>];

 var zonets =[<?php  
    $sql=$db->query("SELECT * FROM zone WHERE comp_code='$stncodesrow' AND zone_type='ts' AND zone_type='ts'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']." - ".$row['zone_name']."', ";
    }
 ?>];

</script>
<script>

autocomplete(document.getElementById("zonecode_a"), zonecode_a);
autocomplete(document.getElementById("zonecode_b"), zonecode_b);
autocomplete(document.getElementById("zonecode_c"), zonecode_c);
autocomplete(document.getElementById("zone_cash"), zonecash);
autocomplete(document.getElementById("zone_ts"), zonets);


</script>
<script>

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
                // $('#companysection').css('display','block');
                var superadmin_stn = $('#stncode1').val();
                //var superadmin_stn = 'DAA';
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
var destcode =[<?php  
    $sql=$db->query("SELECT stncode FROM stncode");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['stncode']."', ";
    }
 ?>];
 //Autocomplete Detination Name
var destname =[<?php  
    $sql=$db->query("SELECT stnname FROM stncode");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['stnname']."', ";
    }
 ?>];

autocomplete(document.getElementById("dest_code"), destcode);
autocomplete(document.getElementById("dest_name"), destname);
autocomplete(document.getElementById("hub1"), destcode);
autocomplete(document.getElementById("hub2"), destcode);
</script>

<script>
function resetFunction() {
    document.getElementById('dest_code').value = "";
    document.getElementById('dest_name').value = "";
    document.getElementById('state').value = "";
    document.getElementById('stname').value = "";
    document.getElementById('zone_cc').value = "";
    document.getElementById('zone_ba').value = "";
    document.getElementById('zone_cash').value = "";
    document.getElementById('zone_ts').value = "";
    document.getElementById('hub1').value = "";
    document.getElementById('hub2').value = "";   
}
</script>