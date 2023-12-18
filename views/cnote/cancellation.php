<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'cancellation';
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

  $sessionbr_code = $datafetch['br_code'];
   
?>
<style>
       html {
    height:100%;
    width:100%;
    margin:0%;
    padding:0%;
    overflow-x: hidden ;
}

body {
    overflow-x: initial !important;
}
.space {
  height: 10px;
}
.checkbox * {
  box-sizing: border-box;
  position: relative;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
.checkbox {
  display: inline-block;
}
.checkbox > input {
  display: none;
}
.checkbox > label {
  vertical-align: top;
  font-size: 18px;
  padding-left: 30px;
}
.checkbox > [type="checkbox"] + label:before {
  color: #777;
  content: '';
  position: absolute;
  left: 0px;
  display: inline-block;
  min-height: 20px;
  height: 20px;
  width: 20px;
  border: 2px solid #777;
  font-size: 15px;
  vertical-align: top;
  text-align: center;
  transition: all 0.2s ease-in;
  content: '';
}
.checkbox.radio-square > [type="checkbox"] + label:before {
  border-radius: 0px;
}
.checkbox.radio-rounded > [type="checkbox"] + label:before {
  border-radius: 25%;
}
.checkbox.radio-blue > [type="checkbox"] + label:before {
  border: 2px solid #ccc;
}
.checkbox > [type="checkbox"] + label:hover:before {
  border-color: #337ab7;
}
.checkbox > [type="checkbox"]:checked + label:before {
  width: 8px;
  height: 16px;
  border-top: transparent;
  border-left: transparent;
  border-color: #337ab7;
  border-width: 4px;
  transform: rotate(45deg);
  top: -4px;
  left: 4px;
}


  .tool-tip[title-new]:hover:after {
      content: attr(title-new);
      position: absolute;
      border: #c0c0c0 1px dotted;
      border-radius: 5px;
      padding: 10px;
      display: block;
      z-index: 100;
      background-color: #000000;
      color: #ffffff;
      width: 300px;
      text-decoration: none;
      text-align:center;
    }
</style>

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
                 if ( isset($_GET['success']) && $_GET['success'] == 1 || isset($_GET['success']) && $_GET['success'] == 5)
                 {
                      $message = (($_GET['success']==1)? 'Cancelled' : 'Noservice-return');
                    echo 
            "<div class='alert alert-success alert-dismissible'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <h4><i class='icon fa fa-check'></i> Alert!</h4>
                            Cnote Successfully  ".$message.".
            </div>";
            }

            ?>

            </div>
            <form method="POST" action="<?php echo __ROOT__ ?>cancellation" accept-charset="UTF-8" class="form-horizontal" id="cancelcnote" enctype="multipart/form-data">
            

	        <div class="boxed boxBt panel panel-primary">
            <div class="title_1 title panel-heading"style=" height:50px;">
                  <p  style="font-weight:600;font-size:23px;color:white;margin-top:10px;">Cancel Form</p>
                  <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
                        <i class="fa fa-times" ></i>
                </button></a>

                </div>
               </div> 
        	<div class="content panel-body">
            <ul class="nav nav-tabs" id="myTab4">
            <li class="active">
                    <a data-toggle="tab" href="#singlecnotecancel" >Single-Cancel</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#bulkcnotecancel">Bulk-Cancel</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#noservice_return">Noservice-return</a>
                </li>
                </ul>

            <div class="tab_form">

		
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none">

            
            <div class="tab-content">
            <div id="singlecnotecancel" class="tab-pane active" style="margin-left: 25px;">   

        	<table style="width:70%;margin-left:0;" id="formPOSpurchase" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">	
	    	<tbody>
            <tr>
                <td>Company Code</td>
                <td><input type="text" id="stncode" class="form-control border-form stncode" name="singlecancelstation" readonly></td>
                <td>Company Name</td>
                <td><input type="text" id="stnname" class="form-control border-form stnname" readonly></td>
            </tr>
            <tr>		
			<td class="">Cnote Number</td>
			<td>
                 <input type="text" class="form-control border-form " name="single_cnote" id="single_cnote">
            </td>
				
			
            <td class="">Serial Type</td>
<td>
<!-- //<input type="text" class="form-control border-form " name="single_serial" id="single_serial"> -->
                 <select class="form-control" name="single_serial" id="single_serial">
                        <option value="">Select Serial</option>

                    </select> 
			</td>
            </tr> 
          
         
            </tbody></table>
           
                <table>

                <div class="align-right btnstyle" >


                <button type="button" class="btn btn-primary btnattr_cmn" name="singlecancellation" id="submitcancel" onclick="verifycancel();">
                <i class="fa  fa-save"></i>&nbsp; Save
                </button>
                                                                

                <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                <i class="fa fa-eraser"></i>&nbsp; Reset
                </button>   


                </div>
                </table>
                </form>
           
	</div>
    <div id="bulkcnotecancel" class="tab-pane" style="margin-left: 25px;"> 
    <form method="POST" action="<?php echo __ROOT__ ?>cancellation" accept-charset="UTF-8" class="form-horizontal" id="formcnoteallot" enctype="multipart/form-data">
    <table style="width:70%;margin-left:0;" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">	
	    	<tbody>
            <tr>
                <td>Company Code</td>
                <td><input type="text" id="stncode" class="form-control border-form stncode" name="bulkcancelstation" readonly></td>
                <td>Company Name</td>
                <td><input type="text" id="stnname" class="form-control border-form stnname" readonly></td>
            </tr>    
            <tr>		
			<td>Cancel From</td>
			<td>
                 <input placeholder=""  class="form-control border-form" name="cancel_from" type="text" id="cancel_from">
            </td>
				
			<td>Count</td>
			<td>
                   <input placeholder=""  class="form-control border-form" name="cancel_count" type="text" id="cancel_count">
			</td>
            </tr>
            <tr>
            <td>Cancel To</td>
			<td>
                 <input class="form-control border-form "   name="cancel_to" type="text" id="cancel_to" >
			</td>   		
            <td>Cnote Serial</td>
<td>
                  <!-- <input class="form-control border-form " name="cancel_serial" type="text" id="cancel_serial" > -->
                <select class="form-control" name="cancel_serial" id="cancel_serial">
                        <option value="">Select Serial</option>

                    </select> 
                
                  <input class="form-control border-form " name="ss_u" type="hidden" id="ss_u" >

            </td>
            </tr>  
            </tbody>
            </table>
            
                <table>

                <div class="align-right btnstyle" >


                <button type="button" class="btn btn-primary btnattr_cmn" name="bulkcancellation" id="bulkcancellation" onclick=" bulkcancel();">
                <i class="fa  fa-save"></i>&nbsp; Save
                </button>
                                                                

                <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                <i class="fa fa-eraser"></i>&nbsp; Reset
                </button>   


                </div>
                </table>
           
        </div>
        <div id="noservice_return" class="tab-pane" style="margin-left: 25px;">   

<table style="width:70%;margin-left:0;" id="formPOSpurchase" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">	
<tbody>
  <tr>
  <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none">

      <td id="noservice_heading">Company Code</td>
      <td><input type="text" id="stncode" class="form-control border-form stncode" name="singlecancelstation" readonly></td>
      <td id="noservice_heading1">Company Name</td>
      <td><input type="text" id="stnname" class="form-control border-form stnname" readonly></td>
  </tr>
  <tr>		
<td class="" id="noservice_heading2">Cnote Number</td>
<td>
       <input type="text" class="form-control border-form " name="noservice_cnote" id="noservice_cnote">
  </td>


  <td class="" id="noservice_heading3">Serial Type</td>
<td>
<!-- //<input type="text" class="form-control border-form " name="single_serial" id="single_serial"> -->
       <select class="form-control" name="noservice_serial" id="noservice_serial">
              <option value="">Select Serial</option>

          </select> 
</td>
  </tr> 



  </tbody></table>
 
      <table>

      <div class="align-right btnstyle" >


      <button type="button" class="btn btn-primary btnattr_cmn" name="noservice" id="noservice" onclick="verifynoservice();">
      <i class="fa  fa-save"></i>&nbsp; Save
      </button>
                                                      

      <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
      <i class="fa fa-eraser"></i>&nbsp; Reset
      </button>   


      </div>
      </table>
      </form>
 
</div>
        
    </div>
	        </div>
           
            </div>
            </div>
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cancelled Cnote List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
        </div>
        <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text"></div>
               <div class="table-search-right">Date Filter<input type="month" class="table-search-right-datefilter this-month" id="datefilter"></div>
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
                      <th>Cnote Number</th>
                      <th>Cnote Status</th>
                      <th>Date Added</th>
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
      $conditions[] = "cnote_station='$stationcode'";  

    }else{
      $conditions[] = "cnote_station='$stationcode' AND br_code='$sessionbr_code'";  

    }
    
     

    $conditions[] = "(cnote_status LIKE'%VOID' OR cnote_status LIKE '%Noservice%')";  

    $conditions[] = "Month(date_added)='".get_current_month()."' AND Year(date_added)='".$y."'";

   
    $conditons = implode(' AND ', $conditions);
