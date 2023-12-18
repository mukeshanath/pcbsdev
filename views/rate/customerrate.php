<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'customerrate';
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
  
function loadmode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM mode ORDER BY mid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->mode_code.'">'.$row->mode_name.'</option>';
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

 //branch filter conditions
 $sessionbr_code = $datafetch['br_code'];
if(!empty($sessionbr_code)){
    $br_code = $sessionbr_code;
    $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
    $br_name     = $fetchbrname->br_name;
    }
 //branch filter conditions

if(isset($_GET['cust_code'])){
    $cust_code = $_GET['cust_code'];
    $getcustdata = $db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$stationcode'");
    $row = $getcustdata->fetch(PDO::FETCH_OBJ);
    $getbranch = $db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND br_code='$row->br_code'")->fetch(PDO::FETCH_OBJ);
    
    $br_code = $row->br_code;
    $br_name = $getbranch->br_name;
    $cust_name = $row->cust_name;
    $contract_period = $row->contract_period;
    $date_contract = $row->date_contract;
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
                                        <a href="<?php echo __ROOT__ . 'customerratelist'; ?>">Rate list</a>
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
                                  Customer Rate Record Added successfully.
                  </div>";
                  }

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 1 )
                  {
                       echo 
                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Mode Already added to this Customer.Please choose another mode.
                  </div>";
                  }
                  if ( isset($_GET['zonedest']))
                  {
                       echo 
                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Zone or Destination Entered is Not Valid. Please Enter Valid Destination.
                  </div>";
                  }

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 5 )
                  {
                       echo 
                  '<script> swal({
                    text: "Contract code is Already Added",
                    icon: "info",
                     buttons: true,
                    }).then((okey)=>{
                        window.location.reload();  
                    });</script>';
                  }
                  
               ?>

             </div>
      <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>customerrate" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Rate</p>
        <div class="align-right" style="margin-top:-35px;">

        <a style="color:white" href="<?php echo __ROOT__ ?>customerratelist"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>

          </div>
        </div> 
        
        <div class="content panel-body" >
        <div class="tab_form" style="background:#fff;border: 1px solid var(--heading-primary);">

        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>
            <td>Company</td>
            <td style="width:100px;">
                <input placeholder="" class="form-control stncode" name="stncode" type="text"  id="stncode" readonly>
            </td>
            <td>
                <input class="form-control border-form "  name="stnname" type="text" id="stnname" value=""  placeholder="Station Name" readonly>
            </td>
            <td>Branch</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input id="br_code" name="br_code" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_code))?$br_code:''; ?>" class="form-control input-sm br_code" type="text" required onfocusout="fetchcustomer()">
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
            </div>
            </td>
            <td><div class="col-sm-12">
                <input id="br_name" name="br_name" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_name))?$br_name:''; ?>" class="form-control input-sm" type="text" required></div>
            </td>
            <td>Customer</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input style="text-transform:uppercase;" value="<?php  if(isset($_GET['cust_code'])) {echo $_GET['cust_code'];} ?>" class="form-control cust_code" name="cust_code" type="text" id="cust_code">
                <p id="custcode_validation" class="form_valid_strings">Enter Valid Customer Code</p>
            </div>
            </td>
            <td><div class="autocomplete">
                <input class="form-control border-form " value="<?php  if(isset($_GET['cust_code'])) {echo $cust_name; }  ?>" name="cust_name" type="text" id="cust_name" placeholder="Customer Name" >
            </div></td>	
        </tr>
         <tr>
            <td>Rate Type</td>
            <td>
                <select name="rate_type" id="rate_type">
                <option value="CR">Customer Rate</option>  
                <option value="MSR">MSR</option>  
                <option value="BR">Board Rate</option>  
                </select> 
            </td>	
            <td></td>
            <!-- <td><div class="col-sm-3">File</div>
            <div class="col-sm-9">
                <input type="file" class="form-control input-sm" name="file"></div></td>		 -->
            
            <td>Contract Period and Date</td>
            <td style="width:100px;">
                <input class="form-control border-form" value="<?php  if(isset($_GET['cust_code'])) echo $contract_period; ?>" placeholder="Period(months)" name="contract_period"  id="contract_period"  readonly>
            </td>
            <td>
                <input class="form-control border-form" value="<?php  if(isset($_GET['cust_code'])) echo $date_contract; ?>" name="date_contract"  id="date_contract"  readonly>
            </td>	
            <td> &nbsp; Effective</td>
            <td>date</td>
            <td>
                <input type="date" class="form-control input-sm today" value="" id="effective_date">
            </td>	
        </tr>         
        </tbody>
        </table>
        </div>

        <!-- Rate Breakup form -->
        <div class="title_1 title panel-heading" style=" margin-top:15px;">
            <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Rate Entry Form</p> 
            <div class="align-right" style="margin-top:-35px;">

