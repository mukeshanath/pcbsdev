<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'zoneedit';
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

if (isset($_GET['znid'])) {
$zone_id = $_GET['znid'];
$sql = $db->query("SELECT znid,zone_type,zone_code,zone_name,dbo.GetNumericValue(zone_code) zone_n FROM zone WHERE znid = '$zone_id'");
$row = $sql->fetch();
$znid          = $row['znid'];
$zone_type     = $row['zone_type'];
$zone_code     = $row['zone_code'];
$zone_name     = $row['zone_name'];
$zone_n        = $row['zone_n'];
// if($zone_type=='credit'){
    //$dest_condition = " dbo.GetNumericValue('$zone_code') IN (zonecode_a,zonecode_b,zonecode_c,zone_cash,zone_ts)";
    if($zone_type=='credit'){
    $dest_condition = " '$zone_code' IN (zonecode_a,zonecode_b,zonecode_c)";
    }
    else if($zone_type=='cash'){
        $dest_condition = " zone_cash='$zone_code'";
    }
    else{
        $dest_condition = " zone_ts='$zone_code'";
    }
// }
// else if($zone_type=='cash'){
//     $dest_condition = " zone_cash='$zone_code'";
// }
// else{
//     $dest_condition = " zone_ts='$zone_code'";
// }
 }

function loadstniata($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM stncode ORDER BY stnid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stncode.'">'.$row->stncode.'</option>';
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
?>

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
                                    <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'zonelist'; ?>">Zone list</a>
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
                                Zone Added successfully.
                              </div>";
                }

                 if ( isset($_GET['success']) && $_GET['success'] == 2 )
                {
                     echo 

                "<div class='alert alert-success alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                Zone updated successfully.
                              </div>";
                }

                 ?>
              
           </div>
          <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>zoneedit" accept-charset="UTF-8" class="form-horizontal" id="formzone" enctype="multipart/form-data">
          <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
          <input class="form-control border-form" name="znid" type="text" id="znid" value="<?= $znid; ?>" style="display:none">

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Zone Alter Form</p>
                    <div class="align-right" style="margin-top:-30px;">
          
          <a style="color:white" href="<?php echo __ROOT__ . 'zonelist'; ?>">         
          <button type="button" class="btn btn-danger btnattr_cmn" >
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


          <table class="table" cellspacing="0" cellpadding="0" align="center">	
          <tbody>
          <tr>		
                              
          <td>Company Code</td>
            <td>
            <input placeholder="" class="form-control cate_code" name="comp_code" type="text" id="stncode" readonly>
            </td>
                
            <td>Company Name</td>
            <td>
                <input class="form-control border-form" name="stnname" type="text" id="stnname" readonly>
            </td>
           
                 </tr>
                 <tr>		
                              
                  <td>Zone Type</td>
                  <td>
                   <select class="form-control border-form" name="zone_type" id="zone_type" value="<?= $zone_type; ?>">
                   <option >Select Zone</option>
                   <option value="multi">Multi</option>
                   <option value="credit" <?=(($zone_type=='credit')?'Selected':'')?>>Credit Zone</option>
                   <option value="cash" <?=(($zone_type=='cash')?'Selected':'')?>>Cash Zone</option>                    
                  <option value="ts" <?=(($zone_type=='ts')?'Selected':'')?>>TS Zone</option>
                  </select>
             </td>
              <td>Zone Code</td>
                <td>
                   <input class="form-control border-form" name="zone_code" value="<?= $zone_code; ?>" type="text" id="zone_code">
                   <input type="number" style="display:none" name="zone_number" value="<?=$zone_n?>">
             </td>	
                                 
             </tr>

             <tr>		
               <td>Zone Name</td>
            <td>
               <input class="form-control border-form" name="zone_name" value="<?= $zone_name; ?>" type="text" id="zone_name">
                </td>	
                 </tr>
          
            </tbody>
    </table>
                 
    </form>
    <table>

<div class="align-right btnstyle" >

<button type="submit" class="btn btn-primary btnattr_cmn" name="updatezone">
<i class="fa  fa-save"></i>&nbsp; Update
</button>
                                                

<button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
<i class="fa fa-eraser"></i>&nbsp; Reset
</button>   


