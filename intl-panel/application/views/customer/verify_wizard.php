<!-- Step-by-step Verification Wizard -->
<div class="row">
  <div class="col-md-10 col-md-offset-1">
    
    <!-- Flash OTP notification info helper (displays the mock code on screen) -->
    <?php if($this->session->flashdata('otp_info')): ?>
      <div class="alert alert-info alert-dismissible" style="border-radius: 8px; margin-bottom: 20px;">
        <h4><i class="icon fa fa-send"></i> OTP Code Dispatched (Mocking Channel)</h4>
        <?php echo $this->session->flashdata('otp_info'); ?>
      </div>
    <?php endif; ?>

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-shield"></i> Mandatory Customer Consignment Authorization Wizard</h3>
        <span class="label label-warning pull-right" style="font-size: 13px;">Status: Verification Pending</span>
      </div>

      <div class="box-body" style="padding: 30px;">
        
        <!-- Wizard Navigation Breadcrumb -->
        <div class="row text-center" style="margin-bottom: 35px;">
          <div class="col-xs-3 wizard-step active" id="step-tab-1">
            <span class="badge bg-blue" style="font-size: 16px; padding: 8px 12px; border-radius: 50%;">1</span>
            <p style="margin-top: 10px; font-weight: 600; font-size: 13px;">1. Review details</p>
          </div>
          <div class="col-xs-3 wizard-step" id="step-tab-2">
            <span class="badge bg-gray" style="font-size: 16px; padding: 8px 12px; border-radius: 50%;">2</span>
            <p style="margin-top: 10px; font-weight: 600; font-size: 13px; color: #999;">2. Declarations</p>
          </div>
          <div class="col-xs-3 wizard-step" id="step-tab-3">
            <span class="badge bg-gray" style="font-size: 16px; padding: 8px 12px; border-radius: 50%;">3</span>
            <p style="margin-top: 10px; font-weight: 600; font-size: 13px; color: #999;">3. Signature Pad</p>
          </div>
          <div class="col-xs-3 wizard-step" id="step-tab-4">
            <span class="badge bg-gray" style="font-size: 16px; padding: 8px 12px; border-radius: 50%;">4</span>
            <p style="margin-top: 10px; font-weight: 600; font-size: 13px; color: #999;">4. OTP Authenticator</p>
          </div>
        </div>

        <hr>

        <!-- WIZARD STEP 1: CONSIGNMENT DETAILS REVIEW -->
        <div class="wizard-panel" id="step-panel-1">
          <h4 style="font-weight: 700; margin-bottom: 20px;"><i class="fa fa-search text-blue"></i> Step 1: Review Shipment Booking Details</h4>
          
          <div class="row">
            <div class="col-md-6">
              <div class="well well-sm">
                <h5><strong>Exporter / Sender:</strong></h5>
                <p><?php echo $shipment->sender_name; ?><br>
                <?php echo $shipment->sender_company ? $shipment->sender_company.'<br>' : ''; ?>
                <?php echo $shipment->sender_address; ?>, <?php echo $shipment->sender_city; ?>, <?php echo $shipment->sender_state; ?>, <?php echo $shipment->origin_country_name; ?> - <?php echo $shipment->sender_zip; ?><br>
                Mobile: <?php echo $shipment->sender_mobile; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="well well-sm">
                <h5><strong>Consignee / Receiver:</strong></h5>
                <p><?php echo $shipment->receiver_name; ?><br>
                <?php echo $shipment->receiver_company ? $shipment->receiver_company.'<br>' : ''; ?>
                <?php echo $shipment->receiver_address; ?>, <?php echo $shipment->receiver_city; ?>, <?php echo $shipment->receiver_state; ?>, <?php echo $shipment->dest_country_name; ?> - <?php echo $shipment->receiver_zip; ?><br>
                Mobile: <?php echo $shipment->receiver_mobile; ?></p>
              </div>
            </div>
          </div>

          <h5 style="font-weight: 700; margin-top: 20px;"><i class="fa fa-balance-scale"></i> Package & Box Specifications</h5>
          <table class="table table-bordered table-striped">
            <thead>
              <tr class="bg-gray">
                <th>Box #</th>
                <th>Length (cm)</th>
                <th>Width (cm)</th>
                <th>Height (cm)</th>
                <th>Volumetric Weight (kg)</th>
                <th>Actual Weight (kg)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($boxes as $box): ?>
                <tr>
                  <td><?php echo $box->box_number; ?></td>
                  <td><?php echo $box->length; ?></td>
                  <td><?php echo $box->width; ?></td>
                  <td><?php echo $box->height; ?></td>
                  <td><?php echo $box->volumetric_weight; ?> kg</td>
                  <td><?php echo $box->actual_weight; ?> kg</td>
                </tr>
              <?php endforeach; ?>
              <tr class="bg-gray">
                <td colspan="4" class="text-right"><strong>Consignment Chargeable Weight:</strong></td>
                <td colspan="2"><strong><?php echo $shipment->chargeable_weight; ?> kg</strong></td>
              </tr>
            </tbody>
          </table>

          <h5 style="font-weight: 700; margin-top: 20px;"><i class="fa fa-list-ul"></i> Declared Items & Commodities</h5>
          <table class="table table-bordered table-striped">
            <thead>
              <tr class="bg-gray">
                <th>Item Description</th>
                <th>HS Code</th>
                <th>Quantity</th>
                <th>Unit Value</th>
                <th class="text-right">Total Value</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items as $item): ?>
                <tr>
                  <td><?php echo $item->item_description; ?></td>
                  <td><code><?php echo $item->hs_code ? $item->hs_code : '-'; ?></code></td>
                  <td><?php echo $item->quantity; ?></td>
                  <td>₹<?php echo number_format($item->unit_value, 2); ?></td>
                  <td class="text-right"><strong>₹<?php echo number_format($item->total_value, 2); ?></strong></td>
                </tr>
              <?php endforeach; ?>
              <tr class="bg-gray">
                <td colspan="4" class="text-right"><strong>Total Customs Declared Value:</strong></td>
                <td class="text-right"><strong>₹<?php echo number_format($shipment->total_declared_value, 2); ?></strong></td>
              </tr>
            </tbody>
          </table>

          <div style="margin-top: 30px;" class="text-right">
            <button type="button" class="btn btn-primary btn-lg" onclick="goToStep(2)">Approve details & Continue <i class="fa fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- WIZARD STEP 2: DECLARATION & TERMS -->
        <div class="wizard-panel" id="step-panel-2" style="display: none;">
          <h4 style="font-weight: 700; margin-bottom: 20px;"><i class="fa fa-file-text-o text-blue"></i> Step 2: Legal Declarations & Terms of Carriage</h4>
          
          <!-- Restricted Items warning panel -->
          <div class="alert alert-warning" style="border-radius: 8px;">
            <h5><i class="fa fa-warning"></i> <strong>Prohibited Item Notice for <?php echo $shipment->dest_country_name; ?></strong></h5>
            <p>Ensure that the consignment contains none of the following restricted commodities: <strong><?php echo $this->db->get_where('countries', array('id' => $shipment->destination_country_id))->row()->restricted_items; ?></strong>.</p>
          </div>

          <div class="form-group">
            <label>Read Standard Terms of Service v<?php echo $active_terms->version_number; ?></label>
            <div style="height: 200px; overflow-y: scroll; border: 1px solid #ddd; padding: 15px; background: #fafafa; border-radius: 8px; font-size: 13px;" id="termsScrollBox">
              <?php echo $active_terms->terms_content; ?>
            </div>
            <p class="help-block text-muted text-right"><i class="fa fa-mouse-pointer"></i> Scroll to the bottom to unlock terms consent.</p>
          </div>

          <div class="checkbox" style="margin-top: 25px;">
            <label>
              <input type="checkbox" id="declaration_check"> 
              <strong>I declare that the shipment details, weights, and items described in Step 1 are correct, that no restricted or prohibited cargo has been loaded, and that I accept the Customs declarations.</strong>
            </label>
          </div>

          <div class="checkbox">
            <label>
              <input type="checkbox" id="terms_check" disabled> 
              <strong>I have read and agree to all the Terms and Conditions of Carriage.</strong>
            </label>
          </div>

          <div style="margin-top: 30px; display: flex; justify-content: space-between;">
            <button type="button" class="btn btn-default" onclick="goToStep(1)"><i class="fa fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn-primary" id="btn-step-2" onclick="goToStep(3)" disabled>Agree & Continue <i class="fa fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- WIZARD STEP 3: DIGITAL SIGNATURE CANVAS -->
        <div class="wizard-panel" id="step-panel-3" style="display: none;">
          <h4 style="font-weight: 700; margin-bottom: 20px;"><i class="fa fa-pencil text-blue"></i> Step 3: Draw Digital Signature Consent</h4>
          <p class="text-muted">Use your mouse cursor or touch screen to draw your digital signature inside the border below:</p>

          <div style="text-align: center; margin: 25px 0;">
            <canvas id="signatureCanvas" width="500" height="200" style="border: 2px dashed #3c8dbc; border-radius: 8px; background: #fff; cursor: crosshair; touch-action: none;"></canvas>
            <div style="margin-top: 15px;">
              <button type="button" class="btn btn-default btn-sm" id="clearCanvasBtn"><i class="fa fa-eraser"></i> Clear Canvas</button>
              <button type="button" class="btn btn-success btn-sm" id="saveSignatureBtn"><i class="fa fa-check"></i> Lock Signature</button>
            </div>
          </div>

          <!-- Hidden input for base64 signature -->
          <input type="hidden" id="signature_image_base64">

          <div style="margin-top: 30px; display: flex; justify-content: space-between;">
            <button type="button" class="btn btn-default" onclick="goToStep(2)"><i class="fa fa-arrow-left"></i> Back</button>
            <button type="button" class="btn btn-primary" id="btn-step-3" onclick="goToStep(4)" disabled>Proceed to Authenticate <i class="fa fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- WIZARD STEP 4: OTP AUTHENTICATOR -->
        <div class="wizard-panel" id="step-panel-4" style="display: none;">
          <h4 style="font-weight: 700; margin-bottom: 20px;"><i class="fa fa-lock text-blue"></i> Step 4: Multi-Factor OTP Verification</h4>
          <p class="text-muted">A 6-digit verification code has been dispatched to your email address. Enter the code below to complete authorization:</p>

          <?php echo form_open('customer/submit_verification', array('style' => 'max-width: 400px; margin: 30px auto;')); ?>
            <input type="hidden" name="shipment_id" value="<?php echo $shipment->id; ?>">
            <input type="hidden" name="terms_id" value="<?php echo $active_terms->id; ?>">

            <div class="form-group text-center">
              <label style="font-size: 16px; margin-bottom: 15px;">6-Digit OTP Code</label>
              <input type="text" name="otp_code" class="form-control text-center" style="font-size: 28px; height: auto; letter-spacing: 10px; font-weight: bold;" maxlength="6" required placeholder="000000">
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
              <button type="button" class="btn btn-default" onclick="goToStep(3)"><i class="fa fa-arrow-left"></i> Back</button>
              <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-shield"></i> Authorize & Release Shipment</button>
            </div>
          <?php echo form_close(); ?>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Interactive Drawing JS Canvas Script -->