<button type="button" class="btn btn-success btnattr" style="display:none" onclick="creatediv()">
 <i class="fa fa-plus" ></i>
</button>

  </div>    
        </div>
        <div class="col-sm-12" style="padding-right:15px;border:1px solid var(--heading-primary);background:#fff;float:none">
        <table class="table">
			<tbody>
                <tr>
                    <td>Origin</td>
                    <td>Zone</td>
                    <td>Destination</td>
                    <td></td>
                    <td>Mode</td>
                    <td>Calc Method</td>
                    <td>Spl Charge</td>
                </tr>  
                <tr>
                    <td class="col-sm-2">
                        <input class="form-control stncode"  name="origin" type="text" id="origin" >
                    </td>
                    <td>
                        <input type="radio" class="check" name="zone_dest" value="zone" id="zone" checked>
                    </td>
                    <td class="col-sm-1">
                        <input type="radio"  class="check" name="zone_dest" value="destination" id="dest">
                    </td>
                    <td class="col-sm-2"><div class="autocomplete">
                        <input type="text"  name="zonedest" class="destination" id="zoneautocomplete">
                        <p id="zone_validation" class="form_valid_strings">Enter Valid Zone</p></div>
                        <!-- <p id="custempty_validation" class="form_valid_strings">Enter Customer Code 1st</p></div> -->
                        <div class="autocomplete">
                        <input type="text" class="destination text-uppercase" id="destinationautocomplete" style="display:none">
                        <p id="destination_validation" class="form_valid_strings">Enter Valid Destination</p></div>
                    </td>
                    <td class="col-sm-2">
                        <select class="form-control border-form "  name="mode"  id="mode" >
                            <?php echo loadmode($db); ?>
                        </select>
                    </td>
                    <td class="col-sm-2">
                        <select class="form-control border-form "  name="calcmethod"  id="calcmethod">
                           
                            <option value="Direct">Direct</option>
                            <option value="Indirect">Indirect</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control "  name="splcharges" type="text" id="splcharges">
                    </td>
				</tr>
           

			</tbody>
		    </table>
        <!-- </div>

            <div  class="col-sm-12" style="border :1px solid var(--heading-primary) ;background:#fff;margin-top:15px"> -->
          
            <table  id="t1" class="table custratedynamic" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td>On Add</td>
                    <td>Lower Wt</td>
                    <td>Upper Wt</td>
                    <td>Rate</td>
                    <td>Multiple</td>  
                </tr>
            <tbody  id="dynamic_form1">
                <tr>
                    <td><input class="rate_detil onadd"  value="0" onkeyup="onadd()" type="text" name="onadd[]"></td>
                    <td><input class="rate_detil lowerwt" id="lower_wt1" value="0.001" type="text" name="lowerwt[]" readonly></td>
                    <td><input class="rate_detil upperwt" id="upper_wt1" onkeyup="upper()" type="text" name="upperwt[]" required></td>
                    <td><input class="rate_detil" type="text" name="rate[]" required></td>
                    <td><input class="rate_detil multiple" value="0" type="text" name="multiple[]">
                    <td><span>
                    <button style='margin-left:0px;' type="button" class="btn btn-success btn-xs" onclick="generateweightband()"><i class="fa fa-plus-circle"></i></button>
                    </span></td>
                    </td>
                </tr>
            </tbody>
            </table>

            </div>
            <!-- Dynamic forms -->
           
            
            <div id="share">
               

            </div>
              <!-- end -->
         </div> 
         <div  style="background:#fff;padding:10px;margin-top:15px;border:1px solid var(--heading-primary) ">
         <table class="table">
                <tr>	           
                    <div class="align-right" style="margin-top:0;">
                   
                    <button type="submit" class="btn btn-success btnattr_cmn" name="addananothercustomerrate">
                    <i class="fa fa-save"></i>&nbsp; Save & Add
                    </button>

                    <button type="submit" class="btn btn-primary btnattr_cmn" name="addcustomerrate">
                    <i class="fa fa-save"></i>&nbsp; Save
                    </button>

                    <button type="button" class="btn btn-warning btnattr_cmn" onclick="reset()">
                    <i class="fa fa-eraser"></i>&nbsp; Reset
                    </button> 

                    </div>

                </tr>

            </table>
            </div>
  </form>
  </div>

  </div>
</div>
</div>

</section> 


<script>

 //Dynamic field 
  var i=1;
  var j=1;
