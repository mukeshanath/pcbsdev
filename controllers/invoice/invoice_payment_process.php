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

      class invoice_payment_process extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - inventory';
             // $this->view->render('cnote/inventory');
          }
      }

      $keyId = 'rzp_live_KWZSGr6vLn4Dla';
      $keySecret = 'cq0mqZNgkPd2yLAjPSKXiHZZ';
      $displayCurrency = 'INR';
      
      //These should be commented out in production
      // This is for error reporting
      // Add it to config.php to report any errors
      error_reporting(0);
      ini_set('display_errors', 1);
      require('controllers/razorpay-php/Razorpay.php');

      use Razorpay\Api\Api;
      use Razorpay\Api\Errors\SignatureVerificationError;

      $api = new Api($keyId, $keySecret);


      if(isset($_POST['orderdata'])){

      $orderData = $_POST['orderdata'];
      $sreq_company = $_POST['sreq_company'];
      $sreq_cust = $_POST['sreq_cust'];        
      $razorpayOrder = $api->order->create($orderData);
      
      $razorpayOrderId = $razorpayOrder['id'];
            
      $amount = $orderData['amount'];

      $description = "Invoice Payment from $sreq_cust";

      $customerdata = $db->query("SELECT * FROM customer WHERE comp_code='$sreq_company' AND cust_code='$sreq_cust'")->fetch(PDO::FETCH_OBJ);

      
      $data = [
          "key"               => $keyId,
          "amount"            => $amount,
          "name"              => "PCNL ".$sreq_company,
          "description"       => $description,
          "image"             => "favicon.ico",
          "prefill"           => [
          "name"              => $customerdata->cust_name,
          "email"             => $customerdata->cust_email,
          "contact"           => $customerdata->cust_mobile,
          ],
          "notes"             => [
          "Company"           => $sreq_company,
          "Branch code"       => $customerdata->br_code,
          "Customer Code"     => $customerdata->cust_code,
          "Customer name"     => $customerdata->cust_name,
          "address"           => $customerdata->cust_addr,
          "merchant_order_id" => "adad3234",
          "Payment For"       => "Invoice Settlement",
          ],
          "theme"             => [
          "color"             => "#2f70b0"
          ],
          "order_id"          => $razorpayOrderId,
      ];

      
      $json = json_encode($data);
      
      echo $json;
      }

      if(isset($_POST['verifypayment_response'])){
          echo verifypaymentstatus($_POST['verifypayment_response'],$db,$api);
      }
      function verifypaymentstatus($verifypayment_response,$db,$api)
      {

        $success = true;

        $successresponse = $verifypayment_response;
        if(!empty($successresponse['razorpay_payment_id'])){

        try
        {
            $attributes = array(
                'razorpay_order_id' => $successresponse['razorpay_order_id'],
                'razorpay_payment_id' => $successresponse['razorpay_payment_id'],
                'razorpay_signature' => $successresponse['razorpay_signature']
            );
            $api->utility->verifyPaymentSignature($attributes);
        }
        catch(SignatureVerificationError $e)
        {
            $success = false;
            $error = 'Razorpay Error : ' . $e->getMessage();
        }

        //get account id
        $current_station = $successresponse['comp_code']; 
        $account = $db->query("SELECT account_id FROM station WHERE stncode='$current_station'")->fetch(PDO::FETCH_OBJ);

        if ($success === true)
        {
            $api->payment->fetch($successresponse['razorpay_payment_id'])->transfer(array(
                'transfers' => [
                    [
                    'account' => $account->account_id,
                    'amount' => $successresponse['amount'],
                    'currency' => 'INR'
                    ]
                 ]
                )
              );
            
            //make collection entry
            $paymentamt = (float)$successresponse['amount']/100;
            $cust_code = $successresponse['cust_code'];
            $comp_code = $successresponse['comp_code'];
            $invno = $successresponse['invno'];
            $remarks = $successresponse['collnremarks'];
            $paymentresid = $successresponse['razorpay_payment_id'];
            $user_name = $successresponse['user_name'];

            $db->query("UPDATE invoice SET rcvdamt=rcvdamt+$paymentamt,lastcoldate=CAST(getdate() as date) WHERE comp_code='$comp_code' AND invno='$invno' AND cust_code='$cust_code'");
            $invdata = $db->query("SELECT * FROM invoice WHERE comp_code='$comp_code' AND invno='$invno' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);

            $balanceamt = $invdata->netamt - $invdata->rcvdamt;
            if($balanceamt==0){
                $invstatus = 'Collected';
                $collstatus = 'Collected';
            }else{
                $invstatus = 'Pending';
                $collstatus = 'On-Hold';
            }
            //collection mass number
            $prevcolmasno=$db->query("SELECT TOP 1 col_mas_rcptno FROM collection_master WHERE comp_code='$comp_code' ORDER BY col_mas_id DESC");
            if($prevcolmasno->rowCount()== 0)
                {
                $colomasno = $comp_code.str_pad('1', 7, "0", STR_PAD_LEFT);
                }
            else{
                $prevcolmasrow=$prevcolmasno->fetch(PDO::FETCH_ASSOC);
                $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$prevcolmasrow['col_mas_rcptno']);
                $arr[0];//PO
                $arr[1]+1;//numbers
                $colomasno = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
                }
            //collection mass number
            //collection number
            $prevcolno=$db->query("SELECT TOP 1 colno FROM collection WHERE comp_code='$comp_code' ORDER BY col_id DESC");
            if($prevcolno->rowCount()== 0){
                $colono = 10001;
                }
            else{
                $prevcolrow=$prevcolno->fetch(PDO::FETCH_ASSOC);
                $colono = $prevcolrow['colno']+1;
                }
            //collection number
            $getbrname = $db->query("SELECT br_name FROM branch WHERE stncode='$comp_code' AND br_code='$invdata->br_code'")->fetch(PDO::FETCH_OBJ);
            $getcustname = $db->query("SELECT cust_name FROM customer WHERE comp_code='$comp_code' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);

            $insertcollection = "INSERT INTO collection (remarks,comp_code,br_code,br_name,cust_code,cust_name,colno,invno,invamt,balamt,chqddno,colamt,tds,reduction,narration,cmode,rcptno,col_mas_rcptno,coldate,edate,invdate,status,user_name,date_added,netcollamt)
            VALUES ('$remarks','$comp_code','$invdata->br_code','$getbrname->br_name','$cust_code','$getcustname->cust_name','$colono','$invno','$invdata->netamt','$balanceamt','$paymentresid','$paymentamt','0','0','0','Online','$paymentresid','$colomasno',GETDATE(),GETDATE(),'$invdata->invdate','$collstatus','$user_name',GETDATE(),'$paymentamt')";
            $db->query($insertcollection);

            $db->query("UPDATE invoice SET status='$invstatus' WHERE invno='$invno' AND comp_code='$comp_code' AND cust_code='$cust_code'");
            
            $insertcollectionmaster = "INSERT INTO collection_master (col_mas_rcptno,cust_code,rcptno,coldate,invno,chqddno,postamt,comp_code,status,user_name,date_added)
            VALUES ('$colomasno','$cust_code','$    ',GETDATE(),'$invno','$paymentresid','$paymentamt','$comp_code','$collstatus','$user_name',GETDATE())";
            $db->query($insertcollectionmaster);

            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','Collection','Created','Collection Master No $colomasno has been Created','$user_name',getdate())");   

            //make collection entry

            return json_encode(array("status"=>200,"payment_id"=>$successresponse['razorpay_payment_id']));
        }
        else
        {
            return json_encode(array("status"=>500,"error_description"=>$error));
        }

        }
      }

      //verify bulk payment response
      if(isset($_POST['verifybulkpayment_response'])){
          echo verifybulkpaymentstatus($_POST['verifybulkpayment_response'],$_POST['selectedInvoices2'],$db,$api);
      }
      function verifybulkpaymentstatus($verifypayment_response,$selectedInvoices,$db,$api)
      {
        $success = true;

        $successresponse = $verifypayment_response;
        if(!empty($successresponse['razorpay_payment_id'])){

        try
        {
            $attributes = array(
                'razorpay_order_id' => $successresponse['razorpay_order_id'],
                'razorpay_payment_id' => $successresponse['razorpay_payment_id'],
                'razorpay_signature' => $successresponse['razorpay_signature']
            );
            $api->utility->verifyPaymentSignature($attributes);
        }
        catch(SignatureVerificationError $e)
        {
            $success = false;
            $error = 'Razorpay Error : ' . $e->getMessage();
        }

        //get account id
        $current_station = $successresponse['comp_code'];
        $account = $db->query("SELECT account_id FROM station WHERE stncode='$current_station'")->fetch(PDO::FETCH_OBJ);

        if ($success === true)
        {
            $api->payment->fetch($successresponse['razorpay_payment_id'])->transfer(array(
                'transfers' => [
                    [
                    'account' => $account->account_id,
                    'amount' => $successresponse['amount'],
                    'currency' => 'INR'
                  ]
                 ]
                )
              );

              $actualamt = (float)$successresponse['amount']/100;
              $paymentamt = (float)$successresponse['amount']/100;

            //make collection entry
            foreach($selectedInvoices as $selectedInvoice){
            $cust_code = $successresponse['cust_code'];
            $comp_code = $successresponse['comp_code'];
            $invno = $selectedInvoice;
            $remarks = $successresponse['collnremarks'];
            $paymentresid = $successresponse['razorpay_payment_id'];
            $user_name = $successresponse['user_name'];

            //check balance amount
            if($actualamt>0)
            {

            $getinvbal = $db->query("SELECT (netamt-rcvdamt) as balanceamt FROM invoice WHERE  comp_code='$comp_code' AND invno='$invno' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);
            if($actualamt<=$getinvbal->balanceamt)
            {
                 $paymentamt = $actualamt;
            }
            else{
                $paymentamt = $getinvbal->balanceamt;
            }

            $db->query("UPDATE invoice SET rcvdamt=rcvdamt+$paymentamt,lastcoldate=CAST(getdate() as date) WHERE comp_code='$comp_code' AND invno='$invno' AND cust_code='$cust_code'");
            $invdata = $db->query("SELECT * FROM invoice WHERE comp_code='$comp_code' AND invno='$invno' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);

            $balanceamt = $invdata->netamt - $invdata->rcvdamt;
            if($balanceamt==0){
                $invstatus = 'Collected';
                $collstatus = 'Collected';
            }else{
                $invstatus = 'Pending';
                $collstatus = 'On-Hold';
            }
            //collection mass number
            $prevcolmasno=$db->query("SELECT TOP 1 col_mas_rcptno FROM collection_master WHERE comp_code='$comp_code' ORDER BY col_mas_id DESC");
            if($prevcolmasno->rowCount()== 0)
                {
                $colomasno = $comp_code.str_pad('1', 7, "0", STR_PAD_LEFT);
                }
            else{
                $prevcolmasrow=$prevcolmasno->fetch(PDO::FETCH_ASSOC);
                $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$prevcolmasrow['col_mas_rcptno']);
                $arr[0];//PO
                $arr[1]+1;//numbers
                $colomasno = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
                }
            //collection mass number
            //collection number
            $prevcolno=$db->query("SELECT TOP 1 colno FROM collection WHERE comp_code='$comp_code' ORDER BY col_id DESC");
            if($prevcolno->rowCount()== 0){
                $colono = 10001;
                }
            else{
                $prevcolrow=$prevcolno->fetch(PDO::FETCH_ASSOC);
                $colono = $prevcolrow['colno']+1;
                }
            //collection number

            $getbrname = $db->query("SELECT br_name FROM branch WHERE stncode='$comp_code' AND br_code='$invdata->br_code'")->fetch(PDO::FETCH_OBJ);
            $getcustname = $db->query("SELECT cust_name FROM customer WHERE comp_code='$comp_code' AND cust_code='$cust_code'")->fetch(PDO::FETCH_OBJ);

            $insertcollection = "INSERT INTO collection (remarks,comp_code,br_code,br_name,cust_code,cust_name,colno,invno,invamt,balamt,chqddno,colamt,tds,reduction,narration,cmode,rcptno,col_mas_rcptno,coldate,edate,invdate,status,user_name,date_added,netcollamt)
            VALUES ('$remarks','$comp_code','$invdata->br_code','$getbrname->br_name','$cust_code','$getcustname->cust_name','$colono','$invno','$invdata->netamt','$balanceamt','$paymentresid','$paymentamt','0','0','0','Online','$paymentresid','$colomasno',GETDATE(),GETDATE(),'$invdata->invdate','$collstatus','$user_name',GETDATE(),'$paymentamt')";
            $db->query($insertcollection);

            $db->query("UPDATE invoice SET status='$invstatus' WHERE invno='$invno' AND comp_code='$comp_code' AND cust_code='$cust_code'");
            
            $insertcollectionmaster = "INSERT INTO collection_master (col_mas_rcptno,cust_code,rcptno,coldate,invno,chqddno,postamt,comp_code,status,user_name,date_added)
            VALUES ('$colomasno','$cust_code','$paymentresid',GETDATE(),'$invno','$paymentresid','$paymentamt','$comp_code','$collstatus','$user_name',GETDATE())";
            $db->query($insertcollectionmaster);

            $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added) VALUES  ('Accounts','Collection','Created','Collection Master No $colomasno has been Created','$user_name',getdate())");   


            $actualamt = $actualamt-$paymentamt;
            }
        }
            //make collection entry
            return json_encode(array("status"=>200,"payment_id"=>$successresponse['razorpay_payment_id']));

        }
        else
        {
            return json_encode(array("status"=>500,"error_description"=>$error));
        }

        }
      }
      ?>