<?php include 'db.php'; 
  //if user is not logged in, they cannot access this page
//  if(empty($_SESSION['username'])) {
  //  header('location: login.php');

  //}
  function loadstationcode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM stncode ORDER BY stnid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stncode.'">'.$row->stncode.'</option>';
         }
         return $output;    
        
    }
?>

<!DOCTYPE html>
<html>
<head>
  <title>PMEIGHT ADMINPANEL</title>
  <link rel="stylesheet" type="text/css" href="style.css">
   <link rel="stylesheet" href="assets/css/admincss.css">
</head>
<body>
  <div class="logoimg">
  <img src="images/eightlogo.png" alt="Logo" style="width: 100px;border-radius: 10px;">
</div>
  <div class="header">
  	<h2>Register</h2>
  </div>
	
  <form method="post" action="<?php echo __ROOT__ ?>encapreg">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Username</label>
  	  <input type="text" name="user_name">
  	</div>

	  <div class="input-group">
  	  <label>Station Code</label>
  	  <select class="form-control" name="stncodes">
                  <option>Select</option>
                  <?php echo loadstationcode($db); ?>
                  </select>   
  	</div>
  	
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="adminreg">Register</button>
  	</div>
  	<p>
  		Already a member? <a href="login.php">Sign in</a>
  	</p>
  </form>
</body>
</html>