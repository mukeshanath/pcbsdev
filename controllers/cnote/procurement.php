<?php defined('__ROOT__') OR exit('No direct script access allowed');



      include 'db.php'; 

      class procurement extends My_controller
      {
        

          public function index()
          {
              $this->view->title = __SITE_NAME__ . ' - procurement';
              $this->view->render('cnote/procurement');
          }
      }

      date_default_timezone_set('Asia/Kolkata');

          $errors = array();
          
         if (isset($_POST['req_no'])) {
          try
		        {
            procurecnote($db);
            } catch(PDOException $e){
            $date_added	= date('Y-m-d H:i:s');
            $error_info	= $e->errorInfo[1];
            $error_detail	= explode("]",$e->errorInfo[2]);
            $err_desc = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', END($error_detail));
      
            $db->query("INSERT INTO logs_tb (log_datetime,status) VALUES ('$date_added','Procurement Error - $err_desc')"); 
      
          }
            exit;
         }

              //fetching the serial type
              function procurecnote($db){

              date_default_timezone_set('Asia/Kolkata');
              $date_added       = date('Y-m-d H:i:s');

              $cnote_station           = $_POST['station_code'];
              //$station_name           = $_POST['station_name'];
              $cnotepono              = $_POST['req_no'];
              $cnote_status           = 'Procured';
              $vendor_code           = $_POST['vendor_code'];
              $username               = $_POST['user_name'];
              $cnote_serial           = $_POST['cnote_serial'];
              $cnote_count            = $_POST['cnote_count'];


              $ctype = $db->query("SELECT grp_type FROM groups WHERE cate_code='$cnote_serial' AND stncode='$cnote_station'");            
              $grptype = $ctype->fetch(PDO::FETCH_ASSOC);

              if($grptype['grp_type']=='sp')
              {
               
                $sql = "INSERT INTO cnote(cnotepono,cnote_station,vendor_code,cnote_requestno,cnote_serial,cnote_count,cnote_status,user_name,date_added)
                      VALUES('$cnotepono','$cnote_station','$vendor_code','','$cnote_serial','$cnote_count','$cnote_status','$username','$date_added')";
                     $insert2 = $db->query($sql);

             }
              else if($grptype['grp_type']=='gl'){
              //get cnoteend from cnotetb
              $sql="SELECT TOP 1 cnote_end FROM cnote WHERE cnote_serial='$cnote_serial' AND cnote_station='$cnote_station' ORDER BY cnoteid DESC";
              $result=$db->query($sql);
              //check previous cnoteend(column) from cnotetb(table) for continue with previous serial number

              //if previous cnoteend value is not available then if condition works 
              if ($result->rowCount()== 0) {
                  
                  //fetching grouptb data for whileloop line no 43
                  $sql="SELECT * FROM groups WHERE stncode='$cnote_station'";
                  $result=$db->query($sql);

                  $sql2="SELECT range_ends FROM groups WHERE cate_code='$cnote_serial' AND stncode='$cnote_station'";
                  
                  $result2=$db->query($sql2);
                  $row2=$result2->fetch(PDO::FETCH_ASSOC);
                  //get the rangeend from grouptb in variable
                  $range_ends=$row2['range_ends'];

                  while($row=$result->fetch(PDO::FETCH_ASSOC))
                  {  
                      //fetch catecode and rangestart to generate serial numbers
                      if($cnote_serial==$row['cate_code'])
                      {
                          $count=$row['range_starts'];
                      }
                  }
                  //length of the forloop -- rangestart ++ cnotecount from textbox
                  $count_ends=$count+$cnote_count-1;
                  if ($count_ends>$range_ends) {
                      echo "Maximim";
                  }
                  else{
                     

                $sql = "INSERT INTO cnote(cnotepono,cnote_station,vendor_code,cnote_serial,cnote_count,cnote_start,cnote_end,cnote_status,user_name,date_added)
                      VALUES('$cnotepono','$cnote_station','$vendor_code','$cnote_serial','$cnote_count','$count','$count_ends','Requested','$username','$date_added')";
                  $insert2 = $db->query($sql);

               
                  }
              }
              //if the previous cnoteend value is available in cnotetb then else statement will run
              else{
                  //fetch rangeend from grouptb to prevent limit overlay
                  $sql2="SELECT range_ends FROM groups WHERE cate_code='$cnote_serial' AND stncode='$cnote_station'";
                  $result2=$db->query($sql2);
                  $row2=$result2->fetch(PDO::FETCH_ASSOC);
                  //get the rangeend from grouptb in variable
                  $range_ends=$row2['range_ends'];
                  $row=$result->fetch(PDO::FETCH_ASSOC);
                  //get cnoteend from cnotetb table and add one(1) and assign in variable
                  $cnote_countstart=$row['cnote_end']+1;
                  //$cnotecount=$_POST['cnotecount'];
                  //calculate loop end and get in variable
                  $count_ends=$cnote_count+$cnote_countstart-1;
                  //check for limit using rangeend
                  if ($count_ends>$range_ends) {
                      echo "Maximim";
                  }
                  else{
                  
                      $sql = "INSERT INTO cnote (cnotepono,cnote_station,vendor_code,cnote_requestno,cnote_serial,cnote_count,cnote_start,cnote_end,cnote_status,user_name,date_added)
                      VALUES('$cnotepono','$cnote_station','$vendor_code',null,'$cnote_serial','$cnote_count','$cnote_countstart','$count_ends','Requested','$username','$date_added') ";
                      //echo json_encode($db);exit;
                      $db->query($sql);

                  }
              }
            }
          //header("location: procurementlist?success=1"); 
        }

?>
