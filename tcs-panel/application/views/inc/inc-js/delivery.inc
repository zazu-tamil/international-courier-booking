<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/iCheck/all.css"> 
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/iCheck/icheck.min.js"></script>
<script>
jQuery(function($) { 
   
     $('#awb_no').focus();
     
     $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    });
    
    $('.timepicker').timepicker({
      showInputs: false
    });
   
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-green',
      radioClass   : 'iradio_minimal-green'
    });
    
     $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });
     
    $('.ndr_drp').hide();
    $('.status_opt').on('ifChecked', function(event) {
    	///alert($(this).val());
        if($(this).val() == 'Undelivered')
        {
            $('.ndr_drp').show();
            $('.pod_upd').hide();
            
        } else {
            $('.ndr_drp').hide();
            $('.pod_upd').show();
        }
    }); 
    
    
   /*$('#ndr_id').change(function() {  
     
    if($(this).val()!= '')
    {
        alert($(this).val());
    }
     
   }); */
    
     
     
      
     
     
     
       
       
    
});
</script> 