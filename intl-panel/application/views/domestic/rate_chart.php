<!-- Content Header (Page header) -->
<section class="content-header" style="padding-top: 5px; padding-bottom: 15px;">
  <h1>
    <i class="fa fa-table text-primary"></i> Domestic Rate Chart
    <small>Weight slab tariffs based on zonal and metro destination classifications</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="javascript:void(0);">Domestic</a></li>
    <li class="active">Rate Chart</li>
  </ol>
</section>

<!-- Action Bar & Filter Header -->
<div class="row" style="margin-bottom: 15px;">
  <div class="col-md-7 col-xs-12">
    <div class="btn-group" role="group">
      <a href="<?php echo site_url('domestic/rates'); ?>" class="btn btn-sm <?php echo empty($selected_type) ? 'btn-primary active' : 'btn-default'; ?>" style="font-weight: 600;">
        <i class="fa fa-cubes"></i> All Modes (<?php echo isset($stats->total_rates) ? $stats->total_rates : 0; ?> Rates)
      </a>
      <a href="<?php echo site_url('domestic/rates?c_type=Air'); ?>" class="btn btn-sm <?php echo ($selected_type === 'Air') ? 'btn-primary active' : 'btn-default'; ?>" style="font-weight: 600;">
        <i class="fa fa-plane text-info"></i> Air Mode (<?php echo isset($stats->air_slabs) ? $stats->air_slabs : 0; ?> Slabs)
      </a>
      <a href="<?php echo site_url('domestic/rates?c_type=Surface'); ?>" class="btn btn-sm <?php echo ($selected_type === 'Surface') ? 'btn-primary active' : 'btn-default'; ?>" style="font-weight: 600;">
        <i class="fa fa-truck text-warning"></i> Surface Mode (<?php echo isset($stats->surface_slabs) ? $stats->surface_slabs : 0; ?> Slabs)
      </a>
    </div>
  </div>

  <div class="col-md-5 col-xs-12 text-right" style="margin-top: 5px;">
    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addRateModal" style="background-color: #28a745; border-color: #28a745; font-weight: 600;">
      <i class="fa fa-plus-circle"></i> Add Domestic Rate
    </button>
    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#calcRateModal" style="background-color: #17a2b8; border-color: #17a2b8; font-weight: 600; margin-left: 5px;">
      <i class="fa fa-calculator"></i> Rate Calculator
    </button>
  </div>
</div>

