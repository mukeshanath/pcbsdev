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
    $module = 'cashdelete';
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
                                        <a href="<?php echo __ROOT__ . 'accountspayinvoicelist'; ?>">Accouts Pay Invoice list</a>
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

  if ( isset($_GET['success']) && $_GET['success'] == 10 )
{
    echo 

"<div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-check'></i> Alert!</h4>
                Collection Invoice Generated successfully.
              </div>";
}

if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 1 )
{
    echo 

"<div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-times'></i> Alert!</h4>
                Please Select Transaction Between Date.
              </div>";
}
if ( isset($_GET['emptyinvoice']))
{
    echo "<script> swal('There is No transaction to Generate Accounts Pay'); </script>";
             
}
if (isset($_GET['nocomission']))
{
    echo "<script> swal('This Customer doesn't have Commision'); </script>";      
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

         <a style="color:white" href="<?php echo __ROOT__ ?>accountspay
         "><button type="button"  data-rel='tooltip' title='Raise Invoice' class="btn btn-success btnattr" >
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
                        <input placeholder="" class="form-control border-form" name="br_code" type="text" id="br_code" value="<?php if(isset($_POST['br_code'])){ echo  $_POST['br_code']; } ?>"  autofocus>
                   </div>
                     </td>
                    
                  <td>Customer</td>
                  <td>
                  <div class="col-12">
				  <input placeholder="" class="form-control border-form" name="cust_code" type="text" id="cust_code" value="<?php if(isset($_POST['cust_code'])){ echo  $_POST['cust_code']; } ?>">
                    </div>
                      </td>

                    <td>Invoice</td>
                  <td>
                  <div class="col-12">
				  <input placeholder="" class="form-control border-form" name="inv_no" type="text" id="addr_type" value="<?php if(isset($_POST['inv_no'])){ echo  $_POST['inv_no']; } ?>">
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
                    <!-- <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()">
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button>  -->
                    </div>
                    </table> 
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Accounts Pay List</p>
              <div class="align-right" style="margin-top:-33px;width:95%;float:right;margin-right:68px" id="print">
          <div class="dt-buttons"> 
               <!-- <button class="btn btn-primary btn-minier bootbox-confirm " aria-controls="data-table" data-rel='tooltip' title='Send Bulk email' onclick="sendbulkemail()" type="button"><i class='fa fa-envelope' aria-hidden='true'></i></button> 
               <button class="btn btn-danger btn-minier bootbox-confirm " aria-controls="data-table" data-rel='tooltip' title='Print Bulk Invoices' onclick="bulkprintinvoice()" type="button"><i class='fa fa-print' aria-hidden='true'></i></button>  -->
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
          
                  <th >S.No</th>
                  <th >Branch Code</th>
                  <th >CC code</th>
                    <th >CCInv No</th>
                    <th >Inv Date</th>                  
                    <th >Total Consignments</th>
                    <!-- <th >V-Weight</th> -->
                    <th >Total Commission</th>                                
                    <th >Action Items</th>
              </tr>
    </thead>
              <tbody id="datatable-tbody">
                  <?php  //error_reporting(0);
                              $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
                              $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
                              $rowuser = $datafetch['last_compcode'];
                              if(!empty($rowuser)){
                                    $stationcode = $rowuser;
                                    $stn_condition = "WHERE station_code='$stationcode' AND";
                              }
                              else{
                                  $userstations = $datafetch['stncodes'];
                                  $stations = explode(',',$userstations);
                                  $stationcode = $stations[0];  
                                  $stn_condition = "WHERE station_code='$stationcode' AND";   
                              }
                            
                              $conditions = array();
                             $conditions[] = "station_code='$stationcode'";

                             if(isset($_POST['filterinvoicelist']))
                             {    
                                 $br_code              = $_POST['br_code'];
                                 $cust_code            = $_POST['cust_code'];
                                 $inv_no               = $_POST['inv_no'];
                                 $mon_th             = $_POST['mon_th']; 
                                 //$date_added           = $_POST['date_added']; 
             
             
                                 if(! empty($br_code)) {
                                 $conditions[] = "br_code LIKE '$br_code%'";
                                 } 
                                 if(! empty($cust_code)) {
                                 $conditions[] = "cc_code LIKE '$cust_code%'";
                                 }
                                 if(! empty($inv_no)) {
                                 $conditions[] = "ccinvno LIKE '$inv_no%'";
                                 }
                                 if(! empty($mon_th)) {
                                  $conditions[] = "YEAR(ccinvdate) ='".explode('-',$mon_th)[0]."' AND MONTH(ccinvdate) = '".explode('-',$mon_th)[1]."'";
                                  }
                               
                            
                                 $conditons = implode(' AND ', $conditions);
             
                                 if (count($conditions) > 0) {
                                     $query = $db->query("SELECT * FROM ccinvoice  WHERE  $conditons AND status='Invoiced' ORDER BY ccinvid ASC");
                                     
                             // echo json_encode($query); 
                                 }
                                 if (count($conditions) == 0) {
                                 $query = $db->query("SELECT * FROM ccinvoice  $stn_condition status='Invoiced'ORDER BY ccinvid ASC "); 
                                 
                             // echo json_encode($query);
                             }
                             } 
                             else{   
                              $conditions[] = "datepart(mm,ccinvdate) = month(getdate()) AND datepart(year,ccinvdate) = year(getdate())";

                              $conditons = implode(' AND ', $conditions);

                              $query = $db->query("SELECT * FROM ccinvoice  $stn_condition status='Invoiced' AND datepart(mm,ccinvdate) = month(getdate()) AND datepart(year,ccinvdate) = year(getdate()) ORDER BY ccinvid ");

                              //echo json_encode($query);
                             
                            }
                             if($query->rowcount()==0)
                             {
                             echo "";
                             }
                             else{
                
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            $sno=0;
                           
                

                                $sno++;
                                echo "<tr>";
                                echo "<td class='center first-child'>
                                        <label>
                                           <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                            <span class='lbl'></span>
                                         </label>
                                      </td>";
                                     
                                      
                                echo "<td>".$row->ccinvid."</td>";
                                echo "<td>".$row->br_code."</td>";
                                echo "<td>".$row->cc_code."</td>";
                                echo "<td>".$row->ccinvno."</td>";
                                echo "<td>".$row->ccinvdate."</td>";
                                echo "<td>".$row->cc_tot_cons."</td>";
                                echo "<td>".$row->cc_tot_comm."</td>";                               
                                echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                                <a class='btn btn-success btn-minier' href='collectioncenterinvoice?collectioninvoice=".$row->cc_code."&fromdate=".$row->from_date."&todate=".$row->to_date."&stncode=".$row->station_code."&br_code=".$row->br_code."&ccinvno=".$row->ccinvno."'  target='_blank' data-rel='tooltip' title='Print'>
                                <i class='ace-icon fa fa-print bigger-130'></i>
                               </a>";
                              if($row->status=='Invoiced'){
                              echo  "<a  class='btn btn-danger btn-minier bootbox-confirm'  id='$row->ccinvno' onclick='".delete($db,$user)."'>
                                <i class='ace-icon fa fa-trash-o bigger-130'></i>
                                 </a>";
                              }else{
                                echo " <a  class='btn btn-danger btn-minier bootbox-confirm' data-rel='tooltip' title='Invoice already deleted' _onclick='deletepreventalert()'>
                                <i class='ace-icon fa fa-trash-o bigger-130'></i> Trashed
                               </a>";
                              }
                             
                              echo "</div>";
                              echo  "</td>";
                              
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
   
    var dtbquery = "SELECT ccinvid,br_code,cc_code,ccinvno,ccinvdate,cc_tot_cons,cc_tot_comm,from_date,to_date,station_code,status FROM ccinvoice with(nolock) WHERE <?=$conditons; ?> AND station_code ='<?=$stationcode;?>' AND status='Invoiced' ORDER BY ccinvdate DESC";
    // console.log(dtbquery);
      var dtbcolumns = 'ccinvid,br_code,cc_code,ccinvno,ccinvdate,cc_tot_cons,cc_tot_comm,from_date,to_date,station_code,status';
          // console.log(dtbquery);
      var dtbpageno = ($(this).val()-1)*10;
      $.ajax({
            url:"<?php echo __ROOT__ ?>cash",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,action: 'cashllish'},
            dataType:"json",
            success:function(datum)
            {
           console.log(dtbpageno);
           $('#datatable-tbody').html('');
           var datacount = Object.keys(datum).length;
           console.log(datacount);
           const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
          
           var html='';
            for(var i=1;i<=datacount;i++){
              var  tabledate = datum[i]['ccinvdate'];
               let current_datetime = new Date(tabledate)
           let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear() 
console.log(formatted_date);
          
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['ccinvid']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cc_code']+"</td>";
              html += "<td>"+datum[i]['ccinvno']+"</td>";
              // html += "<td>"+datum[i]['pcs']+"</td>";
              html += "<td>"+formatted_date+"</td>";
              html += "<td>"+datum[i]['cc_tot_cons']+"</td>";
             
              html += "<td>"+datum[i]['cc_tot_comm']+"</td>";
            //  <button type='button'  class='btn btn-danger btn-minier bootbox-confirm' name='$row->status' value='$row->cnote_serial' id='$row->cno' onclick='".delete($db,$user)."'>
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              html += "<a class='btn btn-success btn-minier' href='collectioncenterinvoice?collectioninvoice="+datum[i]['cc_code']+"&fromdate="+datum[i]['from_date']+"&todate="+datum[i]['to_date']+"&stncode="+datum[i]['station_code']+"&br_code="+datum[i]['br_code']+"&ccinvno="+datum[i]['ccinvno']+"'  target='_blank' data-rel='tooltip' title='Print'><i class='ace-icon fa fa-print bigger-130'></i></a>";
             if(datum[i]['status']=='Invoiced'){
              html += "<a><button type='button' class='btn btn-danger btn-minier bootbox-confirm' id='"+datum[i]['ccinvno']+"' onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></button></a>";
             }
             
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
      sessionStorage.setItem("accountspaylist", fieldvalue);
      
      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT ccinvid,br_code,cc_code,ccinvno,ccinvdate,cc_tot_cons,cc_tot_comm,from_date,to_date,station_code,status FROM ccinvoice with(nolock) WHERE <?=$conditons;?> AND station_code ='<?=$stationcode;?>' AND status='Invoiced' AND (ccinvno like '%"+fieldvalue+"%' OR ccinvno LIKE '%"+fieldvalue+"%') ORDER BY ccinvdate DESC";
      var dtbcolumns = 'ccinvid,br_code,cc_code,ccinvno,ccinvdate,cc_tot_cons,cc_tot_comm,from_date,to_date,station_code,status';
      var dtbpageno = '0';
      console.log(dtbquery);
     
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>cash",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,action: 'cashllish'},
            dataType:"json",
            success:function(datum)
            {
           console.log(datum);
           $('#datatable-tbody').html('');
           var datacount = Object.keys(datum).length;
           console.log(datacount);
           const months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
          
           var html='';
            for(var i=1;i<=datacount;i++){
              var  tabledate = datum[i]['ccinvdate'];
               let current_datetime = new Date(tabledate)
           let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear() 
console.log(formatted_date);
          
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['ccinvid']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['cc_code']+"</td>";
              html += "<td>"+datum[i]['ccinvno']+"</td>";
              // html += "<td>"+datum[i]['pcs']+"</td>";
              html += "<td>"+formatted_date+"</td>";
              html += "<td>"+datum[i]['cc_tot_cons']+"</td>";
             
              html += "<td>"+datum[i]['cc_tot_comm']+"</td>";
            //  <button type='button'  class='btn btn-danger btn-minier bootbox-confirm' name='$row->status' value='$row->cnote_serial' id='$row->cno' onclick='".delete($db,$user)."'>
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              html += "<a class='btn btn-success btn-minier' href='collectioncenterinvoice?collectioninvoice="+datum[i]['cc_code']+"&fromdate="+datum[i]['from_date']+"&todate="+datum[i]['to_date']+"&stncode="+datum[i]['station_code']+"&br_code="+datum[i]['br_code']+"&ccinvno="+datum[i]['ccinvno']+"'  target='_blank' data-rel='tooltip' title='Print'><i class='ace-icon fa fa-print bigger-130'></i></a>";
             if(datum[i]['status']=='Invoiced'){
              html += "<a><button type='button' class='btn btn-danger btn-minier bootbox-confirm' id='"+datum[i]['ccinvno']+"'  onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></button></a>";
             }
             
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
<script>
  //console.log(cnotenumber,cnoteserial);
function deletequeue(cccode){
  swal({
title: "Are you sure?",
text: "Once deleted, you will not be able to recover this record file!",
icon: "warning",
buttons: true,
dangerMode: true,
})
.then((willDelete) => {

  if (willDelete) {
    var delcc_ecompany = '<?=$stationcode?>';
    var delcc_ecnote = cccode;
    var delcash_eusercc = '<?=$user?>'

    console.log(delcc_ecnote);
    $.ajax({
              url:"<?php echo __ROOT__ ?>accountspay",
              method:"POST",
              data:{delcc_ecompany,delcc_ecnote,delcash_eusercc},
              dataType:"text",
              
      });
    swal("Your Record has been deleted!", {
      icon: "success",
    });
    $('#'+cccode).closest('tr').remove();
    
  } else {
    swal("Your Record is safe!");
  }
});

}

</script>  
</script>
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
function opencookie(){
$('.dataTables_filter').find('input').val(sessionStorage.getItem("invoicelist").trim());
$('.dataTables_filter').find('input').keyup();
}
</script>
<!-- Set cookie for datatable search -->

