<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Serviceable Pincode with Franchise Report</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">Serviceable Pincode with Franchise Report</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  
        <div class="box box-info no-print"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="<?php echo site_url('servicable-pincode-report') ?>" id="frmsearch">          
             <div class="row">   
                 <div class="form-group col-md-4"> 
                    <label>Search Pincode</label>
                    <div class="input-group date"> 
                      <input type="text" class="form-control " id="srch_pincode" name="srch_pincode" placeholder="Pincode" value="<?php echo set_value('srch_pincode',$srch_pincode);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                   
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Search'"><i class="fa fa-search"></i> Search</button>
                </div>
             </div>  
            </form>
         </div> 
         </div> 
         <?php if(($submit_flg)) { ?>         
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Serviceable Pincode with Franchise Info Report <span></span></h3> 
            </div>
            <div class="box-body">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>Pincode</th>
                        <th>State</th>
                        <th>City</th> 
                        <th>Region</th>  
                        <th>Is Metro</th>  
                        <th>Franchise Tye</th>
                        <th>Contact Person</th> 
                        <th>Mobile</th> 
                        <th>Email</th> 
                        <th>Branch Code</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php  
                        foreach($record_list as $j => $info) {  
                             if($info['serve_type'] != 'Serviceable') $cls = 'text-red'; else $cls ='';
                        ?>
                        <tr class="<?php echo $cls;?>">
                            <td><?php echo ($j+1) ;?></td>
                            <td><?php echo $info['pincode'];  if($info['serve_type'] != 'Serviceable') echo "<br><i class='badge'>" . $info['serve_type'].'</i>'; ?></td> 
                            <td><?php echo $info['state']?></td> 
                            <td><?php echo $info['city']?></td> 
                            <td><?php echo $info['region']?></td> 
                            <td><?php echo $info['metro_city']?></td> 
                            <td><?php echo $info['franchise_type']?></td> 
                            <td><?php echo $info['contact_person']?></td> 
                            <td><?php echo $info['mobile']?></td>  
                            <td><?php echo $info['email']?></td>
                            <td><strong><?php echo $info['branch_code']?></strong></td>
                        </tr>
                        <?php } ?> 
                       
                    </tbody>
                     
                </table> 
               <!-- <i class="text-red">NOTE:- ODA : Out-of-Delivery Area. ODA CHARGES APPLICABLE</i>-->
                <?php } ?>
            </div>
            <div class="box-footer with-border ">
               <div class="form-group col-sm-12 text-left">
                    <label>Total Records : <?php echo $total_records;?></label>
                </div>
                 
            </div>
            </div> 
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
