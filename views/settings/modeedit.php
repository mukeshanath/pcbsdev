<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'modeedit';
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
            Forbidden - You don't have permission to access this Page.
            </div>";
            exit;
  }
  
if (isset($_GET['mid'])) {
$mode_id = $_GET['mid'];
$sql = $db->query("SELECT * FROM mode WHERE mid = '$mode_id'");
$row = $sql->fetch();
$mid         = $row['mid'];
$mode_code   = $row['mode_code'];
$mode_name   = $row['mode_name'];
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
                                    <li class="list-inline-item active">
                                        <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                        </li>                                        
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'modelist'; ?>">Mode List</a>
                                      </li>
                                    </ul>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
       
        <form method="POST" action="<?php echo __ROOT__ ?>modeedit" accept-charset="UTF-8" class="form-horizontal" id="formmode" enctype="multipart/form-data">

        <input  class="form-control border-form" name="mid" type="text" id="mid" value="<?= $mid; ?>" style="display:none">

        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="" value="<?= $username; ?>" style="display:none;">    

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:10px;">Mode Alter Form</p>
              <div class="align-right" style="margin-top:-30px;">
            <a style="color:white" href="<?php echo __ROOT__ . 'modelist'; ?>">         
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
                  <td>Mode Code</td>
                  <td>
                  <div class="col-sm-9">
                       <input maxlength="6" value="<?= $mode_code; ?>" class="form-control border-form" name="mode_code" type="text" id="mode"  autofocus>
                  
                   </div>
                     </td>
                    
                  <td class="">Mode Name</td>
                  <td>
                  <div class="col-sm-9">
                      <input maxlength="100" value="<?= $mode_name; ?>" class="form-control border-form" name="mode_name" type="text" id="modename" >
                    </div>
                      </td>
                 
                
                  </tr>	
                    </tbody>
                  </table>
                  <table>

                  <div class="align-right btnstyle" >

                  <button type="submit" class="btn btn-primary btnattr_cmn" name="updatemode">
                  <i class="fa  fa-save"></i>&nbsp; Update
                  </button>
                                                                    

                  <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                  <i class="fa fa-eraser"></i>&nbsp; Reset
                  </button>   


                  </div>
                  </table>

<!-- Fetching Mode Details in Table  -->
      

        </div>
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
function resetFunction() {
    document.getElementById('mode_code').value = "";
    document.getElementById('modename').value = ""; 
}
</script>