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

      class group extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - group';
              $this->view->render('masters/group');
          }
      }

         
          if(isset($_POST['addgroup']))
          {
            savegroup($db);
          }


          function savegroup($db)  
            {
                date_default_timezone_set('Asia/Kolkata');
                $stncode   = $_POST['stncode'];
                $grp_type  = $_POST['grp_type'];
                $cate_code = $_POST['cate_code'];
                $cate_name = $_POST['cate_name'];                
                $range_starts = $_POST['range_starts'];
                $range_ends   = $_POST['range_ends'];
                $invoice_start = $_POST['invoice_start'];
                $username   = $_POST['user_name'];
                $stock_range = $_POST['stock_range'];
                $date_added = date('Y-m-d H:i:s');
                $v_flag = (isset($_POST['virtual_flag'])?(int)$_POST['virtual_flag']:0);

                $sqlcheck = "SELECT * from groups WHERE stncode='$stncode' AND cate_code='$cate_code' AND grp_type='$grp_type'";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $stn_code = $row->stncode;
                    $catecode = $row->cate_code;
		    $grptype = $row->grp_type;
                }

              
                if($sqlresult->rowCount() >= 0)
                {
                  
                  if ($stncode==$stn_code AND $cate_code==$catecode AND $grp_type==$grptype )
                    {
                      header("location: group?unsuccess=4"); 
                      } 
                      else {
              $sql = "INSERT INTO groups (v_flag,stncode,grp_type,cate_code,cate_name,range_starts,range_ends,invoice_start,user_name,date_added,stock_alert)
                      VALUES ('$v_flag','$stncode','$grp_type','$cate_code','$cate_name','$range_starts','$range_ends', '$invoice_start','$username','$date_added','$stock_range')";
               }
               if($result  = $db->query($sql))
               {
               header("location: grouplist?success=1"); 
               }
               
             }
          }


          if(isset($_POST['addangroup']))
          {
            saveangroup($db);
          }


          function saveangroup($db)  
            {
                date_default_timezone_set('Asia/Kolkata');
                $stncode   = $_POST['stncode'];
                $grp_type  = $_POST['grp_type'];
                $cate_code = $_POST['cate_code'];
                $cate_name = $_POST['cate_name'];                
                $range_starts = $_POST['range_starts'];
                $range_ends   = $_POST['range_ends'];
                $invoice_start = $_POST['invoice_start'];
                $username   = $_POST['user_name'];
                $stock_range = $_POST['stock_range'];
                $date_added = date('Y-m-d H:i:s');
                $v_flag = (isset($_POST['virtual_flag'])?(int)$_POST['virtual_flag']:0);

                $sqlcheck = "SELECT * from groups WHERE stncode='$stncode' AND cate_code='$cate_code' ";
                $sqlresult  = $db->query($sqlcheck);
        
                while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
                {
                   
                    $stn_code = $row->stncode;
                    $catecode = $row->cate_code;
                }

              
                if($sqlresult->rowCount() == 0)
                {
                  
                  if ($stncode==$stn_code AND $cate_code==$catecode )
                    {
                      header("location: group?success=4"); 
                      } 
                      else { 
              $sql = "INSERT INTO groups (v_flag,stncode,grp_type,cate_code,cate_name,range_starts,range_ends,invoice_start,user_name,date_added,stock_alert)
                      VALUES ('$v_flag','$stncode','$grp_type','$cate_code','$cate_name','$range_starts','$range_ends', '$invoice_start','$username','$date_added','$stock_range')";
               }
               if($result  = $db->query($sql))
               {
               header("location: group?success=1"); 
               }
               
             }
          }
             
?>
