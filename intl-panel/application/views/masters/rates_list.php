<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-money"></i> Country-to-Country Shipping Rates Matrix</h3>
        <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addRateModal">
          <i class="fa fa-plus"></i> Add New Slab
        </button>
      </div>
      
      <div class="box-body table-responsive">
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0;">
              <label class="control-label">Filter by Destination Country:</label>
              <select id="destCountryFilter" class="form-control input-sm">
                <option value="">All Countries</option>
                <?php foreach($countries as $c): ?>
                  <option value="<?php echo htmlspecialchars($c->country_name); ?>"><?php echo $c->country_name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        
        <table class="table table-bordered table-striped dataTable">
          <thead>
            <tr>
              <th>Route</th>
              <th>Service</th>
              <th>Weight Slab Range</th>
              <th>Base Rate</th>
              <th>Fuel Surcharge %</th>
              <th>Handling Fee</th>
              <th>Insurance Fee</th>
              <th>Estimated Total (at Min)</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($rates as $r): ?>
              <tr>
                <td data-search="origin:<?php echo htmlspecialchars($r->origin_country); ?> dest:<?php echo htmlspecialchars($r->destination_country); ?>"><strong><?php echo $r->origin_country; ?></strong> <i class="fa fa-long-arrow-right"></i> <strong><?php echo $r->destination_country; ?></strong></td>
                <td><span class="label label-info"><?php echo $r->service_type; ?></span></td>
                <td><code><?php echo number_format($r->weight_slab_start, 3); ?> kg</code> to <code><?php echo number_format($r->weight_slab_end, 3); ?> kg</code></td>
                <td>₹<?php echo number_format($r->base_rate, 2); ?></td>
                <td><?php echo $r->fuel_surcharge; ?>%</td>
                <td>₹<?php echo number_format($r->handling_charges, 2); ?></td>
                <td>₹<?php echo number_format($r->insurance_charges, 2); ?></td>
                <td>
                  <?php 
                    $fuel = ($r->base_rate * $r->fuel_surcharge) / 100;
                    $est = $r->base_rate + $fuel + $r->handling_charges + $r->insurance_charges;
                  ?>
                  <strong>₹<?php echo number_format($est, 2); ?></strong>
                </td>
                <td>
                  <button class="btn btn-primary btn-xs edit-rate-btn" 
                          data-id="<?php echo $r->id; ?>"
                          data-origin="<?php echo $r->origin_country_id; ?>"
                          data-dest="<?php echo $r->destination_country_id; ?>"
                          data-service="<?php echo $r->service_type; ?>"
                          data-start="<?php echo $r->weight_slab_start; ?>"
                          data-end="<?php echo $r->weight_slab_end; ?>"
                          data-base="<?php echo $r->base_rate; ?>"
                          data-fuel="<?php echo $r->fuel_surcharge; ?>"
                          data-handling="<?php echo $r->handling_charges; ?>"
                          data-insurance="<?php echo $r->insurance_charges; ?>"
                          data-toggle="modal" data-target="#editRateModal">
                    <i class="fa fa-pencil"></i> Edit
                  </button>
                  <a href="<?php echo site_url('rates/delete/' . $r->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Remove this rate slab?');">
                    <i class="fa fa-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Rate Modal -->
