<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="<?= base_url('assets/css/messaging.css') ?>">
<style>
    /* Mobile specific: hide list when in conversation */
    @media (max-width: 768px) {
        .conversation-list { display: none; }
        .chat-area { display: flex !important; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
        <a href="<?= base_url('messages') ?>" class="btn btn-outline-secondary btn-sm me-2 d-md-none">
            <i class="bi bi-chevron-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Messages</h4>
    </div>
    <button type="button" class="btn btn-dark-red btn-sm" data-bs-toggle="modal" data-bs-target="#newConversationModal">
        <i class="bi bi-plus-lg me-2"></i>New Message
    </button>
</div>

<div class="messaging-container">
    <!-- Sidebar (Hidden on mobile when chat open) -->
    <div class="conversation-list d-none d-md-flex">
        <div class="conversation-list-header">
            <input type="text" class="form-control" placeholder="Search messages...">
        </div>
        <div class="conversation-items" id="conversation-list">
           <!-- Populated by JS -->
        </div>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <div class="conversation-avatar me-2" style="width: 40px; height: 40px; font-size: 1rem;">
                    <?= strtoupper(substr($title, 0, 1)) ?>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold"><?= esc($title) ?></h6>
                    <small class="text-muted">
                        <?php if($conversation['type'] === 'group'): ?>
                            <?= count($participants) ?> participants
                        <?php else: ?>
                            Private Conversation
                        <?php endif ?>
                    </small>
                </div>
            </div>
            <div>
                 <!-- Actions like info, leave group etc. could go here -->
            </div>
        </div>

        <div class="chat-messages" id="chat-messages" data-conversation-id="<?= $conversation['id'] ?>">
            <!-- Messages will be loaded here via AJAX -->
            <div class="text-center p-5 text-muted">
                <div class="spinner-border spinner-border-sm" role="status"></div> Loading messages...
            </div>
        </div>

        <div class="chat-input-area">
            <form id="send-message-form" enctype="multipart/form-data">
                <input type="hidden" name="conversation_id" value="<?= $conversation['id'] ?>">
                <div class="input-group">
                    <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('attachment').click()">
                        <i class="bi bi-paperclip"></i>
                    </button>
                    <textarea class="form-control" name="message" id="message-input" rows="1" placeholder="Type a message..." style="resize: none;"></textarea>
                    <button class="btn btn-dark-red" type="submit">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
                <input type="file" id="attachment" name="attachment" class="d-none" onchange="showFileName(this)">
                <div id="file-preview" class="small text-muted mt-1" style="display:none"></div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('Modules\Messaging\Views\new_conversation_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url('assets/js/messaging.js') ?>"></script>
<script>
    function showFileName(input) {
        const preview = document.getElementById('file-preview');
        if (input.files && input.files[0]) {
            preview.textContent = 'Selected: ' + input.files[0].name;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    // Load conversation list for sidebar
    document.addEventListener('DOMContentLoaded', () => {
        fetch('/messages/api/conversations')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('conversation-list');
                const currentId = '<?= $conversation['id'] ?>';
                
                let html = '';
                data.conversations.forEach(conv => {
                    const isActive = conv.id === currentId ? 'active' : '';
                    const avatarLetter = conv.title.charAt(0).toUpperCase();
                    const preview = conv.last_message?.message_text || '<i>Attachment</i>';
                    const time = new Date(conv.last_message?.created_at || conv.created_at).toLocaleDateString();
                    
                    html += `
                        <div class="conversation-item ${isActive}" onclick="window.location.href='/messages/conversation/${conv.id}'">
                            <div class="conversation-avatar">${avatarLetter}</div>
                            <div class="conversation-info">
                                <div class="conversation-name">${conv.title}</div>
                                <div class="conversation-preview">${preview}</div>
                            </div>
                            <div class="conversation-meta">
                                <div class="conversation-time">${time}</div>
                            </div>
                        </div>
                    `;
                });
                list.innerHTML = html;
            });
    });
</script>
<?= $this->endSection() ?>
