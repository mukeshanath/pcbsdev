<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>
	PCBS 
</title><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><meta name="viewport" content="width=device-width, initial-scale=1" /><link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" />
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script> -->
   <!-- sweet alert -->
   <script src="vendor/jquery/sweetalert.min.js"></script>
    <style type="text/css">
           @import url("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");

        html {
            
            background-image: linear-gradient(to bottom, #2160A0,#6fb3e0);
            background-repeat:no-repeat;
            background-size: 100% 100%;
            width:100%;
            height:100%;
        }
        .login-block {
          
            float: left;
            position: relative;
            width: 100%;
            margin-top: 5%;
           
        }

        .banner-sec {
            background-size: cover;
            min-height: 500px;
            border-radius: 0 10px 10px 0;
            padding: 0;
        }

        .container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 15px 20px 0px rgba(0,0,0,0.1);
        }
        .carousel-inner {
            border-radius: 0 10px 10px 0;
            height: 500px;
           
        }

        .carousel-caption {
            text-align: left;
            left: 5%;
        }

        .login-sec {
            padding: 20px 30px;
            position: relative;
        }
         .login-sec .copy-text {
                position: absolute;
                width: 80%;
                bottom: 20px;
                font-size: 13px;
                text-align: center;
            }

                .login-sec .copy-text i {
                    color: #FEB58A;
                }

                .login-sec .copy-text a {
                    color: #E36262;
                }

            .login-sec h2 {
                margin-bottom: 30px;
                font-weight: 800;
                font-size: 30px;
                color: #DE6262;
            }
             .login-sec h2:after {
                    content: " ";
                    width: 100px;
                    height: 5px;
                    background: #FEB58A;
                    display: block;
                    margin-top: 30px;
                    border-radius: 3px;
                    margin-left: auto;
                    margin-right: auto;
                }

        .btn-login {
            background: #DE6262;
            color: #fff;
            font-weight: 600;
        }
         .banner-text {
            width: 120%;
            /*position: absolute;*/
            bottom: 40px;
            padding-left: 20px;
            padding-right:20px;
            background-image: linear-gradient(to bottom, #2160A0,#2160A0);
            border-radius:20px;
            opacity:0.8;
        }

            .banner-text h2 {
                font-family:'Book Antiqua';
                color: #fff;
                font-weight: 600;
            }

                .banner-text h2::after {
                    content: " ";
                    width: 100px;
                    height: 5px;
                    background: #FFF;
                    display: block;
                    margin-top: 10px;
                    border-radius: 3px;
                }

            .banner-text p {
                font-family:Courier New, Courier, monospace;
                font-weight: bold;
                margin-top: 10px;
                color: #fff;
                text-align: left;
            }
        
            /* for rotate spinner */
.fa-spinner{
  -webkit-animation:spin 2s linear infinite;
  -moz-animation:spin 2s linear infinite;
  animation:spin 2s linear infinite;
   } 
   @-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); }}
/* for rotate spinner */
    </style>
