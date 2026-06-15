 <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url() ?>asset/bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
jQuery(function($) { 
   
      
     $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    });
    
    
    $('#add_modal #account_head_id').change(function() {  
          $("#add_modal #sub_account_head_id > option").remove();      
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-sub-account-head', id: $(this).val()  },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Sub-Account Head');
                    $('#add_modal #sub_account_head_id').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.sub_account_head_id);
	                    opt1.text(info.sub_account_head_name);
	                    $('#add_modal #sub_account_head_id').append(opt1); 
	                });
                }
         });  
     }); 
     
     
     $('#edit_modal #account_head_id').change(function() {   
          $("#edit_modal #sub_account_head_id > option").remove();      
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-sub-account-head', id: $(this).val()  },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Sub-Account Head');
                    $('#edit_modal #sub_account_head_id').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.sub_account_head_id);
	                    opt1.text(info.sub_account_head_name);
	                    $('#edit_modal #sub_account_head_id').append(opt1); 
	                });
                }
         });  
     }); 
     
     
     $(".edit_record").click(function() {    
     
      $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'cash_outward_info', id: $(this).val() },
            success: function(d1) {  
              //  alert(d.franchise_type_id);
              $('#edit_modal #cash_outward_id').val(d1.cash_outward_id) ;     
              $('#edit_modal #agent_id').val(d1.agent_id) ;     
              $('#edit_modal #outward_date').val(d1.outward_date) ; 
              $('#edit_modal #account_head_id').val(d1.account_head_id); 
              //$('#edit_modal #sub_account_head_id').val(d1.sub_account_head_id); 
              $('#edit_modal #amount').val(d1.amount) ; 
              $('#edit_modal #remarks').val(d1.remarks) ;
              $('#edit_modal input:radio[value='+ d1.status +']').prop('checked', true);  
              
              
               $("#edit_modal #sub_account_head_id > option").remove();      
              $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'get-sub-account-head', id: d1.account_head_id },
                    success: function(d) {  
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Sub-Account Head');
                        $('#edit_modal #sub_account_head_id').append(opt1); 
    			
        				$.each(d,function(key,info) 
    	                {
    	                    var opt1 = $('<option />'); 
    	                    opt1.val(info.sub_account_head_id);
    	                    opt1.text(info.sub_account_head_name);
                            if(d1.sub_account_head_id == info.sub_account_head_id)
                                opt1.attr('selected','selected');
    	                    $('#edit_modal #sub_account_head_id').append(opt1); 
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
                    data: { tbl : 'cash_outward_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });   
    
});
</script>