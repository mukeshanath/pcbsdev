<?php defined('__ROOT__') OR exit('No direct script access allowed');

include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
}

function authorize($db,$user)
{
    
    $module = 'customerlist';
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
    $module = 'customerdelete';
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
                                        <a href="<?php echo __ROOT__ . 'customerlist'; ?>">Customer List</a>
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
                        Customer Added successfully.
                      </div>";
        }

        if ( isset($_GET['success']) && $_GET['success'] == 2 )
        {
            echo 

        "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Customer updated successfully.
                      </div>";
                      echo "<meta http-equiv='refresh'>";
        }

        ?>
                
             </div>
  
    
      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
          <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>customer"><button type="button" class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>

           </div>
        </div>

       
      <div class="content panel-body">
      

      <div class="tab_form">
        
        

       <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->

       <form method="POST">

       <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
            <td>Customer Code</td>
            <td>
            <input placeholder="" class="form-control " value="<?php if(isset($_POST['cust_code'])){ echo  $_POST['cust_code']; } ?>" name="cust_code" type="text" id="cust_code">

            </td>
                
            <td>Customer Name</td>
            <td>
                <input class="form-control border-form" value="<?php if(isset($_POST['cust_name'])){ echo  $_POST['cust_name']; } ?>" name="cust_name" type="text" id="cust_name">
            </td>
            <td>Mobile</td>
            <td>
            <input placeholder="" class="form-control border-form" value="<?php if(isset($_POST['cust_mobile'])){ echo  $_POST['cust_mobile']; } ?>" name="cust_mobile" type="text" id="cust_mobile">
            </td>
            <td>Active</td>
            <td>
            <select class="form-control border-form" name="cust_active" id="cust_active">
            
                  <option value="Y">Yes</option>
                  <option value="N">No</option>
                  </select>
            </td>		
            </tr>
            
       </tbody>
     </table>
     <table>

        <div class="align-right btnstyle" >

        <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtercustomer">
        Apply <i class="fa fa-filter bigger-110"></i>                         
        </button> 

        <button class="btn btn-warning btnattr_cmn" type="button" id="filter-btn"  onclick="resetFunction()" >
        Reset <i class="fa fa-refresh bigger-110"></i>                         
        </button> 
        </div>
        </table>
      </div>
     </form >

<!-- Fetching Mode Details in Table  -->
<div class="col-12 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer List</p>
              <div class="align-right" style="margin-top:-33px;" id="print">
                <div class="dt-buttons"> 
                    <button class="dt-button buttons-excel buttons-html5" aria-controls="data-table" type="button" onclick="$('#excel').submit();"><span><img data-toggle="tooltip" title="Export to EXCEL" style="width:100%;" src="vendor/bootstrap/icons/ms-excel.png"></span></button> 
                    <button class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="data-table" type="button" onclick="$('#pdf').submit();"><span><img data-toggle="tooltip" title="Export to PDF" style="height:90%;" src="vendor/bootstrap/icons/pdf.png"></span></button> 
                </div>
              </div>
        </div>
        <div class="table-search">
               Show<select>
                 <option>10</option> 
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text" onkeyup="customerlistsearch()"></div>
           </div>
