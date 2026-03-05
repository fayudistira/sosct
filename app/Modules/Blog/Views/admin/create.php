<?= $this->extend('Modules\Blog\Views\admin\layout') ?>

<?= $this->section('content') ?>
<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">New Post</li>
<?= $this->endSection() ?>

<!-- Custom WYSIWYG Editor CSS -->
<style>
.wysiwyg-editor {
    border: 1px solid #ced4da;
    border-radius: 0 0 0.375rem 0.375rem;
    min-height: 400px;
    max-height: 500px;
    padding: 1rem;
    background: #fff;
    overflow-y: auto;
}

.wysiwyg-editor:focus {
    outline: none;
    border-color: #8B0000;
    box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
}

.wysiwyg-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border: 1px solid #ced4da;
    border-bottom: none;
    border-radius: 0.375rem 0.375rem 0 0;
}

.wysiwyg-toolbar button {
    padding: 0.375rem 0.75rem;
    border: 1px solid #ced4da;
    background: #fff;
    border-radius: 0.2rem;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.15s ease;
}

.wysiwyg-toolbar button:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.wysiwyg-toolbar button.active {
    background: #8B0000;
    color: #fff;
    border-color: #8B0000;
}

.wysiwyg-toolbar .separator {
    width: 1px;
    background: #ced4da;
    margin: 0 0.25rem;
}

.wysiwyg-editor ul, .wysiwyg-editor ol {
    padding-left: 1.5rem;
}

.wysiwyg-editor a {
    color: #8B0000;
    text-decoration: underline;
}

