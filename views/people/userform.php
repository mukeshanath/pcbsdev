<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 



function authorize($db,$user)
{
    
    $module = 'userform';
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

function loadstationcode($db)
    {
        $selected = array();
        $output='';
        $query = $db->query('SELECT * FROM station ORDER BY stnid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code   
        $output.= '<option value="' . $row->stncode . '"' . (in_array($row->stncode, $selected) ? ' selected' : '') . '>' . $row->stncode . '</option>'; 
        // $output .='<option  value="'.$row->stncode.'">'.$row->stncode.'</option>';
         }
         return $output;    
        
    }

    function loadusergroup($db)
    {
        $output='';
        $query = $db->query("SELECT DISTINCT group_name FROM users_group");
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->group_name.'">'.$row->group_name.'</option>';
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
            <form method="POST" action="<?php echo __ROOT__ ?>userform" autocomplete="off" class="form-horizontal" name="formdestination" id="formvendors" >
            <input placeholder="" class="form-control border-form" _name="user_name" type="text" _id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">User Form</p>
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
             <td >User Name</td>
			      	<td><input id="user_name" class="form-control" name="user_name" required type="text" value=""/></td>
              <td >Email</td>
			   	<td><input id="user_email" class="form-control" name="usr_email" required type="text" value=""/></td>
              </tr>

              <tr>	
              <td class="">Password</td>
			      	<td><input id="password_1" class="form-control" name="password_1" required type="text" value=""/>
              </td>
              <td class="">Confirm Password</td>
			  	<td><input id="password_2" name="password_2" class="form-control" required type="text" value=""/>              
              </td>
             </tr>
             <tr>	
             <td >User Group</td>
			      	<td><select class="form-control" id="group_name" name="group_name">
                      <option value="">Select Group</option>
                          <?php echo loadusergroup($db); ?>
                          </select></td>

             <td class="">Active</td>
              <td>
              <select class="form-control border-form" name="usr_status" id="usr_status">
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                  </select>
              </td>        
             </tr>
             </tbody>
             <tbody id="dynamic_form">
             <tr>	
              <td>Company</td>
              <td><div class="autocomplete col-sm-12">
                    <div class="col-sm-11">
                    <input type="text" class="form-control border-form" id="stncode" name="stncode" readonly></div>
                    <div class="col-sm-1">
                    <button type="button"  data-toggle="modal" class="btn btn-primary col-sm-12" style="height:33px" data-target="#comp_code"><i style="font-weight:bold"><i style="font-weight:bold">i</i></button>
                    </div>
                    </div> <p id="a_validation" class="form_valid_strings">Enter Valid Code</p></td>
              <!-- <td style="width:450px"> -->
              <!-- <select class="col-sm-12" id="stncode" name="stncode[]" multiple>
              <?=loadstationcode($db)?>
              </select> -->
              
              <!-- </td> -->
              <!-- <td><span>
              <button style='margin-left:-250px;' type="button" class="btn btn-success btn-xs" id="addbtn" ><i class="fa fa-plus-circle"></i></button>
              </span>
              <span><button type="button" style="margin-left:4px;" class="btn btn-danger btn-xs" onclick="remove(this)" id="remove" ><i class="fa fa-minus-circle"></i></button></span>
            </td>                          -->
             </tr>
             <tr>
                <td>Branch Codes</td>
                <td>
                <div class="col-sm-12">
                <select id="br_code" class="form-control " name="br_code[]" _onkeyup="fetchcustomer()" multiple value="">
                
                </select>
                </div></td>
                   </tr>
             <tbody>
             <!-- <tr id="br_row" style="display:none">
                <td>Branch Code</td>
                <td>
                <div class="autocomplete col-sm-12">
                <input id="br_code" class="form-control" name="br_code" onkeyup="fetchcustomer()" type="text" value=""/></div></td>
                 <td>Branch Name</td>
                <td><div class="autocomplete col-sm-12"><input id="br_name" onkeyup="fetchcustomer()" class="form-control" name="br_name"  type="text" value=""/></div></td> 
            </tr> -->
            <tr id="cust_row" style="display:none">
                <td>Customer Code</td>
                <td><div class="autocomplete col-sm-12"><input id="cust_code" class="form-control" name="cust_code"  type="text" value=""/></div></td>
                <td>Customer Name</td>
                <td><div class="autocomplete col-sm-12"><input id="cust_name" class="form-control" name="cust_name"  type="text" value=""/></div></td>
           </tr>
             </tbody>         
             </tbody>         

           </table>
           <table class="table" cellspacing="0" cellpadding="0" align="center">
           
           </table>
           <table>

            <div class="align-right btnstyle" >

            <button type="button" id="addanbutton" onclick="saveandadd()" class="btn btn-success btnattr_cmn" name="addanusers">
            <i class="fa  fa-save"></i>&nbsp; Save & Add
            </button>

            <button type="button" id="addbutton" onclick="save()" class="btn btn-primary btnattr_cmn" name="addusers">
            <i class="fa  fa-save"></i>&nbsp; Save 
            </button>
                                                              

            <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
            <i class="fa fa-eraser"></i>&nbsp; Reset
            </button>   

            </div>
            </table>
           
            <input type="text" class="hidden" name="user_type" id="user_type">
        </form>
  <!-- Fetching Mode Details in Table  -->
          
            </div>
            </div>
            </div>
  </section>

<?php





?>
  <!-- Button trigger modal -->
  <!-- Station Codes Modal -->
  <div class="modal fade" id="comp_code" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Station Codes</b></h5>

            </div>
            <div class="modal-body" style="height: 500px;">
                
                <p style="background-color: white;"> <?=loadcompany($db)?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="get-selected" class="btn btn-primary" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
  </div>
  <!-- Modal END -->
  <!-- Branch Codes Modal -->
  <div class="modal fade" id="brcodemodel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Branch Codes</b></h5>

            </div>
            <div class="modal-body" style="height: 500px;">
                
                <p style="background-color: white;"> <?=loadcompany($db)?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="get-selected" class="btn btn-primary" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
  </div>
    <!-- Modal END -->

  <?php



function loadcompany($db)
{
    $output='';
    $query = $db->query("SELECT stncode FROM station ORDER BY stncode ASC");

    
    $output = '<div class="column1">';

    $divcount = 0;
    while($row = $query->fetch(PDO::FETCH_OBJ)){ 
       $divcount++;
       if($divcount>10){  $divcount = 1;
         $output .= '</div><div class="column1">';
       }
       $output .= "<input type='checkbox' class='chlarge' name='day' value='$row->stncode'><label>".$row->stncode."</label><p></p>";
        
      
     }
     $output .= '</div>';
     return $output;    
    
}







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
                $("#stncode").val(stn_code);
                loadbranches(open);
            })

        });
    </script>
