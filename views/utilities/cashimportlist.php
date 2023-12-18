<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 
function returnstatus($db,$import,$comp_code){
  $fetchstatus = $db->query("SELECT status FROM cashbulkupload WHERE import_id='$import' AND comp_code='$comp_code' GROUP BY status");
  while($rowdata = $fetchstatus->fetch(PDO::FETCH_OBJ)){
    $status[] = $rowdata->status;
  }
  $retdata = implode(",",$status);
  return $retdata;
}
function authorize($db,$user)
{
    
    $module = 'creditimportlist';
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
                                        <a href="<?php echo __ROOT__ . 'cashimportlist'; ?>">Cash Upload list</a>
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
                  "<script> swal('Cash Upload Request has been initiated and will be completed within 10 to 15 mins') </script>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Address Type updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>

             <form method="POST" action="<?php echo __ROOT__ ?>cashimportlist" id="cashimportform">
             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cash Import Filters</p>
                    <div class="align-right" style="margin-top:-35px;">
       
                    <button type="button" class="btn btn-primary btnattr" id="downloadexcelheading" name="cashtemplate">
         <i class="fa fa-file-excel-o" aria-hidden="true"></i>
         </button>
         <a style="color:white" href="<?php echo __ROOT__ ?>cashimport"><button type="button" class="btn btn-success btnattr" >
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
                  <td>Company Code</td>
                  <td>
                  <div class="col-sm-12">
                        <input placeholder="" class="form-control border-form" name="stncode" type="text" id="stncode" value="<?php if(isset($_POST['stncode'])){ echo  $_POST['stncode']; } ?>" autofocus>
                   </div>
                     </td>
                    
                  <td>Import No</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="import" type="text" id="import" value="<?php if(isset($_POST['import'])){ echo  $_POST['import']; } ?>" >
                    </div>
                      </td>

                      <td>Period</td>
                  <td>
                  <div class="col-sm-9">
                      <select class="form-control border-form" name="periodical" id="periodical">
                        <option value="">Select</option>
                        <option value="this_week">This Week</option>
                        <!-- <option value="this_week">Last Week</option> -->
                        <option value="this_month">This Month</option>
                        <!-- <option value="this_week">Last Month</option> -->
                    </div>
                      </td>

                      <td>Month</td>
            <td>
            <input class="form-control border-form <?php if(isset($_POST['mon_th'])){ }else{ echo 'this-month'; } ?>" name="mon_th" type="month" id="mon_th" value="<?php if(isset($_POST['mon_th'])){ echo $_POST['mon_th']; } ?>">
            </td>	
                  
                 
                  </tr>	
                    </tbody>
                  </table>
                  <table>

                    <div class="align-right btnstyle" >

                    <button class="btn btn-info btnattr_cmn" type="button" id="filter-btn" name="filtercashlist">
                    Apply <i class="fa fa-filter bigger-110"></i>                         
                    </button> 

                    <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" >
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button> 

                    </div>
                    </table> 
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cash Import List</p>
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
              <th>Company Code</th>
              <th>Import Id</th>
              <th>Total Cnote Counts</th>
              <th>Status</th>
              <th>Date Added</th>
              <th>Actions</th>
              </tr>
        </thead>
              <tbody>
              <?php 

$conditions = array();                       
                         
$conditions[] = "cm.comp_code='$stationcode'";                       


                       

if(isset($_POST['filtercashlist']))
{    
 $stncode            = $_POST['stncode'];
 $import          = $_POST['import']; 
 $mon_th             = $_POST['mon_th']; 
 $periodical         = $_POST['periodical'];                        


 if(! empty($stncode)) {
 $conditions[] = "cm.comp_code LIKE '$stncode%'";
 }
 if(! empty($import)) {
 $conditions[] = "cm.import_id LIKE '$import%'";
 }                       

if(empty($periodical)) {
  $conditions[] = "YEAR(cm.date_added) ='".explode('-',$mon_th)[0]."' AND MONTH(cm.date_added) = '".explode('-',$mon_th)[1]."'";
  }
  else{
    if($periodical=='this_week'){
    $conditions[] = "cm.date_added BETWEEN DATEADD(DAY,- datepart(dw, getdate()), GETDATE()) AND DATEADD(DAY, 1, GETDATE())";
    }
    else if($periodical=='this_month'){
    $conditions[] = "datepart(mm,cm.date_added) = month(getdate())";
    }
  }                       

 $conditons = implode(' AND ', $conditions);                       

 if (count($conditions) > 0) {
     $query = $db->query("SELECT COUNT(import_id) as count, import_id,comp_code,date_added FROM cashbulkupload cm WHERE $conditons GROUP BY import_id,comp_code,date_added ORDER BY  import_id DESC"); 
   // echo json_encode($query);
 }
 if (count($conditions) == 0) {
 $query = $db->query("SELECT COUNT(import_id) as count, import_id,comp_code,date_added FROM cashbulkupload cm WHERE $conditons GROUP BY import_id,comp_code,date_added ORDER BY  import_id DESC"); 
 //echo json_encode($query);
}
}else{
$conditions[] = ((empty($_GET['date']))? "CAST(date_added as date)= CAST( GETDATE() AS Date )" : "YEAR(cm.date_added) ='".explode('-',$_GET['date'])[0]."' AND MONTH(cm.date_added) = '".explode('-',$_GET['date'])[1]."'"); 
$conditions[] = "cm.comp_code='$stationcode'";  
 $conditions[] = "(import_id IS NOT NULL or import_id!='')";
 $conditons = implode(' AND ', $conditions);

 $query = $db->query("SELECT COUNT(import_id) as count, import_id,comp_code,date_added FROM cashbulkupload cm WHERE $conditons GROUP BY import_id,comp_code,date_added ORDER BY  import_id DESC");
 //echo json_encode($query);
}
while($value = $query->fetch(PDO::FETCH_OBJ)){
  $timestamp = strtotime($value->date_added);
  $new_date_format = date('Y-m-d', $timestamp);
     echo "<tr>
               <td align='center'><label class='pos-rel'>
               <input type='checkbox' class='ace' />
               <span class='lbl'></span>
          </label></td>
           <td>".$value->comp_code."</td>
           <td>".$value->import_id."</td>
           <td>".$value->count."</td>
          
           <td>[".returnstatus($db,$value->import_id,$value->comp_code)."]</td>
           <td>".$new_date_format."</td>
           <td><div class='hidden-sm hidden-xs action-buttons'>
                

                  <a class='btn btn-primary  btn-minier' href='cashimportdetail?importid=$value->import_id&date=$new_date_format' data-rel='tooltip' title='Edit'>
                   <i class='ace-icon fa fa-eye bigger-130'></i>
                  </a>

                  <a  class='btn btn-danger btn-minier bootbox-confirm' >
                     <i class='ace-icon fa fa-trash-o bigger-130'></i>
                  </a>
                  <a  class='btn  btn-minier bootbox-confirm' id='bulkcash_print' data-import_no=$value->import_id data-comp_code=$value->comp_code>
                  <img src='vendor/bootstrap/icons/bulk_challan.png' width='20px' height='20px'>

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
            </td>
           </tr>";
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
  $(document).on('click','#bulkcash_print',function(){
     var import_id = $(this).data('import_no');
     var stncode = $('#bulkcash_print').data('comp_code');
    
     $.ajax({
            url:"<?php echo __ROOT__ ?>cashimport",
            method:"POST",
            data:{stncode,import_id,action:"bulk_cash_cnote"},
            dataType:"text",
            success:function(res)
            {
                
                var data = JSON.parse(res);
                console.log(data);
                if(data.success==false){
                  swal('No Cnotes in the import Number!');
                }
                else if(data.Aprovestatus=="NotApprove"){
                  swal('Bulk Cnotes are only open with current day entries!');
                }
                else{
                  window.open('<?php echo __ROOT__ ?>bulkcashcnoteprint?array_cno='+data.cnote+'&station='+stncode+'');
                }
                
            }
            });
  })
</script>


<script>
  var origin = document.location.origin;
  $(document).on('click','#downloadexcelheading',function(){
    $('#cashimportform').attr('action',''+origin+'/template');
  $('#downloadexcelheading').attr('type','submit');
  });
</script>


<script>
  var url = document.URL;
  $(document).on('click','#filter-btn',function(){
    $('#cashimportform').attr('action',url);
   $(this).attr('type','submit');
  });
</script>
<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('bookcounter_code').value = "";    
    document.getElementById('cust_name').value = "";   
}    
</script>
