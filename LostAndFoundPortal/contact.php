<?php
/**
 * Lost and Found Portal - Contact Helpdesk Page
 */
$pageTitle = "Contact Us";
require_once __DIR__ . '/includes/header.php';

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card glass-card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 bg-dark text-white p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-primary text-white mb-3">Helpdesk & Support</span>
                            <h3 class="fw-bold mb-3">Get in Touch</h3>
                            <p class="text-secondary small mb-4">
                                Have questions regarding a lost item or need administrator assistance? Send us a message and our campus team will respond promptly.
                            </p>

                            <div class="d-flex align-items-center mb-3">
                                <i class="fa-solid fa-location-dot text-primary fs-5 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Campus Address</h6>
                                    <small class="text-secondary">Department of Computer Applications, Block B</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <i class="fa-solid fa-phone text-primary fs-5 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Helpdesk Phone</h6>
                                    <small class="text-secondary">+91 98765 43210 / Ext 402</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <i class="fa-solid fa-envelope text-primary fs-5 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Email Support</h6>
                                    <small class="text-secondary">support@lostandfound.local</small>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-top border-secondary">
                            <small class="text-secondary">BCA Mini Project Helpdesk • Mon - Fri (9 AM - 5 PM)</small>
                        </div>
                    </div>

                    <div class="col-md-7 p-4 p-md-5 bg-white">
                        <h4 class="fw-bold mb-4">Send Us a Message</h4>

                        <?php if ($success): ?>
                            <div class="alert alert-success border-0 shadow-sm mb-4">
                                <i class="fa-solid fa-circle-check me-2"></i> Thank you! Your inquiry has been sent to the admin team.
                            </div>
                        <?php endif; ?>

                        <form action="contact.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Your Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Rahul Sharma" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="Inquiry regarding Lost ID Card" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Message</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="Describe your inquiry or item details..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-semibold shadow-sm">
                                <i class="fa-solid fa-paper-plane me-2"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/header.php'; ?>
