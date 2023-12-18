<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'tschargelist';
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
                                            <a href="#">Dashboard</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Accounts</li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Crossing Charge</li>
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
                                  Crossing Charge Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Crossing Charge Updated Successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <form method="POST"  >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

         <div class="boxed boxBt panel panel-primary">
         <div class="title_1 title panel-heading">
         <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Crossing Charge Filters</p>
         <div class="align-right" style="margin-top:-35px;">

        
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
                  <td>Branch</td>
                  <td>
                  <div class="col-sm-9">
                        <input placeholder="" class="form-control border-form" name="colmasno" type="text" id="colmasno"  autofocus>
                   </div>
                     </td>
                    
                  <td>Customer</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="cust_code" type="text" id="cust_code" >
                    </div>
                      </td>

                      <td>Credit Note</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="invno" type="text" id="invno" >
                    </div>
                      </td>

                      <td>Date</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="date" type="date" id="date" >
                    </div>
                      </td>
                  
                 
                  </tr>	
                    </tbody>
                  </table>
                  <table>

                    <div class="align-right btnstyle" >

                    <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtercredit">
                    Apply <i class="fa fa-filter bigger-110"></i>                         
                    </button> 

                    </div>
                    </table> 
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Note List</p>
         
           </div>
           <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>                            
              </th>
              <th>Branch</th>              
              <th>Customer</th>
              <th>Credit Note No</th>
              <th>Invoice No</th>             
              <th>Collection No</th>
              <th>Amount</th>
              <th>Remarks</th>              
              <th>Actions</th>              
              </tr>
    </thead>
              <tbody>
                  <?php 
                $stn_user = $_SESSION['user_name'];
                $get_stncode = $db->query("SELECT stncodes FROM users WHERE user_name='$stn_user' "); 
                $stn_user_code = $get_stncode->fetch(PDO::FETCH_ASSOC);
                $stn_res = $stn_user_code['stncodes']; 
                    if(empty($stn_res)) {
                        $stn_condition =""; 
                    }
                    if(is_null($stn_res)) {
                        $stn_condition =""; 
                    }
                    else{
                        $stn_condition = "WHERE comp_code='$stn_res'";
                    }
                if(isset($_POST['filtercredit']))
                {    
                    $br_code          = $_POST['br_code'];
                    $cust_code         = $_POST['cust_code'];
                    $crnno             = $_POST['crnno']; 
                    $date             = $_POST['date']; 

                    $conditions = array();

                    if(! empty($br_code)) {
                    $conditions[] = "br_code='$br_code'";
                    }
                    if(! empty($cust_code)) {
                    $conditions[] = "cust_code='$cust_code'";
                    }
                    if(! empty($crnno)) {
                    $conditions[] = "crnno='$crnno'";
                    }
                    if(! empty($date)) {
                    $conditions[] = "date_added='$date'";
                    }
                    if(!empty($stn_res)) {
                    $conditions[] = "comp_code='$stn_res'";
                    }
                    

                    $conditons = implode(' AND ', $conditions);

                    if (count($conditions) > 0) {
                        $query = $db->query("SELECT * FROM creditnote  WHERE  $conditons"); 
                    }
                    if (count($conditions) == 0) {
                    $query = $db->query("SELECT * FROM creditnote $stn_condition ORDER BY credit_note_id DESC"); 
                }
                }
                //Normal Station list
                else{
                $query = $db->query("SELECT * FROM creditnote $stn_condition ORDER BY credit_note_id DESC");
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
                    
                        echo "<td>".$row->br_code."</td>";
                        echo "<td>".$row->cust_code."</td>";
                        echo "<td>".$row->crnno."</td>";
                        echo "<td>".$row->invno."</td>";
                        echo "<td>".$row->colno."</td>";
                        echo "<td>".$row->netamt."</td>";
                        echo "<td>".$row->crnreason."</td>";
                        echo "<td><div class='hidden-sm hidden-xs action-buttons'>

                        <a class='btn btn-primary btn-minier' href='form.php?cnotepono='>
                            <i class='ace-icon fa fa-print bigger-130'></i>
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
function resetFunction() {
  document.getElementById("formcash").reset();
}

//DataTable
  $(document).ready(function(){
        $('#data-table').DataTable();
    });
</script>


  