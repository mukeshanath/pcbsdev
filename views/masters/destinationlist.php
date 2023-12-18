<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'destinationlist';
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
  
  function delete($db,$user){
    $module = 'destinationdelete';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;
  
    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'swal("You Dont Have Permission to Delete this Record!")';
        }
        else{
            return "deleterecord(this.id)";
        }
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
                                    <li class="list-inline-item active">
                                        <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'destinationlist'; ?>">Destination List</a>
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
                        Branch Added successfully.
                      </div>";
        }

        if ( isset($_GET['success']) && $_GET['success'] == 3 )
        {
            echo 

        "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Branch updated successfully.
                      </div>";
        }

        ?>
                
             </div>
  

     

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Dest.Zone Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>destination"><button type="button" class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>

         </div>
        </div>

     
        
      <div class="content panel-body">
      

        <div class="tab_form">
       
       <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->

       <form method="POST">

       <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
            <td>Destination Code</td>
            <td>
            <input placeholder="" class="form-control br_code" name="dest_code" value="<?php if(isset($_POST['dest_code'])){ echo  $_POST['dest_code']; } ?>" type="text" id="dest_code">
            </td>
                
            <td>Destination Name</td>
            <td>
                <input class="form-control border-form" name="dest_name" value="<?php if(isset($_POST['dest_name'])){ echo  $_POST['dest_name']; } ?>" type="text" id="dest_name" > 
            </td>
            <!-- <td>Phone</td>
            <td>
            <input placeholder="" class="form-control border-form" name="br_phone" value="<?php if(isset($_POST['br_phone'])){ echo  $_POST['br_phone']; } ?>" type="text" id="br_phone">
             </td> -->
            	
            </tr>
            
       </tbody>
     </table>
     <table>

        <div class="align-right btnstyle" >

        <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterdestination">
        Apply <i class="fa fa-filter bigger-110"></i>                         
        </button> 

        <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn"  onclick="resetFunction()">
        Reset <i class="fa fa-refresh bigger-110"></i>                         
        </button> 

        </div>
        </table> 

        </form>

      </div>    

      <style>
               .table-search{
                 height:50px;
                 padding: 10px;
               }
               .table-search select{
                 height:25px;
                 width:70px;
                 margin-left: 10px;
               }
               .table-search-right{
                 float:right;
               }
               .table-search-right input{
                margin-left: 10px;
               }
               .table-paginate-btns{
                 text-align:right;
                 padding:10px;
               }
               </style>
