<?php defined('__ROOT__') OR exit('No direct script access allowed'); 
include 'db.php';

if(!isset($_SESSION['user_name']))  
{  
   header("location: login?unsuccess=3");  
} 

function loadmode($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM mode ORDER BY mid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->mode_name.'">'.$row->mode_name.'</option>';
         }
         return $output;    
        
    }

    function loadzone($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM zone ORDER BY znid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->zncode.'">'.$row->zncode.'</option>';
         }
         return $output;    
        
    }
    
    function loadgroup($db)
    {
        $output='';
        $query = $db->query('SELECT * FROM group ORDER BY gpid ASC');
       
         while ($row = $query->fetch(PDO::FETCH_OBJ)){
            
        //menu list in home page using this code    
        $output .='<option  value="'.$row->cate_code.'">'.$row->cate_code.'</option>';
         }
         return $output;    
        
    }
   
?>

<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollable padder" style="background-color : #eee;">  


<div id="col-form1" class="col_form lngPage col-sm-25">
<div class="au-breadcrumb m-t-75" style="margin-bottom:-5px;">
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
            <form method="POST" action="<?php echo __ROOT__ ?>boardrate" accept-charset="UTF-8" class="form-horizontal" id="formcustomerrate" enctype="multipart/form-data">
            <input placeholder="" class="form-control border-form" name="user_name" type="text" id="user_name" value="<?php echo $_SESSION['user_name']; ?>" style="display:none;">    

	        <div class="boxed boxBt panel panel-primary">
            <div class="title_1 title panel-heading"style=" height:50px;margin-bottom:10px;">
                  <p style="font-weight:600;font-size:20px;color:white;margin-top:10px;">BOARD RATE MASTER FORM</p>
             <div class="align-right" style="margin-top:-35px;">

             <button type="submit" class="btn btn-success" name="addcustomerrate">
                <i class="fa fa-star"></i>&nbsp; Submit
                </button>

                <button type="submit" class="btn" name="updatecustomerrate">
                <i class="fa fa-star"></i>&nbsp; Update
                </button>
                                            
                <button type="button" class="btn btn-warning" onclick="resetFunction()">
                <i class="fa fa-map-marker"></i>&nbsp; Reset
                </button>    
              </div>
               </div> 
	        <div class="content panel-body">
	
            <div class="tab_form">

                
            <table id="formPOSpurchase" class=" table" cellspacing="0" cellpadding="0" align="center">
        
		<tbody>
        <tr>
        <h3> Customer Rate: </h3>
        </tr>
        <tr>		
			<td>Station Code</td>
            <td>
                <input placeholder="" style="background: ;" class="form-control border-form" name="stncode" type="text" id="stncode">
            </td>	
			<td>Station Name</td>
			<td>
                <input class="form-control border-form "  name="stnname" type="text" id="stnname" placeholder="Station Name" >
			</td>
			
			<td>Branch Code</td>
			<td>
                 <input placeholder="" style="background: ;" class="form-control border-form" name="br_code" type="text" id="br_code">
			</td>	
			
		
			<td>Branch Name</td>
			<td>
                 <input class="form-control border-form"  name="br_name" type="text" id="br_name" placeholder="Branch Name" >
			</td>			
		</tr>
		<tr>
            <td>
                    <select class="form-control border-form"type="text" name="cnote_serial">
                    <option value="0">Select type</option>
                    <?php echo loadgroup($db); ?>
                    </select>
            </td>
            <td>
                 <input class="form-control border-form"  name="sel_destin" type="text" id="sel_destin" >
            </td>
        </tbody>     
    </table>
   
	<div class="col-sm-13" style=" overflow-y : hidden; border-bottom: 1px solid #d5cece; ">
	    <table class="table"  cellspacing="0" cellpadding="0" style="margin-left:0px;">
	        <tbody  id="dynamic_form_br">
                <tr class="form_cat_head" style="background-color : #3F5569; color : #fff;">
                
                    <td>  Zone</td>
                    <td>Mode</td>
                    <td>0-100gm</td>
                    <td>101-250gm</td>
                    <td>251-400gm</td>
                    <td>401-500gm</td>
                    <td>501gm-1kg</td>
                    <td>1-2kg</td>
                    <td>2kg above</td>
                    <td></td>
                </tr>
                <tr>
                    
                    <td>
                    <select  class="zone" type="text" name="zone[]">
                    <option value="0">Select</option>
                    <?php echo loadzone($db); ?>
                    </select>
                    </td>
                    <td><select  type="text" name="mode[]">
                    <option value="0">Select</option>
                    <?php echo loadmode($db); ?>
                    </select>
                    </td>
                    <td><input style="width:100%;" type="text" name="wt0[]"></td>
                    <td><input style="width:100%;" type="text" name="wt101[]"></td>
                    <td><input style="width:100%;" type="text" name="wt251[]"></td>
                    <td><input style="width:100%;" type="text" name="wt401[]"></td>
                    <td><input style="width:100%;" type="text" name="wt501[]"></td>
                    <td><input style="width:100%;" type="text" name="wt1kg[]"></td>
                    <td><input style="width:100%;" type="text" name="wt2kgabove[]"></td>
                    <td><button type="button" class="btn btn-success btn-xs" id="btn" ><i class="fa fa-plus-circle"></i></button></td>
                </tr>
            </tbody>
        </table>
	</div>

	</form>
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

<!-- inline scripts related to this page -->
<script>
function resetFunction() {
  document.getElementById("formcustomerrate").reset();
}
</script>

<script>
 var btn=document.querySelector("#btn");
 btn.onclick=function(){
     var div=document.createElement("tr");
     div.setAttribute("id","form_group");
     div.innerHTML=gen();
     document.getElementById("dynamic_form_br").appendChild(div); 
    }
     function gen()
     {
        return '<td><select type="text" name="zone[]" class="zone"><option value="0">Select</option><?php echo loadzone($db); ?></select></td><td><select type="text" name="mode[]"><option value="0">Select</option><?php echo loadmode($db); ?></select></td><td><input style="width:100%;" type="text" name="wt0[]"></td><td><input style="width:100%;" type="text" name="wt101[]"></td><td><input style="width:100%;" type="text" name="wt251[]"></td><td><input style="width:100%;" type="text" name="wt401[]"></td><td><input style="width:100%;" type="text" name="wt501[]"></td><td><input style="width:100%;" type="text" name="wt1kg[]"></td><td><input style="width:100%;" type="text" name="wt2kgabove[]"></td><td><button type="button" class="btn btn-danger btn-xs" onclick="remove(this)" id="remove" ><i class="fa fa-minus-circle"></i></button></td>';
     }
     function remove(div)
     {
         document.getElementById("form_group").remove();
     }
 

     $(document).ready(function(){
        $(document).on('change','.zone',function (e) {
        var zone =  $(this).val();

        $.ajax({
            url:"<?php echo __ROOT__ ?>fetch",
            method:"POST",
            data:{zone:zone},
            dataType:"text",
            success:function(data)
            {  
                   var desti = '#sel_destin';
                   $(desti).val(data);
            }
    });
    });
  });     

 </script>