<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
}

function authorize($db,$user)
{
   
    $module = 'zonelist';
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
    $module = 'zonedelete';
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
                                        <a href="<?php echo __ROOT__ . 'zonelist'; ?>">Zone list</a>
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
                        Zone Added successfully.
                      </div>";
        }

        if ( isset($_GET['success']) && $_GET['success'] == 3 )
        {
            echo 

        "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Zone Updated Successfully.
                      </div>";
        }

        ?>
                
             </div>
  

     

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Zone Filters</p>
         <div class="align-right" style="margin-top:-35px;">

         <a style="color:white" href="<?php echo __ROOT__ ?>zone"><button type="button" class="btn btn-success btnattr" >
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
            <td>Zone Type</td>
            <td>
            <input placeholder="" class="form-control br_code" name="zone_type" value="<?php if(isset($_POST['zone_type'])){ echo  $_POST['zone_type']; } ?>" type="text" id="zone_type">
           
            </td>
                
            <td>Zone Code</td>
            <td>
                <input class="form-control border-form" name="zone_code" type="text" value="<?php if(isset($_POST['zone_code'])){ echo  $_POST['zone_code']; } ?>" id="zone_code" >
               
            </td>
            <td>Zone Name</td>
            <td>
            <input placeholder="" class="form-control border-form" name="zone_name" type="text" value="<?php if(isset($_POST['zone_name'])){ echo  $_POST['zone_name']; } ?>" id="zone_name">
           

            </td>
            <td>Status</td>
            <td>
            <select class="form-control border-form" name="br_active" id="br_active">
               
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                  </select>
                 
            </td>		
            </tr>
            
       </tbody>
     </table>
     <table>

<div class="align-right btnstyle" >

<button class="btn btn-info btnattr_cmn" type="submit" id="filter-btn" name="filterzone">
 Apply <i class="fa fa-filter bigger-110"></i>                         
</button> 
                                                  
<button class="btn btn-warning btnattr_cmn" type="submit" id="filter-btn"  onclick="resetFunction()">
Reset <i class="fa fa-refresh bigger-110"></i>                         
</button> 
<!-- <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
<i class="fa fa-eraser"></i>&nbsp; Reset
</button>    -->


</div>
</table>
    

      </div>    

     </form>

