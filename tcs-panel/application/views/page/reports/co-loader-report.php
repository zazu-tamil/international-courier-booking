<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Co-loader Wise Booking Report </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">Co-loader Wise Booking Report</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="" id="frmsearch">          
             <div class="row">   
                 <div class="form-group col-md-3"> 
                    <label>From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_from_date" name="srch_from_date" value="<?php echo set_value('srch_from_date',$srch_from_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-3"> 
                    <label>To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_to_date" name="srch_to_date" value="<?php echo set_value('srch_to_date',$srch_to_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-4">
                    <label>Co-Loader</label>
                      <div class="input-group">
                        <?php echo form_dropdown('srch_co_loader_id',array('' => 'All') + $co_loader_opt  ,set_value('srch_co_loader_id',$srch_co_loader_id) ,' id="srch_co_loader_id" class="form-control"');?> 
                       </div>                                   
                 </div>  
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show Reports'"><i class="fa fa-search"></i> Show Reports</button>
                </div>
             </div>  
            </form>
         </div> 
         </div> 
         <?php if(($submit_flg)) { ?>         
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Co-Loader Wise Booking Report </h3> 
            </div>
            <div class="box-body">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped" id="content-table">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>AWB No</th>
                        <th>CL No</th>  
                        <th>Branch</th>  
                        <th>Contact No</th>  
                        <th>Booking Date</th>  
                        <th>Delivery Date</th>  
                        <th>Weight</th>  
                        <th>CL Charges</th> 
                        <th>Status</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php
	                           foreach($record_list as $co_loadder => $info1) {   
                        ?>
                        <tr>
                            <th colspan="10">Co-Loader : <?php echo $co_loadder ;?></th>
                        </tr>
                        <?php 
                             $tot['weight'] = $tot['amt'] = 0;
                        foreach($info1 as $j => $info) {    
                          $tot['weight'] += $info['cl_chrg_weight'];  
                          $tot['amt'] += $info['co_loader_charges'];  
                        ?> 
                        <tr>
                            <td><?php echo ($j+1)?></td> 
                            <td><?php echo $info['awbno'];?></td> 
                            <td><?php echo $info['cl_no'];?></td> 
                            <td><?php echo $info['cl_branch'];?></td> 
                            <td><?php echo $info['cl_contact_number'];?></td> 
                            <td><?php echo date('d-m-Y', strtotime($info['cl_booking_date']));?></td> 
                            <td><?php echo date('d-m-Y', strtotime($info['cl_delivery_date']));?></td>  
                            <td class="text-right"><?php echo number_format($info['cl_chrg_weight'],3)?></td>
                            <td class="text-right"><?php echo number_format($info['co_loader_charges'],2);?></td>   
                            <td><?php echo $info['status'];?></td>
                        </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="7">Total</th> 
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                                <th class="text-right"><?php echo number_format($tot['amt'],2)?></th> 
                            </tr>
                        </tfoot>
                        <?php } ?>
                    </tbody>
                     
                </table>  
                 
                  <?php } ?>
            </div>
            <div class="box-footer with-border ">
                
                <div class="form-group col-sm-12 text-right">
                    <?php //echo $pagination; ?>
                    <a class="btn btn-success dl-xls" data="Co-Loader Wise Booking Report" title="Download as Excel File">Download as <i class="fa fa-file-excel-o "></i></a>
                </div>
            </div>
            </div> 
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
