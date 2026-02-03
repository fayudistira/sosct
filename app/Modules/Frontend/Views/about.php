<?= $this->extend('Modules\Frontend\Views\layout') ?>

<?= $this->section('content') ?>
<!-- Documentation Header -->
<div class="hero-section py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">System Documentation</h1>
                <p class="lead mb-0">Complete guide to the ERP V 1.1 - Future English Education Center Management System.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="badge bg-white text-dark p-2 px-3 rounded-pill shadow-sm">
                    <i class="bi bi-tag-fill me-2 text-primary"></i>Version 1.1.0 Stable
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="sticky-top" style="top: 2rem;">
                <div class="card-custom border-0 shadow-sm overflow-hidden">
                    <div class="list-group list-group-flush" id="doc-nav">
                        <a href="#overview" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-info-circle me-2"></i> Overview
                        </a>
                        <a href="#modules" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-grid-fill me-2"></i> Core Modules
                        </a>
                        <a href="#admission" class="list-group-item list-group-item-action border-0 py-3 ps-5">
                            <i class="bi bi-person-plus me-2"></i> Admission
                        </a>
                        <a href="#payment" class="list-group-item list-group-item-action border-0 py-3 ps-5">
                            <i class="bi bi-credit-card me-2"></i> Payments
                        </a>
                        <a href="#developers" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-code-slash me-2"></i> Developers
                        </a>
                        <a href="#command" class="list-group-item list-group-item-action border-0 py-3 ps-5">
                            <i class="bi bi-terminal me-2"></i> MakeModule
                        </a>
                    </div>
                </div>
                
                <div class="card-custom bg-light border-0 mt-4 p-4">
                    <h6 class="fw-bold mb-2">Need Help?</h6>
                    <p class="small text-muted mb-0">Contact the system administrator if you encounter any issues with the ERP modules.</p>
                </div>
            </div>
        </div>

        <!-- Main Documentation Content -->
        <div class="col-lg-9">
            <!-- Overview Section -->
            <section id="overview" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-stars"></i>
                    </span>
                    System Overview
                </h2>
                <div class="card-custom p-4 border-0 shadow-sm">
                    <p class="lead">The FEEC ERP is a comprehensive management system built on CodeIgniter 4, designed to handle the recruitment, academic, and financial operations of the education center.</p>
                    <p>It utilizes an <strong>HMVC (Hierarchical Model-View-Controller)</strong> architecture, allowing for modular development and easy scaling. Each feature is encapsulated within its own "Module" directory, containing its own Config, Controllers, Models, and Views.</p>
                    
                    <div class="row mt-4 g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="fw-bold text-primary mb-1">7</h3>
                                <div class="small fw-semibold">Core Modules</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="fw-bold text-success mb-1">CI4</h3>
                                <div class="small fw-semibold">Framework</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="fw-bold text-danger mb-1">HMVC</h3>
                                <div class="small fw-semibold">Architecture</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Core Modules Section -->
            <section id="modules" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-grid-fill"></i>
                    </span>
                    Core Modules
                </h2>
                
                <div class="row g-4">
                    <div id="admission" class="col-12 scroll-mt" style="scroll-margin-top: 2rem;">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-primary p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Admission Module</h4>
                                <p class="text-muted">Handles new student registrations, admission forms, and applicant tracking.</p>
                                <ul class="mb-0">
                                    <li><strong>Public Form:</strong> Multi-step registration for prospective students.</li>
                                    <li><strong>Management:</strong> Backend dashboard to approve or reject applications.</li>
                                    <li><strong>JSON Data:</strong> Complex fields like addresses are stored as JSON for flexibility.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="payment" class="col-12 scroll-mt" style="scroll-margin-top: 2rem;">
                        <div class="card-custom border-0 shadow-sm h-100 overflow-hidden">
                            <div class="bg-success p-2"></div>
                            <div class="p-4">
                                <h4 class="fw-bold"><i class="bi bi-credit-card me-2 text-success"></i>Payment & Finance</h4>
                                <p class="text-muted">Automated invoicing and payment tracking for student fees.</p>
                                <ul class="mb-0">
                                    <li><strong>Smart Search:</strong> AJAX-powered student search using Select2.</li>
                                    <li><strong>Invoice Population:</strong> Dynamically loads unpaid invoices upon student selection.</li>
                                    <li><strong>Financial Reporting:</strong> Track income and pending balances in real-time.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-custom border-0 shadow-sm h-100 p-4">
                            <h5 class="fw-bold">Program Management</h5>
                            <p class="small text-muted">Defines educational tracks, durations, and pricing tiers.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card-custom border-0 shadow-sm h-100 p-4">
                            <h5 class="fw-bold">User RBAC</h5>
                            <p class="small text-muted">Role-based access control for students, tutors, and administrators.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Developer Section -->
            <section id="developers" class="mb-5">
                <h2 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="feature-icon ms-0 me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 1rem;">
                        <i class="bi bi-code-slash"></i>
                    </span>
                    Developer Resources
                </h2>
                
                <div id="command" class="card-custom border-0 shadow-sm p-4 scroll-mt" style="scroll-margin-top: 2rem;">
                    <h4 class="fw-bold"><i class="bi bi-terminal me-2"></i>Module Generator</h4>
                    <p>To speed up development, the ERP includes a custom CLI generator. This tool automatically creates the necessary HMVC structure and registers namespaces.</p>
                    
                    <div class="bg-dark text-white p-3 rounded mb-3 position-relative">
                        <code>php spark make:module <span class="text-info">[ModuleName]</span></code>
                    </div>
                    
                    <h6 class="fw-bold mb-2">Automated Actions:</h6>
                    <ul class="mb-0">
                        <li>Creates folders: Config, Controllers, Models, Views, Migrations.</li>
                        <li>Generates boilerplate Routes, Base Controller, and Layouts.</li>
                        <li>Automatically registers the namespace in <code>app/Config/Autoload.php</code>.</li>
                        <li>Optional CRUD generation with standard boilerplate methods.</li>
                    </ul>
                </div>
            </section>

            <!-- Technical Spec -->
            <section id="tech-spec" class="mb-5">
                <h2 class="fw-bold mb-4">Technical Stack</h2>
                <div class="table-responsive">
                    <table class="table table-hover border">
                        <thead class="bg-light">
                            <tr>
                                <th>Component</th>
                                <th>Technology</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">Framework</td>
                                <td>CodeIgniter 4.x</td>
                                <td>Back-end processing & Routing</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">UI Framework</td>
                                <td>Bootstrap 5.3</td>
                                <td>Responsive Layouts & Components</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Database</td>
                                <td>MySQL / MariaDB</td>
                                <td>Relational Data Persistence</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Icons</td>
                                <td>Bootstrap Icons</td>
                                <td>Visual cues & iconography</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Interactions</td>
                                <td>Vanilla JS / jQuery</td>
                                <td>AJAX Operations & Form Logic</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    section {
        scroll-margin-top: 100px;
    }
    
    .list-group-item.active {
        background-color: var(--dark-red);
        border-color: var(--dark-red);
    }
    
    #doc-nav .list-group-item:hover {
        background-color: var(--light-red);
        color: var(--dark-red);
    }
    
    .scroll-mt {
        transition: all 0.3s;
    }
    
    code {
        font-family: 'Consolas', 'Monaco', monospace;
    }
</style>

<script>
    // Simple scrollspy implementation if needed manually, 
    // though the anchor tags work by default.
    document.querySelectorAll('#doc-nav a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            document.querySelector(targetId).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>
<?= $this->endSection() ?>