<!-- Rates Slabs Display -->
<?php if (!empty($slabs)): ?>
  <?php foreach ($slabs as $slabKey => $slab): ?>
    <div class="box box-solid" style="border: 1px solid #d2d6de; border-top: 3px solid #3c8dbc; border-radius: 4px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
      
      <!-- Slab Header matching reference image -->
      <div class="box-header with-border" style="background: #fff; padding: 12px 18px;">
        <h3 class="box-title" style="font-size: 16px; font-weight: 700; color: #333; margin: 0; line-height: 24px;">
          <i class="<?php echo ($slab['c_type'] === 'Air') ? 'fa fa-plane text-info' : 'fa fa-truck text-warning'; ?>" style="font-size: 18px; margin-right: 6px;"></i> 
          <?php echo htmlspecialchars($slab['c_type']); ?>
          <span class="badge" style="background-color: #00a7d0; font-size: 13px; font-weight: 600; padding: 4px 10px; margin-left: 10px; border-radius: 4px; letter-spacing: 0.3px;">
            Weight: <?php echo number_format($slab['from_weight'], 3); ?> - <?php echo number_format($slab['to_weight'], 3); ?> Kg
          </span>
        </h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" title="Collapse/Expand Slab">
            <i class="fa fa-minus"></i>
          </button>
        </div>
      </div>

      <!-- Slab Table matching reference image -->
      <div class="box-body no-padding table-responsive">
        <table class="table table-bordered table-striped" style="margin-bottom: 0; font-size: 13px; width: 100%;">
          <thead>
            <tr style="background-color: #fafafa; color: #333;">
              <th rowspan="2" class="text-center" style="vertical-align: middle; width: 45px; border-bottom: 2px solid #ddd;">#</th>
              <th rowspan="2" class="text-center" style="vertical-align: middle; width: 130px; border-bottom: 2px solid #ddd;">Last Updated</th>
              <th colspan="4" class="text-center" style="background-color: #f4f6f9; font-weight: 700; border-bottom: 1px solid #ddd;">Source &amp; Destination</th>
              <th rowspan="2" class="text-center" style="vertical-align: middle; border-bottom: 2px solid #ddd;">Init. Wgt <small class="text-muted" style="font-size: 11px;">(Kg)</small></th>
              <th rowspan="2" class="text-center" style="vertical-align: middle; border-bottom: 2px solid #ddd;">Init. Charges</th>
              <th rowspan="2" class="text-center" style="vertical-align: middle; border-bottom: 2px solid #ddd;">Addt. Wgt <small class="text-muted" style="font-size: 11px;">(Kg)</small></th>
              <th rowspan="2" class="text-center" style="vertical-align: middle; border-bottom: 2px solid #ddd;">Addt. Charges</th>
              <th rowspan="2" class="text-center" style="vertical-align: middle; width: 70px; border-bottom: 2px solid #ddd;">Action</th>
            </tr>
            <tr style="background-color: #fafafa; color: #555; font-size: 12px;">
              <th class="text-center" style="width: 120px; border-bottom: 2px solid #ddd;">Within Region</th>
              <th class="text-center" style="width: 120px; border-bottom: 2px solid #ddd;">Within State</th>
              <th class="text-center" style="width: 120px; border-bottom: 2px solid #ddd;">Within City</th>
              <th class="text-center" style="width: 95px; border-bottom: 2px solid #ddd;">Metro</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($slab['rates'] as $idx => $r): ?>
              <tr>
                <td class="text-center" style="vertical-align: middle; font-weight: 600;"><?php echo ($idx + 1); ?></td>
                <td class="text-center" style="vertical-align: middle; font-size: 13px;">
                  <?php echo !empty($r->rate_as_on) ? date('d-m-Y', strtotime($r->rate_as_on)) : date('d-m-Y'); ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                  <?php if ($r->flg_region == '1'): ?>
                    <i class="fa fa-check text-green" style="font-size: 16px; font-weight: 700;"></i>
                  <?php else: ?>
                    <i class="fa fa-times text-danger" style="font-size: 15px; color: #dd4b39; font-weight: 700;"></i>
                  <?php endif; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                  <?php if ($r->flg_state == '1'): ?>
                    <i class="fa fa-check text-green" style="font-size: 16px; font-weight: 700;"></i>
                  <?php else: ?>
                    <i class="fa fa-times text-danger" style="font-size: 15px; color: #dd4b39; font-weight: 700;"></i>
                  <?php endif; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                  <?php if ($r->flg_city == '1'): ?>
                    <i class="fa fa-check text-green" style="font-size: 16px; font-weight: 700;"></i>
                  <?php else: ?>
                    <i class="fa fa-times text-danger" style="font-size: 15px; color: #dd4b39; font-weight: 700;"></i>
                  <?php endif; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                  <?php if ($r->flg_metro == '1'): ?>
                    <i class="fa fa-check text-green" style="font-size: 16px; font-weight: 700;"></i>
                  <?php else: ?>
                    <i class="fa fa-times text-danger" style="font-size: 15px; color: #dd4b39; font-weight: 700;"></i>
                  <?php endif; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;"><?php echo number_format($r->min_weight, 3); ?></td>
                <td class="text-center" style="vertical-align: middle; font-weight: 700; color: #222; font-size: 14px;">
                  &#8377;<?php echo number_format($r->min_charges, 2); ?>
                </td>
                <td class="text-center" style="vertical-align: middle;"><?php echo number_format($r->addt_weight, 3); ?></td>
                <td class="text-center" style="vertical-align: middle; font-weight: 700; color: #222; font-size: 14px;">
                  &#8377;<?php echo number_format($r->addt_charges, 2); ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                  <button type="button" class="btn btn-primary btn-sm edit-rate-btn" data-id="<?php echo $r->domestic_rate_id; ?>" style="background-color: #007bff; border-color: #007bff; padding: 4px 10px; border-radius: 4px;" title="Edit Domestic Rate">
                    <i class="fa fa-pencil-square-o"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <div class="box box-default">
    <div class="box-body text-center" style="padding: 50px 20px;">
      <i class="fa fa-table text-muted" style="font-size: 48px; margin-bottom: 15px;"></i>
      <h4 style="font-weight: 600; color: #555;">No Domestic Rates Found</h4>
      <p class="text-muted" style="margin-bottom: 20px;">No domestic shipping rates have been configured for the selected filter.</p>
      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addRateModal">
        <i class="fa fa-plus-circle"></i> Add First Domestic Rate
      </button>
    </div>
  </div>
<?php endif; ?>

