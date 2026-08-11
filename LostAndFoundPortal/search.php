<?php
/**
 * Lost and Found Portal - Comprehensive Search Module
 * Filters: Item Name, Category, Date Range, Lost/Found Type
 */
$pageTitle = "Search Items";
require_once __DIR__ . '/includes/header.php';

// Fetch Categories for Filter
$stmtCat = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC");
$categories = $stmtCat->fetchAll();

// Get Search Parameters
$query    = sanitize($_GET['query'] ?? '');
$category = sanitize($_GET['category'] ?? '');
$type     = sanitize($_GET['type'] ?? ''); // 'lost', 'found', or ''
$date_from = sanitize($_GET['date_from'] ?? '');
$date_to   = sanitize($_GET['date_to'] ?? '');

$items = [];

// Query Lost Items if type is 'lost' or empty
if ($type === 'lost' || empty($type)) {
    $sqlLost = "SELECT l.id, l.item_name, l.description, l.image, l.location, l.date_lost AS item_date, l.reward, l.contact, l.status, c.category_name, 'Lost' AS report_type 
                FROM lost_items l 
                JOIN categories c ON l.category_id = c.id 
                WHERE l.status IN ('Approved', 'Claimed')";
    $paramsLost = [];

    if (!empty($query)) {
        $sqlLost .= " AND (l.item_name LIKE ? OR l.description LIKE ? OR l.location LIKE ?)";
        $searchTerm = "%$query%";
        $paramsLost[] = $searchTerm;
        $paramsLost[] = $searchTerm;
        $paramsLost[] = $searchTerm;
    }

    if (!empty($category)) {
        $sqlLost .= " AND l.category_id = ?";
        $paramsLost[] = $category;
    }

    if (!empty($date_from)) {
        $sqlLost .= " AND l.date_lost >= ?";
        $paramsLost[] = $date_from;
    }

    if (!empty($date_to)) {
        $sqlLost .= " AND l.date_lost <= ?";
        $paramsLost[] = $date_to;
    }

    $stmt = $pdo->prepare($sqlLost);
    $stmt->execute($paramsLost);
    $items = array_merge($items, $stmt->fetchAll());
}

// Query Found Items if type is 'found' or empty
if ($type === 'found' || empty($type)) {
    $sqlFound = "SELECT f.id, f.item_name, f.description, f.image, f.location, f.date_found AS item_date, 0 AS reward, f.contact, f.status, c.category_name, 'Found' AS report_type 
                 FROM found_items f 
                 JOIN categories c ON f.category_id = c.id 
                 WHERE f.status IN ('Approved', 'Claimed')";
    $paramsFound = [];

    if (!empty($query)) {
        $sqlFound .= " AND (f.item_name LIKE ? OR f.description LIKE ? OR f.location LIKE ?)";
        $searchTerm = "%$query%";
        $paramsFound[] = $searchTerm;
        $paramsFound[] = $searchTerm;
        $paramsFound[] = $searchTerm;
    }

    if (!empty($category)) {
        $sqlFound .= " AND f.category_id = ?";
        $paramsFound[] = $category;
    }

    if (!empty($date_from)) {
        $sqlFound .= " AND f.date_found >= ?";
        $paramsFound[] = $date_from;
    }

    if (!empty($date_to)) {
        $sqlFound .= " AND f.date_found <= ?";
        $paramsFound[] = $date_to;
    }

    $stmt = $pdo->prepare($sqlFound);
    $stmt->execute($paramsFound);
    $items = array_merge($items, $stmt->fetchAll());
}

