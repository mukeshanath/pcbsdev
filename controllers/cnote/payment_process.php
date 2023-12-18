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

      class payment_process extends My_controller
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
      error_reporting(E_ALL);
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

      $description = "CnoteSales Request from $sreq_cust";

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
          "merchant_order_id" => "12312321",
          "Payment For"       => "CnoteSales Request",
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
          echo verifypaymentstatus($_POST['verifypayment_response'],$api,$db);
      }
      function verifypaymentstatus($verifypayment_response,$api,$db)
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

            return json_encode(array("status"=>200,"payment_id"=>$successresponse['razorpay_payment_id']));
        }
        else
        {
            return json_encode(array("status"=>500,"error_description"=>$error));
        }

        }
      }
      ?>