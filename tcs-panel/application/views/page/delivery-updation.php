<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Delivery Updation </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> Delivery</a></li> 
    <li class="active">Delivery Updation Entry</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
        <form method="post" action=""> 
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Delivery Updation Search</h3>
            </div>
        <div class="box-body">
            <div class="row">  
                 <div class="form-group col-md-5">
                    <label>AWB No</label>
                      <div class="input-group input-group-sm">
                        <input class="form-control" type="text" name="awbno" id="awbno" value="<?php echo set_value('awbno');?>" placeholder="AWB No">  
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-success btn-flat" name="btn_inscan" value="Delivery"><i class="fa fa-search"></i>  Search</button>
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
              <h3 class="box-title text-white">Delivery Consignment Info</h3>
            </div>
        <div class="box-body">
            
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr class="bg-blue-gradient">
                        
                        <th>AWB No</th> 
                        <th>Delivery Address </th>
                        <th>No of Pcs</th>
                        <th>Weight</th>
                        <th>COD</th>
                        <th>To Pay</th> 
                    </tr>
                    
                    </thead>
                    <tbody>
                        <?php 
                            $tot['no_of_pieces'] = $tot['weight'] = 0; $status = '';
                        foreach($record_list as $j => $info) { 
                            $tot['no_of_pieces'] +=  $info['no_of_pieces'];
                            $tot['weight'] +=  $info['weight'];
                            $status = $info['status'];
                        ?>
                        <tr> 
                            <td><?php echo $info['awbno']?>
                                <input type="hidden" name="awbno" value="<?php echo $info['awbno']?>" />
                                <input type="hidden" name="drs_no" value="<?php echo $info['drs_no']?>" />
                            </td> 
                            <td><?php echo $info['receiver_name'] .' <br> ' . $info['receiver_address']?></td>
                            <td class="text-right"><?php echo $info['no_of_pieces']?></td>
                            <td class="text-right"><?php echo $info['weight']?></td>
                            <td class="text-right"><?php echo ($info['cod']== 0 ? '-' : $info['cod_amount'])?></td> 
                            <td class="text-right"><?php echo ($info['to_pay']== 0 ? '-' : $info['grand_total'])?></td> 
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                            <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table> 
                <?php if(!empty($ndr_info)) { ?> 
                <caption>NDR Attempt</caption>
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
                        <?php 
                             
                        foreach($ndr_info as $j => $info) { 
                           
                        ?>
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
            <br />    
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="text-info">Delivery Status</label>
                        </div>
                        <div class="form-group col-xs-6 text-center">
                            <label for="status1">Delivered</label> 
                            <input class="status_opt flat-red" id="status1"  type="radio" name="status" value="Delivered" <?php if($status == 'Delivered') echo ' checked="true"'?>>  
                        </div>
                        <div class="form-group col-xs-6 text-center"> 
                            <label for="status2">Un-Delivered</label> 
                            <input class="status_opt flat-red" id="status2" type="radio" name="status" value="Undelivered" <?php if($status == 'Undelivered') echo ' checked="true"'?>> 
                        </div>
                    </div>
                    <span class="ndr_drp">
                    <i class="text-maroon"> Note : If consignment is Undelivered specify appropriate reason</i><br />
                    <label>Non-Delivery Reason</label>  
                    <?php echo form_dropdown('ndr_id',array('' => 'Select Reason') + $ndr_opt ,set_value('ndr_id'),'id="ndr_id" class="form-control"'); ?>
                    </span>
                </div> 
                <div class="form-group col-md-6">
                    <label>Remarks</label>  
                    <textarea class="form-control" name="remarks" id="remarks" rows="5" placeholder="Remarks"> </textarea>  
                </div> 
             </div>
            <div class="row">   
                 <div class="form-group col-md-2"> 
                    <label>Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="delivered_date" name="delivered_date" value="<?php echo date('Y-m-d');?>">
                    </div>                                   
                 </div>  
                 <div class="form-group col-md-2"> 
                    <label>Time</label>
                     <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" class="form-control timepicker" name="delivered_time" id="delivered_time"> 
                      </div>                                           
                 </div>                      
                 <div class="form-group col-md-3 pod_upd">
                    <label>POD Image Upload </label>  
                    <input type="file" name="pod_img" id="pod_img" class="form-control" /> 
                 </div>  
                 <div class="form-group col-md-3 pod_upd">
                    <label>Delivered To </label>  
                    <input type="text" name="delivered_to" id="delivered_to" class="form-control" placeholder="Delivered To" /> 
                 </div>
                 <div class="form-group col-md-2 pod_upd">
                    <label>Mobile </label>  
                    <input type="text" name="delivered_to_mobile" id="delivered_to_mobile" class="form-control" placeholder="Mobile or Phone" /> 
                 </div>
             </div> 
              
             
         </div> 
         <div class="box-footer">
            <div class="form-group text-center"> 
                <button type="submit" class="btn btn-success btn-flat" name="btn_save" value="delivered"><i class="fa fa-save"></i>  Save</button>  
             </div> 
         </div>   
         </div>  
        </form> 
        <?php }  ?> 
        <?php if($sflg && empty($record_list)) {  ?> 
            <div class="alert alert-error">No Record Found.</div>
        <?php }  ?> 
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
