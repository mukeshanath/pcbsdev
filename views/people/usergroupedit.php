<?php defined('__ROOT__') OR exit('No direct script access allowed'); 

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{

    $module = 'usergroupedit';
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
            You don't have permission to access this Page.
            </div>";
            exit;
  }
  
function loadstationcode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM stncodetb ORDER BY stnid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stncode.'">'.$row->stncode.'</option>';
         }
         return $output;    
        
    }

if(isset($_GET['grp_id'])){
    $group_name = $_GET['grp_id'];

    $user_grouptype = $db->query("SELECT TOP 1 user_type FROM users_group WHERE group_name='$group_name'")->fetch(PDO::FETCH_OBJ);

    $dashboard              =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='dashboard'");
    $branch                 =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='branch'");
    $branchedit             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='branchedit'");
    $branchlist             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='branchlist'");
    $branchdetail           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='branchdetail'");
    $bookingcounter         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bookingcounter'");
    $bookingcounteredit     =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bookingcounteredit'");
    $bookingcounterlist     =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bookingcounterlist'");
    $bookingcounterdetail   =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bookingcounterdetail'");
    $company                =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='company'");
    $companyedit            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='companyedit'");
    $companylist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='companylist'");
    $companydetail          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='companydetail'");
    $customer               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customer'");
    $customeredit           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customeredit'");
    $customerlist           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerlist'");
    $customerdetail         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerdetail'");
    //$customersetting         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customersetting'");
    $newmodule         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='newmodule'");
    $newmodule1         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='newmodule1'");
    $newmodule4         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='newmodule4'");
    $destination            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='destination'");
    $destinationedit        =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='destinationedit'");
    $destinationlist        =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='destinationlist'");
    $group                  =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='group'");
    $groupedit              =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='groupedit'");
    $grouplist              =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='grouplist'");
    $itemtype               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='itemtype'");
    $itemedit               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='itemedit'");
    $itemlist               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='itemlist'");
    $peopleadd              =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peopleadd'");
    $peopleedit             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peopleedit'");
    $peoplelist             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peoplelist'");
    $peopledetail           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peopledetail'");
    $vendors                =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='vendors'");
    $vendorsedit            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='vendorsedit'");
    $vendorslist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='vendorslist'");
    $vendorsdetail          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='vendorsdetail'");
    $zone                   =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='zone'");
    $zoneedit               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='zoneedit'");
    $zonelist               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='zonelist'");
    $procurement            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='procurement'");
    $procurementlist        =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='procurementlist'");
    $receiveform            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='receiveform'");
    $receivelist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='receivelist'");
    $cnotesales             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotesales'");
    $cnotesalesedit         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotesalesedit'");
    $cnotesaleslist         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotesaleslist'");
    $cnotesalesdelete         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotesalesdelete'");
    $cnoteallotment         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallotment'");
    $cnotereallotment       =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotereallotment'");
    $cnoteccreallotment     =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteccreallotment'");
    $cnoteallotmentlist     =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallotmentlist'");
    $cancellation           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cancellation'");
    $customerrate           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerrate'");
    $customerrate           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cancellation'");
    $cancellation           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cancellation'");
   
    $returncnote           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='returncnote'");
 
    $customerrate           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerrate'");
    $customerratelist       =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerratelist'");
    $customerratedetail     =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerratedetail'");
    $bainbound              =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bainbound'");
    $bainboundlist          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bainboundlist'");
    $duplicatechalllan      = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='duplicatechellan'");

    $duplicatechalllan1      = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='duplicatechellan1'");
    //echo json_encode($duplicatechalllan);

    $cash                   =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cash'");
    $cashlist               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cashlist'");
    $cashcnotedetail        =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cashcnotedetail'");
    $credit                 =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='credit'");
    $creditlist             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='creditlist'");
    $creditcnotedetail      =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='creditcnotedetail'");
    $invoice                =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='invoice'");
    $invoiceedit            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='invoiceedit'");
    $invoicelist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='invoicelist'");
    $invoicedetail          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='invoicedetail'");
    $invoicedelete          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='invoicedelete'");
    $manualinvoice                =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='manualinvoice'");
    $manualinvoiceedit            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='manualinvoiceedit'");
    $manualinvoicelist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='manualinvoicelist'");
    $manualinvoicedetail          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='manualinvoicedetail'");
    $manualinvoicedelete          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='manualinvoicedetail'");
    $accountspay          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='accountspay'");
    $collection             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collection'");
    $collectionlist         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collectionlist'");
    $collectiondelete         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collectiondelete'");
    $creditnote             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='creditnote'");
    $creditnotelist         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='creditnotelist'");
    $creditnotedelete         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='creditnotedelete'");
    $tscharge               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='tscharge'");
    $tschargelist           =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='tschargelist'");
    //Settings
    $addresstype            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='addresstype'");
    $addressedit            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='addressedit'");
    $addresslist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='addresslist'");
    $country                =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='country'");
    $countryedit            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='countryedit'");
    $countrylist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='countrylist'");
    $mode                   =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='mode'");
    $modeedit               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='modeedit'");
    $modelist               =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='modelist'");
    $peopletype             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peopletype'");
    $peopletypeedit         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peopletypeedit'");
    $peopletypelist         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peopletypelist'");
    $pincode                =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='pincode'");
    $pincodeedit            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='pincodeedit'");
    $pincodelist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='pincodelist'");
    $region                 =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='region'");
    $regionedit             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='regionedit'");
    $regionlist             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='regionlist'");
    $referencetype          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='referencetype'");
    $referenceedit          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='referenceedit'");
    $referencelist          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='referencelist'");
    $stationcode            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='stationcode'");
    $stationcodeedit        =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='stationcodeedit'");
    $stationcodelist        =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='stationcodelist'");
    $statustype             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='statustype'");
    $statusedit             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='statusedit'");
    $statuslist             =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='statuslist'");
    $state                  =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='state'");
    $stateedit              =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='stateedit'");
    $statelist              =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='statelist'");
    $tax                    =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='tax'");
    $taxedit                =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='taxedit'");
    $taxlist                =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='taxlist'");
    //users
    $usergroup              = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='usergroup'");
    $usergroupedit          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='usergroupedit'");
    $usergrouplist          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='usergrouplist'");
    $usergroupdetail        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='usergroupdetail'");
    $userform               = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='userform'");
    $useredit               = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='useredit'");
    $userlist               = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='userlist'");
    $cashimport             = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cashimport'");
    $cashimportlist         = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cashimportlist'");
    $creditimport           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='creditimport'");
    $creditimportlist       = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='creditimportlist'");
    //request
    $cnotesalesrequest      = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotesalesrequest'");
    $cnotesalesrequestlist  = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotesalesrequestlist'");
    $cnoteallotmentrequest  = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallotmentrequest'");
    $cnoteallotmentrequestlist = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallotmentrequestlist'");
    //delete access
    $branchdelete           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='branchdelete'");
    $bookingcounterdelete   = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bookingcounterdelete'");
    $companydelete          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='companydelete'");
    $customerdelete         = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerdelete'");
    $creditdelete           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='creditdelete'");
    $cashdelete             = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cashdelete'");
    $destinationdelete      = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='destinationdelete'");
    $groupdelete            = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='groupdelete'");
    $peopledelete           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peopledelete'");
    $vendordelete           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='vendordelete'");
    $zonedelete             = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='zonedelete'");
    $itemdelete             = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='itemdelete'");
    $addressdelete          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='addressdelete'");
    $countrydelete          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='countrydelete'");
    $modedelete             = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='modedelete'");
    $peopledelete           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='peopledelete'");
    $pincodedelete          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='pincodedelete'");
    $regiondelete           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='regiondelete'");
    $referencedelete        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='referencedelete'");
    $stationcodedelete      = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='stationcodedelete'");
    $statusdelete           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='statusdelete'");
    $statedelete            = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='statedelete'");
    $taxdelete              = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='taxdelete'");
    $usergroupdelete        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='usergroupdelete'");
    $customerratedelete     = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerratedelete'");
    $userdelete             = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='userdelete'");
    //for report
    $bookingreport          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bookingreport'");

    $demoreport          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='demoreport'");

    $customerreport         = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='customerreport'");
    $collectionreport       = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collectionreport'");
    $invoicereport          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='invoicereport'");
    $salesreport            = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='salesreport'");
    $salessummary            = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='salessummary'");
    $newbusinessreport            = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='newbusinessreport'");
    $cnotetracker           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotetracker'");
    $missingreport          = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='missingreport'");
    $inventory              = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='inventory'");
    $deviationreport        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='deviationreport'");
    $gstreport        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='gstreport'");
    $tonnagereport        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='tonnagereport'");
    //bainboundbatch
    $bainboundbatch         =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bainboundbatch'");
    $bainboundbatchlist     =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bainboundbatchlist'");
    $bainboundbatchdetail   =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bainboundbatchdetail'");
    $bainboundbatchdelete   =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='bainboundbatchdelete'");
    //external users
    $extcnotetracker           = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='extcnotetracker'");
    $extinvoicelist            =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='extinvoicelist'");
    $extinvoicedetail          =  $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='extinvoicedetail'");
    $extcustomerreport         = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='extcustomerreport'");
    $extcnotesalesrequestlist   = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='extcnotesalesrequestlist'");
    $cnotesalesapprove   = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnotesalesapprove'");
    $cnoteallotmentrequestint  = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallotmentrequestint'");
    $extcnoteallotmentrequestlist = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='extcnoteallotmentrequestlist'");
    $extgstreport = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='extgstreport'");
    $exbainboundreport = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='exbainboundreport'");
    $cnoteallotmentdelete = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallotmentdelete'");
    $totaloutstandingreport = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='totaloutstandingreport'");

    $collectioncenter        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collectioncenter'");
    $collectioncenteredit        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collectioncenteredit'");
    $collectioncenterlist        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collectioncenterlist'");
    $collectioncenterdetail        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collectioncenterdetail'");
    $collectioncenterdelete        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='collectioncenterdelete'");

    $cnoteallocationcc        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallocationcc'");
    $cnoteallocationlistcc        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallocationlistcc'");
    $cnoteallocationccdelete        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='cnoteallocationccdelete'");

    $denominationlist        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='denominationlist'");
    $denomination        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='denomination'");
    $denominationedit        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='denominationedit'");
    $denominationdelete        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='denominationdelete'");

    $denominationreport        = $db->query("SELECT module FROM users_group WHERE group_name='$group_name' AND module='denominationreport'");

}   
    
