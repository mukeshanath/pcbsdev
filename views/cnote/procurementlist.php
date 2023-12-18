<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
  
    $module = 'procurementlist';
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
  
 function loadstatus($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM status ORDER BY sid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->stat_type.'">'.$row->stat_type.'</option>';
         }
         return $output;    
        
    }

    function loadgroup($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM groups  ORDER BY gpid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
         }
         return $output;    
        
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
                                        
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        
                                        <li class="list-inline-item">
                                        <a href="<?php echo __ROOT__ . 'procurementlist'; ?>">Cnote Procurement list</a>
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
                            Cnote Procurement Record Added successfully.
            </div>";
            }
            if ( isset($_GET['success']) && $_GET['success'] == 5 )
            {
                    echo 
            "<script>
                swal('Procurement request has been Initiated and will be completed with in 5 to 10mins.');
            </script>";
            }


            ?>

            </div>
           
           

	        <div class="boxed boxBt panel panel-primary">
            <div class="title_1 title panel-heading">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Procurement Filters</p>
                  <div class="align-right" style="margin-top:-35px;">

        <a style="color:white" href="<?php echo __ROOT__ ?>procurement"><button type="button" class="btn btn-success btnattr" >
        <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
                <i class="fa fa-times" ></i>
        </button></a>

 </div>
             
               </div> 
              
	        <div class="content panel-body">
	
            <div class="tab_form">

        
        <!-- Error or Warning display panel -->
        
        <form method="POST">

<input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
   <table class="table" cellspacing="0" cellpadding="0" align="center">	
   <tbody>
            <tr>		
            <td>Purchase Order No</td>
            <td>
            <input class="form-control stncode" name="po_no" type="text" id="po_no" value="<?php if(isset($_POST['po_no'])){ echo  $_POST['po_no']; } ?>">
            
            </td>
                
            <td>Cnote Serial</td>
            <td>
            <input class="form-control border-form" name="cnote_serial" type="text" id="cnote_serial" value="<?php if(isset($_POST['cnote_serial'])){ echo  $_POST['cnote_serial']; } ?>">
           

            </td>
            <td>Vendor</td>
            <td>
            <input class="form-control border-form" name="vendor" type="text" id="vendor" value="<?php if(isset($_POST['vendor'])){ echo  $_POST['vendor']; } ?>">
            </td>		
            <td>Month</td>
            <td>
            <input class="form-control border-form <?php if(isset($_POST['mon_th'])){ }else{ echo 'this-month'; } ?>" name="mon_th" type="month" id="mon_th" value="<?php if(isset($_POST['mon_th'])){ echo $_POST['mon_th']; } ?>">
            </td>		
           
            </tr>
            
       </tbody>
</table>
<table>

<div class="align-right btnstyle" >

<button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterprocure">
 Apply <i class="fa fa-filter bigger-110"></i>                         
</button> 
                                                  
<button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()" >
 Reset <i class="fa fa-refresh bigger-110"></i>                         
</button> 
<!-- <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
<i class="fa fa-eraser"></i>&nbsp; Reset
</button>    -->


</div>
</table>


  

</form>

