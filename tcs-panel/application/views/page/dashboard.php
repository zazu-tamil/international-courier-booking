<?php  include_once(VIEWPATH . '/inc/header.php'); ?>
<section class="content">
      <!-- Small boxes (Stat box) -->
      <?php if(!empty($wallet_msg)) {?>
      <div class="row">
        <div class="form-group col-md-12">
            <div class="bg-red" style="padding: 5px;">
            <marquee width="100%" scrollamount="3" direction="left"  loop="-1" onMouseOver="this.stop()" onMouseOut="this.start()">
                <strong>* Warning * : <?php echo $wallet_msg; ?></strong>
            </marquee>
            </div>
        </div>
      </div>
      <?php } ?>  
      <div class="row"> 
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-book"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text">Today's</span>
              <span class="info-box-number "><?php echo number_format($fr_no_of_booking,0); ?></span> 
              <strong class="text-fuchsia">Domestic Bookings</strong> 
            </div> 
          </div> 
        </div> 
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-book"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text"><?php echo date('F');?> Month</span>
              <span class="info-box-number "><?php echo number_format($fr_no_of_booking_month,0); ?></span> 
              <strong class="text-fuchsia">Domestic Bookings</strong> 
            </div> 
          </div> 
        </div> 
         <?php if($this->session->userdata('cr_is_admin') == 1) {  ?>
        
        
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-rupee"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text">Today's</span>
              <span class="info-box-number "><?php echo number_format($fr_day_amt,2); ?></span> 
              <strong class="text-fuchsia">Revenue</strong> 
            </div> 
          </div> 
        </div> 
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-rupee"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text"><?php echo date('F');?> </span>
              <span class="info-box-number "><?php echo number_format($fr_month_amt,2); ?></span> 
              <strong class="text-fuchsia">Revenue</strong> 
            </div> 
          </div> 
        </div>  
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-rupee"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text">Last Month </span>
              <span class="info-box-number "><?php echo number_format($fr_last_month_amt,2); ?></span> 
              <strong class="text-fuchsia">Revenue</strong> 
            </div> 
          </div> 
        </div> 
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-plane"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text">International</span>
              <span class="info-box-number "><?php echo number_format($intl_booking,0); ?></span> 
              <strong class="text-fuchsia">For <?php echo date('M'); ?> Bookings</strong> 
            </div> 
          </div> 
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-plane"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text">International</span>
              <span class="info-box-number "><i class="fa fa-rupee"></i><?php echo number_format($intl_summary[0]['tot'],2); ?></span> 
              <strong class="text-fuchsia">For <?php echo $intl_summary[0]['g_month']; ?></strong> 
            </div> 
          </div> 
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-ticket"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text">Support Tickets</span>
              <span class="info-box-number "><?php echo number_format($ticket,0); ?></span> 
              <a href="<?php echo site_url('ticket-list'); ?>" class="" target="_blank"><strong class="text-fuchsia">View</strong></a>
            </div> 
          </div> 
        </div>
        <?php } ?>
        <?php if($this->session->userdata('cr_is_admin') == 11) {  ?>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-plane"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text">International</span>
              <span class="info-box-number "><?php echo number_format($intl_booking,0); ?></span> 
              <strong class="text-fuchsia">For <?php echo date('F'); ?></strong> 
            </div> 
          </div> 
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-rupee"></i></span> 
            <div class="info-box-content text-center">
              <span class="info-box-text">International</span>
              <span class="info-box-number "><?php echo number_format($wallet,2); ?></span> 
              <strong class="text-fuchsia">Wallet</strong> 
            </div> 
          </div> 
        </div> 
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-black-gradient"><i class="fa fa-ticket"></i></span>

            <div class="info-box-content text-center">
              <span class="info-box-text">Support Tickets</span>
              <span class="info-box-number "><a href="<?php echo site_url('ticket-list'); ?>" class="" target="_blank"><?php echo number_format($ticket,0); ?></a></span> 
              <a href="<?php echo site_url('ticket-list'); ?>" class="" target="_blank"><strong class="text-fuchsia">View</strong></a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
         <?php } ?>
         <?php if($this->session->userdata('cr_tracking_upd_rights') == 1) {  //print_r($attendance);?>
             <?php if(!isset($attendance['status'])) {  ?>
             <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-black-gradient"><i class="fa fa-sign-in"></i></span>
        
                    <div class="info-box-content text-center">
                      <form method="post"> 
                      <span class="info-box-text">Attendance</span>
                      <br />
                      <button type="submit" class="btn btn-success btn_sign" name="btn_sign" value="Sign-In"> Sign-In</button>
                      </form>   
                    </div> 
                  </div> 
               </div>
             <?php } /* elseif($attendance['status'] != 'In') {  ?>
              <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-black-gradient"><i class="fa fa-sign-in"></i></span>
        
                    <div class="info-box-content text-center">
                      <form method="post"> 
                      <span class="info-box-text">Attendance</span>
                      <br />
                      <button type="submit" class="btn btn-success btn_sign" name="btn_sign" value="Sign-In"> Sign-In</button>
                      </form>   
                    </div> 
                  </div> 
               </div>
             <?php } */ elseif( $attendance['status'] == 'In' ) { ?>
              <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-black-gradient"><i class="fa fa-sign-in"></i></span> 
                    <div class="info-box-content text-center"> 
                      <span class="info-box-text">Attendance</span> 
                      <b>Sign-In</b><br /> 
                      <strong class="text-fuchsia text-uppercase"><?php echo date('h:i a' , strtotime($attendance['in_time'])); ?></strong> 
                    </div> 
                  </div> 
               </div>  
              <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-black-gradient"><i class="fa fa-sign-out"></i></span>
        
                    <div class="info-box-content text-center">
                      <form method="post"> 
                      <span class="info-box-text">Attendance</span>
                      <br />
                      <input type="hidden" name="attendance_log_id" id="attendance_log_id" value="<?php echo $attendance['attendance_log_id']; ?>" />
                      <button type="submit" class="btn btn-success btn_sign" name="btn_sign" value="Sign-Out"> Sign-Out</button>
                      </form>   
                    </div> 
                  </div> 
               </div>
              <?php } elseif( $attendance['status'] == 'Out' ) { ?>
              <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-black-gradient"><i class="fa fa-sign-in"></i></span>
        
                    <div class="info-box-content text-center"> 
                      <span class="info-box-text">Attendance</span> 
                      <b>In</b><br />
                      <strong class="text-fuchsia text-uppercase"><?php echo date('h:i a' , strtotime($attendance['in_time'])); ?></strong> 
                    </div> 
                  </div> 
               </div> 
               <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-black-gradient"><i class="fa fa-sign-out"></i></span>
        
                    <div class="info-box-content text-center"> 
                      <span class="info-box-text">Attendance</span> 
                      <b>Out</b><br />
                      <strong class="text-fuchsia text-uppercase"><?php echo date('h:i a' , strtotime($attendance['out_time'])); ?></strong> 
                    </div> 
                  </div> 
               </div> 
              <?php } ?>
         <?php } ?>
      </div>
      <!-- /.row -->
      
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable1"  >
          <?php if($this->session->userdata('cr_is_admin') == 1) { ?> 
          <?php if(!empty($wallet_payment_info)) {?> 
          <div class="box box-success">
            <div class="box-header">
              <i class="ion ion-clipboard"></i> 
              <h3 class="box-title">Wallet Payment Transfer</h3> 
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Franchise</th>
                        <th>Mode & Amount</th> 
                        <th>Remarks</th>
                        <th>Screenshot</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php   
                        foreach($wallet_payment_info as $j => $ls) {  
                    ?>
                    <tr>
                        <td class="text-center"><?php echo ($j + 1 );?></td> 
                        <td><?php echo date('d-m-Y', strtotime($ls['payment_date'])) ?></td>   
                        <td>
                            <?php echo $ls['franchise_type_name']?><br />
                            <?php echo $ls['contact_person']?><br />
                            <i class="badge"><?php echo $ls['city']?></i>
                         </td>   
                         <td><?php echo $ls['pay_mode']?> <br /> <?php echo $ls['amount']?></td>   
                         <td><?php echo $ls['remarks']?></td>   
                         <td><?php if(!empty($ls['pay_photo'])) { ?><a href="<?php echo base_url($ls['pay_photo']); ?>" target="_blank"><img src="<?php echo base_url($ls['pay_photo']); ?>" alt="" class="img-md" /></a><?php } ?></td>   
                         <td class="text-center">
                            <input type="text" name="received_by" id="received_by_<?php echo $ls['wallet_payment_transfer_id']?>" value="" class="form-control" placeholder="Received By" /> <br />
                            <button class="btn btn-sm btn_received btn-success" name="btn_received" value="<?php echo $ls['wallet_payment_transfer_id']?>"><i class="fa fa-thumbs-up"></i> Received</button>
                         </td>
                    </tr>
                    <?php } ?> 
                </tbody>
               </table>
            </div> 
          </div>
         <?php } ?>  
         <?php } ?>  
            
          <?php if(!empty($incoming_manifest)) {?> 
          <div class="box box-success">
            <div class="box-header">
              <i class="ion ion-clipboard"></i> 
              <h3 class="box-title">Incoming Manifest to be received</h3> 
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Manifest No</th>
                        <th>From</th>
                        <th>AWB Nos</th>
                        <th class="text-right">No.of Pcs</th> 
                        <th class="text-right">Weight</th>
                        <th class="text-left">Co-Loader</th>
                    </tr>
                </thead>
                <tbody>
                    <?php   
                        foreach($incoming_manifest as $j => $info) {  
                    ?>
                    <tr>
                        <td><?php echo ($j + 1)?></td>
                        <td><?php echo date('d-m-Y', strtotime($info['manifest_date'])) ;?></td>
                        <td><?php echo $info['manifest_no'] ;?></td> 
                        <td><?php echo $info['from_city_code'] ;?></td> 
                        <td><?php echo $info['awbno'] ;?></td> 
                        <td class="text-right"><?php echo number_format($info['no_of_pieces'],0);?></td> 
                        <td class="text-right"><?php echo number_format($info['weight'],3);?></td>
                        <td>
                            <?php echo $info['co_loader'] ;?> <br /> 
                            <?php echo $info['co_loader_awb_no'] ;?><br /> 
                            <?php echo $info['co_loader_remarks'] ;?>
                        </td> 
                       
                    </tr>
                    <?php } ?> 
                </tbody>
               </table>
            </div> 
          </div>
         <?php } ?>

        <?php if(!empty($drs_to_be_prepared)) {?> 
          <div class="box box-success">
            <div class="box-header">
              <i class="ion ion-clipboard"></i> 
              <h3 class="box-title">DRS to be Prepared</h3> 
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Received Date</th>
                        <th>Booking Date</th> 
                        <th>From</th>
                        <th>Pincode</th>
                        <th>AWB No</th>
                        <th class="text-right">No.of Pcs</th> 
                        <th class="text-right">Weight</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php   
                        foreach($drs_to_be_prepared as $j => $info) {  
                    ?>
                    <tr>
                        <td><?php echo ($j + 1)?></td>
                        <td><?php echo date('d-m-Y', strtotime($info['received_date'])) ;?></td>
                        <td><?php echo date('d-m-Y', strtotime($info['booking_date'])) ;?></td>
                        <td><?php echo $info['from_city_code'] ;?></td> 
                        <td><?php echo $info['dest_pincode'] ;?></td> 
                        <td><?php echo $info['awbno'] ;?></td> 
                        <td class="text-right"><?php echo number_format($info['no_of_pieces'],0);?></td> 
                        <td class="text-right"><?php echo number_format($info['weight'],3);?></td> 
                    </tr>
                    <?php } ?> 
                </tbody>
               </table>
            </div> 
          </div>
         <?php } ?>
         
         <?php if(!empty($out_for_delivery)) {?> 
          <div class="box box-success">
            <div class="box-header">
              <i class="ion ion-clipboard"></i> 
              <h3 class="box-title">Out for Delivery</h3> 
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>DRS Date & Time</th>
                        <th>AWB No</th>
                        <th>Pincode</th>  
                        <th class="text-right">No.of Pcs</th> 
                        <th class="text-right">Weight</th> 
                        <th>Delivery</th>
                    </tr>
                </thead>
                <tbody>
                    <?php   
                        foreach($out_for_delivery as $j => $info) {  
                    ?>
                    <tr>
                        <td><?php echo ($j + 1)?></td>
                        <td><?php echo date('d-m-Y', strtotime($info['drs_date'])) ;?> <?php echo date('h:i a', strtotime($info['drs_time'])) ;?></td>
                        <td><?php echo $info['awbno'] ;?></td> 
                        <td><?php echo $info['dest_pincode'] ;?></td> 
                        <td class="text-right"><?php echo number_format($info['no_of_pieces'],0);?></td> 
                        <td class="text-right"><?php echo number_format($info['weight'],3);?></td>
                        <td><?php echo $info['delivery_by'] ;?></td> 
                    </tr>
                    <?php } ?> 
                </tbody>
               </table>
            </div> 
          </div>
         <?php } ?>

        </section>
        <!-- /.Left col -->
        <section class="col-lg-12 connectedSortable">
            <div class="row">
                <div class="col-lg-4">
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">Domestic Courier Rate Calculator</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                             <div class="form-group col-md-12">
                                <label>Source</label>
                                <input type="number" step="any" class="form-control" id="source" name="source" placeholder="Source" value="">
                             </div> 
                             <div class="form-group col-md-12">
                                <label>Destination</label>
                                <input type="number" step="any"  class="form-control" id="destination" name="destination" placeholder="Destination" value="">
                             </div> 
                            </div> 
                            <div class="row">
                             <div class="form-group col-md-12">
                                <label>Weight</label>
                                <input type="number" step="any" class="form-control" id="weight" name="weight" placeholder="Weight" value="">
                             </div> 
                            </div> 
                           <!-- <div class="row">
                             <div class="form-group col-md-4">
                                <label>Length</label>
                                <input type="number" step="any" class="form-control" id="length" name="length" placeholder="Length">
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Width</label>
                                <input type="number" step="any" class="form-control" id="width" name="width" placeholder="Width">
                             </div> 
                             <div class="form-group col-md-4">
                                <label>Height</label>
                                <input type="number" step="any" class="form-control" id="height" name="height" placeholder="Height">
                             </div> 
                            </div>-->
                            <div class="row">
                             <div class="form-group col-md-12 text-center">
                                <button type="button" class="btn btn-info btn-flat btn_calc" name="btn_calc" value="Calc"><i class="fa fa-calculator" ></i> Show</button>
                             </div> 
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 text-center rate_show"></div>
                            </div> 
                        </div>
                      </div>
                </div> 
                <div class="col-lg-4">
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">International Courier Rate Calculator</h3>
                        </div>
                        <div class="box-body">
                            <form method="post" action="<?php echo site_url('international-rate-calc');?>">
                            <div class="row">
                             <div class="form-group col-md-12">
                                <label>Destination</label>
                                <?php echo form_dropdown('srch_country_id',array('' => 'Select Country') + $country_opt,set_value('srch_country_id') ,' id="srch_country_id" class="form-control" required');?>
                             </div> 
                             <div class="form-group col-md-12">
                                <label>Package Type</label>
                                <?php echo form_dropdown('srch_pkg_type',array('' => 'Package Type') + $package_type_opt,set_value('srch_pkg_type') ,' id="srch_pkg_type" class="form-control" required');?>
                             </div> 
                            </div> 
                            <div class="row">
                             <div class="form-group col-md-12">
                                <label>Weight <i class="text-sm">In Kgs</i></label>
                                <?php echo form_dropdown('srch_pkg_weight',array('' => 'Select Weight')   ,set_value('srch_pkg_weight') ,' id="srch_pkg_weight" class="form-control" required');?>
                             </div> 
                            </div>  
                            <div class="row">
                             <div class="form-group col-md-12 text-center">
                                <button type="submit" class="btn btn-info btn-flat btn_calc" name="btn_calc" value="Calc"><i class="fa fa-calculator" ></i> Show</button>
                             </div> 
                            </div>
                            </form> 
                        </div>
                    </div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    <div class="box box-primary ">
                        <div class="box-header bg-light-blue-gradient">
                          <h3 class="box-title">Notice Board</h3> 
                        </div> 
                        <div class="box-body"> 
                          <marquee width="100%" scrollamount="3" direction="up" height="250px" loop="-1" onMouseOver="this.stop()" onMouseOut="this.start()">
                            <div id="notice">
                            <p class="text-justify">
                                Welcome 
                                <span class="pull-right"><i class="label label-info">24-12-2021</i></span>
                            </p>
                            <hr />
                            <p class="text-justify">
                                This is a sample scrolling text that has scrolls in the upper direction.
                                <span class="pull-right"><i class="label label-info">24-12-2021</i></span>
                            </p>
                            <hr />
                            </div>
                          </marquee>  
                        </div>  
                      </div> 
                </div>
            </div>
            
        </section>
        
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-4 connectedSortable">
              
           
          <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">AWB Tracking</h3>
            </div>
            <div class="box-body">
                <form method="post" action="<?php echo site_url('awb-tracking') ?>" id="frmsearch" target="_blank">
                      <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="awbno" name="awbno" placeholder="AWB No Tracking">
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-info btn-flat" name="btn_search" value="Tracking"><i class="fa fa-search" ></i></button>
                            </span>
                      </div>
                </form>
            </div>
          </div>    
          <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Serviceable Pincode Search</h3>
            </div>
            <div class="box-body">
                <form method="post" action="<?php echo site_url('servicable-pincode-report') ?>" id="frmsearch">
                      <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="srch_pincode" name="srch_pincode" placeholder="Pincode Search">
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search" ></i></button>
                            </span>
                      </div>
                </form>
            </div>
          </div>  
         <?php  /* ?> 
         <div class="box box-success hide">
            <div class="box-header">
                <h3 class="box-title">Franchise Search</h3>
            </div>
            <div class="box-body">
                <form method="post" action="<?php echo site_url('dash') ?>" id="frmsearch">
                      <div class="input-group input-group-sm">
                            <?php echo form_dropdown('srch_state',array('' => 'All') + $state_opt,set_value('srch_state',$srch_state) ,' id="srch_state" class="form-control"');?>
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search" ></i></button>
                            </span>
                      </div>
                </form>
            </div>
          </div> 
           <?php  */ ?>   
          <?php
	       if($this->session->userdata('cr_is_admin') == 1) { 
          ?>
          <div class="box box-success collapsed-box">
            <div class="box-header">
                <h3 class="box-title">International Summary</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                      <i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                      <i class="fa fa-times"></i></button>
                  </div>
            </div>
            <div class="box-body">
                 <table class="table table-hover table-bordered table-striped table-responsive">
                    <tr>
                        <th>#</th>
                        <th>Month</th>
                        <th>Consignment</th>
                        <th class="text-right">Total</th>
                    </tr>
                    <?php foreach($intl_summary as $j => $info) {   ?>
                    <tr>
                        <td><?php echo ($j + 1)?></td>
                        <td><?php echo $info['g_month'] ;?></td> 
                        <td class="text-right"><?php echo $info['cnt'] ;?></td> 
                        <td class="text-right"><?php echo number_format($info['tot'],2) ;?></td> 
                    </tr>    
                    <?php } ?>
                 </table>
            </div>
          </div>
          <div class="box box-success collapsed-box">
            <div class="box-header">
                <h3 class="box-title">Domestic Summary</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                      <i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                      <i class="fa fa-times"></i></button>
                  </div>
            </div>
            <div class="box-body">
                 <table class="table table-hover table-bordered table-striped table-responsive">
                    <tr>
                        <th>#</th>
                        <th>Month</th>
                        <th>Consignment</th>
                        <th class="text-right">Total</th>
                    </tr>
                    <?php foreach($dmst_summary as $j => $info) {   ?>
                    <tr>
                        <td><?php echo ($j + 1)?></td>
                        <td><?php echo $info['g_month'] ;?></td> 
                        <td class="text-right"><?php echo $info['cnt'] ;?></td> 
                        <td class="text-right"><?php echo number_format($info['tot'],2) ;?></td> 
                    </tr>    
                    <?php } ?>
                 </table>
            </div>
          </div>
          
          <div class="box box-info collapsed-box">
            <div class="box-header">
                <h3 class="box-title">Wallet Balance</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                      <i class="fa fa-plus"></i></button>
                     
                  </div>
            </div>
            <div class="box-body">
                 <table class="table table-hover table-bordered table-striped table-responsive">
                    <tr>
                        <th>#</th> 
                        <th>Type</th>
                        <th>Franchise</th>
                        <th class="text-right">Balance</th>
                    </tr>
                    <?php foreach($wallet_balance as $j => $info) {   ?>
                    <tr>
                        <td><?php echo ($j + 1)?></td>
                        <td><?php echo $info['franchise_type_name'] ;?></td>  
                        <td><?php echo $info['contact_person'] ;?><br /><i class="badge"><?php echo $info['branch_code'];?></i></td>  
                        <td class="text-right"><?php echo number_format($info['balance'],2) ;?></td> 
                    </tr>    
                    <?php } ?>
                 </table>
            </div>
          </div>
          
          
          <?php
	       } 
          ?>
        </section>
        <!-- right col --> 
        <section class="col-lg-8 connectedSortable">
            <?php /* if(!empty($franchise_info)) {?> 
          <div class="box box-success">
            <div class="box-header">
              <i class="ion ion-umbrella"></i> 
              <h3 class="box-title">Franchise</h3> 
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Franchise Type</th>
                        <th>Contact Person</th>
                        <th>Contact Info</th>
                        <th>Address</th> 
                        <th>City</th>
                    </tr>
                </thead>
                <tbody>
                    <?php   
                        foreach($franchise_info as $j => $info) {  
                    ?>
                    <tr>
                        <td><?php echo ($j + 1)?></td>
                        <td><?php echo $info['franchise_type_name'] ;?></td> 
                        <td><?php echo $info['contact_person'] ;?></td> 
                        <td><?php echo $info['mobile'] ;?><br /><?php echo $info['phone'] ;?><br /><?php echo $info['email'] ;?></td>  
                        <td><?php echo str_replace("\n","<br>", $info['address']) ;?></td> 
                        <td><?php echo $info['city_code'] ;?></td> 
                    </tr>
                    <?php } ?> 
                </tbody>
               </table>
            </div> 
          </div>
         <?php } */ ?>
        </section>
      </div>
      <!-- /.row (main row) -->

    </section>
<?php  include_once(VIEWPATH . 'inc/footer.php'); ?>
