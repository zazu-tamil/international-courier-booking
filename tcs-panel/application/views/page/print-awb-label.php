<?php
/*	echo "<pre>";
    print_r($label);
	echo "</pre>";
    */
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AWB Label</title>
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
     
    .wrapper{ 
        border:0px solid black;
        padding: 0px;
    }
    .invoice { 
        border:2px solid black;
    }
    .border-right{
         border-right:1px solid black;
    }
    .border-left{
         border-left:1px solid black;
    }
    .border-top{
         border-top:1px solid black;
    }
    
    .border-bottom{
         border-bottom:1px solid black;
    }
     
    
  </style>  
</head>
<body onload="window.print();">
<div class="wrapper" > 
    <div class="container"> 
        <table class="table table-bordered " style="width:80%;">
            <tr>
                 <td class="text-center" width="50%"><img src="<?php echo base_url('asset/images/logo.png')?>" alt="" width="80%"/></td>
                 <td class="text-center"><h2>AWB No: <br /><?php echo $label['awbno'];?></h2></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center"><h2><?php echo $label['origin_city_code'];?> <i class="fa  fa-long-arrow-right"></i> <?php echo $label['dest_city_code'];?></h2></td>
            </tr>
            <tr> 
                <td><h4>No of Pcs: <?php echo $label['no_of_pieces'];?></h4></td>
                <td><h4>Weight: <?php echo $label['weight'];?>Kgs</h4><h4>Dimension: <?php echo $label['length'];?>X<?php echo $label['width'];?>X<?php echo $label['height'];?></h4></td>
            </tr>
             <tr>
                 <td>
                 <h4>Sender:<br /> 
                    <?php echo $label['sender_company'];?><br />
                    <?php echo $label['sender_name'];?><br />
                    <?php echo $label['sender_mobile'];?><br />
                    <?php echo str_replace("\n","<br>",$label['sender_address']) ;?> 
                 </h4>
                 </td>
                  <td>
                 <h4>Receiver:<br /> 
                    <?php echo $label['receiver_company'];?><br />
                    <?php echo $label['receiver_name'];?><br /> 
                    <?php echo $label['receiver_mobile'];?><br /> 
                    <?php echo str_replace("\n","<br>",$label['receiver_address']) ;?>
                 </h4>
                 </td>
            </tr> 
        </table> 
    </div>
 <!-- <section class="invoice border-left border-right" style=" width:60%;">
        <div class="row">
            <div class="col-xs-6 text-center border-right border-bottom"><img src="<?php echo base_url('asset/images/logo.png')?>" alt="" width="50%"/></div>
            <div class="col-xs-6 text-center border-bottom"><h2>AWB No: <?php echo $label['awbno'];?></h2></div>
        </div>
        <div class="row text-center ">
            <div class="col-xs-12 text-uppercase border-bottom"><h2><?php echo $label['origin_city_code'];?> <i class="fa  fa-long-arrow-right"></i> <?php echo $label['dest_city_code'];?></h2></div>
        </div>
        <div class="row text-center"> 
            <div class="col-xs-4 border-right border-bottom"><h4>No of Pcs: <?php echo $label['no_of_pieces'];?></h4></div>
            <div class="col-xs-4 border-right border-bottom"><h4>Weight: <?php echo $label['weight'];?>Kgs</h4></div>
            <div class="col-xs-4 border-bottom"><h4>Dimention: <?php echo $label['length'];?>X<?php echo $label['width'];?>X<?php echo $label['height'];?></h4></div>
        </div>
        <div class="row ">
             <div class="col-xs-6 border-right">
             <h4>Sender:<br /> 
                <?php echo $label['sender_company'];?><br />
                <?php echo $label['sender_name'];?><br />
                <?php echo $label['sender_address'];?><br />
             </h4>
             </div>
              <div class="col-xs-6">
             <h4>Receiver:<br /> 
                <?php echo $label['receiver_company'];?><br />
                <?php echo $label['receiver_name'];?><br />
                <?php echo $label['receiver_address'];?><br />
             </h4>
             </div>
        </div> 
  </section>  -->
  
</div> 
</body>
</html>
