<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'tscharge';
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
    $user2 = $_SESSION['user_name'];
    
    function colmasno($db,$user2)
    {
        //error_reporting(0);
        //$cnotepono='';
        $stncode = $db->query("SELECT stncodes,group_name FROM users WHERE user_name='$user2'");
    $station = $stncode->fetch(PDO::FETCH_ASSOC);
    $station_code = $station['stncodes'];
    if(!empty($station_code))
    {
        $stncodesrow = $station['stncodes'];
    }
    else{
        $stncodesrow = $station['group_name'];
    } 
    $prevcolmasno=$db->query("SELECT TOP 1 col_mas_rcptno FROM collection_master WHERE comp_code='$stncodesrow' ORDER BY col_mas_id DESC");
    if($prevcolmasno->rowCount()== 0)
        {
        $colomasno = $stncodesrow.str_pad('1', 7, "0", STR_PAD_LEFT);
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
?>
<style>
  
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
                                            <li class="list-inline-item">Crossing Charge</li>
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
                                  Please Check form Errors.
                  </div>";
                  }

               ?>

             </div>
      <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>collection" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Note Form</p>
        <div class="align-right" style="margin-top:-35px;">

        <a style="color:white" href="<?php echo __ROOT__ ?>collectionlist"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>

          </div>
        </div> 
        
        <div class="content panel-body" >
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
                <input placeholder="" class="form-control br_code" name="br_code" type="text"  id="br_code" >
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td>
                <input class="form-control border-form "  name="br_name" type="text" id="br_name" value="">
            </td>
            <td>Customer</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input placeholder="" class="form-control cust_code" name="cust_code" type="text"  id="cust_code">
                <p id="custcode_validation" class="form_valid_strings">Enter Valid Customer Code</p>
                </div>
            </td>
            <td>
                <input class="form-control border-form "  name="cust_name" type="text" id="cust_name" value="" >
            </td>
            
        </tr>
                      
        </tbody>
        </table>
        <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>
  
                        
            <td>Credit Note No</td>
            <td>
                <input placeholder="" class="form-control " name="totamt" type="text"  id="totamt" >
            </td>
            <td>Credit Note Date</td>
            <td>
                <input placeholder="" class="form-control " name="totamt" type="date"  id="totamt" >
            </td>
            
            
        </tr>
        <tr>
  
                        
        <td>Invoice No</td>
        <td>
            <input placeholder="" class="form-control " name="totamt" type="text"  id="totamt" >
        </td>
        <td>Invoice Amount</td>
        <td>
            <input placeholder="" class="form-control " name="totamt" type="date"  id="totamt" >
        </td>
        
        
        </tr>
        <tr>
        
                                
        <td>Invoice Date</td>
        <td>
            <input placeholder="" class="form-control " name="totamt" type="text"  id="totamt" >
        </td>
        <td>Balance</td>
        <td>
            <input placeholder="" class="form-control " name="totamt" type="text"  id="totamt" >
        </td>
        
        
        </tr>
        
       
                      
        </tbody>
        </table>
        </div>

        <!-- collection entry 2 form -->
        
            <div  class="col-sm-12" style="border :1px solid var(--heading-primary) ;background:#fff;margin-top:5px">
          
            <table id="formPOSpurchase" class="form-table table customer_rate_table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            
        <tr>
        
                                
        <td style="width: 19.7%;">Credit Note Amount</td>
        <td style="width: 29.3%;">
            <input  class="form-control " name="totamt" type="text"  id="totamt" />
        </td>
      
        <td >Remarks</td>
          <td>
          <select class="form-control" name="remarks">
                    <option value="BAD">Bad Debts</option>
                    <option value="CAS">Cash Discount</option>
                    <option value="CLA">Claim</option>
                    <option value="CRE">Credit Note</option>
                    <option value="DEL">Delivery Charge</option>
                    <option value="LAT">Late Delivery Claim</option>
                    <option value="OTC">OTC Charge</option>
                    <option value="OTH">Other Charges</option>
                    <option value="SEC">Security</option>
                    
                    </select>    
          </td>
        
        
        </tr>
            </tbody>
            </table>
            <table class="table">
              <tr>	           
              <div class="align-right btnstyle" >
                  <button type="submit" class="btn btn-primary btnattr_cmn" name="addcollection">
                  <i class="fa fa-save"></i>&nbsp; Save
                  </button>

                  <button type="submit" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                  <i class="fa fa-eraser"></i>&nbsp; Reset
                  </button> 

                  </div>

              </tr>

          </table>
            
            </div>

            <!-- collection entry 3 form-->
           
          </div> 
         

  </form>
  </div>
  </div>
</div>
</div>
</section>

<!-- inline scripts related to this page -->
<script>
function resetFunction() {
  document.getElementById("formcustomerrate").reset();
}
</script>
<script>
function totalamt(){
    var colamt    = $('#colamt').val();
    var tds       = $('#tds').val();
    var reduction = $('#reduction').val();
    var wrongbill = $('#wrongbill').val();
    if(tds==""){var tds = 0;}
    if(reduction==""){var reduction = 0;}
    if(wrongbill==""){var wrongbill = 0;}
    $('#totamt').val(parseInt(colamt)+parseInt(tds)+parseInt(reduction)+parseInt(wrongbill));
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
            if($('#stncode').val() == '')
            {
                var superadmin_stn = $('#stncode1').val();
                $.ajax({
                url:"<?php echo __ROOT__ ?>peoplecontroller",
                method:"POST",
                data:{superadmin_stn:superadmin_stn},
                dataType:"text",
                success:function(value)
                {
                    var data = value.split(",");
                    $('#stncode').val(data[0]);
                    $('#stnname').val(data[1]);
		    
                }
                });
            }
            }
    });
  });
 </script>
<script>
  var branchcode =[<?php  
    $user_name2 = $_SESSION['user_name'];
    $getstncodes = $db->query("SELECT stncodes,group_name FROM users WHERE user_name='$user_name2'");
    $resultstncodes = $getstncodes->fetch(PDO::FETCH_ASSOC);
    if(!empty($resultstncodes['stncodes']))
    {
        $stncodesrow = $resultstncodes['stncodes'];
    }
    else{
        $stncodesrow = $resultstncodes['group_name'];
    }    
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stncodesrow'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];
</script>

<script>
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("cust_code"), customercode);

</script>
<script>
$('.br_code').keyup(function(){
   var branch8 = $('.br_code').val();
   var comp8 = $('.stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>cnotecontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("cust_code"), cust8); 
       }
});
});
</script>
<script>
//Verifying Customer code is Valid
$('.cust_code').keyup(function(){
        var cust_verify_br = $('.br_code').val();
        var cust_verify_cust = $('.cust_code').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>collectioncontroller",
            method:"POST",
            data:{cust_verify_br:cust_verify_br,cust_verify_cust:cust_verify_cust},
            dataType:"text",
            success:function(value)
            {
                var customercount = value.split(",");
                if(customercount[0] == 0)
                {
                    $('#custcode_validation').css('display','block');
                }
                else{
                    $('#custcode_validation').css('display','none');
                }
                $('#invno').val(customercount[1]);
                $('#invdate').val(customercount[2]);
                $('#invamt').val(customercount[3]);
                $('#balamt').val(customercount[4]);
            }
    });
    });
</script>
<script>
  //Verifying Branch code is Valid
  $('.br_code').keyup(function(){
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
    });
</script>