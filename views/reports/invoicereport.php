<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'invoicereport';
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

function generatereportid($db){

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

      //branch filter conditions
      $sessionbr_code = $datafetch['br_code'];
      if(!empty($sessionbr_code)){
          $br_code = $sessionbr_code;
          $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
          $br_name     = $fetchbrname->br_name;
          }
       //branch filter conditions
  ?>
  <style>
       .scrollable2 {
     width: 100%;
     overflow-x: scroll;
     border-bottom: 1px solid #ddd;
     }

     .date-filter table tr td,.type-filter table tr td,.month-filter table tr td{
          border-top: none;
     }
     .date-filter,.type-filter,.month-filter{
          text-align: center;
          margin-bottom: 10px;
          padding: 10px;
     }
     .filter-title{
          font-weight: 600;
     }
     .preview-heading{
          font-size:20px;
          font-weight:600;
          font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif
     }
     
  </style>
  <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;float:none">  


<div id="col-form1" class="col_form lngPage col-sm-25">
<!-- Bread Crumb -->
 <div class="au-breadcrumb m-t-75" style="margin-bottom:-8px;">
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
                                        <a href="<?php echo __ROOT__ . 'invoicereport'; ?>">Invoice Report Filters</a>
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
  
                    if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Branch Added successfully.
                                </div>";
                  }

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 4 )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Branch Code is already added.
                                </div>";
                  }

                  

                   ?>
                
     </div>
        <form method="POST" action="<?php echo __ROOT__ ?>excel" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="formbranch" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice Report</p>
          
    <div class="align-right" style="margin-top:-35px;">
                                       
       <!-- <button type="button" class="btn btn-warning btnattr_cmn" onclick="printData()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>    -->

       <a style="color:white" data-toggle="tab" href="#filters" _href="<?php echo __ROOT__ . 'dashboard'; ?>" onclick="$('#rep_li').attr('class','');$('#gen1_li').attr('class','active');">         
       <button type="button" class="btn btn-danger btnattr_cmn" >
       <i class="fa fa-times" ></i>
       </button></a>
    </div>
    </div> 
	<div class="content panel-body">
            <!-- Navigation Tabs -->
      <ul class="nav nav-tabs" id="myTab4">
        <li id="gen1_li" class="active">
            <a id="gen1_a" data-toggle="tab" href="#filters">Filters</a>
        </li>
        <li id="rep_li">
            <a id="rep_a" data-toggle="disabled" href="#report">Report</a>
        </li>
      </ul>
    
   <div class="tab_form" >
   <input type="text" id="stncode" class="hidden" name="inv_stn" value="<?=$stationcode;?>">
  
	    
    <div class="tab-content">
          <!-- Tab Form 1 -->
     <div id="filters" class="tab-pane active" style="min-height:190px;">
     <div class="type-filter col-sm-3">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title">Report Name</td></tr>
        <tr>		
               <td><select class="form-control" id="report-type" name="reporttype" onchange="if($(this).val()=='monthwiseinvoicesummary'){$('.date-filter').css('display','none');$('.month-filter').css('display','block')}else{$('.date-filter').css('display','block');$('.month-filter').css('display','none')}">
            <option value="">Select Type</option>     
            <option value="invoicesummary">Invoice Summary</option>     
            <option value="invoicesummarywithcoll">Invoice Summary with collection</option>     
            <option value="monthwiseinvoicesummary">Month wise Invoice Summary</option>     
            <option value="ccinvoicesummary">CC Invoice Summary</option>  
            <option value="invoicegstreport">GST Report</option>     
                
            </select>
            </td>
       </tr>
    </table>
    </div>
     <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Invoice Date Range</td></tr>
        <tr>		
            <td><input type="date" class="form-control today" name="inv_date_from" id="inv-date-from"></td>
            <td>to</td>
              
            <td><input type="date" class="form-control today" name="inv_date_to" id="inv-date-to"></td>
            <td>
           
            </td>
       </tr>
    </table>
    </div>
     <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Transaction Date Range</td></tr>
        <tr>		
            <td><input type="date" class="form-control today" name="invtran_date_from" id="invtran-date-from" onchange="$('#inv-date-from').val('');$('#inv-date-to').val('')"></td>
            <td>to</td>
              
            <td><input type="date" class="form-control today" name="invtran_date_to" id="invtran-date-to"></td>
            <td>
           
            </td>
       </tr>
    </table>
    </div>
     <div class="month-filter col-sm-4" style="display:none">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Transaction Month Range</td></tr>
        <tr>		
            <td><input type="month" class="form-control this-month" name="inv_month_from" id="inv-month-from">
            </td>
            <td>to</td>
              
            <td><input type="month" class="form-control this-month" name="inv_month_to" id="inv-month-to">
            </td>
          
       </tr>
    </table>
    </div>

    <!-- Branch and customer -->
    <div class="customer-filter col-sm-12">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
     <tr>
     <td class="filter-title">Branch Code</td>
     <td class="filter-title">Branch Name</td>
     <td class="filter-title">Customer Code</td>
     <td class="filter-title">Customer Name</td>
