<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'vendorsedit';
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
  
if (isset($_GET['vend_id'])) {
    $vend_id = $_GET['vend_id'];
    $sql = $db->query("SELECT * FROM vendor WHERE vend_id = '$vend_id'");
    $row = $sql->fetch();
    $vend_id                    = $row['vend_id'];
    $stncode                    = $row['stncode'];
    $stnname                    = $row['stnname'];
    $vendor_code                = $row['vendor_code'];
    $vendor_name                = $row['vendor_name'];
    $vendor_add				    = $row['vendor_add'];
    $vendor_city   			   	= $row['vendor_city'];
    $vendor_state  			  	= $row['vendor_state'];
    $vendor_country   	      	= $row['vendor_country'];
    $vendor_phone			   	= $row['vendor_phone'];
    $vendor_mobile			   	= $row['vendor_mobile'];            
    $vendor_fax				    = $row['vendor_fax'];
    $vendor_email			    = $row['vendor_email'];
    $vendor_contactperson	  	= $row['vendor_contactperson'];
    $vendor_remarks			   	= $row['vendor_remarks'];
    $vendor_status			   	= $row['vendor_status'];
    $user_name                  = $row['user_name'];

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
                                        <a href="<?php echo __ROOT__ . 'vendorslist'; ?>">Vendors list</a>
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
                                  Vendor Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Vendor updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>
            <form method="POST" class="form-horizontal" name="formdestination" id="formvendors" >
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
            <input class="form-control border-form" name="vend_id" type="text" id="vend_id" value="<?= $vend_id; ?>" style="display:none">

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Vendor Form</p>
                <div class="align-right" style="margin-top:-30px;">
                

            <a style="color:white" href="<?php echo __ROOT__ . 'vendorslist'; ?>">         
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

              <td >Company Code</td>
			      	<td><input id="stncode" name="stncode" value="<?= $stncode; ?>"  readonly="readonly" type="text" value=""/></td>

              <td >Company Name</td>
			      	<td ><input id="stnname" class="form-control" value="<?= $stnname; ?>"  name="stnname" readonly="readonly" type="text" value=""/></td>
             </tr>
             <tr>

              <td >Vendor Code</td>
			      	<td><input id="vendor_code" class="form-control" value="<?= $vendor_code; ?>"  name="vendor_code"  type="text" value=""/></td>

              <td >Vendor Name</td>
			      	<td><input class="form-control" id="vendor_name" value="<?= $vendor_name; ?>"  name="vendor_name" type="text" value=""/></td>

              </tr>

              <tr>	
              
              <td class="">Address</td>
			      	<td><input id="vendor_add" class="form-control" value="<?= $vendor_add; ?>"  value="<?= $addr_type; ?>"  name="vendor_add"  type="text" value=""/>
              
              </td>
              <td class="">City</td>
			      	<td><input id="vendor_city" name="vendor_city" value="<?= $vendor_city; ?>"  class="form-control"  type="text" value=""/>
              
              </td>
              

             </tr>
             <tr>	
              
              <td class="">State</td>
			      	<td><input id="vendor_state" name="vendor_state" value="<?= $vendor_state; ?>"  class="form-control"  type="text" value=""/>
              
              </td>
              <td class="">Country</td>
			      	<td><input id="vendor_country" name="vendor_country" value="<?= $vendor_country; ?>"  class="form-control" type="text" value=""/>
              
              </td>
              

             </tr>
             <tr>

              <td class="">Mobile</td>
			      	<td><input id="vendor_mobile" class="form-control" value="<?= $vendor_mobile; ?>"  name="vendor_mobile"  type="text" value=""/></td>

              <td class="">Phone</td>
			      	<td><input id="vendor_phone" class="form-control" value="<?= $vendor_phone; ?>"  name="vendor_phone"  type="text" value=""/></td>

              </tr>

              <tr>	

              <td class="">Fax</td>
              <td><input id="vendor_fax" class="form-control" value="<?= $vendor_fax; ?>"  name="vendor_fax"  type="text" value=""/></td>

              <td class="">Email</td>
              <td><input id="vendor_email" class="form-control" value="<?= $vendor_email; ?>"  name="vendor_email"  type="text" value=""/></td>
              </tr>
             <tr>
              <td class="">Contact Person</td>
              <td><input id="vendor_contactperson" class="form-control" value="<?= $vendor_contactperson; ?>"  name="vendor_contactperson"  type="text" value=""/></td>

              <td class="">Remarks</td>
              <td><input id="vendor_remarks" class="form-control" value="<?= $vendor_remarks; ?>"  name="vendor_remarks"  type="text" value=""/></td>

              </tr>
              <tr>
              <td class="">Active</td>
              <td>
              <select class="form-control border-form" name="vendor_status" id="vendor_status">
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                  </select>
             </td>

              </tr>

              </tbody>

           </table>

           <table>

            <div class="align-right btnstyle" >

            <button type="submit" class="btn btn-primary btnattr_cmn" name="updatevendors">
            <i class="fa  fa-save"></i>&nbsp; Update
            </button>
                                                              

            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
            <i class="fa fa-eraser"></i>&nbsp; Reset
            </button>   


            </div>
            </table>
           
          
        </form>
  <!-- Fetching Mode Details in Table  -->

 
          
            </div>
            </div>
            </div>
  </section>

<!-- inline scripts related to this page -->
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
            }
    });
  });



</script>

<script>
    $(document).ready(function(){
        $('#data-table').DataTable();
    });
</script>

<script>
function resetFunction() {
    document.getElementById('vendor_code').value = "";
    document.getElementById('vendor_name').value = "";
    document.getElementById('vendor_add').value = "";
    document.getElementById('vendor_city').value = "";
    document.getElementById('vendor_state').value = "";
    document.getElementById('vendor_country').value = "";
    document.getElementById('vendor_mobile').value = "";
    document.getElementById('vendor_phone').value = "";
    document.getElementById('vendor_fax').value = "";
    document.getElementById('vendor_email').value = "";   
    document.getElementById('vendor_contactperson').value = "";   
    document.getElementById('vendor_remarks').value = "";     
}
</script>