function generateweightband(){
     i++;
     $("#dynamic_form"+j+"").append('<tr id="weightrow"><td><input type="text" class="onadd" value="0" onkeyup="onadd()" name="onadd[]"></td><td><input type="text"  id="lower_wt'+i+'" class="lowerwt" name="lowerwt[]" readonly></td><td><input type="text"  onkeyup="upper()"  id="upper_wt'+i+'" class="upperwt" name="upperwt[]" required></td><td><input type="text"  name="rate[]" required></td><td><input type="text" class="multiple" value="0" name="multiple[]"></td><td><button type="button" style="margin-left:0px;margin-top:0px;" class="btn btn-danger btn-xs" onclick="remove(this)" id="remove" ><i class="fa fa-minus-circle"></i></button></td></tr>');
     }
     $('#t1').on('click','.btn-danger',function(){
        if(confirm("Are You sure Want to delete") == true)
        {
            $(this).closest('tr').remove();
        }
        else{
            return false;
        }
    });

</script>

<!-- Dynamic Rate Breakup Form -->
<script>
    function creatediv(){
        j++;
    $("#share").append('<div id="dynamicratebreakup"><div class="title_1 panel-heading" style=" margin-top:15px;"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Rate Breakup Form ('+j+')</p><div class="align-right" style="margin-top:-35px;"><button type="button" class="btn btn-danger btnattr" onclick="removeratediv()"><i class="fa fa-times" ></i></button></div></div><div class="col-sm-12" style="padding-right:15px;float:none;border:1px solid var(--heading-primary);background:#fff"><table class="table"><tbody><tr><td>Origin</td>                 <td>Zone</td>                    <td>Destination</td> <td></td><td>Mode</td><td>Calc Method</td><td>Spl Charge</td></tr><tr>        <td class="col-sm-2">         <input class="form-control stncode"  name="origin" type="text" id="origin" >       </td>               <td>                <input type="radio" class="check" name="zone_dest" value="zone" id="zone"> </td><td class="col-sm-1">             <input type="radio"  class="check" name="zone_dest" value="destination" id="dest">       </td>        <td class="col-sm-2"><div class="autocomplete">   <input type="text"  name="zonedest" class="destination" id="zoneautocomplete">   <p id="zone_validation" class="form_valid_strings">Enter Valid Zone</p></div>   <div class="autocomplete">   <input type="text" class="destination" id="destinationautocomplete" style="display:none">          <p id="destination_validation" class="form_valid_strings">Enter Valid Destination</p></div>  </td>      <td class="col-sm-2">               <select class="form-control border-form "  name="mode"  id="mode" >       <option value="0">Select Mode</option>          <?php echo loadmode($db); ?>          </select>              </td>            <td class="col-sm-2">             <select class="form-control border-form "  name="calcmethod"  id="calcmethod"><option value="Direct">Direct</option><option value="Indirect">Indirect</option>  </select>    </td>    <td>                    <input class="form-control "  name="splcharges" type="text" id="splcharges"> </td></tr></tbody></table><table  id="t1" class="table custratedynamic" cellspacing="0" cellpadding="0" align="center"><tr><td>On Add</td> <td>Lower Wt</td>       <td>Upper Wt</td><td>Rate</td>      <td>Multiple</td>  </tr><tbody  id="dynamic_form'+j+'">  <tr>            <td><input class="rate_detil" value="0.00" type="text" name="onadd[]"></td>    <td><input class="rate_detil" id="lower_wt1" value="0.001" type="text" name="lowerwt[]" readonly></td>         <td><input class="rate_detil" id="upper_wt1" onkeyup="upper()" value="0.00" type="text" name="upperwt[]"></td>           <td><input class="rate_detil" value="0.00" type="text" name="rate[]"></td>           <td><input class="rate_detil" value="0.00" type="text" name="multiple[]">      <td><span>    <button style="margin-left:0px;" type="button" class="btn btn-success btn-xs" onclick="generateweightband()"><i class="fa fa-plus-circle"></i></button>          </span></td>    </td>     </tr>  </tbody> </table></div>');
    }
    function removeratediv(){
        $("#dynamicratebreakup").remove();
    }
</script>
<script>
  $(document).on('keyup', ".onadd",function () {
       $(this).closest('tr').find('td').find('.multiple').val($(this).val());
    });
    </script>
<script>
            //zone,dest
            $('#zone').click(function(){
                //alert("zone clicked");
                $('#zoneautocomplete').css('display','block');
                $('#zoneautocomplete').attr('name','zonedest');
                $('#destinationautocomplete').css('display','none');
                $('#destinationautocomplete').attr('name','');
                $('#destination_validation').css('display','none');
            }); 
            $('#dest').click(function(){
                //alert("Destination clicked");
                $('#zoneautocomplete').css('display','none');
                $('#zoneautocomplete').attr('name','');
                $('#destinationautocomplete').css('display','block');
                $('#destinationautocomplete').attr('name','zonedest');
                $('#zone_validation').css('display','none');
            }); 
