<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{

    $module = 'pincodelist';
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
    $module = 'pincodedelete';
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
                                        <a href="<?php echo __ROOT__ . 'pincodelist'; ?>">Pincode List</a>
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
                                  Pincode Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Pincode updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <form method="POST" _action="<?php echo __ROOT__ ?>pincode" accept-charset="UTF-8" class="form-horizontal" id="formpincode" enctype="multipart/form-data">

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

              <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Pincode Filters</p>
                    <div class="align-right" style="margin-top:-35px;">

                    <a style="color:white" href="<?php echo __ROOT__ ?>pincode" ><button type="button" class="btn btn-success btnattr" >
                    <i class="fa fa-plus" ></i>
                    </button></a>

                    <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
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
                  <td>Pincode</td>
                  <td>
                  <div class="col-sm-9">
                        <input placeholder="" class="form-control border-form" name="pincode"  type="text" id="pincode" value="<?php if(isset($_POST['pincode'])){ echo  $_POST['pincode']; } ?>" autofocus>
                   </div>
                     </td>
                    
                  <td>City Name</td>
                  <td>
                  <div class="col-sm-9">
                       <input placeholder="" class="form-control border-form" name="city_name" type="text" id="city_name" value="<?php if(isset($_POST['city_name'])){ echo  $_POST['city_name']; } ?>" >
                  </div>
                   </td>
                  <td>Destination Code</td>
                  <td>
                  <div class="col-sm-9">
                         <input placeholder="" class="form-control border-form" name="dest_code" type="text" id="dest_code" value="<?php if(isset($_POST['dest_code'])){ echo  $_POST['dest_code']; } ?>">
                    </div>
                    </td>
                    </tr>	
                    </tbody>
                  </table>
                  <table>

                <div class="align-right btnstyle" >

                <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filter">
                Apply <i class="fa fa-filter bigger-110"></i>                         
                </button> 

                <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()">
                Reset <i class="fa fa-refresh bigger-110"></i>                         
                </button> 

                </div>
                </table> 

        <!-- Fetching Mode Details in Table  -->

        <div class="col-sm-12 data-table-div" >

         <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Pincode List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
           </div>
           <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text" onkeyup="datatablelistsearch()"></div>
           </div>
        <table _id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
         <thead><tr>
            <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>
                    <th>Destination Code</th>
                    <th>Pincode</th>
                    <th>City Name</th>
                    <th>Actions</th>
              </tr>
                </thead>
              <tbody id="pincodelist_tbody">
              <?php

                        if(isset($_POST['filter']))
                        {    
                            $pincode     = $_POST['pincode'];
                            $city_name    = $_POST['city_name'];
                            $dest_code   = $_POST['dest_code'];                         

                            $conditions = array();

                            if(! empty($pincode)) {
                              $conditions[] = "pincode LIKE '$pincode%'";
                            } 
                            if(! empty($city_name)) {
                              $conditions[] = "city_name LIKE '$city_name%'";
                            }
                            if(! empty($dest_code)) {
                                $conditions[] = "dest_code LIKE '$dest_code%'";
                              }
                            
                              $conditons = implode(' AND ', $conditions);

                              if (count($conditions) > 0) {
                                  $sqlquery = "SELECT * FROM pincode WHERE  $conditons ORDER BY pid DESC OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY"; 
                                  $query = $db->query($sqlquery); 
                              }
                              if (count($conditions) == 0) {
                                $sqlquery = "SELECT * FROM pincode ORDER BY pid DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY";
                                $query = $db->query($sqlquery); 
                            }
                        }
                        //Normal Station list

                        else{

                          $sqlquery = "SELECT * FROM pincode  ORDER BY pid DESC OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY";
                          $query = $db->query($sqlquery);
                        }

                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                            echo "<td>".$row->dest_code."</td>";
                            echo "<td>".$row->pincode."</td>";
                            echo "<td>".$row->city_name."</td>";
                            
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                                 <a class='btn btn-success btn-minier' href='pincodeedit?pid=".$row->pid."'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='pincode,$row->pid,pid' onclick='".delete($db,$user)."'>
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

                          
                      
                      ?>
                 </tbody>
            </table>
            <div class="table-paginate-btns">
              <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM pincode")->fetch(PDO::FETCH_OBJ);
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
          </form>
          </div>
          </div>
          </div>
</section>

<!-- inline scripts related to this page -->
<script>
function resetFunction() {
    document.getElementById('stncode').value = "";
    document.getElementById('br_code').value = ""; 
    document.getElementById('cnote_serial').value = "";    
}      
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
            if($('#stncode').val() == '')
              {
                //alert(data[0]);
                $('#pincodeadd').attr('href','<?php echo __ROOT__ ?>pincode');
              }
              else {
                $("#pincodeadd").click(function() {
                  alert("You do not have permission to add pincode.");
                  });
              }
            }
    });
  });

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
              url:"<?php echo __ROOT__ ?>settingscontroller",
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
      var dtbquery = "SELECT * FROM pincode ORDER BY pid DESC";
      var dtbcolumns = 'dest_code,pincode,city_name';
      var dtbpageno = ($(this).val()-1)*10;
      var sessionuser = $('#user_name').val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>settingscontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,sessionuser,datatable_page:'pincodelist',datatable_module:'pincodedelete'},
            dataType:"text",
            success:function(data){
              $('#pincodelist_tbody').html(data);
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
//datatable search
  function datatablelistsearch(){
    
      var fieldvalue = $('.table-search-right-text').val();
      sessionStorage.setItem("pincodelist", fieldvalue);
      var dtbquery = "SELECT * FROM pincode WHERE pincode LIKE '"+fieldvalue.trim()+"%'  ORDER BY pid DESC";
      var dtbcolumns = 'dest_code,pincode,city_name';
      var dtbpageno = '0';
      var sessionuser = $('#user_name').val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>settingscontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,sessionuser,datatable_page:'pincodelist',datatable_module:'pincodedelete'},
            dataType:"text",
            success:function(data){
              $('#pincodelist_tbody').html(data);
            }
          }); 
  }
  </script>
  <script>
  $('.table-search-right-text').val(sessionStorage.getItem("pincodelist").trim());
  if($('.table-search-right-text').val().trim()!=''){
    datatablelistsearch();
  }
  </script>