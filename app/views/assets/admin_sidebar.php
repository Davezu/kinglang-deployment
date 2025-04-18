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
            border-radius: 0 10px 10px 0;
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
                <a href="/admin/dashboard" class="sidebar-link <?= basename($_SERVER["PHP_SELF"]) == 'dashboard' ? 'active' : ''; ?>">
                    <i class="bi bi-grid"></i>
                    <span class="menu-text">Dashboard</span>
                </a>   
                <a href="/admin/booking-requests" class="sidebar-link <?= basename($_SERVER["PHP_SELF"]) == 'booking-requests' || basename($_SERVER["PHP_SELF"]) == 'rebooking-requests' ? 'active' : ''; ?>">
                    <i class="bi bi-journals fs-5"></i>
                    <span class="menu-text">Bookings</span>
                </a>
                <a href="/admin/users" class="sidebar-link <?= basename($_SERVER["PHP_SELF"]) == 'users' ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Clients</span>
                </a>
                <a href="/admin/payment-management" class="sidebar-link <?= basename($_SERVER["PHP_SELF"]) == 'payment-management' ? 'active' : ''; ?>">
                    <i class="bi bi-wallet2"></i>
                    <span class="menu-text">Payments</span>
                </a>
                <a href="/admin/reports" class="sidebar-link <?= basename($_SERVER["PHP_SELF"]) == 'reports' ? 'active' : ''; ?>">
                    <i class="bi bi-clipboard-data"></i>
                    <span class="menu-text">Reports</span>  
                </a>
                <a href="/admin/settings" class="sidebar-link <?= basename($_SERVER["PHP_SELF"]) == 'settings' ? 'active' : ''; ?>">
                    <i class="bi bi-gear"></i>
                    <span class="menu-text">Settings</span>
                </a>
            </div>

            <!-- Sidebar Footer -->
            <div class="border-top border-secondary">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-question-circle"></i>
                    <span class="menu-text">Help</span>
                </a>
                <a href="/admin/logout" class="sidebar-link">
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