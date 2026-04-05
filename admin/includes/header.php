<style>
    /* Reset and base styles for the new layout */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        background-color: #f4f7f6;
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
    }

    /* Navbar */
    .top-navbar {
        background-color: #fff;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .top-navbar .nav-left {
        display: flex;
        align-items: center;
    }

    .top-navbar .menu-toggle {
        background: none;
        border: none;
        font-size: 20px;
        color: #333;
        cursor: pointer;
        margin-right: 15px;
    }

    .top-navbar .logo-box {
        display: flex;
        align-items: center;
        font-weight: 700;
        font-size: 18px;
        color: #2b4b80;
    }

    .top-navbar .logo-box img {
        height: 35px;
        margin-right: 10px;
    }

    .top-navbar .nav-right {
        display: flex;
        align-items: center;
    }

    .top-navbar .nav-icon {
        font-size: 20px;
        color: #555;
        margin-right: 20px;
        position: relative;
        text-decoration: none;
    }

    .top-navbar .nav-icon .badge {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: #f05050;
        color: #fff;
        font-size: 11px;
        border-radius: 50%;
        padding: 2px 6px;
    }

    .top-navbar .user-profile {
        display: flex;
        align-items: center;
        cursor: pointer;
        position: relative;
    }

    .top-navbar .profile-avatar {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #1e40af);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 15px;
        margin-right: 10px;
        flex-shrink: 0;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }

    .top-navbar .user-info {
        display: flex;
        flex-direction: column;
        margin-right: 5px;
    }

    .top-navbar .user-name {
        font-weight: 600;
        font-size: 14px;
        color: #333;
    }

    .top-navbar .user-role {
        font-size: 12px;
        color: #888;
    }

    /* Profile Dropdown */
    .profile-dropdown {
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        min-width: 220px;
        z-index: 2000;
        display: none;
        overflow: hidden;
        animation: dropdownFadeIn 0.2s ease;
    }
    @keyframes dropdownFadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .profile-dropdown.show { display: block; }
    .profile-dropdown-header {
        padding: 16px 18px;
        background: linear-gradient(135deg, #1e3a5f, #2b4b80);
        color: #fff;
    }
    .profile-dropdown-header .pd-name {
        font-weight: 700;
        font-size: 15px;
    }
    .profile-dropdown-header .pd-role {
        font-size: 12px;
        opacity: 0.8;
        margin-top: 2px;
    }
    .profile-dropdown-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 0;
    }
    .profile-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        color: #334155;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .profile-dropdown-item i {
        width: 20px;
        text-align: center;
        color: #64748b;
        font-size: 15px;
    }
    .profile-dropdown-item:hover {
        background: #f8fafc;
        color: #1e293b;
        text-decoration: none;
    }
    .profile-dropdown-item.logout {
        color: #dc2626;
    }
    .profile-dropdown-item.logout i { color: #dc2626; }
    .profile-dropdown-item.logout:hover {
        background: #fef2f2;
    }

    /* Sidebar */
    .sidebar {
        width: 260px;
        background-color: #2b4162;
        position: fixed;
        top: 70px;
        left: 0;
        bottom: 0;
        overflow-y: auto;
        padding-top: 20px;
        z-index: 999;
    }

    .sidebar .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar .sidebar-menu li {
        margin-bottom: 5px;
    }

    .sidebar .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #a8b8cf;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-left: 4px solid transparent;
        transition: all 0.3s;
    }

    .sidebar .sidebar-menu a i {
        width: 24px;
        font-size: 16px;
        margin-right: 10px;
    }

    .sidebar .sidebar-menu a:hover,
    .sidebar .sidebar-menu a.active {
        background-color: #dbeafe;
        color: #1e3a8a;
        border-left: 4px solid #1e3a8a;
    }

    .sidebar .sidebar-menu a:hover i,
    .sidebar .sidebar-menu a.active i {
        color: #1e3a8a;
    }

    .sidebar .sidebar-menu a .arrow {
        margin-left: auto;
    }

    /* Main Content Wrapper */
    .main-wrapper {
        margin-left: 260px;
        margin-top: 70px;
        padding: 25px;
        min-height: calc(100vh - 70px);
        background-color: #f4f6f9;
    }

    /* Hiding original header stuff from style.css */
    .navbar-inverse {
        display: none !important;
    }

    .menu-section {
        display: none !important;
    }

    .footer-section {
        margin-left: 260px;
    }

    /* Responsive adjustments */
    @media(max-width: 768px) {
        .sidebar {
            left: -260px;
            transition: all 0.3s;
        }

        .sidebar.show {
            left: 0;
        }

        .main-wrapper {
            margin-left: 0;
            margin-top: 70px;
        }

        .footer-section {
            margin-left: 0;
        }
    }
