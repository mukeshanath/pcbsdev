<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'demoreport';
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

  $sessionbr_code = $datafetch['br_code'];

  ?>
  <style>
     .type-filter table tr td,.date-filter table tr td,.weight-filter table tr td,.destination-filter table tr td{
          border-top: none;
     }
     .type-filter, .date-filter,.weight-filter,.destination-filter{
          text-align: left;
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
     /* styles for pdfheader */
     .left-contents{
         height: 300px;
         background-color: antiquewhite;
     }
     .right-contents{
          height:300px;
          background-color: aliceblue;
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
                                        <a href="<?php echo __ROOT__ . 'bookingreport'; ?>">Booking Report Filters</a>
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
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Booking Report</p>
          
    <div class="align-right" style="margin-top:-35px;">
<!--                                        
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="printData()">
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
   <input type="text" id="stncode" class="hidden" name="bookstn" value="<?=$stationcode;?>">

      <div class="tab-content">
          <!-- Tab Form 1 -->
     <div id="filters" class="tab-pane active" style="min-height:250px;">
     <div class="type-filter col-sm-3">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title">Report Name</td></tr>
        <tr>		
        <td><select class="form-control" id="report-type" name="book_rep_type">
            <option value="">Select Type</option>     
            <option value="bookcustomers">Customers<Code>
            <option value="bookcredit">Credits<Code>

            </Code></option>     
           
            </select>
            </td>
            
       </tr>
       <tr><td class="filter-title">BrCode</td>
        <td></td>
        <td class="filter-title">CustCode</td>
        <td></td>
        <td class="filter-title">Destination</td>
        <td></td>

     </tr>
        <tr>	

            <td>
            <div class="autocomplete">  
               <input type="text" class="form-control" id="br_brcode" name="br_brcode" <?=(!empty($sessionbr_code))?'readonly':''?> value="<?=(!empty($sessionbr_code))?$sessionbr_code:'ALL'?>" onkeyup="validatebranch(this.value)">
            </div>   
           </td>

            <td></td>
            <td>               
            <div class="autocomplete">  
                 <input type="text" class="form-control" id="br_custcode" name="br_custcode" value="ALL">
                 </div>
               </td>

               <td></td>
               <td>               
            <div class="autocomplete">  
                 <input type="text" class="form-control" id="destination-val" name="destination" value="">
                 </div>
               </td>

    </table>
    </div>
     <div class="date-filter col-sm-4">
     <table cellpadding="0" border="0" cellspacing="0" class="table" style="width:100%" >
	   <tr><td class="filter-title" colspan="3">Booking Date Range</td></tr>
        <tr>		
            <td><input type="date" class="form-control today" id="book-date-from" name="book_date_from"></td>
            <td>to</td>
              
            <td><input type="date" class="form-control today" id="book-date-to" name="book_date_to"></td>
            <td>
           
            </td>
       </tr>
    </table>
    </div>
    <div class="weight-filter col-sm-4">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title" colspan="3">Weight Range</td></tr>
        <tr>		
            <td><input type="number" class="form-control" id="book-weight-from" value="" name="weight_from"></td>
            <td>to</td>
              
            <td><input type="number" class="form-control" id="book-weight-to" value=""name="weight_to"></td>
            <td>
            
            </td>
       </tr>
    </table>
    </div>
     
     <div class="align-right col-sm-1" style="float:right;margin-top:100px;margin-right:25px; ">
     <button type="button" class="btn btn-success" data-toggle="tab" id="filter-next" onclick="generatereport();">
              Generate Report
               </button>
     </div>
     </div>
        <!-- Second Tab --> 
     <div id="report" class="tab-pane">
     <div class="report-header" style="border-bottom:2px solid black;margin-bottom:10px">
          <div class="date-between-column" style="text-align:left;">
               <h5 class="preview-heading">Preview of Booking between <span id="head-date-from"></span> and <span id="head-date-to"></span> Summary</h5>
          </div>
     </div>
     <div style="border:1px solid var(--side-nav-main)">
     <div class="title_1 title panel-heading" style="text-align:left;">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Report Preview</p>
          <div class="align-right" style="margin-top:-33px;" id="print">
          <div class="dt-buttons"> 
               <button class="dt-button buttons-excel buttons-html5" aria-controls="data-table" onclick="exportexcel('xlsx')" type="button"><span><img data-toggle="tooltip" title="Export to EXCEL" style="width:100%;" src="https://img.icons8.com/color/48/000000/ms-excel.png"></span></button> 
               <button class="dt-button buttons-pdf buttons-html5"  aria-controls="data-table" type="button" onclick="printpdf()"><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/pdf.png"></span></button> 
			   <button class="dt-button buttons-pdf buttons-html5" id="getpdf" aria-controls="data-table" type="button" ><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/download-pdf.png"></span></button> 

          </div>
         </div>
               
</div>
<div class="scrollable2">
     <table class="data-table booking-report-data" id="data-table1" cellpadding="0" border="1" cellspacing="0" class="table" style="width:110%;">
          <thead>
          <tr>
               <th>S.no</th>
               <th>Cno</th>
               <th>Branch</th>
               <th>Customer</th>
               <th>Weight</th>
               <th>Mode</th>
               <th>Destination</th>
               <th>Origin</th>
               <th>Booking Date</th>
               <th>Amount</th>
          </tr>
          </thead>
          <tbody id="booking-report-data">

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
     $("#cc_code").val('ALL');
    // $("#br_brcode").val('ALL');
    $("#cc_code").keyup(function(){
     var cc_code = $('#cc_code').val();
     if(cc_code==''){
          $("#cc_code").val('ALL');
       }
    });
//     $("#br_brcode").keyup(function(){
//      var branch = $('#br_brcode').val();
//      if(branch==''){
//           $("#br_brcode").val('ALL');
//        }
//     });
});
</script>
        <script>
     $('#report-type').change(function(){
          if($(this).val()=='CCbookingSummary' || $(this).val()=='CCpendingSummary'){
               $('#book-weight-from').attr('readonly',true);
               $('#book-weight-to').attr('readonly',true);
               $('#br_custcode').attr('readonly',true);
               $('#destination-val').attr('readonly',true);
               $('#zone-val').attr('readonly',true);
               $('#bkrpt_podorigin').attr('readonly',true);
          }else{
               $('#book-weight-from').attr('readonly',false);
               $('#book-weight-to').attr('readonly',false);
               $('#br_custcode').attr('readonly',false);
               $('#destination-val').attr('readonly',false);
               $('#zone-val').attr('readonly',false);
               $('#bkrpt_podorigin').attr('readonly',false);
     
          }
     });
