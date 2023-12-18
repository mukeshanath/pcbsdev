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

if(authorize($db,$user)=='False'){

    echo "<div class='alert alert-danger alert-dismissible'>
           
            <h4><i class='icon fa fa-times'></i> Alert!</h4>
            You don't have permission to access this Page.
            </div>";
            exit;
  }

function loadaddresstype($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM address ORDER BY aid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->addr_type.'">'.$row->addr_type.'</option>';
         }
         return $output;    
        
    }

    function loadtaxslab($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM tax ORDER BY taxid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->taxval.'">'.$row->taxval.'</option>';
         }
         return $output;    
        
    }

   
 
   function loadgroup($db)
           {
               $output='';
               $user1 = $_SESSION['user_name'];
                $stncode1 = $db->query("SELECT stncodes FROM users WHERE user_name='$user1'");
                $station1 = $stncode1->fetch(PDO::FETCH_ASSOC);
                $station_code = $station1['stncodes'];
               
               $query = $db->query("SELECT * FROM groups WHERE stncode='$station_code' ORDER BY gpid ASC");
              
                while ($row = $query->fetch(PDO::FETCH_OBJ)){
                   
               //menu list in home page using this code    
               $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
                }
                return $output;    
               
           }

    function loadbrcat($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM brcategorytb ORDER BY brcid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->brcate_code.'">'.$row->brcate_code.'</option>';
         }
         return $output;    
        }
        
         function loadpeopletype($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM people ORDER BY plid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->ple_type.'">'.$row->ple_type.'</option>';
         }
         return $output;    
        
    }
    
?>
  <style>
           td{
                height: 33px;
           }
           .column_select{
                width:25px;
                height:25px;
                border-radius: 5px;
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
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Reports</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Report Generator</li>
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
       <button type="submit" class="btn btn-success btnattr_cmn" id="addanbranch" name="addanbranch">
       <i class="fa fa-save"></i>&nbsp; Save & Add
       </button>

       <button type="submit" class="btn btn-primary btnattr_cmn" id="addbranch" name="addbranch">
       <i class="fa  fa-save"></i>&nbsp; Save
       </button>
                                                           
     
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
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
        <li id="gen_li" class="active">
            <a id="gen_a" data-toggle="tab" href="#general">Data Source</a>
        </li>
        <li id="rate_li">
            <a id="rate_a" data-toggle="disabled" href="#ratefactor">Columns</a>
        </li>
        <li id="off_li">
            <a id="off_a" data-toggle="disabled" href="#office">Report</a>
        </li>
      </ul>
    
   <div class="tab_form" >

  
	    
    <div class="tab-content">
  	    <!-- Tab Form 1 -->

     <div id="general" class="tab-pane active" style="margin-left: 25px;">
     <table class="table data-table" style="width:100%" cellspacing="0" border="1"  cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
            
                    <th class="">S.No</th>
                    <th class="">Table Name</th>
                    <th >Select</th>
              </tr>
    </thead>	    
    <tbody>
        <tr>
             <td style="width:100px">1</td>
             <td>BA Inbound</td>
             <td style="width:200px"><button type="button" class="btn btn-primary btn_rate" id="bainbound" data-toggle="tab" onclick="ratefact()">
        SELECT
        </button></td>
        </tr>
        <tr>
             <td style="width:100px">2</td>
             <td>Cash</td>
             <td style="width:200px"><button type="button" class="btn btn-primary btn_rate" id="cash" data-toggle="tab" onclick="ratefact()">
        SELECT
        </button></td>
        </tr>
        <tr>
             <td style="width:100px">3</td>
             <td>Credit</td>
             <td style="width:200px"><button type="button" class="btn btn-primary btn_rate" id="credit" data-toggle="tab" onclick="ratefact()">
        SELECT
        </button></td>
        </tr>
         </tbody>
     </table>
<input type="text" id="tblname" style="display:none">
 </div>

            <!-- Second Tab     -->
<div id="ratefactor" class="tab-pane" style="margin-left: 25px;">
        <table class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead>
             <thead>
             <tr>
                    <th class="">S.No</th>
                    <th class="">Column Name</th>
                    <th ></th>
              </tr>
             </thead>
             <tbody id="columns">

             </tbody>
               </thead>
               </table>
   <div class="align-right">
   <button type="button" class="btn btn-primary" data-toggle="tab" id="btn_offz" onclick="offz()" >
    Next &#8594;
    </button>
    </div>
  </div>

              <!-- Third Tab -->
    <div id="office" class="tab-pane" style="margin-left: 25px;">
   <table id="reporttable" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        
   </table>
    </div>       
	</div>

	</form>
	</div>
	</div>
	</div>
</div>


						
					</section>

<!-- inline scripts related to this page -->
<script>
     
$('.btn_rate').click(function(){
            $("#rate_li").attr('class','active');
            $("#gen_li").attr('class','');
            $("#rate_a").attr('data-toggle','tab');
            $(".btn_rate").attr('href','#ratefactor');
        var tbname = $(this).attr('id');
        $.ajax({
        url:"<?php echo __ROOT__ ?>reportcontroller",
        method:"POST",
        data:{tbname:tbname},
        dataType:"text",
        success:function(data)
        {
        $('#columns').html(data);
        $('#tblname').val(tbname);
        }
        });   
});   
</script>
<script>
var selected_columns = [];
$(document).on('click','.column_select',function(){
    if($(this).prop("checked") == true){
        selected_columns.push($(this).attr('id'));
    }
    else{
        const index = selected_columns.indexOf($(this).attr('id'));
        selected_columns.splice(index, 1);
        // selected_columns.splice($(this).attr('id'));
    }   
// alert(selected_columns);
});
</script>
<script>
function offz(){
     var tblname = $('#tblname').val();
     $.ajax({
        url:"<?php echo __ROOT__ ?>reportcontroller",
        method:"POST",
        data:{std_columns:selected_columns.join(),tblname:tblname},
        dataType:"text",
        success:function(data)
        {
        $('#reporttable').html(data);
        }
        });  
    $("#off_li").attr('class','active');
    $("#rate_li").attr('class','');
    $("#off_a").attr('data-toggle','tab');
    $("#btn_offz").attr('href','#office');
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