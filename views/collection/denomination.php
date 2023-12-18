<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 



function authorize($db,$user)
{
    
    $module = 'denomination';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;

    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return $access='False';
        }
        else{
            return $access='True';
        }
}

if(authorize($db,$user)=='False'){

    echo "<div class='alert alert-danger alert-dismissible'>
            
            <h4><i class='icon fa fa-times'></i> Alert!</h4>
            You don't have permission to access this Page.
            </div>";
            exit;
  }
  
function loadmode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM mode ORDER BY mid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->mode_name.'">'.$row->mode_name.'</option>';
         }
         return $output;    
        
    }
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


  //branch filter conditions
  $sessionbr_code = $datafetch['br_code'];
  if(!empty($sessionbr_code)){
      $br_code = $sessionbr_code;
      $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
      $br_name     = $fetchbrname->br_name;
      }
   //branch filter conditions

    function colmasno($db,$stationcode)
    {
    
    $prevcolmasno=$db->query("SELECT TOP 1 col_mas_rcptno FROM collection_master WHERE comp_code='$stationcode' ORDER BY col_mas_id DESC");
    if($prevcolmasno->rowCount()== 0)
        {
        $colomasno = $stationcode.str_pad('1', 7, "0", STR_PAD_LEFT);
        }
    else{
        $prevcolmasrow=$prevcolmasno->fetch(PDO::FETCH_ASSOC);
        $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$prevcolmasrow['col_mas_rcptno']);
        $arr[0];//PO
        $arr[1]+1;//numbers
        $colomasno = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
        }
     return $colomasno;            
    }

    function collectionno($db,$stationcode)
    {
        $prevcolno=$db->query("SELECT TOP 1 colno FROM collection WHERE comp_code='$stationcode' ORDER BY col_id DESC");
         if($prevcolno->rowCount()== 0){
            $colono = 10001;
            }
        else{
            $prevcolrow=$prevcolno->fetch(PDO::FETCH_ASSOC);
            $colono = $prevcolrow['colno']+1;
            }
        return $colono;        
    }

    // //Total cheque and entry count of cheque
    // function gettotalcheque($db,$stationcode){
    //     $data = $db->query("SELECT sum(amt) as totalchq,count(amt) as datacount FROM cash WHERE payment_mode='Cheque' AND comp_code='$stationcode' AND cast(date_added as date)=cast(getdate() as date)")->fetch(PDO::FETCH_OBJ);
    //     return array($data->datacount,$data->totalchq);
    // }
    // $gettotalcheque = gettotalcheque($db,$stationcode);
    // //Total cheque and entry count of cheque

    // //Total upi and entry count of upi
    // function gettotalupi($db,$stationcode){
    //     $data = $db->query("SELECT sum(amt) as totalchq,count(amt) as datacount FROM cash WHERE payment_mode='UPI' AND comp_code='$stationcode' AND cast(date_added as date)=cast(getdate() as date)")->fetch(PDO::FETCH_OBJ);
    //     return array($data->datacount,$data->totalchq);
    // }
    // $gettotalupi = gettotalupi($db,$stationcode);
    // //Total upi and entry count of upi

    // //Total neft and entry count of neft
    // function gettotalneft($db,$stationcode){
    //     $data = $db->query("SELECT sum(amt) as totalchq,count(amt) as datacount FROM cash WHERE payment_mode='NEFT' AND comp_code='$stationcode' AND cast(date_added as date)=cast(getdate() as date)")->fetch(PDO::FETCH_OBJ);
    //     return array($data->datacount,$data->totalchq);
    // }
    // $gettotalneft = gettotalneft($db,$stationcode);
    // //Total neft and entry count of neft

    // //Total cash and entry count of cash
    // function getsumoftotalentryamt($db,$stationcode){
    //     $data = $db->query("SELECT sum(amt) as totalcash FROM cash WHERE comp_code='$stationcode' AND cast(date_added as date)=cast(getdate() as date)")->fetch(PDO::FETCH_OBJ);
    //     return array($data->totalcash);
    // }
    // $getsumoftotalentryamt = getsumoftotalentryamt($db,$stationcode);
    // //Total cash and entry count of cash
    // $collectedamount = $gettotalcheque[1]+$gettotalupi[1]+$gettotalneft[1];
    if(isset($_GET['invno']))
      {
        $invoicenumber = $_GET['invno'];
        $collection = $db->query("SELECT TOP(1) * FROM collection WHERE invno='$invoicenumber' ORDER BY coldate DESC,col_id DESC");
        $row = $collection->fetch(PDO::FETCH_OBJ);
        $getrunbal = $db->query("SELECT TOP(1) totamt,running_balance FROM collection_master WHERE cust_code='$row->cust_code' ORDER BY col_mas_id DESC");
        $rowrunbal = $getrunbal->fetch(PDO::FETCH_OBJ);
        $getecptpayref = $db->query("SELECT TOP(1) rcptno,chqddno FROM collection_master WHERE cust_code='$row->cust_code' ORDER BY col_mas_id DESC")->fetch(PDO::FETCH_OBJ);
      }
      
