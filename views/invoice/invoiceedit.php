<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $module = 'invoiceedit';
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

if(isset($_GET['invno'])){
    $get_from_invoice  = $db->query("SELECT * FROM invoice WHERE comp_code='$stationcode' AND invno='".$_GET['invno']."'");
    $rowinv = $get_from_invoice->fetch(PDO::FETCH_OBJ);
}
?>
<style>
textarea {
  resize: none;
  width: 250px;
}
textarea.ta10em {
  height: 10em;
 
}
</style>
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
                                            <a href="<?php echo __ROOT__ . 'invoicelist'; ?>">Invoice list</a>
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
                                  Invoice Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Invoice updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <form method="POST"  >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice Customer Detail</p>
                    <div class="align-right" style="margin-top:-35px;">

        <button type="button" class="btn btn-warning btnattr" data-toggle="tooltip" title="Invoice Rerun" <?=($rowinv->inv_type=='manual')?' disabled readonly ':'' ?> onclick="invoicererun()">
         <i class="fa fa-repeat" ></i>
        </button>

        <button type="button" class="btn btn btnattr" data-toggle="tooltip" title="Rate Rerun" <?=($rowinv->inv_type=='manual')?' disabled readonly ':'' ?> onclick="invoiceratererun()">
        <i class="fa fa-money" aria-hidden="true"></i>
     </button>

        <button type="button" class="btn btn-success btnattr" data-toggle="modal" data-target="#invoice_modal" <?=($rowinv->inv_type=='manual')?' disabled readonly ':'' ?> onclick="focus12()">
         <i class="fa fa-plus" ></i>
        </button>

        <a style="color:white" href="<?php echo __ROOT__ ?>invoicelist"><button type="button" class="btn btn-danger btnattr" >
                <i class="fa fa-times" ></i>
        </button></a>

        </div>
              
                </div> 
      <div class="content panel-body">


        <div class="tab_form">


          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>		
                  <td>Invoice No</td>
                  <td>
                  <div class="col-sm-12">
                        <input placeholder="" class="form-control border-form" name="inv_no" type="text" id="inv_no" value='<?=$_GET['invno'];?>' readonly>
                   </div>
                     </td>
                    
                  <td>Company Code</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="comp_code" type="text" id="comp_code" value="<?=$rowinv->comp_code;?>" readonly>
                    </div>
                      </td>

                    <td>Branch Code</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="br_code" type="text" id="br_code" value="<?=$rowinv->br_code;?>" readonly>
                    </div>
                      </td>

                      <td>Customer Code</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="cust_code" type="text" id="cust_code" value="<?=$rowinv->cust_code;?>" readonly>
                    </div>
                      </td>
                  
                 
                  </tr>	
                    </tbody>
                  </table>
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoiced Cnote List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
           </div>

             <!-- comment this table to take big invoice data  -->

           <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
                   </th>
                    <th >Cnote Number</th>
                    <th >Bill Wt</th>
                    <th ><?= ($rowinv->br_code=='TR')?'TR ':'' ?>Bill Rate</th>
                    <?= ($rowinv->br_code=='TR')?'<th>Type</th>':'' ?>
                    <th >Bill Mode</th>
                    <th >Bill Dest</th>
                    <th >Booking Date</th>
                    <th >Action</th>
              </tr>
         </thead>
              <tbody>
              <?php 

          if(isset($_GET['invno']))
           {
               $invno = $_GET['invno'];
               if($rowinv->br_code=='TR'){
                $query = $db->query("SELECT * FROM opdata WHERE comp_code='$stationcode' AND invno = '$invno' ORDER BY tdate");
                while ($row = $query->fetch(PDO::FETCH_OBJ))
                {
                 $origDate = "$row->tdate";
                 $newDate = date("d-m-Y", strtotime($origDate));
                 $rowtype = ($row->mode_code=='DE')?'DLY':'TR';

                  echo "<tr>";
                  echo "<td class='center first-child'>
                          <label>
                             <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                              <span class='lbl'></span>
                           </label>
                        </td>";
                  echo "<td>".$row->cno."</td>";
                  echo "<td>".number_format($row->wt,3)."</td>";
                  echo "<td>".number_format($row->amt,2)."</td>";
                  echo "<td>".$rowtype."</td>";
                  echo "<td>".$row->mode_code."</td>";
                  echo "<td>".$row->destn."</td>";
                  echo "<td>".$newDate."</td>";    
                  echo "<td><div class='hidden-sm hidden-xs action-buttons'>
     
                  <a  class='btn btn-danger btn-minier bootbox-confirm removeinvoice' id='$row->cno' data-rel='tooltip' title='Delete'>
                     <i class='ace-icon fa fa-trash-o bigger-130'></i>
                  </a>
                </div>
                   </td>";                         
                echo "</tr>";
                 
                }  
            }
            else{
               $query = $db->query("SELECT * FROM credit WHERE comp_code='$stationcode' AND invno = '$invno' ORDER BY bdate");
        

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
           echo "<td>".number_format($row->wt,3)."</td>";
           echo "<td>".number_format($row->amt,2)."</td>";
           echo "<td>".$row->mode_code."</td>";
           echo "<td>".$row->dest_code."</td>";
           echo "<td>".date("d-m-Y", strtotime($row->tdate))."</td>";
           echo "<td><div class='hidden-sm hidden-xs action-buttons'>";
   
           if($row->status=='Invoiced'){
            echo "<a  class='btn btn-danger btn-minier bootbox-confirm removeinvoice' id='$row->cno' data-rel='tooltip' title='Delete'>
            <i class='ace-icon fa fa-trash-o bigger-130'></i>
         </a>";
           }else{
            echo "<a  class='btn btn-danger btn-minier bootbox-confirm removeinvoice' disabled id='$row->cno' data-rel='tooltip' title='Delete'>
            <i class='ace-icon fa fa-trash-o bigger-130'></i>
         </a>";
           }
           echo "</div>
            </td>";
        echo "</tr>";
          //  echo  "<!-- Modal for Edit Invoice-->
          //  <div class='modal fade' id='editinv$row->cno' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
          //    <div class='modal-dialog' role='document' style='width:30%'>
          //      <div class='modal-content'>
          //        <div class='modal-header'>
          //          <h5 class='modal-title' id='exampleModalLabel'>Invoice $row->cno</h5>
          //        </div>
          //        <div class='modal-body'>
          //        <div class='row'>
          //            <span class = 'label label-primary' style='font-size:18px;height:31px;margin-left:22px;width:150px'>Cnote Number</span><input type='text' id='edit_cnotenumber' value=' $row->cno' readonly>
          //        </div>
          //        <div class='row' style='margin-top:10px'>
          //            <span class = 'label label-primary' style='font-size:18px;height:31px;margin-left:22px;width:150px'>Bill Wt</span><input type='text' id='edit_bilwt' value='".number_format($row->wt,3)."'>
          //        </div>
          //        <div class='row' style='margin-top:10px'>
          //            <span class = 'label label-primary' style='font-size:18px;height:31px;margin-left:22px;width:150px'>Bill Rate</span><input type='text' id='editrate' value='".round($row->amt)."'>
          //        </div>
          //        <div class='row' style='margin-top:10px'>
          //            <span class = 'label label-primary' style='font-size:18px;height:31px;margin-left:22px;width:150px'>Bill Mode</span><input type='text' id='edit_bilmode' value='$row->mode_code'>
          //        </div>
          //        <div class='row' style='margin-top:10px'>
          //            <span class = 'label label-primary' style='font-size:18px;height:31px;margin-left:22px;width:150px'>Bill Dest</span><input type='text' id='edit_bildest' value='$row->dest_code'>
          //        </div>
          //        </div>
          //        <div class='modal-footer'>
          //          <button type='button' class='btn btn-success edit_cnote' id='$row->cno'>Save</button>
          //          <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
          //        </div>
          //      </div>
          //    </div>
          //  </div>
          //  <!-- End -->  ";     
          
         }                    
           }
          }

     ?>

                   
                 </tbody>

            </table>

            
              </div>

        </div>
        <div style="border:1px solid #2F70B1;margin-top:6px;background:#fff;">
 
 <table id="formPOSpurchase" class="table" cellspacing="0" cellpadding="0" align="center" >	