</script>

</script>
        <script>
     $('#report-type').change(function(){
          if($(this).val()=='bookcredit' || $(this).val()=='bookinbound' || $(this).val()=='credittally'){
               $('#cc_code').attr('readonly',true);    
          }else{
               $('#cc_code').attr('readonly',false);  
          }
     });
</script>

<script>
     $('#report-type').change(function(){
          if($(this).val()=='bookcustomers'){
               $('#book-weight-from').attr('readonly',true);  
               $('#book-weight-to').attr('readonly',true);      
          }else{
               $('#book-weight-from').attr('readonly',false);  
               $('#book-weight-to').attr('readonly',false);  

          }
     });
</script>

<script>
     $('#report-type').change(function(){
          if($(this).val()=='bookcustomers'){
               $('#destination-val').attr('readonly',true);    
          }else{
               $('#destination-val').attr('readonly',false);  
          }
     });
</script>


<script>
     $('#report-type').change(function(){
          if($(this).val()=='bookcash' || $(this).val()=='cashtally'){
               $('#payment_mode').attr('readonly',false); 
          }else{
               $('#payment_mode').attr('readonly',true); 
          }
     });
</script>
<script>
     $('#report-type').change(function(){
          if($(this).val()=='bookcash' || $(this).val()=='cashtally'){
               $('#payment_mode').show();
               $('#payment_mode_heading').show();
               
          }else{
               $('#payment_mode').hide();
               $('#payment_mode_heading').hide();
          }
     });
</script>
</script>
        <script>
     $('#report-type').change(function(){
          if($(this).val()=='overallbookcash' || $(this).val()=='bookcash' || $(this).val()=='CCbookingSummary' || $(this).val()=='CCpendingSummary' || $(this).val()=='cashtally'){
               $('#br_custcode').attr('readonly',true);    
          }else{
               $('#br_custcode').attr('readonly',false);  
          }
     });
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
     $('#filter-next').click(function(){
          if($('#book-date-from').val().trim()=='' || $('#book-date-to').val().trim()==''){
               swal("Please Select Booking Date Range");
          }
          else{
               progress();
          var book_date_from = $('#book-date-from').val();
          var book_date_to   = $('#book-date-to').val();     
          var weight_from     = $('#book-weight-from').val();
          var br_brcode     = $('#br_brcode').val();
          var br_custcode     = $('#br_custcode').val();
          var weight_to  = $('#book-weight-to').val();
          var destination = $('#destination-val').val();
          var book_rep_type = $('#report-type').val();
          var bkrpt_podorigin = $('#bkrpt_podorigin').val();
          var zone = $('#zone-val').val();
          var bookstn = $('#stncode').val();
          var cc_code = $('#cc_code').val();
          var current_station = '<?=$stationcode?>';
		var payment_mode  = $("#payment_mode").val();
          
          $.ajax({
            url:"<?php echo __ROOT__ ?>demoreportcontroller",
            method:"POST",
            data:{book_date_from
               ,book_date_to
               ,weight_from
               ,weight_to
               ,destination
               ,zone
               ,book_rep_type
               ,bkrpt_podorigin
               ,bookstn
               ,br_custcode
               ,cc_code
               ,current_station
			,payment_mode
               ,br_brcode},
            dataType:"text",
            success:function(data)
            {
               console.log(data);
               //console.log(data);
               stopprogress();
               $('.booking-report-data').html(data);
               if(book_rep_type=='bookcredit'){
               $('.preview-heading').html('Credit Booking Report Between '+book_date_from+' and '+book_date_to);
               
               $('#report-title').html('Statement of Booking Entry Between '+book_date_from+' and '+book_date_to);
            }

            if(book_rep_type=='bookcustomers'){
               $('.preview-heading').html('Customers Report Between '+book_date_from+' and '+book_date_to);
               
               $('#report-title').html('Statement of Booking Entry Between '+book_date_from+' and '+book_date_to);
            }
              
            }
            });
          }
     });
