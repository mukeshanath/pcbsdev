<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 
  
function authorize($db,$user)
{
   
    $module = 'procurement';
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
  
error_reporting(0);
 function loadstatus($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM status ORDER BY sid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stat_type.'">'.$row->stat_type.'</option>';
         }
         return $output;    
        
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
 function requestno($db,$stationcode)
    {
        //$cnotepono='';

        $purno=$db->query("SELECT TOP 1 cnotepono FROM cnote WHERE cnote_station='$stationcode' AND cnotepono IS NOT NULL ORDER BY cnoteid DESC");
        $tablerow=$purno->fetch(PDO::FETCH_ASSOC);
        $lastpono=$tablerow['cnotepono'];
        if($purno->rowCount()== 0)
            {
            $cnotepono = $stationcode.'PO10001';
            }
        else{
            $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lastpono);
            $arr[0];//PO
            $arr[1]+1;//numbers
            $cnotepono = $arr[0].($arr[1]+1);
            }
         return $cnotepono;    
        
    }

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
              $query = $db->query("SELECT * FROM groups  WHERE stncode='$stationcode' ORDER BY gpid ASC");
             
               while ($row = $query->fetch(PDO::FETCH_OBJ)){
                  
              //menu list in home page using this code    
               $output .='<option  value="'.$row->cate_code.'">'.$row->cate_name.'('.$row->cate_code.')
               </option>';
               }
               return $output;    
          }


?>
<style>
.progress-bar {
    -webkit-transition: none !important;
    transition: none !important;
}
.progress{
    padding-left: 10%;
}
</style>
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  

<div id="col-form1" class="col_form lngPage col-sm-25">
<div class="au-breadcrumb m-t-75" style="margin-top:6px!important;">
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
                                        <a href="<?php echo __ROOT__ . 'procurementlist'; ?>">Cnote Procurement list</a>
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
                            Cnote Procurement Record Added successfully.
            </div>";
            }

            ?>

            </div>
            <form method="POST" action="<?php echo __ROOT__ ?>procurement" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="formitem" enctype="multipart/form-data">
            
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
            <input id="grp_type" name="grp_type" type="text" class="form-control input-sm" style="display:none">

	        <div class="boxed boxBt panel panel-primary">
            <div class="title_1 title panel-heading">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Procurement Form</p>
                  <div class="align-right" style="margin-top:-30px;">
            
           

            <a style="color:white" href="<?php echo __ROOT__ . 'procurementlist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
             
               </div> 
              
	        <div class="content panel-body">
	
            <div class="tab_form">

        
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->
        
        <div class="smsg summary" style="color:#3EA99F;"></div>
            <table id="formPOSpurchase" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">
        
		<tbody>
        <tr>		
			<td>Company Code</td>
			<td>
            <input id="stncode" name="station_code" type="text" value="" class="form-control input-sm" readonly>
            </td>
				
			<td>Company Name</td>
			<td>
				<input id="stnname" name="station_name" value="" class="form-control input-sm"  type="text" readonly>
			</td>
            </tr>
            <tr>
			
			<td>Vendor Code</td>
			<td>
                <div class="autocomplete col-sm-12">
				    <input type="text" class="form-control input-sm text-uppercase" name="vendor_code" id="vendor_code" onfocusout="fetchvendor()"> 
                    <p id="vendor_validation" class="form_valid_strings">Invalid Vendor Code.</p>
                </div>
			</td>	
			
		
			<td>Vendor Name</td>
			<td><div class="autocomplete col-sm-12">
                <input type="text" class="form-control input-sm text-uppercase" tabindex="-1" name="vendor_name" id="vendor_name"> 
                </div>
			</td>			
		        </tr>
            <tr>

            <td>PO No</td>
			<td>
            <input id="req_no" name="req_no" type="text" value="<?php echo requestno($db,$stationcode); ?>" class="form-control input-sm">
            </td>
            <td>Procure Date</td>
			<td>
            <input id="date" type="date" class="form-control input-sm today">
            </td>
            </tr>
            <tr>
            <td>Serial Type</td>
            <td>
            <select id="serial_type"  name="cnote_serial" class="form-control input-sm">
            <option value="0">Select Serial</option>
            <?php echo loadgroup($db,$user); ?>
            </select>
            </td> 
            <td>Quantity</td>
            <td>	
				  <input id="cnote_count" name="cnote_count" placeholder="No of C-Note" step="1000" class="form-control input-sm" oninput="if($(this).val()<=0){ $(this).val(''); }" type="number" value="">  
			</td>
            </tr>
           
            
                <tr  id="cnote_startends">        
                <td>Cnote Start</td>
                <td>         
                <input id="cnote_start" type="text" class="form-control input-sm" readonly>
                <p id="procrangeval" class="form_valid_strings">Cnote is Out of range or duplicated.</p>

                </td>
                <td>Cnote End</td>
                <td>         
                <input id="cnote_end" type="text" class="form-control input-sm" readonly>
                </td>
                </tr>
                </tbody>
         </table>
    <table>
        <input id="cnote_status" name="cnote_status" type="hidden" value="Procured" class="form-control input-sm" readonly>

        <div class="align-right btnstyle" >

        <button type="button" class="btn btn-primary btnattr_cmn" id="addcnote" name="addcnote" onclick="progress()">
        <i class="fa  fa-save"></i>&nbsp; Save
        </button>
                                                        

        <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
        <i class="fa fa-eraser"></i>&nbsp; Reset
        </button>   


        </div>
        </table>

	</div>

	</form>
    </div>
    
    </div>
    
	</div>
