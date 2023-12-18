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


      class template extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - template';
              //$this->view->render('reports/bookingreport');
          }
      }
header("Content-Type: application/csv");    
header("Pragma: no-cache"); 
header("Expires: 0");



if(isset($_POST['cashtemplate'])){
    
    header('Content-type: application/csv');
     header("Content-Disposition: attachment; filename=cash_template.csv");  
     echo "CompCode,Origin,Pod_origin,Tdate,Branch Code,Booking Counter,CC code,C No,Destn,zone,Wt,Mode,RAmt,Shipper Mobile,Consignee Mobile";
}

if(isset($_POST['credittemplate'])){
     header("Content-Disposition: attachment; filename=credit_template.csv");  
     echo "Origin,POD Origin,Tdate,Branch Code,Cust code,C No,Destn,Wt,Mode,Consignee Mobile";
} 