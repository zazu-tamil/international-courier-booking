<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-line-chart"></i> Tracking Idle Report</h3>
      </div>
      
      <div class="box-body">
        <form method="POST" action="<?php echo site_url('reports/tracking_idle'); ?>" class="form-inline" style="margin-bottom: 20px; background: #f9fafc; padding: 15px; border-radius: 4px; border: 1px solid #f4f4f4;">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <div class="form-group">
                <label style="margin-right: 10px;">From Date:</label>
                <input type="date" name="from_date" class="form-control" value="<?php echo htmlspecialchars($from_date); ?>" required>
            </div>
            <div class="form-group" style="margin-left: 20px;">
                <label style="margin-right: 10px;">To Date:</label>
                <input type="date" name="to_date" class="form-control" value="<?php echo htmlspecialchars($to_date); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-left: 20px;"><i class="fa fa-filter"></i> Generate Report</button>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover" id="reportTable">
            <thead>
              <tr class="bg-gray">
                <th>AWB Number</th>
                <th>Booking Date</th>
                <th>Destination</th>
                <th>Customer</th>
                <th>Shipment Status</th>
                <th>Latest Tracking Update</th>
                <th>Last Update Date</th>
                <th class="text-center">Days Idle</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($results)): ?>
                <?php foreach($results as $row): ?>
                  <tr>
                    <td>
                        <a href="<?php echo site_url('shipments/view/' . $row['shipment_id']); ?>" target="_blank">
                            <strong><?php echo htmlspecialchars($row['awb_number']); ?></strong>
                        </a>
                    </td>
                    <td><?php echo date('d-M-Y', strtotime($row['booking_date'])); ?></td>
                    <td>
                        <?php if(!empty($row['destination_country'])): ?>
                            <i class="fa fa-flag-o"></i> <?php echo htmlspecialchars($row['destination_country']); ?>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['customer_name']); ?>
                        <?php if(!empty($row['company_name'])): ?>
                            <br><small class="text-muted"><i class="fa fa-building-o"></i> <?php echo htmlspecialchars($row['company_name']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                        $label = 'info';
                        if ($row['master_status'] == 'Delivered') $label = 'success';
                        elseif ($row['master_status'] == 'Cancelled') $label = 'danger';
                        elseif ($row['master_status'] == 'Booked') $label = 'primary';
                        ?>
                        <span class="label label-<?php echo $label; ?>">
                            <?php echo htmlspecialchars($row['master_status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if(!empty($row['tracking_status'])): ?>
                            <strong><?php echo htmlspecialchars($row['tracking_status']); ?></strong>
                            <?php if(!empty($row['current_location'])): ?>
                                <br><small class="text-muted"><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($row['current_location']); ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted"><em>No tracking updates</em></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo !empty($row['last_update_date']) ? date('d-M-Y H:i', strtotime($row['last_update_date'])) : '<span class="text-muted">-</span>'; ?>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <?php 
                        if ($row['master_status'] == 'Delivered' || $row['master_status'] == 'Cancelled') {
                            echo '<span class="text-success"><i class="fa fa-check-circle"></i> Closed</span>';
                        } else {
                            if ($row['idle_days'] !== null && $row['idle_days'] !== '') {
                                $idle = (int)$row['idle_days'];
                                if ($idle > 3) {
                                    echo "<span class='badge bg-red' style='font-size: 14px;'>{$idle} days</span>";
                                } elseif ($idle > 1) {
                                    echo "<span class='badge bg-yellow' style='font-size: 14px;'>{$idle} days</span>";
                                } else {
                                    echo "<span class='badge bg-green' style='font-size: 14px;'>{$idle} days</span>";
                                }
                            } else {
                                echo '<span class="text-muted">-</span>';
                            }
                        }
                        ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center" style="padding: 30px;">
                    <i class="fa fa-inbox fa-3x text-muted" style="margin-bottom: 10px;"></i>
                    <p class="text-muted" style="font-size: 16px;">No shipments found for the selected date range.</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
