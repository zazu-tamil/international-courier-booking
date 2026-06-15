<script>
jQuery(function($) {    

    
    $('#add_modal #type').change(function() {  
          $("#add_modal #account_head_id > option").remove();      
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-account-head-type', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Account Head');
                    $('#add_modal #account_head_id').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.account_head_id);
	                    opt1.text(info.account_head_name);
	                    $('#add_modal #account_head_id').append(opt1); 
	                });
                }
         });  
     });  
    
    
    $(".edit_record").click(function() {  // alert($(this).val());
     
      $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'sub_account_head_info', id: $(this).val() },
            success: function(d1) {  
              //  alert(d.franchise_type_id);
              $('#edit_modal #sub_account_head_id').val(d1.sub_account_head_id) ;     
              $('#edit_modal #type').val(d1.type) ;     
              $('#edit_modal #sub_account_head_name').val(d1.sub_account_head_name) ; 
              $('#edit_modal input:radio[value='+ d1.status +']').prop('checked', true);  
              
              
              $("#edit_modal #account_head_id > option").remove();      
              $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'get-account-head-type', id: d1.type },
                    success: function(d) {  
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Account Head');
                        $('#edit_modal #account_head_id').append(opt1); 
    			
        				$.each(d,function(key,info) 
    	                {
    	                    var opt1 = $('<option />'); 
    	                    opt1.val(info.account_head_id);
    	                    opt1.text(info.account_head_name);
                            if(d1.account_head_id == info.account_head_id)
                                opt1.attr('selected','selected');
    	                    $('#edit_modal #account_head_id').append(opt1); 
    	                });
                    }
             });   
              
            }
         });
                
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'sub_account_head_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 