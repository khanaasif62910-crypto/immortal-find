<?php
/**
 * Lost and Found Portal - Admin Login
 */
$pageTitle = "Admin Login";
require_once __DIR__ . '/../config/config.php';

// If admin logged in, redirect
if (isAdmin()) {
    redirect('admin/dashboard.php');
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            $_SESSION['success_msg'] = "Welcome to Admin Dashboard, " . htmlspecialchars($admin['username']) . "!";
            redirect('admin/dashboard.php');
        } else {
            $error = "Invalid administrator credentials.";
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card glass-card border-0 p-4 p-md-5 shadow-lg border-top border-4 border-warning">
                <div class="text-center mb-4">
                    <span class="bg-warning text-dark rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                        <i class="fa-solid fa-user-shield fs-3"></i>
                    </span>
                    <h3 class="fw-bold mb-1">Admin Portal</h3>
                    <p class="text-muted small">Restricted access for system administrators</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger shadow-sm border-0 mb-4 d-flex align-items-center">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    </div>
                <?php endif; ?>

                <form action="index.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Admin Username or Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="admin" value="<?php echo htmlspecialchars($username); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Admin Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning btn-lg text-dark w-100 fw-bold rounded-pill shadow-sm mb-3">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Login to Admin Panel
                    </button>

                    <p class="text-center text-muted small mb-0">
                        <a href="../index.php" class="text-secondary text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Return to Main Website</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
