<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Franchises List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Franchises List</li>
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
            <form action="<?php echo site_url('franchise-list');?>" method="post" id="frm">
            <div class="row"> 
                <div class="col-sm-3 col-md-3"> 
                    <label for="srch_franchise">Franchise Type</label>
                    <?php echo form_dropdown('srch_franchise',array('' => 'All') + $franchise_type_opt,set_value('srch_franchise',$srch_franchise) ,' id="srch_franchise" class="form-control"');?>
                </div>
                <div class="col-sm-3 col-md-3"> 
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
    <div class="box-body table-responsive no-padding"> 
       <table class="table table-hover table-bordered table-striped table-responsive">
        <thead> 
            <tr>
                <th>#</th>
                <th>Franchise Type</th>  
                <th>Contact Person</th>  
                <th>Mobile,Phone & Email</th> 
                <th>Address</th>  
                <th>State & City</th>  
                <th>Servicable Pincode</th>   
                <th>Status</th>  
                <th>User<br />Login</th>  
                <th colspan="2" class="text-center">Action</th>  
            </tr> 
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo $ls['franchise_type']?></td>   
                    <td><?php echo $ls['contact_person']?><br /><i class="badge"><?php echo $ls['branch_code']?></i></td>  
                    <td><?php echo '<i class="fa fa-mobile"></i>  '.($ls['mobile']);?><br /><?php echo '<i class="fa fa-phone"></i>  '.($ls['phone']);?><br /><?php echo '<i class="fa fa-envelope"></i> ' . $ls['email']?></td>   
                    <td><?php echo $ls['address']?></td>   
                    <td><?php echo $ls['state_code']?><br /><?php echo $ls['city_code']?></td>   
                    <td><?php echo str_replace(',',',<br>', $ls['servicable_pincode'])?></td>  
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                       <button data-toggle="modal" data-target="#user_modal" value="<?php echo $ls['franchise_id']?>"  class="user_record btn btn-success btn-xs"><i class="fa fa-users"></i></button> 
                    </td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['franchise_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <button value="<?php echo $ls['franchise_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
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
                        <h3 class="modal-title" id="scrollmodalLabel">Add Franchise</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Add" />
                    </div>
                    <div class="modal-body">
                         <div class="row">
                             <div class="form-group col-md-6">
                                <label>Franchise Type</label>
                                <?php echo form_dropdown('franchise_type_id',array('' => 'Select') + $franchise_type_opt,set_value('franchise_type_id') ,' id="franchise_type_id" class="form-control"');?> 
                             </div> 
                             <div class="form-group col-md-6">
                                <label>Contact Person</label>
                                <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Contact Person Name">                                             
                             </div>  
                         </div> 
                         <div class="row"> 
                             <div class="form-group col-md-6">
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                             </div> 
                             <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                             </div> 
                         </div> 
                         <div class="row">
                             <div class="form-group col-md-6">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
                             </div> 
                             <div class="form-group col-md-6">
                                <label>GSTIN</label>
                                <input class="form-control" type="text" name="gst_no" id="gst_no" value="" placeholder="GSTIN">                                             
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-12">
                                <label>Address</label>
                                <textarea class="form-control" name="address" placeholder="Address" id="address"></textarea>                                             
                             </div>  
                         </div> 
                         <div class="row"> 
                             <div class="form-group col-md-6">
                                <label>State</label>
                                <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code',$srch_state) ,' id="state_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-6">
                                <label>City</label>
                                <?php echo form_dropdown('city_code',array('' => 'Select') ,set_value('city_code') ,' id="city_code" class="form-control"');?>                                             
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-12">
                                <label>Servicable Pincode</label> 
                                <?php echo form_dropdown('servicable_pincode[]',array('' => 'Select')  ,set_value('servicable_pincode','') ,' placeholder="Servicable Pincode" id="servicable_pincode" class="form-control select2" style="width: 100%;"  multiple="multiple"');?>
                                <br />
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox"  id="checkbox"> Select All Pincodes
                                  </label>
                                </div>
                             </div>  
                         </div>
                         <div class="row">
                             <div class="form-group col-md-6">
                                <label>Branch Code</label>
                                <?php echo form_dropdown('branch_code',array('' => 'Select') + $branch_code_opt,set_value('branch_code') ,' id="branch_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-6">
                                <label>HUB Code</label>
                                <?php echo form_dropdown('hub_code',array('' => 'Select') + $hub_code_opt,set_value('hub_code',$srch_state) ,' id="hub_code" class="form-control" ');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Tracking Entry Rights</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="tracking_upd_rights"  value="1" checked="true" /> Enable 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="tracking_upd_rights"  value="0"  /> Disable
                                    </label>
                                </div> 
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
                        <h5 class="modal-title" id="scrollmodalLabel">Edit Franchise </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="franchise_id" id="franchise_id" />
                    </div>
                    <div class="modal-body"> 
                         <div class="row">
                             <div class="form-group col-md-6">
                                <label>Franchise Type</label>
                                <?php echo form_dropdown('franchise_type_id',array('' => 'Select') + $franchise_type_opt,set_value('franchise_type_id') ,' id="franchise_type_id" class="form-control"');?> 
                             </div> 
                             <div class="form-group col-md-6">
                                <label>Contact Person</label>
                                <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Contact Person Name">                                             
                             </div>  
                         </div> 
                         <div class="row"> 
                             <div class="form-group col-md-6">
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                             </div> 
                             <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                             </div> 
                         </div> 
                         <div class="row">
                             <div class="form-group col-md-6">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
                             </div> 
                             <div class="form-group col-md-6">
                                <label>GSTIN</label>
                                <input class="form-control" type="text" name="gst_no" id="gst_no" value="" placeholder="GSTIN">                                             
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-12">
                                <label>Address</label>
                                <textarea class="form-control" name="address" placeholder="Address" id="address"></textarea>                                             
                             </div>  
                         </div> 
                         <div class="row"> 
                             <div class="form-group col-md-6">
                                <label>State</label>
                                <?php echo form_dropdown('state_code',array('' => 'Select') + $state_opt,set_value('state_code',$srch_state) ,' id="state_code" class="form-control"');?>                                             
                             </div>
                             <div class="form-group col-md-6">
                                <label>City</label>
                                <?php echo form_dropdown('city_code',array('' => 'Select') ,set_value('city_code') ,' id="city_code" class="form-control"');?>                                             
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-12">
                                <label>Servicable Pincode</label> 
                                <?php echo form_dropdown('servicable_pincode[]',array('' => 'Select')  ,set_value('servicable_pincode','') ,' placeholder="Servicable Pincode" id="servicable_pincode" class="form-control select2" style="width: 100%;"   multiple="multiple"');?> 
                                <br />
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox"  id="checkbox"> Select All Pincodes
                                  </label>
                                </div>  
                             </div>  
                         </div>
                         <div class="row">
                             <div class="form-group col-md-6">
                                <label>Branch Code</label>
                                <?php echo form_dropdown('branch_code',array('' => 'Select') + $branch_code_opt,set_value('branch_code') ,' id="branch_code" class="form-control"');?>
                              </div>
                             <div class="form-group col-md-6">
                                <label>HUB Code</label>
                                <?php echo form_dropdown('hub_code',array('' => 'Select') + $hub_code_opt,set_value('hub_code',$srch_state) ,' id="hub_code" class="form-control" ');?>                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Tracking Entry Rights</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="tracking_upd_rights"  value="1" /> Enable 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="tracking_upd_rights"  value="0"  /> Disable
                                    </label>
                                </div> 
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                        <input type="submit" name="Save" value="Update"  class="btn btn-primary" />
                    </div> 
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="user_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content"> 
                    <form method="post" action="" id="frmuser">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>User Login Details</strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> 
                        <input type="hidden" name="mode" id="mode" value="Add User" readonly="true" />
                        <input type="hidden" name="franchise_id" id="franchise_id" value="" />
                        <input type="hidden" name="user_id" id="user_id" value="" />
                    </div>
                    <div class="modal-body table-responsive">
                      <div class="box box-info">
                        <div class="box-body"> 
                            <div class="row">  
                                 <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input class="form-control" type="text" name="first_name" id="first_name" value="" placeholder="Name">                                             
                                 </div> 
                                 <div class="form-group col-md-4">
                                    <label>User Name</label>
                                    <input class="form-control" type="text" name="user_name" id="user_name" value="" placeholder="User Name">                                             
                                 </div> 
                                 <div class="form-group col-md-4">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="pwd" id="pwd" value="" placeholder="Password">                                             
                                 </div>  
                             </div> 
                             <div class="row"> 
                                 <div class="form-group col-md-6">
                                    <label>Mobile</label>
                                    <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>Phone</label>
                                    <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                                 </div> 
                                  
                             </div> 
                             <div class="row">
                                 <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
                                 </div>
                                 <div class="form-group col-md-6">
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
                         <div class="box-footer text-center">
                            <button class="btn btn-info" type="reset">Reset</button>
                            <button class="btn btn-success" type="submit">Save</button>
                         </div>
                       </div>
                       
                       <div class="box box-warning">
                         <div class="box-header"><h4 class="heading">User List</h4></div>
                         <div class="box-body user_list"> 
                            
                         </div>
                       </div>  
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
                    </div> 
                    </form> 
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
