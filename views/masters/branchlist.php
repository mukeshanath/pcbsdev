<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'branchlist';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;

    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'False';
        }
        else{
            return 'True';
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
  $module = 'branchdelete';
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
                                        <a href="<?php echo __ROOT__ . 'branchlist'; ?>">Branch</a>
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

if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 4 )
        {
            echo 

        "<div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Branch Already Added.
                      </div>";
        }

        ?>
                
       </div>
 
      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Branch Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white"  href="<?php echo __ROOT__ ?>branch"><button type="button" class="btn btn-success btnattr" >
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

       <form method="POST">

       <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
            <td>Branch Code</td>
            <td>
            <input placeholder="" class="form-control" name="br_code" value="<?php if(isset($_POST['br_code'])){ echo $_POST['br_code']; } ?>" type="text" id="br_code">
            </td>
                
            <td>Branch</td>
            <td>
                <input class="form-control border-form" name="br_name" value="<?php if(isset($_POST['br_name'])){ echo $_POST['br_name']; } ?>" type="text" id="br_name" > 
            </td>
            <td>Branch Type</td>
            <td>
            <input placeholder="" class="form-control border-form" value="<?php if(isset($_POST['branchtype'])){ echo $_POST['branchtype']; } ?>" name="branchtype" type="text" id="branchtype">
            </td>
            <td>Active</td>
            <td>
            <select class="form-control border-form" value="" name="br_active" id="br_active">
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                  </select>
            </td>		
            </tr>
            
       </tbody>
     </table>
     <table>

        <div class="align-right btnstyle" >

        <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterbranch">
        Apply <i class="fa fa-filter bigger-110"></i>                         
        </button> 

        <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn"  onclick="resetFunction()" >
        Reset <i class="fa fa-refresh bigger-110"></i>                         
        </button> 

        </div>
        </table> 

         
       
     </form>

     </div>
<!-- Fetching Mode Details in Table  -->
<div class="col-11 data-table-div">
<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Branch List</p>
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
            
                    <th class="hidden">Company Code</th>
                    <th class="hidden">Company Nme</th>
                    <th >Br Code</th>
                    <th >Br Name</th>
                    <th class="hidden">Br Address</th>
                    <th class="hidden">Br City</th>
                    <th class="hidden">Br State</th>
                    <th class="hidden">Br Country</th>
                    <th >Origin</th>
                    <th >Phone</th>
                    <th class="hidden">Mobile</th>
                    <th class="hidden">Fax</th>
                    <th class="hidden">Tax</th>
                    <th class="hidden">Manifest</th>
                    <th class="hidden">Category Code</th>
                    <th class="hidden">Trans Hub</th>
                    <th class="hidden">Current Invoice</th>
                    <th class="hidden">Current Col No</th>
                    <th class="hidden">Rate</th>
                    <th class="hidden">POD All</th>
                    <th class="hidden">Br Ncode</th>
                    <th class="hidden">Origin</th>
                    <th class="hidden">Bkg Staff</th>
                    <th class="hidden">Propriter</th>
                    <th class="hidden">Commission</th>
                    <th class="hidden">Pro Commission</th>
                    <th >Branch type</th>
                    <th class="hidden">Intl Rate</th>
                    <th >Email</th>
                    <th >Active</th>
                    <th >Action</th>
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

                     $conditions = array();

                     $conditions[] = "stncode='$stationcode'";

                    //branch filter conditions
                    $sessionbr_code = $datafetch['br_code'];
                    if(!empty($sessionbr_code)){
                        $br_code = $sessionbr_code;
                        $conditions[] = "br_code='$br_code'";
                     }
                    //branch filter conditions
                     
                     if(isset($_POST['filterbranch']))
                     {    
                          $br_code      = $_POST['br_code'];
                          $br_name      = $_POST['br_name'];
                          $branchtype     = $_POST['branchtype'];
                          $active       = $_POST['br_active']; 

                          $conditions = array();

                          if(! empty($br_code)) {
                            $conditions[] = "br_code LIKE '$br_code%'";
                          }
                          if(! empty($br_name)) {
                            $conditions[] = "br_name LIKE '$br_name%'";
                          }
                          if(! empty($branchtype)) {
                            $conditions[] = "branchtype LIKE '$branchtype%'";
                          }
                           if(! empty($active)) {
                             $conditions[] = "br_active='$active'";
                           }
                           if(!empty($stationcode)) {
                             $conditions[] = "stncode='$stationcode'";
                           }
                           

                           $conditons = implode(' AND ', $conditions);

                           if (count($conditions) > 0) {
                               $query = $db->query("SELECT * FROM branch  WHERE  $conditons"); 
                           }
                           if (count($conditions) == 0) {
                             $query = $db->query("SELECT * FROM branch WHERE  $conditons ORDER BY brid DESC"); 
                         }
                     }
                     //Normal Station list
                     else{
                      $conditons = implode(' AND ', $conditions);

                         $query = $db->query("SELECT * FROM branch WHERE  $conditons ORDER BY brid DESC");
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
                           
                            echo "<td class='hidden'>".$row->stncode."</td>";
                            echo "<td class='hidden'>".$row->stnname."</td>";
                            echo "<td>".$row->br_code."</td>";
                            echo "<td>".$row->br_name."</td>";
                            echo "<td class='hidden'>".$row->br_address."</td>";
                            echo "<td class='hidden'>".$row->br_city."</td>";
                            echo "<td class='hidden'>".$row->br_state."</td>";
                            echo "<td class='hidden'>".$row->br_country."</td>";
                            echo "<td>".$row->origin."</td>";
                            echo "<td>".$row->br_phone."</td>";
                            echo "<td class='hidden'>".$row->br_mobile."</td>";
                            echo "<td class='hidden'>".$row->br_fax."</td>";
                            echo "<td class='hidden'>".$row->br_tax."</td>";
                            echo "<td class='hidden'>".$row->manifest."</td>";
                            echo "<td class='hidden'>".$row->cate_code."</td>";
                            echo "<td class='hidden'>".$row->transit_hub."</td>";
                            echo "<td class='hidden'>".$row->curinvno."</td>";
                            echo "<td class='hidden'>".$row->curcolno."</td>";
                            echo "<td class='hidden'>".$row->rate."</td>";
                            echo "<td class='hidden'>".$row->podall."</td>";
                            echo "<td class='hidden'>".$row->br_ncode."</td>";
                            echo "<td class='hidden'>".$row->origin."</td>";
                            echo "<td class='hidden'>".$row->bkgstaff."</td>";
                            echo "<td class='hidden'>".$row->proprietor."</td>";
                            echo "<td class='hidden'>".$row->commission."</td>";
                            echo "<td class='hidden'>".$row->pro_commission."</td>";
                            echo "<td>".$row->branchtype."</td>";
                            echo "<td class='hidden'>".$row->intl_rate."</td>";
                            echo "<td>".$row->br_email."</td>";
                            echo "<td>".$row->br_active."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                                <a  class='btn btn-primary btn-minier' data-rel='tooltip' title='View' href='branchdetail?brid=".$row->brid."'>
                                  <i class='ace-icon fa fa-eye bigger-130'></i>
                                </a>

                                 <a class='btn btn-success btn-minier' href='branchedit?brid=".$row->brid."' data-rel='tooltip' title='Edit'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='branch,$row->brid' onclick='".delete($db,$user)."'>
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
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";    
    document.getElementById('branchtype').value = "";   
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
      var deletecol = 'brid';
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