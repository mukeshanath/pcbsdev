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


      class collectioncenteredit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - collectioncenteredit';
              $this->view->render('masters/collectioncenteredit');
          }
      }

         $errors = array();

     	   if(isset($_POST['updatecounter'])){
                altercounter($db);
            }
            
            function altercounter($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_modified = date('Y-m-d H:i:s');
                
                $bkcid              = $_POST['bkcid'];
                $stncode            = $_POST['stncode'];
                $stnname            = $_POST['stnname'];
                $br_code            = $_POST['br_code'];
                $br_name            = $_POST['br_name'];
                $cc_code          = $_POST['cc_code'];
                $cc_name          = $_POST['cc_name'];                
                $cc_addr          = $_POST['cc_addr'];
                $cc_city          = $_POST['cc_city'];
                $cc_state         = $_POST['cc_state'];
                $cc_country       = $_POST['cc_country'];
                $cc_phone         = $_POST['cc_phone'];
                $cc_fax           = $_POST['cc_fax'];
                $cc_email         = $_POST['cc_email'];
                $c_person           = $_POST['c_person'];
                $cc_remarks       = $_POST['cc_remarks'];
                $cc_active        = $_POST['cc_active'];
                $proprietor         = $_POST['proprietor'];
                $cc_gstin         = $_POST['cc_gstin'];
                $cc_tin           = $_POST['cc_tin'];
                $cc_fsc           = $_POST['cc_fsc'];
                $cc_deposit       = $_POST['cc_deposit'];
                $cnote_charges      = $_POST['cnote_charges'];
                $percent_domscomsn  = $_POST['percent_domscomsn'];
                $percent_intlcomsn  = $_POST['percent_intlcomsn'];
                $pro_commission     = $_POST['pro_commission'];
                $spl_discount       = $_POST['spl_discount'];
                $deposit_date       = $_POST['deposit_date'];
                $username		    = $_POST['user_name'];                 
                $bank_acc_name        = $_POST['baccname'];
                $bank_accno           = $_POST['baccno'];
                $bankname             = $_POST['bankname'];
                $account_type         = $_POST['bacctype'];
                $bank_branch          = $_POST['bbranch'];
                $ifsc                 = $_POST['ifsc'];
                $micr                 = $_POST['micr'];
                $swift                = $_POST['pancard'];
                $account_id           = $_POST['account_id'];
                $checkue           = $_POST['checkue'];
            if(empty($cc_phone)){
                array_push($errors, "please enter state");
              }

             
              
            if (count($errors) == 0) 
              {

                 $sql = "UPDATE collectioncenter set 
                                stncode               = '".$stncode."',
                                stnname               = '".$stnname."',
                                br_code               = '".$br_code."',
                                br_name               = '".$br_name."',
                                cc_code             = '".$cc_code."',
                                cc_name             = '".$cc_name."',                      
                                cc_addr             = '".$cc_addr."',
                                cc_city             = '".$cc_city."', 
                                cc_state            = '".$cc_state."', 
                                cc_country          = '".$cc_country."', 
                                cc_phone            = '".$cc_phone."',                             
                                cc_fax              = '".$cc_fax."',
                                cc_email            = '".$cc_email."',
                                c_person              = '".$c_person."',
                                cc_remarks          = '".$cc_remarks."',
                                cc_active           = '".$cc_active."',
                                proprietor            = '".$proprietor."',
                                cc_gstin            = '".$cc_gstin."',
                                cc_tin              = '".$cc_tin."',
                                cc_fsc              = '".$cc_fsc."',
                                cc_deposit          = '".$cc_deposit."',
                                cnote_charges         = '".$cnote_charges."',
                                percent_domscomsn     = '".$percent_domscomsn."',
                                percent_intlcomsn     = '".$percent_intlcomsn."',
                                pro_commission        = '".$pro_commission."',
                                spl_discount          = '".$spl_discount."',
                                deposit_date          = '".$deposit_date."',
                                user_name              = '".$username."',
								date_modified         = '".$date_modified."',

                                bank_accname          = '".$bank_acc_name."',
                                bank_acc_no          = '".$bank_accno."',
                                bank_name          = '".$bankname."',
                                bank_branch          = '".$bank_branch."',
                                account_type          = '".$account_type."',
                                ifsc          = '".$ifsc."',
                                micr          = '".$micr."',
                                pancardno          = '".$swift."',
                                acc_id          = '".$account_id."',
                                checkue          = '".$checkue."'

                                WHERE bkcid='".$bkcid."'";

              
                 $result  = $db->query($sql);

              }
              else
              {
               echo "data not added";
              }
              header("location: collectioncenterlist?success=2"); 
             }

      ?>
