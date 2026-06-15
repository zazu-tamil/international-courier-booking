<script>
jQuery(function($) {    


    $(".edit_record").click(function() {  
     
     // alert($(this).closest('tr').find('td').eq(1).text());
      
      var c_pincode = $(this).closest('tr').find('td').eq(1).text();
      var c_area = $(this).closest('tr').find('td').eq(2).text();  
      var c_status = $(this).closest('tr').find('td').eq(5).text();  
      
      $('#edit_modal #pincode_id').val($(this).val()) ; 
      $('#edit_modal #pincode').val(c_pincode) ; 
      $('#edit_modal #area_name').val(c_area) ; 
      $('#edit_modal #district_name').val($(this).closest('tr').find('td').eq(3).text()) ; 
      $('#edit_modal #state_name').val($(this).closest('tr').find('td').eq(4).text()) ; 
      $('#edit_modal input:radio[value='+ c_status +']').prop('checked', true);  
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'pincode', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 