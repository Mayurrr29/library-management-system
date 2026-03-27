<div class="navbar navbar-inverse set-radius-zero" >
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" >

                    <img src="assets/img/logo.png" />
                </a>

            </div>
<?php if ($_SESSION['login']) 
{
?> 
            <div class="right-div">
                <a href="logout.php" class="btn btn-danger pull-right">LOG ME OUT</a>
            </div>
            <?php
}?>

        </div>
    </div>
    <!-- LOGO HEADER END-->
<?php if ($_SESSION['login']) 
{
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

body {
    background-color: #f8f9fa;
    font-family: 'Inter', sans-serif;
    margin: 0;
    overflow-x: hidden;
}

/* Top Navbar Styles */
.user-topnav {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 80px;
    background-color: #fff;
    padding: 0 40px;
    z-index: 1000;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.user-topnav .brand-logo {
    font-size: 22px;
    font-weight: 800;
    color: #1a1a1a;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-right: 40px;
}

.user-topnav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
}

.user-topnav ul li {
    margin: 0 5px;
}

.user-topnav ul li a {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: #6c757d;
    font-weight: 600;
    font-size: 14px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.user-topnav ul li a i {
    font-size: 16px;
    margin-right: 8px;
}

.user-topnav ul li a:hover {
    color: #ff6a4a;
    background-color: #fff0ed;
}

.user-topnav ul li a.active {
    background-color: #ff6a4a;
    color: #fff;
    box-shadow: 0 4px 15px rgba(255, 106, 74, 0.3);
}

.user-topnav .nav-right {
    margin-left: auto;
    display: flex;
    align-items: center;
}

.user-topnav .nav-right .menu-split {
    width: 1px;
    height: 30px;
    background: #eaeaea;
    margin: 0 15px;
}

.user-dropdown {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.user-dropdown img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

/* Hide old header globally for logged in user */
.navbar-inverse { display: none !important; }
.menu-section { display: none !important; }

.footer-section { padding-left: 0; margin-top: 40px; }

/* Override Main Wrapper for all user pages */
.content-wrapper {
    margin-left: 0 !important;
    margin-top: 80px !important;
    padding: 0 !important;
    min-height: calc(100vh - 80px) !important;
    background: #f8f9fa !important;
    border: none !important;
}

/* General cleanups for other pages */
.panel { border-radius: 12px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.panel-heading { border-radius: 12px 12px 0 0 !important; background-color: #fff !important; font-weight: 700; }
</style>

<div class="user-topnav">
    <div class="brand-logo">The Books</div>
    <ul>
        <li><a href="dashboard.php" class="<?php echo($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="fa fa-home"></i> Discover</a></li>
        <li><a href="listed-books.php" class="<?php echo($current_page == 'listed-books.php') ? 'active' : ''; ?>"><i class="fa fa-th-large"></i> Category</a></li>
        <li><a href="issued-books.php" class="<?php echo($current_page == 'issued-books.php') ? 'active' : ''; ?>"><i class="fa fa-bookmark"></i> My Library</a></li>
    </ul>

    <div class="nav-right">
        <ul>
            <li><a href="my-profile.php" class="<?php echo($current_page == 'my-profile.php') ? 'active' : ''; ?>"><i class="fa fa-cog"></i> Profile Setting</a></li>
            <li><a href="change-password.php" class="<?php echo($current_page == 'change-password.php') ? 'active' : ''; ?>"><i class="fa fa-lock"></i> Password</a></li>
            <div class="menu-split"></div>
            <li><a href="logout.php"><i class="fa fa-sign-out"></i> Log out</a></li>
        </ul>
        <div class="menu-split"></div>
        <div class="user-dropdown">
            <?php
            $sid = $_SESSION['stdid'];
            $sql = "SELECT FullName FROM tblstudents WHERE StudentId=:sid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':sid', $sid, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            $name = $result->FullName ? $result->FullName : "User";
            ?>
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($name); ?>&background=2b4162&color=fff" alt="User">
            <span><?php echo htmlentities($name); ?> <i class="fa fa-angle-down" style="color:#aaa;"></i></span>
        </div>
    </div>
</div>
    <?php
}
else { ?>
        <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">                        
                          
      <li><a href="index.php">Home</a></li>
      <li><a href="index.php#ulogin">User Login</a></li>
                            <li><a href="signup.php">User Signup</a></li>
                         
                            <li><a href="adminlogin.php">Admin Login</a></li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php
}?>