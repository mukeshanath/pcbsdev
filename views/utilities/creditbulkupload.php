<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 
  
function authorize($db,$user)
{
   
    $module = 'creditbulkupload';
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
  
 function loadstatus($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM status ORDER BY sid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stat_type.'">'.$row->stat_type.'</option>';
         }
         return $output;    
        
    }
 

?>
<style>
.progress-bar {
    -webkit-transition: none !important;
    transition: none !important;
}
.progress{
    padding-left: 10%;
}
</style>
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  

<div id="col-form1" class="col_form lngPage col-sm-25">
<div class="au-breadcrumb m-t-75" style="margin-top:6px!important;">
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
                                            <li class="list-inline-item">Utilities</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Credit Bulk Upload</li>
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
                            Cnote Procurement Record Added successfully.
            </div>";
            }


            ?>

            </div>
            <form method="POST" action="<?php echo __ROOT__ ?>creditbulkupload" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="formitem" enctype="multipart/form-data">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
            <input id="grp_type" name="grp_type" type="text" class="form-control input-sm" style="display:none">

	        <div class="boxed boxBt panel panel-primary">
            <div class="title_1 title panel-heading">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Bulk Upload</p>
                  <div class="align-right" style="margin-top:-30px;">
            
           

            <a style="color:white" href="<?php echo __ROOT__ . 'creditbulkuploadlist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
             
               </div> 
              
	        <div class="content panel-body">
	
            <div class="tab_form">

        
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->
        
        <div class="smsg summary" style="color:#3EA99F;"></div>
            <table id="formPOSpurchase" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">
        
		<tbody>
        <tr>		
			<td>Company Code</td>
			<td>
            <input id="stncode" name="station_code" type="text" value="" class="form-control input-sm" readonly>
            </td>
				
			<td>Company Name</td>
			<td>
				<input id="stnname" name="station_name" value="" class="form-control input-sm"  type="text" readonly>
               </td>
               <td>Date</td>
			<td>
                <div class="autocomplete col-sm-12">
				    <input type="date" class="form-control input-sm today" name="edate" id="edate"> 
                </div>
			</td>	
            </tr>
            <tr>

			<td>Import File</td>
			<td>
            <div class="autocomplete col-sm-12">
                
                <input type="file" class="form-control input-sm" name="importfile" id="importfile" required  onchange="ValidateSingleInput(this);"  accept=".xlsx,.csv"> 
                
                </div>
               </td>
               <td></td>
               <td></td>
               <td></td>
               <td>	<button type="submit" class="btn btn-primary btnattr_cmn" id="creditbulkupload" name="creditbulkupload">
               <i class="fa  fa-upload"></i>&nbsp; Upload
               </button></td>	
            <td></td>
		</tr>
          
                </tbody>
         </table>
    <table>
    <input id="cnote_status" name="cnote_status" type="hidden" value="Procured" class="form-control input-sm" readonly>

        <div class="align-right btnstyle" >

    

        </div>
        </table>

 
	
	</div>
 
    </div>
    </div>
    </form>
	</div>

				
</section> 
                
        <div class="progress active">
            <div class="progress-bar progress-bar-striped"  style="width: 0%" ></div>
        </div>
<script>
   function progress(){
    $loadingtime = (parseInt($('#cnote_count').val())/1.5);
    $(".progress-bar").animate({
        width: "100%"
    }, $loadingtime);

    $('#header').css('filter','blur(5px)');
    $('nav').css('filter','blur(5px)');
    $('section').css('filter','blur(5px)');
    $('footer').css('filter','blur(5px)');
    $(document).on('keydown', disableF5);
    }
</script>
<script>
 function disableF5(e) {
      if ((e.which || e.keyCode) == 116) e.preventDefault(); 
    };

</script>

<script>

$(window).on('load', function () {
   var usr_name = $('#user_name').val();
        
        $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{usr_name:usr_name},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#stncode').val(data[0]);
            $('#stnname').val(data[1]);
            $('#origin').val(data[0]);
            }
    });
  });
 
</script>
<script>
     var _validFileExtensions = [".xlsx", ".csv"];    
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
             
            if (!blnValid) {
                alert("Sorry, file is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}
</script>