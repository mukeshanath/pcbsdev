<?php defined('__ROOT__') OR exit('No direct script access allowed'); ?>
</div>
<footer id="footer">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <p class="text-center">
          &copy; PCNL 2020-2021
        </p>
      </div>
    </div>
  </div>
</footer>
<!-- jQuery first, then Popper.js -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<!-- Bootstrap -->

<script type="text/javascript" src="<?php echo __ROOT__ . '/vendor/bootstrap/js/ace-extra.min.js'?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script src="vendor/bootstrap/js/datatable.min.js"></script>

<script src="vendor/bootstrap/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="vendor/bootstrap/js/form_validations.js"></script>

      <!--DataTables-->

<script src="vendor/bootstrap/datatables/dataTables.min.js" ></script>
<script src="vendor/bootstrap/datatables/dataTables.buttons.min.js" ></script>
<script src="vendor/bootstrap/datatables/buttons.flash.min.js "></script>
<script src="vendor/bootstrap/datatables/jszip.min.js "></script>
<script src="vendor/bootstrap/datatables/pdfmake.min.js" ></script>
<script src="vendor/bootstrap/datatables/vfs_fonts.js" ></script>
<script src="vendor/bootstrap/datatables/buttons.html5.min.js" ></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    <script>
    
     var table = $('#data-table').DataTable();
 
        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: [
              'excelHtml5', 
              'pdfHtml5',
            ]
        }).container().appendTo($('#buttons'));

    </script>
    <!-- Data table for customerrate -->
    <script>
    
    var table = $('#ratelist-table').DataTable();

       var buttons = new $.fn.dataTable.Buttons(table, {
           buttons: [
             'excelHtml5',
            //  'csvHtml5',
             'pdfHtml5'
           ]
       }).container().appendTo($('#listbuttons'));

   </script>
    <script>
    
    var table = $('#ratedetail-table').DataTable();

       var buttons = new $.fn.dataTable.Buttons(table, {
           buttons: [
             'excelHtml5',
            //  'csvHtml5',
             'pdfHtml5'
           ]
       }).container().appendTo($('#detailbuttons'));

   </script>
<!-- Datatables end -->
<?php 

date_default_timezone_set('Asia/Kolkata');

$date = new DateTime('now');
$date->modify('last day of this month');

?>
<!-- <script>
    //auto logout after session expire
  setInterval(function() {
    console.log('checking session');
    $.ajax({
            url:"<?php echo __ROOT__ ?>peoplecontroller",
            method:"POST",
            data:{checksessionvalid:'session'},
            dataType:"text",
            success:function(isvalid)
            {
              if(parseInt(isvalid)>550){
                location.href='logout';
              }
              console.log('session time '+isvalid);
            }
      });
  }, 5000*20);
  //auto logout after session expire
  </script> -->
  <script>


  $('.today').val('<?php echo date('Y-m-d'); ?>');

  //$('.today-max').attr('max','<?php echo date('Y-m-d'); ?>');

  $('input[type="date"]').attr('max','<?php echo date('Y-m-d'); ?>');
  $('.lastdate').attr('max','<?php echo $date->format('Y-m-d'); ?>');

  $('.this-month').val('<?php echo date('Y-m'); ?>');

  opencookie();


</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</body>
</html>