<tbody>


<tr> 
<td>Total</td>
 <td>
 <input  id="total" name="total" value="<?=number_format($rowinv->grandamt,2); ?>" class="form-control input-sm"  type="text" readonly>
 </td>
 <td>FSC</td>
 <td>
 <input class="form-control input-sm " value="<?=number_format($rowinv->fsc,2); ?>"  name="fsc_hc" type="text" id="fsc_hc" readonly>
 </td>
  <td>F handling</td>
 <td>
 <input class="form-control input-sm " value="<?=number_format($rowinv->fhandcharge,2); ?>"  name="fsc_hc" type="text" id="fsc_hc" readonly>
 </td>
 <td>Invoice Amount</td>
 <td>
 <input class="form-control input-sm " value="<?=number_format($rowinv->invamt,2); ?>"  name="fsc_hc" type="text" id="fsc_hc" readonly>
 </td>
 <td>GST</td>
 <td><input  class="form-control input-sm " value="<?=number_format($rowinv->igst+$rowinv->cgst+$rowinv->sgst+$rowinv->STax,2); ?>" name="s_tax" type="text" id="s_tax"  readonly></td>
 <td>Discount</td>
  <td><input class="form-control input-sm" value="<?=number_format($rowinv->spl_discount,2);?>" name="discount" type="text" id="discount" readonly></td>
 <td>Net Amount</td>
 <td><input class="form-control input-sm " value="<?=number_format($rowinv->netamt,2); ?>" name="net_amt" type="text" id="net_amt" readonly></td>
 
