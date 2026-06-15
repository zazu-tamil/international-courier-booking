<script>
jQuery(function($) {    


    $(".edit_record").click(function() {  
     
     // alert($(this).closest('tr').find('td').eq(1).text());
      
      var ndr_code = $(this).closest('tr').find('td').eq(1).text();
      var ndr_details = $(this).closest('tr').find('td').eq(2).text(); 
      var c_status = $(this).closest('tr').find('td').eq(3).text(); 
      
      $('#edit_modal #ndr_id').val($(this).val()) ; 
      $('#edit_modal #ndr_code').val(ndr_code) ; 
      $('#edit_modal #ndr_details').val(ndr_details) ; 
      $('#edit_modal input:radio[value='+ c_status +']').prop('checked', true);  
                
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'NDR', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 