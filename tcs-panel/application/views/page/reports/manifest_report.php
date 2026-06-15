<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Manifest Report</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">Manifest Report</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  
        <div class="box box-info no-print"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="<?php echo site_url('manifest-report') ?>" id="frmsearch">          
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
              <h3 class="box-title text-white">Manifest Report  </h3> 
            </div>
            <div class="box-body">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>Manifest No</th>
                        <th>Date</th>
                        <th>From</th>  
                        <th>To</th>  
                        <th>Co-Loader</th>
                        <th>No.of Load</th> 
                        <th>Received</th> 
                        <th>No of Pcs</th>
                        <th>Weight</th>   
                        <th class="no-print">Print</th>   

                    </tr> 
                    </thead>
                    <tbody>
                        <?php 
                            $tot['no_of_booking'] = $tot['no_of_pieces'] = $tot['weight'] = $tot['amt'] = 0;
                        foreach($record_list as $j => $info) {  
                          $tot['no_of_pieces'] += $info['no_of_pieces'];  
                          $tot['weight'] += $info['tot_weight'];   
                        ?>
                        <tr>
                            <td><?php echo( $sno + ($j+1))?></td> 
                            <td><?php echo $info['manifest_no'];?></td> 
                            <td><?php echo date('d-m-Y', strtotime($info['manifest_date']));?></td> 
                            <td><?php echo $info['from_city_code'] ;?></td> 
                            <td><?php echo $info['to_city_code'];?></td> 
                            <td class="text-left"><?php echo $info['co_loader'];?><br /><?php echo $info['co_loader_awb_no'];?></td> 
                            <td class="text-right"><?php echo $info['open_mf'];?></td> 
                            <td class="text-right"><?php echo $info['received_mf'];?></td> 
                            <td class="text-right"><?php echo number_format($info['no_of_pieces'],0);?></td> 
                            <td class="text-right"><?php echo number_format($info['tot_weight'],3)?></td> 
                            <td class="text-center no-print"><a href="<?php echo site_url('print-manifest/').$info['manifest_no']?>" title="Manifest Print" target="_blank"><i class="fa fa-print"></i></a></td> 
                        </tr>
                        <?php } ?> 
                        <tr>
                            <th class="text-right" colspan="8">Total</th> 
                            <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                            <th class="text-right"><?php echo number_format($tot['weight'],3)?></th> 
                            <th class="no-print"></th>
                        </tr> 
                    </tbody>
                     
                </table>  
                 
                  <?php } ?>
            </div>
            <div class="box-footer with-border ">
               <div class="form-group col-sm-6 text-left">
                    <label>Total Records : <?php echo $total_records;?></label>
                </div>
                <div class="form-group col-sm-6 text-right no-print">
                    <?php //echo $pagination; ?>
                     <a class="btn btn-success dl-xls" data="Manifest Report" title="Download as Excel File">Download as <i class="fa fa-file-excel-o "></i></a>
                </div>
            </div>
            </div> 
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
