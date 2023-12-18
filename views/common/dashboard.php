<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 


function authorize($db,$user)
{
    
    $module = 'dashboard';
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

function countprocurement($db,$stationcode,$filter){
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
    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode'"); 
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function countprinted($db,$stationcode,$filter){
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
  

    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status!='Procured' AND cnote_station='$stationcode' AND $query_filter");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function countalloted($db,$stationcode,$filter){
    $cnotequery = '';

    //filters
    $date_variable = 'allot_date';
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

    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status not in ('procured','printed') AND cnote_station='$stationcode' AND $query_filter");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function countbilled($db,$stationcode,$filter){
    $cnotequery = '';
    //filters
    $date_variable = 'tdate';
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
    $cnotesql = $db->query("SELECT COUNT(*) from credit WITH (NOLOCK) WHERE comp_code='$stationcode' AND $query_filter");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}
//currently not used
function countinvoiced($db,$stationcode,$filter){
    $cnotequery = '';

  

    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status='Invoiced' AND cnote_station='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}
//currently not used
function totalcountvoid($db,$stationcode,$filter){
    $cnotequery = '';

  

    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status IN ('Procured-Void','Printed-Void','Alloted-Void','Billed-Void','Invoiced-Void') AND cnote_station='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function countprocuredvoid($db,$stationcode,$filter){
    $cnotequery = '';

  

    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status='Procured-Void' AND cnote_station='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function countprintedvoid($db,$stationcode,$filter){
    $cnotequery = '';

  

    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status='Printed-Void' AND cnote_station='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function countallotedvoid($db,$stationcode,$filter){
    $cnotequery = '';

  

    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status='Procured-Void' AND cnote_station='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function countbilledvoid($db,$stationcode,$filter){
    $cnotequery = '';
    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status='Procured-Void' AND cnote_station='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function countinvoicedvoid($db,$stationcode,$filter){
    $cnotequery = '';

  

    $cnotesql = $db->query("SELECT COUNT(*) from cnotenumber WITH (NOLOCK) WHERE cnote_status='invoiced-Void' AND cnote_station='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function branch($db,$stationcode,$filter){
    $cnotequery = '';
    $cnotesql = $db->query("SELECT COUNT(*) from branch WHERE stncode='$stationcode'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}
function babranch($db,$stationcode,$filter){
    $cnotequery = '';

    $cnotesql = $db->query("SELECT COUNT(*) from branch WHERE stncode='$stationcode' AND branchtype='B'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function fabranch($db,$stationcode,$filter){
    $cnotequery = '';

  

    $cnotesql = $db->query("SELECT COUNT(*) from branch WHERE stncode='$stationcode' AND branchtype='F'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function dcbranch($db,$stationcode,$filter){
    $cnotequery = '';

  

    $cnotesql = $db->query("SELECT COUNT(*) from branch WHERE stncode='$stationcode' AND branchtype='D'");
    $cnotequery .= $cnotesql->fetchColumn();
    return $cnotequery;
}

function customer($db,$stationcode,$filter){
    $cnotesql = $db->query("SELECT COUNT(*) as 'total' from customer WHERE comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
    return $cnotesql->total;
}

function bacustomer($db,$stationcode,$filter){
    $bcustomer[]='';
    $bbranch = $db->query("SELECT br_code from branch WHERE stncode='$stationcode' AND branchtype='B'");
    while($row=$bbranch->fetch(PDO::FETCH_OBJ)){
        $bcust = $db->query("SELECT COUNT(*) as 'total' FROM customer WHERE br_code='$row->br_code' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
        array_push($bcustomer,$bcust->total);
    }
    return array_sum($bcustomer);
}

function facustomer($db,$stationcode,$filter){
    $bcustomer[]='';
    $bbranch = $db->query("SELECT br_code from branch WHERE stncode='$stationcode' AND branchtype='F'");
    while($row=$bbranch->fetch(PDO::FETCH_OBJ)){
        $bcust = $db->query("SELECT COUNT(*) as 'total' FROM customer WHERE br_code='$row->br_code' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
        array_push($bcustomer,$bcust->total);
    }
    return array_sum($bcustomer);
}
function dccustomer($db,$stationcode,$filter){
    $bcustomer[]='';
    $bbranch = $db->query("SELECT br_code from branch WHERE stncode='$stationcode' AND branchtype='D'");
    while($row=$bbranch->fetch(PDO::FETCH_OBJ)){
        $bcust = $db->query("SELECT COUNT(*) as 'total' FROM customer WHERE br_code='$row->br_code' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
        array_push($bcustomer,$bcust->total);
    }
    return array_sum($bcustomer);
}
//not used
function totalinv($db,$stationcode,$filter){
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
    $query = $db->query("SELECT COUNT(*) as 'total' from invoice WITH (NOLOCK) WHERE comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);

    return $query->total;
}
//not used
function pendinginv($db,$stationcode,$filter){
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
    $query = $db->query("SELECT COUNT(*) as 'total' from invoice WITH (NOLOCK) WHERE comp_code='$stationcode' AND status='Raised' OR status='Pending' AND $query_filter")->fetch(PDO::FETCH_OBJ);

    return $query->total;
}
//not used
function collectedinv($db,$stationcode,$filter){
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
    $query = $db->query("SELECT COUNT(*) as 'total' from invoice WITH (NOLOCK) WHERE comp_code='$stationcode' AND status='Collected' AND $query_filter")->fetch(PDO::FETCH_OBJ);

    return $query->total;
}
//not used
function totalcollection($db,$stationcode,$filter){
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
    $query = $db->query("SELECT SUM(netamt) as 'total' from invoice WHERE comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);

    return round($query->total);
}
//not used
function pendingcollection($db,$stationcode,$filter){
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
    $total = $db->query("SELECT SUM(netamt) as 'total' from invoice WHERE comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);
    $rcved = $db->query("SELECT SUM(rcvdamt) as 'rcvd' from invoice WHERE comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);

    return round($total->total)-round($rcved->rcvd);
}
//not used
function collectedcollection($db,$stationcode,$filter){
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
    $query = $db->query("SELECT SUM(rcvdamt) as 'total' from invoice WHERE comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);

    return round($query->total);
}
function unbilled($db,$stationcode,$filter){
    //filters
    $date_variable = 'tdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate())";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='thisweek'){
         $query_filter = "$date_variable BETWEEN CAST(DATEADD(DD, 1 - DATEPART(DW, getdate()),  getdate()) as date) AND GETDATE()";
     }
     else if($filter=='lastweek'){
        $query_filter = "$date_variable BETWEEN CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 0) as DATE) AND CAST(DATEADD(wk, DATEDIFF(wk, 6, GETDATE()), 6) as DATE)";
     }
     //filters end
    $total = $db->query("SELECT COUNT(*) as total FROM cnotenumber WHERE opstatus='Unbilled' AND (SELECT count(cno) FROM cash WHERE cno=cnote_number AND comp_code=cnote_station)=0 AND cnote_status='Allotted' AND cnote_station='$stationcode' AND $query_filter AND (SELECT count(cno) FROM credit WHERE cno=cnote_number AND comp_code=cnote_station)=0")->fetch(PDO::FETCH_OBJ);
    return $total->total;
}
function invoiced($db,$stationcode,$filter){
    //filters
    $date_variable = 'tdate';
    $query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -2, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -2, getdate()))";
     }
    //filters end
    $total = $db->query("SELECT COUNT(*) as total FROM credit WITH (NOLOCK) WHERE status='Invoiced' AND comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);
    return $total->total;
}
function invoicedcnotevalue($db,$stationcode,$filter){
    //filters
    $date_variable = 'tdate';
    $query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -1, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -1, getdate()))";
     }
     else if($filter=='lastmonth'){
        $query_filter = "DATEPART(m, $date_variable) = DATEPART(m, DATEADD(m, -2, getdate())) AND DATEPART(yyyy, $date_variable) = DATEPART(yyyy, DATEADD(m, -2, getdate()))";
     }
    //filters end
    $total = $db->query("SELECT SUM(amt) as total FROM credit WITH (NOLOCK) WHERE status='Invoiced' AND comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);
    return amount_inr_format($total->total);
}
function uninvoiced($db,$stationcode,$filter){
    //filters
    $date_variable = 'tdate';
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = " AND $date_variable <= cast(dateadd(d,-(day(getdate())),getdate()) as date)";
    }
    //filters end
    $total = $db->query("SELECT COUNT(*) as total,SUM(amt) as totalamt  FROM credit WITH (NOLOCK) WHERE  status='Billed' AND comp_code='$stationcode' $query_filter")->fetch(PDO::FETCH_OBJ);
    return array($total->total,amount_inr_format($total->totalamt));
}
// function uninvoicedvalue($db,$stationcode,$filter){
//     //filters
//     $date_variable = 'tdate';
//     $query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND getdate()";
//     //filters end
//     $total = $db->query("SELECT SUM(amt) as total FROM credit WITH (NOLOCK) WHERE status='Billed' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
//     return amount_inr_format(round($total->total));
// }
//not used
function invoicedvalue($db,$stationcode,$filter){
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
    $value = $db->query("SELECT SUM(netamt) as total FROM invoice WITH (NOLOCK) WHERE comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);
    return amount_inr_format($value->total);
}
function invoicecount($db,$stationcode,$filter){
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
    $value = $db->query("SELECT COUNT(*) as total FROM invoice WITH (NOLOCK) WHERE comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);
    return $value->total;
}
function invoicevalue($db,$stationcode,$filter){
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
    $value = $db->query("SELECT SUM(netamt) as total FROM invoice WITH (NOLOCK) WHERE comp_code='$stationcode' AND $query_filter")->fetch(PDO::FETCH_OBJ);
    return amount_inr_format(round($value->total));
}
function collectiontotal($db,$stationcode,$filter){
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
    $value = $db->query("SELECT COUNT(*) as total FROM collection WHERE comp_code='$stationcode' AND $query_filter AND status NOT LIKE '%Void'")->fetch(PDO::FETCH_OBJ);
    return $value->total;
}
function collectedamount($db,$stationcode,$filter){
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
    $value = $db->query("SELECT SUM(netcollamt) as total FROM collection WHERE comp_code='$stationcode' AND $query_filter AND status NOT LIKE '%Void'")->fetch(PDO::FETCH_OBJ);
    return amount_inr_format($value->total);
}
function collectedtotalamount($db,$stationcode,$filter){
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
    $value = $db->query("SELECT invno,comp_code,invamt,(select netamt-rcvdamt from invoice where invno=c.invno and comp_code=c.comp_code and status='Pending') inv_balamt FROM collection c WHERE comp_code='$stationcode' AND $query_filter AND status NOT LIKE '%Void' GROUP BY invno,comp_code,invamt");
    $total = 0;
    while($row = $value->fetch(PDO::FETCH_OBJ)){
        $total = $total+$row->inv_balamt;
    }
    return amount_inr_format(round($total));
}
function collectedtotal($db,$stationcode,$filter){
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
    $value2 = $db->query("SELECT DISTINCT invno,comp_code FROM collection WHERE comp_code='$stationcode' AND $query_filter AND status NOT LIKE '%Void'")->fetch(PDO::FETCH_OBJ);
    $value = $db->query(" SELECT @@ROWCOUNT as counts")->fetch(PDO::FETCH_OBJ);
    return ($value->counts);
}
function pendinginvoices($db,$stationcode,$filter){
    $date_variable = 'invdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
    $value = $db->query("SELECT COUNT(*) as total FROM invoice WHERE comp_code='$stationcode' AND status='Pending' $query_filter")->fetch(PDO::FETCH_OBJ);
    return $value->total;
}

function pendinginvoicevalue($db,$stationcode,$filter){
    //filters
    $date_variable = 'invdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //filters end
    $value = $db->query("SELECT SUM(netamt-rcvdamt) as total FROM invoice WHERE comp_code='$stationcode' AND status='Pending' $query_filter")->fetch(PDO::FETCH_OBJ);
    return amount_inr_format(round($value->total));
}

//methods for external users
function ext_pendinginvoicevalue($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'invdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT count(cust_code) pendingcount,sum(netamt-rcvdamt) pendingvalue FROM invoice WHERE comp_code='$stationcode' AND status='Pending' $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("Invcounts"=>$value->pendingcount,"PendValue"=>amount_inr_format(round($value->pendingvalue)));
}
function ext_fullypaidinvoices($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'invdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT count(cust_code) invoicecount,sum(netamt) invoicevalue FROM invoice WHERE comp_code='$stationcode' AND status='Collected' $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("Invcounts"=>$value->invoicecount,"PendValue"=>amount_inr_format(round($value->invoicevalue)));
}
function ext_partlypaidinvoices($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'invdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
        $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";             
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT count(cust_code) pendingcount,sum(netamt-rcvdamt) pendingvalue FROM invoice WHERE comp_code='$stationcode' AND status='Pending' and rcvdamt>0 $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("Invcounts"=>$value->pendingcount,"PendValue"=>amount_inr_format(round($value->pendingvalue)));
}
function ext_cnotes_requested($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'invdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT sum(cast(cnote_quantity as int)) cnotecounts,sum(net_amt) req_amt FROM cnotesalesrequest WHERE comp_code='$stationcode' $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("cnotecounts"=>$value->cnotecounts,"req_amt"=>amount_inr_format(round($value->req_amt)));
}
function ext_cnotes_approved($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'invdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT sum(cast(cnote_quantity as int)) cnotecounts,sum(net_amt) req_amt FROM cnotesalesrequest WHERE comp_code='$stationcode' AND status='Approved' $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("cnotecounts"=>$value->cnotecounts,"req_amt"=>amount_inr_format(round($value->req_amt)));
}
function ext_cnotes_pending($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'invdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT sum(cast(cnote_quantity as int)) cnotecounts,sum(net_amt) req_amt FROM cnotesalesrequest WHERE comp_code='$stationcode' AND status='Requested' $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("cnotecounts"=>$value->cnotecounts,"req_amt"=>amount_inr_format(round($value->req_amt)));
}
function ext_lastmonthbilled($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'tdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT count(*) cnotecounts,sum(amt) sumamt FROM credit WHERE comp_code='$stationcode' AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date) $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("cnotecounts"=>$value->cnotecounts,"sumamt"=>amount_inr_format(round($value->sumamt)));
}
function ext_currentmonthbilled($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'tdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT count(*) cnotecounts,sum(amt) sumamt FROM credit WHERE comp_code='$stationcode' AND datepart(mm,$date_variable) = month(getdate()) AND datepart(year,$date_variable) = year(getdate()) $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("cnotecounts"=>$value->cnotecounts,"sumamt"=>amount_inr_format(round($value->sumamt)));
}
function ext_yesterdaybilled($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'tdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT count(*) cnotecounts,sum(amt) sumamt FROM credit WHERE comp_code='$stationcode' AND $date_variable=DATEADD(day, -1, CAST(GETDATE() AS date)) $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("cnotecounts"=>$value->cnotecounts,"sumamt"=>amount_inr_format(round($value->sumamt)));
}
function ext_todaybilled($db,$stationcode,$filter,$br_code,$cust_code){
    //filters
    $date_variable = 'tdate';
    //$query_filter = "$date_variable BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-2, 0) AND getdate()";
    if($filter=='' || $filter=='thismonth'){
        $query_filter = "";
     }
     else if($filter=='lastmonth'){
        $query_filter = "AND $date_variable<=CAST(DATEADD(day,-1,DATEADD(MM, DATEDIFF(MM,0,GETDATE()),0)) as date)";
     }
     else if($filter=='thisweek'){
         $query_filter = "";
     }
     else if($filter=='lastweek'){
        $query_filter = "";
     }
     //branch and customer
     $br_filter = "";
     $cust_filter = "";
     if(!empty($br_code)){
        $br_filter = " AND br_code='$br_code'";
     }
     if(!empty($cust_code)){
        $cust_filter = " AND cust_code='$cust_code'";
     }
     //branch and customer
     //filters end
    $value = $db->query("SELECT count(*) cnotecounts,sum(amt) sumamt FROM credit WHERE comp_code='$stationcode' AND $date_variable=cast(getdate() as date) $br_filter $cust_filter")->fetch(PDO::FETCH_OBJ);//$query_filter 
    return array("cnotecounts"=>$value->cnotecounts,"sumamt"=>amount_inr_format(round($value->sumamt)));
}

function countoperationcnotes($db,$stationcode,$filter){
    $cnotequery = '';
    //filters
    $date_variable = 'tdate';
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
    $cnotesql = $db->query("SELECT COUNT(DISTINCT(cno)) opcount FROM opdata WITH (NOLOCK) WHERE $query_filter AND origin='$stationcode'")->fetch(PDO::FETCH_OBJ);

    $cnotequery = $cnotesql->opcount;
    return $cnotequery;
}
function countnotprocessedcnotes($db,$stationcode,$filter){
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
    $cnotesql = $db->query("SELECT COUNT(cnote_number) notprocessedopcount FROM cnotenumber WITH (NOLOCK) WHERE $query_filter AND cnote_station='$stationcode' AND opstatus IS NULL")->fetch(PDO::FETCH_OBJ);

    $cnotequery = $cnotesql->notprocessedopcount;
    return $cnotequery;
}
// echo json_encode(ext_fullypaidinvoices($db,$stationcode,$filter,$user_branch,$user_customer));exit;
//methods for external users
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
            if(empty($user_branch) && empty($user_customer)){
            ?>

            <section class="welcome p-t-10">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                        <br class="line-seprate">
                            <h3 class="title-5">Cnote</h3>
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
                                <h2 class="number"><?php echo countprocurement($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Ordered</span>
                                <hr>
                                <h4 class="number"><?php echo countprocuredvoid($db,$stationcode,$filter); ?></h4>
                                <span class="desc">Ordered
                                 Void</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo countprinted($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Received</span>
                                <hr>
                                <h4 class="number"><?php echo countprintedvoid($db,$stationcode,$filter); ?></h4>
                                <span class="desc">Received void</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-calendar-note"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo countalloted($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Alloted</span>
                                <hr>
                                <h4 class="number"><?php echo countallotedvoid($db,$stationcode,$filter); ?></h4>
                                <span class="desc">Alloted Void</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-money"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    if($filter=='' || $filter=='thismonth' || $filter=='lastmonth'){
                ?>
                    <section class="welcome p-t-10">
                        <div class="col-md-7">
                            <h3 class="title-5">Billing</h3>
                        </div>
                        <div class="col-md-3">
                            <h3 class="title-5">Op Data</h3>
                        </div>
            </section>
                    <div class="row">
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo unbilled($db,$stationcode,$filter); ?></h2>
                                <span class="desc">UnBilled</span>
                                <hr>
                                <h4 class="number">0</h4>
                                <span class="desc">UnBilled Void</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-account-o"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo countbilled($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Billed</span>
                                <hr>
                                <h4 class="number"><?php echo countbilledvoid($db,$stationcode,$filter); ?></h4>
                                <span class="desc">Billed Void</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-account-o"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo countoperationcnotes($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Processed</span>
                                <hr>
                                <h4 class="number"><?=countnotprocessedcnotes($db,$stationcode,$filter)?></h4>
                                <span class="desc">Not Processed</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-account-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="welcome p-t-10">
               
                        <div class="col-md-12">
                            <h3 class="title-5">Invoice</h3>
                            <!-- <hr class="line-seprate"> -->
                        </div>
            </section>
                    <div class="row">
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo uninvoiced($db,$stationcode,$filter)[0]; ?></h2>
                                <span class="desc">UnInvoiced Cnote Count</span>
                                <hr>
                                <h4 class="number">&#8377; <?=uninvoiced($db,$stationcode,$filter)[1]?></h4>
                                <span class="desc">Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                     
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo invoiced($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Invoiced Cnote Count</span>
                                <hr>
                                <h4 class="number">&#8377; <?= invoicedcnotevalue($db,$stationcode,$filter) ?></h4>
                                <span class="desc">Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?=invoicecount($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Invoices Count</span>
                                <hr>
                                <h4 class="number">&#8377; <?=invoicevalue($db,$stationcode,$filter)?></h4>
                                <span class="desc">Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="welcome p-t-10">
                            <div class="col-md-12">
                                <h3 class="title-5">Collection</h3>
                                <!-- <hr class="line-seprate"> -->
                            </div>
                </section>
                <div class="row">   
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?=pendinginvoices($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Pending Invoices</span>
                                <hr>
                                <h4 class="number">&#8377; <?=pendinginvoicevalue($db,$stationcode,$filter); ?></h4>
                                <span class="desc"> Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?= collectedtotal($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Collected Invoice count</span>
                                <hr>
                                <h4 class="number">&#8377; <?=collectedtotalamount($db,$stationcode,$filter); ?></h4>
                                <span class="desc">Pending Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?= collectiontotal($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Collection</span>
                                <hr>
                                <h4 class="number">&#8377; <?=collectedamount($db,$stationcode,$filter); ?></h4>
                                <span class="desc">Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    }
                    else if( $filter=='thisweek' || $filter=='lastweek')
                    {
                    ?>
                      <section class="welcome p-t-10">
                                    <div class="col-md-12">
                                        <h3 class="title-5">Operations</h3>
                                        <!-- <hr class="line-seprate"> -->
                                    </div>
                        </section>
                        <div class="row">
                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo countbilled($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Billed</span>
                                <hr>
                                <h4 class="number"><?php echo countbilledvoid($db,$stationcode,$filter); ?></h4>
                                <span class="desc">Billed Void</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-account-o"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?=invoicecount($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Invoices Count</span>
                                <hr>
                                <h4 class="number">&#8377; <?=invoicevalue($db,$stationcode,$filter)?></h4>
                                <span class="desc">Invoices Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 stats">
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?= collectiontotal($db,$stationcode,$filter); ?></h2>
                                <span class="desc">Collection Count</span>
                                <hr>
                                <h4 class="number">&#8377; <?=collectedamount($db,$stationcode,$filter); ?></h4>
                                <span class="desc">Collection Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    }
                    ?>
                </div>
            </section>   
            <?php 
                }//external users section
                else if(!empty($user_branch) || !empty($user_customer)){
                    ?>
        
        <!-- cnote valid number -->
        <style>
            .extlink-icon{
                float: right;
                color: #fff;
                font-size: 23px;
            }
        </style>
            <section class="statistic statistic2">
                <div style="margin-top:0px" class="container contain">
            
                <section class="welcome p-t-12">
                        <div class="col-md-12">
                            <h3 class="title-5">Invoice</h3>
                            <!-- <hr class="line-seprate"> -->
                        </div>
                </section>
                    <div class="row">
                        
                        <div class="col-md-6 col-lg-3 stats">
                            <?php $fillypaidinvoice = ext_fullypaidinvoices($db,$stationcode,$filter,$user_branch,$user_customer); ?>
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php  echo $fillypaidinvoice['Invcounts']; ?></h2>
                                <span class="desc">Fully Paid Invoices</span>
                                <hr>
                                <h4 class="number">&#8377; <?= $fillypaidinvoice['PendValue']; ?></h4>
                                <span class="desc">Value</span>
                                <a href="extinvoicelist?filter=collected"><i class="fa fa-external-link extlink-icon" aria-hidden="true"></i></a>

                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 stats">
                        <?php $partlypaidinvoices = ext_partlypaidinvoices($db,$stationcode,$filter,$user_branch,$user_customer); ?>
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?=$partlypaidinvoices['Invcounts']; ?></h2>
                                <span class="desc">Partly Paid Invoices</span>
                                <hr>
                                <h4 class="number">&#8377; <?=$partlypaidinvoices['PendValue']?></h4>
                                <span class="desc">Pending Value</span>
                                <a href="extinvoicelist?filter=partpaid"><i class="fa fa-external-link extlink-icon" aria-hidden="true"></i></a>

                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 stats">
                        <?php $pendinginvoice = ext_pendinginvoicevalue($db,$stationcode,$filter,$user_branch,$user_customer); ?>
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo $pendinginvoice['Invcounts']; ?></h2>
                                <span class="desc">Invoices pending</span>
                                <hr>
                                <h4 class="number">&#8377; <?=$pendinginvoice['PendValue'];?></h4>
                                <span class="desc">Value</span>
                                <a href="extinvoicelist?filter=pending"><i class="fa fa-external-link extlink-icon" aria-hidden="true"></i></a>

                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                  
               
                   
                      <section class="welcome p-t-10">
                        <div class="col-md-12">
                                <h3 class="title-5">C.Notes Billed</h3>
                                <!-- <hr class="line-seprate"> -->
                        </div>
                        </section>
                        <div class="row">
                        <div class="col-md-6 col-lg-3 stats">
                        <?php $ext_cnotes_requested = ext_lastmonthbilled($db,$stationcode,$filter,$user_branch,$user_customer); ?>
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?php echo $ext_cnotes_requested['cnotecounts']; ?></h2>
                                <span class="desc">Billed Last month</span>
                                <hr>
                                <h4 class="number">&#8377; <?php echo $ext_cnotes_requested['sumamt']; ?></h4>
                                <span class="desc">value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-account-o"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 stats">
                        <?php $ext_cnotes_approved = ext_currentmonthbilled($db,$stationcode,$filter,$user_branch,$user_customer); ?>
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?=$ext_cnotes_approved['cnotecounts']; ?></h2>
                                <span class="desc">Billed This month</span>
                                <hr>
                                <h4 class="number">&#8377; <?=$ext_cnotes_approved['sumamt'];?></h4>
                                <span class="desc">Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 stats">
                        <?php $ext_cnotes_pending = ext_yesterdaybilled($db,$stationcode,$filter,$user_branch,$user_customer); ?>
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?=$ext_cnotes_pending['cnotecounts']; ?></h2>
                                <span class="desc">Billed Yesterday</span>
                                <hr>
                                <h4 class="number">&#8377; <?=$ext_cnotes_pending['sumamt']; ?></h4>
                                <span class="desc">Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 stats">
                        <?php $ext_cnotes_pending = ext_todaybilled($db,$stationcode,$filter,$user_branch,$user_customer); ?>
                            <div class="statistic__item statistic__item--skyblue">
                                <h2 class="number"><?=$ext_cnotes_pending['cnotecounts']; ?></h2>
                                <span class="desc">Billed Today</span>
                                <hr>
                                <h4 class="number">&#8377; <?=$ext_cnotes_pending['sumamt']; ?></h4>
                                <span class="desc">Value</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    }
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