<script>
  function goToStep(step) {
    $('.wizard-panel').hide();
    $('#step-panel-' + step).show();
    
    // Update steps CSS
    $('.wizard-step').find('span').removeClass('bg-blue').addClass('bg-gray');
    for (var i = 1; i <= step; i++) {
      $('#step-tab-' + i).find('span').removeClass('bg-gray').addClass('bg-blue');
    }
  }

  $(document).ready(function() {
    // Scroll event on terms
    $('#termsScrollBox').on('scroll', function() {
      var box = $(this);
      // Check if reached bottom
      if (box.scrollTop() + box.innerHeight() >= box[0].scrollHeight - 10) {
        $('#terms_check').prop('disabled', false);
      }
    });

    // Handle checkboxes step 2
    function checkStep2() {
      var dec = $('#declaration_check').is(':checked');
      var terms = $('#terms_check').is(':checked');
      if (dec && terms) {
        $('#btn-step-2').prop('disabled', false);
      } else {
        $('#btn-step-2').prop('disabled', true);
      }
    }
    $('#declaration_check, #terms_check').change(checkStep2);

    // Canvas drawing logic
    var canvas = document.getElementById('signatureCanvas');
    var ctx = canvas.getContext('2d');
    var drawing = false;

    ctx.strokeStyle = '#222';
    ctx.lineWidth = 3;
    ctx.lineCap = 'round';

    function getMousePos(canvasDom, event) {
      var rect = canvasDom.getBoundingClientRect();
      // Handle touch vs mouse
      var clientX = event.clientX || event.touches[0].clientX;
      var clientY = event.clientY || event.touches[0].clientY;
      return {
        x: clientX - rect.left,
        y: clientY - rect.top
      };
    }

    // Mouse listeners
    canvas.addEventListener('mousedown', function(e) {
      drawing = true;
      var pos = getMousePos(canvas, e);
      ctx.beginPath();
      ctx.moveTo(pos.x, pos.y);
    });

    canvas.addEventListener('mousemove', function(e) {
      if (!drawing) return;
      var pos = getMousePos(canvas, e);
      ctx.lineTo(pos.x, pos.y);
      ctx.stroke();
    });

    canvas.addEventListener('mouseup', function() {
      drawing = false;
    });

    // Touch listeners for mobile support
    canvas.addEventListener('touchstart', function(e) {
      e.preventDefault();
      drawing = true;
      var pos = getMousePos(canvas, e);
      ctx.beginPath();
      ctx.moveTo(pos.x, pos.y);
    });

    canvas.addEventListener('touchmove', function(e) {
      e.preventDefault();
      if (!drawing) return;
      var pos = getMousePos(canvas, e);
      ctx.lineTo(pos.x, pos.y);
      ctx.stroke();
    });

    canvas.addEventListener('touchend', function() {
      drawing = false;
    });

    // Clear Canvas
    $('#clearCanvasBtn').click(function() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      $('#saveSignatureBtn').removeClass('btn-primary').addClass('btn-success').html('<i class="fa fa-check"></i> Lock Signature').prop('disabled', false);
      $('#btn-step-3').prop('disabled', true);
    });

    // Save Signature via AJAX
    $('#saveSignatureBtn').click(function() {
      var signature_data = canvas.toDataURL('image/png');
      var shipment_id = '<?php echo $shipment->id; ?>';
      
      $.ajax({
        url: '<?php echo site_url("customer/submit-signature"); ?>',
        type: 'POST',
        data: {
          shipment_id: shipment_id,
          signature_data: signature_data,
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Signature Locked!',
              text: 'Digital signature saved successfully.',
              timer: 1500,
              showConfirmButton: false
            });
            $('#saveSignatureBtn').removeClass('btn-success').addClass('btn-primary').html('<i class="fa fa-lock"></i> Locked & Saved').prop('disabled', true);
            $('#btn-step-3').prop('disabled', false);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Save Failed',
              text: response.message
            });
          }
        }
      });
    });
  });
</script>
