<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'receivelist';
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
                                        <a href="<?php echo __ROOT__ . 'receivelist'; ?>">Cnote Receive list</a>
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
                        Cnote Received.
                      </div>";
        }
        if ( isset($_GET['success']) && $_GET['success'] == 5 )
        {
                echo 
        "<script>
            swal('Receive request has been Initiated and will be completed with in 5 to 10 mins.');
        </script>";
        }

        ?>
                
             </div>
  

     

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Receive Filter</p>
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
            <td>Purchase Order No</td>
            <td>
            <input class="form-control stncode" name="po_no" type="text" id="po_no" value="<?php if(isset($_POST['po_no'])){ echo  $_POST['po_no']; } ?>">
            
            </td>
                
            <td>Cnote Serial</td>
            <td>
            <input class="form-control border-form" name="cnote_serial" type="text" id="cnote_serial" value="<?php if(isset($_POST['cnote_serial'])){ echo  $_POST['cnote_serial']; } ?>">
           

            </td>
            <td>Vendor</td>
            <td>
            <input class="form-control border-form" name="vendor" type="text" id="vendor" value="<?php if(isset($_POST['vendor'])){ echo  $_POST['vendor']; } ?>">
                
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
                                                        
      <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()" >
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button> 
      <!-- <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
      <i class="fa fa-eraser"></i>&nbsp; Reset
      </button>    -->


      </div>
      </table>

          

     </form>
     </div>
<!-- Fetching Mode Details in Table  -->
<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Receive List</p>
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
              <th>S.N.</th>
                    <th >Purchase Order No</th>
                    <th >Cnote Station</th>
                    <th >Cnote Serial</th>
                    <th >Total Count</th>
                    <th >Cnote Start</th>
                    <th >Cnote End</th>
                    <th >Cnote Status</th>
                    <th >Received Count</th>
                    <th >Pending Count</th>
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
                        $stn_condition = "WHERE cnote_station='$stationcode'";
                    }
                    else{
                        $userstations = $datafetch['stncodes'];
                        $stations = explode(',',$userstations);
                        $stationcode = $stations[0];  
                        $stn_condition = "WHERE cnote_station='$stationcode'";   
                    }
                     if(isset($_POST['filterreceive']))
                     {    
                      $po_no              = $_POST['po_no'];
                      $cnote_serial       = $_POST['cnote_serial'];
                      $vendor             = $_POST['vendor']; 
                      $mon_th             = $_POST['mon_th']; 

                          $conditions = array();

                          if(! empty($po_no)) {
                             $conditions[] = "cnotepono='$po_no'";
                           }                    
                           if(! empty($cnote_serial)) {
                             $conditions[] = "cnote_serial LIKE '$cnote_serial%'";
                           }
                           if(!empty($vendor)) {
                             $conditions[] = "vendor LIKE '$vendor%'";
                           }
                           if(! empty($mon_th)) {
                            $conditions[] = "YEAR(date_added) ='".explode('-',$mon_th)[0]."' AND MONTH(date_added) = '".explode('-',$mon_th)[1]."'";
                            }
                           if(!empty($stationcode)) {
                             $conditions[] = "cnote_station='$stationcode'";
                           }
                           
                           // AND pending_count !=0 
                           $conditons = implode(' AND ', $conditions);

                           if (count($conditions) > 0) {
                               $query = $db->query("SELECT * FROM cnotereceive  WHERE  $conditons"); 
                           }
                           if (count($conditions) == 0) {
                             $query = $db->query("SELECT * FROM cnotereceive $stn_condition ORDER BY cnotereceiveid DESC"); 
                         }
                     }
                     //Normal Station list
                     else{
                         $query = $db->query("SELECT * FROM cnotereceive $stn_condition AND datepart(mm,date_added) = month(getdate()) AND datepart(yyyy,date_added) = year(getdate()) ORDER BY cnotereceiveid DESC");
                     }
                     if($query->rowcount()==0)
                     {
                       echo "";
                     }
                     else{
                
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            $getnewserial = $db->query("SELECT * FROM groups WHERE cate_code='$row->cnote_serial' AND stncode='$row->cnote_station'")->fetch(PDO::FETCH_OBJ);
                            if($getnewserial->grp_type=='gl'){
                                $rowserial = $row->cnote_station;
                            }
                            else{
                                $rowserial = $getnewserial->cate_code;
                            }
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                            echo "<td>".$row->cnotereceiveid."</td>";
                            echo "<td>".$row->cnotepono."</td>";
                            echo "<td>".$row->cnote_station."</td>";
                            echo "<td>".$row->cnote_serial."</td>";
                            echo "<td>".$row->cnote_count."</td>";
                            echo "<td>".$rowserial.$row->actuel_count."</td>";
                            echo "<td>".$rowserial.$row->cnote_end."</td>";
                            echo "<td>".$row->cnote_status."</td>";                           
                            echo "<td>".$row->received_count."</td>";
                            echo "<td>".$row->pending_count."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                               

                                 <a class='btn btn-success btn-minier' href='receiveform?cnotereceiveid=".$row->cnotereceiveid."' data-rel='tooltip' title='Edit'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
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


<!-- the above ui.all.css makes erroe message (color #fff) during customer creation, 
		whcih makes error unviewable, hence the below code written  -->
<style>
	#POSCustErrorMsg .error{
	color : #FE2E64;
	}
</style>

<!-- inline scripts related to this page -->
<script>
 $(document).ready(function(){
    $('.po_no').keyup(function(){
        var purordno = $('.po_no').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{purordno:purordno},
            dataType:"text",
            success:function(data)
            {
            $('#postorder').html(data);
            }
    });
    });

    $('.po_no').keyup(function(){
        var purno = $('.po_no').val();    
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{purno:purno},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#station_code').val(data[0]);
            $('#station_name').val(data[1]);
            $('#br_code').val(data[2]);
            $('#br_name').val(data[3]);
            }
    });
    });
});


</script>
<script>
function resetFunction() {
    document.getElementById('po_no').value = "";
    document.getElementById('cnote_serial').value = ""; 
    document.getElementById('vendor').value = "";    
}  
</script>