<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'invoicelist';
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
    $module = 'invoicedelete';
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
<style>
.tooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
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
                                        <a href="<?php echo __ROOT__ . 'outstandinginvoicelist'; ?>">O/s Invoice list</a>
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
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Outstanding Invoice Filters</p>
                    <div class="align-right" style="margin-top:-35px;">

       
        <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" data-rel='tooltip' title='Close' class="btn btn-danger btnattr" >
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
                        <input placeholder="" class="form-control border-form" name="br_code" type="text" id="br_code"  autofocus>
                   </div>
                     </td>
                    
                  <td>Customer</td>
                  <td>
                  <div class="col-12">
                      <input placeholder="" class="form-control border-form" name="cust_code" type="text" id="cust_code" >
                    </div>
                      </td>

                    <td>Invoice</td>
                  <td>
                  <div class="col-12">
                      <input placeholder="" class="form-control border-form" name="inv_no" type="text" id="addr_type" >
                    </div>
                      </td>

                      <!-- <td>Date</td>
                  <td>
                  <div class="col-12">
                      <input placeholder="" class="form-control border-form today" name="date_added" type="date" id="date_added" >
                    </div>
                      </td> -->
                      <!-- <td>Month</td>
                      <td>
                      <input class="form-control border-form <?php if(isset($_POST['mon_th'])){ }else{echo 'this-month'; } ?>" name="mon_th" type="month" id="mon_th" value="<?php if(isset($_POST['mon_th'])){ echo $_POST['mon_th']; } ?>">
                      </td>                  -->
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Outstanding Invoice List</p>
              <div class="align-right" style="margin-top:-33px;width:95%;float:right;margin-right:68px" id="print">
          <div class="dt-buttons"> 
               <!-- <button class="btn btn-primary btn-minier bootbox-confirm " aria-controls="data-table" data-rel='tooltip' title='Send Bulk email' onclick="sendbulkemail()" type="button"><i class='fa fa-envelope' aria-hidden='true'></i></button>  -->
               <button class="btn btn-danger btn-minier bootbox-confirm " aria-controls="data-table" data-rel='tooltip' title='Print Bulk Invoices' onclick="bulkprintinvoice()" type="button"><i class='fa fa-print' aria-hidden='true'></i></button> 
               <!-- <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="data-table" type="button" onclick="printpdf()"><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/pdf.png"></span></button>  -->
          </div>
         </div>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
            </div>
               <!-- pagination search  -->


           <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text"></div>
           </div>
           <!-- pagination search  -->
           <table id="data-tdable" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace checkall-invoicelist" />
                <span class="lbl"></span>
                </label>
                
              </th>
              <th>Branch</th>
              <th>Branch Name</th>
              <th>Customer</th>
              <th>Customer Name</th>
              <th>Pending Invoices</th>              
              <th>Pending Value</th>
              <th>Action</th>
              </tr>
    </thead>
              <tbody id="datatable-tbody">
                  <?php  
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
                              $conditions = array();
                             if(isset($_POST['filterinvoicelist']))
                             {    
                                 $br_code              = $_POST['br_code'];
                                 $cust_code            = $_POST['cust_code'];
                                 $inv_no               = $_POST['inv_no'];
                                 //$mon_th             = $_POST['mon_th']; 
                                 //$date_added           = $_POST['date_added']; 
             
                           
             
                                 if(! empty($br_code)) {
                                 $conditions[] = "br_code LIKE '$br_code%'";
                                 }
                                 if(! empty($cust_code)) {
                                 $conditions[] = "cust_code LIKE '$cust_code%'";
                                 }
                                 if(! empty($inv_no)) {
                                 $conditions[] = "invno LIKE '$inv_no%'";
                                 }
                                //  if(! empty($mon_th)) {
                                //   $conditions[] = "YEAR(invdate) ='".explode('-',$mon_th)[0]."' AND MONTH(invdate) = '".explode('-',$mon_th)[1]."'";
                                //   }
                                 //  if(! empty($date_added)) {
                                 //  $conditions[] = "date_added='$date_added'";
                                 //  }
                                 if(!empty($stn_condition)) {
                                 $conditions[] = "comp_code='$stationcode'";
                                 }

                                 //$conditions[] = "datepart(year,invdate) = year(getdate())";

                                 $conditons = implode(' AND ', $conditions);
             
                                 if (count($conditions) > 0) {
                                     $query = $db->query("SELECT comp_code,br_code,cust_code,count(*) pendingcount,sum(netamt-ISNULL(rcvdamt,0)) totalpending,(SELECT TOP 1 br_name FROM branch WHERE br_code=invoice.br_code) AS branchname,(SELECT TOP 1 cust_name FROM customer WHERE cust_code=invoice.cust_code AND comp_code=invoice.comp_code) AS customeername,(SELECT TOP 1 cust_email FROM customer WHERE comp_code=invoice.comp_code AND cust_code=invoice.cust_code) AS custemail FROM invoice  WHERE  $conditons  AND NOT status LIKE '%void' AND status='Pending' GROUP BY comp_code,br_code,cust_code ORDER BY cust_code ASC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                                 }
                                 if (count($conditions) == 0) {
                                 $query = $db->query("SELECT comp_code,br_code,cust_code,count(*) pendingcount,sum(netamt-ISNULL(rcvdamt,0)) totalpending,(SELECT TOP 1 br_name FROM branch WHERE br_code=invoice.br_code) AS branchname,(SELECT TOP 1 cust_name FROM customer WHERE cust_code=invoice.cust_code AND comp_code=invoice.comp_code) AS customeername,(SELECT TOP 1 cust_email FROM customer WHERE comp_code=invoice.comp_code AND cust_code=invoice.cust_code) AS custemail FROM invoice $stn_condition  AND NOT status LIKE '%void' AND status='Pending' GROUP BY comp_code,br_code,cust_code ORDER BY cust_code ASC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                             }
                             } 
                             else{   
                              $conditions[] = "comp_code='$stationcode' AND NOT status LIKE '%void' AND status='Pending'";
                              $conditons = implode(' AND ', $conditions);
                                  
                              
                           
                              try
                              {
                                $query = $db->query("SELECT comp_code,br_code,cust_code,count(*) pendingcount,sum(netamt-ISNULL(rcvdamt,0)) totalpending,(SELECT TOP 1 br_name FROM branch WHERE br_code=invoice.br_code) AS branchname,(SELECT TOP 1 cust_name FROM customer WHERE cust_code=invoice.cust_code AND comp_code=invoice.comp_code) AS customeername,(SELECT TOP 1 cust_email FROM customer WHERE comp_code=invoice.comp_code AND cust_code=invoice.cust_code) AS custemail FROM invoice WHERE $conditons GROUP BY comp_code,br_code,cust_code  ORDER BY cust_code OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");
                              }
                              
                              catch(PDOException $e){
                               echo json_encode($e);
                              }
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
                                       <input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist' value='$row->cust_code'/>
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                           
                            
                            echo "<td>".$row->br_code."</td>";
                            echo "<td>".$row->branchname."</td>";
                            echo "<td>".$row->cust_code."</td>";
                            echo "<td>".$row->customeername."</td>";
                            echo "<td>".$row->pendingcount."</td>";
                            echo "<td>".number_format($row->totalpending,2)."</td>";
                            
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                              
                            <a class='btn btn-success btn-minier' href='outstandinginvoice?cust_code=$row->cust_code' data-rel='tooltip' title='View'>
                             <i class='ace-icon fa fa-eye bigger-130'></i>
                            </a>
                           
                          
                        ";
                        if(!empty($row->custemail)){
                          echo "<a  class='btn btn-primary btn-minier bootbox-confirm single-mail-button' id='sendmail' name='$row->cust_code' data-rel='tooltip' title='u'>
                          <i class='fa fa-envelope' aria-hidden='true'></i>
                          </a>";
                       
                          }
                          echo "<a class='btn btn-success btn-minier' href='outstandinginvoicestatement?invoicenumber=".$row->cust_code."'  target='_blank' data-rel='tooltip' title='Print'>
                          <i class='ace-icon fa fa-print bigger-130'></i>
                         </a>";

                          echo "</div>
                          <div class='hidden-md hidden-lg'>
                            <div class='inline pos-rel'>
                             <button class='btn btn-minier btn-yellow dropdown-toggle' data-toggle='dropdown' data-position='auto'>
                              <i class='ace-icon fa fa-caret-down icon-only bigger-120'></i>
                             </button>
                             <ul class='dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close'>
                               <li>
                                  <a  class='tooltip-success' data-rel='tooltip' title='View'>
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
                <!-- pagination -->
                <div class="table-paginate-btns">
            <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
     
          
          <?php $noofpages = $db->query("SELECT DISTINCT COUNT(*) OVER () as pages FROM invoice  WHERE $conditons GROUP BY comp_code,br_code,cust_code")->fetch(PDO::FETCH_OBJ);
                for($i=1;$i<=ceil($noofpages->pages / 10);$i++){
                  //pagination control
                  if(ceil($noofpages->pages / 10)>5){
                    //dot property
                    if($i==3){
                      $leftdot = '...';
                    }
                    else{
                      $leftdot = '';
                    }
                    if($i==ceil($noofpages->pages / 10)-1){
                      $rightdot = '...';
                    }
                    else{
                      $rightdot = '';
                    }
                    //end dot property
                    //number btn display property
                    if($i==1||$i==2||$i==ceil($noofpages->pages / 10)||$i==round(ceil($noofpages->pages / 10)/2)||$i==round(ceil($noofpages->pages / 10)/2)-1||$i==round(ceil($noofpages->pages / 10)/2+1)){
                      $style = "style='display:'";
                    }else{
                      $style = "style='display:none'";
                    }
                    //end number btn display property
                  }
                  else{
                    $leftdot = '';
                    $rightdot = '';
                    $style= '';
                  }
                  //end pagination control
                  echo "$leftdot<input type='button' $style class='btn btn-primary paginate-btn-nums' value='$i' id='$i'>$rightdot";
                }
              ?>
              <button type="button" class="btn btn-primary paginate-btn-nums" id="next" value="2">Next</button>
            </div>
            <!-- pagination -->
              </div>

        </div>
        
          </div>
          </div>
          </div>
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
//send single Email
    $(document).on('click','#sendmail',function(){
      $(this).html("<i class='fa fa-spinner'></i>");

     var cust_code = $(this).attr('name');
      var station = '<?=$stationcode?>';
      var mail_user = '<?=$user?>';
      $.ajax({
            url:"<?php echo __ROOT__ ?>outstandmail",
            method:"POST",
            data:{cust_code,station,mail_user},
            dataType:"text",
            success:function(message)
            {
              console.log(station);
              $('.single-mail-button').html("<i class='fa fa-envelope' aria-hidden='true'></i>");

              // if(message=='0'){
              //   swal("OOPS!", "Email Not Sent!", "danger");
              // }
              // else{
                swal(message);
              //}
            }
            });
    });
</script>
<script>
  function sendbulkemail(){
    $('.sendmail').each(function(){
      $(this).html("<i class='fa fa-spinner'></i>");
      var rep_cust_code = $(this).attr('name');
      var rep_station = '<?=$stationcode?>';
      var rep_mail_user = '<?=$user?>';
      $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{rep_cust_code,rep_station,rep_mail_user},
            dataType:"text",
            success:function(message)
            {
              $('.single-mail-button').html("<i class='fa fa-envelope' aria-hidden='true'></i>");

              if(message=='0'){
                swal("OOPS!", "Email Not Sent!", "danger");
              }
              else{
              swal("Email has been Sent Successfully");
              }
            }
            });
    });
  }
</script>

<script>
var bulkprintinv_array = [];

function setallinvnoinarray(){
  var newarray = $(".checkbox-invoicelist").map(function(){ return $(this).val() ;}).get();
  bulkprintinv_array = newarray;
  console.log(bulkprintinv_array);
}
function removeallinvnoinarray(){
  bulkprintinv_array = [];
  console.log(bulkprintinv_array);
}

function bulkprintinvoice(){
  var invno_json = bulkprintinv_array;
  var print_station = '<?=$stationcode?>';
  //alert(print_station);
if(bulkprintinv_array.length==0){
  swal("Please Select Invoices to print");
}
else{
  window.open('<?php echo __ROOT__ ?>bulkoutstaninginvoiceprint?print_station='+print_station+'&invno_json='+invno_json, '_blank');
}
}
</script>
<script>
$(document).on('click','.checkbox-invoicelist',function(){
  if($(this).is(':checked')){
    $(this).closest('tr').find('#sendmail').addClass('sendmail');
    bulkprintinv_array.push($(this).val());
    console.log(bulkprintinv_array);
  }
  else{
    var index = bulkprintinv_array.indexOf($(this).val());
    if (index !== -1) {
      bulkprintinv_array.splice(index, 1);
    }
    $(this).closest('tr').find('#sendmail').removeClass('sendmail');
    console.log(bulkprintinv_array);
  }
});
</script>
<script> 

$(document).on('click','.checkall-invoicelist',function(){
  if($(this).is(':checked')){
    //bulkprintinv_array.push('test');
    $('.single-mail-button').addClass('sendmail');
    $('.checkbox-invoicelist').prop('checked', true);
    setallinvnoinarray();
    //get invno in array
  }
  else{
    $('.single-mail-button').removeClass('sendmail');
    $('.checkbox-invoicelist').prop('checked', false);
    removeallinvnoinarray();
  }
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








<script>
  $('.paginate-btn-nums').click(function(){
    //console.log("dsds");

    var inc = parseInt($(this).val())+1;
            var dec = parseInt($(this).val())-1;
            var curt = parseInt($(this).val());
            var end = parseInt(<?php echo ceil($noofpages->pages / 10); ?>);
            if($(this).val()==0||$(this).val()>end){
              process.end;
            }
    $('#datatable-tbody').css('opacity','0.5');
    $('.paginate-btn-nums').removeClass('active');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT br_code,cust_code,count(*) pendingcount,sum(netamt-ISNULL(rcvdamt,0)) totalpending,(SELECT TOP 1 br_name FROM branch WHERE br_code=invoice.br_code) AS branchname,(SELECT TOP 1 cust_name FROM customer WHERE cust_code=invoice.cust_code AND comp_code=invoice.comp_code) AS customeername,(SELECT TOP 1 cust_email FROM customer WHERE comp_code=invoice.comp_code AND cust_code=invoice.cust_code) AS custemail FROM invoice WHERE <?=$conditons;?> GROUP BY comp_code,br_code,cust_code ORDER BY cust_code DESC";
       console.log(dtbquery);                   
 
      var dtbcolumns = 'br_code,branchname,cust_code,customeername,pendingcount,totalpending,custemail';
    
      var dtbpageno = ($(this).val()-1)*10;
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns},
            dataType:"json",
            success:function(datum){
             console.log(datum);
            $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var datacount = Object.keys(datum).length;
      
            var html='';
            for(var i=1;i<=datacount;i++){
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' value='"+datum[i]['cust_code']+"' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['branchname']+"</td>";
              html += "<td>"+datum[i]['cust_code']+"</td>";
              html += "<td>"+datum[i]['customeername']+"</td>";
              html += "<td>"+datum[i]['pendingcount']+"</td>";
              html += "<td>"+parseFloat(datum[i]['totalpending']).toFixed(2)+"</td>";
          
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
             
              html += "<a class='btn btn-success btn-minier' href='outstandinginvoice?cust_code="+datum[i]['cust_code']+"' data-rel='tooltip' title='View'><i class='ace-icon fa fa-eye bigger-130'></i></a>";                           
             
              if(datum[i]['custemail'].length > 0){
                html += "<a class='btn btn-primary btn-minier bootbox-confirm single-mail-button' id='sendmail' name='"+datum[i]['cust_code']+"' data-rel='tooltip' title='U'><i class='fa fa-envelope' aria-hidden='true'></i></a>";                           
              }
              html += "<a class='btn btn-success btn-minier' href='outstandinginvoicestatement?invoicenumber="+datum[i]['cust_code']+"'  target='_blank' data-rel='tooltip' title='Print'><i class='ace-icon fa fa-print bigger-130'></i></a>";

              //action
              html += "</div></td></tr>";
            }




            






            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
            }

            
          }); 
          //pagination dynamic buttons
            if(end>5){
              $('#'+curt).addClass('active');
              $('.paginate-btn-nums').css('display','none');
              $('#'+curt).css('display','');
              $('#1').css('display','');
              $('#'+inc).css('display','');
              $('#'+dec).css('display','');
              // $('#'+dec).css('display','');
              $('#'+end).css('display','');
              $('#'+end).css('display','');
              $('#prev').css('display','');
              $('#prev').val(dec);
              $('#next').css('display','');
              $('#next').val(inc);
            }
            else{
              $('#'+curt).addClass('active');
              $('#prev').val(dec);
              $('#next').val(inc);
            }
          //end pagination dynamic buttons
  });
</script>





<script>
  $('.table-search-right-text').keyup(function(event){
    // event.preventDefault();
    var fieldvalue = $(this).val();
      sessionStorage.setItem("outstandinginvoicelist", fieldvalue);
      
      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT br_code,cust_code,count(*) pendingcount,sum(netamt-ISNULL(rcvdamt,0)) totalpending,(SELECT TOP 1 br_name FROM branch WHERE br_code=invoice.br_code) AS branchname,(SELECT TOP 1 cust_name FROM customer WHERE cust_code=invoice.cust_code AND comp_code=invoice.comp_code) AS customeername,(SELECT TOP 1 cust_email FROM customer WHERE comp_code=invoice.comp_code AND cust_code=invoice.cust_code) AS custemail FROM invoice WHERE <?=$conditons;?> AND (cust_code like '%"+fieldvalue+"%' OR br_code LIKE '%"+fieldvalue+"%') GROUP BY comp_code,br_code,cust_code  ORDER BY cust_code DESC";
     console.log(dtbquery);
      var dtbcolumns = 'br_code,branchname,cust_code,customeername,pendingcount,totalpending,custemail';
      var dtbpageno = '0';
      console.log(dtbquery);
     
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns},
            dataType:"json",
            success:function(datum){
              console.log(datum);
            $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var datacount = Object.keys(datum).length;
            var html='';
            for(var i=1;i<=datacount;i++){
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' value='"+datum[i]['cust_code']+"' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['branchname']+"</td>";
              html += "<td>"+datum[i]['cust_code']+"</td>";
              html += "<td>"+datum[i]['customeername']+"</td>";
              html += "<td>"+datum[i]['pendingcount']+"</td>";
              html += "<td>"+parseFloat(datum[i]['totalpending']).toFixed(2)+"</td>";
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              html += "<a class='btn btn-success btn-minier' href='outstandinginvoice?cust_code="+datum[i]['cust_code']+"' data-rel='tooltip' title='View'><i class='ace-icon fa fa-eye bigger-130'></i></a>";                           
             
             if(datum[i]['custemail'].length > 0){
               html += "<a class='btn btn-primary btn-minier bootbox-confirm single-mail-button' id='sendmail' name='"+datum[i]['cust_code']+"' data-rel='tooltip' title='U'><i class='fa fa-envelope' aria-hidden='true'></i></a>";                           
             }
             html += "<a class='btn btn-success btn-minier' href='outstandinginvoicestatement?invoicenumber="+datum[i]['cust_code']+"'  target='_blank' data-rel='tooltip' title='Print'><i class='ace-icon fa fa-print bigger-130'></i></a>";

              //action
              html += "</div></td></tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
            }

            
          }); 
       });

      

</script>