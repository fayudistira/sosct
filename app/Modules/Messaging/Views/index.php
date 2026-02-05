<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="<?= base_url('assets/css/messaging.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Messages</h4>
    <button type="button" class="btn btn-dark-red btn-sm" data-bs-toggle="modal" data-bs-target="#newConversationModal">
        <i class="bi bi-plus-lg me-2"></i>New Message
    </button>
</div>

<div class="messaging-container">
    <!-- Sidebar -->
    <div class="conversation-list w-100 w-md-auto">
        <div class="conversation-list-header">
            <input type="text" class="form-control" placeholder="Search messages...">
        </div>
        <div class="conversation-items" id="conversation-list">
            <!-- Loading State -->
            <div class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State (Desktop) -->
    <div class="chat-area d-none d-md-flex align-items-center justify-content-center bg-light">
        <div class="text-center text-muted">
            <i class="bi bi-chat-square-text display-1"></i>
            <h5 class="mt-3">Select a conversation</h5>
            <p>Choose a conversation from the list or start a new one.</p>
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
    document.addEventListener('DOMContentLoaded', () => {
        // Fetch conversations
        fetch('/messages/api/conversations')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('conversation-list');
                if (data.conversations.length === 0) {
                    list.innerHTML = '<div class="text-center p-4 text-muted">No conversations yet</div>';
                    return;
                }
                
                let html = '';
                data.conversations.forEach(conv => {
                    const activeClass = ''; // No active conversation here
                    const avatarLetter = conv.title.charAt(0).toUpperCase();
                    const time = new Date(conv.last_message?.created_at || conv.created_at).toLocaleDateString();
                    const preview = conv.last_message?.message_text || '<i>Attachment</i>';
                    const unreadParams = conv.unread_count > 0 ? `<span class="badge-unread">${conv.unread_count}</span>` : '';
                    
                    html += `
                        <div class="conversation-item ${activeClass}" onclick="window.location.href='/messages/conversation/${conv.id}'">
                            <div class="conversation-avatar">${avatarLetter}</div>
                            <div class="conversation-info">
                                <div class="conversation-name">${conv.title}</div>
                                <div class="conversation-preview">${preview}</div>
                            </div>
                            <div class="conversation-meta">
                                <div class="conversation-time">${time}</div>
                                ${unreadParams}
                            </div>
                        </div>
                    `;
                });
                list.innerHTML = html;
            });
    });
</script>
<?= $this->endSection() ?>
