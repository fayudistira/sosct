<!-- New Conversation Modal -->
<div class="modal fade" id="newConversationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('messages/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="type" id="typePrivate" value="private" checked onclick="toggleGroupFields(false)">
                            <label class="btn btn-outline-secondary" for="typePrivate">Private Message</label>

                            <input type="radio" class="btn-check" name="type" id="typeGroup" value="group" onclick="toggleGroupFields(true)">
                            <label class="btn btn-outline-secondary" for="typeGroup">Group Chat</label>
                        </div>
                    </div>

                    <div class="mb-3" id="groupTitleField" style="display: none;">
                        <label for="title" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Project Team">
                    </div>

                    <div class="mb-3">
                        <label for="participants" class="form-label">Participants</label>
                        <select class="form-select select2-users" id="participants" name="participants[]" multiple="multiple" style="width: 100%" required>
                            <!-- AJAX Populated -->
                        </select>
                         <div class="form-text">Search for users to add to the conversation</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark-red">Start Conversation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleGroupFields(isGroup) {
    const titleField = document.getElementById('groupTitleField');
    if (isGroup) {
        titleField.style.display = 'block';
    } else {
        titleField.style.display = 'none';
    }
}
</script>
