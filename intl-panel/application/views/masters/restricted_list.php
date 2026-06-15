<div class="row">
  <div class="col-xs-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-warning text-yellow"></i> Country-Specific Prohibited & Restricted Items Directory</h3>
      </div>
      <div class="box-body table-responsive">
        <p class="text-muted">Below is a checklist of prohibited international cargo commodities grouped by destination countries. Entering any of these items during shipment booking will trigger instant alerts to shipping staff and exporters.</p>
        
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style="width: 200px;">Country</th>
              <th style="width: 100px;">ISO Code</th>
              <th>Prohibited & Restricted Items</th>
              <th style="width: 150px;">Customs Invoice Required</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($countries as $c): ?>
              <tr>
                <td><strong><?php echo $c->country_name; ?></strong></td>
                <td><span class="label label-default"><?php echo $c->iso_code; ?></span></td>
                <td>
                  <?php if(empty($c->restricted_items)): ?>
                    <span class="text-success"><i class="fa fa-check-circle"></i> No specific restrictions cataloged.</span>
                  <?php else: ?>
                    <?php 
                      $items = explode(',', $c->restricted_items);
                      foreach($items as $it):
                    ?>
                      <span class="label label-danger" style="display: inline-block; margin: 3px; font-size: 11px;">
                        <i class="fa fa-ban"></i> <?php echo trim($it); ?>
                      </span>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($c->customs_required == 1): ?>
                    <span class="label label-warning"><i class="fa fa-shield"></i> Yes</span>
                  <?php else: ?>
                    <span class="text-muted">No</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
