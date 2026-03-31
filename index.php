<?php
session_start();
error_reporting(0);
include('includes/config.php');
if ($_SESSION['login'] != '') {    $_SESSION['login'] = '';
}
if (isset($_POST['login'])) 
{
    $email = $_POST['emailid'];    $password = md5($_POST['password']);    $sql = "SELECT EmailId,Password,StudentId,Status FROM tblstudents WHERE EmailId=:email and Password=:password";    $query = $dbh->prepare($sql);    $query->bindParam(':email', $email, PDO::PARAM_STR);    $query->bindParam(':password', $password, PDO::PARAM_STR);    $query->execute();    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) 
{
        foreach ($results as $result) {
            $_SESSION['stdid'] = $result->StudentId;            if ($result->Status == 1) 
{                $_SESSION['login'] = $_POST['emailid'];                echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";            }
            else {                echo "<script>alert('Your Account Has been blocked .Please contact admin');</script>";
            }        }
    }

    
else {        echo "<script>alert('Invalid Details');</script>";    }
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Library Management System | Member Login</title>
    <link href="assets/css/auth-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left" style="background: url('assets/img/libraryImg.jpg') no-repeat center center/cover;">
            <div class="auth-left-logo">
                LibraryMS
            </div>
            <div class="auth-left-content">
                <h1>Find your next great read</h1>
                <p>Locate books in just a few clicks<br>Borrow books seamlessly</p>
                <div class="auth-dots">
                    <span class="active"></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-top-right">
                <a href="adminlogin.php" class="btn-top">Admin Login</a>
            </div>
            
            <div class="auth-form-container">
                <h2>Welcome Back!</h2>
                <p>Sign in to your account</p>
                
                <form role="form" method="post">
                    <div class="form-group">
                        <label>Your Email</label>
                        <input class="form-control" type="text" name="emailid" placeholder="example@gmail.com" required autocomplete="off" />
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" placeholder="********" required autocomplete="off"  />
                    </div>
                    
                    <div class="form-actions">
                        <label><input type="checkbox"> Remember Me</label>
                        <a href="user-forgot-password.php">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" name="login" class="btn-primary">Login</button>
                    
        
                    
                    <div class="auth-footer">
                        Don't have an account? <a href="signup.php">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="assets/js/jquery-1.10.2.js"></script>
</body>
</html>
