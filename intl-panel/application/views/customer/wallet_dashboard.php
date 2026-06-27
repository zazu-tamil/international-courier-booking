<div class="row">
  <div class="col-md-4">
    <!-- Balance Widget -->
    <div class="box box-solid bg-purple-gradient">
      <div class="box-header">
        <i class="fa fa-google-wallet"></i>
        <h3 class="box-title">Wallet Balance</h3>
      </div>
      <div class="box-body text-center" style="padding: 30px;">
        <h1 style="font-size: 45px; font-weight: 700; margin: 0; color: #fff;">₹<?php echo number_format($balance, 2); ?></h1>
        <p style="margin-top: 10px; color: rgba(255,255,255,0.8); font-size: 15px;">Available Prepaid Balance</p>
      </div>
      <div class="box-footer text-black text-center">
        <button class="btn btn-default btn-block" data-toggle="modal" data-target="#addFundsModal">
          <i class="fa fa-plus-circle text-purple"></i> <strong>Load Prepaid Funds</strong>
        </button>
      </div>
    </div>
    
    <!-- Customer Details Card -->
    <div class="box box-purple">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-user"></i> Account Profile</h3>
      </div>
      <div class="box-body">
        <p><strong>Customer Name:</strong> <?php echo $customer->name; ?></p>
        <p><strong>Account Type:</strong> <?php echo ucfirst($customer->customer_type); ?></p>
        <p><strong>Credit Limit:</strong> ₹<?php echo number_format($customer->credit_limit, 2); ?></p>
        <p><strong>Credit Allowed:</strong> <?php echo $customer->credit_days; ?> Days</p>
        <p><strong>Outstanding Balance:</strong> <span class="text-danger font-weight-bold">₹<?php echo number_format($customer->outstanding_balance, 2); ?></span></p>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <!-- Pending Requests List -->
    <?php if(!empty($pending_requests)): ?>
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-clock-o"></i> Pending Load Requests</h3>
      </div>
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Mode</th>
              <th>Amount</th>
              <th>Reference ID</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($pending_requests as $pr): ?>
              <tr>
                <td><?php echo date('d M Y H:i', strtotime($pr->created_at)); ?></td>
                <td><?php echo $pr->payment_mode; ?></td>
                <td><strong>₹<?php echo number_format($pr->amount, 2); ?></strong></td>
                <td><code><?php echo $pr->transaction_id ? $pr->transaction_id : '-'; ?></code></td>
                <td><span class="label label-warning">Pending Approval</span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <!-- Transactions List -->
    <div class="box box-purple">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list"></i> Wallet Transaction Ledger</h3>
      </div>
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Date</th>
              <th>Transaction Type</th>
              <th>Reference ID</th>
              <th>Description</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($transactions as $t): ?>
              <tr>
                <td><?php echo date('d M Y H:i', strtotime($t->created_at)); ?></td>
                <td>
                  <?php if($t->transaction_type == 'Credit'): ?>
                    <span class="label label-success"><i class="fa fa-plus-circle"></i> Credit</span>
                  <?php else: ?>
                    <span class="label label-danger"><i class="fa fa-minus-circle"></i> Debit</span>
                  <?php endif; ?>
                </td>
                <td><code><?php echo $t->reference_id ? $t->reference_id : '-'; ?></code></td>
                <td><?php echo $t->description; ?></td>
                <td><strong class="<?php echo ($t->transaction_type == 'Credit') ? 'text-success' : 'text-danger'; ?>">
                  <?php echo ($t->transaction_type == 'Credit') ? '+' : '-'; ?> ₹<?php echo number_format($t->amount, 2); ?>
                </strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Load Prepaid Funds Modal -->
<div class="modal fade" id="addFundsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open_multipart('customer/add_funds'); ?>
        <!-- Send customer ID if loaded by staff, otherwise uses logged-in session customer_id in controller -->
        <input type="hidden" name="customer_id" value="<?php echo $customer->id; ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Load Prepaid Wallet Funds</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Load Amount (INR) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="amount" class="form-control" placeholder="1000.00" min="1" required>
          </div>
          <div class="form-group">
            <label>Prepaid Payment Mode</label>
            <select name="payment_mode" class="form-control">
              <option value="UPI">UPI / QR Scan</option>
              <option value="Card">Credit / Debit Card</option>
              <option value="Bank Transfer">Direct Bank Wire</option>
              <option value="Cash">Cash Handover at Office</option>
            </select>
          </div>
          <div class="form-group">
            <label>Transaction ID / Reference Number</label>
            <input type="text" name="transaction_id" class="form-control" placeholder="e.g. TXN9988776655">
          </div>
          <div class="form-group">
            <label>Upload Payment Proof (Optional)</label>
            <input type="file" name="proof_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <small class="text-muted">Max 5MB. Formats: JPG, PNG, PDF</small>
          </div>
          <p class="text-muted"><i class="fa fa-info-circle"></i> Deposited funds will be credited to your wallet balance once approved by the administration.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Submit for Approval</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
