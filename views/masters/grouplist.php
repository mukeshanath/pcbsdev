<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'grouplist';
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
    $module = 'groupdelete';
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
                                        <a href="<?php echo __ROOT__ . 'grouplist'; ?>">Group list</a>
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
                        Group Added successfully.
                      </div>";
        }

        if ( isset($_GET['success']) && $_GET['success'] == 3 )
        {
            echo 

        "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Group updated successfully.
                      </div>";
        }

        ?>
                
             </div>
  

     

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Group Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>group"><button type="button" class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>

         </div>
        </div>

     
        <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

      <div class="content panel-body">
      

        <div class="tab_form">
       
       <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->

       <form method="POST">

          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
            <td>Category Code</td>
            <td>
            <input placeholder="" class="form-control br_code" name="cate_code"  value="<?php if(isset($_POST['cate_code'])){ echo  $_POST['cate_code']; } ?>"  type="text" id="cate_code">
           
            </td>
                
            <td>Category Name</td>
            <td>
                <input class="form-control border-form" name="cate_name" type="text" id="cate_name" value="<?php if(isset($_POST['cate_name'])){ echo  $_POST['cate_name']; } ?>">
               
            </td>
            <td>Cnote Type</td>
            <td>
            <input placeholder="" class="form-control border-form" name="grp_type" type="text" id="grp_type" value="<?php if(isset($_POST['grp_type'])){ echo  $_POST['grp_type']; } ?>">
           

            </td>
            <td>Status</td>
            <td>
            <select class="form-control border-form" name="br_active" id="br_active" >
                 
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                  </select>
                 
            </td>		
            </tr>
            
       </tbody>
     </table>
     <table>

<div class="align-right btnstyle" >

<button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtergroup">
 Apply <i class="fa fa-filter bigger-110"></i>                         
</button> 

<button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn"  onclick="resetFunction()">
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
<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Group List</p>
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
                      <th>Company</th>
                      <th>Category Code</th>
                      <th>Category Name</th>
                      <th>Type</th>
                      <th>Cnote Start</th>
                      <th>Cnote End</th>
                      <th>Invoice Start</th>
                      <!-- <th>Cur Invoice</th>
                      <th>Cur Collection</th> -->
                      <th>Virtual Cnote</th>
                      <th>Stock Range</th>
                      <th>Action</th>
                </tr>
                  </thead>
                <tbody>
                    <?php 
                          //Filter Button Event
                          $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
                          $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
                          $rowuser = $datafetch['last_compcode'];
                          if(!empty($rowuser)){
                                $stationcode = $rowuser;
                                $stn_condition = "WHERE stncode='$stationcode'";
                          }
                          else{
                              $userstations = $datafetch['stncodes'];
                              $stations = explode(',',$userstations);
                              $stationcode = $stations[0];  
                              $stn_condition = "WHERE stncode='$stationcode'";   
                          }
                          if(isset($_POST['filtergroup']))
                          {    
                               $cate_code     = $_POST['cate_code'];
                               $cate_name     = $_POST['cate_name'];
                               $grp_type   = $_POST['grp_type'];
                              //  $stat        = $_POST['stat']; 
    
                               $conditions = array();
    
                               if(! empty($cate_code)) {
                                  $conditions[] = "cate_code LIKE '$cate_code%'";
                                } 
                                if(! empty($cate_name)) {
                                  $conditions[] = "cate_name LIKE '$cate_name%'";
                                }
                                if(! empty($grp_type)) {
                                  $conditions[] = "grp_type LIKE '$grp_type%'";
                                }
                                if(! empty($stat)) {
                                  $conditions[] = "stn_active='$stat'";
                                }
                                if(! empty($stationcode)) {
                                  $conditions[] = "stncode='$stationcode'";
                                }
    
                                $conditons = implode(' AND ', $conditions);
    
                                if (count($conditions) > 0) {
                                    $query = $db->query("SELECT * FROM groups  WHERE  $conditons"); 
                                }
                                if (count($conditions) == 0) {
                                  $query = $db->query("SELECT * FROM groups $stn_condition ORDER BY gpid DESC"); 
                              }
                          }
                          //Normal Station list
                          else{
                              $query = $db->query("SELECT * FROM groups $stn_condition ORDER BY gpid DESC"); 
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
                              echo "<td>".$row->stncode."</td>";
                              echo "<td>".$row->cate_code."</td>";
                              echo "<td>".$row->cate_name."</td>";
                              echo "<td>".$row->grp_type."</td>";
                              echo "<td>".$row->range_starts."</td>";
                              echo "<td>".$row->range_ends."</td>";
                              echo "<td>".$row->invoice_start."</td>";   
                              // echo "<td>".$row->curinvno."</td>";   
                              // echo "<td>".$row->curcolno."</td>";                             
                              echo "<td>".(($row->v_flag)?'Yes':'No')."</td>";    
                              echo "<td>".$row->stock_alert."</td>";                     
                              echo "<td><div class='hidden-sm hidden-xs action-buttons'>

                              
                              <a class='btn btn-success btn-minier' href='groupedit?gpid=".$row->gpid."'>
                                    <i class='ace-icon fa fa-pencil bigger-130'></i>
                              </a>
                              
                              
                                  <a  class='btn btn-danger btn-minier bootbox-confirm' id='groups,$row->gpid,gpid' onclick='".delete($db,$user)."'>
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
    document.getElementById('cate_code').value = "";
    document.getElementById('cate_name').value = "";    
    document.getElementById('grp_type').value = "";   
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