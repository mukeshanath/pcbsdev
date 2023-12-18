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

      class groupedit extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - groupedit';
              $this->view->render('masters/groupedit');
          }
      }

          $errors = array();

          
          if(isset($_POST['updategroup']))
          {
            altergroup($db);
          }

          function altergroup($db)  
            {
                date_default_timezone_set('Asia/Kolkata');
                $gpid      = $_POST['gpid'];
                $grp_type  = $_POST['grp_type'];
                $cate_code = $_POST['cate_code'];
                $cate_name = $_POST['cate_name'];
                $range_starts = $_POST['range_starts'];
                $range_ends   = $_POST['range_ends'];
                $invoice_start = $_POST['invoice_start'];
                $username  = $_POST['user_name'];
                $stock_range  = $_POST['stock_range'];
                $v_flag = (isset($_POST['virtual_flag'])?(int)$_POST['virtual_flag']:0);
                $date_modified = date('Y-m-d H:i:s');
                
            if(empty($cate_code)){
                array_push($errors, "please enter state");
              }

              if(empty($cate_name)){
                array_push($errors, "please enter state name");
              }

              
            if (count($errors) == 0) 
              {
              $sql = "UPDATE groups set grp_type = '".$grp_type."',
                                        v_flag = '".$v_flag."',
                                        cate_code = '".$cate_code."',
                                        cate_name = '".$cate_name."',
                                        range_starts = '".$range_starts."',
                                        range_ends = '".$range_ends."',  
                                        invoice_start = '".$invoice_start."', 
                                        user_name = '".$username."',                                   
                                        date_modified = '".$date_modified."',
                                        stock_alert = '".$stock_range."'
                                        WHERE gpid='".$gpid."' ";
              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: grouplist?success=2"); 
             }
?>
