<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>HUB/Branch Code List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">HUB/Branch Code List</li>
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
         <form method="post" action="<?php echo site_url('hub-branch-list')?>" id="frmsearch">          
         <div class="row">   
             <div class="form-group col-md-3"> 
                <label>Type</label>
                <div class="radio">
                    <label>
                        <input type="radio" name="srch_type"  value="HUB" <?php echo set_radio('srch_type','HUB', false); ?> /> HUB 
                    </label> 
                </div>
                <div class="radio">
                    <label>
                         <input type="radio" name="srch_type"  value="Branch"  <?php echo set_radio('srch_type','Branch',true); ?> /> Branch
                    </label>
                </div>                                          
             </div>  
             <div class="form-group col-md-4"> 
                <label>HUB/Branch Name or Code</label>
                <div class="input-group"> 
                  <input type="text" class="form-control " id="srch_key" name="srch_key" value="<?php echo set_value('srch_key');?>">
                </div>
                <!-- /.input group -->                                             
             </div>  
            <div class="form-group col-md-2 text-left">
                <br />
                <button class="btn btn-success" name="btn_show" value="Show'"><i class="fa fa-search"></i> Show</button>
            </div>
         </div>  
        </form>
     </div> 
 </div>  
 <br />
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
                <th>Type</th>  
                <th>HUB/Branch Name</th>  
                <th>HUB/Branch Code</th>  
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
                    <td><?php echo $ls['type']?></td>   
                    <td><?php echo $ls['hub_branch_name']?></td>   
                    <td><?php echo $ls['hub_branch_code']?></td>   
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['hub_branch_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <button value="<?php echo $ls['hub_branch_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                    </td>                                      
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
      </table>
        
                <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmadd">
                            <div class="modal-header">
                                
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="scrollmodalLabel">Add HUB/Branch Code</h5>
                                <input type="hidden" name="mode" value="Add" />
                            </div>
                            <div class="modal-body">
                                 <div class="form-group">
                                    <label>Type</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="type"  value="HUB" checked="true" /> HUB 
                                        </label> 
                                    </div>
                                    <div class="radio">
                                        <label>
                                             <input type="radio" name="type"  value="Branch"  /> Branch
                                        </label>
                                    </div> 
                                 </div>
                                 <div class="form-group">
                                    <label>HUB/Branch Name</label>
                                    <input class="form-control" type="text" name="hub_branch_name" id="hub_branch_name" value="">                                             
                                 </div> 
                                 <div class="form-group">
                                    <label>HUB/Branch Code</label>
                                    <input class="form-control" type="text" name="hub_branch_code" id="hub_branch_code" value="">                                             
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
                                
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="scrollmodalLabel">Edit HUB/Branch Code</h5>
                                <input type="hidden" name="mode" value="Edit" />
                                <input type="hidden" name="hub_branch_id" id="hub_branch_id" />
                            </div>
                            <div class="modal-body"> 
                                <div class="form-group">
                                    <label>Type</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="type"  value="HUB" checked="true" /> HUB 
                                        </label> 
                                    </div>
                                    <div class="radio">
                                        <label>
                                             <input type="radio" name="type"  value="Branch"  /> Branch
                                        </label>
                                    </div> 
                                 </div>
                                 <div class="form-group">
                                    <label>HUB/Branch Name</label>
                                    <input class="form-control" type="text" name="hub_branch_name" id="hub_branch_name" value="">                                             
                                 </div> 
                                 <div class="form-group">
                                    <label>HUB/Branch Code</label>
                                    <input class="form-control" type="text" name="hub_branch_code" id="hub_branch_code" value="">                                             
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
