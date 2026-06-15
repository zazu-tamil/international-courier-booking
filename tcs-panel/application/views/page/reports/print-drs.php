<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Manifest</title>
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
         
          <b>Delivery Run Sheet</b>  
         
      </div>
      <div class="col-xs-4 text-center">
        <h2> <em>Company Name</em> </h2>
      </div>
      <div class="col-xs-4">
        <span class="pull-left" ><img src="<?php echo base_url('asset/images/logo.png') ?>" class="img-responsive" width="80%" /></span>
        
      </div>
      <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-xs-4">
            <b>FR Name: </b><br />
            <b>FR Code: </b> 
        </div>
        <div class="col-xs-4">
            <b>Delivery Branch : </b> <?php echo $record_list[0]['branch_city_code']?><br /> 
            <b>Delivery By : </b> <?php echo $record_list[0]['delivery_by']?> 
        </div>
        <div class="col-xs-4">
            <b>DRS No :#<?php echo $record_list[0]['drs_no']?></b><br> 
            <b>Date :</b> <?php echo date('d-m-Y', strtotime($record_list[0]['drs_date']));?> 
        </div>
    </div>  

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-condensed table-bordered">
          <thead>
          <tr>
            <th>S.No</th>
            <th>Origin</th> 
            <th>Consignee's Name</th>  
            <th>Receiver's Details</th>  
            <th>No of Pcs</th>  
            <th>Weight</th>  
            <th class="text-center">Time</th> 
            <th class="text-center" width="30%">Signature/Co-Seal</th> 
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
            <td><?php echo $info['origin_state_code'] .' - ' . $info['origin_city_code'];?></td> 
            <td><?php echo $info['sender_company'];?><br /><?php echo $info['sender_name'] ;?></td> 
            <td><?php echo $info['receiver_company'];?><br /><?php echo $info['receiver_name'] ;?></td> 
            <td><?php echo $info['no_of_pieces'];?></td> 
            <td><?php echo $info['chargable_weight'];?></td> 
            <td rowspan="2"></td>  
            <td rowspan="2"></td>  
          </tr>
          <tr> 
            <td colspan="3"><strong>AWB # : </strong><?php echo $info['awbno'];?></td>
            <td colspan="3">Mobile: <?php echo $info['receiver_mobile'];?></td>
          </tr>
           <?php } ?>
           <tr>
              <td colspan="3">C/Ments Delivered: </td>  
              <td>FOD Amt: </td>
              <td colspan="5" rowspan="2"><strong>FR Code</strong> <span class="pull-right margin-r-5"><strong>Sign</strong></span></td>
           </tr>
           <tr> 
              <td colspan="3">C/Ments Un-delivered: </td>  
              <td>CVC Amt: </td> 
           </tr>
           <tr> 
              <td colspan="3">
                <div class="row">
                    <div class="col-md-6">Out Time : </div>
                    <div class="col-md-6">Kms : </div>
                </div>
              </td>  
              <td>Octroi/Others : </td> 
              <td colspan="5" rowspan="2"><strong>Supervisor's Name</strong> <span class="pull-right margin-r-5"><strong>Sign</strong></span></td>
           </tr>
           <tr> 
              <td colspan="3">
                <div class="row">
                    <div class="col-md-6">In Time : </div>
                    <div class="col-md-6">Kms : </div>
                </div>
              </td>  
              <td>Total Amt: </td> 
           </tr>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
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
