<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
}

function authorize($db,$user)
{
    
    $module = 'collectionlist';
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
    $module = 'collectiondelete';
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
                                        <a href="<?php echo __ROOT__ . 'collectionlist'; ?>">Collection List</a>
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
                                  Collection Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Collection updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <form method="POST"  >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

         <div class="boxed boxBt panel panel-primary">
         <div class="title_1 title panel-heading">
         <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Collection Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>collection"><button type="button" class="btn btn-success btnattr" >
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
                  <td>Collection Master No</td>
                  <td>
                  <div class="col-sm-9">
                        <input placeholder="" class="form-control border-form" name="colmasno" type="text" id="colmasno" value="<?php if(isset($_POST['colmasno'])){ echo  $_POST['colmasno']; } ?>" autofocus>
                   </div>
                     </td>
                    
                  <td>Customer</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="cust_code" type="text" id="cust_code" value="<?php if(isset($_POST['cust_code'])){ echo  $_POST['cust_code']; } ?>">
                    </div>
                      </td>

                      <td>Invoice No</td>
                  <td>
                  <div class="col-sm-9">
                      <input placeholder="" class="form-control border-form" name="invno" type="text" id="invno" value="<?php if(isset($_POST['invno'])){ echo  $_POST['invno']; } ?>" >
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

                    <button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filtercollection">
                    Apply <i class="fa fa-filter bigger-110"></i>                         
                    </button> 

                    <button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn" onclick="resetFunction()" >
                    Reset <i class="fa fa-refresh bigger-110"></i>                         
                    </button> 

                    </div>
                    </table> 
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Collection List</p>
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
           <table id="data-table4" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>                            
              </th>
              <th>Collection Master No</th>
              <th>Customer</th>
              <th>Collection Date</th>
              <th>Invoice No</th>             
              <th>Collected Amt</th>
              <th>Net Collected Amt</th>
              <th>Status</th>              
              <th>Actions</th>              
              </tr>
    </thead>
              <tbody id="datatable-tbody">
                <?php 
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
                 
                if(isset($_POST['filtercollection']))
                {    
                    $colmasno          = $_POST['colmasno'];
                    $cust_code         = $_POST['cust_code'];
                    $invno             = $_POST['invno']; 
                    //$date             = $_POST['date']; 
                    $mon_th             = $_POST['mon_th']; 

                   

                    if(! empty($colmasno)) {
                    $conditions[] = "col_mas_rcptno LIKE '$colmasno%'";
                    } 
                    if(! empty($cust_code)) {
                    $conditions[] = "cust_code LIKE '$cust_code%'";
                    }
                    if(! empty($invno)) {
                    $conditions[] = "invno LIKE '$invno%'";
                    }
                    // if(! empty($date)) {
                    // $conditions[] = "date_added='$date'";
                    // }
                    if(!empty($stationcode)) {
                    $conditions[] = "comp_code='$stationcode' AND NOT status LIKE '%void'";
                    }
                    if(! empty($mon_th)) {
                        $conditions[] = "YEAR(coldate) ='".explode('-',$mon_th)[0]."' AND MONTH(coldate) = '".explode('-',$mon_th)[1]."'";
                        }

                    $conditons = implode(' AND ', $conditions);

                    if (count($conditions) > 0) {
                        $query = $db->query("SELECT * FROM collection  WHERE NOT status LIKE '%void' AND $conditons ORDER BY col_id DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                        echo json_encode($query);
                    }
                    if (count($conditions) == 0) {
                    $query = $db->query("SELECT * FROM collection WHERE comp_code='$stationcode' AND NOT status LIKE '%void' ORDER BY col_id DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                    echo json_encode($query);
                }
                }
                //Normal Station list
                else{
                  $conditions[] = "comp_code='$stationcode' AND datepart(mm,coldate) = month(getdate()) AND datepart(yyyy,coldate) = year(getdate()) AND NOT status LIKE '%void'";
                  $conditons = implode(' AND ', $conditions);
                $query = $db->query("SELECT * FROM collection WHERE  $conditons ORDER BY col_id DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");
                echo json_encode($query);
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
                    
                        echo "<td>".$row->col_mas_rcptno."</td>";
                        echo "<td>".$row->cust_code."</td>";
                        echo "<td>".$row->coldate."</td>";
                        echo "<td>".$row->invno."</td>";
                        echo "<td>".number_format($row->colamt,2)."</td>"; 
                        echo "<td>".number_format($row->netcollamt,2)."</td>";
                        echo "<td>".$row->status."</td>";
                        echo "<td><div class='hidden-sm hidden-xs action-buttons'>";
                        if($row->status=='On-Hold')
                        {
                        echo "<a class='btn btn-success btn-minier' href='collectionedit?colno=$row->colno'>
                            <i class='ace-icon fa fa-pencil bigger-130'></i>
                            </a>";
                        }
                        else{
                            echo "<a class='btn btn-success btn-minier' href='collectionedit?invno=$row->invno'>
                            <i class='ace-icon fa fa-pencil bigger-130'></i>
                            </a>";
                        }
                        echo "<a class='btn btn-danger btn-minier'  id='$row->col_id' onclick='".delete($db,$user)."'>
                            <i class='ace-icon fa fa-trash bigger-130'></i>
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
                                <a  class='tooltip-primary' data-rel='tooltip' title='Edit'>
                                <span class='blue'>
                                <i class='ace-icon fa fa-pencil bigger-120'></i>
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

                   
                 </tbody>
            </table>
                <!-- pagination -->
                <div class="table-paginate-btns">
            <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM collection WHERE $conditons")->fetch(PDO::FETCH_OBJ);
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

//DataTable
  $(document).ready(function(){
        $('#data-table').DataTable();
    });
</script>
<script>
function resetFunction() {
    document.getElementById('colmasno').value = "";
    document.getElementById('cust_code').value = ""; 
    document.getElementById('invno').value = "";    
       
}
</script>
<!-- Set cookie for datatable search -->
<script>
$(document).on('keyup', ".dataTables_filter",function () {
    var collectioncookie = $('.dataTables_filter').find('input').val();
    sessionStorage.setItem("collectionlist", collectioncookie);
});
</script>
<script>
function opencookie(){
$('.dataTables_filter').find('input').val(sessionStorage.getItem("collectionlist").trim());
$('.dataTables_filter').find('input').keyup();
}
</script>
<!-- Set cookie for datatable search -->
<script>
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
    //var datas = id.split(",");
    //var deletetb = datas[0];
    //var deletecol = 'brid';
    var del_collno = id;
    var del_collstn = '<?=$stationcode?>';
    var del_coll_user = '<?=$user?>';
    $.ajax({
              url:"<?php echo __ROOT__ ?>collectioncontroller",
              method:"POST",
              data:{del_collno,del_collstn,del_coll_user},
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
      var dtbquery = "SELECT col_id,col_mas_rcptno,cust_code,coldate,invno,colamt,netcollamt,status FROM collection  WHERE <?=$conditons;?> ORDER BY col_id DESC";
    console.log(dtbquery);
      var dtbcolumns = 'col_id,col_mas_rcptno,cust_code,coldate,invno,colamt,netcollamt,status';
      
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
              html += "<td>"+datum[i]['col_mas_rcptno']+"</td>";
              html += "<td>"+datum[i]['cust_code']+"</td>";
              html += "<td>"+datum[i]['coldate']+"</td>";
              html += "<td>"+datum[i]['invno']+"</td>";
              html += "<td>"+Math.round(datum[i]['colamt'])+"</td>";
              html += "<td>"+Math.round(datum[i]['netcollamt'])+"</td>";
              html += "<td>"+datum[i]['status']+"</td>";
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
             if(datum[i]['status'] == "On-Hold"){
              html += "<a class='btn btn-success btn-minier' href='collectionedit?invno="+datum[i]['colno']+"' data-rel='tooltip' title='edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";
             }else{
              html += "<a class='btn btn-success btn-minier' href='collectionedit?invno="+datum[i]['invno']+"' data-rel='tooltip' title='edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";
             }
              
              html += "<a  class='btn btn-danger btn-minier bootbox-confirm' id='"+datum[i]['col_id']+"' data-rel='tooltip' title='Delete' "+((Math.round(datum[i]['coll_counts'])>0)?'onclick="deletepreventalert()" _':'')+"onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
            
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
      sessionStorage.setItem("collectionlist", fieldvalue);
      
      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT col_id,col_mas_rcptno,cust_code,coldate,invno,colamt,netcollamt,status FROM collection  WHERE <?=$conditons;?> AND (cust_code like '%"+fieldvalue+"%' OR invno LIKE '%"+fieldvalue+"%') ORDER BY col_id DESC";
      var dtbcolumns = 'col_id,col_mas_rcptno,cust_code,coldate,invno,colamt,netcollamt,status';
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
              html += "<td>"+datum[i]['col_mas_rcptno']+"</td>";
              html += "<td>"+datum[i]['cust_code']+"</td>";
              html += "<td>"+datum[i]['coldate']+"</td>";
              html += "<td>"+datum[i]['invno']+"</td>";
              html += "<td>"+Math.round(datum[i]['colamt'])+"</td>";
              html += "<td>"+Math.round(datum[i]['netcollamt'])+"</td>";
              html += "<td>"+datum[i]['status']+"</td>";
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
              if(datum[i]['status'] == "On-Hold"){
              html += "<a class='btn btn-success btn-minier' href='collectionedit?invno="+datum[i]['colno']+"' data-rel='tooltip' title='edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";
             }else{
              html += "<a class='btn btn-success btn-minier' href='collectionedit?invno="+datum[i]['invno']+"' data-rel='tooltip' title='edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";
             }
              
              html += "<a  class='btn btn-danger btn-minier bootbox-confirm' id='"+datum[i]['col_id']+"' data-rel='tooltip' title='Delete' "+((Math.round(datum[i]['coll_counts'])>0)?'onclick="deletepreventalert()" _':'')+"onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
  
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