$query = $db->query("SELECT * FROM cnotenumber WITH(NOLOCK) WHERE $conditons order by cnote_number desc OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");
while ($row = $query->fetch(PDO::FETCH_OBJ))
                            {
                              echo "<tr>";
                              echo "<td class='center first-child'>
                                      <label>
                                        <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                          <span class='lbl'></span>
                                      </label>
                                    </td>";
                              echo "<td>".$row->cnotenoid."</td>";
                              echo "<td>".$row->cnote_station."</td>";
                              echo "<td>".$row->br_code."</td>";
                              echo "<td>".$row->cnote_serial."</td>";                            
                              echo "<td>".$row->cnote_station.$row->cnote_number."</td>";
                              echo "<td>".$row->cnote_status."</td>";
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

            <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM cnotenumber WHERE $conditons")->fetch(PDO::FETCH_OBJ);
            //  echo json_encode(SELECT COUNT(*) as pages FROM cash WHERE $conditons);
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
                </form>
            </div>
</section>

<!-- inline scripts related to this page -->
<script>
  $(document).on('click','#myTab4',function(e) {
    // use this if you want limit clicks in .directFilter only
    // $(document).on('click','.directFilter',function(e)
    $this = $(e.target);
    if($this.text().trim()=='Noservice-return'){
        $('.tab-content').css('background-color','#87CEEB');
    }else{
      $('.tab-content').css('background-color','');
     
    }
    console.log($this.text().trim());
});
</script>
<script>
$(document).ready(function(){
      $("#cancel_count, #cancel_serial").on('keyup change', function (){

        var cancel_from = $('#cancel_from').val(); 
        var cancel_to  = $('#cancel_to').val();      
        var cancel_count = $('#cancel_count').val();
        var cancel_serial = $('#cancel_serial').val();
        var stationcode = '<?=$stationcode?>';
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            async: false,
            data:{cancel_from:cancel_from,cancel_count:cancel_count,cancel_to:cancel_to,stationcode:stationcode,cancel_serial:cancel_serial},
            dataType:"text",
            
            success:function(value)
            {
            var data = JSON.parse(value);
            getResponse(data);
            $('#cancel_to').val(data[0]);
            // $('#cancel_serial').val(data[1]);
            }
    });
    });
  });
  </script>
  