</tr>
        <tr>	
            <td><div class="autocomplete col-sm-12"><input type="text" placeholder="Branch Code" class="form-control" name="inv_branch" id="inv_branch" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_code))?$br_code:''; ?>"></div></td>
            <td><div class="autocomplete col-sm-12"><input type="text" placeholder="Branch Name" class="form-control" id="inv_branch_name" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_name))?$br_name:''; ?>"></div></td>
            <td><div class="autocomplete col-sm-12"><input type="text" placeholder="Customer Code" class="form-control" name="inv_customer" id="inv_customer"></div></td>
            <td><div class="autocomplete col-sm-12"><input type="text" placeholder="Customer Name" class="form-control" id="inv_customer_name"></div></td>
       </tr>
    </table>
    </div>
    <!-- Branch and customer -->
   
     <div class="align-right" style="padding:10px;float:right;margin-top:100px">
     <button type="button" class="btn btn-success" data-toggle="tab" id="filter-next" onclick="generatereport()">
              Generate Report
               </button>
     </div>
     </div>
        <!-- Second Tab --> 
     <div id="report" class="tab-pane">
     <div class="report-header" style="border-bottom:2px solid black;margin-bottom:10px">
          <div class="date-between-column" style="text-align:left;">
               <h5 class="preview-heading">Preview of Invoice Summary between <span id="head-date-from"></span> and <span id="head-date-to"></span></h5>
          </div>
     </div>
     <div style="border:1px solid var(--side-nav-main)">
     <div class="title_1 title panel-heading" style="text-align:left;">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Report Preview</p>
                  <div class="align-right" style="margin-top:-33px;" id="print">
          <div class="dt-buttons"> 
               <button class="dt-button buttons-excel buttons-html5" aria-controls="data-table" onclick="exportexcel('xlsx')" type="button"><span><img data-toggle="tooltip" title="Export to EXCEL" style="width:100%;" src="https://img.icons8.com/color/48/000000/ms-excel.png"></span></button> 
               <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="data-table" type="button" onclick="printpdf()"><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/pdf.png"></span></button> 
          </div>
         </div>
               </div>
              
               <div class="scrollable2">
     <table class="data-table invoice-report-table" id="data-table1" cellpadding="0" border="1" cellspacing="0" style="width:100%;white-space: nowrap;" class="table">
          <thead>
               <tr>
                    <th></th>
               </tr>
          </thead>
          <tbody>
               <tr>
                    <td></td>
               </tr>
          </tbody>
     </table>
     </div>
     </div>
      
	</div>
	</div>

	</form>
	</div>
	</div>
	</div>
</div>

<div class="pdf-header" style="display:none">
     <div id="pdfheader" style="padding:15px;max-width:100%">
         <div style='float:left;height:160px;'>
            <h2 style="font-family:Arial, Helvetica, sans-serif;font-weight:600"><?php $stationname = $db->query("SELECT stnname FROM station WHERE stncode='$stationcode'")->fetch(PDO::FETCH_OBJ); echo $stationname->stnname ?></h2>
            <p style="margin-top:80px;font-size:13px" id='report-title'></p>
          </div>
            <div  style='float:right;height:160px;width:40%;margin-right:50px'>
            <img src="views/image/TPC-Logo.jpg" style="width:100%;margin-left:60px">
         </div>
     </div>
   </div>
						
</section>
<div class="progress active">
            <div class="progress-bar progress-bar-striped progress-bar-success"  style="width: 0%" ></div>
        </div>
<script>
	
	    $(document).ready(function(){
     $("#inv-date-from").change(function(){
        $("#invtran-date-from").val('');
        $("#invtran-date-to").val('');
     });
    });
   function progress(){
        //start progress
    $loadingtime = parseInt($('#cnote_count').val());
    $(".progress-bar").animate({
        width: "100%"
    }, $loadingtime);

    $('#header').css('filter','blur(5px)');
    $('nav').css('filter','blur(5px)');
    $('section').css('filter','blur(5px)');
    $('footer').css('filter','blur(5px)');
    }
    //stop progress-bar
    function stopprogress(){
     $(".progress-bar").css("width","0%");
     $('#header').css('filter','');
    $('nav').css('filter','');
    $('section').css('filter','');
    $('footer').css('filter','');
    }