</div>
    <!-- ToolTip -->
       


    <div class="col-sm-12 data-table-div">
    <div class="title_1 title panel-heading">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Procurement List</p>
                  <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
               </div>
	    <table id="data-table" class="data-table table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
	        <thead>
                <tr class="form_cat_head">
                     <th class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" />
                            <span class="lbl"></span>
                        </label>
                    </th>
                   
                    <th>Purchase Order</th>
                    <th>Station</th>
                    <th>Serial Type</th>
                    <th>CNote Total</th>		
                    <th>CNote Start</th>
                    <th>CNote End</th>		
                    <th>Procure Date</th>
                    <th>Status</th>                    
                    <th>Action</th>
                </tr>
            </thead>
		<tbody>
        <?php 

                $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
                $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
                $rowuser = $datafetch['last_compcode'];
                if(!empty($rowuser)){
                    $stationcode = $rowuser;
                    $stn_condition = "WHERE cnote_station='$stationcode'";// AND cnote_status='Procured'
                }
                else{
                    $userstations = $datafetch['stncodes'];
                    $stations = explode(',',$userstations);
                    $stationcode = $stations[0];  
                    $stn_condition = "WHERE cnote_station='$stationcode'";   // AND cnote_status='Procured'
                }
                if(isset($_POST['filterprocure']))
                {    
                    $po_no              = $_POST['po_no'];
                    $cnote_serial       = $_POST['cnote_serial'];
                    $vendor             = $_POST['vendor']; 
                    $mon_th             = $_POST['mon_th']; 

                    $conditions = array();

                    if(! empty($po_no)) {
                    $conditions[] = "cnotepono LIKE '$po_no%'"; 
                    }
                    if(! empty($cnote_serial)) {
                    $conditions[] = "cnote_serial LIKE '$cnote_serial%'";
                    }
                    if(! empty($vendor)) {
                    $conditions[] = "vendor LIKE '$vendor%'";
                    }
                    if(! empty($mon_th)) {
                    $conditions[] = "YEAR(date_added) ='".explode('-',$mon_th)[0]."' AND MONTH(date_added) = '".explode('-',$mon_th)[1]."'";
                    }
                    if(!empty($stationcode)) {
                    $conditions[] = "cnote_station='$stationcode'";
                    }
                    

                    $conditons = implode(' AND ', $conditions);

                    if (count($conditions) > 0) {
                        $query = $db->query("SELECT * FROM cnote WHERE  $conditons"); 
                    }
                    if (count($conditions) == 0) {
                    $query = $db->query("SELECT * FROM cnote $stn_condition ORDER BY cnoteid DESC"); 
                }
                }
                //Normal Station list
                else{
                $query = $db->query("SELECT * FROM cnote $stn_condition  AND datepart(mm,date_added) = month(getdate()) ORDER BY cnoteid DESC");
                }
                if($query->rowcount()==0)
                {
                echo "";
                }
                else{
                    while ($row = $query->fetch(PDO::FETCH_OBJ))
                    {
                        $getnewserial = $db->query("SELECT * FROM groups WHERE cate_code='$row->cnote_serial' AND stncode='$row->cnote_station'")->fetch(PDO::FETCH_OBJ);
                        if($getnewserial->grp_type=='gl'){
                            $rowserial = $row->cnote_station;
                        }
                        else{
                            $rowserial = $getnewserial->cate_code;
                        }
                        echo "<tr>";
                        echo "<td class='center first-child'>
                                <label>
                                <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                    <span class='lbl'></span>
                                </label>
                            </td>";
                    
                        echo "<td>".$row->cnotepono."</td>";
                        echo "<td>".$row->cnote_station."</td>";
                        echo "<td>".$row->cnote_serial."</td>";
                        echo "<td>".$row->cnote_count."</td>";
                        echo "<td>".$rowserial.$row->cnote_start."</td>";
                        echo "<td>".$rowserial.$row->cnote_end."</td>";
                        echo "<td>".$row->date_added."</td>"; 
                        echo "<td><a class='btn btn-".(($row->cnote_status=='Procured')?'success ':'warning ')." btn-minier'>".$row->cnote_status."</a></td>";       
                        echo "<td><div class='hidden-sm hidden-xs action-buttons'>

                        <a class='btn btn-primary btn-minier' href='posummary?ponumber=".$row->cnotepono."' data-rel='tooltip' title='Print' target='_blank'>
                            <i class='ace-icon fa fa-print bigger-130'></i>
                        </a>
                        
                        </div>
                        <div class='hidden-md hidden-lg'>
                            <div class='inline pos-rel'>
                            <button class='btn btn-minier btn-yellow dropdown-toggle' data-toggle='dropdown' data-position='auto'>
                            <i class='ace-icon fa fa-caret-down icon-only bigger-120'></i>
                            </button>
                            <ul class='dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close'>
                                <li>
                                <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                                <span class='blue'>
                                <i class='ace-icon fa fa-eye bigger-120'></i>
                                </span>
                                </a>
                            </li>
                            <li>
                            </li>
                            </ul>
                                        </div>
                                    </div>
                    </td>";
                            echo "</tr>";
                    
                                                
                    }
                }
                    
                ?>

	</tbody></table>
	</div>
	
	</div>

	
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

<script>
function resetFunction() {
  document.getElementById("formitem").reset();
}
</script>

<script>
 var btn=document.querySelector(".addbtn");
 btn.onclick=function create(){
     var div=document.createElement("tr");
     div.setAttribute("id","form_group");
     div.innerHTML=gen();
     document.getElementById("dynamic_form").appendChild(div); 
    }
     function gen()
     {
        return '<td><select class="form-control input-sm" name="cnote_serial[]" style="border-color:#337AB7"><?php echo loadgroup($db); ?></select></td><td><div class="input-group"><input type="text" class="form-control input-sm" name="cnote_count[]" style="border-color:#337AB7"><span class="input-group-btn"><a type="button" class="btn btn-danger btn-xs" style="padding: 3.5px 4.5px;" onclick="remove(this)" id="remove" ><i class="fa fa-minus-circle"></i></a></span></div></td>';
     }
     function remove()
     {
         document.getElementById("form_group").remove();
     }

    function searchcollection()
    {
        document.getElementsByClassName('searchcollection').style.display='block';
    }

 </script>
<script>
function resetFunction() {
    document.getElementById('po_no').value = "";
    document.getElementById('cnote_serial').value = ""; 
    document.getElementById('vendor').value = "";    
}  
</script>