<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/iCheck/all.css">
<script src="<?php echo base_url() ?>asset/plugins/iCheck/icheck.min.js"></script>
<script>
jQuery(function($) { 
   
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-green',
      radioClass   : 'iradio_minimal-green'
    });
    
     $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    }); 
      
     
    
     
     $(".update_record").click(function() {   
      //alert($(this).closest("tr").find(".flg_city").prop('checked'));
      $.ajax({
                url: "<?php echo site_url('update-data');?>",
                type: "post",
                data: { 'tbl' : 'customer-domestic-rate-v2', 
                        'id': $(this).val() ,
                        'customer_id': $(this).attr('customer_data') , 
                        'c_type': $(this).attr('data') , 
                        'flg_region'  : ($(this).closest("tr").find(".flg_region").prop('checked') == true ? 1: 0),
                        'flg_state'  : ($(this).closest("tr").find(".flg_state").prop('checked') == true ? 1: 0),
                        'flg_city'  :  ($(this).closest("tr").find(".flg_city").prop('checked') == true ? 1: 0),
                        'flg_metro'  :  ($(this).closest("tr").find(".flg_metro").prop('checked') == true ? 1: 0),
                        'min_weight' : $(this).closest("tr").find(".min_weight").val(),
                        'min_charges'  : $(this).closest("tr").find(".min_charges").val(),
                        'addt_weight'  : $(this).closest("tr").find(".addt_weight").val(),
                        'addt_charges'  : $(this).closest("tr").find(".addt_charges").val(),
                      },
                success: function(d) {  
                     location.reload();
                }
           });  
      
      
     });  
     
     $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'customer_domestic_rate_info_v2', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  
     
     
        
});     
</script>