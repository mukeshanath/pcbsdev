<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'itemedit';
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
  
if (isset($_GET['iid'])) {
  $item_id = $_GET['iid'];
  $sql = $db->query("SELECT * FROM item WHERE iid = '$item_id'");
  $row = $sql->fetch();
  $iid         = $row['iid'];
  $item_code   = $row['item_code'];
  $item_name   = $row['item_name'];
  $spl_rate   = $row['spl_rate'];
  $oth_chrg   = $row['oth_chrg'];
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
                                        <a href="<?php echo __ROOT__ . 'itemlist'; ?>">Items list</a>
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
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  Item Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  Item updated successfully.
                                </div>";
                  }

 if ( isset($_GET['success']) && $_GET['success'] == 4 )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Item already added.
                                </div>";
                  }

                   ?>
                
             </div>
            <form method="POST" action="<?php echo __ROOT__ ?>item" accept-charset="UTF-8" class="form-horizontal" id="formzone" enctype="multipart/form-data">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
            <input class="form-control border-form" name="iid" type="text" id="iid" value="<?= $iid; ?>" style="display:none">

              <div class="boxed boxBt panel panel-primary">
                <div class="title_1 title panel-heading">
                      <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Item Alter Form</p>
           <div class="align-right" style="margin-top:-30px;">
            
           

            <a style="color:white" href="<?php echo __ROOT__ . 'itemlist'; ?>">         
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
                                
            <td>Company Code</td>
              <td>
              <input placeholder="" class="form-control cate_code" name="comp_code" type="text" id="stncode" readonly>
              </td>
                  
              <td>Company Name</td>
              <td>
                  <input class="form-control border-form" name="stnname" type="text" id="stnname" readonly>
              </td>
             
                   </tr>
                   <tr>		
                                
                    <td>Item Code</td>
                    <td>
                    <input class="form-control border-form" name="item_code" type="text" value="<?= $item_code; ?>" id="item_code" autofocus>
               </td>
                <td>Item Name</td>
                  <td>
                     <input class="form-control border-form" name="item_name" value="<?= $item_name; ?>" type="text" id="item_name" >
               </td>	
                                   
               </tr>

               <tr>		
                 <td>Special Rate</td>
              <td>
                 <input class="form-control border-form" name="spl_rate" type="text" value="<?= $spl_rate; ?>" id="spl_rate">
                  </td>	

                  <td>Other Charges</td>
              <td>
                 <input class="form-control border-form" name="oth_chrg" type="text" value="<?= $oth_chrg; ?>" id="oth_chrg">
                  </td>	
                   </tr>
            
              </tbody>
              </table>
              <table>

             <div class="align-right btnstyle" style="margin-top:10px;">
             <button type="submit" class="btn btn-primary btnattr_cmn" name="updateitem">
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
            </div>
  </section>

<!-- inline scripts related to this page -->
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
function resetFunction() {
    document.getElementById('item_code').value = "";
    document.getElementById('item_name').value = "";
    document.getElementById('spl_rate').value = "";
    document.getElementById('oth_chrg').value = "";
}
</script>