<!-- ============================================================== -->
<!-- ADD DOMESTIC RATE MODAL (Matching Image 2) -->
<!-- ============================================================== -->
<div class="modal fade" id="addRateModal" tabindex="-1" role="dialog" aria-labelledby="addRateModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 780px;">
    <?php echo form_open('domestic/rates/add', array('id' => 'addRateForm')); ?>
      <div class="modal-content" style="border-radius: 6px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        
        <!-- Modal Header -->
        <div class="modal-header bg-green" style="background-color: #28a745; color: #fff; padding: 12px 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9; font-size: 24px; margin-top: -2px;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="addRateModalLabel" style="font-weight: 600; font-size: 17px;">
            <i class="fa fa-plus-circle"></i> Add Domestic Rate
          </h4>
        </div>

        <!-- Modal Body -->
        <div class="modal-body" style="padding: 22px 25px;">
          
          <!-- Row 1: Courier Type, Min Weight, Max Weight -->
          <div class="row">
            <div class="col-md-4 col-sm-12">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333; display: block; margin-bottom: 8px;">Courier Type</label>
                <div style="padding-top: 4px;">
                  <label class="radio-inline" style="font-weight: 600; margin-right: 20px; font-size: 14px;">
                    <input type="radio" name="c_type" value="Air" checked style="margin-top: 2px;"> Air
                  </label>
                  <label class="radio-inline" style="font-weight: 600; font-size: 14px;">
                    <input type="radio" name="c_type" value="Surface" style="margin-top: 2px;"> Surface
                  </label>
                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Min Weight</label>
                <div class="input-group">
                  <input type="number" step="0.001" min="0" name="from_weight" class="form-control" placeholder="0.000" required style="border-radius: 4px 0 0 4px; text-align: right;">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-size: 13px;">in Kg</span>
                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Max Weight</label>
                <div class="input-group">
                  <input type="number" step="0.001" min="0" name="to_weight" class="form-control" placeholder="10.000" required style="border-radius: 4px 0 0 4px; text-align: right;">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-size: 13px;">in Kg</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 2: Source & Destination Within Same Box -->
          <div class="row">
            <div class="col-xs-12">
              <div class="well well-sm" style="background-color: #fff; border: 1px solid #d2d6de; border-radius: 6px; padding: 12px 18px; margin: 5px 0 15px 0;">
                <label style="font-weight: 700; color: #444; display: block; margin-bottom: 10px; font-size: 14px;">
                  <i class="fa fa-map-marker text-muted" style="margin-right: 4px;"></i> Source &amp; Destination Within Same:
                </label>
                <div style="padding-left: 5px;">
                  <label class="checkbox-inline" style="font-weight: 600; margin-right: 25px; font-size: 13px;">
                    <input type="checkbox" name="flg_region" value="1" style="margin-top: 2px;"> Region
                  </label>
                  <label class="checkbox-inline" style="font-weight: 600; margin-right: 25px; font-size: 13px;">
                    <input type="checkbox" name="flg_state" value="1" style="margin-top: 2px;"> State
                  </label>
                  <label class="checkbox-inline" style="font-weight: 600; margin-right: 25px; font-size: 13px;">
                    <input type="checkbox" name="flg_city" value="1" style="margin-top: 2px;"> City
                  </label>
                  <label class="checkbox-inline" style="font-weight: 600; font-size: 13px;">
                    <input type="checkbox" name="flg_metro" value="1" style="margin-top: 2px;"> Metro
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 3: Initial Weight & Charges -->
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Initial Weight</label>
                <div class="input-group">
                  <input type="number" step="0.001" min="0" name="min_weight" class="form-control" placeholder="0.500" required style="border-radius: 4px 0 0 4px; text-align: right;">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-size: 13px;">in Kg</span>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Initial Wgt Charges</label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-weight: 700;">&#8377;</span>
                  <input type="number" step="0.01" min="0" name="min_charges" class="form-control" placeholder="0.00" required style="border-radius: 0 4px 4px 0; text-align: right;">
                </div>
              </div>
            </div>
          </div>

          <!-- Row 4: Additional Weight Step & Charges -->
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <div class="form-group" style="margin-bottom: 10px;">
                <label style="font-weight: 700; color: #333;">Additional Weight Step</label>
                <div class="input-group">
                  <input type="number" step="0.001" min="0" name="addt_weight" class="form-control" placeholder="0.500" required style="border-radius: 4px 0 0 4px; text-align: right;">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-size: 13px;">in Kg</span>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-sm-6">
              <div class="form-group" style="margin-bottom: 10px;">
                <label style="font-weight: 700; color: #333;">Additional Wgt Charges</label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-weight: 700;">&#8377;</span>
                  <input type="number" step="0.01" min="0" name="addt_charges" class="form-control" placeholder="0.00" required style="border-radius: 0 4px 4px 0; text-align: right;">
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer" style="background-color: #fff; border-top: 1px solid #e5e5e5; padding: 15px 25px;">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color: #6c757d; color: #fff; border-color: #6c757d; font-weight: 600; min-width: 80px; padding: 7px 16px;">
            Cancel
          </button>
          <button type="submit" class="btn btn-success" style="background-color: #28a745; border-color: #28a745; font-weight: 600; padding: 7px 18px;">
            <i class="fa fa-save" style="margin-right: 4px;"></i> Save Rate
          </button>
        </div>

      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- EDIT DOMESTIC RATE MODAL (Matching Image 3) -->
