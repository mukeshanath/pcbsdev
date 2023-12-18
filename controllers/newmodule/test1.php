<?php

   


      defined('__ROOT__') OR exit('No direct script access allowed');

      include 'db.php'; 
 
       class test1 extends My_controller
       {
           public function index()
           {
               $this->view->title = __SITE_NAME__ . ' -test1';
               $this->view->render('newmodule/test1');
           }
       }
 
     	   

      ?>
