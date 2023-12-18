<?php 
/* File name   : tax.php
 Begin       : 2020-03-04
 Description : class tax file saves master tax slab in appliaction
               Default Header and Footer

 Author: zabiullah

 (c) Copyright:
              codim solutions
============================================================+*/

/**
 * render the tax in to class 
 * get database string to establish connection from sql server
 * Capture the tax data and save ion to database
 * @author zabi  
 * @since 2020-03-04
 */

/* Include the main tax master (search for installation path). */

defined('__ROOT__') OR exit('No direct script access allowed');

       include 'db.php'; 


      class collection extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - collection';
              $this->view->render('collection/collection');
          }
      }

      if(isset($_POST['addcollection']))
      {
          addcollection($db);
      }
      
      function addcollection($db)
      {
          date_default_timezone_set('Asia/Kolkata');

          $comp_code                = $_POST['comp_code'];
          $comp_name                = $_POST['comp_name'];
          $br_code                  = $_POST['br_code'];
          $br_name                  = $_POST['br_name'];
          $cust_code                = $_POST['cust_code'];
          $cust_name                = $_POST['cust_name'];
          $coldate                  = $_POST['coldate'];
          $rcptno                   = $_POST['rcptno'];
          $totamt                   = $_POST['totamt'];
          $run_bal                  = $_POST['run_bal'];
          $chdt                     = $_POST['chdt'];
          $chqbank                  = $_POST['chqbank'];
          $depbank                  = $_POST['depbank'];
          $invno                    = $_POST['invno'];
          $invamt                   = (double)str_replace(',','',($_POST['invamt']));
          $balamt                   = $_POST['balamt'];
          $invdate                  = $_POST['invdate'];
          $chqddno                  = $_POST['chqddno'];   
          $sheetno                  = $_POST['sheetno'];
          $cmode                    = $_POST['cmode'];
          $remarks                  = $_POST['remarks'];
          $narration                = $_POST['narration'];
          $col_mas_rcptno           = $_POST['col_mas_rcptno'];
          $colno                    = $_POST['col_no'];
          $colamt                   = (double)str_replace(',','',($_POST['colamt']));
          $tds                      = (double)str_replace(',','',($_POST['tds']));
          $reduction                = (double)str_replace(',','',($_POST['reduction']));
          $wrongbill                = $_POST['wrongbill'];
          $user_name                = $_POST['user_name'];
          $date_added               = date('Y-m-d H:i:s');
          
      
          if(!empty($br_code) && !empty($cust_code)) 
          {
            $balanceamt = (double)str_replace(',','',$balamt) - (double)str_replace(',','',($colamt+$tds+$reduction));
            if($balanceamt==0){
                $invstatus = 'Collected';
                $collstatus = 'Collected';
            }else{
                $invstatus = 'Pending';
                $collstatus = 'Pending';
            }
            if(!empty($run_bal)){
                $run_bal2 = (double)str_replace(',','',($run_bal)); 
            }
            else{
                $run_bal2 = (double)str_replace(',','',($totamt));   
            } 
            $running_bal = (double)str_replace(',','',($run_bal2))-(double)str_replace(',','',($colamt));
            
            $netcoll = (double)str_replace(',','',($colamt+$tds+$reduction));

            $getpendingamt = $db->query("SELECT (netamt-rcvdamt) as balamt from invoice WHERE comp_code='$comp_code' AND invno='$invno' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);
            if((int)$netcoll>(int)$getpendingamt->balamt)
            {
                header("Location:collection?unsuccess=invalidamt");
            }
            else{
            $insertcollection = "INSERT INTO collection (comp_code,comp_name,br_code,br_name,cust_code,cust_name,colno,invno,invamt,balamt,chqddno,colamt,tds,reduction,narration,cmode,rcptno,col_mas_rcptno,sheetno,wrongbill,coldate,edate,invdate,status,user_name,date_added,netcollamt,remarks)
            VALUES ('$comp_code','$comp_name','$br_code','$br_name','$cust_code','$cust_name','$colno','$invno','$invamt','$balanceamt','$chqddno','$colamt','$tds','$reduction','$narration','$cmode','$rcptno','$col_mas_rcptno','$sheetno','$wrongbill','$coldate','$date_added','$invdate','$collstatus','$user_name','$date_added','$netcoll','$remarks')";
            $db->query($insertcollection);  
            $rcvdamt = (double)str_replace(',','',($invamt-$balamt))+(double)str_replace(',','',($colamt+$tds+$reduction));
            $db->query("UPDATE invoice SET rcvdamt=rcvdamt+$netcoll,lastcoldate='$date_added',status='$invstatus' WHERE invno='$invno' AND comp_code='$comp_code'");
            
            $insertcollectionmaster = "INSERT INTO collection_master (col_mas_rcptno,cust_code,rcptno,coldate,totamt,running_balance,chdt,invno,chqddno,chqbank,depbank,postamt,comp_code,status,user_name,date_added)
            VALUES ('$col_mas_rcptno','$cust_code','$rcptno','$coldate','$totamt','$running_bal','$chdt','$invno','$chqddno','$chqbank','$depbank','$colamt','$comp_code','$collstatus','$user_name','$date_added')";
            $db->query($insertcollectionmaster);

            header("Location:collectionlist");
            }
          }
          else{
                $emptyfields = '';
                if(empty($br_code)){ $emptyfields .='branch code'; }
                if(empty($cust_code)){ $emptyfields .='customer code'; }
                header("Location:collection?unsuccess=$emptyfields");
          }
      }


      if(isset($_POST['addancollection']))
      {
        addancollection($db);
      }

      function addancollection($db)
      {
          date_default_timezone_set('Asia/Kolkata');

          $comp_code                = $_POST['comp_code'];
          $comp_name                = $_POST['comp_name'];
          $br_code                  = $_POST['br_code'];
          $br_name                  = $_POST['br_name'];
          $cust_code                = $_POST['cust_code'];
          $cust_name                = $_POST['cust_name'];
          $coldate                  = $_POST['coldate'];
          $rcptno                   = $_POST['rcptno'];
          $totamt                   = $_POST['totamt'];
          $run_bal                  = $_POST['run_bal'];
          $chdt                     = $_POST['chdt'];
          $chqbank                  = $_POST['chqbank'];
          $depbank                  = $_POST['depbank'];
          $invno                    = strtoupper($_POST['invno']);
          $invamt                   = (double)str_replace(',','',($_POST['invamt']));
          $balamt                   = $_POST['balamt'];
          $invdate                  = $_POST['invdate'];
          $chqddno                  = $_POST['chqddno'];
          $sheetno                  = $_POST['sheetno'];
          $cmode                    = $_POST['cmode'];
          $remarks                  = $_POST['remarks'];
          $narration                = $_POST['narration'];
          $col_mas_rcptno           = $_POST['col_mas_rcptno'];
          $colno                    = $_POST['col_no'];
          $colamt                   = (double)str_replace(',','',($_POST['colamt']));
          $tds                      = (double)str_replace(',','',($_POST['tds']));
          $reduction                = (double)str_replace(',','',($_POST['reduction']));
          $wrongbill                = $_POST['wrongbill'];
          $user_name                = $_POST['user_name'];
          $date_added               = date('Y-m-d H:i:s');
          
         
          if(!empty($br_code) && !empty($cust_code))
          {
            $balanceamt = (double)str_replace(',','',$balamt) - (double)str_replace(',','',($colamt+$tds+$reduction));
                if($balanceamt==0){
                    $invstatus = 'Collected';
                    $collstatus = 'Collected';
                }else{
                    $invstatus = 'Pending';
                    $collstatus = 'Pending';
                }
                if(!empty($run_bal)){
                    $run_bal2 = (double)str_replace(',','',($run_bal)); 
                }
                else{
                    $run_bal2 = (double)str_replace(',','',($totamt));   
                } 
                if(empty($totamt)){
                    $running_bal = '';
                }
                else{
                $running_bal = (double)str_replace(',','',($run_bal2))-(double)str_replace(',','',($colamt));
                }

                $netcoll = (double)str_replace(',','',($colamt+$tds+$reduction));
                $getpendingamt = $db->query("SELECT (netamt-rcvdamt) as balamt from invoice WHERE comp_code='$comp_code' AND invno='$invno' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);
                if((int)$netcoll>(int)$getpendingamt->balamt)
                {
                    header("Location:collection?unsuccess=invalidamt");
                }
                else{
                $insertcollection = "INSERT INTO collection (comp_code,comp_name,br_code,br_name,cust_code,cust_name,colno,invno,invamt,balamt,chqddno,colamt,tds,reduction,narration,cmode,rcptno,col_mas_rcptno,sheetno,wrongbill,coldate,edate,invdate,status,user_name,date_added,netcollamt,remarks)
                VALUES ('$comp_code','$comp_name','$br_code','$br_name','$cust_code','$cust_name','$colno','$invno','$invamt','$balanceamt','$chqddno','$colamt','$tds','$reduction','$narration','$cmode','$rcptno','$col_mas_rcptno','$sheetno','$wrongbill','$coldate','$date_added','$invdate','$collstatus','$user_name','$date_added','$netcoll','$remarks')";
                $db->query($insertcollection);
                $rcvdamt = (double)str_replace(',','',($invamt-$balamt))+(double)str_replace(',','',($colamt+$tds+$reduction));
                $db->query("UPDATE invoice SET rcvdamt=rcvdamt+$netcoll,lastcoldate='$date_added',status='$invstatus' WHERE invno='$invno' AND comp_code='$comp_code'");
                
                $insertcollectionmaster = "INSERT INTO collection_master (col_mas_rcptno,cust_code,rcptno,coldate,totamt,running_balance,chdt,invno,chqddno,chqbank,depbank,postamt,comp_code,status,user_name,date_added)
                VALUES ('$col_mas_rcptno','$cust_code','$rcptno','$coldate','$totamt','$running_bal','$chdt','$invno','$chqddno','$chqbank','$depbank','$colamt','$comp_code','$collstatus','$user_name','$date_added')";
                $db->query($insertcollectionmaster);

                header("Location:collection?invno=$invno");
                }
          }
          else{
            $emptyfields = '';
            if(empty($br_code)){ $emptyfields .='Branch code, '; }
            if(empty($cust_code)){ $emptyfields .='Customer code, '; }
            header("Location:collection?unsuccess=$emptyfields");
          }
      }

      if(isset($_POST['updateholdcolln']))
      {
        updateholdcolln($db);
      }

      function updateholdcolln($db)
      {
          date_default_timezone_set('Asia/Kolkata');

          $comp_code                = $_POST['comp_code'];
          $comp_name                = $_POST['comp_name'];
          $br_code                  = $_POST['br_code'];
          $br_name                  = $_POST['br_name'];
          $cust_code                = $_POST['cust_code'];
          $cust_name                = $_POST['cust_name'];
          $coldate                  = $_POST['coldate'];
          $rcptno                   = $_POST['rcptno'];
          $totamt                   = $_POST['totamt'];
          $run_bal                  = $_POST['run_bal'];
          $chdt                     = $_POST['chdt'];
          $chqbank                  = $_POST['chqbank'];
          $depbank                  = $_POST['depbank'];
          $invno                    = strtoupper($_POST['invno']);
          $invamt                   = (double)str_replace(',','',($_POST['invamt']));
          $balamt                   = $_POST['balamt'];
          $invdate                  = $_POST['invdate'];
          $chqddno                  = $_POST['chqddno'];
          $sheetno                  = $_POST['sheetno'];
          $cmode                    = $_POST['cmode'];
          $remarks                  = $_POST['remarks'];
          $extremarks               = $_POST['extremarks'];
          $narration                = $_POST['narration'];
          $col_mas_rcptno           = $_POST['col_mas_rcptno'];
          $colno                    = $_POST['col_no'];
          $colamt                   = (double)str_replace(',','',($_POST['colamt']));
          $tds                      = (double)str_replace(',','',($_POST['tds']));
          $reduction                = (double)str_replace(',','',($_POST['reduction']));
          $wrongbill                = $_POST['wrongbill'];
          $user_name                = $_POST['user_name'];
          $date_added               = date('Y-m-d H:i:s');
          
         $db->query("UPDATE collection SET cmode='$cmode',remarks='$extremarks',colamt='$colamt',tds='$tds',reduction='$reduction',status='Pending' WHERE comp_code='$comp_code' AND colno='$colno'");
         $db->query("UPDATE collection_master SET status='Pending' WHERE comp_code='$comp_code' AND col_mas_rcptno='$col_mas_rcptno'");
         header("Location:collectionlist");

      }