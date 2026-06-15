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
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
        <span class="pull-right" ><img src="<?php echo base_url('asset/images/logo.png') ?>" class="img-responsive" width="80%" /></span>
          <i class="fa fa-globe"></i>
          
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>Company Name </strong><br>
          Door No,<br>
          Street Name,<br>
          City.<br> 
          Email: info@test.in <br />
          Phone : 000 - 00000 <br />
          GST : XXXXX.
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        <address>
          <strong><?php echo $bill_list[0]['contact_person']?></strong><br>
          <?php echo str_replace("\n",'<br>', $bill_list[0]['address']);?><br> 
          Mobile: <?php echo $bill_list[0]['mobile']?> 
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Invoice #<?php echo $bill_list[0]['invoice_no']?></b><br> 
        <b>Invoice Date :</b> <?php echo date('d-m-Y', strtotime($bill_list[0]['invoice_date']));?><br> 
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-bordered table-striped">
                    <thead>
                    <tr class="bg-blue-gradient">
                        <th>SNo</th>
                        <th>AWB No</th>
                        <th>Date</th>
                        <th>Origin</th> 
                        <th>Destination</th> 
                        <th>No of Pcs</th>
                        <th>Weight</th> 
                        <th>TS</th> 
                        <th>FOD</th> 
                        <th>COD</th> 
                        <th>ODA</th> 
                        <th>Amount</th>  
                    </tr> 
                    </thead>
                    <tbody>
                        <?php 
                            $subtot = $tot['no_of_pieces'] = $tot['weight'] = $tot['amt'] = 0;
                        foreach($bill_list as $j => $info) { 
                          $tot['no_of_pieces'] += $info['no_of_pieces'];  
                          $tot['weight'] += $info['chargable_weight'];  
                          $tot['amt'] += $info['ts_amount'];  
                        ?>
                        <tr>
                            <td><?php echo ($j+1)?></td>
                            <td><?php echo $info['awb_no']?></td>
                            <td><?php echo date('d-m-Y' , strtotime($info['booking_date'])); ?></td>
                            <td><?php echo $info['origin_pincode'];?><br /><?php echo $info['origin_state_code'] . ' - ' . $info['origin_city_code'];?></td> 
                            <td><?php echo $info['dest_pincode'];?><br /><?php echo $info['dest_state_code'] . ' - ' . $info['dest_city_code'];?></td> 
                            <td class="text-right"><?php echo $info['no_of_pieces'];?></td> 
                            <td class="text-right"><?php echo $info['chargable_weight']?></td> 
                            <td class="text-right"><?php echo number_format($info['ts_amt'],2);?></td>   
                            <td class="text-right"><?php echo number_format($info['fod'],2);?></td>   
                            <td class="text-right"><?php echo number_format($info['cod'],2);?></td>   
                            <td class="text-right"><?php echo number_format($info['oda'],2);?></td>   
                            <td class="text-right"><?php echo number_format($info['ts_amount'],2);?></td>   
                        </tr>
                        <?php } ?>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="5">Total</th>
                                <th class="text-right"><?php echo number_format($tot['no_of_pieces'],0)?></th>
                                <th class="text-right"><?php echo number_format($tot['weight'],3)?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-right"><?php echo number_format($tot['amt'],2)?></th> 
                            </tr>
                        </tfoot>
                    </tbody>
                     
                </table>  
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-6">
        <p class="lead"><strong>Terms & Conditions</strong></p>
         
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; text-transform:uppercase;"> 

        1. Please Make Payment By Cash/ Rtgs/ Neft  Only.<br />
        
        2. The  Payment Should Be Transferred To The Account Under The Name Of <br /> "<strong>XXXXX</strong>".<br /><b>A/C Number : XXXX.<br /> Ifsc Code :XXXXX,<br /> XXXXX.<br /> XXXXX.</b><br />
        
        3. The Payment Should Be Made Within 7 Days From The Date Of Billing.<br />
        
        4. In Case The  Payment Is Not Made Within 15 Days From The Date Of Billing,  Interest Will Be Charged At The Rate Of  21 % Per Annum.<br />
        </p>
      </div>
      <!-- /.col -->
      <div class="col-xs-6 text-right"> 

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal</th>
              <td><i class="fa fa-rupee"></i> <?php echo number_format($tot['amt'],2);?></td>
            </tr>  
            <?php if($bill_list[0]['fr_state_code'] != 'TN') {?>
            <tr>
              <th>IGST (18%)</th>
              <td><i class="fa fa-rupee"></i> <?php echo number_format(($tot['amt'] * 18 /100),2);?></td>
            </tr> 
            <?php } else {?>
            <tr>
              <th>CGST (9%)</th>
              <td><i class="fa fa-rupee"></i> <?php echo number_format((($tot['amt'] * 18 /100) /2),2);?></td>
            </tr> 
            <tr>
              <th>SGST (9%)</th>
              <td><i class="fa fa-rupee"></i> <?php echo number_format((($tot['amt'] * 18 /100)/2),2);?></td>
            </tr> 
            <?php } ?>
            <tr>
              <th>Total:</th>
              <td><i class="fa fa-rupee"></i> <strong><?php echo number_format(($tot['amt'] + ($tot['amt'] * 18 /100)),2);?></strong></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <div class="text-center noprint">
        <a href="<?php echo site_url('ts-invoice-list')?>" class="btn btn-info" >Back to Invoice List</a>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
