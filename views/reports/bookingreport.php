<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'bookingreport';
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
            <option value="bookcredit">Credit Report</option>     
            <option value="bookinbound">BA Inbound Summary</option> 
            <option value="overallbookcash">Cash Summary</option>     
            <option value="bookcash">Cash Report</option>    
            <option value="CCbookingSummary">CC Booking Summary</option>     
            <option value="CCpendingSummary">CC pending Summary</option>  
            <option value="gta_report">GTA Report</option>  
            <option value="conbinereport">Combine Report</option>  
            <option value="cashtax">Cash Tax Summary Report</option>  
            <option value="cashtally">Cash Tally Report</option> 
            <option value="credittally">Credit Tally Report</option> 
            </select>
            </td>
       </tr>
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
            <td><input type="number" class="form-control" id="book-weight-from" name="weight_from"></td>
            <td>to</td>
              
            <td><input type="number" class="form-control" id="book-weight-to" name="weight_to"></td>
            <td>
            
            </td>
       </tr>
    </table>
    </div>
     <div class="destination-filter col-sm-4">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr><td class="filter-title">BrCode</td>
        <td></td>
        <td class="filter-title">CustCode</td>
        <td></td>
        <td class="filter-title">CC Code</td></tr>
        <tr>		
            <td>
            <div class="autocomplete">  
               <input type="text" class="form-control" id="br_brcode" name="br_brcode" <?=(!empty($sessionbr_code))?'readonly':''?> value="<?=(!empty($sessionbr_code))?$sessionbr_code:'ALL'?>" onkeyup="validatebranch(this.value)">
            </div>   
           </td>
            <td></td>
              
            <td>               
            <div class="autocomplete">  
                 <input type="text" class="form-control" id="br_custcode" name="br_custcode">
                 </div>
</td>
<td></td>
              
            <td>
            <div class="autocomplete">  
                 <input type="text" class="form-control" style="width:150px;" id="cc_code" name="cc_code">
                 </div>
