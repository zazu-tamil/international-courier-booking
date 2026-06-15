<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>HUB To HUB ManiFest</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> ManiFest</a></li> 
    <li class="active">H2H ManiFest</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
        <form method="post" action=""> 
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">H2H ManiFest</h3>
            </div>
        <div class="box-body">
            <div class="row">   
                 <div class="form-group col-md-3"> 
                    <label>Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="manifest_date" name="manifest_date" value="<?php echo date('Y-m-d');?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-3">
                    <label>From</label>
                     <div class="input-group1">
                         <?php echo form_dropdown('from_city_code',array('' => 'Select') + $from_city_opt ,set_value('from_city_code','') ,' id="from_city_code" class="form-control"');?>
                      </div>
                      <!-- /.input group -->                                           
                 </div> 
                 <div class="form-group col-md-3">
                    <label>To</label>
                      <div class="input-group1">
                        <?php echo form_dropdown('to_city_code',array('' => 'Select') + $to_city_opt ,set_value('to_city_code','') ,' id="to_city_code" class="form-control"');?> 
                            
                      </div>                                   
                 </div>  
                 <div class="form-group col-md-3">
                    <label>&nbsp;</label>
                    <div class="input-group1">
                    <button type="submit" class="btn btn-success btn-flat" name="btn_show" value="Show AWB"><i class="fa fa-search"></i>  Show</button>
                    </div>
                 </div>
             </div> 
         </div> 
         </div> 
         <?php  if(!empty($record_list)) { ?>         
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">AWB List</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>AWB No</th>
                        <th>Dest</th>
                        <th>No of Pcs</th>
                        <th>Weight</th>
                        <th>Remarks</th>
                        <th class="text-center">Select All</th>
                    </tr>
                    <tr>
                        <th colspan="6"></th>
                        <th class="text-center"><input type="checkbox" name="select_all" value="1" class="select_all minimal" /></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $tot['no_of_pieces'] = $tot['weight'] = 0;
                        foreach($record_list as $j => $info) { 
                            $tot['no_of_pieces'] +=  $info['no_of_pieces'];
                            $tot['weight'] +=  $info['weight'];
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?></td>
                            <td><?php echo $info['awbno']?></td>
                            <td><?php echo $info['dest_state_code'] .' - ' . $info['dest_city_code']?></td>
                            <td class="text-right"><?php echo $info['no_of_pieces']?></td>
                            <td class="text-right"><?php echo $info['weight']?></td>
                            <td><?php echo $info['commodity_type']?><br /><?php echo $info['description']?> </td>
                            <td class="text-center">
                                <input class="minimal booking_id" type="checkbox" name="booking_ids[]" value="<?php echo $info['booking_id']?>" />
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3"></th>
                            <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                            <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
                <br />
                  
                <div class="row">  
                     <div class="form-group col-md-4">
                        <label>Co-Loader</label>
                         <div class="input-group">
                             <?php echo form_dropdown('co_loader_id',array('' => 'Select') +$co_loader_opt  ,set_value('co_loader_id','') ,' id="co_loader_id" class="form-control"');?>
                              <span class="input-group-btn">
                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_modal"><i class="fa fa-plus"></i></button>
                            </span>
                          </div>
                          <!-- /.input group -->                                           
                     </div> 
                     <div class="form-group col-md-3">
                        <label>Co-Loader AWB No</label>
                          <div class="input-group">
                            <input type="text" class="form-control pull-right" id="co_loader_awb_no" name="co_loader_awb_no" value=""> 
                          </div>                                   
                     </div>  
                     <div class="form-group col-md-4">
                        <label>Remarks</label>
                        <textarea class="form-control" name="co_loader_remarks" id="co_loader_remarks"></textarea> 
                     </div>
                 </div> 
            </div>
            <div class="box-footer with-border text-center">
                <a href="<?php echo site_url('b2h-manifest-list')?>" class="btn btn-info"><i class="fa fa-backward"></i> Back to Manifest List</a>  
                &nbsp;&nbsp;&nbsp;    
                <button type="submit" name="btn_save" value="Save" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
            </div>
            </div> 
            </form> 
            
            <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="" id="frmadd">
                        <div class="modal-header">
                            <b class="modal-title" id="scrollmodalLabel">Add Co-Loader</b>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <input type="hidden" name="mode" value="Add Co-Loader" />
                        </div>
                        <div class="modal-body">
                             <div class="form-group">
                                <label>Co-Loader Name</label>
                                <input class="form-control" type="text" name="co_loader_name" id="co_loader_name" value="">                                             
                             </div>  
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                            <input type="button" name="Save" value="Save"  class="btn btn-primary btn_co_load" />
                        </div> 
                        </form>
                    </div>
                </div>
            </div>
            
            <?php } ?>
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