<!-- ============================================================== -->
<div class="modal fade" id="editRateModal" tabindex="-1" role="dialog" aria-labelledby="editRateModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 780px;">
    <?php echo form_open('domestic/rates/edit/0', array('id' => 'editRateForm')); ?>
      <input type="hidden" name="domestic_rate_id" id="edit_domestic_rate_id">
      
      <div class="modal-content" style="border-radius: 6px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        
        <!-- Modal Header -->
        <div class="modal-header bg-primary" style="background-color: #007bff; color: #fff; padding: 12px 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9; font-size: 24px; margin-top: -2px;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="editRateModalLabel" style="font-weight: 600; font-size: 17px;">
            <i class="fa fa-pencil-square-o"></i> Edit Domestic Rate
          </h4>
        </div>

        <!-- Modal Body -->
        <div class="modal-body" style="padding: 22px 25px;">
          
          <!-- Row 1: Courier Type, Min Weight, Max Weight -->
          <div class="row">
            <div class="col-md-4 col-sm-12">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333; display: block; margin-bottom: 8px;">Courier Type</label>
                <div style="padding-top: 4px;">
                  <label class="radio-inline" style="font-weight: 600; margin-right: 20px; font-size: 14px;">
                    <input type="radio" name="c_type" id="edit_ctype_air" value="Air" style="margin-top: 2px;"> Air
                  </label>
                  <label class="radio-inline" style="font-weight: 600; font-size: 14px;">
                    <input type="radio" name="c_type" id="edit_ctype_surface" value="Surface" style="margin-top: 2px;"> Surface
                  </label>
                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Min Weight</label>
                <div class="input-group">
                  <input type="number" step="0.001" min="0" name="from_weight" id="edit_from_weight" class="form-control" required style="border-radius: 4px 0 0 4px; text-align: right;">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-size: 13px;">in Kg</span>
                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Max Weight</label>
                <div class="input-group">
                  <input type="number" step="0.001" min="0" name="to_weight" id="edit_to_weight" class="form-control" required style="border-radius: 4px 0 0 4px; text-align: right;">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-size: 13px;">in Kg</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 2: Source & Destination Within Same Box -->
          <div class="row">
            <div class="col-xs-12">
              <div class="well well-sm" style="background-color: #fff; border: 1px solid #d2d6de; border-radius: 6px; padding: 12px 18px; margin: 5px 0 15px 0;">
                <label style="font-weight: 700; color: #444; display: block; margin-bottom: 10px; font-size: 14px;">
                  <i class="fa fa-map-marker text-muted" style="margin-right: 4px;"></i> Source &amp; Destination Within Same:
                </label>
                <div style="padding-left: 5px;">
                  <label class="checkbox-inline" style="font-weight: 600; margin-right: 25px; font-size: 13px;">
                    <input type="checkbox" name="flg_region" id="edit_flg_region" value="1" style="margin-top: 2px;"> Region
                  </label>
                  <label class="checkbox-inline" style="font-weight: 600; margin-right: 25px; font-size: 13px;">
                    <input type="checkbox" name="flg_state" id="edit_flg_state" value="1" style="margin-top: 2px;"> State
                  </label>
                  <label class="checkbox-inline" style="font-weight: 600; margin-right: 25px; font-size: 13px;">
                    <input type="checkbox" name="flg_city" id="edit_flg_city" value="1" style="margin-top: 2px;"> City
                  </label>
                  <label class="checkbox-inline" style="font-weight: 600; font-size: 13px;">
                    <input type="checkbox" name="flg_metro" id="edit_flg_metro" value="1" style="margin-top: 2px;"> Metro
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 3: Initial Weight & Charges -->
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Initial Weight</label>
                <div class="input-group">
                  <input type="number" step="0.001" min="0" name="min_weight" id="edit_min_weight" class="form-control" required style="border-radius: 4px 0 0 4px; text-align: right;">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-size: 13px;">in Kg</span>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Initial Wgt Charges</label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-weight: 700;">&#8377;</span>
                  <input type="number" step="0.01" min="0" name="min_charges" id="edit_min_charges" class="form-control" required style="border-radius: 0 4px 4px 0; text-align: right;">
                </div>
              </div>
            </div>
          </div>

          <!-- Row 4: Additional Weight Step & Charges -->
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Additional Weight Step</label>
                <div class="input-group">
                  <input type="number" step="0.001" min="0" name="addt_weight" id="edit_addt_weight" class="form-control" required style="border-radius: 4px 0 0 4px; text-align: right;">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-size: 13px;">in Kg</span>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-sm-6">
              <div class="form-group" style="margin-bottom: 15px;">
                <label style="font-weight: 700; color: #333;">Additional Wgt Charges</label>
                <div class="input-group">
                  <span class="input-group-addon" style="background-color: #f7f7f7; color: #555; font-weight: 700;">&#8377;</span>
                  <input type="number" step="0.01" min="0" name="addt_charges" id="edit_addt_charges" class="form-control" required style="border-radius: 0 4px 4px 0; text-align: right;">
                </div>
              </div>
            </div>
          </div>

          <!-- Row 5: Status (Active / InActive matching Image 3) -->
          <div class="row">
            <div class="col-xs-12" style="margin-top: 5px;">
              <label style="font-weight: 700; color: #333; display: block; margin-bottom: 8px;">Status</label>
              <label class="radio-inline" style="font-weight: 700; color: #28a745; margin-right: 25px; font-size: 14px;">
                <input type="radio" name="status" id="edit_status_active" value="Active" style="margin-top: 2px;"> Active
              </label>
              <label class="radio-inline" style="font-weight: 700; color: #dc3545; font-size: 14px;">
                <input type="radio" name="status" id="edit_status_inactive" value="InActive" style="margin-top: 2px;"> InActive
              </label>
            </div>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer" style="background-color: #fff; border-top: 1px solid #e5e5e5; padding: 15px 25px;">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color: #6c757d; color: #fff; border-color: #6c757d; font-weight: 600; min-width: 80px; padding: 7px 16px;">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; font-weight: 600; padding: 7px 18px;">
            <i class="fa fa-save" style="margin-right: 4px;"></i> Update Changes
          </button>
        </div>

      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- ============================================================== -->
