<?php
/**
 * Lost and Found Portal - Admin Dashboard
 */
$pageTitle = "Admin Dashboard";
require_once __DIR__ . '/../config/config.php';
requireAdminLogin();
require_once __DIR__ . '/../includes/header.php';

// Overall System Statistics
$totalUsers      = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalLost       = $pdo->query("SELECT COUNT(*) FROM lost_items")->fetchColumn();
$totalFound      = $pdo->query("SELECT COUNT(*) FROM found_items")->fetchColumn();

$pendingLost     = $pdo->query("SELECT COUNT(*) FROM lost_items WHERE status = 'Pending'")->fetchColumn();
$pendingFound    = $pdo->query("SELECT COUNT(*) FROM found_items WHERE status = 'Pending'")->fetchColumn();

// Fetch Pending Approval Items
$stmtPendingLost = $pdo->query("
    SELECT l.*, c.category_name, u.name as user_name 
    FROM lost_items l 
    JOIN categories c ON l.category_id = c.id 
    JOIN users u ON l.user_id = u.id 
    WHERE l.status = 'Pending' 
    ORDER BY l.created_at DESC LIMIT 5
");
$pendingLostList = $stmtPendingLost->fetchAll();

$stmtPendingFound = $pdo->query("
    SELECT f.*, c.category_name, u.name as user_name 
    FROM found_items f 
    JOIN categories c ON f.category_id = c.id 
    JOIN users u ON f.user_id = u.id 
    WHERE f.status = 'Pending' 
    ORDER BY f.created_at DESC LIMIT 5
");
$pendingFoundList = $stmtPendingFound->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-user-shield text-warning me-2"></i>Admin Management Dashboard</h2>
            <p class="text-muted m-0">Administrator Control Center & System Oversight</p>
        </div>
        <div class="d-flex gap-2">
            <a href="categories.php" class="btn btn-outline-primary rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-list me-1"></i> Manage Categories
            </a>
            <a href="manage_users.php" class="btn btn-outline-secondary rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-users me-1"></i> Manage Users
            </a>
        </div>
    </div>

    <!-- Statistics Overview Cards -->
    <div class="row g-3 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-primary bg-gradient border-0 shadow-sm">
                <div class="stat-content">
                    <h6 class="text-white-50 text-uppercase fw-bold small mb-1">Registered Users</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalUsers; ?></h2>
                </div>
                <i class="fa-solid fa-users stat-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-danger bg-gradient border-0 shadow-sm">
                <div class="stat-content">
                    <h6 class="text-white-50 text-uppercase fw-bold small mb-1">Total Lost Items</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalLost; ?> <small class="fs-6">(<?php echo $pendingLost; ?> pending)</small></h2>
                </div>
                <i class="fa-solid fa-box-open stat-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-success bg-gradient border-0 shadow-sm">
                <div class="stat-content">
                    <h6 class="text-white-50 text-uppercase fw-bold small mb-1">Total Found Items</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalFound; ?> <small class="fs-6">(<?php echo $pendingFound; ?> pending)</small></h2>
                </div>
                <i class="fa-solid fa-hand-holding-hand stat-icon"></i>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-dark bg-gradient border-0 shadow-sm">
                <div class="stat-content">
                    <h6 class="text-white-50 text-uppercase fw-bold small mb-1">Active Categories</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalCategories; ?></h2>
                </div>
                <i class="fa-solid fa-tags stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Links -->
    <div class="row g-3 mb-5">
        <div class="col-md-6">
            <div class="card glass-card border-0 p-4 shadow-sm h-100 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation text-danger me-2"></i>Lost Items Portal</h5>
                    <p class="text-muted small mb-0">Approve, reject, or manage all submitted lost items</p>
                </div>
                <a href="manage_lost.php" class="btn btn-danger rounded-pill px-4 fw-semibold">Manage Lost &rarr;</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card glass-card border-0 p-4 shadow-sm h-100 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-hand-holding-heart text-success me-2"></i>Found Items Portal</h5>
                    <p class="text-muted small mb-0">Approve, reject, or manage all submitted found items</p>
                </div>
                <a href="manage_found.php" class="btn btn-success rounded-pill px-4 fw-semibold">Manage Found &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Pending Lost Approvals -->
    <div class="card glass-card border-0 p-4 shadow-sm mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0"><i class="fa-solid fa-clock text-warning me-2"></i>Pending Approval: Lost Item Reports</h5>
            <a href="manage_lost.php" class="btn btn-outline-danger btn-sm rounded-pill">View All Lost Items</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle custom-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reported By</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Date Lost</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pendingLostList) > 0): ?>
                        <?php foreach ($pendingLostList as $item): ?>
                            <tr>
                                <td><strong class="text-dark"><?php echo htmlspecialchars($item['user_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-dark"><?php echo htmlspecialchars($item['category_name']); ?></span></td>
                                <td><small class="text-muted"><?php echo date('M d, Y', strtotime($item['date_lost'])); ?></small></td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($item['location']); ?></small></td>
                                <td>
                                    <a href="manage_lost.php" class="btn btn-sm btn-primary rounded-pill px-3">Review & Approve</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No pending lost item reports. All caught up!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Found Approvals -->
    <div class="card glass-card border-0 p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0"><i class="fa-solid fa-clock text-warning me-2"></i>Pending Approval: Found Item Reports</h5>
            <a href="manage_found.php" class="btn btn-outline-success btn-sm rounded-pill">View All Found Items</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle custom-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reported By</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Date Found</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pendingFoundList) > 0): ?>
                        <?php foreach ($pendingFoundList as $item): ?>
                            <tr>
                                <td><strong class="text-dark"><?php echo htmlspecialchars($item['user_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-dark"><?php echo htmlspecialchars($item['category_name']); ?></span></td>
                                <td><small class="text-muted"><?php echo date('M d, Y', strtotime($item['date_found'])); ?></small></td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($item['location']); ?></small></td>
                                <td>
                                    <a href="manage_found.php" class="btn btn-sm btn-primary rounded-pill px-3">Review & Approve</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No pending found item reports. All caught up!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
