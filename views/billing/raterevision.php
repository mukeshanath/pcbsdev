<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

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

$month_ini = new DateTime("first day of last month");
$month_end = new DateTime("last day of last month");
?>
 <!-- Autocomplete -->
 <script type="text/javascript" src="vendor/jqueryautocomplete.js"></script>
        <link rel="stylesheet" href="vendor/jqueryautocompletestyle.css">
        <!-- end -->
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  

          <div class="alert-primary" role="alert">

          <?php 
               if ( isset($_GET['success']) && $_GET['success'] == 1 )
          {
               echo 
          "<div class='alert alert-success alert-dismissible'>
                         <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                         <h4><i class='icon fa fa-check'></i> Alert!</h4>
                         Billing Record updated successfully.
          </div>";
          }
          ?>

          </div>

<div id="col-form1" class="col_form lngPage col-sm-25">
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
                                            <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                            </li>                                        
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>                                        
                                            <li class="list-inline-item"> 
                                            <a href="<?php echo __ROOT__ . 'raterevisionlist'; ?>">Rate Revision List</a>
                                          </li>
                                    </ul>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


            <form method="POST" action="<?php echo __ROOT__ ?>raterevision" accept-charset="UTF-8" class="form-horizontal" id="formaddress" enctype="multipart/form-data">

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

            <div class="boxed boxBt panel panel-primary">
                  <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Rate Revision Update Form</p>
                    <div class="align-right" style="margin-top:-35px;">


<a style="color:white" href="<?php echo __ROOT__ ?>raterevisionlist"><button type="button" class="btn btn-danger btnattr" >
       <i class="fa fa-times" ></i>
</button></a>

</div>
              
          </div> 
      <div class="content panel-body">


        <div class="tab_form">
          <table class="table">	
          <tr>		
            <td>Company Code</td>
            <td>
                <div class="col-sm-9">
                     <input class="form-control border-form stncode" name="comp_code" type="text" id="stncode" readonly>
                </div>
            </td>              
            <td>Company Name</td>
            <td>
                <div class="col-sm-9">
                    <input class="form-control border-form " onkeyup="stnname_val(this)"  name="stnname" type="text" id="stnname" placeholder="Station Name" readonly>
                    
                </div>
            </td>
       </tr>
       <tr>		
            <td>Branch Code</td>
            <td>
                <div class="col-sm-9">
                <input placeholder="" class="form-control border-form br_code text-uppercase" name="br_code" type="text" id="br_code">
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                </div>
            </td>
            <td>Branch Name</td>
            <td>
                <div class="col-sm-9">
                <input class="form-control border-form" tabindex="-1" name="br_name" type="text" id="br_name" >
                   
                </div>
            </td>
       </tr>
       <tr>
        <td>Customer Code</td>
        <td>
        <div class="col-sm-9 " >
            <input id="cust_code"  name="cust_code" class="form-control input-sm cust_code text-uppercase" type="text">
            <p id="customer_validation" class="form_valid_strings">Enter Valid Customer Code</p>
            </div>
        </td>
        <!--  -->
        <td>Customer Name</td>
        <td>
        <div class="col-sm-9">
        <input id="cust_name" name="cust_name" tabindex="-1"  class="form-control input-sm"  type="text" >
        </div>
        </td>
        </tr>
       <tr><td class="filter-title">Booking Date From</td>
            <td>
            <div class="col-sm-9">
              <input type="date" class="form-control border-form" id="book-date-from" name="book_date_from" value="<?=$month_ini->format('Y-m-d'); // First Date of Prev Month?>"></td>
          </div>
            <td>Booking Date to</td>
              
            <td>
            <div class="col-sm-9">
              <input type="date" class="form-control border-form" id="book-date-to" name="book_date_to"  value="<?=$month_end->format('Y-m-d'); // Last Date of Prev Month?>">
        </div>
      </td>
            
       </tr>  
                  </table>
                  <table>

                  <div class="align-right btnstyle" >

                  <button type="button" onclick="revice()" class="btn btn-success btnattr_cmn" name="updateall">
                  <i class="fa  fa-save"></i>&nbsp; Revice
                  </button>

                  <!-- <button type="submit" class="btn btn-primary btnattr_cmn" name="deviationonly">
                  <i class="fa  fa-save"></i>&nbsp; Deviated Only
                  </button>
                                                                     -->


                  </div>
                  </table>

                <!-- Fetching Mode Details in Table  -->
               
          </form>
          </div>
          </div>
          </div>
</section>

<!-- inline scripts related to this page -->
<script>
    function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
</script>
<script>
var branchcode =[<?php  

$brcode=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
while($rowbrcode=$brcode->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrcode['br_code']."',";
}

?>];
autocomplete(document.getElementById("br_code"), branchcode);

var branchname =[<?php  

$brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode'");
while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrname['br_name']."',";
}

