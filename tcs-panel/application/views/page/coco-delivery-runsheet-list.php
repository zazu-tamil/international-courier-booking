<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Co-Courier Delivery Runsheet List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Co-Courier Delivery</a></li> 
    <li class="active">Co-Courier Delivery Runsheet List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
              
      
            </div>
        <div class="box-body">
             <form method="post" action="" id="frmsearch">          
             <div class="row">   
                 <div class="form-group col-md-2"> 
                    <label>From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_from_date" name="srch_from_date" value="<?php echo set_value('srch_from_date',$srch_from_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-2"> 
                    <label>To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="srch_to_date" name="srch_to_date" value="<?php echo set_value('srch_to_date',$srch_to_date);?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div>
                 <div class="form-group col-md-3">
                    <label>Co-Courier</label> 
                        <?php echo form_dropdown('srch_co_courier_id',array('' => 'All') + $co_courier_opt  ,set_value('srch_co_courier_id','') ,' id="srch_co_courier_id" class="form-control"');?> 
                                          
                 </div>  
                 <div class="form-group col-md-3">
                    <label>Delivery By</label> 
                        <?php echo form_dropdown('srch_delivery_by',array('' => 'All') + $delivery_by_opt  ,set_value('srch_delivery_by','') ,' id="srch_delivery_by" class="form-control" ');?> 
                                           
                 </div>   
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show Reports'"><i class="fa fa-search"></i> Show</button>
                </div>
             </div>  
            </form>
         </div> 
         </div> 
         <?php if(($submit_flg)) { ?>         
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Co-Courier Delivery AWB List</h3> 
              <button type="button" class="btn btn-success mb-1 pull-right" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add New </button>
            </div>
            <div class="box-body table-responsive">  
                <?php  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>Co-Courier</th>
                        <th>AWB No</th>
                        <th>Date</th>
                        <th>Type</th> 
                        <th>No of Pcs</th>
                        <th>Weight</th> 
                        <th>Mode</th> 
                        <th>Status</th> 
                        <th>Dely.Status</th> 
                        <th colspan="2" class="text-center">Delivery<br />Updation</th> 
                        <th colspan="2">Action</th> 
                    </tr> 
                    </thead>
                    <tbody>
                        <?php 
                        $tot = 0;
                        foreach($record_list as $j => $info) {   
                           $tot += $info['to_pay_amt'];
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?><br />
                                <i class="badge bg-black-active"><?php echo $info['delivery_by']?></i><br />
                                <i class="badge bg-blue-active"><?php echo $info['franchise']?></i></td>
                            <td><?php echo $info['co_courier']?></td>
                            <td> <?php echo $info['awb_no']?></td>
                            <td><?php echo date('d-m-Y', strtotime($info['booking_date']))?></td>
                            <td><?php echo $info['pkg_type']; ?></td>  
                            <td><?php echo $info['no_of_pcs'] ?></td>  
                            <td><?php echo$info['weight']?></td>  
                            <td class="text-right"><?php echo $info['p_mode']?><br /><?php echo number_format($info['to_pay_amt'],2);?></td>   
                            <td><?php echo $info['status']?></td>
                            <td><?php echo $info['curr_status']?></td>
                            <td class="text-center">
                                <button data-toggle="modal" data-target="#delivery_modal"  value="<?php echo $info['co_courier_drs_id']?>" class="delivery_record btn btn-success btn-xs" title="Delivery Updation"><i class="fa fa-cube"></i></button>
                            </td>
                            <td class="text-center">
                                <button data-toggle="modal" data-target="#view_modal"  value="<?php echo $info['co_courier_drs_id']?>" class="view_record btn btn-default btn-xs" title="Delivery View"><i class="fa fa-eye"></i></button>
                            </td>
                             <td class="text-center">
                                <button data-toggle="modal" data-target="#edit_modal"  value="<?php echo $info['co_courier_drs_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                            </td>                               
                            <td class="text-center">
                                <button value="<?php echo $info['co_courier_drs_id']?>"  class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                            </td>
                        </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="7">Total</th> 
                                <th class="text-right"><?php echo number_format($tot,2)?></th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </tbody>
                     
                </table>  
                 
                  <?php } ?>
            </div>
            <div class="box-footer with-border ">
               <div class="form-group col-sm-6 text-left">
                    <label>Total Records : <?php echo $total_records;?></label>
                </div>
                <div class="form-group col-sm-6 text-right">
                    <?php echo $pagination; ?>
                </div>
            </div>
            </div> 
            <?php } ?> 
        
          <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="post" action="" id="frmadd">
                    <div class="modal-header">
                        <h3 class="modal-title" id="scrollmodalLabel">Add Co-Courier Delivery Entry</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="mode" value="Add" />
                    </div>
                    <div class="modal-body">
                        <div class="row">
                         <div class="form-group col-md-6">
                            <label>Co-Courier </label>
                            <?php echo form_dropdown('co_courier_id',array('' => 'Select') + $co_courier_opt  ,set_value('co_courier_id',$srch_co_courier_id) ,' id="co_courier_id" class="form-control" required="true"');?> 
                         </div> 
                         <div class="form-group col-md-3">
                            <label>AWB No</label>
                            <input class="form-control" type="text" name="awb_no" id="awb_no" value="" required="true"> 
                         </div>
                         <div class="form-group col-md-3">
                            <label>Booking Date</label>
                            <input class="form-control" type="date" name="booking_date" id="booking_date" value=""> 
                         </div> 
                         <div class="form-group col-md-3">
                            <label>Type</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="pkg_type"  value="Doc" checked="true" /> Doc 
                                </label> 
                                &nbsp;&nbsp;&nbsp;
                                <label>
                                     <input type="radio" name="pkg_type"  value="Non-Doc"  /> Non-Doc
                                </label>
                                
                            </div> 
                         </div>
                         <div class="form-group col-md-2">
                            <label>Weight</label>
                            <input class="form-control" type="number" step="any" name="weight" id="weight" value=""> 
                         </div>
                         <div class="form-group col-md-2">
                            <label>No of Pieces</label>
                            <input class="form-control" type="number" step="any" name="no_of_pcs" id="no_of_pcs" value=""> 
                         </div>
                         <div class="form-group col-md-3">
                            <label>Mode</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="p_mode"  value="Paid" checked="true" /> Paid 
                                </label> 
                                &nbsp;&nbsp;&nbsp;
                                <label>
                                     <input type="radio" name="p_mode"  value="To Pay"  /> To Pay
                                </label>
                                
                            </div> 
                         </div>
                         <div class="form-group col-md-2">
                            <label>Tp Pay Amount</label>
                            <input class="form-control" type="number" step="any" name="to_pay_amt" id="to_pay_amt" value=""> 
                         </div>
                         <div class="form-group col-md-8">
                            <label>Delivery Address</label>
                            <textarea name="delivery_address" id="delivery_address" class="form-control"></textarea> 
                         </div>
                         <div class="form-group col-md-4">
                            <label>Delivery Assign To </label>
                            <?php echo form_dropdown('delivery_by',array('' => 'Select') + $delivery_by_opt  ,set_value('delivery_by') ,' id="delivery_by" class="form-control" required="true"');?> 
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
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form method="post" action="" id="frmedit">
                        <div class="modal-header">
                            <h3 class="modal-title" id="scrollmodalLabel">Edit Co-Courier Delivery Entry</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <input type="hidden" name="mode" value="Edit" />
                            <input type="hidden" name="co_courier_drs_id" id="co_courier_drs_id" />
                        </div>
                        <div class="modal-body"> 
                            <div class="row">
                                 <div class="form-group col-md-6">
                                    <label>Co-Courier </label>
                                    <?php echo form_dropdown('co_courier_id',array('' => 'Select') + $co_courier_opt  ,set_value('co_courier_id',$srch_co_courier_id) ,' id="co_courier_id" class="form-control" required="true"');?> 
                                 </div> 
                                 <div class="form-group col-md-3">
                                    <label>AWB No</label>
                                    <input class="form-control" type="text" name="awb_no" id="awb_no" value="" required="true"> 
                                 </div>
                                 <div class="form-group col-md-3">
                                    <label>Booking Date</label>
                                    <input class="form-control" type="date" name="booking_date" id="booking_date" value=""> 
                                 </div> 
                                 <div class="form-group col-md-3">
                                    <label>Type</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="pkg_type"  value="Doc" checked="true" /> Doc 
                                        </label> 
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                             <input type="radio" name="pkg_type"  value="Non-Doc"  /> Non-Doc
                                        </label>
                                        
                                    </div> 
                                 </div>
                                 <div class="form-group col-md-2">
                                    <label>Weight</label>
                                    <input class="form-control" type="number" step="any" name="weight" id="weight" value=""> 
                                 </div>
                                 <div class="form-group col-md-2">
                                    <label>No of Pieces</label>
                                    <input class="form-control" type="number" step="any" name="no_of_pcs" id="no_of_pcs" value=""> 
                                 </div>
                                 <div class="form-group col-md-3">
                                    <label>Mode</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="p_mode"  value="Paid" checked="true" /> Paid 
                                        </label> 
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                             <input type="radio" name="p_mode"  value="To Pay"  /> To Pay
                                        </label>
                                        
                                    </div> 
                                 </div>
                                 <div class="form-group col-md-2">
                                    <label>Tp Pay Amount</label>
                                    <input class="form-control" type="number" step="any" name="to_pay_amt" id="to_pay_amt" value=""> 
                                 </div>
                                <div class="form-group col-md-8">
                                    <label>Delivery Address</label>
                                    <textarea name="delivery_address" id="delivery_address" class="form-control"></textarea> 
                                 </div>
                                 <div class="form-group col-md-4">
                                    <label>Delivery Assign To </label>
                                    <?php echo form_dropdown('delivery_by',array('' => 'Select') + $delivery_by_opt  ,set_value('delivery_by') ,' id="delivery_by" class="form-control" required="true"');?> 
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
           
           
                <div class="modal fade" id="delivery_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmedit" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h3 class="modal-title" id="scrollmodalLabel">Co-Courier Delivery Updation</h3> 
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <input type="hidden" name="mode" value="Add DLYUPD" />
                                <input type="hidden" name="co_courier_drs_id" id="co_courier_drs_id" />
                            </div>
                            <div class="modal-body"> 
                              <div class="row">
                                 <div class="form-group col-md-6">
                                    <label>Co-Courier : </label>
                                    <label class="hdspan"></label>
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>AWB No: </label>
                                    <label class="hdspan1"></label>
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>Date</label>
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                    <input class="form-control datepicker" type="text" name="dely_date" id="dely_date" value="">                                             
                                    </div> 
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>Delivered  By </label>
                                    <?php echo form_dropdown('delivered_by',array('' => 'Select') + $delivery_by_opt  ,set_value('delivered_by') ,' id="delivered_by" class="form-control"');?> 
                                 </div>  
                                 <div class="form-group col-md-6">
                                    <label>Delivery Status </label>
                                    <?php echo form_dropdown('dely_status',array('' => 'Select') + $co_courier_drs_status_opt  ,set_value('dely_status') ,' id="dely_status" class="form-control"');?> 
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>POD Upload</label>
                                    <input class="form-control" type="file" name="pod_upload" id="pod_upload" value="">                                             
                                 </div>  
                                 <div class="form-group col-md-6">
                                    <label>Receiver Name</label>
                                    <input class="form-control" type="text" name="receiver_name" id="receiver_name" value="">                                             
                                 </div>     
                                 <div class="form-group col-md-6">
                                    <label>Receiver Contact Number</label>
                                    <input class="form-control" type="text" name="receiver_contact" id="receiver_contact" value="">                                             
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>To Pay Amount Received</label>
                                    <input class="form-control" type="number" step="any" name="to_pay_amt_rec" id="to_pay_amt_rec" value="">                                             
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>Delivery Expenses</label>
                                    <input class="form-control" type="number" step="any" name="delivery_exp" id="delivery_exp" value="">                                             
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>Loading & Unloading Expenses</label>
                                    <input class="form-control" type="number" step="any" name="loading_unloading_exp" id="loading_unloading_exp" value="">                                             
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>Challan Upload</label>
                                    <input class="form-control" type="file" name="challan_upload" id="challan_upload" value="">                                             
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
                
                <div class="modal fade" id="edit_delivery_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmedit" enctype="multipart/form-data">
                            <div class="modal-header">
                                
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h3 class="modal-title" id="scrollmodalLabel">Co-Courier Delivery Updation</h3> 
                                <input type="hidden" name="mode" value="Edit DLYUPD" />
                                <input type="hidden" name="co_courier_drs_dely_id" id="co_courier_drs_dely_id" /> 
                                <input type="hidden" name="co_courier_drs_id" id="co_courier_drs_id" /> 
                            </div>
                            <div class="modal-body table-responsive" style="overflow: auto; "> 
                                 <div class="row">
                                 <!--<div class="form-group col-md-6">
                                    <label>Co-Courier : </label>
                                    <label class="hdspan"></label>
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>AWB No: </label>
                                    <label class="hdspan1"></label>
                                 </div>-->
                                 <div class="form-group col-md-6">
                                    <label>Date</label>
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                    <input class="form-control datepicker" type="text" name="dely_date" id="dely_date">                                             
                                    </div> 
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>Delivered By </label>
                                    <?php echo form_dropdown('delivered_by',array('' => 'Select') + $delivery_by_opt  ,set_value('delivered_by') ,' id="delivered_by" class="form-control"');?> 
                                 </div>  
                                 <div class="form-group col-md-6">
                                    <label>Delivery Status </label>
                                    <?php echo form_dropdown('dely_status',array('' => 'Select') + $co_courier_drs_status_opt  ,set_value('dely_status') ,' id="dely_status" class="form-control"');?> 
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>POD Upload</label>
                                    <input class="form-control" type="file" name="pod_upload" id="pod_upload" value="">
                                    <input type="hidden" name="pod_upload_path" id="pod_upload_path" />                                             
                                 </div>  
                                 <div class="form-group col-md-6">
                                    <label>Receiver Name</label>
                                    <input class="form-control" type="text" name="receiver_name" id="receiver_name" value="">                                             
                                 </div>     
                                 <div class="form-group col-md-6">
                                    <label>Receiver Contact Number</label>
                                    <input class="form-control" type="text" name="receiver_contact" id="receiver_contact" value="">                                             
                                 </div>  
                                 <div class="form-group col-md-6">
                                    <label>POD Image Photo</label>
                                    <div class="pod-img"></div>
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>To Pay Amount Received</label>
                                    <input class="form-control" type="number" step="any" name="to_pay_amt_rec" id="to_pay_amt_rec" value="">                                             
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>Delivery Expenses</label>
                                    <input class="form-control" type="number" step="any" name="delivery_exp" id="delivery_exp" value="">                                             
                                 </div> 
                                 <div class="form-group col-md-6">
                                    <label>Loading & Unloading Expenses</label>
                                    <input class="form-control" type="number" step="any" name="loading_unloading_exp" id="loading_unloading_exp" value="">                                             
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>Challan Upload</label>
                                    <input class="form-control" type="file" name="challan_upload" id="challan_upload" value="">
                                    <input type="hidden" name="challan_upload_path" id="challan_upload_path" />                                              
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>Challan Upload Photo</label>
                                    <div class="challan-img"></div>
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
                
                <div class="modal fade" id="view_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content"> 
                            <div class="modal-header">
                                <h3 class="modal-title" id="scrollmodalLabel"><strong>View Details</strong></h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> 
                            </div>
                            <div class="modal-body table-responsive">
                            
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary close_link" data-dismiss="modal">Close</button>  
                            </div>  
                        </div>
                    </div>
                </div>
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
