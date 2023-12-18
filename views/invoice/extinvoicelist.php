<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'extinvoicelist';
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
                                    $stn_condition = "WHERE comp_code='$stationcode'";
                              }
                              else{
                                  $userstations = $datafetch['stncodes'];
                                  $stations = explode(',',$userstations);
                                  $stationcode = $stations[0];  
                                  $stn_condition = "WHERE comp_code='$stationcode'";   
                              }
?>
<style>
  .modal-header .close {
    margin-top: -19px;
}
.pay-radio{
  width:35px;
  height: 18px;
}
.pay-label{
  font-weight:bold;
}
.payment-data-tb tr td{
  line-height: 20px;
}
.border-top{
  border-top:1px solid gray;
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
                                        <a href="<?php echo __ROOT__ . 'extinvoicelist'; ?>">Invoice list</a>
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
                                  Cash Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Address Type updated successfully.
                                </div>";
                  }
                  if ( isset($_GET['error']))
                  {
                      echo 
          
                  "<script> swal('Unknown Error occurred at invoice !!. Please re-run transaction'); </script>";
                  }
                   ?>
                
             </div>

            <form method="POST"  >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice Filters</p>
                    <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>Invoice"><button type="button" class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
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
                  <div class="col-12">
                        <input value="<?=$datafetch['br_code']?>" class="form-control border-form" name="br_code" type="text" id="br_code"  readonly>
                   </div>
                     </td>
                    
                  <td>Customer</td>
                  <td>
                  <div class="col-12">
                      <input value="<?=$datafetch['cust_code']?>" class="form-control border-form" name="cust_code" type="text" id="cust_code" readonly>
                    </div>
                      </td>

                    <td>Invoice</td>
                  <td>
                  <div class="col-12">
                      <input placeholder="" class="form-control border-form" name="inv_no" type="text" id="addr_type" >
                    </div>
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

                    <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterinvoicelist">
                    Apply <i class="fa fa-filter bigger-110"></i>                         
                    </button> 

                    <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()">
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button> 

                    </div>
                    </table> 
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice List</p>
              <!-- <div class="align-right col-sm-11" style="margin-top:-33px;" id="print" style="position:absolute">
          <div class="dt-buttons"> 
               <button class="dt-button buttons-excel buttons-html5" aria-controls="data-table" type="submit"><span><img data-toggle="tooltip" title="Bulk Email" style="width:100%;" src="vendor/bootstrap/icons/gmailcopy.png"></span></button> 
               <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="data-table" type="button" onclick="printpdf()"><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/pdf.png"></span></button> 
          </div>
         </div> -->
              <div class="align-right" style="margin-top:-33px;display:flex;float:right" id="buttons">
              <button class="btn btn-success btn-minier" tabindex="0" aria-controls="data-table" data-toggle='modal' data-target='#bulkinvoicepayment' style="margin-right: 5px;" type="button" onclick="bulkpayment()"><i class="fa fa-inr" aria-hidden="true"></i> Bulk Pay
