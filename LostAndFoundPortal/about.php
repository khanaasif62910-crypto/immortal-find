<?php
/**
 * Lost and Found Portal - About Page
 */
$pageTitle = "About Project";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-semibold mb-3">
                <i class="fa-solid fa-graduation-cap me-1"></i> Academic Project Overview
            </span>
            <h1 class="fw-bold display-5 mb-3">About Lost & Found Portal</h1>
            <p class="lead text-secondary mb-4">
                Designed as a BCA Final Year Mini Project to solve real-world campus lost item recovery challenges through modern web technologies.
            </p>
            <p class="text-secondary mb-4">
                Every year, hundreds of valuable items—including laptops, college identity cards, keys, wallets, and textbooks—are misplaced across lecture halls, libraries, and cafeterias. This portal provides a streamlined digital system for students, faculty, and administrative staff to report lost items or turn in found belongings.
            </p>

            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3 bg-white p-3 rounded-3 shadow-sm border">
                        <i class="fa-solid fa-shield-halved fs-2 text-primary"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Secure Verification</h6>
                            <small class="text-muted">Admin approval workflow</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3 bg-white p-3 rounded-3 shadow-sm border">
                        <i class="fa-solid fa-bolt fs-2 text-warning"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Fast Match</h6>
                            <small class="text-muted">Instant category filtering</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card glass-card border-0 p-4 shadow-lg text-center">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=700&auto=format&fit=crop&q=80" alt="Team Working" class="img-fluid rounded-4 mb-3">
                <h5 class="fw-bold mb-1">Bachelor of Computer Applications (BCA)</h5>
                <p class="text-muted small">Department of Computer Applications • Final Year Project</p>
            </div>
        </div>
    </div>

    <!-- Tech Stack Highlights -->
    <div class="bg-white rounded-4 p-4 p-md-5 shadow-sm border">
        <h3 class="fw-bold text-center mb-4"><i class="fa-solid fa-layer-group text-primary me-2"></i>Technology Stack</h3>
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="p-3 border rounded-3 bg-light">
                    <i class="fa-brands fa-php fs-1 text-primary mb-2"></i>
                    <h6 class="fw-bold mb-0">Core PHP 8</h6>
                    <small class="text-muted">Backend Logic & PDO</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 border rounded-3 bg-light">
                    <i class="fa-solid fa-database fs-1 text-warning mb-2"></i>
                    <h6 class="fw-bold mb-0">MySQL / MariaDB</h6>
                    <small class="text-muted">Database Engine</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 border rounded-3 bg-light">
                    <i class="fa-brands fa-bootstrap fs-1 text-purple mb-2" style="color: #7952b3;"></i>
                    <h6 class="fw-bold mb-0">Bootstrap 5</h6>
                    <small class="text-muted">Responsive UI Layout</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 border rounded-3 bg-light">
                    <i class="fa-brands fa-js fs-1 text-warning mb-2"></i>
                    <h6 class="fw-bold mb-0">JavaScript & CSS3</h6>
                    <small class="text-muted">Interactivity & Animations</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
