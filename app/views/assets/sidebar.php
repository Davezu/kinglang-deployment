<link rel="stylesheet" href="../../../public/css/client/booking_request.css"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<div class="sidebar" id="sidebar">
    <div class="wrapper">
    <div class="brand">
        <div class="name-logo">
            <img src="../../../public/images/main-logo.png" alt="icon" height="30px">
            <span class="text">KingLang</span>
        </div>
        <div class="toggle-container">
            <i class="toggle-btn bi bi-layout-sidebar-inset" id="toggle-btn"></i>
        </div>
    </div>
    <nav class="nav flex-column">
        <a href="/home/booking-requests" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'booking-requests' ? 'active' : '' ?>">
            <i class="bi bi-journals icon fs-5"></i>
            <span class="text">My Bookings</span>
        </a>
        <a href="/home/book/<?= $_SESSION["user_id"]; ?>" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'book' ? 'active' : '' ?>">
            <i class="bi bi-journal-plus icon fs-5"></i>
            <span class="text">Book a Trip</span>
        </a>
        <a href="/my-account" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'my-account' ? 'active' : '' ?>">
            <i class="bi bi-person-fill icon fs-5"></i>
            <span class="text">My Account</span>
        </a>
        <a href="#" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == '#' ? 'active' : '' ?>">
            <i class="bi bi-chat-square-quote-fill icon fs-5"></i>
            <span class="text">Feedback & Support</span>
        </a>
    </nav>
    </div>

    <nav class="nav flex-column">
        <a href="#" class="nav-link">
            <i class="bi bi-question-circle icon fs-5"></i>
            <span class="text">Help</span>
        </a>
        <a href="/logout" class="nav-link">
            <i class="bi bi-box-arrow-right icon fs-5"></i>
            <span class="text">Log out</span>
        </a>
    </nav>

</div>


<script src="/../../../public/js/client/design.js"></script>