<script>
jQuery(function($) {   
    
    $('.btn_chg_pwd').click(function() {   
        
        if($('#new_password').val() == $('#retype_password').val()) { 
             $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'change-password', id: $('#user_id').val(), old_pwd:  $('#old_password').val(), new_pwd:  $('#new_password').val()},
                    success: function(d) {  
                        alert(d.msg);
                        if(d.flg == 1)
                            window.location = "logout"; 
                    }
             });
        } else {
            alert("New Password Mis-Match ... Retry New Password");
            $('#retype_password').val('');
            $('#retype_password').focus();
        } 
        
     });  

});
</script> 