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
   
     $('#awb_no').focus();
     
     $('.datepicker').datepicker({
      autoclose: true,
      format : 'yyyy-mm-dd' 
    });
    
    $('.timepicker').timepicker({
      showInputs: false
    });
   
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-green',
      radioClass   : 'iradio_minimal-green'
    });
    
     $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    }); 
    
    $('.select_all').on('ifChecked', function(event) {
    	$('.booking_id').iCheck('check');
    });
    $('.select_all').on('ifUnchecked', function(event) {
    	$('.booking_id').iCheck('uncheck');
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
     
     $('.btn-del').click(function() {  
        //alert($(this).val());
         $.ajax({
                url: "<?php echo site_url('delete-record');?>",
                type: "post",
                data: { tbl : 'open_manifest', id: $(this).val() },
                success: function(d) {  
                   alert(d); 
                   location.reload();  
                }
         });     
     });
    
     $('.btn_inscan').click(function() {  
        //alert($('.manifest_type:checked').val());
       // alert($(".manifest_type").iCheck('check'));
       
       var m_ty = ($('.from_type:checked').val() + " - " + $('.to_type:checked').val());
       //alert(m_ty);
         $.ajax({
                url: "<?php echo site_url('insert-data');?>",
                type: "post",
                data: { tbl : 'open-manifest', 
                        manifest_no : $('#manifest_no').val(),  
                        manifest_date :  $('#manifest_date').val(), 
                        manifest_type :  m_ty, 
                        from_city_code :  $('#from_city_code').val(), 
                        to_city_code : $('#to_city_code').val(), 
                        awbno : $('#awbno').val()  
                      },
                success: function(d) {  
                   //alert(d); 
                   location.reload();  
                }
         }); 
             
     });  
     
     $('.btn_received').click(function() {  
       //alert($('.to_type:checked').val());
       // alert($(".manifest_type").iCheck('check'));
       if($('#awbno').val() != '') {
         $.ajax({
                url: "<?php echo site_url('update-data');?>",
                type: "post",
                data: { tbl : 'received-manifest',  
                        id : $('#awbno').val() ,
                        city_code : $('#to_city_code').val(),  
                        m_type : $('.to_type:checked').val(),  
                      },
                success: function(d) {  
                   alert(d); 
                   location.reload();  
                }
         }); 
       }
             
     });
     
      $('.btn-view').click(function() {   
        //    alert($(this).val());
         $.ajax({
                url: "<?php echo site_url('get-content');?>",
                type: "post",
                data: { tbl : 'manifest-list',  
                        id : $(this).val() ,
                        edit_mode : 0,  
                        del_mode : 0,  
                      },
                success: function(ctnt) {                                 
                     //alert(ctnt);
                    $('#view_modal .modal-body').html(ctnt) ; 
                    // #FC6705
                }
         }); 
             
     });
     
     
    $('#frmsearch .from_type').click(function() {  
        $("#frmsearch #from_city_code > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-hub-branch-code', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select HUB/Branch Code');
                    $('#frmsearch #from_city_code').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.hub_branch_code);
	                    opt1.text(info.hub_branch_name + ' [ ' + info.hub_branch_code  + ' ]');
	                    $('#frmsearch #from_city_code').append(opt1); 
	                });
                }
         });  
     });  
     
     /*
     $('#frmsearch .to_type').click(function() {  
        $("#frmsearch #to_city_code > option").remove();       
          $.ajax({
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'get-hub-branch-code', id: $(this).val() },
                success: function(d) {  
                    var opt1 = $('<option />'); 
                    opt1.val('');
                    opt1.text('Select HUB/Branch Code');
                    $('#frmsearch #to_city_code').append(opt1); 
			
    				$.each(d,function(key,info) 
	                {
	                    var opt1 = $('<option />'); 
	                    opt1.val(info.hub_branch_code);
	                    opt1.text(info.hub_branch_name + ' [ ' + info.hub_branch_code  + ' ]');
	                    $('#frmsearch #to_city_code').append(opt1); 
	                });
                }
         });  
     }); */  
    
    //$('#frmsearch .to_type').click();   
    
});
</script> 