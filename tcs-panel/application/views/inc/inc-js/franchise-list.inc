<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/select2/dist/css/select2.min.css">
<script src="<?php echo base_url() ?>asset/bower_components/select2/dist/js/select2.full.min.js"></script>
<style>
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #0080FF;
    border: 1px solid #000;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #000;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    margin-right: 2px;
}
</style>
<script>
jQuery(function($) { 
    //Initialize Select2 Elements
    $('#add_modal #servicable_pincode').select2();
    $('#edit_modal #servicable_pincode').select2();
    
     
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
            data: { tbl : 'get-franchise', id: $(this).val() },
            success: function(d) {  
                
              $('#edit_modal #franchise_id').val(d.franchise_id) ;   
              $('#edit_modal #franchise_type_id').val(d.franchise_type_id) ;   
              $('#edit_modal #contact_person').val(d.contact_person) ;   
              $('#edit_modal #phone').val(d.phone) ;   
              $('#edit_modal #mobile').val(d.mobile) ;   
              $('#edit_modal #email').val(d.email) ;   
              $('#edit_modal #gst_no').val(d.gst_no) ;   
              $('#edit_modal #address').val(d.address) ;   
              $('#edit_modal #state_code').val(d.state_code) ;   
              $('#edit_modal #branch_code').val(d.branch_code) ;   
              $('#edit_modal #hub_code').val(d.hub_code) ;   
              
              //alert(d.tracking_upd_rights);
               
              $('#edit_modal input:radio[value="'+ d.tracking_upd_rights +'"]').prop('checked', true); 
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
			
    				$.each(d1,function(key,info) 
	                {
	                      /*if(d.city_code = info.city_code) {  
	                           var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, true);
	                      } else {
                               var newOption = new Option(info.city_name + ' [ ' + info.city_code  + ' ]', info.city_code, false, false);
                          }
                         //$('#edit_modal #city_code').append(newOption).trigger('change')  ;
                         
                          
                         $('#edit_modal #city_code').append(newOption)   ;*/
                         
                        var opt2 = $('<option />'); 
	                    opt2.val(info.city_code);
	                    opt2.text(info.city_name + ' [ ' + info.city_code  + ' ]');
                        if(d.city_code == info.city_code) 
                            opt2.attr('selected','selected');
	                    $('#edit_modal #city_code').append(opt2); 
                         
	                }); 
                   
                    }
                });  
                
                //alert(d.servicable_pincode);
                
                $.ajax({
                        url: "<?php echo site_url('get-data');?>",
                        type: "post",
                        data: { tbl : 'city-serv-pincode', id: d.city_code },
                        success: function(d1) {  
                             $("#edit_modal #servicable_pincode > option").remove();
                             var ob = d.servicable_pincode.split(','); 
                             
            				 $.each(d1,function(key,info) 
                                {   
                                  /*if(jQuery.inArray( info.pincode, ob ) != '-1')  {
                                    var newOption = new Option(info.pincode, info.pincode, false, true); 
                                  } else {  
                                    var newOption = new Option(info.pincode, info.pincode, false,false ); 
                                  } 
                                  $('#edit_modal #servicable_pincode').append(newOption).trigger('change'); */
                                  
                                  
                                    var opt2 = $('<option />'); 
            	                    opt2.val(info.pincode);
            	                    opt2.text(info.pincode);
                                    if(jQuery.inArray( info.pincode, ob ) != '-1') 
                                        opt2.attr('selected','selected');
            	                    $('#edit_modal #servicable_pincode').append(opt2);  
                                    
            	                });  
                                
                               
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
     
     $(".user_record").click(function() {  
        $('#user_modal #franchise_id').val($(this).val());
        $.ajax({
                url: "<?php echo site_url('get-content');?>",
                type: "post",
                data: { tbl : 'franchises_users', id: $(this).val() ,edit_mode : 1, del_mode : 1},
                success: function(ctnt) {                                 
                     //alert(ctnt);
                    $('#user_modal .user_list').html(ctnt) ; 
                    // #FC6705
                }
        }); 
        
     });   
     
     $(document).on('click','#user_modal .btn_chld_del', function () {
        //alert('hi' + $(this).attr('data'));
        
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : $(this).attr('data'), id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         } 
     });   
        
     $(document).on('click','#user_modal .btn_chld_edit', function () {
        //alert('hi' + $(this).val());
        //alert($('#user_modal #franchise_id').val());
        $('#user_modal #user_id').val($(this).val());
        $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-franchise-user', id: $(this).val() },
                success: function(d) {                                 
                     //alert(ctnt);
                    $('#user_modal #mode').val('Edit User') ;  
                    $('#user_modal #first_name').val(d.first_name) ;  
                    $('#user_modal #user_name').val(d.user_name) ;  
                    $('#user_modal #pwd').val(d.pwd) ;  
                    $('#user_modal #email').val(d.email) ;  
                    $('#user_modal #mobile').val(d.mobile) ;  
                    $('#user_modal input:radio[value="'+ d.status +'"]').prop('checked', true); 
                    $('#user_modal #first_name').focus();
                }
        }); 
     });
     
     
     $("#add_modal #checkbox").click(function(){
        if($("#add_modal #checkbox").is(':checked') ){
            $("#add_modal #servicable_pincode > option").prop("selected",true);
            $("#add_modal #servicable_pincode > option").trigger("change");
        }else{
            $("#add_modal #servicable_pincode > option").prop("selected",false);
            $("#add_modal #servicable_pincode > option").trigger("change");;
         }
     });
     
     $("#edit_modal #checkbox").click(function(){
        if($("#edit_modal #checkbox").is(':checked') ){
            $("#edit_modal #servicable_pincode > option").prop("selected",true);
            $("#edit_modal #servicable_pincode > option").trigger("change");
        }else{
            $("#edit_modal #servicable_pincode > option").prop("selected",false);
            $("#edit_modal #servicable_pincode > option").trigger("change");;
         }
     });
     
     
     $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'franchise_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  
        
     
});
</script>