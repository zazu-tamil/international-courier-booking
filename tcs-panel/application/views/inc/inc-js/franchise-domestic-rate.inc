<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/iCheck/all.css">
<script src="<?php echo base_url() ?>asset/plugins/iCheck/icheck.min.js"></script>
<script>
jQuery(function($) { 
   
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-green',
      radioClass   : 'iradio_minimal-green'
    });
    
     $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    }); 
      
     
    $('#frmsrch #srch_franchise_type').change(function() {  
        $("#frmsrch #srch_state > option").remove(); 
        $("#frmsrch #srch_franchise_id > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'franchise-type-state', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select State');
                    $('#frmsrch #srch_state').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.state_code);
	                    opt1.text(info.state + '[ ' + info.state_code  + ' ]');
	                    $('#frmsrch #srch_state').append(opt1); 
	                });
                }
         });  
     });  
     
     $('#frmsrch #srch_state').change(function() {   
        $("#frmsrch #srch_franchise_id > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-franchise', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select Franchise');
                    $('#frmsrch #srch_franchise_id').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.franchise_id);
	                    opt1.text(info.contact_person + ' [ ' + info.mobile  + ' ] [' + info.city_code + ' ]');
	                    $('#frmsrch #srch_franchise_id').append(opt1); 
	                });
                }
           });  
     });
     
     /*
     
     $(".edit_record").click(function() {  
     
      var c_1 = $(this).closest('tr').find('td').eq(2).text().toString();
	  var c_2 = $(this).closest('tr').find('td').eq(3).text();  
	  var c_3 = $(this).closest('tr').find('td').eq(4).text();  
	  var c_4 = $(this).closest('tr').find('td').eq(5).text();  
	  var c_5 = $(this).closest('tr').find('td').eq(6).text();  
	  var c_6 = $(this).closest('tr').find('td').eq(7).text();  
	   
      //alert($(this).attr('data')); 
      
      if(c_1 == 1)  { 
        $("#edit_modal #flg_state").prop('checked',true).iCheck('update');  
      
      } else {
        $("#edit_modal #flg_state").prop('checked',false).iCheck('update');  
      }
        
       if(c_2 == 1){ 
        $("#edit_modal #flg_city").prop('checked',true).iCheck('update');       
      
      } else {
        $("#edit_modal #flg_city").prop('checked',false).iCheck('update'); 
      }
        
    
      
          $('#edit_modal #min_weight').val(c_3) ; 
          $('#edit_modal #min_charges').val(c_4) ; 
          $('#edit_modal #addt_weight').val(c_5) ; 
          $('#edit_modal #addt_charges').val(c_6) ;
          $('#edit_modal #domestic_rate_id').val($(this).val()) ;  
          $('#edit_modal #c_type').val($(this).attr('data')) ;  
          $('#edit_modal #franchise_id').val($(this).attr('franchise_data')) ;  
          
          $('#edit_modal .srch_franchise_type').val($('#srch_franchise_type :selected').val()) ;  
          $('#edit_modal .srch_state').val($('#srch_state :selected').val()) ;  
          $('#edit_modal .srch_franchise_id').val($('#srch_franchise_id :selected').val()) ;  
          
          
          
         // alert($(this).attr('data')) ;  
       
       
                
    });
     */
     
     $(".update_record").click(function() {   
      //alert($(this).closest("tr").find(".flg_city").prop('checked'));
      $.ajax({
                url: "<?php echo site_url('update-data');?>",
                type: "post",
                data: { 'tbl' : 'franchise-domestic-rate', 
                        'id': $(this).val() ,
                        'franchise_id': $(this).attr('franchise_data') , 
                        'c_type': $(this).attr('data') , 
                        'flg_region'  : ($(this).closest("tr").find(".flg_region").prop('checked') == true ? 1: 0),
                        'flg_state'  : ($(this).closest("tr").find(".flg_state").prop('checked') == true ? 1: 0),
                        'flg_city'  :  ($(this).closest("tr").find(".flg_city").prop('checked') == true ? 1: 0),
                        'flg_metro'  :  ($(this).closest("tr").find(".flg_metro").prop('checked') == true ? 1: 0),
                        'min_weight' : $(this).closest("tr").find(".min_weight").val(),
                        'min_charges'  : $(this).closest("tr").find(".min_charges").val(),
                        'addt_weight'  : $(this).closest("tr").find(".addt_weight").val(),
                        'addt_charges'  : $(this).closest("tr").find(".addt_charges").val() 
                      },
                success: function(d) {  
                     location.reload();
                }
           });  
      
      
     });  
     
     
        
});     
</script>