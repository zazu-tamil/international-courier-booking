<div class="row">
  <div class="col-xs-12">
    <!-- KPI Summary Row -->
    <div class="row" style="margin-bottom: 5px;">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua" style="border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
          <span class="info-box-icon" style="border-radius: 6px 0 0 6px;"><i class="fa fa-calculator"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Rate Slabs</span>
            <span class="info-box-number" style="font-size: 22px;"><?php echo number_format($total_count); ?></span>
            <div class="progress" style="margin: 5px 0;"><div class="progress-bar" style="width: 100%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Configured in Matrix v2</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green" style="border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
          <span class="info-box-icon" style="border-radius: 6px 0 0 6px;"><i class="fa fa-check-square-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Active Slabs</span>
            <span class="info-box-number" style="font-size: 22px;"><?php echo number_format($active_count); ?></span>
            <div class="progress" style="margin: 5px 0;"><div class="progress-bar" style="width: <?php echo ($total_count > 0) ? round(($active_count/$total_count)*100) : 0; ?>%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Live for Booking &amp; Quotes</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow" style="border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
          <span class="info-box-icon" style="border-radius: 6px 0 0 6px;"><i class="fa fa-globe"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Destinations</span>
            <span class="info-box-number" style="font-size: 22px;"><?php echo number_format($countries_count); ?></span>
            <div class="progress" style="margin: 5px 0;"><div class="progress-bar" style="width: 100%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Unique Target Countries</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple" style="border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
          <span class="info-box-icon" style="border-radius: 6px 0 0 6px;"><i class="fa fa-truck"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Courier Partners</span>
            <span class="info-box-number" style="font-size: 22px;"><?php echo number_format($partners_count); ?></span>
            <div class="progress" style="margin: 5px 0;"><div class="progress-bar" style="width: 100%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Integrated Providers</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter & Master Card -->
    <div class="box box-primary" style="border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
      <div class="box-header with-border" style="padding: 12px 15px;">
        <h3 class="box-title" style="font-size: 17px; font-weight: 600;">
          <i class="fa fa-sliders text-primary"></i> Shipping Rates Matrix v2
        </h3>
        <div class="box-tools pull-right" style="top: 8px;">
          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addRateModal" style="border-radius: 3px; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Add New Rate
          </button>
          <button type="button" class="btn btn-primary btn-sm bg-purple" data-toggle="modal" data-target="#importRateModal" style="border-radius: 3px; font-weight: 600;">
            <i class="fa fa-upload"></i> Import Excel / CSV
          </button>
          <?php
            $export_params = http_build_query(array_filter($filters));
            $export_url = site_url('rates/export') . ($export_params ? '?' . $export_params : '');
          ?>
          <a href="<?php echo $export_url; ?>" class="btn btn-info btn-sm" title="Export current rates to Excel (.xls)" style="border-radius: 3px; font-weight: 600;">
            <i class="fa fa-file-excel-o"></i> Export to Excel
          </a>
          <a href="<?php echo site_url('rates/template'); ?>" class="btn btn-default btn-sm" title="Download Excel template with sample data" style="border-radius: 3px;">
            <i class="fa fa-download text-green"></i> Sample Template
          </a>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="box-body" style="background-color: #fcfcfc; border-bottom: 1px solid #f0f0f0; padding: 15px;">
        <?php echo form_open('rates', array('id' => 'filterForm', 'class' => 'row', 'method' => 'post')); ?>
          <div class="col-md-3 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Destination Country</label>
            <select name="dest_country" class="form-control input-sm select2" style="width: 100%;">
              <option value="">All Destination Countries</option>
              <?php foreach ($countries as $c): ?>
                <option value="<?php echo $c->id; ?>" <?php echo (!empty($filters['destination_country_id']) && $filters['destination_country_id'] == $c->id) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($c->country_name); ?> (<?php echo $c->country_code; ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-2 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Courier Partner</label>
            <select name="partner" class="form-control input-sm select2" style="width: 100%;">
              <option value="">All Partners</option>
              <?php foreach ($courier_partners as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo (!empty($filters['courier_partner_id']) && $filters['courier_partner_id'] == $p->id) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($p->partner_name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-2 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Service Type</label>
            <select name="service_type" class="form-control input-sm">
              <option value="">All Service Types</option>
              <?php foreach ($service_types as $st): ?>
                <option value="<?php echo htmlspecialchars($st->service_name); ?>" <?php echo (!empty($filters['service_type']) && $filters['service_type'] == $st->service_name) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($st->service_name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Shipment Type</label>
            <select name="shipment_type" class="form-control input-sm">
              <option value="">All Shipment Types</option>
              <option value="Documents (Paper / Files)" <?php echo (!empty($filters['shipment_type']) && $filters['shipment_type'] == 'Documents (Paper / Files)') ? 'selected' : ''; ?>>Documents (Paper / Files)</option>
              <option value="Non-Documents (Commercial Goods / Parcels)" <?php echo (!empty($filters['shipment_type']) && $filters['shipment_type'] == 'Non-Documents (Commercial Goods / Parcels)') ? 'selected' : ''; ?>>Non-Documents (Commercial Goods / Parcels)</option>
              <option value="Non Documents ( Non Commercial Goods )" <?php echo (!empty($filters['shipment_type']) && $filters['shipment_type'] == 'Non Documents ( Non Commercial Goods )') ? 'selected' : ''; ?>>Non Documents ( Non Commercial Goods )</option>
            </select>
          </div>

          <div class="col-md-1 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Status</label>
            <select name="status" class="form-control input-sm">
              <option value="">All</option>
              <option value="Active" <?php echo (!empty($filters['status']) && $filters['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
              <option value="Inactive" <?php echo (!empty($filters['status']) && $filters['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
          </div>

          <div class="col-md-1 col-sm-6 form-group" style="padding-top: 22px;">
            <button type="submit" class="btn btn-primary btn-sm btn-block" title="Apply Filters">
              <i class="fa fa-filter"></i>
            </button>
            <?php if (!empty(array_filter($filters))): ?>
              <a href="<?php echo site_url('rates'); ?>" class="btn btn-default btn-xs btn-block" style="margin-top: 4px;" title="Clear Filters">
                <i class="fa fa-times text-danger"></i> Reset
              </a>
            <?php endif; ?>
          </div>
        <?php echo form_close(); ?>
      </div>

      <!-- Data Table -->
      <div class="box-body table-responsive">
        <table id="ratesV2Table" class="table table-bordered table-striped table-hover" style="width: 100%;">
          <thead>
            <tr style="background-color: #f7f9fa; color: #333;">
              <th style="width: 45px; text-align: center;">#</th>
              <th>Destination Country</th>
              <th>Courier Partner</th>
              <th>Service Type</th>
              <th>Shipment Type</th>
              <th style="text-align: right;">Weight (kg)</th>
              <th style="text-align: right;">Rate (INR)</th>
              <th style="text-align: center; width: 90px;">Status</th>
              <th style="text-align: center; width: 110px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($rates)): ?>
              <?php $sno = 1; foreach ($rates as $r): ?>
                <tr>
                  <td style="text-align: center; vertical-align: middle;"><?php echo $sno++; ?></td>
                  <td style="vertical-align: middle;">
                    <strong><i class="fa fa-globe text-primary" style="margin-right: 4px;"></i> <?php echo htmlspecialchars($r->destination_country); ?></strong>
                    <?php if (!empty($r->country_code)): ?>
                      <span class="label label-default" style="font-size: 10px; margin-left: 4px;"><?php echo htmlspecialchars($r->country_code); ?></span>
                    <?php endif; ?>
                  </td>
                  <td style="vertical-align: middle;">
                    <span class="badge bg-purple" style="font-size: 11px; padding: 4px 8px; font-weight: normal;">
                      <i class="fa fa-truck"></i> <?php echo htmlspecialchars($r->courier_partner_name); ?>
                    </span>
                  </td>
                  <td style="vertical-align: middle;">
                    <?php 
                      $service_badge = 'label-info';
                      if (stripos($r->service_type, 'Saver') !== false) $service_badge = 'label-warning';
                      if (stripos($r->service_type, 'Economy') !== false) $service_badge = 'label-success';
                    ?>
                    <span class="label <?php echo $service_badge; ?>" style="font-size: 11px;"><?php echo htmlspecialchars($r->service_type); ?></span>
                  </td>
                  <td style="vertical-align: middle;">
                    <?php if (stripos($r->shipment_type, 'Documents') !== false && stripos($r->shipment_type, 'Non-') === false): ?>
                      <i class="fa fa-file-text-o text-orange"></i>
                    <?php else: ?>
                      <i class="fa fa-cubes text-blue"></i>
                    <?php endif; ?>
                    <span style="font-size: 12px; margin-left: 3px;"><?php echo htmlspecialchars($r->shipment_type); ?></span>
                  </td>
                  <td style="text-align: right; vertical-align: middle; font-weight: 600;">
                    <code><?php echo number_format($r->weight, 3); ?> kg</code>
                  </td>
                  <td style="text-align: right; vertical-align: middle; font-size: 14px; font-weight: 700; color: #008d4c;">
                    ₹ <?php echo number_format($r->rate, 2); ?>
                  </td>
                  <td style="text-align: center; vertical-align: middle;">
                    <a href="<?php echo site_url('rates/toggle-status/' . $r->id); ?>" 
                       class="btn-toggle-status" 
                       data-id="<?php echo $r->id; ?>"
                       title="Click to toggle status">
                      <?php if ($r->status === 'Active'): ?>
                        <span class="label label-success" style="cursor: pointer;"><i class="fa fa-check"></i> Active</span>
                      <?php else: ?>
                        <span class="label label-danger" style="cursor: pointer;"><i class="fa fa-times"></i> Inactive</span>
                      <?php endif; ?>
                    </a>
                  </td>
                  <td style="text-align: center; vertical-align: middle; white-space: nowrap;">
                    <button type="button" 
                            class="btn btn-primary btn-xs btn-edit-rate" 
                            data-id="<?php echo $r->id; ?>" 
                            title="Edit Rate">
                      <i class="fa fa-pencil"></i> Edit
                    </button>
                    <a href="<?php echo site_url('rates/delete/' . $r->id); ?>" 
                       class="btn btn-danger btn-xs" 
                       onclick="return confirm('Are you sure you want to delete this rate slab for <?php echo htmlspecialchars(addslashes($r->destination_country)); ?>?');" 
                       title="Delete Rate">
                      <i class="fa fa-trash"></i> Delete
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="9" class="text-center" style="padding: 30px; color: #888;">
                  <i class="fa fa-folder-open-o fa-3x" style="color: #ccc; margin-bottom: 10px; display: block;"></i>
                  <strong>No shipping rates found matching the selected criteria.</strong><br>
                  <span style="font-size: 12px;">Click "Add New Rate" or "Import Excel / CSV" to populate the rate matrix.</span>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================== -->
<!-- 1. ADD NEW SHIPPING RATE MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="addRateModal" tabindex="-1" role="dialog" aria-labelledby="addRateModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <?php echo form_open('rates/add', array('id' => 'addRateForm')); ?>
      <div class="modal-content" style="border-radius: 6px; overflow: hidden;">
        <div class="modal-header bg-green" style="padding: 12px 15px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="addRateModalLabel" style="font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Add Shipping Rate Slab (v2)
          </h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          <div class="form-group">
            <label>Destination Country <span class="text-danger">*</span></label>
            <select name="destination_country_id" class="form-control select2" style="width: 100%;" required>
              <option value="">Select Destination Country</option>
              <?php foreach ($countries as $c): ?>
                <option value="<?php echo $c->id; ?>">
                  <?php echo htmlspecialchars($c->country_name); ?> (<?php echo $c->country_code; ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label>Courier Partner <span class="text-danger">*</span></label>
              <select name="courier_partner_id" class="form-control" required>
                <option value="">Select Courier Partner</option>
                <?php foreach ($courier_partners as $p): ?>
                  <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->partner_name); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Service Type <span class="text-danger">*</span></label>
              <select name="service_type" class="form-control" required>
                <option value="">Select Service Type</option>
                <?php foreach ($service_types as $st): ?>
                  <option value="<?php echo htmlspecialchars($st->service_name); ?>"><?php echo htmlspecialchars($st->service_name); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Shipment Type <span class="text-danger">*</span></label>
            <select name="shipment_type" class="form-control" required>
              <option value="Documents (Paper / Files)">Documents (Paper / Files)</option>
              <option value="Non-Documents (Commercial Goods / Parcels)">Non-Documents (Commercial Goods / Parcels)</option>
              <option value="Non Documents ( Non Commercial Goods )">Non Documents ( Non Commercial Goods )</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label>Weight (kg) <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number" step="0.001" min="0.001" name="weight" class="form-control" placeholder="e.g. 0.500" required>
                <span class="input-group-addon">kg</span>
              </div>
              <small class="help-block" style="margin-bottom: 0;">Weight slab breakpoint (e.g. 0.5, 1.0, 2.5)</small>
            </div>

            <div class="col-md-6 form-group">
              <label>Rate (INR ₹) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-addon">₹</span>
                <input type="number" step="0.01" min="0" name="rate" class="form-control" placeholder="e.g. 1450.00" required>
              </div>
              <small class="help-block" style="margin-bottom: 0;">Base applicable rate in INR</small>
            </div>
          </div>

          <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-control" required>
              <option value="Active">Active (Available for booking)</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer" style="background-color: #f7f9fa;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Rate Slab</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- 2. EDIT SHIPPING RATE MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="editRateModal" tabindex="-1" role="dialog" aria-labelledby="editRateModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <?php echo form_open('rates/edit', array('id' => 'editRateForm')); ?>
      <input type="hidden" name="id" id="edit_rate_id" value="">
      <div class="modal-content" style="border-radius: 6px; overflow: hidden;">
        <div class="modal-header bg-primary" style="padding: 12px 15px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="editRateModalLabel" style="font-weight: 600;">
            <i class="fa fa-pencil-square-o"></i> Edit Shipping Rate Slab
          </h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          <div class="form-group">
            <label>Destination Country <span class="text-danger">*</span></label>
            <select name="destination_country_id" id="edit_destination_country_id" class="form-control select2" style="width: 100%;" required>
              <option value="">Select Destination Country</option>
              <?php foreach ($countries as $c): ?>
                <option value="<?php echo $c->id; ?>">
                  <?php echo htmlspecialchars($c->country_name); ?> (<?php echo $c->country_code; ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label>Courier Partner <span class="text-danger">*</span></label>
              <select name="courier_partner_id" id="edit_courier_partner_id" class="form-control" required>
                <option value="">Select Courier Partner</option>
                <?php foreach ($courier_partners as $p): ?>
                  <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->partner_name); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Service Type <span class="text-danger">*</span></label>
              <select name="service_type" id="edit_service_type" class="form-control" required>
                <option value="">Select Service Type</option>
                <?php foreach ($service_types as $st): ?>
                  <option value="<?php echo htmlspecialchars($st->service_name); ?>"><?php echo htmlspecialchars($st->service_name); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Shipment Type <span class="text-danger">*</span></label>
            <select name="shipment_type" id="edit_shipment_type" class="form-control" required>
              <option value="Documents (Paper / Files)">Documents (Paper / Files)</option>
              <option value="Non-Documents (Commercial Goods / Parcels)">Non-Documents (Commercial Goods / Parcels)</option>
              <option value="Non Documents ( Non Commercial Goods )">Non Documents ( Non Commercial Goods )</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label>Weight (kg) <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number" step="0.001" min="0.001" name="weight" id="edit_weight" class="form-control" required>
                <span class="input-group-addon">kg</span>
              </div>
            </div>

            <div class="col-md-6 form-group">
              <label>Rate (INR ₹) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-addon">₹</span>
                <input type="number" step="0.01" min="0" name="rate" id="edit_rate" class="form-control" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" id="edit_status" class="form-control" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer" style="background-color: #f7f9fa;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Rate Slab</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- 3. IMPORT RATES EXCEL / CSV MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="importRateModal" tabindex="-1" role="dialog" aria-labelledby="importRateModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <?php echo form_open_multipart('rates/import', array('id' => 'importRateForm')); ?>
      <div class="modal-content" style="border-radius: 6px; overflow: hidden;">
        <div class="modal-header bg-purple" style="padding: 12px 15px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="importRateModalLabel" style="font-weight: 600;">
            <i class="fa fa-cloud-upload"></i> Import Shipping Rates Matrix from Excel
          </h4>
        </div>
        <div class="modal-body" style="padding: 20px;">
          <div class="callout callout-info" style="border-left-width: 4px; margin-bottom: 15px; font-size: 13px;">
            <h4><i class="fa fa-info-circle"></i> Instructions for Excel Import</h4>
            <p>Your uploaded sheet must contain the following columns in row 1:</p>
            <ul style="padding-left: 20px; margin-top: 5px;">
              <li><strong>Destination Country</strong> (e.g. <code>United States</code> or code <code>US</code>)</li>
              <li><strong>Service Type</strong> (e.g. <code>Express</code>, <code>Economy</code>)</li>
              <li><strong>Shipment Type</strong> (e.g. <code>Documents (Paper / Files)</code>)</li>
              <li><strong>Courier Partner</strong> (e.g. <code>DHL Express</code>, <code>FedEx</code>)</li>
              <li><strong>Weight</strong> in kg (e.g. <code>0.500</code>, <code>1.000</code>)</li>
              <li><strong>Rate</strong> in INR (e.g. <code>1450.00</code>)</li>
              <li><strong>Status</strong> (<code>Active</code> or <code>Inactive</code>)</li>
            </ul>
          </div>

          <div class="form-group">
            <label>Select Excel (.xls, .xlsx) or CSV File <span class="text-danger">*</span></label>
            <input type="file" name="excel_file" class="form-control" accept=".xls,.xlsx,.csv" required>
            <p class="help-block" style="font-size: 11px;">Supported formats: Microsoft Excel 97-2003 (.xls), Excel (.xlsx), or Comma-separated (.csv)</p>
          </div>

          <div class="form-group" style="background: #fafafa; border: 1px solid #e5e5e5; padding: 12px; border-radius: 4px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Duplicate Handling Strategy</label>
            <div class="radio" style="margin-top: 0;">
              <label>
                <input type="radio" name="strategy" value="update" checked>
                <strong>Update Existing Rates:</strong> Overwrite rates for matching country, partner, service, shipment type &amp; weight.
              </label>
            </div>
            <div class="radio" style="margin-bottom: 0;">
              <label>
                <input type="radio" name="strategy" value="skip">
                <strong>Skip Existing Rates:</strong> Keep existing rates unchanged and only insert newly added combinations.
              </label>
            </div>
          </div>

          <div class="text-center" style="margin-top: 15px;">
            <a href="<?php echo site_url('rates/template'); ?>" class="btn btn-default btn-sm" style="border-radius: 3px;">
              <i class="fa fa-file-excel-o text-green"></i> Download Formatted Sample Template (.xls)
            </a>
          </div>
        </div>

        <div class="modal-footer" style="background-color: #f7f9fa;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary bg-purple"><i class="fa fa-upload"></i> Start Upload &amp; Import</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
$(document).ready(function() {
  // Initialize select2 if available
  if ($.fn.select2) {
    $('.select2').select2({
      dropdownAutoWidth: true
    });
  }

  // Prevent DataTable re-initialization warning
  if ($.fn.DataTable.isDataTable('#ratesV2Table')) {
    $('#ratesV2Table').DataTable().destroy();
  }

  // DataTable init
  var table = $('#ratesV2Table').DataTable({
    "destroy": true,
    "pageLength": 25,
    "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
    "order": [[1, "asc"], [2, "asc"], [5, "asc"]],
    "columnDefs": [
      { "targets": [0, 7, 8], "orderable": false }
    ],
    "language": {
      "search": "_INPUT_",
      "searchPlaceholder": "Search rates matrix...",
      "emptyTable": "No rates available in table."
    }
  });

  // Edit Rate Modal Population via AJAX
  $('#ratesV2Table').on('click', '.btn-edit-rate', function() {
    var id = $(this).data('id');
    var btn = $(this);
    var originalHtml = btn.html();
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
      url: "<?php echo site_url('rates/get/'); ?>" + id,
      type: "GET",
      dataType: "json",
      success: function(res) {
        btn.prop('disabled', false).html(originalHtml);
        if (res.status === 'success' && res.data) {
          var d = res.data;
          $('#edit_rate_id').val(d.id);
          $('#edit_destination_country_id').val(d.destination_country_id).trigger('change');
          $('#edit_courier_partner_id').val(d.courier_partner_id);
          $('#edit_service_type').val(d.service_type);
          $('#edit_shipment_type').val(d.shipment_type);
          $('#edit_weight').val(parseFloat(d.weight).toFixed(3));
          $('#edit_rate').val(parseFloat(d.rate).toFixed(2));
          $('#edit_status').val(d.status);

          $('#editRateForm').attr('action', "<?php echo site_url('rates/edit/'); ?>" + d.id);
          $('#editRateModal').modal('show');
        } else {
          alert('Could not retrieve rate details: ' + (res.message || 'Error'));
        }
      },
      error: function() {
        btn.prop('disabled', false).html(originalHtml);
        alert('Server connection error. Please try again.');
      }
    });
  });

  // AJAX Quick Status Toggle
  $('#ratesV2Table').on('click', '.btn-toggle-status', function(e) {
    e.preventDefault();
    var link = $(this);
    var id = link.data('id');
    var currentBadge = link.find('.label');

    currentBadge.html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
      url: "<?php echo site_url('rates/toggle-status/'); ?>" + id,
      type: "GET",
      dataType: "json",
      success: function(res) {
        if (res.status === 'success') {
          if (res.new_status === 'Active') {
            link.html('<span class="label label-success" style="cursor: pointer;"><i class="fa fa-check"></i> Active</span>');
          } else {
            link.html('<span class="label label-danger" style="cursor: pointer;"><i class="fa fa-times"></i> Inactive</span>');
          }
        } else {
          window.location.href = link.attr('href');
        }
      },
      error: function() {
        // Fallback to normal navigation
        window.location.href = link.attr('href');
      }
    });
  });
});
</script>
