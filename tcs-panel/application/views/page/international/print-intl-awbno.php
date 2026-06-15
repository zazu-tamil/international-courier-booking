<?php
   /*	 echo "<pre>";
     print_r($label);
	 echo "</pre>";  */
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $label['country'] .'-'. $label['awbno']; ?></title>
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
  <script src="<?php echo base_url() ?>asset/bower_components/jquery/dist/jquery.min.js"></script>
  <!--<script src="<?php echo base_url() ?>asset/bower_components/jquery.excelexportjs.js"></script>-->
  <script src="<?php echo base_url() ?>asset/bower_components/excelexport.js"></script>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    @media print{
       .noprint{
           display:none;
       }
       
        body {
          overflow-y: hidden; /* Hide vertical scrollbar */
          overflow-x: hidden; /* Hide horizontal scrollbar */
        }
    }
    i{font-size: 12px;}
    #sts tr {border: 1px solid black;}
    #content-table td {border: 1px solid black;}
    
    .div-txt {
      writing-mode: vertical-lr; 
      display: inline-block;
     
      margin: 5px;
    }
    
    
    .div-txt .b {
      text-orientation: upright;
    }
  </style>  
<script>
jQuery(function($) {  
 
 
 /*$(".btnexp1").click(function(){
    
   
   //alert($(this).attr('id'));
   $("#mtc").excelexportjs({
                containerid: "mtc"
               , datatype: 'table'
               , worksheetName: 'MJP Test Cerificate'
   });
   
 }); */
 
    $(".btnexp").click(function() {    
    
    var ee = excelExport("content-table").parseToCSV().parseToXLS('Courier Syndicate - AWB').; 
		location.href = ee.getXLSDataURI();
	});

});
</script> 
</head>
<body onload="window.print();" style="font-size: 12px;">
 