</div>
</table>
     
    </div>
    <!-- destination tab -->
    <div class="title_1 title panel-heading" style="margin-top:10px">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Destination Zone</p>
                    
                    <div class="align-right" style="margin-top:-30px;">
          
                 
          <button type="button" class="btn btn-success btnattr_cmn" onclick="adddestionationrow()">
          <i class="fa fa-plus" ></i>
            </button>
        </div>
       
          </div> 
        <div class="tab_form">


      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->


        <table class="table" cellspacing="0" cellpadding="0" align="center">	
        <thead>
          <tr>		
             <th>Dest Code</th>
             <th>Dest Name</th>
             <th class='align-center'>Zone A</th>
             <th class='align-center'>Zone B</th>
             <th class='align-center'>Zone C</th>
             <th class='align-center'>Cash Zone</th>
             <th class='align-center'>TS Zone</th>
             <th class='align-center'>Remove</th>
          </tr>
         </thead>
         <tbody id="destination_zone_tbody">
         </tbody>
         <tbody>
             <script>
                 var desnforCurrentZone = [];
                 </script>
          <?php
           $i=0;
          $destinations = $db->query("SELECT * FROM destination WHERE comp_code='$stationcode' AND $dest_condition");
          while($destinationrow = $destinations->fetch(PDO::FETCH_OBJ))
          { $i++;
              echo "<script> desnforCurrentZone.push('$destinationrow->dest_code'.trim()) </script>";
             
          echo "<tr>
                <td class='col-sm-2'><input type='text' class='form-control' value='$destinationrow->dest_code' name='dest_code[]' readonly style='background:transparent!important;border:none'></td>
                <td class='col-sm-3'><input type='text' class='form-control' value='$destinationrow->dest_name' name='dest_name[]' readonly style='background:transparent!important;border:none'></td>
                <td class='col-sm-1 align-center'><input value='$zone_code'  name='zone_a[$destinationrow->dest_code]'    style='width:50px;height:25px;'      ".(($destinationrow->zonecode_a==$zone_code && $zone_type=='credit' )?'Checked':'')." ".(((empty($destinationrow->zonecode_a) || $destinationrow->zonecode_a==$zone_code)  && $zone_type=='credit' )?'':'Disabled')." type='checkbox'></td>
                <td class='col-sm-1 align-center'><input value='$zone_code'  name='zone_b[$destinationrow->dest_code]'    style='width:50px;height:25px;'       ".(($destinationrow->zonecode_b==$zone_code && $zone_type=='credit' )?'Checked':'')." ".(((empty($destinationrow->zonecode_b) || $destinationrow->zonecode_b==$zone_code)  && $zone_type=='credit' )?'':'Disabled')." type='checkbox'></td>
                <td class='col-sm-1 align-center'><input value='$zone_code'  name='zone_c[$destinationrow->dest_code]'    style='width:50px;height:25px;'       ".(($destinationrow->zonecode_c==$zone_code && $zone_type=='credit' )?'Checked':'')." ".(((empty($destinationrow->zonecode_c) || $destinationrow->zonecode_c==$zone_code)  && $zone_type=='credit' )?'':'Disabled')." type='checkbox'></td>
                <td class='col-sm-1 align-center'><input value='$zone_code'  name='zone_cash[$destinationrow->dest_code]' style='width:50px;height:25px;'   ".(($destinationrow->zone_cash==$zone_code  && $zone_type=='cash'   )?'Checked':'')." ".(((empty($destinationrow->zone_cash)  || $destinationrow->zone_cash==$zone_code )  && $zone_type=='cash'   )?'':'Disabled')." type='checkbox'></td>
                <td class='col-sm-1 align-center'><input value='$zone_code'  name='zone_ts[$destinationrow->dest_code]'   style='width:50px;height:25px;'      ".(($destinationrow->zone_ts==$zone_code    && $zone_type=='ts'     )?'Checked':'')." ".(((empty($destinationrow->zone_ts)    || $destinationrow->zone_ts==$zone_code   )  && $zone_type=='ts'     )?'':'Disabled')." type='checkbox'></td>
                <td class='col-sm-1 align-center'><button type='button' style='margin-top:0px;' class='btn btn-danger btn-xs' onclick='removedestinationrow(this)' id='remove' ><i class='fa fa-minus-circle'></i></button></td>
            </tr>";
        //   echo "<tr>
        //         <td>$i</td>
        //         <td class='col-sm-2'><input type='text' class='form-control' value='$destinationrow->dest_code' name='dest_code[]' readonly style='background:transparent!important;border:none'></td>
        //         <td class='col-sm-3'><input type='text' class='form-control' value='$destinationrow->dest_name' name='dest_name[]' readonly style='background:transparent!important;border:none'></td>
        //         <td class='col-sm-1 align-center'><input value='$zone_n'  name='zone_a[$destinationrow->dest_code]' style='width:50px;height:25px;'     ".(empty($destinationrow->zonecodea) || $destinationrow->zonecodea==$zone_n?'':'Disabled')."  ".(($zone_n==$destinationrow->zonecodea)?"Checked":"")." type='checkbox'></td>
        //         <td class='col-sm-1 align-center'><input value='B$zone_n' name='zone_b[$destinationrow->dest_code]' style='width:50px;height:25px;'     ".(empty($destinationrow->zonecodeb) || $destinationrow->zonecodeb==$zone_n?'':'Disabled')." ".(($zone_n==$destinationrow->zonecodeb)?"Checked":"")." type='checkbox'></td>
        //         <td class='col-sm-1 align-center'><input value='C$zone_n' name='zone_c[$destinationrow->dest_code]' style='width:50px;height:25px;'     ".(empty($destinationrow->zonecodec) || $destinationrow->zonecodec==$zone_n?'':'Disabled')." ".(($zone_n==$destinationrow->zonecodec)?"Checked":"")." type='checkbox'></td>
        //         <td class='col-sm-1 align-center'><input value='$zone_n'  name='zone_cash[$destinationrow->dest_code]' style='width:50px;height:25px;'  ".(empty($destinationrow->zonecash) || $destinationrow->zonecash==$zone_n?'':'Disabled')." ".(($zone_n==$destinationrow->zonecash)?"Checked":"")." type='checkbox'></td>
        //         <td class='col-sm-1 align-center'><input value='$zone_n' name='zone_ts[$destinationrow->dest_code]' style='width:50px;height:25px;'     ".(empty($destinationrow->zonets) || $destinationrow->zonets==$zone_n?'':'Enabled')." ".(($zone_n==$destinationrow->zonets)?"Checked":"")." type='checkbox'></td>
        //         <td class='col-sm-1 align-center'><button type='button' style='margin-top:0px;' class='btn btn-danger btn-xs' onclick='removedestinationrow(this)' id='remove' ><i class='fa fa-minus-circle'></i></button></td>
        //     </tr>";
           
          }
          ?>
            </tbody>
    </table>
    
    </form>
    </div>
    <!-- destination tab -->

          </div>
          </div>
          </div>
