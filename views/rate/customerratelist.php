<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'customerratelist';
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
                if ( isset($_GET['success']) && $_GET['success'] == 3 )
                {
                     echo 
                "<div class='alert alert-success alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                Customer Rate Record Updated successfully.
                </div>";
                }
                ?>

                </div>

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Rate</p>
         <div class="align-right" style="margin-top:-35px;">

        <a style="color:white" href="<?php echo __ROOT__ ?>customerrate"><button type="button" class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
         <i class="fa fa-times" ></i>
        </button></a>

          </div>
        </div>

        
      <div class="content panel-body">
      

        <div class="tab_form">   

        <div id="form_error" class="alert alert-danger fade in errMrg" style="display: none;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <span class="error" style="color : #fff;"></span>
        </div>  

       <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7 " id="error-panel"></div><!-- Error or Warning display panel -->

       <form method="POST">

       <input placeholder="" class="form-control border-form" name="user_name" type="hidden" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
            <tr>		
            <td>Origin</td>
            <td>
            <input value="<?php if(isset($_POST['mode'])){ echo $_POST['mode']; } ?>" class="form-control border-form" name="mode" type="text" id="mode">
            </td>  
            <td>Rate Code</td>
            <td>
                <input  value="<?php if(isset($_POST['cust_rate_id'])){ echo $_POST['cust_rate_id']; } ?>" class="form-control border-form" name="cust_rate_id" type="text" id="cust_rate_id" > 
            </td>
            <td>Customer Code</td>
            <td>
                <input value="<?php if(isset($_POST['cust_code'])){ echo $_POST['cust_code']; } ?>" class="form-control " name="cust_code" type="text" id="cust_code'">
            </td>
            
            <!-- <td>Destination</td>
            <td>
                 <input placeholder="" class="form-control border-form" name="destn" type="text" id="destn">
            </td>		 -->
            </tr>
            
       </tbody>
     </table>
     <table>

        <div class="align-right btnstyle" >

        <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtercustomerrate">
        Apply <i class="fa fa-filter bigger-110"></i>                         
        </button> 

        <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()">
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button> 

        </div>
        </table> 

         

     </form>

     </div> 

<!-- Fetching Mode Details in Table  -->
<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Customer Rate List</p>
              <div class="align-right" style="margin-top:-33px;" id="listbuttons"></div>
        </div>
         <!-- pagination search  -->


         <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text"></div>
           </div>
           <!-- pagination search  -->
