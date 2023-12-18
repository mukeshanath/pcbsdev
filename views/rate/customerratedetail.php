<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'customerratedetail';
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
 
  function delete($db,$user){
    $module = 'customerratedelete';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;
  
    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'swal("You Dont Have Permission to Delete this Record!")';
        }
        else{
            return "deleterecord(this.id)";
        }
  }

function loadmode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM mode ORDER BY mid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->mode_code.'">'.$row->mode_name.'</option>';
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
 


    if(isset($_GET['cust_code']))
    {
        $cust_code = $_GET['cust_code'];
        $query1 = $db->query("SELECT * FROM rate_custcontract WHERE cust_code ='$cust_code' AND stncode='$stationcode'");
        $row1 = $query1->fetch(PDO::FETCH_ASSOC);
    
        $getcustdetails = $db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$stationcode'");
        if($getcustdetails->rowCount()==0){
          echo "<script> alert('No Customer Found!!') </script>";
        }
        else{
        $custdetailsrow = $getcustdetails->fetch(PDO::FETCH_ASSOC);
        
        $br_code = $custdetailsrow['br_code'];
        $getbranch = $db->query("SELECT br_name FROM branch WHERE stncode='$stationcode' AND br_code='$br_code'")->fetch(PDO::FETCH_OBJ);
        $br_name = $getbranch->br_name;
        $cust_name = $custdetailsrow['cust_name'];
        $contract_period = $custdetailsrow['contract_period'];
        $date_contract = $custdetailsrow['date_contract'];
    }
    //check zone 
    if($custdetailsrow['zncode']=='cash'){
        $zntype = 'cash';
    }
    else{
        $zntype = 'credit';
    }
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
                                        <a href="<?php echo __ROOT__ . 'customerratelist'; ?>">Rate list</a>
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
                                  Customer Rate Record Added successfully.
                  </div>";
                  }
                  

                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 1 )
                  {
                       echo 
                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Mode Already added to this Customer.Please choose another mode.
                  </div>";
                  }
                  if ( isset($_GET['zonedest']))
                  {
                  echo 
                  "<div class='alert alert-danger alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-times'></i> Alert!</h4>
                                  Zone or Destination Entered is Not Valid. Please Enter Valid Destination.
                  </div>";
                  }

               ?>

             </div>
      <form method="POST" autocomplete="off" action="<?php echo __ROOT__ ?>customerrate" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
        <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

        <div class="boxed boxBt panel panel-primary">
 
       <!-- Tittle -->
       <div class="title_1 title panel-heading">
        <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Rate - <?php echo $row1['cust_rate_id']; ?></p>
        <div class="align-right" style="margin-top:-35px;">

        <a style="color:white" href="<?php echo __ROOT__ ?>customerratelist"><button type="button" class="btn btn-danger btnattr" >
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
                <input placeholder="" class="form-control stncode" name="stncode" type="text"  id="stncode" readonly>
            </td>
            <td>
                <input class="form-control border-form "  name="stnname" type="text" id="stnname" value=""  placeholder="Station Name" readonly>
            </td>
            <td>Branch</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input id="br_code" name="br_code" value="<?=$br_code;  ?>" class="form-control input-sm br_code" type="text" required>
                <p id="brcode_validation" class="form_valid_strings">Enter Valid Branch Code</p>
            </div>
            </td>
            <td><div class="col-sm-12">
                <input id="br_name" name="br_name" value="<?=$br_name;  ?>" class="form-control input-sm" type="text" required></div>
            </td>
            <td>Customer</td>
            <td style="width:100px;">
            <div class="autocomplete">
                <input style="text-transform:uppercase;" value="<?php  if(isset($_GET['cust_code'])) {echo $_GET['cust_code'];} ?>" class="form-control cust_code" name="cust_code" type="text" id="cust_code" >
                <p id="custcode_validation" class="form_valid_strings">Enter Valid Customer Code</p>
            </div>
            </td>
            <td>
                <input class="form-control border-form " value="<?=$cust_name; ?>" name="cust_name" type="text" id="cust_name" placeholder="Customer Name" >
            </td>	
        </tr>
         <tr>
            <td>Rate Type</td>
            <td>
                <select name="rate_type" id="rate_type">  
                <option value="CR">Customer Rate</option>  
                <option value="MSR">MSR</option>  
                <option value="BR">Board Rate</option>  
                </select> 
            </td>
            <td></td>	
            <!-- <td><div class="col-sm-3">File</div>
            <div class="col-sm-9">
                <input type="file" class="form-control input-sm" name="file" accept=".jpg,.pdf" onchange="ValidateSingleInput(this);" id="contract_files" data-max-size="1"></div></td>		
             -->
            <td>Contract Period and Date</td>
            <td style="width:100px;">
                <input class="form-control border-form" value="<?=$contract_period; ?>" placeholder="Period(months)" name="contract_period"  id="contract_period"  readonly>
            </td>
            <td>
                <input class="form-control border-form" value="<?=$date_contract; ?>" name="date_contract"  id="date_contract"  readonly>
            </td>	
            <td>&nbsp; Effective</td>
            <td>date</td>
            <td>
                <input type="date" class="form-control input-sm today">
            </td>	
            <?php
            if(!empty($custdetailsrow['rate_file_name']))
            {
            ?>
            <!-- <tr>
            <td>ContractFile</td>
            <td><input class="form-control border-form" value="<?=$custdetailsrow['rate_file_name']; ?>" name="rate_contract_file_name"  id="rate_contract_file_name"  readonly></td>
            <td>
            <button data-toggle="modal" data-target="#ratefile" class="btn btn-xs btn-primary" aria-controls="data-table" type="button"><i class="fa fa-folder-open" aria-hidden="true"></i></button> 
            <button class="btn btn-xs btn-danger" onclick="deletecontractfile()" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button> 
    
            </td>
            </tr>  -->
            <?php
             }
            ?>
        </tr>          
        </tbody>
        </table>
        </div>

         <!-- Modal for Zone code A-->
        <!-- <div class="modal fade" id="ratefile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:40%">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Contract File of Customer - <?=$_GET['cust_code'];?></h5>
                <button type="button" style="margin-top:-25px" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <?php 
            $endfilename = explode(".",$custdetailsrow['rate_file_name']);
            $ext = end($endfilename);
            if($ext=='pdf'){
                echo '<embed style="width:100%;min-height:800px" src="data:application/pdf;base64,'. $custdetailsrow['rate_file'] .'"/>'; 
            }
            else{
                echo '<embed style="width:100%;" src="data:image/jpg;base64,'. $custdetailsrow['rate_file'] .'"/>'; 
            }
             ?>  <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div> -->
        <!-- End -->

        <div class="col-sm-12" style="float:none;margin-top:15px;">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Contract</p>
            
              <div class="align-right" style="margin-top:-33px;display: flex;
                width: auto;
                float: right;" id="buttons">
              <a class='btn btn-warning bulkpdfbtn btn-minier bootbox-confirm' id='".$row->con_code."' onclick='bulkprintpdf()' style="margin-right:5px" mode='$row->mode' destn='$tmp_destn'>
                                    <i class='ace-icon fa fa-print bigger-130'></i>
                                </a>
              <a class='btn btn-success btn-minier bulkexcelbox bootbox-confirm' id='".$row->con_code."' onclick='bulkexcel()' style="margin-right:5px" mode='$row->mode' destn='$tmp_destn'>
              
              <!-- <i class="fa fa-spinner bigger-130" aria-hidden="true"></i> -->

                <i class='ace-icon fa  fa-file-excel-o bigger-130'></i>
                                </a>
            </div>
        </div>
        <div style="background:#fff;border:1px solid var(--heading-primary);">
    <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="radio" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>            
                    <th >Contract Code</th>
                    <th >Origin</th>
                    <th >Destination</th>
                    <th >Mode</th>
                    <th >Action</th>
              </tr>
        </thead>
              <tbody>
                  <?php 
                 
                 $query2 = $db->query("SELECT * FROM rate_custcontract WHERE cust_code = '$cust_code' AND origin='$stationcode' AND stncode='$stationcode'");
                         while ($row = $query2->fetch(PDO::FETCH_OBJ))
                          {
                            //get zone name
                            $zonename = $db->query("SELECT zone_name FROM zone WHERE zone_type='$zntype' AND zone_code='$row->destn' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
                            
                            $tmp_destn = $row->destn."-".$zonename->zone_name;

                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='radio' name='chkIds[]' value='".$row->con_code."' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                            echo "<td>".$row->con_code."</td>";
                            echo "<td>".$row->origin."</td>";
                            echo "<td>".$tmp_destn."</td>";
                            echo "<td>".$row->mode."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                              
                                 <a class='btn btn-success btn-minier editbtn' id='".$row->con_code."' data-rel='tooltip' title='Edit'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>
                                 <a class='btn btn-danger btn-minier bootbox-confirm' id='rate_custcontract,$row->con_code,con_code' onclick='".delete($db,$user)."'>
                                    <i class='ace-icon fa fa-trash-o bigger-130'></i>
                                 </a>

                                 <a class='btn btn-primary btn-minier bootbox-confirm' id='".$row->con_code."' onclick='printpdf(this)' mode='$row->mode' destn='$tmp_destn'>
                                    <i class='ace-icon fa fa-print bigger-130'></i>
                                </a>
                                 <a  class='btn btn-warning btn-minier bootbox-confirm' id='".$row->con_code."' onclick='downloadexcel(this)' mode='$row->mode' destn='$tmp_destn'>
                                    <i class='ace-icon fa fa-file-pdf-o bigger-130'></i>
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
                                       <a  class='tooltip-success' data-rel='tooltip' title='Edit'>
                                        <span class='green'>
                                        <i class='ace-icon fa fa-pencil-square-o bigger-120'></i>
                                        </span>
                                       </a>
                                    </li>

                                  <li>
                                   <a  class='tooltip-error bootbox-confirm' data-rel='tooltip' title='Delete'>
                                   <span class='red'>
                                    <i class='ace-icon fa fa-trash-o bigger-120'></i>
                                    </span>
                                    </a>
                                  </li>
                                </ul>
                                              </div>
                                          </div>
                           </td>";
                            echo "</tr>";
                           
                          }                    
                       
   
                      ?>

                   
                 </tbody>
            </table>
              </div>
              </div>
              <div  class="col-sm-12" style="float:none;margin-top:15px">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Contract Detail</p>
           
        </div>
        <div style="background:#fff;border:1px solid var(--heading-primary);">
    <table id="ratedetail-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
                    <th></th>
                    <th >OnAdd</th>
                    <th >Lower Wt</th>
                    <th >Upper Wt</th>
                    <th >Rate</th>
                    <th >Multiple</th>
              </tr>
        </thead>
              <tbody id="detail_table">
                   
                 </tbody>
            </table>
              </div>
              </div>
        <!-- Rate Breakup form -->
        <div class="title_1 title panel-heading" style=" margin-top:15px;">
            <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Rate Entry Form</p> 
            <div class="align-right" style="margin-top:-35px;">

        <button type="button" class="btn btn-success btnattr" style="display:none" onclick="creatediv()">
        <i class="fa fa-plus" ></i>
        </button>
        <a href="customerrate" class="btn btn-success btnattr" data-toggle="tooltip" data-placement="bottom" title="Add New-Rate for customer <?=$cust_code; ?>"><i class="fa fa-plus" ></i></a>


  </div>    
        </div>
        <div class="col-sm-12" style="padding-right:15px;border:1px solid var(--heading-primary);background:#fff;float:none">
        <table class="table">
			<tbody>
                <tr>
                    <td>Origin</td>
                    <td>Zone</td>
                    <td>Destination</td>
                    <td></td>
                    <td>Mode</td>
                    <td>Calc Method</td>
                    <td>Spl Charge</td>
                </tr>  
                <tr>
                    <td class="col-sm-2">
                        <input class="form-control stncode"  name="origin" type="text" id="origin" >
                    </td>
                    <td>
                        <input type="radio" class="check" name="zone_dest" value="zone" id="zone">
                    </td>
                    <td class="col-sm-1">
                        <input type="radio"  class="check" name="zone_dest" value="destination" id="dest">
                    </td>
                    <td class="col-sm-2"><div class="autocomplete">
                        <input type="text"  name="zonedest" class="destination" id="zoneautocomplete">
                        <p id="zone_validation" class="form_valid_strings">Enter Valid Zone</p></div>
                        <div class="autocomplete">
                        <input type="text" class="destination" id="destinationautocomplete" style="display:none">
                        <p id="destination_validation" class="form_valid_strings">Enter Valid Destination</p></div>
                    </td>
                    <td class="col-sm-2">
                        <select class="form-control border-form"  name="mode"  id="mode">
                          <option id="modetd"></option>
                            <?php //echo loadmode($db); ?>
                        </select>
                    </td>
                    <td class="col-sm-2">
                        <select class="form-control border-form"  name="calcmethod"  id="calcmethod">
                            
                            <option value="Direct">Direct</option>
                            <option value="Indirect">Indirect</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control"  name="splcharges" type="text" id="splcharges">
                    </td>
				</tr>
			</tbody>
		    </table>
            <input type="hidden" id="updateconcode" name="updateconcode">
        <!-- </div>

            <div  class="col-sm-12" style="border :1px solid var(--heading-primary) ;background:#fff;margin-top:15px"> -->
          
            <table  id="t1" class="table custratedynamic" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td>On Add</td>
                    <td>Lower Wt</td>
                    <td>Upper Wt</td>
                    <td>Rate</td>
                    <td>Multiple</td>  
                </tr>
            <tbody  id="dynamic_form1">
                <tr>
                    <td><input class="rate_detil onadd0" onkeyup="onadd()" value="0" type="text" name="onadd[]"></td>
                    <td><input class="rate_detil" id="lower_wt1" value="0.001" type="text" name="lowerwt[]" readonly></td>
                    <td><input class="rate_detil" id="upper_wt1" onkeyup="upper()"  type="text" name="upperwt[]"></td>
                    <td><input class="rate_detil"  type="text" name="rate[]"></td>
                    <td><input class="rate_detil multiple0" value="0" type="text" name="multiple[]">
                    <td><span>
                    <button style='margin-left:0px;' type="button" class="btn btn-success btn-xs" onclick="generateweightband()"><i class="fa fa-plus-circle"></i></button>
                    </span></td>
                    </td>
                </tr>
            </tbody>
            </table>

            </div>
            <!-- Dynamic forms -->
           
            
            <div id="share">
               

            </div>
              <!-- end -->
         </div> 
         <div  style="background:#fff;padding:10px;margin-top:15px;border:1px solid var(--heading-primary) ">
         <table class="table">
                <tr>	           
                    <div class="align-right" style="margin-top:0;">
                    <button type="submit" class="btn btn-info btnattr_cmn" name="updatecustomerrate">
                    <i class="fa fa-save"></i>&nbsp; Update
                    </button>
                    <!-- <button type="submit" class="btn btn-success btnattr_cmn" name="addananothercustomerrate">
                    <i class="fa fa-save"></i>&nbsp; Save & Add
                    </button>

                    <button type="submit" class="btn btn-primary btnattr_cmn" name="addcustomerrate">
                    <i class="fa fa-save"></i>&nbsp; Save
                    </button> -->

                    <button type="button" class="btn btn-warning btnattr_cmn" onclick="reset()">
                    <i class="fa fa-eraser"></i>&nbsp; Reset
                    </button> 

                    </div>

                </tr>

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
     function printpdf(concode){
         var prntccode = $(concode).attr('id');
         var stnprint = $('#stncode').val();
         var custprint = $('#cust_code').val();
         var modeprint = $(concode).attr('mode');
         var destnprint = $(concode).attr('destn');
          var divToPrint=document.getElementById("data-table1");
          var headToPrint=document.getElementById("pdfheader");
          $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{contractcode1:prntccode,cont_stncode:stnprint},
            dataType:"text",
            success:function(detail1)
            {
            var frame1 = document.createElement('iframe');
            frame1.name = "frame1";
            frame1.style.position = "absolute";
            frame1.style.top = "-1000000px";
            document.body.appendChild(frame1);
            var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
            frameDoc.document.open();
            frameDoc.document.write('<html><head><title>RateContract</title>');
            frameDoc.document.write('</head><body>');
            //setting content
            frameDoc.document.write('<style> .tb1{width:60%} .tb2{width:100%} table tr td{padding: 3px !important;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 13px;} table tr th{border-bottom:1px solid black;border-top:1px solid black;text-align:left;}.header{height:40px;padding: 5px;}.printable{width:80%}</style>');
            frameDoc.document.write('<head><title>Contract - '+prntccode+'</title></head>');
            frameDoc.document.write('<div class="printable"><div class="header"><p style="font-size:18px">Customer - '+custprint+' , Contract - '+prntccode+'</p></div><table class="tb1">');
            frameDoc.document.write('<tr><th></th><th>Destination</th><th>Mode</th></tr><td><td>'+destnprint+'</td><td>'+modeprint+'</td></td></table>');
            frameDoc.document.write('<table class="tb2"><tr><th></th><th>On Add</th><th>Lower Wt</th><th>Upper Wt</th><th>Rate</th><th>Multiple</th></tr>');
            frameDoc.document.write(detail1);
            frameDoc.document.write('</table></div>');
            //setting content
            //frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                document.body.removeChild(frame1);
            }, 100);
            return false;
            }
            });
     }