</section>

<!-- inline scripts related to this page -->
<script>
//console.log(desnforCurrentZone);
//DataTable
$(document).ready(function(){
      $('#data-table').DataTable();
  });

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
function resetFunction() {
    document.getElementById('zone_type').value = "";
    document.getElementById('zone_code').value = "";
    document.getElementById('zone_name').value = "";
   
}
</script>
<script>
function alertfn(boxcount){
    alert(boxcount);
}
</script>
<script>
 

var destination =[<?php  
    $sql1=$db->query("SELECT dest_code,dest_name FROM destination where comp_code='$stationcode'");
    while($row1=$sql1->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row1['dest_code']."',";
    }
    
 ?>];


</script>
<script>
    var zonecode = '<?=$zone_code?>';
var boxcount=0;
    function adddestionationrow(){
        boxcount++;
        $('#destination_zone_tbody').append("<tr><td class='col-sm-2'><div class='autocomplete'><input type='text' id='dest_code"+boxcount+"' class='form-control' name='dest_code[]' oninput='addname(this)' onfocusout='addname(this)'></div></td>                <td class='col-sm-3'><input type='text' class='form-control dynamic-dest_name' name='dest_name[]'></td>                <td class='col-sm-1 align-center'><input style='width:50px;height:25px;' value='"+zonecode+"' id='zone_a' type='checkbox'></td>                <td class='col-sm-1 align-center'><input style='width:50px;height:25px;' value='"+zonecode+"' id='zone_b' type='checkbox'></td>                <td class='col-sm-1 align-center'><input style='width:50px;height:25px;' value='"+zonecode+"' id='zone_c' type='checkbox'></td>                <td class='col-sm-1 align-center'><input style='width:50px;height:25px;' value='"+zonecode+"' id='zone_cash' type='checkbox'></td>                <td class='col-sm-1 align-center'><input style='width:50px;height:25px;' value='"+zonecode+"' id='zone_ts' type='checkbox'></td>                <td class='col-sm-1 align-center'><button type='button' style='margin-top:0px;' class='btn btn-danger btn-xs' onclick='removedestinationrow(this)' id='remove' ><i class='fa fa-minus-circle'></i></button></td>          </tr>");
        
        autocomplete(document.getElementById("dest_code"+boxcount+""), destination);
    }


