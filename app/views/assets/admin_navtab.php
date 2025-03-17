<ul class="nav nav-tabs mt-4">
    <li class="nav-item">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard' ? 'active' : ''; ?>" aria-current="page" href="/admin/dashboard">Bookings</a>
    </li>
    <li class="nav-item">
        <a class="nav-link  <?= basename($_SERVER['PHP_SELF']) == 'resched-requests' ? 'active' : ''; ?>" href="/admin/resched-requests">Reschedule Requests</a>
    </li>
</ul>