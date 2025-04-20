<?php
require_once __DIR__ . "/../../../app/models/admin/NotificationModel.php";
$notificationModel = new NotificationModel();
$unreadCount = $notificationModel->getNotificationCount();
$notifications = $notificationModel->getUnreadNotifications();
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</head>


<div class="p-2 d-flex align-items-center gap-2">
    <a href="#" class="text-success"><i class="bi bi-plus-square-fill me-2 fs-5"></i></a>
    
    <div class="dropdown">
        <a href="#" class="text-success position-relative" data-bs-toggle="dropdown" aria-expanded="false" id="notificationDropdown">
            <i class="bi bi-bell-fill me-2 fs-5 text-success"></i>
            <?php if ($unreadCount > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; margin-left: -8px;">
                    <?= $unreadCount ?>
                </span>
            <?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 300px; max-height: 400px; overflow-y: auto;">
            <div class="p-2 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="m-0">Notifications</h6>
                <?php if ($unreadCount > 0): ?>
                    <a href="javascript:void(0)" class="text-decoration-none small mark-all-read">Mark all as read</a>
                <?php endif; ?>
            </div>
            <div class="notification-list">
                <?php if (count($notifications) > 0): ?>
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
                        ?>" class="dropdown-item p-2 border-bottom notification-item" data-id="<?= $notification['notification_id'] ?>">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <?php if (strpos($notification['type'], 'booking_confirmed') !== false): ?>
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <?php elseif (strpos($notification['type'], 'booking_rejected') !== false || strpos($notification['type'], 'booking_canceled') !== false): ?>
                                        <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                                    <?php elseif (strpos($notification['type'], 'rebooking') !== false): ?>
                                        <i class="bi bi-arrow-repeat text-warning fs-5"></i>
                                    <?php elseif (strpos($notification['type'], 'payment_submitted') !== false): ?>
                                        <i class="bi bi-cash-coin text-primary fs-5"></i>
                                    <?php elseif (strpos($notification['type'], 'booking_cancelled_by_client') !== false): ?>
                                        <i class="bi bi-person-x-fill text-danger fs-5"></i>
                                    <?php else: ?>
                                        <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="ms-2" style="width: calc(100% - 30px);">
                                    <p class="mb-0 small text-wrap" style="overflow-wrap: break-word; word-break: break-word;"><?= htmlspecialchars($notification['message']) ?></p>
                                    <span class="text-muted small"><?= date('M d, H:i', strtotime($notification['created_at'])) ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-3 text-center">
                        <p class="mb-0 small text-muted">No new notifications</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($notifications) > 0): ?>
                <div class="p-2 border-top text-center">
                    <a href="/admin/notifications" class="text-decoration-none small">View all notifications</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <img src="../../../public/images/profile.png" alt="profile" class="me-2" height="35px">
    <div class="">
        <div class="name text-success fw-bold" style="font-size: 12px"><?= $_SESSION["admin_name"]; ?> </div>
        <div class="role" style="font-size: 10px"><?= $_SESSION["role"]; ?></div>
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
                    // Update UI if needed
                }
            });
        });
    });
    
    // Mark all notifications as read
    const markAllReadBtn = document.querySelector('.mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the page or update UI
                    window.location.reload();
                }
            });
        });
    }
});
</script>