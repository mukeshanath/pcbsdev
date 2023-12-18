<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';
if(isset($_GET['denid']))
    {
        $showden = $_GET['denid'];
        $showdenomination = $db->query("SELECT * FROM denomination WHERE  den_id='$showden' AND status NOT LIKE '%Void'")->fetch(PDO::FETCH_OBJ);
       // $holdinvoicedata = $db->query("SELECT * FROM invoice WHERE comp_code='$stationcode' AND cust_code='$holdcollection->cust_code' AND invno='$holdcollection->invno'")->fetch(PDO::FETCH_OBJ);
    }

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $module = 'denominationedit';
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

   //Total cheque and entry count of cheque
   function gettotalcheque($db,$stationcode){
    $data = $db->query("SELECT sum(amt) as totalchq,count(amt) as datacount FROM cash WHERE payment_mode='Cheque' AND comp_code='$stationcode' AND cast(date_added as date)=cast(getdate() as date)")->fetch(PDO::FETCH_OBJ);
    return array($data->datacount,$data->totalchq);
}
$gettotalcheque = gettotalcheque($db,$stationcode);
//Total cheque and entry count of cheque

//Total upi and entry count of upi
function gettotalupi($db,$stationcode){
    $data = $db->query("SELECT sum(amt) as totalchq,count(amt) as datacount FROM cash WHERE payment_mode='UPI' AND comp_code='$stationcode' AND cast(date_added as date)=cast(getdate() as date)")->fetch(PDO::FETCH_OBJ);
    return array($data->datacount,$data->totalchq);
}
$gettotalupi = gettotalupi($db,$stationcode);
//Total upi and entry count of upi

//Total neft and entry count of neft
function gettotalneft($db,$stationcode){
    $data = $db->query("SELECT sum(amt) as totalchq,count(amt) as datacount FROM cash WHERE payment_mode='NEFT' AND comp_code='$stationcode' AND cast(date_added as date)=cast(getdate() as date)")->fetch(PDO::FETCH_OBJ);
    return array($data->datacount,$data->totalchq);
}
$gettotalneft = gettotalneft($db,$stationcode);
//Total neft and entry count of neft

//Total cash and entry count of cash
function getsumoftotalentryamt($db,$stationcode){
    $data = $db->query("SELECT sum(amt) as totalcash FROM cash WHERE comp_code='$stationcode' AND cast(date_added as date)=cast(getdate() as date)")->fetch(PDO::FETCH_OBJ);
    return array($data->totalcash);
}
$getsumoftotalentryamt = getsumoftotalentryamt($db,$stationcode);
//Total cash and entry count of cash
$collectedamount = $gettotalcheque[1]+$gettotalupi[1]+$gettotalneft[1];
  
// function loadmode($db)
//     {
//         $output='';
//         $query = $db->query('SELECT * FROM mode ORDER BY mid ASC');
       
//          while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
//         //menu list in home page using this code    
//         $output .='<option  value="'.$row->mode_name.'">'.$row->mode_name.'</option>';
//          }
//          return $output;    
        
//     }
//     $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
//     $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
//     $rowuser = $datafetch['last_compcode'];
//     if(!empty($rowuser)){
//          $stationcode = $rowuser;
//     }
//     else{
//         $userstations = $datafetch['stncodes'];
//         $stations = explode(',',$userstations);
//         $stationcode = $stations[0];     
//     } 

//     function colmasno($db,$stationcode)
//     {
    
//     $prevcolmasno=$db->query("SELECT TOP 1 col_mas_rcptno FROM collection_master WHERE comp_code='$stationcode' ORDER BY col_mas_id DESC");
//     if($prevcolmasno->rowCount()== 0)
//         {
//         $colomasno = $stationcode.str_pad('1', 7, "0", STR_PAD_LEFT);
//         }
//     else{
//         $prevcolmasrow=$prevcolmasno->fetch(PDO::FETCH_ASSOC);
//         $arr = preg_split('/(?<=[A-Z])(?=[0-9]+)/i',$prevcolmasrow['col_mas_rcptno']);
//         $arr[0];//PO
//         $arr[1]+1;//numbers
//         $colomasno = $arr[0].str_pad(($arr[1]+1), 7, "0", STR_PAD_LEFT);
//         }
//      return $colomasno;    
        
//     }

