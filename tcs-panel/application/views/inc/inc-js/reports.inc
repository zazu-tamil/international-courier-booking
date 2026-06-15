<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/iCheck/all.css"> 
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/iCheck/icheck.min.js"></script>
<script src="https://rawgit.com/Snack-X/excelexport.js/master/excelexport.js"></script>
<script>
jQuery(function($) {  
    
    
     $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    });
    
    
   
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-green',
      radioClass   : 'iradio_minimal-green'
    });
    
     $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    }); 
    
    $('.select_all').on('ifChecked', function(event) {
    	$('.booking_id').iCheck('check');
    });
    $('.select_all').on('ifUnchecked', function(event) {
    	$('.booking_id').iCheck('uncheck');
    }); 
    
    $('.del_record').click(function() {  
       // alert($(this).attr('data'));
       if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : $(this).attr('data'), id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     }); 
     
     
     $('.btn_paid').click(function() {   
        $('#pay_modal #booking_id').val($(this).val());  
     }); 
     
     $('#pay_modal .btn_paid_save').click(function() {   
             $.ajax({
                    url: "<?php echo site_url('update-data');?>",
                    type: "post",
                    data: { tbl : 'credit-booking-payment', id: $("#pay_modal #booking_id").val(), paid_date: $("#pay_modal #paid_date").val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
        
        
     }); 
     
    
 
	$(".dl-xls").click(function() {   
    
    var ee = excelExport("content-table").parseToCSV().parseToXLS($(this).attr('data')); 
		location.href = ee.getXLSDataURI();
	});
    
    
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox1').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox1').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox1').on('click',function(){
        if($('.checkbox1:checked').length == $('.checkbox1').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    }); 
       
    
}); 
 
</script> 