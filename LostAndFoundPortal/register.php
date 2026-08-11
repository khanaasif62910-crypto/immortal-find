<?php
/**
 * Lost and Found Portal - User Registration
 */
$pageTitle = "Register";
require_once __DIR__ . '/includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('user/dashboard.php');
}

$errors = [];
$name = $email = $phone = $address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($_POST['name'] ?? '');
    $email    = sanitize($_POST['email'] ?? '');
    $phone    = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $address  = sanitize($_POST['address'] ?? '');

    // Validation
    if (empty($name)) $errors[] = "Full Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid Email Address is required.";
    if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) $errors[] = "Valid 10-digit Phone Number is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters long.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    // Check duplicate email
    if (empty($errors)) {
        $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->rowCount() > 0) {
            $errors[] = "An account with this email already exists.";
        }
    }

    // Insert user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, address) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $hashedPassword, $address])) {
            $_SESSION['success_msg'] = "Registration successful! You can now log in.";
            redirect('login.php');
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card glass-card border-0 p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <span class="bg-primary text-white rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-user-plus fs-4"></i>
                    </span>
                    <h3 class="fw-bold mb-1">Create Account</h3>
                    <p class="text-muted small">Register to report lost or found items in campus</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger shadow-sm border-0 mb-4">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Rahul Sharma" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                                <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile" value="<?php echo htmlspecialchars($phone); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="At least 6 chars" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-check-double"></i></span>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Campus Address / Department (Optional)</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="e.g. BCA Block B, Room 204"><?php echo htmlspecialchars($address); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold rounded-pill shadow-sm mb-3">
                        <i class="fa-solid fa-user-check me-2"></i> Register Now
                    </button>

                    <p class="text-center text-muted small mb-0">
                        Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