?>


  <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


  <div id="col-form1" class="col_form lngPage col-sm-25">
  <div class="au-breadcrumb m-t-75" style="margin-bottom: -5px;">
              <div class="section__content section__content--p30">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="au-breadcrumb-content">
                                  <div class="au-breadcrumb-left">
                                      <span class="au-breadcrumb-span"></span>
                                      <ul class="list-unstyled list-inline au-breadcrumb__list">
                                      <li class="list-inline-item active">
                                        <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                        </li>                                        
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'usergrouplist'; ?>">User Group list</a>
                                      </li>
                                      </ul>
                                  </div>
                                  
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div> 
          <div class="alert-primary" role="alert">

               <?php 
  
                    if ( isset($_GET['success']) && $_GET['success'] == 1 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  User Group Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  User Group Updated Successfully.
                                </div>";
                  }

                   ?>
                
             </div>
            <form method="POST" action="<?php echo __ROOT__ ?>usergroupedit" class="form-horizontal" name="formdestination" id="formvendors" >
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">User Group Form of <?=$_GET['grp_id']; ?></p>
                <div class="align-right" style="margin-top:-30px;">
                <!-- <button type="submit" class="btn btn-success btnattr_cmn" id="addangrp" name="addgrp">
                <i class="fa fa-save"></i>&nbsp; Save & Add
                </button> -->

                <button type="submit" class="btn btn-primary btnattr_cmn" id="addgrp" name="updategrp">
                <i class="fa  fa-save"></i>&nbsp; Update
                </button>
                
                <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                <i class="fa fa-eraser"></i>&nbsp; Reset
                </button>     

            <a style="color:white" href="<?php echo __ROOT__ . 'usergrouplist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
          
            </div> 
        <div class="content panel-body">


          <div class="tab_form">
          <input type="text" name="usergroup" id="usergroup" style="display:none" value="<?=$_GET['grp_id']; ?>">
          <input type="text" name="user_type" id="user_type" style="display:none" value="<?=$user_grouptype->user_type; ?>">

         
            <!-- <div id="permissions" class="tab-pane"> -->
            <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>

                     <tr>
                     <th class="font-weight-bold text-center" style="border:none">S.no</th>
                     <th class="font-weight-bold text-center" style="border:none">Menu Title</th>
                     <th class="font-weight-bold text-center" style="border:none">Create</th>
                     <th class="font-weight-bold text-center" style="border:none">Modify</th>
                     <th class="font-weight-bold text-center" style="border:none">List Access</th>
                     <th class="font-weight-bold text-center" style="border:none">Detail Access</th>
                     <th class="font-weight-bold text-center" style="border:none">Remove</th>
                     </tr>
                     <tr>
                        <th style="border:none" class="font-weight-bold text-center">Dashboard</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Dashboard</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($dashboard->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="dashboard,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>                    
                     </tr>
                     <tr>
                        <th style="border:none" class="font-weight-bold text-center">Masters</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Branch</td>
                     <td class="text-center"><input style='width:120px;height:25px;'  type="checkbox" <?php if($branch->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="branch,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;'  type="checkbox" <?php if($branchedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="branchedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;'  type="checkbox" <?php if($branchlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="branchlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;'  type="checkbox" <?php if($branchdetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="branchdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;'  type="checkbox" <?php if($branchdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="branchdelete,remove"></td>                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Booking Counter</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bookingcounter->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bookingcounter,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bookingcounteredit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bookingcounteredit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bookingcounterlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bookingcounterlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bookingcounterdetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bookingcounterdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bookingcounterdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bookingcounterdelete,remove"></td>                    
                     </tr>
                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Collection Center</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($collectioncenter->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="collectioncenter,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($collectioncenteredit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="collectioncenteredit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($collectioncenterlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="collectioncenterlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($collectioncenterdetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="collectioncenterdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($collectioncenterdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="collectioncenterdelete,remove"></td>                    
                     </tr>
                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Company</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($company->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="company,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($companyedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="companyedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($companylist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="companylist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($companydetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="companydetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($companydelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="companydelete,remove"></td>                    
                     </tr>

                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Customer</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($customer->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customer,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($customeredit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customeredit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($customerlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($customerdetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($customerdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerdelete,remove"></td>                     
                     </tr>
                    <!-- 
                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Customer Setting</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" disabled <?php// if($customer->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customer,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php// if($customersetting->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customersetting,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" disabled <?php// if($customerlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" disabled <?php// if($customerdetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" disabled <?php// if($customerdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerdelete,remove"></td>                     
                     </tr> -->

                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Destination Zone</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($destination->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="destination,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($destinationedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="destinationedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($destinationlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="destinationlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="destinationdetail,detail_access" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($destinationdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="destinationdelete,remove"></td>                    
                     </tr>

                     <tr>
                     <td class="text-center">6</td>
                     <td class="text-center">Group</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($group->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="group,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($groupedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="groupedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($grouplist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="grouplist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="groupdetail,detail_access" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($groupdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="groupdelete,remove"></td>                   
                     </tr>

                     <tr>
                     <td class="text-center">7</td>
                     <td class="text-center">People</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($peopleadd->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="peopleadd,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($peopleedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="peopleedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($peoplelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="peoplelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($peopledetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="peopledetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($peopledelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="peopledelete,remove"></td>                     
                     </tr>

                     <tr>
                     <td class="text-center">8</td>
                     <td class="text-center">Vendor</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($vendors->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="vendors,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($vendorsedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="vendorsedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($vendorslist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="vendorslist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($vendorsdetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="vendorsdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($vendordelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="vendordelete,remove"></td>                     
                     </tr>

                     <tr>
                     <td class="text-center">9</td>
                     <td class="text-center">Zone</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($zone->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="zone,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($zoneedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="zoneedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($zonelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="zonelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="zonedetail,detail_access" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($zonedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="zonedelete,remove"></td>                     
                     </tr>                   

                     <tr>
                     <td class="text-center">10</td>
                     <td class="text-center">Item</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($itemtype->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="itemtype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($itemedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="itemedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($itemlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="itemlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="itemdetail,detail_access" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($itemdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="itemdelete,remove"></td>                     
                     </tr>

                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Cnote</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Procurement</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($procurement->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="procurement,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($procurementlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="procurementlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="procurementdelete,remove" disabled></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Receive</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($receiveform->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="receiveform,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($receivelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="receivelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="receivedelete,remove" disabled></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Sales</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnotesales->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnotesales,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($cnotesalesedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="cnotesalesedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnotesaleslist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnotesaleslist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesdelete,remove" <?php if($cnotesalesdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]"></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Sales Request</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnotesalesapprove->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnotesalesapprove,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnotesalesrequestlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnotesalesrequestlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesdelete,remove" disabled></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Allotment</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallotment->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnoteallotment,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallotmentlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnoteallotmentlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallotmentdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cnoteallotmentdelete,remove"></td>
                     </tr>
                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Allocation CC</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallocationcc->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cnoteallocationcc,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallocationlistcc->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cnoteallocationlistcc,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallocationccdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cnoteallocationccdelete,remove"></td>

                     </tr>
                     <tr>
                     <td class="text-center">6</td>
                     <td class="text-center">Br Re Allotment</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnotereallotment->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnotereallotment,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     </tr>

                     <tr>
                     <td class="text-center">7</td>
                     <td class="text-center">CC Re Allotment</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteccreallotment->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnoteccreallotment,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     </tr>
                     <!-- <tr>
                     <td class="text-center">6</td>
                     <td class="text-center">Allotment Request</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallotmentrequestint->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnoteallotmentrequestint,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallotmentrequestlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnoteallotmentrequestlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentdelete,remove" disabled></td>
                     
                     </tr> -->

                     <tr>
                     <td class="text-center">8</td>
                     <td class="text-center">Cancel</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cancellation->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cancellation,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                    
                     </tr>
                     <tr>
                     <td class="text-center">9</td>
                     <td class="text-center">Return</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($returncnote->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="returncnote,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                    
                     </tr>
                     <tr>
                     <td class="text-center">10</td>
                     <td class="text-center">Inventory</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="cancellation,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($inventory->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="inventory,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                    
                     </tr>

                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Rate Master</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Customer Rate</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($customerrate->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerrate,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($customerratelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerratelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($customerratedetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="customerratedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($customerratedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="customerratedelete,remove"></td>
                    
                     </tr>
                   
                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Billing</th>
                     </tr>
<!--                     
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">BA Inbound </td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bainbound->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bainbound,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bainboundlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bainboundlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="bainboundremove,remove"></td>
                    
                     </tr> -->
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">BA Inbound</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bainboundbatch->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bainboundbatch,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($bainboundbatchlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bainboundbatchlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($bainboundbatchdetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bainboundbatchdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($bainboundbatchdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="bainboundbatchdelete,remove"></td>
                    
                     </tr>
                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Cash Entry</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cash->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cash,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cashlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cashlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($cashcnotedetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="cashcnotedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($cashdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="cashdelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Credit Entry</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($credit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="credit,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($creditlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="creditlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($creditcnotedetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="creditcnotedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($creditdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="creditdelete,remove"></td>
                    
                     </tr>                           
                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Cnote Tracking</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="credit,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnotetracker->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cnotetracker,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditdelete,remove"></td>
                    
                     </tr>  
                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Duplicate  Challan</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($duplicatechalllan->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="duplicatechellan,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if( $duplicatechalllan->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="duplicatechellan,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="duplicatechellan,remove"></td>
                    
                     </tr>

                     <!-- <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Duplicate  Challan1</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($duplicatechalllan1->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="duplicatechellan1,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if( $duplicatechalllan1->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="duplicatechellan1,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="duplicatechellan1,remove"></td>
                    
                     </tr> -->
                     
                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Utilities</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Cash Bulk Upload</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cashimport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cashimport,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cashimportlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="cashimportlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cashimportremove,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Credit Bulk Upload</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($creditimport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="creditimport,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($creditimportlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="creditimportlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditimportlistdelete,remove"></td>
                    
                     </tr>

                     <!-- <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Cash Bulk Upload</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php //if($cashbulkupload->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="cashbulkupload,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php //if($cashbulkuploadlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="cashbulkuploadlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value=",detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cashbulkuploaddelete,remove"></td>
                    
                     </tr>              
                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Credit Bulk Upload</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php //if($creditbulkupload->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="creditbulkupload,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php //if($creditbulkuploadlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="creditbulkuploadlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value=",detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="creditbulkuploaddelete,remove"></td>
                    
                     </tr>   -->

                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Accounts</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Invoice</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($invoice->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="invoice,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($invoiceedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="invoiceedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($invoicelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="invoicelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($invoicedetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="invoicedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($invoicedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="invoicedelete,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Manual Invoice</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($manualinvoice->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="manualinvoice,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($manualinvoiceedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="manualinvoiceedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($manualinvoicelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="manualinvoicelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($manualinvoicedetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="manualinvoicedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($manualinvoicedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="manualinvoicedelete,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Accounts Pay</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($accountspay->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="accountspay,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if( $accountspay->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="accountspay,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="accountspay,remove"></td>
                    
                     </tr>
                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Collection</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($collection->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="collection,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($collectionlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="collectionlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($collectiondelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="collectiondelete,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Denomination</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($denomination->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="denomination,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($denominationedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="denominationedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($denominationlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="denominationlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($denominationdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="denominationdelete,remove"></td>
                    
                     </tr> 
                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">Credit Note</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($creditnote->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="creditnote,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($creditnotelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="creditnotelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($creditnotedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="creditnotedelete,remove"></td>
                    
                     </tr> 

                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">TS Charge</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($tscharge->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="tscharge,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($tschargelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="tschargelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="tschargedelete,remove"></td>
                    
                     </tr> 

                     <tr>
                         
                         <th style="border:none" class="font-weight-bold text-center">Reports</th>
                         </tr>

                         <tr>
                         <td class="text-center">1</td>
                         <td class="text-center">Booking</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($bookingreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="bookingreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoiceedit,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedetail,detail_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>
                         </tr>

                         <tr>
                         <td class="text-center">1</td>
                         <td class="text-center">Demoreport</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" 
                         <?php if($demoreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="demoreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoiceedit,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedetail,detail_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>
                         </tr>

                         <tr>
                         <td class="text-center">2</td>
                         <td class="text-center">Customer</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($customerreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="customerreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoiceedit,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedetail,detail_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>
                        
                         </tr> 
    
                         <tr>
                         <td class="text-center">3</td>
                         <td class="text-center">Collection</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($collectionreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="collectionreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="collectionlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="collectiondelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">3</td>
                         <td class="text-center">Denomination</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($denominationreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="denominationreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                        
                         </tr> 
    
                         <tr>
                         <td class="text-center">4</td>
                         <td class="text-center">Invoice</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($invoicereport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="invoicereport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditnotelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditnotedelete,remove"></td>
                        
                         </tr> 
    
                         <tr>
                         <td class="text-center">5</td>
                         <td class="text-center">Sales</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($salesreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="salesreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">6</td>
                         <td class="text-center">Sales Summary</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($salessummary->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="salessummary,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 

                         <tr>
                         <td class="text-center">7</td>
                         <td class="text-center">New Business</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($newbusinessreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="newbusinessreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 

                         <tr>
                         <td class="text-center">8</td>
                         <td class="text-center">Missing</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($missingreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="missingreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">9</td>
                         <td class="text-center">Deviation</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($deviationreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="deviationreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="tschargedelete,remove"></td>
                        
                         </tr> 
                         <tr>
                         <td class="text-center">10</td>
                         <td class="text-center">GST</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($gstreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="gstreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportdelete,remove"></td>
                        
                         </tr>         
                         <tr>
                         <td class="text-center">11</td>
                         <td class="text-center">Tonnage</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($tonnagereport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="tonnagereport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportdelete,remove"></td>
                        
                         </tr>  
                         <tr>
                         <td class="text-center">12</td>
                         <td class="text-center">Total Station Outstanding</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($totaloutstandingreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="totaloutstandingreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="gstreportdelete,remove"></td>
                        
                         </tr>       

                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Users</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Users</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($userform->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="userform,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($useredit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="useredit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($userlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="userlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($userdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="userdelete,remove"></td>
                   
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">User Groups</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($usergroup->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="usergroup,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($usergroupedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="usergroupedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($usergrouplist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="usergrouplist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($usergroupdetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="usergroupdetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($usergroupdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="usergroupdelete,remove"></td>
                   
                     </tr>
                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">Settings</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">Address</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($addresstype->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="addresstype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($addressedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="addressedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($addresslist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="addresslist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($addressdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="addressdelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">Country</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($country->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="country,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($countryedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="countryedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($countrylist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="countrylist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($countrydelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="countrydelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">Mode</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($mode->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="mode,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($modeedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="modeedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($modelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="modelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($modedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="modedelete,remove"></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">4</td>
                     <td class="text-center">People Type</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($peopletype->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="peopletype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($peopletypeedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="peopletypeedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($peopletypelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="peopletypelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($peopledelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="peopledelete,remove"></td>
                     
                     </tr>

                     <tr>
                     <td class="text-center">5</td>
                     <td class="text-center">Pincode</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($pincode->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="pincode,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($pincodeedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="pincodeedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($pincodelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="pincodelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($pincodedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="pincodedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">6</td>
                     <td class="text-center">Region</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($region->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="region,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($regionedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="regionedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($regionlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="regionlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($regiondelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="regiondelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">7</td>
                     <td class="text-center">Reference</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($referencetype->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="referencetype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($referenceedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="referenceedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($referencelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="referencelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($referencedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="referencedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">8</td>
                     <td class="text-center">Station</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($stationcode->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="stationcode,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($stationcodeedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="stationcodeedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($stationcodelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="stationcodelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($stationcodedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="stationcodedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">9</td>
                     <td class="text-center">Status</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($statustype->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="statustype,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($statusedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="statusedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($statuslist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="statuslist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($statusdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="statusdelete,remove"></td>
                   
                     </tr>

                     <tr>
                     <td class="text-center">10</td>
                     <td class="text-center">State</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($state->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="state,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($stateedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="stateedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($statelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="statelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($statedelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="statedelete,remove"></td>
                    
                     </tr>

                     <tr>
                     <td class="text-center">11</td>
                     <td class="text-center">Tax Slab</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($tax->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="tax,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($taxedit->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="taxedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($taxlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="taxlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($taxdelete->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="taxdelete,remove"></td>
                    
                     </tr>
                     <tr>
                     <th style="border:none" class="font-weight-bold text-center">External Users</th>
                     </tr>
                     <tr>
                     <td class="text-center">1</td>
                     <td class="text-center">EU-Sales Request</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnotesalesrequest->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnotesalesrequest,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($extcnotesalesrequestlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="extcnotesalesrequestlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnotesalesdelete,remove" disabled></td>
                     
                     </tr>
                     <!-- <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">EU-Allotment Request</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($cnoteallotmentrequest->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="cnoteallotmentrequest,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($extcnoteallotmentrequestlist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?>  name="module[]" value="extcnoteallotmentrequestlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" value="cnoteallotmentdelete,remove" disabled></td>
                     
                     </tr> -->
                     <tr>
                     <td class="text-center">2</td>
                     <td class="text-center">EU-Cnote Tracking</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="credit,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditlist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($extcnotetracker->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="extcnotetracker,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="creditdelete,remove"></td>
                    
                     </tr>  
                     <tr>
                     <td class="text-center">3</td>
                     <td class="text-center">EU-Invoice</td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]"  disabled value="invoice,p_create"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoiceedit,p_modify"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($extinvoicelist->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="extinvoicelist,list_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($extinvoicedetail->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="extinvoicedetail,detail_access"></td>
                     <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>
                    
                     </tr> 
                     <tr>
                         <td class="text-center">4</td>
                         <td class="text-center">EU-Customer Report</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($extcustomerreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="extcustomerreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoiceedit,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedetail,detail_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>
                    </tr>
                     <tr>
                         <td class="text-center">4</td>
                         <td class="text-center">EU-GST Report</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($extgstreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="extgstreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoiceedit,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicelist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedetail,detail_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="invoicedelete,remove"></td>
                    </tr>
                    <tr>
                         <td class="text-center">4</td>
                         <td class="text-center">EU-BA-Inbound Report</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($exbainboundreport->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="exbainboundreport,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="exbainboundreportdit,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="exbainboundreportlist,list_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="exbainboundreportdetail,detail_access"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" disabled value="exbainboundreportdelete,remove"></td>
                    </tr>
                    <tr>
                     <th style="border:none" class="font-weight-bold text-center">New module</th>
                     </tr>
                     <tr>
                     <td class="text-center">4</td>
                         <td class="text-center">new module</td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($newmodule->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="newmodule,p_create"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($newmodule1->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="newmodule1,p_modify"></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[]" <?php if($newmodule1->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> value="newmodule1,list_access">
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" name="module[] "value="newmodule3,detail_access" disabled></td>
                         <td class="text-center"><input style='width:120px;height:25px;' type="checkbox" <?php if($newmodule4->rowCount()==0){ echo ''; }else{ echo 'Checked'; } ?> name="module[]" value="newmodule4,remove" ></td>
                     </tr>
                     <tr>
             </tbody>         

           </table>
                <!-- </div> -->
                    
          
        </form>
  <!-- Fetching Mode Details in Table  -->
          
            <!-- </div> -->
            </div>
            </div>
            </div>
  </section>

<!-- inline scripts related to this page -->
<script>


$(window).on('load', function () {
   var usr_name = $('#user_name').val();
        
        $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{usr_name:usr_name},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#stncode').val(data[0]);
            $('#stnname').val(data[1]);

            var station = $('#stncode').val();
         
            $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{station:station},
            dataType:"text",
            success:function(data)
            {
            $('#vendor_code').val(data);
            }
            });

            if($('#stncode').val() == '')
            {
                // $('#companysection').css('display','block');
                var superadmin_stn = $('#stncode1').val();
                //var superadmin_stn = 'DAA';
                $.ajax({
                url:"<?php echo __ROOT__ ?>peoplecontroller",
                method:"POST",
                data:{superadmin_stn:superadmin_stn},
                dataType:"text",
                success:function(value)
                {
                    var data = value.split(",");
                    $('#stncode').val(data[0]);
                    $('#stnname').val(data[1]);
                }
                });
            }
            }
         });
       
  });

</script>