</script>
<script>
     function bulkprintpdf(){
         $('.bulkpdfbtn').html('<i class="fa fa-spinner bigger-130" aria-hidden="true"></i>');
         var stnprint = $('#stncode').val();
         var custprint = $('#cust_code').val();
         var bprint_custzone = '<?=$zntype;?>';
          $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{bprintcustcode1:custprint,bprintcont_stncode:stnprint,bprint_custzone},
            dataType:"json",
            success:function(cust_contracts)
            {
            // console.log(cust_contracts);
            $('.bulkpdfbtn').html("<i class='ace-icon fa fa-print bigger-130'></i>");
            var frame1 = document.createElement('iframe');
            frame1.name = "frame1";
            frame1.style.position = "absolute";
            frame1.style.top = "-1000000px";
            document.body.appendChild(frame1);
            var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
            frameDoc.document.open();
            frameDoc.document.write('<html><head><title>RateContract</title>');
            frameDoc.document.write('</head><body>');
            for(var c=0;c<cust_contracts.length;c++){
            //setting content
            frameDoc.document.write('<style>  @media print{.printable{page-break-after: always;}}} .tb1{width:60%} .tb2{width:100%} table tr td{padding: 3px !important;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 13px;} table tr th{border-bottom:1px solid black;border-top:1px solid black;text-align:left;}.header{height:40px;padding: 5px;}.printable{width:80%}</style>');
            frameDoc.document.write('<head><title>Contract - '+cust_contracts[c].CONCODE+'</title></head>');
            frameDoc.document.write('<div class="printable"><div class="header"><p style="font-size:18px">Customer - '+custprint+' , Contract - '+cust_contracts[c].CONCODE+'</p></div><table class="tb1">');
            frameDoc.document.write('<tr><th></th><th>Destination</th><th>Mode</th></tr><td><td>'+cust_contracts[c].DESTN+'</td><td>'+cust_contracts[c].MODE+'</td></td></table>');
            frameDoc.document.write('<table class="tb2"><tr><th></th><th>On Add</th><th>Lower Wt</th><th>Upper Wt</th><th>Rate</th><th>Multiple</th></tr>');
            for(var s=0;s<cust_contracts[c].BANDS.length;s++){
            frameDoc.document.write('<tr><td></td><td>'+cust_contracts[c].BANDS[s].ONADD+'</td><td>'+cust_contracts[c].BANDS[s].LW+'</td><td>'+cust_contracts[c].BANDS[s].UW+'</td><td>'+cust_contracts[c].BANDS[s].RATE+'</td><td>'+cust_contracts[c].BANDS[s].MULTIPLE+'</td></tr>');
            }

            frameDoc.document.write('</table></div>');
            //setting content
            }
            //frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                document.body.removeChild(frame1);
            }, 100);
            return false;
            }
            });
     }
