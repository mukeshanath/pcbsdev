<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
}

function authorize($db,$user)
{
   
    $module = 'stationcodelist';
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
    $module = 'stationcodedelete';
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
                                        <a href="<?php echo __ROOT__ . 'stationcodelist'; ?>">Station Code list</a>
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
                        Station Code Added successfully.
                      </div>";
        }

        if ( isset($_GET['success']) && $_GET['success'] == 2 )
          {
              echo 

          "<div class='alert alert-success alert-dismissible'>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                          <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Station Code updated successfully.
                        </div>";
          }

       

        ?>
                
      </div>
      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Station Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>stationcode" ><button type="button" class="btn btn-success btnattr" >
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
       <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 
          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
            <td>Station Code</td>
            <td>
            <input placeholder="" class="form-control br_code" name="stncode" type="text" id="stncode" value="<?php if(isset($_POST['stncode'])){ echo  $_POST['stncode']; } ?>">
           
            </td>
                
            <td>Station Name</td>
            <td>
                <input class="form-control border-form" name="stnname" type="text" id="stnname" value="<?php if(isset($_POST['stnname'])){ echo  $_POST['stnname']; } ?>">              
            </td>
            <td>State Code</td>
            <td>
            <input placeholder="" class="form-control border-form" name="stat_code" type="text" id="stat_code" value="<?php if(isset($_POST['stat_code'])){ echo  $_POST['stat_code']; } ?>">
            </td>
            	
            </tr>
            
       </tbody>
     </table>
     <table>

<div class="align-right btnstyle" >

<button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterstationcode">
 Apply <i class="fa fa-filter bigger-110"></i>                         
</button> 
                                                  
<button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()">
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:10px;">Station List</p>
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
                    <th>Station Code</th>
                    <th>Station Name</th>
                    <th>State Code</th>                   
                    <th>Actions</th>
              </tr>
                </thead>
              <tbody>
              <?php 

                      if(isset($_POST['filterstationcode']))
                      {    
                           $stncode         = $_POST['stncode'];
                           $stnname         = $_POST['stnname'];
                           $stat_code       = $_POST['stat_code'];
                         

                           $conditions = array();

                           if(! empty($stncode)) {
                              $conditions[] = "stncode LIKE '$stncode%'";
                            }
                            if(! empty($stnname)) {
                              $conditions[] = "stnname LIKE '$stnname%'";
                            }
                            if(! empty($stat_code)) {
                              $conditions[] = "state LIKE '$stat_code%'";
                            }
                           

                            $conditons = implode(' AND ', $conditions);

                            if (count($conditions) > 0) {
                                $query = $db->query("SELECT * FROM stncode  WHERE  $conditons"); 
                            }
                            if (count($conditions) == 0) {
                              $query = $db->query("SELECT * FROM stncode ORDER BY stnid DESC"); 
                            }
                      }
                      //Normal Station list
                      else{
                          $query = $db->query("SELECT * FROM stncode ORDER BY stnid DESC"); 
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
                            echo "<td>".$row->state."</td>";                           
                           
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                            <a class='btn btn-success btn-minier' href='stationcodeedit?stnid=".$row->stnid."'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                               </a>

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='stncode,$row->stnid,stnid' onclick='".delete($db,$user)."'>
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
                $('#iataadd').attr('href','<?php echo __ROOT__ ?>stationcode');
              }
              else {
                $("#iataadd").click(function() {
                  alert("You do not have permission to add station code.");
                  });
              }
            }
    });
  });
  </script>

<script>
function resetFunction() {
    document.getElementById('stncode').value = "";
    document.getElementById('br_code').value = ""; 
    document.getElementById('cnote_serial').value = "";    
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