<div class="modal fade" id="addRateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('rates/add'); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Create Shipping Rate Slab</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Origin Country <span class="text-danger">*</span></label>
              <select name="origin_country_id" class="form-control" required>
                <option value="">Select Origin</option>
                <?php foreach($countries as $c): ?>
                  <option value="<?php echo $c->id; ?>"><?php echo $c->country_name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Destination Country <span class="text-danger">*</span></label>
              <select name="destination_country_id" class="form-control" required>
                <option value="">Select Destination</option>
                <?php foreach($countries as $c): ?>
                  <option value="<?php echo $c->id; ?>"><?php echo $c->country_name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Service Type <span class="text-danger">*</span></label>
              <select name="service_type" class="form-control" required>
                <option value="">Select Service</option>
                <?php foreach($service_types as $st): ?>
                  <option value="<?php echo htmlspecialchars($st->service_name); ?>"><?php echo htmlspecialchars($st->service_name); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3 form-group">
              <label>Slab Start (kg) <span class="text-danger">*</span></label>
              <input type="number" step="0.001" name="weight_slab_start" class="form-control" placeholder="0.000" required>
            </div>
            <div class="col-md-3 form-group">
              <label>Slab End (kg) <span class="text-danger">*</span></label>
              <input type="number" step="0.001" name="weight_slab_end" class="form-control" placeholder="0.500" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Base Rate (₹) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="base_rate" class="form-control" placeholder="1000.00" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Fuel Surcharge (%)</label>
              <input type="number" step="0.01" name="fuel_surcharge" class="form-control" placeholder="10.00" value="0.00">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Handling Charges (₹)</label>
              <input type="number" step="0.01" name="handling_charges" class="form-control" placeholder="0.00" value="0.00">
            </div>
            <div class="col-md-6 form-group">
              <label>Insurance Charges (₹)</label>
              <input type="number" step="0.01" name="insurance_charges" class="form-control" placeholder="0.00" value="0.00">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Slab</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Edit Rate Modal -->
<div class="modal fade" id="editRateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editRateForm" action="" method="POST">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Edit Rate Slab Settings</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Origin Country</label>
              <select name="origin_country_id" id="edit_origin" class="form-control" disabled>
                <?php foreach($countries as $c): ?>
                  <option value="<?php echo $c->id; ?>"><?php echo $c->country_name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Destination Country</label>
              <select name="destination_country_id" id="edit_dest" class="form-control" disabled>
                <?php foreach($countries as $c): ?>
                  <option value="<?php echo $c->id; ?>"><?php echo $c->country_name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Service Type</label>
              <select name="service_type" id="edit_service" class="form-control" disabled>
                <option value="">Select Service</option>
                <?php foreach($service_types as $st): ?>
                  <option value="<?php echo htmlspecialchars($st->service_name); ?>"><?php echo htmlspecialchars($st->service_name); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3 form-group">
              <label>Slab Start (kg) <span class="text-danger">*</span></label>
              <input type="number" step="0.001" name="weight_slab_start" id="edit_start" class="form-control" required>
            </div>
            <div class="col-md-3 form-group">
              <label>Slab End (kg) <span class="text-danger">*</span></label>
              <input type="number" step="0.001" name="weight_slab_end" id="edit_end" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Base Rate (₹) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="base_rate" id="edit_base" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Fuel Surcharge (%)</label>
              <input type="number" step="0.01" name="fuel_surcharge" id="edit_fuel" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Handling Charges (₹)</label>
              <input type="number" step="0.01" name="handling_charges" id="edit_handling" class="form-control">
            </div>
            <div class="col-md-6 form-group">
              <label>Insurance Charges (₹)</label>
              <input type="number" step="0.01" name="insurance_charges" id="edit_insurance" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.edit-rate-btn').click(function() {
      var id = $(this).data('id');
      var origin = $(this).data('origin');
      var dest = $(this).data('dest');
      var service = $(this).data('service');
      var start = $(this).data('start');
      var end = $(this).data('end');
      var base = $(this).data('base');
      var fuel = $(this).data('fuel');
      var handling = $(this).data('handling');
      var insurance = $(this).data('insurance');
      
      $('#editRateForm').attr('action', '<?php echo site_url("rates/edit/"); ?>' + id);
      $('#edit_origin').val(origin);
      $('#edit_dest').val(dest);
      $('#edit_service').val(service);
      $('#edit_start').val(start);
      $('#edit_end').val(end);
      $('#edit_base').val(base);
      $('#edit_fuel').val(fuel);
      $('#edit_handling').val(handling);
      $('#edit_insurance').val(insurance);
    });

    // Custom Datatables Filter for Destination Country
    $('#destCountryFilter').on('change', function() {
      var val = $(this).val();
      var table = $('.dataTable').DataTable();
      table.column(0).search(val ? 'dest:' + val : '').draw();
    });
  });
</script>