<!-- Fetching Mode Details in Table  -->
<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Dest.Zone List</p>
              <div class="align-right" style="margin-top:-33px;" id="print">
                <div class="dt-buttons"> 
                    <button class="dt-button buttons-excel buttons-html5" aria-controls="data-table" type="button" onclick="$('#excel').submit();"><span><img data-toggle="tooltip" title="Export to EXCEL" style="width:100%;" src="vendor/bootstrap/icons/ms-excel.png"></span></button> 
                    <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="data-table" type="button" onclick="$('#pdf').submit();"><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/pdf.png"></span></button> 
                </div>
              </div>
        </div>
        <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text" onkeyup="destinationsearch()"></div>
           </div>
        <table id="" class="table data-table" cellspacing="0" cellpadding="0" align="center">
         <thead><tr>
         <th class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" />
                            <span class="lbl"></span>
                        </label>
                    </th>            
                  
                    <th>Origin</th>
                    <th>Destination Code</th>
                    <th>Destination Name</th>                  
                    <th>Zone A</th>
                    <th>Zone B</th>
                    <th>Zone C</th>
                    <th>Cash Zone</th>
                    <th>TS Zone</th>
                    <th>Hub 1</th>
                    <th>Hub 2</th>
                    <th>Actions</th>
              </tr>
                </thead>
              <tbody id="destzonelist-tbody">
              <?php 

                        //Filter Button Event
                        $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
                        $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
                        $rowuser = $datafetch['last_compcode'];
                        if(!empty($rowuser)){
                              $stationcode = $rowuser;
                              $stn_condition = "WHERE comp_code='$stationcode'";
                        }
                        else{
                            $userstations = $datafetch['stncodes'];
                            $stations = explode(',',$userstations);
                            $stationcode = $stations[0];  
                            $stn_condition = "WHERE comp_code='$stationcode'";   
                        }
                          if(isset($_POST['filterdestination']))
                          {    
                               $dest_code     = $_POST['dest_code'];
                               $dest_name     = $_POST['dest_name'];
                            
                               $conditions = array();
    
                               if(! empty($dest_code)) {
                                  $conditions[] = "dest_code LIKE '$dest_code%'";
                                }
                                if(! empty($dest_name)) {
                                  $conditions[] = "dest_name LIKE '$dest_name%'";
                                }
                                    
                                // if(! empty($stationcode)) {
                                //   $conditions[] = "comp_code='$stationcode'";
                                // }
                                
                                $conditions[] = "comp_code='$stationcode'";

                                $conditons = implode(' AND ', $conditions);
    
                                if (count($conditions) > 0) {
                                  $sqlquery = "SELECT * FROM destination WHERE $conditons ORDER BY zone_destid DESC OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY";
                                    $query = $db->query($sqlquery); 
                                }
                                if (count($conditions) == 0) {
                                  $sqlquery = "SELECT * FROM destination WHERE $conditons ORDER BY zone_destid DESC";
                                  $query = $db->query($sqlquery); 
                              }
                          }
                          //Normal Station list
                          else{
                            $conditions[] = "comp_code='$stationcode'";

                            $conditons = implode(' AND ', $conditions);

                            $sqlquery = "SELECT * FROM destination WHERE $conditons ORDER BY zone_destid DESC OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY";
                              $query = $db->query($sqlquery); 
                          }
                          if($query->rowcount()==0)
                          {
                            echo "";
                          }
                          else{
                           while ($row = $query->fetch(PDO::FETCH_OBJ))
                            {
                       
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                  <label>
                                    <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                      <span class='lbl'></span>
                                  </label>
                                </td>";                           
                           
                            echo "<td>".$row->comp_code."</td>";
                            echo "<td>".$row->dest_code."</td>";
                            echo "<td>".$row->dest_name."</td>";                           
                            echo "<td>".$row->zonecode_a."</td>";
                            echo "<td>".$row->zonecode_b."</td>";
                            echo "<td>".$row->zonecode_c."</td>";
                            echo "<td>".$row->zone_cash."</td>";
                            echo "<td>".$row->zone_ts."</td>";
                            echo "<td>".$row->hub1."</td>";
                            echo "<td>".$row->hub2."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                            

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='destination,$row->zone_destid,zone_destid' onclick='".delete($db,$user)."'>
                                    <i class='ace-icon fa fa-trash-o bigger-130'></i>
                                 </a>
                               </div>
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
                        }
                          
                      ?>
                 </tbody>
            </table>
            <div class="table-paginate-btns">
              <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM destination WHERE $conditons")->fetch(PDO::FETCH_OBJ);
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
              </div>
              </div>
          </div>
          </div>
<!-- export to excel -->
<form id="excel" method="POST" action="<?php echo __ROOT__ ?>mastercontroller">
<input type="text" name="excel_query" id="excel_query" class="hidden" value="<?=explode('OFFSET',$sqlquery)[0]?>" style="width:100%">
<input type="text" name="excel_columns" id="excel_columns" class="hidden" value="comp_code,dest_code,dest_name,zonecode_a,zonecode_b,zonecode_c,zone_cash,zone_ts,hub1,hub2" style="width:100%">
</form>
<!-- export to excel -->
<!-- export to pdf -->
<form id="pdf" method="POST" action="<?php echo __ROOT__ ?>mastercontroller">
<input type="text" name="pdf_query" id="pdf_query" class="hidden" value="<?=explode('OFFSET',$sqlquery)[0]?>" style="width:100%">
<input type="text" name="pdf_columns" id="pdf_columns" class="hidden" value="comp_code,dest_code,dest_name,zonecode_a,zonecode_b,zonecode_c,zone_cash,zone_ts,hub1,hub2" style="width:100%">
</form>
<!-- export to pdf -->
</section>

<script>
function resetFunction() {
    document.getElementById('dest_code').value = "";
    document.getElementById('dest_name').value = "";    
   
} 

function deleterecord(id){

swal({
title: "Are you sure?",
text: "Once deleted, you will not be able to recover this record file!",
icon: "warning",
buttons: true,
dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    var datas = id.split(",");
    var deletetb = datas[0];
    var deletecol = datas[2];
    var deleterow = datas[1];
    $.ajax({
              url:"<?php echo __ROOT__ ?>mastercontroller",
              method:"POST",
              data:{deletetb:deletetb,deleterow:deleterow,deletecol:deletecol},
              dataType:"text",
              success:function(value)
              {
                location.reload();
              }
      });
    swal("Your Record has been deleted!", {
      icon: "success",
    });
  } else {
    swal("Your Record is safe!");
  }
});

} 
</script>
<!-- custom datatable -->
<script>
  $('.paginate-btn-nums').click(function(){
    var inc = parseInt($(this).val())+1;
            var dec = parseInt($(this).val())-1;
            var curt = parseInt($(this).val());
            var end = parseInt(<?php echo ceil($noofpages->pages / 10); ?>);
            if($(this).val()==0||$(this).val()>end){
              process.end;
            }
    $('.paginate-btn-nums').removeClass('active');
      var dtbquery = "SELECT * FROM destination WHERE <?=$conditons;?> ORDER BY zone_destid DESC";
      var dtbcolumns = 'comp_code,dest_code,dest_name,zonecode_a,zonecode_b,zonecode_c,zone_cash,zone_ts,hub1,hub2';
      var dtbpageno = ($(this).val()-1)*10;
      var sessionuser = $('#user_name').val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,sessionuser,datatable_page:'destinationlist'},
            dataType:"text",
            success:function(data){
              $('#destzonelist-tbody').html(data);
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
          //end pagination dynamic buttons
  });
</script>
<script>
  function destinationsearch(){
     var fieldvalue = $('.table-search-right-text').val();
      sessionStorage.setItem("destinationlist", fieldvalue);
      var dtbquery = "SELECT * FROM destination WHERE <?=$conditons;?> AND dest_code LIKE '"+$('.table-search-right-text').val().trim()+"%' ORDER BY zone_destid DESC";
      var dtbcolumns = 'comp_code,dest_code,dest_name,zonecode_a,zonecode_b,zonecode_c,zone_cash,zone_ts,hub1,hub2';
      var dtbpageno = '0';
      var sessionuser = $('#user_name').val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,sessionuser,datatable_page:'destinationlist'},
            dataType:"text",
            success:function(data){
              $('#destzonelist-tbody').html(data);
            }
          }); 
  }
  </script>
 <script>
    $('.table-search-right-text').val(sessionStorage.getItem("destinationlist").trim());
    destinationsearch();
 </script>
<!-- end custom datatable -->