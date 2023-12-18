<?php defined('__ROOT__') OR exit('No direct script access allowed');
include 'db.php'; 
class login {
  // we define 3 attributes
  // they are public so that we can access them using $post->author directly

  public function getlogin()
  {
    if(isset($_REQUEST['user_name']) && isset($_REQUEST['password']))
    {
      if($_REQUEST['user_name'] == 'user_name' && $_REQUEST['password'] == 'password'){
        return 'login';
      }
      else {
        return 'invalid user';
      }
    }
  }
  
}