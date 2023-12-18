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
    // require_once ('models/login.php');

   

      class login extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - login';
              $this->view->render_in('users/login');
          }

      }

    if (isset($_POST['adminlogin'])) {
       getlogin($db);
    }

    //  $errors = array();
    function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";
    
        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
    
        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
    
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
    
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }
    
        // check if we have a number
        if ($version==null || $version=="") {$version="?";}
    
        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }
      
    function getlogin($db)
    {
        date_default_timezone_set('Asia/Kolkata');
        // global $db, $user_name, $password ;
        $user_name = $_POST['user_name'];
        $password = $_POST['cpassword'];
        $password = md5($password); 
        $remote_addr  = $_SERVER['REMOTE_ADDR'];
        $log_datetime = date('Y-m-d H:i:s');
                
        // now try it
        $ua=getBrowser();
        $yourbrowser= $ua['name'];
        $user_surename = get_current_user();
        $platform= $ua['platform'];   
        

            if (!empty($_SERVER['HTTP_CLIENT_IP']))   
            {
                $remote_addr = $_SERVER['HTTP_CLIENT_IP'];
            }
            //whether ip is from proxy
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
            {
                $remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            //whether ip is from remote address
            else
            {
                $remote_addr = $_SERVER['REMOTE_ADDR'];
            }

            if ($user_name == "" || $password == "") {
                header("location: login?unsuccess=2");
            }
             else {
    
                $sql = "SELECT * FROM users WHERE user_name='$user_name' AND cpassword='$password'";
                $result  = $db->query($sql);
        
                while ($row = $result->fetch(PDO::FETCH_OBJ))
                {
                    $usern = $row->user_name;
                    $passn = $row->cpassword;
                    $usergroup = $row->group_name;
                    $flag   = $row->flag;
                }
                
            if($result->rowCount()>0)  {
                if ($user_name==$usern AND $password==$passn)
                {
                    if($flag==1){
                    session_start();
                  //  $_SESSION['usrid'] = $count[0]["usrid"];
                    $_SESSION['user_name'] = $user_name;
                    $_SESSION['cpassword'] = $password;
                    $session_datetime      = date('Y-m-d H:i:s');
                    //Generate log
                    $token = getToken(20);
                    $_SESSION['pcb_token'] = $token;
                    $db->query("UPDATE users SET token='$token' WHERE user_name='$user_name' AND cpassword='$password'");
                    
                    $log_tb = $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,session_start_time,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$log_datetime','$session_datetime','$user_surename','$user_name','Logged In Successfully','$yourbrowser')");
                    header("location:Homepage");
                    }
                    else{
                        header("location: login?unsuccess=1");
                    }
                } 
          } 
          else {
            $log_tb = $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,application,system_username,user_name,status) VALUES ('$remote_addr','$platform','$log_datetime','$yourbrowser','$user_surename','$user_name','Logged In Failed due to Invalid Credentials')");
              header("location: login?unsuccess=2");
          }
        }
    
     
    }
      
    // Generate token
    function getToken($length){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited
    
        for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
        }
    
        return $token;
    }
      
?>
