<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Wallet Payment Transfer List</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> Cash Book</a></li> 
    <li class="active">Wallet Payment Transfer List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
  <!-- Default box -->
        <div class="box box-info no-print"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Search Filter</h3>
            </div>
        <div class="box-body">
             <form method="post" action="<?php echo site_url('wallet-payment-transfer-list') ?>" id="frmsearch">          
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
                 <div class="form-group col-md-4">
                    <label>Franchise</label> 
                        <?php echo form_dropdown('srch_franchise_id',array('' => 'All') + $franchise_opt  ,set_value('srch_franchise_id',$srch_franchise_id) ,' id="srch_franchise_id" class="form-control"');?> 
                  </div>  
                 
                <div class="form-group col-md-6 text-right">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show Reports'"><i class="fa fa-search"></i> Show Reports</button>
                </div>
             </div>  
            </form>
         </div> 
         </div> 
  <div class="box box-success">
    <div class="box-header with-border">
      <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add New </button>
        
       
    </div>
    <div class="box-body table-responsive"> 
       <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>  
                <th>Franchise</th>  
                <th>Amount</th>  
                <th>Mode</th>  
                <th>Photo</th>  
                <th>Received By</th>  
                <th>Remarks</th>  
                <th>Status</th>  
                <th colspan="2" class="text-center">Action</th>  
            </tr>
        </thead>
          <tbody>
               <?php $tot = 0;
                   foreach($record_list as $j=> $ls){
                    $tot += $ls['amount'];
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo date('d-m-Y', strtotime($ls['payment_date'])) ?></td>   
                    <td>
                        <?php echo $ls['franchise_type_name']?><br />
                        <?php echo $ls['contact_person']?><br />
                        <i class="badge"><?php echo $ls['city']?></i>
                     </td>   
                     <td class="text-right"><?php echo $ls['amount']?></td>   
                     <td><?php echo $ls['pay_mode']?></td>   
                     <td><?php if(!empty($ls['pay_photo'])) { ?><a href="<?php echo base_url($ls['pay_photo']); ?>" target="_blank"><img src="<?php echo base_url($ls['pay_photo']); ?>" alt="" class="img-md" /></a><?php } ?></td>   
                    <td><?php echo $ls['received_by']?></td> 
                    <td><?php echo $ls['remarks']?></td> 
                    <td><?php echo $ls['status']?></td> 
                    <!--<td class="text-center">
                        <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['wallet_payment_transfer_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td>-->                                  
                    <td class="text-center">    
                        <?php if($ls['status'] == 'Pending') {?>
                        <button value="<?php echo $ls['wallet_payment_transfer_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                        <?php } ?>
                    </td>                                      
                </tr>
                <?php
                    }
                ?>      
                <tr>
                    <th colspan="3">Total</th>
                    <th class="text-right"><?php echo number_format($tot,2)?></th>
                    <th colspan="6"></th>
                </tr>                           
            </tbody>
      </table>
        
                <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <form method="post" action="" id="frmadd" enctype="multipart/form-data">
                            <div class="modal-header"> 
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h2 class="modal-title" id="scrollmodalLabel">Add Wallet Payment Transfer</h2>
                                <input type="hidden" name="mode" value="Add" />
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                     <div class="form-group col-md-12">
                                        <label>Franchise</label> 
                                        <?php echo form_dropdown('franchise_id',array('' => 'Select') + $franchise_opt  ,set_value('franchise_id',$this->session->userdata('cr_franchise_id')) ,' id="franchise_id" class="form-control"');?> 
                                     </div>  
                                     <div class="form-group col-md-6">
                                        <label>Payment Date</label>
                                        <input class="form-control" type="date" name="payment_date" id="payment_date" value="" required="true">                                             
                                     </div> 
                                     <div class="form-group col-md-6">
                                        <label>Amount</label>
                                        <input class="form-control" type="number" step="any" name="amount" id="amount" value="" required="true">                                             
                                     </div>
                                     <div class="form-group col-md-6">
                                        <label>Payment Mode</label> 
                                        <?php echo form_dropdown('pay_mode',array('' => 'Select') + $pay_mode_opt  ,set_value('pay_mode') ,' id="pay_mode" class="form-control" required="true"');?> 
                                     </div>
                                     <div class="form-group col-md-6">
                                        <label>Transfer Screenshot</label>
                                         <input class="form-control" type="file" name="pay_photo" id="pay_photo" value="">
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