</style>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
// Fetch dynamic admin display name from session
$adminUsername = isset($_SESSION['alogin']) ? $_SESSION['alogin'] : 'Admin';
// Generate initials for avatar (take first 2 chars of username, uppercase)
$adminInitials = strtoupper(substr($adminUsername, 0, 1));
if (strpos($adminUsername, ' ') !== false) {
    $parts = explode(' ', trim($adminUsername));
    $adminInitials = strtoupper(substr($parts[0],0,1) . substr(end($parts),0,1));
}
?>
<!-- Top Navbar -->
<div class="top-navbar">
    <div class="nav-left">
        <div class="logo-box">
            <span class="logo-text">Library Management System</span>
        </div>
    </div>
    <div class="nav-right">
        <div class="user-profile" id="profileToggle" onclick="toggleProfileDropdown()">
            <div class="profile-avatar"><?php echo htmlspecialchars($adminInitials); ?></div>
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars(ucfirst($adminUsername)); ?></span>
                <span class="user-role">Administrator</span>
            </div>
            <i class="fa fa-angle-down" id="profileArrow" style="color:#888; font-size:13px; transition:transform 0.2s;"></i>

            <!-- Dropdown -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-dropdown-header">
                    <div class="pd-name"><?php echo htmlspecialchars(ucfirst($adminUsername)); ?></div>
                    <div class="pd-role">Administrator</div>
                </div>
                <div class="profile-dropdown-divider"></div>
                <a href="change-password.php" class="profile-dropdown-item">
                    <i class="fa fa-cog"></i> Settings & Password
                </a>
                <div class="profile-dropdown-divider"></div>
                <a href="logout.php" class="profile-dropdown-item logout">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleProfileDropdown() {
    var dd = document.getElementById('profileDropdown');
    var arrow = document.getElementById('profileArrow');
    dd.classList.toggle('show');
    arrow.style.transform = dd.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
}
document.addEventListener('click', function(e) {
    var toggle = document.getElementById('profileToggle');
    var dd = document.getElementById('profileDropdown');
    var arrow = document.getElementById('profileArrow');
    if (toggle && dd && !toggle.contains(e.target)) {
        dd.classList.remove('show');
        arrow.style.transform = 'rotate(0deg)';
    }
});
</script>

<!-- Sidebar -->
<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i
                    class="fa fa-th-large"></i> Dashboard <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-books.php"
                class="<?php echo ($current_page == 'manage-books.php' || $current_page == 'add-book.php' || $current_page == 'edit-book.php') ? 'active' : ''; ?>"><i
                    class="fa fa-book"></i> Manage Books <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="reg-students.php" class="<?php echo ($current_page == 'reg-students.php') ? 'active' : ''; ?>"><i
                    class="fa fa-users"></i> Manage Members <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-authors.php"
                class="<?php echo ($current_page == 'manage-authors.php' || $current_page == 'add-author.php' || $current_page == 'edit-author.php') ? 'active' : ''; ?>"><i
                    class="fa fa-pencil"></i> Manage Authors <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-categories.php"
                class="<?php echo ($current_page == 'manage-categories.php' || $current_page == 'add-category.php' || $current_page == 'edit-category.php') ? 'active' : ''; ?>"><i
                    class="fa fa-folder-o"></i> Manage Category <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-issued-books.php"
                class="<?php echo ($current_page == 'manage-issued-books.php') ? 'active' : ''; ?>"><i
                    class="fa fa-list-alt"></i> Issued Books <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="issue-book.php" class="<?php echo ($current_page == 'issue-book.php') ? 'active' : ''; ?>"><i
                    class="fa fa-share-square-o"></i> Issue Book <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-requests.php" class="<?php echo ($current_page == 'manage-requests.php') ? 'active' : ''; ?>"><i
                    class="fa fa-envelope-o"></i> Book Requests <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-borrow-requests.php" class="<?php echo ($current_page == 'manage-borrow-requests.php') ? 'active' : ''; ?>"><i
                    class="fa fa-hand-paper-o"></i> Borrow Requests <i class="fa fa-angle-right arrow"></i></a></li>


        <li><a href="change-password.php"
                class="<?php echo ($current_page == 'change-password.php') ? 'active' : ''; ?>"><i class="fa fa-cog"></i>
                Settings <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout <i class="fa fa-angle-right arrow"></i></a></li>
    </ul>
</div>

<div class="main-wrapper">