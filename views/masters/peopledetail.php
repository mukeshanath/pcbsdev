<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'peopledetail';
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
  
// this function load people type from people table
function loadpeopletype($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM people ORDER BY plid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->ple_code.'">'.$row->ple_type.'</option>';
         }
         return $output;    
        
    }

    if (isset($_GET['staff_id'])) {
        $staff_id = $_GET['staff_id'];
        $sql = $db->query("SELECT * FROM staff WHERE staff_id = '$staff_id'");
        $row = $sql->fetch();
        $staff_id                    = $row['staff_id'];
        $stncode                    = $row['comp_code'];
        $stnname                    = $row['comp_name'];
            
        $pople_type                 = $row['people_type'];
        $exe_code                   = $row['exe_code'];
        $exe_name                   = $row['exe_name'];
        $exe_add				    = $row['exe_add'];
        $exe_city   			    = $row['exe_city'];
        $exe_state  			    = $row['exe_state'];
        $exe_country   	          	= $row['exe_country'];           
        $exe_mobile			        = $row['exe_mobile'];            
        $exe_doj			        = $row['exe_doj']; 
        $exe_email			        = $row['exe_email'];
        $exe_qualification	      	= $row['exe_qualification'];
        $exe_remarks			    = $row['remarks'];
        $exe_status			        = $row['active'];
        $username			        = $row['user_name'];
    
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
                                        <a href="<?php echo __ROOT__ . 'peoplelist'; ?>">People list</a>
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
                                  People Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  People updated successfully.
                                </div>";
                  }

               ?>
                
             </div>
            <form method="POST" class="form-horizontal" name="formpeople" id="formpeople" >
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">People Form</p>
                <div class="align-right" style="margin-top:-30px;">
                

            <a style="color:white" href="<?php echo __ROOT__ . 'peoplelist'; ?>">         
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

              <td >Company Code</td>
              <td>
              <div class="col-sm-9">
              <input id="stncode" name="comp_code" class="form-control" readonly="readonly"  type="text" value="<?= $stncode; ?>"/>
              </div>
              </td>
              

              <td >Company Name</td>
              <td >
              <div class="col-sm-9"><input id="stnname" class="form-control" name="comp_name" readonly="readonly" type="text" value="<?= $stnname; ?>"/>
              </div>
              </td>
             </tr>
             <tr>		
            <td >People Code</td> 	
        <td>
        <div class="col-sm-9">
         <input class="form-control border-form "  name="exe_code" type="text" id="exe_code"  value="<?= $exe_code; ?>"  readonly>
         </div>
         </td>
               
        <td>People Name</td>
		  	<td>
            <div class="col-sm-9">
            <input class="form-control border-form "  name="exe_name" type="text" id="exe_name" value="<?= $exe_name; ?>"  readonly>
            </div>
        </td>	
    </tr>
    <tr>
    <td >Role</td> 	
    <td >
            <div class="col-sm-9">
                    <input class="form-control" value="<?= $pople_type; ?>" readonly>
                                    
                  </div>
        </td>
    </tr>
         <tr>	
              
              <td >Address</td>
			      	<td>
                      <div class="col-sm-9"><input id="exe_add" class="form-control" name="exe_add"  type="text" value="<?= $exe_add; ?>" readonly />
                      </div>
              </td>
              <td >City</td>
			      	<td>
                      <div class="col-sm-9"><input id="exe_city" name="exe_city" class="form-control"  type="text" value="<?= $exe_city; ?>" readonly/>
                      </div>
              </td>
              

             </tr>
             <tr>	
              
              <td >State</td>
			      	<td>
                      <div class="col-sm-9"><input id="exe_state" name="exe_state" class="form-control"  type="text" value="<?= $exe_state; ?>" readonly/>
                      </div>
              </td>
              <td>Country</td>
			      	<td>
                      <div class="col-sm-9"><input id="exe_country" name="exe_country" class="form-control" type="text" vvalue="<?= $exe_country; ?>" readonly/>
                      </div>
              </td>
              

             </tr>
             <tr>

              <td >Mobile</td>
			  <td><div class="col-sm-9">
              <input id="exe_mobile" class="form-control" name="exe_mobile"  type="text" value="<?= $exe_mobile; ?>" readonly/>
              </div>
              </td>

              <td >Email</td>
              <td>
              <div class="col-sm-9"><input id="exe_email" class="form-control" name="exe_email"  type="text" value="<?= $exe_email; ?>" readonly/>
              </div>
              </td>
              </tr>
             <tr>
              <td >DOJ</td>
              <td>
              <div class="col-sm-9"><input id="exe_doj" class="form-control" name="exe_doj"  type="date" value="<?= $exe_doj; ?>" readonly/>
              </div>
              </td>

              <td >Qualification</td>
              <td>
              <div class="col-sm-9"><input id="exe_qualification" class="form-control" name="exe_qualification"  type="text" value="<?= $exe_qualification; ?>" readonly/>
              </div>
              </td>

              </tr>
              <tr>
              <td class="">Remarks</td>
              <td>
              <div class="col-sm-9"><input id="remarks" class="form-control" name="remarks"  type="text" value="<?= $exe_remarks; ?>" readonly/></div></td>
              <td class="">Active</td>
              <td>
              <div class="col-sm-9">
              <select class="form-control border-form" name="active" id="active" value="<?= $exe_status; ?>" readonly>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                  </select>
              </div>
             </td>

              </tr>

              </tbody>

           </table>

           <table>

            <div class="align-right btnstyle" >

            
            <button type="submit" class="btn btn-primary btnattr_cmn" name="updatepeopleexe">
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
function resetFunction() {
  document.getElementById("formpeople").reset();
}
</script>