?>
<style>  
 .lc2k,.lc500,.lc200,.lc100,.lc50,.lc20,.lc10,.lc5
{
    width:80px;
}

.lc500,.lc50,.lc5
{
    margin-left:-100px;
}

.form-control
{
    width:150px;
    
} 
.form-control2k,.form-control100,.form-control10,.form-control200,.form-control20
{
    margin-left:-70px;

}


</style>

<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">
<div id="col-form1" class="col_form lngPage col-sm-25">
        <!-- BreadCrum -->
        <div class="au-breadcrumb m-t-75" style="margin-bottom: -5px;">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="au-breadcrumb-content">
                                    <div class="au-breadcrumb-left">
                                        <span class="au-breadcrumb-span"></span>
                                        <ul class="list-unstyled list-inline au-breadcrumb__list">
                                            <li class="list-inline-item active">
                                                <a href="#">Dashboard</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Accounts</li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">Denomination</li>
                                        </ul>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

             <div class="alert-primary" role="alert">

               <?php 
                      if ( isset($_GET['success']) && $_GET['success'] == 1)
                  {
                       echo 
                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Collection Record Added successfully.
                  </div>";
                  }
                  if (isset($_GET['unsuccess']))
                  {
                       echo 
                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Please Check form Errors. <strong>".$_GET['unsuccess']."</strong>
                  </div>";
                  }
               ?>
             </div>
      <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>denomination" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
        <div class="boxed boxBt panel panel-primary"> 
       <!-- Title -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Denomination Entry Add Form</p>
        <div class="align-right" style="margin-top:-35px;">
        <a style="color:white" href="<?php echo __ROOT__ ?>denominationlist"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times"></i>
        </button>
    </a>
        </div>
        </div>         
        <div class="content panel-body">
        <div class="tab_form" style="background:#fff;border: 1px solid var(--heading-primary);">
        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>  
            <td>Company</td>
            <td style="width:100px;">
                <input placeholder="" class="form-control stncode" name="comp_code" type="text"  id="stncode" readonly>
            </td>
            <td>
                <input class="form-control border-form "  name="comp_name" type="text" id="stnname" readonly>
            </td>
            <td>Branch</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" required class="form-control br_code" name="br_code"  type="text"  id="br_code" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_code))?$br_code:''; ?>">
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form "  name="br_name"  type="text" id="br_name" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?=(!empty($br_name))?$br_name:''; ?>">
            </div></td>
            <td>Collection Date</td>
            <td>
                <input placeholder="" class="today-max form-control today" name="coldate" type="date"  id="coldate" onchange="getdenominationamount()">
            </td>

            <!-- <td>Collection Center</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" class="form-control cust_code"  name="coll_code" type="text"  id="coll_code" value="<?php if(isset($_GET['invno'])){echo $row->cust_code; } ?>" onkeyup="customervalidation()">
                <p id="custcode_validation" class="form_valid_strings">Enter Valid Collection center Code</p>
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form"  name="coll_name" type="text" id="coll_name" value="<?php if(isset($_GET['invno'])){echo $row->cust_name; } ?>" >
            </div></td>             -->
        </tr>                      
        </tbody>
        </table>
        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>   

          <tr>          
            <td>₹2000&nbsp;<b>X</b></td>
            <td>
            <input placeholder="" class="lc2k"  name="m2k" min="0"  type="number"  onfocusout="viewamt2k();viewamt()"  id="m2k"  value="0"></td>
            <td> <input placeholder="" class="form-control2k" readonly name="m2000" type="text"  id="m2000" value="0">
          </td>
          <td>₹500&nbsp;<b>X</b></td>
          <td> 
            <input placeholder="" class="lc500" name="mo500" type="number" min="0" onfocusout="viewamt500();viewamt()"  id="mo500" value="0"></td>
            <td>
                <input placeholder="" class="form-control" readonly name="m500" type="text"  id="m500" value="0">
          </td>
          <td>₹200&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc200" name="mo200" type="number" min="0" onfocusout="viewamt200();viewamt()"  id="mo200" value="0"></td>
            <td>
                <input placeholder="" class="form-control" readonly name="m200" type="text"  id="m200" value="0">
          </td>       
                </tr>
                <tr>
                  <td>₹100&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc100" name="mo100" type="number" min="0" onfocusout="viewamt100();viewamt()"  id="mo100" value="0"></td>
            <td>
                <input placeholder="" class="form-control100" readonly name="m100" type="text"  id="m100" value="0">
          </td>
          <td>₹50&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc50" name="mo50" type="number" min="0" onfocusout="viewamt50();viewamt()"  id="mo50" value="0"></td>
            <td>
                <input placeholder=""  class="form-control" readonly name="m50" type="text"  id="m50" value="0">
          </td>    
          <td>₹20&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc20" name="mo20" type="number" min="0"  onfocusout="viewamt20();viewamt()" id="mo20" value="0"></td>
            <td>
                <input placeholder="" class="form-control" readonly name="m20" type="text"  id="m20" value="0">
          </td>
                </tr>
                <tr>
          <td>₹10&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc10" name="mo10" type="number" min="0"  id="mo10" onfocusout="viewamt10();viewamt()" value="0"></td>
            <td>
                <input placeholder="" class="form-control10" readonly name="m10" type="text"  id="m10" value="0">
          </td>
          <td>₹5&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc5" name="mo5" type="number" min="0"  id="mo5" onfocusout="viewamt5();viewamt()" value="0"></td>
            <td>
                <input placeholder="" class="form-control" readonly name="m5" type="text"  id="m5" value="0">
          </td>
          <td>Coins Value</td>
            <td>
                <input placeholder="" class="lc10" name="coins" type="number" min="0" onfocusout="viewamt()"  id="coins" value="0">
          </td> 
         </tr>         
                
                <tr>
          <td>Cheque</b></td>
          <td>
            <input placeholder="" class="lc10" name="chq" type="number" min="0" readonly id="chq" value="<?=$gettotalcheque[0];?>"></td>
            <td>
                <input placeholder="" class="form-control10" readonly name="chqamt" onfocusout="viewamt()" type="number" min="0"  id="chqamt" value="<?=number_format($gettotalcheque[1],2);?>">
            </td>
          <td>UPI</b></td>
          <td>
            <input placeholder="" class="lc5" name="upi" type="number" min="0" readonly id="upi" value="<?=$gettotalupi[0];?>"></td>
            <td>
                <input placeholder="" class="form-control"  name="upiamt" onfocusout="viewamt()" type="number" min="0"  id="upiamt" readonly value="<?=number_format($gettotalupi[1],2);?>">
          </td>
          <td>NEFT</td>
          <td>
            <input class="lc10" name="cneft" type="number" min="0" readonly id="cneft" value="<?=$gettotalneft[0];?>"></td>
            <td>
                <input placeholder="" class="form-control"  name="neftamt" onfocusout="viewamt()" type="number" min="0" id="neftamt" readonly value="">
          </td>
                      </tr>         
                <tr>
                <td  style="width:100px;">Total Amt</td>
            <td>
                <input placeholder="" class="form-control" style="width:160px;" readonly name="totalamt" type="text"  id="totalamt" value="">
            </td>               
          <td  style="width:100px;">Collected Amount</td>
            <td>
                <input placeholder="" class="form-control" style="width:160px;" readonly name="colamt" type="text"  id="colamt" value="">
            </td>
          <td id="rema">Remarks</td>
            <td>
                <input placeholder=""   class="form-control" name="rmks" type="text"  id="rmks" value="">
          </td>  
          </tr> 
          </tbody>
        </table>                  
               
