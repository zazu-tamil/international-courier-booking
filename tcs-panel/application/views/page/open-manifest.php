<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Open ManiFest</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> ManiFest</a></li> 
    <li class="active">Open ManiFest</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
<form method="post" action="" id="frmsearch">         
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Open ManiFest</h3>
            </div>
        <div class="box-body">
            
             <div class="row"> 
                <div class="form-group col-md-3">
                    <label>HUB Or Branch [Source]</label>
                    <div class="radio">
                        <label>
                            <input type="radio" class="from_type" name="from_type"  value="HUB" <?php echo  set_radio('from_type', 'HUB', TRUE); ?> /> HUB 
                        </label> 
                    </div>
                    <div class="radio">
                        <label>
                             <input type="radio" class="from_type" name="from_type"  value="Branch" <?php echo  set_radio('from_type', 'Branch'); ?> /> Branch
                        </label>
                    </div>                                                                
                 </div>
                 <div class="form-group col-md-3">
                    <label>From [Source]</label>
                     <div class="input-group">
                         <?php echo form_dropdown('from_city_code', array('' => 'Select HUB/Branch Code') + $from_city_code_opt ,set_value('from_city_code',$this->session->userdata('branch_code')) ,' id="from_city_code" class="form-control"');?>
                      </div>
                      <!-- /.input group -->                                           
                 </div>
                 <div class="form-group col-md-3">
                    <label>HUB or Branch [Destination]</label>
                     <div class="input-group">
                         <div class="radio">
                            <label>
                                <input type="radio" class="to_type" name="to_type"  value="HUB" <?php echo  set_radio('to_type', 'HUB', TRUE); ?> /> HUB 
                            </label> 
                        </div>
                        <div class="radio">
                            <label>
                                 <input type="radio" class="to_type" name="to_type"  value="Branch" <?php echo  set_radio('to_type', 'Branch'); ?>  /> Branch
                            </label>
                        </div>
                     </div>
                      <!-- /.input group -->                                           
                 </div>
                 <div class="form-group col-md-3">
                    <label>To [Destination]</label>
                      <div class="input-group">
                        <?php echo form_dropdown('to_city_code',array('' => 'Select HUB/Branch Code') + $to_city_code_opt ,set_value('to_city_code','') ,' id="to_city_code" class="form-control"');?> 
                            
                      </div>                                   
                 </div>  
             </div>  
             <div class="row">   
                 <div class="form-group col-md-3"> 
                    <label>Manifest Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="manifest_date" name="manifest_date" value="<?php echo date('Y-m-d');?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                  
                 <div class="form-group col-md-3 hide">
                    <label>Manifest Type</label>
                    <br />  
                    <label class="text-success">HUB</label>
                    <input class="manifest_type flat-red" type="radio" name="manifest_type" value="HUB" checked="true" >   
                     
                    <label class="text-success">Branch</label>
                    <input class="manifest_type flat-red" type="radio" name="manifest_type" value="Branch" <?php if($this->input->post('manifest_type') == 'Branch') echo 'checked="true"'; ?> > 
                </div>
                <div class="form-group col-md-3 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show AWB'"><i class="fa fa-search"></i> Show Manifest</button>
                </div>
             </div>  
            
         </div> 
         </div> 
         <?php  if(($submit_flg)) { ?>         
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">AWB List</h3> 
            </div>
            <div class="box-body">
                <div class="form-group col-md-2 pull-left"> 
                    <label>Manifest No:</label>
                    <input type="text" name="manifest_no" id="manifest_no" class="form-control" value="<?php if(isset($record_list[0]['manifest_no'])) echo $record_list[0]['manifest_no']; else echo (isset($manifest_no) ? $manifest_no : ''); ?>" readonly="true"  /> 
                </div>
                <div class="form-group col-md-2"> 
                    <label>Manifest Type</label><br />    
                    <label class="text-info"><?php  echo $this->input->post('from_type') . ' - ' .$this->input->post('to_type') ;   ?></label>   
                </div>
                <div class="form-group col-md-3"> 
                    <label>From and To : </label> <br />
                     <?php  echo $this->input->post('from_city_code') . ' To ' . $this->input->post('to_city_code') ;   ?>  
                </div>
                
                <div class="form-group col-md-4 pull-right"> 
                  <label>AWB No</label>  
                  <div class="input-group input-group-sm">
                    <input class="form-control input-group-sm" type="text" name="awbno" id="awbno" value="" placeholder="Scan AWB No">  
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-success btn-flat btn_inscan" name="btn_inscan" value="Add AWB No"> <i class="fa fa-plus-circle"></i>  Add AWB No</button>
                        </span>
                  </div>  
                </div>
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>AWB No</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>No of Pcs</th>
                        <th>Weight</th>
                        <th>Remarks</th>
                        <th class="text-center">Action</th>
                    </tr>
                    <!--<tr>
                        <th colspan="6"></th>
                        <th class="text-center"><input type="checkbox" name="select_all" value="1" class="select_all minimal" /></th>
                    </tr>-->
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
                            <td><?php echo $info['origin_state_code'] .' - ' . $info['origin_city_code']?></td>
                            <td><?php echo $info['dest_state_code'] .' - ' . $info['dest_city_code']?></td>
                            <td class="text-right"><?php echo $info['no_of_pieces']?></td>
                            <td class="text-right"><?php echo $info['weight']?></td>
                            <td><?php echo $info['commodity_type']?><br /><?php echo $info['description']?> </td>
                            <td class="text-center">
                                <input class="minimal booking_id" type="hidden" name="booking_ids[]" value="<?php echo $info['awbno']?>" />
                                <button class="btn btn-danger btn-sm btn-del" type="button" name="remove_awb" value="<?php echo $info['manifest_id']?>" /><i class="fa fa-remove"></i></button>
                            
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4"></th>
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
                             <?php echo form_dropdown('co_loader_id',array('' => 'Select') +$co_loader_opt  ,set_value('co_loader_id',((isset($record_list[0]['co_loader_remarks']) ? $record_list[0]['co_loader_id'] : ''))) ,' id="co_loader_id" class="form-control"');?>
                              <span class="input-group-btn">
                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_modal"><i class="fa fa-plus"></i></button>
                            </span>
                          </div>
                          <!-- /.input group -->                                           
                     </div> 
                     <div class="form-group col-md-3">
                        <label>Co-Loader AWB No</label>
                          <div class="input-group">
                            <input type="text" class="form-control pull-right" id="co_loader_awb_no" name="co_loader_awb_no" value="<?php if(isset($record_list[0]['co_loader_remarks'])) echo $record_list[0]['co_loader_awb_no']?>"> 
                          </div>                                   
                     </div>  
                     <div class="form-group col-md-4">
                        <label>Remarks</label>
                        <textarea class="form-control" name="co_loader_remarks" id="co_loader_remarks"><?php if(isset($record_list[0]['co_loader_remarks'])) echo $record_list[0]['co_loader_remarks']?></textarea> 
                     </div>
                 </div> 
                  <?php } ?>
            </div>
            <div class="box-footer with-border text-center">
                 <?php  if(!empty($record_list)) { ?>
                 
                <button type="submit" name="btn_save" value="Save" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                 <?php } ?>
            </div>
            </div> 
            <?php } ?>
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
                            <input type="button" name="Save" value="Save"  class="btn btn-primary btn_co_load" />
                        </div> 
                        </form>
                    </div>
                </div>
            </div>
            
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
