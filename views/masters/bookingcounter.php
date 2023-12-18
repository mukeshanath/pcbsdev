<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

//if user is not logged in, they cannot access this page
	
if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'bookingcounter';
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

function loadaddresstype($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM address ORDER BY aid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->addr_type.'">'.$row->addr_type.'</option>';
         }
         return $output;    
        
    }

    function loadtaxslab($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM tax ORDER BY taxid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->taxval.'">'.$row->taxval.'</option>';
         }
         return $output;    
        
    }

    function loadregion($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM region ORDER BY regid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->regioncode.'">'.$row->regioncode.'</option>';
         }
         return $output;    
        
    }

    $user = $_SESSION['user_name'];

    $stncode = $db->query("SELECT br_code,stncodes,last_compcode FROM users WHERE user_name='$user'");
        $station = $stncode->fetch(PDO::FETCH_ASSOC);
        $station_codes = $station['last_compcode'];
        if(!empty($station_codes)){
            $station_code = $station_codes;
        }
        else{
            $userstations = $station['stncodes'];
            $stations = explode(',',$userstations);
            $station_code = $stations[0]; 
        }

    //branch filter conditions
 $sessionbr_code = $station['br_code'];
 if(!empty($sessionbr_code)){
     $br_code = $sessionbr_code;
     $fetchbrname = $db->query("SELECT br_name FROM branch WITH (NOLOCK) WHERE br_code='$sessionbr_code' AND stncode='$station_code'")->fetch(PDO::FETCH_OBJ);
     $br_name     = $fetchbrname->br_name;
     }
//branch filter conditions
?>
<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


<div id="col-form1" class="col_form lngPage col-sm-25">
<!-- Bread Crumb -->
 <div class="au-breadcrumb m-t-75" style="margin-bottom:-8px;">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="au-breadcrumb-content">
                                    <div class="au-breadcrumb-left">
                                        <span class="au-breadcrumb-span"></span>
                                        <ul class="list-unstyled list-inline au-breadcrumb__list">
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'bookingcounterlist'; ?>">Booking Counter list</a>
                                        </li>
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
                                  <h4><i class='icon fa fa-check'></i> Success!</h4>
                                  Booking Counter Added successfully.
                                </div>";
                  }

                   ?>

             </div>

