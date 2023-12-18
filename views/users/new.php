

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>
	CRM Login
</title><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><meta name="viewport" content="width=device-width, initial-scale=1" /><link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" />
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" />
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>

    <style type="text/css">
           @import url("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");

        html {
            
            background-image: linear-gradient(to bottom, #DE6262,#DE6262);
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
            background-image: linear-gradient(to bottom, #FFB88C,#DE6262);
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
        
    </style>
</head>
<body>
    <form method="post" action="./" id="form1" style="height: 100%">
<div class="aspNetHidden">
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="rgzLmGk/NxU/Ur4Bq6hU2ofU9ZC8VP9YOEUGyebGk+Zuy67KScF0KD1GfoTuWrGOJ06LSVIRTNQMktdYOWSYGKtof3sylG2KlKLLX4Q8Vvc=" />
</div>

<div class="aspNetHidden">

	<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="C2EE9ABB" />
	<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="MS/wUNAIcQz6ADriIOI/A/USFEdNBYLfWbGOptlckSf8NVEf9SOClGymrI/CHFVnurINlsyo8dtG7/1ewHPfXa/wiOK6TFTyETvzx+pcZLwc79/g3ioHuHZLgxj53RVL" />
</div>
        <div class="col-md-12">
        <section class="login-block">
            <div class="container">
                <div class="row">
                <form method="post" action="">
                    <div class="col-md-4 login-sec">
                        <img class="img-responsive" src="views/image/TPC-Logo.JPG" alt="Logo" height="400" width="400" />
                        <h2 class="text-center">Login Now</h2>
                       
                            <div class="form-group">
                                <label for="username" class="text">Username</label>
                                <input id="txtUsername" type="text" class="form-control" placeholder="Enter Username" required="required" name="user_name" autocomplete="off"/>
                            </div>
                            <div class="form-group">
                                <label for="password" class="text">Password</label>
                                <input id="txtPassword" type="password" class="form-control" placeholder="Enter Password" required="required" name="cpassword" autocomplete="off"/>
                            </div>
                            <div class="form-check">
                                <input type="submit" name="adminlogin" value="LogIn" onclick="return validate();" id="btnSubmit" class="btn btn-login float-right" />
                            </div>
                            <span id="lblerr" class="col-12 pt-sm-0 pt-2 pt-lg-2 pt-md-1 row text-danger small"></span>                                      
                    </div>
                </form>


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
                                                Customers don’t expect you to be perfect. But they do expect you to fix things when they go wrong. – Donald Porter<br /><br />
                                               
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
                                                Our greatest asset is the customer! Treat each customer as if they are the only one! – Laurice Leitao, Customer Service Professional SeraCare Life Sciences <br /><br />
                                                
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
                                                I’ve learned that people will forget what you said, people will forget what you did, but people will never forget how you made them feel.
–Maya Angelou, American Poet<br /><br />
                                                
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
   

        <script src="lib/jquery/jquery.min.js"></script>
        <script src="lib/bootstrap/js/bootstrap.min.js"></script>
        <!-- plugins:js -->
        <script src="Library/vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="Library/vendors/chart.js/Chart.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="Library/js/off-canvas.js"></script>
        <script src="Library/js/hoverable-collapse.js"></script>
        <script src="Library/js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="Library/js/dashboard.js"></script>
        <script src="Library/js/todolist.js"></script>
        <script src="UserScript/LoginScript.js"></script>

        <script>
        $(document).ready(function () {

            $(document).ajaxStart(function () {
                $("#wait").css("display", "block");
            });
            $(document).ajaxComplete(function () {
                $("#wait").css("display", "none");
            });
            $("#txtUserName").focus();
        });
        </script>

        <script>
            //$(document).ready(function () {
            //    $("body").height($(window).height());
            //    if ($(window).width() <= 568) {
            //        $("#main-div").removeClass("shadow").addClass("bg-transparent");
            //        $("#content").addClass("shadow");
            //    }
            //    else {
            //        $("#main-div").removeClass("bg-transparent").addClass("shadow");
            //        $("#content").removeClass("shadow");
            //    }
            //    $(window).resize(function () {
            //        $("body").height($(window).height());
            //        if ($(window).width() <= 568) {
            //            $("#main-div").removeClass("shadow").addClass("bg-transparent");
            //            $("#content").addClass("shadow");
            //        }
            //        else {
            //            $("#main-div").removeClass("bg-transparent").addClass("shadow");
            //            $("#content").removeClass("shadow");
            //        }
            //    });
            //    $("#chkBox").change(function () {
            //        if ($(this).is(":checked")) {
            //            $("#txtPassword").attr("type", "text");
            //        }
            //        else
            //            $("#txtPassword").attr("type", "password");
            //    });
            //});
            function validate() {
                $("#lblerr").text("");
                if ($("#txtUsername").val() === "") {
                    $(this).attr("placeholder", "Enter username");
                    $(this).focus();
                    return false;
                }
                if ($("#txtPassword").val() === "") 
                {
                    $(this).attr("placeholder", "Enter password");
                    $(this).focus();
                    return false;
                }
                return true;
            }
        </script>
  </form>
</body>
</html>