<script>
   var data;
   var response;
function getResponse(response) {
    data = response;
    return data;
}
</script>
<script>
  function sum(array){
    var total = 0;
      $.each(array,function() {
      total += parseInt(this, 10);
     });
     return total;
  }
</script>
<script>
    function bulkcancel(){ 
      
     
      if($('#cancel_from').val().trim()==''){
        swal("Please enter From And To CnoteNumber");  
      }
      else if($('#cancel_count').val().trim()==''){
        swal("Please enter Cancel Count");
      }
     else if($('#cancel_serial').val().trim()=='Select Serial'){
        swal("Please Select Cnote Serial");
      }
      else if(data[5] > 0){
        swal("There is any one of the Cnote is Already  "+data[9]+"  Please Check!");

     }
     else if(data[2]  > 0){
      swal("Please compleate the Invoice!");

     }
     else if(data[6] > 0){
      swal("Please compleate the CC Invoice!");

     }
     else if(data[3]  > 0){
      swal("Please compleate the Opstatus!");

     }
     else if(sum(data[4]) > 0){
      swal("Please compleate the denomination");

     }
     else if(data[7] > 0){
      swal("This Cnote Is Return");

     }
     else{
         $('#bulkcancellation').attr('type','submit');
      }
    }
</script>

<script>
  $(document).ready(function(){
     $('#cancel_from').focusout(function(){
    var cnote_from = $('#cancel_from').val();
    var stationcode = '<?=$stationcode?>';

    $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        async: false,
        global: false,
        data:{stationcode,cnote_from:cnote_from},
        dataType:"text",
        success:function(data)
        {
          console.log(data);
          $('#cancel_serial').html(data);

        }
});
});
});

