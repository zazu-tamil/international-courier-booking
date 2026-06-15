<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Manifest</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="shortcut icon" href="" />
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>asset/dist/css/AdminLTE.min.css">
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    @media print{
       .noprint{
           display:none;
       }
    }
  </style>  
</head>
<body onload="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row"> 
      <div class="col-xs-4">
         
          <span class="pull-left" ><img src="<?php echo base_url('asset/images/logo.png') ?>" class="img-responsive" width="80%" /></span>  
         
      </div>
      <div class="col-xs-4 text-center">
        <h2> Manifest </h2>
      </div>
      <div class="col-xs-4">
         
        <b>No :#<?php echo $record_list[0]['manifest_no']?></b><br> 
        <b>Date :</b> <?php echo date('d-m-Y', strtotime($record_list[0]['manifest_date']));?><br> 
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-xs-6 invoice-col">
        From : <strong><?php echo $record_list[0]['from_city_code']?></strong>
         
      </div>
      <!-- /.col -->
      <div class="col-xs-6 invoice-col">
        To : <strong><?php echo $record_list[0]['to_city_code']?></strong>
        
      </div>
      <!-- /.col --> 
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-condensed table-bordered">
          <thead>
          <tr>
            <th>S.No</th>
            <th>AWB No</th> 
            <th>Destination</th>  
            <th>No of Pcs</th>
            <th>Weight</th> 
            <th width="40%">Remarks</th> 
          </tr>
          </thead>
          <tbody>
          <?php 
          $subtot = $tax_amt = $cod_charges = $fod_charges = $fov_charges = $fuel_charges = 0;
          foreach($record_list as $i => $info) 
          {
            /*$subtot += $info['sub_total'];
            $tax_amt += $info['tax_amt'];
            $cod_charges += $info['cod_charges']; 
            $fod_charges += $info['fod_charges']; 
            $fov_charges += $info['fov_charges']; 
            $fuel_charges += $info['fuel_charges']; */
          ?>
          <tr>
            <td><?php echo ($i + 1);?></td> 
            <td><?php echo $info['awbno']?></td> 
            <td><?php echo $info['dest_pincode'];?><br /><?php echo $info['dest_state_code'] . ' - ' . $info['dest_city_code'];?></td> 
            <td class="text-right"><?php echo $info['no_of_pieces'];?></td> 
            <td class="text-right"><?php echo $info['chargable_weight']?></td>
            <td class="text-right"></td>  
          </tr>
           <?php } ?>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
       <div class="col-xs-4">
            <b>Despatched By: <?php echo $record_list[0]['despatch_by']?></b>
       </div>
       <div class="col-xs-4">
            <b>Received By: <?php echo $record_list[0]['received_by']?></b>
       </div>
       <div class="col-xs-4">
            <b>Received Date: <?php if(!empty($record_list[0]['received_date'])) echo date('d-m-Y', strtotime($record_list[0]['received_date']));?></b>
       </div>
    </div>
    <div class="text-center noprint">
        <button class="btn btn-info" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
