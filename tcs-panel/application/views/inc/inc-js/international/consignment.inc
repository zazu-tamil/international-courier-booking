<script>
jQuery(function($) {    
    
     $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
     });
    
     $("#add_modal #package_type_id").change(function() {     
            $("#add_modal #package_weight_id > option").remove(); 
            
            $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'package_weight', id: $(this).val() },
                    success: function(d) {  
                        //alert(d); 
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Package Weight');
                        $('#add_modal #package_weight_id').append(opt1); 
                        
        				$.each(d,function(key,info) 
    	                {
    	                    var opt1 = $('<option />'); 
    	                    opt1.val(info.package_weight_id);
    	                    opt1.text(info.package_weight_range);
    	                    $('#add_modal #package_weight_id').append(opt1); 
    	                });
                    }
             });
    
     });
     
     
     
    $("#add_modal #btn_rate").click(function() {  
            
            $('#add_modal #actual_charges').val(0);
            $('#add_modal #dc_charges').val(0);
        
            $("#add_modal #international_service_provider_id > option").remove(); 
             $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'international-rate-calc', 
                            country: $('#add_modal #country_id').val() , 
                            pkg_type: $('#add_modal #package_type_id').val(), 
                            weight: $('#add_modal #package_weight_id').val() , 
                          } ,
                    success: function(ret) {  
                        //alert(ret.sql);  
                        
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Service Provider');
                        $('#add_modal #international_service_provider_id').append(opt1); 
                        $.each(ret.charges, function(i, info) {
                            var opt1 = $('<option />'); 
    	                    opt1.val(info.international_service_provider_id);
    	                    opt1.text(info.international_service_provider + " - " + info.rate);
    	                    opt1.attr('data-rate', info.rate  ); 
    	                    $('#add_modal #international_service_provider_id').append(opt1); 
                        
                        }); 
                                                 
                    }
             });
     }); 
     

    $("#add_modal #international_service_provider_id").change(function() {  
        //alert($(this).find(':selected').attr('data-rate')); 
       $('#add_modal #actual_charges').val($(this).find(':selected').attr('data-rate'));
       $("#add_modal #addt_charges").change();
       $("#add_modal #addt_charges").focus();
        
    });
    
    $("#add_modal #addt_charges").change(function() {  
        var tot = parseFloat($('#add_modal #actual_charges').val()) + parseFloat($("#add_modal #addt_charges").val()) + parseFloat($('#add_modal #oda_charges').val()) + parseFloat($('#add_modal #ah_charges').val()) + parseFloat($('#add_modal #markups').val());
        $('#add_modal #tot_charges').val(tot.toFixed(2));
    });
    
     $("#add_modal #addt_charges").keyup(function() {  
       $(this).change();
     });  
     
     $("#add_modal #markups").keyup(function() {  
        $("#add_modal #addt_charges").change();
     }); 
     
     $("#add_modal #ah_charges_id").change(function() {  
        //alert($(this).find(':selected').attr('data-rate')); 
       //alert($(this).val()); 
       if($(this).val() > 0)
         $('#add_modal #ah_charges').val($(this).find(':selected').attr('data-ah'));
       else
        $('#add_modal #ah_charges').val(0);
       $("#add_modal #addt_charges").change(); 
        
    });
    
    $("#add_modal #oda_charges_id").change(function() {   
        //alert($(this).find(':selected').attr('data-rate')); 
        if($(this).val() > 0)
            $('#add_modal #oda_charges').val($(this).find(':selected').attr('data-oda'));
        else    
            $('#add_modal #oda_charges').val(0);
            
       $("#add_modal #addt_charges").change(); 
        
    });
    
    
    
    $(".edit_record").click(function() {  //alert($(this).val());
     
      $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'international_consignment_info', id: $(this).val() },
            success: function(d) {  
               // alert(d.international_consignment_id);
              $('#edit_modal #international_consignment_id').val(d.international_consignment_id) ;  
              $('#edit_modal #awbno').val(d.awbno) ;  
              $('#edit_modal #booking_date').val(d.booking_date) ;  
              //$('#edit_modal #booking_time').val(d.booking_time) ;   
              $('#edit_modal #sender_company').val(d.sender_company) ;   
              $('#edit_modal #sender_name').val(d.sender_name) ;   
              $('#edit_modal #sender_mobile').val(d.sender_mobile) ;   
              $('#edit_modal #sender_address').val(d.sender_address) ;   
              $('#edit_modal #receiver_company').val(d.receiver_company) ;   
              $('#edit_modal #receiver_name').val(d.receiver_name)  ;   
              $('#edit_modal #receiver_mobile').val(d.receiver_mobile) ;     
              $('#edit_modal #receiver_address').val(d.receiver_address) ;    
              $('#edit_modal #no_of_pieces').val(d.no_of_pieces) ;    
              $('#edit_modal #country_id').val(d.country_id) ;    
              $('#edit_modal #package_type_id').val(d.package_type_id) ; 
                  
              //$('#edit_modal #international_service_provider_id').val(d.international_service_provider_id) ;    
              $('#edit_modal #package_weight_id').val(d.package_weight_id) ;    
              
              $('#edit_modal #description_of_goods').val(d.description_of_goods) ;    
              $('#edit_modal #actual_charges').val(d.actual_charges) ;    
              $('#edit_modal #addt_charges').val(d.addt_charges) ;    
              $('#edit_modal #ah_charges_id').val(d.ah_charges_id) ;    
              $('#edit_modal #ah_charges').val(d.ah_charges) ;    
              $('#edit_modal #oda_charges_id').val(d.oda_charges_id) ;    
              $('#edit_modal #oda_charges').val(d.oda_charges) ;    
              $('#edit_modal #markups').val(d.markups) ;    
              $('#edit_modal #tot_charges').val(d.tot_charges) ;    
              $('#edit_modal #dc_charges').val(d.dc_charges) ;    
              $('#edit_modal #sr_awbno').val(d.sr_awbno) ;   
              $('#edit_modal #exp_dv_date').val(d.exp_dv_date) ;   
              
              $('#edit_modal #id_proof_type').val(d.id_proof_type) ;   
              $('#edit_modal #id_proof_no').val(d.id_proof_no) ;   
              $('#edit_modal #id_proof_doc_path').val(d.id_proof_doc) ;   
              //alert(d.id_proof_doc);
              if(d.id_proof_doc == null || d.id_proof_doc == '') {
                 $('#edit_modal #id_proof_doc').attr("required","true");
               } else {
                 $('#ID_doc_view').html('<br /><a href="../'+  d.id_proof_doc +'" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> View</a>');
               }
              
              $('#edit_modal input:radio[value="'+ d.status +'"]').prop('checked', true); 
              
              $("#edit_modal #package_weight_id > option").remove();             
                $.ajax({
                        url: "<?php echo site_url('get-data');?>",
                        type: "post",
                        data: { tbl : 'package_weight', id: d.package_type_id },
                        success: function(d1) {  
                            //alert(d); 
                            var opt1 = $('<option />'); 
                            opt1.val('');
                            opt1.text('Select Package Weight');
                            $('#edit_modal #package_weight_id').append(opt1); 
                            
            				$.each(d1,function(key,info) 
        	                {
        	                    var opt1 = $('<option />'); 
        	                    opt1.val(info.package_weight_id);
        	                    opt1.text(info.package_weight_range);
                                if(d.package_weight_id == info.package_weight_id) 
                                    opt1.attr('selected','selected');
        	                    $('#edit_modal #package_weight_id').append(opt1); 
        	                });
                        }
                 });
              
             
             $("#edit_modal #international_service_provider_id > option").remove(); 
             $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'international-rate-calc', 
                            country: d.country_id, 
                            pkg_type: d.package_type_id, 
                            weight: d.package_weight_id , 
                          } ,
                    success: function(ret) {  
                        //alert(ret.sql);  
                        
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Service Provider');
                        $('#edit_modal #international_service_provider_id').append(opt1); 
                        $.each(ret.charges, function(i, info) {
                            var opt1 = $('<option />'); 
    	                    opt1.val(info.international_service_provider_id);
    	                    opt1.text(info.international_service_provider);
    	                    opt1.attr('data-rate', info.rate  ); 
                            if(d.international_service_provider_id == info.international_service_provider_id) 
                                    opt1.attr('selected','selected');
                            
    	                    $('#edit_modal #international_service_provider_id').append(opt1); 
                        
                        }); 
                                                 
                    }
             }); 
              
            }
         });
                
    });
    
    $("#edit_modal #btn_rate1").click(function() {  
            
          //  $('#edit_modal #actual_charges').val(0);
          //  $('#edit_modal #dc_charges').val(0);
        
            $("#edit_modal #international_service_provider_id > option").remove(); 
             $.ajax({
                    url: "<?php echo site_url('get-international-rate-calc-v3');?>",
                    type: "post",
                    data: { tbl : 'international-rate-calc', 
                            country: $('#edit_modal #country_id').val() , 
                            pkg_type: $('#edit_modal #package_type_id').val(), 
                            weight: $('#edit_modal #package_weight_id').val() ,
                            ch_type : $('#edit_modal #channel_type').val(),
                            ch_id : $('#edit_modal #channel_id').val(),
                          } ,
                    success: function(ret) {  
                        //alert(ret.sql);  
                        
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Service Provider');
                        $('#edit_modal #international_service_provider_id').append(opt1); 
                        $.each(ret.charges, function(i, info) {
                            var opt1 = $('<option />'); 
    	                    opt1.val(info.international_service_provider_id);
    	                    opt1.text(info.international_service_provider);
    	                    opt1.attr('data-rate', info.rate  ); 
    	                    $('#edit_modal #international_service_provider_id').append(opt1); 
                        
                        }); 
                                                 
                    }
             });
     });
     
     
     $("#edit_modal #btn_rate").click(function() {  
            
         //   $('#edit_modal #actual_charges').val(0);
         //   $('#edit_modal #dc_charges').val(0);
        
            $("#edit_modal #international_service_provider_id > option").remove(); 
             $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'international-rate-calc', 
                            country: $('#edit_modal #country_id').val() , 
                            pkg_type: $('#edit_modal #package_type_id').val(), 
                            weight: $('#edit_modal #package_weight_id').val() , 
                          } ,
                    success: function(ret) {  
                        //alert(ret.sql);  
                        
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Service Provider');
                        $('#edit_modal #international_service_provider_id').append(opt1); 
                        $.each(ret.charges, function(i, info) {
                            var opt1 = $('<option />'); 
    	                    opt1.val(info.international_service_provider_id);
    	                    opt1.text(info.international_service_provider + " - " + info.rate);
    	                    opt1.attr('data-rate', info.rate  ); 
    	                    $('#edit_modal #international_service_provider_id').append(opt1); 
                        
                        }); 
                                                 
                    }
             });
     }); 
    
   /* $("#edit_modal #btn_rate").click(function() {  
        
            $("#edit_modal #international_service_provider_id > option").remove(); 
             $.ajax({
                    url: "<?php echo site_url('get-international-rate-calc');?>",
                    type: "post",
                    data: { tbl : 'international-rate-calc', country: $('#edit_modal #country_id').val() , pkg_type: $('#edit_modal #package_type_id').val(), weight: $('#edit_modal #package_weight_id').val() },
                    success: function(ret) {  
                       // alert(ret.sql);  
                        
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Service Provider');
                        $('#edit_modal #international_service_provider_id').append(opt1); 
                        $.each(ret.charges, function(i, info) {
                            var opt1 = $('<option />'); 
    	                    opt1.val(info.international_service_provider_id);
    	                    opt1.text(info.international_service_provider + ' [ ' + info.dc_rate + ' ] ');
    	                    opt1.attr('data-rate', info.dc_rate  );
    	                    $('#edit_modal #international_service_provider_id').append(opt1); 
                        
                        }); 
                                                 
                    }
             });
     }); 
      
     

    $("#edit_modal #international_service_provider_id").change(function() {  
        //alert($(this).find(':selected').attr('data-rate'));
       // $(this).find(':selected').attr('data-id')
       $('#edit_modal #actual_charges').val($(this).find(':selected').attr('data-rate'));
       $('#edit_modal #dc_charges').val($(this).find(':selected').attr('data-rate'));
    });
    */
    
    
    $("#edit_modal #international_service_provider_id").change(function() {  
        //alert($(this).find(':selected').attr('data-rate'));
       // $(this).find(':selected').attr('data-id')
       $('#edit_modal #actual_charges').val($(this).find(':selected').attr('data-rate')); 
       $('#edit_modal #addt_charges').change();
       $('#edit_modal #addt_charges').focus();
    });
    
    $("#edit_modal #addt_charges").change(function() {  
       
        var tot = parseFloat($('#edit_modal #actual_charges').val()) + parseFloat($("#edit_modal #addt_charges").val()) + parseFloat($('#edit_modal #oda_charges').val()) + parseFloat($('#edit_modal #ah_charges').val()) + parseFloat($('#edit_modal #markups').val());
        $('#edit_modal #tot_charges').val(tot.toFixed(2));
    });
    
     $("#edit_modal #addt_charges").keyup(function() {  
        $(this).change();
     });   
     
     $("#edit_modal #markups").keyup(function() {  
         $("#edit_modal #addt_charges").change();
     });
    
     $("#edit_modal #ah_charges_id").change(function() {  
        //alert($(this).find(':selected').attr('data-rate')); 
       //alert($(this).val()); 
       if($(this).val() > 0)
         $('#edit_modal #ah_charges').val($(this).find(':selected').attr('data-ah'));
       else
        $('#edit_modal #ah_charges').val(0);
       $("#edit_modal #addt_charges").change(); 
        
    });
    
    $("#edit_modal #oda_charges_id").change(function() {   
        //alert($(this).find(':selected').attr('data-rate')); 
        if($(this).val() > 0)
            $('#edit_modal #oda_charges').val($(this).find(':selected').attr('data-oda'));
        else    
            $('#edit_modal #oda_charges').val(0);
            
       $("#edit_modal #addt_charges").change(); 
        
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'international_consignment', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     }); 
     
     
     $(".btn_tracking").click(function() {  
            $('#tracking_modal #international_consignment_id').val($(this).val());
            $.ajax({
                    url: "<?php echo site_url('get-content');?>",
                    type: "post",
                    data: { tbl : 'intl_tracking_info', id: $(this).val() ,edit_mode : 0, del_mode : 1},
                    success: function(ctnt) {                                 
                         //alert(ctnt);
                        $('#tracking_modal .tracking_list').html(ctnt) ; 
                        // #FC6705
                    }
            }); 
            
         });   
         
         $(document).on('click','#tracking_modal .btn_chld_del', function () {
           // alert('hi' + $(this).val());
           // alert($(this).attr('data'));
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
         
         
         $("#add_modal #awbno").on("change", function() {
            //alert('sdfasdfadf');
            $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: {tbl : 'check-awbno',  id: $('#add_modal #awbno').val(), vchk: 1 , f_id : <?php if($this->session->userdata('cr_is_admin') != '1') echo	$this->session->userdata('cr_franchise_id'); else echo "0"; ?>},
                success: function(d) {
                    //alert(d.sql);
                    //$('#sender_address').val(d.sql);
                    //alert(d.awbill_chk);
                    //alert(d.awb_book);
                    if(d.awbill_chk == 0 ){
                        $('#add_modal .awbno_warning').html('AWB No Invaild'); 
                        //$('#btn_save').addClass('disabled');
                        $('#add_modal #btn_save').hide();
                         $('#add_modal #awbno').focus();
                        
                    } else {
                        if(d.awb_book > 0){
                            $('#add_modal .awbno_warning').html('AWB No Already Booked'); 
                            //$('#btn_save').addClass('disabled');
                            $('#add_modal #btn_save').hide();
                            
                            $('#add_modal #awbno').focus();
                        }
                        else {
                           $('#add_modal .awbno_warning').html(''); 
                           //$('#btn_save').removeClass('disabled');
                           $('#add_modal #btn_save').show();
                        }
                    }
                    
                    /*if(d.cnt > 0) {
                    $('.awbno_warning').html('AWB No Already Exists');
                    $('#awbno').focus();
                    $('#btn_save').addClass('disabled');
                    } else {
                       $('.awbno_warning').html(''); 
                       $('#btn_save').removeClass('disabled');
                    }*/
                    
                    
                }
              });  
            
          });

});
</script> 