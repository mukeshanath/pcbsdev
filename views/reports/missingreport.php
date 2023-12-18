<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'missingreport';
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

     //For Generating Request Number on form load
     function loadgroup($db,$stationcode)
     {
          $output='';
         $query = $db->query("SELECT * FROM groups WHERE stncode='$stationcode' ORDER BY gpid ASC");
        
          while ($row = $query->fetch(PDO::FETCH_OBJ)){
             
         //menu list in home page using this code    
         $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
          }
          return $output;    
         
     }
  ?>
  <style>
     .customer-filter table tr td,.type-filter table tr td,.branch-filter table tr td,.date-filter table tr td,.month-filter table tr td,.cc-filter table tr td{
          border-top: none;
     }
     .customer-filter,.type-filter,.branch-filter,.date-filter,.month-filter,.cc-filter{
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
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Reports</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Missing Report</li>
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
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Missing Report</p>
          
    <div class="align-right" style="margin-top:-35px;">
                                       
       <!-- <button type="button" class="btn btn-warning btnattr_cmn" onclick="printData()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>    -->

       <a style="color:white" href="<?php echo __ROOT__ . 'dashboard'; ?>">         
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
         <input type="text" id="stncode" class="hidden" value="<?=$stationcode;?>" name="missed_station">
          <!-- Tab Form 1 -->
     <div id="filters" class="tab-pane active" style="min-height:200px;">
     <div class="type-filter col-sm-3">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title align-left">Report Name</td></tr>
        <tr>		
            <td><select class="form-control" id="missed-report-type" name="missed_report_type">
            <option value="">Select Type</option>     
            <option value="custau">Customer Allotment Vs Usage</option>     
            <option value="custub">Customer Used Vs Billed</option>  
            <option value="branchau">Branch Allotment Vs Usage</option>        
            <option value="branchub">Branch Used Vs Billed</option>   
            <option value="UnUsedCC">CC Allotment Vs Usage</option>      
            <option value="Unallocated">Unallocated Cnotes</option>    
            <option value="UnKnown">UnKnown Cnotes</option>
            <option value="UnInvoiced">UnInvoiced Cnotes</option>
 
            </select>
            </td>
       </tr>
    </table>
    </div>
    <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%" >
	   <tr><td class="filter-title" colspan="3">Date Range</td></tr>
        <tr>		
            <td><input type="date" class="form-control today" id="book-date-from" name="miss_date_from"></td>
            <td>to</td>
              
            <td><input type="date" class="form-control today" id="book-date-to" name="miss_date_to"></td>
            <td>
           
            </td>
       </tr>
    </table>
    </div>

    <!-- <div class="month-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Month Range</td><td class="filter-title">Year</td></tr>
        <tr>		
            <td><select class="form-control" name="miss_month_from" id="inv-month-from">
                 <option value="">Select</option>
                 <option value="1">January</option>
                 <option value="2">February</option>
                 <option value="3">March</option>
                 <option value="4">April</option>
                 <option value="5">May</option>
                 <option value="6">June</option>
                 <option value="7">July</option>
                 <option value="8">August</option>
                 <option value="9">September</option>
                 <option value="10">October</option>
                 <option value="11">November</option>
                 <option value="12">December</option>
                 </select>
            </td>
            <td>to</td>
              
            <td><select type="date" class="form-control" name="miss_month_to" id="inv-month-to">
                 <option value="">Select</option>
                 <option value="1">January</option>
                 <option value="2">February</option>
                 <option value="3">March</option>
                 <option value="4">April</option>
                 <option value="5">May</option>
                 <option value="6">June</option>
                 <option value="7">July</option>
                 <option value="8">August</option>
                 <option value="9">September</option>
                 <option value="10">October</option>
                 <option value="11">November</option>
                 <option value="12">December</option>
                 </select>
            </td>
            <td>
           <select class="form-control" id="inv-year" name="miss_year">
           <option value="2020">2020</option>
           </select>
            </td>
       </tr>
    </table>
    </div> -->
     <!-- <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Collection Date Range</td></tr>
        <tr>		
            <td><input type="date" class="form-control today" id="coll-date-from"></td>
            <td>to</td>
              
            <td><input type="date" class="form-control today" id="coll-date-to"></td>
            <td>
           
            </td>
       </tr>
    </table>
    </div> -->
    <div class="branch-filter col-sm-1">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title align-left missed-br-td">Serial</td></tr>
        <tr class=" align-left">		
            <td class="missed-br-td">
                 <div class="autocomplete align-left">
                      <select class="form-control" id="missed-serial" name="missed_serial">
                           <option value="ALL">ALL</option>
                         <?php echo loadgroup($db,$stationcode); ?>
                         </select>
                      </div>
               </td>
       </tr>
    </table>
    </div>
     <div class="branch-filter col-sm-2">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title align-left missed-br-td">Branch</td></tr>
        <tr class=" align-left">		
            <td class="missed-br-td">
                 <div class="autocomplete align-left">
                      <input type="text" class="form-control" id="missed-branch" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_code))?$br_code:''; ?>" name="missed_branch" onkeyup="validatebranch(this.value)">
                      </div>
                    </td>
       </tr>
    </table>
    </div>
     <div class="customer-filter col-sm-2">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title align-left">Customer</td></tr>
        <tr>		
            <td class="align-left">
                 <div class="autocomplete align-left">
                 <input type="text" class="form-control" id="missed-customer" value="" name="missed_customer">
               </div></td>
       </tr>
    </table>
    </div>
     <div class="cc-filter col-sm-2" style="display:none">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title align-left">CollectionCenter</td></tr>
        <tr>		
            <td class="align-left">
                 <div class="autocomplete align-left">
                 <input type="text" class="form-control" id="missed-cc" value="ALL" name="missed_cc">
               </div></td>
       </tr>
    </table>
    </div>
     <div class="align-right" style="padding:10px;margin-top:40px;float:right">
     <button type="button" class="btn btn-success" data-toggle="tab" id="filter-next" onclick="generatereport()">
              Generate Report
               </button>
     </div>
     </div>
        <!-- Second Tab --> 
     <div id="report" class="tab-pane">
     <div class="report-header" style="border-bottom:2px solid black;margin-bottom:10px">
          <div class="date-between-column" style="text-align:left;">
               <h5 class="preview-heading">Preview of Missing report
                    </h5>
          </div>
     </div>
     <div style="border:1px solid var(--side-nav-main)">
     <div class="title_1 title panel-heading" style="text-align:left;">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Report Preview</p>
                  <div class="align-right" style="margin-top:-33px;" id="print">
               <div class="dt-buttons"> 
                    <button class="dt-button buttons-excel buttons-html5" aria-controls="data-table" onclick="exportexcel('xlsx')" type="submit"><span><img data-toggle="tooltip" title="Export to EXCEL" style="width:100%;" src="https://img.icons8.com/color/48/000000/ms-excel.png"></span></button> 
                    <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="data-table" type="button" onclick="printpdf()"><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/pdf.png"></span></button> 
               </div>
          </div>
                  <!-- <div class="align-right" style="margin-top:-33px;" id="buttons"></div> -->
               </div>
             <table class="data-table collection-report-data" id="data-table" cellpadding="0" border="1" cellspacing="0" class="table" style="width:100%;">
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
$(document).ready(function(){
    // $("#missed-branch").val('ALL');
     $("#missed-customer").val('ALL');
     $("#missed-cc").val('ALL');
     
    $("#missed-cc").keyup(function(){
     var missed = $('#missed-cc').val();
     if(missed==''){
          $("#missed-cc").val('ALL');
       }
    }); 
   
    $("#missed-customer").keyup(function(){
     var missedcustomer = $('#missed-customer').val();
     if(missedcustomer==''){
          $("#missed-customer").val('ALL');
       }
    });
})
</script>
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