</tr> 
</tbody></table>
<table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <tr>      
            <td>
                <p>Description</p><textarea  rows=3 id="description" name="description" readonly><?=$rowinv->description; ?></textarea>
            </td>         
        </tr>                     
        </tbody>
        </table>


   </div>
          </div>
          </div>
          <!-- Modal for Revice Invoice-->
            <div class="modal fade" id="invoice_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document" style="width:30%">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoice <?=$_GET['invno'];?></h5>
                  </div>
                  <div class="modal-body">
                  <div class="row">
                      <span class = "label label-primary" style="font-size:18px;height:31px;margin-left:22px;width:150px">Cnote Number</span><input type="text" id="rev_cnotenumber">
                  </div>
                  <div class="row" style="margin-top:10px">
                      <span class = "label label-primary" style="font-size:18px;height:31px;margin-left:22px;width:150px">Wt</span><input type="text" id="rev_bilwt" readonly>
                  </div>
                  <div class="row" style="margin-top:10px">
                      <span class = "label label-primary" style="font-size:18px;height:31px;margin-left:22px;width:150px"> Rate</span><input type="text" id="rev_bilrate" readonly>
                  </div>
                  <div class="row" style="margin-top:10px">
                      <span class = "label label-primary" style="font-size:18px;height:31px;margin-left:22px;width:150px"> Mode</span><input type="text" id="rev_bilmode" readonly>
                  </div>
                  <div class="row" style="margin-top:10px">
                      <span class = "label label-primary" style="font-size:18px;height:31px;margin-left:22px;width:150px"> Dest</span><input type="text" id="rev_bildest" readonly>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="addcnote">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- End -->
         
          </div>
</section>

