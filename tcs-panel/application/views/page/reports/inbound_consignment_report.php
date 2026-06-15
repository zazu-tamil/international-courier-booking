<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>In-bound Consignment Report</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Report</a></li> 
    <li class="active">In-bound Consignment Report</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  
        <div class="box box-info no-print"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="<?php echo site_url('inbound-consignment-report') ?>" id="frmsearch">          
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
                 <!--<div class="form-group col-md-4">
                    <label>Franchise</label>
                      <div class="input-group">
                        <?php //echo form_dropdown('srch_franchise_id',array('' => 'All') + $franchise_opt  ,set_value('srch_franchise_id',$srch_franchise_id) ,' id="srch_franchise_id" class="form-control"');?> 
                            
                      </div>                                   
                 </div> --> 
                  
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
              <h3 class="box-title text-white">In-bound Consignment Report <span></span></h3> 
            </div>
            <div class="box-body">  
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
                        <th>Weight</th> 
                        <th>Delivery Branch</th> 
                        <th>Status</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php $tot1['no_of_pieces'] = $tot1['weight'] = $tot1['amt'] = 0;
                        foreach($record_list as $franchise => $info1) {
                        echo "<tr>";
                            echo "<th colspan='9'>". $franchise ."</th>"; 
                        echo "</tr>";    
                            
                            $tot['no_of_pieces'] = $tot['weight'] = $tot['amt'] = 0;
                        foreach($info1 as $j => $info) { 
                          $tot['no_of_pieces'] += $info['no_of_pieces'];  
                          $tot['weight'] += $info['chargable_weight'];  
                        //  $tot['amt'] += $info['grand_total'];  
                        ?>
                        <tr>
                            <td><?php echo ($j+1). '<br>' . $info['booking_id'];?></td>
                            <td><?php echo $info['awbno']?></td>
                            <td><?php echo  date('d-m-Y', strtotime($info['booking_date']));?></td>
                            <td><?php echo $info['origin_pincode'];?><br /><?php echo $info['origin_state_code'] . ' - ' . $info['origin_city_code'];?></td> 
                            <td><?php echo $info['dest_pincode'];?><br /><?php echo $info['dest_state_code'] . ' - ' . $info['dest_city_code'];?></td> 
                            <td class="text-right"><?php echo $info['no_of_pieces'];?></td> 
                            <td class="text-right"><?php echo $info['chargable_weight']?></td> 
                            <td><?php echo $info['delivery_branch']?><br /><?php echo $info['contact_person']?></td>
                            <td><?php echo $info['status']?></td>
                        </tr>
                        <?php                
                        
                        
                        } ?> 
                            <tr>
                                <th class="text-right" colspan="5">Total</th>
                                <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th> 
                                <th></th>
                                <th></th>
                            </tr> 
                        <?php 
                        $tot1['no_of_pieces'] += $tot['no_of_pieces'];
                        $tot1['weight'] += $tot['weight'];
                        
                        } ?>
                            <tr>
                                <th class="text-right" colspan="5">Consolidated Total</th>
                                <th class="text-right"><?php echo number_format($tot1['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot1['weight'],3)?></th> 
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
                    <a class="btn btn-success dl-xls" data="In-bound Consignment Report" title="Download as Excel File">Download as <i class="fa fa-file-excel-o "></i></a>
                </div> 
            </div>
            </div> 
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
