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
  
if (isset($_GET['destid'])) {
  $dest_id = $_GET['destid'];
  $sql = $db->query("SELECT * FROM destinationtb WHERE destid = '$dest_id'");
  $row = $sql->fetch();
  $comp_code      = $row['comp_code'];
  $dest_code      = $row['dest_code'];
  $dest_name      = $row['dest_name'];
  $zone_cc        = $row['zone_cc'];
  $zone_ba        = $row['zone_ba'];
  $zone_cash      = $row['zone_cash'];
  $zone_ts        = $row['zone_ts'];
  $state          = $row['state'];
  $stname         = $row['stname'];
  $hub1           = $row['hub1'];
  $hub2           = $row['hub2'];
  $username       = $row['user_name'];

    
?>
<script>
      $(document).ready(function(){
        $('#data-table').DataTable();
     });
</script>

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
                                              <a href="#">Dashboard</a>
                                          </li>
                                          <li class="list-inline-item seprate">
                                              <span>/</span>
                                          </li>
                                          <li class="list-inline-item">Masters</li>
                                          <li class="list-inline-item seprate">
                                              <span>/</span>
                                          </li>
                                          <li class="list-inline-item">Destination Zone Edit</li>
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
                                  Destination Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Destination updated successfully.
                                </div>";
                  }

                  if ( isset($_GET['success']) && $_GET['success'] == 3 )
                  {
                       echo 

                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Check the form errors.
                                </div>";
                  }

                   ?>
                
             </div>
            <form method="POST" autocorrection="off" class="form-horizontal" name="formdestination" id="formdestination">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading"style=" height:50px;">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:10px;">Dest.Zone Form</p>
                <div class="align-right" style="margin-top:-30px;">
            
           

            <a style="color:white" href="<?php echo __ROOT__ . 'home'; ?>">         
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
           
           <table class="table">	
           <tr>		
                                
            <td>Company Code</td>
              <td><div class=" autocomplete">
              <input placeholder="" class="form-control cate_code" name="comp_code" value="<?php echo $row['comp_code']; ?>" type="text" id="stncode" readonly>
              </div>
              </td>
                  
              <td>Company Name</td>
              <td>
                  <input class="form-control border-form" name="stnname" type="text"  id="stnname" readonly >
              </td>
             
                   </tr>
           
            <tr>
           <td >Destination Code</td>
                <td ><div class=" autocomplete">
                
                 <input  class="form-control border-form" name="dest_code" value="<?php echo $row['dest_code']; ?>" type="text" id="dest_code"autofocus>
                  </div>          
                </td>
                
                <td  >Destination Name</td>
                <td >
               
                 <input  class="form-control border-form" name="dest_name" value="<?php echo $row['dest_name']; ?>" type="text" id="dest_name" >
                           
                </td>
           
                </tr>
                <tr>
           <td  >State Code</td>
                <td><div class="autocomplete">
                
                 <input  class="form-control border-form" name="state" value="<?php echo $row['state']; ?>" type="text" id="state" autofocus>
                 </div>      
                </td>
                
                <td >State Name</td>
                <td >
                
                 <input  class="form-control border-form" value="<?php echo $row['stname']; ?>" type="text" id="stname" >
                              
                </td>
           
                </tr>

                <tr>
            <td>Credit Zone</td>
            <td><div class=" autocomplete">
            <input  class="form-control border-form" name="zone_cc" value="<?php echo $row['zone_cc']; ?>" type="text" id="zone_cc">
            </div></td>
            <td>BA Zone</td>
            <td><div class=" autocomplete">
            <input  class="form-control border-form" name="zone_ba" value="<?php echo $row['zone_ba']; ?>" type="text" id="zone_ba">
            </div>
            </td>
            </tr>
            <tr>
            <td>Cash Zone</td>
            <td>
            <div class="autocomplete">
            <input  class="form-control border-form" name="zone_cash" value="<?php echo $row['zone_cash']; ?>" type="text" id="zone_cash">
            </div>
            </td>
            <td>TS Zone </td>
            <td>
            <div class="autocomplete">
             <input  class="form-control border-form" name="zone_ts" value="<?php echo $row['zone_ts']; ?>" type="text" id="zone_ts">
             </div>
             </td>
           
            </tr>
            <tr>
            <td>Hub 1</td>
            <td>
            <div class="autocomplete">
            <input  class="form-control border-form" name="hub1" type="text" value="<?php echo $row['hub1']; ?>" id="hub1">
            </div>
            </td>
            <td>Hub 2</td>
            <td>
            <div class="autocomplete">
             <input  class="form-control border-form" name="hub2" type="text" value="<?php echo $row['hub2']; ?>" id="hub2">
             </div>
             </td>
           
            </tr>
            </tbody>
              
           </table>
           <table>

          <div class="align-right btnstyle" >

          <button type="submit" class="btn btn-primary btnattr_cmn" name="adddestination">
          <i class="fa  fa-save"></i>&nbsp; Save
          </button>
                                                            

          <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
          <i class="fa fa-eraser"></i>&nbsp; Reset
          </button>   


          </div>
          </table>
        </form>
  <!-- Fetching Mode Details in Table  -->

  <!-- <div class="col-sm-12 data-table-div" >
          <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:10px;">Dest.Zone Detail List</p>
         
           </div> 
       <table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
         <thead><tr>
         <th class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" />
                            <span class="lbl"></span>
                        </label>
                    </th>            
                  
                    <th>Company</th>
                    <th>Destination</th>
                    <th>Zone </th>
                    <th>BA Zone </th>
                    <th>Cash Zone </th>
                    <th> TS Zone </th>
                    <th>Actions</th>
              </tr>
                </thead>
              <tbody>
              <?php 

                    $query = $db->query('SELECT * FROM destinationtb ORDER BY zone_destid DESC');
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                  <label>
                                    <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                      <span class='lbl'></span>
                                  </label>
                                </td>";                           
                           
                            echo "<td>".$row->comp_code."</td>";
                            echo "<td>".$row->dest_code."</td>";
                            echo "<td>".$row->zone_cc."</td>";
                            echo "<td>".$row->zone_ba."</td>";
                            echo "<td>".$row->zone_cash."</td>";
                            echo "<td>".$row->zone_ts."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                            <a class='btn btn-success btn-minier' href='destinationedit?zone_destid=".$row->zone_destid."'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                               </a>

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' >
                                    <i class='ace-icon fa fa-trash-o bigger-130'></i>
                                 </a>
                               </div>
                               <div class='hidden-md hidden-lg'>
                                 <div class='inline pos-rel'>
                                  <button class='btn btn-minier btn-yellow dropdown-toggle' data-toggle='dropdown' data-position='auto'>
                                   <i class='ace-icon fa fa-caret-down icon-only bigger-120'></i>
                                  </button>
                                  <ul class='dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close'>
                                    <li>
                                       <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                                       <span class='blue'>
                                       <i class='ace-icon fa fa-eye bigger-120'></i>
                                       </span>
                                       </a>
                                    </li>
                                    <li>
                                       <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                                        <span class='green'>
                                        <i class='ace-icon fa fa-pencil-square-o bigger-120'></i>
                                        </span>
                                       </a>
                                    </li>

                                  <li>
                                   <a  class='tooltip-error bootbox-confirm' data-rel='tooltip' title='Delete'>
                                   <span class='red'>
                                    <i class='ace-icon fa fa-trash-o bigger-120'></i>
                                    </span>
                                    </a>
                                  </li>
                                </ul>
                                              </div>
                                          </div>
                           </td>";
                     echo "</tr>";
                           
                                                    
                          }

                          
                      ?>
                 </tbody>
            </table>
              </div> -->


          </div>
          
            </div>
            </div>
            </div>
  </section>

