<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'group';
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
  
?>
  <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


  <div id="col-form1" class="col_form lngPage col-sm-25">
  <div class="au-breadcrumb m-t-75" style="margin-bottom: -5px;">
              <div class="section__content section__content--p30">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="au-breadcrumb-content">
                                  <div class="au-breadcrumb-left">
                                      <span class="au-breadcrumb-span"></span>
                                      <ul class="list-unstyled list-inline au-breadcrumb__list">
                                      <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'grouplist'; ?>">Group list</a>
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
    
                      if ( isset($_GET['success']) && $_GET['success'] == 1 )
                    {
                        echo 

                    "<div class='alert alert-success alert-dismissible'>
                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                    <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                    Group Added successfully.
                                  </div>";
                    }

                    if ( isset($_GET['success']) && $_GET['success'] == 2 )
                    {
                        echo 

                    "<div class='alert alert-success alert-dismissible'>
                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                    <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                    Group updated successfully.
                                  </div>";
                    }

 			if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 4 )
                    {
                        echo 

                    "<div class='alert alert-danger alert-dismissible'>
                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                    <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                    Group is Already Added.
                                  </div>";
                    }


                    ?>
                  
              </div>
              <form method="POST" action="<?php echo __ROOT__ ?>group" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" id="formgroup" enctype="multipart/form-data">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Group Form</p>
                <div class="align-right" style="margin-top:-30px;">
            
           

            <a style="color:white" href="<?php echo __ROOT__ . 'grouplist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
          
            </div> 
        <div class="content panel-body">


          <div class="tab_form">


          <div id="form_error" class="alert alert-danger fade in errMrg" style="display: none;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
              <span class="error" style="color : #fff;"></span>
          </div>  

        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->


            <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
              <td>Company Code</td>
              <td>
              <input placeholder="" class="form-control cate_code" name="stncode" type="text" id="stncode" readonly>
              </td>
                  
              <td>Company Name</td>
              <td>
                  <input class="form-control border-form" type="text" id="stnname" readonly>
              </td>
              <td>Category Type</td>
              <td>
              <select class="form-control border-form"  name="grp_type"  id="grp_type" onchange="changecategorytype(this.value)">
               <option value="gl">General</option>
               <option value="sp">Special</option>
               </select>

              </td>
              </tr>
              <tr>		
              <td>Category Code</td>
              <td>
              <input placeholder="" class="form-control cate_code" name="cate_code" type="text" id="cate_code" required>
              <p id="cate_code_validation" class="form_valid_strings">Category Already Exists</p>
              </td>
                  
              <td>Category Name</td>
              <td>
                  <input class="form-control border-form" name="cate_name" type="text" id="cate_name" required >
              </td>
              <td>Stock Range</td>
              <td>
                  <input  class="form-control border-form" name="stock_range" type="number"  min="10" max="100000" id="stock_range" >
              </td>
              </tr>
              <tr>
              <td>Cnote Range Start</td>
              <td>
              <input placeholder="" class="form-control border-form" name="range_starts" type="text" id="range_starts" required oninput="validateranges(this.value,$('#range_ends').val(),$('#stncode').val())">
              <p id="grouprangeval" class="form_valid_strings">Entered Range is Already exists.</p>

              </td>
              <td>Cnote Range End</td>
              <td>
              <input placeholder="" class="form-control border-form" name="range_ends" type="text" id="range_ends" required oninput="validateranges($('#range_starts').val(),this.value,$('#stncode').val())">

              </td>	
              <tr>
              
              <td>Invoice Range Start</td>
              <td>
              <input placeholder="" class="form-control border-form" name="invoice_start" type="text" id="invoice_start">

              </td>	

              <td>Virtual Cnote flag</td>

              <td style="text-align:left">
              <input style='width:50px;height:25px;' type="checkbox" value="1" name="virtual_flag">
                </td>
                  </tr>
              </tbody>
             </table>
             <table>

            <div class="align-right btnstyle" >

            <button type="submit" class="btn btn-success btnattr_cmn submit_btn" name="addangroup">
            <i class="fa  fa-save"></i>&nbsp; Save & Add
            </button>

            <button type="submit" class="btn btn-primary btnattr_cmn submit_btn" name="addgroup">
            <i class="fa  fa-save"></i>&nbsp; Save
            </button>                                                              

            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
            <i class="fa fa-eraser"></i>&nbsp; Reset
            </button>   


            </div>
            </table>
            </form>
           </div>

            </div>
            </div>
  </section>

<!-- inline scripts related to this page -->
<script> 
function changecategorytype(category){
    if(category=='gl'){
        $('#range_starts').attr('required',true);
        $('#range_ends').attr('required',true);

        $('#range_starts').attr('readonly',false);
        $('#range_ends').attr('readonly',false);
    }
    else{
        $('#range_starts').attr('required',false);
        $('#range_ends').attr('required',false);

        $('#range_starts').val('');
        $('#range_ends').val('');

        $('#range_starts').attr('readonly',true);
        $('#range_ends').attr('readonly',true);
    }
}
</script>
<script>

//DataTable
  $(document).ready(function(){
        $('#data-table').DataTable();
    });

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
            if($('#stncode').val() == '')
            {
                // $('#companysection').css('display','block');
                var superadmin_stn = $('#stncode1').val();
                //var superadmin_stn = 'DAA';
                $.ajax({
                url:"<?php echo __ROOT__ ?>peoplecontroller",
                method:"POST",
                data:{superadmin_stn:superadmin_stn},
                dataType:"text",
                success:function(value)
                {
                    var data = value.split(",");
                    $('#stncode').val(data[0]);
                    $('#stnname').val(data[1]);
                }
                });
            }
            
            }
    });
  });
</script>
<script>
  $('#cate_code').keyup(function(){
        var cate_code = $('#cate_code').val();
        var station_code = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{cate_code:cate_code,station_code:station_code},
            dataType:"text",
            success:function(cate_coderow)
            {
              if(cate_coderow == 0)
              {
                  $('#cate_code_validation').css('display','none');
                  $('.submit_btn').attr({"disabled": false, "readonly": false});
              }
              else{
                  $('#cate_code_validation').css('display','block');
                  $('.submit_btn').attr({"disabled": true, "readonly": true});
              }
            }
    });
    });
  </script>
<script> 
function validateranges(grp_val_start,grp_val_end,grp_val_comp_code){
    // if(grp_val_start!='' || grp_val_end!=''){
    //     $('.submit_btn').attr({"disabled": false, "readonly": false});
    // }
    // else{
    //     $('.submit_btn').attr({"disabled": true, "readonly": true});
    // }

    if(parseInt(grp_val_start)>0 && parseInt(grp_val_end)>0)
    {
    var flag = false;
    $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{grp_val_start,grp_val_end,grp_val_comp_code},
            dataType:"text",
            success:function(value)
            {
                console.log(value);
                if(value==0){
                    $('.submit_btn').attr({"disabled": false, "readonly": false});
                    $('#grouprangeval').css('display','');
                    flag=true;
                }
                else{
                    $('.submit_btn').attr({"disabled": true, "readonly": true});
                    $('#grouprangeval').css('display','block');
                }
            }
    });
    }
}
</script>
<script>
function resetFunction() {   
    document.getElementById('cate_code').value = "";
    document.getElementById('cate_name').value = "";
    document.getElementById('range_starts').value = "";
    document.getElementById('range_ends').value = "";
    document.getElementById('invoice_start').value = "";   
}

</script>