<script>
 function viewamt2k(){
    var value = parseInt($('#m2k').val());
    // var value1 = parseInt($('#mo500').val());
    // var value2 = parseInt($('#mo200').val());
    // var value3 = parseInt($('#mo100').val());
    // var value4 = parseInt($('#mo50').val());
    // var value5 = parseInt($('#mo20').val());
    // var value6 = parseInt($('#mo10').val());
    // var value7 = parseInt($('#mo5').val());
    // var value8 = parseInt($('#coins').val());
    

    $('#m2000').val(parseInt(value*2000));
    // $('#m500').val(parseInt(value1*500));
    // $('#m200').val(parseInt(value2*200));
    // $('#m100').val(parseInt(value3*100));
    // $('#m50').val(parseInt(value4*50));
    // $('#m20').val(parseInt(value5*20));
    // $('#m10').val(parseInt(value6*10));
    // $('#m5').val(parseInt(value7*5));

    var val = parseInt($('#m2000').val());
    // var val1 = parseInt($('#m500').val());
    // var val2 = parseInt($('#m200').val());
    // var val3 = parseInt($('#m100').val());
    // var val4 = parseInt($('#m50').val());
    // var val5 = parseInt($('#m20').val());
    // var val6 = parseInt($('#m10').val());
    // var val7 = parseInt($('#m5').val());
    // var val8 = parseInt($('#coins').val());
    // var val9 = parseInt($('#chqamt').val());
    // var val10 = parseInt($('#upiamt').val());


  // $('#colamt').val(parseInt(val+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10));
    
 }
 function viewamt500(){
    var value1 = parseInt($('#mo500').val());
    $('#m500').val(parseInt(value1*500));
    var val1 = parseInt($('#m500').val());
    //$('#colamt').val(parseInt(val+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10));
 }
 function viewamt200(){
    var value2 = parseInt($('#mo200').val());
    $('#m200').val(parseInt(value2*200));
    var val2 = parseInt($('#m200').val());
    //$('#colamt').val(parseInt(val+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10));
 }
 function viewamt100(){
    var value3 = parseInt($('#mo100').val());
    $('#m100').val(parseInt(value3*100));
    var val3 = parseInt($('#m100').val());
   // $('#colamt').val(parseInt(val+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10));
 }
 function viewamt50(){
    var value4 = parseInt($('#mo50').val());
    $('#m50').val(parseInt(value4*50));
    var val4 = parseInt($('#m50').val());
   // $('#colamt').val(parseInt(val+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10));

 }
 function viewamt20(){
    var value5 = parseInt($('#mo20').val());
    $('#m20').val(parseInt(value5*20));
    var val5 = parseInt($('#m20').val());
   // $('#colamt').val(parseInt(val+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10));

 }
 function viewamt10(){
    var value6 = parseInt($('#mo10').val());
    $('#m10').val(parseInt(value6*10));
    var val6 = parseInt($('#m10').val());
   // $('#colamt').val(parseInt(val+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10));
 }
 function viewamt5()
 {
    var value7 = parseInt($('#mo5').val());
    $('#m5').val(parseInt(value7*5));
    var val7 = parseInt($('#m5').val());
 }
    function viewamt()
    {
    var valo = parseInt($('#m2000').val());
    var val1 = parseInt($('#m500').val());
    var val2 = parseInt($('#m200').val());
    var val3 = parseInt($('#m100').val());
    var val4 = parseInt($('#m50').val());
    var val5 = parseInt($('#m20').val());
    var val6 = parseInt($('#m10').val());
    var val7 = parseInt($('#m5').val());
    var val8 = parseInt($('#coins').val());
    var val9 = parseInt($('#chqamt').val());
    var val10 = parseInt($('#upiamt').val());
    var val11 = parseInt($('#neftamt').val());
    $('#colamt').val(parseInt(valo+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10+val11));
    }
 
 //$('#colamt').val(parseInt(val+val1+val2+val3+val4+val5+val6+val7+val8+val9+val10));