<div class="wrapper1">
  <!-- Main content -->
  <section class="invoice">
    
    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12" id="mtc">
            <table class="table table-condensed table-bordered " id="content-table"> 
                <tr>
                    <td class="text-center" width="33%" colspan="4"><img src="<?php echo base_url('asset/images/logo.png')?>" alt="" width="40%"/></td>
                    <td colspan="3" class="text-center"><strong>www.couriersyndicate.co.in</strong><br /><strong>support@couriersyndicate.co.in</strong></td>
                    <td colspan="3" class="text-center">
                        <?php if(file_exists($barcode)) { ?>
                        <img src="<?php echo base_url($barcode);?>" alt="" class="img-fluid" />
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-center">Account</td>
                    <td class="text-center">CS_SWC</td>
                    <td class="text-center"></td>
                    <td colspan="3" class="text-center"><strong>AWB: <?php echo $label['awbno'];?></strong></td>
                    <td class="text-center"  width="5%" >6</td>
                    <td colspan="2" class="text-center">TYPE OF SERVICE</td>
                </tr>
                <tr>
                    <td rowspan="5" class="text-center"><div class="div-txt b1 " style="text-orientation: upright;"><strong>SENDER</strong></div></td>
                    <td colspan="3" class="text-center">FROM SHIPPER</td>
                    <td class="text-center" width="5%">4</td>
                    <td colspan="2" class="text-center">TO RECEIVER</td>
                    <td colspan="3" rowspan="1" class="text-center">
                        <?php echo $label['international_service_provider'];?>  
                        <strong><?php echo $label['country'];?></strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">SENDER : <?php echo $label['sender_name'];?></td>
                    <td rowspan="4" class="text-center"><div class="div-txt b1 " style="text-orientation: upright;"><strong>RECEIVER</strong></div></td>
                    <td colspan="2">RECEIVER : <?php echo $label['receiver_name'];?></td> 
                    <td colspan="3">DESCRIPTION OF CONTENTS</td>
                </tr>
                <tr>
                    <td colspan="3" rowspan="3">ADDRESS: <br /><?php echo $label['sender_address'];?><br />Mob:<?php echo $label['sender_mobile'];?></td>
                    <td rowspan="3" colspan="2">ADDRESS: <br /><?php echo $label['receiver_address'];?><br />Mob:<?php echo $label['receiver_mobile'];?></td>
                    <td colspan="3"><?php echo $label['description_of_goods'];?></td>
                </tr>
                <tr> 
                    <td colspan="3">SPECIAL INSTRUCTION</td>
                </tr>
                <tr> 
                    <td colspan="3">---</td>
                </tr> 
                <tr>
                    <td class="text-center">2</td>
                    <td class="text-center">CUSTOMER REF</td>
                    <td colspan="2" class="text-center">ALT REFERENCE</td>
                    <td class="text-center">5</td>
                    <td colspan="2" class="text-center">DUTIES/TAXES/VALUE/CODE NOS</td> 
                    <td class="text-center">7</td>
                    <td colspan="2" class="text-center">SIZE & WEIGHT</td>
                </tr>
                <tr>
                    <td class="text-center"></td>
                    <td class="text-center">CWS</td>
                    <td colspan="2" class="text-center">-</td>
                    <td colspan="2" rowspan="2" class="text-center">VALUE DECLARED FOR <br />CUSTOMS PURPOSE</td>
                    <td class="text-left">Amount : <?php //echo $label['commodity_invoice_value'];?></td> 
                    <td class="text-center">NO.OF PCS</td> 
                    <td class="text-center">CHARGEABLE WEIGHT</td>  
                    <td class="text-center">AMOUNT</td>  
                </tr>
                <tr>
                    <td>3</td>
                    <td colspan="3">SHIPPER'S AGREEMENT AND SIGNATURE</td>
                    <td>Currency : INR</td>
                    <td  class="text-center"><?php echo $label['no_of_pieces'];?></td>
                    <td class="text-center"><?php echo $label['package_weight'];?> Kgs</td>
                    <td class="text-center"><?php echo $label['tot_charges'];?></td>
                </tr>
                <tr>
                    <td colspan="4">
                        Received for COURIER SYNDICATE
                        <br />
                        <br />
                        DATE : <?php echo date('d-m-Y', strtotime($label['booking_date']));?> <div class="pull-right" >TIME : <?php echo date('h A', strtotime($label['booking_date']. ' ' . $label['booking_time']));?></div>
                    </td>
                    <td colspan="3">CODE NOS/VAT/GST/HS NOS.ECT <br /> PROOF OF DELIVERYFOR CLEARANCE/DUTIES</td>
                    <td colspan="3">
                        PROOF OF DELIVERY
                        <br />
                        <br />
                        <br />
                         SIGNATURE
                          
                    </td>
                </tr>
                <tr>
                    <td colspan="4">Terms & Condition :
                        <br />
                            1) Maximum liability for lost of shipment is INR 1000 or invoice value whichever lower.<br />
                            2) Insurance available at additional cost.<br />
                            3) We are authorized to digitalize the invoice for Customs declaration purposes.
                    </td>
                    <td colspan="3">
                        <table class="table table-condensed" style="font-size: 10px;">
                            <tr>
                                <td>1</td>
                                <td>DESCRIPTION</td>
                                <td>CODE NO.</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">CUSTOMS DUTY (AMT)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">LOCAL TAXES (AMT)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">OTHER- Pls Specify</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td colspan="3">All customs duties/taxes payable by consignee</td>
                            </tr>
                        </table>
                    
                    </td>
                    <td colspan="3" >  
                        <br /> 
                         NAME
                         <br /><br />
                         DATE: ........../............/............ <br /><br />
                         TIME: ............ A.M/P.M 
                    </td>
                </tr>
            </table>
            
             
        </div>
    </div>
     

     
    <div class="row text-center noprint">
        <div class="col-md-12">
            <a href="<?php echo site_url('international-consignment-list')?>" class="btn btn-info" >Back to Consignment List</a> 
        </div>
        
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
