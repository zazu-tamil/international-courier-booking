<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>City Wise Booking Summary</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">City Wise Booking Summary</li>
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
              <h3 class="box-title text-white">City Wise Booking Summary </h3> 
            </div>
            <div class="box-body">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped" id="content-table">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>State & City</th>
                        <th>No of Booking</th>  
                        <th>No of Pcs</th>
                        <th>Weight</th> 
                        <th>Amount</th>  
                    </tr> 
                    </thead>
                    <tbody>
                        <?php 
                            $tot['no_of_booking'] = $tot['no_of_pieces'] = $tot['weight'] = $tot['amt'] = 0;
                        foreach($record_list as $j => $info) { 
                          $tot['no_of_booking'] += $info['no_of_booking'];  
                          $tot['no_of_pieces'] += $info['no_of_pieces'];  
                          $tot['weight'] += $info['weight'];  
                          $tot['amt'] += $info['total'];  
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?></td> 
                            <td><?php echo $info['origin_state_code'] . ' - ' . $info['origin_city_code'];?></td> 
                            <td class="text-right"><?php echo $info['no_of_booking'];?></td> 
                            <td class="text-right"><?php echo number_format($info['no_of_pieces'],0);?></td> 
                            <td class="text-right"><?php echo number_format($info['weight'],3)?></td>
                            <td class="text-right"><?php echo number_format($info['total'],2);?></td>   
                        </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="2">Total</th>
                                <th class="text-right"><?php echo number_format($tot['no_of_booking'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                                <th class="text-right"><?php echo number_format($tot['amt'],2)?></th> 
                            </tr>
                        </tfoot>
                    </tbody>
                     
                </table>  
                 
                  <?php } ?>
            </div>
            <div class="box-footer with-border ">
               <div class="form-group col-sm-6 text-left">
                    <label>Total Records : <?php echo $total_records;?></label>
                </div>
                <div class="form-group col-sm-6 text-right">
                    <?php //echo $pagination; ?>
                    <a class="btn btn-success dl-xls" data="City Wise Booking Summary" title="Download as Excel File">Download as <i class="fa fa-file-excel-o "></i></a>
                </div>
            </div>
            </div> 
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
