<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'creditcnotedetail';
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

if(isset($_GET['date']))
{
    $date = $_GET['date'];
    $bc_code = $_GET['bc_code'];
    $query = $db->query("SELECT *,(SELECT count(*) FROM denomination where col_date=cash.tdate AND comp_code=cash.comp_code and br_code=cash.br_code) den_counts FROM cash WHERE tdate='$date' AND book_counter='$bc_code' AND comp_code='$stationcode'");
    $exitroute = 'cashlist';
}
if(isset($_GET['import']))
{
    $date_added = $_GET['import'];
    $query = $db->query("SELECT *,(SELECT count(*) FROM denomination where col_date=cash.tdate AND comp_code=cash.comp_code and br_code=cash.br_code) den_counts FROM cash WHERE date_added = '$date_added' AND import='1'");
    $exitroute = 'cashimportlist';
}

function delete($db,$user){
  $module = 'cashdelete';
  $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
  $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
  $user_group = $rowusergrp->group_name;

  $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
      if($verify_user->rowCount()=='0'){
          return 'swal("You Dont Have Permission to Delete this Record!")';
      }
      else{
          return "deletequeue(this.id,this.value,this.name)";
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
                                        <a href="<?php echo __ROOT__ . 'cashlist'; ?>">Cash List</a>
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
                                Record Added successfully.
                </div>";
                }

                ?>

                </div>

        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cash Cnote Detail</p>
              <div class="align-right" style="margin-top:-33px;margin-right:40px" id="buttons"></div>
              <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ .$exitroute;?>"><button type="button" class="btn btn-danger btnattr" >
                <i class="fa fa-times" ></i>
                </button></a>

                </div>
        </div>
        <div style="background:#fff;border:1px solid var(--heading-primary);">
    <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>            
                    <th >CNote No</th>
                    <!-- <th >Customer Code</th> -->
                    <th >Origin</th>
                    <th >ShipperName</th>
                    <th >ShipperAddress</th>
                    <th >ShipperMobile</th>
                    <th >Destination</th>
                    <th >Weight</th>
                    <th >Pcs</th>
                    <th >Mode</th>
                    <th >Amount</th>
                    <th >Bill Date</th>
                    <th >Entry Date</th>
                    <th >Status</th> 
                    <th >Action</th> 
                    
              </tr>
        </thead>
              <tbody>
                  <?php 


                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                           
                            echo "<td>".$row->cno."</td>";
                            echo "<td>".$row->origin."</td>";
                            echo "<td>".$row->shp_name."</td>";
                            echo "<td>".$row->shp_addr."</td>";
                            echo "<td>".$row->shp_mob."</td>";
                            echo "<td>".$row->dest_code."</td>";
                            echo "<td>".number_format((float)$row->wt, 3)."</td>";
                            echo "<td>".$row->pcs."</td>";
                            echo "<td>".$row->mode_code."</td>";
                            echo "<td>".round($row->ramt)."</td>";  
                            echo "<td>".$row->tdate."</td>";     
                            echo "<td>".$row->edate."</td>"; 
                            echo "<td>".$row->status."</td>";                           
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                            <a  class='btn btn-primary btn-minier bootbox-confirm' _href='cashchallan?cno=$row->cno' target='_blank'>
                               <i class='ace-icon fa fa-print bigger-130'></i>
                            </a>";

                            if($row->den_counts==0)
                            {
                            echo "
                            <button  class='btn btn-danger btn-minier bootbox-confirm' name='$row->status' value='$row->cnote_serial' id='$row->cno' onclick='".delete($db,$user)."'>
                            <i class='ace-icon fa fa-trash-o bigger-130'></i>
                             </button>
                             ";
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
                           
                          }                    
   
                      ?>

                   
                 </tbody>
            </table>
              </div>
              </div>
</section>
<script>
function deletequeue(cnotenumber,cnoteserial,statuscno){
    swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this record file!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
  })
  .then((willDelete) => {

    if (willDelete) {
      var delcash_ecompany = '<?=$stationcode?>';
      var delcash_ecnote = cnotenumber;
      var serial  = cnoteserial;
      var status = statuscno;
      var delcash_euser = '<?=$user?>'
      $.ajax({
                url:"<?php echo __ROOT__ ?>cash",
                method:"POST",
                data:{delcash_ecompany,delcash_ecnote,delcash_euser,serial},
                dataType:"text",
                success:function(cnotinv){
                    collectioncenters = $.parseJSON(cnotinv);
                  }
                
        });
        if(statuscno=='Invoiced'){
            swal("already invoiced");
          }else{
      swal("Your Record has been deleted!", {
        icon: "success",
      });
      $('#'+cnotenumber).closest('tr').remove();
    }
      
    } else {
      swal("Your Record is safe!");
    }
  });
  
}

</script>
<script>
  var cnotenumber =[<?php  

$brname=$db->query("SELECT * from cash");
while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrname['cno']."',";
}

?>];

</script>