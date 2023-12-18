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


      class Raterevision extends My_controller
      {
          public function index()
          {
              $this->view->title = 'Rate Deviation';
              $this->view->render('billing/raterevision');
          }
      }




date_default_timezone_set('Asia/Kolkata');
$date_modified       	 = date('Y-m-d H:i:s');  

            $yourbrowser   = getBrowser()['name'];
						$user_surename = get_current_user();
						$platform      = getBrowser()['platform'];   
						$remote_addr   = getBrowser()['remote_addr']; 


if(isset($_POST['br_code'])){
    $comp_code  = $_POST['comp_code'];
    $br_code    = $_POST['br_code'];
    $cust_code  = $_POST['cust_code'];
    $bdate_from = $_POST['bdate_from'];
    $bdate_to   = $_POST['bdate_to'];
    $user_name  = $_POST['user_name'];
   
    $db->query("EXEC [CreditRateDeviationUpdate] @filer_comp_code='$comp_code',@user_name='$user_name',@filer_br_code='$br_code',@filter_cust_code='$cust_code',@filter_tdate_from='$bdate_from',@filter_tdate_to='$bdate_to'");
}
//logs function
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
     'pattern'    => $pattern,
     'remote_addr' => $remote_addr
    );
}
//end logs