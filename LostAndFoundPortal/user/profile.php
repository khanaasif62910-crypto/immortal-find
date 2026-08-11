<?php
/**
 * Lost and Found Portal - User Profile Management
 */
$pageTitle = "Edit Profile";
require_once __DIR__ . '/../config/config.php';
requireUserLogin();

$user = getCurrentUser($pdo);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $phone   = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');

    if (empty($name)) $errors[] = "Full Name is required.";
    if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) $errors[] = "Valid 10-digit Phone Number is required.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
        if ($stmt->execute([$name, $phone, $address, $_SESSION['user_id']])) {
            $_SESSION['user_name'] = $name;
            $_SESSION['success_msg'] = "Profile details updated successfully!";
            redirect('user/profile.php');
        } else {
            $errors[] = "Failed to update profile. Please try again.";
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card glass-card border-0 p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <span class="bg-primary text-white rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                        <i class="fa-solid fa-user-gear fs-3"></i>
                    </span>
                    <h3 class="fw-bold mb-1">Edit Account Profile</h3>
                    <p class="text-muted small">Update your personal contact details</p>
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

                <form action="profile.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address (Read-only)</label>
                        <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($user['email']); ?>" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Campus Address / Department</label>
                        <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold rounded-pill shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Save Profile Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