<form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>bookingcounter" accept-charset="UTF-8" class="form-horizontal" id="formcompany" enctype="multipart/form-data">
<input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

 <div class="boxed boxBt panel panel-primary">
                    <!-- Tittle -->
    <div class="title_1 panel-heading"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Booking Counter Form</p>
          
    <div class="align-right" style="margin-top:-35px;">
       <button type="submit" class="btn btn-success btnattr_cmn" id="addanbook" name="addancounter">
       <i class="fa fa-save"></i>&nbsp; Save & Add
       </button>

       <button type="submit" class="btn btn-primary btnattr_cmn" id="addbook" name="addcounter">
       <i class="fa  fa-save"></i>&nbsp; Save
       </button>
                                                           
     
       <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
       <i class="fa fa-eraser"></i>&nbsp; Reset
       </button> 

       <a style="color:white" href="<?php echo __ROOT__ . 'bookingcounterlist'; ?>">          
       <button type="button" class="btn btn-danger btnattr_cmn" >
        <i class="fa fa-times" ></i>
       </button></a>
    </div>
    </div> 
	<div class="content panel-body">
            <!-- Navigation Tabs -->
      <ul class="nav nav-tabs" id="myTab4">
        <li id="gen_li" class="active">
            <a id="gen_a" data-toggle="tab" href="#general" >General</a>
        </li>
        <li id="reg_li">
            <a id="reg_a" data-toggle="disabled" href="#regulatory" >Regulatory</a>
        </li>
        
      </ul>
   <div class="tab_form" >
    <div class="tab-content">
  	    <!-- Tab Form 1 -->
     <div id="general" class="tab-pane active" style="margin-left: 25px;">
     <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
	    <tbody>
        <tr>		
            <td>Company Code</td>
            <td >
                <div class="col-sm-9 autocomplete">
                     <input  class="form-control border-form"  name="stncode" type="text" id="stncode" autocomplete="off" readonly>
                      
                   
                </div>
            </td>
              
            <td>Company Name</td>
            <td>
                <div class="col-md-9">
                    <input class="form-control border-form "  name="stnname" type="text" id="stnname"  readonly>
                           
                </div>
            </td>
       </tr>
       <tr>		
            <td>Branch Code</td>
            <td >
                <div class="col-sm-9 autocomplete">
                <input placeholder="" class="form-control border-form br_code" name="br_code" type="text" id="br_code" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?php echo !empty($sessionbr_code)?$br_code:''; ?>" autofocus required>
                    <p id="br_code_val" class="form_valid_strings">Please enter only strings.</p>
                    <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
                     <p id="br_code_empty" class="form_valid_strings">Field Cannot be Empty.</p>
                </div>
            </td>
              
            <td>Branch Name</td>
            <td>
                <div class="col-md-9">
                <input class="form-control border-form" name="br_name" type="text" <?=(!empty($sessionbr_code)?" readonly ":"")?> value="<?php echo !empty($sessionbr_code)?$br_name:''; ?>" id="br_name">
                       
                </div>
            </td>
       </tr>
      <tr>	
      <tr>		
            <td>Counter Code</td>
            <td >
                <div class="col-sm-9 autocomplete">
                <input class="form-control border-form" name="book_code" type="text" id="book_code" readonly>
                </div>
            </td>
              
            <td>Counter Name</td>
            <td>
                <div class="col-md-9">
                <input class="form-control border-form "  name="book_name" type="text" id="book_name"  >       
                </div>
            </td>
       </tr>
      <tr>				
			  <td>Address</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="book_addr" type="text" id="book_addr"  >
                   
                </div>
             </td>
             <td>City</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="book_city" type="text" id="book_city">
                </div>
             </td>
                </tr>
    <tr>
    <tr>			
			  <td>State</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="book_state" type="text" id="book_state">
                   
                </div>
             </td>
             <td>Country</td>
			  <td>
               <div class="col-sm-9">
                    <input class="form-control border-form"  name="book_country" type="text" id="book_country">
                 
                </div>
             </td>
                </tr>

    <tr>
        <td>Phone</td>
			  <td>
              <div class="col-sm-9">
                    <input  class="form-control border-form"  name="book_phone" type="number" id="book_phone">
                   
                   
              </div>
        </td>
        <td>Fax</td>
		  	<td>
            <div class="col-sm-9">
                 <input  class="form-control border-form" name="book_fax" type="number" id="book_fax"  >
                    
            </div>  
        </td>
    </tr>
    <tr>	
		  
			
		  	<td>Email</td>
		  	<td>
             <div class="col-sm-9">
                  <input  class="form-control border-form" onkeyup="email_val(this)" name="book_email" type="text" id="book_email" placeholder="" >
                  <p id="email_val" class="form_valid_strings">Enter valid Email.</p>   
                               
             </div>
		  	</td>	
              <td>Contact Person</td>
		  	<td>
            <div class="col-sm-9">
            <input  class="form-control border-form" name="c_person" type="text" id="c_person" placeholder="" >      
            </div>  
        </td>
    </tr>
    <tr>	
		  
			
        <td>Active</td>
		  	<td>
            <div class="col-sm-9">
                <select class="form-control" name="book_active" id="book_active">
                      <option value="Y" selected="selected">Yes</option>
                      <option value="N">No</option>
                    </select> 
                </div>            
         </td>
         <td>Remarks</td>
		  	<td>
            <div class="col-sm-9">
            <input  class="form-control border-form" rows="1" name="book_remarks" cols="50" id="book_remarks">     
            </div>  
        </td>

    </tr>
   </tbody>
  </table>
  <div class="align-right">
        <button type="button" class="btn btn-primary " data-toggle="tab" onclick="regulatory()" id="btn_regul">
      Next  &#8594;
      </button>
</div>
 </div>
 <div id="regulatory" class="tab-pane" style="margin-left: 25px;">
    <table id="formPOSpurchase" cellpadding="0" border="0" cellspacing="0" align="center" class="table">
        <tbody>
            <tr>
            <td>Proprietor</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" name="proprietor" type="text" id="proprietor"  >  
              </div>
          </td>
            
          <td>GSTIN</td>
          <td>
              <div class="col-sm-9">
                <input class="form-control border-form" onkeyup="gst_val(this)"  name="book_gstin" type="text" id="book_gstin" >	
                <p id="gst_val" class="form_valid_strings">Enter valid GST Number.</p>                                   
                </div>
          </td>
                    </tr><tr>
          <td>TIN</td>
          <td>
              <div class="col-sm-9">
              <input class="form-control border-form "  name="book_tin" type="text" id="book_tin" >
              </div> 
          </td>	

           <td>FSC</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" name="book_fsc" type="text" id="book_fsc"  >
              </div> 
          </td>	
        
            </tr>
            <tr>
            <td>Deposit</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" name="book_deposit" type="text" id="book_deposit"  >
              </div>
          </td>	
          <td>C-Note Charge</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" name="cnote_charges" type="text" id="cnote_charges"  >
              </div>
          </td>	
                </tr>

                <tr>
            <td>Commission % D</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" name="percent_domscomsn" type="text" id="percent_domscomsn"  >
              </div>
          </td>	
          <td>Commission % I</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" name="percent_intlcomsn" type="text" id="percent_intlcomsn"  >
      </div>
              </div>
          </td>	
                </tr>

                <tr>
            <td>PRO Commission</td>
          <td>
              <div class="col-sm-9">
              <input class="form-control border-form "  name="pro_commission" type="text" id="pro_commission" >
              </div>
          </td>	
          <td>Special Discount</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form" name="spl_discount" type="text" id="spl_discount"  >
           </div>
              </div>
          </td>	
                </tr>

           <tr>
            <td>Deposit Date</td>
          <td>
              <div class="col-sm-9">
              <input  class="form-control border-form today" name="deposit_date" type="date" id="deposit_date" >
              </div>
          </td>	
          
          </tr>
        </tbody>
        </table>
        </div>
        </div>      
        </form>
	</div>
	</div>
	</div>
