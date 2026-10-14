<div class="row">
  <!-- KYC Alert Status -->
  <div class="col-md-12">
    <?php if ($customer->kyc_status == 'approved'): ?>
      <div class="alert alert-success">
        <h4><i class="icon fa fa-check-circle"></i> KYC Approved!</h4>
        Your Know Your Customer (KYC) documentation has been verified and approved. Your shipments can be cleared for transit movements immediately.
      </div>
    <?php elseif ($customer->kyc_status == 'rejected'): ?>
      <div class="alert alert-danger">
        <h4><i class="icon fa fa-times-circle"></i> KYC Rejected</h4>
        Your KYC profile has been rejected by administration. Reason: <strong><?php echo $customer->reject_reason; ?></strong>. Please go to <a href="<?php echo site_url('customer/kyc'); ?>" class="btn-link" style="color: #fff; text-decoration: underline;">KYC uploads</a> to re-submit corrected documents.
      </div>
    <?php else: ?>
      <div class="alert alert-warning">
        <h4><i class="icon fa fa-warning"></i> KYC Verification Pending</h4>
        Your KYC document details are undergoing verification. You can still review bookings, sign terms, and verify OTPs, but shipments will remain blocked from physical transit until your KYC is approved. <a href="<?php echo site_url('customer/kyc'); ?>" style="color: inherit; text-decoration: underline; font-weight: bold;">Upload KYC here</a>.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- International Rate Calculator Quick Action Banner -->
<div class="row" style="margin-bottom: 20px;">
  <div class="col-md-12">
    <div style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 8px; padding: 18px 24px; color: #ffffff; box-shadow: 0 4px 15px rgba(30, 60, 114, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 52px; height: 52px; border-radius: 12px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; font-size: 26px; color: #facc15; flex-shrink: 0; box-shadow: inset 0 0 10px rgba(255,255,255,0.1);">
          <i class="fa fa-calculator"></i>
        </div>
        <div>
          <h4 style="margin: 0 0 4px 0; font-weight: 700; font-size: 19px; color: #ffffff; letter-spacing: -0.3px;">
            International Shipping Rate Calculator
          </h4>
          <p style="margin: 0; font-size: 13px; color: rgba(255,255,255,0.85); line-height: 1.4;">
            Instantly compare live carrier rates, calculate volumetric weights, and estimate delivery charges across global destinations.
          </p>
        </div>
      </div>
      <div>
        <button type="button" id="btnOpenRateCalcModal" class="btn btn-warning btn-trigger-rate-calc" onclick="openRateCalculatorModal(event);" style="font-weight: 700; border-radius: 6px; padding: 10px 22px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(0,0,0,0.18); border: none;">
          <i class="fa fa-calculator" style="margin-right: 6px;"></i> Calculate Live Rates
        </button>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-purple">
      <span class="info-box-icon"><i class="fa fa-google-wallet"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Wallet Balance</span>
        <span class="info-box-number">₹<?php echo number_format($wallet_balance, 2); ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-red">
      <span class="info-box-icon"><i class="fa fa-balance-scale"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Outstanding Balance</span>
        <span class="info-box-number">₹<?php echo number_format($customer->outstanding_balance, 2); ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-blue">
      <span class="info-box-icon"><i class="fa fa-cubes"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Shipments</span>
        <span class="info-box-number"><?php echo $total_shipments; ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-orange">
      <span class="info-box-icon"><i class="fa fa-pencil-square-o"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pending Action</span>
        <span class="info-box-number"><?php echo $pending_verifications; ?></span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Recent Shipment list -->
  <div class="col-md-7">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-cube"></i> Recent Consignments</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>AWB Number</th>
              <th>Destination</th>
              <th>Status</th>
              <th>Verification</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($recent_shipments)): ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No shipments found.</td>
              </tr>
            <?php else: ?>
              <?php foreach($recent_shipments as $s): ?>
                <tr>
                  <td><strong><?php echo $s->awb_number; ?></strong></td>
                  <td><?php echo $s->destination_country; ?></td>
                  <td>
                    <?php if($s->status == 'Booking Created'): ?>
                      <span class="label label-info"><?php echo $s->status; ?></span>
                    <?php elseif($s->status == 'Verification Pending'): ?>
                      <span class="label label-warning"><?php echo $s->status; ?></span>
                    <?php elseif($s->status == 'Ready For Dispatch' || $s->status == 'Released For Transit'): ?>
                      <span class="label label-success"><?php echo $s->status; ?></span>
                    <?php else: ?>
                      <span class="label label-primary"><?php echo $s->status; ?></span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if($s->verification_status == 'Completed'): ?>
                      <span class="text-success"><i class="fa fa-check-circle"></i> Verified</span>
                    <?php else: ?>
                      <span class="text-danger"><i class="fa fa-warning"></i> Action Required</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="<?php echo site_url('shipments/view/' . $s->id); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i> View</a>
                    <?php if($s->verification_status == 'Pending'): ?>
                      <a href="<?php echo site_url('customer/verify/' . $s->id); ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Sign & Verify</a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="box-footer text-center">
        <a href="<?php echo site_url('shipments'); ?>" class="uppercase">View All Shipments</a>
      </div>
    </div>
  </div>

  <!-- Recent Wallet Transactions -->
  <div class="col-md-5">
    <div class="box box-purple">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-google-wallet"></i> Recent Wallet Transactions</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Type</th>
              <th>Amount</th>
              <th>Description</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($wallet_transactions)): ?>
              <tr>
                <td colspan="4" class="text-center text-muted">No transactions recorded.</td>
              </tr>
            <?php else: ?>
              <?php foreach($wallet_transactions as $t): ?>
                <tr>
                  <td>
                    <?php if($t->transaction_type == 'Credit'): ?>
                      <span class="label label-success"><i class="fa fa-plus"></i> Credit</span>
                    <?php else: ?>
                      <span class="label label-danger"><i class="fa fa-minus"></i> Debit</span>
                    <?php endif; ?>
                  </td>
                  <td><strong>₹<?php echo number_format($t->amount, 2); ?></strong></td>
                  <td style="font-size: 13px;"><?php echo $t->description; ?></td>
                  <td style="font-size: 12px; color: #777;"><?php echo date('d M Y', strtotime($t->created_at)); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="box-footer text-center">
        <a href="<?php echo site_url('customer/wallet'); ?>" class="uppercase">View Wallet Statement</a>
      </div>
    </div>
  </div>
</div>
