<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('styles') ?>
<!-- QRCode.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
    /* QR Code specific styles */
    #qrcode img {
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .color-picker-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .color-picker-wrapper:hover {
        background-color: var(--light-red);
    }
    
    .color-picker-wrapper input[type="color"] {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background: transparent;
    }
    
    .tab-btn {
        flex: 1;
        padding: 12px 16px;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        background: transparent;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .tab-btn.active {
        color: var(--dark-red);
        border-bottom-color: var(--dark-red);
    }
    
    .tab-btn:not(.active) {
        color: var(--light-text);
        border-bottom-color: transparent;
    }
    
    .tab-btn:hover:not(.active) {
        color: var(--dark-red);
    }
    
    .output-container {
        display: none;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 12px;
        border: 2px dashed var(--border-color);
    }
    
    .output-container.show {
        display: flex;
    }
    
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #333;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(80px);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        z-index: 1050;
    }
    
    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .toast.error {
        background-color: #dc3545;
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
                <li class="breadcrumb-item active">QR Code Generator</li>
            </ol>
        </nav>
        <h4 class="fw-bold">QR Code Generator</h4>
        <p class="text-muted mb-0">Create custom QR codes for URLs, text, or WhatsApp links.</p>
    </div>
</div>

<!-- QR Generator Card -->
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="dashboard-card">
            <!-- Tabs Navigation -->
            <div class="d-flex border-bottom">
                <button type="button" onclick="switchTab('text')" id="tab-btn-text" class="tab-btn active">
                    Text / URL
                </button>
                <button type="button" onclick="switchTab('wa')" id="tab-btn-wa" class="tab-btn">
                    WhatsApp
                </button>
            </div>
            
            <div class="card-body p-4">
                <!-- Tab Content: Text/URL -->
                <div id="content-text" class="mb-4">
                    <label for="text-input" class="form-label fw-medium">Text or URL</label>
                    <input type="text" id="text-input" class="form-control form-control-lg" 
                        placeholder="Example: https://google.com or Hello World" autocomplete="off">
                </div>
                
                <!-- Tab Content: WhatsApp -->
                <div id="content-wa" class="mb-4 d-none">
                    <div class="mb-3">
                        <label for="wa-number" class="form-label fw-medium">WhatsApp Number</label>
                        <input type="tel" id="wa-number" class="form-control" 
                            placeholder="Example: 628123456789" autocomplete="off">
                        <div class="form-text">Use country code (e.g., 62 for Indonesia) without the + sign.</div>
                    </div>
                    <div>
                        <label for="wa-message" class="form-label fw-medium">Auto Message</label>
                        <textarea id="wa-message" rows="3" class="form-control" 
                            placeholder="Hello, I would like to ask about your product..."></textarea>
                    </div>
                </div>
                
                <!-- Color Options -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="form-label fw-medium">QR Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="color-dark" value="#000000">
                            <span class="text-muted small">Pick color</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-medium">Background</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="color-light" value="#ffffff">
                            <span class="text-muted small">Pick color</span>
                        </div>
                    </div>
                </div>
                
                <!-- Generate Button -->
                <button type="button" onclick="generateQR()" class="btn btn-lg w-100 text-white" 
                    style="background-color: var(--dark-red);">
                    <i class="bi bi-qr-code me-2"></i>Generate QR Code
                </button>
                
                <!-- Output Area -->
                <div id="output-container" class="output-container mt-4">
                    <div id="qrcode" class="p-2 bg-white rounded shadow-sm"></div>
                    
                    <div class="d-flex gap-2 w-100">
                        <button type="button" onclick="downloadQR()" class="btn btn-success flex-grow-1">
                            <i class="bi bi-download me-2"></i>Download PNG
                        </button>
                        <button type="button" onclick="clearQR()" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast">Notification message</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Initialize variables
let activeTab = 'text'; // 'text' or 'wa'

const textInput = document.getElementById('text-input');
const waNumberInput = document.getElementById('wa-number');
const waMessageInput = document.getElementById('wa-message');

const colorDarkInput = document.getElementById('color-dark');
const colorLightInput = document.getElementById('color-light');
const qrcodeContainer = document.getElementById('qrcode');
const outputContainer = document.getElementById('output-container');
let qrcodeObj = null;

// Function: Switch Tab
function switchTab(tab) {
    activeTab = tab;
    const btnText = document.getElementById('tab-btn-text');
    const btnWa = document.getElementById('tab-btn-wa');
    const contentText = document.getElementById('content-text');
    const contentWa = document.getElementById('content-wa');

    if (tab === 'text') {
        // Style Button
        btnText.classList.add('active');
        btnWa.classList.remove('active');

        // Show/Hide Content
        contentText.classList.remove('d-none');
        contentWa.classList.add('d-none');
    } else {
        // Style Button
        btnWa.classList.add('active');
        btnText.classList.remove('active');

        // Show/Hide Content
        contentWa.classList.remove('d-none');
        contentText.classList.add('d-none');
    }

    // Hide output when switching tabs
    outputContainer.classList.remove('show');
}

// Function: Generate QR Code
function generateQR() {
    let finalText = '';

    if (activeTab === 'text') {
        finalText = textInput.value.trim();
        if (!finalText) {
            showToast('Please enter text or URL!', true);
            textInput.focus();
            return;
        }
    } else if (activeTab === 'wa') {
        let phone = waNumberInput.value.replace(/[^0-9]/g, ''); // Numbers only
        const message = waMessageInput.value.trim();

        if (!phone) {
            showToast('Please enter WhatsApp number!', true);
            waNumberInput.focus();
            return;
        }

        // Normalize number (if user inputs 08..., change to 628...)
        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1);
        }

        // Format WhatsApp API link
        finalText = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    }

    // Clear old QR code
    qrcodeContainer.innerHTML = '';
    outputContainer.classList.add('show');

    // Generate new QR Code
    try {
        qrcodeObj = new QRCode(qrcodeContainer, {
            text: finalText,
            width: 200,
            height: 200,
            colorDark: colorDarkInput.value,
            colorLight: colorLightInput.value,
            correctLevel: QRCode.CorrectLevel.H
        });

        showToast('QR Code generated successfully!');

        // Scroll to result
        setTimeout(() => {
            outputContainer.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }, 100);
    } catch (error) {
        console.error(error);
        showToast('Error generating QR Code.', true);
    }
}

// Function: Download
function downloadQR() {
    setTimeout(() => {
        const img = qrcodeContainer.querySelector('img');

        if (img && img.src) {
            const link = document.createElement('a');
            link.href = img.src;
            link.download = `qrcode-${activeTab}-${Date.now()}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('Image downloading...');
        } else {
            // Fallback for canvas render
            const canvas = qrcodeContainer.querySelector('canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = `qrcode-${activeTab}-${Date.now()}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                showToast('Image downloading...');
            } else {
                showToast('Image not ready, please try again.', true);
            }
        }
    }, 100);
}

// Function: Reset
function clearQR() {
    if (activeTab === 'text') {
        textInput.value = '';
        textInput.focus();
    } else {
        waNumberInput.value = '';
        waMessageInput.value = '';
        waNumberInput.focus();
    }

    qrcodeContainer.innerHTML = '';
    outputContainer.classList.remove('show');
}

// Helper: Show toast notification
function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    toast.textContent = message;

    if (isError) {
        toast.classList.add('error');
    } else {
        toast.classList.remove('error');
    }

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Event Listeners for Enter key
textInput.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        generateQR();
    }
});

waNumberInput.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        generateQR();
    }
});
</script>
<?= $this->endSection() ?>