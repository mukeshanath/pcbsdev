<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';
session_start();  
if(!isset($_SESSION['user_name']))  
{  
   header("location: login");  
} 

$user = $_SESSION['user_name'];

//validatesession and logout
function validatetoken($db,$user){
  $token = $db->query("SELECT token FROM users WHERE user_name='$user'")->fetch(PDO::FETCH_OBJ);
  if($token->token===$_SESSION['pcb_token']){
    return true;
  }
  else{
    return false;
  }
}

if(!validatetoken($db,$user)){
  header("location: login?invalidtoken");  
}
//validatesession and logout
//session idle 
// if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
//   session_unset();     
//   session_destroy();  
//   header("location: login?invalidtoken");  
// }
$_SESSION['LAST_ACTIVITY'] = time();
//session idle 

function loadstation($db,$user)
{
    $output='';
    $query = $db->query("SELECT * FROM users WHERE user_name='$user'");
    $row = $query->fetch(PDO::FETCH_OBJ);
    $stncodes = explode(',',$row->stncodes);
    sort($stncodes);
     for ($i=0;$i<count($stncodes);$i++){  
       if($stncodes[$i]==$row->last_compcode){

       } else{
        //menu list in home page using this code    
        $output .='<option  value="'.$stncodes[$i].'">'.$stncodes[$i].'</option>';
       }
     }
     return $output;    
    
}
function loadmegastation($db,$user)
{
    $output='';
    $query = $db->query("SELECT * FROM users WHERE user_name='$user'");
    $row = $query->fetch(PDO::FETCH_OBJ);
    $stncodes = explode(',',$row->stncodes);
    sort($stncodes);
    $output = '<div class="column">';

    $divcount = 0;
     for ($i=0;$i<count($stncodes);$i++){  
       $divcount++;
       if($divcount>10){  $divcount = 1;
         $output .= '</div><div class="column">';
       }
        $output .= "<a id='$stncodes[$i]' onclick='changestation(this.id)'>".$stncodes[$i]."</a>";
        
       if($stncodes[$i]==$row->last_compcode){

       } else{
       }
     }
     $output .= '</div>';
     return $output;    
    
}
function loadlaststation($db,$user)
{
    $query = $db->query("SELECT * FROM users WHERE user_name='$user'");
    $row = $query->fetch(PDO::FETCH_OBJ);

    $stnname = $db->query("SELECT stnname FROM stncode WHERE stncode='$row->last_compcode'")->fetch(PDO::FETCH_OBJ);

    return array($row->last_compcode,$stnname->stnname);    
    
}
function loadusergroupname($db,$user)
{
    $output='';
    $query = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
   
     while ($row = $query->fetch(PDO::FETCH_OBJ)){
        
    //menu list in home page using this code    
    $output .=$row->group_name;
     }
     return $output;    
}

//$branchcodes = '';

function loadbranches($db,$user)
{
   
    $output='';
    $query = $db->query("SELECT * FROM users WHERE user_name='$user'");
    $row = $query->fetch(PDO::FETCH_OBJ);
    $stationbrs = json_decode($row->br_codes,true)[$row->last_compcode];
    foreach($stationbrs as $brcode){
      $select = ($row->br_code==$brcode)?' Selected ':'';
      $output .= "<option ".$select." value='$brcode'>$brcode</option>";
    }
    return $output;
}

