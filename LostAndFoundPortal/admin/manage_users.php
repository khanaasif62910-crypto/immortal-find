<?php
/**
 * Lost and Found Portal - Admin Manage Registered Users
 */
$pageTitle = "Manage Users";
require_once __DIR__ . '/../config/config.php';
requireAdminLogin();

// Delete User
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $_SESSION['success_msg'] = "User account deleted successfully.";
    redirect('admin/manage_users.php');
}

require_once __DIR__ . '/../includes/header.php';

// Fetch All Users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-users text-primary me-2"></i>Manage Registered Users</h2>
            <p class="text-muted m-0">View student & staff user accounts</p>
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
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Address / Dept</th>
                        <th>Registered Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>#<?php echo $u['id']; ?></td>
                                <td><strong class="text-dark"><?php echo htmlspecialchars($u['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['phone']); ?></td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($u['address'] ?: 'N/A'); ?></small></td>
                                <td><small class="text-muted"><?php echo date('M d, Y', strtotime($u['created_at'])); ?></small></td>
                                <td class="text-end">
                                    <a href="manage_users.php?action=delete&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger confirm-delete" title="Delete User">
                                        <i class="fa-solid fa-trash me-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted py-5">No registered users in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