</script>

<script>
  $(document).ready(function(){
$('#single_cnote').keyup(function(){
    var cnote_from = $('#single_cnote').val();
    var stationcode = '<?=$stationcode?>';

    $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        async: false,
        global: false,
        data:{stationcode,cnote_from:cnote_from},
        dataType:"text",
        success:function(data)
        {
          console.log(data);
          $('#single_serial').html(data);

        }
});
});
});
</script>

<script>
  $(document).ready(function(){
$('#noservice_cnote').focusout(function(){
    var cnote_from = $('#noservice_cnote').val();
    var stationcode = '<?=$stationcode?>';

    $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        async: false,
        global: false,
        data:{stationcode,cnote_from:cnote_from},
        dataType:"text",
        success:function(data)
        {
          console.log(data);
          $('#noservice_serial').html(data);

        }
});
});
});

</script>
<script>
  $(document).ready(function(){
 $("#single_cnote, #single_serial").on('keyup change', function (){

    var single_cnote1 = $('#single_cnote').val();
    var stationcode = '<?=$stationcode?>';
    var single_serial = $('#single_serial').val();
    
    console.log(single_serial);
    $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        async: false,
        global: false,
        data:{stationcode,single_cnote1:single_cnote1,single_serial:single_serial},
        dataType:"text",
        success:function(data)
        {
			// cnote_serial = JSON.parse(data);
            // console.log(cnote_serial);
            console.log(data);
            result = JSON.parse(data);
            // $('#single_serial').val(cnote_serial[0]);  
            //$('#single_serial').val(result[0]);
            var invdata = result[1];
            var opdata = result[2];
            var billvoid = result[3];
            var returncnote = result[5];
            var denomination = result[6];
            var status = result[7];
           
        }
});
});
});
</script>

<script>
  $(document).ready(function(){
 $("#noservice_cnote, #noservice_serial").on('keyup change', function (){

    var single_cnote1 = $('#noservice_cnote').val();
    var stationcode = '<?=$stationcode?>';
    var single_serial = $('#noservice_serial').val();
    
    console.log(single_serial);
    $.ajax({
        url:"<?php echo __ROOT__ ?>cnotecontroller",
        method:"POST",
        async: false,
        global: false,
        data:{stationcode,single_cnote1:single_cnote1,single_serial:single_serial},
        dataType:"text",
        success:function(data)
        {
			// cnote_serial = JSON.parse(data);
            // console.log(cnote_serial);
            console.log(data);
            result_data = JSON.parse(data);
            // $('#single_serial').val(cnote_serial[0]);  
            //$('#single_serial').val(result[0]);
            var invdata = result_data[1];
            var opdata = result_data[2];
            var billvoid = result_data[3];
            var returncnote = result_data[5];
            var denomination = result_data[6];
            var status = result_data[7];
           console.log([invdata,opdata,billvoid,returncnote,denomination,status]);
        }
});
});
});
</script>
<script>
    $(document).ready(function(){
    $('#notdeliver').change(function(){
        if($(this).prop('checked') === true){
           $('#show').text($(this).attr('value',1));
        }else{
             $('#show').text($(this).attr('value',0));
        }
    });
});
  </script>