var customercode =[<?php  

$brname=$db->query("SELECT cust_code FROM customer WHERE comp_code='$stationcode'");
while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrname['cust_code']."',";
}

?>];

var cccode =[<?php  

$cccode =$db->query("SELECT cc_code FROM collectioncenter WHERE stncode='$stationcode'");
while($rowcccode=$cccode->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowcccode['cc_code']."',";
}

?>];
 </script>
 <script>
 autocomplete(document.getElementById("missed-branch"), branchcode);
 autocomplete(document.getElementById("missed-customer"), customercode);
 //autocomplete(document.getElementById("missed-cc"), cccode);

</script>

<script>
     $('#filter-next').click(function(){
          if($('#missed-report-type').val().trim()==''){
               swal("Please Select Report Type");
          }
          else{

               progress();
          var missed_branch = $('#missed-branch').val();
          var missed_customer   = $('#missed-customer').val();
          var missed_report_type   = $('#missed-report-type').val();
          var missed_station   = $('#stncode').val();
          var miss_date_from = $('#book-date-from').val();
          var miss_date_to   = $('#book-date-to').val();
          var miss_month_from  = $('#inv-month-from').val();
          var missed_cc       =    $('#missed-cc').val();
          var miss_month_to  = $('#inv-month-to').val();
          var miss_year = $('#inv-year').val();
          var missed_serial = $('#missed-serial').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>reportcontroller",
            method:"POST",
            data:{missed_branch,missed_customer,missed_report_type,missed_station,miss_date_from,miss_date_to,miss_month_from,miss_month_to,miss_year,missed_serial,missed_cc},
            dataType:"text",
            success:function(data)
            {
               var miss_date_from = $('#book-date-from').val();
               var miss_date_to   = $('#book-date-to').val();
               stopprogress();
               $('.collection-report-data').html(data);             

               if(missed_report_type=='custau')
               {
               $('.preview-heading').html('Preview of Cnote entries in Customer Allotment Vs Usage Between '+miss_date_from+' and '+miss_date_to);
               $('#report-title').html('Summary of Missing Report Between '+miss_date_from+' and '+miss_date_to);
               }
               if(missed_report_type=='custub')
               {
               $('.preview-heading').html('Preview of Cnote entries in Customer Used Vs Billed Between '+miss_date_from+' and '+miss_date_to);
               $('#report-title').html('Summary of Missing Report Between '+miss_date_from+' and '+miss_date_to);
               }
               if(missed_report_type=='branchau')
               {
               $('.preview-heading').html('Preview of Cnote entries in Branch Allotment Vs Usage Between '+miss_date_from+' and '+miss_date_to);
               $('#report-title').html('Summary of Missing Report Between '+miss_date_from+' and '+miss_date_to);
               }
               if(missed_report_type=='branchub')
               {
               $('.preview-heading').html('Preview of Cnote entries in Branch Used Vs Billed Between '+miss_date_from+' and '+miss_date_to);
               $('#report-title').html('Summary of Missing Report Between '+miss_date_from+' and '+miss_date_to);
               }
               if(missed_report_type=='Unallocated')
               {
               $('.preview-heading').html('Preview of Unallocated Cnote entries from operations Between '+miss_date_from+' and '+miss_date_to);
               $('#report-title').html('Summary of Missing Report Between '+miss_date_from+' and '+miss_date_to);
               }
               if(missed_report_type=='UnKnown')
               {
               $('.preview-heading').html('Preview of UnKnown Cnote entries from operations Between '+miss_date_from+' and '+miss_date_to);
               $('#report-title').html('Summary of Missing Report Between '+miss_date_from+' and '+miss_date_to);
               }
               if(missed_report_type=='UnInvoiced')
               {
               $('.preview-heading').html('Preview of Un-invoiced Cnote entries Between '+miss_date_from+' and '+miss_date_to);
               $('#report-title').html('Summary of Missing Report Between '+miss_date_from+' and '+miss_date_to);
               }
               
            }
            });
          }
     });
