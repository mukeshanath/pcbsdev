<?php defined('__ROOT__') OR exit('No direct script access allowed');

class Bootstrap
{
	public function __construct() 
	{
		$flag = FALSE;
		// 1. router
		if (isset ($_GET['path'])) {
			$tokens = explode('/', rtrim($_GET['path'], '/'));
			
			// 2. Dispatcher
			$controllerName = ucfirst(array_shift($tokens));
			if (file_exists('Controllers/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					} 
				}
				else
				{ 
					// default action
					$controller->index();
				}				
			} 
			//api controller starts
			elseif (file_exists('Controllers/webservice/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// api controller ends
			
            //masters controller starts
            elseif (file_exists('Controllers/masters/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} //masters controller ends 
            //settings controller ends 
            elseif (file_exists('Controllers/settings/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} //settings controller ends

				//cnote controller starts
			 elseif (file_exists('Controllers/cnote/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// cnote controller ends

			//user controller starts
			 elseif (file_exists('Controllers/users/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// user controller ends

			//rate controller starts
			elseif (file_exists('Controllers/rate/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// rate controller ends

			//people controller starts
			elseif (file_exists('Controllers/people/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// people controller ends
			//people controller starts
			elseif (file_exists('Controllers/phpmailer/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// people controller ends
			//people controller starts
			elseif (file_exists('Controllers/links/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// people controller ends

			//billing controller starts
			elseif (file_exists('Controllers/billing/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// billing controller ends

			//invoice controller starts
			elseif (file_exists('Controllers/invoice/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// invoice controller ends

			//collection controller starts
			elseif (file_exists('Controllers/collection/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// collection controller ends
			//docs controller starts
			elseif (file_exists('Controllers/docs/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// docs controller ends
			//docs controller starts
			elseif (file_exists('Controllers/reports/'.$controllerName.'.php')) {
				var_dump($controllerName);
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// docs controller ends
			//docs controller starts
			elseif (file_exists('Controllers/utilities/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// docs controller ends
			//common controller starts
			elseif (file_exists('Controllers/newmodule/'.$controllerName.'.php')) {
				
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} elseif (file_exists('Controllers/common/'.$controllerName.'.php')) {
				$controller = new $controllerName();
				if (!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists ( $controller , $actionName )) {
						$controller->{$actionName}(@$tokens);	
					}
					else {
						$flag = TRUE;
					}
				}
				else
				{
					// default action
					$controller->index();
				}				
			} 
			// common controller ends

			else {
				$flag = TRUE;
			}
		} 

		else {
			$controllerName = 'login';
			$controller = new $controllerName();
			$controller->index();
		}


		
		//Error404 page
		if ( $flag ) {
			$controllerName = 'Error404';
			$controller = new $controllerName();
			$controller->index();
		}


	}
}
