<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-money"></i> Prepaid Wallet Load Requests</h3>
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
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
