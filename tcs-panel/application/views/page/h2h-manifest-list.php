<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>HUB To HUB ManiFest</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> ManiFest List</a></li> 
    <li class="active">H2H ManiFest List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
        <form method="post" action=""> 
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">H2H ManiFest Search</h3>
            </div>
        <div class="box-body">
            <div class="row">   
                  
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
                    <button type="submit" class="btn btn-success btn-flat" name="btn_search" value="Search"><i class="fa fa-search"></i>  Show</button>
                    </div>
                 </div>
             </div> 
         </div> 
         </div> 
        <?php  if(!empty($record_list)) { ?>          
         <div class="box box-success"> 
            <div class="box-header with-border"> 
              <div class="row">
                     <div class="form-group col-md-12">
                        <h3 class="box-title text-white">AWB List</h3>
                     </div>
                     <div class="form-group col-md-4 hide"> 
                          <div class="input-group">
                            <input type="text" class="form-control pull-right" id="manifest_awb_no" name="manifest_awb_no" value="">
                            <span class="input-group-btn">
                                <button class="btn btn-success">Add AWB No</button>
                            </span> 
                          </div> 
                     </div>
                </div>
            </div>
            <div class="box-body">
               
               <?php  
                foreach($record_list as $manifest_no => $list) { 
                ?> 
                <input type="hidden" name="b2h_manifest_no" value="<?php echo $manifest_no?>" />
                <input type="hidden" name="manifest_date" value="<?php echo $list[0]['manifest_date']?>" />
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>AWB No</th>
                        <th>Dest</th>
                        <th>No of Pcs</th>
                        <th>Weight</th>
                        <th>Remarks</th>
                        <th class="text-center">Action</th>
                    </tr> 
                    </thead> 
                    <tbody> 
                        <tr class="bg-fuchsia">
                            <th colspan="2">Manifest No : <?php echo $manifest_no; ?></th> 
                            <th colspan="2">Date : <?php echo $list[0]['manifest_date']; ?></th> 
                            <th colspan="3">Co-Loader : <?php echo $list[0]['co_loader_name'] .'<br>AWB No : '. $list[0]['co_loader_awb_no'] .'<br>Remark :'. $list[0]['co_loader_remarks']; ?></th> 
                        </tr>
                        <?php    
                            $tot['no_of_pieces'] = $tot['weight'] = 0;
                        foreach($list as $j => $info) { 
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
                                <button class="btn btn-danger btn-sm btn-del" type="button" name="remove_awb" value="<?php echo $info['booking_id']?>" /><i class="fa fa-remove"></i></button>
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
                             <?php echo form_dropdown('co_loader_id',array('' => 'Select') +$co_loader_opt  ,set_value('co_loader_id',$list[0]['co_loader_id']) ,' id="co_loader_id" class="form-control"');?>
                              <span class="input-group-btn">
                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_modal"><i class="fa fa-plus"></i></button>
                            </span>
                          </div>
                          <!-- /.input group -->                                           
                     </div> 
                     <div class="form-group col-md-3">
                        <label>Co-Loader AWB No</label>
                          <div class="input-group">
                            <input type="text" class="form-control pull-right" id="co_loader_awb_no" name="co_loader_awb_no" value="<?php echo set_value('co_loader_awb_no',$list[0]['co_loader_awb_no']) ?>"> 
                          </div>                                   
                     </div>  
                     <div class="form-group col-md-4">
                        <label>Remarks</label>
                        <textarea class="form-control" name="co_loader_remarks" id="co_loader_remarks"><?php echo set_value('co_loader_remarks',$list[0]['co_loader_remarks']);?></textarea> 
                     </div>
                 </div> 
                <?php } ?>
               
                <?php  if(!empty($new_record_list)) { ?>
                <h3>Pending AWB Bills</h3>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-orange-active">
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
                        foreach($new_record_list as $j => $info) { 
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
                <?php } ?>
            </div>
            <div class="box-footer with-border text-center">
                <a href="" class="btn btn-info"><i class="fa fa-backward"></i> Back to Manifest List</a>  
                &nbsp;&nbsp;&nbsp;    
                
                <button type="submit" name="btn_save" value="Update" class="btn btn-success"><i class="fa fa-save"></i> Update</button> 
            </div>
            </div> 
           <?php } ?>
        </form>  
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
