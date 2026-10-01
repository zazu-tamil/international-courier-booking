<div class="row">
  <div class="col-xs-12">
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-map-marker text-primary"></i> Geo Locations Master 
          <small class="badge bg-aqua" id="totalCountBadge"><?php echo number_format($total_count); ?> Records</small>
        </h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addGeoModal">
            <i class="fa fa-plus"></i> Add Geo Location
          </button>
          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importGeoModal">
            <i class="fa fa-upload"></i> Import Excel / CSV
          </button>
          <a href="<?php echo site_url('geo-locations/export'); ?>" class="btn btn-info btn-sm" title="Export all geo locations to Excel">
            <i class="fa fa-download"></i> Export Excel
          </a>
          <a href="<?php echo site_url('geo-locations/template'); ?>" class="btn btn-default btn-sm" title="Download Excel template for import">
            <i class="fa fa-file-excel-o text-green"></i> Sample Template
          </a>
        </div>
      </div>

      <div class="box-body table-responsive">
        <table id="geoLocationsTable" class="table table-bordered table-striped" style="width: 100%;">
          <thead>
            <tr class="bg-gray-light">
              <th style="width: 50px;">ID</th>
              <th>Country</th>
              <th>State</th>
              <th>District</th>
              <th>City</th>
              <th>Postal Code &amp; Area</th>
              <th>Coordinates</th>
              <th>Status</th>
              <th style="width: 110px;">Actions</th>
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
<!-- ADD GEO LOCATION MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="addGeoModal" tabindex="-1" role="dialog" aria-labelledby="addGeoModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <?php echo form_open('geo-locations/add'); ?>
      <div class="modal-content">
        <div class="modal-header bg-green">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="addGeoModalLabel"><i class="fa fa-plus-circle"></i> Add New Geo Location</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Country Name <span class="text-danger">*</span></label>
                <input type="text" name="country_name" id="add_country_name" list="countries_list" class="form-control" placeholder="e.g. India, United States" required>
                <datalist id="countries_list">
                  <?php if (!empty($countries)): ?>
                    <?php foreach ($countries as $c): ?>
                      <option value="<?php echo htmlspecialchars($c->country_name); ?>" data-iso="<?php echo $c->iso_code; ?>">
                    <?php endforeach; ?>
                  <?php endif; ?>
                </datalist>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Country Code (ISO-2)</label>
                <input type="text" name="country_code" id="add_country_code" class="form-control" maxlength="2" placeholder="e.g. IN, US" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Country Code (ISO-3)</label>
                <input type="text" name="country_code3" id="add_country_code3" class="form-control" maxlength="3" placeholder="e.g. IND, USA" style="text-transform: uppercase;">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>State Name</label>
                <input type="text" name="state_name" class="form-control" placeholder="e.g. Tamil Nadu, California">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>State Code</label>
                <input type="text" name="state_code" class="form-control" placeholder="e.g. TN, CA">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>State Type</label>
                <input type="text" name="state_type" class="form-control" placeholder="e.g. State, Province, UT">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>District Name</label>
                <input type="text" name="district_name" class="form-control" placeholder="e.g. Chennai, Los Angeles">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>District Code</label>
                <input type="text" name="district_code" class="form-control" placeholder="e.g. CH">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>District Type</label>
                <input type="text" name="district_type" class="form-control" placeholder="e.g. District, County">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>City Name</label>
                <input type="text" name="city_name" class="form-control" placeholder="e.g. Chennai, New York">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>City Type</label>
                <input type="text" name="city_type" class="form-control" placeholder="e.g. City, Town, Municipality">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Postal Code / PIN Code</label>
                <input type="text" name="postal_code" class="form-control" placeholder="e.g. 600001, 10001">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Postal Name / Area / Locality</label>
                <input type="text" name="postal_name" class="form-control" placeholder="e.g. George Town, Manhattan">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Latitude</label>
                <input type="number" step="any" name="latitude" class="form-control" placeholder="e.g. 13.0827000">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Longitude</label>
                <input type="number" step="any" name="longitude" class="form-control" placeholder="e.g. 80.2707000">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                  <option value="1" selected>Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Geo Location</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- EDIT GEO LOCATION MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="editGeoModal" tabindex="-1" role="dialog" aria-labelledby="editGeoModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form id="editGeoForm" method="post" action="">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="editGeoModalLabel"><i class="fa fa-pencil-square-o"></i> Edit Geo Location</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Country Name <span class="text-danger">*</span></label>
                <input type="text" name="country_name" id="edit_country_name" list="countries_list" class="form-control" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Country Code (ISO-2)</label>
                <input type="text" name="country_code" id="edit_country_code" class="form-control" maxlength="2" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Country Code (ISO-3)</label>
                <input type="text" name="country_code3" id="edit_country_code3" class="form-control" maxlength="3" style="text-transform: uppercase;">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>State Name</label>
                <input type="text" name="state_name" id="edit_state_name" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>State Code</label>
                <input type="text" name="state_code" id="edit_state_code" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>State Type</label>
                <input type="text" name="state_type" id="edit_state_type" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>District Name</label>
                <input type="text" name="district_name" id="edit_district_name" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>District Code</label>
                <input type="text" name="district_code" id="edit_district_code" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>District Type</label>
                <input type="text" name="district_type" id="edit_district_type" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>City Name</label>
                <input type="text" name="city_name" id="edit_city_name" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>City Type</label>
                <input type="text" name="city_type" id="edit_city_type" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Postal Code / PIN Code</label>
                <input type="text" name="postal_code" id="edit_postal_code" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Postal Name / Area / Locality</label>
                <input type="text" name="postal_name" id="edit_postal_name" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Latitude</label>
                <input type="number" step="any" name="latitude" id="edit_latitude" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Longitude</label>
                <input type="number" step="any" name="longitude" id="edit_longitude" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status</label>
                <select name="is_active" id="edit_is_active" class="form-control">
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update Geo Location</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ============================================================== -->
<!-- IMPORT EXCEL / CSV MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="importGeoModal" tabindex="-1" role="dialog" aria-labelledby="importGeoModalLabel">
  <div class="modal-dialog" role="document">
    <?php echo form_open_multipart('geo-locations/import'); ?>
      <div class="modal-content">
        <div class="modal-header bg-purple">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="importGeoModalLabel"><i class="fa fa-file-excel-o"></i> Import Geo Locations from Excel / CSV</h4>
        </div>
        <div class="modal-body">
          <div class="callout callout-info" style="margin-bottom: 15px;">
            <h4><i class="fa fa-info-circle"></i> Import Instructions</h4>
            <p>Upload an Excel (<code>.xls</code>, <code>.xlsx</code>) or <code>.csv</code> file. The first row must be the column header.</p>
            <ul style="padding-left: 20px;">
              <li><strong>Required:</strong> <code>Country Name</code> (or <code>Postal Code</code>)</li>
              <li><strong>Optional:</strong> <code>Country Code</code>, <code>State Name</code>, <code>City Name</code>, <code>District Name</code>, <code>Postal Name</code>, <code>Latitude</code>, <code>Longitude</code>, <code>Status</code></li>
              <li>Existing records matching Country + Postal Code + City will be automatically updated with the new details.</li>
            </ul>
          </div>

          <div class="form-group">
            <label>Select Excel / CSV File <span class="text-danger">*</span></label>
            <input type="file" name="excel_file" class="form-control" accept=".xls,.xlsx,.csv" required>
            <p class="help-block">Max file size according to your PHP server configuration. Allowed formats: <code>.xls</code>, <code>.xlsx</code>, <code>.csv</code></p>
          </div>

          <div class="text-center" style="margin-top: 15px; margin-bottom: 10px;">
            <a href="<?php echo site_url('geo-locations/template'); ?>" class="btn btn-default btn-sm">
              <i class="fa fa-download text-green"></i> Download Formatted Sample Template (.xls)
            </a>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary bg-purple"><i class="fa fa-upload"></i> Start Upload &amp; Import</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
