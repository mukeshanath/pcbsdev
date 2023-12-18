<?php
defined('__ROOT__') OR exit('No direct script access allowed');

include 'db.php'; 

 class subnewmodule extends My_controller
 {
     public function index()
     {
         $this->view->title = __SITE_NAME__ . 'subnewmodule';
         $this->view->render('settings/subnewmodule');
     }
 }
 ?>
 