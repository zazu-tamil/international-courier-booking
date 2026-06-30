<!DOCTYPE html>
<html>
<head>
  <title>Commercial Invoice - <?php echo $shipment->awb_number; ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 30px;
      color: #333;
      background: #fff;
    }
    .invoice-container {
      max-width: 800px;
      margin: 0 auto;
      border: 1px solid #ccc;
      padding: 20px;
    }
    .header-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .header-table td {
      vertical-align: top;
    }
    .title {
      font-size: 24px;
      font-weight: bold;
      color: #333;
      text-transform: uppercase;
    }
    .meta-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }
    .meta-table td {
      border: 1px solid #ddd;
      padding: 8px;
      font-size: 13px;
    }
    .address-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }
    .address-table td {
      width: 50%;
      border: 1px solid #ddd;
      padding: 12px;
      vertical-align: top;
      font-size: 13px;
      line-height: 1.5;
    }
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }
    .items-table th, .items-table td {
      border: 1px solid #ddd;
      padding: 8px;
      font-size: 13px;
      text-align: left;
    }
    .items-table th {
      background-color: #f5f5f5;
    }
    .totals-table {
      width: 40%;
      margin-left: 60%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .totals-table td {
      padding: 6px;
      border-bottom: 1px solid #ddd;
    }
    .footer {
      margin-top: 40px;
      font-size: 11px;
      color: #777;
      text-align: center;
      border-top: 1px solid #eee;
      padding-top: 10px;
    }
  </style>
</head>
<body onload="window.print()">

<div class="invoice-container">
  <table class="header-table">
    <tr>
      <td>
        <div class="title">Commercial Invoice</div>
        <div style="font-size: 12px; margin-top: 5px; color: #666;">International Shipping Cargo Document</div>
      </td>
      <td style="text-align: right;">
        <strong><?php echo defined('COMPANY_NAME') && COMPANY_NAME ? htmlspecialchars(COMPANY_NAME) : 'CourierSyndicate International'; ?></strong><br>
        <span style="font-size: 12px; color: #555;">
          <?php echo defined('COMPANY_ADDRESS') && COMPANY_ADDRESS ? nl2br(htmlspecialchars(COMPANY_ADDRESS)) : 'HQ Origin branch Office'; ?><br>
          <?php echo defined('COMPANY_EMAIL') && COMPANY_EMAIL ? htmlspecialchars(COMPANY_EMAIL) : 'billing@couriersyn.com'; ?>
        </span>
      </td>
    </tr>
  </table>

  <table class="meta-table">
    <tr>
      <td><strong>Invoice Number:</strong><br><code><?php echo $invoice ? $invoice->invoice_number : 'N/A'; ?></code></td>
      <td><strong>AWB Number:</strong><br><strong><?php echo $shipment->awb_number; ?></strong><br>
      <span style="font-size: 11px; color: #555;">Service: <?php echo $shipment->service_type; ?></span></td>
      <td><strong>Invoice Date:</strong><br><?php echo $invoice ? date('d M Y', strtotime($invoice->invoice_date)) : date('d M Y'); ?></td>
      <td><strong>Payment Mode:</strong><br><?php echo $invoice && $invoice->status == 'Paid' ? 'Paid (Settled)' : 'Credit Account Debit'; ?></td>
    </tr>
  </table>

  <table class="address-table">
    <tr>
      <td>
        <strong style="text-transform: uppercase; font-size: 11px; color: #555; display: block; margin-bottom: 5px;">Exporter (Shipper):</strong>
        <strong><?php echo $shipment->sender_name; ?></strong><br>
        <?php echo $shipment->sender_company ? $shipment->sender_company.'<br>' : ''; ?>
        <?php echo $shipment->sender_address; ?><br>
        <?php echo $shipment->sender_city; ?>, <?php echo $shipment->sender_state; ?>, <?php echo $shipment->origin_country_name; ?> - <?php echo $shipment->sender_zip; ?><br>
        Phone: <?php echo $shipment->sender_mobile; ?><br>
        Email: <?php echo $shipment->sender_email; ?>
      </td>
      <td>
        <strong style="text-transform: uppercase; font-size: 11px; color: #555; display: block; margin-bottom: 5px;">Consignee (Receiver):</strong>
        <strong><?php echo $shipment->receiver_name; ?></strong><br>
        <?php echo $shipment->receiver_company ? $shipment->receiver_company.'<br>' : ''; ?>
        <?php echo $shipment->receiver_address; ?><br>
        <?php echo $shipment->receiver_city; ?>, <?php echo $shipment->receiver_state; ?>, <?php echo $shipment->dest_country_name; ?> - <?php echo $shipment->receiver_zip; ?><br>
        Phone: <?php echo $shipment->receiver_mobile; ?><br>
        Email: <?php echo $shipment->receiver_email; ?>
      </td>
    </tr>
  </table>

  <h4 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Consignment Contents</h4>
  <table class="items-table">
    <thead>
      <tr>
        <th>Description of Goods</th>
        <th>HS Code</th>
        <th>Qty</th>
        <th>Box No.</th>
        <th>Unit Price</th>
        <th style="text-align: right;">Total Price</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($items as $item): ?>
        <tr>
          <td><?php echo $item->item_description; ?></td>
          <td><code><?php echo $item->hs_code; ?></code></td>
          <td><?php echo $item->quantity; ?></td>
          <td style="text-align: center;"><?php echo $item->box_no; ?></td>
          <td>₹<?php echo number_format($item->unit_value, 2); ?></td>
          <td style="text-align: right;">₹<?php echo number_format($item->total_value, 2); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5" style="text-align: right;">Items Value:</td>
        <td style="text-align: right;">₹<?php echo number_format($shipment->total_declared_value, 2); ?></td>
      </tr>
    </tfoot>
  </table>

  <table class="totals-table">
    <?php if(!empty($charges)): ?>
      <?php foreach($charges as $charge): 
        $charge_name = 'Unknown Charge';
        foreach($charge_types as $ct) {
          if ($ct->id == $charge->charge_type_id) {
            $charge_name = $ct->charge_name;
            break;
          }
        }
      ?>
      <tr>
        <td><?php echo htmlspecialchars($charge_name); ?>:</td>
        <td style="text-align: right;">₹<?php echo number_format($charge->amount, 2); ?></td>
      </tr>
      <?php endforeach; ?>
      <tr style="font-weight: bold; font-size: 15px; border-top: 2px solid #000;">
        <td>Total Billing Charges:</td>
        <td style="text-align: right;">₹<?php echo number_format($shipment->estimated_charges, 2); ?></td>
      </tr>
    <?php elseif($invoice): ?>
      <tr>
        <td>Shipping Charges:</td>
        <td style="text-align: right;">₹<?php echo number_format($invoice->total_amount, 2); ?></td>
      </tr>
      <tr>
        <td>GST Tax (18.00%):</td>
        <td style="text-align: right;">₹<?php echo number_format($invoice->tax_amount, 2); ?></td>
      </tr>
      <tr style="font-weight: bold; font-size: 15px; border-top: 2px solid #000;">
        <td>Final Amount:</td>
        <td style="text-align: right;">₹<?php echo number_format($invoice->final_amount, 2); ?></td>
      </tr>
    <?php endif; ?>
  </table>

  <div style="margin-top: 50px;">
    <table style="width: 100%; border: none;">
      <tr>
        <td style="width: 50%; vertical-align: bottom;">
          <span style="font-size: 11px; display: block; border-top: 1px solid #ccc; width: 180px; margin-top: 40px; text-align: center;">Authorized Signatory / Exporter</span>
        </td>
        <td style="width: 50%; text-align: right; vertical-align: bottom;">
          <span style="font-size: 11px; display: block; border-top: 1px solid #ccc; width: 180px; margin-left: auto; text-align: center;">Carrier Officer stamp</span>
        </td>
      </tr>
    </table>
  </div>

  <div class="footer">
    <p>This is a computer generated commercial export document. The exporter declares that all contents correspond to export laws and no prohibited hazardous items were loaded.</p>
  </div>
</div>

</body>
</html>