</script>
<script>
     function downloadexcel(concode){
         var prntccode = $(concode).attr('id');
         var stnprint = $('#stncode').val();
         var custprint = $('#cust_code').val();
         var modeprint = $(concode).attr('mode');
         var destnprint = $(concode).attr('destn');
        //   var divToPrint=document.getElementById("data-table1");
        //   var headToPrint=document.getElementById("pdfheader");
          $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{contractcode1:prntccode,cont_stncode:stnprint},
            dataType:"text",
            success:function(detail1)
            {
           
            var type = 'xlsx';
            var dl = '';
            var fn = '';

                //var elt =  document.createElementFromString('<table class="tb2"><tr><th></th><th>On Add</th><th>Lower Wt</th><th>Upper Wt</th><th>Rate</th><th>Multiple</th></tr></table>');
                var html = '<table class="tb2">';
                    html+= '<tr><td></td><th>Customer</th><td>'+custprint+'</td><th>Contract</th><td>'+prntccode+'</td></tr>';
                    html+= '<tr><td></td><th>Destination</th><th>Mode</th></tr>';
                    html+= '<tr><td></td><td>'+destnprint+'</td><td>'+modeprint+'</td></tr>';
                    html+= '<tr><th></th><th>On Add</th><th>Lower Wt</th><th>Upper Wt</th><th>Rate</th><th>Multiple</th></tr>';
                    html+= detail1;
                    html+= '</table>';

                var elt = document.createElementFromString(html);
                var report_type =  'Contract - '+prntccode; 
                var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet 1",header:1,raw:false,dateNF:'yyyy-mm-dd'});
                return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
                XLSX.writeFile(wb, fn || (report_type+'.' + (type || 'xlsx')));
            }
            });
          
     }

     Document.prototype.createElementFromString = function (str) {
   const element = new DOMParser().parseFromString(str, 'text/html');
   const child = element.documentElement.querySelector('body').firstChild;
   return child;
};
</script>
<script>
     function bulkexcel(){
        $('.bulkexcelbox').html('<i class="fa fa-spinner bigger-130" aria-hidden="true"></i>');
         var stnprint = $('#stncode').val();
         var custprint = $('#cust_code').val();
         var bprint_custzone = '<?=$zntype;?>';
          $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{bprintcustcode1:custprint,bprintcont_stncode:stnprint,bprint_custzone},
            dataType:"json",
            success:function(cust_contracts)
            {
            // console.log(cust_contracts);

            var type = 'xlsx';
            var dl = '';
            var fn = '';

           
            var html = '<table class="tb1">';
            for(var c=0;c<cust_contracts.length;c++){
            //setting content
            html +='<tr><th>Customer</th><td> '+custprint+' </td><td> Contract</td><td>'+cust_contracts[c].CONCODE+'</td></tr>';
            html +='<tr><th></th><th>Destination</th><th>Mode</th></tr><td><td>'+cust_contracts[c].DESTN+'</td><td>'+cust_contracts[c].MODE+'</td></td>';
            html +='<tr><th></th><th>On Add</th><th>Lower Wt</th><th>Upper Wt</th><th>Rate</th><th>Multiple</th></tr>';
            for(var s=0;s<cust_contracts[c].BANDS.length;s++){
            html +='<tr><td></td><td>'+cust_contracts[c].BANDS[s].ONADD+'</td><td>'+cust_contracts[c].BANDS[s].LW+'</td><td>'+cust_contracts[c].BANDS[s].UW+'</td><td>'+cust_contracts[c].BANDS[s].RATE+'</td><td>'+cust_contracts[c].BANDS[s].MULTIPLE+'</td></tr>';
            }
            html +='<tr></tr>';
            //setting content
            }
            html +='</table>';

            $('.bulkexcelbox').html("<i class='ace-icon fa  fa-file-excel-o bigger-130'></i>");

            var elt = document.createElementFromString(html);
                var report_type =  'Contracts of - '+custprint+' Customer'; 
                var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet 1",header:1,raw:false,dateNF:'yyyy-mm-dd'});
                return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
                XLSX.writeFile(wb, fn || (report_type+'.' + (type || 'xlsx')));
            
            }
            });
     }