function addcollection1()
{
  
       if(($('#m2000').val().trim()=="NaN") 
       || ($('#m500').val().trim()=="NaN")
       || ($('#m200').val().trim()=="NaN")
       || ($('#m100').val().trim()=="NaN")
       || ($('#m50').val().trim()=="NaN")
       || ($('#m20').val().trim()=="NaN")
       || ($('#m10').val().trim()=="NaN")
       || ($('#m5').val().trim()=="NaN")
       || ($('#coins').val().trim()=="")
       || ($('#chqamt').val().trim()=="")
       || ($('#upiamt').val().trim()=="")
       || ($('#colamt').val().trim()=="NaN")           
       )
        {
            swal("Make Sure all fields has valid count or 0");
        }
        else if(parseFloat($('#totalamt').val())!=parseFloat($('#colamt').val())){
            swal("Total amount and Collected amount must be same");
        }
        else{
            $('#addcollection').attr('type','submit');
            $('#addcollection').attr('name','addcollection');
        }
}
</script>

        <table class="table">
            <tr>	           
              <div class="align-right btnstyle">
                  <!-- <button type="button" class="btn btn-success btnattr_cmn" onclick="addancollection1()" id="addancollection">
                  <i class="fa fa-save"></i>&nbsp; Save & Add
                  </button> -->

                  <!-- <button type="button" class="btn btn-primary btnattr_cmn" onclick="viewamt()" id="viewamnt" >
                  <i class="fa fa-eye"></i>&nbsp; View Amount
                  </button> -->

                  <button type="button" class="btn btn-primary btnattr_cmn" onclick="addcollection1()" id="addcollection" >
                  <i class="fa fa-save"></i>&nbsp; Save
                  </button>

                  <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                  <i class="fa fa-eraser"></i>&nbsp; Reset
                  </button> 
                  </div>
              </tr>
          </table>
        </div>
        <!-- collection entry 2 form -->
        
            <!-- <div  class="col-sm-12" style="border :1px solid var(--heading-primary) ;background:#fff;margin-top:5px"> -->
          
            <!-- <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
            <tr>
  
            <td>Invoice No</td>
            <td >
                <input placeholder="" class="form-control text-uppercase" name="invno" type="text"  id="invno" value="<?php if(isset($_GET['invno'])){echo $row->invno; } ?>">
            </td>

            <td>Invoice Date</td>
            <td >
                <input placeholder="" class="form-control today" readonly name="invdate" type="date"  id="invdate" value="<?php if(isset($_GET['invno'])){echo $row->invdate; } ?>">
            </td>
            
            <td>Invoice Amount</td>
            <td>
                <input placeholder="" readonly class="form-control " name="invamt" type="text"  id="invamt" value="<?php if(isset($_GET['invno'])){echo round($row->invamt); } ?>">
            </td>
            <td>CRN No</td>
            <td>
                <input class="form-control " name="crn_no" type="text"  id="crn_no" value="<?php if(isset($_GET['invno'])){  } ?>">
            </td>
            
            
            </tr>
            <tr>
            <td>Collected Amount</td>
            <td >
              <input  class="form-control " min="0" onfocusout="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"  name="colamt" type="text"  id="colamt" value="<?php if(isset($_GET['invno'])){ if($rowrunbal->running_balance=='0'){}else{echo $rowrunbal->totamt;} } ?>">
            </td>
                   
            <td>TDS</td>
          <td>
              <input class="form-control " name="tds" type="text"  id="tds" min="0" onfocusout="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" >
          </td>
          <td>Invoice Balance</td>
            <td>
                <input placeholder="" readonly class="form-control " name="balamt" type="text"  id="balamt" value="<?php if(isset($_GET['invno'])){echo round($row->balamt); } ?>">
            </td>
          <td>CRN Amount</td>
            <td>
                <input placeholder="" class="form-control " name="crn_bal" type="text"  id="crn_bal" value="<?php if(isset($_GET['invno'])){ } ?>">
            </td>
          </tr>
          <tr>

          <td>Deduction Type</td>
          <td>
          <select class="form-control" name="remarks">
                    <option value="">Select Type</option>
                    <option value="BAD">Bad Debts</option>
                    <option value="CAS">Cash Discount</option>
                    <option value="CLA">Claim</option>
                    <option value="CRE">Credit Note</option>
                    <option value="DEL">Delivery Charge</option>
                    <option value="IAT">Invoice Adjustment</option>
                    <option value="DEL">POD Deduction</option>
                    <option value="LAT">Late Delivery Claim</option>
                    <option value="OTC">OTC Charge</option>
                    <option value="OTH">Other Charges</option>
                    <option value="SEC">Security</option>
                    <option value="SEC">Wrong Bill</option>
                    </select>    
          </td>
          <td>Deduction Amount</td>
          <td>
              <input placeholder="" class="form-control " name="reduction" type="text"  id="reduction" min="0" onfocusout="this.value =!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" >
          </td>
          <td>Deduction Detail</td>
          <td>
              <input placeholder="" class="form-control " name="narration" type="text"  id="narration" >
          </td>
 
     
            </tr>
            </tbody>
            </table> -->

           
            
            <!-- </div> -->

            
          </div> 
          <div class="col-sm-12 data-table-div" >



