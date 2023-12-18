<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $modulearr = explode('?',basename($_SERVER['REQUEST_URI']));
    $module = $modulearr[0];
    $getusergrp = $db->query("SELECT group_name FROM usertb WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;

    $verify_user = $db->query("SELECT module FROM users_grouptb WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return $access='False';
        }
        else{
            return $access='True';
        }
}

if(authorize($db,$user)=='False'){

    echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-times'></i> Alert!</h4>
            Forbidden - You don't have permission to access this Page.
            </div>";
            exit;
  }
  
function loadzone($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM zonetb ORDER BY znid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->zncode.'">'.$row->zncode.'</option>';
         }
         return $output;    
        
    }

    function loadstniata($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM stncodetb ORDER BY stnid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stncode.'">'.$row->stncode.'</option>';
         }
         return $output;    
        
    }
?>


 <head>        
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
 </head>  
<div class="container">

 <section class="au-breadcrumb m-t-75" style="margin-bottom: -20px;">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="au-breadcrumb-content">
                                    <div class="au-breadcrumb-left">
                                        <span class="au-breadcrumb-span"></span>
                                        <ul class="list-unstyled list-inline au-breadcrumb__list">
                                            <li class="list-inline-item active">
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Masters</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Zone</li>
                                        </ul>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
            <hr>

            

            <div class="row">
             <div class="col-xs-12 ">
                <div class="clearfix hidden-print " >
                  <div class="easy-link-menu align-left">
                  <a class="btn-success btn-sm" href="<?php echo __ROOT__ ?>destination"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Destination Master Form</a>
                  <a class="btn-primary btn-sm" href="<?php echo __ROOT__ ?>destinationlist"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;Destination list</a>
                  
              </div>
              </div>
                                                                      <!-- PAGE CONTENT BEGINS -->
            <div class="form-horizontal">
              
            <form method="POST" class="form-horizontal" name="formdestination" id="formdestination" >
                      <label for="destination" class="col-sm-1 control-label">Destination</label>
                        <div class="col-sm-2">
                          <input placeholder="" class="form-control border-form" name="destination" type="text" id="destination" required autofocus>
                        </select>
                        </div>
                          <div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr>
                                      <th>zone</th>
                                      <th>stations</th>
                                      <th>Rate</th>
                                    </tr>

                                    <tr>  
                                         <td> <select class="form-control"  name="zone[]" id="zone">
                                                <?php echo loadzone($db); ?>
                                              </select>
                                          </td>  
                                         <td><select class="form-control"  name="stations[]" id="stations" multiple="">
                                                <?php echo loadstniata($db); ?>
                                              </select></td> 
                                         <td class="col-sm-2"><input type="text" name="rate[]" placeholder="Rate" class="form-control col-sm-1" /></td> 
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                              
                          </div>  

                        </div>
                          <div class="form-group">
          <div class="card-body" style="float: right;">
            <button type="submit" class="btn btn-success" name="adddestination">
            <i class="fa fa-star"></i>&nbsp; Submit
            </button>
                                       
            <button type="submit" class="btn btn-primary" name="updatedestination">
            <i class="fa fa-magic"></i>&nbsp; Update
            </button>

            <button type="button" class="btn btn-warning" onclick="resetFunction()">
            <i class="fa fa-map-marker"></i>&nbsp; Reset
            </button>                    
           </div>
         </div>
      </form>
      </div>
    </div><!-- /.col -->

  </div><!-- /.row -->

  


                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
  
</div>

<!-- inline scripts related to this page -->
<script>
function resetFunction() {
  document.getElementById("formdestination").reset();
}
</script>

<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td> <select class="form-control"  name="zone[]" id="zone"><?php echo loadzone($db); ?></select>      </td><td><select class="form-control"  name="stations[]" id="stations" multiple=""><?php echo loadstniata($db); ?></select></td><td><input type="text" name="rate[]" placeholder="Rate" class="form-control " /></td> <td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"<?php echo __ROOT__ ?>destination",  
                method:"POST",  
                data:$('#formdestination').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#formdestination')[0].reset();  
                }  
           });  
      });  
 });  
 </script>