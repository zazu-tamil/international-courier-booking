<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Create Booking</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Booking</a></li> 
    <li class="active">Create Booking</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
   
  <!--<div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Create Booking</h3>   
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>-->
    <!--<div class="box-body"> -->
        <div class="box box-info">
        <div class="box-body">
        <div class="row">  
             <div class="form-group col-md-4">
                <label>AWB No</label>
                <input class="form-control" type="text" name="awb_no" id="awb_no" value="" placeholder="AWB No">                                             
             </div> 
             <div class="form-group col-md-4">
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
         <div class="row"> 
            <div class="form-group col-md-4">
                <label>Origin Pincode</label>
                <input class="form-control" type="text" name="origin_pincode" id="origin_pincode" value="" placeholder="Origin Pincode">                                             
             </div>
             <div class="form-group col-md-4">
                <label>Origin State</label>
                <?php echo form_dropdown('origin_state_code',array('' => 'Select') + $state_opt ,set_value('origin_state_code','') ,' id="origin_state_code" class="form-control"');?>                                             
             </div>
             <div class="form-group col-md-4">
                <label>Origin City</label>
                <?php echo form_dropdown('origin_city_code',array('' => 'Select') ,set_value('origin_city_code','') ,' id="origin_city_code" class="form-control"');?>                                          
             </div> 
         </div>
         <div class="row"> 
             <div class="form-group col-md-4">
                <label>Destination Pincode</label>
                <input class="form-control" type="text" name="destination_pincode" id="destination_pincode" value="" placeholder="Destination Pincode">                                             
             </div>
             <div class="form-group col-md-4">
                <label>Destination State</label>
                <?php echo form_dropdown('destination_state_code',array('' => 'Select') + $state_opt,set_value('destination_state_code','') ,' id="destination_state_code" class="form-control"');?>                                             
             </div>
             <div class="form-group col-md-4">
                <label>Destination City</label>
                <?php echo form_dropdown('destination_city_code',array('' => 'Select'),set_value('destination_city_code','') ,' id="destination_city_code" class="form-control"');?>                                              
             </div> 
         </div>
         <div class="row">
             <div class="form-group col-md-3">
                <label>No Of Pieces </label>
                <input class="form-control text-right" type="number" name="no_of_pieces" id="no_of_pieces" value="" placeholder="No of Pieces">                                             
             </div>
             <div class="form-group col-md-3">
                <label>Package Weight <i class="label label-info">In Kg</i></label>
                <input class="form-control text-right" type="number" name="weight" id="weight" value="" placeholder="Weight">                                             
             </div>
             <div class="form-group col-md-6">
                <label>Dimension [ L x W x H ] <i class="label label-info">In Cms</i></label>
                <div class="row">
                    <div class="col-xs-4">
                      <input type="text" class="form-control text-right" placeholder="Length">
                    </div>
                    <div class="col-xs-4">
                      <input type="text" class="form-control text-right" placeholder="Width">
                    </div>
                    <div class="col-xs-4">
                      <input type="text" class="form-control text-right" placeholder="Height">
                    </div>
                  </div>                                          
             </div>
         </div>
         </div>
         </div> 
         <div class="box box-info">
         <div class="box-body">
         <div class="row"> 
             <div class="form-group col-md-6">
                 <h3 class="text-blue">Consignor Details</h3>     
                <div class="row">
                     <div class="form-group col-md-3">
                        <label>Code</label>
                        <input class="form-control" type="text" name="company" id="company" value="" placeholder="Code">                                             
                     </div>
                     <div class="form-group col-md-9">
                        <label>Existing Customer</label>
                        <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code','') ,' id="state_code" class="form-control"');?>                                             
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
             <div class="form-group col-md-6">
                <h3 class="text-blue">Consignee Details</h3> 
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>Code</label>
                        <input class="form-control" type="text" name="company" id="company" value="" placeholder="Code">                                             
                     </div>
                     <div class="form-group col-md-9">
                        <label>Existing Customer Consignee</label>
                        <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code','') ,' id="state_code" class="form-control"');?>                                             
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
        
    <!--</div>
    <!- - /.box-body - - >
    <div class="box-footer">
        
    </div>
    <!- - /.box-footer- ->
  </div> -->
  <!-- /.box -->

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
