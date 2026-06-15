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
    $('#share_to').select2();   
    
    
    $('.btn_share').click(function() {   
        
        var share = $("#frm_share").serializeArray();
        
        //alert(JSON.stringify(share));                
        
             $.ajax({
                    url: "<?php echo site_url('update-data');?>",
                    type: "post",
                    data: { tbl : $('#frm_share #tbl').val(), id: $('#frm_share #share_hd_ticket_id').val(), share_to: $('#frm_share #share_to').val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         
        
     });  
    
});
</script> 