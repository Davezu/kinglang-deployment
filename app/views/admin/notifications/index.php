<?php
$pageTitle = "Notifications";
include_once __DIR__ . "/../../templates/header.php";
include_once __DIR__ . "/../../templates/admin_sidebar.php";
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Notifications</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Notifications</li>
                    </ol>
                </div>
            </div>
        </div>
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
                                <a href="<?= $notification['type'] == 'booking_confirmed' || $notification['type'] == 'booking_rejected' || $notification['type'] == 'booking_canceled' ? '/admin/booking/view/' . $notification['reference_id'] : '#' ?>" 
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
                                            <?php else: ?>
                                                <i class="bi bi-info-circle-fill text-primary me-3 fs-4"></i>
                                            <?php endif; ?>
                                            <div>
                                                <h5 class="mb-1"><?= htmlspecialchars($notification['message']) ?></h5>
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

<?php include_once __DIR__ . "/../../templates/footer.php"; ?> 