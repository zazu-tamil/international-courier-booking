<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/iCheck/all.css"> 
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.css">
<!-- bootstrap datepicker -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>asset/plugins/iCheck/icheck.min.js"></script>
<script>
jQuery(function($) { 
   
      
     $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    });
    
    $('.timepicker').timepicker({
      showInputs: false
    });
    
    
    $(".delivery_record").click(function() {  
        $('#delivery_modal #co_courier_drs_id').val($(this).val()) ;
        $('#delivery_modal .hdspan').html($(this).closest('tr').find('td').eq(1).text()) ;
        $('#delivery_modal .hdspan1').html($(this).closest('tr').find('td').eq(2).text()) ;
        //alert($(this).closest('tr').find('td').eq(1).text());
        //alert($(this).closest('tr').find('td').eq(2).text());
    });  
    
    
    $(".view_record").click(function() {  
            //alert($(this).val());
            $.ajax({
                        url: "<?php echo site_url('get-content');?>",
                        type: "post",
                        data: { tbl : 'co_courier_drs_dely_info', id: $(this).val() ,edit_mode : 1, del_mode : 1},
                        success: function(ctnt) {    
                            $('#view_modal .modal-body').html(ctnt) ;  
                        }
              });
              
             
      }); 
        
     $(document).on('click','#view_modal .btn_chld_edit', function () {
        //alert($(this).val()); 
        $('#view_modal .close_link').click();
        $("#edit_delivery_modal").modal('show');
        
        //$(this).attr('data-toggle','modal');
        //$(this).attr('data-target','#edit_delivery_modal');
        
        //data-toggle="modal" data-target="#view_modal"
        
        $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'co_courier_drs_dely_info', id: $(this).val() },
            success: function(d) {  
              //  alert(d.franchise_type_id);
              $('#edit_delivery_modal #co_courier_drs_dely_id').val(d.co_courier_drs_dely_id) ;     
              $('#edit_delivery_modal #co_courier_drs_id').val(d.co_courier_drs_id) ;     
              $('#edit_delivery_modal #dely_date').val(d.dely_date) ;     
              $('#edit_delivery_modal #delivered_by').val(d.delivered_by) ;     
              $('#edit_delivery_modal #dely_status').val(d.dely_status) ;   
              $('#edit_delivery_modal #pod_upload_path').val(d.pod_upload) ;   
              $('#edit_delivery_modal #challan_upload_path').val(d.challan_upload) ;   
              $('#edit_delivery_modal #receiver_name').val(d.receiver_name) ;   
              $('#edit_delivery_modal #receiver_contact').val(d.receiver_contact) ;   
              $('#edit_delivery_modal #to_pay_amt_rec').val(d.to_pay_amt_rec) ;   
              $('#edit_delivery_modal #delivery_exp').val(d.delivery_exp) ;   
              $('#edit_delivery_modal #loading_unloading_exp').val(d.loading_unloading_exp) ;   
              $('#edit_delivery_modal .pod-img').html("<a href='../"+ d.pod_upload +"' target='_blank'><img src='../"+ d.pod_upload +"' class='img-md'></a>") ;   
              if(d.challan_upload != '' && d.challan_upload != '0')
                $('#edit_delivery_modal .challan-img').html("<a href='../"+ d.challan_upload +"' target='_blank'><img src='../"+ d.challan_upload +"' class='img-md'></a>") ;   
              else
                $('#edit_delivery_modal .challan-img').html('No Challan');
              
            }
         });
            
         
         
        
        
     });   
    
    
      
    $(".edit_record").click(function() {  
            //alert($(this).val());
          $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'co_courier_drs_info', id: $(this).val() },
            success: function(d) {  
                
              $('#edit_modal #co_courier_drs_id').val(d.co_courier_drs_id) ;     
              $('#edit_modal #co_courier_id').val(d.co_courier_id) ;     
              $('#edit_modal #awb_no').val(d.awb_no) ;   
              $('#edit_modal #booking_date').val(d.booking_date) ;   
              $('#edit_modal #weight').val(d.weight) ;   
              $('#edit_modal #no_of_pcs').val(d.no_of_pcs) ;    
              $('#edit_modal #delivery_address').val(d.delivery_address) ;   
              $('#edit_modal #to_pay_amt').val(d.to_pay_amt) ; 
              $('#edit_modal #delivery_by').val(d.delivery_by) ; 
              $('#edit_modal input:radio[value="'+ d.p_mode +'"]').prop('checked', true); 
              $('#edit_modal input:radio[value="'+ d.pkg_type +'"]').prop('checked', true); 
              //$('#edit_modal input:radio[value="'+ d.status +'"]').prop('checked', true); 
    
    
            }
            
          });
          
    });
     
     
   $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'co_courier_drs_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     }); 
     
     
     $(document).on('click','#view_modal .btn_chld_del', function () {   
      
     if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'co_courier_drs_dely_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     }); 
     
     
       
       
    
});
</script> 