<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'branch';
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

  ?>
  <style>
     input.rounded {
         width:400px;
         height:40px;
     }
     .add-btn{
          height:40px;
     }
     .queryarea{
          width:440px;
          height:350px;
     }
     .query-label{
        margin-top: 60px;
     }
     .filters{
         padding: 20px;
         font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
         font-size: 18px;
         border: 1px solid #b0adad;
         border-radius: 9px;
         width:40%;
     }
     .filters .row{
         padding:10px;
     }
     .full-query-box{
        border: 1px solid #b0adad;
        border-radius: 9px;
        min-height: 170px;
        margin-top:25px;
        padding:10px;
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
                                        <a href="<?php echo __ROOT__ . 'queryreport'; ?>"> Report Filters</a>
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
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Report Form</p>
          
    <div class="align-right" style="margin-top:-35px;">
       <!-- <button type="submit" class="btn btn-success btnattr_cmn" id="addanbranch" name="addanbranch">
       <i class="fa fa-save"></i>&nbsp; Save & Add
       </button>

       <button type="submit" class="btn btn-primary btnattr_cmn" id="addbranch" name="addbranch">
       <i class="fa  fa-save"></i>&nbsp; Save
       </button>
                                                            -->
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="printData()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button>   

       <a style="color:white" href="<?php echo __ROOT__ . 'branchlist'; ?>">         
       <button type="button" class="btn btn-danger btnattr_cmn" >
       <i class="fa fa-times" ></i>
       </button></a>
    </div>
    </div> 
	<div class="content panel-body">
            <!-- Navigation Tabs -->
      <ul class="nav nav-tabs" id="myTab4">
        <li id="gen1_li" class="active">
            <a id="gen1_a" data-toggle="tab" href="#general1">General</a>
        </li>
        <li id="gen_li">
            <a id="gen_a" data-toggle="disabled" href="#general">Search</a>
        </li>
        <li id="rate_li">
            <a id="rate_a" data-toggle="disabled" href="#ratefactor">Query</a>
        </li>
        <li id="off_li">
            <a id="off_a" data-toggle="disabled" href="#office">Report</a>
        </li>
      </ul>
    
   <div class="tab_form" >

  
	    
    <div class="tab-content">
          <!-- Tab Form 1 -->
     <div id="general1" class="tab-pane active">
     <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	    <tbody>
        <tr>		
            <td>Report Id</td>
            <td >
                <div class="col-sm-12 autocomplete">
                     <input  class="form-control border-form"  name="report_id" type="text" value="REP00001" id="report_id" autocomplete="off" readonly>
                      
                   
                </div>
            </td>
              
            <td>Report Name</td>
            <td>
                <div class="col-md-12">
                    <input class="form-control border-form "  name="report_name" type="text" id="report_name" autofocus>
                           
                </div>
            </td>
       <!-- </tr>
       <tr>		 -->
            <td>Report Date</td>
            <td >
                <div class="col-sm-12 autocomplete">
                    <input class="form-control border-form br_code today" name="report_date" type="date" id="report_date" required>
                </div>
            </td>
              
            <td>Report By   </td>
            <td>
                <div class="col-md-12">
                <input class="form-control border-form" name="report_by" type="text" id="report_by" value="<?=$_SESSION['user_name'];?>">
                       
                </div>
            </td>
       </tr>
    </table>
     <div class='align-right'>
     <button type="button" class="btn btn-primary" data-toggle="tab" id="btn_gen1" onclick="search()" >
    Next &#8594;
    </button>
                </div>
     </div>
        <!-- Second Tab --> 
     <div id="general" class="tab-pane" style="margin-left: 25px;text-align:center">
     <div class="row">
     <span class="label label-primary"><h4>Search Fields</h4></span><br>
     <div class="autocomplete">
     <input type="text" class="rounded" id="searchfields" /></div><button class="add-btn" type="button">Add</button></div>
     <span class="label label-primary query-label"><h4>Fields on XML Form</h4></span><br>
     <textarea class="queryarea"></textarea><br>
     
     <div class='align-right'>
     <button type="button" class="btn btn-primary" data-toggle="tab" id="btn_rate" onclick="rate()" >
    Next &#8594;
    </button>
                </div>
     </div>

            <!-- Third Tab     -->
    <div id="ratefactor" class="tab-pane" style="margin-left: 25px;min-height:220px">
    <div class="filters">
    <div class="row">
        Filters by Date
        <input type="date" class="form-control" id="filter-fromdate">
    </div>
    <div class="row">
        to
        <input type="date" class="form-control" id="filter-todate">
    </div>
    <button type="button" class="btn btn-success" data-toggle="tab" id="date-filter-btn" onclick="filterdate()" >
    Apply<i class="fa fa-filter" aria-hidden="true"></i>
    </button>
    <button type="button" class="btn btn-warning" data-toggle="tab" id="date-filter-btn" onclick="resetdate()" >
    Reset<i class="fa fa-filter" aria-hidden="true"></i>
    </button>
    </div>
<!-- 
    <div class="other-filters filters">
    <div class="row">
        Filters by Date
        <input type="date" class="form-control ">
    </div>
    <div class="row">
        to
        <input type="date" class="form-control">
    </div>
    </div> -->
  
       
   <div class="full-query-box">
    <div style="width:50%;float:left">
    <input type="text" id="fields" style="display:none">
    <h2>Query Area</h2>
    <textarea class="sqlquery" style="width:600px;height:100px">SELECT * FROM</textarea>
    
    </div>
    <!-- <div style="width:50%;float:left">
    <h2>Condition Area</h2>
    <textarea class="sqlquerywhere" id="where-clause" style="width:600px;height:100px"></textarea>
    </div> -->
    </div>
   <div class="align-right">
   <button type="button" class="btn btn-primary" data-toggle="tab" id="btn_offz" onclick="offz()" >
    Next &#8594;
    </button>
    </div>
  </div>

              <!-- Fourth Tab -->
    <div id="office" class="tab-pane" style="margin-left: 25px;">
   <table id="reports" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" border="1"  align="center">
        
   </table>
    </div>       
	</div>

	</form>
	</div>
	</div>
	</div>
</div>


						
</section>
<script>
     var columns = [];
     var xmls = [];
     var fields = [];
     $('.add-btn').click(function(){
          var column = $('.rounded').val();
          columns.push("<field>"+column+"</field>");
          var xmls = columns.join("\n");
          $('.queryarea').val("<?xml version='1.0' encoding='UTF-8'?>\n<fields>\n"+xmls+'\n</fields>');
          $('.rounded').val('');
          $('.rounded').focus();
          fields.push(column);
          $('#fields').val(fields);
     });
</script>
<script>
    function rate(){
    $("#gen_li").attr('class','');
    $("#rate_li").attr('class','active');
    $("#rate_a").attr('data-toggle','tab');
    $("#btn_rate").attr('href','#ratefactor');
    }
    function search(){
    $("#gen1_li").attr('class','');
    $("#gen_li").attr('class','active');
    $("#gen_a").attr('data-toggle','tab');
    $("#btn_gen1").attr('href','#general');
    }
    function offz(){
    var sql_query = $('.sqlquery').val();
    var q_fields = $('#fields').val();
    $.ajax({
        url:"<?php echo __ROOT__ ?>reportcontroller",
        method:"POST",
        data:{sql_query:sql_query,q_fields:q_fields},
        dataType:"text",
        success:function(data)
        {
        $('#reports').html(data);
        }
        }); 
    $("#off_li").attr('class','active');
    $("#rate_li").attr('class','');
    $("#off_a").attr('data-toggle','tab');
    $("#btn_offz").attr('href','#office');
    }
</script>
<script>
    function printData()
{
   var divToPrint=document.getElementById("reports");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
}
</script>
<script>
function filterdate(){
    var filterfrom = $('#filter-fromdate').val();
    var filterto = $('#filter-todate').val();
    // alert(filterfrom+"to"+filterto);
    $('.sqlquery').val($('.sqlquery').val()+" WHERE date_added BETWEEN '"+filterfrom+"' AND '"+filterto+"'");
}
</script>
<script>
 var dbcolumns = [<?php
 $dbcols=$db->query("SELECT DISTINCT column_name FROM INFORMATION_SCHEMA.COLUMNS;");
 while($rowdbcols=$dbcols->fetch(PDO::FETCH_ASSOC))
 {
 echo "'".$rowdbcols['column_name']."',";
 }
 ?>]
</script>
<script>
 autocomplete(document.getElementById("searchfields"), dbcolumns); 

</script>
<script>
    function resetdate(){
        var query = $('.sqlquery').val();
        var output = 'WHERE'+query.split('WHERE')[1];
        var reset = query.replace(output,'');
        $('.sqlquery').val(reset);
    }
</script>
<script>
function exportexcel(type, fn, dl){
     //data-table
     var sel = document.getElementById("report-type");//report type option             
     var elt = document.getElementById('data-table'); //table output to make excel
       var report_type = sel.options[sel.selectedIndex].text; 
       var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet 1",header:1,raw:false,dateNF:'dd-MMM-yyyy'});
       return dl ?
       XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
       XLSX.writeFile(wb, fn || (report_type+'.' + (type || 'xlsx')));
}
</script>