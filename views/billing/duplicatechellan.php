<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'duplicatechellan';
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
   
?>

<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


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
                            Cnote Successfully Canelled.
            </div>";
            }

            ?>

            </div>
            <form method="POST" action="<?php echo __ROOT__ ?>cancellation" accept-charset="UTF-8" class="form-horizontal" id="formcnoteallot" enctype="multipart/form-data">
            

	        <div class="boxed boxBt panel panel-primary">
            <div class="title_1 title panel-heading"style=" height:50px;">
                  <p  style="font-weight:600;font-size:23px;color:white;margin-top:10px;">Duplicate Chellan</p>
                  <div class="align-right" style="margin-top:-35px;">

                <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
                        <i class="fa fa-times" ></i>
                </button></a>

                </div>
               </div> 
        	<div class="content panel-body">
            <ul class="nav nav-tabs" id="myTab4">
            
            
                </ul>

            <div class="tab_form">

		
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none">

            
            <div class="tab-content">
            <div id="singlecnotecancel" class="tab-pane active" style="margin-left: 25px;">   

        	<table style="width:70%;margin-left:0;" id="formPOSpurchase" class="POSDynStatic table" cellspacing="0" cellpadding="0" align="center">	
	    	<tbody>
          
            <tr>		
			<td class="">Entry Type</td>
			<td>
                 <select type="text" class="form-control border-form " name="entrytype" id="entrytype">
                    <option value="">Select Type</option>
                    <option value="cash">Cash</option>
                    <option value="credit">Credit</option>
                    </select>
            </td>				
			
            <td class="">Cnote Number</td>
			<td>
                 <input type="text" class="form-control border-form " name="cnote_number" id="cnote_number">
			</td>
            <td class="">Date</td>
			<td>
            <input type="text" id="date" name="date" value="<?php echo date("Y-m-d");   ?>" readonly>
            </tr> 
            </td>
            </tr> 
            



            </tbody></table>
           
                <table>

                <div class="align-right btnstyle" >


                <!-- <a style="color:white" href="<?php echo __ROOT__ ?>outstandinginvoicestatement"><button type="button" class="btn btn-primary btnattr" >
                       Printing
                </button></a>
                                                                 -->

                <a style="color:white" ><button type="button" class="btn btn-primary btnattr" id="printing" onclick="add()">
                       Print
                </button></a>
                                    


                </div>
                </table>
                </form>
           
	</div>

        
    </div>
	        </div>
           
            </div>
            </div>
             
                </form>
            </div>
</section>

<!-- inline scripts related to this page -->

<script>
  $(document).ready(function(){
$("#printing").click(function(){
	var compcode = '<?=$stationcode?>';
    var entrytype = $("#entrytype").val()
    var cnotenumber = $("#cnote_number").val();
    var datefield  = $("#date").val();
    // if(entrytype.trim()==''){
    //     swal("please enter type");
    // }
    // else if(cnotenumber.trim()==''){
    //     swal("please cnote type");
    // }
    console.log(cnotenumber);
     $.ajax({
        url:"<?php echo __ROOT__ ?>billingcontroller",
        method:"POST",
         data:{compcode,datefield,entrytype,cnotenumber,action : 'cnotenumber'},
         success:function(msg){
            
            data1 = JSON.parse(msg);
            custcode = data1[0].cust_code
       console.log(data1);
      
       if(data1[0].cnote_type=='Virtual'){
        if(entrytype=='cash' && data1[1]==datefield){
        
            window.open('<?php echo __ROOT__ ?>cashchallan?cno='+cnotenumber+'');

        }else if(entrytype=='credit' &&  data1[1]==datefield){
            window.open('<?php echo __ROOT__ ?>duplicatechallan?cno='+cnotenumber+'&custcode='+custcode+'');
     
        }else{
            swal("Please enter Cnote number billed on today's date only"); 
        }
       }
       else{

if(cnotenumber==''){
    swal("Please Enter the cnote number");
}else{
    if(data1[2]=='Printed'){
swal("Already Printed Challan");
}else{
swal("This is a regular Cnote number! Enter Virtual Cnote Number only");
 }
}



} 



         }
     })
   
});
  });
</script>
<!-- <script>
  $(document).ready(function(){
$("#printing").click(function(){

    var entrytype = $("#entrytype").val()
    var cnotenumber = $("#cnote_number").val();
  
    console.log(cnotenumber);
     $.ajax({
        url:"<?php echo __ROOT__ ?>billingcontroller",
        method:"POST",
         data:{entrytype,cnotenumber,action : 'cnotenumber'},
         success:function(msg){
            data1 = JSON.parse(msg);
            custcode = data1.cust_code
       console.log(custcode);
       if(data1.cnote_type=='Virtual'){
        if(entrytype=='cash'){
            
            window.open('<?php echo __ROOT__ ?>cashchallan?cno='+cnotenumber+'');
        }else if(entrytype=='credit'){
            window.open('<?php echo __ROOT__ ?>duplicatechallan?cno='+cnotenumber+'&custcode='+custcode+'');
     
        }
        
       }else{
       swal("This is either Invalid Cnote number or not belong to selected Entry type or not Virtual Cnote");
       }
     
         
         }
     })
   
});
  });
</script> -->
<script>
    function add(){
        if($('#entrytype').val().trim()=='')
        {
            swal("Please select the entry type");
        }
       
    }
</script>

