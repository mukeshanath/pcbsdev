<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'newbusinessreport';
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
  <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


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
                                        <a href="<?php echo __ROOT__ . 'newbusinessreport'; ?>">New Business Report Filters</a>
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
        <form method="POST" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="formbranch" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">New Business Report</p>
          
    <div class="align-right" style="margin-top:-35px;">
                                       
       <!-- <button type="button" class="btn btn-warning btnattr_cmn" onclick="printData()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>    -->

       <a style="color:white" data-toggle="tab" href="#filters" _href="<?php echo __ROOT__ . 'homepage'; ?>" onclick="$('#rep_li').attr('class','');$('#gen1_li').attr('class','active');">         
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
    <input type="text" id="newbs_station" class="hidden" name="newbs_station" value="<?=$stationcode;?>">
    <input type="text" id="newbs_user" class="hidden" name="newbs_user" value="<?=$_SESSION['user_name'];?>">

          <!-- Tab Form 1 -->
     <div id="filters" class="tab-pane active" style="min-height:250px;">
     <div class="type-filter col-sm-3">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title">Report Name</td></tr>
        <tr>		
               <td><select class="form-control" id="newbs_report_type">
            <option value="">Select Type</option>     
            <option value="B">BA New Business Summary</option>        
            <option value="F">FA New Business Summary</option>        
            <option value="D">DC New Business Summary</option>        
            </select>
            </td>
       </tr>
    </table>
    </div>
     <!-- <div class="type-filter col-sm-3">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title">Report Format</td></tr>
        <tr>		
               <td><select class="form-control" id="newbs_report_format" onchange="if($(this).val()=='monthly'){$('.date-filter').css('display','none');$('.month-filter').css('display','block')}else{$('.date-filter').css('display','block');$('.month-filter').css('display','none')}">
            <option value="">Select Format</option>     
            <option value="datewise">Date Wise</option>        
            <option value="daterange">Date Range</option>        
            <option value="monthly">Monthly Report</option>        
            </select>
            </td>
       </tr>
    </table>
    </div> -->
     <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Date Range</td></tr>
        <tr>		
            <td><input type="date" class="form-control today" id="newbs_date_from"></td>
            <td>to</td>
              
            <td><input type="date" class="form-control today" id="newbs_date_to"></td>
            <td>
           
            </td>
       </tr>
    </table>
    </div>
     <div class="month-filter col-sm-4" style="display:none">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Month Range</td><td class="filter-title">Year</td></tr>
        <tr>		
            <td><select class="form-control" id="newbs_month_from">
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
              
            <td><select class="form-control" id="newbs_month_to">
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
           <select class="form-control" id="newbs_year">
           <option value="2020">2020</option>
           </select>
            </td>
       </tr>
    </table>
    </div>
    <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title">Branch</td><td class="filter-title">Customer</td></tr>
        <tr>		
            <td>
                 <div class="autocomplete"><input type="text" class="form-control" onfocusout="fetchcustomers()"  id="newbs_branch" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_code))?$br_code:''; ?>"></div></td>
              
            <td><div class="autocomplete"><input type="text" class="form-control" id="newbs_customer"></div></td>
       </tr>
    </table>
    </div>

   
     <div class="align-right" style="padding:10px;float:right;margin-top:100px">
     <button type="button" class="btn btn-success" data-toggle="tab" id="filter-next" onclick="generatereport()">    <!-- onclick="generatereport()" -->
              Generate Report
               </button>
     </div>
     </div>
        <!-- Second Tab --> 
     <div id="report" class="tab-pane">
     <div class="report-header" style="border-bottom:2px solid black;margin-bottom:10px">
          <div class="date-between-column" style="text-align:left;">
               <h5 class="preview-heading">Preview of New Business Report</h5>
          </div>
     </div>
     <div style="border:1px solid var(--side-nav-main)">
     <div class="title_1 title panel-heading" style="text-align:left;">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">New Business Report Preview</p>
                  <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
               </div>
               <div style="width:100%">
     <table class="data-table invoice-report-table" id="data-table1" cellpadding="0" border="1" cellspacing="0" class="table" style="min-width:100%;overflow-x:scroll">
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
          if($('#newbs_date_from').val().trim()==''){
               swal("Please Select Invoice Date Range");
          }
          else if($('#newbs_report_type').val()==''){
               swal("Please Select Report Type");
          }
          else{
               progress();
          var newbs_report_type = $('#newbs_report_type').val();
          var newbs_report_format   = $('#newbs_report_format').val();
          var newbs_date_from   = $('#newbs_date_from').val();
          var newbs_date_to   = $('#newbs_date_to').val();
          var newbs_month_from   = $('#newbs_month_from').val();
          var newbs_month_to   = $('#newbs_month_to').val();
          var newbs_year   = $('#newbs_year').val();
          var newbs_branch   = $('#newbs_branch').val();
          var newbs_customer   = $('#newbs_customer').val();
          var newbs_station   = $('#newbs_station').val();
          var newbs_user   = $('#newbs_user').val();
     
          $.ajax({
            url:"<?php echo __ROOT__ ?>reportcontroller",
            method:"POST",
            data:{newbs_report_type,newbs_report_format,newbs_date_from,newbs_date_to,newbs_month_from,newbs_month_to,newbs_year,newbs_branch,newbs_customer,newbs_station,newbs_user},
            dataType:"text",
            success:function(data)
            {
               stopprogress();
            $('.invoice-report-table').html(data);
          //   $('#head-date-from').html(inv_date_from);
          //   $('#head-date-to').html(inv_date_to);
            }
            });
          }
     });
</script>
<script>
     function generatereport(){
          if($('#newbs_date_from').val().trim()==''){
               swal("Please Select Invoice Date Range");
          }
          else if($('#newbs_report_type').val()==''){
               swal("Please Select Report Type");
          }
          else{
          $('#rep_li').attr('class','active');
          $('#gen1_li').attr('class','');
          $('#rep_a').attr('data-toggle','tab');
          $('#filter-next').attr('href','#report');
          }
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
 autocomplete(document.getElementById("newbs_branch"), branchcode);

</script>
<script>
function fetchcustomers(){
   var branch8 = $('#newbs_branch').val();
   var comp8 = $('#newbs_station').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("newbs_customer"), cust8); 
       }
   });
   }
</script>
<script>
function exportexcel(type, fn, dl){
     //data-table
     var sel = document.getElementById("report-type");//report type option             
     var elt = document.getElementById('data-table1'); //table output to make excel
       var report_type = sel.options[sel.selectedIndex].text; 
       var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet 1",header:1,raw:false,dateNF:'dd-MMM-yyyy'});
       return dl ?
       XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
       XLSX.writeFile(wb, fn || (report_type+'.' + (type || 'xlsx')));
}

$(window).on('load', function () {
          fetchcustomers();
  });
</script>