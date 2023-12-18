<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $modulearr = explode('?',basename($_SERVER['REQUEST_URI']));
    $module = $modulearr[0];
    $getusergrp = $db->query("SELECT group_name FROM user WHERE user_name='$user'");
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
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-times'></i> Alert!</h4>
            Forbidden - You don't have permission to access this Page.
            </div>";
            exit;
  }

?>
<div class="container">

  <section class="au-breadcrumb m-t-75" style="margin-bottom: -20px;">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="au-breadcrumb-content">
                                    <div class="au-breadcrumb-left">
                                        <span class="au-breadcrumb-span"></span>
                                        <ul class="list-unstyled list-inline au-breadcrumb__list">
                                            <li class="list-inline-item active">
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Branch</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Branch Category</li>
                                        </ul>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
            <hr>

             <div class="alert-primary" role="alert">

               <?php 
  
                    if ( isset($_GET['success']) && $_GET['success'] == 1 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                 Branch Category Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Branch Category Updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <div class="row">
             <div class="col-xs-12 ">
                <div class="clearfix hidden-print " >
               <div class="easy-link-menu align-left">
                <a class="btn-primary btn-sm " href="<?php echo __ROOT__ ?>branch"><i class="fa fa-history" aria-hidden="true"></i>&nbsp;Branch Master Form</a>
                <a class="btn-primary btn-sm " href="<?php echo __ROOT__ ?>branchlist"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;Branch Master List</a>
                <a class="btn-success btn-sm" href="<?php echo __ROOT__ ?>brcategory"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Branch Category</a>
                
                </div>
            </div>
                                                                      <!-- PAGE CONTENT BEGINS -->
            <div class="form-horizontal">
             
            <form method="POST" action="<?php echo __ROOT__ ?>brcategory" accept-charset="UTF-8" class="form-horizontal" id="formbrcategory" enctype="multipart/form-data">

                <h4 class="header large lighter blue"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Branch Category form</h4>
                <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" hidden>
              <div class="form-group">
                <label for="brcate_code" class="col-sm-1 control-label">Category code</label>
                <div class="col-sm-1">
                    <input placeholder="" class="form-control border-form" name="brcate_code" type="text" id="brcate_code"  autofocus>
                </div>

                <label for="brcate_name" class="col-sm-1 control-label">Category name</label>
                <div class="col-sm-2">
                    <input placeholder="" class="form-control border-form" name="brcate_name" type="text" id="brcate_name" >
                </div>

            </div>
          <div class="form-group">
          <div class="card-body" style="margin-left: 450px;">
            <button type="submit" class="btn btn-success" name="addbrcategory">
            <i class="fa fa-star"></i>&nbsp; Submit
            </button>
                                       
            <button type="button" class="btn btn-warning" onclick="resetFunction()">
            <i class="fa fa-map-marker"></i>&nbsp; Reset
            </button>                    
           </div>
         </div>
          
        </form>
      </div>
    </div><!-- /.col -->

  </div><!-- /.row -->

  <div class="form-horizontal">
   <div class="row">
    <div class="col-xs-12">
    <h4 class="header large lighter blue"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;Branch Category List</h4>
    <div class="clearfix">
    <span class="easy-link-menu">
        <a class="btn-primary btn-sm bulk-action-btn" attr-action-type="active"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Active</a>
        <a class="btn-warning btn-sm bulk-action-btn" attr-action-type="in-active"><i class="fa fa-remove" aria-hidden="true"></i>&nbsp;In-Active</a>
        <a class="btn-danger btn-sm bulk-action-btn" attr-action-type="delete"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete</a>
    </span>

    <span class="pull-right tableTools-container"></span>
</div>
<div class="table-header">
    Branch category Record list on table. Filter branch category using the filter.
</div>    <!-- div.table-responsive -->
        <div class="table-responsive">
            
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" />
                            <span class="lbl"></span>
                        </label>
                    </th>
                    <th>S.N.</th>
                    <th>Br Category code</th>
                    <th>Br Category Name</th>
                    <th>User</th>
                    <th>Date Added</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                  <?php 

                    $query = $db->query('SELECT * FROM brcategorytb');
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                            echo "<td>".$row->brcid."</td>";
                            echo "<td>".$row->brcate_code."</td>";
                            echo "<td>".$row->brcate_name."</td>";
                            echo "<td>".$row->user_name."</td>";
                            echo "<td>".$row->date_added."</td>";
                            echo  "<td class='hidden-480'>
                                     <div class='btn-group'>
                                      <button data-toggle='dropdown' class='btn btn-primary btn-minier dropdown-toggle btn-info'>
                                        Active
                                      <span class='ace-icon fa fa-caret-down icon-on-right'></span>
                                      </button>

                                      <ul class='dropdown-menu'>
                                       <li>
                                         <a  title='Active'><i class='fa fa-check' aria-hidden='true'></i></a>
                                        </li>

                                        <li>
                                       <a  title='In-Active'><i class='fa fa-remove' aria-hidden='true'></i></a>
                                         </li>
                                       </ul>
                                     </div>
                                   </td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                                <a class='btn btn-success btn-minier' href='bredit?brcid=".$row->brcid."'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                                </a>

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' >
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
</div>


                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
  
</div>

<!-- inline scripts related to this page -->
<script>
function resetFunction() {
  document.getElementById("formbrcategory").reset();
}
</script>