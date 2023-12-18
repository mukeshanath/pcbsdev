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


      class bookingcounteredit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - bookingcounteredit';
              $this->view->render('masters/bookingcounteredit');
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
                $book_code          = $_POST['book_code'];
                $book_name          = $_POST['book_name'];                
                $book_addr          = $_POST['book_addr'];
                $book_city          = $_POST['book_city'];
                $book_state         = $_POST['book_state'];
                $book_country       = $_POST['book_country'];
                $book_phone         = $_POST['book_phone'];
                $book_fax           = $_POST['book_fax'];
                $book_email         = $_POST['book_email'];
                $c_person           = $_POST['c_person'];
                $book_remarks       = $_POST['book_remarks'];
                $book_active        = $_POST['book_active'];
                $proprietor         = $_POST['proprietor'];
                $book_gstin         = $_POST['book_gstin'];
                $book_tin           = $_POST['book_tin'];
                $book_fsc           = $_POST['book_fsc'];
                $book_deposit       = $_POST['book_deposit'];
                $cnote_charges      = $_POST['cnote_charges'];
                $percent_domscomsn  = $_POST['percent_domscomsn'];
                $percent_intlcomsn  = $_POST['percent_intlcomsn'];
                $pro_commission     = $_POST['pro_commission'];
                $spl_discount       = $_POST['spl_discount'];
                $deposit_date       = $_POST['deposit_date'];
                $username		    = $_POST['user_name'];                 
                
            if(empty($book_phone)){
                array_push($errors, "please enter state");
              }

             
              
            if (count($errors) == 0) 
              {

                 $sql = "UPDATE bcounter set 
                                stncode               = '".$stncode."',
                                stnname               = '".$stnname."',
                                br_code               = '".$br_code."',
                                br_name               = '".$br_name."',
                                book_code             = '".$book_code."',
                                book_name             = '".$book_name."',                      
                                book_addr             = '".$book_addr."',
                                book_city             = '".$book_city."', 
                                book_state            = '".$book_state."', 
                                book_country          = '".$book_country."', 
                                book_phone            = '".$book_phone."',                             
                                book_fax              = '".$book_fax."',
                                book_email            = '".$book_email."',
                                c_person              = '".$c_person."',
                                book_remarks          = '".$book_remarks."',
                                book_active           = '".$book_active."',
                                proprietor            = '".$proprietor."',
                                book_gstin            = '".$book_gstin."',
                                book_tin              = '".$book_tin."',
                                book_fsc              = '".$book_fsc."',
                                book_deposit          = '".$book_deposit."',
                                cnote_charges         = '".$cnote_charges."',
                                percent_domscomsn     = '".$percent_domscomsn."',
                                percent_intlcomsn     = '".$percent_intlcomsn."',
                                pro_commission        = '".$pro_commission."',
                                spl_discount          = '".$spl_discount."',
                                deposit_date          = '".$deposit_date."',
                                user_name              = '".$username."',
                                date_modified         = '".$date_modified."'

                                WHERE bkcid='".$bkcid."'";

              
                 $result  = $db->query($sql);

              }
              else
              {
               echo "data not added";
              }
              header("location: bookingcounterlist?success=2'"); 
             }

      ?>
