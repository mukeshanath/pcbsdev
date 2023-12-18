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

      class returncnote extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - returncnote';
              $this->view->render('cnote/returncnote');
          }
      }

    if(isset($_POST['returncnote'])){
        $br_code = $_POST['br_code'];
        $comp_code = $_POST['stncode'];
        $cnote_number = $_POST['cnote_number'];
        $cnote_serial = $_POST['cnote_serial'];
    
        $rowcountcash = $db->query("SELECT * FROM cash WHERE cno='$cnote_number' AND comp_code='$comp_code' AND cnote_serial='$cnote_serial' AND br_code='$br_code' AND status='Billed'");
         
        if($rowcountcash->rowCount()==0){
            header("Location:returncnote?unsuccess=2");
        }else{
            $db->query("UPDATE cash SET status='Return' WHERE cno='$cnote_number' AND comp_code='$comp_code' AND cnote_serial='$cnote_serial' AND br_code='$br_code'");
            $db->query("UPDATE cnotenumber SET cnote_status='Return' WHERE cnote_number='$cnote_number' AND cnote_station='$comp_code' AND cnote_serial='$cnote_serial' AND br_code='$br_code'");
            header("Location:returncnote?success=1");
 
        }

    }