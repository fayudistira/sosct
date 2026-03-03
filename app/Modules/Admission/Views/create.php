<?= $this->extend('Modules\Dashboard\Views\layout') ?>

<?= $this->section('content') ?>

<!-- Terms Modal CSS -->
<style>
.terms-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.terms-modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.terms-modal-content {
    background-color: #fff;
    border-radius: 12px;
    width: 90%;
    max-width: 700px;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.terms-modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #8B0000 0%, #a52a2a 100%);
    border-radius: 12px 12px 0 0;
    color: white;
}

.terms-modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.terms-modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 5px;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.terms-modal-close:hover {
    opacity: 1;
}

.terms-modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

.terms-modal-body h3 {
    color: #8B0000;
    font-size: 1.1rem;
    margin-bottom: 12px;
    margin-top: 20px;
}

.terms-modal-body h3:first-child {
    margin-top: 0;
}

.terms-modal-body h4 {
    color: #333;
    font-size: 1rem;
    margin-bottom: 10px;
    margin-top: 15px;
}

.terms-modal-body p {
    color: #555;
    line-height: 1.6;
    margin-bottom: 12px;
}

.terms-modal-body ul {
    padding-left: 20px;
    color: #555;
    line-height: 1.7;
}

.terms-modal-body ul ul {
    margin-top: 5px;
}

.terms-modal-body hr {
    border: none;
    border-top: 1px solid #e0e0e0;
    margin: 20px 0;
}

.terms-modal-body ol {
    padding-left: 20px;
}

.terms-modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e0e0e0;
    background-color: #f8f9fa;
    border-radius: 0 0 12px 12px;
}

.terms-modal-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 15px;
}

