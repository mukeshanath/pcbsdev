<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'cashreceiptlist';
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
    $module = 'cashreceiptdelete';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;
  
    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'swal(`You Dont Have Permission to Delete this Record!`)';
        }
        else{
            return "deleterecord(this.id)";
        }
  }
?>
<style>
.tooltip 
{
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext
 {
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
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cash Receipt Filters</p>
                    <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>cashreceipt"><button type="button"  data-rel='tooltip' title='Raise Invoice' class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a>

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
                        <input placeholder="" class="form-control border-form" name="br_code" type="text" id="br_code"  autofocus value="<?=$_POST['br_code'];?>">
                   </div>
                     </td>
                    
                  <td>Customer</td>
                  <td>
                  <div class="col-12">
                      <input placeholder="" class="form-control border-form" name="cust_name" type="text" id="cust_name" value="<?=$_POST['cust_name'];?>">
                    </div>
                      </td>

                    <td>Cash Receipt No</td>
                  <td>
                  <div class="col-12">
                      <input placeholder="" class="form-control border-form" name="cash_receipt_number" type="text" id="cash_receipt_number" value="<?=$_POST['cash_receipt_number'];?>">
                    </div>
                      </td>

                      <!-- <td>Date</td>
                  <td>
                  <div class="col-12">
                      <input placeholder="" class="form-control border-form today" name="date_added" type="date" id="date_added" >
                    </div>
                      </td> -->
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
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cash Receipt List</p>
              <div class="align-right" style="margin-top:-33px;width:95%;float:right;margin-right:68px" id="print">
          <div class="dt-buttons"> 
               <button class="btn btn-primary btn-minier bootbox-confirm " aria-controls="data-table" data-rel='tooltip' title='Send Bulk email' onclick="sendbulkemail()" type="button"><i class='fa fa-envelope' aria-hidden='true'></i></button> 
               <button class="btn btn-danger btn-minier bootbox-confirm " aria-controls="data-table" data-rel='tooltip' title='Print Bulk Invoices' onclick="bulkprintinvoice()" type="button"><i class='fa fa-print' aria-hidden='true'></i></button> 
               <!-- <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="data-table" type="button" onclick="printpdf()"><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/pdf.png"></span></button>  -->
          </div>
         </div>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
           </div>

           <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text" onkeyup="customerlistsearch()"></div>
           </div>
           <table _id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace checkall-invoicelist" />
                <span class="lbl"></span>
                </label>
                
              </th>
          
              <th>Cash Receipt No</th>
              <th>Cash Receipt Date</th>
              <th>Branch</th>
              <th>Customer Name</th>
              <th>CC Code</th>              
              <th>Cnote Count</th>
              <th>Gstin</th>
              <th>From Date</th>
              <th>To Date</th>
              <th>Status</th>
              <th>Status</th>
              </tr>
    </thead>
              <tbody id="datatable-tbody">
                  <?php  //error_reporting(0);
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
                              $conditions[] = "i.stncode='$stationcode'";

                             if(isset($_POST['filterinvoicelist']))
                             {    
                                 $br_code              = $_POST['br_code'];
                                 $cust_name            = $_POST['cust_name'];
                                 $inv_no               = $_POST['cash_receipt_number'];
                                 $mon_th             = $_POST['mon_th']; 
                                 //$date_added           = $_POST['date_added']; 
             
             
                                 if(! empty($br_code)) {
                                 $conditions[] = "i.br_code LIKE '$br_code%'";
                                 } 
                                 if(! empty($cust_code)) {
                                 $conditions[] = "i.cust_name LIKE '$cust_code%'";
                                 }
                                 if(! empty($inv_no)) {
                                 $conditions[] = "i.cash_receipt_no LIKE '$inv_no%'";
                                 }
                                 if(! empty($mon_th)) {
                                  $conditions[] = "YEAR(i.cash_receipt_date) ='".explode('-',$mon_th)[0]."' AND MONTH(i.cash_receipt_date) = '".explode('-',$mon_th)[1]."'";
                                  }
                               
                            
                                 $conditons = implode(' AND ', $conditions);
             
                                 if (count($conditions) > 0) {
                                     $query = $db->query("SELECT * FROM cash_receipt i WHERE  $conditons AND cr_status='CR-Invoiced' ORDER BY cash_receipt_no ASC  OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                                  
                                 }
                                 if (count($conditions) == 0) {
                                 $query = $db->query("SELECT * FROM cash_receipt i $stn_condition AND cr_status='CR-Invoiced' ORDER BY cash_receipt_no ASC  OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                                
                             }
                             } 
                             else{   
                              $conditions[] = "datepart(mm,cash_receipt_date) = month(getdate()) AND datepart(year,cash_receipt_date) = year(getdate())";

                              $conditons = implode(' AND ', $conditions);

                              $query = $db->query("SELECT * FROM cash_receipt i $stn_condition  AND datepart(mm,cash_receipt_date) = month(getdate()) AND datepart(year,cash_receipt_date) = year(getdate()) AND cr_status='CR-Invoiced' ORDER BY cash_receipt_no OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY");
                               
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
                                    <input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist' value='$row->cash_receipt_no'/>
                                    <span class='lbl'></span>
                                     </label>
                                  </td>";

                            echo "<td>".$row->cash_receipt_no."</td>";
                            echo "<td>".date("d-M-Y",strtotime($row->cash_receipt_date))."</td>"; 
                            echo "<td>".$row->br_code."</td>";
                            echo "<td>".$row->cust_name."</td>";
                            echo "<td>".$row->cc_code."</td>";
                            echo "<td>".$row->cash_cnote_count."</td>";
                            echo "<td>".$row->gstin."</td>";
                            echo "<td>".date("d-M-Y",strtotime($row->frm_date))."</td>";
                            echo "<td>".date("d-M-Y",strtotime($row->to_date))."</td>"; 
                            echo "<td>".$row->cr_status."</td>";
                            
                            echo "<td><a class='btn btn-success btn-minier' href='cashreceiptstatement?cash_receipt_no=".$row->cash_receipt_no."'  target='_blank' data-rel='tooltip' title='Print'>
                            <i class='ace-icon fa fa-print bigger-130'></i>
                           </a>
                           <a  class='btn btn-danger btn-minier bootbox-confirm' id='$row->cash_receipt_no' data-rel='tooltip' title='Delete' onclick='".delete($db,$user)."'>
                           <i class='ace-icon fa fa-trash-o bigger-130'></i>
                        </a>
                           </td>";
                           echo "</tr>";
                          } 
                        //  echo '<button style="font-size:12px"><a href="invoicestatement?invoicenumber='.$row->invno.'" download="invoice">Button <i class="fa fa-download"></i></a></button>';

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
                            
                                  
                           
                          }                      
                          
                          
                      ?>

                   
                 </tbody>
            </table>
            <!-- pagination -->
            <div class="table-paginate-btns">
              <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM cash_receipt i WHERE $conditons")->fetch(PDO::FETCH_OBJ);
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
    var del_invno = id;
    var del_invstn = '<?=$stationcode?>';
    var del_inv_user = '<?=$user?>';
    $.ajax({
              url:"<?php echo __ROOT__ ?>cashreceipt",
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
<!-- custom datatable -->

<script>
  $('.paginate-btn-nums').click(function(){
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
      var dtbquery = "SELECT cash_receipt_no,cash_receipt_date,br_code,cust_name,cc_code,cash_cnote_count,gstin,frm_date,to_date,cr_status FROM cash_receipt i WHERE  <?=$conditons;?> AND cr_status='CR-Invoiced' ORDER BY cash_receipt_no";
      var dtbcolumns = 'cash_receipt_no,cash_receipt_date,br_code,cust_name,cc_code,cash_cnote_count,gstin,frm_date,to_date,cr_status';
      var dtbpageno = ($(this).val()-1)*10;
      var sessionuser = $('#user_name').val();
      console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,action: 'cashllish'},
            dataType:"json",
            success:function(datum){
              $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var invoicedatacount = Object.keys(datum).length;
            var html='';
            const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

            for(var i=1;i<=invoicedatacount;i++){
              var  tabledate = datum[i]['cash_receipt_date'];
               let current_datetime = new Date(tabledate)
           let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear() 

           var  tabledate = datum[i]['frm_date'];
               let current_datetime1 = new Date(tabledate)
           let formatted_date1 = current_datetime1.getDate() + "-" + months[current_datetime1.getMonth()] + "-" + current_datetime1.getFullYear() 

           var  tabledate = datum[i]['to_date'];
               let current_datetime2 = new Date(tabledate)
           let formatted_date2 = current_datetime2.getDate() + "-" + months[current_datetime2.getMonth()] + "-" + current_datetime2.getFullYear() 
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist' value='"+datum[i]['cash_receipt_no']+"'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['cash_receipt_no']+"</td>";
              html += "<td>"+formatted_date+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cust_name']+"</td>";
              html += "<td>"+datum[i]['cc_code']+"</td>";
              html += "<td>"+datum[i]['cash_cnote_count']+"</td>";
              html += "<td>"+datum[i]['gstin']+"</td>";
              html += "<td>"+formatted_date1+"</td>";
              html += "<td>"+formatted_date2+"</td>";
              html += "<td>"+datum[i]['cr_status']+"</td>";
          
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              html += "<a class='btn btn-success btn-minier' href='cashreceiptstatement?cash_receipt_no="+datum[i]['cash_receipt_no']+"'  target='_blank' data-rel='tooltip' title='Print'><i class='ace-icon fa fa-print bigger-130'></i></a>";

              html += "<a  class='btn btn-danger btn-minier bootbox-confirm' id='"+datum[i]['cash_receipt_no']+"' data-rel='tooltip' title='Delete' onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
              html += "</div></td>";
              //action
              html += "</div></td>";
              html += "</tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            removeallinvnoinarray();
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
//datatable search
  function customerlistsearch(){
    
      var fieldvalue = $('.table-search-right-text').val();
      sessionStorage.setItem("customerlist", fieldvalue);
      var dtbquery = "SELECT cash_receipt_no,cash_receipt_date,br_code,cust_name,cc_code,cash_cnote_count,gstin,frm_date,to_date,cr_status FROM cash_receipt i WHERE <?=$conditons;?> AND cr_status='CR-Invoiced' AND (cash_receipt_no like '%"+fieldvalue+"%' OR gstin LIKE '%"+fieldvalue+"%') ORDER BY cash_receipt_no";
      var dtbcolumns = 'cash_receipt_no,cash_receipt_date,br_code,cust_name,cc_code,cash_cnote_count,gstin,frm_date,to_date,cr_status';
      var dtbpageno = '0';
      var sessionuser = $('#user_name').val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,action: 'cashllish'},
            dataType:"json",
            success:function(datum){
              $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var invoicedatacount = Object.keys(datum).length;
            var html='';
            for(var i=1;i<=invoicedatacount;i++){
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist' value='"+datum[i]['cash_receipt_no']+"'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['cash_receipt_no']+"</td>";
              html += "<td>"+datum[i]['cash_receipt_date']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cust_name']+"</td>";
              html += "<td>"+datum[i]['cc_code']+"</td>";
              html += "<td>"+datum[i]['cash_cnote_count']+"</td>";
              html += "<td>"+datum[i]['gstin']+"</td>";
              html += "<td>"+datum[i]['frm_date']+"</td>";
              html += "<td>"+datum[i]['to_date']+"</td>";
              html += "<td>"+datum[i]['cr_status']+"</td>";
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              html += "<a class='btn btn-success btn-minier' href='invoicestatement?invoicenumber="+datum[i]['cash_receipt_no']+"'  target='_blank' data-rel='tooltip' title='Print'><i class='ace-icon fa fa-print bigger-130'></i></a>";

              html += "<a  class='btn btn-danger btn-minier bootbox-confirm' id='"+datum[i]['cash_receipt_no']+"' data-rel='tooltip' title='Delete' onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
              html += "</div></td>";
              
              //action
              html += "</tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            removeallinvnoinarray();
            }
          }); 
  }
  </script>
