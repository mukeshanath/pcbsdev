<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
}

function authorize($db,$user)
{
    
    $module = 'companylist';
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
    $module = 'companydelete';
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
                                        <a href="<?php echo __ROOT__ . 'companylist'; ?>">Company List</a>
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
                                  Station Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Station Updated Successfully.
                                </div>";
                  }

                   ?>
                
             </div>
  
      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Company Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" id="companyadd"><button type="button" class="btn btn-success btnattr" >
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
       
       <input  class="form-control border-form" name="stncode" type="text" id="stncode" style="display:none;">

       <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
            <td>Company Code</td>
            <td>
            <input placeholder="" class="form-control stncode" value="<?php if(isset($_POST['stncode'])){ echo  $_POST['stncode']; } ?>" name="stncode" type="text" id="stncode"> 
            </td>
                
            <td>Company Name</td>
            <td>
                <input class="form-control border-form" name="stnname" value="<?php if(isset($_POST['stnname'])){ echo  $_POST['stnname']; } ?>" type="text" id="stnname" >
 
            </td>
            <!-- <td>Phone</td>
            <td>
            <input placeholder="" class="form-control border-form" name="stn_phone" value="<?php if(isset($_POST['stn_phone'])){ echo  $_POST['stn_phone']; } ?>" type="text" id="stn_phone"> 
            </td> -->
            <td>Active</td>
            <td>
            <select class="form-control border-form" name="stat" id="stat">
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                  </select> 
            </td>		
           
            </tr>
            
       </tbody>
     </table>
     <table>

      <div class="align-right btnstyle" >

      <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtercompany">
      Apply <i class="fa fa-filter bigger-110"></i>                         
      </button> 

      <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn"  onclick="resetFunction()" >
        Reset <i class="fa fa-refresh bigger-110"></i>                         
        </button> 

      </div>
      </table>

          
          </div>    

     </form >