</script>
<script>
  //Fetch Branch Name
  $('.ace').click(function(){
      var contractcode1 = $(this).val();
      var cont_stncode = $('#stncode').val();
      $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{contractcode1,cont_stncode},
            dataType:"text",
            success:function(detail1)
            {
            $('#detail_table').html(detail1);
            }
    });
    });
    </script>
<script>
 $('.editbtn').click(function(){
     var con_code = $(this).attr('id');
     var editcont_stncode = $('#stncode').val();
     $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{editcontract:con_code},
            dataType:"text",
            success:function(con_data)
            {
                var data = con_data.split(",");
                $('#origin').val(data[0]);
                $('.destination').val(data[1]);
                $('#calcmethodtd').html(data[3]);
                $('#calcmethodtd').val(data[3]);
                $('#modetd').html(data[2]);
                $('#modetd').val(data[2]);
                $('#splcharges').val(data[4]);
                $('#updateconcode').val(con_code);
            }
            });
     $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{editcontractbands:con_code,editcont_stncode},
            dataType:"text",
            success:function(bands)
            {
                $('#dynamic_form1').html(bands);
            }
            });
 });
</script>  
<script>
function onadd(){
$('.onadd0').keyup(function(){
    $('.multiple0').val($(this).val());
});
$('.onadd1').keyup(function(){
    $('.multiple1').val($(this).val());
});
$('.onadd2').keyup(function(){
    $('.multiple2').val($(this).val());
});
$('.onadd3').keyup(function(){
    $('.multiple3').val($(this).val());
});
$('.onadd4').keyup(function(){
    $('.multiple4').val($(this).val());
});
$('.onadd5').keyup(function(){
    $('.multiple5').val($(this).val());
});
$('.onadd6').keyup(function(){
    $('.multiple6').val($(this).val());
});
$('.onadd7').keyup(function(){
    $('.multiple7').val($(this).val());
});
}