</div>

				
</section> 
                
<div class="progress active">
            <div class="progress-bar progress-bar-striped progress-bar-success"  style="width: 0%" ></div>
        </div>
<script>
   function progress(){
       //checking
       $('#addcnote').attr({"disabled": true, "readonly": true});
       if($('#serial_type').val()=='0'){
           swal("Please Select Serial Type");
       }
       else if(parseInt($('#cnote_count').val())<1 || $('#cnote_count').val().trim()==''){
           swal("Please Enter Quantity");
       }
       else{
       //execution
        $loadingtime = parseInt($('#cnote_count').val());
        $(".progress-bar").animate({
            width: "100%"
        }, $loadingtime);

        $('#header').css('filter','blur(5px)');
        $('nav').css('filter','blur(5px)');
        $('section').css('filter','blur(5px)');
        $('footer').css('filter','blur(5px)');
        $(document).on('keydown', disableF5);
        //$('#formitem').submit();
        createprocurement();
        }
    }
</script>
<script>//Create Procurement
        function createprocurement(){
        var req_no = $('#req_no').val();
        var vendor_code = $('#vendor_code').val();
        var station_code = $('#stncode').val();
        var user_name = $('#user_name').val();
        var cnote_serial = $('#serial_type').val();
        var cnote_count = $('#cnote_count').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>procurement",
            method:"POST",
            data:{
                req_no,
                vendor_code,
                station_code,
                user_name,
                cnote_serial,
                cnote_count
            },
            dataType:"text",
            // success:function(value)
            // {
            //     location.href='procurementlist?success=5';
            // }
            });
            location.href='procurementlist?success=5';
        }
</script>
<script>
 function disableF5(e) {
      if ((e.which || e.keyCode) == 116) e.preventDefault(); 
    };

</script>

<script>

$(window).on('load', function () {
   var usr_name = $('#user_name').val();
       
        $.ajax({
            url:"<?php echo __ROOT__ ?>cash",
            method:"POST",
            data:{usr_name:usr_name,action:"p_controller"},
            dataType:"text",
            success:function(value)
            {
                console.log(value);
            var data = value.split(",");
            console.log(data);
            $('#stncode').val(data[0]);
            $('#stnname').val(data[1]);
            $('#origin').val(data[0]);
            }
    });
  });
 
</script>
 <script>
   var vendorcode =[<?php  
    $sql=$db->query("SELECT vendor_code FROM vendor WHERE stncode='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
   
    echo "'".$row['vendor_code']."', ";
    }
 ?>];
    var vendorname =[<?php  
    $vname=$db->query("SELECT vendor_name FROM vendor WHERE stncode='$stationcode'");
    while($rowvname=$vname->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$rowvname['vendor_name']."', ";
    }
 ?>];
</script>