</script> 
<script>
     function generatereport(){
          if($('#missed-report-type').val().trim()==''){
               swal("Please Select Collection Date Range");
          }
          else{
            //tab event
               $('.collection-report-data').html("");
               $('#rep_li').attr('class','active');
               $('#gen1_li').attr('class','');
               $('#rep_a').attr('data-toggle','tab');
               $('#filter-next').attr('href','#report');
          }
     }
</script>
<script>
     function printpdf(){
          var divToPrint=document.getElementById("data-table");
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
     var branch = '<?=$sessionbr_code?>';
     $('#missed-report-type').change(function(){
          if($(this).val()=='custau' && !branch=='' || $(this).val()=='custub' && !branch=='' || $(this).val()=='branchau' && !branch=='' || $(this).val()=='branchub' && !branch=='' || $(this).val()=='Unallocated' && !branch=='' || $(this).val()=='UnKnown' && !branch=='' || $(this).val()=='UnUsedCC' && !branch==''){
               $('#missed-branch').attr('readonly',true);
               $('#missed-customer').val('ALL');

          }else{
               $('#missed-branch').attr('readonly',false);
               $('#missed-customer').attr('readonly',false);
               $('#missed-customer').val('ALL');
               $('#missed-branch').val('ALL');
          }

          

          if($(this).val()=='UnUsedCC'){
             
               $('.customer-filter').css('display','none');
               $('.cc-filter').css('display','');
          } else {
               $('.customer-filter').css('display','');
               $('.cc-filter').css('display','none');
          }
     });
 });
</script>

<script>
 $(document).ready(function(){
     var branch = '<?=$sessionbr_code?>';
   if(branch==''){
     $('#missed-branch').val('ALL');
   }
 });
</script>



<script>
     $(document).ready(function(){
          var branch = $("#missed-branch").val();    
          var company = '<?=$stationcode?>';
          $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data: { company,branch,action : 'missbranch'},
       success:function(missbranch)
       {
          data = JSON.parse(missbranch);
          console.log(data);
           autocomplete(document.getElementById("missed-cc"), data[0]); 
           autocomplete(document.getElementById("missed-customer"), data[1]);
         
       }
   });
     });
</script>


<script>
function fetchcustomers(branch10){
   var comp8 = '<?=$stationcode?>';
   var branch = $("#missed-branch").val();
   console.log(branch);
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{branch10:branch10,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
          console.log(cust8);
           autocomplete(document.getElementById("missed-cc"), cust8[0]); 
           autocomplete(document.getElementById("missed-customer"), cust8[1]);
         
       }
   });
   }
</script>
<script> 
function validatebranch(brcode){
     console.log(brcode);
     if(branchcode.includes(brcode)){
          fetchcustomers(brcode)
     }
}
</script>

