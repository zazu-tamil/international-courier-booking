<script>
jQuery(function($) {  
    
    $("#srch_country_id_v2").change(function() {    //alert($(this).val());
            $("#srch_pkg_weight_v2 > option").remove(); 
            
            $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'package_weight_v2', id: $(this).val() },
                    success: function(d) {  
                        //alert(d); 
                        var opt1 = $('<option />'); 
                        opt1.val('');
                        opt1.text('Select Package Weight');
                        $('#srch_pkg_weight_v2').append(opt1); 
                        
        				$.each(d,function(key,info) 
    	                {
    	                    var opt1 = $('<option />'); 
    	                    opt1.val(info.weight);
    	                    opt1.text(info.weight);
    	                    $('#srch_pkg_weight_v2').append(opt1); 
    	                });
                    }
             });
    
     });
    
    
    $(".btn_calc_v2").click(function() {   
             $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'international-rate-calc-v2', country: $('#srch_country_id_v2').val() ,  weight: $('#srch_pkg_weight_v2').val() },
                    success: function(ret) {  
                        //alert(ret); 
                        // var cnt = "";
                         var cnt1 = '<table class="table table-bordered table-striped">';
                         cnt1 += '<tr>';
                         cnt1 += '<th class="text-center">#</th>';
                         cnt1 += '<th class="text-left">Service Provider</th>';
                         cnt1 += '<th class="text-left">FR Type</th>';
                         cnt1 += '<th class="text-right">Amount</th>';
                         cnt1 += '</tr>';
                        $.each(ret.charges, function(i, info) {  
                            
                            cnt1 += '<tr>';                             
                            cnt1 += '<td>'+ (i + 1) +'</td>';                             
                            cnt1 += '<td class="text-left">'+ info.service_provider +'</td>';                             
                            cnt1 += '<td class="text-left">'+ info.franchise_type_name +'</td>';                             
                            cnt1 += '<td class="text-right">'+ info.fr_rate +'</td>';                             
                            cnt1 += '</tr>';                         
                         });
                        cnt1+= "</table>";
                        $('.rate_show_v2').html(cnt1); 
                                                 
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
     
     
      $('.calc').keyup(function() {  
        var wt1 = (($('#length_1').val() * $('#width_1').val() * $('#height_1').val()) / 4900 );
        $('#weight_1').val( wt1.toFixed(2));
        
        var wt2 = (($('#length_2').val() * $('#width_2').val() * $('#height_2').val()) / 4900 );
        $('#weight_2').val( wt2.toFixed(2));
        
        var wt3 = (($('#length_3').val() * $('#width_3').val() * $('#height_3').val()) / 4900 );
        $('#weight_3').val( wt3.toFixed(2));
        
        var wt4 = (($('#length_4').val() * $('#width_4').val() * $('#height_4').val()) / 4900 );
        $('#weight_4').val( wt4.toFixed(2));
        
        var wt5 = (($('#length_5').val() * $('#width_5').val() * $('#height_5').val()) / 4900 );
        $('#weight_5').val( wt5.toFixed(2));
        
        var tot_wt = wt1 + wt2 +wt3 +wt4 + wt5;
        
        $('#tot_wt').html( tot_wt.toFixed(2) + " Kgs");
        
        
     });  
     
     
     $(".btn_calc").click(function() {   
             $.ajax({
                    url: "<?php echo site_url('get-data');?>",
                    type: "post",
                    data: { tbl : 'international-rate-calc', country: $('#srch_country_id').val() , pkg_type: $('#srch_pkg_type').val(), weight: $('#srch_pkg_weight').val() },
                    success: function(ret) {  
                        //alert(ret); 
                         var cnt = "";
                        $.each(ret.charges, function(i, info) {
                            
                            cnt += '<div class="box box-success">';                             
                            cnt += '<div class="info-box box-body">';                            
                            //cnt += '<span class="info-box-icon bg-green1"> <img src="'+ info.logo_url +'" alt="' +  info.international_service_provider + '"></span>';                            
                            cnt += '<span class="info-box-icon"> <img src="<?php echo base_url('asset/images/logo.png')?>" alt="' +  info.international_service_provider + '"> </span>';                            
                            cnt += '<div class="info-box-content">';
                            cnt += '<span class="info-box-text"><h2>' +  info.international_service_provider + '</h2></span>';
                            cnt += '<span class="info-box-number">Amount : <i class="fa fa-rupee margin-r-5"></i>'+ info.rate   +'</span>';
                            cnt += '</div>';                            
                            cnt += '</div>';                            
                            cnt += '</div>';                            
                       //   alert(i + " = " + info);
                          //cnt+= "<strong class='label'>" +  info.international_service_provider + "<br> CP Rate : "+ info.cp_rate  +"<br> DC Rate : "+ info.dc_rate + "</strong>";
                          //cnt+= "<h4 class='text-info'>" +  info.international_service_provider + "</h4><img src='"+ info.logo_url +"' class='img img-md' > <p class='text-muted text-right text-blue'> Amount : <i class='fa fa-rupee margin-r-5'></i>"+ info.rate   +"</p><hr>";
                        });
                        //cnt+= "</div>";
                        $('.rate_show').html(cnt); 
                                                 
                    }
             });
     });

      <?php if($btn_flg == 1) {?>
        $(".btn_calc").click();
      <?php } ?>

});
</script> 