</button> 
          </div>
           </div>
           <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" onclick="($(this).is(':checked'))?$('.invchkbox').prop('checked', true):$('.invchkbox').prop('checked', false)"/>
                <span class="lbl"></span>
                </label>
                
              </th>
              <th>Invoice No</th>
              <th>Invoice Date</th>
              <th>Branch</th>
              <th>Customer</th>
              <th>Grand Amount</th>              
              <th>FSC</th>
              <th>Invoice Amount</th>
              <th>IGST</th>
              <th>CGST</th>
              <th>SGST</th>
              <th>Net Amount</th>
              <th>Amt Paid</th>
              <th>Amt Pending</th>
              <th>Action</th>
              </tr>
    </thead>
              <tbody>
                  <?php  error_reporting(0);
                              
                             if(isset($_POST['filterinvoicelist']))
                             {    
                                 $br_code              = $_POST['br_code'];
                                 $cust_code            = $_POST['cust_code'];
                                 $inv_no               = $_POST['inv_no'];
                                 $mon_th             = $_POST['mon_th']; 
                                 //$date_added           = $_POST['date_added']; 
             
                                 $conditions = array();
             
                                 if(! empty($br_code)) {
                                 $conditions[] = "br_code LIKE '$br_code%'";
                                 } 
                                 if(! empty($cust_code)) {
                                 $conditions[] = "cust_code LIKE '$cust_code%'";
                                 }
                                 if(! empty($inv_no)) {
                                 $conditions[] = "invno LIKE '$inv_no%'";
                                 }
                                 if(! empty($mon_th)) {
                                  $conditions[] = "YEAR(invdate) ='".explode('-',$mon_th)[0]."' AND MONTH(invdate) = '".explode('-',$mon_th)[1]."'";
                                  }
                                //  if(! empty($date_added)) {
                                //  $conditions[] = "date_added='$date_added'";
                                //  }
                                 if(!empty($stn_condition)) {
                                 $conditions[] = "comp_code='$stationcode'";
                                 }

                               //  $conditions[] = "datepart(year,invdate) = year(getdate())";

                            
                                 $conditons = implode(' AND ', $conditions);
             
                                 if (count($conditions) > 0) {
                                     $query = $db->query("SELECT * FROM invoice  WHERE  $conditons"); 
                                 }
                                 if (count($conditions) == 0) {
                                 $query = $db->query("SELECT * FROM invoice $stn_condition ORDER BY invdate,invno"); 
                             }
                             } 
                             else{   
                                  $extcustomer = $datafetch['cust_code'];
                               $query = $db->query("SELECT * FROM invoice $stn_condition AND cust_code='$extcustomer' AND datepart(mm,invdate) = month(getdate()) AND datepart(year,invdate) = year(getdate()) AND NOT status LIKE '%void' ORDER BY invdate,invno");
                             }
                             if($query->rowcount()==0)
                             {
                             echo "";
                             }
                             else{
                
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                       
                            $origDate = "$row->invdate";
                            $newDate = date("d-m-Y", strtotime($origDate));
                            
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' ".(($row->status=='Pending')?'':'disabled')." value='$row->invno' class='ace ".(($row->status=='Pending')?'invchkbox':'')."' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                           
                            
                            echo "<td>".$row->invno."</td>";
                            echo "<td>".$newDate."</td>";
                            echo "<td>".$row->br_code."</td>";
                            echo "<td>".$row->cust_code."</td>";
                            echo "<td>".round($row->grandamt)."</td>";
                            echo "<td>".round($row->fsc)."</td>";
                            echo "<td>".round($row->invamt)."</td>";
                            echo "<td>".round($row->igst)."</td>";
                            echo "<td>".round($row->cgst)."</td>";
                            echo "<td>".round($row->sgst)."</td>";
                            echo "<td>".round($row->netamt)."</td>";
                            echo "<td>".round($row->rcvdamt)."</td>";
                            echo "<td>".round($row->netamt-$row->rcvdamt)."</td>";
                            
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                              
                            <a class='btn btn-primary btn-minier' href='extinvoicedetail?invno=".$row->invno."' data-rel='tooltip' title='Edit'>
                             <i class='ace-icon fa fa-eye bigger-130'></i>
                            </a>
                      
                            <a class='btn btn- btn-minier' href='invoicestatement?invoicenumber=".$row->invno."'  target='_blank' data-rel='tooltip' title='Edit'>
                             <i class='ace-icon fa fa-print bigger-130'></i>
                            </a>";
                            if($row->status=='Pending'){
                            echo "<a  class='btn btn-success btn-minier bootbox-confirm' id='$row->invno' data-rel='tooltip' title='Pay Online' data-toggle='modal' onclick='payinvoice(this.id)' data-target='#invoicepayment'>
                               <i class='ace-icon fa fa-inr'></i> PayOnline
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
          <!--Single inv payment Modal -->
          <div class="modal fade" id="invoicepayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Payment Options</h5>

                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <table><tr>
                      <td><input type="radio" name="payment-type" value="PartPayment" onclick="if($(this).prop('checked')){ $('#pay-payable').removeAttr('readonly') }" class="pay-radio"></td><td> <label class="pay-label">Part Payment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
                      <td><input type="radio" name="payment-type" value="FullPayment" onclick="if($(this).prop('checked')){ $('#pay-payable').attr('readonly',true) }" class="pay-radio" checked></td><td> <label class="pay-label">Full Payment</label></td>
                        </tr>
                        </table>
                        <table class="payment-data-tb data-table" style="margin-top:50px;width:90%">
                          <tr><td><label class="pay-label">Cust code </label></td><td><input type="text" class="form-control border-form"  id="pay-custname"   readonly></td></tr>
                          <tr><td><label class="pay-label">Inv Date </label></td><td><input type="date" class="form-control border-form"   id="pay-invdate"    readonly></td></tr>
                          <tr><td><label class="pay-label">Invoice No </label></td><td><input type="text" class="form-control border-form" id="pay-invno"      readonly></td></tr>
                          <tr><td><label class="pay-label">Inv Amount</label></td><td><input type="text" class="form-control border-form"  id="pay-invamt"     readonly></td></tr>
                          <tr><td><label class="pay-label">Balance Amount</label></td><td><input type="text" class="form-control border-form"  id="pay-balance"     readonly></td></tr>
                          <tr><td><label class="pay-label">Payable Amount</label></td><td><input type="text" class="form-control border-form" id="pay-payable" readonly oninput="if(parseFloat($(this).val())>parseFloat($('#pay-balance').val())){ $('#amt-paying').html($('#pay-balance').val()); $(this).val($('#pay-balance').val()) } else{ $('#amt-paying').html($(this).val()) }"></td></tr>
                          <tr><td><label class="pay-label">Remarks</label></td><td><input type="text" class="form-control border-form" id="pay-remarks"></td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                <div style="float:left;font-size:22px" id="model-loading"></div>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" onclick="makepayment()" class="btn btn-primary">Pay ₹ <span id="amt-paying"></span></button>
                </div>
              </div>
            </div>
          </div>
          <!--Single inv payment Modal -->
          <!--Bulk inv payment Modal -->
          <div class="modal fade" id="bulkinvoicepayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Payment Options</h5>

                  <button type="button" class="close bulk-modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <div class="modal-contents">
                 <table><tr>
                      <td><input type="radio" name="bulkpayment-type" value="PartPayment" onclick="if($(this).prop('checked')){ $('#bulkpay-amtpayable').removeAttr('readonly') }" class="pay-radio"></td><td> <label class="pay-label">Part Payment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
                      <td><input type="radio" name="bulkpayment-type" value="FullPayment" onclick="if($(this).prop('checked')){ $('#bulkpay-amtpayable').attr('readonly',true) }" class="pay-radio" checked></td><td> <label class="pay-label">Full Payment</label></td>
                        </tr>
                        </table>
                          <hr>
                    <table id="bulkpayment-table" class="data-table" style="width:100%;margin-top:10px">
                      <thead>
                      <tr>
                        <th>InvNo</th>
                        <th>InvDate</th>
                        <th>Netamt</th>
                        <th>PaidAmt</th>
                        <th>PendingAmt</th>
                        </tr>
                        </thead>
                        <tbody id="bulkpayment-table-tbody">

                        </tbody>
                    </table>
                    <table class="payment-data-tb data-table" style="margin-top:50px;width:90%">
                          <tr><td><label class="pay-label">Cust code </label></td><td><input type="text" class="form-control border-form"  id="bulkpay-custname"   readonly></td></tr>
                          <tr><td><label class="pay-label">Amount Balance &#8377;</label></td><td><input type="text" class="form-control border-form"   id="bulkpay-balamt"    readonly></td></tr>
                          <tr><td><label class="pay-label">Amount Payable &#8377;</label></td><td><input type="text" class="form-control border-form" id="bulkpay-amtpayable"  oninput="if(parseFloat($(this).val())>parseFloat($('#bulkpay-balamt').val())){ $('#bulkamt-paying').html($('#bulkpay-balamt').val()); $(this).val($('#bulkpay-balamt').val()) } else{ $('#bulkamt-paying').html($(this).val()) }"    readonly></td></tr>
                          <tr><td><label class="pay-label">Remarks</label></td><td><input type="text" class="form-control border-form" id="bulkpay-remarks"></td></tr>

                        </table>
                </div>
                <div class="emptycontent">
                  <h3>Please Select Invoices to Pay</h3>
                </div>
                </div>
                <div class="modal-footer modal-contents">
                <div style="float:left;font-size:22px" id="model-loading"></div>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" onclick="makebulkpayment()" class="btn btn-primary">Pay ₹ <span id="bulkamt-paying"></span></button>
                </div>
              </div>
            </div>
          </div>
          <!--Bulk inv payment Modal -->

</section>

<!-- inline scripts related to this page -->
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
$(document).ready(function(){
  var d = new Date();

  var months = ['Index','January','February','March','April','May','June','July','August','September','October','November','December'];

   for(var i=1; i<months.length; i++){
    if(d.getMonth()+1==i){
    var selected = 'selected';
     }
     else{
       var selected = '';
     }
    $('#month').append("<option value="+i+" "+selected+">"+months[i]+"</option>"); 
   }
});
</script>
<script>
    $(document).on('click','#sendmail',function(){
      var invno = $(this).attr('name');
      var station = '<?=$stationcode?>';
      $.ajax({
            url:"<?php echo __ROOT__ ?>phpmail",
            method:"POST",
            data:{invno,station},
            dataType:"text",
            success:function(message)
            {
              alert(message);
            }
            });
    });
</script>
<!-- Set cookie for datatable search -->
<script>
$(document).on('keyup', ".dataTables_filter",function () {
    var collectioncookie = $('.dataTables_filter').find('input').val();
    sessionStorage.setItem("invoicelist", collectioncookie);
});
</script>
<script>
function opencookie(){
$('.dataTables_filter').find('input').val(sessionStorage.getItem("invoicelist").trim());
$('.dataTables_filter').find('input').keyup();
}
</script>
<!-- Set cookie for datatable search -->
<script>
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
    //var datas = id.split(",");
    //var deletetb = datas[0];
    //var deletecol = 'brid';
    var del_invno = id;
    var del_invstn = '<?=$stationcode?>';
    var del_inv_user = '<?=$user?>';
    $.ajax({
              url:"<?php echo __ROOT__ ?>invoicecontroller",
              method:"POST",
              data:{del_invno,del_invstn,del_inv_user},
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
<!-- Single Invoice payment -->
<script>
      function payinvoice(invno){
        $('#model-loading').html("<i class='fa fa-spinner'></i>");
        var de_invno = invno;
        var de_stn = '<?=$stationcode?>';
        var de_cust = "<?=$datafetch['cust_code']?>";
        $.ajax({
              url:"<?php echo __ROOT__ ?>invoicecontroller",
              method:"POST",
              data:{de_invno,de_stn,de_cust},
              dataType:"json",
              success:function(paymentdetails)
              {
                $('#pay-custname').val(paymentdetails.cust_code);
                $('#pay-invdate').val(paymentdetails.invdate);
                $('#pay-invno').val(paymentdetails.invno);
                $('#pay-invamt').val(parseFloat(paymentdetails.netamt));
                $('#pay-payable').val(parseFloat(paymentdetails.netamt)-parseFloat(paymentdetails.rcvdamt));
                $('#pay-balance').val(parseFloat(paymentdetails.netamt)-parseFloat(paymentdetails.rcvdamt));
                $('#amt-paying').html(parseFloat(paymentdetails.netamt)-parseFloat(paymentdetails.rcvdamt));
                console.log(paymentdetails.cust_code);
                $('')
                $('#model-loading').html("");
              }
      });
      }
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function makepayment(){
  console.log(parseFloat($('#pay-payable').val()));
    if(parseInt($('#pay-payable').val())>0){
        var totalAmount = $('#pay-payable').val();
        var rcptno = $('#pay-invno').val();

        var orderdata = {
        "receipt": rcptno,
        "amount": parseFloat(totalAmount)*100, // 2000 paise = INR 20
        "currency": "INR",
        "payment_capture" :1,
        }

        var sreq_cust = $('#cust_code').val();
        var sreq_company = '<?=$stationcode?>';


        $.ajax({
        url: "<?php echo __ROOT__ ?>invoice_payment_process",
        type: 'post',
        dataType: 'json',
        data: { orderdata,sreq_cust,sreq_company }, 
        success: function (options) {
            // options.notes.Quantity = $('#cnote_count').val();
            // options.notes.CnoteType = $('#cnote_serial').val();
         options.handler = function (response){
            verifypayment(response);
        };
        checkoutpayment(options);
        }
        });
    }
    else{
        swal('Please enter Payable Amount');
    }
  }

</script>
<script>
function checkoutpayment(options){
        var rzp1 = new Razorpay(options);
        rzp1.open();
        e.preventDefault();
}
</script>
<script>
    function verifypayment(verifypayment_response){
        verifypayment_response.amount = parseFloat($('#pay-payable').val())*100;
        verifypayment_response.comp_code = '<?=$stationcode?>';
        verifypayment_response.cust_code = $('#cust_code').val();
        verifypayment_response.invno = $('#pay-invno').val();
        verifypayment_response.collnremarks = $('#pay-remarks').val();
        verifypayment_response.user_name = $('#user_name').val();
        console.log(verifypayment_response);
        $.ajax({
        url: "<?php echo __ROOT__ ?>invoice_payment_process",
        type: 'post',
        dataType: 'text',
        data: {verifypayment_response}, 
        success: function (msg) {

        msg = JSON.parse(msg);

        console.log(msg.status);

          if(msg.status==200){
            swal("Payment Successfull");
            location.reload();

          }else{
            swal("Payment Failed")

          }
        }
        });
    }
</script>
<!-- Single Invoice payment -->

<!-- Bulk Invoice payment -->
<script>
  //get invoice data 
  var selectedInvoices = [];
  function bulkpayment(){
    $('#bulkpayment-table-tbody').html("");

    var selectedInvoices = [];
    var stationcde = '<?=$stationcode?>';
    var customercde ="<?=$datafetch['cust_code']?>";

    $('.invchkbox').each(function() 
    {
        if($(this).is(':checked'))
        selectedInvoices.push($(this).val());
    });
    if(selectedInvoices.length>0)
    {
      $('.modal-contents').css('display','');
      $('.emptycontent').css('display','none');

      $.ajax({
        url: "<?php echo __ROOT__ ?>invoicecontroller", 
        type: 'post',
        dataType: 'text',
        data: { stationcde,selectedInvoices,customercde }, 
        success: function (invdata) {
          netpayable = 0;
          invdata = JSON.parse(invdata);
          invdata.forEach((invoice) => {
              console.log(invoice.invno);
              netpayable = netpayable+parseFloat(invoice.balamt);
              var html = '<tr><td>'+invoice.invno+'</td><td>'+invoice.invdate+'</td><td>'+parseFloat(invoice.netamt)+'</td><td>'+parseFloat(invoice.rcvdamt)+'</td><td>'+parseFloat(invoice.balamt)+'</td></tr>';
              $('#bulkpayment-table-tbody').append(html);
          });
          $('#bulkpayment-table-tbody').append('<tr><td></td><td></td><td></td><th class="border-top">Total</th><td class="border-top">'+parseFloat(netpayable)+'</td></tr>');
          $('#bulkpay-custname').val(customercde);
          $('#bulkpay-balamt').val(netpayable);
          $('#bulkpay-amtpayable').val(netpayable);
          $('#bulkamt-paying').html(netpayable);
        }
        });
       // alert(selectedInvoices);
      }
      else{
        $('#bulkpay-amtpayable').val('0');
        $('#bulkamt-paying').html("");

        $('.modal-contents').css('display','none');
        $('.emptycontent').css('display','');
      }
  } 
</script>
<script>
function makebulkpayment(){
  console.log(parseFloat($('#bulkpay-amtpayable').val()));
    if(parseInt($('#bulkpay-amtpayable').val())>0){
        var totalAmount = $('#bulkpay-amtpayable').val();
        var rcptno = $('#bulkpay-custname').val();

        var orderdata = {
        "receipt": rcptno,
        "amount": parseFloat(totalAmount)*100, // 2000 paise = INR 20
        "currency": "INR",
        "payment_capture" :1,
        }

        var sreq_cust = $('#bulkpay-custname').val();
        var sreq_company = '<?=$stationcode?>';


        $.ajax({
        url: "<?php echo __ROOT__ ?>invoice_payment_process",
        type: 'post',
        dataType: 'json',
        data: { orderdata,sreq_cust,sreq_company }, 
        success: function (options) {
         options.handler = function (response){
            verifybulkpayment(response);
        };
        checkoutpayment(options);
        }
        });
    }
    else{
        swal('Please enter Payable Amount');
    }
  }

</script>
<script>
  var selectedInvoices2 = [];
    function verifybulkpayment(verifypayment_response){
      selectedInvoices2 = [];
        verifypayment_response.amount = parseFloat($('#bulkpay-amtpayable').val())*100;
        verifypayment_response.comp_code = '<?=$stationcode?>';
        verifypayment_response.cust_code = $('#bulkpay-custname').val();
        verifypayment_response.collnremarks = $('#bulkpay-remarks').val();
        verifypayment_response.user_name = $('#user_name').val();
        $('.invchkbox').each(function() 
        {
            if($(this).is(':checked'))
            selectedInvoices2.push($(this).val());
        });
        console.log(selectedInvoices2);
        $.ajax({
        url: "<?php echo __ROOT__ ?>invoice_payment_process",
        type: 'post',
        dataType: 'text',
        data: {verifybulkpayment_response:verifypayment_response,selectedInvoices2}, 
        success: function (msg) {
          msg = JSON.parse(msg);
          if(msg.status==200){
            swal("Payment Successfull");
            location.reload();
          }
          else{
            swal("Payment Failed")
          }
        }
        });
    }
</script>
<!-- Bulk Invoice payment -->