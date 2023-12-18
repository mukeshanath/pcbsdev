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


      class creditnoteedit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - creditnote';
              $this->view->render('collection/creditnoteedit');
          }
      }

      if(isset($_POST['updatecreditnote'])){
          updatecreditnote($db);
      }
  
    function updatecreditnote($db){
  
        date_default_timezone_set('Asia/Kolkata');
  
        $comp_code          = $_POST['comp_code'];
        $credit_note_no     = $_POST['credit_note_no'];
        $totamt             = $_POST['totamt'];
      
        //update credit_note
        $db->query("UPDATE credit_note SET net_amt=$totamt WHERE crn_no='$credit_note_no' AND comp_code='$comp_code'");
  
        header('Location:creditnotelist?success=1');
    }