<?php defined('__ROOT__') OR exit('No direct script access allowed');

      class ddd extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - ddd';
              $this->view->render('masters/ddd');
          }
      }
