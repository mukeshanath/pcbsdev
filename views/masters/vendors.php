<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'vendors';
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
                                  Zone Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Zone updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>
            <form method="POST" class="form-horizontal" name="formdestination" id="formvendors" >
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

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
			      	<td><input id="stncode" name="stncode" readonly="readonly" type="text" value="" readonly/></td>

              <td >Company Name</td>
			      	<td ><input id="stnname" class="form-control" name="stnname" readonly="readonly" type="text" value="" readonly/></td>
             </tr>
             <tr>

              <td >Vendor Code</td>
			      	<td><input id="vendor_code" class="form-control" name="vendor_code"  type="text" value="" readonly/></td>

              <td >Vendor Name</td>
			      	<td><input class="form-control" id="vendor_name" name="vendor_name" type="text" value="" required/></td>

              </tr>

              <tr>	
              
              <td class="">Address</td>
			      	<td><input id="vendor_add" class="form-control" name="vendor_add"  type="text" value=""/>
              
              </td>
              <td class="">City</td>
			      	<td><input id="vendor_city" name="vendor_city" class="form-control"  type="text" value=""/>
              
              </td>
              

             </tr>
             <tr>	
              
              <td class="">State</td>
			      	<td><input id="vendor_state" name="vendor_state" class="form-control"  type="text" value=""/>
              
              </td>
              <td class="">Country</td>
			      	<td><input id="vendor_country" name="vendor_country" class="form-control" type="text" value=""/>
              
              </td>
              

             </tr>
             <tr>

              <td class="">Mobile</td>
			      	<td><input id="vendor_mobile" class="form-control" name="vendor_mobile"  type="number" value=""/></td>

              <td class="">Phone</td>
			      	<td><input id="vendor_phone" class="form-control" name="vendor_phone"  type="number" value=""/></td>

              </tr>

              <tr>	

              <td class="">Fax</td>
              <td><input id="vendor_fax" class="form-control" name="vendor_fax"  type="number" value=""/></td>

              <td class="">Email</td>
              <td><input id="vendor_email" class="form-control" name="vendor_email"  type="text" value=""/></td>
              </tr>
             <tr>
              <td class="">Contact Person</td>
              <td><input id="vendor_contactperson" class="form-control" name="vendor_contactperson"  type="text" value=""/></td>

              <td class="">Remarks</td>
              <td><input id="vendor_remarks" class="form-control" name="vendor_remarks"  type="text" value=""/></td>

              </tr>
              <tr>
              <td class="">Active</td>
              <td>
              <select class="form-control border-form" name="vendor_status" id="vendor_status">
                  <option value="Y">Yes</option>
                  <option value="N">No</option>
                  </select>
             </td>

              </tr>

              </tbody>

           </table>

           <table>

            <div class="align-right btnstyle" >

            <button type="submit" class="btn btn-success btnattr_cmn" name="addanvendors">
            <i class="fa  fa-save"></i>&nbsp; Save & Add
            </button>

            <button type="submit" class="btn btn-primary btnattr_cmn" name="addvendors">
            <i class="fa  fa-save"></i>&nbsp; Save 
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

            var station = $('#stncode').val();
         
            $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{station:station},
            dataType:"text",
            success:function(data)
            {
            $('#vendor_code').val(data);
            }
            });

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