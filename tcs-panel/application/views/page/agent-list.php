<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Agent /Employee List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Agent List</li>
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
                <th>Agent/Employee Name</th>  
                <th>Mobile,Phone & Email</th> 
                <th>Address</th>  
                <th>State & City</th>  
                <th>Servicable Pincode</th>   
                <th>Status</th>  
                <th colspan="2" class="text-center">Action</th>  
            </tr> 
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td>  
                    <td><?php echo $ls['contact_person']?><br /><i class="badge"><?php echo $ls['franchise']?></i></td>  
                    <td><?php echo '<i class="fa fa-mobile"></i>  '.($ls['mobile']);?><br /><?php echo '<i class="fa fa-phone"></i>  '.($ls['phone']);?><br /><?php echo '<i class="fa fa-envelope"></i> ' . $ls['email']?></td>   
                    <td><?php echo $ls['address']?></td>   
                    <td><?php echo $ls['state_code']?><br /><?php echo $ls['city_code']?></td>   
                    <td><?php echo $ls['servicable_pincode']?></td>  
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['agent_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <button value="<?php echo $ls['agent_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
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
                        <h3 class="modal-title" id="scrollmodalLabel"><strong>Add Agent/Employee</strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Add" />
                    </div>
                    <div class="modal-body">
                         <div class="row"> 
                             <div class="form-group col-md-6">
                                <label>Agent / Employee Name</label>
                                <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Name" required="true">                                             
                             </div> 
                             <div class="form-group col-md-6">
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                             </div> 
                         </div> 
                         <div class="row">  
                             <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                             </div>
                             <div class="form-group col-md-6">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
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
                                <?php echo form_dropdown('servicable_pincode[]',array('' => 'Select')  ,set_value('servicable_pincode','') ,' placeholder="Servicable Pincode" id="servicable_pincode" class="form-control"  multiple="multiple"');?> 
                             </div>  
                         </div>
                         <div class="form-group">
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
                        <h5 class="modal-title" id="scrollmodalLabel"><strong>Edit Agent/Employee</strong> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="agent_id" id="agent_id" />
                    </div>
                    <div class="modal-body"> 
                         <div class="row"> 
                             <div class="form-group col-md-6">
                                <label>Agent / Employee Name</label>
                                <input class="form-control" type="text" name="contact_person" id="contact_person" value="" placeholder="Name">                                             
                             </div> 
                             <div class="form-group col-md-6">
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile">                                             
                             </div> 
                         </div> 
                         <div class="row">  
                             <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input class="form-control" type="text" name="phone" id="phone" value="" placeholder="Phone">                                             
                             </div>
                             <div class="form-group col-md-6">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="" placeholder="Email ID">                                             
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
                                <?php echo form_dropdown('servicable_pincode[]',array('' => 'Select')  ,set_value('servicable_pincode','') ,' placeholder="Servicable Pincode" id="servicable_pincode" class="form-control"  multiple="multiple"');?> 
                             </div>  
                         </div>
                         <div class="form-group">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                        <input type="submit" name="Save" value="Update"  class="btn btn-primary" />
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
