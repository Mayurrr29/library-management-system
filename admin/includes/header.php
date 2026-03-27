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
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
}

.top-navbar .profile-img {
    height: 40px;
    width: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
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

.sidebar .sidebar-menu a:hover, .sidebar .sidebar-menu a.active {
    background-color: #dbeafe;
    color: #1e3a8a;
    border-left: 4px solid #1e3a8a;
}
.sidebar .sidebar-menu a:hover i, .sidebar .sidebar-menu a.active i {
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
.navbar-inverse { display: none !important; }
.menu-section { display: none !important; }
.footer-section { margin-left: 260px; }

/* Responsive adjustments */
@media(max-width: 768px) {
    .sidebar { left: -260px; transition: all 0.3s; }
    .sidebar.show { left: 0; }
    .main-wrapper { margin-left: 0; margin-top: 70px; }
    .footer-section { margin-left: 0; }
}
</style>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Top Navbar -->
<div class="top-navbar">
    <div class="nav-left">
        <button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('show')"><i class="fa fa-bars"></i></button>
        <div class="logo-box">
            <i class="fa fa-book fa-2x" style="color:#007bff; margin-right:10px;"></i>
            <span class="logo-text">Library Management System</span>
        </div>
    </div>
    <div class="nav-right">  
        <div class="user-profile">
            <img src="assets/img/user.jpg" alt="User" class="profile-img" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff'">
            <div class="user-info">
                <span class="user-name">Mayur Panchal</span>
                <span class="user-role">Admin</span>
            </div>
            <i class="fa fa-angle-down"></i>
        </div>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="dashboard.php" class="<?php echo($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="fa fa-th-large"></i> Dashboard <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-books.php" class="<?php echo($current_page == 'manage-books.php' || $current_page == 'add-book.php' || $current_page == 'edit-book.php') ? 'active' : ''; ?>"><i class="fa fa-book"></i> Manage Books <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="reg-students.php" class="<?php echo($current_page == 'reg-students.php') ? 'active' : ''; ?>"><i class="fa fa-users"></i> Manage Members <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-authors.php" class="<?php echo($current_page == 'manage-authors.php' || $current_page == 'add-author.php' || $current_page == 'edit-author.php') ? 'active' : ''; ?>"><i class="fa fa-pencil"></i> Manage Authors <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-categories.php" class="<?php echo($current_page == 'manage-categories.php' || $current_page == 'add-category.php' || $current_page == 'edit-category.php') ? 'active' : ''; ?>"><i class="fa fa-folder-o"></i> Manage Category <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="manage-issued-books.php" class="<?php echo($current_page == 'manage-issued-books.php') ? 'active' : ''; ?>"><i class="fa fa-list-alt"></i> Issued Books <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="issue-book.php" class="<?php echo($current_page == 'issue-book.php') ? 'active' : ''; ?>"><i class="fa fa-share-square-o"></i> Issue Book <i class="fa fa-angle-right arrow"></i></a></li>


        <li><a href="change-password.php" class="<?php echo($current_page == 'change-password.php') ? 'active' : ''; ?>"><i class="fa fa-cog"></i> Settings <i class="fa fa-angle-right arrow"></i></a></li>
        <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout <i class="fa fa-angle-right arrow"></i></a></li>
    </ul>
</div>

<div class="main-wrapper">