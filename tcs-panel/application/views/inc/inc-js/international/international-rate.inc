<script>
jQuery(function($) {  
     
     
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
                        opt1.text('All Package Weight');
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
     
     
        

    $(".edit_record").click(function() {  
     
     // alert($(this).closest('tr').find('td').eq(1).text());
      
      var country = $(this).closest('tr').find('td').eq(1).text();
      var package_type = $(this).closest('tr').find('td').eq(2).text();
      var package_weight = $(this).closest('tr').find('td').eq(3).text();
      var rate = $(this).closest('tr').find('td').eq(4).text();
      var c_status = $(this).closest('tr').find('td').eq(5).text(); 
      
      $('#edit_modal #international_rate_id').val($(this).val()) ;  
      $("#edit_modal #country option:contains('"+ country +"')").attr('selected', true);
      $('#edit_modal #package_type').val(package_type) ; 
      $('#edit_modal #package_weight').val(package_weight) ; 
      $('#edit_modal #rate').val(rate) ; 
      $('#edit_modal input:radio[value='+ c_status +']').prop('checked', true);  
                
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'international_rate_info', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 