//     function collectionno($db,$stationcode)
//     {
//         $prevcolno=$db->query("SELECT TOP 1 colno FROM collection WHERE comp_code='$stationcode' ORDER BY col_id DESC");
//          if($prevcolno->rowCount()== 0)
//          {
//             $colono = 10001;
//             }
//         else{
//             $prevcolrow=$prevcolno->fetch(PDO::FETCH_ASSOC);
//             $colono = $prevcolrow['colno']+1;
//             }
//         return $colono;        
//     }

//     if(isset($_GET['invno']))
//       {
//         $invoicenumber = $_GET['invno'];


//         $collection = $db->query("SELECT TOP(1) * FROM collection WHERE invno='$invoicenumber' AND comp_code='$stationcode' AND status NOT LIKE '%Void'  ORDER BY coldate DESC,col_id DESC");
//         $row = $collection->fetch(PDO::FETCH_OBJ);

//         $invoicedata = $db->query("SELECT * FROM invoice WHERE comp_code='$stationcode' AND cust_code='$row->cust_code' AND invno='$invoicenumber'")->fetch(PDO::FETCH_OBJ);


//         $getrunbal = $db->query("SELECT TOP(1) totamt,running_balance FROM collection_master WHERE cust_code='$row->cust_code' AND comp_code='$stationcode' AND status NOT LIKE '%Void'  ORDER BY col_mas_id DESC");
//         $rowrunbal = $getrunbal->fetch(PDO::FETCH_OBJ);

//         $getecptpayref = $db->query("SELECT TOP(1) rcptno,chqddno,chqbank,depbank,chdt FROM collection_master WHERE invno='$invoicenumber' AND comp_code='$stationcode' AND status NOT LIKE '%Void'  ORDER BY col_mas_id DESC")->fetch(PDO::FETCH_OBJ);

//       }
    
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
                      if ( isset($_GET['success']) && $_GET['success'] == 1 )
                  {
                       echo 
                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Collection Record Added successfully.
                  </div>";
                  }

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 1  )
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
      <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>denominationedit" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">

        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
        <input placeholder="" class="form-control border-form" name="showdenid" type="text" id="showdenid" value="<?php echo $showden ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Denomination Entry Edit Form</p>
        <div class="align-right" style="margin-top:-35px;">

        <a style="color:white" href="<?php echo __ROOT__ ?>denominationlist"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>
          </div>
        </div>         
        <div class="content panel-body" >
        <div class="tab_form" style="background:#fff;border: 1px solid var(--heading-primary);">
        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        
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
                <input placeholder="" class="form-control br_code" name="br_code"  type="text"  id="br_code" value="<?=$showdenomination->br_code?>" onkeyup="fetchcustcode();branchvalidation()">
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form "  name="br_name"  type="text" id="br_name" value="<?=$showdenomination->br_code ?>">
            </div></td>
            <td>Collection Date</td>
            <td >
                <input placeholder="" class="form-control today" name="coldate" type="date" value="<?=$showdenomination->col_date?>"  id="coldate">
            </td>
            <!-- <td>Collection Center</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" class="form-control cust_code"  name="coll_code" type="text"  id="coll_code" value="<?=$showdenomination->coll_center?>" onkeyup="customervalidation()">
                <p id="custcode_validation" class="form_valid_strings">Enter Valid Collection center Code</p>
                </div>
            </td> -->
            <!-- <td><div class="autocomplete col-sm-12">
                <input class="form-control border-form"  name="cust_name" type="text" id="cust_name" value="<?php if(isset($_GET['invno'])){echo $row->cust_name; } ?>" >
            </div></td>             -->
        </tr>                        
        </tbody>
        </table>
        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>              
            <td>₹2000&nbsp;<b>X</b></td>
            <td>
            <input placeholder="" class="lc2k" name="m2k" type="number" min="0" oninput="viewamt2k();viewamt()"  id="m2k" value="<?=round($showdenomination->c2000)?>">
        </td>
            <td>
                <input placeholder="" readonly class="form-control2k" name="m2000" type="text"  id="m2000"  value="<?=round($showdenomination->m2000)?>">
          </td>

          <td>₹500&nbsp;<b>X</b></td>
          <td> 
            <input placeholder="" class="lc500" name="mo500" type="number" min="0"  id="mo500" oninput="viewamt500();viewamt()" value="<?=round($showdenomination->c500)?>">
        </td>
            <td>
                <input placeholder="" readonly class="form-control" name="m500" type="text"  id="m500" value="<?=round($showdenomination->m500)?>">
          </td>
          <td>₹200&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc200" name="mo200" type="number" min="0"  id="mo200" oninput="viewamt200();viewamt()" value="<?=round($showdenomination->c200)?>">
        </td>
            <td>
                <input placeholder="" readonly class="form-control200" name="m200" type="text"  id="m200" value="<?=round($showdenomination->m200)?>">
          </td>
          </tr>
