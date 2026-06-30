<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Shipment Tracking Gateway | <?php echo defined('COMPANY_NAME') && COMPANY_NAME ? htmlspecialchars(COMPANY_NAME) : 'CourierSyndicate International'; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('asset/time-line.css'); ?>">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background: #f4f6f9;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .tracking-header {
      background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
      color: #fff;
      padding: 60px 0;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .tracking-search-box {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      margin-top: -30px;
      border: 1px solid #eee;
    }
    .timeline-container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      margin-top: 25px;
      border: 1px solid #eee;
    }
    .btn-primary {
      background: #3c8dbc;
      border: none;
      border-radius: 8px;
      padding: 12px 20px;
      font-weight: 600;
    }
    .btn-primary:hover {
      background: #2a688d;
    }
    .form-control {
      border-radius: 8px;
      padding: 12px 15px;
      height: auto;
      border: 1px solid #ddd;
    }
    
    /* Timeline CSS */
    .timeline { position: relative; margin: 0 0 30px 0; padding: 0; list-style: none; }
    .timeline::before { content: ''; position: absolute; top: 0; bottom: 0; width: 4px; background: #ddd; left: 31px; margin: 0; border-radius: 2px; }
    .timeline > li { position: relative; margin-right: 10px; margin-bottom: 15px; clear: both; }
    .timeline > li::before, .timeline > li::after { content: " "; display: table; }
    .timeline > li::after { clear: both; }
    .timeline > li > .timeline-item { box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1); border-radius: 3px; margin-top: 0; background: #f9f9f9; border: 1px solid #eee; color: #444; margin-left: 60px; margin-right: 15px; padding: 0; position: relative; }
    .timeline > li > .timeline-item > .time { color: #999; float: right; padding: 10px; font-size: 12px; }
    .timeline > li > .timeline-item > .timeline-header { margin: 0; color: #555; border-bottom: 1px solid #f4f4f4; padding: 10px; font-size: 16px; line-height: 1.1; }
    .timeline > li > .timeline-item > .timeline-body { padding: 10px; }
    .timeline > li > .fa { width: 30px; height: 30px; font-size: 15px; line-height: 30px; position: absolute; color: #666; background: #d2d6de; border-radius: 50%; text-align: center; left: 18px; top: 0; }
    .timeline > li > .fa.bg-blue { background-color: #3c8dbc !important; color: #fff !important; }
    .timeline > li > .fa.bg-gray { background-color: #d2d6de !important; color: #444 !important; }
  </style>
</head>
<body>

<div class="tracking-header">
  <div class="container">
    <h1 style="font-weight: 700; margin: 0;"><i class="fa fa-paper-plane-o"></i> CourierSyndicate</h1>
    <p style="color: rgba(255,255,255,0.7); font-size: 16px; margin-top: 10px;">Global Freight & Consignment Tracking Gateway</p>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      
      <!-- Search Input box -->
      <div class="tracking-search-box">
        <form action="<?php echo site_url('tracking'); ?>" method="GET">
          <div class="form-group" style="margin-bottom: 0;">
            <label style="font-size: 15px; font-weight: 600; color: #444; margin-bottom: 10px;">Enter AWB Number or Customer Mobile Number:</label>
            <div class="input-group">
              <input type="text" name="awb" class="form-control" placeholder="e.g. 41000001" value="<?php echo htmlspecialchars($awb); ?>" required>
              <span class="input-group-btn">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Track Cargo</button>
              </span>
            </div>
          </div>
        </form>
      </div>

      <!-- Error warning -->
      <?php if(isset($error)): ?>
        <div class="alert alert-danger text-center" style="margin-top: 20px; border-radius: 8px;">
          <i class="fa fa-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <!-- Results details -->
      <?php if($shipment): ?>
        <div class="timeline-container">
          <h3 style="font-weight: 700; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px;">
            Tracking Summary: <strong><?php echo $shipment->awb_number; ?></strong>
          </h3>
          
          <div class="row" style="margin-bottom: 25px; font-size: 14px;">
            <div class="col-xs-6">
              <p><strong>Origin:</strong> <?php echo $shipment->origin_country_name; ?></p>
              <p><strong>Destination:</strong> <?php echo $shipment->dest_country_name; ?></p>
            </div>
            <div class="col-xs-6 text-right">
              <p><strong>Booking Date:</strong> <?php echo date('d M Y', strtotime($shipment->booking_date)); ?></p>
              <p><strong>Current Status:</strong> <span class="label label-info" style="font-size: 12px;"><?php echo $shipment->status; ?></span></p>
            </div>
          </div>

          <h4 style="font-weight: 700; margin-bottom: 20px;"><i class="fa fa-map-marker text-blue"></i> Movement History Timeline</h4>
          
          <ul class="timeline">
            <?php foreach($timeline as $t): ?>
              <li>
                <i class="fa fa-circle bg-blue"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> <?php echo date('d M Y H:i', strtotime($t->date_time)); ?></span>
                  <h3 class="timeline-header"><span class="label label-info"><?php echo $t->status; ?></span> at <strong><?php echo $t->location; ?></strong></h3>
                  <div class="timeline-body"><?php echo $t->remarks; ?></div>
                </div>
              </li>
            <?php endforeach; ?>
            <li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
          </ul>
        </div>
      <?php endif; ?>

      <div class="text-center" style="margin-top: 40px; margin-bottom: 40px;">
        <a href="<?php echo site_url('login'); ?>" class="text-muted"><i class="fa fa-sign-in"></i> Sign In to Portal Dashboard</a>
      </div>

    </div>
  </div>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