.terms-modal-checkbox input[type="checkbox"] {
    margin-top: 3px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.terms-modal-checkbox label {
    color: #555;
    font-size: 0.95rem;
    cursor: pointer;
    line-height: 1.5;
}

.terms-modal-footer .send-btn {
    width: 100%;
    padding: 12px 24px;
    background: linear-gradient(135deg, #8B0000 0%, #a52a2a 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.terms-modal-footer .send-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(139, 0, 0, 0.4);
}

.terms-modal-footer .send-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Form hidden state */
#admissionFormContent {
    display: none;
}

#admissionFormContent.show {
    display: block;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<!-- Terms Modal HTML -->
<div class="terms-modal show" id="termsModal">
    <div class="terms-modal-content">
        <div class="terms-modal-header">
            <h2>Syarat dan Ketentuan</h2>
            <button class="terms-modal-close" id="termsModalClose" type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="terms-modal-body">
            <h3>1. Pendaftaran</h3>
            <p>
                Pendaftaran kursus di Xihuan Mandarin Indonesia bersifat online
                melalui formulir pendaftaran yang tersedia. Calon siswa wajib
                mengisi formulir dengan data yang benar dan lengkap.
            </p>
            <hr />
            <h3>2. Pembayaran</h3>
            <ul>
                <li>
                    Pembayaran biaya pendaftaran harus dilakukan setelah mengisi
                    formulir pendaftaran.
                </li>
                <li>
                    Pembayaran dapat dilakukan melalui transfer bank ke rekening yang
                    telah ditentukan.
                </li>
                <li>
                    Bukti pembayaran <strong>WAJIB</strong> dikirimkan melalui
                    WhatsApp kepada admin.
                </li>
                <li>
                    Admin akan memberikan Kuitansi Pembayaran setelah pembayaran
                    <strong>Terkonfirmasi</strong> dan dianggap sah.
                </li>
                <li>
                    Pembayaran tidak dapat dikembalikan (non-refundable) dengan alasan
                    apapun.
                </li>
            </ul>
            <hr />
            <h4>2.1 Prosedur Pembayaran Kelas Paket 3 Bulan</h4>
            <ul>
                <li>
                    Biaya Pendaftaran sebesar
                    <strong>Rp. 500.000,-</strong> dibayarkan setelah mengisi Formulir
                    Pendaftaran
                </li>
                <li>
                    Pembayaran program dapat dilakukan sebelum pembelajaran dimulai.
                </li>
                <li>
                    Pembayaran dapat dilakukan dengan cicilan 2 tahap sebagai berikut:
                    <ul>
                        <li>
                            Pembayaran <strong>Tahap 1 : Rp. 4.000.000,-</strong>
                            dibayarkan ketika check-in asrama
                        </li>
                        <li>
                            Pembayaran
                            <strong>Tahap 2 (Pelunasan) : Rp. 3.999.000,-</strong>
                            dibayarkan sebelum memasuki bulan ke-2
                        </li>
                    </ul>
                </li>
            </ul>
            <hr />
            <h4>2.2 Prosedur Pembayaran Kelas Paket 5 Bulan</h4>
            <ul>
                <li>
                    Biaya Pendaftaran sebesar <strong>Rp. 500.000,-</strong>
                    dibayarkan setelah mengisi Formulir Pendaftaran
                </li>
                <li>
                    Pembayaran program dapat dilakukan sebelum pembelajaran dimulai.
                </li>
                <li>
                    Pembayaran dapat dilakukan dengan cicilan 3 tahap sebagai berikut:
                    <ul>
                        <li>
                            Pembayaran <strong>Tahap 1 : Rp. 4.000.000,-</strong>
                            dibayarkan ketika check in asrama
                        </li>
                        <li>
                            Pembayaran <strong>Tahap 2 : Rp. 4.000.000,-</strong>
                            dibayarkan sebelum memasuki bulan ke-2
                        </li>
                        <li>
                            Pembayaran
                            <strong>Tahap 3 (Pelunasan) : Rp. 3.999.000,-</strong>
                            dibayarkan sebelum memasuki bulan ke-3
                        </li>
                    </ul>
                </li>
            </ul>
            <hr />
            <h4>2.3 Prosedur Pembayaran Kelas Paket 7 Bulan</h4>
            <ul>
                <li>
                    Biaya Pendaftaran sebesar <strong>Rp. 500.000,-</strong>
                    ketika mengisi Formulir Pendaftaran
                </li>
                <li>
                    Pembayaran program dapat dilakukan sebelum pembelajaran dimulai.
                </li>
                <li>
                    Pembayaran dapat dilakukan dengan cicilan 3 tahap sebagai berikut:
                </li>
                <ul>
                    <li>
                        Pembayaran <strong>Tahap 1 : Rp 6.000.000,-</strong>
                        dibayarkan ketika check in asrama
                    </li>
                    <li>
                        Pembayaran <strong>Tahap 2 : Rp. 5.000.000,-</strong>
                        dibayarkan sebelum memasuki bulan ke-2
                    </li>
                    <li>
                        Pembayaran
                        <strong>Tahap 3 (Pelunasan): Rp. 4.999.000,-</strong>
                        dibayarkan sebelum memasuki bulan ke-3
                    </li>
                </ul>
            </ul>
            <hr />
            <h4>2.3 Prosedur Pembayaran Kelas Paket 9 Bulan (Gap Year)</h4>
            <ul>
                <li>
                    Biaya Pendaftaran sebesar <strong>Rp. 500.000,-</strong>
                    ketika mengisi Formulir Pendaftaran
                </li>
                <li>Total biaya program sebesar <strong>Rp 19.500.000.</strong></li>
                <li>
                    Pembayaran program dapat dilakukan sebelum pembelajaran dimulai.
                </li>
                <li>
                    Pembayaran dapat dilakukan dengan cicilan 3 tahap sebagai berikut:
                </li>
                <ul>
                    <li>
                        Pembayaran <strong>Tahap 1 : Rp 7.000.000</strong> Dibayarkan
                        saat check-in asrama / sebelum kelas dimulai
                    </li>
                    <li>
                        Pembayaran <strong>Tahap 2 : Rp. 6.500.000,-</strong> dibayarkan
                        sebelum memasuki bulan ke-2
                    </li>
                    <li>
                        Pembayaran
                        <strong>Tahap 3 (Pelunasan): Rp. 6.000.000,-</strong> dibayarkan
                        sebelum memasuki bulan ke-3
                    </li>
                </ul>
            </ul>
            <hr />
            <h4>2.4 Prosedur Pembayaran Rombongan diberikan secara 2 (Dua) Tahap.</h4>
            <ul>
                <li>
                    Tahap I sebesar 50% dari jumlah Nominal yang tertera dikali jumlah
                    peserta kursus yang didaftarkan. Jika jumlah peserta adalah 20
                    orang, silahkan melakukan pembayaran sebanyak 10 orang dan
                    menyertakan kode unik sebagai tanda pengenal pembayaran
                    masing-masing peserta.
                </li>
                <li>
                    Pembayaran Tahap I, dibayarkan 30 (Tiga Puluh) Hari sebelum
                    Tanggal Pelaksanaan Kursus. Tahap II 50% berikutnya dibayarkan
                    setibanya peserta kursus di Kampung Inggris Pare.
                </li>
                <li>
                    Kwitansi dan Invoice akan dikirimkan ke alamat Email valid salah
                    satu Penanggung Jawab / Perwakilan Rombongan yang bersangkutan.
                </li>
            </ul>
            <hr />
            <h4>2.5 Prosedur Pembayaran Reguler</h4>
            <p>
                Jika siswa mendaftar lebih dari satu program dengan biaya langsung
                lunas diawal (contoh: ambil program hsk 2 dan 3 secara bersamaan,
                maka siswa hanya dikenakan biaya registrasi 1x saja)
            </p>
            <hr />
            <h3>3. Condition of Implementation | Ketentuan Pelaksanaan</h3>
            <ul>
                <li>
                    Kursus dimulai setiap tanggal 10 setiap bulan sepanjang tahun.
                    Jika pada tanggal tersebut jatuh pada hari Sabtu, Minggu, atau
                    Hari Libur maka akan dimajuan/disesuaikan. Khusus untuk kelas
                    reguler kuota minimal 3 orang, sehingga pembelajaran akan dimulai
                    setelah ada minimal 3 orang pendaftar. Tanggal 10 dikenal dengan
                    nama PERIODE.
                </li>
                <li>
                    Khusus bagi Anda yang melakukan request untuk kursus di Luar
                    Periode tanggal 10 silahkan menghubungi admin kami. Aturan ini
                    berlaku untuk pendaftar Perseorangan maupun Kolektif.
                </li>
                <li>
                    Bagi siswa yg ingin melakukan garansi (mengulang kelas) tidak
                    dikenakan biaya apapun kecuali biaya asrama per bulan 500k
                </li>
                <li>
                    jika siswa ingin melanjutkan program offline ke level lebih tinggi
                    /selanjutnya, maka akan di kenakan biaya registrasi ulang sebesar
                    500k
                </li>
            </ul>
            <hr />
            <h3>4. Ketentuan Pembatalan dan Perubahan Program</h3>
            <h4>4.A. PEMBATALAN KURSUS:</h4>
            <ul>
                <li>
                    Konfirmasi Pembatalan Kursus wajib disampaikan maksimal 5 HARI
                    sebelum pelaksanaan tanggal kursus.
                </li>
                <li>
                    Konsekuensi dari pembatalan sesuai poin 1 adalah biaya pendaftaran
                    hangus.
                </li>
                <li>
                    Pengembalian biaya (Refund) hanya dapat dilakukan apabila
                    pembelajaran belum dimulai/dilaksanakan.
                </li>
                <li>
                    Pengembalian biaya paling lambat 2 Minggu setelah siswa melakukan
                    pengajuan pembatalan.
                </li>
            </ul>
            <hr />
            <h4>4.B. PERUBAHAN PROGRAM</h4>
            <ul>
                <li>
                    Perubahan program dapat dilakukan maksimal sebelum check-in
                    asrama/sebelum program pembelajaran dimulai.
                </li>
                <li>
                    Perubahan program paket hanya bisa dilakukan ke program paket lain
                    dan tidak bisa ke program per level / reguler.
                </li>
                <li>
                    Ketika siswa mengganti program paket ke program paket yang
                    berdurasi lebih pendek, maka biaya yang telah masuk bisa di refund
                    50% dari sisa biaya paket yang dipilih.
                </li>
                <li>
                    Apabila perubahan dilakukan setelah program dimulai, siswa wajib
                    melakukan registrasi ulang sebesar Rp 500.000,-.
                </li>
                <li>Ketentuan Refund TIDAK berlaku untuk program paket.</li>
                <li>
                    Apabila pembelajaran telah dimulai, biaya yang sudah masuk tidak
                    dapat ditarik kembali dengan alasan apapun.
                </li>
                <li>
                    Perubahan paket kursus SETELAH KURSUS BERJALAN tidak akan kami
                    proses, dan menyebabkan dana yang telah masuk HANGUS jika anda
                    tidak mengikuti paket kursus yang telah anda pesan.
                </li>
            </ul>
            <hr />
            <h3>5. Term of Warranty | Ketentuan Garansi</h3>
            <p>
                Garansi diberikan untuk Program Paket dengan target yang sudah
                ditentukan sebelumnya. Pelaksanaan garansi dengan cara siswa yang
                mendapatkan garansi mengikuti pembelajaran program yang berjalan
                yang sama dengan program yang siswa ambil dengan tanpa tambahan
                biaya program, hanya harus membayar asrama saja.
            </p>
            <p>
                Contoh: "Clara mengikuti program Paket 7 bulan Bahasa Mandarin pada
                periode 10 Januari 2021 dan selesai pada 9 Agustus 2021, tetapi
                Clara merasa belum mencapai target meskipun dia sudah belajar dengan
                keras selama 7 bulan. Maka Clara berhak mengikuti pembelajaran
                program Paket 7 bulan Bahasa Mandarin pada periode berikutnya."
            </p>
            <ol>
                <li>Ketentuan</li>
                <ul>
                    <li>Garansi tidak terpaut jangka waktu.</li>
                    <li>
                        Garansi dilaksanakan dengan mengikuti program yang sama yang
                        akan/sedang berjalan diperiode berikutnya.
                    </li>
                    <li>
                        Peserta garansi diwajibkan registrasi asrama/camp dan tidak
                        diperbolehkan tinggal diluar asrama.
                    </li>
                </ul>
                <li>Persyaratan klaim garansi</li>
                <ul>
                    <li>
                        Siswa patuh dengan semua aturan yang sudah ditetapkan Lembaga.
                    </li>
                    <li>Siswa aktif dalam kelas pembelajaran.</li>
                    <li>Selalu mengerjakan tugas sesuai ketentuan pengajar.</li>
                </ul>
                <li>Garansi tidak berlaku bila:</li>
                <ul>
                    <li>Siswa tidak patuh dengan aturan Lembaga.</li>
                    <li>Siswa malas belajar.</li>
                    <li>
                        Siswa membuat kesepakatan tersendiri dengan guru untuk merubah
                        target dan metode pembelajaran.
                    </li>
                    <li>
                        Siswa tidak mengerjakan tugas lebih dari 3 kali dalam sebulan.
                    </li>
                    <li>
                        Absen (tidak masuk) siswa lebih dari 3 kali Pertemuan (untuk
                        durasi paket 1 bulan), 9 kali Pertemuan (untuk durasi paket 3
                        bulan), 12 kali Pertemuan (untuk durasi paket 5 bulan), 15 kali
                        Pertemuan (untuk durasi paket 7 bulan).
                    </li>
                </ul>
                <li>Pencabutan garansi</li>
                <p>
                    Pencabutan garansi dapat dilakukan sepihak oleh Lembaga apabila
                    Siswa melakukan hal yang menyebabkan garansi tidak berlaku (poin
                    3) ketika menjalani pembelajaran di periode garansi.
                </p>
            </ol>
            <hr />
            <h3>6. Privasi Data</h3>
            <p>
                Data pribadi siswa akan dijaga kerahasiaannya dan hanya digunakan
                untuk keperluan administrasi internal Xihuan Mandarin Indonesia.
            </p>
            <hr />
            <h3>7. Lain-lain</h3>
            <ul>
                <li>
                    Xihuan Mandarin Indonesia berhak mengubah syarat dan ketentuan
                    sewaktu-waktu dengan memberitahukan terlebih dahulu.
                </li>
                <li>
                    Hal-hal yang belum diatur dalam syarat dan ketentuan ini akan
                    ditentukan kemudian oleh pihak Xihuan Mandarin Indonesia.
                </li>
            </ul>
            <hr />
            <p>
                Untuk informasi lebih lanjut, silakan hubungi admin Xihuan Mandarin
                Indonesia.
            </p>
        </div>

        <!-- Footer dengan Checkbox dan Tombol -->
        <div class="terms-modal-footer">
            <div class="terms-modal-checkbox">
                <input type="checkbox" id="termsAgreeCheckbox" />
                <label for="termsAgreeCheckbox">
                    Saya telah membaca, memahami, dan menyetujui seluruh Syarat dan
                    Ketentuan di atas
                </label>
            </div>
            <button class="send-btn" id="acceptTermsBtn" disabled>
                <i class="fas fa-check-circle"></i> Setuju & Lanjutkan ke
                Pendaftaran
            </button>
        </div>
    </div>
</div>

<!-- Terms Modal JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const termsModal = document.getElementById('termsModal');
    const termsAgreeCheckbox = document.getElementById('termsAgreeCheckbox');
    const acceptTermsBtn = document.getElementById('acceptTermsBtn');
    const termsModalClose = document.getElementById('termsModalClose');
    const admissionFormContent = document.getElementById('admissionFormContent');
    
    // Check if terms were already accepted (using sessionStorage)
    const termsAccepted = sessionStorage.getItem('termsAccepted');
    
    if (termsAccepted === 'true') {
        // Hide modal and show form
        termsModal.classList.remove('show');
        admissionFormContent.classList.add('show');
    } else {
        // Show modal (already has 'show' class by default)
        termsModal.classList.add('show');
    }
    
    // Handle checkbox change
    termsAgreeCheckbox.addEventListener('change', function() {
        acceptTermsBtn.disabled = !this.checked;
    });
    
    // Handle accept button click
    acceptTermsBtn.addEventListener('click', function() {
        if (termsAgreeCheckbox.checked) {
            // Store that terms were accepted
            sessionStorage.setItem('termsAccepted', 'true');
            
            // Hide modal with animation
            termsModal.classList.remove('show');
            
            // Show form content
            admissionFormContent.classList.add('show');
        }
    });
    
    // Handle close button (X)
    termsModalClose.addEventListener('click', function() {
        // If user clicks X, redirect back or show message
        if (!termsAgreeCheckbox.checked) {
            alert('Anda harus menyetujui syarat dan ketentuan untuk melanjutkan.');
        }
    });
    
    // Prevent modal from closing when clicking outside
    termsModal.addEventListener('click', function(e) {
        if (e.target === termsModal) {
            if (!termsAgreeCheckbox.checked) {
                alert('Anda harus menyetujui syarat dan ketentuan untuk melanjutkan.');
            }
        }
    });
});
</script>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold">Create New Admission</h4>
        <p class="text-muted mb-0">Fill in the admission application form</p>
    </div>
    <div class="col-auto">
        <a href="<?= base_url('admission') ?>" class="btn btn-outline-dark-red">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<!-- Superadmin Autofill Tool -->