<!-- <div class="title_1 title panel-heading">
<p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice Review</p>
<div class="align-right" style="margin-top:-33px;" id="buttons"></div>
</div>
<table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
<thead><tr class="form_cat_head">
<th class="center">
<label class="pos-rel">
   <input type="radio" class="ace" />
<span class="lbl"></span>
</label>
</th>
<th>Invoice No</th>
<th>Invoice Date</th>
<th>Branch Code</th>
<th>Customer Code</th>
<th>Grand Amt</th>     
<th>FSC</th>
<th>Invoice Amount</th>
<th>GST</th>
<th>Net Amt</th>
<th>Status</th>            
</tr>
</thead>
<tbody id="collectioninvtb">
 <?php 
            if(isset($_GET['invno'])){

            $getpendinginv = $db->query("SELECT * FROM invoice WHERE cust_code='$row->cust_code' AND status='Pending'");
            
            while($rowinv = $getpendinginv->fetch(PDO::FETCH_OBJ)){
            echo "<tr>";
            echo "<td class='center first-child'>
                    <label>
                        <input type='radio' name='chkIds[]' value='$rowinv->invno' class='ace invoiceradio'/>
                        <span class='lbl'></span>
                        </label>
                    </td>";
            
                    echo "<td>".$rowinv->invno."</td>";
                    echo "<td>".$rowinv->invdate."</td>";     
                    echo "<td>".$rowinv->br_code."</td>";
                    echo "<td>".$rowinv->cust_code."</td>";
                    echo "<td>".round($rowinv->grandamt)."</td>";
                    echo "<td>".round($rowinv->fsc)."</td>";
                    echo "<td>".round($rowinv->invamt)."</td>";
                    echo "<td>".round($rowinv->STax)."</td>";
                    echo "<td>".round($rowinv->netamt)."</td>";
                    echo "<td>".$rowinv->status."</td>";
            echo "</tr>";
            }
        }
     ?>  
