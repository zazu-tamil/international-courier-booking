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
    width: 360px;
    min-width: 320px;
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

  .chat-contact-item.chat-conv-item.active {
    background: #f3e8ff;
    border-left: 4px solid #7c3aed;
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

  .chat-avatar.role-admin {
    background: linear-gradient(135deg, #e53e3e, #dd6b20);
  }
  .chat-avatar.role-branch {
    background: linear-gradient(135deg, #3182ce, #00b4d8);
  }
  .chat-avatar.role-franchise {
    background: linear-gradient(135deg, #d97706, #f59e0b);
  }
  .chat-avatar.role-broadcast {
    background: linear-gradient(135deg, #059669, #10b981);
  }
  .chat-avatar.role-conv {
    background: linear-gradient(135deg, #7c3aed, #2563eb);
  }

  .online-indicator {
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: #cbd5e1;
    border: 2px solid #fff;
    position: absolute;
    bottom: 0px;
    right: 0px;
  }

  .online-indicator.is-online {
    background: #10b981;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
  }

  .chat-contact-info {
    flex: 1;
    min-width: 0;
  }

  .chat-contact-top {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 2px;
  }

  .chat-contact-name {
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .chat-contact-time {
    font-size: 11px;
    color: #94a3b8;
    margin-left: 6px;
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
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
  }

  .badge-unread {
    background: #ef4444;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: 8px;
    flex-shrink: 0;
  }

  /* Right Main Chat Window */
  .chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #ffffff;
    position: relative;
  }

  .chat-main-header {
    padding: 14px 20px;
    border-bottom: 1px solid #edf2f7;
    background: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .chat-active-user {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .chat-messages-body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  /* Chat Bubbles */
  .message-wrapper {
    display: flex;
    flex-direction: column;
    max-width: 75%;
    animation: fadeIn 0.15s ease-in;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .message-wrapper.mine {
    align-self: flex-end;
  }

  .message-wrapper.theirs {
    align-self: flex-start;
  }

  .message-bubble {
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 13.5px;
    line-height: 1.45;
    position: relative;
    word-break: break-word;
  }

  .message-wrapper.mine .message-bubble {
    background: #3c8dbc;
    color: #ffffff;
    border-bottom-right-radius: 2px;
    box-shadow: 0 1px 2px rgba(60,141,188,0.2);
  }

  .message-wrapper.theirs .message-bubble {
    background: #ffffff;
    color: #1e293b;
    border-bottom-left-radius: 2px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
  }

  /* Observer Mode Bubbles */
  .message-wrapper.observer-u1 {
    align-self: flex-start;
  }

  .message-wrapper.observer-u1 .message-bubble {
    background: #ffffff;
    color: #1e293b;
    border-left: 4px solid #3b82f6;
    border-bottom-left-radius: 2px;
    border-top: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
  }

  .message-wrapper.observer-u2 {
    align-self: flex-end;
  }

  .message-wrapper.observer-u2 .message-bubble {
    background: #f8fafc;
    color: #1e293b;
    border-right: 4px solid #10b981;
    border-bottom-right-radius: 2px;
    border-top: 1px solid #e2e8f0;
    border-left: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
  }

  .sender-tag {
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 4px;
    display: block;
  }

  .sender-tag.role-admin { color: #dc2626; }
  .sender-tag.role-branch { color: #2563eb; }
  .sender-tag.role-franchise { color: #d97706; }

  .message-meta {
    font-size: 10px;
    margin-top: 4px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 4px;
  }

  .message-wrapper.mine .message-meta {
    color: rgba(255, 255, 255, 0.75);
  }

  .message-wrapper.theirs .message-meta,
  .message-wrapper.observer-u1 .message-meta,
  .message-wrapper.observer-u2 .message-meta {
    color: #94a3b8;
  }

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

  .message-wrapper.theirs .chat-file-card,
  .message-wrapper.observer-u1 .chat-file-card,
  .message-wrapper.observer-u2 .chat-file-card {
    background: #f1f5f9;
    color: #1e293b !important;
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
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <strong style="font-size:14px; color:#1e293b;">Internal Messages</strong>
            <?php if($is_superadmin): ?>
              <span class="label label-danger" style="font-size:10px; padding:3px 6px;">
                <i class="fa fa-user-secret"></i> Super Admin Hub
              </span>
            <?php else: ?>
              <span class="label label-primary" style="font-size:10px; padding:3px 6px;">
                Staff Chat
              </span>
            <?php endif; ?>
          </div>

          <div class="input-group">
            <span class="input-group-addon" style="background:#fff; border-right:none;"><i class="fa fa-search text-muted"></i></span>
            <input type="text" id="searchContactsInput" class="form-control" placeholder="Search contacts &amp; branches..." style="border-left:none;">
          </div>
          
          <div class="chat-filter-pills">
            <?php if($is_superadmin): ?>
              <button type="button" class="chat-filter-btn <?php echo ($is_observing_thread || (!empty($all_conversations) && $active_contact_id == 0)) ? 'active' : ''; ?>" data-filter="conversations">
                <i class="fa fa-comments"></i> All Chats (<?php echo count($all_conversations); ?>)
              </button>
              <button type="button" class="chat-filter-btn <?php echo (!$is_observing_thread && empty($all_conversations)) ? 'active' : ''; ?>" data-filter="all">
                <i class="fa fa-users"></i> Staff Directory
              </button>
              <button type="button" class="chat-filter-btn" data-filter="branch">Branches</button>
              <button type="button" class="chat-filter-btn" data-filter="franchise">Franchises</button>
            <?php else: ?>
              <button type="button" class="chat-filter-btn active" data-filter="all">All Contacts</button>
              <button type="button" class="chat-filter-btn" data-filter="branch">Branches</button>
              <button type="button" class="chat-filter-btn" data-filter="franchise">Franchises</button>
            <?php endif; ?>
          </div>
        </div>

        <ul class="chat-contacts-scroll" id="contactsList">
          <!-- Pinned Announcements Channel -->
          <li class="chat-contact-item <?php echo (!$is_observing_thread && $active_contact_id == 0) ? 'active' : ''; ?>" data-contact-id="0" data-role="broadcast">
            <div class="chat-avatar role-broadcast">
              <i class="fa fa-bullhorn"></i>
              <span class="online-indicator is-online"></span>
            </div>
            <div class="chat-contact-info">
              <div class="chat-contact-top">
                <span class="chat-contact-name text-green"><i class="fa fa-bullhorn"></i> Team Announcements</span>
                <span class="chat-contact-time"><?php echo $is_superadmin ? 'Broadcast' : 'Read-Only'; ?></span>
              </div>
              <div class="chat-contact-bottom">
                <span class="chat-contact-snippet">Official company notices &amp; updates</span>
              </div>
            </div>
          </li>

          <!-- Super Admin All Conversation Threads (Live Surveillance) -->
          <?php if($is_superadmin && !empty($all_conversations)): ?>
            <?php foreach($all_conversations as $conv): ?>
              <?php 
                $is_this_conv = ($is_observing_thread && (($observe_u1 == $conv->user1_id && $observe_u2 == $conv->user2_id) || ($observe_u1 == $conv->user2_id && $observe_u2 == $conv->user1_id)));
              ?>
              <li class="chat-contact-item chat-conv-item <?php echo $is_this_conv ? 'active' : ''; ?>" 
                  data-conv-u1="<?php echo $conv->user1_id; ?>" 
                  data-conv-u2="<?php echo $conv->user2_id; ?>"
                  data-role="conversations"
                  data-search="<?php echo strtolower($conv->user1->username . ' ' . $conv->user2->username . ' ' . $conv->user1->org_badge . ' ' . $conv->user2->org_badge . ' ' . $conv->last_message); ?>">
                <div class="chat-avatar role-conv" style="font-size:15px;">
                  <i class="fa fa-exchange"></i>
                </div>
                <div class="chat-contact-info">
                  <div class="chat-contact-top">
                    <span class="chat-contact-name" style="color: #4338ca; font-weight:700;">
                      <?php echo htmlspecialchars($conv->user1->username); ?> ↔ <?php echo htmlspecialchars($conv->user2->username); ?>
                    </span>
                    <span class="chat-contact-time"><?php echo $conv->formatted_time; ?></span>
                  </div>
                  <div class="chat-contact-bottom">
                    <span class="chat-contact-snippet">
                      <span class="label label-primary" style="font-size: 9px; padding: 1px 4px;"><?php echo htmlspecialchars($conv->user1->org_badge); ?></span>
                      <span class="label label-info" style="font-size: 9px; padding: 1px 4px;"><?php echo htmlspecialchars($conv->user2->org_badge); ?></span>
                      <span class="label label-default" style="font-size: 9px; padding: 1px 4px;"><?php echo $conv->message_count; ?> msgs</span>
                      <?php echo htmlspecialchars($conv->last_message); ?>
                    </span>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>

          <!-- Dynamic Direct Contacts (Super Admin is NEVER in this list for regular staff) -->
          <?php if(!empty($contacts)): ?>
            <?php foreach($contacts as $contact): ?>
              <?php 
                $role_class = ($contact->role_id == 1) ? 'admin' : (($contact->role_id == 2) ? 'branch' : 'franchise');
                $initials = strtoupper(substr($contact->username, 0, 2));
                $is_active = (!$is_observing_thread && $active_contact_id == $contact->id);
              ?>
              <li class="chat-contact-item <?php echo $is_active ? 'active' : ''; ?>" 
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
            <?php if($is_observing_thread): ?>
              <!-- Super Admin Surveillance View Header -->
              <div class="chat-avatar role-conv" style="width: 42px; height: 42px; font-size: 16px;">
                <i class="fa fa-eye"></i>
              </div>
              <div>
                <h4 style="margin: 0; font-weight: 700; font-size: 16px; color: #1e293b;">
                  <?php echo htmlspecialchars($observe_user1->username); ?> <small style="color:#2563eb;">(<?php echo htmlspecialchars($observe_user1->org_badge); ?>)</small>
                  <span style="color: #7c3aed; margin: 0 4px;">↔</span>
                  <?php echo htmlspecialchars($observe_user2->username); ?> <small style="color:#059669;">(<?php echo htmlspecialchars($observe_user2->org_badge); ?>)</small>
                  <span class="label label-warning" style="font-size: 10px; margin-left: 8px; vertical-align: middle;">
                    <i class="fa fa-user-secret"></i> Super Admin Observation Mode
                  </span>
                </h4>
                <small class="text-muted">
                  <i class="fa fa-shield text-purple"></i> Stealth Surveillance &mdash; Live feed between <?php echo htmlspecialchars($observe_user1->username); ?> and <?php echo htmlspecialchars($observe_user2->username); ?>
                </small>
              </div>

            <?php elseif($active_contact_id == 0): ?>
              <!-- Team Announcements Header -->
              <div class="chat-avatar role-broadcast" style="width: 40px; height: 40px; font-size: 14px;">
                <i class="fa fa-bullhorn"></i>
              </div>
              <div>
                <h4 style="margin: 0; font-weight: 700; font-size: 16px; color: #1e293b;">
                  Team Announcements &amp; General Discussions
                  <?php if($is_superadmin): ?>
                    <span class="label label-success" style="font-size: 10px; margin-left: 6px;"><i class="fa fa-pencil"></i> Admin Broadcast Access</span>
                  <?php else: ?>
                    <span class="label label-default" style="font-size: 10px; margin-left: 6px;"><i class="fa fa-lock"></i> Read-Only Channel</span>
                  <?php endif; ?>
                </h4>
                <small class="text-muted"><i class="fa fa-users text-green"></i> Shared channel visible to all Branches and Franchises</small>
              </div>

            <?php else: ?>
              <!-- Direct 1-on-1 Contact Header -->
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
            <button type="button" class="btn btn-default btn-sm" id="btnToggleSound" title="Toggle sound alerts">
              <i class="fa fa-bell text-yellow" id="soundIcon"></i>
            </button>
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

        <!-- Composer / Input Bar / Readonly Notices -->
        <?php if($is_observing_thread): ?>
          <!-- Super Admin Surveillance: Stealth Notice -->
          <div style="padding: 14px 20px; background: #faf5ff; border-top: 1px solid #e9d5ff; display: flex; align-items: center; justify-content: center; gap: 12px; color: #6b21a8; text-align: center;">
            <i class="fa fa-user-secret" style="font-size: 20px; color: #9333ea;"></i>
            <span style="font-size: 13px;">
              <strong>Super Admin Surveillance Mode:</strong> Observing conversation between <strong><?php echo htmlspecialchars($observe_user1->username); ?></strong> and <strong><?php echo htmlspecialchars($observe_user2->username); ?></strong>. You are completely invisible to both participants.
            </span>
          </div>

        <?php elseif($active_contact_id == 0 && !$is_superadmin): ?>
          <!-- FEATURE 2: Non-Superadmin read-only barrier for Team Announcements -->
          <div style="padding: 16px 20px; background: #f0f7ff; border-top: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; gap: 14px; color: #1e40af; text-align: center;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: #dbeafe; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #2563eb; flex-shrink: 0;">
              <i class="fa fa-bullhorn"></i>
            </div>
            <div>
              <strong style="font-size: 13px; display: block;">Official Team Announcements (Read-Only)</strong>
              <span style="font-size: 12px; color: #475569;">Only Head Office / Super Admin can post announcements here. To message a colleague directly, select their branch or franchise from the contacts list.</span>
            </div>
          </div>

        <?php else: ?>
          <!-- Active Composer Bar (For direct chat OR for Super Admin on Announcements) -->
          <div class="chat-composer">
            <?php if($active_contact_id == 0 && $is_superadmin): ?>
              <div style="padding: 6px 14px; margin: -12px -18px 10px -18px; background: #fffbeb; border-bottom: 1px solid #fde68a; font-size: 12px; color: #92400e; display: flex; align-items: center; gap: 8px;">
                <i class="fa fa-bullhorn text-warning"></i>
                <span><strong>Admin Broadcast Mode:</strong> Announcements and attachments sent here are instantly visible to all branches and franchises.</span>
              </div>
            <?php endif; ?>

            <!-- Attachment selected preview -->
            <div class="attachment-preview-bar" id="attachmentPreviewBar">
              <div style="display: flex; align-items: center; gap: 8px; min-width: 0; overflow: hidden;">
                <img id="attachmentThumbPreview" src="" style="width: 28px; height: 28px; border-radius: 4px; object-fit: cover; display: none;">
                <i class="fa fa-paperclip text-blue" id="attachmentIcon"></i>
                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                  <strong id="attachmentFileName"></strong> (<span id="attachmentFileSize"></span>)
                </span>
              </div>
              <button type="button" class="btn btn-xs btn-danger" id="btnRemoveAttachment" style="flex-shrink: 0; margin-left: 10px;"><i class="fa fa-times"></i></button>
            </div>

            <form id="chatMessageForm" enctype="multipart/form-data">
              <input type="hidden" name="receiver_id" id="receiver_id" value="<?php echo $active_contact_id; ?>">
              <input type="hidden" name="camera_snapshot" id="chatCameraSnapshot" value="">
              <input type="file" id="chatAttachmentInput" name="attachment" style="display: none;" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.csv,.zip,.txt">
              
              <div class="chat-input-row">
                <button type="button" class="btn btn-attach" id="btnTriggerAttach" title="Attach file or photo">
                  <i class="fa fa-paperclip" style="font-size: 16px;"></i>
                </button>
                <button type="button" class="btn btn-attach" id="btnTriggerCamera" title="Take Photo / Selfie">
                  <i class="fa fa-camera" style="font-size: 15px;"></i>
                </button>
                
                <textarea name="message" id="chatMessageInput" class="chat-textarea" placeholder="<?php echo ($active_contact_id == 0) ? 'Type team announcement... (Press Enter to broadcast)' : 'Type message... (Press Enter to send, Shift+Enter for new line)'; ?>" rows="1"></textarea>
                
                <button type="submit" class="btn btn-send" id="btnSendChat" title="<?php echo ($active_contact_id == 0) ? 'Broadcast Announcement' : 'Send message'; ?>">
                  <i class="fa <?php echo ($active_contact_id == 0) ? 'fa-bullhorn' : 'fa-paper-plane'; ?>" style="font-size: 15px;"></i>
                </button>
              </div>
            </form>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </div>
</div>

<!-- Camera / Selfie Capture Modal -->
<div class="modal fade" id="chatCameraModal" tabindex="-1" role="dialog" aria-labelledby="chatCameraModalLabel" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content" style="border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
      <div class="modal-header bg-primary" style="padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
        <h4 class="modal-title" id="chatCameraModalLabel" style="font-weight: 600; font-size: 16px; margin: 0;">
          <i class="fa fa-camera"></i> Take Photo / Selfie
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9; margin: 0; font-size: 22px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding: 15px; background: #0f172a; text-align: center;">
        
        <!-- Live Camera Viewport -->
        <div id="cameraViewport" style="position: relative; width: 100%; height: 340px; background: #000; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
          <video id="cameraVideo" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); border-radius: 8px;"></video>
          <canvas id="cameraCanvas" style="display: none;"></canvas>
          <img id="cameraSnapshotPreview" src="" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">

          <!-- Camera Loading Notice -->
          <div id="cameraLoadingNotice" style="position: absolute; color: #fff; text-align: center;">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p style="margin-top: 8px; font-size: 13px;">Connecting to camera...</p>
          </div>

          <!-- Flip camera button overlay -->
          <button type="button" id="btnFlipCamera" class="btn btn-sm btn-dark" style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.65); color: #fff; border: 1px solid rgba(255,255,255,0.3); border-radius: 20px; font-size: 12px;" title="Switch Front / Rear Camera">
            <i class="fa fa-refresh"></i> Flip Camera
          </button>
        </div>

        <!-- Optional message / caption -->
        <div id="cameraCaptionWrap" style="margin-top: 12px; display: none;">
          <input type="text" id="cameraPhotoCaption" class="form-control" placeholder="Add a caption... (optional)" style="border-radius: 20px; font-size: 13px; background: #1e293b; color: #fff; border-color: #334155;">
        </div>

        <div id="cameraErrorAlert" class="alert alert-danger" style="display: none; margin-top: 12px; font-size: 12px; text-align: left; margin-bottom: 0;"></div>
      </div>

      <div class="modal-footer" style="background: #f8fafc; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

        <!-- Live Capture Controls -->
        <div id="cameraLiveControls">
          <button type="button" class="btn btn-primary" id="btnCapturePhoto" style="border-radius: 20px; padding: 6px 22px; font-weight: 600;">
            <i class="fa fa-circle"></i> Capture Photo
          </button>
        </div>

        <!-- Captured Controls -->
        <div id="cameraCapturedControls" style="display: none; gap: 8px;">
          <button type="button" class="btn btn-default" id="btnRetakePhoto">
            <i class="fa fa-repeat"></i> Retake
          </button>
          <button type="button" class="btn btn-success" id="btnAttachPhoto">
            <i class="fa fa-check"></i> Attach Photo
          </button>
          <button type="button" class="btn btn-primary" id="btnSendPhotoDirect">
            <i class="fa fa-paper-plane"></i> Send Now
          </button>
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
    window._isInActiveChatView = true;

    var isObserving = <?php echo $is_observing_thread ? 'true' : 'false'; ?>;
    var observeU1 = <?php echo $observe_u1; ?>;
    var observeU2 = <?php echo $observe_u2; ?>;
    var activeContactId = parseInt($('#receiver_id').val()) || 0;
    var currentUserId = <?php echo $current_user_id; ?>;
    var isSuperAdmin = <?php echo $is_superadmin ? 'true' : 'false'; ?>;
    var lastMessageId = 0;
    var isPolling = false;
    var pollInterval = null;

    // Sound alert toggle setting
    var soundEnabled = (localStorage.getItem('chat_sound_enabled') !== 'false');
    function updateSoundIcon() {
      if (soundEnabled) {
        $('#soundIcon').removeClass('fa-bell-slash text-muted').addClass('fa-bell text-yellow');
        $('#btnToggleSound').attr('title', 'Sound Alerts: Enabled (Click to mute)');
      } else {
        $('#soundIcon').removeClass('fa-bell text-yellow').addClass('fa-bell-slash text-muted');
        $('#btnToggleSound').attr('title', 'Sound Alerts: Muted (Click to enable)');
      }
    }
    updateSoundIcon();

    $('#btnToggleSound').click(function() {
      soundEnabled = !soundEnabled;
      localStorage.setItem('chat_sound_enabled', soundEnabled ? 'true' : 'false');
      updateSoundIcon();
      if (soundEnabled && window.playChatNotificationChime) {
        window.playChatNotificationChime();
      }
    });

    // Window focus & title flashing on new message
    var originalDocumentTitle = document.title;
    var titleInterval = null;
    var isWindowFocused = true;

    $(window).on('focus', function() {
      isWindowFocused = true;
      if (titleInterval) {
        clearInterval(titleInterval);
        titleInterval = null;
        document.title = originalDocumentTitle;
      }
    }).on('blur', function() {
      isWindowFocused = false;
    });

    function notifyIncomingMessage() {
      if (window.playChatNotificationChime) {
        window.playChatNotificationChime();
      }
      if (!isWindowFocused && !titleInterval) {
        var isAlt = false;
        titleInterval = setInterval(function() {
          document.title = isAlt ? '💬 New Message!' : originalDocumentTitle;
          isAlt = !isAlt;
        }, 1200);
      }
    }

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
      var wrapperClass = '';
      var senderHtml = '';

      if (isObserving) {
        // Super Admin Live Surveillance Mode
        wrapperClass = msg.is_user1 ? 'observer-u1' : 'observer-u2';
        var roleTag = msg.sender_role_name || '';
        var orgTag = msg.sender_branch_name ? msg.sender_branch_name : (msg.sender_franchise_name ? msg.sender_franchise_name : roleTag);
        var badgeColor = msg.is_user1 ? '#2563eb' : '#059669';
        senderHtml = '<span class="sender-tag" style="color:' + badgeColor + '; font-weight:700;"><i class="fa fa-user"></i> ' + $('<div>').text(msg.sender_name).html() + ' (' + orgTag + ')</span>';
      } else {
        // Standard Direct or Broadcast Mode
        var isMine = (msg.sender_id == currentUserId);
        wrapperClass = isMine ? 'mine' : 'theirs';

        if (!isMine) {
          var roleClass = (msg.sender_role_id == 1) ? 'role-admin' : ((msg.sender_role_id == 2) ? 'role-branch' : 'role-franchise');
          var orgTag = msg.sender_branch_name ? msg.sender_branch_name : (msg.sender_franchise_name ? msg.sender_franchise_name : msg.sender_role_name);
          senderHtml = '<span class="sender-tag ' + roleClass + '">' + $('<div>').text(msg.sender_name).html() + ' (' + orgTag + ')</span>';
        }
      }

      var contentHtml = '';
      if (msg.message && msg.message.trim() !== '') {
        var escaped = $('<div>').text(msg.message).html().replace(/\n/g, '<br>');
        contentHtml += '<div>' + escaped + '</div>';
      }

      // Attachment preview
      if (msg.attachment) {
        var isImg = (msg.is_image === true || msg.is_image === 1 || msg.is_image === '1') || (msg.attachment_type && msg.attachment_type.indexOf('image/') === 0);
        if (isImg) {
          contentHtml += '<div style="margin-top: 6px; position: relative;">' +
            '<img src="' + msg.attachment_url + '" class="chat-attachment-img zoomable-chat-img" data-full="' + msg.attachment_url + '" alt="Selfie Photo" style="min-width: 120px; min-height: 80px; background: #e2e8f0; display: block; border-radius: 8px; object-fit: cover;">' +
            '<div style="margin-top: 3px; font-size: 11px; opacity: 0.85;">' +
              '<a href="' + msg.download_url + '" target="_blank" style="color: inherit; text-decoration: underline;" title="Download full image"><i class="fa fa-download"></i> ' + (msg.formatted_size || 'Download') + '</a>' +
            '</div>' +
          '</div>';
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

      var checkHtml = (!isObserving && (msg.sender_id == currentUserId)) ? ' <i class="fa fa-check" style="font-size:9px;"></i>' : '';
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
      var reqData = {
        last_id: reqLastId
      };

      if (isObserving) {
        reqData.u1 = observeU1;
        reqData.u2 = observeU2;
      } else {
        reqData.contact_id = activeContactId;
      }

      $.ajax({
        url: '<?php echo site_url("chat/get-messages"); ?>',
        type: 'GET',
        data: reqData,
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
                    '<h4 style="font-weight:600; color:#64748b; margin:0 0 6px 0;">No Messages in this Channel</h4>' +
                    '<p style="font-size:13px; max-width:320px; margin:0;">' + (isObserving ? 'No conversation history between these staff members yet.' : 'Send a message or attach documents to begin the discussion.') + '</p>' +
                  '</div>'
                );
              }
            }

            if (res.messages.length > 0) {
              if (!isIncremental && $('#chatMessagesContainer .chat-empty-state').length) {
                $('#chatMessagesContainer').empty();
              }

              var hasIncomingMessage = false;
              $.each(res.messages, function(i, m) {
                if (parseInt(m.id) > lastMessageId) {
                  lastMessageId = parseInt(m.id);
                  if (!m.is_mine) {
                    hasIncomingMessage = true;
                  }
                }
                $('#chatMessagesContainer').append(renderMessageBubble(m));
              });

              scrollToBottom(!isIncremental ? false : true);

              // Play notification chime & flash title for incoming messages during live poll
              if (isIncremental && hasIncomingMessage) {
                notifyIncomingMessage();
              }
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

    // -------------------------------------------------------------
    // CAMERA & SELFIE CAPTURE ENGINE
    // -------------------------------------------------------------
    var cameraStream = null;
    var currentFacingMode = 'user'; // 'user' (selfie/front) or 'environment' (rear)
    var capturedBlob = null;
    var capturedDataUrl = null;

    $('#btnTriggerCamera').click(function() {
      $('#chatCameraModal').modal('show');
    });

    $('#chatCameraModal').on('shown.bs.modal', function() {
      startCamera();
    });

    $('#chatCameraModal').on('hidden.bs.modal', function() {
      stopCamera();
      resetCameraState();
    });

    function startCamera() {
      $('#cameraLoadingNotice').show();
      $('#cameraErrorAlert').hide();
      $('#cameraVideo').show();
      $('#cameraSnapshotPreview').hide();
      $('#cameraLiveControls').show();
      $('#cameraCapturedControls').hide();
      $('#cameraCaptionWrap').hide();

      if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        $('#cameraLoadingNotice').hide();
        $('#cameraErrorAlert').html('<i class="fa fa-exclamation-triangle"></i> Camera is not supported by your browser or requires a secure (HTTPS or localhost) connection.').show();
        return;
      }

      var constraints = {
        video: {
          facingMode: currentFacingMode,
          width: { ideal: 1280 },
          height: { ideal: 720 }
        },
        audio: false
      };

      navigator.mediaDevices.getUserMedia(constraints)
        .then(function(stream) {
          cameraStream = stream;
          var video = document.getElementById('cameraVideo');
          video.srcObject = stream;
          video.onloadedmetadata = function() {
            $('#cameraLoadingNotice').hide();
            video.play();
            if (currentFacingMode === 'user') {
              $(video).css('transform', 'scaleX(-1)');
            } else {
              $(video).css('transform', 'none');
            }
          };
        })
        .catch(function(err) {
          $('#cameraLoadingNotice').hide();
          var errMsg = 'Unable to access camera. Please allow camera permissions in your browser.';
          if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
            errMsg = 'Camera permission was denied. Please allow camera access in your browser address bar.';
          } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
            errMsg = 'No camera hardware found on this device.';
          } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
            errMsg = 'Camera is currently in use by another application.';
          }
          $('#cameraErrorAlert').html('<i class="fa fa-exclamation-triangle"></i> ' + errMsg).show();
        });
    }

    function stopCamera() {
      if (cameraStream) {
        cameraStream.getTracks().forEach(function(track) {
          track.stop();
        });
        cameraStream = null;
      }
      var video = document.getElementById('cameraVideo');
      if (video) {
        video.srcObject = null;
      }
    }

    function resetCameraState() {
      capturedBlob = null;
      capturedDataUrl = null;
      $('#cameraPhotoCaption').val('');
      $('#cameraSnapshotPreview').attr('src', '').hide();
      $('#cameraVideo').show();
      $('#cameraLiveControls').show();
      $('#cameraCapturedControls').hide();
      $('#cameraCaptionWrap').hide();
    }

    // Flip Camera button
    $('#btnFlipCamera').click(function() {
      currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
      stopCamera();
      startCamera();
    });

    // Capture Photo / Selfie
    $('#btnCapturePhoto').click(function() {
      var video = document.getElementById('cameraVideo');
      var canvas = document.getElementById('cameraCanvas');
      if (!video || !video.videoWidth) return;

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      var ctx = canvas.getContext('2d');

      // Mirror horizontally if front/selfie camera
      if (currentFacingMode === 'user') {
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
      }
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      capturedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
      $('#cameraSnapshotPreview').attr('src', capturedDataUrl).show();
      $(video).hide();

      canvas.toBlob(function(blob) {
        capturedBlob = blob;
      }, 'image/jpeg', 0.9);

      stopCamera();

      $('#cameraLiveControls').hide();
      $('#cameraCapturedControls').css('display', 'flex');
      $('#cameraCaptionWrap').show();
    });

    // Retake Photo
    $('#btnRetakePhoto').click(function() {
      resetCameraState();
      startCamera();
    });

    // Attach Photo to Main Composer
    $('#btnAttachPhoto').click(function() {
      if (!capturedDataUrl) return;

      var caption = $('#cameraPhotoCaption').val().trim();
      if (caption && !$('#chatMessageInput').val().trim()) {
        $('#chatMessageInput').val(caption);
      }

      applyCapturedPhotoToComposer();
      $('#chatCameraModal').modal('hide');
    });

    // Send Photo Directly from Modal
    $('#btnSendPhotoDirect').click(function() {
      if (!capturedDataUrl) return;

      var caption = $('#cameraPhotoCaption').val().trim();
      applyCapturedPhotoToComposer();

      if (caption) {
        $('#chatMessageInput').val(caption);
      }

      $('#chatCameraModal').modal('hide');
      $('#chatMessageForm').submit();
    });

    function applyCapturedPhotoToComposer() {
      $('#chatCameraSnapshot').val(capturedDataUrl);
      var sizeKb = Math.round((capturedDataUrl.length * 0.75) / 1024);
      $('#attachmentFileName').text('Selfie_Photo_' + (new Date()).toLocaleTimeString().replace(/[^0-9]/g, '') + '.jpg');
      $('#attachmentFileSize').text(sizeKb + ' KB');
      $('#attachmentThumbPreview').attr('src', capturedDataUrl).show();
      $('#attachmentIcon').hide();
      $('#attachmentPreviewBar').css('display', 'flex');

      // Also set into input files via DataTransfer if supported
      try {
        if (capturedBlob && window.DataTransfer) {
          var dt = new DataTransfer();
          var file = new File([capturedBlob], 'selfie_' + Date.now() + '.jpg', { type: 'image/jpeg' });
          dt.items.add(file);
          document.getElementById('chatAttachmentInput').files = dt.files;
        }
      } catch(e) {}
    }

    // Attachment trigger (File)
    $('#btnTriggerAttach').click(function() {
      $('#chatAttachmentInput').click();
    });

    $('#chatAttachmentInput').change(function() {
      var file = this.files[0];
      if (file) {
        var sizeStr = (file.size > 1048576) ? (file.size / 1048576).toFixed(1) + ' MB' : (file.size / 1024).toFixed(1) + ' KB';
        $('#attachmentFileName').text(file.name);
        $('#attachmentFileSize').text(sizeStr);
        $('#chatCameraSnapshot').val('');
        
        // Show image thumbnail if image
        if (file.type && file.type.indexOf('image/') !== -1) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#attachmentThumbPreview').attr('src', e.target.result).show();
            $('#attachmentIcon').hide();
          };
          reader.readAsDataURL(file);
        } else {
          $('#attachmentThumbPreview').attr('src', '').hide();
          $('#attachmentIcon').show();
        }

        $('#attachmentPreviewBar').css('display', 'flex');
      } else {
        $('#attachmentPreviewBar').hide();
      }
    });

    $('#btnRemoveAttachment').click(function() {
      $('#chatAttachmentInput').val('');
      $('#chatCameraSnapshot').val('');
      $('#attachmentThumbPreview').attr('src', '').hide();
      $('#attachmentIcon').show();
      $('#attachmentPreviewBar').hide();
      capturedBlob = null;
      capturedDataUrl = null;
    });

    // Send Message Form Submit
    $('#chatMessageForm').submit(function(e) {
      e.preventDefault();
      var msg = $('#chatMessageInput').val().trim();
      var fileInput = document.getElementById('chatAttachmentInput');
      var hasFile = fileInput.files && fileInput.files.length > 0;
      var hasCameraSnapshot = !!$('#chatCameraSnapshot').val();

      if (!msg && !hasFile && !hasCameraSnapshot) return;

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
            $('#chatCameraSnapshot').val('');
            $('#attachmentThumbPreview').attr('src', '').hide();
            $('#attachmentIcon').show();
            $('#attachmentPreviewBar').hide();
            capturedBlob = null;
            capturedDataUrl = null;

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

        if (filter === 'conversations') {
          if (role === 'conversations') {
            $(this).show();
          } else {
            $(this).hide();
          }
        } else if (filter === 'all') {
          if (role !== 'conversations') {
            $(this).show();
          } else {
            $(this).hide();
          }
        } else {
          if (role === filter) {
            $(this).show();
          } else {
            $(this).hide();
          }
        }
      });
    });

    // Switch regular contact click
    $(document).on('click', '.chat-contact-item:not(.chat-conv-item)', function() {
      var targetId = $(this).data('contact-id');
      window.location.href = '<?php echo site_url("chat/contact/"); ?>' + targetId;
    });

    // Switch conversation audit click (Super Admin)
    $(document).on('click', '.chat-conv-item', function() {
      var u1 = $(this).data('conv-u1');
      var u2 = $(this).data('conv-u2');
      window.location.href = '<?php echo site_url("chat"); ?>?u1=' + u1 + '&u2=' + u2;
    });

  });
</script>
