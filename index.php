<?php
$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
$script_name = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
define ('__SALT__', 'Fho8G**g&0ds43syK0PKPph&^D64fi7g1k-90`j*G&7IVGD');
define ('__ROOT__', $root.$script_name);
define ('__SITE_NAME__', 'PCBS');

//define ('__ROOT_PATH__', realpath($_SERVER['DOCUMENT_ROOT'].'/'.$script_name));



spl_autoload_register(function ($className) {
    if (file_exists('System/' . $className . '.php')) { 
        require_once 'System/' . $className . '.php'; 
    }
	else if (file_exists('Controllers/' . $className . '.php')) { 
        require_once 'Controllers/' . $className . '.php'; 
    }	
    else if (file_exists('Controllers/webservice/' . $className . '.php')) { 
        require_once 'Controllers/webservice/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/masters/' . $className . '.php')) { 
        require_once 'Controllers/masters/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/settings/' . $className . '.php')) { 
        require_once 'Controllers/settings/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/cnote/' . $className . '.php')) { 
        require_once 'Controllers/cnote/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/users/' . $className . '.php')) { 
        require_once 'Controllers/users/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/rate/' . $className . '.php')) { 
        require_once 'Controllers/rate/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/people/' . $className . '.php')) { 
        require_once 'Controllers/people/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/billing/' . $className . '.php')) { 
        require_once 'Controllers/billing/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/invoice/' . $className . '.php')) { 
        require_once 'Controllers/invoice/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/newmodule/' . $className . '.php')) { 
        require_once 'Controllers/newmodule/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/collection/' . $className . '.php')) { 
        require_once 'Controllers/collection/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/docs/' . $className . '.php')) { 
        require_once 'Controllers/docs/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/common/' . $className . '.php')) { 
        require_once 'Controllers/common/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/reports/' . $className . '.php')) { 
        require_once 'Controllers/reports/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/utilities/' . $className . '.php')) { 
        require_once 'Controllers/utilities/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/phpmailer/' . $className . '.php')) { 
        require_once 'Controllers/phpmailer/' . $className . '.php'; 
    }
    else if (file_exists('Controllers/links/' . $className . '.php')) { 
        require_once 'Controllers/links/' . $className . '.php'; 
    }
	else if (file_exists('Models/' . $className . '.php')) { 
        require_once 'Models/' . $className . '.php'; 
    }
    else if (file_exists('Libraries/' . $className . '.php')) { 
        require_once 'Libraries/' . $className . '.php'; 
    }
    else if (file_exists($className . '.php')) { 
        require_once $className . '.php'; 
    }
});



new Bootstrap();