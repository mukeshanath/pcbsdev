<?php 
/* File name  : cancellation controller
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
// $user_name = $_SESSION['user_name'];

 include 'db.php'; 

      class cancellation extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - cnotecancellation';
              $this->view->render('cnote/cancellation');
          }
      }

if(isset($_POST['bulkcancellation']))
{
    bulkcancel($db);
}
// cnote bulk cancellation for company
function bulkcancel($db)
{
    $cancel_stationbulk = $_POST['bulkcancelstation'];
    $cancel_from    = $_POST['cancel_from'];
    $cancel_to      = $_POST['cancel_to'];
    $cancel_serial  = $_POST['cancel_serial'];
    $user           = $_POST['ss_u'];

    $bulkcancel_cash = "UPDATE cash SET status='Billed-Void' WHERE comp_code='$cancel_stationbulk' AND cno BETWEEN '$cancel_from' AND '$cancel_to' AND cnote_serial='$cancel_serial' AND status NOT LIKE '%-void'";     
    $bulkcancel_credit = "UPDATE credit SET status='Billed-Void' WHERE comp_code='$cancel_stationbulk' AND cno BETWEEN '$cancel_from' AND '$cancel_to' AND cnote_serial='$cancel_serial' AND status NOT LIKE '%-void'";   
    $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$cancel_from TO $cancel_to','Bulk Cancel','Cancelled','CNOTES FROM $cancel_stationbulk$cancel_from TO $cancel_stationbulk$cancel_to has been Cancelled','$user',getdate())");
    $bulkcancel ="UPDATE cnotenumber SET cnote_status =cnote_status+'-Void' WHERE cnote_station='$cancel_stationbulk' AND cnote_number BETWEEN '$cancel_from' AND '$cancel_to' AND cnote_serial='$cancel_serial' AND cnote_status NOT LIKE '%-void'";
    $bulkk_cancel_cash = $db->query($bulkcancel_cash);
    $bulkk_cancel_credit = $db->query($bulkcancel_credit);   
    $cancel = $db->query($bulkcancel);
    header("Location: cancellation?success=1");
}

if(isset($_POST['singlecancellation']))
{
    singlecancel($db);
}

// single cnote cancellation for company
function singlecancel($db)
{
       $user = $_POST['user_name'];
            $cancel_stationsingle = $_POST['singlecancelstation'];
            $single_cnote       =    $_POST['single_cnote'];
            $single_serial =    $_POST['single_serial'];           
            $singlecancel_cash = "UPDATE cash SET status='Billed-Void' WHERE comp_code='$cancel_stationsingle' AND cnote_serial='$single_serial' AND cno='$single_cnote' AND status NOT LIKE '%-void'";     
            $singlecancel_credit = "UPDATE credit SET status='Billed-Void' WHERE comp_code='$cancel_stationsingle'  AND cnote_serial='$single_serial' AND cno='$single_cnote' AND status NOT LIKE '%-void'";   
            $singlecancel_cnm = "UPDATE cnotenumber SET cnote_status =cnote_status+'-Void' WHERE cnote_station='$cancel_stationsingle' AND cnote_number ='$single_cnote' AND cnote_serial='$single_serial' AND cnote_status NOT LIKE '%-void'";
            $singcancel_cash = $db->query($singlecancel_cash);
            $singcancel_credit = $db->query($singlecancel_credit);
            $singcancel_cnm = $db->query($singlecancel_cnm);
            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$single_cnote','Cancel','Cancelled','$cancel_stationsingle$single_cnote has been Cancelled','$user',getdate())");
            header("Location: cancellation?success=1");
            }


            if(isset($_POST['noservice_cnote']))
            {
                noservice_return($db);
            }
            
            
            function noservice_return($db)
            {
                $cancel_stationsingle = $_POST['singlecancelstation'];
                $single_cnote       =    $_POST['noservice_cnote'];
                $single_serial =    $_POST['noservice_serial'];  
                $user = $_POST['user_name'];
                
                $selectcnotenumber = $db->query("SELECT * FROM cnotenumber WHERE cnote_number='$single_cnote' AND cnote_station='$cancel_stationsingle' AND cnote_serial='$single_serial'")->fetch(PDO::FETCH_OBJ); 
                if($selectcnotenumber->cnote_status=='Billed'){
                   
                        $singlecancel_credit = "UPDATE credit SET status='Noservice-return',amt=0.00 WHERE comp_code='$cancel_stationsingle'  AND cnote_serial='$single_serial' AND cno='$single_cnote' AND status NOT LIKE '%Noservice%'";  
                        $singcancel_credit = $db->query($singlecancel_credit); 
            
                        $singlecancel_cash = "UPDATE cash SET status='Noservice-return',amt=0.00,ramt=0.00 WHERE comp_code='$cancel_stationsingle'  AND cnote_serial='$single_serial' AND cno='$single_cnote' AND status NOT LIKE '%Noservice%'";  
                        $singcancel_cash = $db->query($singlecancel_cash); 
                      
                        $singlecancel_cnm = "UPDATE cnotenumber SET cnote_status ='Noservice-return',bilrate=0.00 WHERE cnote_station='$cancel_stationsingle' AND cnote_number ='$single_cnote' AND cnote_serial='$single_serial' AND cnote_status NOT LIKE '%Noservice%'";
                        $singcancel_cnm = $db->query($singlecancel_cnm);

                        $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('$single_cnote','Cancel','Noservice-return','$cancel_stationsingle$single_cnote has been updated as Noservice-return','$user',getdate())");
                      
                        
                        header("Location: cancellation?success=5");   
                }else{
                       header("Location: cancellation?unsuccess=2"); 
                }
            }
 
            if(isset($_POST['action']) && $_POST['action'] == 'cashllish'){
               echo select10rows($db,$_POST['dtbquery'],$_POST['dtbpageno'],$_POST['dtbcolumns']);
               exit();
             }
           
                 function select10rows($db,$dtbquery,$dtbpageno,$dtbcolumn2){
                     $dtbcolumns = explode(",",$dtbcolumn2);
                     $t10query = $db->query($dtbquery." OFFSET ".$dtbpageno." ROWS  FETCH NEXT 10 ROWS ONLY");
                     $datum = array();
                     $index = 0;
                     while($t10row=$t10query->fetch(PDO::FETCH_OBJ)){$index++;
                         $data = array();
                           foreach($dtbcolumns as $dtbcolumn){
                         //console_log($dtbcolumn);
                             $data[$dtbcolumn] = $t10row->$dtbcolumn;
                           }
                       $datum[$index]=$data;
                     }
                     return json_encode($datum);
                     exit();
                 }
           
           
                 if(isset($_POST['action']) && $_POST['action']=='change_datefilter'){
                   $conditions = $_POST['conditions'];
                   $output = '';
                   $output = '<button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>';
                   $noofpages = $db->query("SELECT COUNT(*) as pages FROM cnotenumber WHERE $conditions")->fetch(PDO::FETCH_OBJ);
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
           

