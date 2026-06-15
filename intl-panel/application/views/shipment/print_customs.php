<!DOCTYPE html>
<html>
<head>
  <title>Customs Declaration - <?php echo $shipment->awb_number; ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 30px;
      color: #333;
      background: #fff;
    }
    .declaration-container {
      max-width: 800px;
      margin: 0 auto;
      border: 2px solid #000;
      padding: 20px;
    }
    .title {
      font-size: 20px;
      font-weight: bold;
      text-align: center;
      text-transform: uppercase;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .table-layout {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    .table-layout td, .table-layout th {
      border: 1px solid #000;
      padding: 8px;
      font-size: 12px;
      vertical-align: top;
    }
    .table-layout th {
      background-color: #f0f0f0;
      text-align: left;
    }
    .declaration-clause {
      font-size: 11px;
      line-height: 1.5;
      margin-top: 15px;
      text-align: justify;
    }
  </style>
</head>
<body onload="window.print()">

<div class="declaration-container">
  <div class="title">CN22 / CN23 International Customs Declaration Form</div>

  <table class="table-layout">
    <tr>
      <td style="width: 50%;">
        <strong>FROM (Exporter):</strong><br>
        <?php echo $shipment->sender_name; ?><br>
        <?php echo $shipment->sender_company ? $shipment->sender_company.'<br>' : ''; ?>
        <?php echo $shipment->sender_address; ?>, <?php echo $shipment->sender_city; ?>, <?php echo $shipment->sender_state; ?><br>
        Country: <?php echo $shipment->origin_country_name; ?><br>
        Mobile: <?php echo $shipment->sender_mobile; ?>
      </td>
      <td style="width: 50%;">
        <strong>TO (Consignee):</strong><br>
        <?php echo $shipment->receiver_name; ?><br>
        <?php echo $shipment->receiver_company ? $shipment->receiver_company.'<br>' : ''; ?>
        <?php echo $shipment->receiver_address; ?>, <?php echo $shipment->receiver_city; ?>, <?php echo $shipment->receiver_state; ?><br>
        Country: <?php echo $shipment->dest_country_name; ?><br>
        Mobile: <?php echo $shipment->receiver_mobile; ?>
      </td>
    </tr>
  </table>

  <table class="table-layout">
    <tr>
      <th style="width: 30%;">AWB Reference</th>
      <td><strong><?php echo $shipment->awb_number; ?></strong></td>
      <th style="width: 30%;">Shipment Purpose</th>
      <td>
        <?php 
          // Default to Gift/Commercial based on total value
          echo $shipment->total_declared_value > 10000 ? 'Commercial' : 'Gift / Personal Effects'; 
        ?>
      </td>
    </tr>
    <tr>
      <th>Origin Country</th>
      <td><?php echo $shipment->origin_country_name; ?></td>
      <th>Destination Country</th>
      <td><?php echo $shipment->dest_country_name; ?> (ISO: <?php echo $this->db->get_where('countries', array('id' => $shipment->destination_country_id))->row()->iso_code; ?>)</td>
    </tr>
    <tr>
      <th>Chargeable Weight</th>
      <td><strong><?php echo $shipment->chargeable_weight; ?> kg</strong></td>
      <th>Invoice Value</th>
      <td><strong>₹<?php echo number_format($shipment->total_declared_value, 2); ?></strong></td>
    </tr>
  </table>

  <h4 style="margin-top: 15px; margin-bottom: 5px; font-size: 13px;">Detailed Description of Goods</h4>
  <table class="table-layout">
    <thead>
      <tr style="background-color: #f0f0f0;">
        <th>Description</th>
        <th>HS Tariff Code</th>
        <th>Qty</th>
        <th>Value (INR)</th>
        <th>Country of Origin</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($items as $item): ?>
        <tr>
          <td><?php echo $item->item_description; ?></td>
          <td><code><?php echo $item->hs_code; ?></code></td>
          <td><?php echo $item->quantity; ?></td>
          <td>₹<?php echo number_format($item->total_value, 2); ?></td>
          <td><?php echo $shipment->origin_country_name; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="declaration-clause">
    <strong>DECLARATION:</strong> I, the undersigned exporter of the goods described in this customs declaration, certify that the particulars given in this declaration are correct and that the goods do not contain any dangerous articles, uncertified batteries, liquids, medicines, or articles prohibited by customs or postal regulations. I understand that failure to submit accurate particulars may lead to severe fines or consignment seizure at port of entry.
  </div>

  <div style="margin-top: 40px;">
    <table style="width: 100%; border: none;">
      <tr>
        <td style="width: 60%; font-size: 12px; vertical-align: bottom;">
          Date of Declaration: <strong><?php echo date('d-m-Y', strtotime($shipment->booking_date)); ?></strong>
        </td>
        <td style="width: 40%; text-align: right; vertical-align: bottom; font-size: 12px;">
          <span style="font-size: 11px; display: block; border-top: 1px solid #000; width: 200px; margin-left: auto; text-align: center; padding-top: 5px;">Signature of Exporter</span>
        </td>
      </tr>
    </table>
  </div>
</div>

</body>
</html>
