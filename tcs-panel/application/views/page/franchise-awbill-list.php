<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     Franchise AWB No List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Franchise List</li>
    <li class="active">Franchise AWB No List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
   
  <div class="box box-info">
    <div class="box-header with-border">
       <form method="post" action="<?php echo site_url('franchise-awbill-list');?>">
         <div class="row">  
             <div class="form-group col-md-3">
                 <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add New </button>
             </div>
             <div class="form-group col-md-6">
                <label>Franchises</label> 
                    <?php echo form_dropdown('srch_franchise_id',array('' => 'Select the Franchise')  + $franchise_opt  ,set_value('srch_franchise_id',$srch_franchise_id) ,' id="srch_franchise_id" class="form-control" required');?> 
                                                  
             </div>
             <div class="form-group col-md-3">
                 <br />
                 <button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Search</button>
             </div>
         </div> 
     </form>
    </div>
    <div class="box-body table-responsive "> 
       <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No</th>  
                <th>AWB No From</th>  
                <th>AWB No To</th>  
                <th>Remarks</th>   
                <th colspan="2" class="text-center">Action</th>  
            </tr>
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo $ls['awbill_from']?></td>   
                    <td><?php echo $ls['awbill_to']?></td>   
                    <td><?php echo $ls['remarks']?></td>   
                    <td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['franchise_awbill_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                 
                    <td class="text-center">
                        <button value="<?php echo $ls['franchise_awbill_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
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
                            <form method="post" action="" id="frmadd" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h3 class="modal-title" id="scrollmodalLabel">Add Franchise AWB No </h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <input type="hidden" name="mode" value="Add" />
                            </div>
                            <div class="modal-body">
                                 <div class="row">
                                     <div class="form-group col-md-12">
                                        <label>Franchise</label>
                                        <?php echo form_dropdown('franchise_id',array('' => 'Select the Franchise') + $franchise_opt  ,set_value('franchise_id',$srch_franchise_id) ,' id="franchise_id" class="form-control" required');?>
                                     </div>
                                     <div class="form-group col-md-6">
                                        <label>AWB No From</label>
                                        <input class="form-control" type="number" name="awbill_from" id="awbill_from" required="true" value="">                                             
                                     </div> 
                                     <div class="form-group col-md-6">
                                        <label>AWB No To</label>
                                        <input class="form-control" type="number" name="awbill_to" id="awbill_to" required="true" value="">                                             
                                     </div> 
                                     <div class="form-group col-md-12">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks"></textarea> 
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
                                <h5 class="modal-title" id="scrollmodalLabel">Edit Franchise  AWB No List</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <input type="hidden" name="mode" value="Edit" />
                                <input type="hidden" name="franchise_awbill_id" id="franchise_awbill_id" />
                            </div>
                            <div class="modal-body"> 
                                 <div class="row">
                                     <div class="form-group col-md-12">
                                        <label>Franchise</label>
                                        <?php echo form_dropdown('franchise_id',array('' => 'Select the Franchise') + $franchise_opt  ,set_value('franchise_id',$srch_franchise_id) ,' id="franchise_id" class="form-control" required');?>
                                     </div>
                                     <div class="form-group col-md-6">
                                        <label>AWB No From</label>
                                        <input class="form-control" type="number" name="awbill_from" id="awbill_from" required="true" value="">                                             
                                     </div> 
                                     <div class="form-group col-md-6">
                                        <label>AWB No To</label>
                                        <input class="form-control" type="number" name="awbill_to" id="awbill_to" required="true" value="">                                             
                                     </div> 
                                     <div class="form-group col-md-12">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks"></textarea> 
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
