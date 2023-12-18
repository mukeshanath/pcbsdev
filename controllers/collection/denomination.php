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


      class denomination extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - Denomination';
              $this->view->render('collection/denomination');
          }
      } 
      if(isset($_POST['addcollection']))
      {
        try{
          addcollection($db);
          }
          catch(PDOException $e)
          {
            $date_added	= date('Y-m-d H:i:s');
            $yourbrowser   = getBrowser()['name'];
            $user_surename = get_current_user();
            $platform      = getBrowser()['platform'];   
            $remote_addr   = getBrowser()['remote_addr']; 
            $comp_code     = $_POST['comp_code'];
            $comp_name     = $_POST['comp_name'];
            $br_code       = $_POST['br_code'];
            $br_name       = $_POST['br_name'];           
            $coldate       = $_POST['coldate'];            
            $user_name     = $_POST['user_name'];                  
            $error_info	= $e->errorInfo[1];
            $error_detail	= explode("]",$e->errorInfo[2]);
            $err_desc = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', END($error_detail));    
          $db->query("INSERT INTO logs_tb (machine_ip,platform,log_datetime,system_username,user_name,status,application) VALUES ('$remote_addr','$platform','$date_added','$user_surename','$user_name','Transaction failed at denomination. ERROR_CODE:$error_info-$err_desc.','$yourbrowser')");     
          header("Location: denominationlist?error=1");
          }        
      }

      function addcollection($db)
      {
          date_default_timezone_set('Asia/Kolkata');

          $comp_code                = $_POST['comp_code'];
          $comp_name                = $_POST['comp_name'];
          $br_code                  = $_POST['br_code'];
          $br_name                  = $_POST['br_name'];
         //$coll_code               = $_POST['coll_code'];         
          $colamt                   = $_POST['colamt'];
          $m2000                    = $_POST['m2000'];
          $m500                     = $_POST['m500'];
          $m200                     = $_POST['m200'];
          $m100                     = $_POST['m100'];
          $m50                      = $_POST['m50'];
          $m20                      = $_POST['m20'];
          $m10                      = $_POST['m10'];
          $m5                       = $_POST['m5'];
          $coins                    = $_POST['coins'];
          $c2000                    = $_POST['m2k'];
          $c500                     = $_POST['mo500'];
          $c200                     = $_POST['mo200'];
          $c100                     = $_POST['mo100'];
          $c50                      = $_POST['mo50'];
          $c20                      = $_POST['mo20'];
          $c10                      = $_POST['mo10'];
          $c5                       = $_POST['mo5'];
          $cchq                     = $_POST['chq'];
          $cupi                     = $_POST['upi'];
          $chqamt                   = $_POST['chqamt'];
          $neftamt                   = $_POST['neftamt'];
          $cneft                   = $_POST['cneft'];
          $upiamt                   = $_POST['upiamt'];
          $coldate                  = $_POST['coldate'];            
          $user_name                = $_POST['user_name'];
          $date_added               = date('Y-m-d H:i:s');
          $date_modif               = date('Y-m-d H:i:s');
          $remarkss                 = $_POST['rmks'];
         
          
         
      // $db->query

               
 $insertdenomination= "INSERT INTO denomination (cneft,neftamt,comp_code, br_code,	col_date, collamt,remarks,flag,status,date_added,date_modified,user_name,m2000,m500,m200,m100,m50,m20,m10,m5,c2000,c500,c200,c100,c50,c20,c10,c5,coins,ccheq,cheqamt,cupi,upiamt)
  VALUES                (
                        '$cneft', 
                        '$neftamt',                                                                                                               
                        '$comp_code',                                                        
                        '$br_code', 
                        '$coldate',                                                                                                               
                        '$colamt',  
                        '$remarkss',
                        '1',
                        'collected',    
                        '$date_added',
                        '$date_modif',
                        '$user_name',   
                        '$m2000',                
                        '$m500',               
                        '$m200',                
                        '$m100',                
                        '$m50',                
                        '$m20',              
                        '$m10', 
                        '$m5', 
                        '$c2000',                
                        '$c500',               
                        '$c200',                
                        '$c100',                
                        '$c50',                
                        '$c20',              
                        '$c10', 
                        '$c5', 
                        '$coins',
                        '$cchq',  
                        '$chqamt',
                        '$cupi',                                                        
                        '$upiamt')";    
             $db->query($insertdenomination);
               // $rcvdamt = ($invamt-$balamt)+($colamt+$tds+$reduction);
             //  $db->query("UPDATE invoice SET rcvdamt=rcvdamt+$netcoll,lastcoldate='$date_added',status='$invstatus' WHERE invno='$invno' AND comp_code='$comp_code'");
                
                // $insertcollectionmaster = "INSERT INTO collection_master (col_mas_rcptno,cust_code,rcptno,coldate,totamt,running_balance,chdt,invno,chqddno,chqbank,depbank,postamt,comp_code,status,user_name,date_added)
                // VALUES ('$col_mas_rcptno','$cust_code','$rcptno','$coldate','$totamt','$running_bal','$chdt','$invno','$chqddno','$chqbank','$depbank','$colamt','$comp_code','$collstatus','$user_name','$date_added')";
                // $db->query($insertcollectionmaster);
                 header("Location:denominationlist?success=1");
          }

        //   else
        //   {
        //     $emptyfields = '';
        //     if(empty($br_code)){ $emptyfields .='Branch code, '; }
        //     if(empty($cust_code)){ $emptyfields .='Customer code, '; }
        //     header("Location:collection?unsuccess=$emptyfields");
        //   }
    //   }     	   

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
        'userAgent'   => $u_agent,
        'name'        => $bname,
        'version'     => $version,
        'platform'    => $platform,
        'pattern'     => $pattern,
        'remote_addr' => $remote_addr
     );
 }
 //end logs
