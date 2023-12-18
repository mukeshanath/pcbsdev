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

      class branchedit extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - branchedit';
              $this->view->render('masters/branchedit');
          }
      }

      $errors = array();

          if(isset($_POST['updatebranch']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_modified    = date('Y-m-d H:i:s');

                $brid             = $_POST['brid'];
                $br_code          = $_POST['br_code'];
                $br_name          = $_POST['br_name'];
                $stncode          = $_POST['stncode'];
                $stnname          = $_POST['stnname'];
                $br_head          = $_POST['br_head'];
                $br_headcode      = $_POST['br_headcode'];
                $br_address       = $_POST['br_address'];
                $br_city   		  =	$_POST['br_city'];
                $br_state  		  =	$_POST['br_state'];
                $br_country   	  =	$_POST['br_country'];
                $br_phone         = $_POST['br_phone'];
                $br_mobile        = $_POST['br_mobile'];
                $br_email         = $_POST['br_email'];
                $br_fax           = $_POST['br_fax'];
                $br_tax           = $_POST['br_tax'];
                $br_remarks       = $_POST['br_remarks'];
                $br_active        = $_POST['br_active'];
                $manifest         = $_POST['manifest'];
                $exe_code         =	$_POST['exe_code'];
                $exe_name         =	$_POST['exe_name'];
                $role             =	$_POST['role'];
                $cate_code        = $_POST['cate_code'];
                $brcate_code      = $_POST['brcate_code'];
                $transit_hub      = $_POST['transit_hub'];
                $curinvno         = $_POST['curinvno'];
                $curcolno         = $_POST['curcolno'];
                $rate             = $_POST['rate'];
                $podall           = $_POST['podall'];
                $br_ncode         = $_POST['br_ncode'];
                $origin           = $_POST['origin'];
                $bkgstaff         = $_POST['bkgstaff'];
                $proprietor       = $_POST['proprietor'];
                $commission       = $_POST['commission'];
                $pro_commission   = $_POST['pro_commission'];
                $branchtype       = $_POST['branchtype'];
                $intl_rate        = $_POST['intl_rate'];
                $am               = $_POST['am'];
                $username         = $_POST['user_name'];
                $virtual_edit         = $_POST['virtual_edit'];
                $cc_enable         = $_POST['cc_enable'];
                

               $sql = "UPDATE branch set br_code = '".$br_code."',
                                        br_name = '".$br_name."',
                                        stncode = '".$stncode."',
                                        stnname = '".$stnname."',
                                        br_head = '".$br_head."',
                                        br_headcode = '".$br_headcode."',                             
                                        br_address = '".$br_address."',
                                        br_city = '".$br_city."',
                                        br_state = '".$br_state."',
                                        br_country = '".$br_country."',
                                        br_phone = '".$br_phone."',
                                        br_mobile = '".$br_mobile."',
                                        br_email = '".$br_email."',
                                        br_fax = '".$br_fax."',
                                        br_tax = '".$br_tax."',
                                        br_remarks = '".$br_remarks."',
                                        br_active = '".$br_active."',
                                        manifest = '".$manifest."',
                                        exe_code = '".$exe_code."',
                                        exe_name = '".$exe_name."',
                                        role = '".$role."',
                                        cate_code = '".$cate_code."',
                                        brcate_code = '".$brcate_code."',
                                        transit_hub = '".$transit_hub."',
                                        curinvno = '".$curinvno."',
                                        curcolno = '".$curcolno."',
                                        rate = '".$rate."',
                                        podall = '".$podall."',
                                        br_ncode = '".$br_ncode."',
                                        origin = '".$origin."',
                                        bkgstaff = '".$bkgstaff."',
                                        proprietor = '".$proprietor."',
                                        commission = '".$commission."',
                                        pro_commission = '".$pro_commission."',
                                        branchtype = '".$branchtype."',
                                        intl_rate = '".$intl_rate."',
                                        am = '".$am."',
                                        user_name = '".$username."',
                                        date_modified = '".$date_modified."',
                                        virtual_edit = '".$virtual_edit."',
                                        cc_enable = '".$cc_enable."'
                                        WHERE brid='".$brid."' ";

              if($result  = $db->query($sql))
             {
              header("location: branchlist?success=3"); 
             }
             }


?>
