<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'receiveform';
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

//Generating Request Number
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

if(authorize($db,$user)=='False'){

    echo "<div class='alert alert-danger alert-dismissible'>
         
            <h4><i class='icon fa fa-times'></i> Alert!</h4>
            You don't have permission to access this Page.
            </div>";
            exit;
  }
  
if (isset($_GET['cnotereceiveid'])) {
    $cnotereceiveid = $_GET['cnotereceiveid'];
    $sql = $db->query("SELECT * FROM cnotereceive WHERE cnotereceiveid = '$cnotereceiveid'");
    $row = $sql->fetch();
    $cnotepono         = $row['cnotepono'];
    $cnote_serial      = $row['cnote_serial'];
    $cnote_station     = $row['cnote_station'];
    $cnote_count       = $row['cnote_count'];
    $cnote_start       = $row['cnote_start'];
    $cnote_end         = $row['cnote_end'];
    $pending_count     = $row['pending_count'];
    $prev_received_count    = $row['received_count'];

    $type = $db->query("SELECT grp_type FROM groups WHERE cate_code='$cnote_serial'");
    $type_row = $type->fetch();
}
?>

<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


<div id="col-form1" class="col_form lngPage col-sm-25">
<div class="au-breadcrumb m-t-75" style="margin-bottom:-5px;">
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
                                        <a href="<?php echo __ROOT__ . 'receivelist'; ?>">Cnote Receive list</a>
                                      </li>
                                        </ul>
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <form method="POST" action="<?php echo __ROOT__ ?>receiveform" accept-charset="UTF-8" class="form-horizontal" id="formcnoteallot" enctype="multipart/form-data">
            <input class="form-control border-form" name="user_name" type="text" id="user_name" style="display:none;" value="<?php echo $_SESSION['user_name']; ?>" hidden>
            <input class="form-control border-form" name="cnotereceiveid" type="text" id="cnotereceiveid" style="display:none;" value="<?=$cnotereceiveid; ?>" hidden>
            <input class="form-control border-form" name="prev_received_count" type="text" id="prev_received_count" style="display:none;" value="<?=$prev_received_count; ?>" hidden>
            <input class="form-control border-form" name="cnote_station" type="text" id="cnote_station" style="display:none;" value="<?=$cnote_station; ?>" hidden>
            <input class="form-control border-form" name="group_type" type="text" id="group_type" style="display:none;" value="<?=$type_row['grp_type']; ?>" hidden>

	        <div class="boxed boxBt panel panel-primary">
            <div class="title_1 title panel-heading">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:10px;">Cnote Receive Form</p>
                  <div class="align-right" style="margin-top:-30px;">
            
           

            <a style="color:white" href="<?php echo __ROOT__ . 'receivelist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
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
                                Cnote Procurement Record Added successfully.
                </div>";
                }

                
                ?>

                </div>
	        <div class="content panel-body">
	
            <div class="tab_form">

        
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->
        
        <div class="smsg summary" style="color:#3EA99F;"></div>
            <table id="formPOSpurchase" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">
        
		<tbody>
        <tr>		
			<td>Purchase Order No</td>
			<td>
      <input class="form-control border-form" value="<?= $cnotepono; ?>"  name="cnotepono" type="text" id="cnotepono" readonly tabindex="-1">
      </td>
				
			<td>Cnote Serial</td>
			<td>
				<input id="cnote_serial" name="cnote_serial" value="<?= $cnote_serial; ?>" class="form-control input-sm"  type="text" readonly tabindex="-1">
			</td>
			<td>Cnote Count</td>
			<td>
				<input id="cnote_count" name="cnote_count" value="<?= $cnote_count; ?>" class="form-control input-sm"  type="text" readonly tabindex="-1">
			</td>
			<td>Cnote Start</td>
			<td>
                <input id="cnote_start" type="text" value="<?= $cnote_start; ?>" name="cnote_start" readonly tabindex="-1">
            </td>	
			
		
			<td>Cnote End</td>
			<td>
          <input class="form-control border-form "  name="cnote_end" type="text" id="cnote_end" value="<?= $cnote_end; ?>" readonly tabindex="-1">
			</td>			
	   
							
		</tr>
        <tr>
            <td>Received Count</td>
			<td>
                 <input  class="form-control border-form received_count" onkeyup="fax_val(this)" name="received_count" type="text" oninput="this.value =!!this.value && Math.round(this.value) >= 0 ? Math.round(this.value) : null" id="received_count" autofocus>
                 <p id="receive_val" class="form_valid_strings">*Please enter Lower then Pending Value.</p>
                 <!-- <p id="fax_val" class="form_valid_strings">*Enter Only Numbers.</p> -->

            </td>
            <td>Pending Count</td>
			<td>
                 <input  class="form-control border-form" name="pending_count" type="text" id="pending_count" value="<?=$pending_count;?>" tabindex="-1" readonly>
			</td>
        </tr>
        <tr id="podstartend">
            <td>Print POD Start</td>
			<td>
                 <input class="form-control border-form" name="printpod_start" type="text" id="printpod_start" >
                 <p id="procrangeval" class="form_valid_strings">Cnote Range is Not valid or duplicated.</p>
            </td>
            <td>Print POD End</td>
			<td>
                 <input  class="form-control border-form" name="printpod_end" type="text" id="printpod_end"  tabindex="-1" readonly>
			</td>
        </tr>
          
            </tbody>
    </table>
            <table>

            <div class="align-right btnstyle" >

            <button id="submit_receive" type="button" onclick="submitform()" class="btn btn-primary btnattr_cmn" name="addreceived">
            <i class="fa  fa-save"></i>&nbsp; Save
            </button>
                                                            

            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
            <i class="fa fa-eraser"></i>&nbsp; Reset
            </button>   
            

            </div>
            </table>

    <div class="col-sm-12 data-table-div">
    <div class="title_1 title panel-heading">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Receive Detail List</p>
                  <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
               </div>
    <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>
            
                    <th >Purchase Order No</th>
                    <th >Cnote Station</th>
                    <th >Cnote Serial</th>
                    <th >Total Count</th>
                    <th >Cnote Start</th>
                    <th >Cnote End</th>
                    <th >Status</th>
                    <th >Count Received</th>
                    <th >Count Pending</th>
                    
                    
              </tr>
    </thead>
              <tbody>
                  <?php 
                     
                     //Normal Station list
                     
                     $query = $db->query("SELECT * FROM cnotereceivedetail WHERE cnotepono='$cnotepono' AND cnote_serial='$cnote_serial' ORDER BY cnotereceivedetid ASC");
 
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                          
                            echo "<td>".$row->cnotepono."</td>";
                            echo "<td>".$row->cnote_station."</td>";
                            echo "<td>".$row->cnote_serial."</td>";
                            echo "<td>".$row->cnote_count."</td>";
                            echo "<td>".$row->cnote_start."</td>";
                            echo "<td>".$row->cnote_end."</td>";
                            echo "<td>".$row->cnote_status."</td>";
                            echo "<td>".$row->received_count."</td>";
                            echo "<td>".$row->pending_count."</td>";
                           
                                   echo "</tr>";
                           
                                                    
                          }

                          
                      ?>

                   
                 </tbody>
            </table>
