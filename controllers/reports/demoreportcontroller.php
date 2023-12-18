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

	 class demoreportcontroller extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - fetch';
             // $this->view->render('cnote/fetch');
          }
      }

error_reporting(0);

if(isset($_POST['book_date_from'])){

    // echo json_encode("wfdfdhgsw");
    // exit();
    
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
    
        
    if($book_rep_type=="bookcustomers"){

    if(empty($book_date_from)){

    }else{

     $book_conditions[] = "AND doe  BETWEEN '$book_date_from' AND '$book_date_to'";

    }

   if($bk_branch == 'ALL' || empty($bk_branch)){

   }else{

   $book_conditions[] = "br_code='$bk_branch'";

   }

   if($bk_customer == 'ALL' || empty($bk_customer)){
    
   }else{

      $book_conditions[] = "cust_code='$bk_customer'";

   }

 

    $book_condition = implode(' AND ',$book_conditions);

    $select_customer = $db->query("SELECT * FROM customer WHERE comp_code='$current_station' $book_condition");

//console_log($book_condition);

$b_sno=0;
echo "<thead>
<tr>
 <th style='text-align:left'>S.no</th>
 <th style='text-align:left'>Date</th>
 <th style='text-align:left'>Br Code</th>
 <th style='text-align:left'>Customer Code</th>
 <th style='text-align:left'>Customer Name</th>
 <th style='text-align:left'>Customer Address</th>
 <th style='text-align:left'>Customer City</th>
 <th style='text-align:left'>Customer State</th>
 <th style='text-align:left'>Customer Country</th>
 <th style='text-align:left'>Customer Phone</th>
 <th style='text-align:left'>Customer Fax</th>
 <th style='text-align:left'>Customer Email</th>
 <th style='text-align:left'>Customer Mobile</th>
 <th style='text-align:left'>Customer Remarks</th>
 <th style='text-align:left'>Customer Status</th>
 <th style='text-align:left'>Customer GST</th>
 <th style='text-align:left'>Customer Active</th>";
 

      echo "</tr>
      </thead>";
      while($bkrow = $select_customer->fetch(PDO::FETCH_OBJ)){ $b_sno++;
        echo "<tr>";
                echo "<td>".$b_sno."</td>";
                echo "<td>".$bkrow->doe."</td>";
                echo "<td>".$bkrow->br_code."</td>";
                echo "<td>".$bkrow->cust_code."</td>";
                echo "<td>".$bkrow->cust_name."</td>";
                echo "<td>".$bkrow->cust_addr."</td>";
                echo "<td>".$bkrow->cust_city."</td>";
                echo "<td>".$bkrow->cust_state."</td>";
                echo "<td>".$bkrow->cust_counrty."</td>";
                echo "<td>".($bkrow->cust_phone)."</td>";
                echo "<td>".($bkrow->cust_fax)."</td>";
                echo "<td>".($bkrow->cust_email)."</td>";
                echo "<td>".($bkrow->cust_mobile)."</td>";
                echo "<td>".($bkrow->cust_remarks)."</td>";
                echo "<td>".($bkrow->cust_status)."</td>";
                echo "<td>".($bkrow->cust_gstin)."</td>";
                echo "<td>".($bkrow->cust_active)."</td>";
            
        }
        echo "</tr>";
        exit();
    }

    if($book_rep_type=="bookcredit"){

        if(empty($book_date_from)){

        }else{
    
         $book_conditions[] = "AND tdate  BETWEEN '$book_date_from' AND '$book_date_to'";
    
        }

        if(!empty($weight_from) ||! number_format($weight_from,2) ==0.00){
            $book_conditions[] = "wt  BETWEEN '".number_format($weight_from,2)."' AND '$weight_to'";

        }else{
        }

        //   if(empty($weight_from)){

        // }else{
        //     $book_conditions[] = "wt BETWEEN '$weight_from' AND '$weight_to'";

        // }


    
       if($bk_branch == 'ALL' || empty($bk_branch)){
    
       }else{
    
       $book_conditions[] = "br_code='$bk_branch'";
    
       }
    
       if($bk_customer == 'ALL' || empty($bk_customer)){
    
       }else{
    
          $book_conditions[] = "cust_code='$bk_customer'";
    
       }

    

       if($book_destination == 'ALL' || empty($book_destination)){

       }else{
    
          $book_conditions[] = "dest_code='$book_destination'";
    
       }
       
       
    
         $book_condition = implode(' AND ',$book_conditions);
    
        $select_credit = $db->query("SELECT * FROM credit WHERE comp_code='$current_station' $book_condition");
    
    
  // console_log(strlen($weight_from));
   // console_log($book_condition);
    
    
    
    
    $b_sno=0;
    echo "<thead>
    <tr>
     <th style='text-align:left'>S.no</th>
     <th style='text-align:left'>Transaction Date</th>
     <th style='text-align:left'>Br Code</th>
     <th style='text-align:left'>Cust Code</th>
     <th style='text-align:left'>Weight</th>
     <th style='text-align:left'>pcs</th>
     <th style='text-align:left'>Mode Code</th>
     <th style='text-align:left'>Destination Code</th>
     <th style='text-align:left'>Amount</th>
     <th style='text-align:left'>Cnote Number</th>
     <th style='text-align:left'>Status</th>
     <th style='text-align:left'>Cnote Serial</th>
     <th style='text-align:left'>Cnote Type</th>
     <th style='text-align:left'>Invoice Number</th>
     <th style='text-align:left'>UserName</th>";
     
    
          echo "</tr>
          </thead>";
          while($bkrow = $select_credit->fetch(PDO::FETCH_OBJ)){ $b_sno++;
            echo "<tr>";
                    echo "<td>".$b_sno."</td>";
                    echo "<td>".$bkrow->tdate."</td>";
                    echo "<td>".$bkrow->br_code."</td>";
                    echo "<td>".$bkrow->cust_code."</td>";
                    echo "<td>".$bkrow->wt."</td>";
                    echo "<td>".$bkrow->pcs."</td>";
                    echo "<td>".$bkrow->mode_code."</td>";
                    echo "<td>".($bkrow->dest_code)."</td>";
                    echo "<td>".$bkrow->amt."</td>";
                    echo "<td>".$bkrow->cno."</td>";
                    echo "<td>".($bkrow->status)."</td>";
                    echo "<td>".($bkrow->cnote_serial)."</td>";
                    echo "<td>".($bkrow->cnote_type)."</td>";
                    echo "<td>".($bkrow->invno)."</td>";
                    echo "<td>".($bkrow->user_name)."</td>";
                
            }
            echo "</tr>";
            exit();

        }


}    
    
 
    
    function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
        ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }