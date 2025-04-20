<?php
$pageTitle = "Notifications";
// Fix the template paths to match the project structure
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="../../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../public/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../../public/css/admin/dashboard.css">
</head>
<body>
    <?php include_once __DIR__ . "/../../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <h3>Notifications</h3>
                <?php include_once __DIR__ . "/../../assets/admin_profile.php"; ?>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">All Notifications</h3>
                            <?php if (count($notifications) > 0): ?>
                                <button id="markAllReadBtn" class="btn btn-sm btn-outline-success">Mark All as Read</button>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (count($notifications) > 0): ?>
                                <div class="list-group">
                                    <?php foreach ($notifications as $notification): ?>
                                        <a href="<?php 
                                            if (strpos($notification['type'], 'booking_confirmed') !== false || 
                                                strpos($notification['type'], 'booking_rejected') !== false || 
                                                strpos($notification['type'], 'booking_canceled') !== false || 
                                                strpos($notification['type'], 'booking_cancelled_by_client') !== false) {
                                                echo '/admin/booking/view/' . $notification['reference_id'];
                                            } elseif (strpos($notification['type'], 'payment_submitted') !== false) {
                                                echo '/admin/payment-management?booking_id=' . $notification['reference_id'];
                                            } else {
                                                echo '#';
                                            }
                                        ?>" 
                                           class="list-group-item list-group-item-action <?= $notification['is_read'] ? '' : 'list-group-item-light' ?> notification-item"
                                           data-id="<?= $notification['notification_id'] ?>">
                                            <div class="d-flex w-100 justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <?php if (strpos($notification['type'], 'booking_confirmed') !== false): ?>
                                                        <i class="bi bi-check-circle-fill text-success me-3 fs-4"></i>
                                                    <?php elseif (strpos($notification['type'], 'booking_rejected') !== false || strpos($notification['type'], 'booking_canceled') !== false): ?>
                                                        <i class="bi bi-x-circle-fill text-danger me-3 fs-4"></i>
                                                    <?php elseif (strpos($notification['type'], 'rebooking') !== false): ?>
                                                        <i class="bi bi-arrow-repeat text-warning me-3 fs-4"></i>
                                                    <?php elseif (strpos($notification['type'], 'payment_submitted') !== false): ?>
                                                        <i class="bi bi-cash-coin text-primary me-3 fs-4"></i>
                                                    <?php elseif (strpos($notification['type'], 'booking_cancelled_by_client') !== false): ?>
                                                        <i class="bi bi-person-x-fill text-danger me-3 fs-4"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-info-circle-fill text-primary me-3 fs-4"></i>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h5 class="mb-1 text-wrap" style="overflow-wrap: break-word; word-break: break-word;"><?= htmlspecialchars($notification['message']) ?></h5>
                                                    </div>
                                                </div>
                                                <small class="text-muted"><?= date('M d, Y - h:i A', strtotime($notification['created_at'])) ?></small>
                                            </div>
                                            <?php if (!$notification['is_read']): ?>
                                                <span class="badge bg-primary">New</span>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i> No notifications found.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../../../public/js/assets/sidebar.js"></script>
    <script src="../../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark notification as read when clicked
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                
                fetch('/admin/notifications/mark-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'notification_id=' + notificationId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        this.classList.remove('list-group-item-light');
                        const badge = this.querySelector('.badge');
                        if (badge) {
                            badge.remove();
                        }
                    }
                });
            });
        });
        
        // Mark all notifications as read
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                fetch('/admin/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Refresh the page
                        window.location.reload();
                    }
                });
            });
        }
    });
    </script>
</body>
</html> 