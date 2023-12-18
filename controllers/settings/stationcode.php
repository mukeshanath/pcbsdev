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

      class stationcode extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - stationcode';
              $this->view->render('settings/stationcode');
          }
      }

          $errors = array();



          if(isset($_POST['addstationcode']))
          {
            savestationcode($db);
          }
            
          function savestationcode($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $stncode = $_POST['stncode'];
                $stnname = $_POST['stnname'];
                $state = $_POST['state'];
                $stname = $_POST['stname'];
                $coun_code = $_POST['coun_code'];
                $coun_name = $_POST['coun_name'];
                $username = $_POST['user_name'];

                $date_added = date('Y-m-d H:i:s');

		$sqlcheck = "SELECT * from stncode WHERE stncode='$stncode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $stn_code = $row->stncode;
                    
                }
                
           
              if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($stncode==$stn_code AND  $stncode !='')
                    {
                      header("location: stationcode?success=3"); 
                      } 
                      else { 

                $sql = "INSERT INTO stncode(stncode,stnname,state,stname,coun_code,coun_name,user_name,date_added)
                        VALUES ('$stncode','$stnname','$state','$stname','$coun_code','$coun_name','$username','$date_added')";
                  }
               if($result  = $db->query($sql))
               {
               header("location: stationcodelist?success=1"); 
               }
               
             }
          }
?>
