<script>
jQuery(function($) {    


     $(".edit_record").click(function() {  
            //alert($(this).val());
          $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'customer_invoice_info', id: $(this).val() },
            success: function(d) {  
               //alert(d.invoice_amount);
              $('#edit_modal #customer_invoice_id').val(d.customer_invoice_id) ; 
              if(d.rec_amt > 0 || d.rec_amt != '')   
                $('#edit_modal #rec_amt').val(d.invoice_amount) ;     
              else
                $('#edit_modal #rec_amt').val(d.rec_amt) ;     
              $('#edit_modal #rec_date').val(d.rec_date) ;   
              $('#edit_modal input:radio[value="'+ d.status +'"]').prop('checked', true); 
              
              
               }
         }); 
         
     });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'crit_customer_invoice_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 