$(document).ready(function() {
  // Prevent Reinitialise DataTable warning (destroy existing instance if any)
  if ($.fn.DataTable.isDataTable('#geoLocationsTable')) {
    $('#geoLocationsTable').DataTable().destroy();
  }

  // Initialize Server-Side DataTable
  var table = $('#geoLocationsTable').DataTable({
    "destroy": true,
    "processing": true,
    "serverSide": true,
    "order": [[0, "desc"]],
    "pageLength": 25,
    "lengthMenu": [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]],
    "ajax": {
      "url": "<?php echo site_url('geo-locations/ajax'); ?>",
      "type": "POST",
      "data": function(d) {
        d.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
      }
    },
    "columnDefs": [
      { "targets": [7, 8], "orderable": false },
      { "targets": [0], "className": "text-center" },
      { "targets": [7, 8], "className": "text-center" }
    ],
    "language": {
      "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i> Loading data...',
      "emptyTable": "No geo locations found. Click 'Add Geo Location' or 'Import Excel' to get started."
    }
  });

  // Country Selection Helper: Auto-fill ISO Code if found
  $('#add_country_name').on('input', function() {
    var val = $(this).val();
    var opt = $('#countries_list option[value="' + val + '"]');
    if (opt.length > 0) {
      var iso = opt.data('iso');
      if (iso && !$('#add_country_code3').val()) {
        $('#add_country_code3').val(iso);
      }
    }
  });

  // Edit Button Click (Delegated for dynamically loaded DataTable rows)
  $('#geoLocationsTable').on('click', '.edit-geo-btn', function() {
    var id = $(this).data('id');
    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
      url: "<?php echo site_url('geo-locations/get/'); ?>" + id,
      type: "GET",
      dataType: "json",
      success: function(res) {
        btn.prop('disabled', false).html('<i class="fa fa-pencil"></i> Edit');
        if (res.status === 'success') {
          var d = res.data;
          $('#edit_id').val(d.id);
          $('#edit_country_name').val(d.country_name);
          $('#edit_country_code').val(d.country_code);
          $('#edit_country_code3').val(d.country_code3);
          $('#edit_state_name').val(d.state_name);
          $('#edit_state_code').val(d.state_code);
          $('#edit_state_type').val(d.state_type);
          $('#edit_district_name').val(d.district_name);
          $('#edit_district_code').val(d.district_code);
          $('#edit_district_type').val(d.district_type);
          $('#edit_city_name').val(d.city_name);
          $('#edit_city_type').val(d.city_type);
          $('#edit_postal_code').val(d.postal_code);
          $('#edit_postal_name').val(d.postal_name);
          $('#edit_latitude').val(d.latitude);
          $('#edit_longitude').val(d.longitude);
          $('#edit_is_active').val(d.is_active);

          $('#editGeoForm').attr('action', "<?php echo site_url('geo-locations/edit/'); ?>" + d.id);
          $('#editGeoModal').modal('show');
        } else {
          alert('Failed to fetch details: ' + (res.message || 'Unknown error'));
        }
      },
      error: function() {
        btn.prop('disabled', false).html('<i class="fa fa-pencil"></i> Edit');
        alert('Server error occurred while fetching details.');
      }
    });
  });
});
</script>
