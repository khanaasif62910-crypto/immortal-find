<?php
/**
 * Lost and Found Portal - User Dashboard
 */
$pageTitle = "User Dashboard";
require_once __DIR__ . '/../config/config.php';
requireUserLogin();
require_once __DIR__ . '/../includes/header.php';

$userId = $_SESSION['user_id'];

// Get User Statistics
$stmtLostCount = $pdo->prepare("SELECT COUNT(*) FROM lost_items WHERE user_id = ?");
$stmtLostCount->execute([$userId]);
$totalMyLost = $stmtLostCount->fetchColumn();

$stmtFoundCount = $pdo->prepare("SELECT COUNT(*) FROM found_items WHERE user_id = ?");
$stmtFoundCount->execute([$userId]);
$totalMyFound = $stmtFoundCount->fetchColumn();

$stmtClaimedCount = $pdo->prepare("
    SELECT (SELECT COUNT(*) FROM lost_items WHERE user_id = ? AND status = 'Claimed') + 
           (SELECT COUNT(*) FROM found_items WHERE user_id = ? AND status = 'Claimed')
");
$stmtClaimedCount->execute([$userId, $userId]);
$totalMyClaimed = $stmtClaimedCount->fetchColumn();

// Fetch Recent Lost Items by User
$stmtMyLost = $pdo->prepare("
    SELECT l.*, c.category_name 
    FROM lost_items l 
    JOIN categories c ON l.category_id = c.id 
    WHERE l.user_id = ? 
    ORDER BY l.created_at DESC LIMIT 5
");
$stmtMyLost->execute([$userId]);
$myLostItems = $stmtMyLost->fetchAll();

// Fetch Recent Found Items by User
$stmtMyFound = $pdo->prepare("
    SELECT f.*, c.category_name 
    FROM found_items f 
    JOIN categories c ON f.category_id = c.id 
    WHERE f.user_id = ? 
    ORDER BY f.created_at DESC LIMIT 5
");
$stmtMyFound->execute([$userId]);
$myFoundItems = $stmtMyFound->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-gauge text-primary me-2"></i>My Dashboard</h2>
            <p class="text-muted m-0">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
        </div>
        <div class="d-flex gap-2">
            <a href="report_lost.php" class="btn btn-danger rounded-pill px-3 shadow-sm fw-semibold">
                <i class="fa-solid fa-circle-exclamation me-1"></i> Report Lost Item
            </a>
            <a href="report_found.php" class="btn btn-success rounded-pill px-3 shadow-sm fw-semibold">
                <i class="fa-solid fa-hand-holding-heart me-1"></i> Report Found Item
            </a>
        </div>
    </div>

    <!-- Stat Summary Cards -->
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card stat-card bg-danger bg-gradient shadow-sm border-0">
                <div class="stat-content">
                    <h6 class="text-white-50 text-uppercase fw-bold small mb-1">My Lost Item Reports</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalMyLost; ?></h2>
                </div>
                <i class="fa-solid fa-box-open stat-icon"></i>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card bg-success bg-gradient shadow-sm border-0">
                <div class="stat-content">
                    <h6 class="text-white-50 text-uppercase fw-bold small mb-1">My Found Item Reports</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalMyFound; ?></h2>
                </div>
                <i class="fa-solid fa-hand-holding-hand stat-icon"></i>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card bg-primary bg-gradient shadow-sm border-0">
                <div class="stat-content">
                    <h6 class="text-white-50 text-uppercase fw-bold small mb-1">Claimed / Reunited Items</h6>
                    <h2 class="fw-bold mb-0"><?php echo $totalMyClaimed; ?></h2>
                </div>
                <i class="fa-solid fa-heart stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Recent Lost Items Table -->
    <div class="card glass-card border-0 p-4 shadow-sm mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0"><i class="fa-solid fa-circle-exclamation text-danger me-2"></i>My Recent Lost Reports</h5>
            <a href="my_reports.php" class="btn btn-outline-secondary btn-sm rounded-pill">View All My Reports</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle custom-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Date Lost</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($myLostItems) > 0): ?>
                        <?php foreach ($myLostItems as $item): ?>
                            <tr>
                                <td>
                                    <img src="../assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                         onerror="this.src='https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=100&auto=format&fit=crop&q=60';" 
                                         class="rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td><strong class="text-dark"><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-dark"><?php echo htmlspecialchars($item['category_name']); ?></span></td>
                                <td><small class="text-muted"><?php echo date('M d, Y', strtotime($item['date_lost'])); ?></small></td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($item['location']); ?></small></td>
                                <td>
                                    <?php if ($item['status'] === 'Approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php elseif ($item['status'] === 'Pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($item['status'] === 'Claimed'): ?>
                                        <span class="badge bg-info text-dark">Claimed</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="my_reports.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">Manage</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No lost items reported yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Found Items Table -->
    <div class="card glass-card border-0 p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0"><i class="fa-solid fa-hand-holding-heart text-success me-2"></i>My Recent Found Reports</h5>
            <a href="my_reports.php" class="btn btn-outline-secondary btn-sm rounded-pill">View All My Reports</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle custom-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Date Found</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($myFoundItems) > 0): ?>
                        <?php foreach ($myFoundItems as $item): ?>
                            <tr>
                                <td>
                                    <img src="../assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                         onerror="this.src='https://images.unsplash.com/photo-1544816155-12df9643f363?w=100&auto=format&fit=crop&q=60';" 
                                         class="rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td><strong class="text-dark"><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-dark"><?php echo htmlspecialchars($item['category_name']); ?></span></td>
                                <td><small class="text-muted"><?php echo date('M d, Y', strtotime($item['date_found'])); ?></small></td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($item['location']); ?></small></td>
                                <td>
                                    <?php if ($item['status'] === 'Approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php elseif ($item['status'] === 'Pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($item['status'] === 'Claimed'): ?>
                                        <span class="badge bg-info text-dark">Claimed</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="my_reports.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">Manage</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No found items reported yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
