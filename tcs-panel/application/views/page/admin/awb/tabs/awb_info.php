<!-- views/awb/tabs/awb_info.php -->
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Company</label>
      <input type="text" name="company_name" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Origin Country Code</label>
      <input type="text" name="origin_country_code" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Origin Country Name</label>
      <input type="text" name="origin_country_name" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Origin Zone</label>
      <input type="text" name="origin_zone" class="form-control">
    </div>
    <div class="form-group">
      <label>Destination Country Code</label>
      <input type="text" name="destination_country_code" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Destination Country Name</label>
      <input type="text" name="destination_country_name" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Product</label>
      <select name="product_type" class="form-control">
        <option value="DOX">DOX</option>
        <option value="NON DOX">NON DOX</option>
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Booking Date</label>
      <input type="datetime-local" name="booking_date" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Forwarding Date</label>
      <input type="datetime-local" name="forwarding_date" class="form-control">
    </div>
    <div class="form-group">
      <label>Service Name</label>
      <input type="text" name="service_name" class="form-control">
    </div>
    <div class="form-group">
      <label>Forwarding Number</label>
      <input type="text" name="forwarding_number" class="form-control">
    </div>
    <div class="form-group">
      <label>Shipment Value</label>
      <input type="number" step="0.01" name="shipment_value" class="form-control">
    </div>
    <div class="form-group">
      <label>Currency</label>
      <input type="text" name="shipment_currency" class="form-control" placeholder="INR, USD...">
    </div>
    <div class="form-group">
      <label>Invoice Date</label>
      <input type="date" name="invoice_date" class="form-control">
    </div>
    <div class="form-group">
      <label>Invoice Number</label>
      <input type="text" name="invoice_number" class="form-control">
    </div>
    <div class="form-group">
      <label>Content</label>
      <textarea name="content" class="form-control" rows="2"></textarea>
    </div>
    <div class="form-group">
      <label>Payment Mode</label>
      <select name="payment_mode" class="form-control">
        <option value="CASH">Cash</option>
        <option value="CHEQUE">Cheque</option>
        <option value="UPI">UPI</option>
        <option value="NEFT">NEFT</option>
      </select>
    </div>
  </div>
</div>
<!-- Repeat similar format for shipper, receiver, box_dimensions, invoice, and invoice_items tabs -->
