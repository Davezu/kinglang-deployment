<ul class="nav nav-tabs mt-4">
    <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'booking-requests' ? 'active' : ''; ?>" aria-current="page" href="/admin/booking-requests">Bookings</a>
    </li>
    <li class="nav-item">
        <a class="nav-link  <?= basename($_SERVER['PHP_SELF']) == 'resched-requests' ? 'active' : ''; ?>" href="/admin/resched-requests">Reschedule Requests</a>
    </li>
</ul>