<tr>
          <td>₹100&nbsp;<b>X</b></td>
          <td> 
            <input placeholder="" class="lc100" name="mo100" type="number" min="0" oninput="viewamt100();viewamt()"   id="mo100" value="<?=round($showdenomination->c100)?>">
        </td>
            <td>
                <input placeholder="" readonly class="form-control100" name="m100" type="text"  id="m100" value="<?=round($showdenomination->m100)?>">
          </td>                               
          <td>₹50&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc50" name="mo50" type="number" min="0"  id="mo50" oninput="viewamt50();viewamt()"  value="<?=round($showdenomination->c50)?>">
        </td>
            <td>
                <input placeholder="" readonly class="form-control" name="m50" type="text"  id="m50" value="<?=round($showdenomination->m50)?>">
          </td>

          <td>₹20&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc20" name="mo20" type="number" min="0"  id="mo20"  oninput="viewamt20();viewamt()"  value="<?=round($showdenomination->c20)?>">
        </td>
            <td>
                <input placeholder="" readonly class="form-control20" name="m20" type="text"  id="m20" value="<?=round($showdenomination->m20)?>">
          </td>
          </tr>
<tr>
          <td>₹10&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc10" name="mo10" type="number" min="0" oninput="viewamt10();viewamt()"  id="mo10" value="<?=round($showdenomination->c10)?>">
        </td>
            <td>
                <input placeholder="" readonly class="form-control10" name="m10" type="text"  id="m10" value="<?=round($showdenomination->m10)?>">
          </td>

          <td>₹5&nbsp;<b>X</b></td>
          <td>
            <input placeholder="" class="lc5" name="mo5" type="number" min="0" oninput="viewamt5();viewamt()"  id="mo5" value="<?=round($showdenomination->c5)?>">
        </td>
            <td>
                <input placeholder="" readonly class="form-control" readonly name="m5" type="text"  id="m5" value="<?=round($showdenomination->m5)?>">
          </td>
          <td>Coins Value</td>
            <td>
                <input placeholder="" class="form-control" name="coins" type="number" min="0" oninput="viewamt()"  id="coins" value="<?=round($showdenomination->coins)?>">
          </td>
          </tr>
          <tr>
          <td>Cheque</b></td>
          <td>
            <input placeholder="" class="lc10" name="chq" type="number" readonly id="chq" value="<?=$gettotalcheque[0];?>"></td>
            <td>
                <input placeholder="" class="form-control10" readonly name="chqamt" oninput="viewamt()" type="number"  id="chqamt" value="<?=number_format($gettotalcheque[1],2);?>">
            </td>
          <td>UPI</b></td>
          <td>
            <input placeholder="" class="lc5" name="upi" type="number" readonly id="upi" value="<?=$gettotalupi[0];?>"></td>
            <td>
                <input placeholder="" class="form-control"  name="upiamt" oninput="viewamt()" type="number"  id="upiamt" readonly value="<?=number_format($gettotalupi[1],2);?>">
          </td>
          <td>NEFT</td>
          <td>
            <input placeholder="" name="cneft" type="number" readonly id="cneft" value="<?=$gettotalneft[0];?>"></td>
            <td>
                <input placeholder="" class="form-control"  name="neftamt" oninput="viewamt()" type="number" id="neftamt" readonly value="<?=number_format($gettotalneft[1],2);?>">
          </td>
                      </tr> 
 
            <tr>
            <td  style="width:100px;">Total Amount</td>
            <td>
                <input placeholder="" class="form-control" style="width:160px;" readonly name="totalamt" type="text"  id="totalamt" value="<?=number_format($getsumoftotalentryamt[0],2)?>">
            </td> 
          <td  style="width:100px;">Collected Amount</td>
            <td>
                <input placeholder="" class="form-control" readonly style="width:160px;" name="colamt" type="text"  id="colamt" value="<?=round($showdenomination->collamt)?>">
            </td>
          <td>Remarks</td>
            <td>
                <input placeholder="" style="width:160px;"  class="form-control" name="rmks" type="text"  id="rmks" value="<?=$showdenomination->remarks?>">
          </td>
          </tr>                                      
                </tbody>
        </table>
        <table class="table">
            <tr>	           
              <div class="align-right btnstyle">
                  <!-- <button type="button" class="btn btn-success btnattr_cmn" onclick="addancollection1()" id="addancollection">
                  <i class="fa fa-save"></i>&nbsp; Save & Add
                  </button> -->

                  <!-- <button type="button" class="btn btn-primary btnattr_cmn" onclick="viewamt()" id="viewamnt" >
                  <i class="fa fa-eye"></i>&nbsp; View Amount
                  </button> -->

                  <button type="button" class="btn btn-primary btnattr_cmn" onclick="updatedeno()" id="updatedenom" name="updatedenom" >
                  <i class="fa fa-save"></i>&nbsp; Update
                  </button>

                  <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                  <i class="fa fa-eraser"></i>&nbsp; Reset
                  </button> 
                  </div>
              </tr>
          </table>
        </div>

         

            <div class="title_1 title panel-heading">
            <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Denomination Review</p>
            <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
            </div>
            <table id="collectiontbl" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
            <thead><tr class="form_cat_head">
            <th class="center">
            <label class="pos-rel">
            <input type="checkbox" class="ace" />
            <span class="lbl"></span>
            </label>
            </th>
            <th>Denomination No</th>
            <th>Denomination Date</th>
            <!-- <th>Collection Center</th>             -->
            <th>₹ 2000</th>  
            <th>₹ 500</th>
            <th>₹ 200</th>
            <th>₹ 100</th>
            <th>₹ 50</th>
            <th>₹ 20</th>
            <th>₹ 10</th>   
            <th>Coins</th>
            <th>Collected Amt</th>          
