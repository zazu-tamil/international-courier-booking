<script>
jQuery(function($) {    


    $(".edit_record").click(function() {  
     
     // alert($(this).closest('tr').find('td').eq(1).text());
      
      var c_1 = $(this).closest('tr').find('td').eq(1).text();
      var c_2 = $(this).closest('tr').find('td').eq(2).text(); 
      var c_3 = $(this).closest('tr').find('td').eq(3).text(); 
      var c_4 = $(this).closest('tr').find('td').eq(4).text(); 
      
      $('#edit_modal #hub_branch_id').val($(this).val()) ; 
      $('#edit_modal input:radio[value='+ c_1 +']').prop('checked', true); 
      $('#edit_modal #hub_branch_name').val(c_2) ; 
      $('#edit_modal #hub_branch_code').val(c_3) ; 
      $('#edit_modal input:radio[value='+ c_4 +']').prop('checked', true);  
                
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'hub_branch_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 