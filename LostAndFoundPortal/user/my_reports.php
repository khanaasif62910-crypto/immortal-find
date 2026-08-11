<?php
/**
 * Lost and Found Portal - Manage User Reports (Edit/Delete/Mark Claimed)
 */
$pageTitle = "My Reported Items";
require_once __DIR__ . '/../config/config.php';
requireUserLogin();

$userId = $_SESSION['user_id'];

// Handle Actions: Delete or Mark Claimed
if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['type'])) {
    $action = $_GET['action'];
    $itemId = intval($_GET['id']);
    $type   = $_GET['type']; // 'lost' or 'found'
    $table  = ($type === 'lost') ? 'lost_items' : 'found_items';

    // Verify ownership
    $stmtCheck = $pdo->prepare("SELECT id FROM $table WHERE id = ? AND user_id = ?");
    $stmtCheck->execute([$itemId, $userId]);
    
    if ($stmtCheck->rowCount() > 0) {
        if ($action === 'delete') {
            $stmtDel = $pdo->prepare("DELETE FROM $table WHERE id = ?");
            $stmtDel->execute([$itemId]);
            $_SESSION['success_msg'] = "Report deleted successfully.";
        } elseif ($action === 'claimed') {
            $stmtClaim = $pdo->prepare("UPDATE $table SET status = 'Claimed' WHERE id = ?");
            $stmtClaim->execute([$itemId]);
            $_SESSION['success_msg'] = "Item status updated to Reunited / Claimed!";
        }
    } else {
        $_SESSION['error_msg'] = "Unauthorized action or item not found.";
    }
    redirect('user/my_reports.php');
}

require_once __DIR__ . '/../includes/header.php';

// Fetch All Lost Items reported by user
$stmtLost = $pdo->prepare("
    SELECT l.*, c.category_name 
    FROM lost_items l 
    JOIN categories c ON l.category_id = c.id 
    WHERE l.user_id = ? 
    ORDER BY l.created_at DESC
");
$stmtLost->execute([$userId]);
$myLost = $stmtLost->fetchAll();

// Fetch All Found Items reported by user
$stmtFound = $pdo->prepare("
    SELECT f.*, c.category_name 
    FROM found_items f 
    JOIN categories c ON f.category_id = c.id 
    WHERE f.user_id = ? 
    ORDER BY f.created_at DESC
");
$stmtFound->execute([$userId]);
$myFound = $stmtFound->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-folder-open text-primary me-2"></i>My Reported Items</h2>
            <p class="text-muted m-0">View, edit, or mark your reported lost and found listings as reunited</p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4" id="reportsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 fw-semibold" id="lost-tab" data-bs-toggle="pill" data-bs-target="#lost-content" type="button" role="tab">
                <i class="fa-solid fa-circle-exclamation me-2"></i> My Lost Reports (<?php echo count($myLost); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold ms-2" id="found-tab" data-bs-toggle="pill" data-bs-target="#found-content" type="button" role="tab">
                <i class="fa-solid fa-hand-holding-heart me-2"></i> My Found Reports (<?php echo count($myFound); ?>)
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reportsTabContent">
        <!-- Lost Reports Tab -->
        <div class="tab-pane fade show active" id="lost-content" role="tabpanel">
            <div class="card glass-card border-0 p-4 shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle custom-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Lost Date</th>
                                <th>Location</th>
                                <th>Reward</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($myLost) > 0): ?>
                                <?php foreach ($myLost as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="../assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                                     onerror="this.src='https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=100&auto=format&fit=crop&q=60';" 
                                                     class="rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($item['item_name']); ?></strong>
                                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 180px;"><?php echo htmlspecialchars($item['description']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary bg-opacity-10 text-dark"><?php echo htmlspecialchars($item['category_name']); ?></span></td>
                                        <td><small class="text-muted"><?php echo date('M d, Y', strtotime($item['date_lost'])); ?></small></td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($item['location']); ?></small></td>
                                        <td>
                                            <?php if ($item['reward'] > 0): ?>
                                                <span class="badge bg-warning text-dark fw-bold">₹<?php echo number_format($item['reward']); ?></span>
                                            <?php else: ?>
                                                <small class="text-muted">None</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($item['status'] === 'Approved'): ?>
                                                <span class="badge bg-success px-2 py-1">Approved</span>
                                            <?php elseif ($item['status'] === 'Pending'): ?>
                                                <span class="badge bg-warning text-dark px-2 py-1">Pending Approval</span>
                                            <?php elseif ($item['status'] === 'Claimed'): ?>
                                                <span class="badge bg-info text-dark px-2 py-1">Claimed / Reunited</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger px-2 py-1">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <?php if ($item['status'] !== 'Claimed'): ?>
                                                    <a href="my_reports.php?action=claimed&id=<?php echo $item['id']; ?>&type=lost" class="btn btn-outline-success" title="Mark as Reunited/Claimed">
                                                        <i class="fa-solid fa-check me-1"></i> Claimed
                                                    </a>
                                                <?php endif; ?>
                                                <a href="my_reports.php?action=delete&id=<?php echo $item['id']; ?>&type=lost" class="btn btn-outline-danger confirm-delete" title="Delete Report">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-muted py-5">You haven't reported any lost items yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Found Reports Tab -->
        <div class="tab-pane fade" id="found-content" role="tabpanel">
            <div class="card glass-card border-0 p-4 shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle custom-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Found Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($myFound) > 0): ?>
                                <?php foreach ($myFound as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="../assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                                     onerror="this.src='https://images.unsplash.com/photo-1544816155-12df9643f363?w=100&auto=format&fit=crop&q=60';" 
                                                     class="rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($item['item_name']); ?></strong>
                                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 180px;"><?php echo htmlspecialchars($item['description']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary bg-opacity-10 text-dark"><?php echo htmlspecialchars($item['category_name']); ?></span></td>
                                        <td><small class="text-muted"><?php echo date('M d, Y', strtotime($item['date_found'])); ?></small></td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($item['location']); ?></small></td>
                                        <td>
                                            <?php if ($item['status'] === 'Approved'): ?>
                                                <span class="badge bg-success px-2 py-1">Approved</span>
                                            <?php elseif ($item['status'] === 'Pending'): ?>
                                                <span class="badge bg-warning text-dark px-2 py-1">Pending Approval</span>
                                            <?php elseif ($item['status'] === 'Claimed'): ?>
                                                <span class="badge bg-info text-dark px-2 py-1">Claimed / Reunited</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger px-2 py-1">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <?php if ($item['status'] !== 'Claimed'): ?>
                                                    <a href="my_reports.php?action=claimed&id=<?php echo $item['id']; ?>&type=found" class="btn btn-outline-success" title="Mark as Reunited/Claimed">
                                                        <i class="fa-solid fa-check me-1"></i> Claimed
                                                    </a>
                                                <?php endif; ?>
                                                <a href="my_reports.php?action=delete&id=<?php echo $item['id']; ?>&type=found" class="btn btn-outline-danger confirm-delete" title="Delete Report">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center text-muted py-5">You haven't reported any found items yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
