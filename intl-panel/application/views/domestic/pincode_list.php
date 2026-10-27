<!-- Content Header (Page header) -->
<section class="content-header" style="padding-top: 5px; padding-bottom: 15px;">
  <h1>
    <i class="fa fa-map-pin text-primary"></i> Domestic Pincode Master
    <small>Manage serviceable domestic postal codes, zones &amp; metro classification</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="javascript:void(0);">Domestic</a></li>
    <li class="active">Pincode List</li>
  </ol>
</section>

<!-- KPI Stats Row -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-aqua">
      <span class="info-box-icon"><i class="fa fa-map-pin"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Active Pincodes</span>
        <span class="info-box-number"><?php echo isset($stats->active_cnt) ? number_format($stats->active_cnt) : '0'; ?></span>
        <div class="progress">
          <div class="progress-bar" style="width: 100%"></div>
        </div>
        <span class="progress-description">Covered in Database</span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-green">
      <span class="info-box-icon"><i class="fa fa-flag"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">States &amp; UTs</span>
        <span class="info-box-number"><?php echo isset($stats->state_cnt) ? number_format($stats->state_cnt) : '0'; ?></span>
        <div class="progress">
          <div class="progress-bar" style="width: 100%"></div>
        </div>
        <span class="progress-description">Regions Across India</span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-yellow">
      <span class="info-box-icon"><i class="fa fa-building"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Metro Pincodes</span>
        <span class="info-box-number"><?php echo isset($stats->metro_cnt) ? number_format($stats->metro_cnt) : '0'; ?></span>
        <div class="progress">
          <div class="progress-bar" style="width: 100%"></div>
        </div>
        <span class="progress-description">Special Tier-1 Coverage</span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box bg-purple">
      <span class="info-box-icon"><i class="fa fa-map-signs"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Non-Metro Pincodes</span>
        <span class="info-box-number"><?php echo isset($stats->non_metro_cnt) ? number_format($stats->non_metro_cnt) : '0'; ?></span>
        <div class="progress">
          <div class="progress-bar" style="width: 100%"></div>
        </div>
        <span class="progress-description">Standard Domestic Network</span>
      </div>
    </div>
  </div>
</div>

