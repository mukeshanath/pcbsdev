<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'useredit';
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

function getusertype($db,$grp_name){
    $group = $db->query("SELECT TOP 1 * FROM users_group WHERE group_name='$grp_name'")->fetch(PDO::FETCH_OBJ);
    return $group->user_type;
}

if(authorize($db,$user)=='False'){

    echo "<div class='alert alert-danger alert-dismissible'>
            
            <h4><i class='icon fa fa-times'></i> Alert!</h4>
            You don't have permission to access this Page.
            </div>";
            exit;
  }
error_reporting(0);
if(isset($_GET['usr_name'])){
    $usr_name = $_GET['usr_name'];
    $get_data = $db->query("SELECT * FROM users WHERE user_name='$usr_name'");
    $row = $get_data->fetch(PDO::FETCH_OBJ);
    $usr_group = $row->group_name;
    $br_name = $db->query("SELECT br_name FROM branch WHERE br_code='$row->br_code'")->fetch(PDO::FETCH_OBJ);
    $cust_name = $db->query("SELECT cust_name FROM customer WHERE cust_code='$row->cust_code'")->fetch(PDO::FETCH_OBJ);
    $selectedstationsjson = json_encode(array_map('trim',explode(',',$row->stncodes)));
    } 

    function loadcompany($db,$row)
    {  
       
        
        $output='';
        $query = $db->query("SELECT stncode FROM station ORDER BY stncode ASC");
        // $row->stncodes = explode(',',$row->stncodes);
        $selected2 = explode(',',$row->stncodes);
        $result = array_map('trim', $selected2);

    
        $output = '<div class="column1">';
    
        $divcount = 0;
        while($row1 = $query->fetch(PDO::FETCH_OBJ)){ 
           $divcount++;
           if($divcount>10){  $divcount = 1;
             $output .= '</div><div class="column1">';
           }
            $output .= "<input type='checkbox' class='chlarge' name='day'  value='".$row1->stncode."'". (in_array(trim($row1->stncode), $result) ? 'Checked' : '') ."><label>".$row1->stncode."</label>
             <p></p>";
          //  $people = array("Peter", "Joe", "Glenn", "Cleveland");
    //echo in_array("IXC" ,$result);
      //   exit();
         }
         $output .= '</div>';
         return $output; 
   
    }
    
    function loadusergroup($db,$usr_group)
    {
        $output='';
        $query = $db->query("SELECT DISTINCT group_name FROM users_group");
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
        if($usr_group==$row->group_name){
            $selected = 'Selected';
        }
        else{
            $selected = '';
        }
        //menu list in home page using this code    
        $output .='<option '.$selected.' value="'.$row->group_name.'">'.$row->group_name.'</option>';
         }
         return $output;    
        
    }
    $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
    $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
    $rowuser = $datafetch['last_compcode'];
    if(!empty($rowuser)){
         $stationcode = $rowuser;
    }
    else{
        $userstations = $datafetch['stncodes'];
        $stations = explode(',',$userstations);
        $stationcode = $stations[0];     
    }

    $ugroup = $db->query("SELECT TOP 1 user_type FROM users_group WHERE group_name='$usr_group'")->fetch(PDO::FETCH_OBJ);
?>
<style>
    .column1 {
  float: left;
  width: 100px;
  padding: 10px;
  background-color: #ccc;
}

