<script>
jQuery(function($) { 
      
    $('#add_modal #state_code').change(function() {  
        $("#add_modal #city_code > option").remove(); 
        $("#add_modal #servicable_pincode > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select City Code');
                    $('#add_modal #city_code').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.city_code);
	                    opt1.text(info.city_name + '[ ' + info.city_code  + ' ]');
	                    $('#add_modal #city_code').append(opt1); 
	                });
                }
         });  
     });  
     
     
     
     
     $('#add_modal #city_code').change(function() {  
        $("#add_modal #servicable_pincode > option").remove();    
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'city-serv-pincode', id: $(this).val() },
                success: function(d) {  
                   /* var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Pincode');
                    $('#servicable_pincode').append(opt1);*/ 
			
    				 $.each(d,function(key,info) 
    	                {
    	                    //var opt1 = $('<option />'); 
    	                    //opt1.val(info.pincode);
    	                    //opt1.text(info.pincode);
    	                    //$('#servicable_pincode').append(opt1); 
                            var newOption = new Option(info.pincode, info.pincode, false, false);
                            $('#add_modal #servicable_pincode').append(newOption).trigger('change');
    	                });  
                        
                       /* $(".servicable_pincode").select2({
                          data: d
                        })*/
                        
                        
                }
         });
          
     });

     $(".edit_record").click(function() {  
            //alert($(this).val());
          $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'get-agent', id: $(this).val() },
            success: function(d) {  
                
              $('#edit_modal #agent_id').val(d.agent_id) ;     
              $('#edit_modal #contact_person').val(d.contact_person) ;   
              $('#edit_modal #phone').val(d.phone) ;   
              $('#edit_modal #mobile').val(d.mobile) ;   
              $('#edit_modal #email').val(d.email) ;    
              $('#edit_modal #address').val(d.address) ;   
              $('#edit_modal #state_code').val(d.state_code) ;   
               
              $('#edit_modal input:radio[value="'+ d.status +'"]').prop('checked', true); 
              
              //alert(d.servicable_pincode);
              $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: d.state_code },
                success: function(d) {  
                    $("#edit_modal #city_code > option").remove(); 
                    var opt1 = $('<option />'); 
                    opt1.val('0');
                    opt1.text('Select City Code');
                    $('#edit_modal #city_code').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                      if(d.city_code = info.city_code) {  
	                           var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, true);
	                      } else {
                               var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, false);
                          }
                         $('#edit_modal #city_code').append(newOption).trigger('change')  ;
	                }); 
                   
                    }
                });  
                
                $.ajax({
                        url: "<?php echo site_url('get-data');?>",
                        type: "post",
                        data: { tbl : 'city-serv-pincode', id: d.city_code },
                        success: function(d1) {  
                             $("#edit_modal #servicable_pincode > option").remove();
                             var ob = d.servicable_pincode.split(','); 
            				 $.each(d1,function(key,info) 
                                {   
                                  if(jQuery.inArray( info.pincode, ob ) != '-1')  {
                                    var newOption = new Option(info.pincode, info.pincode, false, true); 
                                  } else {  
                                    var newOption = new Option(info.pincode, info.pincode, false,false ); 
                                  } 
                                  $('#edit_modal #servicable_pincode').append(newOption) ;   
                                    
            	                });  
                               // $('#edit_modal #servicable_pincode').multiselect('select', ob)); 
                               
                             //$('#edit_modal #servicable_pincode').val(JSON.stringify(d.servicable_pincode.split(',')));   
                                
                        }
                 });
                
                
              
              
            }
         });
         
     });
     
     $('#edit_modal #state_code').change(function() {  
        $("#edit_modal #city_code > option").remove(); 
        $("#edit_modal #servicable_pincode > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select City Code');
                    $('#edit_modal #city_code').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    /*var opt1 = $('<option />'); 
	                    opt1.val(info.city_code);
	                    opt1.text(info.city_name + ' [ ' + info.city_code  + ' ]');
	                    $('#edit_modal #city_code').append(opt1); */
                        
                        var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, false);
                        $('#edit_modal #city_code').append(newOption).trigger('change');
                        
	                });
                }
          });  
     });  
     
     
     
     
     $('#edit_modal #city_code').change(function() {  
        $("#edit_modal #servicable_pincode > option").remove();    
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'city-serv-pincode', id: $(this).val() },
                success: function(d) {  
                   /* var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Pincode');
                    $('#servicable_pincode').append(opt1);*/ 
			
    				 $.each(d,function(key,info) 
    	                {
    	                    //var opt1 = $('<option />'); 
    	                    //opt1.val(info.pincode);
    	                    //opt1.text(info.pincode);
    	                    //$('#servicable_pincode').append(opt1); 
                            var newOption = new Option(info.pincode, info.pincode, false, false);
                            $('#edit_modal #servicable_pincode').append(newOption).trigger('change');
    	                });  
                        
                       /* $(".servicable_pincode").select2({
                          data: d
                        })*/
                        
                        
                }
         });
          
     });
     
     $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'agent_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  
     
});
</script>