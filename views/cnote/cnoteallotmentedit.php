<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'cnoteallotmentedit';
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

if(isset($_GET['allotid'])){
    $salesorderid = $_GET['allotid'];
    $allotment = $db->query("SELECT * FROM cnoteallot WHERE allotorderid='$salesorderid' AND stncode='$stationcode'")->fetch(PDO::FETCH_OBJ);
}
  
?>
    
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
                                        <a href="<?php echo __ROOT__ . 'Homepage'; ?>">Home</a>
                                        </li>
                                        
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'cnoteallotmentlist'; ?>">Cnote Allotment list</a>
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
                    if (isset($_GET['success']) && $_GET['success'] == 1)
                {
                        echo 
                "<div class='alert alert-success alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                Cnote Allotment updated successfully.
                </div>";
                }

                ?>

                </div>
                <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>cnoteallotmentedit" accept-charset="UTF-8" class="form-horizontal" id="formcnotesales" enctype="multipart/form-data">
                <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
                <input placeholder="" class="form-control border-form" name="salesid" type="text" id="salesid" value="<?php echo $salesorderid; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">

        <!-- Tittle -->
        <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Allotment</p>
        <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>cnoteallotmentlist"><button type="button" class="btn btn-danger btnattr" >
                        <i class="fa fa-times" ></i>
                </button></a>

                </div>
             
               </div> 
        <div class="content panel-body">

        <div class="tab_form">

        
        <table id="formPOSpurchase" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
        <!-- First Row -->
        <tr>		
        <td>Company Code</td>
        <td><input id="stncode" name="stncode" readonly type="text" value="" class="form-control input-sm stncode" style="text-transform:uppercase;"></td>
            
        <td>Company Name</td>
        <td>
            <input id="stnname" name="stnname" readonly value="" class="form-control input-sm" type="text">
        </td>
        </tr>
        <tr>
        <td>Branch Code</td>
        <td><div class="autocomplete col-sm-12">
        <input id="br_code" name="br_code" class="form-control input-sm br_code" type="text" value="<?=$allotment->br_code?>">
        <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
        </div>
        </td>
        <td>Branch Name</td>
        <td><div class="col-sm-12">
             <input id="br_name" name="br_name" value="<?=$allotment->br_name?>" class="form-control input-sm"  type="text"></div>
             </div>
        </td>	
        </tr>
        <!-- Second Row	 -->
        <tr>
        <td>Allotorder Id</td>
        <td>
        <input id="allotorderid" name="allotorderid" type="text" readonly value="<?=$allotment->allotorderid?>" class="form-control input-sm">
        </td>	
        <td>Alloted Date</td>
        <td>
        <input id="allotdate" name="allotdate" type="datetime" readonly value="<?=$allotment->date_added?>" class="form-control input-sm">
        </td>

        </tr>
        <tr>

        <td>Serial Type</td>
        <td>
        <input id="cnote_serial" name="cnote_serial" type="text" readonly value="<?=$allotment->cnote_serial?>" class="form-control input-sm">
        </td>	
        <td>Quantity</td>
        <td>
            <input  class="form-control input-sm cnote_count" name="cnote_count" type="text" readonly id="cnote_count" value="<?=$allotment->cnote_count?>">
            <p id="quantity_verification" class="form_valid_strings">Enter Cnote Quantity</p>
            <p id="quantityerror" class="form_valid_strings">Entered Quantity Unavailable!!</p>
            </td>

        </tr> 
        <tr> 
        
            <input tabindex="-1" readonly id="cnote_start" name="cnote_start" style="display:none" class="form-control input-sm"  type="text" >
           
             <td></td>
            <td></td>
          
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td> <button type="button" class="btn btn-primary btnattr_cmn" id="updatesales" name="cnoteallotmentupdate" onclick="verify()">
                            <i class="fa fa-crosshairs"></i>&nbsp; Update
                            </button>                           
        </tr> 
        </tbody></table>
  
        </div>

        </form>
        
        <!-- detail list -->
        
        <!-- detail list -->

        </div>
        </div>
        </div>

</section>

<!-- inline scripts related to this page -->
<script>
    function verify()
    {
        if($('#br_code').val().trim()=='' || $('#br_name').val().trim()=='')
        {
            swal("Please Enter Valid code !!");
        }
        else if($("#brcode_validation").css("display")==="block")
        {
           swal("Entered valid Branch code !!");
        }
        else{
            $('#updatesales').attr('type','submit');
            $('#updatesales').attr('name','cnoteallotmentupdate');
        }
    }

</script>


  <script>
  //Fetch Company code and name based on user
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
var branchcode =[<?php  

$brcode=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode' AND branchtype='D'");
while($rowbrcode=$brcode->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrcode['br_code']."',";
}

?>];
autocomplete(document.getElementById("br_code"), branchcode);

var branchname =[<?php  

$brname=$db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND branchtype='D'");
while($rowbrname=$brname->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrname['br_name']."',";
}
?>];
 
 autocomplete(document.getElementById("br_name"), branchname);
</script>

<script>
//branches array
var branches = {
    <?php
    $branches = $db->query("SELECT br_code,br_name FROM branch WHERE stncode='$stationcode' AND branchtype='D'");
    while($rowbranches=$branches->fetch(PDO::FETCH_OBJ))
    {
    echo $rowbranches->br_code.":'".$rowbranches->br_name."',";
    }
    ?>
}
</script>
<script>
  $(document).ready(function(){

        $('#br_code').keyup(function(){
            $('#brcode_validation').css('display','none');
            var br_code = $('#br_code').val().toUpperCase().trim();

            if(branches[br_code]!==undefined){
                //$('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
                fetchcustomer();
                getcustomersjson();
            }
            else{
                $('#br_name').val('');
                //$('#brcode_validation').css('display','block');
            }
        });
        //fetch code of entering name
        $('#br_name').keyup(function(){
            $('#brcode_validation').css('display','none');
            var br_name = $(this).val().trim();
            var gccode = getKeyByValue(branches,br_name);
            
            if(gccode!==undefined){
                //$('#brcode_validation').css('display','none');
                $('#br_code').val(gccode);
                fetchcustomer();
                getcustomersjson();
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
            }
            else{
                $('#brcode_validation').css('display','block');
            }
        }

    });
</script>
<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";  
}
</script>