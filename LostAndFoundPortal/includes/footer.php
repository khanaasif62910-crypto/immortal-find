<?php
/**
 * Lost and Found Portal - Footer Template
 * Responsive Dark Footer with Bootstrap 5
 */
?>
<footer class="bg-dark text-white pt-5 pb-3 mt-auto border-top border-secondary border-opacity-25">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: About Portal -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="bg-primary text-white rounded-3 p-2 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-magnifying-glass-location"></i>
                    </span>
                    <h5 class="fw-bold m-0 text-white"><?php echo SITE_NAME; ?></h5>
                </div>
                <p class="text-secondary small leading-relaxed">
                    A centralized campus & community platform designed to report, locate, and reunite lost possessions with their rightful owners safely and quickly.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-uppercase fw-bold text-light mb-3">Quick Links</h6>
                <ul class="list-unstyled text-secondary small">
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>index.php" class="text-decoration-none text-secondary hover-white"><i class="fa-solid fa-angle-right me-1 text-primary"></i> Home</a></li>
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>search.php" class="text-decoration-none text-secondary hover-white"><i class="fa-solid fa-angle-right me-1 text-primary"></i> Search Portal</a></li>
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>about.php" class="text-decoration-none text-secondary hover-white"><i class="fa-solid fa-angle-right me-1 text-primary"></i> About Project</a></li>
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>contact.php" class="text-decoration-none text-secondary hover-white"><i class="fa-solid fa-angle-right me-1 text-primary"></i> Contact Helpdesk</a></li>
                </ul>
            </div>

            <!-- Col 3: Report Categories -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold text-light mb-3">Item Categories</h6>
                <ul class="list-unstyled text-secondary small">
                    <li class="mb-2"><i class="fa-solid fa-laptop text-info me-2"></i> Laptops & Electronics</li>
                    <li class="mb-2"><i class="fa-solid fa-id-card text-warning me-2"></i> ID Cards & Documents</li>
                    <li class="mb-2"><i class="fa-solid fa-key text-success me-2"></i> Keys & Accessories</li>
                    <li class="mb-2"><i class="fa-solid fa-wallet text-danger me-2"></i> Wallets & Purses</li>
                </ul>
            </div>

            <!-- Col 4: Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold text-light mb-3">BCA Project Details</h6>
                <p class="text-secondary small mb-1"><i class="fa-solid fa-graduation-cap text-primary me-2"></i> Final Year Mini Project</p>
                <p class="text-secondary small mb-1"><i class="fa-solid fa-code text-primary me-2"></i> Stack: PHP 8, MySQL, Bootstrap 5</p>
                <p class="text-secondary small mb-1"><i class="fa-solid fa-location-dot text-primary me-2"></i> Department of Computer Applications</p>
                <p class="text-secondary small"><i class="fa-solid fa-envelope text-primary me-2"></i> support@lostandfound.local</p>
            </div>
        </div>

        <hr class="my-4 border-secondary border-opacity-25">

        <div class="row align-items-center text-center text-md-start small text-secondary">
            <div class="col-md-6 mb-2 mb-md-0">
                &copy; <?php echo date('Y'); ?> <strong>Lost & Found Portal</strong>. Built for BCA Final Year Mini Project.
            </div>
            <div class="col-md-6 text-md-end">
                <a href="<?php echo BASE_URL; ?>admin/index.php" class="text-secondary text-decoration-none me-3"><i class="fa-solid fa-user-shield me-1"></i> Admin Portal</a>
                <a href="#top" class="text-secondary text-decoration-none"><i class="fa-solid fa-arrow-up me-1"></i> Back to Top</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5.3 JS Bundle (Includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>