.column1 p {
  float: none;
  color: black;
  padding: 3px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.column1 a:hover {
  background-color: white;
}

.column1{
    background-color: white;
}
.column1 label{
    margin-left: 15px;
    font-size: 18px;
}
.column1 .chlarge{
    width: 17px;
  height: 17px;
}
</style>
<link rel="stylesheet" href="vendor/bootstrap/multiselect/tokenize2.css">
<script src="vendor/bootstrap/multiselect/tokenize2.js"></script>
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
                                        <a href="<?php echo __ROOT__ . 'userlist'; ?>">Users list</a>
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
                                  Users Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Users Updated Successfully.
                                </div>";
                  }

                  if ( isset($_GET['unsuccess']))
                  {
                      $error_arr = unserialize($_GET['unsuccess']);
                    echo "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  ".$error_arr."
                                </div>";
                  }

                   ?>
                
             </div>
            <form method="POST" action="<?php echo __ROOT__ ?>useredit" autocomplete="off" class="form-horizontal" name="formdestination" id="formvendors" >
            <input placeholder="" class="form-control border-form" _name="user_name" type="text" _id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
            <input id="usr_id" class="form-control" name="usr_id"  type="hidden" value="<?=$row->usrid;?>"/>

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">User Alter Form</p>
                <div class="align-right" style="margin-top:-30px;">
                

            <a style="color:white" href="<?php echo __ROOT__ . 'userlist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
          
            </div> 
        <div class="content panel-body">


          <div class="tab_form">

            <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody >

             <tr>
             <td>User Name</td>
			      	<td><input id="user_name" class="form-control" name="user_name" required type="text" value="<?=$row->user_name; ?>"/></td>
              <td>Email</td>
			   	<td><input id="user_email" class="form-control" name="user_email" required type="text" value="<?=$row->user_email; ?>"/></td>
              </tr>
             <tr>	
             <td>User Group</td>
			      	<td><select class="form-control" id="group_name" name="group_name">
                          <?php echo loadusergroup($db,$usr_group); ?>
                          </select>
                    </td>

             <td class="">Active</td>
              <td>
              <select class="form-control border-form" name="flag" id="flag">
              <option value="1" <?=$row->flag=='1'?"selected":"";?> >Yes</option>
              <option value="0" <?=$row->flag=='0'?"selected":"";?> >No</option>
                  </select>
              </td>        
             </tr>
             </tbody>
            
             <tr>	
              <td>Company</td>
              <td style="width:450px">
              <div class="autocomplete col-sm-12">
                    <div class="col-sm-11">
                    <input type="text" class="form-control border-form" id="compcode" name="stncode" value="<?=$row->stncodes;?>" readonly></div>
                    <div class="col-sm-1">
                    <button type="button"  data-toggle="modal" class="btn btn-primary col-sm-12" style="height:33px" data-target="#comp_code"><i style="font-weight:bold"><i style="font-weight:bold">i</i></button>
                    </div>
                    </div>
              </td>
              <!--  <td><span>
              <button style='margin-left:-250px;' type="button" class="btn btn-success btn-xs" id="addbtn" ><i class="fa fa-plus-circle"></i></button>
              </span>
              <span><button type="button" style="margin-left:4px;" class="btn btn-danger btn-xs" onclick="remove(this)" id="remove" ><i class="fa fa-minus-circle"></i></button></span>
               </td>  -->
             </tr>
             <tr>
                <td>Branch Codes</td>
                <td>
                <div class="col-sm-12">
                <select id="br_code" class="form-control " name="br_code[]" _onkeyup="fetchcustomer()" multiple value="">
                
                </select>
                </div></td>
                   </tr>
             <tbody id="br_cust">
                    
                    <tr id="cust_row" style="display:<?php if($ugroup->user_type=='I'){echo 'none';}else if($ugroup->user_type=='E-B'){echo 'none';}?>">
                    <td>Customer Code</td>
                    <td><div class="autocomplete col-sm-12"><input id="cust_code" class="form-control" name="cust_code"  type="text" value="<?=$row->cust_code?>"/></div></td>
                    <td>Customer Name</td>
                    <td><div class="autocomplete col-sm-12"><input id="cust_name" class="form-control" name="cust_name"  type="text" value="<?=$cust_name->cust_name ?>"/></div></td>
                </tr>
                    </tbody>       
           </table>

           <table>

            <div class="align-right btnstyle" >

            

            <button type="submit" class="btn btn-primary btnattr_cmn" name="updateusers">
            <i class="fa  fa-save"></i>&nbsp; Update
            </button>
                                                              

            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
            <i class="fa fa-eraser"></i>&nbsp; Reset
            </button>   

            </div>
            </table>
            </div>
            <input type="text" class="hidden" name="user_type" id="user_type" value="<?=getusertype($db,$usr_group)?>">
            <div class="change_password" style="margin-top:10px;background:#fff;border:1px solid var(--heading-primary)">
            <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Change Password</p>
                <div class="align-right" style="margin-top:-30px;">
                

            <a style="color:white" href="<?php echo __ROOT__ . 'userlist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
             </div>
          
            </div> 
            <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody >
              <tr>	            
                <td class="">New Password</td>
			      	<td><input id="password_1" class="form-control" name="password_1" placeholder="********" type="text"/>  
                 </td>
                <td class="">Confirm Password</td>
			  	<td><input id="password_2" name="password_2" class="form-control" placeholder="********" type="text" value=""/>              
              </td>
             </tr>
                    </tbody>       
           </table>
           <div class="align-right btnstyle">

            <button type="button" class="btn btn-primary btnattr_cmn" name="changepassword" id="changepassword">
            <i class="fa  fa-save"></i>&nbsp; Update
            </button>
                                                            
            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
            <i class="fa fa-eraser"></i>&nbsp; Reset
            </button>   

            </div>
           </div>
        </form>
  <!-- Fetching Mode Details in Table  -->
            </div>
            </div>
  </section>
<!-- Modal -->
<div class="modal fade" id="comp_code" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Station Codes</b></h5>

            </div>
            <div class="modal-body" style="height: 500px;">
                
                <p style="background-color: white;"> <?=loadcompany($db,$row)?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="get-selected" class="btn btn-primary" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
  </div>
  <?php
?>
 <script>
        $(function () {
            $("#get-selected").click(function () {
                var open = [];
                console.log()
                $.each($("input:checkbox[name='day']:checked"), function () {
                    open.push($(this).val());
                    console.log(open);
                });
                var stn_code = open.join(",");
                
                $("#compcode").val(stn_code);
                loadbranches(open);
            })
        });
    </script>