<!-- Fetching Mode Details in Table  -->
<div class="col-12 data-table-div" >

   <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Zone Detail List</p>
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
      <table id="data-tableq" class="table data-table" cellspacing="0" cellpadding="0" align="center">
          <thead><tr>
                    <th class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" />
                            <span class="lbl"></span>
                        </label>
                    </th>
                    <th>Comp Code</th>
                    <th>Zone Type</th>
                    <th>Zone Code</th>
				            <th>Zone Name</th>
				            <th class='hidden'>Country Code</th>
                    <th>Action</th>
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
                              $stn_condition = "WHERE comp_code='$stationcode'";
                        }
                        else{
                            $userstations = $datafetch['stncodes'];
                            $stations = explode(',',$userstations);
                            $stationcode = $stations[0];  
                            $stn_condition = "WHERE comp_code='$stationcode'";   
                        }

                        $conditions = array();
                       
                     if(isset($_POST['filterzone']))
                     {    
                          $zone_type     = $_POST['zone_type'];
                          $zone_code     = $_POST['zone_code'];
                          $zone_name   = $_POST['zone_name'];                         
                          // $active        = $_POST['cust_active']; 

                         

                          if(! empty($zone_type)) {
                            $conditions[] = "zone_type LIKE '$zone_type%'";
                          } 
                          if(! empty($zone_code)) {
                            $conditions[] = "zone_code LIKE '$zone_code%'";
                          }
                          if(! empty($zone_name)) {
                             $conditions[] = "zone_name LIKE '$zone_name%'";
                           }
                          
                           if(! empty($active)) {
                             $conditions[] = "cust_active='$active'";
                           }

                           if(!empty($stationcode)) {
                             $conditions[] = "comp_code='$stationcode'";
                           }

                           $conditons = implode(' AND ', $conditions);

                           if (count($conditions) > 0) {
                               $query = $db->query("SELECT * FROM zone WHERE  $conditons OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                           }
                           if (count($conditions) == 0) {
                            
                             $query = $db->query("SELECT * FROM zone  $stn_condition ORDER BY znid DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY"); 
                         }
                     }
                     //Normal Station list
                     
                     else{
                      $conditions[] = "WHERE comp_code='$stationcode'";  
                      $conditons = implode(' AND ', $conditions);
                   
                        $query = $db->query("SELECT * FROM zone  $conditons ORDER BY znid DESC OFFSET 0 ROWS  FETCH NEXT 10 ROWS ONLY");
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
                            echo "<td>".$row->comp_code."</td>";
                            echo "<td>".$row->zone_type."</td>";
                            echo "<td>".$row->zone_code."</td>";
                            echo "<td>".$row->zone_name."</td>";                                                       
                            echo "<td class='hidden'>".$row->coun_code."</td>";                                                       
                            echo "<td><div class='hidden-sm hidden-xs action-buttons'>
                               <a class='btn btn-success btn-minier' href='zoneedit?znid=".$row->znid."'>
                                  <i class='ace-icon fa fa-pencil bigger-130'></i>
                                 </a>

                                 <a  class='btn btn-danger btn-minier bootbox-confirm' id='zone,$row->znid,znid,$row->comp_code' onclick='".delete($db,$user)."'>
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
                           
                                                    
                          }
                        }
                            
                        ?>

                    
                  </tbody>
              </table>
                <!-- pagination -->
                <div class="table-paginate-btns">
            <button type="button" class="btn btn-primary paginate-btn-nums" id="prev" value="0">Prev</button>
             
              <?php $noofpages = $db->query("SELECT COUNT(*) as pages FROM zone $stn_condition")->fetch(PDO::FETCH_OBJ);
              
              
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
function resetFunction() {
    document.getElementById('zone_type').value = "";
    document.getElementById('zone_code').value = "";    
    document.getElementById('zone_name').value = "";   
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
    var deletecomp = datas[3];

    var delu_user_name = $('#user_name').val();
    $.ajax({
              url:"<?php echo __ROOT__ ?>mastercontroller",
              method:"POST",
              data:{deletetb:deletetb,deleterow:deleterow,deletecol:deletecol,deletecomp:deletecomp,delu_user_name:delu_user_name},
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
      var dtbquery = "SELECT znid,comp_code,zone_type,zone_code,zone_name FROM zone  <?=$conditons;?> ORDER BY znid DESC";
    console.log(dtbquery);
      var dtbcolumns = 'znid,comp_code,zone_type,zone_code,zone_name';
      
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
              html += "<td>"+datum[i]['comp_code']+"</td>";
              html += "<td>"+datum[i]['zone_type']+"</td>";
              html += "<td>"+datum[i]['zone_code']+"</td>";
              html += "<td>"+datum[i]['zone_name']+"</td>";
           
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
           
              html += "<a class='btn btn-success btn-minier' href='zoneedit?znid="+datum[i]['znid']+"' data-rel='tooltip' title='edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";

              html += "<a  class='btn btn-danger btn-minier bootbox-confirm' id='zone,"+datum[i]['znid']+",znid' data-rel='tooltip' title='Delete' onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
             

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
      sessionStorage.setItem("zonelist", fieldvalue);
      
      $('#datatable-tbody').css('opacity','0.5');
      //var dtbquery = "SELECT invno,cust_code,invdate,br_code,MailDate,grandamt,fsc,invamt,igst,cgst,sgst,netamt,flag,(SELECT COUNT(*) FROM collection WHERE invno=invoice.invno AND comp_code=invoice.comp_code AND status NOT LIKE '%void') coll_counts FROM invoice WHERE <?=$conditons;?> ORDER BY invno";
      var dtbquery = "SELECT znid,comp_code,zone_type,zone_code,zone_name FROM zone  <?=$conditons;?> AND (comp_code like '%"+fieldvalue+"%' OR zone_name LIKE '%"+fieldvalue+"%') ORDER BY znid DESC";
      var dtbcolumns = 'znid,comp_code,zone_type,zone_code,zone_name';
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
              html += "<td>"+datum[i]['comp_code']+"</td>";
              html += "<td>"+datum[i]['zone_type']+"</td>";
              html += "<td>"+datum[i]['zone_code']+"</td>";
              html += "<td>"+datum[i]['zone_name']+"</td>";
            
              //action
              html += "<td><div class='hidden-sm hidden-xs action-buttons'>";
          
              html += "<a class='btn btn-success btn-minier' href='zoneedit?znid="+datum[i]['znid']+"' data-rel='tooltip' title='edit'><i class='ace-icon fa fa-pencil bigger-130'></i></a>";

              html += "<a  class='btn btn-danger btn-minier bootbox-confirm' id='zone,"+datum[i]['znid']+",znid' data-rel='tooltip' title='Delete' onclick='<?=delete($db,$user)?>'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
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