function module($db,$user,$module_name){
  $group = $db->query("SELECT group_name FROM users WHERE user_name='$user'")->fetch(PDO::FETCH_OBJ);
  $check = $db->query("SELECT module FROM users_group WHERE group_name='$group->group_name' AND module = '$module_name'");
  if($check->rowCount()=='0'){
    return '0';
  }else{
    return '1';
  }
}
$getbranchcodes = $db->query("SELECT br_code FROM users WHERE user_name='$user'")->fetch(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo isset($this->title)? $this->title : __SITE_NAME__ ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <meta http-equiv="refresh" content="1200;url=logout" /> -->
    <script type="text/javascript" src="vendor/jquery/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="vendor/bootstrap/js/autocorrection.js"></script>
    
    <link href="https://fonts.googleapis.com/css?family=Fugaz+One|Lobster|Merienda|Righteous|Black+Ops+One|Gilda+Display" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo __ROOT__ . '/vendor/bootstrap/css/bootstrap.min.css' ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo __ROOT__ . '/vendor/bootstrap/css/jquery-ui.min.css' ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo __ROOT__ . '/vendor/bootstrap/css/ace.min.css' ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo __ROOT__ . '/vendor/bootstrap/css/ace-skins.min.css' ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo __ROOT__ . '/vendor/font-awesome/css/font-awesome.min.css' ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo __ROOT__ . '/vendor/bootstrap/css/style.css' ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo __ROOT__ . '/views/stylesheet/stylesheet.css'?>" />
       
    <script src="<?php echo __ROOT__ .'vendor/jquery/exportexcel.js' ?>"></script>

    
    <script type="text/javascript" src="views/javascript/system.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- sweet alert -->
    <script src="vendor/jquery/sweetalert.min.js"></script>
    
  <!-- DataTable script file link -->
  <!-- mobile view styles -->
  <style> 
  
  @media screen and (max-width: 500px) {
    /* body {
      background-color: lightgreen;
    } */
    #img{
      height:25px; 
      margin-left:10px;
      width: 60px;
      margin-top: 5px;
    }
  
  }
  @media screen and (min-width: 500px) {
    /* body {
      background-color: lightgreen;
    } */
    #img{
      /*cdsslogo*/

      /* height: 50px;
      margin-top: -5px;
      margin-right: 80px;
      margin-left: -5px;
      width: 11%;  */

      /*cdsslogo*/
      
      /*pcbslogo*/ 

      height:27px;
      margin-top: 5px;  
      margin-right:80px; 
      margin-left:14px;
      width: 6%;

      /*pcbslogo*/ 
    }
    
  }


  /* style for stationcode mega dropdown */
  .meganavbar {
  overflow: hidden;
  font-family: Arial, Helvetica, sans-serif;
}

.meganavbar a {
  float: left;
  font-size: 16px;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

.megadropdown {
  float: left;
  overflow: hidden;
}
html {
    height:100%;
    width:100%;
    margin:0%;
    padding:0%;
    overflow-x: hidden;
}

body {
    overflow-x: initial !important;
}

.megadropdown .dropbtn {
  font-size: 16px;  
  border: none;
  height: 38px;
  float: right;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font: inherit;
  margin: 0;

}

.meganavbar a:hover, .megadropdown:hover .dropbtn {
  background-color:#333;
}

.dropdown-content {
  display: none;
  position: inherit;
  background-color: #f9f9f9;
  width: 100%;
  left: 0;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content .megaheader {
  background: #2160A0;
  padding: 16px;
  color: white;
}

/* .megadropdown:hover .dropdown-content {
  display: block;
} */

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 100px;
  padding: 10px;
  background-color: #ccc;
}

.column a {
  float: none;
  color: black;
  padding: 7px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.column a:hover {
  background-color: #ddd;
}
.megarow{
  background: #ccc;

}
/* Clear floats after the columns */
.megarow:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
    height: auto;
  }
}
.mega-dropdownbutton {
    height: 38px;
    width: 100%;
}
  /* style for stationcode mega dropdown */
  </style>

  <!-- mobile view styles -->
  </head>
  <body>
  
  <header id="header" >
  <img id="img" class="nav pull" src="views/image/PCBS-Logo.png"/>
  <!-- <ul id="bar" class="nav pull-left" style="margin-left:0px;"><li><a id="collapselink" onclick="navactive()"><i class="fa fa-bars" id="arrow"></i></a></li></ul> -->
      <ul class="nav pull-right">
      <li><form method="POST">

      <!-- mega dropdown stncodes -->
      <div class="meganavbar">
      <div class="megadropdown">
        <div class="mega-dropdownbutton">
    <button type="button" class="dropbtn"><?php echo loadlaststation($db,$user)[0]; ?>
      <i class="fa fa-caret-down"></i>
    </button></div>
    <div class="dropdown-content">
      <div class="megaheader">
        <h4><?=loadlaststation($db,$user)[0]." - ".loadlaststation($db,$user)[1];?></h4>
      </div>   
      <div class="megarow">
            <?=loadmegastation($db,$user)?>
      </div>
    </div>
  </div> 
  </div> 
