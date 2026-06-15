<script>
jQuery(function($) {    


    $(".edit_record").click(function() {  
     
     // alert($(this).closest('tr').find('td').eq(1).text());
      
      var c_name = $(this).closest('tr').find('td').eq(1).text();
      var c_status = $(this).closest('tr').find('td').eq(2).text(); 
      
      $('#edit_modal #country_id').val($(this).val()) ; 
      $('#edit_modal #country_name').val(c_name) ; 
      $('#edit_modal input:radio[value='+ c_status +']').prop('checked', true); 
      
      /*$.ajax({
               // url: "send-enquiry.php",
                url: "<?php echo site_url('get-data');?>",
                type: "post",
                data: { tbl : 'area', id: $(this).val() },
                success: function(d) {                                 
                    // alert(d);
                    $('#edit_modal #city_area_id').val(d.city_area_id) ; 
                    $('#edit_modal #districts_id').val(d.districts_id) ; 
                    $('#edit_modal #city_name').val(d.city_name) ; 
                    $('#edit_modal #area_name').val(d.area_name) ;  
                }
      });*/
      
       
                
    });
    
    
    $('.del_record').click(function() {  
        if (confirm('Are U sure want to delete ??')) {
             $.ajax({
                    url: "<?php echo site_url('delete-record');?>",
                    type: "post",
                    data: { tbl : 'country', id: $(this).val() },
                    success: function(d) {  
                        alert(d);
                        location.reload();
                    }
             });
         }
        
     });  

});
</script> 