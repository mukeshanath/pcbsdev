<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'usergroup';
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

function loadstationcode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM stncode ORDER BY stnid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stncode.'">'.$row->stncode.'</option>';
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
                                                <a href="<?php echo __ROOT__ . 'usergrouplist'; ?>">User Group list</a>
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
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  User Group Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  User Group Updated Successfully.
                                </div>";
                  }

                  if ( isset($_GET['unsuccess']))
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  User Group for ".$_GET['unsuccess']." Already added <a href='usergroupedit?grp_id=".$_GET['unsuccess']."'>Click here</a> to edit ".$_GET['unsuccess'].".
                                </div>";
                  }
                   ?>
                
             </div>
            <form method="POST" action="<?php echo __ROOT__ ?>usergroup" class="form-horizontal" name="formdestination" id="formvendors" >
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">User Group Form</p>
                <div class="align-right" style="margin-top:-30px;">
                <button type="submit" class="btn btn-success btnattr_cmn" id="addangrp" name="addgrp">
                <i class="fa fa-save"></i>&nbsp; Save & Add
                </button>

                <button type="submit" class="btn btn-primary btnattr_cmn" id="addgrp" name="addgrp">
                <i class="fa  fa-save"></i>&nbsp; Save
                </button>
                
                <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                <i class="fa fa-eraser"></i>&nbsp; Reset
                </button>     

            <a style="color:white" href="<?php echo __ROOT__ . 'usergrouplist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
          
            </div> 
        <div class="content panel-body">
        <ul class="nav nav-tabs" id="myTab4">
        <li id="grp_li" class="active">
            <a id="grp_a" data-toggle="tab" href="#group">User Group</a>
        </li>
        <li id="permission_li">
            <a id="permission_a" data-toggle="disabled" href="#permissions">Permissions</a>
        </li>
        <!-- <li id="off_li">
            <a id="off_a" data-toggle="" href="#office">Office</a>
        </li> -->
      </ul>

          <div class="tab_form">
          <div class="tab-content">
            <div id="group" class="tab-pane active">
           <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:50%">
               <tr>
                    <td>Group Name</td>
                    <td><input type="text" name="usergroup" id="usergroup" style="padding:5px;margin-top:5px;width:250px" value="" onkeyup="$('#empty_group').css('display','none');">
                    <p id="empty_group" class="form_valid_strings">Please Fill out this Field</p>
                    </td>
                    </tr><tr>
                    <td>Group Type</td>
                    <td><select name="user_type" id="user_type" style="width:250px">
                    <option value="">Select User Type</option>
                    <option value="I">Internal User</option>
                    <!-- <option value="E-B">External Branch</option> -->
                    <option value="E-C">External Customer</option>
                    </select>
                    <p id="empty_type" class="form_valid_strings">Please Select User Type</p>
                    </td>
                </tr>  
                <tr>
                    <td>Description</td>
                    <td><input type="text" name="description" style="padding:10px;margin-top:5px;width:250px">
                </td>
                </tr>
                </table>
                    <div class="align-right">
                        <button type="button" class="btn btn-primary" id="nxt_btn" data-toggle="tab" onclick="permissions()">
                        Next  &#8594;
                        </button>
                    </div>
                </div>
         
            <div id="permissions" class="tab-pane">
            <table class="table" id="internalusers" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
                     <tr>
                     <th class="font-weight-bold text-center" style="border:none">S.no</th>
                     <th class="font-weight-bold text-center" style="border:none">Menu Title</th>
                     <th class="font-weight-bold text-center" style="border:none">Create</th>
                     <th class="font-weight-bold text-center" style="border:none">Modify</th>
                     <th class="font-weight-bold text-center" style="border:none">List Access</th>
                     <th class="font-weight-bold text-center" style="border:none">Detail Access</th>
                     <th class="font-weight-bold text-center" style="border:none">Remove</th>
                     </tr>
                     <tr>
                        <th style="border:none" class="font-weight-bold text-center">Dashboard</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Dashboard</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="dashboard,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>                    
                     </tr>
                     <tr>
                        <th style="border:none" class="font-weight-bold text-center">Masters</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Branch</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="branch,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="branchedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="branchlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="branchdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="branchdelete,remove"></td>                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Booking Counter</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bookingcounter,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bookingcounteredit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bookingcounterlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bookingcounterdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bookingcounterdelete,remove"></td>                    
                     </tr>

                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Company</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="company,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="companyedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="companylist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="companydetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="companydelete,remove"></td>                    
                     </tr>

                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Customer</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customer,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customeredit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customerlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customerdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customerdelete,remove"></td>                     
                     </tr>


                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Destination Zone</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="destination,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="destinationedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="destinationlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="destinationdetail,detail_access" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="destinationdelete,remove"></td>                    
                     </tr>

                     <tr>
                     <td class="text-center">6</td>
                     <td class="text-center">Group</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="group,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="groupedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="grouplist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="groupdetail,detail_access" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="groupdelete,remove"></td>                   
                     </tr>

                     <tr>
                     <td class="text-center">7</td>
                     <td class="text-center">People</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peopleadd,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peopleedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peoplelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peopledetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peopledelete,remove"></td>                     
                     </tr>

                     <tr>
                     <td class="text-center">8</td>
                     <td class="text-center">Vendor</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="vendors,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="vendorsedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="vendorslist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="vendorsdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="vendordelete,remove"></td>                     
                     </tr>

                     <tr>
                     <td class="text-center">9</td>
                     <td class="text-center">Zone</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="zone,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="zoneedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="zonelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="zonedetail,detail_access" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="zonedelete,remove"></td>                     
                     </tr>                   

                     <tr>
                     <td class="text-center">10</td>
                     <td class="text-center">Item</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="itemtype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="itemedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="itemlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="itemdetail,detail_access" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="itemdelete,remove"></td>                     
                     </tr>

                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Cnote</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Procurement</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="procurement,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="procurementlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="procurementdelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Receive</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="receiveform,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="receivelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="receivedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Sales</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesales,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesaleslist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesdelete,remove"></td>
                     
                     </tr>
                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Sales Request</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesapprove,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="cnotesalesedit_int,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesrequestlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesrequestdelete,remove"></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Allotment</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotment,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentdelete,remove"></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Allocation CC</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox"  name="module[]" value="cnoteallocationcc,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox"  name="module[]" value="cnoteallocationlistcc,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox"  name="module[]" value="cnoteallocationccdelete,remove"></td>

                     </tr>

                     <tr>
                     <td class="text-center">6</td>
                     <td class="text-center">Br Re Allotment</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotereallotment,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">7</td>
                     <td class="text-center">CC Re Allotment</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteccreallotment,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     
                     </tr>

                     <!-- <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Allotment Request</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentrequestint,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentrequestlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentdelete,remove"></td>
                     
                     </tr> -->

                     <tr>
                     <td class="text-center">8</td>
                     <td class="text-center">Cancel</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cancellation,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                    
                     </tr>
                     <tr>
                     <td class="text-center">8</td>
                     <td class="text-center">Return</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="returncnote,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                    
                     </tr>
                     <tr>
                     <td class="text-center">9</td>
                     <td class="text-center">Inventory</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="cancellation,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="inventory,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                    
                     </tr>

                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Rate Master</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Customer Rate</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customerrate,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customerratelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customerratedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customerratedelete,remove"></td>
                    
                     </tr>
                   
                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Billing</th>
                     </tr>
                     <!-- <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">BA Inbound</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bainbound,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bainboundlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bainbounddelete,remove"></td>
                    
                     </tr> -->
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">BA Inbound</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bainboundbatch,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bainboundbatchlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bainboundbatchdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bainboundbatchdelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Cash Entry</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cash,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cashlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cashcnotedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cashdelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Credit Entry</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="credit,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditcnotedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditdelete,remove"></td>
                    
                     </tr>   
                     
                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Cnote Tracking</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="cnotetracker,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotetracker,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditdelete,remove"></td>
                    
                     </tr>   

                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Duplicate Challan</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="duplicatechellan,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="duplicatechellan,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="duplicatechellan,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="duplicatechellan,remove"></td>
                    
                     </tr>   

                     <!-- <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Duplicate Challan1</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="duplicatechellan1,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="duplicatechellan1,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="duplicatechellan1,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="duplicatechellan1,remove"></td>
                    
                     </tr>    -->

                    
                     </tr>   

                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Utilities</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Cash Bulk Upload</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cashimport,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cashimportlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cashimportdelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Credit Bulk Upload</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditimport,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditimportlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditimportdelete,remove"></td>
                    
                     </tr>

                     <tr>

                     <th style="border:none" class="font-weight-bold text-center">Accounts</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Invoice</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="invoice,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="invoiceedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="invoicelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="invoicedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="invoicedelete,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Manual Invoice</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="manualinvoice,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="manualinvoiceedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="manualinvoicelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="manualinvoicedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="manualinvoicedelete,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Accounts pay</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="accountspay,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="accountspay,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="accountspay,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="accountspay,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="accountspay,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Collection</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="collection,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="collectionlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="collectiondelete,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Denomination</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="denomination,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="denominationlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="denominationdelete,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Credit Note</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditnote,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditnotelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditnotedelete,remove"></td>
                    
                     </tr> 

                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">TS Charge</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="tscharge,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="tschargelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="tschargedelete,remove"></td>
                    
                     </tr> 

                     <tr>
                         
                         <th style="border:none" class="font-weight-bold text-center">Reports</th>
                         </tr>

                         <tr>
                         <td class="text-center">1</td>
                         <td class="text-center">Booking</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bookingreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoiceedit,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedetail,detail_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>
                         </tr> 

                         <tr>
                         <td class="text-center">1</td>
                         <td class="text-center">DemoReport</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="demoreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoiceedit,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedetail,detail_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>
                         </tr> 
                         <tr>

                         <td class="text-center">2</td>
                         <td class="text-center">Customer</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="customerreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="collectionlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="collectiondelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">3</td>
                         <td class="text-center">Collection</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="collectionreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="collectionlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="collectiondelete,remove"></td>
                        
                         </tr> 
    
                         <tr>
                         <td class="text-center">4</td>
                         <td class="text-center">Invoice</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="invoicereport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditnotelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditnotedelete,remove"></td>
                        
                         </tr> 
    
                         <tr>
                         <td class="text-center">5</td>
                         <td class="text-center">Sales</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="salesreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">6</td>
                         <td class="text-center">Sales Summary</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="salessummary,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">7</td>
                         <td class="text-center">New Business</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="newbusinessreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr>  
                        
                         <tr>
                         <td class="text-center">8</td>
                         <td class="text-center">Missing</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="missingreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">9</td>
                         <td class="text-center">Deviation</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="deviationreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">10</td>
                         <td class="text-center">GST</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="gstreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportdelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">11</td>
                         <td class="text-center">Tonnage</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="tonnagereport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportdelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">12</td>
                         <td class="text-center">Total Station Outstanding</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="totaloutstandingreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                        
                         </tr> 
                        

                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Users</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Users</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]"  value="userform,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]"  value="useredit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]"  value="userlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]"  disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]"  value="userdelete,remove"></td>
                   
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">User Groups</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="usergroup,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="usergroupedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="usergrouplist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="usergroupdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="usergroupdelete,remove"></td>
                   
                     </tr>
                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Settings</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Address</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="addresstype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="addressedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="addresslist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="addressdelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Country</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="country,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="countryedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="countrylist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="countrydelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Mode</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="mode,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="modeedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="modelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="modedelete,remove"></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">People Type</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peopletype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peopletypeedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peopletypelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="peopledelete,remove"></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Pincode</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="pincode,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="pincodeedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="pincodelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="pincodedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">6</td>
                     <td class="text-center">Region</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="region,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="regionedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="regionlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="regiondelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">7</td>
                     <td class="text-center">Reference</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="referencetype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="referenceedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="referencelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="referencedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">8</td>
                     <td class="text-center">Station</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="stationcode,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="stationcodeedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="stationcodelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="stationcodedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">9</td>
                     <td class="text-center">Status</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="statustype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="statusedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="statuslist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="statusdelete,remove"></td>
                   
                     </tr>

                     <tr>
                     <td class="text-center">10</td>
                     <td class="text-center">State</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="state,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="stateedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="statelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="statedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">11</td>
                     <td class="text-center">Tax Slab</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="tax,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="taxedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="taxlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="taxdelete,remove"></td>
                    
                     </tr>
           
                     <tr>
                        <th style="border:none" class="font-weight-bold text-center">External Users</th>
                     </tr>
                     
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">EU-Sales Request</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesrequest,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="extcnotesalesrequestlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesrequestdelete,remove"></td>
                     
                     <!-- </tr>
                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Allotment Request</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentrequest,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="extcnoteallotmentrequestlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentdelete,remove"></td>
                     
                     </tr> -->
                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">EU-Cnote Tracking</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="extcnotetracker,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="extcnotetracker,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditdelete,remove"></td>
                    
                     </tr>   
                    
                    <tr>
                    <td class="text-center">3</td>
                    <td class="text-center">EU-Invoice</td>
                    <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoice,p_create"></td>
                    <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="extinvoiceedit,p_modify"></td>
                    <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="extinvoicelist,list_access"></td>
                    <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="extinvoicedetail,detail_access"></td>
                    <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>

                    </tr> 
                  
                    <tr>
                         <td class="text-center">4</td>
                         <td class="text-center">EU-Customer Report</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="extcustomerreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="collectionlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="collectiondelete,remove"></td>
                        
                         </tr> 
                    <tr>
                         <td class="text-center">5</td>
                         <td class="text-center">EU-GST Report</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="extgstreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value=",list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value=",remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">5</td>
                         <td class="text-center">EU-BA-Inbound Report</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="exbainboundreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value=",list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value=",remove"></td>
                        
                         </tr> 
          </tbody>
          </table>
           <!-- permissions for external customers -->
                </div>
                    
          
        </form>
  <!-- Fetching Mode Details in Table  -->
          
            </div>
            </div>
            </div>
            </div>
  </section>

