<?php
/**
 * Lost and Found Portal - Landing Home Page
 * Technology: PHP 8, MySQL, Bootstrap 5
 */
$pageTitle = "Home";
require_once __DIR__ . '/includes/header.php';

// Fetch Categories for Search Dropdown
$stmtCat = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC");
$categories = $stmtCat->fetchAll();

// Fetch Total Statistics
$totalLost = $pdo->query("SELECT COUNT(*) FROM lost_items WHERE status = 'Approved'")->fetchColumn();
$totalFound = $pdo->query("SELECT COUNT(*) FROM found_items WHERE status = 'Approved'")->fetchColumn();
$totalClaimedLost = $pdo->query("SELECT COUNT(*) FROM lost_items WHERE status = 'Claimed'")->fetchColumn();
$totalClaimedFound = $pdo->query("SELECT COUNT(*) FROM found_items WHERE status = 'Claimed'")->fetchColumn();
$totalReunited = $totalClaimedLost + $totalClaimedFound;

// Fetch Recent Approved Lost Items (Limit 6)
$stmtLost = $pdo->prepare("
    SELECT l.*, c.category_name 
    FROM lost_items l 
    JOIN categories c ON l.category_id = c.id 
    WHERE l.status = 'Approved' 
    ORDER BY l.created_at DESC 
    LIMIT 6
");
$stmtLost->execute();
$recentLostItems = $stmtLost->fetchAll();

// Fetch Recent Approved Found Items (Limit 6)
$stmtFound = $pdo->prepare("
    SELECT f.*, c.category_name 
    FROM found_items f 
    JOIN categories c ON f.category_id = c.id 
    WHERE f.status = 'Approved' 
    ORDER BY f.created_at DESC 
    LIMIT 6
");
$stmtFound->execute();
$recentFoundItems = $stmtFound->fetchAll();
?>

<!-- Hero Section -->
<section class="hero-gradient text-white py-5 py-md-6 mb-5">
    <div class="container text-center py-4">
        <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50 px-3 py-2 rounded-pill fw-semibold mb-3">
            <i class="fa-solid fa-graduation-cap me-1"></i> BCA Final Year Mini Project
        </span>
        <h1 class="display-4 fw-bold mb-3">Lost Something? Found Something?</h1>
        <p class="lead text-secondary mb-4 mx-auto" style="max-width: 700px;">
            A centralized campus portal to quickly report lost possessions or reconnect found items with their rightful owners safely and easily.
        </p>

        <!-- Quick Quick Actions -->
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
            <a href="user/report_lost.php" class="btn btn-danger btn-lg rounded-pill px-4 shadow">
                <i class="fa-solid fa-circle-exclamation me-2"></i> Report Lost Item
            </a>
            <a href="user/report_found.php" class="btn btn-success btn-lg rounded-pill px-4 shadow">
                <i class="fa-solid fa-hand-holding-heart me-2"></i> Report Found Item
            </a>
            <a href="search.php" class="btn btn-outline-light btn-lg rounded-pill px-4">
                <i class="fa-solid fa-magnifying-glass me-2"></i> Search Items
            </a>
        </div>

        <!-- Quick Search Bar -->
        <div class="col-lg-10 mx-auto">
            <div class="card glass-card p-3 text-dark border-0 shadow-lg">
                <form action="search.php" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="query" class="form-control border-start-0 ps-0" placeholder="Search by item name or keyword...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">Lost & Found</option>
                            <option value="lost">Lost Items Only</option>
                            <option value="found">Found Items Only</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-3 py-2">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="container mb-5">
    <!-- Live Stats Counter Bar -->
    <div class="row g-3 text-center mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <h6 class="text-uppercase text-muted fw-bold mb-1 small">Total Lost Reports</h6>
                        <h2 class="fw-bold mb-0 text-danger"><?php echo number_format($totalLost); ?></h2>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 fs-3">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <h6 class="text-uppercase text-muted fw-bold mb-1 small">Total Found Reports</h6>
                        <h2 class="fw-bold mb-0 text-success"><?php echo number_format($totalFound); ?></h2>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 fs-3">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <h6 class="text-uppercase text-muted fw-bold mb-1 small">Items Reunited</h6>
                        <h2 class="fw-bold mb-0 text-primary"><?php echo number_format($totalReunited); ?></h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 fs-3">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Lost Items Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0"><i class="fa-solid fa-circle-exclamation text-danger me-2"></i>Recently Lost Items</h3>
            <p class="text-muted small m-0">Latest items reported lost by community members</p>
        </div>
        <a href="search.php?type=lost" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">View All Lost Items &rarr;</a>
    </div>

    <div class="row g-4 mb-5">
        <?php if (count($recentLostItems) > 0): ?>
            <?php foreach ($recentLostItems as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card glass-card border-0 h-100 overflow-hidden">
                        <div class="item-img-container">
                            <span class="badge badge-lost position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                <i class="fa-solid fa-circle-dot me-1"></i> LOST
                            </span>
                            <img src="assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                 onerror="this.src='https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=500&auto=format&fit=crop&q=60';" 
                                 alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary bg-opacity-10 text-dark border px-2 py-1 rounded-2 small">
                                    <?php echo htmlspecialchars($item['category_name']); ?>
                                </span>
                                <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i> <?php echo date('M d, Y', strtotime($item['date_lost'])); ?></small>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($item['item_name']); ?></h5>
                            <p class="card-text text-secondary small flex-grow-1 text-truncate-2 mb-3">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                            <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center small">
                                <span class="text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i> <?php echo htmlspecialchars($item['location']); ?></span>
                                <?php if ($item['reward'] > 0): ?>
                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1">Reward: ₹<?php echo number_format($item['reward']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 p-3 text-center">
                            <a href="search.php?query=<?php echo urlencode($item['item_name']); ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-semibold">
                                <i class="fa-solid fa-eye me-1"></i> View Details & Contact Owner
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                <i class="fa-solid fa-inbox text-muted fs-1 mb-2"></i>
                <p class="text-muted">No lost items reported yet.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Found Items Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold m-0"><i class="fa-solid fa-hand-holding-heart text-success me-2"></i>Recently Found Items</h3>
            <p class="text-muted small m-0">Latest items turned in or reported found</p>
        </div>
        <a href="search.php?type=found" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold">View All Found Items &rarr;</a>
    </div>

    <div class="row g-4">
        <?php if (count($recentFoundItems) > 0): ?>
            <?php foreach ($recentFoundItems as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card glass-card border-0 h-100 overflow-hidden">
                        <div class="item-img-container">
                            <span class="badge badge-found position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                <i class="fa-solid fa-circle-check me-1"></i> FOUND
                            </span>
                            <img src="assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                 onerror="this.src='https://images.unsplash.com/photo-1544816155-12df9643f363?w=500&auto=format&fit=crop&q=60';" 
                                 alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary bg-opacity-10 text-dark border px-2 py-1 rounded-2 small">
                                    <?php echo htmlspecialchars($item['category_name']); ?>
                                </span>
                                <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i> <?php echo date('M d, Y', strtotime($item['date_found'])); ?></small>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($item['item_name']); ?></h5>
                            <p class="card-text text-secondary small flex-grow-1 text-truncate-2 mb-3">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                            <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center small">
                                <span class="text-muted"><i class="fa-solid fa-location-dot text-success me-1"></i> <?php echo htmlspecialchars($item['location']); ?></span>
                                <span class="text-success fw-bold"><i class="fa-solid fa-phone me-1"></i> Verified</span>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 p-3 text-center">
                            <a href="search.php?query=<?php echo urlencode($item['item_name']); ?>" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-semibold">
                                <i class="fa-solid fa-eye me-1"></i> View Details & Claim Item
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                <i class="fa-solid fa-inbox text-muted fs-1 mb-2"></i>
                <p class="text-muted">No found items reported yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