<!-- Fetching Mode Details in Table  -->
<div class="col-12 data-table-div" >

  <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Company List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
        </div>
        <table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
            <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>
                  <th>Company Code</th>
                  <th>Company Name</th>  
                  <th class="hidden">Station</th>  
                  <th class="hidden">Station AddrType</th>  
                  <th class="hidden">Station Address</th>  
                  <th class="hidden">City</th>  
                  <th class="hidden">State</th>  
                  <th class="hidden">Country</th>  
                  <th>GSTIN</th>
                  <th class="hidden">Mobile</th>                    
                  <th>Phone</th>
                  <th>Email</th>
                  <th class="hidden">Fax</th>                    
                  <th class="hidden">Tax</th>                    
                  <th class="hidden">PAN</th>                    
                  <th class="hidden">TIN</th>                    
                  <th class="hidden">Current Inv No</th>                    
                  <th class="hidden">Station Auto</th>                    
                  <th class="hidden">Bank Account Name</th>                    
                  <th class="hidden">Bank Account No</th>                    
                  <th class="hidden">Bank Account Type</th>                    
                  <th class="hidden">Bank Name</th>                    
                  <th class="hidden">Bank branch</th>                    
                  <th class="hidden">IFSC</th>                    
                  <th class="hidden">MICR</th>                    
                  <th class="hidden">Swift</th>                    
                  <th class="hidden">Hub</th>                    
                  <th class="hidden">Web Login</th>                    
                  <th class="hidden">Password</th>                    
                  <th class="hidden">web id</th>                    
                  <th class="hidden">Region</th>                    
                  <th class="hidden">Remarks</th>                    
                  <th>Active</th>
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
                      if(isset($_POST['filtercompany']))
                      {    
                           $stncode     = $_POST['stncode'];
                           $stnname     = $_POST['stnname'];
                           $stn_phone   = $_POST['stn_phone'];
                           $stat        = $_POST['stat']; 

                           $conditions = array();

                            if(! empty($stnname)) {
                              $conditions[] = "stnname LIKE '$stnname%'";
                            }
                            if(! empty($stn_phone)) {
                              $conditions[] = "stn_phone LIKE '$stn_phone%'";
                            }
                            if(! empty($stat)) {
                              $conditions[] = "stn_active='$stat'";
                            }
                             if(!empty($stationcode)) {
                             $conditions[] = "stncode='$stationcode'";
                           }

                            $conditons = implode(' AND ', $conditions);

                            if (count($conditions) > 0) {
                                $query = $db->query("SELECT * FROM station  WHERE  $conditons"); 
                            }
                            if (count($conditions) == 0) {
                              $query = $db->query("SELECT * FROM station $stn_condition ORDER BY stnid DESC"); 
                          }
                      }
                      //Normal Station list
                      else{
                          $query = $db->query("SELECT * FROM station $stn_condition ORDER BY stnid DESC"); 
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
                          echo "<td>".$row->stnname."</td>";  
                          echo "<td class='hidden'>".$row->station."</td>";  
                          echo "<td class='hidden'>".$row->stn_addrtype."</td>";  
                          echo "<td class='hidden'>".$row->stn_addr."</td>";  
                          echo "<td class='hidden'>".$row->stn_city."</td>";  
                          echo "<td class='hidden'>".$row->stn_state."</td>";  
                          echo "<td class='hidden'>".$row->stn_country."</td>";  
                          echo "<td>".$row->stn_gstin."</td>";  
                          echo "<td class='hidden'>".$row->stn_mobile."</td>";                   
                          echo "<td>".$row->stn_phone."</td>";
                          echo "<td>".$row->stn_email."</td>";
                          echo "<td class='hidden'>".$row->stn_fax."</td>";  
                          echo "<td class='hidden'>".$row->stn_taxval."</td>";  
                          echo "<td class='hidden'>".$row->stn_pan."</td>";  
                          echo "<td class='hidden'>".$row->stn_tin."</td>";  
                          echo "<td class='hidden'>".$row->curinvno."</td>";  
                          echo "<td class='hidden'>".$row->stnauto."</td>";  
                          echo "<td class='hidden'>".$row->baccname."</td>";  
                          echo "<td class='hidden'>".$row->baccno."</td>";  
                          echo "<td class='hidden'>".$row->bacctype."</td>";  
                          echo "<td class='hidden'>".$row->bankname."</td>";  
                          echo "<td class='hidden'>".$row->bbranch."</td>";  
                          echo "<td class='hidden'>".$row->ifsc."</td>";  
                          echo "<td class='hidden'>".$row->micr."</td>";  
                          echo "<td class='hidden'>".$row->swift."</td>";  
                          echo "<td class='hidden'>".$row->transit_hub."</td>";  
                          echo "<td class='hidden'>".$row->weblogin."</td>";  
                          echo "<td class='hidden'>".$row->webpassword."</td>";  
                          echo "<td class='hidden'>".$row->webid."</td>";  
                          echo "<td class='hidden'>".$row->stn_region."</td>";  
                          echo "<td class='hidden'>".$row->stn_remarks."</td>";  
                          echo "<td>".$row->stn_active."</td>";
                          echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                              <a  class='btn btn-primary btn-minier' data-rel='tooltip' title='View'  href='companydetail?stnid=".$row->stnid."'>
                                <i class='ace-icon fa fa-eye bigger-130'></i>
                              </a>

                               <a class='btn btn-success btn-minier' data-rel='tooltip' title='Edit' href='companyedit?stnid=".$row->stnid."'>
                                <i class='ace-icon fa fa-pencil bigger-130'></i>
                               </a>

                               <a  class='btn btn-danger btn-minier bootbox-confirm' id='station,$row->stnid,stnid' onclick='".delete($db,$user)."'>
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
</section>

<!-- inline scripts related to this page -->
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
        $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{page_name:'company',page_user:usr_name},
            dataType:"text",
            success:function(access)
            {
              if(access.trim() == 'True')
                {
                  $('#companyadd').attr('href','<?php echo __ROOT__ ?>company');
                }
                else {
                    $("#companyadd").click(function() {
                    swal("You do not have permission to add company.");
                    });
                }
            }
           });
  });
  </script>

<script>
function resetFunction() {
    document.getElementById('stncode').value = "";
    document.getElementById('stnname').value = "";    
    document.getElementById('stn_phone').value = "";   
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