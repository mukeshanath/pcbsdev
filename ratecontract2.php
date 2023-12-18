<?php

include 'vendor/bootstrap/simplexlxs.php';
include 'db.php';


if(isset($_FILES["importfile"])){
    $file = $_FILES['importfile']['tmp_name'];
    $file_ext = pathinfo($_FILES["importfile"]['name'], PATHINFO_EXTENSION);
    
    if($file_ext == 'xlsx'){
      $xlsx = SimpleXLSX::parse($file);
      $xlsxrow = $xlsx->rows();
    }
    else if($file_ext == 'csv'){
    ini_set('auto_detect_line_endings', TRUE);

    $header = '';
    $xlsxrow = array_map('str_getcsv', file($file));

    }

    $destinations = array($_POST['destination']);
    $modes = array($_POST['mode_code']);
    //$header = array_shift($xlsxrow);
    //$csv = array();
        //end
        $r=0;
        $ins_counts=0;
      foreach($xlsxrow as $rows){
        $r++;
         if($r==1 || $r==2){
    
         }else{

            $c=0;
        
            for($i=0;$i<count($destinations);$i++)
            {
            //creating rate contracts
            //Tamilnadu - 1
            $con_code = gencontractcode($db);
            $cust_code = $rows[0];
            $stncode = $_POST['comp_code'];
            $destn = $destinations[$i];
            $mode = $modes[$i];
            //$wtband = $wtbands[$i];

            $db->query("INSERT INTO rate_custcontract (con_code,cust_code,stncode,origin,destn,mode,calcmethod,splcharges) VALUES
            ('$con_code','$cust_code','$stncode','$stncode','$destn','$mode','D','0')");
			      //Generating Rate Id
            $ins_counts++;

            //band1
            $onadd = 0;
            $lowerwt = 0.001;
            $upperwt = 10;
            $rate = $rows[1];
            $multiple = $onadd;
            if($upperwt>0){
            $db->query("INSERT INTO rate_contractdetail (con_code,stncode,onadd,lowerwt,upperwt,rate,multiple)
						VALUES ('$con_code','$stncode','$onadd','$lowerwt','$upperwt','$rate','$multiple')");
            }
            //band2
            $onadd = 1;
            $lowerwt = 10.001;
            $upperwt = 999.99;
            $rate = $rows[2];
            $multiple = $onadd;
            if($upperwt>0){
            $db->query("INSERT INTO rate_contractdetail (con_code,stncode,onadd,lowerwt,upperwt,rate,multiple)
						VALUES ('$con_code','$stncode','$onadd','$lowerwt','$upperwt','$rate','$multiple')");
            }
            // //band3
            // $onadd = $rows[5];
            // $lowerwt = $rows[3]+0.001;
            // $upperwt = $rows[6];
            // $rate = $rows[7];
            // $multiple = $onadd;
            // if($upperwt>0){
            // $db->query("INSERT INTO rate_contractdetail (con_code,stncode,onadd,lowerwt,upperwt,rate,multiple)
						// VALUES ('$con_code','$stncode','$onadd','$lowerwt','$upperwt','$rate','$multiple')");
            // }
            // //band4
            // $onadd = $rows[8];
            // $lowerwt = $rows[6]+0.001;
            // $upperwt = $rows[9];
            // $rate = $rows[10];
            // $multiple = $onadd;
            // if($upperwt>0){
            // $db->query("INSERT INTO rate_contractdetail (con_code,stncode,onadd,lowerwt,upperwt,rate,multiple)
						// VALUES ('$con_code','$stncode','$onadd','$lowerwt','$upperwt','$rate','$multiple')");
            // }
            // //band5
            // $onadd = $rows[11];
            // $lowerwt = $rows[9]+0.001;
            // $upperwt = $rows[12];
            // $rate = $rows[13];
            // $multiple = $onadd;
            // if($upperwt>0){
            // $db->query("INSERT INTO rate_contractdetail (con_code,stncode,onadd,lowerwt,upperwt,rate,multiple)
						// VALUES ('$con_code','$stncode','$onadd','$lowerwt','$upperwt','$rate','$multiple')");
            // }
            //creating rate contracts
            }
           }
    }
    echo "<h3 style='color:green'>Data Added Successfully. Total $ins_counts Contracts</h3>";
}

function gencontractcode($db){
          $ccode="SELECT TOP 1 con_code FROM rate_custcontract ORDER BY cast(dbo.GetNumericValue(con_code) AS decimal) DESC";
          $code=$db->query($ccode);
          $coderow=$code->fetch(PDO::FETCH_ASSOC);
          if($code->rowCount()== 0){
            $con_code = 'CO'.str_pad('1', 7, "0", STR_PAD_LEFT);
          }
          else{
            $row = $coderow['con_code'];
            $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$row);
            // $arr[0];PO
            // $arr[1]+1;numbers
            $con_code = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
          }
        return $con_code;
}

?>

<form method="POST" enctype="multipart/form-data">
Enter Company &nbsp;&nbsp; : <input type="text" name="comp_code" value="MAA"><br><br>
Enter Destination : <input type="text" name="destination"><br><br>
Enter Mode Code :  <input type="text" name="mode_code"><br><br>
Select Contract File <input type="file" name="importfile" accept=".csv,.xlsx" onchange="ValidateSingleInput(this);">
    <br><br> <input type="submit" value="Submit" style="float:left" > <br>

  
</form>
<script>
     var _validFileExtensions = [".csv", ".xlsx"];    
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
            if (!blnValid) {
                alert("Sorry, file is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}
</script>