// Sort all items by date descending
usort($items, function($a, $b) {
    return strtotime($b['item_date']) - strtotime($a['item_date']);
});
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-magnifying-glass text-primary me-2"></i>Search Lost & Found Directory</h2>
            <p class="text-muted">Filter reported items by keywords, categories, dates, and status</p>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card glass-card border-0 p-4 mb-5 shadow-sm">
        <form action="search.php" method="GET" class="row g-3">
            <div class="col-lg-4 col-md-6">
                <label class="form-label fw-semibold">Keywords</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="query" class="form-control" placeholder="Laptop, ID card, wallet, keys..." value="<?php echo htmlspecialchars($query); ?>">
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold">Item Type</label>
                <select name="type" class="form-select">
                    <option value="">All (Lost & Found)</option>
                    <option value="lost" <?php echo $type === 'lost' ? 'selected' : ''; ?>>Lost Items Only</option>
                    <option value="found" <?php echo $type === 'found' ? 'selected' : ''; ?>>Found Items Only</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-8">
                <label class="form-label fw-semibold">Date Range</label>
                <div class="input-group">
                    <input type="date" name="date_from" class="form-control" value="<?php echo htmlspecialchars($date_from); ?>" placeholder="From">
                    <span class="input-group-text bg-light text-muted">-</span>
                    <input type="date" name="date_to" class="form-control" value="<?php echo htmlspecialchars($date_to); ?>" placeholder="To">
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                <a href="search.php" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Filters
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="fa-solid fa-filter me-1"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Search Results Grid -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0 text-secondary">
            Found <?php echo count($items); ?> match<?php echo count($items) === 1 ? '' : 'es'; ?>
        </h5>
    </div>

    <div class="row g-4">
        <?php if (count($items) > 0): ?>
            <?php foreach ($items as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card glass-card border-0 h-100 overflow-hidden">
                        <div class="item-img-container">
                            <?php if ($item['report_type'] === 'Lost'): ?>
                                <span class="badge badge-lost position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                    <i class="fa-solid fa-circle-dot me-1"></i> LOST
                                </span>
                            <?php else: ?>
                                <span class="badge badge-found position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                    <i class="fa-solid fa-circle-check me-1"></i> FOUND
                                </span>
                            <?php endif; ?>

                            <?php if ($item['status'] === 'Claimed'): ?>
                                <span class="badge bg-secondary position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                    <i class="fa-solid fa-check-double me-1"></i> REUNITED / CLAIMED
                                </span>
                            <?php endif; ?>

                            <img src="assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                 onerror="this.src='https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=500&auto=format&fit=crop&q=60';" 
                                 alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary bg-opacity-10 text-dark border px-2 py-1 rounded-2 small">
                                    <?php echo htmlspecialchars($item['category_name']); ?>
                                </span>
                                <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i> <?php echo date('M d, Y', strtotime($item['item_date'])); ?></small>
                            </div>

                            <h5 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($item['item_name']); ?></h5>
                            <p class="card-text text-secondary small flex-grow-1 mb-3">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>

                            <div class="border-top pt-3 mt-auto small">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted"><i class="fa-solid fa-location-dot me-1 text-primary"></i> Location:</span>
                                    <span class="fw-semibold text-dark"><?php echo htmlspecialchars($item['location']); ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted"><i class="fa-solid fa-phone me-1 text-success"></i> Contact:</span>
                                    <a href="tel:<?php echo htmlspecialchars($item['contact']); ?>" class="fw-bold text-decoration-none text-success">
                                        <?php echo htmlspecialchars($item['contact']); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-0 p-3 text-center">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-semibold" data-bs-toggle="modal" data-bs-target="#itemModal<?php echo $item['report_type'] . $item['id']; ?>">
                                <i class="fa-solid fa-eye me-1"></i> View Full Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Detail Modal for Item -->
                <div class="modal fade" id="itemModal<?php echo $item['report_type'] . $item['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="modal-header <?php echo $item['report_type'] === 'Lost' ? 'bg-danger' : 'bg-success'; ?> text-white p-3">
                                <h5 class="modal-header-title fw-bold mb-0">
                                    <i class="fa-solid fa-box-open me-2"></i> <?php echo htmlspecialchars($item['item_name']); ?>
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <img src="assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                     onerror="this.src='https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=500&auto=format&fit=crop&q=60';" 
                                     class="img-fluid rounded-3 mb-3 w-100 image-preview-box" alt="Item Image">

                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <strong class="text-muted">Type:</strong>
                                        <span class="badge <?php echo $item['report_type'] === 'Lost' ? 'bg-danger' : 'bg-success'; ?> px-3 py-1 rounded-pill">
                                            <?php echo $item['report_type']; ?> Item
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <strong class="text-muted">Category:</strong>
                                        <span><?php echo htmlspecialchars($item['category_name']); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <strong class="text-muted">Date:</strong>
                                        <span><?php echo date('F d, Y', strtotime($item['item_date'])); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <strong class="text-muted">Location:</strong>
                                        <span><?php echo htmlspecialchars($item['location']); ?></span>
                                    </li>
                                    <?php if ($item['reward'] > 0): ?>
                                        <li class="list-group-item d-flex justify-content-between px-0 text-warning">
                                            <strong class="text-dark">Reward Offered:</strong>
                                            <span class="fw-bold">₹<?php echo number_format($item['reward']); ?></span>
                                        </li>
                                    <?php endif; ?>
                                    <li class="list-group-item px-0">
                                        <strong class="text-muted d-block mb-1">Description:</strong>
                                        <p class="text-secondary small mb-0"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                                    </li>
                                </ul>

                                <div class="p-3 bg-light rounded-3 text-center border">
                                    <small class="text-muted d-block mb-1">Contact Phone Number:</small>
                                    <a href="tel:<?php echo htmlspecialchars($item['contact']); ?>" class="btn btn-success fw-bold px-4 rounded-pill">
                                        <i class="fa-solid fa-phone me-2"></i> Call <?php echo htmlspecialchars($item['contact']); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border">
                <i class="fa-solid fa-magnifying-glass text-muted fs-1 mb-3"></i>
                <h5 class="fw-bold text-dark">No Items Found</h5>
                <p class="text-muted small">Try adjusting your keyword, category, or date range filters.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
