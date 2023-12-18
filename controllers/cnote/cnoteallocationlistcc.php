<?php 
/*
File name   : cnoteallocationlistcc.php
Begin       : 2020-03-04
Description : Cnote allotment for Collection Center - Controller file

 Author: Suhail

 (c) Copyright:
              codim solutions
============================================================
*/

/**
 * render the tax in to class 
 * get database string to establish connection from sql server
 * Capture the tax data and save ion to database
 * @author Suhail  
 * @since 2022-01-04
 */

/* Include the main tax master (search for installation path). */

defined('__ROOT__') OR exit('No direct script access allowed');

 include 'db.php'; 

      class cnoteallocationlistcc extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - cnoteallocationlistcc';
              $this->view->render('cnote/cnoteallocationlistcc');
          }
      }