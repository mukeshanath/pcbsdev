<?php

defined('__ROOT__') OR exit('No direct script access allowed');



include 'db.php'; 
// require_once ('models/login.php');



 class otplogin extends My_controller
 {
     public function index()
     {
         $this->view->title = __SITE_NAME__ . ' - otplogin';
         $this->view->render_in('users/otplogin');
     }

 }

 if(isset($_POST['action']) && $_POST['action'] == 'genratechallan'){
    $cnote = $_POST['cnote'];
    $consnumber  = $_POST['consnumber'];
    $branch = $_POST['branch'];
    $station = $_POST['station'];

    $selectotp = $db->query("SELECT otp FROM otp where cno='$cnote' AND consignment_number='$consnumber' AND br_code='$branch' AND comp_code='$station'")->fetch(PDO::FETCH_OBJ);
    $getotp = $selectotp->otp;
    echo json_encode(array($cnote,$consnumber,$branch,$station,$getotp));
    exit();
  }


//   if(isset($_POST['action']) && $_POST['action'] == 'resendotp'){
//     $cnote = $_POST['cnote'];
//     $consnumber  = $_POST['consnumber'];
//     $branch = $_POST['branch'];
//     $station = $_POST['station'];
//     $resendotp = $_POST['resendotp'];

//     $message123 = "Your Resend Otp '$resendotp' to genrate cashchallam : AMB1065";
 
//        $cash_voucher_smsgw = "https://pgapi.vispl.in/fe/api/v1/send?username=theproftrnpg.trans&password=mokcc&unicode=false&from=PCBS&to=$consignee_mob&dltContentId=XXXXXXXXXX&text=$message123";

//     $selectotp = $db->query("UPDATE otp SET otp='$resendotp' where cno='$cnote' AND consignment_number='$consnumber' AND br_code='$branch' AND comp_code='$station'");
//     echo json_encode(array($cnote,$consnumber,$branch,$station,$resendotp));
//     exit();
//   }

//   if(isset($_POST['action']) && $_POST['action'] == 'collapseotp'){
//     $cnote = $_POST['cnote'];
//     $consnumber  = $_POST['consnumber'];
//     $branch = $_POST['branch'];
//     $station = $_POST['station'];
//     $resendotp = $_POST['resendotp'];

//     $selectotp = $db->query("UPDATE otp SET otp=NULL where cno='$cnote' AND consignment_number='$consnumber' AND br_code='$branch' AND comp_code='$station'");
//     $selectotp = $db->query("SELECT otp FROM otp where cno='$cnote' AND consignment_number='$consnumber' AND br_code='$branch' AND comp_code='$station'")->fetch(PDO::FETCH_OBJ);
//     $getotp = $selectotp->otp;
//     echo json_encode(array("colappse"));
//     exit();
//   }


//   if(isset($_POST['action']) && $_POST['action'] == 'getnullvalues'){
//     $cnote = $_POST['cnote'];
//     $consnumber  = $_POST['consnumber'];
//     $branch = $_POST['branch'];
//     $station = $_POST['station'];
//     $resendotp = $_POST['resendotp'];

//     $selectotp = $db->query("SELECT otp FROM otp where cno='$cnote' AND consignment_number='$consnumber' AND br_code='$branch' AND comp_code='$station'")->fetch(PDO::FETCH_OBJ);
//     $getotp = $selectotp->otp;
//     echo json_encode($getotp);
//     exit();
//   }
?>