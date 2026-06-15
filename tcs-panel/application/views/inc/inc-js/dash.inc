 
<script>
 
/*if (window.matchMedia("(max-width: 767px)").matches) 
{ 
    
    // The viewport is less than 768 pixels wide 
    alert("This is a mobile device."); 
} else { 
    
    // The viewport is at least 768 pixels wide 
    alert("This is a tablet or desktop."); 
} */

jQuery(function($) { 
    
      function myFunction() {  
            $.ajax({
                    url: "<?php echo site_url('get-notice');?>",
                    type: "post", 
                    success: function(msg) {  
                       $('#notice').html(msg); 
                    }
             });
      }
      setInterval(myFunction, 60000); 
      myFunction();
     
   
    
    $('.btn_received').click(function() {   
        
        if($('#received_by_' + $(this).val()).val() != '') {
    
            $.ajax({
                    url: "<?php echo site_url('update-data');?>",
                    type: "post",
                    data: { tbl : 'wallet-payment', id: $(this).val() , received_by : $('#received_by_' + $(this).val()).val()},
                    success: function(msg) {  
                        alert(msg); 
                        location.reload()
                    }
             });
        } else {
            alert(" Please Enter Payment Received By !!! ");
            $('#received_by_' + $(this).val()).focus();
        }
    }); 
    
    
    $('.btn_calc').click(function() {  //alert($('#source').val());
        
          $.ajax({
                url: "<?php echo site_url('rate-calc');?>",
                type: "post",
                data: { origin_pincode: $('#source').val() , dest_pincode: $('#destination').val(), weight:$('#weight').val()},
                success: function(datainfo) {  
                    //alert(datainfo['Air'].tot_charges);
                    //alert(jQuery.isEmptyObject(datainfo));
                    
                    //arr = $.parseJSON(datainfo); 
                    //alert(datainfo.length);
                    if(datainfo.length > 0){  $('.rate_show').html("");
                    $.each( datainfo, function( key, value ) {
                        
                        if(value.mode == 'Air'){                         
                            $('.rate_show').append("<p><i class='fa fa-plane fa-2x'></i> : <i class='fa fa-rupee'></i>" + value.tot_charges );
                        } else {
                            $('.rate_show').append("<p><i class='fa fa-truck fa-2x'></i> : <i class='fa fa-rupee'></i>" + value.tot_charges );
                        }    
                    });
                    } else {
                        $('.rate_show').html("Invalid");
                    }
                   
                   /* if(jQuery.isEmptyObject(datainfo) == 'true'){
                        $('.rate_show').html("Invalid");
                    } else {
                        $('.rate_show').html("<p> <b>Air</b>  : " datainfo['Air'].tot_charges + "</p>" );
                        $('.rate_show').append("<p><b>Surface</b> : " datainfo['Surface'].tot_charges + "</p>" );
                    } 
                   */   
                    
                    //alert(d.sql);
                    //$('.rate_show').html(datainfo.sql);
                    //location.reload();
                }
         }); 
        
     }); 
     
     
     $("#srch_pkg_type").change(function() {    
            $("#srch_pkg_weight > option").remove(); 
            
            $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'package_weight', id: $(this).val() },
                    success: function(d) {  
                        //alert(d); 
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Package Weight');
                        $('#srch_pkg_weight').append(opt1); 
                        
        				$.each(d,function(key,info) 
    	                {
    	                    var opt1 = $('<option />'); 
    	                    opt1.val(info.package_weight_id);
    	                    opt1.text(info.package_weight_range);
    	                    $('#srch_pkg_weight').append(opt1); 
    	                });
                    }
             });
    
     }); 

});
</script>