<?php if ($user && $user->inGroup('superadmin')): ?>
    <div class="row mb-4">
        <div class="col">
            <div class="card bg-light border-primary shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-primary"><i class="bi bi-magic me-2"></i>Testing Tool: Autofill From JSON</h6>
                            <p class="small mb-0 text-muted">
                                Upload a <code>.txt</code> file to populate the form.
                                <a href="<?= base_url('templates/admission_autofill_template.txt') ?>" download class="text-decoration-none ms-1 fw-bold">
                                    <i class="bi bi-download me-1"></i>Download Template
                                </a>
                            </p>
                        </div>
                        <div class="ms-3" style="width: 300px;">
                            <input type="file" id="autofill_file" class="form-control form-control-sm" accept=".txt,.json">
                        </div>
                    </div>
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
                    alert('Please upload a .txt or .json file.\nDetected file type: ' + file.type);
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

                        const form = document.querySelector('form[action$="admission/store"]');
                        console.log('Form found:', !!form);

                        if (!form) {
                            alert('Form not found! Check console for details.');
                            console.log('All form actions on page:', Array.from(document.querySelectorAll('form')).map(f => f.action));
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
                                    let found = false;
                                    Array.from(input.options).forEach(opt => {
                                        if (opt.value == data[key] || opt.textContent.trim().includes(data[key])) {
                                            input.value = opt.value;
                                            found = true;
                                        }
                                    });
                                    if (!found && key === 'course') console.warn('Program not found:', data[key]);
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

                        // Specific handling for 'course'
                        if (data.course) {
                            const courseSelect = form.querySelector('select[name="course"]');
                            if (courseSelect) {
                                courseSelect.value = data.course;
                                courseSelect.dispatchEvent(new Event('change'));
                            }
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
                        alert('Error parsing JSON: ' + err.message + '\nCheck console for details.');
                    }
                };
                reader.readAsText(file);
            });
        });
    </script>