<script>

function verifycancel(){
      if($('#single_cnote').val().trim()==''){
        swal("Please enter a Cnote number to Cancel");
      }
     else if($('#single_serial').val().trim()=='Select Serial'){
        swal("Please Select Cnote Serial");
      }
      else if(result[3]=='1' || result[7].trim()=='Allotted-Void' || result[7].trim()=='Noservice-return'){
        swal("Cnote has Already "+result[7]+"");
      }
      else if(result[2]=='1'){
        swal("Cnote has Opdata - Cannot Cancel");
      }
      else if(result[1]=='1'){
        swal("Cnote is Already Invoiced - Cannot Cancel");

      }
      else if(result[6]=='1'){
        swal("This Cnote is in Denomination");

      }
    
      else{
        $('#submitcancel').attr('type','submit');
      }
    }
</script>
<script>

    function verifynoservice(){
      
      if($('#noservice_cnote').val().trim()==''){
        swal("Please enter a Cnote number to update Noservice-return");
      }
     else if($('#noservice_serial').val().trim()=='Select Serial'){
        swal("Please Select Cnote Serial");
      }
      else if(result_data[7]=='Allotted'){
        swal("This Cnote is Allotted and cannot update as Noservice");
      }
      else if(result_data[3]=='1' || result_data[7].trim()=='Allotted-Void' || result_data[7]=='Noservice-return'){
        swal("Cnote has Already  "+result_data[7]+"");
      } 
      else if(result_data[1]=='1'){
        swal("Cnote is Already Invoiced - Cannot Cancel");

      }
      else if(result_data[6]=='1'){
        swal("This Cnote is in Denomination");

      }
      else{
        $('#noservice').attr('type','submit');
      }
    }
</script>
<script>
function resetFunction() {
    document.getElementById('single_cnote').value = "";
    document.getElementById('single_serial').value = "";
    document.getElementById('cancel_from').value = "";
    document.getElementById('cancel_count').value = "";
    document.getElementById('cancel_to').value = "";
    document.getElementById('cancel_serial').value = "";
}
</script>
<script>
$(window).on('load', function () {
   var usr_name = $('#user_name').val();
   $('#ss_u').val(usr_name);
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
            $('#origin').val(data[0]);
            }
    });
  });