</tbody>
</table>
</div> -->
          <div class="col-sm-12 data-table-div">
<div class="title_1 title panel-heading">
<p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Collection Review</p>
<div class="align-right" style="margin-top:-33px;" id="buttons">
</div>
</div>
                    <table id="collectiontbl" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
                            <thead><tr class="form_cat_head">
                            <th class="center">
                            <label class="pos-rel">
                            <input type="checkbox" class="ace"/>
                            <span class="lbl"></span>
                            </label>
                                        </th>
                                        <th>Collection No</th>
                                        <th>Collection Date</th>
                                        <!-- <th>Invoice No</th>
                                        <th>Invoice Date</th> -->
                                        <!-- <th>Collection Center</th>               -->
                                        <!-- <th>Net Amount</th> -->
                                        <th>₹ 2000</th>  
                                        <th>₹ 500</th>
                                        <th>₹ 200</th>
                                        <th>₹ 100</th>
                                        <th>₹ 50</th>
                                        <th>₹ 20</th>
                                        <th>₹ 10</th>  
                                        <th>₹ 5</th>  
                                        <th>Coins</th>
                                        <th>Collected Amt</th>
                    <!-- <th>TDS</th>
                    <th>Deduction </th>
                    <th>Net Collected</th>
                    <th>InvNet Balance</th>
                    <th>Status</th>             -->
                    </tr>