<!-- inline scripts related to this page -->
<script>
    function permissions(){
        if($('#usergroup').val().trim()==''){
            $('#empty_group').css('display','block');
            $('#usergroup').focus();
         }
        if($('#user_type').val().trim()==''){
            $('#empty_type').css('display','block');
            $('#user_type').focus();
         }
        else{
                
        // if($('#user_type').val().trim()=='I'){
        //     $('#internalusers').css('display','');
        //     $('#externalusers').css('display','none');
        // }
        // else{
        //     $('#externalusers').css('display','');
        //     $('#internalusers').css('display','none');
        // }
         $('#grp_li').attr('class','');
         $('#permission_li').attr('class','active');
         //$("#permission_a").attr('data-toggle','tab');
         $("#nxt_btn").attr('href','#permissions');
        }
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
            $('#stnname').val(data[1]);

            var station = $('#stncode').val();
         
            $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{station:station},
            dataType:"text",
            success:function(data)
            {
            $('#vendor_code').val(data);
            }
            });

            if($('#stncode').val() == '')
            {
                // $('#companysection').css('display','block');
                var superadmin_stn = $('#stncode1').val();
                //var superadmin_stn = 'DAA';
                $.ajax({
                url:"<?php echo __ROOT__ ?>peoplecontroller",
                method:"POST",
                data:{superadmin_stn:superadmin_stn},
                dataType:"text",
                success:function(value)
                {
                    var data = value.split(",");
                    $('#stncode').val(data[0]);
                    $('#stnname').val(data[1]);
                }
                });
            }
            }
         });
       
  });


    
</script>

<script>
 //Dynamic field 
// 

</script>