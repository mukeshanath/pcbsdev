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

      class cnotereallotment extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - cnotereallotment';
              $this->view->render('cnote/cnotereallotment');
          }
      }

      if(isset($_POST['reallocate'])){
        $stncode           = $_POST['stncode'];               
        $brcode            = $_POST['br_code'];
        $brname            = $_POST['br_name'];
        $cnote_serial      = $_POST['cnote_serial'];
        $cnote_start       = $_POST['cnote_start'];
        $cnote_end         = $_POST['cnote_end'];
        $user_name         = $_POST['user_name'];
        $precious_branch = $_POST['pre_br_code'];
         echo json_encode($precious_branch);
         
        date_default_timezone_set('Asia/Kolkata');
        $date_added 	= date('Y-m-d H:i:s');

      
     
    
     try {
      
      $allotted = "UPDATE cnotenumber SET br_code='$brcode', br_name='$brname' WHERE cnote_status='Allotted' AND cnote_station='$stncode' AND cnote_number BETWEEN '$cnote_start' AND '$cnote_end' AND cnote_serial='$cnote_serial' and cust_code is NULL";
      $sqlallotted  = $db->query($allotted); 

      $db->query("INSERT INTO br_reallotment (stncode,br_code,cnote_start,cnote_end,date_added,realloted_from,cnote_serial) VALUES ('$stncode','$brcode','$cnote_start','$cnote_end','$date_added','$precious_branch','$cnote_serial')");
      

      $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$stncode','Allotement','Branch Re-Allotment','Branch Re-Allotment to $precious_branch to $brcode in $stncode Station','$user_name','$date_added')");

       
      header("location: cnotereallotment?success=2");
     } catch (PDOException $e) {

      header("location: cnotereallotment?error=$err_desc1");
     }
  

         
    }

//fetch cnote from stn..
    if(isset($_POST['action']) && $_POST['action']=='fetchreallot'){
       
        $stncode = $_POST['stncode'];
        $cnote_serial = $_POST['cnote_serial'];
        $cnote_number = $_POST['cnote_number'];


        $fetchreallot = $db->query("SELECT TOP 1  * FROM cnotenumber WHERE cnote_station='$stncode' AND cnote_serial='$cnote_serial' AND cnote_number='$cnote_number'")->fetch(PDO::FETCH_OBJ);
        echo json_encode(array(strtoupper($fetchreallot->br_code)));
        exit();
    }

//validate cnote from start to end!
    if(isset($_POST['action']) && $_POST['action']=='validate_cnote_number'){
       
      $stncode = $_POST['stncode'];
      $cnote_serial = $_POST['cnote_serial'];
      $cnote_number = $_POST['cnote_number'];
      $br_code  = $_POST['br_code'];
      $cnoteend  = $_POST['cnoteend'];

       $s_cnote = $db->query("SELECT  count(*) as cs FROM cnotenumber WHERE cnote_status='Allotted' AND cnote_station='$stncode' AND cnote_number = '$cnote_number' AND cnote_serial='$cnote_serial' and cust_code is NULL")->fetch(PDO::FETCH_OBJ);

       $e_cnote = $db->query("SELECT  count(*) as ce FROM cnotenumber WHERE cnote_status='Allotted' AND cnote_station='$stncode' AND cnote_number = '$cnoteend' AND cnote_serial='$cnote_serial' and cust_code is NULL")->fetch(PDO::FETCH_OBJ);

       if($s_cnote->cs == 1 && $e_cnote->ce == 1){
          $response = 'Success';
       }else{
          $response = 'Invalid';
       }
      echo json_encode(array(
          $response,$s_cnote->cs,$e_cnote->ce
      ));
      exit();
  }


    if(isset($_POST['action']) && $_POST['action']=='change_datefilter'){
        $conditions = $_POST['conditions'];
        $output = '';
        $output = '<button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>';
        $noofpages = $db->query("SELECT COUNT(*) as pages FROM br_reallotment WHERE $conditions")->fetch(PDO::FETCH_OBJ);
        //  echo json_encode(SELECT COUNT(*) as pages FROM cash WHERE $conditons);
       for($i=1;$i<=ceil($noofpages->pages / 10);$i++){
    //pagination control
    if(ceil($noofpages->pages / 10)>5){
      //dot property
      if($i==3){
        $leftdot = '...';
      }
      else{
        $leftdot = '';
      }
      if($i==ceil($noofpages->pages / 10)-1){
        $rightdot = '...';
      }
      else{
        $rightdot = '';
      }
      //end dot property
      //number btn display property
      if($i==1||$i==2||$i==ceil($noofpages->pages / 10)||$i==round(ceil($noofpages->pages / 10)/2)||$i==round(ceil($noofpages->pages / 10)/2)-1||$i==round(ceil($noofpages->pages / 10)/2+1)){
        $style = "style='display:'";
      }else{
        $style = "style='display:none'";
      }
      //end number btn display property
    }
    else{
      $leftdot = '';
      $rightdot = '';
      $style= '';
    }
    //end pagination control
    $output .= "$leftdot<input type='button' $style class='btn btn-primary paginate-btn-nums' value='$i' id='$i'>$rightdot";
  }
  $output .= '<button type="button" class="btn btn-primary paginate-btn-nums" id="next" value="2">Next</button>';

  
    echo json_encode(array($output,$noofpages->pages,"Onchange"));

    exit();
      }