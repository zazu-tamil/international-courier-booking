<script>
jQuery(function($) {    


    $(".edit_record").click(function() {  
     
     // alert($(this).closest('tr').find('td').eq(1).text());
      
      var c_name = $(this).closest('tr').find('td').eq(1).text();
      var c_status = $(this).closest('tr').find('td').eq(2).text(); 
      
      $('#edit_modal #package_type_id').val($(this).val()) ; 
      $('#edit_modal #package_type_name').val(c_name) ; 
      $('#edit_modal input:radio[value='+ c_status +']').prop('checked', true); 
      
     
      
       
                
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'country', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 