<?php endif; ?>

<!-- Form Content (Hidden until terms accepted) -->
<div id="admissionFormContent">

<?php if (session('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Validation Errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<form action="<?= base_url('admission/store') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Personal Information -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-person me-2"></i>Personal Information
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control form-control-sm" value="<?= old('full_name') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nickname</label>
                    <input type="text" name="nickname" class="form-control form-control-sm" value="<?= old('nickname') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select form-select-sm" required>
                        <option value="">Select</option>
                        <option value="Male" <?= old('gender') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= old('gender') === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="date_of_birth" class="form-control form-control-sm" value="<?= old('date_of_birth') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                    <input type="text" name="place_of_birth" class="form-control form-control-sm" value="<?= old('place_of_birth') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Religion <span class="text-danger">*</span></label>
                    <input type="text" name="religion" class="form-control form-control-sm" value="<?= old('religion') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Citizen ID</label>
                    <input type="text" name="citizen_id" class="form-control form-control-sm" value="<?= old('citizen_id') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Contact -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-telephone me-2"></i>Contact Information
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control form-control-sm" value="<?= old('phone') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-sm" value="<?= old('email') ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Address -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-geo-alt me-2"></i>Address
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Street Address <span class="text-danger">*</span></label>
                    <textarea name="street_address" class="form-control form-control-sm" rows="2" required><?= old('street_address') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">District <span class="text-danger">*</span></label>
                    <input type="text" name="district" class="form-control form-control-sm" value="<?= old('district') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Regency/City <span class="text-danger">*</span></label>
                    <input type="text" name="regency" class="form-control form-control-sm" value="<?= old('regency') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Province <span class="text-danger">*</span></label>
                    <input type="text" name="province" class="form-control form-control-sm" value="<?= old('province') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" class="form-control form-control-sm" value="<?= old('postal_code') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-exclamation-triangle me-2"></i>Emergency Contact
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="emergency_contact_name" class="form-control form-control-sm" value="<?= old('emergency_contact_name') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" name="emergency_contact_phone" class="form-control form-control-sm" value="<?= old('emergency_contact_phone') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Relationship <span class="text-danger">*</span></label>
                    <input type="text" name="emergency_contact_relation" class="form-control form-control-sm" value="<?= old('emergency_contact_relation') ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Family -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-people me-2"></i>Family Information
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                    <input type="text" name="father_name" class="form-control form-control-sm" value="<?= old('father_name') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                    <input type="text" name="mother_name" class="form-control form-control-sm" value="<?= old('mother_name') ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Course & Files -->
    <div class="dashboard-card mb-3">
        <div class="card-header">
            <i class="bi bi-mortarboard me-2"></i>Course & Files
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Course <span class="text-danger">*</span></label>
                    <select name="program_id" class="form-select form-select-sm" required>
                        <option value="">Select Program</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?= esc($program['id']) ?>" <?= old('program_id') === $program['id'] ? 'selected' : '' ?>>
                                <?= esc($program['title']) ?> (Rp <?= number_format($program['registration_fee'] ?? 0, 0, ',', '.') ?>)
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
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
                            $displayDate = date('F d, Y (l)', $tenthDay);
                            $startDateOptions[$dateValue] = $displayDate;
                        }
                    }
                    ?>
                    <select name="start_date" class="form-select form-select-sm">
                        <option value="">Select Start Date</option>
                        <?php foreach ($startDateOptions as $value => $label): ?>
                            <option value="<?= $value ?>" <?= old('start_date') === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <small class="text-muted">10th of each month (moved to Monday if weekend)</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="pending" <?= old('status') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= old('status') === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= old('status') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Profile Photo <span class="text-danger">*</span></label>
                    <input type="file" name="photo" class="form-control form-control-sm" accept="image/jpeg,image/jpg,image/png,image/webp" required>
                    <small class="text-muted">Accepted formats: JPG, PNG, WebP. Images will be converted to WebP for optimization.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supporting Documents</label>
                    <input type="file" name="documents[]" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,image/*" multiple>
                    <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, PNG, GIF (Max 5MB each)</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control form-control-sm" rows="3"><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-dark-red">
            <i class="bi bi-save me-1"></i> Save Admission
        </button>
        <a href="<?= base_url('admission') ?>" class="btn btn-outline-dark-red">Cancel</a>
    </div>
</form>
</div>
<?= $this->endSection() ?>