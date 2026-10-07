<style>
  .chat-box-container {
    height: calc(100vh - 170px);
    min-height: 600px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    display: flex;
    overflow: hidden;
    border: 1px solid #e1e8ed;
  }
  
  /* Sidebar Contacts List */
  .chat-sidebar {
    width: 350px;
    min-width: 300px;
    border-right: 1px solid #edf2f7;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  
  .chat-sidebar-header {
    padding: 15px;
    border-bottom: 1px solid #edf2f7;
    background: #ffffff;
  }
  
  .chat-filter-pills {
    display: flex;
    gap: 5px;
    margin-top: 10px;
    overflow-x: auto;
    padding-bottom: 3px;
  }
  
  .chat-filter-btn {
    border-radius: 15px;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
  }
  
  .chat-filter-btn.active, .chat-filter-btn:hover {
    background: #3c8dbc;
    color: #fff;
    border-color: #3c8dbc;
  }

  .chat-contacts-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 0;
    margin: 0;
    list-style: none;
  }

  .chat-contact-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    transition: background 0.15s ease-in-out;
    position: relative;
    text-decoration: none !important;
    color: inherit !important;
  }

  .chat-contact-item:hover {
    background: #f1f5f9;
  }

  .chat-contact-item.active {
    background: #e6f2fb;
    border-left: 4px solid #3c8dbc;
  }

  .chat-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #fff;
    font-size: 16px;
    position: relative;
    flex-shrink: 0;
    margin-right: 12px;
  }

  .chat-avatar.role-admin { background: linear-gradient(135deg, #dd4b39, #c23321); }
  .chat-avatar.role-branch { background: linear-gradient(135deg, #3c8dbc, #286090); }
  .chat-avatar.role-franchise { background: linear-gradient(135deg, #f39c12, #d58512); }
  .chat-avatar.role-broadcast { background: linear-gradient(135deg, #00a65a, #008d4c); }

  .online-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    position: absolute;
    bottom: -1px;
    right: -1px;
    background-color: #94a3b8;
  }

  .online-indicator.is-online {
    background-color: #22c55e;
    box-shadow: 0 0 0 1px #fff;
  }

  .chat-contact-info {
    flex: 1;
    min-width: 0;
  }

  .chat-contact-top {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 3px;
  }

  .chat-contact-name {
    font-weight: 700;
    font-size: 14px;
    color: #1e293b;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .chat-contact-time {
    font-size: 11px;
    color: #94a3b8;
    margin-left: 5px;
    flex-shrink: 0;
  }

  .chat-contact-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .chat-contact-snippet {
    font-size: 12px;
    color: #64748b;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 190px;
  }

  .badge-unread {
    background-color: #ef4444;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    border-radius: 10px;
    padding: 2px 7px;
    flex-shrink: 0;
  }

  /* Main Conversation Area */
  .chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #ffffff;
    min-width: 0;
  }

  .chat-main-header {
    padding: 12px 20px;
    border-bottom: 1px solid #edf2f7;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }

  .chat-active-user {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .chat-messages-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  /* Message Bubbles */
  .message-wrapper {
    display: flex;
    margin-bottom: 5px;
    max-width: 80%;
  }

  .message-wrapper.mine {
    margin-left: auto;
    flex-direction: row-reverse;
  }

  .message-wrapper.theirs {
    margin-right: auto;
  }

  .message-bubble {
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 13.5px;
    line-height: 1.5;
    position: relative;
    word-wrap: break-word;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  }

  .message-wrapper.mine .message-bubble {
    background: #3c8dbc;
    color: #ffffff;
    border-bottom-right-radius: 2px;
  }

  .message-wrapper.theirs .message-bubble {
    background: #ffffff;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 2px;
  }

  .message-meta {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 10.5px;
    margin-top: 4px;
  }

  .message-wrapper.mine .message-meta {
    color: rgba(255,255,255,0.85);
    justify-content: flex-end;
  }

  .message-wrapper.theirs .message-meta {
    color: #94a3b8;
  }

  .sender-tag {
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 2px;
    display: block;
  }

  .sender-tag.role-admin { color: #dd4b39; }
  .sender-tag.role-branch { color: #3c8dbc; }
  .sender-tag.role-franchise { color: #d58512; }

  /* Message Attachments */
  .chat-attachment-img {
    max-width: 260px;
    max-height: 200px;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 6px;
    display: block;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s;
  }

  .chat-attachment-img:hover {
    transform: scale(1.02);
  }

  .chat-file-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(0,0,0,0.04);
    border-radius: 8px;
    padding: 8px 12px;
    margin-top: 6px;
    text-decoration: none !important;
  }

  .message-wrapper.mine .chat-file-card {
    background: rgba(255,255,255,0.18);
    color: #fff !important;
  }

  .message-wrapper.theirs .chat-file-card {
    background: #f1f5f9;
    color: #1e293b !important;
  }

  /* Date Divider */
  .chat-date-divider {
    text-align: center;
    margin: 15px 0 10px 0;
    position: relative;
  }

  .chat-date-divider span {
    background: #e2e8f0;
    color: #64748b;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 12px;
    border-radius: 12px;
  }

  /* Composer Toolbar */
  .chat-composer {
    padding: 12px 18px;
    background: #ffffff;
    border-top: 1px solid #edf2f7;
  }

  .attachment-preview-bar {
    display: none;
    align-items: center;
    justify-content: space-between;
    background: #f1f5f9;
    padding: 6px 12px;
    border-radius: 6px;
    margin-bottom: 8px;
    font-size: 12px;
  }

  .chat-input-row {
    display: flex;
    gap: 8px;
    align-items: flex-end;
  }

  .chat-textarea {
    flex: 1;
    resize: none;
    border: 1px solid #cbd5e1;
    border-radius: 20px;
    padding: 10px 16px;
    font-size: 13.5px;
    outline: none;
    max-height: 100px;
    line-height: 1.4;
    transition: border-color 0.2s;
  }

  .chat-textarea:focus {
    border-color: #3c8dbc;
    box-shadow: 0 0 0 2px rgba(60,141,188,0.15);
  }

  .btn-attach, .btn-send {
    border-radius: 50%;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    padding: 0;
  }

  .btn-send {
    background: #3c8dbc;
    color: #fff;
    border: none;
    transition: all 0.2s;
  }

  .btn-send:hover {
    background: #286090;
    color: #fff;
    transform: translateY(-1px);
  }

  .btn-attach {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
  }

  .btn-attach:hover {
    background: #e2e8f0;
    color: #334155;
  }

  /* Empty state */
  .chat-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #94a3b8;
    text-align: center;
    padding: 30px;
  }
</style>

<div class="row">
  <div class="col-xs-12">
    <div class="chat-box-container">
      
      <!-- LEFT SIDEBAR: CONTACTS & CHANNELS -->
      <div class="chat-sidebar">
        <div class="chat-sidebar-header">
          <div class="input-group">
            <span class="input-group-addon" style="background:#fff; border-right:none;"><i class="fa fa-search text-muted"></i></span>
            <input type="text" id="searchContactsInput" class="form-control" placeholder="Search staff, branch, franchise..." style="border-left:none;">
          </div>
          
          <div class="chat-filter-pills">
            <button type="button" class="chat-filter-btn active" data-filter="all">All</button>
            <button type="button" class="chat-filter-btn" data-filter="admin">Admins</button>
            <button type="button" class="chat-filter-btn" data-filter="branch">Branches</button>
            <button type="button" class="chat-filter-btn" data-filter="franchise">Franchises</button>
          </div>
        </div>

        <ul class="chat-contacts-scroll" id="contactsList">
          <!-- Pinned Broadcast Channel -->
          <li class="chat-contact-item <?php echo ($active_contact_id == 0) ? 'active' : ''; ?>" data-contact-id="0" data-role="broadcast">
            <div class="chat-avatar role-broadcast">
              <i class="fa fa-bullhorn"></i>
              <span class="online-indicator is-online"></span>
            </div>
            <div class="chat-contact-info">
              <div class="chat-contact-top">
                <span class="chat-contact-name text-green"><i class="fa fa-users"></i> Team Broadcast</span>
                <span class="chat-contact-time">Public</span>
              </div>
              <div class="chat-contact-bottom">
                <span class="chat-contact-snippet">General staff discussions &amp; announcements</span>
              </div>
            </div>
          </li>

          <!-- Dynamic Direct Contacts -->
          <?php if(!empty($contacts)): ?>
            <?php foreach($contacts as $contact): ?>
              <?php 
                $role_class = ($contact->role_id == 1) ? 'admin' : (($contact->role_id == 2) ? 'branch' : 'franchise');
                $initials = strtoupper(substr($contact->username, 0, 2));
              ?>
              <li class="chat-contact-item <?php echo ($active_contact_id == $contact->id) ? 'active' : ''; ?>" 
                  data-contact-id="<?php echo $contact->id; ?>" 
                  data-role="<?php echo $role_class; ?>"
                  data-search="<?php echo strtolower($contact->username . ' ' . $contact->role_name . ' ' . $contact->org_badge); ?>">
                <div class="chat-avatar role-<?php echo $role_class; ?>">
                  <?php echo $initials; ?>
                  <span class="online-indicator <?php echo ($contact->is_online) ? 'is-online' : ''; ?>"></span>
                </div>
                <div class="chat-contact-info">
                  <div class="chat-contact-top">
                    <span class="chat-contact-name"><?php echo htmlspecialchars($contact->username); ?></span>
                    <span class="chat-contact-time"><?php echo !empty($contact->last_message_time) ? date('h:i A', strtotime($contact->last_message_time)) : ''; ?></span>
                  </div>
                  <div class="chat-contact-bottom">
                    <span class="chat-contact-snippet">
                      <span class="label label-<?php echo $contact->role_color; ?>" style="font-size: 9px; padding: 1px 4px;"><?php echo $contact->org_badge; ?></span>
                      <?php echo htmlspecialchars($contact->last_message); ?>
                    </span>
                    <?php if($contact->unread_count > 0): ?>
                      <span class="badge-unread"><?php echo $contact->unread_count; ?></span>
                    <?php endif; ?>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>

      <!-- RIGHT CHAT CONVERSATION AREA -->
      <div class="chat-main">
        <!-- Active Chat Header -->
        <div class="chat-main-header">
          <div class="chat-active-user">
            <?php if($active_contact_id == 0): ?>
              <div class="chat-avatar role-broadcast" style="width: 40px; height: 40px; font-size: 14px;">
                <i class="fa fa-bullhorn"></i>
              </div>
              <div>
                <h4 style="margin: 0; font-weight: 700; font-size: 16px; color: #1e293b;">
                  Team Announcements &amp; General Discussions
                </h4>
                <small class="text-muted"><i class="fa fa-users text-green"></i> Shared channel visible to all Admins, Branches, and Franchises</small>
              </div>
            <?php else: ?>
              <?php 
                $active_role_class = ($active_contact->role_id == 1) ? 'admin' : (($active_contact->role_id == 2) ? 'branch' : 'franchise');
                $active_initials = strtoupper(substr($active_contact->username, 0, 2));
              ?>
              <div class="chat-avatar role-<?php echo $active_role_class; ?>" style="width: 40px; height: 40px; font-size: 14px;">
                <?php echo $active_initials; ?>
                <span class="online-indicator <?php echo ($active_contact->is_online) ? 'is-online' : ''; ?>"></span>
              </div>
              <div>
                <h4 style="margin: 0; font-weight: 700; font-size: 16px; color: #1e293b;">
                  <?php echo htmlspecialchars($active_contact->username); ?>
                  <span class="label label-<?php echo $active_contact->role_color; ?>" style="font-size: 10px; margin-left: 5px;"><?php echo $active_contact->org_badge; ?></span>
                </h4>
                <small id="activeContactOnlineStatus" class="<?php echo ($active_contact->is_online) ? 'text-green' : 'text-muted'; ?>">
                  <i class="fa fa-circle" style="font-size: 9px;"></i> <?php echo ($active_contact->is_online) ? 'Online Now' : 'Offline'; ?>
                  &bull; <?php echo $active_contact->role_name; ?>
                </small>
              </div>
            <?php endif; ?>
          </div>

          <div style="display: flex; gap: 8px;">
            <button type="button" class="btn btn-default btn-sm" id="btnRefreshMessages" title="Refresh messages">
              <i class="fa fa-refresh"></i>
            </button>
          </div>
        </div>

        <!-- Messages Container -->
        <div class="chat-messages-body" id="chatMessagesContainer">
          <div class="text-center" style="padding: 20px;">
            <i class="fa fa-spinner fa-spin text-muted"></i> Loading conversation history...
          </div>
        </div>

        <!-- Composer / Input Bar -->
        <div class="chat-composer">
          <!-- Attachment selected chip -->
          <div class="attachment-preview-bar" id="attachmentPreviewBar">
            <span><i class="fa fa-paperclip text-blue"></i> <strong id="attachmentFileName"></strong> (<span id="attachmentFileSize"></span>)</span>
            <button type="button" class="btn btn-xs btn-danger" id="btnRemoveAttachment"><i class="fa fa-times"></i></button>
          </div>

          <form id="chatMessageForm" enctype="multipart/form-data">
            <input type="hidden" name="receiver_id" id="receiver_id" value="<?php echo $active_contact_id; ?>">
            <input type="file" id="chatAttachmentInput" name="attachment" style="display: none;" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.csv,.zip,.txt">
            
            <div class="chat-input-row">
              <button type="button" class="btn btn-attach" id="btnTriggerAttach" title="Attach file or photo">
                <i class="fa fa-paperclip" style="font-size: 16px;"></i>
              </button>
              
              <textarea name="message" id="chatMessageInput" class="chat-textarea" placeholder="Type message... (Press Enter to send, Shift+Enter for new line)" rows="1"></textarea>
              
              <button type="submit" class="btn btn-send" id="btnSendChat" title="Send message">
                <i class="fa fa-paper-plane" style="font-size: 15px;"></i>
              </button>
            </div>
          </form>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Lightbox Modal for Chat Images -->
<div class="modal fade" id="chatImageModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="text-align: center; margin-top: 50px;">
    <div style="display: inline-block; position: relative;">
      <button type="button" class="close" data-dismiss="modal" style="position: absolute; right: -25px; top: -25px; color: #fff; font-size: 30px; opacity: 1;">&times;</button>
      <img id="chatModalImageSrc" src="" style="max-width: 90vw; max-height: 85vh; border-radius: 6px; box-shadow: 0 5px 25px rgba(0,0,0,0.5);">
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var activeContactId = parseInt($('#receiver_id').val()) || 0;
    var currentUserId = <?php echo $current_user_id; ?>;
    var lastMessageId = 0;
    var isPolling = false;
    var pollInterval = null;

    // Auto-scroll messages body to bottom
    function scrollToBottom(smooth) {
      var container = document.getElementById('chatMessagesContainer');
      if (container) {
        if (smooth) {
          container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
        } else {
          container.scrollTop = container.scrollHeight;
        }
      }
    }

    // Render single message HTML bubble
    function renderMessageBubble(msg) {
      var isMine = (msg.sender_id == currentUserId);
      var wrapperClass = isMine ? 'mine' : 'theirs';
      
      var senderHtml = '';
      if (!isMine) {
        var roleClass = (msg.sender_role_id == 1) ? 'role-admin' : ((msg.sender_role_id == 2) ? 'role-branch' : 'role-franchise');
        var orgTag = msg.sender_branch_name ? msg.sender_branch_name : (msg.sender_franchise_name ? msg.sender_franchise_name : msg.sender_role_name);
        senderHtml = '<span class="sender-tag ' + roleClass + '">' + $('<div>').text(msg.sender_name).html() + ' (' + orgTag + ')</span>';
      }

      var contentHtml = '';
      if (msg.message && msg.message.trim() !== '') {
        // Safe HTML text with newlines
        var escaped = $('<div>').text(msg.message).html().replace(/\n/g, '<br>');
        contentHtml += '<div>' + escaped + '</div>';
      }

      // Attachment preview
      if (msg.attachment) {
        if (msg.is_image) {
          contentHtml += '<img src="' + msg.attachment_url + '" class="chat-attachment-img zoomable-chat-img" data-full="' + msg.attachment_url + '" alt="Attachment">';
        } else {
          var fileIcon = 'fa-file-o';
          var ext = msg.attachment_name ? msg.attachment_name.split('.').pop().toLowerCase() : '';
          if (ext === 'pdf') fileIcon = 'fa-file-pdf-o text-danger';
          else if (['xls', 'xlsx', 'csv'].indexOf(ext) !== -1) fileIcon = 'fa-file-excel-o text-success';
          else if (['doc', 'docx'].indexOf(ext) !== -1) fileIcon = 'fa-file-word-o text-primary';
          else if (['zip', 'rar'].indexOf(ext) !== -1) fileIcon = 'fa-file-archive-o text-warning';

          contentHtml += '<a href="' + msg.download_url + '" class="chat-file-card" target="_blank">' +
            '<i class="fa ' + fileIcon + ' fa-2x"></i>' +
            '<div style="min-width:0; overflow:hidden;">' +
              '<div style="font-weight:600; font-size:12px; white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">' + $('<div>').text(msg.attachment_name).html() + '</div>' +
              '<small style="font-size:10px; opacity:0.8;">' + (msg.formatted_size || '') + ' &bull; Click to download</small>' +
            '</div>' +
            '<i class="fa fa-download" style="margin-left:auto;"></i>' +
          '</a>';
        }
      }

      var checkHtml = isMine ? ' <i class="fa fa-check" style="font-size:9px;"></i>' : '';
      var metaHtml = '<div class="message-meta"><span>' + msg.formatted_time + '</span>' + checkHtml + '</div>';

      return '<div class="message-wrapper ' + wrapperClass + '" data-msg-id="' + msg.id + '">' +
        '<div class="message-bubble">' +
          senderHtml +
          contentHtml +
          metaHtml +
        '</div>' +
      '</div>';
    }

    // Load messages via AJAX
    function fetchMessages(isIncremental) {
      if (isPolling) return;
      isPolling = true;

      var reqLastId = isIncremental ? lastMessageId : 0;

      $.ajax({
        url: '<?php echo site_url("chat/get-messages"); ?>',
        type: 'GET',
        data: {
          contact_id: activeContactId,
          last_id: reqLastId
        },
        dataType: 'json',
        success: function(res) {
          isPolling = false;
          if (res.status === 'success' && res.messages) {
            if (!isIncremental) {
              $('#chatMessagesContainer').empty();
              if (res.messages.length === 0) {
                $('#chatMessagesContainer').html(
                  '<div class="chat-empty-state">' +
                    '<i class="fa fa-comments-o fa-4x" style="opacity:0.3; margin-bottom:12px;"></i>' +
                    '<h4 style="font-weight:600; color:#64748b; margin:0 0 6px 0;">Start a Conversation</h4>' +
                    '<p style="font-size:13px; max-width:300px; margin:0;">Send a message or attach documents to discuss consignment updates directly.</p>' +
                  '</div>'
                );
              }
            }

            if (res.messages.length > 0) {
              if (!isIncremental && $('#chatMessagesContainer .chat-empty-state').length) {
                $('#chatMessagesContainer').empty();
              }

              $.each(res.messages, function(i, m) {
                if (parseInt(m.id) > lastMessageId) {
                  lastMessageId = parseInt(m.id);
                }
                $('#chatMessagesContainer').append(renderMessageBubble(m));
              });

              scrollToBottom(!isIncremental ? false : true);
            }
          }
        },
        error: function() {
          isPolling = false;
        }
      });
    }

    // Initial load
    fetchMessages(false);

    // Live Polling every 3.5 seconds
    pollInterval = setInterval(function() {
      fetchMessages(true);
    }, 3500);

    // Refresh button
    $('#btnRefreshMessages').click(function() {
      lastMessageId = 0;
      fetchMessages(false);
    });

    // Auto-expand textarea
    $('#chatMessageInput').on('input', function() {
      this.style.height = 'auto';
      this.style.height = (this.scrollHeight) + 'px';
    });

    // Press Enter to send, Shift+Enter for new line
    $('#chatMessageInput').on('keydown', function(e) {
      if (e.keyCode === 13 && !e.shiftKey) {
        e.preventDefault();
        $('#chatMessageForm').submit();
      }
    });

    // Attachment trigger
    $('#btnTriggerAttach').click(function() {
      $('#chatAttachmentInput').click();
    });

    $('#chatAttachmentInput').change(function() {
      var file = this.files[0];
      if (file) {
        var sizeStr = (file.size > 1048576) ? (file.size / 1048576).toFixed(1) + ' MB' : (file.size / 1024).toFixed(1) + ' KB';
        $('#attachmentFileName').text(file.name);
        $('#attachmentFileSize').text(sizeStr);
        $('#attachmentPreviewBar').css('display', 'flex');
      } else {
        $('#attachmentPreviewBar').hide();
      }
    });

    $('#btnRemoveAttachment').click(function() {
      $('#chatAttachmentInput').val('');
      $('#attachmentPreviewBar').hide();
    });

    // Send Message Form Submit
    $('#chatMessageForm').submit(function(e) {
      e.preventDefault();
      var msg = $('#chatMessageInput').val().trim();
      var fileInput = document.getElementById('chatAttachmentInput');
      var hasFile = fileInput.files && fileInput.files.length > 0;

      if (!msg && !hasFile) return;

      var formData = new FormData(this);
      formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');

      $('#btnSendChat').prop('disabled', true);

      $.ajax({
        url: '<?php echo site_url("chat/send-message"); ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
          $('#btnSendChat').prop('disabled', false);
          if (res.status === 'success' && res.message) {
            $('#chatMessageInput').val('').css('height', 'auto');
            $('#chatAttachmentInput').val('');
            $('#attachmentPreviewBar').hide();

            if ($('#chatMessagesContainer .chat-empty-state').length) {
              $('#chatMessagesContainer').empty();
            }

            if (parseInt(res.message.id) > lastMessageId) {
              lastMessageId = parseInt(res.message.id);
            }
            $('#chatMessagesContainer').append(renderMessageBubble(res.message));
            scrollToBottom(true);
          } else {
            alert(res.message || 'Failed to dispatch message.');
          }
        },
        error: function() {
          $('#btnSendChat').prop('disabled', false);
          alert('Network communication error while sending message.');
        }
      });
    });

    // Image lightbox
    $(document).on('click', '.zoomable-chat-img', function() {
      var src = $(this).data('full') || $(this).attr('src');
      $('#chatModalImageSrc').attr('src', src);
      $('#chatImageModal').modal('show');
    });

    // Search contacts filter
    $('#searchContactsInput').on('keyup', function() {
      var val = $(this).val().toLowerCase().trim();
      $('.chat-contact-item').each(function() {
        var isBroadcast = ($(this).data('role') === 'broadcast');
        if (isBroadcast) {
          $(this).show();
          return;
        }
        var searchStr = $(this).data('search') || '';
        if (searchStr.indexOf(val) !== -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });

    // Role filter tabs
    $('.chat-filter-btn').click(function() {
      $('.chat-filter-btn').removeClass('active');
      $(this).addClass('active');
      var filter = $(this).data('filter');

      $('.chat-contact-item').each(function() {
        var role = $(this).data('role');
        if (role === 'broadcast') {
          $(this).show();
          return;
        }
        if (filter === 'all' || role === filter) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });

    // Switch contact click
    $(document).on('click', '.chat-contact-item', function() {
      var targetId = $(this).data('contact-id');
      window.location.href = '<?php echo site_url("chat/contact/"); ?>' + targetId;
    });

  });
</script>
