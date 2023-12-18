<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
}

function authorize($db,$user)
{
    
    $module = 'denominationlist';
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
    $module = 'denominationdelete';
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

function getcompanycode($db,$user)
{
  $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
  $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
  $rowuser = $datafetch['last_compcode'];
  if(!empty($rowuser)){
       return $stationcode = $rowuser;
  }
  else{
      $userstations = $datafetch['stncodes'];
      $stations = explode(',',$userstations);
      return $stationcode = $stations[0];     
  }
} 
$stationcode = getcompanycode($db,$user);
//denamination list by branch

$getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
//echo json_encode($getuserstation);
$datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
$rowuser = $datafetch['last_compcode'];
if(!empty($rowuser)){
     $stationcode = $rowuser;
}
else{
    $userstations = $datafetch['stncodes'];
    $stations = explode(',',$userstations);
    $stationcode = $stations[0];     
}

$sessionbr_code = $datafetch['br_code'];
$getbrname = $db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND br_code='$sessionbr_code'")->fetch(PDO::FETCH_OBJ);

       $brcode = $sessionbr_code;
       $brname = $getbrname->br_name;
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
                                            <a href="#">Dashboard</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Accounts</li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Denomination</li>
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
                                  Collection Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Collection updated successfully.
                                </div>";
                  }
                  if ( isset($_GET['error']) && $_GET['error'] == 1 )
                  {
                       echo "<script>
                       swal('You cannot add a denomination more than one time in a day');
                       </script>";

                  // "<div class='alert alert-danger alert-dismissible'>
                  //                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                  //                 <h4><i class='icon fa fa-check'></i> Alert!</h4>
                  //                 You cannot add a denomination more than one time in a day
                  //               </div>";
                  }

                   ?>
                
             </div>

            <form method="POST"  >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

         <div class="boxed boxBt panel panel-primary">
         <div class="title_1 title panel-heading">
         <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Denomination Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>denomination"><button type="button" class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__ ?>dashboard"><button type="button" class="btn btn-danger btnattr" >
                <i class="fa fa-times" ></i>
        </button></a>

        </div>
              
                </div> 
      <div class="content panel-body">


        <div class="tab_form">


          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>		
                  <td>Denomination Master No</td>
                  <td>
                  <div class="col-sm-9">
                        <input placeholder="" class="form-control border-form" name="denmasno" type="text" id="denmasno" value="<?php if(isset($_POST['denmasno'])){ echo  $_POST['denmasno']; } ?>" autofocus>
                   </div>
                     </td>
                    
                  <!-- <td>Collection Center</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="coll_code" type="text" id="coll_code" value="<?php if(isset($_POST['cust_code'])){ echo  $_POST['cust_code']; } ?>">
                    </div>
                      </td> -->

                      <!-- <td>Invoice No</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="invno" type="text" id="invno" value="<?php if(isset($_POST['invno'])){ echo  $_POST['invno']; } ?>" >
                    </div>
                      </td> -->

                      <td>Month</td>
                        <td>
                        <input class="form-control border-form <?php if(isset($_POST['mon_th'])){ }else{ echo 'this-month'; } ?>" name="mon_th" type="month" id="mon_th" value="<?php if(isset($_POST['mon_th'])){ echo $_POST['mon_th']; } ?>">

                      </td>	
                 
                  </tr>	
                    </tbody>
                  </table>
                  <table>

                    <div class="align-right btnstyle" >

                    <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterdenomination">
                    Apply <i class="fa fa-filter bigger-110"></i>                         
                    </button> 

                    <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()" >
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button> 

                    </div>
                    </table> 
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Denomination List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
           </div>
           <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" border="1" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace"/>
                <span class="lbl"></span>
                </label>                            
              </th>
              <th>Denomination No</th>
              <!-- <th>Collection Center</th> -->
              <th>Collection Date</th>
              <th>Branch</th>
              <th>₹ 2000</th>  
              <th>₹ 500</th>
              <th>₹ 200</th>
              <th>₹ 100</th>
              <th>₹ 50</th>
              <th>₹ 20</th>
              <th>₹ 10</th>   
              <th>₹ 5</th> 
              <th>Coins</th>
              <th>Cheque</th>
              <th>NEFT</th>
              <th>UPI</th>
              <th>Total Collected Amt</th>                      
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
                 }
                 else{
                     $userstations = $datafetch['stncodes'];
                     $stations = explode(',',$userstations);
                     $stationcode = $stations[0];     
                 }
                if(isset($_POST['filterdenomination']))
                {    
                    $denmasno          = $_POST['denmasno'];
                    $mon_th             = $_POST['mon_th']; 

                    $conditions = array();

                    if(! empty($denmasno)) {
                    $conditions[] = "den_id LIKE '$denmasno%'";
                    } 
                  
                    if(empty($brcode)){
                        $conditions[] = "comp_code='$stationcode'";
                       }else{
                        $conditions[] = "comp_code='$stationcode' AND   br_code='$brcode'";
    
                       }
                    if(! empty($mon_th)) {
                        $conditions[] = "YEAR(col_date) ='".explode('-',$mon_th)[0]."' AND MONTH(col_date) = '".explode('-',$mon_th)[1]."'";
                        }

                    $conditons = implode(' AND ', $conditions);

                    if (count($conditions) > 0) {
                        $query = $db->query("SELECT * FROM denomination  WHERE NOT status LIKE '%void' AND $conditons"); 
                     //   echo json_encode ($query);
                    }
                    if (count($conditions) == 0) {
                    $query = $db->query("SELECT * FROM denomination WHERE comp_code='$stationcode'   AND NOT status LIKE '%void' ORDER BY den_id DESC"); 
                  //  echo json_encode ($query);
                }
                }

                //Normal Station list
                else{
                   $denoid = $db->query("SELECT den_id FROM denomination");

                   $conditions = array();
                    //denamination list by branch
                    if(!empty($sessionbr_code)){
                      $conditions[] = "comp_code='$stationcode' AND br_code='$brcode'";
                     }else{
                   $conditions[] = "comp_code='$stationcode'";
                     }
                   $conditons = implode(' AND ', $conditions);


$query = $db->query("SELECT * FROM denomination WHERE $conditons AND flag=1 AND  datepart(mm,col_date) = month(getdate()) AND datepart(yyyy,col_date) = year(getdate()) AND NOT status LIKE '%void' ORDER BY den_id DESC");
   // echo json_encode ($query);
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
                    
                        echo "<td>".$row->den_id."</td>";
                       //echo "<td>".$row->coll_center."</td>";
                        echo "<td>".date("d-m-y", strtotime($row->col_date))."</td>";
                        echo "<td>".$row->br_code."</td>";
                        echo "<td>".round($row->m2000)."</td>";
                        echo "<td>".round($row->m500)."</td>";
                        echo "<td>".round($row->m200)."</td>";
                        echo "<td>".round($row->m100)."</td>";
                        echo "<td>".round($row->m50)."</td>";
                        echo "<td>".round($row->m20)."</td>";
                        echo "<td>".round($row->m10)."</td>";
                        echo "<td>".round($row->m5)."</td>";
                        echo "<td>".round($row->coins)."</td>";                                            
                        echo "<td>".round($row->cheqamt)."</td>";       
                        echo "<td>".round($row->neftamt)."</td>";                                                                                 
                        echo "<td>".round($row->upiamt)."</td>";                                            
                        echo "<td>".round($row->collamt)."</td>";
                        echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                        
                        <a class='btn btn-primary btn-minier' target='_blank' href='denominationprint?denid=$row->den_id'>
                        <i class='ace-icon fa fa-print bigger-130'></i>
                        </a>
                      <!--  <a class='btn btn-success btn-minier' href='denominationedit?denid=$row->den_id'>
                        <i class='ace-icon fa fa-pencil bigger-130'></i>
                        </a> -->
                        <a class='btn btn-danger btn-minier'  id='$row->den_id' onclick='".delete($db,$user)."'>
                            <i class='ace-icon fa fa-trash bigger-130'></i>
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
                                <a  class='tooltip-primary' data-rel='tooltip' title='Edit'>
                                <span class='blue'>
                                <i class='ace-icon fa fa-pencil bigger-120'></i>
                                </span>
                                </a>
                                </li>                        
                            <li>                           
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
          </div>
</section>

<!-- inline scripts related to this page -->
<script>

//DataTable
  $(document).ready(function(){
        $('#data-table').DataTable();
    });
</script>
<script>
function resetFunction() {
    document.getElementById('colmasno').value = "";
    document.getElementById('cust_code').value = ""; 
    document.getElementById('invno').value = "";    
       
}
</script>

<!-- Set cookie for datatable search -->
<script>
$(document).on('keyup', ".dataTables_filter",function () {
    var collectioncookie = $('.dataTables_filter').find('input').val();
    sessionStorage.setItem("collectionlist", collectioncookie);
});
</script>
<script>
function opencookie(){
$('.dataTables_filter').find('input').val(sessionStorage.getItem("collectionlist").trim());
$('.dataTables_filter').find('input').keyup();
}
</script>
<!-- Set cookie for datatable search -->
<script>
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
    //var datas = id.split(",");
    //var deletetb = datas[0];
    //var deletecol = 'brid';
    var del_den_id = id;
    var del_den_stn = '<?=$stationcode?>';
    var del_den_user = '<?=$user?>';
    console.log(del_den_id+','+del_den_stn+','+del_den_user);

    $.ajax({
              url:"<?php echo __ROOT__ ?>collectioncontroller",
              method:"POST",
              data:{del_den_id,del_den_stn,del_den_user},
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