</script> 
<script>
   $(document).on('keyup', ".upperwt",function () {
       $(this).closest('tr').next('tr').find('td').find('.lowerwt').val((parseFloat($(this).val())+parseFloat(0.001)).toFixed(3));
    });
</script>

<script>
  
   function customercode(){
        var cust_rate = $('#cust_code').val();
        var getzn_stn = '<?=$stationcode?>';
        $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{cust_rate,getzn_stn},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#date_contract').val(data[0]);
            $('#contract_period').val(data[1]);
            //$('#cust_name').val(data[6]);
            $('#zone_destn').val(data[7]);
            }
    });

        $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{cust_destn:cust_rate,comp_destn:getzn_stn},
            dataType:"json",
            success:function(zoneautocomplete)
            {
                autocomplete(document.getElementById("zoneautocomplete"), zoneautocomplete); 
            }
        });
    }
</script>
<script>
//Verify Destination is Valid
    $('#destinationautocomplete').focusout(function(){
        var dest_code1 = $('#destinationautocomplete').val().split('-')[0];
        $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{dest_code1:dest_code1},
            dataType:"text",
            success:function(dest)
            {
              if(dest == 0){
              $('#destination_validation').css('display','block');
              }
              else{
              $('#destination_validation').css('display','none');
               }
            }
    });
    });
    //Verify Zone is Valid
    $('#zoneautocomplete').focusout(function(){
        var zone_code1 = $('#zoneautocomplete').val().split('-')[0];
        var cust_zone = $('.cust_code').val();
        var zoneval_stn = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{zone_code1:zone_code1,cust_zone:cust_zone,zoneval_stn},
            dataType:"text",
            success:function(zne)
            {
                // console.log(zne);
              if(zne == 0){
              $('#zone_validation').css('display','block');
              }
              else{
              $('#zone_validation').css('display','none');
               }
            }
    });
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
            $('#origin').val(data[0]);
            }
    });
  });
 </script>
 <script>
 

var destinationautocomplete =[<?php  
    $sql=$db->query("SELECT DISTINCT dest_code,dest_name FROM destination where comp_code='$stationcode' ");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['dest_code']."-".$row['dest_name']."', ";
    }
 ?>];
</script>

<script>
autocomplete(document.getElementById("destinationautocomplete"), destinationautocomplete);
</script>
<script>
  var branchcode =[<?php  

    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode' AND br_active='Y'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];
  var branchname =[<?php  

    $brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND br_active='Y'");
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
  $(document).ready(function(){
        $('#br_code').focusout(function(){
            var br_code = $('#br_code').val().toUpperCase().trim();
            if(branches[br_code]!==undefined){
                $('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
                getcustomersjson();
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
                getcustomersjson();
            }
            else{
                $('#brcode_validation').css('display','block');
            }
        });

    });
</script>
<script>
    function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
</script>
<script> 

$(document).ready(function(){
     $('#cust_code').focusout(function(){
         var cust_code = $('#cust_code').val().toUpperCase().trim();
        if(customers[cust_code]!==undefined){
            $('#cust_name').val(customers[cust_code]);
            $('#custcode_validation').css('display','none');
        }
        else{
            $('#custcode_validation').css('display','block');
        }
        customercode();

     });
     //fetch code of entering name
     $('#cust_name').focusout(function(){
         var cust_name = $(this).val();
         var ccode = getKeyByValue(customers,cust_name);

         if(ccode!==undefined){
            $('#cust_code').val(ccode);
            $('#custcode_validation').css('display','none');
            }
            else{
                $('#custcode_validation').css('display','block');
            }
            customercode();

     });
});

</script>
<script> 
customers = {}

function getcustomersjson(){
   //get customers json
   var branch8 = $('#br_code').val().toUpperCase();
   var comp8 = $('#stncode').val().toUpperCase();
   $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{getjsoncust_br:branch8,getjsoncust_stn:comp8},
       dataType:"text",
       success:function(customerjson)
       {
          customers = JSON.parse(customerjson);
       }
       });
    }
</script>
<script>
function fetchcustomer(){
   var branch8 = $('#br_code').val();
   var comp8 = $('#stncode').val();
       $.ajax({
       url:"<?php echo __ROOT__ ?>ratecontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(ratecustcode)
       {
           autocomplete(document.getElementById("cust_code"), ratecustcode); 
       }
       });
       $.ajax({
       url:"<?php echo __ROOT__ ?>ratecontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(ratecustname)
       {
           autocomplete(document.getElementById("cust_name"), ratecustname); 
       }
       });
}
</script>
<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cust_code').value = "";
    document.getElementById('cust_name').value = "";
    document.getElementById('zoneautocomplete').value = "";
    document.getElementById('destination_validation').value = "";
    document.getElementById('mode').value = "";
    document.getElementById('calcmethod').value = "";
    document.getElementById('splcharges').value = "";   
}
</script>