<!-- inline scripts related to this page -->
<script>
$('.removeinvoice').click(function(){
    if(confirm("Are You sure Want to delete") == true)
        {
            var void_inv = $(this).attr('id');
            var comp_code = $('#comp_code').val();
            var inv_no = $('#inv_no').val();
            var inv_customer = $('#cust_code').val();
            if($('#br_code').val()=='TR'){
              var table = 'opdata';
            }
            else{
              var table = 'credit';
            }
            $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{void_inv:void_inv,inv_comp:comp_code,inv_no:inv_no,inv_customer:inv_customer,table},
            dataType:"text",
            success:function(data){
              $(this).closest('tr').remove();
            location.reload();
            }
            });
            
        }
        else{
            return false;
        }
});

</script>
<script>
//fetch cnote data for add
$('#rev_cnotenumber').keyup(function(){
  var revcno = $(this).val();
  var revcomp = $('#comp_code').val();
  $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{revcno:revcno,revcomp:revcomp},
            dataType:"text",
            success:function(datum){
              var data = datum.split(",");
              $('#rev_bilwt').val(data[0]);
              $('#rev_bilvwt').val(data[1]);
              $('#rev_bilrate').val(data[2]);
              $('#rev_bilmode').val(data[3]);
              $('#rev_bildest').val(data[4]);
            }
            });
});
</script>
<script>
$('#addcnote').click(function(){
  var revcno = $('#rev_cnotenumber').val();
  var revcomp = $('#comp_code').val();
  var revinvno = $('#inv_no').val();
  var rev_customer = $('#cust_code').val();
  var rev_bilrate = $('#rev_bilrate').val();
  if(revcno.trim()!='' && $('#rev_bilrate').val().trim()!='' && $('#rev_bilmode').val().trim()!='' && $('#rev_bildest').val().trim()!='')
  {
  $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{savecno:revcno,savecomp:revcomp,revinvno:revinvno,rev_customer:rev_customer,rev_bilrate:rev_bilrate},
            dataType:"text",
            success:function(data){
              location.reload();
               }
            }); 
  }
  else{
    alert('Please Enter Valid Cnote');
  }
});
</script>
<!-- <script>
$('.edit_cnote').click(function(){
  alert($(this).parent().closest('#edit_cnotenumber').val());
});
</script> -->
<script>

//DataTable
  $(document).ready(function(){
        $('#data-table').DataTable();
    });
</script>

<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('cust_code').value = ""; 
    document.getElementById('inv_no').value = "";        
}  
</script>

<script>
  //update cnotedata
     $(document).on('click', '.edit_cnote', function(){ 
     var edit_cno = $(this).attr('id');
     var edit_stn = $('#comp_code').val();
     var edit_wt  = $(this).parent().parent().find('#edit_bilwt').val();
     var edit_rate  = $(this).parent().parent().find('#editrate').val();
     var edit_mode  = $(this).parent().parent().find('#edit_bilmode').val();
     var edit_destn  = $(this).parent().parent().find('#edit_bildest').val();
     $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{edit_cno:edit_cno,edit_stn:edit_stn,edit_wt:edit_wt,edit_rate:edit_rate,edit_mode:edit_mode,edit_destn:edit_destn},
            dataType:"text",
            success:function(data)
                {
              location.reload();
               }
            });
  });
</script>
<script>
  function invoicererun(){
    var re_invno = $('#inv_no').val();
    var re_stn = $('#comp_code').val();
    var re_cust = $('#cust_code').val();
    var re_user = $('#user_name').val();
    console.log(re_cust);
    $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{re_invno,re_stn,re_cust,re_user},
            dataType:"text",
            success:function(data)
            {
              console.log(data);
              location.reload();
            }
          });
  }

  function invoiceratererun(){
    swal('Invoice rate will be revised in 2 minutes')
    .then((value) => {
  location.href = "invoicelist";
});
    var ratere_invno = $('#inv_no').val();
    var ratere_stn = $('#comp_code').val();
    var ratere_cust = $('#cust_code').val();
    var ratere_user = $('#user_name').val();
    var revisecredent = 1;
    $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{ratere_invno,ratere_stn,ratere_cust,ratere_user,revisecredent},
            dataType:"text",
            success:function(data)
            {
              location.reload();
            }
          });
  }
</script>