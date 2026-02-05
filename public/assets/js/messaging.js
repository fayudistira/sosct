/**
 * Messaging Module JavaScript
 */

const Messaging = (() => {
    let currentConversationId = null;
    let pollInterval = null;
    const POLL_TIME = 3000; // 3 secs
    const baseApiUrl = '/messages/api';

    // Init
    const init = () => {
        setupEventListeners();
        setupSelect2();

        // If on conversation page, start polling
        const chatContainer = document.getElementById('chat-messages');
        if (chatContainer) {
            currentConversationId = chatContainer.dataset.conversationId;
            scrollToBottom();
            startPolling();
        }
    };

    const setupEventListeners = () => {
        // Send message form
        const sendForm = document.getElementById('send-message-form');
        if (sendForm) {
            sendForm.addEventListener('submit', handleSendMessage);
        }

        // Enter key to submit (if not mobile/shift)
        const messageInput = document.getElementById('message-input');
        if (messageInput) {
            messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendForm.dispatchEvent(new Event('submit'));
                }
            });
        }
    };

    const setupSelect2 = () => {
        if ($('.select2-users').length) {
            $('.select2-users').select2({
                dropdownParent: $('#newConversationModal'),
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Search for users...',
                minimumInputLength: 2,
                ajax: {
                    url: baseApiUrl + '/users/search',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });
        }
    };

    const handleSendMessage = async (e) => {
        e.preventDefault();
        const form = e.target;
        const input = form.querySelector('textarea[name="message"]');
        const fileInput = form.querySelector('input[name="attachment"]');
        const submitBtn = form.querySelector('button[type="submit"]');

        const message = input.value.trim();
        const hasFile = fileInput.files.length > 0;

        if (!message && !hasFile) return;

        // Optimistic UI update (optional, skipping for simplicity/reliability first)

        const formData = new FormData(form);

        try {
            submitBtn.disabled = true;
            const response = await fetch('/messages/send', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            if (response.ok) {
                input.value = '';
                fileInput.value = '';
                // Immediate fetch to show new message
                fetchMessages();
            } else {
                console.error('Failed to send message');
                alert('Failed to send message. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            submitBtn.disabled = false;
            input.focus();
        }
    };

    const startPolling = () => {
        if (pollInterval) clearInterval(pollInterval);
        // Initial fetch
        // fetchMessages(); // Called manually on page load usually or rely on static load + poll
        pollInterval = setInterval(fetchMessages, POLL_TIME);
    };

    const fetchMessages = async () => {
        if (!currentConversationId) return;

        try {
            const response = await fetch(`${baseApiUrl}/messages/${currentConversationId}`);
            if (response.ok) {
                const data = await response.json();
                renderMessages(data.messages, data.current_user_id);
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    };

    const renderMessages = (messages, currentUserId) => {
        const container = document.getElementById('chat-messages');
        if (!container) return;

        const currentScroll = container.scrollTop;
        const isNearBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 100;

        // Simple full re-render (inefficient but safe for now to ensure consistency/edits/deletes if added)
        // Optimization: Append only new messages based on last known ID

        let html = '';
        messages.forEach(msg => {
            const isSent = msg.sender_id == currentUserId;
            const messageClass = isSent ? 'sent' : 'received';
            const avatarHtml = !isSent ?
                `<div class="message-avatar" title="${msg.sender_name}">${msg.sender_name.charAt(0).toUpperCase()}</div>` : '';

            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            const attachmentHtml = msg.attachment_path ?
                `<div class="mt-2"><a href="/${msg.attachment_path}" target="_blank" class="text-reset"><i class="bi bi-file-earmark-arrow-down"></i> Attachment</a></div>` : '';

            html += `
                <div class="message ${messageClass}" id="msg-${msg.id}">
                    ${avatarHtml}
                    <div class="message-content">
                        <div class="message-text">${escapeHtml(msg.message_text || '')}</div>
                        ${attachmentHtml}
                        <span class="message-time">${time}</span>
                    </div>
                </div>
            `;
        });

        // Only update if changed (simple string compare check)
        if (container.innerHTML !== html) {
            container.innerHTML = html;
            if (isNearBottom) {
                scrollToBottom();
            }
        }
    };

    const scrollToBottom = () => {
        const container = document.getElementById('chat-messages');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    };

    const escapeHtml = (text) => {
        if (!text) return '';
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    };

    return {
        init
    };
})();

document.addEventListener('DOMContentLoaded', Messaging.init);
