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

// if(authorize($db,$user)=='False'){

//     echo "<div class='alert alert-danger alert-dismissible'>
            
//             <h4><i class='icon fa fa-times'></i> Alert!</h4>
//             You don't have permission to access this Page.
//             </div>";
//             exit;
//   }

error_reporting(0);
    $usr_name = $_SESSION['user_name'];
    $get_data = $db->query("SELECT * FROM users WHERE user_name='$usr_name'");
    $row = $get_data->fetch(PDO::FETCH_OBJ);

?>
 <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  

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
                                        <li class="list-inline-item">People</li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Users</li>
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

                 ?>
              
           </div>
           <form method="post" action="<?php echo __ROOT__ ?>changepassword" id="form1" style="height: 100%">
           <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Change Password Form</p>
                <div class="align-right" style="margin-top:-30px;">
                

            <a style="color:white" href="<?php echo __ROOT__ . 'userlist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
          
            </div> 

            <div class="content panel-body">
                <div class="tab_form">
                <input id="user_email" type="text" class="form-control" style="display:none" value="<?=$row->user_email;?>" required="required" name="user_email"/>
                <table class="table" id="otp-section" cellspacing="0" cellpadding="0" align="center">	
                <tbody>
                 <!-- <tr>
                   <td>User Name</td>
                        <td><input id="user_name" class="form-control" name="user_name"  type="text" value="<?=$_SESSION['user_name']; ?>"/></td>
                        <td>Email</td> 
                        <td><input id="user_email" type="text" class="form-control" placeholder="Enter Email" required="required" name="user_email" autofocus/></td>
                        <td><button type="button" class="btn btn-primary btn-sm" id="otp-btn" onclick="checkemailisvalid()">Send OTP</button></td>  

                    </tr> -->     
                <tr>
                    <td><label for="password" class="text">Enter OTP</label></td>
                           <td> 
                               <div class="col-sm-12">
                                   <div class="col-sm-9">
                               <input id="otp1" type="text" class="form-control" required="required" name="otp1" autocomplete="off"/>
                               </div>
                               <div class="col-sm-3"><button type="button" class="btn btn-primary btn-sm" id="otp-btn" onclick="checkemailisvalid()">Send OTP</button>
                                </div>
                             </div>
                             <p id="otp-text" style="display:none;font-size:12px;font-weight:bold">OTP successfully sent to Your registred email</p>
                        </td>
            </tr>
            <tr>
                        <td>New Password</td>
                            <td><input id="new_password" type="password" class="form-control" placeholder="Enter New Password" required="required" name="new_password" autocomplete="off"/></td>
                    <td>Confirm Password</td>
                        <td><input id="confirm_password" type="password" class="form-control" placeholder="Confirm Password" required="required" name="confirm_password" autocomplete="off"/></td>
                    
                        <td><input type="button" name="changepassword" value="Change Password" onclick="sumbitpassword();" id="btnSubmit" class="btn btn-sm float-right" /></td>        

                    </tr>
                </tbody>
                </table>
<!-- 
                <table class="table" id="pswd-chn-section" style="display:none" cellspacing="0" cellpadding="0" align="center">	
                <tbody>

                <tr>
                <td>New Password</td>
                            <td><input id="new_password" type="password" class="form-control" placeholder="Enter New Password" required="required" name="new_password" autocomplete="off"/></td>
                    <td>Confirm Password</td>
                        <td><input id="confirm_password" type="password" class="form-control" placeholder="Confirm Password" required="required" name="confirm_password" autocomplete="off"/></td>
                    </tr>
                <tr>	
                    <td></td>
                    <td></td>
                    <td></td>
                <td><input type="button" name="changepassword" value="Change Password" onclick="sumbitpassword();" id="btnSubmit" class="btn btn-sm float-right" /></td>        
                </tr>
               
                </tbody>
                </table> -->

             </div> 
            </div> 
            </div> 
<!-- old setup -->

<script>
var uservalidemail = '<?=$row->user_email;?>';
    
document.onkeydown = function(e) {
        if (e.ctrlKey && ( e.keyCode === 85 || e.keyCode === 117)) {//Alt+c, Alt+v will also be disabled sadly.
           return false;
        }
};
</script>
<script>
    var otp2;
    function checkemailisvalid(){
    var rst_pswd_user_email = uservalidemail;
    if(rst_pswd_user_email.trim()==''){
        swal("Please Enter Your Email");
    }
    else if(rst_pswd_user_email.trim()!=uservalidemail.trim()){
        swal("Entered mail is not registered with your account");
    }
    else{
    $('#otp-btn').html("<i class='fa fa-spinner'></i>&nbsp; Sending");
    $.ajax({
        url:"<?php echo __ROOT__ ?>peoplecontroller",
        method:"POST",
        data:{rst_pswd_user_email},
        dataType:"text",
        success:function(data)
        {
            if(parseInt(data)>0){
                var otp = Math.floor(100000 + Math.random() * 900000);
                //send otp to mail
               var otpmail = uservalidemail;
                $.ajax({
                url:"<?php echo __ROOT__ ?>peoplecontroller",
                method:"POST",
                data:{sendotp:otp,otpmail},
                dataType:"text",
                success:function(data)
                {
                    $('#otp-btn').removeClass('btn-primary');
                    $('#otp-btn').addClass('btn-success');
                    $('#otp-btn').html('Re-Send OTP');
                    $('#otp-text').css('display','');
                }
                });
                //send otp to mail
                otp2 = otp;
            }
            else{
                $('#otp-btn').html("Send OTP");
                swal("There is no users Registred with this email");
            }
        }
        });
    }
    }

    checkemailisvalid();
</script>
<script>
    var mailvalidated = false;
function validateOTP(){
    var otp1 =  $('#otp1').val();
    if(otp1.trim()==''){
        swal("Please Enter OTP");
    }
    else{
    if(otp1==otp2){
        mailvalidated = true;
        //$('#otp-section').css('display','none');
        //$('#pswd-chn-section').css('display','');
    }
    else{
        mailvalidated = false;
        swal('Invalid OTP');
    }
    }
}
</script>
<script>
  function sumbitpassword(){
    var new_pass = $('#new_password').val();
    var conn_pass = $('#confirm_password').val();
    validateOTP();
    if(mailvalidated == false){
        swal("You are Not validated OTP with Mail");
    }
    else if(new_pass.trim()==''){
        swal("Please Enter Password");
    }   
    else{
        if(new_pass===conn_pass){
            $('#form1').submit();
        }
        else{
            swal('Password Not match');
        }
    }
  }
</script>