</script> 
<script>
 //Dynamic field 
  var i=1;
  var j=1;
function generateweightband(){
     i++;
     $("#dynamic_form"+j+"").append('<tr id="weightrow"><td><input type="text" class="onadd'+i+'" value="0" onkeyup="onadd()" name="onadd[]"></td><td><input type="text"  id="lower_wt'+i+'" name="lowerwt[]" readonly></td><td><input type="text"  onkeyup="upper()"  id="upper_wt'+i+'"  name="upperwt[]" required></td><td><input type="text"  name="rate[]" required></td><td><input type="text" value="0" class="multiple'+i+'" name="multiple[]"></td><td><button type="button" style="margin-left:0px;margin-top:0px;" class="btn btn-danger btn-xs" onclick="remove(this)" id="remove" ><i class="fa fa-minus-circle"></i></button></td></tr>');
     }
     function remove()
     {
        if(confirm("Are You sure Want to delete") == true)
        {
            document.getElementById('weightrow').remove();
        }
        else{
            return false;
        }
    }

</script>
<!-- Dynamic Rate Breakup Form -->
<script>
    function creatediv(){
        j++;
    $("#share").append('<div id="dynamicratebreakup"><div class="title_1 panel-heading" style=" margin-top:15px;"><p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Rate Breakup Form ('+j+')</p><div class="align-right" style="margin-top:-35px;"><button type="button" class="btn btn-danger btnattr" onclick="removeratediv()"><i class="fa fa-times" ></i></button></div></div><div class="col-sm-12" style="padding-right:15px;float:none;border:1px solid var(--heading-primary);background:#fff"><table class="table"><tbody><tr><td>Origin</td>                 <td>Zone</td>                    <td>Destination</td> <td></td><td>Mode</td><td>Calc Method</td><td>Spl Charge</td></tr><tr>        <td class="col-sm-2">         <input class="form-control stncode"  name="origin" type="text" id="origin" >       </td>               <td>                <input type="radio" class="check" name="zone_dest" value="zone" id="zone"> </td><td class="col-sm-1">             <input type="radio"  class="check" name="zone_dest" value="destination" id="dest">       </td>        <td class="col-sm-2"><div class="autocomplete">   <input type="text"  name="zonedest" class="destination" id="zoneautocomplete">   <p id="zone_validation" class="form_valid_strings">Enter Valid Zone</p></div>   <div class="autocomplete">   <input type="text" class="destination" id="destinationautocomplete" style="display:none">          <p id="destination_validation" class="form_valid_strings">Enter Valid Destination</p></div>  </td>      <td class="col-sm-2">               <select class="form-control border-form "  name="mode"  id="mode" >       <option value="0">Select Mode</option>          <?php echo loadmode($db); ?>          </select>              </td>            <td class="col-sm-2">             <select class="form-control border-form "  name="calcmethod"  id="calcmethod"><option value="Direct">Direct</option><option value="Indirect">Indirect</option>  </select>    </td>    <td>                    <input class="form-control "  name="splcharges" type="text" id="splcharges"> </td></tr></tbody></table><table  id="t1" class="table custratedynamic" cellspacing="0" cellpadding="0" align="center"><tr><td>On Add</td> <td>Lower Wt</td>       <td>Upper Wt</td><td>Rate</td>      <td>Multiple</td>  </tr><tbody  id="dynamic_form'+j+'">  <tr>            <td><input class="rate_detil" value="0.00" type="text" name="onadd[]"></td>    <td><input class="rate_detil" id="lower_wt1" value="0.001" type="text" name="lowerwt[]" readonly></td>         <td><input class="rate_detil" id="upper_wt1" onkeyup="upper()" value="0.00" type="text" name="upperwt[]"></td>           <td><input class="rate_detil" value="0.00" type="text" name="rate[]"></td>           <td><input class="rate_detil" value="0.00" type="text" name="multiple[]">      <td><span>    <button style="margin-left:0px;" type="button" class="btn btn-success btn-xs" onclick="generateweightband()"><i class="fa fa-plus-circle"></i></button>          </span></td>    </td>     </tr>  </tbody> </table></div>');
    }
    function removeratediv(){
        $("#dynamicratebreakup").remove();
    }
