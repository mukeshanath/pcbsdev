<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'peopleadd';
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

    function genpeoplecode($db)
    {
        $peoplecode = $db->query("SELECT TOP 1 exe_code FROM staff ORDER BY cast(dbo.GetNumericValue(exe_code) AS decimal) DESC");
        if($peoplecode->rowCount()== 0)
            {
            $exec_code = 'MKT'.str_pad('1', 7, "0", STR_PAD_LEFT);
            }
        else{
            $peoplerow  = $peoplecode->fetch(PDO::FETCH_ASSOC);
            $lasteoplecode =$peoplerow['exe_code'];
            $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$lasteoplecode);
            $exec_code = 'MKT'.str_pad($arr[1]+1, 7, "0", STR_PAD_LEFT);
            }
         return $exec_code;    
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
                                  People Updated successfully.
                                </div>";
                  }

               ?>
                
             </div>
            <form method="POST" autocomplete="off" class="form-horizontal" name="formpeople" id="formpeople" >
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

            <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>
              <td >Company Code</td>
              <td>
              <div class="col-sm-9">
              <input id="stncode" name="comp_code" class="form-control" readonly="readonly" type="text" value=""/>
              </div>
              </td>            

              <td >Company Name</td>
              <td >
              <div class="col-sm-9"><input id="stnname" class="form-control" name="comp_name" readonly="readonly" type="text" value=""/>
              </div>
              </td>
             </tr>
            <tr>		
		  	<td>People Code</td>
          <td>
          <div class="col-sm-9">
            <input class="form-control border-form " readonly name="exe_code" type="text" id="exe_code"  value="<?php echo genpeoplecode($db); ?>">
          </div>
          </td>
               
        <td>People Name</td>
		  	<td>
            <div class="col-sm-9">
            <input class="form-control border-form "  name="exe_name" type="text" id="exe_name" >
            </div>
        </td>	
       </tr>
       <tr>
       <td>Role</td>
       <td><div class="col-sm-9">
                    <select class="form-control" name="people_type">
                    <option value="0">Select Role</option>
                    <?php echo loadpeopletype($db); ?>
                    </select>                
                  </div>
                  <td></td>
                  <td></td>
       </tr>
         <tr>	
              
              <td >Address</td>
			      	<td>
                      <div class="col-sm-9"><input id="exe_add" class="form-control" name="exe_add"  type="text" />
                      </div>
              </td>
              <td >City</td>
			      	<td>
                      <div class="col-sm-9"><input id="exe_city" name="exe_city" class="form-control"  type="text" value=""/>
                      </div>
              </td>
              

             </tr>
             <tr>	
              
              <td >State</td>
			      	<td>
                      <div class="col-sm-9"><input id="exe_state" name="exe_state" class="form-control"  type="text" value=""/>
                      </div>
              </td>
              <td>Country</td>
			      	<td>
                      <div class="col-sm-9"><input id="exe_country" name="exe_country" class="form-control" type="text" value=""/>
                      </div>
              </td>
              

             </tr>
             <tr>

              <td >Mobile</td>
			  <td><div class="col-sm-9">
              <input id="exe_mobile" class="form-control" name="exe_mobile"  type="text" value=""/>
              </div>
              </td>

              <td >Email</td>
              <td>
              <div class="col-sm-9"><input id="exe_email" class="form-control" name="exe_email"  type="text" value=""/>
              </div>
              </td>
              </tr>
             <tr>
              <td >DOJ</td>
              <td>
              <div class="col-sm-9"><input id="exe_doj" class="form-control today" name="exe_doj"  type="date" value=""/>
              </div>
              </td>

              <td >Qualification</td>
              <td>
              <div class="col-sm-9"><input id="exe_qualification" class="form-control" name="exe_qualification"  type="text" value=""/>
              </div>
              </td>

              </tr>
              <tr>
              <td class="">Remarks</td>
              <td>
              <div class="col-sm-9"><input id="remarks" class="form-control" name="remarks"  type="text" value=""/></div></td>
              <td class="">Active</td>
              <td>
              <div class="col-sm-9">
              <select class="form-control border-form" name="active" id="active">
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

            <button type="submit" class="btn btn-success btnattr_cmn" name="addanpeopleexe">
            <i class="fa  fa-save"></i>&nbsp; Save & Add
            </button>

            <button type="submit" class="btn btn-primary btnattr_cmn" name="addpeopleexe">
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

$(document).ready(function(){
  $('#br_code').keyup(function(){
      var br_code = $('#br_code').val();
      $.ajax({
              url:"<?php echo __ROOT__ ?>mastercontroller",
              method:"POST",
              data:{br_code5:br_code},
              dataType:"text",
              success:function(data)
              {
              $('#br_name').val(data);
              }
      });
  });
});

</script>
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

 var stationcode =[<?php  
    $sql=$db->query("SELECT * FROM stncode");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['stncode']."', ";
    }
 ?>];



var branchcode = [<?php  
    $usr_name = $_SESSION['user_name'];
    $user_data = $db->query("SELECT * FROM users WHERE user_name='$usr_name'");
    $user_row = $user_data->fetch(PDO::FETCH_ASSOC);

    $user = $user_row['stncodes'];
    $sql=$db->query("SELECT * FROM branch where stncode='$user'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."', ";
    }
 ?>];
</script>

<script>
autocomplete(document.getElementById("stncode"), stationcode);
autocomplete(document.getElementById("br_code"), branchcode);

</script>


<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('exe_code').value = "";
    document.getElementById('exe_name').value = "";
    document.getElementById('exe_add').value = "";
    document.getElementById('exe_city').value = "";
    document.getElementById('exe_state').value = "";
    document.getElementById('exe_country').value = "";
    document.getElementById('exe_mobile').value = "";
    document.getElementById('exe_email').value = "";   
    document.getElementById('exe_doj').value = "";   
    document.getElementById('exe_qualification').value = "";   
    document.getElementById('remarks').value = "";      
}
</script>