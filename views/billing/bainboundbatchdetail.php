<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'bainboundbatchdetail';
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
    $module = 'bainboundbatchdelete';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;
  
    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'swal("You Dont Have Permission to Delete this Record!")';
        }
        else{
            return "deletequeue(this.id)";
        }
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

  date_default_timezone_set('Asia/Kolkata');

if(isset($_GET['mfmasno']))
{
   // $today = date('Y-m-d');
    $gmsmasno = $_GET['mfmasno'];
    $mfmaster = $db->query("SELECT * FROM bainboundbatch_master WHERE bainboundmasterid='$gmsmasno'")->fetch(PDO::FETCH_OBJ); 
    $getpod = $db->query("SELECT * FROM bainboundbatch WHERE mf_no='$mfmaster->mf_no' AND bacode='$mfmaster->ba_code' AND cast(date_added as date)='$mfmaster->date_added' ORDER BY cno,bainboundbatch_id");
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
                                        <a href="<?php echo __ROOT__ . 'bainboundbatchlist'; ?>">BAIB List</a>
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">BaInbound Detail</p>
              <div class="align-right" style="margin-top:-33px;margin-right:40px" id="buttons"></div>
              <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>bainboundbatchlist"><button type="button" class="btn btn-danger btnattr" >
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
                    <th >Customer Code</th>
                    <th >Origin</th>
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

                 $prevcno = '';
                 $status = '';
                         while ($row = $getpod->fetch(PDO::FETCH_OBJ))
                          {
                              $billed = $db->query("SELECT count(*) as counts FROM credit WHERE cno='$row->cno' AND comp_code='$row->comp_code'")->fetch(PDO::FETCH_OBJ);
                              if($billed->counts>=1){
                                  $status2 = 'Billed';
                              }
                              else{
                                  $status2 = '';
                              }

                            if($prevcno==$row->cno){
                                $status = 'Duplicate';
                            }
                            else{
                                $status = $status2;
                            }
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                           
                            echo "<td>".$row->cno."</td>";
                            echo "<td>".$row->bacode."</td>";
                            echo "<td>".$row->origin."</td>";
                            echo "<td>".$row->dest_code."</td>";
                            echo "<td>".number_format((float)$row->wt, 3)."</td>";
                            echo "<td>".$row->pcs."</td>";
                            echo "<td>".$row->mode_code."</td>";
                            echo "<td>".round($row->amt)."</td>";
                            echo "<td>".date("d-m-Y", strtotime($row->bdate))."</td>";
                            echo "<td>".date("d-m-Y", strtotime($row->edate))."</td>";
                            echo "<td>".$row->status."</td>                         
                            <td><a id='".$row->cno."' onclick='".delete($db,$user)."' class='btn btn-danger btn-minier bootbox-confirm' ><i class='ace-icon fa fa-trash-o bigger-130'></i></a></td>";  
                                   echo "</tr>";
                            $prevcno = $row->cno;
                          }                    
   
                      ?>

                   
                 </tbody>
            </table>
              </div>
              </div>
</section>

<script>
function deletequeue(cnotenumber){
    swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this record file!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
  })
  .then((willDelete) => {

    if (willDelete) {
      var delbatchcompany = '<?=$stationcode?>';
      var delbatchcnote = cnotenumber;
      var delbatchuser = '<?=$user?>'
      $.ajax({
                url:"<?php echo __ROOT__ ?>bainboundbatch",
                method:"POST",
                data:{delbatchcompany,delbatchcnote,delbatchuser},
                dataType:"text",
                
        });
      swal("Your Record has been deleted!", {
        icon: "success",
      });
      $('#'+cnotenumber).closest('tr').remove();
      
    } else {
      swal("Your Record is safe!");
    }
  });
  
}

</script>