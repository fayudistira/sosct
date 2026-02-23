<?= $this->extend('Modules\Dashboard\Views\layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-card-text me-2"></i>Hanzi Flashcards
            </h1>
            <p class="text-muted mb-0">Practice Chinese characters with interactive flashcards</p>
        </div>
        <a href="<?= site_url('tools/hanzi') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <!-- Settings Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat ?>" <?= $currentCategory === $cat ? 'selected' : '' ?>>
                                <?= $cat ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="limit" class="form-label">Number of Cards</label>
                    <select name="limit" id="limit" class="form-select">
                        <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5 cards</option>
                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10 cards</option>
                        <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20 cards</option>
                        <option value="30" <?= $limit == 30 ? 'selected' : '' ?>>30 cards</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50 cards</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-shuffle me-1"></i> Start Practice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($hanzi)): ?>
        <!-- No Data -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="bi bi-journal-x display-1 text-muted"></i>
                <h4 class="mt-3">No Hanzi Available</h4>
                <p class="text-muted">There are no hanzi characters in the selected category.</p>
                <a href="<?= site_url('tools/hanzi/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add Hanzi
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Progress Bar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small text-muted">Progress</span>
                    <span class="small text-muted">
                        <span id="currentIndex">1</span> / <span id="totalCards"><?= count($hanzi) ?></span>
                    </span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar" id="progressBar" role="progressbar" 
                         style="width: <?= 100 / count($hanzi) ?>%;" 
                         aria-valuenow="1" aria-valuemin="0" aria-valuemax="<?= count($hanzi) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Flashcard Container -->
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <!-- Flashcard -->
                <div class="flashcard-container" id="flashcardContainer">
                    <div class="flashcard" id="flashcard" onclick="flipCard()">
                        <!-- Front (Hanzi) -->
                        <div class="flashcard-front" id="cardFront">
                            <div class="hanzi-large" id="hanziDisplay"></div>
                            <div class="text-muted mt-3">
                                <small>Click to reveal</small>
                            </div>
                        </div>
                        <!-- Back (Details) -->
                        <div class="flashcard-back" id="cardBack">
                            <div class="hanzi-medium" id="hanziSmall"></div>
                            <div class="pinyin-display" id="pinyinDisplay"></div>
                            <hr class="my-3">
                            <div class="translation-section">
                                <h6>Translation</h6>
                                <div id="translationDisplay"></div>
                            </div>
                            <div class="example-section mt-3">
                                <h6>Example</h6>
                                <div id="exampleDisplay"></div>
                            </div>
                            <div class="category-badge mt-3">
                                <span class="badge bg-primary" id="categoryDisplay"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button class="btn btn-outline-secondary btn-lg" id="prevBtn" onclick="prevCard()" disabled>
                        <i class="bi bi-chevron-left"></i> Previous
                    </button>
                    <button class="btn btn-outline-primary btn-lg" id="flipBtn" onclick="flipCard()">
                        <i class="bi bi-arrow-repeat"></i> Flip
                    </button>
                    <button class="btn btn-primary btn-lg" id="nextBtn" onclick="nextCard()">
                        Next <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <!-- Keyboard Shortcuts -->
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="bi bi-keyboard me-1"></i> 
                        Keyboard shortcuts: <kbd>Space</kbd> flip, <kbd>←</kbd> previous, <kbd>→</kbd> next
                    </small>
                </div>
            </div>
        </div>

        <!-- Completion Modal -->
        <div class="modal fade" id="completionModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-trophy me-2"></i>Practice Complete!
                        </h5>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-check-circle display-1 text-success"></i>
                        <h4 class="mt-3">Great Job!</h4>
                        <p class="text-muted">You have completed all <?= count($hanzi) ?> flashcards.</p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="restartPractice()">
                                <i class="bi bi-arrow-repeat me-1"></i> Practice Again
                            </button>
                            <a href="<?= site_url('tools/hanzi') ?>" class="btn btn-primary">
                                <i class="bi bi-list me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.flashcard-container {
    perspective: 1000px;
    margin: 20px 0;
}

