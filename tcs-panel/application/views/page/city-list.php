<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1> City List </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">City List</li>
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
            <form action="" method="post" id="frm">
            <div class="row">
                <div class="col-sm-4 col-md-4"> 
                    <label for="srch_state">State</label>
                    <?php echo form_dropdown('srch_state',array('' => 'Select') + $state_opt,set_value('srch_state',$srch_state) ,' id="srch_state" class="form-control"');?>
                </div>
                <div class="col-sm-4 col-md-4">
                    <label>City or City Code</label>
                    <input type="text" class="form-control" name="srch_key" id="srch_key" value="<?php echo set_value('srch_key', $srch_key) ?>" placeholder="Search City or CityCode" />
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
                <th>S.No</th>
                <th>State Code</th>  
                <th>City Name</th>  
                <th>City Code</th>  
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
                    <td><?php echo $ls['state_code']?></td>   
                    <td><?php echo $ls['city_name']?></td>   
                    <td><?php echo $ls['city_code']?></td>   
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['city_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <button value="<?php echo $ls['city_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
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
                                <h5 class="modal-title" id="scrollmodalLabel">Add City</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <input type="hidden" name="mode" value="Add" />
                            </div>
                            <div class="modal-body">
                                 <div class="form-group">
                                    <label>State</label>
                                    <?php echo form_dropdown('state_code', (array('' => 'Select') + $state_opt), set_value("state_code"),' class="form-control" id="state_code" '); ?>
                                 </div>  
                                 <div class="form-group">
                                    <label>City Name</label>
                                    <input class="form-control" type="text" name="city_name" id="city_name" value="">                                             
                                 </div>
                                 <div class="form-group">
                                    <label>City Code</label>
                                    <input class="form-control" type="text" name="city_code" id="city_code" value="">                                             
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
                                <h5 class="modal-title" id="scrollmodalLabel">Edit City</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <input type="hidden" name="mode" value="Edit" />
                                <input type="hidden" name="city_id" id="city_id" />
                            </div>
                            <div class="modal-body"> 
                                 <div class="form-group">
                                    <label>State</label>
                                    <?php echo form_dropdown('state_code', (array('' => 'Select') + $state_opt), set_value("state_code"),' class="form-control" id="state_code" '); ?>
                                 </div>  
                                 <div class="form-group">
                                    <label>City Name</label>
                                    <input class="form-control" type="text" name="city_name" id="city_name" value="">                                             
                                 </div>
                                 <div class="form-group">
                                    <label>City Code</label>
                                    <input class="form-control" type="text" name="city_code" id="city_code" value="">                                             
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
