<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Customer Invoice Generate</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Customer Invoice</a></li> 
    <li class="active">Customer Invoice</li>
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
                 <div class="form-group col-md-2"> 
                    <label>From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_from_date" name="srch_from_date" value="<?php echo set_value('srch_from_date',$srch_from_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-2"> 
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
                    <label>Customer</label>
                      <div class="input-group">
                        <?php echo form_dropdown('srch_customer_id',array('' => 'Select') + $service_opt  ,set_value('srch_customer_id','') ,' id="srch_customer_id" class="form-control" required="true"');?> 
                            
                      </div>                                   
                 </div> 
                  <div class="form-group col-md-2">
                    <label>Payment Mode</label>
                      <div class="input-group">
                        <?php echo form_dropdown('srch_pay_mode',array('' => 'All' , 'Cash' => 'Cash' , 'Credit' => 'Credit')   ,set_value('srch_pay_mode',$srch_pay_mode) ,' id="srch_pay_mode" class="form-control"');?> 
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
              <h3 class="box-title text-white">Customer Airway Bill Lists</h3> 
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
                        <th>Cust.Ref.no</th> 
                        <th>No of Pcs</th>
                        <th>Weight</th> 
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
                          $tot['amt'] += $info['grand_total'];  
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?></td>
                            <td><a href="<?php echo site_url('in-scan-edit/') . $info['booking_id']; ?>" target="_blank"><?php echo $info['awbno']?></a></td>
                            <td>
                                <?php echo date('d-m-Y',strtotime($info['booking_date']))?><br />
                                <i class="badge <?php echo ($info['payment_mode'] == 'Cash' ? 'bg-green' : 'bg-red' ); ?>"><?php echo $info['payment_mode']?></i> 
                            </td>
                            <td><?php echo $info['origin_pincode'];?><br /><?php echo $info['origin_state_code'] . ' - ' . $info['origin_city_code'];?></td> 
                            <td><?php echo $info['dest_pincode'];?><br /><?php echo $info['dest_state_code'] . ' - ' . $info['dest_city_code'];?></td> 
                            <td class="text-center"><?php echo $info['customer_ref_no'];?></td> 
                            <td class="text-right"><?php echo $info['no_of_pieces'];?></td> 
                            <td class="text-right"><?php echo $info['chargable_weight']?></td>
                            <td class="text-right"><?php echo number_format($info['grand_total'],2);?></td>   
                            <td><?php echo $info['status']?></td>
                            <td class="text-center">
                                <input type="checkbox" name="awb_nos[]" class="awb_nos checkbox1" value="<?php echo $info['awbno']?>" />
                            </td>
                        </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="6">Total</th>
                                <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                                <th class="text-right"><?php echo number_format($tot['amt'],2)?></th>
                                <th></th>
                                <th class="text-center">
                                    <input type="checkbox" name="select_all" id="select_all" class="select_all" value="1" /><br />
                                    <label for="select_all">Select All</label>
                                </th>
                            </tr>
                        </tfoot>
                    </tbody>
                     
                </table>  
                 
                  <?php } else { echo "<h2 class='text-red'>No Records Found.</h2>"; } ?>
            </div> 
           </div> 
           <?php  if(!empty($record_list)) { ?>  
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
                        <input type="hidden" name="customer_id" value="<?php echo $srch_customer_id; ?>" />
                        <input type="submit" class="btn btn-success" name="btn_generate" value="Generate Invoice" />
                    </div>
                    <div class="form-group col-md-3">
                        <input type="button" class="btn btn-info" name="btn_invoice_list" value="Back to Invoice List" />
                    </div>
                </div>
            </div>
           </div>
           <?php } ?>
           </form>
            <?php } ?> 
        
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
