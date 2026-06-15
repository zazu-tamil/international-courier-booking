  
<script>
jQuery(function($) {   

    $(".edit_record").click(function() {    
         
      $.ajax({   
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'ah_charges_info', id: $(this).val() },
                success: function(d) {   
                    $('#edit_modal #ah_charges_id').val(d.ah_charges_id) ; 
                    $('#edit_modal #ah_charges_type').val(d.ah_charges_type);  
                    $('#edit_modal #ah_charges').val(d.ah_charges) ;   
                    $('#edit_modal input:radio[value='+ d.status +']').prop('checked', true);   
                     
                    
                    
                }
          }); 
                
    }); 
    
   $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'ah_charges_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 