</script>

<script>
            //zone,dest
            $('#zone').click(function(){
                //alert("zone clicked");
                $('#zoneautocomplete').css('display','block');
                $('#zoneautocomplete').attr('name','zonedest');
                $('#destinationautocomplete').css('display','none');
                $('#destinationautocomplete').attr('name','');
                $('#destination_validation').css('display','none');
            }); 
            $('#dest').click(function(){
                //alert("Destination clicked");
                $('#zoneautocomplete').css('display','none');
                $('#zoneautocomplete').attr('name','');
                $('#destinationautocomplete').css('display','block');
                $('#destinationautocomplete').attr('name','zonedest');
                $('#zone_validation').css('display','none');
            }); 
</script> 
<script>
//Generate Lower weight after entered upper weight
function upper(){
    var upper1 = document.getElementById('upper_wt1').value;
    var lower1 = parseFloat(upper1)+.001;
    if(!isNaN(lower1)){
    document.getElementById('lower_wt2').value=lower1;
    }

    var upper2 = document.getElementById('upper_wt2').value;
    var lower2 = parseFloat(upper2)+.001;
    if(!isNaN(lower2)){
    document.getElementById('lower_wt3').value=lower2;
    }

    var upper3 = document.getElementById('upper_wt3').value;
    var lower3 = parseFloat(upper3)+.001;
    if(!isNaN(lower3)){
    document.getElementById('lower_wt4').value=lower3;
    }

    var upper4 = document.getElementById('upper_wt4').value;
    var lower4 = parseFloat(upper4)+.001;
    if(!isNaN(lower4)){
    document.getElementById('lower_wt5').value=lower4;
    }

    var upper5 = document.getElementById('upper_wt5').value;
    var lower5 = parseFloat(upper5)+.001;
    if(!isNaN(lower5)){
    document.getElementById('lower_wt6').value=lower5;
    }
}