<script>
autocomplete(document.getElementById("vendor_code"), vendorcode);
autocomplete(document.getElementById("vendor_name"), vendorname);
</script>
<script>
    $(document).ready(function(){
    $('#cnote_count').keyup(function(){
    var cnote_count = $('#cnote_count').val();
    var cnote_start = $('#cnote_start').val();
            
            var cnoend = (parseInt(cnote_start)+parseInt(cnote_count))-1;
            if(!isNaN(cnoend)){
            $('#cnote_end').val(cnoend);
             }

    validateProcurmentRanges(cnote_start,cnoend,'<?=$stationcode?>',$('#serial_type').val(),$('#grp_type').val());
    });

    });
</script>
<script>  
    $('#serial_type').change(function(){
        $('#cnote_count').val('');
    var serial_type = $('#serial_type').val();
    var stncode2 = $('#stncode').val();
            
            $.ajax({
                url:"<?php echo __ROOT__ ?>cnotecontroller",
                method:"POST",
                data:{serial_type:serial_type,stncode2:stncode2},
                dataType:"text",
                success:function(value)
                {
                
                var data = value.split(",");
                console.log(value);
                $('#cnote_start').val(data[0]);
                $('#grp_type').val(data[1]);
                if($('#grp_type').val() == 'sp')
                    {
                        $('#cnote_startends').css('display','none');
                        $('#procrangeval').css('display','none');
                        $('#addcnote').attr({"disabled": false, "readonly": false});
                    }
                    else if($('#grp_type').val() == 'gl')
                    {
                        $('#cnote_startends').css('display','');
                    }
                }
      });
     });
 </script>
<script>
        //Fetch Vendor Name and validation
        function fetchvendor(){
        var vendor_code = $('#vendor_code').val();
        var stncode_ven = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{vendor_code:vendor_code,stncode_ven:stncode_ven},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#vendor_name').val(data[0]);
            if(data[1]==0){
                $('#vendor_validation').css('display','block');
                $('#addcnote').attr('type','button');
            }
            else{
                $('#vendor_validation').css('display','none');
                $('#addcnote').attr('type','submit');
            }
            }
            });
        }
</script>
<script>
  $('#vendor_name').focusout(function(){
        var vend_name = $(this).val();
        var vend_stn = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{vend_name:vend_name,vend_stn:vend_stn},
            dataType:"text",
            success:function(vend_code)
            {
                $('#vendor_code').val(vend_code);
                fetchvendor();
            }
    });
 });
</script>
<script>
    function validateProcurmentRanges(proc_start,proc_end,proc_comp_code,proc_serial,proc_type){
        if($('#grp_type').val().trim() == ''){
            swal("Please Select Category");
        }
        else if($('#grp_type').val() == 'gl')
        {
            if(parseInt(proc_start)>0 && parseInt(proc_end)>0)
            {
                $.ajax({
                    url:"<?php echo __ROOT__ ?>cnotecontroller",
                    method:"POST",
                    data:{
                        proc_start,
                        proc_end,
                        proc_comp_code,
                        proc_serial,
                        proc_type
                    },
                    dataType:"text",
                    success:function(value)
                    {
                        if(value==0){
                            $('#procrangeval').css('display','block');
                            $('#addcnote').attr({"disabled": true, "readonly": true});
                        }
                        else{
                            $('#procrangeval').css('display','none');
                            $('#addcnote').attr({"disabled": false, "readonly": false});
                        }
                        console.log(value);
                    }
                });
            }
        }
        else if($('#grp_type').val() == 'sp'){
            $('#procrangeval').css('display','none');
            $('#addcnote').attr({"disabled": false, "readonly": false});
        }
    }
</script>
<script>
    $(document).ready(function(){
        $("#serial_type").change(function(){
            console.log($(this).val());
        
         var cnote_serial = $(this).val();
         var stockrange_stn = $('#stncode').val();
         var cnote_start = $('#cnote_start').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{cnote_start,stockrange_stn,cnote_serial,action : 'stock_alert'},
            dataType:"text",
            success:function(value)
            {
               
            stock = JSON.parse(value);
            console.log(stock);
          
                if(stock[0]=="1"){
                swal("Cnotes are going Out Of Stock");
            }else{
               
            }
        
                
            
           
            }
    });
       });
    });
</script>
