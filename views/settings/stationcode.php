<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'stationcode';
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
                          Station Code Added successfully.
                        </div>";
          }

          if ( isset($_GET['success']) && $_GET['success'] == 2 )
          {
              echo 

          "<div class='alert alert-success alert-dismissible'>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                          <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Station Code updated successfully.
                        </div>";
          }

          if ( isset($_GET['success']) && $_GET['success'] == 3 )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Station already Added.
                                </div>";
                  }

          ?>
                
             </div>
            <form method="POST" action="<?php echo __ROOT__ ?>stationcode" class="form-horizontal" id="formstationcode" >
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Station Form</p>
                <div class="align-right" style="margin-top:-30px;">
            
           

            <a style="color:white" href="<?php echo __ROOT__ . 'stationcodelist'; ?>">         
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

              <td >Station Code</td>
			      	<td>
              <input id="stncode" name="stncode" type="text" />
              </td>

              <td >Station Name</td>
			      	<td ><input id="stnname" class="form-control" name="stnname"  type="text" /></td>
              </tr>
              <tr>	

              <td >State Code</td>
              <td>
              <div class="autocomplete">
                <input id="state" name="state" type="text" /></div></td>

              <td >State Name</td>
              <td ><input id="stname" class="form-control" name="stname"  type="text" /></td>
              </tr>
              <tr>	

              <td >Country Code</td>
              <td>
              <div class="autocomplete">
              <input id="coun_code" name="coun_code" type="text" />
              </div>
              </td>

              <td >Country Name</td>
              <td ><input id="coun_name" class="form-control" name="coun_name"  type="text" /></td>
              </tr>
              </tbody>
           </table>

           <table>

          <div class="align-right btnstyle1" >

          <button type="submit" class="btn btn-primary btnattr_cmn" name="addstationcode">
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
        $('#data-table').DataTable();

        $('#state').keyup(function(){
        var state = $('#state').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{state:state},
            dataType:"text",
            success:function(data)
            {
            $('#stname').val(data);
            }
    });
    });

    
    $('#coun_code').keyup(function(){
        var country = $('#coun_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{country:country},
            dataType:"text",
            success:function(data)
            {
            $('#coun_name').val(data);
            }
    });
    });
     });

//State code autocomplete
     var statecode =[<?php  
    $sql=$db->query("SELECT statecode FROM state");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['statecode']."', ";
    }
 ?>];
//Country code Autocomplete
var countrycode =[<?php  
    $sql=$db->query("SELECT coun_code FROM country");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['coun_code']."', ";
    }
 ?>];

autocomplete(document.getElementById("state"), statecode);
autocomplete(document.getElementById("coun_code"), countrycode);

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