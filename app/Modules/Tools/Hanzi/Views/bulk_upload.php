<?= $this->extend('Modules\Dashboard\Views\layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-upload me-2"></i>Bulk Upload Hanzi
            </h1>
            <p class="text-muted mb-0">Import multiple Chinese characters at once</p>
        </div>
        <a href="<?= site_url('tools/hanzi') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Upload Form -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upload File</h6>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('tools/hanzi/bulk-upload/process') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="csv_file" class="form-label">Select File (CSV, JSON, or Excel)</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file" 
                                   accept=".csv,.json,.xlsx,.xls" required>
                            <div class="form-text">
                                Supported formats: <strong>.csv</strong>, <strong>.json</strong>, <strong>.xlsx</strong>, and <strong>.xls</strong>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> If a hanzi already exists, it will be updated with the new data.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= site_url('tools/hanzi') ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i> Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- File Format Guide -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">File Format Guide</h6>
                </div>
                <div class="card-body">
                    <!-- Excel Format -->
                    <div class="mb-4">
                        <h6><i class="bi bi-file-earmark-excel me-2 text-success"></i>Excel Format (Recommended)</h6>
                        <p class="text-muted small">First row must be headers. Required columns: <code>hanzi</code>, <code>pinyin</code></p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>hanzi</th>
                                        <th>pinyin</th>
                                        <th>category</th>
                                        <th>translation_en</th>
                                        <th>translation_id</th>
                                        <th>example_en</th>
                                        <th>example_id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>你好</td>
                                        <td>nǐ hǎo</td>
                                        <td>HSK1</td>
                                        <td>hello</td>
                                        <td>halo</td>
                                        <td>Hello friend</td>
                                        <td>Halo teman</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-success btn-sm mt-2" onclick="downloadExcelTemplate()">
                            <i class="bi bi-download me-1"></i> Download Excel Template
                        </button>
                    </div>

                    <!-- CSV Format -->
                    <div class="mb-4">
                        <h6><i class="bi bi-file-earmark-spreadsheet me-2"></i>CSV Format</h6>
                        <p class="text-muted small">First row must be headers. Required columns: <code>hanzi</code>, <code>pinyin</code></p>
                        <button class="btn btn-outline-success btn-sm" onclick="downloadCsvTemplate()">
                            <i class="bi bi-download me-1"></i> Download CSV Template
                        </button>
                    </div>

                    <!-- JSON Format -->
                    <div>
                        <h6><i class="bi bi-file-earmark-code me-2"></i>JSON Format</h6>
                        <p class="text-muted small">Array of objects with required fields: <code>hanzi</code>, <code>pinyin</code></p>
                        <button class="btn btn-outline-success btn-sm" onclick="downloadJsonTemplate()">
                            <i class="bi bi-download me-1"></i> Download JSON Template
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Field Reference -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Field Reference</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Required</th>
                            <th>Description</th>
                            <th>Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>hanzi</code></td>
                            <td><span class="badge bg-danger">Yes</span></td>
                            <td>Chinese character(s)</td>
                            <td>你好</td>
                        </tr>
                        <tr>
                            <td><code>pinyin</code></td>
                            <td><span class="badge bg-danger">Yes</span></td>
                            <td>Pronunciation with tone marks</td>
                            <td>nǐ hǎo</td>
                        </tr>
                        <tr>
                            <td><code>category</code></td>
                            <td><span class="badge bg-secondary">No</span></td>
                            <td>HSK level: HSK1, HSK2, HSK3, HSK4, HSK5, HSK6, or OTHER</td>
                            <td>HSK1</td>
                        </tr>
                        <tr>
                            <td><code>translation_en</code></td>
                            <td><span class="badge bg-secondary">No</span></td>
                            <td>English translation</td>
                            <td>hello</td>
                        </tr>
                        <tr>
                            <td><code>translation_id</code></td>
                            <td><span class="badge bg-secondary">No</span></td>
                            <td>Indonesian translation</td>
                            <td>halo</td>
                        </tr>
                        <tr>
                            <td><code>example_en</code></td>
                            <td><span class="badge bg-secondary">No</span></td>
                            <td>Example sentence in English</td>
                            <td>Hello friend</td>
                        </tr>
                        <tr>
                            <td><code>example_id</code></td>
                            <td><span class="badge bg-secondary">No</span></td>
                            <td>Example sentence in Indonesian</td>
                            <td>Halo teman</td>
                        </tr>
                        <tr>
                            <td><code>stroke_count</code></td>
                            <td><span class="badge bg-secondary">No</span></td>
                            <td>Number of strokes</td>
                            <td>7</td>
                        </tr>
                        <tr>
                            <td><code>frequency</code></td>
                            <td><span class="badge bg-secondary">No</span></td>
                            <td>Frequency ranking (lower = more common)</td>
                            <td>500</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function downloadExcelTemplate() {
    // Create a simple HTML table that can be opened in Excel
    const htmlContent = `
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <title>Hanzi Template</title>
</head>
<body>
    <table border="1">
        <tr>
            <th>hanzi</th>
            <th>pinyin</th>
            <th>category</th>
            <th>translation_en</th>
            <th>translation_id</th>
            <th>example_en</th>
            <th>example_id</th>
            <th>stroke_count</th>
            <th>frequency</th>
        </tr>
        <tr>
            <td>你好</td>
            <td>nǐ hǎo</td>
            <td>HSK1</td>
            <td>hello</td>
            <td>halo</td>
            <td>Hello friend</td>
            <td>Halo teman</td>
            <td>7</td>
            <td>500</td>
        </tr>
        <tr>
            <td>谢谢</td>
            <td>xiè xie</td>
            <td>HSK1</td>
            <td>thank you</td>
            <td>terima kasih</td>
            <td>Thank you very much</td>
            <td>Terima kasih banyak</td>
            <td>12</td>
            <td>600</td>
        </tr>
        <tr>
            <td>再见</td>
            <td>zài jiàn</td>
            <td>HSK1</td>
            <td>goodbye</td>
            <td>selamat tinggal</td>
            <td>Goodbye friend</td>
            <td>Selamat tinggal teman</td>
            <td>6</td>
            <td>800</td>
        </tr>
    </table>
</body>
</html>`;
    
    const blob = new Blob([htmlContent], { type: 'application/vnd.ms-excel' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'hanzi_template.xls';
    link.click();
}

function downloadCsvTemplate() {
    const csvContent = `hanzi,pinyin,category,translation_en,translation_id,example_en,example_id,stroke_count,frequency
你好,nǐ hǎo,HSK1,hello,halo,Hello friend,Halo teman,7,500
谢谢,xiè xie,HSK1,thank you,terima kasih,Thank you very much,Terima kasih banyak,12,600
再见,zài jiàn,HSK1,goodbye,selamat tinggal,Goodbye friend,Selamat tinggal teman,6,800`;
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'hanzi_template.csv';
    link.click();
}

function downloadJsonTemplate() {
    const jsonContent = [
        {
            hanzi: "你好",
            pinyin: "nǐ hǎo",
            category: "HSK1",
            translation_en: "hello",
            translation_id: "halo",
            example_en: "Hello friend",
            example_id: "Halo teman",
            stroke_count: 7,
            frequency: 500
        },
        {
            hanzi: "谢谢",
            pinyin: "xiè xie",
            category: "HSK1",
            translation_en: "thank you",
            translation_id: "terima kasih",
            example_en: "Thank you very much",
            example_id: "Terima kasih banyak",
            stroke_count: 12,
            frequency: 600
        }
    ];
    
    const blob = new Blob([JSON.stringify(jsonContent, null, 2)], { type: 'application/json' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'hanzi_template.json';
    link.click();
}
</script>

<?= $this->endSection() ?>