</script>
<script>
//Fetch Branch Name
// $('#br_code').keyup(function(){
//         var branchlookup = $('#br_code').val();
//         $.ajax({
//             url:"<?php echo __ROOT__ ?>cnotecontroller",
//             method:"POST",
//             data:{branchlookup:branchlookup},
//             dataType:"text",
//             success:function(data)
//             {
//             $('#br_name').val(data);
//             }
//         });
//     });
</script>
<script> 
    // $(document).ready(function(){
    //     var cust_rate = $('.cust_code').val();
    //     var getzn_stn = '<?=$stationcode?>';
    //     $.ajax({
    //         url:"<?php echo __ROOT__ ?>ratecontroller",
    //         method:"POST",
    //         data:{cust_destn:cust_rate,comp_destn:getzn_stn},
    //         dataType:"json",
    //         success:function(zoneautocomplete)
    //         {
    //             autocomplete(document.getElementById("zoneautocomplete"), zoneautocomplete); 
    //         }
    //     });
    //     });

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
        $('#br_code').keyup(function(){
            var br_code = $('#br_code').val().toUpperCase().trim();
            if(branches[br_code]!==undefined){
                $('#brcode_validation').css('display','none');
                $('#br_name').val(branches[br_code]);
                getcustomersjson();
            }
            else{
                $('#brcode_validation').css('display','block');
            }
        });
        //fetch code of entering name
        $('#br_name').keyup(function(){
            var br_name = $(this).val().trim();
            var gccode = getKeyByValue(branches,br_name);
            
            if(gccode!==undefined){
                $('#brcode_validation').css('display','none');
                $('#br_code').val(gccode);
                getcustomersjson();
            }
            else{
                $('#brcode_validation').css('display','block');
            }
           
        });
    });
</script>
<script> 
customers = {}

function getcustomersjson(){
   //get customers json
   var branch8 = $('#br_code').val().toUpperCase();
   var comp8 = $('#stncode').val().toUpperCase();
   $.ajax({
       url:"<?php echo __ROOT__ ?>billingcontroller",
       method:"POST",
       data:{getjsoncust_br:branch8,getjsoncust_stn:comp8},
       dataType:"text",
       success:function(customerjson)
       {
          customers = JSON.parse(customerjson);
       }
       });
    }
</script>
<script>
    function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
</script>
<script> 

