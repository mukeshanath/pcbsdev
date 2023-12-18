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
 * @since 
 * 
 * 2020-03-04
 */

/* Include the main tax master (search for installation path). */

defined('__ROOT__') OR exit('No direct script access allowed');


 include 'db.php'; 

      class cnoteallotment extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - cnoteallotment';
              $this->view->render('cnote/cnoteallotment');
          }
      }

       if(isset($_POST['br_code']))
            {
              date_default_timezone_set('Asia/Kolkata');
              
              $stncode           = $_POST['stncode'];
              $stnname           = $_POST['stnname'];
              $br_code           = $_POST['br_code'];
              $br_name           = $_POST['br_name'];  			  
              $username          = $_POST['user_name'];              
              $date_added        = date('Y-m-d H:i:s');                 
              $cnote_serial      = $_POST['cnote_serial'];
              $cnote_count       = $_POST['cnote_count'];
              $cnote_start       = $_POST['salesstart'][0];
              $cnote_end         = $_POST['cnote_end'];
              $cnote_procured    = $_POST['cnote_procured'];
              $cnote_void        = $_POST['cnote_void'];
              $cnote_amount      = $_POST['cnote_amount'];

              //generate allot order
              $allotoid = $db->query("SELECT TOP(1) allotorderid FROM cnoteallot ORDER BY cast(dbo.GetNumericValue(allotorderid) AS decimal) DESC");
              if($allotoid->rowCount()==0){
                  $allotorderid = 'AO'.str_pad('1', 5, "0", STR_PAD_LEFT);
              }
              else{
                  $rowallotoid = $allotoid->fetch(PDO::FETCH_OBJ);
                  $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$rowallotoid->allotorderid);
                  $allotorderid = 'AO'.str_pad(($arr[1]+1), 5, "0", STR_PAD_LEFT);
              }
           
             $sql = "INSERT INTO cnoteallot (stncode,stnname,br_code,br_name,allotorderid,cnote_serial,cnote_count,cnote_start,cnote_end,cnote_status,cnote_procured,cnote_void,date_added,user_name,flag) 
                                     VALUES ('$stncode','$stnname','$br_code','$br_name','$allotorderid','$cnote_serial','$cnote_count','$cnote_start','$cnote_end','Allotted','$cnote_procured','$cnote_void','$date_added','$username',1)";
             $result  = $db->query($sql);

             for($i = 0; $i < count($_POST['salespono']); $i++){
                $allotpono  = stripslashes($_POST['salespono'][$i]);
                $allotstart = stripslashes($_POST['salesstart'][$i]);
                $allotend   = stripslashes($_POST['salesend'][$i]);
                $allotcount = stripslashes($_POST['salescount'][$i]);

                $allotted = "UPDATE cnotenumber SET cnote_status ='Allotted', br_code='$br_code', br_name='$br_name',allot_date='$date_added',allot_po_no='$allotorderid' WHERE cnote_status='Printed' AND cnote_station='$stncode' AND cnote_serial='$cnote_serial' AND cnote_number BETWEEN '$allotstart' AND '$allotend'";
                $allotted  = $db->query($allotted); 

                $insertsalesdetail = $db->query("INSERT INTO cnoteallotdetail(stncode,stnname,br_code,br_name,allotorderid,cnotepono,cnote_serial,cnote_count,cnote_start,cnote_end,cnote_status,date_added,user_name)
                 VALUES ('$stncode','$stnname','$br_code','$br_name','$allotorderid','$allotpono','$cnote_serial','$allotcount','$allotstart','$allotend','Allotted','$date_added','$username')");
            }
            
            header("location: cnoteallotmentlist?success=1"); 
            }

?>