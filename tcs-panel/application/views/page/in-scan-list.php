<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     In Scan List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Booking</a></li> 
    <li class="active">In Scan List</li>
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
             <form method="post" action="<?php echo site_url('in-scan-list')?>" id="frmsearch">          
             <div class="row">   
                 <div class="form-group col-md-3"> 
                    <label>From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_from_date" name="srch_from_date" value="<?php echo set_value('srch_from_date',$srch_from_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-3"> 
                    <label>To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_to_date" name="srch_to_date" value="<?php echo set_value('srch_to_date',$srch_to_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div>
                 <div class="form-group col-md-1"> 
                    <br />
                    <label>Or</label>
                 </div>
                 <div class="form-group col-md-3"> 
                    <label>AWB No</label>
                    <div class="input-group"> 
                      <input type="text" class="form-control " id="srch_awbno" name="srch_awbno" value="<?php echo set_value('srch_awbno',$srch_awbno);?>">
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
  <div class="box">
    <div class="box-header with-border"> &nbsp;
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
                <th class="text-center">S.No</th>
                <th>Date</th>  
                <th>AWB No</th>  
                <th>Origin</th>  
                <th>Destination</th>  
                <th>Status</th>  
                <th colspan="2" class="text-center">Upload Doc</th>  
                <th>Label</th> 
                <th colspan="2" class="text-center">Action</th>  
            </tr>
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td>
                        <?php echo date('d-m-Y h:i a', strtotime($ls['booking_date'] . '' . $ls['booking_time'])); ?>
                        <br /><i class="label label-info "><?php echo $ls['branch_code']?></i>
                    </td> 
                    <td><?php echo $ls['awbno']?></td>   
                    <td><?php echo $ls['origin_pincode'] .'<br>' . $ls['origin_state_code']. ' - ' . $ls['origin_city_code']; ?></td>   
                    <td><?php echo $ls['dest_pincode'] .'<br>' . $ls['dest_state_code']. ' - ' . $ls['dest_city_code']; ?></td>    
                    <td><?php echo $ls['status']?> - <?php echo $ls['status_city_code']?></td> 
                    <td>
                        <button class="btn btn-success btn_upload btn-xs" data-toggle="modal" data-target="#add_modal" value="<?php echo $ls['booking_id']?>"><i class="fa fa-upload"></i></button>
                    </td>
                    <td>
                        <button data-toggle="modal" data-target="#view_modal" value="<?php echo $ls['booking_id']?>" class="view_record btn btn-success btn-xs"><i class="fa fa-eye"></i></button>
                    </td>
                    <td class="text-center">
                        <a href="<?php echo site_url('print-awbno').'/'. $ls['booking_id']?>" target="_blank" class="btn btn-info btn-xs" title="Print Label"><i class="fa fa-print"></i></button>
                    </td> 
                    <td class="text-center">
                        <a href="<?php echo site_url('in-scan-edit').'/'. $ls['booking_id']?>" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>                                  
                    <td class="text-center">
                        <button value="<?php echo $ls['booking_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
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
                <form method="post" action="" id="frmadd" enctype="multipart/form-data">
                <div class="modal-header">
                   
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                     <h3 class="modal-title" id="scrollmodalLabel">Add Upload Doc</h3>
                    <input type="hidden" name="mode" value="Add" />
                    <input type="hidden" name="booking_id" id="booking_id" value="" />
                </div>
                <div class="modal-body">
                     <div class="form-group">
                        <label>Doc Type</label>
                        <?php echo form_dropdown('doc_upload_type', (array('' => 'Select')+ $doc_upload_type_opt), set_value("doc_upload_type"),' class="form-control" id="doc_upload_type" '); ?>
                     </div>  
                     <div class="form-group">
                        <label>Doc Upload</label>
                        <input class="form-control" type="file" name="doc_upload_path" id="doc_upload_path" value="">                                             
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
    
    <div class="modal fade" id="view_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"> 
                <div class="modal-header">                        
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> 
                    <h3 class="modal-title" id="scrollmodalLabel"><strong>View Details</strong></h3>
                </div>
                <div class="modal-body table-responsive"> 
                    <span class="master"></span> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
                </div>  
            </div>
        </div>
    </div> 

</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
