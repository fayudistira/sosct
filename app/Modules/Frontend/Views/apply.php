<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="hero-section py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Daftar Sekarang</h1>
        <p class="lead">Mulai perjalanan belajar Anda bersama kami dengan mengisi formulir pendaftaran di bawah ini</p>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <?php if (isset($user) && $user && $user->inGroup('superadmin')): ?>
        <div class="row mb-4">
            <div class="col">
                <div class="alert alert-warning border-warning shadow-sm d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1 fw-bold text-dark"><i class="bi bi-magic me-2"></i>Superadmin Tool: Autofill Form</h6>
                        <p class="small mb-0 opacity-75">
                            Upload a <code>.txt</code> file to test this form.
                            <a href="<?= base_url('templates/admission_autofill_template.txt') ?>" download class="text-decoration-none ms-1 fw-bold text-dark">
                                <i class="bi bi-download me-1"></i>Download Template
                            </a>
                        </p>
                    </div>
                    <div style="width: 250px;">
                        <input type="file" id="autofill_file" class="form-control form-control-sm" accept=".txt,.json">
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const autofillFile = document.getElementById('autofill_file');
                console.log('Autofill file element:', autofillFile);

                if (!autofillFile) {
                    console.error('Autofill file input not found!');
                    return;
                }

                autofillFile.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    console.log('Processing file:', file.name);

                    // Check file type
                    if (file.type !== 'application/json' && !file.name.endsWith('.txt') && !file.name.endsWith('.json')) {
                        alert('Please upload a .txt or .json file.\\nDetected file type: ' + file.type);
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            const rawContent = e.target.result;
                            console.log('File content length:', rawContent.length);

                            // Try to parse JSON
                            const data = JSON.parse(rawContent);
                            console.log('Parsed JSON data:', data);

                            const form = document.querySelector('form[action$="apply/submit"]');
                            console.log('Form found:', !!form);

                            if (!form) {
                                alert('Form not found!\\nCheck console for details.');
                                console.log('All forms on page:', Array.from(document.querySelectorAll('form')).map(f => f.action));
                                return;
                            }

                            const inputEl = event.target;
                            let filledCount = 0;
                            let notFound = [];

                            for (const key in data) {
                                const input = form.querySelector(`[name="${key}"], [name="${key}[]"]`);
                                console.log(`Field "${key}":`, input ? 'FOUND' : 'NOT FOUND');

                                if (input) {
                                    if (input.type === 'checkbox' || input.type === 'radio') {
                                        if (input.value == data[key]) input.checked = true;
                                    } else if (input.tagName === 'SELECT') {
                                        // Specific handling for 'course' to match by ID or Title
                                        if (key === 'course') {
                                            let found = false;
                                            Array.from(input.options).forEach(opt => {
                                                if (opt.value == data[key] || opt.textContent.trim().includes(data[key]) || (opt.dataset.title && opt.dataset.title == data[key])) {
                                                    input.value = opt.value;
                                                    found = true;
                                                }
                                            });
                                            if (!found) console.warn('Program not found in dropdown:', data[key]);
                                        } else {
                                            input.value = data[key];
                                        }
                                        input.dispatchEvent(new Event('change'));
                                    } else if (input.type !== 'file') {
                                        console.log(`Setting "${key}" = "${data[key]}"`);
                                        input.value = data[key];
                                        filledCount++;
                                    }
                                } else {
                                    notFound.push(key);
                                }
                            }

                            if (notFound.length > 0) {
                                console.warn('Fields not in form:', notFound);
                            }

                            // Show feedback
                            const feedback = document.createElement('div');
                            feedback.className = 'alert alert-success mt-2 mb-0 py-2 small fw-medium';
                            feedback.innerHTML = `<i class="bi bi-check-circle me-1"></i> Form autofilled with ${filledCount} values!`;
                            inputEl.parentElement.appendChild(feedback);

                            console.log('Total fields filled:', filledCount);

                            inputEl.value = '';
                            setTimeout(() => feedback.remove(), 4000);

                        } catch (err) {
                            console.error('JSON Parse Error:', err);
                            alert('Error parsing JSON: ' + err.message + '\\nCheck console for details.');
                        }
                    };
                    reader.readAsText(file);
                });
            });
        </script>
    <?php endif; ?>

    <?php if (session('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <?php if (session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Selected Program Banner -->
    <?php if (isset($selectedProgram) && $selectedProgram): ?>
        <div class="alert alert-info border-0 shadow-sm mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="alert-heading mb-2">
                        <i class="bi bi-mortarboard me-2"></i>Mendaftar ke: <?= esc($selectedProgram['title']) ?>
                    </h5>
                    <p class="mb-0">
                        <strong>Kategori:</strong> <?= esc($selectedProgram['category'] ?? 'N/A') ?> |
                        <strong>Biaya Kursus:</strong>
                        <?php if ($selectedProgram['discount'] > 0): ?>
                            <span class="text-decoration-line-through">Rp <?= number_format($selectedProgram['tuition_fee'], 0, ',', '.') ?></span>
                            <span class="text-success fw-bold">
                                Rp <?= number_format($selectedProgram['tuition_fee'] * (1 - $selectedProgram['discount'] / 100), 0, ',', '.') ?>
                            </span>
                            <span class="badge bg-success"><?= number_format($selectedProgram['discount'], 0) ?>% OFF</span>
                        <?php else: ?>
                            <span class="fw-bold">Rp <?= number_format($selectedProgram['tuition_fee'], 0, ',', '.') ?></span>
                        <?php endif ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <a href="<?= base_url('programs/' . $selectedProgram['id']) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-info-circle me-1"></i>Lihat Detail Program
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>

    <form action="<?= base_url('apply/submit') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Personal Information -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-person me-2"></i>Data Pribadi</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= old('full_name') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nickname" class="form-label">Nickname</label>
                        <input type="text" class="form-control" id="nickname" name="nickname" value="<?= old('nickname') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Male" <?= old('gender') === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= old('gender') === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="place_of_birth" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="<?= old('place_of_birth') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="religion" class="form-label">Agama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="religion" name="religion" value="<?= old('religion') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="citizen_id" class="form-label">Nomor KTP</label>
                        <input type="text" class="form-control" id="citizen_id" name="citizen_id" value="<?= old('citizen_id') ?>">
                        <small class="text-muted">Optional - Only if you have an ID card</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-telephone me-2"></i>Contact Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Alamat</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="street_address" class="form-label">Alamat Jalan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="street_address" name="street_address" rows="2" required><?= old('street_address') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="district" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="district" name="district" value="<?= old('district') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="regency" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="regency" name="regency" value="<?= old('regency') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="province" class="form-label">Provinsi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="province" name="province" value="<?= old('province') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= old('postal_code') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Kontak Darurat</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="emergency_contact_name" class="form-label">Nama Kontak <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="<?= old('emergency_contact_name') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="emergency_contact_phone" class="form-label">No. Telepon Kontak <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" value="<?= old('emergency_contact_phone') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="emergency_contact_relation" class="form-label">Hubungan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation" value="<?= old('emergency_contact_relation') ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family Information -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-people me-2"></i>Family Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="father_name" class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="father_name" name="father_name" value="<?= old('father_name') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="mother_name" class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?= old('mother_name') ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Selection -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Pemilihan Program</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="course" class="form-label">Program yang Dipilih <span class="text-danger">*</span></label>
                    <?php if (isset($selectedProgram) && $selectedProgram): ?>
                        <!-- Pre-selected program (read-only) -->
                        <input type="text" class="form-control" value="<?= esc($selectedProgram['title']) ?>" readonly style="background-color: #f8f9fa;">
                        <input type="hidden" name="program_id" value="<?= esc($selectedProgram['id']) ?>">
                        <input type="hidden" name="course" value="<?= esc($selectedProgram['title']) ?>">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Anda mendaftar untuk program ini.
                            <a href="<?= base_url('apply') ?>">Klik di sini</a> untuk memilih program lain.
                        </small>
                    <?php else: ?>
                        <!-- Dropdown for program selection -->
                        <select class="form-select" id="course" name="course" required>
                            <option value="">Pilih Program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?= esc($program['id']) ?>" data-title="<?= esc($program['title']) ?>" <?= old('course') === $program['id'] ? 'selected' : '' ?>>
                                    <?= esc($program['title']) ?>
                                    <?php if ($program['discount'] > 0): ?>
                                        (<?= number_format($program['discount'], 0) ?>% OFF)
                                    <?php endif ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Tidak yakin program yang cocok? <a href="<?= base_url('programs') ?>" target="_blank">Lihat program kami</a>
                        </small>
                    <?php endif ?>
                </div>
                <?php
                // Generate start date options: 10th of each month for current and next year
                $startDateOptions = [];
                $currentYear = date('Y');
                $nextYear = $currentYear + 1;
                
                for ($year = $currentYear; $year <= $nextYear; $year++) {
                    for ($month = 1; $month <= 12; $month++) {
                        // Skip past months in current year
                        if ($year == $currentYear && $month < date('n')) {
                            continue;
                        }
                        
                        // Find the 10th day of the month
                        $tenthDay = mktime(0, 0, 0, $month, 10, $year);
                        $dayOfWeek = date('N', $tenthDay);
                        
                        // If 10th is Friday (5), Saturday (6), or Sunday (7), move to next Monday
                        if ($dayOfWeek >= 5) {
                            $daysUntilMonday = 8 - $dayOfWeek; // Days until next Monday
                            $tenthDay = strtotime("+{$daysUntilMonday} days", $tenthDay);
                        }
                        
                        $dateValue = date('Y-m-d', $tenthDay);
                        $displayDate = date('d F Y', $tenthDay);
                        $startDateOptions[$dateValue] = $displayDate;
                    }
                }
                ?>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai Dimulai <span class="text-danger">*</span></label>
                    <select class="form-select" id="start_date" name="start_date" required>
                        <option value="">Pilih Tanggal Mulai</option>
                        <?php foreach ($startDateOptions as $value => $label): ?>
                            <option value="<?= $value ?>" <?= old('start_date') === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Kelas dimulai pada tanggal 10 setiap bulan. Jika tanggal 10 jatuh pada akhir pekan, kelas akan dimulai pada Senin berikutnya.
                    </small>
                </div>
            </div>
        </div>

        <!-- File Uploads -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-file-earmark-arrow-up me-2"></i>Unggah Berkas</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="photo" class="form-label">Foto Profil <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/jpg,image/png,image/webp" required>
                        <small class="text-muted">Diterima: JPG, PNG, WebP. Maks: 2MB. Foto akan dikonversi ke WebP untuk optimasi.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="documents" class="form-label">Berkas Pendukung</label>
                        <input type="file" class="form-control" id="documents" name="documents[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,image/*" multiple>
                        <small class="text-muted">Diterima: PDF, DOC, DOCX, JPG, PNG, GIF. Maks: 5MB per berkas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="card-custom card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Informasi Tambahan</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan Tambahan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Informasi tambahan yang ingin Anda sampaikan..."><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-dark-red btn-lg px-5" id="submitBtn">
                <i class="bi bi-send me-2"></i>Kirim Pendaftaran
            </button>
            <a href="<?= base_url('/') ?>" class="btn btn-outline-dark-red btn-lg px-5 ms-2">
                <i class="bi bi-x-circle me-2"></i>Batal
            </a>
        </div>
    </form>
</div>

<script>
    // Disable submit button when clicked to prevent multiple submissions
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action$="apply/submit"]');
        const submitBtn = document.getElementById('submitBtn');

        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                // Disable the submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';
                submitBtn.classList.add('disabled');

                // Allow the form to submit normally
                // The button will remain disabled until the page reloads
            });
        }
    });
</script>
<?= $this->endSection() ?>