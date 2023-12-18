<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
}

function authorize($db,$user)
{
    
    $module = 'bookingcounterlist';
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
    $module = 'bookingcounterdelete';
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
                                        <a href="<?php echo __ROOT__ . 'bookingcounterlist'; ?>">Booking Counter list</a>
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
                                  Booking Counter Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Booking Counter Successfully.
                                </div>";
                  }

                   ?>
                
             </div>
  
      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Booking Counter Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>bookingcounter"><button type="button" class="btn btn-success btnattr" >
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

       <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
            <td>Branch Code</td>
            <td>
            <input placeholder="" class="form-control border-form" name="br_code" type="text" id="br_code" value="<?php if(isset($_POST['br_code'])){ echo $_POST['br_code']; } ?>">      
            </td>
            <td>Counter Code</td>
            <td>
            <input class="form-controle"  type="text" name="book_code" id="book_code" value="<?php if(isset($_POST['book_code'])){ echo $_POST['book_code']; } ?>">          
            </td>                
            <td>Counter Name</td>
            <td>
                <input class="form-control border-form"  type="text" name="book_name" id="book_name"  value="<?php if(isset($_POST['book_name'])){ echo $_POST['book_name']; } ?>">               
            </td>            
            <td>Active</td>
            <td>
            <select class="form-control border-form" name="book_active" id="book_active">                
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
            </select>   
            </td>		           
            </tr>            
       </tbody>
     </table>
     <table>

        <div class="align-right btnstyle" >

        <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtercounter">
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
<div class="col-sm-12 data-table-div">

  <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Booking Counter List</p>
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
              <th>Counter code</th>
              <th>Counter Name</th>
              <th>Branch</th>
              <th class="hidden">Station Name</th>
              <th class="hidden">Book Addr Type</th>
              <th class="hidden">Book Address</th>
              <th>Phone</th>
              <th>Email</th>
              <th class="hidden">Fax</th>
              <th class="hidden">Remarks</th>
              <th class="hidden">Tin</th>
              <th class="hidden">GST</th>
              <th class="hidden">FSC</th>
              <th class="hidden">Cnote Charge</th>
              <th class="hidden">Special Discount</th>
              <th class="hidden">Book Deposit</th>
              <th class="hidden">Br Code</th>
              <th class="hidden">Br Name</th>
              <th class="hidden">Contact Persion</th>
              <th class="hidden">Flag</th>
              <th class="hidden">Proprietor</th>
              <th class="hidden">Domestic Commission</th>
              <th class="hidden">International Commission</th>
              <th class="hidden">Pro Commission</th>
              <th class="hidden">Deposit Date</th>
              <th>Active</th>
              <th>Action</th>
              </tr>
                </thead>
              <tbody>
                <?php  //Filter Button Event
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
                        $br_code = $sessionbr_code;
                        $conditions[] = "br_code='$br_code'";
                     }
                    //branch filter conditions
                     
                     if(isset($_POST['filtercounter']))
                     {    
                          $br_code      = $_POST['br_code'];
                          $br_name      = $_POST['br_name'];
                          $book_name     = $_POST['book_name'];
                          $book_active       = $_POST['book_active']; 


                          if(! empty($br_code)) {
                            $conditions[] = "br_code LIKE '$br_code%'";
                          }
                          if(! empty($book_code)) {
                            $conditions[] = "book_code LIKE '$book_code%'";
                          }
                          if(! empty($book_name)) {
                            $conditions[] = "book_name LIKE '$book_name%'";
                          }
                           if(! empty($book_active)) {
                             $conditions[] = "book_active='$book_active'";
                           }
                          

                           $conditons = implode(' AND ', $conditions);

                           if (count($conditions) > 0) {
                               $query = $db->query("SELECT * FROM bcounter  WHERE  $conditons"); 
                           }
                           if (count($conditions) == 0) {
                             $query = $db->query("SELECT * FROM bcounter WHERE  $conditons ORDER BY bkcid DESC"); 
                         }
                     }
                     //Normal Station list
                     else{
                      $conditons = implode(' AND ', $conditions);

                         $query = $db->query("SELECT * FROM bcounter WHERE  $conditons ORDER BY bkcid DESC");
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
                          
                          echo "<td>".$row->book_code."</td>";
                          echo "<td>".$row->book_name."</td>";
                          echo "<td >".$row->br_code."</td>"; 
                          echo "<td class='hidden'>".$row->stncode."</td>";
                          echo "<td class='hidden'>".$row->stnname."</td>";
                          echo "<td class='hidden'>".$row->book_addrtype."</td>";
                          echo "<td class='hidden'>".$row->book_addr."</td>";
                          echo "<td>".$row->book_phone."</td>";
                          echo "<td>".$row->book_email."</td>";                           
                          echo "<td class='hidden'>".$row->book_fax."</td>";                           
                          echo "<td class='hidden'>".$row->book_remarks."</td>";                           
                          echo "<td class='hidden'>".$row->book_tin."</td>";                           
                          echo "<td class='hidden'>".$row->book_gstin."</td>";                           
                          echo "<td class='hidden'>".$row->book_fsc."</td>";                           
                          echo "<td class='hidden'>".$row->cnote_charges."</td>";                           
                          echo "<td class='hidden'>".$row->spl_discount."</td>";                           
                          echo "<td class='hidden'>".$row->book_deposit."</td>";                           
                                                    
                          echo "<td class='hidden'>".$row->br_name."</td>";                           
                          echo "<td class='hidden'>".$row->c_person."</td>";                           
                          echo "<td class='hidden'>".$row->flag."</td>";                           
                          echo "<td class='hidden'>".$row->proprietor."</td>";                           
                          echo "<td class='hidden'>".$row->percent_domscomsn."</td>";                           
                          echo "<td class='hidden'>".$row->percent_intlcomsn."</td>";                           
                          echo "<td class='hidden'>".$row->pro_commission."</td>";                           
                          echo "<td class='hidden'>".$row->deposit_date."</td>";                           
                          echo "<td>".$row->book_active."</td>";
                          echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                              <a  class='btn btn-primary btn-minier'  data-rel='tooltip' title='View' href='bookingcounterdetail?bkcid=".$row->bkcid."'  >
                                <i class='ace-icon fa fa-eye bigger-130'></i>
                              </a>

                               <a class='btn btn-success btn-minier' data-rel='tooltip' title='Edit' href='bookingcounteredit?bkcid=".$row->bkcid."'   >
                                <i class='ace-icon fa fa-pencil bigger-130'></i>
                               </a>

                               <a  class='btn btn-danger btn-minier bootbox-confirm' id='bcounter,$row->bkcid' onclick='".delete($db,$user)."'>
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
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('book_code').value = "";    
    document.getElementById('book_name').value = "";   
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
          var deletecol = 'bkcid';
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