<?php defined('__ROOT__') OR exit('No direct script access allowed');

session_start();

header("location: login?success=4");

session_destroy();

date_default_timezone_set('Asia/Kolkata');
include 'db.php'; 
$session_user       = $_SESSION['user_name'];
$session_endtime      = date('Y-m-d H:i:s');
$update_log = $db->query("UPDATE top (1) logs_tb SET session_end_time='$session_endtime' WHERE user_name='$session_user' AND log_id=(SELECT max(log_id) FROM logs_tb)");

?>