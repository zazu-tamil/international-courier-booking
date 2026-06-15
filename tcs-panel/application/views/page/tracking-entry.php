<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Tracking Status Entry </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Tracking</a></li> 
    <li class="active">Tracking Status Entry</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
        <form method="post" action=""> 
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">AWB No Search</h3>
            </div>
        <div class="box-body">
            <div class="row">  
                 <div class="form-group col-md-5">
                    <label>AWB No</label>
                      <div class="input-group input-group-sm">
                        <input class="form-control" type="text" name="awbno" id="awbno" value="<?php echo set_value('awbno');?>" placeholder="AWB No" required="true">  
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-success btn-flat" name="btn_inscan" value="Tracking"><i class="fa fa-search"></i>  Search</button>
                            </span>
                      </div>                                   
                 </div>  
             </div> 
         </div> 
         </div>  
        </form> 
         <?php //print_r($record_list);
         if(!empty($record_list)) { ?>   
        <form method="post" action="" enctype="multipart/form-data"> 
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Consignment Info</h3>
            </div>
        <div class="box-body">
            
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr class="bg-blue-gradient">
                        
                        <th>AWB No</th> 
                        <th>Sender</th>
                        <th>Receiver </th>
                        <th>No of Pcs</th>
                        <th>Weight</th>
                        <th>Amount</th> 
                        <th>Internal Status</th> 
                    </tr>
                    
                    </thead>
                    <tbody>
                        <?php 
                            //$tot['no_of_pieces'] = $tot['weight'] = 0;
                        foreach($record_list as $j => $info) { 
                            //$tot['no_of_pieces'] +=  $info['no_of_pieces'];
                            //$tot['weight'] +=  $info['weight'];
                        ?>
                        <tr> 
                            <td><?php echo $info['awbno']?>
                                <input type="hidden" name="awbno" value="<?php echo $info['awbno']?>" />
                                <input type="hidden" name="drs_no" value="<?php echo $info['drs_no']?>" />
                            </td> 
                            <td><?php echo $info['sender_name'] .' <br> ' . $info['sender_mobile'].'<br>'. $info['sender_address']?></td>
                            <td><?php echo $info['receiver_name'] .' <br> ' . $info['receiver_mobile'].' <br> '.  $info['receiver_address']?></td>
                            <td class="text-right"><?php echo $info['no_of_pieces']?></td>
                            <td class="text-right"><?php echo $info['weight']?></td>
                            <td class="text-right"><?php echo  $info['grand_total']?></td>  
                            <td class="text-center"><?php echo  $info['status']?><br /><?php echo  $info['status_city_code']?></td>  
                        </tr>
                        <?php } ?>
                    </tbody>
                     
                </table> 
                <?php if(!empty($manifest_info)) {  //print_r($manifest_info)?> 
                <h5>Manifest Info</h5>
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr class="bg-fuchsia-active"> 
                        <th>S.No</th> 
                        <th>Manifest No ,Date & Type </th>
                        <th>From & To</th>
                        <th>Co-loader</th>
                        <th>Status</th>
                        <th>Received Date</th> 
                        <th>Remarks</th> 
                    </tr>
                    
                    </thead>
                    <tbody>
                        <?php  foreach($manifest_info as $j => $info) {   ?>
                        <tr> 
                            <td><?php echo ($j+1)?> </td> 
                            <td><?php echo $info['manifest_no'] .'<br>' . $info['manifest_date'] . '<br>' . $info['manifest_type']?></td> 
                            <td><?php echo $info['from_city_code'] .' - ' . $info['to_city_code']?></td> 
                            <td><?php echo $info['co_loader'] .' <br> ' . $info['co_loader_awb_no']. '<br>' . $info['co_loader_remarks'] ?></td> 
                            <td><?php echo $info['m_status'];?></td> 
                            <td><?php echo $info['received_date'];?></td> 
                            <td><?php echo $info['remarks'];?></td>  
                        </tr>
                        <?php } ?>
                    </tbody> 
                </table> 
                <?php } ?> 
                <hr />
                <?php if(!empty($delivery_info)) {  //print_r($delivery_info)?> 
                <h5>Delivery Info</h5>
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr class="bg-maroon-active"> 
                        <th>S.No</th> 
                        <th>DRS No, Date & Time </th>
                        <th>Status</th>
                        <th>POD Details</th>
                        <th>Remarks</th> 
                    </tr>
                    
                    </thead>
                    <tbody>
                        <?php  foreach($delivery_info as $j => $info) { ?>
                        <tr> 
                            <td><?php echo ($j+1)?> </td> 
                            <td><?php echo $info['drs_no']. '<br />' .$info['drs_date'] .' ' . $info['drs_time']?></td> 
                            <td><?php echo $info['drs_status'];?></td> 
                            <td><?php echo ($info['pod_img']!= '' ? '<img src="../'. $info['pod_img'] .'" width="60">': '');?><br /><?php echo $info['delivered_to'];?><br /><?php echo $info['delivered_date']. ' ' .$info['delivered_time'];?></td> 
                            <td><?php echo $info['remarks'];?></td>  
                        </tr>
                        <?php } ?>
                    </tbody> 
                </table> 
                <?php } ?> 
                <hr />
                <?php if(!empty($ndr_info)) { ?> 
                <h5>NDR Attempt</h5>
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr class="bg-orange-active"> 
                        <th>S.No</th> 
                        <th>Date & Time </th>
                        <th>NDR Code</th>
                        <th>Description</th>
                        <th>Remarks</th> 
                    </tr>
                    
                    </thead>
                    <tbody>
                        <?php  foreach($ndr_info as $j => $info) { ?>
                        <tr> 
                            <td><?php echo ($j+1)?> </td> 
                            <td><?php echo $info['ndr_date'] .' ' . $info['ndr_time']?></td> 
                            <td><?php echo $info['ndr_code'];?></td> 
                            <td><?php echo $info['ndr_details'];?></td> 
                            <td><?php echo $info['remarks'];?></td>  
                        </tr>
                        <?php } ?>
                    </tbody> 
                </table> 
                <?php } ?> 
             
         </div> 
           
         </div>  
        </form> 
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Customer Tracking Status Info</h3>
              <?php if($this->session->userdata('cr_tracking_upd_rights') == '1') {  ?>
                 <div class="pull-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#add_modal"><i class="fa fa-plus-circle"></i> Add Tracking Status</button>
                 </div>
              <?php  } ?>   
            </div>
            <div class="box-body">
                 
                 <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>   
                            <th>Tracking Status</th>  
                            <th>City/Location</th>  
                            <th>Date & Time</th>  
                            <th>Remarks</th>  
                            <?php if($this->session->userdata('cr_tracking_upd_rights') == '1') {  ?>
                            <th colspan="2" class="text-center">Action</th>  
                            <?php } ?>
                        </tr>
                    </thead>
                      <tbody>
                           <?php //print_r($awb_tracking_info);
                               foreach($awb_tracking_info as $j=> $ls){
                            ?> 
                            <tr> 
                                <td class="text-center"><?php echo ($j + 1);?></td>  
                                <td><?php echo $ls['tracking_status']?></td>   
                                <td><?php echo $ls['city_code']?></td>  
                                <td><?php echo $ls['status_date'] .' ' . $ls['status_time']?></td>  
                                <td><?php echo $ls['remarks']?></td>
                                <!--<td class="text-center">
                                    <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['awb_tracking_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                                </td>-->  
                                <?php if($this->session->userdata('cr_tracking_upd_rights') == '1') {  ?>                                
                                <td class="text-center">
                                       <button value="<?php echo $ls['awb_tracking_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                                </td> 
                                <?php } ?>                                     
                            </tr>
                            <?php
                                }
                            ?>                                 
                        </tbody>
                  </table>
                  
                  <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmadd">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="scrollmodalLabel">Add AWB Tracking Status</h4>
                                
                                <input type="hidden" name="mode" value="Add" />
                                <input type="hidden" name="awbno" id="awbno" value="<?php echo set_value('awbno');?>" />
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                     <div class="form-group col-md-2"> 
                                        <label>Date</label>
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input type="text" class="form-control pull-right datepicker" id="status_date" name="status_date" value="<?php echo date('Y-m-d');?>">
                                        </div>
                                        <!-- /.input group -->                                             
                                     </div> 
                                     <div class="form-group col-md-2">
                                        <label>Time</label>
                                         <div class="input-group">
                                            <input type="time" class="form-control" name="status_time" id="status_time">
                         
                                          </div>
                                          <!-- /.input group -->                                           
                                     </div> 
                                     
                                     <div class="form-group col-md-4" >
                                        <label>Tracking Status</label>
                                        <?php echo form_dropdown('tracking_status',array('' => 'Select Tracking Status') + $tracking_opt ,set_value('tracking_status'),'id="tracking_status" class="form-control"'); ?> 
                                     </div>
                                     <div class="form-group col-md-4" >
                                        <label>City/Location</label>
                                        <?php echo form_dropdown('city_code',array('' => 'Select City') + $state_opt ,set_value('city_code'),'id="city_code" class="form-control"'); ?> 
                                     </div>
                                 </div>
                                 <div class="row"> 
                                     <div class="form-group col-md-12" >
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks"></textarea> 
                                     </div>
                                 </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                                <input type="button" name="btn_Save" value="Save" id="btn_Save"  class="btn btn-primary" />
                            </div> 
                            </form>
                        </div>
                    </div>
                </div> 
            </div> 
         </div> 
        <?php }  ?> 
        <?php if($sflg && empty($record_list)) {  ?> 
            <div class="alert alert-error">No Record Found.</div>
        <?php }  ?> 
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
