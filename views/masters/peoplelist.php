<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
}

function authorize($db,$user)
{
    
    $module = 'peoplelist';
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
    $module = 'peopledelete';
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
                                        <a href="<?php echo __ROOT__ . 'peoplelist'; ?>">People list</a>
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
                        People Added Successfully.
                      </div>";
        }

        if ( isset($_GET['success']) && $_GET['success'] == 3 )
        {
            echo 

        "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        People Updated Successfully.
                      </div>";
        }

        ?>
                
             </div>
      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">People Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>peopleadd"><button type="button" class="btn btn-success btnattr" >
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
            <td>People Code</td>
            <td>
            <input class="form-control br_code" name="exe_code" type="text" value="<?php if(isset($_POST['exe_code'])){ echo  $_POST['exe_code']; } ?>" id="exe_code">
            </td>
                
            <td>People Name</td>
            <td>
                <input class="form-control border-form" name="exe_name" type="text" value="<?php if(isset($_POST['exe_name'])){ echo  $_POST['exe_name']; } ?>" id="exe_name" >
            </td>
            <td>Moblie</td>
            <td>
            <input class="form-control border-form" name="exe_mobile" type="text" value="<?php if(isset($_POST['exe_mobile'])){ echo  $_POST['exe_mobile']; } ?>" id="exe_mobile">
            </td>
            <td>Active</td>
            <td>
            <select class="form-control border-form" name="active"  id="active">
                  
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                  </select>
                 
            </td>		
            </tr>
            
       </tbody>
     </table>
     <table>

<div class="align-right btnstyle" >

<button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtervendor">
 Apply <i class="fa fa-filter bigger-110"></i>                         
</button> 

<button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn"  onclick="resetFunction()" >
Reset <i class="fa fa-refresh bigger-110"></i>                         
</button> 
                                                  

<!-- <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
<i class="fa fa-eraser"></i>&nbsp; Reset
</button>    -->


</div>
</table>
    

      </div>    

     </form>

<!-- Fetching Mode Details in Table  -->
<div class="col-sm-12 data-table-div" >
          <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:10px;">People List</p>
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
                    
                    <th>People Code</th>
                    <th>People Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>DOJ</th>
                    <th>Role</th>
                    <th class="hidden">Company Code</th>
                    <th class="hidden">Company Name</th>
                    <th class="hidden">Branch Name</th>
                    <th class="hidden">Executive ADD</th>
                    <th class="hidden">City</th>
                    <th class="hidden">State</th>
                    <th class="hidden">Country</th>
                    <th class="hidden">Qualification</th>
                    <th class="hidden">Type</th>
                    <th class="hidden">Remarks</th>
                    <th class="hidden">Ucode</th>
                    <th>Active</th>
                    <th>Actions</th>
              </tr>
                </thead>
              <tbody>
              <?php 
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
                      if(isset($_POST['filterpeople']))
                      {    
                           $exe_code          = $_POST['exe_code'];
                           $exe_name          = $_POST['exe_name'];
                           $exe_mobile         = $_POST['exe_mobile'];
                           $exe_status        = $_POST['active']; 

                           $conditions = array();

                           if(! empty($exe_code)) {
                              $conditions[] = "exe_code LIKE '$exe_code%'";
                            } 
                            if(! empty($exe_name)) { 
                              $conditions[] = "exe_name LIKE '$exe_name%'";
                            }
                            if(! empty($exe_mobile)) {
                              $conditions[] = "exe_mobile LIKE '$exe_mobile%'";
                            }
                            if(! empty($exe_status)) {
                              $conditions[] = "active='$exe_status'";
                            }
                            if(! empty($stationcode)) {
                              $conditions[] = "comp_code='$stationcode'";
                            }

                            $conditons = implode(' AND ', $conditions);

                            if (count($conditions) > 0) {
                                $query = $db->query("SELECT * FROM staff  WHERE  $conditons"); 
                            }
                            if (count($conditions) == 0) {
                              $query = $db->query("SELECT * FROM staff $stn_condition ORDER BY staff_id DESC"); 
                          }
                      }
                      //Normal Station list
                      else{
                          $query = $db->query("SELECT * FROM staff $stn_condition ORDER BY staff_id DESC"); 
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
                           
                            echo "<td>".$row->exe_code."</td>";
                            echo "<td>".$row->exe_name."</td>";
                            echo "<td>".$row->exe_mobile."</td>";
                            echo "<td>".$row->exe_email."</td>";
                            echo "<td>".$row->exe_doj."</td>"; 
                            echo "<td class='hidden'>".$row->comp_code."</td>"; 
                            echo "<td class='hidden'>".$row->comp_name."</td>"; 
                            echo "<td class='hidden'>".$row->br_name."</td>"; 
                            echo "<td class='hidden'>".$row->exe_add."</td>"; 
                            echo "<td class='hidden'>".$row->exe_city."</td>"; 
                            echo "<td class='hidden'>".$row->exe_state."</td>"; 
                            echo "<td class='hidden'>".$row->exe_country."</td>"; 
                            echo "<td class='hidden'>".$row->exe_qualification."</td>"; 
                            echo "<td class='hidden'>".$row->people_type."</td>"; 
                            echo "<td class='hidden'>".$row->remarks."</td>"; 
                            echo "<td class='hidden'>".$row->ucode."</td>"; 
                            echo "<td>".$row->people_type."</td>"; 
                            echo "<td>".$row->active."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                            <a  class='btn btn-primary btn-minier' data-rel='tooltip' title='View' href='peopledetail?staff_id=".$row->staff_id."'>
                                  <i class='ace-icon fa fa-eye bigger-130'></i>
                                </a>
                            <a class='btn btn-success btn-minier' data-rel='tooltip' title='Edit' href='peopleedit?staff_id=".$row->staff_id."'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                               </a>

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='staff,$row->staff_id,staff_id' onclick='".delete($db,$user)."'>
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
              </div>


          </div>
          </div>
          </div>
</section>

<script>
function resetFunction() {
    document.getElementById('exe_code').value = "";
    document.getElementById('exe_name').value = "";    
    document.getElementById('exe_mobile').value = "";   
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