$(document).ready(function(){
     $('#cust_code').keyup(function(){
         var cust_code = $('#cust_code').val().toUpperCase().trim();
        if(customers[cust_code]!==undefined){
            $('#cust_name').val(customers[cust_code]);
            $('#custcode_validation').css('display','none');
        }
        else{
            $('#custcode_validation').css('display','block');
        }
     });
     //fetch code of entering name
     $('#cust_name').keyup(function(){
         var cust_name = $(this).val();
         var ccode = getKeyByValue(customers,cust_name);

         if(ccode!==undefined){
            $('#cust_code').val(ccode);
            $('#custcode_validation').css('display','none');
            }
            else{
                $('#custcode_validation').css('display','block');
            }
     });
});

</script>
<script>

    $(document).ready(function(){
      
        $('.cust_code').keyup(function(){
        var cust_rate = $('.cust_code').val();
        var getzn_stn = '<?=$stationcode?>';

        $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{cust_rate,getzn_stn},
            dataType:"text",
            success:function(value)
            {
            var data = value.split(",");
            $('#date_contract').val(data[0]);
            $('#contract_period').val(data[1]);
            //$('#cust_name').val(data[6]);
            $('#zone_destn').val(data[7]);
            }
    });

        $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{cust_destn:cust_rate,comp_destn:getzn_stn},
            dataType:"json",
            success:function(zoneautocomplete)
            {
                autocomplete(document.getElementById("zoneautocomplete"), zoneautocomplete); 
            }
        });
    });
    });
//Verify Destination is Valid
    $('#destinationautocomplete').keyup(function(){
        var dest_code1 = $('#destinationautocomplete').val().split('-')[0];
        $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{dest_code1:dest_code1},
            dataType:"text",
            success:function(dest)
            {
              if(dest == 0){
              $('#destination_validation').css('display','block');
              }
              else{
              $('#destination_validation').css('display','none');
               }
            }
    });
    });
    //Verify Zone is Valid
    $('#zoneautocomplete').keyup(function(){
        var zone_code1 = $('#zoneautocomplete').val().split('-')[0];
        var cust_zone = $('.cust_code').val();
        var zoneval_stn = $('#stncode').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{zone_code1:zone_code1,cust_zone:cust_zone,zoneval_stn},
            dataType:"text",
            success:function(zne)
            {
              if(zne == 0){
              $('#zone_validation').css('display','block');
              }
              else{
              $('#zone_validation').css('display','none');
               }
            }
    });
    // if($('.cust_code').val().trim()==''){
    //     $('#custempty_validation').css('display','block');
    // }
    // else{
    //     $('#custempty_validation').css('display','none');
    // }
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
                    $('#origin').val(data[0]);
                }
                });
            }
            }
    });
  });
 </script>
 <script>


var destinationautocomplete =[<?php  
    $sql=$db->query("SELECT DISTINCT dest_code,dest_name FROM destination where comp_code='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['dest_code']."-".$row['dest_name']."', ";
    }
 ?>];
</script>

<script>
   autocomplete(document.getElementById("destinationautocomplete"), destinationautocomplete);
</script>
<script>
  var branchcode =[<?php  

    $sql=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
    while($row=$sql->fetch(PDO::FETCH_ASSOC))
    {
    echo "'".$row['br_code']."',";
    }

 ?>];


</script>
<script>
autocomplete(document.getElementById("br_code"), branchcode);

</script>
<script>

$('.br_code').keyup(function(){
   var branch8 = $('.br_code').val();
   var comp8 = $('.stncode').val();
   $.ajax({
       url:"<?php echo __ROOT__ ?>ratecontroller",
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
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('br_name').value = "";
    document.getElementById('cust_code').value = "";
    document.getElementById('cust_name').value = "";
    document.getElementById('zoneautocomplete').value = "";
    document.getElementById('destination_validation').value = "";
    document.getElementById('mode').value = "";
    document.getElementById('calcmethod').value = "";
    document.getElementById('splcharges').value = "";   
}

function deleterecord(id){

swal({
title: "Are you sure?",
text: "Once deleted, you will not be able to recover this record file!",
icon: "warning",
buttons: true,
dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    var datas = id.split(",");
    var deletetb = datas[0];
    var deletecol = datas[2];
    var deleterow = datas[1];
    var delete_user = $('#user_name').val();
    var delete_station = $('#stncode').val();
    $.ajax({
              url:"<?php echo __ROOT__ ?>ratecontroller",
              method:"POST",
              data:{deletetb:deletetb,deleterow:deleterow,deletecol:deletecol,delete_user,delete_station},
              dataType:"text",
              success:function(value)
              {
                location.reload();
              }
      });
    swal("Your Record has been deleted!", {
      icon: "success",
    });
  } else {
    swal("Your Record is safe!");
  }
});

} 
</script>
<script>
     var _validFileExtensions = [".jpg", ".pdf"];    
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
<script>
    function deletecontractfile(){
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this record file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $('#rate_contract_file_name').val('');

            } else {
                swal("Your Record is safe!");
            }
            });
    }
</script>