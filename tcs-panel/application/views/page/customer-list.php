<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Customer List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Customer List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
  <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"></i> Search</h3>
        </div>
       <div class="box-body"> 
            <form action="" method="post" id="frm">
            <div class="row">  
                <div class="col-sm-3 col-md-4"> 
                    <label for="srch_state">State</label>
                    <?php echo form_dropdown('srch_state',array('' => 'All') + $state_opt,set_value('srch_state',$srch_state) ,' id="srch_state" class="form-control"');?>
                </div>
                <div class="col-sm-3 col-md-4">
                    <label>Search pincode ,name , phone , mobile or email</label>
                    <input type="text" class="form-control" name="srch_key" id="srch_key" value="<?php echo set_value('srch_key','') ?>" placeholder="Search pincode ,name , phone , mobile or email" />
                </div>
                <div class="col-sm-3 col-md-2"> 
                <br />
                    <button class="btn btn-info" type="submit">Show</button>
                </div>
            </div>
            </form> 
       </div> 
    </div> 
  <div class="box box-success">
    <div class="box-header with-border">
      <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add New </button>
        
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body table-responsive"> 
       <table class="table table-hover table-bordered table-striped table-responsive">
        <thead> 
            <tr>
                <th>#</th> 
                <th>Type</th>  
                <th>Company</th>  
                <th>Contact Person</th>  
                <th>Mobile,Phone & Email</th> 
                <th>Address</th>  
                <th>State & City</th> 
                <th>Status</th>  
                <th colspan="2" class="text-center">Group</th>  
                <th>Rate</th>  
                <th colspan="2" class="text-center">Action</th>  
            </tr> 
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td>  
                    <td><?php echo $ls['customer_type_name']?><br /><i class="badge bg-black"><?php echo $ls['franchise']?></i></td>  
                    <td><?php echo $ls['company']?><br /><span class="label label-info"><?php echo $ls['customer_code']?></span></td>  
                    <td><?php echo $ls['contact_person']?></td>  
                    <td><?php echo '<i class="fa fa-mobile"></i>  '.($ls['mobile']);?><br /><?php echo '<i class="fa fa-phone"></i>  '.($ls['phone']);?><br /><?php echo '<i class="fa fa-envelope"></i> ' . $ls['email']?></td>   
                    <td><?php echo $ls['address']?></td>   
                    <td><?php echo $ls['state_code']?><br /><?php echo $ls['city_code']?></td> 
                    <td><?php echo $ls['status']?></td> 
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#add_group_modal" value="<?php echo $ls['customer_id']?>" class="btn btn-success btn-xs btn_contact" title="Add Group"><i class="fa fa-plus-circle"></i></button>
                    </td> 
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#view_modal" value="<?php echo $ls['customer_id']?>" class="view_record btn btn-warning btn-xs" title="View"><i class="fa fa-eye"></i></button>
                    </td>
                    <td class="text-center">
                        <a href="<?php echo site_url('customer-domestic-rate-v2/').$ls['customer_id']?>" class="rate_record btn btn-default btn-sm" title="Rate"><i class="fa fa-rupee"></i> Rate</button>
                    </td>  
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['customer_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td> 
                                                      
                    <td class="text-center">
                        <button value="<?php echo $ls['customer_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                    </td>                                      
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
      </table>
        
        <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmadd">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>Add Customer Info</strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Add" />
                    </div>
                    <div class="modal-body">
                                                 
                         <div class="row"> 
                             <div class="form-group col-md-4">
                                <label>Customer Type</label>
                                <?php echo form_dropdown('customer_type_id',array('' => 'Select') + $customer_type_opt,set_value('customer_type_id','') ,' id="customer_type_id" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Company</label>
                                <input class="form-control" type="text" name="company" id="company" value="" placeholder="Company Name">                                             
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Contact Person Name</label>
                                <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Contact Person Name">                                             
                             </div>  
                         </div> 
                         <div class="row">  
                            <div class="form-group col-md-2">
                                <label>Customer Code</label>
                                <input class="form-control" type="text" name="customer_code" id="customer_code" value="" placeholder="Customer Code">                                             
                             </div>
                            <div class="form-group col-md-3">
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                             </div> 
                             <div class="form-group col-md-3">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
                             </div>  
                         </div> 
                         <div class="row">  
                            <div class="form-group col-md-6">
                                <label>GST Number</label>
                                <input class="form-control" type="text" name="gst_no" id="gst_no" value="" placeholder="GST">                                             
                             </div>
                            <div class="form-group col-md-6">
                                <label>Aadhar Number</label>
                                <input class="form-control" type="text" name="aadhar_no" id="aadhar_no" value="" placeholder="Aadhar Number">                                             
                             </div>  
                         </div> 
                         <div class="row"> 
                             
                             <div class="form-group col-md-4">
                                <label>State</label>
                                <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code',$srch_state) ,' id="state_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>City</label>
                                <?php echo form_dropdown('city_code',array('' => 'Select') ,set_value('city_code') ,' id="city_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Pincode</label>
                                <input class="form-control" type="text" name="pincode" id="pincode" value="" placeholder="Pincode">                                             
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-12">
                                <label>Full Address</label>
                                <textarea class="form-control" name="address" placeholder="Address" id="address"></textarea>                                             
                             </div>  
                         </div>  
                         <div class="row">
                             <div class="form-group col-md-4">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status"  value="Active" checked="true" /> Active 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="status"  value="InActive"  /> InActive
                                    </label>
                                </div> 
                             </div>
                             <div class="form-group col-md-4">
                                <label>Franchise Type</label>
                                <?php echo form_dropdown('franchise_type_id',array('' => 'Select') + $franchise_type_opt,set_value('franchise_type_id') ,' id="franchise_type_id" class="form-control"');?> 
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Referral Franchise</label>
                                <?php echo form_dropdown('franchise_id',array('' => 'Select'),set_value('franchise_id') ,' id="franchise_id" class="form-control"');?> 
                             </div> 
                          </div>   
                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                        <input type="submit" name="Save" value="Save"  class="btn btn-primary" />
                    </div> 
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmedit">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scrollmodalLabel"><strong>Edit Customer</strong> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="customer_id" id="customer_id" />
                    </div>
                    <div class="modal-body"> 
                         <div class="row"> 
                             <div class="form-group col-md-4">
                                <label>Customer Type</label>
                                <?php echo form_dropdown('customer_type_id',array('' => 'Select') + $customer_type_opt,set_value('customer_type_id') ,' id="customer_type_id" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Company</label>
                                <input class="form-control" type="text" name="company" id="company" value="" placeholder="Company Name">                                             
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Contact Person Name</label>
                                <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Contact Person Name">                                             
                             </div>  
                         </div> 
                         <div class="row">  
                            <div class="form-group col-md-2">
                                <label>Customer Code</label>
                                <input class="form-control" type="text" name="customer_code" id="customer_code" value="" placeholder="Customer Code">                                             
                             </div>
                            <div class="form-group col-md-3">
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                             </div> 
                             <div class="form-group col-md-3">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
                             </div>  
                         </div> 
                         <div class="row">  
                            <div class="form-group col-md-6">
                                <label>GST Number</label>
                                <input class="form-control" type="text" name="gst_no" id="gst_no" value="" placeholder="GST">                                             
                             </div>
                            <div class="form-group col-md-6">
                                <label>Aadhar Number</label>
                                <input class="form-control" type="text" name="aadhar_no" id="aadhar_no" value="" placeholder="Aadhar Number">                                             
                             </div>  
                         </div> 
                         <div class="row"> 
                             
                             <div class="form-group col-md-4">
                                <label>State</label>
                                <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code',$srch_state) ,' id="state_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>City</label>
                                <?php echo form_dropdown('city_code',array('' => 'Select') ,set_value('city_code') ,' id="city_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Pincode</label>
                                <input class="form-control" type="text" name="pincode" id="pincode" value="" placeholder="Pincode">                                             
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-12">
                                <label>Full Address</label>
                                <textarea class="form-control" name="address" placeholder="Address" id="address"></textarea>                                             
                             </div>  
                         </div>  
                         <div class="row">
                             <div class="form-group col-md-4">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status"  value="Active" checked="true" /> Active 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="status"  value="InActive"  /> InActive
                                    </label>
                                </div> 
                             </div>
                             <div class="form-group col-md-4">
                                <label>Franchise Type</label>
                                <?php echo form_dropdown('franchise_type_id',array('' => 'Select') + $franchise_type_opt,set_value('franchise_type_id') ,' id="franchise_type_id" class="form-control"');?> 
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Referral Franchise</label>
                                <?php echo form_dropdown('franchise_id',array('' => 'Select'),set_value('franchise_id') ,' id="franchise_id" class="form-control"');?> 
                             </div> 
                          </div>
                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                        <input type="submit" name="Save" value="Update"  class="btn btn-primary" />
                    </div> 
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="add_group_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmadd">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>Add Customer Contact Group Info</strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Add Contact Group" />
                        <input type="hidden" name="customer_id" id="customer_id" value="" />
                    </div>
                    <div class="modal-body">
                                                 
                         <div class="row"> 
                             <div class="form-group col-md-4">
                                <label>Customer Group</label>
                                <?php echo form_dropdown('customer_group',array('' => 'Select') + $customer_group_opt,set_value('customer_group','') ,' id="customer_group" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Company</label>
                                <input class="form-control" type="text" name="company" id="company" value="" placeholder="Company Name">                                             
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Contact Person Name</label>
                                <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Contact Person Name">                                             
                             </div>  
                         </div> 
                         <div class="row">  
                            <div class="form-group col-md-2">
                                <label>CGroup Code</label>
                                <input class="form-control" type="text" name="cc_code" id="cc_code" value="" placeholder="Contact Group Code">                                             
                             </div>
                            <div class="form-group col-md-3">
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                             </div> 
                             <div class="form-group col-md-3">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
                             </div>  
                         </div> 
                         <div class="row">  
                            <div class="form-group col-md-6">
                                <label>GST Number</label>
                                <input class="form-control" type="text" name="gst_no" id="gst_no" value="" placeholder="GST">                                             
                             </div>
                            <div class="form-group col-md-6">
                                <label>Aadhar Number</label>
                                <input class="form-control" type="text" name="aadhar_no" id="aadhar_no" value="" placeholder="Aadhar Number">                                             
                             </div>  
                         </div> 
                         <div class="row"> 
                             
                             <div class="form-group col-md-4">
                                <label>State</label>
                                <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code',$srch_state) ,' id="state_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>City</label>
                                <?php echo form_dropdown('city_code',array('' => 'Select') ,set_value('city_code') ,' id="city_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Pincode</label>
                                <input class="form-control" type="text" name="pincode" id="pincode" value="" placeholder="Pincode">                                             
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-8">
                                <label>Full Address</label>
                                <textarea class="form-control" name="address" placeholder="Address" id="address"></textarea>                                             
                             </div>  
                         
                             <div class="form-group col-md-4">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status"  value="Active" checked="true" /> Active 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="status"  value="InActive"  /> InActive
                                    </label>
                                </div> 
                             </div>
                          </div>   
                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                        <input type="submit" name="Save" value="Save"  class="btn btn-primary" />
                    </div> 
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="edit_group_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmadd">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>Edit Customer Contact Group Info</strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Edit Contact Group" />
                        <input type="hidden" name="customer_contact_id" id="customer_contact_id" value="" />
                    </div>
                    <div class="modal-body">    
                         <div class="row"> 
                             <div class="form-group col-md-4">
                                <label>Customer Group</label>
                                <?php echo form_dropdown('customer_group',array('' => 'Select') + $customer_group_opt,set_value('customer_group','') ,' id="customer_group" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Company</label>
                                <input class="form-control" type="text" name="company" id="company" value="" placeholder="Company Name">                                             
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Contact Person Name</label>
                                <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Contact Person Name">                                             
                             </div>  
                         </div> 
                         <div class="row">  
                            <div class="form-group col-md-2">
                                <label>CGroup Code</label>
                                <input class="form-control" type="text" name="cc_code" id="cc_code" value="" placeholder="Contact Group Code">                                             
                             </div>
                            <div class="form-group col-md-3">
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                             </div> 
                             <div class="form-group col-md-3">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
                             </div>  
                         </div> 
                         <div class="row">  
                            <div class="form-group col-md-6">
                                <label>GST Number</label>
                                <input class="form-control" type="text" name="gst_no" id="gst_no" value="" placeholder="GST">                                             
                             </div>
                            <div class="form-group col-md-6">
                                <label>Aadhar Number</label>
                                <input class="form-control" type="text" name="aadhar_no" id="aadhar_no" value="" placeholder="Aadhar Number">                                             
                             </div>  
                         </div> 
                         <div class="row"> 
                             
                             <div class="form-group col-md-4">
                                <label>State</label>
                                <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code',$srch_state) ,' id="state_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>City</label>
                                <?php echo form_dropdown('city_code',array('' => 'Select') ,set_value('city_code') ,' id="city_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Pincode</label>
                                <input class="form-control" type="text" name="pincode" id="pincode" value="" placeholder="Pincode">                                             
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-8">
                                <label>Full Address</label>
                                <textarea class="form-control" name="address" placeholder="Address" id="address"></textarea>                                             
                             </div>  
                         
                             <div class="form-group col-md-4">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="status"  value="Active" checked="true" /> Active 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="status"  value="InActive"  /> InActive
                                    </label>
                                </div> 
                             </div>
                          </div>   
                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                        <input type="submit" name="Save" value="Save"  class="btn btn-primary" />
                    </div> 
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="view_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content"> 
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>View Details</strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> 
                    </div>
                    <div class="modal-body table-responsive">
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
                    </div>  
                </div>
            </div>
        </div> 
        
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="form-group col-sm-6">
            <label>Total Records : <?php echo $total_records;?></label>
        </div>
        <div class="form-group col-sm-6">
            <?php echo $pagination; ?>
        </div>
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
