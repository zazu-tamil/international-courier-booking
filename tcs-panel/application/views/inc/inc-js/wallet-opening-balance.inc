 <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
 <!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
 
<script>
jQuery(function($) {    
    
   $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    }); 
    
   $(".edit_record").click(function() {   
        
      $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'opening_balance_wallet_info', id: $(this).val() },
            success: function(d) {  
                $('#edit_modal #opening_balance_wallet_id').val(d.opening_balance_wallet_id) ; 
                $('#edit_modal #franchise_id').val(d.franchise_id) ; 
                $('#edit_modal #op_date').val(d.op_date) ; 
                $('#edit_modal #amount').val(d.amount) ; 
                $('#edit_modal input:radio[value='+ d.status +']').prop('checked', true);
               
            }
         }); 
      
        
                
    });
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'opening_balance_wallet_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 