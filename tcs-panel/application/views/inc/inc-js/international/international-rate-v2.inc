  
<script>
jQuery(function($) {    
    
   $('#frmdelete #btn_delete').click(function() {  
    
        if ($('#frmdelete #del_service_provider').val() != '') {

            if (confirm('Are U sure want to delete ??')) {
                 
                 $.ajax({
                        url: "<?php echo site_url('delete-record');?>",
                        type: "post",
                        data: { tbl : 'international_rate_info_v2',  service_provider: $('#frmdelete #del_service_provider').val() ,  country: $('#frmdelete #del_country').val() , weight: $('#frmdelete #del_pkg_weight').val() },
                        success: function(d) {  
                            alert(d);
                            location.reload();
                        }
                 });
             }
         } else {
            alert('Pls Select Service Provider');
            $('#frmdelete #del_service_provider').focus();
         }
        
     });  

});
</script> 