<!-- DOMESTIC RATE CALCULATOR MODAL -->
<!-- ============================================================== -->
<div class="modal fade" id="calcRateModal" tabindex="-1" role="dialog" aria-labelledby="calcRateModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 720px;">
    <div class="modal-content" style="border-radius: 6px; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.25);">
      <div class="modal-header bg-aqua" style="background-color: #17a2b8; color: #fff; padding: 14px 20px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9; font-size: 24px; margin-top: -2px;">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="calcRateModalLabel" style="font-weight: 600;">
          <i class="fa fa-calculator"></i> Domestic Shipping Rate Calculator
        </h4>
      </div>
      <div class="modal-body" style="padding: 20px 25px;">
        
        <!-- Row 1: Mode & Weight -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label style="font-weight: 700; color: #333;">Courier Mode <span class="text-danger">*</span></label>
              <select id="calc_ctype" class="form-control">
                <option value="Air">Air Mode</option>
                <option value="Surface">Surface Mode</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label style="font-weight: 700; color: #333;">Actual / Chargeable Weight <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number" step="0.001" min="0.001" id="calc_weight" class="form-control" placeholder="e.g. 2.500" value="1.000">
                <span class="input-group-addon" style="font-weight: 600;">in Kg</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Row 2: Source & Destination Pincodes -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-weight: 700; color: #333;">Source Pincode (Origin) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-addon" style="background-color: #f4f6f9;"><i class="fa fa-map-marker text-green"></i></span>
                <input type="text" id="calc_source_pincode" maxlength="6" class="form-control" placeholder="e.g. 600001">
              </div>
              <div id="source_pincode_feedback" style="font-size: 11px; margin-top: 4px; min-height: 18px; color: #666;"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group" style="margin-bottom: 10px;">
              <label style="font-weight: 700; color: #333;">Destination Pincode <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-addon" style="background-color: #f4f6f9;"><i class="fa fa-map-marker text-red"></i></span>
                <input type="text" id="calc_dest_pincode" maxlength="6" class="form-control" placeholder="e.g. 110001">
              </div>
              <div id="dest_pincode_feedback" style="font-size: 11px; margin-top: 4px; min-height: 18px; color: #666;"></div>
            </div>
          </div>
        </div>

        <!-- Row 3: Product Category & Destination Classification -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label style="font-weight: 700; color: #333;">Product Category</label>
              <select id="calc_category_id" class="form-control">
                <option value="">-- Standard / General Category (No Surcharge) --</option>
                <?php if (!empty($categories)): ?>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat->category_id; ?>" data-charge="<?php echo floatval($cat->extra_charge); ?>">
                      <?php echo htmlspecialchars($cat->category_name); ?>
                      <?php if (floatval($cat->extra_charge) > 0): ?>
                        (+&#8377;<?php echo number_format($cat->extra_charge, 2); ?> Surcharge)
                      <?php endif; ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <small class="text-muted" style="font-size: 11px;">Select category to factor in special handling charges</small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label style="font-weight: 700; color: #333;">
                Destination Classification
                <span id="autoDetectBadge" style="display: none; margin-left: 5px;"></span>
              </label>
              <select id="calc_dest_type" class="form-control">
                <option value="national">National / Rest of India (Default)</option>
                <option value="metro">Metro City Destination</option>
                <option value="region">Within Same Region / Zone</option>
                <option value="state">Within Same State</option>
                <option value="city">Within Same City (Local)</option>
              </select>
              <small class="text-muted" style="font-size: 11px;">Auto-detected from pincodes (can be overridden)</small>
            </div>
          </div>
        </div>

        <button type="button" id="btnRunCalc" class="btn btn-info btn-block" style="background-color: #17a2b8; border-color: #17a2b8; font-weight: 600; font-size: 15px; padding: 10px 15px; margin-top: 10px;">
          <i class="fa fa-calculator"></i> Calculate Freight Charges
        </button>

        <!-- Calculation Result Display -->
        <div id="calcResultBox" style="display: none; margin-top: 20px; border: 1px solid #b8daff; background-color: #f7fbff; border-radius: 6px; padding: 16px;">
          <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0056b3; padding-bottom: 8px; margin-bottom: 12px;">
            <h5 style="font-weight: 700; color: #004085; margin: 0; font-size: 15px;">
              <i class="fa fa-file-text-o text-primary"></i> Estimated Tariff Breakdown
            </h5>
            <span class="label label-primary" id="calc_res_route_badge" style="font-size: 11px; padding: 4px 8px;">Domestic Route</span>
          </div>

          <!-- Route Info Banner -->
          <div id="calc_res_route_banner" style="background-color: #e9f4ff; border-radius: 4px; padding: 8px 12px; margin-bottom: 12px; font-size: 12px; color: #004085; display: flex; justify-content: space-between;">
            <div>
              <strong>Origin:</strong> <span id="calc_res_source_loc">-</span>
            </div>
            <div>
              <i class="fa fa-arrow-right text-muted" style="margin: 0 6px;"></i>
            </div>
            <div>
              <strong>Destination:</strong> <span id="calc_res_dest_loc">-</span>
            </div>
          </div>

          <table class="table table-condensed table-striped" style="margin-bottom: 0; background: transparent; font-size: 13px;">
            <tbody>
              <tr>
                <td style="width: 55%;"><strong>Matched Weight Slab:</strong></td>
                <td class="text-right" id="calc_res_slab" style="font-weight: 600;">-</td>
              </tr>
              <tr>
                <td><strong>Applied Classification:</strong></td>
                <td class="text-right" id="calc_res_class">-</td>
              </tr>
              <tr>
                <td><strong>Base Freight (Min Weight &amp; Charge):</strong></td>
                <td class="text-right">
                  <span id="calc_res_minwgt" class="text-muted" style="margin-right: 5px;">-</span>
                  <strong id="calc_res_base">-</strong>
                </td>
              </tr>
              <tr>
                <td><strong>Additional Weight &amp; Steps:</strong></td>
                <td class="text-right" id="calc_res_steps">-</td>
              </tr>
              <tr>
                <td><strong>Additional Weight Charges:</strong></td>
                <td class="text-right"><strong id="calc_res_extra">-</strong></td>
              </tr>
              <tr style="background-color: #eef6fd; font-weight: 600;">
                <td><strong>Freight Subtotal:</strong></td>
                <td class="text-right" id="calc_res_freight_subtotal">&#8377;0.00</td>
              </tr>
              <tr>
                <td>
                  <strong>Product Category Surcharge:</strong><br>
                  <small id="calc_res_cat_name" class="text-muted">Standard</small>
                </td>
                <td class="text-right" style="vertical-align: middle;">
                  <strong id="calc_res_cat_surcharge" class="text-green">+&#8377;0.00</strong>
                </td>
              </tr>
              <tr style="font-size: 18px; border-top: 2px solid #0056b3; background-color: #d1ecf1; color: #0c5460;">
                <td style="padding: 10px 8px;"><strong>Total Estimated Tariff:</strong></td>
                <td class="text-right" style="padding: 10px 8px;"><strong id="calc_res_total">&#8377;0.00</strong></td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
      <div class="modal-footer" style="background-color: #f9f9f9; padding: 12px 20px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  </div>
</div>

<!-- Scripts for Edit Modal & Calculator -->
<script>
$(document).ready(function() {
  var csrfTokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var csrfTokenHash = '<?php echo $this->security->get_csrf_hash(); ?>';

  // Edit Button Click Handler
  $('.edit-rate-btn').on('click', function() {
    var id = $(this).data('id');
    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
      url: "<?php echo site_url('domestic/rates/get/'); ?>" + id,
      type: "GET",
      dataType: "json",
      success: function(res) {
        btn.prop('disabled', false).html('<i class="fa fa-pencil-square-o"></i>');
        if (res.status === 'success') {
          var d = res.data;
          $('#edit_domestic_rate_id').val(d.domestic_rate_id);
          
          // Courier Type
          if (d.c_type === 'Surface') {
            $('#edit_ctype_surface').prop('checked', true);
          } else {
            $('#edit_ctype_air').prop('checked', true);
          }

          // Min & Max Weight
          $('#edit_from_weight').val(parseFloat(d.from_weight).toFixed(3));
          $('#edit_to_weight').val(parseFloat(d.to_weight).toFixed(3));

          // Destination flags
          $('#edit_flg_region').prop('checked', d.flg_region == '1');
          $('#edit_flg_state').prop('checked', d.flg_state == '1');
          $('#edit_flg_city').prop('checked', d.flg_city == '1');
          $('#edit_flg_metro').prop('checked', d.flg_metro == '1');

          // Initial & Additional charges
          $('#edit_min_weight').val(parseFloat(d.min_weight).toFixed(3));
          $('#edit_min_charges').val(parseFloat(d.min_charges).toFixed(2));
          $('#edit_addt_weight').val(parseFloat(d.addt_weight).toFixed(3));
          $('#edit_addt_charges').val(parseFloat(d.addt_charges).toFixed(2));

          // Status radio
          if (d.status === 'InActive') {
            $('#edit_status_inactive').prop('checked', true);
          } else {
            $('#edit_status_active').prop('checked', true);
          }

          // Form action
          $('#editRateForm').attr('action', "<?php echo site_url('domestic/rates/edit/'); ?>" + d.domestic_rate_id);
          $('#editRateModal').modal('show');
        } else {
          alert(res.message || 'Error loading rate details.');
        }
      },
      error: function() {
        btn.prop('disabled', false).html('<i class="fa fa-pencil-square-o"></i>');
        alert('Server communication error. Please try again.');
      }
    });
  });

  // Live Pincode lookup helper
  function checkPincodeLookup(inputElem, feedbackElem, callback) {
    var pin = $(inputElem).val().trim();
    if (pin.length === 6 && /^\d{6}$/.test(pin)) {
      $(feedbackElem).html('<i class="fa fa-spinner fa-spin text-muted"></i> Checking pincode...');
      $.ajax({
        url: "<?php echo site_url('domestic/pincodes/lookup'); ?>",
        type: "GET",
        data: { pincode: pin },
        dataType: "json",
        success: function(res) {
          if (res.status === 'success' && res.data) {
            var d = res.data;
            var loc = d.district_name + ', ' + d.state_name;
            var extra = [];
            if (d.zone) extra.push('Zone ' + d.zone);
            if (d.metro === 'Y') extra.push('Metro');
            var fullText = loc + (extra.length ? ' (' + extra.join(', ') + ')' : '');
            $(feedbackElem).html('<span class="text-success"><i class="fa fa-check-circle"></i> ' + fullText + '</span>');
            $(inputElem).data('pincodeData', d);
          } else {
            $(feedbackElem).html('<span class="text-warning"><i class="fa fa-info-circle"></i> Pincode not in master (Defaulting to National)</span>');
            $(inputElem).removeData('pincodeData');
          }
          if (typeof callback === 'function') callback();
        },
        error: function() {
          $(feedbackElem).html('<span class="text-muted"><i class="fa fa-question-circle"></i> Lookup offline</span>');
          $(inputElem).removeData('pincodeData');
          if (typeof callback === 'function') callback();
        }
      });
    } else if (pin.length === 0) {
      $(feedbackElem).html('');
      $(inputElem).removeData('pincodeData');
      if (typeof callback === 'function') callback();
    } else {
      $(feedbackElem).html('<span class="text-muted">Enter 6-digit valid pincode</span>');
      $(inputElem).removeData('pincodeData');
    }
  }

  // Auto-detect destination classification based on Source & Destination Pincodes
  function autoDetectClassification() {
    var srcData = $('#calc_source_pincode').data('pincodeData');
    var dstData = $('#calc_dest_pincode').data('pincodeData');

    if (srcData && dstData) {
      var srcDist = (srcData.district_name || '').toUpperCase().trim();
      var dstDist = (dstData.district_name || '').toUpperCase().trim();
      var srcState = (srcData.state_name || '').toLowerCase().trim();
      var dstState = (dstData.state_name || '').toLowerCase().trim();
      var srcZone = (srcData.zone || '').toUpperCase().trim();
      var dstZone = (dstData.zone || '').toUpperCase().trim();
      var srcMetro = (srcData.metro || '').toUpperCase().trim();
      var dstMetro = (dstData.metro || '').toUpperCase().trim();

      var detected = 'national';
      var reason = 'National / Rest of India';

      if (srcDist && dstDist && srcDist === dstDist) {
        detected = 'city';
        reason = 'Same City / Local';
      } else if (srcState && dstState && srcState === dstState) {
        detected = 'state';
        reason = 'Same State';
      } else if (srcZone && dstZone && srcZone === dstZone) {
        detected = 'region';
        reason = 'Same Region (Zone ' + srcZone + ')';
      } else if (srcMetro === 'Y' && dstMetro === 'Y') {
        detected = 'metro';
        reason = 'Metro to Metro';
      }

      $('#calc_dest_type').val(detected);
      $('#autoDetectBadge').html('<span class="badge bg-green" style="font-size: 10px; padding: 3px 6px;"><i class="fa fa-magic"></i> Auto: ' + reason + '</span>').show();
    } else {
      $('#autoDetectBadge').hide();
    }
  }

  $('#calc_source_pincode').on('input blur change', function() {
    checkPincodeLookup('#calc_source_pincode', '#source_pincode_feedback', autoDetectClassification);
  });

  $('#calc_dest_pincode').on('input blur change', function() {
    checkPincodeLookup('#calc_dest_pincode', '#dest_pincode_feedback', autoDetectClassification);
  });

  // Domestic Rate Calculator AJAX
  $('#btnRunCalc').on('click', function() {
    var c_type         = $('#calc_ctype').val();
    var weight         = $('#calc_weight').val();
    var dest_type      = $('#calc_dest_type').val();
    var source_pincode = $('#calc_source_pincode').val().trim();
    var dest_pincode   = $('#calc_dest_pincode').val().trim();
    var category_id    = $('#calc_category_id').val();

    if (!weight || parseFloat(weight) <= 0) {
      alert('Please enter a valid weight in Kg.');
      $('#calc_weight').focus();
      return;
    }

    var postData = {};
    postData[csrfTokenName] = csrfTokenHash;
    postData.c_type         = c_type;
    postData.weight         = weight;
    postData.dest_type      = dest_type;
    postData.source_pincode = source_pincode;
    postData.dest_pincode   = dest_pincode;
    postData.category_id    = category_id;

    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Calculating Tariff...');

    $.ajax({
      url: "<?php echo site_url('domestic/rates/calculate'); ?>",
      type: "POST",
      data: postData,
      dataType: "json",
      success: function(res) {
        btn.prop('disabled', false).html('<i class="fa fa-calculator"></i> Calculate Freight Charges');
        if (res.status === 'success') {
          var d = res.data;
          
          // Route banner
          $('#calc_res_source_loc').text(d.source_location || 'Origin');
          $('#calc_res_dest_loc').text(d.dest_location || 'Destination');
          $('#calc_res_route_badge').text(d.dest_type_label || 'Domestic Tariff');

          // Breakdown details
          $('#calc_res_slab').text(d.c_type + ' (' + parseFloat(d.from_weight).toFixed(3) + ' - ' + parseFloat(d.to_weight).toFixed(3) + ' Kg)');
          $('#calc_res_class').html('<span class="label label-info" style="font-size: 12px;">' + (d.dest_type_label || d.dest_type) + '</span>');
          $('#calc_res_minwgt').text('(' + parseFloat(d.min_weight).toFixed(3) + ' Kg Min)');
          $('#calc_res_base').html('&#8377;' + parseFloat(d.min_charges).toFixed(2));

          if (parseFloat(d.excess_weight) > 0) {
            $('#calc_res_steps').text(parseFloat(d.excess_weight).toFixed(3) + ' Kg (' + d.extra_steps + ' step' + (d.extra_steps > 1 ? 's' : '') + ' of ' + parseFloat(d.addt_weight).toFixed(3) + ' Kg @ &#8377;' + parseFloat(d.addt_charges).toFixed(2) + ')');
            $('#calc_res_extra').html('&#8377;' + parseFloat(d.extra_charge).toFixed(2));
          } else {
            $('#calc_res_steps').text('None (Within min weight)');
            $('#calc_res_extra').html('&#8377;0.00');
          }

          // Subtotal
          $('#calc_res_freight_subtotal').html('&#8377;' + parseFloat(d.freight_subtotal).toFixed(2));

          // Category Surcharge
          $('#calc_res_cat_name').text(d.category_name || 'Standard');
          if (parseFloat(d.category_surcharge) > 0) {
            $('#calc_res_cat_surcharge').html('+&#8377;' + parseFloat(d.category_surcharge).toFixed(2)).removeClass('text-muted').addClass('text-green');
          } else {
            $('#calc_res_cat_surcharge').html('&#8377;0.00 (No Surcharge)').removeClass('text-green').addClass('text-muted');
          }

          // Total
          $('#calc_res_total').html('&#8377;' + parseFloat(d.total_tariff).toFixed(2));
          
          $('#calcResultBox').slideDown();
        } else {
          $('#calcResultBox').hide();
          alert(res.message || 'Unable to calculate rate.');
        }
      },
      error: function() {
        btn.prop('disabled', false).html('<i class="fa fa-calculator"></i> Calculate Freight Charges');
        alert('Server error while calculating rate.');
      }
    });
  });

});
</script>