<table class="table data-table" cellspacing="0" cellpadding="0" align="center">
        <thead><tr>
              <th class="center">
                  <label class="pos-rel">
                      <input type="checkbox" class="ace" />
                  <span class="lbl"></span>
                  </label>
                </th>
                <th>Customer code</th>
                <th style="width:150px;">Customer Name</th>
                <th>Branch Code</th>
                <th>Branch Name</th>
                <th>GSTIN</th>
                <th>Contract Date</th>
                <th>Periods</th>
                <th>Active</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody id="customerlist_tbody">
                 <?php 
                     //Filter Button Event
                     $getuserstation = $db->query("SELECT * FROM users WHERE user_name='$user'");
                     $datafetch = $getuserstation->fetch(PDO::FETCH_ASSOC);
                     $rowuser = $datafetch['last_compcode'];
                     if(!empty($rowuser)){
                           $stationcode = $rowuser;
                           //$stn_condition = "WHERE comp_code='$stationcode'";
                     }
                     else{
                         $userstations = $datafetch['stncodes'];
                         $stations = explode(',',$userstations);
                         $stationcode = $stations[0];  
                         //$stn_condition = "WHERE comp_code='$stationcode'";   
                     }

                     $conditions = array();

                     //branch filter conditions
                    $sessionbr_code = $datafetch['br_code'];
                    if(!empty($sessionbr_code)){
                        $br_code = $sessionbr_code;
                        $conditions[] = "br_code='$br_code'";
                     }
                    //branch filter conditions

                     if(isset($_POST['filtercustomer']))
                     {    
                          $cust_code     = $_POST['cust_code'];
                          $cust_name     = $_POST['cust_name'];
                          $cust_mobile   = $_POST['cust_mobile'];                         
                          $active        = $_POST['cust_active']; 


                          if(! empty($cust_code)) {
                            $conditions[] = "cust_code LIKE '$cust_code%'";
                          } 
                          if(! empty($cust_name)) {
                            $conditions[] = "cust_name LIKE '$cust_name%'";
                          }
                          if(! empty($cust_mobile)) {
                             $conditions[] = "cust_mobile LIKE '$cust_mobile%'";
                           }
                          
                           if(! empty($active)) {
                             $conditions[] = "cust_active='$active'";
                           }

                           if(!empty($stationcode)) {
                             $conditions[] = "comp_code='$stationcode'";
                           }

                           $conditons = implode(' AND ', $conditions);

                           if (count($conditions) > 0) {
                               $sqlquery = "SELECT * FROM customer WHERE  $conditons AND flag!=0 ORDER BY cusid DESC OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY";
                               $query = $db->query($sqlquery); 
                           }
                           if (count($conditions) == 0) {
                             $sqlquery = "SELECT * FROM customer WHERE $conditons AND flag!=0 ORDER BY cusid DESC OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY";
                             $query = $db->query($sqlquery); 
                         }
                     }
                     //Normal Station list
                     
                     else{
                        $conditions[] = "comp_code='$stationcode'";

                        $conditons = implode(' AND ', $conditions);

                        $sqlquery = "SELECT * FROM customer WHERE $conditons AND flag!=0 ORDER BY cusid DESC OFFSET  0 ROWS  FETCH NEXT 10 ROWS ONLY";
                        $query = $db->query($sqlquery);
                     }
                     if($query->rowCount()==0)
                     {
                       echo "";
                     }
                     else{
                
                   
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                            echo "<td>".$row->cust_code."</td>";
                            echo "<td>".$row->cust_name."</td>";
                            echo "<td>".$row->br_code."</td>";
                            echo "<td>".$row->br_name."</td>";
                            echo "<td>".$row->cust_gstin."</td>"; 
                            echo "<td>".$row->date_contract."</td>";                            
                            echo "<td>".$row->contract_period."</td>";                          
                            echo "<td>".$row->cust_active."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                                <a  class='btn btn-primary btn-minier' data-rel='tooltip' title='View' href='customerdetail?cusid=$row->cusid&cust_code=$row->cust_code'>
                                  <i class='ace-icon fa fa-eye bigger-130'></i>
                                </a>

                                 <a class='btn btn-success btn-minier' href='customeredit?cusid=$row->cusid&cust_code=$row->cust_code' data-rel='tooltip' title='Edit'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>
                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='customer,$row->cusid,cusid' onclick='".delete($db,$user)."'>
                                    <i class='ace-icon fa fa-trash-o bigger-130'></i>
                                 </a>
                               </div>
                           </td>";
                                   echo "</tr>";
                           
                          }                        
                          }

                          
                      ?>
                 </tbody>
            </table>
            <div class="table-paginate-btns">
              <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM customer WHERE $conditons")->fetch(PDO::FETCH_OBJ);
                for($i=1;$i<=ceil($noofpages->pages / 10);$i++){
                  //pagination control
                  if(ceil($noofpages->pages / 10)>5){
                    //dot property
                    if($i==3){
                      $leftdot = '...';
                    }
                    else{
                      $leftdot = '';
                    }
                    if($i==ceil($noofpages->pages / 10)-1){
                      $rightdot = '...';
                    }
                    else{
                      $rightdot = '';
                    }
                    //end dot property
                    //number btn display property
                    if($i==1||$i==2||$i==ceil($noofpages->pages / 10)||$i==round(ceil($noofpages->pages / 10)/2)||$i==round(ceil($noofpages->pages / 10)/2)-1||$i==round(ceil($noofpages->pages / 10)/2+1)){
                      $style = "style='display:'";
                    }else{
                      $style = "style='display:none'";
                    }
                    //end number btn display property
                  }
                  else{
                    $leftdot = '';
                    $rightdot = '';
                    $style= '';
                  }
                  //end pagination control
                  echo "$leftdot<input type='button' $style class='btn btn-primary paginate-btn-nums' value='$i' id='$i'>$rightdot";
                }
              ?>
              <button type="button" class="btn btn-primary paginate-btn-nums" id="next" value="2">Next</button>
            </div>
           
              </div>
             
              </div>
             
          </div>
          </table> 

