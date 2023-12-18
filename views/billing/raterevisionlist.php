<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'creditlist';
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
                                            <a href="<?php echo __ROOT__ . 'raterevisionlist'; ?>">Rate Revision List</a>
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

                  "<script> swal('RateRevision request has been Initiated and will be Complete in 15 to 20 mins (Completion time may vary based on Row Count)') </script>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Record Updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <form method="POST"  >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">RateRevision Filters</p>
                    <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>raterevision"><button type="button" class="btn btn-success btnattr" >
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
                  <td>Branch Code</td>
                  <td>
                  <div class="col-sm-12">
                        <input placeholder="" class="form-control border-form" name="br_code" type="text" id="br_code" value="<?php if(isset($_POST['br_code'])){ echo  $_POST['br_code']; } ?>"  autofocus>
                   </div>
                     </td>
                    
                  <td>Customer Code</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="cust_code" type="text" id="cust_code" value="<?php if(isset($_POST['cust_code'])){ echo  $_POST['cust_code']; } ?>">
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

                    <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtercreditlist">
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">RateRevision List</p>
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
              <th>Branch Code</th>
              <th>Customer Code</th>
              <th>Customer Name</th>
              <th>Tdate from</th>
              <th>Tdate to</th>
              <th>Username</th>
              <th>Reviced Cnote Counts</th>
              <th>Status</th>
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

                                $conditions = array();

                                $conditions[] = "cm.comp_code='$stationcode'";

                                //branch filter conditions
                                $sessionbr_code = $datafetch['br_code'];
                                if(!empty($sessionbr_code)){
                                $conditions[] = "cm.br_code='$sessionbr_code'";
                                }
                                //branch filter conditions

                             if(isset($_POST['filtercreditlist']))
                             {    
                                 $br_code            = $_POST['br_code'];
                                 $cust_code          = $_POST['cust_code'];
                                 //$date_added         = $_POST['date_added'];
                                 $mon_th             = $_POST['mon_th']; 
                                 $periodical         = $_POST['periodical']; 

             
                                 if(! empty($br_code)) {
                                 $conditions[] = "cm.br_code LIKE '$br_code%'";
                                 }
                                 if(! empty($cust_code)) {
                                 $conditions[] = "cm.cust_code LIKE '$cust_code%'";
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
                                     $query = $db->query("SELECT cm.comp_code,cm.br_code,cm.cust_code,c.cust_name,cnote_counts,cm.date_added FROM rate_deviation_master cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code=c.comp_code WHERE  $conditons"); 
                                 }
                                 if (count($conditions) == 0) {
                                 $query = $db->query("SELECT cm.comp_code,cm.br_code,cm.cust_code,c.cust_name,cnote_counts,cm.date_added FROM rate_deviation_master cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code=c.comp_code WHERE $conditons ORDER BY cm.rd_id DESC"); 
                             }
                             } 
                             else{   
                              $conditons = implode(' AND ', $conditions);

                              $query = $db->query("SELECT cm.tdate_from,cm.tdate_to,cm.user_name,cm.status,
                              cm.comp_code,cm.br_code,cm.cust_code,c.cust_name,cnote_counts,cm.date_added FROM rate_deviation_master cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code=c.comp_code WHERE $conditons AND cm.date_added BETWEEN DATEADD(DAY,-datepart(dw, getdate()), GETDATE()) AND DATEADD(DAY, 1, GETDATE()) ORDER BY rd_id DESC");
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
                           
                            echo "<td>".$row->comp_code."</td>";
                            echo "<td>".((empty($row->br_code))?'ALL':$row->br_code)."</td>";
                            echo "<td>".((empty($row->cust_code))?'ALL':$row->cust_code)."</td>";
                            echo "<td>".((empty($row->cust_name))?'ALL':$row->cust_name)."</td>";
                            echo "<td>".$row->tdate_from."</td>";
                            echo "<td>".$row->tdate_to."</td>";
                            echo "<td>".$row->user_name."</td>";
                            echo "<td>".$row->cnote_counts."</td>";
                            echo "<td><a  class='btn btn-".(($row->status=='Completed')?'success':'warning')." btn-minier bootbox-confirm' >
                                      ".$row->status."
                                  </a></td>";
                      //       echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                               

                      //       <a class='btn btn-primary btn-minier' href='creditcnotedetail?billed=$row->cust_code&date=$row->date_added' data-rel='tooltip' title='View'>
                      //        <i class='ace-icon fa fa-eye bigger-130'></i>
                      //       </a>

                      //       <a  class='btn btn-danger btn-minier bootbox-confirm' >
                      //          <i class='ace-icon fa fa-trash-o bigger-130'></i>
                      //       </a>
                      //     </div>
                      //     <div class='hidden-md hidden-lg'>
                      //       <div class='inline pos-rel'>
                      //        <button class='btn btn-minier btn-yellow dropdown-toggle' data-toggle='dropdown' data-position='auto'>
                      //         <i class='ace-icon fa fa-caret-down icon-only bigger-120'></i>
                      //        </button>
                      //        <ul class='dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close'>
                      //          <li>
                      //             <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                      //             <span class='blue'>
                      //             <i class='ace-icon fa fa-eye bigger-120'></i>
                      //             </span>
                      //             </a>
                      //          </li>
                      //          <li>
                      //             <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                      //              <span class='green'>
                      //              <i class='ace-icon fa fa-pencil-square-o bigger-120'></i>
                      //              </span>
                      //             </a>
                      //          </li>

                      //        <li>
                      //         <a  class='tooltip-error bootbox-confirm' data-rel='tooltip' title='Delete'>
                      //         <span class='red'>
                      //          <i class='ace-icon fa fa-trash-o bigger-120'></i>
                      //          </span>
                      //          </a>
                      //        </li>
                      //      </ul>
                      //                    </div>
                      //                </div>
                      // </td>";
                        
                          
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
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('cust_code').value = "";    
  
}
</script>


  