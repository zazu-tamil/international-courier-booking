<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>Receive ManiFest</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-book"></i> ManiFest</a></li> 
    <li class="active">Receive ManiFest</li>
  </ol>
</section>
<!-- Main content -->
<section class="content"> 
<form method="post" action="" id="frmsearch">         
        <div class="box box-info"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Receive ManiFest</h3>
            </div>
        <div class="box-body">
            
             <div class="row">   
                 <div class="form-group col-md-2"> 
                    <label>From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="from_date" name="from_date" value="<?php echo set_value('from_date');?>" required="true">
                    </div>
                    <!-- /.input group -->                                             
                 </div> 
                 <div class="form-group col-md-2"> 
                    <label>To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="to_date" name="to_date" value="<?php echo set_value('to_date');?>" required="true">
                    </div>
                    <!-- /.input group -->                                             
                 </div>
                 <!--<div class="form-group col-md-3 hide">
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
                 </div> -->
                 <div class="form-group col-md-3">
                    <label>To [Destination]</label>
                      <div class="input-group">
                        <?php echo form_dropdown('to_city_code',array('' => 'Select HUB/Branch Code') + $to_city_code_opt ,set_value('to_city_code',$this->session->userdata('cr_branch_code')) ,' id="to_city_code" class="form-control "' . ($this->session->userdata('cr_is_admin') == 11 ? "readonly" : "" ));?> 
                            
                      </div>                                   
                 </div>
                  
                <div class="form-group col-md-2 text-left">
                    <br />
                    <button class="btn btn-success" name="btn_show" value="Show Manifest'"><i class="fa fa-search"></i> Show Manifest</button>
                </div>
             </div>  
            
         </div> 
         </div> 
         <?php  if(($submit_flg)) { ?>         
         <div class="box box-success"> 
            <div class="box-header with-border">
              <h3 class="box-title text-white">Manifest List</h3> 
            </div>
            <div class="box-body">
                 
                <div class="form-group col-md-6 pull-right"> 
                  <label>Received AWB No</label>  
                  <div class="input-group input-group-sm">
                    <input class="form-control input-group-sm" type="text" name="awbno" id="awbno" value="" placeholder="Scan AWB No">  
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-success btn-flat btn_received" name="btn_received" value="Received AWB No"> <i class="fa fa-plus-circle"></i>  Received AWB No</button>
                        </span>
                  </div>  
                </div>
                <?php  if(!empty($record_list)) {  ?>    
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>Manifest No</th>
                        <th>Date</th>
                        <th>From</th> 
                        <th>Send</th> 
                        <th>Received</th> 
                        <th>No of Pcs</th>
                        <th>Weight</th> 
                        <th>Co-Loader</th>
                        <th class="text-center">Action</th>
                    </tr> 
                    </thead>
                    <tbody>
                        <?php 
                            $tot['no_of_pieces'] = $tot['weight'] = 0;
                        foreach($record_list as $j => $info) { 
                            
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?></td>
                            <td><?php echo $info['manifest_no']?></td>
                            <td><?php echo $info['manifest_date']?></td>
                            <td><?php echo $info['from_city_code'];?></td> 
                            <td class="text-center"><span class="badge label-info"><?php echo $info['open_mf'];?></span></td> 
                            <td class="text-center"><span class="badge label-success"><?php echo number_format($info['received_mf'],0);?></span></td> 
                            <td class="text-right"><?php echo $info['no_of_pieces']?></td>
                            <td class="text-right"><?php echo $info['tot_weight']?></td>
                            <td><?php echo $info['co_loader']?><br /><?php echo $info['co_loader_awb_no']?><br /><?php echo $info['co_loader_remarks']?> </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm btn-view" data-toggle="modal" data-target="#view_modal" name="btn-view" value="<?php echo $info['manifest_no']?>"><i class="fa fa-eye"></i></button>
                            </td> 
                        </tr>
                        <?php } ?>
                    </tbody>
                     
                </table> 
                
                <div class="modal fade" id="view_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content"> 
                            <div class="modal-header">
                                <b class="modal-title" id="scrollmodalLabel"><strong>Manifest List</strong></b>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> 
                            </div>
                            <div class="modal-body table-responsive">
                            
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
                            </div>  
                        </div>
                    </div>
                </div>   
                 
                  <?php } ?>
            </div>
            <div class="box-footer with-border text-center">
                 
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
