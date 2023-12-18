<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
  include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <title>API</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
    <span>(c) Copyright: 2020-03-04 ... AS2 Web Service API......</span> <span style='font-weight:bold'>BA Inbound push</span>
        <script>

   var datum= 
    <?php
        date_default_timezone_set('Asia/Kolkata');
        $today_date = date("Y-m-d");
        $users = array();
        $customers = $db->query("SELECT cust_code FROM customer WHERE push_to_website='1'");
        while($custrow = $customers->fetch(PDO::FETCH_OBJ)){
           $data = $db->query("SELECT * FROM bainbound WHERE date_added='$today_date' AND bacode='$custrow->cust_code'");
           while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){
               $users[] = array(
                   'POD_NO'      =>  $OutputData['cno'],
                   'WEIGHT'      =>  $OutputData['wt'],
                   'PIECES'      =>  $OutputData['pcs'],
                   'AMOUNT'      =>  $OutputData['amt'],
                   'DESTINATION' =>  $OutputData['dest_code'],
                   'ORIGIN'      =>  $OutputData['origin'],
               );
           }
        }
       
        echo json_encode($users);

       ?>

            function postdata(){
                var count = Object.keys(datum).length;   
                    for(i=0;i<count;i++){  
                        var POD_NO1 = datum[i].POD_NO;
                        var WEIGHT1 = datum[i].WEIGHT;
                        var PIECES1 =  datum[i].PIECES;
                        var AMOUNT1 =  datum[i].AMOUNT;
                        var DESTINATION1 =  datum[i].DESTINATION;
                        var ORIGIN1 =  datum[i].ORIGIN;
                        var cno     = POD_NO1;
                        $.ajax({
                            //url:"http://pcbsdev.cdssapps.in/datapull",// url to post data
							url:"http://www.tpcindia.com/TPCWebService/IOM.aspx",
                            method:"POST",
                            data:{pod_no:POD_NO1,Weight:WEIGHT1,PIECES:PIECES1,AMOUNT:AMOUNT1,DESTINATION:DESTINATION1,origin:ORIGIN1,TYPEOFDOC:'INBOUND'},
                            dataType:"text",
                            async:false,  
                            success:function(data1){
                            //  alert(data1);
                            },
                            complete:function(data2){
                            //  alert(data2);
                            }
                            });  
                    } 
                }
                    postdata();
                    window.close();
</script>

    </body>


</html>