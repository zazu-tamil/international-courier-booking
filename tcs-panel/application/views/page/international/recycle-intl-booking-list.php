<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     Recycle International Booking List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Recycle Bin</a></li> 
    <li class="active">Recycle International Booking List</li>
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
             <form method="post" action="<?php echo site_url('recycle-intl-booking-list')?>" id="frmsearch">          
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
  <div class="box box-success">
    <div class="box-header with-border"> &nbsp;
       <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button> 
      </div>
    </div>
    <div class="box-body table-responsive no-padding">
       
       <table class="table table-hover table-bordered table-striped table-responsive">
        <thead>
            <tr>
                <th class="text-center">S.No</th>
                <th>Date</th>  
                <th>AWBNo</th>  
                <th>Country</th>  
                <th>Weight</th>  
                <th>Service Type</th>  
                <th>Amount</th> 
                <th colspan="2" class="text-center">Action</th>  
            </tr>
        </thead>
          <tbody>
               <?php
                   foreach($record_list as $j=> $ls){
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo date('d-m-Y h:i a', strtotime($ls['booking_date'] . '' . $ls['booking_time'])); ?><br /><i class="badge bg-blue"><?php echo $ls['branch_code']?></i></td> 
                    <td><?php echo $ls['awbno']?></td>   
                    <td><?php echo $ls['country'] ; ?></td>   
                    <td><?php echo $ls['package_weight_range']; ?></td>    
                    <td> <?php echo $ls['international_service_provider']; ?><br /><?php echo $ls['sr_awbno']; ?></td>    
                    <td class="text-right"> <?php echo $ls['actual_charges']; ?> </td>  
                    <td class="text-center">
                        <button value="<?php echo $ls['international_consignment_id']?>" class="btn btn-primary btn-xs btn_restore"   name="btn_restore" title="Restore">Re-Store</a>
                    </td> 
                    <td class="text-center">
                        <button value="<?php echo $ls['international_consignment_id']?>" class="btn btn-warning btn-xs del_record"   name="del_record" title="Delete">Delete</a>
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