<table id="ratelist-table1" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="radio" class="ace" />
                <span class="lbl"></span>
                </label>
              </th> <th >Origin</th>          
                    <!-- <th >Rate Code</th> -->
                    <th >Customer Code</th>
                    
                    <!-- <th>Effective Date</th> -->
                    <th >Action</th>
              </tr>
    </thead>
              <tbody id="datatable-tbody">
                  <?php 

                     //Filter Button Event
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
                      $conditions = array();

                     
                     if(isset($_POST['filtercustomerrate']))
                     {    
                          $cust_rate_id      = $_POST['cust_rate_id'];
                          $cust_code       = $_POST['cust_code'];
                          $mode           = $_POST['mode'];
                           

                       

                          if(! empty($cust_rate_id)) {
                            $conditions[] = "cust_rate_id LIKE '$cust_rate_id%'";
                          }
                          if(! empty($cust_code)) {
                            $conditions[] = "cust_code LIKE '$cust_code%'";
                          }
                          if(! empty($mode)) {
                            $conditions[] = "mode LIKE '$mode%'";
                          }
                          
                           

                           $conditons = implode(' AND ', $conditions);

                           if (count($conditions) > 0) {
                               $query = $db->query("SELECT DISTINCT cust_code,cust_rate_id,origin FROM rate_custcontract  WHERE  $conditons OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                           }
                           if (count($conditions) == 0) {
                             $query = $db->query("SELECT DISTINCT cust_code,cust_rate_id,origin FROM rate_custcontract WHERE origin='$stationcode' ORDER BY custcontid DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                         }
                     }
                     //Normal Station list
                     else{
                      $conditions[] = "origin='$stationcode' AND stncode='$stationcode'";
                      $conditons = implode(' AND ', $conditions);
                     
                         $query = $db->query("SELECT DISTINCT cust_code,cust_rate_id,origin FROM rate_custcontract WHERE $conditons ORDER BY cust_rate_id DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");
                     }
                     if($query->rowcount()==0)
                     {
                       echo "";
                     }
                     else{
                
                         while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            // $no_of_contracts  = $db->query("SELECT COUNT ( * ) as 'no_of_contract' FROM rate_custcontract WHERE cust_code='$row->cust_code'");
                            // $row_nocontracts = $no_of_contracts->fetch(PDO::FETCH_ASSOC);
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                       <input type='radio' name='contractcode' value='' class='ace' />
                                        <span class='lbl'></span>
                                     </label>
                                  </td>";
                            echo "<td>".$row->origin."</td>";
                            //echo "<td>".$row->cust_rate_id."</td>";
                            echo "<td>".$row->cust_code."</td>";
                            
                            // echo "<td>".$row->eff_date."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                              
                                 <a class='btn btn-success btn-minier' href='customerratedetail?cust_code=".$row->cust_code."' data-rel='tooltip' title='Edit'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>
              
                                 <a  class='btn btn-danger btn-minier bootbox-confirm' >
                                    <i class='ace-icon fa fa-trash-o bigger-130'></i>
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
                          //  echo " <!-- Modal -->
                          //  <div class='modal fade' id='$row->con_code' role='dialog'>
                          //    <div class='modal-dialog'  style='height:500px;'>
                             
                          //      <!-- Modal content-->
                          //      <div class='modal-content' style='margin-top:-100px;width:700px;'>
                          //        <div class='modal-header'>
                          //          <button type='button' class='close' data-dismiss='modal'>&times;</button>
                          //          <h4 class='modal-title'>Rate File of Customer <strong>$row->cust_code</strong></h4>
                          //        </div>
                          //        <div class='modal-body'>
                          //          <embed src='$row->ratefile' style='width:100%;min-height:400px'>
                          //        </div>
                          //        <div class='modal-footer'>
                          //          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                          //        </div>
                          //      </div>
                               
                          //    </div>
                          //  </div>";
                                                    
                          }
                        }

                          
                      ?>

                   
                 </tbody>
            </table>
               <!-- pagination -->
               <div class="table-paginate-btns">
            <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>

              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM (SELECT DISTINCT cust_code FROM  rate_custcontract WHERE $conditons) as pages")->fetch(PDO::FETCH_OBJ);
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
            <!-- pagination -->
              </div>
              
          </div>
          </div>
          </div>
</section>

    <script>
  //Fetch Branch Name
  $('.ace').click(function(){
      var contractcode1 = $(this).val();
      $.ajax({
            url:"<?php echo __ROOT__ ?>ratecontroller",
            method:"POST",
            data:{contractcode1:contractcode1},
            dataType:"text",
            success:function(detail1)
            {
            $('#detail_table').html(detail1);
            }
    });
    $('#mode').val(contractcode1);
    });
    </script>

<script>
function resetFunction() {
    document.getElementById('mode').value = "";
    document.getElementById('cust_code').value = "";    
    document.getElementById('cust_rate_id').value = "";   
}
</script>


<script>
  $('.paginate-btn-nums').click(function(){
    //console.log("dsds");

    var inc = parseInt($(this).val())+1;
            var dec = parseInt($(this).val())-1;
            var curt = parseInt($(this).val());
            var end = parseInt(<?php echo ceil($noofpages->pages / 10); ?>);
            if($(this).val()==0||$(this).val()>end){
              process.end;
            }
    $('#datatable-tbody').css('opacity','0.5');
    $('.paginate-btn-nums').removeClass('active');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT DISTINCT cust_rate_id,cust_code,origin FROM rate_custcontract WHERE <?=$conditons;?> ORDER BY cust_rate_id DESC";
    console.log(dtbquery)
      var dtbcolumns = 'cust_rate_id,cust_code,origin';
      
      var dtbpageno = ($(this).val()-1)*10;
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns},
            dataType:"json",
            success:function(datum){
              console.log(datum);
            $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var datacount = Object.keys(datum).length;
            var html='';
            for(var i=1;i<=datacount;i++){
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['origin']+"</td>";
              html += "<td>"+datum[i]['cust_code']+"</td>";
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              // html += "<a class='btn btn-primary btn-minier' href='creditcnotedetail?billed="+datum[i]['cust_code']+"&date="+datum[i]['date_added']+"' data-rel='tooltip' title='View'><i class='ace-icon fa fa-eye bigger-130'></i></a>";
              html += "<a class='btn btn-success btn-minier' href='customerratedetail?cust_code="+datum[i]['cust_code']+"' data-rel='tooltip' title='Edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";
             
              html += "<a  class='btn btn-danger btn-minier bootbox-confirm' ><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
              //action
              html += "</div></td></tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
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
            else{
              $('#'+curt).addClass('active');
              $('#prev').val(dec);
              $('#next').val(inc);
            }
          //end pagination dynamic buttons
  });
</script>


<script>
  $('.table-search-right-text').keyup(function(event){
    // event.preventDefault();
    var fieldvalue = $(this).val();
      sessionStorage.setItem("customerratelist", fieldvalue);
      
      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT DISTINCT cust_rate_id,cust_code,origin FROM rate_custcontract WHERE (cust_code like '%"+fieldvalue+"%' OR origin LIKE '%"+fieldvalue+"%') AND <?=$conditons;?> ORDER BY cust_rate_id DESC";
      console.log(dtbquery);
      var dtbcolumns = 'cust_rate_id,cust_code,origin';
      var dtbpageno = '0';
      console.log(dtbpageno);
     
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>invoicecontroller",
            method:"POST",
            data:{dtbquery,dtbpageno,dtbcolumns},
            dataType:"json",
            success:function(datum){
              console.log(datum);
            $('#datatable-tbody').html('');
             // console.log(Math.round(12.434));
            var datacount = Object.keys(datum).length;
            var html='';
            for(var i=1;i<=datacount;i++){
              html += "<tr>";
              html += "<td class='center first-child'><label><input type='checkbox' name='chkIds[]' class='ace checkbox-invoicelist'/><span class='lbl'></span></label></td>";
              html += "<td>"+datum[i]['origin']+"</td>";
              html += "<td>"+datum[i]['cust_code']+"</td>";
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              html += "<a class='btn btn-success btn-minier' href='customerratedetail?cust_code="+datum[i]['cust_code']+"' data-rel='tooltip' title='Edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";
             
              html += "<a  class='btn btn-danger btn-minier bootbox-confirm' ><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
              //action
              html += "</div></td></tr>";
            }
            $('#datatable-tbody').append(html);
            $('#datatable-tbody').css('opacity','1');
            //removeallinvnoinarray();
            }

            
          }); 
       });


</script>