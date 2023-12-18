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


      class bulkinvoiceprint extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - bulkinvoiceprint';
              $this->view->render_in('docs/bulkinvoiceprint');
          }
      }


      if(isset($_POST['action']) && $_POST['action']=='fetch_signedinvoice'){
        $invno = $_POST['invno'];
        $station = $_POST['station'];

        $fetch_irndata = $db->query("SELECT * FROM invoice WHERE invno='$invno' AND comp_code='$station'")->fetch(PDO::FETCH_OBJ);
        echo json_encode($fetch_irndata->signedirn);
        exit();
      }