.flashcard {
    width: 100%;
    height: 400px;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.6s;
    cursor: pointer;
}

.flashcard.flipped {
    transform: rotateY(180deg);
}

.flashcard-front,
.flashcard-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 30px;
}

.flashcard-front {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.flashcard-back {
    background: white;
    transform: rotateY(180deg);
    border: 2px solid #e0e0e0;
}

.hanzi-large {
    font-family: "Microsoft YaHei", "SimHei", sans-serif;
    font-size: 6rem;
    line-height: 1.2;
}

.hanzi-medium {
    font-family: "Microsoft YaHei", "SimHei", sans-serif;
    font-size: 3rem;
    color: #333;
}

.pinyin-display {
    font-size: 1.5rem;
    color: #667eea;
    font-style: italic;
}

.translation-section,
.example-section {
    text-align: center;
    width: 100%;
}

.translation-section h6,
.example-section h6 {
    color: #6c757d;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

kbd {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    padding: 2px 6px;
    font-size: 0.75rem;
}
</style>

<script>
// Hanzi data
const hanziData = <?= json_encode($hanzi) ?>;
let currentIndex = 0;
let isFlipped = false;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    if (hanziData.length > 0) {
        showCard(0);
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.code === 'Space') {
            e.preventDefault();
            flipCard();
        } else if (e.code === 'ArrowLeft') {
            prevCard();
        } else if (e.code === 'ArrowRight') {
            nextCard();
        }
    });
});

function showCard(index) {
    const card = hanziData[index];
    
    // Parse JSON fields
    const translation = typeof card.translation === 'string' ? JSON.parse(card.translation) : card.translation;
    const example = typeof card.example === 'string' ? JSON.parse(card.example) : card.example;
    
    // Update front
    document.getElementById('hanziDisplay').textContent = card.hanzi;
    
    // Update back
    document.getElementById('hanziSmall').textContent = card.hanzi;
    document.getElementById('pinyinDisplay').textContent = card.pinyin;
    document.getElementById('categoryDisplay').textContent = card.category;
    
    // Translation
    let transHtml = '';
    if (translation && translation.en) {
        transHtml += `<div><span class="badge bg-light text-dark me-1">EN</span>${translation.en}</div>`;
    }
    if (translation && translation.id) {
        transHtml += `<div><span class="badge bg-light text-dark me-1">ID</span>${translation.id}</div>`;
    }
    document.getElementById('translationDisplay').innerHTML = transHtml || '<span class="text-muted">No translation</span>';
    
    // Example
    let exampleHtml = '';
    if (example && example.en) {
        exampleHtml += `<div><span class="badge bg-light text-dark me-1">EN</span>${example.en}</div>`;
    }
    if (example && example.id) {
        exampleHtml += `<div><span class="badge bg-light text-dark me-1">ID</span>${example.id}</div>`;
    }
    document.getElementById('exampleDisplay').innerHTML = exampleHtml || '<span class="text-muted">No example</span>';
    
    // Reset flip state
    if (isFlipped) {
        document.getElementById('flashcard').classList.remove('flipped');
        isFlipped = false;
    }
    
    // Update progress
    document.getElementById('currentIndex').textContent = index + 1;
    const progress = ((index + 1) / hanziData.length) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    
    // Update buttons
    document.getElementById('prevBtn').disabled = index === 0;
    document.getElementById('nextBtn').disabled = false;
}

function flipCard() {
    const flashcard = document.getElementById('flashcard');
    isFlipped = !isFlipped;
    flashcard.classList.toggle('flipped');
}

function prevCard() {
    if (currentIndex > 0) {
        currentIndex--;
        showCard(currentIndex);
    }
}

function nextCard() {
    if (currentIndex < hanziData.length - 1) {
        currentIndex++;
        showCard(currentIndex);
    } else {
        // Show completion modal
        new bootstrap.Modal(document.getElementById('completionModal')).show();
    }
}

function restartPractice() {
    currentIndex = 0;
    showCard(0);
}
</script>

<?= $this->endSection() ?>
