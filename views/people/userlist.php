<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'userlist';
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
    $module = 'userdelete';
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
<style>
/* Tooltip container */
.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
}

/* Tooltip text */
.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
  visibility: visible;
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
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'userlist'; ?>">Users list</a>
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
                                  Users Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Users Updated Successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <form method="POST" >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Users Filters</p>
                    <div class="align-right" style="margin-top:-35px;">

        

        <a style="color:white" id="useradd" href="<?php echo __ROOT__?>userform" ><button type="button" class="btn btn-success btnattr" >
        <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__?>homepage"><button type="button" class="btn btn-danger btnattr" >
                <i class="fa fa-times" ></i>
        </button></a>

        </div>
              
                </div> 
      <div class="content panel-body">


        <div class="tab_form">


          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>		
                  <td>Users Name</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="user_name" type="text" id="addr_type" value="<?php if(isset($_POST['user_name'])){ echo  $_POST['user_name']; } ?>">
                    </div>
                      </td>

                  <td>Users Group</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="user_group" type="text" id="addr_type" value="<?php if(isset($_POST['user_group'])){ echo  $_POST['user_group']; } ?>">
                    </div>
                      </td>
                 
                  </tr>	
                    </tbody>
                  </table>
                  <table>

                    <div class="align-right btnstyle" >

                    <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="userfilter">
                    Apply <i class="fa fa-filter bigger-110"></i>                         
                    </button> 

                    <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" >
                    Reset <i class="fa fa-fa-refresh bigger-110"></i>                         
                    </button> 

                    </div>
                    </table> 
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Users List</p>
         
           </div>
           <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>             
              <th>User Group</th>           
              <th>User Name</th>
              <th>Email</th>
              <th>Company Access</th>
              <th>Action</th>
              
              </tr>
        </thead>
              <tbody>
                  <?php 

                         if(isset($_POST['userfilter']))
                         {    
                             $user_name            = $_POST['user_name'];
                             $user_group          = $_POST['user_group'];
                          
                             $conditions = array();
         
                             if(! empty($user_name)) {
                             $conditions[] = "user_name LIKE '$user_name%'";
                             }
                             if(! empty($user_group)) {
                             $conditions[] = "group_name LIKE '$user_group%'";
                             }
                           
                             $conditons = implode(' AND ', $conditions);
         
                             if (count($conditions) > 0) {
                                 $query = $db->query("SELECT * FROM users  WHERE  $conditons"); 
                             }
                             if (count($conditions) == 0) {
                             $query = $db->query("SELECT * FROM users  ORDER BY usrid DESC"); 
                         }
                        
                         } 
                         else{
                          $query = $db->query("SELECT * FROM users ORDER BY usrid DESC");

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
                           
                            
                            echo "<td>".$row->group_name."</td>";
                            echo "<td>".$row->user_name."</td>";
                            echo "<td>".$row->user_email."</td>";                           
                            echo "<td>".((strlen($row->stncodes)>50)?substr($row->stncodes,0,50)."...<a  class='tooltip-success' data-rel='tooltip' data-toggle='modal' data-target='#stncode".$row->usrid."' title='Edit'>
                            <span class='blue'>
                            <i class='ace-icon fa fa-eye bigger-120'></i>
                            </span>
                            </a>":$row->stncodes)."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                               
                            <a class='btn btn-success  btn-minier' href='useredit?usr_name=".$row->user_name."' data-rel='tooltip' title='Edit'>
                            <i class='ace-icon fa fa-pencil bigger-130'></i>
                           </a>

                                 <a class='btn btn-primary  btn-minier' href='' data-rel='tooltip' title='Edit'>
                                  <i class='ace-icon fa fa-eye bigger-130'></i>
                                 </a>

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='users,$row->usrid,usrid' onclick='".delete($db,$user)."'>
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
                           
                                     //modal station codes
                                     if(strlen($row->stncodes)>50){
                                     echo '<!-- Modal -->
                                     <div class="modal fade" id="stncode'.$row->usrid.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                       <div class="modal-dialog modal-dialog-centered" role="document">
                                         <div class="modal-content">
                                           <div class="modal-header">
                                             <h5 class="modal-title" id="exampleModalLongTitle">'.$row->user_name.'</h5>
                                             
                                           </div>
                                           <div class="modal-body" style="line-break: anywhere;">
                                           '.$row->stncodes.'
                                           </div>
                                           <div class="modal-footer">
                                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                           </div>
                                         </div>
                                       </div>
                                     </div>';    
                                     }     
                                     //modal station codes               
                          }
                          
                      ?>

                 </tbody>
            </table>
              </div>

        </div>
        
          </div>
          </div>
          </div>
</section>

<!-- inline scripts related to this page -->
<script>
function resetFunction() {
  document.getElementById("formcash").reset();
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
    console.log(datas);
    var deletetb = datas[0];
    var deletecol = datas[2];
    var deleterow = datas[1]; 
    var delu_user_name = $('#user_name').val();
    $.ajax({
              url:"<?php echo __ROOT__ ?>peoplecontroller",
              method:"POST",
              data:{deleteutb:deletetb,deleteurow:deleterow,deleteucol:deletecol,delu_user_name},
              dataType:"text",
              success:function(value)
              {
                var data = JSON.parse(value);
                swal(data['message'], {
                  icon: "success",
                });
                location.reload();
              }
      });
    
  } else {
    swal("Your Record is safe!");
  }
});

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
            }
    });
  });
  </script>

  