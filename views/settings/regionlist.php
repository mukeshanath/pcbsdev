<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'regionlist';
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
    $module = 'regiondelete';
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

function loadstate($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM state ORDER BY stid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->statecode.'">'.$row->statecode.'</option>';
         }
         return $output;    
        
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
                                        <a href="<?php echo __ROOT__ . 'regionlist'; ?>">Region List</a>
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
                              Region Added successfully.
                            </div>";
              }

                if ( isset($_GET['success']) && $_GET['success'] == 2 )
              {
                    echo 

              "<div class='alert alert-success alert-dismissible'>
                              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                              <h4><i class='icon fa fa-check'></i> Alert!</h4>
                              Region updated successfully.
                            </div>";
              }

                ?>
            
            </div>

            <form method="POST" action="<?php echo __ROOT__ ?>region" accept-charset="UTF-8" class="form-horizontal" id="formregion" enctype="multipart/form-data">

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Region Filters</p>
                    <div class="align-right" style="margin-top:-35px;">

                    <a style="color:white" href="<?php echo __ROOT__ ?>region"  ><button type="button" class="btn btn-success btnattr" >
                    <i class="fa fa-plus" ></i>
                    </button></a>

                    <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
                            <i class="fa fa-times" ></i>
                    </button></a>

                    </div>
             
                </div> 
      <div class="content panel-body">


        <div class="tab_form">

          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>		
                  <td>Region Code</td>
                  <td>
                  <div class="col-sm-9">
                        <input placeholder=""  class="form-control border-form" name="regioncode" type="text" id="regioncode" value="<?php if(isset($_POST['regioncode'])){ echo  $_POST['regioncode']; } ?>" autofocus>
                   </div>
                     </td>
                    
                  <td>Region Name</td>
                  <td>
                  <div class="col-sm-9">
                         <input placeholder="" class="form-control border-form" name="regionname" type="text" id="regionname" value="<?php if(isset($_POST['regionname'])){ echo  $_POST['regionname']; } ?>">
                    </div>
                      </td>
                 
                  <td>State Names</td>
                  <td>
                      <div class="col-sm-12">
                        <input class="form-control container-fluid"  name="statenames" id="statenames" value="<?php if(isset($_POST['statenames'])){ echo  $_POST['statenames']; } ?>">
                        
                      </div>
                  </td>
                  <td>Active</td>		
                  <td>
                  <div class="col-sm-9">
                    <select class="form-control" name="reg_active">
                      <option value="Yes" selected="selected">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  </td>		
                  </tr>	
                    </tbody>
                  </table>
                  <table>

                    <div class="align-right btnstyle" >

                    <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterregion">
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Region List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
           </div>
        <table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
         <thead><tr>
            <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>
                    <!-- <th>S.N.</th> -->
                    <th>Region Code</th>
                    <th>Region Name</th>
                    <th>States</th>                   
                    <th>Actions</th>
              </tr>
            </thead>
              <tbody>
              <?php 

                  $query = $db->query('SELECT * FROM region ORDER BY regid DESC');
                  while ($row = $query->fetch(PDO::FETCH_OBJ))
                  {
                    echo "<tr>";
                    echo "<td class='center first-child'>
                            <label>
                                <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                <span class='lbl'></span>
                              </label>
                          </td>";
                    // echo "<td>".$row->regid."</td>";
                    echo "<td>".$row->regioncode."</td>";
                    echo "<td>".$row->regionname."</td>";
                    echo "<td>".$row->statenames."</td>";
                  
                    echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                        <a class='btn btn-success btn-minier' href='regionedit?regid=".$row->regid."'>
                          <i class='ace-icon fa fa-pencil bigger-130'></i>
                        </a>

                          <a  class='btn btn-danger btn-minier bootbox-confirm' id='region,$row->regid,regid' onclick='".delete($db,$user)."'>
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
                $('#regionadd').attr('href','<?php echo __ROOT__ ?>region');
              }
              else {
                $("#regionadd").click(function() {
                  alert("You do not have permission to add region.");
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