</td>
            <td>
            </td>
       </tr>
    </table>
    </div>
     <div class="destination-filter col-sm-6">
     <table cellpadding="0" cellspacing="0" class="table" style="width:100%">
	   <tr>
             <td class="filter-title">Destination</td>
             <td></td>
             <td class="filter-title">Zone</td>
             <td></td>
             <td class="filter-title" style="width:200px">POD Origin</td>
			 <td></td>
             <td class="filter-title" id="payment_mode_heading" style="width:170px">Payment Mode</td>
          </tr>
        <tr>		
            <td>
            <div class="autocomplete">  
            <input type="text" class="form-control" id="destination-val" name="destination">
            </div>   </td>
            <td></td>
            <td>
                 <input type="text" class="form-control" id="zone-val" name="zone">
               </td>
               <td></td>
            <td>
           <select name="bkrpt_podorigin" class="form-control" id="bkrpt_podorigin">
                <option value='ALL'>ALL</option>
                <option value='<?=$stationcode?>'><?=$stationcode?></option>
                <option value='PRO'>PRO</option>
                <option value='PRC'>PRC</option>
                <option value='PRD'>PRD</option>
                <option value='COD'>COD</option>
               </select>
            </td>
			<td></td>
            <td>
           <select name="payment_mode" class="form-control" id="payment_mode">
                <option value='ALL'>ALL</option>
                <option value="Cash">Cash</option>
                <option value="NEFT">NEFT</option>
                <option value="Cheque">Cheque</option>
                <option value="UPI">UPI</option>
               </select>
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
            url:"<?php echo __ROOT__ ?>reportcontroller",
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
               stopprogress();
               $('.booking-report-data').html(data);
               
          
		if(book_rep_type=='bookcash'){
               $('#getpdf').show();
            }else{
               $('#getpdf').hide();
            }
            if(book_rep_type=='bookcredit'){
               $('.preview-heading').html('Credit Booking Report Between '+book_date_from+' and '+book_date_to);
               $('#report-title').html('Statement of Booking Entry Between '+book_date_from+' and '+book_date_to);
            }
            if(book_rep_type=='cashtally'){
               $('.preview-heading').html('Cash Tally Report Between '+book_date_from+' and '+book_date_to);
               $('#report-title').html('Statement of Booking Entry Between '+book_date_from+' and '+book_date_to);
            }
            if(book_rep_type=='credittally'){
               $('.preview-heading').html('Credit Tally Report Between '+book_date_from+' and '+book_date_to);
               $('#report-title').html('Statement of Booking Entry Between '+book_date_from+' and '+book_date_to);
            }

            if(book_rep_type=='bookcash'){
               $('.preview-heading').html('Cash Booking Report Between '+book_date_from+' and '+book_date_to);
               $('#report-title').html('Statement of Booking Entry Between '+book_date_from+' and '+book_date_to);
             
            }
            
            if(book_rep_type=='overallbookcash'){
               $('.preview-heading').html('Cash Booking Summary Between '+book_date_from+' and '+book_date_to);
               $('#report-title').html('Statement of Cash Booking Summary Between '+book_date_from+' and '+book_date_to);
            }
            
            if(book_rep_type=='bookinbound'){
               $('.preview-heading').html('BA Inbound Summary Between '+book_date_from+' and '+book_date_to);
               $('#report-title').html('Statement of Booking Entry Between '+book_date_from+' and '+book_date_to);
            }
            
            if(book_rep_type=='CCbookingSummary'){
                   sum =0;
$('.loop').each(function(){
sum += parseFloat($(this).text());

});

cancel =0;
$('.cancel').each(function(){
     cancel += parseFloat($(this).text());
});

bil =0;
$('.total').each(function(){
bil += parseFloat($(this).text());
});

miss =0;
$('.miss').each(function(){
     miss += parseFloat($(this).text());
});
$('.preview-heading').html((br_brcode==='ALL') ? 'CC Booking Summary Between '+book_date_from+' and '+book_date_to+'<br>'+ '<?php echo $stationaname;  ?>'+'<br><p style="color:#000;font-size:16px;margin-top:10px;"><b>Cancel  : </b>' +cancel+ '&nbsp;&nbsp;<b>Amount :</b> '+miss + '&nbsp;&nbsp;<b> Balance :</b> '+ bil + '&nbsp;&nbsp;<b> Missing :</b> ' + sum+'<p>':'CC Booking Summary Between '+book_date_from+' and '+book_date_to+'<br>'+ '<?php echo $stationaname;   ?>'+'<br>'+ '<?php echo $branch1.", &nbsp; Ph :".$mobile;   ?>'+'<br><p style="color:#000;font-size:16px;margin-top:10px;"><b>Cancel  : </b>' +cancel+ '&nbsp;&nbsp;<b>Amount :</b> '+miss + '&nbsp;&nbsp;<b> Balance :</b> '+ bil + '&nbsp;&nbsp;<b> Missing :</b> ' + sum+'</p>');
               $('#report-title').html('Statement of Booking Entry Between '+book_date_from+' and '+book_date_to);  
			   $("#summary1").html('<b>Summary :</b>&nbsp;&nbsp;&nbsp;<b>Cancel  : </b>' +cancel+ '&nbsp;&nbsp;<b>Amount :</b> '+sum + '&nbsp;&nbsp;<b> Balance :</b> '+ bil + '&nbsp;&nbsp;<b> Missing :</b> ' + miss);


                  
 
// $('#Amount1').html('<b>'+totalamount+'.00');
$('#Amount1').html('<b>'+sum+'</b>');
$('#cancel1').html('<b>'+cancel+'</b>');
$('#total1').html('<b>'+bil+'</b>');
$('#miss1').html('<b>'+miss+'.00</b>');

 }


            if(book_rep_type=='CCpendingSummary'){
                   sum =0;
$('.loop').each(function(){
sum += parseFloat($(this).text());

});

cancel =0;
$('.cancel').each(function(){
     cancel += parseFloat($(this).text());
});

bil =0;
$('.total').each(function(){
bil += parseFloat($(this).text());
});

miss =0;
$('.miss').each(function(){
     miss += parseFloat($(this).text());
});
     $('.preview-heading').html('CC Pending Summary Between '+book_date_from+' and '+book_date_to);
               $('#report-title').html('Statement of CC Pending Entries Between '+book_date_from+' and '+book_date_to);
               

                  
 
// $('#Amount1').html('<b>'+totalamount+'.00');
$('#Amount1').html('<b>'+sum+'</b>');
$('#cancel1').html('<b>'+cancel+'</b>');
$('#total1').html('<b>'+bil+'</b>');
$('#miss1').html('<b>'+miss+'</b>');







            }
            
            if(book_rep_type=='gta_report'){
                $('.preview-heading').html('CC Gta Summary Between '+book_date_from+' and '+book_date_to);
                $('#report-title').html('Statement of Gta Entries Between '+book_date_from+' and '+book_date_to);
                     }
                     if(book_rep_type=='conbinereport'){
                         $('.preview-heading').html('Combined Report Between '+book_date_from+' and '+book_date_to);
                         $('#report-title').html('Statement of Combined Report Entries Between '+book_date_from+' and '+book_date_to);
                     }
                     if(book_rep_type=='cashtax'){
               $('.preview-heading').html('Cash Tax Summary Report Between '+book_date_from+' and '+book_date_to);
               $('#report-title').html('Statement of Cash Tax Summary Report Entries Between '+book_date_from+' and '+book_date_to);
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
            url:"<?php echo __ROOT__ ?>reportcontroller",
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
          if($('#book-date-from').val().trim()=='' || $('#book-date-to').val().trim()==''){
               swal("Please Select Booking Date Range");
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