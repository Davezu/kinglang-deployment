<!-- <link rel="stylesheet" href="../../../public/css/client/booking_request.css"> 
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

<script src="/../../../public/js/client/design.js"></script> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://kit.fontawesome.com/066bf74adc.js" crossorigin="anonymous"></script>
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: #fff;
            color: black;
            box-shadow: 5px 0 15px rgba(25, 188, 63, 0.32);
            transition: width 0.3s;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        .sidebar.collapsed {
            width: 4.5rem;
        }

        .sidebar.expanded {
            width: 250px;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            min-height: 65px;
            position: relative; /* For absolute positioning of children */
            min-width: 250px; /* Match expanded width */
        }

        .sidebar-header img {
            position: absolute;
            left: 1rem;
        }

        .brand-text {
            margin: 0;
            position: absolute;
            left: 4rem;
            opacity: 1;
            transition: opacity 0.3s;
        }

        .toggle-btn {
            background: transparent;
            border: none;
            color: black;
            cursor: pointer;
            padding: 0.5rem;
            position: absolute;
            left: 200px; /* Position from left */
            transition: all 0.3s;
        }

        .sidebar.collapsed .toggle-btn {
            left: 0.75rem; /* Center when collapsed */
            opacity: 0;
        }

        .toggle-btn:hover {
            color: rgba(0, 0, 0, 0.8);
        }

        .sidebar-link {
            color: rgba(0, 0, 0, 0.8);
            text-decoration: none;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
            min-width: 250px; /* Match expanded width */
        }

        .sidebar-link .icon {
            min-width: 2rem;
            text-align: center;
        }

        .sidebar-link:hover {
            color: black;
            background: #d1f7c4;
        }

        .sidebar-link.active {
            color: black;
            background: #d1f7c4;
        }   

        .sidebar-link i {
            font-size: 1.25rem;
            min-width: 2rem;
            text-align: center;
        }

        .menu-text {
            opacity: 1;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
        }

        .sidebar-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-menu {
            flex: 1;
        }

        .content {
            margin-left: 250px;
            transition: margin-left 0.3s;
        }

        .content.collapsed {
            margin-left: 4.5rem;
        }

        @media (max-width: 768px) {
            .sidebar.expanded {
                width: 4.5rem;
            }
            .content {
                margin-left: 4.5rem;
            }
            .menu-text {
                opacity: 0;
            }
            .toggle-btn {
                left: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar collapsed" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header border-bottom border-secondary">
            <img src="../../../../public/images/main-logo.png" alt="logo" height="35px">
            <h5 class="brand-text menu-text">KingLang</h5>
            <button class="toggle-btn" id="toggleBtn">
                <i class="bi bi-chevron-right fs-4"></i>
            </button>
        </div>

        <div class="sidebar-content">
            <!-- Sidebar Menu -->
            <div class="sidebar-menu pb-2 ">
                <a href="/home/booking-requests" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) == 'booking-requests' ? 'active' : '' ?>">
                    <i class="bi bi-journals fs-5"></i>
                    <span class="menu-text">My Bookings</span>
                </a>
                <a href="/home/book/<?= $_SESSION["user_id"]; ?>" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) == 'book' ? 'active' : '' ?>">
                    <i class="bi bi-journal-plus fs-5"></i>
                    <span class="menu-text">Book a Trip</span>
                </a>
                <a href="/my-account" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) == 'my-account' ? 'active' : '' ?>">
                    <i class="bi bi-person-fill fs-5"></i>
                    <span class="menu-text">My Account</span>
                </a>
                <a href="#" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) == '#' ? 'active' : '' ?>">
                    <i class="bi bi-chat-square-quote-fill icon fs-5"></i>
                    <span class="menu-text">Feedback & Support</span>
                </a>
            </div>

            <!-- Sidebar Footer -->
            <div class="border-top border-secondary">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-question-circle"></i>
                    <span class="menu-text">Help</span>
                </a>
                <a href="/logout" class="sidebar-link">
                    <i class="bi bi-box-arrow-left"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('toggleBtn');
        const toggleIcon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            sidebar.classList.toggle('expanded');
            content.classList.toggle('collapsed');
            toggleIcon.classList.toggle('bi-chevron-left');
            toggleIcon.classList.toggle('bi-chevron-right');
        });

        function checkWidth() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebar.classList.remove('expanded');
                content.classList.add('collapsed');
            } else {
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('expanded');
                    sidebar.classList.remove('collapsed');
                    content.classList.remove('collapsed');
                }
            }
        }

        window.addEventListener('resize', checkWidth);
        checkWidth();
    </script> -->
</body>
</html>