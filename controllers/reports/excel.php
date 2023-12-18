<style>
    table tr th, table tr td{
        border:.5px solid black;
    }
</style>
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


      class excel extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - excel';
              //$this->view->render('reports/bookingreport');
          }
      }


require 'db.php';
// if(isset($_POST['filename'])){
//      $filename = $_POST['filename'];
// }
// else{
//      $
// }

date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/xls");    
// header("Content-Disposition: attachment; filename=".$filename.".xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

error_reporting(0);
      function getDatesFromRange($start, $end){
          $dates = array($start);
          while(end($dates) < $end){
              $dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
          }
          return $dates;
      }

      date_default_timezone_set('Asia/Kolkata');
      $months = array("index","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

     
      //generate report for booking reportcontroller
      if(isset($_POST['book_date_from'])){
          header("Content-Disposition: attachment; filename=booking-report.xls");  
          $book_date_from = $_POST['book_date_from'];
          $book_date_to = $_POST['book_date_to'];
          $weight_from = $_POST['weight_from'];
          $weight_to = $_POST['weight_to'];
          $book_destination = $_POST['destination'];
          $book_zone = $_POST['zone'];
          $book_rep_type = $_POST['book_rep_type'];
          $bookstn = $_POST['bookstn'];

          $cust_type_condition = '';
              if($book_rep_type=='bookcredit'){
               $table = 'credit';
               $columnramt = "";
              }
              if($book_rep_type=='bookinbound'){
               $table = 'bainbound';
               $columnramt = "";
              }
              if($book_rep_type=='bookcash'){
               $table = 'cash';
               $cust_type_condition = '';
               $columnramt = "";
              }
              
               echo "<table><thead>
               <tr>
                    <th style='text-align:left'>S.no</th>
                    <th style='text-align:left'>Branch</th>";
                    if($book_rep_type=='bookcredit' || $book_rep_type=='bookinbound'){
                    echo "<th style='text-align:left'>Customer</th>";
                    }
                    echo "<th style='text-align:left'>Cno</th>
                    <th style='text-align:left'>Weight</th>
                    <th style='text-align:left'>Mode</th>
                    <th style='text-align:left'>Origin</th>
                    <th style='text-align:left'>Destination</th>
                    <th style='text-align:left'>Booking Date</th>
                    <th style='text-align:right'>Amount</th>";
                    if($book_rep_type=='bookcash'){
                       echo "<th style='text-align:right'>Rcvd Amount</th>";
                    }
               echo "</tr>
               </thead>";
      
          //gen report
          $book_conditions = array();
          if(!empty($book_date_from)){
               $book_conditions[] = "tdate BETWEEN '$book_date_from' AND '$book_date_to'";
          }
          if(!empty($weight_from)){
              $book_conditions[] = "wt BETWEEN '$weight_from' AND '$weight_to'";
          }
          if(!empty($book_destination)){
              $book_conditions[] = "dest_code = '$book_destination'";
          }
          if(!empty($book_zone)){
              $book_conditions[] = "zone_code = '$book_zone'";
          }
          if(!empty($bookstn)){
              $book_conditions[] = "comp_code = '$bookstn'";
          }
          $book_condition = implode(' AND ', $book_conditions);

          $booking_report = $db->query("SELECT * FROM $table WITH (NOLOCK)  WHERE $book_condition $cust_type_condition ORDER BY tdate");

          $totalbookamt = $db->query("SELECT sum(amt) as 'total' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition")->fetch(PDO::FETCH_OBJ);
          if($book_rep_type=='bookcash'){
              $totalbookramt = $db->query("SELECT sum(ramt) as 'rtotal' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition AND ramt is not null AND ramt!=''")->fetch(PDO::FETCH_OBJ);
         }
          $b_sno=0;
          while($bkrow = $booking_report->fetch(PDO::FETCH_OBJ)){ $b_sno++;
              echo "<tr>
                   <td>".$b_sno."</td>
                   <td>".$bkrow->br_code."</td>";
                   if($book_rep_type=='bookcredit'){
                        echo "<td>".$bkrow->cust_code."</td>";
                   }
                   if($book_rep_type=='bookinbound'){
                        echo "<td>".$bkrow->bacode."</td>";
                   }
                   echo "
                   <td>".$bkrow->cno."</td>
                   <td >".number_format($bkrow->wt,3)."</td>
                   <td>".$bkrow->mode_code."</td>
                   <td>".$bkrow->comp_code."</td>
                   <td>".$bkrow->dest_code."</td>
                   <td>".date("d-m-y", strtotime($bkrow->tdate))."</td>
                   <td style='text-align:right'>".number_format($bkrow->amt, 2)."</td>";
                   if($book_rep_type=='bookcash'){
                        echo "<td style='text-align:right'>".number_format($bkrow->ramt, 2)."</td>";
                     }
              echo "</tr>";
          }
          echo "<tr>
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>";
          if($book_rep_type=='bookcredit' || $book_rep_type=='bookinbound'){
               echo "<td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>";
          }
          echo "<td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>Total :</td>
          <td style='text-align:right;border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($totalbookamt->total, 2)."</td>";
          if($book_rep_type=='bookcash'){
              echo "<td style='text-align:right;border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($totalbookramt->rtotal, 2)."</td>";
          }
         echo" </tr></table>";
      }
      //generate report for collecton
      if(isset($_POST['coll_date_from'])){
          header("Content-Disposition: attachment; filename=collection-report.xls");  
           $coll_date_from = $_POST['coll_date_from'];
           $coll_date_to = $_POST['coll_date_to'];
           $coll_branch = $_POST['coll_branch'];
           $coll_report_type = $_POST['coll_report_type'];
           $coll_stn = $_POST['coll_stn'];
           //conditions of collectionreport
           if($coll_report_type=='collsummaryindate'){
           $coll_conditions = array();
           if(!empty($coll_date_from)){
                $coll_conditions[] = "coldate BETWEEN '$coll_date_from' AND '$coll_date_to'";
           }
           if(!empty($coll_branch)){
                $coll_conditions[] = "br_code = '$coll_branch'";
           }
           if(!empty($coll_stn)){
                $coll_conditions[] = "comp_code = '$coll_stn'";
           }
           $coll_condition = implode(' AND ', $coll_conditions);
           //collection data sql_query
           $collreport = $db->query("SELECT * FROM collection WHERE $coll_condition ORDER BY coldate");
           echo "<table><thead>
           <tr>
               <th class='align-left'>S.no</th>
               <th class='align-left'>Collection Date</th>
               <th class='align-right'>Colln.Amt</th>
               <th class='align-right'>TDS</th>
               <th class='align-right'>Wrong Bill</th>
               <th class='align-right'>Deduction</th>
               <th class='align-right'>Others</th>
               <th class='align-right'>Total</th>
          </tr>
           </thead><tbody>";
           $c_sno=0;
           while($collrow = $collreport->fetch(PDO::FETCH_OBJ)){ $c_sno++;
               echo "<tr>
                    <td>".$c_sno."</td>
                    <td>".date("d-m-y", strtotime($collrow->coldate))."</td>
                    <td style='text-align:right'>".number_format($collrow->colamt, 2)."</td>
                    <td style='text-align:right'>".number_format($collrow->tds, 2)."</td>
                    <td style='text-align:right'>".number_format($collrow->wrongbill, 2)."</td>
                    <td style='text-align:right'>".number_format($collrow->reduction, 2)."</td>
                    <td style='text-align:right'>".$collrow->remarks."</td>
                    <td style='text-align:right'>".number_format($collrow->invamt,  2)."</td>
                    </tr>";
           }
           $suminvamt = $db->query("SELECT SUM(invamt) as totalamt
           ,SUM(colamt) as sum_collamt
           ,SUM(tds) as sum_tds
           ,SUM(wrongbill) as sum_wrongbill
           ,SUM(reduction) as sum_reduction
            FROM collection WHERE $coll_condition AND status NOT LIKE '%Void' ")->fetch(PDO::FETCH_OBJ);
           echo "<tr>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".number_format($suminvamt->sum_collamt,2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".number_format($suminvamt->sum_tds,2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".number_format($suminvamt->sum_wrongbill,2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".number_format($suminvamt->sum_reduction,2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".number_format($suminvamt->totalamt,2)."</td>
           </tr></tbody></table>";
          }
          else if($coll_report_type=='collsummary'){
               $collsummreport = $db->query("SELECT * FROM collection WHERE coldate BETWEEN '$coll_date_from' AND '$coll_date_to' AND comp_code='$coll_stn' ORDER BY coldate");
           echo "<table><thead>
           <tr>
               <th class='align-left'>S.no</th>
               <th class='align-left'  style='min-width:100px'>Comp Code</th>
               <th class='align-left'  style='min-width:100px'>Br Code</th>
               <th class='align-left'  style='min-width:100px'>Cust Code</th>
               <th class='align-left'  style='min-width:170px'>Cust Name</th>
               <th class='align-left'  style='min-width:100px'>Sheet No</th>
               <th class='align-left'  style='min-width:100px'>Collection Number</th>
               <th class='align-left'  style='min-width:100px'>Invoice Number</th>
               <th class='align-left'  style='min-width:100px'>Collection Date</th>
               <th class='align-left'  style='min-width:100px'>Chq/DD/NEFT No</th>
               <th class='align-right'  style='min-width:100px'>Collection Amount</th>
               <th class='align-right'  style='min-width:100px'>Delivery</th>
               <th class='align-right'  style='min-width:100px'>OTC</th>
               <th class='align-right'  style='min-width:100px'>Deduction</th>
               <th class='align-right'  style='min-width:100px'>TDS</th>
               <th class='align-right'  style='min-width:100px'>Wrong Bill</th>  
               <th class='align-left'  style='min-width:100px'>Remarks</th>
               <th class='align-right'  style='min-width:100px'>Narration</th>
               <th class='align-left' style='min-width:80px'>Inv Date</th>
               <th class='align-left'  style='min-width:100px'>C Mode</th>
          </tr>
           </thead><tbody>";
           $c_sno=0;
           while($collsumrow = $collsummreport->fetch(PDO::FETCH_OBJ)){ $c_sno++;
               $collsummcname = $db->query("SELECT cust_name FROM customer WHERE cust_code='$collsumrow->cust_code' AND comp_code='$coll_stn'")->fetch(PDO::FETCH_OBJ);
               $collsumminvdate = $db->query("SELECT invdate FROM invoice WHERE invno='$collsumrow->invno' AND comp_code='$coll_stn'")->fetch(PDO::FETCH_OBJ);
               echo "<tr>
                         <td>".$c_sno."</td>
                         <td style='width:120px'>".$collsumrow->comp_code."</td>
                         <td style='width:120px'>".$collsumrow->br_code."</td>
                         <td style='width:120px'>".$collsumrow->cust_code."</td>
                         <td style='width:120px'>".$collsummcname->cust_name."</td>
                         <td style='width:120px'>".$collsumrow->sheetno."</td>
                         <td style='width:120px'>".$collsumrow->colno."</td>
                         <td style='width:120px'>".$collsumrow->invno."</td>
                         <td style='width:120px'>".date("d-m-y", strtotime($collsumrow->coldate))."</td>
                         <td style='width:120px'>".$collsumrow->rcptno."</td>
                         <td style='text-align:right'>".number_format($collsumrow->colamt,2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->delivery, 2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->otc, 2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->reduction, 2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->tds, 2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->wrongbill,  2)."</td>
                         <td style='width:120px'>".$collsumrow->remarks."</td>
                         <td style='width:120px'>".$collsumrow->narration."</td>
                         <td style='width:120px'>".date("d-m-y", strtotime($collsumminvdate->invdate))."</td>
                         <td style='width:120px'>".$collsumrow->cmode."</td>
                    </tr>";
           }
           //all sum values
           $csvaluesum = $db->query("SELECT SUM(delivery) as 'sumdelivery', SUM(colamt) as 'sumcolamt',SUM(otc) as 'sumotc', SUM(tds) as 'sumtds', SUM(wrongbill) as 'sumwrongbill', SUM(colamt) as 'sumcolamt', SUM(reduction) as 'sumreduction' FROM collection WHERE coldate BETWEEN '$coll_date_from' AND '$coll_date_to' AND comp_code='$coll_stn'")->fetch(PDO::FETCH_OBJ);
           echo "</tbody></table>";
          }
          else if($coll_report_type=='collsummaryinbranch'){
               $collbrreport = $db->query("SELECT SUM(delivery) as 'repsumbr', SUM(colamt) as 'repchequebr', SUM(reduction) as 'totbrotc', SUM(tds) as 'sutdsmbr', SUM(wrongbill) as 'brwrongsum' FROM collection WHERE br_code='$coll_branch' AND coldate BETWEEN '$coll_date_from' AND '$coll_date_to' AND comp_code='$coll_stn'")->fetch(PDO::FETCH_OBJ);

           echo "<table><thead>
           <tr>
               <th class='align-left'>Branch</th>
               <th class='align-left'>Branch Name</th>
               <th class='align-right'>Cash</th>
               <th class='align-right'>Cheque</th>
               <th class='align-right'>Deduction</th>
               <th class='align-right'>TDS</th>
               <th class='align-right'>Wrong Bill</th>
               <th class='align-right'>Total</th>
          </tr>
           </thead><tbody><tr>
                    <td>".$coll_branch."</td>
                    <td>".$coll_branch."</td>
                    <td class='align-right'>".bcdiv($collbrreport->repsumbr, 1, 2)."</td>
                    <td class='align-right'>".bcdiv($collbrreport->repchequebr, 1, 2)."</td>
                    <td class='align-right'>".bcdiv($collbrreport->totbrotc, 1, 2)."</td>
                    <td style='text-align:right'>".bcdiv($collbrreport->sutdsmbr, 1, 2)."</td>
                    <td style='text-align:right'>".bcdiv($collbrreport->brwrongsum, 1, 2)."</td>
                    <td style='text-align:right'>".bcdiv($collbrreport->repchequebr+$collbrreport->totbrotc+$collbrreport->sutdsmbr+$collbrreport->brwrongsum, 1, 2)."</td>
                    </tr></tbody></table>";
          }
      }
     //generate report for invoice
     if(isset($_POST['reporttype'])){
          header("Content-Disposition: attachment; filename=invoice-report.xls");  
          $reporttype = $_POST['reporttype'];
          $inv_date_to = $_POST['inv_date_to'];
          $invtran_date_from = $_POST['invtran_date_from'];
          $invtran_date_to = $_POST['invtran_date_to'];
          $inv_branch = $_POST['inv_branch'];
          $inv_customer = $_POST['inv_customer'];
          $inv_date_from = $_POST['inv_date_from'];
          $inv_month_from = $_POST['inv_month_from']."-01";
          $inv_month_to = $_POST['inv_month_to']."-31";
          //$inv_year = $_POST['inv_year'];
          $inv_stn = $_POST['inv_stn'];
          //generate tables
          if($reporttype=='invoicesummary'){ 
               if(!empty($inv_date_from)){
                    $invrptdatefilter = "invdate BETWEEN '$inv_date_from' AND '$inv_date_to'";
               }
               else{
                    $invrptdatefilter = "fmdate='$invtran_date_from' AND todate='$invtran_date_to'";
               }
               echo"<table><thead>
               <tr>
               <th class='align-left'>S.No</th>
               <th class='align-left'>Inv No</th>
               <th style='width:100px' class='align-left'>Inv Date</th>
               <th class='align-left'>Cust Code</th>
               <th class='align-left'>Cust Name</th>
               <th  style='width:100px' class='align-left'>Frm Dt</th>
               <th  style='width:100px' class='align-left'>To Dt</th>
               <th class='align-right'>Grand Amt</th>
               <th class='align-right'>FSC</th>
               <th class='align-right'>Inv Amt</th>
               <th class='align-right'>IGST</th>
               <th class='align-right'>CGST</th>
               <th class='align-right'>SGST</th>
               <th class='align-right'>Net Amt</th>
               <th class='align-right'>Cust GSTIN</th>
             
               </tr>
               </thead><tbody>";
               $invoicesummary = $db->query("SELECT * FROM invoice WHERE $invrptdatefilter AND comp_code='$inv_stn' ORDER BY invdate");
               $is_sno=0;
               while($invsrow = $invoicesummary->fetch(PDO::FETCH_OBJ)){$is_sno++;
                    $invsrcname = $db->query("SELECT cust_name,cust_gstin FROM customer WHERE cust_code='$invsrow->cust_code' AND comp_code='$inv_stn'")->fetch(PDO::FETCH_OBJ);
                    echo "<tr>
                    <td>".$is_sno."</td>
                    <td>".$invsrow->invno."</td>
                    <td>".date("d-m-y", strtotime($invsrow->invdate))."</td>
                    <td>".$invsrow->cust_code."</td>
                    <td>".$invsrcname->cust_name."</td>
                    <td>".date("d-m-y", strtotime($invsrow->fmdate))."</td>          
                    <td>".date("d-m-y", strtotime($invsrow->todate))."</td>
                    <td style='text-align:right'>".number_format($invsrow->grandamt, 2)."</td>
                    <td style='text-align:right'>".number_format($invsrow->fsc,  2)."</td>
                    <td style='text-align:right'>".number_format($invsrow->invamt,  2)."</td>
                    <td style='text-align:right'>".number_format($invsrow->igst,  2)."</td>
                    <td style='text-align:right'>".number_format($invsrow->cgst,  2)."</td>
                    <td style='text-align:right'>".number_format($invsrow->sgst,  2)."</td>
                    <td style='text-align:right'>".number_format($invsrow->netamt,  2)."</td>
                    <td style='text-align:right'>".$invsrcname->cust_gstin."</td>
                  
                    </tr>";
               }
               //sumofgrandamt
              
                $insgrand = $db->query("SELECT SUM(grandamt) as 'sum_grandamt'
                ,SUM(fsc) as 'sum_fsc'
                ,SUM(invamt) as 'sum_invamt'
                , SUM(igst) as 'sum_igst'
                , SUM(cgst) as 'iscgst'
                , SUM(sgst) as 'isigst'
                ,SUM(netamt) as 'isnetamt' FROM invoice WHERE invdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND comp_code='$inv_stn'")->fetch(PDO::FETCH_OBJ);
                echo "<tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->sum_grandamt,2)."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->sum_fsc,  )."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->sum_invamt,  )."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->sum_igst,  2)."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->iscgst,  2)."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->isigst,  2)."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->isnetamt,  2)."</td>
                <td></td>
               </tbody></table>";
          }
          else if($reporttype=='invoicesummarywithcoll'){
              echo"<table><thead>
              <tr>
              <th class='align-left'>S.No</th>
              <th class='align-left'>Inv No</th>
              <th style='width: 80px;' class='align-left'>Inv Date</th>
              <th class='align-left'>Cust Code</th>
              <th  class='align-left'>Cust Name</th>
              <th style='width: 80px;' class='align-left'>From Date</th>
              <th style='width: 80px;' class='align-left'>To Date</th>
              <th class='align-right'>Net Amt</th>
              <th class='align-right'>Rcvd Amt</th>
              <th class='align-right'>TDS</th>
              <th class='align-right'>Wrong Bill</th>
              <th class='align-right'>Deduction</th>
              <th class='align-right'>Balance</th>
              </tr>
              </thead><tbody>";
              if(!empty($inv_date_from))
              {
                   $invrptdatefilter = "invdate BETWEEN '$inv_date_from' AND '$inv_date_to'";
              }
              else
              {
                   $invrptdatefilter = "fmdate='$invtran_date_from' AND todate='$invtran_date_to'";
              }
              $invoicesummarywithcoll = $db->query("SELECT cust_code,invno,invdate,fmdate,todate,netamt,rcvdamt
              ,(select sum(tds) from collection where comp_code=invoice.comp_code and invno=invoice.invno) inv_tds
              ,(select sum(reduction) from collection where comp_code=invoice.comp_code and invno=invoice.invno) inv_reduction
              ,(select sum(wrongbill) from collection where comp_code=invoice.comp_code and invno=invoice.invno) inv_wrongbill
               FROM invoice WHERE $invrptdatefilter AND comp_code='$inv_stn' ORDER BY invdate");
              $iswc_sno=0;
              while($invswcrow = $invoicesummarywithcoll->fetch(PDO::FETCH_OBJ)){$iswc_sno++;
                   $invswcrcname = $db->query("SELECT cust_name FROM customer WHERE cust_code='$invswcrow->cust_code' AND comp_code='$inv_stn'")->fetch(PDO::FETCH_OBJ);

                   echo "<tr>
                   <td>".$iswc_sno."</td>
                   <td>".$invswcrow->invno."</td>
                   <td>".date("d-m-y", strtotime($invswcrow->invdate))."</td>                      
                   <td>".$invswcrow->cust_code."</td>
                   <td>".$invswcrcname->cust_name."</td>                             
                   <td>".date("d-m-y", strtotime($invswcrow->fmdate))."</td>          
                   <td>".date("d-m-y", strtotime($invswcrow->todate))."</td>
                   <td style='text-align:right'>".number_format($invswcrow->netamt, 2)."</td>
                   <td style='text-align:right'>".number_format($invswcrow->rcvdamt, 2)."</td>
                   <td style='text-align:right'>".number_format($invswcrow->inv_tds, 2)."</td>
                   <td style='text-align:right'>".number_format($invswcrow->inv_wrongbill, 2)."</td>
                   <td style='text-align:right'>".number_format($invswcrow->inv_reduction, 2)."</td>
                   <td style='text-align:right'>".number_format($invswcrow->netamt-$invswcrow->rcvdamt, 2)."</td>
                   </tr>";
              }
              //sumofgrandamt
              $inswcgrand = $db->query("SELECT SUM(netamt) as 'iswcgamt', SUM(rcvdamt) as 'iswcrcamt'
              ,(select SUM(tds) from collection where invdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND comp_code=invoice.comp_code) as 'iswctds'
              ,(select SUM(wrongbill) from collection where invdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND comp_code=invoice.comp_code) as 'iswcwrongbil'
              ,(select SUM(reduction) from collection where invdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND comp_code=invoice.comp_code) as 'isdeductbil' 
              FROM invoice WHERE invdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND comp_code='$inv_stn'")->fetch(PDO::FETCH_OBJ);
              echo "<tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td style='border-top:1px solid black;text-align:right;font-weight:bold;line-height:20px'>".number_format($inswcgrand->iswcgamt,2)."</td>
              <td style='border-top:1px solid black;text-align:right;font-weight:bold;line-height:20px'>".number_format($inswcgrand->iswcrcamt,2)."</td>
              <td style='border-top:1px solid black;text-align:right;font-weight:bold;line-height:20px'>".number_format($inswcgrand->iswctds,2)."</td>
              <td style='border-top:1px solid black;text-align:right;font-weight:bold;line-height:20px'>".number_format($inswcgrand->iswcwrongbil,2)."</td>
              <td style='border-top:1px solid black;text-align:right;font-weight:bold;line-height:20px'>".number_format($inswcgrand->isdeductbil,2)."</td>
              <td style='border-top:1px solid black;text-align:right;font-weight:bold;line-height:20px'>".number_format($inswcgrand->iswcgamt-$inswcgrand->iswcrcamt,2)."</td>
              <td></td>
              </tbody></table>";
          }
          else if($reporttype=='monthwiseinvoicesummary'){
               if(empty($inv_branch) || trim($inv_branch)=='ALL'){
                    $invr_brconditions = "";
               }
               else{
                    $invr_brconditions = " AND br_code='$inv_branch'";
               }
               if(empty($inv_customer) || trim($inv_customer)=='ALL'){
                    $invr_custconditions = "";
               }
               else{
                    $invr_custconditions = " AND cust_code='$inv_customer'";
               }
               $monthinv = $db->query("SELECT DISTINCT(invdate) FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' $invr_brconditions $invr_custconditions order by invdate");
               echo"<table><thead>
               <tr>
               <th>S.no</th>
               <th class='align-left'>Cust Code</th>
               <th class='align-left'>Cust Name</th>";
               while($monthinvrow=$monthinv->fetch(PDO::FETCH_OBJ))
               {
               echo "<th class='align-left'>".date("M - Y", strtotime($monthinvrow->invdate))."</th>";
               }
               echo "<th class='align-left'>Total</th>
               </tr>
               </thead><tbody>";

               //looping table datas
               $getmocusts = $db->query("SELECT DISTINCT(cust_code) FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' $invr_brconditions $invr_custconditions ");

               $sno=0;
               while($moinvrow = $getmocusts->fetch(PDO::FETCH_OBJ)){$sno++;
               $getcustname = $db->query("SELECT top 1 cust_name FROM customer WHERE cust_code = '$moinvrow->cust_code' AND comp_code = '$inv_stn'")->fetch(PDO::FETCH_OBJ);
                    echo "<tr>
                          <td>$sno</td>
                          <td>".$moinvrow->cust_code." </td>
                          <td>".$getcustname->cust_name."</td>";
                          $monthinv2 = $db->query("SELECT DISTINCT(invdate) FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' $invr_brconditions $invr_custconditions ");
                          while($cdataminv = $monthinv2->fetch(PDO::FETCH_OBJ)){
                               $getmoinvnet = $db->query("SELECT SUM(netamt-rcvdamt) as balamt FROM invoice WHERE cust_code='$moinvrow->cust_code' AND comp_code='$inv_stn' AND invdate='$cdataminv->invdate' AND status='Pending'")->fetch(PDO::FETCH_OBJ);
                                   echo "<td>".number_format($getmoinvnet->balamt)."</td>"; 
                              }
                    $invmototnet = $db->query("SELECT SUM(netamt) as 'mocutotnet' FROM invoice WHERE cust_code='$moinvrow->cust_code' AND comp_code='$inv_stn' AND  invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND status='Pending'")->fetch(PDO::FETCH_OBJ);
                    $invmototrcvd = $db->query("SELECT SUM(rcvdamt) as 'mocutotrcvd' FROM invoice WHERE cust_code='$moinvrow->cust_code' AND comp_code='$inv_stn' AND  invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND status='Pending'")->fetch(PDO::FETCH_OBJ);
                    echo "<td>".number_format($invmototnet->mocutotnet-$invmototrcvd->mocutotrcvd)."</td>
                    </tr>";
               }
               //sum_of_invs_amt month wise
               echo "<tr>
               <th></th>
               <th></th>
               <th class='align-right'>Total</th>
               ";
               $monthinv2sum = $db->query("SELECT SUM(netamt-rcvdamt) as balamt FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' $invr_brconditions $invr_custconditions ")->fetch(PDO::FETCH_OBJ);
               $monthinv2 = $db->query("SELECT DISTINCT(invdate) FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' $invr_brconditions $invr_custconditions ");
                          while($cdataminv = $monthinv2->fetch(PDO::FETCH_OBJ)){
                              $getmoinvnet = $db->query("SELECT SUM(netamt-rcvdamt) as balamt FROM invoice WHERE comp_code='$inv_stn' AND invdate='$cdataminv->invdate' AND status='Pending' $invr_brconditions $invr_custconditions ")->fetch(PDO::FETCH_OBJ);
                              echo "<th>".number_format($getmoinvnet->balamt)."</th>"; 
                              }
                              echo "<th>".number_format($monthinv2sum->balamt)."</th>"; 
               echo "</tr></tbody></table>";
          }
          else if($reporttype=='groupinvoicesummary')
          {
             
          }
          else if($reporttype=='unvoicesummary')
          {
             echo "<table><thead>
              <tr>
              <th>S.No</th>
              <th>Company</th>
              <th>Cnote Number</th>
              <th>Branch</th>
              <th>Weight</th>
              <th>Mode</th>
              <thstyle='text-align:right'>Amount</th>
              <th>T date</th>
              <th>Status</th>
             </tr>
             </thead><tbody>";
             $unvoice = $db->query("SELECT * FROM credit WHERE tdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND status='Billed'");$sno=0;
             while($rowunvoice = $unvoice->fetch(PDO::FETCH_OBJ)){$sno++;
                  echo "<tr>
                  <td>$sno</td>
                  <td>$rowunvoice->comp_code</td>
                  <td>$rowunvoice->cno</td>
                  <td>$rowunvoice->br_code</td>
                  <td>".number_format($rowunvoice->wt,3)."</td>
                  <td>$rowunvoice->mode_code</td>
                  <td style='text-align:right'>".round($rowunvoice->amt)."</td>
                  <td>$rowunvoice->tdate</td>
                  <td>$rowunvoice->status</td>
                  </tr>";
             }
             echo "<tbody>";
          }
          else if($reporttype=='invoicegstreport'){
             echo "<thead>
              <tr>
              <th>S.No</th>
              <th>Inv Date</th>
              <th>Company</th>
              <th>Branch</th>
              <th>Customer</th>
              <th>IGST</th>
              <th>CGST</th>
              <th>SGST</th>
             </tr>
             </thead><tbody>";
             $gstvoice = $db->query("SELECT * FROM invoice WHERE invdate BETWEEN '$inv_date_from' AND '$inv_date_to'");$sno=0;
             while($rowgstvoice = $gstvoice->fetch(PDO::FETCH_OBJ)){$sno++;
                  echo "<tr>
                  <td>$sno</td>
                  <td>$rowgstvoice->invdate</td>
                  <td>$rowgstvoice->comp_code</td>
                  <td>$rowgstvoice->br_code</td>
                  <td>$rowgstvoice->cust_code</td>
                  <td>".number_format($rowgstvoice->igst,2)."</td>
                  <td>".number_format($rowgstvoice->cgst,2)."</td>
                  <td>".number_format($rowgstvoice->sgst,2)."</td>
                  </tr>";
             }
             echo "<tbody></table>";
          }

     }
      //fetch data for missing report
      if(isset($_POST['missed_report_type'])){
          header("Content-Disposition: attachment; filename=missing-report.xls");  
          $missed_report_type = $_POST['missed_report_type'];
           $missed_branch      = $_POST['missed_branch'];
           $missed_customer    = $_POST['missed_customer'];
           $missed_station    = $_POST['missed_station'];
           $miss_date_from    = $_POST['miss_date_from'];
           $miss_date_to      = $_POST['miss_date_to'];
           $miss_month_from    = $_POST['miss_month_from'];
           $miss_month_to     = $_POST['miss_month_to'];
           $miss_year         = $_POST['miss_year'];
           $missed_serial         = $_POST['missed_serial'];
          
           if($missed_report_type=='Unallocated'){
               if(!empty($miss_date_from) && !empty($miss_date_to)){
                    $datefilter = " AND tdate BETWEEN '$miss_date_from' AND '$miss_date_to'";
               }
               else{
                    $datefilter = " ";
               }
               echo"<table><thead>
               <tr>
               <th class='align-left'>S.No</th>
               <th class='align-left'>Origin</th>
               <th class='align-left'>Tdate</th>
               <th class='align-left'>Cno</th>
               <th class='align-left'>Op Destn</th>
               <th class='align-left'>Op wt</th>
               <th class='align-left'>Op mode</th>
               <th class='align-left'>Ttype</th>
               <th class='align-left'>Status</th>
               </tr></thead><tbody>";
               $usno=0;
               $unallocated = $db->query("SELECT * FROM opdata WHERE origin='$missed_station' $datefilter AND (select count(*) from cnotenumber where cnote_station=opdata.origin and cnote_number=opdata.cno and br_code is null and cust_code is null)>0");
               while($unalloc_row = $unallocated->fetch(PDO::FETCH_OBJ)){ $usno++;
                    echo "<tr>
                    <td>".$usno."</td>
                    <td>".$unalloc_row->origin."</td>
                    <td>".$unalloc_row->tdate."</td>
                    <td>".$unalloc_row->cno."</td>
                    <td>".$unalloc_row->destn."</td>
                    <td>".$unalloc_row->wt."</td>
                    <td>".$unalloc_row->mode_code."</td>
                    <td>".$unalloc_row->ttype."</td>
                    <td>UnAllocated</td>
                    </tr>";
               }
               echo "</tbody></table>";
           }
           else if($missed_report_type=='UnKnown'){
               if(!empty($miss_date_from) && !empty($miss_date_to)){
                    $datefilter = " AND tdate BETWEEN '$miss_date_from' AND '$miss_date_to'";
               }
               else{
                    $datefilter = " ";
               }
               echo"<table><thead>
               <tr>
               <th class='align-left'>S.No</th>
               <th class='align-left'>Origin</th>
               <th class='align-left'>Tdate</th>
               <th class='align-left'>Cno</th>
               <th class='align-left'>Op Destn</th>
               <th class='align-left'>Op wt</th>
               <th class='align-left'>Op mode</th>
               <th class='align-left'>Ttype</th>
               <th class='align-left'>Status</th>
               </tr></thead><tbody>";
               $usno=0;
               $unallocated = $db->query("SELECT * FROM opdata WHERE origin='$missed_station' $datefilter AND (select count(*) from cnotenumber where cnote_station=opdata.origin and cnote_number=opdata.cno)=0");
               while($unalloc_row = $unallocated->fetch(PDO::FETCH_OBJ)){ $usno++;
                    echo "<tr>
                    <td>".$usno."</td>
                    <td>".$unalloc_row->origin."</td>
                    <td>".$unalloc_row->tdate."</td>
                    <td>".$unalloc_row->cno."</td>
                    <td>".$unalloc_row->destn."</td>
                    <td>".$unalloc_row->wt."</td>
                    <td>".$unalloc_row->mode_code."</td>
                    <td>".$unalloc_row->ttype."</td>
                    <td>UnKnown</td>
                    </tr>";
               }
               echo "</tbody></table>";
           }
           else{
               if(!empty($miss_month_from)){
                    $datefilter = "AND MONTH(tdate) BETWEEN '$miss_month_from' AND '$miss_month_to'";
               }
               else{
                   $datefilter = "AND tdate BETWEEN '$miss_date_from' AND '$miss_date_to'";
               }
               if($missed_branch=='ALL' || $missed_branch==''){
                    $brcondition = '';
               }
               else{
                    $brcondition = "AND br_code='$missed_branch'";
               }
               if($missed_customer=='ALL' || $missed_customer==''){
                    $custcondition = '';
               }
               else{
                    $custcondition = "AND cust_code='$missed_customer'";
               }
               if($missed_serial=='ALL'){
                    $missedserialcondition = '';
               }
               else{
                    $missedserialcondition = "AND cnote_serial='$missed_serial'";
               }
               if($missed_serial=='PRO'){
                    $oprigin = "AND oporigin='PRO'";
               }
               else{
                    $oprigin = "AND oporigin='$missed_station'";
               }
               if($missed_report_type=='custau' || $missed_report_type=='custub'){
                    $missedtype_filter = "AND cust_code IS NOT NULL";
               }
               else if($missed_report_type=='branchau' || $missed_report_type=='branchub'){
                    $missedtype_filter = "AND cust_code IS NULL";
               }
               //if($missed_report_type=='custau' || $missed_report_type=='branchau'){
               echo"<table><thead>
               <tr>
               <th class='align-left'>S.No</th>
               <th class='align-left'>Tdate</th>";
               if($missed_report_type=='custub' || $missed_report_type=='custau'){
                    echo "<th class='align-left'>Br Code</th>
                         <th class='align-left'>Cust Code</th>";
                         $nullcondition = "AND NOT cust_code IS NULL";
               }
               else{
                    echo "<th class='align-left'>Br Code</th>";
                    $nullcondition = "AND cust_code IS NULL";
               }
               echo "<th class='align-left'>Cnote Number</th>
               <th class='align-left'>Cnote Serial</th>
               <th class='align-left'>Destination</th>
               <th class='align-left'>Weight</th>
               <th class='align-left'>Mode</th>
               <th class='align-left'>Status</th>
               </tr>
               </thead><tbody>";
               if($missed_report_type=='custau' || $missed_report_type=='branchau'){
               $opdata = $db->query("SELECT * FROM cnotenumber WHERE cnote_station='$missed_station' AND cnote_status='Allotted' AND (SELECT count(cno) FROM credit WHERE cno=cnote_number AND comp_code=cnote_station)=0 AND (SELECT count(cno) FROM cash WHERE cno=cnote_number AND comp_code=cnote_station)=0 $missedserialcondition $datefilter $brcondition $custcondition $oprigin $nullcondition $missedtype_filter ORDER BY tdate");
               }
               if($missed_report_type=='custub' || $missed_report_type=='branchub'){
               $opdata = $db->query("SELECT * FROM cnotenumber WHERE cnote_station='$missed_station' AND cnote_status='Allotted' AND (SELECT count(cno) FROM credit WHERE cno=cnote_number AND comp_code=cnote_station)=0 AND (SELECT count(cno) FROM cash WHERE cno=cnote_number AND comp_code=cnote_station)=0 AND opstatus='Unbilled' $missedserialcondition $datefilter $brcondition $custcondition $missedtype_filter ORDER BY tdate");
               }
               $sno=0;
               while($oprow = $opdata->fetch(PDO::FETCH_OBJ)){$sno++;
                    echo "<tr>
                    <td>$sno</td>
                    <td>".date("d-m-y", strtotime($oprow->tdate))."</td>";
                    if($missed_report_type=='custub' || $missed_report_type=='custau')
                    {
                         echo "<td>$oprow->br_code</td>
                               <td>$oprow->cust_code</td>";
                    }
                    else
                    {
                         echo "<td>$oprow->br_code</td>";
                    }
                    echo "<td>$oprow->cnote_number</td>
                    <td>$oprow->cnote_serial</td>
                    <td >$oprow->opdest</td>
                    <td >".number_format($oprow->opwt,3)."</td>
                    <td >$oprow->opmode</td>
                    <td>Unbilled</td>
                    </tr>";
               }
               echo "</tbody></table>";
          }

      }
      //generate report for customer reporttype
      if(isset($_POST['custrep_type'])){
          header("Content-Disposition: attachment; filename=customer-report.xls");  
          $custrep_type = $_POST['custrep_type'];
          $custrep_branch = $_POST['custrep_branch'];
          $custrep_customer = $_POST['custrep_customer'];
          $custrep_stn = $_POST['custrep_stn'];
          $ledger_date_from = $_POST['ledger_date_from'];
          $ledger_date_to = $_POST['ledger_date_to'];
          if($custrep_type=='custagewisereport'){
               //Generate for all branch customers
               if($custrep_branch=='ALL'){
                    $branchcondition = "";
                }
                else{
                   $branchcondition = "AND br_code='$custrep_branch'";
                }
                if($custrep_customer=='ALL' || trim($custrep_customer)==''){
                    $customercondition = "";
                }
                else{
                   $customercondition = "AND cust_code='$custrep_customer'";
                }
                   echo"<table><thead>
                   <tr>
                   <th class='align-left'>S.No</th>
                   <th class='align-left'>Company</th>
                   <th class='align-left'>Branch</th>
                   <th class='align-left'>Customer code</th>
                   <th class='align-left'>Customer / Franchisee</th>
                   <th class='align-right'>OutStanding</th>
                   <th class='align-right'>No of Invoice</th>
                   </tr>
                   </thead><tbody>";
              //looping all customers
               $custagereport = $db->query("SELECT DISTINCT(cust_code) FROM invoice WHERE comp_code='$custrep_stn' $branchcondition $customercondition AND status='Pending'");
               $sno=0;
               while($custagewiseall = $custagereport->fetch(PDO::FETCH_OBJ)){$sno++;
                    //get customer datum
                    $custdatum = $db->query("SELECT * FROM customer WHERE cust_code='$custagewiseall->cust_code' AND comp_code='$custrep_stn'")->fetch(PDO::FETCH_OBJ);
                    //get customer OutStanding
                    $custageoutstand = $db->query("SELECT SUM(netamt) as 'agetotalamt' FROM invoice WHERE comp_code='$custrep_stn' AND status='Pending' AND cust_code='$custdatum->cust_code'")->fetch(PDO::FETCH_OBJ);
                    $custageoutstand1 = $db->query("SELECT SUM(rcvdamt) as 'agetotalamt1' FROM invoice WHERE comp_code='$custrep_stn' AND status='Pending' AND cust_code='$custdatum->cust_code'")->fetch(PDO::FETCH_OBJ);
                    $custageoutinv = $db->query("SELECT COUNT(*) as 'agependinvcount' FROM invoice WHERE comp_code='$custrep_stn' AND status='Pending' AND cust_code='$custdatum->cust_code'")->fetch(PDO::FETCH_OBJ);
                    echo "<tr>
                        <td>".$sno."</td>                         
                        <td>".$custrep_stn."</td>                         
                        <td>".$custdatum->br_code."</td>
                        <td>".$custdatum->cust_code."</td>
                        <td>".$custdatum->cust_name."</td>
                        <td style='text-align:right'>".number_format($custageoutstand->agetotalamt-$custageoutstand1->agetotalamt1,2)."</td>
                        <td style='text-align:right'>".$custageoutinv->agependinvcount."</td>
                        </tr>";
               }
               echo "</tbody></table>";

          }
          else if($custrep_type=='customeroutstandingsummary'){
              if($custrep_branch=='ALL' || $custrep_branch==''){
                   $branchcondition = "";
               }
               else{
                  $branchcondition = "AND br_code='$custrep_branch'";
               }
               if($custrep_customer=='ALL' || $custrep_customer==''){
                    $cust_condition = "";
               }
               else{
                   $cust_condition = " AND cust_code='$custrep_customer'";
               }
                   echo"<table><thead>
                   <tr>
                   <th class='align-left'>S.No</th>
                   <th class='align-left'>Comp Code</th>
                   <th class='align-left'>Br Code</th>
                   <th class='align-left'>Customer code</th>
                   <th class='align-left'>Customer / Franchisee</th>
                   <th class='align-left'>Inv No</th>
                   <th class='align-left'>Inv.Date</th>
                   <th class='align-right'>Inv Value</th>
                   <th class='align-right'>Balance</th>
                   <th class='align-right'>Total Balance</th>
                   <th class='align-left'>Inv Status</th>
                   <th class='align-left'>HUB</th>
                   <th class='align-left'>Phone</th>
                   <th class='align-left'>Email</th>
                   </tr>
                   </thead><tbody>";
             //looping customers one by one
             $pendingcustomers = $db->query("SELECT DISTINCT(cust_code) FROM invoice WHERE comp_code='$custrep_stn' $branchcondition $cust_condition");
             $ics=0;
             while($pendcusts = $pendingcustomers->fetch(PDO::FETCH_OBJ)){
                  $ccustdeta = $db->query("SELECT * FROM customer WHERE cust_code='$pendcusts->cust_code' AND comp_code='$custrep_stn'")->fetch(PDO::FETCH_OBJ);
                  $ccustpinv = $db->query("SELECT * FROM invoice WHERE cust_code='$pendcusts->cust_code' AND comp_code='$custrep_stn' AND status='Pending'");
                  $totalbal = $db->query("SELECT SUM(netamt) as 'totn' FROM invoice WHERE cust_code='$pendcusts->cust_code' AND comp_code='$custrep_stn' AND status='Pending'")->fetch(PDO::FETCH_OBJ);
                  $totalrcv = $db->query("SELECT SUM(rcvdamt) as 'totrcv' FROM invoice WHERE cust_code='$pendcusts->cust_code' AND comp_code='$custrep_stn' AND status='Pending'")->fetch(PDO::FETCH_OBJ);
                  $loopcounts = $db->query("SELECT COUNT(*) as 'lcounts' FROM invoice WHERE cust_code='$pendcusts->cust_code' AND comp_code='$custrep_stn' AND status='Pending'")->fetch(PDO::FETCH_OBJ);
                  $lc=0;
                  while($cinvpndrow = $ccustpinv->fetch(PDO::FETCH_OBJ)){$lc++;$ics++;
                  echo "<tr>
                        <td>$ics</td>
                        <td>".$ccustdeta->comp_code."</td>
                        <td>".$ccustdeta->br_code."</td>
                        <td>".$ccustdeta->cust_code."</td>
                        <td>".$ccustdeta->cust_name."</td>
                        <td>".$cinvpndrow->invno."</td>
                        <td>".date("d-m-Y", strtotime($cinvpndrow->invdate))."</td>
                        <td style='text-align:right'>".number_format($cinvpndrow->netamt,2)."</td>
                        <td style='text-align:right'>".number_format($cinvpndrow->netamt-$cinvpndrow->rcvdamt,2)."</td>";
                        if($loopcounts->lcounts == $lc){ // if this is the last item
                             echo "<th style='text-align:right'>".number_format($totalbal->totn-$totalrcv->totrcv,2)."</th>";
                         }
                         else{
                             echo "<td></td>";
                         }
                        
                        echo "<td>".$cinvpndrow->status."</td>
                        <td></td>
                        <td>".$ccustdeta->cust_mobile."</td>
                        <td>".$ccustdeta->cust_email."</td>
                  </tr>";
                  }
             }
               echo "</tbody></table>";

          }
          else if($custrep_type=='customeroutgroup'){
              //Generate for all branch customers
              if($custrep_branch=='ALL'){
                   $branchcondition = "";
               }
               else{
                  $branchcondition = "AND br_code='$custrep_branch'";
               }
               if($custrep_customer=='ALL' || trim($custrep_customer)==''){
                   $customercondition = "";
               }
               else{
                  $customercondition = "AND cust_code='$custrep_customer'";
               }
                  echo"<table><thead>
                  <tr>
                  <th class='align-left'>S.No</th>
                  <th class='align-left'>Customer code</th>
                  <th class='align-left'>Customer / Franchisee</th>
                  <th class='align-left'><=30 Days</th>
                  <th class='align-left'><=60 Days</th>
                  <th class='align-left'><=90 Days</th>
                  <th class='align-left'>>90 Days</th>
                  <th class='align-left'>Total</th>
                  </tr>
                  </thead><tbody>";
             //looping all customers
              $custagereport = $db->query("SELECT DISTINCT(cust_code) FROM invoice WHERE comp_code='$custrep_stn' $branchcondition $customercondition AND status='Pending'");
              $sno=0;
              while($custagewiseall = $custagereport->fetch(PDO::FETCH_OBJ)){$sno++;
                   //get customer datum
                   $custdatum = $db->query("SELECT * FROM customer WHERE cust_code='$custagewiseall->cust_code' AND comp_code='$custrep_stn'")->fetch(PDO::FETCH_OBJ);
                   //get last 30days record
                   $last30 = $db->query("SELECT SUM(netamt-rcvdamt) as last30 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' AND invdate >= DATEADD(day, -30, getdate())")->fetch(PDO::FETCH_OBJ);
                   $last60 = $db->query("SELECT SUM(netamt-rcvdamt) as last60 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' AND invdate <= DATEADD(day, -30, getdate()) AND invdate >= DATEADD(day, -60, getdate())")->fetch(PDO::FETCH_OBJ);
                   $last90 = $db->query("SELECT SUM(netamt-rcvdamt) as last90 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' AND invdate <= DATEADD(day, -60, getdate()) AND invdate >= DATEADD(day, -90, getdate())")->fetch(PDO::FETCH_OBJ);
                   $above90 = $db->query("SELECT SUM(netamt-rcvdamt) as above90 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' AND invdate < DATEADD(day, -90, getdate())")->fetch(PDO::FETCH_OBJ);
                   $total = $db->query("SELECT SUM(netamt-rcvdamt) as total FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code'")->fetch(PDO::FETCH_OBJ);
                   echo "<tr>
                       <td>".$sno."</td>                         
                       <td>".$custdatum->cust_code."</td>
                       <td>".$custdatum->cust_name."</td>
                       <td style='text-align:right'>".number_format($last30->last30,2)."</td>
                       <td style='text-align:right'>".number_format($last60->last60,2)."</td>
                       <td style='text-align:right'>".number_format($last90->last90,2)."</td>
                       <td style='text-align:right'>".number_format($above90->above90,2)."</td>
                       <td style='text-align:right'>".number_format($total->total,2)."</td>
                       </tr>";
              }
              echo "</tbody></table>";

         }
          else if($custrep_type=='branchoutstandingsummary'){
              if($custrep_branch=='ALL' || $custrep_branch==''){
                   $brcondition = "";
               }
               else{
                  $brcondition = "AND br_code='$custrep_branch'";
               }
                  echo"<table><thead>
                  <tr>
                  <th class='align-left'>S.No</th>
                  <th class='align-left'>Branch code</th>
                  <th class='align-left'>Branch Name</th>
                  <th class='align-left'><=30 Days</th>
                  <th class='align-left'><=60 Days</th>
                  <th class='align-left'><=90 Days</th>
                  <th class='align-left'>>90 Days</th>
                  <th class='align-left'>Total</th>
                  </tr>
                  </thead><tbody>";
             //looping all customers
              $bragereport = $db->query("SELECT DISTINCT(br_code) FROM invoice WHERE comp_code='$custrep_stn' $brcondition AND status='Pending'");
              $sno=0;
              while($bragewiseall = $bragereport->fetch(PDO::FETCH_OBJ)){$sno++;
                   $getbrname = $db->query("SELECT br_name FROM branch WHERE br_code='$bragewiseall->br_code' AND stncode='$custrep_stn'")->fetch(PDO::FETCH_OBJ);
                   $last30 = $db->query("SELECT SUM(netamt-rcvdamt) as last30 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' AND invdate >= DATEADD(day, -30, getdate())")->fetch(PDO::FETCH_OBJ);
                   $last60 = $db->query("SELECT SUM(netamt-rcvdamt) as last60 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' AND invdate <= DATEADD(day, -30, getdate()) AND invdate >= DATEADD(day, -60, getdate())")->fetch(PDO::FETCH_OBJ);
                   $last90 = $db->query("SELECT SUM(netamt-rcvdamt) as last90 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' AND invdate <= DATEADD(day, -60, getdate()) AND invdate >= DATEADD(day, -90, getdate())")->fetch(PDO::FETCH_OBJ);
                   $above90 = $db->query("SELECT SUM(netamt-rcvdamt) as above90 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' AND invdate < DATEADD(day, -90, getdate())")->fetch(PDO::FETCH_OBJ);
                   $total = $db->query("SELECT SUM(netamt-rcvdamt) as total FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code'")->fetch(PDO::FETCH_OBJ);
                   echo "<tr>
                       <td>".$sno."</td>                         
                       <td>".$bragewiseall->br_code."</td>
                       <td>".$getbrname->br_name."</td>
                       <td style='text-align:right'>".number_format($last30->last30,2)."</td>
                       <td style='text-align:right'>".number_format($last60->last60,2)."</td>
                       <td style='text-align:right'>".number_format($last90->last90,2)."</td>
                       <td style='text-align:right'>".number_format($above90->above90,2)."</td>
                       <td style='text-align:right'>".number_format($total->total,2)."</td>
                       </tr>";
              }
              echo "</tbody></table>";

         }
         else if($custrep_type=="customerledger"){
              echo "<table><thead>
              <th>S.no</th>
              <th>Date</th>
              <th>Particulars</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Balance</th>
              </thead><tbody>";
              $cust_ledger = $db->query("SELECT * FROM invoice WHERE invdate BETWEEN '$ledger_date_from' AND '$ledger_date_to' AND comp_code='$custrep_stn'");$sno=0;
              while($rowcust_ledger = $cust_ledger->fetch(PDO::FETCH_OBJ)){$sno++;
                   $ledgercheck = $db->query("SELECT colno FROM collection WHERE invno='$rowcust_ledger->invno' AND comp_code='$custrep_stn'");
                   if($ledgercheck->rowCount()==0){
                        $ledger_balance = number_format($rowcust_ledger->netamt,2);
                   }
                   else{
                        $ledger_balance = '';
                   }
                   echo "<tr>
                   <td>$sno</td>
                   <td>$rowcust_ledger->invdate</td>
                   <td>To Invoice: $rowcust_ledger->invno</td>
                   <td>".number_format($rowcust_ledger->netamt,2)."</td>
                   <td></td>
                   <td>$ledger_balance</td>
                   </tr>";
                   //check for collecton is availabe for current invoice
                  
                   if($ledgercheck->rowCount()!=0){
                   $coll_ledger = $db->query("SELECT sum(colamt)as colnamt , invno FROM collection WHERE invno='$rowcust_ledger->invno' AND comp_code='$custrep_stn' GROUP BY invno")->fetch(PDO::FETCH_OBJ);
                   $coll_ledger2 = $db->query("SELECT TOP(1) colno, coldate FROM collection WHERE invno='$rowcust_ledger->invno' AND comp_code='$custrep_stn' ORDER BY col_id DESC")->fetch(PDO::FETCH_OBJ);
                   echo "<tr>
                   <td></td>
                   <td>$coll_ledger2->coldate</td>
                   <td>By Colln     : $coll_ledger2->colno</td>
                   <td></td>
                   <td>".number_format($coll_ledger->colnamt,2)."</td>
                   <td>".($rowcust_ledger->netamt-$coll_ledger->colnamt)."</td>
                   </tr>";
                   }
                   echo "<tr style='height:25px'><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
              }
              echo "</tbody></table>";
         }
      }
       //generate report for new business
       if(isset($_POST['newbs_report_type'])){
          header("Content-Disposition: attachment; filename=newbussiness-report.xls");  
          $newbs_report_type = $_POST['newbs_report_type'];
          $newbs_report_format = $_POST['newbs_report_format'];
          $newbs_station = $_POST['newbs_station'];
          $newbs_branch = $_POST['newbs_branch'];
          $newbs_customer = $_POST['newbs_customer'];
          $newbs_date_from = $_POST['newbs_date_from'];
          $newbs_date_to = $_POST['newbs_date_to'];
          $newbs_month_from = $_POST['newbs_month_from'];
          $newbs_month_to = $_POST['newbs_month_to'];
          $newbs_year = $_POST['newbs_year'];
          $newbs_user = $_POST['newbs_user'];

          
          //branch filter
          if(empty($newbs_branch) || $newbs_branch=='ALL'){
               $newbsbrfilter = "";
          }
          else{
               $newbsbrfilter = "AND br_code='$newbs_branch'";
          }
          //customerfilter
          if(empty($newbs_customer) || $newbs_customer=='ALL'){
               $newbscustflter = "";
          }
          else{
               $newbscustflter = "AND cust_code='$newbs_customer'";
          }
          echo "<table><thead>
          <tr>
          <th>S.no</th>
          <th>Br code</th>
          <th>Br name</th>
          <th>cust code</th>
          <th>cust name</th>
          <th>Total Cnotes</th>
          <th>Total Amount</th>
          </tr>
          </thead>";
          $newbs_branchlist = $db->query("SELECT * FROM branch WHERE stncode='$newbs_station' $newbsbrfilter AND branchtype='$newbs_report_type'");
         while($rownewbs_branch = $newbs_branchlist->fetch(PDO::FETCH_OBJ)){
              $newbs_cfrmbr = $db->query("SELECT * FROM customer WHERE br_code='$rownewbs_branch->br_code' AND comp_code='$newbs_station'  $newbscustflter  AND date_added BETWEEN '$newbs_date_from' AND '$newbs_date_to'");$sno=0;
              while($rownewbs_cfrmbr = $newbs_cfrmbr->fetch(PDO::FETCH_OBJ)){$sno++;
               $newbstcnotes = $db->query("SELECT count(cno) as cnotecounts,SUM(amt) as totalamt FROM credit WHERE cust_code='$rownewbs_cfrmbr->cust_code' AND comp_code='$newbs_station' AND tdate BETWEEN '$newbs_date_from' AND '$newbs_date_to'")->fetch(PDO::FETCH_OBJ);
               if($newbstcnotes->cnotecounts>0){
               echo "<tr>
               <td>$sno</td>
               <td>$rownewbs_branch->br_code</td>
               <td>$rownewbs_branch->br_name</td>
               <td>$rownewbs_cfrmbr->cust_code</td>
               <td>$rownewbs_cfrmbr->cust_name</td>
               <td>$newbstcnotes->cnotecounts</td>
               <td>".number_format($newbstcnotes->totalamt,2)."</td>
               </tr>";
               }
              }
         }
         echo "</table>";
      }
      //generate report for sales report
      if(isset($_POST['newsales_report_type'])){
          header("Content-Disposition: attachment; filename=sales summary-report.xls");  
          $newsales_report_type = $_POST['newsales_report_type'];
          $newsales_report_format = $_POST['newsales_report_format'];
          $newsales_station = $_POST['newsales_station'];
          $newsales_branch = $_POST['newsales_branch'];
          $newsales_customer = $_POST['newsales_customer'];
          $newsales_date_from = $_POST['newsales_date_from'];
          $newsales_date_to = $_POST['newsales_date_to'];
          $newsales_month_from = $_POST['newsales_month_from'];
          $newsales_month_to = $_POST['newsales_month_to'];
          $newsales_year = $_POST['newsales_year'];
          $newsales_user = $_POST['newsales_user'];

          //branch filter
          if(empty($newsales_branch) || $newsales_branch=='ALL'){
               $newsalesbrfilter = "";
          }
          else{
               $newsalesbrfilter = "AND br_code='$newsales_branch'";
          }
          //customerfilter
          if(empty($newsales_customer) || $newsales_customer=='ALL'){
               $newsalescustflter = "";
          }
          else{
               $newsalescustflter = "AND cust_code='$newsales_customer'";
          }
          echo "<table><thead>
          <tr>
          <th>S.no</th>
          <th>Br code</th>
          <th>Br name</th>
          <th>cust code</th>
          <th>cust name</th>
          <th>Total Cnotes</th>
          <th>Total Amount</th>
          </tr>
          </thead>";
          $newsales_branchlist = $db->query("SELECT * FROM branch WHERE stncode='$newsales_station' $newsalesbrfilter AND branchtype='$newsales_report_type'");
         while($rownewsales_branch = $newsales_branchlist->fetch(PDO::FETCH_OBJ)){
              $newsales_cfrmbr = $db->query("SELECT * FROM customer WHERE br_code='$rownewsales_branch->br_code' AND comp_code='$newsales_station'  $newsalescustflter");$sno=0;
              while($rownewsales_cfrmbr = $newsales_cfrmbr->fetch(PDO::FETCH_OBJ)){$sno++;
               $newsalestcnotes = $db->query("SELECT count(cno) as cnotecounts,SUM(amt) as totalamt FROM credit WHERE cust_code='$rownewsales_cfrmbr->cust_code' AND comp_code='$newsales_station' AND tdate BETWEEN '$newsales_date_from' AND '$newsales_date_to'")->fetch(PDO::FETCH_OBJ);
               if($newsalestcnotes->cnotecounts>0){
               echo "<tr>
               <td>$sno</td>
               <td>$rownewsales_branch->br_code</td>
               <td>$rownewsales_branch->br_name</td>
               <td>$rownewsales_cfrmbr->cust_code</td>
               <td>$rownewsales_cfrmbr->cust_name</td>
               <td>$newsalestcnotes->cnotecounts</td>
               <td>".number_format($newsalestcnotes->totalamt,2)."</td>
               </tr>";
               }
              }
         }
         echo "</table>";
      }
      //generate report for sales report
      if(isset($_POST['sales_report_type'])){
          header("Content-Disposition: attachment; filename=sales-report.xls");  
          $sales_report_type = $_POST['sales_report_type'];
          $sales_date_from = $_POST['sales_date_from'];
          $sales_date_to = $_POST['sales_date_to'];
          $sales_branch = $_POST['sales_branch'];
          $sales_customer = $_POST['sales_customer'];
          $sales_station = $_POST['sales_station'];
          if($sales_branch=='ALL' || $sales_branch==''){
              $salesbrfilter = "";
         }
         else{
              $salesbrfilter = "AND br_code='$sales_branch'";
         }
         if($sales_customer=='ALL' || $sales_customer==''){
              $salescustfilter = "";
         }
         else{
              $salescustfilter = "AND cust_code='$sales_customer'";
         }
         if($sales_report_type=='all'){
              $salesserialfilter = "";
              $salserial = "";
         }
         else{
              $salesserialfilter = "AND cnote_serial='$sales_report_type'";
              $salserial = "$sales_report_type";
         }
         echo"<table><thead>
         <tr>
         <th class='align-left' style='min-width:100px'>S.No</th>
         <th class='align-left' style='min-width:100px'>Comp Code</th>
         <th class='align-left' style='min-width:100px'>Br Code</th>
         <th class='align-left' style='min-width:100px'>Cust Code</th>
         <th class='align-left' style='min-width:100px'>Cust Name</th>
         <th class='align-left' style='min-width:100px'>Invoice Date</th>
         <th class='align-left' style='min-width:100px'>Invoice No</th>
         <th class='align-left' style='min-width:100px'>Opening No</th>
         <th class='align-left' style='min-width:100px'>Closing No</th>
         <th class='align-left' style='min-width:100px'>Qty</th>
         <th class='align-left' style='min-width:100px'>Chq Amt</th>
         <th class='align-left' style='min-width:100px'>Cash Amt</th>
         <th class='align-left' style='min-width:100px'>Deposit Amt</th>
         <th class='align-left' style='min-width:100px'>Cheque No/Rcpt No/Deposit By</th>
         <th class='align-left' style='min-width:100px'>Stationary Charge</th>
         <th class='align-left' style='min-width:100px'>Consignment Charge</th>
         <th class='align-left' style='min-width:100px'>Partial Transhipment Charge</th>
         <th class='align-left' style='min-width:100px'>Grand Amount</th>
         <th class='align-left' style='min-width:50px'>CGST</th>
         <th class='align-left' style='min-width:50px'>SGST</th>
         <th class='align-left' style='min-width:50px'>IGST</th>
         <th class='align-right' style='min-width:100px'>Net Amt</th>
         <th class='align-right' style='min-width:100px'>GST No</th>
         </tr>
         </thead><tbody>";
         $salescusts = $db->query("SELECT stncode,br_code,
         cust_code,cust_name,date_added,salesorderid,cnote_procured,pMode,NetAmt,RcptNo,CNote_Stationary_Charge,Consignment_Charge,GST,inv_no,cnote_start,cnote_end,
         (SELECT cust_gstin FROM customer WHERE comp_code=cnotesales.stncode AND cust_code=cnotesales.cust_code) gstnumber
         ,((convert(int,cnote_end)-convert(int,cnote_start))+1) actual_count
         ,(SELECT cust_name FROM customer WHERE cust_code=cnotesales.cust_code AND comp_code=cnotesales.stncode) actual_custname
         ,(SELECT stn_gstin FROM station WHERE stncode=cnotesales.stncode) stngstnumber FROM cnotesales WHERE flag = 1 AND stncode='$sales_station' AND date_added BETWEEN '$sales_date_from' AND '$sales_date_to' $salesserialfilter $salesbrfilter $salescustfilter ORDER BY date_added");$sno=0;
         while($salescrow = $salescusts->fetch(PDO::FETCH_OBJ)){$sno++;
              // $totcnos = $db->query("SELECT SUM(CNote_Stationary_Charge) as salesamt,SUM(GST) as salesgst,SUM(GrandAmt) as salesgrand,SUM(NetAmt) as salesnet FROM cnotesales WHERE stncode='$sales_station' $salesserialfilter AND cust_code='$salescrow->cust_code' AND date_added BETWEEN '$sales_date_from' AND '$sales_date_to'")->fetch(PDO::FETCH_OBJ);
              $salesclosingno = $db->query("SELECT cnote_end FROM cnotesalesdetail WHERE stncode='$sales_station' AND salesorderid='$salescrow->salesorderid'")->fetch(PDO::FETCH_OBJ);

              echo "<tr>
              <td>".$sno."</td>  
              <td>".$salescrow->stncode."</td>  
              <td>".$salescrow->br_code."</td>
              <td>".$salescrow->cust_code."</td>
              <td>".$salescrow->actual_custname."</td>
              <td>".date("d-m-Y", strtotime($salescrow->date_added))."</td>
              <td>".(!empty($salescrow->salesorderid)?$salescrow->salesorderid:$salescrow->inv_no)."</td>
              <td>".$salescrow->cnote_start."</td>
              <td>".(!empty($salescrow->cnote_end)?$salescrow->cnote_end:$salesclosingno->cnote_end)."</td> 
              <td>".(!empty($salescrow->cnote_procured)?$salescrow->cnote_procured:$salescrow->actual_count)."</td>
              <td>".($salescrow->pMode=='cheque'? number_format($salescrow->NetAmt,2):'0.00')."</td>
              <td>".(($salescrow->pMode!='card')&&($salescrow->pMode!='cheque')? number_format($salescrow->NetAmt,2):'0.00')."</td>
              <td>".($salescrow->pMode=='card'? number_format($salescrow->NetAmt,2):'0.00')."</td>
              <td>$salescrow->RcptNo</td>
              <td style='text-align:left'>".number_format($salescrow->CNote_Stationary_Charge,2)."</td>
              <td style='text-align:left'>".number_format($salescrow->Consignment_Charge,2)."</td>
              <td>0.00</td>
              <td style='text-align:left'>".number_format($salescrow->CNote_Stationary_Charge,2)."</td>
              <td>".((substr($salescrow->gstnumber,0,2)==substr($salescrow->stngstnumber,0,2))?number_format($salescrow->GST/2,2):'')."</td>
              <td>".((substr($salescrow->gstnumber,0,2)==substr($salescrow->stngstnumber,0,2))?number_format($salescrow->GST/2,2):'')."</td>
              <td>".((substr($salescrow->gstnumber,0,2)!=substr($salescrow->stngstnumber,0,2))?number_format($salescrow->GST,2):'')."</td>
              <td style='text-align:right'>".number_format($salescrow->NetAmt,2)."</td>
              <td style='text-align:right'>".$salescrow->gstnumber."</td>
              </tr>";
         }
         echo "</tbody></table>";
      }
         //generate report for tonnage
         if(isset($_POST['ton_reporttype'])){
          $ton_reporttype = $_POST['ton_reporttype'];
          $ton_month_from = $_POST['ton_month_from'];
          $ton_month_to = $_POST['ton_month_to'];
          $ton_year = $_POST['ton_year'];
          $ton_stn = $_POST['ton_stn'];
          echo "<thead>
                <tr>
                <th class='align-left'>Zone</th>";
                for($i=$ton_month_from;$i<=$ton_month_to;$i++){
                echo "<th class='align-left'>$months[$i]</th>";
                }
                echo "</tr>
                </thead><tbody>";

          //fetchzones
          $ton_zonecodes = $db->query("SELECT zone_name FROM zone WHERE comp_code='$ton_stn' and zone_type='credit' and zone_code IN('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','18')");
          while($tonzrow = $ton_zonecodes->fetch(PDO::FETCH_OBJ)){
               echo "<tr>
               <td style='width:200px'>".strtok($tonzrow->zone_name,'(')."</td>";
               for($i=$ton_month_from;$i<=$ton_month_to;$i++){
                    //calculate percentage
                    $getzonecode = $db->query("SELECT zone_code FROM zone WHERE zone_name='$tonzrow->zone_name' AND comp_code='$ton_stn' AND zone_type='credit'")->fetch(PDO::FETCH_OBJ);
                    $tontotalinmonth = $db->query("SELECT COUNT(cnote_number) as volume FROM cnotenumber WHERE bilzone='$getzonecode->zone_code' AND cnote_station='$ton_stn' AND MONTH(bdate)='$i'")->fetch(PDO::FETCH_OBJ);
                    echo "<th class='align-left'>$tontotalinmonth->volume</th>";
                    }
               echo "</tr>";
          }
          echo "</tbody>";
      }
     //generate report for deviation report
     if(isset($_POST['dev_reporttype'])){
          header("Content-Disposition: attachment; filename=deviation-report.xls");  
          $dev_reporttype = $_POST['dev_reporttype'];
          $dev_date_from = $_POST['dev_date_from'];
          $dev_date_to = $_POST['dev_date_to'];
          $dev_stncode = $_POST['dev_stncode'];
          if($dev_reporttype=='weightdeviation'){
          echo "<table><thead><tr>
          <th>S.no</th>
          <th>Branch</th>
          <th>Customer</th>
          <th>CnoteNumber</th>
          <th>Bill date</th>
          <th style='text-align:right;'>Bill Wt</th>
          <th style='text-align:right;'>Op Wt</th>
          <th style='text-align:right;'>Bill Rate</th>
          </tr><tbody>";
          $sno=0;
          $wtdeviation = $db->query("SELECT * FROM cnotenumber WHERE opwt IS NOT NULL AND bilwt IS NOT NULL AND cast(bilwt as money)!=cast(opwt as money) AND cnote_station='$dev_stncode' AND bdate BETWEEN '$dev_date_from' AND '$dev_date_to'");
          while($rowwtdev = $wtdeviation->fetch(PDO::FETCH_OBJ))
          {
          
                    $sno++;
               echo "<tr>
               <td>$sno</td>
               <td>$rowwtdev->br_code</td>
               <td>$rowwtdev->cust_code</td>
               <td>$rowwtdev->cnote_number</td>
               <td>".date("d-m-Y", strtotime($rowwtdev->bdate))."</td>               
               <td style='text-align:right;width:100px;'>".number_format($rowwtdev->bilwt,3)."</td>
               <td style='text-align:right;width:100px;'>".number_format($rowwtdev->opwt,3)."</td>
               <td style='text-align:right;width:100px;'>".number_format($rowwtdev->bilrate,2)."</td>
               </tr>";          
          }
          echo "</tbody></table>";
     }
     }
     
            //gst report generation
            if(isset($_POST['gstreporttype'])){
               header("Content-Disposition: attachment; filename=gst-report.xls");  
               $gst_date_from = $_POST['gst_date_from'];
               $gst_date_to = $_POST['gst_date_to'];
               $gstreporttype = $_POST['gstreporttype'];
               $gst_stn = $_POST['gst_stn'];
               $gst_branch = $_POST['gst_branch'];
               $gst_customer = $_POST['gst_customer'];
    
               if(empty($gst_branch)){
                    $gst_brcondition = "";
               }
               else{
                    $gst_brcondition = " AND br_code='$gst_branch'";
               }
               if(empty($gst_customer)){
                    $gst_custcondition = "";
               }
               else{
                    $gst_custcondition = " AND cust_code='$gst_customer'";
               }
    
               if($gstreporttype=='creditb2b'){
               echo "<table><tr>
               <th>S.no</th>
               <th style='min-width:100px'>GSTIN/UIN of Receipient</th>
               <th  style='min-width:100px' >Invoice Number</th>
               <th  style='min-width:100px' >Invoice Date</th>
               <th  style='min-width:100px'  class='align-right'>Invoice Value</th>
               <th  style='min-width:100px' >Place of Supply</th>
               <th  style='min-width:100px' >Reverse Charge</th>
               <th  style='min-width:100px' >Invoice Type</th>
               <th  style='min-width:100px' >E-Commerce GSTIN</th>
               <th  style='min-width:100px' >Rate</th>
               <th  style='min-width:100px' >Taxable Value</th>
               <th  style='min-width:100px' >Cess</th>
               <th  style='min-width:100px'  class='align-right'>IGST</th>
               <th  style='min-width:100px'  class='align-right'>CGST</th>
               <th  style='min-width:100px'  class='align-right'>SGST</th>
               <th  style='min-width:100px' >Customer Name</th>
               </tr>";
               $b2binvoice = $db->query("SELECT * FROM invoice WHERE invdate BETWEEN '$gst_date_from' AND '$gst_date_to' $gst_brcondition $gst_custcondition AND comp_code='$gst_stn' AND ISNULL(LEN((select cust_gstin from customer where cust_code=invoice.cust_code and comp_code=invoice.comp_code)),0)>0");
               $sno=0;
              while($b2brow = $b2binvoice->fetch(PDO::FETCH_OBJ)){$sno++;
                   $gstgst = $db->query("SELECT cust_gstin,cust_name FROM customer WHERE cust_code='$b2brow->cust_code' AND comp_code='$gst_stn'")->fetch(PDO::FETCH_OBJ);
                   echo "<tr>
                   <td>$sno</td>
                   <td>$gstgst->cust_gstin</td>
                   <td>$b2brow->invno</td>
                   <td>".date("d-m-Y", strtotime($b2brow->invdate))."</td>
                   <td class='align-right'>".number_format($b2brow->netamt,2)."</td>
                   <td>$b2brow->tds</td>
                   <td>N</td>
                   <td>Regular</td>
                   <td></td>
                   <td>18</td>
                   <td class='align-right'>".number_format($b2brow->invamt,2)."</td>
                   <td>0.00</td>
                   <td class='align-right'>".number_format($b2brow->igst,2)."</td>
                   <td class='align-right'>".number_format($b2brow->cgst,2)."</td>
                   <td class='align-right'>".number_format($b2brow->sgst,2)."</td>
                   <td>$gstgst->cust_name</td>
                   </tr>";
              }
    
               }
               if($gstreporttype=='cnotesles_gst_b2b'){
               echo "<table><tr>
               <th>S.no</th>
               <th style='min-width:100px'>GSTIN/UIN of Receipient</th>
               <th  style='min-width:100px' >Invoice Number</th>
               <th  style='min-width:100px' >Invoice Date</th>
               <th  style='min-width:100px'  class='align-right'>Invoice Value</th>
               <th  style='min-width:100px' >Invoice Type</th>
               <th  style='min-width:100px' >E-Commerce GSTIN</th>
               <th  style='min-width:100px' >Rate</th>
               <th  style='min-width:100px' >Taxable Value</th>
               <th  style='min-width:100px' >Cess</th>
               <th  style='min-width:100px'  class='align-right'>IGST</th>
               <th  style='min-width:100px'  class='align-right'>CGST</th>
               <th  style='min-width:100px'  class='align-right'>SGST</th>
               <th  style='min-width:100px' >Customer Name</th>
               </tr>";
               $sales_gst_comp_gstn = $db->query("SELECT stn_gstin FROM station WHERE stncode='$gst_stn'")->fetch(PDO::FETCH_OBJ);
               $sales_gst = $db->query("SELECT cnotesalesid
               ,salesorderid
               ,stncode
               ,stnname
               ,br_code
               ,br_name
               ,cust_code
               ,cust_name
               ,cnote_type
               ,cnote_serial
               ,cnote_count
               ,inv_no
               ,inv_date
               ,CNote_Stationary_Charge
               ,Consignment_Charge
               ,GST
               ,GrandAmt
               ,NetAmt
               ,cnote_start
               ,cnote_end
               ,cnote_status
               ,cnote_procured
               ,cnote_void
               ,cnote_charge
               ,pMode
               ,RcptNo
               ,flag
               ,Rate_CNote_Stationary
               ,Rate_CNote_Advance
               ,date_added
               ,date_modified
               ,user_name
               ,(SELECT cust_name FROM customer WHERE cust_code=cnotesales.cust_code AND comp_code=cnotesales.stncode) actual_custname
               ,(SELECT cust_gstin FROM customer WHERE cust_code=cnotesales.cust_code AND comp_code=cnotesales.stncode) cust_gstin FROM cnotesales WHERE date_added BETWEEN '$gst_date_from' AND '$gst_date_to' AND stncode='$gst_stn' $gst_brcondition $gst_custcondition AND ISNULL(LEN((select cust_gstin from customer where cust_code=cnotesales.cust_code and comp_code=cnotesales.stncode)),0)>0");
               $sno=0;
              while($sales_gstrow = $sales_gst->fetch(PDO::FETCH_OBJ)){$sno++;
                   echo "<tr>
                   <td>$sno</td>
                   <td>$sales_gstrow->cust_gstin</td>
                   <td>".(!empty($sales_gstrow->salesorderid)?$sales_gstrow->salesorderid:$sales_gstrow->inv_no)."</td>
                   <td>".date("d-m-Y", strtotime($sales_gstrow->date_added))."</td>
                   <td class='align-right'>".number_format($sales_gstrow->NetAmt,2)."</td>
                   <td>Regular</td>
                   <td></td>
                   <td>18</td>
                   <td class='align-right'>".number_format($sales_gstrow->GrandAmt,2)."</td>
                   <td>0.00</td>
                   <td class='align-right'>".((substr($sales_gst_comp_gstn->stn_gstin,0,2)!=substr($sales_gstrow->cust_gstin,0,2)) ? number_format($sales_gstrow->GST,2) : '0.00' )."</td>
                   <td class='align-right'>".((substr($sales_gst_comp_gstn->stn_gstin,0,2)==substr($sales_gstrow->cust_gstin,0,2)) ? number_format($sales_gstrow->GST/2,2) : '0.00' )."</td>
                   <td class='align-right'>".((substr($sales_gst_comp_gstn->stn_gstin,0,2)==substr($sales_gstrow->cust_gstin,0,2)) ? number_format($sales_gstrow->GST/2,2) : '0.00' )."</td>
                   <td>$sales_gstrow->actual_custname</td>
                   </tr>";
              }
              echo "</table>";
               }
               if($gstreporttype=='cnotesles_gst_b2cs'){
               echo "<table><tr>
               <th>S.no</th>
               <th style='min-width:100px'>GSTIN/UIN of Receipient</th>
               <th  style='min-width:100px' >Invoice Number</th>
               <th  style='min-width:100px' >Invoice Date</th>
               <th  style='min-width:100px'  class='align-right'>Invoice Value</th>
               <th  style='min-width:100px' >Invoice Type</th>
               <th  style='min-width:100px' >E-Commerce GSTIN</th>
               <th  style='min-width:100px' >Rate</th>
               <th  style='min-width:100px' >Taxable Value</th>
               <th  style='min-width:100px' >Cess</th>
               <th  style='min-width:100px'  class='align-right'>IGST</th>
               <th  style='min-width:100px'  class='align-right'>CGST</th>
               <th  style='min-width:100px'  class='align-right'>SGST</th>
               <th  style='min-width:100px' >Customer Name</th>
               </tr>";
               $sales_gst_comp_gstn = $db->query("SELECT stn_gstin FROM station WHERE stncode='$gst_stn'")->fetch(PDO::FETCH_OBJ);
               $sales_gst = $db->query("SELECT cnotesalesid
               ,salesorderid
               ,stncode
               ,stnname
               ,br_code
               ,br_name
               ,cust_code
               ,cust_name
               ,cnote_type
               ,cnote_serial
               ,cnote_count
               ,inv_no
               ,inv_date
               ,CNote_Stationary_Charge
               ,Consignment_Charge
               ,GST
               ,GrandAmt
               ,NetAmt
               ,cnote_start
               ,cnote_end
               ,cnote_status
               ,cnote_procured
               ,cnote_void
               ,cnote_charge
               ,pMode
               ,RcptNo
               ,flag
               ,Rate_CNote_Stationary
               ,Rate_CNote_Advance
               ,date_added
               ,date_modified
               ,user_name
               ,(SELECT cust_name FROM customer WHERE cust_code=cnotesales.cust_code AND comp_code=cnotesales.stncode) actual_custname
               ,(SELECT cust_gstin FROM customer WHERE cust_code=cnotesales.cust_code AND comp_code=cnotesales.stncode) cust_gstin FROM cnotesales WHERE date_added BETWEEN '$gst_date_from' AND '$gst_date_to' AND stncode='$gst_stn' AND ISNULL(LEN((select cust_gstin from customer where cust_code=cnotesales.cust_code and comp_code=cnotesales.stncode)),0)!>0");
               $sno=0;
              while($sales_gstrow = $sales_gst->fetch(PDO::FETCH_OBJ)){$sno++;
                   echo "<tr>
                   <td>$sno</td>
                   <td>$sales_gstrow->cust_gstin</td>
                   <td>".(!empty($sales_gstrow->salesorderid)?$sales_gstrow->salesorderid:$sales_gstrow->inv_no)."</td>
                   <td>".date("d-m-Y", strtotime($sales_gstrow->date_added))."</td>
                   <td class='align-right'>".number_format($sales_gstrow->NetAmt,2)."</td>
                   <td>Regular</td>
                   <td></td>
                   <td>18</td>
                   <td class='align-right'>".number_format($sales_gstrow->GrandAmt,2)."</td>
                   <td>0.00</td>
                   <td class='align-right'>".((substr($sales_gst_comp_gstn->stn_gstin,0,2)!=substr($sales_gstrow->cust_gstin,0,2)) ? number_format($sales_gstrow->GST,2) : '0.00' )."</td>
                   <td class='align-right'>".((substr($sales_gst_comp_gstn->stn_gstin,0,2)==substr($sales_gstrow->cust_gstin,0,2)) ? number_format($sales_gstrow->GST/2,2) : '0.00' )."</td>
                   <td class='align-right'>".((substr($sales_gst_comp_gstn->stn_gstin,0,2)==substr($sales_gstrow->cust_gstin,0,2)) ? number_format($sales_gstrow->GST/2,2) : '0.00' )."</td>
                   <td>$sales_gstrow->actual_custname</td>
                   </tr>";
              }
              echo "</table>";
               }
               if($gstreporttype=='creditb2cs'){
               echo "<table><tr>
               <th>S.no</th>
               <th>TYPE</th>
               <th>Place of Supply</th>
               <th>Rate</th>
               <th>Total Taxable Value</th>
               <th>Cess Amount</th>
               <th>E-Commerce GSTIN</th>
               <th>IGST</th>
               <th>CGST</th>
               <th>SGST</th>
               <th>Invoice Number</th>
               <th>Invoice Date</th>
               </tr>";
               $b2csinvoice = $db->query("SELECT * FROM invoice WHERE invdate BETWEEN '$gst_date_from' AND '$gst_date_to' AND comp_code='$gst_stn' AND ISNULL(LEN((select cust_gstin from customer where cust_code=invoice.cust_code and comp_code=invoice.comp_code)),0)!>0");
               $sno=0;
              while($b2csrow = $b2csinvoice->fetch(PDO::FETCH_OBJ)){$sno++;
                   $gstgst = $db->query("SELECT cust_gstin,cust_name FROM customer WHERE cust_code='$b2brow->cust_code' AND comp_code='$gst_stn'")->fetch(PDO::FETCH_OBJ);
                   echo "<tr>
                   <td>$sno</td>
                   <td>OE</td>
                   <td></td>
                   <td>18</td>
                   <td>".number_format($b2csrow->netamt,2)."</td>
                   <td>0.00</td>
                   <td></td>
                   <td>".number_format($b2csrow->igst,2)."</td>
                   <td>".number_format($b2csrow->cgst,2)."</td>
                   <td>".number_format($b2csrow->sgst,2)."</td>
                   <td>$b2csrow->invno</td>
                   <td>".date("d-m-Y", strtotime($b2csrow->invdate))."</td>
                   </tr>";
              }
    
               }
             
               if($gstreporttype=='gstsummary'){
               echo "<table><tr>
               <th>S.no</th>
               <th>Nature of Document</th>
               <th>Br code</th>
               <th>Sr No From</th>
               <th>Sr No To</th>
               <th>Total Number</th>
               <th>Cancelled</th>
               </tr>";
              $gstsummary = $db->query("SELECT br_code FROM branch WHERE stncode='$gst_stn'");
              $sno=0;
              while($gstsummaryrow = $gstsummary->fetch(PDO::FETCH_OBJ)){$sno++;
                   //gst invoice summary
                   $sr_no_frm = $db->query("SELECT TOP(1) invno FROM invoice WHERE br_code='$gstsummaryrow->br_code' AND comp_code='$gst_stn' AND invdate BETWEEN '$gst_date_from' AND '$gst_date_to' ORDER BY invoice_id")->fetch(PDO::FETCH_OBJ);
                   $sr_no_to = $db->query("SELECT TOP(1) invno FROM invoice WHERE  br_code='$gstsummaryrow->br_code' AND comp_code='$gst_stn' AND invdate BETWEEN '$gst_date_from' AND '$gst_date_to' ORDER BY invoice_id DESC")->fetch(PDO::FETCH_OBJ);
                   $totalgstinvcounts = $db->query("SELECT count(*) as gstinvcount FROM invoice WHERE  br_code='$gstsummaryrow->br_code' AND comp_code='$gst_stn' AND invdate BETWEEN '$gst_date_from' AND '$gst_date_to'")->fetch(PDO::FETCH_OBJ);
                   //gst invoice summary
                   //gst sales summary
                   $s_gst_smry_frm = $db->query("SELECT TOP(1) salesorderid FROM cnotesales WHERE br_code='$gstsummaryrow->br_code' AND stncode='$gst_stn' AND date_added BETWEEN '$gst_date_from' AND '$gst_date_to' AND cnote_charge>0 ORDER BY salesorderid")->fetch(PDO::FETCH_OBJ);
                   $s_gst_smry_to = $db->query("SELECT TOP(1) salesorderid FROM cnotesales WHERE br_code='$gstsummaryrow->br_code' AND stncode='$gst_stn' AND date_added BETWEEN '$gst_date_from' AND '$gst_date_to' AND cnote_charge>0 ORDER BY salesorderid DESC")->fetch(PDO::FETCH_OBJ);
                   $s_gst_smry_ct = $db->query("SELECT count(*) as salescount FROM cnotesales WHERE br_code='$gstsummaryrow->br_code' AND stncode='$gst_stn' AND date_added BETWEEN '$gst_date_from' AND '$gst_date_to' AND cnote_charge>0 ")->fetch(PDO::FETCH_OBJ);
                   //gst sales summary
                   if($totalgstinvcounts->gstinvcount>0)
                   {
                   echo "<tr>
                   <td>$sno</td>
                   <td>Invoice for outward supply</td>
                   <td>$gstsummaryrow->br_code</td>
                   <td>$sr_no_frm->invno</td>
                   <td>$sr_no_to->invno</td>
                   <td>$totalgstinvcounts->gstinvcount</td>
                   <td>0</td>
                   </tr>";
                   }
                   if($s_gst_smry_ct->salescount>0)
                   {
                        echo "<tr>
                        <td>$sno</td>
                        <td>CN sales summary</td>
                        <td>$gstsummaryrow->br_code</td>
                        <td>$s_gst_smry_frm->salesorderid</td>
                        <td>$s_gst_smry_to->salesorderid</td>
                        <td>$s_gst_smry_ct->salescount</td>
                        <td>0</td>
                        </tr>";
                   }
                    }
    
               }
               if($gstreporttype=='cashb2cs')
               {
                   echo "<table><tr>
                   <th>S.no</th>
                   <th>TYPE</th>
                   <th>Place of Supply</th>
                   <th>Rate</th>
                   <th class='align-right'>Total Taxable Value</th>
                   <th class='align-right'>Cess Amount</th>
                   <th>E-Commerce GSTIN</th>
                   <th class='align-right'>IGST</th>
                   <th class='align-right'>CGST</th>
                   <th class='align-right'>SGST</th>
                   <th>Invoice Number</th>
                   <th>Invoice Date</th>
                   </tr>";
                  $cashb2csinvoice = $db->query("SELECT * FROM cash WHERE tdate BETWEEN '$gst_date_from' AND '$gst_date_to' AND comp_code='$gst_stn'");
                  $sno=0;
                  while($cashb2csinvoicerow = $cashb2csinvoice->fetch(PDO::FETCH_OBJ)){$sno++;
                       echo "<tr>
                       <td>$sno</td>
                       <td>OE</td>
                       <td></td>
                       <td>18</td>
                       <td class='align-right'>".number_format($cashb2csinvoicerow->amt,2)."</td>
                       <td class='align-right'>0.00</td>
                       <td></td>
                       <td class='align-right'>0</td>
                       <td class='align-right'>".number_format((($cashb2csinvoicerow->amt/100)*9),2)."</td>
                       <td class='align-right'>".number_format((($cashb2csinvoicerow->amt/100)*9),2)."</td>
                       <td>$cashb2csinvoicerow->cno</td>
                       <td>".date("d-m-Y", strtotime($cashb2csinvoicerow->tdate))."</td>
                       </tr>";
                  }
                  echo "</table>";
              }
               if($gstreporttype=='cashb2b')
               {
                   echo "<table><tr>
                   <th>S.no</th>
                   <th>TYPE</th>
                   <th>Place of Supply</th>
                   <th>Rate</th>
                   <th class='align-right'>Total Taxable Value</th>
                   <th class='align-right'>Cess Amount</th>
                   <th>E-Commerce GSTIN</th>
                   <th class='align-right'>IGST</th>
                   <th class='align-right'>CGST</th>
                   <th class='align-right'>SGST</th>
                   <th>Invoice Number</th>
                   <th>Invoice Date</th>
                   </tr>";
                  $cashb2csinvoice = $db->query("SELECT * FROM cash WHERE edate BETWEEN '$gst_date_from' AND '$gst_date_to' AND comp_code='$gst_stn'");
                  $sno=0;
                  while($cashb2csinvoicerow = $cashb2csinvoice->fetch(PDO::FETCH_OBJ)){$sno++;
                       echo "<tr>
                       <td>$sno</td>
                       <td>OE</td>
                       <td></td>
                       <td>18</td>
                       <td class='align-right'>".number_format($cashb2csinvoicerow->amt,2)."</td>
                       <td class='align-right'>0.00</td>
                       <td></td>
                       <td class='align-right'>0</td>
                       <td class='align-right'>".number_format((($cashb2csinvoicerow->amt/100)*9),2)."</td>
                       <td class='align-right'>".number_format((($cashb2csinvoicerow->amt/100)*9),2)."</td>
                       <td>$cashb2csinvoicerow->cno</td>
                       <td>".date("d-m-Y", strtotime($cashb2csinvoicerow->edate))."</td>
                       </tr>";
                  }
                  echo "</table>";
              }
          }