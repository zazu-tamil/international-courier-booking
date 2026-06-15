<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/iCheck/all.css"> 
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url() ?>asset/bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
jQuery(function($) { 
    
    $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    }); 
   
   $(".btn_restore").click(function() {  
           // alert($(this).val());
             $.ajax({
                        url: "<?php echo site_url('update-data');?>",
                        type: "post",
                        data: { tbl : 'booking_restore', id: $(this).val() },
                        success: function(ctnt) {                                 
                             
                            alert(ctnt);
                            location.reload();
                            
                        }
              });  
             
      }); 
      
     
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'delete_booking_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });       
    
});
</script>     