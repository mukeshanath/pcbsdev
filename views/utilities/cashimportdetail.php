<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'cashimport';
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

  include 'vendor/bootstrap/simplexlxs.php';

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
                "<div class='alert alert-success alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                Record Added successfully.
                </div>";
                }

                ?>

                </div>
                <form method="POST" action="<?php echo __ROOT__ ?>cashimport">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cash Import Cnote Detail</p>
              <div class="align-right" style="margin-top:-30px;">
              <button type="button" toot-tip="Export Excel" class="btn btn-success" onclick="ExportToExcel('xlsx')"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>   
                <a style="color:white" href="<?php echo __ROOT__ ?>cashimportlist?date=<?=$_GET['date']?>"><button type="button" class="btn btn-danger btnattr_cmn" >
                <i class="fa fa-times" ></i>
                </button></a>

                </div>
        </div>
        <div style="background:#fff;border:1px solid var(--heading-primary);">
    <table class="table data-table" id="tbl_exporttable_to_xls" style="width:100%" cellspacing="0" cellpadding="0" align="center">
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
              <th>Tdate</th>
              <th>Company</th>

               <th>Origin</th>
               <th>C No</th>
               <th>Branch Code</th>
               <th>Destn</th>
               <th>Wt</th>
               <th>Pcs</th>
               <th>Mode</th>
               <th>Amt</th>
               <th>ramt</th>
               <th>Status</th>
              
              </tr>
        </thead>
              <tbody>
                  <?php 
                  $import_id = $_GET['importid'];
                  $import_date = $_GET['date'];
                $row = $db->query("SELECT * FROM cashbulkupload WHERE comp_code='$stationcode' AND import_id='$import_id'");
                $counter = 0;
                while($value = $row->fetch(PDO::FETCH_OBJ)){
                    echo "<tr>
                              <td align='center'><label class='pos-rel'>
                              <input type='checkbox' class='ace' />
                              <span class='lbl'></span>
                         </label></td>
                         <td>".++$counter."</td>
                          <td>".$value->tdate."</td>
                          <td>".$value->comp_code."</td>
                          <td>".$value->pod_origin."</td>
                          <td>".$value->cno."</td>
                          <td>".$value->br_code."</td>
                          <td>".$value->dest_code."</td>
                          <td>".$value->wt."</td>
                          <td>".$value->pcs."</td>
                          <td>".$value->mode_code."</td>
                          <td>".number_format($value->amt,2)."</td>
                          <td>".number_format($value->ramt,2)."</td>
                          <td>".$value->status."</td>
                          </tr>";
               }
                      ?>

                   
                 </tbody>
            </table>
              </div>
              </div>
                    </form>
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
         XLSX.writeFile(wb, fn || ('cashbulk_upload_'+ '<?=$import_date?>.' + (type || 'xlsx')));
    }
</script>