<!-- export to excel -->
<form id="excel" method="POST" action="<?php echo __ROOT__ ?>mastercontroller">
<input type="text" name="excel_query" id="excel_query" class="hidden" value="<?=explode('OFFSET',$sqlquery)[0]?>" style="width:100%">
<input type="text" name="excel_columns" id="excel_columns" class="hidden" value="comp_code,cust_code,cust_name,br_code,br_name,cust_gstin,date_contract,contract_period,cust_active" style="width:100%">
</form>
<!-- export to excel -->
<!-- export to pdf -->
<form id="pdf" method="POST" action="<?php echo __ROOT__ ?>mastercontroller">
<input type="text" name="pdf_query" id="pdf_query" class="hidden" value="<?=explode('OFFSET',$sqlquery)[0]?>" style="width:100%">
<input type="text" name="pdf_columns" id="pdf_columns" class="hidden" value="comp_code,cust_code,cust_name,br_code,br_name,cust_gstin,date_contract,contract_period,cust_active" style="width:100%">
</form>
<!-- export to pdf -->

</section>

<script>
function resetFunction() {
    document.getElementById('cust_code').value = "";
    document.getElementById('cust_name').value = "";    
    document.getElementById('cust_mobile').value = "";   
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
      $.ajax({
                url:"<?php echo __ROOT__ ?>mastercontroller",
                method:"POST",
                data:{deletetb:deletetb,deleterow:deleterow,deletecol:deletecol},
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
<!-- custom datatable -->
<script>
  $('.paginate-btn-nums').click(function(){
    var inc = parseInt($(this).val())+1;
            var dec = parseInt($(this).val())-1;
            var curt = parseInt($(this).val());
            var end = parseInt(<?php echo ceil($noofpages->pages / 10); ?>);
            if($(this).val()==0||$(this).val()>end){
              process.end;
            }
    $('.paginate-btn-nums').removeClass('active');
      var dtbquery = "SELECT * FROM customer WHERE <?=$conditons;?> AND flag!=0  ORDER BY cusid DESC";
      var dtbcolumns = 'cust_code,cust_name,br_code,br_name,cust_gstin,date_contract,date_contract,contract_period,cust_active';
      var dtbpageno = ($(this).val()-1)*10;
      var sessionuser = $('#user_name').val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,sessionuser,datatable_page:'customerlist',datatable_module:'customerdelete'},
            dataType:"text",
            success:function(data){
              // console.log(data);
              $('#customerlist_tbody').html(data);
            }
          }); 
          //pagination dynamic buttons
            if(end>5){
              $('#'+curt).addClass('active');
              $('.paginate-btn-nums').css('display','none');
              $('#'+curt).css('display','');
              $('#1').css('display','');
              $('#'+inc).css('display','');
              $('#'+dec).css('display','');
              // $('#'+dec).css('display','');
              $('#'+end).css('display','');
              $('#'+end).css('display','');
              $('#prev').css('display','');
              $('#prev').val(dec);
              $('#next').css('display','');
              $('#next').val(inc);
            }
          //end pagination dynamic buttons
  });
</script>
<script>
//datatable search
  function customerlistsearch(){
    
      var fieldvalue = $('.table-search-right-text').val();
      sessionStorage.setItem("customerlist", fieldvalue);
      var dtbquery = "SELECT * FROM customer WHERE <?=$conditons;?> AND cust_code LIKE '"+fieldvalue.trim()+"%' AND flag!=0  ORDER BY cusid DESC";
      var dtbcolumns = 'cust_code,cust_name,br_code,br_name,cust_gstin,date_contract,date_contract,contract_period,cust_active';
      var dtbpageno = '0';
      var sessionuser = $('#user_name').val();
          $.ajax({
            url:"<?php echo __ROOT__ ?>mastercontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns,sessionuser,datatable_page:'customerlist',datatable_module:'customerdelete'},
            dataType:"text",
            success:function(data){
              $('#customerlist_tbody').html(data);
            }
          }); 
  }
  </script>
  <script>
  $('.table-search-right-text').val(sessionStorage.getItem("customerlist").trim());
  customerlistsearch();
  </script>