</div>


<style>
	#POSCustErrorMsg .error{
	color : #FE2E64;
	}
</style>


						
</section>

<!-- inline scripts related to this page -->


<script>

function regulatory(){
    
    if($("#stncode").val()==""){
        $('#stncode_empty').css('display','block');
    }
    if($("#stnname").val()==""){
        $('#stnname_empty').css('display','block');
    }
    
   
   
    else{
      if($("#stnname_val").css("display")==="block")
        {
            return false;
        }
        if($("#stncode_val").css("display")==="block")
        {
            return false;
        }
        if($("#stnname_empty").css("display")==="block")
        {
            return false;
        }
        if($("#stncode_empty").css("display")==="block")
        {
            return false;
        }
       
        
       
        
        else{
            $("#reg_li").attr("class","active");
            $("#gen_li").attr("class","");
            $("#reg_a").attr('data-toggle','tab');
            $("#btn_regul").attr('href','#regulatory');
        }
    }
}


//Suggession of Zone Destination Textfields
    $('#stncode').keyup(function(){
        var keyval = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{keyval:keyval},
            dataType:"text",
            success:function(data)
            {
            $('#stationlist_sugg').html(data);
            }
    });
    });
    $(document).on('click', '#sugg_li', function(){  
           $('#stncode').val($(this).text());  
           $('#stationlist_sugg').empty();  
      }); 

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
            if($('#stncode').val() == '')
            {
                // $('#companysection').css('display','block');
                var superadmin_stn = $('#stncode1').val();
                //var superadmin_stn = 'DAA';
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

  var stationcode =[<?php  
    $sql=$db->query("SELECT * FROM stncode");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['stncode']."', ";
    }
 ?>];
 </script>

<script>
    function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
</script>
 <script>

 //Branch Code Autocomplete
 var branchcode =[<?php  
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
    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];

 
var branchname = [<?php
 $brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode'");
 while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
 {
 echo "'".$rowbrname['br_name']."',";
 }
 ?>]
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
  $(document).ready(function(){

        $('#br_code').focusout(function(){
            $('#brcode_validation').css('display','none');
            var br_code = $('#br_code').val().toUpperCase().trim();

            if(branches[br_code]!==undefined){
                generatecountercode(br_code);
                $('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
                $('#addanbook').attr('type','submit');
	            $('#addbook').attr('type','submit');
            }
            else{
                $('#book_code').val('');
                $('#br_name').val('');
                $('#brcode_validation').css('display','block');
                $('#addanbook').attr('type','button');
	            $('#addbook').attr('type','button');
            }
        });
        //fetch code of entering name
        $('#br_name').focusout(function(){
            $('#brcode_validation').css('display','none');
            var br_name = $(this).val().trim();
            var gccode = getKeyByValue(branches,br_name);
            
            if(gccode!==undefined){
                generatecountercode(gccode);
                $('#brcode_validation').css('display','none');
                $('#br_code').val(gccode);
                $('#addanbook').attr('type','submit');
	            $('#addbook').attr('type','submit');
            }
            else{
                $('#book_code').val('');
                $('#br_code').val('');
                $('#brcode_validation').css('display','block');
                $('#addanbook').attr('type','button');
	            $('#addbook').attr('type','button');
            }
        });

    });

</script>
<script>
autocomplete(document.getElementById("stncode"), stationcode);
autocomplete(document.getElementById("br_code"), branchcode);
autocomplete(document.getElementById("br_name"), branchname);

</script>
<script>
    function generatecountercode(br_code){
        $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{bookcode_brcode:br_code,bookcode_stn:'<?=$station_code?>'},
            dataType:"text",
            success:function(data)
            {
                $('#book_code').val(data);
            }
        });
    }
</script>
<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('book_code').value = "";
    document.getElementById('book_name').value = "";
    document.getElementById('book_addr').value = "";
    document.getElementById('book_city').value = "";
    document.getElementById('book_state').value = "";
    document.getElementById('book_country').value = "";
    document.getElementById('book_phone').value = "";
    document.getElementById('book_fax').value = "";
    document.getElementById('book_email').value = "";
    document.getElementById('book_active').value = "";
    document.getElementById('book_remarks').value = "";
    document.getElementById('proprietor').value = "";
    document.getElementById('book_gstin').value = "";
    document.getElementById('book_tin').value = "";
    document.getElementById('book_fsc').value = "";
    document.getElementById('book_deposit').value = "";
    document.getElementById('cnote_charges').value = "";
    document.getElementById('percent_domscomsn').value = "";
    document.getElementById('percent_intlcomsn').value = "";
    document.getElementById('pro_commission').value = "";
    document.getElementById('spl_discount').value = "";
    document.getElementById('deposit_date').value = "";
}


$(window).on('load', function () {
    if(<?=isset($sessionbr_code)?>)
    generatecountercode('<?=$sessionbr_code?>');
  });
</script>