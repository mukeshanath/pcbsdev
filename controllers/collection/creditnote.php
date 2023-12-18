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


      class creditnote extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - creditnote';
              $this->view->render('collection/creditnote');
          }
      }

      if(isset($_POST['addcreditnote'])){
          addcreditnote($db);
      }

    function addcreditnote($db){

        date_default_timezone_set('Asia/Kolkata');

        $credit_note_no     = $_POST['credit_note_no'];
        $credit_note_date   = $_POST['credit_note_date'];
        $invno              = $_POST['invno'];
        $invdate            = $_POST['invdate'];
        $br_code            = $_POST['br_code'];
        $br_name            = $_POST['br_name'];
        $cust_code          = $_POST['cust_code'];
        $cust_name          = $_POST['cust_name'];
        $comp_code          = $_POST['comp_code'];
        $comp_name          = $_POST['comp_name'];
        $invamt             = (double)str_replace(',','',($_POST['invamt'])); 
        $remarks            = $_POST['remarks'];
        $balamt            = (double)str_replace(',','',($_POST['balamt']));  
        $totamt             = (double)str_replace(',','',($_POST['totamt'])); 
        $user_name          = $_POST['user_name'];    
        $date_added         = date('Y-m-d H:i:s');  

        $col_mas_rcptno = colmasno($db,$comp_code);
        $colno = collectionno($db,$comp_code);
        //adding collection 
        $balanceamt = (double)str_replace(',','',($balamt)) - (double)str_replace(',','',($totamt));
        if($balanceamt==0){
            $invstatus = 'Collected';
            $collstatus = 'Collected';
        }else{
            $invstatus = 'Pending';
            $collstatus = 'Pending';
        }
        if(!empty($run_bal)){
            $run_bal2 = (double)str_replace(',','',($run_bal2));
        }
        else{
            $run_bal2 = (double)str_replace(',','',($totamt));
        }
        if(empty($totamt)){
            $running_bal = '';
        }
        else{ 
        $running_bal = (double)str_replace(',','',($run_bal2))-(double)str_replace(',','',($totamt));
        }

        $netcoll = (double)str_replace(',','',($totamt)); 
        $getpendingamt = $db->query("SELECT (netamt-rcvdamt) as balamt from invoice WHERE comp_code='$comp_code' AND invno='$invno' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);
        if((int)$netcoll>(int)$getpendingamt->balamt)
        {
            header("Location:creditnote?unsuccess=invalidamt");
        }
        else{
        $insertcollection = "INSERT INTO collection (comp_code,comp_name,br_code,br_name,cust_code,cust_name,colno,invno,invamt,balamt,chqddno,reduction,cmode,rcptno,col_mas_rcptno,coldate,edate,invdate,status,user_name,date_added,netcollamt,remarks)
        VALUES ('$comp_code','$comp_name','$br_code','$br_name','$cust_code','$cust_name','$colno','$invno','$invamt','$balanceamt','$credit_note_no','$totamt','CreditNote','$credit_note_no','$col_mas_rcptno','$credit_note_date','$date_added','$invdate','$collstatus','$user_name','$date_added','$netcoll','$remarks')";
        $db->query($insertcollection);
        $rcvdamt = ($invamt-$balamt)+($totamt);

        $db->query("UPDATE invoice SET rcvdamt=rcvdamt+$netcoll,lastcoldate='$date_added',status='$invstatus' WHERE invno='$invno' AND comp_code='$comp_code'");
        
        $insertcollectionmaster = "INSERT INTO collection_master (col_mas_rcptno,cust_code,rcptno,coldate,totamt,running_balance,invno,chqddno,postamt,comp_code,status,user_name,date_added)
        VALUES ('$col_mas_rcptno','$cust_code','$credit_note_no','$credit_note_date','$totamt','$running_bal','$invno','$credit_note_no','$totamt','$comp_code','$collstatus','$user_name','$date_added')";
        $db->query($insertcollectionmaster);
        //adding collection
        //insert credit note
        $db->query("INSERT INTO credit_note (col_no,crn_no,crn_date,invno,invdate,cust_code,br_code,comp_code,grand_amt,crn_reason,net_amt,user_name,date_added)
         VALUES ('$colno','$credit_note_no','$credit_note_date','$invno','$invdate','$cust_code','$br_code','$comp_code','$invamt','$remarks','$totamt','$user_name','$date_added')");
    
        //update collection
        $db->query("UPDATE collection SET crnno='$credit_note_no' WHERE invno='$invno'");

        header('Location:creditnotelist?success=1');
        }
    }
    
    function colmasno($db,$stationcode)
    {
    
    $prevcolmasno=$db->query("SELECT TOP 1 col_mas_rcptno FROM collection_master WHERE comp_code='$stationcode' ORDER BY col_mas_id DESC");
    if($prevcolmasno->rowCount()== 0)
        {
        $colomasno = $stationcode.str_pad('1', 7, "0", STR_PAD_LEFT);
        }
    else{
        $prevcolmasrow=$prevcolmasno->fetch(PDO::FETCH_ASSOC);
        $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$prevcolmasrow['col_mas_rcptno']);
        $arr[0];//PO
        $arr[1]+1;//numbers
        $colomasno = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
        }
     return $colomasno;    
        
    }

    function collectionno($db,$stationcode)
    {
        $prevcolno=$db->query("SELECT TOP 1 colno FROM collection WHERE comp_code='$stationcode' ORDER BY col_id DESC");
         if($prevcolno->rowCount()== 0){
            $colono = 10001;
            }
        else{
            $prevcolrow=$prevcolno->fetch(PDO::FETCH_ASSOC);
            $colono = $prevcolrow['colno']+1;
            }
        return $colono;        
    }


