<?php defined('__ROOT__') OR exit('No direct script access allowed');

class home extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - home';
		$this->view->render('home');
	}
}
