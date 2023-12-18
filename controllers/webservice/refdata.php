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

class refdata extends My_controller
{
	public function index()
	{
      	$this->view->title = __SITE_NAME__ . ' - refdata';
	}
}
   
    //Get Credit data to push to website
    class groupapi
    {
        function Select($db)
        {
            $today_date = date("Y-m-d");
            $users = array();
            $data = $db->query("SELECT * FROM credit WHERE date_added='$today_date' AND flag='1'");
            while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
                $users[] = array(
                    'POD_NO'      =>  $OutputData['cno'],
		            'CUSTOMER'    =>  $OutputData['cust_code'],
                    'WEIGHT'      =>  $OutputData['wt'],
                    'PIECES'      =>  $OutputData['pcs'],
                    'AMOUNT'      =>  $OutputData['amt'],
                    'DESTINATION' =>  $OutputData['dest_code'],
                    'ORIGIN'      =>  $OutputData['origin'],
                );
            }
            return json_encode($users);

        }
    }
 
    $API = new groupapi;
    echo $API->Select($db);   

   


