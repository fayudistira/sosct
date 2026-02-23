<?= $this->extend('Modules\Dashboard\Views\layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil me-2"></i>Edit Hanzi
            </h1>
            <p class="text-muted mb-0">Modify Chinese character information</p>
        </div>
        <a href="<?= site_url('tools/hanzi') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <!-- Error Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php 
    $translation = json_decode($hanzi->translation ?? '{}', true);
    $example = json_decode($hanzi->example ?? '{}', true);
    ?>

    <!-- Form Card -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Hanzi Information</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('tools/hanzi/update/' . $hanzi->id) ?>" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <?= csrf_field() ?>

                <div class="row">
                    <!-- Hanzi Character -->
                    <div class="col-md-4 mb-3">
                        <label for="hanzi" class="form-label">Hanzi Character <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg text-center" 
                               id="hanzi" name="hanzi" 
                               value="<?= old('hanzi', $hanzi->hanzi) ?>" 
                               style="font-family: 'Microsoft YaHei', 'SimHei', sans-serif; font-size: 2rem;"
                               required>
                        <div class="form-text">Enter the Chinese character(s)</div>
                    </div>

                    <!-- Pinyin -->
                    <div class="col-md-4 mb-3">
                        <label for="pinyin" class="form-label">Pinyin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="pinyin" name="pinyin" 
                               value="<?= old('pinyin', $hanzi->pinyin) ?>" 
                               required>
                        <div class="form-text">Include tone marks (e.g., nǐ hǎo)</div>
                    </div>

                    <!-- Category -->
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="category" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat ?>" <?= old('category', $hanzi->category) === $cat ? 'selected' : '' ?>>
                                    <?= $cat ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">HSK level or OTHER</div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Translations</h5>
                <div class="row">
                    <!-- English Translation -->
                    <div class="col-md-6 mb-3">
                        <label for="translation_en" class="form-label">English Translation</label>
                        <input type="text" class="form-control" 
                               id="translation_en" name="translation_en" 
                               value="<?= old('translation_en', $translation['en'] ?? '') ?>">
                    </div>

                    <!-- Indonesian Translation -->
                    <div class="col-md-6 mb-3">
                        <label for="translation_id" class="form-label">Indonesian Translation</label>
                        <input type="text" class="form-control" 
                               id="translation_id" name="translation_id" 
                               value="<?= old('translation_id', $translation['id'] ?? '') ?>">
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Example Sentences</h5>
                <div class="row">
                    <!-- English Example -->
                    <div class="col-md-6 mb-3">
                        <label for="example_en" class="form-label">English Example</label>
                        <textarea class="form-control" id="example_en" name="example_en" 
                                  rows="2"><?= old('example_en', $example['en'] ?? '') ?></textarea>
                    </div>

                    <!-- Indonesian Example -->
                    <div class="col-md-6 mb-3">
                        <label for="example_id" class="form-label">Indonesian Example</label>
                        <textarea class="form-control" id="example_id" name="example_id" 
                                  rows="2"><?= old('example_id', $example['id'] ?? '') ?></textarea>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Additional Information</h5>
                <div class="row">
                    <!-- Stroke Count -->
                    <div class="col-md-6 mb-3">
                        <label for="stroke_count" class="form-label">Stroke Count</label>
                        <input type="number" class="form-control" 
                               id="stroke_count" name="stroke_count" 
                               value="<?= old('stroke_count', $hanzi->stroke_count) ?>" 
                               min="1" max="100">
                        <div class="form-text">Number of strokes to write the character</div>
                    </div>

                    <!-- Frequency -->
                    <div class="col-md-6 mb-3">
                        <label for="frequency" class="form-label">Frequency Ranking</label>
                        <input type="number" class="form-control" 
                               id="frequency" name="frequency" 
                               value="<?= old('frequency', $hanzi->frequency) ?>" 
                               min="1">
                        <div class="form-text">Usage frequency ranking (lower = more common)</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?= site_url('tools/hanzi') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Update Hanzi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-control-lg {
    height: auto;
    padding: 0.5rem 1rem;
}
</style>

<?= $this->endSection() ?>
