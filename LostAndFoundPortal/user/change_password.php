<?php
/**
 * Lost and Found Portal - Change Password
 */
$pageTitle = "Change Password";
require_once __DIR__ . '/../config/config.php';
requireUserLogin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($current)) $errors[] = "Current Password is required.";
    if (strlen($new) < 6) $errors[] = "New password must be at least 6 characters long.";
    if ($new !== $confirm) $errors[] = "New passwords do not match.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if ($user && password_verify($current, $user['password'])) {
            $newHashed = password_hash($new, PASSWORD_BCRYPT);
            $stmtUpdate = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmtUpdate->execute([$newHashed, $_SESSION['user_id']]);
            
            $_SESSION['success_msg'] = "Password changed successfully!";
            redirect('user/dashboard.php');
        } else {
            $errors[] = "Current password is incorrect.";
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card glass-card border-0 p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <span class="bg-secondary text-white rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                        <i class="fa-solid fa-key fs-3"></i>
                    </span>
                    <h3 class="fw-bold mb-1">Change Password</h3>
                    <p class="text-muted small">Update your account security password</p>
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

                <form action="change_password.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="At least 6 characters" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter new password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold rounded-pill shadow-sm">
                        <i class="fa-solid fa-lock me-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
