<script>
jQuery(function($) {    


    $(".edit_record").click(function() {   
        
      $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'notice_board_info', id: $(this).val() },
            success: function(d) {  
                $('#edit_modal #notice_board_id').val(d.notice_board_id) ; 
                $('#edit_modal #notice_date').val(d.notice_date) ; 
                $('#edit_modal #notice_msg').val(d.notice_msg) ; 
                $('#edit_modal input:radio[value='+ d.status +']').prop('checked', true);
               
            }
         }); 
      
        
                
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'notice_board_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 