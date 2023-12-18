<?php 
/* File name   : tax.php
 Begin       : 2020-03-04
 Description : class tax file saves master tax slab in appliaction
               Default Header and Footer

 Author: zabiullah

 (c) Copyright:
              codim solutions
============================================================+*/

/**
 * render the tax in to class 
 * get database string to establish connection from sql server
 * Capture the tax data and save ion to database
 * @author zabi  
 * @since 2020-03-04
 */

/* Include the main tax master (search for installation path). */

defined('__ROOT__') OR exit('No direct script access allowed');

 include 'db.php'; 

	 class peoplecontroller extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - fetch';
             // $this->view->render('cnote/fetch');
          }
      }
      error_reporting(0);
      date_default_timezone_set('Asia/Kolkata');

      require 'controllers/phpmailer/PHPMailerAutoload.php';

       //Fetch user station
        if(isset($_POST['usr_name']))
        {  
            $usr_name = $_POST['usr_name'];
            $usr_tb="SELECT * FROM users WHERE user_name='$usr_name'";
            $user_data = $db->query($usr_tb);
            $user_row = $user_data->fetch(PDO::FETCH_ASSOC);

            $user = $user_row['last_compcode'];
            if(!empty($user)){
                $companies = $db->query("SELECT stncode,stnname FROM stncode WHERE stncode='$user'");
                $row_companies = $companies->fetch(PDO::FETCH_ASSOC);
                echo $user.",".$row_companies['stnname'].","."fgfg";
            }
            else{
                $userstations = $user_row['stncodes'];
                $stations = explode(',',$userstations);
                $station0 = $stations[0];
                $companies = $db->query("SELECT stncode,stnname FROM stncode WHERE stncode='$station0'");
                $row_companies = $companies->fetch(PDO::FETCH_ASSOC);
                echo $stations[0].",".$row_companies['stnname'].","."fgfg";
            }
          }


      

          //check permissions for user
        if(isset($_POST['page_name'])){
          $pagename = $_POST['page_name'];
          $page_user = $_POST['page_user'];
          echo authorize($db,$page_user,$pagename);
        }
        function authorize($db,$page_user,$pagename)
          {
              
              $module = $pagename;
              $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$page_user'");
              $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
              $user_group = $rowusergrp->group_name;

              $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
                  if($verify_user->rowCount()=='0'){
                      return 'False';
                  }
                  else{
                      return 'True';
                  }
          }
        //delete user group
         if(isset($_POST['deletetb'])){
            $deletetb = $_POST['deletetb'];
            $deleterow = $_POST['deleterow'];
            $deleteuuser = $_POST['deleteuuser'];
            $delete = $db->query("DELETE FROM $deletetb WHERE group_name='$deleterow'");
            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$deletetb','$deletetb','Deleted','User Group $deleterow has been Deleted','$deleteuuser',getdate())");

          }
         //delete user data
         if(isset($_POST['deleteutb'])){
          $deleteutb = $_POST['deleteutb'];
          $deleteurow = $_POST['deleteurow'];
          $deleteucol = $_POST['deleteucol'];
          $delu_user_name = $_POST['delu_user_name'];
          $selectfromdelete = $db->query("SELECT user_name FROM users WHERE $deleteucol='$deleteurow'")->fetch(PDO::FETCH_OBJ);
          $delete = $db->query("DELETE FROM $deleteutb WHERE $deleteucol='$deleteurow'");
          $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$deleteutb','$deleteutb','Deleted','User $selectfromdelete->user_name has been Deleted','$delu_user_name',getdate())");
          echo json_encode(array("status"=>200,"message"=>"User Deleted Successfully"));
        }
          //check user group Internal or External for user form
          if(isset($_POST['grp_name'])){
             echo getusertype($db,$_POST['grp_name']);
          }
          function getusertype($db,$grp_name){
              $group = $db->query("SELECT TOP 1 * FROM users_group WHERE group_name='$grp_name'")->fetch(PDO::FETCH_OBJ);
              return $group->user_type;
          }
          if(isset($_POST['cust_code'])){
               $custcode = $_POST['cust_code'];
               $comp_code  = $_POST['comp_code'];
               $valid_br = $db->query("SELECT br_code FROM customer WHERE cust_code='AMB1064' AND comp_code='$comp_code'")->fetch(PDO::FETCH_OBJ);
               echo $valid_br->br_code;
             }
        if(isset($_POST['rst_pswd_user_email'])){
          echo validateuseremail($db,$_POST['rst_pswd_user_email']);
        }
        function validateuseremail($db,$email){
            $count = $db->query("SELECT count(*) as row_count FROM users WHERE user_email='$email'")->fetch(PDO::FETCH_OBJ);
            return $count->row_count;
        }
        if(isset($_POST['sendotp'])){
          sendotptomail($_POST['sendotp'],$_POST['otpmail']);
        }
        function sendotptomail($sendotp,$otpmail){
            
        }

        //send otp for change password
        if(isset($_POST['sendotp'])){
          echo sendmailotp($_POST['sendotp'],$_POST['otpmail']);
        }

        function sendmailotp($otp,$tomail){
            $mail = new PHPMailer;
            $mail->isSMTP();                            // Set mailer to use SMTP
            $mail->Host = 'smtp.bizmail.yahoo.com';   // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                     // Enable SMTP authentication
            $mail->Username = 'pcbs@pcnl.in'; // your email id
            $mail->Password = 'hcygtgklfglvigvw'; // your password                      
            $mail->SMTPSecure = 'ssl';                  
            $mail->Port = 465;     //587 is used for Outgoing Mail (SMTP) Server.
            $mail->setFrom('pcbs@pcnl.in', 'OTP for Password Reset');
            $mail->addAddress($tomail);   // Add a recipient
            $mail->isHTML(true);  // Set email format to HTML
            
            //$bodyContent = '<img id="img" style="width: 100px;"  class="nav pull" src=""http://pcnl.in/Library/Images/tpclogo.jpg"/>'; 
            $bodyContent .= '<h7><strong>Dear Customer,</strong></h7>';
            $bodyContent .= '<p>You have requested to change password, please find the OTP below.</p>';
            $bodyContent .= '<p>Your OTP is <strong>'.$otp.'</strong>.</p><br><br><br>';
            $bodyContent .= '<h7><strong>Regards,</strong></h7>';
            $bodyContent .= '<p><strong>PCNL</strong></p>';
            $bodyContent .= '<img src="http://pcnl.in/Library/Images/tpclogo.jpg"/>';
            $mail->Subject = ' PCBS - Change Password Request';
            
            $mail->Body    = $bodyContent;
            if(!$mail->send()) {
              echo 'Message was not sent.';
              echo 'Mailer error: ' . $mail->ErrorInfo;
            } else {
              echo 'Message has been sent.';
            }
          }

           //get all branches of selected stations in user forms
           if(isset($_POST['station_codes'])){
            echo getallbranchesofstations($db,$_POST['station_codes']);
         }
         function getallbranchesofstations($db,$station_codes){
             $brcodes = array();
             foreach($station_codes as $stationcode)
             {
               $branches = $db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
                 while($row = $branches->fetch(PDO::FETCH_OBJ)){
                 $brcodes[$stationcode][]=$row->br_code;
                 }
             }
             return json_encode($brcodes);
         }
         //check session valid 
         if(isset($_POST['checksessionvalid'])){
          session_start();
            echo time() - $_SESSION['LAST_ACTIVITY'];
         }
         if(isset($_POST['action']) && $_POST['action'] =='stock_alert'){
          $cnote_serial = $_POST['cnote_serial'];
          $stockrange_stn  = $_POST['stockrange_stn'];
          $cnote_start = $_POST['cnote_start'];
          
        $stock_alert = $db->query("SELECT *  FROM groups where cate_code='$cnote_serial' AND stncode='$stockrange_stn'")->fetch(PDO::FETCH_OBJ);

        $stock_range_filter = $db->query("SELECT COUNT(cnote_number) as count from cnotenumber where cnote_serial='$cnote_serial' and cnote_station='$stockrange_stn' ")->fetch(PDO::FETCH_OBJ);
        
        $start = $db->query("SELECT TOP 1 cnote_end FROM cnote WHERE cnote_serial='$cnote_serial' AND cnote_station='$stockrange_stn' ORDER BY cnoteid DESC");
        $row = $start->fetch(PDO::FETCH_ASSOC);
        $result = $row['cnote_end'] + 1;


        $stock_range = ($stock_alert->range_ends-$stock_alert->stock_alert);
        $stock_count = $stock_range_filter->count;
        if($stock_alert->grp_type=='gl'){
          if($stock_range<=$result){
            $stock = "1";
          }else{
            $stock = "0";
          }
        }
       
        echo json_encode(array($stock));

      // echo json_encode(array($stock_range,$stock_alert->range_starts,$stock,$result));
       }