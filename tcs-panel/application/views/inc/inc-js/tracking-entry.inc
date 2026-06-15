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


    $('#add_modal #btn_Save').click(function() {  
            //alert('hi');
           // alert($(".manifest_type").iCheck('check'));
             $.ajax({
                    url: "<?php echo site_url('insert-data');?>",
                    type: "post",
                    data: { tbl : 'awb_tracking_info', 
                            tracking_status : $('#add_modal #tracking_status').val(),  
                            city_code :  $('#add_modal #city_code').val(), 
                            status_date :  $('#add_modal #status_date').val(), 
                            status_time :  $('#add_modal #status_time').val(), 
                            remarks :  $('#add_modal #remarks').val(), 
                            awbno : $('#add_modal #awbno').val()  
                          },
                    success: function(d) {  
                       //alert(d); 
                       location.reload();  
                    }
             }); 
                 
    }); 
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'awb_tracking_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });     



});
</script>     