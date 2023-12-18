<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $module = 'cnoteallocationlistcc';
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
   
    function delete($db,$user){
      $module = 'cnotesalesdelete';
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
                                            <a href="#">Dashboard</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Cnote</li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">CC Allocation</li>
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">CC Allocation Filter</p>
              <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>cnoteallocationcc"><button type="button" class="btn btn-success btnattr" >
                <i class="fa fa-plus" ></i>
                </button></a>

                <a style="color:white" href="<?php echo __ROOT__ ?>home"><button type="button" class="btn btn-danger btnattr" >
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
            <td>Sales Id</td>
            <td>
            <input class="form-control stncode" name="salesid" type="text" id="salesid" value="<?php if(isset($_POST['salesid'])){ echo  $_POST['salesid']; } ?>">     
            </td>        
            
            <td>Branch Code</td>
            <td>
            <input class="form-control border-form" name="br_code" type="text" id="br_code" value="<?php if(isset($_POST['br_code'])){ echo  $_POST['br_code']; } ?>">
                
            </td>
            <td>Customer Code</td>
            <td>
            <input class="form-control border-form" name="cust_code" type="text" id="cust_code" value="<?php if(isset($_POST['cust_code'])){ echo  $_POST['cust_code']; } ?>">
                
            </td>
            <td>Cnote Serial</td>
            <td>
            <input class="form-control border-form text-uppercase" name="cnote_serial" type="text" id="cnote_serial" value="<?php if(isset($_POST['cnote_serial'])){ echo  $_POST['cnote_serial']; } ?>">
                
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">CC Allocation List</p>
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
            
                    <th >Sales ID</th>    
                    <th>Date</th>               
                    <!-- <th >Company Code</th>                   
                    <th >Branch Code</th>     -->
                    <th >Customer</th>                  
                    <th >Cnote Serial</th>
                    <th >Cnote Count</th>
                    <!-- <th >Cnote Details</th> -->
                    <th >Cnote Start</th>
                    <th >Cnote End</th>
                    <th >Cnote Status</th>
                    <th >Cnote Charge</th>
                    <th >Action</th>
              </tr>
    </thead>
              <tbody>
              <?php 
           // error_reporting(0);
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
                        $conditions[] = "br_code='$sessionbr_code'";
                      }
                      if(!empty($datafetch['cust_code'])){
                        $conditions[] = "cc_code='$sessionbr_code'";
                      }
                      //branch filter conditions

                     if(isset($_POST['filterreceive']))
                     {    
                      $salesid             = $_POST['salesid'];
                      $mon_th              = $_POST['mon_th']; 
                      $br_code             = $_POST['br_code']; 
                      $cust_code           = $_POST['cust_code']; 
                      $cnote_serial        = $_POST['cnote_serial']; 


                           if(! empty($stationcode)) {
                             $conditions[] = "stncode LIKE '$stationcode%'";
                           }                    
                           if(! empty($salesid)) {
                             $conditions[] = "salesorderid LIKE '$salesid%'";
                           }                    
                           if(! empty($mon_th)) {
                            $conditions[] = "YEAR(date_added) ='".explode('-',$mon_th)[0]."' AND MONTH(date_added) = '".explode('-',$mon_th)[1]."'";
                            }
                           if(!empty($br_code)) {
                             $conditions[] = "br_code LIKE '$br_code%'";
                           }
                           if(!empty($cust_code)) {
                             $conditions[] = "cust_code LIKE '$cust_code%'";
                           }
                           if(!empty($cnote_serial)) {
                            $conditions[] = "cnote_serial LIKE '$cnote_serial%'";
                          }

                           $conditons = implode(' AND ', $conditions);

                           if (count($conditions) > 0) {
                               $query = $db->query("SELECT salesorderid,date_added,cc_code,cnote_serial,cnote_procured,cnote_start,cnote_status,cnote_charge,(SELECT count(cnote_number) FROM cnotenumber WHERE allot_cc_no=cnoteallocationcc.salesorderid AND cnote_status!='Allotted' AND cnote_station=cnoteallocationcc.stncode) as billed_counts FROM cnoteallocationcc  WHERE  $conditons  AND flag='1'"); 
                           }
                           if (count($conditions) == 0) {
                             $query = $db->query("SELECT salesorderid,date_added,cc_code,cnote_serial,cnote_procured,cnote_start,cnote_status,cnote_charge,(SELECT count(cnote_number) FROM cnotenumber WHERE allot_cc_no=cnoteallocationcc.salesorderid AND cnote_status!='Allotted' AND cnote_station=cnoteallocationcc.stncode) as billed_counts FROM cnoteallocationcc WHERE  $conditons   AND flag='1' ORDER BY cnoteallocationccid DESC"); 
                         }
                     }
                     //Normal Station list
                     else{
                      $conditons = implode(' AND ', $conditions);

                         $query = $db->query("SELECT salesorderid,date_added,cc_code,cnote_serial,cnote_procured,cnote_start,cnote_status,cnote_charge,(SELECT count(cnote_number) FROM cnotenumber WHERE allot_cc_no=cnoteallocationcc.salesorderid AND cnote_status!='Allotted' AND cnote_station=cnoteallocationcc.stncode) as billed_counts FROM cnoteallocationcc WHERE  $conditons  AND datepart(mm,date_added) = month(getdate()) AND datepart(yyyy,date_added) = year(getdate()) AND flag='1' ORDER BY cnoteallocationccid DESC");
                     }
                     if($query->rowcount()==0)
                     {
                       echo "";
                     }
                     else{
                
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            //get cnote end
                            $cnote_detail = $db->query("SELECT TOP(1) cnote_end FROM cnoteallocationccdetail WHERE salesorderid='$row->salesorderid' ORDER BY allocationccdetailid DESC")->fetch(PDO::FETCH_OBJ);
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                           
                            echo "<td>".$row->salesorderid."</td>";                         
                            echo "<td>".$row->date_added."</td>";                         
                            // echo "<td>".$row->stncode."</td>";                         
                            // echo "<td>".$row->br_code."</td>";   
                            echo "<td>".$row->cc_code."</td>";                               
                            echo "<td>".$row->cnote_serial."</td>";
                            echo "<td>".$row->cnote_procured."</td>";
                            // echo "<td><button class='btn btn-success btnattr_cmn' type='button' id='filter-btn' name='filterreceive'  data-toggle='modal' data-target='#detail$row->salesorderid'>
                            // View <i class='fa fa-info-circle'></i>                         
                            // </button></td>";                           
                            echo "<td>".$row->cnote_start."</td>";
                            echo "<td>".$cnote_detail->cnote_end."</td>";
                            echo "<td>".$row->cnote_status."</td>";
                            echo "<td>".round($row->cnote_charge)."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                               
                            <a class='btn btn-success btn-minier' id='filter-btn' name='filterreceive' data-rel='tooltip' title='View Range' data-toggle='modal' data-target='#detail$row->salesorderid'>
                            <i class='fa fa-info-circle'></i>                         
                            </a>
                                 <a class='btn btn-primary btn-minier' href='cnotesalesdetail?soid=$row->salesorderid' data-rel='tooltip' title='View'>
                                  <i class='ace-icon fa fa-eye bigger-130'></i>
                                 </a>
                                 <a class='btn btn-success btn-minier' href='aosummary?sonumber=$row->salesorderid' data-rel='tooltip' title='Print' target='_blank'>
                                 <i class='ace-icon fa fa-print bigger-130'></i>
                                 </a>
                                 <a class='btn btn-warning btn-minier' href='cnotesalesedit?salesid=$row->salesorderid' data-rel='tooltip' title='Edit'>
                                 <i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>";
                                if($row->billed_counts=='0' && !empty($row->salesorderid)){
                                echo "<a  class='btn btn-danger btn-minier bootbox-confirm' id='$row->salesorderid' data-rel='tooltip' title='Delete' onclick='".delete($db,$user)."'>
                                    <i class='ace-icon fa fa-trash-o bigger-130'></i>
                                 </a>";
                                }
                               echo "</div>
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

                                   $viewdetails = $db->query("SELECT * FROM cnoteallocationccdetail WHERE salesorderid='$row->salesorderid'");
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
<script>
  //delete cnotesales
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
    var del_salesno_cc = id;
    var del_salesstn_cc = '<?=$stationcode?>';
    var del_sales_user_cc = '<?=$user?>';
    //console.log(del_salesno_cc);
    $.ajax({
              url:"<?php echo __ROOT__ ?>cnotecontroller",
              method:"POST",
              data:{del_salesno_cc,del_salesstn_cc,del_sales_user_cc},
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