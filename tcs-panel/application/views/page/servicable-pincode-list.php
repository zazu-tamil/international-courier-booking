<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     Serviceable Pincode List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Serviceable Pincode List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
  <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"></i> Search</h3>
        </div>
       <div class="box-body"> 
            <form action="<?php echo site_url('servicable-pincode-list'); ?>" method="post" id="frm">
            <div class="row"> 
                <div class="col-sm-4 col-md-3"> 
                    <label for="srch_state">State</label>
                    <?php echo form_dropdown('srch_state',array('' => 'All State') + $state_opt,set_value('srch_state',$srch_state) ,' id="srch_state" class="form-control"');?>
                </div>
                <div class="col-sm-4 col-md-3">
                    <label>State,Area or Pincode</label>
                    <input type="text" class="form-control" name="srch_key" id="srch_key" value="<?php echo set_value('srch_key', $srch_key) ?>" placeholder="Search State ,Area or Pincode" />
                </div>
                <div class="col-sm-2 col-md-2"> 
                    <label>Type</label>
                    <?php echo form_dropdown('srch_serve_type',array('' => 'All Type' , 'Serviceable' => 'Serviceable', 'ODA' => 'ODA', 'Extended' => 'Extended' ) ,set_value('srch_serve_type',$srch_serve_type) ,' id="srch_serve_type" class="form-control"');?>
                </div>
                <div class="col-sm-2 col-md-2"> 
                <br />
                    <button class="btn btn-info" type="submit">Show</button>
                </div>
                <div class="col-md-2 text-right">
                    <form method="post" action="">
                    <div class="input-group ">
                        <span class="form-group">
                        <button class="btn btn-success " type="submit" name="export" value="xls"><i class="fa fa-download"></i> Export Excel</button>&nbsp;
                        </span> 
                    </div>
                    </form>
                </div>
            </div>
            </form> 
       </div> 
    </div> 
  <div class="box">
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
                <th>ID</th>  
                <th>Pincode</th>  
                <th>Area</th>  
                <th>Premium</th>  
                <th>Business</th>  
                <th>State</th>  
                <th>Zone</th>  
                <th>Branch</th>  
                <th>Is Metro</th>  
                <th>OPS</th>  
                <th>Service By</th>  
                <th>Status</th>  
                <th colspan="2" class="text-center">Action</th>  
            </tr> 
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                    if($ls['serve_type'] != 'Serviceable') $cls = 'text-red'; else $cls ='';
                ?> 
                <tr class="<?php echo $cls;?>"> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo $ls['pincode_id']?></td>   
                    <td><?php echo $ls['pincode']; if($ls['serve_type'] != 'Serviceable') echo "<br><i class='badge'>" . $ls['serve_type'].'</i>'; else echo "<br><i class='badge'>" . $ls['serve_type'].'</i>'; ?></td>   
                    <td><?php echo $ls['area']?></td>  
                    <td><?php echo ($ls['premium_express']);?></td>   
                    <td><?php echo $ls['business_express']?></td>   
                    <td><?php echo $ls['state']?></td>   
                    <td><?php echo $ls['zone']?></td>   
                    <td><?php echo $ls['city']?></td> 
                    <td><?php echo $ls['metro_city']?></td>  
                    <td><?php echo $ls['ops_by']?></td>   
                    <td><?php echo $ls['service_by']?></td>  
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['pincode_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <button value="<?php echo $ls['pincode_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
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
                        <h3 class="modal-title" id="scrollmodalLabel">Add Serviceable Pincode</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Add" />
                    </div>
                    <div class="modal-body">
                         <div class="row">
                         <div class="form-group col-md-6">
                            <label>Pincode</label>
                            <input class="form-control" type="text" name="pincode" id="pincode" value="">                                             
                         </div> 
                         <div class="form-group col-md-6">
                            <label>Area</label>
                            <input class="form-control" type="text" name="area" id="area" value="">                                             
                         </div> 
                         </div> 
                         <div class="row">
                         <div class="form-group col-md-4">
                            <label>Premium Express</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="premium_express"  value="Y" checked="true" /> Yes 
                                </label> 
                            </div>
                            <div class="radio">
                                <label>
                                     <input type="radio" name="premium_express"  value="N"  /> No
                                </label>
                            </div>                                             
                         </div> 
                         <div class="form-group col-md-4">
                            <label>Business Express</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="business_express"  value="Y" checked="true" /> Yes 
                                </label> 
                            </div>
                            <div class="radio">
                                <label>
                                     <input type="radio" name="business_express"  value="N"  /> No
                                </label>
                            </div>                                              
                         </div>
                         <div class="form-group col-md-4">
                            <label>TYPE</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="serve_type"  value="Serviceable" checked="true" /> Serviceable 
                                </label> 
                            </div>
                            <div class="radio">
                                <label>
                                     <input type="radio" name="serve_type"  value="ODA"  /> ODA Location
                                </label>
                            </div> 
                            <div class="radio">
                                <label>
                                     <input type="radio" name="serve_type"  value="Extended"  /> Extended Location
                                </label>
                            </div> 
                         </div> 
                         </div> 
                         <div class="row">
                             <div class="form-group col-md-6">
                                <label>State Code</label>
                                <?php echo form_dropdown('state_code',array('' => 'Select State') + $state_opt,set_value('state_code') ,' id="state_code" class="form-control"');?>                                           
                             </div> 
                             <div class="form-group col-md-6">
                                <label>Branch Code[ City ]</label>
                                <?php echo form_dropdown('branch_code',array('' => 'Select') ,set_value('branch_code') ,' id="branch_code" class="form-control"');?>                                          
                             </div> 
                         </div>
                         <div class="row"> 
                             <div class="form-group col-md-6">
                                <label>Zone</label>
                                 <?php echo form_dropdown('zone',array('' => 'Select Zone' , 'N' => 'North' , 'NE' => 'North East' , 'E' => 'East', 'W' => 'West','S' => 'South') ,set_value('zone') ,' id="zone" class="form-control"');?>
                                                                            
                             </div> 
                             <div class="form-group col-md-6">
                                <label>OPS By</label>  
                                <?php echo form_dropdown('ops_by',array('' => 'Select') ,set_value('ops_by') ,' id="ops_by" class="form-control"');?>                                            
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-4">
                                <label>Service By</label>
                                <input class="form-control" type="text" name="service_by" id="service_by" value="ECPL">                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Is Metro City</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="metro_city"  value="Y" checked="true" /> Yes 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="metro_city"  value="N"  /> No
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
                        <h5 class="modal-title" id="scrollmodalLabel">Edit Servicable Pincode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="pincode_id" id="pincode_id" />
                    </div>
                    <div class="modal-body"> 
                         <div class="row">
                         <div class="form-group col-md-6">
                            <label>Pincode</label>
                            <input class="form-control" type="text" name="pincode" id="pincode" value="">                                             
                         </div> 
                         <div class="form-group col-md-6">
                            <label>Area</label>
                            <input class="form-control" type="text" name="area" id="area" value="">                                             
                         </div> 
                         </div> 
                         <div class="row">
                             <div class="form-group col-md-4">
                                <label>Premium Express</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="premium_express1" name="premium_express"  value="Y" checked="true"  /> Yes 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" id="premium_express2" name="premium_express"  value="N"  /> No
                                    </label>
                                </div>                                             
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Business Express</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" class="business_express" name="business_express"  value="Y" checked="true" /> Yes 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" class="business_express" name="business_express"  value="N"  /> No
                                    </label>
                                </div>                                              
                             </div> 
                             <div class="form-group col-md-4">
                                <label>TYPE</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="serve_type"  value="Serviceable" checked="true" /> Serviceable 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="serve_type"  value="ODA"  /> ODA Location
                                    </label>
                                </div> 
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="serve_type"  value="Extended"  /> Extended Location
                                    </label>
                                </div>
                             </div> 
                         </div> 
                         <div class="row">
                             <div class="form-group col-md-6">
                                <label>State Code</label>
                                <?php echo form_dropdown('state_code',array('' => 'Select State') + $state_opt,set_value('state_code') ,' id="state_code" class="form-control"');?>                                           
                             </div> 
                             <div class="form-group col-md-6">
                                <label>Branch Code[ City ]</label>
                                <?php echo form_dropdown('branch_code',array('' => 'Select') ,set_value('branch_code') ,' id="branch_code" class="form-control"');?>                                          
                             </div> 
                         </div>
                         <div class="row"> 
                             <div class="form-group col-md-6">
                                <label>Zone</label>
                                 <?php echo form_dropdown('zone',array('' => 'Select Zone' , 'N' => 'North' , 'NE' => 'North East' , 'E' => 'East', 'W' => 'West','S' => 'South') ,set_value('zone') ,' id="zone" class="form-control"');?>
                                                                            
                             </div> 
                             <div class="form-group col-md-6">
                                <label>OPS By</label>  
                                <?php echo form_dropdown('ops_by',array('' => 'Select') ,set_value('ops_by') ,' id="ops_by" class="form-control"');?>                                            
                             </div>
                         </div>
                         <div class="row">
                             <div class="form-group col-md-4">
                                <label>Service By</label>
                                <input class="form-control" type="text" name="service_by" id="service_by" value="ECPL">                                             
                             </div>
                             <div class="form-group col-md-4">
                                <label>Is Metro City</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="metro_city"  value="Y" checked="true" /> Yes 
                                    </label> 
                                </div>
                                <div class="radio">
                                    <label>
                                         <input type="radio" name="metro_city"  value="N"  /> No
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