</tr>
</thead>
<tbody id="collectiontb">
 <?php 
            if(isset($_GET['denno'])){

            $getcollslist = $db->query("SELECT * FROM denomination WHERE cust_code='$row->cust_code' AND invno='".$_GET['invno']."'");
            while($collsrow = $getcollslist->fetch(PDO::FETCH_OBJ)){
            echo "<tr>";
            echo "<td class='center first-child'>
                    <label>
                        <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                        <span class='lbl'></span>
                        </label>
                    </td>";
            
                    echo "<td>".$collsrow->colno."</td>";
                    echo "<td>".$collsrow->coldate."</td>";
                    echo "<td>".$collsrow->invno."</td>";
                    echo "<td>".$collsrow->invdate."</td>";     
                    echo "<td>".$collsrow->cust_code."</td>";
                    echo "<td>".round($collsrow->invamt)."</td>";
                    echo "<td>".round($collsrow->colamt)."</td>";
                    echo "<td>".round($collsrow->tds)."</td>";
                    echo "<td>".round($collsrow->reduction)."</td>";
                    echo "<td>".round($collsrow->netcollamt)."</td>";
                    echo "<td>".round($collsrow->balamt)."</td>";
                    echo "<td>".$collsrow->status."</td>";
            echo "</tr>";
            }
        }
            if(isset($_GET['colno'])){

            $getcollslist = $db->query("SELECT * FROM collection WHERE comp_code='$stationcode' AND cust_code='$holdcollection->cust_code' AND invno='$holdcollection->invno'");
            while($collsrow = $getcollslist->fetch(PDO::FETCH_OBJ)){
            echo "<tr>";
            echo "<td class='center first-child'>
                    <label>
                        <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                        <span class='lbl'></span>
                        </label>
                    </td>";
            
                    echo "<td>".$collsrow->colno."</td>";
                    echo "<td>".$collsrow->coldate."</td>";
                    echo "<td>".$collsrow->invno."</td>";
                    echo "<td>".$collsrow->invdate."</td>";     
                    echo "<td>".$collsrow->cust_code."</td>";
                    echo "<td>".round($collsrow->invamt)."</td>";
                    echo "<td>".round($collsrow->colamt)."</td>";
                    echo "<td>".round($collsrow->tds)."</td>";
                    echo "<td>".round($collsrow->reduction)."</td>";
                    echo "<td>".round($collsrow->netcollamt)."</td>";
                    echo "<td>".round($collsrow->balamt)."</td>";
                    echo "<td>".$collsrow->status."</td>";
            echo "</tr>";
            }
        }
     ?>

  