?>];
 
 autocomplete(document.getElementById("br_name"), branchname);
console.log(branchcode);
</script>

<script>
//branches array
var branches = {
    <?php
    $branches = $db->query("SELECT br_code,br_name FROM branch WHERE stncode='$stationcode'");
    while($rowbranches=$branches->fetch(PDO::FETCH_OBJ))
    {
    echo $rowbranches->br_code.":'".$rowbranches->br_name."',";
    }
    ?>
}
</script>
<script>
function fetchcustomer(){

   var branch8 = $('.br_code').val().toUpperCase();
   var comp8 = '<?=$stationcode?>'.toUpperCase();
   //console.log(branch8,comp8);

       $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch8:branch8,comp8:comp8},
       dataType:"json",
       success:function(cust8)
       {
           autocomplete(document.getElementById("cust_code"), cust8); 
           //console.log(cust8);

       }
       });
       $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{branch11:branch8,comp11:comp8},
       dataType:"json",
       success:function(custname)
       {
           autocomplete(document.getElementById("cust_name"), custname); 
       }
       });

}
</script>
<script> 

$(document).ready(function(){
     
     $('#cust_code').focusout(function(){
        $('#customer_validation').css('display','none');
         var cust_code = $('#cust_code').val().toUpperCase().trim();

        if(customers[cust_code]!==undefined){
            $('#cust_name').val(customers[cust_code]);
           
        }
        else{
            $('#cust_name').val('');
            //$('#customer_validation').css('display','block');
        }
     });

     $('#cust_code,#cust_name').focusout(function(){
        if($(this).val().trim()!='')
        {
        validatecustomer();
        }
     });

     //fetch code of entering name
     $('#cust_name').focusout(function(){
         $('#customer_validation').css('display','none');
         var cust_name = $(this).val();
         var ccode = getKeyByValue(customers,cust_name);

         if(ccode!==undefined){
            $('#cust_code').val(ccode);
            //$('#customer_validation').css('display','none');
            
            }
            else{
                $('#cust_code').val('');
                //$('#customer_validation').css('display','block');
            }
     });
});

function validatecustomer(){
    var cust_code = $('#cust_code').val().toUpperCase().trim();
    if(customers[cust_code]!==undefined){
            $('#customer_validation').css('display','none');
        }
        else{
            $('#customer_validation').css('display','block');
        }
}
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
           
            }
    });


    
  });

</script>
<script>
  $(document).ready(function(){
    var customers = {};

        $('#br_code').focusout(function(){
            $('#brcode_validation').css('display','none');
            var br_code = $('#br_code').val().toUpperCase().trim();

            if(branches[br_code]!==undefined){
                //$('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
                fetchcustomer();
            }
            else{
                $('#br_name').val('');
                //$('#brcode_validation').css('display','block');
            }
        });
        //fetch code of entering name
        $('#br_name').focusout(function(){
            $('#brcode_validation').css('display','none');
            var br_name = $(this).val().trim();
            var gccode = getKeyByValue(branches,br_name);
            
            if(gccode!==undefined){
                //$('#brcode_validation').css('display','none');
                $('#br_code').val(gccode);
                fetchcustomer();
            }
            else{
                $('#br_code').val('');
                //$('#brcode_validation').css('display','block');
            }
           
        });

        $('#br_code,#br_name').focusout(function(){
            if($(this).val().trim()!='')
            {
                validatebranch();
            }
        });

        function validatebranch(){
            var br_code = $('#br_code').val().toUpperCase().trim();
            if(branches[br_code]!==undefined){
                $('#brcode_validation').css('display','none');
                getcustomersjson();
            }
            else{
                $('#brcode_validation').css('display','block');
            }
        }


    });

</script>
<script> 
function getcustomersjson(){
   //get customers json
   var branch8 = $('.br_code').val().toUpperCase();
   var comp8 = '<?=$stationcode?>'.toUpperCase();
   $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{getjsoncust_br:branch8,getjsoncust_stn:comp8},
       dataType:"text",
       success:function(customerjson)
       {
          customers = JSON.parse(customerjson);
          //console.log(customers);
       }
       });
    }
</script>
<script> 
function revice(){
   //get customers json
   var br_code = $('#br_code').val().toUpperCase();
   var comp_code = '<?=$stationcode?>'.toUpperCase();
   var cust_code = $('#cust_code').val().toUpperCase();
   var bdate_from = $('#book-date-from').val();
   var bdate_to = $('#book-date-to').val();
   var user_name = $('#user_name').val();

   if(br_code.trim().length>0){
   $.ajax({
       url:"<?php echo __ROOT__ ?>raterevision",
       method:"POST",
       data:{br_code,comp_code,cust_code,bdate_from,bdate_to,user_name},
       dataType:"text",
       });
       
       location.href='raterevisionlist?success=1';
    }
    else{
        swal('Please Enter BranchCode to proceed');
    }
    }
</script>