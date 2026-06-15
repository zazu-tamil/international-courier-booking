<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     Master Pincode List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Pincode List</li>
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
            <form action="<?php echo site_url('pincode-list'); ?>" method="post" id="frm">
            <div class="row">
                <div class="col-sm-4 col-md-4"> 
                    <label for="srch_state">State</label>
                    <?php echo form_dropdown('srch_state',array('' => 'Select') + $state_info,set_value('srch_state',$srch_state) ,' id="srch_state" class="form-control"');?>
                </div>
                <div class="col-sm-4 col-md-4">
                    <label>District,Area or Pincode</label>
                    <input type="text" class="form-control" name="srch_area" id="srch_area" value="<?php echo set_value('srch_area', $srch_area) ?>" placeholder="Search District,Area or Pincode" />
                </div>
                <div class="col-sm-4 col-md-4"> 
                <br />
                    <button class="btn btn-info" type="submit">Show</button>
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
       
       <table class="table table-hover table-bordered table-striped">
        <thead> 
            <tr>
                <th>#</th>
                <th>Pincode</th>  
                <th>Area</th>  
                <th>District</th>  
                <th>State</th>  
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
                    <td><?php echo $ls['pincode']?></td>   
                    <td><?php echo $ls['area']?></td>  
                    <td><?php echo ($ls['district_name']);?></td>   
                    <td><?php echo $ls['state_name']?></td>   
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
                        <h3 class="modal-title" id="scrollmodalLabel">Add Master Pincode</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Add" />
                    </div>
                    <div class="modal-body">
                         <div class="form-group">
                            <label>Pincode</label>
                            <input class="form-control" type="text" name="pincode" id="pincode" value="">                                             
                         </div> 
                         <div class="form-group">
                            <label>Area</label>
                            <input class="form-control" type="text" name="area_name" id="area_name" value="">                                             
                         </div> 
                         <div class="form-group">
                            <label>District</label>
                            <input class="form-control" type="text" name="district_name" id="district_name" value="">                                             
                         </div> 
                         <div class="form-group">
                            <label>State</label>
                             <input class="form-control" type="text" name="state_name" id="state_name" value="<?php echo set_value('state_code' , $srch_state);?>">
                             <?php //echo form_dropdown('state_code', array('' => 'Select the State') + $state_info,set_value('state_code' , $srch_state) ,' id="state_code" class="form-control"');?>                                             
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
                        <h5 class="modal-title" id="scrollmodalLabel">Edit Master Pincode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Edit" />
                        <input type="hidden" name="pincode_id" id="pincode_id" />
                    </div>
                    <div class="modal-body"> 
                         <div class="form-group">
                            <label>Pincode</label>
                            <input class="form-control" type="text" name="pincode" id="pincode" value="">                                             
                         </div> 
                        <div class="form-group">
                            <label>Area</label>
                            <input class="form-control" type="text" name="area_name" id="area_name" value="">                                             
                         </div> 
                         <div class="form-group">
                            <label>District</label>
                            <input class="form-control" type="text" name="district_name" id="district_name" value="">                                             
                         </div> 
                         <div class="form-group">
                            <label>State</label>
                             <input class="form-control" type="text" name="state_name" id="state_name" value="">
                             <?php //echo form_dropdown('state_code', array('' => 'Select the State') + $state_info,set_value('state_code' , $srch_state) ,' id="state_code" class="form-control"');?>                                             
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
