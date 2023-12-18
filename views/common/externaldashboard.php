<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 


function authorize($db,$user)
{
    
    $module = 'ex_dashboard';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;

    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return $access='False';
        }
        else{
            return $access='True';
        }
}
if(authorize($db,$user)=='False'){
    echo "<div class='alert alert-danger alert-dismissible'>
    <h4><i class='icon fa fa-times'></i> Alert!</h4>
    Permission denied to access dashboard.
    </div>";
    exit;
  }

date_default_timezone_set('Asia/Kolkata');
function amount_inr_format($number){
    $decimal = (string)($number - floor($number));
    $money = floor($number);
    $length = strlen($money);
    $delimiter = '';
    $money = strrev($money);
  
    for($i=0;$i<$length;$i++){
        if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
            $delimiter .=',';
        }
        $delimiter .=$money[$i];
    }
    $result = strrev($delimiter);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);
  
    if( $decimal != '0'){
        $result = $result.$decimal;
    }
  
    return $result;
  }
$user = $_SESSION['user_name'];

$getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
                     $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
                     $rowuser = $datafetch['last_compcode'];
                     if(!empty($rowuser)){
                           $stationcode = $rowuser;
                     }
                     else{
                         $userstations = $datafetch['stncodes'];
                         $stations = explode(',',$userstations);
                         $stationcode = $stations[0];  
                     }

$user_branch = $datafetch['br_code'];
$user_customer = $datafetch['cust_code'];

if(isset($_POST['month_filter']))
{
$filter = $_POST['month_filter'];
}
else{
$filter = '';
}

