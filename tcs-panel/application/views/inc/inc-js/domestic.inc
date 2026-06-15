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
    
    
    $(".edit_record").click(function() {  
     
      var c_r = $(this).closest('tr').find('td').eq(2).text();
	  var c_s = $(this).closest('tr').find('td').eq(3).text();  
	  var c_c = $(this).closest('tr').find('td').eq(4).text();  
	  var c_m = $(this).closest('tr').find('td').eq(5).text();  
	  var c_5 = $(this).closest('tr').find('td').eq(6).text();  
	  var c_6 = $(this).closest('tr').find('td').eq(7).text();  
	  var c_7 = $(this).closest('tr').find('td').eq(8).text();  
	  var c_8 = $(this).closest('tr').find('td').eq(9).text();  
	  var c_9 = $(this).closest('tr').find('td').eq(10).text();  
	  var c_10 = $(this).closest('tr').find('td').eq(11).text();  
	   
      //alert($(this).attr('data')); 
      //alert(c_r); 
      
      if(c_r == 1)  { 
        $("#edit_modal #flg_region").prop('checked',true).iCheck('update');  
      
      } else {
        $("#edit_modal #flg_region").prop('checked',false).iCheck('update');  
      }
      if(c_s == 1)  { 
        $("#edit_modal #flg_state").prop('checked',true).iCheck('update');  
      
      } else {
        $("#edit_modal #flg_state").prop('checked',false).iCheck('update');  
      }
        
      if(c_c == 1){ 
        $("#edit_modal #flg_city").prop('checked',true).iCheck('update');       
      
      } else {
        $("#edit_modal #flg_city").prop('checked',false).iCheck('update'); 
      }
      if(c_m == 1){ 
        $("#edit_modal #flg_metro").prop('checked',true).iCheck('update');       
      
      } else {
        $("#edit_modal #flg_metro").prop('checked',false).iCheck('update'); 
      } 
    
      
          $('#edit_modal #min_weight').val(c_5) ; 
          $('#edit_modal #min_charges').val(c_6) ; 
          $('#edit_modal #addt_weight').val(c_7) ; 
          $('#edit_modal #addt_charges').val(c_8) ;
          $('#edit_modal #addt_weight_1').val(c_9) ;
          $('#edit_modal #addt_charges_1').val(c_10) ;
          $('#edit_modal #domestic_rate_id').val($(this).val()) ;  
          $('#edit_modal #c_type').val($(this).attr('data')) ;  
          $('#edit_modal #p_type').val($(this).attr('data-prod')) ;  
         // alert($(this).attr('data')) ;  
       
       
                
    });
    

});

</script>