</tbody>
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
function updatedeno()
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
            swal("Make Sure all fields has Valid Count or Enter 0");
        }
        else if(parseFloat(<?=$getsumoftotalentryamt[0]?>)!=parseFloat($('#colamt').val())){
            swal("Total amount and Collected amount must be same");
        }
         else
         {
            $('#updatedenom').attr('type','submit');
            $('#updatedenom').attr('name','updatedenom');
         }
}

function viewamt()
{
    var value = parseInt($('#m2k').val());
    $('#m2000').val(parseInt(value*2000));   
    var val = parseInt($('#m2000').val());         
 }

 function viewamt500()
 {
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
</script>


<script>
//Fetch Branch Name
$('.br_code').keyup(function(){
        var branchcodes = $('.br_code').val();
        var stationscode = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{branchcodes:branchcodes,stationscode:stationscode},
            dataType:"text",
            success:function(data3)
            {
                // document.write(data3);
            $('#br_name').val(data3);
            }
    });
    });
</script>
<script>

    $(document).ready(function(){
    $('#cust_code').keyup(function(){
        var ccode = $('#cust_code').val();
        var brcodes = $('#br_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{ccode:ccode,brcodes:brcodes},
            dataType:"text",
            success:function(data)
            {
            $('#cust_name').val(data);
            }
        });
    });
    });
  </script>
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
    //Fetch Branch code
    $('#br_name').keyup(function(){
            var ratebrname = $(this).val();
            var ratestnname = $('#stncode').val();
            $.ajax({
                url:"<?php echo __ROOT__ ?>collectioncontroller",
                method:"POST",
                data:{s_bname:ratebrname,s_comp:ratestnname},
                dataType:"text",
                success:function(data)
                {
                $('#br_code').val(data);
                fetchcustcode();
                branchvalidation();
                }
            });
        });
</script>
<script>
            //fetch code of entering name
            $('#cust_name').keyup(function(){
            var cccust_name = $(this).val();
            var cc_namestn = $('#stncode').val();
            var cc_namebr = $('#br_code').val();
            $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{cccust_name:cccust_name,cc_namestn:cc_namestn,cc_namebr:cc_namebr},
            dataType:"text",
            success:function(cust_code)
            {
                $('#cust_code').val(cust_code);
                customervalidation();
            }
            });
            });
</script>
<script>
function fetchcustcode(){
    var branch8 = $('#br_code').val();
    var comp8 = $('#stncode').val();
       $.ajax({
       url:"<?php echo __ROOT__ ?>collectioncontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(ratecustcode)
       {
           autocomplete(document.getElementById("cust_code"), ratecustcode); 
       }
       });
       $.ajax({
       url:"<?php echo __ROOT__ ?>collectioncontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(ratecustname)
       {
           autocomplete(document.getElementById("cust_name"), ratecustname); 
       }
       });
}
</script>

<script>
  //Verifying Branch code is Valid
  function branchvalidation(){
        var branch_verify = $('.br_code').val();
        var stationcode = $('.stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{branch_verify:branch_verify,stationcode:stationcode},
            dataType:"text",
            success:function(branchcount)
            {
            if(branchcount == 0)
            {
                $('#brcode_validation').css('display','block');
            }
            else{
                $('#brcode_validation').css('display','none');
            }
            }
    });
    }
</script>
<script>
//check invoice is Valid
    function validateinvoice(){
        var valid_invno = $('#invno').val();
        var valid_inv_cstn = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{valid_invno,valid_inv_cstn},
            dataType:"text",
            success:function(res)
            {
                if(res==0){
                swal('Entered Invoice is not Belongs to this Station');
                }
            }
        });
    }
