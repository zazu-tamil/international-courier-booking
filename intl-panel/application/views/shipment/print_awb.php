<!DOCTYPE html>
<html>
<head>
  <title>Air Waybill (AWB) Document - <?php echo $shipment->awb_number; ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 30px;
      color: #333;
      background: #fff;
    }
    .awb-container {
      max-width: 800px;
      margin: 0 auto;
      border: 2px solid #000;
      padding: 25px;
    }
    .header-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      border-bottom: 3px double #000;
      padding-bottom: 15px;
    }
    .header-table td {
      vertical-align: middle;
    }
    .document-title {
      font-size: 22px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .company-logo-text {
      font-size: 18px;
      font-weight: bold;
      text-align: right;
    }
    .company-sub {
      font-size: 11px;
      color: #555;
      text-align: right;
      line-height: 1.4;
    }
    .meta-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .meta-table td {
      border: 1px solid #000;
      padding: 8px;
      font-size: 12px;
    }
    .meta-table th {
      background-color: #f2f2f2;
      border: 1px solid #000;
      padding: 8px;
      font-size: 12px;
      font-weight: bold;
      text-align: left;
    }
    .address-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .address-table td {
      width: 50%;
      border: 1px solid #000;
      padding: 12px;
      vertical-align: top;
      font-size: 12px;
      line-height: 1.5;
    }
    .section-title {
      font-size: 13px;
      font-weight: bold;
      text-transform: uppercase;
      background-color: #f2f2f2;
      border: 1px solid #000;
      padding: 6px 10px;
      margin-top: 20px;
      margin-bottom: 8px;
    }
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .details-table th, .details-table td {
      border: 1px solid #000;
      padding: 8px;
      font-size: 11px;
      text-align: left;
    }
    .details-table th {
      background-color: #fafafa;
    }
    .terms-box {
      border: 1px solid #aaa;
      background-color: #fcfcfc;
      padding: 12px;
      font-size: 10px;
      line-height: 1.5;
      text-align: justify;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    .signature-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }
    .signature-table td {
      width: 50%;
      border: none;
      vertical-align: bottom;
      font-size: 12px;
    }
    .barcode-wrapper {
      text-align: center;
      padding: 10px;
    }
    .barcode-img {
      width: 250px;
      height: 55px;
    }
  </style>
</head>
<body onload="window.print()">

<div class="awb-container">
  
  <!-- Header block -->
  <table class="header-table">
    <tr>
      <td style="width: 33%; text-align: left;">
        <div class="document-title">Air Waybill (AWB)</div>
        <div style="margin-top: 5px;">
          <img src="<?php echo site_url('shipment/barcode/' . $shipment->awb_number); ?>" style="width: 180px; height: 38px;" alt="Barcode"><br>
          <span style="font-size: 8px; font-weight: bold; letter-spacing: 1px; display: block; margin-top: 2px;"><?php echo $shipment->awb_number; ?></span>
        </div>
      </td>
      <td style="width: 34%; text-align: center;">
        <?php if(defined('COMPANY_LOGO') && !empty(COMPANY_LOGO)): ?>
          <img src="<?php echo base_url('assets/img/' . COMPANY_LOGO); ?>" alt="Company Logo" style="max-height: 70px;">
        <?php endif; ?>
      </td>
      <td style="width: 33%; text-align: right;">
        <div class="company-logo-text"><?php echo defined('COMPANY_NAME') && COMPANY_NAME ? htmlspecialchars(COMPANY_NAME) : 'CourierSyndicate International'; ?></div>
        <div class="company-sub">
          <?php echo defined('COMPANY_ADDRESS') && COMPANY_ADDRESS ? nl2br(htmlspecialchars(COMPANY_ADDRESS)) : 'HQ Origin branch Office'; ?><br>
          <?php echo defined('COMPANY_EMAIL') && COMPANY_EMAIL ? htmlspecialchars(COMPANY_EMAIL) : 'billing@couriersyn.com'; ?>
        </div>
      </td>
    </tr>
  </table>

  <!-- AWB Meta Grid -->
  <table class="meta-table">
    <tr>
      <th style="width: 20%;">AWB Number:</th>
      <td style="width: 30%; font-size: 14px;"><strong><?php echo $shipment->awb_number; ?></strong></td>
      <th style="width: 20%;">Booking Date:</th>
      <td style="width: 30%;"><?php echo date('d M Y', strtotime($shipment->booking_date)); ?></td>
    </tr>
    <tr>
      <th>Service Type:</th>
      <td><span style="text-transform: uppercase; font-weight: bold;"><?php echo $shipment->service_type; ?></span></td>
      <th>Courier Partner:</th>
      <td><?php echo $shipment->courier_partner_name; ?></td>
    </tr>
    <tr>
      <th>Charge Weight:</th>
      <td><strong><?php echo $shipment->chargeable_weight; ?> kg</strong></td>
      <th>Declared Value:</th>
      <td><strong>₹<?php echo number_format($shipment->total_declared_value, 2); ?></strong></td>
    </tr>
  </table>

  <!-- Addresses side by side -->
  <table class="address-table">
    <tr>
      <td>
        <strong style="text-transform: uppercase; font-size: 11px; color: #555; display: block; margin-bottom: 5px; border-bottom: 1px solid #ccc; padding-bottom: 2px;">FROM (Exporter/Shipper):</strong>
        <strong><?php echo $shipment->sender_name; ?></strong><br>
        <?php echo $shipment->sender_company ? $shipment->sender_company.'<br>' : ''; ?>
        <?php echo $shipment->sender_address; ?><br>
        <?php echo $shipment->sender_city; ?>, <?php echo $shipment->sender_state; ?>, <?php echo $shipment->origin_country_name; ?> - <?php echo $shipment->sender_zip; ?><br>
        Phone: <?php echo $shipment->sender_mobile; ?><br>
        Email: <?php echo $shipment->sender_email; ?>
      </td>
      <td>
        <strong style="text-transform: uppercase; font-size: 11px; color: #555; display: block; margin-bottom: 5px; border-bottom: 1px solid #ccc; padding-bottom: 2px;">TO (Consignee/Receiver):</strong>
        <strong><?php echo $shipment->receiver_name; ?></strong><br>
        <?php echo $shipment->receiver_company ? $shipment->receiver_company.'<br>' : ''; ?>
        <?php echo $shipment->receiver_address; ?><br>
        <?php echo $shipment->receiver_city; ?>, <?php echo $shipment->receiver_state; ?>, <?php echo $shipment->dest_country_name; ?> - <?php echo $shipment->receiver_zip; ?><br>
        Phone: <?php echo $shipment->receiver_mobile; ?><br>
        Email: <?php echo $shipment->receiver_email; ?>
      </td>
    </tr>
  </table>

  <!-- Package dimension slabs -->
  <div class="section-title"><i class="fa fa-cubes"></i> Consignment Box Package Details</div>
  <table class="details-table">
    <thead>
      <tr>
        <th style="width: 15%;">Box No</th>
        <th>Dimensions (L x W x H)</th>
        <th style="width: 25%;">Volumetric Weight</th>
        <th style="width: 25%;">Actual Weight</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($boxes as $box): ?>
        <tr>
          <td>Box <?php echo $box->box_number; ?></td>
          <td><?php echo $box->length; ?> x <?php echo $box->width; ?> x <?php echo $box->height; ?> cm</td>
          <td><?php echo $box->volumetric_weight; ?> kg</td>
          <td><?php echo $box->actual_weight; ?> kg</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Declared items CN list -->
  <div class="section-title"><i class="fa fa-list"></i> Itemized Goods Declaration</div>
  <table class="details-table">
    <thead>
      <tr>
        <th>Description of Goods</th>
        <th style="width: 20%;">HS Code</th>
        <th style="width: 10%;">Qty</th>
        <th style="width: 20%; text-align: right;">Unit Value</th>
        <th style="width: 20%; text-align: right;">Subtotal Value</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($items as $item): ?>
        <tr>
          <td><?php echo $item->item_description; ?></td>
          <td><code><?php echo $item->hs_code; ?></code></td>
          <td><?php echo $item->quantity; ?></td>
          <td style="text-align: right;">₹<?php echo number_format($item->unit_value, 2); ?></td>
          <td style="text-align: right;">₹<?php echo number_format($item->total_value, 2); ?></td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="4" style="text-align: right; font-weight: bold;">Total Declared Value:</td>
        <td style="text-align: right; font-weight: bold;">₹<?php echo number_format($shipment->total_declared_value, 2); ?></td>
      </tr>
    </tbody>
  </table>

  <!-- Terms and Conditions section -->
  <div class="section-title">Terms &amp; Conditions (Contract of Carriage)</div>
  <?php if($accepted_terms): ?>
    <div style="font-size: 11px; margin-bottom: 5px; font-weight: bold;">
      Carriage Contract Version: <?php echo htmlspecialchars($accepted_terms->version_number); ?> (Status: <?php echo htmlspecialchars($accepted_terms->status); ?>)
    </div>
    <div class="terms-box">
      <?php echo $accepted_terms->terms_content; ?>
    </div>
  <?php else: ?>
    <div class="terms-box" style="text-align: center; color: #777;">
      No Terms &amp; Conditions version registered/active.
    </div>
  <?php endif; ?>

  <!-- Authenticity sign-offs block -->
  <div class="section-title">Verification &amp; Security Release Logs</div>
  <table class="meta-table">
    <tr>
      <th style="width: 30%;">OTP Authentication:</th>
      <td>
        <?php if($shipment->otp_verification_status == 'Verified'): ?>
          <span style="color: green; font-weight: bold;">&#10004; OTP VERIFIED</span>
        <?php else: ?>
          <span style="color: red; font-weight: bold;">&#10008; OTP PENDING Verification</span>
        <?php endif; ?>
      </td>
      <th style="width: 30%;">Declaration Status:</th>
      <td><strong><?php echo $shipment->declaration_status; ?></strong></td>
    </tr>
    <tr>
      <th>KYC Validation status:</th>
      <td>
        <span style="text-transform: uppercase; font-weight: bold;">
          <?php echo $shipment->kyc_status == 'approved' ? 'Approved &amp; Cleared' : 'Awaiting Approval / Pending'; ?>
        </span>
      </td>
      <th>Verification completed at:</th>
      <td><?php echo $shipment->verification_completed_at ? date('d M Y H:i:s', strtotime($shipment->verification_completed_at)) : 'N/A'; ?></td>
    </tr>
  </table>

  <!-- Signatures blocks -->
  <table class="signature-table">
    <tr>
      <td style="text-align: center; padding-right: 20px;">
        <?php if($signature): ?>
          <div style="margin-bottom: 8px;">
            <img src="<?php echo base_url($signature->signature_image_path); ?>" style="max-height: 60px; border: 1px dashed #777; background: #fff;" alt="Exporter Signature"><br>
          </div>
          <span style="font-size: 10px; color: #555; display: block;">Signed from IP: <?php echo $signature->ip_address; ?></span>
        <?php else: ?>
          <div style="height: 60px; border-bottom: 1px solid #000; margin-bottom: 8px;"></div>
        <?php endif; ?>
        <strong style="font-size: 11px; text-transform: uppercase;">Signature of Exporter / Shipper</strong>
      </td>
      <td style="text-align: center; padding-left: 20px;">
        <div style="height: 60px; border-bottom: 1px solid #000; margin-bottom: 8px;"></div>
        <strong style="font-size: 11px; text-transform: uppercase;">Carrier Officer Stamp / Authority</strong>
      </td>
    </tr>
  </table>

</div>

</body>
</html>
