<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    $module = 'extinvoicedetail';
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


if(isset($_GET['invno'])){
     $extcustomer = $datafetch['cust_code'];
    $get_from_invoice  = $db->query("SELECT * FROM invoice WHERE invno='".$_GET['invno']."' AND comp_code='$stationcode' AND cust_code='$extcustomer'");
    $rowinv = $get_from_invoice->fetch(PDO::FETCH_OBJ);
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
                                        <a href="<?php echo __ROOT__ . 'extinvoicelist'; ?>">Invoice list</a>
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
                                  Invoice Added successfully.
                                </div>";
                  }

                   if ( isset($_GET['success']) && $_GET['success'] == 2 )
                  {
                       echo 

                  "<div class='alert alert-success alert-dismissible'>
                                  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                  <h4><i class='icon fa fa-check'></i> Alert!</h4>
                                  Invoice updated successfully.
                                </div>";
                  }

                   ?>
                
             </div>

            <form method="POST"  >

             <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    
             <input placeholder="" class="form-control stncode" name="stncode" type="text" id="stncode" style="display:none;"> 

            <div class="boxed boxBt panel panel-primary">
              <div class="title_1 title panel-heading">
                    <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice Customer Detail</p>
                    <div class="align-right" style="margin-top:-35px;">

         <!-- <a style="color:white" href="<?php echo __ROOT__ ?>invoice"><button type="button" class="btn btn-success btnattr" >
         <i class="fa fa-plus" ></i>
        </button></a> -->

        <a style="color:white" href="<?php echo __ROOT__ ?>invoicelist"><button type="button" class="btn btn-danger btnattr" >
                <i class="fa fa-times" ></i>
        </button></a>

        </div>
              
                </div> 
      <div class="content panel-body">


        <div class="tab_form">


          <table class="table" cellspacing="0" cellpadding="0" align="center">	
            <tbody>
              <tr>		
                  <td>Invoice No</td>
                  <td>
                  <div class="col-sm-12">
                        <input placeholder="" class="form-control border-form" name="inv_no" type="text" id="inv_no" value='<?=$_GET['invno'];?>' readonly>
                   </div>
                     </td>
                    
                  <td>Company Code</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="comp_code" type="text" id="comp_code" value="<?=$rowinv->comp_code;?>" readonly>
                    </div>
                      </td>

                    <td>Branch Code</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="br_code" type="text" id="br_code" value="<?=$rowinv->br_code;?>" readonly>
                    </div>
                      </td>

                      <td>Customer Code</td>
                  <td>
                  <div class="col-sm-12">
                      <input placeholder="" class="form-control border-form" name="cust_code" type="text" id="cust_code" value="<?=$rowinv->cust_code;?>" readonly>
                    </div>
                      </td>
                  
                 
                  </tr>	
                    </tbody>
                  </table>
             </form>
                <!-- Fetching Mode Details in Table  -->
                <div class="col-sm-12 data-table-div" >

                 <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoiced Cnote List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
           </div>
           <table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
                
              </th>
             <!-- <th >Invoice no</th>
                    <th >Company Code</th>
                    <th >Branch Code</th>
                    <th >Customer Code</th> -->
                    <!-- <th >Cnote Serial</th> -->
                    <th >Cnote Number</th>
                    <th >Bill Wt</th>
                    <th >Bill Rate</th>
                    <th >Bill Mode</th>
                    <th >Bill Dest</th>
                    <th >Booking Date</th>
              </tr>
    </thead>
              <tbody>
              <?php 

          if(isset($_GET['invno']))
           {
               $invno = $_GET['invno'];
              // $verifytb = $db->query("SELECT * FROM invoice WHERE invno = '$invno'  AND comp_code='$stationcode'")->fetch(PDO::FETCH_OBJ);
        
                    $query = $db->query("SELECT * FROM credit WHERE invno = '$invno' AND comp_code='$stationcode' AND cust_code='$extcustomer' ORDER BY tdate");
                    while ($row = $query->fetch(PDO::FETCH_OBJ))
                    {
                     $origDate = "$row->tdate";
                     $newDate = date("d-m-Y", strtotime($origDate));
                      echo "<tr>";
                      echo "<td class='center first-child'>
                              <label>
                                 <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                                  <span class='lbl'></span>
                               </label>
                            </td>";
                      echo "<td>".$row->cno."</td>";
                      echo "<td>".number_format($row->wt,3)."</td>";
                      echo "<td>".number_format($row->amt,2)."</td>";
                      echo "<td>".$row->mode_code."</td>";
                      echo "<td>".$row->dest_code."</td>";
                      echo "<td>".$newDate."</td>";    
                             echo "</tr>";
                     
                    }          
           }

     ?>
                 </tbody>
            </table>
              </div>
             
        </div>
        <div style="border:1px solid #2F70B1;margin-top:6px;background:#fff;">
 
 <table id="formPOSpurchase" class="table" cellspacing="0" cellpadding="0" align="center" >	
<tbody>


<tr> 
<td>Total</td>
 <td>
 <input  id="total" name="total" value="<?=round($rowinv->grandamt); ?>" class="form-control input-sm"  type="text"  readonly>
 </td>
 <td>FSC</td>
 <td>
 <input class="form-control input-sm " value="<?=round($rowinv->fsc); ?>"  name="fsc_hc" type="text" id="fsc_hc" readonly>
 </td>
 <td>Invoice Amount</td>
 <td>
 <input class="form-control input-sm " value="<?=round($rowinv->invamt); ?>"  name="fsc_hc" type="text" id="fsc_hc" readonly>
 </td>
 <td>GST</td>
 <td><input  class="form-control input-sm " value="<?=round($rowinv->igst+$rowinv->cgst+$rowinv->sgst+$rowinv->STax); ?>" name="s_tax" type="text" id="s_tax"  readonly></td>
 <td>Discount</td>
  <td><input class="form-control input-sm " value="0" name="discount" type="text" id="discount" ></td>
 <td>Net Amount</td>
 <td><input class="form-control input-sm " value="<?=round($rowinv->netamt); ?>" name="net_amt" type="text" id="net_amt" readonly></td>

</tr> 
</tbody></table>
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
    document.getElementById('br_code').value = "";
    document.getElementById('cust_code').value = ""; 
    document.getElementById('inv_no').value = "";        
}  
</script>

  