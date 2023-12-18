<?php

require 'db.php';

date_default_timezone_set('Asia/Kolkata');

try{
  // $salesrow = $db->query("SELECT * FROM cnoteallot where stncode='ASR' and br_code='ASRBA' and year(idate)='2019'   ");
  $salesrow = $db->query("SELECT * FROM cnoteallot where stncode='CCU' and br_code between 'TRLBA0001' and 'TRLBA9000'     order by idate asc");
 // $salesrow = $db->query("SELECT * FROM cnoteallot  where cnoteallotid='8143'");
//$salesrow = $db->query("SELECT * FROM cnoteallot  where cnoteallotid='8577'");

while($salesdata = $salesrow->fetch(PDO::FETCH_OBJ)){
    if($salesdata->origin=='PRO'){
        $serial = 'PRO';
     }//elseif($salesdata->origin=='PRC')
    // {
    //     $serial = 'PRC';
    // }
    else{
        $serial = 'BA';
    }

    $ins_count = 0;
    for($cno=$salesdata->cnote_start;$cno<=$salesdata->cnote_end;$cno++){
        $checkcno = $db->query("SELECT cnote_number FROM cnotenumber WHERE cnote_number='$cno' AND cnote_station='$salesdata->stncode'");
        if($checkcno->rowCount()==0){
            $ins_count++;
            $db->query("INSERT INTO cnotenumber (cnote_station,br_code,cust_code,cnote_serial,cnote_number,cnote_status,date_added)
            VALUES ('$salesdata->stncode','TRLBA','$salesdata->br_code','$serial','$cno','Allotted','2022-05-30')");
        } 
    }

    $actualcount = $cno<=$salesdata->cnote_end-$salesdata->cnote_start;
    $db->query("INSERT INTO cnote_audit (stncode,br_code,cust_code,cnote_serial,cnote_count,cnote_start,cnote_end,count)
 VALUES ('$salesdata->stncode','TRLBA','$salesdata->br_code','$serial','$actualcount','$salesdata->cnote_start','$salesdata->cnote_end','$ins_count')");

}

}catch(PDOException $e){
    echo $e;
}

?>