  
<script>
jQuery(function($) {   
    
    $(".edit_record").click(function() {    
         
      if($(this).val() != 0) {
         $.ajax({   
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'intl_setting_info_v2', id: $(this).val() },
                success: function(d) {     
                    
                    $('#chrg_modal #mode').val('Edit') ; 
                    $('#chrg_modal #intl_setting_id').val(d.intl_setting_id) ; 
                    $('#chrg_modal #service_provider').val(d.service_provider);  
                    $('#chrg_modal #gst').val(d.gst) ;  
                    $('#chrg_modal #fsc').val(d.fsc) ;  
                    $('#chrg_modal #addt_sc').val(d.addt_sc) ;  
                    
                }
          }); 
      } else {
        $('#chrg_modal #mode').val('Add') ; 
        $('#chrg_modal #service_provider').val($(this).attr('data'));  
      }
    });
    
    $(".margin_record").click(function() {    
        
        $('#margin_modal #service_provider').val($(this).attr('data'))
        $('#margin_modal #intl_setting_id').val($(this).val()) ; 
         $.ajax({   
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'intl_fr_ty_margin_info', id: $(this).val() },
                success: function(d) {     
                    
                   //alert(d.length);
                    
                   $.each(d, function(index, det) {
                         //alert(det.franchise_type_id);
                         $('#margin_modal #margin_'+ det.franchise_type_id).val(det.margin);  
                         
                   });
                     
                    
                }
          }); 
      
    });
    
   

});
</script> 