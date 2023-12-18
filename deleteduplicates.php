<?php

include 'db.php';

$sql = $db->query("SELECT cnote_number,cnote_station,count(cnote_number) from cnotenumber group by cnote_number,cnote_station having count(cnote_number)=2");

while($row = $sql->fetch(PDO::FETCH_OBJ)){
     $db->query("DELETE TOP (1) FROM cnotenumber WHERE cnote_number='$row->cnote_number' AND cnote_station='$row->cnote_station'");
}

echo "Success";