</script>
<script>
     $('#filter-next').click(function(){
          if($('#inv-date-from').val().trim()=='' && $('#inv-date-to').val().trim()=='' && $('#invtran-date-from').val().trim()=='' && $('#invtran-date-to').val().trim()==''){               swal("Please Select Invoice Date Range");
          }
          else{
          progress();
          var inv_date_from   = $('#inv-date-from').val();
          var inv_branch   = $('#inv_branch').val();
          var inv_customer   = $('#inv_customer').val();
          var invtran_date_from   = $('#invtran-date-from').val();
          var inv_date_to     = $('#inv-date-to').val();
          var invtran_date_to     = $('#invtran-date-to').val();
          var reporttype      = $('#report-type').val();
          var inv_month_from  = $('#inv-month-from').val()+'-01';
          var inv_month_to  = $('#inv-month-to').val()+'-15';
          //console.log(inv_month_from);
          //console.log(inv_month_to);
          var inv_year = $('#inv-year').val();
          var inv_stn = $('#stncode').val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>reportcontroller",
            method:"POST",
            data:{inv_date_from,inv_date_to,reporttype,inv_month_from,inv_month_to,inv_year,inv_stn,invtran_date_to,invtran_date_from,inv_branch,inv_customer},
            dataType:"text",
            success:function(data)
            {
               stopprogress();
               var months = ["zero","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            $('.invoice-report-table').html(data);
            if(reporttype=='invoicesummary'){
            $('.preview-heading').html('Invoice Summary Between '+((inv_date_from.length==0) ? invtran_date_from : inv_date_from)+' and '+((inv_date_to.length==0) ? invtran_date_to : inv_date_to));
            $('#report-title').html('Invoice Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(reporttype=='invoicesummarywithcoll'){
            $('.preview-heading').html('Invoice Summary with Collection Between '+inv_date_from+' and '+inv_date_to);
            $('#report-title').html('Invoice Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(reporttype=='monthwiseinvoicesummary'){
            $('.preview-heading').html('Month wise Invoice Summary Between '+months[parseInt(inv_month_from.split("-")[1])]+' '+inv_month_from.split("-")[0]+' and '+months[parseInt(inv_month_to.split("-")[1])]+' '+inv_month_to.split("-")[0]);
            $('#report-title').html('Month wise Invoice Summary Between '+months[parseInt(inv_month_from.split("-")[1])]+' '+inv_month_from.split("-")[0]+' and '+months[parseInt(inv_month_to.split("-")[1])]+' '+inv_month_to.split("-")[0]);
            }
            else if(reporttype=='unvoicesummary'){
            $('.preview-heading').html('Unvoice Summary Between '+inv_date_from+' and '+inv_date_to);
            $('#report-title').html('Unvoice Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(reporttype=='invoicegstreport'){
            $('.preview-heading').html('GST Summary Between '+inv_date_from+' and '+inv_date_to);
            $('#report-title').html('GST Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(reporttype=='ccinvoicesummary'){
            $('.preview-heading').html('CC Summary  Between '+inv_date_from+' and '+inv_date_to);
            $('#report-title').html('CC Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            }
            });
          }
     });
</script>
<script>
     function generatereport(){
          if($('#inv-date-from').val().trim()=='' && $('#inv-date-to').val().trim()=='' && $('#invtran-date-from').val().trim()=='' && $('#invtran-date-to').val().trim()==''){
               swal("Please Select Invoice Date Range");
               return false;
          }
          else if($('#report-type').val()==''){
               swal("Please Select Report Type");
          }
          else{
          $('.invoice-report-table').html("");
          $('#rep_li').attr('class','active');
          $('#gen1_li').attr('class','');
          $('#rep_a').attr('data-toggle','tab');
          $('#filter-next').attr('href','#report');
          }
     }
</script>
<script>
     function printpdf(){
          var divToPrint=document.getElementById("data-table1");
          var headToPrint=document.getElementById("pdfheader");
          newWin= window.open("");
          newWin.document.write('<style> table tr td{padding: 3px !important;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 13px;} table tr th{border-bottom:1px solid black;border-top:1px solid black;text-align:left;font-size:12px;font-weight:600} </style>');
          newWin.document.write('<head><title>Customer Report</title></head>');
          newWin.document.write(headToPrint.outerHTML);
          newWin.document.write(divToPrint.outerHTML);
          newWin.print();
           //newWin.close();
     }
</script>
<script>
 $(document).ready(function(){
    
    var max = new Date().getFullYear();
    var min = max-20;
    var select = document.getElementById('inv-year');

    $('#inv-year').html("<option value='"+new Date().getFullYear()+"'>"+new Date().getFullYear()+"</option");

    for (var i = min; i<=max; i++){
         if(i==max){

         }
         else{
          var opt = document.createElement('option');
          opt.value = i;
          opt.innerHTML = i;
           select.appendChild(opt);
         }
    }
 });
</script>
<script>
var branchcode =[<?php  

$brcode=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
while($rowbrcode=$brcode->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrcode['br_code']."',";
}

?>];
autocomplete(document.getElementById("inv_branch"), branchcode);

var branchname =[<?php  

$brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode'");
while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrname['br_name']."',";
}
?>];
 
 autocomplete(document.getElementById("inv_branch_name"), branchname);
</script>
<script>
//branches array
var branches = {
    <?php
    $branches = $db->query("SELECT br_code,br_name FROM branch WHERE stncode='$stationcode'");
    while($rowbranches=$branches->fetch(PDO::FETCH_OBJ))
    {
    echo $rowbranches->br_code.":'".$rowbranches->br_name."',";
    }
    ?>
}
</script>

<script>


customers = {}

  </script>
<script>
function fetchcustomer(){
   var branch8 = $('#inv_branch').val().toUpperCase();
   var comp8 = '<?=$stationcode?>';
       $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("inv_customer"), cust8); 
       }
       });
       $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(custname)
       {
           autocomplete(document.getElementById("inv_customer_name"), custname); 
       }
       });

}
</script>
<script> 
function getcustomersjson(){
   //get customers json
   var branch8 = $('#inv_branch').val().toUpperCase();
   var comp8 = '<?=$stationcode?>';
   $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{getjsoncust_br:branch8,getjsoncust_stn:comp8},
       dataType:"text",
       success:function(customerjson)
       {
          customers = JSON.parse(customerjson);
       }
       });
    }