</script>



<script>
     $('#getpdf').click(function(){
          console.log($('#report-type').val().trim().length);
          if($('#book-date-from').val().trim()=='' || $('#book-date-to').val().trim()==''){
               swal("Please Select Booking Date Range");
          }
          
          else{
               progress();
          var book_date_from = $('#book-date-from').val();
          var book_date_to   = $('#book-date-to').val();
          var weight_from     = $('#book-weight-from').val();
          var br_brcode     = $('#br_brcode').val();
          var br_custcode     = $('#br_custcode').val();
          var weight_to  = $('#book-weight-to').val();
          var destination = $('#destination-val').val();
          var book_rep_type = $('#report-type').val();
          var bkrpt_podorigin = $('#bkrpt_podorigin').val();
          var zone = $('#zone-val').val();
          var bookstn = $('#stncode').val();
          var cc_code = $('#cc_code').val();
          var mode  = $('#mode_code').val();
          var current_station = '<?=$stationcode?>';
		  var payment_mode  = $("#payment_mode").val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>demoreportcontroller",
            method:"POST",
            data:{book_date_from
               ,book_date_to
               ,weight_from
               ,weight_to
               ,destination
               ,zone
               ,book_rep_type
               ,bkrpt_podorigin
               ,bookstn
               ,br_custcode
               ,cc_code
               ,current_station
			   ,payment_mode
                  ,mode
               },
            dataType:"text",
            success:function(data)
            { 
               stopprogress();
              
               window.open('<?php echo __ROOT__ ?>cashdocs?book_date_from='+book_date_from+'&book_date_to='+book_date_to+'&weight_from='+weight_from+'&weight_to='+weight_to+'&destination='+destination+'&zone='+zone+'&book_rep_type='+book_rep_type+'&bkrpt_podorigin='+bkrpt_podorigin+'&bookstn='+bookstn+'&br_custcode='+br_custcode+'&cc_code='+cc_code+'&current_station='+current_station+'&payment_mode='+payment_mode+'&br_brcode='+br_brcode+'');
            }
            });
          }
     });
</script>
<script>
     function generatereport(){
          if($('#book-date-from').val().trim()=='' || 
             $('#book-date-to').val().trim()==''){
               swal("Please Select Booking Date Range");
          }

          else if($('#report-type').val().trim()==''){
               $('#filter-next').attr('href','');
               swal("Please Select type of report");
          }
          
          else if($('#book-weight-from').val().trim().length >= 1 && $('#book-weight-to').val().trim().length ==0  
           
           || $('#book-weight-from').val().trim().length == 0 && $('#book-weight-to').val().trim().length  >= 1){
               $('#filter-next').attr('href','');
               swal("Please Select weight Range");
            } 

            else if($('#book-weight-from').val().trim()  > 
                    $('#book-weight-to').val().trim()){
               
               $('#filter-next').attr('href','');
               swal("Check weight range From is greater than to ");
            } 

         
          else{
          $('.booking-report-data').html("");
          $('#rep_li').attr('class','active');
          $('#gen1_li').attr('class','');
          $('#rep_a').attr('data-toggle','tab');
          $('#filter-next').attr('href','#report');
          }
          
     }
</script>
<script>
var stationcode =[<?php  

$brcode=$db->query("SELECT dest_code FROM destination WHERE comp_code='$stationcode'");
while($rowbrcode=$brcode->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrcode['dest_code']."',";
}

?>];


 </script>
 <script>
 autocomplete(document.getElementById("destination-val"), stationcode);
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
 autocomplete(document.getElementById("br_brcode"), branchcode);

</script>
<script>
function fetchcustomers(branch8){
   var comp8 = '<?=$stationcode?>';
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("br_custcode"), cust8[0]); 
           autocomplete(document.getElementById("cc_code"), cust8[1]);
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
<script>
     function printpdf(){
          var divToPrint=document.getElementById("data-table1");
          var headToPrint=document.getElementById("pdfheader");
          newWin= window.open("");
          newWin.document.write('<style>  table tr td{padding: 3px !important;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 13px;} table tr th{border-bottom:1px solid black;border-top:1px solid black;text-align:left;} </style>');
          newWin.document.write('<head><title>Booking Report</title></head>');
          newWin.document.write(headToPrint.outerHTML);
          newWin.document.write(divToPrint.outerHTML);
          newWin.print();
          //newWin.close();
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
     fetchcustomers('<?=$sessionbr_code?>');
  });
</script>

<script>

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
</script>