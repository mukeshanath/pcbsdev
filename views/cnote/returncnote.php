<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
   
    $module = 'returncnote';
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
<style>
       html {
    height:100%;
    width:100%;
    margin:0%;
    padding:0%;
    overflow-x: hidden ;
}

body {
    overflow-x: initial !important;
}
</style>
<head>

</head>
<input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none">

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
                                            </li>/                                      
                                            <li class="list-inline-item active">
                                            <a href="<?php echo __ROOT__ . 'returncnote'; ?>">Return</a>
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
                            Cnote Return Successfully.
            </div>";
            }
            if ( isset($_GET['unsuccess']) && $_GET['unsuccess'] == 2 )
            {
                    echo 
            "<div class='alert alert-danger alert-dismissible'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <h4><i class='icon fa fa-check'></i> Alert!</h4>
                            Cnote is Alredy Return. or Invalid Cnote
            </div>";
            }
            ?>

            </div>
            <div class="boxed boxBt panel panel-primary">
          <div class="title_1 title panel-heading">
                <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cash Return Form</p>
                <div class="align-right" style="margin-top:-30px;">
                

            <a style="color:white" href="<?php echo __ROOT__ . 'cashlist'; ?>">         
            <button type="button" class="btn btn-danger btnattr_cmn" >
            <i class="fa fa-times" ></i>
            </button></a>
          </div>
         
            </div> 
        <div class="content panel-body">
        <form method="POST" autocomplete="off" class="form-horizontal" name="returncnote" id="returncnote">


          <div class="tab_form">


           <table class="table">	
           <tr>		
            <td>Company Code</td>
            <td>
                <div class="col-sm-12">
                     <input class="form-control border-form" name="stncode" type="text" id="stncode" readonly>
                </div>
            </td>
              
            <td>Company Name</td>
            <td>
                <div class="col-sm-12">
                    <input class="form-control border-form "   name="stnname" type="text" id="stnname" readonly>
                </div>
            </td>
      	
            <td>Branch Code</td>
            <td>
                <div class="col-sm-12 autocomplete">
                <input class="form-control border-form br_code text-uppercase" name="br_code" type="text" id="br_code" onfocusout="fetchbr_name()">
                </div>
            </td>
            <td>Branch Name</td>
            <td>
                <div class="col-sm-12">
                    <input class="form-control border-form" tabindex="-1" name="br_name" type="text" id="br_name">
                </div>
            </td>
       </tr>
        <tr>
        <td>Cnote Number</td>
          <td>
          <div class="col-sm-12">
           <input type="text" id="cnote_number" name="cnote_number" class="form-control text-uppercase" onkeyup="fetchserial()">
        </td>
          </div>
          <td>Cnote Serial</td>
        <td>
        <div class="col-sm-12">
            <input type="text" id="cnote_serial" name="cnote_serial" tabindex="-1" class="form-control " readonly>
            </div>
        </td>
        <td>Billed Date</td>
          <td>
          <div class="col-sm-12">
           <input type="text" id="billdate" name="billdate" tabindex="-1" class="form-control text-uppercase" readonly>
          </td>
          </div>
         
        </tr>
               
       
       
                        </div>
                        <!-- </div> -->
          
                </div>
        </div>
        
           <table>
           <div style="padding-bottom:5px;padding-left:5px;">
                 
          <div class="align-right btnstyle" >
          <button type="button" class="btn btn-primary btnattr_cmn" id="recnote">
          <i class="fa fa-undo"></i>&nbsp; Return
          </button>
          </div>
          </div>
          </table>
        
 
          </div>
 
            </div>
            
              </div>
          </div>
          </form>
          <div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Cnote Return Data</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"><div class="loader"></div></div>
        </div>
        <table id="returntable" class="table return-table" cellspacing="0" cellpadding="0" align="center">	
       
       </table>  
</div>
<div class="col-12 data-table-div" style="margin-top:40px;">

<div class="title_1 title panel-heading">
            <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Returned Cnote List</p>
            <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
      </div>
    <table id="data-table" class="table data-table" cellspacing="0" cellpadding="0" align="center">
        <thead><tr>
            <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>
                    <th>S.N.</th>
                    <th>Cnote Station</th>
                    <th>Cnote Serial</th>                   
                    <th>Cnote Number</th>
                    <th>Cnote Status</th>
                    <th>Date Added</th>
              </tr>
                </thead>
              <tbody>
                  <?php 

                        
                    $query = $db->query("SELECT * FROM cnotenumber WHERE cnote_status LIKE'%Return' AND cnote_station='$stationcode' order by date_added desc");
                        while ($row = $query->fetch(PDO::FETCH_OBJ))
                          {
                            echo "<tr>";
                            echo "<td class='center first-child'>
                                    <label>
                                      <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                        <span class='lbl'></span>
                                    </label>
                                  </td>";
                            echo "<td>".$row->cnotenoid."</td>";
                            echo "<td>".$row->cnote_station."</td>";
                            echo "<td>".$row->cnote_serial."</td>";                            
                            echo "<td>".$row->cnote_station.$row->cnote_number."</td>";
                            echo "<td>".$row->cnote_status."</td>";
                            echo "<td>".date("d-M-Y",strtotime($row->date_added))."</td>";
                           
                            echo "<td>
                            
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
</section>

<!-- inline scripts related to this page -->
<script>
 $(document).ready(function(){
  $('#recnote').click(function(){
    if($('#cnote_serial').val().trim()==''){
        swal("cnote serial is empty")
    }
    else if($('#billdate').val().trim()==''){
        swal("Bill Date is empty");
    }else{
        $('#recnote').attr('type','submit');
        $('#recnote').attr('name','returncnote');
    }
  });
 });
</script>


<script>
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
            console.log(data);
            $('#stncode').val(data[0]);
            $('#stnname').val(data[1]);
            $('#origin').val(data[0]);
            }
    });
  });

</script>
<script>
    var branchcode =[<?php  

$brcode=$db->query("SELECT br_code FROM branch WHERE stncode='$stationcode'");
while($rowbrcode=$brcode->fetch(PDO::FETCH_ASSOC))
{
echo "'".$rowbrcode['br_code']."',";
}

?>];
autocomplete(document.getElementById("br_code"), branchcode);
</script>

<script>
    function fetchbr_name(){
        var brcode = $('#br_code').val();
        var station = '<?=$stationcode?>';
        $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{brcode:brcode,station:station},
            dataType:"text",
            success:function(value)
            {
              $('#br_name').val(value);
            }
    });
    }
</script>
<script>
       function fetchserial(){
        var br_code = $('#br_code').val();
        var stationcode = '<?=$stationcode?>';
        var cnumber = $('#cnote_number').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>cash",
            method:"POST",
            data:{br_code,stationcode,cnumber,action:'fetchserial'},
            dataType:"text",
            success:function(res)
            {
                console.log(res);
                var data = JSON.parse(res);
                $('#cnote_serial').val(data[0]);
                $('#billdate').val(data[1]);

            }
    });
    }
</script>
<script>
    if(window.location.host!='localhost')
    window.history.pushState('Credit', 'Title', '/returncnote');
    $(document).ready(function(){
    $('#cnote_number').keyup(function(){
        var br_code = $('#br_code').val();
        var stationcode = '<?=$stationcode?>';
        var cnumber = $('#cnote_number').val();
        $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{br_code:br_code,stationcode:stationcode,cnumber:cnumber},
            dataType:"text",
            success:function(response)
            {
               $('#returntable').html(response);
            }
        });
    });
    });
  </script>