</div>
	
	</div>

	</form>
	</div>
	</div>
	</div>
</div>
					
</section>

<script>
$(document).ready(function(){
     $('.received_count').keyup(function(){
     $('#cnote_end').val(parseInt($('#cnote_start').val())+parseInt($('.received_count').val())-1);                                                                                                                                       
    });

    $('.received_count').keyup(function(){
            var received_count = parseInt($('.received_count').val());
            var pending_count  = parseInt($('#pending_count').val())
            if(received_count > pending_count || $('.received_count').val().trim() == '')
            {
                $('#receive_val').css('display','block');
                $('#submit_receive').attr('type','button');
                $('#submit_receive').attr('onclick','podalert()');
            }
            else if($('#group_type').val().trim()=='sp' && $('#printpod_start').val().trim()==''){
                $('#receive_val').css('display','none');
                $('#submit_receive').attr('type','button');
                $('#submit_receive').attr('onclick','podalert()');
            }                                               
            else if($('#group_type').val().trim()=='sp' && $('#printpod_end').val().trim()==''){
                $('#receive_val').css('display','none');
                $('#submit_receive').attr('onclick','podalert()');
                $('#submit_receive').attr('type','button');
            }                                               
            else
            {
                $('#submit_receive').attr('onclick','');
                $('#receive_val').css('display','none');
                $('#submit_receive').attr('type','button');
                $('#submit_receive').attr('onclick','savereceive()');
            }   
            // if($("#receive_val").css("display")==="block" || if($("#stnname_val").css("display")==="block"))
            // {
            //     $('#submit_receive').attr('type','button');
            // }
            // else{
            //     $('#submit_receive').attr('type','submit');
            // }                                                                                        
    });
      
});
</script> 
<script>//Create Procurement
        function savereceive(){
        var cnotepono = $('#cnotepono').val();
        var cnotereceiveid = $('#cnotereceiveid').val();
        var cnote_serial = $('#cnote_serial').val();
        var cnote_station = $('#cnote_station').val();
        var cnote_count = $('#cnote_count').val();
        var cnote_start = $('#cnote_start').val();
        var cnote_end = $('#cnote_end').val();
        var user_name = $('#user_name').val();
        var received_count = $('#received_count').val();
        var prev_received_count = $('#prev_received_count').val();
        var pending_count = $('#pending_count').val();
        var printpod_start = $('#printpod_start').val();
        var printpod_end = $('#printpod_end').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>receiveform",
            method:"POST",
            data:{
                cnotepono,
                cnotereceiveid,
                cnote_serial,
                cnote_station,
                cnote_start,
                cnote_end,
                user_name,
                received_count,
                prev_received_count,
                pending_count,
                printpod_start,
                printpod_end,
                cnote_count
            },
            dataType:"text",
            // success:function(value)
            // {
            //     location.href='procurementlist?success=5';
            // }
            });
            location.href='receivelist?success=5';
        }
