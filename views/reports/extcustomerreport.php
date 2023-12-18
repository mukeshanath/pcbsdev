<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'extcustomerreport';
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
  ?>
  <style>
     .date-filter table tr td,.type-filter table tr td,.branch-filter table tr td,.customer-filter table tr td{
          border-top: none;
     }
     .date-filter,.customer-filter,.type-filter,.branch-filter{
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
                                        <a href="<?php echo __ROOT__ . 'extcustomerreport'; ?>">Customer Report Filters</a>
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
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Report</p>
          
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

  
	    
    <div class="tab-content">
         <input type="text" id="stncode" class="hidden" name="custrep_stn" value="<?=$stationcode;?>">
          <!-- Tab Form 1 -->
     <div id="filters" class="tab-pane active" style="min-height:150px;">
     <div class="type-filter col-sm-3">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title align-left">Report Name</td></tr>
        <tr>		
               <td><select class="form-control" id="cust-report-type" name="custrep_type" onchange="if($(this).val()=='branchoutstandingsummary'){ $('.customer-filter').css('display','none');}else{$('.customer-filter').css('display','block');}; if($(this).val()=='customerledger'){$('.date-filter').css('display','block')}else{$('.date-filter').css('display','none')}">
           
            <option value="customeroutstandingsummary">Customer Outstanding Summary</option>  
            <option value="customerledger">Customer Ledger</option>     
            </select>
            </td>
       </tr>
    </table>
    </div>
     <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Collection Date Range</td></tr>
        <tr>		
            <td><input type="date" class="form-control today" id="ledger-date-from"></td>
            <td>to</td>
              
            <td><input type="date" class="form-control today" id="ledger-date-to"></td>
            <td>
           
            </td>
       </tr>
    </table>
    </div>
     <div class="branch-filter col-sm-2">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title align-left">Branch</td></tr>
        <tr>		
            <td class=" align-left">
                 <div class="autocomplete align-left">
                      <input type="text" class="form-control text-uppercase" id="filter-branch" name="custrep_branch" value="<?=$datafetch['br_code']?>" readonly></div></td>              
       </tr>
    </table>
    </div>
     <div class="customer-filter col-sm-2">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title align-left">Customer</td></tr>
        <tr>		
            <td class=" align-left">
            <div class="autocomplete align-left">
                 <input type="text" class="form-control text-uppercase" id="filter-customer" name="custrep_customer" value="<?=$datafetch['cust_code']?>" readonly oninput="verifycustomer()">
                 <p id="custcode_validation" class="form_valid_strings">Enter Valid Customer Code</p>
            </div></td>
       </tr>
    </table>
    </div>
     <div class="align-right col-sm-6" style="padding:10px;float:right;">
     <button type="button" class="btn btn-success" data-toggle="tab" id="filter-next" onclick="">
              Generate Report
               </button>
     </div>
     </div>
        <!-- Second Tab --> 
     <div id="report" class="tab-pane">
     <div class="report-header" style="border-bottom:2px solid black;margin-bottom:10px">
          <div class="date-between-column" style="text-align:left;">
               <h5 class="preview-heading">Preview of Customer Report 
                    <!-- <span id="head-date-from"></span> and <span id="head-date-to"></span> -->
               </h5>
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
     <table class="data-table collection-report-data" id="data-table1" cellpadding="0" border="1" cellspacing="0" style="width:100%;">
          <thead>
          <tr>
              <th></th>
          </tr>
          </thead>
          <tbody>
               <td></td>
          </tbody>
     </table>
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
var branchcode =[<?php  

$brcode=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
while($rowbrcode=$brcode->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrcode['br_code']."',";
}

?>];

 </script>
 <script>
 autocomplete(document.getElementById("filter-branch"), branchcode);

</script>
<script>
     $('#filter-next').click(function(){
          if($('#cust-report-type').val().trim()==''){
               swal("Please Select Report Type");
               return false;
          }
          else if($('#cust-report-type').val()=='customerledger' && $('#filter-customer').val().trim().toUpperCase()=='ALL'){
               swal("Customer is mandatory for ledger");
               return false;
          }
          else if($('#cust-report-type').val()=='customerledger' && $('#custcode_validation').css('display')==='block'){
               swal("Please enter valid customer");
               return false;
          }
          else{
          progress();
          generatereport();
          var custrep_type = $('#cust-report-type').val();
          var custrep_branch   = $('#filter-branch').val().toUpperCase();
          var custrep_customer   = $('#filter-customer').val().toUpperCase();
          var custrep_stn   = $('#stncode').val();
          var ledger_date_from   = $('#ledger-date-from').val();
          var ledger_date_to   = $('#ledger-date-to').val();
          
          $.ajax({
            url:"<?php echo __ROOT__ ?>reportcontroller",
            method:"POST",
            data:{custrep_type,custrep_branch,custrep_customer,custrep_stn,ledger_date_from,ledger_date_to},
            dataType:"text",
            success:function(data)
            {
               stopprogress();
               var d = new Date();
            $('.collection-report-data').html(data);
            if(custrep_type=='customeroutgroup'){
            $('.preview-heading').html('Customer Outstanding Group Summary Report as on '+new Date().toISOString().split('T')[0]);
            $('#report-title').html('Customer Outstanding Group Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(custrep_type=='customeroutstandingsummary'){
            $('.preview-heading').html('Customer Outstanding Summary Report as on '+new Date().toISOString().split('T')[0]);
            $('#report-title').html('Customer Outstanding Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(custrep_type=='custagewisereport'){
            $('.preview-heading').html('Customer Age Wise Summary Report as on '+new Date().toISOString().split('T')[0]);
            $('#report-title').html('Customer Age Wise outstanding Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            }
            });
          }
     });
</script> 
<script>
     function generatereport(){
         
          $('.collection-report-data').html("");
          $('#rep_li').attr('class','active');
          $('#gen1_li').attr('class','');
          $('#rep_a').attr('data-toggle','tab');
          $('#filter-next').attr('href','#report');
          
     }
</script>
<script>
     function printpdf(){
          var divToPrint=document.getElementById("data-table1");
          var headToPrint=document.getElementById("pdfheader");
          newWin= window.open("");
          newWin.document.write('<style>  table tr td{padding: 3px !important;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 13px;} table tr th{border-bottom:1px solid black;border-top:1px solid black;text-align:left;font-size:12px;font-weight:600} </style>');
          newWin.document.write('<head><title>Customer Report</title></head>');
          newWin.document.write(headToPrint.outerHTML);
          newWin.document.write(divToPrint.outerHTML);
          newWin.print();
           //newWin.close();
     }
</script>
<script>
$('#filter-branch').keyup(function(){
   var branch8 = $(this).val();
   var comp8 = $('#stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("filter-customer"), cust8); 
       }
   });
   });
</script>
<script>
            //Verifying Customer code is Valid
    function verifycustomer(){
     if($('#filter-customer').val().trim().toUpperCase()=='ALL'){
          $('#custcode_validation').css('display','none');
        }
        else{
        var cust_verify_br = $('#filter-branch').val();
        var cust_verify_cust = $('#filter-customer').val();
       
        $.ajax({
            url:"<?php echo __ROOT__ ?>cnotecontroller",
            method:"POST",
            data:{cust_verify_br:cust_verify_br,cust_verify_cust:cust_verify_cust},
            dataType:"text",
            success:function(customercount)
            {
            if(customercount == 0)
            {
                $('#custcode_validation').css('display','block');
            }
            else{
                $('#custcode_validation').css('display','none');
            }
            }
        });
     }
    }
</script>
<script>
function exportexcel(type, fn, dl){
     //data-table
     var sel = document.getElementById("cust-report-type");//report type option             
     var elt = document.getElementById('data-table1'); //table output to make excel
       var report_type = sel.options[sel.selectedIndex].text; 
       var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet 1",header:1,raw:false,dateNF:'dd-MMM-yyyy'});
       return dl ?
       XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
       XLSX.writeFile(wb, fn || (report_type+'.' + (type || 'xlsx')));
}
</script>