</script>
<script>
     $(document).ready(function(){
     
     $('#inv_branch').keyup(function(){
         var br_code = $('#inv_branch').val().toUpperCase().trim();
         if(branches[br_code]!==undefined){
             //$('#brcode_validation').css('display','none');
             $('#inv_branch_name').val(branches[br_code]);
             fetchcustomer();
             getcustomersjson();
         }
         else{
             //$('#brcode_validation').css('display','block');
         }
     });
     //fetch code of entering name
     $('#inv_branch_name').keyup(function(){
         var br_name = $(this).val().trim();
         var gccode = getKeyByValue(branches,br_name);
         
         if(gccode!==undefined){
             //$('#brcode_validation').css('display','none');
             $('#inv_branch').val(gccode);
             fetchcustomer();
             getcustomersjson();
         }
         else{
             //$('#brcode_validation').css('display','block');
         }
       });
     });
</script>
<script>
    function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
</script>
<script> 

$(document).ready(function(){
     
     $('#inv_customer').keyup(function(){
         var cust_code = $('#inv_customer').val().toUpperCase().trim();

        if(customers[cust_code]!==undefined){
            $('#inv_customer_name').val(customers[cust_code]);
            checkpacking_insurance(cust_code);
            $('#customer_validation').css('display','none');
            checkcustlockstatus(cust_code,'<?=$stationcode?>');
        }
        else{
            $('#customer_validation').css('display','block');
        }
         cust_cnote_verify();
     });
     //fetch code of entering name
     $('#inv_customer_name').keyup(function(){
         var cust_name = $(this).val();
         var ccode = getKeyByValue(customers,cust_name);

         if(ccode!==undefined){
            $('#inv_customer').val(ccode);
            checkpacking_insurance(ccode);
            checkcustlockstatus(cust_code,'<?=$stationcode?>');
            $('#customer_validation').css('display','none');
            }
            else{
                $('#customer_validation').css('display','block');
            }
         cust_cnote_verify();
     });

});

</script>
<script>
function exportexcel(type, fn, dl){
     //data-table
     if($('#report-type').val()=='monthwiseinvoicesummary' || $('#report-type').val()=='ccinvoicesummary'){
        var sel = document.getElementById("report-type");//report type option             
        var elt = document.getElementById('data-table1'); //table output to make excel
        var report_type = sel.options[sel.selectedIndex].text; 
        var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet 1",header:1,raw:true});
        return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
        XLSX.writeFile(wb, fn || (report_type+'.' + (type || 'xlsx')));
     }
     else{
     var sel = document.getElementById("report-type");//report type option             
     var elt = document.getElementById('data-table1'); //table output to make excel
       var report_type = sel.options[sel.selectedIndex].text; 
       var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet 1",header:1,raw:false,dateNF:'dd-MMM-yyyy'});
       return dl ?
       XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
       XLSX.writeFile(wb, fn || (report_type+'.' + (type || 'xlsx')));
     }
}



$(window).on('load', function () {
    fetchcustomer();
  });
</script>