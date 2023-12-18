<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'groupedit';
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
  
if (isset($_GET['gpid'])) {
$grp_id = $_GET['gpid'];
$sql = $db->query("SELECT * FROM groups WHERE gpid = '$grp_id'");
$row = $sql->fetch();
$gpid        = $row['gpid'];
$grp_type    = $row['grp_type'];
$cate_code   = $row['cate_code'];
$cate_name   = $row['cate_name'];
$range_starts = $row['range_starts'];
$range_ends   = $row['range_ends'];
$invoice_start   = $row['invoice_start'];
$v_flag        = $row['v_flag'];
$stock_alert        = $row['stock_alert'];
$username  = $row['user_name'];
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
                                    Mode Added successfully.
                                  </div>";
                    }

                    if ( isset($_GET['success']) && $_GET['success'] == 2 )
                    {
                        echo 

                    "<div class='alert alert-success alert-dismissible'>
                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                    <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                    Mode updated successfully.
                                  </div>";
                    }

                    ?>
                  
              </div>
              <form method="POST" action="<?php echo __ROOT__ ?>groupedit" accept-charset="UTF-8" class="form-horizontal" id="formgroup" enctype="multipart/form-data">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
            <input  class="form-control border-form" name="gpid" type="text" id="gpid" value="<?= $grp_id ; ?>" style="display:none;">     

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Group Alter Form</p>
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
              <select class="form-control border-form" name="grp_type"  id="grp_type"  onchange="changecategorytype(this.value)">
              <?php if($grp_type=='gl'){
                    echo "<option value='gl'>General</option>
                    <option value='sp'>Special</option>";
                     }
                    else if($grp_type=='sp'){
                    echo "<option value='sp'>Special</option>
                    <option value='gl'>General</option>";
                     } ?>
              </select>
              </td>
              </tr>
              <tr>		
              <td>Category Code</td>
              <td>
              <input value="<?= $cate_code; ?>" class="form-control cate_code" name="cate_code" type="text" id="cate_code">
              </td>
                  
              <td>Category Name</td>
              <td>
                  <input value="<?= $cate_name; ?>" class="form-control border-form" name="cate_name" type="text" id="cate_name" >
              </td>
              <td>Stock Range</td>
              <td>
                  <input  value="<?=$stock_alert?>" class="form-control border-form" name="stock_range" type="number"  min="10" max="100000" id="stock_range" >
              </td>
              </tr>
              <tr>
              <td>Cnote Range Start</td>
              <td>
              <input value="<?= $range_starts; ?>" class="form-control border-form" name="range_starts" type="text" id="range_starts">

              </td>
              <td>Cnote Range End</td>
              <td>
              <input value="<?= $range_ends; ?>" class="form-control border-form" name="range_ends" type="text" id="range_ends">

              </td>	
              <tr>
              
              <td>Invoice Range Start</td>
              <td>
              <input value="<?= $invoice_start; ?>" class="form-control border-form" name="invoice_start" type="text" id="invoice_start">

              </td>	
              <td>Virtual Cnote flag</td>

                <td style="text-align:left">
                <input style='width:50px;height:25px;' type="checkbox" <?=($v_flag==1)?'Checked':''?> value="1" name="virtual_flag">
                </td>
                  </tr>
           
              
              </tbody>
             </table>

             <table>

            <div class="align-right btnstyle" >

            <button type="submit" class="btn btn-primary btnattr_cmn" name="updategroup">
            <i class="fa  fa-save"></i>&nbsp; Update
            </button>
                                                              

            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
            <i class="fa fa-eraser"></i>&nbsp; Reset
            </button>   


            </div>
            </table>
            </form>
           </div>
  <!-- Fetching Mode Details in Table  -->
 
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

changecategorytype('<?=$grp_type?>');
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
            
            }
    });
  });
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