<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('styles') ?>
<style>
    /* Image Converter specific styles */
    .upload-zone {
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #fafafa;
    }
    
    .upload-zone:hover {
        border-color: var(--dark-red);
        background-color: var(--light-red);
    }
    
    .upload-zone.dragover {
        border-color: var(--dark-red);
        background-color: var(--light-red);
    }
    
    .quality-slider {
        width: 100%;
        max-width: 300px;
    }
    
    .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        width: 100%;
    }
    
    .image-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        text-align: left;
        transition: transform 0.2s;
    }
    
    .image-card:hover {
        transform: translateY(-3px);
    }
    
    .preview-box {
        height: 180px;
        background: #eee;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .preview-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .file-name-display {
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        margin-bottom: 5px;
    }
    
    .stat-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 4px;
    }
    
    .stat-val {
        font-weight: 600;
        color: #333;
    }
    
    .savings-positive {
        color: #27ae60;
        font-weight: bold;
        font-size: 0.9rem;
        text-align: center;
        margin: 10px 0;
    }
    
    .savings-negative {
        color: #e74c3c;
        font-weight: bold;
        font-size: 0.9rem;
        text-align: center;
        margin: 10px 0;
    }
    
    .btn-download {
        display: block;
        width: 100%;
        background-color: var(--dark-red);
        color: white;
        text-align: center;
        padding: 10px 0;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.2s;
        box-sizing: border-box;
    }
    
    .btn-download:hover {
        background-color: var(--medium-red);
        color: white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= base_url('tools') ?>">Tools</a></li>
                <li class="breadcrumb-item active">Image Converter</li>
            </ol>
        </nav>
        <h4 class="fw-bold">Image Converter</h4>
        <p class="text-muted mb-0">Convert images to WebP format with batch processing. All processing happens locally in your browser.</p>
    </div>
</div>

<!-- Image Converter Card -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="dashboard-card">
            <div class="card-body p-4">
                <!-- Upload Zone -->
                <div class="upload-zone mb-4" id="uploadZone">
                    <i class="bi bi-cloud-upload" style="font-size: 3rem; color: var(--light-text);"></i>
                    <h5 class="mt-3 mb-1">Click to select images or drag & drop</h5>
                    <p class="text-muted small mb-0">Supports PNG, JPG, JPEG (Batch processing)</p>
                    <input type="file" id="fileInput" accept="image/png, image/jpeg, image/jpg" multiple style="display: none;">
                </div>
                
                <!-- Quality Control -->
                <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                    <label for="quality" class="fw-medium">Quality:</label>
                    <input type="range" id="quality" class="quality-slider form-range" min="0.1" max="1.0" step="0.1" value="0.8">
                    <span id="qualityValue" class="fw-bold" style="min-width: 40px;">80%</span>
                </div>
                <p class="text-center text-muted small">Adjusting quality re-processes all images immediately.</p>
            </div>
        </div>
        
        <!-- Results Grid -->
        <div class="results-grid" id="resultsGrid"></div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const fileInput = document.getElementById('fileInput');
const uploadZone = document.getElementById('uploadZone');
const resultsGrid = document.getElementById('resultsGrid');
const qualityInput = document.getElementById('quality');
const qualityValue = document.getElementById('qualityValue');

// Store uploaded files for re-processing
let uploadedFiles = [];

function formatBytes(bytes, decimals = 1) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function processBatch(files, quality) {
    resultsGrid.innerHTML = ''; // Clear current grid
    
    Array.from(files).forEach((file, index) => {
        // Create Card Placeholder immediately
        const card = document.createElement('div');
        card.className = 'image-card';
        card.innerHTML = `
            <div class="preview-box">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="file-info">
                <span class="file-name-display">${file.name}</span>
            </div>
        `;
        resultsGrid.appendChild(card);

        // Read and Convert
        const reader = new FileReader();
        reader.readAsDataURL(file);
        
        reader.onload = function(event) {
            const img = new Image();
            img.src = event.target.result;
            
            img.onload = function() {
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                canvas.toBlob((blob) => {
                    const url = URL.createObjectURL(blob);
                    const savedPercent = ((file.size - blob.size) / file.size * 100).toFixed(0);
                    const isSaved = savedPercent > 0;
                    
                    // Update the card HTML
                    card.innerHTML = `
                        <div class="preview-box">
                            <img src="${url}" alt="preview">
                        </div>
                        <div class="file-info">
                            <span class="file-name-display" title="${file.name}">${file.name}</span>
                            <div class="stat-row">
                                <span>Original:</span> <span class="stat-val">${formatBytes(file.size)}</span>
                            </div>
                            <div class="stat-row">
                                <span>WebP:</span> <span class="stat-val">${formatBytes(blob.size)}</span>
                            </div>
                        </div>
                        <div class="${isSaved ? 'savings-positive' : 'savings-negative'}">
                            ${isSaved ? '⬇ ' + savedPercent + '% Size Reduction' : '⬆ Size Increased'}
                        </div>
                        <a href="${url}" download="${file.name.split('.')[0]}.webp" class="btn-download">
                            <i class="bi bi-download me-2"></i>Download
                        </a>
                    `;
                }, 'image/webp', parseFloat(quality));
            }
        }
    });
}

// Handle File Selection
fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        uploadedFiles = e.target.files;
        processBatch(uploadedFiles, qualityInput.value);
    }
});

// Handle click on upload zone
uploadZone.addEventListener('click', () => {
    fileInput.click();
});

// Handle drag and drop
uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('dragover');
});

uploadZone.addEventListener('dragleave', () => {
    uploadZone.classList.remove('dragover');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        uploadedFiles = files;
        processBatch(uploadedFiles, qualityInput.value);
    }
});

// Handle Quality Slider Change
qualityInput.addEventListener('change', (e) => {
    qualityValue.textContent = Math.round(e.target.value * 100) + '%';
    if (uploadedFiles.length > 0) {
        processBatch(uploadedFiles, e.target.value);
    }
});

// Update number while dragging slider
qualityInput.addEventListener('input', (e) => {
    qualityValue.textContent = Math.round(e.target.value * 100) + '%';
});
</script>
<?= $this->endSection() ?>