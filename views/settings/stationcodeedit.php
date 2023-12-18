<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'stationcodeedit';
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
  
function loadzone($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM zone ORDER BY znid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->zncode.'">'.$row->zncode.'</option>';
         }
         return $output;    
        
    }

    if (isset($_GET['stnid'])) {
      $stncode_id = $_GET['stnid'];
      $sql = $db->query("SELECT * FROM stncode WHERE stnid = '$stncode_id'");
      $row = $sql->fetch();
      $stnid     = $row['stnid'];
      $stncode   = $row['stncode'];
      $stnname   = $row['stnname'];
      $state     = $row['state'];
      $stname    = $row['stname'];
      $coun_code = $row['coun_code'];
      $coun_name = $row['coun_name'];
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
                                        <a href="<?php echo __ROOT__ . 'stationcodelist'; ?>">Station Code list</a>
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
                          Station IATA Added successfully.
                        </div>";
          }

          if ( isset($_GET['success']) && $_GET['success'] == 2 )
          {
              echo 

          "<div class='alert alert-success alert-dismissible'>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                          <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Station IATA updated successfully.
                        </div>";
          }

          ?>
                
             </div>
            <form method="POST" action="<?php echo __ROOT__ ?>stationcodeedit" class="form-horizontal" id="formstationcode" >
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
            <input  class="form-control border-form" name="stnid" type="hidden" id="stnid" value="<?= $stnid; ?>" >
       
            <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Station Alter Form</p>

              <div class="align-right" style="margin-top:-30px;">

              <a style="color:white" href="<?php echo __ROOT__ . 'stationcodelist'; ?>">         
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

              <td class="">Station Code</td>
			      	<td><input id="code1" name="stncode" value="<?= $stncode; ?>" type="text" value=""/></td>

              <td class="">Station</td>
			      	<td ><input id="code1" class="form-control" name="stnname" value="<?= $stnname; ?>" type="text" value=""/></td>

              </tr>
              <tr>	

              <td >State Code</td>
              <td>
              <div class="autocomplete">
                <input id="state" name="state" type="text" value="<?= $state; ?>" /></div></td>

              <td >State Name</td>
              <td ><input id="stname" class="form-control" name="stname" value="<?= $stname; ?>"  type="text" /></td>
              </tr>
              <tr>	

              <td >Country Code</td>
              <td>
              <div class="autocomplete">
              <input id="coun_code" name="coun_code"  value="<?= $coun_code; ?>"  type="text" />
              </div>
              </td>

              <td >Country Name</td>
              <td ><input id="coun_name" class="form-control" name="coun_name" value="<?= $coun_name; ?>"  type="text" /></td>
              </tr>

              </tbody>

           </table>

                     
           <table>

            <div class="align-right btnstyle" >

            <button type="submit" class="btn btn-primary btnattr_cmn" name="updatestncode">
            <i class="fa  fa-save"></i>&nbsp; Update
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
$(document).ready(function(){
        $('#data-table').DataTable();
     });
</script>

<script>
function resetFunction() {
    document.getElementById('stncode').value = "";
    document.getElementById('stnname').value = "";    
    document.getElementById('state').value = "";    
    document.getElementById('stname').value = "";    
    document.getElementById('coun_code').value = "";    
    document.getElementById('coun_name').value = "";    
}    
</script>
