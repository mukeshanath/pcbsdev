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

  date_default_timezone_set('Asia/Kolkata');

  function delete($db,$user){
    $module = 'creditdelete';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;
  
    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'swal("You Dont Have Permission to Delete this Record!")';
        }
        else{
            return "deletequeue(this.id,this.value)";
        }
  }

if(isset($_GET['billed']))
{
   // $today = date('Y-m-d');
    $custcode = $_GET['billed'];
    $query = $db->query("SELECT * FROM credit WHERE cust_code = '$custcode' AND tdate='".$_GET['date']."' ORDER BY tdate DESC");
    

    $exitroute = 'creditlist';
}
if(isset($_GET['import']))
{
    $import = $_GET['import'];
    $import_date = $_GET['date'];
    $query = $db->query("SELECT * FROM creditbulkuploadbatch WHERE comp_code='$stationcode' AND import='$import'");
    $exitroute = 'creditimportlist';

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
                                        <a href="<?php echo __ROOT__ . 'creditlist'; ?>">Credit List</a>
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Cnote Detail</p>
              <div class="align-right" style="margin-top:-33px;margin-right:40px" id="buttons"></div>
              <div class="align-right" style="margin-top:-35px;">
              <button type="button" toot-tip="Export Excel" class="btn btn-success" onclick="ExportToExcel('xlsx')"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
                <a style="color:white" href="<?php echo __ROOT__ .$exitroute?>"><button type="button" class="btn btn-danger btnattr" >
                <i class="fa fa-times" ></i>
                </button></a>

                </div>
        </div>
        <div style="background:#fff;border:1px solid var(--heading-primary);">
    <table id="tbl_exporttable_to_xls" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
        <th class="center">
            <label class='pos-rel'>
                              <input type='checkbox' class='ace' />
                              <span class='lbl'></span>
                         </label>
              </th>    
              <th>
               S.no
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
                   
              </tr>
        </thead>
              <tbody>
                  <?php 
  $counter = 0;
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                             echo "<td>".++$counter."</td>";
                            echo "<td>".$row->cno."</td>";
                            echo "<td>".$row->cust_code."</td>";
                            echo "<td>".$row->origin."</td>";
                            echo "<td>".$row->dest_code."</td>";
                            echo "<td>".number_format((float)$row->wt, 3)."</td>";
                            echo "<td>".$row->pcs."</td>";
                            echo "<td>".$row->mode_code."</td>";
                            echo "<td>".number_format($row->amt,2)."</td>";                             
                            echo "<td>".$row->tdate."</td>";     
                            echo "<td>".$row->edate."</td>"; 
                            echo "<td>".$row->status."</td>";                           
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>";
      
                            if($row->status == 'Billed'){
                              echo "
                              <button type='button' class='btn btn-danger btn-minier bootbox-confirm' value='$row->cnote_serial' id='$row->cno' onclick='".delete($db,$user)."'>
                              <i class='ace-icon fa fa-trash-o bigger-130'></i>
                           </button>
                           ";
                             }else{
 
                             }
                        echo  "</div>
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
     <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
</script>
<script>
   
    function ExportToExcel(type, fn, dl) {
       var elt = document.getElementById('tbl_exporttable_to_xls');
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('creditbulk_upload'+ '<?=$import_date?>.' + (type || 'xlsx')));
    }
</script>
<script>
function deletequeue(cnotenumber,serial){
    swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this record file!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
  })
  .then((willDelete) => {

    if (willDelete) {
      var delcredit_ecompany = '<?=$stationcode?>';
      var delcredit_ecnote = cnotenumber;
      var delcredit_serial = serial;
      var delcredit_euser = '<?=$user?>'
      $.ajax({
                url:"<?php echo __ROOT__ ?>credit",
                method:"POST",
                data:{delcredit_ecompany,delcredit_ecnote,delcredit_euser,delcredit_serial},
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
