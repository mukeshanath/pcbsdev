<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function authorize($db,$user)
{
    
    $module = 'reviseinvoice';
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
                                            <a href="#">Dashboard</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Invoice</li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Invoice Revise</li>
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
                        Branch Added successfully.
                      </div>";
        }

        if ( isset($_GET['success']) && $_GET['success'] == 3 )
        {
            echo 

        "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Alert!</h4>
                        Branch updated successfully.
                      </div>";
        }

        ?>
                
             </div>
  

     

      <div class="boxed boxBt panel panel-primary">
        <div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice Revise</p>
         <div class="align-right" style="margin-top:-35px;">

        
        <a style="color:white" href="<?php echo __ROOT__ ?>dashboard"><button type="button" class="btn btn-danger btnattr" >
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
        <td>Category</td>
        <td>
        <div class="autocomplete col-sm-12">
              <input class="form-control input-sm"  name="trans_date_from" type="text" id="cust_code">
              </div>
        </td>	
        <td>Branch Code</td>
        <td>
                <input id="trans_date_to" name="trans_date_to" type="text" class="form-control input-sm" >
        </td>
        <td>Client Code</td>
            <td>
            <input  class="form-control input-sm "   name="br_code" type="text" id="br_code">
            </td>
            <td>Client Name</td>
            <td><input  class="form-control input-sm "  name="br_name" type="text" id="br_name" ></td></tr>
     
        </tr>
        <tr>
       
        <td>Invoice No</td>
        <td>
        <input  type="text" id="inv_date" name="inv_date" class="form-control input-sm">
           
        </td>
        <td >Invoice Date</td>
            <td><input class="form-control input-sm "  name="customer" type="date" id="customer"></td>
           	
        <td>Invoice Amount</td>
        <td>
        <input  type="text" id="inv_date" name="inv_date" class="form-control input-sm">
           
        </td>
        <td>Balance</td>
        <td>
            <input  class="form-control input-sm cnote_count" name="inv_no" type="text" id="inv_no">
            </td>
        </tr> 
        <tr>
  
          <tr>
          <td>Transaction</td>
          <td><input class="form-control input-sm "  name="customer" type="date" id="customer"></td>
          <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to</td>
          <td><input class="form-control input-sm "  name="customer" type="date" id="customer"></td>
         
        </tr>
       </tbody>
     </table>
     <table>

       
        </table> 

     
            <div class="align-right btnstyle" style="margin-top:-10px;">
                <button type="submit" class="btn btn-success btnattr_cmn" >
                <i class="fa fa-save"></i>&nbsp; Revise
                </button>

                <button type="submit" class="btn btn-primary btnattr_cmn" >
                <i class="fa  fa-save"></i>&nbsp; Cancel
                </button>
                                                                                  
                <button type="button" class="btn btn-warning btnattr_cmn" onclick="resetFunction()">
                <i class="fa fa-eraser"></i>&nbsp; Clear
                </button>   
            </div>
    

     </form>

     </div> 

<!-- Fetching Mode Details in Table  -->
<div class="col-11 data-table-div" >

<div class="title_1 title panel-heading">
              <p style="font-weight:600;font-size:20px;color:white;margin-top:6px;">Invoice Revise List</p>
              <div class="align-right" style="margin-top:-33px;" id="buttons"></div>
        </div>
        <!-- <div  style="height:400px;overflow-y:scroll"> -->
<table id="data-table" class="table data-table" style="width:100%" cellspacing="0" cellpadding="0" align="center">
        <thead><tr class="form_cat_head">
             <th class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" />
                <span class="lbl"></span>
                </label>
              </th>
            
                    <th >Mf_No</th>
                    <th >Date</th>
                    <th >Cnote Number</th>
                    <th >Destination</th>
                    <th >Weight</th>
                    <th >Mod</th>
                    <th >Amount</th>
                    <th >User</th>
                    <th >Op.Destination</th>
                    <th >Remerks</th>
              </tr>
    </thead>
              <tbody>
                  <?php 

                        //  $query = $db->query("SELECT * FROM cnotenumbertb WHERE cnote_status='Procured-void'  ORDER BY cnotenoid ASC");
                   
                
                        //  while ($row = $query->fetch(PDO::FETCH_OBJ))
                        //   {
                        //     echo "<tr>";
                        //     echo "<td class='center first-child'>
                        //             <label>
                        //                <input type='checkbox' name='chkIds[]' value='1' class='ace' />
                        //                 <span class='lbl'></span>
                        //              </label>
                        //           </td>";
                           
                        //     echo "<td>".$row->cnotenoid."</td>";
                        //     echo "<td>".$row->date_added."</td>";
                        //     echo "<td>".$row->cnote_number."</td>";
                        //     echo "<td>".$row->cnote_station."</td>";
                        //     echo "<td>".$row->cnote_serial."</td>";
                        //     echo "<td>".$row->cnote_status."</td>";
                        //     echo "<td>".$row->cnotenoid."</td>";
                        //     echo "<td>".$row->user_name."</td>";
                        //     echo "<td>".$row->cnote_station."</td>";
                        //     echo "<td>".$row->cust_name."</td>";
                          
                        //            echo "</tr>";
                           
                                                    
                        //   }
                          
                      ?>

                   
                 </tbody>
            </table>
            
              <!-- </div> -->
 
              </div>
            <div style="border:1px solid #2F70B1;margin-top:6px;background:#fff;">
 
            <table id="formPOSpurchase" class="table" cellspacing="0" cellpadding="0" align="center">	
        <tbody>
       
 
        <tr> 
         <td>Total</td>
            <td>
            <input  id="total" name="total" class="form-control input-sm"  type="text" >
            </td>
            <td>FSC HC</td>
            <td>
            <input class="form-control input-sm "   name="fsc_hc" type="text" id="fsc_hc">
            </td>
            <td>GST</td>
            <td><input  class="form-control input-sm "  name="s_tax" type="text" id="s_tax" ></td>
            <td>Net Amount</td>
            <td><input class="form-control input-sm "  name="net_amt" type="text" id="net_amt"></td>
            <td>CNote Advance</td>
            <td><input class="form-control input-sm "  name="cnote_advance" type="text" id="cnote_advance"></td>
            <td><input class="form-control input-sm "  name="customer" type="text" id="customer"></td>
            <td>Special Discount</td>
            <td><input class="form-control input-sm "  name="cnote_advance" type="text" id="cnote_advance"></td>
     

        <!-- </tr> 
        <td>Special Discount</td>
            <td><input class="form-control input-sm "  name="cnote_advance" type="text" id="cnote_advance"></td>
        <tr>
        </tr> -->
        </tbody></table>
              </div>
              </div>
          </div>
          </div>
</section>

<script>
  $(document).ready(function(){
        $('#data-table').DataTable();
    });
    </script>