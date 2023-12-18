<?php

 include('db.php');

 if(isset($_GET['id'])){
$otp_id = $_GET['id'];
// if(isset($_GET['cnotenumber'])){
  
//  $cnotenumber = $_GET['cnotenumber'];
//  $consign_number = $_GET['consign_number'];
//   $br_code = $_GET['br_code'];
//   $compcode = $_GET['compcode'];
  
 
//   $mobilenumber = substr($consign_number,7,9);

  $getotp = $db->query("SELECT * FROM otp WHERE otp_id='$otp_id'")->fetch(PDO::FETCH_OBJ);

  $cnotenumber = $getotp->cno;
  $consign_number = $getotp->consignment_number;
  $br_code = $getotp->br_code;
  $compcode = $getotp->comp_code;

  $mobilenumber = substr($consign_number,7,9);
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Document</title>
</head>
<style>
    .card {
  width: 350px;
  padding: 10px;
  border-radius: 20px;
  background: #fff;
  border: none;
  height: 390px;
  position: relative;
}

.container {
  height: 100vh;
}

body {
  background: #eee;
}

.mobile-text {
  color: black;
  font-size: 15px;
}

.form-control {
  margin-right: 12px;
}

.form-control:focus {
  color: #495057;
  background-color: #fff;
  border-color: #ff8880;
  outline: 0;
  box-shadow: none;
}

.cursor {
  cursor: pointer;
}
</style>
<body>

    <div class="d-flex justify-content-center align-items-center container">
    
        <div class="card py-5 px-3" style="margin-bottom:300px">
        <img src="views/image/TPC-Logo.jpg" class="image" width="310px" style="margin-bottom:20px;">
        <p style="display:none;" id="resendotp_msg">otp will resend</p>  <p style="display:none;" id="collapse_msg">otp will Expired</p>
            <h5  class="m-0 text-primary">Mobile phone verification</h5><span class="mobile-text">Enter the code we just send on your mobile phone <b class="text-primary">+91 *******<?=$mobilenumber;?></b></span>
            <div class="d-flex flex-row mt-5">
              <input type="text" class="form-control inputs" maxlength="1" id="otpbox1" autofocus="" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <input type="text" id="otpbox2" maxlength="1" class="form-control inputs" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <input type="text" id="otpbox3" maxlength="1" class="form-control inputs" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <input type="text" id="otpbox4" maxlength="1" class="form-control inputs" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></div>
            <div class="text-center mt-5">
              <!-- <span class="d-block mobile-text">Don't receive the code?</span> -->
              <button type="submit" class="btn btn-success" id="submitotp"><span class="font-weight-bold text-white cursor">Submit</span></button>
         
          <span class="d-block mobile-text" style="color:blue;margin-top:10px;" id="countdown"></span>
          <span class="d-block mobile-text" style="color:blue;" id="resend"></span>
       
            </div>
           
        </div>
    </div>
</body>
<script>
  $(".inputs").keyup(function () {
    if (this.value.length == this.maxLength) {
      var $next = $(this).next('.inputs');
      if ($next.length)
          $(this).next('.inputs').focus();
      else
          $(this).blur();
    }
});
</script>
<script>
  $(document).ready(function(){
   $("#submitotp").click(function(){
    var cnote = '<?=$cnotenumber;?>';
    var consnumber = '<?=$consign_number;?>';
    var branch = '<?=$br_code;?>';
    var station = '<?=$compcode;?>';
    var otp_pin = ($("#otpbox1").val()+$("#otpbox2").val()+$("#otpbox3").val()+$("#otpbox4").val()).trim();
   
    $.ajax({
                url:"<?php echo __ROOT__ ?>otplogin",
                method:"POST",
                data:{otp_pin,cnote,consnumber,branch,station,action : 'genratechallan'},
                dataType:"text",
                success:function(otplogin)
                {
                  data1 = JSON.parse(otplogin);
                  console.log(data1[4]);
                  console.log(otp_pin);
                    if(otp_pin==data1[4]){
                      window.open('<?php echo __ROOT__ ?>msgcashchallan?cno='+cnote+'&stationcode='+station+'');
                    }else{
                      swal("Enter Valid Otp");
                          setTimeout(function() { 
                            history.go(-1);
                        },4000);
                    }
                
                }
            });
   });
  });
</script>
<script>
   $(function() {
        $('input').on('keypress', function(e) {
            if (e.which == 32){
                console.log('Space Detected');
                return false;
            }
        });
});
</script>
<!-- <script>
  let timerOn = true;
function timer(remaining) {
  var m = Math.floor(remaining / 60);
  var s = remaining % 60;
  m = m < 10 ? "0" + m : m;
  s = s < 10 ? "0" + s : s;
  document.getElementById("countdown").innerHTML = `Time left: ${m} : ${s}`;
  remaining -= 1;
  if (remaining >= 0 && timerOn) {
    setTimeout(function () {
      timer(remaining);
    }, 1000);
    document.getElementById("resend").innerHTML = `
    `;
    return;
  }
  if (!timerOn) {
    return;
  }
  function ajaxCall() {
    var cnote = '<?=$cnotenumber;?>';
    var consnumber = '<?=$consign_number;?>';
    var branch = '<?=$br_code;?>';
    var station = '<?=$compcode;?>';

    $("#resendotp_msg").css('display','none');
    $("#collapse_msg").css('display','block');

    $.ajax({
                url:"<?php echo __ROOT__ ?>otplogin",
                method:"POST",
                data:{cnote,consnumber,branch,station,action : 'collapseotp'},
                dataType:"text",
                success:function(collapseotp)
                {
                  console.log(collapseotp);
                  
                }
    });
  }
  //ajaxCall();
  document.getElementById("resend").innerHTML = `Don't receive the code? 
  <span class="font-weight-bold text-color cursor" onclick="timer(60)">Resend
  </span>`;
}
timer(60);
console.log(timer(60));
</script> -->

<script>
  $(document).ready(function(){
    $("#resend").click(function(){
      var cnote = '<?=$cnotenumber;?>';
    var consnumber = '<?=$consign_number;?>';
    var branch = '<?=$br_code;?>';
    var station = '<?=$compcode;?>';
    var resendotp = generateOTP();
   
    function generateOTP() {
          
          // Declare a string variable 
          // which stores all string
          var string = '0123456789';
          let OTP = '';
            
          // Find the length of string
          var len = string.length;
          for (let i = 0; i < 4; i++ ) {
              OTP += string[Math.floor(Math.random() * len)];
          }
          return OTP;
      }
      $("#collapse_msg").css('display','none');
     $("#resendotp_msg").css('display','block');

    $.ajax({
                url:"<?php echo __ROOT__ ?>otplogin",
                method:"POST",
                data:{resendotp,cnote,consnumber,branch,station,action : 'resendotp'},
                dataType:"text",
                success:function(resend)
                {
                  console.log(resend);
                
                }
    });
  });
});
</script>
</html>