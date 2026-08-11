<?php
/**
 * Lost and Found Portal - Navigation Bar Template
 * Responsive Dark Navigation Bar with Bootstrap 5
 */
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-2">
    <div class="container">
        <!-- Brand Logo & Name -->
        <a class="navbar-brand d-flex align-items-center fw-bold fs-4" href="<?php echo BASE_URL; ?>index.php">
            <span class="bg-primary text-white rounded-3 p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-magnifying-glass-location fs-5"></i>
            </span>
            <span class="brand-text">Lost<span class="text-primary">&</span>Found</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link px-3" href="<?php echo BASE_URL; ?>index.php">
                        <i class="fa-solid fa-house me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="<?php echo BASE_URL; ?>search.php">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Search Items
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="<?php echo BASE_URL; ?>about.php">
                        <i class="fa-solid fa-circle-info me-1"></i> About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="<?php echo BASE_URL; ?>contact.php">
                        <i class="fa-solid fa-envelope me-1"></i> Contact
                    </a>
                </li>
            </ul>

            <!-- Right Action Buttons / User Menu -->
            <div class="d-flex align-items-center gap-2">
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo BASE_URL; ?>user/report_lost.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> Report Lost
                    </a>
                    <a href="<?php echo BASE_URL; ?>user/report_found.php" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold">
                        <i class="fa-solid fa-hand-holding-heart me-1"></i> Report Found
                    </a>

                    <!-- User Dropdown Menu -->
                    <div class="dropdown ms-2">
                        <button class="btn btn-primary btn-sm rounded-pill dropdown-toggle px-3 fw-semibold d-flex align-items-center gap-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user fs-5"></i>
                            <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Account'); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" aria-labelledby="userMenu">
                            <li><a class="dropdown-item py-2" href="<?php echo BASE_URL; ?>user/dashboard.php"><i class="fa-solid fa-gauge me-2 text-primary"></i> Dashboard</a></li>
                            <li><a class="dropdown-item py-2" href="<?php echo BASE_URL; ?>user/my_reports.php"><i class="fa-solid fa-folder-open me-2 text-warning"></i> My Reported Items</a></li>
                            <li><a class="dropdown-item py-2" href="<?php echo BASE_URL; ?>user/profile.php"><i class="fa-solid fa-user-gear me-2 text-info"></i> Edit Profile</a></li>
                            <li><a class="dropdown-item py-2" href="<?php echo BASE_URL; ?>user/change_password.php"><i class="fa-solid fa-key me-2 text-secondary"></i> Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger fw-semibold" href="<?php echo BASE_URL; ?>logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                        </ul>
                    </div>

                <?php elseif (isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="btn btn-warning btn-sm rounded-pill px-3 fw-semibold text-dark">
                        <i class="fa-solid fa-user-shield me-1"></i> Admin Panel
                    </a>
                    <a href="<?php echo BASE_URL; ?>logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </a>

                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Login
                    </a>
                    <a href="<?php echo BASE_URL; ?>register.php" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                        <i class="fa-solid fa-user-plus me-1"></i> Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
