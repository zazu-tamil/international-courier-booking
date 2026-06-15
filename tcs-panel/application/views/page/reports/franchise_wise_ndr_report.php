<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Franchise Wise NDR Report</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">Franchise Wise NDR Report</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  
        <div class="box box-info no-print"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="<?php echo site_url('franchise-NDR-report') ?>" id="frmsearch">          
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
                    <label>Franchise</label>
                      <div class="input-group">
                        <?php echo form_dropdown('srch_franchise_id',array('' => 'All') + $franchise_opt  ,set_value('srch_franchise_id',$srch_franchise_id) ,' id="srch_franchise_id" class="form-control"');?> 
                            
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
              <h3 class="box-title text-white">Franchise Wise NDR Report <span></span></h3> 
            </div>
            <div class="box-body table-responsive">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped" id="content-table">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>AWB No</th>
                        <th>Date</th>
                        <th>Origin</th> 
                        <th>Destination</th>  
                        <th>No of Pcs</th>
                        <th>Weight <br />[<i>In Kgs</i>]</th> 
                        <th>Amount</th> 
                        <th>Status</th> 
                        <th>NDR Reasons</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php $tot1['no_of_pieces'] = $tot1['weight'] = $tot1['amt'] = 0;
                        foreach($record_list as $franchise => $info1) {
                        echo "<tr>";
                            echo "<th colspan='13'>". $franchise ."</th>"; 
                        echo "</tr>";    
                            
                            $tot['no_of_pieces'] = $tot['weight'] = $tot['amt'] = 0;
                        foreach($info1 as $j => $info) { 
                          $tot['no_of_pieces'] += $info['no_of_pieces'];  
                          $tot['weight'] += $info['chargable_weight'];  
                          $tot['amt'] += $info['grand_total'];  
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?></td>
                            <td><?php echo $info['awbno']?></td>
                            <td><?php echo $info['booking_date']?></td>
                            <td><?php echo $info['origin_pincode'];?><br /><?php echo $info['origin_city'];?></td> 
                            <td><?php echo $info['dest_pincode'];?><br /><?php echo $info['dest_city'];?></td> 
                            <td class="text-right"><?php echo $info['no_of_pieces'];?></td> 
                            <td class="text-right"><?php echo $info['chargable_weight']?></td>
                            <td class="text-right"><?php echo number_format($info['grand_total'],2);?></td>   
                            <td><?php echo $info['status']?></td>
                            <td><?php echo str_replace(',','<br>',$info['ndr_details']);?></td>
                        </tr>
                        <?php                
                        
                        
                        } ?> 
                            <tr>
                                <th class="text-right" colspan="5">Total</th>
                                <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                                <th class="text-right"><?php echo number_format($tot['amt'],2)?></th>
                                <th></th>
                                <th></th>
                            </tr> 
                        <?php 
                        $tot1['no_of_pieces'] += $tot['no_of_pieces'];
                        $tot1['weight'] += $tot['weight'];
                        $tot1['amt'] += $tot['amt'];
                        } ?>
                            <tr>
                                <th class="text-right" colspan="5">Consolidated Total</th>
                                <th class="text-right"><?php echo number_format($tot1['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot1['weight'],3)?></th>
                                <th class="text-right"><?php echo number_format($tot1['amt'],2)?></th>
                                <th></th>
                                <th></th>
                            </tr> 
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
                    <a class="btn btn-success dl-xls" data="Franchise Wise NDR Report" title="Download as Excel File">Download as <i class="fa fa-file-excel-o "></i></a>
                </div> 
            </div>
            </div> 
            <?php } ?>  
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
