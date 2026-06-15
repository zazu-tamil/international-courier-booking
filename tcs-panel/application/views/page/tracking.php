<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Courier Tracking</title>
  <link rel="shortcut icon" href="" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/dist/css/AdminLTE.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page"> 
  <section class="content">

      <!-- Default box -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> Courier Tracking</h3> 
        </div>
        <div class="box-body bg-gray-light"> 
           <div class="row">
               <div class="col-md-12">
                <form method="post" action=""> 
                <div class="box box-success"> 
                    <div class="box-header with-border">
                      <h3 class="box-title text-white"> AWB No Tracking</h3>
                    </div>
                <div class="box-body">
                    <div class="row">  
                         <div class="col-md-4 form-group text-center">
                            <img src="<?php echo base_url('asset/images/logo.png')?>" width="30%" class="img-responsive" />
                         </div>
                         <div class="form-group col-md-4"> 
                                <label>AWB No : </label>
                              <div class="input-group input-group-sm">
                                <input class="form-control" type="text" name="awbno" id="awbno" value="<?php echo set_value('awbno');?>" placeholder="AWB No : 20210301,110044" required>  
                                    <span class="input-group-btn">
                                      <button type="submit" class="btn btn-success btn-flat" name="btn_search" value="Tracking"><i class="fa fa-search"></i>  Search</button>
                                    </span>
                                
                              </div>
                              <i class="text-red">Use comma ' , ' for Multiple Tracking</i>                                   
                         </div>
                         <div class="col-md-4 form-group text-center">
                            <img src="<?php echo base_url('asset/images/logo.png')?>" width="30%" class="img-responsive" />
                         </div>  
                          
                     </div> 
                 </div> 
                 </div>  
                </form> 
               </div>
           </div>
           <?php if(isset($record_list) && !empty($record_list)) { ?>
           <?php $q=1;  foreach($record_list as $awbno => $rec ) { ?>
            <div class="row ">
               <div class="col-md-12 text-center">
                    <div class="box box-warning"> 
                     <div class="box-header with-border">
                     <i class="label bg-maroon-gradient pull-left"># <?php echo $q; ?></i> <h3 class="box-title text-white"> <label class="text-blue">AWB NO: <?php echo $awbno ; ?></label></h3>
                      
                     </div> 
                     <div class="box-body" style="background-color: beige;">
                        <div class="row ">
                           <div class="col-md-4">
                               <div class="box box-info"> 
                                 <div class="box-header with-border">
                                  <h3 class="box-title text-white">Origin</h3>
                                 </div>
                                 <div class="box-body">
                                     <p class="text-left">
                                      Pincode:   <?php echo $rec['origin_pincode'] ;?> <br />
                                      City :   <?php echo $rec['origin_city'] ;?> <br />
                                      State :   <?php echo $rec['origin_state'];?> <br />
                                      Name :  <?php echo $rec['sender_name'] ;?> <br /> 
                                      
                                    </p>          
                                 </div>
                            </div>
                           </div>
                           <div class="col-md-4"> 
                             <div class="box box-info"> 
                                 <div class="box-header with-border">
                                  <h3 class="box-title text-white">Destination</h3>
                                 </div>
                                 <div class="box-body">
                                    <p class="text-left">
                                      Pincode:   <?php echo $rec['dest_pincode'] ;?> <br /> 
                                      City :   <?php echo $rec['dest_city'] ;?> <br />
                                      State :   <?php echo $rec['dest_state'];?> <br /> 
                                      Name : <?php echo $rec['receiver_name'] ;?> <br /> 
                                       
                                       
                                    </p> 
                                 </div>
                            </div>
                           </div>
                           <div class="col-md-4"> 
                             <div class="box box-info"> 
                                 <div class="box-header with-border">
                                  <h3 class="box-title text-white">Package Info</h3>
                                 </div>
                                 <div class="box-body">
                                     <p class="text-left">
                                      Weight : <?php echo $rec['weight'] ;?> Kgs <br /> 
                                      No of Pieces : <?php echo $rec['no_of_pieces'] ;?> <br />  
                                    </p>
                                 </div>
                            </div>
                           </div>
                       </div>
                       <div class="row">
                            <div class="col-md-6"> 
                                 <div class="box box-info "> 
                                    <div class="box-header with-border">
                                      <h3 class="box-title text-white">Tracking</h3>
                                     </div>
                                    <div class="box-body bg-navy">
                                        <ul class="timeline">
                                            <?php if(isset($awb_tracking_info[$awbno])) {
                                             foreach($awb_tracking_info[$awbno] as $info) { ?>
                                            <li class="time-label">
                                                  <span class="bg-green-gradient">
                                                    <?php echo date('d M Y', strtotime($info['status_date'])) ;?>
                                                  </span>
                                            </li>
                                            <li>
                                              <i class="fa fa-envelope bg-blue"></i>
                                
                                              <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> <?php echo date('h:i a', strtotime($info['status_time']));?></span>
                                
                                                <h3 class="timeline-header"> <?php echo $info['tracking_status'];?> @ <i class="text-capitalize"><?php echo strtolower($info['city']);?></i></h3>
                                
                                                <div class="timeline-body">
                                                   <?php echo $info['remarks'];?>
                                                   <?php 
                                                    if( isset($delivery_info[$awbno]) and $info['tracking_status'] == $delivery_info[$awbno]['drs_status']) { ?>
                                                         <br />
                                                         <a href="<?php echo base_url($delivery_info[$awbno]['pod_img']);?>" target="_blank"><img src="<?php echo base_url($delivery_info[$awbno]['pod_img']);?>" width="30%" class="img-thumbnail" /></a>
                                                         <br />
                                                         <?php echo $delivery_info[$awbno]['delivered_to'];?> - <?php echo $delivery_info[$awbno]['delivered_to_mobile'];?>
                                                   <?php  } ?>
                                                </div>
                                                 
                                              </div>
                                            </li>
                                            <?php } ?>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                 </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box box-info"> 
                                    <div class="box-header with-border">
                                      <h3 class="box-title text-white">Tracking</h3>
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
                                            </tr>
                                        </thead>
                                          <tbody>
                                               <?php //print_r($awb_tracking_info);
                                                   if(isset($awb_tracking_info[$awbno])) { 
                                                   foreach($awb_tracking_info[$awbno] as $j=> $ls){
                                                ?> 
                                                <tr> 
                                                    <td class="text-center"><?php echo ($j + 1);?></td>  
                                                    <td><?php echo $ls['tracking_status']?></td>   
                                                    <td><?php echo $ls['city']?></td>  
                                                    <td><?php echo date('d M Y', strtotime($ls['status_date'])) .' ' . date('h:i a', strtotime($ls['status_time']))?></td>  
                                                    <td><?php echo $ls['remarks']?></td>  
                                                </tr>
                                                <?php
                                                    }
                                                    }
                                                ?>                                 
                                            </tbody>
                                      </table>
                                      </div>
                                 </div>
                                 <?php if($this->session->userdata('cr_logged_in')) {  ?>
                                 <div class="box box-success"> 
                                    <div class="box-header with-border">
                                      <h3 class="box-title text-white">Co-Loading Details</h3>
                                     </div>
                                    <div class="box-body">
                                        <table class="table table-hover table-bordered table-striped"> 
                                          <tbody>
                                               <?php 
                                                    //print_r($co_loader_info);
                                                   if(isset($co_loader_info[$awbno])) { 
                                                   foreach($co_loader_info[$awbno] as $fld => $ls){
                                                ?> 
                                                <tr> 
                                                    <td class="text-right text-capitalize"><strong><?php echo str_replace("_"," ", $fld);?></strong></td>  
                                                    <td class="text-left"><?php echo $ls; ?></td>  
                                                </tr>
                                                <?php
                                                    }
                                                    }
                                                ?>                                 
                                            </tbody>
                                      </table>
                                      </div>
                                 </div>
                                 <?php } ?>
                                 
                            </div>
                       </div>
                     </div>
                    </div> 
               </div>     
            </div>     
           
           <?php $q++; 
                 } ?>
           <?php } ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          &COPY; <?php echo date('Y');?> Courier Syndicate.
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content --> 
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url() ?>asset/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url() ?>asset/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
 
 
</body>
</html>