</script>
<?php
    if(!isset($holdcoln)){
?>
<script>
    $('#invno').keyup(function(){
        var invno = $(this).val();
        var customer = $('#cust_code').val();
        var inv_cstn = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{invno:invno,inv_cstn},
            dataType:"text",
            success:function(invdata)
            {
                var data = invdata.split("|");
               
                if(data[16]=='Collected'){
                    swal("Entered Invoice number is already Collected");
                    $('#invno').val('');
                }
                else{
                    $('#rcptno').val(data[13]);
                    $('#chqddno').val(data[14]);
                    $('#chqbank').val(data[17]);
                    $('#depbank').val(data[18]);
                    $('#sheetno').val(data[19]);
                    if($('#totamt').val().trim() == ''){
                    //$('#totamt').val(data[6]);
                    //$('#run_bal').val(data[7]);
                    //$('#colamt').val(data[6]);  
                    }
                    if($('#rcptno').val()==''){
                        //$('#rcptno').val(data[13]);
                    }
                    if($('#chqddno').val()==''){
                       // $('#chqddno').val(data[14]);
                    }
                    if(parseInt($('#balamt').val()) > parseInt($('#run_bal').val())){
                        $('#colamt').val($('#run_bal').val());  
                    }
                    else{
                        $('#colamt').val(data[5]);  
                    }

                    $('#br_code').val(data[0]);
                    $('#br_name').val(data[1]);
                    $('#cust_code').val(data[2]);
                    $('#cust_name').val(data[3]);
                    $('#invamt').val(data[4]);
                    $('#balamt').val(data[5]);
                    
                    $('#invdate').val(data[9]);
                    $('#coldate').val('<?php echo date('Y-m-d'); ?>');
                    $('#coldate').attr('min',data[9]);
                    $('#crn_no').val(data[10]);
                    $('#crn_bal').val(data[11]); 

                    if($('#totamt').val()=='0'){
                    //$('#rcptno').val('');
                    //$('#chqddno').val('');
                     }

                }    
            }

            });
            $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{invoicetb:invno,coll_rev_stn:inv_cstn},
            dataType:"text",
            success:function(collectiontb)
            {
               $('#collectiontb').html(collectiontb);
            }
            });
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{colinvrev:invno,colinvclist:inv_cstn},
            dataType:"text",
            success:function(collectioninvtb)
            {
               $('#collectioninvtb').html(collectioninvtb);
            }
            });
    });
</script>
<?php
    }
?>
<script>
 $(document).on('click', '.invoiceradio', function(){ 
        var invno = $(this).val();
        var inv_cstn = $('#stncode').val();
        // alert(invno);
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{invno:invno,inv_cstn},
            dataType:"text",
            success:function(invoicedata)
            {
                var data = invoicedata.split("|");
                    $('#rcptno').val(data[13]);
                    $('#chqddno').val(data[14]);
                    $('#chqbank').val(data[17]);
                    $('#depbank').val(data[18]);
                    $('#sheetno').val(data[19]);
                if($('#totamt').val().trim() == ''){
                    //$('#totamt').val(data[6]);
                    //$('#run_bal').val(data[7]);
                    //$('#colamt').val(data[6]);  
                    }
                if($('#rcptno').val()==''){
                   // $('#rcptno').val(data[13]);
                }
                if($('#chqddno').val()==''){
                    //$('#chqddno').val(data[14]);
                }
                if(parseInt($('#balamt').val()) > parseInt($('#totamt').val())){
                    $('#colamt').val($('#totamt').val());  
                }
                else{
                    $('#colamt').val(data[5]);  
                }
                $('#br_code').val(data[0]);
                $('#br_name').val(data[1]);
                $('#cust_code').val(data[2]);
                $('#cust_name').val(data[3]);
                $('#cust_name').val(data[3]);
                $('#invamt').val(data[4]);
                $('#balamt').val(data[5]);
                // $('#colamt').val(data[5]);
                // $('#totamt').val(data[6]);
                // $('#run_bal').val(data[7]);
                $('#invno').val(data[8]);
                $('#invdate').val(data[9]);
                $('#coldate').val('<?php echo date('Y-m-d'); ?>');
                $('#coldate').attr('min',data[9]);
                // $('#rcptno').val(data[13]);
                // $('#chqddno').val(data[14]);
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
    
     var table = $('#collectiontbl').DataTable();
 
        // var buttons = new $.fn.dataTable.Buttons(table, {
        //     buttons: [
        //       'excelHtml5',
        //       'pdfHtml5',
        //     ]
        // }).container().appendTo($('#buttons'));

     </script>