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


      class companyedit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - companyedit';
              $this->view->render('masters/companyedit');

              
          }
      }

     	   $errors = array();

          if(isset($_POST['updatecompany'])){
            stnupdate($db);
          }
            
          function stnupdate($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_modified    = date('Y-m-d H:i:s');

                $stnid            = $_POST['stnid'];
                $stncode          = $_POST['stncode'];
                $stnname          = $_POST['stnname'];
                $account_id       = $_POST['account_id'];
             // $stn_addrtype     = $_POST['stn_addrtype'];
                $stn_addr         = $_POST['stn_addr'];
                $stn_city         = $_POST['stn_city'];
                $stn_state        = $_POST['stn_state'];
                $stn_country      = $_POST['stn_country'];
                $stn_mobile       = $_POST['stn_mobile'];
                $stn_phone        = $_POST['stn_phone'];
                $stn_email        = $_POST['stn_email'];
                $stn_fax          = $_POST['stn_fax'];
                $stn_gstin        = $_POST['stn_gstin'];
                $stn_taxval       = $_POST['stn_taxval'];
                $stn_pan          = $_POST['stn_pan'];
                $stn_tin          = $_POST['stn_tin'];
                $stn_active       = $_POST['stn_active'];
                $curinvno         = $_POST['curinvno'];
                $stnauto          = $_POST['stnauto'];
                $baccname         = $_POST['baccname'];
                $baccno           = $_POST['baccno'];
                $bacctype         = $_POST['bacctype'];
                $bankname         = $_POST['bankname'];
                $bbranch          = $_POST['bbranch'];
                $ifsc             = $_POST['ifsc'];
                $micr             = $_POST['micr'];
                $swift            = $_POST['swift'];
                $transit_hub      = $_POST['transit_hub'];
                $weblogin         = $_POST['weblogin'];
                $webpassword      = $_POST['webpassword'];
                $webid            = $_POST['webid'];
                $stn_region       = $_POST['stn_region'];
                $stn_remarks      = $_POST['stn_remarks'];
                $username         = $_POST['user_name'];
				        $gta              = $_POST['gta'];



                if(empty($stn_phone)){
                array_push($errors, "please enter pincode");
              }              

                
            if (count($errors) == 0) 
              {
               $sql = "UPDATE station set stncode = '".$stncode."',
                                        stnname = '".$stnname."',                                 
                                        stn_addr = '".$stn_addr."',
                                        stn_city = '".$stn_city."',
                                        stn_state = '".$stn_state."',
                                        stn_country = '".$stn_country."',
                                        stn_mobile = '".$stn_mobile."',
                                        stn_phone = '".$stn_phone."',
                                        stn_email = '".$stn_email."',
                                        stn_fax = '".$stn_fax."',
                                        stn_gstin = '".$stn_gstin."',
                                        stn_taxval = '".$stn_taxval."',
                                        stn_pan = '".$stn_pan."',
                                        stn_tin = '".$stn_tin."',
                                        stn_active = '".$stn_active."',
                                        curinvno = '".$curinvno."',
                                        stnauto = '".$stnauto."',
                                        baccno = '".$baccno."',
                                        baccname = '".$baccname."',
                                        bacctype = '".$bacctype."',
                                        bankname = '".$bankname."',
                                        bbranch = '".$bbranch."',
                                        ifsc = '".$ifsc."',
                                        micr = '".$micr."',
                                        swift = '".$swift."',
                                        transit_hub = '".$transit_hub."',
                                        weblogin = '".$weblogin."',
                                        webpassword = '".$webpassword."',
                                        webid = '".$webid."',
                                        stn_region = '".$stn_region."',
                                        stn_remarks = '".$stn_remarks."',                                  
                                        account_id = '".$account_id."',                                  
                                        user_name = '".$username."',
										                    date_modified = '".$date_modified."',
                                        gta = '".$gta."'
                                        
                                        WHERE stnid='".$stnid."' ";

              $result  = $db->query($sql);
              $db->query("INSERT INTO audit_log (object,module,change,description,user_name,date_added)
              VALUES('$stncode','Company','Update','Company $stncode has been Updated','$username',getdate())");

              }
              else
              {
               echo "data not added";
              }
              header("location: companylist?success=3"); 
             }
           

            

      ?>
