<div class="row no-print">
  <?php if($this->session->userdata('role_id') != 4): ?>
    <!-- Staff Customer selector dropdown -->
    <div class="col-xs-12">
      <div class="box box-solid">
        <div class="box-body">
          <form action="<?php echo site_url('customer/statement'); ?>" method="GET" class="form-inline">
            <div class="form-group">
              <label style="margin-right: 10px;">Select Customer Profile: </label>
              <select name="customer_id" class="form-control" onchange="this.form.submit()">
                <?php foreach($customers as $c): ?>
                  <option value="<?php echo $c->id; ?>" <?php echo ($customer && $customer->id == $c->id) ? 'selected' : ''; ?>>
                    <?php echo $c->name; ?> <?php echo $c->company_name ? '('.$c->company_name.')' : ''; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary btn-flat" style="margin-left: 10px;">Get Ledger Statement</button>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary" id="printableStatementArea">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Exporter Account Statement Ledger</h3>
        <div class="pull-right no-print">
          <button class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Print Statement</button>
          <button class="btn btn-success btn-sm" id="exportCsvBtn"><i class="fa fa-file-excel-o"></i> Export CSV</button>
        </div>
      </div>
      
      <div class="box-body">
        <!-- Billing headers -->
        <div class="row" style="margin-bottom: 20px;">
          <div class="col-xs-6">
            <h4><strong><?php echo defined('COMPANY_NAME') && COMPANY_NAME ? htmlspecialchars(COMPANY_NAME) : 'CourierSyndicate International'; ?></strong></h4>
            <p>101 Logistics Boulevard, Sector 5<br>
               <?php echo defined('COMPANY_ADDRESS') && COMPANY_ADDRESS ? nl2br(htmlspecialchars(COMPANY_ADDRESS)) : 'HQ Origin branch Office'; ?><br>
               Email: <?php echo defined('COMPANY_EMAIL') && COMPANY_EMAIL ? htmlspecialchars(COMPANY_EMAIL) : 'billing@couriersyn.com'; ?></p>
          </div>
          <div class="col-xs-6 text-right">
            <h4><strong>STATEMENT OF ACCOUNT</strong></h4>
            <p>Date: <?php echo date('d M Y'); ?></p>
            <p>Account Reference: <code>CUST-<?php echo str_pad($customer->id, 5, '0', STR_PAD_LEFT); ?></code></p>
          </div>
        </div>

        <div class="row well well-sm" style="margin-bottom: 25px; border-radius: 8px;">
          <div class="col-sm-6">
            <h5 style="font-weight: 700; margin-top: 0; text-transform: uppercase;">Exporter Details:</h5>
            <strong><?php echo $customer->name; ?></strong><br>
            <?php if($customer->company_name): ?>Company: <?php echo $customer->company_name; ?><br><?php endif; ?>
            Address: <?php echo $customer->address; ?>, <?php echo $customer->city; ?>, <?php echo $customer->state; ?>, <?php echo $customer->country_name; ?><br>
            Mobile: <?php echo $customer->mobile; ?> | Email: <?php echo $customer->email; ?>
          </div>
          <div class="col-sm-6 text-right">
            <h5 style="font-weight: 700; margin-top: 0; text-transform: uppercase;">Account Balances:</h5>
            <h3 style="margin: 0; font-weight: 700;" class="<?php echo ($customer->outstanding_balance > 0) ? 'text-danger' : 'text-success'; ?>">
              Outstanding: ₹<?php echo number_format($customer->outstanding_balance, 2); ?>
            </h3>
            <p style="margin-top: 10px; margin-bottom: 0;">Credit Limit: ₹<?php echo number_format($customer->credit_limit, 2); ?> | Credit Days: <?php echo $customer->credit_days; ?> Days</p>
          </div>
        </div>

        <!-- Statement Table -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="statementTable">
            <thead>
              <tr class="bg-gray">
                <th style="width: 120px;">Date</th>
                <th style="width: 150px;">Entry Type</th>
                <th style="width: 150px;">Reference ID</th>
                <th>Description</th>
                <th style="width: 120px;" class="text-right">Debit (Dr)</th>
                <th style="width: 120px;" class="text-right">Credit (Cr)</th>
                <th style="width: 150px;" class="text-right">Running Balance</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>-</td>
                <td><strong>Opening Balance</strong></td>
                <td>-</td>
                <td>Previous balance brought forward</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right"><strong>₹0.00</strong></td>
              </tr>
              <?php foreach($statement as $row): ?>
                <tr>
                  <td><?php echo date('d M Y', strtotime($row->entry_date)); ?></td>
                  <td>
                    <?php if($row->entry_type == 'Invoice'): ?>
                      <span class="label label-danger">Invoice Debit</span>
                    <?php else: ?>
                      <span class="label label-success">Payment Received</span>
                    <?php endif; ?>
                  </td>
                  <td><code><?php echo $row->reference_id; ?></code></td>
                  <td>
                    <?php 
                      if($row->entry_type == 'Invoice') {
                        echo "Shipping Charges AWB consignment debit";
                      } else {
                        echo "Payment transaction receipt credit";
                      }
                    ?>
                  </td>
                  <td class="text-right text-danger"><?php echo $row->debit_amount > 0 ? '₹' . number_format($row->debit_amount, 2) : '-'; ?></td>
                  <td class="text-right text-success"><?php echo $row->credit_amount > 0 ? '₹' . number_format($row->credit_amount, 2) : '-'; ?></td>
                  <td class="text-right"><strong>₹<?php echo number_format($row->running_balance, 2); ?></strong></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statement Export JavaScript -->
<script>
  $(document).ready(function() {
    $('#exportCsvBtn').click(function() {
      var csv = [];
      var rows = document.querySelectorAll("#statementTable tr");
      
      for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) {
          var cleanText = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s)/gm, " ").trim();
          cleanText = cleanText.replace(/"/g, '""');
          row.push('"' + cleanText + '"');
        }
        
        csv.push(row.join(","));
      }

      var csvString = csv.join("\n");
      var filename = 'Statement_CUST-<?php echo str_pad($customer->id, 5, '0', STR_PAD_LEFT); ?>_' + new Date().toISOString().slice(0,10) + '.csv';
      var link = document.createElement("a");
      var mimeType = 'text/csv;charset=utf-8';
      
      if (navigator.msSaveBlob) { // IE10
        navigator.msSaveBlob(new Blob([csvString], {type: mimeType}), filename);
      } else if (URL && 'download' in link) {
        link.href = 'data:' + mimeType + ',' + encodeURIComponent(csvString);
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      } else {
        location.href = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csvString);
      }
    });
  });
</script>

<style>
  @media print {
    .no-print {
      display: none !important;
    }
    .content-wrapper {
      background: white !important;
      margin-left: 0 !important;
      padding: 0 !important;
    }
    .main-sidebar, .main-header, .main-footer {
      display: none !important;
    }
    #printableStatementArea {
      border: none !important;
      box-shadow: none !important;
    }
  }
</style>
