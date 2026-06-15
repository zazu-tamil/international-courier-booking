<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-envelope-o"></i> Notification Dispatch Logs</h3>
      </div>
      
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped dataTable" id="notificationLogsTable">
          <thead>
            <tr>
              <th style="width: 50px;">ID</th>
              <th>Recipient</th>
              <th style="width: 100px;">Channel</th>
              <th>Subject</th>
              <th>Message Preview</th>
              <th style="width: 80px;">Status</th>
              <th>Timestamp</th>
              <th>Triggered By</th>
              <th style="width: 60px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($logs as $log): ?>
              <tr>
                <td><strong>#<?php echo $log->id; ?></strong></td>
                <td><?php echo htmlspecialchars($log->recipient_contact); ?></td>
                <td>
                  <?php if($log->type == 'Email'): ?>
                    <span class="label label-info"><i class="fa fa-envelope"></i> Email</span>
                  <?php elseif($log->type == 'SMS'): ?>
                    <span class="label label-warning"><i class="fa fa-commenting"></i> SMS</span>
                  <?php elseif($log->type == 'WhatsApp'): ?>
                    <span class="label label-success" style="background-color: #25d366 !important;"><i class="fa fa-whatsapp"></i> WhatsApp</span>
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($log->subject ? $log->subject : 'N/A'); ?></td>
                <td>
                  <?php 
                    $preview = strip_tags($log->message);
                    if (strlen($preview) > 80) {
                      echo htmlspecialchars(substr($preview, 0, 80)) . '...';
                    } else {
                      echo htmlspecialchars($preview);
                    }
                  ?>
                </td>
                <td>
                  <?php if($log->status == 'Sent'): ?>
                    <span class="label label-success"><i class="fa fa-check"></i> Sent</span>
                  <?php elseif($log->status == 'Failed'): ?>
                    <span class="label label-danger"><i class="fa fa-times"></i> Failed</span>
                  <?php else: ?>
                    <span class="label label-warning"><i class="fa fa-clock-o"></i> <?php echo htmlspecialchars($log->status); ?></span>
                  <?php endif; ?>
                </td>
                <td><?php echo date('Y-m-d H:i:s', strtotime($log->sent_at)); ?></td>
                <td><?php echo htmlspecialchars($log->username ? $log->username : 'System'); ?></td>
                <td>
                  <button class="btn btn-default btn-xs view-log-btn"
                          data-id="<?php echo $log->id; ?>"
                          data-recipient="<?php echo htmlspecialchars($log->recipient_contact); ?>"
                          data-type="<?php echo htmlspecialchars($log->type); ?>"
                          data-subject="<?php echo htmlspecialchars($log->subject ? $log->subject : 'N/A'); ?>"
                          data-status="<?php echo htmlspecialchars($log->status); ?>"
                          data-sent="<?php echo date('Y-m-d H:i:s', strtotime($log->sent_at)); ?>"
                          data-sender="<?php echo htmlspecialchars($log->username ? $log->username : 'System'); ?>"
                          data-message="<?php echo htmlspecialchars($log->message); ?>"
                          data-toggle="modal" data-target="#viewMessageModal">
                    <i class="fa fa-eye"></i> View
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" role="dialog" aria-labelledby="viewMessageModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 8px;">
      <div class="modal-header" style="border-top-left-radius: 8px; border-top-right-radius: 8px; background-color: #f7f9fa; border-bottom: 1px solid #eee;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="viewMessageModalLabel" style="font-weight: 700; color: #333;"><i class="fa fa-info-circle"></i> Notification Dispatch Details</h4>
      </div>
      <div class="modal-body" style="padding: 25px;">
        <div class="row" style="margin-bottom: 20px;">
          <div class="col-md-6">
            <table class="table table-condensed table-bordered">
              <tr>
                <th style="width: 120px; background-color: #f9f9f9;">Log ID</th>
                <td id="modal-log-id" style="font-weight: bold;"></td>
              </tr>
              <tr>
                <th style="background-color: #f9f9f9;">Recipient</th>
                <td id="modal-recipient"></td>
              </tr>
              <tr>
                <th style="background-color: #f9f9f9;">Channel</th>
                <td id="modal-channel"></td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-condensed table-bordered">
              <tr>
                <th style="width: 120px; background-color: #f9f9f9;">Triggered By</th>
                <td id="modal-sender"></td>
              </tr>
              <tr>
                <th style="background-color: #f9f9f9;">Timestamp</th>
                <td id="modal-timestamp"></td>
              </tr>
              <tr>
                <th style="background-color: #f9f9f9;">Status</th>
                <td id="modal-status"></td>
              </tr>
            </table>
          </div>
        </div>

        <div class="form-group">
          <label style="font-size: 14px; font-weight: 600; color: #555;">Subject</label>
          <div id="modal-subject" style="padding: 10px; background-color: #f5f5f5; border: 1px solid #ddd; border-radius: 4px; font-weight: bold;"></div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label style="font-size: 14px; font-weight: 600; color: #555;">Message Content & Troubleshooting Logs</label>
          <pre id="modal-message-body" style="padding: 15px; background-color: #2c3e50; color: #ecf0f1; border: none; border-radius: 4px; max-height: 400px; overflow-y: auto; white-space: pre-wrap; font-family: Consolas, Monaco, monospace; font-size: 13px; line-height: 1.5;"></pre>
        </div>
      </div>
      <div class="modal-footer" style="background-color: #f7f9fa; border-top: 1px solid #eee; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.view-log-btn').click(function() {
      var id = $(this).data('id');
      var recipient = $(this).data('recipient');
      var type = $(this).data('type');
      var subject = $(this).data('subject');
      var status = $(this).data('status');
      var sent = $(this).data('sent');
      var sender = $(this).data('sender');
      var message = $(this).data('message');

      // Populate elements
      $('#modal-log-id').text('#' + id);
      $('#modal-recipient').text(recipient);
      
      // Channel Badge
      var channelHtml = '';
      if(type === 'Email') {
        channelHtml = '<span class="label label-info"><i class="fa fa-envelope"></i> Email</span>';
      } else if(type === 'SMS') {
        channelHtml = '<span class="label label-warning"><i class="fa fa-commenting"></i> SMS</span>';
      } else if(type === 'WhatsApp') {
        channelHtml = '<span class="label label-success" style="background-color: #25d366 !important;"><i class="fa fa-whatsapp"></i> WhatsApp</span>';
      }
      $('#modal-channel').html(channelHtml);

      // Status Badge
      var statusHtml = '';
      if(status === 'Sent') {
        statusHtml = '<span class="label label-success"><i class="fa fa-check"></i> Sent</span>';
      } else if(status === 'Failed') {
        statusHtml = '<span class="label label-danger"><i class="fa fa-times"></i> Failed</span>';
      } else {
        statusHtml = '<span class="label label-warning">' + status + '</span>';
      }
      $('#modal-status').html(statusHtml);

      $('#modal-timestamp').text(sent);
      $('#modal-sender').text(sender);
      $('#modal-subject').text(subject);
      $('#modal-message-body').text(message);
    });
  });
</script>