</script>
<script>
$('#printpod_start').keyup(function(){
    var pod_spl_end = (parseInt($('#printpod_start').val()) + parseInt($('#received_count').val())) - 1;
    $('#printpod_end').val(pod_spl_end);
    $('.received_count').keyup();
    validate_special_cnotes($(this).val(),pod_spl_end,'<?=$stationcode?>',$('#cnote_serial').val(),'sp')
});
</script>
<script>
    function validate_special_cnotes(proc_start,proc_end,proc_comp_code,proc_serial,proc_type){
        if(parseInt(proc_start)>0 && parseInt(proc_end)>0)
        {
        $('#submit_receive').attr('type','button');
        $.ajax({
                    url:"<?php echo __ROOT__ ?>cnotecontroller",
                    method:"POST",
                    data:{proc_start,proc_end,proc_comp_code,proc_serial,proc_type},
                    dataType:"text",
                    success:function(value)
                    {
                        if(value==0){
                            $('#procrangeval').css('display','block');
                            $('#submit_receive').attr('type','button');
                            $('#submit_receive').attr('onclick','savereceive1()');
                        }
                        else{
                            $('#procrangeval').css('display','none');
                            $('#submit_receive').attr('type','button');
                            $('#submit_receive').attr('onclick','savereceive()');
                        }
                        console.log(value);
                    }
                });
        }
    }
</script>
<script>
$(window).on('load', function () {
    if($('#group_type').val() != 'sp')
    {
        $('#podstartend').css('display','none');
    }
});

function podalert(){
    swal("Please Enter POD cnote range");
}
</script>

<script>
function resetFunction() {   
    document.getElementById('received_count').value = "";
    }
</script>