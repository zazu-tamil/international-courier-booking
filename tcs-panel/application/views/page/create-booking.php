<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>EDP Entry</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> EDP Entry</a></li> 
    <li class="active">EDP Entry</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
<form method="post" action="">
  <!-- Default box --> 
        <div class="box box-info">
        <div class="box-body">
        <div class="row">  
             <div class="form-group col-md-4">
                <label>AWB No</label>
                <input class="form-control" type="text" name="awbno" id="awbno" value="" placeholder="AWB No">                                             
             </div>  
             <div class="form-group col-md-4">
                <input type="hidden" name="mode" value="Add" />
                <label>Date</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right datepicker" id="booking_date" name="booking_date" value="<?php echo date('Y-m-d');?>">
                </div>
                <!-- /.input group -->                                             
             </div> 
             <div class="form-group col-md-4">
                <label>Time</label>
                 <div class="input-group">
                    <input type="text" class="form-control timepicker" name="booking_time" id="booking_time">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->                                           
             </div> 
             
         </div> 
         </div> 
         </div>  
        <div class="row">
            <div class="col-md-6">
               <div class="box box-info">
                    <div class="box-body">
                         <div class="row"> 
                            <div class="form-group col-md-12">
                                <label>Origin Pincode</label>
                                <input class="form-control" type="text" name="origin_pincode" id="origin_pincode" value="" placeholder="Origin Pincode">                                             
                             </div>
                            <div class="form-group col-md-12"> 
                                <label>Origin State</label>
                                <?php echo form_dropdown('origin_state_code',array('' => 'Select') + $state_opt ,set_value('origin_state_code','') ,' id="origin_state_code" class="form-control" readonly="true"');?>                                             
                            </div>
                            <div class="form-group col-md-12">  
                                <label>Origin City</label>
                                <?php echo form_dropdown('origin_city_code',array('' => 'Select') ,set_value('origin_city_code','') ,' id="origin_city_code" class="form-control" readonly="true"');?>                                          
                            </div>
                              
                         </div>
                     </div>
               </div> 
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                 <div class="box-body">
                     <div class="row"> 
                         <div class="form-group col-md-12">
                            <label>Destination Pincode</label>
                            <input class="form-control" type="text" name="dest_pincode" id="dest_pincode" value="" placeholder="Destination Pincode">                                             
                         </div>
                        <div class="form-group col-md-12"> 
                            <label>Destination State</label>
                            <?php echo form_dropdown('dest_state_code',array('' => 'Select') + $state_opt,set_value('dest_state_code','') ,' id="dest_state_code" class="form-control" readonly="true"');?>                                             
                        </div>
                        <div class="form-group col-md-12">  
                            <label>Destination City</label>
                            <?php echo form_dropdown('dest_city_code',array('' => 'Select'),set_value('dest_city_code','') ,' id="dest_city_code" class="form-control" readonly="true"');?>                                              
                        </div>
                        
                     </div>
                  </div>
                 </div>
            </div>
        </div> 
         <div class="row">
             <div class="col-md-6">
                <div class="box box-info">
                 <div class="box-body">
                    <h3 class="text-blue">Consignor Details</h3>     
                    <div class="row">
                         <div class="form-group col-md-3">
                            <label>Code</label>
                            <input class="form-control" type="text" name="consignor_code" id="consignor_code" value="" placeholder="Code">                                             
                         </div>
                         <div class="form-group col-md-9">
                            <label>Consignor Customer</label>
                            <?php echo form_dropdown('consignor_id',array('' => 'Select') + $customer_opt,set_value('consignor_id','') ,' id="consignor_id" class="form-control"');?>                                             
                         </div>
                         <div class="form-group col-md-12">
                            <label>Company</label>
                            <input class="form-control" type="text" name="company" id="company" value="" placeholder="Company Name">                                             
                         </div> 
                         <div class="form-group col-md-12">
                            <label>Sender Name</label>
                            <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Contact Person Name">                                             
                         </div> 
                         <div class="form-group col-md-12">
                            <label>Mobile</label>
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                         </div>
                         <div class="form-group col-md-12">
                            <label>Full Address</label>
                            <textarea class="form-control" name="address" placeholder="Address" id="address"></textarea>                                             
                         </div>  
                         <div class="form-group col-md-5">
                            <label>State</label>
                            <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code','') ,' id="state_code" class="form-control"');?>                                             
                         </div>
                         <div class="form-group col-md-4">
                            <label>City</label>
                            <?php echo form_dropdown('city_code',array('' => 'Select') ,set_value('city_code') ,' id="city_code" class="form-control"');?>                                             
                         </div>
                         <div class="form-group col-md-3">
                            <label>Pincode</label>
                            <input class="form-control" type="text" name="pincode" id="pincode" value="" placeholder="Pincode">                                             
                         </div>
                    </div>
                 </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                 <div class="box-body">
                    <h3 class="text-blue">Consignee Details</h3> 
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Code</label>
                            <input class="form-control" type="text" name="consignee_code" id="consignee_code" value="" placeholder="Code">                                             
                         </div>
                         <div class="form-group col-md-9">
                            <label>Consignee Customer</label>
                            <?php echo form_dropdown('consignee_id',array('' => 'Select') ,set_value('consignee_id','') ,' id="consignee_id" class="form-control"');?>                                             
                         </div> 
                        <div class="form-group col-md-12">
                            <label>Company</label>
                            <input class="form-control" type="text" name="company" id="company" value="" placeholder="Company Name">                                             
                         </div> 
                         <div class="form-group col-md-12">
                            <label>Receiver Name</label>
                            <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Contact Person Name">                                             
                         </div> 
                         <div class="form-group col-md-12">
                            <label>Mobile</label>
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                         </div> 
                         <div class="form-group col-md-12">
                            <label>Full Address</label>
                            <textarea class="form-control" name="address" placeholder="Address" id="address"></textarea>                                             
                         </div>  
                         <div class="form-group col-md-5">
                            <label>State</label>
                            <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code','') ,' id="state_code" class="form-control"');?>                                             
                         </div>
                         <div class="form-group col-md-4">
                            <label>City</label>
                            <?php echo form_dropdown('city_code',array('' => 'Select') ,set_value('city_code') ,' id="city_code" class="form-control"');?>                                             
                         </div>
                         <div class="form-group col-md-3">
                            <label>Pincode</label>
                            <input class="form-control" type="text" name="pincode" id="pincode" value="" placeholder="Pincode">                                             
                         </div>
                    </div> 
                 </div>
                </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4">
                <div class="box box-info">
                 <div class="box-header"><strong>Services</strong></div>
                 <div class="box-body"> 
                 <div class="row">
                     <div class="form-group col-md-12">
                        <label>Carrier </label>
                        <?php echo form_dropdown('carrier_id',array('' => 'Select') + $carrier_opt ,set_value('carrier_id','1') ,' id="carrier_id" class="form-control"');?>                                             
                     </div>
                     <div class="form-group col-md-12">    
                        <label>Service</label>
                        <?php echo form_dropdown('service_id',array('' => 'Select') + $service_opt ,set_value('service_id','1') ,' id="service_id" class="form-control"');?>                                             
                     </div>
                     <div class="form-group col-md-12">
                        <label>Package Type</label>
                        <?php echo form_dropdown('package_type_id',array('' => 'Select') + $package_type_opt ,set_value('package_type_id','1') ,' id="package_type_id" class="form-control"');?>                                             
                     </div> 
                     <div class="form-group col-md-12">
                        <label>Product Type</label>
                        <?php echo form_dropdown('product_type_id',array('' => 'Select') + $product_type_opt ,set_value('product_type_id','1') ,' id="product_type_id" class="form-control"');?>                                             
                     </div> 
                     <div class="form-group col-md-4 text-center">
                        <label for="to_pay">To Pay</label>                                              
                     </div>
                     <div class="form-group col-md-2 text-center"> 
                        <input class="flat-red" type="checkbox" name="to_pay" id="to_pay" value="1">                                             
                     </div>
                     <div class="form-group col-md-4 text-center">
                        <label for="cod">Is COD</label>                                              
                     </div>
                     <div class="form-group col-md-2 text-center"> 
                        <input class="flat-red" type="checkbox" name="cod" id="cod" value="1">                                             
                     </div>                       
                     <div class="form-group col-md-12 cod_amt hide">                          
                       <label>COD Amount</label>  
                       <input class="form-control" type="number" name="cod_amount" id="cod_amount" value="" placeholder="COD Amount">                                             
                     </div>  
                     <div class="form-group col-md-12">
                        <label>Commodity</label>
                        <?php echo form_dropdown('commodity_type',array('' => 'Select') + $commodity_type_opt ,set_value('commodity_type','') ,' id="commodity_type" class="form-control"');?>                                             
                     </div>
                     <div class="form-group col-md-12">
                        <label>Commodity Invoice Value</label>
                        <input class="form-control" type="text" name="commodity_invoice_value" id="commodity_invoice_value" value="" placeholder="Commodity Invoice Value">                                             
                     </div>
                     <div class="form-group col-md-12">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Description"></textarea>                                             
                     </div>
                 </div> 
                 </div>
                 </div>
            </div>
            <div class="col-md-4">
                <div class="box box-info">
                 <div class="box-header"><strong>Package Weight</strong></div>
                    <div class="box-body"> 
                        <div class="form-group col-md-12">
                            <label>No Of Pieces </label>
                            <input class="form-control text-right" type="number" name="no_of_pieces" id="no_of_pieces" value="" placeholder="No of Pieces">                                             
                         </div>
                         <div class="form-group col-md-12">
                            <label>Actual Weight <i class="label label-info">In Kg</i></label>
                            <input class="form-control text-right" type="number" name="weight" id="weight" step="any" min="0" value="" placeholder="Weight">                                             
                         </div>
                         <div class="form-group col-md-12">
                            <label>Dimension [ L x W x H ] <i class="label label-info">In Cms</i></label>
                            <div class="row">
                                <div class="form-group col-md-4">
                                   
                                  <input type="text" id="length" name="length" class="form-control text-right" placeholder="Length">
                                </div>
                                <div class="form-group col-md-4">
                                  <input type="text" id="width" name="width" class="form-control text-right" placeholder="Width">
                                </div>
                                <div class="form-group col-md-4">
                                  <input type="text" id="height" name="height" class="form-control text-right" placeholder="Height">
                                </div>
                                <div class="form-group col-md-12 text-center">
                                  <button class="btn btn-warning btn_calc_wt" type="button">Calculate Volumetric</button>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Volumetric Weight  : </label>
                                    <div class="surface_wt"></div>
                                    <div class="air_wt"></div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Chargable Weight</label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Actual</label>
                                </div>    
                                <div class="form-group col-md-3">
                                    <input class="chargable_opt flat-red" type="radio" name="chargable_opt" value="Actual" checked="true">  
                                </div>
                                <div class="form-group col-md-3"> 
                                    <label>Volumetric</label>
                                </div>    
                                <div class="form-group col-md-3">
                                    <input class="chargable_opt flat-red" type="radio" name="chargable_opt" value="Volumetric"> 
                                </div>
                                <div class="form-group col-md-12"> 
                                    <input class="form-control text-right" type="number" name="chargable_weight" id="chargable_weight" step="any" min="0" value="" placeholder="Chargable Weight">
                                </div>
                              </div>                                          
                         </div>
                    </div>
                 </div>
            </div>
            <div class="col-md-4">
                <div class="box box-info">
                 <div class="box-header"><strong>Charges</strong></div>
                 <div class="box-body"> 
                 <div class="row">
                     <div class="form-group col-md-6">
                        <label>Is Manual Calculation</label>
                      </div>
                     <div class="form-group col-md-6">   
                        <input class="is_manual_rate flat-red" type="checkbox" name="is_manual_rate" value="1" checked="true">                                             
                     </div>
                     <div class="form-group col-md-12">
                        <label>Rate</label>
                        <input class="form-control text-right calc" type="number" name="rate" id="rate" value="0" placeholder="Rate">                                             
                     </div>
                     <div class="form-group col-md-12">    
                        <label>COD Charges</label>
                        <input class="form-control text-right calc" type="number" name="cod_charges" id="cod_charges" value="0" placeholder="COD Charges" readonly="true">                                             
                     </div>
                     <div class="form-group col-md-12">
                        <label>Fuel Surcharges</label>
                        <input class="form-control text-right calc" type="number" name="fuel_charges" id="fuel_charges" value="0" placeholder="Fuel Surcharges">                                             
                     </div>
                 </div>
                 <div class="row">
                     <div class="form-group col-md-12">
                        <label>Sub Total</label>
                        <input class="form-control text-right" type="number" name="sub_total" id="sub_total" value="0" readonly="true" placeholder="Sub Total">                                             
                     </div>
                     <div class="form-group col-md-6">
                        <label>GST %</label>
                        <select class="form-control calc" name="tax_percentage" id="tax_percentage">
                            <option value="">Select GST</option>
                            <option value="18" selected>GST 18%</option>
                        </select>                                            
                     </div>
                     <div class="form-group col-md-6">
                        <label>GST Amount</label>
                        <input class="form-control text-right calc" type="number" name="tax_amt" id="tax_amt" value="0" readonly="true" placeholder="GST Amount">                                             
                     </div>
                     <div class="form-group col-md-12">
                        <label>Grand Total</label>
                        <input class="form-control text-right" type="number" name="grand_total" id="grand_total" value="0" readonly="true" placeholder="Grand Total">                                             
                     </div>
                 </div>
                 <div class="row">
                     <div class="form-group col-md-12">
                        <label>Payment Mode</label>
                        <?php echo form_dropdown('payment_mode',array('' => 'Select','Cash' => 'Cash','Cheque' => 'Cheque','NEFT' => 'NEFT' ) ,set_value('payment_mode','') ,' id="payment_mode" class="form-control"');?>                                             
                     </div> 
                 </div>
                 </div>
                 </div>
            </div>
         </div>
         <div class="box box-info">
          <div class="box-body text-center"> 
            <button type="submit" class="btn btn-success btn-mini"><i class="fa fa-save"></i>  Save</button>
          </div>
         </div> 
        
     
  <!-- /.box -->
  </form>  
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