function counttotal($db,$stationcode,$filter){
    $cnotequery = '';
    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WHERE cnote_station='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery; 
}
function allotted($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'date_added';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode' AND cust_code='$user_customer' AND cnote_status='Allotted'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}
function allotted_void($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'date_added';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode' AND cust_code='$user_customer' AND cnote_status='Allotted-Void'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}
function billed($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'bdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode' AND cust_code='$user_customer' AND cnote_status='Billed'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}
function billed_void($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'bdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode' AND cust_code='$user_customer' AND cnote_status='Billed-Void'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}
function billed_amount($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'bdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT SUM(bilrate) as amount from cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode' AND cust_code='$user_customer' AND cnote_status='Billed'")->fetch(PDO::FETCH_OBJ); 
    return number_format($cnotesql->amount,2);
}


function invoiced_cnote($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'bdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode' AND cust_code='$user_customer' AND cnote_status='Invoiced'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}


function invoiced_cnote_amount($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'bdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
     $cnotesql = $db->query("SELECT SUM(bilrate) as amount from cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode' AND cust_code='$user_customer' AND cnote_status='Invoiced'")->fetch(PDO::FETCH_OBJ); 
     return number_format($cnotesql->amount,2);

}
function total_invoiced($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'invdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT COUNT(*) from invoice WITH (NOLOCK) WHERE $query_filter AND comp_code='$stationcode' AND cust_code='$user_customer'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function total_invoiced_amount($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'invdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
     $cnotesql = $db->query("SELECT SUM(netamt) as amount from invoice WITH (NOLOCK) WHERE $query_filter AND comp_code='$stationcode' AND cust_code='$user_customer'")->fetch(PDO::FETCH_OBJ); 
     return number_format($cnotesql->amount,2);
}

function collect($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'coldate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT COUNT(*) from collection WITH (NOLOCK) WHERE $query_filter AND comp_code='$stationcode' AND cust_code='$user_customer' AND status='Collected'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}
function collect_amt($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'coldate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
     $cnotesql = $db->query("SELECT SUM(colamt) as amount from collection WITH (NOLOCK) WHERE $query_filter AND comp_code='$stationcode' AND cust_code='$user_customer' AND status='Collected'")->fetch(PDO::FETCH_OBJ); 
     return number_format($cnotesql->amount,2);
}

function pending($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'coldate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $cnotesql = $db->query("SELECT COUNT(*) from collection WITH (NOLOCK) WHERE $query_filter AND comp_code='$stationcode' AND cust_code='$user_customer' AND status='Pending'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function pending_amt($db,$stationcode,$filter,$user_customer){
    $cnotequery = '';
    //filters
    $date_variable = 'coldate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND CAST(GETDATE() as DATE)";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
     $cnotesql = $db->query("SELECT SUM(balamt) as amount from collection WITH (NOLOCK) WHERE $query_filter AND comp_code='$stationcode' AND cust_code='$user_customer' AND status='Pending'")->fetch(PDO::FETCH_OBJ); 
     return number_format($cnotesql->amount,2);
}
?>

<style>
.number{
    color:white;
}
section {
	  position: revert;
	}
    
</style>

<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  

         <div class="alert-primary" role="alert">

               <?php 
  
                    if ( isset($_GET['success']) && $_GET['success'] == 1 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Login successfully.
                                </div>";
                  }
                ?>
        </div>

    <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
          
            <section class="statistic statistic2">
                <div class="container contain">
                    <div class="row">
                    <form method="POST" id="month_form">
                    <div class="col-sm-1 col-lg-2 stats"><h3 class="title-5">Period</h3>
                    <select class="form-control" name="month_filter" id="month_filter">
                    <option value="thismonth" <?php if(isset($_POST['month_filter'])){if($_POST['month_filter']=='thismonth'){echo 'Selected'; } } ?>>This Month</option>
                    <option value="lastmonth" <?php if(isset($_POST['month_filter'])){if($_POST['month_filter']=='lastmonth'){echo 'Selected'; } } ?>>Last Month</option>
                    <option value="thisweek" <?php if(isset($_POST['month_filter'])){if($_POST['month_filter']=='thisweek'){echo 'Selected'; } } ?>>This Week</option>
                    <option value="lastweek" <?php if(isset($_POST['month_filter'])){if($_POST['month_filter']=='lastweek'){echo 'Selected'; } } ?>>Last Week</option>
                    </select>
                    </div> 
                   
                    </form>
                    </div>
                </div>
            </section>

            <?php 
          
            ?>

            <section class="welcome p-t-10">
            <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                        <br class="line-seprate">
                            <h2 class="title-5" style="margin-left:8px;">Cnote</h2>
                            <br class="line-seprate">
                        </div>
                    </div>
                </div>
            </section>
        <!-- cnote valid number -->
            <section class="statistic statistic2">
                <div class="container contain">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 stats">
                       
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo allotted($db,$stationcode,$filter,$user_customer); ?></h2>
                                <span class="desc">Allotted</span>
                                <hr>
                                <h4 class="number"><?php echo allotted_void($db,$stationcode,$filter,$user_customer); ?></h4>
                                <span class="desc">Allotted
                                 Void</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                            
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo billed($db,$stationcode,$filter,$user_customer); ?></h2>
                                <span class="desc">Billed</span>
                                <hr>
                                <h4 class="number"><?php echo billed_void($db,$stationcode,$filter,$user_customer); ?></h4>
                                <span class="desc">Billed Void</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-calendar-note"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                     
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo invoiced_cnote($db,$stationcode,$filter,$user_customer); ?></h2>
                                <span class="desc">Invoice Cnote</span>
                                <hr>
                                <h4 class="number"><?php echo invoiced_cnote_amount($db,$stationcode,$filter,$user_customer); ?></h4>
                                <span class="desc">Invoice Amount</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-money"></i>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    <section class="welcome p-t-10">
            <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                        <br class="line-seprate">
                            <h2 class="title-5" style="margin-left:8px;">Invoice</h2>
                            <br class="line-seprate">
                        </div>
                    </div>
                </div>
            </section>
                    <div class="row">
                        <div class="col-md-6 col-lg-3 stats">
                     
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo total_invoiced($db,$stationcode,$filter,$user_customer); ?></h2>
                                <span class="desc">Invoiced</span>
                                <hr>
                                <h4 class="number"><?php echo total_invoiced_amount($db,$stationcode,$filter,$user_customer); ?></h4>
                                <span class="desc">Invoiced Amount
                                 </span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                         
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo collect($db,$stationcode,$filter,$user_customer); ?></h2>
                                <span class="desc">Collection</span>
                                <hr>
                                <h4 class="number"><?php echo collect_amt($db,$stationcode,$filter,$user_customer); ?></h4>
                                <span class="desc">Collection Amount</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-calendar-note"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                   
                            <div class="statistic__item statistic__item--skyblue">
                            <h2 class="number"><?php echo pending($db,$stationcode,$filter,$user_customer); ?></h2>
                                <span class="desc">Pending</span>
                                <hr>
                                <h2 class="number"><?php echo pending_amt($db,$stationcode,$filter,$user_customer); ?></h2>
                                <span class="desc">Pending Amount</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-money"></i>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </section>   
            <?php 
                    ?>
                </div>
            </section>           
                  
                </div>
            </section>  
                      
<!-- inline scripts related to this page -->
<script>
    $('#month_filter').change(function(){
        $('#month_form').submit();
    });
</script>