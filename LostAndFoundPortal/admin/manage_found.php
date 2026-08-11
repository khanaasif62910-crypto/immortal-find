<?php
/**
 * Lost and Found Portal - Admin Manage Found Items
 */
$pageTitle = "Manage Found Items";
require_once __DIR__ . '/../config/config.php';
requireAdminLogin();

// Handle Status Updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $itemId = intval($_GET['id']);

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE found_items SET status = 'Approved' WHERE id = ?");
        $stmt->execute([$itemId]);
        $_SESSION['success_msg'] = "Found item report approved successfully!";
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE found_items SET status = 'Rejected' WHERE id = ?");
        $stmt->execute([$itemId]);
        $_SESSION['success_msg'] = "Found item report rejected.";
    } elseif ($action === 'claimed') {
        $stmt = $pdo->prepare("UPDATE found_items SET status = 'Claimed' WHERE id = ?");
        $stmt->execute([$itemId]);
        $_SESSION['success_msg'] = "Status updated to Claimed / Reunited.";
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM found_items WHERE id = ?");
        $stmt->execute([$itemId]);
        $_SESSION['success_msg'] = "Report deleted permanently.";
    }
    redirect('admin/manage_found.php');
}

require_once __DIR__ . '/../includes/header.php';

// Fetch All Found Items
$stmt = $pdo->query("
    SELECT f.*, c.category_name, u.name as user_name, u.email as user_email 
    FROM found_items f 
    JOIN categories c ON f.category_id = c.id 
    JOIN users u ON f.user_id = u.id 
    ORDER BY f.created_at DESC
");
$foundItems = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-hand-holding-heart text-success me-2"></i>Manage Found Item Listings</h2>
            <p class="text-muted m-0">Review pending found item submissions, approve, reject or delete records</p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card glass-card border-0 p-4 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle custom-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Item & Image</th>
                        <th>Finder Details</th>
                        <th>Category</th>
                        <th>Location & Date</th>
                        <th>Status</th>
                        <th class="text-end">Admin Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($foundItems) > 0): ?>
                        <?php foreach ($foundItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="../assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                             onerror="this.src='https://images.unsplash.com/photo-1544816155-12df9643f363?w=100&auto=format&fit=crop&q=60';" 
                                             class="rounded-3 shadow-sm" style="width: 55px; height: 55px; object-fit: cover;">
                                        <div>
                                            <strong class="text-dark d-block"><?php echo htmlspecialchars($item['item_name']); ?></strong>
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 180px;"><?php echo htmlspecialchars($item['description']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong class="d-block text-dark small"><?php echo htmlspecialchars($item['user_name']); ?></strong>
                                    <small class="text-muted"><?php echo htmlspecialchars($item['contact']); ?></small>
                                </td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-dark"><?php echo htmlspecialchars($item['category_name']); ?></span></td>
                                <td>
                                    <small class="d-block text-muted"><i class="fa-solid fa-location-dot me-1 text-success"></i><?php echo htmlspecialchars($item['location']); ?></small>
                                    <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo date('M d, Y', strtotime($item['date_found'])); ?></small>
                                </td>
                                <td>
                                    <?php if ($item['status'] === 'Approved'): ?>
                                        <span class="badge bg-success px-3 py-1">Approved</span>
                                    <?php elseif ($item['status'] === 'Pending'): ?>
                                        <span class="badge bg-warning text-dark px-3 py-1">Pending</span>
                                    <?php elseif ($item['status'] === 'Claimed'): ?>
                                        <span class="badge bg-info text-dark px-3 py-1">Claimed</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger px-3 py-1">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($item['status'] !== 'Approved'): ?>
                                            <a href="manage_found.php?action=approve&id=<?php echo $item['id']; ?>" class="btn btn-outline-success" title="Approve Listing">
                                                <i class="fa-solid fa-check me-1"></i> Approve
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($item['status'] !== 'Rejected'): ?>
                                            <a href="manage_found.php?action=reject&id=<?php echo $item['id']; ?>" class="btn btn-outline-warning" title="Reject Listing">
                                                <i class="fa-solid fa-ban me-1"></i> Reject
                                            </a>
                                        <?php endif; ?>

                                        <a href="manage_found.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-outline-danger confirm-delete" title="Delete Record">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-5">No found items reported in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
