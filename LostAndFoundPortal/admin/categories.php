<?php
/**
 * Lost and Found Portal - Admin Manage Categories
 */
$pageTitle = "Manage Categories";
require_once __DIR__ . '/../config/config.php';
requireAdminLogin();

$errors = [];

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = sanitize($_POST['category_name'] ?? '');
    if (empty($category_name)) {
        $errors[] = "Category name is required.";
    } else {
        $stmtCheck = $pdo->prepare("SELECT id FROM categories WHERE category_name = ?");
        $stmtCheck->execute([$category_name]);
        if ($stmtCheck->rowCount() > 0) {
            $errors[] = "Category already exists.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->execute([$category_name]);
            $_SESSION['success_msg'] = "New category added successfully!";
            redirect('admin/categories.php');
        }
    }
}

// Handle Delete Category
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $catId = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$catId]);
    $_SESSION['success_msg'] = "Category deleted successfully.";
    redirect('admin/categories.php');
}

require_once __DIR__ . '/../includes/header.php';

// Fetch Categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC");
$categories = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-tags text-dark me-2"></i>Manage Item Categories</h2>
            <p class="text-muted m-0">Add or remove categories for lost and found items</p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="row g-4">
        <!-- Add Category Form -->
        <div class="col-lg-4">
            <div class="card glass-card border-0 p-4 shadow-sm">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-plus-circle me-2 text-primary"></i>Add Category</h5>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger shadow-sm border-0 mb-3">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="categories.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" name="category_name" class="form-control" placeholder="e.g. Smartwatches" required>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary w-100 rounded-pill fw-semibold shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> Add Category
                    </button>
                </form>
            </div>
        </div>

        <!-- Category List Table -->
        <div class="col-lg-8">
            <div class="card glass-card border-0 p-4 shadow-sm">
                <h5 class="fw-bold mb-3">Existing Categories</h5>
                <div class="table-responsive">
                    <table class="table align-middle custom-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th>Created Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($categories) > 0): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td>#<?php echo $cat['id']; ?></td>
                                        <td><strong class="text-dark"><?php echo htmlspecialchars($cat['category_name']); ?></strong></td>
                                        <td><small class="text-muted"><?php echo date('M d, Y', strtotime($cat['created_at'])); ?></small></td>
                                        <td class="text-end">
                                            <a href="categories.php?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-outline-danger confirm-delete">
                                                <i class="fa-solid fa-trash me-1"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center text-muted py-4">No categories created yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