</script>

<script>
        function removedestinationrow(textbox){
        if(confirm("Are You sure Want to delete") == true)
        {  
        $(textbox).closest('tr').remove();
        }
        else{
            return false;
        }
    }
</script>
<script>
    function addname(textbox){
        var valueofname = $(textbox).val();
        if(desnforCurrentZone.includes(valueofname))
        {
            swal("Entered Destination is Already Added");
        }
        $(textbox).closest('tr').find('#zone_a').attr({name:'zone_a['+valueofname+']'});
        $(textbox).closest('tr').find('#zone_b').attr('name','zone_b['+valueofname+']');
        $(textbox).closest('tr').find('#zone_c').attr('name','zone_c['+valueofname+']');
        $(textbox).closest('tr').find('#zone_cash').attr('name','zone_cash['+valueofname+']');
        $(textbox).closest('tr').find('#zone_ts').attr('name','zone_ts['+valueofname+']');

        //check destcode and disable destinations
                $(textbox).closest('tr').find('#zone_a').prop('checked',false);
                $(textbox).closest('tr').find('#zone_b').prop('checked',false);
                $(textbox).closest('tr').find('#zone_c').prop('checked',false);
                $(textbox).closest('tr').find('#zone_cash').prop('checked',false);
                $(textbox).closest('tr').find('#zone_ts').prop('checked',false);
        var dest_zone_check_station = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{valueofname,dest_zone_check_station},
            dataType:"text",
            success:function(json)
            {
                var data = JSON.parse(json);
                //.(((empty($destinationrow->zonecode_a) || $destinationrow->zonecode_a==$zone_code)  && $zone_type=='credit' )?'':'Disabled').
                $(textbox).closest('tr').find('.dynamic-dest_name').val(data['dest_name'].toUpperCase());
                $(textbox).closest('tr').find('#zone_a')    .prop('disabled',(('<?=$zone_type?>'=='credit') ? false:true));
                $(textbox).closest('tr').find('#zone_b')    .prop('disabled',(('<?=$zone_type?>'=='credit') ? false:true));
                $(textbox).closest('tr').find('#zone_c')    .prop('disabled',(('<?=$zone_type?>'=='credit') ? false:true));
                $(textbox).closest('tr').find('#zone_cash') .prop('disabled',(('<?=$zone_type?>'=='cash') ? false:true));
                $(textbox).closest('tr').find('#zone_ts')   .prop('disabled',(('<?=$zone_type?>'=='ts') ? false:true));
                //.(((empty($destinationrow->zonecode_a) || $destinationrow->zonecode_a==$zone_code)  && $zone_type=='credit' )?'':'Disabled').
                $(textbox).closest('tr').find('#zone_a')    .attr('onclick','showwarning(this,"'+data['zonecode_a'].trim()+'","'+valueofname+'")');
                $(textbox).closest('tr').find('#zone_b')    .attr('onclick','showwarning(this,"'+data['zonecode_b'].trim()+'","'+valueofname+'")');
                $(textbox).closest('tr').find('#zone_c')    .attr('onclick','showwarning(this,"'+data['zonecode_c'].trim()+'","'+valueofname+'")');
                $(textbox).closest('tr').find('#zone_cash') .attr('onclick','showwarning(this,"'+data['zone_cash'].trim()+'","'+valueofname+'")');
                $(textbox).closest('tr').find('#zone_ts')   .attr('onclick','showwarning(this,"'+data['zone_ts'].trim()+'","'+valueofname+'")');
                //(data['zonecodea'].trim()=='' || data['zonecodea']==zone_number) ? '':
            }
        });
        //check destcode and disable destinations
    }
</script>
<script>
    function showwarning(checkbox,assigned_zone,destination){
        if(assigned_zone.trim().length>0 && $(checkbox).prop('checked')==true){
            swal({
            title: "Are you sure?",
            text: "You Want to move "+destination+" from zone "+assigned_zone+" to "+zonecode+"",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {

                if (willDelete) {
                    
                } else {
                    $(checkbox).prop('checked',false);
                }
            });
        }
    }
</script>
<script>
    function setautocomplete(){
           $("#zone_name").autocomplete({
            source:[destination]
        });
    }
</script>