<!-- inline scripts related to this page -->
<script>
function resetFunction() {
  document.getElementById("formdestination").reset();
}

$('#dest_code').keyup(function(){
        var dest_code = $('#dest_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dest_code:dest_code},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#dest_name').val(data[0]);
            $('#state').val(data[1]);
            $('#stname').val(data[2]);
            }
    });
    });
  
</script>

<script>
 var btn=document.querySelector(".addbtn");
 btn.onclick=function create(){
     var div=document.createElement("tr");
     div.setAttribute("id","form_group");
     div.innerHTML=gen();
     document.getElementById("dynamic_form").appendChild(div); 
    }
     function gen()
     {
        return '<td><select id="zone_dc" style="border-color:#337AB7" name="zone_dc[]" class="form-control input-sm" value=""><option value="0">Select Zone</option><?php echo loadzone($db); ?></select></td><td><select id="zone_ba" style="border-color:#337AB7" name="zone_ba[]" class="form-control input-sm" value=""><option value="0">Select Zone</option><?php echo loadzone($db); ?></select></td><td><select id="zone_cash" style="border-color:#337AB7" name="zone_cash[]" class="form-control input-sm" value=""><option value="0">Select Zone</option><?php echo loadzone($db); ?></select></td><td><div class="input-group"><select id="zone_ts[]" style="border-color:#337AB7;width: 125%;" name="zone_ts" class="form-control input-sm" value=""><option value="0">Select Zone</option><?php echo loadzone($db); ?></select></td> ';
     }
     function remove()
     {
         document.getElementById("form_group").remove();
     }

    function searchcollection()
    {
        document.getElementsByClassName('searchcollection').style.display='block';
    }

  </script>
  <script>
  var zncredit =[<?php  
    $sql=$db->query("SELECT zone_code FROM zonetb WHERE zone_type='multi'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']."', ";
    }
 ?>];

  
 var zncash =[<?php  
    $sql=$db->query("SELECT zone_code FROM zonetb WHERE zone_type='multi'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']."', ";
    }
 ?>];

var znts =[<?php  
    $sql=$db->query("SELECT zone_code FROM zonetb WHERE zone_type='multi'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['zone_code']."', ";
    }
 ?>];
</script>
<script>

autocomplete(document.getElementById("zone_cc"), zncredit);
autocomplete(document.getElementById("zone_ba"), zncredit);
autocomplete(document.getElementById("zone_cash"), zncash);
autocomplete(document.getElementById("zone_ts"), znts);


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
            }
    });
  });
</script>
<script>
var destcode =[<?php  
    $sql=$db->query("SELECT * FROM stncodetb");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['stncode']."', ";
    }
 ?>];

autocomplete(document.getElementById("dest_code"), destcode);
autocomplete(document.getElementById("hub1"), destcode);
autocomplete(document.getElementById("hub2"), destcode);
</script>