<?php
require_once __DIR__ . "/../../../app/models/admin/NotificationModel.php";
$notificationModel = new NotificationModel();
$unreadCount = $notificationModel->getNotificationCount();
$notifications = $notificationModel->getUnreadNotifications();
?>

<div class="p-2 d-flex align-items-center gap-2">
    <a href="#" class="text-success"><i class="bi bi-plus-square-fill me-2 fs-5"></i></a>
    
    <div class="dropdown">
        <a href="#" class="text-success position-relative dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="notificationDropdown">
            <i class="bi bi-bell-fill me-2 fs-5 text-success"></i>
            <?php if ($unreadCount > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; margin-left: -8px;">
                    <?= $unreadCount ?>
                </span>
            <?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 300px; max-height: 400px; overflow-y: auto;" id="notificationDropdownMenu">
            <div class="p-2 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="m-0">Notifications</h6>
                <?php if ($unreadCount > 0): ?>
                    <a href="javascript:void(0)" class="text-decoration-none small mark-all-read">Mark all as read</a>
                <?php endif; ?>
            </div>
            <div class="notification-list">
                <?php if (count($notifications) > 0): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <a href="<?= $notification['type'] == 'booking_confirmed' || $notification['type'] == 'booking_rejected' || $notification['type'] == 'booking_canceled' ? '/admin/booking/view/' . $notification['reference_id'] : '#' ?>" class="dropdown-item p-2 border-bottom notification-item" data-id="<?= $notification['notification_id'] ?>">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <?php if (strpos($notification['type'], 'booking_confirmed') !== false): ?>
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <?php elseif (strpos($notification['type'], 'booking_rejected') !== false || strpos($notification['type'], 'booking_canceled') !== false): ?>
                                        <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                                    <?php elseif (strpos($notification['type'], 'rebooking') !== false): ?>
                                        <i class="bi bi-arrow-repeat text-warning fs-5"></i>
                                    <?php else: ?>
                                        <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="ms-2">
                                    <p class="mb-0 small"><?= htmlspecialchars($notification['message']) ?></p>
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

<!-- Make sure Bootstrap JS is loaded -->
<script>
// Add Bootstrap JS if not already loaded
if (typeof bootstrap === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
    script.integrity = 'sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz';
    script.crossOrigin = 'anonymous';
    document.head.appendChild(script);
}

document.addEventListener('DOMContentLoaded', function() {
    // Manual dropdown toggle functionality
    const notificationToggle = document.getElementById('notificationDropdown');
    const notificationMenu = document.getElementById('notificationDropdownMenu');
    
    notificationToggle.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Try to use Bootstrap's dropdown if available
        if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            const dropdown = bootstrap.Dropdown.getInstance(notificationToggle) || 
                             new bootstrap.Dropdown(notificationToggle);
            dropdown.toggle();
        } else {
            // Fallback to manual toggle
            if (notificationMenu.classList.contains('show')) {
                notificationMenu.classList.remove('show');
            } else {
                notificationMenu.classList.add('show');
                notificationMenu.style.display = 'block';
                notificationMenu.style.position = 'absolute';
                notificationMenu.style.inset = '0px auto auto 0px';
                notificationMenu.style.margin = '0px';
                notificationMenu.style.transform = 'translate(-225px, 40px)';
            }
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationToggle.contains(e.target) && !notificationMenu.contains(e.target)) {
            notificationMenu.classList.remove('show');
        }
    });
    
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