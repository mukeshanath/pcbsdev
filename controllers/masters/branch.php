<?php 
/* File name   : tax.php
 Begin       : 2020-03-04
 Description : class branch file saves master - branch  in appliaction
               Default Header and Footer

 Author: zabiullah

 (c) Copyright:
              codim solutions
============================================================+*/

/**
 * render the branch in to class 
 * get database string to establish connection from sql server
 * Capture the branch data and save in to database
 * @author zabi  
 * @since 2020-03-04
 */

/* Include the main barnch master (search for installation path). */

defined('__ROOT__') OR exit('No direct script access allowed');

      include 'db.php';
      
      class branch extends My_controller
      {
          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - branch';
              $this->view->render('masters/branch'); 
          }
      }
      
      if(isset($_POST['addbranch']))
      {
        savebranch($db);
      }
      
      function savebranch($db)  
      {
        date_default_timezone_set('Asia/Kolkata');
        $date_added      	= date('Y-m-d H:i:s');
        $br_code 				  =	strtoupper($_POST['br_code']);
        $br_name					=	$_POST['br_name'];
        $stncode          = $_POST['stncode'];
        $stnname          = $_POST['stnname'];
        $br_head          = $_POST['br_head'];
        $br_headcode      = $_POST['br_headcode'];
        $br_address				=	$_POST['br_address'];
        $br_city   				=	$_POST['br_city'];
        $br_state  				=	$_POST['br_state'];
        $br_country   		=	$_POST['br_country'];
        $br_phone					=	$_POST['br_phone'];
        $br_mobile				=	$_POST['br_mobile'];
        $br_email					=	$_POST['br_email'];
        $br_fax						=	$_POST['br_fax'];
        $br_tax						=	$_POST['br_tax'];
        $br_remarks				=	$_POST['br_remarks'];
        $br_active				=	$_POST['br_active'];
        $manifest					=	$_POST['manifest'];
        $exe_code         =	$_POST['exe_code'];
        $exe_name         =	$_POST['exe_name'];
        $role             =	$_POST['role'];
        $cate_code				=	$_POST['cate_code'];
        $brcate_code			=	$_POST['brcate_code'];
        $transit_hub	    =	$_POST['transit_hub'];
        $curinvno					=	$_POST['curinvno'];
        $curcolno					=	$_POST['curcolno'];
        $rate 						=	$_POST['rate'];
        $podall						=	$_POST['podall'];
        $br_ncode					=	$_POST['br_ncode'];
        $origin						=	$_POST['origin'];
        $bkgstaff					=	$_POST['bkgstaff'];
        $proprietor				=	$_POST['proprietor'];
        $commission 			=	$_POST['commission'];
        $pro_commission 	=	$_POST['pro_commission'];
        $branchtype 			=	$_POST['branchtype'];
        $intl_rate				=	$_POST['intl_rate'];
        $am							  =	$_POST['am'];
        $username				  =	$_POST['user_name'];
        $virtual_edit    = $_POST['virtual_edit'];
        $cc_enable         = $_POST['cc_enable'];


        $sqlcheck = "SELECT * from branch WHERE stncode='$stncode' AND br_code='$br_code' ";
        $sqlresult  = $db->query($sqlcheck);
        
        while ($row = $sqlresult->fetch(PDO::FETCH_OBJ))
        {
          $brcode = $row->br_code;
          $stn_code = $row->stncode;
        }

        if($sqlresult->rowCount() >= 0)
        {
          if ($stncode==$stn_code AND $br_code==$brcode)
          {
            header("location: branch?unsuccess=4"); 
          } 
         else 
         {
           $sql = "INSERT INTO branch (br_code,
                    br_name,
                    br_address,
                    br_city,
                    br_state,
                    br_country,
                    br_phone,
                    br_mobile,
                    br_email,
                    br_fax,
                    br_headcode,
                    br_head,
                    br_tax,
                    br_remarks,
                    br_active,
                    manifest,
                    exe_code,
                    exe_name,
                    role,
                    cate_code,
                    brcate_code,
                    stncode,
                    stnname,
                    transit_hub,
                    curinvno,
                    curcolno,
                    rate,
                    podall,
                    br_ncode,
                    origin,
                    bkgstaff,
                    proprietor,
                    commission,
                    pro_commission,
                    branchtype,
                    intl_rate,
                    am,
                    user_name,  
                    virtual_edit,
                    cc_enable,          
                    date_added) VALUES ('$br_code',
                                '$br_name',
                                '$br_address',
                                '$br_city',
                                '$br_state',
                                '$br_country',
                                '$br_phone',
                                '$br_mobile',
                                '$br_email',                                             	
                                '$br_fax',
                                '$br_headcode,',
                                '$br_head',                                               
                                '$br_tax',                                       					  
                                '$br_remarks',                                   					  
                                '$br_active',                                    					  
                                '$manifest',    
                                '$exe_code',
                                '$exe_name',
                                '$role',                                 					  
                                '$cate_code',                                 					  
                                '$brcate_code',                                 					  
                                '$stncode',
                                '$stnname',                                      					  
                                '$transit_hub',
                                '$curinvno',
                                '$curcolno',
                                '$rate',
                                '$podall',
                                '$br_ncode',
                                '$origin',
                                '$bkgstaff',
                                '$proprietor',
                                '$commission',
                                '$pro_commission',
                                '$branchtype',
                                '$intl_rate',
                                '$am',
                                '$username',   
                                '$virtual_edit', 
                                '$cc_enable',                                       			
                                '$date_added')";
                  }
                  if($result  = $db->query($sql))
                  {
                  header("location: branchlist?success=1"); 
                  }
                  
                }
             }

             // adding another branch

             if(isset($_POST['addanbranch']))
            
            {
                date_default_timezone_set('Asia/Kolkata');
                $date_added       = date('Y-m-d H:i:s');

                $br_code 				  =	strtoupper($_POST['br_code']);
                $br_name          = $_POST['br_name'];
                $stncode          = $_POST['stncode'];
                $stnname          = $_POST['stnname'];
                $br_head          = $_POST['br_head'];
                $br_headcode      = $_POST['br_headcode'];
                $br_address       = $_POST['br_address'];
                $br_city   				=	$_POST['br_city'];
                $br_state  				=	$_POST['br_state'];
                $br_country   		=	$_POST['br_country'];
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
                $username				  =	$_POST['user_name'];
                $virtual_edit    = $_POST['virtual_edit'];
                $cc_enable       = $_POST['cc_enable'];
                if(empty($br_name)){
                array_push($errors, "please enter pincode");
                }              
                
              if (count($errors) == 0) 
              {
                $sql = "INSERT INTO branch (br_code,
                                              br_name,
                                              br_address,
                                              br_city,
								                              br_state,
								                              br_country,
                                              br_phone,
                                              br_mobile,
                                              br_email,
                                              br_fax,
                                              br_headcode,
                                              br_head,
                                              br_tax,
                                              br_remarks,
                                              br_active,
                                              manifest,
                                              exe_code,
                                              exe_name,
                                              role,
                                              cate_code,
                                              brcate_code,
                                              stncode,
                                              stnname,
                                              transit_hub,
                                              curinvno,
                                              curcolno,
                                              rate,
                                              podall,
                                              br_ncode,
                                              origin,
                                              bkgstaff,
                                              proprietor,
                                              commission,
                                              pro_commission,
                                              branchtype,
                                              intl_rate,
                                              am,
                                              user_name, 
                                              virtual_edit,   
                                              cc_enable,        
                                              date_added)                                               
                                              VALUES ('$br_code',
                                                          '$br_name',
                                                          '$br_address',
                                                          '$br_city',
                                              					  '$br_state',
                                              					  '$br_country',
                                                          '$br_phone',
                                                          '$br_mobile',
                                                          '$br_email',                                                        
                                                          '$br_fax',
                                                          '$br_headcode,',
                                                          '$br_head',                                                         
                                                          '$br_tax',                                                          
                                                          '$br_remarks',                                                    
                                                          '$br_active',                                                       
                                                          '$manifest', 
                                                          '$exe_code',
                                                          '$exe_name',
                                                          '$role',                                                       
                                                          '$cate_code',                                                       
                                                          '$brcate_code',                                                     
                                                          '$stncode',
                                                          '$stnname',                                                         
                                                          '$transit_hub',
                                                          '$curinvno',
                                                          '$curcolno',
                                                          '$rate',
                                                          '$podall',
                                                          '$br_ncode',
                                                          '$origin',
                                                          '$bkgstaff',
                                                          '$proprietor',
                                                          '$commission',
                                                          '$pro_commission',
                                                          '$branchtype',
                                                          '$intl_rate',
                                                          '$am',
                                                          '$username',
                                                          '$virtual_edit',    
                                                          '$cc_enable',                                                    
                                                          '$date_added')";

              $result  = $db->query($sql);
              }
              else
              {
               echo "data not added";
              }
              header("location: branch?success=2"); 
             }

            
             if(isset($_POST['action']) && $_POST['action'] == 'virtual_edit'){
                $branchcode = $_POST['branchcode'];
                $branchname = $_POST['branchname'];
                $station = $_POST['station'];
                 $select_branch = $db->query("SELECT virtual_edit FROM branch WHERE br_code='$branchcode' AND br_name='$branchname' AND stncode='$station'")->fetch(PDO::FETCH_OBJ);
                $virutaledit = $select_branch->virtual_edit;
                 echo json_encode(array($virutaledit));
                  exit();
                }
  
  
              
                if(isset($_POST['action']) && $_POST['action'] == 'virtual_check'){
                  $branch = $_POST['branch'];
                  $brname = $_POST['brname'];
                  $station1 = $_POST['station1'];
  
                  $selbranch_name = $db->query("SELECT br_name FROM branch WHERE br_code='$branch' AND stncode='$station1'")->fetch(PDO::FETCH_OBJ);
                   $selbranch = $db->query("SELECT virtual_edit FROM branch WHERE br_code='$branch' AND br_name='$selbranch_name->br_name' AND stncode='$station1'")->fetch(PDO::FETCH_OBJ);
                  $getvalue = $selbranch->virtual_edit;
                   echo json_encode(array($getvalue));
                    exit();
                  } 
                
?>
