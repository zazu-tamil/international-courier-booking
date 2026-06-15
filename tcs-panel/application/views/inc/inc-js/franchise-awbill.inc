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
    
    $('#srch_franchise_id').select2();
    
    $(".edit_record").click(function() {  
            //alert($(this).val());
          $.ajax({
            url: "<?php echo site_url('get-data');?>",
            type: "post",
            data: { tbl : 'franchise_awbill_info', id: $(this).val() },
            success: function(d) {  
                
              $('#edit_modal #franchise_awbill_id').val(d.franchise_awbill_id) ;     
              $('#edit_modal #franchise_id').val(d.franchise_id) ;   
              $('#edit_modal #awbill_from').val(d.awbill_from) ;   
              $('#edit_modal #awbill_to').val(d.awbill_to) ;   
              $('#edit_modal #remarks').val(d.remarks) ;   
                
            }
         });
         
     });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'franchise_awbill_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 