<!-- Main Row -->
<div class="row">
  <div class="col-xs-12">
    
    <!-- Filter Box -->
    <div class="box box-default collapsed-box" id="filterBox" style="margin-bottom: 15px; border-top: 3px solid #3c8dbc;">
      <div class="box-header with-border" style="cursor: pointer;" data-widget="collapse">
        <h3 class="box-title text-primary"><i class="fa fa-filter"></i> Advanced Filter Controls</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body" style="background-color: #fafafa;">
        <div class="row">
          <div class="col-md-3 col-sm-6">
            <div class="form-group">
              <label><i class="fa fa-flag-o"></i> State</label>
              <select id="filter_state" class="form-control select2" style="width: 100%;">
                <option value="">-- All States &amp; UTs --</option>
                <?php if (!empty($states)): ?>
                  <?php foreach ($states as $st): ?>
                    <option value="<?php echo htmlspecialchars($st->state_name); ?>">
                      <?php echo htmlspecialchars($st->state_name); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="form-group">
              <label><i class="fa fa-compass"></i> Zone</label>
              <select id="filter_zone" class="form-control" style="width: 100%;">
                <option value="">-- All Zones --</option>
                <option value="N">North (N)</option>
                <option value="S">South (S)</option>
                <option value="E">East (E)</option>
                <option value="W">West (W)</option>
                <option value="NE">North East (NE)</option>
                <option value="NW">North West (NW)</option>
              </select>
            </div>
          </div>

          <div class="col-md-2 col-sm-6">
            <div class="form-group">
              <label><i class="fa fa-building-o"></i> Metro Status</label>
              <select id="filter_metro" class="form-control" style="width: 100%;">
                <option value="">-- All --</option>
                <option value="Y">Metro Only (Y)</option>
                <option value="N">Non-Metro (N)</option>
              </select>
            </div>
          </div>

          <div class="col-md-2 col-sm-6">
            <div class="form-group">
              <label><i class="fa fa-toggle-on"></i> Record Status</label>
              <select id="filter_status" class="form-control" style="width: 100%;">
                <option value="">-- All Active &amp; Inactive --</option>
                <option value="Active">Active Only</option>
                <option value="InActive">InActive Only</option>
              </select>
            </div>
          </div>

          <div class="col-md-2 col-sm-12" style="margin-top: 24px;">
            <button type="button" id="btnFilterApply" class="btn btn-primary btn-sm btn-block">
              <i class="fa fa-search"></i> Apply Filter
            </button>
            <button type="button" id="btnFilterReset" class="btn btn-default btn-sm btn-block" style="margin-top: 5px;">
              <i class="fa fa-refresh"></i> Reset
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pincode Table Box -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-list"></i> Pincode Directory
          <small class="badge bg-blue" id="totalFilteredBadge"><?php echo isset($stats->total) ? number_format($stats->total) : '0'; ?> Records</small>
        </h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addPincodeModal">
            <i class="fa fa-plus-circle"></i> Add New Pincode
          </button>
          <button type="button" id="btnExportCsv" class="btn btn-info btn-sm" title="Export current list to CSV">
            <i class="fa fa-download"></i> Export CSV
          </button>
          <button type="button" id="btnReloadTable" class="btn btn-default btn-sm" title="Refresh Table">
            <i class="fa fa-refresh"></i>
          </button>
        </div>
      </div>

      <div class="box-body table-responsive">
        <table id="pincodesTable" class="table table-bordered table-striped table-hover" style="width: 100%;">
          <thead>
            <tr class="bg-gray-light">
              <th style="width: 45px;" class="text-center">ID</th>
              <th style="width: 90px;">Pincode</th>
              <th>Area / Locality</th>
              <th>District</th>
              <th>State</th>
              <th style="width: 60px;" class="text-center">Zone</th>
              <th style="width: 95px;" class="text-center">Metro</th>
              <th>Circle &amp; Division</th>
              <th style="width: 75px;" class="text-center">Status</th>
              <th style="width: 120px;" class="text-center">Action</th>
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
<!-- ADD PINCODE MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="addPincodeModal" tabindex="-1" role="dialog" aria-labelledby="addPincodeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <?php echo form_open('domestic/pincodes/add', array('id' => 'addPincodeForm')); ?>
      <div class="modal-content">
        <div class="modal-header bg-green" style="color: #fff;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="addPincodeModalLabel"><i class="fa fa-plus-circle"></i> Add New Domestic Pincode</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Pincode <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-map-pin"></i></span>
                  <input type="number" name="pincode" id="add_pincode" class="form-control" placeholder="e.g. 600001" required min="100000" max="999999">
                </div>
                <small class="help-block text-muted">6-digit Indian Postal PIN</small>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>State Name <span class="text-danger">*</span></label>
                <input type="text" name="state_name" id="add_state_name" list="states_datalist" class="form-control" placeholder="e.g. Tamil Nadu, Maharashtra" required>
                <datalist id="states_datalist">
                  <?php if (!empty($states)): ?>
                    <?php foreach ($states as $st): ?>
                      <option value="<?php echo htmlspecialchars($st->state_name); ?>">
                    <?php endforeach; ?>
                  <?php endif; ?>
                </datalist>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>District Name</label>
                <input type="text" name="district_name" id="add_district_name" class="form-control" placeholder="e.g. CHENNAI, BANGALORE">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Area / Locality Name</label>
                <input type="text" name="area_name" id="add_area_name" class="form-control" placeholder="e.g. T Nagar, Anna Nagar, Indiranagar">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Zone</label>
                <select name="zone" id="add_zone" class="form-control">
                  <option value="">-- Select Zone --</option>
                  <option value="N">North (N)</option>
                  <option value="S">South (S)</option>
                  <option value="E">East (E)</option>
                  <option value="W">West (W)</option>
                  <option value="NE">North East (NE)</option>
                  <option value="NW">North West (NW)</option>
                </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Metro Classification</label>
                <select name="metro" id="add_metro" class="form-control">
                  <option value="N" selected>No (Non-Metro)</option>
                  <option value="Y">Yes (Metro City)</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Circle Name</label>
                <input type="text" name="circle_name" id="add_circle_name" class="form-control" placeholder="e.g. Tamilnadu Circle">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Region Name</label>
                <input type="text" name="region_name" id="add_region_name" class="form-control" placeholder="e.g. Chennai Region">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Division Name</label>
                <input type="text" name="division_name" id="add_division_name" class="form-control" placeholder="e.g. Chennai City Central Division">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Status</label>
                <select name="status" id="add_status" class="form-control">
                  <option value="Active" selected>Active</option>
                  <option value="InActive">InActive</option>
                </select>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer" style="background-color: #f9f9f9;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Pincode</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- EDIT PINCODE MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="editPincodeModal" tabindex="-1" role="dialog" aria-labelledby="editPincodeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <?php echo form_open('domestic/pincodes/edit/0', array('id' => 'editPincodeForm')); ?>
      <input type="hidden" name="pincode_id" id="edit_pincode_id">
      <div class="modal-content">
        <div class="modal-header bg-primary" style="color: #fff;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="editPincodeModalLabel"><i class="fa fa-pencil-square-o"></i> Edit Domestic Pincode</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Pincode <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-map-pin"></i></span>
                  <input type="number" name="pincode" id="edit_pincode" class="form-control" required min="100000" max="999999">
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>State Name <span class="text-danger">*</span></label>
                <input type="text" name="state_name" id="edit_state_name" list="states_datalist" class="form-control" required>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>District Name</label>
                <input type="text" name="district_name" id="edit_district_name" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Area / Locality Name</label>
                <input type="text" name="area_name" id="edit_area_name" class="form-control">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Zone</label>
                <select name="zone" id="edit_zone" class="form-control">
                  <option value="">-- Select Zone --</option>
                  <option value="N">North (N)</option>
                  <option value="S">South (S)</option>
                  <option value="E">East (E)</option>
                  <option value="W">West (W)</option>
                  <option value="NE">North East (NE)</option>
                  <option value="NW">North West (NW)</option>
                </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Metro Classification</label>
                <select name="metro" id="edit_metro" class="form-control">
                  <option value="N">No (Non-Metro)</option>
                  <option value="Y">Yes (Metro City)</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Circle Name</label>
                <input type="text" name="circle_name" id="edit_circle_name" class="form-control">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Region Name</label>
                <input type="text" name="region_name" id="edit_region_name" class="form-control">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Division Name</label>
                <input type="text" name="division_name" id="edit_division_name" class="form-control">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status" class="form-control">
                  <option value="Active">Active</option>
                  <option value="InActive">InActive</option>
                </select>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer" style="background-color: #f9f9f9;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Changes</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- SOFT DELETE CONFIRMATION MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-red">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="deleteConfirmModalLabel"><i class="fa fa-trash"></i> Confirm Delete</h4>
      </div>
      <div class="modal-body text-center" style="padding: 20px 15px;">
        <i class="fa fa-exclamation-triangle text-red fa-3x" style="margin-bottom: 10px;"></i>
        <p style="font-size: 15px; margin-bottom: 5px;">Are you sure you want to delete pincode?</p>
        <p style="font-weight: 700; font-size: 16px; color: #333;" id="deletePincodeName">-</p>
        <div class="alert alert-warning" style="margin-top: 10px; margin-bottom: 0; padding: 8px; font-size: 12px; text-align: left;">
          <i class="fa fa-info-circle"></i> <strong>Note:</strong> No hard delete will occur. The status will be set to <code>Delete</code>.
        </div>
      </div>
      <div class="modal-footer" style="text-align: center; background-color: #f9f9f9;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="btnConfirmSoftDelete"><i class="fa fa-trash"></i> Yes, Mark Deleted</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
