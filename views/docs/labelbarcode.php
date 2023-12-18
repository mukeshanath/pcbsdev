
<?php include 'db.php';
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
	<style type="text/css">
@page {
    -webkit-print-color-adjust: exact!important;
    margin: 1.5mm 1.5mm 1.5mm 1.5mm;
 }
@media print {
    .container{
        page-break-after: always;
     }
     .labelbox1{
        page-break-inside: avoid;
        margin-top:27px !important;

     }
 .cnotenumber{
    text-align:center !important;
    margin-left:0px !important;
    margin-top:4px !important;
 }   
html, body {
    
  height:100vh; 
  margin: 0 !important; 
  padding: 0 !important;
  
  
}
.label{
    text-align: center!important;
}
.page{
    width: 100%!important;
}
.result12 img{
    width:50px!important;
}



.labelbox{
    page-break-inside: avoid;
    height: 32px;
}

}
		
.label{
    text-align: center!important;
    height: 18px;
}         
.squarebox{
  
    border: 1px solid black;
   height: 28px;
   width: 58px;
   padding-left: 18px;
   font-weight: bold;
   padding-top:8px;
   font-size:20px;
   
}

.labelbox1{
    height:27px;
}
.result12 img{
    width:165px!important;
    height:32px;
}

	</style>
</head>

<body style="margin-left: 0px;margin-top: 10px;">
  <?php if(isset($_GET['cno'])){ 
    $pcs = (int)$_GET['pcs'];
    $cno = $_GET['cno'];
    $wt = $_GET['wt'];
    $cc = $_GET['cc'];
    $destcode = $_GET['destcode'];
    $podorigin = $_GET['podorigin'];




    $user = $_SESSION['user_name'];

    $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
    $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
    $rowuser = $datafetch['last_compcode'];
    if(!empty($rowuser)){
         $stationcode = $rowuser;
    }
    else{
        $userstations = $datafetch['stncodes'];
        $stations = explode(',',$userstations);
        $stationcode = $stations[0];     
    } 
    ?>
  
  <?php for($a=0;$a<$pcs;$a++){;  ?>
    
    <div class="container" >
    <div class="labelbox" style="display: flex;">
            <div class="logolabel"><img src="views/image/TPC-Logo.JPG" width="130px" height="40px"></div>
            
            <div style="margin-bottom:10px;margin-left: 0px;" class="result12"><img src="<?=__ROOT__?>barcode?text=<?=$_GET['cno']?>&codetype=code128&orientation=horizontal&size=20&print=<?=$_GET['cno']?>" class="barcode float-right">
            <div class="cnotenumber">
         <b><?=$podorigin.$_GET['cno']?></b>
        </div>
        </div>
            
            <div class="squarebox"><?=$destcode?> </div>
        </div> 
      
        <div class="labelbox1" style="display: flex;margin-top:5px;">
            <div style="margin-bottom:10px;letter-spacing: 0px;font-size: 17PX;margin-top: 1px;margin-left: 7px;" id="logo"><b style="margin-left:16px;"><?=$cc?></b></div><div style="margin-top:2px;margin-left: 18px;letter-spacing: 1px;" id="wt"><b>Wt&nbsp; :&nbsp;  <?=number_format($wt,2);?></b></div><div style="margin-top:2px;margin-left: 20px;" class="pcs"><b>Pcs&nbsp;&nbsp; :&nbsp; <?=$a+1?>&nbsp;&nbsp;/&nbsp;<?=$pcs?></b></div>
        </div>
    </div>
    <?php }  ?>

    <?php }  ?>

</body>

</html>



<!-- 
<div class="container" >
        <div class="labelbox" style="display: flex;">
            <div class="logolabel"><img src="views/image/TPC-Logo.JPG" width="110px" height="29px"></div><div style="margin-bottom:10px;margin-left: 0px;" class="result12"><img src="<?=__ROOT__?>barcode?text=<?=$_GET['cno']?>&codetype=code128&orientation=horizontal&size=20&print=<?=$_GET['cno']?>" class="barcode float-right"></div><div class="squarebox">TRZ </div>
        </div>
        <div class="cnotenumber" style="margin-left:140px;">
         <b><?=$_GET['cno']?></b>
        </div>
        <div class="labelbox1" style="display: flex;">
            <div style="margin-bottom:10px;letter-spacing: 1.5px;font-size: 17PX;margin-top: 1px;margin-left: 7px;" id="logo"><b>AMB1065</b></div><div style="margin-top:0pxpx;margin-left: 26px;letter-spacing: 1px;" id="wt"><b>Wt&nbsp; :&nbsp;&nbsp;&nbsp;&nbsp;  0.100</b></div><div style="margin-top:0px;margin-left: 30px;" class="pcs"><b>Pcs&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp;&nbsp; <?=$a+1?>&nbsp;&nbsp;/&nbsp;&nbsp;<?=$pcs?></b></div>
        </div>
    </div> -->