</thead>

</table>
</div>

  </form>
  </div>
  </div>
</div>
</div>
</section>

<!-- inline scripts related to this page -->



 <script> 
$(window).on('load', function () {
   var usr_name = $('#user_name').val();
        
        $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{usr_name:usr_name},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#stncode').val(data[0]);
            $('#stnname').val(data[1]);
            $('#origin').val(data[0]);

            getdenominationamount();

            }
    });
  });
  </script>

 <script>
  var branchcode =[<?php    
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

  ?>];
var branchname =[<?php  
$brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode'");
while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrname['br_name']."',";
}
 ?>];
 </script>
 <script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);
autocomplete(document.getElementById("cust_code"), customercode);
 </script>
 

<script>
//branches array
var branches = {
    <?php
    $branches = $db->query("SELECT trim(br_code) as br_code,trim(br_name) as br_name   FROM branch WHERE stncode='$stationcode'");
    while($rowbranches=$branches->fetch(PDO::FETCH_OBJ))
    {
    echo $rowbranches->br_code.":'".$rowbranches->br_name."',";
    }
    ?>
}
</script>
<script>
    function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
</script>
<script>
    
$(document).ready(function(){
     
     $('#br_code').focusout(function(){
         var br_code = $('#br_code').val().toUpperCase().trim();
         if(branches[br_code]!==undefined){
             $('#brcode_validation').css('display','none');
             $('#br_name').val(branches[br_code]);
             getdenominationamount();
         }
         else{
             $('#brcode_validation').css('display','block');
         }
     });
     //fetch code of entering name
     $('#br_name').focusout(function(){
         var br_name = $(this).val().trim();
         var gccode = getKeyByValue(branches,br_name);
         
         if(gccode!==undefined){
             $('#brcode_validation').css('display','none');
             $('#br_code').val(gccode);
             getdenominationamount();
         }
         else{
             $('#brcode_validation').css('display','block');
         }
        
     });
     });
     </script>




 <script>
function resetFunction() {   
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('coll_code').value = "";
    document.getElementById('coll_name').value = "";
    document.getElementById('coldate').value = "";  
    document.getElementById('colamt').value = "";
    document.getElementById('m2000').value = "";
    document.getElementById('m500').value = "";
    document.getElementById('m200').value = "";
    document.getElementById('m100').value = "";
    document.getElementById('m50').value = "";
    document.getElementById('m20').value = "";
    document.getElementById('m10').value = "";
    document.getElementById('coins').value = "";
    document.getElementById('rmks').value = "";
    }
</script> 
 <script>
    
function getdenominationamount(){
    var den_stn = $('#stncode').val();
    var den_br = $('#br_code').val();
    var den_bdate = $('#coldate').val();
    if(den_br.length>0 && den_bdate.length>0)
    {
    $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{den_stn,den_br,den_bdate},
            dataType:"text",
            success:function(data)
            {
                var array = JSON.parse(data);
                console.log(array);
                $('#chq').val(array['cheque'][0]);
                $('#chqamt').val(array['cheque'][1]);
                $('#upi').val(array['upi'][0]);
                $('#upiamt').val(array['upi'][1]);
                $('#cneft').val(array['neft'][0]);
                $('#neftamt').val(array['neft'][1]);
                $('#totalamt').val(array['totalcash'][0]);
                var sumcolamt = parseFloat(array['cheque'][1])+parseFloat(array['upi'][1])+parseFloat(array['neft'][1]);
                $('#colamt').val(isNaN(sumcolamt)?0:sumcolamt);
            }
    });

}   
}

</script>
<script>

const numInputs = document.querySelectorAll('input[type=number]')

numInputs.forEach(function(input) {
  input.addEventListener('input', function(e) {
    if (e.target.value == '') {
      e.target.value = 0
    }
  })
})

</script>