.wysiwyg-editor h1, .wysiwyg-editor h2, .wysiwyg-editor h3 {
    color: #333;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}
</style>

<!-- Error Messages -->
<?php if (session('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Validation Errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Success Message -->
<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Create New Post</h4>
        <p class="text-muted mb-0">Write a new educational blog article</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admin/blog') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Posts
        </a>
    </div>
</div>

<!-- Error Messages -->
<?php if (session('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Validation Errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= base_url('admin/blog/store') ?>" method="post" id="blogForm">
    <?= csrf_field() ?>
    
    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= old('title') ?>" required
                               placeholder="Enter blog post title">
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label for="slug" class="form-label">URL Slug</label>
                        <div class="input-group">
                            <span class="input-group-text"><?= base_url('blog/') ?></span>
                            <input type="text" class="form-control" id="slug" name="slug" 
                                   value="<?= old('slug') ?>"
                                   placeholder="auto-generated-if-empty">
                        </div>
                        <div class="form-text">Alphanumeric characters, dashes, and underscores only</div>
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        
                        <!-- Editor Toggle -->
                        <div class="d-flex justify-content-end mb-2">
                            <div class="btn-group btn-group-sm" role="group" aria-label="Editor mode">
                                <input type="radio" class="btn-check" name="editorMode" id="modeVisual" value="visual" checked>
                                <label class="btn btn-outline-secondary" for="modeVisual">
                                    <i class="bi bi-eye me-1"></i>Visual
                                </label>
                                <input type="radio" class="btn-check" name="editorMode" id="modeCode" value="code">
                                <label class="btn btn-outline-secondary" for="modeCode">
                                    <i class="bi bi-code-slash me-1"></i>Code
                                </label>
                            </div>
                        </div>
                        
                        <!-- Visual Editor (WYSIWYG) -->
                        <div id="visualEditorContainer">
                            <div class="wysiwyg-toolbar">
                                <button type="button" onclick="execCmd('bold')" title="Bold"><i class="bi bi-type-bold"></i></button>
                                <button type="button" onclick="execCmd('italic')" title="Italic"><i class="bi bi-type-italic"></i></button>
                                <button type="button" onclick="execCmd('underline')" title="Underline"><i class="bi bi-type-underline"></i></button>
                                <button type="button" onclick="execCmd('strikeThrough')" title="Strikethrough"><i class="bi bi-type-strikethrough"></i></button>
                                <div class="separator"></div>
                                <button type="button" onclick="execCmd('insertUnorderedList')" title="Bullet List"><i class="bi bi-list-ul"></i></button>
                                <button type="button" onclick="execCmd('insertOrderedList')" title="Numbered List"><i class="bi bi-list-ol"></i></button>
                                <div class="separator"></div>
                                <button type="button" onclick="execCmd('formatBlock', 'h2')" title="Heading 2">H2</button>
                                <button type="button" onclick="execCmd('formatBlock', 'h3')" title="Heading 3">H3</button>
                                <button type="button" onclick="execCmd('formatBlock', 'p')" title="Paragraph">P</button>
                                <div class="separator"></div>
                                <button type="button" onclick="execCmd('justifyLeft')" title="Align Left"><i class="bi bi-text-left"></i></button>
                                <button type="button" onclick="execCmd('justifyCenter')" title="Align Center"><i class="bi bi-text-center"></i></button>
                                <button type="button" onclick="execCmd('justifyRight')" title="Align Right"><i class="bi bi-text-right"></i></button>
                                <div class="separator"></div>
                                <button type="button" onclick="insertLink()" title="Insert Link"><i class="bi bi-link-45deg"></i></button>
                                <button type="button" onclick="execCmd('removeFormat')" title="Clear Formatting"><i class="bi bi-x-lg"></i></button>
                            </div>
                            <div id="contentEditor" class="wysiwyg-editor" contenteditable="true" data-placeholder="Write your blog content here..."><?= old('content') ?></div>
                        </div> <!-- End visualEditorContainer -->
                        
                        <!-- Code Editor (HTML Source) -->
                        <div id="codeEditorContainer" style="display: none;">
                            <textarea id="codeEditor" class="form-control" rows="20" style="font-family: monospace; font-size: 13px;" placeholder="Enter HTML code..."></textarea>
                        </div>
                        
                        <input type="hidden" name="content" id="contentInput">
                        <small class="text-muted">Use the toolbar above to format your content</small>
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Excerpt</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3"
                                  placeholder="Short description for previews (optional)"><?= old('excerpt') ?></textarea>
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image URL</label>
                        <input type="url" class="form-control" id="featured_image" name="featured_image" 
                               value="<?= old('featured_image') ?>"
                               placeholder="https://example.com/image.jpg">
                        <?php if (old('featured_image')): ?>
                            <div class="mt-2">
                                <img src="<?= old('featured_image') ?>" alt="Featured Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Publish Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Publish</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                                        <?= esc($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-select" id="tags" name="tags[]" multiple>
                            <?php if (!empty($tags)): ?>
                                <?php foreach ($tags as $tag): ?>
                                    <option value="<?= $tag['id'] ?>" <?= in_array($tag['id'], old('tags', [])) ? 'selected' : '' ?>>
                                        <?= esc($tag['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text">Hold Ctrl/Cmd to select multiple</div>
                    </div>

                    <div class="mb-3">
                        <label for="published_at" class="form-label">Publish Date</label>
                        <input type="datetime-local" class="form-control" id="published_at" name="published_at" 
                               value="<?= old('published_at') ?>">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" 
                               <?= old('is_published') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_published">Publish immediately</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                               <?= old('is_featured') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_featured">Feature this post</label>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Create Post
                    </button>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">SEO Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                               value="<?= old('meta_title') ?>" maxlength="70"
                               placeholder="SEO title (recommended: 60-70 characters)">
                        <div class="form-text"><span id="meta_title_count">0</span>/70 characters</div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3"
                                  maxlength="160" placeholder="SEO description (recommended: 150-160 characters)"><?= old('meta_description') ?></textarea>
                        <div class="form-text"><span id="meta_description_count">0</span>/160 characters</div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                               value="<?= old('meta_keywords') ?>"
                               placeholder="keyword1, keyword2, keyword3">
                    </div>
                </div>
            </div>

            <!-- AI Features -->
            <?php if (config('Blog')->enableAI ?? false): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">AI Assistant</h5>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" id="ai_generate_summary">
                        <i class="bi bi-magic me-1"></i> Generate Summary
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" id="ai_extract_keywords">
                        <i class="bi bi-tags me-1"></i> Extract Keywords
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
// WYSIWYG Editor Functions
function execCmd(command, value = null) {
    document.execCommand(command, false, value);
    document.getElementById('contentEditor').focus();
    updateToolbarState();
}

function insertLink() {
    var url = prompt('Enter URL:');
    if (url) {
        document.execCommand('createLink', false, url);
    }
}

function updateToolbarState() {
    // Update button states based on current formatting
    var buttons = document.querySelectorAll('.wysiwyg-toolbar button');
    buttons.forEach(function(btn) {
        var command = btn.getAttribute('onclick');
        if (command && command.includes('execCmd')) {
            var cmd = command.match(/execCmd\('(\w+)'/);
            if (cmd && document.queryCommandState(cmd[1])) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        }
    });
}

// Listen for selection changes to update toolbar
document.addEventListener('selectionchange', function() {
    updateToolbarState();
});

// Sync content before form submission
document.getElementById('blogForm').addEventListener('submit', function(e) {
    var mode = document.querySelector('input[name="editorMode"]:checked').value;
    var content;
    
    if (mode === 'visual') {
        content = document.getElementById('contentEditor').innerHTML;
    } else {
        content = document.getElementById('codeEditor').value;
    }
    
    document.getElementById('contentInput').value = content;
    
    // Check if content is empty
    if (content.trim() === '' || content === '<br>') {
        e.preventDefault();
        alert('Please enter content for the blog post.');
        return false;
    }
});

// Editor mode toggle
document.querySelectorAll('input[name="editorMode"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var visualContainer = document.getElementById('visualEditorContainer');
        var codeContainer = document.getElementById('codeEditorContainer');
        
        if (this.value === 'visual') {
            // Switch to visual mode - copy code to visual editor
            visualContainer.style.display = 'block';
            codeContainer.style.display = 'none';
            document.getElementById('contentEditor').innerHTML = document.getElementById('codeEditor').value;
        } else {
            // Switch to code mode - copy visual to code editor
            visualContainer.style.display = 'none';
            codeContainer.style.display = 'block';
            document.getElementById('codeEditor').value = document.getElementById('contentEditor').innerHTML;
        }
    });
});

// Add placeholder support for contenteditable
document.getElementById('contentEditor').addEventListener('focus', function() {
    this.removeAttribute('data-placeholder-active');
});

document.getElementById('contentEditor').addEventListener('blur', function() {
    if (this.innerHTML.trim() === '') {
        this.innerHTML = '';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('blur', function() {
        if (!slugInput.value) {
            slugInput.value = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        }
    });

    // Character count for meta fields
    const metaTitle = document.getElementById('meta_title');
    const metaTitleCount = document.getElementById('meta_title_count');
    if (metaTitle) {
        metaTitle.addEventListener('input', function() {
            metaTitleCount.textContent = this.value.length;
        });
        metaTitleCount.textContent = metaTitle.value.length;
    }

    const metaDesc = document.getElementById('meta_description');
    const metaDescCount = document.getElementById('meta_description_count');
    if (metaDesc) {
        metaDesc.addEventListener('input', function() {
            metaDescCount.textContent = this.value.length;
        });
        metaDescCount.textContent = metaDesc.value.length;
    }

    // AI Features
    const generateSummaryBtn = document.getElementById('ai_generate_summary');
    if (generateSummaryBtn) {
        generateSummaryBtn.addEventListener('click', function() {
            const content = document.getElementById('contentEditor').innerHTML;
            if (!content || content === '<br>') {
                alert('Please enter some content first');
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';
            
            fetch('<?= base_url('admin/blog/ai/generate-summary') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': '<?= csrf_hash() ?>'
                },
                body: 'content=' + encodeURIComponent(content)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('excerpt').value = data.summary;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error generating summary');
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-magic me-1"></i> Generate Summary';
            });
        });
    }

    const extractKeywordsBtn = document.getElementById('ai_extract_keywords');
    if (extractKeywordsBtn) {
        extractKeywordsBtn.addEventListener('click', function() {
            const content = document.getElementById('contentEditor').innerHTML;
            if (!content || content === '<br>') {
                alert('Please enter some content first');
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Extracting...';
            
            fetch('<?= base_url('admin/blog/ai/extract-keywords') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': '<?= csrf_hash() ?>'
                },
                body: 'content=' + encodeURIComponent(content)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('meta_keywords').value = data.keywords;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error extracting keywords');
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-tags me-1"></i> Extract Keywords';
            });
        });
    }
});
</script>

<?= $this->endSection() ?>