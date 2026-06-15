<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script>
jQuery(function($) { 
    
    $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    });
    
    $('.timepicker').timepicker({
      showInputs: false
    });
    
    
     $("#origin_pincode").change(function() {  
	   //alert($(this).val());  
       $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'pin-state', id: $(this).val() },
                success: function(d) { 
                    //alert(d.state_code);
                    $("#origin_state_code > option").remove();    
                    $("#origin_city_code > option").remove();  
                    
                    var opt1 = $('<option />'); 
	                    opt1.val(d.state_code);
	                    opt1.text(d.state + '[ ' + d.state_code  + ' ]');
	                    $('#origin_state_code').append(opt1); 
                        
                    var opt2 = $('<option />'); 
	                    opt2.val(d.city_code);
	                    opt2.text(d.city + '[ ' + d.city_code  + ' ]');
	                    $('#origin_city_code').append(opt2);    
                }
            });
     });
     
     $("#destination_pincode").change(function() {  
	   //alert($(this).val());  
       $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'pin-state', id: $(this).val() },
                success: function(d) { 
                    //alert(d.state_code);
                    $("#destination_state_code > option").remove();    
                    $("#destination_city_code > option").remove();  
                    
                    var opt1 = $('<option />'); 
	                    opt1.val(d.state_code);
	                    opt1.text(d.state + '[ ' + d.state_code  + ' ]');
	                    $('#destination_state_code').append(opt1); 
                        
                    var opt2 = $('<option />'); 
	                    opt2.val(d.city_code);
	                    opt2.text(d.city + '[ ' + d.city_code  + ' ]');
	                    $('#destination_city_code').append(opt2);    
                }
            });
     });
     
    /* $('#origin_state_code').change(function() {  
        $("#origin_city_code > option").remove();      
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: $(this).val() },
                success: function(d) {  
                   /* var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select');
                    $('#origin_city_code').append(opt1); * /
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.city_code);
	                    opt1.text(info.city_name + '[ ' + info.city_code  + ' ]');
	                    $('#origin_city_code').append(opt1); 
	                });
                }
         });  
     }); */ 

});
</script>