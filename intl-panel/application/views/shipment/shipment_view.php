<div class="row">
  <div class="col-md-8">
    
    <!-- Consignment Header Detail Card -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-paper-plane-o"></i> Consignment AWB: <strong><?php echo $shipment->awb_number; ?></strong></h3>
        <div class="pull-right">
          <a href="<?php echo site_url('shipments'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back to List</a>
          <?php if($this->session->userdata('role_id') != 4): ?>
            <a href="<?php echo site_url('shipments/edit/' . $shipment->id); ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Edit Booking</a>
            <a href="<?php echo site_url('shipments/send-login/' . $shipment->id); ?>" class="btn btn-info btn-sm"><i class="fa fa-envelope"></i> Send Login Details</a>
          <?php endif; ?>
          <?php if($this->session->userdata('role_id') == 4 && $shipment->verification_status == 'Pending'): ?>
            <a href="<?php echo site_url('customer/verify/' . $shipment->id); ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Sign & Verify Wizard</a>
          <?php endif; ?>
          <a href="<?php echo site_url('shipments/print-label/' . $shipment->id); ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-barcode"></i> Print Label</a>
          <a href="<?php echo site_url('shipments/print-invoice/' . $shipment->id); ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o text-red"></i> Commercial Invoice</a>
          <a href="<?php echo site_url('shipments/print-customs/' . $shipment->id); ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-shield"></i> Customs Dec</a>
          <a href="<?php echo site_url('shipments/print-awb/' . $shipment->id); ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Print AWB</a>
        </div>
      </div>
      
      <div class="box-body">
        <div class="row">
          <div class="col-xs-6">
            <h5><strong>Sender Address Details:</strong></h5>
            <p><strong><?php echo $shipment->sender_name; ?></strong><br>
            <?php echo $shipment->sender_company ? $shipment->sender_company.'<br>' : ''; ?>
            <?php echo $shipment->sender_address; ?>, <?php echo $shipment->sender_city; ?>, <?php echo $shipment->sender_state; ?>, <?php echo $shipment->origin_country_name; ?> - <?php echo $shipment->sender_zip; ?><br>
            Phone: <?php echo $shipment->sender_mobile; ?></p>
          </div>
          <div class="col-xs-6 text-right">
            <h5><strong>Receiver Address Details:</strong></h5>
            <p><strong><?php echo $shipment->receiver_name; ?></strong><br>
            <?php echo $shipment->receiver_company ? $shipment->receiver_company.'<br>' : ''; ?>
            <?php echo $shipment->receiver_address; ?>, <?php echo $shipment->receiver_city; ?>, <?php echo $shipment->receiver_state; ?>, <?php echo $shipment->dest_country_name; ?> - <?php echo $shipment->receiver_zip; ?><br>
            Phone: <?php echo $shipment->receiver_mobile; ?></p>
          </div>
        </div>

        <hr>

        <div class="row">
          <div class="col-sm-4">
            <p><strong>Booking Date:</strong> <?php echo date('d M Y', strtotime($shipment->booking_date)); ?></p>
            <p><strong>Courier Partner:</strong> <?php echo $shipment->courier_partner_name; ?></p>
          </div>
          <div class="col-sm-4">
            <p><strong>Service Type:</strong> <?php echo $shipment->service_type; ?></p>
            <p><strong>Shipment Type:</strong> <?php echo $shipment->shipment_type; ?></p>
          </div>
          <div class="col-sm-4 text-right">
            <p><strong>Chargeable Weight:</strong> <?php echo $shipment->chargeable_weight; ?> kg</p>
            <p><strong>Billing Cost:</strong> <strong class="text-blue">₹<?php echo number_format($shipment->estimated_charges, 2); ?></strong></p>
          </div>
        </div>

        <h5 style="font-weight:700; margin-top:20px;"><i class="fa fa-cubes"></i> Consignment Box Sizes</h5>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Box No</th>
              <th>Dimensions (L x W x H)</th>
              <th>Volumetric Weight</th>
              <th>Actual Weight</th>
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

        <h5 style="font-weight:700; margin-top:20px;"><i class="fa fa-list"></i> Declared Items</h5>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Description</th>
              <th>HS Code</th>
              <th>Qty</th>
              <th>Unit Value</th>
              <th class="text-right">Total Declared</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($items as $item): ?>
              <tr>
                <td><?php echo $item->item_description; ?></td>
                <td><code><?php echo $item->hs_code; ?></code></td>
                <td><?php echo $item->quantity; ?></td>
                <td>₹<?php echo number_format($item->unit_value, 2); ?></td>
                <td class="text-right">₹<?php echo number_format($item->total_value, 2); ?></td>
              </tr>
            <?php endforeach; ?>
            <tr>
              <td colspan="4" class="text-right"><strong>Total Declared Customs Value:</strong></td>
              <td class="text-right"><strong>₹<?php echo number_format($shipment->total_declared_value, 2); ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Movement stages timeline -->
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-map-marker"></i> Transit Tracking stages Timeline</h3>
      </div>
      <div class="box-body">
        <ul class="timeline">
          <?php foreach($timeline as $t): ?>
            <li>
              <i class="fa fa-circle bg-blue"></i>
              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?php echo date('d M Y H:i', strtotime($t->date_time)); ?></span>
                <h3 class="timeline-header"><span class="label label-info"><?php echo $t->status; ?></span> at <strong><?php echo $t->location; ?></strong></h3>
                <div class="timeline-body"><?php echo $t->remarks; ?></div>
                <div class="timeline-footer" style="padding: 2px 10px; font-size:11px; color:#777;">Updated by: <?php echo $t->updater_name; ?></div>
              </div>
            </li>
          <?php endforeach; ?>
          <li>
            <i class="fa fa-clock-o bg-gray"></i>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    
    <!-- Step 3 Verification status banner -->
    <div class="box box-solid <?php echo ($shipment->verification_status == 'Completed') ? 'box-success' : 'box-warning'; ?>">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-shield"></i> Verification Workflow Checklist</h3>
      </div>
      <div class="box-body" style="padding: 15px 0;">
        <ul class="list-group list-group-unbordered" style="margin: 0; padding: 0 15px;">
          <li class="list-group-item">
            <b>Customer KYC status:</b> 
            <?php if($shipment->customer_profile_type == 'individual'): ?>
              <span class="pull-right label <?php echo ($shipment->kyc_status == 'approved') ? 'label-success' : 'label-warning'; ?>">
                <?php echo ($shipment->kyc_status == 'approved') ? 'Approved' : 'KYC Pending'; ?>
              </span>
            <?php else: ?>
              <span class="pull-right label <?php echo ($shipment->kyc_status == 'approved') ? 'label-success' : 'label-warning'; ?>">
                <?php echo ($shipment->kyc_status == 'approved') ? 'Corporate Approved' : 'KYC Pending'; ?>
              </span>
            <?php endif; ?>
          </li>
          <li class="list-group-item">
            <b>Declaration Accepted:</b>
            <span class="pull-right label <?php echo ($shipment->declaration_status == 'Accepted') ? 'label-success' : 'label-warning'; ?>">
              <?php echo $shipment->declaration_status; ?>
            </span>
          </li>
          <li class="list-group-item">
            <b>Terms & Conditions:</b>
            <span class="pull-right label <?php echo ($shipment->terms_status == 'Accepted') ? 'label-success' : 'label-warning'; ?>">
              <?php echo $shipment->terms_status; ?>
            </span>
          </li>
          <li class="list-group-item">
            <b>Digital Signature:</b>
            <span class="pull-right label <?php echo ($shipment->signature_status == 'Completed') ? 'label-success' : 'label-warning'; ?>">
              <?php echo ($shipment->signature_status == 'Completed') ? 'Signed' : 'Pending'; ?>
            </span>
          </li>
          <li class="list-group-item">
            <b>OTP Verification:</b>
            <span class="pull-right label <?php echo ($shipment->otp_verification_status == 'Verified') ? 'label-success' : 'label-warning'; ?>">
              <?php echo $shipment->otp_verification_status; ?>
            </span>
          </li>
        </ul>
        
        <?php if($shipment->verification_status == 'Completed' && $shipment->kyc_status == 'approved'): ?>
          <div class="alert alert-success text-center" style="margin: 15px 15px 0 15px; border-radius: 8px;">
            <i class="fa fa-check-circle"></i> <strong>RELEASED FOR TRANSIT</strong>
          </div>
        <?php else: ?>
          <div class="alert alert-danger text-center" style="margin: 15px 15px 0 15px; border-radius: 8px;">
            <i class="fa fa-ban"></i> <strong>VERIFICATION PENDING</strong><br>Shipment blocked from transit.
          </div>
        <?php endif; ?>

        <!-- Signature display -->
        <?php if($signature): ?>
          <div style="padding: 15px; text-align: center;">
            <h5><strong>Exporter Digital Signature:</strong></h5>
            <img src="<?php echo base_url($signature->signature_image_path); ?>" style="max-width: 100%; height: 80px; border: 1px solid #ddd; border-radius: 4px; padding: 5px; background: white;" alt="Signature image">
            <p style="font-size:10px; color:#777; margin-top: 5px;">Signed from IP: <?php echo $signature->ip_address; ?><br>on <?php echo date('d M Y H:i', strtotime($signature->created_at)); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Accounts Invoice Payment Card -->
    <div class="box box-solid <?php echo ($invoice && $invoice->status == 'Paid') ? 'box-success' : 'box-danger'; ?>">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Billing Invoice Status</h3>
      </div>
      <div class="box-body">
        <?php if($invoice): ?>
          <table class="table table-condensed">
            <tr><th>Invoice No:</th><td><code><?php echo $invoice->invoice_number; ?></code></td></tr>
            <tr><th>Base Amount:</th><td>₹<?php echo number_format($invoice->total_amount, 2); ?></td></tr>
            <tr><th>GST Tax (18%):</th><td>₹<?php echo number_format($invoice->tax_amount, 2); ?></td></tr>
            <tr style="font-size: 15px; font-weight: bold;">
              <th>Final Total:</th><td>₹<?php echo number_format($invoice->final_amount, 2); ?></td>
            </tr>
            <tr>
              <th>Status:</th>
              <td>
                <span class="label <?php echo ($invoice->status == 'Paid') ? 'label-success' : 'label-danger'; ?>">
                  <?php echo $invoice->status; ?>
                </span>
              </td>
            </tr>
          </table>
          
          <?php if($invoice->status == 'Unpaid' && $this->session->userdata('role_id') != 4): ?>
            <!-- Receive Payment Form (Staff only) -->
            <hr>
            <h5 style="font-weight: 700;">Receive Payment Handover</h5>
            <?php echo form_open('payments/receive/' . $invoice->id); ?>
              <div class="form-group">
                <select name="payment_mode" class="form-control input-sm" required>
                  <option value="Cash">Cash</option>
                  <option value="UPI">UPI / QR Scan</option>
                  <option value="Card">Credit/Debit Card</option>
                  <option value="Bank Transfer">Direct Wire</option>
                </select>
              </div>
              <div class="form-group">
                <input type="text" name="transaction_id" class="form-control input-sm" placeholder="Txn ID / Ref No">
              </div>
              <div class="form-group">
                <input type="text" name="remarks" class="form-control input-sm" placeholder="Remarks">
              </div>
              <button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-money"></i> Process Receipt Payment</button>
            <?php echo form_close(); ?>
          <?php endif; ?>

        <?php else: ?>
          <p class="text-muted">Invoice not generated.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- File Upload Attachment Card -->
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-paperclip"></i> Consignment Documents</h3>
      </div>
      <div class="box-body">
        <ul class="list-unstyled" style="padding-left: 0;">
          <?php foreach($documents as $doc): ?>
            <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
              <a href="<?php echo base_url($doc->file_path); ?>" target="_blank" class="text-info">
                <i class="fa fa-file-pdf-o text-red"></i> <strong><?php echo $doc->doc_type; ?></strong>
              </a>
              <span class="pull-right text-muted" style="font-size: 11px;"><?php echo date('d M Y', strtotime($doc->created_at)); ?></span>
            </li>
          <?php endforeach; ?>
        </ul>

        <?php if($this->session->userdata('role_id') != 4): ?>
          <hr>
          <!-- Upload form -->
          <?php echo form_open_multipart('shipments/upload-doc/' . $shipment->id); ?>
            <div class="form-group">
              <label>Select Document Type</label>
              <select name="doc_type" class="form-control input-sm" required>
                <option value="Exporter Invoice">Exporter Invoice</option>
                <option value="Packing List">Packing List</option>
                <option value="Passport Copy">Passport Copy</option>
                <option value="Customs Documents">Customs Documents</option>
              </select>
            </div>
            <div class="form-group">
              <input type="file" name="doc_file" class="form-control input-sm" required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fa fa-upload"></i> Attach Document File</button>
          <?php echo form_close(); ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Manual Tracking Update (Staff Only) -->
    <?php if($this->session->userdata('role_id') != 4): ?>
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-truck"></i> Update Transit movement</h3>
        </div>
        <div class="box-body">
          <?php if($shipment->verification_status == 'Pending'): ?>
            <div class="alert alert-danger" style="font-size:12px; border-radius: 8px;">
              <i class="fa fa-ban"></i> <strong>BLOCKED:</strong> Customer verification is incomplete.
            </div>
          <?php else: ?>
            <?php echo form_open('shipment/add_tracking_stage'); ?>
              <input type="hidden" name="shipment_id" value="<?php echo $shipment->id; ?>">
              <div class="form-group">
                <label>Movement Status Stage</label>
                <select name="status" class="form-control input-sm" required>
                  <option value="Received At Branch">Received At Branch</option>
                  <option value="Received At Origin Hub">Received At Origin Hub</option>
                  <option value="Customs Clearance">Customs Clearance</option>
                  <option value="Departed Origin">Departed Origin</option>
                  <option value="Arrived Destination">Arrived Destination</option>
                  <option value="Out For Delivery">Out For Delivery</option>
                  <option value="Delivered">Delivered (Completes booking)</option>
                  <option value="Returned">Returned to Origin</option>
                  <option value="Exception">Exception (Delay/Alert)</option>
                </select>
              </div>
              <div class="form-group">
                <label>Date and Time of Update</label>
                <input type="datetime-local" name="date_time" class="form-control input-sm" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
              </div>
              <div class="form-group">
                <label>Remarks / Location Details</label>
                <input type="text" name="remarks" class="form-control input-sm" placeholder="e.g. Cleared New Delhi customs gate." required>
              </div>
              <button type="submit" class="btn btn-warning btn-sm btn-block"><i class="fa fa-save"></i> Post Movement Update</button>
            <?php echo form_close(); ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</div>
