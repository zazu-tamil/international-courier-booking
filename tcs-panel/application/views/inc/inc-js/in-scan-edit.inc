<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/iCheck/all.css"> 
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/select2/dist/css/select2.min.css"> 
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url() ?>asset/bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
jQuery(function($) { 
   
     $('#awb_no').focus();
     $('.select2').select2();

     
     $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    });
    
    $('.timepicker').timepicker({
      showInputs: false,
      defaultTime : '<?php echo $record_edit['booking_time'];?>'
    });
    
  
   
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-green',
      radioClass   : 'iradio_minimal-green'
    });
    
     $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    }); 
    
    $("#origin_pincode").on("keyup",function(){  
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
     
     $("#dest_pincode").on("keyup",function(){  
	   //alert($(this).val());  
       $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'pin-state', id: $(this).val() },
                success: function(d) { 
                    //alert(d.state_code);
                    $("#dest_state_code > option").remove();    
                    $("#dest_city_code > option").remove();  
                    
                    var opt1 = $('<option />'); 
	                    opt1.val(d.state_code);
	                    opt1.text(d.state + '[ ' + d.state_code  + ' ]');
	                    $('#dest_state_code').append(opt1); 
                        
                    var opt2 = $('<option />'); 
	                    opt2.val(d.city_code);
	                    opt2.text(d.city + '[ ' + d.city_code  + ' ]');
	                    $('#dest_city_code').append(opt2);    
                }
            });
     });
     
     
        
     $('#origin_state_code').change(function() {  
        $("#origin_city_code > option").remove();      
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'state-city', id: $(this).val() },
                success: function(d) {  
                   /* var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select');
                    $('#origin_city_code').append(opt1); */
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.city_code);
	                    opt1.text(info.city_name + '[ ' + info.city_code  + ' ]');
	                    $('#origin_city_code').append(opt1); 
	                });
                }
         });  
     });
     
     $('#consignor_id').change(function() {    
        $("#consignee_id > option").remove();      
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-consignee', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select');
                    $('#consignee_id').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.customer_contact_id);
	                    opt1.text(info.cc_code + ':' + info.company + '[ ' + info.contact_person  + ' ]');
	                    $('#consignee_id').append(opt1); 
	                });
                }
         });  
         //alert($(this).val());
         $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-customer', id: $(this).val() },
                success: function(info) {  
                     $('#sender_company').val(info.company);
                     $('#sender_name').val(info.contact_person);
                     $('#sender_mobile').val(info.mobile);
                     $('#sender_address').val(info.address);
                }
         }); 
     });
     
     $('#consignee_id').change(function() { 
        
        if($(this).val() != '') 
        {
            $('#is_manual_rate').val(0);
            
        } else {
            $('#is_manual_rate').val(1);
        }
        
        $('#is_manual_rate').change();
        
        
         $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-customer-contact', id: $(this).val() },
                success: function(info) {  
                     $('#receiver_company').val(info.company);
                     $('#receiver_name').val(info.contact_person);
                     $('#receiver_mobile').val(info.mobile);
                     $('#receiver_address').val(info.address);
                }
         }); 
     });
     
     $('.calc').keyup(function() {  
      var tot = gst_amt = 0;
      tot = parseFloat($('#rate').val()) + parseFloat($('#cod_charges').val())  + parseFloat($('#fuel_charges').val()) + parseFloat($('#oda_charges').val());
      gst_amt = parseFloat(tot) * parseFloat($('#tax_percentage').val()/100) ; 
      $('#sub_total').val(parseFloat(tot).toFixed(2)); 
      $('#tax_amt').val(parseFloat(gst_amt).toFixed(2)); 
      $('#grand_total').val((parseFloat(tot) + parseFloat(gst_amt)).toFixed(2)); 
     }); 
    
    
     $('input[name="cod"]').on('ifClicked', function (event) {
        //alert("You clicked " + this.value);
        //alert($(this).is(':checked'));
        if($(this).is(':checked') == false)
        {
            $('.cod_amt').removeClass('hide');
            $('#cod_charges').attr('readonly',false);
        } else {
            $('.cod_amt').addClass('hide');
            $('#cod_charges').attr('readonly',true);
        }
    });   
    
      
     
    $('.btn_calc_wt').click(function() {  
        var wt = (($('#length').val() * $('#width').val() * $('#height').val()) / 5000 );
        $('.air_wt').html("Air : " + wt.toFixed(0) + "  Kgs");
        var wt1 = (($('#length').val() * $('#width').val() * $('#height').val()) / 4000 );
        $('.surface_wt').html("Surface : " + wt1.toFixed(0) + "  Kgs");
        
     });
     
     $("#weight").on("blur", function() {
        $('#is_manual_rate').change();
        
        if($('input[name="chargable_opt"]:checked').val() == 'Actual')
            $('#chargable_weight').val($(this).val());
        else
            $('#chargable_weight').val($(this).val());
        //alert($('input[name="chargable_opt"]:checked').val());
     }); 
    
    
     $('#is_manual_rate').change(function() {  
        
        //alert($(this).val());
        if($(this).val() == 1 ){
            $('#rate').attr('readonly',false);
        } else {
            $('#rate').attr('readonly',true);
            
            $.ajax({
                url: "<?php echo site_url('get-charges');?>",
                type: "post",
                data: {origin_pincode: $('#origin_pincode').val() , dest_pincode:$('#dest_pincode').val() , consignor_id : $('#consignor_id').val() ,weight :$('#chargable_weight').val() , c_type : $('#service_id :selected').text()  },
                success: function(d) {
                   //  alert(d.sql);
                    //alert(d.tot_charges);
                    $('#rate').val(d.tot_charges);
                    $('#rate').focus();
                }
              });  
            
        }
     });   
     
     $('.is_manual_rate').on('ifChecked', function(event){
         
            $('#rate').attr('readonly',false);
         
     });  
     
     
     $('.is_manual_rate').on('ifUnchecked', function(event){
            
                /*alert($('#origin_pincode').val());
                alert($('#dest_pincode').val()  );
                alert( $('#consignor_id').val() );
                alert( $('#chargable_weight').val() );
                alert( $('#service_id :selected').text() );*/
                
             $('#rate').attr('readonly',true);   
            
              $.ajax({
                url: "<?php echo site_url('get-charges');?>",
                type: "post",
                data: {origin_pincode: $('#origin_pincode').val() , dest_pincode:$('#dest_pincode').val() , consignor_id : $('#consignor_id').val() ,weight :$('#chargable_weight').val() , c_type : $('#service_id :selected').text()  },
                success: function(d) {
                    // alert(d.sql);
                    //alert(d.tot_charges);
                    $('#rate').val(d.tot_charges);
                    $('#rate').focus();
                }
              });  
            
        
     });
      
     
     $('.chargable_opt').on('ifChecked ', function(event){

        if($(this).val() == 'Actual')
            $('#chargable_weight').val($('#weight').val());
        else {
            if($('#service_id :selected').text() == 'Air'){
                var wt = (($('#length').val() * $('#width').val() * $('#height').val()) / 5000 );
                $('#chargable_weight').val(wt.toFixed(0));
            } else {
                var wt = (($('#length').val() * $('#width').val() * $('#height').val()) / 4000 );
                $('#chargable_weight').val(wt.toFixed(0));
            }
        } 
         //alert($('#service_id :selected').text());
     });
     
     $("#consignor_code").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#consignor_id *").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
      
      $("#consignee_code").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#consignee_id *").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
     
     $("#origin_pincode").blur();
     $("#dest_pincode").blur();
     //$('#consignor_id').change().refresh();
     //$('#consignee_id').val('<?php //echo $record_edit['consignee_id']?>');
     
          $("#consignee_id > option").remove();      
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-consignee', id: '<?php echo $record_edit['consignor_id']?>' },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select');
                    $('#consignee_id').append(opt1); 
			
    				$.each(d1,function(key,info) 
	                { 
                         /* if(info.customer_contact_id == '<?php echo $record_edit['consignee_id']?>') {  
                            var newOption = new Option(info.cc_code + ':' + info.company + '[ ' + info.contact_person  + ' ]', info.customer_contact_id, false, true);
                          } else {
                               var newOption = new Option(info.cc_code + ':' + info.company + '[ ' + info.contact_person  + ' ]', info.customer_contact_id, false, false);
                          }
                          $('#consignee_id').append(newOption)   ;*/
                          
                          
                       var opt2 = $('<option />'); 
                       opt2.val(info.customer_contact_id);
                       opt2.text(info.cc_code + ':' + info.company + '[ ' + info.contact_person  + ' ]');
                       if(info.customer_contact_id == '<?php echo $record_edit['consignee_id']?>') 
                        opt2.attr('selected','selected');
                       $('#consignee_id').append(opt2); 
                         
	                });
                }
         }); 
         
         
         $('.btn_co_load').click(function() {   
            $('#add_modal').modal('toggle');
             $.ajax({
                    url: "<?php echo site_url('insert-data');?>",
                    type: "post",
                    data: { tbl : 'co-loader', co_loader_name: $('#co_loader_name').val() },
                    success: function(d) {  
                       // alert(d); 
                       $('#co_loader_id').append('<option value="'+ d +'" selected >'+ $('#co_loader_name').val() +'</option>');               
                        
                    }
             });     
          });  
     
});
</script> 