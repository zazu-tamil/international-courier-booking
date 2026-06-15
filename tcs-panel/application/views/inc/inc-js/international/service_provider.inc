  
<script>
jQuery(function($) {  
     
    

    $(".edit_record").click(function() {    
         
      $.ajax({   
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'service_provider_info', id: $(this).val() },
                success: function(d) {                                 
                    //alert(d.cp_percentage);
                    $('#edit_modal #international_service_provider_id').val(d.international_service_provider_id) ; 
                    $('#edit_modal #international_service_provider').val(d.international_service_provider);  
                    $('#edit_modal #logo_url').val(d.logo_url) ;  
                    $('#edit_modal #tracking_url').val(d.tracking_url) ;  
                    $('#edit_modal input:radio[value='+ d.status +']').prop('checked', true);   
                     
                    
                    
                }
          }); 
                
    });
    
    
     
    
    
   /* $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'carrier', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  */

});
</script> 