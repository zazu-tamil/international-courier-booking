<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-money"></i> Prepaid Wallet Load Requests</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-primary btn-sm" onclick="openWalletModal()"><i class="fa fa-plus"></i> Add Request</button>
        </div>
      </div>
      
      <div class="box-body">
        <form method="GET" action="<?php echo site_url('customer/wallet-requests'); ?>" class="row" style="margin-bottom: 20px;">
            <div class="col-md-3">
                <label>Customer</label>
                <select name="customer_id" class="form-control">
                    <option value="">All Customers</option>
                    <?php foreach($customers as $c): ?>
                        <option value="<?php echo $c->id; ?>" <?php echo (isset($filter_customer) && $filter_customer == $c->id) ? 'selected' : ''; ?>><?php echo $c->name; ?> (<?php echo $c->company_name; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control" value="<?php echo isset($filter_from) ? $filter_from : ''; ?>">
            </div>
            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control" value="<?php echo isset($filter_to) ? $filter_to : ''; ?>">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label><br>
                <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                <a href="<?php echo site_url('customer/wallet-requests'); ?>" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
            </div>
        </form>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Amount</th>
              <th>Mode / TXN ID</th>
              <th>Payment Proof</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($requests as $req): ?>
              <tr>
                <td><?php echo $req->id; ?></td>
                <td><?php echo date('d M Y H:i', strtotime($req->created_at)); ?></td>
                <td>
                  <strong><?php echo $req->customer_name; ?></strong><br>
                  <small class="text-muted"><?php echo $req->company_name; ?></small>
                </td>
                <td><strong class="text-green">₹<?php echo number_format($req->amount, 2); ?></strong></td>
                <td>
                  <?php echo $req->payment_mode; ?><br>
                  <code><?php echo $req->transaction_id ? $req->transaction_id : 'N/A'; ?></code>
                </td>
                <td>
                  <?php if($req->proof_file_path): ?>
                    <a href="<?php echo base_url($req->proof_file_path); ?>" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-download"></i> View Proof</a>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($req->status == 'Pending'): ?>
                    <span class="label label-warning">Pending</span>
                  <?php elseif($req->status == 'Approved'): ?>
                    <span class="label label-success">Approved</span>
                  <?php elseif($req->status == 'Rejected'): ?>
                    <span class="label label-danger">Rejected</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($req->status == 'Pending'): ?>
                    <a href="<?php echo site_url('customer/approve-wallet-request/'.$req->id); ?>" class="btn btn-success btn-xs" onclick="return confirm('Approve this request? Funds will be credited to customer.');"><i class="fa fa-check"></i> Approve</a>
                    <a href="<?php echo site_url('customer/reject-wallet-request/'.$req->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Reject this request?');"><i class="fa fa-times"></i> Reject</a>
                  <?php else: ?>
                    <span class="text-muted">Processed</span>
                  <?php endif; ?>
                  <button class="btn btn-info btn-xs" onclick="openWalletModal(<?php echo $req->id; ?>, <?php echo $req->customer_id; ?>, '<?php echo $req->amount; ?>', '<?php echo htmlspecialchars($req->payment_mode, ENT_QUOTES); ?>', '<?php echo htmlspecialchars($req->transaction_id ?? '', ENT_QUOTES); ?>')" style="margin-left: 5px;"><i class="fa fa-edit"></i> Edit</button>
                  <a href="<?php echo site_url('customer/delete_wallet_request/'.$req->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this request?');" style="margin-left: 5px;"><i class="fa fa-trash"></i> Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Wallet Request Modal -->
<div class="modal fade" id="walletRequestModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo site_url('customer/save_wallet_request'); ?>" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="walletModalTitle">Add Wallet Request</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="request_id" id="modal_request_id" value="">
          <div class="form-group">
            <label>Customer <span class="text-danger">*</span></label>
            <select name="customer_id" id="modal_customer_id" class="form-control" required>
              <option value="">Select Customer</option>
              <?php foreach($customers as $c): ?>
                <option value="<?php echo $c->id; ?>"><?php echo $c->name; ?> (<?php echo $c->company_name; ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Amount (₹) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="amount" id="modal_amount" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Payment Mode <span class="text-danger">*</span></label>
            <select name="payment_mode" id="modal_payment_mode" class="form-control" required>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="UPI / QR Scan">UPI / QR Scan</option>
              <option value="Cash">Cash</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>
          <div class="form-group">
            <label>Transaction ID / Reference Number</label>
            <input type="text" name="transaction_id" id="modal_transaction_id" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Request</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function openWalletModal(id = '', customer_id = '', amount = '', payment_mode = 'Bank Transfer', transaction_id = '') {
    $('#modal_request_id').val(id);
    $('#modal_customer_id').val(customer_id);
    $('#modal_amount').val(amount);
    $('#modal_payment_mode').val(payment_mode);
    $('#modal_transaction_id').val(transaction_id);
    
    if (id) {
        $('#walletModalTitle').text('Edit Wallet Request');
    } else {
        $('#walletModalTitle').text('Add Wallet Request');
    }
    
    $('#walletRequestModal').modal('show');
}
</script>
