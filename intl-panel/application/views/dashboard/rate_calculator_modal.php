<!-- ============================================================== -->
<!-- INTERNATIONAL RATE CALCULATOR MODAL -->
<!-- ============================================================== -->
<style>
  #rateCalculatorModal {
    z-index: 1060 !important;
  }
  .modal-backdrop {
    z-index: 1040 !important;
  }
  /* ODA Suggestions Dropdown */
  .oda-suggestions-menu {
    position: absolute;
    top: 100%;
    left: 4px;
    right: 4px;
    z-index: 1075;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    max-height: 250px;
    overflow-y: auto;
    margin-top: 2px;
    display: none;
  }
  .oda-sugg-header {
    padding: 6px 10px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 10px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .oda-sugg-item {
    padding: 8px 10px;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    transition: background 0.15s ease-in-out;
  }
  .oda-sugg-item:last-child {
    border-bottom: none;
  }
  .oda-sugg-item:hover, .oda-sugg-item.active {
    background-color: #eff6ff !important;
  }
</style>
<div class="modal fade" id="rateCalculatorModal" tabindex="-1" role="dialog" aria-labelledby="rateCalcModalTitle">
  <div class="modal-dialog modal-lg" role="document" style="width: 95%; max-width: 1080px;">
    <div class="modal-content" style="border-radius: 10px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.25); border: none;">
      
      <!-- Modal Header -->
      <div class="modal-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff; padding: 18px 25px; border-bottom: none;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.85; font-size: 26px; text-shadow: none;">
          <span aria-hidden="true">&times;</span>
        </button>
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 22px; color: #ffeb3b;">
            <i class="fa fa-calculator"></i>
          </div>
          <div>
            <h4 class="modal-title" id="rateCalcModalTitle" style="font-weight: 700; font-size: 18px; margin: 0; color: #fff;">
              International Shipping Rate Calculator
            </h4>
            <p style="margin: 3px 0 0 0; font-size: 12px; color: rgba(255,255,255,0.85);">
              Live door-to-door carrier comparison, volumetric weight calculator &amp; ODA surcharge detection.
            </p>
          </div>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="modal-body" style="padding: 25px; background-color: #f8fafc;">
        <div class="row">
          
          <!-- LEFT COLUMN: SHIPMENT SPECIFICATIONS -->
          <div class="col-md-5 col-sm-12">
            <div class="panel panel-default" style="border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03); margin-bottom: 0;">
              <div class="panel-heading" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; font-weight: 700; font-size: 13px; color: #334155;">
                <i class="fa fa-sliders text-primary"></i> 1. Shipment Parameters
              </div>
              <div class="panel-body" style="padding: 20px;">
                <form id="calcRateForm" onsubmit="event.preventDefault(); runRateCalculation();">
                  
                  <!-- Destination Country -->
                  <div class="form-group">
                    <label style="font-weight: 600; font-size: 12px; color: #475569; text-transform: uppercase;">
                      Destination Country <span class="text-danger">*</span>
                    </label>
                    <select id="calc_dest_country" class="form-control select2" style="width: 100%;" required>
                      <option value="">-- Select Destination Country --</option>
                      <?php if (!empty($countries)): ?>
                        <?php foreach ($countries as $c): ?>
                          <option value="<?php echo $c->id; ?>" <?php echo ($c->country_name === 'United States' || $c->country_name === 'United Arab Emirates') ? '' : ''; ?>>
                            <?php echo htmlspecialchars($c->country_name); ?> (<?php echo $c->country_code; ?>)
                          </option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </select>
                  </div>

                  <!-- Shipment Type -->
                  <div class="form-group">
                    <label style="font-weight: 600; font-size: 12px; color: #475569; text-transform: uppercase;">
                      Shipment Nature <span class="text-danger">*</span>
                    </label>
                    <div style="display: flex; gap: 10px;">
                      <label class="btn btn-default btn-sm active" id="btn_type_doc" style="flex: 1; text-align: left; border-radius: 6px; font-weight: 600;">
                        <input type="radio" name="calc_shipment_type" value="Documents (Paper / Files)" checked style="margin-right: 5px;">
                        <i class="fa fa-file-text-o text-primary"></i> Documents
                      </label>
                      <label class="btn btn-default btn-sm" id="btn_type_parcel" style="flex: 1; text-align: left; border-radius: 6px; font-weight: 600;">
                        <input type="radio" name="calc_shipment_type" value="Non-Documents (Commercial Goods / Parcels)" style="margin-right: 5px;">
                        <i class="fa fa-cube text-success"></i> Non-Doc / Parcel
                      </label>
                    </div>
                  </div>

                  <!-- Weight Section -->
                  <div class="well well-sm" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; margin-bottom: 15px;">
                    <label style="font-weight: 600; font-size: 12px; color: #1e293b; margin-bottom: 6px; display: block;">
                      <i class="fa fa-balance-scale text-primary"></i> Dead Weight &amp; Dimensions
                    </label>
                    
                    <div class="form-group" style="margin-bottom: 10px;">
                      <label style="font-size: 11px; color: #64748b; font-weight: 600;">Actual / Scale Weight (KG) <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="number" step="0.05" min="0.05" id="calc_actual_weight" class="form-control input-sm" placeholder="e.g. 0.500" value="0.500" required oninput="updateChargeableWeightLive()">
                        <span class="input-group-addon" style="font-weight: 600; font-size: 11px; background: #f1f5f9;">KG</span>
                      </div>
                      
                      <!-- Quick presets -->
                      <div style="display: flex; gap: 5px; margin-top: 5px; flex-wrap: wrap;">
                        <button type="button" class="btn btn-xs btn-default" onclick="setWeightPreset(0.5)">0.5 kg</button>
                        <button type="button" class="btn btn-xs btn-default" onclick="setWeightPreset(1.0)">1.0 kg</button>
                        <button type="button" class="btn btn-xs btn-default" onclick="setWeightPreset(2.0)">2.0 kg</button>
                        <button type="button" class="btn btn-xs btn-default" onclick="setWeightPreset(5.0)">5.0 kg</button>
                        <button type="button" class="btn btn-xs btn-default" onclick="setWeightPreset(10.0)">10.0 kg</button>
                      </div>
                    </div>

                    <!-- Volumetric toggle -->
                    <div style="border-top: 1px dashed #e2e8f0; padding-top: 10px; margin-top: 10px;">
                      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 11px; font-weight: 600; color: #475569;">
                          <i class="fa fa-arrows-alt text-muted"></i> Package Box Dimensions (cm)
                        </span>
                        <small class="text-muted" style="font-size: 10px;">Formula: (L×W×H)/5000</small>
                      </div>
                      <div class="row" style="margin: 0 -4px;">
                        <div class="col-xs-4" style="padding: 0 4px;">
                          <input type="number" step="0.1" min="0" id="calc_length" class="form-control input-sm text-center" placeholder="L (cm)" oninput="updateChargeableWeightLive()">
                        </div>
                        <div class="col-xs-4" style="padding: 0 4px;">
                          <input type="number" step="0.1" min="0" id="calc_width" class="form-control input-sm text-center" placeholder="W (cm)" oninput="updateChargeableWeightLive()">
                        </div>
                        <div class="col-xs-4" style="padding: 0 4px;">
                          <input type="number" step="0.1" min="0" id="calc_height" class="form-control input-sm text-center" placeholder="H (cm)" oninput="updateChargeableWeightLive()">
                        </div>
                      </div>
                    </div>

                    <!-- Chargeable Weight Live Badge -->
                    <div id="live_chargeable_box" style="margin-top: 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 5px; padding: 6px 10px; display: flex; justify-content: space-between; align-items: center;">
                      <span style="font-size: 11px; font-weight: 600; color: #1e40af;">Chargeable Weight:</span>
                      <span id="live_chargeable_display" style="font-size: 13px; font-weight: 700; color: #1d4ed8;">0.500 KG</span>
                    </div>
                  </div>

                  <!-- Optional Filters: Partner & Service Type -->
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label style="font-weight: 600; font-size: 11px; color: #475569; text-transform: uppercase;">Courier Partner</label>
                      <select id="calc_partner" class="form-control input-sm">
                        <option value="0">All Carriers (Compare)</option>
                        <?php if (!empty($courier_partners)): ?>
                          <?php foreach ($courier_partners as $p): ?>
                            <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->partner_name); ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>

                    <div class="col-sm-6 form-group">
                      <label style="font-weight: 600; font-size: 11px; color: #475569; text-transform: uppercase;">Service Type</label>
                      <select id="calc_service" class="form-control input-sm">
                        <option value="All">All Services</option>
                        <?php if (!empty($service_types)): ?>
                          <?php foreach ($service_types as $st): ?>
                            <option value="<?php echo htmlspecialchars($st->service_name); ?>"><?php echo htmlspecialchars($st->service_name); ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>
                  </div>

                  <!-- Receiver Location for ODA Check -->
                  <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-weight: 600; font-size: 11px; color: #475569; text-transform: uppercase;">
                      Destination Postal Code / City <span class="text-muted" style="font-weight: normal; text-transform: none;">(For ODA detection)</span>
                    </label>
                    <div class="row" style="margin: 0 -4px;">
                      <!-- ZIP / Postal Code with Live Suggestions -->
                      <div class="col-xs-6" style="padding: 0 4px; position: relative;">
                        <div class="input-group input-group-sm" style="width: 100%;">
                          <span class="input-group-addon" style="background: #f1f5f9; color: #64748b; font-size: 11px; padding: 4px 8px;" title="Postal Code"><i class="fa fa-map-pin"></i></span>
                          <input type="text" id="calc_pincode" class="form-control input-sm" placeholder="ZIP / Postal Code" style="text-transform: uppercase;" autocomplete="off">
                          <span id="pincode_spinner" class="input-group-addon" style="background: #fff; display: none; padding: 4px 8px;"><i class="fa fa-spinner fa-spin text-primary"></i></span>
                        </div>
                        <div id="pincode_suggestions_box" class="oda-suggestions-menu"></div>
                      </div>

                      <!-- City / Area Name with Live Suggestions -->
                      <div class="col-xs-6" style="padding: 0 4px; position: relative;">
                        <div class="input-group input-group-sm" style="width: 100%;">
                          <span class="input-group-addon" style="background: #f1f5f9; color: #64748b; font-size: 11px; padding: 4px 8px;" title="City Name"><i class="fa fa-building-o"></i></span>
                          <input type="text" id="calc_city" class="form-control input-sm" placeholder="City / Area Name" autocomplete="off">
                          <span id="city_spinner" class="input-group-addon" style="background: #fff; display: none; padding: 4px 8px;"><i class="fa fa-spinner fa-spin text-primary"></i></span>
                        </div>
                        <div id="city_suggestions_box" class="oda-suggestions-menu"></div>
                      </div>
                    </div>

                    <!-- Live ODA match preview indicator -->
                    <div id="calc_oda_preview" style="display: none; margin-top: 6px; font-size: 11px; border-radius: 4px; padding: 6px 10px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                      <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa fa-exclamation-triangle text-warning"></i> <strong>ODA Surcharge Rule Matched:</strong> <span id="calc_oda_preview_text"></span></span>
                        <a href="javascript:void(0);" onclick="$('#calc_oda_preview').hide();" style="color: #92400e; text-decoration: none; font-size: 14px; line-height: 1;">&times;</a>
                      </div>
                    </div>
                  </div>

                  <!-- Submit button -->
                  <button type="submit" id="btnRunCalc" class="btn btn-primary btn-block" style="font-weight: 700; padding: 10px; border-radius: 6px; box-shadow: 0 4px 12px rgba(60, 141, 188, 0.3);">
                    <i class="fa fa-calculator"></i> Calculate Rates &amp; Compare Carriers
                  </button>
                  <button type="button" class="btn btn-default btn-block btn-xs" onclick="resetCalculatorForm()" style="margin-top: 6px;">
                    <i class="fa fa-refresh"></i> Reset Calculator
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- RIGHT COLUMN: RESULTS & CARRIER COMPARISON -->
          <div class="col-md-7 col-sm-12">
            <div class="panel panel-default" style="border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03); min-height: 480px; display: flex; flex-direction: column;">
              
              <div class="panel-heading" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; font-weight: 700; font-size: 13px; color: #334155; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fa fa-tags text-success"></i> 2. Available Shipping Rates &amp; Comparison</span>
                <span id="calc_badge_count" class="label label-primary" style="display: none; font-size: 11px;">0 Options Found</span>
              </div>

              <div class="panel-body" style="padding: 20px; flex: 1;">
                
                <!-- Initial State -->
                <div id="calc_state_initial" class="text-center" style="padding: 60px 20px;">
                  <div style="width: 70px; height: 70px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 15px;">
                    <i class="fa fa-plane"></i>
                  </div>
                  <h4 style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">Ready to Calculate</h4>
                  <p style="color: #64748b; font-size: 13px; max-width: 360px; margin: 0 auto; line-height: 1.5;">
                    Select your destination country and dead weight on the left panel, then click <strong>Calculate Rates</strong> to compare live prices, delivery estimates, and verify ODA surcharges.
                  </p>
                </div>

                <!-- Loading State -->
                <div id="calc_state_loading" class="text-center" style="display: none; padding: 70px 20px;">
                  <i class="fa fa-spinner fa-spin fa-3x text-primary" style="margin-bottom: 15px;"></i>
                  <h4 style="font-weight: 600; color: #334155;">Querying International Carrier Tariffs...</h4>
                  <p class="text-muted" style="font-size: 12px;">Evaluating weight slabs, fuel surcharges, and remote area databases...</p>
                </div>

                <!-- Results State -->
                <div id="calc_state_results" style="display: none;">
                  
                  <!-- Summary Pill Box -->
                  <div style="background: #f1f5f9; border-radius: 6px; padding: 10px 15px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                    <div>
                      <span style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600;">Destination:</span>
                      <strong id="res_summary_country" style="font-size: 13px; color: #0f172a; margin-left: 4px;">-</strong>
                    </div>
                    <div>
                      <span style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600;">Chargeable Weight:</span>
                      <span id="res_summary_weight" class="label label-info" style="font-size: 12px; margin-left: 4px;">-</span>
                    </div>
                    <div>
                      <span style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600;">Shipment:</span>
                      <span id="res_summary_type" class="label label-default" style="font-size: 11px; margin-left: 4px;">-</span>
                    </div>
                  </div>

                  <!-- ODA Alert Box (if detected) -->
                  <div id="res_oda_banner" class="alert alert-warning" style="display: none; border-radius: 6px; border-left: 4px solid #f59e0b; padding: 10px 14px; margin-bottom: 15px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                      <i class="fa fa-map-marker text-warning" style="font-size: 18px; margin-top: 2px;"></i>
                      <div>
                        <strong style="color: #92400e; font-size: 13px;">Out of Delivery Area (ODA) Surcharge Detected</strong>
                        <p id="res_oda_details" style="font-size: 12px; color: #78350f; margin: 2px 0 0 0;"></p>
                      </div>
                    </div>
                  </div>

                  <!-- Cards List Container -->
                  <div id="calc_quotes_container" style="display: flex; flex-direction: column; gap: 12px; max-height: 420px; overflow-y: auto; padding-right: 4px;">
                    <!-- Rendered dynamically via JavaScript -->
                  </div>

                </div>

                <!-- Empty / Error State -->
                <div id="calc_state_empty" class="text-center" style="display: none; padding: 50px 20px;">
                  <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 12px;">
                    <i class="fa fa-exclamation-circle"></i>
                  </div>
                  <h4 style="font-weight: 700; color: #1e293b;">No Tariffs Configured</h4>
                  <p id="calc_empty_msg" style="color: #64748b; font-size: 13px; max-width: 380px; margin: 0 auto 15px auto;"></p>
                  <a href="<?php echo site_url('rates-v2'); ?>" class="btn btn-sm btn-default" style="border-radius: 4px;">
                    <i class="fa fa-plus-circle text-primary"></i> Add Tariff in Rate Matrix
                  </a>
                </div>

              </div>

              <!-- Footer with Note -->
              <div class="panel-footer" style="background: #ffffff; border-top: 1px solid #e2e8f0; font-size: 11px; color: #64748b; padding: 10px 18px; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fa fa-info-circle text-blue"></i> Rates calculated directly from tariff table (shipping_rates_v2) plus ODA charge if applicable. Customs duties/taxes payable by consignee at destination.</span>
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<!-- ============================================================== -->
<!-- RATE CALCULATOR JAVASCRIPT ENGINE -->
<!-- ============================================================== -->
<script>
(function() {
  var csrfTokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var currentCsrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

  // Dedicated safe modal launcher function
  window.openRateCalculatorModal = function(e) {
    if (e && e.preventDefault) e.preventDefault();
    var $modal = $('#rateCalculatorModal');
    if ($modal.parent().not('body').length) {
      $modal.appendTo('body');
    }
    $modal.modal('show');
    return false;
  };

  // Live Chargeable Weight computation
  window.updateChargeableWeightLive = function() {
    var actual = parseFloat($('#calc_actual_weight').val()) || 0;
    var l = parseFloat($('#calc_length').val()) || 0;
    var w = parseFloat($('#calc_width').val()) || 0;
    var h = parseFloat($('#calc_height').val()) || 0;

    var vol = 0;
    if (l > 0 && w > 0 && h > 0) {
      vol = (l * w * h) / 5000;
    }

    var chargeable = Math.max(actual, vol);
    if (chargeable <= 0) chargeable = 0.500;

    var text = chargeable.toFixed(3) + ' KG';
    if (vol > actual && actual > 0) {
      text += ' (Volumetric)';
      $('#live_chargeable_box').css({'background': '#fef3c7', 'border-color': '#fde68a'});
      $('#live_chargeable_display').css('color', '#b45309').text(text);
    } else {
      $('#live_chargeable_box').css({'background': '#eff6ff', 'border-color': '#bfdbfe'});
      $('#live_chargeable_display').css('color', '#1d4ed8').text(text);
    }
  };

  window.setWeightPreset = function(val) {
    $('#calc_actual_weight').val(val);
    window.updateChargeableWeightLive();
  };

  window.resetCalculatorForm = function() {
    $('#calcRateForm')[0].reset();
    if ($.fn.select2) {
      $('#calc_dest_country').val('').trigger('change');
    }
    $('#calc_actual_weight').val('0.500');
    window.updateChargeableWeightLive();
    $('#calc_state_initial').show();
    $('#calc_state_loading').hide();
    $('#calc_state_results').hide();
    $('#calc_state_empty').hide();
    $('#calc_badge_count').hide();
    $('#calc_oda_preview').hide();
    $('.oda-suggestions-menu').hide();
  };

  // ==============================================================
  // ODA LIVE AUTOCOMPLETE SUGGESTION ENGINE
  // ==============================================================
  var odaAjaxTimer = null;

  function fetchOdaSuggestions(type, query, targetBoxId, spinnerId) {
    var countryId = $('#calc_dest_country').val();
    var $box = $('#' + targetBoxId);
    var $spinner = $('#' + spinnerId);

    if (!countryId) {
      $box.html(
        '<div style="padding: 10px 12px; font-size: 11px; color: #92400e; background: #fffbeb; text-align: center; border-radius: 4px;">' +
          '<i class="fa fa-exclamation-triangle text-warning"></i> <strong>Please select Destination Country first</strong> to view ODA surcharge locations.' +
        '</div>'
      ).show();
      return;
    }

    $spinner.show();
    if (odaAjaxTimer) clearTimeout(odaAjaxTimer);

    odaAjaxTimer = setTimeout(function() {
      $.ajax({
        url: '<?php echo site_url("dashboard/oda-suggestions"); ?>',
        type: 'GET',
        data: {
          country_id: countryId,
          q: query,
          type: type
        },
        dataType: 'json',
        success: function(res) {
          $spinner.hide();
          if (res && res.status === 'success' && res.items && res.items.length > 0) {
            renderOdaSuggestions(res.items, type, $box, query);
          } else {
            var msg = query 
              ? 'No ODA records found matching "<strong>' + escapeHtml(query) + '</strong>" for this country.<br><span class="text-success"><i class="fa fa-check-circle"></i> Likely standard delivery (no ODA surcharge).</span>'
              : 'No ODA records configured for this country.<br><span class="text-success"><i class="fa fa-check-circle"></i> Standard delivery rates apply.</span>';
            $box.html(
              '<div style="padding: 10px 12px; font-size: 11px; color: #64748b; text-align: center; line-height: 1.5;">' +
                msg +
              '</div>'
            ).show();
          }
        },
        error: function() {
          $spinner.hide();
          $box.hide();
        }
      });
    }, 200);
  }

  function renderOdaSuggestions(items, type, $box, query) {
    var html = '<div class="oda-sugg-header">' +
      '<span><i class="fa fa-map-pin text-warning"></i> ODA Surcharge Records</span>' +
      '<span class="badge" style="background: #e2e8f0; color: #475569; font-size: 10px;">' + items.length + ' rules</span>' +
    '</div>';

    $.each(items, function(idx, item) {
      var isZipType = (type === 'zip');
      var primary = isZipType ? (item.display_zip || item.postal_code) : (item.city_name || item.display_zip);
      var secondary = isZipType 
        ? [item.city_name, item.state_name].filter(Boolean).join(', ')
        : [item.state_name, (item.display_zip ? 'ZIP: ' + item.display_zip : '')].filter(Boolean).join(' • ');

      html += '<div class="oda-sugg-item" ' +
        'data-zip="' + escapeHtml(item.postal_code || item.display_zip || '') + '" ' +
        'data-city="' + escapeHtml(item.city_name || '') + '" ' +
        'data-state="' + escapeHtml(item.state_name || '') + '" ' +
        'data-partner="' + escapeHtml(item.partner_name || '') + '" ' +
        'data-service="' + escapeHtml(item.service_type || '') + '" ' +
        'data-rate="' + escapeHtml(item.rate_summary || '') + '">' +
        '<div style="display: flex; justify-content: space-between; align-items: center;">' +
          '<span style="font-weight: 700; color: #0f172a; font-size: 12px;"><i class="fa ' + (isZipType ? 'fa-map-pin' : 'fa-building-o') + ' text-primary" style="font-size: 10px; margin-right: 4px;"></i>' + escapeHtml(primary) + '</span>' +
          '<span class="label label-warning" style="font-size: 9px; padding: 2px 5px; font-weight: 700;"><i class="fa fa-exclamation-triangle"></i> ODA</span>' +
        '</div>' +
        '<div style="font-size: 11px; color: #64748b; margin-top: 2px; display: flex; justify-content: space-between; align-items: center; gap: 6px;">' +
          '<span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + (secondary ? escapeHtml(secondary) : '<span class="text-muted">General area</span>') + '</span>' +
          '<span class="label label-default" style="font-size: 9px; flex-shrink: 0; background: #e2e8f0; color: #334155;">' + escapeHtml(item.partner_name) + '</span>' +
        '</div>' +
        '<div style="font-size: 10px; color: #b45309; margin-top: 2px; font-weight: 600;">' +
          '<i class="fa fa-tag"></i> ' + escapeHtml(item.rate_summary) +
        '</div>' +
      '</div>';
    });

    $box.html(html).show();
  }

  function escapeHtml(str) {
    if (!str) return '';
    return $('<div>').text(str).html();
  }

  // Run AJAX rate calculation
  window.runRateCalculation = function() {
    var destId = $('#calc_dest_country').val();
    if (!destId) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Destination Required',
          text: 'Please select a destination country to calculate international rates.'
        });
      } else {
        alert('Please select a destination country.');
      }
      return;
    }

    var actualWeight = parseFloat($('#calc_actual_weight').val()) || 0.5;
    var l = parseFloat($('#calc_length').val()) || 0;
    var w = parseFloat($('#calc_width').val()) || 0;
    var h = parseFloat($('#calc_height').val()) || 0;
    var shipmentType = $('input[name="calc_shipment_type"]:checked').val() || 'Documents (Paper / Files)';
    var partnerId = $('#calc_partner').val() || 0;
    var serviceType = $('#calc_service').val() || 'All';
    var pincode = $('#calc_pincode').val() || '';
    var city = $('#calc_city').val() || '';

    $('#calc_state_initial').hide();
    $('#calc_state_empty').hide();
    $('#calc_state_results').hide();
    $('#calc_state_loading').show();
    $('#btnRunCalc').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Calculating...');

    var postData = {
      destination_country_id: destId,
      shipment_type: shipmentType,
      actual_weight: actualWeight,
      length: l,
      width: w,
      height: h,
      courier_partner_id: partnerId,
      service_type: serviceType,
      pincode: pincode,
      city: city
    };
    postData[csrfTokenName] = currentCsrfHash;

    $.ajax({
      url: '<?php echo site_url("dashboard/calculate-rate"); ?>',
      type: 'POST',
      data: postData,
      dataType: 'json',
      success: function(res) {
        if (res && res.csrf_hash) {
          currentCsrfHash = res.csrf_hash;
        }
        $('#btnRunCalc').prop('disabled', false).html('<i class="fa fa-calculator"></i> Calculate Rates &amp; Compare Carriers');
        $('#calc_state_loading').hide();

        if (res && res.status === 'success' && res.quotes && res.quotes.length > 0) {
          renderCalculatorResults(res, destId);
        } else {
          var msg = (res && res.message) ? res.message : 'No matching carrier rates found for ' + (res.destination_country || 'selected destination') + ' with chargeable weight of ' + (res.chargeable_weight || actualWeight) + ' kg.';
          $('#calc_empty_msg').text(msg);
          $('#calc_state_empty').show();
          $('#calc_badge_count').hide();
        }
      },
      error: function(xhr, status, error) {
        $('#btnRunCalc').prop('disabled', false).html('<i class="fa fa-calculator"></i> Calculate Rates &amp; Compare Carriers');
        $('#calc_state_loading').hide();
        $('#calc_empty_msg').text('Server communication error retrieving shipping rates. Please retry.');
        $('#calc_state_empty').show();
        $('#calc_badge_count').hide();
      }
    });
  };

  // Render cards
  function renderCalculatorResults(data, destId) {
    $('#res_summary_country').text(data.destination_country + ' (' + (data.country_code || '') + ')');
    var weightText = parseFloat(data.chargeable_weight).toFixed(3) + ' KG';
    if (data.is_volumetric_applied) {
      weightText += ' (Volumetric Applied)';
    }
    $('#res_summary_weight').text(weightText);
    var isDoc = $('input[name="calc_shipment_type"]:checked').val().indexOf('Non') === -1;
    $('#res_summary_type').text(isDoc ? 'Documents' : 'Non-Doc / Parcel');

    // ODA Banner & Deliverability
    var hasOda = false;
    var odaNote = '';
    var hasNonDeliverable = false;
    $.each(data.quotes, function(i, q) {
      if (q.is_non_deliverable) {
        hasNonDeliverable = true;
      }
      if (q.is_oda && q.oda_amount > 0) {
        hasOda = true;
        odaNote = q.oda_note || 'Location matched Out of Delivery Area surcharge rule.';
      }
    });

    if (hasNonDeliverable) {
      $('#res_oda_details').html('<span class="label label-danger" style="margin-right: 5px;"><i class="fa fa-ban"></i> Non-Deliverable Location</span> Carrier has flagged this destination as non-deliverable.' + (odaNote ? ' (' + odaNote + ')' : ''));
      $('#res_oda_banner').slideDown();
    } else if (hasOda) {
      $('#res_oda_details').text(odaNote);
      $('#res_oda_banner').slideDown();
    } else {
      $('#res_oda_banner').hide();
    }

    // Render cards
    var container = $('#calc_quotes_container').empty();
    $('#calc_badge_count').text(data.quotes.length + ' Carriers Available').show();

    $.each(data.quotes, function(idx, q) {
      var isCheapest = q.is_cheapest;
      var cardBorder = isCheapest ? 'border: 2px solid #10b981; background: #ffffff;' : 'border: 1px solid #e2e8f0; background: #ffffff;';
      var partnerColor = '#3b82f6';
      if (q.partner_name.indexOf('DHL') !== -1) partnerColor = '#d97706';
      else if (q.partner_name.indexOf('FedEx') !== -1) partnerColor = '#7c3aed';
      else if (q.partner_name.indexOf('Aramex') !== -1) partnerColor = '#dc2626';
      else if (q.partner_name.indexOf('UPS') !== -1) partnerColor = '#78350f';

      var serviceBadgeClass = (q.service_type.toLowerCase() === 'express') ? 'label-warning' : 'label-primary';

      // Book URL with parameters prefilled
      var bookUrl = '<?php echo site_url("shipments/book"); ?>' + 
                    '?dest=' + destId + 
                    '&partner=' + q.partner_id + 
                    '&service=' + encodeURIComponent(q.service_type) + 
                    '&weight=' + data.chargeable_weight;

      var cardHtml = 
        '<div class="box box-solid" style="margin-bottom: 0; border-radius: 8px; ' + cardBorder + ' box-shadow: 0 2px 6px rgba(0,0,0,0.04); transition: transform 0.2s;" onmouseover="this.style.transform=\'translateY(-2px)\'" onmouseout="this.style.transform=\'translateY(0)\'">' +
          '<div class="box-body" style="padding: 14px 18px;">' +
            '<div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">' +
              
              // Column 1: Partner & Service
              '<div class="col-sm-4 col-xs-12">' +
                '<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">' +
                  '<span class="label" style="background: ' + partnerColor + '; font-size: 12px; padding: 4px 8px; font-weight: 700; border-radius: 4px;">' +
                    '<i class="fa fa-truck"></i> ' + q.partner_name +
                  '</span>' +
                  (isCheapest ? '<span class="label label-success" style="font-size: 10px; padding: 3px 6px; border-radius: 3px;"><i class="fa fa-star"></i> BEST VALUE</span>' : '') +
                  (q.is_non_deliverable ? '<span class="label label-danger" style="font-size: 10px; padding: 3px 6px; border-radius: 3px;"><i class="fa fa-ban"></i> Non-Deliverable</span>' : '') +
                '</div>' +
                '<div style="font-size: 12px; color: #475569; font-weight: 600;">' +
                  '<span class="label ' + serviceBadgeClass + '" style="font-size: 10px; margin-right: 4px;">' + q.service_type + '</span>' +
                  '<span style="color: #64748b; font-size: 11px;"><i class="fa fa-clock-o"></i> ' + q.transit_estimate + '</span>' +
                '</div>' +
              '</div>' +

              // Column 2: Cost Breakup Breakdown (Shipping charge & ODA charge only)
              '<div class="col-sm-5 col-xs-12" style="font-size: 11px; color: #475569; line-height: 1.6;">' +
                '<div>Shipping Charge: <strong style="color: #0f172a;">₹' + parseFloat(q.base_rate).toFixed(2) + '</strong>' + 
                  (q.is_extrapolated ? ' <small class="text-muted" style="font-size: 9px;">(Slab ext.)</small>' : '') + 
                '</div>' +
                (q.is_non_deliverable ? '<div class="text-danger" style="margin-top: 2px;"><strong><i class="fa fa-ban"></i> Non-Deliverable Location</strong></div>' : '') +
                (q.is_oda ? '<div class="text-warning" style="margin-top: 2px;"><strong><i class="fa fa-exclamation-triangle"></i> ODA Charge: +₹' + parseFloat(q.oda_amount).toFixed(2) + '</strong>' + (q.oda_note ? '<br><small style="font-size: 10px; color: #b45309;">' + q.oda_note + '</small>' : '') + '</div>' : (!q.is_non_deliverable ? '<div style="color: #10b981; margin-top: 2px;"><small><i class="fa fa-check-circle"></i> Standard Delivery (No ODA)</small></div>' : '')) +
              '</div>' +

              // Column 3: Total Cost & Book Button
              '<div class="col-sm-3 col-xs-12 text-right">' +
                '<div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 600;">Estimated Cost</div>' +
                '<div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">₹' + parseFloat(q.total_charges).toFixed(2) + '</div>' +
                '<a href="' + bookUrl + '" class="btn btn-success btn-sm btn-block" style="font-weight: 600; border-radius: 4px; padding: 5px 10px;">' +
                  '<i class="fa fa-arrow-right"></i> Book Now' +
                '</a>' +
              '</div>' +

            '</div>' +
          '</div>' +
        '</div>';

      container.append(cardHtml);
    });

    $('#calc_state_results').slideDown();
  }

  // Bind Select2 to modal when modal opens
  $(document).ready(function() {
    $('#rateCalculatorModal').on('shown.bs.modal', function () {
      if ($.fn.select2) {
        $('#calc_dest_country').select2({
          dropdownParent: $('#rateCalculatorModal'),
          width: '100%'
        });
      }
      window.updateChargeableWeightLive();
    });

    // Toggle button active styling for Document / Parcel
    $('input[name="calc_shipment_type"]').change(function() {
      if ($(this).val().indexOf('Documents') !== -1 && $(this).val().indexOf('Non') === -1) {
        $('#btn_type_doc').addClass('active').addClass('btn-primary').removeClass('btn-default');
        $('#btn_type_parcel').removeClass('active').removeClass('btn-primary').addClass('btn-default');
      } else {
        $('#btn_type_parcel').addClass('active').addClass('btn-primary').removeClass('btn-default');
        $('#btn_type_doc').removeClass('active').removeClass('btn-primary').addClass('btn-default');
      }
    });

    // Delegated click listener for any rate calculator launcher buttons
    $(document).on('click', '#btnOpenRateCalcModal, #navRateCalcBtn, .btn-trigger-rate-calc', function(e) {
      e.preventDefault();
      window.openRateCalculatorModal(e);
    });

    // ==============================================================
    // LIVE SUGGESTION LISTENERS FOR ZIP & CITY INPUTS
    // ==============================================================

    // ZIP input live typing and focus
    $('#calc_pincode').on('input focus', function() {
      var q = $(this).val().trim();
      fetchOdaSuggestions('zip', q, 'pincode_suggestions_box', 'pincode_spinner');
    });

    // City input live typing and focus
    $('#calc_city').on('input focus', function() {
      var q = $(this).val().trim();
      fetchOdaSuggestions('city', q, 'city_suggestions_box', 'city_spinner');
    });

    // Handle suggestion item click (populates inputs and sets preview badge)
    $(document).on('click', '.oda-sugg-item', function(e) {
      e.preventDefault();
      var zip = $(this).attr('data-zip');
      var city = $(this).attr('data-city');
      var partner = $(this).attr('data-partner');
      var rate = $(this).attr('data-rate');

      if (zip) $('#calc_pincode').val(zip);
      if (city) $('#calc_city').val(city);

      var hintText = (zip ? 'ZIP ' + zip + ' ' : '') + (city ? '(' + city + ') ' : '') + '• Carrier: ' + partner + ' • ' + rate;
      $('#calc_oda_preview_text').text(hintText);
      $('#calc_oda_preview').slideDown();

      $('.oda-suggestions-menu').hide();
    });

    // Keyboard navigation (Arrow Down, Arrow Up, Enter, Escape)
    $('#calc_pincode, #calc_city').on('keydown', function(e) {
      var isZip = (this.id === 'calc_pincode');
      var $box = isZip ? $('#pincode_suggestions_box') : $('#city_suggestions_box');
      if (!$box.is(':visible')) return;

      var $items = $box.find('.oda-sugg-item');
      if (!$items.length) return;

      var $active = $items.filter('.active');
      var idx = $items.index($active);

      if (e.keyCode === 40) { // Arrow Down
        e.preventDefault();
        if (idx < $items.length - 1) {
          $items.removeClass('active');
          $items.eq(idx + 1).addClass('active')[0].scrollIntoView({ block: 'nearest' });
        } else {
          $items.removeClass('active');
          $items.eq(0).addClass('active')[0].scrollIntoView({ block: 'nearest' });
        }
      } else if (e.keyCode === 38) { // Arrow Up
        e.preventDefault();
        if (idx > 0) {
          $items.removeClass('active');
          $items.eq(idx - 1).addClass('active')[0].scrollIntoView({ block: 'nearest' });
        } else {
          $items.removeClass('active');
          $items.eq($items.length - 1).addClass('active')[0].scrollIntoView({ block: 'nearest' });
        }
      } else if (e.keyCode === 13) { // Enter key
        if ($active.length) {
          e.preventDefault();
          $active.trigger('click');
        }
      } else if (e.keyCode === 27) { // Escape key
        $box.hide();
      }
    });

    // Clear inputs and ODA preview when Destination Country changes
    $('#calc_dest_country').on('change', function() {
      $('#calc_pincode').val('');
      $('#calc_city').val('');
      $('#calc_oda_preview').hide();
      $('.oda-suggestions-menu').hide();
    });

    // Close suggestion menus when clicking outside
    $(document).on('click', function(e) {
      if (!$(e.target).closest('#calc_pincode, #calc_city, .oda-suggestions-menu').length) {
        $('.oda-suggestions-menu').hide();
      }
    });
  });
})();
</script>
