<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     Franchise Doc Upload List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Master</a></li> 
    <li class="active">Franchise List</li>
    <li class="active">Franchise Doc Upload List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
   
  <div class="box box-info">
    <div class="box-header with-border">
       <form method="post" action="<?php echo site_url('franchise-doc-upload-list');?>">
         <div class="row">  
             <div class="form-group col-md-3">
                 <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add New </button>
             </div>
             <div class="form-group col-md-6">
                <label>Franchises</label>
                  <div class="input-group">
                    <?php echo form_dropdown('srch_franchise_id',array('' => 'Select the Franchise' , '999999' => 'General')  + $franchise_opt  ,set_value('srch_franchise_id',$srch_franchise_id) ,' id="srch_franchise_id" class="form-control" required');?> 
                   </div>                                   
             </div>
             <div class="form-group col-md-3">
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
                <th>DOC Name</th>  
                <th>Remarks</th> 
                <th>View Doc</th> 
                <th colspan="2" class="text-center">Action</th>  
            </tr>
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo $ls['doc_name']?></td>   
                    <td><?php echo $ls['remarks']?></td>   
                    <td><?php if(!empty($ls['doc_path'])) { ?><a href="<?php echo base_url() . $ls['doc_path']; ?>" target="_blank">View</a><?php } ?></td>   
                    <!--<td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['country_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td> -->                                 
                    <td class="text-center">
                        <button value="<?php echo $ls['franchise_doc_upload_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
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
                                <h3 class="modal-title" id="scrollmodalLabel">Add Franchise Doc Upload</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <input type="hidden" name="mode" value="Add" />
                            </div>
                            <div class="modal-body">
                                 <div class="form-group">
                                    <label>Franchise</label>
                                    <?php echo form_dropdown('franchise_id',array('' => 'Select the Franchise', '999999' => 'General') + $franchise_opt  ,set_value('franchise_id',$srch_franchise_id) ,' id="franchise_id" class="form-control" required');?>
                                 </div>
                                 <div class="form-group">
                                    <label>Doc Name</label>
                                    <input class="form-control" type="text" name="doc_name" id="doc_name" value="">                                             
                                 </div> 
                                 <div class="form-group">
                                    <label>Doc Upload</label>
                                    <input class="form-control" type="file" name="doc_path" id="doc_path" value="">                                             
                                 </div> 
                                 <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks"></textarea> 
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
                                <h5 class="modal-title" id="scrollmodalLabel">Edit Country</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <input type="hidden" name="mode" value="Edit" />
                                <input type="hidden" name="country_id" id="country_id" />
                            </div>
                            <div class="modal-body"> 
                                 <div class="form-group">
                                    <label>Country Name</label>
                                    <input class="form-control" type="text" name="country_name" id="country_name" value="">                                             
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
