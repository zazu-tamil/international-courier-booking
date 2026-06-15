<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     Line Haul List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Line Haul</a></li> 
    <li class="active">Line Haul List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
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
                 <div class="form-group col-md-3">
                    <label>Courier Service </label> 
                        <?php echo form_dropdown('srch_co_loader_id',array('' => 'All') + $co_loader_opt  ,set_value('srch_co_loader_id','') ,' id="srch_co_loader_id" class="form-control"');?> 
                  </div>  
                 
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show Reports'"><i class="fa fa-search"></i> Show</button>
                </div>
             </div>  
            </form>
         </div> 
         </div> 
  <?php if(($submit_flg)) { ?> 
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
    <div class="box-body table-responsive">
        
       <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Courier Service</th>  
                <th>Date</th>  
                <th>Origin</th>  
                <th>Destination</th>  
                <th>Co-Loader</th>  
                <th>No of Box</th>  
                <th>Mode</th>  
                <th>V.Weight</th>  
                <th>Weight</th>  
                <th>Status</th>  
                <th colspan="3" class="text-center">Action</th>  
            </tr>
        </thead>
          <tbody>
               <?php foreach($record_list as $j=> $ls){ ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo $ls['co_loader_name']?></td>   
                    <td><?php echo $ls['booking_date']?></td>   
                    <td><?php echo $ls['origin']?></td>   
                    <td><?php echo $ls['destination']?></td>   
                    <td><?php echo $ls['service']?></td>   
                    <td><?php echo $ls['no_of_box']?></td>   
                    <td><?php echo $ls['v_mode']?></td>   
                    <td><?php echo $ls['volumetric_weight']?></td>   
                    <td><?php echo $ls['total_weight']?></td>   
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#upload_modal" value="<?php echo $ls['line_haul_id']?>" class="upload_record btn btn-success btn-xs" title="Upload"><i class="fa fa-upload"></i></button>
                    </td>
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['line_haul_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td> 
                                                     
                    <td class="text-center">
                        <button value="<?php echo $ls['line_haul_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                    </td>                                      
                </tr>
                <?php } ?>                                 
            </tbody>
      </table>
         
        <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmadd" enctype="multipart/form-data">
                            <div class="modal-header">
                                
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h3 class="modal-title" id="scrollmodalLabel">Add Line Haul Entry</h3>                                
                                <input type="hidden" name="mode" value="Add" />
                            </div>
                            <div class="modal-body">
                                 <div class="row">
                                     <div class="form-group col-md-4">
                                        <label>Courier Service</label> 
                                            <?php echo form_dropdown('co_loader_id',array('' => 'Select') + $co_loader_opt  ,set_value('co_loader_id','') ,' id="co_loader_id" class="form-control"');?> 
                                      </div>  
                                     <div class="form-group col-md-4">
                                        <label>Booking Date</label>
                                        <input class="form-control datepicker" type="text" name="booking_date" id="booking_date" value="">                                             
                                     </div> 
                                     <div class="form-group col-md-4">
                                        <label>Origin</label>
                                        <input class="form-control" type="text" name="origin" id="origin" value="">                                             
                                     </div>
                                     <div class="form-group col-md-6">
                                        <label>Destination</label>
                                        <input class="form-control" type="text" name="destination" id="destination" value="">                                             
                                     </div>
                                     
                                     <div class="form-group col-md-6">
                                        <label>Co-Loader</label>
                                        <input class="form-control" type="text" name="service" id="service" value="">                                             
                                     </div>
                                     
                                     <div class="form-group col-md-4">
                                        <label>No of Box</label>
                                        <input class="form-control" type="number" step="any" name="no_of_box" id="no_of_box" value="">                                             
                                     </div>
                                     <div class="form-group col-md-4">
                                        <label>Volumetric Weight</label>
                                        <input class="form-control" type="number" step="any" name="volumetric_weight" id="volumetric_weight" value="">                                             
                                     </div>
                                     <div class="form-group col-md-4">
                                        <label>Weight</label>
                                        <input class="form-control" type="number" step="any" name="total_weight" id="total_weight" value="">                                             
                                     </div>
                                     <div class="form-group col-md-3">
                                        <label>Mode</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="v_mode"  value="Box" checked="true" /> Box 
                                            </label> 
                                        </div>
                                        <div class="radio">
                                            <label>
                                                 <input type="radio" name="v_mode"  value="Weight"  /> Weight
                                            </label>
                                        </div> 
                                     </div> 
                                     <div class="form-group col-md-3">
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
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmedit" enctype="multipart/form-data">
                            <div class="modal-header"> 
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h3 class="modal-title" id="scrollmodalLabel">Edit Line Haul</h3>
                                <input type="hidden" name="mode" value="Edit" />
                                <input type="hidden" name="line_haul_id" id="line_haul_id" />
                            </div>
                            <div class="modal-body"> 
                                 <div class="row">
                                     <div class="form-group col-md-4">
                                        <label>Courier Service</label> 
                                            <?php echo form_dropdown('co_loader_id',array('' => 'Select') + $co_loader_opt  ,set_value('co_loader_id','') ,' id="co_loader_id" class="form-control"');?> 
                                      </div>  
                                     <div class="form-group col-md-4">
                                        <label>Booking Date</label>
                                        <input class="form-control datepicker" type="text" name="booking_date" id="booking_date" value="">                                             
                                     </div>
                                     <div class="form-group col-md-4">
                                        <label>Origin</label>
                                        <input class="form-control" type="text" name="origin" id="origin" value="">                                             
                                     </div> 
                                     <div class="form-group col-md-6">
                                        <label>Destination</label>
                                        <input class="form-control" type="text" name="destination" id="destination" value="">                                             
                                     </div>
                                     <div class="form-group col-md-6">
                                        <label>Co-Loader</label>
                                        <input class="form-control" type="text" name="service" id="service" value="">                                             
                                     </div>
                                     
                                     <div class="form-group col-md-4">
                                        <label>No of Box</label>
                                        <input class="form-control" type="number" step="any" name="no_of_box" id="no_of_box" value="">                                             
                                     </div>
                                     <div class="form-group col-md-4">
                                        <label>Volumetric Weight</label>
                                        <input class="form-control" type="number" step="any" name="volumetric_weight" id="volumetric_weight" value="">                                             
                                     </div>
                                     <div class="form-group col-md-4">
                                        <label>Weight</label>
                                        <input class="form-control" type="number" step="any" name="total_weight" id="total_weight" value="">                                             
                                     </div>
                                     <div class="form-group col-md-3">
                                        <label>Mode</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="v_mode"  value="Box" checked="true" /> Box 
                                            </label> 
                                        </div>
                                        <div class="radio">
                                            <label>
                                                 <input type="radio" name="v_mode"  value="Weight"  /> Weight
                                            </label>
                                        </div> 
                                     </div> 
                                     <div class="form-group col-md-3">
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
                
                <div class="modal fade" id="upload_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmadd" enctype="multipart/form-data">
                            <div class="modal-header">
                                
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h3 class="modal-title" id="scrollmodalLabel">Upload Photo - Line Haul </h3>                                
                                <input type="hidden" name="mode" value="Add Upload" />
                                <input type="hidden" name="line_haul_id" id="line_haul_id" />
                            </div>
                            <div class="modal-body">
                                 <div class="row"> 
                                     <div class="form-group col-md-12">
                                        <label>Photo Upload </label>
                                        <input class="form-control" type="file" name="photo_upload" id="" value="">                                             
                                     </div> 
                                 </div>
                                 <h2>Photos List</h2>
                                 <div class="view_upload"> 
                                 
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
   <?php } ?> 
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
