<script>
jQuery(function($) {   
    
   
    
     $(".edit_record").click(function() {   
                  
         $.ajax({
                   // url: "send-enquiry.php",
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'crit_transhipment_rate_info', id: $(this).val() },
                    success: function(d) {                                 
                        // alert(d.c_type);
                        $('#edit_modal #transhipment_rate_id').val(d.transhipment_rate_id) ;  
                        $('#edit_modal #c_type').val('Transhipment Weight') ;  
                        $('#edit_modal #from_weight').val(d.from_weight) ;  
                        $('#edit_modal #to_weight').val(d.to_weight) ;  
                        //$('#edit_modal input:radio[value="'+ d.c_type +'"]').prop('checked', true);
                        if(d.flg_region == '1')
                            $('#edit_modal #flg_region').prop('checked', true);
                        if(d.flg_state == '1')
                            $('#edit_modal #flg_state').prop('checked', true);
                        if(d.flg_city == '1')
                            $('#edit_modal #flg_city').prop('checked', true);
                        if(d.flg_metro == '1')
                            $('#edit_modal #flg_metro').prop('checked', true);
                        
                        $('#edit_modal #from_weight').val(d.from_weight) ; 
                        $('#edit_modal #to_weight').val(d.to_weight) ;  
                        $('#edit_modal #min_charges').val(d.min_charges) ;  
                        $('#edit_modal #delivery_charges').val(d.delivery_charges) ;  
                       
                        $('#edit_modal input:radio[value='+ d.status +']').prop('checked', true);  
                    }
          }); 
                    
        });
    
    
    
     
    
    
} );
</script> 