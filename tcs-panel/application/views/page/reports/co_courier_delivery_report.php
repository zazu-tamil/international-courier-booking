<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Co-Courier Delivery Report</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">Co-Courier Delivery Report</li>
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
                    <label>Co-Courier</label>
                      <div class="input-group">
                        <?php echo form_dropdown('srch_co_courier_id',array('' => 'All') + $co_courier_opt  ,set_value('srch_co_courier_id','') ,' id="srch_co_courier_id" class="form-control"');?> 
                            
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
              <h3 class="box-title text-white">Co-Courier Delivery Report <span></span></h3> 
            </div>
            <div class="box-body">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped" id="content-table">
                    <thead>
                    <?php foreach($record_list as $co_courier => $det) { ?>
                    <tr>
                        <th colspan="9">Co-Courier: <?php echo $co_courier; ?></th>
                    </tr>
                    <tr class="bg-blue-gradient">
                        <th>S.No</th>
                        <th>AWB No</th>  
                        <th>Date</th>  
                        <th>Type</th>  
                        <th>Assign To</th>   
                        <th class="text-right">To Pay Amt</th>  
                        <th class="text-right">No of Pcs</th>  
                        <th class="text-right">Weight</th> 
                        <th>Curr.Status</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php 
                            $tot['no_of_pieces'] = $tot['weight'] = $tot['to_pay_amt'] = 0;
                        foreach($det as $j => $info) { 
                          $tot['to_pay_amt'] += $info['to_pay_amt'];  
                          $tot['no_of_pieces'] += $info['no_of_pcs'];  
                          $tot['weight'] += $info['weight']; 
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?></td>
                            <td><?php echo $info['awb_no']?></td> 
                            <td><?php echo date('d-m-Y', strtotime($info['booking_date']))?></td>   
                            <td><?php echo $info['pkg_type']?></td>   
                            <td><?php echo $info['delivery_by']?></td>  
                            <td class="text-right"><?php echo $info['to_pay_amt']?></td>   
                            <td class="text-right"><?php echo $info['no_of_pcs']?></td>   
                            <td class="text-right"><?php echo $info['weight']?></td> 
                            <td><?php echo $info['curr_status']?></td>
                        </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="5">Total</th>
                                <th class="text-right"><?php echo number_format($tot['to_pay_amt'],2)?></th>
                                <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th> 
                                <th></th>
                            </tr>
                        </tfoot>
                    </tbody>
                    <?php } ?> 
                </table>  
                 
                  <?php } ?>
            </div>
            <div class="box-footer with-border ">
               <div class="form-group col-sm-6 text-left">
                    <label>Total Records : <?php echo count($record_list);?></label>
                </div>
                <div class="form-group col-sm-6 text-right">
                    <?php //echo $pagination; ?>
                    <a class="btn btn-success dl-xls" data="Line Haul Report" title="Download as Excel File">Download as <i class="fa fa-file-excel-o "></i></a>
                </div>
            </div>
            </div> 
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
