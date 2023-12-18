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

      class receiveform extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - receiveform';
              $this->view->render('cnote/receiveform');
          }
      }

       if(isset($_POST['cnotepono']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_added       = date('Y-m-d H:i:s');
                $cnotepono              = $_POST['cnotepono'];
                $cnotereceiveid         = $_POST['cnotereceiveid'];
                $cnote_serial           = $_POST['cnote_serial'];
                $cnote_station          = $_POST['cnote_station'];
                $cnote_count            = $_POST['cnote_count'];
                $cnote_start            = $_POST['cnote_start'];
                $cnote_end              = $_POST['cnote_end'];
                $username               = $_POST['user_name'];
                $rece_count             = $_POST['received_count'];
                $received_count         = $_POST['received_count'] + $_POST['prev_received_count'];
                $pending_count          = $_POST['pending_count'] - $_POST['received_count'];
                $receive_start          = $_POST['cnote_start'] + $_POST['received_count'];

                $checktype = $db->query("SELECT grp_type FROM groups WHERE cate_code='$cnote_serial'");
                $grp_type = $checktype->fetch(PDO::FETCH_ASSOC);
                $result_type = $grp_type['grp_type'];

                if($result_type == 'gl')
                {
                $received = "UPDATE cnotenumber SET cnote_status ='Printed' WHERE cnote_status='Procured' AND cnote_number BETWEEN '$cnote_start' AND '$cnote_end'";
                $receive  = $db->query($received);


                $received2 = "UPDATE cnotereceive SET received_count ='$received_count',pending_count='$pending_count',cnote_start='$receive_start',cnote_status='Printed' WHERE cnotereceiveid ='$cnotereceiveid' ";
                $receive2  = $db->query($received2);
                
                $insert = "INSERT INTO cnotereceivedetail(cnotepono,cnote_station,cnote_serial,cnote_count,cnote_start,cnote_end,received_count,pending_count,cnote_status,user_name,date_added)
                VALUES('$cnotepono','$cnote_station','$cnote_serial','$cnote_count','$cnote_start','$cnote_end','$rece_count','$pending_count','Printed','$user_name','$date_added')";
                $rece_insert  = $db->query($insert);
                }
                else if($result_type == 'sp')
                {
                    $printpod_start = $_POST['printpod_start'];
                    $printpod_end   = $_POST['printpod_end'];
                    $rece_count     = $_POST['received_count'];

                    $received2 = "UPDATE cnotereceive SET received_count ='$received_count',pending_count='$pending_count',cnote_start='$printpod_start',cnote_end='$printpod_end',actuel_count='$printpod_start',cnote_status='Printed' WHERE cnotereceiveid ='$cnotereceiveid' ";
                    $receive2  = $db->query($received2);

                    $insert = "INSERT INTO cnotereceivedetail(cnotepono,cnote_station,cnote_serial,cnote_count,cnote_start,cnote_end,received_count,pending_count,cnote_status,user_name,date_added)
                    VALUES('$cnotepono','$cnote_station','$cnote_serial','$cnote_count','$printpod_start','$printpod_end','$rece_count','$pending_count','Printed','$user_name','$date_added')";
                    $rece_insert  = $db->query($insert);

                    //CREATING CNOTES
                    $db->query("EXEC [CreateCnotesForCNM_Special]
                        @comp_codec='$cnote_station',
                        @cnote_serialc='$cnote_serial',
                        @cnoteponoc='$cnotepono',
                        @usernamec='$username',
                        @cnote_startc='$printpod_start',
                        @cnote_endc='$printpod_end'");
                    // for($x=$printpod_start; $x <= $printpod_end; $x++)
                    // {
                    // //$received = "UPDATE TOP(1) cnotenumber SET cnote_status ='Printed',  cnote_number='$x'  WHERE cnote_serial='$cnote_serial' AND cnote_number='0' AND cnotepono='$cnotepono'";
                    // $receive  = $db->query("INSERT INTO cnotenumber(cnote_station,cnotepono,cnote_serial,cnote_number,cnote_status,user_name,date_added) VALUES('$cnote_station','$cnotepono','$cnote_serial','$x','Printed','$username','$date_added')");
                    // }     
                    
                }

                //header("location: receivelist?success=1");

            }
            ?>