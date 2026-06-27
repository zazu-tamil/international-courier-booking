<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Edit International Shipment Booking - <strong><?php echo $shipment->awb_number; ?></strong></h3>
        <div class="pull-right">
          <a href="<?php echo site_url('shipments'); ?>" class="btn btn-default btn-sm" style="margin-right: 5px;"><i class="fa fa-list"></i> Back to Shipment List</a>
          <span class="label label-danger" style="font-size: 12px; padding: 5px; display: inline-block;">Office Staff Only</span>
        </div>
      </div>
      
      <?php echo form_open('shipments/edit/' . $shipment->id, array('id' => 'bookingForm')); ?>
        <div class="box-body" style="padding: 20px;">
          
          <!-- Tab Navigation -->
          <ul class="nav nav-tabs nav-justified" id="bookingTabs" style="margin-bottom: 25px;">
            <li class="active"><a href="#tab-sender" data-toggle="tab">Tab 1: Sender Info</a></li>
            <li><a href="#tab-receiver" data-toggle="tab">Tab 2: Receiver Info</a></li>
            <li><a href="#tab-consignment" data-toggle="tab" id="nav-tab-consignment">Tab 3: Consignment</a></li>
            <li><a href="#tab-boxes" data-toggle="tab" id="nav-tab-boxes">Tab 4: Box Details</a></li>
            <li><a href="#tab-contents" data-toggle="tab">Tab 5: Contents</a></li>
            <li><a href="#tab-status" data-toggle="tab">Tab 6: Review & Save</a></li>
          </ul>

          <div class="tab-content">
            
            <!-- TAB 1: SENDER INFORMATION -->
            <div class="tab-pane active" id="tab-sender">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-user"></i> Exporter / Sender Details</h4>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Sender Full Name <span class="text-danger">*</span></label>
                  <input type="text" name="sender_name" class="form-control" placeholder="Exporter Name" value="<?php echo htmlspecialchars($shipment->sender_name); ?>" required>
                </div>
                <div class="col-md-6 form-group">
                  <label>Company Name</label>
                  <input type="text" name="sender_company" class="form-control" placeholder="Sender Business Legal Name" value="<?php echo htmlspecialchars($shipment->sender_company); ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Mobile Number <span class="text-danger">*</span></label>
                  <input type="text" name="sender_mobile" class="form-control" placeholder="Primary phone number" value="<?php echo htmlspecialchars($shipment->sender_mobile); ?>" required>
                </div>
                <div class="col-md-6 form-group">
                  <label>Alternate Mobile</label>
                  <input type="text" name="sender_alt_mobile" class="form-control" placeholder="Secondary contact number" value="<?php echo htmlspecialchars($shipment->sender_alt_mobile); ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Email Address</label>
                  <input type="email" name="sender_email" class="form-control" placeholder="sender@example.com" value="<?php echo htmlspecialchars($shipment->sender_email); ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label>ZIP / Postal Code <span class="text-danger">*</span></label>
                  <input type="text" name="sender_zip" class="form-control" placeholder="PIN/ZIP code" value="<?php echo htmlspecialchars($shipment->sender_zip); ?>" required>
                </div>
              </div>
              <div class="form-group">
                <label>Street Address <span class="text-danger">*</span></label>
                <textarea name="sender_address" class="form-control" rows="2" placeholder="Full pickup address" required><?php echo htmlspecialchars($shipment->sender_address); ?></textarea>
              </div>
              <div class="row">
                <div class="col-md-4 form-group">
                  <label>City <span class="text-danger">*</span></label>
                  <input type="text" name="sender_city" class="form-control" value="<?php echo htmlspecialchars($shipment->sender_city); ?>" required>
                </div>
                <div class="col-md-4 form-group">
                  <label>State <span class="text-danger">*</span></label>
                  <input type="text" name="sender_state" class="form-control" value="<?php echo htmlspecialchars($shipment->sender_state); ?>" required>
                </div>
                <div class="col-md-4 form-group">
                  <label>Country <span class="text-danger">*</span></label>
                  <select name="sender_country_id" class="form-control" required>
                    <option value="">Select country</option>
                    <?php foreach($countries as $c): ?>
                      <option value="<?php echo $c->id; ?>" <?php echo ($c->id == $shipment->origin_country_id) ? 'selected' : ''; ?>><?php echo $c->country_name; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              
              <div class="text-right" style="margin-top: 20px;">
                <button type="button" class="btn btn-primary" onclick="switchTab('#tab-receiver')">Next: Receiver Info <i class="fa fa-arrow-right"></i></button>
              </div>
            </div>

            <!-- TAB 2: RECEIVER INFORMATION -->
            <div class="tab-pane" id="tab-receiver">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-user"></i> Consignee / Receiver Details</h4>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Receiver Full Name <span class="text-danger">*</span></label>
                  <input type="text" name="receiver_name" class="form-control" placeholder="Consignee Name" value="<?php echo htmlspecialchars($shipment->receiver_name); ?>" required>
                </div>
                <div class="col-md-6 form-group">
                  <label>Company Name</label>
                  <input type="text" name="receiver_company" class="form-control" placeholder="Consignee Business Name" value="<?php echo htmlspecialchars($shipment->receiver_company); ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Mobile Number <span class="text-danger">*</span></label>
                  <input type="text" name="receiver_mobile" class="form-control" placeholder="Consignee phone number" value="<?php echo htmlspecialchars($shipment->receiver_mobile); ?>" required>
                </div>
                <div class="col-md-6 form-group">
                  <label>Alternate Mobile</label>
                  <input type="text" name="receiver_alt_mobile" class="form-control" placeholder="Secondary contact number" value="<?php echo htmlspecialchars($shipment->receiver_alt_mobile); ?>">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Email Address</label>
                  <input type="email" name="receiver_email" class="form-control" placeholder="receiver@example.com" value="<?php echo htmlspecialchars($shipment->receiver_email); ?>">
                </div>
                <div class="col-md-6 form-group">
                  <label>ZIP / Postal Code <span class="text-danger">*</span></label>
                  <input type="text" name="receiver_zip" class="form-control" placeholder="Destination zip code" value="<?php echo htmlspecialchars($shipment->receiver_zip); ?>" required>
                </div>
              </div>
              <div class="form-group">
                <label>Street Address <span class="text-danger">*</span></label>
                <textarea name="receiver_address" class="form-control" rows="2" placeholder="Full delivery address" required><?php echo htmlspecialchars($shipment->receiver_address); ?></textarea>
              </div>
              <div class="row">
                <div class="col-md-4 form-group">
                  <label>City <span class="text-danger">*</span></label>
                  <input type="text" name="receiver_city" class="form-control" value="<?php echo htmlspecialchars($shipment->receiver_city); ?>" required>
                </div>
                <div class="col-md-4 form-group">
                  <label>State <span class="text-danger">*</span></label>
                  <input type="text" name="receiver_state" class="form-control" value="<?php echo htmlspecialchars($shipment->receiver_state); ?>" required>
                </div>
                <div class="col-md-4 form-group">
                  <label>Country <span class="text-danger">*</span></label>
                  <select name="receiver_country_id" class="form-control" required>
                    <option value="">Select country</option>
                    <?php foreach($countries as $c): ?>
                      <option value="<?php echo $c->id; ?>" <?php echo ($c->id == $shipment->destination_country_id) ? 'selected' : ''; ?>><?php echo $c->country_name; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div style="margin-top: 20px; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-default" onclick="switchTab('#tab-sender')"><i class="fa fa-arrow-left"></i> Back</button>
                <button type="button" class="btn btn-primary" onclick="switchTab('#tab-consignment')">Next: Consignment <i class="fa fa-arrow-right"></i></button>
              </div>
            </div>

            <!-- TAB 3: CONSIGNMENT INFORMATION -->
            <div class="tab-pane" id="tab-consignment">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-info-circle"></i> Consignment Specifications</h4>
              
              <!-- Restricted items warning alerts box -->
              <div class="alert alert-warning" id="restricted_warn_box" style="display: none; border-radius: 8px;">
                <h5><i class="fa fa-warning"></i> <strong>Prohibited Commodity Alert for Destination country</strong></h5>
                <p id="restricted_warn_text"></p>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label>AWB Number</label>
                  <input type="text" class="form-control" value="<?php echo $shipment->awb_number; ?>" disabled style="background-color: #f7f9fa; font-weight: bold; color: #000;">
                </div>
                <div class="col-md-6 form-group">
                  <label>Booking Date <span class="text-danger">*</span></label>
                  <input type="date" name="booking_date" class="form-control" value="<?php echo $shipment->booking_date; ?>" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Exporter Customer Account <span class="text-danger">*</span></label>
                  <select name="customer_id" class="form-control" required>
                    <option value="">Select customer login profile</option>
                    <?php foreach($customers as $c): ?>
                      <option value="<?php echo $c->id; ?>" <?php echo ($c->id == $shipment->customer_id) ? 'selected' : ''; ?>><?php echo $c->name; ?> <?php echo $c->company_name ? '('.$c->company_name.')' : ''; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6 form-group">
                  <label>Courier Partner <span class="text-danger">*</span></label>
                  <select name="courier_partner_id" class="form-control" required>
                    <option value="">Select partner</option>
                    <?php foreach($partners as $p): ?>
                      <option value="<?php echo $p->id; ?>" <?php echo ($p->id == $shipment->courier_partner_id) ? 'selected' : ''; ?>><?php echo $p->partner_name; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Origin Country <span class="text-danger">*</span></label>
                  <select name="origin_country_id" id="origin_country_id" class="form-control" required>
                    <option value="">Select Origin</option>
                    <?php foreach($countries as $c): ?>
                      <option value="<?php echo $c->id; ?>" <?php echo ($c->id == $shipment->origin_country_id) ? 'selected' : ''; ?>><?php echo $c->country_name; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6 form-group">
                  <label>Destination Country <span class="text-danger">*</span></label>
                  <select name="destination_country_id" id="destination_country_id" class="form-control" required>
                    <option value="">Select Destination</option>
                    <?php foreach($countries as $c): ?>
                      <option value="<?php echo $c->id; ?>" <?php echo ($c->id == $shipment->destination_country_id) ? 'selected' : ''; ?> data-restricted="<?php echo htmlspecialchars($c->restricted_items); ?>"><?php echo $c->country_name; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Service Type <span class="text-danger">*</span></label>
                  <select name="service_type" id="service_type" class="form-control" required>
                    <option value="">Select Service</option>
                    <?php foreach($service_types as $st): ?>
                      <option value="<?php echo htmlspecialchars($st->service_name); ?>" <?php echo ($shipment->service_type == $st->service_name) ? 'selected' : ''; ?>><?php echo htmlspecialchars($st->service_name); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6 form-group">
                  <label>Shipment Type <span class="text-danger">*</span></label>
                  <select name="shipment_type" class="form-control" required>
                    <option value="Non-Documents" <?php echo ($shipment->shipment_type == 'Non-Documents') ? 'selected' : ''; ?>>Non-Documents (Commercial Goods / Parcels)</option>
                    <option value="Documents" <?php echo ($shipment->shipment_type == 'Documents') ? 'selected' : ''; ?>>Documents (Paper / Files)</option>
                  </select>
                </div>
              </div>

              <div style="margin-top: 20px; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-default" onclick="switchTab('#tab-receiver')"><i class="fa fa-arrow-left"></i> Back</button>
                <button type="button" class="btn btn-primary" onclick="switchTab('#tab-boxes')">Next: Box details <i class="fa fa-arrow-right"></i></button>
              </div>
            </div>

            <!-- TAB 4: BOX DETAILS (DYNAMIC GRID) -->
            <div class="tab-pane" id="tab-boxes">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-cube"></i> Dimension & Weight details</h4>
              <p class="text-muted">Enter dimensions of boxes to calculate volumetric weights. Volumetric weight formula: <code>Length x Width x Height ÷ 5000</code>.</p>
              
              <table class="table table-bordered table-striped" id="boxTable">
                <thead>
                  <tr class="bg-gray">
                    <th>Box No</th>
                    <th>Length (cm)</th>
                    <th>Width (cm)</th>
                    <th>Height (cm)</th>
                    <th>Actual Weight (kg)</th>
                    <th>Volumetric (kg)</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($boxes)): ?>
                    <?php foreach($boxes as $idx => $box): ?>
                      <tr class="box-row">
                        <td><input type="text" name="box_number[]" class="form-control box-num" value="<?php echo $box->box_number; ?>" readonly style="background-color:#eee;"></td>
                        <td><input type="number" step="0.1" name="length[]" class="form-control box-len" value="<?php echo $box->length; ?>" placeholder="cm" required min="1"></td>
                        <td><input type="number" step="0.1" name="width[]" class="form-control box-width" value="<?php echo $box->width; ?>" placeholder="cm" required min="1"></td>
                        <td><input type="number" step="0.1" name="height[]" class="form-control box-height" value="<?php echo $box->height; ?>" placeholder="cm" required min="1"></td>
                        <td><input type="number" step="0.001" name="actual_weight[]" class="form-control box-actual" value="<?php echo $box->actual_weight; ?>" placeholder="kg" required min="0.01"></td>
                        <td><input type="text" name="volumetric_weight[]" class="form-control box-volumetric" value="<?php echo $box->volumetric_weight; ?>" readonly style="background-color:#eee; font-weight:bold;"></td>
                        <td><button type="button" class="btn btn-danger btn-sm delete-box-btn" <?php echo ($idx == 0) ? 'disabled' : ''; ?>><i class="fa fa-trash"></i></button></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr class="box-row">
                      <td><input type="text" name="box_number[]" class="form-control box-num" value="1" readonly style="background-color:#eee;"></td>
                      <td><input type="number" step="0.1" name="length[]" class="form-control box-len" placeholder="cm" required min="1"></td>
                      <td><input type="number" step="0.1" name="width[]" class="form-control box-width" placeholder="cm" required min="1"></td>
                      <td><input type="number" step="0.1" name="height[]" class="form-control box-height" placeholder="cm" required min="1"></td>
                      <td><input type="number" step="0.001" name="actual_weight[]" class="form-control box-actual" placeholder="kg" required min="0.01"></td>
                      <td><input type="text" name="volumetric_weight[]" class="form-control box-volumetric" readonly style="background-color:#eee; font-weight:bold;"></td>
                      <td><button type="button" class="btn btn-danger btn-sm delete-box-btn" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>

              <button type="button" class="btn btn-success btn-sm" id="addBoxBtn" style="margin-bottom: 25px;"><i class="fa fa-plus"></i> Add Box Row</button>

              <!-- Calculated weight summaries -->
              <div class="row well well-sm" style="margin: 0; background: #fcfcfc;">
                <div class="col-sm-4 text-center">
                  <h5>Total Actual Weight:</h5>
                  <h4 style="font-weight: 700;" id="total_actual_text"><?php echo $shipment->total_weight; ?> kg</h4>
                  <input type="hidden" name="total_weight_val" id="total_weight_val" value="<?php echo $shipment->total_weight; ?>">
                </div>
                <div class="col-sm-4 text-center" style="border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                  <h5>Total Volumetric Weight:</h5>
                  <h4 style="font-weight: 700;" id="total_volumetric_text"><?php echo $shipment->total_volumetric_weight; ?> kg</h4>
                  <input type="hidden" name="total_volumetric_val" id="total_volumetric_val" value="<?php echo $shipment->total_volumetric_weight; ?>">
                </div>
                <div class="col-sm-4 text-center">
                  <h5 class="text-blue"><strong>Chargeable Weight:</strong></h5>
                  <h3 style="font-weight: 700; margin-top: 5px;" class="text-blue" id="chargeable_weight_text"><?php echo $shipment->chargeable_weight; ?> kg</h3>
                  <input type="hidden" name="chargeable_weight_val" id="chargeable_weight_val" value="<?php echo $shipment->chargeable_weight; ?>">
                </div>
              </div>

              <!-- Rate calculations details -->
              <div class="row" style="margin-top: 25px;">
                <div class="col-md-6 col-md-offset-3">
                  <div class="panel panel-info" style="border-radius: 8px;">
                    <div class="panel-heading" style="font-weight: bold;"><i class="fa fa-calculator"></i> Shipping cost calculator</div>
                    <div class="panel-body text-center" style="padding: 20px;">
                      <button type="button" class="btn btn-info btn-block btn-lg" id="btn-calc-charges"><i class="fa fa-refresh"></i> Look up rates</button>
                      
                      <div id="cost_summary_box" style="display: none; margin-top: 20px; text-align: left;">
                        <table class="table table-condensed">
                          <tr><th>Base Rate:</th><td class="text-right" id="res_base">₹0.00</td></tr>
                           <tr><th>Fuel Surcharge (<span id="res_fuel_p">0</span>%):</th><td class="text-right" id="res_fuel">₹0.00</td></tr>
                          <tr><th>Handling Charges:</th><td class="text-right" id="res_handling">₹0.00</td></tr>
                          <tr><th>Insurance Charges:</th><td class="text-right" id="res_insurance">₹0.00</td></tr>
                          <tr class="info" style="font-size: 16px; font-weight: bold;">
                            <th>Estimated Total:</th><td class="text-right" id="res_total">₹<?php echo number_format($shipment->estimated_charges, 2); ?></td>
                          </tr>
                        </table>
                      </div>
                      
                      <div class="form-group text-left" style="margin-top: 20px;">
                        <label>Confirm / Enter Billing Charges (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-addon" style="font-weight: bold; background: #eee;">₹</span>
                          <input type="number" step="0.01" min="0.01" name="estimated_charges_val" id="estimated_charges_val" class="form-control input-lg" style="font-weight: bold; color: #0073b7;" value="<?php echo $shipment->estimated_charges; ?>" placeholder="0.00" required>
                        </div>
                        <p class="help-block" style="font-size: 11px;">You can look up rates using the button above or type the billing amount manually.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div style="margin-top: 20px; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-default" onclick="switchTab('#tab-consignment')"><i class="fa fa-arrow-left"></i> Back</button>
                <button type="button" class="btn btn-primary" onclick="switchTab('#tab-contents')">Next: Contents <i class="fa fa-arrow-right"></i></button>
              </div>
            </div>

            <!-- TAB 5: SHIPMENT CONTENTS -->
            <div class="tab-pane" id="tab-contents">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-list"></i> Consignment contents invoice items</h4>
              <p class="text-muted">Enter individual items inside the package for customs declaration invoices.</p>

              <table class="table table-bordered table-striped" id="itemTable">
                <thead>
                  <tr class="bg-gray">
                    <th>Description</th>
                    <th>HS Code</th>
                    <th style="width: 100px;">Qty</th>
                    <th style="width: 150px;">Unit Value (₹)</th>
                    <th style="width: 150px;">Total Value (₹)</th>
                    <th>Country of Origin</th>
                    <th style="width: 80px;">Box No</th>
                    <th style="width: 50px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($items)): ?>
                    <?php foreach($items as $idx => $item): ?>
                      <tr class="item-row">
                        <td><input type="text" name="item_desc[]" class="form-control" value="<?php echo htmlspecialchars($item->item_description); ?>" placeholder="Item description" required></td>
                        <td><input type="text" name="item_hscode[]" class="form-control" value="<?php echo htmlspecialchars($item->hs_code); ?>" placeholder="HS code"></td>
                        <td><input type="number" name="item_qty[]" class="form-control item-qty" value="<?php echo $item->quantity; ?>" required min="1"></td>
                        <td><input type="number" step="0.01" name="item_value[]" class="form-control item-val" value="<?php echo $item->unit_value; ?>" placeholder="0.00" required min="0.01"></td>
                        <td><input type="text" name="item_total[]" class="form-control item-total" value="<?php echo $item->total_value; ?>" readonly style="background-color:#eee; font-weight:bold;"></td>
                        <td>
                          <select name="item_origin[]" class="form-control" required>
                            <option value="">Select country</option>
                            <?php foreach($countries as $c): ?>
                              <option value="<?php echo $c->id; ?>" <?php echo ($c->id == $item->country_of_origin_id) ? 'selected' : ''; ?>><?php echo $c->country_name; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                        <td><input type="number" name="item_box_no[]" class="form-control" value="<?php echo isset($item->box_no) && $item->box_no ? $item->box_no : 1; ?>" required min="1"></td>
                        <td><button type="button" class="btn btn-danger btn-sm delete-item-btn" <?php echo ($idx == 0) ? 'disabled' : ''; ?>><i class="fa fa-trash"></i></button></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr class="item-row">
                      <td><input type="text" name="item_desc[]" class="form-control" placeholder="Item description" required></td>
                      <td><input type="text" name="item_hscode[]" class="form-control" placeholder="HS code"></td>
                      <td><input type="number" name="item_qty[]" class="form-control item-qty" value="1" required min="1"></td>
                      <td><input type="number" step="0.01" name="item_value[]" class="form-control item-val" placeholder="0.00" required min="0.01"></td>
                      <td><input type="text" name="item_total[]" class="form-control item-total" readonly style="background-color:#eee; font-weight:bold;"></td>
                      <td>
                        <select name="item_origin[]" class="form-control" required>
                          <option value="">Select country</option>
                          <?php foreach($countries as $c): ?>
                            <option value="<?php echo $c->id; ?>" <?php echo ($c->id == 1) ? 'selected' : ''; ?>><?php echo $c->country_name; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td><input type="number" name="item_box_no[]" class="form-control" value="1" required min="1"></td>
                      <td><button type="button" class="btn btn-danger btn-sm delete-item-btn" disabled><i class="fa fa-trash"></i></button></td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>

              <button type="button" class="btn btn-success btn-sm" id="addItemBtn" style="margin-bottom: 25px;"><i class="fa fa-plus"></i> Add Item Row</button>

              <div class="row well well-sm" style="margin: 0; background: #fcfcfc;">
                <div class="col-xs-12 text-right">
                  <h4>Total Declared Customs Value: <strong class="text-blue" id="declared_value_text">₹<?php echo number_format($shipment->total_declared_value, 2); ?></strong></h4>
                  <input type="hidden" name="total_declared_val" id="total_declared_val" value="<?php echo $shipment->total_declared_value; ?>">
                </div>
              </div>

              <div style="margin-top: 20px; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-default" onclick="switchTab('#tab-boxes')"><i class="fa fa-arrow-left"></i> Back</button>
                <button type="button" class="btn btn-primary" onclick="switchTab('#tab-status')">Next: Review & Save <i class="fa fa-arrow-right"></i></button>
              </div>
            </div>

            <!-- TAB 6: REVIEW & SAVE -->
            <div class="tab-pane" id="tab-status">
              <h4 style="font-weight: 700; margin-bottom: 20px;" class="text-blue"><i class="fa fa-shield"></i> Save Modified Booking</h4>
              <p class="text-muted">Save your edits and update the system records:</p>

              <div class="row">
                <div class="col-md-6 col-md-offset-3">
                  <div class="panel panel-warning" style="border-radius: 8px;">
                    <div class="panel-heading" style="font-weight: bold;"><i class="fa fa-warning"></i> Attention Needed</div>
                    <div class="panel-body">
                      <ul>
                        <li>Recalculated weights and booking rates will update the customer ledger debit statement.</li>
                        <li>Existing invoices and accounts records will be updated automatically with new price quotes.</li>
                        <li>Digital signature and customer KYC records are preserved.</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <a href="<?php echo site_url('shipments'); ?>" class="btn btn-default btn-lg"><i class="fa fa-arrow-left"></i> Back to Shipment List</a>
                <div>
                  <button type="button" class="btn btn-default btn-lg" onclick="switchTab('#tab-contents')"><i class="fa fa-arrow-left"></i> Back</button>
                  <button type="button" class="btn btn-success btn-lg" id="submitBookingForm"><i class="fa fa-check-circle"></i> Save & Update Booking</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- jQuery Wizard and Dynamic Calculators Scripts -->
<script>
  function switchTab(selector) {
    $('#bookingTabs a[href="' + selector + '"]').tab('show');
  }

  $(document).ready(function() {
    
    // Alert restricted items warning on selecting destination country
    $('#destination_country_id').change(function() {
      var selected = $(this).find('option:selected');
      var restricted = selected.data('restricted');
      if (restricted) {
        $('#restricted_warn_text').html(restricted);
        $('#restricted_warn_box').slideDown();
      } else {
        $('#restricted_warn_box').slideUp();
      }
    });

    // Run restricted items warning check on load
    $('#destination_country_id').trigger('change');

    // BOX DETAILS DYNAMIC GRID ADD/REMOVE & AUTO-CALCULATORS
    function calculateBoxVolumetric(row) {
      var len = parseFloat(row.find('.box-len').val()) || 0;
      var width = parseFloat(row.find('.box-width').val()) || 0;
      var height = parseFloat(row.find('.box-height').val()) || 0;
      
      var volumetric = (len * width * height) / 5000;
      row.find('.box-volumetric').val(volumetric.toFixed(3));
      return volumetric;
    }

    function sumWeights() {
      var total_actual = 0;
      var total_volumetric = 0;
      
      $('.box-row').each(function() {
        var row = $(this);
        calculateBoxVolumetric(row);
        
        var act = parseFloat(row.find('.box-actual').val()) || 0;
        var vol = parseFloat(row.find('.box-volumetric').val()) || 0;
        
        total_actual += act;
        total_volumetric += vol;
      });

      $('#total_actual_text').text(total_actual.toFixed(3) + ' kg');
      $('#total_volumetric_text').text(total_volumetric.toFixed(3) + ' kg');
      
      $('#total_weight_val').val(total_actual.toFixed(3));
      $('#total_volumetric_val').val(total_volumetric.toFixed(3));

      // Chargeable weight = MAX of actual and volumetric
      var chargeable = Math.max(total_actual, total_volumetric);
      $('#chargeable_weight_text').text(chargeable.toFixed(3) + ' kg');
      $('#chargeable_weight_val').val(chargeable.toFixed(3));
    }

    // Bind inputs keyup/change
    $(document).on('keyup change', '.box-len, .box-width, .box-height, .box-actual', function() {
      sumWeights();
    });

    // Add Box Row
    $('#addBoxBtn').click(function() {
      var rowCount = $('.box-row').length + 1;
      var newRow = $('.box-row:first').clone();
      
      newRow.find('input').val('');
      newRow.find('.box-num').val(rowCount);
      newRow.find('.delete-box-btn').prop('disabled', false);
      
      $('#boxTable tbody').append(newRow);
      sumWeights();
    });

    // Remove Box Row
    $(document).on('click', '.delete-box-btn', function() {
      $(this).closest('.box-row').remove();
      
      // Re-number box rows
      var idx = 1;
      $('.box-row').each(function() {
        $(this).find('.box-num').val(idx++);
      });
      
      sumWeights();
    });

    // CONTENTS DYNAMIC GRID ADD/REMOVE & AUTO-CALCULATORS
    function calculateItemValue(row) {
      var qty = parseInt(row.find('.item-qty').val()) || 0;
      var val = parseFloat(row.find('.item-val').val()) || 0;
      var total = qty * val;
      row.find('.item-total').val(total.toFixed(2));
      return total;
    }

    function sumInvoiceValues() {
      var total_declared = 0;
      $('.item-row').each(function() {
        var row = $(this);
        var t = calculateItemValue(row);
        total_declared += t;
      });
      $('#declared_value_text').text('₹' + total_declared.toLocaleString('en-IN', {minimumFractionDigits: 2}));
      $('#total_declared_val').val(total_declared.toFixed(2));
    }

    $(document).on('keyup change', '.item-qty, .item-val', function() {
      sumInvoiceValues();
    });

    $('#addItemBtn').click(function() {
      var newRow = $('.item-row:first').clone();
      newRow.find('input').val('');
      newRow.find('.item-qty').val(1);
      newRow.find('.delete-item-btn').prop('disabled', false);
      $('#itemTable tbody').append(newRow);
      sumInvoiceValues();
    });

    $(document).on('click', '.delete-item-btn', function() {
      $(this).closest('.item-row').remove();
      sumInvoiceValues();
    });

    // LOOK UP ESTIMATED RATES VIA AJAX
    $('#btn-calc-charges').click(function() {
      var origin = $('#origin_country_id').val();
      var dest = $('#destination_country_id').val();
      var service = $('#service_type').val();
      var weight = $('#chargeable_weight_val').val();

      if (!origin || !dest || !service || !weight || parseFloat(weight) <= 0) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'Missing Parameters',
            text: 'Please configure routing (Tab 3) and box weight slab totals (Tab 4) before looking up rates.'
          });
        } else {
          alert('Missing Parameters: Please configure routing (Tab 3) and box weight totals (Tab 4) before looking up rates.');
        }
        return;
      }

      $.ajax({
        url: '<?php echo site_url("shipment/get_estimated_charges"); ?>',
        type: 'POST',
        data: {
          origin: origin,
          dest: dest,
          service: service,
          weight: weight,
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            var data = response.data;
            $('#res_base').text('₹' + parseFloat(data.base_rate).toFixed(2));
            $('#res_fuel_p').text(data.fuel_percentage);
            $('#res_fuel').text('₹' + parseFloat(data.fuel_surcharge).toFixed(2));
            $('#res_handling').text('₹' + parseFloat(data.handling_charges).toFixed(2));
            $('#res_insurance').text('₹' + parseFloat(data.insurance_charges).toFixed(2));
            $('#res_total').text('₹' + parseFloat(data.total_charges).toFixed(2));
            
            $('#estimated_charges_val').val(data.total_charges.toFixed(2));
            $('#cost_summary_box').slideDown();
          } else {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'error',
                title: 'Pricing Error',
                text: response.message
              });
            } else {
              alert('Pricing Error: ' + response.message);
            }
            $('#cost_summary_box').slideUp();
            // Do not reset the value if the user wants to type it manually
          }
        }
      });
    });

    // Intercept submit button click to handle tab-aware HTML5 validation programmatically
    $('#submitBookingForm').click(function(e) {
      e.preventDefault();
      var form = document.getElementById('bookingForm');
      
      if (!form.checkValidity()) {
        var invalid = form.querySelector(':invalid');
        if (invalid) {
          var tabPane = $(invalid).closest('.tab-pane');
          if (tabPane.length) {
            var tabId = tabPane.attr('id');
            switchTab('#' + tabId);
            setTimeout(function() {
              invalid.focus();
              if (typeof invalid.reportValidity === 'function') {
                invalid.reportValidity();
              }
            }, 200);
          }
        }
        return false;
      }

      var est = parseFloat($('#estimated_charges_val').val()) || 0;
      if (est <= 0) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'Calculate shipping charges',
            text: 'Please run the rates lookup calculator in Tab 4 or type a manual billing amount before submitting.'
          });
        } else {
          alert('Calculate shipping charges: Please run the rates lookup calculator in Tab 4 or type a manual billing amount before submitting.');
        }
        switchTab('#tab-boxes');
        return false;
      }

      form.submit();
    });

    // Initialize/trigger recalculations on load
    sumWeights();
    sumInvoiceValues();
    $('#btn-calc-charges').trigger('click');

  });
</script>
