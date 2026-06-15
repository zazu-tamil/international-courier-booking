<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Account Head List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Petty Cash</a></li> 
    <li class="active">Account Head List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
   
  <div class="box box-info">
    <div class="box-header with-border">
      <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add New </button>
        
       
    </div>
    <div class="box-body table-responsive"> 
       <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Account Head Name</th>  
                <th>Account Type</th>  
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
                    <td><?php echo $ls['account_head_name']?></td>   
                    <td><?php echo $ls['type']?></td>   
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['account_head_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <button value="<?php echo $ls['account_head_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                    </td>                                      
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
      </table>
        
                <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmadd">
                            <div class="modal-header">
                                
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h3 class="modal-title" id="scrollmodalLabel">Add Account Head Info</h3>
                                <input type="hidden" name="mode" value="Add" />
                            </div>
                            <div class="modal-body">
                                 <div class="form-group">
                                    <label>Account Head Name</label>
                                    <input class="form-control" type="text" name="account_head_name" id="account_head_name" value="">                                             
                                 </div> 
                                 <div class="form-group">
                                    <label>Type</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="type"  value="Inward" checked="true" /> Inward 
                                        </label> 
                                    </div>
                                    <div class="radio">
                                        <label>
                                             <input type="radio" name="type"  value="Outward"  /> Outward
                                        </label>
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
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmedit">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scrollmodalLabel">Edit Account Head Info</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <input type="hidden" name="mode" value="Edit" />
                                <input type="hidden" name="account_head_id" id="account_head_id" />
                            </div>
                            <div class="modal-body"> 
                                <div class="form-group">
                                    <label>Account Head Name</label>
                                    <input class="form-control" type="text" name="account_head_name" id="account_head_name" value="">                                             
                                 </div> 
                                 <div class="form-group">
                                    <label>Type</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="type"  value="Inward" checked="true" /> Inward 
                                        </label> 
                                    </div>
                                    <div class="radio">
                                        <label>
                                             <input type="radio" name="type"  value="Outward"  /> Outward
                                        </label>
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
