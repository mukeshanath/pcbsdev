<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'gstreport';
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
     .scrollable2 {
     width: 100%;
     overflow-x: scroll;
     border-bottom: 1px solid #ddd;
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
                                        <a href="<?php echo __ROOT__ . 'gstreport'; ?>">GST Report Filters</a>
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
   <input type="text" id="gst_stn" class="hidden" name="gst_stn" value="<?=$stationcode;?>">
  
	    
    <div class="tab-content">
     <!-- Tab Form 1 -->
     <div id="filters" class="tab-pane active" style="min-height:150px;">
     <div class="type-filter col-sm-3">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title">Report Name</td></tr>
        <tr>		
            <td>
            <select class="form-control" id="gstreporttype" name="gstreporttype">
               <option value="">Select Type</option>     
               <option value="creditb2b">Credit B2B</option>     
               <option value="creditb2cs">Credit B2CS</option>     
               <!-- <option value="cashb2b">Cash B2B</option>      -->
               <option value="cashb2cs">Cash B2CS</option>     
               <option value="cnotesles_gst_b2b">Cnote Sales - B2B</option>     
               <option value="cnotesles_gst_b2cs">Cnote Sales - B2CS</option>     
               <option value="gstsummary">GST Summary</option>
            </select>
            </td>
       </tr>
    </table>
    </div>
     <!-- <div class="date-filter col-sm-4">
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
    </div> -->
     <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Transaction Date Range</td></tr>
        <tr>		
            <td><input type="date" class="form-control today" name="gst_date_from" id="gst_date_from" ></td>
            <td>to</td>
              
            <td><input type="date" class="form-control today" name="gst_date_to" id="gst_date_to"></td>
            <td>
           
            </td>
       </tr>
    </table>
    </div>
     <!-- <div class="month-filter col-sm-4" style="display:none">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Transaction Month Range</td><td class="filter-title">Year</td></tr>
        <tr>		
            <td><select class="form-control" name="inv_month_from" id="inv-month-from">
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
              
            <td><select type="date" class="form-control" name="inv_month_to" id="inv-month-to">
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
           <select class="form-control" id="inv-year" name="inv_year">
           </select>
            </td>
       </tr>
    </table>
    </div> -->
   
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
     <table class="data-table gst-report-table" id="data-table1" cellpadding="0" border="1" cellspacing="0" class="table" style="width:100%;">
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
          if($('#gst_date_from').val().trim()=='' && $('#gst_date_from').val().trim()==''){               
               swal("Please Select GST Date Range");
          }
          else{
               progress();  
          var gst_stn   = $('#gst_stn').val();
          var gstreporttype   = $('#gstreporttype').val();
          var gst_date_from     = $('#gst_date_from').val();
          var gst_date_to     = $('#gst_date_to').val();
         
          $.ajax({
            url:"<?php echo __ROOT__ ?>reportcontroller",
            method:"POST",
            data:{gst_stn,gstreporttype,gst_date_from,gst_date_to,gst_branch:'',gst_customer:''},
            dataType:"text",
            success:function(data)
            {
               stopprogress();
              // var months = ["zero","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            $('.gst-report-table').html(data);
            if(gstreporttype=='creditb2b'){
            $('.preview-heading').html('Credit B2B Between '+gst_date_from+' and '+gst_date_to);
            $('#report-title').html('Credit B2B Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(gstreporttype=='creditb2cs'){
            $('.preview-heading').html('Credit B2CS Between '+gst_date_from+' and '+gst_date_to);
            $('#report-title').html('Credit B2CS Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(gstreporttype=='cashb2cs'){
            $('.preview-heading').html('Cash B2CS Between '+gst_date_from+' and '+gst_date_to);
            $('#report-title').html('Cash B2CS Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(gstreporttype=='gstsummary'){
            $('.preview-heading').html('GST Summary Between '+gst_date_from+' and '+gst_date_to);
            $('#report-title').html('GST Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(gstreporttype=='cnotesles_gst_b2b'){
            $('.preview-heading').html('Cnote Sales B2B Summary Between '+gst_date_from+' and '+gst_date_to);
            $('#report-title').html('GST Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            else if(gstreporttype=='cnotesles_gst_b2cs'){
            $('.preview-heading').html('Cnote Sales B2CS Summary Between '+gst_date_from+' and '+gst_date_to);
            $('#report-title').html('GST Summary Report as on '+new Date().toISOString().split('T')[0]);
            }
            }
            });
          }
     });
</script>
<script>
     function generatereport(){
          if($('#gst_date_from').val().trim()=='' && $('#gst_date_from').val().trim()==''){
               swal("Please Select Report Date Range");
               return false;
          }
          else if($('#report-type').val()==''){
               swal("Please Select Report Type");
          }
          else{
          $('.gst-report-table').html("");
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
          newWin.document.write('<head><title>GST Report</title></head>');
          newWin.document.write(headToPrint.outerHTML);
          newWin.document.write(divToPrint.outerHTML);
          newWin.print();
          
     }
</script>
<script>
function exportexcel(type, fn, dl){
     //data-table
     var sel = document.getElementById("gstreporttype");//report type option             
     var elt = document.getElementById('data-table1'); //table output to make excel
       var report_type = sel.options[sel.selectedIndex].text; 
       var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet 1",header:1,raw:false,dateNF:'dd-MMM-yyyy'});
       return dl ?
       XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
       XLSX.writeFile(wb, fn || (report_type+'.' + (type || 'xlsx')));
}
</script>