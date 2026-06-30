<!DOCTYPE html>
<html>
<head>
  <title>Print Label - <?php echo $shipment->awb_number; ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 10px;
      background: #fff;
    }
    .label-container {
      width: 400px;
      border: 3px solid #000;
      padding: 15px;
      margin: 0 auto;
      box-sizing: border-box;
    }
    .label-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }
    .logo-text {
      font-size: 20px;
      font-weight: bold;
    }
    .partner-name {
      font-size: 16px;
      font-weight: bold;
      background: #000;
      color: #fff;
      padding: 4px 8px;
    }
    .address-box {
      font-size: 12px;
      line-height: 1.4;
      border-bottom: 1px solid #000;
      padding-bottom: 8px;
      margin-bottom: 8px;
    }
    .address-title {
      font-weight: bold;
      text-transform: uppercase;
      font-size: 11px;
      margin-bottom: 2px;
    }
    .barcode-section {
      text-align: center;
      padding: 10px 0;
      border-bottom: 1px solid #000;
    }
    .barcode-img {
      width: 320px;
      height: 70px;
    }
    .awb-text {
      font-size: 14px;
      font-weight: bold;
      letter-spacing: 2px;
      margin-top: 5px;
    }
    .footer-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 12px;
      padding-top: 5px;
    }
    .weight-info {
      font-size: 14px;
      font-weight: bold;
    }
  </style>
</head>
<body onload="window.print()">

<?php foreach($boxes as $box): ?>
  <div class="label-container" style="page-break-after: always; margin-bottom: 20px;">
    <div class="label-header">
      <span class="logo-text"><?php echo defined('COMPANY_NAME') && COMPANY_NAME ? htmlspecialchars(COMPANY_NAME) : 'CourierSyndicate International'; ?></span>
      <span class="partner-name"><?php echo strtoupper($shipment->courier_partner_name); ?></span>
    </div>
    
    <div class="address-box">
      <div class="address-title">FROM (Sender):</div>
      <strong><?php echo $shipment->sender_name; ?></strong><br>
      <?php echo $shipment->sender_company ? $shipment->sender_company.'<br>' : ''; ?>
      <?php echo $shipment->sender_address; ?>, <?php echo $shipment->sender_city; ?>, <?php echo $shipment->sender_state; ?>, <?php echo $shipment->origin_country_name; ?> - <?php echo $shipment->sender_zip; ?><br>
      Phone: <?php echo $shipment->sender_mobile; ?>
    </div>

    <div class="address-box">
      <div class="address-title">TO (Consignee):</div>
      <strong><?php echo $shipment->receiver_name; ?></strong><br>
      <?php echo $shipment->receiver_company ? $shipment->receiver_company.'<br>' : ''; ?>
      <?php echo $shipment->receiver_address; ?>, <?php echo $shipment->receiver_city; ?>, <?php echo $shipment->receiver_state; ?>, <?php echo $shipment->dest_country_name; ?> - <?php echo $shipment->receiver_zip; ?><br>
      Phone: <?php echo $shipment->receiver_mobile; ?>
    </div>

    <div class="barcode-section">
      <!-- Renders Barcode PNG directly via Zend Barcode -->
      <img class="barcode-img" src="<?php echo site_url('shipment/barcode/' . $shipment->awb_number); ?>" alt="Code128 Barcode"><br>
      <div class="awb-text"><?php echo $shipment->awb_number; ?></div>
    </div>

    <div class="footer-section">
      <div>
        <div class="weight-info">CHARGE WT: <?php echo $shipment->chargeable_weight; ?> kg</div>
        <div>BOX <?php echo $box->box_number; ?> of <?php echo count($boxes); ?></div>
        <div>Booking Date: <?php echo date('d-m-Y', strtotime($shipment->booking_date)); ?></div>
      </div>
      <div>
        <!-- Renders QR code that opens public tracking page -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=<?php echo urlencode(site_url('tracking?awb=' . $shipment->awb_number)); ?>" style="width: 80px; height: 80px;" alt="QR Tracking Link">
      </div>
    </div>
  </div>
<?php endforeach; ?>

</body>
</html>
