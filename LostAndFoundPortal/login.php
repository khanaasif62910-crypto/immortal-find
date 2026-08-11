<?php
/**
 * Lost and Found Portal - User Login
 */
$pageTitle = "Login";
require_once __DIR__ . '/includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('user/dashboard.php');
} elseif (isAdmin()) {
    redirect('admin/dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Regeneration of session id for security against session fixation
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            $_SESSION['success_msg'] = "Welcome back, " . htmlspecialchars($user['name']) . "!";
            redirect('user/dashboard.php');
        } else {
            $error = "Invalid email address or password.";
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card glass-card border-0 p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <span class="bg-primary text-white rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-right-to-bracket fs-4"></i>
                    </span>
                    <h3 class="fw-bold mb-1">Welcome Back</h3>
                    <p class="text-muted small">Sign in to manage your lost & found reports</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger shadow-sm border-0 mb-4 d-flex align-items-center">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold rounded-pill shadow-sm mb-3">
                        <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Sign In
                    </button>

                    <div class="text-center text-muted small mt-3">
                        <p class="mb-1">Don't have an account? <a href="register.php" class="text-primary fw-bold text-decoration-none">Register here</a></p>
                        <p class="mb-0"><a href="admin/index.php" class="text-secondary text-decoration-none"><i class="fa-solid fa-user-shield me-1"></i> Admin Login Portal</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
