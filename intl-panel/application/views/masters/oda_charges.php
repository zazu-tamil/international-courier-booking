<div class="row">
  <div class="col-xs-12">
    
    <!-- KPI Summary Row -->
    <div class="row" style="margin-bottom: 5px;">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua" style="border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
          <span class="info-box-icon" style="border-radius: 6px 0 0 6px;"><i class="fa fa-map-pin"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total ODA Rules</span>
            <span class="info-box-number" style="font-size: 22px;"><?php echo number_format($total_count); ?></span>
            <div class="progress" style="margin: 5px 0;"><div class="progress-bar" style="width: 100%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Configured Surcharge Rules</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green" style="border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
          <span class="info-box-icon" style="border-radius: 6px 0 0 6px;"><i class="fa fa-check-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Active Rules</span>
            <span class="info-box-number" style="font-size: 22px;"><?php echo number_format($active_count); ?></span>
            <div class="progress" style="margin: 5px 0;"><div class="progress-bar" style="width: <?php echo ($total_count > 0) ? round(($active_count/$total_count)*100) : 0; ?>%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Active in Booking Surcharges</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow" style="border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
          <span class="info-box-icon" style="border-radius: 6px 0 0 6px;"><i class="fa fa-pause-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Inactive Rules</span>
            <span class="info-box-number" style="font-size: 22px;"><?php echo number_format($inactive_count); ?></span>
            <div class="progress" style="margin: 5px 0;"><div class="progress-bar" style="width: <?php echo ($total_count > 0) ? round(($inactive_count/$total_count)*100) : 0; ?>%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Temporarily Disabled</span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-purple" style="border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
          <span class="info-box-icon" style="border-radius: 6px 0 0 6px;"><i class="fa fa-truck"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Courier Partners</span>
            <span class="info-box-number" style="font-size: 22px;"><?php echo count($courier_partners); ?></span>
            <div class="progress" style="margin: 5px 0;"><div class="progress-bar" style="width: 100%"></div></div>
            <span class="progress-description" style="font-size: 11px;">Integrated Providers</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Card -->
    <div class="box box-primary" style="border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
      <div class="box-header with-border" style="padding: 12px 15px;">
        <h3 class="box-title" style="font-size: 17px; font-weight: 600;">
          <i class="fa fa-map-pin text-primary"></i> Out of Delivery Area (ODA) Surcharge Master
        </h3>
        <div class="box-tools pull-right" style="top: 8px;">
          <button type="button" class="btn btn-success btn-sm" onclick="openAddOdaModal()" style="border-radius: 3px; font-weight: 600;">
            <i class="fa fa-plus-circle"></i> Add ODA Rule
          </button>
          <button type="button" class="btn btn-primary btn-sm bg-purple" data-toggle="modal" data-target="#importOdaModal" style="border-radius: 3px; font-weight: 600;">
            <i class="fa fa-upload"></i> Import Excel / CSV
          </button>
          <?php
            $export_params = http_build_query(array_filter($filters));
            $export_xls_url = site_url('oda-charges/export') . '?' . ($export_params ? $export_params . '&' : '') . 'format=excel';
            $export_csv_url = site_url('oda-charges/export') . '?' . ($export_params ? $export_params . '&' : '') . 'format=csv';
          ?>
          <div class="btn-group">
            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 3px; font-weight: 600;">
              <i class="fa fa-download"></i> Export <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
              <li>
                <a href="<?php echo $export_xls_url; ?>" title="Export current filtered rules to Excel">
                  <i class="fa fa-file-excel-o text-green"></i> Export to Excel (.xls)
                </a>
              </li>
              <li>
                <a href="<?php echo $export_csv_url; ?>" title="Export current filtered rules to CSV">
                  <i class="fa fa-file-text-o text-blue"></i> Export to CSV (.csv)
                </a>
              </li>
            </ul>
          </div>
          <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 3px; font-weight: 600;">
              <i class="fa fa-file-text-o text-green"></i> Sample Template <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
              <li>
                <a href="<?php echo site_url('oda-charges/template?format=xls'); ?>" title="Download sample template in Excel format">
                  <i class="fa fa-file-excel-o text-green"></i> Excel Template (.xls)
                </a>
              </li>
              <li>
                <a href="<?php echo site_url('oda-charges/template?format=csv'); ?>" title="Download sample template in CSV format">
                  <i class="fa fa-file-text-o text-blue"></i> CSV Template (.csv)
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="box-body" style="background-color: #fcfcfc; border-bottom: 1px solid #f0f0f0; padding: 15px;">
        <?php echo form_open('oda-charges', array('id' => 'odaFilterForm', 'class' => 'row', 'method' => 'post')); ?>
          <div class="col-md-3 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Destination Country</label>
            <select name="dest_country" id="filter_dest_country" class="form-control input-sm select2" style="width: 100%;">
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
            <select name="partner" id="filter_partner" class="form-control input-sm select2" style="width: 100%;">
              <option value="">All Courier Partners</option>
              <option value="0" <?php echo (isset($filters['courier_partner_id']) && $filters['courier_partner_id'] === '0') ? 'selected' : ''; ?>>Universal (All Partners)</option>
              <?php foreach ($courier_partners as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo (!empty($filters['courier_partner_id']) && $filters['courier_partner_id'] == $p->id) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($p->partner_name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-2 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Service Type</label>
            <select name="service_type" id="filter_service_type" class="form-control input-sm select2" style="width: 100%;">
              <option value="">All Service Types</option>
              <option value="All" <?php echo (!empty($filters['service_type']) && $filters['service_type'] === 'All') ? 'selected' : ''; ?>>Universal (All Services)</option>
              <?php if (!empty($service_types)): ?>
                <?php foreach ($service_types as $st): ?>
                  <option value="<?php echo htmlspecialchars($st->service_name); ?>" <?php echo (!empty($filters['service_type']) && $filters['service_type'] == $st->service_name) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($st->service_name); ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <div class="col-md-2 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Tier / Zone</label>
            <select name="tier_zone" id="filter_tier_zone" class="form-control input-sm select2" style="width: 100%;">
              <option value="">All Tiers / Zones</option>
              <?php if (!empty($tier_zones)): ?>
                <?php foreach ($tier_zones as $tz): 
                  $tz_val = is_object($tz) ? (isset($tz->tier_zone) ? $tz->tier_zone : '') : (is_array($tz) ? (isset($tz['tier_zone']) ? $tz['tier_zone'] : '') : (string)$tz);
                  if ($tz_val === '') continue;
                ?>
                  <option value="<?php echo htmlspecialchars($tz_val); ?>" <?php echo (!empty($filters['tier_zone']) && $filters['tier_zone'] == $tz_val) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($tz_val); ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <div class="col-md-2 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Transit Mode</label>
            <select name="transit_mode" id="filter_transit_mode" class="form-control input-sm">
              <option value="">All Transit Modes</option>
              <option value="courier" <?php echo (!empty($filters['transit_mode']) && $filters['transit_mode'] === 'courier') ? 'selected' : ''; ?>>Courier</option>
              <option value="commercial" <?php echo (!empty($filters['transit_mode']) && $filters['transit_mode'] === 'commercial') ? 'selected' : ''; ?>>Commercial</option>
            </select>
          </div>

          <div class="col-md-2 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Non Deliverable</label>
            <select name="is_non_deliverable" id="filter_is_non_deliverable" class="form-control input-sm">
              <option value="">All Locations</option>
              <option value="1" <?php echo (isset($filters['is_non_deliverable']) && $filters['is_non_deliverable'] === '1') ? 'selected' : ''; ?>>Yes (Non-Deliverable)</option>
              <option value="0" <?php echo (isset($filters['is_non_deliverable']) && $filters['is_non_deliverable'] === '0') ? 'selected' : ''; ?>>No (Deliverable)</option>
            </select>
          </div>

          <div class="col-md-3 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Location Type</label>
            <select name="location_type" id="filter_location_type" class="form-control input-sm">
              <option value="">All Location Types</option>
              <option value="pincode" <?php echo (!empty($filters['location_type']) && $filters['location_type'] === 'pincode') ? 'selected' : ''; ?>>Single Postal Code</option>
              <option value="pincode_range" <?php echo (!empty($filters['location_type']) && $filters['location_type'] === 'pincode_range') ? 'selected' : ''; ?>>Postal Code Range</option>
              <option value="city" <?php echo (!empty($filters['location_type']) && $filters['location_type'] === 'city') ? 'selected' : ''; ?>>City / Town Name</option>
            </select>
          </div>

          <div class="col-md-3 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Calculation Type</label>
            <select name="calculation_type" id="filter_calculation_type" class="form-control input-sm">
              <option value="">All Calculation Models</option>
              <option value="higher_of" <?php echo (!empty($filters['calculation_type']) && $filters['calculation_type'] === 'higher_of') ? 'selected' : ''; ?>>Higher Of (Min or Per KG)</option>
              <option value="flat" <?php echo (!empty($filters['calculation_type']) && $filters['calculation_type'] === 'flat') ? 'selected' : ''; ?>>Flat Surcharge</option>
              <option value="per_kg" <?php echo (!empty($filters['calculation_type']) && $filters['calculation_type'] === 'per_kg') ? 'selected' : ''; ?>>Per KG Rate</option>
            </select>
          </div>

          <div class="col-md-2 col-sm-6 form-group">
            <label class="control-label" style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #555;">Status</label>
            <select name="status" id="filter_status" class="form-control input-sm">
              <option value="">All Status</option>
              <option value="Active" <?php echo (!empty($filters['status']) && $filters['status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
              <option value="Inactive" <?php echo (!empty($filters['status']) && $filters['status'] === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
          </div>

          <div class="col-md-4 col-sm-12 form-group" style="padding-top: 23px;">
            <button type="submit" class="btn btn-primary btn-sm" style="font-weight: 600;">
              <i class="fa fa-filter"></i> Apply Filter
            </button>
            <a href="<?php echo site_url('oda-charges'); ?>" class="btn btn-default btn-sm" title="Clear all search filters">
              <i class="fa fa-refresh"></i> Reset Filters
            </a>
            <?php if (!empty(array_filter($filters))): ?>
              <span class="label label-warning" style="margin-left: 8px; font-size: 11px; padding: 4px 8px; border-radius: 3px;">
                <i class="fa fa-filter"></i> Filters Active
              </span>
            <?php endif; ?>
          </div>
        <?php echo form_close(); ?>
      </div>

      <!-- Data Table -->
      <div class="box-body table-responsive" style="padding: 15px;">
        <table id="odaChargesTable" class="table table-bordered table-striped table-hover" style="width: 100%;">
          <thead>
            <tr class="bg-gray-light" style="font-weight: 600; font-size: 12px; color: #333;">
              <th style="width: 45px; text-align: center;">S.No</th>
              <th>Destination Country</th>
              <th>Courier Partner</th>
              <th>Service Type</th>
              <th>Tier / Zone</th>
              <th>Transit Mode</th>
              <th style="text-align: center;">Non Deliverable Location</th>
              <th>Type</th>
              <th>Location / Area / Code</th>
              <th>Calculation Method &amp; Charges</th>
              <th style="width: 80px; text-align: center;">Status</th>
              <th style="width: 80px; text-align: center;">Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Loaded via Server-Side DataTables -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================== -->
<!-- ADD / EDIT ODA CHARGE MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="odaChargeModal" tabindex="-1" role="dialog" aria-labelledby="odaModalTitle">
  <div class="modal-dialog modal-lg" role="document">
    <form id="odaChargeForm" method="post" action="<?php echo site_url('oda-charges/save'); ?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <input type="hidden" name="id" id="oda_id" value="">

      <div class="modal-content" style="border-radius: 8px; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.2);">
        <div class="modal-header bg-primary" style="padding: 15px 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="odaModalTitle" style="font-weight: 600; font-size: 16px;">
            <i class="fa fa-plus-circle"></i> Add New ODA Surcharge Rule
          </h4>
        </div>

        <div class="modal-body" style="padding: 20px 25px; background: #fafbfc;">
          <div id="modalAlertBox" style="display:none;"></div>

          <!-- Section 1: Location Definition & Geo Location Info Integration -->
          <div class="panel panel-default" style="border-radius: 6px; border: 1px solid #e0e6ed; box-shadow: none;">
            <div class="panel-heading" style="background: #f4f6f9; font-weight: 600; font-size: 13px; color: #475569;">
              <i class="fa fa-map-marker text-success"></i> 1. Location Identification &amp; Geo Location Integration
            </div>
            <div class="panel-body">

              <!-- Geo Location Info AJAX Lookup Box -->
              <input type="hidden" name="geo_location_id" id="modal_geo_location_id" value="">
              <div class="well well-sm" style="background: #eef7fc; border: 1px dashed #3c8dbc; border-radius: 6px; margin-bottom: 15px;">
                <label style="color: #20638f; font-size: 12px; font-weight: 600; margin-bottom: 4px; display: block;">
                  <i class="fa fa-map-pin text-primary"></i> Search &amp; Auto-fill from Geo Location Database (table: geo_location_info)
                </label>
                <div class="row">
                  <div class="col-sm-9 form-group" style="margin-bottom: 5px;">
                    <select id="modal_geo_search" class="form-control" style="width: 100%;">
                    </select>
                    <small class="text-muted">Selecting an entry will auto-fill Postal Code, City, State, and link the Geo ID.</small>
                  </div>
                  <div class="col-sm-3 form-group" style="margin-bottom: 5px;">
                    <button type="button" class="btn btn-default btn-sm btn-block" onclick="clearGeoSelection()" style="margin-top: 1px;">
                      <i class="fa fa-times text-danger"></i> Unlink Geo
                    </button>
                    <div id="geo_linked_badge" style="display:none; margin-top: 4px;">
                      <span class="label label-info" style="font-size: 11px; display: block; text-align: center;"><i class="fa fa-link"></i> <span id="geo_linked_text">Linked</span></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group">
                  <label style="margin-bottom: 8px;">How should this Out of Delivery Area be identified? <span class="text-danger">*</span></label>
                  <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <label class="radio-inline" style="font-weight: 600;">
                      <input type="radio" name="location_type" id="loc_type_pincode" value="pincode" checked onchange="toggleLocationFields()">
                      <i class="fa fa-envelope-o text-blue"></i> Single Postal Code / Exact ZIP
                    </label>
                    <label class="radio-inline" style="font-weight: 600;">
                      <input type="radio" name="location_type" id="loc_type_range" value="pincode_range" onchange="toggleLocationFields()">
                      <i class="fa fa-arrows-h text-orange"></i> Postal Code Range (From - To)
                    </label>
                    <label class="radio-inline" style="font-weight: 600;">
                      <input type="radio" name="location_type" id="loc_type_city" value="city" onchange="toggleLocationFields()">
                      <i class="fa fa-building-o text-green"></i> City / Town / Island Name
                    </label>
                  </div>
                </div>
              </div>

              <!-- Single Postal Code Group -->
              <div class="row" id="group_pincode">
                <div class="col-md-6 form-group">
                  <label>Postal Code / ZIP Code <span class="text-danger">*</span></label>
                  <input type="text" name="postal_code" id="modal_postal_code" class="form-control" placeholder="e.g. 99501 or 0870" style="text-transform: uppercase;">
                  <small class="text-muted">Enter exact postal code (or comma-separated codes).</small>
                </div>
                <div class="col-md-6 form-group">
                  <label>State / Province (Optional)</label>
                  <input type="text" name="state_name" id="modal_state_name_1" class="form-control" placeholder="e.g. Alaska or Northern Territory">
                </div>
              </div>

              <!-- Postal Code Range Group -->
              <div class="row" id="group_range" style="display: none;">
                <div class="col-md-4 form-group">
                  <label>Postal Code From <span class="text-danger">*</span></label>
                  <input type="text" name="postal_code_from" id="modal_postal_code_from" class="form-control" placeholder="e.g. 99502 or IV1" style="text-transform: uppercase;">
                </div>
                <div class="col-md-4 form-group">
                  <label>Postal Code To <span class="text-danger">*</span></label>
                  <input type="text" name="postal_code_to" id="modal_postal_code_to" class="form-control" placeholder="e.g. 99950 or IV63" style="text-transform: uppercase;">
                </div>
                <div class="col-md-4 form-group">
                  <label>State / Province (Optional)</label>
                  <input type="text" name="state_name" id="modal_state_name_2" class="form-control" placeholder="e.g. Highlands & Islands">
                </div>
              </div>

              <!-- City / Town Group -->
              <div class="row" id="group_city" style="display: none;">
                <div class="col-md-6 form-group">
                  <label>City / Town / Island Name <span class="text-danger">*</span></label>
                  <input type="text" name="city_name" id="modal_city_name" class="form-control" placeholder="e.g. Hatta or Al Madam or Alice Springs">
                </div>
                <div class="col-md-6 form-group">
                  <label>State / Emirate / Region (Optional)</label>
                  <input type="text" name="state_name" id="modal_state_name_3" class="form-control" placeholder="e.g. Dubai or Sharjah">
                </div>
              </div>
            </div>
          </div>

          <!-- Section 2: Destination, Partner, Service Type & Classification Mapping -->
          <div class="panel panel-default" style="border-radius: 6px; border: 1px solid #e0e6ed; box-shadow: none;">
            <div class="panel-heading" style="background: #f4f6f9; font-weight: 600; font-size: 13px; color: #475569;">
              <i class="fa fa-globe text-primary"></i> 2. Destination Country, Courier Partner &amp; Classification
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4 form-group">
                  <label>Destination Country <span class="text-danger">*</span></label>
                  <select name="destination_country_id" id="modal_country_id" class="form-control select2" style="width: 100%;" required>
                    <option value="">Select Destination Country</option>
                    <?php foreach ($countries as $c): ?>
                      <option value="<?php echo $c->id; ?>"><?php echo htmlspecialchars($c->country_name); ?> (<?php echo $c->country_code; ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-md-4 form-group">
                  <label>Courier Partner <span class="text-danger">*</span></label>
                  <select name="courier_partner_id" id="modal_partner_id" class="form-control select2" style="width: 100%;" required>
                    <option value="0">All Courier Partners (Universal)</option>
                    <?php foreach ($courier_partners as $p): ?>
                      <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->partner_name); ?></option>
                    <?php endforeach; ?>
                  </select>
                  <small class="text-muted">Specific partner or universal for all couriers.</small>
                </div>

                <div class="col-md-4 form-group">
                  <label>Service Type <span class="text-danger">*</span></label>
                  <select name="service_type" id="modal_service_type" class="form-control select2" style="width: 100%;" required>
                    <option value="All">All Services (Universal)</option>
                    <?php if (!empty($service_types)): ?>
                      <?php foreach ($service_types as $st): ?>
                        <option value="<?php echo htmlspecialchars($st->service_name); ?>"><?php echo htmlspecialchars($st->service_name); ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                  <small class="text-muted">Universal (All) or specific (e.g. Express, Economy).</small>
                </div>
              </div>

              <div class="row" style="margin-top: 10px; border-top: 1px dashed #e2e8f0; padding-top: 12px;">
                <div class="col-md-6 form-group">
                  <label>Tier / Zone</label>
                  <input type="text" name="tier_zone" id="modal_tier_zone" class="form-control" placeholder="e.g. Tier 1, Zone A, Remote Zone" list="tier_zone_suggestions">
                  <datalist id="tier_zone_suggestions">
                    <?php if (!empty($tier_zones)): ?>
                      <?php foreach ($tier_zones as $tz): 
                        $tz_val = is_object($tz) ? (isset($tz->tier_zone) ? $tz->tier_zone : '') : (is_array($tz) ? (isset($tz['tier_zone']) ? $tz['tier_zone'] : '') : (string)$tz);
                        if ($tz_val === '') continue;
                      ?>
                        <option value="<?php echo htmlspecialchars($tz_val); ?>"></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </datalist>
                  <small class="text-muted">ODA classification tier or zone.</small>
                </div>

                <div class="col-md-6 form-group">
                  <label>Transit Mode <span class="text-danger">*</span></label>
                  <select name="transit_mode" id="modal_transit_mode" class="form-control">
                    <option value="courier">Courier</option>
                    <option value="commercial">Commercial</option>
                  </select>
                  <small class="text-muted">Transit Mode: Courier or Commercial.</small>
                </div>
              </div>

              <div class="row" style="margin-top: 10px; border-top: 1px dashed #e2e8f0; padding-top: 12px;">
                <div class="col-md-12 form-group" style="margin-bottom: 5px;">
                  <label style="font-weight: 600;">Non Deliverable Location <span class="text-danger">*</span></label>
                  <div style="display: flex; gap: 20px; align-items: center; margin-top: 4px; flex-wrap: wrap;">
                    <label class="radio-inline" style="font-weight: 600; cursor: pointer;">
                      <input type="radio" name="is_non_deliverable" id="non_deliv_no" value="0" checked>
                      <span class="label label-success"><i class="fa fa-truck"></i> No</span> Deliverable (Standard ODA Area)
                    </label>
                    <label class="radio-inline" style="font-weight: 600; cursor: pointer;">
                      <input type="radio" name="is_non_deliverable" id="non_deliv_yes" value="1">
                      <span class="label label-danger"><i class="fa fa-ban"></i> Yes</span> Non-Deliverable Location (Courier cannot deliver to this area)
                    </label>
                  </div>
                  <small class="text-muted">Flag if shipments cannot be accepted or delivered to this destination.</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Section 3: Calculation Model & Surcharges -->
          <div class="panel panel-default" style="border-radius: 6px; border: 1px solid #e0e6ed; box-shadow: none;">
            <div class="panel-heading" style="background: #f4f6f9; font-weight: 600; font-size: 13px; color: #475569;">
              <i class="fa fa-calculator text-warning"></i> 3. ODA Surcharge Calculation Formula
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-12 form-group">
                  <label style="margin-bottom: 8px;">Calculation Model <span class="text-danger">*</span></label>
                  <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <label class="radio-inline" style="font-weight: 600;">
                      <input type="radio" name="calculation_type" id="calc_type_higher_of" value="higher_of" checked onchange="toggleCalculationFields()">
                      <span class="label label-primary" style="font-size:11px;">Higher Of</span> Max(Min Base Charge, Weight × Per KG Rate)
                    </label>
                    <label class="radio-inline" style="font-weight: 600;">
                      <input type="radio" name="calculation_type" id="calc_type_flat" value="flat" onchange="toggleCalculationFields()">
                      <span class="label label-info" style="font-size:11px;">Flat Fee</span> Fixed Amount per Consignment
                    </label>
                    <label class="radio-inline" style="font-weight: 600;">
                      <input type="radio" name="calculation_type" id="calc_type_per_kg" value="per_kg" onchange="toggleCalculationFields()">
                      <span class="label label-warning" style="font-size:11px;">Per KG</span> Weight × Rate per KG
                    </label>
                  </div>
                </div>
              </div>

              <!-- Higher Of Fields -->
              <div class="row" id="fields_higher_of">
                <div class="col-md-6 form-group">
                  <label>Minimum Base Charge (₹ INR) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    <input type="number" step="0.01" min="0" name="min_charge" id="modal_min_charge" class="form-control" placeholder="e.g. 1850.00" value="0.00" oninput="updateFormulaPreview()">
                  </div>
                  <small class="text-muted">Guaranteed baseline minimum surcharge.</small>
                </div>
                <div class="col-md-6 form-group">
                  <label>Rate per KG (₹ INR / kg) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    <input type="number" step="0.01" min="0" name="per_kg_rate" id="modal_per_kg_rate" class="form-control" placeholder="e.g. 35.00" value="0.00" oninput="updateFormulaPreview()">
                  </div>
                  <small class="text-muted">Applied if (Weight × Rate) exceeds Minimum Base Charge.</small>
                </div>
              </div>

              <!-- Flat Charge Field -->
              <div class="row" id="fields_flat" style="display: none;">
                <div class="col-md-6 form-group">
                  <label>Flat Surcharge Fee (₹ INR) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    <input type="number" step="0.01" min="0" name="flat_charge" id="modal_flat_charge" class="form-control" placeholder="e.g. 1500.00" value="0.00" oninput="updateFormulaPreview()">
                  </div>
                  <small class="text-muted">Charged regardless of package weight.</small>
                </div>
              </div>

              <!-- Formula Explanatory Preview Box -->
              <div class="alert alert-info" id="formulaPreviewBox" style="margin-top: 10px; margin-bottom: 0; padding: 10px 15px; border-radius: 4px; font-size: 12px;">
                <i class="fa fa-info-circle"></i> <strong id="formulaPreviewText">Formula preview: Max(₹0.00, Weight × ₹0.00)</strong>
              </div>
            </div>
          </div>

          <!-- Section 4: Status -->
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Status <span class="text-danger">*</span></label>
              <div>
                <label class="radio-inline" style="font-weight: 600;">
                  <input type="radio" name="status" id="status_active" value="Active" checked>
                  <span class="label label-success">Active</span> Active for Live Bookings
                </label>
                <label class="radio-inline" style="font-weight: 600;">
                  <input type="radio" name="status" id="status_inactive" value="Inactive">
                  <span class="label label-danger">Inactive</span> Temporarily Disabled
                </label>
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer" style="padding: 15px 25px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" id="btnSaveOda" class="btn btn-primary" style="font-weight: 600;">
            <i class="fa fa-save"></i> Save ODA Rule
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ============================================================== -->
<!-- BULK IMPORT EXCEL MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="importOdaModal" tabindex="-1" role="dialog" aria-labelledby="importModalTitle">
  <div class="modal-dialog" role="document">
    <?php echo form_open_multipart('oda-charges/import', array('id' => 'importOdaForm')); ?>
      <div class="modal-content" style="border-radius: 8px; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.2);">
        <div class="modal-header bg-purple" style="padding: 15px 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="importModalTitle" style="font-weight: 600; font-size: 16px;">
            <i class="fa fa-upload"></i> Bulk Import ODA Surcharge Rules
          </h4>
        </div>

        <div class="modal-body" style="padding: 20px 25px;">
          <div class="alert alert-info" style="border-radius: 6px; font-size: 13px;">
            <p><strong><i class="fa fa-info-circle"></i> Instructions:</strong></p>
            <ul style="padding-left: 20px; margin-bottom: 0;">
              <li>Upload <strong>.xls</strong>, <strong>.xlsx</strong>, or <strong>.csv</strong> files.</li>
              <li>Required column: <strong>Destination Country</strong>.</li>
              <li>Classification columns: <strong>Tier / Zone</strong>, <strong>Transit Mode</strong> (courier / commercial), <strong>Non Deliverable Location</strong> (Yes/No).</li>
              <li>Supports location types: <code>pincode</code>, <code>pincode_range</code>, and <code>city</code>.</li>
              <li>Supports calculation formulas: <code>higher_of</code>, <code>flat</code>, and <code>per_kg</code>.</li>
            </ul>
          </div>

          <div class="form-group">
            <label>Select Spreadsheet File <span class="text-danger">*</span></label>
            <input type="file" name="oda_file" id="oda_file" class="form-control" accept=".xls,.xlsx,.csv" required>
            <p class="help-block">Max file size: 5MB.</p>
          </div>

          <div class="form-group">
            <label>Import Strategy for Existing Matching Rules:</label>
            <div class="radio">
              <label>
                <input type="radio" name="strategy" value="update" checked>
                <strong>Update Existing Rules:</strong> Overwrite surcharge amounts and status for matching location.
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="strategy" value="skip">
                <strong>Skip Existing Rules:</strong> Only insert novel locations not currently present.
              </label>
            </div>
          </div>

          <div style="border-top: 1px solid #eee; padding-top: 12px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="<?php echo site_url('oda-charges/template?format=xls'); ?>" class="btn btn-default btn-sm">
              <i class="fa fa-file-excel-o text-green"></i> Download Excel Template (.xls)
            </a>
            <a href="<?php echo site_url('oda-charges/template?format=csv'); ?>" class="btn btn-default btn-sm">
              <i class="fa fa-file-text-o text-blue"></i> Download CSV Template (.csv)
            </a>
          </div>
        </div>

        <div class="modal-footer" style="padding: 15px 25px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary bg-purple" style="font-weight: 600;">
            <i class="fa fa-upload"></i> Upload &amp; Import
          </button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- JAVASCRIPT LOGIC -->
<!-- ============================================================== -->
<script>
  var odaTable;

  $(document).ready(function() {
    // Initialize Select2 if present
    if ($.fn.select2) {
      $('.select2').select2({
        width: '100%'
      });

      // Initialize Geo Location Select2 with AJAX (targeting 01_geo_location_info)
      $('#modal_geo_search').select2({
        placeholder: 'Search postal code, city, district or click to browse...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#odaChargeModal'),
        ajax: {
          url: '<?php echo site_url("oda-charges/geo-search"); ?>',
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return {
              q: params.term || '',
              country_id: $('#modal_country_id').val()
            };
          },
          processResults: function(data) {
            return {
              results: data.items || []
            };
          },
          cache: true
        },
        minimumInputLength: 0,
        templateResult: function(item) {
          if (item.loading) return item.text;
          if (!item.id) return item.text;
          var html = '<div style="padding: 4px 2px;">' +
            '<div style="font-weight: 600; font-size: 13px; color: #1e293b;">' + 
            (item.city_name || '') + 
            (item.postal_code && item.postal_code !== '00000' ? ' <span class="label label-primary" style="font-size: 10px; margin-left: 6px; padding: 2px 6px;">' + item.postal_code + '</span>' : '') +
            '</div>' +
            '<div style="font-size: 11px; color: #64748b; margin-top: 2px;">' +
            (item.postal_name ? '<i class="fa fa-map-marker text-muted"></i> ' + item.postal_name + ' &bull; ' : '') +
            (item.district_name ? item.district_name + ', ' : '') +
            (item.state_name ? item.state_name + ', ' : '') +
            '<strong style="color: #0284c7;">' + (item.country_name || '') + '</strong>' +
            '</div>' +
            '</div>';
          return $(html);
        },
        templateSelection: function(item) {
          if (!item.id) return item.text || 'Select Geo Location';
          return item.text || ((item.city_name || '') + (item.postal_code ? ' (' + item.postal_code + ')' : '') + ', ' + (item.country_name || ''));
        }
      }).on('select2:select', function(e) {
        var item = e.params.data;
        if (item) {
          $('#modal_geo_location_id').val(item.id);
          $('#geo_linked_badge').show();
          $('#geo_linked_text').text('Geo ID #' + item.id);

          // Auto-fill postal code
          if (item.postal_code && item.postal_code !== '00000') {
            $('#modal_postal_code').val(item.postal_code);
            $('#loc_type_pincode').prop('checked', true);
            toggleLocationFields();
          } else if (item.city_name) {
            $('#modal_city_name').val(item.city_name);
            $('#loc_type_city').prop('checked', true);
            toggleLocationFields();
          }

          if (item.city_name) {
            $('#modal_city_name').val(item.city_name);
          }

          // Auto-fill states
          if (item.state_name) {
            $('#modal_state_name_1').val(item.state_name);
            $('#modal_state_name_2').val(item.state_name);
            $('#modal_state_name_3').val(item.state_name);
          }

          // If Country not selected or doesn't match, auto-select destination country
          if (item.country_name) {
            var matchedVal = null;
            $('#modal_country_id option').each(function() {
              var optText = $(this).text().toLowerCase();
              if (optText.indexOf(item.country_name.toLowerCase()) !== -1) {
                matchedVal = $(this).val();
                return false;
              }
            });
            if (matchedVal && matchedVal !== $('#modal_country_id').val()) {
              $('#modal_country_id').val(matchedVal).trigger('change.select2');
            }
          }
        }
      }).on('select2:unselect', function() {
        clearGeoSelection();
      });

      // When country changes in modal, reset geo search if adding new rule
      $('#modal_country_id').on('change', function() {
        if (!$('#oda_id').val() && !$('#modal_geo_location_id').val()) {
          $('#modal_geo_search').val(null).trigger('change');
        }
      });
    }

    // Initialize Server-Side DataTables (9 columns)
    odaTable = $('#odaChargesTable').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?php echo site_url('oda-charges/ajax'); ?>",
        "type": "POST",
        "data": function(d) {
          d.dest_country        = $('#filter_dest_country').val();
          d.partner             = $('#filter_partner').val();
          d.service_type        = $('#filter_service_type').val();
          d.tier_zone           = $('#filter_tier_zone').val();
          d.transit_mode        = $('#filter_transit_mode').val();
          d.is_non_deliverable  = $('#filter_is_non_deliverable').val();
          d.location_type       = $('#filter_location_type').val();
          d.calculation_type    = $('#filter_calculation_type').val();
          d.status              = $('#filter_status').val();
          d['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
        }
      },
      "columns": [
        { "data": 0, "orderable": false, "className": "text-center" }, // S.No
        { "data": 1, "orderable": true },                             // Destination Country
        { "data": 2, "orderable": true },                             // Courier Partner
        { "data": 3, "orderable": true },                             // Service Type
        { "data": 4, "orderable": true },                             // Tier / Zone
        { "data": 5, "orderable": true },                             // Transit Mode
        { "data": 6, "orderable": true, "className": "text-center" }, // Non Deliverable Location
        { "data": 7, "orderable": true },                             // Loc Type
        { "data": 8, "orderable": false },                            // Location details (includes geo ID)
        { "data": 9, "orderable": true },                             // Calculation & charges
        { "data": 10, "orderable": true, "className": "text-center" },// Status
        { "data": 11, "orderable": false, "className": "text-center" }// Actions
      ],
      "order": [[1, "asc"]],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
      "language": {
        "emptyTable": "No ODA Surcharge Rules configured. Click 'Add ODA Rule' or 'Import Excel' to begin.",
        "processing": "<i class=\"fa fa-spinner fa-spin fa-2x fa-fw\"></i> Loading ODA data..."
      }
    });

    // Handle AJAX form submission for modal
    $('#odaChargeForm').on('submit', function(e) {
      e.preventDefault();
      
      // Sync state name based on active location type before sending
      var locType = $('input[name="location_type"]:checked').val();
      var stateVal = '';
      if (locType === 'pincode') stateVal = $('#modal_state_name_1').val();
      else if (locType === 'pincode_range') stateVal = $('#modal_state_name_2').val();
      else if (locType === 'city') stateVal = $('#modal_state_name_3').val();

      var formData = $(this).serializeArray();
      // Ensure state_name parameter is clean
      formData = formData.filter(function(item) { return item.name !== 'state_name'; });
      formData.push({ name: 'state_name', value: stateVal });

      $('#btnSaveOda').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
      $('#modalAlertBox').hide().empty();

      $.ajax({
        url: '<?php echo site_url("oda-charges/save"); ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
          $('#btnSaveOda').prop('disabled', false).html('<i class="fa fa-save"></i> Save ODA Rule');
          if (response.status === 'success') {
            $('#odaChargeModal').modal('hide');
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
              });
            } else {
              alert(response.message);
            }
            odaTable.ajax.reload(null, false);
          } else {
            $('#modalAlertBox').html('<div class="alert alert-danger" style="border-radius:4px;"><i class="fa fa-ban"></i> ' + response.message + '</div>').slideDown();
          }
        },
        error: function(xhr, status, error) {
          $('#btnSaveOda').prop('disabled', false).html('<i class="fa fa-save"></i> Save ODA Rule');
          $('#modalAlertBox').html('<div class="alert alert-danger" style="border-radius:4px;"><i class="fa fa-ban"></i> Server error processing request. Please retry.</div>').slideDown();
        }
      });
    });
  });

  // Clear Geo Location selection and unbind
  function clearGeoSelection() {
    $('#modal_geo_location_id').val('');
    $('#modal_geo_search').empty().trigger('change');
    $('#geo_linked_badge').hide();
    $('#geo_linked_text').text('');
  }

  // Toggle Location Input Fields in Modal
  function toggleLocationFields() {
    var type = $('input[name="location_type"]:checked').val();
    $('#group_pincode').hide();
    $('#group_range').hide();
    $('#group_city').hide();

    if (type === 'pincode') {
      $('#group_pincode').show();
      $('#modal_postal_code').focus();
    } else if (type === 'pincode_range') {
      $('#group_range').show();
      $('#modal_postal_code_from').focus();
    } else if (type === 'city') {
      $('#group_city').show();
      $('#modal_city_name').focus();
    }
  }

  // Toggle Calculation Input Fields in Modal
  function toggleCalculationFields() {
    var calc = $('input[name="calculation_type"]:checked').val();
    $('#fields_higher_of').hide();
    $('#fields_flat').hide();

    if (calc === 'higher_of') {
      $('#fields_higher_of').show();
    } else if (calc === 'flat') {
      $('#fields_flat').show();
    } else if (calc === 'per_kg') {
      $('#fields_higher_of').show(); // uses per_kg_rate input
    }
    updateFormulaPreview();
  }

  // Update Interactive Formula Preview String
  function updateFormulaPreview() {
    var calc = $('input[name="calculation_type"]:checked').val();
    var min = parseFloat($('#modal_min_charge').val()) || 0;
    var perKg = parseFloat($('#modal_per_kg_rate').val()) || 0;
    var flat = parseFloat($('#modal_flat_charge').val()) || 0;

    var txt = '';
    if (calc === 'higher_of') {
      txt = 'Formula: Maximum of Minimum Base (₹' + min.toFixed(2) + ') OR (Consignment Weight × ₹' + perKg.toFixed(2) + '/kg).';
    } else if (calc === 'flat') {
      txt = 'Formula: Fixed flat surcharge of ₹' + flat.toFixed(2) + ' per consignment.';
    } else if (calc === 'per_kg') {
      txt = 'Formula: Consignment Weight × ₹' + perKg.toFixed(2) + ' per kg.';
    }
    $('#formulaPreviewText').text(txt);
  }

  // Open Modal to Add New Rule
  function openAddOdaModal() {
    $('#oda_id').val('');
    $('#odaChargeForm')[0].reset();
    $('#odaModalTitle').html('<i class="fa fa-plus-circle"></i> Add New ODA Surcharge Rule');
    $('#modalAlertBox').hide().empty();

    if ($.fn.select2) {
      $('#modal_country_id').val('').trigger('change');
      $('#modal_partner_id').val('0').trigger('change');
      $('#modal_service_type').val('All').trigger('change');
    }

    clearGeoSelection();

    $('#modal_tier_zone').val('');
    $('#modal_transit_mode').val('courier');
    $('input[name="is_non_deliverable"][value="0"]').prop('checked', true);

    $('#loc_type_pincode').prop('checked', true);
    $('#calc_type_higher_of').prop('checked', true);
    $('#status_active').prop('checked', true);

    toggleLocationFields();
    toggleCalculationFields();
    $('#odaChargeModal').modal('show');
  }

  // Open Modal to Edit Existing Rule
  function openEditOdaModal(id) {
    $('#modalAlertBox').hide().empty();
    $.ajax({
      url: '<?php echo site_url("oda-charges/get"); ?>/' + id,
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success' && response.data) {
          var r = response.data;
          $('#oda_id').val(r.id);
          $('#odaModalTitle').html('<i class="fa fa-pencil"></i> Edit ODA Surcharge Rule #' + r.id);

          if ($.fn.select2) {
            $('#modal_country_id').val(r.destination_country_id).trigger('change');
            $('#modal_partner_id').val(r.courier_partner_id).trigger('change');
            $('#modal_service_type').val(r.service_type || 'All').trigger('change');
          } else {
            $('#modal_country_id').val(r.destination_country_id);
            $('#modal_partner_id').val(r.courier_partner_id);
            $('#modal_service_type').val(r.service_type || 'All');
          }

          // Geo Location Info pre-fill if linked
          if (r.geo_location_id) {
            $('#modal_geo_location_id').val(r.geo_location_id);
            $('#geo_linked_badge').show();
            $('#geo_linked_text').text('Geo ID #' + r.geo_location_id);

            var geoLabel = (r.geo_city_name ? r.geo_city_name + ' ' : '') + 
                           (r.geo_postal_code ? '- ' + r.geo_postal_code + ' ' : '') + 
                           (r.geo_postal_name ? '(' + r.geo_postal_name + ') ' : '') + 
                           '[Geo #' + r.geo_location_id + ']';
            var opt = new Option(geoLabel, r.geo_location_id, true, true);
            $('#modal_geo_search').empty().append(opt).trigger('change');
          } else {
            clearGeoSelection();
          }

          // Classification fields
          $('#modal_tier_zone').val(r.tier_zone || '');
          $('#modal_transit_mode').val(r.transit_mode ? r.transit_mode.toLowerCase() : 'courier');
          $('input[name="is_non_deliverable"][value="' + (r.is_non_deliverable == 1 ? '1' : '0') + '"]').prop('checked', true);

          // Location Type
          $('input[name="location_type"][value="' + r.location_type + '"]').prop('checked', true);
          $('#modal_postal_code').val(r.postal_code || '');
          $('#modal_postal_code_from').val(r.postal_code_from || '');
          $('#modal_postal_code_to').val(r.postal_code_to || '');
          $('#modal_city_name').val(r.city_name || '');
          $('#modal_state_name_1').val(r.state_name || '');
          $('#modal_state_name_2').val(r.state_name || '');
          $('#modal_state_name_3').val(r.state_name || '');

          // Calculation Type
          $('input[name="calculation_type"][value="' + r.calculation_type + '"]').prop('checked', true);
          $('#modal_min_charge').val(r.min_charge || '0.00');
          $('#modal_per_kg_rate').val(r.per_kg_rate || '0.00');
          $('#modal_flat_charge').val(r.flat_charge || '0.00');

          // Status
          $('input[name="status"][value="' + r.status + '"]').prop('checked', true);

          toggleLocationFields();
          toggleCalculationFields();
          $('#odaChargeModal').modal('show');
        } else {
          alert('Failed to load ODA rule details.');
        }
      },
      error: function() {
        alert('Server communication error retrieving rule data.');
      }
    });
  }

  // Toggle Status via AJAX Badge Click
  function toggleOdaStatus(id) {
    $.ajax({
      url: '<?php echo site_url("oda-charges/toggle-status"); ?>/' + id,
      type: 'POST',
      data: {
        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
      },
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          odaTable.ajax.reload(null, false);
        } else {
          alert(response.message || 'Failed to toggle status.');
        }
      },
      error: function() {
        alert('Error connecting to server.');
      }
    });
  }

  // Soft Delete Confirmation with SweetAlert
  function confirmDeleteOda(id) {
    var doDelete = function() {
      $.ajax({
        url: '<?php echo site_url("oda-charges/delete"); ?>/' + id,
        type: 'POST',
        data: {
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'success',
                title: 'Deleted',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
              });
            } else {
              alert(response.message);
            }
            odaTable.ajax.reload(null, false);
          } else {
            alert(response.message || 'Failed to delete ODA rule.');
          }
        },
        error: function() {
          alert('Server error deleting record.');
        }
      });
    };

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Delete ODA Rule?',
        text: 'This record will be soft-deleted and removed from active rate calculations.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          doDelete();
        }
      });
    } else {
      if (confirm('Are you sure you want to soft delete this ODA rule?')) {
        doDelete();
      }
    }
  }
</script>
