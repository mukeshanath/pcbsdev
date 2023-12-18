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


      class settingscontroller extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - settingscontroller';
              //$this->view->render('settings/regionlist');
          }
      }


       
       //delete masters data
        if(isset($_POST['deletetb'])){
          $deletetb = $_POST['deletetb'];
          $deleterow = $_POST['deleterow'];
          $deletecol = $_POST['deletecol'];
          $delete = $db->query("DELETE FROM $deletetb WHERE $deletecol='$deleterow'");
        }

                //load data for pagination in destination table
		if(isset($_POST['dtbquery'])){
      function delete($db,$user){
        $module = $_POST['datatable_module'];
        $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
        $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
        $user_group = $rowusergrp->group_name;
      
        $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
            if($verify_user->rowCount()=='0'){
                return 'swal("You Dont Have Permission to Delete this Record!")';
            }
            else{
                return "deleterecord(this.id)";
            }
        }
        $sessionuser = $_POST['sessionuser'];
        $dtbquery = $_POST['dtbquery'];
        $dtbpageno = $_POST['dtbpageno'];
        $dtbcolumns = explode(",",$_POST['dtbcolumns']);
        $cno = 'cno';
        $t10query = $db->query($dtbquery." OFFSET ".$dtbpageno." ROWS  FETCH NEXT 10 ROWS ONLY");
        while($t10row=$t10query->fetch(PDO::FETCH_OBJ)){
          echo "<tr><td class='center first-child'>
          <label>
            <input type='checkbox' name='chkIds[]' value='1' class='ace' />
              <span class='lbl'></span>
          </label>
          </td>";
          foreach($dtbcolumns as $dtbcolumn){
            echo "<td>".$t10row->$dtbcolumn."</td>";
          }
          if($_POST['datatable_page']=='pincodelist'){
            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
            <a class='btn btn-success btn-minier' href='pincodeedit?pid=".$t10row->pid."'>
             <i class='ace-icon fa fa-pencil bigger-130'></i>
            </a>

            <a  class='btn btn-danger btn-minier bootbox-confirm' id='pincode,$t10row->pid,pid' onclick='".delete($db,$user)."'>
               <i class='ace-icon fa fa-trash-o bigger-130'></i>
            </a>
          </div>
            </td></tr>";
          }
        }
      }