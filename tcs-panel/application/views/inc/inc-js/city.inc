<script>
jQuery(function($) {    


    $(".edit_record").click(function() {  
     
     // alert($(this).closest('tr').find('td').eq(1).text());
      
      var s_code = $(this).closest('tr').find('td').eq(1).text();
      var c_name = $(this).closest('tr').find('td').eq(2).text(); 
      var c_code = $(this).closest('tr').find('td').eq(3).text(); 
      var c_status = $(this).closest('tr').find('td').eq(4).text(); 
      
      $('#edit_modal #city_id').val($(this).val()) ; 
      $('#edit_modal #city_name').val(c_name) ; 
      $('#edit_modal #state_code').val(s_code) ; 
      $('#edit_modal #city_code').val(c_code) ; 
      $('#edit_modal input:radio[value='+ c_status +']').prop('checked', true);  
       
                
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'city_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 