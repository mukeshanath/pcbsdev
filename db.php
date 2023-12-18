<?php



$servername = "DESKTOP-16VPQMH,2301";
$username = "sa";     
$password = "Repute2023";
$database = "pcbs_test";//pcnl_migration_8

      try 
      {
           $db = new PDO("sqlsrv:server=$servername;Database=$database;ConnectionPooling=0", $username, $password);
           $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } 
      catch (PDOException $e) 
      {
           echo ("Error connecting to SQL Server: " . $e->getMessage());
      }


    

    
?>

