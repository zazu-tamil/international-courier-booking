<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Customer Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
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
<section class="invoice">
<table class="table table-bordered p-2"> 
     <thead>
        <tr>
            <th colspan="9" class="text-center"><strong style="font-size: 22px;">Invoice</strong></th>
        </tr> 
        <tr>
            <td width="30%" colspan="3">
                <address>
                  <strong style="font-size: 22px;">Ajmeer Traders</strong><br> 
                  Kollam,<br>
                  Kerala-691506. <br>
                  Email:support@couriersyndicate.co.in<br />
                  GST: <strong>32AEYPN6058B2ZA</strong>
                  
                </address>
            </td>
            <td width="35%" colspan="3">
                Bill To,<br />
                <address>
                  <strong><?php echo $record_list['customer']?></strong><br>
                  <?php echo str_replace(',',',<br>', $record_list['address']);?><br>
                  Email: <?php echo $record_list['email']?><br>
                  Phone: <?php echo $record_list['phone']?> - Mobile: <?php echo $record_list['mobile']?><br />
                  GST: <?php echo $record_list['gst_no']?>
                </address>
            </td>
            <td width="30%" colspan="3">
                 <b>Invoice #<?php echo $record_list['invoice_no']?></b><br> 
                 <b>Invoice Date :</b> <?php echo date('d-m-Y', strtotime($record_list['invoice_date']));?><br> 
                 <hr />
                 <img src="<?php echo base_url('asset/images/logo.png') ?>" class="img-responsive" width="50%" />
            </td>
        </tr> 
        
        <tr>
            <th>S.No</th>
            <th>AWB No</th>
            <th>Date</th>
            <th>Origin</th> 
            <th>Destination</th> 
            <th>Cust.Ref.no</th> 
            <th>No of Pcs</th>
            <th>Weight</th> 
            <th>Amount</th> 
        </tr> 
        </thead> 
        <tbody> 
                  <?php 
                  $subtot = $tax_amt = $cod_charges = $fod_charges = $fov_charges = $fuel_charges = 0;
                  foreach($bill_list as $i => $info) 
                  {
                    $subtot += $info['rate'];
                    $tax_amt += $info['tax_amt'];
                    $cod_charges += $info['cod_charges']; 
                    $fod_charges += $info['fod_charges']; 
                    $fov_charges += $info['fov_charges']; 
                    $fuel_charges += $info['fuel_charges']; 
                  ?>
                  <tr>
                    <td><?php echo ($i + 1);?></td> 
                    <td><?php echo $info['awbno']?></td>
                    <td><?php echo date('d-m-Y', strtotime($info['booking_date'])); ?></td>
                    <td><?php echo $info['origin_pincode'];?><br /><?php echo $info['origin_state_code'] . ' - ' . $info['origin_city_code'];?></td> 
                    <td><?php echo $info['dest_pincode'];?><br /><?php echo $info['dest_state_code'] . ' - ' . $info['dest_city_code'];?></td> 
                    <td class="text-center"><?php echo $info['customer_ref_no'];?></td> 
                    <td class="text-right"><?php echo $info['no_of_pieces'];?></td> 
                    <td class="text-right"><?php echo $info['chargable_weight']?></td>
                    <td class="text-right"><?php echo number_format($info['rate'],2);?></td>  
                  </tr>
                   <?php } ?> 
        <tr>
            <td colspan="6" rowspan="8">
                <p class="lead">Terms & Conditions</p>
         
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; text-transform:capitalize;"> 
        
                1. Please Make Payment By Cash/ Rtgs/ Neft  Only.<br />
                
                2. The  Payment Should Be Transferred To  The Account Under   The Name Of "Ajmeer Traders".<br /> A/C Number : 920020060275454.<br /> IFSC Code :UTIB0001460,<br />Axis Bank - Kottarakkara.<br />
                
                3. The Payment Should Be Made Within 7 Days From The Date Of Billing. 
                </p>
            </td>
            <td colspan="2"><strong>Subtotal</strong></td>
            <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($subtot,2);?></td>
        </tr>
        <tr>
             <td colspan="2"><strong>COD Charges</strong></td>
             <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($cod_charges,2);?></td>
        </tr>
        <tr>
             <td colspan="2"><strong>FOD Charges</strong></td>
             <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($fod_charges,2);?></td>
        </tr>
        <tr>
             <td colspan="2"><strong>FOV Charges</strong></td>
             <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($fov_charges,2);?></td>
        </tr>
        <tr>
             <td colspan="2"><strong>Fuel Surcharges</strong></td>
             <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($fuel_charges,2);?></td>
        </tr>
        <?php if($record_list['state_code'] != 'KL') {?>
        <tr>
             <td colspan="2"><strong>IGST (18%)</strong></td>
             <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format($tax_amt,2);?></td>
        </tr>
         <?php } else {?>
        <tr>
             <td colspan="2"><strong>CGST (9%)</strong></td>
             <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format(($tax_amt /2),2);?></td>
        </tr>
        <tr>
             <td colspan="2"><strong>SGST (9%)</strong></td>
             <td class="text-right"><i class="fa fa-rupee"></i> <?php echo number_format(($tax_amt /2),2);?></td>
        </tr>
        <?php } ?>
        <tr>
             <td colspan="2"><strong>Total</strong></td>
             <td class="text-right"><i class="fa fa-rupee"></i> <strong><?php echo number_format((($subtot + $cod_charges + $fod_charges + $fov_charges + $fuel_charges) + $tax_amt),2);?></strong></td>
        </tr> 
    </tbody>
</table>
<div class="text-center noprint">
    <a href="<?php echo site_url('customer-invoice-list')?>" class="btn btn-info" >Back to Invoice List</a>
</div>
</section> 
</body>
</html>
