<?php 
/* File name   : report controller.php
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

	 class reportcontroller extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - fetch';
             // $this->view->render('cnote/fetch');
          }
      }

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

      //Fetch Columns 
      if(isset($_POST['tbname'])){
           $tbname = $_POST['tbname'];
           $columns = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$tbname'");
           $i=0;
           while($rowcolumn = $columns->fetch(PDO::FETCH_OBJ))
           {    $i++;
                echo "<tr><td>$i</td><td>$rowcolumn->COLUMN_NAME</td><td><input type='checkbox' class='column_select' id='$rowcolumn->COLUMN_NAME'></td></tr>";
           }
      }
      //Generate Data 
      if(isset($_POST['std_columns'])){
           $std_columns = $_POST['std_columns'];
           $tblname = $_POST['tblname'];
           $datas = $db->query("SELECT $std_columns FROM $tblname");
           $headers = explode(",",$std_columns);
           
           echo "<thead><tr>";
           foreach($headers as $header){
           echo  "<th class='align-left'>".$header."</th>";
           }
           echo "</tr></thead>";
            $j = 0;
            echo "<tbody>";
           while($rowdata = $datas->fetch(PDO::FETCH_OBJ)){
             echo "<tr>";
             foreach($headers as $header){
               echo  "<td>".$rowdata->$header."</td>";
               }
             echo "</tr>";
             $j++;
           }
           echo "</tbody>";
      }
      //generate report for query report
      if(isset($_POST['sql_query'])){
           $sql_query = $_POST['sql_query'];
           $q_fields = explode(",",$_POST['q_fields']);
           $tables = $db->query($sql_query);
           echo "<tr>";
           foreach($q_fields as $theaders){
                echo "<th class='align-left'>".$theaders."</th>";
           }
           while($sqlqueryrow = $tables->fetch(PDO::FETCH_OBJ)){          
                echo "</tr>";
                echo "<tr>";
                    foreach($q_fields as $qfield){
                echo "<td>".$sqlqueryrow->$qfield."</td>";
                    }
                echo "</tr>";

           }
      }
      //generate report for booking reportcontroller
      if(isset($_POST['book_date_from'])){
          try{
          $book_date_from = $_POST['book_date_from'];
          $book_date_to = $_POST['book_date_to'];
          $weight_from = $_POST['weight_from'];
          $weight_to = $_POST['weight_to'];
          $bk_branch = $_POST['br_brcode'];
          $bk_customer = $_POST['br_custcode'];
          $book_destination = $_POST['destination'];
          $bkrpt_podorigin = $_POST['bkrpt_podorigin'];
          $book_zone = $_POST['zone'];
          $book_rep_type = $_POST['book_rep_type'];
          $bookstn = $_POST['bookstn'];
          $current_station = $_POST['current_station'];
          $cc_code = $_POST['cc_code'];
		$station = $_POST['station'];   
		$payment_mode = $_POST['payment_mode'];   
          $exbainbound  = $_POST['exbainbound'];
          $excustomer   = $_POST['excustomer'];

          //   echo json_encode($_POST['book_rep_type']);
          //   exit();

		  if($book_rep_type=='CCbookingSummary')
          {

               $date = "cast(tdate as date) BETWEEN '$book_date_from' AND '$book_date_to'";
               $bdate ="bdate BETWEEN '$book_date_from' AND '$book_date_to'";
               $tdate ="tdate BETWEEN '$book_date_from' AND '$book_date_to'";
               
             
               if($bk_branch == 'ALL'){
                   
               }else{
                    $book_conditions[] = "AND  stncode='$current_station' and br_code='$bk_branch'";
               }
               // if($current_station == $current_station){
                   
               // }else{
               //      $book_conditions[] = "br_code='$current_station'";
               // }
               if($cc_code=='ALL'){
                   
               }else{
                    $book_conditions[] = "cc_code='$cc_code'";
               }
             
                 
               $book_condition = implode(' AND ', $book_conditions);   

               //distinct

               $booking_report = $db->query("SELECT DISTINCT cc_code,COUNT(cc_code), cnote_serial,(select sum(ramt) from cash as c where c.cc_code=cc.cc_code and c.cnote_serial=cc.cnote_serial and c.status='Billed' and $tdate) as amount,
               (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial  and $bdate order by cnote_number asc) as cstar,
               (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial and $bdate order by cnote_number desc) as cend,
               (select count(cnote_number) from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cnote_serial and cn.cnote_status LIKE '%-void' AND cnote_number between (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial  and $bdate order by cnote_number asc) and (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial  and $bdate order by cnote_number desc)) as cancel,
               (select count(cnote_number) from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cnote_serial and cn.cnote_status='Billed'  AND cnote_number between (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial and $bdate order by cnote_number asc) and (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial  and $bdate order by cnote_number desc) and $bdate) as billed,
             (select count(cnote_number) from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cnote_serial and cn.cnote_status='Allotted'  AND cnote_number between (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial and $bdate order by cnote_number asc) and (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial  and $bdate order by cnote_number desc)) as miss
             FROM cnoteallocationccdetail as cc WHERE (select top 1 cnote_number from cnotenumber as cn where cn.cust_code=cc.cc_code and cn.cnote_serial=cc.cnote_serial  and $bdate order by cnote_number asc)  is not null $book_condition
             GROUP BY cc_code,cnote_serial ");
                //console_log($booking_report);


              $b_sno=0;
              echo " <thead>
              <tr>
                   <th style='text-align:left'>S.no</th>
                   <th style='text-align:left'>Code no</th>";
                   echo "<th style='text-align:left'>Start CNo</th>
                   <th style='text-align:left'>End CNo</th>
                   <th style='text-align:left'> CL </th>
                   <th style='text-align:left'>TOTAL</th>
                   <th style='text-align:left'>Miss</th>
                   <th style='text-align:left'>Amount</th>
                 ";
              echo "</tr>
              </thead>";
              while($bkrow = $booking_report->fetch(PDO::FETCH_OBJ)){ $b_sno++;
                  echo "<tr>";
                    echo "<td>".$b_sno."</td>";
                      echo "<td>".$bkrow->cc_code."</td>";
                      echo "<td>".$bkrow->cstar."</td>";
                      echo "<td>".$bkrow->cend."</td>";
                      echo "<td class='cancel'>".$bkrow->cancel."</td>";
                      echo "<td class='total'>".$bkrow->billed."</td>";
                     echo "<td class='loop'>".$bkrow->miss."</td>";
                      echo "<td class='miss'>".rtrim($bkrow->amount, "0")."00</td>";
                  
              }
              echo "</tr>
              <tr>
              <td></td>
                   <td></td>";                       
                   echo "<td> </td>
                   <td ><b>Total</b></td>
                   <td id='cancel1'><b></b></td>
                   <td id='total1'><b></b></td>
                   <td id='Amount1'><b></b></td>                  
                   <td id='miss1'><b></b></td>
              
              </tr>";
              exit();
          }
          if($book_rep_type=='CCpendingSummary')
          {
               if(!empty($book_date_from)){
                    $book_conditions[] = "date_added  BETWEEN '$book_date_from' AND '$book_date_to'";
               }
               if(!empty($weight_from)){
                   $book_conditions[] = "wt BETWEEN '$weight_from' AND '$weight_to'";
               }
               if($bk_branch == 'ALL'){
                   
               }else{
                    $book_conditions[] = "br_code='$bk_branch'";
               }


               if($cc_code=='ALL'){
                   
               }else{
                    $book_conditions[] = "cust_code='$cc_code'";
               }
               $book_condition = implode(' AND ', $book_conditions);
 
               $select_cnote = $db->query("SELECT * FROM cnotenumber WHERE $book_condition AND cnote_status = 'Allotted' AND cnote_station='$current_station' AND cust_code in (select cc_code from cnoteallocationccdetail as cn where cn.cc_code=cnotenumber.cust_code and cn.cnotepono=cnotenumber.allot_po_no)");
            
           //console_log($select_cnote);

              $b_sno=0;
              echo " <thead>
              <tr>
                   <th style='text-align:left'>S.no</th>
                   <th style='text-align:left'>Allot_date</th>;
				   <th style='text-align:left'>Branch Code</th>;
                   <th style='text-align:left'>CC_code</th>";
                   echo "<th style='text-align:left'>Cnote Number</th>";
                 
                
              echo "</tr>
              </thead>";
              while($bkrow = $select_cnote->fetch(PDO::FETCH_OBJ)){ $b_sno++;
                  echo "<tr>
                       <td>".$b_sno."</td>
                       <td>".date("d-M-Y",strtotime($bkrow->allot_date))."</td>;
					   <td>".$bkrow->br_code."</td>;
                       <td>".$bkrow->cust_code."</td>";
                            echo "<td>".$bkrow->cnote_number."</td>";
                      
                  echo "</tr>";




                  
              }
          //     echo "</tr>
          //     <tr>
          //     <td></td>
          //          <td></td>";
          //               echo "<td></td>";
          //          echo "<td> </td>
          //          <td></td>
                 
          //          <td></td>
                          
          //          <td></td>
              
          //     </tr>";
              exit();
          }
          if($book_rep_type=='gta_report')
          {
  
               if($bk_branch == 'ALL'){
                   
               }else{
                    $book_conditions[] = "AND br_code='$bk_branch'";
               }

               
               $book_condition = implode(' AND ', $book_conditions);
               
               $total_amt = $db->query("SELECT sum(amt) as amount FROM cash c WHERE (SELECT gta FROM station cu WHERE cu.comp_code=c.comp_code)='Y' and c.mode_code='ST' AND (gst!='' and gst is not null) AND CONVERT(DECIMAL(10,2), 0.5)<=wt AND cast(tdate as date) BETWEEN '$book_date_from' AND '$book_date_to' AND comp_code='$current_station'  $book_condition")->fetch(PDO::FETCH_OBJ);   
               $total_ramt = $db->query("SELECT sum(ramt) as amount FROM cash c WHERE (SELECT gta FROM station cu WHERE cu.comp_code=c.comp_code)='Y' and c.mode_code='ST' AND (gst!='' and gst is not null) AND CONVERT(DECIMAL(10,2), 0.5)<=wt AND cast(tdate as date) BETWEEN '$book_date_from' AND '$book_date_to' AND comp_code='$current_station' $book_condition")->fetch(PDO::FETCH_OBJ);            
               $select_cnote = $db->query("SELECT * FROM cash c WHERE (SELECT gta FROM station cu WHERE cu.comp_code=c.comp_code)='Y' and c.mode_code='ST' AND (gst!='' AND gst is NOT null) AND CONVERT(DECIMAL(10,2), 0.5)<=wt AND cast(tdate as date) BETWEEN '$book_date_from' AND '$book_date_to' AND comp_code='$current_station'  $book_condition");
               $charge_val = $db->query("SELECT SUM(convert(float,charges)) as charges FROM cash c WHERE (SELECT gta FROM station cu WHERE cu.comp_code=c.comp_code)='Y' and c.mode_code='ST' AND (gst!='' and gst is not null) AND CONVERT(DECIMAL(10,2), 0.5)<=wt AND cast(tdate as date) BETWEEN '$book_date_from' AND '$book_date_to' AND comp_code='$current_station'  $book_condition")->fetch(PDO::FETCH_OBJ);
               $charge_cgst = $db->query("SELECT SUM(convert(float,cgst)) as cgst FROM cash c WHERE (SELECT gta FROM station cu WHERE cu.comp_code=c.comp_code)='Y' and c.mode_code='ST' AND (gst!='' and gst is not null) AND CONVERT(DECIMAL(10,2), 0.5)<=wt AND cast(tdate as date) BETWEEN '$book_date_from' AND '$book_date_to' AND comp_code='$current_station'  $book_condition")->fetch(PDO::FETCH_OBJ);   
               $charge_sgst = $db->query("SELECT SUM(convert(float,sgst)) as sgst FROM cash c WHERE (SELECT gta FROM station cu WHERE cu.comp_code=c.comp_code)='Y' and c.mode_code='ST' AND (gst!='' and gst is not null) AND CONVERT(DECIMAL(10,2), 0.5)<=wt AND cast(tdate as date) BETWEEN '$book_date_from' AND '$book_date_to' AND comp_code='$current_station'  $book_condition")->fetch(PDO::FETCH_OBJ);   
               $charge_igst = $db->query("SELECT SUM(convert(float,igst)) as igst FROM cash c WHERE (SELECT gta FROM station cu WHERE cu.comp_code=c.comp_code)='Y' and c.mode_code='ST' AND (gst!='' and gst is not null) AND CONVERT(DECIMAL(10,2), 0.5)<=wt AND cast(tdate as date) BETWEEN '$book_date_from' AND '$book_date_to' AND comp_code='$current_station'  $book_condition")->fetch(PDO::FETCH_OBJ);   

   

          // console_log($charges);


              $b_sno=0;
              echo " <thead>  
              <tr>
                   <th style='text-align:left'>S.no</th>
                   <th style='text-align:left'>Branch</th>";
                   echo "<th style='text-align:left'>Cno</th>";
                   echo "<th style='text-align:left'>Weight</th>";
                   echo "<th style='text-align:left'>Mode</th>";
                   echo "<th style='text-align:left'>Gst</th>";
                   echo "<th style='text-align:left'>Origin</th>";
                   echo "<th style='text-align:left'>Destination</th>";
                   echo "<th style='text-align:left'>Booking Date</th>";

                   echo "<th style='text-align:left'>Amount</th>";
                   echo "<th style='text-align:left'>Charges</th>";
                   echo "<th style='text-align:left'>CGST</th>";
                   echo "<th style='text-align:left'>SGST</th>";
                   echo "<th style='text-align:left'>IGST</th>";
                   echo "<th style='text-align:left'>Rcvd Amount</th>";
              echo "</tr>
              </thead>";
              while($bkrow = $select_cnote->fetch(PDO::FETCH_OBJ)){ $b_sno++;
                  echo "<tr>
                       <td>".$b_sno."</td>
                       <td>".$bkrow->br_code."</td>";
                       echo "<td>".$bkrow->cno."</td>";
                            
                            echo "<td>".$bkrow->wt."</td>";
                            echo "<td>".$bkrow->mode_code."</td>";
                            echo "<td>".$bkrow->gst."</td>";
                            echo "<td>".$bkrow->origin."</td>";
                            echo "<td>".$bkrow->dest_code."</td>";
                            echo "<td>".$bkrow->tdate."</td>";
                            echo "<td>".number_format($bkrow->amt,2)."</td>";
                            echo "<td>".$bkrow->charges."</td>";
                            echo "<td>".$bkrow->cgst."</td>";
                            echo "<td>".$bkrow->sgst."</td>";
                            echo "<td>".$bkrow->igst."</td>";
                            echo "<td>".number_format($bkrow->ramt,2)."</td>";
                            
                  echo "</tr>";         
                  
              }
              echo "<tr>
              <td></td>
              <td></td>";
              echo "<td></td>";
                   
                   echo "<td></td>";
                   echo "<td></td>";
                   echo "<td></td>";
                   echo "<td></td>";
                   echo "<td></td>";
                   echo "<td><b>Total :</td>";
                   echo "<td><b>".number_format($total_amt->amount,2)."</b></td>";   
                   echo "<td><b>".number_format($charge_val->charges,2)."</b></td>"; 
                   echo "<td><b>".number_format($charge_cgst->cgst,2)."</b></td>";  
                   echo "<td><b>".number_format($charge_sgst->sgst,2)."</b></td>";
                   echo "<td><b>".number_format($charge_igst->igst,2)."</b></td>";
                   echo "<td><b>".number_format($total_ramt->amount,2)."</b></td>";
                   
         echo "</tr>";


              exit();
          }

          // Cash Tax Summary Report
          if($book_rep_type=='cashtax')
          {
  
               if($bk_branch == 'ALL'){
                   
               }else{
                    $book_conditions[] = "AND br_code='$bk_branch'";
               }

               
               $book_condition = implode(' AND ', $book_conditions);  
              // convert (int, N'A.my_NvarcharColumn')

               // $cashtax = $db->query("SELECT br_code,
               //  (select sum(CONVERT(float,charges)) as amt from cash cc where cc.cnote_serial NOT IN ('PRO','PRC') AND (CASE WHEN ROUND(FORMAT(CONVERT(float, charges)*0.18/2, 'N2'),0,10) = ROUND(cgst,0,10) OR ROUND(FORMAT(CONVERT(float, charges)*0.18/2*2, 'N2'),0,10) = ROUND(igst,0,10) THEN '18' ELSE '5' END)='5' AND zone_code !='100' AND comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code and status = 'Billed' group by br_code) as DAmt5           
               // ,(select SUM(CONVERT(float, igst)+CONVERT(float, cgst)+CONVERT(float, cgst)) as amt from cash cc where cc.cnote_serial NOT IN ('PRO','PRC') AND (CASE WHEN ROUND(FORMAT(CONVERT(float, charges)*0.18/2, 'N2'),0,10) = ROUND(cgst,0,10) OR ROUND(FORMAT(CONVERT(float, charges)*0.18/2*2, 'N2'),0,10) = ROUND(igst,0,10) THEN '18' ELSE '5' END)='5' AND zone_code !='100' AND comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code and status = 'Billed' group by br_code) as D5percent
               // ,(select sum(CONVERT(float,charges)) as amt from cash cc where cc.cnote_serial NOT IN ('PRO','PRC')  AND (CASE WHEN ROUND(FORMAT(CONVERT(float, charges)*0.18/2, 'N2'),0,10) = ROUND(cgst,0,10) OR ROUND(FORMAT(CONVERT(float, charges)*0.18/2*2, 'N2'),0,10) = ROUND(igst,0,10) THEN '18' ELSE '5' END)='18'  AND zone_code !='100' AND comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code and status = 'Billed' group by br_code) as Damut18
               // ,(select SUM(CONVERT(float, igst)+CONVERT(float, cgst)+CONVERT(float, cgst)) as amt from cash cc where cc.cnote_serial NOT IN ('PRO','PRC')  AND (CASE WHEN ROUND(FORMAT(CONVERT(float, charges)*0.18/2, 'N2'),0,10) = ROUND(cgst,0,10) OR ROUND(FORMAT(CONVERT(float, charges)*0.18/2*2, 'N2'),0,10) = ROUND(igst,0,10) THEN '18' ELSE '5' END)='18'  AND zone_code !='100' AND comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code and status = 'Billed' group by br_code) as Damut18p
               // ,(select sum(CONVERT(float,charges)) from cash p WITH (NOLOCK)  WHERE cnote_serial IN ('PRO','PRC') AND comp_code='$current_station'  AND zone_code !='100' AND p.tdate  BETWEEN '$book_date_from' AND '$book_date_to' and c.br_code=p.br_code and status = 'Billed' group by br_code ) AS PAmt
               // ,(select SUM(CONVERT(float, igst)+CONVERT(float, cgst)+CONVERT(float, cgst)) from cash p WITH (NOLOCK)  WHERE p.cnote_serial IN ('PRO','PRC') AND p.comp_code='$current_station' AND zone_code !='100' AND p.tdate  BETWEEN '$book_date_from' AND '$book_date_to' and c.br_code=p.br_code and status = 'Billed' group by br_code ) AS P18
               // ,(select sum(CONVERT(float,charges)) from cash i WITH (NOLOCK)  WHERE i.zone_code='100' AND i.comp_code='$current_station' AND i.tdate  BETWEEN '$book_date_from' AND '$book_date_to' and c.br_code=i.br_code and status = 'Billed'  group by br_code) AS iAmt
               // ,(select SUM(CONVERT(float, igst)+CONVERT(float, cgst)+CONVERT(float, cgst)) from cash i WITH (NOLOCK)  WHERE i.zone_code='100' AND i.comp_code='$current_station' AND i.tdate  BETWEEN '$book_date_from' AND '$book_date_to' and c.br_code=i.br_code and status = 'Billed' group by br_code) AS i18
               // ,(select sum(ramt) from cash cc where comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code  AND status not in ('Billed-Void','Invoiced-Void') group by br_code) as total

               // from cash c where cnote_serial NOT IN ('PRO','PRC') AND  comp_code='$current_station' AND tdate  BETWEEN '$book_date_from' AND '$book_date_to'  group by br_code order by br_code");   
              
               $convert_decimal = 'SUM(ISNULL(CAST(TRY_CONVERT(money, sgst, 1) AS DECIMAL(18, 2)), 0)+ISNULL(CAST(TRY_CONVERT(money, cgst, 1) AS DECIMAL(18, 2)), 0)+ISNULL(CAST(TRY_CONVERT(money, igst, 1) AS DECIMAL(18, 2)), 0))';
               $check_gst = '(CASE WHEN cast(cast(ROUND(ISNULL(CAST(TRY_CONVERT(money, charges, 1) AS DECIMAL(18, 2)), 0)*0.18/2,0,10) as decimal(9,2)) AS FLOAT) = cast(cast(ROUND(ISNULL(CAST(TRY_CONVERT(money, cgst, 1) AS DECIMAL(18, 2)), 0),0,10) as decimal(9,2)) AS FLOAT) 
               OR cast(cast(ROUND(ISNULL(CAST(TRY_CONVERT(money, charges, 1) AS DECIMAL(18, 2)), 0)*0.18/2*2,0,10) as decimal(9,2)) AS FLOAT) = cast(cast(ROUND(ISNULL(CAST(TRY_CONVERT(money, igst, 1) AS DECIMAL(18, 2)), 0),0,10) as decimal(9,2)) AS FLOAT) THEN 18 ELSE 5 END)';
          
          
                         $cashtax = $db->query("SELECT br_code,
                             (select SUM(ISNULL(CAST(TRY_CONVERT(money, charges, 1) AS DECIMAL(18, 2)), 0)) as amt from cash cc where cc.cnote_serial NOT IN ('PRO','PRC') AND $check_gst='5' AND zone_code !='100' AND comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code and status not in ('Billed-Void','Invoiced-Void') group by br_code) as DAmt5          
                            ,(select $convert_decimal as amt from cash cc where cc.cnote_serial NOT IN ('PRO','PRC') AND $check_gst='5' AND zone_code !='100' AND comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code and status not in ('Billed-Void','Invoiced-Void') group by br_code) as D5percent
                            ,(select SUM(ISNULL(CAST(TRY_CONVERT(money, charges, 1) AS DECIMAL(18, 2)), 0)) as amt from cash cc where cc.cnote_serial NOT IN ('PRO','PRC')  AND $check_gst='18'  AND zone_code !='100' AND comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code and status not in ('Billed-Void','Invoiced-Void') group by br_code) as Damut18
                            ,(select $convert_decimal as amt from cash cc where cc.cnote_serial NOT IN ('PRO','PRC')  AND $check_gst='18'  AND zone_code !='100' AND comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code and status not in ('Billed-Void','Invoiced-Void') group by br_code) as Damut18p
                            ,(select SUM(ISNULL(CAST(TRY_CONVERT(money, charges, 1) AS DECIMAL(18, 2)), 0)) from cash p WITH (NOLOCK)  WHERE cnote_serial IN ('PRO','PRC') AND comp_code='$current_station'  AND zone_code !='100' AND p.tdate  BETWEEN '$book_date_from' AND '$book_date_to' and c.br_code=p.br_code and status not in ('Billed-Void','Invoiced-Void') group by br_code ) AS PAmt
               
                            ,(select $convert_decimal from cash p WITH (NOLOCK)  WHERE p.cnote_serial IN ('PRO','PRC') AND p.comp_code='$current_station' AND zone_code !='100' AND p.tdate  BETWEEN '$book_date_from' AND '$book_date_to' and c.br_code=p.br_code and status not in ('Billed-Void','Invoiced-Void') group by br_code ) AS P18
                            ,(select SUM(ISNULL(CAST(TRY_CONVERT(money, charges, 1) AS DECIMAL(18, 2)), 0)) from cash i WITH (NOLOCK)  WHERE i.zone_code='100' AND i.comp_code='$current_station' AND i.tdate  BETWEEN '$book_date_from' AND '$book_date_to' and c.br_code=i.br_code and status not in ('Billed-Void','Invoiced-Void')  group by br_code) AS iAmt
                            ,(select $convert_decimal from cash i WITH (NOLOCK)  WHERE i.zone_code='100' AND i.comp_code='$current_station' AND i.tdate  BETWEEN '$book_date_from' AND '$book_date_to' and c.br_code=i.br_code and status not in ('Billed-Void','Invoiced-Void') group by br_code) AS i18
                                 ,(select sum(ramt) from cash cc where comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code  AND status not in ('Billed-Void','Invoiced-Void') group by br_code) as total
                            from cash c where  comp_code='$current_station' AND tdate  BETWEEN '$book_date_from' AND '$book_date_to' and (select sum(ramt) from cash cc where comp_code='$current_station' AND cc.tdate  BETWEEN '$book_date_from' AND '$book_date_to' AND cc.br_code=c.br_code  AND status not in ('Billed-Void','Invoiced-Void') group by br_code)  not in ('0.00','0') group by br_code order by br_code asc");
           //  console_log($cashtax);

              $b_sno=0;
              echo " <thead>  
              
              <tr>
                   <th style='text-align:center;' colspan='6'>Domestic</th>
                   ";
                   echo "";
                  echo "";
                   echo "";
                   echo "";
                echo "<th style='text-align:center;' colspan='2'>Pro</th>";
                   echo "";
              
                    echo "<th style='text-align:center;' colspan='2'>International</th>";
                    echo "";
               echo "<th style='text-align:center;'></th>";
                 
              echo "</tr>
              </thead>";
              echo " <thead>  
              
              <tr>
                   <th style='text-align:left'>S.no</th>
                   <th style='text-align:left'>Branch</th>";
                   echo "<th style='text-align:left'>DAmt</th>";
                  echo "<th style='text-align:left'>D5%</th>";
                  echo "<th style='text-align:left'>DAmt</th>";
                  echo "<th style='text-align:left'>D18%</th>";
                echo "<th style='text-align:left'>PAmt</th>";
                   echo "<th style='text-align:left'>P18%</th>";
              
                    echo "<th style='text-align:left'>Int_Amt</th>";
                    echo "<th style='text-align:left'>Int 18%</th>";
               echo "<th style='text-align:left'>Total</th>";
                 
              echo "</tr>
              </thead>";
              while($rowtax = $cashtax->fetch(PDO::FETCH_OBJ)){ $b_sno++;              

                  echo "<tr>
                       <td>".$b_sno."</td>
                            <td>".$rowtax->br_code."</td>"; 
                            echo "<td>".number_format($rowtax->DAmt5,2)."</td>"; 
                           echo "<td>".number_format($rowtax->D5percent,2)."</td>";
                           echo "<td>".number_format($rowtax->Damut18,2)."</td>"; 
                           echo "<td>".number_format($rowtax->Damut18p,2)."</td>";
                              echo "<td>".number_format($rowtax->PAmt,2)."</td>";
                         echo "<td>".number_format($rowtax->P18,2)."</td>";
                       
                          
                         echo "<td>".number_format($rowtax->iAmt,2)."</td>";
                            echo "<td>".number_format($rowtax->i18,2)."</td>";
                        
                            echo "<td>".number_format($rowtax->total,2)."</td>";
                                   
                  echo "</tr>";         
                  
              }
              
              exit();
          }

              $cust_type_condition = '';
              if($book_rep_type=='bookcredit'){
               $table = 'credit';
               $columnramt = "";
               $podcolumn = "podorigin";
               $customercolumn = " cust_code ";
              }
              if($book_rep_type=='bookinbound'){
               $table = 'bainbound';
               $columnramt = "";
               $customercolumn = " ba_code ";
               $podcolumn = "podorigin";
              }
              if($book_rep_type=='bookcash'){
               $table = 'cash';
               $cust_type_condition = '';
               $columnramt = "";
               $podcolumn = "pod_origin";
              }
              if($book_rep_type=='overallbookcash'){
               $podcolumn = "pod_origin";
              }
              
                            
      
          //gen report
          $book_conditions = array();
          if(!empty($book_date_from)){
               $book_conditions[] = "tdate BETWEEN '$book_date_from' AND '$book_date_to'";
          }
          if($book_rep_type=='bookcash'){
               $book_conditions[] = "status not in ('Billed-Void','Invoiced-Void')";
               
          }
          // if($book_rep_type=='bookcash' && $cc_code=='ALL'){
                   
          // }else{
          //      $book_conditions[] = "cc_code='$cc_code'";
          // }
          if($book_rep_type=='overallbookcash'){
               $book_conditions[] = "status not in ('Billed-Void','Invoiced-Void')";
          }
          if($book_rep_type=='bookcredit'){
               $book_conditions[] = "status not in ('Billed-Void','Invoiced-Void')";
          }
		  if($book_rep_type=='bookcash'){
			if($payment_mode=='ALL'){
				
			}else{
				 $book_conditions[] = "payment_mode='$payment_mode'";
			}    
	   }
          if(!empty($weight_from)){
              $book_conditions[] = "wt BETWEEN '$weight_from' AND '$weight_to'";
          }
          if(empty($bk_branch) || $bk_branch=='ALL'){
              
          }
          else{
              $book_conditions[] = "br_code ='$bk_branch'";
          }
          if(empty($cc_code) || $cc_code=='ALL'){
              
          }
          else{
              $book_conditions[] = "cc_code ='$cc_code'";
          }

          if(empty($bk_customer) || $bk_customer=='ALL'){
              
          }
          else{
              $book_conditions[] = "$customercolumn ='$bk_customer'";
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
          if($bkrpt_podorigin=='ALL'){
          }
          else if($bkrpt_podorigin==$bookstn){
               $book_conditions[] = "  $podcolumn not in ('PRO','PRC','PRD','COD')";
          }
          else{
               if($exbainbound=='exbainbound'){
                    $book_conditions[] = " cust_code='$excustomer'";

               }else{
                    $book_conditions[] = "  $podcolumn='$bkrpt_podorigin'";
               }
          }
          $book_condition = implode(' AND ', $book_conditions);
          if($book_rep_type=='conbinereport')
          {
            
               echo "<thead style='margin-top:15px;'>
               <tr>
               <th colspan='11' style='background-color:#2160A0;text-align:center;color:white;height:30px;'>Cash Report List</th>
               </tr>
               </thead>
               ";
               echo "<thead>
               <tr>
               <th style='text-align:left'>S.no</th>
               <th style='text-align:left'>Branch</th>
               <th style='text-align:left'>Comp Code</th>
              <th style='text-align:left'>Cno</th>
               <th style='text-align:left'>Ch Weight</th>
               <th style='text-align:left'>Mode</th>
               <th style='text-align:left'>Origin</th>
               <th style='text-align:left'>Destination</th>
               <th style='text-align:right'>Status</th>
               <th style='text-align:left'>Booking Date</th>
               <th style='text-align:right'>Amount</th>
         
               </tr>
               </thead>";
               $overalcashsummary = $db->query("SELECT * FROM cash WITH (NOLOCK)  WHERE $book_condition AND status not in ('Invoiced-Void')  $cust_type_condition ORDER BY tdate");
               $combine_amt = $db->query("SELECT SUM(ramt) as amount FROM cash WITH (NOLOCK)  WHERE $book_condition AND status not in ('Invoiced-Void')  $cust_type_condition")->fetch(PDO::FETCH_OBJ);

               $i=0;
               while($rowoveralcashsummary = $overalcashsummary->fetch(PDO::FETCH_OBJ)){$i++;
                    echo "<tr style='background-color:".(($rowoveralcashsummary->status=='Billed-Void')? 'green' : '').";color:".(($rowoveralcashsummary->status=='Billed-Void')? 'white' : '')."'>
                    <td>$i</td>
                    <td>$rowoveralcashsummary->br_code</td>
                    <td style='text-align:right' value='billed'>$rowoveralcashsummary->comp_code</td>
                    <td style='text-align:right'>".($rowoveralcashsummary->cno)."</td>
                    <td>".number_format(max($rowoveralcashsummary->wt, $rowoveralcashsummary->v_wt),3)."</td>
                    <td style='text-align:right'>$rowoveralcashsummary->mode_code</td>
                    <td style='text-align:right'>".($rowoveralcashsummary->origin)."</td>
                    <td>$rowoveralcashsummary->dest_code</td>
                    <td>$rowoveralcashsummary->status</td>
                    <td>".date("d-M-Y", strtotime($rowoveralcashsummary->tdate))."</td>
                    <td style='text-align:right'>".number_format($rowoveralcashsummary->ramt, 2)."</td>
                    </tr>";
               }
               echo "<tr>
               <td></td>
               <td></td>
               <td style='text-align:right'></td>
               <td style='text-align:right'></td>
               <td></td>
               <td style='text-align:right'></td>
               <td style='text-align:right'></td>
               <td></td>
               <td></td>
               <td><b>Total</b></td>
               <td style='text-align:right'><b>".number_format($combine_amt->amount,2)."</b></td>
               </tr>";
               echo "<thead>
               <tr>
               <th colspan='11' style='background-color:#2160A0;text-align:center;color:white;height:30px;'>Credit Report List</th>
               </tr>
               </thead>
               ";

               $creditoveralcashsummary = $db->query("SELECT * FROM credit WITH (NOLOCK)  WHERE $book_condition AND status not in ('Billed-Void','Invoiced-Void')  $cust_type_condition ORDER BY tdate");

           

               $combine_credit_total = $db->query("SELECT SUM(amt) as amount FROM credit WITH (NOLOCK)  WHERE $book_condition AND status not in ('Billed-Void','Invoiced-Void')  $cust_type_condition")->fetch(PDO::FETCH_OBJ);

               

               if($creditoveralcashsummary->rowCount()==0){
                    echo "<thead>
                    <tr>
                    <th colspan='11' style='text-align:center;'>No Data</th>
                    </tr>
                    </thead>
                    ";  
               }else{
                    
                     $i=0;
                     while($rowoveralcashsummary = $creditoveralcashsummary->fetch(PDO::FETCH_OBJ)){$i++;
                          echo "<tr style='background-color:".(($rowoveralcashsummary->status=='Billed-Void')? 'green' : '').";color:".(($rowoveralcashsummary->status=='Billed-Void')? 'white' : '')."'>
                          <td>$i</td>
                          <td>$rowoveralcashsummary->br_code</td>
                          <td style='text-align:right'>$rowoveralcashsummary->comp_code</td>
                          <td style='text-align:right'>".($rowoveralcashsummary->cno)."</td>
                          <td>".number_format(max($rowoveralcashsummary->wt, $rowoveralcashsummary->v_wt),3)."</td>
                          <td style='text-align:right'>$rowoveralcashsummary->mode_code</td>
                          <td style='text-align:right'>".($rowoveralcashsummary->origin)."</td>
                          <td>$rowoveralcashsummary->dest_code</td>
                          <td>$rowoveralcashsummary->status</td>
                          <td>".date("d-M-Y", strtotime($rowoveralcashsummary->tdate))."</td>
                          <td style='text-align:right'>".number_format($rowoveralcashsummary->amt, 2)."</td>
                          </tr>";
                     }
                     echo "<tr>
                     <td></td>
                     <td></td>
                     <td style='text-align:right'></td>
                     <td style='text-align:right'></td>
                     <td></td>
                     <td style='text-align:right'></td>
                     <td style='text-align:right'></td>
                     <td></td>
                     <td></td>
                     <td><b>Total</b></td>
                     <td style='text-align:right'><b>".number_format($combine_credit_total->amount,2)."</b></td>
                     </tr>";
                     echo "<thead>
                     <tr>
                     <th colspan='11' style='background-color:#2160A0;text-align:center;color:white;height:30px;'>Bainbound Report List</th>
                     </tr>
                     </thead>
                     ";
               }
             

               $overalcashsummary = $db->query("SELECT * FROM bainbound WITH (NOLOCK)  WHERE $book_condition $cust_type_condition ORDER BY tdate");
               $combine_bainbound_total = $db->query("SELECT SUM(amt) AS amount FROM bainbound WITH (NOLOCK)  WHERE $book_condition $cust_type_condition")->fetch(PDO::FETCH_OBJ);

              console_log($combine_bainbound_total);
               $i=0;
               if($overalcashsummary->rowCount()==0){
                    echo "<thead>
                    <tr>
                    <th colspan='11' style='text-align:center;'>No Data</th>
                    </tr>
                    </thead>
                    ";  
               }else{
                    while($rowoveralcashsummary = $overalcashsummary->fetch(PDO::FETCH_OBJ)){$i++;
                         echo "<tr style='background-color:".(($rowoveralcashsummary->status=='Billed-Void')? 'green' : '').";color:".(($rowoveralcashsummary->status=='Billed-Void')? 'white' : '')."'>
                         <td>$i</td>
                         <td>$rowoveralcashsummary->br_code</td>
                         <td style='text-align:right'>$rowoveralcashsummary->comp_code</td>
                         <td style='text-align:right'>".($rowoveralcashsummary->cno)."</td>
                         <td>".number_format(max($rowoveralcashsummary->wt, $rowoveralcashsummary->v_wt),3)."</td>
                         <td style='text-align:right'>$rowoveralcashsummary->mode_code</td>
                         <td style='text-align:right'>".($rowoveralcashsummary->origin)."</td>
                         <td>$rowoveralcashsummary->dest_code</td>
                         <td>$rowoveralcashsummary->status</td>
                         <td>".date("d-M-Y", strtotime($rowoveralcashsummary->tdate))."</td>
                         <td style='text-align:right'>".number_format($rowoveralcashsummary->amt, 2)."</td>
                         </tr>";
                    } echo "<tr>
                    <td></td>
                    <td></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td></td>
                    <td style='text-align:right'></td>
                    <td style='text-align:right'></td>
                    <td></td>
                    <td></td>
                    <td><b>Total</b></td>
                    <td style='text-align:right'><b>".number_format($combine_bainbound_total->amount,2)."</b></td>
                    </tr>";
               }
              
               
               exit;
            
              
          }

   

          if($book_rep_type=='overallbookcash'){
               echo "<thead>
               <tr>
               <th style='text-align:left'>S.no</th>
               <th style='text-align:left'>Branch</th>
               
               <th style='text-align:right'>Cnote Count</th>
               <th style='text-align:right'>Amount</th>
               </tr>
               </thead>";
               console_log("SELECT br_code,sum(amt) sumamt ,count(*) cnotecount FROM cash WHERE $book_condition group by br_code");
               $overalcashsummary = $db->query("SELECT br_code,sum(ramt) sumamt ,count(*) cnotecount FROM cash WHERE $book_condition group by br_code");
               console_log("SELECT br_code,sum(amt) sumamt ,count(*) cnotecount FROM cash WHERE $book_condition group by br_code");
               $i=0;
               while($rowoveralcashsummary = $overalcashsummary->fetch(PDO::FETCH_OBJ)){$i++;
                    echo "<tr>
                    <td>$i</td>
                    <td>$rowoveralcashsummary->br_code</td>
                    <td style='text-align:right'>$rowoveralcashsummary->cnotecount</td>
                    <td style='text-align:right'>".number_format($rowoveralcashsummary->sumamt,2)."</td>
                    </tr>";
               }
               exit;
              }

              if($book_rep_type=='credittally'){

               if(!empty($book_date_from)){
                    $book_conditions1[] = "tdate BETWEEN '$book_date_from' AND '$book_date_to'";
               }
               if($book_rep_type=='credittally'){
                    $book_conditions1[] = "status not in ('Billed-Void','Invoiced-Void')";
               }
               if(!empty($bookstn)){
                    $book_conditions1[] = "comp_code='$bookstn'";
               }

               if(!empty($weight_from)){
                    $book_conditions1[] = "wt BETWEEN '$weight_from' AND '$weight_to'";
                }
                if(empty($bk_branch) || $bk_branch=='ALL'){
                    
                }
                else{
                    $book_conditions1[] = "br_code ='$bk_branch'";
                }


                if(empty($bk_customer) || $bk_customer=='ALL'){
              
               }
               else{
                   $book_conditions1[] = "cust_code ='$bk_customer'";
               }
               if(!empty($book_destination)){
                   $book_conditions1[] = "dest_code = '$book_destination'";
               }
               if(!empty($book_zone)){
                   $book_conditions1[] = "zone_code = '$book_zone'";
               }

               if($bkrpt_podorigin=='ALL'){
               }
               else if($bkrpt_podorigin==$bookstn){
                    $book_conditions1[] = "  pod_origin not in ('PRO','PRC','PRD','COD')";
               }
               else{
                    if($exbainbound=='exbainbound'){
                         $book_conditions1[] = " cust_code='$excustomer'";
     
                    }else{
                         $book_conditions1[] = "  pod_origin='$bkrpt_podorigin'";
                    }
               }
               if($payment_mode=='ALL'){
				
			}else{
				 $book_conditions1[] = "payment_mode='$payment_mode'";
			}    

               $book_condition = implode(' AND ', $book_conditions1);

               echo "<thead>
               <tr>
               <th style='text-align:left'>SL NO</th>
               <th style='text-align:left'>VOUCHER TYPE</th>
               <th style='text-align:right'>PARTY CODE</th>
               <th style='text-align:right'>PARTY NAME</th>
               <th style='text-align:left'>GSTIN</th>
               <th style='text-align:left'>IRN</th>
               <th style='text-align:right'>ACK</th>
               <th style='text-align:right'>Branch</th>
               <th style='text-align:left'>Credit Note Number</th>
               <th style='text-align:left'>Original Invoice No</th>
               <th style='text-align:right'>Credit Note Date</th>
               <th style='text-align:right'>Taxable Value</th>
               <th>CGST</th>
               <th>SGST</th>
               <th>IGST</th>
               <th>TOTAL</th>
               <th>Narration</th>
               </tr>
               </thead>";
               
               $credittally = $db->query("SELECT * FROM credit WITH (NOLOCK)  WHERE $book_condition  ORDER BY tdate");
               console_log($credittally);
               $i=0;
               while($rowoveralcashsummary = $credittally->fetch(PDO::FETCH_OBJ)){$i++;

                    
                    echo "<tr>
                    <td style='text-align:left'>".$i."</td>
                    <td style='text-align:left'>-</td>
                    <td style='text-align:right'>-</td>
                    <td style='text-align:right'>-</td>
                    <td style='text-align:left'>-</td>
                    <td style='text-align:left'>-</td>
                    <td style='text-align:right'>-</td>
                    <td style='text-align:right'>".$rowoveralcashsummary->br_code."</td>
                    <td style='text-align:left'>".$rowoveralcashsummary->cno."</td>
                    <td style='text-align:left'>-</td>
                    <td style='text-align:right'>".$rowoveralcashsummary->tdate."</td>
                    <td style='text-align:right'>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>".$rowoveralcashsummary->amt."</td>
                    <td>-</td>
                    </tr>";
               }
               exit;
              }

              if($book_rep_type=='cashtally'){

               if(!empty($book_date_from)){
                    $book_conditions1[] = "tdate BETWEEN '$book_date_from' AND '$book_date_to'";
               }
               if($book_rep_type=='cashtally'){
                    $book_conditions1[] = "status not in ('Billed-Void','Invoiced-Void')";
               }
               if(!empty($bookstn)){
                    $book_conditions1[] = "comp_code='$bookstn'";
               }

               if(!empty($weight_from)){
                    $book_conditions1[] = "wt BETWEEN '$weight_from' AND '$weight_to'";
                }
                if(empty($bk_branch) || $bk_branch=='ALL'){
                    
                }
                else{
                    $book_conditions1[] = "br_code ='$bk_branch'";
                }


                if(empty($cc_code) || $cc_code=='ALL'){
              
               }
               else{
                   $book_conditions[] = "cc_code ='$cc_code'";
               }
               if(!empty($book_destination)){
                   $book_conditions1[] = "dest_code = '$book_destination'";
               }
               if(!empty($book_zone)){
                   $book_conditions1[] = "zone_code = '$book_zone'";
               }

               if($bkrpt_podorigin=='ALL'){
               }
               else if($bkrpt_podorigin==$bookstn){
                    $book_conditions1[] = "  pod_origin not in ('PRO','PRC','PRD','COD')";
               }
               else{
                    if($exbainbound=='exbainbound'){
                         $book_conditions1[] = " cust_code='$excustomer'";
     
                    }else{
                         $book_conditions1[] = "  pod_origin='$bkrpt_podorigin'";
                    }
               }

               if($payment_mode=='ALL'){
				
			}else{
				 $book_conditions1[] = "payment_mode='$payment_mode'";
			}    
               $book_condition = implode(' AND ', $book_conditions1);

               echo "<thead>
               <tr>
               <th style='text-align:left'>SL NO</th>
               <th style='text-align:left'>VOUCHER TYPE</th>
               <th style='text-align:right'>PARTY CODE</th>
               <th style='text-align:right'>PARTY NAME</th>
               <th style='text-align:left'>GSTIN</th>
               <th style='text-align:left'>IRN</th>
               <th style='text-align:right'>ACK</th>
               <th style='text-align:right'>Branch</th>
               <th style='text-align:left'>Credit Note Number</th>
               <th style='text-align:left'>Original Invoice No</th>
               <th style='text-align:right'>Credit Note Date</th>
               <th style='text-align:right'>Taxable Value</th>
               <th>CGST</th>
               <th>SGST</th>
               <th>IGST</th>
               <th>TOTAL</th>
               <th>Narration</th>
               </tr>
               </thead>";
               
               $credittally = $db->query("SELECT * FROM cash WITH (NOLOCK)  WHERE $book_condition  ORDER BY tdate");
               console_log($credittally);
               $i=0;
               while($rowoveralcashsummary = $credittally->fetch(PDO::FETCH_OBJ)){$i++;
                    echo "<tr>
                    <td style='text-align:left'>".$i."</td>
                    <td style='text-align:left'>VOUCHER TYPE</td>
                    <td style='text-align:right'>PARTY CODE</td>
                    <td style='text-align:right'>PARTY NAME</td>
                    <td style='text-align:left'>".$rowoveralcashsummary->gst."</td>
                    <td style='text-align:left'>-</td>
                    <td style='text-align:right'>-</td>
                    <td style='text-align:right'>".$rowoveralcashsummary->br_code."</td>
                    <td style='text-align:left'>".$rowoveralcashsummary->cno."</td>
                    <td style='text-align:left'>-</td>
                    <td style='text-align:right'>".$rowoveralcashsummary->tdate."</td>
                    <td style='text-align:right'>-</td>
                    <td>".number_format($rowoveralcashsummary->cgst,2)."</td>
                    <td>".number_format($rowoveralcashsummary->sgst,2)."</td>
                    <td>".number_format($rowoveralcashsummary->igst,2)."</td>
                    <td>".number_format($rowoveralcashsummary->ramt,2)."</td>
                    <td>-</td>
                    </tr>";
               }
               exit;
              }
           
          $booking_report = $db->query("SELECT * FROM $table WITH (NOLOCK)  WHERE $book_condition $cust_type_condition ORDER BY tdate");
     // console_log($booking_report);

          //04/09/2022
           if($table=='cash'){
               $totalbookamt = $db->query("SELECT sum(amt) as 'total', SUM(convert(float,igst)) as 'igstval', SUM(convert(float,sgst)) as 'sgstval', SUM(convert(float,cgst)) as 'cgstval', SUM(convert(float,charges)) as 'charges' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition")->fetch(PDO::FETCH_OBJ);
           }else{
               $totalbookamt = $db->query("SELECT sum(amt) as 'total' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition")->fetch(PDO::FETCH_OBJ);
           }
            //04/09/2022
          
          
          if($book_rep_type=='bookcash'){
               $validatecount = $db->query("SELECT count(*) as 'rctotal' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition AND ramt is not null AND ramt!=''")->fetch(PDO::FETCH_OBJ);
       
               if($validatecount->rctotal>0){
                   $totalbookramt = $db->query("SELECT sum(ramt) as 'rtotal' FROM $table WITH (NOLOCK) WHERE $book_condition  $cust_type_condition AND ramt>0")->fetch(PDO::FETCH_OBJ);
               }
          }



          echo "<thead>
          <tr>
               <th style='text-align:left'>S.no</th>
			   <th style='text-align:left'>Gst</th>
               <th style='text-align:left'>Branch</th>";
               if($book_rep_type=='bookcredit' || $book_rep_type=='bookinbound'){
               echo "<th style='text-align:left'>Customer</th>";
               }
               if($book_rep_type=='bookcredit'){
                    echo "<th style='text-align:left'>Consignee Name</th>";
                    }
               echo "<th style='text-align:left'>Cno</th>
               <th style='text-align:left'>Ch Weight</th>
               <th style='text-align:left'>Mode</th>
               <th style='text-align:left'>Origin</th>
               <th style='text-align:left'>Destination</th>
               <th style='text-align:left'>Booking Date</th>";
               if($book_rep_type=='bookcredit'){
                    echo "<th>Pincode</th>";
                    echo "<th>Booking Time</th>";
                 
                    }
                    if($book_rep_type=='bookcash'){
				
                         echo "<th>Consignee Name</th>";
                         echo "<th>Shipper Name</th>";
                         echo "<th>Booking Time</th>";
                         echo "<th>Payment Reference(UPI)</th>";     
                    }
               echo "<th style='text-align:right'>Amount</th>";
               if($book_rep_type=='bookcash'){
				echo "<th style='text-align:right'>CC Code</th>";
                    echo "<th style='text-align:right'>Charges</th>";
                    echo "<th style='text-align:right'>CGST</th>";
                    echo "<th style='text-align:right'>SGST</th>";
                    echo "<th style='text-align:right'>IGST</th>";
                  echo "<th style='text-align:right'>Rcvd Amount</th>";
               }
          echo "</tr>
          </thead>";

          $b_sno=0;
          while($bkrow = $booking_report->fetch(PDO::FETCH_OBJ)){ $b_sno++;
              echo "<tr>
                   <td>".$b_sno."</td>
				   <td>".$bkrow->gst."</td>
                   <td>".$bkrow->br_code."</td>";
                   if($book_rep_type=='bookcredit'){
                        echo "<td>".$bkrow->cust_code."</td>";
                        echo "<td>".$bkrow->consignee."</td>";
                   }
                   if($book_rep_type=='bookinbound'){
                        echo "<td>".$bkrow->bacode."</td>";
                   }
                   echo "
                   <td>".$bkrow->cno."</td>
                 <td>".number_format(max($bkrow->wt, $bkrow->v_wt),3)."</td>
                   <td>".$bkrow->mode_code."</td>";
                   if($book_rep_type=='bookcash'){
                    echo "<td>".(($bkrow->pod_origin=='gl')?$bookstn:$bkrow->pod_origin)."</td>";
                   } else {
                    echo "<td>".(($bkrow->podorigin=='gl')?$bookstn:$bkrow->podorigin)."</td>";
                   }
                   echo "<td>".$bkrow->dest_code."</td>
                   <td style='width:150px;'>".date("d-M-Y", strtotime($bkrow->tdate))."</td>";
                   if($book_rep_type=='bookcredit'){
                    echo "<td>".$bkrow->consignee_pin."</td>";
                    echo "<td>".date('H:i:s ',strtotime($bkrow->date_added))."</td>";
                 
                    }
                    if($book_rep_type=='bookcash'){
                         echo "<td style='text-align:left'>".$bkrow->consignee."</td>";
                         echo "<td style='text-align:left'>".$bkrow->shp_name."</td>";
                         echo "<td style='text-align:left'>".date('H:i:s ',strtotime($bkrow->date_added))."</td>";
                         echo "<td style='width:172px;'>".(($bkrow->payment_mode=='UPI')? $bkrow->payment_reference : '')."</td>";
                         }
                   echo "<td style='text-align:right'>".number_format($bkrow->amt, 2)."</td>";
                   if($book_rep_type=='bookcash'){
					echo "<td style='text-align:right'>".$bkrow->cc_code."</td>";
                    echo "<td style='text-align:right'>".$bkrow->charges."</td>";
                    echo "<td style='text-align:right'>".$bkrow->sgst."</td>";
                    echo "<td style='text-align:right'>".$bkrow->cgst."</td>";
                    echo "<td style='text-align:right'>".$bkrow->igst."</td>";
                    echo "<td style='text-align:right'>".number_format($bkrow->ramt, 2)."</td>";
                     }
              echo "</tr>";
          }
          echo "<tr>
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
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
          <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".(($book_rep_type=='bookcash' || $book_rep_type=='bookcredit')? '' : 'Total :')."</td>";
         
          if($book_rep_type=='bookcredit'){
               echo "<td></td>";
               echo "<td></td>";
              
               echo "<td style='border-top:1px solid black;font-weight:bold;line-height:20px'>Total :</td>";
          }
          if($book_rep_type=='bookcash'){
               echo "<td></td>";
               echo "<td></td>";
              
               echo "<td></td>";
               echo "<td style='border-top:1px solid black;font-weight:bold;line-height:20px'>Total :</td>";
          }
          echo "<td style='text-align:left;border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($totalbookamt->total, 2)."</td>";
         

          if($book_rep_type=='bookcash'){
			echo "<td></td>
			<td style='text-align:right;border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($totalbookamt->charges, 2)."</td>
                <td style='text-align:right;border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($totalbookamt->sgstval, 2)."</td>
               <td style='text-align:right;border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($totalbookamt->cgstval, 2)."</td>
               <td style='text-align:right;border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($totalbookamt->igstval, 2)."</td> ";
              echo "<td style='text-align:right;border-top:1px solid black;font-weight:bold;line-height:20px'>".(($validatecount->rctotal>0)?number_format($totalbookramt->rtotal, 2):'0')."</td>";
          }
    
         echo" </tr>";
          }catch(PDOException $e){
               console_log($e);
          }
     }
      //generate report for collecton
      if(isset($_POST['coll_date_from'])){
           $coll_date_from   = $_POST['coll_date_from'];
           $coll_date_to     = $_POST['coll_date_to'];
           $coll_branch      = $_POST['coll_branch'];
           $coll_report_type = $_POST['coll_report_type'];
           $coll_stn         = $_POST['coll_stn'];
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
           $collreport = $db->query("SELECT * FROM collection WHERE $coll_condition AND status NOT LIKE '%Void' ORDER BY coldate");
           echo "<thead>
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
                    <td>".date("d-M-Y", strtotime($collrow->coldate))."</td>
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
           </tr></tbody>";
          }
          else if($coll_report_type=='collsummary'){
               $collsummreport = $db->query("SELECT * FROM collection WHERE coldate BETWEEN '$coll_date_from' AND '$coll_date_to' AND comp_code='$coll_stn' AND status NOT LIKE '%Void'  ORDER BY coldate");
           echo "<thead>
           <tr>
               <th class='align-left'>S.no</th>
               <th class='align-left'   style='min-width:100px'>Comp Code</th>
               <th class='align-left'   style='min-width:100px'>Br Code</th>
               <th class='align-left'   style='min-width:100px'>Cust Code</th>
               <th class='align-left'   style='min-width:170px'>Cust Name</th>
               <th class='align-left'   style='min-width:100px'>Sheet No</th>
               <th class='align-left'   style='min-width:100px'>Collection Number</th>
               <th class='align-left'   style='min-width:100px'>Invoice Number</th>
               <th class='align-left'   style='min-width:100px'>Collection Date</th>
               <th class='align-left'   style='min-width:100px'>Chq/DD/NEFT No</th>
               <th class='align-left'   style='min-width:100px'>Rcpt No</th>
               <th class='align-right'  style='min-width:100px'>Collection Amount</th>
               <th class='align-right'  style='min-width:100px'>Delivery</th>
               <th class='align-right'  style='min-width:100px'>OTC</th>
               <th class='align-right'  style='min-width:100px'>Deduction</th>
               <th class='align-right'  style='min-width:100px'>TDS</th>
               <th class='align-right'  style='min-width:100px'>Wrong Bill</th>  
               <th class='align-left'   style='min-width:100px'>Remarks</th>
               <th class='align-right'  style='min-width:100px'>Narration</th>
               <th class='align-left'   style='min-width:80px'>Inv Date</th>
               <th class='align-left'   style='min-width:100px'>C Mode</th>
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
                         <td style='width:120px'>".date("d-M-Y", strtotime($collsumrow->coldate))."</td>
                         <td style='width:120px'>".$collsumrow->chqddno."</td>
                         <td style='width:120px'>".$collsumrow->rcptno."</td>
                         <td style='text-align:right'>".number_format($collsumrow->colamt,2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->delivery,2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->otc,2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->reduction,2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->tds,2)."</td>
                         <td style='text-align:right'>".number_format($collsumrow->wrongbill,2)."</td>
                         <td style='width:120px'>".$collsumrow->remarks."</td>
                         <td style='width:120px'>".$collsumrow->narration."</td>
                         <td style='width:120px'>".date("Y-m-d", strtotime($collsumminvdate->invdate))."</td>
                         <td style='width:120px'>".$collsumrow->cmode."</td>
                    </tr>";
           }
           //all sum values
           $csvaluesum = $db->query("SELECT SUM(delivery) as 'sumdelivery', SUM(colamt) as 'sumcolamt',SUM(otc) as 'sumotc', SUM(tds) as 'sumtds', SUM(wrongbill) as 'sumwrongbill', SUM(colamt) as 'sumcolamt', SUM(reduction) as 'sumreduction' FROM collection WHERE coldate BETWEEN '$coll_date_from' AND '$coll_date_to' AND comp_code='$coll_stn' AND status NOT LIKE '%Void' ")->fetch(PDO::FETCH_OBJ);
           echo "<tr>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".bcdiv($csvaluesum->sumcolamt, 1, 2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".bcdiv($csvaluesum->sumdelivery, 1, 2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".bcdiv($csvaluesum->sumotc, 1, 2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".bcdiv($csvaluesum->sumreduction, 1, 2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".bcdiv($csvaluesum->sumtds, 1, 2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px;text-align:right'>".bcdiv($csvaluesum->sumwrongbill, 1, 2)."</td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           <td style='border-top:1px solid black;font-weight:bold;line-height:20px'></td>
           </tr></tbody>";
          }
          else if($coll_report_type=='collsummaryinbranch'){
               $collbrreport = $db->query("SELECT SUM(delivery) as 'repsumbr'
               , SUM(colamt) as 'repchequebr'
               , SUM(reduction) as 'totbrotc'
               , SUM(tds) as 'sutdsmbr'
               , SUM(wrongbill) as 'brwrongsum' FROM collection WHERE br_code='$coll_branch' AND coldate BETWEEN '$coll_date_from' AND '$coll_date_to' AND comp_code='$coll_stn' AND status NOT LIKE '%Void' ")->fetch(PDO::FETCH_OBJ);

           echo "<thead>
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
                    </tr>";
          }
      }
      //generate report for invoice summary
      if(isset($_POST['reporttype'])){
           $reporttype = $_POST['reporttype'];
           $inv_date_to = $_POST['inv_date_to'];
           $invtran_date_from = $_POST['invtran_date_from'];
           $inv_branch = $_POST['inv_branch'];
           $inv_customer = $_POST['inv_customer'];
           $invtran_date_to = $_POST['invtran_date_to'];
           $inv_date_from = $_POST['inv_date_from'];
           $inv_month_from = $_POST['inv_month_from'];
           $inv_month_to = date('Y-m-t',strtotime($_POST['inv_month_to']));
           //$inv_year = $_POST['inv_year'];
           $inv_stn = $_POST['inv_stn'];
           //generate tables
           if($reporttype=='invoicesummary'){ 
                if(!empty($inv_date_from)){
                     $invrptdatefilter = "invdate BETWEEN '$inv_date_from' AND '$inv_date_to'";
                }
                else{
                    $invrptdatefilter = "fmdate >='$invtran_date_from' AND todate <='$invtran_date_to'";
                }

                if(empty($inv_customer) || trim($inv_customer)=='ALL'){
                    $invr_custconditions = "";
               }
               else{
                    $invr_custconditions = " AND cust_code='$inv_customer'";
               }
               if(empty($inv_branch) || trim($inv_branch)=='ALL'){
                    $invr_branchconditions = "";
               }
               else{
                    $invr_branchconditions = " AND br_code='$inv_branch'";
               }
                echo"<thead>
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
                <th class='align-right'>Hand Chgs</th>
                <th class='align-right'>Inv Amt</th>
                <th class='align-right'>IGST</th>
                <th class='align-right'>CGST</th>
                <th class='align-right'>SGST</th>
                <th class='align-right'>Net Amt</th>
                <th class='align-right'>Cust GSTIN</th>
                </tr>
                </thead><tbody>";
                $invoicesummary = $db->query("SELECT * FROM invoice WHERE $invrptdatefilter AND comp_code='$inv_stn' $invr_custconditions $invr_branchconditions ORDER BY invdate");
                console_log($invoicesummary);
                $is_sno=0;
                while($invsrow = $invoicesummary->fetch(PDO::FETCH_OBJ)){$is_sno++;
                     $invsrcname = $db->query("SELECT cust_name,cust_gstin FROM customer WHERE cust_code='$invsrow->cust_code' AND comp_code='$inv_stn'")->fetch(PDO::FETCH_OBJ);
                     echo "<tr>
                     <td>".$is_sno."</td>
                     <td>".$invsrow->invno."</td>
                     <td>".date("d-M-Y", strtotime($invsrow->invdate))."</td>
                     <td>".$invsrow->cust_code."</td>
                     <td>".$invsrcname->cust_name."</td>
                     <td>".date("d-M-Y", strtotime($invsrow->fmdate))."</td>          
                     <td>".date("d-M-Y", strtotime($invsrow->todate))."</td>
                     <td style='text-align:right'>".number_format($invsrow->grandamt, 2)."</td>
                     <td style='text-align:right'>".number_format($invsrow->fsc,  2)."</td>
                     <td style='text-align:right'>".number_format($invsrow->fhandcharge,  2)."</td>
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
                ,SUM(fhandcharge) as 'sum_hc'
                ,SUM(invamt) as 'sum_invamt'
                , SUM(igst) as 'sum_igst'
                , SUM(cgst) as 'iscgst'
                , SUM(sgst) as 'isigst'
                ,SUM(netamt) as 'isnetamt' FROM invoice WHERE $invrptdatefilter AND comp_code='$inv_stn' $invr_branchconditions $invr_custconditions")->fetch(PDO::FETCH_OBJ);
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
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->sum_hc,  )."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->sum_invamt,  )."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->sum_igst,  2)."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->iscgst,  2)."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->isigst,  2)."</td>
                <td style='border-top:1px solid black;font-weight:bold;line-height:20px'>".number_format($insgrand->isnetamt,  2)."</td>
                <td></td>
                </tbody>";
           }
           else if($reporttype=='invoicesummarywithcoll'){
               echo"<thead>
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

               if(empty($inv_customer) || trim($inv_customer)=='ALL'){
                    $invr_custconditions = "";
               }
               else{
                    $invr_custconditions = " AND cust_code='$inv_customer'";
               }

               $invoicesummarywithcoll = $db->query("SELECT cust_code,invno,invdate,fmdate,todate,netamt,rcvdamt
               ,(select sum(tds) from collection where comp_code=invoice.comp_code and invno=invoice.invno AND status NOT LIKE '%void%') inv_tds
               ,(select sum(reduction) from collection where comp_code=invoice.comp_code and invno=invoice.invno AND status NOT LIKE '%void%') inv_reduction
               ,(select sum(wrongbill) from collection where comp_code=invoice.comp_code and invno=invoice.invno AND status NOT LIKE '%void%') inv_wrongbill
                FROM invoice WHERE $invrptdatefilter AND comp_code='$inv_stn' $invr_custconditions AND status NOT LIKE '%void%' ORDER BY invdate");
               $iswc_sno=0;
               while($invswcrow = $invoicesummarywithcoll->fetch(PDO::FETCH_OBJ)){$iswc_sno++;
                    $invswcrcname = $db->query("SELECT cust_name FROM customer WHERE cust_code='$invswcrow->cust_code' AND comp_code='$inv_stn'")->fetch(PDO::FETCH_OBJ);

                    echo "<tr>
                    <td>".$iswc_sno."</td>
                    <td>".$invswcrow->invno."</td>
                    <td>".date("d-M-Y", strtotime($invswcrow->invdate))."</td>                      
                    <td>".$invswcrow->cust_code."</td>
                    <td>".$invswcrcname->cust_name."</td>                             
                    <td>".date("d-M-Y", strtotime($invswcrow->fmdate))."</td>          
                    <td>".date("d-M-Y", strtotime($invswcrow->todate))."</td>
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
               ,(select SUM(tds) from collection where invdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND comp_code='$inv_stn' ) as 'iswctds'
               ,(select SUM(wrongbill) from collection where invdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND comp_code='$inv_stn' ) as 'iswcwrongbil'
               ,(select SUM(reduction) from collection where invdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND comp_code='$inv_stn' ) as 'isdeductbil' 
               FROM invoice WHERE invdate BETWEEN '$inv_date_from' AND '$inv_date_to' $invr_custconditions AND comp_code='$inv_stn' ")->fetch(PDO::FETCH_OBJ);
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
               </tbody>";
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
                    $invr_custconditions = "AND cust_code='$inv_customer'";
               }
               $monthinv = $db->query("SELECT DISTINCT year(invdate) as month_year,month(invdate) as month_invdate FROM invoice WHERE flag!=0 and invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' $invr_brconditions $invr_custconditions  order by year(invdate) ASC, month(invdate) ASC");
               echo"<thead>
               <tr>
               <th>S.no</th>
               <th class='align-left'>Cust Code</th>
               <th class='align-left'>Cust Name</th>
               <th class='align-left'>Branch Code</th>";
               
               while($monthinvrow=$monthinv->fetch(PDO::FETCH_OBJ))
               {
                    $monthNum = $monthinvrow->month_invdate;
                  $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                echo "<th class='align-left'>".date('M - '.substr($monthinvrow->month_year, 2).'', strtotime($monthName))."</th>"; 
                 
               }
               echo "<th class='align-left'>Total</th>
               </tr>
               </thead><tbody>";

               //looping table datas
               $getmocusts = $db->query("SELECT DISTINCT(cust_code) FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' $invr_brconditions $invr_custconditions ");
   
               $sno=0;
               while($moinvrow = $getmocusts->fetch(PDO::FETCH_OBJ)){$sno++;
               $getcustname = $db->query("SELECT top 1 cust_name FROM customer WHERE cust_code = '$moinvrow->cust_code' AND comp_code = '$inv_stn'")->fetch(PDO::FETCH_OBJ);
               $getbranchname = $db->query("SELECT DISTINCT br_code FROM invoice WHERE cust_code = '$moinvrow->cust_code' AND invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' $invr_brconditions $invr_custconditions ")->fetch(PDO::FETCH_OBJ);

                    echo "<tr>
                          <td>$sno</td>
                          <td>".$moinvrow->cust_code." </td>
                           <td>".$getcustname->cust_name."</td>";
                           echo "<td>".$moinvrow->br_code."</td>";
                          $monthinv2 = $db->query("SELECT DISTINCT year(invdate) as month_year,month(invdate) as month_invdate FROM invoice WHERE flag!=0 and invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' $invr_brconditions $invr_custconditions order by year(invdate) ASC, month(invdate) ASC");

                          
                          while($cdataminv = $monthinv2->fetch(PDO::FETCH_OBJ)){
                               $month = $cdataminv->month_invdate;
                               $year = $cdataminv->month_year;
                              $getmoinvnet = $db->query("SELECT SUM(netamt) as balamt FROM invoice WHERE cust_code='$moinvrow->cust_code' AND comp_code='$inv_stn' AND month(invdate)='$month' AND year(invdate)='$year'")->fetch(PDO::FETCH_OBJ);
                            // console_log("SELECT SUM(netamt) as balamt FROM invoice WHERE cust_code='$moinvrow->cust_code' AND comp_code='$inv_stn' AND month(invdate)='$month' AND year(invdate)='$year' order by month(invdate),year(invdate) ASC");
                                   echo "<td>".number_format($getmoinvnet->balamt)."</td>"; 
                              }
                    $invmototnet = $db->query("SELECT SUM(netamt) as 'mocutotnet' FROM invoice WHERE cust_code='$moinvrow->cust_code' AND comp_code='$inv_stn' AND  invdate BETWEEN '$inv_month_from' AND '$inv_month_to'")->fetch(PDO::FETCH_OBJ);
                    //$invmototrcvd = $db->query("SELECT SUM(rcvdamt) as 'mocutotrcvd' FROM invoice WHERE cust_code='$moinvrow->cust_code' AND comp_code='$inv_stn' AND  invdate BETWEEN '$inv_month_from' AND '$inv_month_to'")->fetch(PDO::FETCH_OBJ);
                    echo "<td>".number_format($invmototnet->mocutotnet)."</td>
                    </tr>";
               }
               //sum_of_invs_amt month wise
               echo "<tr>
               <th></th>
               <th></th>
               <th></th>
               <th class='align-right'>Total</th>";
         
               $monthinv2sum = $db->query("SELECT SUM(netamt) as balamt FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' $invr_brconditions $invr_custconditions ")->fetch(PDO::FETCH_OBJ);
               $monthinv2 = $db->query("SELECT DISTINCT year(invdate) as month_year,month(invdate) as month_invdate FROM invoice WHERE flag!=0 and invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' $invr_brconditions $invr_custconditions  order by year(invdate) ASC, month(invdate) ASC");
                          while($cdataminv = $monthinv2->fetch(PDO::FETCH_OBJ)){
                              $month = $cdataminv->month_invdate;
                              $year = $cdataminv->month_year;
                              $getmoinvnet = $db->query("SELECT SUM(netamt) as balamt FROM invoice WHERE comp_code='$inv_stn' AND month(invdate)='$month' AND year(invdate)='$year' $invr_brconditions $invr_custconditions ")->fetch(PDO::FETCH_OBJ);
                              echo "<th>".number_format($getmoinvnet->balamt)."</th>"; 
                              }
                              echo "<th>".number_format($monthinv2sum->balamt)."</th>"; 
               echo "</tr>";
           }
           else if($reporttype=='monthwiseinvoiceoutstandingsummary'){

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
                    $invr_custconditions = "AND cust_code='$inv_customer'";
               }
               $monthinv = $db->query("SELECT DISTINCT year(invdate) as month_year,month(invdate) as month_invdate FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' $invr_brconditions $invr_custconditions  order by month(invdate),year(invdate) ASC");
               
               echo"<thead>
               <tr>
               <th>S.no</th>
               <th class='align-left'>Cust Code</th>
               <th class='align-left'>Cust Name</th>";
               echo " <th class='align-left'>Branch Code</th>";
               
               while($monthinvrow=$monthinv->fetch(PDO::FETCH_OBJ))
               {
                    $monthNum = $monthinvrow->month_invdate;
                    $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                    echo "<th class='align-left'>".date("M - y", strtotime($monthName))."</th>";
               }
               echo "<th class='align-left'>Total</th>
               </tr>
               </thead><tbody>";

               //looping table datas
               $getmocusts = $db->query("SELECT DISTINCT(cust_code),br_code FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' $invr_brconditions $invr_custconditions ");

               $sno=0;
               while($moinvrow = $getmocusts->fetch(PDO::FETCH_OBJ)){$sno++;
               $getcustname = $db->query("SELECT top 1 cust_name FROM customer WHERE cust_code = '$moinvrow->cust_code' AND comp_code = '$inv_stn'")->fetch(PDO::FETCH_OBJ);
                    echo "<tr>
                          <td>$sno</td>
                          <td>".$moinvrow->cust_code." </td>
                           <td>".$getcustname->cust_name."</td>
                           <td>".$getcustname->cust_name."</td>";
                          
                          $monthinv2 = $db->query("SELECT DISTINCT year(invdate) as month_year,month(invdate) as month_invdate FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' $invr_brconditions $invr_custconditions  ");
                                                    
                          while($cdataminv = $monthinv2->fetch(PDO::FETCH_OBJ)){
                               $month = $cdataminv->month_invdate;
                               $year = $cdataminv->month_year;
                              $getmoinvnet = $db->query("SELECT SUM(netamt-rcvdamt) as balamt FROM invoice WHERE cust_code='$moinvrow->cust_code' AND comp_code='$inv_stn' AND month(invdate)='$month' AND year(invdate)='$year' AND status='Pending'")->fetch(PDO::FETCH_OBJ);
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
               <th></th>
               <th class='align-right'>Total</th>";
               
               $monthinv2sum = $db->query("SELECT SUM(netamt-rcvdamt) as balamt FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' $invr_brconditions $invr_custconditions ")->fetch(PDO::FETCH_OBJ);
               $monthinv2 = $db->query("SELECT DISTINCT year(invdate) as month_year,month(invdate) as month_invdate FROM invoice WHERE invdate BETWEEN '$inv_month_from' AND '$inv_month_to' AND comp_code='$inv_stn' AND status='Pending' AND status='Pending' $invr_brconditions $invr_custconditions ");
                 
                          while($cdataminv = $monthinv2->fetch(PDO::FETCH_OBJ)){
                              $month = $cdataminv->month_invdate;
                              $year = $cdataminv->month_year;
                              $getmoinvnet = $db->query("SELECT SUM(netamt-rcvdamt) as balamt FROM invoice WHERE comp_code='$inv_stn' AND month(invdate)='$month' AND year(invdate)='$year' AND status='Pending' $invr_brconditions $invr_custconditions ")->fetch(PDO::FETCH_OBJ);
                              echo "<th>".number_format($getmoinvnet->balamt)."</th>"; 
                              }
                              echo "<th>".number_format($monthinv2sum->balamt)."</th>"; 
               echo "</tr>";
           }
           
           else if($reporttype=='groupinvoicesummary')
           {
              
           }
           else if($reporttype=='unvoicesummary')
           {
              echo "<thead>
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
              $unvoice = $db->query("SELECT * FROM credit WHERE tdate BETWEEN '$inv_date_from' AND '$inv_date_to' AND status='Billed' ORDER BY tdate");$sno=0;
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
               if(empty($inv_customer) || trim($inv_customer)=='ALL'){
                    $invr_custconditions = "";
               }
               else{
                    $invr_custconditions = " AND cust_code='$inv_customer'";
               }
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
              $gstvoice = $db->query("SELECT * FROM invoice WHERE invdate BETWEEN '$inv_date_from' AND '$inv_date_to' $invr_custconditions ORDER BY invdate");$sno=0;
              while($rowgstvoice = $gstvoice->fetch(PDO::FETCH_OBJ)){$sno++;
                   echo "<tr>
                   <td>$sno</td>
                   <td>".date("d-M-Y",strtotime($rowgstvoice->invdate))."</td>
                   <td>$rowgstvoice->comp_code</td>
                   <td>$rowgstvoice->br_code</td>
                   <td>$rowgstvoice->cust_code</td>
                   <td>".number_format($rowgstvoice->igst,2)."</td>
                   <td>".number_format($rowgstvoice->cgst,2)."</td>
                   <td>".number_format($rowgstvoice->sgst,2)."</td>
                   </tr>";
              }
              echo "<tbody>";
           }
           else if($reporttype=='ccinvoicesummary'){
               if(empty($inv_date_from)){
                    $invr_date = "from_date = '$invtran_date_from' and to_date='$invtran_date_to'";
               }else{
                    $invr_date = "ccinvdate BETWEEN '$inv_date_from' AND '$inv_month_from'";

               }
               echo "<thead>
               <tr>
               <th>S.No</th>
               <th>Branch</th>             
               <th>Inv No</th>
               <th>Inv Date</th>
			   <th>Tr Frm Dt</th>
               <th>Tr To Dt</th>
               <th>Inv Month</th>
               <th>CC Code</th>
               <th>CC Name</th>
               <th>Total Cno</th>              
               <th>Collection</th>
               <th>Commission Amt</th>
               <th>TDS</th>
               <th>Net Comm Amount</th>
               <th>Cheque</th>
               <th>Bank</th>
               <th>Bank Branch</th>
               <th>A/c No</th>
               <th>IFSC</th>
               <th>Commission %</th>
               <th>Pan No</th>
               <th>Phone No</th>
               <th>BrMgr Commission</th>
               <th>Br Mail Id</th>
               <th>CC Mail Id</th>
              </tr>
              </thead><tbody>";
              $getdata = $db->query("SELECT *,month(ccinvdate) as monthinvoice,
              (select checkue from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as checkque
              ,(select bank_name from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as bankname
              ,(select bank_branch from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as bankbranch 
              ,(select bank_acc_no from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as acno
              ,(select ifsc from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as ifsc
              ,(select percent_domscomsn from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as percent_domscomsn
              ,(select pancardno from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as pan
              ,(select cc_phone from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as phone
              ,(select cc_email from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as cc_email
              ,(select cc_name from collectioncenter as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code AND cc.cc_code=c.cc_code) as Collcent
              ,(select commission from branch as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code) as commission 
              ,(select br_email from branch as cc where cc.stncode=c.station_code AND cc.br_code=c.br_code) as br_mail FROM ccinvoice as c where status='Invoiced'  AND  $invr_date AND station_code='$inv_stn'");
              console_log($getdata);
              while($rowcash = $getdata->fetch(PDO::FETCH_OBJ)){$sno++;
               $date = strtotime($rowcash->from_date);
               $con_month = date('M ', $date);
                  $comissionval = $rowcash->cc_tot_comm;
                  $pancard = $rowcash->pan;
                  
     //   function tdscharge($comissionval,$pancard){
                  
                  $tdsvalue = '20.00';
                  if($tdsvalue <= $comissionval && !empty($pancard)){
                     if($pancard[3]=='P' OR $pancard[3]=='H'){
                         $getval = ($comissionval*0.01);
                     }else{
                         $getval = ($comissionval*0.02);
                     }
                  }else{
                    
                    $getval = '0'; 
                  }
               //    return $getval; 
               //   }
               if(25>$rowcash->ccinvamt){
                $value =  0;
            }else{
                if(25 <= $rowcash->ccinvamt && 1000>$rowcash->ccinvamt){
                     $value =  25;
                }else{
                     if(1000 <= $rowcash->ccinvamt && 5000>$rowcash->ccinvamt){
                          $value =  50;
                     }else{
                          if(5000<=$rowcash->ccinvamt){
                               $value =  100;
                          }else{
                               
                          }
                     }
                }
            }
           
            $commission_amt = number_format($rowcash->cc_tot_comm,2);
            $tdsv = number_format($getval,2);
            $netcomission_amt = number_format($rowcash->cc_tot_comm-$getval,2);
                   echo "<tr>
                   <td>$sno</td>
                   <td>$rowcash->br_code</td>
                   <td>$rowcash->ccinvno</td>
                   <td>".date("d-M-Y", strtotime($rowcash->ccinvdate))."</td>  
				   <td>".date("d-M-Y", strtotime($rowcash->from_date))."</td>     
                   <td>".date("d-M-Y", strtotime($rowcash->to_date))."</td>                
                   <td>$con_month</td>
                   <td>$rowcash->cc_code</td>
                   <td>$rowcash->Collcent</td>
                   <td>$rowcash->cc_tot_cons</td>                  
                   <td>".number_format($rowcash->ccinvamt,2)."</td>
                   <td>".((explode(".",$commission_amt)[1] >= 50)? ceil($commission_amt) : $commission_amt)."</td>                     
                   <td>".((explode(".",$tdsv)[1] >= 50)? ceil($tdsv) : $tdsv)."</td>
                   <td>".((explode(".",$netcomission_amt)[1] >= 50)? ceil($netcomission_amt) : $netcomission_amt)."</td>
                   <td>$rowcash->checkque</td>
                   <td>$rowcash->bankname</td>
                   <td>$rowcash->bankbranch</td>
                   <td>$rowcash->acno</td>
                   <td>$rowcash->ifsc</td>
                   <td>$rowcash->percent_domscomsn</td>
                   <td>$rowcash->pan</td>
                   <td>$rowcash->phone</td>
                   <td>".number_format($value,2)."</td>
                   <td>$rowcash->br_mail</td>
                   <td>$rowcash->cc_email</td>
                   </tr>";
              }
              echo "<tbody>";
           }  

      }

      //fetch data for missing report
      if(isset($_POST['missed_report_type'])){
          
           $missed_report_type = $_POST['missed_report_type'];
           $missed_branch      = $_POST['missed_branch'];
           $missed_customer    = $_POST['missed_customer'];
           $missed_station    = $_POST['missed_station'];
           $miss_date_from    = $_POST['miss_date_from'];
           $miss_date_to      = $_POST['miss_date_to'];
           $miss_month_from    = $_POST['miss_month_from'];
           $miss_month_to     = $_POST['miss_month_to'];
           $miss_year         = $_POST['miss_year'];
           $missed_cc          = $_POST['missed_cc'];
           $missed_serial         = $_POST['missed_serial'];
          
           if($missed_report_type=='Unallocated'){
               if(!empty($miss_date_from) && !empty($miss_date_to)){
                    $datefilter = " AND tdate BETWEEN '$miss_date_from' AND '$miss_date_to'";
               }
               else{
                    $datefilter = " ";
               }
               echo"<thead>
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
               $unallocated = $db->query("SELECT * FROM opdata WITH (NOLOCK) WHERE origin='$missed_station' $datefilter AND (select count(*) from cnotenumber where cnote_station=opdata.origin and cnote_number=opdata.cno and br_code is null and cust_code is null)>0");
               while($unalloc_row = $unallocated->fetch(PDO::FETCH_OBJ)){ $usno++;
                    echo "<tr>
                    <td>".$usno."</td>
                    <td>".$unalloc_row->origin."</td>
                    <td>".date("d-M-Y",strtotime($unalloc_row->tdate))."</td>
                    <td>".$unalloc_row->cno."</td>
                    <td>".$unalloc_row->destn."</td>
                    <td>".$unalloc_row->wt."</td>
                    <td>".$unalloc_row->mode_code."</td>
                    <td>".$unalloc_row->ttype."</td>
                    <td>UnAllocated</td>
                    </tr>";
               }
               echo "</tbody>";
           }
           else if($missed_report_type=='UnKnown'){
               if(!empty($miss_date_from) && !empty($miss_date_to)){
                    $datefilter = " AND tdate BETWEEN '$miss_date_from' AND '$miss_date_to'";
               }
               else{
                    $datefilter = " ";
               }
               echo"<thead>
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
               $unallocated = $db->query("SELECT * FROM opdata WITH (NOLOCK) WHERE origin='$missed_station' $datefilter AND (select count(*) from cnotenumber where cnote_station=opdata.origin and cnote_number=opdata.cno)=0 AND (select top 1 origin from opdatabatch where origin='$missed_station' and cno=opdata.cno) IS NOT NULL order by tdate");
               while($unalloc_row = $unallocated->fetch(PDO::FETCH_OBJ)){ $usno++;
                    echo "<tr>
                    <td>".$usno."</td>
                    <td>".$unalloc_row->origin."</td>
                    <td>".date("d-M-Y", strtotime($unalloc_row->tdate))."</td>
                    <td>".$unalloc_row->cno."</td>
                    <td>".$unalloc_row->destn."</td>
                    <td>".$unalloc_row->wt."</td>
                    <td>".$unalloc_row->mode_code."</td>
                    <td>".$unalloc_row->ttype."</td>
                    <td>UnKnown</td>
                    </tr>";
               }
               echo "</tbody>";
           }
           else if($missed_report_type=='UnUsedCC'){
               if(!empty($miss_date_from) && !empty($miss_date_to)){
                    $datefilter = "AND date_added BETWEEN '$miss_date_from' AND '$miss_date_to'";
               }
               else{
                    $datefilter = " ";
               }
               if($missed_branch=='ALL' || $missed_branch==''){
                    $brcondition = '';
               }
               else{
                    $brcondition = " AND br_code='$missed_branch'";
               }
               if($missed_cc=='ALL' || $missed_cc==''){
                    $cccondition = '';
               }
               else{
                    $cccondition = " AND cust_code='$missed_cc'";
               }
               echo "<thead>
               <tr>
               <th class='align-left'>S.No</th>
               <th class='align-left'>BrCode</th>
               <th class='align-left'>CC Code</th>
               <th class='align-left'>Allot Date</th>
               <th class='align-left'>Allot Id</th>
               <th class='align-left'>CnoteSerial</th>
               <th class='align-left'>CnoteCount</th>
               <th class='align-left'>Status</th>
               </tr></thead><tbody>";
               $usno=0;
               //$unallocated = $db->query("SELECT * FROM opdata WHERE origin='$missed_station' $datefilter AND (select count(*) from cnotenumber where cnote_station=opdata.origin and cnote_number=opdata.cno)=0 order by tdate");
              // $unallocated = $db->query("SELECT br_code,cust_code,allot_date,allot_cc_no,cnote_serial,count(*) as cnotecount FROM cnotenumber WHERE cnote_station='$missed_station' AND cnote_status='Allotted' AND LEN(allot_cc_no)>0 $brcondition $custcondition $cccondition $datefilter  GROUP BY br_code,cust_code,allot_date,allot_cc_no,cnote_serial ORDER BY cust_code");
			  $unallocated = $db->query("SELECT br_code,cust_code,allot_date,allot_cc_no,cnote_serial,cnote_number FROM cnotenumber WHERE cnote_station='$missed_station' AND cnote_status='Allotted' AND LEN(allot_cc_no)>0 $brcondition $custcondition $cccondition $datefilter ORDER BY cust_code");
               while($unalloc_row = $unallocated->fetch(PDO::FETCH_OBJ)){ $usno++;
                    echo "<tr>
                    <td>".$usno."</td>
                    <td>".$unalloc_row->br_code."</td>
                    <td>".$unalloc_row->cust_code."</td>
                    <td>".date("d-M-Y", strtotime($unalloc_row->allot_date))."</td>
                    <td>".$unalloc_row->allot_cc_no."</td>
                    <td>".$unalloc_row->cnote_serial."</td>
                    <td>".$unalloc_row->cnote_number."</td>
                    <td>Allotted</td>
                    </tr>";
               }
               echo "</tbody>";
           }
           else if($missed_report_type=='UnInvoiced'){
               if(!empty($miss_date_from) && !empty($miss_date_to)){
                    $datefilter = " AND tdate BETWEEN '$miss_date_from' AND '$miss_date_to'";
               }
               else{
                    $datefilter = " ";
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
               echo"<thead>
               <tr>
               <th class='align-left'>S.No</th>
               <th class='align-left'>Cno</th>
               <th class='align-left'>Tdate</th>
               <th class='align-left'>BrCode</th>
               <th class='align-left'>CustCode</th>
               <th class='align-left'>Destn</th>
               <th class='align-left'>wt</th>
               <th class='align-left'>Pcs</th>
               <th class='align-left'>mode</th>
               <th class='align-left'>Amt</th>
               <th class='align-left'>Status</th>
               </tr></thead><tbody>";
               $usno=0;
               $uninvoiced = $db->query("SELECT * FROM credit WITH (NOLOCK) WHERE comp_code='$missed_station' $datefilter $brcondition $custcondition AND status='Billed' order by tdate");
               while($uninvoiced_row = $uninvoiced->fetch(PDO::FETCH_OBJ)){ $usno++;
                    echo "<tr>
                    <td>".$usno."</td>
                    <td>".$uninvoiced_row->cno."</td>
                    <td>".date("d-M-Y", strtotime($uninvoiced_row->tdate))."</td>
                    <td>".$uninvoiced_row->br_code."</td>
                    <td>".$uninvoiced_row->cust_code."</td>
                    <td>".$uninvoiced_row->dest_code."</td>
                    <td>".$uninvoiced_row->wt."</td>
                    <td>".$uninvoiced_row->pcs."</td>
                    <td>".$uninvoiced_row->mode_code."</td>
                    <td>".$uninvoiced_row->amt."</td>
                    <td>UnInvoiced</td>
                    </tr>";
               }
               echo "</tbody>";
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
               // if($missed_serial=='PRO'){
               //      $oprigin = "AND oporigin='PRO'";
               // }
               // else{
               //      $oprigin = "AND oporigin='$missed_station'";
               // }
               if($missed_report_type=='custub'){
                    $missedtype_filter = "AND cust_code IS NOT NULL";
               }
               else if($missed_report_type=='custau'){
                    $missedtype_filter = "AND cust_code IS NOT NULL AND opstatus is NULL";
               }
               else if($missed_report_type=='branchau'){
                    $missedtype_filter = "AND cust_code IS NULL AND opstatus is NULL";
               }
               else if($missed_report_type=='branchub'){
                    $missedtype_filter = "AND cust_code IS NULL";
               }
               //if($missed_report_type=='custau' || $missed_report_type=='branchau'){
               echo"<thead>
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
               <th class='align-left'>Cnote Status</th>
               </tr>
               </thead><tbody>";
               if($missed_report_type=='branchau'){
               $opdata = $db->query("SELECT * FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$missed_station' AND (cnote_status='Allotted' or cnote_status='Billed-Void')  $missedserialcondition $datefilter $brcondition $custcondition $nullcondition $missedtype_filter ORDER BY tdate");
              console_log($opdata);
          }
          if($missed_report_type=='branchub'){
            $opdata = $db->query("SELECT * FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$missed_station' AND cnote_status='Allotted' AND (SELECT count(cno) FROM credit WHERE cno=cnote_number AND comp_code=cnote_station)=0 AND (SELECT count(cno) FROM cash WHERE cno=cnote_number AND comp_code=cnote_station)=0 AND opstatus='Unbilled' $missedserialcondition $datefilter $brcondition $custcondition $missedtype_filter ORDER BY tdate");
               console_log($opdata);
          }
          if($missed_report_type=='custub'){
            $opdata = $db->query("SELECT * FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$missed_station'  AND (SELECT count(cno) FROM credit WHERE cno=cnote_number AND comp_code=cnote_station)=0 AND (SELECT count(cno) FROM cash WHERE cno=cnote_number AND comp_code=cnote_station)=0 AND opstatus='Unbilled' $missedserialcondition $datefilter $brcondition $custcondition $missedtype_filter ORDER BY tdate");
            console_log($opdata);
       }
       if($missed_report_type=='custau'){
            $opdata = $db->query("SELECT * FROM cnotenumber WITH (NOLOCK) WHERE cnote_station='$missed_station' AND (cnote_status='Allotted' or cnote_status='Billed-Void')  $missedserialcondition $datefilter $brcondition $custcondition $missedtype_filter ORDER BY tdate");
            console_log($opdata);
       }
               $sno=0;
               while($oprow = $opdata->fetch(PDO::FETCH_OBJ)){$sno++;
                    echo "<tr>
                    <td>$sno</td>
                    <td>".date("d-M-Y", strtotime($oprow->tdate))."</td>";
                    if($missed_report_type=='custub' || $missed_report_type=='custau'){
                         echo "<td>$oprow->br_code</td>
                               <td>$oprow->cust_code</td>";
                    }
                    else{
                         echo "<td>$oprow->br_code</td>";
                    }
                    echo "<td>$oprow->cnote_number</td>
                    <td>$oprow->cnote_serial</td>
                    <td >$oprow->opdest</td>
                    <td >".number_format($oprow->opwt,3)."</td>
                    <td >$oprow->opmode</td>
                    <td>Unbilled</td>
                    <td>$oprow->cnote_status</td>
                    </tr>";
               }
               echo "</tbody>";
          }

      }
      //generate report for customer reporttype
      if(isset($_POST['custrep_type'])){
           $custrep_type = $_POST['custrep_type'];
           $custrep_branch = $_POST['custrep_branch'];
           $custrep_customer = $_POST['custrep_customer'];
           $custrep_stn = $_POST['custrep_stn'];
           $ledger_date_from = $_POST['ledger_date_from'];
           $ledger_date_to = $_POST['ledger_date_to'];
		   $checkdate  = $_POST['checkdate'];
           $as_on_date = $_POST['as_on_date'];
           if($custrep_type=='custagewisereport'){
               //Generate for all branch customers
               if($custrep_branch=='ALL' || empty($custrep_branch)){
                    $branchcondition = "";
                }
                else{
                   $branchcondition = "AND i.br_code='$custrep_branch'";
                }
                if($custrep_customer=='ALL' || trim($custrep_customer)==''){
                    $customercondition = "";
                }
                else{
                   $customercondition = "AND i.cust_code='$custrep_customer'";
                }
				if($checkdate == 1){
                    $datefilter = "AND i.date_added BETWEEN '$ledger_date_from' AND '$ledger_date_to' AND i.date_added <= '$as_on_date'";
                }
                else{
                    $datefilter = "AND i.date_added <= '$as_on_date'";
                }
                   echo "<thead>
                   <tr>
                   <th class='align-left'>S.No</th>
                   <th class='align-left'>Company</th>
                   <th class='align-left'>Branch</th>
                   <th class='align-left'>Customer code</th>
                   <th class='align-left'>Customer / Franchise</th>
                   <th class='align-right'>OutStanding</th>
                   <th class='align-right'>No of Invoice</th>
                   </tr>
                   </thead><tbody>";
              //looping all customers
               $custagereport = $db->query("SELECT i.br_code,i.cust_code,c.cust_name,count(i.cust_code) counts,sum(netamt-rcvdamt) pending FROM invoice i LEFT JOIN customer c ON i.cust_code=c.cust_code and i.comp_code=c.comp_code WHERE i.comp_code='$custrep_stn' $branchcondition $customercondition $datefilter AND status='Pending'  group by i.cust_code,c.cust_name,i.br_code");
               $sno=0;
               while($custagewiseall = $custagereport->fetch(PDO::FETCH_OBJ)){$sno++;
                        echo "<tr>
                        <td>".$sno."</td>                         
                        <td>".$custrep_stn."</td>                         
                        <td>".$custagewiseall->br_code."</td>
                        <td>".$custagewiseall->cust_code."</td>
                        <td>".$custagewiseall->cust_name."</td>
                        <td style='text-align:right'>".number_format($custagewiseall->pending,2)."</td>
                        <td style='text-align:right'>".$custagewiseall->counts."</td>
                        </tr>";
               }
               echo "</tbody>";

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
                if($checkdate==1){
                    $datefilter1 = "AND date_added BETWEEN '$ledger_date_from' AND '$ledger_date_to' AND date_added <= '$as_on_date'";
                }else{
                    $datefilter1 = "AND date_added <= '$as_on_date'";
                }

                if($checkdate==1){
                    $datefilter = "AND i.date_added BETWEEN '$ledger_date_from' AND '$ledger_date_to' AND i.date_added <= '$as_on_date'";
                }else{
                    $datefilter = "AND i.date_added <= '$as_on_date'";
                }
                    echo"<thead>
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
              $pendingcustomers = $db->query("SELECT cust_code,sum(netamt-rcvdamt) pendingamt,COUNT(cust_code) pend_count FROM invoice with(nolock) WHERE comp_code='$custrep_stn' AND status='Pending' $branchcondition $cust_condition $datefilter1 GROUP BY cust_code");
              $ics=0;
              console_log($pendingcustomers); 
              while($pendcusts = $pendingcustomers->fetch(PDO::FETCH_OBJ)){
               $ccustpinv = $db->query("SELECT  i.status,i.cust_code,i.br_code,c.cust_name,i.cust_code,invno,invdate,netamt,c.cust_email,c.cust_mobile,netamt-rcvdamt pending FROM invoice i with(nolock) LEFT JOIN customer c ON i.cust_code=c.cust_code and i.comp_code=c.comp_code WHERE i.comp_code='$custrep_stn' AND i.cust_code='$pendcusts->cust_code' AND status='Pending' $datefilter");
               $lc=0;
                   while($cinvpndrow = $ccustpinv->fetch(PDO::FETCH_OBJ)){$lc++;$ics++;
                   echo "<tr>
                         <td>$ics</td>
                         <td>".$custrep_stn."</td>
                         <td>".$cinvpndrow->br_code."</td>
                         <td>".$cinvpndrow->cust_code."</td>
                         <td>".$cinvpndrow->cust_name."</td>
                         <td>".$cinvpndrow->invno."</td>
                         <td>".date("d-M-Y", strtotime($cinvpndrow->invdate))."</td>
                         <td style='text-align:right'>".number_format($cinvpndrow->netamt,2)."</td>
                         <td style='text-align:right'>".number_format($cinvpndrow->pending,2)."</td>";
                         if($pendcusts->pend_count == $lc){ // if this is the last item
                              echo "<th style='text-align:right'>".round($pendcusts->pendingamt)."</th>";
                          }
                          else{
                              echo "<td></td>";
                          }
                         echo "<td>".$cinvpndrow->status."</td>
                         <td></td>
                         <td>".$cinvpndrow->cust_mobile."</td>
                         <td>".$cinvpndrow->cust_email."</td>
                   </tr>";
                   }
              }
                echo "</tbody>";

           }
           else if($custrep_type=='customeroutgroup'){

               //Generate for all branch customers
               if($custrep_branch=='ALL' || empty($custrep_branch)){
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
				if($custrep_customer=='ALL' || trim($custrep_customer)==''){
                    $customercondition = "";
                }
                else{
                   $customercondition = "AND cust_code='$custrep_customer'";
                }
                if($checkdate==1){
                    $datefilter1 = "AND date_added BETWEEN '$ledger_date_from' AND '$ledger_date_to' AND date_added <= '$as_on_date'";
                }else{
                    $datefilter1 = "AND date_added <= '$as_on_date'";
                }
                   echo"<thead>
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
               $custagereport = $db->query("SELECT DISTINCT(cust_code) FROM invoice WHERE comp_code='$custrep_stn' $branchcondition $customercondition $datefilter1 AND status='Pending'");
               $sno=0;
               while($custagewiseall = $custagereport->fetch(PDO::FETCH_OBJ)){$sno++;
                    //get customer datum
                    $custdatum = $db->query("SELECT * FROM customer WHERE cust_code='$custagewiseall->cust_code' AND comp_code='$custrep_stn'")->fetch(PDO::FETCH_OBJ);
                    //get last 30days record
                    $last30 = $db->query("SELECT SUM(netamt-rcvdamt) as last30 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' $datefilter1 AND invdate >= DATEADD(day, -30, getdate())")->fetch(PDO::FETCH_OBJ);
                    $last60 = $db->query("SELECT SUM(netamt-rcvdamt) as last60 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' $datefilter1 AND invdate <= DATEADD(day, -30, getdate()) AND invdate >= DATEADD(day, -60, getdate())")->fetch(PDO::FETCH_OBJ);
                    $last90 = $db->query("SELECT SUM(netamt-rcvdamt) as last90 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' $datefilter1 AND invdate <= DATEADD(day, -60, getdate()) AND invdate >= DATEADD(day, -90, getdate())")->fetch(PDO::FETCH_OBJ);
                    $above90 = $db->query("SELECT SUM(netamt-rcvdamt) as above90 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' $datefilter1 AND invdate < DATEADD(day, -90, getdate())")->fetch(PDO::FETCH_OBJ);
                    $total = $db->query("SELECT SUM(netamt-rcvdamt) as total FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$custdatum->br_code' AND cust_code='$custdatum->cust_code' $datefilter1")->fetch(PDO::FETCH_OBJ);
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
               echo "</tbody>";

          }
           else if($custrep_type=='branchoutstandingsummary'){
               if($custrep_branch=='ALL' || $custrep_branch==''){
                    $brcondition = "";
                }
                else{
                   $brcondition = "AND br_code='$custrep_branch'";
                }
				if($checkdate==1){
                    $datefilter1 = "AND date_added BETWEEN '$ledger_date_from' AND '$ledger_date_to' AND date_added <= '$as_on_date'";
                }else{
                    $datefilter1 = "AND date_added <= '$as_on_date'";
                }
                   echo"<thead>
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
               $bragereport = $db->query("SELECT DISTINCT(br_code) FROM invoice WHERE comp_code='$custrep_stn' $brcondition $datefilter1 AND status='Pending'");
               $sno=0;
               while($bragewiseall = $bragereport->fetch(PDO::FETCH_OBJ)){$sno++;
                    $getbrname = $db->query("SELECT br_name FROM branch WHERE br_code='$bragewiseall->br_code' AND stncode='$custrep_stn'")->fetch(PDO::FETCH_OBJ);
                    $last30 = $db->query("SELECT SUM(netamt-rcvdamt) as last30 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' $datefilter1 AND invdate >= DATEADD(day, -30, getdate())")->fetch(PDO::FETCH_OBJ);
                    $last60 = $db->query("SELECT SUM(netamt-rcvdamt) as last60 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' $datefilter1 AND invdate <= DATEADD(day, -30, getdate()) AND invdate >= DATEADD(day, -60, getdate())")->fetch(PDO::FETCH_OBJ);
                    $last90 = $db->query("SELECT SUM(netamt-rcvdamt) as last90 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' $datefilter1 AND invdate <= DATEADD(day, -60, getdate()) AND invdate >= DATEADD(day, -90, getdate())")->fetch(PDO::FETCH_OBJ);
                    $above90 = $db->query("SELECT SUM(netamt-rcvdamt) as above90 FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' $datefilter1 AND invdate < DATEADD(day, -90, getdate())")->fetch(PDO::FETCH_OBJ);
                    $total = $db->query("SELECT SUM(netamt-rcvdamt) as total FROM invoice WHERE comp_code='$custrep_stn' AND br_code='$bragewiseall->br_code' $datefilter1")->fetch(PDO::FETCH_OBJ);
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
               echo "</tbody>";

          }
          else if($custrep_type=="customerledger"){
               echo "<thead>
               <th>S.no</th>
               <th>Date</th>
               <th>Particulars</th>
               <th>Debit</th>
               <th>Credit</th>
               <th>Balance</th>
               </thead><tbody>";
			   if($checkdate==1){
				$datefilter1 = "date_added BETWEEN '$ledger_date_from' AND '$ledger_date_to' AND date_added <= '$as_on_date'";
			}else{
				$datefilter1 = "date_added <= '$as_on_date'";
			}
               $cust_ledger = $db->query("SELECT * FROM invoice WHERE $datefilter1 AND comp_code='$custrep_stn' AND cust_code='$custrep_customer' ORDER BY invdate");$sno=0;
               while($rowcust_ledger = $cust_ledger->fetch(PDO::FETCH_OBJ)){$sno++;
                    $ledgercheck = $db->query("SELECT * FROM collection WHERE $datefilter1 AND invno='$rowcust_ledger->invno' AND comp_code='$custrep_stn' AND cust_code='$custrep_customer' AND status NOT LIKE '%Void' ORDER BY coldate");
                    $ledgercountcheck = $db->query("SELECT count(*) as led_count FROM collection WHERE $datefilter1 AND invno='$rowcust_ledger->invno' AND comp_code='$custrep_stn' AND cust_code='$custrep_customer' AND status NOT LIKE '%Void'")->fetch(PDO::FETCH_OBJ);
                    if($ledgercheck->rowCount()==0){
                         $ledger_balance = number_format($rowcust_ledger->netamt,2);
                    }
                    else{
                         $ledger_balance = '';
                    }
                    echo "<tr>
                    <td>$sno</td>
                    <td>".date("d-M-Y", strtotime($rowcust_ledger->invdate))."</td>
                    <td>To Invoice: $rowcust_ledger->invno</td>
                    <td>".number_format($rowcust_ledger->netamt,2)."</td>
                    <td></td>
                    <td>$ledger_balance</td>
                    </tr>";
                    //check for collecton is availabe for current invoice
                    $count = 0;
                    if($ledgercheck->rowCount()!=0){
                    while($rowledgercheck = $ledgercheck->fetch(PDO::FETCH_OBJ)){
                    $count++;
                    $colln = empty($rowledgercheck->remarks) ? "colln : $rowledgercheck->chqddno" : $rowledgercheck->remarks." ".substr($rowledgercheck->narration,0,5)." : ".$rowledgercheck->chqddno;
                    echo "<tr>
                    <td></td>
                    <td>".date("d-M-Y", strtotime($rowledgercheck->coldate))."</td>
                    <td>By $colln</td>
                    <td></td>
                    <td>".number_format($rowledgercheck->netcollamt,2)."</td>";
                    if($ledgercountcheck->led_count==$count){
                         echo "<td>".number_format($rowcust_ledger->netamt-$rowcust_ledger->rcvdamt,2)."</td>";
                    }
                    else{
                       echo  "<td>  </td>";
                    }
                    echo "</tr>";
                    }
                    }
                    echo "<tr style='height:25px'><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
               }
          }
      }
      //generate report for new business
      if(isset($_POST['newbs_report_type'])){
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
          echo "<thead>
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
               <td class='cno_count'>$newbstcnotes->cnotecounts</td>
               <td class='tot_amt' data-amt='$newbstcnotes->totalamt'>".number_format($newbstcnotes->totalamt,2)."</td>    
               </tr>";
               }
              }
         }
          //table total for new business  start
          echo "<tr>
          <td></td>
          <td></td>
          <td></td>
          <td><b>Total</b></td>
          <td class='set_count'><b></b></td>
          <td class='set_amt'><b></b></td>
          </tr>";
        //table total for new business  end
      }
      //generate report for sales report
      if(isset($_POST['newsales_report_type'])){
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
          echo "<thead>
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
      }
      //generate report for Cnote sales report
      if(isset($_POST['sales_report_type'])){
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
          echo"<thead>
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

               $salescusts1 = $db->query("SELECT cust_gstin FROM customer WHERE comp_code='$salescrow->stncode' AND cust_code='$salescrow->cust_code'")->fetch(PDO::FETCH_OBJ);
               $salescusts2 = $db->query("SELECT cust_name FROM customer WHERE cust_code='$salescrow->cust_code' AND comp_code='$salescrow->stncode'")->fetch(PDO::FETCH_OBJ);

             //  $salescusts1->cust_gstin
               echo "<tr>
               <td>".$sno."</td>  
               <td>".$salescrow->stncode."</td>  
               <td>".$salescrow->br_code."</td>
               <td>".$salescrow->cust_code."</td>
               <td>".$salescusts2->cust_name."</td>
               <td>".date("d-M-Y", strtotime($salescrow->date_added))."</td>
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
               <td>".((substr($salescusts1->cust_gstin,0,2)==substr($salescrow->stngstnumber,0,2))?number_format($salescrow->GST/2,2):'')."</td>
               <td>".((substr($salescusts1->cust_gstin,0,2)==substr($salescrow->stngstnumber,0,2))?number_format($salescrow->GST/2,2):'')."</td>
               <td>".((substr($salescusts1->cust_gstin,0,2)!=substr($salescrow->stngstnumber,0,2))?number_format($salescrow->GST,2):'')."</td>
               <td style='text-align:right'>".number_format($salescrow->NetAmt,2)."</td>
               <td style='text-align:right'>".$salescusts1->cust_gstin."</td>
               </tr>";
          }
          echo "</tbody>";
      }
         //generate report for tonnage
         if(isset($_POST['ton_reporttype'])){
          $ton_reporttype = $_POST['ton_reporttype'];
          $ton_date_from = $_POST['ton_date_from'];
          $ton_date_to = $_POST['ton_date_to'];
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
          $dev_reporttype = $_POST['dev_reporttype'];
          $dev_date_from = $_POST['dev_date_from'];
          $dev_date_to = $_POST['dev_date_to'];
          $dev_stncode = $_POST['dev_stncode'];
          $dev_filter = $_POST['dev_filter'];
          //console_log($dev_filter);exit;
          if($dev_reporttype=='weightdeviation'){
          echo "<table><thead><tr>
          <th>S.no</th>
          <th>Branch</th>
          <th>Customer</th>
          <th>CnoteNumber</th>
          <th style='width:80px;'>Bill date</th>
          <th style='text-align:right;'>Bill Wt</th>
          <th style='text-align:right;'>Op Wt</th>
          <th style='text-align:right;'>Bill mode</th>
          <th style='text-align:right;'>Op mode</th>
          <th style='text-align:right;'>Bill dest</th>
          <th style='text-align:right;'>Op dest</th>
          <th style='text-align:right;'>Bill Rate</th>
          <th style='text-align:right;'>Op Rate</th>
          </tr><tbody>";
          $sno=0;
          $wtdeviation = $db->query("SELECT [cnotenoid]
          ,[cnote_station]
          ,[br_code]
          ,[br_name]
          ,[cust_code]
          ,[bilwt]
          ,bdate
          ,[bilrate]
          ,[oprate]
          --  ,dbo.IBQamountupdate(CASE WHEN cust_code is null THEN 'RATE1' ELSE cust_code END,cnote_station,opdest,opwt,opmode,pod_origin) opamt  
          ,[opwt]
          ,[opmode]
          ,[opdest]
          ,[bilmode]
          ,[bildest]
          ,[oprate]
          ,[cnote_number]
          ,[cnote_status]
          FROM cnotenumber with(nolock) WHERE cnote_station='$dev_stncode' AND opwt IS NOT NULL AND bilwt IS NOT NULL AND bdate BETWEEN '$dev_date_from' AND '$dev_date_to'");
          while($rowwtdev = $wtdeviation->fetch(PDO::FETCH_OBJ)){
               if($rowwtdev->bilrate==$rowwtdev->oprate){

               }
               else{
                    if($dev_filter=='BRLTOR') // && (floatval($rowwtdev->bilrate)<floatval($rowwtdev->opamt))
                    {
                    if(floatval($rowwtdev->bilrate)<floatval($rowwtdev->oprate))
                    {
                    $sno++;
                    echo "<tr>
                    <td>$sno</td>
                    <td>$rowwtdev->br_code</td>
                    <td>$rowwtdev->cust_code</td>
                    <td>$rowwtdev->cnote_number</td>
                    <td>".date("d-M-Y", strtotime($rowwtdev->bdate))."</td>               
                    <td style='text-align:right;width:100px;'>".number_format($rowwtdev->bilwt,3)."</td>
                    <td style='text-align:right;width:100px;'>".number_format($rowwtdev->opwt,3)."</td>
                    <td style='text-align:right;width:100px;'>".$rowwtdev->bilmode."</td>
                    <td style='text-align:right;width:100px;'>".$rowwtdev->opmode."</td>
                    <td style='text-align:right;width:100px;'>".$rowwtdev->bildest."</td>
                    <td style='text-align:right;width:100px;'>".$rowwtdev->opdest."</td>
                    <td style='text-align:right;width:100px;'>".number_format($rowwtdev->bilrate,2)."</td>
                    <td style='text-align:right;width:100px;'>".number_format($rowwtdev->oprate,2)."</td>
                    </tr>";
                    }
                    }
                    else if($dev_filter=='ORLTBR') // && (floatval($rowwtdev->bilrate)<floatval($rowwtdev->opamt))
                    {
                    if(floatval($rowwtdev->bilrate)>floatval($rowwtdev->oprate))
                    {
                    $sno++;
                    echo "<tr>
                    <td>$sno</td>
                    <td>$rowwtdev->br_code</td>
                    <td>$rowwtdev->cust_code</td>
                    <td>$rowwtdev->cnote_number</td>
                    <td>".date("d-M-Y", strtotime($rowwtdev->bdate))."</td>               
                    <td style='text-align:right;width:100px;'>".number_format($rowwtdev->bilwt,3)."</td>
                    <td style='text-align:right;width:100px;'>".number_format($rowwtdev->opwt,3)."</td>
                    <td style='text-align:right;width:100px;'>".$rowwtdev->bilmode."</td>
                    <td style='text-align:right;width:100px;'>".$rowwtdev->opmode."</td>
                    <td style='text-align:right;width:100px;'>".$rowwtdev->bildest."</td>
                    <td style='text-align:right;width:100px;'>".$rowwtdev->opdest."</td>
                    <td style='text-align:right;width:100px;'>".number_format($rowwtdev->bilrate,2)."</td>
                    <td style='text-align:right;width:100px;'>".number_format($rowwtdev->oprate,2)."</td>
                    </tr>";
                    }
                    }
                    else {
                         $sno++;
                         echo "<tr>
                         <td>$sno</td>
                         <td>$rowwtdev->br_code</td>
                         <td>$rowwtdev->cust_code</td>
                         <td>$rowwtdev->cnote_number</td>
                         <td>".date("d-M-Y", strtotime($rowwtdev->bdate))."</td>               
                         <td style='text-align:right;width:100px;'>".number_format($rowwtdev->bilwt,3)."</td>
                         <td style='text-align:right;width:100px;'>".number_format($rowwtdev->opwt,3)."</td>
                         <td style='text-align:right;width:100px;'>".$rowwtdev->bilmode."</td>
                         <td style='text-align:right;width:100px;'>".$rowwtdev->opmode."</td>
                         <td style='text-align:right;width:100px;'>".$rowwtdev->bildest."</td>
                         <td style='text-align:right;width:100px;'>".$rowwtdev->opdest."</td>
                         <td style='text-align:right;width:100px;'>".number_format($rowwtdev->bilrate,2)."</td>
                         <td style='text-align:right;width:100px;'>".number_format($rowwtdev->oprate,2)."</td>
                         </tr>";
                    }

               }
          }
          echo "</tbody></table>";
     
         }
         if($dev_reporttype=='cashdeviation'){
          echo "<table><thead><tr>
          <th>S.no</th>
          <th>Branch</th>
     
          <th>CnoteNumber</th>
          <th style='width:80px;'>Bill date</th>
          <th style='text-align:right;'>Bill Wt</th>
          <th style='text-align:right;'>Bill mode</th>
          <th style='text-align:right;'>Bill dest</th>
          <th style='text-align:right;'>Amt</th>
          <th style='text-align:right;'>Ramt</th>
          <th style='text-align:right;'>Difference</th>
          </tr><tbody>";
          $getcashdeviation = $db->query("SELECT * FROM cash  where amt != ramt AND tdate BETWEEN '$dev_date_from' AND '$dev_date_to' AND comp_code='$dev_stncode' ORDER BY tdate DESC");
          while($rowcashdev = $getcashdeviation->fetch(PDO::FETCH_OBJ)){
               $difference = $rowcashdev->amt-$rowcashdev->ramt;
               $sno++;
               echo "<tr>
               <td>$sno</td>
               <td>$rowcashdev->br_code</td>
               <td>$rowcashdev->cno</td>
               <td>".date("Y-m-d", strtotime($rowcashdev->tdate))."</td>               
               <td style='text-align:right;width:100px;'>".number_format($rowcashdev->wt,3)."</td>
               <td style='text-align:right;width:100px;'>".$rowcashdev->mode_code."</td>
               <td style='text-align:right;width:100px;'>".$rowcashdev->dest_code."</td>
               <td style='text-align:right;width:100px;'>".number_format($rowcashdev->amt,2)."</td>
               <td style='text-align:right;width:100px;'>".number_format($rowcashdev->ramt,2)."</td>
               <td style='text-align:right;width:100px;'>".number_format($difference,2)."</td>
               </tr>";  
          }
          
         }
     }
      //gst report generation
      if(isset($_POST['gstreporttype'])){
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
           $b2binvoice = $db->query("SELECT invno,invdate,netamt,tds,invamt,igst,cgst,sgst,c.cust_gstin,c.cust_name  FROM invoice i RIGHT JOIN customer c ON i.comp_code=c.comp_code AND i.cust_code=c.cust_code WHERE invdate BETWEEN '$gst_date_from' AND '$gst_date_to' $gst_brcondition $gst_custcondition AND i.comp_code='$gst_stn' AND ISNULL(LEN(c.cust_gstin),0)>0 order by invdate");
           $sno=0;
          while($b2brow = $b2binvoice->fetch(PDO::FETCH_OBJ)){$sno++;
               echo "<tr>
               <td>$sno</td>
               <td>$b2brow->cust_gstin</td>
               <td>$b2brow->invno</td>
               <td>".date("d-M-Y", strtotime($b2brow->invdate))."</td>
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
               <td>$b2brow->cust_name</td>
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
               <td>".date("d-M-Y", strtotime($sales_gstrow->date_added))."</td>
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
               <td>".date("d-M-Y", strtotime($sales_gstrow->date_added))."</td>
               <td class='align-right'>".number_format($sales_gstrow->NetAmt,2)."</td>
               <td>Regular</td>
               <td></td>
               <td>18</td>
               <td class='align-right'>".number_format($sales_gstrow->GrandAmt,2)."</td>
               <td>0.00</td>
               <td class='align-right'>0.00</td>
               <td class='align-right'>".number_format($sales_gstrow->GST/2,2)."</td>
               <td class='align-right'>".number_format($sales_gstrow->GST/2,2)."</td>
               <td>$sales_gstrow->actual_custname</td>
               </tr>";
          }

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
               <td>".date("d-M-Y", strtotime($b2csrow->invdate))."</td>
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
                   <td>".date("d-M-Y", strtotime($cashb2csinvoicerow->tdate))."</td>
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
                   <td>".date("Y-m-d", strtotime($cashb2csinvoicerow->edate))."</td>
                   </tr>";
              }
              echo "</table>";
          }
      }


      if(isset($_POST['user_mail_excel'])){
          echo missingreport($db,$_POST['user_mail_excel'],$_POST['missed_report_type'],$_POST['miss_date_from'],$_POST['miss_date_to'],$_POST['missed_station'],$_POST['missed_branch'],$_POST['missed_customer']);
      }
      function missingreport($db,$user_mail_excel,$missed_report_type,$miss_date_from,$miss_date_to,$missed_station,$missed_branch,$missed_customer){
           $getusermail = $db->query("SELECT user_email FROM users WHERE user_name='$user_mail_excel'")->fetch(PDO::FETCH_OBJ);
          $result = $db->query("EXEC sendmissing_invoice_mail @report_user = '$user_mail_excel',@recepient = '$getusermail->user_email',@report_type= '$missed_report_type',@filter = 'date_added BETWEEN ''$miss_date_from'' AND ''$miss_date_to''',@missed_station='$missed_station',@filter2= '$missed_branch',@customer= '$missed_customer' ");
          return $result;
      }
      if(isset($_POST['total_inv_report_user'])){
          echo sendtotalinvoicereportbymail($db,$_POST['total_inv_report_user']);
      }
      function sendtotalinvoicereportbymail($db,$total_inv_report_user){
           $getusermail = $db->query("SELECT user_email FROM users WHERE user_name='$total_inv_report_user'")->fetch(PDO::FETCH_OBJ);
          $result = $db->query("EXEC SendOutstandingInvByMail @report_user = '$total_inv_report_user',@recepient = '$getusermail->user_email',@filter = ' status=''Pending'''");
          return $result;
      }

      function console_log($output, $with_script_tags = true) {
          $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
          ');';
          if ($with_script_tags) {
              $js_code = '<script>' . $js_code . '</script>';
          }
          echo $js_code;
      }