</script>
<script>
 $(document).on('click','.paginate-btn-nums',function(){   
    var inc = parseInt($(this).val())+1;
            var dec = parseInt($(this).val())-1;
            var curt = parseInt($(this).val());
           
            if(res[2].trim()=='Onchange'){
              var end = parseInt(Math.ceil(res[1]/10));
            }else{
              var end = parseInt(<?php echo ceil($noofpages->pages / 10); ?>);
            }
           
            if($(this).val()==0||$(this).val()>end){
              process.end;
            }

          var comp_branch;
           var datefilter = $('#datefilter').val().trim();
           var year = datefilter.split("-")[0];
           var month = datefilter.split("-")[1];
           var session_brcode = '<?=$sessionbr_code?>';
           if(session_brcode.length==0){
             comp_branch = "cnote_station='<?=$stationcode?>'";
           }else{
            comp_branch = "cnote_station='<?=$stationcode?>' AND br_code='"+session_brcode+"'";

           }

          
           const condition = [comp_branch, "(cnote_status LIKE'%VOID' OR cnote_status LIKE '%Noservice%')", " Month(date_added)='"+month+"' AND Year(date_added)='"+year+"'"];
             let conditions = condition.join(" AND ");




          $('#datatable-tbody').css('opacity','0.5');
          $('.paginate-btn-nums').removeClass('active');
   
    var dtbquery = "SELECT cnotenoid,cnote_station,cnote_serial,cnote_status,date_added,cnote_number,br_code FROM cnotenumber WITH(NOLOCK) WHERE  "+conditions+" order by cnote_number desc";

      var dtbcolumns = 'cnotenoid,cnote_station,cnote_serial,cnote_status,date_added,cnote_number,br_code';
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
              html += "<td>"+datum[i]['cnotenoid']+"</td>";
              html += "<td>"+datum[i]['cnote_station']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_station']+datum[i]['cnote_number']+"</td>";
              html += "<td>"+datum[i]['cnote_status']+"</td>";
           
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
             comp_branch = "cnote_station='<?=$stationcode?>'";
           }else{
            comp_branch = "cnote_station='<?=$stationcode?>' AND br_code='"+session_brcode+"'";

           }
           const condition = [comp_branch, "(cnote_status LIKE'%VOID' OR cnote_status LIKE '%Noservice%')", " Month(date_added)='"+month+"' AND Year(date_added)='"+year+"'"];
             let conditions = condition.join(" AND ");

      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT cnotenoid,cnote_station,cnote_serial,cnote_status,date_added,cnote_number,br_code FROM cnotenumber WITH(NOLOCK) WHERE "+conditions+" AND (cnote_number like '%"+fieldvalue+"%' OR cnote_number LIKE '%"+fieldvalue+"%') order by cnote_number desc";
      var dtbcolumns = 'cnotenoid,cnote_station,cnote_serial,cnote_status,date_added,cnote_number,br_code';
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
              html += "<td>"+datum[i]['cnotenoid']+"</td>";
              html += "<td>"+datum[i]['cnote_station']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_station']+datum[i]['cnote_number']+"</td>";
              html += "<td>"+datum[i]['cnote_status']+"</td>";
           
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
             comp_branch = "cnote_station='<?=$stationcode?>'";
           }else{
            comp_branch = "cnote_station='<?=$stationcode?>' AND br_code='"+session_brcode+"'";

           }
      const condition = [comp_branch, "(cnote_status LIKE'%VOID' OR cnote_status LIKE '%Noservice%')", " Month(date_added)='"+month+"' AND Year(date_added)='"+year+"'"];
      let conditions = condition.join(" AND ");
      $('#datatable-tbody').css('opacity','0.5');
      var dtbquery = "SELECT cnotenoid,cnote_station,cnote_serial,cnote_status,date_added,cnote_number,br_code FROM cnotenumber WITH(NOLOCK) WHERE "+conditions+" order by cnote_number desc";
      console.log(dtbquery);
      var dtbcolumns = 'cnotenoid,cnote_station,cnote_serial,cnote_status,date_added,cnote_number,br_code';
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
              html += "<td>"+datum[i]['cnotenoid']+"</td>";
              html += "<td>"+datum[i]['cnote_station']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cnote_serial']+"</td>";
              html += "<td>"+datum[i]['cnote_station']+datum[i]['cnote_number']+"</td>";
              html += "<td>"+datum[i]['cnote_status']+"</td>"; 
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
     var datefilter = $(this).val().trim();
     var comp_branch;
      var year = datefilter.split("-")[0];
      var month = datefilter.split("-")[1];
      var session_brcode = '<?=$sessionbr_code?>';
           if(session_brcode.length==0){
             comp_branch = "cnote_station='<?=$stationcode?>'";
           }else{
            comp_branch = "cnote_station='<?=$stationcode?>' AND br_code='"+session_brcode+"'";

           }

      const condition = [comp_branch, "(cnote_status LIKE'%VOID' OR cnote_status LIKE '%Noservice%')", " Month(date_added)='"+month+"' AND Year(date_added)='"+year+"'"];
      let conditions = condition.join(" AND ");
      console.log(conditions);
    $.ajax({
        url:"<?php echo __ROOT__ ?>cancellation",
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
              console.log(res[2]);
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
