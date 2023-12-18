<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'cnotesalesrequestlist';
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
  
function loadaddresstype($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM address ORDER BY aid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->addr_type.'">'.$row->addr_type.'</option>';
         }
         return $output;    
        
    }

    function loadgroup($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM groups ORDER BY gpid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
         }
         return $output;    
        
    }

    function loadstatus($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM status ORDER BY sid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stat_type.'">'.$row->stat_type.'</option>';
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
                                        <a href="<?php echo __ROOT__ . 'cnotesalesrequestlist'; ?>">Cnote Sales list</a>
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
                        Cnote Sales Added Successfully.
                      </div>";
        }


        ?>
                
             </div>
  

     

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Sales Request Filter</p>
              <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
                        <i class="fa fa-times" ></i>
                </button></a>

                </div>
        </div>

     
        
      <div class="content panel-body">
      

        <div class="tab_form">
      

      <form method="POST">
       <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
       <table class="table" cellspacing="0" cellpadding="0" align="center">	
       <tbody>
            <tr>		
            <td>Station Code</td>
            <td>
            <input class="form-control stncode" name="stncode" type="text" id="stncode" value="<?php if(isset($_POST['stncode'])){ echo  $_POST['stncode']; } ?>">     
            </td>        
            
            <td>Branch Code</td>
            <td>
            <input class="form-control border-form" name="br_code" type="text" id="br_code" value="<?php if(isset($_POST['br_code'])){ echo  $_POST['br_code']; } ?>">
                
            </td>
            <td>Cnote Serial</td>
            <td>
            <input class="form-control border-form" name="cnote_serial" type="text" id="cnote_serial" value="<?php if(isset($_POST['cnote_serial'])){ echo  $_POST['cnote_serial']; } ?>">
                
            </td>		
           
            </tr>
            
       </tbody>
     </table>
     <table>

      <div class="align-right btnstyle" >

      <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterreceive">
         Apply <i class="fa fa-filter bigger-110"></i>                         
      </button> 
      
      <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()">
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button>                                                  


      </div>
      </table>

          

     </form>
     </div>
<!-- Fetching Mode Details in Table  -->
<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Sales Request List</p>
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
            
                    <th >CompCode</th>                   
                    <th >BrCode</th>    
                    <th >Customer</th>                  
                    <th >Cn Serial</th>
                    <th >Cn Count</th>
                    <th >Cn Details</th>
                    <th >Sales Req Id</th>                    
                    <th >Sales ID</th>                    
                    <th >Status</th>
                    <th >Cn Charge</th>
                    <th >Action</th>
              </tr>
    </thead>
              <tbody>
              <?php 
                    //error_reporting(0);
                    //  //Filter Button Event
                     $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
                     $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
                     $rowuser = $datafetch['last_compcode'];
                     if(!empty($rowuser)){
                           $stationcode = $rowuser;
                           $stn_condition = "WHERE comp_code='$stationcode'";
                     }
                     else{
                         $userstations = $datafetch['stncodes'];
                         $stations = explode(',',$userstations);
                         $stationcode = $stations[0];  
                         $stn_condition = "WHERE comp_code='$stationcode'";   
                     }
                     if(isset($_POST['filterreceive']))
                     {    
                      $comp_code             = $_POST['stncode'];
                     
                      $br_code             = $_POST['br_code']; 
                      $cnote_serial        = $_POST['cnote_serial']; 

                          $conditions = array();

                          if(! empty($stationcode)) {
                             $conditions[] = "comp_code LIKE '$stationcode%'";
                           }                    
                           
                           if(!empty($br_code)) {
                             $conditions[] = "br_code LIKE '$br_code%'";
                           }
                           if(!empty($cnote_serial)) {
                            $conditions[] = "serial_type LIKE '$cnote_serial%'";
                          }

                           $conditons = implode(' AND ', $conditions);

                           if (count($conditions) > 0) {
                               $query = $db->query("SELECT * FROM cnotesalesrequest  WHERE  $conditons"); 
                           }
                           if (count($conditions) == 0) {
                             $query = $db->query("SELECT * FROM cnotesalesrequest $stn_condition ORDER BY sales_req_id DESC"); 
                         }
                     }
                     //Normal Station list
                     else{
                         $query = $db->query("SELECT * FROM cnotesalesrequest $stn_condition ORDER BY sales_req_id DESC");
                     }
                     if($query->rowcount()==0)
                     {
                       echo "";
                     }
                     else{
                
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            if($row->status=='Approved'){
                              $colorclass = 'success';
                            }
                            else{
                              $colorclass = 'warning';
                            }
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                           
                            echo "<td>".$row->comp_code."</td>";                         
                            echo "<td>".$row->br_code."</td>";   
                            echo "<td>".$row->cust_code."</td>";                               
                            echo "<td>".$row->serial_type."</td>";
                            echo "<td>".$row->cnote_quantity."</td>";
                            echo "<td><button class='btn btn-$colorclass btnattr_cmn' type='button' id='filter-btn' name='filterreceive'  data-rel='tooltip' title='View Ranges' data-toggle='modal' data-target='#detail$row->salesorderid'>
                            View <i class='fa fa-info-circle'></i>                         
                            </button></td>";                           
                            echo "<td>".$row->request_id."</td>";
                            echo "<td>".$row->salesorderid."</td>";
                            echo "<td><button class='btn btn-$colorclass btnattr_cmn' type='button' >$row->status</button></td>";
                            echo "<td>".number_format($row->stationary_charge,2)."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                               

                                 <a class='btn btn-primary btn-minier' ".(($row->status=='Approved')?'onclick="swal(\'Request already Approved\');"':'')." ".(($row->status=='Approved')?'_':'')."href='cnotesalesapprove?request_id=".$row->request_id."' data-rel='tooltip' title='Edit'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>
                                 <a class='btn btn-success btn-minier' _href='sosummary?sonumber=' data-rel='tooltip' title='Print' target='_blank'>
                                 <i class='ace-icon fa fa-print bigger-130'></i>
                                 </a>
                                 <a  class='btn btn-danger btn-minier bootbox-confirm' data-rel='tooltip' title='Delete' >
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

                                   $viewdetails = $db->query("SELECT * FROM cnotesalesdetail WHERE salesorderid='$row->salesorderid'");
                                   $i=0;
                                   while($saledatarow = $viewdetails->fetch(PDO::FETCH_OBJ)){
                                   $i++;
                                   echo"<!-- Modal for Zone code A-->
                                   <div class='modal fade' id='detail$row->salesorderid' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                     <div class='modal-dialog' role='document' style='width:30%'>
                                       <div class='modal-content'>
                                         <div class='modal-header'>
                                           <h5 class='modal-title' id='exampleModalLabel'>Cnote Detail</h5>
                                         </div>
                                         <div class='modal-body center' style='line-height:20px'>
                                          <strong class=''>S.No</strong><strong style='margin-left:50px;' class='center'>Cnote Start </strong><strong style='margin-left:130px;' class='center'>Cnote End</strong><br>
                                          <label>".$i."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><label class='center' style='margin-left:50px;'>".$saledatarow->cnote_start."</label> <label class='center' style='margin-left:130px'>".$saledatarow->cnote_end."</label>
                                         
                                      
                                          </div>
                                         <div class='modal-footer'>
                                           <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                                   <!-- End -->";
                                  }                       
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


<!-- inline scripts related to this page -->

<script>
function resetFunction() {
    document.getElementById('stncode').value = "";
    document.getElementById('br_code').value = ""; 
    document.getElementById('cnote_serial').value = "";    
}      
</script>