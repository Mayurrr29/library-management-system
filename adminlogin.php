<?php
session_start();
error_reporting(0);
include('includes/config.php');
if ($_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT UserName,Password FROM admin WHERE UserName=:username and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Admin Loginn</title>
    <link href="assets/css/auth-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-left" style="background: url('assets/img/libraryImg.jpg') no-repeat center center/cover;">
            <div class="auth-left-logo">
                <i class="fa fa-book"></i> Library System
            </div>
            <div class="auth-left-content">
                <h1>Manage the Library Workspace</h1>
                <p>Welcome to the Administrative portal<br>Control and organize with ease</p>
                <div class="auth-dots">
                    <span></span>
                    <span></span>
                    <span class="active"></span>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-top-right">
                <a href="index.php" class="btn-top">User Login</a>
            </div>

            <div class="auth-form-container">
                <h2>Admin Login</h2>
                <p>Enter your credentials to manage the system</p>

                <form role="form" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input class="form-control" type="text" name="username" placeholder="admin" required
                            autocomplete="off" />
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" placeholder="********" required
                            autocomplete="off" />
                    </div>

                    <button type="submit" name="login" class="btn-primary" style="margin-top: 2rem;">Login
                        Dashboard</button>

                    <div class="auth-footer" style="margin-top: 2rem;">
                        Not an admin? <a href="index.php">Return to User Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-1.10.2.js"></script>
</body>

</html>