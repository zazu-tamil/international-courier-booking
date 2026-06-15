<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
 <section class="content-header">
  <h1>
     Consignment List
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-cubes"></i> International Consignment</a></li> 
    <li class="active">Consignment List</li>
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
             <form method="post" action="<?php echo site_url('international-consignment-list')?>" id="frmsearch">          
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
        <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#add_modal"><span class="fa fa-plus-circle"></span> Add New </button>
      
       <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body table-responsive"> 
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
                <th colspan="4" class="text-center">Action</th>  
            </tr>
        </thead>
          <tbody>
               <?php $tot = 0;
                   foreach($record_list as $j=> $ls){ $tot +=  $ls['tot_charges'];
                ?> 
                <tr> 
                    <td class="text-center"><?php echo ($j + 1 + $sno);?></td> 
                    <td><?php echo date('d-m-Y h:i a', strtotime($ls['booking_date'] . '' . $ls['booking_time'])); ?><br /><i class="badge bg-blue"><?php echo $ls['branch_code']?></i></td> 
                    <td><?php echo $ls['awbno']?></td>   
                    <td><?php echo $ls['country'] ; ?></td>   
                    <td><?php echo $ls['package_weight_range']; ?></td>    
                    <td> <?php echo $ls['international_service_provider']; ?><br /><?php echo $ls['sr_awbno']; ?></td>    
                    <td class="text-right"> <?php echo $ls['tot_charges']; ?> </td> 
                    <td class="text-center">
                       <button data-toggle="modal" data-target="#edit_modal" value="<?php echo $ls['international_consignment_id']?>" class="edit_record btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></button>
                    </td> 
                    <?php if($this->session->userdata('cr_tracking_upd_rights') == '1') {  ?>
                    <td class="text-center">
                       <button data-toggle="modal" data-target="#tracking_modal" value="<?php echo $ls['international_consignment_id']?>" class="btn_tracking btn btn-success btn-xs" title="Tracking"><i class="fa fa-map-marker"></i></button>
                    </td>  
                    <?php }  ?>
                    <td class="text-center">
                       <a href="<?php echo site_url('print-intl-awbno/'). $ls['international_consignment_id']?>" target="_blank" class="btn btn-success btn-xs" title="AWB"><i class="fa fa-print"></i></button>
                    </td>            
                    <td class="text-center">
                        <button value="<?php echo $ls['international_consignment_id']?>" class="del_record btn btn-danger btn-xs" title="Delete"><i class="fa fa-remove"></i></button>
                    </td>                                 
                </tr>
                <?php
                    }
                ?>                                 
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">Total</th>
                    <th class="text-right"><?php echo number_format($tot, 2); ?></th>
                </tr>
            </tfoot>
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
    <div class="modal fade" id="add_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="" id="frmadd" enctype="multipart/form-data">
                <div class="modal-header">
                   
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                     <h3 class="modal-title" id="scrollmodalLabel">Add International Consignment</h3>
                    <input type="hidden" name="mode" value="Add" />
                </div>
                <div class="modal-body"> 
                     <div class="row"> 
                             <div class="col-md-6">
                                <div class="box box-info bg-gray-light ">
                                    <div class="box-header with-border text-center"><b class="text-blue">Consignor Details</b></div>
                                 <div class="box-body"> 
                                    <div class="row"> 
                                         <div class="form-group col-md-12">
                                            <label>Company</label>
                                            <input class="form-control" type="text" name="sender_company" id="sender_company" value="" placeholder="Company Name">                                             
                                         </div> 
                                         <div class="form-group col-md-12">
                                            <label>Sender Name</label>
                                            <input class="form-control" type="text" name="sender_name" id="sender_name" value="" placeholder="Sender Name" required="true">                                             
                                         </div> 
                                         <div class="form-group col-md-12">
                                            <label>Mobile</label>
                                            <input class="form-control" type="text" name="sender_mobile" id="sender_mobile" value="" placeholder="Mobile" maxlength="15" required="true">                                             
                                         </div>
                                         <div class="form-group col-md-12">
                                            <label>Full Address</label>
                                            <textarea class="form-control" name="sender_address" placeholder="Address" id="sender_address" required="true"></textarea>                                             
                                         </div>  
                                          
                                    </div>
                                 </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box box-info bg-gray-light">
                                    <div class="box-header with-border text-center"><b class="text-blue">Consignee Details</b></div>
                                 <div class="box-body">  
                                    <div class="row"> 
                                        <div class="form-group col-md-12">
                                            <label>Company</label>
                                            <input class="form-control" type="text" name="receiver_company" id="receiver_company" value="" placeholder="Company Name">                                             
                                         </div> 
                                         <div class="form-group col-md-12">
                                            <label>Receiver Name</label>
                                            <input class="form-control" type="text" name="receiver_name" id="receiver_name" value="" placeholder="Receiver Name" required="true">                                             
                                         </div> 
                                         <div class="form-group col-md-12">
                                            <label>Mobile</label>
                                            <input class="form-control" type="text" name="receiver_mobile" id="receiver_mobile" value="" placeholder="Mobile" required="true">                                             
                                         </div> 
                                         <div class="form-group col-md-12">
                                            <label>Full Address</label>
                                            <textarea class="form-control" name="receiver_address" placeholder="Address" id="receiver_address" required="true"></textarea>                                             
                                         </div> 
                                            
                                    </div> 
                                 </div>
                                </div>
                            </div> 
                     </div> 
                     <div class="row">
                        <div class="col-md-12">
                            <div class="box box-warning">
                                <div class="box-header"><strong class="text-red">Sender ID Proof</strong></div> 
                                <div class="box-body"> 
                                    <div class="form-group col-md-4">
                                        <label>ID Proof Type</label>
                                        <?php echo form_dropdown('id_proof_type',array('' => 'Select') + $doc_upload_type_opt,set_value('id_proof_type','') ,' id="id_proof_type" class="form-control" required="true"');?>                                             
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>ID Proof Number</label>
                                        <input class="form-control" type="text" name="id_proof_no" id="id_proof_no" value="" placeholder="ID Proof Number" required="true">                                             
                                    </div> 
                                    <div class="form-group col-md-4">
                                        <label>ID Proof Doc Upload</label>
                                        <input class="form-control" type="file" name="id_proof_doc" id="id_proof_doc" value="" placeholder="ID Proof Doc Upload" required="true">                                             
                                     </div> 
                                </div>
                            </div>    
                        </div>    
                     </div>
                     <div class="row"> 
                         <div class="col-md-6">
                            <div class="box box-info ">
                                    <div class="box-header with-border text-center"><b class="text-blue">Consignment Info</b></div>
                                 <div class="box-body"> 
                                    <div class="form-group">
                                        <label>Country</label>
                                        <?php echo form_dropdown('country_id',array('' => 'Select Country') + $country_opt,set_value('country_id') ,' id="country_id" class="form-control" required');?>
                                     </div>  
                                     <div class="form-group">
                                        <label>Package Type</label>
                                        <?php echo form_dropdown('package_type_id',array('' => 'Package Type') + $package_type_opt,set_value('package_type_id') ,' id="package_type_id" class="form-control" required');?>                                          
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>Weight</label>
                                        <?php echo form_dropdown('package_weight_id',array('' => 'Weight')   ,set_value('package_weight_id') ,' id="package_weight_id" class="form-control" required');?>                                          
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>No of Pieces</label>
                                        <input type="number" name="no_of_pieces" id="no_of_pieces" step="any" class="form-control" value="1" required="true" />
                                    </div>  
                                    <div class="form-group">
                                        <label>Description Of Goods &  Dimension Detail</label>
                                        <textarea class="form-control" name="description_of_goods" id="description_of_goods" placeholder="Description Of Goods" required="true"></textarea>                                             
                                    </div>
                                     <div class="form-group text-center">  
                                        <button type="button" name="btn_rate" id="btn_rate" class="btn btn-info col-md-12" >Get Rate</button>                                          
                                     </div> 
                                     
                                     <div class="form-group "><br /><br /><br />
                                        <label>Courier Syndicate AWB No</label>
                                        <input type="text" class="form-control" name="awbno" id="awbno" placeholder="Courier Syndicate AWB No" />
                                        <span class="text-red awbno_warning"></span>  
                                    </div>
                                    
                                 </div> 
                            </div> 
                         </div>
                         <div class="col-md-6">
                            <div class="box box-info ">
                                    <div class="box-header with-border text-center"><b class="text-blue">Consignment Info</b></div>
                                 <div class="box-body"> 
                                    <div class="form-group col-md-12">
                                        <label>Service Type</label>
                                        <?php echo form_dropdown('international_service_provider_id',array('' => 'Select Service Type')   ,set_value('international_service_provider_id') ,' id="international_service_provider_id" class="form-control" ');?>                                          
                                     </div>
                                    <div class="col-md-6 form-group">
                                        <label>Rate</label>
                                        <input type="number" name="actual_charges" id="actual_charges" step="any" class="form-control" value="0"  readonly="true" required="true"  />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Addt.charges [FR Amount]</label>
                                        <input type="number" name="addt_charges" id="addt_charges" step="any" class="form-control" value="0"  required="true"  />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>ODA Type</label>
                                        <select class="form-control" name="oda_charges_id" id="oda_charges_id">
                                            <option value="" data-ah="0">Select</option>
                                            <?php foreach($oda_opt as $j => $info){ ?>
                                            <option value="<?php echo $info['oda_charges_id'];?>" data-oda="<?php echo $info['oda_charges'];?>"><?php echo $info['oda_charges_type'];?></option>
                                            <?php } ?>
                                        </select>
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>ODA Charge</label>
                                        <input type="number" name="oda_charges" id="oda_charges" step="any" class="form-control" value="0"  readonly="true"  />
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>AH Type</label>
                                        <select class="form-control" name="ah_charges_id" id="ah_charges_id">
                                            <option value="" data-ah="0">Select</option>
                                            <?php foreach($ah_opt as $j => $info){ ?>
                                            <option value="<?php echo $info['ah_charges_id'];?>" data-ah="<?php echo $info['ah_charges'];?>"><?php echo $info['ah_charges_type'];?></option>
                                            <?php } ?>
                                        </select>
                                        <?php //echo form_dropdown('ah_charges_id',array('' => 'Select')   ,set_value('ah_charges_id') ,' id="ah_charges_id" class="form-control" ');?>                                          
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>AH Charge</label>
                                        <input type="number" name="ah_charges" id="ah_charges" step="any" class="form-control" value="0" readonly="true"   />
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>Markups</label>
                                        <input type="number" name="markups"  id="markups" step="any" class="form-control"  value="0" required="true" />
                                    </div> 
                                     <div class="col-md-6 form-group">
                                        <label>Total Charges</label>
                                        <input type="number" name="tot_charges"  id="tot_charges" step="any" class="form-control" readonly="true"  required="true"  />
                                    </div> 
                                    <div class="form-group col-md-6"> 
                                        <label>Booking Date</label>
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                           <input type="text" class="form-control pull-right datepicker" id="booking_date" name="booking_date" value="<?php echo date('Y-m-d');?>">
                                        </div>
                                        <!-- /.input group -->                                             
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Time</label>
                                         <div class="input-group">
                                            <input type="time" class="form-control" name="booking_time" id="booking_time" required="true" value="<?php echo date('H:i');?>">
                         
                                          </div>                                        
                                     </div> 
                                     <div class="form-group col-md-12 hide">
                                        <label> Service Provider AWB No <i>[ Connection Tracking ]</i></label>
                                        <input type="text" class="form-control" name="sr_awbno" id="sr_awbno" placeholder="Service Provider AWB No" />
                                    </div>
                                    <div class="form-group col-md-12"> 
                                        <label>Expected Delivery Date</label>
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                           <input type="text" class="form-control pull-right datepicker" id="exp_dv_date" name="exp_dv_date" value="" required="true">
                                        </div>
                                        <!-- /.input group -->                                             
                                    </div>
                                    <div class="col-md-12 form-group hide">
                                        <label>Status</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status"  value="Active" checked="true" /> Active 
                                            </label> 
                                        </div>
                                        <div class="radio">
                                            <label>
                                                 <input type="radio" name="status"  value="InActive"  /> InActive
                                            </label>
                                        </div> 
                                    </div> 
                                 </div> 
                            </div> 
                         </div>
                     </div>        
                        
                     
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                    <input type="submit" name="Save" value="Save" id="btn_save" class="btn btn-primary " />
                </div> 
                </form>
            </div>
        </div>
    </div> 
    
    <div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="" id="frmedit" enctype="multipart/form-data">
                <div class="modal-header">
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="scrollmodalLabel">Edit International Consignment</h3>
                    <input type="hidden" name="mode" value="Edit" />
                    <input type="hidden" name="international_consignment_id" id="international_consignment_id" />
                </div>
                <div class="modal-body">
                    <div class="row"> 
                         <div class="col-md-6">
                            <div class="box box-info bg-gray-light ">
                                <div class="box-header with-border text-center"><b class="text-blue">Consignor Details</b></div>
                             <div class="box-body"> 
                                <div class="row"> 
                                     <div class="form-group col-md-12">
                                        <label>Company</label>
                                        <input class="form-control" type="text" name="sender_company" id="sender_company" value="" placeholder="Company Name">                                             
                                     </div> 
                                     <div class="form-group col-md-12">
                                        <label>Sender Name</label>
                                        <input class="form-control" type="text" name="sender_name" id="sender_name" value="" placeholder="Sender Name" required="true">                                             
                                     </div> 
                                     <div class="form-group col-md-12">
                                        <label>Mobile</label>
                                        <input class="form-control" type="text" name="sender_mobile" id="sender_mobile" value="" placeholder="Mobile" maxlength="15" required="true">                                             
                                     </div>
                                     <div class="form-group col-md-12">
                                        <label>Full Address</label>
                                        <textarea class="form-control" name="sender_address" placeholder="Address" id="sender_address" required="true"></textarea>                                             
                                     </div>  
                                      
                                </div>
                             </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info bg-gray-light">
                                <div class="box-header with-border text-center"><b class="text-blue">Consignee Details</b></div>
                             <div class="box-body">  
                                <div class="row"> 
                                    <div class="form-group col-md-12">
                                        <label>Company</label>
                                        <input class="form-control" type="text" name="receiver_company" id="receiver_company" value="" placeholder="Company Name">                                             
                                     </div> 
                                     <div class="form-group col-md-12">
                                        <label>Receiver Name</label>
                                        <input class="form-control" type="text" name="receiver_name" id="receiver_name" value="" placeholder="Receiver Name" required="true">                                             
                                     </div> 
                                     <div class="form-group col-md-12">
                                        <label>Mobile</label>
                                        <input class="form-control" type="text" name="receiver_mobile" id="receiver_mobile" value="" placeholder="Mobile" required="true">                                             
                                     </div> 
                                     <div class="form-group col-md-12">
                                        <label>Full Address</label>
                                        <textarea class="form-control" name="receiver_address" placeholder="Address" id="receiver_address" required="true"></textarea>                                             
                                     </div> 
                                        
                                </div> 
                             </div>
                            </div>
                        </div> 
                 </div> 
                 <div class="row">
                        <div class="col-md-12">
                            <div class="box box-warning">
                                <div class="box-header"><strong class="text-red">Sender ID Proof</strong></div> 
                                <div class="box-body"> 
                                    <div class="form-group col-md-4">
                                        <label>ID Proof Type</label>
                                        <?php echo form_dropdown('id_proof_type',array('' => 'Select') + $doc_upload_type_opt,set_value('id_proof_type','') ,' id="id_proof_type" class="form-control" required="true"');?>                                             
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>ID Proof Number</label>
                                        <input class="form-control" type="text" name="id_proof_no" id="id_proof_no" value="" placeholder="ID Proof Number" required="true">                                             
                                    </div> 
                                    <div class="form-group col-md-4">
                                        <label>ID Proof Doc Upload</label>
                                        <input class="form-control" type="file" name="id_proof_doc" id="id_proof_doc" value="" placeholder="ID Proof Doc Upload" >                                             
                                        <input type="hidden" name="id_proof_doc_path" id="id_proof_doc_path" value=""  />
                                        <span id="ID_doc_view"></span>
                                     </div> 
                                </div>
                            </div>    
                        </div>    
                     </div>
                 <div class="row"> 
                     <div class="col-md-6">
                        <div class="box box-info ">
                                <div class="box-header with-border text-center"><b class="text-blue">Consignment Info</b></div>
                             <div class="box-body"> 
                                <div class="form-group">
                                    <label>Country</label>
                                    <?php echo form_dropdown('country_id',array('' => 'Select Country') + $country_opt,set_value('country_id') ,' id="country_id" class="form-control" required');?>
                                 </div>  
                                 <div class="form-group">
                                    <label>Package Type</label>
                                    <?php echo form_dropdown('package_type_id',array('' => 'Package Type') + $package_type_opt,set_value('package_type_id') ,' id="package_type_id" class="form-control" required');?>                                          
                                 </div>
                                 <div class="col-md-6 form-group">
                                    <label>Weight</label>
                                    <?php echo form_dropdown('package_weight_id',array('' => 'Weight')   ,set_value('package_weight_id') ,' id="package_weight_id" class="form-control" required');?>                                          
                                 </div>
                                 <div class="col-md-6 form-group">
                                    <label>No of Pieces</label>
                                    <input type="number" name="no_of_pieces" id="no_of_pieces" step="any" class="form-control" value="1" required="true" />
                                </div>  
                                <div class="form-group">
                                    <label>Description Of Goods &  Dimension Detail</label>
                                    <textarea class="form-control" name="description_of_goods" id="description_of_goods" placeholder="Description Of Goods" required="true"></textarea>                                             
                                </div>
                                 <div class="form-group text-center">  
                                    <button type="button" name="btn_rate" id="btn_rate" class="btn btn-info col-md-12" >Get Rate</button>                                          
                                 </div> 
                                 
                                 <div class="form-group "><br /><br /><br />
                                    <label>Courier Syndicate AWB No</label>
                                    <input type="text" class="form-control" name="awbno" id="awbno" placeholder="Courier Syndicate AWB No" readonly="true" />
                                </div>
                             </div> 
                        </div> 
                     </div>
                     <div class="col-md-6">
                        <div class="box box-info ">
                                <div class="box-header with-border text-center"><b class="text-blue">Consignment Info</b></div>
                             <div class="box-body"> 
                                <div class="form-group col-md-12">
                                    <label>Service Type</label>
                                    <?php echo form_dropdown('international_service_provider_id',array('' => 'Select Service Type')   ,set_value('international_service_provider_id') ,' id="international_service_provider_id" class="form-control" ');?>                                          
                                 </div>
                                <div class="col-md-6 form-group">
                                        <label>Rate</label>
                                        <input type="number" name="actual_charges" id="actual_charges" step="any" class="form-control" readonly="true"  required="true"  />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Addt.charges [FR Amount]</label>
                                        <input type="number" name="addt_charges" id="addt_charges" step="any" class="form-control" value="0"  required="true"  />
                                    </div>
                                     <div class="col-md-6 form-group">
                                        <label>ODA Type</label>
                                        <select class="form-control" name="oda_charges_id" id="oda_charges_id">
                                            <option value="" data-ah="0">Select</option>
                                            <?php foreach($oda_opt as $j => $info){ ?>
                                            <option value="<?php echo $info['oda_charges_id'];?>" data-oda="<?php echo $info['oda_charges'];?>"><?php echo $info['oda_charges_type'];?></option>
                                            <?php } ?>
                                        </select>
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>ODA Charge</label>
                                        <input type="number" name="oda_charges" id="oda_charges" step="any" class="form-control" value="0"  readonly="true"  />
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>AH Type</label>
                                        <select class="form-control" name="ah_charges_id" id="ah_charges_id">
                                            <option value="" data-ah="0">Select</option>
                                            <?php foreach($ah_opt as $j => $info){ ?>
                                            <option value="<?php echo $info['ah_charges_id'];?>" data-ah="<?php echo $info['ah_charges'];?>"><?php echo $info['ah_charges_type'];?></option>
                                            <?php } ?>
                                        </select>
                                        <?php //echo form_dropdown('ah_charges_id',array('' => 'Select')   ,set_value('ah_charges_id') ,' id="ah_charges_id" class="form-control" ');?>                                          
                                     </div>
                                     <div class="col-md-6 form-group">
                                        <label>AH Charge</label>
                                        <input type="number" name="ah_charges" id="ah_charges" step="any" class="form-control" value="0" readonly="true"   />
                                     </div>
                                    <div class="col-md-6 form-group">
                                        <label>Markups</label>
                                        <input type="number" name="markups"  id="markups" step="any" class="form-control"  value="0" required="true" />
                                    </div> 
                                    <div class="col-md-6 form-group">
                                        <label>Total Charges</label>
                                        <input type="number" name="tot_charges"  id="tot_charges" step="any" class="form-control" readonly="true"  required="true"  />
                                    </div> 
                                <div class="form-group col-md-6"> 
                                    <label>Booking Date</label>
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                       <input type="text" class="form-control pull-right datepicker" id="booking_date" name="booking_date" value="<?php echo date('Y-m-d');?>">
                                    </div>
                                    <!-- /.input group -->                                             
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Time</label>
                                     <div class="input-group">
                                        <input type="time" class="form-control" name="booking_time" id="booking_time" required="true" value="<?php echo date('H:i');?>">
                     
                                      </div>                                        
                                 </div> 
                                 <div class="form-group col-md-12 hide">
                                    <label> Service Provider AWB No <i>[ Connection Tracking ]</i></label>
                                    <input type="text" class="form-control" name="sr_awbno" id="sr_awbno" placeholder="Service Provider AWB No" />
                                </div>
                                <div class="form-group col-md-12"> 
                                        <label>Expected Delivery Date</label>
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                           <input type="text" class="form-control pull-right datepicker" id="exp_dv_date" name="exp_dv_date" value="" required="true">
                                        </div>
                                        <!-- /.input group -->                                             
                                    </div>
                                <div class="col-md-12 form-group">
                                    <label>Status</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status"  value="Active" checked="true" /> Active 
                                        </label> 
                                    </div>
                                    <div class="radio">
                                        <label>
                                             <input type="radio" name="status"  value="InActive"  /> InActive
                                        </label>
                                    </div> 
                                </div> 
                             </div> 
                        </div> 
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
    
    <div class="modal fade" id="tracking_modal" role="dialog" aria-labelledby="scrollmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"> 
                <form method="post" action="" id="frmuser" enctype="multipart/form-data">
                <div class="modal-header">
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> 
                    <h3 class="modal-title" id="scrollmodalLabel"><strong>Tracking Details</strong></h3>
                    <input type="hidden" name="mode" id="mode" value="Add Tracking" readonly="true" />
                    <input type="hidden" name="international_consignment_id" id="international_consignment_id" value="" /> 
                </div>
                <div class="modal-body table-responsive">
                  <div class="box box-info">
                    <div class="box-body"> 
                         <div class="row">
                                 <div class="form-group col-md-2"> 
                                    <label>Date</label>
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input type="text" class="form-control pull-right datepicker" id="status_date" name="status_date" value="<?php echo date('Y-m-d');?>" required="true">
                                    </div>
                                    <!-- /.input group -->                                             
                                 </div> 
                                 <div class="form-group col-md-2">
                                    <label>Time</label>
                                     <div class="input-group">
                                        <input type="time" class="form-control" name="status_time" id="status_time" value=""  required="true">
                                      </div>
                                      <!-- /.input group -->                                           
                                 </div> 
                                 
                                 <div class="form-group col-md-4" >
                                    <label>Tracking Status</label>
                                    <?php echo form_dropdown('tracking_status',array('' => 'Select Tracking Status') + $tracking_opt ,set_value('tracking_status'),'id="tracking_status" class="form-control"  required="true"'); ?> 
                                 </div>
                                 <div class="form-group col-md-4" >
                                    <label>City/Location</label>
                                     <input type="text" class="form-control" name="location" id="location"  required="true" />
                                 </div>
                             </div>
                             <div class="row"> 
                                 <div class="form-group col-md-4" >
                                    <label>POD Upload</label>
                                     <input type="file" class="form-control" name="intl_pod_path" id="intl_pod_path" />
                                 </div>
                                 <div class="form-group col-md-8" >
                                    <label>Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks"></textarea> 
                                 </div>
                             </div>
                     </div>
                     <div class="box-footer text-center">
                        <button class="btn btn-info" type="reset">Reset</button>
                        <button class="btn btn-success" type="submit">Save</button>
                     </div>
                   </div>
                   
                   <div class="box box-warning">
                     <div class="box-header"><h4 class="heading">Tracking Info</h4></div>
                     <div class="box-body tracking_list"> 
                        
                     </div>
                   </div>  
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
                </div> 
                </form> 
            </div>
        </div>
      </div> 
</section>
<!-- /.content -->
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
