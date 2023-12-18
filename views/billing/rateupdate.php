<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';



?>

<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  

          <div class="alert-primary" role="alert">

          <?php 
               if ( isset($_GET['success']) && $_GET['success'] == 1 )
          {
               echo 
          "<div class='alert alert-success alert-dismissible'>
                         <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                         <h4><i class='icon fa fa-check'></i> Alert!</h4>
                         Billing Record updated successfully.
          </div>";
          }
          ?>

          </div>
<div id="col-form1" class="col_form lngPage col-sm-25">



            <form method="POST" action="<?php echo __ROOT__ ?>ratecheckexcel" accept-charset="UTF-8" class="form-horizontal" id="formaddress" enctype="multipart/form-data">

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

            <div class="boxed boxBt panel panel-primary">
                  <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Rate Deviation Check & Update Form</p>
            <div class="align-right" style="margin-top:-30px;">
            
           
          </div>
              
          </div> 
      <div class="content panel-body">


        <div class="tab_form">


          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>		
                  <td>Excel File</td>
                  <td>
                  <div class="col-sm-9">
                        <input class="form-control border-form" name="rate_excel_update" type="file" id="rate_excel_update"  accept=".xlsx,.csv" onchange="ValidateSingleInput(this)">
                   </div>
                     </td>                 
                  </tr>	
                    </tbody>
                  </table>
                  <table>

                  <div class="align-right btnstyle" >

                  <button type="submit" class="btn btn-success btnattr_cmn" name="updateall">
                  <i class="fa  fa-save"></i>&nbsp; Update
                  </button>

                  <!-- <button type="submit" class="btn btn-primary btnattr_cmn" name="deviationonly">
                  <i class="fa  fa-save"></i>&nbsp; Deviated Only
                  </button>
                                                                     -->


                  </div>
                  </table>

                <!-- Fetching Mode Details in Table  -->
               
          </form>
          </div>
          </div>
          </div>
</section>

<!-- inline scripts related to this page -->
<script>
//DataTable
  $(document).ready(function(){
        $('#data-table').DataTable();
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