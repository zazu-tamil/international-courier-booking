<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script>
jQuery(function($) {    
    
    
     $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    });

    $(".edit_record").click(function() {  
            //alert($(this).val());
          $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'line_haul_info', id: $(this).val() },
            success: function(d) {  
                
              $('#edit_modal #line_haul_id').val(d.line_haul_id) ;     
              $('#edit_modal #co_loader_id').val(d.co_loader_id) ;   
              $('#edit_modal #booking_date').val(d.booking_date) ;   
              $('#edit_modal #origin').val(d.origin) ;   
              $('#edit_modal #destination').val(d.destination) ;   
              $('#edit_modal #service').val(d.service) ;   
              $('#edit_modal #no_of_box').val(d.no_of_box) ;   
              $('#edit_modal #volumetric_weight').val(d.volumetric_weight) ;  
              $('#edit_modal #total_weight').val(d.total_weight) ;  
              $('#edit_modal input:radio[value="'+ d.status +'"]').prop('checked', true); 
              $('#edit_modal input:radio[value="'+ d.v_mode +'"]').prop('checked', true); 
               
              
            }
         });
         
     });
     
     
     $(".upload_record").click(function() {  
            //alert($(this).val());
           $('#upload_modal #line_haul_id').val($(this).val()) ;    
            
          $.ajax({
            url: "<?php echo site_url('get-content');?>",
            type: "post",
            data: { tbl : 'line_haul_upload_info', id: $(this).val(),edit_mode : 0, del_mode : 1 },
            success: function(ctnt) {  
                
              $('#upload_modal .view_upload').html(ctnt) ;
               
              
            }
         });
         
     });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'line_haul_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });
     
     
     $(document).on('click','#upload_modal .btn_chld_del', function () {
        //alert('hi' + $(this).attr('data'));
        
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'line_haul_upload_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         } 
     });     

});
</script> 