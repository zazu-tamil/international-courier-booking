<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Delivery Runsheet</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> ManiFest</a></li> 
    <li class="active">Delivery Runsheet</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
<form method="post" action="" id="frmsearch">         
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Delivery Runsheet</h3>
            </div>
        <div class="box-body">
            
             <div class="row">   
                 <div class="form-group col-md-2"> 
                    <label>Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="runsheet_date" name="runsheet_date" value="<?php echo date('Y-m-d');?>">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-2">
                     <label>Time</label>
                     <div class="input-group">
                        <input type="text" class="form-control timepicker" name="runsheet_time" id="runsheet_time"> 
                        <div class="input-group-addon">
                          <i class="fa fa-clock-o"></i>
                        </div>
                      </div>                                          
                 </div> 
                <!--<div class="form-group col-md-3">
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
                 </div>-->
                 <div class="form-group col-md-3">
                    <label>To [Destination]</label>
                      <div class="input-group">
                        <?php echo form_dropdown('to_city_code',array('' => 'Select HUB/Branch Code') + $to_city_code_opt ,set_value('to_city_code',$this->session->userdata('cr_branch_code')) ,' id="to_city_code" class="form-control"' . ($this->session->userdata('cr_is_admin') == 11 ? "readonly" : "" ));?> 
                            
                      </div>                                   
                 </div> 
                  
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show AWB'"><i class="fa fa-search"></i> Show Delivery</button>
                </div>
             </div>  
            
         </div> 
         </div> 
         <?php  if(($submit_flg)) { ?>         
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Delivery List</h3> 
            </div>
            <div class="box-body">
                 
                <?php //print_r($record_list);
                  if(!empty($record_list)) { ?>    
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>AWB No</th>
                        <th>Pincode</th>
                        <th>Delivery Address </th>
                        <th>No of Pcs</th>
                        <th>Weight</th>
                        <th>COD</th>
                        <th>To Pay</th>
                        <th class="text-center">Select All</th>
                    </tr>
                    <tr>
                        <th colspan="8"></th>
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
                            <td><?php echo $info['dest_pincode']; ?></td>
                            <td><?php echo $info['receiver_name'] .' <br> ' . $info['receiver_address']?></td>
                            <td class="text-right"><?php echo $info['no_of_pieces']?></td>
                            <td class="text-right"><?php echo $info['weight']?></td>
                            <td class="text-right"><?php echo ($info['cod']== 0 ? '-' : $info['cod_amount'])?></td> 
                            <td class="text-right"><?php echo ($info['to_pay']== 0 ? '-' : $info['grand_total'])?></td> 
                            <td class="text-center">
                                <input class="minimal booking_id" type="checkbox" name="booking_ids[]" value="<?php echo $info['awbno']?>" />
                             
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                            <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table> 
                 
                  <?php } ?>
            </div>
            <div class="box-footer with-border">
                 <?php  if(!empty($record_list)) { ?> 
                 <div class="row">
                 <div class="form-group col-md-4">
                     <label>Delivery By :</label>  
                         <?php echo form_dropdown('delivery_by',array('' => 'Select Delivery Person') +$delivery_by_opt  ,set_value('delivery_by') ,' id="delivery_by" class="form-control"');?>
                 </div> 
                 <div class="form-group col-md-8 text-center"> 
                     <br />
                     <button type="submit" name="btn_save" value="Save" class="btn btn-success col-md-3"><i class="fa fa-save"></i> Save</button>
                 </div>
                 </div>
                 <?php } ?>
            </div>
            </div> 
            <?php } ?>
            </form> 
           
         
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
