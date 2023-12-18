<?php defined('__ROOT__') OR exit('No direct script access allowed');

class View
{
	public function render_in($viewPath, $layout = NULL)
	{
		if ($layout === NULL) {
			$this->view = $viewPath;
			require('Views/layout.php');
		}
		else if ($layout === FALSE) {
			require('Views/' . $viewPath . '.php');			
		}
		else {
			$this->view = $viewPath;
			require("Views/$layout.php");
		}
	}

	public function render($viewPath, $home = NULL)
	{
		if ($home === NULL) {
			$this->view = $viewPath;
			require('Views/home.php');
		}
		else if ($home === FALSE) {
			require('Views/' . $viewPath . '.php');			
		}
		else {
			$this->view = $viewPath;
			require("Views/$home.php");
		}
	}
}
