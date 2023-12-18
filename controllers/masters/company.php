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


      class company extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - company';
              $this->view->render('masters/company');
          }
      }

     	   $errors = array();

          if(isset($_POST['addcompany']))
          {
            savecompany($db);
          }

          function savecompany($db)  
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_added      = date('Y-m-d H:i:s');

                $stncode          = $_POST['stncode'];
                $stnname          = $_POST['stnname'];
                //$station          = $_POST['station'];
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
                $account_id         = $_POST['account_id'];
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
                $username = $_POST['user_name'];


                $sqlcheck = "SELECT * from station WHERE stncode='$stncode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                    $stn_code = $row->stncode;
                }
                if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($stncode==$stn_code )
                    {
                      header("location: company?unsuccess=1"); 
                      } 
                      else {
                    
              $sql = "INSERT INTO station (stncode,
                                              stnname,
                                              stn_addr,
                                              stn_city,
                                              stn_state,
                                              stn_country,
                                              stn_mobile,
                                              stn_phone,
                                              stn_email,
                                              stn_fax,
                                              stn_gstin,
                                              stn_taxval,
                                              stn_pan,
                                              stn_tin,
                                              stn_active,
                                              stnauto,
                                              baccname,
                                              baccno,
                                              bacctype,
                                              bankname,
                                              bbranch,
                                              ifsc,
                                              micr,
                                              swift,
                                              transit_hub,
                                              weblogin,
                                              webpassword,
                                              webid,
                                              stn_region,
                                              stn_remarks,
                                              user_name,
                                              account_id,
                                              date_added)
                                              VALUES
                                               ('$stncode',
                                               '$stnname',
                                               '$stn_addr',
                                               '$stn_city',
                                               '$stn_state',
                                               '$stn_country',
                                               '$stn_mobile',
                                               '$stn_phone',
                                               '$stn_email',
                                               '$stn_fax',
                                               '$stn_gstin',
                                               '$stn_taxval',
                                               '$stn_pan',
                                               '$stn_tin',
                                               '$stn_active',
                                               '$stnauto',
                                               '$baccname',
                                               '$baccno',
                                               '$bacctype',
                                               '$bankname',
                                               '$bbranch',
                                               '$ifsc',
                                               '$micr',
                                               '$swift',
                                               '$transit_hub',
                                               '$weblogin',
                                               '$webpassword',
                                               '$webid',
                                               '$stn_region',
                                               '$stn_remarks',
                                               '$username',
                                               '$account_id',
                                               '$date_added')";

                          }
                          if($result  = $db->query($sql))
                          {
                          header("location: companylist?success=1"); 
                          }

                          }
                          }
            

             //add another n save

             if(isset($_POST['addancompany'])){
              saveancompany($db);
             }
            
             function saveancompany($db)
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_added      = date('Y-m-d H:i:s');

                $stncode          = $_POST['stncode'];
                $stnname          = $_POST['stnname'];
                $account_id          = $_POST['account_id'];
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
               
                $sqlcheck = "SELECT * from station WHERE stncode='$stncode' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                    $stn_code = $row->stncode;
                }
                if($sqlresult->rowCount() == 0)
                {
                  
                  if ($stncode==$stn_code )
                    {
                      header("location: company?unsuccess=1"); 
                      } 
                      else {
                    
              $sql = "INSERT INTO station (stncode,stnname,stn_addr, stn_city,stn_state,stn_country,stn_mobile,stn_phone,stn_email,stn_fax,
                                            stn_gstin,stn_taxval, stn_pan,stn_tin,stn_active,curinvno,stnauto,baccname,baccno,bacctype,bankname,bbranch,ifsc,micr,swift,transit_hub,weblogin,webpassword,webid,stn_region,stn_remarks,user_name,date_added,account_id)
                                            VALUES ('$stncode','$stnname',$stn_addr','$stn_city','$stn_state','$stn_country','$stn_mobile','$stn_phone','$stn_email','$stn_fax','$stn_gstin','$stn_taxval','$stn_pan','$stn_tin','$stn_active','$curinvno','$stnauto','$baccname','$baccno','$bacctype','$bankname','$bbranch','$ifsc','$micr','$swift','$transit_hub','$weblogin','$webpassword','$webid','$stn_region','$stn_remarks','$username','$date_added','$account_id')";

                          }
                          if($result  = $db->query($sql))
                          {
                          header("location: company?success=2"); 
                  }

            }
        }

      ?>