$(document).ready(function() {
  var csrfTokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var csrfTokenHash = '<?php echo $this->security->get_csrf_hash(); ?>';

  // Destroy previous DataTable instance if exists
  if ($.fn.DataTable.isDataTable('#pincodesTable')) {
    $('#pincodesTable').DataTable().destroy();
  }

  // Initialize Server-Side DataTable
  var pincodesTable = $('#pincodesTable').DataTable({
    "destroy": true,
    "processing": true,
    "serverSide": true,
    "order": [[0, "desc"]],
    "pageLength": 25,
    "lengthMenu": [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]],
    "ajax": {
      "url": "<?php echo site_url('domestic/pincodes/ajax'); ?>",
      "type": "POST",
      "data": function(d) {
        d[csrfTokenName] = csrfTokenHash;
        d.state_filter = $('#filter_state').val();
        d.zone_filter = $('#filter_zone').val();
        d.metro_filter = $('#filter_metro').val();
        d.status_filter = $('#filter_status').val();
      }
    },
    "columnDefs": [
      { "targets": [0], "className": "text-center font-bold" },
      { "targets": [1], "className": "text-center" },
      { "targets": [5, 6, 8, 9], "className": "text-center" },
      { "targets": [9], "orderable": false }
    ],
    "language": {
      "processing": '<div style="padding: 10px;"><i class="fa fa-spinner fa-spin fa-2x fa-fw text-primary"></i> <span style="font-weight: 600;">Loading pincodes directory...</span></div>',
      "emptyTable": "No pincodes found matching the criteria.",
      "search": "_INPUT_",
      "searchPlaceholder": "Search pincode, area, district, state..."
    },
    "drawCallback": function(settings) {
      var total = settings.json ? settings.json.recordsFiltered : 0;
      $('#totalFilteredBadge').text(Number(total).toLocaleString() + ' Records');
    }
  });

  // Filter Actions
  $('#btnFilterApply').on('click', function() {
    pincodesTable.ajax.reload();
  });

  $('#btnFilterReset').on('click', function() {
    $('#filter_state').val('').trigger('change');
    $('#filter_zone').val('');
    $('#filter_metro').val('');
    $('#filter_status').val('');
    pincodesTable.ajax.reload();
  });

  $('#btnReloadTable').on('click', function() {
    pincodesTable.ajax.reload(null, false);
  });

  // Export CSV handler
  $('#btnExportCsv').on('click', function() {
    var state = encodeURIComponent($('#filter_state').val() || '');
    var zone = encodeURIComponent($('#filter_zone').val() || '');
    var metro = encodeURIComponent($('#filter_metro').val() || '');
    var status = encodeURIComponent($('#filter_status').val() || '');
    var exportUrl = "<?php echo site_url('domestic/pincodes/export'); ?>?state=" + state + "&zone=" + zone + "&metro=" + metro + "&status=" + status;
    window.location.href = exportUrl;
  });

  // Edit Button Click (Delegated)
  $('#pincodesTable').on('click', '.edit-pincode-btn', function() {
    var id = $(this).data('id');
    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
      url: "<?php echo site_url('domestic/pincodes/get/'); ?>" + id,
      type: "GET",
      dataType: "json",
      success: function(res) {
        btn.prop('disabled', false).html('<i class="fa fa-pencil"></i> Edit');
        if (res.status === 'success') {
          var d = res.data;
          $('#edit_pincode_id').val(d.pincode_id);
          $('#edit_pincode').val(d.pincode);
          $('#edit_state_name').val(d.state_name || '');
          $('#edit_district_name').val(d.district_name || '');
          $('#edit_area_name').val(d.area_name || '');
          $('#edit_circle_name').val(d.circle_name || '');
          $('#edit_region_name').val(d.region_name || '');
          $('#edit_division_name').val(d.division_name || '');
          $('#edit_zone').val(d.zone ? d.zone.toUpperCase() : '');
          $('#edit_metro').val(d.metro || 'N');
          $('#edit_status').val(d.status || 'Active');

          $('#editPincodeForm').attr('action', "<?php echo site_url('domestic/pincodes/edit/'); ?>" + d.pincode_id);
          $('#editPincodeModal').modal('show');
        } else {
          alert(res.message || 'Error fetching pincode details.');
        }
      },
      error: function() {
        btn.prop('disabled', false).html('<i class="fa fa-pencil"></i> Edit');
        alert('Server connection error. Please try again.');
      }
    });
  });

  // Soft Delete Button Click
  var targetDeleteId = null;
  $('#pincodesTable').on('click', '.delete-pincode-btn', function() {
    targetDeleteId = $(this).data('id');
    var pin = $(this).data('pincode');
    var area = $(this).data('area');
    $('#deletePincodeName').html('<i class="fa fa-map-pin text-primary"></i> ' + pin + (area ? ' &mdash; ' + area : ''));
    $('#deleteConfirmModal').modal('show');
  });

  // Confirm Soft Delete Click
  $('#btnConfirmSoftDelete').on('click', function() {
    if (!targetDeleteId) return;
    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

    var postData = {};
    postData[csrfTokenName] = csrfTokenHash;

    $.ajax({
      url: "<?php echo site_url('domestic/pincodes/delete/'); ?>" + targetDeleteId,
      type: "POST",
      data: postData,
      dataType: "json",
      success: function(res) {
        btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Yes, Mark Deleted');
        $('#deleteConfirmModal').modal('hide');
        if (res.status === 'success') {
          pincodesTable.ajax.reload(null, false);
        } else {
          alert(res.message || 'Failed to delete pincode.');
        }
      },
      error: function() {
        btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Yes, Mark Deleted');
        $('#deleteConfirmModal').modal('hide');
        // Fallback to standard delete redirect
        window.location.href = "<?php echo site_url('domestic/pincodes/delete/'); ?>" + targetDeleteId;
      }
    });
  });

});
</script>
