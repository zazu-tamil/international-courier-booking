<script>
jQuery(function($) {   
    
    
    $('#add_modal #state_code').change(function() {  
        $("#add_modal #branch_code > option").remove(); 
        $("#add_modal #ops_by > option").remove(); 
        //$("#add_modal #servicable_pincode > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select City Code');
                    $('#add_modal #branch_code').append(opt1); 
                     
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.city_code);
	                    opt1.text(info.city_name + '[ ' + info.city_code  + ' ]');
	                    $('#add_modal #branch_code').append(opt1);  
	                });
                    
                    
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Branch Code'); 
                    $('#add_modal #ops_by').append(opt1); 
                    $.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.city_code);
	                    opt1.text(info.city_name + '[ ' + info.city_code  + ' ]');
	                    //$('#add_modal #branch_code').append(opt1); 
	                    $('#add_modal #ops_by').append(opt1); 
	                });
                }
         });  
     });  


    $(".edit_record").click(function() { 
        
         $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'get-servicable-pincode', id: $(this).val() },
            success: function(d) {  
                
                
              $('#edit_modal #pincode_id').val(d.pincode_id) ; 
              $('#edit_modal #pincode').val(d.pincode) ; 
              $('#edit_modal #area').val(d.area) ; 
              $('#edit_modal input:radio[name="premium_express"][value="'+ d.premium_express +'"]').prop('checked', true); 
              $('#edit_modal input:radio[name="business_express"][value="'+ d.business_express +'"]').prop('checked', true); 
              $('#edit_modal input:radio[name="metro_city"][value="'+ d.metro_city +'"]').prop('checked', true); 
              $('#edit_modal input:radio[name="serve_type"][value="'+ d.serve_type +'"]').prop('checked', true); 
              
               
              $('#edit_modal #state_code').val(d.state_code) ;  
              $('#edit_modal #zone').val(d.zone) ;  
              $('#edit_modal #branch_code').val(d.branch_code) ;  
              $('#edit_modal #ops_by').val(d.ops_by) ;  
              $('#edit_modal #service_by').val(d.service_by) ;  
              $('#edit_modal input:radio[value='+ d.status +']').prop('checked', true);  
              
              
                $("#edit_modal #branch_code > option").remove(); 
                $("#edit_modal #ops_by > option").remove();      
                  $.ajax({
                        url: "<?php echo site_url('get-data');?>",
                        type: "post",
                        data: { tbl : 'state-city', id: d.state_code },
                        success: function(d1) {  
                            var opt1 = $('<option />'); 
                            opt1.val('');
                            opt1.text('Select City Code');
                            $('#edit_modal #branch_code').append(opt1); 
                             
        			
            				$.each(d1,function(key,info) 
        	                {
        	                    var opt1 = $('<option />'); 
        	                    opt1.val(info.city_code);
        	                    opt1.text(info.city_name + '[ ' + info.city_code  + ' ]');
                                if(info.city_code == d.branch_code)
                                    opt1.attr('selected','selected');
        	                    $('#edit_modal #branch_code').append(opt1);  
        	                });
                            
                            
                            var opt1 = $('<option />'); 
                            opt1.val('');
                            opt1.text('Select Branch Code'); 
                            $('#edit_modal #ops_by').append(opt1); 
                            $.each(d1,function(key,info) 
        	                {
        	                    var opt1 = $('<option />'); 
        	                    opt1.val(info.city_code);
        	                    opt1.text(info.city_name + '[ ' + info.city_code  + ' ]');
        	                    if(info.city_code == d.ops_by)
                                    opt1.attr('selected','selected');
        	                    $('#edit_modal #ops_by').append(opt1); 
        	                });
                        }
                 }); 
              
                 
            }
        
        });
    });
    
    $('#add_modal #pincode').change(function() { 
        
         $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'pin-state', id: $(this).val() },
            success: function(d) {  
              // alert(d.state.length);
              if(d.state.length != '' )
              {
                alert('Pincode Already Exist');
                $(this).focus();
              } 
            }
          });  
    });  
    
    
    
    /*    
    $(".edit_record").click(function() {  
     
     // alert($(this).closest('tr').find('td').eq(1).text());
      
      var c_pincode = $(this).closest('tr').find('td').eq(1).text();
      var c_area = $(this).closest('tr').find('td').eq(2).text();  
      var c_premium_express = $(this).closest('tr').find('td').eq(3).text();  
      var c_business_express = $(this).closest('tr').find('td').eq(4).text();  
      var state_code = $(this).closest('tr').find('td').eq(5).text();  
      var zone = $(this).closest('tr').find('td').eq(6).text();  
      var branch_code = $(this).closest('tr').find('td').eq(7).text();  
      var ops_by = $(this).closest('tr').find('td').eq(8).text();   
      var service_by = $(this).closest('tr').find('td').eq(9).text();  
      var c_status = $(this).closest('tr').find('td').eq(10).text();  
      
      $('#edit_modal #pincode_id').val($(this).val()) ; 
      $('#edit_modal #pincode').val(c_pincode) ; 
      $('#edit_modal #area').val(c_area) ; 
      $('#edit_modal #premium_express').val(c_premium_express) ;  
      $('#edit_modal #business_express').val(c_business_express) ;  
      $('#edit_modal #state_code').val(state_code) ;  
      $('#edit_modal #zone').val(zone) ;  
      $('#edit_modal #branch_code').val(branch_code) ;  
      $('#edit_modal #ops_by').val(ops_by) ;  
      $('#edit_modal #service_by').val(service_by) ; 
      $('#edit_modal input:radio[value='+ c_status +']').prop('checked', true);  
    });
    
    */
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'servicable_pincode', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });
     
       

});
</script> 