<!-- brcode dropdown -->
  </li><li> 
  <select style="background:#686868;color:#fff;border:none;margin-top: 10px;height:100%;display:<?=(!empty($getbranchcodes->br_code)?'':'none')?>" name="br_codes" id="br_codes">
  <?php echo loadbranches($db,$user); ?>
  </select>
<!-- brcode dropdown -->

      <!-- mega dropdown stncodes -->

      <input class="form-control border-form" name="usr_head" type="text" id="usr_head" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
      <!-- <input class="form-control border-form" name="stn_head" type="text" id="stn_head" style="display:;">    -->
          <!-- <select style="background:#686868;color:#fff;border:none" name="stncodes_head" id="stncodes_head">
          <option id="stncodes_val"></option>
          <?php echo loadstation($db,$user); ?>
          </select> -->
      </li>
      <li><input type="text" style="display:none;" id="stncode1" value="<?php echo loadusergroupname($db,$user); ?>"></li></form>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" name="user_name"><?php echo $_SESSION['user_name']; ?></a>
          <ul class="dropdown-menu dropdown-menu-right" style = "display: inline-table;">
          <li><a href="<?php echo __ROOT__ . 'changepassword'; ?>"><i class="fa fa-key"></i> Change Password</a></li> 
            <li><a href="<?php echo __ROOT__ . 'logout'; ?>"><i class="fa fa-sign-out"></i> Logout</a></li> 
          </ul>
        </li>
          </ul>
      </ul>
    </header>
    <nav id="column-left" role="navigation" class="active">
      <ul id="menu" class="list-unstyled components text-justify ">
        <li><a href="<?php echo __ROOT__ . 'dashboard'; ?>" ><i class="fa fa-dashboard fa-2x"></i> <span>Dashboard</span></a></li>
        <?php if(module($db,$user,'branchlist')=='1' ||module($db,$user,'bookingcounterlist')=='1' ||module($db,$user,'companylist')=='1' ||module($db,$user,'customerlist')=='1' ||module($db,$user,'destinationlist')=='1' ||module($db,$user,'grouplist')=='1' ||module($db,$user,'peoplelist')=='1' ||module($db,$user,'vendorslist')=='1' ||module($db,$user,'zonelist')=='1' ||module($db,$user,'itemlist')=='1'){ ?>
        <li><a href="#masters" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa fa-anchor"></i> <span>Masters</span></a>
          <ul class="collapse list" id="masters">
                    <?php if(module($db,$user,'branchlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'branchlist'; ?>"><span>Branch</span></a></li><?php } ?>
                    <?php if(module($db,$user,'bookingcounterlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'bookingcounterlist'; ?>"><span>Booking Counter</span></a></li><?php } ?>
                    <?php if(module($db,$user,'collectioncenterlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'collectioncenterlist'; ?>"><span>Collection Center</span></a></li><?php } ?>
                    <?php if(module($db,$user,'companylist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'companylist'; ?>">Company</a></li><?php } ?>
                    <?php if(module($db,$user,'customerlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'customerlist'; ?>"><span>Customer</span></a></li>      <?php } ?>             
                    <?php if(module($db,$user,'destinationlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'destinationlist'; ?>"><span>Dest.Zone</span></a></li><?php } ?>
                    <?php if(module($db,$user,'grouplist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'grouplist'; ?>"><span>Group</span></a></li>  <?php } ?>
                    <?php if(module($db,$user,'peoplelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'peoplelist'; ?>"><span>People</span></a></li>      <?php } ?>              
                    <?php if(module($db,$user,'vendorslist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'vendorslist'; ?>"><span>Vendor</span></a></li><?php } ?>
                    <?php if(module($db,$user,'zonelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'zonelist'; ?>"><span>Zone</span></a></li><?php } ?>
                    <?php if(module($db,$user,'itemlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'itemlist'; ?>"><span>Item</span></a></li> <?php } ?>
                    </ul>  
            </li><?php }?>
        <?php if(module($db,$user,'procurementlist')=='1'||module($db,$user,'cnotesalesrequestlist')=='1'||module($db,$user,'extcnoteallotmentrequestlist')=='1' ||module($db,$user,'cnoteallocationlistcc')=='1' ||module($db,$user,'extcnotesalesrequestlist')=='1' || module($db,$user,'receivelist')=='1' || module($db,$user,'cnotesaleslist')=='1' ||module($db,$user,'cnoteallotmentlist')=='1' ||module($db,$user,'cancellation')=='1' ||module($db,$user,'inventory')=='1'){ ?>
        <li><a href="#cnote" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa fa-ticket"></i> <span>C-Note</span></a>
          <ul class="collapse list-unstyled" id="cnote">
                    <?php if(module($db,$user,'procurementlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'procurementlist'; ?>"><span>Procurement</span></a></li><?php } ?>
                    <?php if(module($db,$user,'receivelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'receivelist'; ?>"><span>Receive</span></a></li><?php } ?>
                    <?php if(module($db,$user,'cnotesaleslist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cnotesaleslist'; ?>"><span>Sales</span></a></li>      <?php } ?>            
                    <?php if(module($db,$user,'cnotesalesrequestlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cnotesalesrequestlist'; ?>"><span>Sales Request</span></a></li>      <?php } ?>            
                    <?php if(module($db,$user,'extcnotesalesrequestlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'extcnotesalesrequestlist'; ?>"><span>Sales Request</span></a></li>      <?php } ?>            
                    <?php if(module($db,$user,'cnoteallotmentlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cnoteallotmentlist'; ?>"><span>BR Allotment</span></a></li>  <?php } ?>                 
                    <?php if(module($db,$user,'cnoteallocationlistcc')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cnoteallocationlistcc'; ?>"><span>CC Allotment</span></a></li>  <?php } ?>                 
                    <?php if(module($db,$user,'cnotereallotment')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cnotereallotment'; ?>"><span>BR ReAllotment</span></a></li>  <?php } ?>                 
                    <?php if(module($db,$user,'cnoteccreallotment')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cnoteccreallotment'; ?>"><span>CC ReAllotment</span></a></li>  <?php } ?>                 
                    <!-- <?php if(module($db,$user,'cnoteallotmentrequestlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cnoteallotmentrequestlist'; ?>"><span>Allotment Request</span></a></li>  <?php } ?>                  -->
                    <?php if(module($db,$user,'extcnoteallotmentrequestlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'extcnoteallotmentrequestlist'; ?>"><span>Allotment Request</span></a></li>  <?php } ?>                 
                    <?php if(module($db,$user,'cancellation')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cancellation'; ?>"><span>Cancel</span></a></li><?php } ?>
                    <?php if(module($db,$user,'returncnote')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'returncnote'; ?>"><span>Return</span></a></li><?php } ?>
                    <?php if(module($db,$user,'inventory')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'inventory'; ?>"><span>Inventory</span></a></li><?php } ?>
			 </ul>
			</li><?php }?>
      <?php if(module($db,$user,'customerratelist')=='1'){ ?>
        <li><a href="#ratemaster" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa fa-bullseye"></i> <span>Rate Master</span></a>
          <ul class="collapse list-unstyled" id="ratemaster">
                            
          <?php if(module($db,$user,'customerratelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'customerratelist'; ?>"><span>Customer Rate<span></a></li>   <?php } ?>
          </ul>
        </li><?php }?>
        <?php if(module($db,$user,'bainboundlist')=='1' || module($db,$user,'cashlist')=='1' || module($db,$user,'creditlist')=='1' || module($db,$user,'cnotetracker')=='1' || module($db,$user,'extcnotetracker')=='1'){ ?>
        <li><a href="#billing" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa  fa-credit-card"></i> <span>Billing</span></a>
          <ul class="collapse list-unstyled" id="billing">
                    <?php if(module($db,$user,'bainboundbatchlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'bainboundbatchlist'; ?>"><span>BA Inbound Entry</span></a></li><?php } ?>
                    <!-- <?php if(module($db,$user,'bainboundlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'bainboundlist'; ?>"><span>BA Inbound Entry</span></a></li><?php } ?> -->
                    <?php if(module($db,$user,'cashlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cashlist'; ?>"><span>Cash Entry</span></a></li><?php } ?>
                    <?php if(module($db,$user,'creditlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'creditlist'; ?>"><span>Credit Entry</span></a></li><?php } ?>
                    <?php if(module($db,$user,'cnotetracker')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cnotetracker'; ?>"><span>Cnote Tracking</span></a></li><?php } ?>
                    <?php if(module($db,$user,'extcnotetracker')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'extcnotetracker'; ?>"><span>Cnote Tracking</span></a></li><?php } ?>
                    <?php if(module($db,$user,'creditlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'raterevisionlist'; ?>"><span>Rate Revision</span></a></li><?php } ?>

                    <?php if(module($db,$user,'duplicatechellan')=='1'){ 
                      ?>
                    <li><a href="<?php echo __ROOT__ . 'duplicatechellan'; 
                      ?>"><span>Duplicate Challan</span></a></li><?php } ?>

                    <!-- <?php if(module($db,$user,'duplicatechellan')=='1'){ 
                      ?>
                    <li><a href="<?php echo __ROOT__ . 'duplicatechellan1'; 
                      ?>"><span>Duplicate Challan1</span></a></li><?php } ?> -->
                      
          </ul>
        </li><?php }?>
        <?php if(module($db,$user,'invoicelist')=='1' || module($db,$user,'extinvoicelist')=='1' || module($db,$user,'denominationlist')=='1' || module($db,$user,'collectionlist')=='1' || module($db,$user,'creditnotelist')=='1'){ ?>
        <li><a href="#accounts" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa  fa-rupee (alias)"></i> <span>Accounts</span></a>
          <ul class="collapse list-unstyled" id="accounts">
                    <?php if(module($db,$user,'extinvoicelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'extinvoicelist'; ?>"><span>Invoice</span></a></li><?php } ?>
                    <?php if(module($db,$user,'invoicelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'invoicelist'; ?>"><span>Invoice</span></a></li><?php } ?>
                    <?php if(module($db,$user,'accountspay')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'accountspayinvoicelist'; ?>"><span>Accounts Pay</span></a></li><?php } ?>
                    <?php if(module($db,$user,'manualinvoicelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'manualinvoicelist'; ?>"><span>Manual Invoice</span></a></li><?php } ?>
                    <?php if(module($db,$user,'collectionlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'collectionlist'; ?>"><span>Collection</span></a></li><?php } ?>
                    <?php if(module($db,$user,'denominationlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'denominationlist'; ?>"><span>Denomination</span></a></li><?php } ?>
                    <?php if(module($db,$user,'creditnotelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'creditnotelist'; ?>"><span>Credit Note</span></a></li><?php } ?>
                    <?php if(module($db,$user,'creditnotelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'outstandinginvoicelist'; ?>"><span>Outstanding</span></a></li><?php } ?>
                  <!-- <li><a href="<?php echo __ROOT__ . 'tschargelist'; ?>"><span>TS Charge</span></a></li>-->
                   
                    
          </ul>
        </li><?php }?>
        <?php if(module($db,$user,'bookingreport')=='1' || module($db,$user,'totaloutstandingreport')=='1' || module($db,$user,'extgstreport')=='1' || module($db,$user,'customerreport')=='1' || module($db,$user,'extcustomerreport')=='1' || module($db,$user,'collectionreport')=='1' || module($db,$user,'invoicereport')=='1' ||module($db,$user,'missingreport')=='1' ||module($db,$user,'salesreport')=='1' ||module($db,$user,'newbusinessreport')=='1' ||module($db,$user,'deviationreport')=='1' ||module($db,$user,'tonnagereport')=='1'){ ?>
        <li><a href="#reports" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa fa-pie-chart"></i> <span>Reports</span></a>
          <ul class="collapse list-unstyled" id="reports">

                    <?php if(module($db,$user,'bookingreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'bookingreport'; ?>"><span>Booking</span></a></li> <?php } ?> 

                    <?php if(module($db,$user,'bookingreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'demoreport'; ?>"><span>Demo</span></a></li> <?php } ?> 

                    <?php if(module($db,$user,'exbainboundreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'exbookingreport'; ?>"><span>Booking</span></a></li> <?php } ?>    
                    <?php if(module($db,$user,'customerreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'customerreport'; ?>"><span>Customer</span></a></li> <?php } ?> 
                    <?php if(module($db,$user,'extcustomerreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'extcustomerreport'; ?>"><span>Customer</span></a></li> <?php } ?> 
                    <?php if(module($db,$user,'collectionreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'collectionreport'; ?>"><span>Collection</span></a></li>  <?php } ?>    
                    <?php if(module($db,$user,'invoicereport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'invoicereport'; ?>"><span>Invoice</span></a></li> <?php } ?>
                    <?php if(module($db,$user,'totaloutstandingreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'totaloutstandingreport'; ?>"><span>Total Outstanding Invoices</span></a></li> <?php } ?>
                    <?php if(module($db,$user,'missingreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'missingreport'; ?>"><span>Missing</span></a></li>  <?php } ?>   
                    <?php if(module($db,$user,'salesreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'salesreport'; ?>"><span>Cnotes Sales</span></a></li>   <?php } ?>   
                    <?php if(module($db,$user,'salessummary')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'salessummary'; ?>"><span>Sales Summary</span></a></li>   <?php } ?>   
                    <?php if(module($db,$user,'newbusinessreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'newbusinessreport'; ?>"><span>New Business</span></a></li><?php } ?>      
                    <?php if(module($db,$user,'deviationreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'deviationreport'; ?>"><span>Deviation</span></a></li>  <?php } ?>    
                    <?php if(module($db,$user,'tonnagereport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'tonnagereport'; ?>"><span>Tonnage</span></a></li>   <?php } ?>  
                    <?php if(module($db,$user,'gstreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'gstreport'; ?>"><span>GST</span></a></li>   <?php } ?>  
                    <?php if(module($db,$user,'extgstreport')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'extgstreport'; ?>"><span>GST</span></a></li>   <?php } ?>  
                    <!-- <li><a href="<?php echo __ROOT__ . 'performancereport'; ?>"><span>Performance</span></a></li>   -->
          </ul>
        </li><?php }?>
        <?php if(module($db,$user,'cashimportlist')=='1' ||module($db,$user,'creditimportlist')=='1'){ ?>
        <li><a href="#utilities" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa  fa-suitcase"></i> <span>Utilities</span></a>
          <ul class="collapse list-unstyled" id="utilities">
                    <?php if(module($db,$user,'cashimportlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'cashimportlist'; ?>"><span>Cash Bulk Upload</span></a></li><?php } ?>
                    <?php if(module($db,$user,'creditimportlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'creditimportlist'; ?>"><span>Credit Bulk Upload</span></a></li><?php } ?>
                   
                    
          </ul>
        </li><?php }?>
        <?php if(module($db,$user,'userlist')=='1' ||module($db,$user,'usergrouplist')=='1'){ ?>
        <li><a href="#users" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa  fa-users"></i> <span>Users</span></a>
          <ul class="collapse list-unstyled"  id="users">
                    <?php if(module($db,$user,'userlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'userlist'; ?>"><span>Users</span></a></li><?php } ?>
                    <?php if(module($db,$user,'usergrouplist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'usergrouplist'; ?>"><span>User group</span></a></li><?php } ?>
                  
                    
          </ul>
        </li><?php }?>
        <?php if(module($db,$user,'addresslist')=='1' ||module($db,$user,'countrylist')=='1' ||module($db,$user,'modelist')=='1' ||module($db,$user,'peopletypelist')=='1' ||module($db,$user,'pincodelist')=='1'
         ||module($db,$user,'regionlist')=='1' ||module($db,$user,'referencelist')=='1' ||module($db,$user,'stationcodelist')=='1' ||module($db,$user,'statuslist')=='1' ||module($db,$user,'statelist')=='1' ||module($db,$user,'taxlist')=='1'){ ?>
        <li><a href="#settings" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa  fa-gears (alias)"></i> <span>Settings</span></a>
          <ul class="collapse list-unstyled" id="settings">
                    <?php if(module($db,$user,'addresslist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'addresslist'; ?>"><span>Address</span></a></li><?php } ?>
                    <?php if(module($db,$user,'countrylist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'countrylist'; ?>"><span>Country</span></a></li><?php } ?>
                    <?php if(module($db,$user,'modelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'modelist'; ?>"><span>Mode</span></a></li> <?php } ?>
                    <?php if(module($db,$user,'peopletypelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'peopletypelist'; ?>"><span>People Type</span></a></li><?php } ?>
                    <?php if(module($db,$user,'pincodelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'pincodelist'; ?>"><span>Pincode</span></a></li> <?php } ?>
                    <?php if(module($db,$user,'regionlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'regionlist'; ?>"><span>Region</span></a></li>  <?php } ?>   
                    <?php if(module($db,$user,'referencelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'referencelist'; ?>"><span>Reference</span></a></li><?php } ?>
                    <?php if(module($db,$user,'stationcodelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'stationcodelist'; ?>">Station</a></li><?php } ?>
                    <?php if(module($db,$user,'statuslist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'statuslist'; ?>"><span>Status </span></a></li>    <?php } ?>        
                    <?php if(module($db,$user,'statelist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'statelist'; ?>"><span>State</span></a></li><?php } ?>
                    <?php if(module($db,$user,'taxlist')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'taxlist'; ?>"><span>Tax</span></a></li>   <?php } ?>           
          </ul>
         </li><?php }?>
         <?php if(module($db,$user,'newmodule')=='1'){ ?>
        <li><a href="#newmodule" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa  fa-gears (alias)"></i> <span>newmodule</span></a>
          <ul class="collapse list-unstyled" id="newmodule">  
            <?php if(module($db,$user,'newmodule')=='1'){ ?><li><a href="<?php echo __ROOT__ . 'test1'; ?>"><span>test1</span></a></li><?php } ?>
         </ul>     
         </li><?php }?>
          <!-- links -->
          <li><a href="#links" data-toggle="collapse" aria-expanded="false" class="parent"><i class="fa fa-link" aria-hidden="true"></i><span>Links</span></a>
          <ul class="collapse list-unstyled" id="links">
                  <li><a href="<?php echo __ROOT__ . 'Homepage'; ?>"><span>Home</span></a></li>
                   <li><a href="<?php echo __ROOT__ . 'about'; ?>"><span>About us</span></a></li>
                   <li><a href="<?php echo __ROOT__ . 'privacy'; ?>"><span>Privacy Policy</span></a></li>
                   <li><a href="<?php echo __ROOT__ . 'returnpolicy'; ?>"><span>Return, Cancellation & Refund Policy</span></a></li>
                   <li><a href="<?php echo __ROOT__ . 'terms'; ?>"><span>Terms&nbsp&nbsp&<br> Conditions</span></a></li>
                    <li><a href="https://pcnl.in/PCNLWebPages/CustomerCare.aspx" target="_blank" data-toggle="tooltip" title="Note: Content opens in a new browser tab"><span>Contact us</span></a></li>
            </ul>
         </li>
          <!-- links -->
          </ul>
        </li>
      </ul>
    </nav>
       
<div id="content" class="container-fluid">
      
<script>

$(window).on('load', function () {
   var usr_name = $('#usr_head').val();    
        $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{usr_name:usr_name},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#stncodes_val').val(data[0]);
            $('#stncodes_val').html(data[0]);
            }
    });
  });
  </script>
<script>
$('#stncodes_head').change(function(){
        var stncodes_head = $('#stncodes_head').val();
        var usr_head = $('#usr_head').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{stncodes_head:stncodes_head,usr_head:usr_head},
            dataType:"text",
            success:function(value)
            {
              var data = value.split(",");
              $('#stncode').val(data[0]);
              $('#stnname').val(data[1]);
              location.reload();
            }
    });
    });
</script>
<script>
function changestation(selectedstation){
        var stncodes_head = selectedstation;
        var usr_head = $('#usr_head').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{stncodes_head:stncodes_head,usr_head:usr_head},
            dataType:"text",
            success:function(value)
            {
              var data = value.split(",");
              $('#stncode').val(data[0]);
              $('#stnname').val(data[1]);
              location.reload();
            }
        });
    }
</script>

<script>
$(document).ready(function(){
      $('#menu').find('.open').children('ul').addClass('in');
})
</script>
<script>
$('.parent').click(function(){
  if($(this).parent().hasClass('open')){
    $('.collapse').removeclass('in');
  }
  else{
  $('.collapse').removeClass('in');
  $('#menu').find('li').removeClass('open');
  }
  });
</script>
<script>
  $('#br_codes').change(function() {
    //Use $option (with the "$") to see that the variable is a jQuery object
    var $option = $(this).find('option:selected');
    //Added with the EDIT
    var value = $option.val();//to get content of "value" attrib
    var text = $option.text();//to get <option>Text</option> content
        var stncodes_head = value;
        var usr_head = $('#usr_head').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{brcodes_head:stncodes_head,usr_brhead:usr_head},
            dataType:"text",
            success:function(value2)
            {
              location.reload();
            }
        });

});

</script>
<script>

    document.onkeydown = function(e) {
            if (e.ctrlKey && ( e.keyCode === 85 || e.keyCode === 117)) {//Alt+c, Alt+v will also be disabled sadly.
               return false;
            }
    };
    </script>
    <script>
      $('.megadropdown').click(function(){
        $('.dropdown-content').toggle();

        if($('.dropdown-content').css('display')=='block'){
         // $('body').attr('onclick','$(".dropdown-content").css("display","none")');
        }
        else{
         // $('body').attr('onclick',"ddd");
        }

      })
      </script>
<!-- mobile view scripts -->
 <script>

//  jQuery(window).resize(function () {
//   resizeaction();
//   });

//   $(document).ready(function(){
//     resizeaction();
//   });
//   function resizeaction(){
//     var screen = $(window).width();
//       console.log(screen);
//       if (screen < 500) {
//         $("#column-left").removeClass("active");
//       }
//      else {
//        $("#column-left").addClass("active");
//       }
//   }
</script>
  <!-- mobile view scripts -->