</head>
<body oncontextmenu="return false">
    <form method="post" action="<?php echo __ROOT__ ?>forgetpassword" id="form1" style="height: 100%">
    
        <div class="col-md-12">
        <section class="login-block">
            <div class="container">
                <div class="row">
                
                    <div class="col-md-4 login-sec">
                        <img class="img-responsive" src="views/image/TPC-Logo.JPG" alt="Logo" height="400" width="400" />
                        <h2 class="text-center">Login Now</h2>
                        <div class="alert-primary" role="alert">

                        <?php 
                        
                            
                            if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 1 )
                            {
                                echo    "<p class='remember_me' style='color: red;'>
                                            Access denied !!
                                        </p>";
                            }
                            if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 2 )
                            {
                                echo    "<p class='remember_me' style='color: red;'>
                                            Wrong User Name!!
                                        </p>";
                            }

                            if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 3 )
                            {
                                echo    "<p class='remember_me' style='color: red;'>
                                            Enter the Username please..
                                        </p>";
                            }

                            if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 4 )
                            {
                                echo    "<p class='remember_me' style='color: red;'>
                                        New Password and Confirm Password not match..
                                        </p>";
                            }

                            if ( isset($_GET['success']) && $_GET['success'] == 4 )
                            {
                                echo    "<p class='remember_me' style='color: default;'>
                                           New Password and Confirm Password not match..
                                        </p>";
                            }
                        
                            ?>
                            </div>
                       <div id="otp-section">
                            <div class="form-group">
                                <label for="username" class="text">Enter Email</label>
                                <input id="user_email" type="text" class="form-control" placeholder="Enter Email" required="required" name="user_email" autofocus/>
                            </div>
                            <div class="form-group" style="text-align:right">
                                <button type="button" class="btn btn-primary" id="otp-btn" onclick="checkemailisvalid()">Send OTP</button>
                            </div>
                            <div class="form-group">
                                <label for="password" class="text">Enter OTP</label>
                                <input id="otp1" type="number" class="form-control" required="required" name="otp1" autocomplete="off"/>
                            </div>
                            <div class="form-check">
                            <a href="login">Back to Login</a>
                                <input type="button" name="changepassword" value="Submit" onclick="validateOTP();" id="btnSubmit" class="btn btn-login float-right" />
                            </div>

                        </div>

                        <div id="pswd-chn-section" style="display:none">
                        <div class="form-group">
                                <label for="username" class="text">New Password</label>
                                <input id="new_password" type="password" class="form-control" placeholder="Enter New Password" required="required" name="new_password" autocomplete="off"/>
                            </div>
                        <div class="form-group">
                                <label for="username" class="text">Confirm Password</label>
                                <input id="confirm_password" type="password" class="form-control" placeholder="Confirm Password" required="required" name="confirm_password" autocomplete="off"/>
                            </div>
                            <input type="button" name="changepassword" value="Change Password" onclick="sumbitpassword();" id="btnSubmit" class="btn btn-login float-right" />
                        </div>
                            <span id="lblerr" class="col-12 pt-sm-0 pt-2 pt-lg-2 pt-md-1 row text-danger small"></span>

                    </div>

                    <div class="col-md-8 banner-sec">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <img class="img-responsive" src="views/image/photo1.jpg" alt="First slide" />
                                    <div class="carousel-caption d-none d-md-block">
                                        <div class="banner-text" >
                                            <h2>Customer Expectation</h2>
                                            <p>
                                                Customers don't expect you to be perfect. But they do expect you to fix things when they go wrong. - Donald Porter<br /><br />
                                               
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img class="img-responsive" src="views/image/photo3.jpg" alt="First slide" />
                                    <div class="carousel-caption d-none d-md-block">
                                        <div class="banner-text" style="margin-top:-210px;">
                                            <h2>Customer Retention</h2>
                                            <p>
                                                Our greatest asset is the customer! Treat each customer as if they are the only one! - Laurice Leitao, Customer Service Professional SeraCare Life Sciences <br /><br />
                                                
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img class="img-responsive" src="views/image/photo2.jpg" alt="First slide" />
                                    <div class="carousel-caption d-none d-md-block">
                                        <div class="banner-text">
                                            <h2>Customer Satisfaction</h2>
                                            <p>
                                                I've learned that people will forget what you said, people will forget what you did, but people will never forget how you made them feel.
- Maya Angelou, American Poet<br /><br />
                                                
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
   
      

  </form>
  <script>
    document.onkeydown = function(e) {
            if (e.ctrlKey && ( e.keyCode === 85 || e.keyCode === 117)) {//Alt+c, Alt+v will also be disabled sadly.
               return false;
            }
    };
    </script>
<script>
     var otp2;
        function checkemailisvalid(){
            
        var rst_pswd_user_email = $('#user_email').val();
        if(rst_pswd_user_email.trim()==''){
            swal("Please Enter Your Email");
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
                console.log(data);
                if(parseInt(data)>0){
                    var otp = Math.floor(100000 + Math.random() * 900000);
                    console.log(otp);
                    //send otp to mail
                   var otpmail = $('#user_email').val();
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
                        swal("OTP Sent successfully");
                    }
                    });
                    //send otp to mail
                    otp2 =otp;
                    
                }
                else{
                    $('#otp-btn').html("Send OTP");
                    swal("There is no users Registred with this email");
                }
            }
            });
        }
        }
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
            $('#otp-section').css('display','none');
            $('#pswd-chn-section').css('display','');
        }
        else{
            swal('Invalid OTP');
        }
        }
    }
</script>
<script>
      function sumbitpassword(){
        var new_pass = $('#new_password').val();
        var conn_pass = $('#confirm_password').val();
        if(new_pass.trim()==''){
            swal("Please Enter Password");
        } 
        else if(mailvalidated == false){
        swal("You are Not validated OTP with Mail");
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
</body>
</html>
