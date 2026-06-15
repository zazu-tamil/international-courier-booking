<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Notice Board List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Notice Board List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
   
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
                <th>Date </th>  
                <th>Message</th>  
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
                    <td><?php echo $ls['notice_date']?></td>   
                    <td><?php echo $ls['notice_msg']?></td>   
                    <td><?php echo $ls['status']?></td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['notice_board_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <button value="<?php echo $ls['notice_board_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                    </td>                                      
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
      </table> 
        
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
  
  <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" action="" id="frmadd">
            <div class="modal-header">
                <h5 class="modal-title" id="scrollmodalLabel">Add Notice Board Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <input type="hidden" name="mode" value="Add" />
            </div>
            <div class="modal-body">
                 <div class="form-group">
                    <label>Date</label>
                    <input class="form-control" type="date" name="notice_date" id="notice_date" value="" required="true">                                             
                 </div> 
                 <div class="form-group">
                    <label>Message</label>
                    <?php echo form_textarea('notice_msg','', 'id="notice_msg" class="form-control" required="true"') ?>                                           
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
                <h5 class="modal-title" id="scrollmodalLabel">Edit Notice Board Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <input type="hidden" name="mode" value="Edit" />
                <input type="hidden" name="notice_board_id" id="notice_board_id" />
            </div>
            <div class="modal-body"> 
                <div class="form-group">
                    <label>Date</label>
                    <input class="form-control" type="date" name="notice_date" id="notice_date" value="" required="true">                                             
                 </div> 
                 <div class="form-group">
                    <label>Message</label>
                    <?php echo form_textarea('notice_msg','', 'id="notice_msg" class="form-control" required="true"') ?>                                           
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

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
