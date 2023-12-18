<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{

    $module = 'pincodeedit';
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
  
if (isset($_GET['pid'])) {
$pincode_id = $_GET['pid'];
$sql = $db->query("SELECT * FROM pincode WHERE pid = '$pincode_id'");
$row = $sql->fetch();
$pid     = $row['pid'];
$pincode = $row['pincode'];
$city_name    = $row['city_name'];
$dest_code    = $row['dest_code'];
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
                                        <a href="<?php echo __ROOT__ . 'pincodelist'; ?>">Pincode List</a>
                                      </li>
                                    </ul>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
       

            <form method="POST" action="<?php echo __ROOT__ ?>pincodeedit" accept-charset="UTF-8" class="form-horizontal" id="formpincode" enctype="multipart/form-data">

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

                <input  class="form-control border-form" name="pid" type="text" id="pid" value="<?= $pid; ?>" style="display:none">

             <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Pincode Alter Form</p>
              <div class="align-right" style="margin-top:-30px;">
                    <a style="color:white" href="<?php echo __ROOT__ . 'pincodelist'; ?>">         
                    <button type="button" class="btn btn-danger btnattr_cmn" >
                    <i class="fa fa-times" ></i>
                    </button></a>
                  </div>
              
                </div> 
      <div class="content panel-body">


        <div class="tab_form">

          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>	
              <td>Destination Code</td>
                  <td>
                  <div class="col-sm-9">
                        <input placeholder="" class="form-control border-form" name="dest_code"  value="<?= $dest_code; ?>"type="text" id="dest_code" >
                    </div>
                    </td>	
                  <td>Pincode</td>
                  <td>
                  <div class="col-sm-9">
                        <input placeholder="" class="form-control border-form" name="pincode" type="text" value="<?= $pincode; ?>" id="pincode"  autofocus>
                   </div>
                     </td>
                    
                  <td>City Name</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="city_name" value="<?= $city_name; ?>" type="text" id="city_name" >
                  </div>
                   </td>
                  
                    </tr>	
                    </tbody>
                  </table>
                  <table>

                  <div class="align-right btnstyle" >

                  <button type="submit" class="btn btn-primary btnattr_cmn" name="updatepincode">
                  <i class="fa  fa-save"></i>&nbsp; Update
                  </button>
                                                                    

                  <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                  <i class="fa fa-eraser"></i>&nbsp; Reset
                  </button>   


                  </div>
                  </table>

      

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
    document.getElementById('dest_code').value = "";
    document.getElementById('pincode').value = ""; 
    document.getElementById('city_name').value = ""; 
}
</script>