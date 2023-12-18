

<?php

require 'db.php';

date_default_timezone_set('Asia/Kolkata');

 // $salesrow = $db->query("SELECT * FROM cnoteallot where allotorderid = 'AO00436'");

 $salesrow = $db->query("SELECT * FROM cnoteallot where allotorderid between 'AO05574' and 'AO05581'");
 
// $salesrow = $db->query("SELECT * FROM cnoteallocationcc");

while($salesdata = $salesrow->fetch(PDO::FETCH_OBJ))
{
    // if($salesdata->cnote_type=='PRO')
    // {
    //     $serial = 'PRO';
    //  }
     
    //  //elseif($salesdata->origin=='PRC')
    // // {
    // //     $serial = 'PRC';
    // // }
    // else
    // {
    //     $serial = 'GEN';
    // }

    $ins_count = 0;

    for($cno=$salesdata->cnote_start;$cno<=$salesdata->cnote_end;$cno++)
    {
        $checkcno = $db->query("SELECT cnote_number FROM cnotenumber WHERE cnote_number='$cno' AND cnote_station='$salesdata->stncode' AND br_code='$salesdata->br_code'");
        if($checkcno->rowCount()==0)
        {
            //cnote allotment to branch

            // $ins_count++;
            // $db->query("INSERT INTO cnotenumber with (rowlock) (cnote_station,br_code,br_name,allot_po_no,cust_code,cnote_serial,cnote_number,cnote_status,date_added, user_name, allot_cc_no,ccinvno,cc_status)
            // VALUES ('$salesdata->stncode','$salesdata->br_code','$salesdata->br_name','$salesdata->allotorderid',NULL,'$salesdata->cnote_serial','$cno','$salesdata->cnote_status','$salesdata->date_added','$salesdata->user_name',NULL,NULL,NULL)");

            
            //cnote allotment to coll center

            // $ins_count++;
            // $db->query("INSERT INTO cnotenumber (cnote_station,br_code,br_name,cust_code,cust_name,cnote_serial,cnote_number,cnote_status,date_added, user_name, allot_cc_no,ccinvno,cc_status)
            // VALUES ('$salesdata->stncode','$salesdata->br_code','$salesdata->br_name','$salesdata->cc_code','$salesdata->cc_name','$salesdata->cnote_serial','$cno','Allotted','$salesdata->date_added','$salesdata->user_name',NULL,NULL,NULL)");


            //cnote sales to customer

            $ins_count++;
            $db->query("INSERT INTO cnotenumber (cnote_station,br_code,cust_code,cust_name,cnote_serial,cnote_number,cnote_status,date_added, user_name, allot_cc_no)
            VALUES ('$salesdata->stncode','$salesdata->br_code','$salesdata->receiptno','$salesdata->allot_inv_no','$salesdata->cnote_serial','$cno','Allotted','$salesdata->date_added','$salesdata->user_name',NULL)");



        }
    }

}

?>