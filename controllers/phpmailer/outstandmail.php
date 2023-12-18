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

	 class outstandmail extends My_controller
      {
           public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - outstandmail';
             // $this->view->render('cnote/fetch');
          }
      }
      
      require 'controllers/phpmailer/dompdf/autoload.inc.php';
      require 'controllers/phpmailer/PHPMailerAutoload.php';

      //require 'db.php';
      use Dompdf\Dompdf;
      
     if(isset($_POST['cust_code'])){
      //generating pdf using dompdf
      
      ob_start();
      
      ?>
      <!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>Invoice</title>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
          
        </head>
        <style>
       @page {
        size: A4;
        margin: 4mm 4mm 4mm 4mm;
       }
       @media print{
           /* header {
                position:fixed;
                background:#fff;
                float:none;
           }
           section{
                position: relative; top: 270px;
           }
          
           footer{
                page-break-inside: avoid;
                bottom:0;
           } */
           .amountcalc{
                page-break-inside: avoid;
           }
       }
        body{
           margin: auto;
           padding:15px;
           background:#fff;
           font-family:"Times New Roman", Times, serif;
        }
      .flex-container{
           display:flex;
      }
      .image{
           width:90%;
           height: 90px;
      }
      .dom{
           font-size:14px;
      }
      #company{
           width:320px;
           border:1px solid black;
           padding:15px;
           position: relative;
      }
      #project{
           margin-left:50%;
           margin-bottom:-210px;
           height:223px;
          width:350px; 
          border:1px solid black;
          /* margin-left:1%; */
          line-height:1.4;
          position: relative;
      }
      .gsthead{
           margin-top:20px;
      }
      .left-20{
           margin-left:100%;
      }
      .billdata{
           width:100%;
           font-size:12px;
           padding:none;
           padding-left:10px!important;
           margin-top:5px;
           height:20px;
           border-bottom:1px solid black;
      }
      .customerbox{
           padding:10px;
           font-size:18px;
      }
      .flex-box-3{
           width:33.3333%;
           float: left;
           position: relative;
      }
      .transdate{
           height:20px;
           width:630px;
           padding:10px;
           /* margin-top:-200px; */
           border-bottom: 1px solid black;
      }
      .centertb{
           text-align:right;
           margin-left:8px;
      }
      .center{
           text-align:left;
           margin-left:2px;
      }
      .tablehead{
           font-size:10px;
           line-height:2;
      }
      .tablehead th{
           border-bottom: 1px solid black; 
      }
      /* tbody{
          border-bottom: 1px solid black;  
      } */
      .border-bottom{
           border-bottom: 1px solid black; 
      }
      .cnotestable{
           width:100%;
      }
      .tablebody{
           font-size:12px;
      }
      .calculations{
           max-width:850px;
      }
      .righttb{
           text-align:right;
      }
      .lefttb{
           text-align:left;
      }
      .border-top{
           border-top:1px solid black;
      }
      .footer{
           width:600px;
      }
      .note{
           width:600px;
           padding:40px;
           font-size:12px;
      }
      .notehead{
           font-weight:bold;
           text-decoration: underline;
      }
      .bank-details{
           font-size:14px;
           padding:25px;
      }
      .bankhead{
           font-weight:bold;
      }
      .amountcalc{
          border-top: 1px solid gray;          
          page-break-inside: avoid;
          font-size: 13px;
     }
      .red{
           background:red!important;
      }
      .p{
           line-height:2px;
      }
      .image img{
        margin-left: 440px;
      }
      .column {
  float: left;
  width: 50%;
  padding: 10px;
 /* Should be removed. Only for demonstration */
}
.column1{
     padding: 10px;    
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
        </style>
        <body id="printable">
      <?php 
      include 'db.php';
      
           $invno = $_POST['cust_code'];
           $invnostn = $_POST['station'];
           $date = date(" jS  F Y ");
           $fetchdata = $db->query("SELECT * FROM invoice WHERE cust_code='$invno' AND comp_code='$invnostn'");
           $rowinvoice = $fetchdata->fetch(PDO::FETCH_OBJ);
           //POD info
      
           $getcompanyname = $db->query("SELECT * FROM station WHERE stncode='$rowinvoice->comp_code'");
           $rowcompany = $getcompanyname->fetch(PDO::FETCH_OBJ);

             $getcustomerinfo = $db->query("SELECT * FROM customer WHERE cust_code='$rowinvoice->cust_code' AND comp_code='$invnostn'");
           $rowcustomer = $getcustomerinfo->fetch(PDO::FETCH_OBJ);

           $gettotal = $db->query("SELECT sum(netamt-rcvdamt) as total FROM invoice WHERE cust_code='$invno'");
           $total = $gettotal->fetch(PDO::FETCH_OBJ);
      ?>
          <header>
         

                      <div class="row">
                  <div class="column"><strong>From</strong><br>
                   <?=$rowcompany->stnname;?><br>
                   <div><?=$rowcompany->stn_addr;?>,<br /><?=$rowcompany->stn_city;?>,<?=$rowcompany->stn_state;?><br>Phone :<?=$rowcompany->stn_mobile;?></div>
                      </div>
  <div class="column"><img src="views/image/TPC-Logo.jpg" class="image"></div>
</div>
      
            <div class="row">
            <div class="column"><strong>To</strong><br>
            <?=$rowcustomer->cust_name;?><br>
                   <div><?=$rowcustomer->cust_addr;?>,<br /><?=$rowcustomer->cust_city;?>,<?=$rowcustomer->cust_state;?>
                   <br>Branch :<?=$rowcompany->stn_mobile;?><br>GSTIN : <?=$rowcustomer->cust_gstin;?><br>Customer No:<?=$invno;?>
               </div>
                      </div>

  <div class="column" style="margin-left: 150px;"><b><?=$date;?></b></div>
          </div>
 

      
<div class="row">
  <div class="column"> <p>Dear Sir / Madam,<p></div>
  
</div>
<div class="row">
  <div class="column"><b>Sub: Pending out standing invoices and it's details<b></div>
  
</div>
<div class="row">
  <div class="column1"><p>Please find the list of pending out standing invoice details and it's amount. Please clear it immediately.<p></div>
  
</div> 
          </header>
           <section>
          
            <table class="cnotestable">
              <thead>
                <tr class="tablehead">
                <th>InvNo</th>
              <th>Bill Date</th>
              <th>Bill Amount</th>
              <th>Total Collection</th>
              <th>Balance</th>              
             
                </tr>
              </thead>
               <tbody class="tablebody">
               <?php

 $query = $db->query("SELECT * FROM invoice WHERE comp_code='$invnostn' AND cust_code='$invno' AND status='Pending'"); 
 
 
 while ($row = $query->fetch(PDO::FETCH_OBJ))
 {
  
               ?>
                    </tbody>
                          <tbody class="amountcalc">
                       <tr>
                        <td><?=$row->invno;?></td>
                        <td><?=$row->invdate;?></td>
                        <td><?=number_format($row->netamt,2)?></td>
                        <td><?=number_format($row->rcvdamt,2)?></td>
                        <td class='total'><?=number_format($row->netamt-$row->rcvdamt,2)?></td>
                    
                       </tr>
                      
              </tbody>
     
   <?php   }    ?>

 <tfoot>
    <tr>
      <th id="total"> </th>

      <td></td>
      <td></td>
      <td><b>Total :</b></td>
      <td id='settotal' style="border-bottom: 1px solid black;border-top: 1px solid black;"><b><?=number_format($total->total,2)?></b></td>
 
    </tr>
 
   </tfoot>
            </table>
           <div class="content1" style="padding: 10px;">
           <b>Note : If the payments are already settled, kindly provide the payment infomation</b>
           </div>
           <div class="content2" style="padding: 10px;">
           <b>PLEASE TREAT THIS LETTER AS TOP MOST URGENT</b>
           </div>
           <div class="content3" style="padding: 10px;">
           <b>Thanking you</b>
           </div>
           <footer style="margin-top: 90px;">
           for: <b><?=$rowcompany->stnname;?></b>
           <div class="authroty" style="margin-top: 40px;">
               <p>Authorized Signatory</p>
           </div>
           </footer>
          </section>
          
        </body>
      </html>
      
      <?php
      
      $dompdf = new Dompdf();
      $htmlcontents = ob_get_clean();
      $dompdf->loadHtml($htmlcontents);
      $dompdf->setPaper('A3', 'landscape');
      $dompdf->render();
     
      $attachment_file = $dompdf->output();
      
            
      $mail = new PHPMailer;
      //$mail->SMTPDebug = 2;
      $mail->IsSMTP();   // Set mailer to use SMTP
      $mail->Host = 'smtp.bizmail.yahoo.com';   // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;  // Enable SMTP authentication
      $mail->Username = 'pcbs@pcnl.in'; // your email id
      $mail->Password = 'hcygtgklfglvigvw'; // your password                      
      $mail->Port = 465;     //587 is used for Outgoing Mail (SMTP) Server.
      $mail->SMTPSecure = 'ssl';                     
      $mail->SetFrom('pcbs@pcnl.in', 'PCNL ');
      $mail->addAddress($rowcustomer->cust_email);   // Add a recipient
      //$mail->AddReplyTo($rowcompany->stn_email, 'PCNL '.$invnostn);
      $mail->isHTML(true);  // Set email format to HTML
      
      $bodyContent = '<h7><strong>Dear Sir / Madam,</strong></h7>';
      $bodyContent .= "<p>Please find attached pdf of pending outstanding invoice details and it's amount</p>";
      $bodyContent .= '<p>Please clear it immediately.</p><br>';
      $bodyContent .= '<p><strong>Kind Regards,</strong></p><p>PCNL </p>';
      $mail->Subject = "Pending outstanding invoices and it's details";
 
      $mail->addStringAttachment($attachment_file,''.$rowinvoice->cust_code.'-'.$invno.'.pdf');
      
      $mail->Body    = $bodyContent;
      if(!$mail->send()) {
        echo 'Email was not sent.';
        echo 'Mailer error: ' . $mail->ErrorInfo;
      

      } else {
         
        echo 'Email has been sent.';
      }
      
     }
      ?>

   