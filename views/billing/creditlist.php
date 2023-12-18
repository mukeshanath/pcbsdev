<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'creditlist';
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
    $module = 'creditdelete';
    $getusergrp = $db->query("SELECT group_name FROM users WHERE user_name='$user'");
    $rowusergrp = $getusergrp->fetch(PDO::FETCH_OBJ);
    $user_group = $rowusergrp->group_name;
  
    $verify_user = $db->query("SELECT module FROM users_group WHERE group_name='$user_group' AND module='$module'");
        if($verify_user->rowCount()=='0'){
            return 'swal("You Dont Have Permission to Delete this Record!")';
        }
        else{
            return "deletequeue(this.id,this.value)";
        }
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

  if(isset($_GET['cnotenumber'])){
    $cnotenumber = $_GET['cnotenumber'];
    $cust_code = $_GET['cust_code'];
    $cnotetype = base64_decode($_GET['cnotetype']);
    $cnodetailsofprev = $db->query("SELECT TOP(1) cnote_number,cnote_serial,(select v_flag from groups where cate_code=cnotenumber.cnote_serial and stncode=cnotenumber.cnote_station) as vflag FROM cnotenumber WITH (NOLOCK) WHERE cust_code='$cust_code' AND cnote_station='$stationcode' AND cnote_number='".$_GET['cnotenumber']."'")->fetch(PDO::FETCH_OBJ);

    $challan_credit = $db->query("SELECT * FROM customer WHERE cust_code='$cust_code' AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
    $creditchal_1 =  $challan_credit->credit_accounts_copy;
    $creditchal_2 =  $challan_credit->credit_pod_copy;
    $creditchal_3 =  $challan_credit->credit_consignor_copy;

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
                                            <a href="<?php echo __ROOT__ . 'creditlist'; ?>">Credit List</a>
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
                                  Record Added successfully.
                                </div>";
                  }
                  if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 4 )
                  {
                    echo "<script>swal('Entry not Saved ! Customer Rate Issue !')</script>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Record Updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <form method="POST"  >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Entry Filters</p>
                    <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>credit"><button type="button" class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a>

        <a style="color:white" href="<?php echo __ROOT__ ?>homepage"><button type="button" class="btn btn-danger btnattr" >
                <i class="fa fa-times" ></i>
        </button></a>

        </div>
              
                </div> 
      <div class="content panel-body">


        <div class="tab_form">


          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>		
                  <td>Branch Code</td>
                  <td>
                  <div class="col-sm-12">
                        <input placeholder="" class="form-control border-form" name="br_code" type="text" id="br_code" value="<?php if(isset($_POST['br_code'])){ echo  $_POST['br_code']; } ?>"  autofocus>
                   </div>
                     </td>
                    
                  <td>Cnote Number</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="cnotenumber" type="text" id="cnotenumber" value="<?php if(isset($_POST['cnotenumber'])){ echo  $_POST['cnotenumber']; } ?>">
                      </div>
                      </td>

                      <td>Period</td>
                  <td>
                  <div class="col-sm-9">
                      <select class="form-control border-form" name="periodical" id="periodical">
                        <option value="">Select</option>
                        <option value="this_week">This Week</option>
                        <!-- <option value="this_week">Last Week</option> -->
                        <option value="this_month">This Month</option>
                        <!-- <option value="this_week">Last Month</option> -->
                    </div>
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

                    <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtercreditlist">
                    Apply <i class="fa fa-filter bigger-110"></i>                         
                    </button> 

                    <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" >
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button> 

                    </div>
                    </table>
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Credit Entry List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
           </div>
             <!-- pagination search  -->


             <div class="table-search">
               Show<select>
                 <option>10</option>
               </select>
               <div class="table-search-right">Search<input type="text" class="table-search-right-text"></div>
           </div>
           <!-- pagination search  -->
           <table id="data-table1" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label> 
              </th>
              <th>Cnote Number</th>
              <th>Branch Code</th>
              <th>Destination</th>
              <th>Weight</th>
              <!-- <th>Pcs</th> -->
              <th>Mode</th>
              <th>Amount</th>
              <th>Bill Date</th>
              <!-- <th>Entry Date</th> -->
              <th>Status</th>
              <th>Action</th>
              </tr>
    </thead>
              <tbody id="datatable-tbody">
                  <?php 

                              

                                $conditions = array();

                                $conditions[] = "cm.comp_code='$stationcode'";
                                

                                //branch filter conditions
                                $sessionbr_code = $datafetch['br_code'];
                                if(!empty($sessionbr_code)){
                                $conditions[] = "cm.br_code='$sessionbr_code'";
                                }
                                //branch filter conditions

                             if(isset($_POST['filtercreditlist']))
                             {    
                                 $br_code            = $_POST['br_code'];
                                 $cnotenumber          = $_POST['cnotenumber'];
                                 //$date_added         = $_POST['date_added'];
                                 $mon_th             = $_POST['mon_th']; 
                                 $periodical         = $_POST['periodical']; 

             
                                 if(! empty($br_code)) {
                                 $conditions[] = "cm.br_code LIKE '$br_code%'";
                                 }
                                 if(! empty($cnotenumber)) {
                                 $conditions[] = "cm.cno LIKE '$cnotenumber%'";
                                 }
                               
                                if(empty($periodical)) {
                                  $conditions[] = "YEAR(cm.tdate) ='".explode('-',$mon_th)[0]."' AND MONTH(cm.tdate) = '".explode('-',$mon_th)[1]."'";
                                  }
                                  else{
                                    if($periodical=='this_week'){
                                    $conditions[] = "cm.tdate BETWEEN DATEADD(DAY,- datepart(dw, getdate()), GETDATE()) AND DATEADD(DAY, 1, GETDATE())";
                                    }
                                    else if($periodical=='this_month'){
                                    $conditions[] = "datepart(mm,cm.tdate) = month(getdate())";
                                    }
                                  }
                               
                                 $conditons = implode(' AND ', $conditions);
             
                                 if (count($conditions) > 0) {
                                     $query = $db->query("SELECT * FROM credit cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code=c.comp_code WHERE  $conditons ORDER BY cm.tdate DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                                     //echo json_encode($query);
                                 }
                                 if (count($conditions) == 0) {
                                 $query = $db->query("SELECT * FROM credit cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code=c.comp_code WHERE $conditons ORDER BY cm.tdate DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                                 //echo json_encode($query);
                             }
                             } 
                             else{
                              $conditions[] = "tdate= CAST( GETDATE() AS Date )"; 
                              $conditions[] = "cm.comp_code='$stationcode'";  
                              $conditons = implode(' AND ', $conditions);
                              // echo json_encode($conditions);
                              // exit();


                              $query = $db->query("SELECT * FROM credit cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code = c.comp_code WHERE $conditons ORDER BY cm.tdate DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");
                             }
                             if($query->rowcount()==0)
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
                           
                                  echo "<td>".$row->cno."</td>";
                                  echo "<td>".$row->br_code."</td>";
                                  echo "<td>".$row->dest_code."</td>";
                                  echo "<td>".number_format((float)$row->wt, 3)."</td>";
                                  // echo "<td>".$row->pcs."</td>";
                                  echo "<td>".$row->mode_code."</td>";
                                  echo "<td>".number_format($row->amt,2)."</td>";                             
                                  echo "<td>".$row->tdate."</td>";     
                                  // echo "<td>".$row->edate."</td>"; 
                                  echo "<td>".$row->status."</td>";
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>";
                               

                            if($row->status == 'Billed'){
                              echo "<button type='button' class='btn btn-danger btn-minier bootbox-confirm' value='$row->cnote_serial' id='$row->cno' onclick='".delete($db,$user)."'>
                              <i class='ace-icon fa fa-trash-o bigger-130'></i>
                           </button>";
                           }else{

                           }
                           echo "</div>
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
                          }
                          
                      ?>

                   
                 </tbody>
            </table>
                            <!-- pagination -->
                            <div class="table-paginate-btns">
            <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM credit cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code = c.comp_code WHERE $conditons")->fetch(PDO::FETCH_OBJ);
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
          </div>
</section>

<!-- inline scripts related to this page -->
<script>
function resetFunction() {
    document.getElementById('br_code').value = "";
    document.getElementById('cust_code').value = "";    
  
}
</script>
<script>
    $(window).on('load', function() {
        console.log("<?=$_GET['cnotenumber']?>"," flag = ",'<?=$cnodetailsofprev->vflag?>','<?=$cnotetype?>');


        if('<?=$_GET['cnotenumber']?>'.trim().length>0 && '<?=$cnodetailsofprev->vflag?>'==1 && '<?=$creditchal_1?>'=='1' || '<?=$creditchal_2?>'=='1' || '<?=$creditchal_3?>'=='1'){
          if('<?=$cnotetype?>'=='Virtual'){
            window.open('creditchallan?cno=<?=base64_encode(http_build_query(array($_GET['cnotenumber'])))?>&type=<?=base64_encode('printed')?>&customer=<?=base64_encode($cust_code)?>');
          }
        }
       
    })
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
      var dtbquery = "SELECT credit_id,cm.cno,cm.br_code,cm.dest_code,cm.wt,cm.pcs,cm.mode_code,cm.amt,cm.tdate,cm.edate,cm.status,cm.cnote_serial FROM credit cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code = c.comp_code WHERE <?=$conditons;?> ORDER BY cm.tdate DESC";
    
      var dtbcolumns = 'credit_id,cno,br_code,dest_code,wt,pcs,mode_code,amt,tdate,edate,status,cnote_serial';
      
      var dtbpageno = ($(this).val()-1)*10;
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
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
              html += "<td>"+datum[i]['cno']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['dest_code']+"</td>";
              html += "<td>"+datum[i]['wt']+"</td>";
              // html += "<td>"+datum[i]['pcs']+"</td>";
              html += "<td>"+datum[i]['mode_code']+"</td>";
              html += "<td>"+parseFloat(datum[i]['amt'])+"</td>";
              html += "<td>"+datum[i]['tdate']+"</td>";
              // html += "<td>"+datum[i]['edate']+"</td>";
              html += "<td>"+datum[i]['status']+"</td>";
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              // html += "<a class='btn btn-primary btn-minier' href='creditcnotedetail?billed="+datum[i]['br_code']+"&date="+datum[i]['tdate']+"' data-rel='tooltip' title='View'><i class='ace-icon fa fa-eye bigger-130'></i></a>";
            //  echo "<button type='button' class='btn btn-danger btn-minier bootbox-confirm' value='$row->cnote_serial' id='$row->cno' onclick='".delete($db,$user)."'>
              if(datum[i]['status']=='Billed'){
              html += "<a><button type='button' class='btn btn-danger btn-minier bootbox-confirm' value="+datum[i]['cnote_serial']+" id="+datum[i]['cno']+" onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></button></a>";
             }
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
      sessionStorage.setItem("creditlist", fieldvalue);
      
      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT credit_id,cm.cno,cm.br_code,cm.dest_code,cm.wt,cm.pcs,cm.mode_code,cm.amt,cm.tdate,cm.edate,cm.status,cm.cnote_serial FROM credit cm LEFT JOIN customer c ON cm.cust_code = c.cust_code and cm.comp_code = c.comp_code WHERE <?=$conditons;?> AND (cm.cno like '%"+fieldvalue+"%' OR cm.cno LIKE '%"+fieldvalue+"%') ORDER BY tdate DESC";
      var dtbcolumns = 'credit_id,cno,br_code,dest_code,wt,pcs,mode_code,amt,tdate,edate,status,cnote_serial';
      var dtbpageno = '0';
      console.log(dtbquery);
     
      //var sessionuser = $('#user_name').val();
      //console.log(dtbquery);
          $.ajax({
            url:"<?php echo __ROOT__ ?>billingcontroller",
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
              html += "<td>"+datum[i]['cno']+"</td>";
              html += "<td>"+datum[i]['br_code']+"</td>";
              html += "<td>"+datum[i]['dest_code']+"</td>";
              html += "<td>"+datum[i]['wt']+"</td>";
              // html += "<td>"+datum[i]['pcs']+"</td>";
              html += "<td>"+datum[i]['mode_code']+"</td>";
              html += "<td>"+parseFloat(datum[i]['amt'])+"</td>";
              html += "<td>"+datum[i]['tdate']+"</td>";
              // html += "<td>"+datum[i]['edate']+"</td>";
              html += "<td>"+datum[i]['status']+"</td>";
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              // html += "<a class='btn btn-primary btn-minier' href='creditcnotedetail?billed="+datum[i]['cust_code']+"&date="+datum[i]['date_added']+"' data-rel='tooltip' title='View'><i class='ace-icon fa fa-eye bigger-130'></i></a>";
             
              if(datum[i]['status']=='Billed'){
              html += "<a><button type='button' class='btn btn-danger btn-minier bootbox-confirm' value="+datum[i]['cnote_serial']+" id="+datum[i]['cno']+" onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></button></a>";
             }
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

<script>
function deletequeue(cnotenumber,serial){
    swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this record file!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
  })
  .then((willDelete) => {

    if (willDelete) {
      var delcredit_ecompany = '<?=$stationcode?>';
      var delcredit_ecnote = cnotenumber;
      var delcredit_serial = serial;
      var delcredit_euser = '<?=$user?>'
      $.ajax({
                url:"<?php echo __ROOT__ ?>credit",
                method:"POST",
                data:{delcredit_ecompany,delcredit_ecnote,delcredit_euser,delcredit_serial},
                dataType:"text",
                
        });
      swal("Your Record has been deleted!", {
        icon: "success",
      });
      $('#'+cnotenumber).closest('tr').remove();
      
    } else {
      swal("Your Record is safe!");
    }
  });
  
}

</script>