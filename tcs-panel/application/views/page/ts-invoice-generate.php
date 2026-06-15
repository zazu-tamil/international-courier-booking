<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Transhipment Invoice Generate</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Invoice</a></li> 
    <li class="active">Transhipment Invoice Generate</li>
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
                 <div class="form-group col-md-3"> 
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="srch_connection_ts_charges" id="srch_connection_ts_charges"  value="1" <?php echo set_checkbox('srch_connection_ts_charges',$srch_connection_ts_charges);?>>
                           <strong>Connection Charges</strong>
                        </label>
                      </div> 
                 </div>
                 <div class="form-group col-md-3"> 
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="srch_delivery_ts_charges" id="srch_delivery_ts_charges" value="1" <?php echo set_checkbox('srch_delivery_ts_charges',$srch_delivery_ts_charges);?>>
                           <strong>Delivery Charges</strong>
                        </label>
                      </div>
                 </div>
                 </div>
                 <div class="row">  
                 <div class="form-group col-md-6">
                    <label>Franchises</label>
                      <div class="input-group1">
                        <?php echo form_dropdown('srch_franchise_id',array('' => 'Select the Franchise') + $franchise_opt  ,set_value('srch_franchise_id',$srch_franchise_id) ,' id="srch_franchise_id" class="form-control" required');?> 
                            
                      </div>                                   
                 </div>  
                  
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show Records'"><i class="fa fa-search"></i> Show Records</button>
                </div>
             </div>  
            </form>
         </div> 
         </div> 
         <?php if(($submit_flg)) { ?>  
         <form method="post">       
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Franchises Airway Bill Lists</h3> 
            </div>
            <div class="box-body">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>AWB No</th>
                        <th>Date</th>
                        <th>Origin</th> 
                        <th>Destination</th> 
                        <th>No of Pcs</th>
                        <th>Weight</th> 
                        <?php if($srch_connection_ts_charges == 1) {?>
                        <th>C.TS</th> 
                        <?php } if($srch_delivery_ts_charges == 1) {?>
                        <th>D.TS</th> 
                        <?php } ?>
                        <th>FOD</th> 
                        <th>COD</th> 
                        <th>ODA</th> 
                        <th>Amount</th> 
                        <th>Status</th> 
                        <th>Select</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php 
                            $tot['no_of_pieces'] = $tot['weight'] = $tot['amt'] = 0;
                        foreach($record_list as $j => $info) { 
                          $tot['no_of_pieces'] += $info['no_of_pieces'];  
                          $tot['weight'] += $info['chargable_weight'];  
                            
                          
                          if($srch_connection_ts_charges == 1)
                            $connection_ts_charges = $info['connection_ts_charges'];
                          else
                            $connection_ts_charges = 0;  
                            
                          if($srch_delivery_ts_charges == 1)
                            $delivery_ts_charges = $info['delivery_ts_charges'];
                          else
                            $delivery_ts_charges = 0;  
                            
                          $ts_charges = ($connection_ts_charges + $delivery_ts_charges + $info['fod_chrges'] + $info['cod_chrges'] + $info['oda_chrg']);    
                            
                          $tot['amt'] += $ts_charges;   
                          
                          
                          if($info['dest_state_code'] == '' || $info['dest_city_code'] == '')
                          {
                            $cls = "text-red";
                          } else {
                            $cls = '';
                          }
                        ?>
                        <tr class="<?php echo $cls; ?>">
                            <td><?php echo ($j+1)?></td>
                            <td><?php echo $info['awbno']?></td>
                            <td><?php echo $info['booking_date']?></td>
                            <td><?php echo $info['origin_pincode'];?><br /><?php echo $info['origin_state_code'] . ' - ' . $info['origin_city_code'];?></td> 
                            <td><?php echo $info['dest_pincode'];?><br /><?php echo $info['dest_state_code'] . ' - ' . $info['dest_city_code'];?></td> 
                            <td class="text-right"><?php echo $info['no_of_pieces'];?></td> 
                            <td class="text-right"><?php echo $info['chargable_weight']?></td>
                            <?php if($srch_connection_ts_charges == 1) {?>
                            <td class="text-right"><?php echo number_format($info['connection_ts_charges'],2);?></td>
                            <?php } ?>
                            <?php if($srch_delivery_ts_charges == 1) {?>
                            <td class="text-right"><?php echo number_format($info['delivery_ts_charges'],2);?></td>
                            <?php } ?>
                            <td class="text-right"><?php echo ($info['to_pay']== '1'?  number_format($info['fod_chrges'],2) : '-'); ?></td>
                            <td class="text-right"><?php echo ($info['cod']== '1'?   number_format($info['cod_chrges'],2)  : '-'); ?></td>
                            <td class="text-right"><?php echo (($info['serve_type'] == 'ODA' || $info['dest_state_code'] == '' || $info['dest_city_code'] == '') ? number_format($info['oda_chrg'],2) : '-'); ?></td>
                            <td class="text-right"><?php echo number_format($ts_charges,2);?></td>   
                            <td><?php echo $info['status']?></td>
                            <td class="text-center">
                                <input type="hidden" name="fod[<?php echo $info['awbno']?>]" value="<?php echo number_format($info['fod_chrges'],2);?>" />
                                <input type="hidden" name="cod[<?php echo $info['awbno']?>]" value="<?php echo number_format($info['cod_chrges'],2);?>" />
                                <input type="hidden" name="ts[<?php echo $info['awbno']?>]" value="<?php echo number_format( ($connection_ts_charges + $delivery_ts_charges),2);?>" />
                                <input type="hidden" name="oda[<?php echo $info['awbno']?>]" value="<?php echo number_format($info['oda_chrg'],2);?>" />
                                <input type="hidden" name="invoice_amt[<?php echo $info['awbno']?>]" value="<?php echo number_format($ts_charges,2);?>" />
                                <input type="checkbox" name="awb_nos[]" class="awb_nos" value="<?php echo $info['awbno']?>" />
                            </td>
                        </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="5">Total</th>
                                <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                                 <?php if($srch_connection_ts_charges == 1) {?>
                                <th></th>
                                 <?php } if($srch_delivery_ts_charges == 1) {?>
                                <th></th>
                                 <?php } ?>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-right"><?php echo number_format($tot['amt'],2)?></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </tbody>
                     
                </table>  
                 
                  <?php } ?>
            </div> 
           </div> 
           <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Invoice Info</h3>
            </div>
            <div class="box-body text-center">
                <div class="row">
                    <div class="form-group col-md-3">  
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control pull-right datepicker" id="invoice_date" name="invoice_date" value="<?php echo set_value('invoice_date',date('Y-m-d'));?>">
                        </div>                                           
                    </div>
                    <div class="form-group col-md-3">
                        <input type="hidden" name="franchise_id" value="<?php echo $srch_franchise_id; ?>" />
                        <input type="submit" class="btn btn-success" name="btn_generate" value="Generate Invoice" />
                    </div>
                    <div class="form-group col-md-3">
                        <input type="button" class="btn btn-info" name="btn_invoice_list" value="Back to Invoice List" />
                    </div>
                </div>
            </div>
           </div>
           </form>
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