<!-- inline scripts related to this page -->
<script>
    $('#changepassword').click(function(){
        if($('#password_1').val().trim()=='' || $('#password_2').val().trim()==''){
            swal("Please Enter Password");
            return false;
        }
        else if ($('#password_1').val().trim() != $('#password_2').val().trim()){
            swal("New Password and Confirm Password must be Same");
        }
        else{
            $(this).attr('type','submit');
        }
    })
</script>
<script>
        $('#stncode').tokenize2();
        $('.tokens-container').css('margin','0');
                </script>
<script>
function resetFunction() {
    document.getElementById('TextBox1').value = "";
}


$(document).ready(function(){
        $('#data-table').DataTable();
     });

    
</script>

<script>
 //Dynamic field 
 var addbtn=document.querySelector("#addbtn");
  var i=1;
 addbtn.onclick=function(){
     i++;
     var div=document.createElement("tr");
     div.setAttribute("id","form_group");
     div.innerHTML=gen();
     document.getElementById("dynamic_form").appendChild(div); 
    }
     function gen()
     
     {
        return '<td>Company</td><td><input type="text" name="stncode[]"></td>';
     }
     function remove()
     {
        if(confirm("Are You sure Want to delete") == true)
        {
            document.getElementById('form_group').remove();
        }
        else{
            return false;
        }
    }

</script>
<script>
    $('#group_name').change(function(){
        var grp_name = $(this).val();
           $.ajax({
                url:"<?php echo __ROOT__ ?>peoplecontroller",
                method:"POST",
                data:{grp_name},
                dataType:"text",
                success:function(value)
                {
                   $('#user_type').val(value);
                   if(value.trim()=='I'){
                        $('#br_row').css('display','none');
                        $('#cust_row').css('display','none');
                   }
                   else if(value.trim()=='E-B'){
                        $('#br_row').css('display','');
                        $('#cust_row').css('display','none');
                   }
                   else if(value.trim()=='E-C'){
                        $('#br_row').css('display','');
                        $('#cust_row').css('display','');
                   }
                }
                });
    });
</script>
<script>
  var branchcode =[<?php  
   $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
   $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
   $rowuser = $datafetch['last_compcode'];
   if(!empty($rowuser)){
        $stationcode = $rowuser;
   }
   else{
       $userstations = $datafetch['stncodes'];
       $stations = explode(',',$userstations);
       $stationcode = $stations[0];     
   }
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];

 var branchname = [<?php
 $brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode'");
 while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
 {
 echo "'".$rowbrname['br_name']."',";
 }
 ?>]

</script>
<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);
</script>
<script>
   //Fetch Branch Name
   $('#br_code').keyup(function(){
        var branchlookup = $('#br_code').val();
        var br_stncode = '<?=$stationcode?>';
        $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{branchlookup:branchlookup,br_stncode},
            dataType:"text",
            success:function(data)
            {
            $('#br_name').val(data);
            fetchcustcode();
            }
    });
    });
      //Fetch Customer Name
      $('#cust_code').keyup(function(){
        var customerlookup = $('#cust_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{customerlookup:customerlookup},
            dataType:"text",
            success:function(data)
            {
            $('#cust_name').val(data);
            }
    });
    });
</script>
<script>
function fetchcustcode(){
    var branch8 = $('#br_code').val();
    var comp8 = '<?=$stationcode?>';
       $.ajax({
       url:"<?php echo __ROOT__ ?>invoicecontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(ratecustcode)
       {
           autocomplete(document.getElementById("cust_code"), ratecustcode); 
       }
       });
       $.ajax({
       url:"<?php echo __ROOT__ ?>invoicecontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(ratecustname)
       {
           autocomplete(document.getElementById("cust_name"), ratecustname); 
       }
       });
}

fetchcustcode();
</script>
<script>
    function loadbranches(stncodes){
        var station_codes = stncodes;
        //console.log(station_codes);
        $.ajax({
        url:"<?php echo __ROOT__ ?>peoplecontroller",
        method:"POST",
        data:{station_codes},
        dataType:"text",
        success:function(branches)
        {
            var brancharray = JSON.parse(branches);
            var html;
           
            for(var i=0;i<station_codes.length;i++){
                html += '<optgroup value="'+station_codes[i]+'" label="'+station_codes[i]+'">';
                for(var stn of brancharray[station_codes[i]]){
                     html += '<option value="'+station_codes[i]+':'+stn+'">'+stn+'</option>';
                }
                html += '</optgroup>';
                 $('#br_code').html(html);
            }
            console.log('stn loaded');
        }
        });
    }

    function getSelectValues(select) {
        var result = [];
        var options = select && select.options;
        var opt;

            for (var i=0, iLen=options.length; i<iLen; i++) {
                opt = options[i];

                if (opt.selected) {
                result.push(opt.value || opt.text);
                }
            }
        return result;
    }
</script>
<script>
    //JSON.parse('<?=$selectedstationsjson?>');
    var selectedstationsjson = <?=$selectedstationsjson?>;
    console.log(selectedstationsjson);
    loadbranches(selectedstationsjson); 
    
</script>