<!-- inline scripts related to this page -->
<script>
    function saveandadd()
    {
        $('#addanbutton').attr('type','button');

        if($('#user_name').val().trim()=='')
        {
            swal('Please enter the user name');
        }
        else if($('#user_email').val().trim()=='')
        {
            swal('Please enter the users email id');
        }
        else if($('#password_1').val().trim()=='')
        {
            swal('Please enter a password');
        }
        else if($('#password_2').val().trim()=='')
        {
            swal('Please re-enter the entered password');
        }
        else if($('#group_name').val().trim()=='')
        {
            swal('Please Select User Group');
        }
        else if($('#stncode').val().length==0){
            swal('Please Select Company');
        }
        else{
            $('#addanbutton').attr('type','submit');
        }
    }
    function save(){
        $('#addbutton').attr('type','button');
        if($('#user_name').val().trim()=='')
        {
            swal('Please enter the user name');
        }
        else if($('#user_email').val().trim()=='')
        {
            swal('Please enter the users email id');
        }
        else if($('#password_1').val().trim()=='')
        {
            swal('Please enter a password');
        }
        else if($('#password_2').val().trim()=='')
        {
            swal('Please re-enter the entered password');
        }
        else if($('#group_name').val().trim()=='')
        {
            swal('Please Select User Group');
        }
        else if($('#stncode').val().length==0){
            swal('Please Select Company');
        }
        else{
            $('#addbutton').attr('type','submit');
        }
    }
    </script>
<script>
  $('#stncode').tokenize2();
  $('.tokens-container').css('margin','0');
//   $('.token-search').find('input').attr('placeholder','multi select');
//   $('.token-search').find('input').css('width','100%');
</script>
<script>
function resetFunction() {
    document.getElementById('TextBox1').value = "";
    alert($('#stncode').val());
}


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
           // $('#stncode').val(data[0]);
           // $('#stnname').val(data[1]);

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
                  //  $('#stncode').val(data[0]);
                  //  $('#stnname').val(data[1]);
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
function fetchcustomer(){
   var branch8 = $('#br_code').val();
   var comp8 = '<?=$stationcode?>';
   $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("cust_code"), cust8); 
       }
    });
    $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(custname)
       {
           autocomplete(document.getElementById("cust_name"), custname); 
       }
       });
}
</script>
<script>
    function loadbranches(stncodes){
        var station_codes = stncodes;
        console.log(station_codes);
        $.ajax({
        url:"<?php echo __ROOT__ ?>peoplecontroller",
        method:"POST",
        data:{station_codes},
        dataType:"text",
        success:function(branches)
        {
            console.log(branches);

            var brancharray = JSON.parse(branches);
            console.log(station_codes.length);
            var html;
            // for(var stn of branches){
            //     console.log(stn);
            // }
            for(var i=0;i<station_codes.length;i++){
                html += '<optgroup value="'+station_codes[i]+'" label="'+station_codes[i]+'">';
                for(var stn of brancharray[station_codes[i]]){
                     console.log(stn);
                     html += '<option value="'+station_codes[i]+':'+stn+'">'+stn+'</option>';
                }
                html += '</optgroup>';
                // html += '<option value="">'+brancharray[i]+'</option>';
                 $('#br_code').html(html);
            }
            //$('#stncode').html('<option value=""></option>');
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