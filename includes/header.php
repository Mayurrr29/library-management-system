<?php
// Fetch current user data for header
$_header_name = "User";
$_header_email = "";
$_header_img = "";

if (isset($_SESSION['stdid']) && isset($dbh)) {
    $sid = $_SESSION['stdid'];
    $sql = "SELECT FullName, EmailId, ProfileImage FROM tblstudents WHERE StudentId=:sid";
    $q = $dbh->prepare($sql);
    $q->bindParam(':sid', $sid, PDO::PARAM_STR);
    $q->execute();
    $u = $q->fetch(PDO::FETCH_OBJ);
    if ($u) {
        $_header_name = $u->FullName ?: "User";
        $_header_email = $u->EmailId ?: "";
        $_header_img = (!empty($u->ProfileImage) && file_exists("assets/img/profiles/" . $u->ProfileImage))
            ? "assets/img/profiles/" . $u->ProfileImage
            : "";
    }
}
$_header_initials = strtoupper(substr($_header_name, 0, 1));
?>

<!-- Old navbar hidden by CSS below -->
<div class="navbar navbar-inverse set-radius-zero">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand"><img src="assets/img/logo.png" /></a>
        </div>
        <?php if ($_SESSION['login']): ?>
            <div class="right-div">
                <a href="logout.php" class="btn btn-danger pull-right">LOG ME OUT</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($_SESSION['login']):
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            background-color: #f7f8f8;
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow-x: hidden;
            color: #111827;
        }

        /* ── Hide legacy elements ── */
        .navbar-inverse {
            display: none !important;
        }

        .menu-section {
            display: none !important;
        }

        .footer-section {
            padding-left: 0;
            margin-top: 40px;
        }

        /* ── Main wrapper reset ── */
        .content-wrapper {
            margin-left: 0 !important;
            margin-top: 60px !important;
            padding: 0 !important;
            min-height: calc(100vh - 60px) !important;
            background: #f7f8f8 !important;
            border: none !important;
        }

        /* ══════════════════════════════
       TOP NAV
    ══════════════════════════════ */
        .u-topnav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: #ffffff;
            border-bottom: 1px solid #e8e8e8;
            padding: 0 40px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0;
        }

        /* Brand */
        .u-topnav__brand {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.4px;
            text-decoration: none;
            white-space: nowrap;
            margin-right: 40px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .u-topnav__brand-mark {
            width: 7px;
            height: 7px;
            border-radius: 2px;
            background: #18534f;
            display: inline-block;
            flex-shrink: 0;
        }

        /* Nav links */
        .u-topnav__nav {
            display: flex;
            align-items: center;
            gap: 0;
            list-style: none;
            margin: 0;
            padding: 0;
            flex: 1;
            height: 100%;
        }

        .u-topnav__nav li {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .u-topnav__nav li a {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0 16px;
            font-weight: 500;
            font-size: 13.5px;
            color: #374151;
            text-decoration: none;
            transition: color 0.15s;
            border-bottom: 2px solid transparent;
            letter-spacing: -0.1px;
            position: relative;
            top: 1px;
        }

        .u-topnav__nav li a:hover {
            color: #111827;
        }

        .u-topnav__nav li a.active {
            color: #18534f;
            border-bottom-color: #18534f;
            font-weight: 600;
        }

        /* Right section */
        .u-topnav__right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .u-topnav__divider {
            width: 1px;
            height: 22px;
            background: #e8e8e8;
            margin: 0 4px;
        }

        /* ── User dropdown ── */
        .u-dropdown {
            position: relative;
        }

        .u-dropdown__trigger {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            padding: 5px 10px 5px 5px;
            border-radius: 8px;
            transition: background 0.15s;
            border: none;
            background: none;
        }

        .u-dropdown__trigger:hover {
            background: #f3f4f6;
        }

        .u-dropdown__avatar {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            object-fit: cover;
            display: block;
        }

        .u-dropdown__avatar-fallback {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            background: #18534f;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .u-dropdown__info {
            text-align: left;
            line-height: 1.25;
        }

        .u-dropdown__name {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .u-dropdown__role {
            font-size: 11px;
            color: #6b7280;
            font-weight: 400;
        }

        .u-dropdown__caret {
            color: #9ca3af;
            font-size: 11px;
            transition: transform 0.2s;
        }

        .u-dropdown.open .u-dropdown__caret {
            transform: rotate(180deg);
        }

        /* Dropdown menu */
        .u-dropdown__menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 210px;
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
            overflow: hidden;
            z-index: 2000;
            animation: dropIn 0.15s ease;
        }

        @keyframes dropIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .u-dropdown.open .u-dropdown__menu {
            display: block;
        }

        .u-dropdown__menu-header {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
        }

        .u-dropdown__menu-name {
            font-weight: 600;
            font-size: 13px;
            color: #111827;
            margin-bottom: 2px;
        }

        .u-dropdown__menu-email {
            font-size: 11.5px;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .u-dropdown__menu ul {
            list-style: none;
            margin: 0;
            padding: 6px;
        }

        .u-dropdown__menu ul li a {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            text-decoration: none;
            border-radius: 7px;
            transition: background 0.12s;
        }

        .u-dropdown__menu ul li a i {
            width: 15px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }

        .u-dropdown__menu ul li a:hover {
            background: #f7f8f8;
            color: #111827;
        }

        .u-dropdown__menu ul li a:hover i {
            color: #374151;
        }

        .u-dropdown__menu-divider {
            height: 1px;
            background: #f3f4f6;
            margin: 4px 0;
        }

        .u-dropdown__menu ul li a.logout-link {
            color: #dc2626;
        }

        .u-dropdown__menu ul li a.logout-link i {
            color: #dc2626;
        }

        .u-dropdown__menu ul li a.logout-link:hover {
            background: #fef2f2;
        }
    </style>

    <header class="u-topnav">
        <a href="dashboard.php" class="u-topnav__brand">
            <span class="u-topnav__brand-mark"></span>
            LibraryMS
        </a>

        <ul class="u-topnav__nav">
            <li>
                <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                    Home
                </a>
            </li>
            <li>
                <a href="listed-books.php" class="<?php echo ($current_page == 'listed-books.php') ? 'active' : ''; ?>">
                    Browse Books
                </a>
            </li>
            <li>
                <a href="issued-books.php" class="<?php echo ($current_page == 'issued-books.php') ? 'active' : ''; ?>">
                    My Borrowings
                </a>
            </li>
        </ul>

        <div class="u-topnav__right">
            <!-- User Dropdown -->
            <div class="u-dropdown" id="userDropdown">
                <button class="u-dropdown__trigger" onclick="toggleUserDropdown(event)">
                    <?php if ($_header_img): ?>
                        <img src="<?php echo htmlentities($_header_img); ?>" class="u-dropdown__avatar" alt="Profile">
                    <?php else: ?>
                        <div class="u-dropdown__avatar-fallback"><?php echo htmlentities($_header_initials); ?></div>
                    <?php endif; ?>
                    <div class="u-dropdown__info">
                        <div class="u-dropdown__name"><?php echo htmlentities($_header_name); ?></div>
                        <div class="u-dropdown__role">Member</div>
                    </div>
                    <i class="fa fa-angle-down u-dropdown__caret"></i>
                </button>

                <div class="u-dropdown__menu">
                    <div class="u-dropdown__menu-header">
                        <div class="u-dropdown__menu-name"><?php echo htmlentities($_header_name); ?></div>
                        <div class="u-dropdown__menu-email"><?php echo htmlentities($_header_email); ?></div>
                    </div>
                    <ul>
                        <li><a href="my-profile.php"><i class="fa fa-user"></i> My Profile</a></li>
                        <li><a href="change-password.php"><i class="fa fa-lock"></i> Change Password</a></li>
                        <div class="u-dropdown__menu-divider"></div>
                        <li><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <script>
        function toggleUserDropdown(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('open');
        }
        document.addEventListener('click', function () {
            document.getElementById('userDropdown').classList.remove('open');
        });
    </script>

<?php else: ?>
    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="index.php#ulogin">Member Login</a></li>
                            <li><a href="signup.php">Register</a></li>
                            <li><a href="adminlogin.php">Admin Login</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>