<?php
session_start();
error_reporting(0);
include('includes/config.php');

$login_error = '';
if ($_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}
if (isset($_POST['login'])) {
    $email    = trim($_POST['emailid']);
    $password = md5($_POST['password']);
    
    if (empty($email) || empty($_POST['password'])) {
        $login_error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_error = 'Please enter a valid email address.';
    } else {
        $sql = "SELECT EmailId, Password, StudentId, Status FROM tblstudents WHERE EmailId=:email AND Password=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email',    $email,    PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $_SESSION['stdid'] = $result->StudentId;
                if ($result->Status == 1) {
                    $_SESSION['login'] = $_POST['emailid'];
                    header('location:dashboard.php');
                    exit;
                } else {
                    $login_error = 'Your account has been blocked. Please contact the admin.';
                }
            }
        } else {
            $login_error = 'Invalid email or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="Library Management System - Member Login" />
    <title>Library Management System | Member Login</title>
    <link href="assets/css/auth-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        /* Inline validation enhancements */
        .form-group { margin-bottom: 18px; }
        .field-error {
            font-size: 12px;
            color: #dc2626;
            margin-top: 5px;
            display: none;
            font-weight: 500;
        }
        .field-error.show { display: block; }
        .form-control.invalid {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1) !important;
        }
        .form-control.valid {
            border-color: #16a34a !important;
        }
        .alert-server {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
            color: #b91c1c;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .pwd-toggle {
            position: relative;
        }
        .pwd-toggle input { padding-right: 42px !important; }
        .pwd-toggle .toggle-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 15px;
            transition: color 0.2s;
        }
        .pwd-toggle .toggle-icon:hover { color: #475569; }

        .btn-loading { opacity: 0.7; pointer-events: none; }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left" style="background: url('assets/img/libraryImg.jpg') no-repeat center center/cover;">
            <div class="auth-left-logo">LibraryMS</div>
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

                <?php if (!empty($login_error)): ?>
                    <div class="alert-server">
                        <i class="fa fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($login_error); ?>
                    </div>
                <?php endif; ?>
                
                <form role="form" method="post" id="loginForm" novalidate>
                    <div class="form-group">
                        <label>Your Email</label>
                        <input class="form-control" type="email" name="emailid" id="emailid"
                               placeholder="example@gmail.com" autocomplete="off"
                               value="<?php echo isset($_POST['emailid']) ? htmlspecialchars($_POST['emailid']) : ''; ?>" />
                        <span class="field-error" id="email_err">Please enter a valid email address.</span>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <div class="pwd-toggle">
                            <input class="form-control" type="password" name="password" id="password"
                                   placeholder="••••••••" autocomplete="off" />
                            <span class="toggle-icon" onclick="togglePwd('password', this)">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <span class="field-error" id="pwd_err">Password cannot be empty.</span>
                    </div>
                    
                    <div class="form-actions">
                        <label><input type="checkbox"> Remember Me</label>
                        <a href="user-forgot-password.php">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" name="login" class="btn-primary" id="loginBtn">Login</button>
                    
                    <div class="auth-footer">
                        Don't have an account? <a href="signup.php">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script>
    function togglePwd(inputId, icon) {
        var input = document.getElementById(inputId);
        var i = icon.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            i.className = 'fa fa-eye-slash';
        } else {
            input.type = 'password';
            i.className = 'fa fa-eye';
        }
    }

    function validateEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    $('#emailid').on('blur input', function() {
        var v = $(this).val().trim();
        if (!v || !validateEmail(v)) {
            $(this).addClass('invalid').removeClass('valid');
            $('#email_err').addClass('show');
        } else {
            $(this).addClass('valid').removeClass('invalid');
            $('#email_err').removeClass('show');
        }
    });

    $('#password').on('blur input', function() {
        if (!$(this).val()) {
            $(this).addClass('invalid').removeClass('valid');
            $('#pwd_err').addClass('show');
        } else {
            $(this).addClass('valid').removeClass('invalid');
            $('#pwd_err').removeClass('show');
        }
    });

    $('#loginForm').on('submit', function(e) {
        var email = $('#emailid').val().trim();
        var pwd   = $('#password').val();
        var valid = true;

        if (!email || !validateEmail(email)) {
            $('#emailid').addClass('invalid'); $('#email_err').addClass('show');
            valid = false;
        }
        if (!pwd) {
            $('#password').addClass('invalid'); $('#pwd_err').addClass('show');
            valid = false;
        }
        if (!valid) { e.preventDefault(); return; }

        $('#loginBtn').addClass('btn-loading').text('Signing in...');
    });
    </script>
</body>
</html>
