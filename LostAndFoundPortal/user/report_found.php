<?php
/**
 * Lost and Found Portal - Report Found Item Module
 */
$pageTitle = "Report Found Item";
require_once __DIR__ . '/../config/config.php';
requireUserLogin();
require_once __DIR__ . '/../includes/header.php';

// Fetch Categories
$stmtCat = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC");
$categories = $stmtCat->fetchAll();

$errors = [];
$item_name = $category_id = $description = $location = $date_found = $contact = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name   = sanitize($_POST['item_name'] ?? '');
    $category_id = sanitize($_POST['category_id'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $location    = sanitize($_POST['location'] ?? '');
    $date_found  = sanitize($_POST['date_found'] ?? '');
    $contact     = sanitize($_POST['contact'] ?? '');

    // Validations
    if (empty($item_name)) $errors[] = "Item Name is required.";
    if (empty($category_id)) $errors[] = "Category selection is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($location)) $errors[] = "Found Location is required.";
    if (empty($date_found)) $errors[] = "Found Date is required.";
    if (empty($contact) || !preg_match('/^[0-9]{10}$/', $contact)) $errors[] = "Valid 10-digit Contact Phone Number is required.";

    // Handle Image Upload
    $imageName = 'default_item.jpg';
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
        $uploadRes = uploadImage($_FILES['item_image'], '../assets/uploads/');
        if ($uploadRes['success']) {
            $imageName = $uploadRes['fileName'];
        } else {
            $errors[] = $uploadRes['message'];
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO found_items (user_id, category_id, item_name, description, image, location, date_found, contact, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
        ");
        
        if ($stmt->execute([$_SESSION['user_id'], $category_id, $item_name, $description, $imageName, $location, $date_found, $contact])) {
            $_SESSION['success_msg'] = "Found item reported successfully! It is currently pending admin approval.";
            redirect('user/my_reports.php');
        } else {
            $errors[] = "Failed to submit report. Please try again.";
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card glass-card border-0 p-4 p-md-5 shadow-lg">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="bg-success text-white rounded-circle p-3 d-inline-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <i class="fa-solid fa-hand-holding-heart fs-3"></i>
                    </span>
                    <div>
                        <h3 class="fw-bold mb-0">Report Found Item</h3>
                        <p class="text-muted small m-0">Help reunite misplaced items with their rightful owners</p>
                    </div>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger shadow-sm border-0 mb-4">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="report_found.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" class="form-control" placeholder="e.g. AirPods Pro White Case" value="<?php echo htmlspecialchars($item_name); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['category_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Detailed Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe where you found it, its condition, color, or distinguishing features..." required><?php echo htmlspecialchars($description); ?></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Found Location <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Canteen Outer Bench" value="<?php echo htmlspecialchars($location); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date Found <span class="text-danger">*</span></label>
                            <input type="date" name="date_found" class="form-control" value="<?php echo htmlspecialchars($date_found); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Contact Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="contact" class="form-control" placeholder="10-digit mobile number" value="<?php echo htmlspecialchars($contact ?: ($_SESSION['user_phone'] ?? '')); ?>" required>
                    </div>

                    <!-- Image Upload & Preview Box -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Upload Found Item Image (Optional)</label>
                        <input type="file" name="item_image" id="item_image" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                        <small class="text-muted">Supported formats: JPG, PNG, WEBP (Max size: 5MB)</small>

                        <div id="image_preview_container" class="mt-3 text-center d-none">
                            <img id="image_preview" src="#" alt="Preview" class="img-thumbnail image-preview-box shadow-sm">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 fw-semibold rounded-pill shadow-sm">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Found Item Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
