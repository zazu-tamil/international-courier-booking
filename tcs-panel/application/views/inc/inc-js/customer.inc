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
      
      

     $(".edit_record").click(function() {  
          //  alert($(this).val());
          $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'get-customer', id: $(this).val() },
            success: function(d) {  
              //  alert(d.franchise_id);
              $('#edit_modal #customer_id').val(d.customer_id) ;     
              $('#edit_modal #customer_code').val(d.customer_code) ;     
              $('#edit_modal #customer_type_id').val(d.customer_type_id) ;     
              $('#edit_modal #company').val(d.company) ;   
              $('#edit_modal #contact_person').val(d.contact_person) ;   
              $('#edit_modal #phone').val(d.phone) ;   
              $('#edit_modal #mobile').val(d.mobile) ;   
              $('#edit_modal #email').val(d.email) ;    
              $('#edit_modal #address').val(d.address) ;   
              $('#edit_modal #gst_no').val(d.gst_no) ;   
              $('#edit_modal #aadhar_no').val(d.aadhar_no) ;   
              $('#edit_modal #state_code').val(d.state_code) ;   
              $('#edit_modal #pincode').val(d.pincode) ;   
              $('#edit_modal #franchise_type_id').val(d.franchise_type_id) ;   
             // $('#edit_modal #franchise_id').val(d.franchise_id) ;   
               
              $('#edit_modal input:radio[value="'+ d.status +'"]').prop('checked', true); 
              
              
              $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: d.state_code },
                success: function(d1) {  
                    $("#edit_modal #city_code > option").remove(); 
                    var opt1 = $('<option />'); 
                    opt1.val('0');
                    opt1.text('Select City Code');
                    $('#edit_modal #city_code').append(opt1); 
			        // alert(d.city_code);
    				$.each(d1,function(key,info) 
	                {
	                     /*  if(d.city_code == info.city_code) {  
	                           var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, true);
	                      } else {
                               var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, false);
                          }
                         $('#edit_modal #city_code').append(newOption).trigger('change')  ; */
                         
                        var opt1 = $('<option />'); 
                        opt1.val(info.city_code);
                        opt1.text(info.city_name + ' [ ' + info.city_code  + ' ]');
                        if(d.city_code == info.city_code) {  
                          opt1.attr('selected','selected');  
                        }
                        $('#edit_modal #city_code').append(opt1); 
                         
	                }); 
                   
                    }
                });  
                
                
                 $("#edit_modal #franchise_id > option").remove();       
                  $.ajax({
                        url: "<?php echo site_url('get-data');?>",
                        type: "post",
                        data: { tbl : 'state-city-franchise-type', id: d.franchise_type_id, state_code : d.state_code, city_code: d.city_code },
                        success: function(d1) {  
                            var opt1 = $('<option />'); 
                            opt1.val('');
                            opt1.text('Select Franchise');
                            $('#edit_modal #franchise_id').append(opt1); 
        			
            				$.each(d1,function(key,info) 
        	                { 
                                  
                                var opt1 = $('<option />'); 
                                opt1.val(info.franchise_id);
                                opt1.text(info.contact_person + ' [ ' + info.mobile  + ' ]');
                                 if(d.franchise_id == info.franchise_id) {  
                                  opt1.attr('selected','selected');  
                                } 
                                $('#edit_modal #franchise_id').append(opt1); 
                                 
        	                });
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
     
     $('#add_modal #franchise_type_id').change(function() {  
        $("#add_modal #franchise_id > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city-franchise-type', id: $(this).val(), state_code : $('#add_modal #state_code :selected').val() , city_code:  $('#add_modal #city_code :selected').val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Franchise');
                    $('#add_modal #franchise_id').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.franchise_id);
	                    opt1.text(info.contact_person + '[ ' + info.mobile  + ' ]');
	                    $('#add_modal #franchise_id').append(opt1); 
	                });
                }
         });  
     });  
     
     $('#edit_modal #franchise_type_id').change(function() {  
        $("#edit_modal #franchise_id > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city-franchise-type', id: $(this).val(), state_code : $('#edit_modal #state_code :selected').val() , city_code:  $('#edit_modal #city_code :selected').val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Franchise');
                    $('#edit_modal #franchise_id').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.franchise_id);
	                    opt1.text(info.contact_person + '[ ' + info.mobile  + ' ]');
	                    $('#edit_modal #franchise_id').append(opt1); 
	                });
                }
         });  
     }); 
     
     
      $('.btn_contact').click(function() {  
        
        $('#add_group_modal #customer_id').val($(this).val());
        
      });
     
      $('#add_group_modal #state_code').change(function() {  
        $("#add_group_modal #city_code > option").remove(); 
        $("#add_group_modal #servicable_pincode > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select City Code');
                    $('#add_group_modal #city_code').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.city_code);
	                    opt1.text(info.city_name + '[ ' + info.city_code  + ' ]');
	                    $('#add_group_modal #city_code').append(opt1); 
	                });
                }
         });  
     }); 
      
    
        
     $(".view_record").click(function() {  
            //alert($(this).val());
            $.ajax({
                        url: "<?php echo site_url('get-content');?>",
                        type: "post",
                        data: { tbl : 'customer_contact', id: $(this).val() ,edit_mode : 1, del_mode : 1},
                        success: function(ctnt) {                                 
                             //alert(ctnt);
                            $('#view_modal .modal-body').html(ctnt) ; 
                            // #FC6705
                        }
              });
              
             
      }); 
        
     $(document).on('click','#view_modal .btn_chld_edit', function () {
        //alert($(this).val());
        
        $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'get-customer-contact', id: $(this).val() },
            success: function(d) {  
              //  alert(d.franchise_type_id);
              $('#edit_group_modal #customer_contact_id').val(d.customer_contact_id) ;     
              $('#edit_group_modal #cc_code').val(d.cc_code) ;     
              $('#edit_group_modal #customer_group').val(d.customer_group) ;     
              $('#edit_group_modal #company').val(d.company) ;   
              $('#edit_group_modal #contact_person').val(d.contact_person) ;   
              $('#edit_group_modal #phone').val(d.phone) ;   
              $('#edit_group_modal #mobile').val(d.mobile) ;   
              $('#edit_group_modal #email').val(d.email) ;    
              $('#edit_group_modal #address').val(d.address) ;   
              $('#edit_group_modal #gst_no').val(d.gst_no) ;   
              $('#edit_group_modal #aadhar_no').val(d.aadhar_no) ;   
              $('#edit_group_modal #state_code').val(d.state_code) ;   
              $('#edit_group_modal #pincode').val(d.pincode) ; 
               
              $('#edit_group_modal input:radio[value="'+ d.status +'"]').prop('checked', true); 
              
              
              $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: d.state_code },
                success: function(d1) {  
                    $("#edit_group_modal #city_code > option").remove(); 
                    var opt1 = $('<option />'); 
                    opt1.val('0');
                    opt1.text('Select City Code');
                    $('#edit_group_modal #city_code').append(opt1); 
			
    				$.each(d1,function(key,info) 
	                {
	                      if(d.city_code = info.city_code) {  
	                           var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, true);
	                      } else {
                               var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, false);
                          }
                         $('#edit_group_modal #city_code').append(newOption)  ;
	                }); 
                   
                    }
                });  
              
              
            }
         });
            
         $('#view_modal').modal('hide');
         $("#edit_group_modal").modal('show');
        
        
     });   
      
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'customer_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });   
      
     
});
</script>